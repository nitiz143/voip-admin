@extends('layouts.dashboard')

@section('content')
<style>
    .form-check {
        padding-left: 0.25rem !important;
    }
    .hidden{
        display: none;
    }
</style>
<div class="content-wrapper mt-3">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">

                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">

                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Client Information</h3>
                        </div>

                        <form action="{{ route('client.update',$user->id) }}" method="PUT" id="Clientform">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-group">
                                    <input type="hidden" name="id" value="{{$user->id}}">
                                </div>
                                <div class="row">
                                    {{-- <div class="col-xl-6">
                                        <div class="form-group">
                                            <label>Account Owner</label>
                                            <select class="custom-select form-control-border border-width-2"
                                                name="lead_owner" id="lead_owner">
                                                <optgroup label="Select option">
                                                    @foreach ($data as $dat )
                                                    <option value="{{$dat->id}}" {{$dat->id ==
                                                        $user->lead_owner}}>{{$dat->name}} ({{$dat->role}})</option>
                                                    @endforeach
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div> --}}
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label for="company">Company</label>
                                            <input type="text" class="form-control" id="company" name="company"
                                                value="{{$user->company}}">
                                        </div>
                                    </div>
                                </div>
                                <!----------2 row ---------->
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label for="firstname">First Name</label>
                                            <input type="text" class="form-control" id="firstname" name="firstname"
                                                value="{{$user->firstname}}">
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label for="lastname">Last Name</label>
                                            <input type="text" class="form-control" id="lastname" name="lastname"
                                                value="{{$user->lastname}}">
                                        </div>
                                    </div>
                                </div>
                                <!----------3 row ---------->
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="text" class="form-control" id="email" name="email"
                                                value="{{$user->email}}">
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label for="skype_id">Skype ID</label>
                                            <input type="text" class="form-control" id="skype_id" name="skype_id"
                                                value="{{$user->skype_id}}">
                                        </div>
                                    </div>
                                </div>
                                <!----------4 row ---------->
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label for="phone">Phone</label>
                                            <input type="text" class="form-control" id="phone" name="phone"
                                                value="{{$user->phone}}">
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label for="fax">Fax</label>
                                            <input type="text" class="form-control" id="fax" name="fax"
                                                value="{{$user->fax}}">
                                        </div>
                                    </div>
                                </div>
                                <!----------5 row ---------->
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label for="mobile">Mobile</label>
                                            <input type="text" class="form-control" id="mobile" name="mobile"
                                                value="{{$user->mobile}}">
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label for="website">Website</label>
                                            <input type="text" class="form-control" id="website" name="website"
                                                value="{{$user->website}}">
                                        </div>
                                    </div>
                                </div>
                                <!----------5 row ---------->
                                <div class="row">
                                    <div class="col-xl-6">
                                        {{-- <div class="form-group">
                                            <label>Lead Source</label>
                                            <select class="custom-select form-control-border border-width-2"
                                                name="lead_source" id="lead_source">
                                                <option selected disabled>--Select Lead Source--</option>
                                                <option value="Advertisment" {{"Advertisment"==$user->lead_source ?
                                                    'selected' : ''}}>Advertisment</option>
                                                <option value="Cold Call" {{"Cold Call"==$user->lead_source ? 'selected'
                                                    : ''}}>Cold Call</option>
                                                <option value="Employee Referral" {{"Employee Referral"==$user->
                                                    lead_source ? 'selected' : ''}}>Employee Referral</option>
                                                <option value="Online Store" {{"Online Store"==$user->lead_source ?
                                                    'selected' : ''}}>Online Store</option>
                                                <option value="Partner" {{"Partner"==$user->lead_source ? 'selected' :
                                                    ''}}>Partner</option>
                                                <option value="Public Relations" {{"Public Relations"==$user->
                                                    lead_source ? 'selected' : ''}}>Public Relations</option>
                                                <option value="Sales Mail Alias" {{"Sales Mail Alias"==$user->
                                                    lead_source ? 'selected' : ''}}>Sales Mail Alias</option>
                                                <option value="Saminer Partner" {{"Saminer Partner"==$user->lead_source
                                                    ? 'selected' : ''}}>Saminer Partner</option>
                                            </select>
                                        </div> --}}
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            {{-- <label>Lead Status</label>
                                            <select class="custom-select form-control-border border-width-2"
                                                name="lead_status" id="lead_status">
                                                <option selected disabled>--Select Lead Status--</option>
                                                <option value="Attempted to Contact" {{"Attempted to Contact"==$user->
                                                    lead_status ? 'selected' : ''}}>Attempted to Contact</option>
                                                <option value="Contact Future" {{"Contact Future"==$user->lead_status ?
                                                    'selected' : ''}} >Contact Future</option>
                                                <option value="Contacted" {{"Contacted"==$user->lead_status ? 'selected'
                                                    : ''}}>Contacted</option>
                                                <option value="Junk Leak" {{"Junk Leak"==$user->lead_status ? 'selected'
                                                    : ''}}>Junk Leak</option>
                                                <option value="Not Contacted " {{"Not Contacted"==$user->lead_status ?
                                                    'selected' : ''}}>Not Contacted</option>
                                                <option value="Pre Qualified " {{"Pre Qualified"==$user->lead_status ?
                                                    'selected' : ''}}>Pre Qualified</option>
                                            </select> --}}
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="billing_id" value="{{@$user->billing_id}}">
                                <!----------6 row ---------->
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            {{-- <label for="rating">Rating</label>
                                            <select class="custom-select form-control-border border-width-2"
                                                name="rating" id="rating">
                                                <option selected disabled>--Select Rating--</option>
                                                <option value="Aquired" {{"Aquired"==$user->rating ? 'selected' :
                                                    ''}}>Aquired</option>
                                                <option value="Active" {{"Active"==$user->rating ? 'selected' :
                                                    ''}}>Active</option>
                                                <option value="Market Failed" {{"Market Failed"==$user->rating ?
                                                    'selected' : ''}}>Market Failed</option>
                                                <option value="Project Cencel" {{"Project Cencel"==$user->rating ?
                                                    'selected' : ''}}>Project Cencel</option>
                                                <option value="Shutdown" {{"Shutdown"==$user->rating ? 'selected' :
                                                    ''}}>Shutdown</option>
                                            </select> --}}
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        {{-- <div class="form-group">
                                            <label for="employee">No. of Employees</label>
                                            <input type="text" class="form-control" id="employee" name="employee"
                                                value="{{$user->employee}}">
                                        </div> --}}
                                    </div>
                                </div>
                                <!----------8 row ---------->
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="custom-select form-control-border border-width-2"
                                                name="status" id="status">
                                                <option selected disabled>--Select Status--</option>
                                                <option value="0" {{"0"==$user->status ? 'selected' : ''}}>Active
                                                </option>
                                                <option value="1" {{"1"==$user->status ? 'selected' : ''}}>Inactive
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label for="vat_number">VAT Number</label>
                                            <input type="text" class="form-control" id="vat_number" name="vat_number"
                                                value="{{$user->vat_number}}">
                                        </div>
                                    </div>
                                </div>
                                <!----------9 row ---------->
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" id="description" name="description" value="">
                                                    {{$user->description}}
                                                </textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-body">
                                <h4>Address</h4>
                                <hr>
                                <!-------- address row1---------->
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label for="address_line1">Address Line1</label>
                                            <input type="text" class="form-control" id="address_line1"
                                                name="address_line1" value="{{$user->address_line1}}">
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label for="city">City</label>
                                            <input type="text" class="form-control" id="city" name="city"
                                                value="{{$user->city}}">
                                        </div>
                                    </div>
                                </div>
                                <!-------- address row2--------->
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label for="address_line2">Address Line2</label>
                                            <input type="text" class="form-control" id="address_line2"
                                                name="address_line2" value="{{$user->address_line2}}">
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label for="postzip">PostZip Code</label>
                                            <input type="text" class="form-control" id="postzip" name="postzip"
                                                value="{{$user->postzip}}">
                                        </div>
                                    </div>
                                </div>
                                <!-------- address row3--------->
                                <div class="row">
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label for="address_line3">Address Line3</label>
                                            <input type="text" class="form-control" id="address_line3"
                                                name="address_line3" value="{{$user->address_line3}}">
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="form-group">
                                            <label for="country">Country<span style="color:red;">*</span></label>
                                            <select class="custom-select form-control-border border-width-2"
                                                name="country" id="country">
                                                <option selected disabled>--Select Country--</option>
                                                <option value="Afghanistan">Afghanistan</option>
                                                <option value="Albania">Albania</option>
                                                <option value="Algeria">Algeria</option>
                                                <option value="American Samoa">American Samoa</option>
                                                <option value="Andorra">Andorra</option>
                                                <option value="Angola">Angola</option>
                                                <option value="Anguilla">Anguilla</option>
                                                <option value="Antartica">Antarctica</option>
                                                <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                                <option value="Argentina">Argentina</option>
                                                <option value="Armenia">Armenia</option>
                                                <option value="Aruba">Aruba</option>
                                                <option value="Australia">Australia</option>
                                                <option value="Austria">Austria</option>
                                                <option value="Azerbaijan">Azerbaijan</option>
                                                <option value="Bahamas">Bahamas</option>
                                                <option value="Bahrain">Bahrain</option>
                                                <option value="Bangladesh">Bangladesh</option>
                                                <option value="Barbados">Barbados</option>
                                                <option value="Belarus">Belarus</option>
                                                <option value="Belgium">Belgium</option>
                                                <option value="Belize">Belize</option>
                                                <option value="Benin">Benin</option>
                                                <option value="Bermuda">Bermuda</option>
                                                <option value="Bhutan">Bhutan</option>
                                                <option value="Bolivia">Bolivia</option>
                                                <option value="Bosnia and Herzegowina">Bosnia and Herzegowina</option>
                                                <option value="Botswana">Botswana</option>
                                                <option value="Bouvet Island">Bouvet Island</option>
                                                <option value="Brazil">Brazil</option>
                                                <option value="British Indian Ocean Territory">British Indian Ocean
                                                    Territory</option>
                                                <option value="Brunei Darussalam">Brunei Darussalam</option>
                                                <option value="Bulgaria">Bulgaria</option>
                                                <option value="Burkina Faso">Burkina Faso</option>
                                                <option value="Burundi">Burundi</option>
                                                <option value="Cambodia">Cambodia</option>
                                                <option value="Cameroon">Cameroon</option>
                                                <option value="Canada">Canada</option>
                                                <option value="Cape Verde">Cape Verde</option>
                                                <option value="Cayman Islands">Cayman Islands</option>
                                                <option value="Central African Republic">Central African Republic
                                                </option>
                                                <option value="Chad">Chad</option>
                                                <option value="Chile">Chile</option>
                                                <option value="China">China</option>
                                                <option value="Christmas Island">Christmas Island</option>
                                                <option value="Cocos Islands">Cocos (Keeling) Islands</option>
                                                <option value="Colombia">Colombia</option>
                                                <option value="Comoros">Comoros</option>
                                                <option value="Congo">Congo</option>
                                                <option value="Congo">Congo, the Democratic Republic of the</option>
                                                <option value="Cook Islands">Cook Islands</option>
                                                <option value="Costa Rica">Costa Rica</option>
                                                <option value="Cota D'Ivoire">Cote d'Ivoire</option>
                                                <option value="Croatia">Croatia (Hrvatska)</option>
                                                <option value="Cuba">Cuba</option>
                                                <option value="Cyprus">Cyprus</option>
                                                <option value="Czech Republic">Czech Republic</option>
                                                <option value="Denmark">Denmark</option>
                                                <option value="Djibouti">Djibouti</option>
                                                <option value="Dominica">Dominica</option>
                                                <option value="Dominican Republic">Dominican Republic</option>
                                                <option value="East Timor">East Timor</option>
                                                <option value="Ecuador">Ecuador</option>
                                                <option value="Egypt">Egypt</option>
                                                <option value="El Salvador">El Salvador</option>
                                                <option value="Equatorial Guinea">Equatorial Guinea</option>
                                                <option value="Eritrea">Eritrea</option>
                                                <option value="Estonia">Estonia</option>
                                                <option value="Ethiopia">Ethiopia</option>
                                                <option value="Falkland Islands">Falkland Islands (Malvinas)</option>
                                                <option value="Faroe Islands">Faroe Islands</option>
                                                <option value="Fiji">Fiji</option>
                                                <option value="Finland">Finland</option>
                                                <option value="France">France</option>
                                                <option value="France Metropolitan">France, Metropolitan</option>
                                                <option value="French Guiana">French Guiana</option>
                                                <option value="French Polynesia">French Polynesia</option>
                                                <option value="French Southern Territories">French Southern Territories
                                                </option>
                                                <option value="Gabon">Gabon</option>
                                                <option value="Gambia">Gambia</option>
                                                <option value="Georgia">Georgia</option>
                                                <option value="Germany">Germany</option>
                                                <option value="Ghana">Ghana</option>
                                                <option value="Gibraltar">Gibraltar</option>
                                                <option value="Greece">Greece</option>
                                                <option value="Greenland">Greenland</option>
                                                <option value="Grenada">Grenada</option>
                                                <option value="Guadeloupe">Guadeloupe</option>
                                                <option value="Guam">Guam</option>
                                                <option value="Guatemala">Guatemala</option>
                                                <option value="Guinea">Guinea</option>
                                                <option value="Guinea-Bissau">Guinea-Bissau</option>
                                                <option value="Guyana">Guyana</option>
                                                <option value="Haiti">Haiti</option>
                                                <option value="Heard and McDonald Islands">Heard and Mc Donald Islands
                                                </option>
                                                <option value="Holy See">Holy See (Vatican City State)</option>
                                                <option value="Honduras">Honduras</option>
                                                <option value="Hong Kong">Hong Kong</option>
                                                <option value="Hungary">Hungary</option>
                                                <option value="Iceland">Iceland</option>
                                                <option value="India">India</option>
                                                <option value="Indonesia">Indonesia</option>
                                                <option value="Iran">Iran (Islamic Republic of)</option>
                                                <option value="Iraq">Iraq</option>
                                                <option value="Ireland">Ireland</option>
                                                <option value="Israel">Israel</option>
                                                <option value="Italy">Italy</option>
                                                <option value="Jamaica">Jamaica</option>
                                                <option value="Japan">Japan</option>
                                                <option value="Jordan">Jordan</option>
                                                <option value="Kazakhstan">Kazakhstan</option>
                                                <option value="Kenya">Kenya</option>
                                                <option value="Kiribati">Kiribati</option>
                                                <option value="Democratic People's Republic of Korea">Korea, Democratic
                                                    People's Republic of</option>
                                                <option value="Korea">Korea, Republic of</option>
                                                <option value="Kuwait">Kuwait</option>
                                                <option value="Kyrgyzstan">Kyrgyzstan</option>
                                                <option value="Lao">Lao People's Democratic Republic</option>
                                                <option value="Latvia">Latvia</option>
                                                <option value="Lebanon">Lebanon</option>
                                                <option value="Lesotho">Lesotho</option>
                                                <option value="Liberia">Liberia</option>
                                                <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                                                <option value="Liechtenstein">Liechtenstein</option>
                                                <option value="Lithuania">Lithuania</option>
                                                <option value="Luxembourg">Luxembourg</option>
                                                <option value="Macau">Macau</option>
                                                <option value="Macedonia">Macedonia, The Former Yugoslav Republic of
                                                </option>
                                                <option value="Madagascar">Madagascar</option>
                                                <option value="Malawi">Malawi</option>
                                                <option value="Malaysia">Malaysia</option>
                                                <option value="Maldives">Maldives</option>
                                                <option value="Mali">Mali</option>
                                                <option value="Malta">Malta</option>
                                                <option value="Marshall Islands">Marshall Islands</option>
                                                <option value="Martinique">Martinique</option>
                                                <option value="Mauritania">Mauritania</option>
                                                <option value="Mauritius">Mauritius</option>
                                                <option value="Mayotte">Mayotte</option>
                                                <option value="Mexico">Mexico</option>
                                                <option value="Micronesia">Micronesia, Federated States of</option>
                                                <option value="Moldova">Moldova, Republic of</option>
                                                <option value="Monaco">Monaco</option>
                                                <option value="Mongolia">Mongolia</option>
                                                <option value="Montserrat">Montserrat</option>
                                                <option value="Morocco">Morocco</option>
                                                <option value="Mozambique">Mozambique</option>
                                                <option value="Myanmar">Myanmar</option>
                                                <option value="Namibia">Namibia</option>
                                                <option value="Nauru">Nauru</option>
                                                <option value="Nepal">Nepal</option>
                                                <option value="Netherlands">Netherlands</option>
                                                <option value="Netherlands Antilles">Netherlands Antilles</option>
                                                <option value="New Caledonia">New Caledonia</option>
                                                <option value="New Zealand">New Zealand</option>
                                                <option value="Nicaragua">Nicaragua</option>
                                                <option value="Niger">Niger</option>
                                                <option value="Nigeria">Nigeria</option>
                                                <option value="Niue">Niue</option>
                                                <option value="Norfolk Island">Norfolk Island</option>
                                                <option value="Northern Mariana Islands">Northern Mariana Islands
                                                </option>
                                                <option value="Norway">Norway</option>
                                                <option value="Oman">Oman</option>
                                                <option value="Pakistan">Pakistan</option>
                                                <option value="Palau">Palau</option>
                                                <option value="Panama">Panama</option>
                                                <option value="Papua New Guinea">Papua New Guinea</option>
                                                <option value="Paraguay">Paraguay</option>
                                                <option value="Peru">Peru</option>
                                                <option value="Philippines">Philippines</option>
                                                <option value="Pitcairn">Pitcairn</option>
                                                <option value="Poland">Poland</option>
                                                <option value="Portugal">Portugal</option>
                                                <option value="Puerto Rico">Puerto Rico</option>
                                                <option value="Qatar">Qatar</option>
                                                <option value="Reunion">Reunion</option>
                                                <option value="Romania">Romania</option>
                                                <option value="Russia">Russian Federation</option>
                                                <option value="Rwanda">Rwanda</option>
                                                <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                                <option value="Saint LUCIA">Saint LUCIA</option>
                                                <option value="Saint Vincent">Saint Vincent and the Grenadines</option>
                                                <option value="Samoa">Samoa</option>
                                                <option value="San Marino">San Marino</option>
                                                <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                                <option value="Saudi Arabia">Saudi Arabia</option>
                                                <option value="Senegal">Senegal</option>
                                                <option value="Seychelles">Seychelles</option>
                                                <option value="Sierra">Sierra Leone</option>
                                                <option value="Singapore">Singapore</option>
                                                <option value="Slovakia">Slovakia (Slovak Republic)</option>
                                                <option value="Slovenia">Slovenia</option>
                                                <option value="Solomon Islands">Solomon Islands</option>
                                                <option value="Somalia">Somalia</option>
                                                <option value="South Africa">South Africa</option>
                                                <option value="South Georgia">South Georgia and the South Sandwich
                                                    Islands</option>
                                                <option value="Span">Spain</option>
                                                <option value="SriLanka">Sri Lanka</option>
                                                <option value="St. Helena">St. Helena</option>
                                                <option value="St. Pierre and Miguelon">St. Pierre and Miquelon</option>
                                                <option value="Sudan">Sudan</option>
                                                <option value="Suriname">Suriname</option>
                                                <option value="Svalbard">Svalbard and Jan Mayen Islands</option>
                                                <option value="Swaziland">Swaziland</option>
                                                <option value="Sweden">Sweden</option>
                                                <option value="Switzerland">Switzerland</option>
                                                <option value="Syria">Syrian Arab Republic</option>
                                                <option value="Taiwan">Taiwan, Province of China</option>
                                                <option value="Tajikistan">Tajikistan</option>
                                                <option value="Tanzania">Tanzania, United Republic of</option>
                                                <option value="Thailand">Thailand</option>
                                                <option value="Togo">Togo</option>
                                                <option value="Tokelau">Tokelau</option>
                                                <option value="Tonga">Tonga</option>
                                                <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                                <option value="Tunisia">Tunisia</option>
                                                <option value="Turkey">Turkey</option>
                                                <option value="Turkmenistan">Turkmenistan</option>
                                                <option value="Turks and Caicos">Turks and Caicos Islands</option>
                                                <option value="Tuvalu">Tuvalu</option>
                                                <option value="Uganda">Uganda</option>
                                                <option value="Ukraine">Ukraine</option>
                                                <option value="United Arab Emirates">United Arab Emirates</option>
                                                <option value="United Kingdom">United Kingdom</option>
                                                <option value="United States">United States</option>
                                                <option value="United States Minor Outlying Islands">United States Minor
                                                    Outlying Islands</option>
                                                <option value="Uruguay">Uruguay</option>
                                                <option value="Uzbekistan">Uzbekistan</option>
                                                <option value="Vanuatu">Vanuatu</option>
                                                <option value="Venezuela">Venezuela</option>
                                                <option value="Vietnam">Viet Nam</option>
                                                <option value="Virgin Islands (British)">Virgin Islands (British)
                                                </option>
                                                <option value="Virgin Islands (U.S)">Virgin Islands (U.S.)</option>
                                                <option value="Wallis and Futana Islands">Wallis and Futuna Islands
                                                </option>
                                                <option value="Western Sahara">Western Sahara</option>
                                                <option value="Yemen">Yemen</option>
                                                <option value="Serbia">Serbia</option>
                                                <option value="Zambia">Zambia</option>
                                                <option value="Zimbabwe">Zimbabwe</option>


                                                <!------- seleted option-->
                                                <optgroup label="Selected Country">
                                                    <option {{$user->country ? 'selected' :''}}
                                                        value="{{$user->country}}" >{{$user->country}}</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h4>Authentication Rule</h4>
                                    <div class="row border">
                                        <div class="row">
                                            <h5 style="padding:10px;">Customer Details</h5>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label>Authentication Rule</label>
                                                <select class="custom-select form-control"
                                                    name="customer_authentication_rule"
                                                    id="customer_authentication_rule">
                                                    <option selected disabled>--Select Authentication Rule--</option>
                                                    <option value="0" {{$user->customer_authentication_rule == 0 ?
                                                        'selected' : ''}}>Account Name-Account Number</option>
                                                    <option value="1" {{$user->customer_authentication_rule == 1 ?
                                                        'selected' : ''}}>Account Number-Account Name</option>
                                                    <option value="2" {{$user->customer_authentication_rule == 2 ?
                                                        'selected' : ''}}>Account Name</option>
                                                    <option value="3" {{$user->customer_authentication_rule == 3 ?
                                                        'selected' : ''}}>Account Number</option>
                                                    <option value="4" {{$user->customer_authentication_rule == 4 ?
                                                        'selected' : ''}}>IP</option>
                                                    <option value="5" {{$user->customer_authentication_rule == 5 ?
                                                        'selected' : ''}}>Cli</option>
                                                    <option value="6" {{$user->customer_authentication_rule == 6 ?
                                                        'selected' : ''}}>Other</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 {{$user->customer_authentication_rule != 6 ? 'd-none' : ''}}"
                                            id="customer_authentication_value_div">
                                            <div class="form-group">
                                                <label for="customer_authentication_value">Value</label>
                                                <input type="text" class="form-control" id="city"
                                                    name="customer_authentication_value"
                                                    value="{{$user->customer_authentication_value}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row border">
                                        <div class="row">
                                            <h5 style="padding:10px;">Vendor Details</h5>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label>Authentication Rule</label>
                                                <select class="custom-select form-control"
                                                    name="vendor_authentication_rule" id="vendor_authentication_rule">
                                                    <option selected disabled>--Select Authentication Rule--</option>
                                                    <option value="0" {{$user->vendor_authentication_rule == 0 ?
                                                        'selected' : ''}}>Account Name-Account Number</option>
                                                    <option value="1" {{$user->vendor_authentication_rule == 1 ?
                                                        'selected' : ''}}>Account Number-Account Name</option>
                                                    <option value="2" {{$user->vendor_authentication_rule == 2 ?
                                                        'selected' : ''}}>Account Name</option>
                                                    <option value="3" {{$user->vendor_authentication_rule == 3 ?
                                                        'selected' : ''}}>Account Number</option>
                                                    <option value="4" {{$user->vendor_authentication_rule == 4 ?
                                                        'selected' : ''}}>IP</option>
                                                    <option value="5" {{$user->vendor_authentication_rule == 5 ?
                                                        'selected' : ''}}>Cli</option>
                                                    <option value="6" {{$user->vendor_authentication_rule == 6 ?
                                                        'selected' : ''}}>Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 {{$user->vendor_authentication_rule != 6 ? 'd-none' : ''}}"
                                            id="vendor_authentication_value_div">
                                            <div class="form-group">
                                                <label for="vendor_authentication_value">Value</label>
                                                <input type="text" class="form-control" id="vendor_authentication_value"
                                                    name="vendor_authentication_value"
                                                    value="{{$user->vendor_authentication_value}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-header">
                                        <h3 class="card-title">Account Details</h3>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label for="accountowner">Account Owner</label>

                                                <select class="custom-select form-control-border border-width-2"
                                                    name="lead_owner" id="lead_owner">
                                                    <optgroup label="Select option">
                                                        @foreach ($data as $dat )
                                                        <option value="{{$dat->id}}" {{$dat->id ==
                                                            $user->lead_owner}}>{{$dat->name}} ({{$dat->role}})</option>
                                                        @endforeach
                                                    </optgroup>
                                                </select>

                                                {{-- <select class="custom-select form-control" name="account_owner"
                                                    id="account_owner">
                                                    <option selected disabled>--Select Authentication Rule--</option>
                                                    <option value="0" {{$user->account_owner==0 ? 'selected' :
                                                        ''}}>Account Name: Account Number</option>
                                                    <option value="1" {{$user->account_owner ==1 ? 'selected' :
                                                        ''}}>Cold Call</option>
                                                    <option value="2" {{$user->account_owner ==2 ? 'selected' :
                                                        ''}}>Employee Referral</option>
                                                    <option value="3" {{$user->account_owner ==3 ? 'selected' :
                                                        ''}}>Online Store</option>
                                                </select> --}}
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label for="ownership">Ownership</label>
                                                <select class="custom-select form-control" name="ownership"
                                                    id="ownership">
                                                    <option value="">None</option>
                                                    <option value="Private" {{$user->ownership == 'Private' ? 'selected'
                                                        : ''}}>Private</option>
                                                    <option value="Public" {{$user->ownership == 'Public' ? 'selected' :
                                                        ''}}>Public</option>
                                                    <option value="Subsidiary" {{$user->ownership == 'Subsidiary' ?
                                                        'selected' : ''}}>Subsidiary</option>
                                                    <option value="Other" {{$user->ownership ==4 ? 'Other' : ''}}>Other
                                                    </option>

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label for="account_number">Account Number</label>
                                                <input type="text" class="form-control" id="account_number"
                                                    name="account_number" value="{{$user->account_number}}">
                                            </div>
                                        </div>

                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label for="account_name">Account Name<span style="color:red;">*</span></label>
                                                <input type="text" class="form-control" id="account_name"
                                                    name="account_name" value="{{$user->account_name}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <div class="form-check form-switch">
                                                <label for="Vendor">Vendor</label>
                                                <input class="form-check-input mt-5" type="checkbox" id="Vendor"
                                                    name="Vendor" value="1" {{$user->Vendor==1 ? 'checked' : ''}}>
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label for="accounttag">Account tags</label>
                                                <input type="text" class="form-control" id="account_tag"
                                                    name="account_tag" value="{{$user->account_tag}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <div class="form-check form-switch">
                                                <label for="customer">Customer</label>
                                                <input class="form-check-input mt-5" style="margin-left: -3.5em;"
                                                    type="checkbox" id="customer" name="customer" value="1" {{
                                                    $user->customer == 1 ? 'checked' : ''}}>
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label for="employee">Employee</label>
                                                <input type="text" class="form-control" id="employee" name="employee"
                                                    value="{{$user->employee}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label for="billingemail">Billing Email</label>
                                                <input type="email" class="form-control" id="billing_email"
                                                    name="billing_email" value="{{$user->billing_email}}">
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label for="language">Language</label>
                                                <select id="language" class="form-control" name="language">
                                                    <option value="english" {{ $user->language == 'english' ? 'selected'
                                                        : '' }}>English</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <div class="form-check form-switch">
                                                <label for="reseller">Reseller</label>
                                                <input class="form-check-input mt-5" style="margin-left: -3em;"
                                                    type="checkbox" name="reseller" value="1" {{ $user->reseller == 1 ?
                                                'checked' : ''}}>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label for="account_reseller">Account Reseller</label>
                                                <select class="custom-select form-control" name="account_reseller"
                                                    id="account_reseller">
                                                    <option value="" >Select</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label for="currency">Currency</label>
                                                <select class="custom-select form-control" name="currency"
                                                    id="currency">
                                                    <option value="0" {{$user->currency ==0 ? 'selected' : ''}}>USD
                                                    </option>
                                                    <option value="1" {{$user->currency ==1 ? 'selected' : ''}}>GBP
                                                    </option>
                                                    <option value="2" {{$user->currency ==2 ? 'selected' : ''}}>AED
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <label for="currency">Timezone</label>
                                            <div class="form-group timepicker" twelvehour="true">
                                                {{-- <div class="selectpicker"></div> --}}
                                                <select class="form-control selectpicker" id="timezone"
                                                    name="timezone"></select>
                                                {{-- <input type="timezone" class="form-control timedemo"
                                                    name="timezone" value="{{$user->timezone}}"
                                                    placeholder="hh:mm am/pm"> --}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label for="verification_status">Verification Status</label>
                                                <input type="text" class="form-control" id="verification_status"
                                                    name="verification_status" value="{{$user->verification_status}}">
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label for="norminal_code">Norminal Code</label>
                                                <input type="text" class="form-control" id="norminal_code"
                                                    name="norminal_code" value="{{$user->norminal_code}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <h4>Account Credits</h4>
                                    <div class="row border">
                                        <div class="row">
                                            <h5 style="padding:10px;">Credit Control</h5>

                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label>Account Balance</label>
                                                    <input type="text" class="form-control" id="account_balance"
                                                        name="account_balance" value="{{$user->account_balance}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-4">
                                                <div class="form-group">
                                                    <label>Customer Unbilled Ammount</label>
                                                    <input type="text" class="form-control"
                                                        id="customer_unbilled_ammount" name="customer_unbilled_ammount"
                                                        value="{{$user->customer_unbilled_ammount}}">
                                                </div>
                                            </div>
                                            <div class="col-xl-4">
                                                <div class="form-group">
                                                    <label>Vendor Unbilled Ammount</label>
                                                    <input type="text" class="form-control" id="vendor_unbilled_ammount"
                                                        name="vendor_unbilled_ammount"
                                                        value="{{$user->vendor_unbilled_ammount}}">
                                                </div>
                                            </div>
                                            <div class="col-xl-4">
                                                {{-- <div class="form-group"> --}}
                                                    {{-- <label> test</label> --}}
                                                    <button type="button" class="btn btn-dark ms-4"
                                                        style="margin-top: 31px" ;><i
                                                            class="fa fa-eye white-color pe-2"></i>View Report</button>
                                                    {{--
                                                </div> --}}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label>Account Exposure</label>
                                                    <input type="text" class="form-control" id="account_exposure"
                                                        name="account_exposure" value="{{$user->account_exposure}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label>Available Credit Limit</label>
                                                    <input type="text" class="form-control" id="available_credit_limit"
                                                        name="available_credit_limit"
                                                        value="{{$user->available_credit_limit}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label>Credit Limit</label>
                                                    <input type="text" class="form-control" id="credit_limit"
                                                        name="credit_limit" value="{{$user->credit_limit}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label>Balance Threshold</label>
                                                    <input type="text" class="form-control" id="balance_threshold"
                                                        name="balance_threshold" value="{{$user->balance_threshold}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row border">
                                        <div class="col-xl-6">
                                            <h5 style="padding:10px;">Billing</h5>
                                        </div>
                                        <div class="col-xl-6">
                                            <label class="float-end form-check form-switch">
                                                <input class="form-check-input mt-3 textbox1" type="checkbox"
                                                    id="switch" name="billing_status" value="active"
                                                    {{$user->billing_status=='active' ? 'checked' : ''}}>
                                            </label>
                                        </div>
                                        <input type="hidden" name="billing_id" value="{{@$billingdata->id}}">
                                        <div class="product {{$user->billing_status == 'inactive' ? 'd-none' : ''}}">
                                            <div class="row">
                                                <div class="col-xl-6">
                                                    <div class="form-group">
                                                        <label for="billing_class">Billing Class<span style="color:red;">*</span></label>
                                                        <select class="custom-select form-control" name="billing_class"
                                                            id="billing_class">
                                                            <option value="">Select</option>
                                                            <option value="1" @if(!empty($billingdata->billing_class))
                                                                {{$billingdata->billing_class ==1 ? 'selected' : ''}}
                                                                @endif >Default</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6">
                                                    <div class="form-group">
                                                        <label for="billing_type">Billing Type<span style="color:red;">*</span></label>
                                                        <select class="custom-select form-control" name="billing_type"
                                                            id="billing_type">
                                                            <option value="1" @if(!empty($billingdata->billing_type))
                                                                {{$billingdata->billing_type ==1 ? 'selected' : ''}}
                                                                @endif>Prepaid</option>
                                                            <option value="2" @if(!empty($billingdata->billing_type))
                                                                {{$billingdata->billing_type ==2 ? 'selected' : ''}}
                                                                @endif>Postpaid</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-6">
                                                    <div class="form-group" twelvehour="true">
                                                        <label for="billing_timezone">Billing Timezone<span style="color:red;">*</span></label>
                                                        <div class="form-group timepicker" twelvehour="true">
                                                            <select class="form-control selectpicker"
                                                                name="billing_timezone" id="billing_timezone"></select>
                                                            {{-- <input type="text" class="form-control selectpicker"
                                                                name="billing_timezone"
                                                                value="@if(!empty($billingdata->billing_timezone))  {{$billingdata->billing_timezone}} @endif"
                                                                placeholder="hh:mm am/pm"> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6">
                                                    @if(!empty($billingdata->billing_startdate))
                                                        <div class="form-group mt-4">
                                                            <label>Billing Start Date<span style="color:red;">*</span></label>
                                                            <input  data-date-format="yyyy-mm-dd"  type="text"  class="form-control hidden datepicker  billing_start_date" id="billing_start_date" name="billing_startdate"
                                                            value="{{ $billingdata->billing_startdate}}">
                                                            <span class="text ml-2">
                                                                {{ $billingdata->billing_startdate}} 
                                                            </span>
                                                        </div>
                                                    @else
                                                        <div class="form-group ">
                                                            <label>Billing Start Date<span style="color:red;">*</span></label>
                                                            <input  data-date-format="yyyy-mm-dd"  type="text"  class="form-control datepicker  billing_start_date" id="billing_start_date" name="billing_startdate" value="">
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-6">
                                                    @if($user->billing_status=='active')
                                                        <div class="form-group">
                                                            <label for="billing_cycle">Billing Cycle<span style="color:red;">*</span></label>
                                                            <select class="custom-select  form-control hidden" name="billing_cycle"
                                                                id="billing_cycle">
                                                                <option value="">Select</option>
                                                                <option value="daily" @if(!empty($billingdata->
                                                                    billing_cycle)) {{$billingdata->billing_cycle=='daily' ?
                                                                    'selected' : ''}} @endif>Daily</option>
                                                                <option value="fortnightly" @if(!empty($billingdata->
                                                                    billing_cycle))
                                                                    {{$billingdata->billing_cycle=='fortnightly' ?
                                                                    'selected' : ''}} @endif>Fortnightly</option>
                                                                <option value="in_specific_days" @if(!empty($billingdata->
                                                                    billing_cycle)){{$billingdata->billing_cycle=='in_specific_days'
                                                                    ? 'selected' : ''}} @endif>In Specific days</option>
                                                                <option value="manual" @if(!empty($billingdata->
                                                                    billing_cycle)){{$billingdata->billing_cycle=='manual' ?
                                                                    'selected' : ''}} @endif>Manual</option>
                                                                <option value="monthly" @if(!empty($billingdata->
                                                                    billing_cycle)){{$billingdata->billing_cycle=='monthly'
                                                                    ? 'selected' : ''}} @endif>Monthly</option>
                                                                <option value="monthly_anniversary"
                                                                    @if(!empty($billingdata->
                                                                    billing_cycle)){{$billingdata->billing_cycle=='monthly_anniversary'
                                                                    ? 'selected' : ''}} @endif>Monthly anniversary</option>
                                                                <option value="quarterly" @if(!empty($billingdata->
                                                                    billing_cycle)){{$billingdata->billing_cycle=='quarterly'
                                                                    ? 'selected' : ''}} @endif>Quarterly</option>
                                                                <option value="weekly" @if(!empty($billingdata->
                                                                    billing_cycle)){{$billingdata->billing_cycle=='weekly' ?
                                                                    'selected' : ''}} @endif>Weekly</option>
                                                                <option value="yearly" @if(!empty($billingdata->
                                                                    billing_cycle)){{$billingdata->billing_cycle=='yearly' ?
                                                                    'selected' : ''}} @endif>Yearly</option>
                                                            </select>
                                                            <span class="bill_cycle_edit_text ml-2">
                                                                @if(!empty($billingdata->billing_cycle))
                                                                {{ $billingdata->billing_cycle}} 
                                                                @endif
                                                            </span>
                                                            {{-- <span><a href="#" class=" btn btn-dark btn-sm Edit_bill_cycle ml-2" style="font-size: 8px;" ><i class="fas fa-pen"></i></a></span> --}}
                                                        </div>
                                                    @else
                                                        <div class="form-group">
                                                            <label for="billing_cycle">Billing Cycle<span style="color:red;">*</span></label>
                                                            <select class="custom-select  form-control " name="billing_cycle"
                                                                id="billing_cycle">
                                                                <option value="">Select</option>
                                                                <option value="daily" @if(!empty($billingdata->
                                                                    billing_cycle)) {{$billingdata->billing_cycle=='daily' ?
                                                                    'selected' : ''}} @endif>Daily</option>
                                                                <option value="fortnightly" @if(!empty($billingdata->
                                                                    billing_cycle))
                                                                    {{$billingdata->billing_cycle=='fortnightly' ?
                                                                    'selected' : ''}} @endif>Fortnightly</option>
                                                                <option value="in_specific_days" @if(!empty($billingdata->
                                                                    billing_cycle)){{$billingdata->billing_cycle=='in_specific_days'
                                                                    ? 'selected' : ''}} @endif>In Specific days</option>
                                                                <option value="manual" @if(!empty($billingdata->
                                                                    billing_cycle)){{$billingdata->billing_cycle=='manual' ?
                                                                    'selected' : ''}} @endif>Manual</option>
                                                                <option value="monthly" @if(!empty($billingdata->
                                                                    billing_cycle)){{$billingdata->billing_cycle=='monthly'
                                                                    ? 'selected' : ''}} @endif>Monthly</option>
                                                                <option value="monthly_anniversary"
                                                                    @if(!empty($billingdata->
                                                                    billing_cycle)){{$billingdata->billing_cycle=='monthly_anniversary'
                                                                    ? 'selected' : ''}} @endif>Monthly anniversary</option>
                                                                <option value="quarterly" @if(!empty($billingdata->
                                                                    billing_cycle)){{$billingdata->billing_cycle=='quarterly'
                                                                    ? 'selected' : ''}} @endif>Quarterly</option>
                                                                <option value="weekly" @if(!empty($billingdata->
                                                                    billing_cycle)){{$billingdata->billing_cycle=='weekly' ?
                                                                    'selected' : ''}} @endif>Weekly</option>
                                                                <option value="yearly" @if(!empty($billingdata->
                                                                    billing_cycle)){{$billingdata->billing_cycle=='yearly' ?
                                                                    'selected' : ''}} @endif>Yearly</option>
                                                            </select>
                                                        </div>
                                                    @endif
                                                   
                                                </div>
                                                <div class="col-xl-6">
                                                    <div class="form-group  {{@$billingdata->billing_cycle !='in_specific_days' ? 'd-none' : ''}} "
                                                        id="in_specific_days">
                                                        <label for="billing_cycle_startday">Billing Cycle - for
                                                            Days<span style="color:red;">*</span></label>
                                                        <input type="text" name="billing_cycle_startday_for_days"
                                                            class="form-control billing_cycle_startday" id="number_only"
                                                            value="{{ @$billingdata->billing_cycle_startday }}">
                                                    </div>
                                                    <div class="form-group  {{@$billingdata->billing_cycle !='monthly_anniversary' ? 'd-none' : ''}} "
                                                        id="monthly_anniversary">
                                                        <label for="billing_cycle_startday">Billing Cycle - Monthly
                                                            Anniversary Date<span style="color:red;">*</span></label>
                                                        <input type="text" name="billing_cycle_startday_for_monthly"
                                                            class="form-control datepicker billing_cycle_startday" data-date-format="yyyy-mm-dd"
                                                            value="{{ @$billingdata->billing_cycle_startday}}">
                                                    </div>
                                                    <div class="form-group  {{@$billingdata->billing_cycle !='weekly' ? 'd-none' : ''}} "
                                                        id="week">
                                                        <label for="billing_cycle_startday">Billing Cycle Start of
                                                            Day<span style="color:red;">*</span></label>
                                                        <select
                                                            class="custom-select form-control billing_cycle_startday"
                                                            name="billing_cycle_startday" id="billing_cycle_startday">
                                                            <option data-id="0" value="Sunday" @if(!empty($billingdata->billing_cycle_startday))
                                                                {{$billingdata->billing_cycle_startday=='Sunday' ?
                                                                'selected' : ''}} @endif>Sunday</option>
                                                            <option data-id="1" value="Monday" @if(!empty($billingdata->billing_cycle_startday))
                                                                {{$billingdata->billing_cycle_startday=='Monday' ?
                                                                'selected' : ''}} @endif>Monday</option>
                                                            <option data-id="2" value="Tuesday" @if(!empty($billingdata->billing_cycle_startday))
                                                                {{$billingdata->billing_cycle_startday=='Tuesday' ?
                                                                'selected' : ''}}@endif>Tuesday</option>
                                                            <option data-id="3" value="Wednesday" @if(!empty($billingdata->billing_cycle_startday))
                                                                {{$billingdata->billing_cycle_startday=='Wednesday' ?
                                                                'selected' : ''}}@endif>Wednesday</option>
                                                            <option data-id="4" value="Thursday" @if(!empty($billingdata->billing_cycle_startday))
                                                                {{$billingdata->billing_cycle_startday=='Thursday' ?
                                                                'selected' : ''}}@endif>Thursday</option>
                                                            <option  data-id="5" value="Friday" @if(!empty($billingdata->billing_cycle_startday))
                                                                {{$billingdata->billing_cycle_startday=='Friday' ?
                                                                'selected' : ''}} @endif>Friday</option>
                                                            <option data-id="6" value="Saturday" @if(!empty($billingdata->billing_cycle_startday))
                                                                {{$billingdata->billing_cycle_startday=='Saturday' ?
                                                                'selected' : ''}}@endif>Saturday</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-6">
                                                    <div class="form-group">
                                                        <label for="billing_cycle_startdate">Auto Pay</label>
                                                        <select class="custom-select form-control" name="auto_pay"
                                                            id="auto_pay">
                                                            <option value="">Never</option>
                                                            <option value="1" @if(!empty($billingdata->auto_pay))
                                                                {{$billingdata->auto_pay==1 ? 'selected' : ''}}
                                                                @endif>On Invoice Date</option>
                                                            <option value="2" @if(!empty($billingdata->auto_pay))
                                                                {{$billingdata->auto_pay==2 ? 'selected' : ''}}
                                                                @endif>On Due Date</option>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6">
                                                    <div class="form-group">
                                                        <label for="auto_pay_method">Auto Pay Method</label>
                                                        <select class="custom-select form-control"
                                                            name="auto_pay_method" id="auto_pay_method">
                                                            <option value="">Select</option>
                                                            <option value="1" @if(!empty($billingdata->auto_pay_method))
                                                                {{$billingdata->auto_pay_method==1 ? 'selected' : ''}}
                                                                @endif>Account Balance</option>
                                                            <option value="2" @if(!empty($billingdata->auto_pay_method))
                                                                {{$billingdata->auto_pay_method==2 ? 'selected' : ''}}
                                                                @endif>Preferred Method</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                           
                                            <div class="row">
                                                <div class="col-xl-6">
                                                    <div class="form-group">
                                                        <label for="send_invoice_via_email">Send Invoice By Email</label>
                                                        <select class="custom-select form-control" name="send_invoice_via_email"
                                                            id="send_invoice_via_email">
                                                            <option value="">Please select an Option</option>
                                                            <option value="1" @if(!empty($billingdata->
                                                                send_invoice_via_email)){{$billingdata->send_invoice_via_email==1
                                                                ? "selected" : ''}} @endif>Automatically</option>
                                                            <option value="2" @if(!empty($billingdata->send_invoice_via_email))
                                                                {{$billingdata->send_invoice_via_email==2 ? "selected" : ''}}
                                                                @endif>After Admin Review</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            @if($user->billing_status=='active')
                                                <div class="row">
                                                    <div class="col-xl-6">
                                                        <div class="form-group">
                                                            <input type="hidden" name="last_invoice_date" value="{{@$billingdata->last_invoice_date}}">
                                                            <label for="last_invoice_date">Last Invoice Date</label>
                                                            <span>{{@$billingdata->last_invoice_date}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-6">
                                                        <div class="form-group d-flex">
                                                            <label for="next_invoice_date">Next Invoice Date</label>
                                                            <input class="form-control datepicker hidden next_invoice_date" id="next_invoice_date" data-date-format="yyyy-mm-dd" name="next_invoice_date" type="text" style="width: 50%; margin-left:10px;" value=" @if(!empty($billingdata->next_invoice_date)){{ $billingdata->next_invoice_date}}@endif">
                                                            
                                                            <span class="next_invoice_edit_text ml-2 next_invoice_date">
                                                                @if(!empty($billingdata->next_invoice_date))
                                                                {{ $billingdata->next_invoice_date}} 
                                                                @endif
                                                            </span>
                                                            {{-- <span><a href="#" class=" btn btn-dark btn-sm Edit ml-2"  style="font-size: 8px;"><i class="fas fa-pen" ></i></a></span> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-xl-6">
                                                        <div class="form-group">
                                                            <input type="hidden" name="last_charge_date" value="{{@$billingdata->last_charge_date}}">
                                                            <label for="last_charge_date">Last Charge Date</label>
                                                            <span>{{@$billingdata->last_charge_date}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-6">
                                                        <div class="form-group">
                                                            <input type="hidden" name="next_charge_date" value="{{@$billingdata->next_charge_date}}">
                                                            <label for="next_charge_date">Next Charge Date</label>
                                                            <span>{{@$billingdata->next_charge_date}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="row">
                                                    <div class="col-xl-6">
                                                        <div class="form-group">
                                                            <label for="next_invoice_date">Next Invoice Date</label>
                                                            <input type="text" class="form-control datepicker next_invoice_date" id="next_invoice_date" data-date-format="yyyy-mm-dd" name="next_invoice_date"
                                                                value="">
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-6">
                                                        <div class="form-group">
                                                            <label for="next_charge_date">Next Charge Date</label>
                                                            <input type="text" class="form-control datepicker" name="next_charge_date"  data-date-format="yyyy-mm-dd" id="next_charge_date"
                                                                value="" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row border">
                                        <div class="row">
                                            <h5 style="padding:10px;">Discount</h5>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="outbound_discount_plan">Outbound Discount Plan</label>
                                                    <select class="custom-select form-control"
                                                        name="outbound_discount_plan" id="outbound_discount_plan">
                                                        <option value="">Select</option>
                                                        <option value="1" @if(!empty($billingdata->
                                                            outbound_discount_plan)){{$billingdata->outbound_discount_plan==1
                                                            ? 'selected' : ''}}@endif>Test</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="inbound_discount_plan">Inbound Discount Plan</label>
                                                    <select class="custom-select form-control"
                                                        name="inbound_discount_plan" id="inbound_discount_plan">
                                                        <option value="">Select</option>
                                                        <option value="1" @if(!empty($billingdata->
                                                            inbound_discount_plan)){{$billingdata->inbound_discount_plan==1
                                                            ? 'selected' : ''}} @endif>Test</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" id="submit" class="btn btn-primary">Submit</button>
                                <button type="button" id="cancel" class="btn btn-danger">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
{{-- Timepicker --}}



@endsection

@section('page_js')
<script src="{{asset('js/timezones.full.js')}}"></script>
<script src="{{asset('js/account_custom.js')}}"></script>


<script type="text/javascript">
    $(document).ready(function () {
        var timezone = "{{@$user->timezone}}";
        $('#timezone > option').each(function() {
            if($(this).val() == timezone) {
                $(this).attr('selected', 'selected');
            }
        });
        var billing_timezone = "{{@$billingdata->billing_timezone}}"
        $('#billing_timezone > option').each(function() {
            if($(this).val() == billing_timezone) {
                $(this).attr('selected', 'selected');
            }
        }); 
    });

    $('#submit').click(function (e) {
        e.preventDefault();
        let formdata = $('#Clientform').serialize();
        let url =   $('#Clientform').attr('action');
        let method =   $('#Clientform').attr('method');
        save(formdata,url,method);

    });
    $('.Edit').click(function (e){
        e.preventDefault();
        $('.next_invoice_edit_text').addClass('hidden');
        $('#next_invoice_date').removeClass('hidden');
        $('.Edit').addClass('d-none');
    })
    $('.Edit_bill_cycle').click(function (e){
        e.preventDefault();
        $('.bill_cycle_edit_text').addClass('hidden');
        $('#billing_cycle').removeClass('hidden');
        $('.Edit_bill_cycle').addClass('d-none');
        $('.next_invoice_edit_text').addClass('hidden');
        $('#next_invoice_date').removeClass('hidden');
        $('.Edit').addClass('d-none');
    })

    $(function () {
        $(".datepicker").datepicker({ 
                autoclose: true, 
                todayHighlight: true
        });
    });

</script>
@endsection