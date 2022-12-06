<div class="col-md-12">
    <div class="form-group">
        <label for="field-1" class="control-label col-sm-12 text-left bold">Title:</label>
        <div class="col-sm-12">{{$clients->company."($downloads->format)($downloads->effective)"}}</div>
    </div>	
    <div class="form-group">
        <label for="field-1" class="control-label col-sm-12 bold">Description</label>
        <div class="col-sm-12">Download Customer Rate Sheet</div>
    </div>   
    <div class="form-group">
        <label for="field-1" class="control-label col-sm-12 bold">Account Name</label>
        <div class="col-sm-12">{{$clients->company}}</div>
    </div>         
    <div class="form-group">
        <label for="field-1" class="control-label col-sm-12 bold">Output format</label>
        <div class="col-sm-12">{{$downloads->format}}</div>
    </div>	
    <div class="form-group">
        <label for="field-1" class="control-label col-sm-12 bold">Trunks</label>
        <div class="col-sm-12">
            @if(!empty($trunks))
               @foreach ( $trunks as $trunk)
                   {{$trunk->title}}
               @endforeach
            @endif
        </div>
    </div>	
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
    <div class="form-group">
        <label for="field-1" class="control-label col-sm-12 bold">Date Created</label>
        <div class="col-sm-12">{{$downloads->created_at}}</div>
    </div>	
</div>