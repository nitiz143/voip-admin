<style>
    .datatable tr.selected{
         background:#EDC171;
     }
 </style>
<div class="row">
    <div class="col-md-12">
        <form  id="CustomerTrunk-form" method="post" action="{{route('Customer.trunk',@request()->id)}}">
            @csrf
        <div class="card card-primary" data-collapsed="0">
            <div class="card-header">
                <div class="card-title">
                    Outgoing
                </div>
                <div class="form-check form-switch float-right">
                    <a href="#" data-rel="collapse" id="switch"><i class="fas fa-angle-down"></i></a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered datatable" id="table-4">
                    <thead>
                        <tr>
                            <th width="1%"><div class="checkbox "><input type="checkbox" id="selectall" name="checkbox[]" class="" ></div></th>
                            <th width="13%">Trunk</th>
                            <th width="13%">Prefix</th>
                            <th style="text-align:center" width="7%">Show Prefix in Ratesheet</th>
                            <th width="7%">Use Prefix In CDR</th>
                            <th style="text-align:center" width="7%">Enable Routing Plan</th>
                            <th width="18%">CodeDeck</th>
                            <th width="30%">Base Rate Table</th>
                            <th width="4%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($trunks))
                            @foreach ( $trunks as $index=>$trunk )
                                <tr class="odd gradeX @if(!$trunk->customers->isEmpty()) @if($trunk->customers[0]->customer_id == @request()->id) selected @endif @endif">
                                    <td>
                                        <input type="checkbox" name="CustomerTrunk[{{$trunk->id}}][status]" class="rowcheckbox" value="1" @if(!$trunk->customers->isEmpty()) @if($trunk->customers[0]->customer_id == @request()->id) checked @endif @endif>
                                    </td>
                                    <td>
                                        {{$trunk->title}}
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="CustomerTrunk[{{$trunk->id}}][prefix]" value="@if(!$trunk->customers->isEmpty()) @if($trunk->customers[0]->customer_id == @request()->id) {{$trunk->customers[0]->prefix}} @endif @endif"/>
                                    </td>
                                    <td class="center" style="text-align:center">
                                        <input type="checkbox" value="1" name="CustomerTrunk[{{$trunk->id}}][includePrefix]" @if(!$trunk->customers->isEmpty()) @if($trunk->customers[0]->customer_id == @request()->id)  @if($trunk->customers[0]->includePrefix == 1)
                                        checked
                                        @endif  @endif @endif>
                                    </td>
                                    <td class="center" style="text-align:center">
                                        <input type="checkbox" value="1" name="CustomerTrunk[{{$trunk->id}}][prefix_cdr]"  @if(!$trunk->customers->isEmpty()) @if($trunk->customers[0]->customer_id == @request()->id)  @if($trunk->customers[0]->prefix_cdr == 1)
                                        checked
                                        @endif  @endif @endif>
                                    </td>
                                    <td class="center" style="text-align:center">
                                        <input type="checkbox" value="1" name="CustomerTrunk[{{$trunk->id}}][routine_plan_status]"  @if(!$trunk->customers->isEmpty()) @if($trunk->customers[0]->customer_id == @request()->id)  @if($trunk->customers[0]->routine_plan_status == 1)
                                        checked
                                        @endif  @endif @endif>
                                    </td>
                                    <td>
                                        <select class=" codedeckid custom-select form-control" name="CustomerTrunk[{{$trunk->id}}][codedeck]">
                                            <option value="">Select</option>
                                            <option value="3" @if(!$trunk->customers->isEmpty())  @if($trunk->customers[0]->customer_id == @request()->id) {{$trunk->customers[0]->codedeck == '3' ? "selected" : ""}} @endif @endif>Customer Codedeck</option>
                                            <option value="2" @if(!$trunk->customers->isEmpty())  @if($trunk->customers[0]->customer_id == @request()->id) {{$trunk->customers[0]->codedeck == '2' ? "selected" : ""}} @endif  @endif>Customer Codes</option>
                                            <option value="1" @if(!$trunk->customers->isEmpty())  @if($trunk->customers[0]->customer_id == @request()->id) {{$trunk->customers[0]->codedeck == '1' ? "selected" : ""}} @endif  @endif>Vendor Codes</option>
                                        </select>
                                        <input type="hidden" name="codedeckid" value=" @if(!$trunk->customers->isEmpty())  @if($trunk->customers[0]->customer_id == @request()->id) {{$trunk->customers[0]->codedeck}} @endif  @endif">
                                        <input type="hidden" id="trunkid" name="CustomerTrunk[{{$trunk->id}}][trunkid]" value="{{$trunk->id}}">
                                    </td>
                                    <td>
                                        <select class="ratetableid custom-select form-control" id="ratetableid" name="CustomerTrunk[{{$trunk->id}}][rate_table_id]">
                                            <option value="">Select</option>
                                        <input type="hidden" name="ratetableid" value="">
                                    </td>
                                    <td>
                                        @if(!$trunk->customers->isEmpty())
                                            @if($trunk->customers[0]->customer_id == @request()->id)
                                                @if($trunk->customers[0]->status == 1)
                                                    Active
                                                @endif
                                            @else
                                                Inactive
                                            @endif
                                        @else
                                            Inactive
                                        @endif
                                    </td>
                                    <input type="hidden" id="customer_trunk_id" name="CustomerTrunk[{{$trunk->id}}][customer_trunk_id]" value="@if(!$trunk->customers->isEmpty())  @if($trunk->customers[0]->customer_id == @request()->id) {{$trunk->customers[0]->id}} @endif  @endif">
                                </tr>
                            @endforeach
                        @endif
                    </tbody>

                </table>
                <p class="float-right mt-4" >
                    <a href="#" id="customer-trunks-submit" class="btn save btn-primary btn-sm btn-icon icon-left">
                        <i class="entypo-floppy"></i>
                        Save
                    </a>
                </p>
            </div>
        </div>
        </form>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <form  id="inbound-ratetable-form" class="form-horizontal " method="post" action="" >
        <div class="card card-primary" data-collapsed="0">
            <div class="card-header " >
                <div class="card-title ">
                    Incoming
                </div>
                <div class="card-title float-right">
                    <a href="#" class="" data-rel="collapse"><i class="fas fa-angle-down"></i></a>
                </div>
            </div>
            <div class="card-body mt-4">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="field-1" class="col-sm-2 control-label">Inbound Rate Table</label>
                        <div class="col-md-4">
                          <select class="custom-select form-control" data-placeholder="Select a Rate Table" name="InboudRateTableID"><option value="" selected="selected">Select</option><option value="1">C_IDT_NCLI_1659</option><option value="2">C_1World_CLI_20148</option><option value="3">C_Acrobat_CLI_20093</option><option value="4">C_Acrobat_NCLI_10093</option><option value="5">C_Addwin_NCLI_10194</option><option value="6">C_Addwin_CLI_20194</option><option value="7">C_Adcom_NCLI_10077</option><option value="8">C_Adcom_CLI_20077</option><option value="9">C_Adcom_CC_30077</option><option value="10">C_Advise_CLI_20188</option><option value="11">C_Advise_NCLI_10188</option><option value="12">C_Aiwo_NCLI_10184</option><option value="13">C_Alfacall_NCLI_10160</option><option value="14">C_Alfacall_CLI_20160</option><option value="15">C_AlkaIP_NCLI_10078</option><option value="16">C_AlkaIP_CLI_20078</option><option value="17">C_American_CLI_20047</option><option value="18">C_American_NCLI_10047</option><option value="19">C_AR_NCLI_10061</option><option value="20">C_AR_CLI_20061</option><option value="21">C_AR_CC_40003</option><option value="22">C_AR_ORTP_30061</option><option value="23">C_Arlon_NCLI_10176</option><option value="24">C_Arlon_CC_40176</option><option value="25">C_Armatel_NCLI_10153</option><option value="26">C_Armatel_CLI_20153</option><option value="27">C_Arptel_CLI_1111</option><option value="28">C_Asia_CLI_20033</option><option value="29">C_Asia_NCLI_10033</option><option value="30">C_Askvoip_NCLI_10105</option><option value="31">C_Askvoip_CLI_20105</option><option value="32">C_Asterope_CLI_20112</option><option value="33">C_Asterope_NCLI_10112</option><option value="34">C_AVSQ_NCLI_10149</option><option value="35">C_AVSQ_CLI_20149</option><option value="36">C_AVYS_NCLI_10032</option><option value="37">C_AVYS_CLI_20032</option><option value="38">C_AVYS_CC_40032</option><option value="39">C_Bamtel_NCLI_01</option><option value="40">C_Bamtel_CLI_00</option><option value="41">C_Barter_CC_40159</option><option value="42">C_Bluestar_ORTP_30151</option><option value="43">C_Brain_CC_40191</option><option value="44">C_BRM_NCLI_10189</option><option value="45">C_BWB_NCLI_10174</option><option value="46">C_BWB_CLI_20174</option><option value="47">C_BWB_CC_40174</option><option value="48">C_CallCaribe_NCLI_10088</option><option value="49">C_CallCaribe_CLI_20088</option><option value="50">C_Cambridge_NCLI_10185</option><option value="51">C_Cambridge_CLI_20185</option><option value="52">C_Canota_NCLI_10098</option><option value="53">C_Canota_CLI_20098</option><option value="54">C_Celestial_NCLI_10179</option><option value="55">C_Celestial_CLI_20179</option><option value="56">C_Celestial_ORTP_30179</option><option value="57">C_ChinaSky_NCLI_10014</option><option value="58">C_ChinaSky_CLI_20014</option><option value="59">C_ChinaSky_CC_40014</option><option value="60">C_Circell_CLI_20165</option><option value="61">C_Circell_CC_40165</option><option value="62">C_Cliqnet_NCLI_10135</option><option value="63">C_Cliqnet_CLI_20135</option><option value="64">C_Cloudcom_CLI_20006</option><option value="65">C_Cloudcom_NCLI_10006</option><option value="66">C_Coastnet_NCLI_10020</option><option value="67">C_Coastnet_CLI_20020</option><option value="68">C_Coastnet_CC_40002</option><option value="69">C_Coastnet_ORTP_30020</option><option value="70">C_Coastnet_TDM_50020</option><option value="71">C_Computer_NCLI_10016</option><option value="72">C_Computer_CLI_20016</option><option value="73">C_Confiar_CLI_20090</option><option value="74">C_Confiar_NCLI_10090</option><option value="75">C_Confiar_ORTP_30090</option><option value="76">C_Core3_NCLI_10103</option><option value="77">C_Core3_CLI_20103</option><option value="78">C_Core3_TDM_50103</option><option value="79">C_Cronomax_NCLI_10082</option><option value="80">C_Cronomax_CLI_20082</option><option value="81">C_Crypto_CLI_20106</option><option value="82">C_Crypto_ORTP_30106</option><option value="83">C_Datora_NCLI_10175</option><option value="84">C_Datora_CLI_20175</option><option value="85">C_Debross_NCLI_40110</option><option value="86">C_Debross_ORTP_30110</option><option value="87">C_Debross_Root_10110</option><option value="88">C_Debross_CLI_20110</option><option value="89">C_DialTel_CLI_20052</option><option value="90">C_DialTel_NCLI_10052</option><option value="91">C_Diamond_NCLI_10097</option><option value="92">C_Diamond_CLI_20097</option><option value="93">C_DilaTel_NCLI_10017</option><option value="94">C_DilaTel_CLI_20017</option><option value="95">C_DilaTel_ORTP_30002</option><option value="96">C_Ditto_NCLI_10092</option><option value="97">C_Ditto_CLI_20092</option><option value="98">C_Dollar_NCLI_10010</option><option value="99">C_Dollar_CLI_20010</option><option value="100">C_Easy2Call_CLI_20170</option><option value="102">C_Etelix_CLI_20044</option><option value="103">C_FamilyTalk_NCLI_10140</option><option value="104">C_FamilyTalk_CLI_20140</option><option value="105">C_FamilyTalk_TDM_50140</option><option value="106">C_Filasco_NCLI_10064</option><option value="107">C_Filasco_CLI_20064</option><option value="108">C_Filasco_CC_40064</option><option value="109">C_Flames_NCLI_10040</option><option value="110">C_Flames_CLI_20040</option><option value="111">C_Flavien_NCLI_10102</option><option value="112">C_Flavien_CLI_20102</option><option value="113">C_Flavien_ORTP_30102</option><option value="114">C_Fortis_NCLI_10043</option><option value="115">C_Fortis_CLI_20043</option><option value="116">C_Franzcom_NCLI_10162</option><option value="117">C_Franzcom_CLI_20162</option><option value="118">C_Fusion_NCLI_10002</option><option value="119">C_Fusion_CLI_20002</option><option value="120">C_G5_NCLI_10069</option><option value="121">C_G5_CLI_20069</option><option value="122">C_G5_ORTP_30069</option><option value="123">C_G5_TDM_50069</option><option value="124">C_Genza_NCLI_10131</option><option value="125">C_Genza_CLI_20131</option><option value="126">C_GNH_NCLI_10018</option><option value="127">C_GNH_CLI_20018</option><option value="128">C_GNH_CC_40018</option><option value="129">C_GNH_ORTP_30018</option><option value="130">C_GlobalRinger_NCLI_10073</option><option value="131">C_GlobalRinger_CLI_20073</option><option value="132">C_GTS_NCLI_70001</option><option value="133">C_GTS_CLI_70002</option><option value="134">C_GTS_CC_70003</option><option value="135">C_GTS_ORTP_70004</option><option value="136">C_Globtel_NCLI_10147</option><option value="137">C_Globtel_CLI_20147</option><option value="138">C_Globtel_CC_40147</option><option value="139">C_Glory_NCLI_10011</option><option value="140">C_Glory_CLI_20011</option><option value="141">C_Godzilla_NCLI_10037</option><option value="142">C_Godzilla_CLI_20037</option><option value="143">C_Goodwin_NCLI_10086</option><option value="144">C_Goodwin_CLI_20086</option><option value="145">C_Gsoft_NCLI_10067</option><option value="146">C_Gsoft_CLI_20067</option><option value="147">C_Gventure_NCLI_10028</option><option value="148">C_Gventure_CLI_20028</option><option value="149">C_Gventure_ORTP_30028</option><option value="150">C_HayaAir_NCLI_10187</option><option value="151">C_HayaAir_CLI_20187</option><option value="153">C_HDTalk_NCLI_10079</option><option value="154">C_HDTalk_CLI_20079</option><option value="155">C_HMTele_NCLI_10048</option><option value="156">C_HMTele_CLI_20048</option><option value="157">C_Iconnect_NCLI_10026</option><option value="158">C_Iconnect_CLI_20026</option><option value="159">C_Iconnect_CC_40026</option><option value="160">C_ICS_NCLI_10181</option><option value="161">C_ICS_CLI_20181</option><option value="162">C_IDT_CLI_5892</option><option value="164">C_Iglobe_NCLI_10108</option><option value="165">C_Iglobe_CLI_20108</option><option value="166">C_Iglobe_ORTP_30108</option><option value="167">C_Inaani_NCLI_10019</option><option value="168">C_Inaani_CLI_20019</option><option value="169">C_Inaani_CC_40019</option><option value="170">C_Inet_NCLI_10003</option><option value="171">C_Inet_CLI_20003</option><option value="172">C_Inet_CC_40005</option><option value="173">C_Instacom_NCLI_10013</option><option value="174">C_Instacom_CLI_20013</option><option value="175">C_Instacom_ORTP_30013</option><option value="176">C_Instacom_IGW_40013</option><option value="177">C_INS_NCLI_10141</option><option value="178">C_INS_CLI_20141</option><option value="179">C_INS_ORTP_30141</option><option value="180">C_Integral_NCLI_10114</option><option value="181">C_Integral_CLI_20114</option><option value="182">C_Intelcom_NCLI_10143</option><option value="183">C_Intelcom_CLI_20143</option><option value="184">C_Ivoco_NCLI_10116</option><option value="185">C_Ivoco_CLI_20116</option><option value="186">C_Ivoco_CC_30008</option><option value="187">C_IPGab_ORTP_30154</option><option value="188">C_IPro_NCLI_10027</option><option value="189">C_IPro_CLI_20027</option><option value="190">C_IXC_NCLI_10038</option><option value="191">C_IXC_CLI_20038</option><option value="192">C_IXC_CC_40038</option><option value="193">C_Kair_NCLI_10056</option><option value="194">C_Kair_CLI_20056</option><option value="195">C_KG_CC_40178</option><option value="196">C_Kol_NCLI_10035</option><option value="197">C_Kol_CLI_20035</option><option value="198">C_Kol_ORTP_30035</option><option value="199">C_Layer3_NCLI_10034</option><option value="200">C_Layer3_CLI_20034</option><option value="201">C_Lensol_NCLI_10161</option><option value="202">C_Lensol_CLI_20161</option><option value="203">C_Lexico_NCLI_10023</option><option value="204">C_Lexico_CLI_20023</option><option value="205">C_Lexico_CC_40004</option><option value="206">C_Lexico_TDM_50023</option><option value="207">C_Loop_NCLI_10015</option><option value="208">C_Loop_CLI_20015</option><option value="209">C_Magik_NCLI_10195</option><option value="210">C_Magik_CLI_20195</option><option value="211">C_Manor_NCLI_10132</option><option value="212">C_Manor_CLI_20132</option><option value="213">C_Manor_TDM_50132</option><option value="214">C_Maple_NCLI_10042</option><option value="215">C_Maple_CLI_20042</option><option value="216">C_Maxes_NCLI_10168</option><option value="217">C_Maxes_CLI_20168</option><option value="218">C_Mea_NCLI_10045</option><option value="219">C_Mea_CLI_20045</option><option value="220">C_Microtalk_CC_40169</option><option value="221">C_Midway_CLI_20193</option><option value="222">C_Mts_CLI_20197</option><option value="223">C_Mush_NCLI_10071</option><option value="224">C_Mush_CLI_20071</option><option value="225">C_Nalia_NCLI_10159</option><option value="226">C_Nalia_CLI_20159</option><option value="227">C_NextPage_NCLI_10157</option><option value="228">C_NextPage_CLI_20157</option><option value="229">C_NGNICS_NCLI_10173</option><option value="230">C_NGNICS_CLI_20173</option><option value="231">C_Nordfon_NCLI_10182</option><option value="232">C_Nordfon_CLI_20182</option><option value="233">C_OceanTele_NCLI_10068</option><option value="234">C_OceanTele_CLI_20068</option><option value="235">C_OceanTel_NCLI_10046</option><option value="236">C_OceanTel_CLI_20046</option><option value="237">C_OLO_NCLI_10171</option><option value="238">C_OLO_CLI_20171</option><option value="239">C_Oracus_NCLI_10111</option><option value="240">C_Oracus_CLI_20111</option><option value="241">C_Oracus_ORTP_30111</option><option value="242">C_Orphy_NCLI_10029</option><option value="243">C_Orphy_CLI_20029</option><option value="244">C_Oscatel_NCLI_10163</option><option value="245">C_Oscatel_CLI_20163</option><option value="246">C_OXNP_NCLI_10051</option><option value="247">C_OXNP_CLI_20051</option><option value="248">C_Passport_NCLI_10076</option><option value="249">C_Passport_CLI_20076</option><option value="250">C_PateckTel_NCLI_10133</option><option value="251">C_PRN_NCLI_10145</option><option value="252">C_PRN_CLI_20145</option><option value="253">C_Progressive_CLI_20039</option><option value="254">C_Progressive_NCLI_10039</option><option value="255">C_PTGI_NCLI_10084</option><option value="256">C_PTGI_CLI_20084</option><option value="260">C_Quickcom_CC_40008</option><option value="261">C_Quickcom_NCLI_10008</option><option value="262">C_Quickcom_CLI_20008</option><option value="263">C_Rain_CLI_20190</option><option value="264">C_Rain_NCLI_10190</option><option value="265">C_Ratemax_NCLI_10050</option><option value="266">C_Ratemax_CLI_20050</option><option value="267">C_Raza_NCLI_10156</option><option value="268">C_Rigalo_NCLI_10072</option><option value="269">C_Rigalo_CLI_20072</option><option value="270">C_Rigel_NCLI_10104</option><option value="271">C_Rigel_CLI_20104</option><option value="272">C_Rigel_BTCL_30104</option><option value="273">C_Ring_NCLI_10055</option><option value="274">C_Ring_CLI_20055</option><option value="275">C_Rovex_CLI_020180</option><option value="276">C_Rovex_NCLI_010180</option><option value="277">C_RSCom_NCLI_10115</option><option value="278">C_RSCom_CLI_20115</option><option value="279">C_RSM_CLI_20021</option><option value="280">C_RSM_ORTP_30021</option><option value="281">C_Saarc_NCLI_10158</option><option value="282">C_Saarc_CLI_20158</option><option value="283">C_Scaffnet_CLI_20096</option><option value="284">C_Scaffnet_NCLI_10096</option><option value="285">C_SearchTec_NCLI_10091</option><option value="286">C_SearchTec_CLI_20091</option><option value="287">C_SearchTec_ORTP_30091</option><option value="288">C_Secured_NCLI_10113</option><option value="289">C_Secured_CLI_20113</option><option value="290">C_Shengli_CLI_20004</option><option value="291">C_Shengli_NCLI_10004</option><option value="292">C_Sify_NCLI_653422</option><option value="293">C_Sify_CLI_653421</option><option value="294">C_Sigmo_CLI_20186</option><option value="295">C_Sigmo_NCLI_10186</option><option value="296">C_SIP_NCLI_10144</option><option value="297">C_SIP_CLI_20144</option><option value="298">C_Sirius_CLI_20150</option><option value="299">C_Sirius_NCLI_10150</option><option value="300">C_SmartNet_NCLI_10196</option><option value="301">C_SmartNet_CLI_20196</option><option value="302">C_SmartRoutes_CLI_20085</option><option value="303">C_SmartRoutes_NCLI_10085</option><option value="304">C_SMS_NCLI_10012</option><option value="305">C_SMS_CLI_20012</option><option value="306">C_SMS_CC_40001</option><option value="307">C_SMS_ORTP_30001</option><option value="308">C_Snowfly_NCLI_10080</option><option value="309">C_Snowfly_CLI_20080</option><option value="310">C_Songbird_CLI_20053</option><option value="311">C_Songbird_NCLI_10053</option><option value="312">C_SpaceTele_NCLI_10164</option><option value="313">C_SpaceTele_CLI_20164</option><option value="314">C_Speak2Call_CLI_20161</option><option value="315">C_Speak2Call_CC_40161</option><option value="316">C_Speedflow_NCLI_10119</option><option value="317">C_Speedflow_CLI_20119</option><option value="318">C_Speedflow_CC_40119</option><option value="319">C_Speedflow_TDM_50119</option><option value="320">C_SROT_NCLI_10031</option><option value="321">C_SROT_CLI_20031</option><option value="322">C_SROT_CC_40031</option><option value="323">C_SSH_NCLI_10001</option><option value="324">C_SSH_CLI_20001</option><option value="325">C_StarGlobal_NCLI_10100</option><option value="326">C_StarGlobal_CLI_20100</option><option value="327">C_StarGlobal_ORTP_30100</option><option value="328">C_Suratel_NCLI_10192</option><option value="329">C_Suratel_CLI_20192</option><option value="330">C_Sync_NCLI_10060</option><option value="331">C_Sync_CLI_20060</option><option value="332">C_TalkShop_NCLI_10095</option><option value="333">C_TalkShop_CLI_20095</option><option value="334">C_Talk2All_NCLI_10030</option><option value="335">C_Talk2All_CLI_20030</option><option value="336">C_Talking2World_CLI_20075</option><option value="337">C_Talking2World_NCLI_10075</option><option value="338">C_Teknolab_NCLI_10065</option><option value="339">C_Teknolab_CLI_20065</option><option value="340">C_Telasco_CLI_20063</option><option value="341">C_Telasco_NCLI_10063</option><option value="342">C_TelCasta_NCLI_10220</option><option value="343">C_TelCasta_CLI_20220</option><option value="344">C_Telebiz_NCLI_10005</option><option value="345">C_Telebiz_CLI_20005</option><option value="346">C_Telebiz_ORTP_30006</option><option value="347">C_TelecomRoute_CLI_20177</option><option value="348">C_TelecomRoute_CC_40177</option><option value="349">C_Telefonix_NCLI_10036</option><option value="350">C_Telefonix_CLI_20036</option><option value="351">C_Telesense_CLI_20058</option><option value="352">C_Telesense_NCLI_10058</option><option value="353">C_Tonerro_NCLI_10087</option><option value="354">C_Tonerro_CLI_20087</option><option value="357">C_Tvoice_NCLI_10089</option><option value="358">C_Tvoice_CLI_20089</option><option value="359">C_Tvoice_ORTP_30089</option><option value="360">C_Uniquantum_NCLI_10007</option><option value="361">C_Uniquantum_CLI_20007</option><option value="362">C_Vertial_NCLI_10117</option><option value="363">C_Vertial_CLI_20117</option><option value="364">C_Vesper_NCLI_10107</option><option value="365">C_Vesper_CLI_20107</option><option value="366">C_Vesper_ORTP_30107</option><option value="367">C_Vinculum_NCLI_10041</option><option value="368">C_Vinculum_CLI_20041</option><option value="369">C_VivaIcs_NCLI_10155</option><option value="370">C_VivaIcs_CLI_20155</option><option value="371">C_VivaIcs_CC_40155</option><option value="372">C_Vnexus_NCLI_10057</option><option value="373">C_Vnexus_CLI_20057</option><option value="374">C_Vnexus_ORTP_30057</option><option value="375">C_Vodca_NCLI_10136</option><option value="376">C_Vodca_CLI_20136</option><option value="377">C_Vodca_ORTP_30136</option><option value="378">C_VoiceAreUs_CC_40163</option><option value="379">C_VoiceTec_NCLI_10172</option><option value="380">C_VoiceTec_CLI_20172</option><option value="381">C_VoiceTec_CC_40172</option><option value="382">C_VoipShop_NCLI_10074</option><option value="383">C_VoipShop_CLI_20074</option><option value="384">C_VoipShop_CC_40074</option><option value="385">C_Voover_NCLI_10009</option><option value="386">C_Voover_CLI_20009</option><option value="387">C_Vovitel_NCLI_10024</option><option value="388">C_Vovitel_CLI_20024</option><option value="389">C_Vovitel_ORTP_30024</option><option value="390">C_Voxcarrier_NCLI_10022</option><option value="391">C_Voxpace_NCLI_10109</option><option value="392">C_Voxpace_CLI_20109</option><option value="393">C_Voxpace_ORTP_30007</option><option value="394">C_Voyzee_NCLI_10049</option><option value="395">C_Voyzee_CLI_20049</option><option value="396">C_VVN_NCLI_10070</option><option value="397">C_VVN_CLI_20070</option><option value="398">C_Webgeniez_NCLI_10118</option><option value="399">C_Webgeniez_CLI_20118</option><option value="400">C_Webgeniez_ORTP_30009</option><option value="401">C_WorldTone_NCLI_10062</option><option value="402">C_WorldTone_CLI_20062</option><option value="403">C_WorldTone_TDM_50001</option><option value="404">C_Xpecto_CLI_9911#</option><option value="405">C_YX_NCLI_10101</option><option value="406">C_YX_CLI_20101</option><option value="407">C_YX_TDM_50101</option><option value="408">C_Zaheen_CLI_10433</option><option value="409">C_Zevs_NCLI_10059</option><option value="410">C_Zevs_CLI_20059</option><option value="411">C_Zingmax_NCLI_10183</option><option value="412">C_Zingmax_CLI_20183</option><option value="413">C_Zingmax_CC_40183</option><option value="414">C_Sigma_CLI_5555</option><option value="415">C_Sigma_TDM_6666</option><option value="416">C_Sigma_ORTP_7777</option><option value="417">C_HDTele_CC_40167</option><option value="418">C_INS_CC_40141</option><option value="419">C_IT-Dec_NCLI_10152</option><option value="420">C_IT-Dec_CLI_20152</option><option value="421">C_Quantum_CLI_20099</option><option value="422">C_Quantum_NCLI_10099</option><option value="423">C_Quantum_CC_30099</option><option value="424">C_1World_NCLI_10148</option><option value="425">C_Alfacall_CC_40160</option>
                            <option value="426">C_Aiwo_CLI_20184</option><option value="427">C_Asia_TDM_50033</option><option value="428">C_FamilyTalk_ORTP_30140</option>
                            <option value="429">C_Xpecto_Other_9977#</option><option value="430">C_Xpecto_Other_9944#</option>
                        </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>

<script>
//    var  ratabale = @json($ratetable);
//    console.log(ratabale)
 $(document).ready(function ($) {
        // Replace Checboxes
        $(".pagination a").click(function (ev) {
        replaceCheckboxes();
        });
        $('#table-4 tbody .rowcheckbox').click(function () {
            if( $(this).prop("checked")){
                $(this).parent().parent().addClass('selected');
            }else{
                $(this).parent().parent().removeClass('selected');
            }
        });
        $("#selectall").click(function (ev) {

            var is_checked = $(this).is(':checked');

            $('#table-4 tbody tr').each(function (i, el) {
                if(is_checked){
                    $(this).find('.rowcheckbox').prop("checked",true);
                    $(this).addClass('selected');
                }else{
                    $(this).find('.rowcheckbox').prop("checked",false);
                    $(this).removeClass('selected');
                }
            });
        });


            $("#customer-trunks-submit").click(function () {
                $("#CustomerTrunk-form").submit();
                return false;
            });

        // var prev_val;
        // $('.codedeckid').on('select2-open',function(e){
        //     prev_val = $(this).val();

        // }).on('change',function (e) {
        //     var self = $(this);
        //     var current_obj = self;
	    //     var trunkid = self.parent().children('[name="trunkid"]').val();
	    //     //var RateTableID = self.parent().next().find('[name="[CustomerTrunk['+trunkid+'][RateTableID]"]');
        //     var RateTableID = self.parent().next().find('select.ratetableid');

        //     var json = JSON.parse(ratabale);

        //     //if( typeof  json[trunkid] != 'undefined'){
        //         var filtereddata = [];
        //         /*if(typeof json[trunkid][self.val()] !='undefined'){
        //             filtereddata = json[trunkid][self.val()];
        //         }*/

        //         filtereddata = json[self.val()];

        //         //convert json
        //         if(filtereddata.length != 0) {
        //             filtereddata= filtereddata.map(({id, text}) =>  ({[id]: text}));
        //             var filtereddata = Object.assign(...filtereddata);
        //         }

        //         self.parent().next().find('select.ratetableid').select2('destroy');
        //         rebuildSelect2(RateTableID,filtereddata,'Select');
        //         opts = {
        //             allowClear: false,
        //             minimumResultsForSearch:Infinity,
        //             dropdownCssClass:'no-search'
        //         };
        //         self.parent().next().find('select.ratetableid').select2(opts);
        //         self.select2('container').find('.select2-search').addClass ('hidden') ;
        //         RateTableID = self.parent().next().find('select.ratetableid');
        //     });

                $('.codedeckid').on('change',function (e) {
                    codedeckid = $(this).val();
                   var id = $('#trunkid').val();

                $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                        });
                    $.ajax({
                        type: "post",
                        url: "{{ route('customerCodedeckid.update') }}",
                        data: {
                        id: id,
                        codedeckid: codedeckid
                        },

                        success: function(res) {
                            $.each(res.rate_table, function (key, value) {

                                $("#ratetableid").app('<option value="' + value
                                    .id + '">' + value.name + '</option>');

                            });
                        }
                    });
            });

    });



</script>
