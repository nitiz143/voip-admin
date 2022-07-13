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
        CronJob::where('id',$tasks->id)->update(array('created_at'=>$created_at));
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
                            if(empty($csvImport)){
                                $customerArr  = $this->readCSV($newest['file'],array('delimiter' => ','));
                                $csv_import_id = CsvImport::create($data);
                                for ($i = 0; $i < count($customerArr); $i ++)
                                {
                                    $history = [
                                        'caller_id' => $customerArr[$i] ?  $customerArr[$i][0] : '',
                                        'callere164' => $customerArr[$i] ? $customerArr[$i][1] : '',
                                        'calleraccesse164' => $customerArr[$i] ? $customerArr[$i][2] : '',
                                        'calleee164' => $customerArr[$i] ? $customerArr[$i][3] : '',
                                        'calleeaccesse164' => $customerArr[$i] ? $customerArr[$i][4] : '',
                                        'callerip' => $customerArr[$i] ? $customerArr[$i][5] : '',
                                        'callergatewayh323id' => $customerArr[$i] ? $customerArr[$i][6] : '',
                                        'callerproductid' => $customerArr[$i] ? $customerArr[$i][7] : '',
                                        'callertogatewaye164' => $customerArr[$i] ? $customerArr[$i][8] : '',
                                        'calleeip' => $customerArr[$i] ? $customerArr[$i][9] : '',
                                        'calleegatewayh323id' => $customerArr[$i] ? $customerArr[$i][10] : '',
                                        'calleetogatewaye164' => $customerArr[$i] ? $customerArr[$i][11] : '',
                                        'billingmode' => $customerArr[$i] ? $customerArr[$i][12] : '',
                                        'calllevel' => $customerArr[$i] ? $customerArr[$i][13] : '',
                                        'agentfeetime' => $customerArr[$i] ? $customerArr[$i][14] : '',
                                        'starttime' => $customerArr[$i] ? $customerArr[$i][15] : '',
                                        'stoptime' => $customerArr[$i] ? $customerArr[$i][16] : '',
                                        'pdd' => $customerArr[$i] ? $customerArr[$i][17] : '',
                                        'holdtime' => $customerArr[$i] ? $customerArr[$i][18] : '',
                                        'feeprefix' => $customerArr[$i] ? $customerArr[$i][19] : '',
                                        'feetime' => $customerArr[$i] ? $customerArr[$i][20] : '',
                                        'fee' => $customerArr[$i] ? $customerArr[$i][21] : '',
                                        'suitefee' => $customerArr[$i] ? $customerArr[$i][22] : '',
                                        'suitefeetime' => $customerArr[$i] ? $customerArr[$i][23] : '',
                                        'incomefee' => $customerArr[$i] ?  $customerArr[$i][24] : '',
                                        'customername' => $customerArr[$i] ? $customerArr[$i][25] : '',
                                        'agentfeeprefix' => $customerArr[$i] ? $customerArr[$i][26] : '',
                                        'agentfee' => $customerArr[$i] ? $customerArr[$i][27] : '',
                                        'agentsuitefee' => $customerArr[$i] ? $customerArr[$i][28] : '',
                                        'agentsuitefeetime' => $customerArr[$i]? $customerArr[$i][29] : '',
                                        'agentaccount' => $customerArr[$i] ? $customerArr[$i][30] : '',
                                        'agentname' => $customerArr[$i] ? $customerArr[$i][31] : '',
                                        'flowno' => $customerArr[$i] ? $customerArr[$i][32] : '',
                                        'softswitchdn' => $customerArr[$i] ? $customerArr[$i][33] : '',
                                        'enddirection' => $customerArr[$i] ? $customerArr[$i][34] : '',
                                        'endreason' => $customerArr[$i] ? $customerArr[$i][35] : '',
                                        'calleebilling' => $customerArr[$i] ? $customerArr[$i][36] : '',
                                        'cdrlevel' => $customerArr[$i] ? $customerArr[$i][37] : '',
                                        'subcdr_id' => $customerArr[$i] ? $customerArr[$i][38] : '',
                                    ];
                                    $getcsv = CsvImport::find($csv_import_id->id);
                                    if(!empty($getcsv)){
                                        if(!empty($customerArr[$i][0])){
                                            if(CallHistory::create($history)){
                                                $getcsv->update(['status' => 2]);
                                            }
                                        }
                                        $updated_at  = Carbon::now();
                                        CronJob::where('id',$tasks->id)->update(array('updated_at'=>$updated_at));

                                    }

                                }
                            }
                        }
                    }
                }
            }
        }
        // return 0;
    }
}
