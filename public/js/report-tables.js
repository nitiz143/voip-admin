// to make table header dynamic
let tableData, reportName, tableRow;
        tableData = $('.tables_data');                               
        $('select[name=report]').on('change', function(){
            reportName = $(this).val();
            if(reportName == 'Customer-Summary'){
                tableRow = `
                <table class="table w-100 table-bordered" id="customer_summary">
                    <thead>    
                        <tr style="margin-bottom:30px;" class="item_table_header">
                            <th style="width:9%">CustomerAccountCode</th> 
                            <th style="width:9%">Customer</th>
                            <th style="width:9%">CustDestination</th>
                            <th style="width:9%">Attempts</th>
                            <th style="width:9%">Completed</th>
                            <th style="width:9%">ASR(%)</th>
                            <th style="width:9%">ACD(Sec)</th>
                            <th style="width:9%">Raw Dur</th>
                            <th style="width:9%">Rnd Dur</th>
                            <th style="width:9%">Revenue</th>
                            <th style="width:9%">Revenue/Min</th>
                            <th style="width:9%">Margin</th>
                            <th style="width:9%">Margin/Min</th>
                            <th style="width:9%">Margin%</th>
                            <th style="width:9%">CustProductGroup</th>
                            <th style="width:9%">VendProductGroup</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table> `;
                tableData.html(tableRow);
            }else if( reportName == 'Customer-Hourly'){
                tableRow = `
                <table class="table w-100 table-bordered" id="customer_hourly">
                    <thead>
                        <tr style="margin-bottom:30px;" class="item_table_header">
                            <th style="width:9%">CustomerAccountCode</th> 
                            <th style="width:9%">Customer</th>
                            <th style="width:9%">CustDestination</th>
                            <th style="width:9%">Attempts</th>
                            <th style="width:9%">Completed</th>
                            <th style="width:9%">ASR(%)</th>
                            <th style="width:9%">ACD(Sec)</th>
                            <th style="width:9%">Raw Dur</th>
                            <th style="width:9%">Rnd Dur</th>
                            <th style="width:9%">Revenue</th>
                            <th style="width:9%">Revenue/Min</th>
                            <th style="width:9%">Margin</th>
                            <th style="width:9%">Margin/Min</th>
                            <th style="width:9%">Margin%</th>
                            <th style="width:9%">CustProductGroup</th>
                            <th style="width:9%">VendProductGroup</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>`;
                tableData.html(tableRow);
            }else if(reportName == 'Customer/Vendor-Report'){
                   tableRow = `<table class="table w-100 table-bordered" id="customer_vendor_report">
                        <thead>
                        <tr style="margin-bottom:30px;" class="item_table_header">
                            <th style="width:9%">CustomerAccountCode</th> 
                            <th style="width:9%">Customer</th>
                            <th style="width:9%">CustDestination</th>
                            <th style="width:9%">VendAccountCode</th>
                            <th style="width:9%">Vendor</th>
                            <th style="width:9%">Attempts</th>
                            <th style="width:9%">Completed</th>
                            <th style="width:9%">ASR(%)</th>
                            <th style="width:9%">ACD(Sec)</th>
                            <th style="width:9%">Raw Dur</th>
                            <th style="width:9%">Rnd Dur</th>
                            <th style="width:9%">Revenue</th>
                            <th style="width:9%">Revenue/Min</th>
                            <th style="width:9%">Cost</th>
                            <th style="width:9%">Cost/Min</th>
                            <th style="width:9%">Margin</th>
                            <th style="width:9%">Margin/Min</th>
                            <th style="width:9%">Margin%</th>
                            <th style="width:9%">CustProductGroup</th>
                            <th style="width:9%">VendProductGroup</th>
                        </tr>
                        </thead>
                        <tbody> </tbody>
                        </table>`;
                        tableData.html(tableRow);
        }else if(reportName == 'Account-Manage'){
            tableRow = `
                <table class="table w-100 table-bordered" id="account_manage">
                    <thead>
                    <tr style="margin-bottom:30px;" class="item_table_header">
                        <th style="width:9%">CustomerAccountCode</th> 
                        <th style="width:9%">Customer</th>
                        <th style="width:9%">CustDestination</th>
                        <th style="width:9%">VendAccountCode</th>
                        <th style="width:9%">Vendor</th>
                        <th style="width:9%">Attempts</th>
                        <th style="width:9%">Completed</th>
                        <th style="width:9%">ASR(%)</th>
                        <th style="width:9%">ACD(Sec)</th>
                        <th style="width:9%">Raw Dur</th>
                        <th style="width:9%">Rnd Dur</th>
                        <th style="width:9%">Revenue</th>
                        <th style="width:9%">Revenue/Min</th>
                        <th style="width:9%">Cost</th>
                        <th style="width:9%">Cost/Min</th>
                        <th style="width:9%">Margin</th>
                        <th style="width:9%">Margin/Min</th>
                        <th style="width:9%">Margin%</th>
                        <th style="width:9%">CustProductGroup</th>
                        <th style="width:9%">VendProductGroup</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody
                </table>
            `;
            tableData.html(tableRow);
        }else if(reportName == 'Customer-Margin-Report'){
            let tableRow = `
            <table class="table w-100 table-bordered" id="customer_margin">
                <thead>
                    <tr style="margin-bottom:30px;" class="item_table_header">
                    <th style="width:9%">CustomerAccountCode</th> 
                    <th style="width:9%">Customer</th>
                    <th style="width:9%">CustDestination</th>
                    <th style="width:9%">Attempts</th>
                    <th style="width:9%">Completed</th>
                    <th style="width:9%">ASR(%)</th>
                    <th style="width:9%">ACD(Sec)</th>
                    <th style="width:9%">Raw Dur</th>
                    <th style="width:9%">Rnd Dur</th>
                    <th style="width:9%">Revenue</th>
                    <th style="width:9%">Revenue/Min</th>
                    <th style="width:9%">Margin</th>
                    <th style="width:9%">Margin/Min</th>
                    <th style="width:9%">Margin%</th>
                    <th style="width:9%">CustProductGroup</th>
                    <th style="width:9%">VendProductGroup</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            `;
            tableData.html(tableRow);
        }else if(reportName == 'Customer-Negative-Report'){
            tableRow = `
            <table class="tablew-100 table-bordered" id="customer_negative">
                <thead>
                <tr style="margin-bottom:30px;" class="item_table_header">
                    <th style="width:11%">CustomerAccountCode</th> 
                    <th style="width:11%">Customer</th>
                    <th style="width:11%">Attempts</th>
                    <th style="width:11%">Completed</th>
                    <th style="width:11%">ASR(%)</th>
                    <th style="width:11%">ACD(Sec)</th>
                    <th style="width:11%">Raw Dur</th>
                    <th style="width:11%">Rnd Dur</th>
                    <th style="width:11%">Revenue</th>
                    <th style="width:11%">Revenue/Min</th>
                    <th style="width:11%">CustProductGroup</th>
                    <th style="width:11%">VendProductGroup</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>`;
            tableData.html(tableRow);
        }else if(reportName == 'Vendor-Negative-Report'){
            tableRow = `
            <table class="tablew-100 table-bordered" id="vendor_negative">
                <thead>
                <tr style="margin-bottom:30px;" class="item_table_header">
                <th style="width:11%">VendAccountCode</th> 
                <th style="width:11%">Vendor</th>
                <th style="width:11%">Attempts</th>
                <th style="width:11%">Completed</th>
                <th style="width:11%">ASR(%)</th>
                <th style="width:11%">ACD(Sec)</th>
                <th style="width:11%">Raw Dur</th>
                <th style="width:11%">Rnd Dur</th>
                <th style="width:11%">Cost</th>
                <th style="width:11%">Cost/Min</th>
                <th style="width:11%">CustProductGroup</th>
                <th style="width:11%">VendProductGroup</th>
            </tr></thead>
                <tbody></tbody>
            </table>`;
            tableData.html(tableRow);   
        }else if(reportName == 'Vendor-Margin-Report'){
            tableRow = `
            <table class="table w-100 table-bordered" id="vendor_margin">
                <thead>
                    <tr style="margin-bottom:30px;" class="item_table_header">
                    <th style="width:9%">VendorAccountCode</th> 
                    <th style="width:9%">Vendor</th>
                    <th style="width:9%">Attempts</th>
                    <th style="width:9%">Completed</th>
                    <th style="width:9%">ASR(%)</th>
                    <th style="width:9%">ACD(Sec)</th>
                    <th style="width:9%">Raw Dur</th>
                    <th style="width:9%">Rnd Dur</th>
                    <th style="width:9%">Cost</th>
                    <th style="width:9%">Cost/Min</th>
                    <th style="width:9%">Margin</th>
                    <th style="width:9%">Margin/Min</th>
                    <th style="width:9%">Margin%</th>
                    <th style="width:9%">CustProductGroup</th>
                    <th style="width:9%">VendProductGroup</th>
                </tr>
                </thead>
                <tbody></tbody>
                </table>`;
                tableData.html(tableRow);
        }else if (reportName == 'Vendor-Summary'){
            tableRow = `<table class="table w-100 table-bordered" id="vendor_summary">
                <thead>
                <tr style="margin-bottom:30px;" class="item_table_header">
                    <th style="width:9%">VendorAccountCode</th> 
                    <th style="width:9%">Vendor</th>
                    <th style="width:9%">Attempts</th>
                    <th style="width:9%">Completed</th>
                    <th style="width:9%">ASR(%)</th>
                    <th style="width:9%">ACD(Sec)</th>
                    <th style="width:9%">Raw Dur</th>
                    <th style="width:9%">Rnd Dur</th>
                    <th style="width:9%">Cost</th>
                    <th style="width:9%">Cost/Min</th>
                    <th style="width:9%">Margin</th>
                    <th style="width:9%">Margin/Min</th>
                    <th style="width:9%">Margin%</th>
                    <th style="width:9%">CustProductGroup</th>
                    <th style="width:9%">VendProductGroup</th>
                </tr>
                </thead>
                <tbody></tbody>
                </table>`;
                tableData.html(tableRow);
        }else if(reportName == 'Vendor-Hourly'){
            tableRow = `<table class="table w-100 table-bordered" id="vendor_hourly">
                <thead>
                <tr style="margin-bottom:30px;" class="item_table_header">
                    <th style="width:9%">VendorAccountCode</th> 
                    <th style="width:9%">Vendor</th>
                    <th style="width:9%">Attempts</th>
                    <th style="width:9%">Completed</th>
                    <th style="width:9%">ASR(%)</th>
                    <th style="width:9%">ACD(Sec)</th>
                    <th style="width:9%">Raw Dur</th>
                    <th style="width:9%">Rnd Dur</th>
                    <th style="width:9%">Cost</th>
                    <th style="width:9%">Cost/Min</th>
                    <th style="width:9%">Margin</th>
                    <th style="width:9%">Margin/Min</th>
                    <th style="width:9%">Margin%</th>
                    <th style="width:9%">CustProductGroup</th>
                    <th style="width:9%">VendProductGroup</th>
                </tr>
                </thead>
                <tbody></tbody>
                </table>`;
            tableData.html(tableRow);
        }else{
            tableRow = `
            <table class="table w-100" id="else_reports">
                <thead>
                <tr style="margin-bottom:30px;" class="item_table_header">
                    <th style="width:14%">Country</th> 
                    <th style="width:14%">Total calls</th>
                    <th style="width:14%">Completed</th>
                    <th style="width:14%">ASR(%)</th>
                    <th style="width:14%">ACD(Sec)</th>
                    <th style="width:14%">Duration</th>
                    <th style="width:14%">Billed Duration</th>
                    <th style="width:14%">Avg Rate/Min</th>
                    <th style="width:14%">Total Cost</th>
                </tr>
                </thead>
            </table>`;
            tableData.html(tableRow);
        }
        });