@extends('layouts.dashboard')
@section('content')
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
                                                    <select class="form-control" id="report" allowClear="true" name="report">
                                                        <option value="">Select</option>
                                                        <option value="Customer-Summary">Customer Summary</option>
                                                        <option value="Customer-Hourly">Customer Hourly</option>
                                                        <option value="Customer/Vendor-Report">Customer/Vendor Report</option>
                                                        <option value="Account-Manage">Account Manage</option>
                                                        <option value="Margin-Report">Margin Report</option>
                                                        <option value="Negative-Report">Negative Report</option>
                                                    </select>
                                                </div>
                                                
                                            
                                                <div class="form-group mt-4">
                                                    <input type="hidden" id="ActiveTab" name="ActiveTab" value="1">
                                                    <a href="#" class="btn btn-primary save-collection btn-md mt-2"  id="ToolTables_table-4_0">
                                                        <undefined>EXCEL</undefined>
                                                    </a>
                                                    <a href="#" class="btn btn-primary save-collection btn-md mt-2" id="ToolTables_table-4_1">
                                                        <undefined>CSV</undefined>
                                                    </a>
                                                </div>
                                            </form>
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
                                                    <select class="form-control" id="report" allowClear="true" name="report">
                                                        <option value="">Select</option>
                                                        <option value="Vendor-Summary">Vendor Summary</option>
                                                        <option value="Vendor-Hourly">Vendor Hourly</option>
                                                        <option value="Customer/Vendor-Report">Customer/Vendor Report</option>
                                                        <option value="Account-Manage">Account Manage</option>
                                                        <option value="Margin-Report">Margin Report</option>
                                                        <option value="Negative-Report">Negative Report</option>
                                                    </select>
                                                </div>
                                                
                                            
                                                <div class="form-group mt-4">
                                                    <input type="hidden" id="ActiveTab" name="ActiveTab" value="2">
                                                    <a href="#" class="btn btn-primary save-collection btn-md mt-2"  id="ToolTables_table-4_0">
                                                        <undefined>EXCEL</undefined>
                                                    </a>
                                                    <a href="#" class="btn btn-primary save-collection btn-md mt-2" id="ToolTables_table-4_1">
                                                        <undefined>CSV</undefined>
                                                    </a>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-bordered data-table">
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
            $('.timepicker').datetimepicker({
                format: 'HH:mm:ss'
            });
    
            var $searchFilter = {};
            var TotalCall = 0;
            var TotalDuration = 0;
            var TotalCost = 0;
            
            $searchFilter.Account = $("#cdr_filter select[name='AccountID']").val();
            $searchFilter.Report = $("#cdr_filter select[name='report']").val();
            $searchFilter.ActiveTab = $("#cdr_filter input[name='ActiveTab']").val();

            var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax:
                {
                "url": "{{ route('export-csv.history')}}",
                "data" : function ( d ){
                    d.Account= $searchFilter.Account ?? "",
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
    $(document).on("click", ".csv-xlsx",function() {
            var $searchFilter = {};
            var ref_this = $("ul.nav li.nav-item a.nav-link.active");
            if(ref_this.data('id') == 1){
               
                $searchFilter.Account = $("#cdr_filter select[name='AccountID']").val();
                $searchFilter.Report = $("#cdr_filter select[name='report']").val();
                $searchFilter.ActiveTab = $("#cdr_filter input[name='ActiveTab']").val();

            }
            if(ref_this.data('id') == 2){
               
                $searchFilter.Account = $("#cdr_filter_1 select[name='AccountID']").val();
                $searchFilter.Report = $("#cdr_filter_1 select[name='report']").val();
                $searchFilter.ActiveTab = $("#cdr_filter_1 input[name='ActiveTab']").val();

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
        $('#ToolTables_table-4_1').on("click",function(e){
            e.preventDefault();
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
   

   
</script>

@endsection
