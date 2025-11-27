@extends('template/frontend/userdashboard/layout/default')
@section('content')

<?php $currency = Session::get('currency_symbol'); $account_No = Session::get('account_No'); ?>

<div id="passport-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Upload Passport Data</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form class="ps-3 pe-3" action="" id="form_passport" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">            
                        <div class="form">
                            <input type="file" id="passport_img" onchange="loadFileactivity(event)" name="passport_img" class="hidden" style="display: none;"/> 
                            
                            <button type="button" class="btn btn-primary"> 
                                <label for="passport_img">Upload ScreenShot Of Passport</label>
                            </button>
                            
                            <button type="submit" id="submit_passport" class="btn btn-primary">Submit</button>
                        </div>
                        
                        <div class="col-md-6">
                            <span class="setcategory2">
                                <img id="imgoutput_other" width="150"/>
                            </span>  
                        </div>
                        
                        <div class="col-md-6" id="passport_loader" style="display:none;">
                            <iframe src="https://embed.lottiefiles.com/animation/98195"></iframe>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h4 class="header-title">Atol Setting</h4>
        @if($errors->any())
            <h4>{{$errors->first()}}</h4>
        @endif
        <form action="{{URL::to('add_registered_atol')}}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf
            
            <div id="progressbarwizard">
                <div class="row">
                    
                    <div class="col-3" style="background-color: #eef2f7;">
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link active" id="v-pills-registered-atol-tab" data-bs-toggle="pill" href="#registered_Atol_tab" role="tab" aria-controls="v-pills-home" aria-selected="true">Register Atol</a>
                            <a class="nav-link" id="v-pills-flight-only-tab" data-bs-toggle="pill" href="#flight_Only_tab" role="tab" aria-controls="v-pills-home" aria-selected="true">Flight Only</a>
                            <a class="nav-link" id="v-pills-package-tab" data-bs-toggle="pill" href="#package_tab" role="tab" aria-controls="v-pills-profile" aria-selected="false">Package</a>
                            <a class="nav-link" id="v-pills-submit-tab" data-bs-toggle="pill" href="#submit_tab" role="tab" aria-controls="v-pills-settings" aria-selected="false">Submit</a>
                        </div>
                    </div>
                    
                    <div class="col-9">
                        <div class="tab-content" id="v-pills-tabContent">
                            
                            <div class="tab-pane fade show active" id="registered_Atol_tab" role="tabpanel" aria-labelledby="v-pills-registered-atol-tab">
                                <h1 class="theme-bg-clr" style="text-align:center">Register Atol</h1>
                                
                                <div class="row">
                                    
                                    <div class="col-xl-6">
                                        <div class="mb-3">
                                            <label class="form-label">Company Name</label>
                                            <input type="text" name="company_Name" id="company_Name" class="form-control" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-6">
                                        <div class="mb-3">
                                            <label class="form-label">Atol Number</label>
                                            <input type="number" name="atol_Number" id="atol_Number" class="form-control" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4">
                                        <div class="mb-3">
                                            <label class="form-label">Country</label>
                                            <select class="form-control" name="atol_country" id="atol_country"  onchange="get_city_atol()" required>
                                                @foreach($all_countries as $country_res)
                                                    <option value='{{ json_encode($country_res) }}'>{{ $country_res->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4">
                                        <div class="mb-3">
                                            <label class="form-label">City</label>
                                            <select class="form-control" name="atol_city" id="atol_city" required></select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4">
                                        <div class="mb-3">
                                            <label class="form-label">Date</label>
                                            <input type="datetime-local" name="atol_date" id="atol_date" class="form-control" required>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            
                            <div class="tab-pane fade" id="flight_Only_tab" role="tabpanel" aria-labelledby="v-pills-flight-only-tab">
                                <h1 class="theme-bg-clr" style="text-align:center">Flight Only</h1>
                                
                                <div class="row">
                                    
                                    <div class="col-xl-4">
                                        <label class="form-label">Select Atol</span></label>
                                        <select class="form-control" id="select_Atol" name="select_Atol"></select>
                                    </div>
                                    
                                    <div class="col-xl-4">
                                        <label class="form-label">Atol Fee</label>
                                        <input type="text" name="atol_Fee" id="atol_Fee" class="form-control" required>
                                    </div>
                                    
                                </div>
                                
                                <div class="row d-none">
                                    
                                    <div class="col-xl-12">
                                        <label class="custom-control-label">Client Type</span></label>
                                        <select class="form-control" id="client_type" name="client_type">
                                           <option value="">Choose Client type</option>
                                           <option value="Existing">Existing</option>
                                           <option value="New_client">New</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-xl-6" id="agent_main_div" style="margin-top:15px;display:none">
                                        <input type="checkbox" class="custom-control-input" id="selectAgent" name="customer_select" value="booking_by_customer">
                                        <label  class="custom-control-label" for="selectAgent">Book for Agent</span></label>
                                        
                                        <div class="mb-3" id="agent_div" style="display:none;padding-top: 10px;">
                                            <select class="form-control" id="agent_Company_Name" name="agent_Company_Name">
                                               <option value="-1">Choose...</option>
                                                @if(isset($Agents_detail) && $Agents_detail !== null && $Agents_detail !== '')
                                                    @foreach($Agents_detail as $Agents_details)
                                                        <option attr-Id="{{ $Agents_details->id }}" attr-AN="{{ $Agents_details->agent_Name }}" value="{{ $Agents_details->company_name }}">{{ $Agents_details->company_name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        
                                            <input type="hidden" name="agent_Id" id="agent_Id" readonly>
                                            
                                            <input type="hidden" name="agent_Name" id="agent_Name" readonly>
                                            
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-6" id="customer_main_div" style="margin-top:15px;display:none">
                                        <input type="checkbox" class="custom-control-input" id="selectcustomer" name="customer_select" value="booking_by_customer">
                                        <label  class="custom-control-label" for="selectcustomer">Book for Customer</span></label>
                                        
                                        <div class="mb-3" id="customer_div" style="display:none;padding-top: 10px;">
                                            <select class="form-control" id="customer_name" name="customer_name">
                                               <option attr-cusData="-1" value="-1">Choose...</option>
                                                @if(isset($customers_data) && $customers_data !== null && $customers_data !== '')
                                                    @foreach($customers_data as $customers_res)
                                                        <option attr-cusData='{{ json_encode($customers_res) }}' attr-Id="{{ $customers_res->id }}" value="{{ $customers_res->id }}">{{ $customers_res->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            
                            <div class="tab-pane fade" id="package_tab" role="tabpanel" aria-labelledby="v-pills-package-tab">
                                <h1 class="theme-bg-clr" style="text-align:center">Package</h1>
                                
                                <div class="row">
                                    
                                    <div class="col-xl-4">
                                        <label class="form-label">Select Atol</span></label>
                                        <select class="form-control" id="select_Atol" name="select_Atol"></select>
                                    </div>
                                    
                                    <div class="col-xl-4">
                                        <label class="form-label">Atol Fee</label>
                                        <input type="text" name="atol_Fee" id="atol_Fee" class="form-control" required>
                                    </div>
                                    
                                </div>
                                
                                <div class="row d-none" id="booked_by_passenger_div" style="display:none">
                                    <div class="col-xl-6">
                                        <div class="mb-3">
                                            <label class="form-label">Passenger Type</label>
                                            <select class="form-control" id="booked_by_passenger" name="booked_by_passenger">
                                                <option value="-1">Select Passenger Type</option>
                                                <option value="Agent">Agent</option>
                                                <option value="Customer">Customer</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row d-none" id="passenger_details_div">
                                    
                                    <div class="col-xl-4">
                                        <div class="mb-3">
                                            <label class="form-label">Title</label>
                                            <input type="text" name="title" id="title" class="form-control" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4">
                                        <div class="mb-3">
                                            <label class="form-label">First Name</label>
                                            <input type="text" name="first_Name" id="first_Name" class="form-control" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4">
                                        <div class="mb-3">
                                            <label class="form-label">Last Name</label>
                                            <input type="text" name="last_Name" id="last_Name" class="form-control" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4">
                                        <div class="mb-3">
                                            <label class="form-label">Gender</label>
                                            <select class="form-control" id="gender" name="gender" required>
                                                <option value="">Select Gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4">
                                        <div class="mb-3">
                                            <label class="form-label">DOB</label>
                                            <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4">
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="text" name="email" id="email" class="form-control" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4">
                                        <div class="mb-3">
                                            <label class="form-label">Phone No</label>
                                            <input type="number" name="phone_No" id="phone_No" class="form-control" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4">
                                        <div class="mb-3">
                                            <label class="form-label">Mobile No</label>
                                            <input type="number" name="mobile_No" id="mobile_No" class="form-control" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4">
                                        <div class="mb-3">
                                            <label class="form-label">Fax</label>
                                            <input type="text" name="fax" id="fax" class="form-control" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4">
                                        <div class="mb-3">
                                            <label class="form-label">Postal Code</label>
                                            <input type="text" name="postal_Code" id="postal_Code" class="form-control" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4">
                                        <div class="mb-3">
                                            <label class="form-label">Address</label>
                                            <input type="text" name="address" id="address" class="form-control" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4">
                                        <div class="mb-3">
                                            <label class="form-label">Opening Balance</label>
                                            <input type="number" name="opening_Balance" id="opening_Balance" class="form-control">
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4" id="agent_name_div">
                                        <div class="mb-3">
                                            <label class="form-label">Agent Name</label>
                                            <input type="text" name="Agent_Name_new" id="Agent_Name_new" class="form-control">
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            
                            <div class="tab-pane fade" id="submit_tab" role="tabpanel" aria-labelledby="v-pills-submit-tab">
                                <h1 class="theme-bg-clr" style="text-align:center">Submit</h1>
                                
                                <div class="row">
                                    <div class="col-lg-12">
                                        <button style="float: right;" type="submit" name="submit" class="btn btn-info deletButton">Submit</button>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            
        </form>
    </div>
</div>

@endsection

@section('scripts')

<script src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initAutocomplete&language=nl&output=json&key=AIzaSyBmwlQFQKwxZ4D8nRbsWVRTBFUHMO-lUOY" async defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs5.min.js" integrity="sha512-6F1RVfnxCprKJmfulcxxym1Dar5FsT/V2jiEUvABiaEiFWoQ8yHvqRM/Slf0qJKiwin6IDQucjXuolCfCKnaJQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBmwlQFQKwxZ4D8nRbsWVRTBFUHMO-lUOY&sensor=false&libraries=places"></script>

<!--LEAD-->
<script>

    $(document).ready(function() {
        get_city_atol();
    });
    
    // Client Type
    $('#client_type').change(function(){
        
        $('#first_Name').val('');
        $('#last_Name').val('');
        $('#email').val('');
        $('#phone_No').val('');
        $('#mobile_No').val('');
        $('#postal_Code').val('');
        $('#address').val('');
        $('#opening_Balance').val('');
        $('#gender').empty();
        var g_d = `<option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>`;
        $('#gender').append(g_d);
        
        $('#refered_by_new_Agent').css('display','none');
        $('#refered_by_new_Customer').css('display','none');
        
        $('#refered_agent_Company_Name').val('-1').change();
        $('#refered_customer_name').val('-1').change();
        $("#refered_agent_main_div").css('display','none');
        $("#refered_customer_main_div").css('display','none');
        
        var client_type = $('#client_type').find('option:selected').attr('value');
        
        if(client_type == 'Existing'){
            $('#agent_main_div').css('display','');
            $('#customer_main_div').css('display','');
            $('#refered_by_existing_div').css('display','');
            $('#refered_by_new_div').css('display','none');
            $('#v-pills-refered-tab').css('display','none');
            $('#refered_Detail_tab').css('display','none');
            $('#booked_by_passenger_div').css('display','none');
            $('#booked_by_passenger').val('-1').change();
            $('#agent_name_div').css('display','none');
            $('#customer_div').css('display','none');
        }else if(client_type == 'New_client'){
            $('#agent_main_div').css('display','none');
            $('#customer_main_div').css('display','none');
            $('#refered_by_existing_div').css('display','none');
            $('#refered_by_new_div').css('display','');
            $('#refered_by_customer_main_div').css('display','none');
            $('#refered_by_agent_main_div').css('display','none');
            $("#selectcustomer").prop('checked', false);
            $('#customer_div').css('display','none');
            $('#agent_div').css('display','block');
            $('#customer_name').val('-1').change();
            $("#selectAgent").prop('checked', false);
            $('#agent_div').css('display','none');
            $('#customer_div').css('display','block');
            $('#agent_Company_Name').val('-1').change();
            $('#v-pills-refered-tab').css('display','');
            $('#refered_Detail_tab').css('display','');
            $('#booked_by_passenger_div').css('display','');
            $('#booked_by_passenger').val('-1').change();
            $('#agent_name_div').css('display','none');
        }else{
            $('#agent_main_div').css('display','none');
            $('#customer_main_div').css('display','none');
            $('#refered_by_existing_div').css('display','none');
            $('#refered_by_new_div').css('display','none');
            $('#refered_by_customer_main_div').css('display','none');
            $('#refered_by_agent_main_div').css('display','none');
            $("#selectcustomer").prop('checked', false);
            $('#customer_div').css('display','none');
            $('#agent_div').css('display','block');
            $('#customer_name').val('-1').change();
            $("#selectAgent").prop('checked', false);
            $('#agent_div').css('display','none');
            $('#customer_div').css('display','block');
            $('#agent_Company_Name').val('-1').change();
            $('#v-pills-refered-tab').css('display','');
            $('#refered_Detail_tab').css('display','');
            $('#booked_by_passenger_div').css('display','none');
            $('#booked_by_passenger').val('-1').change();
            $('#agent_name_div').css('display','none');
        }
    });
    
    $('#booked_by_passenger').change(function(){
        var booked_by_passenger = $('#booked_by_passenger').find('option:selected').attr('value');
        $('#Agent_Name_new').val('');
        if(booked_by_passenger == 'Agent'){
            $('#agent_name_div').css('display','');
        }else if(booked_by_passenger == 'Customer'){
            $('#agent_name_div').css('display','none');
        }else{
            $('#agent_name_div').css('display','none');
        }
    });
    
    $("#selectAgent").click(function() {
        $('#first_Name').val('');
        $('#last_Name').val('');
        $('#email').val('');
        $('#phone_No').val('');
        $('#mobile_No').val('');
        $('#postal_Code').val('');
        $('#address').val('');
        $('#opening_Balance').val('');
        $('#gender').empty();
        var g_d = `<option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>`;
        $('#gender').append(g_d);
        $('#agent_name_div').css('display','');
        
        if($("#selectAgent").is(':checked')){
            $("#selectcustomer").prop('checked', false);
            $('#customer_div').css('display','none');
            $('#agent_div').css('display','block');
            $('#customer_name').val('-1').change();
        }else {
            $('#agent_div').css('display','none');
        }
    });

    $("#selectcustomer").click(function() {
        $('#first_Name').val('');
        $('#last_Name').val('');
        $('#email').val('');
        $('#phone_No').val('');
        $('#mobile_No').val('');
        $('#postal_Code').val('');
        $('#address').val('');
        $('#opening_Balance').val('');
        $('#gender').empty();
        var g_d = `<option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>`;
        $('#gender').append(g_d);
        $('#agent_name_div').css('display','none');
        
        if($("#selectcustomer").is(':checked')){
            $("#selectAgent").prop('checked', false);
            $('#agent_div').css('display','none');
            $('#customer_div').css('display','block');
            $('#agent_Company_Name').val('-1').change();
        }else{
            $('#customer_div').css('display','none');
        }
    });
    // End Client Type
    
    // Passeneger Details
    var Agents_detail = {!!json_encode($Agents_detail)!!};
    
    $('#agent_Company_Name').change(function () {
        var agent_Id    = $(this).find('option:selected').attr('attr-Id');
        var agent_Name  = $(this).find('option:selected').attr('attr-AN');
        $('#agent_Name').val(agent_Name);
        $('#agent_Id').val(agent_Id);
        
        $('#gender').empty();
        var g_d = `<option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>`;
        $('#gender').append(g_d);
        
        $.each(Agents_detail, function(key, value) {
            var AD_id = value.id;
            if(AD_id == agent_Id){
                
                var lead_fname          = value.agent_Name;
                var lead_lname          = value.agent_Name;
                var lead_email          = value.agent_Email;
                var lead_mobile         = value.agent_contact_number;
                var company_name        = value.company_name;
                var agent_Address       = value.agent_Address;
                var agent_Name          = value.agent_Name;
                var opening_balance     = value.opening_balance;
                
                $('#first_Name').val(company_name);
                $('#last_Name').val(company_name);
                $('#email').val(lead_email);
                $('#mobile_No').val(lead_mobile);
                $('#address').val(agent_Address);
                $('#Agent_Name_new').val(agent_Name);
                $('#opening_Balance').val(opening_balance);
            }
        });
    });
    
    $('#customer_name').change(function () {
        var customer_data   = $('#customer_name').find('option:selected').attr('attr-cusData');
        if(customer_data != '-1'){
            var customer_data2          = JSON.parse(customer_data);
            
            var lead_fname              = customer_data2['name'];
            var lead_lname              = customer_data2['name'];
            var lead_email              = customer_data2['email'];
            var lead_mobile             = customer_data2['phone'];
            var lead_whatsapp           = customer_data2['whatsapp'];
            var lead_gender             = customer_data2['gender'];
            var lead_country            = customer_data2['country'];
            var lead_city               = customer_data2['city'];
            var lead_post_code          = customer_data2['post_code'];
            var lead_opening_balance    = customer_data2['opening_balance'];
            
            $('#first_Name').val(lead_fname);
            $('#last_Name').val(lead_lname);
            $('#gender').empty();
            gender_data = `<option value="${lead_gender}">${lead_gender}</option>`;
            $('#gender').append(gender_data);
            $('#email').val(lead_email);
            $('#phone_No').val(parseFloat(lead_mobile));
            $('#mobile_No').val(parseFloat(lead_whatsapp));
            $('#postal_Code').val(lead_post_code);
            $('#opening_Balance').val(parseFloat(lead_opening_balance));
        }
    });
    
    function get_city_atol(){
        var country         = $('#atol_country').find('option:selected').attr('value');
        var country2        = JSON.parse(country);
        var id              = country2['id'];
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ url('/country_cites1') }}",
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                "id": id,
            },
            success: function(result){
                var options = result.options;
                $('#atol_city').html(options);
            },
            error:function(error){
            }
        });
    }
    // End Passeneger Details
    
    // Passport Details
    $('#passport_type').change(function () {
        
        $('#first_Name_passport').val('');
        $('#last_Name_passport').val('');
        $('#nationality_passport').val('');
        $('#date_of_birth_passport').val('');
        $('#passport_Number').val('');
        $('#passport_Expiry').val('');
        
        var passport_type = $('#passport_type').find('option:selected').attr('value');
        if(passport_type == 'Upload'){
            $('#passport_div_wrriten').css('display','none');
            $('#passport_div_Upload').css('display','');
        }else if(passport_type == 'Written'){
            $('#passport_div_wrriten').css('display','');
            $('#passport_div_Upload').css('display','none');
        }else{
            $('#passport_div_wrriten').css('display','none');
            $('#passport_div_Upload').css('display','none');
        }
    });
    
    var loadFileactivity    = function(event) {
        var image           = document.getElementById('imgoutput_other');
        image.src           = URL.createObjectURL(event.target.files[0]);
        var mime_types      = [ 'image/jpeg', 'image/png' ];
        var submit_button   = document.querySelector('#submit_passport');
        console.log(submit_button);
        submit_button.addEventListener('click', function() {
            $("#form_passport").on('submit',(function(e) {
                $('#passport_loader').css('display','');
                e.preventDefault();
                $.ajax({
                    url         : "{{URL::to('Upload_lead_passport')}}",
                    type        : "POST",
                    data        :  new FormData(this),
                    contentType : false,
                    cache       : false,
                    processData : false,
                    beforeSend  : function(){
                        $("#err").fadeOut();
                    },
                    success: function(data){
                        console.log(data);
                        var data    = 'urls={{URL::to('')}}/public/images/passportimg/'+data;
                        var xhr     = new XMLHttpRequest();
                        xhr.addEventListener("readystatechange", function () {
                            if (this.readyState === this.DONE) {
                                
                                $('#passport_div_wrriten').css('display','');
                                
                                var result = this.responseText;
                                if(result){
                                    $('#passport_loader').css('display','none');
                                    result          = JSON.parse(result);
                                    var result      = result['result'][0];
                                    var prediction  = result['prediction'];
                                    for (var i = 0; i < prediction.length; i++) {
                                        if(prediction[i]['label'] == 'First_Name'){
                                            $('#first_Name_passport').val(prediction[i]['ocr_text']);
                                        }
                                        if(prediction[i]['label'] == 'Surname'){
                                            $('#last_Name_passport').val(prediction[i]['ocr_text']);
                                        }  
                                        if(prediction[i]['label'] == 'Nationality'){
                                            $('#nationality_passport').val(prediction[i]['ocr_text']);
                                        }
                                        if(prediction[i]['label'] == 'Date_of_Birth'){
                                            $('#date_of_birth_passport').val(prediction[i]['ocr_text']);
                                        }
                                        if(prediction[i]['label'] == 'Passport_Number'){
                                            $('#passport_Number').val(prediction[i]['ocr_text']);
                                        }
                                        if(prediction[i]['label'] == 'Date_of_expiry'){
                                            $('#passport_Expiry').val(prediction[i]['ocr_text']);
                                        }
                                        if(prediction[i]['label'] == 'Sex'){}
                                        if(prediction[i]['label'] == 'Place_of_birth'){}
                                        if(prediction[i]['label'] == 'Date_of_Issue'){}
                                        if(prediction[i]['label'] == 'Code'){}
                                        if(prediction[i]['label'] == 'MRZ'){};
                                    }
                                }
                            }
                        });
            
                        xhr.open("POST", "https://app.nanonets.com/api/v2/OCR/Model/8f515866-897e-4291-b1ac-c3e9096f6c8b/LabelUrls/?async=false");
                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        xhr.setRequestHeader("authorization", "Basic " + btoa("qrpOJl8dy39IJLmjGn322Otm7U7rJvJU:"));
                        xhr.send(data);
                    },
                    error: function(e){
                        $('.passport_form').append('Sorry error occur while reading!');
                    }
                });
            }));
        });
    };
    // End Passport Details
    
    // Preference Details
    $('#preference_type').change(function () {
        $('#preference_location').val('');
        $('#more_lead_location_div').empty();
        $('#invoice_type').val('-1').change();
        $('#package_type').val('-1').change();
        $('#quotation_type').val('-1').change();
        
        var preference_type = $('#preference_type').find('option:selected').attr('value');
        if(preference_type == 'Location'){
            $('#preference_type_Location').css('display','');
            $('#preference_type_Invoice').css('display','none');
            $('#preference_type_Package').css('display','none');
            $('#preference_type_Quotation').css('display','none');
        }else if(preference_type == 'Invoice'){
            $('#preference_type_Location').css('display','none');
            $('#preference_type_Invoice').css('display','');
            $('#preference_type_Package').css('display','none');
            $('#preference_type_Quotation').css('display','none');
        }else if(preference_type == 'Package'){
            $('#preference_type_Location').css('display','none');
            $('#preference_type_Invoice').css('display','none');
            $('#preference_type_Package').css('display','');
            $('#preference_type_Quotation').css('display','none');
        }else if(preference_type == 'Quotation'){
            $('#preference_type_Location').css('display','none');
            $('#preference_type_Invoice').css('display','none');
            $('#preference_type_Package').css('display','none');
            $('#preference_type_Quotation').css('display','');
        }else{
            $('#preference_type_Location').css('display','none');
            $('#preference_type_Invoice').css('display','none');
            $('#preference_type_Package').css('display','none');
            $('#preference_type_Quotation').css('display','none');
        }
    });
    
    var more_location = 1;
    function add_more_lead_location(){
        
        var data = `<div id="more_lead_location_div_${more_location}" class="row" style="margin-top: 20px;">
                        <div class="col-xl-6">
                            <div class="mb-3">
                                <label class="form-label">More Location</label>
                                <input type="text" name="more_location[]" id="more_location_${more_location}" class="form-control">
                            </div>
                        </div>
                        
                        <div class="col-xl-6" style="margin-top: 27px;">
                            <div class="mb-3">
                                <a href="javascript:;" onclick="remove_more_lead_location(${more_location})" class="btn btn-info">Remove</a>
                            </div>
                        </div>
                    </div>
                    `;
        $('#more_lead_location_div').append(data);
        add_preference_location('more_location_'+more_location+'');
        more_location = parseFloat(more_location) + 1;
    }
    
    function remove_more_lead_location(id){
        $('#more_lead_location_div_'+id+'').remove();
    }
    
    let places,input, address, city;
    google.maps.event.addDomListener(window, "load", function () {
        var places = new google.maps.places.Autocomplete(
            document.getElementById("preference_location")
        );
        
        google.maps.event.addListener(places, "place_changed", function () {
            var place = places.getPlace();
            var address = place.formatted_address;
            var latitude = place.geometry.location.lat();
            var longitude = place.geometry.location.lng();
            var latlng = new google.maps.LatLng(latitude, longitude);
            var geocoder = (geocoder = new google.maps.Geocoder());
            geocoder.geocode({ latLng: latlng }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        var address = results[0].formatted_address;
                        var pin =
                        results[0].address_components[
                    results[0].address_components.length - 1
                  ].long_name;
                        var country =
                          results[0].address_components[
                            results[0].address_components.length - 2
                          ].long_name;
                        var state =
                          results[0].address_components[
                            results[0].address_components.length - 3
                          ].long_name;
                        var city =
                          results[0].address_components[
                            results[0].address_components.length - 4
                          ].long_name;
                        var country_code =
                          results[0].address_components[
                            results[0].address_components.length - 2
                          ].short_name;
                        $('#country').val(country);
                        $('#lat').val(latitude);
                        $('#long').val(longitude);
                        $('#pin').val(pin);
                        $('#city').val(city);
                        $('#country_code').val(country_code);
                    }
                }
            });
        });
    });
    
    function add_preference_location(id){
        var places = new google.maps.places.Autocomplete(
            document.getElementById(id)
        );
        
        google.maps.event.addListener(places, "place_changed", function () {
            var place = places.getPlace();
            var address = place.formatted_address;
            var latitude = place.geometry.location.lat();
            var longitude = place.geometry.location.lng();
            var latlng = new google.maps.LatLng(latitude, longitude);
            var geocoder = (geocoder = new google.maps.Geocoder());
            geocoder.geocode({ latLng: latlng }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        var address = results[0].formatted_address;
                        var pin =
                        results[0].address_components[
                    results[0].address_components.length - 1
                  ].long_name;
                        var country =
                          results[0].address_components[
                            results[0].address_components.length - 2
                          ].long_name;
                        var state =
                          results[0].address_components[
                            results[0].address_components.length - 3
                          ].long_name;
                        var city =
                          results[0].address_components[
                            results[0].address_components.length - 4
                          ].long_name;
                        var country_code =
                          results[0].address_components[
                            results[0].address_components.length - 2
                          ].short_name;
                        $('#country').val(country);
                        $('#lat').val(latitude);
                        $('#long').val(longitude);
                        $('#pin').val(pin);
                        $('#city').val(city);
                        $('#country_code').val(country_code);
                        $('#pickup_CountryCode').val(country_code)
                    }
                }
            });
        });
    }
    // End Preference Details
    
    // Refered By
    $("#refered_by_new").on('change',function() {
        $('#refered_agent_Company_Name').val('-1').change();
        $('#refered_customer_name').val('-1').change();
        
        var refered_by_new = $('#refered_by_new').find('option:selected').attr('value');
        if(refered_by_new == 'Agent'){
            $("#refered_agent_main_div").css('display','');
            $("#refered_customer_main_div").css('display','none');
        }else if(refered_by_new == 'Customer'){
            $("#refered_agent_main_div").css('display','none');
            $("#refered_customer_main_div").css('display','');
        }else{
            $("#refered_agent_main_div").css('display','none');
            $("#refered_customer_main_div").css('display','none');
        }
    });
    
    function get_customer_city(){
        var country         = $('#customer_country_new').find('option:selected').attr('value');
        var country2        = JSON.parse(country);
        var id              = country2['id'];
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ url('/country_cites1') }}",
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                "id": id,
            },
            success: function(result){
                var options = result.options;
                $('#customer_city_new').html(options);
            },
            error:function(error){
            }
        });
    }
    // End Refered By
    
    // Emergency Number
    var more_address = 1;
    function add_more_lead_address(){
        
        var data = `<div id="more_lead_address_div_${more_address}" class="row" style="margin-top: 20px;">
            
                        <div class="col-xl-3">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="more_name[]" id="more_name_${more_address}" class="form-control">
                            </div>
                        </div>
                        
                        <div class="col-xl-3">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="text" name="more_email[]" id="more_email_${more_address}" class="form-control">
                            </div>
                        </div>
                        
                        <div class="col-xl-3">
                            <div class="mb-3">
                                <label class="form-label">Contact</label>
                                <input type="text" name="more_contact[]" id="more_contact_${more_address}" class="form-control">
                            </div>
                        </div>
                        
                        <div class="col-xl-3">
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <input type="text" name="more_address[]" id="more_address_${more_address}" class="form-control">
                            </div>
                        </div>
                        
                        <div class="col-xl-12">
                            <div class="mb-3" style="float: right;">
                                <a href="javascript:;" onclick="remove_more_lead_address(${more_address})" class="btn btn-info"> Remove</a>
                            </div>
                        </div>
                    </div>
                    `;
        $('#more_lead_address_div').append(data);
        more_address = parseFloat(more_address) + 1;
    }
    
    function remove_more_lead_address(id){
        $('#more_lead_address_div_'+id+'').remove();
    }
    // End Emergency Number
    
</script>
<!--END LEAD-->
  
@stop