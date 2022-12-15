<div class="row">
    <div class="col-md-12">
           <form role="form" id="vendor-rate-search" method="get"  action="" class="form-horizontal form-groups-bordered validate" novalidate="novalidate">
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

                        <label class="col-sm-1 control-label">Description</label>
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
                                        <option value="{{$value->id}}">{{$value->phonecode}} {{$value->name}} </option>
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
                        <button type="submit" class="btn btn-primary btn-sm btn-icon icon-left">
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
function myFunction() {
    var x = document.getElementById("myDIV");
    if (x.style.display === "none") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
}
    </script>
