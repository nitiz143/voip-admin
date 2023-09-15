@extends('layouts.dashboard')

@section('content')

<style>
    .form-check {
        padding-left: 0.25rem !important;
    }
    #myTab{
        margin-bottom: -12px;
        border-bottom : 0px solid #fff;
        }
</style>

<div class="content-wrapper mt-3">
    <section class="content-header">
        <div class="container">
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
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <form action="{{ route('Convert_to_Client',$crm->id) }}" method="POST" id="Clientform">
                            @csrf
                            <div class="card-header">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Account Details
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="address-tab" data-toggle="tab" href="#address" role="tab" aria-controls="address" aria-selected="false">Address Information
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Billing
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                     {{-- <div class="card-header">
                                        <h3 class="card-title">New Account</h3>
                                    </div> 
                        --}}
                                    <div class="card-body"> 
                                        <div class="card-header">
                                            <h3 class="card-title">Account Details</h3>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label>Account Owner</label>
                                                    <select class="custom-select form-control-border border-width-2"
                                                        name="lead_owner" id="lead_owner">
                                                        <optgroup label="Select option">
                                                            <option value="{{$crm->lead_owner}}">{{$lead_owner->role}}</option>
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="ownership">Ownership</label>
                                                    <select class="custom-select form-control" name="ownership"
                                                        id="ownership">
                                                        <option value="">None</option>
                                                        <option value="Private">Private</option>
                                                        <option value="Public" >Public</option>
                                                        <option value="Subsidiary">Subsidiary</option>
                                                        <option value="Other" >Other
                                                        </option>

                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-4">
                                                <div class="form-group">
                                                    <label for="company">Company</label>
                                                    <input type="text" class="form-control" id="company" name="company" value="{{$crm->company}}">
                                                </div>
                                            </div>
                                            <div class="col-xl-4">
                                                <div class="form-group">
                                                    <label for="mobile">Mobile</label>
                                                    <input type="text" class="form-control number_only" id="mobile" name="mobile" value="{{$crm->mobile}}">
                                                </div>
                                            </div>
                                            <div class="col-xl-4">
                                                <div class="form-group">
                                                    <label for="skype_id">Skype ID</label>
                                                    <input type="text" class="form-control" id="skype_id" name="skype_id"
                                                        value="{{$crm->skype_id}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="firstname">First Name</label>
                                                    <input type="text" class="form-control" id="firstname" name="firstname"
                                                        value="{{$crm->firstname}}">
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="lastname">Last Name</label>
                                                    <input type="text" class="form-control" id="lastname" name="lastname"
                                                        value="{{$crm->lastname}}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="account_number">Account Number</label>
                                                    <input type="text" class="form-control" id="account_number"
                                                        name="account_number" value="">
                                                </div>
                                            </div>

                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="website">Website</label>
                                                    <input type="text" class="form-control" id="website" name="website"
                                                        value="{{$crm->website}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="account_name">Account Name<span style="color:red;">*</span></label>
                                                    <input type="text" class="form-control" id="account_name"
                                                        name="account_name" value="">
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="phone">Phone</label>
                                                    <input type="text" class="form-control" id="phone" name="phone"
                                                        value="{{$crm->phone}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-check form-switch">
                                                    <label for="Vendor">Vendor</label>
                                                    <input class="form-check-input mt-5" type="checkbox" id="Vendor"
                                                        name="Vendor" value="1" >
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="fax">Fax</label>
                                                    <input type="text" class="form-control" id="fax" name="fax"
                                                        value="{{$crm->fax}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-check form-switch">
                                                    <label for="customer">Customer</label>
                                                    <input class="form-check-input mt-5" style="margin-left: -3.5em;"
                                                        type="checkbox" id="customer" name="customer" value="1" >
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="employee">Employee</label>
                                                    <input type="text" class="form-control" id="employee" name="employee" value="{{$crm->employee}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-check form-switch">
                                                    <label for="reseller">Reseller</label>
                                                    <input class="form-check-input mt-5" style="margin-left: -3em;"
                                                        type="checkbox" name="reseller" value="1" >
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
                                                    <label for="email">Email</label>
                                                    <input type="text" class="form-control" id="email" name="email"
                                                        value="{{$crm->email}}">
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="billingemail">Billing Email</label>
                                                    <input type="email" class="form-control" id="billing_email"
                                                        name="billing_email" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-check form-switch">
                                                    <label for="reseller">Active</label>
                                                    <input class="form-check-input mt-5" style="margin-left: -3em;"
                                                        type="checkbox" name="status" value="0" >
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="vat_number">VAT Number</label>
                                                    <input type="text" class="form-control" id="vat_number" name="vat_number"
                                                        value="{{$crm->vat_number}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="currency">Currency</label>
                                                    <select class="custom-select form-control" name="currency"
                                                        id="currency">
                                                        <option value="0">USD
                                                        </option>
                                                        <option value="1">GBP
                                                        </option>
                                                        <option value="2">AED
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <label for="currency">Timezone</label>
                                                <div class="form-group timepicker" twelvehour="true">
                                                    <select class="form-control selectpicker" id="timezone"
                                                        name="timezone"></select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="verification_status">Verification Status</label>
                                                        <select class="form-control" name="verification_status" disabled>
                                                            <option value="1">Not verified</option>
                                                            <option value="0">Verified</option>
                                                        </select>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="norminal_code">Norminal Code</label>
                                                    <input type="text" class="form-control" id="norminal_code"
                                                        name="norminal_code" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="language">Language</label>
                                                    <select id="language" class="form-control" name="language">
                                                        <option value="english" >English</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="form-group">
                                                    <label for="description">Description</label>
                                                    <textarea class="form-control" id="description" name="description" value="{{$crm->description}}">
                                                    </textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- address information section --}}
                                <div class="tab-pane fade show active card-body" id="address" role="tabpanel" aria-labelledby="address-tab">
                                    <div class="card-header">
                                        <h3 class="card-title">Address Information</h3>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label for="address_line1">Address Line 1</label>
                                                <input type="text" class="form-control" id="address_line1"
                                                    name="address_line1" value="{{$crm->address_line1}}">
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label for="city">City</label>
                                                <input type="text" class="form-control" id="city" name="city"
                                                    value="{{$crm->city}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label for="address_line2">Address Line 2</label>
                                                <input type="text" class="form-control" id="address_line2"
                                                    name="address_line2" value="{{$crm->address_line2}}">
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label for="postzip">Post/Zip Code</label>
                                                <input type="text" class="form-control" id="postzip" name="postzip"
                                                    value="{{$crm->postzip}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label for="address_line3">Address Line 3</label>
                                                <input type="text" class="form-control" id="address_line3"
                                                    name="address_line3" value="{{$crm->address_line3}}">
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="form-group">
                                                <label for="country">Country</label>
                                                <select class="custom-select form-control-border border-width-2"
                                                    name="country" id="country">
                                                    <option >--Select Country--</option>
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
                                                    <option value="Finland">Finland</option>    $rules['Country'] = 'required';
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
                                                    <option value="Qatar">Qatar</option    $rules['Country'] = 'required';>
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
                                                    <optgroup label="Selected Country">
                                                        <option {{$crm->country ? 'selected' :''}}
                                                            value="{{$crm->country}}" >{{$crm->country}}</option>
                                                    </optgroup>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab"> 
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <h3 class="card-title">Billing</h3>
                                        </div>
                                        <div class="col-xl-6">
                                            <label class="float-end form-check form-switch">
                                                <input class="form-check-input mt-3 textbox1" type="checkbox"
                                                    id="switch" name="billing_status" value="active">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="product d-none">
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="billing_class">Billing Class<span style="color:red;">*</span></label>
                                                    <select class="custom-select form-control" name="billing_class"
                                                        id="billing_class">
                                                        <option value="">Select</option>
                                                        <option value="1" >Default</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="billing_type">Billing Type<span style="color:red;">*</span></label>
                                                    <select class="custom-select form-control" name="billing_type"
                                                        id="billing_type">
                                                        <option value="">Select Billing Type</option>
                                                        <option value="1">Prepaid</option>
                                                        <option value="2">Postpaid</option>
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
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label>Billing Start Date<span style="color:red;">*</span></label>
                                                    <input type="text" class="form-control datepicker billing_start_date"  data-date-format="yyyy-mm-dd" id="billing_startdate"
                                                        name="billing_startdate" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="billing_cycle">Billing Cycle<span style="color:red;">*</span></label>
                                                    <select class="custom-select form-control" name="billing_cycle"
                                                        id="billing_cycle">
                                                        <option value="">Select</option>
                                                        <option value="daily">Daily</option>
                                                        <option value="fortnightly">Fortnightly</option>
                                                        <option value="in_specific_days">In Specific days</option>
                                                        <option value="manual">Manual</option>
                                                        <option value="monthly">Monthly</option>
                                                        <option value="monthly_anniversary">Monthly anniversary</option>
                                                        <option value="quarterly">Quarterly</option>
                                                        <option value="weekly">Weekly</option>
                                                        <option value="yearly">Yearly</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group d-none" id="in_specific_days">
                                                    <label for="billing_cycle_startday">Billing Cycle - for
                                                        Days<span style="color:red;">*</span></label>
                                                    <input type="text" name="billing_cycle_startday_for_days"
                                                        class="form-control billing_cycle_startday" id="number_only"
                                                        value="">
                                                </div>
                                                <div class="form-group d-none" id="monthly_anniversary">
                                                    <label for="billing_cycle_startday">Billing Cycle - Monthly
                                                        Anniversary Date</label>
                                                    <input type="text" name="billing_cycle_startday_for_monthly"
                                                        class="form-control billing_cycle_startday next_invoice_date datepicker" value="">
                                                </div>
                                                <div class="form-group d-none" id="week">
                                                    <label for="billing_cycle_startday">Billing Cycle Start of
                                                        Day<span style="color:red;">*</span></label>
                                                    <select
                                                        class="custom-select form-control billing_cycle_startday"
                                                        name="billing_cycle_startday" id="billing_cycle_startday">
                                                        <option data-id="0" value="Sunday">Sunday</option>
                                                        <option data-id="1" value="Monday">Monday</option>
                                                        <option data-id="2" value="Tuesday">Tuesday</option>
                                                        <option data-id="3" value="Wednesday">Wednesday</option>
                                                        <option data-id="4" value="Thursday">Thursday</option>
                                                        <option data-id="5" value="Friday">Friday</option>
                                                        <option data-id="6" value="Saturday">Saturday</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="next_invoice_date">Next Invoice Date</label>
                                                    <input type="text" class="form-control next_invoice_date datepicker billing_start_date" name="next_invoice_date" id="next_invoice_date"
                                                        value="">
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="next_charge_date">Next Charge Date</label>
                                                    <input type="text" class="form-control" name="next_charge_date" id="next_charge_date"
                                                        value="" readonly>
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
                                                        <option value="1">On Invoice Date</option>
                                                        <option value="2">On Due Date</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xl-6">
                                                <div class="form-group">
                                                    <label for="auto_pay_method">Auto Pay Method</label>
                                                    <select class="custom-select form-control"
                                                        name="auto_pay_method" id="auto_pay_method">
                                                        <option value="">Select</option>
                                                        <option value="1">Account Balance</option>
                                                        <option value="2">Preferred Method</option>
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
                                                        <option value="1">Automatically</option>
                                                        <option value="2">After Admin Review</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         {{-- </div>  --}}

                    
                            <div class="card-footer">
                                <button type="submit" id="submit" class="btn btn-primary">Submit</button>
                                <button type="button" id="cancel" class="btn btn-danger">Cancel</button>
                            </div>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
@section('page_js')
<script src="{{asset('js/timezones.full.js')}}"></script>
<script src="{{asset('js/account_custom.js')}}"></script>


<script>
    $('#submit').click(function (e) {
        e.preventDefault();
        let formdata = $('#Clientform').serialize();
        let url =   $('#Clientform').attr('action');
        let method =   $('#Clientform').attr('method');
        save(formdata,url,method);

    });

    $(function () {
        $(".datepicker").datepicker({ 
            autoclose: true, 
            todayHighlight: true
        });
    });
</script>
@endsection