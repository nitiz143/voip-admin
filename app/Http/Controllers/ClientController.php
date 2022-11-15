<?php

namespace App\Http\Controllers;
use App\Models\Client;
use App\Models\Billing;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\Trunk;
use App\Models\RateTable;
use App\Models\VendorTrunk;
use App\Models\CustomerTrunk;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Auth;

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
                    if($row->customer == 1){
                        $customer = '<a href="'.route('client.customer',$row->id).'" class=" btn btn-warning btn-sm Customer"  data-id ="'.$row->id.'"><i class="fa fa-user"></i></a> ';
                    }
                    if($row->Vendor == 1){
                        $Vendor = '<a href="'.route('client.vendor',$row->id).'" class=" btn btn-info btn-sm Vendor"  data-id ="'.$row->id.'"><i class="fab fa-slideshare"></i></a> ';
                    }
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
            'lead_owner'=>'required',
            'company'=>'required',
            'firstname'=>'required',
            'lastname'=>'required',
            'email' => ['required', 'string', 'email', 'max:255','regex:/(.+)@(.+)\.(.+)/i','unique:users,email'],
            'phone'=>'required|numeric',
            'fax'=>'required',
            'mobile'=>'required',
            'website'=>['required','regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i'],
            'skype_id'=>'required',
            'vat_number'=>'required',
            'description'=>'required',
            'address_line1'=>'required',
            'city'=>'required',
            'address_line2'=>'required',
            'postzip'=>'required',
            'address_line3'=>'required',
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

        $client = Client::create($request->all());
        if(!empty($client)){
            $billingdata["account_id"] = $client->id;
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
            'lead_owner'=>'required',
            'company'=>'required',
            'firstname'=>'required',
            'lastname'=>'required',
            'email' => ['required', 'string', 'email', 'max:255','regex:/(.+)@(.+)\.(.+)/i','unique:users,email,'.$request->id],
            'phone'=>'required',
            'fax'=>'required',
            'mobile'=>'required',
            'website'=>'required',
            'skype_id'=>'required',
            'status'=>'required',
            'vat_number'=>'required',
            'description'=>'required',
            'address_line1'=>'required',
            'city'=>'required',
            'address_line2'=>'required',
            'postzip'=>'required',
            'address_line3'=>'required',
            'country'=>'required',
                );
            if($request->customer_authentication_rule == 6){
                $rules['customer_authentication_value'] = 'required';
            }
            if($request->vendor_authentication_rule == 6){
                $rules['vendor_authentication_value'] = 'required';
            }
            if(!empty($request->billing_cycle)){
                if($request->billing_cycle == 'in_specific_days' || $request->billing_cycle == 'monthly_anniversary' || $request->billing_cycle == 'weekly'){
                    $rules['billing_cycle_startday'] = 'required';
                }
            }
        }else{
            $rules = array(
                'lead_owner'=>'required',
                'company'=>'required',
                'firstname'=>'required',
                'lastname'=>'required',
                'email' => ['required', 'string', 'email', 'max:255','regex:/(.+)@(.+)\.(.+)/i','unique:users,email'],
                'phone'=>'required|numeric',
                'fax'=>'required',
                'mobile'=>'required',
                'website'=>['required','regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i'],
                'skype_id'=>'required',
                'status'=>'required',
                'vat_number'=>'required',
                'description'=>'required',
                'address_line1'=>'required',
                'city'=>'required',
                'address_line2'=>'required',
                'postzip'=>'required',
                'address_line3'=>'required',
                'country'=>'required',
            );

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
        $request['reseller'] = $request->reseller ? $request->reseller : 2;
        $request['Vendor'] = $request->Vendor ? $request->Vendor : 2;
        $request['customer'] = $request->customer ? $request->customer : 2;
        $request['billing_status'] =  $request->billing_status ? $request->billing_status : 'inactive';
        // dd($request->all());
        // $now = \Carbon\Carbon::now();
        $user =  Client::updateOrCreate([
            'id'   => $request->id,
         ],$request->all());

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

         return response()->json(['message' =>  __('Updated Successfully'),'data' => $user,'success'=>true,'redirect_url' => route('client.index')]);


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
        return view('client.customer.index');
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
            $trunks = Trunk::with(['customers' => function($q) use($value) {
                $q->where('customer_id', '=', $value); // '=' is optional
            }])->get();
            $data =array();
            foreach ($trunks as  $trunk) {
                if(!$trunk->customers->isEmpty()){
                    // $rate[]  = RateTable::where("id",$trunk->customers[0]->rate_table_id)->select('name')->get();
                    $data[] = $trunk->customers[0];
                }
            }
            return view('client.customer.setting',compact('trunks','data'));
        }
        if($request->name == "Download Rate Sheet"){
            $vender_trunks = CustomerTrunk::where('customer_id',$request->id)->get();
            $trunks =array();
            if(!empty($vender_trunks)){
                foreach ($vender_trunks as $key => $value) {
                    $trunks[] = Trunk::where('id',$value->trunkid)->first();
                }
            }
            return view('client.customer.download',compact('trunks'));
        }
        if($request->name == "History"){
            return view('client.customer.history');
        }
    }

    public function customertrunk(Request $request){

        foreach($request->CustomerTrunk as $trunk){
            if(!empty($trunk['status'])){

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
        return back();
    }

    public function vendor(Request $request)
    {
        return view('client.vendor.index');
    }
    public function vendors(Request $request){
        if($request->name == "Vendor Rate"){

            $vender_trunks = VendorTrunk::where('vendor_id',$request->id)->get();
            $trunks =array();
            if(!empty($vender_trunks)){
                foreach ($vender_trunks as $key => $value) {
                    $trunks[] = Trunk::where('id',$value->trunkid)->first();
                }
            }


            return view('client.vendor.vendor_rate',compact('trunks'));
        }
        if($request->name == "Settings"){

            $value = $request->id;
            $trunks = Trunk::with(['vendors' => function($q) use($value) {
                $q->where('vendor_id', '=', $value); // '=' is optional
            }])->get();
            return view('client.vendor.setting',compact('trunks'));
        }
        if($request->name == "Vender Rate Download"){

            $vender_trunks = VendorTrunk::where('vendor_id',$request->id)->get();
            $trunks =array();
            if(!empty($vender_trunks)){
                foreach ($vender_trunks as $key => $value) {
                    $trunks[] = Trunk::where('id',$value->trunkid)->first();
                }
            }

            return view('client.vendor.download',compact('trunks'));
        }
        if($request->name == "Vendor Rate History"){
            return view('client.vendor.history');
        }
        if($request->name == "Blocking"){

            $vender_trunks = VendorTrunk::where('vendor_id',$request->id)->get();
            $trunks =array();
            if(!empty($vender_trunks)){
                foreach ($vender_trunks as $key => $value) {
                    $trunks[] = Trunk::where('id',$value->trunkid)->first();
                }
            }

            return view('client.vendor.blocking ',compact('trunks'));
        }
        if($request->name == "Preference"){

            $vender_trunks = VendorTrunk::where('vendor_id',$request->id)->get();
            $trunks =array();
            if(!empty($vender_trunks)){
                foreach ($vender_trunks as $key => $value) {
                    $trunks[] = Trunk::where('id',$value->trunkid)->first();
                }
            }

            return view('client.vendor.preference',compact('trunks'));
        }
    }
    public function vendortrunk(Request $request){
        foreach($request->VendorTrunk as $trunk){
           if(!empty($trunk['status'])){
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
        return back();
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

}
