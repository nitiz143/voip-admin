<?php

namespace App\Console\Commands;
use App\Models\Billing;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateBilling extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Billing:cron';

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
        $billings = Billing::query('')->get();
        if(!empty($billings)){
            foreach ($billings as $key => $bill) {
                $data = array();
                if($bill->billing_cycle == 'daily'){
                    try{
                        $next_invoice_date = \Carbon\Carbon::parse($bill->next_invoice_date)->format('Y-m-d');
                        $next_charge_date = \Carbon\Carbon::parse($bill->next_charge_date)->format('Y-m-d');
                        $yesterday = \Carbon\Carbon::yesterday()->format('Y-m-d');
                        $now = \Carbon\Carbon::now()->format('Y-m-d');
    
                        if(!empty($next_invoice_date)){
                            if($next_invoice_date == $now){
                                $data['last_invoice_date'] =  $next_invoice_date;
                                $data['next_invoice_date'] =  \Carbon\Carbon::tomorrow()->format('Y-m-d');
                            
                            }
                        }
                        if(!empty($next_charge_date)){
                            if($next_charge_date == $yesterday){
                                $data['last_charge_date'] =  $yesterday;
                                $data['next_charge_date'] =  \Carbon\Carbon::now()->format('Y-m-d');
                            }
                        }
                        Billing::where('id',$bill->id)->update($data);
                    }
                    catch (\Exception $e) {
                        dd($e);
                    }
                }
                if($bill->billing_cycle == 'weekly'){
                    try{
                        $next_invoice_date = \Carbon\Carbon::parse($bill->next_invoice_date)->format('Y-m-d');
                        $next_charge_date = \Carbon\Carbon::parse($bill->next_charge_date)->format('Y-m-d');
                        $now = \Carbon\Carbon::now()->format('Y-m-d');
                        $days = 7;
                        if(!empty($next_invoice_date)){
                            if($next_invoice_date == $now){
                                $data['last_invoice_date'] =  $next_invoice_date;
                                $data['next_invoice_date'] =  \Carbon\Carbon::parse($next_invoice_date)->addDays(7);
                            
                            }
                        }
                        if(!empty($next_charge_date)){
                            if($next_charge_date == $yesterday){
                                $data['last_charge_date'] =  $next_charge_date;
                                $data['next_charge_date'] = \Carbon\Carbon::parse( $next_charge_date)->addDays(7);
                            }
                        }
                        Billing::where('id',$bill->id)->update($data);
                    }
                    catch (\Exception $e) {
                        dd($e);
                    }
                }
            }
        }
    }
}
