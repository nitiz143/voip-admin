<style>
    .datatable tr.selected{
         background:#EDC171;
     }
 </style>
<div class="card card-primary" data-collapsed="0">

    <div class="card-header">
        <div class="card-title">
            Customer Rate Sheet Download
        </div>

        <div class="card-options float-right">
            <a href="#" data-rel="collapse" onclick="myFunction()"><i class="fas fa-angle-down" ></i></a>
        </div>
    </div>

    <div class="card-body" id="myDIV">
        <form id="form-download" action="{{route('process_download',@request()->id)}}" role="form" class="form-horizontal form-groups-bordered">
            <div class="form-group">
                <div class="row">
                    <label for="field-1" class="col-sm-3">Trunk</label>
                    <div class="col-sm-3">
                        <div class="checkbox">
                            <label>
                                @if(!empty($trunks))
                                    @foreach ( $trunks as $trunk)
                                        <input type="checkbox" name="Trunks[]" value="{{$trunk->id}}" >{{$trunk->title}}
                                    @endforeach
                                @endif
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="field-1" class="col-sm-3 control-label">Timezones</label>
                    <div class="col-sm-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="Timezones[]" value="1" >Default
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="field-1" class="col-sm-3 control-label">Output format</label>
                    <div class="col-sm-5">
                        <select class="custom-select form-control" id="fileformat" name="Format">
                            <option value="" selected="selected">Select</option><option value="Vos 2.0">Vos 2.0</option><option value="Vos 3.2">Vos 3.2</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="field-1" class="col-sm-3 control-label">File Type</label>
                    <div class="col-sm-5">
                    <select class="custom-select form-control" id="filetype" allowClear="true" name="filetype"><option value="" selected="selected">Select a Type</option></select>
                    </div>
                </div>
            </div>
            <div class="form-group effective">
                <div class="row">
                    <label for="field-1" class="col-sm-3 control-label">Effective</label>
                    <div class="col-sm-5">
                        <select name="Effective" class="custom-select form-control" data-allow-clear="true" data-placeholder="Select Effective" id="fileeffective">
                            <option value="Now">Now</option>
                            <option value="Future">Future</option>
                            <option value="CustomDate">Custom Date</option>
                            <option value="All">All</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group DateFilter" style="display: none;">
                <div class="row">
                    <label for="field-1" class="col-sm-3 control-label">Date</label>
                    <div class="col-sm-5">
                        <input type="date" class="form-control datepicker" data-date-format="yyyy-mm-dd" placeholder="2022-11-10" data-startdate="2022-11-10" name="CustomDate" type="text" value="2022-11-10">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="field-1" class="col-sm-3 control-label">Merge Output file By Trunk</label>
                    <div class="col-sm-5">
                        <div class="form-check form-switch ml-4">
                            <input type="hidden" name="isMerge" value="0">
                            <input type="checkbox"  class="form-check-input" name="isMerge" value="1">
                            <input type="hidden" name="sendMail" value="0">
                            <input type="hidden" name="type" value="CD" />
                        </div>
                    </div>
                </div>
            </div>
            <h4 >Click <span class="label btn-info" onclick="$('.my_account_table-5').toggle();$('#table-5').toggle();"    style="cursor: pointer; border-radius: 10px;">here</span> to select additional customer accounts for bulk ratesheet download.</h4>
            <div style="max-height: 500px; overflow-y: auto; overflow-x: hidden; " >
                <div class="row my_account_table-5" style="display:none">
                    <div class="col-sm-4" style="float: right">
                        <select id="account_owners" class="custom-select form-control" name="account_owners">
                            <option value="0">Select Owner</option>
                            @if(!empty($owners))
                                @foreach ($owners as $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <table class="table table-bordered datatable mt-4" style="display:none" id="table-5">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectallcust" name="customer[]" class="" /></th>
                            <th>Customer Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($clients))
                            @foreach ($clients as $client)
                            <tr>
                                <td><input type="checkbox" name="customer[]" value="{{$client->id}}" class="rowcheckbox"></td>
                                <td>{{$client->firstname}}</td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

        </form>
        <p style="text-align: right;margin-top: 5px">
            {{-- <a href="#" class="btn emailsend hidden btn-primary btn-sm btn-icon icon-left">
                <i class="entypo-mail"></i>
                Send Email
            </a> --}}
            <a href="#" class="btn download btn-primary btn-sm btn-icon icon-left">
                <i class="entypo-floppy"></i>
                Download
            </a>
        </p>
    </div>
</div>

<script>
        $(document).on('click', '#table-5 tbody .rowcheckbox', function() {
            if( $(this).prop("checked")){
                $(this).parent().parent().addClass('selected');
            }else{
                $(this).parent().parent().removeClass('selected');
            }
        });
        $(document).on('click', '#selectallcust' ,function (ev) {
            var is_checked = $(this).is(':checked');
            $('#table-5 tbody tr').each(function (i, el) {
                if(is_checked){
                    $(this).find('.rowcheckbox').prop("checked",true);
                    $(this).addClass('selected');
                }else{
                    $(this).find('.rowcheckbox').prop("checked",false);
                    $(this).removeClass('selected');
                }
            });
        });
        $(document).ready(function () {
            $('#fileformat').on('change', function () {
                var value = $(this).val();
                if(value != "Vos 2.0" && value != "Vos 3.2" ){
                    $("#filetype").html('<option value="">Select</option>');
                }else{
                    $("#filetype").html('<option value="">Select</option><option value="Text">Text</option><option value="Excel">Excel</option><option value="CSV">CSV</option>');
                }

            });
            $("#fileeffective").on("change", function() {
                if($(this).val() == "CustomDate") {
                    $(".DateFilter").show();
                } else {
                    $(".DateFilter").hide();
                }
            });

            $('#account_owners').on('change', function () {
                var owner_id = $(this).val();
                var id = "{{request()->id}}";
                $.ajax({
                    url: "{{route('owners_customer',":id")}}",
                    type: "GET",
                    data: {
                        "id":id,
                        "owner_id":owner_id
                    },
                    dataType: 'json',
                    success: function (resp) {
                        $('#table-5 tbody').empty();
                        if($.isEmptyObject(resp)) {
                            $('#table-5 tbody').text('No Customer');
                        }
                        else{
                            $.each(resp, function (key, value) {
                                $('#table-5 tbody').append('<tr> <td><input type="checkbox" name="customer[]" id="rowcheckbox" value="'+value.id+'" class="rowcheckbox"/></td> <td>'+value.firstname+'</td> </tr>');
                            });
                        }
                    }
                });
            });
        });

        function myFunction() {
            var x = document.getElementById("myDIV");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }


        $(".btn.download").click(function () {
           // return false;
            var formData = new FormData($('#form-download')[0]);
            $.ajax({
                url:  $('#form-download').attr("action"),  //Server script to process data
                type: 'POST',
                dataType: 'json',
                //Ajax events
                beforeSend: function(){
                    $('.btn.download').button('loading');
                },
                afterSend: function(){
                    console.log("Afer Send");
                },
                success: function (response) {
                    console.log(response.errors)
                    if (response.success) {
                        $.notify(response.message, 'success');
                       
                        reloadJobsDrodown(0);
                     } else {
                        $.each(response.errors, function(k, e) {
                            $.notify(e, 'error');
                        });
                    }
                    //alert(response.message);
                    $('.btn.download').button('reset');
                },
                // Form data
                data: formData,
                //Options to tell jQuery not to process data or worry about content-type.
                cache: false,
                contentType: false,
                processData: false
            });
            return false;

        });

</script>
