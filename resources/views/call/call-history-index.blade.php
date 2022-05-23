@extends('layouts.dashboard')

@section('content')
<div class="content-wrapper mt-3" style="width:max-content">
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
                    <div class="">
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
                                <table class="table table-bordered data-table" >
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Caller_id</th>
                                        <th>Callere164</th>
                                        <th>Calleraccesse164</th>
                                        <th>Calleee164</th>
                                        <th>Calleeaccesse164</th>
                                        <th>Callerip</th>
                                        <th>Callergatewayh323id</th>
                                        <th>Callerproductid</th>
                                        <th>Callertogatewaye164</th>
                                        <th>Calleeip</th>
                                        <th>Calleegatewayh323id</th>
                                        <th>Calleeproductid</th>
                                        <th>Calleetogatewaye164</th>
                                        <th>Billingmode</th>
                                        <th>Calllevel</th>
                                        <th>Agentfeetime</th>
                                        <th>Starttime</th>
                                        <th>Stoptime</th>
                                        <th>Pdd</th>
                                        <th>Holdtime</th>
                                        <th>Feeprefix</th>
                                        <th>Feetime</th>
                                        <th>Fee</th>
                                        <th>Suitefee</th>
                                        <th>Suitefeetime</th>
                                        <th>Incomefee</th>
                                        <th>Customername</th>
                                        <th>Agentfeeprefix</th>
                                        <th>Agentfee</th>
                                        <th>Agentsuitefee</th>
                                        <th>Agentsuitefeetime</th>
                                        <th>Agentaccount</th>
                                        <th>Agentname</th>
                                        <th>Flowno</th>
                                        <th>Softswitchdn</th>
                                        <th>Enddirection</th>
                                        <th>Endreason</th>
                                        <th>Calleebilling</th>
                                        <th>Cdrlevel</th>
                                        <th>Subcdr_id</th>
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





$(document).on('click', '.Delete', function () {
        let id = $(this).data('id');
        var token = "{{ csrf_token() }}";
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })

        swalWithBootstrapButtons
            .fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('call.destroy', ": id ") }}",
                        type: 'delete', // replaced from put
                        dataType: "JSON",
                        data: {
                            "id": id,// method and token not needed in data
                            "_token": token,
                        },
                        success: function (response) {
                            console.log(response)
                            if (response.success == true) { //YAYA
                                table.draw();
                            } else { //Fail check?
                                timeOutId = setTimeout(ajaxFn, 20000); //set the timeout again

                            }
                            // location.reload();
                        },
                        error: function (xhr) {
                            console.log(xhr.responseText); // this line will save you tons of hours while debugging
                            // do something here because of error
                        }
                    });
                    swalWithBootstrapButtons.fire(
                       ' deleted!',
                       ' your file deleted',
                        'success'
                    )
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                       'cancelled',
                       ' your imaginary file is safe',
                        'error'
                    )

                }
            });
    });

</script>

@endsection
