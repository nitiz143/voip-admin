<style>
    .datatable tr.selected{
         background:#EDC171;
     }
 </style>
<div class="row">
    <div class="col-md-12">
        <form  id="CustomerTrunk-form" method="post" action="{{route('Customer.trunk',@request()->id)}}">
            @csrf
        <div class="card card-primary" data-collapsed="0">
            <div class="card-header">
                <div class="card-title">
                    Outgoing
                </div>
                <div class="form-check form-switch float-right">
                    <a href="#" data-rel="collapse" id="switch" onclick="myFunction()"><i class="fas fa-angle-down"></i></a>
                </div>
            </div>
            <div class="card-body" id="myDIV">
                <table class="table table-bordered datatable" id="table-4">
                    <thead>
                        <tr>
                            <th width="1%"><div class="checkbox "><input type="checkbox" id="selectall" name="checkbox[]" class="" ></div></th>
                            <th width="13%">Trunk</th>
                            <th width="13%">Prefix</th>
                            <th style="text-align:center" width="7%">Show Prefix in Ratesheet</th>
                            <th width="7%">Use Prefix In CDR</th>
                            <th style="text-align:center" width="7%">Enable Routing Plan</th>
                            <th width="18%">CodeDeck</th>
                            <th width="30%">Base Rate Table</th>
                            <th width="4%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($trunks))
                            @foreach ( $trunks as $index=>$trunk )
                                <tr class="odd gradeX @if(!$trunk->customers->isEmpty()) @if($trunk->customers[0]->customer_id == @request()->id) selected @endif @endif">
                                    <td>
                                        <input type="checkbox" name="CustomerTrunk[{{$trunk->id}}][status]" class="rowcheckbox" value="1" data-id="{{$trunk->id}}" @if(!$trunk->customers->isEmpty()) @if($trunk->customers[0]->customer_id == @request()->id) checked @endif @endif>
                                    </td>

                                    <td>
                                        {{$trunk->title}}
                                    </td>

                                    <td>
                                        <input type="text" class="form-control" name="CustomerTrunk[{{$trunk->id}}][prefix]" value="@if(!$trunk->customers->isEmpty()) @if($trunk->customers[0]->customer_id == @request()->id) {{$trunk->customers[0]->prefix}} @endif @endif"/>
                                    </td>

                                    <td class="center" style="text-align:center">
                                        <input type="checkbox" value="1" name="CustomerTrunk[{{$trunk->id}}][includePrefix]" @if(!$trunk->customers->isEmpty()) @if($trunk->customers[0]->customer_id == @request()->id)  @if($trunk->customers[0]->includePrefix == 1)
                                        checked
                                        @endif  @endif @endif>
                                    </td>

                                    <td class="center" style="text-align:center">
                                        <input type="checkbox" value="1" name="CustomerTrunk[{{$trunk->id}}][prefix_cdr]"  @if(!$trunk->customers->isEmpty()) @if($trunk->customers[0]->customer_id == @request()->id)  @if($trunk->customers[0]->prefix_cdr == 1)
                                        checked
                                        @endif  @endif @endif>
                                    </td>

                                    <td class="center" style="text-align:center">
                                        <input type="checkbox" value="1" name="CustomerTrunk[{{$trunk->id}}][routine_plan_status]"  @if(!$trunk->customers->isEmpty()) @if($trunk->customers[0]->customer_id == @request()->id)  @if($trunk->customers[0]->routine_plan_status == 1)
                                        checked
                                        @endif  @endif @endif>
                                    </td>

                                    <td>
                                        <select class=" codedeckid custom-select form-control" id="codedeckid" name="CustomerTrunk[{{$trunk->id}}][codedeck]">
                                            <option value="">Select</option>
                                            <option value="3" @if(!$trunk->customers->isEmpty())  @if($trunk->customers[0]->customer_id == @request()->id) {{$trunk->customers[0]->codedeck == '3' ? "selected" : ""}} @endif @endif>Customer Codedeck</option>
                                            <option value="2" @if(!$trunk->customers->isEmpty())  @if($trunk->customers[0]->customer_id == @request()->id) {{$trunk->customers[0]->codedeck == '2' ? "selected" : ""}} @endif  @endif>Customer Codes</option>
                                            <option value="1" @if(!$trunk->customers->isEmpty())  @if($trunk->customers[0]->customer_id == @request()->id) {{$trunk->customers[0]->codedeck == '1' ? "selected" : ""}} @endif  @endif>Vendor Codes</option>
                                        </select>
                                        <input type="hidden" name="codedeckid" value=" @if(!$trunk->customers->isEmpty())  @if($trunk->customers[0]->customer_id == @request()->id) {{$trunk->customers[0]->codedeck}} @endif  @endif">
                                        <input type="hidden" name="trunkid" value="{{$trunk->id}}">
                                        <input type="hidden" name="CustomerTrunk[{{$trunk->id}}][trunkid]" value="{{$trunk->id}}">
                                        <input type="hidden"  name="ratetable_codekid" value=" @if(!$trunk->customers->isEmpty()) @if($trunk->customers[0]->customer_id == @request()->id) {{$trunk->customers[0]->rate_table_id}} @endif @endif">
                                    </td>

                                    <td>
                                        <select class=" ratetableid custom-select form-control" id="ratetableid{{$trunk->id}}" name="CustomerTrunk[{{$trunk->id}}][rate_table_id]">
                                            <option value="">Select</option>
                                        </select>
                                        <input type="hidden" id="ratetable" name="ratetable" value=" @if(!$trunk->customers->isEmpty()) @if($trunk->customers[0]->customer_id == @request()->id) {{$trunk->customers[0]->rate_table_id}} @endif @endif">
                                    </td>

                                    <td>
                                        @if(!$trunk->customers->isEmpty())
                                            @if($trunk->customers[0]->customer_id == @request()->id)
                                                @if($trunk->customers[0]->status == 1)
                                                    Active
                                                @endif
                                            @else
                                                Inactive
                                            @endif
                                        @else
                                            Inactive
                                        @endif
                                    </td>
                                    <input type="hidden" id="customer_trunk_id" name="CustomerTrunk[{{$trunk->id}}][customer_trunk_id]" value="@if(!$trunk->customers->isEmpty())  @if($trunk->customers[0]->customer_id == @request()->id) {{$trunk->customers[0]->id}} @endif  @endif">
                                </tr>
                            @endforeach
                        @endif
                    </tbody>

                </table>
                <p class="float-right mt-4" >
                    <button type="submit" id="customer-trunks-submit" class="btn save btn-primary btn-sm btn-icon icon-left">
                        <i class="entypo-floppy"></i>
                        Save
                    </button>
                </p>
            </div>
        </div>
        </form>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <form  id="inbound-ratetable-form" class="form-horizontal " method="post" action="" >
            <div class="card card-primary" data-collapsed="0">
                <div class="card-header " >
                    <div class="card-title ">
                        Incoming
                    </div>
                    <div class="card-title float-right">
                        <a href="#" id="switch" data-rel="collapse" onclick="myFunction1()"><i class="fas fa-angle-down"></i></a>
                    </div>
                </div>
                <div class="card-body mt-4" id="myDIV1">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="field-1" class="col-sm-2 control-label">Inbound Rate Table</label>
                            <div class="col-md-4">
                                <select class="custom-select form-control" data-placeholder="Select a Rate Table" name="InboudRateTableID">
                                    @if(!empty($rates))
                                        @foreach ($rates as $rate)
                                            <option value="{{$rate->id}}">{{$rate->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
var data = @json($data);
 $(document).ready(function ($) {
        // Replace Checboxes
        $(".pagination a").click(function (ev) {
        replaceCheckboxes();
        });
        $('#table-4 tbody .rowcheckbox').click(function () {
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


        function save(formdata,url){
            $('#global-loader').show();
            $.ajax({
                data: formdata,
                url: url,
                type: "POST",
                 // dataType: 'json',
                cache:false,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (resp) {
                    $('#global-loader').hide();
                    if(resp.success == false){
                        $.each(resp.errors, function(k, e) {
                            $.notify(e, 'error');
                        });
                    }
                    else{
                        $.notify(resp.message, 'success');
                        setTimeout(function () {
                            window.location.reload();
                        }, 1000);
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
        $("body").delegate("#CustomerTrunk-form", "submit", function(e) {
            e.preventDefault();
            var form = $('#CustomerTrunk-form'),
            url = form.attr('action');
            var formData = new FormData(this);
            save(formData,url);

        });

            // $("#customer-trunks-submit").click(function () {
            //     $("#CustomerTrunk-form").submit();
            //     return false;
            // });

            $('.codedeckid').on('select2-open',function(e){

            }).on('change',function (e) {
                codedeckid = $(this).val();
                var self = $(this);
                var current_obj = self;
                var id = self.parent().children('[name="trunkid"]').val();
                $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "post",
                        url: "{{ route('customerCodedeckid.update') }}",
                        data: {
                        id: id,
                        codedeckid: codedeckid
                        },
                        success: function(res) {

                            $('#ratetableid'+id).empty();
                            if($.isEmptyObject(res.rate_table)) {
                                $('#ratetableid'+id).html('<option value="">Select</option>');

                            }else{
                                $.each(res.rate_table, function (key, value) {

                                $('#ratetableid'+id).append('<option value="' + value
                                    .id + '">' + value.name + '</option>');
                                });
                            }
                        }
                    });
            });

    });

    $(document).ready(function(){
        $('input[name="codedeckid"]').each( function() {
        codedeckid = $(this).val();
        var self = $(this);
        var current_obj = self;
        var id = self.parent().children('[name="trunkid"]').val();
        var ratetable = self.parent().children('[name="ratetable_codekid"]').val();
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "post",
                url: "{{ route('customerCodedeckid.update') }}",
                data: {
                id: id,
                codedeckid: codedeckid
                },
                success: function(res) {

                    if($.isEmptyObject(res.rate_table)) {
                        $('#ratetableid'+id).html('<option value="">Select</option>');

                    }else{
                        $.each(res.rate_table, function (key, value) {
                            if(ratetable == value.id){
                                $('#ratetableid'+id).append('<option value="' + value
                                .id + '" selected>' + value.name + '</option>');
                            }else{
                                $('#ratetableid'+id).append('<option value="' + value
                                .id + '">' + value.name + '</option>');
                            }

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

function myFunction1() {
  var x = document.getElementById("myDIV1");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}
</script>
