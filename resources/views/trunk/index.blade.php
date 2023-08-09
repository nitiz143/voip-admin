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
                    <div class="card">
                        <div class="card-header row">
                            <div class="form-group col-6 ">
                                <div class="form-check form-switch">
                                    <label for="TrunkStatus">Status</label>
                                    <input class="form-check-input ml-3" type="checkbox" id="TrunkStatus"
                                        name="TrunkStatus" value="1" checked>
                                </div>
                            </div>
                            <div class="col-6">
                                <p class="text-right">
                                    <a href="#" data-action="showAddModal" data-type="trunk"  class="btn btn-primary add-trunk" id="showAddModal">
                                        <i class="entypo-plus"></i>
                                        Add New
                                    </a>
                                </p>
                            </div>
                            <div class="col-xs-6">
                                <div class="export-data">
                                    <div class="DTTT btn-group float-end mb-2 mt-2">
                                        <a href="{{route('trunks_xlsx')}}" data-value="xlsx"class="btn btn-white save-collection btn-sm" style="border: 1px solid gray;" id="ToolTables_table-4_0">
                                            <undefined>EXCEL</undefined>
                                        </a>
                                        <a  href="{{route('trunks_csv')}}" class="btn btn-white save-collection btn-sm" style="border: 1px solid gray;" id="ToolTables_table-4_1">
                                            <undefined>CSV</undefined>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- /.card-header -->
                        <div class="card-body">
                            
                            <table class="table table-bordered datatable" id="table-4">
                                <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Rate Prefix</th>
                                    <th>Area Prefix</th>
                                    <th>Prefix</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                        <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade in" id="add-new-modal-trunk" >
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-trunk-add" method="post" action="{{ route('trunk.store') }}">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Add New Trunk</h4>
                    <button type="button" class="close" data-dismiss="modal" id="close" aria-hidden="true" value="">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="field-1" class="control-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="Title" value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="field-1" class="col-sm-3 control-label">Rate Prefix</label>
                                <input type="text" class="form-control" name="RatePrefix" data-mask="999999999999" id="RatePrefix" placeholder="Rate Prefix" value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="field-1" class="col-sm-3 control-label">Area Prefix</label>
                                <input type="text" class="form-control" data-mask="999999999999" name="AreaPrefix" id="AreaPrefix" placeholder="Area Prefix" value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="field-1" class="col-sm-3 control-label">Prefix</label>
                                <input type="text" class="form-control" data-mask="999999999999" name="Prefix" placeholder="Prefix" id="Prefix" value="">
                            </div>
                        </div>
                    </div>
                    <div class="form-switch pl-0">
                        <label for="field-1" class="control-label">Active</label>                                    
                        <input class="form-check-input ml-3" type="checkbox" id="Status"
                                name="Status" value="1" checked>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="TrunkID" id="TrunkID" value="">
                        <button type="submit" id="trunk-update" class="save btn btn-primary btn-sm btn-icon icon-left" data-loading-text="Loading..." style="visibility: visible;" value="">
                            <i class="entypo-floppy"></i>
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('page_js')
<script>
    $(document).ready( function () {
        data_table =  $('#table-4').DataTable({
                ajax:
                    {
                    "url": "{{ route('trunks.index') }}",
                    "data": function (data) {
                        if($('#TrunkStatus').is(":checked")){
                            data.status =1
                        }else{
                            data.status =0 
                        }
                    }
                },
                processing: true,
                serverSide: true,
                columns: [
                    { data: 'title', name: 'title' },
                    { data: 'rate_prefix', name: 'rate_prefix' },
                    { data: 'area_prefix', name: 'area_prefix' },
                    { data: 'prefix', name: 'prefix' },
                    { data: 'action', name: 'action' },
                ]
            });
        });

    $('#TrunkStatus').change(function(){
        data_table.draw();
        if($('#TrunkStatus').is(":checked")){
                var status_active =1
                $("#ToolTables_table-4_0").attr('href',"{{ url('trunks/xlsx') }}"+'?status='+status_active);
                $("#ToolTables_table-4_1").attr('href',"{{ url('trunks/csv') }}"+'?status='+status_active);
        }else{
                var status_inactive =0 
                $("#ToolTables_table-4_0").attr('href',"{{ url('trunks/xlsx') }}"+'?status='+status_inactive);
                $("#ToolTables_table-4_1").attr('href',"{{ url('trunks/csv') }}"+'?status='+status_inactive);
        }
    });

    $(document).ready( function () {
        if($('#TrunkStatus').is(":checked")){
                var status_active =1
                $("#ToolTables_table-4_0").attr('href',"{{ url('trunks/xlsx') }}"+'?status='+status_active);
                $("#ToolTables_table-4_1").attr('href',"{{ url('trunks/csv') }}"+'?status='+status_active);
        }else{
                var status_inactive =0 
                $("#ToolTables_table-4_0").attr('href',"{{ url('trunks/xlsx') }}"+'?status='+status_inactive);
                $("#ToolTables_table-4_1").attr('href',"{{ url('trunks/csv') }}"+'?status='+status_inactive);
        }
        });

    $('#showAddModal').click(function (e) {
        e.preventDefault();
        $('#add-new-modal-trunk').modal('show');
    });

    $('.close').click(function (e) {
        e.preventDefault();
        $('#add-new-modal-trunk').modal('hide');
    });

    function save(formdata,url){
        $('#global-loader').show();
        $.ajax({
            data: formdata,
            url: url,
            type: "POST",
            // dataType: 'json',
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
                    $('#add-new-modal-trunk').modal('hide');
                    setTimeout(function () {
                        window.location.reload();
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
    $("body").delegate("#form-trunk-add", "submit", function(e) {
        e.preventDefault();
        var form = $('#form-trunk-add'),
        url = form.attr('action');
        var formData = new FormData(this);
        save(formData,url);

    });

    $(document).on('click', '.Edit', function () {

        let id = $(this).data('id');

        $.ajax({
            url: "{{ route('trunk.edit', ":id") }}",
            type: 'get', // replaced from put
            data: {
                "id": id // method and token not needed in data
            },
            success: function (data)
            {
                $('#TrunkID').val(data.id);
                $('#title').val(data.title);
                $('#RatePrefix').val(data.rate_prefix);
                $('#AreaPrefix').val(data.area_prefix);
                if(data.status == 1){
                    $('#Status').prop('checked', true);
                }else{
                    $('#Status').prop('checked', false);
                }
                
                $('#Prefix').val(data.prefix);
                $('#add-new-modal-trunk').modal('show');
            },
            error: function(xhr) {
            console.log(xhr.responseText); // this line will save you tons of hours while debugging
        }
        });
    });

   
    
</script>
@endsection
