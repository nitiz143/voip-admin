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

                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h1 class="card-title ">Call History</h1>
                            <a href="{{ route('call.create') }}" class="btn btn-primary mb-4 float-right w-10" id="createzoneModal">Import</a>
                        </div>
                        {{-- <div class="form-inline d-flex justify-content-end mt-2 mr-4">
                            <div class="input-group" data-widget="sidebar-search">
                                <input class="form-control search" type="text" value="" placeholder="Search">
                            </div>
                        </div> --}}
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered data-table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Caller_id</th>
                                    <th>Callere164</th>
                                    <th>Calleraccesse164</th>
                                    <th>Calleee164</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>

                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>

                <div class="modal fade" id="ajaxModel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content" id="callForm">

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

    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('call.index') }}",
        columns: [
            {data:'id',name:'id'},
            {data:'caller_id',name:'caller_id'},
            {data:'callere164',name:'callere164'},
            {data:'calleraccesse164',name:'calleraccesse164'},
            {data:'calleee164',name:'calleee164'},

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
