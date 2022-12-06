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
<table class="table table-bordered datatable table-4" id="table-4">
    <thead>
        <tr>
            <th>Title</th>
            <th>Created Date</th>
            <th>Created by</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @if(!empty($downloads))
            @foreach ($downloads as $download)
            <tr>
                <td>{{$clients->company."($download->format)($download->effective)"}}</td>
                <td>{{$download->created_at}}</td>
                <td>{{$download->uname}}</td>
                <td><a href="javascript:;" data-id="{{$download->id}}" id="View"  class="btn btn-default btn-sm View"><i class="fa fa-eye"></i></a>
                 <a  href="" class="btn btn-success btn-sm btn-icon icon-left"><i class="entypo-down"></i>Download</a></td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>

<script>
       var role = $('#table-4').DataTable({});

       $("#View").click(function(e){
            e.preventDefault();
           
            var id = $(this).data('id');
           
        $.ajax({
            url:"{{route('history_detail',":id")}}",
            method:"get",
            data:{"id":id , "client_id": "{{request()->id}}"},
            success:function(data){
                $("#articlesModal").modal('show');
                $('.modal-data').html(data);
            }
        });
       });
</script>
