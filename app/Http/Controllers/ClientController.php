<?php

namespace App\Http\Controllers;
use App\Models\Client;
use App\Models\Billing;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
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

                        $btn = '<a href="'.route('client.edit',$row->id).'" class="delete btn btn-primary btn-sm Edit"  data-id ="'.$row->id.'">Edit</a>&nbsp;&nbsp;';

                        return $btn;
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
            // $billingdata["billing_cycle_startday"] = $request->billing_cycle_startday;
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
}
