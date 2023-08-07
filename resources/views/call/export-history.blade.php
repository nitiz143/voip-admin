@extends('layouts.dashboard')
@section('content')
<div class="content-wrapper mt-3" >
    <section class="content-header">
        <div class="container-fluid">
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
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h1 class="card-title ">Export History</h1>
                        </div>
                        <div class="container-fluid  ml-2 mt-4">
                            <form novalidate class="form-horizontal form-groups-bordered validate d-flex gap-1" method="post" id="cdr_filter">
                                @csrf
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
                                    <label class="control-label" for="field-1"> Account List</label>
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
                                <div class="form-group">
                                    <a type="submit" class="btn btn-sm btn-primary  mt-4 ml-2 w-10 export" data-type="all_invoice_export">Invoice generate</a>
                                    <a type="submit"  class="btn btn-primary btn-sm btn-icon  mt-2 ml-2 " id="filter_data" ><i class="entypo-search" ></i>Filter</a>
                                </div>
                            </form>
                        </div>
                        <div class="card-body">
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
        $('#datepicker').datepicker();
        $('#datepicker').datepicker('setDate', myDate);
        $('#datepicker1').datepicker();
        $('#datepicker1').datepicker('setDate', myDate);
        $('.timepicker').datetimepicker({
            format: 'HH:mm:ss'
        });
    });



    var table = $('.data-table').DataTable({
        "bDestroy": true, // Destroy when resubmit form
        "bProcessing": true,
        "bServerSide": true,
        ajax:
            {
            "url": "{{ route('export.history')}}",
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

        $(document).on("click", "#filter_data",function() {
            var $searchFilter = {};
            var TotalCall = 0;
            var TotalDuration = 0;
            var TotalCost = 0;

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
                        d.EndDate = $searchFilter.EndDate ?? ""
                        d.Report =   $searchFilter.Report ?? ""
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
            e.preventDefault();
             $.ajax({
                type: "get",
                url: "{{url('/invoice_export')}}",
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                data : $('#cdr_filter').serialize(),
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

</script>

@endsection
