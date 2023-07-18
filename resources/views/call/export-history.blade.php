@extends('layouts.dashboard')
@section('content')
<div class="content-wrapper mt-3" >
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">

                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <a href="" class="btn btn-primary mb-4 float-right w-10" id="Filter">Filter</a>
                    </ol>
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
<div class="modal" id="FilterModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filter</h5>
                <button type="button" id="filterClose" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" >
                <form novalidate class="form-horizontal form-groups-bordered validate" method="post" id="cdr_filter">
                    <input type="hidden" name="type" class="form-control"  value="Customer"  />
                    <div class="form-group">
                        <label class="control-label small_label" for="field-1">Start Date</label>
                        <div class="row">
                            <div class="col-sm-6">
                                <input type="text" name="StartDate" class="form-control datepicker" id="datepicker"  data-date-format="yyyy-mm-dd" value="2023-03-03" data-enddate="2023-03-03" />
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="StartTime" data-minute-step="5" data-show-meridian="false" data-default-time="00:00:00" value="00:00:00" data-show-seconds="true" data-template="dropdown" class="form-control timepicker">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label small_label" for="field-1" style="padding-left: 0px;">End Date</label>
                        <div class="row">
                            <div class="col-sm-6">
                                <input type="text" name="EndDate" 
                                id="datepicker1" class="form-control datepicker"  data-date-format="yyyy-mm-dd" value="2023-03-03" data-enddate="2023-03-03" />
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="EndTime" data-minute-step="5" data-show-meridian="false" data-default-time="23:59:59" value="23:59:59" data-show-seconds="true" data-template="dropdown" class="form-control timepicker">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="field-1">Customer List</label>
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
                
                    <div class="form-group">
                        <a type="submit" class="btn btn-primary  mr-2 w-10 export" data-type="all_invoice_export">Invoice generate</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
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


    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
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
