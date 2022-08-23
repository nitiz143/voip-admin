$(document).ready(function () {
    $('.selectpicker').timezones();
});

$('#switch').change(function () {
    if (this.checked) {
        $(".product").removeClass('d-none');
    } else {
        $(".product").addClass('d-none');
    }
});

$('#number_only').bind('keyup paste', function () {
    this.value = this.value.replace(/[^0-9]/g, '');
});

$("#billing_cycle").change(function () {
    $('.billing_cycle_startday').val('');
    if ($(this).val() == 'weekly') {
        $('#week').removeClass('d-none');
        $('#in_specific_days').addClass('d-none');
        $('#monthly_anniversary').addClass('d-none');

    } else if ($(this).val() == 'monthly') {
        $('#week').addClass('d-none');
        $('#in_specific_days').addClass('d-none');
        $('#monthly_anniversary').addClass('d-none');

    } else if ($(this).val() == 'yearly') {
        $('#week').addClass('d-none');
        $('#in_specific_days').addClass('d-none');
        $('#monthly_anniversary').addClass('d-none');

    } else if ($(this).val() == 'in_specific_days') {
        $('#week').addClass('d-none');
        $('#monthly_anniversary').addClass('d-none');
        $('#in_specific_days').removeClass('d-none');
    } else if ($(this).val() == 'monthly_anniversary') {
        $('#week').addClass('d-none');
        $('#in_specific_days').addClass('d-none');
        $('#monthly_anniversary').removeClass('d-none');
    } else {
        $('#week').addClass('d-none');
        $('#in_specific_days').addClass('d-none');
        $('#monthly_anniversary').addClass('d-none');
    }
});

$("#cancel").click(function(){
    location.reload();
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
                    if (resp.redirect_url) {
                        window.location.href = resp.redirect_url;
                    }
                }, 1000);
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

$("#customer_authentication_rule").change(function() {
    if($(this).val() == 6){
        $('#customer_authentication_value_div').removeClass('d-none');
    }else{
        $('#customer_authentication_value_div').addClass('d-none');
    }
});

$("#vendor_authentication_rule").change(function() {
    if($(this).val() == 6){
        $('#vendor_authentication_value_div').removeClass('d-none');
    }else{
        $('#vendor_authentication_value_div').addClass('d-none');
    }
});