<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CronJob;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Config;
use App\Models\CallHistory;
use App\Models\CsvImport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Throwable;
use Log;
use App\Mail\ExceptionOccured;
class CsvImportCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csvImport:cron';

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

    public function readCSV($csvFile, $array)
    {
        $file_handle = fopen('public/storage/'.$csvFile, 'r');
        while (!feof($file_handle)) {
            $line_of_text[] = fgetcsv($file_handle, 0, $array['delimiter']);
        }
        fclose($file_handle);
        return $line_of_text;
    }

    public function handle()
    {
        $tasks = CronJob::where('cron_type','Download VOS SFTP File')->first();
        $created_at  = Carbon::now();
        CronJob::where('id',$tasks->id)->update(array('created_at'=>$created_at,'start_time' => $created_at,'updated_at' => NULL));
         try{
             $settings = Setting::all();
                if($settings->isNotEmpty()){
                    foreach ($settings as $setting) {
                        if($setting->protocol == 1){
                            Config::set('filesystems.disks.ftp.host', $setting->host);
                            Config::set('filesystems.disks.ftp.username', $setting->username);
                            Config::set('filesystems.disks.ftp.password', $setting->password);
                            Config::set('filesystems.disks.ftp.port', $setting->port);
                            Config::set('filesystems.disks.ftp.root', $setting->csv_path);
                            $disk = Storage::disk('ftp');

                        }else{
                            Config::set('filesystems.disks.sftp.host', $setting->host);
                            Config::set('filesystems.disks.sftp.username', $setting->username);
                            Config::set('filesystems.disks.sftp.password', $setting->password);
                            Config::set('filesystems.disks.sftp.port', $setting->port);
                            Config::set('filesystems.disks.sftp.root', $setting->csv_path);
                            $disk = Storage::disk('sftp');
                        }

                        // import file
                        $files = $disk->files("/");

                        $fileData = collect();

                        if(!empty($files)){
                            foreach ($files as $value) {
                                if($value != 'cdr.column'){
                                    $fileData->push([
                                        'file' => $value,
                                        'date' => $disk->lastModified($value)
                                    ]);
                                }
                            }

                            // get latest file
                         $newest = $fileData->sortByDesc('date')->first();

                            if(!empty($newest)){
                                if (!file_exists(public_path() . '/'. $setting->csv_path . $newest['file'])) {
                                    if($setting->protocol == 1){
                                        Storage::disk('public')->put($newest['file'], Storage::disk('ftp')->get($newest['file']));
                                    }else{
                                        Storage::disk('public')->put($newest['file'], Storage::disk('sftp')->get($newest['file']));
                                    }
                                    // check if file already exist
                                    $csvImport = CsvImport::where('csv_file',$newest['file'])->where('status',2)->first();
                                    $data = [
                                        'status' => 1,
                                        'csv_file' => $newest['file'],
                                    ];
                                    // if(empty($csvImport)){
                                    //     $customerArr  = $this->readCSV($newest['file'],array('delimiter' => ','));
                                    //     $csv_import_id = CsvImport::create($data);
                                    //     for ($i = 0; $i < count($customerArr); $i ++)
                                    //     {
                                    //         $history = [
                                    //             'callere164'=> $customerArr[$i] ?  $customerArr[$i][0] : '',
                                    //             'calleraccesse164'=> $customerArr[$i] ? $customerArr[$i][1] : '',
                                    //             'calleee164'=> $customerArr[$i] ? $customerArr[$i][2] : '',
                                    //             'calleeaccesse164'=> $customerArr[$i] ? $customerArr[$i][3] : '',
                                    //             'callerip'=> $customerArr[$i] ? $customerArr[$i][4] : '',
                                    //             'callercodec'=> $customerArr[$i] ? $customerArr[$i][5] : '',
                                    //             'callergatewayid'=> $customerArr[$i] ? $customerArr[$i][6] : '',
                                    //             'callerproductid'=> $customerArr[$i] ? $customerArr[$i][7] : '',
                                    //             'callertogatewaye164'=> $customerArr[$i] ? $customerArr[$i][8] : '',
                                    //             'callertype'=> $customerArr[$i] ? $customerArr[$i][9] : '',
                                    //             'calleeip'=> $customerArr[$i] ? $customerArr[$i][10] : '',
                                    //             'calleecodec'=> $customerArr[$i] ? $customerArr[$i][11] : '',
                                    //             'calleegatewayid'=> $customerArr[$i] ? $customerArr[$i][12] : '',
                                    //             'calleeproductid'=> $customerArr[$i] ? $customerArr[$i][13] : '',
                                    //             'calleetogatewaye164'=> $customerArr[$i] ? $customerArr[$i][14] : '',
                                    //             'calleetype'=> $customerArr[$i] ? $customerArr[$i][15] : '',
                                    //             'billingmode'=> $customerArr[$i] ? $customerArr[$i][16] : '',
                                    //             'calllevel'=> $customerArr[$i] ? $customerArr[$i][17] : '',
                                    //             'agentfeetime'=> $customerArr[$i] ? $customerArr[$i][18] : '',
                                    //             'starttime'=> $customerArr[$i] ? $customerArr[$i][19] : '',
                                    //             'stoptime'=> $customerArr[$i] ? $customerArr[$i][20] : '',
                                    //             'callerpdd'=> $customerArr[$i] ? $customerArr[$i][21] : '',
                                    //             'calleepdd'=> $customerArr[$i] ? $customerArr[$i][22] : '',
                                    //             'holdtime'=> $customerArr[$i] ? $customerArr[$i][23] : '',
                                    //             'callerareacode'=> $customerArr[$i] ? $customerArr[$i][24] : '',
                                    //             'feetime'=> $customerArr[$i] ? $customerArr[$i][25] : '',
                                    //             'fee'=> $customerArr[$i] ? $customerArr[$i][26] : '',
                                    //             'tax'=> $customerArr[$i] ? $customerArr[$i][27] : '',
                                    //             'suitefee'=> $customerArr[$i] ? $customerArr[$i][28] : '',
                                    //             'suitefeetime'=> $customerArr[$i] ? $customerArr[$i][29] : '',
                                    //             'incomefee'=> $customerArr[$i] ? $customerArr[$i][30] : '',
                                    //             'incometax'=> $customerArr[$i] ? $customerArr[$i][31] : '',
                                    //             'customeraccount'=> $customerArr[$i] ? $customerArr[$i][32] : '',
                                    //             'customername'=> $customerArr[$i] ? $customerArr[$i][33] : '',
                                    //             'calleeareacode'=> $customerArr[$i] ? $customerArr[$i][34] : '',
                                    //             'agentfee'=> $customerArr[$i] ? $customerArr[$i][35] : '',
                                    //             'agenttax'=> $customerArr[$i] ? $customerArr[$i][36] : '',
                                    //             'agentsuitefee'=> $customerArr[$i] ? $customerArr[$i][37] : '',
                                    //             'agentsuitefeetime'=> $customerArr[$i] ? $customerArr[$i][38] : '',
                                    //             'agentaccount'=> $customerArr[$i] ? $customerArr[$i][39] : '',
                                    //             'agentname'=> $customerArr[$i] ? $customerArr[$i][40] : '',
                                    //             'flowno'=> $customerArr[$i] ? $customerArr[$i][41] : '',
                                    //             'softswitchname'=> $customerArr[$i] ? $customerArr[$i][42] : '',
                                    //             'softswitchcallid'=> $customerArr[$i] ? $customerArr[$i][43] : '',
                                    //             'callercallid'=> $customerArr[$i] ? $customerArr[$i][44] : '',
                                    //             'calleecallid'=> $customerArr[$i] ? $customerArr[$i][45] : '',
                                    //             'rtpforward'=> $customerArr[$i] ? $customerArr[$i][46] : '',
                                    //             'enddirection'=> $customerArr[$i] ? $customerArr[$i][47] : '',
                                    //             'endreason'=> $customerArr[$i] ? $customerArr[$i][48] : '',
                                    //             'billingtype'=> $customerArr[$i] ? $customerArr[$i][49] : '',
                                    //             'cdrlevel'=> $customerArr[$i] ? $customerArr[$i][50] : '',
                                    //             'agentcdr_id'=> $customerArr[$i] ? $customerArr[$i][51] : '',
                                    //             // 'transactionid'=> $customerArr[$i] ? $customerArr[$i][52] : '',
                                    //         ];
                                    //         $getcsv = CsvImport::find($csv_import_id->id);
                                    //         if(!empty($getcsv)){
                                    //             if(!empty($customerArr[$i][0])){
                                    //                 if(CallHistory::create($history)){
                                    //                     $getcsv->update(['status' => 2]);
                                    //                 }
                                    //             }
                                    //             $updated_at  = Carbon::now();
                                    //             CronJob::where('id',$tasks->id)->update(array('updated_at'=>$updated_at));

                                    //         }

                                    //     }

                                    // }

                                    if(!empty($tasks->success_email)){
                                        Mail::raw("This Job is run Sucessfully", function($message) use ($tasks)
                                        {
                                            $message->from('TestSucess@gmail.com');
                                            $message->to($tasks->success_email)->subject('Sucessfully');
                                        });
                                    }
                                     $updated_at  = Carbon::now();
                                    CronJob::where('id',$tasks->id)->update(array('updated_at'=>$updated_at,'start_time' => ''));
                                }
                            }
                        }
                        $disk->getDriver()->getAdapter()->disconnect();
                    }
                }
            }
        catch (Throwable $exception) {
            $content= [];
            if(!empty($tasks->error_email)){
                $content['message'] = $exception->getMessage();
                $content['file'] = $exception->getFile();
                $content['line'] = $exception->getLine();
                $content['trace'] = $exception->getTrace();

                $content['url'] = request()->url();
                $content['body'] = request()->all();
                $content['ip'] = request()->ip();

                Mail::to($tasks->error_email)->send(new ExceptionOccured($content));
            }
        }

    }
}
