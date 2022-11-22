@extends('layouts.dashboard')

@section('content')

<div class="content-wrapper mt-3">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 id="title"></h1>
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
                                        <ul class="nav nav-tabs gap-5">
                                            <li class="nav nav-tabs">
                                                <a href="" data-bs-toggle="nav-link" id="customer" data-name="Customer Rate"  class="nav-link">Customer Rate</a>
                                            </li>
                                            <li class="nav nav-tabs">
                                                <a href="#" data-bs-toggle="nav-link" id="Setting"  data-name="Settings" class="nav-link">Settings</a>
                                            </li>
                                            <li class="nav nav-tabs">
                                                <a href="#" data-bs-toggle="nav-link" id="Download"  data-name="Download Rate Sheet"  class="nav-link">Download Rate Sheet</a>
                                            </li>
                                            <li class="nav nav-tabs">
                                                <a href="#" data-bs-toggle="nav-link" id="History"  data-name="History"  class="nav-link">History</a>
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
        $("#customer").click(function(e){
            e.preventDefault();
            var name = $(this).data('name');
            $('#tab5').show();
            $('#customer').addClass('active');
            $('#Setting').removeClass('active');
            $('#Download').removeClass('active');
            $('#History').removeClass('active');
            $.ajax({
                url:"{{route('customers')}}",
                method:"get",
                data:{'name':name , 'id':"{{request()->id}}"},
                success:function(data){
                    $('#tab5').html(data);
                    $('#title').text(name);
                }
             });
        });
        $("#Setting").click(function(e){
            e.preventDefault();
            var name = $(this).data('name');
            $('#tab5').show();
            $('#Setting').addClass('active');
            $('#customer').removeClass('active');
            $('#Download').removeClass('active');
            $('#History').removeClass('active');
            $.ajax({
                url:"{{route('customers')}}",
                method:"get",
                data:{'name':name , 'id':"{{request()->id}}"},
                success:function(data){
                    $('#tab5').html(data);
                    $('#title').text(name);
                }
             });
        });
        $("#Download").click(function(e){
            e.preventDefault();
            var name = $(this).data('name');
            $('#tab5').show();
            $('#Download').addClass('active');
            $('#customer').removeClass('active');
            $('#History').removeClass('active');
            $('#Setting').removeClass('active');
            $.ajax({
                url:"{{route('customers')}}",
                method:"get",
                data:{'name':name , 'id':"{{request()->id}}"},
                success:function(data){
                    $('#tab5').html(data);
                    $('#title').text(name);
                }
             });

        });

        $("#History").click(function(e){
            e.preventDefault();
            var name = $(this).data('name');
            $('#tab5').show();
            $('#History').addClass('active');
            $('#customer').removeClass('active');
            $('#Download').removeClass('active');
            $('#Setting').removeClass('active');
            $.ajax({
                url:"{{route('customers')}}",
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
        $('#customer').removeClass('active');
        $('#Setting').addClass('active');
        $('#Download').removeClass('active');
        $('#History').removeClass('active');
        var name = $('#Setting').data('name');
        $.ajax({
            url:"{{route('customers')}}",
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
