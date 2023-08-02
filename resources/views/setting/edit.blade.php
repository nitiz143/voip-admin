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
                        <h3 class="card-title">Site Manager</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="{{ route('setting.store') }}" method="POST" id="settingform">
                            @csrf
                            <div class="card-body">
                                <input type="hidden" name="id" value="{{$setting->id}}">
                                <div class="form-group">
                                    <label for="select_protocol">Protocol</label>
                                    <select class="custom-select form-control" name="protocol" id="parent">
                                        <option value="1" {{"1" == $setting->protocol ? 'selected' : ''}}>FTP - File Transfer Protocol</option>
                                        <option value="2" {{"2" == $setting->protocol ? 'selected' : ''}}>SFTP - SSH File Transfer Protocol</option>
                                    
                                    </select>
                                </div>
                                
                                <div class="row">
                                    <div class="col-xl-8">
                                        <div class="form-group">
                                        <label for="host">Host </label>
                                        <input type="text" class="form-control" id="host" name="host" value="{{$setting->host}}">
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                        <label for="port">Port </label>
                                        <input type="text" class="form-control" id="port" name="port" value="{{$setting->port}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="username">Username </label>
                                    <input type="text" class="form-control" id="username" name="username" value="{{$setting->username}}" placeholder="Enter Name">
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" value="" placeholder="Enter Password" autocomplete="new-password">
                                </div>
                                <div class="form-group">
                                    <label for="csv_path">Csv Path</label>
                                    <input type="text" class="form-control" id="csv_path" name="csv_path" value="{{$setting->csv_path}}">
                                </div>
                                <div class="form-group">
                                    <label for="select_protocol">Version</label>
                                    <select class="custom-select form-control" name="version" id="version">
                                        <option value="">Select Version</option>
                                        <option value="1" {{"1" == $setting->version ? 'selected' : ''}}>V2.1.8.00</option>
                                        <option value="2" {{"2" == $setting->version ? 'selected' : ''}}>V2.1.8.05</option>
                                    </select>
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
                    $("#settingform")[0].reset();
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
        let formdata = $('#settingform').serialize();
        let url =   $('#settingform').attr('action');
        save(formdata,url);

    });




    </script>

@endsection
