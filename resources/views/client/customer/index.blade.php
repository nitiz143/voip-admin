@extends('layouts.dashboard')

@section('content')
<style>
    .not-allowed{
        cursor: not-allowed !important;
        pointer-events: all !important;
    }
    .nav-tabs .nav-link:hover {
        border-color: #e9ecef #e9ecef #dee2e6;
        background-color: gainsboro;
        color: #495057;
    }
    .nav-tabs .nav-link.active:hover{
        color: #495057;
        background-color: #fff;
        border-color: #dee2e6 #dee2e6 #fff;
    }


    </style>
<div class="content-wrapper mt-3">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 id="title"></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li>
                            <a href="{{route('home')}}"><i class="fa fa-home" aria-hidden="true"></i>Home</a>
                        </li>&nbsp;&nbsp;/&nbsp;&nbsp;
                        <li>
                            <a href="{{url('/client')}}">Account</a>
                        </li>&nbsp;&nbsp;/&nbsp;&nbsp;
                        <li>
                            <select id="customer_search" class="custom-select form-control "name="customer_search" style="width: 160px; background-color: #f4f6f9;
                            border: 0px; margin-top: -5px;  box-shadow: inherit;">
                                @if(!empty($clients))
                                    @foreach ($clients as $client)
                                        <option value="{{$client->id}}"{{$client->id == @request()->id ? 'selected' : ''}} >{{$client->company}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </li>
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
                                        <ul class="nav nav-tabs gap-1">
                                            <li class="nav nav-tabs" >
                                                <a href="" data-bs-toggle="nav-link" id="customer" data-name="Customer Rate"  class="nav-link   @if($customer->isEmpty()) not-allowed disabled @endif" >Customer Rate</a>
                                            </li>
                                            <li class="nav nav-tabs">
                                                <a href="#" data-bs-toggle="nav-link" id="Setting"  data-name="Settings" class="nav-link">Settings</a>
                                            </li>
                                            <li class="nav nav-tabs">
                                                <a href="#" data-bs-toggle="nav-link" id="Download"  data-name="Download Rate Sheet"  class="nav-link   @if($customer->isEmpty()) not-allowed disabled @endif" >Download Rate Sheet</a>
                                            </li>
                                            <li class="nav nav-tabs" >
                                                <a href="#" data-bs-toggle="nav-link" id="History"  data-name="History"  class="nav-link @if($customer->isEmpty()) not-allowed disabled @endif"   >History</a>
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
@if($customer->isEmpty())
    <script>
    $.notify("Please select any Trunk", 'info'); 
    </script>
@endif
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

    $(document).ready(function(){
        $('#customer_search').change(function(){
            var id = $(this).val();
            var vals='/client-customer/'+id;
             location.href = vals;
            
        });
    });
    </script>
@endsection
