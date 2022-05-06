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
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                        <h3 class="card-title">Create Users</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="{{ route('users.store') }}" method="POST" id="form">
                            @csrf
                        <div class="card-body">
                            <div class="form-group">
                            <label for="name">Name </label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
                            </div>
                            <div class="form-group">
                            <label for="email">Email </label>
                            <input type="text" class="form-control" id="email" name="email" value="" placeholder="Enter email">
                            </div>
                            <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" value="" placeholder="Password" autocomplete="new-password">
                            </div>
                            {{-- <div class="form-group" id="password">
                                <label>Confirm Password'</label>
                                <input id="password-confirm" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation"  autocomplete="new-password">
                            </div> --}}
                            <div class="form-group">
                                <label for="select_role">Role Select</label>
                                <select class="custom-select form-control-border border-width-2" id="select_role" name="role" >
                                  <option selected disabled>--select role--</option>

                                        @if ( Auth::user()->role  == 'Admin')
                                            <option value="Super Admin"> Super Admin </option>
                                        @endif
                                        @if( Auth::user()->role  == 'Super Admin')
                                        <option value="NOC Admin">NOC Admin </option>
                                        <option value="Rate Admin"> Rate Admin </option>
                                        <option value="Sales Admin"> Sales Admin </option>
                                        <option value="Billing Admin"> Billing Admin </option>
                                        @endif
                                        @if( Auth::user()->role  == 'NOC Admin')
                                        <option value="NOC Executive">NOC Executive</option>
                                        @endif
                                        @if( Auth::user()->role  == 'Rate Admin')
                                        <option value="Rate Executive">Rate Executive</option>
                                        @endif
                                        @if( Auth::user()->role  == 'Sales Admin')
                                        <option value="Sales Executive">Sales Executive</option>
                                        @endif
                                        @if( Auth::user()->role  == 'Billing Admin')
                                        <option value="Billing Executive">Billing Executive</option>
                                        @endif

                                </select>
                            </div>

                            @if( Auth::user()->role  == 'Super Admin')
                                <div class="form-group">
                                    <select class="custom-select form-control-border border-width-2" name="parent_id" id="parent">
                                    </select>
                                </div>
                            @endif

                            {{-- <div class="form-group d-none">
                                <label for="select_role">Billing Executive</label>
                                <select class="custom-select form-control-border border-width-2" id="select_role" name="role" >
                                </select>
                            </div> --}}
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
                    $("#form")[0].reset();
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
            // $(document).ready(function () {
            //     //  var billing = ;
            //     $("#select_role").change(function () {
            //         var val = $(this).val();
            //         if (val == "NOC Admin") {
            //             $("#parent_id").html("<option value='"+{{Auth::user()->id }}+"'>NOC Executive</option>");
            //         } else if (val == "Rate Admin") {
            //             $("#parent_id").html("<option value='"+{{Auth::user()->id }}+"'>Rate Executive</option>");
            //         } else if (val == "Sales Admin") {
            //             $("#parent_id").html("<option value='"+{{Auth::user()->id }}+"'>Sales Executive</option>");
            //         } else if (val == "Billing Admin") {
            //             $("#parent_id").html("<option value='"+{{Auth::user()->id }}+"'></option>");
            //         }
            //     });
            // });

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $(document).ready(function(){

                // Fetch all records
                $('#select_role').change(function() {
                    //  alert();
                    // AJAX GET request
                    $.ajax({
                    url: "{{ route('getUsers')}}",
                    type: 'get',
                    // dataType: 'json',
                    success: function(response){

                            $("#parent").html(response);


// console.log(response);
                    }
                    });
                });
            });


    </script>

@endsection
