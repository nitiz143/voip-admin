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
                        <a href="{{ route('cdr.create') }}" class="btn btn-primary mb-4 float-right w-10" id="createzoneModal">Import</a>
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

    
    $("#btnModeClose").on("click", function (e) {
        e.preventDefault();
        $("#ajaxModel").modal("hide");
    });
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('vendorCdr.index') }}",
        columns: [
            {data:'id',name:'id'},
            {data:'account',name:'account'},
            {data:'Connect_time',name:'Connect_time'},
            {data:'Disconnect_time',name:'Disconnect_time'},
            {data:'billing_duration',name:'billing_duration'},
            {data:'Cost',name:'Cost'},
            {data:'callere164',name:'callere164'},
            {data:'calleee164',name:'calleee164'},
            // {data:'Trunk',name:'Trunk'},
            {data:'action',name:'action', orderable: false, searchable: false},
        ]
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





</script>

@endsection
