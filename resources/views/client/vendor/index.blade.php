@extends('layouts.dashboard')

@section('content')
<style>
    .form-check {
        padding-left: 0.25rem !important;
    }
    .file-input__input {
        width: 0.1px;
        height: 0.1px;
        opacity: 0;
        overflow: hidden;
        position: absolute;
        z-index: -1;
    }

    .file-input__label {
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 600;
        color: #fff;
        font-size: 14px;
        padding: 10px 12px;
        background-color: black;
        box-shadow: 0px 0px 2px rgba(0, 0, 0, 0.25);
    }

    .file-input__label svg {
        height: 16px;
        margin-right: 4px;
    }
</style>
<div class="content-wrapper mt-3">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2 id="title"></h2>
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
                        <div class="card-body">
                            <div class="panel panel-primary">
                                <div class=" tab-menu-heading">
                                    <div class="tabs-menu1">
                                        <!-- Tabs -->
                                        <ul class="nav nav-tabs gap-3">
                                            <li class="nav nav-tabs">
                                                <a href="" data-bs-toggle="nav-link" id="vendor" data-name="Vendor Rate"  class="nav-link">Vendor Rate</a>
                                            </li>
                                            <li class="nav nav-tabs">
                                                <a href="#" data-bs-toggle="nav-link" id="download"  data-name="Vender Rate Download" class="nav-link">Vender Rate Download</a>
                                            </li>
                                            <li class="nav nav-tabs">
                                                <a href="#" data-bs-toggle="nav-link" id="setting"  data-name="Settings"  class="nav-link">Settings</a>
                                            </li>
                                            <li class="nav nav-tabs">
                                                <a href="#" data-bs-toggle="nav-link" id="blocking"  data-name="Blocking"  class="nav-link">Blocking</a>
                                            </li>
                                            <li class="nav nav-tabs">
                                                <a href="#" data-bs-toggle="nav-link" id="preference"  data-name="Preference"  class="nav-link">Preference</a>
                                            </li>
                                            <li class="nav nav-tabs">
                                                <a href="#" data-bs-toggle="nav-link" id="history"  data-name="Vendor Rate History"  class="nav-link">Vendor Rate History</a>
                                            </li>
                                            <li class="float-right ml-5">
                                                <div class="file-input">
                                                    <input type="file"name="csv_file"id="file-input"class="file-input__input"/>
                                                    <label class="file-input__label" for="file-input">
                                                        <svg aria-hidden="true" focusable="false"data-prefix="fas"data-icon="upload"
                                                        class="svg-inline--fa fa-upload fa-w-16"role="img" xmlns="http://www.w3.org/2000/svg"viewBox="0 0 512 512">
                                                            <path fill="currentColor"d="M296 384h-80c-13.3 0-24-10.7-24-24V192h-87.7c-17.8 0-26.7-21.5-14.1-34.1L242.3 5.7c7.5-7.5 19.8-7.5 27.3 0l152.2 152.2c12.6 12.6 3.7 34.1-14.1 34.1H320v168c0 13.3-10.7 24-24 24zm216-8v112c0 13.3-10.7 24-24 24H24c-13.3 0-24-10.7-24-24V376c0-13.3 10.7-24 24-24h136v8c0 30.9 25.1 56 56 56h80c30.9 0 56-25.1 56-56v-8h136c13.3 0 24 10.7 24 24zm-124 88c0-11-9-20-20-20s-20 9-20 20 9 20 20 20 20-9 20-20zm64 0c0-11-9-20-20-20s-20 9-20 20 9 20 20 20 20-9 20-20z"></path>
                                                        </svg>
                                                        <span>Upload Rates</span>
                                                    </label>
                                                </div>
                                            </li>
                                        </ul>

                                    </div>
                                </div>
                                <div class="panel-body tabs-menu-body">
                                    <div class="tab-content">
                                        <div class="tab-pane" id="tab5">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
@section('page_js')
<script>
    $(document).ready(function(){
        $("#vendor").click(function(e){
            e.preventDefault();
            var name = $(this).data('name');
            $('#tab5').show();
            $('#vendor').addClass('active');
            $('#setting').removeClass('active');
            $('#download').removeClass('active');
            $('#history').removeClass('active');
            $('#blocking').removeClass('active');
            $('#preference').removeClass('active');
            $.ajax({
                url:"{{route('Vendors')}}",
                method:"get",
                data:{'name':name , 'id':"{{request()->id}}"},
                success:function(data){
                    $('#tab5').html(data);
                    $('#title').text(name);
                }
             });
        });
        $("#setting").click(function(e){
            e.preventDefault();
            var name = $(this).data('name');
            $('#tab5').show();
            $('#setting').addClass('active');
            $('#vendor').removeClass('active');
            $('#download').removeClass('active');
            $('#history').removeClass('active');
            $('#blocking').removeClass('active');
            $('#preference').removeClass('active');
            $.ajax({
                url:"{{route('Vendors')}}",
                method:"get",
                data:{'name':name , 'id':"{{request()->id}}"},
                success:function(data){
                    $('#tab5').html(data);
                    $('#title').text(name);
                }
             });
        });
        $("#download").click(function(e){
            e.preventDefault();
            var name = $(this).data('name');
            $('#tab5').show();
            $('#download').addClass('active');
            $('#vendor').removeClass('active');
            $('#history').removeClass('active');
            $('#setting').removeClass('active');
            $('#blocking').removeClass('active');
            $('#preference').removeClass('active');
            $.ajax({
                url:"{{route('Vendors')}}",
                method:"get",
                data:{'name':name , 'id':"{{request()->id}}"},
                success:function(data){
                    $('#tab5').html(data);
                    $('#title').text(name);
                }
             });
        });

        $("#history").click(function(e){
            e.preventDefault();
            var name = $(this).data('name');
            $('#tab5').show();
            $('#history').addClass('active');
            $('#vendor').removeClass('active');
            $('#download').removeClass('active');
            $('#setting').removeClass('active');
            $('#blocking').removeClass('active');
            $('#preference').removeClass('active');
            $.ajax({
                url:"{{route('Vendors')}}",
                method:"get",
                data:{'name':name , 'id':"{{request()->id}}"},
                success:function(data){
                    $('#tab5').html(data);
                    $('#title').text(name);
                }
             });
        });

        $("#preference").click(function(e){
            e.preventDefault();
            var name = $(this).data('name');
            $('#tab5').show();
            $('#download').removeClass('active');
            $('#vendor').removeClass('active');
            $('#history').removeClass('active');
            $('#setting').removeClass('active');
            $('#blocking').removeClass('active');
            $('#preference').addClass('active');
            $.ajax({
                url:"{{route('Vendors')}}",
                method:"get",
                data:{'name':name , 'id':"{{request()->id}}"},
                success:function(data){
                    $('#tab5').html(data);
                    $('#title').text(name);
                }
             });

        });

        $("#blocking").click(function(e){
            e.preventDefault();
            var name = $(this).data('name');
            $('#tab5').show();
            $('#history').removeClass('active');
            $('#vendor').removeClass('active');
            $('#download').removeClass('active');
            $('#setting').removeClass('active');
            $('#blocking').addClass('active');
            $('#preference').removeClass('active');
            $.ajax({
                url:"{{route('Vendors')}}",
                method:"get",
                data:{'name':name , 'id':"{{request()->id}}"},
                success:function(data){
                    $('#tab5').html(data);
                    $('#title').text(name);
                }
             });

        });

    });
    $(document).ready(function(){

        $('#tab5').show();
        $('#vendor').addClass('active');
        $('#setting').removeClass('active');
        $('#download').removeClass('active');
        $('#history').removeClass('active');
        $('#blocking').removeClass('active');
        $('#preference').removeClass('active');
        var name = $('#vendor').data('name');
        $.ajax({
                url:"{{route('Vendors')}}",
                method:"get",
                data:{'name':name , 'id':"{{request()->id}}"},
                success:function(data){
                    $('#tab5').html(data);
                    $('#title').text(name);
                }
             });
    });
    </script>
@endsection
