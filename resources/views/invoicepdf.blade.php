<!DOCTYPE html>
<html>
    <head>
        <title>Invoice</title>
    </head>
    <body>
    
        <div class="container">
            <div class="col-12 text-right">
                <h3>Invoice From</h3>
                <p>Pai Telecom LLC</p>
            </div>
            <div class="row">
                <div class="col-6">
                    <img src="{{ public_path('assets/dist/img/download.png')}}" style="width: 260px; height: 100px; margin-bottom:30px;" alt="logo" >
                </div>
            </div>
            <div class="col-md-12 form-group mt-3">
                <div class="from_address" style="float:left;width:50%;">
                    <h2 class="invoice_title">Invoice To:</h2>
                    <h5>{{__('Name')}}: &nbsp;{{!empty($account->account_name) ? $account->account_name : ''}}</h5>
                    <h5>{{__('Email')}}:  &nbsp;{{!empty($account->email) ? $account->email : ''}}</h5>
                    <h5>{{__('Phone')}}: &nbsp;{{!empty($account->phone) ? $account->phone : ''}}</h5>
                    <h5>{{__('Address')}}: &nbsp;{{!empty($account->city) ? $account->city : ''}},{{!empty($account->postzip) ? $account->postzip : ''}},{{!empty($account->country) ? $account->country : ''}}</h5>
                </div>
                <div class="to_address text-right">
                    <h2 class="Invoice_title">Invoice Detail</h2>
                    <h4>{{__('Invoice no')}}: &nbsp;{{!empty($data->Invoice_no) ? $data->Invoice_no : ''}}</h4>
                    <h5>{{__('Invoice Date')}}:&nbsp;{{!empty($account->billing[0]->next_invoice_date) ? $account->billing[0]->next_invoice_date : ''}}</h5>
                    <h5>{{__('Due')}}:&nbsp;{{!empty($account->billing[0]->next_invoice_date) ? \Carbon\Carbon::parse($account->billing[0]->next_invoice_date)->addDays(5)->format('Y-m-d') : ''}}</h5>
                    @if($account->billing[0]->billing_class == 'daily')
                        <h5>{{__('Invoice Period')}}:{{!empty($account->billing[0]->next_invoice_date) ? \Carbon\Carbon::parse($account->billing[0]->next_invoice_date)->subday()->format('Y-m-d') : ''}} to {{!empty($account->billing[0]->next_invoice_date) ? \Carbon\Carbon::parse($account->billing[0]->next_invoice_date)->format('Y-m-d') : ''}}</h5>
                    @elseif ($account->billing[0]->billing_class = 'monthly')
                        <h5>{{__('Invoice Period')}}:{{!empty($account->billing[0]->next_invoice_date) ? \Carbon\Carbon::parse($account->billing[0]->next_invoice_date)->subMonth()->format('Y-m-d') : ''}} to {{!empty($account->billing[0]->next_invoice_date) ? \Carbon\Carbon::parse($account->billing[0]->next_invoice_date)->subday()->format('Y-m-d') : ''}}</h5>
                    @elseif ($account->billing[0]->billing_class = 'weekly')
                        <h5>{{__('Invoice Period')}}:{{!empty($account->billing[0]->next_invoice_date) ? \Carbon\Carbon::parse($account->billing[0]->next_invoice_date)->subday(7)->format('Y-m-d') : ''}} to {{!empty($account->billing[0]->next_invoice_date) ? \Carbon\Carbon::parse($account->billing[0]->next_invoice_date)->subday()->format('Y-m-d') : ''}}</h5>
                    @elseif ($account->billing[0]->billing_class = 'yearly')
                        <h5>{{__('Invoice Period')}}:{{!empty($account->billing[0]->next_invoice_date) ? \Carbon\Carbon::parse($account->billing[0]->next_invoice_date)->subYear()->format('Y-m-d') : ''}} to {{!empty($account->billing[0]->next_invoice_date) ? \Carbon\Carbon::parse($account->billing[0]->next_invoice_date)->subday()->format('Y-m-d') : ''}}</h5>
                    @else
                        <h5>{{__('Invoice Period')}}: </h5>
                    @endif
                
                
                </div>
            </div>
            
                    <br><br>
            <div style="clear: both"></div>
            <div class="col-md-12">
                <table class="table w-100">
                    <tr style="margin-bottom:30px;background: #2e3e4e;color: #fff;" class="item_table_header">
                        <th style="width:25%">{{ __('Usage') }}</th>
                        <th style="width:25%">{{ __('Recurring') }}</th>
                        <th style="width:25%">{{ __('Additional') }}</th>
                        <th style="width:25%" class="text-right">{{ __('TOTAL') }}</th>
                    </tr>
                    <tbody id="items">
                        <tr class="items">
                            <td>${{$total_cost}}</td>
                            <td>${{00.00}}</td>
                            <td>${{00.00}}</td>
                            <td  class="text-right">${{$total_cost}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-12">
                <div class="col-md-6">

                </div>
                <div class="col-md-6">
                    <table class="table">
                        <tr>
                            <th style="width:75%" class="text-right">{{ __('Sub Total') }}</th>
                            <td class="text-right">
                                <span id="subTotal">${{ $total_cost }}</span>
                            </td>
                        </tr>
                        <tr class="amount_due">
                            <th  style="width:75%"  class="text-right">{{ __('Grand Total') }}:</th>
                            <td class="text-right">
                                <hr class="separator">
                                <span id="grandTotal">${{ $total_cost }}</span>
                                <hr class="separator">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-5" >
                    <h4>Bank Details</h4>
                    <h4>US to US Transfer only</h4>
                    <h4>Beneficiary</h4>
                    <p>Beneficiary Name: {{!empty($account->account_name) ? $account->account_name : ''}}<br>
                    Account Number: {{!empty($account->account_number) ? $account->account_number : ''}}<br>
                    Type of Account: Checking<br>
                    Beneficiary Address: {{!empty($account->address_line1) ? $account->address_line1 : ''}}, &nbsp;{{!empty($account->city) ? $account->city : ''}}, &nbsp;{{!empty($account->address_line2) ? $account->address_line2 : ''}},{{!empty($account->postzip) ? $account->postzip : ''}}, &nbsp;{{!empty($account->address_line3) ? $account->address_line3 : ''}}, &nbsp;{{!empty($account->country) ? $account->country : ''}}</p>
                    <h4>Receiving Bank Details</h4>
                    <p>ABA Routing Number: 084106768<br>
                    Bank Name: Evolve Bank & Trust Mercury uses Evolve Bank & Trust as a banking partner.<br>
                    Bank Address: 6070 Poplar Ave, Suite 200 Memphis, TN 38119</p>
                </div>
            </div>
        </div>
        <div class="page-break"></div>
        <div class="container">
            <br><br>
            <div style="clear: both"></div>
            <div style="margin-bottom:10px;background: #929597;color: #fff;" class="item_table_header d-flex justify-content-between">
                <div  style="float:left;width:50%; padding-left:10px;"><h3>Usage</h3></div>
                <div class="text-right" style="padding-right:10px;"><h4>${{ $total_cost }}</h4></div>
            </div>
            <div class="col-md-12">
                <table class="table w-100">
                    <tr style="margin-bottom:10px;background: #2e3e4e;color: #fff;" class="item_table_header">
                    <th style="width:20%">{{ __('Title') }}</th>
                    <th style="width:20%">{{ __('Description') }}</th>
                    <th style="width:20%">{{ __('Price') }}</th>
                    <th style="width:20%">{{ __('Quantity') }}</th>
                    <th style="width:20%">{{ __('TOTAL') }}</th>
                    </tr>
                    <tbody id="items">
                        <tr class="items">
                            <td>Usage</td>
                            @if($account->billing[0]->billing_class == 'daily')
                                <td>From &nbsp;{{!empty($account->billing[0]->next_invoice_date) ? \Carbon\Carbon::parse($account->billing[0]->next_invoice_date)->subday()->format('Y-m-d') : ''}} to {{!empty($account->billing[0]->next_invoice_date) ? \Carbon\Carbon::parse($account->billing[0]->next_invoice_date)->format('Y-m-d') : ''}}</td>
                            @elseif ($account->billing[0]->billing_class = 'monthly')
                                <td>From &nbsp;{{!empty($account->billing[0]->next_invoice_date) ? \Carbon\Carbon::parse($account->billing[0]->next_invoice_date)->subMonth()->format('Y-m-d') : ''}} to {{!empty($account->billing[0]->next_invoice_date) ? \Carbon\Carbon::parse($account->billing[0]->next_invoice_date)->subday()->format('Y-m-d') : ''}}</td>
                            @elseif ($account->billing[0]->billing_class = 'weekly')
                                <td>From &nbsp;{{!empty($account->billing[0]->next_invoice_date) ? \Carbon\Carbon::parse($account->billing[0]->next_invoice_date)->subday(7)->format('Y-m-d') : ''}} to {{!empty($account->billing[0]->next_invoice_date) ? \Carbon\Carbon::parse($account->billing[0]->next_invoice_date)->subday()->format('Y-m-d') : ''}}</td>
                            @elseif ($account->billing[0]->billing_class = 'yearly')
                                <td>From &nbsp;{{!empty($account->billing[0]->next_invoice_date) ? \Carbon\Carbon::parse($account->billing[0]->next_invoice_date)->subYear()->format('Y-m-d') : ''}} to {{!empty($account->billing[0]->next_invoice_date) ? \Carbon\Carbon::parse($account->billing[0]->next_invoice_date)->subday()->format('Y-m-d') : ''}}</td>
                            @else
                                <td> </td>
                            @endif
                            <td>${{ $total_cost }}</td>
                            <td>1</td>
                            <td>${{ $total_cost }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-md-12 mt-5" >
                    <h4>Bank Details</h4>
                    <h4>US to US Transfer only</h4>
                    <h4>Beneficiary</h4>
                    <p>Beneficiary Name: {{!empty($account->account_name) ? $account->account_name : ''}}<br>
                    Account Number: {{!empty($account->account_number) ? $account->account_number : ''}}<br>
                    Type of Account: Checking<br>
                    Beneficiary Address: {{!empty($account->address_line1) ? $account->address_line1 : ''}}, &nbsp;{{!empty($account->city) ? $account->city : ''}}, &nbsp;{{!empty($account->address_line2) ? $account->address_line2 : ''}}, &nbsp;{{!empty($account->postzip) ? $account->postzip : ''}}, &nbsp;{{!empty($account->address_line3) ? $account->address_line3 : ''}}, &nbsp;{{!empty($account->country) ? $account->country : ''}}</p>
                    <h4>Receiving Bank Details</h4>
                    <p>ABA Routing Number: 084106768<br>
                    Bank Name: Evolve Bank & Trust Mercury uses Evolve Bank & Trust as a banking partner.<br>
                    Bank Address: 6070 Poplar Ave, Suite 200 Memphis, TN 38119</p>
                </div>
            </div>
        </div>
        
            <!-- do something here | divs -->
        
        <div class="page-break"></div>
        <div class="container">
            <br><br>
            <div style="clear: both"></div>
            <div style="margin-bottom:10px; background: #929597;color: #fff;" class="item_table_header">
                <div  style="width:50%; padding-left:10px;"><h3> Usage</h3></div>
            </div>
            <div class="col-md-12">
                <table class="table w-100">
                    <tr style="margin-bottom:30px;background: #2e3e4e;color: #fff;" class="item_table_header">
                        <th style="width:14%">{{ __('Trunk') }}</th>
                        <th style="width:14%">{{ __('Prefix') }}</th>
                        <th style="width:14%">{{ __('Country') }}</th>
                        <th style="width:14%">{{ __('Description') }}</th>
                        <th style="width:14%">{{ __('No of calls') }}</th>
                        <th style="width:14%">{{ __('Duration (mm:ss)') }}</th>
                        <th style="width:14%">{{ __('Billed Duration (mm:ss)') }}</th>
                        <th style="width:14%">{{ __('Avg Rate/Min') }}</th>
                        <th style="width:14%">{{ __('Cost') }}</th>
                    </tr>
                    @foreach($invoices as $invoice)
                        <tbody id="items">
                            <tr class="items">
                                <td></td>
                                <td>{{ $invoice->callerareacode }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                @php
                                    // $date = new \DateTime();
                                    // $value = $invoice->starttime;
                                    // $startTime =  $date->setTimestamp($value/1000);
                                    // $date1 = new \DateTime();
                                    // $value1 = $invoice->stoptime;
                                    // $stopTime =  $date1->setTimestamp($value1/1000);

                                    // $now = \Carbon\Carbon::parse($startTime);
                                    // $emitted = \Carbon\Carbon::parse($stopTime);
                                    $totalDuration =   $invoice->feetime;
                                
                                    $totalDuration = sprintf( "%02.2d:%02.2d", floor($totalDuration / 60 ), $totalDuration % 60)
                                @endphp
                                <td>{{ $totalDuration }}</td>
                                <td>{{ $totalDuration }}</td>
                                <td></td>
                                @if($user == 'Vendor')
                                    <td>${{ $invoice->agentfee }}</td>
                                @endif
                                @if($user == 'Customer')
                                    <td>${{ $invoice->fee }}</td>
                                @endif
                            </tr>
                        </tbody>
                    @endforeach
                    <tfoot id="items">
                        <tr class="items">
                            <th colspan="4"></th>
                            <th>Calls</th>
                            <th>Duration</th>
                            <th>Billed Duration</th>
                            <th></th>
                            <th>Charge</th>
                        </tr>
                        <tr class="items">
                            <td colspan="4"></td>
                            <td>{{$invoices->count();}}</td>
                            @php
                                $time= sprintf( "%02.2d:%02.2d", floor( array_sum($count_duration) / 60 ), array_sum($count_duration) % 60 )
                            @endphp
                            <td >{{!empty($time) ? $time :"" }}</td>
                            <td >{{!empty($time) ? $time :"" }}</td>
                            <td ></td>
                            <td >${{$total_cost}}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="row">
                <div class="col-md-12 mt-5" >
                    <h4>Bank Details</h4>
                    <h4>US to US Transfer only</h4>
                    <h4>Beneficiary</h4>
                    <p>Beneficiary Name: {{!empty($account->account_name) ? $account->account_name : ''}}<br>
                    Account Number: {{!empty($account->account_number) ? $account->account_number : ''}}<br>
                    Type of Account: Checking<br>
                    Beneficiary Address: {{!empty($account->address_line1) ? $account->address_line1 : ''}}, &nbsp;{{!empty($account->city) ? $account->city : ''}}, &nbsp;{{!empty($account->address_line2) ? $account->address_line2 : ''}},{{!empty($account->postzip) ? $account->postzip : ''}}, &nbsp;{{!empty($account->address_line3) ? $account->address_line3 : ''}}, &nbsp;{{!empty($account->country) ? $account->country : ''}}</p>
                    <h4>Receiving Bank Details</h4>
                    <p>ABA Routing Number: 084106768<br>
                    Bank Name: Evolve Bank & Trust Mercury uses Evolve Bank & Trust as a banking partner.<br>
                    Bank Address: 6070 Poplar Ave, Suite 200 Memphis, TN 38119</p>
                </div>
            </div>
        </div>
    </body>
</html>
<style>
    body {font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;overflow-x: hidden;overflow-y: auto;font-size: 13px;}
    .amount_due {font-size: 16px;font-weight: 500;}
    .invoice_title{color: #2e3e4e;font-weight: bold;}
    .text-right{text-align: right;}
    .text-center{text-align: center;}
    .col-sm-12{width: 100%;}
    .col-sm-6{width: 50%;float: left;}
    table {border-spacing: 0;border-collapse: collapse;}
    .table {width: 100%;max-width: 100%;margin-bottom: 20px;}
    .item_table_header>th{padding: 8px;line-height: 1.42857143;vertical-align: top;}
    .table>tr>td, .table>tr>th{padding: 8px;line-height: 1.42857143;vertical-align: top;}
    hr.separator{border-color:  #2e3e4e;margin-top: 10px;margin-bottom: 10px;}
    tbody#items>tr>td{border: 3px solid #fff !important;vertical-align: middle;padding: 8px;}
    tfoot#items>tr>td{border: 3px solid #fff !important;vertical-align: middle;padding: 8px;}
    #items{background-color: #f1f1f1;}
    .form-group {margin-bottom: 1rem;}
    .from_address{width: 330px;height:200px;margin-bottom:1rem;float: left;}
    .to_address{width: 330px;float: right;height:200px}
    .capitalize{text-transform: uppercase}
    .invoice_status_cancelled {font-size : 20px;text-align : center;color: #cc0000;border: 1px solid #cc0000;}
    .invoice_status_paid {font-size : 25px;text-align : center;color: #82b440;border: 1px solid #82b440;}
    .btn-success {background-color: #4CAF50;border: none;color: white;padding: 12px 22px;text-align: center;text-decoration: none;display: inline-block;font-size: 16px;}
    .page-break {
        page-break-after: always;
    }
</style>
