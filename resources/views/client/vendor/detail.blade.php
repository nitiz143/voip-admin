<div class="col-md-12">
    <div class="form-group">
        <label for="field-1" class="control-label col-sm-12 text-left bold">Title:</label>
        <div class="col-sm-12">{{$clients->company}}
            @if(!empty($downloads->format)) {{"($downloads->format)"}}@endif @if(!empty($downloads->effective)){{"($downloads->effective)"}} @endif</div>
    </div>	
    <div class="form-group">
        <label for="field-1" class="control-label col-sm-12 bold">Description</label>
        @if($downloads->type == 'Download') 
            <div class="col-sm-12">Vendor Rate Sheet Download</div>
        @else
            <div class="col-sm-12"> Vendor Rate Uplaod</div>
        @endif
    </div>   
    <div class="form-group">
        <label for="field-1" class="control-label col-sm-12 bold">Account Name</label>
        <div class="col-sm-12">{{$clients->company}}</div>
    </div>        
    @if($downloads->type == 'Download') 
        <div class="form-group">
            <label for="field-1" class="control-label col-sm-12 bold">Output format</label>
            <div class="col-sm-12">{{$downloads->format}}</div>
        </div>	
    @endif
    <div class="form-group">
        <label for="field-1" class="control-label col-sm-12 bold">Trunks</label>
        <div class="col-sm-12">
            @if(!empty($trunks))
               @foreach ( $trunks as $trunk)
                   {{$trunk->title}}&nbsp;
               @endforeach
            @endif
        </div>
    </div>
    @if($downloads->type =='Download') 
        <div class="form-group">
            <label class="control-label col-sm-12 bold">Timezones</label>
            <div class="col-sm-12">
                @if(!empty($downloads->timezones))
                    @foreach (json_decode($downloads->timezones) as $timezone)
                        @if($timezone == 1)
                            Default
                        @else
                            {{$timezone}}
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
        <div class="form-group">
            <label for="field-1" class="control-label col-sm-12 bold">Generated File Path</label>
            <div class="col-sm-12">
                <a href="" class="btn btn-success btn-sm btn-icon icon-left"><i class="entypo-down"></i>Download</a>
            </div>
        </div>
    @else
        <div class="form-group">
            <label class="control-label col-sm-12 bold">Settings</label>
            <div class="col-sm-12">
                Rates with 'effective from' date in the past should be uploaded as effective immediatelyAdd new codes from the file to code decks <br> Complete File.
            </div>
        </div>
    @endif
    <div class="form-group">
        <label for="field-1" class="control-label col-sm-12 bold">Date Created</label>
        <div class="col-sm-12">{{$downloads->created_at}}</div>
    </div>	
</div>