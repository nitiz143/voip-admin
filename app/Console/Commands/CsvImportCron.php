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

    public function handle()
    {
        $tasks = CronJob::where('cron_type','Download VOS SFTP File')->first();

        $created_at  = Carbon::now();
        CronJob::where('id',$tasks->id)->update(array('created_at'=>$created_at,'start_time' => $created_at,'updated_at' => NULL));
       // try{
             $settings = Setting::get();

                if($settings->isNotEmpty()){

                    foreach ($settings as $setting) {
                        //dd($setting->protocol);
                        if($setting->protocol == 1){
                            Config::set('filesystems.disks.ftp.host',$setting->host);
                            Config::set('filesystems.disks.ftp.username',$setting->username);
                            Config::set('filesystems.disks.ftp.password',$setting->password);
                            Config::set('filesystems.disks.ftp.port',$setting->port);
                            Config::set('filesystems.disks.ftp.root',$setting->csv_path);
                            $disk = Storage::disk('ftp');

                        }else{
                            Config::set('filesystems.disks.sftp.host', $setting->host);
                            Config::set('filesystems.disks.sftp.username',$setting->username);
                            Config::set('filesystems.disks.sftp.password',$setting->password);
                            Config::set('filesystems.disks.sftp.port',$setting->port);
                            Config::set('filesystems.disks.sftp.root',$setting->csv_path);
                            $disk = Storage::disk('sftp');
                        }

                        // import file
                        $files = collect($disk->files('/'))->sortKeysDesc();
                        if(!empty($files)){
                            // get latest file
                         $newest = $files->first();
                            if(!empty($newest)){
                                    if($setting->protocol == 1){
                                        Storage::put('voip/'.$newest, Storage::disk('ftp')->get($newest),'public');
                                    }else{
                                        Storage::put('voip/'.$newest, Storage::disk('sftp')->get($newest),'public');
                                    }
                                    // check if file already exist
                                    $csvImport = CsvImport::where('csv_file',$newest)->first();
                                    if(empty($csvImport)){
                                        $data = [
                                            'status' => 1,
                                            'csv_file' => $newest,
                                            'version' => $setting->version,
                                            'setting_id' => $setting->id,
                                        ];
                                        CsvImport::create($data);
                                    }

                                    // if(!empty($tasks->success_email)){
                                    //     Mail::raw("This Job is run Sucessfully", function($message) use ($tasks)
                                    //     {
                                    //         $message->from('TestSucess@gmail.com');
                                    //         $message->to($tasks->success_email)->subject('Sucessfully');
                                    //     });
                                    // }
                                    $updated_at  = Carbon::now();
                                    CronJob::where('id',$tasks->id)->update(array('updated_at'=>$updated_at));
                                }
                        }
                        $disk->getDriver()->getAdapter()->disconnect();
                    }
                }
            // }
        // catch (Throwable $exception) {
        //     dd($exception);
        //     $content= [];
            // if(!empty($tasks->error_email)){
            //     $content['message'] = $exception->getMessage();
            //     $content['file'] = $exception->getFile();
            //     $content['line'] = $exception->getLine();
            //     $content['trace'] = $exception->getTrace();

            //     $content['url'] = request()->url();
            //     $content['body'] = request()->all();
            //     $content['ip'] = request()->ip();

            //     Mail::to($tasks->error_email)->send(new ExceptionOccured($content));
            // }
        //}

    }
}
