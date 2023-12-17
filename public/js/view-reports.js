// var table = $('#tables_data').DataTable({
//     processing: true,
//     serverSide: true,
//     ajax: {
//       url: "{{ url('csv_view_rebuild') }}",
//         // data: function (d) {
//         //     d.status = $('#status').val(),
//         //     d.search = $('input[type="search"]').val()
//         // }
//     },
//     columns: [
//         {data: 'id', name: 'id'},
//         // {data: 'name', name: 'name'},
//         // {data: 'email', name: 'email'},
//         // {data: 'status', name: 'status'},
//     ]
// });


// $.ajax({
//     url: "/csv_view_rebuild",
//     data:{
//         'Account': $searchFilter.Account,
//         'billingtype': $searchFilter.billingtype,
//         'StartDate' : $searchFilter.StartDate ,
//         'EndDate' : $searchFilter.EndDate,
//         'Report' :  $searchFilter.Report,
//         'ActiveTab' :  $searchFilter.ActiveTab,
//         'type' : $searchFilter.type 
//     },
//     type: 'get',
//     success: function (data)
//     {
//         console.log('data', data)
//         // $('#tables_data_1').html();
//         // if(ref_this.data('id') == 2){
//         //     $('#tables_data_1').html(data)
//         // }   
//         // if(ref_this.data('id') == 1){
//         //     $('#tables_data').html(data)
//         // }                  
//     },

//     error: function(xhr) {
//     $('#global-loader').hide();
//         // $.notify(xhr.responseText,'error'); // this line will save you tons of hours while debugging
//         // do something here because of error
//     }
// });




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
    let tableName = '';
    let columnData = [];
    if($searchFilter.Report == 'Customer-Summary'){
        tableName = "customer_summary";
        columnData = [
            {data :'customerAccountCode' , name : 'customerAccountCode'},
            {data :'customer' , name : 'customer'},
            {data :'custDestination' , name : 'custDestination'},
            {data :'attempts' , name : 'attempts'},
            {data :'completed' , name : 'completed'},
            {data :'asr' , name : 'asr'},
            {data :'acd_sec' , name : 'acd_sec'},
            {data :'raw_Dur' , name : 'raw_Dur'},
            {data :'rnd_Dur' , name : 'rnd_Dur'},
            {data :'revenue' , name : 'revenue'},
            {data :'revenue_min' , name : 'revenue_min'},
            {data :'margin' , name : 'margin'},
            {data :'margin_min' , name : 'margin_min'},
            {data :'margin_percent' , name : 'margin_percent'},
            {data :'cstProductGroup' , name : 'cstProductGroup'},
            {data :'vendProductGroup' , name : 'vendProductGroup'},
        ];
    }
    else if($searchFilter.Report == 'Customer-Hourly'){
        tableName = "customer_hourly";
        columnData  = [
            { data : 'customerAccountCode', name : 'customerAccountCode'},
            { data : 'customer', name : 'customer'},
            { data : 'custDestination', name : 'custDestination'},
            { data : 'attempts', name : 'attempts'},
            { data : 'completed', name : 'completed'},
            { data : 'asr', name : 'asr'},
            { data : 'acd_sec', name : 'acd_sec'},
            { data : 'raw_Dur', name : 'raw_Dur'},
            { data : 'rnd_Dur', name : 'rnd_Dur'},
            { data : 'revenue', name : 'revenue'},
            { data : 'revenue_min', name : 'revenue_min'},
            { data : 'margin', name : 'margin'},
            { data : 'margin_min', name : 'margin_min'},
            { data : 'margin_percent', name : 'margin_percent'},
            { data : 'cstProductGroup', name : 'cstProductGroup'},
            { data : 'vendProductGroup', name : 'vendProductGroup'},
        ];
    }
    else if($searchFilter.Report == 'Customer-Margin-Report'){
        tableName = "customer_margin";
        columnData = [
            { data : 'customerAccountCode' , name : 'customerAccountCode' },
            { data : 'customer' , name : 'customer' },
            { data : 'custDestination' , name : 'custDestination' },
            { data : 'attempts' , name : 'attempts' },
            { data : 'completed' , name : 'completed'},
            { data : 'asr' , name : 'asr' },
            { data : 'acd' , name : 'acd' },
            { data : 'raw_dur' , name : 'raw_dur' },
            { data : 'rnd_dur' , name : 'rnd_dur' },
            { data : 'revenue' , name : 'revenue' },
            { data : 'revenue_min' , name : 'revenue_min' },
            { data : 'margin' , name : 'margin' },
            { data : 'margin_min' , name : 'margin_min' },
            { data : 'margin_percent' , name : 'margin_percent' },
            { data : 'cstProductGroup' , name : 'custProductGroup' },
            { data : 'vendProductGroup' , name : 'vendProductGroup' }
        ];
    }
    else if($searchFilter.Report == 'Customer-Negative-Report'){
        tableName = "customer_negative";

        columnData = [
            { data : 'customerAccountCode' , name : 'customerAccountCode' },
            { data : 'customer' , name : 'customer' },
            { data : 'attempts' , name : 'attempts' },
            { data : 'completed' , name : 'completed' },
            { data : 'asr' , name : 'asr' },
            { data : 'acd' , name : 'acd' },
            { data : 'raw_dur' , name : 'raw_dur' },
            { data : 'rnd_dur' , name : 'rnd_dur' },
            { data : 'revenue' , name : 'revenue' },
            { data : 'revenue_min' , name : 'revenue_min' },
            { data : 'custProductGroup' , name : 'cstProductGroup' },
            { data : 'vendProductGroup' , name : 'vendProductGroup' },
        ];
    }
    else if($searchFilter.Report == 'Account-Manage'){
        tableName = "account_manage";
        columnData = [
            { data : 'customerAccountCode' , name : 'customerAccountCode' },
            { data : 'customer' , name : 'customer' },
            { data : 'custDestination' , name : 'custDestination' },
            { data : 'vendAccountCode' , name : 'vendAccountCode' },
            { data : 'vendor' , name : 'vendor' },
            { data : 'attempts' , name : 'attempts' },
            { data : 'completed' , name : 'completed' },
            { data : 'asr' , name : 'asr' },
            { data : 'acd' , name : 'acd' },
            { data : 'raw_dur' , name : 'raw_dur' },
            { data : 'rnd_dur' , name : 'rnd_dur' },
            { data : 'revenue' , name : 'revenue' },
            { data : 'revenue_min' , name : 'revenue_min' },
            { data : 'cost' , name : 'cost' },
            { data : 'cost_min' , name : 'cost_min' },
            { data : 'margin' , name : 'margin' },
            { data : 'margin_min' , name : 'margin_min' },
            { data : 'margin_percent' , name : 'margin_percent' },
            { data : 'custProductGroup' , name : 'custProductGroup' },
            { data : 'vendProductGroup' , name : 'vendProductGroup' }
        ];

    }
    else if($searchFilter.Report == 'Customer/Vendor-Report'){
        tableName = "customer_vendor_report";
        columnData = [
            { data : 'customerAccountCode' , name : 'customerAccountCode' },
            { data : 'customer' , name : 'customer' },
            { data : 'custDestination' , name : 'custDestination' },
            { data : 'vendAccountCode' , name : 'vendAccountCode' },
            { data : 'vendor' , name : 'vendor' },
            { data : 'attempts' , name : 'attempts' },
            { data : 'completed' , name : 'completed' },
            { data : 'asr' , name : 'asr' },
            { data : 'acd' , name : 'acd' },
            { data : 'raw_dur' , name : 'raw_dur' },
            { data : 'rnd_dur' , name : 'rnd_dur' },
            { data : 'revenue' , name : 'revenue' },
            { data : 'revenue_min' , name : 'revenue_min' },
            { data : 'cost' , name : 'cost' },
            { data : 'cost_min' , name : 'cost_min' },
            { data : 'margin' , name : 'margin' },
            { data : 'margin_min' , name : 'margin_min' },
            { data : 'margin_percent' , name : 'margin_percent' },
            { data : 'custProductGroup' , name : 'custProductGroup' },
            { data : 'vendProductGroup' , name : 'vendProductGroup' },
        ];
    }
    else if($searchFilter.Report == 'Vendor-Negative-Report'){
        tableName = "vendor_negative";
        columnData = [
            { data : 'vendAccountCode' , name : 'vendAccountCode' },
            { data : 'vendor' , name : 'vendor' },
            { data : 'attempts' , name : 'attempts' },
            { data : 'completed' , name : 'completed' },
            { data : 'asr' , name : 'asr' },
            { data : 'acd' , name : 'acd' },
            { data : 'raw_dur' , name : 'raw_dur' },
            { data : 'rnd_dur' , name : 'rnd_dur' },
            { data : 'cost' , name : 'cost' },
            { data : 'cost_min' , name : 'cost_min' },
            { data : 'custProductGroup' , name : 'custProductGroup' },
            { data : 'vendProductGroup' , name : 'vendProductGroup' },
        ];   
    }
    else if($searchFilter.Report == 'Vendor-Margin-Report'){
        tableName = "vendor_margin";
        columnData = [
            { data : 'vendorAccountCode' , name : 'vendorAccountCode' }, 
            { data : 'vendor' , name : 'vendor' }, 
            { data : 'attempts' , name : 'attempts' }, 
            { data : 'completed' , name : 'completed' }, 
            { data : 'asr' , name : 'asr' }, 
            { data : 'acd' , name : 'acd' }, 
            { data : 'raw_dur' , name : 'raw_dur' }, 
            { data : 'rnd_dur' , name : 'rnd_dur' }, 
            { data : 'cost' , name : 'cost' }, 
            { data : 'cost_min' , name : 'cost_min' }, 
            { data : 'margin' , name : 'margin' }, 
            { data : 'margin_min' , name : 'margin_min' }, 
            { data : 'margin_percent' , name : 'margin_percent' }, 
            { data : 'custProductGroup' , name : 'custProductGroup' }, 
            { data : 'vendProductGroup' , name : 'vendProductGroup' }, 
        ];
    }
    else if($searchFilter.Report == 'Vendor-Summary'){
        tableName = "vendor_summary";
        columnData = [
            { data : 'vendorAccountCode', name : 'vendorAccountCode' },
            { data : 'vendor', name : 'vendor' },
            { data : 'attempts', name : 'attempts' },
            { data : 'completed', name : 'completed' },
            { data : 'asr', name : 'asr' },
            { data : 'acd', name : 'acd' },
            { data : 'raw_dur', name : 'raw_dur' },
            { data : 'rnd_Dur', name : 'rnd_Dur' },
            { data : 'cost', name : 'cost' },
            { data : 'cost_min', name : 'cost_min' },
            { data : 'margin', name : 'margin' },
            { data : 'margin_min', name : 'margin_min' },
            { data : 'margin_percent', name : 'margin_percent' },
            { data : 'custProductGroup', name : 'custProductGroup' },
            { data : 'vendProductGroup', name : 'vendProductGroup' },
        ];
    }
    else if($searchFilter.Report == 'Vendor-Hourly'){
        tableName = "vendor_hourly";
        columnData = [
            { data : 'vendorAccountCode', name : 'vendorAccountCode' },
            { data : 'vendor', name : 'vendor' },
            { data : 'attempts', name : 'attempts' },
            { data : 'completed', name : 'completed' },
            { data : 'asr', name : 'asr' },
            { data : 'acd', name : 'acd' },
            { data : 'raw_dur', name : 'raw_dur' },
            { data : 'rnd_dur', name : 'rnd_dur' },
            { data : 'cost', name : 'cost' },
            { data : 'cost_min', name : 'cost_min' },
            { data : 'margin', name : 'margin' },
            { data : 'margin_min', name : 'margin_min' },
            { data : 'margin_percent', name : 'margin_percent' },
            { data : 'custProductGroup', name : 'custProductGroup' },
            { data : 'vendProductGroup', name : 'vendProductGroup' },
        ];
    }else{
        tableName = "else_reports";
        columnData = [
            { data : 'country' , name : 'country' },
            { data : 'total_calls', name : 'total_calls' },
            { data : 'completed', name : 'completed' },
            { data : 'asr', name : 'asr' },
            { data : 'acd', name : 'acd' },
            { data : 'duration', name : 'duration' },
            { data : 'billed_duration', name : 'billed_duration' },
            { data : 'avg_rate_min' , name : 'avg_rate_min' },
            { data : 'total_cost', name : 'total_cost' },
        ];
    }
       
    console.log('tableName', tableName);
    console.log('columnData', columnData);
    $("#"+tableName).DataTable({
        processing: true,
        serverSide: false,
        responsive: true,
        "bDestroy": true,
        ajax: {
          url: "/csv_view_rebuild",
            // data: function (d) {
            //     d.status = $('#status').val(),
            //     d.search = $('input[type="search"]').val()
            // }
            // type : 'POST',
            data: function(d){
                d.Account = $searchFilter.Account,
                d.billingtype =  $searchFilter.billingtype,
                d.StartDate = $searchFilter.StartDate ,
                d.EndDate =  $searchFilter.EndDate,
                d.Report =  $searchFilter.Report,
                d.ActiveTab =  $searchFilter.ActiveTab,
                d.type =  $searchFilter.type 
            },
        },
        columns: columnData
    });



    // $Report == 'Vendor-Hourly'
    // $Report == 'Vendor-Summary'
    // $Report == 'Vendor-Margin-Report'
    // $Report == 'Vendor-Negative-Report'
    // $('#customer_hourly').DataTable({
    //     processing: true,
    //     serverSide: true,
    //     retrieve : true,
    //     ajax: {
    //       url: "/csv_view_rebuild",
    //         // data: function (d) {
    //         //     d.status = $('#status').val(),
    //         //     d.search = $('input[type="search"]').val()
    //         // }
    //         // type : 'POST',
    //         data: function(d){
    //             d.Account = $searchFilter.Account,
    //             d.billingtype =  $searchFilter.billingtype,
    //             d.StartDate = $searchFilter.StartDate ,
    //             d.EndDate =  $searchFilter.EndDate,
    //             d.Report =  $searchFilter.Report,
    //             d.ActiveTab =  $searchFilter.ActiveTab,
    //             d.type =  $searchFilter.type 
    //         },
    //     },
    //     columns: [
    //         {data :'customerAccountCode' , name : 'customerAccountCode'},
    //         {data :'customer' , name : 'customer'},
    //         {data :'custDestination' , name : 'custDestination'},
    //         {data :'attempts' , name : 'attempts'},
    //         {data :'completed' , name : 'completed'},
    //         {data :'asr' , name : 'asr'},
    //         {data :'acd_sec' , name : 'acd_sec'},
    //         {data :'raw_Dur' , name : 'raw_Dur'},
    //         {data :'rnd_Dur' , name : 'rnd_Dur'},
    //         {data :'revenue' , name : 'revenue'},
    //         {data :'revenue_min' , name : 'revenue_min'},
    //         {data :'margin' , name : 'margin'},
    //         {data :'margin_min' , name : 'margin_min'},
    //         {data :'margin_percent' , name : 'margin_percent'},
    //         {data :'cstProductGroup' , name : 'cstProductGroup'},
    //         {data :'vendProductGroup' , name : 'vendProductGroup'},
    //     ]
    // });

   
});