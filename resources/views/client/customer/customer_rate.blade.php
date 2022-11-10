<div class="row">
    <div class="col-md-12">
        <form  method="post"  action="" class="form-horizontal form-groups-bordered validate" novalidate="novalidate">
           <div class="card card-primary" data-collapsed="0">
               <div class="card-header ">
                   <div class="card-title ">
                       Search
                   </div>
                   <div class="card-options float-right">
                       <a href="#" class=" float-end" data-rel="collapse"><i class="fas fa-angle-down"></i></a>
                   </div>
               </div>

               <div class="card-body">
                    <div class="form-group">
                        <div class="row">
                            <label for="field-1" class="col-sm-1">Code</label>
                            <div class="col-sm-2">
                                <input type="text" name="Code" class="form-control" id="field-1" placeholder="" value="" />
                            </div>

                            <label class="col-sm-1">Description</label>
                            <div class="col-sm-2">
                                <input type="text" name="Description" class="form-control" id="field-1" placeholder="" value="" />
                            </div>

                            <label for="field-2" class="col-sm-2">Discontinued Codes</label>
                            <div class="col-sm-1">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" id="DiscontinuedRates" name="DiscontinuedRates" type="checkbox" value="1">
                                </div>
                            </div>


                            <div class="col-sm-3 EffectiveBox">
                                <label class="EffectiveBox">Show Applied Rates</label>
                                <input id="Effected_Rates_on_off" class="icheck" name="Effected_Rates_on_off" type="checkbox" value="1" >
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-4">
                        <div class="row">
                            <label for="field-1" class="col-sm-1 ">Country</label>
                            <div class="col-sm-2">
                                <select class="custom-select form-control" name="Country"></select>
                            </div>

                            <label for="field-1" class="col-sm-1 ">Trunk</label>
                            <div class="col-sm-2">
                                <select class="custom-select form-control" id="ct_trunk" name="Trunk"></select>
                            </div>

                            <label for="field-1" class="col-sm-1">Effective</label>
                            <div class="col-sm-2 EffectiveBox">
                                <select name="Effective" class="custom-select form-control" data-allow-clear="true" data-placeholder="Select Effective">
                                    <option value="Now">Now</option>
                                    <option value="Future">Future</option>
                                    <option value="All">All</option>
                                    <option value="CustomDate">Custom Date</option>
                                </select>
                            </div>

                            <label for="field-1" class="col-sm-1 EffectiveBox CustomDateBox" style="display: none">Custom Date</label>
                            <div class="col-sm-2 EffectiveBox CustomDateBox" style="display: none">
                                <input type="text" name="CustomDate" data-date-format="yyyy-mm-dd" placeholder="2022-11-10" data-startdate="2022-11-10" value="2022-11-10" class="form-control datepicker">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-5">
                        <div class="row">
                            <label class="col-sm-1">Timezone</label>
                            <div class="col-sm-3">
                                <select class="custom-select form-control" name="Timezones"><option value="1">Default</option></select>
                            </div>

                            <label for="field-3" class="col-sm-1  RoutinePlan">Routing Plan</label>
                            <div class="col-sm-3">
                                <select class="custom-select form-control RoutinePlan" name="RoutinePlanFilter"><option value="" selected="selected">Select</option><option value="2">CLI</option><option value="3">NCLI</option><option value="4">Call Center</option></select>
                            </div>
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
<div class="clear"></div>

{{-- <div class="row">
    <div class="col-md-12">
        <div class="alert alert-danger" style="padding: 6px;">
            In order to offer new rates use 'Bulk New Offer' OR 'New Offer Selected'.  In order to update rates use 'Update Selected Rates' OR 'Bulk Update'. To Delete rates use 'Bulk Clear' OR 'Clear Selected Rates'.
        </div>
    </div>
</div> --}}

<div class="clear"></div>
<div class="row">
    <div  class="col-md-12">
        <div class="input-group-btn pull-right" style="width:76px;" id="btn-action">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action <span class="caret"></span></button>
            <ul class="dropdown-menu dropdown-menu-left" role="menu" style="background-color: #000; border-color: #000; margin-top:0px;">
                <li>
                    <a class="generate_rate create" id="addSelectedCustomerRates" href="javascript:;" >
                            New Offer Selected
                        </a>
                </li>
                <li>
                    <a class="generate_rate create" id="insertBulkCustomerRates" href="javascript:;" >
                        Bulk New Offer
                    </a>
                </li>
                <li>
                    <a class="generate_rate create" id="changeSelectedCustomerRates" href="javascript:;" >
                        Update Selected Rates
                    </a>
                </li>
                <li>
                    <a class="generate_rate create" id="bulk_set_cust_rate" href="javascript:;" style="width:100%">
                        Bulk Update
                    </a>
                </li>
                <li>
                    <a class="generate_rate create" id="clear-bulk-rate" href="javascript:;" style="width:100%">
                        Clear Selected Rates
                    </a>
                </li>
                <li>
                    <a class="generate_rate create" id="bulk_clear_cust_rate" href="javascript:;" style="width:100%">
                        Bulk Clear
                    </a>
                </li>
            </ul>
            <form id="clear-bulk-rate-form" >
                <input type="hidden" name="CustomerRateID" value="">
                <input type="hidden" name="TrunkID" value="">
                <input type="hidden" name="TimezonesID" value="">
            </form>
        </div><!-- /btn-group -->
    </div>
    <div class="clear"></div>
</div>
<br>
<table class="table table-bordered datatable" id="table-4">
    <thead>
        <tr>
            <th width="2%"><input type="checkbox" id="selectall" name="checkbox[]" class="" /></th>
            <th width="5%">Code</th>
            <th width="20%">Description</th>
            <th width="5%">Interval 1</th>
            <th width="5%">Interval N</th>
            <th width="5%">Connection Fee</th>
            <th width="5%" class="routng_plan_cl">Routing plan</th>
            <th width="5%">Rate1 ($)</th>
            <th width="5%">RateN ($)</th>
            <th width="8%">Effective Date</th>
            <th width="8%" class="hidden">End Date</th>
            <th width="8%">Modified Date</th>
            <th width="8%">Modified By</th>
            <th width="20%">Action</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

