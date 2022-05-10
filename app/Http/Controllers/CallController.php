<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CallHistory;
use Illuminate\Support\Facades\Validator;
use Rap2hpoutre\FastExcel\FastExcel;
use File;

class CallController extends Controller
{
    public function index()
    {
        $calls =CallHistory::query('')->paginate(10);
        return view('call.call-history-index',compact('calls'));
    }


    public function store(Request $request)
    {

        // $request->validate([
        //     'file' => 'required',

        // ]);

        $file  = $request->file;
        //  dd($file);
        $name = time().'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs('public/csv', $name);
        $users = (new FastExcel)->import(public_path('storage/csv/' .$name), function ($line) {
            // dd($line['id']);
                $checkHistory = CallHistory::whereCallerId($line['id'])->first();
                if(empty($checkHistory)){
                    return CallHistory::create([
                        'caller_id' => $line['id'],
                        'callere164' => $line['callere164'],
                        'calleraccesse164' => $line['calleraccesse164'],
                        'calleee164' => $line['calleee164'],
                        'calleeaccesse164' => $line['calleeaccesse164'],
                        'callerip' => $line['callerip'],
                        'callergatewayh323id' => $line['callergatewayh323id'],
                        'callerproductid' => $line['callerproductid'],
                        'callertogatewaye164' => $line['callertogatewaye164'],
                        'calleeip' => $line['calleeip'],
                        'calleegatewayh323id' => $line['calleegatewayh323id'],
                        'calleeproductid' => $line['calleeproductid'],
                        'calleetogatewaye164' => $line['calleetogatewaye164'],
                        'billingmode' => $line['billingmode'],
                        'calllevel' => $line['calllevel'],
                        'agentfeetime' => $line['agentfeetime'],
                        'starttime' => $line['starttime'],
                        'stoptime' => $line['stoptime'],
                        'pdd' => $line['pdd'],
                        'holdtime' => $line['holdtime'],
                        'feeprefix' => $line['feeprefix'],
                        'feetime' => $line['feetime'],
                        'fee' => $line['fee'],
                        'suitefee' => $line['suitefee'],
                        'suitefeetime' => $line['suitefeetime'],
                        'incomefee' => $line['incomefee'],
                        'customername' => $line['customername'],
                        'agentfeeprefix' => $line['agentfeeprefix'],
                        'agentfee' => $line['agentfee'],
                        'agentsuitefee' => $line['agentsuitefee'],
                        'agentsuitefeetime' => $line['agentsuitefeetime'],
                        'agentaccount' => $line['agentaccount'],
                        'agentname' => $line['agentname'],
                        'flowno' => $line['flowno'],
                        'softswitchdn' => $line['softswitchdn'],
                        'enddirection' => $line['enddirection'],
                        'endreason' => $line['endreason'],
                        'calleebilling' => $line['calleebilling'],
                        'cdrlevel' => $line['cdrlevel'],
                        'subcdr_id' => $line['subcdr_id'],
                    ]);
                }
         });
         return response()->json(['message' =>  __('updated_successfully'),'success'=>true]);
    }

    public function create()
    {
        return view('call.call-history');
    }


    public function destroy(Request $request)
    {
        //  dd($request->id);
        CallHistory::find($request->id)->delete();
        return response()->json(['message'=>__('deleted_successfully'),'success'=>true]);

    }

}
