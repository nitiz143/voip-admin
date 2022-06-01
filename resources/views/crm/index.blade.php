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
                            <h1 class="card-title ">CRM</h1>
                            <a href="{{ route('crm.create') }}" class="btn btn-primary mb-4 float-right w-10" id="createzoneModal">create</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <!-- {{-- <th>lead_owner</th> --}} -->
                                        <th>company</th>
                                        <th>firstname</th>
                                        <th>lastname</th>
                                        <th>email</th>
                                        <th>phone</th>
                                        <th>created_at</th>
                                        <th>updated_at</th>
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
        ajax: "{{ route('crm.index') }}",
        columns: [
            {data:'id',name:'id'},
            // {data:'lead_owner',name:'lead_owner'},
            {data:'company',name:'company'},
            {data:'firstname',name:'firstname'},
            {data:'lastname',name:'lastname'},
            {data:'email',name:'email'},
            {data:'phone',name:'phone'},
            {data:'created_at',name:'created_at'},
            {data:'updated_at',name:'updated_at'},
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
                        url: "{{ route('crm.destroy', ": id ") }}",
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
