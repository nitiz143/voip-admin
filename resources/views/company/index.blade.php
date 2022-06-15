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
                        <div class="card-header">
                            <h1 class="card-title">Company</h1>
                            <a href="{{ route('company.create') }}" class="btn btn-primary mb-4  float-right" id="createModal">Create</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered data-table" style="width: 100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>VAT</th>
                                    <th>Company Name</th>
                                    <th>Default Trunk Prefix</th>
                                    <th>Last Trunk Prefix</th>
                                    <th>Action</th>
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

@endsection
@section('page_js')
<script>
var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('company.index') }}",
        columns: [
            {data:'id',name:'id'},
            // {data:'lead_owner',name:'lead_owner'},
            {data:'company_name',name:'company_name'},
            {data:'vat',name:'vat'},
            {data:'default_trunk_prefix',name:'default_trunk_prefix'},
            {data:'last_trunk_prefix',name:'last_trunk_prefix'},
            {data:'action',name:'action', orderable: false, searchable: false},
        ]
    });

</script>
@endsection
