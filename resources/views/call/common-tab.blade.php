<form novalidate class="form-horizontal form-groups-bordered validate d-flex gap-1" method="post" id="cdr_filter">
    <input type="hidden" name="type" class="form-control"  value="Customer"  />
    <div class="form-group customer_Account">
        <label class="control-label" for="field-1">Customer Name</label>
        <select class="form-control" id="bulk_AccountID" allowClear="true" name="AccountID">
            @if(!empty($Accounts))
                @foreach ( $Accounts as $Account )
                    <option value="{{$Account->id}}">{{$Account->firstname}}{{$Account->lastname}}</option>
                @endforeach
            @endif
        </select>
    </div>
   
    <div class="form-group">
        <label class="control-label" for="field-1">Report Name</label>
        <select class="form-control" id="Customerreport"  allowClear="true"  name="report"
        style="width: 138px;">
            <option value="Customer-Summary">Customer Summary</option>
            <option value="Customer-Hourly" {{ @request()->report === "Customer-Hourly" ? 'selected' : '' }}>Customer Hourly</option>
            <option value="Customer/Vendor-Report" {{ @request()->report === "Customer/Vendor-Report" ? 'selected' : '' }}>Customer/Vendor Report</option>
            <option value="Account-Manage" {{ @request()->report === "Account-Manage" ? 'selected' : '' }}>Account Manage</option>
            <option value="Customer-Margin-Report" {{ @request()->report === "Customer-Margin-Report" ? 'selected' : '' }}>Margin Report</option>
            <option value="Customer-Negative-Report" {{ @request()->report === "Customer-Negative-Report" ? 'selected' : '' }}>Negative Report</option>
        </select>
    </div>

    <div class="form-group d-flex flex-column" style="max-width: 300px;">
        <label for="field-1">Billing Type</label>
        <select id="billingtype" name="billingtype"  class="form-control w-35">
            <option value="zero"> Zero</option>
            <option value="one">Non-Zero</option>
        </select>
    </div>

    <div class="form-group" style="max-width: 300px;">
        <label class="control-label small_label" for="field-1">Start Date</label>
        <div class="d-flex gap-1">
            <input type="text" name="StartDate" class="form-control datepicker w-35" id="datepicker"  data-date-format="yyyy-mm-dd hh:mm:ss" value="2023-03-03" data-enddate="2023-03-03"  />
        </div>
    </div>
    <div class="form-group" style="max-width: 300px;">
        <label class="col-md-4 control-label small_label" for="field-1" style="padding-left: 0px;">End Date</label>
        <div class="d-flex gap-1">
            <input type="text" name="EndDate" 
            id="datepicker1" class="form-control datepicker"  data-date-format="yyyy-mm-dd hh:mm:ss" value="2023-03-03" data-enddate="2023-03-03" />
        </div>
    </div>
   
    <div class="form-group mt-4">
        <input type="hidden" id="ActiveTab" name="ActiveTab" value="1">
        <a href="" class="btn btn-primary mb-4 w-10 mt-2" id="view_data"><i class="fas fa-eye"></i></a>
        <a href="#" class="btn btn-primary save-collection btn-md  mb-3"  id="ToolTables_table-4_0">
            <undefined>EXCEL</undefined>
        </a>
        <a href="#" class="btn btn-primary save-collection btn-md  mb-3" id="ToolTables_table-4_1">
            <undefined>CSV</undefined>
        </a>
    </div>
</form>



