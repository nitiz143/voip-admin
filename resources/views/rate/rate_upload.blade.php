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
                                <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Upload Rates</h3>
                            </div>

                            <form action="{{ route('post-rate-upload') }}" method="POST" id="RateUploadform" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
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
                                            <select class="form-control  select2" id="ratetable" name="Ratetable"></select>
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
                                                <option>Select Vendor</option>
                                                @if(!empty($trunks))
                                                    @foreach ($trunks as $trunk )
                                                        <option>{{$trunk->title}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <input class="form-control" id="isTrunks" disabled="disabled" name="isTrunks" type="hidden" value="0">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="field-1" class="col-sm-2 control-label">Upload Template</label>
                                        <div class="col-sm-4">
                                            <select class="form-control select2 select2-offscreen" id="uploadtemplate" name="uploadtemplate" tabindex="-1" style="visibility: visible;">
                                                <option value="" >Select</option>
                                               
                                            </select>
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
                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Settings</label>
                                        <div class="col-sm-10">
                                            <div class="checkbox ">
                                                <label>
                                                    <input type="hidden" name="checkbox_replace_all" value="0" >
                                                    <input type="checkbox" id="rd-1" name="checkbox_replace_all" value="1" > Replace all of the existing rates with the rates from the file
                                                </label>
                                            </div>
                                            <div class="checkbox ">
                                                <input type="hidden" name="checkbox_rates_with_effected_from" value="0" >
                                                <label>
                                                    <input type="checkbox" id="rd-1" name="checkbox_rates_with_effected_from" value="1" checked> Rates with 'effective from' date in the past should be uploaded as effective immediately
                                                </label>
                                            </div>
                                            <div class="checkbox ">
                                                <input type="hidden" name="checkbox_add_new_codes_to_code_decks" value="0" >
                                                <label>
                                                    <input type="checkbox" id="rd-1" name="checkbox_add_new_codes_to_code_decks" value="1" checked> Add new codes from the file to code decks
                                                </label>
                                            </div>
                                            <div class="checkbox review_vendor_rate">
                                                <input type="hidden" name="checkbox_review_rates" value="0" >
                                                <label>
                                                    <input type="checkbox" name="checkbox_review_rates" id="checkbox_review_rates" value="1"> Review Rates
                                                </label> 
                                                    
                                                <span class="label label-info popover-primary" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="if checked, review screen will be displayed before processing" data-original-title="Review Rates">?</span>
                                            </div>
                                            <div class="radio ">
                                                <label>
                                                    <input type="radio" name="radio_list_option" value="1" checked>Complete File
                                                </label> 
                                                <span class="label label-info popover-primary" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="if complete file, codes which are not in the file will be deleted." data-original-title="Completed List">?

                                                </span>
                                                <br/>
                                                <label>
                                                    <input type="radio" name="radio_list_option" value="2">Partial File
                                                </label>
                                                <span class="label label-info popover-primary" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="if partial file, codes only in the file will be processed." data-original-title="Partial List">?
                                                
                                                </span>
                                            </div>
                                            <div class="row" style="margin-top:10px;">
                                                <label for="field-1" class="col-sm-2 control-label" style="text-align: right;">Skips rows from Start (Rate)</label>
                                                <div class="col-sm-3" style="padding-left:40px;">
                                                    <input name="start_row" type="number" class="form-control" data-label="<i class='glyphicon glyphicon-circle-arrow-up'></i>&nbsp;   Browse" style="" placeholder="Skips rows from Start" min="0" value="0">
                                                </div>
                                                <label class="col-sm-3 control-label" style="text-align: right;">Skips rows from Bottom (Rate)</label>
                                                <div class="col-sm-3">
                                                    <input name="end_row" type="number" class="form-control" data-label="<i class='glyphicon glyphicon-circle-arrow-up'></i>&nbsp;   Browse" placeholder="Skips rows from Bottom" min="0" value="0">
                                                </div>
                                            </div>
                                            <br/><br/>
                                            <div class="skip_div_2" style="margin-top:10px;display:none;">
                                                <label for="field-1" class="col-sm-2 control-label" style="text-align: right;">Skips rows from Start (DialCodes)</label>
                                                <div class="col-sm-3" style="padding-left:40px;">
                                                    <input name="start_row_sheet2" type="number" class="form-control" data-label="<i class='glyphicon glyphicon-circle-arrow-up'></i>&nbsp;   Browse" style="" placeholder="Skips rows from Start" min="0" value="0">
                                                </div>
                                                <label class="col-sm-3 control-label" style="text-align: right;">Skips rows from Bottom (DialCodes)</label>
                                                <div class="col-sm-3">
                                                    <input name="end_row_sheet2" type="number" class="form-control" data-label="<i class='glyphicon glyphicon-circle-arrow-up'></i>&nbsp;   Browse" placeholder="Skips rows from Bottom" min="0" value="0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
            
                                    <div class="form-group row">
                                        <label class="col-sm-2 control-label">Note</label>
                                        <div class="col-sm-8">
                                            <p><i class="glyphicon glyphicon-minus"></i><strong>Allowed Extension</strong> .xls, .xlsx, .csv</p>
                                           <!-- <p>Please upload the file in given <span style="cursor: pointer" onclick="jQuery('#modal-fileformat').modal('show');" class="label label-info">Format</span></p>
                                            <p>Sample File <a class="btn btn-success btn-sm btn-icon icon-left" href=""><i class="entypo-down"></i>Download</a></p>-->
                                            <i class="glyphicon glyphicon-minus"></i> <strong>Replace all of the existing rates with the rates from the file -</strong> The default option is to add new rates. If there is at least one parameter that differentiates a new rate from the existent one then the new rate will override it. If a rate for a certain prefix exists in the tariff but is not present in the file you received from the carrier, it will remain unchanged. The replace mode uploads all the new rates from the file and marks all the existent rates as discontinued. <br><br>
                                            <i class="glyphicon glyphicon-minus"></i> <strong>Rates with 'effective from' date in the past should be uploaded as 'effective immediately' - </strong> Sometimes you might receive a file with rates later than expected, when the moment at which the rates were supposed to become effective has already passed. By default this check box is disabled and a rate that has an 'effective from' date that has passed will be rejected and not included in the tariff. Altematively, you may choose to insert these rates into the tariff and make them effective from the current moment; to do so enable this check box. <br><br>
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

    $("input[name='Vendor']").on('change', function(){
        
    });
    $("input[name='RateUploadType']").on('change', function(){
        var Type = $("input[name=RateUploadType]:checked").val();
        var id   = $("#"+Type).val();
        $('.typecontentbox').hide().addClass('hidden');
        $('.'+Type+'content').show().removeClass('hidden');
        getUploadTemplates(Type);
        getTrunk(Type,id);
        $('.btn.upload').removeAttr('disabled');
    });
    $("select[name='Vendor']").on('change', function(){
        var Type = $("input[name=RateUploadType]:checked").val();
        var id   = $("select[name=Vendor]").val();

        $.when(getTrunk(Type,id)).then(function() {
            if($('#isTrunks').val() == '0') {
                toastr.error("You can not upload rate against this account, To upload rates against this account you need to setup trunk against this account", "Error", toastr_opts);
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
                    if (response.status == 'success') {
                        var html = '';
                        var Trunks = response.trunks;
                        var Trunk  = response.trunk;

                        if(!jQuery.isEmptyObject(Trunks)) {
                            $('#isTrunks').val('1');
                        } else {
                            $('#isTrunks').val('0');
                        }

                        for(key in Trunks) {
                            if(Trunks[key] == 'Select') {
                                html += '<option value="'+key+'" selected>'+Trunks[key]+'</option>';
                            } else {
                                html += '<option value="'+key+'">'+Trunks[key]+'</option>';
                            }
                        }
                        $('#Trunk').html(html).trigger('change');
                    } else {
                        notify.error(response.message, "Error", toastr_opts);
                    }
                },
                error: function () {
                    notify.error("error", "Error", toastr_opts);
                }
            });
        }
</script>
@endsection
