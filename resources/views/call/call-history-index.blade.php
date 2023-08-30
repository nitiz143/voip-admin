@extends('layouts.dashboard')

@section('content')
<style>
   .for-scroll{
        overflow: auto;
        overscroll-behavior-x: auto;
    }
</style>
<div class="content-wrapper mt-3">
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">

                </div>
                <div class="col-sm-6">
                 
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title ">Call History</h4>
                            <div class="float-right">
                                <a href="{{ route('cdr.create') }}" class="btn btn-primary  w-10" id="createzoneModal">Import</a>
                            </div>
                        </div> 
                        <div class="card-body">
                            <div class="panel panel-primary">
                                <div class=" tab-menu-heading">
                                    <div class="tabs-menu1" id="gfg">
                                        <!-- Tabs -->
                                        <ul class="nav nav-tabs gap-1">
                                            <li class="active">
                                                <a data-toggle="tab" data-id="1" id="customer" href="#home" class="nav-link active">Customer CDR</a>
                                            </li>
                                            <li>
                                                <a  data-toggle="tab" data-id="2" id="vendor" href="#home" class="nav-link ">Vendor CDR</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="tab-content">
                                    <div id="home" class="tab-pane fade in active show">
                                        <div class="container-fluid  ml-2 mt-3">
                                            <form novalidate class="form-horizontal form-groups-bordered validate d-flex gap-3" method="post" id="cdr_filter">
                                                <input type="hidden" name="type" class="form-control"  value="Customer"  />
                                                <div class="form-group customer_Account">
                                                    <label class="control-label" for="field-1">Customer Account List</label>
                                                    <select class="form-control" id="bulk_AccountID" allowClear="true" name="AccountID" required>
                                                        @if(!empty($Accounts))
                                                            <option value="">Select</option>
                                                            @foreach ( $Accounts as $Account )
                                                                <option value="{{$Account->id}}">{{$Account->firstname}}{{$Account->lastname}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="form-group vendor_Account">
                                                    <label class="control-label" for="field-1">Vendor Account List</label>
                                                    <select class="form-control" id="bulk_VAccountID" allowClear="true" name="VAccountID" required>
                                                        @if(!empty($VAccounts))
                                                            <option value="">Select</option>
                                                            @foreach ( $VAccounts as $VAccount )
                                                                <option value="{{$VAccount->id}}">{{$VAccount->firstname}}{{$VAccount->lastname}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="form-group" style="max-width: 300px;">
                                                    <label class="control-label small_label" for="field-1">Start Date</label>
                                                    <div class="d-flex gap-1">
                                                        <input type="text" name="StartDate" class="form-control datepicker w-35" id="datepicker"  data-date-format="yyyy-mm-dd HH:mm:ss" value="2023-03-03" data-enddate="2023-03-03"  />
                                                    </div>
                                                </div>
                                                <div class="form-group" style="max-width: 300px;">
                                                    <label class="col-md-4 control-label small_label" for="field-1" style="padding-left: 0px;">End Date</label>
                                                    <div class="d-flex gap-1">
                                                        <input type="text" name="EndDate" 
                                                        id="datepicker1" class="form-control datepicker"  data-date-format="yyyy-mm-dd HH:mm:ss" value="2023-03-03" data-enddate="2023-03-03" />
                                                    </div>
                                                </div>
                                               
                                                
                                                {{-- <div class="form-group">
                                                    <label class="control-label" for="field-1">Report</label>
                                                    <select class="form-control" id="report" allowClear="true" name="report">
                                                        <option value="">Select</option>
                                                        <option value="Customer-Summary">Customer Summary</option>
                                                        <option value="Customer-Hourly">Customer Hourly</option>
                                                        <option value="Customer/Vendor-Report">Customer/Vendor Report</option>
                                                        <option value="Account-Manage">Account Manage</option>
                                                        <option value="Margin-Report">Margin Report</option>
                                                        <option value="Negative-Report">Negative Report</option>
                                                    </select>
                                                </div> --}}
                                                <div class="form-group">
                                                    <input type="hidden" id="ActiveTab" name="ActiveTab" value="1">
                                                    <button type="submit" class="btn btn-primary btn-md btn-icon " style="
                                                    margin-top: 30px;">
                                                        <i class="entypo-search"></i>
                                                        Search
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="panel-body tabs-menu-body">
                                            <div class="row for-scroll">
                                                <div class="col-sm-12">
                                                    <div class="panel-body mt-3">
                                                        <table class="table table-1 data-table   table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>ID</th>
                                                                    <th>Account Name</th>
                                                                    <th>Connect Time</th>
                                                                    <th>Disconnect Time</th>
                                                                    <th>Billed Duration (sec)</th>
                                                                    <th>Cost</th>
                                                                    <th>Avg. Rate/Min</th>
                                                                    <th>CLI</th>
                                                                    <th>CLD</th>
                                                                    <th>Country-code</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                            
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                                <div class="modal" id="ajaxModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Call Detail</h5>
                                                <button type="button" id="btnModeClose" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-content" id="callForm">
                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
@section('page_js')
<script>
    $("#Filter").on("click", function (e) {
        e.preventDefault();
        $('#FilterModel').modal('show');
    });

    $("#filterClose").on("click", function (e) {
        e.preventDefault();
        $("#FilterModel").modal("hide");
    });

    $("#btnModeClose").on("click", function (e) {
        e.preventDefault();
        $("#ajaxModel").modal("hide");
    });
  
    $(document).ready(function() {
        $(".customer_Account").show();
        $(".vendor_Account").hide();
        var $searchFilter = {};
        var TotalCall = 0;
        var TotalDuration = 0;
        var TotalCost = 0;
        $("#cdr_filter").submit(function(e) {
            e.preventDefault();
            
            $searchFilter.Account = $("#cdr_filter select[name='AccountID']").val();
            $searchFilter.VAccount = $("#cdr_filter select[name='VAccountID']").val();
            $searchFilter.Gateway = $("#cdr_filter select[name='GatewayID']").val();
            $searchFilter.zerovaluecost = $("#cdr_filter select[name='zerovaluecost']").val();
            $searchFilter.Cli = $("#cdr_filter input[name='CLI']").val();
            $searchFilter.Cld = $("#cdr_filter input[name='CLD']").val();
            $searchFilter.Prefix = $("#cdr_filter input[name='area_prefix']").val();
            $searchFilter.StartDate = $("#cdr_filter input[name='StartDate']").val();
            $searchFilter.EndDate = $("#cdr_filter input[name='EndDate']").val();
          
            if(typeof $searchFilter.StartDate  == 'undefined' || $searchFilter.StartDate.trim() == ''){
                $.notify("Please Select a Start date", "Error");
                return false;
            }
            if(typeof $searchFilter.EndDate  == 'undefined' || $searchFilter.EndDate.trim() == ''){
                $.notify("Please Select a End date", "Error");
                return false;
            }
            if($searchFilter.ActiveTab == "1"){
                if(typeof $searchFilter.Account  == 'undefined' || $searchFilter.Account.trim() == ''){
                    $.notify("Please Select a Account", "Error");
                    return false;
                }
            }
            if($searchFilter.ActiveTab == "2"){
                if(typeof $searchFilter.VAccount  == 'undefined' || $searchFilter.VAccount.trim() == ''){
                    $.notify("Please Select a Account", "Error");
                    return false;
                }
            }
           
            $searchFilter.ActiveTab = $("#cdr_filter input[name='ActiveTab']").val();

            if($searchFilter.ActiveTab == "1"){
                var table1 = $('.table-1').DataTable({
                        "bDestroy": true, // Destroy when resubmit form
                        "bProcessing": true,
                        "bServerSide": true,
                        "ajax": {
                            "url" : "{{ route('cdr_show.index') }}",
                            "data" : function ( d ){
                                d.ActiveTab= $searchFilter.ActiveTab
                                d.Trunk= $searchFilter.Trunk,
                                d.Account= $searchFilter.Account,
                                d.Gateway = $searchFilter.Country,
                                d.zerovaluecost = $searchFilter.zerovaluecost,
                                d.Cli = $searchFilter.Cli,
                                d.Cld = $searchFilter.Cld,
                                d.Prefix = $searchFilter.Prefix,
                                d.Tag = $searchFilter.Tag,
                                d.StartDate = $searchFilter.StartDate,
                                d.EndDate = $searchFilter.EndDate
                            },
                        },
                        "aaSorting": [[0, "asc"]],
                        "language": {                
                                        "infoFiltered": ""
                                    },
                        "aoColumns":
                        [
                            {data:'id',name:'id'},
                            {data:'account',name:'account'},
                            {data:'Connect_time',name:'Connect_time'},
                            {data:'Disconnect_time',name:'Disconnect_time'},
                            {data:'billing_duration',name:'billing_duration'},
                            {data:'Cost',name:'Cost'},
                            {data:'Avrage_cost',name:'Avrage_cost'},
                            {data:'callere164',name:'callere164'},
                            {data:'calleee164',name:'calleee164'},
                            {data:'Prefix',name:'Prefix'},
                            {data:'action',name:'action', orderable: false, searchable: false},
                        ],
                        "fnFooterCallback": function ( row, data, start, end, display ) {
                            var api = this.api(), data;

                            /* converting to interger to find total */
                            var intVal = function ( i ) {
                                return typeof i === 'string' ?
                                    i.replace(/[\$,]/g, '')*1 :
                                    typeof i === 'number' ?
                                        i : 0;
                            };

                            var calls = api.column( 3 ).data().reduce( function (a, b) {
                                return table1.data().length;
                            }, 0 );

                            var minitus = api.column( 4 ).data().reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                            minitus  = Math.floor(minitus  / 60)+":" + minitus % 60


                            var amount = api.column( 5 ).data().reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );
                        if (end > 0) {
                            $(row).html('');
                            for (var i = 0; i < $('.table-1 thead th').length; i++) {
                                var a = document.createElement('td');
                                $(a).html('');
                                $(row).append(a);
                            }
                            $($(row).children().get(0)).html('<strong>Total</strong>');
                            $($(row).children().get(3)).html('<strong>'+calls+' Calls</strong>');
                            $($(row).children().get(4)).html('<strong>'+minitus+' (mm:ss)</strong>');
                            $($(row).children().get(5)).html('<strong> $' + amount.toFixed(6)+ '</strong>');
                        }else{
                            $(".table-1").find('tfoot').find('tr').html('');
                        }
                    }
                });
            }
           
            if($searchFilter.ActiveTab == "2"){
                var table2 = $('.table-1').DataTable({
                    "bDestroy": true, // Destroy when resubmit form
                    "bProcessing": true,
                    "bServerSide": true,

                    "ajax": {
                        "url" : "{{ route('vendorCdr.index') }}",
                        "data" : function ( d ){
                            d.ActiveTab= $searchFilter.ActiveTab
                            d.Trunk= $searchFilter.Trunk,
                            d.Account= $searchFilter.Account,
                            d.VAccount= $searchFilter.VAccount,
                            d.Gateway = $searchFilter.Country,
                            d.zerovaluecost= $searchFilter.zerovaluecost,
                            d.Cli= $searchFilter.Cli,
                            d.Cld= $searchFilter.Cld,
                            d.Prefix = $searchFilter.Prefix,
                            d.Tag= $searchFilter.Tag,
                            d.StartDate =  $searchFilter.StartDate,
                            d.EndDate = $searchFilter.EndDate
                        },
                    },
                    "aaSorting": [[0, "asc"]],
                    "language": {                
                                    "infoFiltered": ""
                                },
                    "aoColumns":
                    [
                        {data:'id',name:'id'},
                        {data:'account',name:'account'},
                        {data:'Connect_time',name:'Connect_time'},
                        {data:'Disconnect_time',name:'Disconnect_time'},
                        {data:'billing_duration',name:'billing_duration'},
                        {data:'Cost',name:'Cost'},
                        {data:'Avrage_cost',name:'Avrage_cost'},
                        {data:'callere164',name:'callere164'},
                        {data:'calleee164',name:'calleee164'},
                        {data:'Prefix',name:'Prefix'},
                        {data:'action',name:'action', orderable: false, searchable: false},
                    ],
                    "fnFooterCallback": function ( row, data, start, end, display ) {
                        var api = this.api(), data;

                        /* converting to interger to find total */
                        var intVal = function ( i ) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '')*1 :
                                typeof i === 'number' ?
                                    i : 0;
                        };

                        var calls = api.column( 3 ).data().reduce( function (a, b) {
                            return table2.data().length;
                        }, 0 );

                        var minitus = api.column( 4 ).data().reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                        minitus  = Math.floor(minitus  / 60)+":" + minitus % 60


                        var amount = api.column( 5 ).data().reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
                       if (end > 0) {
                           $(row).html('');
                           for (var i = 0; i < $('.table-1 thead th').length; i++) {
                               var a = document.createElement('td');
                               $(a).html('');
                               $(row).append(a);
                           }
                           $($(row).children().get(0)).html('<strong>Total</strong>')
                           $($(row).children().get(3)).html('<strong>'+calls+' Calls</strong>');
                           $($(row).children().get(4)).html('<strong>'+minitus+'(mm:ss)</strong>');
                           $($(row).children().get(5)).html('<strong> $'+amount.toFixed(6) + '</strong>');
                       }else{
                           $(".table-1").find('tfoot').find('tr').html('');
                       }
                   }
                });
            }
        });
            


            
        $('#customer').on('click', function() {
            $("#ActiveTab").val($(this).data('id'));
            $(".customer_Account").show();
            $(".vendor_Account").hide();
            var $searchFilter = {};
            var TotalCall = 0;
            var TotalDuration = 0;
            var TotalCost = 0; 

          
            $searchFilter.Account = $("#cdr_filter select[name='AccountID']").val();
            $searchFilter.VAccount = $("#cdr_filter select[name='VAccountID']").val();
            $searchFilter.Gateway = $("#cdr_filter select[name='GatewayID']").val();
            $searchFilter.zerovaluecost = $("#cdr_filter select[name='zerovaluecost']").val();
            $searchFilter.Cli = $("#cdr_filter input[name='CLI']").val();
            $searchFilter.Cld = $("#cdr_filter input[name='CLD']").val();
            $searchFilter.Prefix = $("#cdr_filter input[name='area_prefix']").val();
            $searchFilter.StartDate = $("#cdr_filter input[name='StartDate']").val();
            $searchFilter.EndDate = $("#cdr_filter input[name='EndDate']").val();
           
            if(typeof $searchFilter.StartDate  == 'undefined' || $searchFilter.StartDate.trim() == ''){
                $.notify("Please Select a Start date", "Error");
                return false;
            }
            if(typeof $searchFilter.EndDate  == 'undefined' || $searchFilter.EndDate.trim() == ''){
                $.notify("Please Select a End date", "Error");
                return false;
            }
    
            $searchFilter.ActiveTab = $("#cdr_filter input[name='ActiveTab']").val();

            var table_customer = $('.table-1').DataTable({
                    "bDestroy": true, // Destroy when resubmit form
                    "bProcessing": true,
                    "bServerSide": true,
                    "ajax": {
                        url : "{{ route('cdr_show.index') }}",
                        "data" : function ( d ){
                        d.ActiveTab= $searchFilter.ActiveTab ?? "",
                        d.Trunk= $searchFilter.Trunk ?? "",
                        d.Account= $searchFilter.Account ?? "",
                        d.VAccount= $searchFilter.VAccount ?? "",
                        d.Gateway = $searchFilter.Country ?? "", 
                        d.zerovaluecost= $searchFilter.zerovaluecost ?? "", 
                        d.Cli= $searchFilter.Cli ?? "",
                        d.Cld= $searchFilter.Cld ?? "",
                        d.Prefix = $searchFilter.Prefix ?? "",
                        d.Tag= $searchFilter.Tag ?? "",
                        d.StartDate =  $searchFilter.StartDate ?? "",
                        d.EndDate = $searchFilter.EndDate ?? ""
                },
                    },
                    "aaSorting": [[0, "asc"]],
                    "language": {                
                                    "infoFiltered": ""
                                },
                    "aoColumns":
                    [
                        {data:'id',name:'id'},
                        {data:'account',name:'account'},
                        {data:'Connect_time',name:'Connect_time'},
                        {data:'Disconnect_time',name:'Disconnect_time'},
                        {data:'billing_duration',name:'billing_duration'},
                        {data:'Cost',name:'Cost'},
                        {data:'Avrage_cost',name:'Avrage_cost'},
                        {data:'callere164',name:'callere164'},
                        {data:'calleee164',name:'calleee164'},
                        {data:'Prefix',name:'Prefix'},
                        {data:'action',name:'action', orderable: false, searchable: false},
                    ],
                    "fnFooterCallback": function ( row, data, start, end, display ) {
                        var api = this.api(), data;

                        /* converting to interger to find total */
                        var intVal = function ( i ) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '')*1 :
                                typeof i === 'number' ?
                                    i : 0;
                        };

                        var calls = api.column( 3 ).data().reduce( function (a, b) {
                            return table_customer.data().length;
                        }, 0 );

                        var minitus = api.column( 4 ).data().reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                        minitus  = Math.floor(minitus  / 60)+":" + minitus % 60


                        var amount = api.column( 5 ).data().reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
                    if (end > 0) {
                        $(row).html('');
                        for (var i = 0; i < $('.table-1 thead th').length; i++) {
                            var a = document.createElement('td');
                            $(a).html('');
                            $(row).append(a);
                        }
                        $($(row).children().get(0)).html('<strong>Total</strong>');
                        $($(row).children().get(3)).html('<strong>'+calls+' Calls</strong>');
                        $($(row).children().get(4)).html('<strong>'+minitus+' (mm:ss)</strong>');
                        $($(row).children().get(5)).html('<strong> $' + amount.toFixed(6)+ '</strong>');
                    }else{
                        $(".table-1").find('tfoot').find('tr').html('');
                    }
                }
            });
        });
        $('#vendor').on('click', function() {
            $(".customer_Account").hide();
            $(".vendor_Account").show();
            $("#ActiveTab").val($(this).data('id'))
            var $searchFilter = {};
            var TotalCall = 0;
            var TotalDuration = 0;
            var TotalCost = 0; 

          
            $searchFilter.Account = $("#cdr_filter select[name='AccountID']").val();
            $searchFilter.VAccount = $("#cdr_filter select[name='VAccountID']").val();
            $searchFilter.Gateway = $("#cdr_filter select[name='GatewayID']").val();
            $searchFilter.zerovaluecost = $("#cdr_filter select[name='zerovaluecost']").val();
            $searchFilter.Cli = $("#cdr_filter input[name='CLI']").val();
            $searchFilter.Cld = $("#cdr_filter input[name='CLD']").val();
            $searchFilter.Prefix = $("#cdr_filter input[name='area_prefix']").val();
            $searchFilter.StartDate = $("#cdr_filter input[name='StartDate']").val();
            $searchFilter.EndDate = $("#cdr_filter input[name='EndDate']").val();
           
            if(typeof $searchFilter.StartDate  == 'undefined' || $searchFilter.StartDate.trim() == ''){
                $.notify("Please Select a Start date", "Error");
                return false;
            }
            if(typeof $searchFilter.EndDate  == 'undefined' || $searchFilter.EndDate.trim() == ''){
                $.notify("Please Select a End date", "Error");
                return false;
            }
            $searchFilter.ActiveTab = $("#cdr_filter input[name='ActiveTab']").val();

            var table_vendor = $('.table-1').DataTable({
                "bDestroy": true, // Destroy when resubmit form
                "bProcessing": true,
                "bServerSide": true,

                "ajax": {
                    "url" : "{{ route('vendorCdr.index') }}",
                    "data" : function ( d ){
                        d.ActiveTab= $searchFilter.ActiveTab ?? "",
                        d.Trunk= $searchFilter.Trunk ?? "",
                        d.Account= $searchFilter.Account ?? "",
                        d.VAccount= $searchFilter.VAccount ?? "",
                        d.Gateway = $searchFilter.Country ?? "", 
                        d.zerovaluecost= $searchFilter.zerovaluecost ?? "", 
                        d.Cli= $searchFilter.Cli ?? "",
                        d.Cld= $searchFilter.Cld ?? "",
                        d.Prefix = $searchFilter.Prefix ?? "",
                        d.Tag= $searchFilter.Tag ?? "",
                        d.StartDate =  $searchFilter.StartDate ?? "",
                        d.EndDate = $searchFilter.EndDate ?? ""
                    }
                },
                "aaSorting": [[0, "asc"]],
                "language": {                
                                "infoFiltered": ""
                            },
                "aoColumns":
                [
                    {data:'id',name:'id'},
                    {data:'account',name:'account'},
                    {data:'Connect_time',name:'Connect_time'},
                    {data:'Disconnect_time',name:'Disconnect_time'},
                    {data:'billing_duration',name:'billing_duration'},
                    {data:'Cost',name:'Cost'},
                    {data:'Avrage_cost',name:'Avrage_cost'},
                    {data:'callere164',name:'callere164'},
                    {data:'calleee164',name:'calleee164'},
                    {data:'Prefix',name:'Prefix'},
                    {data:'action',name:'action', orderable: false, searchable: false},
                ],
                "fnFooterCallback": function ( row, data, start, end, display ) {
                    var api = this.api(), data;

                    /* converting to interger to find total */
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };

                    var calls = api.column( 3 ).data().reduce( function (a, b) {
                        return table_vendor.data().length;
                    }, 0 );

                    var minitus = api.column( 4 ).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    minitus  = Math.floor(minitus  / 60)+":" + minitus % 60


                    var amount = api.column( 5 ).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                    if (end > 0) {
                        $(row).html('');
                        for (var i = 0; i < $('.table-1 thead th').length; i++) {
                            var a = document.createElement('td');
                            $(a).html('');
                            $(row).append(a);
                        }
                        $($(row).children().get(0)).html('<strong>Total</strong>')
                        $($(row).children().get(3)).html('<strong>'+calls+' Calls</strong>');
                        $($(row).children().get(4)).html('<strong>'+minitus+'(mm:ss)</strong>');
                        $($(row).children().get(5)).html('<strong> $'+amount.toFixed(6) + '</strong>');
                    }else{
                        $(".table-1").find('tfoot').find('tr').html('');
                    }
                }
            });
        });
    });
    $(document).ready(function() {
        var myDate = new Date();
        $('#datepicker').datetimepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            format: 'yyyy-mm-dd HH:ii:ss',
           orientation: "bottom" // add this
        });
        $('#datepicker').datetimepicker('setDate', myDate);
        $('#datepicker1').datetimepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            format: 'yyyy-mm-dd HH:ii:ss',
            orientation: "bottom" // add this
        });
        $('#datepicker1').datetimepicker('setDate', myDate);
       
    });
   

    $(document).on('click','.callhistoryForm',function(e){
        var id = $(this).data('id')
        $.ajax({
           type:'get',
           url:"{{ route('getCallhistory') }}",
           data:{id,id},
           success:function(data){
              $('#callForm').html(data);
              $("#ajaxModel").modal('show');
           }
        });
    });



</script>

@endsection
