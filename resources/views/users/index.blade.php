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
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h1 class="card-title col-10">Users</h1>
                                <a href="{{ route('users.create') }}" class="btn btn-primary mb-4  w-10 col-2" id="createzoneModal">Create</a>
                            </div>
                            <div class="form-inline d-flex justify-content-end mt-2 mr-4">
                                <div class="input-group" data-widget="sidebar-search">
                                  <input class="form-control search" type="text" value="{{@request()->name}}" placeholder="Search">
                                </div>
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
                                    <tbody class="Data">
                                        @foreach($users as $user)
                                        <tr>
                                            <td>{{$user->id}}</td>
                                            <td>{{$user->name}}</td>
                                            <td>{{$user->email}}</td>
                                            <td>{{$user->role}}</td>
                                            <td><a href="{{route('users.edit',$user->id)}} " class="delete btn btn-primary btn-sm Edit"  data-id ="{{$user->id}}">Edit</a>&nbsp;&nbsp;<a href="javascript:void(0)" class="delete btn btn-danger btn-sm Delete"  data-id ="{{$user->id}}">Delete</a></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end mr-4">
                                {!! $users->links() !!}
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

$(".Delete").click(function(){
    var id = $(this).data("id");
    var token = "{{ csrf_token() }}";
if(confirm("Are You sure want to delete !") == true){

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
        },
            error: function (resp) {
                console.log('Error:', resp);
            }
    });
}else{
    location.reload();
}

});

var path = "{{ route('autocomplete') }}";
// var route_id = "{{request()->route_id}}";
$("input.search").keyup(function(){
    $('.Data').html('');
    $.ajax({
        data: {'name':this.value},
        url: path,
        type: "GET",
        // headers: {
        // 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        // },
        success: function (resp) {
            $('.Data').html(resp);
        }
    });
});
</script>
@endsection
