@extends('layouts.dashboard')

@section('content')
<div class="content-wrapper mt-3">
    <section class="content-header">
        <div class="container">
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
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h1 class="card-title">Company</h1>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered data-table" style="width: 100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Company Name</th>
                                    <th>VAT</th>
                                    <th>Default Trunk Prefix</th>
                                    <th>Last Trunk Prefix</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ( $users as $user)
                                    <tr>
                                        <td>{{$user->id}}</td>
                                        <td>{{$user->company_name}}</td>
                                        <td>{{$user->vat}}</td>
                                        <td>{{$user->default_trunk_prefix}}</td>
                                        <td>{{$user->last_trunk_prefix}}</td>
                                        <td><a href="{{route('company.edit',$user->id)}}" class="delete btn btn-primary btn-sm Edit"  data-id ="'.$row->id.'">Edit</a></td>
                                    </tr>
                                    @endforeach

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
// var table = $('.data-table').DataTable({
//         processing: true,
//         serverSide: true,
//         ajax: "{{ route('company.index') }}",
//         columns: [
//             {data:'id',name:'id'},
//             // {data:'lead_owner',name:'lead_owner'},
//             {data:'company_name',name:'company_name'},
//             {data:'vat',name:'vat'},
//             {data:'default_trunk_prefix',name:'default_trunk_prefix'},
//             {data:'last_trunk_prefix',name:'last_trunk_prefix'},
//             {data:'action',name:'action', orderable: false, searchable: false},
//         ]
//     });

    $(document).on('click', '.companyDelete', function () {
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
                        url: "{{ route('company.destroy', ": id ") }}",
                        type: 'delete', // replaced from put
                        dataType: "JSON",
                        data: {
                            "id": id ,// method and token not needed in data
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
                       ' your imaginary file safe',
                        'error'
                    )

                }
            });
    });
</script>
@endsection
