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
                        <div class="col-sm-3">
                        <input type="text" value="" placeholder="Code" id="code" class="form-control" name="Code">
                        </div>
                        <label class="col-sm-1 control-label">Status</label>
                        <div class="col-sm-3">
                            <select class=" custom-select form-control select2" name="Status"><option value="All">All</option>
                                <option value="Blocked">Blocked</option>
                                <option value="Not Blocked">Unblocked</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mt-5">
                        <label for="field-1" class="col-sm-1 control-label">Country</label>
                        <div class="col-sm-2">
                            <select class="custom-select form-control select2" name="Country"><option value="" selected="selected">Select</option>  
                                @if(!empty($country))
                                    @foreach ($country as $value )
                                        <option value="{{$value->id}}">{{$value->phonecode}} {{$value->name}} </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <label for="field-1" class="col-sm-1 control-label">Trunk</label>
                        <div class="col-sm-3">
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
                        <div class="col-sm-2">
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
            <form id="blockSelectedCode-form" method="post" action=""  style="margin: 0px; padding: 0px; display: inline-table;" >
                <button id="blockSelectedCode" class="btn btn-primary btn-sm btn-icon icon-left"   data-loading-text="Loading...">
                    <i class="entypo-floppy"></i>
                    Unblock Selected Codes
                </button>
                <input type="hidden" name="RateID" value="">
                <input type="hidden" name="Trunk" value="">
                <input type="hidden" name="Timezones" value="">
                <input type="hidden" name="criteria" value="">
                <input type="hidden" name="action" value="unblock">
            </form>
            <form id="unblockSelectedCode-form" method="post" action="" style="margin: 0px; padding: 0px; display: inline-table;" >
                <button type="submit" id="unblockSelectedCode" class="btn btn-danger btn-sm btn-icon icon-left" data-loading-text="Loading...">
                    <i class="entypo-cancel"></i>
                    Block Selected Codes
                </button>
                <input type="hidden" name="RateID" value="">
                <input type="hidden" name="Trunk" value="">
                <input type="hidden" name="Timezones" value="">
                <input type="hidden" name="criteria" value="">
                <input type="hidden" name="action" value="block">
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
                {"bSortable": false, //RateId
                    mRender: function(id, type, full) {
                        return '<input type="checkbox" name="checkbox[]" value="' + id + '" class="rowcheckbox" >';
                    }
                },  //0 RateId
                {}, //1 Code
                {}, //2 Status
                {}, //3 Description
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
</script>