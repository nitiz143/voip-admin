<div class="row">
    <div class="export-data ">
        <div class="DTTT btn-group float-end mb-2 mt-2">
            <a href="" data-value="xlsx"class="btn btn-white save-collection btn-sm" style="border: 1px solid gray;" id="ToolTables_table-4_0">
                <undefined>EXCEL</undefined>
            </a>
            <a  href="" class="btn btn-white save-collection btn-sm" style="border: 1px solid gray;" id="ToolTables_table-4_1">
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
    });
</script>
