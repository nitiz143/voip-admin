@extends('layouts.dashboard')
@section('content')
<div class="content-wrapper mt-3" >
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
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h1 class="card-title">Invoices</h1>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item invoice_pdf">
                                    <a class="nav-link active" id="home-tab" data-id="1" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Customer</a>
                                </li>
                                <li class="nav-item invoice_pdf">
                                    <a class="nav-link" id="profile-tab" data-id="2"  data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Vendor</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                    <div class="container-fluid  ml-1 mt-4">
                                        <form novalidate class="form-horizontal form-groups-bordered validate d-flex gap-1" method="post" id="cdr_filter">
                                            @csrf
                                            <input type="hidden" name="type" class="form-control"  value="Customer"  />
                                            <div class="form-group customer_Account">
                                                <label class="control-label" for="field-1"> Customer Name</label>
                                                <select class="form-control" id="bulk_AccountID" allowClear="true" name="AccountID" required>
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
                                                <select class="form-control" id="report" allowClear="true" name="report">
                                                    <option value="">Select</option>
                                                    <option value="Customer-Summary">Customer Summary</option>
                                                    <option value="Customer-Hourly">Customer Hourly</option>
                                                    <option value="Customer/Vendor-Report">Customer/Vendor Report</option>
                                                    <option value="Account-Manage">Account Manage</option>
                                                    <option value="Margin-Report">Margin Report</option>
                                                    <option value="Customer-Negative-Report">Negative Report</option>
                                                </select>
                                            </div>
                                            <div class="form-group" style="max-width: 300px;">
                                                <label class="control-label small_label" for="field-1">Start Date</label>
                                                <div class="d-flex gap-1">
                                                    <input type="text" name="StartDate" class="form-control datepicker w-35" id="datepicker"  data-date-format="yyyy-mm-dd hh:mm:ss" value="2021-04-16 14:45:00" data-enddate="2021-04-16 14:45:00"/>
                                                </div>
                                            </div>
                                            <div class="form-group" style="max-width: 300px;">
                                                <label class="col-md-4 control-label small_label" for="field-1" style="padding-left: 0px;">End Date</label>
                                                <div class="d-flex gap-1">
                                                    <input type="text" name="EndDate"
                                                    id="datepicker1" class="form-control datepicker"  data-date-format="yyyy-mm-dd hh:mm:ss" value="2021-04-16 14:45:00" data-enddate="2021-04-16 14:45:00" />
                                                </div>
                                            </div>
                                            
                                            <div class="form-group mt-4 d-flex">
                                                <input type="hidden" id="ActiveTab" name="ActiveTab" value="1">
                                                <a type="submit"  class="btn btn-primary btn-md btn-icon mt-2 " id="filter_data" ><i class="entypo-search" ></i>Filter</a>
                                                <a type="submit" class="btn btn-md btn-primary mt-2 ml-1 export" data-type="all_invoice_export">generate</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                    <div class="container-fluid  ml-1 mt-4">
                                        <form novalidate class="form-horizontal form-groups-bordered validate d-flex gap-1" method="post" id="cdr_filter_1">
                                            @csrf
                                            <input type="hidden" name="type" class="form-control"  value="Vendor"  />
                                            <div class="form-group customer_Account">
                                                <label class="control-label" for="field-1"> Vendor Name</label>
                                                <select class="form-control" id="bulk_AccountID" allowClear="true" name="AccountID" required>
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
                                                <select class="form-control" id="report" allowClear="true" name="report">
                                                    <option value="">Select</option>
                                                    <option value="Vendor-Summary">Vendor Summary</option>
                                                    <option value="Vendor-Hourly">Vendor Hourly</option>
                                                    <option value="Customer/Vendor-Report">Customer/Vendor Report</option>
                                                    <option value="Account-Manage">Account Manage</option>
                                                    <option value="Margin-Report">Margin Report</option>
                                                    <option value="Vendor-Negative-Report">Negative Report</option>
                                                </select>
                                            </div>
                                            <div class="form-group" style="max-width: 300px;">
                                                <label class="control-label small_label" for="field-1">Start Date</label>
                                                <div class="d-flex gap-1">
                                                    <input type="text" name="StartDate" class="form-control datepicker w-35" id="datepicker2"  data-date-format="yyyy-mm-dd " value="2021-04-16 14:45" data-enddate="2021-04-16 14:45"  />
                                                </div>
                                            </div>
                                            <div class="form-group" style="max-width: 300px;">
                                                <label class="col-md-4 control-label small_label" for="field-1" style="padding-left: 0px;">End Date</label>
                                                <div class="d-flex gap-1">
                                                    <input type="text" name="EndDate"
                                                    id="datepicker3" class="form-control datepicker"  data-date-format="yyyy-mm-dd" value="2021-04-16 14:45" data-enddate="2021-04-16 14:45" />
                                                </div>
                                            </div>
                                           
                                            <div class="form-group mt-4 d-flex">
                                                <input type="hidden" id="ActiveTab" name="ActiveTab" value="2">
                                                <a type="submit"  class="btn btn-primary btn-md btn-icon mt-2 " id="filter_data" ><i class="entypo-search" ></i>Filter</a>
                                                <a type="submit" class="btn btn-md btn-primary mt-2 ml-1  export" data-type="all_invoice_export">generate</a>
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
                                        <th>Report type</th>
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
            format: 'yyyy-mm-dd hh:ii:ss',
            //gotoCurrent: true,
           orientation: "bottom" // add this
        });
        $('#datepicker').datetimepicker('setDate', myDate);
        $('#datepicker1').datetimepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            format: 'yyyy-mm-dd hh:ii:ss',
            //gotoCurrent: true,
           orientation: "bottom" // add this
        });
        $('#datepicker1').datetimepicker('setDate', myDate);

        $('#datepicker2').datetimepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            format: 'yyyy-mm-dd hh:ii:ss',
            //gotoCurrent: true,
           orientation: "bottom" // add this
        });
        $('#datepicker2').datetimepicker('setDate', myDate);
        $('#datepicker3').datetimepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            format: 'yyyy-mm-dd hh:ii:ss',
            //gotoCurrent: true,
           orientation: "bottom" // add this
        });
        $('#datepicker3').datetimepicker('setDate', myDate);
       
      
        var $searchFilter = {};
        var TotalCall = 0;
        var TotalDuration = 0;
        var TotalCost = 0;
        
        $searchFilter.Account = $("#cdr_filter select[name='AccountID']").val();
        $searchFilter.Report = $("#cdr_filter select[name='report']").val();
        $searchFilter.ActiveTab = $("#cdr_filter input[name='ActiveTab']").val();

        var table = $('.data-table').DataTable({
            "bDestroy": true, // Destroy when resubmit form
            "bProcessing": true,
            "bServerSide": true,
            ajax:
                {
                "url": "{{ route('export.history')}}",
                "data" : function ( d ){
                    d.Account= $searchFilter.Account ?? "",
                    d.Report =   $searchFilter.Report ?? "",
                    d.ActiveTab =   $searchFilter.ActiveTab ?? ""
                },
                
            },
            columns: [
                {data:'id',name:'id'},
                {data:'client',name:'client'},
                {data:'report_type',name:'report_type'},
                {data:'status',name:'status'},
                {data:'created_at',name:'created_at'},
                {data:'action',name:'action', orderable: false, searchable: false},
            ]
        });
    });


        $(document).on("click", ".invoice_pdf",function() {
          
            var $searchFilter = {};
            var TotalCall = 0;
            var TotalDuration = 0;
            var TotalCost = 0;
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
                    "url": "{{ route('export.history')}}",
                    "data" : function ( d ){
                        d.Account= $searchFilter.Account ?? "",
                        d.Report =   $searchFilter.Report ?? "",
                        d.ActiveTab =   $searchFilter.ActiveTab ?? ""
                    },
                },
                columns: [
                    {data:'id',name:'id'},
                    {data:'client',name:'client'},
                    {data:'report_type',name:'report_type'},
                    {data:'status',name:'status'},
                    {data:'created_at',name:'created_at'},
                    {data:'action',name:'action', orderable: false, searchable: false},
                ]
            });
        });

        $('.export').on("click",function(e){
            var ref_this = $("ul.nav li.nav-item a.nav-link.active");
            if( ref_this.data('id') == 1){
            var data = $('#cdr_filter').serialize();
            } else{
                var data = $('#cdr_filter_1').serialize();
            }              e.preventDefault();
            $.ajax({
                type: "get",
                url: "{{url('/invoice_export')}}",
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                data : data,
                success: function(result) {
                    if(result.success == true){
                        $.notify(result.message, 'success');
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

         $(document).on("click", "#filter_data",function() {
            var $searchFilter = {};
            var TotalCall = 0;
            var TotalDuration = 0;
            var TotalCost = 0;
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
             
                $searchFilter.ActiveTab = $("#cdr_filter input[name='ActiveTab']").val();

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
    
                $searchFilter.ActiveTab = $("#cdr_filter_1 input[name='ActiveTab']").val();

            }
          
            var table = $('.data-table').DataTable({
                "bDestroy": true, // Destroy when resubmit form
                "bProcessing": true,
                "bServerSide": true,
                ajax:
                    {
                    "url": "{{ route('export.history')}}",
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
                    {data:'report_type',name:'report_type'},
                    {data:'status',name:'status'},
                    {data:'created_at',name:'created_at'},
                    {data:'action',name:'action', orderable: false, searchable: false},
                ]
            });
        });

</script>

@endsection
