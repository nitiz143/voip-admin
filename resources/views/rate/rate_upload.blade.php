@extends('layouts.dashboard')

@section('content')
<style>
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
    <section class="content-heade r">
        <div class="container">
          <div class="row mb-2">
            <div class="col-sm-6">

            </div>
            <div class="col-sm-6">

            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-12"><!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Upload Rates</h3>
                                <div class="card-options float-right">
                                    <a href="#" class=" float-end" data-rel="collapse" onclick="myFunction()"><i class="fas fa-angle-down"></i></a>
                                </div>
                            </div>

                            <form action="{{ route('post-rate-upload') }}" method="POST" id="RateUploadform" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body" id="myDIV">
                                    <div class="form-group row">
                                        <label for="field-1" class="col-sm-2 control-label">Rate Upload Type</label>
                                        <div class="col-sm-10">
                                            <div class="radio radio-replace checked">
                                                <input class="RateUploadType" checked="checked" name="RateUploadType" type="radio" value="vendor">
                                                <label>Vendor</label>
                                            </div>
                                            <div class="radio radio-replace ">
                                                <input class="RateUploadType" name="RateUploadType" type="radio" value="ratetable">
                                                <label>Rate Table</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group vendorcontent typecontentbox row">
                                        <label for="field-1" class="col-sm-2 control-label">Vendor</label>
                                        <div class="col-sm-4">
                                            <select class="form-control select2" id="vendor" name="Vendor">
                                                <option>Select Vendor</option>
                                                @if(!empty($clients))
                                                    @foreach ($clients as $client )
                                                        <option value="{{$client->id}}" >{{$client->company}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group ratetablecontent typecontentbox row" style="display: none">
                                        <label for="field-1" class="col-sm-2 control-label">Ratetable</label>
                                        <div class="col-sm-4">
                                            <select class="form-control  select2" id="ratetable" name="Ratetable">
                                                <option>Select Rate Table</option>
                                                @if (!empty($rate_table))
                                                    @foreach ( $rate_table as $rate)
                                                    <option value="{{$rate->id}}" >{{$rate->name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group customercontent typecontentbox " style="display: none">
                                        <label for="field-1" class="col-sm-2 control-label">Customer</label>
                                        <div class="col-sm-4">
                                            <select class="select2" id="customer" name="Customer"></select>
                                        </div>
                                    </div>

                                    <div class="form-group vendorcontent typecontentbox row">
                                        <label for="field-1" class="col-sm-2 control-label">Trunk</label>
                                        <div class="col-sm-4">
                                            <select class="form-control select2 small" id="Trunk" name="Trunk">
                                                <option>Select Trunk</option>
                                            </select>
                                            <input class="form-control" id="isTrunks" disabled="disabled" name="isTrunks" type="hidden" value="0">
                                        </div>
                                    </div>

                                    <!----------5 row ---------->
        
                                    <div class="form-group row">
                                        <label class="col-sm-2" for="mobile">Upload(.xls, .xlsx, .csv)</label>
                                        {{-- <input type="file" name="file"> --}}
                                        <div class="file-input col-sm-10">
                                            <input type="file" name="csv_file" id="file-input"class="file-input__input"/>
                                            <label class="file-input__label" for="file-input">
                                            <svg
                                                aria-hidden="true"
                                                focusable="false"
                                                data-prefix="fas"
                                                data-icon="upload"
                                                class="svg-inline--fa fa-upload fa-w-16"
                                                role="img"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 512 512">
                                                <path
                                                fill="currentColor"
                                                d="M296 384h-80c-13.3 0-24-10.7-24-24V192h-87.7c-17.8 0-26.7-21.5-14.1-34.1L242.3 5.7c7.5-7.5 19.8-7.5 27.3 0l152.2 152.2c12.6 12.6 3.7 34.1-14.1 34.1H320v168c0 13.3-10.7 24-24 24zm216-8v112c0 13.3-10.7 24-24 24H24c-13.3 0-24-10.7-24-24V376c0-13.3 10.7-24 24-24h136v8c0 30.9 25.1 56 56 56h80c30.9 0 56-25.1 56-56v-8h136c13.3 0 24 10.7 24 24zm-124 88c0-11-9-20-20-20s-20 9-20 20 9 20 20 20 20-9 20-20zm64 0c0-11-9-20-20-20s-20 9-20 20 9 20 20 20 20-9 20-20z"
                                                ></path>
                                            </svg>
                                            <span>Upload file</span></label>
                                        </div>
                                    </div>
                                 
            
                                   
                                        {{-- <p style="text-align: right;">
                                            <button  type="submit" class="btn upload btn-primary btn-sm btn-icon icon-left" data-loading-text="Loading...">
                                                <i class="glyphicon glyphicon-circle-arrow-up"></i>
                                                Upload
                                            </button>
                                        </p>--}}
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer ">
                                    <button type="submit" id="submit" class="btn btn-primary float-right">Submit</button>
                                    <button type="button"  id="cancel" class="btn btn-danger float-right mr-2">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
</div>
{{-- Timepicker --}}



@endsection

@section('page_js')
<script>
    $('#submit').click(function (e) {
        e.preventDefault();
        let formData = new FormData($('#RateUploadform')[0]);
        let url =   $('#RateUploadform').attr('action');
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

   
    $("input[name='RateUploadType']").on('change', function(){
        var Type = $("input[name=RateUploadType]:checked").val();
        var id   = $("#"+Type).val();
        $('.typecontentbox').hide().addClass('hidden');
        $('.'+Type+'content').show().removeClass('hidden');
        getTrunk(Type,id);
        $('.btn.upload').removeAttr('disabled');
    });
    $("select[name='Vendor']").on('change', function(){
        var Type = $("input[name=RateUploadType]:checked").val();
        var id   = $("select[name=Vendor]").val();

        $.when(getTrunk(Type,id)).then(function() {
            if($('#isTrunks').val() == '0') {
               $.notify("You can not upload rate against this account, To upload rates against this account you need to setup trunk against this account", "error");
                $('.btn.upload').attr('disabled','disabled');
            } else {
                $('.btn.upload').removeAttr('disabled');
            }
        });

    });

    function getTrunk($RateUploadType,id) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        return $.ajax({
            url: "{{route('getTrunk')}}",
            data: 'Type='+$RateUploadType+'&id='+id,
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                if(response.success == true) {
                    var html = '';
                    var Trunks = response.data;
                    
                    if(!jQuery.isEmptyObject(Trunks)) {
                        $('#isTrunks').val('1');
                    } else {
                        $('#isTrunks').val('0');
                    }
                    html += '<option value="" >SelectTrunk</option>';

                    Trunks.forEach(Trunks => {
                            html += '<option value="'+Trunks['id']+'">'+Trunks['title']+'</option>';
                        });
                    
                    $('#Trunk').html(html).trigger('change');
                }
                 else {
                   $.notify(response.message, "Error")
                }
            },
            error: function () {
                $.notify("error", "Error")
            }
        });
    }
     
    function myFunction() {
        var x = document.getElementById("myDIV");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }

</script>
@endsection
