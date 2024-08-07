<?php

namespace App\Http\Controllers;
use App\Models\Client;
use App\Models\Billing;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\Trunk;
use App\Models\BlockByCountry;
use App\Models\BlockByCode;
use App\Models\RateTable;
use App\Models\VendorTrunk;
use App\Models\CustomerTrunk;
use App\Models\DownloadProcess;
use App\Models\VendorDownloadProcess;
use App\Models\Country;
use App\Models\Codes;
use App\Models\Preference;
use Illuminate\Support\Arr;
use Rap2hpoutre\FastExcel\FastExcel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;
use App\Jobs\UpdateAccountCallHistory;

class ClientController extends Controller
{
 /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Client::query('');
            $getUsers = $this->getUser();
            if(Auth::user()->role != 'Admin'){
                $data = $data->whereIn('lead_owner',$getUsers);
            }
            return Datatables::of($data)
            ->addColumn('status', function($row){
                return  $row->status == 0 ? __('Active') : __('Inactive');

            })
            ->addColumn('created_at', function($row){
                return Carbon::parse($row->created_at)->format('d/m/Y H:i:s');

            })
            ->addColumn('updated_at', function($row){
                return Carbon::parse($row->updated_at)->format('d/m/Y H:i:s');

            })
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $customer ="";
                    $Vendor = "";
                    // if($row->customer == 1){
                    //     $customer = '<a href="'.route('client.customer',$row->id).'" class=" btn btn-warning btn-sm Customer"  data-id ="'.$row->id.'"><i class="fa fa-user"></i></a> ';
                    // }
                    // if($row->Vendor == 1){
                    //     $Vendor = '<a href="'.route('client.vendor',$row->id).'" class=" btn btn-info btn-sm Vendor"  data-id ="'.$row->id.'"><i class="fab fa-slideshare"></i></a> ';
                    // }
                    $btn = '<a href="'.route('client.edit',$row->id).'" class=" btn btn-primary btn-sm Edit"  data-id ="'.$row->id.'">Edit</a>&nbsp;&nbsp;';

                        return $btn.$customer.$Vendor;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('client.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function create()
     {
        $users= User::query('');
        if(Auth::user()->role == 'Super Admin'){
            $users = $users->where('role','!=','Admin')->where('role','!=','Super Admin');
        }
        if(Auth::user()->role == 'NOC Admin'){
            $users = $users->where('role','!=','Admin')->where('role','!=','Super Admin')->where('role','!=','NOC Admin')->where('role','!=','Rate Admin')->where('role','!=','Sales Admin')->where('role','!=','Billing Admin')->where('role','!=','Billing Executive')->where('role','!=','Rate Executive')->where('role','!=','Sales Executive')->where('parent_id',Auth::id());
        }
        if(Auth::user()->role == 'Rate Admin'){
            $users = $users->where('role','!=','Admin')->where('role','!=','Super Admin')->where('role','!=','NOC Admin')->where('role','!=','Rate Admin')->where('role','!=','Sales Admin')->where('role','!=','Billing Admin')->where('role','!=','NOC Executive')->where('role','!=','Sales Executive')->where('role','!=','Billing Executive')->where('parent_id',Auth::id());
        }
        if(Auth::user()->role == 'Sales Admin'){
            $users = $users->where('role','!=','Admin')->where('role','!=','Super Admin')->where('role','!=','NOC Admin')->where('role','!=','Rate Admin')->where('role','!=','Sales Admin')->where('role','!=','Billing Admin')->where('role','!=','NOC Executive')->where('role','!=','Rate Executive')->where('role','!=','Billing Executive')->where('parent_id',Auth::id());
        }
        if(Auth::user()->role == 'Billing Admin'){
            $users = $users->where('role','!=','Admin')->where('role','!=','Super Admin')->where('role','!=','NOC Admin')->where('role','!=','Rate Admin')->where('role','!=','Sales Admin')->where('role','!=','Billing Admin')->where('role','!=','NOC Executive')->where('role','!=','Rate Executive')->where('role','!=','Sales Executive')->where('parent_id',Auth::id());
        }
        if(Auth::user()->role == 'NOC Executive' || Auth::user()->role == 'Rate Executive' || Auth::user()->role == 'Sales Executive' || Auth::user()->role == 'Billing Executive' ){
            $users = $users->where('id',Auth::id());
        }
        $account_owner = $users->get();
        return view('client.create',compact('account_owner'));
     }


     public function store(Request $request)
     {
        $rules = array(

            'account_name'=>'required',
            'country'=>'required',
        );

        if(!empty($request->billing_status) && $request->billing_status == 'active'){
            $rules['billing_class'] = 'required';
            $rules['billing_type'] = 'required';
            $rules['billing_timezone'] = 'required';
            $rules['billing_startdate'] = 'required';
            $rules['billing_cycle'] = 'required';
        }
        if(!empty($request->billing_cycle)){
            if($request->billing_cycle == 'in_specific_days' || $request->billing_cycle == 'monthly_anniversary' || $request->billing_cycle == 'weekly'){
                if($request->billing_cycle == 'weekly'){
                    $rules['billing_cycle_startday'] = 'required';
                }elseif ($request->billing_cycle == 'in_specific_days') {
                    $rules['billing_cycle_startday_for_days'] = 'required';
                }elseif ($request->billing_cycle == 'monthly_anniversary') {
                    $rules['billing_cycle_startday_for_monthly'] = 'required';
                }
            }
        }

        $request['reseller'] = $request->reseller ? $request->reseller : 2;
        $request['Vendor'] = $request->Vendor ? $request->Vendor : 2;
        $request['customer'] = $request->customer ? $request->customer : 2;
        $request['billing_status'] =  $request->billing_status ? $request->billing_status : 'inactive';
        $request['status'] = $request->status ? $request->status : 1;

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
        {
            $response = \Response::json([
                    'success' => false,
                    'errors' => $validator->getMessageBag()->toArray()
                ]);
            return $response;
        }

        $olddata ="";
        if(!empty( $request->customer_authentication_value)) {
            $olddata  = Client::where([['customer_authentication_value', $request->customer_authentication_value],['id',"!=",$request->id]])->first();
        }
        elseif(!empty( $request->customer_authentication_value) && !empty( $request->vendor_authentication_value)) {

            $olddata  = Client::where([['customer_authentication_value', $request->customer_authentication_value],['vendor_authentication_value', $request->vendor_authentication_value],['id',"!=",$request->id]])->first();

        }
        else {
            $olddata  = Client::where([['vendor_authentication_value', $request->vendor_authentication_value],['id',"!=",$request->id]])->first();
        }

        $user = Client::create($request->all());

        if(!empty($olddata)){
            if($user != $olddata){
                $Csv = new UpdateAccountCallHistory($user,$olddata);
                dispatch($Csv);
            }
        }

        if(!empty($user)){
            if(!empty($request->billing_status) && $request->billing_status == 'active'){
                $billingdata["account_id"] = $user->id;
                $billingdata["billing_class"] = $request->billing_class;
                $billingdata["billing_type"] = $request->billing_type;
                $billingdata["billing_timezone"] = $request->billing_timezone;
                $billingdata["billing_startdate"] = $request->billing_startdate;
                $billingdata["billing_cycle"] = $request->billing_cycle;
                if($request->billing_cycle == 'in_specific_days'){
                    $billingdata["billing_cycle_startday"] = $request->billing_cycle_startday_for_days;
                }elseif ($request->billing_cycle == 'monthly_anniversary') {
                    $billingdata["billing_cycle_startday"] = $request->billing_cycle_startday_for_monthly;
                }elseif ($request->billing_cycle == 'weekly') {
                    $billingdata["billing_cycle_startday"] = $request->billing_cycle_startday;
                }else{
                    $billingdata["billing_cycle_startday"] = NULL;
                }
                $billingdata["auto_pay"] = $request->auto_pay;
                $billingdata["auto_pay_method"] = $request->auto_pay_method;
                $billingdata["send_invoice_via_email"] = $request->send_invoice_via_email;
                $billingdata["next_invoice_date"] = $request->next_invoice_date;
                $billingdata["next_charge_date"] = $request->next_charge_date;

                if(Billing::create($billingdata)){
                    return response()->json(['message' =>  __('Account Created Successfully'),'success'=>true,'redirect_url' => route('client.index')]);
                }
            }
        }
        return response()->json(['message' =>  __('Account Created Successfully'),'success'=>true,'redirect_url' => route('client.index')]);
     }
     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $users= User::query('');
        if(Auth::user()->role == 'Super Admin'){
            $users = $users->where('role','!=','Admin')->where('role','!=','Super Admin');
        }
        if(Auth::user()->role == 'NOC Admin'){
            $users = $users->where('role','!=','Admin')->where('role','!=','Super Admin')->where('role','!=','NOC Admin')->where('role','!=','Rate Admin')->where('role','!=','Sales Admin')->where('role','!=','Billing Admin')->where('role','!=','Billing Executive')->where('role','!=','Rate Executive')->where('role','!=','Sales Executive')->where('parent_id',Auth::id());
        }
        if(Auth::user()->role == 'Rate Admin'){
            $users = $users->where('role','!=','Admin')->where('role','!=','Super Admin')->where('role','!=','NOC Admin')->where('role','!=','Rate Admin')->where('role','!=','Sales Admin')->where('role','!=','Billing Admin')->where('role','!=','NOC Executive')->where('role','!=','Sales Executive')->where('role','!=','Billing Executive')->where('parent_id',Auth::id());
        }
        if(Auth::user()->role == 'Sales Admin'){
            $users = $users->where('role','!=','Admin')->where('role','!=','Super Admin')->where('role','!=','NOC Admin')->where('role','!=','Rate Admin')->where('role','!=','Sales Admin')->where('role','!=','Billing Admin')->where('role','!=','NOC Executive')->where('role','!=','Rate Executive')->where('role','!=','Billing Executive')->where('parent_id',Auth::id());
        }
        if(Auth::user()->role == 'Billing Admin'){
            $users = $users->where('role','!=','Admin')->where('role','!=','Super Admin')->where('role','!=','NOC Admin')->where('role','!=','Rate Admin')->where('role','!=','Sales Admin')->where('role','!=','Billing Admin')->where('role','!=','NOC Executive')->where('role','!=','Rate Executive')->where('role','!=','Sales Executive')->where('parent_id',Auth::id());
        }
        if(Auth::user()->role == 'NOC Executive' || Auth::user()->role == 'Rate Executive' || Auth::user()->role == 'Sales Executive' || Auth::user()->role == 'Billing Executive' ){
            $users = $users->where('id',Auth::id());
        }
        $data = $users->get();
        $user = Client::find($id);
        $billingdata = Billing::where('account_id',$user->id)->first();
        return view('client.edit',compact('user','data','billingdata'));
    }


    public function update(Request $request, $id)
    {
        if(!empty($request->id)){

            $rules = array(

                'account_name'=>'required',
                'country'=>'required',
            );
            if($request->customer_authentication_rule == 6){
                $rules['customer_authentication_value'] = 'required';
            }
            if($request->vendor_authentication_rule == 6){
                $rules['vendor_authentication_value'] = 'required';
            }
            if(!empty($request->billing_status) && $request->billing_status == 'active'){
                $rules['billing_class'] = 'required';
                $rules['billing_type'] = 'required';
                $rules['billing_timezone'] = 'required';
                $rules['billing_startdate'] = 'required';
                $rules['billing_cycle'] = 'required';
            }
            if(!empty($request->billing_cycle)){
                if($request->billing_cycle == 'in_specific_days' || $request->billing_cycle == 'monthly_anniversary' || $request->billing_cycle == 'weekly'){
                    if($request->billing_cycle == 'weekly'){
                        $rules['billing_cycle_startday'] = 'required';
                    }elseif ($request->billing_cycle == 'in_specific_days') {
                        $rules['billing_cycle_startday_for_days'] = 'required';
                    }elseif ($request->billing_cycle == 'monthly_anniversary') {
                        $rules['billing_cycle_startday_for_monthly'] = 'required';
                    }
                }
            }
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
        {
            $response = \Response::json([
                    'success' => false,
                    'errors' => $validator->getMessageBag()->toArray()
                ]);
            return $response;
        }

            $olddata ="";
            if(!empty( $request->customer_authentication_value)) {
                $olddata  = Client::where([['customer_authentication_value', $request->customer_authentication_value],['id',"!=",$request->id]])->first();
            }
            elseif(!empty( $request->customer_authentication_value) && !empty( $request->vendor_authentication_value)) {

                $olddata  = Client::where([['customer_authentication_value', $request->customer_authentication_value],['vendor_authentication_value', $request->vendor_authentication_value],['id',"!=",$request->id]])->first();

            }
            else {
                $olddata  = Client::where([['vendor_authentication_value', $request->vendor_authentication_value],['id',"!=",$request->id]])->first();
            }


            // if(empty($data))
            // {
                $request['reseller'] = $request->reseller ? $request->reseller : 2;
                $request['Vendor'] = $request->Vendor ? $request->Vendor : 2;
                $request['customer'] = $request->customer ? $request->customer : 2;
                $request['billing_status'] =  $request->billing_status ? $request->billing_status : 'inactive';
                // dd($request->all());
                // $now = \Carbon\Carbon::now();
                $user =  Client::updateOrCreate([
                    'id'   => $request->id,
                ],$request->all());

                if(!empty($olddata)){
                    if($user->id != $olddata->id){
                        $Csv = new UpdateAccountCallHistory($user,$olddata);
                        dispatch($Csv);
                    }
                }

                if(!empty($request->billing_status) && $request->billing_status == 'active'){
                    $billingdata["account_id"] = $user->id;
                    $billingdata["billing_class"] = $request->billing_class;
                    $billingdata["billing_type"] = $request->billing_type;
                    $billingdata["billing_timezone"] = $request->billing_timezone;
                    $billingdata["billing_startdate"] = $request->billing_startdate;
                    $billingdata["billing_cycle"] = $request->billing_cycle;
                    if($request->billing_cycle == 'in_specific_days'){
                        $billingdata["billing_cycle_startday"] = $request->billing_cycle_startday_for_days;
                    }elseif ($request->billing_cycle == 'monthly_anniversary') {
                        $billingdata["billing_cycle_startday"] = $request->billing_cycle_startday_for_monthly;
                    }elseif ($request->billing_cycle == 'weekly') {
                        $billingdata["billing_cycle_startday"] = $request->billing_cycle_startday;
                    }else{
                        $billingdata["billing_cycle_startday"] = NULL;
                    }
                    $billingdata["auto_pay"] = $request->auto_pay;
                    $billingdata["auto_pay_method"] = $request->auto_pay_method;
                    $billingdata["send_invoice_via_email"] = $request->send_invoice_via_email;
                    $billingdata["last_invoice_date"] = $request->last_invoice_date;
                    $billingdata["next_invoice_date"] = $request->next_invoice_date;
                    $billingdata["last_charge_date"] = $request->last_charge_date;
                    $billingdata["next_charge_date"] = $request->next_charge_date;
                    $billingdata["outbound_discount_plan"] = $request->outbound_discount_plan;
                    $billingdata["inbound_discount_plan"] = $request->inbound_discount_plan;
                    Billing::updateOrCreate(['id' => $request->billing_id],$billingdata);
                }

                return response()->json(['message' =>  __('Updated Successfully'),'data' => $user,'success'=>true,'redirect_url' => route('client.index')]);


            // }

            // else
            // {
            //     return response()->json(['success' => false,'errors' => ['Name is used only one time']]);
            // }




    }

    public function getUser()
    {
        $users = User::query('');
        if(Auth::user()->role == 'Super Admin'){

            $users = $users->where('role','!=','Admin')->where('role','!=','Super Admin');
        }
        if(Auth::user()->role == 'NOC Admin'){
            $users = $users->where('role','!=','Admin')->where('role','!=','Super Admin')->where('role','!=','NOC Admin')->where('role','!=','Rate Admin')->where('role','!=','Sales Admin')->where('role','!=','Billing Admin')->where('role','!=','Billing Executive')->where('role','!=','Rate Executive')->where('role','!=','Sales Executive')->where('parent_id',Auth::id());
        }
        if(Auth::user()->role == 'Rate Admin'){
            $users = $users->where('role','!=','Admin')->where('role','!=','Super Admin')->where('role','!=','NOC Admin')->where('role','!=','Rate Admin')->where('role','!=','Sales Admin')->where('role','!=','Billing Admin')->where('role','!=','NOC Executive')->where('role','!=','Sales Executive')->where('role','!=','Billing Executive')->where('parent_id',Auth::id());
        }
        if(Auth::user()->role == 'Sales Admin'){
            $users = $users->where('role','!=','Admin')->where('role','!=','Super Admin')->where('role','!=','NOC Admin')->where('role','!=','Rate Admin')->where('role','!=','Sales Admin')->where('role','!=','Billing Admin')->where('role','!=','NOC Executive')->where('role','!=','Rate Executive')->where('role','!=','Billing Executive')->where('parent_id',Auth::id());
        }
        if(Auth::user()->role == 'Billing Admin'){
            $users = $users->where('role','!=','Admin')->where('role','!=','Super Admin')->where('role','!=','NOC Admin')->where('role','!=','Rate Admin')->where('role','!=','Sales Admin')->where('role','!=','Billing Admin')->where('role','!=','NOC Executive')->where('role','!=','Rate Executive')->where('role','!=','Sales Executive')->where('parent_id',Auth::id());
        }
        if(Auth::user()->role == 'NOC Executive' || Auth::user()->role == 'Rate Executive' || Auth::user()->role == 'Sales Executive' || Auth::user()->role == 'Billing Executive' ){
            $users = $users->where('id',Auth::id());
        }
        $users = $users->get();
        $user_ids = [];
        if($users->isNotEmpty()){
            foreach ($users as $user) {
                $user_ids[] = $user->id;
            }
        }
        return $user_ids;
    }

    public function customer(Request $request)
    {
        $clients = Client::where("customer", "=",1)->get();
        $customer =  CustomerTrunk::where('customer_id',$request->id)->get();
        return view('client.customer.index',compact('customer','clients'));
    }
    public function customers(Request $request)
    {
        if($request->name == "Customer Rate"){

            $vender_trunks = CustomerTrunk::where('customer_id',$request->id)->get();
            $trunks =array();
            if(!empty($vender_trunks)){
                foreach ($vender_trunks as $key => $value) {
                    $trunks[] = Trunk::where('id',$value->trunkid)->first();
                }
            }
            return view('client.customer.customer_rate',compact('trunks'));
        }
        if($request->name == "Settings"){
            $value = $request->id;
            $trunks = Trunk::where('status', 1)->with(['customers' => function($q) use($value) {
                $q->where('customer_id', '=', $value); // '=' is optional
            }])->get();
            $data =array();
            foreach ($trunks as  $trunk) {
                if(!$trunk->customers->isEmpty()){
                    // $rate[]  = RateTable::where("id",$trunk->customers[0]->rate_table_id)->select('name')->get();
                    $data[] = $trunk->customers[0];
                }
            }
            $rates =  RateTable::select('*')->get();
            return view('client.customer.setting',compact('trunks','data','rates'));
        }
        if($request->name == "Download Rate Sheet"){
            $vender_trunks = CustomerTrunk::where('customer_id',$request->id)->get();
            $trunks =array();
            if(!empty($vender_trunks)){
                foreach ($vender_trunks as $key => $value) {
                    $trunks[] = Trunk::where('id',$value->trunkid)->where('status', 1)->first();
                }
            }

            $owners = Client::leftjoin('users','users.id','=','clients.lead_owner')->get();
            $owners = $owners->unique('lead_owner');
            $clients = Client::where([["customer", "=", 1],["id", "!=",$request->id ]])->get();
            return view('client.customer.download',compact('trunks','owners','clients'));
        }
        if($request->name == "History"){
            return view('client.customer.history');
        }
    }

    public function customertrunk(Request $request){

        foreach($request->CustomerTrunk as $trunk){
            if(!empty($trunk['status']) == 1){
                $validator = Validator::make($trunk, [
                    'prefix' => ['required'],
                    'codedeck'=>['required'],
                    'rate_table_id'=>['required'],
                ]);

                if ($validator->fails())
                {
                    $response = \Response::json([
                            'success' => false,
                            'errors' => $validator->getMessageBag()->toArray()
                        ]);
                    return $response;
                }
                $data['prefix'] = $trunk['prefix'] ?? "";
                $data['status'] = $trunk['status'] ?? "";
                $data['customer_id'] = $request->id ?? "";
                $data['codedeck'] = $trunk['codedeck'] ?? "" ;
                $data['rate_table_id'] = $trunk['rate_table_id'] ?? "" ;
                $data['prefix_cdr'] = $trunk['prefix_cdr'] ?? "0";
                $data['includePrefix'] = $trunk['includePrefix'] ?? "0";
                $data['routine_plan_status'] = $trunk['routine_plan_status'] ?? "0";
                $data['trunkid'] = $trunk['trunkid'];
                CustomerTrunk::updateOrCreate(['id' => $trunk['customer_trunk_id']],$data);
            }
            if(empty($trunk['status'])){
                CustomerTrunk::where('id' , $trunk['customer_trunk_id'])->delete();
            }
        }
        return response()->json(['message' =>  'Update sucessfully']);
    }

    public function vendor(Request $request)
    {
        $vendor =  VendorTrunk::where('vendor_id',$request->id)->get();
        $clients = Client::where("Vendor", "=",1)->get();
        return view('client.vendor.index',compact('vendor','clients'));
    }
    public function vendors(Request $request){
        if($request->name == "Vendor Rate"){
            $country = Country::query('')->get();
            $vender_trunks = VendorTrunk::where('vendor_id',$request->id)->get();
            $trunks =array();
            if(!empty($vender_trunks)){
                foreach ($vender_trunks as $key => $value) {
                    $trunks[] = Trunk::where('id',$value->trunkid)->where('status', 1)->first();
                }
            }


            return view('client.vendor.vendor_rate',compact('trunks','country'));
        }
        if($request->name == "Settings"){

            $value = $request->id;
            $trunks = Trunk::where('status', 1)->with(['vendors' => function($q) use($value) {
                $q->where('vendor_id', '=', $value); // '=' is optional
            }])->get();
            return view('client.vendor.setting',compact('trunks'));
        }
        if($request->name == "Vender Rate Download"){

            $vender_trunks = VendorTrunk::where('vendor_id',$request->id)->get();
            $trunks =array();
            if(!empty($vender_trunks)){
                foreach ($vender_trunks as $key => $value) {
                    $trunks[] = Trunk::where('id',$value->trunkid)->where('status', 1)->first();
                }
            }
            $clients = Client::where([["Vendor", "=",1],["customer", "=",1],["id", "!=",$request->id ]])->get();
            return view('client.vendor.download',compact('trunks','clients'));
        }
        if($request->name == "Vendor Rate History"){
            return view('client.vendor.history');
        }
        if($request->name == "Blocking"){
            return view('client.vendor.blocking ');
        }

        if($request->name == "Country"){
            $country = Country::query('')->get();
            $vender_trunks = VendorTrunk::where('vendor_id',$request->id)->get();
            $trunks =array();
            if(!empty($vender_trunks)){
                foreach ($vender_trunks as $key => $value) {
                    $trunks[] = Trunk::where('id',$value->trunkid)->where('status', 1)->first();
                }
            }
            return view('client.vendor.country ',compact('trunks','country'));
         }
         if($request->name == "Code"){
            $country = Country::query('')->get();
            $vender_trunks = VendorTrunk::where('vendor_id',$request->id)->get();
            $trunks =array();
            if(!empty($vender_trunks)){
                foreach ($vender_trunks as $key => $value) {
                    $trunks[] = Trunk::where('id',$value->trunkid)->where('status', 1)->first();
                }
            }
            return view('client.vendor.code ',compact('trunks','country'));
         }

        if($request->name == "Preference"){
            $country = Country::query('')->get();
            $vender_trunks = VendorTrunk::where('vendor_id',$request->id)->get();
            $trunks =array();
            if(!empty($vender_trunks)){
                foreach ($vender_trunks as $key => $value) {
                    $trunks[] = Trunk::where('id',$value->trunkid)->where('status', 1)->first();
                }
            }

            return view('client.vendor.preference',compact('trunks','country'));
        }
    }
    public function vendortrunk(Request $request){
        foreach($request->VendorTrunk as $trunk){
           if(!empty($trunk['status']) == 1){
            $validator = Validator::make($trunk, [
                'prefix' => ['required'],
                'codedeck'=>['required'],
            ]);

            if ($validator->fails())
            {
                $response = \Response::json([
                        'success' => false,
                        'errors' => $validator->getMessageBag()->toArray()
                    ]);
                return $response;
            }
            $data['prefix'] = $trunk['prefix'] ?? "";
            $data['status'] = $trunk['status'] ?? "";
            $data['vendor_id'] = $request->id ?? "";
            $data['codedeck'] = $trunk['codedeck'] ?? "" ;
            $data['prefix_cdr'] = $trunk['prefix_cdr'] ?? "0";
            $data['trunkid'] = $trunk['trunkid'];
            VendorTrunk::updateOrCreate(['id' => $trunk['vendor_trunk_id']],$data);
           }
           if(empty($trunk['status'])){
            VendorTrunk::where('id' , $trunk['vendor_trunk_id'])->delete();
           }
        }
        return response()->json(['message' =>  'Update sucessfully']);
    }

    public function updatecodeckid(Request $request)
    {
        VendorTrunk::where("id",$request->id)->update(['codedeck' => $request->codedeckid ]);
        return response()->json(['message' =>  'change sucessfully']);
    }

    public function fetchRateTable(Request $request)
    {
        $data['rate_table']= RateTable::where("codeDeckId",$request->codedeckid)->get();
        return response()->json($data);
    }

    public function owners_customer(Request $request){
        if($request->owner_id == 0){
            $clients = Client::where([["customer", "=",1],["id", "!=",$request->id ]])->get();
        }else{
            $clients = Client::where([["customer", "=",1],["id", "!=",$request->id ],["lead_owner", "=",$request->owner_id ]])->get();
        }
        return response()->json($clients);
    }

    public function process_download(Request $request){
        $validator = Validator::make($request->all(), [
            'filetype' => 'required',
            'Format'=>'required',
            'Timezones'=>'required',
            'Trunks'=>'required',
        ]);

            if ($validator->fails())
            {
                $response = \Response::json([
                        'success' => false,
                        'errors' => $validator->getMessageBag()->toArray()
                    ]);
                return $response;
            }

            $data = array();
            if(!empty($request->Trunks)){
                $data['trunks'] = json_encode($request->Trunks);
            }else{
                $data['trunks'] = json_encode([]);
            }
            if(!empty($request->Timezones)){
                $data['timezones'] = json_encode($request->Timezones);
            }else{
                $data['timezones'] = json_encode([]);
            }

        if(!empty($request->customer)){
            foreach ($request->customer as $key => $value) {
                $clients = Client::where("id", "=",$value)->first();
                $data['name'] =  $clients->company ?? '';
                $data['format'] = $request->Format ?? '';
                $data['filetype'] = $request->filetype ?? '';
                $data['effective'] = $request->Effective ?? '';
                $data['customDate'] = $request->CustomDate ?? '';
                $data['isMerge'] = $request->isMerge ?? '';
                $data['sendMail'] = $request->sendMail ?? '';
                $data['type'] = $request->type ?? '';
                $data['account_owners'] = $request->account_owners ?? '';
                $data['client_id'] = $value;
                $data['created_by'] = Auth::user()->id ?? '';
                DownloadProcess::create($data);
            }
        }
            return response()->json(['message' =>  'File is added to queue for processing. You will be notified once file creation is completed','success'=>true]);
    }

    public function vendor_process_download(Request $request){
        $validator = Validator::make($request->all(), [
            'filetype' => 'required',
            'Format'=>'required',
            'Timezones'=>'required',
            'Trunks'=>'required',
        ]);

            if ($validator->fails())
            {
                $response = \Response::json([
                        'success' => false,
                        'errors' => $validator->getMessageBag()->toArray()
                    ]);
                return $response;
            }

            $data = array();
            if(!empty($request->Trunks)){
                $data['trunks'] = json_encode($request->Trunks);
            }else{
                $data['trunks'] = json_encode([]);
            }
            if(!empty($request->Timezones)){
                $data['timezones'] = json_encode($request->Timezones);
            }else{
                $data['timezones'] = json_encode([]);
            }

        if(!empty($request->vendor)){
            foreach ($request->vendor as $key => $value) {
                $clients = Client::where("id", "=",$value)->first();
                $data['name'] =  $clients->company ?? '';
                $data['format'] = $request->Format ?? '';
                $data['filetype'] = $request->filetype ?? '';
                $data['effective'] = $request->Effective ?? '';
                $data['customDate'] = $request->CustomDate ?? '';
                $data['isMerge'] = $request->isMerge ?? '';
                $data['sendMail'] = $request->sendMail ?? '';
                $data['type'] = "Download";
                $data['account_owners'] = $request->account_owners ?? '';
                $data['client_id'] = $value;
                $data['created_by'] = Auth::user()->id ?? '';
                VendorDownloadProcess::create($data);
            }
        }
            return response()->json(['message' =>  'File is added to queue for processing. You will be notified once file creation is completed','success'=>true]);
    }


    public function history_detail(Request $request){

            $downloads = DownloadProcess::leftjoin('users','users.id','=','download_processes.created_by')->select('download_processes.*','users.name as uname')->where('download_processes.id',$request->id)->first();
            $clients = Client::where("id", "=",$request->client_id)->first();
            if(!empty($downloads->trunks)){
                foreach (json_decode($downloads->trunks) as $trunk){
                    $trunks[] = Trunk::where("id", "=",$trunk)->first();
                }
            }
        return view('client.customer.detail',compact('downloads','clients','trunks'));
    }

    public function vendor_history_detail(Request $request){

        $downloads = VendorDownloadProcess::leftjoin('users','users.id','=','vendor_download_processes.created_by')->select('vendor_download_processes.*','users.name as uname')->where('vendor_download_processes.id',$request->id)->first();
        $clients = Client::where("id", "=",$request->client_id)->first();
        if(!empty($downloads->trunks)){
            foreach (json_decode($downloads->trunks) as $trunk){
                $trunks[] = Trunk::where("id", "=",$trunk)->first();
            }
        }
    return view('client.vendor.detail',compact('downloads','clients','trunks'));
}

    public function history_export_xlsx(Request $request){
        $downloads = DownloadProcess::leftjoin('users','users.id','=','download_processes.created_by')->select('download_processes.*','users.name as uname')->where('download_processes.client_id',$request->id)->get();
        $list =array();
        foreach ( $downloads as $key => $value) {
            $timezone = '';
            $effective = '';
            if(!empty($value->timezones)){
                foreach (json_decode($value->timezones )as $row) {
                    if($row == 1){
                        $timezone  .= "(default)";
                    }
                }
            }

            if(!empty($value->effective)){
                if($value->effective == "CustomDate"){
                    $effective = "($value->customDate)";
                }else{
                    $effective = "($value->effective)";
                }
            }

            if(!empty($value->client_id)){
                $client = Client::where("id", "=",$value->client_id)->first();
            }

            $data =array();
            $data['title'] =  $client->company.(!empty($value->format) ? "($value->format)" : "").$effective.$timezone;
            $data['created_at'] =  $value->created_at->format('d-m-Y H:i:s');
            $list[]= $data;
        }


        return (new FastExcel($list))->download('Customer_history.xlsx');
    }

    public function history_export_csv(Request $request){
        $downloads = DownloadProcess::leftjoin('users','users.id','=','download_processes.created_by')->select('download_processes.*','users.name as uname')->where('download_processes.client_id',$request->id)->get();
        $list =array();
        foreach ( $downloads as $key => $value) {
            $timezone = '';
            $effective = '';
            if(!empty($value->timezones)){
                foreach (json_decode($value->timezones )as $row) {
                    if($row == 1){
                        $timezone  .= "(default)";
                    }
                }
            }

            if(!empty($value->effective)){
                if($value->effective == "CustomDate"){
                    $effective = "($value->customDate)";
                }else{
                    $effective = "($value->effective)";
                }
            }

            if(!empty($value->client_id)){
                $client = Client::where("id", "=",$value->client_id)->first();
            }

            $data =array();
            $data['title'] =  $client->company.(!empty($value->format) ? "($value->format)" : "").$effective.$timezone;
            $data['created_at'] =  $value->created_at->format('d-m-Y H:i:s');
            $list[]= $data;
        }
        return (new FastExcel($list))->download('Customer_history.csv');
    }


    public function vendor_history_export_xlsx(Request $request){
        $downloads = VendorDownloadProcess::leftjoin('users','users.id','=','vendor_download_processes.created_by')->select('vendor_download_processes.*','users.name as uname')->where('vendor_download_processes.client_id',$request->id)->get();
        $list =array();
        foreach ( $downloads as $key => $value) {
            $timezone = '';
            $effective = '';
            if(!empty($value->timezones)){
                foreach (json_decode($value->timezones )as $row) {
                    if($row == 1){
                        $timezone  .= "(default)";
                    }
                }
            }

            if(!empty($value->effective)){
                if($value->effective == "CustomDate"){
                    $effective = "($value->customDate)";
                }else{
                    $effective = "($value->effective)";
                }
            }
            if(!empty($value->client_id)){
                $client = Client::where("id", "=",$value->client_id)->first();
            }

            $data =array();
            $data['title'] =  $client->company.(!empty($value->format) ? "($value->format)" : "").$effective.$timezone;
            $data['created_at'] =  $value->created_at->format('d-m-Y H:i:s');
            $list[]= $data;
        }
        return (new FastExcel($list))->download('Vendor_history.xlsx');
    }

    public function vendor_history_export_csv(Request $request){
        $downloads = VendorDownloadProcess::leftjoin('users','users.id','=','vendor_download_processes.created_by')->select('vendor_download_processes.*','users.name as uname')->where('vendor_download_processes.client_id',$request->id)->get();
        $list =array();
        foreach ( $downloads as $key => $value) {
            $timezone = '';
            $effective = '';
            if(!empty($value->timezones)){
                foreach (json_decode($value->timezones )as $row) {
                    if($row == 1){
                        $timezone  .= "(default)";
                    }
                }
            }

            if(!empty($value->effective)){
                if($value->effective == "CustomDate"){
                    $effective = "($value->customDate)";
                }else{
                    $effective = "($value->effective)";
                }
            }

            if(!empty($value->client_id)){
                $client = Client::where("id", "=",$value->client_id)->first();
            }

            $data =array();
            $data['title'] =  $client->company.(!empty($value->format) ? "($value->format)" : "").$effective.$timezone;
            $data['created_at'] =  $value->created_at->format('d-m-Y H:i:s');
            $list[]= $data;
        }
        return (new FastExcel($list))->download('Vendor_history.csv');
    }

    public function ajax_datagrid_blockbycountry(Request $request){

        if ($request->ajax()) {

            if($request->Country != null){
                $users = Country::where('id',$request->Country)->with(['BlockByCountries'=> function($q) use($request) {
                    // Query the name field in status table
                    $q->where([["client_id", "=", $request->id],["Trunk","=",$request->Trunk]]);
                }]);
            }
            else{
                $users = Country::select('*')->with(['BlockByCountries'=> function($q) use($request) {
                    // Query the name field in status table
                    $q->where([["client_id", "=", $request->id],["Trunk","=",$request->Trunk]]); // '=' is optiona
                }]);
            }
            $users =  $users->get();

            return Datatables::of($users)
            ->filter(function ($instance) use ($request) {
                $instance->collection = $instance->collection->filter(function ($row) use ($request) {

                    if(!empty($request->Status)){
                        if($request->Status == "Blocked"){
                            $row = !empty($row['block_by_countries']);
                            return  $row;
                        }
                        if($request->Status == "Not_Blocked"){
                            $row = empty($row['block_by_countries']);
                            return  $row;
                        }
                        if($request->Status == "All"){
                            return $row;
                        }
                    }

                });
            })

            ->addColumn('status', function($row) use ($request) {

                if(!empty($row->BlockByCountries[0]->CountryID) == $row->id)
                {
                    if($row->BlockByCountries[0]->Trunk == $request->Trunk)
                    {
                        $value = "Blocked";
                        return $value;
                    }else{
                        $value = "Not Blocked";
                        return $value;
                    }

                }else{
                    $value = "Not Blocked";
                    return $value;
                }

            })
                ->addColumn('action', function($row){

                        $btn = '<input type="checkbox" name="checkbox[]" value="'. $row->id.'" class="rowcheckbox" >';

                        return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }


    }


    public function block_unblock_by_country(Request $request){
        if($request->action == 'block'){
            if(!empty($request->CountryID ))
            {
                foreach($request->CountryID as $Country_ID){
                    $validator = Validator::make($request->all(), [
                        'CountryID' => 'required',
                        'Trunk'=>'required',
                    ]);

                        if ($validator->fails())
                        {
                            $response = \Response::json([
                                    'success' => false,
                                    'errors' => $validator->getMessageBag()->toArray()
                                ]);
                            return $response;
                        }

                        $data['CountryID'] = $Country_ID ?? "";
                        $data['client_id'] = $request->client_id ?? "";
                        $data['Trunk'] = $request->Trunk ?? "";
                        $data['Timezones'] = $request->Timezones ?? "" ;
                        $data['prefix_cdr'] = $request->criteria ?? "0";
                        $data['user_id'] = Auth::user()->id ?? '';

                    $block = BlockByCountry::where([["client_id","=", $request->client_id],["CountryID","=",$Country_ID],["Trunk","=", $request->Trunk]])->first();
                    if(empty($block)){
                        BlockByCountry::Create($data);
                    }else{
                        $response = \Response::json(['success' => null,'message' => 'Select Not Blocked country']);
                        return $response;
                    }
                }
                $response = \Response::json(['success' => true,'message' => 'Blocked sucessfully']);
                return $response;
            }
        }

        if($request->action == 'unblock'){
            if(!empty($request->CountryID))
            {
                foreach($request->CountryID as $Country_ID){
                    $validator = Validator::make($request->all(), [
                        'CountryID' => 'required',
                        'Trunk'=>'required',
                    ]);

                        if ($validator->fails())
                        {
                            $response = \Response::json([
                                    'success' => false,
                                    'errors' => $validator->getMessageBag()->toArray()
                                ]);
                            return $response;
                        }


                    $unblock=  BlockByCountry::where([["client_id","=", $request->client_id],["CountryID","=",$Country_ID],["Trunk","=", $request->Trunk]])->first();
                    if(!empty($unblock)){
                        $unblock =  $unblock->delete();
                    }
                    else{
                        $response = \Response::json(['success' => null,'message' => 'Select Blocked Country']);
                        return $response;
                    }

                }
                $response = \Response::json(['success' => true,'message' => 'Unblocked sucessfully']);
                return $response;
            }
        }

    }

    public function ajax_datagrid_blockbycode(Request $request){
        if ($request->ajax()) {
            if($request->Code != null){
                $users = Codes::where('codes',$request->Code)->with(['BlockByCodes'=> function($q) use($request) {
                    // Query the name field in status table
                    $q->where([["client_id", "=", $request->id],["Trunk","=",$request->Trunk]]);
                }]);
            }
            else{
                $users = Codes::select('*')->with(['BlockByCodes'=> function($q) use($request) {
                    // Query the name field in status table
                    $q->where([["client_id", "=", $request->id],["Trunk","=",$request->Trunk]]); // '=' is optiona
                }]);
            }
            if($request->Country != null)
            {
                $users =  $users->where('destination', 'like', '%' . $request->Country . '%')->get();
            }
            else{
                $users =  $users->get();
            }

            return Datatables::of($users)
            ->filter(function ($instance) use ($request) {
                $instance->collection = $instance->collection->filter(function ($row) use ($request) {

                    if(!empty($request->Status)){
                        if($request->Status == "Blocked"){
                            $row = !empty($row['block_by_codes']);
                            return  $row;
                        }
                        if($request->Status == "Not_Blocked"){
                            $row = empty($row['block_by_codes']);
                            return  $row;
                        }
                        if($request->Status == "All"){
                            return $row;
                        }
                    }

                });
            })

            ->addColumn('status', function($row) use ($request) {

                if(!empty($row->BlockByCodes[0]->CodeID) == $row->id)
                {
                    if($row->BlockByCodes[0]->Trunk == $request->Trunk)
                    {
                        $value = "Blocked";
                        return $value;
                    }else{
                        $value = "Not Blocked";
                        return $value;
                    }

                }else{
                    $value = "Not Blocked";
                    return $value;
                }

            })
                ->addColumn('action', function($row){

                        $btn = '<input type="checkbox" name="checkbox[]" value="'. $row->id.'" class="rowcheckbox" >';

                        return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
    public function block_unblock_by_codes(Request $request){
        if($request->action == 'block'){
            if(!empty($request->CodeID ))
            {
                foreach($request->CodeID as $Code_ID){
                    $validator = Validator::make($request->all(), [
                        'CodeID' => 'required',
                        'Trunk'=>'required',
                    ]);

                        if ($validator->fails())
                        {
                            $response = \Response::json([
                                    'success' => false,
                                    'errors' => $validator->getMessageBag()->toArray()
                                ]);
                            return $response;
                        }

                        $data['CodeID'] = $Code_ID ?? "";
                        $data['client_id'] = $request->client_id ?? "";
                        $data['Trunk'] = $request->Trunk ?? "";
                        $data['Timezones'] = $request->Timezones ?? "" ;
                        $data['prefix_cdr'] = $request->criteria ?? "0";
                        $data['user_id'] = Auth::user()->id ?? '';

                    $block = BlockByCode::where([["client_id","=", $request->client_id],["CodeID","=",$Code_ID],["Trunk","=", $request->Trunk]])->first();
                    if(empty($block)){
                        BlockByCode::Create($data);
                    }else{
                        $response = \Response::json(['success' => null,'message' => 'Select Not Blocked country']);
                        return $response;
                    }
                }
                $response = \Response::json(['success' => true,'message' => 'Blocked sucessfully']);
                return $response;
            }
        }

        if($request->action == 'unblock'){
            if(!empty($request->CodeID))
            {
                foreach($request->CodeID as $Code_ID){
                    $validator = Validator::make($request->all(), [
                        'CodeID' => 'required',
                        'Trunk'=>'required',
                    ]);

                        if ($validator->fails())
                        {
                            $response = \Response::json([
                                    'success' => false,
                                    'errors' => $validator->getMessageBag()->toArray()
                                ]);
                            return $response;
                        }


                    $unblock=  BlockByCode::where([["client_id","=", $request->client_id],["CodeID","=",$Code_ID],["Trunk","=", $request->Trunk]])->first();
                    if(!empty($unblock)){
                        $unblock =  $unblock->delete();
                    }
                    else{
                        $response = \Response::json(['success' => null,'message' => 'Select Blocked Country']);
                        return $response;
                    }

                }
                $response = \Response::json(['success' => true,'message' => 'Unblocked sucessfully']);
                return $response;
            }
        }
    }

    public function ajax_datagrid_preference(Request $request){
        if ($request->ajax()) {

            if($request->Code != null){
                $users = Codes::where('codes',$request->Code)->with(['Perferences'=> function($q) use($request) {
                    // Query the name field in status table
                    $q->where([["client_id", "=", $request->id],["Trunk","=",$request->Trunk]]);
                }]);
            }
            else{
                $users = Codes::orderBy('id', 'asc')->with(['Perferences'=> function($q) use($request) {
                    // Query the name field in status table
                    $q->where([["client_id", "=", $request->id],["Trunk","=",$request->Trunk]]);
                }]);
            }

            if($request->Country != "All")
            {
                $users =  $users->where('destination', 'like', '%' . $request->Country . '%')->get();
            }
            else{
                $users =  $users->get();
            }



            return Datatables::of($users)


            ->addColumn('preference', function($row) use ($request) {
                return !empty($row->Perferences[0]->preference) ? $row->Perferences[0]->preference : "";
            })
            ->addColumn('checkbox', function($row){

                $id = !empty($row->Perferences[0]->id) ? $row->Perferences[0]->id : "";
                $btn1 = '<input type="checkbox" name="checkbox[]" data-preference="'.$id.'" value="'. $row->id.'" class="rowcheckbox" >';

                return $btn1 ;

            })

            ->addColumn('action', function($row){
                $id = !empty($row->Perferences[0]->id) ? $row->Perferences[0]->id : "";
                $btn = '<a href="javascript:void(0)" data-id="'.$id.'" data-codeid="'.$row->id.'" id="View"  class="btn btn-default btn-sm edit"><i class="fa fa-pen"></i></a>';

                return $btn;
            })
            ->rawColumns(['action','checkbox'])
            ->make(true);
        }
    }


    public function vendor_preference_store(Request $request){
        if(!empty($request->CodeID))
        {
            foreach( $request->CodeID as  $index => $Code_ID){
                $validator = Validator::make($request->all(), [
                'preference' => 'required',
                ]);

                    if ($validator->fails())
                    {
                        $response = \Response::json([
                                'success' => false,
                                'errors' => $validator->getMessageBag()->toArray()
                            ]);
                        return $response;
                    }

                    $data['CodeID'] = $Code_ID ?? "";
                    $data['client_id'] = $request->client_id ?? "";
                    $data['Trunk'] = $request->Trunk ?? "";
                    $data['user_id'] = Auth::user()->id ?? '';
                    $data['preference']= $request->preference ?? "";
                    $preference_check = Preference::where([["client_id","=", $request->client_id],["CodeID","=",$Code_ID],["Trunk","=", $request->Trunk]])->first();
                    if(empty($preference_check)){
                        Preference::Create($data);
                    }
                    if(!empty($request->VendorPreferenceID[$index])){
                        $Preference = Preference:: updateOrCreate(['id'   => $request->VendorPreferenceID[$index]],$data);
                    }
            }
            return response()->json(['message' =>  'Update sucessfully']);
        }
    }

    public function vendor_preference_edit(Request $request){
        if($request->ajax()){
            $user = Preference::find($request->id);
            return $user;
        }
    }

    public function preference_xlsx(Request $request){
        $list =array();
        $downloads = Codes::with(['Perferences'=> function($q) use($request) {
            // Query the name field in status table
            $q->where([["client_id", "=", $request->id],["Trunk","=",$request->Trunk]]);
        }])->get();

        foreach ( $downloads as $key => $value) {
            $data =array();
            $data['code'] =  $value->codes;
            $data['preference'] =  !empty($value->Perferences[0]->preference) ? $value->Perferences[0]->preference : "";
            $data['Description'] =  $value->destination;
            $list[]= $data;
        }

        return (new FastExcel($list))->download('Vendor_preference.xlsx');
    }

    public function preference_csv(Request $request){

        $list =array();
        $downloads = Codes::with(['Perferences'=> function($q) use($request) {
            // Query the name field in status table
            $q->where([["client_id", "=", $request->id],["Trunk","=",$request->Trunk]]);
        }])->get();

        foreach ( $downloads as $key => $value) {
            $data =array();
            $data['code'] =  $value->codes;
            $data['preference'] =  !empty($value->Perferences[0]->preference) ? $value->Perferences[0]->preference : "";
            $data['Description'] =  $value->destination;
            $list[]= $data;
        }
        return (new FastExcel($list))->download('Vendor_preference.csv');
    }


    public function ajax_datagrid_vendorHistory(Request $request){
        if ($request->ajax()) {
            $data = VendorDownloadProcess::leftjoin('users','users.id','=','vendor_download_processes.created_by')->select('vendor_download_processes.*','users.name as uname')->where('client_id',$request->id)->get();

            return Datatables::of($data)
            ->addColumn('title', function($row)use($request){
                $timezone = '';
                $effective = '';
                if(!empty($row->timezones)){
                    foreach (json_decode($row->timezones) as $value) {
                        if($value == 1){
                            $timezone = "(default)";
                        }
                    }
                }
                if(!empty($row->effective)){
                    if($row->effective == "CustomDate"){
                        $effective = $row->customDate;
                    }else{
                        $effective = "($row->effective)";
                    }
                }
                $clients = Client::where("id", "=",$request->id)->first();
               return  $clients->company.(!empty($row->format)? "($row->format)":"").$effective. $timezone ;
            })
            ->addColumn('created_by', function($row){
                return $row->uname;
            })
            ->addColumn('created_at', function($row){
               return  $row->created_at->format('Y-m-d H:i:s');
            })
            ->addIndexColumn()
            ->addColumn('action', function($row){

                $btn = '<a href="javascript:void(0)" data-id="'.$row->id.'" id="View"  class="btn btn-default btn-sm View"><i class="fa fa-eye"></i></a>
                <a  href="" class="btn btn-success btn-sm btn-icon icon-left"><i class="entypo-down"></i>Download</a>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }

    public function ajax_datagrid_customerHistory(Request $request){
        if ($request->ajax()) {
            $data = DownloadProcess::leftjoin('users','users.id','=','download_processes.created_by')->select('download_processes.*','users.name as uname')->where('client_id',$request->id)->get();

            return Datatables::of($data)
            ->addColumn('title', function($row)use($request){
                $timezone = '';
                $effective = '';
                foreach (json_decode($row->timezones )as $value) {
                    if($value == 1){
                        $timezone  = "(default)";
                    }

                }
                if(!empty($row->effective)){
                    if($row->effective == "CustomDate"){
                        $effective = "($row->customDate)";
                    }else{
                        $effective = "($row->effective)";
                    }
                }
                $clients = Client::where("id", "=",$request->id)->first();
               return  $clients->company.(!empty($row->format)? "($row->format)":"").$effective.$timezone;
            })
            ->addColumn('created_by', function($row){
                return $row->uname;
            })
            ->addColumn('created_at', function($row){
               return  $row->created_at->format('Y-m-d H:i:s');
            })
            ->addIndexColumn()
            ->addColumn('action', function($row){

                $btn = '<a href="javascript:void(0)" data-id="'.$row->id.'" id="View"  class="btn btn-default btn-sm View"><i class="fa fa-eye"></i></a>
                <a  href="" class="btn btn-success btn-sm btn-icon icon-left"><i class="entypo-down"></i>Download</a>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }
}
