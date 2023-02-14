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
                       
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered data-table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Callere</th>
                                    <th>Calleee</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>

                        <!-- /.card-body -->
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
        ajax: "{{ route('call.index') }}",
        columns: [
            {data:'id',name:'id'},
            {data:'account',name:'account'},
            {data:'callere164',name:'callere164'},
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
