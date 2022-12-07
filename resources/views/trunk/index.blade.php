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
                <div class="modal-header">
                    <h4 class="modal-title">Add New Trunk</h4>
                    <button type="button" class="close" data-dismiss="modal" id="close" aria-hidden="true" value="">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="field-1" class="control-label">Title</label>
                                <input type="text" class="form-control" id="Trunk" name="Trunk" placeholder="Title" value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="field-1" class="col-sm-3 control-label">Rate Prefix</label>
                                <input type="text" class="form-control" name="RatePrefix" data-mask="999999999999" placeholder="Rate Prefix" value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="field-1" class="col-sm-3 control-label">Area Prefix</label>
                                <input type="text" class="form-control" data-mask="999999999999" name="AreaPrefix" placeholder="Area Prefix" value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="field-1" class="col-sm-3 control-label">Prefix</label>
                                <input type="text" class="form-control" data-mask="999999999999" name="Prefix" placeholder="Prefix" value="">
                            </div>
                        </div>
                    </div>
                    <div class="form-switch pl-0">
                        <label for="field-1" class="control-label">Active</label>                                    
                        <input class="form-check-input ml-3" type="checkbox" id="Status"
                                name="Status" value="1" checked>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="TrunkID" value="">
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
    });

    $('#showAddModal').click(function (e) {
        e.preventDefault();
        $('#add-new-modal-trunk').modal('show');
    });

    $('.close').click(function (e) {
        e.preventDefault();
        $('#add-new-modal-trunk').modal('hide');
    });

</script>
@endsection
