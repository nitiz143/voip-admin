<style>
   .datatable tr.selected{
        background:#EDC171;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <form  id="VendorTrunk-form" method="post" action="{{route('Vendor.trunk',@request()->id)}}" >
            @csrf
            <div class="card card-primary" data-collapsed="0">
                <div class="card-header">
                    <div class="card-title">
                        Trunks
                    </div>
                    <div class="card-options float-right">
                        <a href="#" data-rel="collapse"><i class="fas fa-angle-down"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered datatable" id="table-4">
                        <thead>
                            <tr>
                                <th width="1%"><div class="checkbox "><input type="checkbox" id="selectall" name="checkbox[]" class="" ></div></th>
                                <th width="13%">Trunk</th>
                                <th width="13%">Prefix</th>
                                <th width="7%">Use Prefix In CDR</th>
                                <th width="18%">CodeDeck</th>
                                <th width="4%">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($trunks))
                                @foreach ( $trunks as $index=>$trunk )
                                    <tr class="odd gradeX @if(!$trunk->vendors->isEmpty()) @if($trunk->vendors[0]->vendor_id == @request()->id) selected @endif @endif">
                                        <td>
                                            <input type="checkbox" name="VendorTrunk[{{$trunk->id}}][status]" class="rowcheckbox" value="1" @if(!$trunk->vendors->isEmpty())  @if($trunk->vendors[0]->vendor_id == @request()->id)  checked @endif @endif>
                                        </td>
                                        <td>
                                            {{$trunk->title}}
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="VendorTrunk[{{$trunk->id}}][prefix]" value="@if(!$trunk->vendors->isEmpty())  @if($trunk->vendors[0]->vendor_id == @request()->id) {{$trunk->vendors[0]->prefix}}  @endif  @endif"/>
                                        </td>
                                        <td class="center" style="text-align:center">
                                            <input type="checkbox" value="1" name="VendorTrunk[{{$trunk->id}}][prefix_cdr]"
                                            @if(!$trunk->vendors->isEmpty())  @if($trunk->vendors[0]->vendor_id == @request()->id)  @if($trunk->vendors[0]->prefix_cdr == 1)
                                            checked
                                            @endif  @endif  @endif>
                                        </td>
                                        <td>
                                            <select class=" codedeckid custom-select form-control" name="VendorTrunk[{{$trunk->id}}][codedeck]">
                                                <option value="">Select</option>
                                                <option value="3" @if(!$trunk->vendors->isEmpty())  @if($trunk->vendors[0]->vendor_id == @request()->id) {{$trunk->vendors[0]->codedeck == '3' ? "selected" : ""}} @endif @endif>Customer Codedeck</option>
                                                <option value="2" @if(!$trunk->vendors->isEmpty())  @if($trunk->vendors[0]->vendor_id == @request()->id) {{$trunk->vendors[0]->codedeck == '2' ? "selected" : ""}} @endif  @endif>Customer Codes</option>
                                                <option value="1" @if(!$trunk->vendors->isEmpty())  @if($trunk->vendors[0]->vendor_id == @request()->id) {{$trunk->vendors[0]->codedeck == '1' ? "selected" : ""}} @endif  @endif>Vendor Codes</option>
                                            </select>
                                            <input type="hidden" name="prev_codedeckid" value="@if(!$trunk->vendors->isEmpty())@if($trunk->vendors[0]->vendor_id == @request()->id) {{$trunk->vendors[0]->codedeck}}@endif @endif">
                                            <input type="hidden" name="VendorTrunk[{{$trunk->id}}][trunkid]" value="{{$trunk->id}}">
                                            <input type="hidden" name="vendor_trunk_id" value="@if(!$trunk->vendors->isEmpty())@if($trunk->vendors[0]->vendor_id == @request()->id) {{$trunk->vendors[0]->id}} @endif @endif">
                                        </td>
                                        <td>
                                            @if(!$trunk->vendors->isEmpty())
                                                @if($trunk->vendors[0]->vendor_id == @request()->id)
                                                    @if($trunk->vendors[0]->status == 1)
                                                        Active
                                                    @endif
                                                @else
                                                    Inactive
                                                @endif
                                            @else
                                                Inactive
                                            @endif
                                        </td>
                                        <input type="hidden" id="vendor_trunk_id" name="VendorTrunk[{{$trunk->id}}][vendor_trunk_id]" value="@if(!$trunk->vendors->isEmpty())@if($trunk->vendors[0]->vendor_id == @request()->id){{$trunk->vendors[0]->id}}@endif @endif"/>

                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <p class="float-right mt-4" >
                        <a  id="vendor-trunks-submit" class="btn save btn-primary btn-sm btn-icon icon-left">
                            <i class="entypo-floppy"></i>
                            Save
                        </a>
                    </p>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
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


        $("#vendor-trunks-submit").click(function () {
            $("#VendorTrunk-form").submit();
            return false;
        });

        $('.codedeckid').on('change',function (e) {
            codedeckid = $(this).val();
            var self = $(this);
            var current_obj = self;
            var id = self.parent().children('[name="vendor_trunk_id"]').val();
            changeConfirmation = confirm("Are you sure? Related Rates will be deleted");
                if(changeConfirmation){
                    $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                    });
                        $.ajax({
                            type: "post",
                            url: "{{route('vendorCodedeckid.update')}}",
                            data: {
                            id: id,
                            codedeckid: codedeckid
                            },
                            success: function(response) {
                                $('#global-loader').hide();
                                if(response.success == false){
                                    $.each(response.errors, function(k, e) {
                                        $.notify(e, 'error');
                                    });
                                }
                                else{
                                    $.notify(response.message, 'success');
                                }
                            }
                        });
                }else{

                }
        });

});
</script>
