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
            foreach ($value as $key => $csvImport) {

                if($csvImport->setting_id == '1'){
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
                }elseif($csvImport->setting_id == '2'){
                    if(Storage::disk('public')->put($csvImport->csv_file, Storage::get('voip/'.$csvImport->csv_file))){
                        $callarr = (new FastExcel)->withoutHeaders()->import(Storage::disk('public')->path($csvImport->csv_file), function ($line) {
                            return $line;
                        });
                        if(!empty($callarr)){
                            foreach($callarr as $i=>$call){
                                $history[] = [
                                    'callere164'=>$call[1] ? $call[1] : '0',
                                    'calleraccesse164'=>$call[2] ? $call[2] : '0',
                                    'calleee164'=>$call[3] ? $call[3] : '0',
                                    'calleeaccesse164'=>$call[4] ? $call[4] : '0',
                                    'callerip'=>$call[5] ? $call[5] : '0',
                                        //    'callerrtpip'=>$call[6] ? $call[6] : '0',
                                    'callercodec'=>$call[6] ? $call[6] : '0',
                                    'callergatewayid'=>$call[7] ? $call[7] : '0',
                                    'callerproductid'=>$call[8] ? $call[8] : '0',
                                    'callertogatewaye164'=>$call[9] ? $call[9] : '0',
                                    'callertype'=>$call[10] ? $call[10] : '0',
                                    'calleeip'=>$call[11] ? $call[11] : '0',
                                        //   'calleertpip'=>$call[13] ? $call[13] : '0',
                                    'calleecodec'=>$call[12] ? $call[12] : '0',
                                    'calleegatewayid'=>$call[13] ? $call[13] : '0',
                                    'calleeproductid'=>$call[14] ? $call[14] : '0',
                                    'calleetogatewaye164'=>$call[15] ? $call[15] : '0',
                                    'calleetype'=>$call[16] ? $call[16] : '0',
                                    'billingmode'=>$call[17] ? $call[17] : '0',
                                    'calllevel'=>$call[18] ? $call[18] : '0',
                                    'agentfeetime'=>$call[19] ? $call[19] : '0',
                                    'starttime'=>$call[20] ? $call[20] : '0',
                                    'stoptime'=>$call[21] ? $call[21] : '0',
                                    'callerpdd'=>$call[22] ? $call[22] : '0',
                                    'calleepdd'=>$call[23] ? $call[23] : '0',
                                    'holdtime'=>$call[24] ? $call[24] : '0',
                                    'callerareacode'=>$call[25] ? $call[25] : '0',
                                    'feetime'=>$call[26] ? $call[26] : '0',
                                    'fee'=>$call[27] ? $call[27] : '0',
                                    'tax'=>$call[28] ? $call[28] : '0',
                                    'suitefee'=>$call[29] ? $call[29] : '0',
                                    'suitefeetime'=>$call[30] ? $call[30] : '0',
                                    'incomefee'=>$call[31] ? $call[31] : '0',
                                    'incometax'=>$call[32] ? $call[32] : '0',
                                    'customeraccount'=>$call[33] ? $call[33] : '0',
                                    'customername'=>$call[34] ? $call[34] : '0',
                                    'calleeareacode'=>$call[35] ? $call[35] : '0',
                                    'agentfee'=>$call[36] ? $call[36] : '0',
                                    'agenttax'=>$call[37] ? $call[37] : '0',
                                    'agentsuitefee'=>$call[38] ? $call[38] : '0',
                                    'agentsuitefeetime'=>$call[39] ? $call[39] : '0',
                                    'agentaccount'=>$call[40] ? $call[40] : '0',
                                    'agentname'=>$call[41] ? $call[41] : '0',
                                    'flowno'=>$call[42] ? $call[42] : '0',
                                    'softswitchname'=>$call[43] ? $call[43] : '0',
                                    'softswitchcallid'=>$call[44] ? $call[44] : '0',
                                    'callercallid'=>$call[45] ? $call[45] : '0',
                                            // 'calleroriginalcallid'=>$call[48] ? $call[48] : '0',
                                    'calleecallid'=>$call[46] ? $call[46] : '',
                                            // 'calleroriginalinfo'=>$call[50] ? $call[50] : '0',
                                    'rtpforward'=>$call[47] ? $call[47] : '0',
                                    'enddirection'=>$call[48] ? $call[48] : '0',
                                    'endreason'=>$call[49] ? $call[49] : '0',
                                    'billingtype'=>$call[50] ? $call[50] : '0',
                                    'cdrlevel'=>$call[51] ? $call[51] : '0',
                                    'agentcdr_id'=>$call[52] ? $call[52] : '0',
                                            // 'sipreasonheader'=>$call[57] ? $call[57] : '0',
                                            // 'recordstarttime'=>$call[58] ? $call[58] : '0',
                                    'transactionid'=>$call[53] ? $call[53] : '0',
                                            // 'flownofirst'=>$call[60] ? $call[60] : '0',
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
