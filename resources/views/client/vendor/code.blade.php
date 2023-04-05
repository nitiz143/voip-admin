<style>
    .datatable tr.selected{
         background:#EDC171;
     }
 </style>
<div class="row">
    <div class="col-md-12">
        <form id="block_by_code_form" method="get"  action="" class="form-horizontal form-groups-bordered validate" novalidate="novalidate">
            <div class="card card-primary" data-collapsed="0">
                <div class="card-header">
                    <div class="card-title">
                        Filter
                    </div>
                    <div class="card-options float-right">
                        <a href="#" class=" float-end" data-rel="collapse" onclick="myFunction1()"><i class="fas fa-angle-down"></i></a>
                    </div>
                </div>
                <div class="card-body" id="myDIV1">
                    <div class="form-group row">
                        <label class="col-sm-1 control-label">Code</label>
                        <div class="col-sm-2">
                        <input type="text" value="" placeholder="Code" id="code" class="form-control" name="Code">
                        </div>
                        <label class="col-sm-1 control-label">Status</label>
                        <div class="col-sm-2">
                            <select class=" custom-select form-control select2" name="Status"><option value="All">All</option>
                                <option value="Blocked">Blocked</option>
                                <option value="Not_Blocked">Unblocked</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mt-5">
                        <label for="field-1" class="col-sm-1 control-label">Country</label>
                        <div class="col-sm-2">
                            <select class="custom-select form-control select2" name="Country"><option value="" selected="selected">Select</option>  
                                @if(!empty($country))
                                    @foreach ($country as $value )
                                        <option value="{{$value->name}}">{{$value->phonecode}} {{$value->name}} </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <label for="field-1" class="col-sm-1 control-label">Trunk</label>
                        <div class="col-sm-2">
                            <select class="custom-select form-control select2" name="Trunk">
                                <option value="">Select</option>
                                @if(!empty($trunks))
                                    @foreach ( $trunks as $trunk)
                                        <option value="{{$trunk->id}}">{{$trunk->title}} </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <label class="col-sm-2 control-label float-right">Timezone</label>
                        <div class="col-sm-3">
                            <select class=" custom-select form-control select2" name="Timezones">
                                <option value="1">Default</option>
                            </select>
                        </div>
                    </div>

                    <p style="text-align: right;">
                        <button type="submit" class="btn btn-primary btn-sm btn-icon icon-left">
                            <i class="entypo-search"></i>
                            Search
                        </button>
                    </p>
                </div>
            </div>
        </form>
        <div style="text-align: right;padding:10px 0 ">
            <form id="blockSelectedCode-form" method="post" action="{{route('block-unblock-by-codes',':id')}}" style="margin: 0px; padding: 0px; display: inline-table;" >
                @csrf
                <button id="blockSelectedCode" class="btn btn-danger btn-sm btn-icon icon-left"   data-loading-text="Loading...">
                    <i class="entypo-floppy"></i>
                    Block Selected Codes
                </button>
                <div id="Code_block">
                </div>
                {{-- <input type="hidden" name="CodeID" value=""> --}}
                <input type="hidden" name="client_id" value="{{request()->id}}">
                <input type="hidden" name="Trunk" value="">
                <input type="hidden" name="Timezones" value="">
                <input type="hidden" name="criteria" value="">
                <input type="hidden" name="action" value="block">
            </form>
            <form id="unblockSelectedCode-form" method="post" action="{{route('block-unblock-by-codes',':id')}}" style="margin: 0px; padding: 0px; display: inline-table;" >
                @csrf
                <button id="unblockSelectedCode" class="btn btn-primary btn-sm btn-icon icon-left" data-loading-text="Loading...">
                    <i class="entypo-cancel"></i>
                    Unblock Selected Codes
                </button>
                <div id="Code_unblock">
                </div>
                {{-- <input type="hidden" name="CodeID" value=""> --}}
                <input type="hidden" name="client_id" value="{{request()->id}}">
                <input type="hidden" name="Trunk" value="">
                <input type="hidden" name="Timezones" value="">
                <input type="hidden" name="criteria" value="">
                <input type="hidden" name="action" value="unblock">
            </form>
        </div>
        <table class="table table-bordered datatable" id="table-4">
            <thead>
                <tr>
                    <th>
                        <div class="checkbox ">
                            <input type="checkbox" id="selectall" name="checkbox[]" class="">
                            <!--<button type="button" id="selectallbutton"  class="btn btn-primary btn-xs" title="Select All Found Code"><i class="entypo-check"></i></button>-->
                        </div>
                    </th>
                    <th>Code</th>
                    <th>Status</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script>
var $searchFilter = {};
var checked='';
    //var data_table;
    $("#block_by_code_form").submit(function(e) {
        e.preventDefault();
        $searchFilter.Trunk = $("#block_by_code_form select[name='Trunk']").val();
        $searchFilter.Status = $("#block_by_code_form select[name='Status']").val();
        $searchFilter.Country = $("#block_by_code_form select[name='Country']").val();
        $searchFilter.Code = $("#block_by_code_form [name='Code']").val();
        $searchFilter.Timezones = $("#block_by_code_form select[name='Timezones']").val();
        if(typeof $searchFilter.Trunk  == 'undefined' || $searchFilter.Trunk == '' ){
            $.notify("Please Select a Trunk", "error");
            return false;
        }
        
        

        data_table = $("#table-4").dataTable({
            "bDestroy": true, // Destroy when resubmit form
            "bProcessing": true,
            "bServerSide": true,
            "ajax": {
                "url" : "{{route('ajax_datagrid_blockbycode',"request()->id")}}",
                data : function ( d ){
                    d.id = "{{request()->id}}",
                    d.Trunk= $searchFilter.Trunk,
                    d.Status= $searchFilter.Status,
                    d.Country = $searchFilter.Country,
                    d.Code= $searchFilter.Code,
                    d.Timezones= $searchFilter.Timezones
                },
            },
            "iDisplayLength": parseInt('50'),
            //  "sDom": "<'row'<'col-xs-6 col-left '<'#selectcheckbox.col-xs-1'>'l><'col-xs-6 col-right'<'export-data'T>f>r>t<'row'<'col-xs-6 col-left'i><'col-xs-6 col-right'p>>",
            "aaSorting": [[1, "asc"]],
            "aoColumns":
            [
                {data:'action',name:'action', orderable: false, searchable: false},
                {data:'codes',name:'codes'},
                {data:'status',name:'status'},
                {data:'destination',name:'destination'},
            ]
        });
        return false;
    });

    // Select all
    $("#selectall").click(function(ev) {
        var is_checked = $(this).is(':checked');
        $('#table-4 tbody tr').each(function(i, el) {
            if (is_checked) {
                $(this).find('.rowcheckbox').prop("checked", true);
                $(this).addClass('selected');
            } else {
                $(this).find('.rowcheckbox').prop("checked", false);
                $(this).removeClass('selected');
            }
        });
    });
    //for single select
        $('#table-4 tbody tr .rowcheckbox').click(function () {
            if( $(this).prop("checked")){
                $(this).parent().parent().addClass('selected');
            }else{
                $(this).parent().parent().removeClass('selected');
            }
        });
    // Replace Checboxes
    $(".pagination a").click(function(ev) {
        replaceCheckboxes();
    });

     //Unblock Selected Countries
     $("#unblockSelectedCode-form").submit(function() {
            var criteria='';
            var CountryIDs = [];
            if($('#selectallbutton').is(':checked')){
                criteria = JSON.stringify($searchFilter);
            }else{
                var i = 0;
                $('#table-4 tr .rowcheckbox:checked').each(function(i, el) {
                    $("#Code_unblock").append('<input type="text" name="CodeID[]"value="'+$(this).val()+'" hidden/>'); 
                });
            }
            
            
            //Trunk = $('#block_by_country_form').find("select[name='Trunk']").val();
            $("#unblockSelectedCode-form").find("input[name='Trunk']").val($searchFilter.Trunk);
            $("#unblockSelectedCode-form").find("input[name='Timezones']").val($searchFilter.Timezones);
            $("#unblockSelectedCode-form").find("input[name='criteria']").val(criteria);
            
            var formData = new FormData($('#unblockSelectedCode-form')[0]);
            $.ajax({
                url: $("#unblockSelectedCode-form").attr("action"),
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                console.log(response)
                    $("#unblockSelectedCode").button('reset');
                    if (response.success == true) {
                        $("#unblockSelectedCode-form").find("input[name='CodeID[]']").remove();
                        $.notify(response.message, "success");
                        data_table.fnFilter('', 0);
                    } else {
                        $.each(response.errors, function(k, e) {
                            $("#unblockSelectedCode-form").find("input[name='CodeID[]']").remove();
                            $.notify(e, 'error');
                            data_table.fnFilter('', 0);
                        
                        });
                    }
                    if (response.success == null) {
                        $("#unblockSelectedCode-form").find("input[name='CodeID[]']").remove();
                        $.notify(response.message, "error");
                        data_table.fnFilter('', 0);
                    }
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

        $("#blockSelectedCode-form").submit(function() {
        
        var criteria='';
        var CountryIDs = [];
        if($('#selectallbutton').is(':checked')){
            criteria = JSON.stringify($searchFilter);
        }else{
        
            $('#table-4 tr .rowcheckbox:checked').each(function(i, el) {
                
                $("#Code_block").append('<input type="text" name="CodeID[]" value="'+$(this).val()+'" hidden/>'); 
            });
        }
        
        // $("#blockSelectedCountry-form").find("input[name='CountryID[]']").val(CountryID);
        $("#blockSelectedCode-form").find("input[name='Trunk']").val($searchFilter.Trunk);
        $("#blockSelectedCode-form").find("input[name='Timezones']").val($searchFilter.Timezones);
        $("#blockSelectedCode-form").find("input[name='criteria']").val(criteria);

        var formData = new FormData($('#blockSelectedCode-form')[0]);
        $.ajax({
            url: $("#blockSelectedCode-form").attr("action"),
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                $("#blockSelectedCode").button('reset');
                if (response.success == true) {
                    $("#blockSelectedCode-form").find("input[name='CodeID[]']").remove();
                    $.notify(response.message, "success");
                    data_table.fnFilter('', 0);
                } else {
                    $.each(response.errors, function(k, e) {
                        $("#blockSelectedCode-form").find("input[name='CodeID[]']").remove();
                        $.notify(e, 'error');
                        data_table.fnFilter('', 0);
                    });
                }
                if (response.success == null) {
                    $("#blockSelectedCode-form").find("input[name='CodeID[]']").remove();
                    $.notify(response.message, "error");
                    data_table.fnFilter('', 0);
                }
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