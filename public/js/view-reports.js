$(document).on("click", "#view_data",function(e) {
    e.preventDefault();
    var $searchFilter = {};
    var ref_this = $("ul.nav li.nav-item a.nav-link.active");
    if(ref_this.data('id') == 1){
      
        $searchFilter.Account = $("#cdr_filter select[name='AccountID']").val();
        $searchFilter.Report = $("#cdr_filter select[name='report']").val();
        $searchFilter.billingtype = $("#cdr_filter select[name='billingtype']").val();
        $searchFilter.StartDate = $("#cdr_filter input[name='StartDate']").val();
        $searchFilter.EndDate = $("#cdr_filter input[name='EndDate']").val();
        if(typeof $searchFilter.StartDate  == 'undefined' || $searchFilter.StartDate.trim() == ''){
            $.notify("Please Select a Start date", "Error");
            return false;
        }
        if(typeof $searchFilter.EndDate  == 'undefined' || $searchFilter.EndDate.trim() == ''){
            $.notify("Please Select a End date", "Error");
            return false;
        }
        if(typeof $searchFilter.Report  == 'undefined' || $searchFilter.Report.trim() == ''){
            $.notify("Please Select a Report", "Error");
            return false;
        }
        if(typeof $searchFilter.Account  == 'undefined' || $searchFilter.Account.trim() == ''){
            $.notify("Please Select a Account", "Error");
            return false;
        }
        $searchFilter.ActiveTab = $("#cdr_filter input[name='ActiveTab']").val();
        $searchFilter.type = "Customer";

    }
    if(ref_this.data('id') == 2){
       
        $searchFilter.Account = $("#cdr_filter_1 select[name='AccountID']").val();
        $searchFilter.Report = $("#cdr_filter_1 select[name='report']").val();
        $searchFilter.billingtype = $("#cdr_filter_1 select[name='billingtype']").val();
        $searchFilter.StartDate = $("#cdr_filter_1 input[name='StartDate']").val();
        $searchFilter.EndDate = $("#cdr_filter_1 input[name='EndDate']").val();
        if(typeof $searchFilter.StartDate  == 'undefined' || $searchFilter.StartDate.trim() == ''){
            $.notify("Please Select a Start date", "Error");
            return false;
        }
        if(typeof $searchFilter.EndDate  == 'undefined' || $searchFilter.EndDate.trim() == ''){
            $.notify("Please Select a End date", "Error");
            return false;
        }
        if(typeof $searchFilter.Report  == 'undefined' || $searchFilter.Report.trim() == ''){
            $.notify("Please Select a Report", "Error");
            return false;
        }
        if(typeof $searchFilter.Account  == 'undefined' || $searchFilter.Account.trim() == ''){
            $.notify("Please Select a Account", "Error");
            return false;
        }
        $searchFilter.ActiveTab = $("#cdr_filter_1 input[name='ActiveTab']").val();
        $searchFilter.type = "Vendor";
    }
    $.ajax({
        url: "/csv_view_rebuild",
        data:{
            'Account': $searchFilter.Account,
            'billingtype': $searchFilter.billingtype,
            'StartDate' : $searchFilter.StartDate ,
            'EndDate' : $searchFilter.EndDate,
            'Report' :  $searchFilter.Report,
            'ActiveTab' :  $searchFilter.ActiveTab,
            'type' : $searchFilter.type 
        },
        type: 'get',
        success: function (data)
        {
            console.log('data', data)
            // $('#tables_data_1').html();
            // if(ref_this.data('id') == 2){
            //     $('#tables_data_1').html(data)
            // }   
            // if(ref_this.data('id') == 1){
            //     $('#tables_data').html(data)
            // }                  
        },

        error: function(xhr) {
        $('#global-loader').hide();
            // $.notify(xhr.responseText,'error'); // this line will save you tons of hours while debugging
            // do something here because of error
        }
    });
});