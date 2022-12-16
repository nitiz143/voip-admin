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
                                                    <button class="btn btn-dark btn-sm btn-icon icon-left" onclick="location.href='{{route('rate-upload')}}'">
                                                        <i class="fa fa-upload"></i>
                                                        Upload Rates
                                                    </button>
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
        $('#vendor').removeClass('active');
        $('#setting').addClass('active');
        $('#download').removeClass('active');
        $('#history').removeClass('active');
        $('#blocking').removeClass('active');
        $('#preference').removeClass('active');
        var name = $('#setting').data('name');
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
