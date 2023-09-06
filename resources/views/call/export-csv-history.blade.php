@extends('layouts.dashboard')
@section('content')
<style>
   .for-scroll{
        overflow: auto;
        overscroll-behavior-x: auto;
    }
</style>
<div class="content-wrapper mt-3" >
    <section class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">

                </div>
                <div class="col-sm-6">
                    {{-- <ol class="breadcrumb float-sm-right">
                        <a href="" class="btn btn-primary mb-4 float-right w-10" id="Filter">Filter</a>
                    </ol> --}}
                </div>
            </div>
        </div>
    </section>
    @if($errors->any())
        <div class="container-fluid">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{$errors->first()}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @endif
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h1 class="card-title ">Reports</h1>
                        </div>
                        <div class="card-body">
                            <div class="panel panel-primary">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item csv-xlsx">
                                      <a class="nav-link check-active active" data-id="1" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Customer</a>
                                    </li>
                                    <li class="nav-item csv-xlsx">
                                      <a class="nav-link check-active" id="profile-tab" data-id="2"  data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Vendor</a>
                                    </li>
                                    <li class="nav-item csv-xlsx">
                                    <a class="nav-link check-active" id="download-tab" data-id="3"  data-toggle="tab" href="#download" role="tab" aria-controls="download" aria-selected="false">Download History</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                        <div class="container-fluid  ml-2 mt-3">
                                            <form novalidate class="form-horizontal form-groups-bordered validate d-flex gap-1" method="post" id="cdr_filter">
                                                <input type="hidden" name="type" class="form-control"  value="Customer"  />
                                                <div class="form-group customer_Account">
                                                    <label class="control-label" for="field-1">Customer Name</label>
                                                    <select class="form-control" id="bulk_AccountID" allowClear="true" name="AccountID">
                                                        @if(!empty($Accounts))
                                                            <option value="">Select</option>
                                                            @foreach ( $Accounts as $Account )
                                                                <option value="{{$Account->id}}">{{$Account->firstname}}{{$Account->lastname}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="control-label" for="field-1">Report Name</label>
                                                    <select class="form-control" id="report" allowClear="true" name="report"
                                                    style="width: 138px;">
                                                        <option value="">Select</option>
                                                        <option value="Customer-Summary">Customer Summary</option>
                                                        <option value="Customer-Hourly">Customer Hourly</option>
                                                        <option value="Customer/Vendor-Report">Customer/Vendor Report</option>
                                                        <option value="Account-Manage">Account Manage</option>
                                                        <option value="Customer-Margin-Report">Margin Report</option>
                                                        <option value="Customer-Negative-Report">Negative Report</option>
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
                                               
                                                <div class="form-group mt-4">
                                                    <input type="hidden" id="ActiveTab" name="ActiveTab" value="1">
                                                    <a href="" class="btn btn-primary mb-4 w-10 mt-2" id="view_data"><i class="fas fa-eye"></i></a>
                                                    <a href="#" class="btn btn-primary save-collection btn-md  mb-3"  id="ToolTables_table-4_0">
                                                        <undefined>EXCEL</undefined>
                                                    </a>
                                                    <a href="#" class="btn btn-primary save-collection btn-md  mb-3" id="ToolTables_table-4_1">
                                                        <undefined>CSV</undefined>
                                                    </a>
                                                </div>
                                            </form>
                                        </div>
                                        <div id="tables_data">
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                        <div class="container-fluid  ml-2 mt-3">
                                            <form novalidate class="form-horizontal form-groups-bordered validate d-flex gap-1" method="post" id="cdr_filter_1">
                                                <input type="hidden" name="type" class="form-control"  value="Vendor"  />
                                                <div class="form-group vendor_Account">
                                                    <label class="control-label" for="field-1">Vendor Name</label>
                                                    <select class="form-control" id="bulk_AccountID" allowClear="true" name="AccountID">
                                                        @if(!empty($VAccounts))
                                                            <option value="">Select</option>
                                                            @foreach ( $VAccounts as $Account )
                                                                <option value="{{$Account->id}}">{{$Account->firstname}}{{$Account->lastname}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label" for="field-1">Report Name</label>
                                                    <select class="form-control" id="report" allowClear="true" name="report" style="width: 138px;">
                                                        <option value="">Select</option>
                                                        <option value="Vendor-Summary">Vendor Summary</option>
                                                        <option value="Vendor-Hourly">Vendor Hourly</option>
                                                        <option value="Customer/Vendor-Report">Customer/Vendor Report</option>
                                                        <option value="Account-Manage">Account Manage</option>
                                                        <option value="Vendor-Margin-Report">Margin Report</option>
                                                        <option value="Vendor-Negative-Report">Negative Report</option>
                                                    </select>
                                                </div>
                                                <div class="form-group" style="max-width: 300px;">
                                                    <label class="control-label small_label" for="field-1">Start Date</label>
                                                    <div class="d-flex gap-1">
                                                        <input type="text" name="StartDate" class="form-control datepicker w-35" id="datepicker2"  data-date-format="yyyy-mm-dd HH:mm:ss" value="2023-03-03" data-enddate="2023-03-03"  />
                                                    </div>
                                                </div>
                                                <div class="form-group" style="max-width: 300px;">
                                                    <label class="col-md-4 control-label small_label" for="field-1" style="padding-left: 0px;">End Date</label>
                                                    <div class="d-flex gap-1">
                                                        <input type="text" name="EndDate" 
                                                        id="datepicker3" class="form-control datepicker"  data-date-format="yyyy-mm-dd HH:mm:ss" value="2023-03-03" data-enddate="2023-03-03" />
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group mt-4">
                                                    <input type="hidden" id="ActiveTab" name="ActiveTab" value="2">
                                                    <a href="" class="btn btn-primary mb-4  w-10 mt-2" id="view_data"><i class="fas fa-eye"></i></a>
                                                    <a href="#" class="btn btn-primary save-collection btn-md mb-3"  id="ToolTables_table-4_0">
                                                        <undefined>EXCEL</undefined>
                                                    </a>
                                                    <a href="#" class="btn btn-primary save-collection btn-md mb-3" id="ToolTables_table-4_1">
                                                        <undefined>CSV</undefined>
                                                    </a>
                                                   
                                                </div>
                                            </form>
                                        </div>
                                        <div id="tables_data_1">
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="download" role="tabpanel" aria-labelledby="download-tab">
                                        <div class="container-fluid  ml-2 mt-3">
                                            {{-- <form novalidate class="form-horizontal form-groups-bordered validate d-flex gap-1" method="post" id="cdr_filter_2">
                                                <input type="hidden" name="type" class="form-control"  value="Customer"  />
                                                <div class="form-group customer_Account">
                                                    <label class="control-label" for="field-1">Account List</label>
                                                    <select class="form-control" id="bulk_AccountID" allowClear="true" name="AccountID">
                                                        <optgroup label="Customer-Accounts">
                                                            @if(!empty($Accounts))
                                                                <option value="">Select</option>
                                                                @foreach ( $Accounts as $Account )
                                                                    <option value="{{$Account->id}}">{{$Account->firstname}}{{$Account->lastname}}</option>
                                                                @endforeach
                                                            @endif
                                                        </optgroup>
                                                        <optgroup label="Vendor-Accounts">
                                                            @if(!empty($VAccounts))
                                                                @foreach ( $VAccounts as $Account )
                                                                    <option value="{{$Account->id}}">{{$Account->firstname}}{{$Account->lastname}}</option>
                                                                @endforeach
                                                            @endif
                                                        </optgroup>
                                                    </select>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="control-label" for="field-1">Report</label>
                                                    <select class="form-control" id="report" allowClear="true" name="report"
                                                    style="width: 138px;">
                                                        <option value="">Select</option>
                                                        <optgroup label="Customer-Reports">
                                                            <option value="Customer-Summary">Customer Summary</option>
                                                            <option value="Customer-Hourly">Customer Hourly</option>
                                                            <option value="Customer-Margin-Report">Customer Margin Report</option>
                                                            <option value="Customer-Negative-Report">Customer Negative Report</option>
                                                            <option value="Account-Manage">Account Manage</option>
                                                            <option value="Vendor-Summary">Vendor Summary</option>
                                                        </optgroup>
                                                        <optgroup label="Vendor-Reports">
                                                            <option value="Customer/Vendor-Report">Customer/Vendor Report</option>
                                                            <option value="Account-Manage">Account Manage</option>
                                                            <option value="Vendor-Summary">Vendor Summary</option>
                                                            <option value="Vendor-Hourly">Vendor Hourly</option>
                                                            <option value="Vendor-Margin-Report">Vendor Margin Report</option>
                                                            <option value="Vendor-Negative-Report">Vendor Negative Report</option>
                                                        </optgroup>
                                                    </select>
                                                </div>
                                                <div class="form-group" style="max-width: 300px;">
                                                    <label class="control-label small_label" for="field-1">Start Date</label>
                                                    <div class="d-flex gap-1">
                                                        <input type="text" name="StartDate" class="form-control datepicker w-35" id="datepicker4"  data-date-format="yyyy-mm-dd HH:mm:ss" value="2023-03-03" data-enddate="2023-03-03"  />
                                                    </div>
                                                </div>
                                                <div class="form-group" style="max-width: 300px;">
                                                    <label class="col-md-4 control-label small_label" for="field-1" style="padding-left: 0px;">End Date</label>
                                                    <div class="d-flex gap-1">
                                                        <input type="text" name="EndDate" 
                                                        id="datepicker5" class="form-control datepicker"  data-date-format="yyyy-mm-dd HH:mm:ss" value="2023-03-03" data-enddate="2023-03-03" />
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group mt-4">
                                                    <input type="hidden" id="ActiveTab" name="ActiveTab" value="3">
                                                    <a href="" class="btn btn-primary mb-4 w-10 mt-2" id="filter">Filter</a>
                                                </div>
                                            </form> --}}
                                        </div>
                                        <table class="table  w-100 table-bordered data-table">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Client</th>
                                                    <th>type</th>
                                                    <th>Status</th>
                                                    <th>Created At</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
            
                                            </tbody>
                                        </table>
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
        $('#datepicker').datetimepicker('setDate', myDate );
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

        $('#datepicker2').datetimepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            format: 'yyyy-mm-dd HH:ii:ss',
           orientation: "bottom" // add this
        });
        $('#datepicker2').datetimepicker('setDate', myDate);
        $('#datepicker3').datetimepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            format: 'yyyy-mm-dd HH:ii:ss',
           orientation: "bottom" // add this
           
        });
        $('#datepicker3').datetimepicker('setDate', myDate);

        $('#datepicker4').datetimepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            format: 'yyyy-mm-dd HH:ii:ss',
           orientation: "bottom" // add this
        });
        $('#datepicker4').datetimepicker('setDate', myDate);
        $('#datepicker5').datetimepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            format: 'yyyy-mm-dd HH:ii:ss',
           orientation: "bottom" // add this
        });
        $('#datepicker5').datetimepicker('setDate', myDate);
        
    });
    

        $(document).on('click','#ToolTables_table-4_0',function(e) {
            e.preventDefault();
            var ref_this = $("ul.nav li.nav-item a.nav-link.active");
            if(ref_this.data('id') == 1){
                var form = $('#cdr_filter').serialize();
            }
            if(ref_this.data('id') == 2){
                var form =$('#cdr_filter_1').serialize();
            }

            $.ajax({
                type:'get',
                url:"{{ url('export-history/xlsx') }}",
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                data: form,
                success: function(result) {
                    if(result.success == true){
                        $.notify(result.message,'success');
                        $("#FilterModel").modal("hide");
                    }else{
                        $.each(result.errors, function (k, e) {
                            $.notify(e, 'error');
                        });
                    }
                },
                error: function(result) {
                    alert('error');
                }
            });
        });
        $(document).on('click','#ToolTables_table-4_1',function(e) {
            e.preventDefault();
            var ref_this = $("ul.nav li.nav-item a.nav-link.active");
            if(ref_this.data('id') == 1){
                var form = $('#cdr_filter').serialize();
            }
            if(ref_this.data('id') == 2){
                var form =$('#cdr_filter_1').serialize();
            }
            $.ajax({
                type:'get',
                url:"{{ url('export-history/csv') }}",
                data: form,
                success: function(result) {
                    if(result.success == true){
                        $.notify(result.message, 'success');
                        $("#FilterModel").modal("hide");
                    }else{
                        $.each(result.errors, function (k, e) {
                            $.notify(e, 'error');
                        });
                    }
                },
                error: function(result) {
                    alert('error');
                }
            });
        });
        $(document).on("click", "#view_data",function(e) {
            e.preventDefault();
            var $searchFilter = {};
            var ref_this = $("ul.nav li.nav-item a.nav-link.active");
            if(ref_this.data('id') == 1){
              
                $searchFilter.Account = $("#cdr_filter select[name='AccountID']").val();
                $searchFilter.Report = $("#cdr_filter select[name='report']").val();
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
                if(typeof $searchFilter.Report  == 'undefined' || $searchFilter.Report.trim() == ''){
                    $.notify("Please Select a Report", "Error");
                    return false;
                }
                if(typeof $searchFilter.Account  == 'undefined' || $searchFilter.Account.trim() == ''){
                    $.notify("Please Select a Account", "Error");
                    return false;
                }
                $searchFilter.ActiveTab = $("#cdr_filter input[name='ActiveTab']").val();
                $searchFilter.type = "Customer";

            }
            if(ref_this.data('id') == 2){
               
                $searchFilter.Account = $("#cdr_filter_1 select[name='AccountID']").val();
                $searchFilter.Report = $("#cdr_filter_1 select[name='report']").val();
                $searchFilter.StartDate = $("#cdr_filter_1 input[name='StartDate']").val();
                $searchFilter.EndDate = $("#cdr_filter_1 input[name='EndDate']").val();
                if(typeof $searchFilter.StartDate  == 'undefined' || $searchFilter.StartDate.trim() == ''){
                    $.notify("Please Select a Start date", "Error");
                    return false;
                }
                if(typeof $searchFilter.EndDate  == 'undefined' || $searchFilter.EndDate.trim() == ''){
                    $.notify("Please Select a End date", "Error");
                    return false;
                }
                if(typeof $searchFilter.Report  == 'undefined' || $searchFilter.Report.trim() == ''){
                    $.notify("Please Select a Report", "Error");
                    return false;
                }
                if(typeof $searchFilter.Account  == 'undefined' || $searchFilter.Account.trim() == ''){
                    $.notify("Please Select a Account", "Error");
                    return false;
                }
                $searchFilter.ActiveTab = $("#cdr_filter_1 input[name='ActiveTab']").val();
                $searchFilter.type = "Vendor";
            }
            $.ajax({
                url: "{{url('/csv_view')}}",
                data:{
                    'Account': $searchFilter.Account,
                    'StartDate' : $searchFilter.StartDate ,
                    'EndDate' : $searchFilter.EndDate,
                    'Report' :  $searchFilter.Report,
                    'ActiveTab' :  $searchFilter.ActiveTab,
                    'type' : $searchFilter.type 
                },
                type: 'get',
                success: function (data)
                {
                    $('#tables_data_1').html();
                    if(ref_this.data('id') == 2){
                        $('#tables_data_1').html(data)
                    }   
                    if(ref_this.data('id') == 1){
                        $('#tables_data').html(data)
                    }                  
                },

                error: function(xhr) {
                $('#global-loader').hide();
                    // $.notify(xhr.responseText,'error'); // this line will save you tons of hours while debugging
                    // do something here because of error
                }
            });
        });


          
            var table = $('.data-table').DataTable({
                "bDestroy": true, // Destroy when resubmit form
                "bProcessing": true,
                "bServerSide": true,
                ajax:
                    {
                    "url": "{{ route('export-csv.history')}}",
                    
                },
                columns: [
                    {data:'id',name:'id'},
                    {data:'client',name:'client'},
                    {data:'type',name:'type'},
                    {data:'status',name:'status'},
                    {data:'created_at',name:'created_at'},
                    {data:'action',name:'action', orderable: false, searchable: false},
                ]
            });
     
</script>
<script type="text/javascript">
  
    $(window).on('hashchange', function() {
        var ref_this = $("ul.nav li.nav-item a.nav-link.active");
        if(ref_this.data('id') == 1){
            if (window.location.hash) {
                var page = window.location.hash.replace('#', '');
                if (page == Number.NaN || page <= 0) {
                    return false;
                }
            }
        }
        if(ref_this.data('id') == 2){
            if (window.location.hash) {
                var page = window.location.hash.replace('#', '');
                if (page == Number.NaN || page <= 0) {
                    return false;
                }
            }
        }
        
    });
    $(document).on('click', '.check-active',function(event){
        event.preventDefault();
        if($(this).data('id') == 1){
            var customerPage = localStorage.getItem("customer_page");
            customerPage = parseInt(customerPage) + 1;
            var value1 = '.pagination li:nth-child(' +  customerPage + ')';
            $('.pagination li').removeClass('active');
            $(value1).addClass('active');
        }
        if($(this).data('id') == 2){
            var vendorPage = localStorage.getItem("Vendor_page");
            vendorPage = parseInt(vendorPage) + 1;
            var value = '.pagination li:nth-child(' + vendorPage + ')';
            $('.pagination li').removeClass('active');
            $(value).addClass('active');
        }
    });
      
    $(document).ready(function()
    {
        $(document).on('click', '.pagination a',function(event)
        {
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            event.preventDefault();
      
            var myurl = $(this).attr('href');
            var page=$(this).attr('href').split('page=')[1];
      
            var $searchFilter = {};
            var ref_this = $("ul.nav li.nav-item a.nav-link.active");
            if(ref_this.data('id') == 1){
             
                $searchFilter.Account = $("#cdr_filter select[name='AccountID']").val();
                $searchFilter.Report = $("#cdr_filter select[name='report']").val();
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
                if(typeof $searchFilter.Report  == 'undefined' || $searchFilter.Report.trim() == ''){
                    $.notify("Please Select a Report", "Error");
                    return false;
                }
                if(typeof $searchFilter.Account  == 'undefined' || $searchFilter.Account.trim() == ''){
                    $.notify("Please Select a Account", "Error");
                    return false;
                }
                $searchFilter.ActiveTab = $("#cdr_filter input[name='ActiveTab']").val();
                $searchFilter.type = "Customer";

            }
            if(ref_this.data('id') == 2){
               
                $searchFilter.Account = $("#cdr_filter_1 select[name='AccountID']").val();
                $searchFilter.Report = $("#cdr_filter_1 select[name='report']").val();
                $searchFilter.StartDate = $("#cdr_filter_1 input[name='StartDate']").val();
                $searchFilter.EndDate = $("#cdr_filter_1 input[name='EndDate']").val();
                if(typeof $searchFilter.StartDate  == 'undefined' || $searchFilter.StartDate.trim() == ''){
                    $.notify("Please Select a Start date", "Error");
                    return false;
                }
                if(typeof $searchFilter.EndDate  == 'undefined' || $searchFilter.EndDate.trim() == ''){
                    $.notify("Please Select a End date", "Error");
                    return false;
                }
                if(typeof $searchFilter.Report  == 'undefined' || $searchFilter.Report.trim() == ''){
                    $.notify("Please Select a Report", "Error");
                    return false;
                }
                if(typeof $searchFilter.Account  == 'undefined' || $searchFilter.Account.trim() == ''){
                    $.notify("Please Select a Account", "Error");
                    return false;
                }
                $searchFilter.ActiveTab = $("#cdr_filter_1 input[name='ActiveTab']").val();
                $searchFilter.type = "Vendor";
            }
            $.ajax({
                url: myurl,
                type: "get",
                data:{
                    'Account': $searchFilter.Account,
                    'StartDate' : $searchFilter.StartDate ,
                    'EndDate' : $searchFilter.EndDate,
                    'Report' :  $searchFilter.Report,
                    'ActiveTab' :  $searchFilter.ActiveTab,
                    'type' : $searchFilter.type 
                },
                datatype: "html",
            })
            .done(function(data){

                if(ref_this.data('id') == 2){
                    $('#tables_data_1').html(data),
                    location.hash = "Vendor_page?"+page;
                    localStorage.setItem('Vendor_page',page);


                }   
                if(ref_this.data('id') == 1){
                    $('#tables_data').html(data),
                    location.hash = "customer_page?"+page;
                    localStorage.setItem('customer_page', page);


                }      
            })
            .fail(function(jqXHR, ajaxOptions, thrownError){
                alert('No response from server');
            });
        });
    });
      
   
      
    </script>

@endsection
