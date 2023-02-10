<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CallHistory;
use Illuminate\Support\Facades\Validator;
use Rap2hpoutre\FastExcel\FastExcel;
// use Datatables;
use Yajra\DataTables\DataTables;
use File;

class CallController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = CallHistory::query('')->with('client_customers','client_vendors')->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){

                           $btn = '<a href="javascript:void(0)" data-target="#ajaxModel" class="view btn btn-primary btn-sm view callhistoryForm" data-id="'.$row->id.'">View</a>';

                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);


                }
              
        return view('call.call-history-index');
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
         return view('call.viewcallhistory',compact('callhistory'));

    }


}
