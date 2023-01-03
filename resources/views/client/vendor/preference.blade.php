
<style>
    .datatable tr.selected{
         background:#EDC171;
     }
 </style>
 <div class="modal fade " id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Preference</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-preference" method="POST"  action="{{route('vendor_preference.store')}}" role="form" class="form-horizontal form-groups-bordered">
                    @csrf
                    <div class="form-group">
                        <div class="row">
                            <label for="field-1" class="col-sm-3">Preference</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control " name="preference" value="" >
                            </div>
                        </div>
                    </div>
                    <div id="Code">

                    </div>
                        <input type="hidden" name="VendorPreferenceID" id="VendorPreferenceID" value="">
                        {{-- <input type="hidden" name="CodeID[]" id="CodeID" value=""> --}}
                        <input type="hidden" name="client_id" id="client_id" value="{{@request()->id}}">
                        <input type="hidden" name="Trunk" value="">
                        <input type="hidden" name="Action" value="">
                    <button type="submit" id="save" class="btn btn-primary">save</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
           <form role="form" id="vendor-rate-search" method="get"  action="" class="form-horizontal form-groups-bordered validate" novalidate="novalidate">
            @csrf
            <div class="card card-primary" data-collapsed="0">
                <div class="card-header">
                    <div class="card-title">
                        Search
                    </div>

                    <div class="card-options float-right">
                        <a href="#" class=" float-end" data-rel="collapse" onclick="myFunction()"><i class="fas fa-angle-down"></i></a>
                    </div>
                </div>

                <div class="card-body" id="myDIV">
                    <div class="form-group row">
                        <label for="field-1" class="col-sm-1 control-label">Code</label>
                        <div class="col-sm-3">
                            <input type="text" name="Code" class="form-control" id="field-1" placeholder="" value="" />
                        </div>

                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-3">
                            <input type="text" name="Description" class="form-control" id="field-1" placeholder="" value="" />

                        </div>
                    </div>
                    <div class="form-group row mt-5">
                        <label for="field-1" class="col-sm-1 control-label">Country</label>
                        <div class="col-sm-3">
                            <select class="form-control select2" name="Country">
                                <option value="All">All</option>
                                @if(!empty($country))
                                    @foreach ($country as $value )
                                        <option value="{{$value->name}} ">{{$value->phonecode}} {{$value->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <label for="field-1" class="col-sm-1 control-label">Trunk</label>
                        <div class="col-sm-3">
                            <select class=" form-control select2" name="Trunk">
                                <option value="">Select</option>
                                @if(!empty($trunks))
                                    @foreach ( $trunks as $trunk)
                                        <option value="{{$trunk->id}}">{{$trunk->title}} </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <label class="col-sm-1 control-label">Timezone</label>
                        <div class="col-sm-3">
                            <select class="form-control select2" name="Timezones"><option value="1">Default</option></select>
                        </div>

                    </div>
                    <p style="text-align: right;">
                        <button class="btn btn-primary btn-sm btn-icon icon-left">
                            <i class="entypo-search"></i>
                            Search
                        </button>
                    </p>
                </div>
            </div>
        </form>
    </div>
    </div>
    <div style="text-align: right;padding:10px 0 ">
        <!--<a class="btn btn-primary btn-sm btn-icon icon-left" id="bulk_set_vendor_rate" href="javascript:;">
            <i class="entypo-floppy"></i>
            Bulk update
        </a>-->
        <a class="btn btn-primary btn-sm btn-icon icon-left" id="changeSelectedVendorRates" href="javascript:;">
            <i class="entypo-floppy"></i>
            Change Selected Preference
        </a>
    </div>

    <table class="table table-bordered datatable" id="table-4">
        <thead>
            <tr>
                <th width="6%"><input type="checkbox" id="selectall" name="checkbox[]" class="" />
                    <!--<button type="button" id="selectallbutton"  class="btn btn-primary btn-xs" title="Select All Preference" alt="Select All Preference"><i class="entypo-check"></i></button>-->
                </th>
                <th width="13%">Code</th>
                <th width="10%">Preference</th>
                <th width="10%">Description</th>
                <th width="15%">Action</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
    <script>
   var list_fields  = ['RateID','Code','Preference','Description','VendorPreferenceID'];
        function myFunction() {
            var x = document.getElementById("myDIV");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }
    $(document).on('click', '.edit', function (e) {
        e.preventDefault();
        $('#editModal').modal('show');
        let codeid = $(this).data('codeid');
        let id = $(this).data('id');
        $("#form-preference").find("input[name='CodeID[]']").remove();
        $("#form-preference").find("input[name='VendorPreferenceID']").val(id);
        $("#Code").append('<input type="text" name="CodeID[]"value="'+codeid+'" hidden/>');
        $("#form-preference").find("input[name='Action']").val("single");
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
                    $('#editModal').modal('hide');

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
        let formdata = $('#form-preference').serialize();
        let url =   $('#form-preference').attr('action');
        save(formdata,url);

    });

      //for single select
      $(document).on( 'click','#table-4 tbody  .rowcheckbox' ,  function () {
            if( $(this).prop("checked")){
                $(this).parent().parent().addClass('selected');
            }else{
                $(this).parent().parent().removeClass('selected');
            }
        });

    $("#selectall").click(function (ev) {

        var is_checked = $(this).is(':checked');

        $('#table-4 tbody tr').each(function (i, el) {
            if(is_checked){
                $(this).find('.rowcheckbox').prop("checked",true);
                $(this).addClass('selected');
            }else{
                $(this).find('.rowcheckbox').prop("checked",false);
                $(this).removeClass('selected');
            }
        });
    });

       //Bulk Edit Button
       $("#changeSelectedVendorRates").click(function(ev) {
        var criteria='';
        if($('#selectallbutton').is(':checked')){
        //if($('#selectallbutton').find('i').hasClass('entypo-cancel')){
            criteria = JSON.stringify($searchFilter);
            if(criteria==''){
                return false;
            }
        }
        $("#form-preference").find("input[name='CodeID[]']").remove();
        $('#table-4 tr .rowcheckbox:checked').each(function(i, el) {
            $("#Code").append('<input type="text" name="CodeID[]"value="'+$(this).val()+'" hidden/>'); 
        });
       
        $('#editModal').modal('show', {backdrop: 'static'});
        $("#form-preference [name='Action']").val('selected');
        $("#form-preference [name='VendorPreferenceID']").val();
    });

    </script>
