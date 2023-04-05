<div class="row">
    <div class="export-data ">
        <div class="DTTT btn-group float-end mb-2 mt-2">
            <a href="{{route('Vendor-history_export_xlsx',@request()->id)}}" data-value="xlsx"class="btn btn-white save-collection btn-sm" style="border: 1px solid gray;" id="ToolTables_table-4_0">
                <undefined>EXCEL</undefined>
            </a>
            <a  href="{{route('Vendor-history_export_csv',@request()->id)}}" class="btn btn-white save-collection btn-sm" style="border: 1px solid gray;" id="ToolTables_table-4_1">
                <undefined>CSV</undefined>
            </a>
        </div>
    </div>
</div>
<div class="modal fade " id="articlesModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-data">
            </div>
        </div>
    </div>
</div>
<table class="table table-bordered datatable" id="table-4">
    <thead>
        <tr>
            <th>Title</th>
            <th>Created Date</th>
            <th>Created by</th>
            <th>Type</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
       
    </tbody>
</table>
<script>
   var role = $('#table-4').DataTable({
        serverSide: true,
        processing: true,
        "ajax": {
            "url" : "{{route('ajax_datagrid_vendorHistory',"request()->id")}}",
            "data" : function ( d ){
                d.id = "{{request()->id}}"
            },
        },
        "aoColumns": [{
                data: 'title',
                name:'title'
            },
            {
                data: 'created_at',
                name: 'created_at'
            },
            {
                data: 'created_by',
                name: 'created_by'
            },
            {
                data: 'type',
                name: 'type'
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false
            },
        ]
    });
    
    $(document).on('click', '.View', function () {
        var id = $(this).data('id');
        $.ajax({
            url:"{{route('vendor_history_detail',":id")}}",
            method:"get",
            data:{"id":id , "client_id": "{{request()->id}}"},
            success:function(data){
                $("#articlesModal").modal('show');
                $('.modal-data').html(data);
            }
        });
    });
</script>

