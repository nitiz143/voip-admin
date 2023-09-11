<h2>Email</h2>
<p>Email has been sent.</p>
<p>Billing_cycle: {{ $data['billing_cycle'] }}</p>

    @if($data['billing_cycle'] == "quarterly")
        
        <p>Start_invoice_date: {{ $data['last_invoice_date'] }}</p>
        <p>last_invoice_date: {{ date('Y-m-d', strtotime($data['last_invoice_date'].' + 3 month')); }}</p>


    @elseif($data['billing_cycle'] == "monthly")
        <p>Start_invoice_date: {{ $data['last_invoice_date'] }}</p>
        <p>last_invoice_date: {{ date('Y-m-d', strtotime($data['last_invoice_date'].' + 1 month')); }}</p>

    @elseif($data['billing_cycle'] == "yearly")
        <p>Start_invoice_date: {{ $data['last_invoice_date'] }}</p>
        <p>last_invoice_date: {{ date('Y-m-d', strtotime($data['last_invoice_date'].' + 1 year')); }}</p>

    @elseif($data['billing_cycle'] == "weekly")
        <p>Start_invoice_date: {{ $data['last_invoice_date'] }}</p>
        <p>last_invoice_date: {{ date('Y-m-d', strtotime($data['last_invoice_date'].' + 1 week')); }}</p>

    @elseif($data['billing_cycle'] == "daily")
        <p>Start_invoice_date: {{ $data['last_invoice_date'] }}</p>
        <p>last_invoice_date: {{ date('Y-m-d', strtotime($data['last_invoice_date'].' + 1 day ')); }}</p>

    @else
        <p>Start_invoice_date: {{ $data['last_invoice_date'] }}</p>
        <p>last_invoice_date: {{ date('Y-m-d', strtotime($data['last_invoice_date'].' + 1 days ')); }}</p>

    @endif