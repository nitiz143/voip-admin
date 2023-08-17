@extends('layouts.dashboard')
@section('content')
<style>
    #tables_data .table {border-collapse:collapse !important; table-layout:fixed  ; }
    #tables_data .table td {white-space: nowrap !important; overflow: hidden !important;  }
    #tables_data_1 .table {border-collapse:collapse !important; table-layout:fixed  ; }
    #tables_data_1 .table td {white-space: nowrap !important; overflow: hidden !important;  }
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
                                      <a class="nav-link active" data-id="1" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Customer</a>
                                    </li>
                                    <li class="nav-item csv-xlsx">
                                      <a class="nav-link" id="profile-tab" data-id="2"  data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Vendor</a>
                                    </li>
                                    <li class="nav-item csv-xlsx">
                                    <a class="nav-link" id="download-tab" data-id="3"  data-toggle="tab" href="#download" role="tab" aria-controls="download" aria-selected="false">Download</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                        <div class="container-fluid  ml-2 mt-3">
                                            <form novalidate class="form-horizontal form-groups-bordered validate d-flex gap-1" method="post" id="cdr_filter">
                                                <input type="hidden" name="type" class="form-control"  value="Customer"  />
                                                <div class="form-group" style="max-width: 300px;">
                                                    <label class="control-label small_label" for="field-1">Start Date</label>
                                                    <div class="d-flex gap-1">
                                                        <input type="text" name="StartDate" class="form-control datepicker w-35" id="datepicker"  data-date-format="yyyy-mm-dd" value="2023-03-03" data-enddate="2023-03-03"  />
                            
                                                        <input type="text" name="StartTime" data-minute-step="5" data-show-meridian="false" data-default-time="00:00:00" value="00:00:00" data-show-seconds="true" data-template="dropdown" class="form-control timepicker" >
                                                    </div>
                                                </div>
                                                <div class="form-group" style="max-width: 300px;">
                                                    <label class="col-md-4 control-label small_label" for="field-1" style="padding-left: 0px;">End Date</label>
                                                    <div class="d-flex gap-1">
                                                        <input type="text" name="EndDate" 
                                                        id="datepicker1" class="form-control datepicker"  data-date-format="yyyy-mm-dd" value="2023-03-03" data-enddate="2023-03-03" />
                                                    
                                                        <input type="text" name="EndTime" data-minute-step="5" data-show-meridian="false" data-default-time="23:59:59" value="23:59:59" data-show-seconds="true" data-template="dropdown" class="form-control timepicker">
                                                    </div>
                                                </div>
                                                <div class="form-group customer_Account">
                                                    <label class="control-label" for="field-1">Customers List</label>
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
                                                    <label class="control-label" for="field-1">Report</label>
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
                                                <div class="form-group" style="max-width: 300px;">
                                                    <label class="control-label small_label" for="field-1">Start Date</label>
                                                    <div class="d-flex gap-1">
                                                        <input type="text" name="StartDate" class="form-control datepicker w-35" id="datepicker2"  data-date-format="yyyy-mm-dd" value="2023-03-03" data-enddate="2023-03-03"  />
                            
                                                        <input type="text" name="StartTime" data-minute-step="5" data-show-meridian="false" data-default-time="00:00:00" value="00:00:00" data-show-seconds="true" data-template="dropdown" class="form-control timepicker" >
                                                    </div>
                                                </div>
                                                <div class="form-group" style="max-width: 300px;">
                                                    <label class="col-md-4 control-label small_label" for="field-1" style="padding-left: 0px;">End Date</label>
                                                    <div class="d-flex gap-1">
                                                        <input type="text" name="EndDate" 
                                                        id="datepicker3" class="form-control datepicker"  data-date-format="yyyy-mm-dd" value="2023-03-03" data-enddate="2023-03-03" />
                                                    
                                                        <input type="text" name="EndTime" data-minute-step="5" data-show-meridian="false" data-default-time="23:59:59" value="23:59:59" data-show-seconds="true" data-template="dropdown" class="form-control timepicker">
                                                    </div>
                                                </div>
                                                <div class="form-group vendor_Account">
                                                    <label class="control-label" for="field-1">Vendor List</label>
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
                                                    <label class="control-label" for="field-1">Report</label>
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
                                            <form novalidate class="form-horizontal form-groups-bordered validate d-flex gap-1" method="post" id="cdr_filter_2">
                                                <input type="hidden" name="type" class="form-control"  value="Customer"  />
                                                <div class="form-group" style="max-width: 300px;">
                                                    <label class="control-label small_label" for="field-1">Start Date</label>
                                                    <div class="d-flex gap-1">
                                                        <input type="text" name="StartDate" class="form-control datepicker w-35" id="datepicker4"  data-date-format="yyyy-mm-dd" value="2023-03-03" data-enddate="2023-03-03"  />
                            
                                                        <input type="text" name="StartTime" data-minute-step="5" data-show-meridian="false" data-default-time="00:00:00" value="00:00:00" data-show-seconds="true" data-template="dropdown" class="form-control timepicker" >
                                                    </div>
                                                </div>
                                                <div class="form-group" style="max-width: 300px;">
                                                    <label class="col-md-4 control-label small_label" for="field-1" style="padding-left: 0px;">End Date</label>
                                                    <div class="d-flex gap-1">
                                                        <input type="text" name="EndDate" 
                                                        id="datepicker5" class="form-control datepicker"  data-date-format="yyyy-mm-dd" value="2023-03-03" data-enddate="2023-03-03" />
                                                    
                                                        <input type="text" name="EndTime" data-minute-step="5" data-show-meridian="false" data-default-time="23:59:59" value="23:59:59" data-show-seconds="true" data-template="dropdown" class="form-control timepicker">
                                                    </div>
                                                </div>
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
                                                <div class="form-group mt-4">
                                                    <input type="hidden" id="ActiveTab" name="ActiveTab" value="3">
                                                    <a href="" class="btn btn-primary mb-4 w-10 mt-2" id="filter">Filter</a>
                                                </div>
                                            </form>
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
        $('#datepicker').datepicker();
        $('#datepicker').datepicker('setDate', myDate);
        $('#datepicker1').datepicker();
        $('#datepicker1').datepicker('setDate', myDate);

        $('#datepicker2').datepicker();
        $('#datepicker2').datepicker('setDate', myDate);
        $('#datepicker3').datepicker();
        $('#datepicker3').datepicker('setDate', myDate);

        $('#datepicker4').datepicker();
        $('#datepicker4').datepicker('setDate', myDate);
        $('#datepicker5').datepicker();
        $('#datepicker5').datepicker('setDate', myDate);
        $('.timepicker').datetimepicker({
            format: 'HH:mm:ss'
        });
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
                var starttime = $("#cdr_filter input[name='StartTime']").val();
                if(starttime =='00:00:01'){
                    starttime = '00:00:00';
                }
                $searchFilter.Account = $("#cdr_filter select[name='AccountID']").val();
                $searchFilter.Report = $("#cdr_filter select[name='report']").val();
                $searchFilter.StartDate = $("#cdr_filter input[name='StartDate']").val();
                $searchFilter.EndDate = $("#cdr_filter input[name='EndDate']").val();
                $searchFilter.starttime = $("#cdr_filter input[name='StartTime']").val();
                // $searchFilter.End_time = $("#cdr_filter input[name='EndTime']").val();
                if(typeof $searchFilter.StartDate  == 'undefined' || $searchFilter.StartDate.trim() == ''){
                    $.notify("Please Select a Start date", "Error");
                    return false;
                }
                if(typeof $searchFilter.EndDate  == 'undefined' || $searchFilter.EndDate.trim() == ''){
                    $.notify("Please Select a End date", "Error");
                    return false;
                }
                $searchFilter.StartDate += ' '+starttime;
                $searchFilter.EndDate += ' '+$("#cdr_filter [name='EndTime']").val();
                $searchFilter.ActiveTab = $("#cdr_filter input[name='ActiveTab']").val();
                $searchFilter.type = "Customer";

            }
            if(ref_this.data('id') == 2){
                var starttime = $("#cdr_filter_1 input[name='StartTime']").val();
                if(starttime =='00:00:01'){
                    starttime = '00:00:00';
                }
                $searchFilter.Account = $("#cdr_filter_1 select[name='AccountID']").val();
                $searchFilter.Report = $("#cdr_filter_1 select[name='report']").val();
                $searchFilter.StartDate = $("#cdr_filter_1 input[name='StartDate']").val();
                $searchFilter.EndDate = $("#cdr_filter_1 input[name='EndDate']").val();
                $searchFilter.starttime = $("#cdr_filter_1 input[name='StartTime']").val();
                // $searchFilter.End_time = $("#cdr_filter input[name='EndTime']").val();
                if(typeof $searchFilter.StartDate  == 'undefined' || $searchFilter.StartDate.trim() == ''){
                    $.notify("Please Select a Start date", "Error");
                    return false;
                }
                if(typeof $searchFilter.EndDate  == 'undefined' || $searchFilter.EndDate.trim() == ''){
                    $.notify("Please Select a End date", "Error");
                    return false;
                }
                $searchFilter.StartDate += ' '+starttime;
                $searchFilter.EndDate += ' '+$("#cdr_filter_1 [name='EndTime']").val();
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

        $(document).on("click", "#filter",function(e) {
            e.preventDefault();
            var $searchFilter = {};
            var ref_this = $("ul.nav li.nav-item a.nav-link.active");
        
            if(ref_this.data('id') == 3){
                var starttime = $("#cdr_filter_2 input[name='StartTime']").val();
                if(starttime =='00:00:01'){
                    starttime = '00:00:00';
                }
                $searchFilter.Account = $("#cdr_filter_2 select[name='AccountID']").val();
                $searchFilter.Report = $("#cdr_filter_2 select[name='report']").val();
                $searchFilter.StartDate = $("#cdr_filter_2 input[name='StartDate']").val();
                $searchFilter.EndDate = $("#cdr_filter_2 input[name='EndDate']").val();
                $searchFilter.starttime = $("#cdr_filter_2 input[name='StartTime']").val();
                // $searchFilter.End_time = $("#cdr_filter input[name='EndTime']").val();
                if(typeof $searchFilter.StartDate  == 'undefined' || $searchFilter.StartDate.trim() == ''){
                    $.notify("Please Select a Start date", "Error");
                    return false;
                }
                if(typeof $searchFilter.EndDate  == 'undefined' || $searchFilter.EndDate.trim() == ''){
                    $.notify("Please Select a End date", "Error");
                    return false;
                }
                $searchFilter.StartDate += ' '+starttime;
                $searchFilter.EndDate += ' '+$("#cdr_filter_2 [name='EndTime']").val();
                $searchFilter.ActiveTab = $("#cdr_filter_2 input[name='ActiveTab']").val();

            }
          
            var table = $('.data-table').DataTable({
                "bDestroy": true, // Destroy when resubmit form
                "bProcessing": true,
                "bServerSide": true,
                ajax:
                    {
                    "url": "{{ route('export-csv.history')}}",
                    "data" : function ( d ){
                        d.Account= $searchFilter.Account ?? "",
                        d.StartDate =  $searchFilter.StartDate ?? "",
                        d.EndDate = $searchFilter.EndDate ?? "",
                        d.Report =   $searchFilter.Report ?? "",
                        d.ActiveTab =   $searchFilter.ActiveTab ?? ""
                    },
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
        });
</script>

@endsection
