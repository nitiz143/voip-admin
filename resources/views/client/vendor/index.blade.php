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
                        <li>
                            <a href="{{route('home')}}"><i class="fa fa-home" aria-hidden="true"></i>Home</a>
                        </li>&nbsp;&nbsp;/&nbsp;&nbsp;
                        <li>
                            <a href="{{url('/client')}}">Account</a>
                        </li>&nbsp;&nbsp;/&nbsp;&nbsp;
                        <li>
                            <select id="vendors_search" class=" custom-select form-control "name="vendors_search" style="width: 160px; background-color: #f4f6f9;
                            border: 0px; margin-top: -5px; box-shadow: inherit;"> 
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
                                            <li class="nav nav-tabs">
                                                <a href="" data-bs-toggle="nav-link" id="vendor" data-name="Vendor Rate"  class="nav-link  @if($vendor->isEmpty()) not-allowed disabled @endif">Vendor Rate</a>
                                            </li>
                                            <li class="nav nav-tabs">
                                                <a href="#" data-bs-toggle="nav-link" id="download"  data-name="Vender Rate Download" class="nav-link  @if($vendor->isEmpty()) not-allowed  disabled @endif">Vender Rate Download</a>
                                            </li>
                                            <li class="nav nav-tabs">
                                                <a href="#" data-bs-toggle="nav-link" id="setting"  data-name="Settings"  class="nav-link">Settings</a>
                                            </li>
                                            <li class="nav nav-tabs">
                                                <a href="#" data-bs-toggle="nav-link" id="blocking"  data-name="Blocking"  class="nav-link  @if($vendor->isEmpty()) not-allowed  disabled @endif">Blocking</a>
                                            </li>
                                            <li class="nav nav-tabs">
                                                <a href="#" data-bs-toggle="nav-link" id="preference"  data-name="Preference"  class="nav-link  @if($vendor->isEmpty()) not-allowed  disabled @endif">Preference</a>
                                            </li>
                                            <li class="nav nav-tabs">
                                                <a href="#" data-bs-toggle="nav-link" id="history"  data-name="Vendor Rate History"  class="nav-link @if($vendor->isEmpty()) not-allowed  disabled @endif">Vendor Rate History</a>
                                            </li>
                                            <li class="ml-5">
                                            </li>
                                            <li class=" float-end ml-5  mt-1">
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

@if($vendor->isEmpty())
    <script>
    $.notify("Please select any Trunk", 'info'); 
    </script>
@endif
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
                    var $searchFilter = {};
                    var checked='';
                    $("#vendor-rate-search").submit(function(e) {
                        e.preventDefault();
                        $searchFilter.Trunk = $("#vendor-rate-search select[name='Trunk']").val();
                        $searchFilter.Code = $("#vendor-rate-search input[name='Code']").val();
                        $searchFilter.Country = $("#vendor-rate-search select[name='Country']").val();
                        $searchFilter.Description =  $("#vendor-rate-search input[name='Description']").val();
                        $searchFilter.Timezones = $("#vendor-rate-search select[name='Timezones']").val();

                        if(typeof $searchFilter.Trunk  == 'undefined' || $searchFilter.Trunk == '' ){
                            $.notify("Please Select a Trunk", "error");
                            return false;
                        }
                        $("#form-preference").find("input[name='Trunk']").val($searchFilter.Trunk);
                        $(".preference-row").show();
                        var id = "{{request()->id}}";
                        $("#ToolTables_table-4_0").attr('href',"{{ url('vendor/preference/xlsx') }}"+'?id='+id+'?Trunk='+$searchFilter.Trunk);
                        $("#ToolTables_table-4_1").attr('href',"{{ url('vendor/preference/csv') }}"+'?id='+id+'?Trunk='+$searchFilter.Trunk);
                        data_table = $("#table-4").dataTable({
                            "bDestroy": true, // Destroy when resubmit form
                            "bProcessing": true,
                            "bServerSide": true,
                            "ajax": {
                                "url" : "{{route('ajax_datagrid_preference',"request()->id")}}",
                                "data" : function ( d ){
                                    d.id = "{{request()->id}}",
                                    d.Trunk= $searchFilter.Trunk,
                                    d.Code= $searchFilter.Code,
                                    d.Description= $searchFilter.Description,
                                    d.Country = $searchFilter.Country,
                                    d.Timezones= $searchFilter.Timezones
                                },
                            },
                            "iDisplayLength": parseInt('50'),
                            //  "sDom": "<'row'<'col-xs-6 col-left '<'#selectcheckbox.col-xs-1'>'l><'col-xs-6 col-right'<'export-data'T>f>r>t<'row'<'col-xs-6 col-left'i><'col-xs-6 col-right'p>>",
                            // "aaSorting": [[1, "asc"]],
                            "aoColumns":
                                [
                                    {data:'checkbox',name:'checkbox',orderable: false, searchable: false},
                                    {data:'codes',name:'codes'},
                                    {data:'preference',name:'preference'},
                                    {data:'destination',name:'destination'},
                                    {data:'action',name:'action', orderable: false, searchable: false},
                                ],
                        });
                        return false;
                    });
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
    $(document).ready(function(){
        $('#vendors_search').change(function(){
            var name = $('#setting').data('name');
            var id = $(this).val();
            var vals='/client-vendor/'+id;
             location.href = vals;
            
        });
    });
    </script>
@endsection
