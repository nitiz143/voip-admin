@extends('layouts.dashboard')

@section('content')
<div class="content-wrapper mt-3">
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
                <div class="col-md-12">
                            <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <div class="card-title">
                                Lead Information
                            </div>
                            <div class="card-options float-right">
                                <a href="#" class=" float-end" data-rel="collapse" onclick="myFunction()"><i class="fas fa-angle-down"></i></a>
                            </div>
                        </div>
                        <div class="card-body"  id="myDIV">
                            <div class="form-group">
                            
                            </div>
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label>Lead Owner</label>
                                        <p>{{!empty($data->role) ? $data->role :"NA"}}</p>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label for="company">Company</label>
                                        <p>{{!empty($crm->company) ? $crm->company :"NA"}}</p>
                                    </div>
                                </div>
                            </div>
                            <!----------2 row ---------->
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label for="firstname">First Name</label>
                                        <p>{{!empty($crm->firstname) ? $crm->firstname :"NA"}}</p>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label for="lastname">Last Name</label>
                                        <p>{{!empty($crm->lastname) ? $crm->lastname :"NA"}}</p>
                                    </div>
                                </div>
                            </div>
                                <!----------3 row ---------->
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <p>{{!empty($crm->email) ? $crm->email :"NA"}}</p>
                                    </div>
                                </div>
                                <div class="col-xl-6">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <div class="card-title">
                               Comments
                            </div>
                            <div class="card-options float-right">
                                <a href="#" class=" float-end" data-rel="collapse" onclick="myFunction1()"><i class="fas fa-angle-down"></i></a>
                            </div>
                        </div>
                        <div id="myDIV1">
                            <form action="{{route('crm.commentstore',$crm->id)}}" method="POST" id="form">
                                @csrf
                                <div class="card-body" >
                                    <div class="row">
                                        <label> Comment</label>
                                        <textarea name="comment"></textarea>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" id="submit" class="btn btn-primary">Submit</button>
                                    <button type="button" id="cancel" class="btn btn-danger">Cancel</button>
                                </div>
                            </form>
                            @if($comments->isNotEmpty())
                                <div class="card-body" >
                                    <label>Related Comments</label>
                                    @foreach ($comments as $comment)
                                        <hr>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-group d-flex">
                                                    <label>Commented By:-</label>
                                                    <p>{{$comment->user->name}}</p>
                                                </div>
                                                <div class="form-group d-flex">
                                                    <label for="company">Role:-</label>
                                                    <p>{{$comment->user->role}}</p>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group d-flex">
                                                    <label>Email:-</label>
                                                    <p>{{$comment->user->email}}</p>
                                                </div>
                                                <div class="form-group d-flex">
                                                    <label for="company">Created_at:-</label>
                                                    <p>{{$comment->created_at}}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <textarea name="comment" readonly>{{$comment->comment}}</textarea>
                                        </div><br>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
@section('page_js')
<script>
function save(formdata, url,method) {
    $('#global-loader').show();
    $.ajax({
        data: formdata,
        url: url,
        type: method,
        dataType: 'json',
        success: function (resp) {
            $('#global-loader').hide();
            if (resp.success == false) {
                $.each(resp.errors, function (k, e) {
                    $.notify(e, 'error');
                });
            }
            else {
                $.notify(resp.message, 'success');
                setTimeout(function () {
                    window.location.reload();
                }, 3000);
            }
        }, error: function (r) {
            $('#global-loader').hide();
            $.each(r.responseJSON.errors, function (k, e) {
                $.notify(e, 'error');
            });
            $('.blocker').hide();
        }
    });
}

$('#submit').click(function (e) {
    e.preventDefault();
    let formdata = $('#form').serialize();
    let url =   $('#form').attr('action');
    let method =   $('#form').attr('method');
    save(formdata,url,method);

});

function myFunction() {
  var x = document.getElementById("myDIV");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}

function myFunction1() {
  var x = document.getElementById("myDIV1");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}
</script>
@endsection
