<div class="modal-header">
    <h4 class="modal-title" id="modelHeading"></h4>
</div>
<div class="modal-body">
    <form class="callhistoryForm" name="callhistoryForm">
    <input type="hidden" name="id" id="id">
        <div class="row">
            <div class="form-group col-md-6">
                <label for="caller_id" class="col-sm-2 control-label">Caller_id</label>
                <input type="text" class="form-control" id="caller_id" name="caller_id"    value="{{$callhistory->caller_id}}" >
            </div>
            <div class="form-group col-md-6">{{$callhistory->caller_id}}
                <label for="callere164" class="col-sm-2 control-label">Callere164</label>
                <input type="text" class="form-control" id="callere164" name="callere164"  value="{{$callhistory->callere164}}" >
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="calleraccesse164" class="col-sm-2 control-label">Calleraccesse164</label>
                <input type="text" class="form-control" id="calleraccesse164" name="calleraccesse164"  value="{{$callhistory->calleraccesse164}}" >
            </div>
            <div class="form-group col-md-6">
                <label for="calleee164" class="col-sm-2 control-label">Calleee164</label>
                <input type="text" class="form-control" id="calleee164" name="calleee164"  value="{{$callhistory->calleee164}}" >
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="calleeaccesse164" class="col-sm-2 control-label">Calleeaccesse164</label>
                <input type="text" class="form-control" id="calleeaccesse164" name="calleeaccesse164"    value="{{$callhistory->calleeaccesse164}}" >
            </div>
            <div class="form-group col-md-6">
                <label for="callerip" class="col-sm-2 control-label">Callerip</label>
                <input type="text" class="form-control" id="callerip" name="callerip"    value="{{$callhistory->callerip}}" >
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="callergatewayh323id" class="col-sm-2 control-label">Callergatewayh323id</label>
                <input type="text" class="form-control" id="callergatewayh323id" name="callergatewayh323id"    value="{{$callhistory->callergatewayh323id}}" >
            </div>
            <div class="form-group col-md-6">
                <label for="callerproductid" class="col-sm-2 control-label">Callerproductid</label>
                <input type="text" class="form-control" id="callerproductid" name="callerproductid"    value="{{$callhistory->callerproductid}}" >
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="callertogatewaye164" class="col-sm-2 control-label">Callertogatewaye164</label>
                <input type="text" class="form-control" id="callertogatewaye164" name="callertogatewaye164"    value="{{$callhistory->callertogatewaye164}}" >
            </div>
            <div class="form-group col-md-6">
                <label for="calleeip" class="col-sm-2 control-label">Calleeip</label>
                <input type="text" class="form-control" id="calleeip" name="calleeip"    value="{{$callhistory->calleeip}}" >
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="calleegatewayh323id" class="col-sm-2 control-label">Calleegatewayh323id</label>
                <input type="text" class="form-control" id="calleegatewayh323id" name="calleegatewayh323id"    value="{{$callhistory->calleegatewayh323id}}" >
            </div>
            <div class="form-group col-md-6">
                <label for="calleeproductid" class="col-sm-2 control-label">Calleeproductid</label>
                <input type="text" class="form-control" id="calleeproductid" name="calleeproductid"    value="{{$callhistory->calleeproductid}}" >
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="calleetogatewaye164" class="col-sm-2 control-label">Calleetogatewaye164</label>
                <input type="text" class="form-control" id="calleetogatewaye164" name="calleetogatewaye164" value="{{$callhistory->calleetogatewaye164}}" >
            </div>
            <div class="form-group col-md-6">
                <label for="billingmode" class="col-sm-2 control-label">Billingmode</label>
                <input type="text" class="form-control" id="billingmode" name="billingmode" value="{{$callhistory->billingmode}}" >
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="calllevel" class="col-sm-2 control-label">Calllevel</label>
                <input type="text" class="form-control" id="calllevel" name="calllevel"  value="{{$callhistory->calllevel}}" >
            </div>
            <div class="form-group col-md-6">
                <label for="agentfeetime" class="col-sm-2 control-label">Agentfeetime</label>
                <input type="text" class="form-control" id="agentfeetime" name="agentfeetime" value="{{$callhistory->agentfeetime}}" >
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="starttime" class="col-sm-2 control-label">Starttime</label>
                <input type="text" class="form-control" id="starttime" name="starttime"    value="{{$callhistory->starttime}}" >
            </div>
            <div class="form-group col-md-6">
                <label for="stoptime" class="col-sm-2 control-label">Stoptime</label>
                <input type="text" class="form-control" id="stoptime" name="stoptime"  value="{{$callhistory->stoptime}}" >
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="pdd" class="col-sm-2 control-label">Pdd</label>
                <input type="text" class="form-control" id="pdd" name="pdd" value="{{$callhistory->pdd}}" >
            </div>
            <div class="form-group col-md-6">
                <label for="holdtime" class="col-sm-2 control-label">Holdtime</label>
                <input type="text" class="form-control" id="holdtime" name="holdtime" value="{{$callhistory->holdtime}}" >
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="feeprefix" class="col-sm-2 control-label">Feeprefix</label>
                <input type="text" class="form-control" id="feeprefix" name="feeprefix"  value="{{$callhistory->feeprefix}}" >
            </div>
            <div class="form-group col-md-6">
                <label for="feetime" class="col-sm-2 control-label">Feetime</label>
                <input type="text" class="form-control" id="feetime" name="feetime" value="{{$callhistory->feetime}}" >
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="fee" class="col-sm-2 control-label">Fee</label> 
                <input type="text" class="form-control" id="fee" name="fee" value="{{$callhistory->fee}}" >
            </div>
            <div class="form-group col-md-6">
                <label for="suitefee" class="col-sm-2 control-label">Suitefee</label>
                <input type="text" class="form-control" id="suitefee" name="suitefee" value="{{$callhistory->suitefee}}" >
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="suitefeetime" class="col-sm-2 control-label">Suitefeetime</label>
                <input type="text" class="form-control" id="suitefeetime" name="suitefeetime"  value="{{$callhistory->suitefeetime}}" >
            </div>
            <div class="form-group col-md-6">
                <label for="incomefee" class="col-sm-2 control-label">Incomefee</label>
                <input type="text" class="form-control" id="incomefee" name="incomefee"  value="{{$callhistory->incomefee}}" >
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="customername" class="col-sm-2 control-label">Customername</label>
                <input type="text" class="form-control" id="customername" name="customername"    value="{{$callhistory->customername}}" >
            </div>
            <div class="form-group col-md-6">
                <label for="agentfeeprefix" class="col-sm-2 control-label">Agentfeeprefix</label>
                <input type="text" class="form-control" id="agentfeeprefix" name="agentfeeprefix"  value="{{$callhistory->agentfeeprefix}}" >
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="agentfee" class="col-sm-2 control-label">Agentfee</label>
                <input type="text" class="form-control" id="agentfee" name="agentfee"  value="{{$callhistory->agentfee}}" >
            </div>
            <div class="form-group col-md-6">
                <label for="agentsuitefee" class="col-sm-2 control-label">Agentsuitefee</label>
                <input type="text" class="form-control" id="agentsuitefee" name="agentsuitefee" value="{{$callhistory->agentsuitefee}}" >
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="agentsuitefeetime" class="col-sm-2 control-label">Agentsuitefeetime</label>
                <input type="text" class="form-control" id="agentsuitefeetime" name="agentsuitefeetime" value="{{$callhistory->agentsuitefeetime}}" >
            </div>
            <div class="form-group col-md-6">
                <label for="agentaccount" class="col-sm-2 control-label">Agentaccount</label>
                <input type="text" class="form-control" id="agentaccount" name="agentaccount" value="{{$callhistory->agentaccount}}" >
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="agentname" class="col-sm-2 control-label">Agentname</label>
                <input type="text" class="form-control" id="agentname" name="agentname" value="{{$callhistory->agentname}}" >
            </div>
            <div class="form-group col-md-6">
                <label for="flowno" class="col-sm-2 control-label">Flowno</label>
                <input type="text" class="form-control" id="flowno" name="flowno" value="{{$callhistory->flowno}}" >
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="softswitchdn" class="col-sm-2 control-label">Softswitchdn</label>
                <input type="text" class="form-control" id="softswitchdn" name="softswitchdn" value="{{$callhistory->softswitchdn}}" >
            </div>
            <div class="form-group col-md-6">
                <label for="enddirection" class="col-sm-2 control-label">Enddirection</label>
                <input type="text" class="form-control" id="enddirection" name="enddirection" value="{{$callhistory->enddirection}}" >
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="endreason" class="col-sm-2 control-label">Endreason</label>
                <input type="text" class="form-control" id="endreason" name="endreason" value="{{$callhistory->endreason}}" >
            </div>
            <div class="form-group col-md-6">
                <label for="calleebilling" class="col-sm-2 control-label">Calleebilling</label>
                <input type="text" class="form-control" id="calleebilling" name="calleebilling" value="{{$callhistory->calleebilling}}" >
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="cdrlevel" class="col-sm-2 control-label">Cdrlevel</label>
                <input type="text" class="form-control" id="cdrlevel" name="cdrlevel" value="{{$callhistory->cdrlevel}}" >
            </div>
            <div class="form-group col-md-6">
                <label for="subcdr_id" class="col-sm-2 control-label">Subcdr_id</label>
                <input type="text" class="form-control" id="subcdr_id" name="subcdr_id" value="{{$callhistory->subcdr_id}}" >
            </div>
        </div>
    </form>
</div>