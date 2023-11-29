$('#Customerreport').change(function() {
    window.location = "{{url('export-csv-history')}}?report="+$(this).val();
});

function initializeDataTable(url, columns, additionalDataFunction) {
    var table = $('#CustomerAccountCode').DataTable({
        "bDestroy": true,
        "bProcessing": true,
        "bServerSide": true,
        ajax: {
            "url": url,
            "data": function (data) {
                if (additionalDataFunction) {
                    additionalDataFunction(data);
                }
            }
        },
        columns: columns
    });
    

    return table;
}


