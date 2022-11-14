<div class="card card-primary" data-collapsed="0">

    <div class="card-header">
        <div class="card-title">
            Customer Rate Sheet Download
        </div>

        <div class="card-options float-right">
            <a href="#" data-rel="collapse"><i class="fas fa-angle-down"></i></a>
        </div>
    </div>

    <div class="card-body">
        <form id="form-download" action="" role="form" class="form-horizontal form-groups-bordered">
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
                        <input class="form-control datepicker" data-date-format="yyyy-mm-dd" placeholder="2022-11-10" data-startdate="2022-11-10" name="CustomDate" type="text" value="2022-11-10">
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
            <h4 >Click <span class="label btn-info" onclick="$('.my_account_table-5').toggle();$('#table-5').toggle();"    style="cursor: pointer">here</span> to select additional customer accounts for bulk ratesheet download.</h4>
            <div style="max-height: 500px; overflow-y: auto; overflow-x: hidden;" >
                <div class="row my_account_table-5">
                    <div class="col-sm-4" style="float: right">
                        <select id="account_owners" class="custom-select form-control" name="account_owners">
                        </select>
                    </div>
                </div>
                <table class="table table-bordered datatable mt-4" id="table-5">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectallcust" name="customer[]" class="" /></th>
                            <th>Customer Name</th>
                        </tr>
                    </thead>
                    <tbody>
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
