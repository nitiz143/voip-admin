@extends('layouts.dashboard')

@section('content')
 <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Import CSV</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('Customer_cdr_show.index')}}">Call-history</a></li>
                        <li class="breadcrumb-item active">Import CSV</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
{{dd( Storage::get('voip/testing.xlsx'));}}
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Import CSV</h3>
                        </div>
                        <form action="{{ route('cdr.store') }}" method="POST" id="form" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="select_protocol">Version</label>
                                    <select class="custom-select form-control" name="version" id="version">
                                        <option value="">Select Version</option>
                                        <option value="1">V2.1.8.00</option>
                                        <option value="2">V2.1.8.05</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">File input</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="file" class="custom-file-input" id="exampleInputFile">
                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
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
        //   dataType: 'json',
          cache:false,
          contentType: false,
          processData: false,
          headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
          },
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
    $("body").delegate("#form", "submit", function(e) {
        e.preventDefault();
        var form = $('#form'),
        url = form.attr('action');
        var formData = new FormData(this);
        save(formData,url);

    });
    </script>

@endsection
