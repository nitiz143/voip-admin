<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CallHistory;
use Illuminate\Support\Facades\Validator;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\Client;
use Yajra\DataTables\DataTables;
use App\Models\Trunk;
use DateTime;
use Date;
use File;

class CallController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = CallHistory::query();
            if(($request->has('StartDate') && $request->has('EndDate'))){
                $start =  strtotime($request->input('StartDate'));
                $start = $start*1000;
                $end = strtotime($request->input('EndDate'));
                $end = $end*1000;
                $data->where([['starttime' ,'>=', $start],['stoptime', '<=',  $end]]);              
            }
            if(!empty($request->Account)){
                $data->where('account_id', $request->Account);
            }
            if(!empty($request->zerovaluecost)){
                if($request->zerovaluecost == 1){
                    $data->where('fee', 0);
                }
                if($request->zerovaluecost == 2){
                    $data->where('fee','!=', 0);
                }
                if($request->zerovaluecost == 0){
                    $data;
                }
            }
            if(!empty($request->Cli)){
                $data->where('callere164', $request->Cli);
            }
            if(!empty($request->Cld)){
                $data->where('calleee164', $request->Cld);
            }
            $data = $data->get();
          
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('account', function($row){
                    $account = Client::where('id',$row->account_id)->first();
                    return  !empty($account->account_name) ? $account->account_name : "";
                })
                ->addColumn('Connect_time', function($row){
                    $date = new \DateTime();
                    $value = $row->starttime;
                    $startTime =  $date->setTimestamp($value/1000);
                    return !empty($startTime) ? $startTime->format('Y-m-d H:i:s') : "";
                })
                ->addColumn('Disconnect_time', function($row){
                    $date = new \DateTime();
                    $value = $row->stoptime;
                    $stopTime =  $date->setTimestamp($value/1000);
                    return !empty($stopTime) ? $stopTime->format('Y-m-d H:i:s') : "";
                })
                ->addColumn('Cost', function($row){
                        $cost = "$".$row->fee;
                    return $cost;
                })
                ->addColumn('Avrage_cost', function($row){
                        $cost = "$0.0";
                    return $cost;
                })
                ->addColumn('Trunk', function($row){
                    $account = Client::where('id',$row->account_id)->first();
                   if(!empty($account->customer_authentication_rule)){
                        if($account->customer_authentication_rule == "6"){
                            return "other";
                        }
                    }
                })
                ->addColumn('billing_duration', function($row){
                    $date = new \DateTime();
                    $value = $row->starttime;
                    $startTime =  $date->setTimestamp($value/1000);

                    $date1 = new \DateTime();
                    $value1 = $row->stoptime;
                    $stopTime =  $date1->setTimestamp($value1/1000);
                  
                    $totalDuration =  $stopTime->diff( $startTime)->format('%S');
                    return   $totalDuration;
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-target="#ajaxModel" class="view btn btn-primary btn-sm view callhistoryForm" data-id="'.$row->id.'">View</a>' ;
                    return $btn;
                }) 
                ->rawColumns(['action'])
                ->make(true);
        }
        $Accounts = Client::where("customer", "=",1)->get();
        $Trunks = Trunk::where("status", "=",1)->get();
        return view('call.call-history-index',compact('Accounts','Trunks'));
    }


    public function VendorIndex(Request $request)
    {
        if ($request->ajax()) {
           
            $data = CallHistory::query();
            if(($request->has('StartDate') && $request->has('EndDate'))){
                $start =  strtotime($request->input('StartDate'));
                $start = $start*1000;
                $end = strtotime($request->input('EndDate'));
                $end = $end*1000;
                $data->where([['starttime' ,'>=', $start],['stoptime', '<=',  $end]]);              
            }
            if(!empty($request->Account)){
                $data->where('account_id', $request->Account);
            }
            if(!empty($request->zerovaluecost)){
                if($request->zerovaluecost == 1){
                    $data->where('agentfee', 0);
                }
                if($request->zerovaluecost == 2){
                    $data->where('agentfee','!=', 0);
                }
                if($request->zerovaluecost == 0){
                    $data;
                }
            }
            if(!empty($request->Cli)){
                $data->where('callere164', $request->Cli);
            }
            if(!empty($request->Cld)){
                $data->where('calleee164', $request->Cld);
            }
            $data = $data->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('account', function($row){
                    $account = Client::where('id',$row->account_id)->first();
                    return  !empty($account->account_name) ? $account->account_name : "";
                })
                ->addColumn('Connect_time', function($row){
                    $date = new \DateTime();
                    $value = $row->starttime;
                    $startTime =  $date->setTimestamp($value/1000);
                    return !empty($startTime) ? $startTime->format('Y-m-d H:i:s') : "";

                })
                ->addColumn('Disconnect_time', function($row){
                    $date = new \DateTime();
                    $value = $row->stoptime;
                    $stopTime =  $date->setTimestamp($value/1000);
                    return !empty($stopTime) ? $stopTime->format('Y-m-d H:i:s') : "";
                })
                ->addColumn('Cost', function($row){
                        $cost = "$".$row->agentfee;
                    return $cost;
                })
                ->addColumn('Avrage_cost', function($row){
                        $cost =  $cost = "$0.0";
                    return $cost;
                })
                ->addColumn('billing_duration', function($row){
                    $date = new \DateTime();
                    $value = $row->starttime;
                    $startTime =  $date->setTimestamp($value/1000);

                    $date1 = new \DateTime();
                    $value1 = $row->stoptime;
                    $stopTime =  $date1->setTimestamp($value1/1000);
                  
                    $totalDuration =  $stopTime->diff( $startTime)->format('%S');
                    return   $totalDuration;
                })
                ->addColumn('Trunk', function($row){
                    $account = Client::where('id',$row->account_id)->first();
                   if(!empty($account->customer_authentication_rule)){
                        if($account->customer_authentication_rule == "6"){
                            return "other";
                        }
                   }
                      
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-target="#ajaxModel" class="view btn btn-primary btn-sm view callhistoryForm" data-id="'.$row->id.'">View</a>' ;
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $Accounts = Client::where("Vendor", "=",1)->get();
        $Trunks = Trunk::where("status", "=",1)->get();
        return view('call.vendor-index',compact('Accounts','Trunks'));
    }


    public function store(Request $request)
    {

        $request->validate([
            'file' => 'required',

        ]);

        $file  = $request->file;
   
        $name = time().'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs('public/csv', $name);
        $users = (new FastExcel)->import(public_path('storage/csv/' .$name), function ($line) {
       
                $checkHistory = CallHistory::whereCallerId($line['id'])->first();
                if(empty($checkHistory)){
                    return CallHistory::create([
                        'caller_id' => $line['id'],
                        'callere164' => $line['callere164'],
                        'calleraccesse164' => $line['calleraccesse164'],
                        'calleee164' => $line['calleee164'],
                        'calleeaccesse164' => $line['calleeaccesse164'],
                        'callerip' => $line['callerip'],
                        'callercodec' => $line['callercodec'],
                        'callergatewayid' => $line['callergatewayid'],
                        'callerproductid' => $line['callerproductid'],
                        'callertogatewaye164' => $line['callertogatewaye164'],
                        'callertype' => $line['callertype'],
                        'calleeip' => $line['calleeip'],
                        'calleecodec' => $line['calleecodec'],
                        'calleegatewayid' => $line['calleegatewayid'],
                        'calleeproductid' => $line['calleeproductid'],
                        'calleetogatewaye164' => $line['calleetogatewaye164'],
                        'calleetype' => $line['calleetype'],
                        'billingmode' => $line['billingmode'],
                        'calllevel' => $line['calllevel'],
                        'agentfeetime' => $line['agentfeetime'],
                        'starttime' => $line['starttime'],
                        'stoptime' => $line['stoptime'],
                        'callerpdd' => $line['callerpdd'],
                        'calleepdd' => $line['calleepdd'],
                        'holdtime' => $line['holdtime'],
                        'callerareacode' => $line['callerareacode'],
                        'feetime' => $line['feetime'],
                        'fee' => $line['fee'],
                        'tax' => $line['tax'],
                        'suitefee' => $line['suitefee'],
                        'suitefeetime' => $line['suitefeetime'],
                        'incomefee' => $line['incomefee'],
                        'incometax' => $line['incometax'],
                        'customeraccount' => $line['customeraccount'],
                        'customername' => $line['customername'],
                        'calleeareacode' => $line['calleeareacode'],
                        'agentfee' => $line['agentfee'],
                        'agenttax' => $line['agenttax'],
                        'agentsuitefee' => $line['agentsuitefee'],
                        'agentsuitefeetime' => $line['agentsuitefeetime'],
                        'agentaccount' => $line['agentaccount'],
                        'agentname' => $line['agentname'],
                        'flowno' => $line['flowno'],
                        'softswitchname' => $line['softswitchname'],
                        'softswitchcallid' => $line['softswitchcallid'],
                        'callercallid' => $line['callercallid'],
                        'calleecallid' => $line['calleecallid'],
                        'rtpforward' => $line['rtpforward'],
                        'enddirection' => $line['enddirection'],
                        'endreason' => $line['endreason'],
                        'billingtype' => $line['billingtype'],
                        'cdrlevel' => $line['cdrlevel'],
                        'agentcdr_id' => $line['agentcdr_id'],
                        'transactionid' => !empty($line['transactionid']) ? $line['transactionid'] : "",

                    ]);
                }
         });
         return response()->json(['message' =>  __('Updated Successfully'),'success'=>true]);
    }

    public function create()
    {
        return view('call.call-history');
    }


    public function destroy(Request $request)
    {
      
        CallHistory::find($request->id)->delete();
        return response()->json(['message'=>__('Deleted Successfully'),'success'=>true]);

    }


    public function getCallhistory(Request $request)
    {
         $callhistory =  CallHistory::find($request->id);
         $account = Client::where('id',$callhistory->account_id)->first();
         return view('call.viewcallhistory',compact('callhistory','account'));

    }


}
