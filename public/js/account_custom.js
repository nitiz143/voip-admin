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
    var start_date = $('.billing_start_date').val();
    $('.billing_cycle_startday').val('');
    if ($(this).val() == 'weekly') {
        $('.next_invoice_date').val('');
        $('#next_charge_date').val('');
        $('#week').removeClass('d-none');
        $('#in_specific_days').addClass('d-none');
        $('#monthly_anniversary').addClass('d-none');

        $(".billing_cycle_startday").change(function () {
           
            var data =$(this).find(':selected').attr('data-id');
            var a = new Date(start_date);
            if(a.getDay() == data ){
                //for invoice date
                const next_date = new Date(start_date);
                next_date.setDate(next_date.getDate() + 7);
                console.log(next_date)
                var date = moment(next_date).format("YYYY-MM-DD");  
                $('.next_invoice_date').val(date);

                //for next charge date
                const charge_date = new Date(start_date);
                charge_date.setDate(charge_date.getDate() + 6);
                var chargedate = moment(charge_date).format("YYYY-MM-DD");  
                $('#next_charge_date').val(chargedate);
            }
            else if(data < a.getDay()){
               
                //for invoice date
                const next_date = new Date(start_date);
                next_date.setDate(next_date.getDate() + (data - 1 - next_date.getDay() + 7) % 7 + 1);
                var date = moment(next_date).format("YYYY-MM-DD");  
                console.log(date)
                $('.next_invoice_date').val(date);

                //for next charge date
               
                const charge_date = new Date(date);
                charge_date.setDate(charge_date.getDate() -  1);
                var chargedate = moment(charge_date).format("YYYY-MM-DD");  
                $('#next_charge_date').val(chargedate);
            }
            else{
                var count = data - a.getDay();

                //for invoice date
                const next_date = new Date(start_date);
                next_date.setDate(next_date.getDate() + count);
                console.log(next_date)
                var date = moment(next_date).format("YYYY-MM-DD");  
                $('.next_invoice_date').val(date);

                //for next charge date
                count = count-1;
                const charge_date = new Date(start_date);
                charge_date.setDate(charge_date.getDate() + count);
                var chargedate = moment(charge_date).format("YYYY-MM-DD");  
                $('#next_charge_date').val(chargedate);
            }
        });

    } else if ($(this).val() == 'monthly') {
        $('.next_invoice_date').val('');
        $('#next_charge_date').val('');
        $('#week').addClass('d-none');
        $('#in_specific_days').addClass('d-none');
        $('#monthly_anniversary').addClass('d-none');

        //for invoice date
        const next_date = new Date(start_date);
        next_date.setMonth(next_date.getMonth() + 1, 1);
        var date = moment(next_date).format("YYYY-MM-DD");  
        $('.next_invoice_date').val(date);

        //for next charge date
        const charge_date = new Date(date);
        charge_date.setDate(charge_date.getDate() - 1);
        var chargedate = moment(charge_date).format("YYYY-MM-DD");  
        $('#next_charge_date').val(chargedate);

    } else if ($(this).val() == 'yearly') {
        $('.next_invoice_date').val('');
        $('#next_charge_date').val('');
        $('#week').addClass('d-none');
        $('#in_specific_days').addClass('d-none');
        $('#monthly_anniversary').addClass('d-none');

        //for invoice date
        const next_date = new Date(start_date);
        next_date.setYear(next_date.getFullYear() + 1);
        var date = moment(next_date).format("YYYY-MM-DD");  
        $('.next_invoice_date').val(date);

        //for next charge date
        const charge_date = new Date(start_date);
        charge_date.setYear(charge_date.getFullYear() + 1);
        var charge_year_date =  new Date(moment(charge_date));  
        charge_year_date.setDate(charge_year_date.getDate() - 1);
        var chargeyeardate = moment(charge_year_date).format("YYYY-MM-DD");  
        $('#next_charge_date').val(chargeyeardate);

    }else if($(this).val() == 'daily') {
        $('.next_invoice_date').val('');
        $('#next_charge_date').val('');
        $('#week').addClass('d-none');
        $('#in_specific_days').addClass('d-none');
        $('#monthly_anniversary').addClass('d-none');

     
        const start_date1 = new Date(start_date);
        start_date1.setDate(start_date1.getDate() + 1);
        var date = moment(start_date1).format("YYYY-MM-DD");  
        $('.next_invoice_date').val(date);
        $('#next_charge_date').val(start_date);

    }
    else if($(this).val() == 'quarterly') {
        $('.next_invoice_date').val('');
        $('#next_charge_date').val('');
        $('#week').addClass('d-none');
        $('#in_specific_days').addClass('d-none');
        $('#monthly_anniversary').addClass('d-none');
        var date = new Date(start_date);    
        var firstday = new Date(date.getFullYear(),0,1); // XXXX/01/01
        var diff = Math.ceil((date - firstday) / 86400000); 
        quarter =  parseInt( diff / ( 365/ 4 )) + 1 
        if (quarter == 1)
        {
            date.setMonth(3, 1);
            var quarter1 = moment(date).format("YYYY-MM-DD");  
            $('.next_invoice_date').val(quarter1);

            var next_charge_date = new Date(quarter1);
            next_charge_date.setDate(next_charge_date.getDate() - 1);
            var quarter2 = moment(next_charge_date).format("YYYY-MM-DD");  
            $('#next_charge_date').val(quarter2);
        }
        if (quarter == 2)
        {
            date.setMonth(6, 1);
            var quarter1 = moment(date).format("YYYY-MM-DD");  
            $('.next_invoice_date').val(quarter1);

            var next_charge_date = new Date(quarter1);
            next_charge_date.setDate(next_charge_date.getDate() - 1);
            var quarter2 = moment(next_charge_date).format("YYYY-MM-DD");  
            $('#next_charge_date').val(quarter2);
        }
        if (quarter == 3)
        {
            date.setMonth(9, 1);
            var quarter1 = moment(date).format("YYYY-MM-DD");  
            $('.next_invoice_date').val(quarter1);

            var next_charge_date = new Date(quarter1);
            next_charge_date.setDate(next_charge_date.getDate() - 1);
            var quarter2 = moment(next_charge_date).format("YYYY-MM-DD");  
            $('#next_charge_date').val(quarter2);
        }
        if (quarter == 4)
        {
            date.setMonth(12, 1);
            var quarter1 = moment(date).format("YYYY-MM-DD");  
            $('.next_invoice_date').val(quarter1);

            var next_charge_date = new Date(quarter1);
            next_charge_date.setDate(next_charge_date.getDate() - 1);
            var quarter2 = moment(next_charge_date).format("YYYY-MM-DD");  
            $('#next_charge_date').val(quarter2);
            
        }
    }
    else {
        $('.next_invoice_date').val('');
        $('#next_charge_date').val('');
        $('#week').addClass('d-none');
        $('#in_specific_days').addClass('d-none');
        $('#monthly_anniversary').addClass('d-none');
        
    }
});



$(document.body).on('change',".billing_start_date",function (e) {
    var bill =  $("#billing_cycle").val();
   if(bill != ""){
        if(bill == "daily"){
            const start_date1 = new Date($(this).val());
            start_date1.setDate(start_date1.getDate() + 1);
            var date = moment(start_date1).format("YYYY-MM-DD");  
            $('.next_invoice_date').val(date);
            $('#next_charge_date').val($(this).val());
        }

        //quarterly
        if(bill == "quarterly"){
            $('.next_invoice_date').val('');
            $('#next_charge_date').val('');
            $('#week').addClass('d-none');
            $('#in_specific_days').addClass('d-none');
            $('#monthly_anniversary').addClass('d-none');
            var date = new Date($(this).val());    
            var firstday = new Date(date.getFullYear(),0,1); // XXXX/01/01
            var diff = Math.ceil((date - firstday) / 86400000); 
            // a quarter is about 365/4 
            quarter =  parseInt( diff / ( 365/ 4 )) + 1 
            if (quarter == 1)
            {
                date.setMonth(3, 1);
                var quarter1 = moment(date).format("YYYY-MM-DD");  
                $('.next_invoice_date').val(quarter1);
    
                var next_charge_date = new Date(quarter1);
                next_charge_date.setDate(next_charge_date.getDate() - 1);
                var quarter2 = moment(next_charge_date).format("YYYY-MM-DD");  
                $('#next_charge_date').val(quarter2);
            }
            if (quarter == 2)
            {
                date.setMonth(6, 1);
                var quarter1 = moment(date).format("YYYY-MM-DD");  
                $('.next_invoice_date').val(quarter1);
    
                var next_charge_date = new Date(quarter1);
                next_charge_date.setDate(next_charge_date.getDate() - 1);
                var quarter2 = moment(next_charge_date).format("YYYY-MM-DD");  
                $('#next_charge_date').val(quarter2);
            }
            if (quarter == 3)
            {
                date.setMonth(9, 1);
                var quarter1 = moment(date).format("YYYY-MM-DD");  
                $('.next_invoice_date').val(quarter1);
    
                var next_charge_date = new Date(quarter1);
                next_charge_date.setDate(next_charge_date.getDate() - 1);
                var quarter2 = moment(next_charge_date).format("YYYY-MM-DD");  
                $('#next_charge_date').val(quarter2);
            }
            if (quarter == 4)
            {
                date.setMonth(12, 1);
                var quarter1 = moment(date).format("YYYY-MM-DD");  
                $('.next_invoice_date').val(quarter1);
    
                var next_charge_date = new Date(quarter1);
                next_charge_date.setDate(next_charge_date.getDate() - 1);
                var quarter2 = moment(next_charge_date).format("YYYY-MM-DD");  
                $('#next_charge_date').val(quarter2);
                
            }
        }
        
        if(bill == "fortnightly"){
            var date = new Date($(this).val());
            next_date = new Date(date.getFullYear(), date.getMonth(), 16)
            if(date < next_date){
                 //for invoice date
                var date1 = moment(next_date).format("YYYY-MM-DD");  
                $('.next_invoice_date').val(date1);
    
                //for invoice date
                const charge_date = new Date(date1);
                charge_date.setDate(charge_date.getDate() - 1);
                var chargedate = moment(charge_date).format("YYYY-MM-DD");  
                $('#next_charge_date').val(chargedate);
            }
            if(date >= next_date){
               
                next_date.setMonth(next_date.getMonth() + 1, 1);
                var date = moment(next_date).format("YYYY-MM-DD");  
                $('.next_invoice_date').val(date);
        
                //for next charge date
                const charge_date = new Date(date);
                charge_date.setDate(charge_date.getDate() - 1);
                var chargedate = moment(charge_date).format("YYYY-MM-DD");  
                $('#next_charge_date').val(chargedate);
            }
        }
        if(bill == "monthly"){
             //for invoice date
            const next_date = new Date($(this).val());
            next_date.setMonth(next_date.getMonth() + 1, 1);
            var date = moment(next_date).format("YYYY-MM-DD");  
            $('.next_invoice_date').val(date);

            //for next charge date
            const charge_date = new Date(date);
            charge_date.setDate(charge_date.getDate() - 1);
            var chargedate = moment(charge_date).format("YYYY-MM-DD");  
            $('#next_charge_date').val(chargedate);
        }
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
    if($(this).val() == 6 || $(this).val() == 2 || $(this).val() == 3){
        $('#customer_authentication_value_div').removeClass('d-none');
    }else{
        $('#customer_authentication_value_div').addClass('d-none');
    }
});

$("#vendor_authentication_rule").change(function() {
    if($(this).val() == 6 || $(this).val() == 2 || $(this).val() == 3){
        $('#vendor_authentication_value_div').removeClass('d-none');
    }else{
        $('#vendor_authentication_value_div').addClass('d-none');
    }
});