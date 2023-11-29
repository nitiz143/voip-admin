
   <div class="container for-scroll">
        <div class="col-md-12">
            @if(!empty($Report))
                @if($Report == 'Customer/Vendor-Report')
                    <table class="table w-100 table-bordered">
                        <tr style="margin-bottom:30px;" class="item_table_header">
                            <th style="width:9%">{{ __('CustomerAccountCode') }}</th> 
                            <th style="width:9%">{{ __('Customer') }}</th>
                            <th style="width:9%">{{ __('CustDestination') }}</th>
                            <th style="width:9%">{{ __('VendAccountCode') }}</th>
                            <th style="width:9%">{{ __('Vendor') }}</th>
                            <th style="width:9%">{{ __('Attempts') }}</th>
                            <th style="width:9%">{{ __('Completed') }}</th>
                            <th style="width:9%">{{ __('ASR(%)') }}</th>
                            <th style="width:9%">{{ __('ACD(Sec)') }}</th>
                            <th style="width:9%">{{ __('Raw Dur') }}</th>
                            <th style="width:9%">{{ __('Rnd Dur') }}</th>
                            <th style="width:9%">{{ __('Revenue') }}</th>
                            <th style="width:9%">{{ __('Revenue/Min') }}</th>
                            <th style="width:9%">{{ __('Cost') }}</th>
                            <th style="width:9%">{{ __('Cost/Min') }}</th>
                            <th style="width:9%">{{ __('Margin') }}</th>
                            <th style="width:9%">{{ __('Margin/Min') }}</th>
                            <th style="width:9%">{{ __('Margin%') }}</th>
                            <th style="width:9%">{{ __('CustProductGroup') }}</th>
                            <th style="width:9%">{{ __('VendProductGroup') }}</th>
                        </tr>
                        {{-- @if(!empty($invoices))
                            @foreach($invoices as $key => $values)

                                <tbody id="items">
                                    <tr class="items">
                                        @php
                                            $country = App\Models\Country::where('phonecode',$values[0]['callerareacode'])->first();
                                        @endphp
                                        <td>{{!empty($values[0]['customeraccount']) ? $values[0]['customeraccount'] :""}}</td>
                                        <td>{{!empty($values[0]['customername']) ? $values[0]['customername'] :""}}</td>
                                        <td>{{!empty($country->name) ? $country->name:"";}}</td>
                                        <td>{{!empty($values[0]['agentaccount']) ? $values[0]['agentaccount'] :"";}}</td>
                                        <td>{{!empty($values[0]['agentname']) ? $values[0]['agentname'] :""}}</td>
                                        @php
                                            $Duration_count = array();
                                            $completed_count = array();
                                            $Agent_Duration_count = array();
                                            $completed_agent_count = array();
                                            $fee_count = array();
                                            $agentfee_count =array();
                                            $customer_fee ="";

                                            $totalSum = $values->filter(function ($value) {
                                                // Check if the 'feetime' property is numeric
                                                return is_numeric($value->feetime);
                                            })->sum('feetime');

                                            if($values->sum('fee') > 0 && $totalSum > 0){
                                                $timepersec = $values->sum('fee')/$totalSum;
                                                $persec =  round($timepersec, 7);
                                                $customer_fee= $persec*60;
                                            }

                                            $agent_fee ="";

                                            $totalagentSum = $values->filter(function ($value) {
                                                // Check if the 'feetime' property is numeric
                                                return is_numeric($value->agentfeetime);
                                            })->sum('agentfeetime');
                                            if($values->sum('agentfee') > 0 && $totalagentSum > 0){
                                                $timepersec2 = $values->sum('agentfee')/$totalagentSum;
                                                $persec2 =  round($timepersec2, 7);
                                                $agent_fee= $persec2*60;
                                            }
                                        
                                            foreach ($values as $invoice){
                                                //customer
                                                $Duration_count[] = $invoice->feetime;
                                                if($invoice->agentfee > 0) {
                                                    $agentfee_count[] = $invoice->agentfee;
                                                }
                                                if($invoice->fee > 0) {
                                                    $fee_count[] = $invoice->fee;
                                                }
                                                if($invoice->feetime > 0 && $invoice->feetime != null) {
                                                    $completed_count[] = $invoice->feetime;
                                                }
                                                $Agent_Duration_count[] = $invoice->agentfeetime;
                                                if($invoice->agentfeetime > 0 && $invoice->agentfeetime != null) {
                                                    $completed_agent_count[] = $invoice->agentfeetime;
                                                }
                                            }
                                            $margin =  array_sum($fee_count)-array_sum( $agentfee_count);
                                            $margin_per_min = (int)$customer_fee - (int)$agent_fee;
                                            $total_margin_time = $totalSum-$totalagentSum;
                                            if( $margin > 0 &&  $total_margin_time > 0){
                                                $timepersec3 = $margin/ $total_margin_time;
                                                $persec3 =  round($timepersec3, 7);
                                                $margin_per_min= $persec3*60;
                                            }
                                            
                                            $Duration= sprintf( "%02.2d:%02.2d", floor( array_sum($Duration_count) / 60 ), array_sum($Duration_count) % 60 );
                                            $sec = "";
                                            if(array_sum($completed_count) != 0 && count($completed_count) != 0){
                                                $sec =  array_sum($completed_count) /  count($completed_count);
                                            }
                                        @endphp
                                        <td>{{$values->count()}}</td>
                                        <td>{{!empty($completed_count) ? count($completed_count) : ""}}</td>
                                        <td>{{\Str::limit((count($completed_count)/$values->count() * 100))}} </td>
                                        <td>{{!empty($sec) ? \Str::limit($sec) :"0"}}</td>
                                        <td>{{!empty($Duration) ?$Duration :""}}</td>
                                        <td>{{ !empty($Duration) ? $Duration :""}}</td>
                                        <td>{{'$'.sprintf('%0.2f', $values->sum('fee'))}}</td>
                                        <td>{{!empty($customer_fee) ? '$'.sprintf('%0.2f', $customer_fee) : "$ 0.00"}}</td>
                                        <td>{{'$'.sprintf('%0.2f', $values->sum('agentfee'));}}</td>
                                        <td>{{!empty($agent_fee) ? '$'.sprintf('%0.2f', $agent_fee) : "$ 0.00";}}</td>
                                        <td>{{!empty($margin) ? '$'.sprintf('%0.2f', $margin) : "$ 0.00";}}</td>
                                        <td>{{!empty($margin_per_min) ? '$'.$margin_per_min : "$ 0.00";}}</td>
                                        <td>{{\Str::limit(($margin/$values->count() * 100))}}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            @endforeach
                        @endif --}}
                    </table>
                @elseif ($Report == 'Customer-Hourly')
                    <table class="table w-100 table-bordered">
                        <tr style="margin-bottom:30px;" class="item_table_header">
                            <th style="width:9%">{{ __('CustomerAccountCode') }}</th> 
                            <th style="width:9%">{{ __('Customer') }}</th>
                            <th style="width:9%">{{ __('CustDestination') }}</th>
                            <th style="width:9%">{{ __('Attempts') }}</th>
                            <th style="width:9%">{{ __('Completed') }}</th>
                            <th style="width:9%">{{ __('ASR(%)') }}</th>
                            <th style="width:9%">{{ __('ACD(Sec)') }}</th>
                            <th style="width:9%">{{ __('Raw Dur') }}</th>
                            <th style="width:9%">{{ __('Rnd Dur') }}</th>
                            <th style="width:9%">{{ __('Revenue') }}</th>
                            <th style="width:9%">{{ __('Revenue/Min') }}</th>
                            <th style="width:9%">{{ __('Margin') }}</th>
                            <th style="width:9%">{{ __('Margin/Min') }}</th>
                            <th style="width:9%">{{ __('Margin%') }}</th>
                            <th style="width:9%">{{ __('CustProductGroup') }}</th>
                            <th style="width:9%">{{ __('VendProductGroup') }}</th>
                        </tr>
                        @if(!empty($invoices))
                            @foreach($invoices as $key => $values)
                                <tbody id="items">
                                    @php
                                        $country = App\Models\Country::where('phonecode',$values[0]['callerareacode'])->first();
                                    @endphp
                                    <tr class="items">
                                        <td>{{!empty($values[0]['customeraccount']) ? $values[0]['customeraccount'] :""}}</td>
                                        <td>{{!empty($values[0]['customername']) ? $values[0]['customername'] :""}}</td>
                                        <td>{{!empty($country->name) ? $country->name:"";}}</td>
                                        @php
                                            $Duration_count = array();
                                            $completed_count =array();
                                            $fee_count = array();
                                            $agentfee_count =array();

                                            $customer_fee ="";
                                            $totalSum = $values->filter(function ($value) {
                                                // Check if the 'feetime' property is numeric
                                                return is_numeric($value->feetime);
                                            })->sum('feetime');

                                            if($values->sum('fee') != 0 && $totalSum != 0){
                                                $timepersec2 = $values->sum('fee')/$totalSum;
                                                $persec2 =  round($timepersec2, 7);
                                                $customer_fee= $persec2*60;
                                            }

                                            $agent_fee ="";
                                            $totalagentSum = $values->filter(function ($value) {
                                                // Check if the 'feetime' property is numeric
                                                return is_numeric($value->agentfeetime);
                                            })->sum('agentfeetime');

                                            if($values->sum('agentfee') > 0 && $totalagentSum > 0){
                                                $timepersec2 = $values->sum('agentfee')/$totalagentSum;
                                                $persec2 =  round($timepersec2, 7);
                                                $agent_fee= $persec2*60;
                                            }
                                    
                                            foreach ($values as $invoice){
                                                $Duration_count[] = $invoice->feetime;
                                                if($invoice->feetime > 0 && $invoice->feetime != null) {
                                                    $completed_count[] = $invoice->feetime;
                                                }
                                                if($invoice->agentfee > 0) {
                                                    $agentfee_count[] = $invoice->agentfee;
                                                }
                                                if($invoice->fee > 0) {
                                                    $fee_count[] = $invoice->fee;
                                                }
                                            }
                                            
                                            $margin =  array_sum($fee_count)-array_sum($agentfee_count);
                                            $margin_per_min = (int)$customer_fee - (int)$agent_fee;
                                            $Duration= sprintf( "%02.2d:%02.2d", floor( array_sum($Duration_count) / 60 ), array_sum($Duration_count) % 60 );
                                            $sec = "";
                                            if(array_sum($completed_count) != 0 && count($completed_count) != 0){
                                                $sec =  array_sum($completed_count) /  count($completed_count);
                                            }
                                        @endphp
                                        <td>{{$values->count()}}</td>
                                        <td>{{!empty($completed_count) ? count($completed_count) : ""}}</td>
                                        <td>{{\Str::limit((count($completed_count)/$values->count() * 100))}} </td>
                                        <td>{{!empty($sec) ? \Str::limit($sec) :"0"}}</td>
                                        <td>{{!empty($Duration) ?$Duration :""}}</td>
                                        <td>{{!empty($Duration) ? $Duration :""}}</td>
                                        <td>{{'$'.sprintf('%0.2f', $values->sum('fee'))}}</td>
                                        <td>{{!empty($customer_fee) ? '$'.sprintf('%0.2f', $customer_fee) : "$ 0.00"}}</td>
                                        <td>{{!empty($margin) ? '$'.sprintf('%0.2f', $margin) : "$ 0.00";}}</td>
                                        <td>{{!empty($margin_per_min) ? '$'.$margin_per_min : "$ 0.00";}}</td>
                                        <td>{{\Str::limit(($margin/$values->count() * 100))}}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            @endforeach
                        @endif
                    </table>
                @elseif ($Report == 'Vendor-Hourly')
                    <table class="table w-100 table-bordered">
                        <tr style="margin-bottom:30px;" class="item_table_header">
                            <th style="width:9%">{{ __('VendorAccountCode') }}</th> 
                            <th style="width:9%">{{ __('Vendor') }}</th>
                            <th style="width:9%">{{ __('Attempts') }}</th>
                            <th style="width:9%">{{ __('Completed') }}</th>
                            <th style="width:9%">{{ __('ASR(%)') }}</th>
                            <th style="width:9%">{{ __('ACD(Sec)') }}</th>
                            <th style="width:9%">{{ __('Raw Dur') }}</th>
                            <th style="width:9%">{{ __('Rnd Dur') }}</th>
                            <th style="width:9%">{{ __('Cost') }}</th>
                            <th style="width:9%">{{ __('Cost/Min') }}</th>
                            <th style="width:9%">{{ __('Margin') }}</th>
                            <th style="width:9%">{{ __('Margin/Min') }}</th>
                            <th style="width:9%">{{ __('Margin%') }}</th>
                            <th style="width:9%">{{ __('CustProductGroup') }}</th>
                            <th style="width:9%">{{ __('VendProductGroup') }}</th>
                        </tr>
                        @if(!empty($invoices))
                            @foreach($invoices as $key => $values)
                                <tbody id="items">
                                    <tr class="items">
                                        <td>{{!empty($values[0]['agentaccount']) ? $values[0]['agentaccount'] :""}}</td>
                                        <td>{{!empty($values[0]['agentname']) ? $values[0]['agentname'] :""}}</td>
                                        @php
                                            $Agent_Duration_count = array();
                                            $completed_agent_count =array();
                                            $fee_count = array();
                                            $agentfee_count =array();

                                            $customer_fee ="";

                                            $totalSum = $values->filter(function ($value) {
                                                // Check if the 'feetime' property is numeric
                                                return is_numeric($value->feetime);
                                            })->sum('feetime');

                                            if($values->sum('fee') != 0 && $totalSum != 0){
                                                $timepersec = $values->sum('fee')/$totalSum;
                                                $persec =  round($timepersec, 7);
                                                $customer_fee= $persec*60;
                                            }

                                            $agent_fee ="";
                                            $totalagentSum = $values->filter(function ($value) {
                                                // Check if the 'feetime' property is numeric
                                                return is_numeric($value->agentfeetime);
                                            })->sum('agentfeetime');
                                            if($values->sum('agentfee') > 0 && $totalagentSum  > 0){
                                                $timepersec2 = $values->sum('agentfee')/$totalagentSum ;
                                                $persec2 =  round($timepersec2, 7);
                                                $agent_fee= $persec2*60;
                                            }

                                            foreach ($values as $invoice){
                                                $Agent_Duration_count[] = $invoice->agentfeetime;
                                                if($invoice->agentfeetime > 0 && $invoice->agentfeetime != null) {
                                                    $completed_agent_count[] = $invoice->agentfeetime;
                                                }
                                                if($invoice->agentfee > 0) {
                                                    $agentfee_count[] = $invoice->agentfee;
                                                }
                                                if($invoice->fee > 0) {
                                                    $fee_count[] = $invoice->fee;
                                                }
                                            }
                                            
                                            $margin =  array_sum($fee_count)-array_sum($agentfee_count);
                                            $margin_per_min = (int)$customer_fee - (int)$agent_fee;
                                            $Duration= sprintf( "%02.2d:%02.2d", floor( array_sum($Agent_Duration_count) / 60 ), array_sum($Agent_Duration_count) % 60 );
                                            $sec = "";
                                            if(array_sum($completed_agent_count) != 0 && count($completed_agent_count) != 0){
                                                $sec =  array_sum($completed_agent_count) /  count($completed_agent_count);
                                            }
                                        @endphp
                                        <td>{{$values->count()}}</td>
                                        <td>{{!empty($completed_agent_count) ? count($completed_agent_count) : ""}}</td>
                                        <td>{{\Str::limit((count($completed_agent_count)/$values->count() * 100))}} </td>
                                        <td>{{!empty($sec) ? \Str::limit($sec) :"0"}}</td>
                                        <td>{{!empty($Duration) ?$Duration :""}}</td>
                                        <td>{{ !empty($Duration) ? $Duration :""}}</td>
                                        <td>{{'$'.sprintf('%0.2f', $values->sum('agentfee'))}}</td>
                                        <td>{{ !empty($agent_fee) ? '$'.sprintf('%0.2f', $agent_fee) : "$ 0.00"}}</td>
                                        <td>{{!empty($margin) ? '$'.sprintf('%0.2f', $margin) : "$ 0.00";}}</td>
                                        <td>{{!empty($margin_per_min) ? '$'.$margin_per_min : "$ 0.00";}}</td>
                                        <td>{{\Str::limit(($margin/$values->count() * 100))}}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            @endforeach
                        @endif
                    </table>
                @elseif ($Report == 'Account-Manage')
                <table class="table w-100 table-bordered">
                    <tr style="margin-bottom:30px;" class="item_table_header">
                        <th style="width:9%">{{ __('CustomerAccountCode') }}</th> 
                        <th style="width:9%">{{ __('Customer') }}</th>
                        <th style="width:9%">{{ __('CustDestination') }}</th>
                        <th style="width:9%">{{ __('VendAccountCode') }}</th>
                        <th style="width:9%">{{ __('Vendor') }}</th>
                        <th style="width:9%">{{ __('Attempts') }}</th>
                        <th style="width:9%">{{ __('Completed') }}</th>
                        <th style="width:9%">{{ __('ASR(%)') }}</th>
                        <th style="width:9%">{{ __('ACD(Sec)') }}</th>
                        <th style="width:9%">{{ __('Raw Dur') }}</th>
                        <th style="width:9%">{{ __('Rnd Dur') }}</th>
                        <th style="width:9%">{{ __('Revenue') }}</th>
                        <th style="width:9%">{{ __('Revenue/Min') }}</th>
                        <th style="width:9%">{{ __('Cost') }}</th>
                        <th style="width:9%">{{ __('Cost/Min') }}</th>
                        <th style="width:9%">{{ __('Margin') }}</th>
                        <th style="width:9%">{{ __('Margin/Min') }}</th>
                        <th style="width:9%">{{ __('Margin%') }}</th>
                        <th style="width:9%">{{ __('CustProductGroup') }}</th>
                        <th style="width:9%">{{ __('VendProductGroup') }}</th>
                    </tr>
                    @if(!empty($invoices))
                        @foreach($invoices as $key => $values)
                            <tbody id="items">
                                <tr class="items">
                                    @php
                                        $country = App\Models\Country::where('phonecode',$values[0]['callerareacode'])->first();
                                    @endphp
                                    <td>{{!empty($values[0]['customeraccount']) ? $values[0]['customeraccount'] :""}}</td>
                                    <td>{{!empty($values[0]['customername']) ? $values[0]['customername'] :""}}</td>
                                    <td>{{!empty($country->name) ? $country->name:"";}}</td>
                                    <td>{{!empty($values[0]['agentaccount']) ? $values[0]['agentaccount'] :"";}}</td>
                                    <td>{{!empty($values[0]['agentname']) ? $values[0]['agentname'] :""}}</td>
                                    @php
                                        $Duration_count = array();
                                        $completed_count = array();
                                        $Agent_Duration_count = array();
                                        $completed_agent_count = array();
                                        $fee_count = array();
                                        $agentfee_count =array();
                                        $customer_fee ="";
                                        $totalSum = $values->filter(function ($value) {
                                                // Check if the 'feetime' property is numeric
                                                return is_numeric($value->feetime);
                                            })->sum('feetime');

                                        if($values->sum('fee') > 0 && $totalSum > 0){
                                            $timepersec = $values->sum('fee')/$totalSum;
                                            $persec =  round($timepersec, 7);
                                            $customer_fee= $persec*60;
                                        }

                                        $agent_fee ="";
                                        $totalagentSum = $values->filter(function ($value) {
                                                // Check if the 'feetime' property is numeric
                                                return is_numeric($value->agentfeetime);
                                            })->sum('agentfeetime');

                                        if($values->sum('agentfee') > 0 && $totalagentSum > 0){
                                            $timepersec2 = $values->sum('agentfee')/$totalagentSum;
                                            $persec2 =  round($timepersec2, 7);
                                            $agent_fee= $persec2*60;
                                        }
                                    
                                        foreach ($values as $invoice){
                                            //customer
                                            $Duration_count[] = $invoice->feetime;
                                            if($invoice->agentfee > 0) {
                                                $agentfee_count[] = $invoice->agentfee;
                                            }
                                            if($invoice->fee > 0) {
                                                $fee_count[] = $invoice->fee;
                                            }
                                            if($invoice->feetime > 0 && $invoice->feetime != null) {
                                                $completed_count[] = $invoice->feetime;
                                            }
                                            $Agent_Duration_count[] = $invoice->agentfeetime;
                                            if($invoice->agentfeetime > 0 && $invoice->agentfeetime != null) {
                                                $completed_agent_count[] = $invoice->agentfeetime;
                                            }
                                        }
                                        $margin =  array_sum($fee_count)-array_sum( $agentfee_count);
                                        $margin_per_min = (int)$customer_fee - (int)$agent_fee;
                                        
                                        $Duration= sprintf( "%02.2d:%02.2d", floor( array_sum($Duration_count) / 60 ), array_sum($Duration_count) % 60 );
                                        $sec = "";
                                        if(array_sum($completed_count) != 0 && count($completed_count) != 0){
                                            $sec =  array_sum($completed_count) /  count($completed_count);
                                        }
                                    @endphp
                                    <td>{{$values->count()}}</td>
                                    <td>{{!empty($completed_count) ? count($completed_count) : ""}}</td>
                                    <td>{{\Str::limit((count($completed_count)/$values->count() * 100))}} </td>
                                    <td>{{!empty($sec) ? \Str::limit($sec) :"0"}}</td>
                                    <td>{{!empty($Duration) ?$Duration :""}}</td>
                                    <td>{{ !empty($Duration) ? $Duration :""}}</td>
                                    <td>{{'$'.sprintf('%0.2f', $values->sum('fee'))}}</td>
                                    <td>{{!empty($customer_fee) ? '$'.sprintf('%0.2f', $customer_fee) : "$ 0.00"}}</td>
                                    <td>{{'$'.sprintf('%0.2f', $values->sum('agentfee'));}}</td>
                                    <td>{{!empty($agent_fee) ? '$'.sprintf('%0.2f', $agent_fee) : "$ 0.00";}}</td>
                                    <td>{{!empty($margin) ? '$'.sprintf('%0.2f', $margin) : "$ 0.00";}}</td>
                                    <td>{{!empty($margin_per_min) ? '$'.$margin_per_min : "$ 0.00";}}</td>
                                    <td>{{\Str::limit(($margin/$values->count() * 100))}}</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        @endforeach
                    @endif
                </table>
                @elseif ($Report == 'Customer-Summary')
                    <table class="table w-100 table-bordered">
                        <tr style="margin-bottom:30px;" class="item_table_header">
                            <th style="width:9%">{{ __('CustomerAccountCode') }}</th> 
                            <th style="width:9%">{{ __('Customer') }}</th>
                            <th style="width:9%">{{ __('CustDestination') }}</th>
                            <th style="width:9%">{{ __('Attempts') }}</th>
                            <th style="width:9%">{{ __('Completed') }}</th>
                            <th style="width:9%">{{ __('ASR(%)') }}</th>
                            <th style="width:9%">{{ __('ACD(Sec)') }}</th>
                            <th style="width:9%">{{ __('Raw Dur') }}</th>
                            <th style="width:9%">{{ __('Rnd Dur') }}</th>
                            <th style="width:9%">{{ __('Revenue') }}</th>
                            <th style="width:9%">{{ __('Revenue/Min') }}</th>
                            <th style="width:9%">{{ __('Margin') }}</th>
                            <th style="width:9%">{{ __('Margin/Min') }}</th>
                            <th style="width:9%">{{ __('Margin%') }}</th>
                            <th style="width:9%">{{ __('CustProductGroup') }}</th>
                            <th style="width:9%">{{ __('VendProductGroup') }}</th>
                        </tr>
                        @if(!empty($invoices))
                            @foreach($invoices as $key => $values)
                                <tbody id="items">
                                    @php
                                        $country = App\Models\Country::where('phonecode',$values[0]['callerareacode'])->first();
                                    @endphp
                                    <tr class="items">
                                        <td>{{!empty($values[0]['customeraccount']) ? $values[0]['customeraccount'] :""}}</td>
                                        <td>{{!empty($values[0]['customername']) ? $values[0]['customername'] :""}}</td>
                                        <td>{{!empty($country->name) ? $country->name:"";}}</td>
                                        @php
                                            $Duration_count = array();
                                            $completed_count =array();
                                            $fee_count = array();
                                            $agentfee_count =array();

                                            $customer_fee ="";
                                            $totalSum = $values->filter(function ($value) {
                                                // Check if the 'feetime' property is numeric
                                                return is_numeric($value->feetime);
                                            })->sum('feetime');
                                            if($values->sum('fee') != 0 && $totalSum != 0){
                                                $timepersec2 = $values->sum('fee')/$totalSum;
                                                $persec2 =  round($timepersec2, 7);
                                                $customer_fee= $persec2*60;
                                            }

                                            $agent_fee ="";
                                            $totalagentSum = $values->filter(function ($value) {
                                                // Check if the 'feetime' property is numeric
                                                return is_numeric($value->agentfeetime);
                                            })->sum('agentfeetime');
                                            if($values->sum('agentfee') > 0 && $totalagentSum > 0){
                                                $timepersec2 = $values->sum('agentfee')/$totalagentSum ;
                                                $persec2 =  round($timepersec2, 7);
                                                $agent_fee= $persec2*60;
                                            }
                                    
                                            foreach ($values as $invoice){
                                                $Duration_count[] = $invoice->feetime;
                                                if($invoice->feetime > 0 && $invoice->feetime != null) {
                                                    $completed_count[] = $invoice->feetime;
                                                }
                                                if($invoice->agentfee > 0 ) {
                                                    $agentfee_count[] = $invoice->agentfee;
                                                }
                                                if($invoice->fee > 0) {
                                                    $fee_count[] = $invoice->fee;
                                                }
                                            }
                                            
                                            $margin =  array_sum($fee_count)-array_sum($agentfee_count);
                                            $margin_per_min = (int)$customer_fee - (int)$agent_fee;
                                            $Duration= sprintf( "%02.2d:%02.2d", floor( array_sum($Duration_count) / 60 ), array_sum($Duration_count) % 60 );
                                            $sec = "";
                                            if(array_sum($completed_count) != 0 && count($completed_count) != 0){
                                                $sec =  array_sum($completed_count) /  count($completed_count);
                                            }
                                        @endphp
                                        <td>{{$values->count()}}</td>
                                        <td>{{!empty($completed_count) ? count($completed_count) : ""}}</td>
                                        <td>{{\Str::limit((count($completed_count)/$values->count() * 100))}} </td>
                                        <td>{{!empty($sec) ? \Str::limit($sec) :"0"}}</td>
                                        <td>{{!empty($Duration) ?$Duration :""}}</td>
                                        <td>{{ !empty($Duration) ? $Duration :""}}</td>
                                        <td>{{'$'.sprintf('%0.2f', $values->sum('fee'))}}</td>
                                        <td>{{ !empty($customer_fee) ? '$'.sprintf('%0.2f', $customer_fee) : "$ 0.00"}}</td>
                                        <td>{{!empty($margin) ? '$'.sprintf('%0.2f', $margin) : "$ 0.00";}}</td>
                                        <td>{{!empty($margin_per_min) ? '$'.$margin_per_min : "$ 0.00";}}</td>
                                        <td>{{\Str::limit(($margin/$values->count() * 100))}}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            @endforeach
                        @endif
                    </table>
                @elseif ($Report == 'Vendor-Summary')
                    <table class="table w-100 table-bordered">
                        <tr style="margin-bottom:30px;" class="item_table_header">
                            <th style="width:9%">{{ __('VendorAccountCode') }}</th> 
                            <th style="width:9%">{{ __('Vendor') }}</th>
                            <th style="width:9%">{{ __('Attempts') }}</th>
                            <th style="width:9%">{{ __('Completed') }}</th>
                            <th style="width:9%">{{ __('ASR(%)') }}</th>
                            <th style="width:9%">{{ __('ACD(Sec)') }}</th>
                            <th style="width:9%">{{ __('Raw Dur') }}</th>
                            <th style="width:9%">{{ __('Rnd Dur') }}</th>
                            <th style="width:9%">{{ __('Cost') }}</th>
                            <th style="width:9%">{{ __('Cost/Min') }}</th>
                            <th style="width:9%">{{ __('Margin') }}</th>
                            <th style="width:9%">{{ __('Margin/Min') }}</th>
                            <th style="width:9%">{{ __('Margin%') }}</th>
                            <th style="width:9%">{{ __('CustProductGroup') }}</th>
                            <th style="width:9%">{{ __('VendProductGroup') }}</th>
                        </tr>
                        @if(!empty($invoices))
                            @foreach($invoices as $key => $values)
                                <tbody id="items">
                                    <tr class="items">
                                        <td>{{!empty($values[0]['agentaccount']) ? $values[0]['agentaccount'] :""}}</td>
                                        <td>{{!empty($values[0]['agentname']) ? $values[0]['agentname'] :""}}</td>
                                        @php
                                            $Agent_Duration_count = array();
                                            $completed_agent_count =array();
                                            $fee_count = array();
                                            $agentfee_count =array();

                                            $customer_fee ="";
                                            $totalSum = $values->filter(function ($value) {
                                                // Check if the 'feetime' property is numeric
                                                return is_numeric($value->feetime);
                                            })->sum('feetime');

                                            if($values->sum('fee') != 0 && $totalSum != 0){
                                                $timepersec = $values->sum('fee')/$totalSum;
                                                $persec =  round($timepersec, 7);
                                                $customer_fee= $persec*60;
                                            }

                                            $agent_fee ="";
                                            $totalagentSum = $values->filter(function ($value) {
                                                // Check if the 'feetime' property is numeric
                                                return is_numeric($value->agentfeetime);
                                            })->sum('agentfeetime');

                                            if($values->sum('agentfee') > 0 && $totalagentSum >0){
                                                $timepersec2 = $values->sum('agentfee')/$totalagentSum;
                                                $persec2 =  round($timepersec2, 7);
                                                $agent_fee= $persec2*60;
                                            }

                                            foreach ($values as $invoice){
                                                $Agent_Duration_count[] = $invoice->agentfeetime;
                                                if($invoice->agentfeetime > 0 && $invoice->agentfeetime != null) {
                                                    $completed_agent_count[] = $invoice->agentfeetime;
                                                }
                                                if($invoice->agentfee > 0) {
                                                    $agentfee_count[] = $invoice->agentfee;
                                                }
                                                if($invoice->fee > 0) {
                                                    $fee_count[] = $invoice->fee;
                                                }
                                            }
                                            
                                            $margin =  array_sum($fee_count)-array_sum($agentfee_count);
                                            $margin_per_min = (int)$customer_fee - (int)$agent_fee;
                                            $Duration= sprintf( "%02.2d:%02.2d", floor( array_sum($Agent_Duration_count) / 60 ), array_sum($Agent_Duration_count) % 60 );
                                            $sec = "";
                                            if(array_sum($completed_agent_count) != 0 && count($completed_agent_count) != 0){
                                                $sec =  array_sum($completed_agent_count) /  count($completed_agent_count);
                                            }
                                        @endphp
                                        <td>{{$values->count()}}</td>
                                        <td>{{!empty($completed_agent_count) ? count($completed_agent_count) : ""}}</td>
                                        <td>{{\Str::limit((count($completed_agent_count)/$values->count() * 100))}} </td>
                                        <td>{{!empty($sec) ? \Str::limit($sec) :"0"}}</td>
                                        <td>{{!empty($Duration) ?$Duration :""}}</td>
                                        <td>{{ !empty($Duration) ? $Duration :""}}</td>
                                        <td>{{'$'.sprintf('%0.2f', $values->sum('agentfee'))}}</td>
                                        <td>{{ !empty($agent_fee) ? '$'.sprintf('%0.2f', $agent_fee) : "$ 0.00"}}</td>
                                        <td>{{!empty($margin) ? '$'.sprintf('%0.2f', $margin) : "$ 0.00";}}</td>
                                        <td>{{!empty($margin_per_min) ? '$'.$margin_per_min : "$ 0.00";}}</td>
                                        <td>{{\Str::limit(($margin/$values->count() * 100))}}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            @endforeach
                        @endif
                    </table>
                @elseif ($Report == 'Customer-Margin-Report')
                    <table class="table w-100 table-bordered">
                        <tr style="margin-bottom:30px;" class="item_table_header">
                            <th style="width:9%">{{ __('CustomerAccountCode') }}</th> 
                            <th style="width:9%">{{ __('Customer') }}</th>
                            <th style="width:9%">{{ __('CustDestination') }}</th>
                            <th style="width:9%">{{ __('Attempts') }}</th>
                            <th style="width:9%">{{ __('Completed') }}</th>
                            <th style="width:9%">{{ __('ASR(%)') }}</th>
                            <th style="width:9%">{{ __('ACD(Sec)') }}</th>
                            <th style="width:9%">{{ __('Raw Dur') }}</th>
                            <th style="width:9%">{{ __('Rnd Dur') }}</th>
                            <th style="width:9%">{{ __('Revenue') }}</th>
                            <th style="width:9%">{{ __('Revenue/Min') }}</th>
                            <th style="width:9%">{{ __('Margin') }}</th>
                            <th style="width:9%">{{ __('Margin/Min') }}</th>
                            <th style="width:9%">{{ __('Margin%') }}</th>
                            <th style="width:9%">{{ __('CustProductGroup') }}</th>
                            <th style="width:9%">{{ __('VendProductGroup') }}</th>
                        </tr>
                        @if(!empty($invoices))
                            @foreach($invoices as $key => $values)
                                <tbody id="items">
                                    @php
                                        $country = App\Models\Country::where('phonecode',$values[0]['callerareacode'])->first();
                                    @endphp
                                    <tr class="items">
                                        <td>{{!empty($values[0]['customeraccount']) ? $values[0]['customeraccount'] :""}}</td>
                                        <td>{{!empty($values[0]['customername']) ? $values[0]['customername'] :""}}</td>
                                        <td>{{!empty($country->name) ? $country->name:"";}}</td>
                                        @php
                                            $Duration_count = array();
                                            $completed_count =array();
                                            $fee_count = array();
                                            $agentfee_count =array();

                                            $customer_fee ="";

                                            $totalSum = $values->filter(function ($value) {
                                                // Check if the 'feetime' property is numeric
                                                return is_numeric($value->feetime);
                                            })->sum('feetime');

                                            if($values->sum('fee') != 0 && $totalSum != 0){
                                                $timepersec2 = $values->sum('fee')/$totalSum;
                                                $persec2 =  round($timepersec2, 7);
                                                $customer_fee= $persec2*60;
                                            }

                                            $agent_fee ="";
                                            $totalagentSum = $values->filter(function ($value) {
                                                // Check if the 'feetime' property is numeric
                                                return is_numeric($value->agentfeetime);
                                            })->sum('agentfeetime');

                                            if($values->sum('agentfee') > 0 && $totalagentSum> 0){
                                                $timepersec2 = $values->sum('agentfee')/$totalagentSum;
                                                $persec2 =  round($timepersec2, 7);
                                                $agent_fee= $persec2*60;
                                            }
                                    
                                            foreach ($values as $invoice){
                                                $Duration_count[] = $invoice->feetime;
                                                if($invoice->feetime > 0 && $invoice->feetime != null) {
                                                    $completed_count[] = $invoice->feetime;
                                                }
                                                if($invoice->agentfee > 0) {
                                                    $agentfee_count[] = $invoice->agentfee;
                                                }
                                                if($invoice->fee > 0) {
                                                    $fee_count[] = $invoice->fee;
                                                }
                                            }
                                            
                                            $margin =  array_sum($fee_count)-array_sum($agentfee_count);
                                            $margin_per_min = (int)$customer_fee - (int)$agent_fee;
                                            $Duration= sprintf( "%02.2d:%02.2d", floor( array_sum($Duration_count) / 60 ), array_sum($Duration_count) % 60 );
                                            $sec = "";
                                            if(array_sum($completed_count) != 0 && count($completed_count) != 0){
                                                $sec =  array_sum($completed_count) /  count($completed_count);
                                            }
                                        @endphp
                                        <td>{{$values->count()}}</td>
                                        <td>{{!empty($completed_count) ? count($completed_count) : ""}}</td>
                                        <td>{{\Str::limit((count($completed_count)/$values->count() * 100))}} </td>
                                        <td>{{!empty($sec) ? \Str::limit($sec) :"0"}}</td>
                                        <td>{{!empty($Duration) ?$Duration :""}}</td>
                                        <td>{{ !empty($Duration) ? $Duration :""}}</td>
                                        <td>{{'$'.sprintf('%0.2f', $values->sum('fee'))}}</td>
                                        <td>{{ !empty($customer_fee) ? '$'.sprintf('%0.2f', $customer_fee) : "$ 0.00"}}</td>
                                        <td>{{!empty($margin) ? '$'.sprintf('%0.2f', $margin) : "$ 0.00";}}</td>
                                        <td>{{!empty($margin_per_min) ? '$'.$margin_per_min : "$ 0.00";}}</td>
                                        <td>{{\Str::limit(($margin/$values->count() * 100))}}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            @endforeach
                        @endif
                    </table>
                @elseif ($Report == 'Vendor-Margin-Report')
                    <table class="table w-100 table-bordered">
                        <tr style="margin-bottom:30px;" class="item_table_header">
                            <th style="width:9%">{{ __('VendorAccountCode') }}</th> 
                            <th style="width:9%">{{ __('Vendor') }}</th>
                            <th style="width:9%">{{ __('Attempts') }}</th>
                            <th style="width:9%">{{ __('Completed') }}</th>
                            <th style="width:9%">{{ __('ASR(%)') }}</th>
                            <th style="width:9%">{{ __('ACD(Sec)') }}</th>
                            <th style="width:9%">{{ __('Raw Dur') }}</th>
                            <th style="width:9%">{{ __('Rnd Dur') }}</th>
                            <th style="width:9%">{{ __('Cost') }}</th>
                            <th style="width:9%">{{ __('Cost/Min') }}</th>
                            <th style="width:9%">{{ __('Margin') }}</th>
                            <th style="width:9%">{{ __('Margin/Min') }}</th>
                            <th style="width:9%">{{ __('Margin%') }}</th>
                            <th style="width:9%">{{ __('CustProductGroup') }}</th>
                            <th style="width:9%">{{ __('VendProductGroup') }}</th>
                        </tr>
                        @if(!empty($invoices))
                            @foreach($invoices as $key => $values)
                                <tbody id="items">
                                    <tr class="items">
                                        <td>{{!empty($values[0]['agentaccount']) ? $values[0]['agentaccount'] :""}}</td>
                                        <td>{{!empty($values[0]['agentname']) ? $values[0]['agentname'] :""}}</td>
                                        @php
                                            $Agent_Duration_count = array();
                                            $completed_agent_count =array();
                                            $fee_count = array();
                                            $agentfee_count =array();

                                            $customer_fee ="";
                                            $totalSum = $values->filter(function ($value) {
                                                // Check if the 'feetime' property is numeric
                                                return is_numeric($value->feetime);
                                            })->sum('feetime');

                                            if($values->sum('fee') != 0 && $totalSum != 0){
                                                $timepersec = $values->sum('fee')/$totalSum;
                                                $persec =  round($timepersec, 7);
                                                $customer_fee= $persec*60;
                                            }

                                            $agent_fee ="";

                                            $totalagentSum = $values->filter(function ($value) {
                                                // Check if the 'feetime' property is numeric
                                                return is_numeric($value->agentfeetime);
                                            })->sum('agentfeetime');

                                            if($values->sum('agentfee') > 0 && $totalagentSum > 0){
                                                $timepersec2 = $values->sum('agentfee')/$totalagentSum;
                                                $persec2 =  round($timepersec2, 7);
                                                $agent_fee= $persec2*60;
                                            }

                                            foreach ($values as $invoice){
                                                $Agent_Duration_count[] = $invoice->agentfeetime;
                                                if($invoice->agentfeetime > 0 && $invoice->agentfeetime != null) {
                                                    $completed_agent_count[] = $invoice->agentfeetime;
                                                }
                                                if($invoice->agentfee > 0) {
                                                    $agentfee_count[] = $invoice->agentfee;
                                                }
                                                if($invoice->fee > 0) {
                                                    $fee_count[] = $invoice->fee;
                                                }
                                            }
                                            
                                            $margin =  array_sum($fee_count)-array_sum($agentfee_count);
                                            $margin_per_min = (int)$customer_fee - (int)$agent_fee;
                                            $Duration= sprintf( "%02.2d:%02.2d", floor( array_sum($Agent_Duration_count) / 60 ), array_sum($Agent_Duration_count) % 60 );
                                            $sec = "";
                                            if(array_sum($completed_agent_count) != 0 && count($completed_agent_count) != 0){
                                                $sec =  array_sum($completed_agent_count) /  count($completed_agent_count);
                                            }
                                        @endphp
                                        <td>{{$values->count()}}</td>
                                        <td>{{!empty($completed_agent_count) ? count($completed_agent_count) : ""}}</td>
                                        <td>{{\Str::limit((count($completed_agent_count)/$values->count() * 100))}} </td>
                                        <td>{{!empty($sec) ? \Str::limit($sec) :"0"}}</td>
                                        <td>{{!empty($Duration) ?$Duration :""}}</td>
                                        <td>{{ !empty($Duration) ? $Duration :""}}</td>
                                        <td>{{'$'.sprintf('%0.2f', $values->sum('agentfee'))}}</td>
                                        <td>{{ !empty($agent_fee) ? '$'.sprintf('%0.2f', $agent_fee) : "$ 0.00"}}</td>
                                        <td>{{!empty($margin) ? '$'.sprintf('%0.2f', $margin) : "$ 0.00";}}</td>
                                        <td>{{!empty($margin_per_min) ? '$'.$margin_per_min : "$ 0.00";}}</td>
                                        <td>{{\Str::limit(($margin/$values->count() * 100))}}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            @endforeach
                        @endif
                    </table>
                @elseif ($Report == 'Customer-Negative-Report')
                    <table class="tablew-100 table-bordered">
                        <tr style="margin-bottom:30px;" class="item_table_header">
                            <th style="width:11%">{{ __('CustomerAccountCode') }}</th> 
                            <th style="width:11%">{{ __('Customer') }}</th>
                            <th style="width:11%">{{ __('Attempts') }}</th>
                            <th style="width:11%">{{ __('Completed') }}</th>
                            <th style="width:11%">{{ __('ASR(%)') }}</th>
                            <th style="width:11%">{{ __('ACD(Sec)') }}</th>
                            <th style="width:11%">{{ __('Raw Dur') }}</th>
                            <th style="width:11%">{{ __('Rnd Dur') }}</th>
                            <th style="width:11%">{{ __('Revenue') }}</th>
                            <th style="width:11%">{{ __('Revenue/Min') }}</th>
                            <th style="width:11%">{{ __('CustProductGroup') }}</th>
                            <th style="width:11%">{{ __('VendProductGroup') }}</th>
                        </tr>
                        @if(!empty($invoices))
                            @foreach($invoices as $key => $values)
                                <tbody id="items">
                                    <tr class="items">
                                        <td>{{!empty($values[0]['customeraccount']) ? $values[0]['customeraccount'] :""}}</td>
                                        <td>{{!empty($values[0]['customername']) ? $values[0]['customername'] :""}}</td>
                                        @php
                                            $Duration_count = array();
                                            $completed_count =array();

                                            $customer_fee ="";
                                            $totalSum = $values->filter(function ($value) {
                                                // Check if the 'feetime' property is numeric
                                                return is_numeric($value->feetime);
                                            })->sum('feetime');

                                            if($values->sum('fee') != 0 && $totalSum != 0){
                                                $timepersec2 = $values->sum('fee')/$totalSum;
                                                $persec2 =  round($timepersec2, 7);
                                                $customer_fee= $persec2*60;
                                            }
                                        
                                            foreach ($values as $invoice){
                                                $Duration_count[] = $invoice->feetime;
                                                if($invoice->feetime > 0 && $invoice->feetime != null) {
                                                    $completed_count[] = $invoice->feetime;
                                                }

                                                $Agent_Duration_count[] = $invoice->agentfeetime;
                                                if($invoice->agentfeetime > 0 && $invoice->agentfeetime != null) {
                                                    $completed_agent_count[] = $invoice->agentfeetime;
                                                }
                                            }
                                            
                                            
                                            $Duration= sprintf( "%02.2d:%02.2d", floor( array_sum($Duration_count) / 60 ), array_sum($Duration_count) % 60 );
                                            $sec = "";
                                            if(array_sum($completed_count) != 0 && count($completed_count) != 0){
                                                $sec =  array_sum($completed_count) /  count($completed_count);
                                            }
                                        @endphp
                                        <td>{{$values->count()}}</td>
                                        <td>{{!empty($completed_count) ? count($completed_count) : ""}}</td>
                                        <td>{{\Str::limit((count($completed_count)/$values->count() * 100))}} </td>
                                        <td>{{!empty($sec) ? \Str::limit($sec) :"0"}}</td>
                                        <td>{{!empty($Duration) ?$Duration :""}}</td>
                                        <td>{{ !empty($Duration) ? $Duration :""}}</td>
                                        <td>{{'$'.sprintf('%0.2f', $values->sum('fee'))}}</td>
                                        <td>{{ !empty($customer_fee) ? '$'.sprintf('%0.2f', $customer_fee) : "$ 0.00"}}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            @endforeach
                        @endif
                    </table>
                @elseif ($Report == 'Vendor-Negative-Report')
                    <table class="tablew-100 table-bordered">
                        <tr style="margin-bottom:30px;" class="item_table_header">
                            <th style="width:11%">{{ __('VendAccountCode') }}</th> 
                            <th style="width:11%">{{ __('Vendor') }}</th>
                            <th style="width:11%">{{ __('Attempts') }}</th>
                            <th style="width:11%">{{ __('Completed') }}</th>
                            <th style="width:11%">{{ __('ASR(%)') }}</th>
                            <th style="width:11%">{{ __('ACD(Sec)') }}</th>
                            <th style="width:11%">{{ __('Raw Dur') }}</th>
                            <th style="width:11%">{{ __('Rnd Dur') }}</th>
                            <th style="width:11%">{{ __('Cost') }}</th>
                            <th style="width:11%">{{ __('Cost/Min') }}</th>
                            <th style="width:11%">{{ __('CustProductGroup') }}</th>
                            <th style="width:11%">{{ __('VendProductGroup') }}</th>
                        </tr>
                        @if(!empty($invoices))
                            @foreach($invoices as $key => $values)
                                <tbody id="items">
                                    <tr class="items">
                                        <td>{{!empty($values[0]['agentaccount']) ? $values[0]['agentaccount'] :""}}</td>
                                        <td>{{!empty($values[0]['agentname']) ? $values[0]['agentname'] :""}}</td>
                                        @php
                                            $Agent_Duration_count = array();
                                            $completed_agent_count =array();
                                        

                                            $agent_fee ="";
                                            $totalagentSum = $values->filter(function ($value) {
                                                // Check if the 'feetime' property is numeric
                                                return is_numeric($value->agentfeetime);
                                            })->sum('agentfeetime');
                                            if($values->sum('agentfee') != 0 && $totalagentSum!= 0){
                                                $timepersec2 = $values->sum('agentfee')/$totalagentSum;
                                                $persec2 =  round($timepersec2, 7);
                                                $agent_fee= $persec2*60;
                                            }
                                        
                                            foreach ($values as $invoice){
                                                $Duration_count[] = $invoice->feetime;
                                                if($invoice->feetime > 0 && $invoice->feetime != null) {
                                                    $completed_count[] = $invoice->feetime;
                                                }

                                                $Agent_Duration_count[] = $invoice->agentfeetime;
                                                if($invoice->agentfeetime > 0 && $invoice->agentfeetime != null) {
                                                    $completed_agent_count[] = $invoice->agentfeetime;
                                                }
                                            }
                                            
                                            
                                            $Duration= sprintf( "%02.2d:%02.2d", floor( array_sum($Agent_Duration_count) / 60 ), array_sum($Agent_Duration_count) % 60 );
                                            $sec = "";
                                            if(array_sum($completed_agent_count) != 0 && count($completed_agent_count) != 0){
                                                $sec =  array_sum($completed_agent_count) /  count($completed_agent_count);
                                            }
                                        @endphp
                                        <td>{{$values->count()}}</td>
                                        <td>{{!empty($completed_agent_count) ? count($completed_agent_count) : ""}}</td>
                                        <td>{{\Str::limit((count($completed_agent_count)/$values->count() * 100))}} </td>
                                        <td>{{!empty($sec) ? \Str::limit($sec) :"0"}}</td>
                                        <td>{{!empty($Duration) ?$Duration :""}}</td>
                                        <td>{{ !empty($Duration) ? $Duration :""}}</td>
                                        <td>{{'$'.sprintf('%0.2f', $values->sum('agentfee'))}}</td>
                                        <td>{{ !empty($agent_fee) ? '$'.sprintf('%0.2f', $agent_fee) : "$ 0.00"}}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            @endforeach
                        @endif
                    </table>
                @else
                    <table class="table w-100">
                        <tr style="margin-bottom:30px;" class="item_table_header">
                            <th style="width:14%">{{ __('Country') }}</th> 
                            <th style="width:14%">{{ __('Total calls') }}</th>
                            <th style="width:14%">{{ __('Completed') }}</th>
                            <th style="width:14%">{{ __('ASR(%)') }}</th>
                            <th style="width:14%">{{ __('ACD(Sec)') }}</th>
                            <th style="width:14%">{{ __('Duration') }}</th>
                            <th style="width:14%">{{ __('Billed Duration') }}</th>
                            <th style="width:14%">{{ __('Avg Rate/Min') }}</th>
                            <th style="width:14%">{{ __('Total Cost') }}</th>
                        </tr>
                    </table>
                @endif
            @endif
        </div>
        <div class="d-flex justify-content-center">
            {!! $invoices->links() !!}
        </div>
    </div>
   
    
    