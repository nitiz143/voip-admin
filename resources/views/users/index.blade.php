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


        <section class="content  ">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title">Users</h2>
                                <a href="{{ route('users.create') }}" class="btn btn-primary mb-4 float-right w-10" id="createzoneModal">new</a>

                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table class="table table-bordered ">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        {{-- @if ( Auth::user()->role  == 'Admin') --}}
                                        @foreach($users as $user)
                                        <tr>
                                            <td>{{$user->id}}</td>
                                            <td>{{$user->name}}</td>
                                            <td>{{$user->email}}</td>
                                            <td>{{$user->role}}</td>

                                            <td><a href="{{route('users.edit',$user->id)}} " class="delete btn btn-primary btn-sm Edit"  data-id ="{{$user->id}}">Edit</a>&nbsp;&nbsp;<a href="javascript:void(0)" class="delete btn btn-danger btn-sm Delete"  data-id ="{{$user->id}}">Delete</a></td>

                                        </tr>

                                        @endforeach
                                        {{-- @endif --}}
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer clearfix">
                                <ul class="pagination pagination-sm m-0 float-right">
                                    <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                                </ul>
                            </div>
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

$(".Delete").click(function(){
    var id = $(this).data("id");
    var token = "{{ csrf_token() }}";

    $.ajax(
    {
        url: "{{ route('users.destroy', ": id ") }}",
        type: 'delete',
         dataType: "JSON",
        data: {
            "id": id,
            "_token": token,
        },
        success: function (resp){
            $.notify(resp.message, 'success');
            setTimeout(function () {
                location.reload();
                 }, 1000);
        }
    });

});
</script>
@endsection
