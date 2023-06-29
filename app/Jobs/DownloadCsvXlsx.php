<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\CallHistory;
use Illuminate\Support\Facades\Storage;
use File;

class DownloadCsvXlsx implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $data,$authUser,$exporthistory_id,$exporthistory_type;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request,$authUser,$exporthistory_id,$exporthistory_type)
    {
        $this->data = $request->all();
        $this->authUser = $authUser;
        $this->exporthistory_id = $exporthistory_id;
        $this->exporthistory_type = $exporthistory_type;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $AccountID = $this->data['AccountID'];
        $StartDate = $this->data['StartDate'] .' '. $this->data['StartTime'];
        $EndDate = $this->data['EndDate'] .' '. $this->data['EndTime'];

        $start =  strtotime($StartDate);
        $start = $start*1000;
        $end = strtotime($EndDate);
        $end = $end*1000;  

        // if($this->exporthistory_type == "Xlsx_file"){

        //     $downloads = CallHistory::where([['starttime' ,'>=', $start],['stoptime', '<=',  $end],['account_id',$AccountID]])->with('clients')->get();

        //     $list =array();
        //     $date = new \DateTime();
        //     $startTime="";
        //     $timepersec="";
        //     $persec="";
            
        //     foreach ( $downloads as $key => $value) {
        //         $startTime =  $date->setTimestamp($value->starttime/1000);
        //         $stopTime =  $date->setTimestamp($value->stoptime/1000);
        //         if($value->fee != 0 && $value->feetime !=0){
        //             $timepersec = $value->fee/$value->feetime;
        //             $persec =  round($timepersec, 7);
        //         }
            
        //         $data =array();
        //         $data['Id'] =  $value->id;
        //         $data['Account Name'] =  !empty($value->clients->firstname) ? $value->clients->firstname :"";
        //         $data['Connect Time'] =   !empty($startTime) ? $startTime->format('Y-m-d H:i:s') : "";
        //         $data['Disconnect Time'] =   !empty($stopTime) ? $stopTime->format('Y-m-d H:i:s') : "";
        //         $data['Billed Duration (sec)'] =   !empty($value->feetime) ? $value->feetime:"0";
        //         $data['Cost'] =   !empty($row->fee) ? "$".$row->fee :"$0.00";
        //         $data['Avg. Rate/Min'] =   !empty($persec) ? '$'.$persec*60 :"$0.00";
        //         $data['CLI'] =   !empty($value->callere164) ? $value->callere164 :"";
        //         $data['CLD'] =   !empty($value->calleee164) ? $value->calleee164 :"";
        //         $data['Country-code'] =   !empty($value->callerareacode) ? $value->callerareacode :"";
        //         $list[]= $data;
        //     }
        //         $destinationPath = public_path('storage/excel_files/');
        //         if (!file_exists($destinationPath)) {
        //             File::isDirectory($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);
        //         }
        //         $exporthistory_arr = ExportXlsxCsvHistory::find($this->exporthistory_id);
        //         $excel = (new FastExcel($list))->export($destinationPath.$exporthistory_arr->file_name);
        //         $exporthistory_arr['file'] =  $exporthistory_arr->file_name;
        //         $exporthistory_arr['status'] = 'complete';
        //         $exporthistory_arr->save();
        // }
        // if($this->exporthistory_type == "Csv_file"){
        //     $downloads = CallHistory::where([['starttime' ,'>=', $start],['stoptime', '<=',  $end],['account_id',$AccountID]])->with('clients')->get();

        //     $list =array();
        //     $date = new \DateTime();
        //     $startTime="";
        //     $timepersec="";
        //     $persec="";
            
        //     foreach ( $downloads as $key => $value) {
        //         $startTime =  $date->setTimestamp($value->starttime/1000);
        //         $stopTime =  $date->setTimestamp($value->stoptime/1000);
        //         if($value->fee != 0 && $value->feetime !=0){
        //             $timepersec = $value->fee/$value->feetime;
        //             $persec =  round($timepersec, 7);
        //         }
            
        //         $data =array();
        //         $data['Id'] =  $value->id;
        //         $data['Account Name'] =  !empty($value->clients->firstname) ? $value->clients->firstname :"";
        //         $data['Connect Time'] =   !empty($startTime) ? $startTime->format('Y-m-d H:i:s') : "";
        //         $data['Disconnect Time'] =   !empty($stopTime) ? $stopTime->format('Y-m-d H:i:s') : "";
        //         $data['Billed Duration (sec)'] =   !empty($value->feetime) ? $value->feetime:"0";
        //         $data['Cost'] =   !empty($row->fee) ? "$".$row->fee :"$0.00";
        //         $data['Avg. Rate/Min'] =   !empty($persec) ? '$'.$persec*60 :"$0.00";
        //         $data['CLI'] =   !empty($value->callere164) ? $value->callere164 :"";
        //         $data['CLD'] =   !empty($value->calleee164) ? $value->calleee164 :"";
        //         $data['Country-code'] =   !empty($value->callerareacode) ? $value->callerareacode :"";
        //         $list[]= $data;
        //     }
        //         $destinationPath = public_path('storage/csv_files/');
        //         if (!file_exists($destinationPath)) {
        //             File::isDirectory($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);
        //         }
        //         $exporthistory_arr = ExportXlsxCsvHistory::find($this->exporthistory_id);
        //         $excel = (new FastExcel($list))->export($destinationPath.$exporthistory_arr->file_name);
        //         $exporthistory_arr['file'] =  $exporthistory_arr->file_name;
        //         $exporthistory_arr['status'] = 'complete';
        //         $exporthistory_arr->save();
        // }
    }
}
