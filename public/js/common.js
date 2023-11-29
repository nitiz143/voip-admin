$(document).ready(function() {
    $('#Customerreport').change(function() {
        var selectedValue = $(this).val();
        window.location.href = "?report=" + selectedValue;
    });
    
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