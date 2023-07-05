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
use App\Models\ExportCsvXlsxHistory;
use Illuminate\Support\Facades\Storage;
use OpenSpout\Writer\XLSX\Options;
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

        if($this->exporthistory_type == "Xlsx_file"){
            $downloads = CallHistory::where([['starttime' ,'>=', $start],['stoptime', '<=',  $end],['account_id',$AccountID]])->with('clients')->get();

            $list =array();
            $date = new \DateTime();
            $date2 = new \DateTime();
            $startTime="";
            $stopTime="";
        
            if(!$downloads->isEmpty()){
                foreach ( $downloads as $key => $value) {
                    $startTime = $value->starttime;
                    $stopTime = $value->stoptime;
                    $startTime =  $date->setTimestamp($startTime/1000);
                    $stopTime =  $date2->setTimestamp($stopTime/1000);

                    $data =array();
                    $data['Account Holder Name'] = !empty($value->clients->firstname) ? $value->clients->firstname. $value->clients->lastname :"";
                    $data['callere'] =   !empty($value->callere164) ? $value->callere164 :"";
                    $data['calleraccesse'] =   !empty($value->calleraccesse164) ? $value->calleraccesse164 :"";
                    $data['calleee'] =   !empty($value->calleee164) ? $value->calleee164 :"";
                    $data['calleeaccesse'] =   !empty($value->calleeaccesse164) ? $value->calleeaccesse164 :"";
                    $data['callerip'] =   !empty($value->callerip) ? $value->callerip :"";
                    $data['callercodec'] =   !empty($value->callercodec) ? $value->callercodec :"";
                    $data['callergatewayid'] =  !empty($value->callergatewayid) ? $value->callergatewayid :"";
                    $data['callerproductid'] =  !empty($value->callerproductid) ? $value->callerproductid :"";
                    $data['callertogatewaye'] = !empty($value->callertogatewaye164) ?$value->callertogatewaye164 :"";
                    $data['callertype'] =   !empty($value->callertype) ? $value->callertype :"";
                    $data['calleeip'] =   !empty($value->calleeip) ? $value->calleeip :"";
                    $data['calleecodec'] =   !empty($value->calleecodec) ? $value->calleecodec :"";
                    $data['calleegatewayid'] =   !empty($value->calleegatewayid) ? $value->calleegatewayid :"";
                    $data['calleeproductid'] =   !empty($value->calleeproductid) ? $value->calleeproductid :"";
                    $data['calleetogatewaye'] =   !empty($value->calleetogatewaye164) ? $value->calleetogatewaye164 :"";
                    $data['calleetype'] =   !empty($value->calleetype) ? $value->calleetype :"";
                    $data['billingmode'] =   !empty($value->billingmode) ? $value->billingmode :"";
                    $data['calllevel'] =   !empty($value->calllevel) ? $value->calllevel :"";
                    $data['agentfeetime'] =   !empty($value->calleegatewayid) ? $value->calleegatewayid :"";
                    $data['starttime'] =   !empty($startTime) ? $startTime->format('Y-m-d H:i:s') : "";
                    $data['stoptime'] =   !empty($stopTime) ? $stopTime->format('Y-m-d H:i:s') : "";
                    $data['callerpdd'] =   !empty($value->callerpdd) ? $value->callerpdd :"";
                    $data['calleepdd'] =   !empty($value->calleepdd) ? $value->calleepdd :"";
                    $data['holdtime'] =   !empty($value->holdtime) ? $value->holdtime :"0";
                    $data['callerareacode'] =   !empty($value->callerareacode) ? $value->callerareacode :"";
                    $data['feetime'] =   !empty($value->feetime) ? $value->feetime :"0";
                    $data['fee']= !empty($value->fee) ? $value->fee :"0";
                    $data['tax']= !empty($value->tax) ? $value->tax :"0";
                    $data['suitefee']= !empty($value->suitefee) ? $value->suitefee :"0";
                    $data['suitefeetime']=!empty($value->suitefeetime) ? $value->suitefeetime :"0";
                    $data['incomefee']=!empty($value->incomefee) ? $value->incomefee :"0";
                    $data['incometax']=!empty($value->incometax) ? $value->incometax :"0";
                    $data['customeraccount']=!empty($value->customeraccount) ? $value->customeraccount :"";
                    $data['customername']=!empty($value->customername) ? $value->customername :"";
                    $data['calleeareacode']=!empty($value->calleeareacode) ? $value->calleeareacode :"";
                    $data['agentfee']=!empty($value->agentfee) ? $value->agentfee :"0";
                    $data['agenttax']=!empty($value->agenttax) ? $value->agenttax :"0";
                    $data['agentsuitefee']=!empty($value->agentsuitefee) ? $value->agentsuitefee :"0";
                    $data['agentsuitefeetime']=!empty($value->agentsuitefeetime) ? $value->agentsuitefeetime :"0";
                    $data['agentaccount']=!empty($value->agentaccount) ? $value->agentaccount :"";
                    $data['agentname']=!empty($value->agentname) ? $value->agentname :"";
                    $data['flowno']=!empty($value->flowno) ? $value->flowno :"";
                    $data['softswitchname']=!empty($value->softswitchname) ? $value->softswitchname :"";
                    $data['softswitchcallid']=!empty($value->softswitchcallid) ? $value->softswitchcallid :"";
                    $data['callercallid']=!empty($value->callercallid) ? $value->callercallid :"";
                    $data['calleecallid']=!empty($value->calleecallid) ? $value->calleecallid :"";
                    $data['rtpforward']=!empty($value->rtpforward) ? $value->rtpforward :"";
                    $data['enddirection']=!empty($value->enddirection) ? $value->enddirection :"0";
                    $data['endreason']=!empty($value->endreason) ? $value->endreason :"";
                    $data['billingtype']=!empty($value->billingtype) ? $value->billingtype :"0";
                    $data['cdrlevel']=!empty($value->cdrlevel) ? $value->cdrlevel :"0";
                    $data['agentcdr_id']=!empty($value->agentcdr_id) ? $value->agentcdr_id :"";
                    $data['transactionid']=!empty($value->transactionid) ? $value->transactionid :"";

                   
                    $list[]= $data;
                }
            }
            else{
                $data =array();
                $data['Account Holder Name'] =  "";
                $data['callere'] =   "";
                $data['calleraccesse'] = "";
                $data['calleee'] =  "";
                $data['calleeaccesse'] =  "";
                $data['callerip'] =  "";
                $data['callercodec'] =  "";
                $data['callergatewayid'] = "";
                $data['callerproductid'] = "";
                $data['callertogatewaye'] =   "";
                $data['callertype'] =  "";
                $data['calleeip'] =  "";
                $data['calleecodec'] = "";
                $data['calleegatewayid'] =  "";
                $data['calleeproductid'] =  "";
                $data['calleetogatewaye'] ="";
                $data['calleetype'] = "";
                $data['billingmode'] = "";
                $data['calllevel'] =  "";
                $data['agentfeetime'] =  "";
                $data['starttime'] = "";
                $data['stoptime'] =   "";
                $data['callerpdd'] = "";
                $data['calleepdd'] = "";
                $data['holdtime'] = "";
                $data['callerareacode'] ="";
                $data['feetime'] = "";
                $data['fee']="";
                $data['tax']= "";
                $data['suitefee']= "";
                $data['suitefeetime']="";
                $data['incomefee']="";
                $data['incometax']="";
                $data['customeraccount']="";
                $data['customername']="";
                $data['calleeareacode']="";
                $data['agentfee']="";
                $data['agenttax']="";
                $data['agentsuitefee']="";
                $data['agentsuitefeetime']="";
                $data['agentaccount']="";
                $data['agentname']="";
                $data['flowno']="";
                $data['softswitchname']="";
                $data['softswitchcallid']="";
                $data['callercallid']="";
                $data['calleecallid']="";
                $data['rtpforward']="";
                $data['enddirection']="";
                $data['endreason']="";
                $data['billingtype']="";
                $data['cdrlevel']= "";
                $data['agentcdr_id']="";
                $list[]= $data;
            }
                $destinationPath = public_path('storage/excel_files/');
                if (!file_exists($destinationPath)) {
                    File::isDirectory($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);
                }
                $exporthistory_arr = ExportCsvXlsxHistory::find($this->exporthistory_id);
                $excel = (new FastExcel($list))->export($destinationPath.$exporthistory_arr->file_name);
                $exporthistory_arr['file'] =  $exporthistory_arr->file_name;
                $exporthistory_arr['status'] = 'complete';
                $exporthistory_arr->save();
        }
        if($this->exporthistory_type == "Csv_file"){
            $downloads = CallHistory::where([['starttime' ,'>=', $start],['stoptime', '<=',  $end],['account_id',$AccountID]])->with('clients')->get();

            $list =array();
            $date = new \DateTime();
            $date2 = new \DateTime();
            $startTime="";
            $stopTime="";
            if(!$downloads->isEmpty()){
                foreach ( $downloads as $key => $value) {
                    $startTime = $value->starttime;
                    $stopTime = $value->stoptime;
                    $startTime =  $date->setTimestamp($startTime/1000);
                    $stopTime =  $date2->setTimestamp($stopTime/1000);
                  
                    $data =array();
                    $data['Account Holder Name'] = !empty($value->clients->firstname) ? $value->clients->firstname. $value->clients->lastname :"";
                    $data['callere'] =   !empty($value->callere164) ? $value->callere164 :"";
                    $data['calleraccesse'] =   !empty($value->calleraccesse164) ? $value->calleraccesse164 :"";
                    $data['calleee'] =   !empty($value->calleee164) ? $value->calleee164 :"";
                    $data['calleeaccesse'] =   !empty($value->calleeaccesse164) ? $value->calleeaccesse164 :"";
                    $data['callerip'] =   !empty($value->callerip) ? $value->callerip :"";
                    $data['callercodec'] =   !empty($value->callercodec) ? $value->callercodec :"";
                    $data['callergatewayid'] =   !empty($value->callergatewayid) ? $value->callergatewayid :"";
                    $data['callerproductid'] =   !empty($value->callerproductid) ? $value->callerproductid :"";
                    $data['callertogatewaye'] = !empty($value->callertogatewaye164) ?$value->callertogatewaye164 :"";
                    $data['callertype'] =   !empty($value->callertype) ? $value->callertype :"";
                    $data['calleeip'] = !empty($value->calleeip) ? $value->calleeip :"";
                    $data['calleecodec'] = !empty($value->calleecodec) ? $value->calleecodec :"";
                    $data['calleegatewayid'] = !empty($value->calleegatewayid) ? $value->calleegatewayid :"";
                    $data['calleeproductid'] = !empty($value->calleeproductid) ? $value->calleeproductid :"";
                    $data['calleetogatewaye'] = !empty($value->calleetogatewaye164) ? $value->calleetogatewaye164 :"";
                    $data['calleetype'] = !empty($value->calleetype) ? $value->calleetype :"";
                    $data['billingmode'] = !empty($value->billingmode) ? $value->billingmode :"";
                    $data['calllevel'] = !empty($value->calllevel) ? $value->calllevel :"";
                    $data['agentfeetime'] = !empty($value->calleegatewayid) ? $value->calleegatewayid :"";
                    $data['starttime'] = !empty($startTime) ? $startTime->format('Y-m-d H:i:s') : "";
                    $data['stoptime'] = !empty($stopTime) ? $stopTime->format('Y-m-d H:i:s') : "";
                    $data['callerpdd'] = !empty($value->callerpdd) ? $value->callerpdd :"";
                    $data['calleepdd'] = !empty($value->calleepdd) ? $value->calleepdd :"";
                    $data['holdtime'] = !empty($value->holdtime) ? $value->holdtime :"0";
                    $data['callerareacode'] = !empty($value->callerareacode) ? $value->callerareacode :"";
                    $data['feetime'] =  !empty($value->feetime) ? $value->feetime :"0";
                    $data['fee'] = !empty($value->fee) ? $value->fee :"0";
                    $data['tax'] = !empty($value->tax) ? $value->tax :"0";
                    $data['suitefee'] = !empty($value->suitefee) ? $value->suitefee :"0";
                    $data['suitefeetime'] = !empty($value->suitefeetime) ? $value->suitefeetime :"0";
                    $data['incomefee'] = !empty($value->incomefee) ? $value->incomefee :"0";
                    $data['incometax'] = !empty($value->incometax) ? $value->incometax :"0";
                    $data['customeraccount'] = !empty($value->customeraccount) ? $value->customeraccount :"";
                    $data['customername'] = !empty($value->customername) ? $value->customername :"";
                    $data['calleeareacode'] = !empty($value->calleeareacode) ? $value->calleeareacode :"";
                    $data['agentfee'] = !empty($value->agentfee) ? $value->agentfee :"0";
                    $data['agenttax'] = !empty($value->agenttax) ? $value->agenttax :"0";
                    $data['agentsuitefee'] = !empty($value->agentsuitefee) ? $value->agentsuitefee :"0";
                    $data['agentsuitefeetime'] = !empty($value->agentsuitefeetime) ? $value->agentsuitefeetime :"0";
                    $data['agentaccount']=!empty($value->agentaccount) ? $value->agentaccount :"";
                    $data['agentname']=!empty($value->agentname) ? $value->agentname :"";
                    $data['flowno']=!empty($value->flowno) ? $value->flowno :"";
                    $data['softswitchname']=!empty($value->softswitchname) ? $value->softswitchname :"";
                    $data['softswitchcallid']=!empty($value->softswitchcallid) ? $value->softswitchcallid :"";
                    $data['callercallid']=!empty($value->callercallid) ? $value->callercallid :"";
                    $data['calleecallid']=!empty($value->calleecallid) ? $value->calleecallid :"";
                    $data['rtpforward']=!empty($value->rtpforward) ? $value->rtpforward :"";
                    $data['enddirection']=!empty($value->enddirection) ? $value->enddirection :"0";
                    $data['endreason']=!empty($value->endreason) ? $value->endreason :"";
                    $data['billingtype']=!empty($value->billingtype) ? $value->billingtype :"0";
                    $data['cdrlevel']=!empty($value->cdrlevel) ? $value->cdrlevel :"0";
                    $data['agentcdr_id']=!empty($value->agentcdr_id) ? $value->agentcdr_id :"";
                    $data['transactionid']=!empty($value->transactionid) ? $value->transactionid :"";
                    $list[]= $data; 
                }
            }else{
                $data =array();
                $data['Account Holder Name'] =  "";
                $data['callere'] =   "";
                $data['calleraccesse'] = "";
                $data['calleee'] =  "";
                $data['calleeaccesse'] =  "";
                $data['callerip'] =  "";
                $data['callercodec'] =  "";
                $data['callergatewayid'] = "";
                $data['callerproductid'] = "";
                $data['callertogatewaye'] =   "";
                $data['callertype'] =  "";
                $data['calleeip'] =  "";
                $data['calleecodec'] = "";
                $data['calleegatewayid'] =  "";
                $data['calleeproductid'] =  "";
                $data['calleetogatewaye'] ="";
                $data['calleetype'] = "";
                $data['billingmode'] = "";
                $data['calllevel'] =  "";
                $data['agentfeetime'] =  "";
                $data['starttime'] = "";
                $data['stoptime'] =   "";
                $data['callerpdd'] = "";
                $data['calleepdd'] = "";
                $data['holdtime'] = "";
                $data['callerareacode'] ="";
                $data['feetime'] = "";
                $data['fee']="";
                $data['tax']= "";
                $data['suitefee']="";
                $data['suitefeetime']="";
                $data['incomefee']="";
                $data['incometax']="";
                $data['customeraccount']="";
                $data['customername']="";
                $data['calleeareacode']="";
                $data['agentfee']="";
                $data['agenttax']="";
                $data['agentsuitefee']="";
                $data['agentsuitefeetime']="";
                $data['agentaccount']="";
                $data['agentname']="";
                $data['flowno']="";
                $data['softswitchname']="";
                $data['softswitchcallid']="";
                $data['callercallid']="";
                $data['calleecallid']="";
                $data['rtpforward']="";
                $data['enddirection']="";
                $data['endreason']="";
                $data['billingtype']="";
                $data['cdrlevel']= "";
                $data['agentcdr_id']="";
                $list[]= $data;
            }
                $destinationPath = public_path('storage/csv_files/');
                if (!file_exists($destinationPath)) {
                    File::isDirectory($destinationPath) or File::makeDirectory($destinationPath, 0777, true, true);
                }
                $exporthistory_arr = ExportCsvXlsxHistory::find($this->exporthistory_id);
                $excel = (new FastExcel($list))->export($destinationPath.$exporthistory_arr->file_name);
                $exporthistory_arr['file'] =  $exporthistory_arr->file_name;
                $exporthistory_arr['status'] = 'complete';
                $exporthistory_arr->save();
        }
    }
}
