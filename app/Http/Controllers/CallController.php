<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CallHistory;
use Illuminate\Support\Facades\Validator;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\Client;
use Yajra\DataTables\DataTables;
use App\Models\Trunk;
use App\Models\Setting;
use App\Models\Codes;
use App\Models\ExportHistory;
use App\Jobs\InvoiceGenrateJob;
use App\Jobs\DownloadCsvXlsx;
use App\Models\ExportCsvXlsxHistory;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use App\Utils\RandomUtil;
use Carbon\Carbon;
use DateTime;
use Date;
use File;
use Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Billing;
use App\Mail\MyCustomMail; 



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

            if($request->billingtype == 'one') {
                $data->where( 'feetime', ">", "0"); 

            }
            // else {
            //     $data->where('feetime' ,">", "0"); 
            // }

                        


            if(!empty($request->Account)){
                $data->where('account_id', $request->Account);
                $data = $data;
            }else{
                $data = [];
            }
          
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
                    if(!empty($row->fee)){
                        return "$".$row->fee;
                    }else{
                        return '$0.00';
                    }
                })
                ->addColumn('Prefix', function($row){
                        $Prefix = $row->callerareacode;
                    return $Prefix;
                })
                ->addColumn('Avrage_cost', function($row){
                    if(!empty($row->feetime)){
                        $timepersec = $row->fee/$row->feetime;
                        $persec =  round($timepersec, 7);
                        return  '$'.$persec*60;                    
                    }else{
                        return '$0.00';
                    }
                   
                })->addColumn('billing_duration', function($row){

                    if(!empty($row->feetime)){
                        return  $row->feetime;
                    }else{
                        return 0;
                    }
                   
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-target="#ajaxModel" class="view btn btn-primary btn-sm view callhistoryForm" data-id="'.$row->id.'">View</a>' ;
                    return $btn;
                }) 
                ->rawColumns(['action'])
                ->make(true);
        }
        $Accounts = Client::where("customer", "=",1)->get();
        $VAccounts = Client::where("Vendor", "=",1)->get();
        $Trunks = Trunk::where("status", "=",1)->get();
        $Gateways = Setting::query()->get();
        return view('call.call-history-index',compact('Accounts','VAccounts','Trunks','Gateways'));
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

            if($request->billingtype == 'one') {
                $data->where( 'agentfeetime', ">", "0"); 

            }
            // else {
            //     $data->where('agentfeetime' ,">", "0"); 
            // }

            if(!empty($request->VAccount)){
                $data->where('vendor_account_id', $request->VAccount);
                $data = $data;
            }else{
                $data = [];
            }
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('account', function($row){
                    $account = Client::where('id',$row->vendor_account_id)->first();
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
                    if(!empty($row->agentfeetime)){
                        $timepersec = $row->agentfee/$row->agentfeetime;
                        $persec =  round($timepersec, 7);
                        return  '$'.$persec*60;                    
                    }else{
                        return '$0.00';
                    }
                })
                ->addColumn('billing_duration', function($row){
                   
                    if(!empty($row->agentfeetime)){
                        return  $row->agentfeetime;
                    }else{
                        return 0;
                    }
                })
                ->addColumn('Prefix', function($row){
                        $Prefix = $row->callerareacode;
                    return $Prefix;
                })->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-target="#ajaxModel" class="view btn btn-primary btn-sm view callhistoryForm" data-id="'.$row->id.'">View</a>' ;
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    public function store(Request $request)
    {

        $request->validate([
            'file' => 'required',
            'version' => 'required',
        ]);
        $file  = $request->file;
        if($request->version == 1 ){
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
        }
        if($request->version == 2 ){
            $name = time().'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('public/csv', $name);
            $users = (new FastExcel)->import(public_path('storage/csv/' .$name), function ($line) {
                $checkHistory = CallHistory::whereCallerId($line['id'])->first();
                if(empty($checkHistory)){
                    return CallHistory::create([
                        'caller_id' => $line['id'],
                        'callere164'=> $line['callere164'],
                        'calleraccesse164'=> $line['calleraccesse164'],
                        'calleee164'=> $line['calleee164'],
                        'calleeaccesse164'=> $line['calleeaccesse164'],
                        'callerip'=> $line['callerip'],
                        'callerrtpip'=> $line['callerrtpip'],
                        'callercodec'=> $line['callercodec'],
                        'callergatewayid'=> $line['callergatewayid'],
                        'callerproductid'=> $line['callerproductid'],
                        'callertogatewaye164'=> $line['callertogatewaye164'],
                        'callertype'=> $line['callertype'],
                        'calleeip'=>$line['calleeip'],
                        'calleertpip'=>$line['calleertpip'],
                        'calleecodec'=> $line['calleecodec'],
                        'calleegatewayid'=> $line['calleegatewayid'],
                        'calleeproductid'=> $line['calleeproductid'],
                        'calleetogatewaye164'=> $line['calleetogatewaye164'],
                        'calleetype'=> $line['calleetype'],
                        'billingmode'=> $line['billingmode'],
                        'calllevel'=> $line['calllevel'],
                        'agentfeetime'=> $line['agentfeetime'],
                        'starttime'=> $line['starttime'],
                        'stoptime'=> $line['stoptime'],
                        'callerpdd'=> $line['callerpdd'],
                        'calleepdd'=> $line['calleepdd'],
                        'holdtime'=> $line['holdtime'],
                        'callerareacode'=> $line['callerareacode'],
                        'feetime'=> $line['feetime'],
                        'fee'=> $line['fee'],
                        'tax'=> $line['tax'],
                        'suitefee'=> $line['suitefee'],
                        'suitefeetime'=> $line['suitefeetime'],
                        'incomefee'=> $line['incomefee'],
                        'incometax'=> $line['incometax'],
                        'customeraccount'=> $line['customeraccount'],
                        'customername'=> $line['customername'],
                        'calleeareacode'=> $line['calleeareacode'],
                        'agentfee'=> $line['agentfee'],
                        'agenttax'=> $line['agenttax'],
                        'agentsuitefee'=> $line['agentsuitefee'],
                        'agentsuitefeetime'=> $line['agentsuitefeetime'],
                        'agentaccount'=> $line['agentaccount'],
                        'agentname'=> $line['agentname'],
                        'flowno'=> $line['flowno'],
                        'softswitchname'=> $line['softswitchname'],
                        'softswitchcallid'=> $line['softswitchcallid'],
                        'callercallid'=> $line['callercallid'],
                        'calleroriginalcallid'=> $line['calleroriginalcallid'],
                        'calleecallid'=> $line['calleecallid'],
                        'calleroriginalinfo'=> $line['calleroriginalinfo'],
                        'rtpforward'=> $line['rtpforward'],
                        'enddirection'=> $line['enddirection'],
                        'endreason'=> $line['endreason'],
                        'billingtype'=> $line['billingtype'],
                        'cdrlevel'=> $line['cdrlevel'],
                        'agentcdr_id'=> $line['agentcdr_id'],
                        'sipreasonheader'=> $line['sipreasonheader'],
                        'recordstarttime'=> $line['recordstarttime'],
                        'transactionid'=> $line['transactionid'],
                        'flownofirst'=> $line['flownofirst'],
                    ]);
                }
            });
        }
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
        $callhistory = CallHistory::find($request->id);
        $account = Client::where('id',$callhistory->account_id)->first();

        $date = new \DateTime();
        $value = $callhistory->starttime;
        $startTime =  $date->setTimestamp($value/1000);

        $date2 = new \DateTime();
        $value2 = $callhistory->stoptime;
        $stopTime =  $date2->setTimestamp($value2/1000);

         return view('call.viewcallhistory',compact('callhistory','account','stopTime','startTime'));

    }
    public function invoice_export(Request $request){
            $validator = Validator::make($request->all(), [
                'AccountID' => 'required',
                'report' => 'required',
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
            $data['client_id'] = !empty($request->AccountID) ? $request->AccountID : " ";
            $data['user_id'] = Auth::user()->id;
            $data['report_type'] = $request->report;
            $data['status'] = 'pending';
            $data['Invoice_no'] =  RandomUtil::randomString('Invoice_');
         
            $code = random_int(100000, 999999);
            $data['file_name'] = date('YmdHis').'-'.$code.".pdf";
            $exporthistory = ExportHistory::create($data);
            if(!empty($exporthistory)){
                $exporthistory_id = $exporthistory->id;
                $authUser = Auth::user();
                $invoice_pdf = new InvoiceGenrateJob($request,$authUser,$exporthistory_id);
                dispatch($invoice_pdf);
                return response()->json(array(
                    'success' => true,
                    'message'=> __('file_download_msg')
                ), 200);
            }    
        
    }
    public function export_history(Request $request){
        if ($request->ajax()) {
            $data = ExportHistory::query();

            if($request->ActiveTab == 1){
                if(($request->has('StartDate') && $request->has('EndDate'))){
                    $data->whereBetween('created_at', [$request->StartDate,$request->EndDate]);
                }
                if(!empty($request->Account)){
                    $data->where('client_id', $request->Account);
                }
                $data->where('report_type','!=','Vendor-Summary')->where('report_type','!=','Vendor-Hourly');
            }
            if($request->ActiveTab == 2){
                if(($request->has('StartDate') && $request->has('EndDate'))){
                    $data->whereBetween('created_at', [$request->StartDate,$request->EndDate]);
                }
                if(!empty($request->Account)){
                    $data->where('client_id', $request->Account);
                }
                $data->where('report_type','!=','Customer-Summary')->where('report_type','!=','Customer-Hourly');
            }
            if(!empty($request->Report)){
                $data->where('report_type', $request->Report);
            }
                $data = $data;
            return Datatables::of($data)
            
            ->addColumn('created_at', function($row){
                return Carbon::parse($row->created_at)->format('d/m/Y H:i:s');
            })
            ->addColumn('send_at', function($row){
                return Carbon::parse($row->send_at)->format('d/m/Y H:i:s');
            })
            ->addColumn('status', function($row){
               if($row->status == "pending"){
                    $badge = '<span class="badge badge-warning">Pending</span>';
               }else{
                    $badge = '<span class="badge badge-info">Completed</span>';
               }
               return $badge;
            })
            ->addColumn('report_type', function($row){
                if($row->report_type == "Vendor-Negative-Report"){
                     $report = 'Negative-Report';
                }
                elseif($row->report_type == "Customer-Negative-Report"){
                    $report = 'Negative-Report';
                }
                else{
                    $report = $row->report_type;
                }
                return $report;
             })
            ->addColumn('client', function($row){
                return !empty($row->clients->company) ? $row->clients->company : "";
            })
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<a href="'.url('/export-history-download',$row->id).'" class="download btn btn-success btn-sm " id="download"  data-id ="'.$row->id.'">Download</a>
               <a href="'.url('/export-history-email',$row->id).'" class="email btn btn-primary btn-sm " id="email"  data-id ="'.$row->id.'">Send Email</a>';

                return $btn;
            })
            ->rawColumns(['action','status'])
            ->make(true);
        }
        $Accounts = Client::where("customer", "=",1)->get();
        $VAccounts = Client::where("vendor", "=",1)->get();
        return view("call.export-history",compact('Accounts','VAccounts'));
    }

    public function email_export_history(Request $request) {

       
        $history = ExportHistory::where('id',$request->id)->first(); 
       
       
        // $data = Billing::where('account_id',$history->client_id)->first();
        $client = Client::where('id',$history->client_id)->first();
        
        $mail = Mail::to($client->email)->send(new MyCustomMail($history));

        if ($mail > 0) {
            $history->send_at =\Carbon\Carbon::now()->format('d-m-y');
            $history->update();
        }
        
        return redirect()->back()->with('success', 'Email Send Succesfully');   

        
    }
    


    public function download_export_history(Request $request){
        $invoice = ExportHistory::where('id',$request->id)->first();

        if(file_exists( storage_path('app/voip/pdf/'.$invoice->file_name))) {

            $file= storage_path('app/voip/pdf/'.$invoice->file_name);
            $headers = array('Content-Type: application/pdf',);
            return response()->download($file, $invoice->file_name, $headers);
        }   
        else{
            return \Redirect::back()->withErrors(['msg' => 'This file is not longer avialable']);
        }
    }

    public function export_history_xlsx(Request $request){
        if($request->report == 'Account-Manage'){
            $validator = Validator::make($request->all(), [
                'report' => 'required',
            ]);
        }
        else{
            $validator = Validator::make($request->all(), [
                'AccountID' => 'required',
                'report' => 'required',
            ]);
        }
        if ($validator->fails())
        {
            $response = \Response::json([
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ]);
            return $response;
        }
        $data = array();
        $data['client_id'] = !empty($request->AccountID) ? $request->AccountID : "0";
        $data['user_id'] = Auth::user()->id;
        $data['type'] = "Excel-report";
        $data['report_type'] = $request->report;
        $data['status'] = 'pending';
        $code = random_int(100000, 999999);
        $data['file_name'] = date('YmdHis').'-'.$code.".xlsx";
        $exporthistory = ExportCsvXlsxHistory::create($data);
        if(!empty($exporthistory)){
            $exporthistory_type = 'Xlsx_file';
            $exporthistory_id = $exporthistory->id;
            $authUser = Auth::user();
            $Excel = new DownloadCsvXlsx($request,$authUser,$exporthistory_id,$exporthistory_type);
            dispatch($Excel);
            return response()->json(array(
                'success' => true,
                'message'=> __('Excel file_download_msg')
            ), 200);
        }    
    }

    public function export_history_csv(Request $request){
       if($request->report == 'Account-Manage'){
            $validator = Validator::make($request->all(), [
                'report' => 'required',
            ]);
        }
        else{
            $validator = Validator::make($request->all(), [
                'AccountID' => 'required',
                'report' => 'required',
            ]);
        }

        if($validator->fails())
        {
            $response = \Response::json([
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ]);
            return $response;
        }
        $data = array();
        $data['client_id'] = !empty($request->AccountID) ? $request->AccountID : "0";
        $data['user_id'] = Auth::user()->id;
        $data['type'] = "Csv-report";
        $data['report_type'] = $request->report;
        $data['status'] = 'pending';
        $code = random_int(100000,999999);
        $data['file_name'] = date('YmdHis').'-'.$code.".csv";
        $exporthistory = ExportCsvXlsxHistory::create($data);
        if(!empty($exporthistory)){
            $exporthistory_type = 'Csv_file';
            $exporthistory_id = $exporthistory->id;
            $authUser = Auth::user();
            $Csv = new DownloadCsvXlsx($request,$authUser,$exporthistory_id,$exporthistory_type);
            dispatch($Csv);
            return response()->json(array(
                'success' => true,
                'message'=> __('Csv file_download_msg')
            ), 200);
        }    
    }

    public function export_csv_history(Request $request){
        if ($request->ajax()) {
            $data = ExportCsvXlsxHistory::query();

            // if(($request->has('StartDate') && $request->has('EndDate'))){
            //     $data->whereBetween('created_at', [$request->StartDate,$request->EndDate]);
            // }
            // if(!empty($request->Account)){
            //     $data->where('client_id', $request->Account);
            // }
            // if(!empty($request->Report)){
            //     $data->where('report_type', $request->Report);
            // }
            $data = $data;
            return Datatables::of($data)
            ->addColumn('created_at', function($row){
                return Carbon::parse($row->created_at)->format('d/m/Y H:i:s');
            })
            ->addColumn('status', function($row){
               if($row->status == "pending"){
                    $badge = '<span class="badge badge-warning">Pending</span>';
               }else{
                    $badge = '<span class="badge badge-info">Completed</span>';
               }
               return $badge;
            })
            ->addColumn('client', function($row){
                return !empty($row->clients->company) ? $row->clients->company : "";
            })
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<a href="'.url('/export-csv-history-download',$row->id).'" class="download btn btn-success btn-sm " id="download"  data-id ="'.$row->id.'">Download</a>';

                return $btn;
            })
            ->rawColumns(['action','status'])
            ->make(true);
        }
     

        $Accounts = Client::where("customer", "=",1)->get();
        $VAccounts = Client::where("vendor", "=",1)->get();
        return view("call.export-csv-history",compact('Accounts','VAccounts'));
    }
    public function download_csv_export_history(Request $request){
       
        $data = ExportCsvXlsxHistory::where('id',$request->id)->first();
        if($data->type =="Excel-report"){
            if(file_exists( public_path('storage/excel_files/'.$data->file_name))) {
                $file= public_path('storage/excel_files/'.$data->file_name);
                $headers = array('Content-Type: application/xlsx',);
                return response()->download($file, $data->file_name, $headers);
            }   
            else{
                return \Redirect::back()->withErrors(['msg' => 'This file is not longer avialable']);
            }
        }else{
            if(file_exists( public_path('storage/csv_files/'.$data->file_name))) {
                $file= public_path('storage/csv_files/'.$data->file_name);
                $headers = array('Content-Type: application/csv',);
                return response()->download($file, $data->file_name, $headers);
            }   
            else{
                return \Redirect::back()->withErrors(['msg' => 'This file is not longer avialable']);
            }
        }
    }
    public function csv_view(Request $request){
        if($request->Report == 'Account-Manage'){
            $validator = Validator::make($request->all(), [
                'Report' => 'required',
            ]);
        }
        else{
            $validator = Validator::make($request->all(), [
                'Account' => 'required',
                'Report' => 'required',
            ]);
        }
        if ($validator->fails())
        {
            $response = \Response::json([
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ]);
            return $response;
        }
        $type = $request->type;
        $AccountID = $request->Account;
        $StartDate = $request->StartDate ;
        $EndDate = $request->EndDate;
        $Report = $request->Report;
        
        if($type == "Vendor"){   
            $query = CallHistory::query('*');
            if((!empty( $StartDate ) && !empty( $EndDate ))){
                $start =  strtotime($StartDate);
                $start = $start*1000;
                $end = strtotime($EndDate);
                $end = $end*1000;
                $query->where([['starttime' ,'>=', $start],['stoptime', '<=',  $end]]);              
            }

            if($request->billingtype == 'one') {
                $query->where( 'agentfeetime', ">", "0"); 

            }

            if($Report != 'Account-Manage') {
                if(!empty( $AccountID )) {
                    $query->where('call_histories.vendor_account_id', $AccountID);
                }
            }
            if($Report == 'Vendor-Negative-Report') {
                $query->where('call_histories.agentfee', '<=', 0);
            }
            $invoices = $query->get();
            $count_duration=[];
            $count_vendor_duration=[];
            $total_vendor_cost ="";
            $total_cost = "";
            $calls = $invoices->count();
            if(!empty($invoices)){
                $total_cost = $invoices->sum('fee');
                $total_vendor_cost = $invoices->sum('agentfee');
                foreach ($invoices as $key => $invoice) {
                    $count_duration[] =   $invoice->feetime;
                    $count_vendor_duration[] =  $invoice->agentfeetime;
                }
            }
            $invoices = $invoices->groupBy('agentaccount');
            $totalGroup = count($invoices);
            $perPage = 5;
            $page = Paginator::resolveCurrentPage('page');

            $invoices = new LengthAwarePaginator( $invoices->forPage($page, $perPage), $totalGroup, $perPage, $page, [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => 'page',
            ]);
            $account ="";
            if(!empty($AccountID)){
                $account = Client::where('id',$AccountID)->with('billing')->first();
            }
            $user = "Vendor";
            return view('csv_view', compact('invoices','Report','total_cost','user','account','count_duration','calls','StartDate','EndDate','total_vendor_cost','count_vendor_duration'));
        }  
        if($type == "Customer"){
            $query = CallHistory::query('*');
            if((!empty( $StartDate ) && !empty( $EndDate ))){
                $start =  strtotime($StartDate);
                $start = $start*1000;
                $end = strtotime($EndDate);
                $end = $end*1000;
                $query->where([['starttime' ,'>=', $start],['stoptime', '<=',  $end]]);              
            }

            
            if($request->billingtype == 'one') {
                $query->where( 'feetime', ">", "0"); 

            }

            if($Report != 'Account-Manage') {
                if(!empty( $AccountID )) {
                    $query->where('call_histories.account_id', $AccountID);
                }
            }
            if($Report == 'Customer-Negative-Report'){
                $query->where('call_histories.fee','<=', 0);
            }
            $invoices = $query->get();
            $count_duration=[];
            $count_vendor_duration=[];
            $total_cost = "";
            $total_vendor_cost ="";
            $calls = $invoices->count();
            if(!empty( $invoices)){
                $total_cost = $invoices->sum('fee');
                $total_vendor_cost = $invoices->sum('agentfee');
                foreach ($invoices as $key => $invoice) {
                    $count_duration[] =   $invoice->feetime;
                    $count_vendor_duration[] =  $invoice->agentfeetime;
                }
            }
            $invoices = $invoices->groupBy('customeraccount');
            $totalGroup = count( $invoices);
            $perPage = 5;
            $page = Paginator::resolveCurrentPage('page');

            $invoices = new LengthAwarePaginator( $invoices->forPage($page, $perPage), $totalGroup, $perPage, $page, [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => 'page',
            ]);
            $account ="";
            if(!empty($AccountID)){
                $account = Client::where('id',$AccountID)->with('billing')->first();
            }
            $user = "Customer";
            return view('csv_view', compact('invoices','Report','total_cost','user','account','count_duration','StartDate','EndDate','calls','total_vendor_cost','count_vendor_duration'));
        }
    }

}
