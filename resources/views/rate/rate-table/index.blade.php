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
                            <h1 class="card-title">Rate Table</h1>
                            <a href="#" class="btn btn-primary mb-4  float-right" id="createTableModal">Create</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered data-table" style="width: 100%">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Currency</th>
                                    <th>Trunk</th>
                                    <th>CodeCheck</th>
                                    <th>Last Updated</th>
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

<div class="modal fade" id="add-new-rate-table">
  <div class="modal-dialog">
      <div class="modal-content" id="tableFormDiv">
          
      </div>
  </div>
</div>
@endsection
@section('page_js')
<script>
$(".modal").on("hidden.bs.modal", function(){
    $("#tableFormDiv").html("");
});
var table = $('.data-table').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('rate-table') }}",
    columns: [
        {data: 'name', name: 'name'},
        {data: 'currency', name: 'currency'},
        {data: 'trunkId', name: 'trunkId'},
        {data: 'codeDeckId', name: 'codeDeckId'},
        {data: 'updated_at', name: 'updated_at'},
        {data: 'action', name: 'action', orderable: false, searchable: false},
    ]
});

$(document).on('click','#createTableModal', function(e){
  e.preventDefault();
  $.ajax({
    url: "{{ route('rate-table.create') }}",
    cache: false,
    success: function(html){
      $("#tableFormDiv").append(html);
      $('#add-new-rate-table').modal('show');
    }
  });
});


$(document).on('click','.editTable', function(e){
  e.preventDefault();
  var id = $(this).data('id');
  $.ajax({
    url: "{{ route('rate-table.create') }}",
    data:{'id':id},
    cache: false,
    success: function(html){
      $("#tableFormDiv").append(html);
      $('#add-new-rate-table').modal('show');
    }
  });
});
$(document).on('click','#formSave', function(e){
  e.preventDefault();
  let formData = new FormData($('#add-new-form')[0]);
  let url =   $('#add-new-form').attr('action');
  $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      url:url,
      method: 'post',
      dataType: 'json',
      data: formData,
      contentType: false,
      cache: false,
      processData:false,
      success: function (resp) {
          if(resp.success == false){
              $.each(resp.errors, function(k, e) {
                  $.notify(e, 'error');
              });
          }
          else{
              $.notify(resp.message, 'success');
              setTimeout(function(){
                  if(resp.redirect_url){
                      window.location.href = resp.redirect_url;
                  }else{
                    location.reload();
                  }
              }, 1000);
          }
      }, error: function(r) {
          $.each(r.responseJSON.errors, function(k, e) {
              $.notify(e, 'error');
          });
          $('.blocker').hide();
      }
  });
});

$(document).on('click', '.Delete', function () {
        let id = $(this).data('id');
        var token = "{{ csrf_token() }}";
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })

        swalWithBootstrapButtons
            .fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('rate-table.delete') }}",
                        type: 'delete', // replaced from put
                        dataType: "JSON",
                        data: {
                            "id": id ,// method and token not needed in data
                            "_token": token,
                        },
                        success: function (resp) {
                          if(resp.success == false){
                              $.each(resp.errors, function(k, e) {
                                  $.notify(e, 'error');
                              });
                          }
                          else{
                              $.notify(resp.message, 'success');
                              setTimeout(function(){
                                  if(resp.redirect_url){
                                      window.location.href = resp.redirect_url;
                                  }else{
                                    location.reload();
                                  }
                              }, 1000);
                          }
                        },
                        error: function (xhr) {
                            console.log(xhr.responseText); // this line will save you tons of hours while debugging
                            // do something here because of error
                        }
                    });
                    swalWithBootstrapButtons.fire(
                       ' deleted!',
                       ' your file deleted',
                        'success'
                    )
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                       'cancelled',
                       ' your imaginary file safe',
                        'error'
                    )

                }
            });
    });



</script>
@endsection
