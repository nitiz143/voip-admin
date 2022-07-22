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
                                if(empty($csvImport)){
                                    // $customerArr  = $this->readCSV($newest['file'],array('delimiter' => ','));
                                    $csv_import_id = CsvImport::create($data);
                                }

                            }
                            $updated_at  = Carbon::now();
                            CronJob::where('id',$tasks->id)->update(array('updated_at'=>$updated_at));
                            if(empty($csvImport)){
                                    Mail::raw("This Job is run Sucessfully", function($message) use ($tasks)
                                    {
                                        $message->from('TestSucess@gmail.com');
                                        $message->to($tasks->success_email)->subject('Sucessfully');
                                    });
                            }
                        }
                    }
                }
            }
        }
        catch (exception $e) {
            Mail::raw("There is a Error", function($message) use ($tasks)
            {
                $message->from('TestError@gmail.com');
                $message->to($tasks->error_email)->subject('Error');
            });
        }

    }
}
