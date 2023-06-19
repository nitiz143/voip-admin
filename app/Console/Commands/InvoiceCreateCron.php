<?php

namespace App\Console\Commands;
use App\Models\Client;
use Carbon\Carbon;
use Auth;
use App\Utils\RandomUtil;
use App\Models\ExportHistory;
use App\Jobs\InvoiceGenrateJob;
use App\Models\Billing;
use Illuminate\Console\Command;

class InvoiceCreateCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice_create:cron';

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
        $clients = Client::query('')->with('billing')->get();
        if(!empty($clients)){
            foreach ($clients as $key => $client) {
                if(!empty($client->billing[0])){
                    $data = array();
                    if($client->billing[0]->billing_cycle == 'daily'){
                        try{
                            $next_invoice_date = $client->billing[0]->next_invoice_date;
                            $next_charge_date = $client->billing[0]->next_charge_date;
                            $yesterday = \Carbon\Carbon::yesterday()->format('Y-m-d');
                            $now = \Carbon\Carbon::now()->format('Y-m-d');
                            if(!empty($next_invoice_date) && !empty($next_charge_date)){
                                if($next_invoice_date <= $now && $next_charge_date < $now){
                                    $data['client_id'] = !empty($client->id) ? $client->id : " ";
                                    $data['report_type'] = "invoice_pdf_export";
                                    $data['status'] = 'pending';
                                    $data['Invoice_no'] =  RandomUtil::randomString('Invoice_');
                                    $code = random_int(100000, 999999);
                                    $data['file_name'] = date('YmdHis').'-'.$code.".pdf";
                                    $exporthistory = ExportHistory::create($data);

                                    $value = Client::where('id',$client->billing[0]->account_id)->get();
                                    $value['type'] = "Customer";
                                    $value['AccountID'] = $client->billing[0]->account_id;
                                    $value['StartDate'] =   $yesterday;
                                    $value['EndDate'] =   $yesterday;
                                    $value['zerovaluecost'] =  0;
                                    $value['StartTime'] =  "00:00:00";
                                    $value['EndTime'] =  "23:59:59";
                                    if(!empty($exporthistory)){
                                        $exporthistory_id = $exporthistory->id;
                                        $authUser = 0;
                                        $invoice_pdf = new InvoiceGenrateJob($value,$authUser,$exporthistory_id);
                                        dispatch($invoice_pdf);
                                    }    

                                    $billing_data['last_invoice_date'] =  $next_invoice_date;
                                    $billing_data['next_invoice_date'] =  \Carbon\Carbon::tomorrow()->format('Y-m-d');
    
                                    $billing_data['last_charge_date'] =  $yesterday;
                                    $billing_data['next_charge_date'] =  \Carbon\Carbon::now()->format('Y-m-d');
                                    Billing::where('id',$client->billing[0]->id)->update($billing_data);
                                }
                            }
                        }
                        catch (\Exception $e) {
                            dd($e);
                        }
                    }
                    if($client->billing[0]->billing_cycle  == 'weekly'){
                        try{
                            $next_invoice_date = $client->billing[0]->next_invoice_date;
                            $next_charge_date = $client->billing[0]->next_charge_date;
                            $now = \Carbon\Carbon::now()->format('Y-m-d');
                            if(!empty($next_invoice_date) && !empty($next_charge_date)){
                                if($next_invoice_date <= $now && $next_charge_date < $now){
                                    $data['client_id'] = !empty($client->id) ? $client->id : " ";
                                    $data['report_type'] = "invoice_pdf_export";
                                    $data['status'] = 'pending';
                                    $data['Invoice_no'] =  RandomUtil::randomString('Invoice_');
                                    $code = random_int(100000, 999999);
                                    $data['file_name'] = date('YmdHis').'-'.$code.".pdf";
                                    $exporthistory = ExportHistory::create($data);
                                    $value = Client::where('id',$client->billing[0]->account_id)->get();

                                    $value['type'] = "Customer";
                                    $value['AccountID'] = $client->billing[0]->account_id;
                                    $value['StartDate'] =  \Carbon\Carbon::parse($next_invoice_date)->subDays(7)->format('Y-m-d');
                                    $value['EndDate'] =  $now;
                                    $value['zerovaluecost'] =  0;
                                    $value['StartTime'] =  "00:00:00";
                                    $value['EndTime'] =  "23:59:59";
                                    if(!empty($exporthistory)){
                                        $exporthistory_id = $exporthistory->id;
                                        $authUser = 0;
                                        $invoice_pdf = new InvoiceGenrateJob($value,$authUser,$exporthistory_id);
                                        dispatch($invoice_pdf);
                                    }

                                    $billing_data['last_invoice_date'] =  $next_invoice_date;
                                    $billing_data['next_invoice_date'] =  \Carbon\Carbon::parse($next_invoice_date)->addDays(7)->format('Y-m-d');
                                    $billing_data['last_charge_date'] =  $next_charge_date;
                                    $billing_data['next_charge_date'] = \Carbon\Carbon::parse( $next_charge_date)->addDays(7)->format('Y-m-d');   
                                    Billing::where('id',$client->billing[0]->id)->update($billing_data); 
                                }
                            }
                        }
                        catch (\Exception $e) {
                            dd($e);
                        }
                    }
                    if($client->billing[0]->billing_cycle  == 'monthly'){
                        try{
                            $next_invoice_date = $client->billing[0]->next_invoice_date;
                            $next_charge_date = $client->billing[0]->next_charge_date;
                            $now = \Carbon\Carbon::now()->format('Y-m-d');
                            if(!empty($next_invoice_date) && !empty($next_charge_date)){
                                if($next_invoice_date <= $now && $next_charge_date < $now){
                                    $data['client_id'] = !empty($client->id) ? $client->id : " ";
                                    $data['report_type'] = "invoice_pdf_export";
                                    $data['status'] = 'pending';
                                    $data['Invoice_no'] =  RandomUtil::randomString('Invoice_');
                                    $code = random_int(100000, 999999);
                                    $data['file_name'] = date('YmdHis').'-'.$code.".pdf";
                                    $exporthistory = ExportHistory::create($data);
                                    $value = Client::where('id',$client->billing[0]->account_id)->get();

                                    $value['type'] = "Customer";
                                    $value['AccountID'] = $client->billing[0]->account_id;
                                    $value['StartDate'] =  \Carbon\Carbon::parse($next_invoice_date)->subMonth()->format('Y-m-d');
                                    $value['EndDate'] =  $now;
                                    $value['zerovaluecost'] =  0;
                                    $value['StartTime'] =  "00:00:00";
                                    $value['EndTime'] =  "23:59:59";

                                    if(!empty($exporthistory)){
                                        $exporthistory_id = $exporthistory->id;
                                        $authUser = 0;
                                        $invoice_pdf = new InvoiceGenrateJob($value,$authUser,$exporthistory_id);
                                        dispatch($invoice_pdf);
                                    } 

                                    $billing_data['last_invoice_date'] =  $next_invoice_date;
                                    $billing_data['next_invoice_date'] =  date('Y-m-d', strtotime('+1 month', strtotime(date('Y-m-01'))));
                                    $date =   date('Y-m-d', strtotime('+1 month', strtotime(date('Y-m-01'))));
                                    $billing_data['last_charge_date'] =  $next_charge_date;
                                    $billing_data['next_charge_date'] = date('Y-m-d', strtotime($date. ' - 1 days')); 
                                    Billing::where('id',$client->billing[0]->id)->update($billing_data);  
                                }
                            }
                        }
                        catch (\Exception $e) {
                            dd($e);
                        }
                    }
                    if($client->billing[0]->billing_cycle  == 'yearly'){
                        try{
                            $next_invoice_date = $client->billing[0]->next_invoice_date;
                            $next_charge_date = $client->billing[0]->next_charge_date;
                            $now = \Carbon\Carbon::now()->format('Y-m-d');
                            if(!empty($next_invoice_date) && !empty($next_charge_date)){
                                if($next_invoice_date <= $now && $next_charge_date < $now){
                                    $data['client_id'] = !empty($client->id) ? $client->id : " ";
                                    $data['report_type'] = "invoice_pdf_export";
                                    $data['status'] = 'pending';
                                    $data['Invoice_no'] =  RandomUtil::randomString('Invoice_');
                                    $code = random_int(100000, 999999);
                                    $data['file_name'] = date('YmdHis').'-'.$code.".pdf";
                                    $exporthistory = ExportHistory::create($data);

                                    $value = Client::where('id',$client->billing[0]->account_id)->get();
                                    $value['type'] = "Customer";
                                    $value['AccountID'] = $client->billing[0]->account_id;
                                    $value['StartDate'] =  \Carbon\Carbon::parse($next_invoice_date)->subYear()->format('Y-m-d');
                                    $value['EndDate'] =  $now;
                                    $value['zerovaluecost'] =  0;
                                    $value['StartTime'] =  "00:00:00";
                                    $value['EndTime'] =  "23:59:59";

                                    if(!empty($exporthistory)){
                                        $exporthistory_id = $exporthistory->id;
                                        $authUser = 0;
                                        $invoice_pdf = new InvoiceGenrateJob($value,$authUser,$exporthistory_id);
                                        dispatch($invoice_pdf);
                                    }  

                                    $billing_data['last_invoice_date'] =  $next_invoice_date;
                                    $billing_data['next_invoice_date'] =  \Carbon\Carbon::parse($next_invoice_date)->addYear()->format('Y-m-d');
                                    $billing_data['last_charge_date'] =  $next_charge_date;
                                    $billing_data['next_charge_date'] = \Carbon\Carbon::parse( $next_charge_date)->addYear()->format('Y-m-d');  
                                    Billing::where('id',$client->billing[0]->id)->update($billing_data);
                                }
                            }
                        }
                        catch (\Exception $e) {
                            dd($e);
                        }
                    }
                    
                    if($client->billing[0]->billing_cycle  == 'quarterly'){
                        try{
                            $next_invoice_date = $client->billing[0]->next_invoice_date;
                            $next_charge_date = $client->billing[0]->next_charge_date;
                            $now = \Carbon\Carbon::now();
                            $currentYear = Carbon::now()->format('Y');
                            $quarter =  $now->quarter;
                          
                            if(!empty($next_invoice_date) && !empty($next_charge_date)){
                                if($next_invoice_date <= $now && $next_charge_date < $now){
                                    if ($quarter == 1)
                                    {
                                        $data['client_id'] = !empty($client->id) ? $client->id : " ";
                                        $data['report_type'] = "invoice_pdf_export";
                                        $data['status'] = 'pending';
                                        $data['Invoice_no'] =  RandomUtil::randomString('Invoice_');
                                        $code = random_int(100000, 999999);
                                        $data['file_name'] = date('YmdHis').'-'.$code.".pdf";
                                        $exporthistory = ExportHistory::create($data);
    
                                        $value = Client::where('id',$client->billing[0]->account_id)->get();
                                        $value['type'] = "Customer";
                                        $value['AccountID'] = $client->billing[0]->account_id;
                                        $value['StartDate'] =  \Carbon\Carbon::parse($next_invoice_date)->subMonth(3)->format('Y-m-d');
                                        $value['EndDate'] =  $now;
                                        $value['zerovaluecost'] =  0;
                                        $value['StartTime'] =  "00:00:00";
                                        $value['EndTime'] =  "23:59:59";
    
                                        if(!empty($exporthistory)){
                                            $exporthistory_id = $exporthistory->id;
                                            $authUser = 0;
                                            $invoice_pdf = new InvoiceGenrateJob($value,$authUser,$exporthistory_id);
                                            dispatch($invoice_pdf);
                                        }  
                                        $billing_data['last_invoice_date'] =  $next_invoice_date;
                                        $billing_data['next_invoice_date'] =  Carbon::createMidnightDate($currentYear,4,1)->format('Y-m-d');
                                        $billing_data['last_charge_date'] =  $next_charge_date;
                                        $billing_data['next_charge_date'] = Carbon::createMidnightDate($currentYear,3,31)->format('Y-m-d');
        
                                        Billing::where('id',$client->billing[0]->id)->update($billing_data);
        
                                    }
                                    if ($quarter == 2)
                                    {
                                        $data['client_id'] = !empty($client->id) ? $client->id : " ";
                                        $data['report_type'] = "invoice_pdf_export";
                                        $data['status'] = 'pending';
                                        $data['Invoice_no'] =  RandomUtil::randomString('Invoice_');
                                        $code = random_int(100000, 999999);
                                        $data['file_name'] = date('YmdHis').'-'.$code.".pdf";
                                        $exporthistory = ExportHistory::create($data);
    
                                        $value = Client::where('id',$client->billing[0]->account_id)->get();
                                        $value['type'] = "Customer";
                                        $value['AccountID'] = $client->billing[0]->account_id;
                                        $value['StartDate'] =  \Carbon\Carbon::parse($next_invoice_date)->subMonth(3)->format('Y-m-d');
                                        $value['EndDate'] =  $now;
                                        $value['zerovaluecost'] =  0;
                                        $value['StartTime'] =  "00:00:00";
                                        $value['EndTime'] =  "23:59:59";
    
                                        if(!empty($exporthistory)){
                                            $exporthistory_id = $exporthistory->id;
                                            $authUser = 0;
                                            $invoice_pdf = new InvoiceGenrateJob($value,$authUser,$exporthistory_id);
                                            dispatch($invoice_pdf);
                                        }  
                                        $billing_data['last_invoice_date'] =  $next_invoice_date;
                                        $billing_data['next_invoice_date'] =  Carbon::createMidnightDate($currentYear,7,1)->format('Y-m-d');
                                        $billing_data['last_charge_date'] =  $next_charge_date;
                                        $billing_data['next_charge_date'] = Carbon::createMidnightDate($currentYear,6,30)->format('Y-m-d');
        
                                        Billing::where('id',$client->billing[0]->id)->update($billing_data);
                                    }
                                    if ($quarter == 3)
                                    {
                                        $data['client_id'] = !empty($client->id) ? $client->id : " ";
                                        $data['report_type'] = "invoice_pdf_export";
                                        $data['status'] = 'pending';
                                        $data['Invoice_no'] =  RandomUtil::randomString('Invoice_');
                                        $code = random_int(100000, 999999);
                                        $data['file_name'] = date('YmdHis').'-'.$code.".pdf";
                                        $exporthistory = ExportHistory::create($data);
    
                                        $value = Client::where('id',$client->billing[0]->account_id)->get();
                                        $value['type'] = "Customer";
                                        $value['AccountID'] = $client->billing[0]->account_id;
                                        $value['StartDate'] =  \Carbon\Carbon::parse($next_invoice_date)->subMonth(3)->format('Y-m-d');
                                        $value['EndDate'] =  $now;
                                        $value['zerovaluecost'] =  0;
                                        $value['StartTime'] =  "00:00:00";
                                        $value['EndTime'] =  "23:59:59";
    
                                        if(!empty($exporthistory)){
                                            $exporthistory_id = $exporthistory->id;
                                            $authUser = 0;
                                            $invoice_pdf = new InvoiceGenrateJob($value,$authUser,$exporthistory_id);
                                            dispatch($invoice_pdf);
                                        }  

                                        $billing_data['last_invoice_date'] =  $next_invoice_date;
                                        $billing_data['next_invoice_date'] =  Carbon::createMidnightDate($currentYear, 10,1)->format('Y-m-d');
                                        $billing_data['last_charge_date'] =  $next_charge_date;
                                        $billing_data['next_charge_date'] = Carbon::createMidnightDate($currentYear,9,30)->format('Y-m-d');
        
                                        Billing::where('id',$client->billing[0]->id)->update($billing_data);
                                    }
                                    if ($quarter == 4)
                                    {
                                        $data['client_id'] = !empty($client->id) ? $client->id : " ";
                                        $data['report_type'] = "invoice_pdf_export";
                                        $data['status'] = 'pending';
                                        $data['Invoice_no'] =  RandomUtil::randomString('Invoice_');
                                        $code = random_int(100000, 999999);
                                        $data['file_name'] = date('YmdHis').'-'.$code.".pdf";
                                        $exporthistory = ExportHistory::create($data);
    
                                        $value = Client::where('id',$client->billing[0]->account_id)->get();
                                        $value['type'] = "Customer";
                                        $value['AccountID'] = $client->billing[0]->account_id;
                                        $value['StartDate'] =  \Carbon\Carbon::parse($next_invoice_date)->subMonth(3)->format('Y-m-d');
                                        $value['EndDate'] =  $now;
                                        $value['zerovaluecost'] =  0;
                                        $value['StartTime'] =  "00:00:00";
                                        $value['EndTime'] =  "23:59:59";
    
                                        if(!empty($exporthistory)){
                                            $exporthistory_id = $exporthistory->id;
                                            $authUser = 0;
                                            $invoice_pdf = new InvoiceGenrateJob($value,$authUser,$exporthistory_id);
                                            dispatch($invoice_pdf);
                                        }  
                                        $Year = Carbon::now();
                                        $nextYear = $Year->addYear();
                                        $firstDateOfNextYear = $nextYear->startOfYear();
                                        $formattedDate = $firstDateOfNextYear->format('Y-m-d');
                                      
                                        $billing_data['last_invoice_date'] =  $next_invoice_date;
                                        $billing_data['next_invoice_date'] =  $formattedDate;
                                        $billing_data['last_charge_date'] =  $next_charge_date;
                                        $billing_data['next_charge_date'] = Carbon::createMidnightDate($currentYear,12,31)->format('Y-m-d');
                                        Billing::where('id',$client->billing[0]->id)->update($billing_data);
                                    }
                                }
                            }
                        }
                        catch (\Exception $e) {
                            dd($e);
                        }
                    }
                }
            }
        }
    }
}
