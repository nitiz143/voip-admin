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
                    <h5>{{__('Invoice Date')}}:&nbsp;{{!empty($account->billing[0]->next_invoice_date) ? $account->billing[0]->next_invoice_date : date('Y-m-d')}}</h5>
                    <h5>{{__('Due')}}:&nbsp;{{!empty($account->billing[0]->next_invoice_date) ? \Carbon\Carbon::parse($account->billing[0]->next_invoice_date)->addDays(5)->format('Y-m-d') : ''}}</h5>
                    <h5>{{__('Invoice Period')}}: {{$StartDate}} - {{$EndDate}}</h5>
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
                            <td class="text-right">${{$total_cost}}</td>
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
                            <td>From &nbsp;{{$StartDate}} to {{$EndDate}}</td>
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
                   {{-- <th style="width:14%">{{ __('Trunk') }}</th> 
                        <th style="width:14%">{{ __('Prefix') }}</th> --}}
                        <th style="width:14%">{{ __('Country') }}</th> 
                        <th style="width:14%">{{ __('Total calls') }}</th>
                        <th style="width:14%">{{ __('Completed') }}</th>
                        <th style="width:14%">{{ __('ASR(%)') }}</th>
                        <th style="width:14%">{{ __('ACD(Sec)') }}</th>
                        <th style="width:14%">{{ __('Duration') }}</th>
                        <th style="width:14%">{{ __('Billed Duration') }}</th>
                        <th style="width:14%">{{ __('Avg Rate/Min') }}</th>
                        <th style="width:14%">{{ __('Total Cost') }}</th>
                    </tr>
                    @if(!empty($invoices))
                        @foreach($invoices as $key => $values)
                            @php
                               $country = App\Models\Country::where('phonecode',$key)->first();
                            @endphp
                            <tbody id="items">
                                <tr class="items">
                                    <td>{{!empty($country->name) ? $country->name : ""}}</td>
                                    <td>{{$values->count()}}</td>

                                    @php
                                        $Duration_count = array();
                                        $completed_count = array();
                                        foreach ($values as $invoice){
                                            $Duration_count[] = $invoice->feetime;
                                            if($invoice->feetime != 0) {
                                                $completed_count[] = $invoice->feetime;
                                            }
                                            $timepersec = $values->sum('fee')/$values->sum('feetime');
                                            $persec =  round($timepersec, 7);
                                            $fee= $persec*60;
                                        }
                                        $Duration= sprintf( "%02.2d:%02.2d", floor( array_sum($Duration_count) / 60 ), array_sum($Duration_count) % 60 )
                                    @endphp

                                    <td>{{!empty($completed_count) ? count($completed_count) : ""}}</td>
                                    <td>{{\Str::limit((count($completed_count)/$values->count() * 100),5)}}%</td>
                                    <td>{{\Str::limit( array_sum($completed_count) /  count($completed_count), 5) }} </td>
                                    <td>{{$Duration}}</td>
                                    <td>{{$Duration}}</td>
                                    <td>{{!empty($fee) ? '$'.$fee : "$ 0.00"}}</td>

                                    @if($user == 'Vendor')
                                        <td>${{$values->sum('agentfee')}}</td>
                                    @endif
                                    @if($user == 'Customer')
                                        <td>${{ $values->sum('fee') }}</td>
                                    @endif
                                </tr>
                            </tbody>
                        @endforeach
                    @endif
                    <tfoot id="items">
                        <tr class="items">
                            <th colspan="6"></th>
                            <th>Calls</th>
                            <th>Duration</th>
                            <th>Charge</th>
                        </tr>
                        <tr class="items">
                            <td colspan="6"></td>
                            <td>{{$calls}}</td>
                            @php
                                $time= sprintf( "%02.2d:%02.2d", floor( array_sum($count_duration) / 60 ), array_sum($count_duration) % 60 )
                            @endphp
                            <td >{{!empty($time) ? $time :"" }}</td>
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
