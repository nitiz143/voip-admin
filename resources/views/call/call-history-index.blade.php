@extends('layouts.dashboard')

@section('content')
<div class="content-wrapper mt-3" style="width:max-content">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">

                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">

                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>


        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="">
                        <div class="card">
                            <div class="card-header">
                                <h1 class="card-title ">Call History</h1>
                                <a href="{{ route('call.create') }}" class="btn btn-primary mb-4 float-right w-10" id="createzoneModal">Import</a>
                            </div>
                            {{-- <div class="form-inline d-flex justify-content-end mt-2 mr-4">
                                <div class="input-group" data-widget="sidebar-search">
                                  <input class="form-control search" type="text" value="" placeholder="Search">
                                </div>
                            </div> --}}
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table class="table table-bordered ">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Caller_id</th>
                                        <th>Callere164</th>
                                        <th>Calleraccesse164</th>
                                        <th>Calleee164</th>
                                        <th>Calleeaccesse164</th>
                                        <th>Callerip</th>
                                        <th>Callergatewayh323id</th>
                                        <th>Callerproductid</th>
                                        <th>Callertogatewaye164</th>
                                        <th>Calleeip</th>
                                        <th>Calleegatewayh323id</th>
                                        <th>Calleeproductid</th>
                                        <th>Calleetogatewaye164</th>
                                        <th>Billingmode</th>
                                        <th>Calllevel</th>
                                        <th>Agentfeetime</th>
                                        <th>Starttime</th>
                                        <th>Stoptime</th>
                                        <th>Pdd</th>
                                        <th>Holdtime</th>
                                        <th>Feeprefix</th>
                                        <th>Feetime</th>
                                        <th>Fee</th>
                                        <th>Suitefee</th>
                                        <th>Suitefeetime</th>
                                        <th>Incomefee</th>
                                        <th>Customername</th>
                                        <th>Agentfeeprefix</th>
                                        <th>Agentfee</th>
                                        <th>Agentsuitefee</th>
                                        <th>Agentsuitefeetime</th>
                                        <th>Agentaccount</th>
                                        <th>Agentname</th>
                                        <th>Flowno</th>
                                        <th>Softswitchdn</th>
                                        <th>Enddirection</th>
                                        <th>Endreason</th>
                                        <th>Calleebilling</th>
                                        <th>Cdrlevel</th>
                                        <th>Subcdr_id</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody class="Data">
                                        @foreach($calls as $call)
                                        <tr>
                                            <td>{{$call->id}}</td>
                                            <td>{{$call->caller_id}}</td>
                                            <td>{{$call->callere164}}</td>
                                            <td>{{$call->calleraccesse164}}</td>
                                            <td>{{$call->calleee164}}</td>
                                            <td>{{$call->calleeaccesse164}}</td>
                                            <td>{{$call->callerip}}</td>
                                            <td>{{$call->callergatewayh323id}}</td>
                                            <td>{{$call->callerproductid}}</td>
                                            <td>{{$call->callertogatewaye164}}</td>
                                            <td>{{$call->calleeip}}</td>
                                            <td>{{$call->calleegatewayh323id}}</td>
                                            <td>{{$call->calleeproductid}}</td>
                                            <td>{{$call->calleetogatewaye164}}</td>
                                            <td>{{$call->billingmode}}</td>
                                            <td>{{$call->calllevel}}</td>
                                            <td>{{$call->agentfeetime}}</td>
                                            <td>{{$call->starttime}}</td>
                                            <td>{{$call->stoptime}}</td>
                                            <td>{{$call->pdd}}</td>
                                            <td>{{$call->holdtime}}</td>
                                            <td>{{$call->feeprefix}}</td>
                                            <td>{{$call->feetime}}</td>
                                            <td>{{$call->fee}}</td>
                                            <td>{{$call->suitefee}}</td>
                                            <td>{{$call->suitefeetime}}</td>
                                            <td>{{$call->incomefee}}</td>
                                            <td>{{$call->customername}}</td>
                                            <td>{{$call->agentfeeprefix}}</td>
                                            <td>{{$call->agentfee}}</td>
                                            <td>{{$call->agentsuitefee}}</td>
                                            <td>{{$call->agentsuitefeetime}}</td>
                                            <td>{{$call->agentaccount}}</td>
                                            <td>{{$call->agentname}}</td>
                                            <td>{{$call->flowno}}</td>
                                            <td>{{$call->softswitchdn}}</td>
                                            <td>{{$call->enddirection}}</td>
                                            <td>{{$call->endreason}}</td>
                                            <td>{{$call->calleebilling}}</td>
                                            <td>{{$call->cdrlevel}}</td>
                                            <td>{{$call->subcdr_id}}</td>
                                            <td><a href="#" class="delete btn btn-primary btn-sm Edit"  data-id ="{{$call->id}}">Edit</a>&nbsp;&nbsp;<a href="javascript:void(0)" class="delete btn btn-danger btn-sm Delete"  data-id ="{{$call->id}}">Delete</a></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end mr-4">
                                {!! $calls->links() !!}
                            </div>
                            <!-- /.card-body -->
                        </div>
            <!-- /.card -->
                    </div>
                </div>
            </div>
        </section>
</div>
@endsection
@section('page_js')
<script>

$(".Delete").click(function(){
    var id = $(this).data("id");
    var token = "{{ csrf_token() }}";
if(confirm("Are You sure want to delete !") == true){

    $.ajax(
    {
        url: "{{ route('call.destroy', ": id ") }}",
        type: 'delete',
         dataType: "JSON",
        data: {
            "id": id,
            "_token": token,
        },
        success: function (resp){
            $.notify(resp.message, 'success');
            setTimeout(function () {
                location.reload();
                 }, 1000);
        },
            error: function (resp) {
                console.log('Error:', resp);
            }
    });
}else{
    $.notify('Your data is not delete','success');
}

});
</script>

@endsection
