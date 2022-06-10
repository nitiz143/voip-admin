@extends('layouts.dashboard')

@section('content')
<style>
    .form-check {
        padding-left: 0.25rem !important;
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
                                <h3 class="card-title">Lead Information</h3>
                            </div>

                            <form action="{{ route('client.update',$user->id) }}" method="PUT" id="Clientform">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="form-group">
                                        <input type="hidden" name="id" value="{{$user->id}}">
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label>Lead Owner</label>
                                                <select class="custom-select form-control-border border-width-2" name="lead_owner" id="lead_owner">
                                                     @if (Auth::user()->role == 'Admin')
                                                     <optgroup label="Selected Option">
                                                        <option value="{{$user->lead_owner}}">{{$user->lead_owner}}</option>
                                                     </optgroup>
                                                     <optgroup label="Select option">
                                                        @foreach ($data as $dat )
                                                            <option value="{{$dat->name}}{{$dat->role}} ">{{$dat->name}}{{$dat->role}}</option>
                                                        @endforeach
                                                     </optgroup>
                                                    @endif

                                                        @if (Auth::user()->role == 'NOC Executive'||Auth::user()->role == 'Rate Executive'||Auth::user()->role == 'Sales Executive'||Auth::user()->role == 'Billing Executive')
                                                        <optgroup label="Selected Option">
                                                            <option value="{{$user->lead_owner}}">{{$user->lead_owner}}</option>
                                                         </optgroup>
                                                         <optgroup label="Select option">
                                                            @foreach ($data as $dat )
                                                                <option value="{{$dat->name}}{{$dat->role}}" disabled>{{$dat->name}}{{$dat->role}}</option>
                                                            @endforeach
                                                         </optgroup>
                                                        @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label for="company">Company</label>
                                                <input type="text" class="form-control" id="company" name="company" value="{{$user->company}}">
                                            </div>
                                        </div>
                                    </div>
                                    <!----------2 row ---------->
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label for="firstname">First Name</label>
                                                <input type="text" class="form-control" id="firstname" name="firstname" value="{{$user->firstname}}">
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label for="lastname">Last Name</label>
                                                <input type="text" class="form-control" id="lastname" name="lastname" value="{{$user->lastname}}">
                                            </div>
                                        </div>
                                    </div>
                                     <!----------3 row ---------->
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="text" class="form-control" id="email" name="email" value="{{$user->email}}">
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label for="skype_id">Skype ID</label>
                                                <input type="text" class="form-control" id="skype_id" name="skype_id" value="{{$user->skype_id}}">
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
                                                <input type="text" class="form-control" id="fax" name="fax" value="{{$user->fax}}">
                                            </div>
                                        </div>
                                    </div>
                                    <!----------5 row ---------->
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label for="mobile">Mobile</label>
                                                <input type="text" class="form-control" id="mobile" name="mobile" value="{{$user->mobile}}">
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label for="website">Website</label>
                                                <input type="text" class="form-control" id="website" name="website" value="{{$user->website}}">
                                            </div>
                                        </div>
                                    </div>
                                    <!----------5 row ---------->
                                    <div class="row">
                                        <div class="col-xl-6">
                                            {{-- <div class="form-group">
                                                <label>Lead Source</label>
                                                <select class="custom-select form-control-border border-width-2" name="lead_source" id="lead_source">
                                                    <option selected disabled>--Select Lead Source--</option>
                                                    <option value="Advertisment" {{"Advertisment" == $user->lead_source ? 'selected' : ''}}>Advertisment</option>
                                                    <option value="Cold Call" {{"Cold Call" == $user->lead_source ? 'selected' : ''}}>Cold Call</option>
                                                    <option value="Employee Referral" {{"Employee Referral" == $user->lead_source ? 'selected' : ''}}>Employee Referral</option>
                                                    <option value="Online Store" {{"Online Store" == $user->lead_source ? 'selected' : ''}}>Online Store</option>
                                                    <option value="Partner" {{"Partner" == $user->lead_source ? 'selected' : ''}}>Partner</option>
                                                    <option value="Public Relations" {{"Public Relations" == $user->lead_source ? 'selected' : ''}}>Public Relations</option>
                                                    <option value="Sales Mail Alias" {{"Sales Mail Alias" == $user->lead_source ? 'selected' : ''}}>Sales Mail Alias</option>
                                                    <option value="Saminer Partner" {{"Saminer Partner" == $user->lead_source ? 'selected' : ''}}>Saminer Partner</option>
                                                </select>
                                            </div> --}}
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                {{-- <label >Lead Status</label>
                                                <select class="custom-select form-control-border border-width-2" name="lead_status" id="lead_status">
                                                    <option selected disabled>--Select Lead Status--</option>
                                                    <option value="Attempted to Contact" {{"Attempted to Contact" == $user->lead_status ? 'selected' : ''}}>Attempted to Contact</option>
                                                    <option value="Contact Future" {{"Contact Future" == $user->lead_status ? 'selected' : ''}} >Contact Future</option>
                                                    <option value="Contacted" {{"Contacted" == $user->lead_status ? 'selected' : ''}}>Contacted</option>
                                                    <option value="Junk Leak" {{"Junk Leak" == $user->lead_status ? 'selected' : ''}}>Junk Leak</option>
                                                    <option value="Not Contacted " {{"Not Contacted" == $user->lead_status ? 'selected' : ''}}>Not Contacted</option>
                                                    <option value="Pre Qualified " {{"Pre Qualified" == $user->lead_status ? 'selected' : ''}}>Pre Qualified</option>
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
                                                <select class="custom-select form-control-border border-width-2" name="rating" id="rating">
                                                    <option selected disabled>--Select Rating--</option>
                                                    <option value="Aquired" {{"Aquired" == $user->rating ? 'selected' : ''}}>Aquired</option>
                                                    <option value="Active" {{"Active" == $user->rating ? 'selected' : ''}}>Active</option>
                                                    <option value="Market Failed" {{"Market Failed" == $user->rating ? 'selected' : ''}}>Market Failed</option>
                                                    <option value="Project Cencel" {{"Project Cencel" == $user->rating ? 'selected' : ''}}>Project Cencel</option>
                                                    <option value="Shutdown" {{"Shutdown" == $user->rating ? 'selected' : ''}}>Shutdown</option>
                                                </select> --}}
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            {{-- <div class="form-group">
                                                <label for="employee">No. of Employees</label>
                                                <input type="text" class="form-control" id="employee" name="employee" value="{{$user->employee}}">
                                            </div> --}}
                                        </div>
                                    </div>
                                    <!----------8 row ---------->
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label for="status">Status</label>
                                                <select class="custom-select form-control-border border-width-2" name="status" id="status">
                                                    <option selected disabled>--Select Status--</option>
                                                    <option value="0" {{"0" == $user->status ? 'selected' : ''}}>Active</option>
                                                    <option value="1" {{"1" == $user->status ? 'selected' : ''}}>Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label for="vat_number">VAT Number</label>
                                                <input type="text" class="form-control" id="vat_number" name="vat_number" value="{{$user->vat_number}}">
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
                                                <input type="text" class="form-control" id="address_line1" name="address_line1" value="{{$user->address_line1}}">
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label for="city">City</label>
                                                <input type="text" class="form-control" id="city" name="city" value="{{$user->city}}">
                                            </div>
                                        </div>
                                    </div>
                                    <!-------- address row2--------->
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label for="address_line2">Address Line2</label>
                                                <input type="text" class="form-control" id="address_line2" name="address_line2" value="{{$user->address_line2}}">
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label for="postzip">PostZip Code</label>
                                                <input type="text" class="form-control" id="postzip" name="postzip" value="{{$user->postzip}}">
                                            </div>
                                        </div>
                                    </div>
                                      <!-------- address row3--------->
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label for="address_line3">Address Line3</label>
                                                <input type="text" class="form-control" id="address_line3" name="address_line3" value="{{$user->address_line3}}">
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label for="country">Country</label>
                                                <select class="custom-select form-control-border border-width-2" name="country" id="country">
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
                                                    <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                                                    <option value="Brunei Darussalam">Brunei Darussalam</option>
                                                    <option value="Bulgaria">Bulgaria</option>
                                                    <option value="Burkina Faso">Burkina Faso</option>
                                                    <option value="Burundi">Burundi</option>
                                                    <option value="Cambodia">Cambodia</option>
                                                    <option value="Cameroon">Cameroon</option>
                                                    <option value="Canada">Canada</option>
                                                    <option value="Cape Verde">Cape Verde</option>
                                                    <option value="Cayman Islands">Cayman Islands</option>
                                                    <option value="Central African Republic">Central African Republic</option>
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
                                                    <option value="French Southern Territories">French Southern Territories</option>
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
                                                    <option value="Heard and McDonald Islands">Heard and Mc Donald Islands</option>
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
                                                    <option value="Democratic People's Republic of Korea">Korea, Democratic People's Republic of</option>
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
                                                    <option value="Macedonia">Macedonia, The Former Yugoslav Republic of</option>
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
                                                    <option value="Northern Mariana Islands">Northern Mariana Islands</option>
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
                                                    <option value="South Georgia">South Georgia and the South Sandwich Islands</option>
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
                                                    <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                                                    <option value="Uruguay">Uruguay</option>
                                                    <option value="Uzbekistan">Uzbekistan</option>
                                                    <option value="Vanuatu">Vanuatu</option>
                                                    <option value="Venezuela">Venezuela</option>
                                                    <option value="Vietnam">Viet Nam</option>
                                                    <option value="Virgin Islands (British)">Virgin Islands (British)</option>
                                                    <option value="Virgin Islands (U.S)">Virgin Islands (U.S.)</option>
                                                    <option value="Wallis and Futana Islands">Wallis and Futuna Islands</option>
                                                    <option value="Western Sahara">Western Sahara</option>
                                                    <option value="Yemen">Yemen</option>
                                                    <option value="Serbia">Serbia</option>
                                                    <option value="Zambia">Zambia</option>
                                                    <option value="Zimbabwe">Zimbabwe</option>


                                                    <!------- seleted option-->
                                                    <optgroup label="Selected Country">
                                                    <option {{$user->country ? 'selected' :''}} value="{{$user->country}}"  >{{$user->country}}</option>
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
                                                    <select class="custom-select form-control" name="customer_authentication_rule" id="customer_authentication_rule">
                                                        <option selected disabled>--Select Authentication Rule--</option>
                                                        <option value="0" {{$user->customer_authentication_rule == 0 ? 'selected' : ''}}>Account Name-Account Number</option>
                                                        <option value="1" {{$user->customer_authentication_rule == 1 ? 'selected' : ''}}>Account Number-Account Name</option>
                                                        <option value="2" {{$user->customer_authentication_rule == 2 ? 'selected' : ''}}>Account Name</option>
                                                        <option value="3" {{$user->customer_authentication_rule == 3 ? 'selected' : ''}}>Account Number</option>
                                                        <option value="4" {{$user->customer_authentication_rule == 4 ? 'selected' : ''}}>IP</option>
                                                        <option value="5" {{$user->customer_authentication_rule == 5 ? 'selected' : ''}}>Cli</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="customer_authentication_value">Value</label>
                                                    <input type="text" class="form-control" id="city" name="customer_authentication_value" value="{{$user->customer_authentication_value}}">
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
                                                    <select class="custom-select form-control" name="vendor_authentication_rule" id="vendor_authentication_rule">
                                                        <option selected disabled>--Select Authentication Rule--</option>
                                                        <option value="0" {{$user->vendor_authentication_rule == 0 ? 'selected' : ''}}>Account Name-Account Number</option>
                                                        <option value="1" {{$user->vendor_authentication_rule == 1 ? 'selected' : ''}}>Account Number-Account Name</option>
                                                        <option value="2" {{$user->vendor_authentication_rule == 2 ? 'selected' : ''}}>Account Name</option>
                                                        <option value="3" {{$user->vendor_authentication_rule == 3 ? 'selected' : ''}}>Account Number</option>
                                                        <option value="4" {{$user->vendor_authentication_rule == 4 ? 'selected' : ''}}>IP</option>
                                                        <option value="5" {{$user->vendor_authentication_rule == 5 ? 'selected' : ''}}>Cli</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="vendor_authentication_value">Value</label>
                                                    <input type="text" class="form-control" id="vendor_authentication_value" name="vendor_authentication_value" value="{{$user->vendor_authentication_value}}">
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
                                                    <select class="custom-select form-control" name="account_owner" id="account_owner">
                                                        <option selected disabled>--Select Authentication Rule--</option>
                                                        <option value="0" {{$user->account_owner==0 ? 'selected' : ''}}>Account Name: Account Number</option>
                                                        <option value="1" {{$user->account_owner ==1 ? 'selected' : ''}}>Cold Call</option>
                                                        <option value="2" {{$user->account_owner ==2  ? 'selected' : ''}}>Employee Referral</option>
                                                        <option value="3" {{$user->account_owner ==3 ? 'selected' : ''}}>Online Store</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="ownership">Ownership</label>
                                                    <select class="custom-select form-control" name="ownership" id="ownership">
                                                        <option selected disabled>--Select Authentication Rule--</option>
                                                        <option value="0" {{$user->ownership ==0 ? 'selected' : ''}}>Account Name: Account Number</option>
                                                        <option value="1" {{$user->ownership ==1 ? 'selected' : ''}}>Cold Call</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="account_number">Account Number</label>
                                                    <input type="text" class="form-control" id="account_number" name="account_number" value="{{$user->account_number}}">
                                                </div>
                                            </div>
                                        
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="account_name">Account Name</label>
                                                    <input type="text" class="form-control" id="account_name" name="account_name" value="{{$user->account_name}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-check form-switch">
                                                    <label for="Vendor">Vendor</label>
                                                    <input class="form-check-input mt-5" type="checkbox" id="Vendor" name="Vendor" value="1" {{$user->Vendor==1 ? 'checked' : ''}}>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="accounttag">Account tags</label>
                                                    <input type="text" class="form-control" id="account_tag" name="account_tag" value="{{$user->account_tag}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-check form-switch">
                                                    <label for="customer">Customer</label>
                                                    <input  class="form-check-input mt-5" style="margin-left: -3.5em;" type="checkbox" id="customer" name="customer" value="1" {{ $user->customer == 1 ? 'checked' : ''}}>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="employee">Employee</label>
                                                    <input type="text" class="form-control" id="employee" name="employee" value="{{$user->employee}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="billingemail">Billing Email</label>
                                                    <input type="email" class="form-control" id="billing_email" name="billing_email" value="{{$user->billing_email}}">
                                                </div>
                                            </div>
                                             <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="language">Language</label>
                                                    <input type="text" class="form-control" id="language" name="language" value="{{$user->language}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-check form-switch">
                                                    <label for="reseller">Reseller</label>
                                                        <input class="form-check-input mt-5" style="margin-left: -3em;" type="checkbox" name="reseller" value="1" {{ $user->reseller == 1 ? 'checked' : ''}}>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="account_reseller">Account Reseller</label>
                                                    <select class="custom-select form-control" name="account_reseller" id="account_reseller">
                                                        <option selected disabled>--Select Account Reseller--</option>
                                                        <option value="0" {{$user->account_reseller ==0 ? 'selected' : ''}}>Account Name: Account Number</option>
                                                        <option value="1" {{$user->account_reseller ==1 ? 'selected' : ''}}>Cold Call</option>
                                                        <option value="2" {{$user->account_reseller ==2 ? 'selected' : ''}}>Employee Referral</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="currency">Currency</label>
                                                    <select class="custom-select form-control" name="currency" id="currency">
                                                        <option value="0" {{$user->currency ==0 ? 'selected' : ''}}>USD</option>
                                                        <option value="1" {{$user->currency ==1 ? 'selected' : ''}}>GBP</option>
                                                        <option value="2" {{$user->currency ==2 ? 'selected' : ''}}>AED</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <label for="currency">Timezone</label>
                                                <div class="form-group timepicker" twelvehour="true">
                                                    <input  type="timezone" class="form-control timedemo" name="timezone" value="{{$user->timezone}}" placeholder="hh:mm am/pm">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="verification_status">Verification Status</label>
                                                    <input type="text" class="form-control" id="verification_status" name="verification_status" value="{{$user->verification_status}}">
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="norminal_code">Norminal Code</label>
                                                    <input type="text" class="form-control" id="norminal_code" name="norminal_code" value="{{$user->norminal_code}}">
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
                                                        <input type="text" class="form-control" id="account_balance" name="account_balance" value="{{$user->account_balance}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-4">
                                                    <div class="form-group">
                                                        <label>Customer Unbilled Ammount</label>
                                                        <input type="text" class="form-control" id="customer_unbilled_ammount" name="customer_unbilled_ammount" value="{{$user->customer_unbilled_ammount}}">
                                                    </div>
                                                </div>
                                                <div class="col-xl-4">
                                                    <div class="form-group">
                                                        <label>Vendor Unbilled Ammount</label>
                                                        <input type="text" class="form-control" id="vendor_unbilled_ammount" name="vendor_unbilled_ammount" value="{{$user->vendor_unbilled_ammount}}">
                                                    </div>
                                                </div>
                                                <div class="col-xl-4">
                                                    {{-- <div class="form-group"> --}}
                                                        {{-- <label> test</label> --}}
                                                        <button type="button" class="btn btn-dark ms-4" style="margin-top: 31px";><i class="fa fa-eye white-color pe-2"></i>View Report</button>
                                                    {{-- </div> --}}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-6">
                                                    <div class="form-group">
                                                        <label>Account Exposure</label>
                                                        <input type="text" class="form-control" id="account_exposure" name="account_exposure" value="{{$user->account_exposure}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-6">
                                                    <div class="form-group">
                                                        <label>Available Credit Limit</label>
                                                        <input type="text" class="form-control" id="available_credit_limit" name="available_credit_limit" value="{{$user->available_credit_limit}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-6">
                                                    <div class="form-group">
                                                        <label>Credit Limit</label>
                                                        <input type="text" class="form-control" id="credit_limit" name="credit_limit" value="{{$user->credit_limit}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-6">
                                                    <div class="form-group">
                                                        <label>Balance Threshold</label>
                                                        <input type="text" class="form-control" id="balance_threshold" name="balance_threshold" value="{{$user->balance_threshold}}">
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
                                                    <input class="form-check-input mt-3 textbox1" type="checkbox" id="switch" name="billing_status" value="active" {{$user->billing_status=='active' ? 'checked' : ''}}>
                                                </label>
                                            </div>
                                            <div class="product {{$user->billing_status == 'inactive' ? 'd-none' : ''}}">
                                                <div class="row">
                                                    <div class="col-xl-6">
                                                        <div class="form-group">
                                                            <label for="billing_class">Billing Class</label>
                                                            <select class="custom-select form-control" name="billing_class" id="billing_class">
                                                                <option value="">Select</option>
                                                                <option value="1" {{$billingdata->billing_class ==1 ? 'selected' : ''}}>Default</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-6">
                                                        <div class="form-group">
                                                            <label for="billing_type">Billing Type</label>
                                                            <select class="custom-select form-control" name="billing_type" id="billing_type">
                                                                <option value="1" {{$billingdata->billing_type ==1 ? 'selected' : ''}}>Prepaid</option>
                                                                <option value="2" {{$billingdata->billing_type ==2 ? 'selected' : ''}}>Postpaid</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xl-6">
                                                        <div class="form-group" twelvehour="true">
                                                            <label for="billing_timezone">Billing Timezone</label>
                                                            <div class="form-group timepicker" twelvehour="true">
                                                                <input  type="text" class="form-control timedemo" name="billing_timezone" value="{{$billingdata->billing_timezone}}" placeholder="hh:mm am/pm">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-6">
                                                        <div class="form-group">
                                                            <label>Billing Start Date</label>
                                                            <input type="date" class="form-control" id="billing_startdate" name="billing_startdate" value="{{$billingdata->billing_startdate}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xl-6">
                                                        <div class="form-group">
                                                            <label for="billing_cycle">Billing Cycle</label>
                                                            <select class="custom-select form-control" name="billing_cycle" id="billing_cycle" >
                                                                <option value="daily" {{$billingdata->billing_cycle=='daily' ? 'selected' : ''}}>Daily</option>
                                                                <option value="weekly"{{$billingdata->billing_cycle=='weekly' ? 'selected' : ''}}>Weekly</option>
                                                                <option value="monthly"{{$billingdata->billing_cycle=='monthly' ? 'selected' : ''}}>Monthly</option>
                                                                <option value="yearly"{{$billingdata->billing_cycle=='yearly' ? 'selected' : ''}}>Yearly</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-6">
                                                        <div class="form-group {{$billingdata->billing_cycle !='weekly' ? 'd-none' : ''}}" id="week">
                                                            <label for="billing_cycle_startday">Billing Cycle Start of Day</label>
                                                            <select class="custom-select form-control" name="billing_cycle_startday_weekly" id="billing_cycle_startday">
                                                                <option value="Sunday"{{$billingdata->billing_cycle_startday=='Sunday' ? 'selected' : ''}}>Sunday</option>
                                                                <option value="Monday" {{$billingdata->billing_cycle_startday=='Monday' ? 'selected' : ''}}>Monday</option>
                                                                <option value="Tuesday"{{$billingdata->billing_cycle_startday=='Tuesday' ? 'selected' : ''}}>Tuesday</option>
                                                                <option value="Wednesday"{{$billingdata->billing_cycle_startday=='Wednesday' ? 'selected' : ''}}>Wednesday</option>
                                                                <option value="Thursday"{{$billingdata->billing_cycle_startday=='Thursday' ? 'selected' : ''}}>Thursday</option>
                                                                <option value="Friday"{{$billingdata->billing_cycle_startday=='Friday' ? 'selected' : ''}}>Friday</option>
                                                                <option value="Saturday"{{$billingdata->billing_cycle_startday=='Saturday' ? 'selected' : ''}}>Saturday</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group {{$billingdata->billing_cycle !='monthly' ? 'd-none' : ''}}" id="month">
                                                            <label for="billing_cycle_startday">Billing Cycle Start of Day</label>
                                                            <select class="custom-select form-control" name="billing_cycle_startday" id="billing_cycle_startday">
                                                                <option value="January"{{$billingdata->billing_cycle_startday=='January' ? 'selected' : ''}}>January</option>
                                                                <option value="February"{{$billingdata->billing_cycle_startday=='February' ? 'selected' : ''}}>February</option>
                                                                <option value="March"{{$billingdata->billing_cycle_startday=='March' ? 'selected' : ''}}>March</option>
                                                                <option value="April"{{$billingdata->billing_cycle_startday=='April' ? 'selected' : ''}}>April</option>
                                                                <option value="May"{{$billingdata->billing_cycle_startday=='May' ? 'selected' : ''}}> May </option>
                                                                <option value="June"{{$billingdata->billing_cycle_startday=='June' ? 'selected' : ''}}> June </option>
                                                                <option value="July"{{$billingdata->billing_cycle_startday=='July' ? 'selected' : ''}}> July </option>
                                                                <option value="August"{{$billingdata->billing_cycle_startday=='August' ? 'selected' : ''}}> August </option>
                                                                <option value="September"{{$billingdata->billing_cycle_startday=='September' ? 'selected' : ''}}> September </option>
                                                                <option value="October"{{$billingdata->billing_cycle_startday=='October' ? 'selected' : ''}}> October </option>
                                                                <option value="November"{{$billingdata->billing_cycle_startday=='November' ? 'selected' : ''}}> November </option>
                                                                <option value="December"{{$billingdata->billing_cycle_startday=='December' ? 'selected' : ''}}> December </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xl-6">
                                                        <div class="form-group">
                                                            <label for="billing_cycle_startdate">Auto Pay</label>
                                                            <select class="custom-select form-control" name="auto_pay" id="auto_pay">
                                                                <option value="0"{{$billingdata->auto_pay==1 ? 'selected' : ''}}>Never</option>
                                                                <option value="1"{{$billingdata->auto_pay==1 ? 'selected' : ''}}>Test1</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-6">
                                                        <div class="form-group">
                                                            <label for="auto_pay_method">Auto Pay Method</label>
                                                            <select class="custom-select form-control" name="auto_pay_method" id="auto_pay_method">
                                                                <option value="">Select</option>
                                                                <option value="0" {{$billingdata->auto_pay_method==1 ? 'selected' : ''}}>Never</option>
                                                                <option value="1" {{$billingdata->auto_pay_method==1 ? 'selected' : ''}}>test1</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="send_invoice_via_email">Send Invoice By Email</label>
                                                    <select class="custom-select form-control" name="send_invoice_via_email" id="send_invoice_via_email">
                                                        <option value="0"{{$billingdata->send_invoice_via_email==0 ? "selected" : ''}}>After Admin Review</option>
                                                        <option value="1"{{$billingdata->send_invoice_via_email==1 ? "selected" : ''}}>Test</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="last_invoice_date">Last Invoice Date</label>
                                                    <input type="date" class="form-control" name="last_invoice_date" value="{{$billingdata->last_invoice_date}}">
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="next_invoice_date">Next Invoice Date</label>
                                                    <input type="date" class="form-control" name="next_invoice_date" value="{{$billingdata->next_invoice_date}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="last_charge_date">Last Charge Date</label>
                                                    <input type="date" class="form-control" name="last_charge_date" value="{{$billingdata->last_charge_date}}">
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="next_charge_date">Next Charge Date</label>
                                                    <input type="date" class="form-control" name="next_charge_date" value="{{$billingdata->next_charge_date}}">
                                                </div>
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
                                                        <select class="custom-select form-control" name="outbound_discount_plan" id="outbound_discount_plan">
                                                            <option value="">Select</option>
                                                            <option value="1"{{$billingdata->outbound_discount_plan==1 ? 'selected' : ''}}>Test</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6">
                                                    <div class="form-group">
                                                        <label for="inbound_discount_plan">Inbound Discount Plan</label>
                                                        <select class="custom-select form-control" name="inbound_discount_plan" id="inbound_discount_plan">
                                                            <option value="">Select</option>
                                                            <option value="1"{{$billingdata->inbound_discount_plan==1 ? 'selected' : ''}}>Test</option>
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
                                    <button type="button"  id="cancel" class="btn btn-danger">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
</div>
{{-- Timepicker --}}

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">

@endsection

@section('page_js')
{{-- Timepicker --}}
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<script type="text/javascript">

$('#switch').change(function() {
        if(this.checked) {
            $(".product").removeClass('d-none');
        }else{
            $(".product").addClass('d-none');       
        }
    });

$(document).ready(function () {
        // Time Picker Initialization
            $('.timedemo').timepicker({
            timeFormat: 'h:mm p',
            interval: 60,
            // minTime: '1',
            // maxTime: '12:00pm',
            startTime: '1:00',
            dynamic: false,
            dropdown: true,
            scrollbar: true
        });
    });
   

function save(formdata,url){
        $('#global-loader').show();
        $.ajax({
          data: formdata,
          url: url,
          type: "PUT",
          dataType: 'json',
        //   cache:false,
        //   contentType: false,
        //   processData: false,
        //   headers: {
        //     'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        //   },
          success: function (resp) {
                $('#global-loader').hide();
                if(resp.success == false){
                    $.each(resp.errors, function(k, e) {
                        $.notify(e, 'error');
                    });
                }
                else{
                    $.notify(resp.message, 'success');
                    $("#Clientform")[0].reset();
                    setTimeout(function(){
                        if(resp.redirect_url){
                            window.location.href = resp.redirect_url;
                        }
                    }, 1000);
                }
             }, error: function(r) {
                $('#global-loader').hide();
                $.each(r.responseJSON.errors, function(k, e) {
                    $.notify(e, 'error');
                });
                $('.blocker').hide();
            }
    });
    }

    $('#submit').click(function (e) {
        //alert();
        e.preventDefault();
        let formdata = $('#Clientform').serialize();
        let url =   $('#Clientform').attr('action');
        save(formdata,url);

    });

    $("#cancel").click(function(){
        location.reload();
    });


    $("#billing_cycle").change(function() {
        // alert($(this).val());
       if($(this).val() == 'daily'){
            $('#week').addClass('d-none');
            $('#month').addClass('d-none');
        }else if($(this).val() == 'weekly'){
            $('#week').removeClass('d-none');
            $('#month').addClass('d-none');
            $('#year').addClass('d-none');
       }else if($(this).val() == 'monthly'){
            $('#week').addClass('d-none');
            $('#month').removeClass('d-none');
            $('#year').addClass('d-none');

       }else if($(this).val() == 'yearly'){
            $('#week').addClass('d-none');
            $('#month').addClass('d-none');

       }
    });    


</script>

@endsection