<?php

namespace App\Console\Commands;

use App\Models\CallHistory;
use App\Models\CsvImport;
use App\Models\CronJob;
use App\Models\Setting;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;
class DownloadCsvImportCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tasks = CronJob::where('cron_type','Download VOS SFTP File')->first();
        $created_at  = Carbon::now();
        CronJob::where('id',$tasks->id)->update(array('created_at'=>$created_at,'start_time' => $created_at,'updated_at' => NULL));
        $value = CsvImport::where('status',1)->get();
        if(!empty($value)){
            $history = [];
            foreach ($value as $key => $csvImport) {
               
                if($csvImport->version == 1){
                    if(Storage::disk('public')->put($csvImport->csv_file, Storage::get('voip/'.$csvImport->csv_file))){
                        $callarr = (new FastExcel)->withoutHeaders()->import(Storage::disk('public')->path($csvImport->csv_file), function ($line) {
                            return $line;
                        });
                        if(!empty($callarr)){
                            foreach($callarr as $i=>$call){
                                $history[] = [
                                    'callere164'=>$call[0] ? $call[0] : '',
                                    'calleraccesse164'=>$call[1] ? $call[1] : '',
                                    'calleee164'=>$call[2] ? $call[2] : '',
                                    'calleeaccesse164'=>$call[3] ? $call[3] : '',
                                    'callerip'=>$call[4] ? $call[4] : '',
                                    'callercodec'=>$call[5] ? $call[5] : '',
                                    'callergatewayid'=>$call[6] ? $call[6] : '',
                                    'callerproductid'=>	$call[7] ? $call[7] : '',
                                    'callertogatewaye164'=>$call[8] ? $call[8] : '',
                                    'callertype'=>$call[9] ? $call[9] : '',
                                    'calleeip'=>$call[10] ? $call[10] : '',
                                    'calleecodec'=>$call[11] ? $call[11] : '',
                                    'calleegatewayid'=>$call[12] ? $call[12] : '',
                                    'calleeproductid'=>	$call[13] ? $call[13] : '',
                                    'calleetogatewaye164'=>$call[14] ? $call[14] : '',
                                    'calleetype'=>$call[15] ? $call[15] : '',
                                    'billingmode'=>$call[16] ? $call[16] : '',
                                    'calllevel'=>$call[17] ? $call[17] : '',
                                    'agentfeetime'=>$call[18] ? $call[18] : '',
                                    'starttime'=>$call[19] ? $call[19] : '',
                                    'stoptime'=>$call[20] ? $call[20] : '',
                                    'callerpdd'=>$call[21] ? $call[21] : '',
                                    'calleepdd'=>$call[22] ? $call[22] : '',
                                    'holdtime'=>$call[23] ? $call[23] : '',
                                    'callerareacode'=>$call[24] ? $call[24] : '',
                                    'feetime'=>$call[25] ? $call[25] : '',
                                    'fee'=>$call[26] ? $call[26] : '',
                                    'tax'=>$call[27] ? $call[27] : '',
                                    'suitefee'=>$call[28] ? $call[28] : '',
                                    'suitefeetime'=>$call[29] ? $call[29] : '',
                                    'incomefee'=>$call[30] ? $call[30] : '',
                                    'incometax'=>$call[31] ? $call[31] : '',
                                    'customeraccount'=>$call[32] ? $call[32] : '',
                                    'customername'=>$call[33] ? $call[33] : '',
                                    'calleeareacode'=>$call[34] ? $call[34] : '',
                                    'agentfee'=>$call[35] ? $call[35] : '',
                                    'agenttax'=>$call[36] ? $call[36] : '',
                                    'agentsuitefee'=>$call[37] ? $call[37] : '',
                                    'agentsuitefeetime'=>$call[38] ? $call[38] : '',
                                    'agentaccount'=>$call[39] ? $call[39] : '',
                                    'agentname'=>$call[40] ? $call[40] : '',
                                    'flowno'=>$call[41] ? $call[41] : '',
                                    'softswitchname'=>$call[42] ? $call[42] : '',
                                    'softswitchcallid'=>$call[43] ? $call[43] : '',
                                    'callercallid'=>$call[44] ? $call[44] : '',
                                    'calleecallid'=>$call[45] ? $call[45] : '',
                                    'rtpforward'=>$call[46] ? $call[46] : '',
                                    'enddirection'=>$call[47] ? $call[47] : '',
                                    'endreason'=>$call[48] ? $call[48] : '',
                                    'billingtype'=>$call[49] ? $call[49] : '',
                                    'cdrlevel'=>$call[50] ? $call[50] : '',
                                    'agentcdr_id'=>$call[51] ? $call[51] : '',
                                ];
                            }
                            if(!empty($history)){
                                CallHistory::insert($history);
                                $getcsv = CsvImport::find($csvImport->id);
                                $getcsv->update(['status' => 2]);
                                Storage::disk('public')->delete($csvImport->csv_file);
                            }
                            $updated_at  = Carbon::now();
                            CronJob::where('id',$tasks->id)->update(array('updated_at'=>$updated_at,'start_time' => ''));
                        }
                    }
                }elseif($csvImport->version == 2){
                    if(Storage::disk('public')->put($csvImport->csv_file, Storage::get('voip/'.$csvImport->csv_file))){
                        $callarr = (new FastExcel)->withoutHeaders()->import(Storage::disk('public')->path($csvImport->csv_file), function ($line) {
                            return $line;
                        });
                        if(!empty($callarr)){
                            foreach($callarr as $i=>$call){
                                $history[] = [
                                    'callere164'=>$call[0] ? $call[0] : '',
                                    'calleraccesse164'=>$call[1] ? $call[1] : '',
                                    'calleee164'=>$call[2] ? $call[2] : '',
                                    'calleeaccesse164'=>$call[3] ? $call[3] : '',
                                    'callerip'=>$call[4] ? $call[4] : '',
                                    'callercodec'=>$call[5] ? $call[5] : '',
                                    'callergatewayid'=>$call[6] ? $call[6] : '',
                                    'callerproductid'=>	$call[7] ? $call[7] : '',
                                    'callertogatewaye164'=>$call[8] ? $call[8] : '',
                                    'callertype'=>$call[9] ? $call[9] : '',
                                    'calleeip'=>$call[10] ? $call[10] : '',
                                    'calleecodec'=>$call[11] ? $call[11] : '',
                                    'calleegatewayid'=>$call[12] ? $call[12] : '',
                                    'calleeproductid'=>	$call[13] ? $call[13] : '',
                                    'calleetogatewaye164'=>$call[14] ? $call[14] : '',
                                    'calleetype'=>$call[15] ? $call[15] : '',
                                    'billingmode'=>$call[16] ? $call[16] : '',
                                    'calllevel'=>$call[17] ? $call[17] : '',
                                    'agentfeetime'=>$call[18] ? $call[18] : '',
                                    'starttime'=>$call[19] ? $call[19] : '',
                                    'stoptime'=>$call[20] ? $call[20] : '',
                                    'callerpdd'=>$call[21] ? $call[21] : '',
                                    'calleepdd'=>$call[22] ? $call[22] : '',
                                    'holdtime'=>$call[23] ? $call[23] : '',
                                    'callerareacode'=>$call[24] ? $call[24] : '',
                                    'feetime'=>$call[25] ? $call[25] : '',
                                    'fee'=>$call[26] ? $call[26] : '',
                                    'tax'=>$call[27] ? $call[27] : '',
                                    'suitefee'=>$call[28] ? $call[28] : '',
                                    'suitefeetime'=>$call[29] ? $call[29] : '',
                                    'incomefee'=>$call[30] ? $call[30] : '',
                                    'incometax'=>$call[31] ? $call[31] : '',
                                    'customeraccount'=>$call[32] ? $call[32] : '',
                                    'customername'=>$call[33] ? $call[33] : '',
                                    'calleeareacode'=>$call[34] ? $call[34] : '',
                                    'agentfee'=>$call[35] ? $call[35] : '',
                                    'agenttax'=>$call[36] ? $call[36] : '',
                                    'agentsuitefee'=>$call[37] ? $call[37] : '',
                                    'agentsuitefeetime'=>$call[38] ? $call[38] : '',
                                    'agentaccount'=>$call[39] ? $call[39] : '',
                                    'agentname'=>$call[40] ? $call[40] : '',
                                    'flowno'=>$call[41] ? $call[41] : '',
                                    'softswitchname'=>$call[42] ? $call[42] : '',
                                    'softswitchcallid'=>$call[43] ? $call[43] : '',
                                    'callercallid'=>$call[44] ? $call[44] : '',
                                    'calleecallid'=>$call[45] ? $call[45] : '',
                                    'rtpforward'=>$call[46] ? $call[46] : '',
                                    'enddirection'=>$call[47] ? $call[47] : '',
                                    'endreason'=>$call[48] ? $call[48] : '',
                                    'billingtype'=>$call[49] ? $call[49] : '',
                                    'cdrlevel'=>$call[50] ? $call[50] : '',
                                    'agentcdr_id'=>$call[51] ? $call[51] : '',
                                    'transactionid'=>$call[52] ? $call[52] : '',
                                ];


                            }
                          
                            if(!empty($history)){
                                $datas = CallHistory::insert($history);
                                // dd($history);
                                $getcsv = CsvImport::find($csvImport->id);
                                $getcsv->update(['status' => 2]);
                                Storage::disk('public')->delete($csvImport->csv_file);
                            }
                            $updated_at  = Carbon::now();
                            CronJob::where('id',$tasks->id)->update(array('updated_at'=>$updated_at,'start_time' => ''));
                        }
                    }
                }
            }
        }
        // if(!empty($getcsv)){
        //     Mail::raw("This Job is run Sucessfully", function($message) use ($tasks)
        //         {
        //             $message->from('nitiz143@gmail.com');
        //             $message->to($tasks->success_email)->subject('Sucessfully');
        //         });
        // }
        // else{
        //         Mail::raw("There is a Error", function($message) use ($tasks)
        //         {
        //             $message->from('nitiz143@gmail.com');
        //             $message->to($tasks->error_email)->subject('Error');
        //         });
        //     }
        // return 0;
    }
}
