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
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Users</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="{{ route('users.store') }}" method="POST" id="form">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <input type="hidden" name="id" value="{{$user->id}}">
                                </div>
                                <div class="form-group">
                                    <label for="name">Name </label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{$user->name}}" placeholder="Enter Name">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email </label>
                                    <input type="text" class="form-control" id="email" name="email"
                                        value="{{$user->email}}" placeholder="Enter email">
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" value=""
                                        placeholder="Password" autocomplete="new-password">
                                </div>
                                {{-- <div class="form-group" id="password">
                                    <label>Confirm Password'</label>
                                    <input id="password-confirm" type="password"
                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                        name="password_confirmation" autocomplete="new-password">
                                </div> --}}
                                <div class="form-group">
                                    <label for="select_role">Role Select</label>
                                    <select class="custom-select form-control-border border-width-2" id="select_role"
                                        name="role" disabled>
                                        <option selected disabled>--select role--</option>
                                        {{-- @foreach ( $roles as $key => $role ) --}}

                                        <!-------admin---------->
                                        @if(Auth::user()->role == 'Admin')
                                        <option value="Super Admin" {{"Super Admin"==$user->role ? 'selected' : ''}}>
                                            Super Admin </option>
                                        @endif
                                        <!-------Super Admin---------->
                                        @if(Auth::user()->role == 'Super Admin')
                                        <option value="NOC Admin" {{"NOC Admin"==$user->role ? 'selected' : ''}}>NOC
                                            Admin </option>
                                        <option value="Rate Admin" {{"Rate Admin"==$user->role ? 'selected' : ''}}> Rate
                                            Admin </option>
                                        <option value="Sales Admin" {{"Sales Admin"==$user->role ? 'selected' : ''}}>
                                            Sales Admin </option>
                                        <option value="Billing Admin" {{"Billing Admin"==$user->role ? 'selected' :
                                            ''}}> Billing Admin </option>
                                        <option value="NOC Executive" {{"NOC Executive"==$user->role ? 'selected' :
                                            ''}}>NOC Executive</option>
                                        <option value="Rate Executive" {{"Rate Executive"==$user->role ? 'selected' :
                                            ''}}>Rate Executive</option>
                                        <option value="Sales Executive" {{"Sales Executive"==$user->role ? 'selected' :
                                            ''}}>Sales Executive</option>
                                        <option value="Billing Executive" {{"Billing Executive"==$user->role ?
                                            'selected' : ''}}>Billing Executive</option>
                                        @endif
                                        <!-------NOC Admin---------->
                                        @if(Auth::user()->role == 'NOC Admin')
                                        <option value="NOC Executive" {{"NOC Executive"==$user->role ? 'selected' :
                                            ''}}>NOC Executive</option>
                                        @endif
                                        <!-------Rate Admin---------->
                                        @if(Auth::user()->role == 'Rate Admin')
                                        <option value="Rate Executive" {{"Rate Executive"==$user->role ? 'selected' :
                                            ''}}>Rate Executive</option>
                                        @endif
                                        <!-------Sales Admin---------->
                                        @if(Auth::user()->role == 'Sales Admin')
                                        <option value="Sales Executive" {{"Sales Executive"==$user->role ? 'selected' :
                                            ''}}>Sales Executive</option>
                                        @endif
                                        <!-------Billing Admin---------->
                                        @if(Auth::user()->role == 'Billing Admin')
                                        <option value="Billing Executive" {{"Billing Executive"==$user->role ?
                                            'selected' : ''}}>Billing Executive</option>
                                        @endif
                                        {{-- @endforeach --}}
                                    </select>
                                    @if (!empty($user->parent_id))
                                    @if( $user->role == 'NOC Executive'|| $user->role == 'Rate Executive' || $user->role
                                    == 'Sales Executive' || $user->role == 'Billing Executive')
                                    <div class="form-group" id="adminuser">
                                        <label for="select_role">Assign Admin</label>
                                        <select class="custom-select form-control-border border-width-2"
                                            name="parent_id" id="parent" disabled>
                                            @foreach($assigns as $assign)
                                            @if( $user->parent_id === $assign->id )
                                            <option value="{{$assign->id}}">{{$assign->name}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    @endif
                                    @endif

                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" id="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
@section('page_js')
<script>
    function save(formdata,url){
        $('#global-loader').show();
        $.ajax({
          data: formdata,
          url: url,
          type: "POST",
          dataType: 'json',
        //   cache:false,
        //   contentType: false,
        //   processData: false,
        //   headers: {
        //     'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        //   },
          success: function (resp) {
                $('#global-loader').hide();
                if(resp.success == false){
                    $.each(resp.errors, function(k, e) {
                        $.notify(e, 'error');
                    });
                }
                else{
                    $.notify(resp.message, 'success');
                    setTimeout(function(){
                        if(resp.redirect_url){
                            window.location.href = resp.redirect_url;
                        }
                    }, 1000);

                }
             }, error: function(r) {
                $('#global-loader').hide();
                $.each(r.responseJSON.errors, function(k, e) {
                    $.notify(e, 'error');
                });
                $('.blocker').hide();
            }
    });
    }
    $('#submit').click(function (e) {
        //alert();
        e.preventDefault();
        let formdata = $('#form').serialize();
        let url =   $('#form').attr('action');
        save(formdata,url);

    });
</script>
@endsection