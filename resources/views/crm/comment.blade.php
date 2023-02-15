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
                                {{-- <!----------4 row ---------->
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <p>{{!empty($crm->phone) ? $crm->phone :"NA"}}</p>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label for="fax">Fax</label>
                                        <p>{{!empty($crm->fax) ? $crm->fax :"NA"}}</p>
                                    </div>
                                </div>
                            </div>
                            <!----------5 row ---------->
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label for="mobile">Mobile</label>
                                        <p>{{!empty($crm->mobile) ? $crm->mobile :"NA"}}</p>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label for="website">Website</label>
                                        <p>{{!empty($crm->website) ? $crm->website :"NA"}}</p>
                                    </div>
                                </div>
                            </div>
                            <!----------5 row ---------->
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label>Lead Source</label>
                                        <p>{{!empty($crm->lead_source) ? $crm->lead_source :"NA"}}</p>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label >Lead Status</label>
                                        <p>{{!empty($crm->lead_status) ? $crm->lead_status :"NA"}}</p>
                                    </div>
                                </div>
                            </div>
                            <!----------6 row ---------->
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label for="rating">Rating</label>
                                        <p>{{!empty($crm->rating) ? $crm->rating :"NA"}}</p>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label for="employee">No. of Employees</label>
                                        <p>{{!empty($crm->employee) ? $crm->employee :"NA"}} </p>
                                    </div>
                                </div>
                            </div>
                            <!----------7 row ---------->
                            <div class="row">
                                <div class="col-xl-6">

                                </div>
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label for="skype_id">Skype ID</label>
                                        <p>{{!empty($crm->skype_id) ? $crm->skype_id  :"NA"}} </p>
                                    </div>
                                </div>
                            </div>
                            <!----------8 row ---------->
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <p>{{!empty($crm->status) ? $crm->status  :"NA"}} </p>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label for="vat_number">VAT Number</label>
                                        <p> {{!empty($crm->vat_number) ? $crm->vat_number  :"NA"}}</p>
                                    </div>
                                </div>
                            </div>
                            <!----------9 row ---------->
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <p>{{!empty($crm->description) ? $crm->description  :"NA"}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-body">
                            <h4>Address</h4>
                            <hr>
                                <!-------- address row1---------->
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label for="address_line1">Address Line1</label>
                                        <p>{{!empty($crm->address_line1) ? $crm->address_line1  :"NA"}}</p>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <p>{{!empty($crm->city) ? $crm->city  :"NA"}}</p>
                                    </div>
                                </div>
                            </div>
                            <!-------- address row2--------->
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label for="address_line2">Address Line2</label>
                                        <p>{{!empty($crm->address_line2 ) ? $crm->address_line2  :"NA"}}</p>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label for="postzip">PostZip Code</label>
                                        <p>{{!empty($crm->postzip ) ? $crm->postzip  :"NA"}}</p>
                                    </div>
                                </div>
                            </div>
                                <!-------- address row3--------->
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label for="address_line3">Address Line3</label>
                                        <p>{{!empty($crm->address_line3) ? $crm->address_line3 :"NA"}}</p>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label for="country">Country</label>
                                        <p>{{!empty($crm->country) ? $crm->country :"NA"}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            
                        </div> --}}
                       
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-md-12">
                            <!-- general form elements -->
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
