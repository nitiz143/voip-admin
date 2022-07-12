<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CronJob;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Config;
use App\Models\CallHistory;
use App\Models\CsvImport;

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
        $settings = Setting::all();
        if($settings->isNotEmpty()){
            foreach ($settings as $setting) {
                $set = Setting::find($setting->id);
                Config::set('filesystems.host', $setting->host);
                Config::set('filesystems.username', $setting->username);
                Config::set('filesystems.password', $setting->password);
                Config::set('filesystems.port', $setting->port);
                // import file
                $disk = Storage::disk('ftp');
                $files = $disk->files($setting->csv_path."/");
                $fileData = collect();
                if(!empty($files)){
                    foreach ($files as $key => $value) {
                        $fileData->push([
                            'file' => str_replace("/".$setting->csv_path, "", $value),
                            'date' => $disk->lastModified(str_replace("/".$setting->csv_path, "", $value) )
                        ]);
                        
                    }
                    // get latest file
                    $newest = $fileData->sortByDesc('date')->first();
                    if(!empty($newest)){
                        if (!file_exists( public_path() . '/'. $setting->csv_path . $newest['file'])) {
                            Storage::disk('public')->put(str_replace("/".$setting->csv_path, "", $newest['file']), Storage::disk('ftp')->get($newest['file']));
                            // check if file already exist
                            $csvImport = CsvImport::where('csv_file',$newest['file'])->where('status',2)->first();
                            $data = [
                                'status' => 1,
                                'csv_file' => $newest['file'],
                            ];
                            if(empty($csvImport)){
                                $customerArr  = $this->readCSV(str_replace("/".$setting->csv_path, "", $newest['file']),array('delimiter' => ','));
                                CsvImport::create($data);
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
                                    $getcsv = CsvImport::latest()->first();
                                    if(!empty($getcsv)){
                                        if(!empty($customerArr[$i][0])){
                                            // check if call history already exist
                                            $call_history = CallHistory::where('caller_id',$customerArr[$i][0])->first();
                                            if(!empty($call_history)){
                                                if($call_history->update($history)){
                                                    $getcsv->update(['status' => 2]);
                                                }
                                            }else{
                                                if(CallHistory::create($history)){
                                                    $getcsv->update(['status' => 2]);
                                                }
                                            }
                                        }
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
