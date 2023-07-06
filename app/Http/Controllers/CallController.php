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
use App\Utils\RandomUtil;
use Carbon\Carbon;
use DateTime;
use Date;
use File;
use Auth;

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
        $Trunks = Trunk::where("status", "=",1)->get();
        $Gateways = Setting::query()->get();
        return view('call.call-history-index',compact('Accounts','Trunks','Gateways'));
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
                    return   $row->feetime;
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
        $Accounts = Client::where("Vendor", "=",1)->get();
        $Trunks = Trunk::where("status", "=",1)->get();
        $Gateways = Setting::query()->get();
        return view('call.vendor-index',compact('Accounts','Trunks','Gateways'));
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
            $search = 1 ;
            if($request->type == "customer"){
                $data = ExportHistory::whereHas('clients', function($q) use($search){
                    $q->where('customer', '=', $search)->where('report_type','!=','Vendor-Summary')->where('report_type','!=','Vendor-Hourly');
                });
            }
            if($request->type == "vendor"){
                $data = ExportHistory::whereHas('clients', function($q) use($search){
                    $q->where('Vendor', '=', $search)->where('report_type','!=','Customer-Summary')->where('report_type','!=','Customer-Hourly');
                });
            }
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
                $btn = '<a href="'.url('/export-history-download',$row->id).'" class="download btn btn-success btn-sm " id="download"  data-id ="'.$row->id.'">Download</a>';

                return $btn;
            })
            ->rawColumns(['action','status'])
            ->make(true);
        }
        return view("call.export-history");
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
        $validator = Validator::make($request->all(), [
            'AccountID' => 'required',
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
        $data['type'] = "Excel-report";
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
        $validator = Validator::make($request->all(), [
            'AccountID' => 'required',
        ]);

        if($validator->fails())
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
        $data['type'] = "Csv-report";
        $data['status'] = 'pending';
        $code = random_int(100000, 999999);
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
                $btn = '<a href="'.url('/export-csv-history-download',$row->type."/".$row->id).'" class="download btn btn-success btn-sm " id="download"  data-id ="'.$row->id.'">Download</a>';

                return $btn;
            })
            ->rawColumns(['action','status'])
            ->make(true);
        }
        return view("call.export-csv-history");
    }
    public function download_csv_export_history(Request $request){
        $data = ExportCsvXlsxHistory::where('id',$request->id)->first();
        if($request->type =="Excel-report"){
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


}
