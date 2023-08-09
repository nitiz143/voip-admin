<form id="add-new-form" action="{{ route('rate-table.store') }}" method="post">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group ">
                    <label for="field-5" class="control-label">Codedeck</label>
                    <select class="form-control select2" name="codeDeckId">
                        <option value="" selected="selected">Select Codedeck</option>
                        <option value="3" {{@$table->codeDeckId == 3 ? 'selected' : ''}}>Customer Codedeck</option>
                        <option value="2" {{@$table->codeDeckId == 2 ? 'selected' : ''}}>Customer Codes</option>
                        <option value="1" {{@$table->codeDeckId == 1 ? 'selected' : ''}}>Vendor Codes</option>
                    </select>
                </div>
            </div>
             <div class="col-md-6">
                <div class="form-group ">
                    <label for="field-5" class="control-label">Trunk</label>
                    <select class="select2 form-control  " data-modal="add-new-modal-trunk" data-active="0" data-type="trunk" name="trunkId">
                        <option value="" selected="selected">Select</option>
                        @if(!empty($trunks))
                            @foreach ($trunks as $trunk )
                            <option value="{{$trunk->id}}"  {{@$table->trunkId == $trunk->id ? 'selected' : ''}}>{{$trunk->title}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>
        <input type="hidden" name="id" value="{{@$table->id}}">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group ">
                    <label for="field-5" class="control-label">Currency</label>
                    <select class="form-control select2" name="currency">
                        <option value="" selected="selected">Select Currency</option>
                        <option value="USD" {{@$table->currency == "USD" ? 'selected' : ''}}>USD</option>
                    </select>
                </div>
            </div>
             <div class="col-md-6">
                <div class="form-group ">
                    <label for="field-5" class="control-label">RateTable Name</label>
                    <input type="text" name="name" class="form-control" value="{{@$table->name}}">

                </div>
            </div>
        </div>
        <div class="row">
             <div class="col-md-6">
                <div class="form-group ">
                    <label for="field-5" class="control-label">Round Charged Amount </label>
                    <input type="number" name="RoundChargedAmount" min="1" class="form-control" value="{{@$table->RoundChargedAmount}}">

                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" id="formSave"  class="save btn btn-primary btn-sm btn-icon icon-left" data-loading-text="Loading...">
            <i class="entypo-floppy"></i>
            Save
        </button>
        <button  type="button" class="btn btn-danger btn-sm btn-icon icon-left" data-bs-dismiss="modal">
            <i class="entypo-cancel"></i>
            Close
        </button>
    </div>
</form>
