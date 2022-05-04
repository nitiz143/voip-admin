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
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Import CSV</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

            <section class="content">
                <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-6">


                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                            <h3 class="card-title">Import CSV</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                        <form action="{{ route('call-history.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
               
                                <div class="form-group">
                                <label for="exampleInputFile">File input</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                    <input type="file" name="file" class="custom-file-input" id="exampleInputFile" required>
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                    </div>
                                    <div class="input-group-append">
                                    <span class="input-group-text">Upload</span>
                                    </div>
                                </div>
                                </div>
                               
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                         </form>
                        </div>
                        <!-- /.card -->

                    </div>
                </div>
                </div>
            </section>


 </div>

@endsection