<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="panel panel-primary">
                            <div class=" tab-menu-heading">
                                <div class="tabs-menu1">
                                    <!-- Tabs -->
                                    <ul class="nav nav-tabs gap-3">
                                        <li class="nav nav-tabs">
                                            <a href="" data-bs-toggle="nav-link" id="Country" data-name="Country"  class="nav-link"> Block  By Country</a>
                                        </li>
                                        <li class="nav nav-tabs">
                                            <a href="" data-bs-toggle="nav-link" id="Code"  data-name="Code" class="nav-link">Block By Code</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="panel-body tabs-menu-body">
                                <div class="tab-content">
                                    <div class="tab-pane" id="tab">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>

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

$(document).ready(function(){
    $("#Country").click(function(e){
        e.preventDefault();
        $('#tab').show();
        $('#Country').addClass('active');
        $('#Code').removeClass('active');
        var name = $(this).data('name');
        $.ajax({
            url:"{{route('Vendors')}}",
            method:"get",
            data:{'name':name , 'id':"{{request()->id}}"},
            success:function(data){
                $('#tab').html(data);
            }
        });
    });
    $("#Code").click(function(e){
        e.preventDefault();
        $('#tab').show();
        $('#Code').addClass('active');
        $('#Country').removeClass('active');
        var name = $(this).data('name');
        $.ajax({
            url:"{{route('Vendors')}}",
            method:"get",
            data:{'name':name , 'id':"{{request()->id}}"},
            success:function(data){
                $('#tab').html(data);
            }
        });
    });
});
$(document).ready(function(){
    $('#tab').show();
    $('#Country').addClass('active');
    $('#Code').removeClass('active');
     var name = $('#Country').data('name');
    $.ajax({
        url:"{{route('Vendors')}}",
        method:"get",
        data:{'name':name , 'id':"{{request()->id}}"},
        success:function(data){
            $('#tab').html(data);
        }
    });
}); 
</script>
