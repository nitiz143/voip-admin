@extends('layouts.dashboard')
@section('content')
<div class="content-wrapper mt-3" >
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
        </div>
    </section>
    @if($errors->any())
        <div class="container-fluid">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{$errors->first()}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @endif
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h1 class="card-title ">Export History</h1>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Client</th>
                                        <th>Invoice No</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
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
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('export.history',request()->type)}}",
        columns: [
            {data:'id',name:'id'},
            {data:'client',name:'client'},
            {data:'Invoice_no',name:'Invoice_no'},
            {data:'status',name:'status'},
            {data:'created_at',name:'created_at'},
            {data:'action',name:'action', orderable: false, searchable: false},
        ]
    });

   
</script>

@endsection
