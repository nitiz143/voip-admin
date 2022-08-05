@extends('layouts.dashboard')

@section('content')
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color:black;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice{
        color: black;

    }
    .select2-container--default .select2-search--inline .select2-search__field:focus.select2-search__field{
        border: 0px;

    }
    .select2-container--default .select2-search--inline .select2-search__field {
        height: 25px;
    }
    .swal2-cancel{
       margin-right:20px;
    }
    </style>
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
                                <h1 class="card-title">Cron Job</h1>
                                <a href="" class="btn btn-primary mb-4  float-right pull-right" data-bs-toggle="modal" data-bs-target="#CronModal" id="createCronModal">Add New</a>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered border text-nowrap mb-0 w-100" id="table">
                                        <thead>
                                            <tr>
                                                <th>PID</th>
                                                <th>Title</th>
                                                <th>Running Since</th>
                                                {{-- <th>Last Run Time</th>
                                                <th>Next Run Time</th> --}}
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- /.card-body -->
                        </div>
            <!-- /.card -->
                    </div>
                </div>
            </div>
            <div class="modal fade" id="CronModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Cron Job</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{route('cron.store')}}" method="POST" id="cronForm">
                            @csrf
                            <div class="modal-body">
                                <input type="hidden" value="" name="id" id="id">
                                <div class="form-row">
                                    <div class="col-xl-12 mb-3">
                                        <label>Job title</label>
                                        <input type="text" name="job_title" id="job_title" class="form-control" >
                                    </div>
                                </div>
                                <div class="form-row ">
                                    <div class="col-xl-12 mb-3">
                                        <label>Cron type</label>
                                        <select class="form-control cron_type" name="cron_type" id="cron_type">
                                            <option value="">Select type</option>
                                            <option value="Download VOS SFTP File">Download VOS SFTP File</option>
                                            <option value="Active job Cron Email">Active job Cron Email</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row" id="gate" style="display:none">
                                    <div class="col-xl-12 mb-3">
                                        <label>Gateway</label>
                                        <select class="form-control" name="gateway" id="gateway">
                                            <option value="">Select gateway</option>
                                            <option value=""></option>
                                            <option value=""></option>
                                        </select>

                                    </div>
                                </div>
                            <div id="display" style="display:none">
                                <div class="form-row">
                                    <div class="col-xl-6 mb-3">
                                        <label>Alert Active Email Time</label>
                                        <input type="text" name="Alert" id="Alert" class="form-control">
                                    </div>
                                </div>
                            </div>
                                <div id="data" style="display:none">
                                    <div class="form-row">
                                        <div class="col-xl-6 mb-3">
                                            <label>Max File Download Limit</label>
                                            <input type="text" name="download_limit" id="download" class="form-control">
                                        </div>
                                        <div class="col-xl-6 mb-3">
                                            <label>Threshold Time(Minute)</label>
                                            <input type="text" name="threshold" id="threshold" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-xl-6 mb-3">
                                        <label>Success Email</label>
                                        <input type="email" name="success_email" id="success_email"class="form-control">
                                    </div>
                                    <div class="col-xl-6 mb-3">
                                        <label>Error Email</label>
                                        <input type="email" name="error_email" id="error_email" class="form-control">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-xl-6 mb-3">
                                        <label>Job Time</label>
                                        <select class="form-control cron" name="job_time" id="job_time">
                                            <option value="">Select Runtime</option>
                                            {{-- <option value="Second">Second</option> --}}
                                            <option value="everyMinute">everyMinute</option>
                                            <option value="hourly">hourly</option>
                                            <option value="daily">daily</option>
                                            <option value="monthly">monthly</option>
                                        </select>
                                    </div> 
                                    <div class="col-xl-6 mb-3  job_intervel">
                                        <label>Job Interval</label>
                                        <select class="form-control intervel" name="job_intervel" id="job_intervel">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-xl-6 mb-3">
                                        <label>Job Day</label><br>
                                        <select class="form-control js-example-basic-multiple" name="job_day[]"  id="job_day" data-placeholder="Choose day" multiple="multiple">
                                            <option value="Mon">Monday</option>
                                            <option value="Tue">Tuesday</option>
                                            <option value="Wedn">Wednesday</option>
                                            <option value="Thu">Thursday</option>
                                            <option value="Fri">Friday</option>
                                            <option value="Sat">Saturday</option>
                                            <option value="Sun">Sunday</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-xl-6 mb-3 mb-3">
                                        <label>Status</label><br>
                                        <select class="form-control" name="status" id="status">
                                            <option value="">Select status</option>
                                            <option value="0">Active</option>
                                            <option value="1">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                <button type="submit" id="save" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
</div>

@endsection
@section('page_js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
     var role = $('#table').DataTable({
     ajax: "{{route('cron.index')}}",
     serverSide: true,
     processing: true,
     aaSorting: [
         [0, "desc"]
     ],
     columns: [{
             data: 'id',
         },
         {
             data: 'job_title',
             name: 'job_title'
         },
         {
             data: 'start_time',
             name: 'start_time'
         },
         {
             data: 'action',
             name: 'action',
             searchable: false,
             orderable: false
         },
     ]
});

$('#createCronModal').click(function (e) {
    e.preventDefault();
    var val = $(this).val();
    if (val == 'Download VOS SFTP File') {
        $("#gate").show();
        $("#data").show();
        $("#display").hide();
    }
    if (val == 'Active job Cron Email') {
        $("#gate").hide();
        $("#data").hide();
        $("#display").show();
    }
    if (val == '') {
        $("#gate").hide();
        $("#data").hide();
        $("#display").hide();
    }
    $('#id').val('');
    $('#job_title').val('');
    $('#cron_type').val('');
    $('#gateway').val('');
    $('#Alert').val('');
    $('#download').val('');
    $('#threshold').val('');
    $('#success_email').val('');
    $('#error_email').val('');
    $('#job_time').val('');
    $('#job_intervel').val('');
    $('#job_day').val('').trigger('change');
    $('#start_time').val('');
    $('#status').val('');
    $('#save').text('{{ __('save') }}');
    $('#exampleModalLabel').text('{{ __('Cron job') }}');
    $('#CronModal').modal('show');

 });


$('.cron').on('change', function() {
 var val = $(this).val();
    if(!val){
        $('.job_intervel').addClass('d-none');
    }else{
        $('.job_intervel').removeClass('d-none');
    }
    if (val == "everyMinute") {
        var selectAge = document.getElementById("job_intervel");
        var contents;

        for (let i = 1; i <=60; i++) {
        contents += "<option value="+i+">" + i + "  MINUTE</option>";
        }

        selectAge.innerHTML = contents;
    }
    if (val == "hourly") {
        var selectAge = document.getElementById("job_intervel");
        var contents;

        for (let i = 1; i <=24 ; i++) {
        contents += "<option value="+i+">" + i + "  HOUR</option>";
        }

        selectAge.innerHTML = contents;
    }
    if (val == "daily") {
        var selectAge = document.getElementById("job_intervel");
        var contents;

        for (let i = 1; i <=31 ; i++) {
        contents += "<option value="+i+">" + i + "  DAY</option>";
        }

        selectAge.innerHTML = contents;
    }
    if (val == "monthly") {
        var selectAge = document.getElementById("job_intervel");
        var contents;

        for (let i = 1; i <=12 ; i++) {
        contents += "<option value="+i+">" + i + "  MONTH</option>";
        }

        selectAge.innerHTML = contents;
    }
});


$('.cron_type').on('change', function() {
    var val = $(this).val();
    if (val == 'Download VOS SFTP File') {
        $("#gate").show();
        $("#data").show();
        $("#display").hide();
        $('#Alert').val('').trigger('change');
   }
   if (val == 'Active job Cron Email') {
    $("#gate").hide();
    $("#data").hide();
    $("#display").show();
    $('#gateway').val('').trigger('change');
    $('#download').val('').trigger('change');
    $('#threshold').val('').trigger('change');
   }
   if (val == '') {
    $("#gate").hide();
    $("#data").hide();
    $("#display").hide();
    $('#Alert').val('').trigger('change');
    $('#gateway').val('').trigger('change');
    $('#download').val('').trigger('change');
    $('#threshold').val('').trigger('change');
   }
});

$(document).ready(function() {
    $('.js-example-basic-multiple').select2({
    width: '100%',

    placeholder: "Select an Option",
    allowClear: true
  });
});

$(document).on('click', '.Edit', function (e) {
        e.preventDefault();
        $('#save').text('{{ __('Update') }}');
        $('#exampleModalLabel').text('{{ __('Update Cron job') }}');
        $('#global-loader').show();

        let id = $(this).data('id');

        $.ajax({
            url: "{{ route('cron.edit', ":id") }}",
            type: 'get', // replaced from put
            data: {
                "id": id // method and token not needed in data
            },
            success: function (data)
            {
                $('#CronModal').modal('show');
                if (data.job_time == "everyMinute") {
                    var selectAge = document.getElementById("job_intervel");
                    var contents;
                    for (let i = 1; i <=60; i++) {
                        let selected = '';
                        if(i == data.job_intervel){
                            selected = 'selected';
                        }
                        contents += "<option value="+i+" "+selected+">" + i + " MINUTE</option>";
                    }

                    selectAge.innerHTML = contents;
                }
                if (data.job_time == "hourly") {
                    var selectAge = document.getElementById("job_intervel");
                    var contents;
                    for (let i = 1; i <=24 ; i++) {
                        let selected = '';
                        if(i == data.job_intervel){
                            selected = 'selected';
                        }
                        contents += "<option value="+i+" "+selected+">" +i + " HOUR</option>";
                    }

                    selectAge.innerHTML = contents;
                }
                if (data.job_time == "daily") {
                    var selectAge = document.getElementById("job_intervel");
                    var contents;
                    for (let i = 1; i <=31 ; i++) {
                        let selected = '';
                        if(i == data.job_intervel){
                            selected = 'selected';
                        }
                        contents += "<option value="+i+" "+selected+">" + i+ " DAY</option>";
                    }


                    selectAge.innerHTML = contents;
                }
                if (data.job_time == "monthly") {
                    var selectAge = document.getElementById("job_intervel");
                    var contents;
                    for (let i = 1; i <=12 ; i++) {
                        let selected = '';
                        if(i == data.job_intervel){
                            selected = 'selected';
                        }
                        contents += "<option value=" + i + " "+selected+">" + i+ "  MONTH </option>";
                    }

                    selectAge.innerHTML = contents;
                }

                if (data.cron_type == 'Download VOS SFTP File') {
                    $("#gate").show();
                    $("#data").show();
                    $("#display").hide();
                }
                if (data.cron_type == 'Active job Cron Email') {
                    $("#gate").hide();
                    $("#data").hide();
                    $("#display").show();
                }
                if (data.cron_type == '') {
                    $("#gate").hide();
                    $("#data").hide();
                    $("#display").hide();
                }



                $('#id').val(data.id);
                $('#job_title').val(data.job_title);
                $('#cron_type').val(data.cron_type);
                $('#gateway').val(data.gateway);
                $('#Alert').val(data.Alert);
                $('#download').val(data.download_limit);
                $('#threshold').val(data.threshold);
                $('#success_email').val(data.success_email);
                $('#error_email').val(data.error_email);
                $('#job_time').val(data.job_time);
                $('#job_day').val($.parseJSON(data.job_day)).trigger('change');
                $('#start_time').val(data.start_time);
                $('#status').val(data.status);
                $('#global-loader').hide();

            },

                    error: function(xhr) {
                    $('#global-loader').hide();
                    $.notify(xhr.responseText,'error'); // this line will save you tons of hours while debugging
                    // do something here because of error
                }
        });


    });


function save(formdata,url){
        $('#global-loader').show();
        $.ajax({
          data: formdata,
          url: url,
          type: "POST",
          dataType: 'json',
          success: function (resp) {
                $('#global-loader').hide();
                if(resp.success == false){
                    $.each(resp.errors, function(k, e) {
                        $.notify(e, 'error');
                    });
                }
                else{
                    $.notify(resp.message, 'success');
                    role.draw();
                    $('#CronModal').modal('hide');
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

    $('#save').click(function (e) {
        e.preventDefault();
        let formdata = $('#cronForm').serialize();
        let url =   $('#cronForm').attr('action');
        save(formdata,url);
    });


    $(document).on('click', '.Delete', function () {
        let id = $(this).data('id');
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })

        swalWithBootstrapButtons
            .fire({
                title: '{{ __('title') }}',
                text: '{{ __('text') }}',
                icon: '{{ __('icon') }}',
                showCancelButton: true,
                confirmButtonText: '{{ __('Confirm') }}',

                cancelButtonText: '{{ __('Cancel') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {


                    $.ajax({
                        url: "{{ route('cron.destroy', ": id ") }}",
                        type: 'delete', // replaced from put
                        dataType: "JSON",
                        data: {
                            "id": id, // method and token not needed in data
                            "_token": "{{ csrf_token() }}",
                        },

                        success: function (response) {
                            console.log(response)
                            if (response.success == true) { //YAYA
                                role.draw();
                            } else { //Fail check?
                                timeOutId = setTimeout(ajaxFn, 20000); //set the timeout again

                            }
                            // location.reload();
                        },
                        error: function (xhr) {
                            console.log(xhr.responseText); // this line will save you tons of hours while debugging
                            // do something here because of error
                        }
                    });
                    swalWithBootstrapButtons.fire(
                        '{{ __('deleted') }}!',
                        '{{ __('your_file_deleted') }}',
                        '{{ __('success') }}'
                    )
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    // swalWithBootstrapButtons.fire(
                    //     '{{ __('common.cancelled') }}',
                    //     '{{ __('common.your_imaginary_file_safe') }}',
                    //     'error'
                    // )

                }
            });
    });


</script>
@endsection
