@extends('layouts.dashboard')
@section('content')
<div class="content-wrapper mt-3">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">

                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <a href="" class="btn btn-primary mb-4 float-right w-10" id="Filter">Filter</a>
                        <a href="{{ route('cdr.create') }}" class="btn btn-primary mb-4 ml-2 float-right w-10" id="createzoneModal">Import</a>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="panel panel-primary">
                                <div class=" tab-menu-heading">
                                    <div class="tabs-menu1">
                                        <!-- Tabs -->
                                        <ul class="nav nav-tabs gap-1">
                                            <li class="nav nav-tabs ">
                                                <a href="{{url('/Customer_cdr_show')}}" data-bs-toggle="nav-link" id="customer" data-name="customer"  class="nav-link ">Customer CDR</a>
                                            </li>
                                            <li class="nav nav-tabs">
                                                <a href="{{url('/Vendor_cdr_show')}}" data-bs-toggle="nav-link" id="vendor"  data-name="vender" class="nav-link active">Vendor CDR</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="panel-body tabs-menu-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h1 class="card-title ">Vendor CDR</h1>
                                                  
                                                </div>
                                                
                                                <!-- /.card-header -->
                                                <div class="card-body">
                                                    <table class="table table-bordered data-table">
                                                        <thead>
                                                            <tr>
                                                                <th>ID</th>
                                                                <th>Account Name</th>
                                                                <th>Connect Time</th>
                                                                <th>Disconnect Time</th>
                                                                <th>Billed Duration (sec)</th>
                                                                <th>Cost</th>
                                                                <th>Callere</th>
                                                                <th>Calleee</th>
                                                                {{-- <th>Trunk</th> --}}
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                        
                                                        </tbody>

                                                        
                                                    </table>
                                                </div><!-- /.card-body -->
                                            </div><!-- /.card -->
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
                                                        <form novalidate class="form-horizontal form-groups-bordered validate"  id="cdr_filter">
                                                            <div class="form-group">
                                                                <label class="control-label small_label" for="field-1">Start Date</label>
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <input type="text" name="StartDate" class="form-control datepicker"  data-date-format="yyyy-mm-dd" value="2023-03-03" data-enddate="2023-03-03" />
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
                                                                        <input type="text" name="EndDate" class="form-control datepicker"  data-date-format="yyyy-mm-dd" value="2023-03-03" data-enddate="2023-03-03" />
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <input type="text" name="EndTime" data-minute-step="5" data-show-meridian="false" data-default-time="23:59:59" value="23:59:59" data-show-seconds="true" data-template="dropdown" class="form-control timepicker">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- <div class="form-group">
                                                                <label for="field-1" class="control-label">Currency</label>
                                                                <select class="form-control" name="CurrencyID"></select>
                                                            </div> --}}
                                                            <div class="form-group">
                                                                <label class="control-label" for="field-1">Gateway</label>
                                                                <select class="form-control" id="GatewayID" name="GatewayID"></select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label" for="field-1">Account</label>
                                                                <select class="form-control" id="bulk_AccountID" allowClear="true" name="AccountID">  
                                                                @if(!empty($Accounts))
                                                                    <option value="">Select</option>
                                                                    @foreach ( $Accounts as $Account )
                                                                        <option value="{{$Account->id}}">{{$Account->firstname}}{{$Account->lastname}}</option>
                                                                    @endforeach
                                                                @endif</select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label" for="field-1">CLI</label>
                                                                <input type="text" name="CLI" class="form-control mid_fld "  value=""  />
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label" for="field-1">CLD</label>
                                                                <input type="text" name="CLD" class="form-control mid_fld  "  value=""  />
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="field-1" class="control-label">Show</label>
                                                                <select class="form-control" id="bulk_AccountID" allowClear="true" name="zerovaluecost"></select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label" for="field-1">Prefix</label>
                                                                <input type="text" name="area_prefix" class="form-control mid_fld "  value=""  />
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label" for="field-1">Trunk</label>
                                                                <select class="form-control" id="bulk_AccountID" allowClear="true" name="Trunk"></select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label">Account Tag</label>
                                                                <input class="form-control tags" name="tag" type="text" >
                                                            </div>
                                                            <div class="form-group">
                                                                <br/>
                                                                <input type="hidden" name="ResellerOwner" value="0">
                                                                <button type="submit" class="btn btn-primary btn-md btn-icon icon-left" id="filter_form">
                                                                    <i class="entypo-search"></i>
                                                                    Search
                                                                </button>
                                                            </div>
                                                        </form>
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
        var $searchFilter = {};
        $("#cdr_filter").submit(function(e) {
            e.preventDefault();
            var starttime = $("#cdr_filter input[name='StartTime']").val();
            if(starttime =='00:00:01'){
                starttime = '00:00:00';
            }

            $searchFilter.Trunk = $("#cdr_filter select[name='Trunk']").val();
            $searchFilter.Account = $("#cdr_filter select[name='AccountID']").val();
            $searchFilter.Gateway = $("#cdr_filter select[name='GatewayID']").val();
            $searchFilter.zerovaluecost = $("#cdr_filter select[name='zerovaluecost']").val();
            $searchFilter.Cli = $("#cdr_filter input[name='CLI']").val();
            $searchFilter.Cld = $("#cdr_filter input[name='CLD']").val();
            $searchFilter.Prefix = $("#cdr_filter input[name='area_prefix']").val();
            $searchFilter.Tag = $("#cdr_filter input[name='tag']").val();
            $searchFilter.StartDate = $("#cdr_filter input[name='StartDate']").val();
            $searchFilter.EndDate = $("#cdr_filter input[name='EndDate']").val();
            $searchFilter.starttime = $("#cdr_filter input[name='StartTime']").val();
            // $searchFilter.End_time = $("#cdr_filter input[name='EndTime']").val();
            if(typeof $searchFilter.StartDate  == 'undefined' || $searchFilter.StartDate.trim() == ''){
                $.notify("Please Select a Start date", "Error", toastr_opts);
                return false;
            }
            if(typeof $searchFilter.EndDate  == 'undefined' || $searchFilter.EndDate.trim() == ''){
                $.notify("Please Select a End date", "Error", toastr_opts);
                return false;
            }
            $searchFilter.StartDate += ' '+starttime;
            $searchFilter.EndDate += ' '+$("#cdr_filter [name='EndTime']").val();
            var table = $('.data-table').DataTable({
                    "bDestroy": true, // Destroy when resubmit form
                    "bProcessing": true,
                    "bServerSide": true,
                    "ajax": {
                        "url" : "{{ route('vendorCdr.index') }}",
                        "data" : function ( d ){
                            d.Trunk= $searchFilter.Trunk,
                            d.Account= $searchFilter.Account,
                            d.Gateway = $searchFilter.Country,
                            d.zerovaluecost= $searchFilter.zerovaluecost,
                            d.Cli= $searchFilter.Cli,
                            d.Cld= $searchFilter.Cld,
                            d.Prefix = $searchFilter.Prefix,
                            d.Tag= $searchFilter.Tag,
                            d.StartDate= $searchFilter.StartDate,
                            d.EndDate= $searchFilter.EndDate
                        },
                    },
                    "aaSorting": [[1, "asc"]],
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
                        {data:'callere164',name:'callere164'},
                        {data:'calleee164',name:'calleee164'},
                        {data:'action',name:'action', orderable: false, searchable: false},
                    ],
            });
            $('#FilterModel').modal('hide');
        });
    });

    $(document).on('click','.callhistoryForm',function(e){
        // $("#ajaxModel").modal();
        var id = $(this).data('id')
        $.ajax({
           type:'get',
           url:"{{ route('getCallhistory') }}",
           data:{id,id},
           success:function(data){
              console.log(data);
              $('#callForm').html(data);
              $("#ajaxModel").modal('show');
           }
        });
    });
    $(function () {
        $(".datepicker").datepicker({ 
                autoclose: true, 
                todayHighlight: true
        });
    });




</script>

@endsection
