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
            $data = Client::query('')->get();
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

     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data= User::query('')->get();
        $user = Client::find($id);
        $billingdata = Billing::find($id);
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

        $user =  Client::updateOrCreate([
            'id'   => $request->id,
         ],$request->all());

            $billingdata["account_id"] = $user->id;
            $billingdata["billing_class"] = $request->billing_class;
            $billingdata["billing_type"] = $request->billing_type;
            $billingdata["billing_timezone"] = $request->billing_timezone;
            $billingdata["billing_startdate"] = $request->billing_startdate;
            $billingdata["billing_cycle"] = $request->billing_cycle;
            if($request->billing_cycle == 'weekly'){
                $billingdata["billing_cycle_startday"] = $request->billing_cycle_startday_weekly;
            }elseif($request->billing_cycle == 'monthly'){
                $billingdata["billing_cycle_startday"] = $request->billing_cycle_startday;
            }else{
                $billingdata["billing_cycle_startday"] = Null;
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
            Billing::updateOrCreate(['id' => $request->id],$billingdata);


         return response()->json(['message' =>  __('Updated Successfully'),'data' => $user,'success'=>true,'redirect_url' => route('client.index')]);


    }

}
