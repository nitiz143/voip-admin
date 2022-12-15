<style>
    .datatable tr.selected{
         background:#EDC171;
     }
 </style>
<div class="row">
    <div class="col-md-12">
        <form id="block_by_country_form" method="get"  action="" class="form-horizontal form-groups-bordered validate" novalidate="novalidate">
            <div class="card card-primary" data-collapsed="0">
                <div class="card-header">
                    <div class="card-title">
                        Filter
                    </div>
                    <div class="card-options float-right">
                        <a href="#" class=" float-end" data-rel="collapse" onclick="myFunction()"><i class="fas fa-angle-down"></i></a>
                    </div>
                </div>
                <div class="card-body" id="myDIV">
                    <div class="form-group row">
                        <label for="field-1" class="col-sm-1 control-label">Country</label>
                        <div class="col-sm-2">
                            <select class="custom-select form-control select2" name="Country">
                                <option value="">Select Country</option>
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
                    <div class="form-group row mt-5">
                        <label class="col-sm-1 control-label">Status</label>
                        <div class="col-sm-3">
                            <select class=" custom-select form-control select2" name="Status"><option value="All">All</option>
                                <option value="Blocked">Blocked</option>
                                <option value="Not_Blocked">Unblocked</option>
                            </select>
                        </div>
                    </div>
                    <p style="text-align: right;">
                        <button type="submit" id="submit" class="btn btn-primary btn-sm btn-icon icon-left">
                            <i class="entypo-search"></i>
                            Search
                        </button>
                    </p>
                </div>
            </div>
        </form>
    </div>
    <div style="text-align: right;padding:10px 0 ">
        <form id="unblockSelectedCountry-form" method="post" action="{{route('block-unblock-by-country',":id")}}" style="margin: 0px; padding: 0px; display: inline-table;" >
            @csrf
            <button type="submit" id="unblockSelectedCountry" class="btn btn-primary btn-sm btn-icon icon-left" data-loading-text="Loading...">
                <i class="entypo-cancel"></i>
                Unblock Selected Country
            </button>
            <div id="Country_unblock">
            </div>
            {{-- <input type="hidden" name="CountryID" value=""> --}}
            <input type="hidden" name="client_id" value="{{request()->id}}">
            <input type="hidden" name="Trunk" value="">
            <input type="hidden" name="Timezones" value="">
            <input type="hidden" name="criteria" value="">
            <input type="hidden" name="action" value="unblock">
        </form>
        <form id="blockSelectedCountry-form"  action="{{route('block-unblock-by-country',":id")}}"  style="margin: 0px; padding: 0px; display: inline-table;" >
            @csrf
            <button  id="blockSelectedCountry" type="submit" class="btn btn-danger btn-sm btn-icon icon-left" href="javascript:;" data-loading-text="Loading...">
                <i class="entypo-floppy"></i>
                Block Selected Country
            </button>
            <div id="Country_block">
            </div>
            {{-- <input type="hidden" name="CountryID[]" value=""> --}}
            <input type="hidden" name="client_id" value="{{request()->id}}">
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
                    </div>
                </th>
                <th>Country</th>
                <th>Staus</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<script>


     //Unblock Selected Countries
     $("#unblockSelectedCountry-form").submit(function() {
         var criteria='';
         var CountryIDs = [];
         if($('#selectallbutton').is(':checked')){
             criteria = JSON.stringify($searchFilter);
         }else{
            var i = 0;
            $('#table-4 tr .rowcheckbox:checked').each(function(i, el) {
                $("#Country_unblock").append('<input type="text" name="CountryID[]"value="'+$(this).val()+'" hidden/>'); 
            });
         }
        
        
        //Trunk = $('#block_by_country_form').find("select[name='Trunk']").val();
        $("#unblockSelectedCountry-form").find("input[name='Trunk']").val($searchFilter.Trunk);
        $("#unblockSelectedCountry-form").find("input[name='Timezones']").val($searchFilter.Timezones);
        $("#unblockSelectedCountry-form").find("input[name='criteria']").val(criteria);
        
        var formData = new FormData($('#unblockSelectedCountry-form')[0]);
        $.ajax({
            url: $("#unblockSelectedCountry-form").attr("action"),
            type: 'POST',
            dataType: 'json',
            success: function(response) {
               
                $("#unblockSelectedCountry").button('reset');
                if (response.success == true) {
                   $.notify(response.message, "success");
                    data_table.fnFilter('', 0);
                    $("#unblockSelectedCountry-form").find('input[name="CountryID[]"]').val('');
                } else {
                    $.each(response.errors, function(k, e) {
                        $.notify(e, 'error');
                        $("#unblockSelectedCountry-form").find('input[name="CountryID[]"]').val('');
                    });
                }
                if (response.success == null) {
                    $.notify(response.message, "error");
                    $("#unblockSelectedCountry-form").find('input[name="CountryID[]"]').val('');
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


    var $searchFilter = {};
    var checked='';
     $("#block_by_country_form").submit(function(e) {
        e.preventDefault();
        $searchFilter.Trunk = $("#block_by_country_form select[name='Trunk']").val();
        $searchFilter.Status = $("#block_by_country_form select[name='Status']").val();
        $searchFilter.Country = $("#block_by_country_form select[name='Country']").val();
        $searchFilter.Timezones = $("#block_by_country_form select[name='Timezones']").val();

        if(typeof $searchFilter.Trunk  == 'undefined' || $searchFilter.Trunk == '' ){
            $.notify("Please Select a Trunk", "error");
            return false;
        }
        data_table = $("#table-4").dataTable({
            "bDestroy": true, // Destroy when resubmit form
            "bProcessing": true,
            "bServerSide": true,
            "ajax": {
                "url" : "{{route('ajax_datagrid_blockbycountry',"request()->id")}}",
                "data" : function ( d ){
                    d.type = "country",
                    d.id = "{{request()->id}}",
                    d.Trunk= $searchFilter.Trunk,
                    d.Status= $searchFilter.Status,
                    d.Country = $searchFilter.Country,
                    d.Timezones= $searchFilter.Timezones
                },
            },
            "iDisplayLength": parseInt('50'),
            //  "sDom": "<'row'<'col-xs-6 col-left '<'#selectcheckbox.col-xs-1'>'l><'col-xs-6 col-right'<'export-data'T>f>r>t<'row'<'col-xs-6 col-left'i><'col-xs-6 col-right'p>>",
             "aaSorting": [[1, "asc"]],
             "aoColumns":
                [
                    {data:'action',name:'action', orderable: false, searchable: false},
                    {data:'name',name:'name'},
                    {data:'status',name:'status'},
                ],
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

    // Replace Checboxes
    $(".pagination a").click(function(ev) {
        replaceCheckboxes();
    });
     


    $(document).on( 'click','#table-4 tbody  .rowcheckbox' ,  function () {
        if( $(this).prop("checked")){
            $(this).parent().parent().addClass('selected');
        }else{
            $(this).parent().parent().removeClass('selected');
        }
    });

    $("#blockSelectedCountry-form").submit(function() {
       
        var criteria='';
        var CountryIDs = [];
        if($('#selectallbutton').is(':checked')){
            criteria = JSON.stringify($searchFilter);
        }else{
           
            $('#table-4 tr .rowcheckbox:checked').each(function(i, el) {
                
                $("#Country_block").append('<input type="text" name="CountryID[]" value="'+$(this).val()+'" hidden/>'); 
            });
        }
        
        // $("#blockSelectedCountry-form").find("input[name='CountryID[]']").val(CountryID);
        $("#blockSelectedCountry-form").find("input[name='Trunk']").val($searchFilter.Trunk);
        $("#blockSelectedCountry-form").find("input[name='Timezones']").val($searchFilter.Timezones);
        $("#blockSelectedCountry-form").find("input[name='criteria']").val(criteria);

        var formData = new FormData($('#blockSelectedCountry-form')[0]);
        $.ajax({
            url: $("#blockSelectedCountry-form").attr("action"),
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                $("#blockSelectedCountry").button('reset');
                if (response.success == true) {
                    $.notify(response.message, "success");
                    data_table.fnFilter('', 0);
                } else {
                    $.each(response.errors, function(k, e) {
                        $.notify(e, 'error');
                    });
                }
                if (response.success == null) {
                    $.notify(response.message, "error");
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