@extends('layouts.dashboard')

@section('content')
<div class="content-wrapper mt-3">
    <section class="content-header">
        <div class="container">
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
        <div class="container">
            <div class="row">
                <div class="col-md-12">
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
                                 Comment
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
                                        <textarea name="comment" id="summernote"></textarea>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" id="submit" class="btn btn-primary">Submit</button>
                                    <button type="button" id="cancel" class="btn btn-danger">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if($comments->isNotEmpty())
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <div class="card-title">
                                    Related  Comments
                                </div>
                                <div class="card-options float-right">
                                    <a href="#" class=" float-end" data-rel="collapse" onclick="myFunction2()"><i class="fas fa-angle-down"></i></a>
                                </div>
                            </div>
                            <div id="myDIV2">
                                @foreach ($comments as $comment)
                                    {{-- <hr style="height: 0.2px; margin-top: 0px;"> --}}
                                    <div class="card-body ">
                                        <div class="card " style="background-color:#f4f6f9">
                                            <div class="row col-12 mb-2 mt-3">
                                                <div class="col-2 col-md-1 ">
                                                    <div class="row">
                                                        <div class="ticket-sender-picture img-shadow">
                                                            <img src="{{asset('assets/dist/img/user.png')}}" alt="image" height="55px" class="rounded-circle center">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-2 col-md-5">
                                                    <div class="row mt-3">
                                                        <div class="form-group d-flex ">
                                                            <label for="lastname">By:</label>&nbsp;
                                                            <p>{{$comment->user->role}}&nbsp;*&nbsp;{{$comment->created_at->diffForHumans()}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="row">
                                                    <section class="content mb-3 mt-3">
                                                        <hr style="height: 0.2px; margin-top: 0px;">
                                                        {!!  $comment->comment !!}
                                                        <hr style="height: 0.2px;"/>
                                                    </section>
                                                </div>
                                            </div>
                                            <div class="col-2 col-md-2">
                                                <div class="row mt-3">
                                                    <span class="form-group d-flex ">
                                                        <label for="lastname">Name:-</label>&nbsp;
                                                        <p>{{$comment->user->name}}</p>
                                                    </span>
                                                    <span class="form-group d-flex">
                                                        <label for="lastname">Email:-</label>&nbsp;
                                                        <p>{{$comment->user->email}}</p>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
</div>
@endsection
@section('page_js')
<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            height: 300,
            popover: {
                image: [
                    ['image', ['resizeFull', 'resizeHalf', 'resizeQuarter', 'resizeNone']],
                    ['float', ['floatLeft', 'floatRight', 'floatNone']],
                    ['remove', ['removeMedia']]
                ],
            }
        });

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
    function myFunction2() {
        var x = document.getElementById("myDIV2");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }
</script>
@endsection
