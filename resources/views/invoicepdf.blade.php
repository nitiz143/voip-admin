<!DOCTYPE html>
<html>
<head>
    <title>Hi</title>
</head>
<body>
   
    <div class="container page-break">
        <div class="row">
            <div class="col-md-3">
                <img src="{{asset('assets/dist/img/default-150x150.png')}}" alt="logo" >
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="panel-body" style="font-size: 16px;font-weight: bold;padding:0">
                    <div class="row">
                        <div class="col-md-12"><h1>{{__('client_information')}}</h1></div>
                    </div>
                    <div class="row">
                        <div class="col-md-9 text-right">{{__('name')}}:</div>
                    </div>
                    <div class="row">
                        <div class="col-md-9 text-right">{{__('email')}}:</div>
                    </div>
                    <div class="row">
                        <div class="col-md-9 text-right">{{__('phone')}}:</div>
                    </div>
                    <div class="row">
                        <div class="col-md-9 text-right">{{__('address')}}:</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-right">
                <div class="panel-body" style="font-size: 16px;font-weight: bold;padding:0">
                    <div class="row">
                        <div class="col-md-9 text-right">{{__('invoice no')}}:</div>
                    </div>
                    <div class="row">
                        <div class="col-md-9 text-right">{{__('due')}}:</div>
                    </div>
                    <div class="row">
                        <div class="col-md-9 text-right">{{__('next date')}}:</div>
                    </div>
                    <div class="row">
                        <div class="col-md-9 text-right">{{__('end date')}}:</div>
                    </div>
                </div>
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
                        <td>${{$cost}}</td>
                        <td>${{00.00}}</td>
                        <td>${{00.00}}</td>
                        <td>{{$cost}}</td>
                    </tr>
                </tbody>
            </table>
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
