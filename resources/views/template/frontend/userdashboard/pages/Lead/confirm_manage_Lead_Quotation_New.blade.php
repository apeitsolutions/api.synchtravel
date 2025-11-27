@extends('template/frontend/userdashboard/layout/default')
@section('content')

<?php $currency=Session::get('currency_symbol'); $account_No=Session::get('account_No'); ?>

<div id="signup-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
<div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Add Visa Type</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <!--<div class="text-center mt-2 mb-4">-->
                <!--    <a href="index.html" class="text-success">-->
                <!--        <span><img src="assets/images/logo-dark.png" alt="" height="18"></span>-->
                <!--    </a>-->
                <!--</div>-->

                <form class="ps-3 pe-3" action="#">

                    <div class="mb-3">
                        <label for="username" class="form-label">Visa </label>
                        <input class="form-control" type="email" id="other_visa_type" name="other_visa_type" placeholder="">
                    </div>

                   

                  

                    

                    <div class="mb-3 text-center">
                        <button class="btn btn-primary" id="submitForm" type="submit">Submit</button>
                    </div>

                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<div id="add-Categories-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Add Categories</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="https://client.synchronousdigital.com/super_admin/submit_categories" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="label" class="form-label">Title</label>
                        <input class="form-control" type="text" id="title" name="title" placeholder="">
                    </div>
                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug</label>
                        <input class="form-control" type="text" id="slug" name="slug" placeholder="">
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input class="form-control" type="file" id="" name="image" placeholder="">
                    </div>
                    <div class="mb-3">
                        <label for="placement" class="form-label">Placement</label>
                        <input class="form-control" type="text" id="placement" name="placement" placeholder="">
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" type="text" id="" name="description" placeholder=""></textarea>
                    </div>
                    

                    <div class="mb-3 text-center">
                        <button class="btn btn-primary" id="" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<div id="add-flight-seats-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Add Seats</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="#" id="flightSeatsForm">
                    @csrf
                    <div class="row">
                        <div class="col-xl-12">
                            <a type="button" class="btn btn-primary" id="manual_flight_button_add" onclick="manual_flight_button_add()">Add Seats</a>
                        
                            <a type="button" class="btn btn-danger" id="manual_flight_button_remove" onclick="manual_flight_button_remove()" style="display:none">Remove</a>
                            
                            <div class="dashboard-content manual_flight_div" style="display:none"></div>
                        </div>
                        
                        <div class="col-xl-12">
                            <button class="btn btn-primary" id="submit_seats_button" onclick="submit_seats()" type="button" style="display:none;float: right;">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="add-transfer-destination-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Add Destinations</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="#" id="transferDestinationForm">
                    @csrf
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="dashboard-content" style="display:none" id="transfer_modal_div">
                                
                                <div class="row">
                            
                                    <input type="hidden" id="pickup_CountryCode" name="pickup_CountryCode">
                                    
                                    <div class="col-xl-6" style="padding-left: 15px;padding-top: 7px;">
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Currency Conversion</label>
                                            <select class="form-control CC_id_store_1" name="currency_conversion" id="currency_conversion1_modal">
                                                <option value="-1">Select Currency Conversion</option>
                                                @foreach($manage_currency as $manage_currency1)
                                                    <option attr_id="{{$manage_currency1->id}}" attr_conversion_type="{{$manage_currency1->conversion_type}}" value="{{$manage_currency1->purchase_currency}} TO {{$manage_currency1->sale_currency}}">{{$manage_currency1->purchase_currency}} TO  {{$manage_currency1->sale_currency}}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" id="conversion_type_Id"  name="conversion_type_Id">
                                            <input type="hidden" id="select_exchange_type" name="select_exchange_type"> 
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4 d-none" style="padding: 15px;">
                                        <label for="">Select Transfer Company</label>
                                        <select id="transfer_company" name="transfer_company" class="form-control transfer_company">
                                            <option value="-1">Select Company</option>
                                            @if(isset($tranfer_company) && $tranfer_company != null && $tranfer_company != '')
                                                @foreach($tranfer_company as $tranfer_companyS)
                                                    <option value="{{ $tranfer_companyS->room_supplier_name }}">{{ $tranfer_companyS->room_supplier_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    
                                    <div class="col-xl-6" style="padding: 15px;">
                                        <label>Select Trip Type</label>
                                        <select name="transfer_type" id="transfer_type" class="form-control" onchange="transferTypefunction()">
                                            <option value="-1">Select Transfer Type</option>
                                            <option value="One-Way">One-Way</option>
                                            <option value="Return">Return</option>
                                            <option value="All_Round">All_Round</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-3" style="padding: 15px;">
                                        <label for="">Available From</label>
                                        <input type="date" id="available_from" name="available_from" class="form-control available_from" required>
                                    </div>
                                    
                                    <div class="col-md-3" style="padding: 15px;">
                                        <label for="">Available To</label>
                                        <input type="date" id="available_to" name="available_to" class="form-control available_to" required>
                                    </div>
                                    
                                    <div class="col-md-3" style="padding: 15px;">
                                        <label for="">Select Pickup City</label>
                                        <input type="text" id="pickup_City" name="pickup_City" class="form-control pickup_City" required>
                                    </div>
                                    
                                    <div class="col-md-3" style="padding: 15px;">
                                        <label for="">Select Dropof City</label>
                                        <input type="text" id="dropof_City" name="dropof_City" class="form-control dropof_City" required>
                                    </div>
                                    
                                    <div class="row" id="returnDestinations" style="display:none">
                                        <h4>Return Destination Details</h4>
                                        
                                        <div class="col-md-6" style="padding: 15px;">
                                            <label for="">Select Return Pickup City</label>
                                            <input type="text" id="return_pickup_City" name="return_pickup_City" class="form-control return_pickup_City">
                                        </div>
                                        
                                        <div class="col-md-6" style="padding: 15px;">
                                            <label for="">Select Return Dropof City</label>
                                            <input type="text" id="return_dropof_City" name="return_dropof_City" class="form-control return_dropof_City">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12" id="subDestinations"></div>
                                    
                                    <div class="col-md-12" style="padding: 15px;">
                                        <button type="button" style="display:none;float: right;" id="subDestinations_button" onclick="addMoreSubDestination()" class="btn btn-info"> + Add More Destinations </button>
                                    </div>
                                    
                                    <div id="append_Ziyarat"></div>
                                    
                                    <div class="mt-2">
                                        <a href="javascript:;" onclick="add_ziyarat()" class="btn btn-info" style="float: right;"> + Add Ziyarat </a>
                                    </div>
                                    
                                    <div class="col-md-3" style="padding: 15px;">
                                        <label for="">Transfer Suppliers</label>
                                        <select name="transfer_supplier[]" class="form-control" id="transfer_supplier_modal">
                                            <option value="-1">Select Suppliers</option>
                                            @foreach($tranfer_supplier as $tranfer_supplierS)
                                                <option attr-id="{{ $tranfer_supplierS->id }}" value="{{ $tranfer_supplierS->room_supplier_name }}">{{ $tranfer_supplierS->room_supplier_name }}</option>
                                            @endforeach
                                        </select> 
                                        <input type="hidden" value="" id="vehicle_currency_symbol" name="currency_symbol[]">
                                        <input type="hidden" id="transfer_supplier_Id" name="transfer_supplier_Id[]">
                                    </div>
                                    
                                    <div class="col-md-3" style="padding: 15px;">
                                        <label for="">Select Category Vehicle</label>
                                        <select name="vehicle_Name[]" class="form-control" id="vehicle_Data">
                                            <option value="-1">Select Vehicle</option>
                                            @foreach($tranfer_vehicle as $value)
                                                <option value='{{ json_encode($value) }}' attr="{{ $value->currency_symbol }}">{{ $value->vehicle_Name }}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" value="" id="vehicle_currency_symbol" name="currency_symbol[]">
                                    </div>
                                    
                                    <div class="col-md-2" style="padding: 15px;">
                                        <label for="">Fare</label>
                                        <div class="input-group">
                                            <input type="text" id="vehicle_Fare" name="vehicle_Fare[]" class="form-control" required>
                                            <span class="input-group-btn input-group-append">
                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value1_modal"></a>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-2" style="padding: 15px;">
                                        <label for="">Exchage Rate</label>
                                        <div class="input-group">
                                            <input type="text" id="exchange_Rate" name="exchange_Rate[]" class="form-control" required>
                                            <span class="input-group-btn input-group-append">
                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value1_modal"></a>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-2" style="padding: 15px;">
                                        <label for="">Total Fare</label>
                                        <div class="input-group">
                                            <input type="text" id="vehicle_total_Fare" name="vehicle_total_Fare[]" class="form-control" required>
                                            <span class="input-group-btn input-group-append">
                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1_modal"></a>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12" style="padding: 15px;">
                                        <input class="form-check-input display_on_website" counter="0" type="checkbox" name="display_on_website[]" value="true" id="display_on_website0">
                                            <label class="form-check-label" for="display_on_website0">
                                                Display on Website
                                            </label>
                                            <div class="row" id="display_markup0" style="display:none;">
                                                <div class="col-md-2" style="padding: 15px;">
                                                    <label for="">Markup Type</label>
                                                    <select name="fare_markup_type[]" id="fare_markup_type" class="form-control">
                                                        <option value="-1">Markup Type</option>
                                                        <option value="%">Percentage</option>
                                                        <option value="<?php echo $currency; ?>">Fixed Amount</option>
                                                    </select>
                                                </div>
                                                
                                                <div class="col-md-2" style="padding: 15px;">
                                                    <label for="">Markup</label>
                                                    <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                        <input type="text" class="form-control" id="fare_markup" name="fare_markup[]">
                                                        <span class="input-group-btn input-group-append">
                                                            <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="fare_mrk"></div></button>
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-2" style="padding: 15px;">
                                                    <label for="">Total</label>
                                                    <div class="input-group">
                                                        <input type="text" id="total_fare_markup" name="total_fare_markup[]" class="form-control">
                                                        <span class="input-group-btn input-group-append">
                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1_modal"></a>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                    
                                    <div id="append_Vehicle"></div>
                                    
                                    <div class="mt-2">
                                        <a href="javascript:;" onclick="add_more_vehicle()" class="btn btn-info" style="float: right;"> + Add More </a>
                                    </div>
                                    
                                </div>
                                
                            </div>
                        </div>
                        
                        <div class="col-xl-12">
                            <button class="btn btn-primary" id="submit_destination_button" onclick="submit_destination()" type="button" style="display:none;float: right;">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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
                            
                            <button type="submit" id="submit_passport" class="btn btn-primary">Scan to fill details</button>
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
        <h4 class="header-title">Edit Quotation</h4>
        
        @if($errors->any())
            <h4>{{$errors->first()}}</h4>
        @endif
        
        <form action="{{URL::to('confirm_Lead_Quotation_New')}}/{{$get_invoice->id}}/{{ $lead_id }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf
            <div id="progressbarwizard">
                <ul class="nav nav-pills nav-justified form-wizard-header mb-3" style="margin-right: 1px;margin-left: 1px;">
                    <li class="nav-item">
                        <a href="#confirm_tab" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                            <i class="mdi mdi-home-variant d-md-none d-block"></i>
                            <span class="d-none d-md-block">Lead Confirm Quotation Details</span>
                        </a>
                    </li>
                    
                    <li class="nav-item d-none">
                        <a href="#home1" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                            <i class="mdi mdi-home-variant d-md-none d-block"></i>
                            <span class="d-none d-md-block">Quotation Details</span>
                        </a>
                    </li>
                        
                    <li class="nav-item accomodation_tab d-none">
                        <a href="#profile1" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                        <i class="uil-bed d-md-none d-block"></i>
                            <span class="d-none d-md-block">Accomodation</span>
                        </a>
                    </li>
                    
                    <li class="nav-item flights_tab d-none">
                        <a href="#settings1" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                           <i class="uil-plane-fly d-md-none d-block"></i>
                            <span class="d-none d-md-block">Flights Details</span>
                        </a>
                    </li>
                    
                    <li class="nav-item visa_tab d-none">
                        <a href="#visa_details" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                           <i class="mdi mdi-passport d-md-none d-block"></i>
                            <span class="d-none d-md-block">Visa Details</span>
                        </a>
                    </li>
                    
                    <li class="nav-item transportation_tab d-none">
                        <a href="#trans_details_1" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                           <i class="uil-bus d-md-none d-block"></i>
                            <span class="d-none d-md-block">Transportation</span>
                        </a>
                    </li>
                      
                    <li class="nav-item d-none">
                        <a  href="#Itinerary_details" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                           <i class="uil-globe d-md-none d-block"></i>
                            <span class="d-none d-md-block">Itinerary</span>
                        </a>
                    </li>
                    
                    <li class="nav-item d-none">
                        <a href="#Extras_details" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                          <i class="uil-moneybag d-md-none d-block"></i>
                            <span class="d-none d-md-block">Costing</span>
                        </a>
                    </li>
                </ul>
                
                <div class="tab-content">
                    
                    <div class="tab-pane show active" id="confirm_tab">
                        
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                        
                                        <h3>Confirmation Required</h3>
                                        
                                        <div class="mt-3">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="checkbox_Passport" onchange="check_confirm_P()" required>
                                                <label class="form-check-label" for="checkbox_Passport">Passport Details?</label>
                                            </div>
                                            <?php $flights_details = $get_invoice->flights_details; ?>
                                            @if(isset($flights_details) && $flights_details != null && $flights_details != '')
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="checkbox_Attol" onchange="check_confirm_A()" required>
                                                    <label class="form-check-label" for="checkbox_Attol">ATOL Certificate?</label>
                                                </div>
                                            @endif
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="checkbox_Iternary" onchange="check_confirm_I()" required>
                                                <label class="form-check-label" for="checkbox_Iternary">Indemnity Form?</label>
                                            </div>
                                            <div class="form-check d-none">
                                                <input type="checkbox" class="form-check-input" id="checkbox_Deposit" onchange="check_confirm_D()">
                                                <label class="form-check-label" for="checkbox_Deposit">Deposit Required?</label>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row" id="passport_details_div" style="display:none">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h3>Passport Details</h3>
                                        
                                        <div class="row" id="passport_type_select_div" style="display:none">
                                            <div class="col-xl-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Select Passport Type</label>
                                                    <select class="form-control" id="passport_type" name="passport_type">
                                                        <option value="">Select Channel</option>
                                                        <option value="Upload">Upload</option>
                                                        <option value="Written">Written</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row" id="passport_div_Upload" style="display:none">
                                            <div class="col-xl-4" style="padding: 10px;">
                                                <div class="input-group" id="timepicker-input-group1">
                                                    <span title="Upload Passport" class="input-group-btn input-group-append">
                                                        <button class="btn btn-primary bootstrap-touchspin-up" data-bs-toggle="modal" data-bs-target="#passport-modal" type="button">
                                                            Upload Passport
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row" id="passport_div_wrriten" style="display:none">
                                            
                                            <div class="col-xl-4">
                                                <div class="mb-3">
                                                    <label class="form-label">First Name</label>
                                                    <input type="text" name="first_Name_passport" id="first_Name_passport" class="form-control" required>
                                                </div>
                                            </div>
                                            
                                            <div class="col-xl-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Last Name</label>
                                                    <input type="text" name="last_Name_passport" id="last_Name_passport" class="form-control" required>
                                                </div>
                                            </div>
                                            
                                            <div class="col-xl-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Nationality</label>
                                                    <input type="text" class="form-control" id="nationality_passport" name="nationality_passport" required>
                                                </div>
                                            </div>
                                            
                                            <div class="col-xl-4">
                                                <div class="mb-3">
                                                    <label class="form-label">DOB</label>
                                                    <input type="text" name="date_of_birth_passport" id="date_of_birth_passport" class="form-control" required>
                                                </div>
                                            </div>
                                            
                                            <div class="col-xl-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Passport Number</label>
                                                    <input type="text" name="passport_Number" id="passport_Number" class="form-control" required>
                                                </div>
                                            </div>
                                            
                                            <div class="col-xl-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Passport Expiry</label>
                                                    <input type="text" name="passport_Expiry" id="passport_Expiry" class="form-control" required>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row" id="Atol_details_div" style="display:none">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h3>Atol Details</h3>
                                        
                                        <div class="button-list d-none">
                                            <button style="height: 40px;" type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#send-mail">Send Mail</button>
                                        </div>  
                                        
                                        <div class="mb-3">
                                            <label for="email_Address" class="form-label">Email address</label>
                                            <input class="form-control" type="email" name="email_Address" id="email_Address" placeholder="Enter Your Email" value="{{ $get_invoice->email }}">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="comment_Area" class="form-label">Atol Details</label>
                                            <textarea class="form-control" name="comment_Area" required readonly>Dear {{ $get_invoice->f_name }},Your booking with Alhijaz tours has been created . Your Invoice Number is : {{ $get_invoice->generate_id }} .Please find the attachment for detailed Invoice and check the below link for detailed itinerary, {{ URL::to('invoice') }}/{{ $get_invoice->generate_id }} . Regards,Team Alhijaz Tours Ltd.</textarea>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="tab-pane d-none" id="home1">
                        <div class="row"> 
                            
                            <div class="col-xl-4">
                                <div class="mb-3">
                                    <div id="tooltip-container">
                                        <label for="simpleinput" class="form-label simpleinput">Services</label>
                                        <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                            data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Enter the package name">
                                        </i>
                                    </div>
                                    <select class="form-control  external_packages" onchange="selectServices()" data-toggle="select2" multiple="multiple" id="select_services" name="services[]">
                                        <option selected value="1">All Services</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-xl-4">
                                <div class="mb-3">
                                    <div id="tooltip-container">
                                        <label for="simpleinput" class="form-label simpleinput">Select Agent</label>
                                        <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                            data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Select the Agent Name">
                                        </i>
                                    </div>
                                    <select class="form-control" id="agent_Company_Name" name="agent_Company_Name">
                                       <option value="">Choose...</option>
                                        @if(isset($Agents_detail) && $Agents_detail !== null && $Agents_detail !== '')
                                            @foreach($Agents_detail as $Agents_details)
                                                <option <?php if($get_invoice->agent_Id == $Agents_details->id) echo "selected" ?>  attr-AN="{{ $Agents_details->agent_Name }}" attr-Id="{{ $Agents_details->id }}" value="{{ $Agents_details->company_name }}">{{ $Agents_details->company_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-xl-4">
                                <div class="mb-3">
                                    <div id="tooltip-container">
                                        <label for="simpleinput" class="form-label simpleinput">Select Customer</label>
                                        <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                            data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Select the Agent Name">
                                        </i>
                                    </div>
                                    <select class="form-control" id="customer_name" name="customer_name">
                                       <option value="-1">Choose...</option>
                                        @if(isset($customers_data) && $customers_data !== null && $customers_data !== '')
                                            @foreach($customers_data as $customers_res)
                                                <option attr-cusData='{{ json_encode($customers_res) }}' attr-Id="{{ $customers_res->id }}" <?php if($get_invoice->booking_customer_id == $customers_res->id) echo "selected" ?> value="{{ $customers_res->id }}">{{ $customers_res->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            
                            <input type="hidden" id="agent_Id" name="agent_Id" class="form-control" value="{{ $get_invoice->agent_Id ?? '' }}">
                            
                            <input type="hidden" id="agent_Name" name="agent_Name" class="form-control" value="{{ $get_invoice->agent_Name ?? '' }}">
                            
                            <div class="col-xl-4">
                                <div class="mb-3">
                                    <div id="tooltip-container">
                                        <label for="" class="form-label">Currency Symbol</label>
                                        <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                            data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Currency Symbol will be auto-selected for your account">
                                        </i>
                                    </div>
                                    <select name="currency_symbol" class="form-control currency_symbol">
                                        @foreach($all_curr_symboles as $all_curr_symbolesS)
                                            <option <?php if($all_curr_symbolesS->currency_symbol == $get_invoice->currency_symbol) echo 'selected'; ?> value="{{ $all_curr_symbolesS->currency_symbol }}">{{ $all_curr_symbolesS->currency_symbol }}</option>
                                        @endforeach
                                    </select>
                                    <input readonly value="{{ $get_invoice->currency_symbol ?? '' }}" name="" class="form-control currency_symbol" hidden>
                                </div>
                            </div>
                            
                            <div class="col-xl-4 d-none">
                                <div class="mb-3">
                                    <div id="tooltip-container">
                                        <label for="simpleinput" class="form-label no_of_pax_days">No Of Pax</label>
                                        <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                            data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="No Of Pax for this package">
                                        </i>
                                    </div>
                                    
                                    <input type="number" name="no_of_pax_days" value="{{ $get_invoice->no_of_pax_days ?? '1' }}" min="1" class="form-control no_of_pax_days" id="no_of_pax_days" required readonly>
                                    <input type="number" id="no_of_pax_prev" hidden value="1">
                                    <div class="invalid-feedback">
                                        This Field is Required
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-xl-4">
                                <div class="mb-3">
                                    <div id="tooltip-container">
                                        <label for="simpleinput" class="form-label">Currency Conversion</label>
                                        <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                            data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Currency Convirsion">
                                        </i>
                                    </div>
                                    <select class="form-control CC_id_store" name="currency_conversion" id="currency_conversion1">
                                        <option value="0">Select Currency Conversion</option>
                                        @foreach($mange_currencies as $mange_currencies)
                                            <option attr_id="{{$mange_currencies->id}}" <?php if($get_invoice->currency_conversion == $mange_currencies->purchase_currency .' '.'TO'.' '. $mange_currencies->sale_currency) echo 'selected' ?> attr_conversion_type="{{$mange_currencies->conversion_type}}" value="{{$mange_currencies->purchase_currency}} TO {{$mange_currencies->sale_currency}}">{{$mange_currencies->purchase_currency}} TO {{$mange_currencies->sale_currency}}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" id="conversion_type_Id"  name="conversion_type_Id" value="{{ $get_invoice->conversion_type_Id ?? '' }}">
                                    <input type="hidden" id="select_exchange_type"  name="conversion_type" value="" > 
                                </div>
                            </div>
                            
                            <div class="col-xl-4">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Author</label>      
                                    <select name="tour_author" id="" class="form-control tour_author">
                                        <option value="">Select Author</option>
                                        <option <?php if($get_invoice->tour_author == 'admin') echo "selected" ?>  value="admin">admin</option>
                                        <option <?php if($get_invoice->tour_author == 'admin1') echo "selected" ?> value="admin1">admin1</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-xl-12">
                                <div class="mb-3">
                                    <h6>Lead Passenger Detials</h6>
                                    <div class="row" style="border: 1px solid #ebebeb;padding: 1rem;border-radius: 6px;">
                                        <div class="col-xl-6">
                                            <div class="mb-3">
                                                <label for="simpleinput" class="form-label">First Name</label>
                                                <input type="text" name="lead_fname" class="form-control" required value="{{$get_invoice->f_name}}" id="lead_fnameI">
                                            </div>
                                        </div>
                                        
                                         <div class="col-xl-6">
                                            <div class="mb-3">
                                                <label for="simpleinput" class="form-label">Last Name</label>
                                                <input type="text" name="lead_lname" class="form-control" required value="{{$get_invoice->middle_name}}" id="lead_lnameI">
                                            </div>
                                        </div>
                                        
                                         <div class="col-xl-6">
                                            <div class="mb-3">
                                                <label for="simpleinput" class="form-label">Email</label>
                                                <input type="text" name="email" class="form-control" required value="{{$get_invoice->email}}" id="lead_emailI">
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-6">
                                            <div class="mb-3">
                                                <label for="simpleinput" class="form-label">Contact Number</label>
                                                <input type="text" name="mobile" class="form-control" required value="{{$get_invoice->mobile}}" id="lead_mobileI">
                                            </div>
                                        </div>
                                                
                                        <div class="col-xl-4">
                                            <div class="mb-3">
                                                <label for="simpleinput" class="form-label">Select Nationality</label>
                                                 <select type="text" class="form-control select2 " name="nationality"  data-placeholder="Choose ...">
                                                    @foreach($all_countries as $country_res)
                                                        <option <?php if($get_invoice->country == $country_res->id) echo "selected" ?> value="{{$country_res->id}}" id="categoriesPV" required>{{$country_res->name}}</option>
                                                       
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-2" >
                                            <div class="mb-3">
                                                 <p style="margin-top: 2.2rem;">Gender</p>
                                            </div>
                                        </div>
                                        
                                           
                                        <div class="col-2">
                                          <div class="form-check" style="margin-top: 2.2rem;">
                                          <input class="form-check-input" type="radio" name="gender" <?php if($get_invoice->gender == 'male') echo "checked" ?> value="male"  id="flexRadioDefault3">
                                          <label class="form-check-label" for="flexRadioDefault3">
                                            Male
                                          </label>
                                        </div>
                                        
                                        </div>
                                        <div class="col-2" style="margin-top: 2.2rem;">
                                            <div class="form-check">
                                          <input class="form-check-input" type="radio" name="gender" <?php if($get_invoice->gender == 'female') echo "checked" ?> value="female"  id="flexRadioDefault4">
                                          <label class="form-check-label" for="flexRadioDefault4">
                                            Female
                                          </label>
                                        </div>
                                        
                                        </div>
                                        
                                       
                                        
                                    </div>
                                    
                                    
                                    <div id="other_passengers">
                                        
                                        
                                    </div>
                                    
                                </div>
                            </div>
                            
                            <div class="col-xl-12 d-none">
                                <div class="mb-3">
                                    <div id="tooltip-container">
                                        <label for="simpleinput" class="form-label">Content</label>
                                        <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                            data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Additional Information of Package">
                                        </i>
                                    </div>
                                    <!--<textarea name="content" id="" class="contentP summernote" cols="142" rows="10"></textarea>-->
                                    <textarea name="content" class="contentP" cols="135" rows="5"></textarea>
                                </div>
                            </div>
                            
                            <div class="col-xl-4 start_date_div">
                                <div class="mb-3">
                                    <div id="tooltip-container">
                                        <label for="simpleinput" class="form-label start_date start_date_label">Start Date</label>
                                        <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                            data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Select start date of package">
                                        </i>
                                    </div>
                                    
                                    <input name="start_date" class="form-control" value="{{$get_invoice->start_date}}" id="start_date" required placeholder="yyyy/mm/dd" readonly>
                                    <div class="invalid-feedback">
                                        This Field is Required
                                    </div>
                                </div>
                             </div>
                            
                            <div class="col-xl-4 end_date_div">
                                <div class="mb-3">
                                    
                                    <div id="tooltip-container">
                                        <label for="simpleinput" class="form-label end_date">End Date</label>
                                        <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                            data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Select end date of package">
                                        </i>
                                    </div>
                                    <input name="end_date" class="form-control" value="{{$get_invoice->end_date}}" id="end_date" placeholder="yyyy/mm/dd" readonly>
                                    <div class="invalid-feedback">
                                        This Field is Required
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-xl-4 total_duration_div">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label no_of_Nights_Other">No Of Nights</label>
                                    <input readonly type="text" name="time_duration" id="duration" value="{{$get_invoice->time_duration}}" class="form-control time_duration" >
                                </div>
                            </div>
                             
                            <div class="col-xl-4 d-none" >
                                    <div class="mb-3">
                                        <div>
                                            <div id="tooltip-container">
                                                <label for="categoriesPV">Categories</label>
                                                <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                                    data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Select package Category">
                                                </i>
                                            
                                            
                                                <span title="Add Categories" class="input-group-btn input-group-append" style="float: right;margin-bottom:10px">
                                                    <button class="btn btn-primary bootstrap-touchspin-up" data-bs-toggle="modal" data-bs-target="#add-Categories-modal" type="button">+</button>
                                                </span>
                                        
                                                <select type="text" class="form-control select2 categories" name="categories"  data-placeholder="Choose ...">
                                                    @foreach($categories as $categories)
                                                        <option value="{{$categories->id}}" id="categoriesPV">{{$categories->title}}</option>
                                                        <div class="invalid-feedback">
                                                            This Field is Required
                                                        </div>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
                            <div class="col-xl-4 d-none">
                                <div class="mb-3">
                                    <div>
                                        <div id="tooltip-container">
                                            <label for="simpleinput" class="form-label">Tour Featured </label>
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                                data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Display your package on Home Page">
                                            </i>
                                        </div>
                                        <select name="tour_feature" id="" class="form-control tour_feature" style="margin-top: 18px">
                                            <option value="">Select Featured</option>
                                            <option value="0">Enable featured</option>
                                            <option value="1">Disable featured</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-xl-4 d-none">
                                <div class="mb-3">
                                    <div>
                                        <!--<div id="tooltip-container">-->
                                            <label for="simpleinput" class="form-label">Default State</label>
                                            <!--<i class="dripicons-information" style="font-size: 17px;" id="title_Icon"-->
                                                <!--data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="No Of Pax Info">-->
                                            <!--</i>-->
                                        <!--</div>-->
                                        <select name="defalut_state" id="" class="form-control defalut_state" style="margin-top: 18px">
                                            <option value="">Select Default State</option>
                                            <option value="0">Always available</option>
                                            <option value="1">Only available on specific dates</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                              
                            <div class="col-xl-6 d-none">
                               <div class="mb-3">
                                    <div id="tooltip-container">
                                        <label for="simpleinput" class="form-label">Banner Image</label>
                                        <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                            data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Upload your detail page image for Package (Image size should be 'Width:1600px' 'Height:300px')">
                                        </i>
                                    </div>
                                    <input type="file" id="simpleinput" name="tour_featured_image" class="form-control tour_featured_imageP">
                                </div>
                            </div>
                            
                            <div class="col-xl-6 d-none">
                                <div class="mb-3">
                                    <div id="tooltip-container">
                                        <label for="simpleinput" class="form-label">Featured Image</label>
                                        <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                            data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Upload your main image for Package">
                                        </i>
                                    </div>
                                    <input type="file" id="simpleinput" name="tour_banner_image" class="form-control tour_banner_imageP">
                                </div>
                            </div>
                            
                            <div class="col-xl-3 more_Div_Acc_All" style="display:none">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Option Date</label>   
                                    <input type="date" id="option_date" value="{{$get_invoice->option_date}}" name="option_date" class="form-control ">
                                </div>
                            </div>
                            
                            <div class="col-xl-3 more_Div_Acc_All" style="display:none">
                                <div class="mb-3">
                                   
                                        <label for="simpleinput" class="form-label">Reservation Number</label>
                                        
                                  
                                  <input type="text" id="reservation_number" value="{{$get_invoice->reservation_number}}" name="reservation_number" class="form-control ">
                              </div>
                            </div>
                            
                            <div class="col-xl-3 more_Div_Acc_All" style="display:none">
                                <div class="mb-3">
                                    
                                        <label for="simpleinput" class="form-label">Hotel Reservation Number</label>
                                       
                                  
                                  <input type="text" id="hotel_reservation_number" value="{{$get_invoice->hotel_reservation_number}}" name="hotel_reservation_number" class="form-control ">
                              </div>
                            </div>
                              
                            <div class="col-xl-8 d-none">
                                <div class="mb-3">
                                    <div id="tooltip-container">
                                        <label for="simpleinput" class="form-label">Gallery Images</label>
                                        <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                            data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Upload at least 10 images">
                                        </i>
                                    </div>
                                    <div>
                                        <?php $img_c = 1; ?>
                            	        <input type="file" id="" name="gallery_images[]" class="form-control gallery_imagesP{{ $img_c }}" onchange="validate1({{ $img_c }})">
                            	        <span class="file_error{{ $img_c }}"></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="more_img d-none"></div>
                                
                            <div class="mt-2 d-none">
                                <a href="javascript:;" id="more_images" class="btn btn-info" style="float: right;">Add Gallery Images</a>
                            </div>
                            
                        </div>
                        
                    </div>
                    
                    <div class="tab-pane d-none" id="profile1">
                        @if(!isset($get_invoice->destination_details))
                            <div class="row">
                                
                                <div class="col-xl-12">
                                    <div class="row">                                                 
                                        <div class="col-xl-1">
                                           <div class="mb-3">
                                                <input type="checkbox" id="destination" data-switch="bool"/>
                                                <label for="destination" data-on-label="On" data-off-label="Off"></label>
                                            </div>
                                        </div>
                                        <div class="col-xl-3">
                                            Destination
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Select Country & City"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row" id="select_destination">
                                    <div class="col-xl-6">
                                        <label for="">COUNTRY</label>
                                        <select name="destination_country" onchange="selectCities()" id="property_countryI" class="form-control">
                                            @foreach($all_countries as $country_res)
                                                <option value="{{ $country_res->id }}">{{ $country_res->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-xl-6">
                                        <label for="">How Many Hotel You Want To Add?</label>
                                        <input type="number" name="city_Count" value="" id="city_No" class="form-control" placeholder="Select...">
                                    </div>
                                    
                                    <div class="col-xl-6" style="display:none">
                                        <input type="hidden" name="tour_location_city" id="tour_location_city1" value="{{ $get_invoice->tour_location  ?? '' }}"/>
                                        <input type="hidden" name="tour_location_city_else" id="tour_location_city_else" value="{{ $get_invoice->tour_location  ?? '' }}"/>
                                    </div>
                                    
                                    <div id="append_destination"></div>
                                </div>
                            </div>
                        @else
                            <div class="row">
                                
                                <div class="col-xl-12 d-none">
                                    <div class="row">                                                 
                                        <div class="col-xl-1">
                                           <div class="mb-3">
                                                <input type="checkbox" id="destination" data-switch="bool"/>
                                                <label for="destination" data-on-label="On" data-off-label="Off"></label>
                                            </div>
                                        </div>
                                        <div class="col-xl-3">
                                            Destination
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Select Country & City"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php
                                    $destination_details=$get_invoice->destination_details;
                                    $destination_details=json_decode($destination_details);
                                    $destination_details=$destination_details->destination_country;
                                ?>
                                
                                @if(isset($get_invoice->destination_details))
                                    <div class="row"  id="select_destination">
                                        <div class="col-xl-6">
                                            <label for="">COUNTRY</label>
                                            <select name="destination_country" onchange="selectCitiesI()" id="property_countryI" class="form-control">
                                                @foreach($all_countries as $country_res)
                                                    <option <?php if($destination_details == $country_res->id) echo "selected" ?>  value="{{ $country_res->id }}">{{ $country_res->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="col-xl-6">
                                            <label for="">How Many Hotel You Want To Add?</label>
                                            <input type="number" name="city_Count" value="{{$get_invoice->city_Count ?? ''}}" id="city_No" class="form-control" placeholder="Select...">
                                        </div>
                                        
                                        <div class="col-xl-6" style="display:none">
                                            <input type="hidden" name="tour_location_city" id="tour_location_city1" value="{{ $get_invoice->tour_location  ?? '' }}"/>
                                            <input type="hidden" name="tour_location_city_else" id="tour_location_city_else" value="{{ $get_invoice->tour_location  ?? '' }}"/>
                                        </div>
                                        <input type="hidden" name="packages_get_city" id="packages_get_city" value="{{ $get_invoice->tour_location  ?? '' }}" />
                                        
                                        <div id="append_destination"></div>
                                    </div>
                                @endif
                            </div>
                        @endif
                        
                        <div class="row">
                            <div class="col-xl-12 mt-2">
                                <div class="mb-3">
                                    <div class="row"></div>
                                </div>
                            </div>
                        
                            <?php
                                $accomodation_details=$get_invoice->accomodation_details;
                                $accomodation_details=json_decode($accomodation_details);
                                //print_r($accomodation_details);die();
                            ?>
                            @if(isset($get_invoice->accomodation_details) && $get_invoice->accomodation_details != null && $get_invoice->accomodation_details != '' && $get_invoice->accomodation_details != 'null')
                                <?php $i = 1; ?>
                                <?php $img_Counter = 0; ?>
                                <?php $divId = 1; ?>
                                <?php $del_counter = 1; ?>
                                <?php $del_acc_city = 1; ?>
                                <div id="append_accomodation">
                                    <?php
                                        $count_hotel=1;
                                        foreach($accomodation_details as $details)
                                        {
                                    ?>
                                            <div class="mb-2" style="border:1px solid #ced4da;padding: 20px 20px 20px 20px;" id="del_hotelI{{ $i }}">
                                                <h4>
                                                    City #{{$i}} ({{$details->hotel_city_name}})
                                                    <i class="dripicons-information" style="font-size: 17px;" id="title_Icon_${i}"
                                                        data-bs-toggle="tooltip" data-bs-placement="right" title="Fill all the information of City {{$details->hotel_city_name}}">
                                                    </i>
                                                </h4> 
                                                <div class="row">
                                                    
                                                    <div class="col-xl-3">
                                                        <label for="">Check In</label>
                                                        <input type="date" value="{{$details->acc_check_in}}" id="makkah_accomodation_check_in_{{$i}}" name="acc_check_in[]" onchange="makkah_accomodation_check_inI({{$i}})" class="form-control  makkah_accomodation_check_in_class_{{$i}} check_in_hotel_{{ $i }}">
                                                    </div>
                                                        
                                                    <div class="col-xl-3">
                                                        <label for="">Check Out</label>
                                                        <input type="date" value="{{$details->acc_check_out}}" id="makkah_accomodation_check_out_date_{{$i}}" name="acc_check_out[]" onchange="makkah_accomodation_check_outI({{$i}})" class="form-control  makkah_accomodation_check_out_date_class_{{$i}} check_out_hotel_{{ $i }}">
                                                    </div>
                                                    
                                                    <div class="col-xl-3">
                                                        <label for="">City Name</label>
                                                        <input readonly value="{{ $details->hotel_city_name ?? ''}}" type="text" name="hotel_city_name[]" class="form-control property_city_newInput_I property_city_new_{{ $i }}"/>
                                                        <select type="text" id="property_city_new{{ $i }}" onchange="put_tour_locationI({{ $i }})" name="hotel_city_name[]" class="form-control property_city_new_selectI" style="display:none"/></select>
                                                    </div>
                                                    
                                                    <div class="col-xl-3">
                                                        <label for="">Hotel Name</label>
                                                        
                                                        <input type="text" id="switch_hotel_name{{ $i }}" name="switch_hotel_name[]" value="1" style="display:none">
                                                        
                                                        <div class="input-group" id="add_hotel_div{{ $i }}">
                                                            <input type="text" onkeyup="hotel_funI({{ $i }})" value="{{$details->acc_hotel_name}}" id="acc_hotel_name_{{ $i }}" name="acc_hotel_name[]" class="form-control acc_hotel_name_class_{{ $i }}">
                                                        </div>
                                                        <a style="float: right;font-size: 10px;width: 102px;" onclick="select_hotel_btn({{ $i }})" class="btn btn-primary select_hotel_btn{{ $i }}">
                                                            SELECT HOTEL
                                                        </a>
                                                        
                                                        <div class="input-group" id="select_hotel_div{{ $i }}" style="display:none">
                                                            <select onchange="get_room_types({{ $i }})" id="acc_hotel_name_{{ $i }}" name="acc_hotel_name_select[]" class="form-control acc_hotel_name_class_{{ $i }} get_room_types_{{ $i }}">
                                                                <option></option>
                                                            </select>
                                                        </div>
                                                        <a style="display:none;float: right;font-size: 10px;width: 102px;" onclick="add_hotel_btn({{ $i }})" class="btn btn-primary add_hotel_btn{{ $i }}">
                                                            ADD HOTEL
                                                        </a>
                                                        <input type="text" id="select_hotel_id{{ $i }}" hidden  name="hotel_id[]" value="@isset($details->hotel_id) {{$details->acc_hotel_name}} @endisset">
                                                    </div>
                                                    
                                                    <div class="col-xl-3">
                                                        <label for="">No Of Nights</label>
                                                        <input readonly type="text" value="{{$details->acc_no_of_nightst}}" id="acomodation_nights_{{$i}}" name="acc_no_of_nightst[]" class="form-control acomodation_nights_class_{{$i}}">
                                                    </div>
                                                    <!--app/resources/views/template/frontend/userdashboard/pages/manageoffie/create_invoice?-->
                                                    <div class="col-xl-3">
                                                        <label>Room Type</label>
                                                        <input type="hidden" value="1" id="switch_htfI_{{ $i }}">
                                                        <div class="input-group hotel_type_add_div_{{ $i }}">
                                                            <select onclick="hotel_type_funI({{ $i }})" name="acc_type[]" id="hotel_type_{{ $i }}" class="form-control other_Hotel_Type hotel_type_class_{{ $i }}" data-placeholder="Choose ...">
                                                                @if($details->acc_type == 'Quad')
                                                                    <option selected attr="4" value="Quad">Quad</option>
                                                                @elseif($details->acc_type == 'Triple')
                                                                    <option selected attr="3" value="Triple">Triple</option>
                                                                @elseif($details->acc_type == 'Double')
                                                                    <option selected attr="2" value="Double">Double</option>
                                                                @else
                                                                    <option attr="" value=""></option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                        
                                                       <select onchange="hotel_type_funInvoice({{ $i }})" name="acc_type_select[]" style="display:none" id="hotel_type_{{ $i }}" class="hotel_type_select_div_{{ $i }} form-control other_Hotel_Type hotel_type_class_{{ $i }} hotel_type_select_class_{{ $i }}" data-placeholder="Choose ...">
                                                                <option value="">Choose ...</option>
                                                            </select>

                                                        <select onchange="add_new_room_type({{ $i }})" name="new_rooms_type[]" style="display:none;" id="new_rooms_type_{{ $i }}" class="form-control other_Hotel_Type new_rooms_type_{{ $i }} "  data-placeholder="Choose ...">
                                                            <option value="">Choose ...</option>
                                                        </select>
                                                        <input type="text" id="select_add_new_room_type_{{ $i }}" hidden  name="new_add_room[]" value="false">
                                                        
                                                    </div>
                                                    
                                                     <div class="col-xl-3" id="new_room_supplier_div_{{ $i }}" style="display:none">
                                                        <label for="">Select Supplier</label>
                                                        <select class="form-control" id="new_room_supplier_{{ $i }}" name="new_room_supplier[]">
                                                            <option>Select One</option>
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="col-xl-3">
                                                        <label for="">Quantity</label>
                                                        <input value="{{$details->acc_qty}}" type="text" id="simpleinput" name="acc_qty[]" class="form-control acc_qty_class_{{ $i }}" onkeyup="acc_qty_classI({{ $i }})">
                                                        <div class="row" style="padding: 2px;">
                                                            <div class="col-lg-6">
                                                                <a style="display: none;font-size: 10px;" class="btn btn-success" id="room_quantity_{{ $i }}"></a>
                                                                <input type="hidden" class="room_quantity_{{ $i }}">
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <a style="display: none;font-size: 10px;" class="btn btn-primary" id="room_available_{{ $i }}"></a>
                                                                <input type="hidden" class="room_available_{{ $i }}">
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="row" style="padding: 2px;">
                                                            <div class="col-lg-6">
                                                                <a style="display: none;font-size: 10px;" class="btn btn-info" id="room_booked_quantity_{{ $i }}"></a>
                                                                <input type="hidden" class="room_booked_quantity_{{ $i }}">
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <a style="display: none;font-size: 10px;" class="btn btn-danger" id="room_over_booked_quantity_{{ $i }}"></a>
                                                                <input type="hidden" class="room_over_booked_quantity_{{ $i }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-xl-3">
                                                        <label for="">Pax</label>
                                                        <input type="text" id="simpleinput" value="{{$details->acc_pax}}" name="acc_pax[]" class="form-control acc_pax_class_{{$i}}" readonly>
                                                    </div>
                                                    
                                                    <div class="col-xl-3">
                                                        <?php
                                                            $hotel_meal_type=$details->hotel_meal_type;
                                                        ?>
                                                        <label for="">Meal Type</label>
                                                        <select name="hotel_meal_type[]" id="hotel_meal_type_{{$i}}" class="form-control hotel_meal_types" data-placeholder="Choose ...">
                                                            <option <?php if($hotel_meal_type == 'Room only') echo 'selected'; ?> value="Room only">Room only</option>
                                                            <option <?php if($hotel_meal_type == 'Breakfast') echo 'selected'; ?> value="Breakfast">Breakfast</option>
                                                            <option <?php if($hotel_meal_type == 'Lunch') echo 'selected'; ?> value="Lunch">Lunch</option>
                                                            <option <?php if($hotel_meal_type == 'Dinner') echo 'selected'; ?> value="Dinner">Dinner</option>
                                                         </select>
                                                    </div>
                                                    
                                                    <div id="hotel_price_for_week_end_{{ $i }}" class="row"></div>
                                                    
                                                    <h4 class="mt-4">Purchase Price</h4>
                                                    
                                                    <input type="hidden" id="hotel_invoice_markup_{{ $i }}" name="hotel_invoice_markup[]" value="{{ $details->hotel_invoice_markup ?? '' }}">
                                                    
                                                    <input type="hidden" id="hotel_supplier_id_{{ $i }}" name="hotel_supplier_id[]" value="{{ $details->hotel_supplier_id ?? '' }}">
                                    
                                                    <input type="hidden" id="hotel_type_id_{{ $i }}" name="hotel_type_id[]" value="{{ $details->hotel_type_id ?? '' }}">
                                                    
                                                    <input type="hidden" id="hotel_type_cat_{{ $i }}" name="hotel_type_cat[]" value="{{ $details->hotel_type_cat ?? '' }}">
                                                    
                                                    <input type="hidden" id="hotelRoom_type_id_{{ $i }}" name="hotelRoom_type_id[]" value="{{ $details->hotelRoom_type_id ?? '' }}">
                                                    
                                                    <input type="hidden" id="hotelRoom_type_idM_{{ $i }}" name="hotelRoom_type_idM[]" value="{{ $details->hotelRoom_type_idM ?? '' }}">
                                                                        
                                                    <div class="col-xl-4">
                                                        <label for="">Price Per Room/Night</label>
                                                        <div class="input-group">
                                                            <span class="input-group-btn input-group-append">
                                                                <a id="" class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                                                  
                                                                </a>
                                                            </span>
                                                            <input type="text" id="makkah_acc_room_price_{{$i}}" onkeyup="makkah_acc_room_price_funsI({{$i}})" value="{{$details->price_per_room_purchase}}" name="price_per_room_purchase[]" class="form-control">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-xl-4">
                                                        <label for="">Price Per Person/Night</label>
                                                        <div class="input-group">
                                                            <span class="input-group-btn input-group-append">
                                                                <a id="" class="btn btn-primary bootstrap-touchspin-up currency_value2">
                                                                 
                                                                </a>
                                                            </span>
                                                            <input type="text" id="makkah_acc_price_{{$i}}" onkeyup="makkah_acc_price_funs({{$i}})" value="{{$details->acc_price_purchase}}" name="acc_price_purchase[]" class="form-control makkah_acc_price_class_{{$i}}">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-xl-4"><label for="">Total Amount/Room</label>
                                                        <div class="input-group">
                                                            <span class="input-group-btn input-group-append">
                                                                <a id="" class="btn btn-primary bootstrap-touchspin-up currency_value3">
                                                                  
                                                                </a>
                                                            </span>
                                                            <input readonly type="text"  id="makkah_acc_total_amount_{{$i}}" value="{{$details->acc_total_amount_purchase}}"  name="acc_total_amount_purchase[]" class="form-control makkah_acc_total_amount_class_{{$i}}">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-xl-6">
                                                        <label for="">Exchange Rate</label>
                                                        <div class="input-group">
                                                            <span class="input-group-btn input-group-append">
                                                                <a id="" class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                                                  
                                                                </a>
                                                            </span>
                                                            <input type="text" id="exchange_rate_price_funs_{{$i}}" value="{{$details->exchange_rate_price}}" onkeyup="exchange_rate_price_funsI({{$i}})" value="" name="exchange_rate_price[]" class="form-control">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-xl-6">
                                                    </div>
                                                                        
                                                    <h4 class="mt-4">Sale Price</h4>
                                                                        
                                                    <div class="col-xl-4">
                                                        <label for="">Price Per Room/Night</label>
                                                        <div class="input-group">
                                                            <span class="input-group-btn input-group-append">
                                                                <a id="" class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">
                                                                  
                                                                </a>
                                                            </span>
                                                            <input type="text" id="price_per_room_exchange_rate_{{$i}}" value="{{$details->price_per_room_sale}}"  name="price_per_room_sale[]" class="form-control">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-xl-4">
                                                    <label for="">Price Per Person/Night</label>
                                                    <div class="input-group">
                                                        <span class="input-group-btn input-group-append">
                                                            <a id="" class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                                              
                                                            </a>
                                                        </span>
                                                        <input type="text" id="price_per_person_exchange_rate_{{$i}}" value="{{$details->acc_price}}"  name="acc_price[]" class="form-control makkah_acc_price_class_{{$i}}">
                                                    </div>
                                    
                                                    </div>
                                                    
                                                    <div class="col-xl-4"><label for="">Total Amount/Room</label>
                                                     <div class="input-group">
                                                        <span class="input-group-btn input-group-append">
                                                            <a id="" class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_3">
                                                              
                                                            </a>
                                                        </span>
                                                        <input readonly type="text"  id="price_total_amout_exchange_rate_{{$i}}" value="{{$details->acc_total_amount}}" name="acc_total_amount[]" class="form-control">
                                                    </div>
                                                    </div>
                                                    
                                                    <input type="hidden" id="starting_acc{{ $del_acc_city }}" value="{{ $i }}">
                                                    
                                                    <div id="append_add_accomodation_{{$i}}">
                                                        
                                                        <?php
                                                            $accomodation_details_more=$get_invoice->accomodation_details_more;
                                                            $accomodation_details_more=json_decode($accomodation_details_more);
                                                            // dd($accomodation_details_more);
                                                        ?>
                                                        @if($get_invoice->accomodation_details_more != null)
                                                            <?php
                                                            if(isset($accomodation_details_more)){
                                                                foreach($accomodation_details_more as $more_details)
                                                                {
                                                            ?>
                                                                    @if(isset($more_details->more_hotel_city) && $more_details->more_hotel_city != null && $more_details->more_hotel_city != '')
                                                                        @if($details->hotel_city_name == $more_details->more_hotel_city)    
                                                                            <div id="click_delete_{{$divId}}" class="mb-2 mt-3" style="border:1px solid #ced4da;padding: 20px 20px 20px 20px;">
                                                                                
                                                                                <input readonly type="hidden" id="acc_nights1_{{$divId}}" value="{{$i}}" class="form-control">
                                                                                <input type='hidden' name="more_hotel_city[]" value="{{$more_details->more_hotel_city}}" id="more_hotel_city{{$more_details->more_hotel_city}}" class="more_property_city_new_selectI"/>
                                                                                
                                                                                <div class="row" style="padding:20px;">
                                                                                    
                                                                                    <div class="col-xl-3">
                                                                                        <label for="">Check In</label>
                                                                                        <input type="date" value="{{ $more_details->more_acc_check_in }}" id="more_makkah_accomodation_check_in_{{ $divId }}" onchange="more_makkah_accomodation_check_in_class({{ $divId }})" name="more_acc_check_in[]" class="form-control more_date makkah_accomodation_check_in_class_{{ $divId }} more_check_in_hotel_{{ $divId }}">
                                                                                    </div>
                                                                                    
                                                                                    <div class="col-xl-3">
                                                                                        <label for="">Check Out</label>
                                                                                        <input type="date" value="{{ $more_details->more_acc_check_out }}" id="more_makkah_accomodation_check_out_date_{{ $divId }}"  name="more_acc_check_out[]" onchange="more_makkah_accomodation_check_out({{ $divId }})" class="form-control more_makkah_accomodation_check_out_date_class_{{ $divId }} more_check_out_hotel_${divId}">
                                                                                    </div>
                                                                                    
                                                                                    <div class="col-xl-3">
                                                                                        <label for="">Hotel Name</label>
                                                                                        
                                                                                        <input type="text" id="more_switch_hotel_name{{ $divId }}" name="more_switch_hotel_name[]" value="1" style="display:none" class="more_switch_hotel_name">
                                                                                        
                                                                                        <div class="input-group" id="more_add_hotel_div{{ $divId }}">
                                                                                            <input type="text" onkeyup="more_hotel_funI({{ $divId }})" value="{{ $more_details->more_acc_hotel_name }}" id="more_acc_hotel_name_{{ $divId }}" name="more_acc_hotel_name[]" class="form-control more_acc_hotel_name_class_{{ $divId }}">
                                                                                        </div>
                                                                                        <a style="float: right;font-size: 10px;width: 102px;" onclick="more_select_hotel_btn({{ $divId }})" class="btn btn-primary more_select_hotel_btn{{ $divId }}">
                                                                                            SELECT HOTEL
                                                                                        </a>
                                                                                        
                                                                                        <div class="input-group" id="more_select_hotel_div{{ $divId }}" style="display:none">
                                                                                            <select onchange="more_get_room_types({{ $divId }})" id="more_acc_hotel_name_select_{{ $divId }}" name="more_acc_hotel_name_select[]" class="form-control more_get_room_types_{{ $divId }}">
                                                                                                <option attr_id="" value=""></option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <a style="display:none;float: right;font-size: 10px;width: 102px;" onclick="more_add_hotel_btn({{ $divId }})" class="btn btn-primary more_add_hotel_btn{{ $divId }}">
                                                                                            ADD HOTEL
                                                                                        </a>
                                                                                        <input type="text" id="more_select_hotel_id{{ $divId }}"  hidden name="more_hotel_id[]" value="">
                                                                                    </div>
                                                                                    
                                                                                    <div class="col-xl-3"><label for="">No Of Nights</label>
                                                                                        <input readonly value="{{ $more_details->more_acc_no_of_nightst }}" type="text" id="more_acomodation_nights_{{ $divId }}" name="more_acc_no_of_nightst[]" class="form-control acomodation_nights_class_{{ $divId }}">
                                                                                    </div>
                                                                    
                                                                                    <input type='hidden' value="{{ $more_details->more_hotel_city }}" id="more_hotel_city{{ $divId }}" class="more_property_city_new_selectI"/>
                                                                                    
                                                                                    <div class="col-xl-3">
                                                                                        <label for="">Room Type</label>
                                                                                        <input type="hidden" value="1" id="more_switch_htfI_{{ $divId }}">
                                                                                        <div class="input-group more_hotel_type_add_div_{{ $divId }} more_hotel_type_add_div">
                                                                                            <select onclick="more_hotel_type_fun({{ $divId }})" name="more_acc_type[]" id="more_hotel_type_{{ $divId }}" class="form-control other_Hotel_Type more_hotel_type_class_{{ $divId }}" data-placeholder="Choose ...">
                                                                                                @if($more_details->more_acc_type == 'Quad')
                                                                                                    <option selected attr="4" value="Quad">Quad</option>
                                                                                                @elseif($more_details->more_acc_type == 'Triple')
                                                                                                    <option selected attr="3" value="Triple">Triple</option>
                                                                                                @elseif($more_details->more_acc_type == 'Double')
                                                                                                    <option selected attr="2" value="Double">Double</option>
                                                                                                @else
                                                                                                    <option attr="" value=""></option>
                                                                                                @endif
                                                                                            </select>
                                                                                        </div>
                                                                                    
                                                                                        <select onchange="more_hotel_type_funInvoice({{ $divId }})" style="display:none" name="more_acc_type_select[]" id="more_hotel_type_select_{{ $divId }}" class="more_hotel_type_select_div_{{ $divId }} form-control other_Hotel_Type more_hotel_type_select_class_{{ $divId }}" data-placeholder="Choose ...">
                                                                                                <option value="">Choose ...</option>
                                                                                        </select>
                                                        
                                                                                        <select onchange="more_add_new_room_type({{ $divId }})" name="more_new_rooms_type[]" style="display:none;" id="more_new_rooms_type_{{ $divId }}" class="form-control other_Hotel_Type more_new_rooms_type_{{ $divId }} "  data-placeholder="Choose ...">
                                                                                            <option value="">Choose ...</option>
                                                                                        </select>
                                                                                        <input type="text" id="more_select_add_new_room_type_{{ $divId }}" hidden name="more_new_add_room[]" value="false">
                                                                                    </div>
                                                                                    
                                                                                     <div class="col-xl-3" id="more_new_room_supplier_div_{{ $divId }}" style="display:none">
                                                                                        <label for="">Select Supplier</label>
                                                                                        <select class="form-control" id="more_new_room_supplier_{{ $divId }}" name="more_new_room_supplier[]">
                                                                                            <option>Select One</option>
                                                                                        </select>
                                                                                    </div>
                                                                                
                                                                                    <div class="col-xl-3">
                                                                                        <label for="">Quantity</label>
                                                                                        <input value="{{$more_details->more_acc_qty}}" onkeyup="more_acc_qty_classInvoice({{$divId}})" type="text" id="simpleinput" name="more_acc_qty[]" class="form-control more_acc_qty_class_{{$divId}}">
                                                                                        <div class="row" style="padding: 2px;">
                                                                                            <div class="col-lg-6">
                                                                                                <a style="display: none;font-size: 10px;" class="btn btn-success" id="more_room_quantity_{{ $divId }}"></a>
                                                                                                <input type="hidden" class="more_room_quantity_{{ $divId }}">
                                                                                            </div>
                                                                                            <div class="col-lg-6">
                                                                                                <a style="display: none;font-size: 10px;" class="btn btn-primary" id="more_room_available_{{ $divId }}"></a>
                                                                                                <input type="hidden" class="more_room_available_{{ $divId }}">
                                                                                            </div>
                                                                                        </div>
                                                                                        
                                                                                        <div class="row" style="padding: 2px;">
                                                                                            <div class="col-lg-6">
                                                                                                <a style="display: none;font-size: 10px;" class="btn btn-info" id="more_room_booked_quantity_{{ $divId }}"></a>
                                                                                                <input type="hidden" class="more_room_booked_quantity_{{ $divId }}">
                                                                                            </div>
                                                                                            <div class="col-lg-6">
                                                                                                <a style="display: none;font-size: 10px;" class="btn btn-danger" id="more_room_over_booked_quantity_{{ $divId }}"></a>
                                                                                                <input type="hidden" class="more_room_over_booked_quantity_{{ $divId }}">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                    <div class="col-xl-3">
                                                                                        <label for="">Pax</label>
                                                                                        <input type="text" value="{{$more_details->more_acc_pax}}" id="more_acc_pax{{$divId}}" name="more_acc_pax[]" class="form-control more_acc_pax_class_{{$divId}}" readonly>
                                                                                    </div>
                                                                                    
                                                                                    <div class="col-xl-3">
                                                                                        <label for="">Meal Type</label>
                                                                                        <select name="more_hotel_meal_type[]" id="hotel_meal_type_{{$divId}}" class="form-control"  data-placeholder="Choose ...">
                                                                                            <option value="">Choose ...</option>
                                                                                            <option <?php if($more_details->more_hotel_meal_type == 'Room only') echo 'selected'; ?>  value="Room only">Room only</option>
                                                                                            <option <?php if($more_details->more_hotel_meal_type == 'Breakfast') echo 'selected'; ?> value="Breakfast">Breakfast</option>
                                                                                            <option <?php if($more_details->more_hotel_meal_type == 'Lunch') echo 'selected'; ?> value="Lunch">Lunch</option>
                                                                                            <option <?php if($more_details->more_hotel_meal_type == 'Dinner') echo 'selected'; ?> value="Dinner">Dinner</option>
                                                                                        </select>
                                                                                    </div>
                                                                                    
                                                                                    <div id="more_hotel_price_for_week_end_{{ $divId }}" class="row more_hotel_price_for_week_end"></div>
                                                                                    
                                                                                    <h4  class="mt-4">Purchase Price</h4>
                                                                                    
                                                                                    <input type="hidden" id="more_hotel_invoice_markup_{{ $divId }}" name="more_hotel_invoice_markup[]" value="{{ $more_details->more_hotel_invoice_markup ?? '' }}">
                                                                                    
                                                                                    <input type="hidden" id="more_hotel_supplier_id_{{ $divId }}" name="more_hotel_supplier_id[]" value="{{ $more_details->more_hotel_supplier_id ?? '' }}">
                        
                                                                                    <input type="hidden" id="more_hotel_type_id_{{ $divId }}" name="more_hotel_type_id[]" value="{{ $more_details->more_hotel_type_id ?? '' }}">
                                                                                                
                                                                                    <input type="hidden" id="more_hotel_type_cat_{{ $divId }}" name="more_hotel_type_cat[]" value="{{ $more_details->more_hotel_type_cat ?? '' }}">
                                                                                    
                                                                                    <input type="hidden" id="more_hotelRoom_type_id_{{ $divId }}" name="more_hotelRoom_type_id[]" value="{{ $more_details->more_hotelRoom_type_id ?? '' }}">
                                                                                    
                                                                                    <input type="hidden" id="more_hotelRoom_type_idM_{{ $divId }}" name="more_hotelRoom_type_idM[]" value="{{ $more_details->more_hotelRoom_type_idM ?? '' }}">
                                                                                    
                                                                                    <div class="col-xl-4">
                                                                                        <label for="">Price Per Room/Night</label>
                                                                                        <div class="input-group">
                                                                                            <span class="input-group-btn input-group-append">
                                                                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                                                                                  
                                                                                                </a>
                                                                                            </span>
                                                                                            <input type="text"  onkeyup="more_makkah_acc_room_price_funsI({{$divId}},{{$divId}})" value="{{$more_details->more_price_per_room_purchase}}" id="more_makkah_acc_room_price_funs_{{$divId}}" name="more_price_per_room_purchase[]" class="form-control">
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                    <div class="col-xl-4">
                                                                                        <label for="">Price Per Person/Night</label>
                                                                                        <div class="input-group">
                                                                                            <span class="input-group-btn input-group-append">
                                                                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                                                                                  
                                                                                                </a>
                                                                                            </span>
                                                                                            <input type="text" onchange="more_acc_price({{$divId}})" id="more_acc_price_get_{{$divId}}" value="{{$more_details->more_acc_price_purchase}}" name="more_acc_price_purchase[]" class="form-control">
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                    <div class="col-xl-4" >
                                                                                        <label for="">Total Amount/Room</label>
                                                                                        <div class="input-group">
                                                                                            <span class="input-group-btn input-group-append">
                                                                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                                                                                   
                                                                                                </a>
                                                                                            </span>
                                                                                            <input readonly type="text" id="more_acc_total_amount_{{$divId}}" value="{{$more_details->more_acc_total_amount_purchase}}" name="more_acc_total_amount_purchase[]" class="form-control">
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                    <div class="col-xl-6" >
                                                                                        <label for="">Exchange Rate</label>
                                                                                        <div class="input-group">
                                                                                            <span class="input-group-btn input-group-append">
                                                                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                                                                                   
                                                                                                </a>
                                                                                            </span>
                                                                                            <input type="text" id="more_exchange_rate_price_funs_{{$divId}}" onkeyup="more_exchange_rate_price_funsI({{$divId}})" value="{{$more_details->more_exchange_rate_price}}" name="more_exchange_rate_price[]" class="form-control">
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                    <div class="col-xl-4"></div>
                                                                                    
                                                                                    <h4  class="mt-4">Sale Price</h4>
                                                                                    
                                                                                    <div class="col-xl-4" >
                                                                                        <label for="">Price Per Room/Night</label>
                                                                                        <div class="input-group">
                                                                                            <span class="input-group-btn input-group-append">
                                                                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                                                                            </span>
                                                                                            <input type="text" id="more_price_per_room_exchange_rate_{{$divId}}" value="{{$more_details->more_price_per_room_sale}}" name="more_price_per_room_sale[]" class="form-control">
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                    <div class="col-xl-4">
                                                                                        <label for="">Price Per Person/Night5</label>
                                                                                        <div class="input-group">
                                                                                            <span class="input-group-btn input-group-append">
                                                                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                                                                            </span>
                                                                                            <input type="text"  id="more_price_per_person_exchange_rate_{{$divId}}" value="{{$more_details->more_acc_price}}" name="more_acc_price[]" class="form-control">
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                    <div class="col-xl-4">
                                                                                        <label for="">Total Amount/Room</label>
                                                                                        <div class="input-group">
                                                                                            <span class="input-group-btn input-group-append">
                                                                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                                                                            </span>
                                                                                            <input readonly type="text" id="more_price_total_amout_exchange_rate_{{$divId}}" value="{{$more_details->more_acc_total_amount}}" name="more_acc_total_amount[]" class="form-control">
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                    <div class="mt-2">
                                                                                        <a href="javascript:;" onclick="deleteRowaccI({{ $divId }})"  id="{{ $divId }}" class="btn btn-info" style="float: right;">Delete </a>
                                                                                    </div>
                                                                                
                                                                                </div>
                                                                            </div>
                                                                            <?php $divId++; ?>
                                                                        @endif
                                                                    @endif
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                        @endif
                                                        <input type="hidden" id="ending_acc{{ $del_acc_city }}" value="{{ $divId }}">
                                                        
                                                        <input type="hidden" id="divId_m_a_e_i" value="{{ $i }}">
                                                        
                                                    </div>
                                                    
                                                    <div class="mt-2">
                                                        <a href="javascript:;" onclick="add_more_accomodation_Invoice({{ $i }})" class="btn btn-info" style="float: right;"> + Add More </a>
                                                    </div>
                                                    
                                                    <div class="col-xl-12">
                                                        <div class="mb-3">
                                                            <label for="simpleinput" class="form-label">Room Amenities</label>
                                                            <textarea name="hotel_whats_included[]" class="form-control" value="{{ $details->hotel_whats_included }}" id="" cols="10" rows="0">{{ $details->hotel_whats_included }}</textarea>
                                                        </div>
                                                    </div>
                                                    
                                                    <?php
                                                        $accomodation_image = $details->accomodation_image;
                                                        $accomodation_image_encode = json_encode($details->accomodation_image);
                                                        
                                                        if(!is_array($accomodation_image)){
                                                            $accomodation_image = array($accomodation_image);
                                                        }else{
                                                            $accomodation_image = $details->accomodation_image;
                                                        }
                                                    ?>
                                                    
                                                    <div class="col-xl-12">
                                                        <label for="">Image</label>
                                                        <input multiple type="file" name="accomodation_image{{ $img_Counter }}[]" class="form-control">
                                                        <div class="row" style="padding:5px">
                                                            @if(isset($accomodation_image) || $accomodation_image !== null || $accomodation_image !== "")
                                                                @foreach($accomodation_image as $value1)
                                                                    <div class="col-md-3" id="accImg<?php echo $del_counter; ?>" style="text-align: center;margin-bottom: 10px;">
                                                                        <img src="{{ asset('/public/uploads/package_imgs/'.$value1) }}" style="width:233px;height:150px;margin-bottom: 10px;" />
                                                                        <div>
                                                                            <input type="text" name="accomodation_image_else{{ $img_Counter }}[]" class="form-control" value="{{ $value1 }}" readonly hidden>
                                                                            <button class="btn btn-danger" type="button" onclick="remove_acc_img(<?php echo $del_counter; ?>)">Delete</button>
                                                                        </div>
                                                                    </div>
                                                                    <?php $del_counter++; ?>
                                                                @endforeach
                                                            @else
                                                                <img src="{{ asset('/public/uploads/package_imgs/'.$value->accomodation_image) }}" style="width:233px;height:150px;margin-bottom: 10px;"  />
                                                                <input type="text" name="accomodation_image_else[]" class="form-control" value="{{ $accomodation_image_encode }}" readonly hidden>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="mt-2">
                                                        <a href="javascript:;" onclick="remove_hotelsI({{ $i }})" id="{{ $i }}" class="btn btn-danger" style="float: right;"> 
                                                            Delete Hotel
                                                        </a>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        <?php
                                            $count_hotel++;
                                            $img_Counter++;
                                            $i++;
                                        }
                                    ?>
                                </div>
                                <input type="text" id="hotel_map" class="form-control" value="{{ $i }}" readonly hidden>
                                <input type="text" id="del_counter1" class="form-control" value="{{ $del_counter }}" readonly hidden>
                                <input type="text" id="img_Counter1" class="form-control" value="{{ $img_Counter }}" readonly hidden>
                                <input type="text" value="{{ $divId }}" id="newdivId" hidden/>
                                <input type="hidden" id="city_No1" value="{{ $del_acc_city }}">
                                
                                <input type="hidden" id="count_hotel" value="{{ $count_hotel }}">
                                
                                <input type="hidden" id="more_acc_new_id" value="{{ $divId }}">
                                
                                <div class="col-xl-3">
                                    <div class="mt-2">
                                        <a href="javascript:;" id="add_hotel_accomodation_editI" class="btn btn-info"> 
                                            + Add Hotels 
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="row">
                                    <div class="col-xl-12 mt-2">
                                        <div class="mb-3">
                                            <div class="row">
                                                <div class="col-xl-3">
                                                    <div class="mt-2">
                                                        <a href="javascript:;" id="add_hotel_accomodation" class="btn btn-info"> 
                                                            + Add Hotels 
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="append_accomodation"> </div>
                                    <div id="append_accomodation1"></div>
                                </div>
                            @endif
                        </div>                                                      
                        <!--<a id="save_Accomodation" class="btn btn-primary" name="submit">Save Accomodation</a>-->   
                    </div>
                    
                    <div class="tab-pane d-none" id="settings1">
                    
                        <div class="col-xl-12 d-none">
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-xl-1">
                                        <input type="checkbox" id="flights_inc" data-switch="bool"/>
                                        <label for="flights_inc" data-on-label="On" data-off-label="Off"></label>
                                    </div>
                                    <div class="col-xl-3">
                                        Flights Included ?
                                        <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Add your flights details"></i>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        
                        <div class="row" id="select_flights_inc d-none">
                            <div class="row mb-2">
                                <?php //dd($get_invoice->flight_supplier); ?>
                                <div class="col-4">
                                    <select class="form-control" id="flight_route_type" name="flight_route_type" style="height: 3.4rem;">
                                        <option value="-1">Departure Route Type</option>
                                        @if(isset($get_invoice->flight_route_type) && $get_invoice->flight_route_type != null && $get_invoice->flight_route_type != '')
                                            <option <?php if($get_invoice->flight_route_type == 'Direct') echo 'selected'; ?> value="Direct" attr_type="Direct_Direct">Direct</option>
                                            <option <?php if($get_invoice->flight_route_type == 'Indirect') echo 'selected'; ?> value="Indirect" attr_type="Indirect_Indirect">Indirect</option>
                                            <option <?php if($get_invoice->flight_route_type == 'Direct_Indirect') echo 'selected'; ?> value="Direct_Indirect" attr_type="Direct_Indirect">Direct to Indirect</option>
                                            <option <?php if($get_invoice->flight_route_type == 'Indirect_Direct') echo 'selected'; ?> value="Indirect_Direct" attr_type="Indirect_Direct">Indirect to Direct</option>
                                        @else
                                            <option value="Direct" attr_type="Direct_Direct">Direct</option>
                                            <option value="Indirect" attr_type="Indirect_Indirect">Indirect</option>
                                            <option value="Direct_Indirect" attr_type="Direct_Indirect">Direct to Indirect</option>
                                            <option value="Indirect_Direct" attr_type="Indirect_Direct">Indirect to Direct</option>
                                        @endif
                                    </select>
                                </div>
                                
                                <div class="col-4 supplier_select_div" id="supplier_select_div">
                                    <select class="form-control" name="flight_supplier" id="supplier_name_id" style="height: 3.4rem;" onchange="supplier_name_id_fun()">
                                        <option value="-1">Select Supplier</option>
                                        @foreach($flight_suppliers as $value)
                                            @if(isset($get_invoice->flight_supplier) && $get_invoice->flight_supplier != null && $get_invoice->flight_supplier != '')
                                                @if($get_invoice->flight_supplier == $value->id)
                                                    <option <?php if($get_invoice->flight_supplier == $value->id) echo 'selected'; ?> value="{{ $value->id }}" att-CN="{{ $value->companyname }}" id="s_ID_{{ $value->id }}" style="display:none" class="sup_class">{{ $value->companyname }}</option>
                                                @else
                                                    <option value="{{ $value->id }}" att-CN="{{ $value->companyname }}" id="s_ID_{{ $value->id }}" style="display:none" class="sup_class">{{ $value->companyname }}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                
                            </div>
                            
                        </div>
                        
                        <div class="row" id="flight_supplier_list_div" style="display:none">
                            <div class="dashboard-content">
                                <h3 style="font-size: 40px;text-align:center">Routes List</h3>
                                <div class="row">
                                    <div class="col-lg-12 col-sm-12">
                                        <div class="dashboard-list-box dash-list margin-top-0">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table id="myTable" class="display nowrap table table-bordered" style="width:100%;">
                                                        <thead style="background: linear-gradient(176deg, rgb(28 63 170) 0%, rgb(0 14 58) 100%);color:white;">
                                                            <tr style="text-align: center;">
                                                                <th>Type</th>
                                                                <th>Location</th>
                                                                <th>Dates</th>
                                                                <th>Seats</th>
                                                                <th>Available Seats</th>
                                                                <th>Required Seats</th>
                                                                <th>Options</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody style="text-align: center;" id="flight_supplier_list_body"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row" id="flight_append_div">
                            
                            <!--Departure-->
                            @if(isset($get_invoice->flights_details) && $get_invoice->flights_details != null && $get_invoice->flights_details != '')
                                <?php
                                    $d_s                = 1;
                                    $fD_count           = 1;
                                    $flights_details    = json_decode($get_invoice->flights_details);
                                ?>
                                @foreach($flights_details as $valueD)
                                    @if(isset($valueD->departure_airport_code) && $valueD->departure_airport_code != null && $valueD->departure_airport_code != '')
                                        <div class="main_div_flight_departure_{{ $fD_count }}" style="margin-top: 10px;">      
                                        
                                            <input type="hidden" name="departure_flight_route_type[]" value='{{ $valueD->departure_flight_route_type ?? '' }}'>
                                            <input type="hidden" name="flight_route_id_occupied[]" value='{{ $valueD->flight_route_id_occupied ?? '' }}'>
                                            
                                            <h3 style="padding: 12px">Departure Details : </h3>
                                            <div class="row" style="padding: 12px">
                                                <div class="col-xl-4">
                                                    <label for="">Departure Airport</label>
                                                    <input name="departure_airport_code[]" readonly id="departure_airport_code" value="{{ $valueD->departure_airport_code ?? '' }}" class="form-control" placeholder="Enter Departure Location">
                                                </div>
                                                <div class="col-xl-1" style="margin-top: 25px;text-align: center;">
                                                    <label for=""></label>
                                                    <span id="Change_Location" readonly class='bi bi-arrow-left-right' style="font-size: 23px;"></span>
                                                </div>
                                                <div class="col-xl-4">
                                                    <label for="">Arrival Airport</label>
                                                    <input name="arrival_airport_code[]" readonly value="{{ $valueD->arrival_airport_code ?? '' }}" id="arrival_airport_code" class="form-control" placeholder="Enter Arrival Location">
                                                    
                                                </div>
                                                <div class="col-xl-3">
                                                    <label for="">Airline Name</label>
                                                    <input type="text" id="other_Airline_Name2" readonly value="{{ $valueD->other_Airline_Name2 ?? '' }}" name="other_Airline_Name2[]" class="form-control other_airline_Name1">
                                                   
                                                </div>
                                                <div class="col-xl-3" style="margin-top: 15px;">
                                                    <label for="">Class Type</label>
                                                    <select  name="departure_Flight_Type[]" readonly  id="departure_Flight_Type" class="form-control">
                                                        <option value="{{ $valueD->departure_Flight_Type ?? '' }}">{{ $valueD->departure_Flight_Type ?? '' }}</option>
                                                    </select>
                                                </div>
                                                <div class="col-xl-3" style="margin-top: 15px;">
                                                    <label for="">Flight Number</label>
                                                    <input type="text" id="simpleinput" readonly value="{{ $valueD->departure_flight_number ?? '' }}" name="departure_flight_number[]" class="form-control direct_flight_number" placeholder="Enter Flight Number">
                                                </div>
                                                <div class="col-xl-3" style="margin-top: 15px;">
                                                    <label for="">Departure Date and Time</label>
                                                    <input type="datetime-local" id="simpleinput" readonly name="departure_time[]" value="{{ $valueD->departure_time ?? '' }}" class="form-control departure_time1 dep_time" value="" >
                                                </div>
                                                <div class="col-xl-3" style="margin-top: 15px;">
                                                    <label for="">Arrival Date and Time</label>
                                                    <input type="datetime-local" readonly value="{{ $valueD->arrival_time ?? '' }}" id="simpleinput" name="arrival_time[]" class="form-control arrival_time1 arr_time" value="" >
                                                </div>
                                            </div>
                                            
                                            <div class="row" style="margin-left: 320px">
                                                <div class="col-sm-3">
                                                    <h3 style="width: 125px;margin-top: 25px;float:right" id="stop_No">
                                                        @if($valueD->departure_flight_route_type == 'Direct')
                                                            Duration :
                                                        @else
                                                            Stop # {{ $d_s }} :
                                                        @endif
                                                    </h3>
                                                </div>
                                                <div class="col-sm-3">
                                                     <label for="">Flight Duration</label>
                                                    <input type="text" id="total_Time" readonly value="{{ $valueD->total_Time ?? '' }}" name="total_Time[]" class="form-control total_Time1" readonly style="width: 170px;" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <?php $fD_count++; $d_s++ ?>
                                    @endif
                                @endforeach
                            @endif
                            
                            <!--Return-->
                            
                            @if(isset($get_invoice->return_flights_details) && $get_invoice->return_flights_details != null && $get_invoice->return_flights_details != '')
                                <?php
                                    $fR_count               = 1;
                                    $r_s                    = 1;
                                    $return_flights_details = json_decode($get_invoice->return_flights_details); 
                                ?>
                                @foreach($return_flights_details as $valueR)
                                    <div class="main_div_flight_return_{{ $fR_count }}" style="margin-top: 10px;">
                                        
                                        <input type="hidden" name="return_flight_route_type[]" value='{{ $valueR->return_flight_route_type ?? '' }}'>
                                        <input type="hidden" name="return_flight_route_id_occupied[]" value='{{ $valueR->return_flight_route_id_occupied ?? '' }}'>
                                        
                                        <h3 style="padding: 12px">Return Details : </h3>
                                        <div class="row" style="padding: 12px">
                                            <div class="col-xl-4">
                                                <label for="">Departure Airport</label>
                                                <input name="return_departure_airport_code[]" readonly id="return_departure_airport_code" value="{{ $valueR->return_departure_airport_code ?? '' }}" class="form-control" placeholder="Enter Departure Location">
                                            </div>
                                            <div class="col-xl-1" style="margin-top: 25px;text-align: center;">
                                                <label for=""></label>
                                                <span id="return_Change_Location" readonly class='bi bi-arrow-left-right' style="font-size: 23px;"></span>
                                            </div>
                                            <div class="col-xl-4">
                                                <label for="">Arrival Airport</label>
                                                <input name="return_arrival_airport_code[]" readonly value="{{ $valueR->return_arrival_airport_code ?? '' }}" id="return_arrival_airport_code" class="form-control" placeholder="Enter Arrival Location">
                                                
                                            </div>
                                            <div class="col-xl-3">
                                                <label for="">Airline Name</label>
                                                <input type="text" id="other_Airline_Name2" readonly value="{{ $valueR->return_other_Airline_Name2 ?? '' }}" name="return_other_Airline_Name2[]" class="form-control other_airline_Name1">
                                               
                                            </div>
                                            <div class="col-xl-3" style="margin-top: 15px;">
                                                <label for="">Class Type</label>
                                                <select  name="return_departure_Flight_Type[]" readonly id="return_departure_Flight_Type" class="form-control">
                                                    <option value="{{ $valueR->return_departure_Flight_Type ?? '' }}">{{ $valueR->return_departure_Flight_Type ?? '' }}</option>
                                                </select>
                                            </div>
                                            <div class="col-xl-3" style="margin-top: 15px;">
                                                <label for="">Flight Number</label>
                                                <input type="text" id="simpleinput" readonly value="{{ $valueR->return_departure_flight_number ?? '' }}" name="return_departure_flight_number[]" class="form-control direct_flight_number" placeholder="Enter Flight Number">
                                            </div>
                                            <div class="col-xl-3" style="margin-top: 15px;">
                                                <label for="">Departure Date and Time</label>
                                                <input type="datetime-local" id="simpleinput" readonly name="return_departure_time[]" value="{{ $valueR->return_departure_time ?? '' }}" class="form-control departure_time1 dep_time" value="" >
                                            </div>
                                            <div class="col-xl-3" style="margin-top: 15px;">
                                                <label for="">Arrival Date and Time</label>
                                                <input type="datetime-local" readonly value="{{ $valueR->return_arrival_time ?? '' }}" id="simpleinput" name="return_arrival_time[]" class="form-control arrival_time1 arr_time" value="" >
                                            </div>
                                        </div>
                                        
                                        <div class="row" style="margin-left: 320px">
                                            <div class="col-sm-3">
                                                <h3 style="width: 125px;margin-top: 25px;float:right" id="stop_No">
                                                    @if($valueD->departure_flight_route_type == 'Direct')
                                                        Duration :
                                                    @else
                                                        Stop # {{ $r_s }} :
                                                    @endif
                                                </h3>
                                            </div>
                                            <div class="col-sm-3">
                                                <label for="">Flight Duration</label>
                                                <input type="text" id="total_Time" readonly value="{{ $valueR->return_total_Time ?? '' }}" name="return_total_Time[]" class="form-control total_Time1" readonly style="width: 170px;" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <?php $fR_count++; $r_s++ ?>
                                @endforeach
                            @endif
                            
                            <!--Pax-->
                            
                            @if(isset($get_invoice->flights_Pax_details) && $get_invoice->flights_Pax_details != null && $get_invoice->flights_Pax_details != '')
                                <?php
                                    $f_count                = 1;
                                    $flights_Pax_details    = json_decode($get_invoice->flights_Pax_details);
                                ?>
                                @foreach($flights_Pax_details as $value)
                                    
                                    <?php $adult_price_all_seatsD = $value->flights_adult_seats + $value->flights_child_seats; ?>
                                
                                    <div class="row" id="flight_price_section_adult_{{ $f_count }}" style="margin-top:10px;">
                                        <h3>Adult Price Section</h3>
                                        
                                        <input type="hidden" name="flight_route_id_occupied_Pax[]" id="flight_route_id_occupied_{{ $f_count }}" value="{{ $value->flight_route_id_occupied_Pax ?? '' }}">
                                        
                                        <div class="col-xl-3">
                                            <label for="">Total Seats</label>
                                            <div class="input-group">
                                                <span class="input-group-btn input-group-append">
                                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                </span>
                                                <input type="text" id="flights_adult_seats_{{ $f_count }}" name="flights_adult_seats[]" class="form-control" value="{{ $value->flights_adult_seats ?? '' }}" onkeyup="flights_adult_seats_fun({{ $f_count }})">
                                            </div>
                                        </div>
                    
                                        <div class="col-xl-3">
                                            <label for="">Type</label>
                                            <div class="input-group">
                                                <span class="input-group-btn input-group-append">
                                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                </span>
                                                <input type="text" id="flights_adult_type_{{ $f_count }}" name="flights_adult_type[]" class="form-control" value="{{ $value->flights_adult_type ?? '' }}" readonly value="Adult">
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-3">
                                            <label for="">Cost Price/Seat</label>
                                            <div class="input-group">
                                                <span class="input-group-btn input-group-append">
                                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                </span>
                                                <input type="text" id="flights_cost_per_seats_adult_{{ $f_count }}" name="flights_cost_per_seats_adult[]" class="form-control" readonly value="{{ $value->flights_cost_per_seats_adult ?? '' }}">
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-3">
                                            <label for="">Total Cost Price</label>
                                            <div class="input-group">
                                                <span class="input-group-btn input-group-append">
                                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                </span>
                                                <input type="text" id="flights_total_cost_adult_{{ $f_count }}" name="flights_total_cost_adult[]" class="form-control" value="{{ $value->flights_total_cost_adult ?? '' }}" readonly>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-3" style="margin-top:10px;">
                                            <label for="">Markup Type</label>
                                            <select id="flights_markup_type_adult_{{ $f_count }}" name="flights_markup_type_adult[]" class="form-control" onchange="flights_markup_type_adult_fun({{ $f_count }})">
                                                <option <?php if($value->flights_markup_type_adult == '%') echo 'selected'?> value="%">Percentage</option>
                                                <option <?php if($value->flights_markup_type_adult == $currency) echo 'selected'?> value="<?php echo $currency; ?>">Fix Amount</option>
                                            </select>
                                        </div>
                    
                                        <div class="col-xl-3 adult_markup_prices_{{ $f_count }}" style="margin-top:10px;">
                                            <label for="">Markup</label>
                                            <div class="input-group">
                                                <span class="input-group-btn input-group-append">
                                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                </span>
                                                <input type="text" id="flights_markup_price_adult_{{ $f_count }}" name="flights_markup_price_adult[]" class="form-control" value="{{ $value->flights_markup_price_adult ?? '' }}" onkeyup="flights_markup_price_adult_fun({{ $f_count }})">
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-3 adult_markup_prices_{{ $f_count }}" style="margin-top:10px;">
                                            <label for="">Sale Price/Seat</label>
                                            <div class="input-group">
                                                <span class="input-group-btn input-group-append">
                                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                </span>
                                                <input type="text" id="flights_sale_price_per_seat_adult_{{ $f_count }}" name="flights_sale_price_per_seat_adult[]" class="form-control" value="{{ $value->flights_sale_price_per_seat_adult ?? '' }}" readonly>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-3 adult_markup_prices_{{ $f_count }}" style="margin-top:10px;">
                                            <label for="">Total Sale Price</label>
                                            <div class="input-group">
                                                <span class="input-group-btn input-group-append">
                                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                </span>
                                                <input type="text" id="flights_sale_price_adult_{{ $f_count }}" name="flights_sale_price_adult[]" class="form-control" value="{{ $value->flights_sale_price_adult ?? '' }}" readonly>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="row" id="flight_price_section_child_{{ $f_count }}" style="margin-top:10px;">
                                        <h3>Child Price Section</h3>
                                        
                                        <div class="col-xl-3">
                                            <label for="">Total Seats</label>
                                            <div class="input-group">
                                                <span class="input-group-btn input-group-append">
                                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                </span>
                                                <input type="text" id="flights_child_seats_{{ $f_count }}" name="flights_child_seats[]" class="form-control" value="{{ $value->flights_child_seats ?? '' }}" onkeyup="flights_child_seats_fun({{ $f_count }})">
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-3">
                                            <label for="">Type</label>
                                            <div class="input-group">
                                                <span class="input-group-btn input-group-append">
                                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                </span>
                                                <input type="text" id="flights_child_type_{{ $f_count }}" name="flights_child_type[]" class="form-control" value="{{ $value->flights_child_type ?? '' }}" readonly value="Child">
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-3">
                                            <label for="">Cost Price/Seat</label>
                                            <div class="input-group">
                                                <span class="input-group-btn input-group-append">
                                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                </span>
                                                <input type="text" id="flights_cost_per_seats_child_{{ $f_count }}" name="flights_cost_per_seats_child[]" class="form-control" value="{{ $value->flights_cost_per_seats_child ?? '' }}" readonly>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-3">
                                            <label for="">Total Cost Price</label>
                                            <div class="input-group">
                                                <span class="input-group-btn input-group-append">
                                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                </span>
                                                <input type="text" id="flights_total_cost_child_{{ $f_count }}" name="flights_total_cost_child[]" class="form-control" value="{{ $value->flights_total_cost_child ?? '' }}" readonly>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-3" style="margin-top:10px;">
                                            <label for="">Markup Type</label>
                                            <select id="flights_markup_type_child_{{ $f_count }}" name="flights_markup_type_child[]" class="form-control" onchange="flights_markup_type_child_fun({{ $f_count }})">
                                                <option <?php if($value->flights_markup_type_child == '%') echo 'selected'?> value="%">Percentage</option>
                                                <option <?php if($value->flights_markup_type_child == $currency) echo 'selected'?> value="<?php echo $currency; ?>">Fix Amount</option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-xl-3 child_markup_prices_{{ $f_count }}" style="margin-top:10px;">
                                            <label for="">Markup</label>
                                            <div class="input-group">
                                                <span class="input-group-btn input-group-append">
                                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                </span>
                                                <input type="text" id="flights_markup_price_child_{{ $f_count }}" name="flights_markup_price_child[]" class="form-control" onkeyup="flights_markup_price_child_fun({{ $f_count }})" value="{{ $value->flights_markup_price_child ?? '' }}">
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-3 child_markup_prices_{{ $f_count }}" style="margin-top:10px;">
                                            <label for="">Sale Price/Seat</label>
                                            <div class="input-group">
                                                <span class="input-group-btn input-group-append">
                                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                </span>
                                                <input type="text" id="flights_sale_price_per_seat_child_{{ $f_count }}" name="flights_sale_price_per_seat_child[]" class="form-control" value="{{ $value->flights_sale_price_per_seat_child ?? '' }}" readonly>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-3 child_markup_prices_{{ $f_count }}" style="margin-top:10px;">
                                            <label for="">Total Sale Price</label>
                                            <div class="input-group">
                                                <span class="input-group-btn input-group-append">
                                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                </span>
                                                <input type="text" id="flights_sale_price_child_{{ $f_count }}" name="flights_sale_price_child[]" class="form-control" value="{{ $value->flights_sale_price_child ?? '' }}" readonly>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="row" id="flight_price_section_infant_{{ $f_count }}" style="margin-top:10px;">
                                        <h3>Infant Price Section</h3>
                                        
                                        <div class="col-xl-3">
                                            <label for="">Total Infants</label>
                                            <div class="input-group">
                                                <span class="input-group-btn input-group-append">
                                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                </span>
                                                <input type="text" id="flights_infant_seats_{{ $f_count }}" name="flights_infant_seats[]" class="form-control" onkeyup="flights_infant_seats_fun({{ $f_count }})" value="{{ $value->flights_infant_seats ?? '' }}">
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-3">
                                            <label for="">Type</label>
                                            <div class="input-group">
                                                <span class="input-group-btn input-group-append">
                                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                </span>
                                                <input type="text" id="flights_infant_type_{{ $f_count }}" name="flights_infant_type[]" class="form-control" value="{{ $value->flights_infant_type ?? '' }}" readonly>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-3">
                                            <label for="">Cost Price/Seat</label>
                                            <div class="input-group">
                                                <span class="input-group-btn input-group-append">
                                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                </span>
                                                <input type="text" id="flights_cost_per_seats_infant_{{ $f_count }}" name="flights_cost_per_seats_infant[]" class="form-control" value="{{ $value->flights_cost_per_seats_infant ?? '' }}" readonly>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-3">
                                            <label for="">Total Cost Price</label>
                                            <div class="input-group">
                                                <span class="input-group-btn input-group-append">
                                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                </span>
                                                <input type="text" id="flights_total_cost_infant_{{ $f_count }}" name="flights_total_cost_infant[]" class="form-control" value="{{ $value->flights_total_cost_infant ?? '' }}" readonly>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-3" style="margin-top:10px;">
                                            <label for="">Markup Type</label>
                                            <select id="flights_markup_type_infant_{{ $f_count }}" name="flights_markup_type_infant[]" class="form-control" onchange="flights_markup_type_infant_fun({{ $f_count }})">
                                                <option <?php if($value->flights_markup_type_infant == '%') echo 'selected'?> value="%">Percentage</option>
                                                <option <?php if($value->flights_markup_type_infant == $currency) echo 'selected'?> value="<?php echo $currency; ?>">Fix Amount</option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-xl-3 infant_markup_prices_{{ $f_count }}" style="margin-top:10px;">
                                            <label for="">Markup</label>
                                            <div class="input-group">
                                                <span class="input-group-btn input-group-append">
                                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                </span>
                                                <input type="text" id="flights_markup_price_infant_{{ $f_count }}" name="flights_markup_price_infant[]" class="form-control" onkeyup="flights_markup_price_infant_fun({{ $f_count }})" value="{{ $value->flights_markup_price_infant ?? '' }}">
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-3 infant_markup_prices_{{ $f_count }}" style="margin-top:10px;">
                                            <label for="">Sale Price/Seat</label>
                                            <div class="input-group">
                                                <span class="input-group-btn input-group-append">
                                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                </span>
                                                <input type="text" id="flights_sale_price_per_seat_infant_{{ $f_count }}" name="flights_sale_price_per_seat_infant[]" class="form-control" value="{{ $value->flights_sale_price_per_seat_infant ?? '' }}" readonly>
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-3 infant_markup_prices_{{ $f_count }}" style="margin-top:10px;">
                                            <label for="">Total Sale Price</label>
                                            <div class="input-group">
                                                <span class="input-group-btn input-group-append">
                                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                </span>
                                                <input type="text" id="flights_sale_price_infant_{{ $f_count }}" name="flights_sale_price_infant[]" class="form-control" value="{{ $value->flights_sale_price_infant ?? '' }}" readonly>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    
                                    <input type="hidden" id="flight_occupied_seats_single_{{ $f_count }}" value="{{ $adult_price_all_seatsD }}">
                                    
                                    <?php $f_count++;?>   
                                @endforeach
                                <input type="hidden" id="c_count_id" value="{{ $f_count }}">
                            @endif
                            
                        </div>
                        
                        <input type="hidden" id="all_departure_appends" value="0">
                        <input type="hidden" id="all_return_appends" value="0">
                        <input type="hidden" id="routes_count_id">
                        
                        <div class="col-xl-12 mt-3" style="display:none" id="text_editer">
                            <label for="">Indirect Flight Duration and Details</label>
                            <textarea name="connected_flights_duration_details" class="form-control indirect_term_and_condition" cols="10" rows="10"></textarea>
                        </div>
                        
                        <div class="col-xl-12 mt-2" style="display:none">
                            <label for="">Additional Flight Details</label>
                            <textarea name="terms_and_conditions" class="form-control direct_term_and_condition" cols="5" rows="5"></textarea>
                        </div>
                        
                        <div class="col-xl-12 mt-2" style="display:none">
                            <label for="">image</label>
                            <input type="file" id="" name="flights_image" class="form-control">
                        </div>
                        
                        <div class="col-xl-12 mt-2" style="display:none" id="manual_flight_div">
                            <span title="Add Flight Seats" class="input-group-btn input-group-append">
                                <button class="btn btn-primary bootstrap-touchspin-up" data-bs-toggle="modal" data-bs-target="#add-flight-seats-modal" type="button">Add Seats</button>
                            </span>
                        </div>
                       
                    </div>
                 
                    <div class="tab-pane d-none" id="visa_details">
                        
                        <div class="row">                                                  
                            <div class="col-xl-12 mt-2">
                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-xl-1">
                                            <input type="checkbox" id="visa_inc" data-switch="bool"/>
                                            <label for="visa_inc" data-on-label="On" data-off-label="Off"></label>
                                        </div>
                                        <div class="col-xl-3">
                                            Visa Included ?
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Add visa price & requirement"></i>
                                        </div>
                                     </div>
                                </div>
                            </div>
                            
                            <div class="row mt-1 mb-3" style="" id="select_visa_inc">
                                
                                <div class="col-xl-4" style="padding: 10px;">
                                    <label for="">Visa Type</label>
                                    <div class="input-group" id="timepicker-input-group1">
                                        @if(isset($get_invoice->visa_type) && $get_invoice->visa_type != null && $get_invoice->visa_type != '')
                                            <select name="visa_type" id="visa_type" class="form-control other_type">
                                                @foreach($visa_type as $value)
                                                    <option <?php if($get_invoice->visa_type == $value->other_visa_type) echo "selected" ?> attr="{{ $value->other_visa_type }}" value="{{ $value->other_visa_type }}">{{ $value->other_visa_type }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <select name="visa_type" id="visa_type" class="form-control other_type"></select>
                                        @endif
                                        <span title="Add Visa Type" class="input-group-btn input-group-append"><button class="btn btn-primary bootstrap-touchspin-up" data-bs-toggle="modal" data-bs-target="#signup-modal" type="button">+</button></span>
                                    </div>
                                 </div>
                                
                                <div class="col-xl-4" style="padding: 10px;">
                                    <label for="">Visa Fee</label>
                                    <div class="input-group">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up visa_C_S">
                                                <?php echo $currency; ?>
                                            </a>
                                        </span>
                                        @if(isset($get_invoice->visa_fee) && $get_invoice->visa_fee != null && $get_invoice->visa_fee != '')
                                            <input type="text" id="visa_fee" name="visa_fee" class="form-control" value="{{ $get_invoice->visa_fee }}">
                                        @else
                                            <input type="text" id="visa_fee" name="visa_fee" class="form-control">
                                        @endif
                                    </div>
                                </div>
                                
                                
                                @if(isset($get_invoice->total_visa_markup_value) && $get_invoice->total_visa_markup_value != null && $get_invoice->total_visa_markup_value != '')
                                    <input type="hidden" id="total_visa_markup_value" name="total_visa_markup_value" class="form-control" value="{{ $get_invoice->total_visa_markup_value }}">
                                @else
                                    <input type="hidden" id="total_visa_markup_value" name="total_visa_markup_value" class="form-control">
                                @endif
                                
                                <div class="col-xl-4" style="padding: 10px;">
                                    <label for="">Visa Pax</label>
                                    @if(isset($get_invoice->visa_Pax) && $get_invoice->visa_Pax != null && $get_invoice->visa_Pax != '')
                                        <input type="text" id="visa_Pax" name="visa_Pax" class="form-control" value="{{ $get_invoice->visa_Pax }}">
                                    @else
                                        <input type="text" id="visa_Pax" name="visa_Pax" class="form-control">
                                    @endif
                                </div>
                                <?php //dd($get_invoice->currency_conversion); ?>
                                @if(isset($get_invoice->currency_conversion) && $get_invoice->currency_conversion != null && $get_invoice->currency_conversion != '')
                                    <div class="col-xl-6" style="padding: 10px;">
                                        <label for="">Exchange Rate</label>
                                        <div class="input-group">
                                            <span class="input-group-btn input-group-append">
                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                                    
                                                </a>
                                            </span>
                                            <input type="text" id="exchange_rate_visaI" onkeyup="exchange_rate_visaI_function()" name="exchange_rate_visaI" class="form-control" value="{{ $get_invoice->exchange_rate_visaI ?? '' }}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-6" style="padding: 10px;">
                                        <label for="">Exchange Visa Fee</label>
                                        <div class="input-group">
                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a></span>
                                            <input type="text" id="exchange_rate_visa_total_amountI" name="exchange_rate_visa_fee" class="form-control" value="{{ $get_invoice->exchange_rate_visa_fee ?? '' }}">
                                        </div>
                                    </div>
                                @endif
                                
                                @if(isset($get_invoice->more_visa_details) && $get_invoice->more_visa_details != null && $get_invoice->more_visa_details != '')
                                    <div id="add_more_visa_Pax_Div">
                                        <?php $more_visa_details = json_decode($get_invoice->more_visa_details); $m_VD_count = 1; //dd($more_visa_details);?>
                                        @foreach($more_visa_details as $value)
                                            <div id="more_visa_pax_div_{{$m_VD_count}}" class="row">
                                                <div class="col-xl-4" style="padding: 10px;">
                                                    <label for="">Visa Type</label>
                                                    @if(isset($get_invoice->visa_type) && $get_invoice->visa_type != null && $get_invoice->visa_type != '')
                                                        <select name="more_visa_type[]" id="more_visa_type_{{$m_VD_count}}" class="form-control more_visa_type_class" onchange="more_visa_type_select({{$m_VD_count}})">
                                                            @foreach($visa_type as $value1)
                                                                <option <?php if($value->more_visa_type == $value1->other_visa_type) echo "selected" ?> attr="{{ $value1->other_visa_type }}" value="{{ $value1->other_visa_type }}">{{ $value1->other_visa_type }}</option>
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        <select name="more_visa_type[]" id="more_visa_type_{{$m_VD_count}}" class="form-control more_visa_type_class" onchange="more_visa_type_select({{$m_VD_count}})"></select>
                                                    @endif
                                                 </div>
                                            
                                                <div class="col-xl-4" style="padding: 10px;">
                                                    <label for="">Visa Fee</label>
                                                    <div class="input-group">
                                                        <span class="input-group-btn input-group-append">
                                                            <a class="btn btn-primary bootstrap-touchspin-up more_visa_C_S">
                                                                <?php echo $currency; ?>
                                                            </a>
                                                        </span>
                                                        <input type="text" id="more_visa_fee_{{$m_VD_count}}" name="more_visa_fee[]" value="{{ $value->more_visa_fee }}" class="form-control" onchange="more_visa_fee_calculation({{$m_VD_count}})">
                                                    </div>
                                                </div>
                                                <input type="hidden" id="more_total_visa_markup_value_{{$m_VD_count}}" name="more_total_visa_markup_value[]" value="{{ $value->more_total_visa_markup_value }}" class="form-control">
                                                <div class="col-xl-3" style="padding: 10px;">
                                                    <label for="">Visa Pax</label>
                                                    <input type="text" id="more_visa_Pax_{{$m_VD_count}}" name="more_visa_Pax[]" value="{{ $value->more_visa_Pax }}" class="form-control">
                                                </div>
                                                <div class="col-xl-1" style="margin-top: 30px;">
                                                    <button type="button" onclick="remove_more_visa_pax({{$m_VD_count}})" class="btn btn-primary">Remove</button>
                                                </div>
                                                 @if(isset($get_invoice->currency_conversion) && $get_invoice->currency_conversion != null && $get_invoice->currency_conversion != '')
                                                    <div class="col-xl-6" style="padding: 10px;">
                                                        <label for="">Exchange Rate</label>
                                                        <div class="input-group">
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                                                    
                                                                </a>
                                                            </span>
                                                            <input type="text" id="more_exchange_rate_visaI_{{$m_VD_count}}" onkeyup="more_exchange_rate_visaI_function({{$m_VD_count}})" name="more_exchange_rate_visaI" class="form-control" value="{{ $value->more_exchange_rate_visa ?? '' }}">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-xl-6" style="padding: 10px;">
                                                        <label for="">Exchange Visa Fee</label>
                                                        <div class="input-group">
                                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a></span>
                                                            <input type="text" id="more_exchange_rate_visa_total_amountI_{{$m_VD_count}}" name="more_exchange_rate_visa_fee[]" class="form-control" value="{{ $value->more_exchange_rate_visa_fee ?? '' }}">
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <?php $m_VD_count++ ?>
                                        @endforeach
                                        <input type="hidden" value="{{ $m_VD_count }}" id="m_VD_count">
                                    </div>
                                @else
                                    <input type="hidden" value="1" id="m_VD_count">
                                    <div id="add_more_visa_Pax_Div"></div>
                                @endif
                                
                                <div class="col-xl-12" style="padding: 10px;text-align: right;">
                                    <button type="button" id="add_more_visa_Pax" class="btn btn-primary">Add more</button>
                                </div>
                                
                                <div class="col-xl-12" style="padding: 10px;">
                                    <label for="">Visa Requirements:</label>
                                    @if(isset($get_invoice->visa_rules_regulations) && $get_invoice->visa_rules_regulations != null && $get_invoice->visa_rules_regulations != '')
                                        <textarea name="visa_rules_regulations" class="form-control" cols="5" rows="5" value="{{ $get_invoice->visa_rules_regulations }}">{{ $get_invoice->visa_rules_regulations }}</textarea>
                                    @else
                                        <textarea name="visa_rules_regulations" class="form-control" cols="5" rows="5"></textarea>
                                    @endif
                                 </div>
                                 
                                <div class="col-xl-12" style="padding: 10px;">
                                    <label for="">Image:</label>
                                    @if(isset($get_invoice->visa_image) && $get_invoice->visa_image != null && $get_invoice->visa_image != '')
                                        <img src="{{ asset('/public/uploads/package_imgs/'.$get_invoice->visa_image) }}" style="width:300px;" /> 
                                    @else
                                        <input type="file" id="" name="visa_image" class="form-control">
                                    @endif
                                 </div>
                            </div>
                            
                        </div>
                        <!--<a id="save_Visa" class="btn btn-primary" name="submit">Save Visa</a>-->
                        
                    </div> 
                    
                    <div class="tab-pane d-none" id="trans_details_1">
                        <div class="row">                                       
                            <div class="tab-pane" id="trans_details_1">
                                
                                <div class="col-xl-12 mt-2 d-none">
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-xl-1">
                                                <input type="checkbox" id="transportation" data-switch="bool"/>
                                                <label for="transportation" data-on-label="On" data-off-label="Off"></label>
                                            </div>
                                            <div class="col-xl-3">
                                                Transportation
                                                <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Add your transportation details"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-xl-3 mt-2" style="padding: 10px;">
                                    <label for="">Transfer Suppliers List</label>
                                    <select name="transfer_supplier" class="form-control" id="transfer_supplier" onchange="transfer_supplier_function_edit()">
                                        <option attr-id="-1" value="">Select Suppliers</option>
                                        @foreach($tranfer_supplier as $tranfer_supplierS)
                                            <option <?php if($get_invoice->transfer_supplier_id == $tranfer_supplierS->id) echo 'selected' ?> attr-id="{{ $tranfer_supplierS->id }}" value="{{ $tranfer_supplierS->room_supplier_name }}">{{ $tranfer_supplierS->room_supplier_name }}</option>
                                        @endforeach
                                    </select>
                                    
                                    <input type="hidden" name="transfer_supplier_id" id="transfer_supplier_selected_id" value="{{ $get_invoice->transfer_supplier_id ?? '' }}">
                                    
                                </div>
                                
                                <div class="row" id="transfer_supplier_list_div" style="display:none">
                                    <div class="dashboard-content">
                                        <h3 style="color:#a30000;font-size: 40px;text-align:center">Vehicle List</h3>
                                        <div class="row">
                                            <div class="col-lg-12 col-sm-12">
                                                <div class="dashboard-list-box dash-list margin-top-0">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <table id="myTable" class="display nowrap table table-bordered" style="width:100%;">
                                                                <thead class="theme-bg-clr">
                                                                    <tr>
                                                                        <th>Id</th>
                                                                        <th class="d-none">Company Name</th>
                                                                        <th>Pickup City</th>
                                                                        <th>Dropoff City</th>
                                                                        <th>Available From</th>
                                                                        <th>Available To</th>
                                                                        <th>Trip Type</th>
                                                                        <th>Total Fare</th>
                                                                        <th>Occupy</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody style="text-align: center;" id="transfer_supplier_list_body"></tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                @if(isset($get_invoice->services) && $get_invoice->services != null && $get_invoice->services != '')
                                    <?php $services = json_decode($get_invoice->services);?>
                                    @if($services[0] == '1' || $services[0] == 'transportation_tab')
                                    
                                        @if(isset($get_invoice->transportation_details) && $get_invoice->transportation_details != null && $get_invoice->transportation_details != '')
                                            <?php   
                                                $transportation_details_D       = json_decode($get_invoice->transportation_details);
                                                $transportation_details_more_D  = json_decode($get_invoice->transportation_details_more);
                                                $MTD_id                         = 1;
                                                $TD_id1                         = 1;
                                                // dd($transportation_details_D);
                                            ?>
                                            <div class="row" style="border:1px solid #ced4da;padding: 20px 20px 20px 20px;" id="select_transportation">
                                                @if(isset($transportation_details_D) && $transportation_details_D != null && $transportation_details_D != '')
                                                    @foreach($transportation_details_D as $transportation_details_DS)
                                                        @if($transportation_details_DS->transportation_trip_type != null && $transportation_details_DS->transportation_trip_type != '' && $transportation_details_DS->transportation_trip_type != 'All_Round')
                                                            
                                                            <input type="hidden" name="all_round_Type[]" value="{{ $transportation_details_DS->all_round_Type ?? '' }}">
                                                            
                                                            <input type="hidden" name="destination_id[]" id="destination_id" value="{{ $transportation_details_DS->destination_id }}">
                                                            
                                                            <input type="hidden" name="vehicle_select_exchange_type[]" id="vehicle_select_exchange_type_ID" value="{{ $transportation_details_DS->vehicle_select_exchange_type ?? '' }}">
                                    
                                                            <input type="hidden" name="vehicle_exchange_Rate[]" id="vehicle_exchange_Rate_ID" value="{{ $transportation_details_DS->vehicle_exchange_Rate ?? '' }}">
                                                            
                                                            <input type="hidden" name="currency_SAR[]" id="currency_SAR" value="{{ $transportation_details_DS->currency_SAR ?? '' }}">
                                                        
                                                            <input type="hidden" name="currency_GBP[]" id="currency_GBP" value="{{ $transportation_details_DS->currency_GBP ?? '' }}">
                                                            
                                                            <input type="hidden" id="currency_GBP_for_costing" value="{{ $transportation_details_DS->currency_GBP ?? '' }}">
                                                            
                                                            <h3>Transportation Details :</h3>
                                                            <div class="col-xl-3" style="padding: 10px;">
                                                                <label for="">Pick-up Location</label>
                                                                <input type="text" value="{{ $transportation_details_DS->transportation_pick_up_location ?? '' }}" id="transportation_pick_up_location" name="transportation_pick_up_location[]" class="form-control pickup_location all_one_way_empty_class" value="" onkeyup="TPUL_function({{ $TD_id1 }})">
                                                            </div>
                                                            
                                                            <div class="col-xl-3" style="padding: 10px;">
                                                                <label for="">Drop-off Location</label>
                                                                <input type="text" value="{{ $transportation_details_DS->transportation_drop_off_location ?? '' }}" id="transportation_drop_off_location" name="transportation_drop_off_location[]" class="form-control dropof_location all_one_way_empty_class" onkeyup="TDOL_function({{ $TD_id1 }})">
                                                            </div>
                                                            
                                                            <div class="col-xl-3" style="padding: 10px;">
                                                                <label for="">Pick-up Date & Time</label>
                                                                <input type="datetime-local" value="{{ $transportation_details_DS->transportation_pick_up_date ?? '' }}" id="transportation_pick_up_date" name="transportation_pick_up_date[]" class="form-control all_one_way_empty_class" onchange="TPUD_function({{ $TD_id1 }})">
                                                            </div>
                                                            
                                                            <div class="col-xl-3" style="padding: 10px;">
                                                                <label for="">Drop-of Date & Time</label>
                                                                <input type="datetime-local" value="{{ $transportation_details_DS->transportation_drop_of_date ?? '' }}" id="transportation_drop_of_date" name="transportation_drop_of_date[]" class="form-control all_one_way_empty_class" onchange="TDOP_function({{ $TD_id1 }})">
                                                            </div>
                                                            
                                                            <div class="col-xl-3" style="padding: 10px;" id="transportation_Time_Div">
                                                                <label for="">Estimate Time</label>
                                                                <input type="text" value="{{ $transportation_details_DS->transportation_total_Time ?? '' }}" id="transportation_total_Time" name="transportation_total_Time[]" class="form-control transportation_total_Time1 all_one_way_empty_class" readonly style="padding: 10px;" value="">
                                                            </div>
                                                            
                                                            <div class="col-xl-3" style="padding: 10px;">
                                                                <label for="">Select Trip Type</label>
                                                                <select name="transportation_trip_type[]" id="slect_trip" class="form-control" readonly>
                                                                    <option value="{{ $transportation_details_DS->transportation_trip_type ?? '' }}">{{ $transportation_details_DS->transportation_trip_type ?? '' }}</option>
                                                                </select>
                                                            </div>
                                                            
                                                            <div class="col-xl-3" style="padding: 10px;">
                                                                <label for="">Vehicle Type</label>
                                                                <select name="transportation_vehicle_type[]" id="transportation_vehicle_typeI" class="form-control">
                                                                    <option selected value="{{ $transportation_details_DS->transportation_vehicle_type ?? '' }}">{{ $transportation_details_DS->transportation_vehicle_type ?? '' }}</option>
                                                                </select>
                                                            </div>
                                                            
                                                            <div class="col-xl-3" style="padding: 10px;">
                                                                <label for="">No Of Vehicle</label>
                                                                <input type="text" value="{{ $transportation_details_DS->transportation_no_of_vehicle ?? '' }}" id="transportation_no_of_vehicle" name="transportation_no_of_vehicle[]" class="form-control all_one_way_empty_class">
                                                            </div>
                                                            
                                                            <div class="col-xl-3" style="padding: 10px;">
                                                                <label for="">Price Per Vehicle</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1_T">{{ $transportation_details_DS->currency_SAR ?? '' }}</a></span>
                                                                    <input type="text" value="{{ $transportation_details_DS->transportation_price_per_vehicle ?? '' }}" id="transportation_price_per_vehicle" name="transportation_price_per_vehicle[]" class="form-control all_one_way_empty_class" readonly>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-xl-3" style="padding: 10px;">
                                                                <label for="">Total Vehicle Price</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1_T">{{ $transportation_details_DS->currency_SAR ?? '' }}</a></span>
                                                                    <input type="text" value="{{ $transportation_details_DS->transportation_vehicle_total_price ?? '' }}" id="transportation_vehicle_total_price" name="transportation_vehicle_total_price[]" class="form-control all_one_way_empty_class" readonly>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-xl-3 d-none" style="padding: 10px;">
                                                                <label for="">Price Per Person</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1_T">{{ $transportation_details_DS->currency_SAR ?? '' }}</a></span>
                                                                    <input type="text" value="{{ $transportation_details_DS->transportation_price_per_person ?? '' }}" id="transportation_price_per_person" name="transportation_price_per_person[]" class="form-control all_one_way_empty_class" readonly>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-xl-3" style="padding: 10px;">
                                                                <label for="">Exchange Rate</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1_T">{{ $transportation_details_DS->currency_SAR ?? '' }}</a></span>
                                                                    <input type="text" value="{{ $transportation_details_DS->transfer_exchange_rate_destination ?? '' }}" name="transfer_exchange_rate_destination[]" id="transfer_exchange_rate_destination" class="form-control all_one_way_empty_class" readonly>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-xl-3" style="padding: 10px;">
                                                                <label for="">Price Per Vehicle</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1_T">{{ $transportation_details_DS->currency_GBP ?? '' }}</a></span>
                                                                    <input type="text" value="{{ $transportation_details_DS->without_markup_price_converted_destination ?? '' }}" name="without_markup_price_converted_destination[]" id="without_markup_price_converted_destination" class="form-control all_one_way_empty_class" readonly>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-xl-3" style="padding: 10px;">
                                                                <label for="">Total Vehicle Price</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1_T">{{ $transportation_details_DS->currency_GBP ?? '' }}</a></span>
                                                                    <input type="text" value="{{ $transportation_details_DS->transportation_vehicle_total_price_converted ?? '' }}" name="transportation_vehicle_total_price_converted[]" id="transportation_vehicle_total_price_converted" class="form-control all_one_way_empty_class" readonly>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-xl-3 d-none" style="padding: 10px;">
                                                                <label for="">Price Per Person</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1_T">{{ $transportation_details_DS->currency_GBP ?? '' }}</a></span>
                                                                    <input type="text" value="{{ $transportation_details_DS->transportation_price_per_person_converted ?? '' }}" name="transportation_price_per_person_converted[]" id="transportation_price_per_person_converted" class="form-control all_one_way_empty_class" readonly>
                                                                </div>
                                                            </div>
                                                            
                                                            <!--Costing-->
                                                            <div class="col-xl-3" style="padding: 10px;">
                                                                <label for="">Vehicle Markup Type</label>
                                                                <select name="vehicle_markup_type[]" id="vehicle_markup_type" class="form-control all_one_way_empty_class" onchange="vehicle_markup_OWandR()">
                                                                    <option <?php if($transportation_details_DS->vehicle_markup_type ?? ''  == '') echo 'Selected' ?> value="">Markup Type</option>
                                                                    <option <?php if($transportation_details_DS->vehicle_markup_type ?? ''  == '%') echo 'Selected' ?> value="%">Percentage</option>
                                                                    <option <?php if($transportation_details_DS->vehicle_markup_type ?? ''  == $currency) echo 'Selected' ?> value="<?php echo $currency; ?>">Fixed Amount</option>
                                                                </select>
                                                            </div>
                                                            
                                                            <div class="col-xl-3" style="padding: 10px;">
                                                                <label for="">Markup Value per Vehicle</label>
                                                                <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                    <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1_T">{{ $transportation_details_DS->currency_GBP ?? '' }}</a></span>
                                                                    <input value="{{ $transportation_details_DS->vehicle_per_markup_value ?? '' }}" type="text" class="form-control all_one_way_empty_class" id="vehicle_per_markup_value" name="vehicle_per_markup_value[]" onkeyup="vehicle_per_markup_OWandR()">
                                                                </div>
                                                            </div>
                                                            
                                                            <input value="{{ $transportation_details_DS->markup_price_per_vehicle_converted ?? '' }}" type="text" class="form-control d-none all_one_way_empty_class" id="markup_price_per_vehicle_converted" name="markup_price_per_vehicle_converted[]" readonly>
                                                            
                                                            <div class="col-xl-3" style="padding: 10px;">
                                                                <label for="">Vehicle Markup Value</label>
                                                                <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                    <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1_T">{{ $transportation_details_DS->currency_SAR ?? '' }}</a></span>
                                                                    <input value="{{ $transportation_details_DS->vehicle_markup_value ?? '' }}" type="text" class="form-control" id="vehicle_markup_value" name="vehicle_markup_value[]" onkeyup="vehicle_markup_OWandR1()" readonly>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-xl-3" style="padding: 10px;">
                                                                <label for="">Vehicle Total Price</label>
                                                                <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                    <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1_T">{{ $transportation_details_DS->currency_SAR ?? '' }}</a></span>
                                                                    <input value="{{ $transportation_details_DS->vehicle_total_price_with_markup ?? '' }}" type="text" class="form-control all_one_way_empty_class" id="vehicle_total_price_with_markup" name="vehicle_total_price_with_markup[]" readonly>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-xl-3" style="padding: 10px;">
                                                                <label for="">Vehicle Markup Value</label>
                                                                <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                    <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1_T">{{ $transportation_details_DS->currency_GBP ?? '' }}</a></span>
                                                                    <input value="{{ $transportation_details_DS->vehicle_markup_value_converted ?? '' }}" type="text" class="form-control all_one_way_empty_class" id="vehicle_markup_value_converted" name="vehicle_markup_value_converted[]" readonly>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-xl-3" style="padding: 10px;">
                                                                <label for="">Vehicle Markup Price</label>
                                                                <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                    <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1_T">{{ $transportation_details_DS->currency_GBP ?? '' }}</a></span>
                                                                    <input value="{{ $transportation_details_DS->vehicle_total_price_with_markup_converted ?? '' }}" type="text" class="form-control all_one_way_empty_class" id="vehicle_total_price_with_markup_converted" name="vehicle_total_price_with_markup_converted[]" readonly>
                                                                </div>
                                                            </div>
                                                            <!--End Costing-->
                                                            
                                                            <input type="hidden" class="transfer_markup_type_invoice" value="{{ $transportation_details_DS->transfer_markup_type_invoice ?? '' }}" name="transfer_markup_type_invoice[]">
                                                            <input type="hidden" class="transfer_markup_invoice" value="{{ $transportation_details_DS->transfer_markup_invoice ?? '' }}" name="transfer_markup_invoice[]">
                                                            <input type="hidden" class="transfer_markup_price_invoice" value="{{ $transportation_details_DS->transfer_markup_price_invoice ?? '' }}" name="transfer_markup_price_invoice[]">
                                                    
                                                            <!--Return Transportation-->
                                                            @if($transportation_details_DS->transportation_trip_type != 'Return')
                                                                <div class="row" id="select_return" style="display:none;padding: 10px;">
                                                                    <h3>Return Details :</h3>
                                                                    <div class="col-xl-3" style="padding: 10px;">
                                                                        <label for="">Pick-up Location</label>
                                                                        <input type="text" id="return_transportation_pick_up_location" name="return_transportation_pick_up_location" class="form-control pickup_location">
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3" style="padding: 10px;">
                                                                        <label for="">Drop-off Location</label>
                                                                        <input type="text" id="return_transportation_drop_off_location" name="return_transportation_drop_off_location" class="form-control dropof_location">
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3" style="padding: 10px;">
                                                                        <label for="">Pick-up Date & Time</label>
                                                                        <input type="datetime-local" id="return_transportation_pick_up_date" name="return_transportation_pick_up_date" class="form-control">
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3" style="padding: 10px;">
                                                                        <label for="">Drop-of Date & Time</label>
                                                                        <input type="datetime-local" id="return_transportation_drop_of_date" name="return_transportation_drop_of_date" class="form-control">
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3" style="padding: 10px;" id="return_transportation_Time_Div">
                                                                        <label for="" style="width: 205px;">Estimate Time Return</label>
                                                                        <input type="text" id="return_transportation_total_Time" name="return_transportation_total_Time" class="form-control return_transportation_total_Time1" readonly style="padding: 10px;" value="">
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3" style="display:none;padding: 10px;">
                                                                        <label for="">Vehicle Type</label>
                                                                        <select name="return_transportation_vehicle_type" id="" class="form-control"  data-placeholder="Choose ...">
                                                                            <option value="">Choose ...</option>
                                                                            <option value="Bus">Bus</option>
                                                                            <option value="Coach">Coach</option>
                                                                            <option value="Vain">Vain</option>
                                                                            <option value="Car">Car</option>
                                                                        </select>
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3" style="display:none;padding: 10px;">
                                                                        <label for="">No Of Vehicle</label>
                                                                        <input type="text" id="return_transportation_no_of_vehicle" name="return_transportation_no_of_vehicle" class="form-control">
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3" style="display:none;padding: 10px;">
                                                                        <label for="">Price Per Vehicle</label>
                                                                        <div class="input-group">
                                                                                    <span class="input-group-btn input-group-append">
                                                                                        <a class="btn btn-primary bootstrap-touchspin-up">
                                                                                           <?php echo $currency; ?>
                                                                                        </a>
                                                                                    </span>
                                                                            <input type="text" id="return_transportation_price_per_vehicle" name="return_transportation_price_per_vehicle" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3" style="display:none;padding: 10px;">
                                                                        <label for="">Total Vehicle Price</label>
                                                                        <div class="input-group">
                                                                                    <span class="input-group-btn input-group-append">
                                                                                        <a class="btn btn-primary bootstrap-touchspin-up">
                                                                                           <?php echo $currency; ?>
                                                                                        </a>
                                                                                    </span>
                                                                            <input type="text" id="return_transportation_vehicle_total_price" name="return_transportation_vehicle_total_price" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3" style="display:none;padding: 10px;">
                                                                        <label for="">Price Per Person</label>
                                                                            <div class="input-group">
                                                                                        <span class="input-group-btn input-group-append">
                                                                                            <a class="btn btn-primary bootstrap-touchspin-up">
                                                                                               <?php echo $currency; ?>
                                                                                            </a>
                                                                                        </span>
                                                                                <input type="text" id="return_transportation_price_per_person" name="return_transportation_price_per_person" class="form-control">
                                                                            </div>
                                                                        </div>
                                                                </div>
                                                            @else
                                                                <div class="row" id="select_return" style="padding: 10px;">
                                                                    <h3>Return Details :</h3>
                                                                    <div class="col-xl-3" style="padding: 10px;">
                                                                        <label for="">Pick-up Location</label>
                                                                        <input type="text" value="{{ $transportation_details_DS->return_transportation_pick_up_location ?? '' }}" id="return_transportation_pick_up_location" name="return_transportation_pick_up_location" class="form-control pickup_location">
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3" style="padding: 10px;">
                                                                        <label for="">Drop-off Location</label>
                                                                        <input type="text" value="{{ $transportation_details_DS->return_transportation_drop_off_location ?? '' }}" id="return_transportation_drop_off_location" name="return_transportation_drop_off_location" class="form-control dropof_location">
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3" style="padding: 10px;">
                                                                        <label for="">Pick-up Date & Time</label>
                                                                        <input type="datetime-local" value="{{ $transportation_details_DS->return_transportation_pick_up_date ?? '' }}" id="return_transportation_pick_up_date" name="return_transportation_pick_up_date" class="form-control">
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3" style="padding: 10px;">
                                                                        <label for="">Drop-of Date & Time</label>
                                                                        <input type="datetime-local" value="{{ $transportation_details_DS->return_transportation_drop_of_date ?? '' }}" id="return_transportation_drop_of_date" name="return_transportation_drop_of_date" class="form-control">
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3" style="padding: 10px;" id="return_transportation_Time_Div_{{ $TD_id1 }}">
                                                                        <label for="" style="width: 205px;">Estimate Time Return</label>
                                                                        <input type="text" value="{{ $transportation_details_DS->return_transportation_total_Time ?? '' }}" id="return_transportation_total_Time" name="return_transportation_total_Time" class="form-control return_transportation_total_Time1" readonly style="padding: 10px;">
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3" style="display:none;padding: 10px;">
                                                                        <label for="">Vehicle Type</label>
                                                                        <select name="return_transportation_vehicle_type" id="" class="form-control"  data-placeholder="Choose ...">
                                                                            <option value="">Choose ...</option>
                                                                            <option value="Bus">Bus</option>
                                                                            <option value="Coach">Coach</option>
                                                                            <option value="Vain">Vain</option>
                                                                            <option value="Car">Car</option>
                                                                        </select>
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3" style="display:none;padding: 10px;">
                                                                        <label for="">No Of Vehicle</label>
                                                                        <input type="text" id="return_transportation_no_of_vehicle" name="return_transportation_no_of_vehicle" class="form-control">
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3" style="display:none;padding: 10px;">
                                                                        <label for="">Price Per Vehicle</label>
                                                                        <div class="input-group">
                                                                                    <span class="input-group-btn input-group-append">
                                                                                        <a class="btn btn-primary bootstrap-touchspin-up">
                                                                                           <?php echo $currency; ?>
                                                                                        </a>
                                                                                    </span>
                                                                            <input type="text" id="return_transportation_price_per_vehicle" name="return_transportation_price_per_vehicle" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3" style="display:none;padding: 10px;">
                                                                        <label for="">Total Vehicle Price</label>
                                                                        <div class="input-group">
                                                                                    <span class="input-group-btn input-group-append">
                                                                                        <a class="btn btn-primary bootstrap-touchspin-up">
                                                                                           <?php echo $currency; ?>
                                                                                        </a>
                                                                                    </span>
                                                                            <input type="text" id="return_transportation_vehicle_total_price" name="return_transportation_vehicle_total_price" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3" style="display:none;padding: 10px;">
                                                                        <label for="">Price Per Person</label>
                                                                            <div class="input-group">
                                                                                        <span class="input-group-btn input-group-append">
                                                                                            <a class="btn btn-primary bootstrap-touchspin-up">
                                                                                               <?php echo $currency; ?>
                                                                                            </a>
                                                                                        </span>
                                                                                <input type="text" id="return_transportation_price_per_person" name="return_transportation_price_per_person" class="form-control">
                                                                            </div>
                                                                        </div>
                                                                </div>
                                                            @endif
                                                            
                                                            <div id="append_transportation"></div>
                                                        
                                                            <div class="mt-2" style="display:none;" id="add_more_destination">
                                                                <a href="javascript:;" id="more_transportationI" class="btn btn-info" style="float: right;">Add More Destinations </a>
                                                            </div>
                                                            
                                                        @else
                                                            @if($transportation_details_DS->transportation_trip_type != null && $transportation_details_DS->transportation_trip_type != '')
                                                                    
                                                                <div class="row" id="allRound_Div_{{ $TD_id1 }}">
                                                                    
                                                                    <input type="hidden" name="all_round_Type[]" value="{{ $transportation_details_DS->all_round_Type ?? '' }}">
                                                                    
                                                                    <input type="hidden" name="destination_id[]" id="destination_id" value="{{ $transportation_details_DS->destination_id }}">
                                                                    
                                                                    <input type="hidden" name="vehicle_select_exchange_type[]" id="vehicle_select_exchange_type_ID_{{ $TD_id1 }}" value="{{ $transportation_details_DS->vehicle_select_exchange_type ?? '' }}">
                                    
                                                                    <input type="hidden" name="vehicle_exchange_Rate[]" id="vehicle_exchange_Rate_ID_{{ $TD_id1 }}" value="{{ $transportation_details_DS->vehicle_exchange_Rate ?? '' }}">
                                                                    
                                                                    <input type="hidden" name="currency_SAR[]" id="currency_SAR_{{ $TD_id1 }}" value="{{ $transportation_details_DS->currency_SAR ?? '' }}">
                                                                
                                                                    <input type="hidden" name="currency_GBP[]" id="currency_GBP_{{ $TD_id1 }}" value="{{ $transportation_details_DS->currency_GBP ?? '' }}">
                                                                    
                                                                    <input type="hidden" id="currency_GBP_for_costing" value="{{ $transportation_details_DS->currency_GBP ?? '' }}">
                                                                    
                                                                    <h3>Transportation Details :</h3>
                                                                    <div class="col-xl-3" style="padding: 10px;">
                                                                        <label for="">Pick-up Location</label>
                                                                        <input type="text" value="{{ $transportation_details_DS->transportation_pick_up_location ?? '' }}" id="transportation_pick_up_location_{{ $TD_id1 }}" name="transportation_pick_up_location[]" class="form-control pickup_location" onkeyup="TPUL_function({{ $TD_id1 }})">
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3" style="padding: 10px;">
                                                                        <label for="">Drop-off Location</label>
                                                                        <input type="text" value="{{ $transportation_details_DS->transportation_drop_off_location ?? '' }}" id="transportation_drop_off_location_{{ $TD_id1 }}" name="transportation_drop_off_location[]" class="form-control dropof_location" onkeyup="TDOL_function({{ $TD_id1 }})">
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3" style="padding: 10px;">
                                                                        <label for="">Pick-up Date & Time</label>
                                                                        <input type="datetime-local" value="{{ $transportation_details_DS->transportation_pick_up_date ?? '' }}" id="transportation_pick_up_date_{{ $TD_id1 }}" name="transportation_pick_up_date[]" class="form-control" onchange="TPUD_function({{ $TD_id1 }})">
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3" style="padding: 10px;">
                                                                        <label for="">Drop-of Date & Time</label>
                                                                        <input type="datetime-local" value="{{ $transportation_details_DS->transportation_drop_of_date ?? '' }}" id="transportation_drop_of_date_{{ $TD_id1 }}" name="transportation_drop_of_date[]" class="form-control" onchange="TDOP_function({{ $TD_id1 }})">
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3" style="padding: 10px;" id="transportation_Time_Div_{{ $TD_id1 }}">
                                                                        <label for="">Estimate Time</label>
                                                                        <input type="text" value="{{ $transportation_details_DS->transportation_total_Time ?? '' }}" id="transportation_total_Time_{{ $TD_id1 }}" name="transportation_total_Time[]" class="form-control transportation_total_Time1" readonly style="padding: 10px;" value="">
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3" style="padding: 10px;">
                                                                        <label for="">Select Trip Type</label>
                                                                        <select name="transportation_trip_type[]" id="slect_trip_{{ $TD_id1 }}" class="form-control"  data-placeholder="Choose ..." readonly>
                                                                            <option value="{{ $transportation_details_DS->transportation_trip_type ?? '' }}">{{ $transportation_details_DS->transportation_trip_type ?? '' }}</option>
                                                                        </select>
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3" style="padding: 10px;">
                                                                        <label for="">Vehicle Type</label>
                                                                        <select name="transportation_vehicle_type[]" id="transportation_vehicle_typeI_{{ $TD_id1 }}" class="form-control"  data-placeholder="Choose ...">
                                                                            <option selected value="{{ $transportation_details_DS->transportation_vehicle_type ?? '' }}">{{ $transportation_details_DS->transportation_vehicle_type ?? '' }}</option>
                                                                        </select>
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3" style="padding: 10px;">
                                                                        <label for="">No Of Vehicle</label>
                                                                        <input type="text" value="{{ $transportation_details_DS->transportation_no_of_vehicle ?? '' }}" id="transportation_no_of_vehicle_{{ $TD_id1 }}" name="transportation_no_of_vehicle[]" class="form-control" onkeyup="TNOV_function({{ $TD_id1 }})">
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3" style="padding: 10px;">
                                                                        <label for="">Price Per Vehicle</label>
                                                                        <div class="input-group">
                                                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1_T_{{ $TD_id1 }}">{{ $transportation_details_DS->currency_SAR ?? '' }}</a></span>
                                                                            <input type="text" value="{{ $transportation_details_DS->transportation_price_per_vehicle ?? '' }}" id="transportation_price_per_vehicle_{{ $TD_id1 }}" name="transportation_price_per_vehicle[]" class="form-control" readonly>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3" style="padding: 10px;">
                                                                        <label for="">Total Vehicle Price</label>
                                                                        <div class="input-group">
                                                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1_T_{{ $TD_id1 }}">{{ $transportation_details_DS->currency_SAR ?? '' }}</a></span>
                                                                            <input type="text" value="{{ $transportation_details_DS->transportation_vehicle_total_price ?? '' }}" id="transportation_vehicle_total_price_{{ $TD_id1 }}" name="transportation_vehicle_total_price[]" class="form-control" readonly>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3 d-none" style="padding: 10px;">
                                                                        <label for="">Price Per Person</label>
                                                                        <div class="input-group">
                                                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1_T_{{ $TD_id1 }}">{{ $transportation_details_DS->currency_SAR ?? '' }}</a></span>
                                                                            <input type="text" value="{{ $transportation_details_DS->transportation_price_per_person ?? '' }}" id="transportation_price_per_person_{{ $TD_id1 }}" name="transportation_price_per_person[]" class="form-control" readonly>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    
                                                                    <div class="col-xl-3" style="padding: 10px;">
                                                                        <label for="">Exchange Rate</label>
                                                                        <div class="input-group">
                                                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1_T_{{ $TD_id1 }}">{{ $transportation_details_DS->currency_SAR ?? '' }}</a></span>
                                                                            <input type="text" value="{{ $transportation_details_DS->transfer_exchange_rate_destination ?? '' }}" name="transfer_exchange_rate_destination[]" id="transfer_exchange_rate_destination_{{ $TD_id1 }}" class="form-control" readonly>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3" style="padding: 10px;">
                                                                        <label for="">Price Per Vehicle</label>
                                                                        <div class="input-group">
                                                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1_T_{{ $TD_id1 }}">{{ $transportation_details_DS->currency_GBP ?? '' }}</a></span>
                                                                            <input type="text" value="{{ $transportation_details_DS->without_markup_price_converted_destination ?? '' }}" name="without_markup_price_converted_destination[]" id="without_markup_price_converted_destination_{{ $TD_id1 }}" class="form-control" readonly>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3" style="padding: 10px;">
                                                                        <label for="">Total Vehicle Price</label>
                                                                        <div class="input-group">
                                                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1_T_{{ $TD_id1 }}">{{ $transportation_details_DS->currency_GBP ?? '' }}</a></span>
                                                                            <input type="text" value="{{ $transportation_details_DS->transportation_vehicle_total_price_converted ?? '' }}" name="transportation_vehicle_total_price_converted[]" id="transportation_vehicle_total_price_converted_{{ $TD_id1 }}" class="form-control" readonly>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3 d-none" style="padding: 10px;">
                                                                        <label for="">Price Per Person</label>
                                                                        <div class="input-group">
                                                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1_T_{{ $TD_id1 }}">{{ $transportation_details_DS->currency_GBP ?? '' }}</a></span>
                                                                            <input type="text" value="{{ $transportation_details_DS->transportation_price_per_person_converted ?? '' }}" name="transportation_price_per_person_converted[]" id="transportation_price_per_person_converted_{{ $TD_id1 }}" class="form-control" readonly>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <!--Costing-->
                                                                    <div class="col-xl-3" style="padding: 10px;">
                                                                        <label for="">Vehicle Markup Type</label>
                                                                        <select name="vehicle_markup_type[]" id="vehicle_markup_type_{{ $TD_id1 }}" class="form-control" onchange="vehicle_markup_AllR({{ $TD_id1 }})">
                                                                            <option <?php if($transportation_details_DS->vehicle_markup_type ?? '' == '') echo 'selected' ?> value="">Markup Type</option>
                                                                            <option <?php if($transportation_details_DS->vehicle_markup_type ?? '' == '%') echo 'selected' ?> value="%">Percentage</option>
                                                                            <option <?php if($transportation_details_DS->vehicle_markup_type ?? '' == $currency) echo 'selected' ?> value="<?php echo $currency; ?>">Fixed Amount</option>
                                                                        </select>
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3" style="padding: 10px;">
                                                                        <label for="">Markup Value per Vehicle</label>
                                                                        <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1_T">{{ $transportation_details_DS->currency_GBP ?? '' }}</a></span>
                                                                            <input value="{{ $transportation_details_DS->vehicle_per_markup_value ?? '' }}" type="text" class="form-control" id="vehicle_per_markup_value_{{ $TD_id1 }}" name="vehicle_per_markup_value[]" onkeyup="vehicle_per_markup_OWandR1({{ $TD_id1 }})">
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <input value="{{ $transportation_details_DS->markup_price_per_vehicle_converted ?? '' }}" type="text" class="form-control d-none" id="markup_price_per_vehicle_converted_{{ $TD_id1 }}" name="markup_price_per_vehicle_converted[]" readonly>
                                                                    
                                                                    <div class="col-xl-3" style="padding: 10px;">
                                                                        <label for="">Vehicle Markup Value</label>
                                                                        <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1_T_{{ $TD_id1 }}">{{ $transportation_details_DS->currency_SAR ?? '' }}</a></span>
                                                                            <input value="{{ $transportation_details_DS->vehicle_markup_value ?? '' }}" ype="text" class="form-control" id="vehicle_markup_value_{{ $TD_id1 }}" name="vehicle_markup_value[]" onkeyup="vehicle_markup_AllR({{ $TD_id1 }})" readonly>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3" style="padding: 10px;">
                                                                        <label for="">Vehicle Total Price</label>
                                                                        <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1_T_{{ $TD_id1 }}">{{ $transportation_details_DS->currency_SAR ?? '' }}</a></span>
                                                                            <input value="{{ $transportation_details_DS->vehicle_total_price_with_markup ?? '' }}" type="text" class="form-control" id="vehicle_total_price_with_markup_{{ $TD_id1 }}" name="vehicle_total_price_with_markup[]" readonly>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3" style="padding: 10px;">
                                                                        <label for="">Vehicle Markup Value</label>
                                                                        <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1_T_{{ $TD_id1 }}">{{ $transportation_details_DS->currency_GBP ?? '' }}</a></span>
                                                                            <input value="{{ $transportation_details_DS->vehicle_markup_value_converted ?? '' }}" type="text" class="form-control" id="vehicle_markup_value_converted_{{ $TD_id1 }}" name="vehicle_markup_value_converted[]" readonly>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3" style="padding: 10px;">
                                                                        <label for="">Vehicle Markup Price</label>
                                                                        <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1_T_{{ $TD_id1 }}">{{ $transportation_details_DS->currency_GBP ?? '' }}</a></span>
                                                                            <input value="{{ $transportation_details_DS->vehicle_total_price_with_markup_converted ?? '' }}" type="text" class="form-control" id="vehicle_total_price_with_markup_converted_{{ $TD_id1 }}" name="vehicle_total_price_with_markup_converted[]" readonly>
                                                                        </div>
                                                                    </div>
                                                                    <!--End Costing-->
                                                                    
                                                                    <input type="hidden" class="transfer_markup_type_invoice" value="{{ $transportation_details_DS->transfer_markup_type_invoice ?? '' }}" name="transfer_markup_type_invoice[]">
                                                                    <input type="hidden" class="transfer_markup_invoice" value="{{ $transportation_details_DS->transfer_markup_invoice ?? '' }}" name="transfer_markup_invoice[]">
                                                                    <input type="hidden" class="transfer_markup_price_invoice" value="{{ $transportation_details_DS->transfer_markup_price_invoice ?? '' }}" name="transfer_markup_price_invoice[]">
                                                                
                                                                    <div id="append_transportation">
                                                                        @if(isset($transportation_details_more_D) && $transportation_details_more_D !=null && $transportation_details_more_D != '')
                                                                            @foreach($transportation_details_more_D as $transportation_details_more_DS)
                                                                                @if(isset($transportation_details_more_DS->more_all_round_Type) && $transportation_details_more_DS->more_all_round_Type != null && $transportation_details_more_DS->more_all_round_Type != '')
                                                                                
                                                                                    @if($transportation_details_more_DS->more_all_round_Type == $transportation_details_DS->all_round_Type)
                                                                                        <input type="hidden" name="more_all_round_Type[]" value="{{ $transportation_details_more_DS->more_all_round_Type ?? '' }}">
                                                                                        
                                                                                        <div class="row" id="click_delete_{{ $MTD_id }}">
                                                                                            <div class="col-xl-3" style="padding: 10px;">
                                                                                                <label for="">Pick-up Location</label>
                                                                                                <input type="text" value="{{ $transportation_details_more_DS->more_transportation_pick_up_location }}" id="more_transportation_pick_up_location_{{ $MTD_id }}" name="more_transportation_pick_up_location[]" class="form-control">
                                                                                            </div>
                                                                                            <div class="col-xl-3" style="padding: 10px;">
                                                                                                <label for="">Drop-off Location</label>
                                                                                                <input type="text" value="{{ $transportation_details_more_DS->more_transportation_drop_off_location }}" id="more_transportation_drop_off_location_{{ $MTD_id }}" name="more_transportation_drop_off_location[]" class="form-control">
                                                                                            </div>
                                                                                            <div class="col-xl-3" style="padding: 10px;">
                                                                                                <label for="">Pick-up Date & Time</label>
                                                                                                <input type="datetime-local" value="{{ $transportation_details_more_DS->more_transportation_pick_up_date }}" id="more_transportation_pick_up_date_{{ $MTD_id }}" onchange="MTPD_function({{ $MTD_id }})" name="more_transportation_pick_up_date[]" class="form-control">
                                                                                            </div>
                                                                                            
                                                                                            <div class="col-xl-3" style="padding: 10px;">
                                                                                                <label for="">Drop-of Date & Time</label>
                                                                                                <input type="datetime-local" value="{{ $transportation_details_more_DS->more_transportation_drop_of_date }}" id="more_transportation_drop_of_date_{{ $MTD_id }}" onchange="MTDD_function({{ $MTD_id }})" name="more_transportation_drop_of_date[]" class="form-control">
                                                                                            </div>
                                                                                            
                                                                                            <div class="col-xl-3" style="padding: 10px;" id="more_transportation_Time_Div_{{ $MTD_id }}">
                                                                                                <label for="">Estimate Time</label>
                                                                                                <input type="text" value="{{ $transportation_details_more_DS->more_transportation_total_Time }}" id="more_transportation_total_Time_{{ $MTD_id }}" name="more_transportation_total_Time[]" class="form-control" readonly style="padding: 10px;">
                                                                                            </div>
                                                                                            
                                                                                            <div class="col-xl-9" style="padding: 20px;">
                                                                                                <button style="float: right;" type="button" class="btn btn-info deletButton" onclick="deleteRowTransI({{ $MTD_id }})"  id="${MTD_id}">Delete</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    @endif
                                                                                @endif
                                                                            <?php $MTD_id = $MTD_id + 1; ?>
                                                                            @endforeach
                                                                        @endif
                                                                    </div>
                                                                
                                                                    <input type="hidden" value="{{ $MTD_id }}" id="MTD_id_input">
                                                                    
                                                                    <div class="mt-2" style="display:none;" id="add_more_destination">
                                                                        <a href="javascript:;" id="more_transportationI" class="btn btn-info" style="float: right;">Add More Destinations </a>
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-6 R_AllRound_class_{{ $TD_id1 }}" style="padding: 10px;"></div>
                                                                    <div class="col-xl-6 R_AllRound_class_{{ $TD_id1 }}" style="padding: 10px;">
                                                                        <a type="button" class="btn btn-primary" id="R_AllRound_Id_{{ $TD_id1 }}" onclick="R_AllRound_function({{ $TD_id1 }},{{ $TD_id1 }})" style="float: right;">Remove</a>
                                                                    </div>
                                                                
                                                                </div>
                                                            
                                                            @endif
                                                        @endif
                                                        <?php $TD_id1 = $TD_id1 + 1; ?>
                                                    @endforeach
                                                @endif
                                            </div>
                                            
                                            <input type="hidden" id="transportation_price_switchAR" value="{{ $TD_id1 }}">
                                            
                                        @endif
                                        
                                    @endif
                                    
                                @endif
                                
                                <div class="row" style="display:none;border:1px solid #ced4da;padding: 20px 20px 20px 20px;" id="select_transportation_Original">
                                    
                                    <input type="hidden" id="transportation_price_switch">
                                    
                                    <input type="hidden" name="all_round_Type[]">
                                    
                                    <input type="hidden" name="destination_id[]" id="destination_id">
                                    
                                    <input type="hidden" name="vehicle_select_exchange_type[]" id="vehicle_select_exchange_type_ID">
                            
                                    <input type="hidden" name="vehicle_exchange_Rate[]" id="vehicle_exchange_Rate_ID">
                                    
                                    <input type="hidden" name="currency_SAR[]" id="currency_SAR">
                                
                                    <input type="hidden" name="currency_GBP[]" id="currency_GBP">
                                    
                                    <h3>Transportation Details :</h3>
                                    <div class="col-xl-3" style="padding: 10px;">
                                        <label for="">Pick-up Location</label>
                                        <input type="text" id="transportation_pick_up_location" name="transportation_pick_up_location[]" class="form-control pickup_location" value="">
                                    </div>
                                    
                                    <div class="col-xl-3" style="padding: 10px;">
                                        <label for="">Drop-off Location</label>
                                        <input type="text" id="transportation_drop_off_location" name="transportation_drop_off_location[]" class="form-control dropof_location">
                                    </div>
                                    
                                    <div class="col-xl-3" style="padding: 10px;">
                                        <label for="">Pick-up Date & Time</label>
                                        <input type="datetime-local" id="transportation_pick_up_date" name="transportation_pick_up_date[]" class="form-control">
                                    </div>
                                    
                                    <div class="col-xl-3" style="padding: 10px;">
                                        <label for="">Drop-of Date & Time</label>
                                        <input type="datetime-local" id="transportation_drop_of_date" name="transportation_drop_of_date[]" class="form-control">
                                    </div>
                                    
                                    <div class="col-xl-3" style="padding: 10px;display:none" id="transportation_Time_Div">
                                        <label for="">Estimate Time</label>
                                        <input type="text" id="transportation_total_Time" name="transportation_total_Time[]" class="form-control transportation_total_Time1" readonly style="padding: 10px;" value="">
                                    </div>
                                    
                                    <div class="col-xl-3" style="padding: 10px;">
                                        <label for="">Select Trip Type</label>
                                        <select name="transportation_trip_type[]" id="slect_trip" class="form-control"  data-placeholder="Choose ...">
                                            <option value="">Select</option>
                                            <option value="One-Way">One-Way</option>
                                            <option value="Return">Return</option>
                                            <option value="All_Round">All Round</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-xl-3" style="padding: 10px;">
                                        <label for="">Vehicle Type</label>
                                        <select name="transportation_vehicle_type[]" id="transportation_vehicle_typeI" class="form-control"  data-placeholder="Choose ...">
                                            <option value="">Choose ...</option>
                                            <option value="Bus">Bus</option>
                                            <option value="Coach">Coach</option>
                                            <option value="Vain">Vain</option>
                                            <option value="Car">Car</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-xl-3" style="padding: 10px;">
                                        <label for="">No Of Vehicle</label>
                                        <input type="text" id="transportation_no_of_vehicle" name="transportation_no_of_vehicle[]" class="form-control">
                                    </div>
                                    
                                    <div class="col-xl-3" style="padding: 10px;">
                                        <label for="">Price Per Vehicle</label>
                                        <div class="input-group">
                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1_T"></a></span>
                                            <input type="text" id="transportation_price_per_vehicle" name="transportation_price_per_vehicle[]" class="form-control" readonly>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-3" style="padding: 10px;">
                                        <label for="">Total Vehicle Price</label>
                                        <div class="input-group">
                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1_T"></a></span>
                                            <input type="text" id="transportation_vehicle_total_price" name="transportation_vehicle_total_price[]" class="form-control" readonly>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-3" style="padding: 10px;">
                                        <label for="">Price Per Person</label>
                                        <div class="input-group">
                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1_T"></a></span>
                                            <input type="text" id="transportation_price_per_person" name="transportation_price_per_person[]" class="form-control" readonly>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-3" style="padding: 10px;">
                                        <label for="">Exchange Rate</label>
                                        <div class="input-group">
                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1_T"></a></span>
                                            <input type="text" name="transfer_exchange_rate_destination[]" id="transfer_exchange_rate_destination" class="form-control" readonly>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-3" style="padding: 10px;">
                                        <label for="">Price Per Vehicle</label>
                                        <div class="input-group">
                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1_T"></a></span>
                                            <input type="text" name="without_markup_price_converted_destination[]" id="without_markup_price_converted_destination" class="form-control" readonly>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-3" style="padding: 10px;">
                                        <label for="">Total Vehicle Price</label>
                                        <div class="input-group">
                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1_T"></a></span>
                                            <input type="text" name="transportation_vehicle_total_price_converted[]" id="transportation_vehicle_total_price_converted" class="form-control" readonly>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-3" style="padding: 10px;">
                                        <label for="">Price Per Person</label>
                                        <div class="input-group">
                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1_T"></a></span>
                                            <input type="text" name="transportation_price_per_person_converted[]" id="transportation_price_per_person_converted" class="form-control" readonly>
                                        </div>
                                    </div>
                                    
                                    <!--Costing-->
                                    <div class="col-xl-3" style="padding: 10px;">
                                        <label for="">Vehicle Markup Type</label>
                                        <select name="vehicle_markup_type[]" id="vehicle_markup_type" class="form-control" onchange="vehicle_markup_OWandR()">
                                            <option value="">Markup Type</option>
                                            <option value="%">Percentage</option>
                                            <option value="<?php echo $currency; ?>">Fixed Amount</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-xl-3" style="padding: 10px;">
                                        <label for="">Markup Value per Vehicle</label>
                                        <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1_T"></a></span>
                                            <input type="text" class="form-control" id="vehicle_per_markup_value" name="vehicle_per_markup_value[]" onkeyup="vehicle_per_markup_OWandR()">
                                        </div>
                                    </div>
                                    
                                    <input type="text" class="form-control d-none" id="markup_price_per_vehicle_converted" name="markup_price_per_vehicle_converted[]" readonly>
                                    
                                    <div class="col-xl-3" style="padding: 10px;">
                                        <label for="">Vehicle Markup Value</label>
                                        <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1_T"></a></span>
                                            <input type="text" class="form-control" id="vehicle_markup_value" name="vehicle_markup_value[]" readonly>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-3" style="padding: 10px;">
                                        <label for="">Vehicle Total Price</label>
                                        <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1_T"></a></span>
                                            <input type="text" class="form-control" id="vehicle_total_price_with_markup" name="vehicle_total_price_with_markup[]" readonly>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-3" style="padding: 10px;">
                                        <label for="">Vehicle Markup Value</label>
                                        <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1_T"></a></span>
                                            <input type="text" class="form-control" id="vehicle_markup_value_converted" name="vehicle_markup_value_converted[]" readonly>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-3" style="padding: 10px;">
                                        <label for="">Vehicle Markup Price</label>
                                        <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1_T"></a></span>
                                            <input type="text" class="form-control" id="vehicle_total_price_with_markup_converted" name="vehicle_total_price_with_markup_converted[]" readonly>
                                        </div>
                                    </div>
                                    <!--End Costing-->
                                    
                                    <input type="hidden" class="transfer_markup_type_invoice" name="transfer_markup_type_invoice[]">
                                    <input type="hidden" class="transfer_markup_invoice" name="transfer_markup_invoice[]">
                                    <input type="hidden" class="transfer_markup_price_invoice" name="transfer_markup_price_invoice[]">
                            
                                    <!--Return Transportation-->
                                    <div class="row" id="select_return" style="display:none;padding: 10px;">
                                        <h3>Return Details :</h3>
                                        <div class="col-xl-3" style="padding: 10px;">
                                            <label for="">Pick-up Location</label>
                                            <input type="text" id="return_transportation_pick_up_location" name="return_transportation_pick_up_location" class="form-control pickup_location">
                                        </div>
                                        
                                        <div class="col-xl-3" style="padding: 10px;">
                                            <label for="">Drop-off Location</label>
                                            <input type="text" id="return_transportation_drop_off_location" name="return_transportation_drop_off_location" class="form-control dropof_location">
                                        </div>
                                        
                                        <div class="col-xl-3" style="padding: 10px;">
                                            <label for="">Pick-up Date & Time</label>
                                            <input type="datetime-local" id="return_transportation_pick_up_date" name="return_transportation_pick_up_date" class="form-control">
                                        </div>
                                        
                                        <div class="col-xl-3" style="padding: 10px;">
                                            <label for="">Drop-of Date & Time</label>
                                            <input type="datetime-local" id="return_transportation_drop_of_date" name="return_transportation_drop_of_date" class="form-control">
                                        </div>
                                        
                                        <div class="col-xl-3" style="padding: 10px;display:none" id="return_transportation_Time_Div">
                                            <label for="" style="width: 205px;">Estimate Time Return</label>
                                            <input type="text" id="return_transportation_total_Time" name="return_transportation_total_Time" class="form-control return_transportation_total_Time1" readonly style="padding: 10px;" value="">
                                        </div>
                                        
                                        <div class="col-xl-3" style="display:none;padding: 10px;">
                                            <label for="">Vehicle Type</label>
                                            <select name="return_transportation_vehicle_type" id="" class="form-control"  data-placeholder="Choose ...">
                                                <option value="">Choose ...</option>
                                                <option value="Bus">Bus</option>
                                                <option value="Coach">Coach</option>
                                                <option value="Vain">Vain</option>
                                                <option value="Car">Car</option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-xl-3" style="display:none;padding: 10px;">
                                            <label for="">No Of Vehicle</label>
                                            <input type="text" id="return_transportation_no_of_vehicle" name="return_transportation_no_of_vehicle" class="form-control">
                                        </div>
                                        
                                        <div class="col-xl-3" style="display:none;padding: 10px;">
                                            <label for="">Price Per Vehicle</label>
                                            <div class="input-group">
                                                <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up"><?php echo $currency; ?></a></span>
                                                <input type="text" id="return_transportation_price_per_vehicle" name="return_transportation_price_per_vehicle" class="form-control">
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-3" style="display:none;padding: 10px;">
                                            <label for="">Total Vehicle Price</label>
                                            <div class="input-group">
                                                <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up"><?php echo $currency; ?></a></span>
                                                <input type="text" id="return_transportation_vehicle_total_price" name="return_transportation_vehicle_total_price" class="form-control">
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-3" style="display:none;padding: 10px;">
                                            <label for="">Price Per Person</label>
                                            <div class="input-group">
                                                <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up"><?php echo $currency; ?></a></span>
                                                <input type="text" id="return_transportation_price_per_person" name="return_transportation_price_per_person" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div id="append_transportation"></div>
                                
                                    <div class="mt-2" style="display:none;" id="add_more_destination">
                                        <a href="javascript:;" id="more_transportationI" class="btn btn-info" style="float: right;">Add More Destinations </a>
                                    </div>
                                </div>
                                
                                @if($services[0] == '1' || $services[0] == 'transportation_tab')
                                    <div class="row" style="display:none;border:1px solid #ced4da;padding: 20px 20px 20px 20px;" id="transportation_main_divI"></div>
                                    
                                    <div class="col-xl-12 d-none" style="padding: 10px;">
                                        <label for="">Image</label>
                                        <input type="file" name="transportation_image" class="form-control">
                                        <img src="{{ asset('/public/uploads/package_imgs/'.$transportation_details_DS->transportation_image) }}" style="width:30%;" /> 
                                        <input type="text" name="transportation_image_else" class="form-control" value="{{ $transportation_details_DS->transportation_image ?? ''}}" readonly hidden>
                                    </div>
                                @else
                                    <div class="row" style="display:none;border:1px solid #ced4da;padding: 20px 20px 20px 20px;" id="transportation_main_divI"></div>
                                
                                    <div class="col-xl-12 d-none" style="padding: 10px;">
                                        <label for="">Image</label>
                                        <input type="file" name="transportation_image" class="form-control">
                                    </div>
                                @endif
                                
                                <div class="col-xl-12 mt-2" style="padding: 10px;;display:none" id="transfer_modal_button">
                                    <span title="Add Flight Seats" class="input-group-btn input-group-append">
                                        <button class="btn btn-primary bootstrap-touchspin-up" data-bs-toggle="modal" data-bs-target="#add-transfer-destination-modal" type="button" onclick="transfer_modal_button_function()">Add Destination</button>
                                    </span>
                                </div>
                                
                            </div>
                        </div> 
                        <!--<a id="save_Transportation" class="btn btn-primary" name="submit">Save Transportation Details</a>-->
                    </div>
                 
                    <div class="tab-pane d-none" id="Itinerary_details">
                        <div class="row">
                            
                            <div class="col-xl-12">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">What's Included In Package?</label>
                                    <textarea name="whats_included" class="form-control summernote" id="" cols="10" rows="0"></textarea>
                                    <!--<textarea name="whats_included" class="form-control" cols="10" rows="5"></textarea>-->
                                </div>
                            </div>
                          
                            <div class="col-xl-12">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">What's Excluded In Package?</label>
                                    <textarea name="whats_excluded" class="form-control summernote" id="" cols="10" rows="0"></textarea>
                                    <!--<textarea name="whats_excluded" class="form-control" cols="10" rows="5"></textarea>-->
                                </div>
                            </div>
                          
                            <div class="col-xl-12">
                                <label for="">Cancellation Policy</label>
                                <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Cancellation Policy"></i>
                                <textarea name="cancellation_policy" class="form-control" cols="5" rows="5"></textarea>
                            </div>
                            
                            <div class="col-xl-12" style="margin-top:15px">
                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-xl-1">
                                            <input type="checkbox" id="Itinerary" data-switch="bool"/>
                                            <label for="Itinerary" data-on-label="On" data-off-label="Off"></label>
                                        </div>
                                        <div class="col-xl-3">
                                            Itinerary
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Add day to day Itinerary for this package"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                          
                            <div class="row" id="Itinerary_select" style="display:none">
                                
                                <div class="col-xl-6">
                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Title</label>
                                        <input type="text" id="simpleinput" name="Itinerary_title" class="form-control">
                                    </div>
                                </div>
                              
                                <div class="col-xl-6">
                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Short Description</label>
                                        <input type="text" id="simpleinput" name="Itinerary_city" class="form-control">
                                    </div>
                                </div>
                              
                                <div class="col-xl-12">
                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Content</label>
                                        <textarea name="Itinerary_content" class="form-control" id="" cols="10" rows="10"></textarea>
                                    </div>
                                </div>
                              
                                <div class="" id="append_data"></div>
                            
                                <div class="mb-3">
                                    <div class="btn btn-info" id="click_more_Itinerary">Add More</div>
                                </div>
                            </div>

                            <div class="col-xl-12">
                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-xl-1">
                                            <input type="checkbox" id="extra_price" data-switch="bool"/>
                                            <label for="extra_price" data-on-label="On" data-off-label="Off"></label>
                                        </div>
                                        <div class="col-xl-3 additional_Services">Additional Services
                                            <i class="dripicons-information" style="font-size: 17px;" id="additional_Services" data-bs-toggle="tooltip" data-bs-placement="right" title="Additional Services"></i>
                                        </div> 
                                    </div>
                                </div>
                            </div>

                            <!-- extra price started -->
                            <div class="row" id="extraprice_select" style="display:none">
                                
                                <div class="col-xl-6">
                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Name</label>
                                        <input type="text" id="simpleinput" name="extra_price_name" class="form-control">
                                    </div>
                                </div>
                              
                                <div class="col-xl-6">
                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Price</label>
                                        <div class="input-group">
                                            <span class="input-group-btn input-group-append">
                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                            
                                                </a>
                                            </span>
                                            <input type="text" id="simpleinput" name="extra_price_price" class="form-control">
                                        </div>
                                    </div>
                                </div>
                              
                                <div class="col-xl-6">
                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Type</label>
                                        <select name="extra_price_type" id="" class="form-control">
                                            <option value="">Select Type</option>
                                            <option value="0">One-Time</option>
                                            <option value="1">Per-Hour</option>
                                            <option value="2">Per-Day</option>
                                        </select>
                                    </div>
                                </div>
                              
                                <div class="col-xl-6">
                                    <div class="mb-3 mt-4">
                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" class="form-check-input" id="Price_per_person" name="extra_price_person">
                                            <label class="form-check-label" for="Price_per_person">Price per person</label>
                                        </div>
                                    </div>
                                </div>
                        
                                <div class="" id="append_data_extra_price"></div>
                        
                                <div class="mb-3">
                                    <div class="btn btn-info" id="click_more_extra_price">Add More</div>
                                </div>
                                
                            </div>
                            <!-- extra price ended -->

                            <div class="col-xl-12">
                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-xl-1">
                                            <input type="checkbox" id="faq" data-switch="bool"/>
                                            <label for="faq" data-on-label="On" data-off-label="Off"></label>
                                        </div>
                                        <div class="col-xl-3">
                                            FAQ
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Add common questions & answers for customers"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- faq started -->
                            <div class="row" id="faq_select" style="display:none">
                                
                                <div class="col-xl-6">
                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Title</label>
                                        <input type="text" id="simpleinput" name="faq_title" class="form-control">
                                    </div>
                                </div>
                                
                                <div class="col-xl-6">
                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label">content</label>
                                        <textarea name="faq_content" class="form-control" id="" cols="10" rows="10"></textarea>
                                    </div>
                                </div>
                                
                                <div class="" id="append_data_faq"></div>
                                
                                <div class="mb-3">
                                    <div class="btn btn-info" id="click_more_faq">Add More</div>   
                                </div>
                                
                            </div>
                            <!-- faq ended -->
                            
                        </div> 
                        <!--<a id="save_Itinerary" class="btn btn-primary" name="submit">Save Itinerar Details</a>-->
                    </div> 
                    
                    <div class="tab-pane d-none" id="Extras_details">
                    
                        <div class="row">
                            
                            <div class="col-xl-4 d-none" style="text-align:right;">
                                <span>Markup on total price</span>
                            </div>
                            
                            <input type="hidden" id="markupSwitch" name="markupSwitch" value="single_markup_switch">
                            
                            <div class="col-xl-1 d-none">
                                <input type="checkbox" id="switch2" data-switch="bool"/>
                                <label for="switch2" data-on-label="On" data-off-label="Off"></label>
                            </div>
                             
                            <div class="col-xl-7 d-none">
                                <span>Markup on break down prices</span>
                            </div>

                            <div class="col-xl-12" id="markup_services">
                                <div class="card">
                                    
                                    <div class="card-header d-none">
                                        <h4 class="modal-title" id="standard-modalLabel">Markup on break down prices</h4>
                                    </div>
                                    
                                    <div class="card-body">
                                        <div class="row">
                                            
                                            <?php $markup_detailsE = $get_invoice->markup_details; ?>
                                            @if(isset($markup_detailsE) && $markup_detailsE != null && $markup_detailsE != '')
                                                <?php $markup_details     = json_decode($markup_detailsE); //dd($markup_details); ?>
                                                @foreach($markup_details as $value)
                                                    @if(isset($value->markup_Type_Costing) && $value->markup_Type_Costing != null && $value->markup_Type_Costing != '')
                                                        @if($value->markup_Type_Costing == 'flight_Type_Costing')
                                                            <div class="row" id="flights_cost" style="display:none;padding-bottom: 25px">
                                                                
                                                                <div class="col-xl-3">
                                                                     <h4 class="" id="">Flights Cost</h4>
                                                                </div>
                                                                
                                                                <input type="hidden" name="acc_hotel_CityName[]">
                                                                <input type="hidden" name="acc_hotel_HotelName[]">
                                                                <input type="hidden" name="acc_hotel_CheckIn[]">
                                                                <input type="hidden" name="acc_hotel_CheckOut[]" >
                                                                <input type="hidden" name="acc_hotel_NoOfNights[]">
                                                                <input type="hidden" name="acc_hotel_Quantity[]">
                                                                
                                                                <div class="col-xl-12">
                                                                    <input type="hidden" id="flight_Type_Costing" name="markup_Type_Costing[]" value="flight_Type_Costing" class="form-control">
                                                                </div>
                                                                
                                                                <div class="col-xl-3">
                                                                    <label>Departue</label>
                                                                    <input type="text" id="flights_departure_code" readonly name="hotel_name_markup[]" class="form-control" value="{{ $value->hotel_name_markup ?? '' }}">
                                                                </div>
                                                                
                                                                <div class="col-xl-3">
                                                                    <label>Arrival</label>
                                                                    <input type="text" id="flights_arrival_code" readonly name="room_type[]" class="form-control" value="{{ $value->room_type ?? '' }}">
                                                                </div>
                                                                
                                                                <div class="col-xl-2" style="display:none">
                                                                    <div class="input-group">
                                                                        <input type="text" id="flights_price_per_night" readonly name="without_markup_price_single[]" class="form-control" value="{{ $value->without_markup_price_single ?? '' }}">    
                                                                        <span class="input-group-btn input-group-append">
                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value1"></a>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="col-xl-2">
                                                                    <label>Total Cost Price</label>
                                                                    <div class="input-group">
                                                                        <input type="text" id="flights_prices" readonly name="without_markup_price[]" class="form-control" value="{{ $value->without_markup_price ?? '' }}">
                                                                        <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">{{ $value->markup ?? '' }}</a></span>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="col-xl-2" style="display:none">
                                                                    <label>Markup Type</label>
                                                                    <select name="markup_type[]" id="markup_type" class="form-control" readonly>
                                                                        <option value="{{ $value->markup_type }}">{{ $value->markup_type ?? '' }}</option>
                                                                    </select>
                                                                </div>
                                                                
                                                                <div class="col-xl-2">
                                                                    <label>Total Markup</label>
                                                                    <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                        <input type="text" class="form-control" id="markup_value" name="markup[]" value="{{ $value->markup ?? '' }}" readonly>
                                                                        <span class="input-group-btn input-group-append">
                                                                            <button class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2" type="button"><div id="flight_markup_btn">{{ $value->markup_type ?? '' }}</div></button>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="col-xl-1" style="display:none">
                                                                    <input type="text" id="flights_exchage_rate_per_night" readonly="" name="exchage_rate_single[]" class="form-control" value="{{ $value->exchage_rate_single ?? '' }}">
                                                                </div>
                                                                
                                                                <div class="col-xl-2" style="display:none">
                                                                    <div class="input-group">
                                                                        <input type="text" id="flights_exchage_rate_markup_total_per_night" readonly="" name="markup_total_per_night[]" class="form-control" value="{{ $value->markup_total_per_night ?? '' }}">
                                                                        <span class="input-group-btn input-group-append">
                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">{{ $value->markup_type ?? '' }}</a>
                                                                        </span>
                                                                    </div> 
                                                                </div>
                                                                
                                                                <div class="col-xl-2">
                                                                    <label>Total Sale Price</label>
                                                                    <div class="input-group">
                                                                        <input type="text" id="total_markup" name="markup_price[]" class="form-control" value="{{ $value->markup_price ?? '' }}" readonly>
                                                                        <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">{{ $value->markup_type ?? '' }}</a></span>
                                                                    </div>
                                                                </div>
                                                            
                                                            </div>
                                                        @endif
                                                    @endif            
                                                @endforeach
                                            @endif
                                            
                                            <div id="append_accomodation_data_cost">
                                                
                                                @if($get_invoice->markup_details != null)
                                                    <?php
                                                        $count = 1;
                                                        $markup_details = $get_invoice->markup_details;
                                                        $markup_details = json_decode($markup_details);
                                                        foreach($markup_details as $details)
                                                        {
                                                            if($details->markup_Type_Costing == 'hotel_Type_Costing')
                                                            {
                                                    ?>
                                                                <div class="row" id="costing_accI{{$count}}" style="margin-bottom:20px;">
                                                                    <input type="hidden" id="hotel_Type_Costing" name="markup_Type_Costing[]" value="hotel_Type_Costing" class="form-control">
                                                                
                                                                    <input type="text" name="hotel_name_markup[]" hidden>
                                                                
                                                                    @if(isset($get_invoice->accomodation_details) && $get_invoice->accomodation_details != null && $get_invoice->accomodation_details != '')
                                                                        <?php
                                                                            $accomodation_details=$get_invoice->accomodation_details;
                                                                            $accomodation_details=json_decode($accomodation_details);
                                                                        ?>
                                                                        @foreach($accomodation_details as $accomodation_detailsS)
                                                                            <div class="col-xl-12 d-none">
                                                                                @if(isset($details->markup_price) && $details->markup_price != null && $details->markup_price != '')
                                                                                    @if($details->markup_price == $accomodation_detailsS->hotel_invoice_markup)
                                                                                        <h4> 
                                                                                            Accomodation Details {{ $accomodation_detailsS->hotel_city_name ?? '' }} - {{ $accomodation_detailsS->acc_hotel_name ?? '' }}
                                                                                            <a class="btn">
                                                                                                (Quantity : <b style="color: #cdc0c0;">{{ $accomodation_detailsS->acc_qty ?? '' }}</b>)
                                                                                                (Check in : <b style="color: #cdc0c0;">{{ $accomodation_detailsS->acc_check_in ?? '' }}</b>)
                                                                                                (Check Out : <b style="color: #cdc0c0;">{{ $accomodation_detailsS->acc_check_out ?? '' }}</b>)
                                                                                                (Nights : <b style="color: #cdc0c0;">{{ $accomodation_detailsS->acc_no_of_nightst ?? '' }}</b>)
                                                                                            </a>
                                                                                        </h4>
                                                                                    @endif
                                                                                @endif
                                                                            </div>
                                                                        @endforeach
                                                                    @else
                                                                        <div class="col-xl-12 d-none"></div>
                                                                    @endif
                                                                    
                                                                    <div class="col-xl-12">
                                                                        <input type="hidden" name="acc_hotel_CityName[]" id="acc_hotel_CityName{{ $count }}" value="{{ $details->acc_hotel_CityName ?? '' }}">
                                                                        <input type="hidden" name="acc_hotel_HotelName[]" id="acc_hotel_HotelName{{ $count }}" value="{{ $details->acc_hotel_HotelName ?? '' }}">
                                                                        <input type="hidden" name="acc_hotel_CheckIn[]" id="acc_hotel_CheckIn{{ $count }}" value="{{ $details->acc_hotel_CheckIn ?? '' }}">
                                                                        <input type="hidden" name="acc_hotel_CheckOut[]" id="acc_hotel_CheckOut{{ $count }}" value="{{ $details->acc_hotel_CheckOut ?? '' }}">
                                                                        <input type="hidden" name="acc_hotel_NoOfNights[]" id="acc_hotel_NoOfNights{{ $count }}" value="{{ $details->acc_hotel_NoOfNights ?? '' }}">
                                                                        <input type="hidden" name="acc_hotel_Quantity[]" id="acc_hotel_Quantity{{ $count }}" value="{{ $details->acc_hotel_Quantity ?? '' }}">
                                                                        @if(isset($details->acc_hotel_CityName) && $details->acc_hotel_CityName != null && $details->acc_hotel_CityName != '')
                                                                            <h4> 
                                                                                Accomodation Details {{ $details->acc_hotel_CityName ?? '' }} - {{ $accomodation_detailsS->acc_hotel_name ?? '' }}
                                                                                <a class="btn">
                                                                                    (Quantity : <b style="color: #cdc0c0;">{{ $details->acc_hotel_Quantity ?? '' }}</b>)
                                                                                    (Check in : <b style="color: #cdc0c0;">{{ $details->acc_hotel_CheckIn ?? '' }}</b>)
                                                                                    (Check Out : <b style="color: #cdc0c0;">{{ $details->acc_hotel_CheckOut ?? '' }}</b>)
                                                                                    (Nights : <b style="color: #cdc0c0;">{{ $details->acc_hotel_NoOfNights ?? '' }}</b>)
                                                                                </a>
                                                                            </h4>
                                                                        @else
                                                                            <h4 id="acc_cost_html_{{ $count }}">Accomodation Cost</h4>
                                                                        @endif
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3">
                                                                        <label>Room Type</label>
                                                                        <input type="text" id="hotel_acc_type_{{$count}}" readonly="" value="{{$details->room_type}}" name="room_type[]" class="form-control id_cot">
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3">
                                                                        <label>Price Per Room/Night</label>
                                                                        <div class="input-group">
                                                                            <input type="text" id="hotel_acc_price_per_night_{{ $count }}" readonly="" name="without_markup_price_single[]" class="form-control" value="{{ $details->without_markup_price_single ?? '' }}">
                                                                            <span class="input-group-btn input-group-append">
                                                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3">
                                                                        <label>Cost Price/Room</label>
                                                                        <div class="input-group">
                                                                            <input type="text" id="hotel_acc_price_{{$count}}" readonly="" value="{{$details->without_markup_price}}"  name="without_markup_price[]" class="form-control">
                                                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2"></a></span>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3">
                                                                        <label>Markup Type</label>
                                                                        <select name="markup_type[]" onchange="hotel_markup_typeI({{$count}})" id="hotel_markup_types_{{$count}}" class="form-control">
                                                                            <option value="">Markup Type</option>
                                                                            <option <?php if($details->markup_type == '%') echo "selected";?> value="%">Percentage</option>
                                                                            <option <?php if($details->markup_type == $currency ) echo "selected";?> value="<?php echo $currency; ?>">Fixed Amount</option>
                                                                            <option <?php if($details->markup_type == 'per_Night' ) echo "selected";?> value="per_Night">Per Night</option>
                                                                        </select>
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3 markup_value_Div_{{ $count }}" style="display:none;margin-top:10px">
                                                                        <label>Markup Value</label>
                                                                        <input type="hidden" id="" name="" class="form-control">
                                                                        <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                            <input type="text" value="{{$details->markup}}"  class="form-control" id="hotel_markup_{{$count}}" name="markup[]" onkeyup="hotel_markup_funI({{ $count }})">
                                                                            <span class="input-group-btn input-group-append">
                                                                                <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="hotel_markup_mrk_{{$count}}" class="currency_value1">%</div></button>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3 exchnage_rate_Div_{{ $count }}" style="display:none;margin-top:10px">
                                                                        <label>Exchange Rate</label>
                                                                        <div class="input-group">
                                                                            <input type="text" id="hotel_exchage_rate_per_night_{{ $count }}" readonly="" name="exchage_rate_single[]" class="form-control" value="{{ $details->exchage_rate_single }}">
                                                                            <span class="input-group-btn input-group-append">
                                                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value1"></a>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3 markup_price_Div_{{ $count }}" style="display:none;margin-top:10px">
                                                                        <label>Markup Price</label>
                                                                        <div class="input-group">
                                                                            <input type="text" id="hotel_exchage_rate_markup_total_per_night_{{ $count }}" readonly="" name="markup_total_per_night[]" class="form-control" value="{{ $details->markup_total_per_night }}">
                                                                            <span class="input-group-btn input-group-append">
                                                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                                                            </span>
                                                                        </div> 
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3 markup_total_price_Div_{{ $count }}" style="display:none;margin-top:10px">
                                                                        <label>Markup Total Price</label>
                                                                        <div class="input-group">
                                                                            <input type="text" id="hotel_markup_total_{{$count}}" value="{{$details->markup_price}}" name="markup_price[]" class="form-control id_cot">
                                                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2"></a></span>
                                                                        </div> 
                                                                    </div>
                                                                </div>
                                                    <?php
                                                            $count=$count+1;
                                                            }
                                                        }
                                                    ?>
                                                @endif
                                            </div>
                                            
                                            <hr style="width: 98%;margin-left: 13px;margin-top: 10px;margin-bottom: 10px;color: black;"></hr>
                                            
                                            <div id="append_accomodation_data_cost1">
                                                
                                                @if($get_invoice->more_markup_details != null)
                                                    <?php
                                                        $count=1;
                                                        $more_markup_details=$get_invoice->more_markup_details;
                                                        $more_markup_details=json_decode($more_markup_details);
                                                        // dd($more_markup_details);
                                                        foreach($more_markup_details as $details)
                                                        {
                                                            if($details->more_markup_Type_Costing == 'more_hotel_Type_Costing')
                                                            {
                                                    ?>
                                                                <div style="padding-bottom: 5px;" class="row click_deleteI_{{$count}}" id="click_deleteI_{{$count}}">
                                                                    
                                                                    @if(isset($get_invoice->accomodation_details_more) && $get_invoice->accomodation_details_more != null && $get_invoice->accomodation_details_more != '')
                                                                        <?php
                                                                            $accomodation_details_more=$get_invoice->accomodation_details_more;
                                                                            $accomodation_details_more=json_decode($accomodation_details_more);
                                                                            // dd($accomodation_details_more);
                                                                        ?>
                                                                        @isset($accomodation_details_more)
                                                                        @foreach($accomodation_details_more as $accomodation_details_moreS)
                                                                            <div class="col-xl-12 d-none">
                                                                                @if(isset($details->more_markup_price) && $details->more_markup_price != null && $details->more_markup_price != '')
                                                                                    @if($details->more_markup_price == $accomodation_details_moreS->more_hotel_invoice_markup)
                                                                                        <h4 class="" id="">
                                                                                            More Accomodation Cost {{ $accomodation_details_moreS->more_hotel_city ?? '' }} - <a id="new_MAHN_{{ $count }}">{{ $accomodation_details_moreS->more_acc_hotel_name ?? '' }}</a>
                                                                                            <a class="btn">
                                                                                                (Quantity : <b style="color: #cdc0c0;">{{ $accomodation_details_moreS->more_acc_qty ?? '' }}</b>)
                                                                                                (Check in : <b style="color: #cdc0c0;">{{ $accomodation_details_moreS->more_acc_check_in ?? '' }}</b>)
                                                                                                (Check Out : <b style="color: #cdc0c0;">{{ $accomodation_details_moreS->more_acc_check_out ?? '' }}</b>)
                                                                                                (Nights : <b style="color: #cdc0c0;">{{ $accomodation_details_moreS->more_acc_no_of_nightst ?? '' }}</b>)
                                                                                            </a>
                                                                                        </h4>
                                                                                    @endif
                                                                                @endif
                                                                            </div>
                                                                        @endforeach
                                                                        @endisset
                                                                    @else
                                                                        <div class="col-xl-12 d-none"></div>
                                                                    @endif
                                                                    
                                                                    <input type="hidden" name="more_acc_hotel_CityName[]" id="more_acc_hotel_CityName{{ $count }}" value="{{ $details->more_acc_hotel_CityName ?? '' }}">
                                                                    <input type="hidden" name="more_acc_hotel_HotelName[]" id="more_acc_hotel_HotelName{{ $count }}" value="{{ $details->more_acc_hotel_HotelName ?? '' }}">
                                                                    <input type="hidden" name="more_acc_hotel_CheckIn[]" id="more_acc_hotel_CheckIn{{ $count }}" value="{{ $details->more_acc_hotel_CheckIn ?? '' }}">
                                                                    <input type="hidden" name="more_acc_hotel_CheckOut[]" id="more_acc_hotel_CheckOut{{ $count }}" value="{{ $details->more_acc_hotel_CheckOut ?? '' }}">
                                                                    <input type="hidden" name="more_acc_hotel_NoOfNights[]" id="more_acc_hotel_NoOfNights{{ $count }}" value="{{ $details->more_acc_hotel_NoOfNights ?? '' }}">
                                                                    <input type="hidden" name="more_acc_hotel_Quantity[]" id="more_acc_hotel_Quantity{{ $count }}" value="{{ $details->more_acc_hotel_Quantity ?? '' }}">
                                                                    
                                                                    <div class="col-xl-12">
                                                                        <input type="text" name="more_hotel_name_markup[]" hidden id="more_hotel_name_markup{{$count}}">
                                                                        @if(isset($details->more_acc_hotel_CityName) && $details->more_acc_hotel_CityName != null && $details->more_acc_hotel_CityName != '')
                                                                            <h4> 
                                                                                More Accomodation Details {{ $details->more_acc_hotel_CityName ?? '' }} - <a id="new_MAHN_{{ $count }}">{{ $accomodation_details_moreS->more_acc_hotel_name ?? '' }}</a>
                                                                                <a class="btn">
                                                                                    (Quantity : <b style="color: #cdc0c0;">{{ $details->more_acc_hotel_Quantity ?? '' }}</b>)
                                                                                    (Check in : <b style="color: #cdc0c0;">{{ $details->more_acc_hotel_CheckIn ?? '' }}</b>)
                                                                                    (Check Out : <b style="color: #cdc0c0;">{{ $details->more_acc_hotel_CheckOut ?? '' }}</b>)
                                                                                    (Nights : <b style="color: #cdc0c0;">{{ $details->more_acc_hotel_NoOfNights ?? '' }}</b>)
                                                                                </a>
                                                                            </h4>
                                                                        @else
                                                                            <h4>
                                                                                More Accomodation Cost {{ $details->more_acc_hotel_CityName ?? '' }} - <a id="new_MAHN_{{ $count }}"></a>
                                                                                <a class="btn" id="more_acc_cost_html_{{ $count }}"></a>
                                                                            </h4>
                                                                        @endif
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3">
                                                                        <input type="hidden" id="more_hotel_Type_Costing" name="more_markup_Type_Costing[]" value="more_hotel_Type_Costing" class="form-control">
                                                                        <label>Room Type</label>
                                                                        <input type="text" id="more_hotel_acc_type_{{$count}}" readonly="" value="{{$details->more_room_type}}" name="more_room_type[]" class="form-control">
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3">
                                                                        <label>Price Per Room/Night</label>
                                                                        <div class="input-group">
                                                                            <input type="text" id="more_hotel_acc_price_per_night_{{ $count }}" readonly="" name="more_without_markup_price_single[]" class="form-control" value="{{ $details->more_without_markup_price_single }}">
                                                                            <span class="input-group-btn input-group-append">
                                                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                                                            </span>        
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3">
                                                                        <label>Cost Price/Room</label>
                                                                        <div class="input-group">
                                                                            <input type="text" id="more_hotel_acc_price_{{$count}}" readonly="" value="{{$details->more_without_markup_price}}" name="more_without_markup_price[]" class="form-control">
                                                                            <span class="input-group-btn input-group-append">
                                                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                                                            </span>
                                                                            
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3">
                                                                        <label>Markup Type</label>
                                                                        <select name="more_markup_type[]" onchange="more_hotel_markup_type_accI({{$count}})" id="more_hotel_markup_types_{{$count}}" class="form-control">
                                                                            <option value="">Markup Type</option>
                                                                            <option <?php if($details->more_markup_type == '%' ) echo "selected";?> value="%">Percentage</option>
                                                                            <option <?php if($details->more_markup_type == $currency ) echo "selected";?> value="<?php echo $currency; ?>">Fixed Amount</option>
                                                                            <option <?php if($details->more_markup_type == 'per_Night' ) echo "selected";?> value="per_Night">Per Night</option>
                                                                        </select>
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3 more_markup_value_Div_{{ $count }}" style="display:none;margin-top:10px">
                                                                        <label>Markup Value</label>
                                                                        <input type="hidden" id="" name="" class="form-control">
                                                                        <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                            <input type="text"  class="form-control" id="more_hotel_markup_{{$count}}" value="{{$details->more_markup}}" name="more_markup[]" onkeyup="get_markup_invoice_price({{ $count }})">
                                                                            <span class="input-group-btn input-group-append">
                                                                                <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="more_hotel_markup_mrk_{{$count}}" class="currency_value1">%</div></button>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3 more_exchnage_rate_Div_{{ $count }}" style="display:none;margin-top:10px">
                                                                        <label>Exchange Rate</label>
                                                                        <div class="input-group">
                                                                            <input type="text" id="more_hotel_exchage_rate_per_night_{{ $count }}" readonly="" name="more_exchage_rate_single[]" class="form-control" value="{{ $details->more_exchage_rate_single }}">    
                                                                            <span class="input-group-btn input-group-append">
                                                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value1"></a>
                                                                            </span>        
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3 more_markup_price_Div_{{ $count }}" style="display:none;margin-top:10px">
                                                                        <label>Markup Price</label>
                                                                        <div class="input-group">
                                                                            <input type="text" id="more_hotel_exchage_rate_markup_total_per_night_{{ $count }}" readonly="" name="more_markup_total_per_night[]" class="form-control" value="{{ $details->more_markup_total_per_night }}"> 
                                                                            <span class="input-group-btn input-group-append">
                                                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3 more_markup_total_price_Div_{{ $count }}" style="display:none;margin-top:10px">
                                                                        <label>Markup Total Price</label>
                                                                        <div class="input-group">
                                                                            <input type="text" id="more_hotel_markup_total_{{$count}}"  name="more_markup_price[]" value="{{$details->more_markup_price}}" class="form-control">
                                                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                    <?php
                                                        $count=$count+1;
                                                            }
                                                        }
                                                    ?>
                                                @endif
                                            </div>
                                            
                                            <div class="row" id="transportation_cost" style="display:none;">
                                            
                                                @if($get_invoice->markup_details != null)
                                                    <?php
                                                        $markup_details = $get_invoice->markup_details;
                                                        $markup_details = json_decode($markup_details);
                                                    ?>
                                                    @foreach($markup_details as $details)
                                                        @if($details->markup_Type_Costing == 'transportation_Type_Costing')
                                                        
                                                        
                                                            <div class="col-xl-3">
                                                                <h4>Transportation Cost</h4>
                                                            </div>
                                                            
                                                            <input type="hidden" name="acc_hotel_CityName[]">
                                                            <input type="hidden" name="acc_hotel_HotelName[]">
                                                            <input type="hidden" name="acc_hotel_CheckIn[]">
                                                            <input type="hidden" name="acc_hotel_CheckOut[]" >
                                                            <input type="hidden" name="acc_hotel_NoOfNights[]">
                                                            <input type="hidden" name="acc_hotel_Quantity[]">
                                                            
                                                            <input type="hidden" name="tranf_no_of_vehicle[]" id="tranf_no_of_vehicle" value="{{ $details->tranf_no_of_vehicle ?? '' }}">
                                                            <input type="hidden" name="tranf_price_per_vehicle[]" id="tranf_price_per_vehicle" value="{{ $details->tranf_price_per_vehicle ?? '' }}">
                                                            <input type="hidden" name="tranf_price_all_vehicle[]" id="tranf_price_all_vehicle" value="{{ $details->tranf_price_all_vehicle ?? '' }}">
                                                            
                                                            <div class="col-xl-9">
                                                                <input type="hidden" value="{{ $details->markup_Type_Costing ?? '' }}" id="transportation_Type_Costing" name="markup_Type_Costing[]" value="transportation_Type_Costing" class="form-control">  
                                                            </div>
                                                            
                                                            <div class="col-xl-2">
                                                                <input type="text" value="{{ $details->hotel_name_markup ?? '' }}" id="transportation_pick_up_location_select" readonly="" name="hotel_name_markup[]" class="form-control">
                                                            </div>
                                                            
                                                            <div class="col-xl-2">
                                                                <input type="text" value="{{ $details->room_type ?? '' }}" id="transportation_drop_off_location_select" readonly="" name="room_type[]" class="form-control">
                                                            </div>
                                                            
                                                            <div class="col-xl-2">
                                                                <div class="input-group">
                                                                    <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2T"></a></span>
                                                                    <input type="text" value="{{ $details->without_markup_price ?? '' }}" id="transportation_price_per_person_select" readonly="" name="without_markup_price[]" class="form-control">
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-xl-2">
                                                                <select name="markup_type[]" id="transportation_markup_type" class="form-control">
                                                                    <option value="">Markup Type</option>
                                                                    <option <?php if($details->markup_type == '%') echo 'selected'; ?> value="%">Percentage</option>
                                                                    <option <?php if($details->markup_type == $currency) echo 'selected'; ?> value="<?php echo $currency; ?>">Fixed Amount</option>
                                                                </select>
                                                            </div>
                                                            
                                                            <div class="col-xl-2">
                                                                <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                    <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2T"></a></span>
                                                                    <input type="text" value="{{ $details->markup ?? '' }}" class="form-control" id="transportation_markup" name="markup[]" readonly>
                                                                    <span class="input-group-btn input-group-append d-none">
                                                                        <button class="btn btn-primary bootstrap-touchspin-up" type="button">
                                                                            <div id="transportation_markup_mrk currency_value_exchange_2T">
                                                                                <?php 
                                                                                    if($details->markup_type == '%'){
                                                                                        echo '%';
                                                                                    }else if($details->markup_type == $currency){
                                                                                        echo $currency;
                                                                                    }else{
                                                                                        echo '';
                                                                                    }
                                                                                ?>
                                                                            </div>
                                                                        </button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-xl-2">
                                                                <div class="input-group">
                                                                    <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2T"></a></span>
                                                                    <input type="text" value="{{ $details->markup_price ?? '' }}" id="transportation_markup_total" name="markup_price[]" class="form-control" readonly>
                                                                </div>
                                                            </div>
                                                        
                                                        @endif
                                                    @endforeach
                                                @endif
                                                
                                            </div>
                                            
                                            @if(isset($get_invoice->visa_type) && $get_invoice->visa_type != null && $get_invoice->visa_type != '')
                                                <div class="row" id="visa_cost">
                                                    <div class="col-xl-3">
                                                        <h4 class="" id="">Visa Cost</h4>
                                                    </div>
                                                    <input type="hidden" name="acc_hotel_CityName[]">
                                                    <input type="hidden" name="acc_hotel_HotelName[]">
                                                    <input type="hidden" name="acc_hotel_CheckIn[]">
                                                    <input type="hidden" name="acc_hotel_CheckOut[]" >
                                                    <input type="hidden" name="acc_hotel_NoOfNights[]">
                                                    <input type="hidden" name="acc_hotel_Quantity[]">
                                                    <?php $markup_detailsE = $get_invoice->markup_details; ?>
                                                    @if(isset($markup_detailsE) && $markup_detailsE != null && $markup_detailsE != '')
                                                        <?php $markup_details     = json_decode($markup_detailsE); ?>
                                                        @foreach($markup_details as $value)
                                                            <?php $markup_Type_Costing = $value->markup_Type_Costing; ?>
                                                            @if(isset($markup_Type_Costing) && $markup_Type_Costing != null && $markup_Type_Costing != '')
                                                                @if($markup_Type_Costing == 'visa_Type_Costing')
                                                                    <div class="col-xl-9">
                                                                        <input type="hidden" id="visa_Type_Costing" name="markup_Type_Costing[]" value="{{ $markup_Type_Costing }}" class="form-control">
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3">
                                                                        <input readonly type="text" id="visa_type_select" name="hotel_name_markup[]" class="form-control" value="{{ $value->hotel_name_markup }}">
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-2" style="display:none">
                                                                        <div class="input-group">
                                                                            <input type="text" id="visa_price_per_night" readonly="" name="without_markup_price_single[]" class="form-control">    
                                                                            <span class="input-group-btn input-group-append">
                                                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value1"></a>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-3">
                                                                        <div class="input-group">
                                                                            <input readonly type="text" id="visa_price_select" name="without_markup_price[]" class="form-control" value="{{ $value->without_markup_price }}">
                                                                            <span class="input-group-btn input-group-append">
                                                                                <a class="btn btn-primary bootstrap-touchspin-up">
                                                                                   <?php echo $currency; ?>
                                                                                </a>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-2">
                                                                        <select name="markup_type[]" id="visa_markup_type" class="form-control">
                                                                            <option <?php if($value->markup_type == '') echo "selected" ?> value="">Markup Type</option>
                                                                            <option <?php if($value->markup_type == '%') echo "selected" ?> value="%">Percentage</option>
                                                                            <option <?php if($value->markup_type == $currency) echo "selected" ?> value="<?php echo $currency; ?>">Fixed Amount</option>
                                                                        </select>
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-2">
                                                                        <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                            <input type="text" class="form-control" id="visa_markup" name="markup[]" value="{{ $value->markup }}">
                                                                            <span class="input-group-btn input-group-append">
                                                                                <button class="btn btn-primary bootstrap-touchspin-up" type="button">
                                                                                    <div id="visa_mrk">
                                                                                        <?php 
                                                                                            if($value->markup_type == '%'){
                                                                                                echo '%';
                                                                                            }else if($value->markup_type == $currency){
                                                                                                echo $currency;
                                                                                            }else{
                                                                                                echo '';
                                                                                            }
                                                                                        ?>
                                                                                    </div>
                                                                                </button>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-1" style="display:none">
                                                                        <input type="text" id="visa_exchage_rate_per_night" readonly="" name="exchage_rate_single[]" class="form-control">    
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-2" style="display:none">
                                                                        <div class="input-group">
                                                                            <input type="text" id="visa_exchage_rate_markup_total_per_night" readonly="" name="markup_total_per_night[]" class="form-control">
                                                                            <span class="input-group-btn input-group-append">
                                                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                                                            </span>
                                                                        </div> 
                                                                    </div>
                                                                    
                                                                    <div class="col-xl-2">
                                                                        <div class="input-group">
                                                                            <input type="text" id="total_visa_markup" name="markup_price[]" class="form-control" value="{{ $value->markup_price }}">
                                                                            <span class="input-group-btn input-group-append">
                                                                                <a class="btn btn-primary bootstrap-touchspin-up">
                                                                                   <?php echo $currency; ?>
                                                                                </a>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                    
                                                </div>
                                                
                                                <div id="more_visa_cost_div" class="row">
                                                    <input type="hidden" name="acc_hotel_CityName[]">
                                                    <input type="hidden" name="acc_hotel_HotelName[]">
                                                    <input type="hidden" name="acc_hotel_CheckIn[]">
                                                    <input type="hidden" name="acc_hotel_CheckOut[]" >
                                                    <input type="hidden" name="acc_hotel_NoOfNights[]">
                                                    <input type="hidden" name="acc_hotel_Quantity[]">
                                                    <?php $markup_detailsE = $get_invoice->markup_details; ?>
                                                    @if(isset($markup_detailsE) && $markup_detailsE != null && $markup_detailsE != '')
                                                        <?php $markup_details = json_decode($markup_detailsE); $m_VC_count = 1; ?>
                                                        @foreach($markup_details as $value)
                                                            <?php $markup_Type_Costing = $value->markup_Type_Costing; ?>
                                                            @if(isset($markup_Type_Costing) && $markup_Type_Costing != null && $markup_Type_Costing != '')
                                                                @if($markup_Type_Costing == 'more_visa_Type_Costing')
                                                                    <div id="more_visa_cost_{{ $m_VC_count }}" class="row">
                                                                        <div class="col-xl-3">
                                                                            <h4 class="" id="">More Visa Cost</h4>
                                                                        </div>
                                                                        
                                                                        <div class="col-xl-9">
                                                                            <input type="hidden" id="more_visa_Type_Costing_{{ $m_VC_count }}" name="markup_Type_Costing[]" value="{{ $markup_Type_Costing }}" class="form-control">
                                                                        </div>
                                                                        
                                                                        <div class="col-xl-3">
                                                                            <input readonly type="text" id="more_visa_type_select_{{ $m_VC_count }}" name="hotel_name_markup[]" class="form-control" value="{{ $value->hotel_name_markup }}">
                                                                        </div>
                                                                        
                                                                        <div class="col-xl-3">
                                                                            <div class="input-group">
                                                                                <input readonly type="text" id="more_visa_price_select_{{ $m_VC_count }}" name="without_markup_price[]" class="form-control" value="{{ $value->without_markup_price }}">
                                                                                <span class="input-group-btn input-group-append">
                                                                                    <a class="btn btn-primary bootstrap-touchspin-up">
                                                                                       <?php echo $currency; ?>
                                                                                    </a>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class="col-xl-2">
                                                                            <select name="markup_type[]" id="more_visa_markup_type_{{ $m_VC_count }}" class="form-control">
                                                                                <option <?php if($value->markup_type == '') echo "selected" ?> value="">Markup Type</option>
                                                                                <option <?php if($value->markup_type == '%') echo "selected" ?> value="%">Percentage</option>
                                                                                <option <?php if($value->markup_type == $currency) echo "selected" ?> value="<?php echo $currency; ?>">Fixed Amount</option>
                                                                            </select>
                                                                        </div>
                                                                        
                                                                        <div class="col-xl-2">
                                                                            <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                                <input type="text" class="form-control" id="more_visa_markup_{{ $m_VC_count }}" name="markup[]" value="{{ $value->markup }}">
                                                                                <span class="input-group-btn input-group-append">
                                                                                    <button class="btn btn-primary bootstrap-touchspin-up" type="button">
                                                                                        <div id="visa_mrk">
                                                                                            <?php 
                                                                                                if($value->markup_type == '%'){
                                                                                                    echo '%';
                                                                                                }else if($value->markup_type == $currency){
                                                                                                    echo $currency;
                                                                                                }else{
                                                                                                    echo '';
                                                                                                }
                                                                                            ?>
                                                                                        </div>
                                                                                    </button>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class="col-xl-2">
                                                                            <div class="input-group">
                                                                                <input type="text" id="more_total_visa_markup_{{ $m_VC_count }}" name="markup_price[]" class="form-control" value="{{ $value->markup_price }}">
                                                                                <span class="input-group-btn input-group-append">
                                                                                    <a class="btn btn-primary bootstrap-touchspin-up">
                                                                                       <?php echo $currency; ?>
                                                                                    </a>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <?php $m_VC_count++; ?>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            @else
                                                <div class="row" id="visa_cost" style="display:none;">
                                                    <div class="col-xl-3">
                                                        <h4 class="" id="">Visa Cost</h4>
                                                    </div>
                                                    <input type="hidden" name="acc_hotel_CityName[]">
                                                    <input type="hidden" name="acc_hotel_HotelName[]">
                                                    <input type="hidden" name="acc_hotel_CheckIn[]">
                                                    <input type="hidden" name="acc_hotel_CheckOut[]" >
                                                    <input type="hidden" name="acc_hotel_NoOfNights[]">
                                                    <input type="hidden" name="acc_hotel_Quantity[]">
                                                
                                                    <div class="col-xl-9">
                                                        <input type="hidden" id="visa_Type_Costing" name="markup_Type_Costing[]" value="visa_Type_Costing" class="form-control">
                                                    </div>
                                                    
                                                    <div class="col-xl-3">
                                                        <input readonly type="text" id="visa_type_select" name="hotel_name_markup[]" class="form-control">
                                                    </div>
                                                    
                                                    <div class="col-xl-2" style="display:none">
                                                        <div class="input-group">
                                                            <input type="text" id="visa_price_per_night" readonly="" name="without_markup_price_single[]" class="form-control">    
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value1"></a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-xl-3">
                                                        <div class="input-group">
                                                            <input readonly type="text" id="visa_price_select" name="without_markup_price[]" class="form-control">
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up">
                                                                   <?php echo $currency; ?>
                                                                </a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                        <select name="markup_type[]" id="visa_markup_type" class="form-control">
                                                            <option value="">Markup Type</option>
                                                            <option value="%">Percentage</option>
                                                            <option value="<?php echo $currency; ?>">Fixed Amount</option>
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                        <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                            <input type="text" class="form-control" id="visa_markup" name="markup[]">
                                                            <span class="input-group-btn input-group-append">
                                                                <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="visa_mrk">%</div></button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-xl-1" style="display:none">
                                                        <input type="text" id="visa_exchage_rate_per_night" readonly="" name="exchage_rate_single[]" class="form-control">    
                                                    </div>
                                                    
                                                    <div class="col-xl-2" style="display:none">
                                                        <div class="input-group">
                                                            <input type="text" id="visa_exchage_rate_markup_total_per_night" readonly="" name="markup_total_per_night[]" class="form-control">
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                                            </span>
                                                        </div> 
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                        <div class="input-group">
                                                            <input type="text" id="total_visa_markup" name="markup_price[]" class="form-control">
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up">
                                                                   <?php echo $currency; ?>
                                                                </a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                
                                                </div>
                                                
                                                <div id="more_visa_cost_div"></div>
                                            @endif
                                                
                                        </div>    
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        
                        <div class="row" >
                            <div class="col-xl-12" style="display:none;" id="all_services_markup">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="modal-title" id="standard-modalLabel">Markup on total price</h4>
                                        <div class="card-body">
                                           <div class="row">
                                               
                                                <div class="col-xl-6">
                                                    <div class="mb-3">
                                                        <label for="all_markup_type" class="form-label">Markup Type</label>
                                                        <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Markup type"></i>
                                                         <select class="form-control" id="all_markup_type" name="all_markup_type" >
                                                            <option>Select Markup Type</option>
                                                            <option value="%">Percentage</option>
                                                            <option value="<?php echo $currency; ?>">Number</option>
                                                        </select>
                                                    </div>
                                                </div>
                            
                                                <div class="col-xl-6">
                                                    <div class="mb-3">
                                                        <label for="all_markup_add" class="form-label">All Markup</label>
                                                        <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="All Markup"></i>
                                                        <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                            <input type="text"  class="form-control" id="all_markup_add" name="all_markup_add">
                                                            <span class="input-group-btn input-group-append">
                                                                <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="select_mrk">%</div></button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                            
                                           </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>
                        
                        <div id="accomodation_price_hide" class="row mt-2" style="display:none">
                            
                            <div class="col-xl-4">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Quad Cost Price</label>
                                    <i class="dripicons-information" style="font-size: 17px;"  id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Quad Cost Price"></i>
                                    <div class="input-group">
                                        <input class="form-control" id="quad_cost_price" value="{{$get_invoice->quad_cost_price ?? ''}}" name="quad_cost_price" />
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                               
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="col-xl-4">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Triple Cost Price</label>
                                    <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Triple Cost Price"></i>
                                    <div class="input-group">
                                        <input class="form-control" id="triple_cost_price" value="{{$get_invoice->triple_cost_price ?? ''}}" name="triple_cost_price" />
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                               
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="col-xl-4">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Double Cost Price</label>
                                    <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Double Cost Price"></i>
                                    <div class="input-group">
                                        <input class="form-control" id="double_cost_price" value="{{$get_invoice->double_cost_price ?? ''}}" name="double_cost_price" />
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                               
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        
                        </div>
                        
                        <div class="row" id="sale_pr" style="display:none">
                            
                            <div class="col-xl-4">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label quad_grand_total_amount">Quad Sale Price</label>
                                    <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Quad Sale Price"></i>
                                    
                                    <div class="input-group">
                                        <!--<input class="form-control" id="quad_grand_total_amount" name="quad_grand_total_amount" />-->
                                        <input type="text" name="quad_grand_total_amount_single" value="{{$get_invoice->quad_grand_total_amount ?? ''}}" class="form-control" id="quad_grand_total_amount">
                                        
                                                <span class="input-group-btn input-group-append">
                                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                                      
                                                    </a>
                                                </span>
                                        <div class="invalid-feedback">
                                            This Field is Required
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-xl-4">
                                <div class="mb-3">
                                    <label for="simpleinput triple_grand_total_amount" class="form-label">Triple Sale Price</label>
                                    <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Triple Sale Price"></i>
                                    <div class="input-group">
                                        <!--<input class="form-control" id="triple_grand_total_amount" name="triple_grand_total_amount" />-->
                                        <input type="text" name="triple_grand_total_amount_single" value="{{$get_invoice->triple_grand_total_amount ?? ''}}" class="form-control" id="triple_grand_total_amount">
                                        
                                                <span class="input-group-btn input-group-append">
                                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                                      
                                                    </a>
                                                </span>
                                        <div class="invalid-feedback">
                                            This Field is Required
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-xl-4">
                                <div class="mb-3">
                                    <label for="simpleinput double_grand_total_amount" class="form-label">Double Sale Price</label>
                                    <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Double Sale Price"></i>
                                    <div class="input-group">
                                        <!--<input class="form-control" id="double_grand_total_amount" name="double_grand_total_amount" />-->
                                        <input type="text" name="double_grand_total_amount_single" value="{{$get_invoice->double_grand_total_amount ?? ''}}" class="form-control" id="double_grand_total_amount">
                                        
                                                <span class="input-group-btn input-group-append">
                                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                                      
                                                    </a>
                                                </span>
                                        <div class="invalid-feedback">
                                            This Field is Required
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                <h4 class="modal-title" id="standard-modalLabel">Final Prices</h4>
                            </div>
                            <div class="card-body">
                                
                                <?php $all_costing_details = json_decode($get_invoice->all_costing_details); // dd($all_costing_details);?>
                                
                                <div id="accomodation_price_hide" class="row mt-2">
                                    
                                    <div class="col-xl-2">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Quad Cost Price</label>
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Quad Cost Price"></i>
                                            <div class="input-group">
                                                <input class="form-control" id="quad_cost_price" name="quad_cost_price" value="{{ $all_costing_details->quad_cost_price }}">
                                                <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-2">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Triple Cost Price</label>
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Triple Cost Price"></i>
                                            <div class="input-group">
                                                <input class="form-control" id="triple_cost_price" name="triple_cost_price" value="{{ $all_costing_details->triple_cost_price }}">
                                                <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-2">
                                        <div class="mb-3">
                                            <label for="" class="form-label">Double Cost Price</label>
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Double Cost Price"></i>
                                            <div class="input-group">
                                                <input class="form-control" id="double_cost_price" name="double_cost_price" value="{{ $all_costing_details->double_cost_price }}">
                                                <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-2">
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Without Acc Cost</label>
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Double Cost Price"></i>
                                            <div class="input-group">
                                                <input class="form-control" id="without_acc_cost_price" name="without_acc_cost_price"  value="{{ $all_costing_details->without_acc_cost_price }}">
                                                <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-2">
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label quad_grand_total_amount">Child Cost Price</label>
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Quad Sale Price"></i>
                                            <div class="input-group">
                                                <input type="text" name="child_total_cost_price" class="form-control" id="child_total_cost_price" value="{{ $all_costing_details->child_total_cost_price }}">
                                                <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-2">
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label quad_grand_total_amount">Infant Cost Price</label>
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Quad Sale Price"></i>
                                            <div class="input-group">
                                                <input type="text" name="infant_total_cost_price" class="form-control" id="infant_total_cost_price" value="{{ $all_costing_details->infant_total_cost_price }}">
                                                <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                
                                <div class="row" id="sale_pr">
                                    
                                    <div class="col-xl-2">
                                        <div class="mb-3">
                                            <label for="" class="form-label quad_grand_total_amount">Quad Sale Price</label>
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Quad Sale Price"></i>
                                            
                                            <div class="input-group">
                                                <!--<input class="form-control" id="quad_grand_total_amount" name="quad_grand_total_amount" />-->
                                                <input type="text" name="quad_grand_total_amount_single" class="form-control" id="quad_grand_total_amount" onkeyup="quad_sale_markup()" value="{{ $all_costing_details->quad_grand_total_amount }}">
                                                <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-2">
                                        <div class="mb-3">
                                            <label for="triple_grand_total_amount" class="form-label">Triple Sale Price</label>
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Triple Sale Price"></i>
                                            <div class="input-group">
                                                <!--<input class="form-control" id="triple_grand_total_amount" name="triple_grand_total_amount" />-->
                                                <input type="text" name="triple_grand_total_amount_single" class="form-control" id="triple_grand_total_amount" onkeyup="triple_sale_markup()" value="{{ $all_costing_details->triple_grand_total_amount }}">
                                                
                                                <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-2">
                                        <div class="mb-3">
                                            <label for="double_grand_total_amount" class="form-label">Double Sale Price</label>
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Double Sale Price"></i>
                                            <div class="input-group">
                                                <input type="text" name="double_grand_total_amount_single" class="form-control" id="double_grand_total_amount" onkeyup="double_sale_markup()" value="{{ $all_costing_details->double_grand_total_amount }}">
                                                <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-2">
                                        <div class="mb-3">
                                            <label for="simpleinput double_grand_total_amount" class="form-label">Without Acc Sale</label>
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Double Sale Price"></i>
                                            <div class="input-group">
                                                <input type="text" name="without_acc_sale_price_single" class="form-control" id="without_acc_sale_price" onkeyup="without_acc_sale_markup()" value="{{ $all_costing_details->without_acc_sale_price_single }}">
                                                <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-2">
                                        <div class="mb-3">
                                            <label for="simpleinput triple_grand_total_amount" class="form-label">Child Sale Price</label>
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Triple Sale Price"></i>
                                            <div class="input-group">
                                                <input type="text" name="child_total_sale_price" class="form-control" id="child_total_sale_price" onkeyup="child_sale_markup()" value="{{ $all_costing_details->child_total_sale_price }}">
                                                <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-2">
                                        <div class="mb-3">
                                            <label for="simpleinput triple_grand_total_amount" class="form-label">Infant Sale Price</label>
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Triple Sale Price"></i>
                                            <div class="input-group">
                                                <input type="text" name="infant_total_sale_price" class="form-control" id="infant_total_sale_price" onkeyup="infant_sale_markup()" value="{{ $all_costing_details->infant_total_sale_price }}">
                                                <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                
                                <div class="row" style="display:none;" id="markups_all">
                                    <div class="col-xl-2">
                                        <label class="form-label">Quad Markup</label>
                                        <input type="readonly" class="form-control" name="quad_total_markup" id="quad_total_markup" value="{{ $all_costing_details->quad_total_markup }}">
                                    </div>
                                    
                                    <div class="col-xl-2">
                                        <label class="form-label">Triple Markup</label>
                                        <input type="readonly" class="form-control" name="triple_total_markup" id="triple_total_markup" value="{{ $all_costing_details->triple_total_markup }}">
                                    </div>
                                    
                                    <div class="col-xl-2">
                                        <label class="form-label">Double Markup</label>
                                        <input type="readonly" class="form-control" name="double_total_markup" id="double_total_markup" value="{{ $all_costing_details->double_total_markup }}">
                                    </div>
                                    
                                    <div class="col-xl-2">
                                        <label class="form-label">Without Acc Markup</label>
                                        <input type="readonly" class="form-control" name="without_acc_total_markup" id="without_acc_total_markup" value="{{ $all_costing_details->without_acc_total_markup }}">
                                    </div>
                                    
                                    <div class="col-xl-2">
                                        <label class="form-label">Child Markup</label>
                                        <input type="readonly" class="form-control" name="child_total_markup" id="child_total_markup" value="{{ $all_costing_details->child_total_markup }}">
                                    </div>
                                    
                                    <div class="col-xl-2">
                                        <label class="form-label">Infant Markup</label>
                                        <input type="readonly" class="form-control" name="infant_total_markup" id="infant_total_markup" value="{{ $all_costing_details->infant_total_markup }}">
                                    </div>
                                </div>
                                
                                <div class="row" id="pax_all">
                                    <div class="col-xl-2">
                                        <label class="form-label">Quad Pax</label>
                                        <input type="readonly" class="form-control" name="quad_total_pax" id="quad_total_pax" onkeyup="quad_total_pax_fun()" value="{{ $all_costing_details->quad_total_pax }}">
                                    </div>
                                    
                                    <div class="col-xl-2">
                                        <label class="form-label">Triple Pax</label>
                                        <input type="readonly" class="form-control" name="triple_total_pax" id="triple_total_pax" onkeyup="triple_total_pax_fun()" value="{{ $all_costing_details->triple_total_pax }}">
                                    </div>
                                    
                                    <div class="col-xl-2">
                                        <label class="form-label">Double Pax</label>
                                        <input type="readonly" class="form-control" name="double_total_pax" id="double_total_pax" onkeyup="double_total_pax_fun()" value="{{ $all_costing_details->double_total_pax }}">
                                    </div>
                                    
                                    <div class="col-xl-2">
                                        <label class="form-label">Without Acc Pax</label>
                                        <input type="readonly" class="form-control" name="without_acc_total_pax" id="without_acc_total_pax" onkeyup="without_acc_total_pax_fun()" value="{{ $all_costing_details->without_acc_total_pax }}">
                                    </div>
                                    
                                    <div class="col-xl-2">
                                        <label class="form-label">Child Pax</label>
                                        <input type="readonly" class="form-control" name="child_total_pax" id="child_total_pax" onkeyup="child_total_pax_fun()" value="{{ $all_costing_details->child_total_pax }}">
                                    </div>
                                    
                                    <div class="col-xl-2">
                                        <label class="form-label">Infant Pax</label>
                                        <input type="readonly" class="form-control" name="infant_total_pax" id="infant_total_pax" onkeyup="infant_total_pax_fun()" value="{{ $all_costing_details->infant_total_pax }}">
                                    </div>
                                </div>
                                
                                <div class="row mt-2" id="with_pax_cost_all">
                                    <div class="col-xl-2">
                                        <label class="form-label">Quad Cost(Pax)</label>
                                        <div class="input-group">
                                            <input type="readonly" class="form-control" name="quad_cost_with_Pax" id="quad_cost_with_Pax" readonly required value="{{ $all_costing_details->quad_cost_with_Pax }}">
                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a></span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-2">
                                        <label class="form-label">Triple Cost(Pax)</label>
                                        <div class="input-group">
                                            <input type="readonly" class="form-control" name="triple_cost_with_Pax" id="triple_cost_with_Pax" readonly required value="{{ $all_costing_details->triple_cost_with_Pax }}">
                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a></span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-2">
                                        <label class="form-label">Double Cost(Pax)</label>
                                        <div class="input-group">
                                            <input type="readonly" class="form-control" name="double_cost_with_Pax" id="double_cost_with_Pax" readonly required value="{{ $all_costing_details->double_cost_with_Pax }}">
                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a></span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-2">
                                        <label class="form-label">Without Acc Cost(Pax)</label>
                                        <div class="input-group">
                                            <input type="readonly" class="form-control" name="without_acc_cost_with_Pax" id="without_acc_cost_with_Pax" readonly required value="{{ $all_costing_details->without_acc_cost_with_Pax }}">
                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a></span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-2">
                                        <label class="form-label">Child Cost(Pax)</label>
                                        <div class="input-group">
                                            <input type="readonly" class="form-control" name="child_cost_with_Pax" id="child_cost_with_Pax" readonly required value="{{ $all_costing_details->child_cost_with_Pax }}">
                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a></span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-2">
                                        <label class="form-label">Infant Cost(Pax)</label>
                                        <div class="input-group">
                                            <input type="readonly" class="form-control" name="infant_cost_with_Pax" id="infant_cost_with_Pax" readonly required value="{{ $all_costing_details->infant_cost_with_Pax }}">
                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mt-2" id="with_pax_prices_all">
                                    <div class="col-xl-2">
                                        <label class="form-label">Quad Price(Pax)</label>
                                        <div class="input-group">
                                            <input type="readonly" class="form-control" name="quad_total_with_Pax" id="quad_total_with_Pax" readonly required value="{{ $all_costing_details->quad_total_with_Pax }}">
                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a></span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-2">
                                        <label class="form-label">Triple Price(Pax)</label>
                                        <div class="input-group">
                                            <input type="readonly" class="form-control" name="triple_total_with_Pax" id="triple_total_with_Pax" readonly required value="{{ $all_costing_details->triple_total_with_Pax }}">
                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a></span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-2">
                                        <label class="form-label">Double Price(Pax)</label>
                                        <div class="input-group">
                                            <input type="readonly" class="form-control" name="double_total_with_Pax" id="double_total_with_Pax" readonly required value="{{ $all_costing_details->double_total_with_Pax }}">
                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a></span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-2">
                                        <label class="form-label">Without Acc Price(Pax)</label>
                                        <div class="input-group">
                                            <input type="readonly" class="form-control" name="without_acc_total_with_Pax" id="without_acc_total_with_Pax" readonly required value="{{ $all_costing_details->without_acc_total_with_Pax }}">
                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a></span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-2">
                                        <label class="form-label">Child Price(Pax)</label>
                                        <div class="input-group">
                                            <input type="readonly" class="form-control" name="child_total_with_Pax" id="child_total_with_Pax" readonly required value="{{ $all_costing_details->child_total_with_Pax }}">
                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a></span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-2">
                                        <label class="form-label">Infant Price(Pax)</label>
                                        <div class="input-group">
                                            <input type="readonly" class="form-control" name="infant_total_with_Pax" id="infant_total_with_Pax" readonly required value="{{ $all_costing_details->infant_total_with_Pax }}">
                                            <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row" id="markup_seprate_services" style="display:none;">
                                        <div class="col-xl-4">
                                            <div class="mb-3">
                                                <label for="simpleinput" class="form-label quad_markup">Quad Sale Price</label>
                                                <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Quad Sale Price"></i>
                                                <input class="form-control" id="quad_markup" name="quad_grand_total_amount" />
                                                <!--<input type="text" name="quad_grand_total_amount" class="form-control" id="quad_markup" required>-->
                                                <!--<div class="invalid-feedback">-->
                                                <!--    This Field is Required-->
                                                <!--</div>-->
                                            </div>
                                        </div>
                                        <div class="col-xl-4">
                                            <div class="mb-3">
                                                <label for="simpleinput" class="form-label triple_markup">Triple Sale Price</label>
                                                <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Triple Sale Price"></i>
                                                <input class="form-control" id="triple_markup" name="triple_grand_total_amount" />
                                                <!--<input type="text" name="triple_grand_total_amount" class="form-control" id="triple_markup" required>-->
                                                <!--<div class="invalid-feedback">-->
                                                <!--    This Field is Required-->
                                                <!--</div>-->
                                            </div>
                                        </div>
                                        <div class="col-xl-4">
                                            <div class="mb-3">
                                                <label for="simpleinput" class="form-label double_markup">Double Sale Price</label>
                                                <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Double Sale Price"></i>
                                                <input class="form-control" id="double_markup" name="double_grand_total_amount" />
                                                <!--<input type="text" name="double_grand_total_amount" class="form-control" id="double_markup" required>-->
                                                <!--<div class="invalid-feedback">-->
                                                <!--    This Field is Required-->
                                                <!--</div>-->
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        
                        <div class="row" style="display:none;" id="markup_seprate_services">
                                <div class="col-xl-4">
                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label quad_markup">Quad Sale Price</label>
                                        <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Quad Sale Price"></i>
                                        <input class="form-control" id="quad_markup" name="quad_grand_total_amount" />
                                        <!--<input type="text" name="quad_grand_total_amount" class="form-control" id="quad_markup" required>-->
                                        <!--<div class="invalid-feedback">-->
                                        <!--    This Field is Required-->
                                        <!--</div>-->
                                    </div>
                                </div>
                                <div class="col-xl-4">
                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label triple_markup">Triple Sale Price</label>
                                        <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Triple Sale Price"></i>
                                        <input class="form-control" id="triple_markup" name="triple_grand_total_amount" />
                                        <!--<input type="text" name="triple_grand_total_amount" class="form-control" id="triple_markup" required>-->
                                        <!--<div class="invalid-feedback">-->
                                        <!--    This Field is Required-->
                                        <!--</div>-->
                                    </div>
                                </div>
                                <div class="col-xl-4">
                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label double_markup">Double Sale Price</label>
                                        <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Double Sale Price"></i>
                                        <input class="form-control" id="double_markup" name="double_grand_total_amount" />
                                        <!--<input type="text" name="double_grand_total_amount" class="form-control" id="double_markup" required>-->
                                        <!--<div class="invalid-feedback">-->
                                        <!--    This Field is Required-->
                                        <!--</div>-->
                                    </div>
                                </div>
                            </div>
                        
                        <div class="row">

                            <div class="col-xl-6">
                                <div class="mb-3">
                                     <label for="simpleinput" class="form-label">Payment Methods</label>
                                     <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Payment GateWay"></i>
                                     <select class="form-control" id="payment_gateways" name="payment_gateways">
                                         <option value="">Select Payment Gateway</option>
                                         @if(isset($payment_gateways))
                                         @foreach($payment_gateways as $payment_gateways)
                                          <option <?php if($get_invoice->payment_gateways = $payment_gateways->payment_gateway_title) echo 'selected'; ?> value="{{$payment_gateways->payment_gateway_title}}">{{$payment_gateways->payment_gateway_title}}</option>
                                          @endforeach
                                          @endif
                                     </select>
                                
                                </div>
                            </div>
                            
                            <div class="col-xl-6">
                                <div class="mb-3">
                                    
                                    
                                <?php //dd($get_invoice->payment_modes); ?>
                                
                                     <label for="simpleinput" class="form-label">Payment Mode</label>
                                     <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Payment Mode"></i>
                                     <select class="select2 form-control select2-multiple external_packages" data-toggle="select2" multiple="multiple" id="payment_modes" name="payment_modes[]">
                                            <option value="">Select Payment Mode</option>
                                            @if(isset($get_invoice->payment_modes) && $get_invoice->payment_modes != null && $get_invoice->payment_modes != '' && $get_invoice->payment_modes != 'null')
                                                <?php $payment_modesDB = json_decode($get_invoice->payment_modes); //dd($payment_modesDB); ?>
                                                @foreach($payment_modesDB as $payment_modesDB1)
                                                    @if(isset($payment_modes))
                                                        @foreach($payment_modes as $payment_modes1)
                                                            <option <?php if($payment_modesDB1 == $payment_modes1->payment_mode) echo 'selected'; ?> value="{{$payment_modes1->payment_mode}}">{{$payment_modes1->payment_mode}}</option>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            @else
                                                @if(isset($payment_modes))
                                                    @foreach($payment_modes as $payment_modes1)
                                                        <option value="{{$payment_modes1->payment_mode}}">{{$payment_modes1->payment_mode}}</option>
                                                    @endforeach
                                                @endif
                                            @endif
                                     </select>
                                
                                 
                                  
                                
                                 
                                 
                                </div>
                            </div>
                        
                            <div class="col-xl-12">
                                <label for="">Payment Instructions</label>
                                <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Payment Instructions"></i>
                                <textarea name="checkout_message" class="form-control" cols="5" rows="5" value="{{ $account_No }}">{{ $account_No }}</textarea>
                            </div>
                        
                        </div>
                        
                        <!--<a id="save_Costing" class="btn btn-primary" name="submit">Save Costing Details</a>-->
                        <button style="float: right;width: 100px;margin-top: 10px;" type="submit" name="submit" class="btn btn-info deletButton">Submit</button>
                    </div>
                    
                    <div class="row" id="Submit_button" style="display:none">
                        <button style="float: right;width: 100px;margin-top: 10px;" type="submit" name="submit" class="btn btn-info deletButton">Confirm</button>
                    </div>
                    
                    <ul class="list-inline mb-0 wizard d-none" style="margin-top:60px;">
                        <li class="previous list-inline-item">
                            <a href="javascript:void(0);" style="width: 100px;" class="btn btn-info">Previous</a>
                        </li>
                        <li class="next list-inline-item float-end">
                            <a href="javascript:void(0);" style="width: 100px;" class="btn btn-info">Next</a>
                        </li>
                    </ul>
                    
                </div>
                
            </div> 
        </form>
    </div>
</div>

@endsection

@section('scripts')

<script src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initAutocomplete&language=nl&output=json&key=AIzaSyBmwlQFQKwxZ4D8nRbsWVRTBFUHMO-lUOY" async defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs5.min.js" integrity="sha512-6F1RVfnxCprKJmfulcxxym1Dar5FsT/V2jiEUvABiaEiFWoQ8yHvqRM/Slf0qJKiwin6IDQucjXuolCfCKnaJQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    
    function check_confirm_P(){
        var checkbox_Passport   = document.getElementById("checkbox_Passport");
        // var checkbox_Attol      = document.getElementById("checkbox_Attol");
        var checkbox_Iternary   = document.getElementById("checkbox_Iternary");
        // var checkbox_Deposit    = document.getElementById("checkbox_Deposit");
        checkbox_Passport.checked;
        
        if(checkbox_Passport.checked == true){
			$('#passport_details_div').css('display','');
			$('#passport_div_Upload').css('display','');
		}else{
		    $('#passport_details_div').css('display','none');
            $('#passport_div_Upload').css('display','none');
            $('#passport_div_wrriten').css('display','none');
            
            $('#first_Name_passport').val('');
            $('#last_Name_passport').val('');
            $('#nationality_passport').val('');
            $('#date_of_birth_passport').val('');
            $('#passport_Number').val('');
            $('#passport_Expiry').val('');
        }
        
        if(checkbox_Passport.checked == true && checkbox_Iternary.checked == true){
			$('#Submit_button').css('display','');
		}else{
            $('#Submit_button').css('display','none');
        }
    }
    
    function check_confirm_I(){
        
        var checkbox_Passport   = document.getElementById("checkbox_Passport");
        // var checkbox_Attol      = document.getElementById("checkbox_Attol");
        var checkbox_Iternary   = document.getElementById("checkbox_Iternary");
        // var checkbox_Deposit    = document.getElementById("checkbox_Deposit");
        checkbox_Iternary.checked;
        if(checkbox_Passport.checked == true && checkbox_Iternary.checked == true){
			$('#Submit_button').css('display','');
		}else{
            $('#Submit_button').css('display','none');
        }
    }
    
    function check_confirm_D(){
        
        var checkbox_Passport   = document.getElementById("checkbox_Passport");
        // var checkbox_Attol      = document.getElementById("checkbox_Attol");
        var checkbox_Iternary   = document.getElementById("checkbox_Iternary");
        // var checkbox_Deposit    = document.getElementById("checkbox_Deposit");
        checkbox_Deposit.checked;
        if(checkbox_Passport.checked == true && checkbox_Iternary.checked == true){
			$('#Submit_button').css('display','');
		}else{
            $('#Submit_button').css('display','none');
        }
    }

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
</script>

<!--Flights-->

<script>
    var all_flight_supplier         = {!!json_encode($flight_suppliers)!!};
    var all_flight_airline          = {!!json_encode($airline)!!};
    var all_flight_airline_codes    = {!!json_encode($airline_code)!!};
    var all_flight_routes           = {!!json_encode($all_flight_routes)!!};
    var flight_suppliers            = {!!json_encode($flight_suppliers)!!};
    var booking_id                  = {!!json_encode($get_invoice->id)!!};
    
    function changeairlineFunction(){
        var airline_name    =  $('#airline_data').val();
        var airline_id      =  $('#airline_data').find('option:selected').attr('attr-id');
        $('.other_Airline_Name2').val(airline_name);
        $('#departure_airline_id').val(airline_id);
    }
    
    function return_changeairlineFunction(){
        var airline_name    =  $('#return_airline_data').val();
        var airline_id      =  $('#return_airline_data').find('option:selected').attr('attr-id');
        $('.return_other_Airline_Name2').val(airline_name);
        $('#return_airline_id').val(airline_id);
    }

    $(".add_airline").click(function () {
       $('#add_airline_modal').modal("show");
    });
   
    $("#other_Airline_Name_submit").click(function () {
        var other_Airline_Name = $('#other_Airline_Name_class').val();
        $.ajax({    
            type: "POST",
            url: "{{URL::to('submitForm_Airline_Name')}}",
            data:{
                "_token"                : "{{ csrf_token() }}",
                'other_Airline_Name'    : other_Airline_Name,     
            },
            success: function(data){
                console.log(data);
            }
        });
   });
   
    function addGoogleApi(id){
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
    
    function total_price_cal_fun(){
        var adult_price = $('#flights_per_adult_price').val();
        var seats       = $('#flights_number_of_seat').val();
        if(adult_price){
            var adult_price = adult_price; 
        }else{
            var adult_price = 0; 
        }
        if(seats){
            var seats = seats; 
        }else{
            var seats = 0; 
        } 
        var price_addition  = parseFloat(adult_price);
        var multiple        = seats * price_addition;
        $('#flights_per_person_total_price').val(multiple);
    }
</script>

<script>
    function departure_supplier_id_fun(){
        $("#return_supplier_id").empty();
        var id      = $("#departure_supplier_id").find('option:selected').attr('value');
        var name    = $("#departure_supplier_id").find('option:selected').attr('attr-name');
        var data    = `<option value="${id}" selected>${name}</option>`;
        $("#return_supplier_id").append(data);
    }
    
    function flights_type_fun(){
        var id = $('#flights_type').find('option:selected').attr('value');
        
        // $('#flights_departure_code').val(id);
        if(id == 'Indirect'){
            
            var airline_data = $('#airline_data').val();
            $('#other_Airline_Name2').val(airline_data);
            
            $('#departure_airport_code').val('');
        	$('#arrival_airport_code').val('');
        	$('#departure_flight_number').val('');
        	$('#departure_time').val('');
        	$('#arrival_time').val('');
        	$('#departure_Flight_Type').val('');
            
            // $("#text_editer").css("padding", "20");
            $('#flights_type_connected').css('display','');
            $('#flights_type_connected').empty();
            var no_of_stays_Append = `<label for="no_of_stays">No Of Stops</label>
                                        <select  name="no_of_stays" id="no_of_stays" class="form-control select2" data-toggle="select2">
                                            <option value="">Choose...</option>
                                            <option value="2">1</option>
                                            <option value="3">2</option>
                                            <option value="4">3</option>
                                        </select>`;
            $('#flights_type_connected').append(no_of_stays_Append);
            // $('#text_editer').css('display','none');
            // $('#text_editer').css('display','');
            $('#stop_No').replaceWith($('<h3 style="width: 125px;margin-top: 25px;float:right" id="stop_No">Stop No 1 :</h3>'));
            $('#return_stop_No').replaceWith($('<h3 style="width: 182;margin-top: 25px;float:right" id="return_stop_No">Return Stop No 1 :</h3>'));

            $('.Flight_section').css('display','none');
            $('.return_Flight_section').css('display','none');
            $('#total_Time_Div').css('display','none');
            $('#return_total_Time_Div').css('display','none');
        }
        else{
            $('#no_of_stays').empty('');
            $('.Flight_section').css('display','');
            $('.return_Flight_section').css('display','');
            $('#total_Time_Div').css('display','');
            $('#return_total_Time_Div').css('display','');
            $('#flights_type_connected').css('display','none');
            $('.Flight_section_append').empty();
            $('.return_Flight_section_append').empty();
            // 	$('#text_editer').css('display','none');
        	$('#stop_No').replaceWith($('<h3 style="width: 125px;margin-top: 25px;float:right" id="stop_No">Direct :</h3>'));
        	$('#return_stop_No').replaceWith($('<h3 style="width: 162px;margin-top: 25px;float:right" id="return_stop_No">Return Direct :</h3>'));
        	addGoogleApi('departure_airport_code');
            addGoogleApi('arrival_airport_code');
            
            if(all_flight_airline_codes != null && all_flight_airline_codes != ''){
                $('#departure_airport_code').attr('readonly', true);
                $('#arrival_airport_code').attr('readonly', true);
                $('#departure_Flight_Type').attr('readonly', true);
                $('#departure_flight_number').attr('readonly', true);
                $('#departure_time').attr('readonly', true);
                $('#arrival_time').attr('readonly', true);
            }else{
                $('#departure_airport_code').attr('readonly', false);
                $('#arrival_airport_code').attr('readonly', false);
                $('#departure_Flight_Type').attr('readonly', false);
                $('#departure_flight_number').attr('readonly', false);
                $('#departure_time').attr('readonly', false);
                $('#arrival_time').attr('readonly', false);
            }
        }
    }
    
    function flights_type_connected_fun(){
        var no_of_stays = $('#no_of_stays').val();
        if(no_of_stays == 'NON_STOP'){
            $('.Flight_section_append').empty();
        }
        else{
            $('.Flight_section_append').empty();
            var no_of_stay_ID = 1;
            $flight_name = $('.airline_data').val();
            for (let i = 1; i <= no_of_stays; i++) {
                var flight_Data =   `<h3 style="">Departure Details : </h3>
                                    
                                    <div class="row" style="">
                            
                                        <div class="col-sm-12 col-md-3" style="padding: 10px;">
                                            <div>
                                                <label for="">Select Departure Airline Code</label>
                                                <div class="input-group">
                                                    <select name="departure_airline_code[]" required id="departure_airline_code_${i}" class="form-control">
                                                        <option attr="select" selected>Select Airline</option>
                                                        @if(isset($airline_code) && $airline_code != null && $airline_code != '')
                                                            @foreach($airline_code as $airline_codeS)
                                                                <option value="{{ $airline_codeS->fs }}">{{$airline_codeS->name}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-12 col-md-3" style="padding: 10px;">
                                            <label for="">Departure Airline Number</label>
                                            <input class="form-control" type="text" name="departure_airline_number[]" id="departure_airline_number_${i}">
                                        </div>
                                        
                                        <div class="col-sm-12 col-md-3" style="padding: 10px;">
                                            <label for="">Departure Airline Date</label>
                                            <input class="form-control" type="date" name="departure_airline_date[]" id="departure_airline_date_${i}">
                                        </div>
                                        
                                        <div class="col-sm-12 col-md-3" style="padding-top: 30px;">
                                            <button type="button" class="btn theme-bg-clr" onclick="get_departure_airline_details_I(${i})">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                        
                                    </div>
                
                                     <div class="row" style="">
                                        <div class="col-xl-4" style="padding: 10px">
                                            <label for="">Departure Airport</label>
                                            <input type="text" name="departure_airport_code[]" id="departure_airport_code_${i}" class="form-control" autocomplete="off" readonly>
                                        </div>
                                        <div class="col-xl-1" style="padding: 10px;margin-top: 25px;text-align: center;">
                                            <label for=""></label>
                                            <span id="Change_Location_${i}" class='bi bi-arrow-left-right' style="font-size: 23px;" onclick="change_location_I(${i})"></span>
                                        </div>
                                        <div class="col-xl-4" style="padding: 10px">
                                            <label for="">Arrival Airport</label>
                                            <input type="text" name="arrival_airport_code[]" id="arrival_airport_code_${i}" class="form-control" autocomplete="off" readonly>
                                        </div>
                                        <div class="col-xl-3" style="padding: 10px">
                                            <label for="">Airline Name</label>
                                            <input type="text" id="other_Airline_Name2_${i}" value="${$flight_name}" name="other_Airline_Name2[]" class="form-control other_airline_Name1_${i} other_Airline_Name2" readonly>
                                        </div>
                                        <div class="col-xl-3" style="padding: 10px">
                                            <label for="">Class Type</label>
                                            <input type="text" id="departure_Flight_Type_${i}" name="departure_Flight_Type[]" class="form-control departure_time1" readonly>
                                        </div>
                                        <div class="col-xl-3" style="padding: 10px">
                                            <label for="">Flight No</label>
                                            <input type="text" id="departure_flight_number_${i}" name="departure_flight_number[]" class="form-control" readonly>
                                        </div>
                                        <div class="col-xl-3" style="padding: 10px">
                                            <label for="">Departure Date & Time</label>
                                            <input type="datetime-local" id="departure_time_${i}" name="departure_time[]" class="form-control departure_time1_${i}" readonly>
                                        </div>
                                        <div class="col-xl-3" style="padding: 10px">
                                            <label for="">Arrival Date and Time</label>
                                            <input type="datetime-local" id="arrival_time_${i}" name="arrival_time[]" class="form-control arrival_time1_${i}" onchange="arrival_time_I(${i})" readonly>
                                        </div>
                                    </div>
                                    <div class="container" style="display:none;">
                                        <div class="row" style="margin-left: 303px;">
                                            <div class="col-sm-3">
                                                <h3 style="width: 140px;margin-top: 25px;float:right" id="no_of_stop_par_${i}">Stop No ${i}</h3>
                                            </div>
                                            <div class="col-sm-3">
                                                <label for="">Flight Duration</label>
                                                <input type="text" id="total_Time_${i}" name="total_Time[]" class="form-control total_Time1_${i}" style="width: 167px;" readonly>
                                            </div>
                                        </div>
                                    </div>`;
                                    
              
                                    
                $('.Flight_section_append').append(flight_Data);
                
                addGoogleApi('departure_airport_code_'+i+'');
                addGoogleApi('arrival_airport_code_'+i+'');
                
                $.ajax({    
                    type: "GET",
                    url: "get_other_Airline_Name",             
                    dataType: "html",                  
                    success: function(data){ 
                        var data1 = JSON.parse(data);
                        var data2 = JSON.parse(data1['airline_Name']);
                        // console.log(data2);
                    	$('#other_Airline_Name2_'+i+'').empty();
                    	$('#return_other_Airline_Name2_'+i+'').empty();
                        $.each(data2['airline_Name'], function(key, value) {
                            // console.log(value);
                            $('#other_Airline_Name2_'+i+'').append('<option attr=' +value.other_Airline_Name+ ' value=' +value.other_Airline_Name+ '>' +value.other_Airline_Name+'</option>');
                            $('#return_other_Airline_Name2_'+i+'').append('<option attr=' +value.other_Airline_Name+ ' value=' +value.other_Airline_Name+ '>' +value.other_Airline_Name+'</option>');
                        });  
                    }
                });
                
                $('#departure_airport_code_'+i+'').on('change',function () {
                    setTimeout(function() {
                        console.log("Working");
                        var address = $('#departure_airport_code_'+i+'').val();
                        $('#flights_arrival_code').val(address);
                        console.log(address);
                    }, 2000);
                });
                
                if(all_flight_airline_codes != null && all_flight_airline_codes != ''){
                    $('#departure_airport_code_'+i+'').attr('readonly', true);
                    $('#arrival_airport_code_'+i+'').attr('readonly', true);
                    $('#departure_Flight_Type_'+i+'').attr('readonly', true);
                    $('#departure_flight_number_'+i+'').attr('readonly', true);
                    $('#departure_time_'+i+'').attr('readonly', true);
                    $('#arrival_time_'+i+'').attr('readonly', true);
                }else{
                    $('#departure_airport_code_'+i+'').attr('readonly', false);
                    $('#arrival_airport_code_'+i+'').attr('readonly', false);
                    $('#departure_Flight_Type_'+i+'').attr('readonly', false);
                    $('#departure_flight_number_'+i+'').attr('readonly', false);
                    $('#departure_time_'+i+'').attr('readonly', false);
                    $('#arrival_time_'+i+'').attr('readonly', false);
                }
            }
        }
    }
    
    // Direct
    function direct_duration(){
        var second=1000, minute=second*60, hour=minute*60, day=hour*24, week=day*7;
        var h                   = "hours";
        var m                   = "minutes";
        var arrival_time1       = $('#arrival_time').val();
        var departure_time1     = $('#departure_time').val();
        var date1               = new Date(departure_time1);
        var date2               = new Date(arrival_time1);
        var timediff            = date2 - date1;
        var minutes_Total       = Math.floor(timediff / minute);
        var total_hours         = Math.floor(timediff / hour)
        var total_hours_minutes = parseFloat(total_hours) * 60;
        var minutes             = parseFloat(minutes_Total) - parseFloat(total_hours_minutes);
        $('#total_Time').val(total_hours+h+ ' : ' +minutes+m);
    }
    
    function Change_Location_fun(){
        var arrival_airport_code   = $('#arrival_airport_code').val();
        var departure_airport_code = $('#departure_airport_code').val();
        $('#arrival_airport_code').val(departure_airport_code);
        $('#departure_airport_code').val(arrival_airport_code);
    }
    
    function get_departure_airline_details(){
        
        var airline_data = $('#airline_data').val();
        $('#other_Airline_Name2').val(airline_data);
        
        $('#departure_airport_code').val('');
    	$('#arrival_airport_code').val('');
    	$('#departure_flight_number').val('');
    	$('#departure_time').val('');
    	$('#arrival_time').val('');
    	$('#departure_Flight_Type').val('');
        
        var departure_airline_code      = $('#departure_airline_code').val();
        var departure_airline_number    = $('#departure_airline_number').val();
        var departure_airline_date      = $('#departure_airline_date').val();
        var DA_date                     = new Date(departure_airline_date);
        
        var date1 = DA_date.getDate();
        if(date1 < 10) {
            date1 = `0${date1}`;
        }
        var month1 = DA_date.getMonth() + 1;
        if(month1 < 10) {
            month1 = `0${month1}`;
        }
        var year1 = DA_date.getFullYear();
        
        $.ajax({    
            type: "GEt",
            url: "{{URL::to('fetch_airline_data')}}",
            data:{
                "_token"                    : "{{ csrf_token() }}",
                'departure_airline_code'    : departure_airline_code,
                'departure_airline_number'  : departure_airline_number,
                'date1'                     : date1,
                'month1'                    : month1,
                'year1'                     : year1,
            },
            success: function(reponse){
                var data = reponse['data'];
                console.log(data);
                if(data['error']){
                    alert('Flight not available in these dates!');
                }else{
                    var appendix            = data['appendix']['airports'];
                    var scheduledFlights    = data['scheduledFlights'];
                    if(appendix.length > 0 && scheduledFlights.length > 0){
                        $('#departure_airport_code').val(appendix[0].name);
                        $('#arrival_airport_code').val(appendix[1].name);
                        $('#departure_Flight_Type').val(scheduledFlights[0].serviceType);
                        $('#departure_flight_number').val(scheduledFlights[0].flightNumber);
                        $('#departure_time').val(scheduledFlights[0].departureTime);
                        $('#arrival_time').val(scheduledFlights[0].arrivalTime);
                        direct_duration();
                    }else{
                        alert('Flight not available in these dates!');
                    }
                }
            }
        });
    }
    
    // Indirect
    function change_location_I(id){
        var arrival_airport_code   = $('#arrival_airport_code_'+id+'').val();
        var departure_airport_code = $('#departure_airport_code_'+id+'').val();
        
        console.log('departure_airport_code : '+departure_airport_code);
        
        $('#arrival_airport_code_'+id+'').val(departure_airport_code);
        $('#departure_airport_code_'+id+'').val(arrival_airport_code);
    }
    
    function arrival_time_I(id){
        var second=1000, minute=second*60, hour=minute*60, day=hour*24, week=day*7;
        var h                       = "hours";
        var m                       = "minutes";
        var arrival_time1           = $('#arrival_time_'+id+'').val();
        var departure_time1         = $('#departure_time_'+id+'').val();
        var date1                   = new Date(departure_time1);
        var date2                   = new Date(arrival_time1);
        var timediff                = date2 - date1;
        var minutes_Total           = Math.floor(timediff / minute);
        var total_hours             = Math.floor(timediff / hour)
        var total_hours_minutes     = parseFloat(total_hours) * 60;
        var minutes                 = parseFloat(minutes_Total) - parseFloat(total_hours_minutes);
        $('#total_Time_'+id+'').val(total_hours+h+ ' : ' +minutes+m);
    }
    
    function get_departure_airline_details_I(id){
        var airline_data = $('#airline_data').val();
        $('#other_Airline_Name2_'+id+'').val(airline_data);
        
        $('#departure_airport_code_'+id+'').val('');
    	$('#arrival_airport_code_'+id+'').val('');
    	$('#departure_Flight_Type_'+id+'').val('');
    	$('#departure_flight_number_'+id+'').val('');
    	$('#departure_time_'+id+'').val('');
    	$('#arrival_time_'+id+'').val('');
        
        var departure_airline_code      = $('#departure_airline_code_'+id+'').val();
        var departure_airline_number    = $('#departure_airline_number_'+id+'').val();
        var departure_airline_date      = $('#departure_airline_date_'+id+'').val();
        var DA_date                     = new Date(departure_airline_date);
        
        var date1 = DA_date.getDate();
        if(date1 < 10) {
            date1 = `0${date1}`;
        }
        var month1 = DA_date.getMonth() + 1;
        if(month1 < 10) {
            month1 = `0${month1}`;
        }
        var year1 = DA_date.getFullYear();
        
        $.ajax({    
            type: "GEt",
            url: "{{URL::to('fetch_airline_data')}}",
            data:{
                "_token"                    : "{{ csrf_token() }}",
                'departure_airline_code'    : departure_airline_code,
                'departure_airline_number'  : departure_airline_number,
                'date1'                     : date1,
                'month1'                    : month1,
                'year1'                     : year1,
            },
            success: function(reponse){
                var data = reponse['data'];
                console.log(data);
                if(data['error']){
                    alert('Flight not available in these dates!');
                }else{
                    var appendix            = data['appendix']['airports'];
                    var scheduledFlights    = data['scheduledFlights'];
                    if(appendix.length > 0 && scheduledFlights.length > 0){
                        $('#departure_airport_code_'+id+'').val(appendix[0].name);
                        $('#arrival_airport_code_'+id+'').val(appendix[1].name);
                        $('#departure_Flight_Type_'+id+'').val(scheduledFlights[0].serviceType);
                        $('#departure_flight_number_'+id+'').val(scheduledFlights[0].flightNumber);
                        $('#departure_time_'+id+'').val(scheduledFlights[0].departureTime);
                        $('#arrival_time_'+id+'').val(scheduledFlights[0].arrivalTime);
                        arrival_time_I(id);
                    }else{
                        alert('Flight not available in these dates!');
                    }
                }
            }
        });
    }
</script>

<script>
    
    function flights_type2_fun(){
        var id = $('#flights_type2').find('option:selected').attr('value');
        // $('#flights_departure_code').val(id);
        if(id == 'Indirect'){
            
            var return_airline_data = $('#return_airline_data').val();
            $('#return_other_Airline_Name2').val(return_airline_data);
            
            $('#return_departure_airport_code').val('');
        	$('#return_arrival_airport_code').val('');
        	$('#return_departure_flight_number').val('');
        	$('#return_departure_time').val('');
        	$('#return_arrival_time').val('');
        	$('#return_departure_Flight_Type').val('');
            
            // $("#text_editer2").css("padding", "20");
            $('#flights_type_connected2').css('display','');
            $('#flights_type_connected2').empty();
            var no_of_stays_Append = `<label for="no_of_stays">No Of Stops</label>
                                        <select  name="return_no_of_stays" id="no_of_stays2" class="form-control select2" data-toggle="select2">
                                            <option value="">Choose...</option>
                                            <option value="2">1</option>
                                            <option value="3">2</option>
                                            <option value="4">3</option>
                                        </select>`;
            $('#flights_type_connected2').append(no_of_stays_Append);
            // $('#text_editer2').css('display','none');
            // $('#text_editer2').css('display','');
            $('#stop_No2').replaceWith($('<h3 style="width: 125px;margin-top: 25px;float:right" id="stop_No2">Stop No 1 :</h3>'));
            $('#return_stop_No2').replaceWith($('<h3 style="width: 182;margin-top: 25px;float:right" id="return_stop_No2">Return Stop No 1 :</h3>'));

            $('.Flight_section2').css('display','none');
            $('.return_Flight_section2').css('display','none');
            $('#total_Time_Div2').css('display','none');
            $('#return_total_Time_Div2').css('display','none');
        }
        else{
            $('#no_of_stays2').empty('');
            
            $('.Flight_section2').css('display','');
            $('.return_Flight_section2').css('display','');
            $('#total_Time_Div2').css('display','');
            $('#return_total_Time_Div2').css('display','');
            
            $('#flights_type_connected2').css('display','none');
            $('.Flight_section_append2').empty();
            $('.return_Flight_section_append2').empty();
            // 	$('#text_editer2').css('display','none');
        	$('#stop_No2').replaceWith($('<h3 style="width: 125px;margin-top: 25px;float:right" id="stop_No2">Direct :</h3>'));
        	$('#return_stop_No2').replaceWith($('<h3 style="width: 162px;margin-top: 25px;float:right" id="return_stop_No2">Return Direct :</h3>'));
        	
        	addGoogleApi('return_departure_airport_code');
            addGoogleApi('return_arrival_airport_code');
            
            if(all_flight_airline_codes != null && all_flight_airline_codes != ''){
                $('#return_departure_airport_code').attr('readonly', true);
                $('#return_arrival_airport_code').attr('readonly', true);
                $('#return_departure_Flight_Type').attr('readonly', true);
                $('#return_departure_flight_number').attr('readonly', true);
                $('#return_departure_time').attr('readonly', true);
                $('#return_arrival_time').attr('readonly', true);
            }else{
                $('#return_departure_airport_code').attr('readonly', false);
                $('#return_arrival_airport_code').attr('readonly', false);
                $('#return_departure_Flight_Type').attr('readonly', false);
                $('#return_departure_flight_number').attr('readonly', false);
                $('#return_departure_time').attr('readonly', false);
                $('#return_arrival_time').attr('readonly', false);
            }
        }
    }
    
    function flights_type_connected2_fun(){
        var no_of_stays = $('#no_of_stays2').val();
        if(no_of_stays == 'NON_STOP'){
            $('.Flight_section_append').empty();
            $('.return_Flight_section_append').empty();
        }
        else{
            $('.Flight_section_append2').empty();
            $('.return_Flight_section_append2').empty();
            var no_of_stay_ID = 1;
            var flight_name = $('#return_airline_data').val();
            
            for (let i = 1; i <= no_of_stays; i++) {
                var flight_Data =   `<h3 style="">Return Details : </h3>
                
                                    <div class="row" style="">
                            
                                        <div class="col-sm-12 col-md-3" style="padding: 10px;">
                                            <div>
                                                <label for="">Select Return Airline Code</label>
                                                <div class="input-group">
                                                    <select name="return_airline_code[]" required id="return_airline_code_${i}" class="form-control">
                                                        <option attr="select" selected>Select Airline</option>
                                                        @if(isset($airline_code) && $airline_code != null && $airline_code != '')
                                                            @foreach($airline_code as $airline_codeS)
                                                                <option value="{{ $airline_codeS->fs }}">{{$airline_codeS->name}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-12 col-md-3" style="padding: 10px;">
                                            <label for="">Return Airline Number</label>
                                            <input class="form-control" type="text" name="return_airline_number[]" id="return_airline_number_${i}">
                                        </div>
                                        
                                        <div class="col-sm-12 col-md-3" style="padding: 10px;">
                                            <label for="">Return Airline Date</label>
                                            <input class="form-control" type="date" name="return_airline_date[]" id="return_airline_date_${i}">
                                        </div>
                                        
                                        <div class="col-sm-12 col-md-3" style="padding-top: 30px;">
                                            <button type="button" class="btn theme-bg-clr" onclick="return_get_departure_airline_details_I(${i})">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                        
                                    </div>
                                    
                                     <div class="row" style="">
                                        <div class="col-xl-4" style="padding: 10px;">
                                            <label for="">Departure Airport</label>
                                            <input name="return_departure_airport_code[]" id="return_departure_airport_code_${i}" class="form-control" autocomplete="off" readonly>
                                        </div>
                                        <div class="col-xl-1" style="padding: 10px;margin-top: 25px;text-align: center;">
                                            <label></label>
                                            <span id="2Change_Location_${i}" class='bi bi-arrow-left-right' style="font-size: 23px;" onclick="return_change_location_I(${i})"></span>
                                        </div>
                                        <div class="col-xl-4" style="padding: 10px">
                                            <label for="">Arrival Airport</label>
                                            <input name="return_arrival_airport_code[]" id="return_arrival_airport_code_${i}" class="form-control" autocomplete="off" readonly>
                                        </div>
                                        <div class="col-xl-3" style="padding: 10px">
                                            <label for="">Airline Name</label>
                                            <input type="text" id="return_other_Airline_Name2_${i}" value="${flight_name}" name="return_other_Airline_Name2[]" class="form-control other_airline_Name1_${i} return_other_Airline_Name2" readonly>
                                        </div>
                                        <div class="col-xl-3" style="padding: 10px">
                                            <label for="">Class Type</label>
                                            <input type="text" id="return_departure_Flight_Type_${i}" name="return_departure_Flight_Type[]" class="form-control" readonly>
                                        </div>
                                        <div class="col-xl-3" style="padding: 10px">
                                            <label for="">Flight No</label>
                                            <input type="text" id="return_departure_flight_number_${i}" name="return_departure_flight_number[]" class="form-control" readonly>
                                        </div>
                                        <div class="col-xl-3" style="padding: 10px">
                                            <label for="">Departure Date & Time</label>
                                            <input type="datetime-local" id="return_departure_time_${i}" name="return_departure_time[]" class="form-control 2departure_time1_${i}" readonly>
                                        </div>
                                        <div class="col-xl-3" style="padding: 10px">
                                            <label for="">Arrival Date and Time</label>
                                            <input type="datetime-local" id="return_arrival_time_${i}" name="return_arrival_time[]" class="form-control 2arrival_time1_${i}" readonly>
                                        </div>
                                    </div>
                                    <div class="container" style="display:none;">
                                        <div class="row" style="margin-left: 303px;">
                                            <div class="col-sm-3">
                                                <h3 style="width: 140px;margin-top: 25px;float:right" id="no_of_stop_par${i}">Stop No ${i}</h3>
                                            </div>
                                            <div class="col-sm-3">
                                            <label for="">Flight Duration</label>
                                                <input type="text" id="return_total_Time_${i}" name="return_total_Time[]" class="form-control 2total_Time1_${i}" readonly style="width: 167px;">
                                            </div>
                                        </div>
                                    </div>`;
                                    
               
                                    
                $('.Flight_section_append2').append(flight_Data);
                
                addGoogleApi('return_departure_airport_code_'+i+'');
                addGoogleApi('return_arrival_airport_code_'+i+'');
                
                if(all_flight_airline_codes != null && all_flight_airline_codes != ''){
                    $('#return_departure_airport_code_'+i+'').attr('readonly', true);
                    $('#return_arrival_airport_code_'+i+'').attr('readonly', true);
                    $('#return_departure_Flight_Type_'+i+'').attr('readonly', true);
                    $('#return_departure_flight_number_'+i+'').attr('readonly', true);
                    $('#return_departure_time_'+i+'').attr('readonly', true);
                    $('#return_arrival_time_'+i+'').attr('readonly', true);
                }else{
                    $('#return_departure_airport_code_'+i+'').attr('readonly', false);
                    $('#return_arrival_airport_code_'+i+'').attr('readonly', false);
                    $('#return_departure_Flight_Type_'+i+'').attr('readonly', false);
                    $('#return_departure_flight_number_'+i+'').attr('readonly', false);
                    $('#return_departure_time_'+i+'').attr('readonly', false);
                    $('#return_arrival_time_'+i+'').attr('readonly', false);
                }
            }
        }
    }
    
    // Direct
    function return_duration(){
        var second=1000, minute=second*60, hour=minute*60, day=hour*24, week=day*7;
        var h                           = "hours";
        var m                           = "minutes";
        var return_arrival_time1        = $('#return_arrival_time').val();
        var return_departure_time1      = $('#return_departure_time').val();
        var return_date1                = new Date(return_departure_time1);
        var return_date2                = new Date(return_arrival_time1);
        var return_timediff             = return_date2 - return_date1;
        var return_minutes_Total        = Math.floor(return_timediff / minute);
        var return_total_hours          = Math.floor(return_timediff / hour)
        var return_total_hours_minutes  = parseFloat(return_total_hours) * 60;
        var return_minutes              = parseFloat(return_minutes_Total) - parseFloat(return_total_hours_minutes);
        $('#return_total_Time2').val(return_total_hours+h+ ' : ' +return_minutes+m);
    }
    
    function return_Change_Location_fun(){
        var arrival_airport_code   = $('#return_arrival_airport_code').val();
        var departure_airport_code = $('#return_departure_airport_code').val();
        $('#return_arrival_airport_code').val(departure_airport_code);
        $('#return_departure_airport_code').val(arrival_airport_code);
    }
    
    function return_get_departure_airline_details(){
        var return_airline_data = $('#return_airline_data').val();
        $('#return_other_Airline_Name2').val(return_airline_data);
        
        $('#return_departure_airport_code').val('');
    	$('#return_arrival_airport_code').val('');
    	$('#return_departure_flight_number').val('');
    	$('#return_departure_time').val('');
    	$('#return_arrival_time').val('');
    	$('#return_departure_Flight_Type').val('');
        
        var departure_airline_code      = $('#return_airline_code').val();
        var departure_airline_number    = $('#return_airline_number').val();
        var departure_airline_date      = $('#return_airline_date').val();
        var RA_date                     = new Date(departure_airline_date);
        
        var date1 = RA_date.getDate();
        if(date1 < 10) {
            date1 = `0${date1}`;
        }
        var month1 = RA_date.getMonth() + 1;
        if(month1 < 10) {
            month1 = `0${month1}`;
        }
        var year1 = RA_date.getFullYear();
        
        $.ajax({    
            type: "GEt",
            url: "{{URL::to('fetch_airline_data')}}",
            data:{
                "_token"                    : "{{ csrf_token() }}",
                'departure_airline_code'    : departure_airline_code,
                'departure_airline_number'  : departure_airline_number,
                'date1'                     : date1,
                'month1'                    : month1,
                'year1'                     : year1,
            },
            success: function(reponse){
                var data = reponse['data'];
                console.log(data);
                if(data['error']){
                    alert('Flight not available in these dates!');
                }else{
                    var appendix            = data['appendix']['airports'];
                    var scheduledFlights    = data['scheduledFlights'];
                    if(appendix.length > 0 && scheduledFlights.length > 0){
                        $('#return_departure_airport_code').val(appendix[0].name);
                        $('#return_arrival_airport_code').val(appendix[1].name);
                        $('#return_departure_Flight_Type').val(scheduledFlights[0].serviceType);
                        $('#return_departure_flight_number').val(scheduledFlights[0].flightNumber);
                        $('#return_departure_time').val(scheduledFlights[0].departureTime);
                        $('#return_arrival_time').val(scheduledFlights[0].arrivalTime);
                        return_duration();
                    }else{
                        alert('Flight not available in these dates!');
                    }
                }
            }
        });
    }
    
    // Indirect
    function return_change_location_I(id){
        var arrival_airport_code   = $('#return_arrival_airport_code_'+id+'').val();
        var departure_airport_code = $('#return_departure_airport_code_'+id+'').val();
        $('#return_arrival_airport_code_'+id+'').val(departure_airport_code);
        $('#return_departure_airport_code_'+id+'').val(arrival_airport_code);
    }
    
    function return_duration_I(id){
        var second=1000, minute=second*60, hour=minute*60, day=hour*24, week=day*7;
        var h                           = "hours";
        var m                           = "minutes";
        var return_arrival_time1        = $('#return_arrival_time_'+id+'').val();
        var return_departure_time1      = $('#return_departure_time_'+id+'').val();
        var return_date1                = new Date(return_departure_time1);
        var return_date2                = new Date(return_arrival_time1);
        var return_timediff             = return_date2 - return_date1;
        var return_minutes_Total        = Math.floor(return_timediff / minute);
        var return_total_hours          = Math.floor(return_timediff / hour)
        var return_total_hours_minutes  = parseFloat(return_total_hours) * 60;
        var return_minutes              = parseFloat(return_minutes_Total) - parseFloat(return_total_hours_minutes);
        $('#return_total_Time_'+id+'').val(return_total_hours+h+ ' : ' +return_minutes+m);
    }
    
    function return_get_departure_airline_details_I(id){
        var return_airline_data = $('#return_airline_data').val();
        $('#return_other_Airline_Name2_'+id+'').val(return_airline_data);
        
        $('#return_departure_airport_code_'+id+'').val('');
    	$('#return_arrival_airport_code_'+id+'').val('');
    	$('#return_departure_Flight_Type_'+id+'').val('');
    	$('#return_departure_flight_number_'+id+'').val('');
    	$('#return_departure_time_'+id+'').val('');
    	$('#return_arrival_time_'+id+'').val('');
        
        var departure_airline_code      = $('#return_airline_code_'+id+'').val();
        var departure_airline_number    = $('#return_airline_number_'+id+'').val();
        var departure_airline_date      = $('#return_airline_date_'+id+'').val();
        var RA_date                     = new Date(departure_airline_date);
        
        var date1 = RA_date.getDate();
        if(date1 < 10) {
            date1 = `0${date1}`;
        }
        var month1 = RA_date.getMonth() + 1;
        if(month1 < 10) {
            month1 = `0${month1}`;
        }
        var year1 = RA_date.getFullYear();
        
        $.ajax({    
            type: "GEt",
            url: "{{URL::to('fetch_airline_data')}}",
            data:{
                "_token"                    : "{{ csrf_token() }}",
                'departure_airline_code'    : departure_airline_code,
                'departure_airline_number'  : departure_airline_number,
                'date1'                     : date1,
                'month1'                    : month1,
                'year1'                     : year1,
            },
            success: function(reponse){
                var data = reponse['data'];
                console.log(data);
                if(data['error']){
                    alert('Flight not available in these dates!');
                }else{
                    var appendix            = data['appendix']['airports'];
                    var scheduledFlights    = data['scheduledFlights'];
                    if(appendix.length > 0 && scheduledFlights.length > 0){
                        $('#return_departure_airport_code_'+id+'').val(appendix[0].name);
                        $('#return_arrival_airport_code_'+id+'').val(appendix[1].name);
                        $('#return_departure_Flight_Type_'+id+'').val(scheduledFlights[0].serviceType);
                        $('#return_departure_flight_number_'+id+'').val(scheduledFlights[0].flightNumber);
                        $('#return_departure_time_'+id+'').val(scheduledFlights[0].departureTime);
                        $('#return_arrival_time_'+id+'').val(scheduledFlights[0].arrivalTime);
                        return_duration_I(id);
                    }else{
                        alert('Flight not available in these dates!');
                    }
                }
            }
        });
    }

</script>

<script>
    
    // Seats
    $('#start_date').on('change',function(){
        $('#flight_route_type').val('-1').change();
        $('#supplier_name_id').val('-1').change();
        
        $('#manual_flight_button_add').css('display','');
        $('#manual_flight_button_remove').css('display','none');
    });
    
    function add_seats(){
        $('#submit_seats_button').css('display','');
        $('.manual_flight_div').empty();
        $('.manual_flight_div').css('display','');
        $('#flights_cost').css('display','');
        
        var flight_route_type       = $('#flight_route_type').find('option:selected').attr('attr_type');
        var selected_supplier_name  = $('#supplier_name_id').find('option:selected').attr('att-CN');
        var selected_supplier_id    = $('#supplier_name_id').find('option:selected').attr('id');
        var selected_supplier_value = $('#supplier_name_id').find('option:selected').attr('value');
        
        var seats = `<h2>ADD SEATS</h2>
                    
                    <div class="row">
                        
                        <div class="col-md-12" id="select_flights_inc">
                            
                            <div class="row" style="padding: 25px;">
                                
                                <h4>DEPARTURE DETAILS</h4>
                                
                                <div class="col-sm-12 col-md-3" style="padding: 10px;">
                                    <label for="">Select Supplier</label>
                                    <select class="form-control" name="supplier" required id="departure_supplier_id" onchange="departure_supplier_id_fun()">
                                        <option attr="-1">Select</option>`;
                                        if(all_flight_supplier != null && all_flight_supplier != ''){
                                            $.each(all_flight_supplier, function(key, all_flight_supplierS) {
                                                seats += `<option value="${all_flight_supplierS.id}" attr-name="${all_flight_supplierS.companyname}">${all_flight_supplierS.companyname}</option>`;
                                            });
                                        }
                        seats   +=  `</select>
                                </div>
                                
                                <div class="col-sm-12 col-md-3" style="padding: 10px;">
                                    <label for="">Flight Type</label>
                                    <select name="flight_type" required id="flights_type" class="form-control" onchange="flights_type_fun()">
                                        <option attr="-1">Select Type</option>
                                        <option attr="Direct" value="Direct">Direct</option>
                                        <option attr="Indirect" value="Indirect">Indirect</option>
                                    </select>
                                </div>
                                
                                <div class="col-sm-12 col-md-3" style="padding: 10px;">
                                    <div>
                                        <label for="">Airline</label>
                                        <div class="input-group">
                                            <select name="selected_flight_airline" required id="airline_data" class="form-control airline_data" onchange="changeairlineFunction()">
                                                <option attr="select" selected value="-1">Select Airline</option>`;
                                                if(all_flight_airline != null && all_flight_airline != ''){
                                                    $.each(all_flight_airline, function(key, all_flight_airlineS) {
                                                        seats +=`<option value="${all_flight_airlineS.other_Airline_Name}" attr-name="${all_flight_airlineS.id}">${all_flight_airlineS.other_Airline_Name}</option>`;
                                                    });
                                                }
                                seats   +=  `</select>
                                        <input type="hidden" name="departure_airline_id" id="departure_airline_id">
                                        </div>
                                    </div>
                                </div>
                              
                                <div class="col-sm-12 col-md-3" style="padding: 10px;" id="flights_type_connected" onchange="flights_type_connected_fun()"></div>
                                
                            </div>
                            
                            <div class="container Flight_section" style="display:none">
                                
                                <div class="row" style="">
                                    
                                    <div class="col-sm-12 col-md-3" style="padding: 10px;">
                                        <div>
                                            <label for="">Select Departure Airline Code</label>
                                            <div class="input-group">
                                                <select name="departure_airline_code[]" required id="departure_airline_code" class="form-control">
                                                    <option value="-1" attr="select" selected>Select Airline</option>`;
                                                    if(all_flight_airline_codes != null && all_flight_airline_codes != ''){
                                                        $.each(all_flight_airline_codes, function(key, all_flight_airline_codesS) {
                                                            seats +=`<option value="${all_flight_airline_codesS.fs}">${all_flight_airline_codesS.name}</option>`;
                                                        });
                                                    }
                                    seats   +=  `</select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-12 col-md-3" style="padding: 10px;">
                                        <label for="">Departure Airline Number</label>
                                        <input class="form-control" type="text" name="departure_airline_number[]" id="departure_airline_number">
                                    </div>
                                    
                                    <div class="col-sm-12 col-md-3" style="padding: 10px;">
                                        <label for="">Departure Airline Date</label>
                                        <input class="form-control" type="date" name="departure_airline_date[]" id="departure_airline_date">
                                    </div>
                                    
                                    <div class="col-sm-12 col-md-3" style="padding-top: 30px;">
                                        <button type="button" class="btn theme-bg-clr" onclick="get_departure_airline_details()">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    
                                </div>
                                
                                <div class="row" style="">
                                    
                                    <div class="col-xl-4" style="padding: 10px;">
                                        <label for="">Departure Airport</label>
                                        <input type="text" name="departure_airport_code[]" id="departure_airport_code" class="form-control" autocomplete="off" placeholder="Enter Departure Location" readonly>
                                    </div>
                                    
                                    <div class="col-xl-1" style="padding: 10px;margin-top: 25px;text-align: center;">
                                        <label for=""></label>
                                        <span id="Change_Location" class='bi bi-arrow-left-right' style="font-size: 23px;" onclick="Change_Location_fun()"></span>
                                    </div>
                                    
                                    <div class="col-xl-4" style="padding: 10px;">
                                        <label for="">Arrival Airport</label>
                                        <input type="text" name="arrival_airport_code[]" id="arrival_airport_code" class="form-control" autocomplete="off" placeholder="Enter Arrival Location" readonly>
                                    </div>
                                    
                                    <div class="col-xl-3" style="padding: 10px;">
                                        <label for="">Airline Name</label>
                                        <input type="text" id="other_Airline_Name2" name="other_Airline_Name2[]" class="form-control other_airline_Name1 other_Airline_Name2" readonly>
                                    </div>
                                    
                                    <div class="col-xl-3" style="padding: 10px;margin-top: 15px;">
                                        <label for="">Class Type</label>
                                        <input type="text" id="departure_Flight_Type" name="departure_Flight_Type[]" class="form-control departure_time1" readonly>
                                    </div>
                                    
                                    <div class="col-xl-3" style="padding: 10px;margin-top: 15px;">
                                        <label for="">Flight Number</label>
                                        <input type="text" id="departure_flight_number" name="departure_flight_number[]" class="form-control" placeholder="Enter Flight Number" readonly>
                                    </div>
                                    
                                    <div class="col-xl-3" style="padding: 10px;margin-top: 15px;">
                                        <label for="">Departure Date and Time</label>
                                        <input type="datetime-local" id="departure_time" name="departure_time[]" class="form-control departure_time1" readonly onchange="direct_duration()">
                                    </div>
                                    
                                    <div class="col-xl-3" style="padding: 10px;margin-top: 15px;">
                                        <label for="">Arrival Date and Time</label>
                                        <input type="datetime-local" id="arrival_time" name="arrival_time[]" class="form-control arrival_time1" readonly onchange="direct_duration()">
                                    </div>
                                    
                                </div>
                                
                                <div class="row" style="margin-left: 320px;display:none;">
                                    <div class="col-sm-3">
                                        <h3 style="width: 125px;margin-top: 25px;float:right" id="stop_No">Direct :</h3>
                                    </div>
                                    <div class="col-sm-3">
                                         <label for="">Flight Duration</label>
                                        <input type="text" id="total_Time" name="total_Time[]" class="form-control total_Time1" readonly style="width: 170px;" value="">
                                    </div>
                                </div>
                                
                            </div>
                            
                            <div class="container Flight_section_append"></div>
                        
                            <hr>
                            
                            <div class="row" id="select_flights_inc" style="padding: 25px;">
                                
                                <h4>RETURN DETAILS</h4>
                                    
                                <div class="col-sm-12 col-md-3" style="padding: 10px;">
                                    <label for="">Select Supplier</label>
                                        <select class="form-control" name="return_supplier" required id="return_supplier_id"></select>
                                    </div>
                                    
                                <div class="col-sm-12 col-md-3" style="padding: 10px;">
                                    <label for="">Flight Type</label>
                                    <select name="return_flight_type" required id="flights_type2" class="form-control" onchange="flights_type2_fun()">
                                        <option attr="-1">Select Type</option>
                                        <option attr="Direct" value="Direct">Direct</option>
                                        <option attr="Indirect" value="Indirect">Indirect</option>
                                    </select>
                                </div>
                                
                                <div class="col-sm-12 col-md-3" style="padding: 10px;">
                                    <label for="">Return Airline</label>
                                    <div class="input-group">
                                        <select name="return_selected_flight_airline" id="return_airline_data" required class="form-control airline_data" onchange="return_changeairlineFunction()">
                                            <option attr="select" selected value="-1">Select Airline</option>`;
                                            if(all_flight_airline != null && all_flight_airline != ''){
                                                $.each(all_flight_airline, function(key, all_flight_airlineS) {
                                                    seats +=`<option value="${all_flight_airlineS.other_Airline_Name}" attr-name="${all_flight_airlineS.id}">${all_flight_airlineS.other_Airline_Name}</option>`;
                                                });
                                            }
                            seats   +=  `</select>
                                        <input type="hidden" name="return_airline_id" id="return_airline_id">
                                    </div>
                                </div>
                              
                                <div class="col-sm-12 col-md-3" style="padding: 10px;" id="flights_type_connected2" onchange="flights_type_connected2_fun()"></div>
                                
                                <div class="container Flight_section_append2"></div>
                                
                                <div class="container return_Flight_section2" style="display:none">
                                    
                                    <div class="row" style="">
                                    
                                        <div class="col-sm-12 col-md-3" style="padding: 10px;">
                                            <div>
                                                <label for="">Select Return Airline Code</label>
                                                <div class="input-group">
                                                    <select name="return_airline_code[]" required id="return_airline_code" class="form-control" onchange="changeairlineFunction()" >
                                                        <option value="-1" attr="select" selected>Select Airline</option>`;
                                                        if(all_flight_airline_codes != null && all_flight_airline_codes != ''){
                                                            $.each(all_flight_airline_codes, function(key, all_flight_airline_codesS) {
                                                                seats +=`<option value="${all_flight_airline_codesS.fs}">${all_flight_airline_codesS.name}</option>`;
                                                            });
                                                        }
                                        seats   +=  `</select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-12 col-md-3" style="padding: 10px;">
                                            <label for="">Return Airline Number</label>
                                            <input class="form-control" type="text" name="return_airline_number[]" id="return_airline_number">
                                        </div>
                                        
                                        <div class="col-sm-12 col-md-3" style="padding: 10px;">
                                            <label for="">Return Airline Date</label>
                                            <input class="form-control" type="date" name="return_airline_date[]" id="return_airline_date">
                                        </div>
                                        
                                        <div class="col-sm-12 col-md-3" style="padding-top: 30px;">
                                            <button type="button" class="btn theme-bg-clr" onclick="return_get_departure_airline_details()">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="row" style="">
                                        <div class="col-xl-4" style="padding: 10px;">
                                            <label for="">Departure Airport</label>
                                            <input name="return_departure_airport_code[]" id="return_departure_airport_code" class="form-control" autocomplete="off" placeholder="Enter Return Location" readonly>
                                        </div>
                                        
                                        <div class="col-xl-1" style="padding: 10px;margin-top: 25px;text-align: center;">
                                            <label for=""></label>
                                            <span id="return_Change_Location" class='bi bi-arrow-left-right' style="font-size: 23px;" onclick="return_Change_Location_fun()"></span>
                                        </div>
                                        
                                        <div class="col-xl-4" style="padding: 10px;">
                                            <label for="">Arrival Airport</label>
                                            <input name="return_arrival_airport_code[]" id="return_arrival_airport_code" class="form-control" autocomplete="off" placeholder="Enter Return Location" readonly>
                                            
                                        </div>
                                        
                                        <div class="col-xl-3" style="padding: 10px;">
                                            <label for="">Airline Name</label>
                                            <input type="text" id="return_other_Airline_Name2" name="return_other_Airline_Name2[]" class="form-control other_airline_Name1 return_other_Airline_Name2" readonly>
                                        </div>
                                        
                                        <div class="col-xl-3" style="padding: 10px;margin-top: 15px;">
                                            <label for="">Class Type</label>
                                            <input type="text" id="return_departure_Flight_Type" name="return_departure_Flight_Type[]" class="form-control" readonly>
                                        </div>
                                        
                                        <div class="col-xl-3" style="padding: 10px;margin-top: 15px;">
                                            <label for="">Flight Number</label>
                                            <input type="text" id="return_departure_flight_number" name="return_departure_flight_number[]" class="form-control" placeholder="Enter Flight Number" readonly>
                                        </div>
                                        
                                        <div class="col-xl-3" style="padding: 10px;margin-top: 15px;">
                                            <label for="">Departure Date and Time</label>
                                            <input type="datetime-local" id="return_departure_time" name="return_departure_time[]" class="form-control return_departure_time1" readonly onchange="return_duration()">
                                        </div>
                                        
                                        <div class="col-xl-3" style="padding: 10px;margin-top: 15px;">
                                            <label for="">Arrival Date and Time</label>
                                            <input type="datetime-local" id="return_arrival_time" name="return_arrival_time[]" class="form-control return_arrival_time1" readonly onchange="return_duration()">
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="row" style="margin-left: 320px;display:none;">
                                        <div class="col-sm-3">
                                            <h3 style="width: 162px;margin-top: 25px;float:right" id="return_stop_No2">Return Direct :</h3>
                                        </div>
                                        <div class="col-sm-3">
                                             <label for="">Flight Duration</label>
                                            <input type="text" id="return_total_Time2" name="return_total_Time[]" class="form-control return_total_Time1" style="width: 170px;" readonly>
                                        </div>
                                    </div>
                                    
                                </div>
                                
                                <div class="container return_Flight_section_append2"></div>
                                
                            </div>
                            
                            <hr>
                            
                            <div class="row" style="padding: 25px;">
                                
                                <h4>SEATS DETAILS</h4>
                                
                                <div class="col-sm-6 col-md-3" style="padding: 10px;">
                                <label>Number of Seats</label>
                                <div class="input-group">
                                    <span class="input-group-btn input-group-append">
                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">{{$currency ?? ""}}</a>
                                    </span>
                                    <input type="text" onchange="total_price_cal_fun()" id="flights_number_of_seat" name="flights_number_of_seat" class="form-control">
                                </div>
                                </div>
                                
                                <div class="col-sm-6 col-md-3" style="padding: 10px;">
                                <label for="">Price Per Adult</label>
                                <div class="input-group">
                                    <span class="input-group-btn input-group-append">
                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">{{$currency ?? ""}} </a>
                                    </span>
                                    <input type="text" id="flights_per_adult_price" name="flights_per_person_price" class="form-control" value="0">
                                </div>
                                </div>
                                
                                <div class="col-sm-6 col-md-3" style="padding: 10px;">
                                <label for="">Child Price</label>
                                <div class="input-group">
                                    <span class="input-group-btn input-group-append">
                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">{{$currency ?? ""}}</a>
                                    </span>
                                    <input type="text" id="flights_per_child_price" name="flights_per_child_price" class="form-control" value="0">
                                </div>
                                </div>
                                
                                <div class="col-sm-6 col-md-3" style="padding: 10px;">
                                <label for="">Infant Price</label>
                                <div class="input-group">
                                    <span class="input-group-btn input-group-append">
                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">{{$currency ?? ""}}</a>
                                    </span>
                                    <input type="text" id="flights_per_infant_price" name="flights_per_infant_price" class="form-control" value="0">
                                </div>
                                </div> 
                                
                                <div class="col-sm-6 col-md-4" style="padding: 10px;display:none;">
                                    <label for="">Total Price</label>
                                    <div class="input-group">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">{{$currency ?? ""}}</a>
                                        </span>
                                        <input type="text" readonly id="flights_per_person_total_price"  name="flights_total_price" class="form-control">
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>`;
        
        $('.manual_flight_div').append(seats);
    }
    
    function submit_seats(){
        event.preventDefault();
        var formData = $('#flightSeatsForm').serialize();
        
        $.ajax({
            url     : "{{ URL::to('addseat1Ajax') }}",
            type    : "POST",
            data    : formData,
            success : function(response) {
                if(response){
                    var data = response.message;
                    if(data == 'True'){
                        $('.manual_flight_div').empty();
                        $('#manual_flight_button_add').css('display','none');
                        $('#submit_seats_button').css('display','none');
                        supplier_name_id_fun();
                    }
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                console.log(xhr.responseText);
            }
        });
    }
    
    function manual_flight_button_add(){
        var flight_route_type       = $('#flight_route_type').find('option:selected').attr('attr_type');
        var selected_supplier_value = $('#supplier_name_id').find('option:selected').attr('value');
        // if(flight_route_type != '-1' && selected_supplier_value != '-1'){
            add_seats();
            $('#manual_flight_button_add').css('display','none');
            $('#manual_flight_button_remove').css('display','');
        // }else{
            // alert('Select Supplier!');
        // }
    }
    
    function manual_flight_button_remove(){
        $('.manual_flight_div').empty();
        $('#submit_seats_button').css('display','none');
        $('#manual_flight_button_add').css('display','');
        $('#manual_flight_button_remove').css('display','none');
    }
    // End Seats

    // Routes Occupancy
    function BeforDateFunction(date) {
        let yestarday   = new Date(date);
        yestarday       = yestarday.setDate(yestarday.getDate() - 1);
        next_day        = new Date(yestarday);
        let Bday        = next_day.getDate();
        if (Bday < 10) {
            Bday = `0${Bday}`;
        }
        let Bmonth      = next_day.getMonth();
        Bmonth          = Bmonth + 1;
        if (Bmonth < 10) {
            Bmonth = `0${Bmonth}`;
        }    
        let Byear               = next_day.getFullYear();
        var beforedateString    = Byear + "-" + Bmonth + "-" + Bday;
        return beforedateString;
    }
    
    function AfterDateFunction(date) {
        const tomorrow  = new Date(date);
        var tommorow    = tomorrow.setDate(tomorrow.getDate() + 1);
        next_day        = new Date(tommorow);
        let Aday        = next_day.getDate();
        if (Aday < 10) {
            Aday = `0${Aday}`;
        }
        let Amonth       = next_day.getMonth();
        Amonth           = Amonth + 1;
        if (Amonth < 10) {
            Amonth = `0${Amonth}`;
        }
        let Ayear            = next_day.getFullYear();
        var afterdateString = Ayear + "-" + Amonth + "-" + Aday;
        return afterdateString;
    }
    
    $('#flight_route_type').on('change',function(){
        var start_date  = $('#start_date').val();
        if(start_date != null && start_date != ''){
            
            $('#supplier_name_id').val('-1').change();
            
            $('#manual_flight_div').css('display','');
            $('#manual_flight_button_add').css('display','');
            $('#manual_flight_button_remove').css('display','none');
            
            $('#flight_supplier_list_div').css('display','none');
            $('#flight_supplier_list_body').empty();
            $('#supplier_select_div').css('display','');
            $('#all_departure_appends').val('');
            $('#all_return_appends').val('');
            $('#flight_append_div').empty();
            $('#flights_cost').css('display','none');
            $('#flights_departure_code').val('');
            $('#flights_arrival_code').val('');
            $('#flights_prices').val('');
            $('#markup_type').val('');
            $('#markup_value').val('');
            $('#total_markup').val('');
            
            $('#no_of_pax_days').val(0);
            
            var flight_route_type       =  $('#flight_route_type').val();
            var flight_route_type_attr  =  $('#flight_route_type').find('option:selected').attr('attr_type');
            $('.sup_class').css('display','none');
            
            $.ajax({
                url     : '{{URL::to('get_flights_all_routes')}}',
                type    : "get",
                data    : {
                    "_token"    : "{{ csrf_token() }}",
                },
                success:function(response){
                    var all_flight_routes = response.data;
                    $.each(all_flight_routes, function(key, value) {
                        var dep_flight_type     = value.dep_flight_type;
                        var return_flight_type  = value.return_flight_type;
                        var type_check_variable = `${dep_flight_type}_${return_flight_type}`;
                        if(flight_route_type_attr == type_check_variable){
                            var dep_supplier    = value.dep_supplier;
                            var return_supplier = value.return_supplier;
                            $.each(flight_suppliers, function(key, value1) {
                                var supplier_id = value1.id;
                                if(dep_supplier == supplier_id && return_supplier == supplier_id){
                                    $('#s_ID_'+supplier_id+'').css('display','');
                                }
                            });
                        }
                    });
                },
            });
        }else{
            alert('Select start date!');
        }
    });
    
    function supplier_name_id_fun(){
        var start_date = $('#start_date').val();
        if(start_date != null && start_date != ''){
            
            $('#manual_flight_button_add').css('display','');
            $('#manual_flight_button_remove').css('display','none');
            
            $('#flight_supplier_list_body').empty();
            $('#flight_supplier_list_div').css('display','none');
            $('#flight_append_div').empty();
            $('#all_departure_appends').val('');
            $('#all_return_appends').val('');
            $('#flight_append_div').empty();
            $('#flights_cost').css('display','none');
            $('#flights_departure_code').val('');
            $('#flights_arrival_code').val('');
            $('#flights_prices').val('');
            $('#markup_type').val('');
            $('#markup_value').val('');
            $('#total_markup').val('');
            
            $('#no_of_pax_days').val(0);
            
            var no_of_pax_days          = $('#no_of_pax_days').val();
            var start_date_date         = start_date.split("T")[0];
            var start_dateB             = BeforDateFunction(start_date);
            var start_dateA             = AfterDateFunction(start_date);
            var supp_Id                 = $('#supplier_name_id').val();
            
            $('#manual_flight_div').css('display','');
            
            var supp_company_name       = $('#supplier_name_id').find('option:selected').attr('att-CN');
            var flight_route_type_attr  = $('#flight_route_type').find('option:selected').attr('attr_type');
            var i                       = 1;
            var I_type                  = 'Invoice';
        
            $.ajax({
                url     : '{{URL::to('get_flights_selected_supplier')}}',
                type    : "get",
                data    : {
                    "_token"    : "{{ csrf_token() }}",
                    supp_Id     : supp_Id,
                },
                success:function(response){
                    var all_flight_routes = response.data;
                    $.each(all_flight_routes, function(key, value) {
                        var dep_flight_type         = value.dep_flight_type;
                        var return_flight_type      = value.return_flight_type;
                        var type_check_variable     = `${dep_flight_type}_${return_flight_type}`;
                        var flights_number_of_seat  = value.flights_number_of_seat;
                        flights_number_of_seat      = parseFloat(flights_number_of_seat);
                        // var occupied_seat           = value.occupied_seat;
                        // occupied_seat               = parseFloat(occupied_seat);
                        var dep_supplier            = value.dep_supplier;
                        var return_supplier         = value.return_supplier;
                        var dep_objectE             = value.dep_object;
                        
                        if(flight_route_type_attr == type_check_variable){
                            if(dep_supplier == supp_Id && return_supplier == supp_Id){
                                if(dep_objectE != null && dep_objectE != ''){
                                    var dep_object          = JSON.parse(dep_objectE);
                                    var departure_time      = dep_object[0].departure_time;
                                    var departure_time_date = departure_time.split("T")[0];
                                    if(departure_time_date == start_date_date || departure_time_date == start_dateB || departure_time_date == start_dateA){
                                        $.ajax({
                                            url: '{{URL::to('get_flights_occupied_seats')}}',
                                            type:"get",
                                            data:{
                                                "_token"    : "{{ csrf_token() }}",
                                                I_type      : I_type,
                                                supp_Id     : supp_Id,
                                                route_Id    : value.id,
                                                // booking_id  : booking_id,
                                            },
                                            success:function(response){
                                                
                                                var data            = response['data'];
                                                var occupied_seat1   = parseFloat(data);
                                                // var dataId          = response['dataId'];
                                                // if(dataId != null && dataId != ''){
                                                    // var f_route_id    = dataId['booking_id'];
                                                    // if(f_route_id == booking_id){
                                                        // var occupied_seat    = dataId['flight_route_seats_occupied'];
                                                        if(flights_number_of_seat > occupied_seat1){
                                                            
                                                            var available_seats = flights_number_of_seat - occupied_seat1;
                                                            var departure       = dep_object[0].departure;
                                                            var arival          = dep_object[0].arival;
                                                            $('#flight_supplier_list_div').css('display','');
                                                            var FS_data =  `<tr>
                                                                                <td>${dep_flight_type} to ${return_flight_type}</td>
                                                                                <td>
                                                                                    Departure  : <b>${departure}</b><br> 
                                                                                    Arrival    : <b>${arival}</b>
                                                                                </td>
                                                                                <td>
                                                                                    Departure   : <b>${departure_time}</b> <br> 
                                                                                    Arrival     : <b>${dep_object[0].arrival_time}</b>
                                                                                </td>
                                                                                <td>${flights_number_of_seat}</td>
                                                                                <td>${available_seats}</td>
                                                                                <td><input value="1" id="avalible_seats_${i}" onkeyup="avalaible_seats(${i})" type="number" class="form-control"></td>
                                                                                <td>
                                                                                    <input type="hidden" id="route_availaible_seat_${i}" value="${occupied_seat1}">
                                                                                    <input type="hidden" id="route_departure_id_${i}" value="${departure}">
                                                                                    <input type="hidden" id="route_arival_id_${i}" value="${arival}">
                                                                                    <input type="hidden" id="route_available_seats_id_${i}" value="${available_seats}">
                                                                                    <input type="hidden" id="route_seats_id_${i}" value="${flights_number_of_seat}">
                                                                                    <input type="hidden" id="supp_selected_id_${i}" value="${supp_Id}">
                                                                                    <input type="hidden" id="route_selected_id_${i}" value="${value.id}">
                                                                                    <input type="hidden" id="flight_ocupancy_btn_switch_${i}" value="0">
                                                                                    <a type="button" class="btn btn-primary" id="flight_occupy_btn_${i}" onclick="flight_occupy_function(${i})">Occupy</a>
                                                                                    <a type="button" class="btn btn-danger" id="flight_delete_btn_${i}" onclick="flight_delete_function(${i})" style="display:none">Delete</a>
                                                                                </td>
                                                                            </tr>`;
                                                            $('#flight_supplier_list_body').append(FS_data);
                                                            i = i + 1;
                                                            $('#routes_count_id').val(i);
                                                        }
                                                    // }
                                                // }
                                                
                                            },
                                        });
                                    }
                                }
                            }
                        }
                    });
                },
            });
        }else{
            alert('Select start date!');
        }
    }
    
    function avalaible_seats(id){
        $('#flight_ocupancy_btn_switch_'+id+'').val(0);
        $('#flight_occupy_btn_'+id+'').css('display','');
        $('#flight_delete_btn_'+id+'').css('display','none');
        
        $('.main_div_flight_departure_'+id+'').remove();
        $('.main_div_flight_return_'+id+'').remove();
        $('#flight_price_section_adult_'+id+'').remove();
        $('#flight_price_section_child_'+id+'').remove();
        $('#flight_price_section_infant_'+id+'').remove();
        
        $('#no_of_pax_days').val(0);
        
        // var route_seats_id  = $('#route_availaible_seat_'+id+'').val();
        var route_seats_id  = $('#route_available_seats_id_'+id+'').val();
        var avalible_seats  = $('#avalible_seats_'+id+'').val();
        var route_seats_id1 = parseFloat(route_seats_id);
        var avalible_seats1 = parseFloat(avalible_seats);
        if(avalible_seats1 > route_seats_id1){
            alert('Enter seats with-in availability!');
            $('#avalible_seats_'+id+'').val(route_seats_id1);
        }
        
        var routes_count_id     = $('#routes_count_id').val();
        routes_count_id         = parseFloat(routes_count_id);
        var no_of_seats_count   = 0;
        for(var i=1; i<routes_count_id; i++){
            var flight_ocupancy_btn_switch  = $('#flight_ocupancy_btn_switch_'+i+'').val();
            flight_ocupancy_btn_switch      = parseFloat(flight_ocupancy_btn_switch);
            
            if(flight_ocupancy_btn_switch != 0){
                var avalible_seats_selected = $('#avalible_seats_'+i+'').val();
                
                no_of_seats_count           = parseFloat(no_of_seats_count) + parseFloat(avalible_seats_selected);
                $('#no_of_pax_days').val(no_of_seats_count);
            }
        }
        
        flight_price_calculation(id);
    }
    
    function flight_occupy_function(id){
        
        var routes_count_id = $('#routes_count_id').val();
        if(routes_count_id > 0){
            routes_count_id     = parseFloat(routes_count_id);
            for(var x=1; x<routes_count_id; x++){
                var check_swicth_status = $('#flight_ocupancy_btn_switch_'+x+'').val();
                if(check_swicth_status == 1){
                    $('#manual_flight_div').css('display','none');
                    var all_departure_appends = $('#all_departure_appends').val();
                }
            }
        }
        
        $('#flights_cost').css('display','');
        $('#flight_occupy_btn_'+id+'').css('display','none');
        
        var all_return_appends          = $('#all_return_appends').val();
        var swicth_val                  = $('#flight_ocupancy_btn_switch_'+id+'').val();
        var route_selected_id           = $('#route_selected_id_'+id+'').val();
        var avalible_seats_selected     = $('#avalible_seats_'+id+'').val();
        var adult_price_all_seatsD      = parseFloat(avalible_seats_selected);
        var route_available_seats_id    = $('#route_available_seats_id_'+id+'').val();
        var route_available_seats_idD   = parseFloat(route_available_seats_id);
        var supp_Id                     = $('#supplier_name_id').val();
        var no_of_seats_countE          = $('#no_of_pax_days').val();
        var no_of_seats_count           = parseFloat(no_of_seats_countE) + parseFloat(avalible_seats_selected);
        
        if(adult_price_all_seatsD > route_available_seats_idD){
            alert('Enter Seats with-in Availablity!');
        }else{
            if(swicth_val != 1){
                $.ajax({
                    url     : '{{URL::to('get_flights_selected_route')}}',
                    type    : "get",
                    data    : {
                        "_token"    : "{{ csrf_token() }}",
                        SR_Id       : route_selected_id,
                    },
                    success:function(response){
                        if(response){
                            var all_flight_routes = response.data;
                            $.each(all_flight_routes, function(key, value) {
                                var route_id            = value.id;
                                var dep_objectE         = value.dep_object;
                                var return_objectE      = value.return_object;
                                var dep_flight_type     = value.dep_flight_type;
                                var return_flight_type  = value.return_flight_type;
                                
                                var flights_per_person_price    = value.flights_per_person_price;
                                var flights_per_child_price     = value.flights_per_child_price;
                                var flights_per_infant_price    = value.flights_per_infant_price;
                                var flights_number_of_seat      = value.flights_number_of_seat;
                                var adult_price_all_seats       = parseFloat(flights_number_of_seat) * parseFloat(flights_per_person_price);
                                var adult_price_selected_seats  = parseFloat(avalible_seats_selected) * parseFloat(flights_per_person_price);
                                
                                if(route_id == route_selected_id){
                                    if(dep_objectE != null && dep_objectE != ''){
                                        var dep_object = JSON.parse(dep_objectE);
                                        if(all_departure_appends){
                                            if(all_departure_appends != 0){
                                                if(dep_flight_type == 'Direct'){
                                                    // Departure
                                                    var all_departure_appendsD  = JSON.parse(all_departure_appends);
                                                    var departureA              = all_departure_appendsD[0].departure;
                                                    var arivalA                 = all_departure_appendsD[0].arival;
                                                    var departure_timeA         = all_departure_appendsD[0].departure_time;
                                                    var arrival_timeA           = all_departure_appendsD[0].arrival_time;
                                                    $.each(dep_object, function(key, dep_objectS) {
                                                        var departure       = dep_objectS.departure;
                                                        var arival          = dep_objectS.arival;
                                                        var departure_time  = dep_objectS.departure_time;
                                                        var arrival_time    = dep_objectS.arrival_time;
                                                        if(departure == departureA && arival == arivalA && departure_time == departure_timeA && arrival_time == arrival_timeA){
                                                            var data = `<div class="main_div_flight_departure_${id}" style="margin-top: 10px;">
                                                                            
                                                                            <input type="hidden" name="departure_flight_route_type[]" value='${dep_flight_type}'>
                                                                            
                                                                            <input type="hidden" name="flight_route_id_occupied[]" id="flight_route_id_occupied_${route_id}" value='${route_id}'>
                                                                            <input type="hidden" name="flight_occupied_object[]" id="flight_occupied_object_${route_id}" value='${dep_objectE}'>
                                                                            
                                                                            <h3 style="padding: 12px">Departure Details : </h3>
                                                                            <div class="row" style="padding: 12px">
                                                                                <div class="col-xl-4">
                                                                                    <label for="">Departure Airport</label>
                                                                                    <input name="departure_airport_code[]" readonly id="departure_airport_code" value="${dep_objectS.departure}" class="form-control" placeholder="Enter Departure Location">
                                                                                </div>
                                                                                <div class="col-xl-1" style="margin-top: 25px;text-align: center;">
                                                                                    <label for=""></label>
                                                                                    <span id="Change_Location" readonly class='bi bi-arrow-left-right' style="font-size: 23px;"></span>
                                                                                </div>
                                                                                <div class="col-xl-4">
                                                                                    <label for="">Arrival Airport</label>
                                                                                    <input name="arrival_airport_code[]" readonly value="${dep_objectS.arival}" id="arrival_airport_code" class="form-control" placeholder="Enter Arrival Location">
                                                                                    
                                                                                </div>
                                                                                <div class="col-xl-3">
                                                                                    <label for="">Airline Name</label>
                                                                                    <input type="text" id="other_Airline_Name2" readonly value="${dep_objectS.airline}" name="other_Airline_Name2[]" class="form-control other_airline_Name1">
                                                                                   
                                                                                </div>
                                                                                <div class="col-xl-3" style="margin-top: 15px;">
                                                                                    <label for="">Class Type</label>
                                                                                    <select  name="departure_Flight_Type[]" readonly id="departure_Flight_Type" class="form-control">
                                                                                        <option value="${dep_objectS.departure_flight_type}">${dep_objectS.departure_flight_type}</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-xl-3" style="margin-top: 15px;">
                                                                                    <label for="">Flight Number</label>
                                                                                    <input type="text" id="simpleinput" readonly value="${dep_objectS.departure_flight_number}" name="departure_flight_number[]" class="form-control direct_flight_number" placeholder="Enter Flight Number">
                                                                                </div>
                                                                                <div class="col-xl-3" style="margin-top: 15px;">
                                                                                    <label for="">Departure Date and Time</label>
                                                                                    <input type="datetime-local" id="simpleinput" readonly name="departure_time[]" value="${dep_objectS.departure_time}" class="form-control departure_time1 dep_time" value="" >
                                                                                </div>
                                                                                <div class="col-xl-3" style="margin-top: 15px;">
                                                                                    <label for="">Arrival Date and Time</label>
                                                                                    <input type="datetime-local" readonly value="${dep_objectS.arrival_time}" id="simpleinput" name="arrival_time[]" class="form-control arrival_time1 arr_time" value="" >
                                                                                </div>
                                                                            </div>
                                                                            <div class="row" style="margin-left: 320px">
                                                                                <div class="col-sm-3">
                                                                                    <h3 style="width: 125px;margin-top: 25px;float:right" id="stop_No">Duration :</h3>
                                                                                </div>
                                                                                <div class="col-sm-3">
                                                                                     <label for="">Flight Duration</label>
                                                                                    <input type="text" id="total_Time" readonly value="${dep_objectS.total_Time}" name="total_Time[]" class="form-control total_Time1" readonly style="width: 170px;" value="">
                                                                                </div>
                                                                            </div>
                                                                        </div>`;
                                                            $('#flight_append_div').append(data);
                                                            $('#all_departure_appends').val(dep_objectE);
                                                            
                                                            $('#no_of_pax_days').val(s_N);
                                                            
                                                            $('#flight_ocupancy_btn_switch_'+id+'').val(1);
                                                            $('#flight_occupy_btn_'+id+'').css('display','none');
                                                            $('#flight_delete_btn_'+id+'').css('display','');
                                                            
                                                            var route_departure_id  = $('#route_departure_id_'+id+'').val();
                                                            var route_arival_id     = $('#route_arival_id_'+id+'').val();
                                                            $('#flights_departure_code').val(route_departure_id);
                                                            $('#flights_arrival_code').val(route_arival_id);
                                                            
                                                            // Return
                                                            if(return_objectE != null && return_objectE != ''){
                                                                var return_object = JSON.parse(return_objectE);
                                                                $.each(return_object, function(key, return_objectS) {
                                                                    $('#no_of_pax_days').val(no_of_seats_count);
                                                                    departure_match_Status = true;
                                                                    var R_data = `<div class="main_div_flight_return_${id}" style="margin-top: 10px;">
                                                                                    
                                                                                    <input type="hidden" name="return_flight_route_type[]" value='${return_flight_type}'>
                                                                                    <input type="hidden" name="return_flight_route_id_occupied[]" value='${route_id}'>
                                                                                    
                                                                                    <h3 style="padding: 12px">Return Details : </h3>
                                                                                    <div class="row" style="padding: 12px">
                                                                                        <div class="col-xl-4">
                                                                                            <label for="">Departure Airport</label>
                                                                                            <input name="return_departure_airport_code[]" readonly id="return_departure_airport_code" value="${return_objectS.departure}" class="form-control" placeholder="Enter Departure Location">
                                                                                        </div>
                                                                                        <div class="col-xl-1" style="margin-top: 25px;text-align: center;">
                                                                                            <label for=""></label>
                                                                                            <span id="return_Change_Location" readonly class='bi bi-arrow-left-right' style="font-size: 23px;"></span>
                                                                                        </div>
                                                                                        <div class="col-xl-4">
                                                                                            <label for="">Arrival Airport</label>
                                                                                            <input name="return_arrival_airport_code[]" readonly value="${return_objectS.arival}" id="arrival_airport_code" class="form-control" placeholder="Enter Arrival Location">
                                                                                            
                                                                                        </div>
                                                                                        <div class="col-xl-3">
                                                                                            <label for="">Airline Name</label>
                                                                                            <input type="text" id="other_Airline_Name2" readonly value="${return_objectS.airline}" name="return_other_Airline_Name2[]" class="form-control other_airline_Name1">
                                                                                           
                                                                                        </div>
                                                                                        <div class="col-xl-3" style="margin-top: 15px;">
                                                                                            <label for="">Class Type</label>
                                                                                            <select  name="return_departure_Flight_Type[]" readonly id="return_departure_Flight_Type" class="form-control">
                                                                                                <option value="${return_objectS.departure_flight_type}">${return_objectS.departure_flight_type}</option>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="col-xl-3" style="margin-top: 15px;">
                                                                                            <label for="">Flight Number</label>
                                                                                            <input type="text" id="simpleinput" readonly value="${return_objectS.departure_flight_number}" name="return_departure_flight_number[]" class="form-control direct_flight_number" placeholder="Enter Flight Number">
                                                                                        </div>
                                                                                        <div class="col-xl-3" style="margin-top: 15px;">
                                                                                            <label for="">Departure Date and Time</label>
                                                                                            <input type="datetime-local" id="simpleinput" readonly name="return_departure_time[]" value="${return_objectS.departure_time}" class="form-control departure_time1 dep_time" value="" >
                                                                                        </div>
                                                                                        <div class="col-xl-3" style="margin-top: 15px;">
                                                                                            <label for="">Arrival Date and Time</label>
                                                                                            <input type="datetime-local" readonly value="${return_objectS.arrival_time}" id="simpleinput" name="return_arrival_time[]" class="form-control arrival_time1 arr_time" value="" >
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row" style="margin-left: 320px">
                                                                                        <div class="col-sm-3">
                                                                                            <h3 style="width: 125px;margin-top: 25px;float:right" id="stop_No">Duration :</h3>
                                                                                        </div>
                                                                                        <div class="col-sm-3">
                                                                                            <label for="">Flight Duration</label>
                                                                                            <input type="text" id="total_Time" readonly value="${return_objectS.total_Time}" name="return_total_Time[]" class="form-control total_Time1" readonly style="width: 170px;" value="">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>`;
                                                                    $('#flight_append_div').append(R_data);
                                                                });
                                                                $('#all_return_appends').val(return_objectE);
                                                            }
                                                            
                                                            // Costing
                                                            var c_count     = $('#c_count_id').val();
                                                            var ACI_costing = `<div class="row" id="flight_price_section_adult_${id}" style="margin-top:10px;">
                                                                                <h3>Adult Price Section</h3>
                                                                                
                                                                                <input type="hidden" name="flight_route_id_occupied_Pax[]" id="flight_route_id_occupied_${route_id}" value='${route_id}'>
                                                                                 
                                                                                <input type="hidden" id="flight_occupied_seats_single_${id}" value="${adult_price_all_seatsD}">
                                                                                    
                                                                                <div class="col-xl-3">
                                                                                    <label for="">Total Seats</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-btn input-group-append">
                                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                        </span>
                                                                                        <input type="text" id="flights_adult_seats_${id}" name="flights_adult_seats[]" class="form-control" value="${adult_price_all_seatsD}" onkeyup="flights_adult_seats_fun(${id})">
                                                                                    </div>
                                                                                </div>
                                                            
                                                                                <div class="col-xl-3">
                                                                                    <label for="">Type</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-btn input-group-append">
                                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                        </span>
                                                                                        <input type="text" id="flights_adult_type_${id}" name="flights_adult_type[]" class="form-control" readonly value="Adult">
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                <div class="col-xl-3">
                                                                                    <label for="">Cost Price/Seat</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-btn input-group-append">
                                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                        </span>
                                                                                        <input type="text" id="flights_cost_per_seats_adult_${id}" name="flights_cost_per_seats_adult[]" class="form-control" readonly value="${flights_per_person_price}">
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                <div class="col-xl-3">
                                                                                    <label for="">Total Cost Price</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-btn input-group-append">
                                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                        </span>
                                                                                        <input type="text" id="flights_total_cost_adult_${id}" name="flights_total_cost_adult[]" class="form-control" readonly value="${adult_price_selected_seats}">
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                <div class="col-xl-3" style="margin-top:10px;">
                                                                                    <label for="">Markup Type</label>
                                                                                    <select id="flights_markup_type_adult_${id}" name="flights_markup_type_adult[]" class="form-control" onchange="flights_markup_type_adult_fun(${id})">
                                                                                        <option>Select Markup</option>
                                                                                        <option value="%">Percentage</option>
                                                                                        <option value="<?php echo $currency; ?>">Fix Amount</option>
                                                                                    </select>
                                                                                </div>
                                                            
                                                                                <div class="col-xl-3 adult_markup_prices_${id}" style="margin-top:10px;display:none">
                                                                                    <label for="">Markup</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-btn input-group-append">
                                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                        </span>
                                                                                        <input type="text" id="flights_markup_price_adult_${id}" name="flights_markup_price_adult[]" class="form-control" onkeyup="flights_markup_price_adult_fun(${id})">
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                <div class="col-xl-3 adult_markup_prices_${id}" style="margin-top:10px;display:none">
                                                                                    <label for="">Sale Price/Seat</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-btn input-group-append">
                                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                        </span>
                                                                                        <input type="text" id="flights_sale_price_per_seat_adult_${id}" name="flights_sale_price_per_seat_adult[]" class="form-control" readonly>
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                <div class="col-xl-3 adult_markup_prices_${id}" style="margin-top:10px;display:none">
                                                                                    <label for="">Total Sale Price</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-btn input-group-append">
                                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                        </span>
                                                                                        <input type="text" id="flights_sale_price_adult_${id}" name="flights_sale_price_adult[]" class="form-control" readonly>
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                            </div>
                                                                            
                                                                            <div class="row" id="flight_price_section_child_${id}" style="margin-top:10px;">
                                                                                <h3>Child Price Section</h3>
                                                                                
                                                                                <div class="col-xl-3">
                                                                                    <label for="">Total Seats</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-btn input-group-append">
                                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                        </span>
                                                                                        <input type="text" id="flights_child_seats_${id}" name="flights_child_seats[]" class="form-control" value="0" onkeyup="flights_child_seats_fun(${id})">
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                <div class="col-xl-3">
                                                                                    <label for="">Type</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-btn input-group-append">
                                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                        </span>
                                                                                        <input type="text" id="flights_child_type_${id}" name="flights_child_type[]" class="form-control" readonly value="Child">
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                <div class="col-xl-3">
                                                                                    <label for="">Cost Price/Seat</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-btn input-group-append">
                                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                        </span>
                                                                                        <input type="text" id="flights_cost_per_seats_child_${id}" name="flights_cost_per_seats_child[]" class="form-control" readonly value="${flights_per_child_price}">
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                <div class="col-xl-3">
                                                                                    <label for="">Total Cost Price</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-btn input-group-append">
                                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                        </span>
                                                                                        <input type="text" id="flights_total_cost_child_${id}" name="flights_total_cost_child[]" class="form-control" readonly value="0">
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                <div class="col-xl-3" style="margin-top:10px;">
                                                                                    <label for="">Markup Type</label>
                                                                                    <select id="flights_markup_type_child_${id}" name="flights_markup_type_child[]" class="form-control" onchange="flights_markup_type_child_fun(${id})">
                                                                                        <option>Select Markup</option>
                                                                                        <option value="%">Percentage</option>
                                                                                        <option value="<?php echo $currency; ?>">Fix Amount</option>
                                                                                    </select>
                                                                                </div>
                                                                                
                                                                                <div class="col-xl-3 child_markup_prices_${id}" style="margin-top:10px;display:none">
                                                                                    <label for="">Markup</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-btn input-group-append">
                                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                        </span>
                                                                                        <input type="text" id="flights_markup_price_child_${id}" name="flights_markup_price_child[]" class="form-control" onkeyup="flights_markup_price_child_fun(${id})">
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                <div class="col-xl-3 child_markup_prices_${id}" style="margin-top:10px;display:none">
                                                                                    <label for="">Sale Price/Seat</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-btn input-group-append">
                                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                        </span>
                                                                                        <input type="text" id="flights_sale_price_per_seat_child_${id}" name="flights_sale_price_per_seat_child[]" class="form-control" readonly>
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                <div class="col-xl-3 child_markup_prices_${id}" style="margin-top:10px;display:none">
                                                                                    <label for="">Total Sale Price</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-btn input-group-append">
                                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                        </span>
                                                                                        <input type="text" id="flights_sale_price_child_${id}" name="flights_sale_price_child[]" class="form-control" readonly>
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                            </div>
                                                                            
                                                                            <div class="row" id="flight_price_section_infant_${id}" style="margin-top:10px;">
                                                                                <h3>Infant Price Section</h3>
                                                                                
                                                                                <div class="col-xl-3">
                                                                                    <label for="">Total Infants</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-btn input-group-append">
                                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                        </span>
                                                                                        <input type="text" id="flights_infant_seats_${id}" name="flights_infant_seats[]" class="form-control" value="0" onkeyup="flights_infant_seats_fun(${id})">
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                <div class="col-xl-3">
                                                                                    <label for="">Type</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-btn input-group-append">
                                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                        </span>
                                                                                        <input type="text" id="flights_infant_type_${id}" name="flights_infant_type[]" class="form-control" readonly value="Infant">
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                <div class="col-xl-3">
                                                                                    <label for="">Cost Price/Seat</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-btn input-group-append">
                                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                        </span>
                                                                                        <input type="text" id="flights_cost_per_seats_infant_${id}" name="flights_cost_per_seats_infant[]" class="form-control" readonly value="${flights_per_infant_price}">
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                <div class="col-xl-3">
                                                                                    <label for="">Total Cost Price</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-btn input-group-append">
                                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                        </span>
                                                                                        <input type="text" id="flights_total_cost_infant_${id}" name="flights_total_cost_infant[]" class="form-control" readonly value="0">
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                <div class="col-xl-3" style="margin-top:10px;">
                                                                                    <label for="">Markup Type</label>
                                                                                    <select id="flights_markup_type_infant_${id}" name="flights_markup_type_infant[]" class="form-control" onchange="flights_markup_type_infant_fun(${id})">
                                                                                        <option>Select Markup</option>
                                                                                        <option value="%">Percentage</option>
                                                                                        <option value="<?php echo $currency; ?>">Fix Amount</option>
                                                                                    </select>
                                                                                </div>
                                                                                
                                                                                <div class="col-xl-3 infant_markup_prices_${id}" style="margin-top:10px;display:none">
                                                                                    <label for="">Markup</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-btn input-group-append">
                                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                        </span>
                                                                                        <input type="text" id="flights_markup_price_infant_${id}" name="flights_markup_price_infant[]" class="form-control" onkeyup="flights_markup_price_infant_fun(${id})">
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                <div class="col-xl-3 infant_markup_prices_${id}" style="margin-top:10px;display:none">
                                                                                    <label for="">Sale Price/Seat</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-btn input-group-append">
                                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                        </span>
                                                                                        <input type="text" id="flights_sale_price_per_seat_infant_${id}" name="flights_sale_price_per_seat_infant[]" class="form-control" readonly>
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                <div class="col-xl-3 infant_markup_prices_${id}" style="margin-top:10px;display:none">
                                                                                    <label for="">Total Sale Price</label>
                                                                                    <div class="input-group">
                                                                                        <span class="input-group-btn input-group-append">
                                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                        </span>
                                                                                        <input type="text" id="flights_sale_price_infant_${id}" name="flights_sale_price_infant[]" class="form-control" readonly>
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                            </div>`;
                                                            $('#flight_append_div').append(ACI_costing);
                                                            c_count = parseFloat(c_count) + 1;
                                                            $('#c_count_id').val(c_count);
                                                        }
                                                        else{
                                                            $('#flight_occupy_btn_'+id+'').css('display','');
                                                            alert('Path does not matched!');
                                                        }
                                                    });
                                                }
                                                else{
                                                    var current_obj_length          = dep_object.length;
                                                    var all_departure_appendsD      = JSON.parse(all_departure_appends);
                                                    var all_departure_appends_count = all_departure_appendsD.length;
                                                    
                                                    if(all_departure_appends_count == current_obj_length){
                                                        for(var l_C=0; l_C<all_departure_appends_count; l_C++){
                                                            var departure       = dep_object[l_C].departure;
                                                            var arival          = dep_object[l_C].arival;
                                                            var departure_time  = dep_object[l_C].departure_time;
                                                            var arrival_time    = dep_object[l_C].arrival_time;
                                                            
                                                            var departureA       = all_departure_appendsD[l_C].departure;
                                                            var arivalA          = all_departure_appendsD[l_C].arival;
                                                            var departure_timeA  = all_departure_appendsD[l_C].departure_time;
                                                            var arrival_timeA    = all_departure_appendsD[l_C].arrival_time;
                                                            
                                                            if(departure == departureA && arival == arivalA && departure_time == departure_timeA && arrival_time == arrival_timeA){
                                                                var departure_match_Status = true;
                                                            }else{
                                                                var departure_match_Status = false;
                                                            }
                                                        }
                                                    }else{
                                                        var departure_match_Status = false;
                                                    }
                                                    
                                                    if(departure_match_Status == true){
                                                        // Departure
                                                        var s_N = 1;
                                                        $.each(dep_object, function(key, dep_objectS) {
                                                        var data = `<div class="main_div_flight_departure_${id}" style="margin-top: 10px;">
                                                                        
                                                                        <input type="hidden" name="departure_flight_route_type[]" value='${dep_flight_type}'>
                                                                        
                                                                        <input type="hidden" name="flight_route_id_occupied[]" id="flight_route_id_occupied_${route_id}" value='${route_id}'>
                                                                        <input type="hidden" name="flight_occupied_object[]" id="flight_occupied_object_${route_id}" value='${dep_objectE}'>
                                                                        
                                                                        <h3 style="padding: 12px">Departure Details : </h3>
                                                                        <div class="row" style="padding: 12px">
                                                                            <div class="col-xl-4">
                                                                                <label for="">Departure Airport</label>
                                                                                <input name="departure_airport_code[]" readonly id="departure_airport_code" value="${dep_objectS.departure}" class="form-control" placeholder="Enter Departure Location">
                                                                            </div>
                                                                            <div class="col-xl-1" style="margin-top: 25px;text-align: center;">
                                                                                <label for=""></label>
                                                                                <span id="Change_Location" readonly class='bi bi-arrow-left-right' style="font-size: 23px;"></span>
                                                                            </div>
                                                                            <div class="col-xl-4">
                                                                                <label for="">Arrival Airport</label>
                                                                                <input name="arrival_airport_code[]" readonly value="${dep_objectS.arival}" id="arrival_airport_code" class="form-control" placeholder="Enter Arrival Location">
                                                                                
                                                                            </div>
                                                                            <div class="col-xl-3">
                                                                                <label for="">Airline Name</label>
                                                                                <input type="text" id="other_Airline_Name2" readonly value="${dep_objectS.airline}" name="other_Airline_Name2[]" class="form-control other_airline_Name1">
                                                                               
                                                                            </div>
                                                                            <div class="col-xl-3" style="margin-top: 15px;">
                                                                                <label for="">Class Type</label>
                                                                                <select  name="departure_Flight_Type[]" readonly  id="departure_Flight_Type" class="form-control">
                                                                                    <option value="${dep_objectS.departure_flight_type}">${dep_objectS.departure_flight_type}</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-xl-3" style="margin-top: 15px;">
                                                                                <label for="">Flight Number</label>
                                                                                <input type="text" id="simpleinput" readonly value="${dep_objectS.departure_flight_number}" name="departure_flight_number[]" class="form-control direct_flight_number" placeholder="Enter Flight Number">
                                                                            </div>
                                                                            <div class="col-xl-3" style="margin-top: 15px;">
                                                                                <label for="">Departure Date and Time</label>
                                                                                <input type="datetime-local" id="simpleinput" readonly name="departure_time[]" value="${dep_objectS.departure_time}" class="form-control departure_time1 dep_time" value="" >
                                                                            </div>
                                                                            <div class="col-xl-3" style="margin-top: 15px;">
                                                                                <label for="">Arrival Date and Time</label>
                                                                                <input type="datetime-local" readonly value="${dep_objectS.arrival_time}" id="simpleinput" name="arrival_time[]" class="form-control arrival_time1 arr_time" value="" >
                                                                            </div>
                                                                        </div>
                                                                        <div class="row" style="margin-left: 320px">
                                                                            <div class="col-sm-3">
                                                                                <h3 style="width: 125px;margin-top: 25px;float:right" id="stop_No">Stop # ${s_N}</h3>
                                                                            </div>
                                                                            <div class="col-sm-3">
                                                                                 <label for="">Flight Duration</label>
                                                                                <input type="text" id="total_Time" readonly value="${dep_objectS.total_Time}" name="total_Time[]" class="form-control total_Time1" readonly style="width: 170px;" value="">
                                                                            </div>
                                                                        </div>
                                                                    </div>`;
                                                        $('#flight_append_div').append(data);
                                                        s_N++
                                                    });
                                                        $('#all_departure_appends').val(dep_objectE);
                                                        
                                                        $('#flight_ocupancy_btn_switch_'+id+'').val(1);
                                                        $('#flight_occupy_btn_'+id+'').css('display','none');
                                                        $('#flight_delete_btn_'+id+'').css('display','');
                                                        
                                                        var route_departure_id  = $('#route_departure_id_'+id+'').val();
                                                        var route_arival_id     = $('#route_arival_id_'+id+'').val();
                                                        $('#flights_departure_code').val(route_departure_id);
                                                        $('#flights_arrival_code').val(route_arival_id);
                                                        
                                                        // Return
                                                        if(return_objectE != null && return_objectE != ''){
                                                            var return_object = JSON.parse(return_objectE);
                                                            $('#no_of_pax_days').val(no_of_seats_count);
                                                            var s_N = 1;
                                                            $.each(return_object, function(key, return_objectS) {
                                                                var R_data = `<div class="main_div_flight_return_${id}" style="margin-top: 10px;">
                                                                                
                                                                                <input type="hidden" name="return_flight_route_type[]" value='${return_flight_type}'>
                                                                                <input type="hidden" name="return_flight_route_id_occupied[]" value='${route_id}'>
                                                                                
                                                                                <h3 style="padding: 12px">Return Details : </h3>
                                                                                <div class="row" style="padding: 12px">
                                                                                    <div class="col-xl-4">
                                                                                        <label for="">Departure Airport</label>
                                                                                        <input name="return_departure_airport_code[]" readonly id="return_departure_airport_code" value="${return_objectS.departure}" class="form-control" placeholder="Enter Departure Location">
                                                                                    </div>
                                                                                    <div class="col-xl-1" style="margin-top: 25px;text-align: center;">
                                                                                        <label for=""></label>
                                                                                        <span id="return_Change_Location" readonly class='bi bi-arrow-left-right' style="font-size: 23px;"></span>
                                                                                    </div>
                                                                                    <div class="col-xl-4">
                                                                                        <label for="">Arrival Airport</label>
                                                                                        <input name="return_arrival_airport_code[]" readonly value="${return_objectS.arival}" id="arrival_airport_code" class="form-control" placeholder="Enter Arrival Location">
                                                                                        
                                                                                    </div>
                                                                                    <div class="col-xl-3">
                                                                                        <label for="">Airline Name</label>
                                                                                        <input type="text" id="other_Airline_Name2" readonly value="${return_objectS.airline}" name="return_other_Airline_Name2[]" class="form-control other_airline_Name1">
                                                                                       
                                                                                    </div>
                                                                                    <div class="col-xl-3" style="margin-top: 15px;">
                                                                                        <label for="">Class Type</label>
                                                                                        <select  name="return_departure_Flight_Type[]" readonly id="return_departure_Flight_Type" class="form-control">
                                                                                            <option value="${return_objectS.departure_flight_type}">${return_objectS.departure_flight_type}</option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-xl-3" style="margin-top: 15px;">
                                                                                        <label for="">Flight Number</label>
                                                                                        <input type="text" id="simpleinput" readonly value="${return_objectS.departure_flight_number}" name="return_departure_flight_number[]" class="form-control direct_flight_number" placeholder="Enter Flight Number">
                                                                                    </div>
                                                                                    <div class="col-xl-3" style="margin-top: 15px;">
                                                                                        <label for="">Departure Date and Time</label>
                                                                                        <input type="datetime-local" id="simpleinput" readonly name="return_departure_time[]" value="${return_objectS.departure_time}" class="form-control departure_time1 dep_time" value="" >
                                                                                    </div>
                                                                                    <div class="col-xl-3" style="margin-top: 15px;">
                                                                                        <label for="">Arrival Date and Time</label>
                                                                                        <input type="datetime-local" readonly value="${return_objectS.arrival_time}" id="simpleinput" name="return_arrival_time[]" class="form-control arrival_time1 arr_time" value="" >
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row" style="margin-left: 320px">
                                                                                    <div class="col-sm-3">
                                                                                        <h3 style="width: 125px;margin-top: 25px;float:right" id="stop_No">Stop # ${s_N}</h3>
                                                                                    </div>
                                                                                    <div class="col-sm-3">
                                                                                         <label for="">Flight Duration</label>
                                                                                        <input type="text" id="total_Time" readonly value="${return_objectS.total_Time}" name="return_total_Time[]" class="form-control total_Time1" readonly style="width: 170px;" value="">
                                                                                    </div>
                                                                                </div>
                                                                            </div>`;
                                                                $('#flight_append_div').append(R_data);
                                                                s_N++
                                                            });
                                                            $('#all_return_appends').val(return_objectE);
                                                        }
                                                        
                                                        // COSTING
                                                        var c_count     = $('#c_count_id').val();
                                                        var ACI_costing = `<div class="row" id="flight_price_section_adult_${id}" style="margin-top:10px;">
                                                                            <h3>Adult Price Section</h3>
                                                                            
                                                                            <input type="hidden" name="flight_route_id_occupied_Pax[]" id="flight_route_id_occupied_${route_id}" value='${route_id}'>
                                                                            
                                                                            <input type="hidden" id="flight_occupied_seats_single_${id}" value="${adult_price_all_seatsD}">
                                                                                
                                                                            <div class="col-xl-3">
                                                                                <label for="">Total Seats</label>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-btn input-group-append">
                                                                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                    </span>
                                                                                    <input type="text" id="flights_adult_seats_${id}" name="flights_adult_seats[]" class="form-control" value="${adult_price_all_seatsD}" onkeyup="flights_adult_seats_fun(${id})">
                                                                                </div>
                                                                            </div>
                                                        
                                                                            <div class="col-xl-3">
                                                                                <label for="">Type</label>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-btn input-group-append">
                                                                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                    </span>
                                                                                    <input type="text" id="flights_adult_type_${id}" name="flights_adult_type[]" class="form-control" readonly value="Adult">
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="col-xl-3">
                                                                                <label for="">Cost Price/Seat</label>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-btn input-group-append">
                                                                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                    </span>
                                                                                    <input type="text" id="flights_cost_per_seats_adult_${id}" name="flights_cost_per_seats_adult[]" class="form-control" readonly value="${flights_per_person_price}">
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="col-xl-3">
                                                                                <label for="">Total Cost Price</label>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-btn input-group-append">
                                                                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                    </span>
                                                                                    <input type="text" id="flights_total_cost_adult_${id}" name="flights_total_cost_adult[]" class="form-control" readonly value="${adult_price_selected_seats}">
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="col-xl-3" style="margin-top:10px;">
                                                                                <label for="">Markup Type</label>
                                                                                <select id="flights_markup_type_adult_${id}" name="flights_markup_type_adult[]" class="form-control" onchange="flights_markup_type_adult_fun(${id})">
                                                                                    <option>Select Markup</option>
                                                                                    <option value="%">Percentage</option>
                                                                                    <option value="<?php echo $currency; ?>">Fix Amount</option>
                                                                                </select>
                                                                            </div>
                                                        
                                                                            <div class="col-xl-3 adult_markup_prices_${id}" style="margin-top:10px;display:none">
                                                                                <label for="">Markup</label>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-btn input-group-append">
                                                                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                    </span>
                                                                                    <input type="text" id="flights_markup_price_adult_${id}" name="flights_markup_price_adult[]" class="form-control" onkeyup="flights_markup_price_adult_fun(${id})">
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="col-xl-3 adult_markup_prices_${id}" style="margin-top:10px;display:none">
                                                                                <label for="">Sale Price/Seat</label>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-btn input-group-append">
                                                                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                    </span>
                                                                                    <input type="text" id="flights_sale_price_per_seat_adult_${id}" name="flights_sale_price_per_seat_adult[]" class="form-control" readonly>
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="col-xl-3 adult_markup_prices_${id}" style="margin-top:10px;display:none">
                                                                                <label for="">Total Sale Price</label>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-btn input-group-append">
                                                                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                    </span>
                                                                                    <input type="text" id="flights_sale_price_adult_${id}" name="flights_sale_price_adult[]" class="form-control" readonly>
                                                                                </div>
                                                                            </div>
                                                                            
                                                                        </div>
                                                                        
                                                                        <div class="row" id="flight_price_section_child_${id}" style="margin-top:10px;">
                                                                            <h3>Child Price Section</h3>
                                                                            
                                                                            <div class="col-xl-3">
                                                                                <label for="">Total Seats</label>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-btn input-group-append">
                                                                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                    </span>
                                                                                    <input type="text" id="flights_child_seats_${id}" name="flights_child_seats[]" class="form-control" value="0" onkeyup="flights_child_seats_fun(${id})">
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="col-xl-3">
                                                                                <label for="">Type</label>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-btn input-group-append">
                                                                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                    </span>
                                                                                    <input type="text" id="flights_child_type_${id}" name="flights_child_type[]" class="form-control" readonly value="Child">
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="col-xl-3">
                                                                                <label for="">Cost Price/Seat</label>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-btn input-group-append">
                                                                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                    </span>
                                                                                    <input type="text" id="flights_cost_per_seats_child_${id}" name="flights_cost_per_seats_child[]" class="form-control" readonly value="${flights_per_child_price}">
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="col-xl-3">
                                                                                <label for="">Total Cost Price</label>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-btn input-group-append">
                                                                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                    </span>
                                                                                    <input type="text" id="flights_total_cost_child_${id}" name="flights_total_cost_child[]" class="form-control" readonly value="0">
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="col-xl-3" style="margin-top:10px;">
                                                                                <label for="">Markup Type</label>
                                                                                <select id="flights_markup_type_child_${id}" name="flights_markup_type_child[]" class="form-control" onchange="flights_markup_type_child_fun(${id})">
                                                                                    <option>Select Markup</option>
                                                                                    <option value="%">Percentage</option>
                                                                                    <option value="<?php echo $currency; ?>">Fix Amount</option>
                                                                                </select>
                                                                            </div>
                                                                            
                                                                            <div class="col-xl-3 child_markup_prices_${id}" style="margin-top:10px;display:none">
                                                                                <label for="">Markup</label>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-btn input-group-append">
                                                                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                    </span>
                                                                                    <input type="text" id="flights_markup_price_child_${id}" name="flights_markup_price_child[]" class="form-control" onkeyup="flights_markup_price_child_fun(${id})">
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="col-xl-3 child_markup_prices_${id}" style="margin-top:10px;display:none">
                                                                                <label for="">Sale Price/Seat</label>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-btn input-group-append">
                                                                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                    </span>
                                                                                    <input type="text" id="flights_sale_price_per_seat_child_${id}" name="flights_sale_price_per_seat_child[]" class="form-control" readonly>
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="col-xl-3 child_markup_prices_${id}" style="margin-top:10px;display:none">
                                                                                <label for="">Total Sale Price</label>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-btn input-group-append">
                                                                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                    </span>
                                                                                    <input type="text" id="flights_sale_price_child_${id}" name="flights_sale_price_child[]" class="form-control" readonly>
                                                                                </div>
                                                                            </div>
                                                                            
                                                                        </div>
                                                                        
                                                                        <div class="row" id="flight_price_section_infant_${id}" style="margin-top:10px;">
                                                                            <h3>Infant Price Section</h3>
                                                                            
                                                                            <div class="col-xl-3">
                                                                                <label for="">Total Infants</label>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-btn input-group-append">
                                                                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                    </span>
                                                                                    <input type="text" id="flights_infant_seats_${id}" name="flights_infant_seats[]" class="form-control" value="0" onkeyup="flights_infant_seats_fun(${id})">
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="col-xl-3">
                                                                                <label for="">Type</label>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-btn input-group-append">
                                                                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                    </span>
                                                                                    <input type="text" id="flights_infant_type_${id}" name="flights_infant_type[]" class="form-control" readonly value="Infant">
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="col-xl-3">
                                                                                <label for="">Cost Price/Seat</label>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-btn input-group-append">
                                                                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                    </span>
                                                                                    <input type="text" id="flights_cost_per_seats_infant_${id}" name="flights_cost_per_seats_infant[]" class="form-control" readonly value="${flights_per_infant_price}">
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="col-xl-3">
                                                                                <label for="">Total Cost Price</label>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-btn input-group-append">
                                                                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                    </span>
                                                                                    <input type="text" id="flights_total_cost_infant_${id}" name="flights_total_cost_infant[]" class="form-control" readonly value="0">
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="col-xl-3" style="margin-top:10px;">
                                                                                <label for="">Markup Type</label>
                                                                                <select id="flights_markup_type_infant_${id}" name="flights_markup_type_infant[]" class="form-control" onchange="flights_markup_type_infant_fun(${id})">
                                                                                    <option>Select Markup</option>
                                                                                    <option value="%">Percentage</option>
                                                                                    <option value="<?php echo $currency; ?>">Fix Amount</option>
                                                                                </select>
                                                                            </div>
                                                                            
                                                                            <div class="col-xl-3 infant_markup_prices_${id}" style="margin-top:10px;display:none">
                                                                                <label for="">Markup</label>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-btn input-group-append">
                                                                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                    </span>
                                                                                    <input type="text" id="flights_markup_price_infant_${id}" name="flights_markup_price_infant[]" class="form-control" onkeyup="flights_markup_price_infant_fun(${id})">
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="col-xl-3 infant_markup_prices_${id}" style="margin-top:10px;display:none">
                                                                                <label for="">Sale Price/Seat</label>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-btn input-group-append">
                                                                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                    </span>
                                                                                    <input type="text" id="flights_sale_price_per_seat_infant_${id}" name="flights_sale_price_per_seat_infant[]" class="form-control" readonly>
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="col-xl-3 infant_markup_prices_${id}" style="margin-top:10px;display:none">
                                                                                <label for="">Total Sale Price</label>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-btn input-group-append">
                                                                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                                    </span>
                                                                                    <input type="text" id="flights_sale_price_infant_${id}" name="flights_sale_price_infant[]" class="form-control" readonly>
                                                                                </div>
                                                                            </div>
                                                                            
                                                                        </div>`;
                                                        $('#flight_append_div').append(ACI_costing);
                                                        c_count = parseFloat(c_count) + 1;
                                                        $('#c_count_id').val(c_count);
                                                    }
                                                    else{
                                                        $('#flight_occupy_btn_'+id+'').css('display','');
                                                        alert('Path does not matched!');
                                                    }
                                                }
                                            }
                                        }else{
                                            // Departure
                                            $('#flight_ocupancy_btn_switch_'+id+'').val(1);
                                            $('#flight_occupy_btn_'+id+'').css('display','none');
                                            $('#flight_delete_btn_'+id+'').css('display','');
                                            var s_N = 1;
                                            $.each(dep_object, function(key, dep_objectS) {
                                                var data = `<div class="main_div_flight_departure_${id}" style="margin-top: 10px;">
                                                                
                                                                <input type="hidden" name="departure_flight_route_type[]" value='${dep_flight_type}'>
                                                                
                                                                <input type="hidden" name="flight_route_id_occupied[]" id="flight_route_id_occupied_${route_id}" value='${route_id}'>
                                                                <input type="hidden" name="flight_occupied_object[]" id="flight_occupied_object_${route_id}" value='${dep_objectE}'>
                                                                
                                                                <h3 style="padding: 12px">Departure Details : </h3>
                                                                <div class="row" style="padding: 12px">
                                                                    <div class="col-xl-4">
                                                                        <label for="">Departure Airport</label>
                                                                        <input name="departure_airport_code[]" readonly id="departure_airport_code" value="${dep_objectS.departure}" class="form-control" placeholder="Enter Departure Location">
                                                                    </div>
                                                                    <div class="col-xl-1" style="margin-top: 25px;text-align: center;">
                                                                        <label for=""></label>
                                                                        <span id="Change_Location" readonly class='bi bi-arrow-left-right' style="font-size: 23px;"></span>
                                                                    </div>
                                                                    <div class="col-xl-4">
                                                                        <label for="">Arrival Airport</label>
                                                                        <input name="arrival_airport_code[]" readonly value="${dep_objectS.arival}" id="arrival_airport_code" class="form-control" placeholder="Enter Arrival Location">
                                                                        
                                                                    </div>
                                                                    <div class="col-xl-3">
                                                                        <label for="">Airline Name</label>
                                                                        <input type="text" id="other_Airline_Name2" readonly value="${dep_objectS.airline}" name="other_Airline_Name2[]" class="form-control other_airline_Name1">
                                                                       
                                                                    </div>
                                                                    <div class="col-xl-3" style="margin-top: 15px;">
                                                                        <label for="">Class Type</label>
                                                                        <select  name="departure_Flight_Type[]" readonly id="departure_Flight_Type" class="form-control">
                                                                            <option value="${dep_objectS.departure_flight_type}">${dep_objectS.departure_flight_type}</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-xl-3" style="margin-top: 15px;">
                                                                        <label for="">Flight Number</label>
                                                                        <input type="text" id="simpleinput" readonly value="${dep_objectS.departure_flight_number}" name="departure_flight_number[]" class="form-control direct_flight_number" placeholder="Enter Flight Number">
                                                                    </div>
                                                                    <div class="col-xl-3" style="margin-top: 15px;">
                                                                        <label for="">Departure Date and Time</label>
                                                                        <input type="datetime-local" id="simpleinput" readonly name="departure_time[]" value="${dep_objectS.departure_time}" class="form-control departure_time1 dep_time" value="" >
                                                                    </div>
                                                                    <div class="col-xl-3" style="margin-top: 15px;">
                                                                        <label for="">Arrival Date and Time</label>
                                                                        <input type="datetime-local" readonly value="${dep_objectS.arrival_time}" id="simpleinput" name="arrival_time[]" class="form-control arrival_time1 arr_time" value="" >
                                                                    </div>
                                                                </div>
                                                                <div class="row" style="margin-left: 320px">
                                                                    <div class="col-sm-3">
                                                                        <h3 style="width: 125px;margin-top: 25px;float:right" id="stop_No">`
                                                                            if(dep_flight_type == 'Direct'){
                                                                                data += `Duration :`;
                                                                            }else{
                                                                                data += `Stop # ${s_N} :`;
                                                                            }
                                                                data += `</h3>
                                                                    </div>
                                                                    <div class="col-sm-3">
                                                                         <label for="">Flight Duration</label>
                                                                        <input type="text" id="total_Time" readonly value="${dep_objectS.total_Time}" name="total_Time[]" class="form-control total_Time1" readonly style="width: 170px;" value="">
                                                                    </div>
                                                                </div>
                                                            </div>`;
                                                $('#flight_append_div').append(data);
                                                s_N++
                                            });
                                            $('#all_departure_appends').val(dep_objectE);
                                            
                                            var route_departure_id  = $('#route_departure_id_'+id+'').val();
                                            var route_arival_id     = $('#route_arival_id_'+id+'').val();
                                            $('#flights_departure_code').val(route_departure_id);
                                            $('#flights_arrival_code').val(route_arival_id);
                                            
                                            // Return
                                            if(return_objectE != null && return_objectE != ''){
                                                var return_object = JSON.parse(return_objectE);
                                                var r_s_N = 1;
                                                $.each(return_object, function(key, return_objectS) {
                                                    var R_data = `<div class="main_div_flight_return_${id}" style="margin-top: 10px;">
                                                                    
                                                                    <input type="hidden" name="return_flight_route_type[]" value='${return_flight_type}'>
                                                                    <input type="hidden" name="return_flight_route_id_occupied[]" value='${route_id}'>
                                                                    
                                                                    <h3 style="padding: 12px">Return Details : </h3>
                                                                    <div class="row" style="padding: 12px">
                                                                        <div class="col-xl-4">
                                                                            <label for="">Departure Airport</label>
                                                                            <input name="return_departure_airport_code[]" readonly id="return_departure_airport_code" value="${return_objectS.departure}" class="form-control" placeholder="Enter Departure Location">
                                                                        </div>
                                                                        <div class="col-xl-1" style="margin-top: 25px;text-align: center;">
                                                                            <label for=""></label>
                                                                            <span id="return_Change_Location" readonly class='bi bi-arrow-left-right' style="font-size: 23px;"></span>
                                                                        </div>
                                                                        <div class="col-xl-4">
                                                                            <label for="">Arrival Airport</label>
                                                                            <input name="return_arrival_airport_code[]" readonly value="${return_objectS.arival}" id="arrival_airport_code" class="form-control" placeholder="Enter Arrival Location">
                                                                            
                                                                        </div>
                                                                        <div class="col-xl-3">
                                                                            <label for="">Airline Name</label>
                                                                            <input type="text" id="other_Airline_Name2" readonly value="${return_objectS.airline}" name="return_other_Airline_Name2[]" class="form-control other_airline_Name1">
                                                                           
                                                                        </div>
                                                                        <div class="col-xl-3" style="margin-top: 15px;">
                                                                            <label for="">Class Type</label>
                                                                            <select  name="return_departure_Flight_Type[]" readonly id="return_departure_Flight_Type" class="form-control">
                                                                                <option value="${return_objectS.departure_flight_type}">${return_objectS.departure_flight_type}</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-xl-3" style="margin-top: 15px;">
                                                                            <label for="">Flight Number</label>
                                                                            <input type="text" id="simpleinput" readonly value="${return_objectS.departure_flight_number}" name="return_departure_flight_number[]" class="form-control direct_flight_number" placeholder="Enter Flight Number">
                                                                        </div>
                                                                        <div class="col-xl-3" style="margin-top: 15px;">
                                                                            <label for="">Departure Date and Time</label>
                                                                            <input type="datetime-local" id="simpleinput" readonly name="return_departure_time[]" value="${return_objectS.departure_time}" class="form-control departure_time1 dep_time" value="" >
                                                                        </div>
                                                                        <div class="col-xl-3" style="margin-top: 15px;">
                                                                            <label for="">Arrival Date and Time</label>
                                                                            <input type="datetime-local" readonly value="${return_objectS.arrival_time}" id="simpleinput" name="return_arrival_time[]" class="form-control arrival_time1 arr_time" value="" >
                                                                        </div>
                                                                    </div>
                                                                    <div class="row" style="margin-left: 320px">
                                                                        <div class="col-sm-3">
                                                                            <h3 style="width: 125px;margin-top: 25px;float:right" id="stop_No">`;
                                                                                if(return_flight_type == 'Direct'){
                                                                                    R_data += `Duration :`;
                                                                                }else{
                                                                                    R_data += `Stop # ${r_s_N} :`;
                                                                                }
                                                                R_data +=   `</h3>
                                                                        </div>
                                                                        <div class="col-sm-3">
                                                                             <label for="">Flight Duration</label>
                                                                            <input type="text" id="total_Time" readonly value="${return_objectS.total_Time}" name="return_total_Time[]" class="form-control total_Time1" readonly style="width: 170px;" value="">
                                                                        </div>
                                                                    </div>
                                                                </div>`;
                                                    $('#flight_append_div').append(R_data);
                                                    r_s_N++;
                                                });
                                                $('#all_return_appends').val(return_objectE);
                                            }
                                            
                                            // COSTING
                                            $('#no_of_pax_days').val(no_of_seats_count);
                                            var c_count = 1;
                                            var ACI_costing = `<div class="row" id="flight_price_section_adult_${id}" style="margin-top:10px;">
                                                                <h3>Adult Price Section</h3>
                                                                
                                                                <input type="hidden" name="flight_route_id_occupied_Pax[]" id="flight_route_id_occupied_${route_id}" value='${route_id}'>
                                                                
                                                                <input type="hidden" id="flight_occupied_seats_single_${id}" value="${adult_price_all_seatsD}">
                                                                    
                                                                <div class="col-xl-3">
                                                                    <label for="">Total Seats</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-btn input-group-append">
                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                        </span>
                                                                        <input type="text" id="flights_adult_seats_${id}" name="flights_adult_seats[]" class="form-control" value="${adult_price_all_seatsD}" onkeyup="flights_adult_seats_fun(${id})">
                                                                    </div>
                                                                </div>
                                            
                                                                <div class="col-xl-3">
                                                                    <label for="">Type</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-btn input-group-append">
                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                        </span>
                                                                        <input type="text" id="flights_adult_type_${id}" name="flights_adult_type[]" class="form-control" readonly value="Adult">
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="col-xl-3">
                                                                    <label for="">Cost Price/Seat</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-btn input-group-append">
                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                        </span>
                                                                        <input type="text" id="flights_cost_per_seats_adult_${id}" name="flights_cost_per_seats_adult[]" class="form-control" readonly value="${flights_per_person_price}">
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="col-xl-3">
                                                                    <label for="">Total Cost Price</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-btn input-group-append">
                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                        </span>
                                                                        <input type="text" id="flights_total_cost_adult_${id}" name="flights_total_cost_adult[]" class="form-control" readonly value="${adult_price_selected_seats}">
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="col-xl-3" style="margin-top:10px;">
                                                                    <label for="">Markup Type</label>
                                                                    <select id="flights_markup_type_adult_${id}" name="flights_markup_type_adult[]" class="form-control" onchange="flights_markup_type_adult_fun(${id})">
                                                                        <option>Select Markup</option>
                                                                        <option value="%">Percentage</option>
                                                                        <option value="<?php echo $currency; ?>">Fix Amount</option>
                                                                    </select>
                                                                </div>
                                            
                                                                <div class="col-xl-3 adult_markup_prices_${id}" style="margin-top:10px;display:none">
                                                                    <label for="">Markup</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-btn input-group-append">
                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                        </span>
                                                                        <input type="text" id="flights_markup_price_adult_${id}" name="flights_markup_price_adult[]" class="form-control" onkeyup="flights_markup_price_adult_fun(${id})">
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="col-xl-3 adult_markup_prices_${id}" style="margin-top:10px;display:none">
                                                                    <label for="">Sale Price/Seat</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-btn input-group-append">
                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                        </span>
                                                                        <input type="text" id="flights_sale_price_per_seat_adult_${id}" name="flights_sale_price_per_seat_adult[]" class="form-control" readonly>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="col-xl-3 adult_markup_prices_${id}" style="margin-top:10px;display:none">
                                                                    <label for="">Total Sale Price</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-btn input-group-append">
                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                        </span>
                                                                        <input type="text" id="flights_sale_price_adult_${id}" name="flights_sale_price_adult[]" class="form-control" readonly>
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>
                                                            
                                                            <div class="row" id="flight_price_section_child_${id}" style="margin-top:10px;">
                                                                <h3>Child Price Section</h3>
                                                                
                                                                <div class="col-xl-3">
                                                                    <label for="">Total Seats</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-btn input-group-append">
                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                        </span>
                                                                        <input type="text" id="flights_child_seats_${id}" name="flights_child_seats[]" class="form-control" value="0" onkeyup="flights_child_seats_fun(${id})">
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="col-xl-3">
                                                                    <label for="">Type</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-btn input-group-append">
                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                        </span>
                                                                        <input type="text" id="flights_child_type_${id}" name="flights_child_type[]" class="form-control" readonly value="Child">
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="col-xl-3">
                                                                    <label for="">Cost Price/Seat</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-btn input-group-append">
                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                        </span>
                                                                        <input type="text" id="flights_cost_per_seats_child_${id}" name="flights_cost_per_seats_child[]" class="form-control" readonly value="${flights_per_child_price}">
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="col-xl-3">
                                                                    <label for="">Total Cost Price</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-btn input-group-append">
                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                        </span>
                                                                        <input type="text" id="flights_total_cost_child_${id}" name="flights_total_cost_child[]" class="form-control" readonly value="0">
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="col-xl-3" style="margin-top:10px;">
                                                                    <label for="">Markup Type</label>
                                                                    <select id="flights_markup_type_child_${id}" name="flights_markup_type_child[]" class="form-control" onchange="flights_markup_type_child_fun(${id})">
                                                                        <option>Select Markup</option>
                                                                        <option value="%">Percentage</option>
                                                                        <option value="<?php echo $currency; ?>">Fix Amount</option>
                                                                    </select>
                                                                </div>
                                                                
                                                                <div class="col-xl-3 child_markup_prices_${id}" style="margin-top:10px;display:none">
                                                                    <label for="">Markup</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-btn input-group-append">
                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                        </span>
                                                                        <input type="text" id="flights_markup_price_child_${id}" name="flights_markup_price_child[]" class="form-control" onkeyup="flights_markup_price_child_fun(${id})">
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="col-xl-3 child_markup_prices_${id}" style="margin-top:10px;display:none">
                                                                    <label for="">Sale Price/Seat</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-btn input-group-append">
                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                        </span>
                                                                        <input type="text" id="flights_sale_price_per_seat_child_${id}" name="flights_sale_price_per_seat_child[]" class="form-control" readonly>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="col-xl-3 child_markup_prices_${id}" style="margin-top:10px;display:none">
                                                                    <label for="">Total Sale Price</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-btn input-group-append">
                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                        </span>
                                                                        <input type="text" id="flights_sale_price_child_${id}" name="flights_sale_price_child[]" class="form-control" readonly>
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>
                                                            
                                                            <div class="row" id="flight_price_section_infant_${id}" style="margin-top:10px;">
                                                                <h3>Infant Price Section</h3>
                                                                
                                                                <div class="col-xl-3">
                                                                    <label for="">Total Infants</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-btn input-group-append">
                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                        </span>
                                                                        <input type="text" id="flights_infant_seats_${id}" name="flights_infant_seats[]" class="form-control" value="0" onkeyup="flights_infant_seats_fun(${id})">
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="col-xl-3">
                                                                    <label for="">Type</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-btn input-group-append">
                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                        </span>
                                                                        <input type="text" id="flights_infant_type_${id}" name="flights_infant_type[]" class="form-control" readonly value="Infant">
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="col-xl-3">
                                                                    <label for="">Cost Price/Seat</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-btn input-group-append">
                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                        </span>
                                                                        <input type="text" id="flights_cost_per_seats_infant_${id}" name="flights_cost_per_seats_infant[]" class="form-control" readonly value="${flights_per_infant_price}">
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="col-xl-3">
                                                                    <label for="">Total Cost Price</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-btn input-group-append">
                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                        </span>
                                                                        <input type="text" id="flights_total_cost_infant_${id}" name="flights_total_cost_infant[]" class="form-control" readonly value="0">
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="col-xl-3" style="margin-top:10px;">
                                                                    <label for="">Markup Type</label>
                                                                    <select id="flights_markup_type_infant_${id}" name="flights_markup_type_infant[]" class="form-control" onchange="flights_markup_type_infant_fun(${id})">
                                                                        <option>Select Markup</option>
                                                                        <option value="%">Percentage</option>
                                                                        <option value="<?php echo $currency; ?>">Fix Amount</option>
                                                                    </select>
                                                                </div>
                                                                
                                                                <div class="col-xl-3 infant_markup_prices_${id}" style="margin-top:10px;display:none">
                                                                    <label for="">Markup</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-btn input-group-append">
                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                        </span>
                                                                        <input type="text" id="flights_markup_price_infant_${id}" name="flights_markup_price_infant[]" class="form-control" onkeyup="flights_markup_price_infant_fun(${id})">
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="col-xl-3 infant_markup_prices_${id}" style="margin-top:10px;display:none">
                                                                    <label for="">Sale Price/Seat</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-btn input-group-append">
                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                        </span>
                                                                        <input type="text" id="flights_sale_price_per_seat_infant_${id}" name="flights_sale_price_per_seat_infant[]" class="form-control" readonly>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="col-xl-3 infant_markup_prices_${id}" style="margin-top:10px;display:none">
                                                                    <label for="">Total Sale Price</label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-btn input-group-append">
                                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"><?php echo $currency; ?></a>
                                                                        </span>
                                                                        <input type="text" id="flights_sale_price_infant_${id}" name="flights_sale_price_infant[]" class="form-control" readonly>
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>`;
                                            $('#flight_append_div').append(ACI_costing);
                                            c_count = parseFloat(c_count) + 1;
                                            $('#c_count_id').val(c_count);
                                        }
                                    }
                                }
                            });
                        }else{
                            $('#flight_occupy_btn_'+id+'').css('display','');
                        }
                    },
                });
            }else{
                alert('Already Occupied');
            }
        }
    }
    
    function flight_delete_function(id){
        $('#flight_ocupancy_btn_switch_'+id+'').val(0);
        $('#flight_occupy_btn_'+id+'').css('display','');
        $('#flight_delete_btn_'+id+'').css('display','none');
        $('.main_div_flight_departure_'+id+'').remove();
        $('.main_div_flight_return_'+id+'').remove();
        $('#flight_price_section_adult_'+id+'').remove();
        $('#flight_price_section_child_'+id+'').remove();
        $('#flight_price_section_infant_'+id+'').remove();
        $('#no_of_pax_days').val(0);
        
        var routes_count_id     = $('#routes_count_id').val();
        routes_count_id         = parseFloat(routes_count_id);
        var no_of_seats_count   = 0;
        for(var i=1; i<routes_count_id; i++){
            var flight_ocupancy_btn_switch  = $('#flight_ocupancy_btn_switch_'+i+'').val();
            flight_ocupancy_btn_switch      = parseFloat(flight_ocupancy_btn_switch);
            if(flight_ocupancy_btn_switch != 0){
                var avalible_seats_selected = $('#avalible_seats_'+i+'').val();
                console.log('no_of_seats_count : '+no_of_seats_count);
                no_of_seats_count           = parseFloat(no_of_seats_count) + parseFloat(avalible_seats_selected);
                $('#no_of_pax_days').val(no_of_seats_count);
            }
        }
        
        flight_price_calculation(id);
    }
    
    // Adult
    function remove_divs_Adult(id){
        $('#flights_markup_price_adult_'+id+'').val('');
        $('#flights_sale_price_per_seat_adult_'+id+'').val('');
        $('#flights_sale_price_adult_'+id+'').val('');
        $('.adult_markup_prices_'+id+'').css('display','none');
        $('#flights_markup_type_adult_'+id+'').empty();
        var data = `<option>Select Markup</option>
                    <option value="%">Percentage</option>
                    <option value="<?php echo $currency; ?>">Fix Amount</option>`;
        $('#flights_markup_type_adult_'+id+'').append(data);
    }
    
    function flights_adult_seats_fun(id){
        remove_divs_Adult(id);
        
        var flight_occupied_seats_single    = $('#flight_occupied_seats_single_'+id+'').val();
        flight_occupied_seats_single        = parseFloat(flight_occupied_seats_single);
        
        var avalible_seats          = $('#no_of_pax_days').val();
        avalible_seats              = parseFloat(avalible_seats);
        
        var flights_adult_seats     = $('#flights_adult_seats_'+id+'').val();
        flights_adult_seats         = parseFloat(flights_adult_seats);
        
        var flights_child_seats     = $('#flights_child_seats_'+id+'').val();
        flights_child_seats         = parseFloat(flights_child_seats);
        
        if(flights_adult_seats > flight_occupied_seats_single){
            $('#flights_adult_seats_'+id+'').val(0);
        }else{
            var total_seat_occupied = flights_adult_seats + flights_child_seats;
            total_seat_occupied     = parseFloat(total_seat_occupied);
            if(total_seat_occupied > flight_occupied_seats_single){
                $('#flights_adult_seats_'+id+'').val(0);
            }else{
                var flights_cost_per_seats_adult    = $('#flights_cost_per_seats_adult_'+id+'').val();
                var total                           = flights_adult_seats * parseFloat(flights_cost_per_seats_adult);
                $('#flights_total_cost_adult_'+id+'').val(total); 
            }  
        }
    }
    
    function flights_markup_type_adult_fun(id){
        var flights_adult_seats     = $('#flights_adult_seats_'+id+'').val();
        var flights_adult_seatsD    = parseFloat(flights_adult_seats);
        if(flights_adult_seatsD > 0){
            var flights_markup_type_adult   = $('#flights_markup_type_adult_'+id+'').val();
            var flights_markup_type_adultD  = parseFloat(flights_markup_type_adult);
            if(flights_markup_type_adultD != ''){
                $('.adult_markup_prices_'+id+'').css('display','');
                $('#flights_markup_price_adult_'+id+'').val('');
                $('#flights_sale_price_per_seat_adult_'+id+'').val('');
                $('#flights_sale_price_adult_'+id+'').val('');
                
                $('#markup_type').empty();
                var data = `<option value="${flights_markup_type_adult}">${flights_markup_type_adult}</option>`;
                $('#markup_type').append(data);
                
            }else{
                alert('Select Markup Type');
            }
        }else{
            alert('Seats are empty')
        }
    }
    
    function flights_markup_price_adult_fun(id){
        var flights_markup_type_adult       = $('#flights_markup_type_adult_'+id+'').val();
        var flights_cost_per_seats_adult    = $('#flights_cost_per_seats_adult_'+id+'').val();
        var flights_total_cost_adult        = $('#flights_total_cost_adult_'+id+'').val();
        var flights_markup_price_adult      = $('#flights_markup_price_adult_'+id+'').val();
        var flights_adult_seats             = $('#flights_adult_seats_'+id+'').val();
        
        $('#flight_markup_btn').html(flights_markup_type_adult);
        
        if(flights_markup_type_adult == '%'){
            var total   = (flights_cost_per_seats_adult * flights_markup_price_adult/100) + parseFloat(flights_cost_per_seats_adult) ;
            var total_1 = total.toFixed(2);
            $('#flights_sale_price_per_seat_adult_'+id+'').val(total_1);
            
            var total2  = parseFloat(total_1) * parseFloat(flights_adult_seats) ;
            var total_2 = total2.toFixed(2);
            $('#flights_sale_price_adult_'+id+'').val(total_2);
        }else{
            var total   = parseFloat(flights_markup_price_adult) + parseFloat(flights_cost_per_seats_adult) ;
            var total_1 = total.toFixed(2);
            $('#flights_sale_price_per_seat_adult_'+id+'').val(total_1);
            
            var total2  = parseFloat(total_1) * parseFloat(flights_adult_seats) ;
            var total_2 = total2.toFixed(2);
            $('#flights_sale_price_adult_'+id+'').val(total_2);
        }
        
        flight_price_calculation(id);
    }
    
    // Child
    function remove_divs_Child(id){
        $('#flights_markup_price_child_'+id+'').val('');
        $('#flights_sale_price_per_seat_child_'+id+'').val('');
        $('#flights_sale_price_child_'+id+'').val('');
        $('.child_markup_prices_'+id+'').css('display','none');
        $('#flights_markup_type_child_'+id+'').empty();
        var data = `<option>Select Markup</option>
                    <option value="%">Percentage</option>
                    <option value="<?php echo $currency; ?>">Fix Amount</option>`;
        $('#flights_markup_type_child_'+id+'').append(data);
    }
    
    function flights_child_seats_fun(id){
        remove_divs_Child(id);
        
        var flight_occupied_seats_single    = $('#flight_occupied_seats_single_'+id+'').val();
        flight_occupied_seats_single        = parseFloat(flight_occupied_seats_single);
        
        var avalible_seats          = $('#no_of_pax_days').val();
        avalible_seats              = parseFloat(avalible_seats);
        
        var flights_adult_seats     = $('#flights_adult_seats_'+id+'').val();
        flights_adult_seats         = parseFloat(flights_adult_seats);
        
        var flights_child_seats     = $('#flights_child_seats_'+id+'').val();
        flights_child_seats         = parseFloat(flights_child_seats);
        
        if(flights_child_seats > flight_occupied_seats_single){
            $('#flights_child_seats_'+id+'').val(0);
        }else{
            var total_seat_occupied = flights_adult_seats + flights_child_seats;
            total_seat_occupied     = parseFloat(total_seat_occupied);
            if(total_seat_occupied > flight_occupied_seats_single){
                $('#flights_child_seats_'+id+'').val(0);
            }else{
                var flights_cost_per_seats_child    = $('#flights_cost_per_seats_child_'+id+'').val();
                var total                           = parseFloat(flights_child_seats) * parseFloat(flights_cost_per_seats_child);
                $('#flights_total_cost_child_'+id+'').val(total);
            }  
        }
    }
    
    function flights_markup_type_child_fun(id){
        var flights_child_seats     = $('#flights_child_seats_'+id+'').val();
        var flights_child_seatsD    = parseFloat(flights_child_seats);
        if(flights_child_seatsD > 0){
            var flights_markup_type_child = $('#flights_markup_type_child_'+id+'').val();
            if(flights_markup_type_child != ''){
                $('.child_markup_prices_'+id+'').css('display','');
                $('#flights_markup_price_child_'+id+'').val('');
                $('#flights_sale_price_per_seat_child_'+id+'').val('');
                $('#flights_sale_price_child_'+id+'').val('');
            }else{
                alert('Select Markup Type');
            }
        }else{
            alert('Seats are empty');
        }
    }
    
    function flights_markup_price_child_fun(id){
        var flights_markup_type_child       = $('#flights_markup_type_child_'+id+'').val();
        var flights_cost_per_seats_child    = $('#flights_cost_per_seats_child_'+id+'').val();
        var flights_total_cost_child        = $('#flights_total_cost_child_'+id+'').val();
        var flights_markup_price_child      = $('#flights_markup_price_child_'+id+'').val();
        var flights_child_seats             = $('#flights_child_seats_'+id+'').val();
        
        if(flights_markup_type_child == '%'){
            var total   = (flights_cost_per_seats_child * flights_markup_price_child/100) + parseFloat(flights_cost_per_seats_child) ;
            var total_1 = total.toFixed(2);
            $('#flights_sale_price_per_seat_child_'+id+'').val(total_1);
            
            var total2  = parseFloat(total_1) * parseFloat(flights_child_seats) ;
            var total_2 = total2.toFixed(2);
            $('#flights_sale_price_child_'+id+'').val(total_2);
        }else{
            var total   = parseFloat(flights_markup_price_child) + parseFloat(flights_cost_per_seats_child) ;
            var total_1 = total.toFixed(2);
            $('#flights_sale_price_per_seat_child_'+id+'').val(total_1);
            
            var total2  = parseFloat(total_1) * parseFloat(flights_child_seats) ;
            var total_2 = total2.toFixed(2);
            $('#flights_sale_price_child_'+id+'').val(total_2);
        }
        
       flight_price_calculation(id);
    }
    
    // Infant
    function remove_divs_Infant(id){
        $('#flights_markup_price_infant_'+id+'').val('');
        $('#flights_sale_price_per_seat_infant_'+id+'').val('');
        $('#flights_sale_price_infant_'+id+'').val('');
        $('.infant_markup_prices_'+id+'').css('display','none');
        $('#flights_markup_type_infant_'+id+'').empty();
        var data = `<option>Select Markup</option>
                    <option value="%">Percentage</option>
                    <option value="<?php echo $currency; ?>">Fix Amount</option>`;
        $('#flights_markup_type_infant_'+id+'').append(data);
    }
    
    function flights_infant_seats_fun(id){
        remove_divs_Infant(id);
        var flights_infant_seats    = $('#flights_infant_seats_'+id+'').val();
        flights_infant_seats        = parseFloat(flights_infant_seats);
        var flights_cost_per_seats_infant    = $('#flights_cost_per_seats_infant_'+id+'').val();
        var total                            = parseFloat(flights_infant_seats) * parseFloat(flights_cost_per_seats_infant);
        $('#flights_total_cost_infant_'+id+'').val(total);
    }
    
    function flights_markup_type_infant_fun(id){
        // var flights_infant_seats    = $('#flights_infant_seats_'+id+'').val();
        // var flights_infant_seatsD   = parseFloat(flights_infant_seats);
        // if(flights_infant_seatsD > 0){
            var flights_markup_type_infant = $('#flights_markup_type_infant_'+id+'').val();
            if(flights_markup_type_infant != ''){
                $('.infant_markup_prices_'+id+'').css('display','');
                $('#flights_markup_price_infant_'+id+'').val('');
                $('#flights_sale_price_per_seat_infant_'+id+'').val('');
                $('#flights_sale_price_infant_'+id+'').val('');
            }else{
                alert('Select Markup Type');
            }
        // }else{
            // alert('Seats are empty');
        // }
    }
    
    function flights_markup_price_infant_fun(id){
        var flights_markup_type_infant       = $('#flights_markup_type_infant_'+id+'').val();
        var flights_cost_per_seats_infant    = $('#flights_cost_per_seats_infant_'+id+'').val();
        var flights_total_cost_infant        = $('#flights_total_cost_infant_'+id+'').val();
        var flights_markup_price_infant      = $('#flights_markup_price_infant_'+id+'').val();
        var flights_infant_seats             = $('#flights_infant_seats_'+id+'').val();
        
        if(flights_markup_type_infant == '%'){
            var total   = (flights_cost_per_seats_infant * flights_markup_price_infant/100) + parseFloat(flights_cost_per_seats_infant) ;
            var total_1 = total.toFixed(2);
            $('#flights_sale_price_per_seat_infant_'+id+'').val(total_1);
            
            var total2  = parseFloat(total_1) * parseFloat(flights_infant_seats) ;
            var total_2 = total2.toFixed(2);
            $('#flights_sale_price_infant_'+id+'').val(total_2);
        }else{
            var total   = parseFloat(flights_markup_price_infant) + parseFloat(flights_cost_per_seats_infant) ;
            var total_1 = total.toFixed(2);
            $('#flights_sale_price_per_seat_infant_'+id+'').val(total_1);
            
            var total2  = parseFloat(total_1) * parseFloat(flights_infant_seats) ;
            var total_2 = total2.toFixed(2);
            $('#flights_sale_price_infant_'+id+'').val(total_2);
        }
        
        flight_price_calculation(id);
    }
    
    function flight_price_calculation(id){
        var total_append    = $('#routes_count_id').val();
        var flights_TCA     = 0;
        var flights_MPA     = 0;
        var flights_SPA     = 0;
        
        for(var i=1; i<=total_append ;i++){
            // Adult
            var flights_total_cost_adult    = $('#flights_total_cost_adult_'+i+'').val();
            var flights_markup_price_adult  = $('#flights_markup_price_adult_'+i+'').val();
            var flights_sale_price_adult    = $('#flights_sale_price_adult_'+i+'').val();
            
            if(flights_total_cost_adult != null && flights_total_cost_adult != ''){
                flights_TCA = parseFloat(flights_TCA) +  parseFloat(flights_total_cost_adult);
                $('#flights_prices').val(flights_TCA);
            }
            
            if(flights_markup_price_adult != null && flights_markup_price_adult != ''){
                flights_MPA = parseFloat(flights_MPA) +  parseFloat(flights_markup_price_adult);
                $('#markup_value').val(flights_MPA);
            }
            
            if(flights_sale_price_adult != null && flights_sale_price_adult != ''){
                flights_SPA = parseFloat(flights_SPA) +  parseFloat(flights_sale_price_adult);
                $('#total_markup').val(flights_SPA);
            }
            
            // Child
            var flights_total_cost_child    = $('#flights_total_cost_child_'+i+'').val();
            var flights_markup_price_child  = $('#flights_markup_price_child_'+i+'').val();
            var flights_sale_price_child    = $('#flights_sale_price_child_'+i+'').val();
            
            if(flights_total_cost_child != null && flights_total_cost_child != ''){
                flights_TCA = parseFloat(flights_TCA) +  parseFloat(flights_total_cost_child);
                $('#flights_prices').val(flights_TCA);
            }
            
            if(flights_markup_price_child != null && flights_markup_price_child != ''){
                flights_MPA = parseFloat(flights_MPA) +  parseFloat(flights_markup_price_child);
                $('#markup_value').val(flights_MPA);
            }
            
            if(flights_sale_price_child != null && flights_sale_price_child != ''){
                flights_SPA = parseFloat(flights_SPA) +  parseFloat(flights_sale_price_child);
                $('#total_markup').val(flights_SPA);
            }
            
            // Infant
            var flights_total_cost_infant    = $('#flights_total_cost_infant_'+i+'').val();
            var flights_markup_price_infant  = $('#flights_markup_price_infant_'+i+'').val();
            var flights_sale_price_infant    = $('#flights_sale_price_infant_'+i+'').val();
            
            if(flights_total_cost_infant != null && flights_total_cost_infant != ''){
                flights_TCA = parseFloat(flights_TCA) +  parseFloat(flights_total_cost_infant);
                $('#flights_prices').val(flights_TCA);
            }
            
            if(flights_markup_price_infant != null && flights_markup_price_infant != ''){
                flights_MPA = parseFloat(flights_MPA) +  parseFloat(flights_markup_price_infant);
                $('#markup_value').val(flights_MPA);
            }
            
            if(flights_sale_price_infant != null && flights_sale_price_infant != ''){
                flights_SPA = parseFloat(flights_SPA) +  parseFloat(flights_sale_price_infant);
                $('#total_markup').val(flights_SPA);
            }
            
            add_numberElseI();
        }
    }
    
    // Flights End
    
</script>

<!--Flights End-->

<script>
    $(document).ready(function() {
        var value_c = $("#currency_conversion1").val();
        const usingSplit = value_c.split(' ');
        var value_1 = usingSplit['0'];
        var value_2 = usingSplit['2'];
        exchange_currency_funs(value_1,value_2);
        var attr_conversion_type = $("#currency_conversion1").find('option:selected').attr('attr_conversion_type');
        if(attr_conversion_type != 0){
            $("#select_exchange_type").val(attr_conversion_type);
        }
        
        $('.visa_C_S').empty();
        $('.visa_C_S').html(value_1);
        
        $('.more_visa_C_S').empty();
        $('.more_visa_C_S').html(value_1);
    });

    $(document).ready(function() {
        $('.summernote').summernote({});
    });
    
    $("#destination").on('click',function(){
        $("#destination").slideToggle();
        $("#select_destination").css('display','');
    });
</script>

<script>
    var services_selected  = {!!json_encode($get_invoice->services)!!};
    
    $(document).ready(function() {
        
        // Services
        var selectedServices = $('#select_services').val();
        console.log(selectedServices);
        
        $.each(selectedServices, function(key, value) {
            if(value != null && value != ''){
                if((value == '1') || (value == 'accomodation_tab')){
                    $('.start_date_label').html('Start Date');
                    $('.more_Div_Acc_All').css('display','');
                    $('.no_of_Nights_Other').html('No Of Nights');
                    $('.start_date_div').css('display','');
                    $('.end_date_div').css('display','');
                    $('.total_duration_div').css('display','');
                }else if(value == 'flights_tab'){
                    $('.start_date_label').html('Departure');
                    $('.start_date_div').css('display','');
                    $('.end_date_div').css('display','none');
                    $('.total_duration_div').css('display','none');
                    $('.more_Div_Acc_All').css('display','none');
                }
                else if(value == 'visa_tab'){
                    $('.start_date_div').css('display','none');
                    $('.end_date_div').css('display','none');
                    $('.total_duration_div').css('display','none');
                    $('.more_Div_Acc_All').css('display','none');
                }else{
                    $('.start_date_label').html('Start Date');
                    $('.start_date_div').css('display','');
                    $('.end_date_div').css('display','');
                    $('.total_duration_div').css('display','');
                    $('.more_Div_Acc_All').css('display','none');
                    $('.no_of_Nights_Other').html('Total Duration');
                }
            }
        });
        
        $.each(selectedServices, function(key, value) {
            
            if(value == '1'){
                $('.accomodation_tab').css('display','');
                $('.flights_tab').css('display','');
                $('#flights_cost').css('display','');
                $('.visa_tab').css('display','');
                $('.transportation_tab').css('display','');
                $('#transportation_cost').css('display','');
            }
            if(value == 'accomodation_tab'){
                $('.accomodation_tab').css('display','');
            }
            if(value == 'flights_tab'){
                $('.flights_tab').css('display','');
                $('#flights_cost').css('display','');
            }
            if(value == 'visa_tab'){
                $('.visa_tab').css('display','');
            }
            if(value == 'transportation_tab'){
                $('.transportation_tab').css('display','');
                $('#transportation_cost').css('display','');
            }
        });
        
        const allServicesSelect = selectedServices.find(element => element == 1);
    
        if(allServicesSelect){
            $(".accomodation_tab").css('display','block');
            $(".flights_tab").css('display','block');
            $(".visa_tab").css('display','block');
            $(".transportation_tab").css('display','block');
        }else{
            $(".accomodation_tab").css('display','none');
            $(".flights_tab").css('display','none');
            $(".visa_tab").css('display','none');
            $(".transportation_tab").css('display','none');
            for(var i=0; i < selectedServices.length; i++){
                $("."+selectedServices[i]+"").css('display','flex');
            }
        }
        // End Services
        
        var ifchecked=$('#NotIncluded').prop('checked', true);
        if(ifchecked){
          $('#NotIncluded_with_price').fadeIn();   
        }
    });
    
    $(document).on('click','#NotIncluded' ,function(){
    
     
     $('#NotIncluded_with_price').fadeIn(); 
    });
    
    $(document).on('click','#Included' ,function(){
     
     $('#NotIncluded_with_price').fadeOut(); 
    });
    
    $('#NotIncluded_with_price_meal').keyup(function() {
        var NotIncluded_with_price =  $('#NotIncluded_with_price_meal').val();
        console.log('NotIncluded_with_price' + NotIncluded_with_price);
        var hotel_meal_types=$('.hotel_meal_types').val();
        $('#meal_type').val(hotel_meal_types);
        $('#meal_type_with_out_markup_price').val(NotIncluded_with_price);
    });
    
    $(document).on('change','#meal_type_with_markup' ,function(){
      var vale = $('#meal_type_with_markup option:selected').val();
      console.log('vale'+vale);
      $('#meal_markup_mrk').text(vale);
      
    });

    $('#meal_type_with_markup_amout').keyup(function() {
            var with_out_markup_price = $('#meal_type_with_out_markup_price').val();
            var meal_type_with_markup  = $('#meal_type_with_markup').val();
            var markup_price = $('#meal_type_with_markup_amout').val();
            if(meal_type_with_markup == '%')
            {
               
               
                    var total1 = (with_out_markup_price * markup_price/100) + parseFloat(with_out_markup_price);
                    var total = total1.toFixed(2);
                    $('#meal_type_with_markup_sale_amout').val(total);
                   
                
            }
            else
            {
                
                    var total1 = parseFloat(with_out_markup_price) + parseFloat(markup_price);
                    var total = total1.toFixed(2);
                    $('#meal_type_with_markup_sale_amout').val(total);
                    
                
            }
        });

    $(document).on('change','#select_services' ,function(){
  var val = $('#select_services option:selected').val();
 //alert(val);
 if(val == 'flights_tab')
 {
     $("#accomodation_price_hide").css('display','none');
     $("#sale_pr").css('display','none');
 }
 if(val == 'visa_tab')
 {
     $("#accomodation_price_hide").css('display','none');
     $("#sale_pr").css('display','none');
 }
 if(val == 'transportation_tab')
 {
      $("#accomodation_price_hide").css('display','none');
     $("#sale_pr").css('display','none');
 }
  

})

    function validate1(id) {
    	$('.file_error'+id+'').html("");
    	$('.gallery_imagesP'+id+'').css("border-color","#F0F0F0");
    	var file_size = $('.gallery_imagesP'+id+'')[0].files[0].size;
    	if(file_size > 100000) {
    		$('.file_error'+id+'').html("File size is greater than 100kb");
    		$('.gallery_imagesP'+id+'').css("border-color","#FF0000");
    		return false;
    	} 
    	return true;
    }
    
    function selectServices(){
        var selectedServices = $('#select_services').val();
        
        $.each(selectedServices, function(key, value) {
            if(value != null && value != ''){
                if((value == '1') || (value == 'accomodation_tab')){
                    $('.start_date_label').html('Start Date');
                    $('.more_Div_Acc_All').css('display','');
                    $('.no_of_Nights_Other').html('No Of Nights');
                    $('.start_date_div').css('display','');
                    $('.end_date_div').css('display','');
                    $('.total_duration_div').css('display','');
                }else if(value == 'flights_tab'){
                    $('.start_date_label').html('Departure');
                    $('.start_date_div').css('display','');
                    $('.end_date_div').css('display','none');
                    $('.total_duration_div').css('display','none');
                    $('.more_Div_Acc_All').css('display','none');
                }
                else if(value == 'visa_tab'){
                    $('.start_date_div').css('display','none');
                    $('.end_date_div').css('display','none');
                    $('.total_duration_div').css('display','none');
                    $('.more_Div_Acc_All').css('display','none');
                }else{
                    $('.start_date_label').html('Start Date');
                    $('.start_date_div').css('display','');
                    $('.end_date_div').css('display','');
                    $('.total_duration_div').css('display','');
                    $('.more_Div_Acc_All').css('display','none');
                    $('.no_of_Nights_Other').html('Total Duration');
                }
            }
        });
        
        const allServicesSelect = selectedServices.find(element => element == 1);
        
        if(allServicesSelect){
            $(".accomodation_tab").css('display','block');
            $(".flights_tab").css('display','block');
            $(".visa_tab").css('display','block');
            $(".transportation_tab").css('display','block');
        }else{
            $(".accomodation_tab").css('display','none');
            $(".flights_tab").css('display','none');
            $(".visa_tab").css('display','none');
            $(".transportation_tab").css('display','none');
            for(var i=0; i < selectedServices.length; i++){
                $("."+selectedServices[i]+"").css('display','flex');
            }
        }
    }
    
    var passengerCounter = 1;
    $('#no_of_pax_days').on('change',function(){
        var no_of_pax_days = $('#no_of_pax_days').val();
        var prev_value = $('#no_of_pax_prev').val();
        
        var difference = no_of_pax_days - prev_value;
        
        passengerCounter = +passengerCounter + +difference
         
         
         for(var i = prev_value; i<=no_of_pax_days; i++){
             id = +i + +1;
             console.log('id is now '+id);
         }
         
         $('#no_of_pax_prev').val(no_of_pax_days);
         var passengerHtml = `<div class="row other_passengers" style="border: 1px solid #ebebeb;padding: 1rem;border-radius: 6px;">
                                            <div class="col-xl-6">
                                                <div class="mb-3">
                                                    <label for="simpleinput" class="form-label no_of_pax_days">First Name</label>
                                                    <input type="text" name="lead_fname" class="form-control" required>
                                                </div>
                                            </div>
                                            
                                             <div class="col-xl-6">
                                                <div class="mb-3">
                                                    <label for="simpleinput" class="form-label no_of_pax_days">Last Name</label>
                                                    <input type="text" name="lead_fname" class="form-control" required>
                                                </div>
                                            </div>
                                            
                                                    
                                            <div class="col-xl-4">
                                                <div class="mb-3">
                                                    <label for="simpleinput" class="form-label no_of_pax_days">Select Nationality</label>
                                                     <select type="text" class="form-control select2 " name="Country"  data-placeholder="Choose ...">
                                                        @foreach($all_countries as $country_res)
                                                            <option value="{{$country_res->id}}" id="categoriesPV" required>{{$country_res->name}}</option>
                                                           
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-xl-2" >
                                                <div class="mb-3">
                                                     <p style="margin-top: 2.2rem;">Gender</p>
                                                </div>
                                            </div>
                                            
                                               
                                            <div class="col-2">
                                              <div class="form-check" style="margin-top: 2.2rem;">
                                              <input class="form-check-input" type="radio" name="gender" value="male"  id="flexRadioDefault3">
                                              <label class="form-check-label" for="flexRadioDefault3">
                                                Male
                                              </label>
                                            </div>
                                            
                                            </div>
                                            <div class="col-2" style="margin-top: 2.2rem;">
                                                <div class="form-check">
                                              <input class="form-check-input" type="radio" name="gender" value="female"  id="flexRadioDefault4">
                                              <label class="form-check-label" for="flexRadioDefault4">
                                                Female
                                              </label>
                                            </div>
                                            </div>
                                        </div>`
    })
    
</script>

<script>
        
    $('#save_Package').on('click',function(e){
        e.preventDefault();
        
        // var data = $('#formP').serialize();
        // console.log(data);
        var title = $(".titleP").val();
        var no_of_pax_days = $(".no_of_pax_days").val();
        var starts_rating = $(".starts_rating").val();
        var currency_symbol = $(".currency_symbol").val();
        var content = $("#snow-editor").val();
        var start_date = $(".start_date").val();
        var end_date = $(".end_date").val();
        var time_duration = $(".time_duration").val();
        var categories = $(".categories").val();
        var tour_feature = $(".tour_feature").val();
        var defalut_state = $(".defalut_state").val();
        var tour_featured_image = $(".tour_featured_imageP").val();
        // console.log(tour_featured_image);
        var tour_banner_image = $(".tour_banner_imageP").val();
        // console.log(tour_banner_image);
        var tour_publish = $(".tour_publish").val();
        var tour_author = $(".tour_author").val();
        var external_packages = $(".external_packages").val();
        // console.log(external_packages);
        var gallery_images = $('.gallery_imagesP').val();
        // console.log(gallery_images);
        $.ajax({    
            type: "POST",
            url: "save_Package",
            data:{
                "_token"                : "{{ csrf_token() }}",
                // 'data'                  : data,
                'title'                 : title,
                'no_of_pax_days'        : no_of_pax_days,
                'starts_rating'         : starts_rating,
                'currency_symbol'       : currency_symbol,
                'content'               : content,
                'start_date'            : start_date,
                'end_date'              : end_date,
                'time_duration'         : time_duration,
                'categories'            : categories,
                'tour_feature'          : tour_feature,
                'defalut_state'         : defalut_state,
                'tour_featured_image'   : tour_featured_image,
                'tour_banner_image'     : tour_banner_image,
                'tour_publish'          : tour_publish,
                'tour_author'           : tour_author,
                'external_packages'     : external_packages,
                'gallery_images'        : gallery_images,
            },
            success: function(data){
                // alert('Package Details Saved SuccessFUl!');
                id = JSON.parse(data['id']);
                $('#save_Accomodation').val(id);
                $('#save_Flights').val(id);
                $('#save_Visa').val(id);
                $('#save_Transportation').val(id);
                $('#save_Itinerary').val(id);
                $('#save_Costing').val(id);
            }
        });
            
    });
    
    $('#save_Flights').on('click',function(e){
        e.preventDefault();
        var id  = $(this).val();
        console.log('id :' +id);
    });
    
    $('#save_Visa').on('click',function(e){
        e.preventDefault();
        var id  = $(this).val();
        console.log('id :' +id);
    });
    
    $('#save_Transportation').on('click',function(e){
        e.preventDefault();
        var id  = $(this).val();
        console.log('id :' +id);
    });
    
    $('#save_Itinerary').on('click',function(e){
        e.preventDefault();
        var id  = $(this).val();
        console.log('id :' +id);
    });
    
    $('#save_Costing').on('click',function(e){
        e.preventDefault();
        var id  = $(this).val();
        console.log('id :' +id);
    });
</script>

<script>
 
    $("#switch2").click(function () {
        $('#markup_services').slideToggle();
        $('#all_services_markup').slideToggle();
        $('#markup_seprate_services').slideToggle();
        $('#sale_pr').slideToggle();
        
        var switchValue = $('#markupSwitch').val();
        if(switchValue == 'single_markup_switch'){
            $('#markupSwitch').val('all_markup_switch');
        }else{
             $('#markupSwitch').val('single_markup_switch');
        }
    });
 
    $("#all_markup_type").change(function () {
    var id = $(this).find('option:selected').attr('value');
    
        $('#select_mrk').text(id);
    if(id == '%')
    {
        $('#all_markup_add').keyup(function() {
            
   var markup_val =  $('#all_markup_add').val();
   
       var quad_cost_price   =  $('#quad_cost_price').val();
       var triple_cost_price =  $('#triple_cost_price').val();
       var double_cost_price =  $('#double_cost_price').val();
       
        if(quad_cost_price != 0){
            var total_quad_cost_price1 = (quad_cost_price * markup_val/100) + parseFloat(quad_cost_price);
            var total_quad_cost_price = total_quad_cost_price1.toFixed(2); 
            $('#quad_markup').val(total_quad_cost_price);   
        }else{
           $('#quad_markup').val(0);
        }
   
      
        if(triple_cost_price != 0){
            var total_triple_cost_price1 = (triple_cost_price * markup_val/100) + parseFloat(triple_cost_price);
            var total_triple_cost_price = total_triple_cost_price1.toFixed(2);
            $('#triple_markup').val(total_triple_cost_price);    
        }else{
            $('#triple_markup').val(0);
        }
        
        if(double_cost_price != 0){
            var total_double_cost_price1 = (double_cost_price * markup_val/100) + parseFloat(double_cost_price);
            var total_double_cost_price = total_double_cost_price1.toFixed(2);
            $('#double_markup').val(total_double_cost_price);
        }else{
            $('#double_markup').val(0);
        }
        
        
        
 
});
       
       
    }
    else
    {
       $('#all_markup_add').keyup(function() {
            
   var markup_val =  $('#all_markup_add').val();
   
        var quad_cost_price =  $('#quad_cost_price').val();
        var triple_cost_price =  $('#triple_cost_price').val();
        var double_cost_price =  $('#double_cost_price').val();
        
        if(quad_cost_price != 0){
            var total_quad_cost_price1 =  parseFloat(quad_cost_price) +  parseFloat(markup_val);
            var total_quad_cost_price = total_quad_cost_price1.toFixed(2); 
            $('#quad_markup').val(total_quad_cost_price);  
        }else{
           $('#quad_markup').val(0);
        }
   
      
        if(triple_cost_price != 0){
            var total_triple_cost_price1 =  parseFloat(triple_cost_price) +  parseFloat(markup_val);
            var total_triple_cost_price = total_triple_cost_price1.toFixed(2);
            $('#triple_markup').val(total_triple_cost_price);    
        }else{
            $('#triple_markup').val(0);
        }
        
        if(double_cost_price != 0){
            var total_double_cost_price1 = parseFloat(double_cost_price) +  parseFloat(markup_val);
            var total_double_cost_price = total_double_cost_price1.toFixed(2);
            $('#double_markup').val(total_double_cost_price);
        }else{
            $('#double_markup').val(0);
        }
   
        // var total_quad_cost_price1 =  parseFloat(quad_cost_price) +  parseFloat(markup_val);
        // var total_quad_cost_price = total_quad_cost_price1.toFixed(2); 
        // $('#quad_markup').val(total_quad_cost_price);
        
        // var total_triple_cost_price1 =  parseFloat(triple_cost_price) +  parseFloat(markup_val);
        // var total_triple_cost_price = total_triple_cost_price1.toFixed(2);
        // $('#triple_markup').val(total_triple_cost_price);
        
        // var total_double_cost_price1 = parseFloat(double_cost_price) +  parseFloat(markup_val);
        // var total_double_cost_price = total_double_cost_price1.toFixed(2);
        // $('#double_markup').val(total_double_cost_price);
        
        
 
});
     
    }
      
  

  });

    $(document).on('click','#visa_inc',function(){
        $.ajax({    
           
            url: "super_admin/get_other_visa_type_detail", 
            type: "GET",
            dataType: "html",                  
            success: function(data){  
                var data1 = JSON.parse(data);
                var data2= JSON.parse(data1['visa_type']);
                $('#visa_type_select').val(data2['visa_type'][0]['other_visa_type']);
            	$("#visa_type").empty();
                $.each(data2['visa_type'], function(key, value) {
                    var visa_type_Data = `<option attr="${value.other_visa_type}" value="${value.other_visa_type}"> ${value.other_visa_type}</option>`;
                    $("#visa_type").append(visa_type_Data);
                });  
            }
        });
    });

    $('#submitForm_PUL').on('click',function(e){
        e.preventDefault();
        let pickup_location = $('#pickup_location').val();
        $.ajax({
            url: "submit_pickup_location",
            type:"POST",
            data:{
                "_token": "{{ csrf_token() }}",
                pickup_location:pickup_location,
            },
            success:function(response){
                if(response){
                    var data1 = JSON.parse(response)
                    var data = data1['pickup_location_get'];
                    $(".pickup_location").empty();
                    $.each(data, function(key, value) {
                        var pickup_location_Data = `<option attr="${value.pickup_location}" value="${value.pickup_location}"> ${value.pickup_location}</option>`;
                        $(".pickup_location").append(pickup_location_Data);
                    });
                    alert('Pickup Location Added SuccessFUl!');
                }
                $('#success-message').text(response.success);
            },
        });
    });
    
    $('#submitForm_DOL').on('click',function(e){
        e.preventDefault();
        let dropof_location = $('#dropof_location').val();
        $.ajax({
            url: "submit_dropof_location",
            type:"POST",
            data:{
                "_token": "{{ csrf_token() }}",
                dropof_location:dropof_location,
            },
            success:function(response){
                if(response){
                    var data1 = JSON.parse(response)
                    var data = data1['dropof_location_get'];
                    $(".dropof_location").empty();
                    $.each(data, function(key, value) {
                        var dropof_location_Data = `<option attr="${value.dropof_location}" value="${value.dropof_location}"> ${value.dropof_location}</option>`;
                        $(".dropof_location").append(dropof_location_Data);
                    });
                    alert('DropOf Location Added SuccessFUl!');
                }
                $('#success-message').text(response.success);
            },
        });
    });
    
    $('#submitForm').on('click',function(e){
        e.preventDefault();
        let other_visa_type = $('#other_visa_type').val();
        $.ajax({
            url: "submit_other_visa_type",
            type:"POST",
            data:{
                "_token": "{{ csrf_token() }}",
                other_visa_type:other_visa_type,
            },
            success:function(response){
                if(response){
                    var data1 = JSON.parse(response)
                    var data = data1['visa_type_get'];
                    console.log(data);
                    $("#visa_type").empty();
                    $.each(data, function(key, value) {
                        var visa_type_Data = `<option attr="${value.other_visa_type}" value="${value.other_visa_type}"> ${value.other_visa_type}</option>`;
                        $("#visa_type").append(visa_type_Data);
                    });
                    alert('Visa Other Type Added SuccessFUl!');
                }
                $('#success-message').text(response.success);
            },
        });
    });
    
    $('#submitForm_Airline_Name').on('click',function(e){
        e.preventDefault();
        let other_Airline_Name = $('#other_Airline_Name').val();
        $.ajax({
            url: "submitForm_Airline_Name",
            type:"POST",
            data:{
                "_token": "{{ csrf_token() }}",
                other_Airline_Name:other_Airline_Name,
            },
            success:function(response){
                // console.log(response);
                if(response){
                    var data1 = response;
                    var data = data1['other_Airline_Name_get'];
                    // console.log(data);
                    $("#other_Airline_Name2").empty();
                    $("#return_other_Airline_Name2").empty();
                    $.each(data, function(key, value) {
                        // console.log(value.other_Airline_Name);
                        $("#other_Airline_Name2").append('<option attr=' +value.other_Airline_Name+ ' value=' +value.other_Airline_Name+ '>' +value.other_Airline_Name+'</option>');
                        $("#return_other_Airline_Name2").append('<option attr=' +value.other_Airline_Name+ ' value=' +value.other_Airline_Name+ '>' +value.other_Airline_Name+'</option>');
                    });
                    alert('Other Airline Name Added SuccessFUl!');
                }
                $('#success-message').text(response.success);
            },
        });
    });
    
</script>

<script>

    $("#Itinerary").on('click',function(){
        $("#Itinerary_select").slideToggle();
    });
    
    $("#extra_price").on('click',function(){
        $("#extraprice_select").slideToggle();
    });
    
    $("#faq").on('click',function(){
        $("#faq_select").slideToggle();
    });
    
    $("#destination1").on('click',function(){
        $("#select_destination1").slideToggle();
    });
    
    $("#flights_inc").on('click',function(){
        $("#select_flights_inc").slideToggle();
        // $('#flights_cost').toggle();
    });

    $("#transportation").on('click',function(){
        $("#select_transportation").slideToggle();
        $('#transportation_cost').toggle();
    });
    
    var divId = 1
    $("#click_more_Itinerary").click(function(){
        var data = `<div class="row" style="border: 2px solid #eef2f7;padding: 10px 10px 10px 10px;" id="click_delete_${divId}">
                        <div class="col-xl-6">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Title</label>
                                    <input type="text" id="simpleinput" name="more_Itinerary_title[]" class="form-control">
                                </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Short Description</label>
                                <input type="text" id="simpleinput" name="more_Itinerary_city[]" class="form-control">
                            </div>
                        </div>
                        <div class="col-xl-12">
                            <div class="mb-3">
                                <label for="simpleinput" class="form-label">Content</label>
                                <textarea name="more_Itinerary_content[]" class="form-control" id="" cols="10" rows="10"></textarea>
                            </div>
                        </div>
                        <div class="col-xl-12">
                            <!--<div class="mb-3">
                                <label for="simpleinput" class="form-label">image</label>
                                <input type="file" id="simpleinput" name="more_Itinerary_image[]" class="form-control">
                            </div>!-->
                            <button style="float: right;" type="button" class="btn btn-info deletButton" onclick="deleteRow(${divId})"  id="${divId}">Delete</button>
                        </div>
                    </div>`
                    ;
        $("#append_data").append(data);
        divId++;
    });
    
    function deleteRow(id){
        $('#click_delete_'+id+'').remove();
    }
    
    function deleteRow_extra(id){
        $('#click_delete_'+id+'').remove();
    }
    
    function deleteRow_faq(id){
        $('#click_delete_'+id+'').remove();
    }
    
    function remove_hotels(id){
            
            $('#del_hotel'+id+'').remove();
            $('#costing_acc'+id+'').remove();
            
            var city_No1 = $('#city_No').val();
            var city_No = parseFloat(city_No1) - 1;
            $('#city_No').val(city_No);
            
            put_tour_location();
            put_tour_location_else();
            add_numberElse();
        }
    
</script>

<script>
    var divId = 1
    $("#more_destination").click(function(){
        var data = `<div class="row" id="click_delete_${divId}"><div class="col-xl-6"><label for="">COUNTRY</label><select name="more_destination_country" onclick="selectCities_on(${divId})"  id="destination_${divId}" class="form-control">@foreach($all_countries as $country_res)<option value="{{ $country_res->id }}">{{ $country_res->name}}</option>@endforeach</select></div><div class="col-xl-6"><label for="">City</label><select name="more_destination_city[]" id="destination_city_${divId}" class="select2 form-control select2-multiple select_ct" data-toggle="select2" multiple="multiple" data-placeholder="Choose ..."></select></div><div class="col-xl-12 mt-2"><button style="float: right;" type="button" class="btn btn-info deletButton" onclick="deleteRowDes(${divId})"  id="${divId}">Delete</button></div></div>`
                    ;
  $("#append_destination").append(data);
  
  
  
  
  
  
  
  
  divId++;
});

</script>

<script>

function selectCities_on(id){
      var country = $('#destination_'+id+'').val();
      console.log(country);
      $.ajaxSetup({
         headers: {
             'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
         }
      });
      $.ajax({
         url: "{{ url('/country_cites') }}",
         method: 'post',
         data: {
             "_token": "{{ csrf_token() }}",
             "id": country,
         },
         success: function(result){
             console.log('cites is call now');
           console.log(result);
           $('#destination_city_'+id+'').html(result);
         },
         error:function(error){
             console.log(error);
         }
      });
   }


 function deleteRowDes(id){
  
     $('#click_delete_'+id+'').remove();
     

 }
 
 

</script>

<script>
    function selectCountry(id,val,shortcode) {
      var suggesstion=  $("#"+id).attr("data-suggestion");
      var shrtcode   =  $("#"+id).parent(".flight").children(".shrtcode");
      $("#"+id).val(val);
      $("#"+suggesstion).hide();
      $(shrtcode).val(shortcode);
    }
  </script>

<script>
    $(".get_flight_name").keyup(function(){

      console.log('This is call ');
      var searchinput = $(this);
      var suggesstion=  $(searchinput).attr("data-suggestion");
      var id = $(this).attr("id");
      var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
      var keyword = $(this).val();
      // console.log('This is call id is '+id+' data suggesstion '+suggesstion);
      $.ajax({
        type: "POST",
        url: '{{URL::to('get_flight_name')}}',
        data: {
          _token    : CSRF_TOKEN,
          'keyword' : keyword,
          "id"      : id,
        },
        success: function(data){
          $("#"+suggesstion).show();
          $("#"+suggesstion).html(data['thml']);
          $(searchinput).css("background","#FFF");
        }
      });
    });

  </script>

<!--Transportation-->
<script>
    $(".display_on_website").click(function (){
        var value = $(this).attr('counter');
            console.log('The Value is '+value)
        if ($("#display_on_website"+value+"").is(':checked')){
            $("#display_markup"+value+"").css('display','flex');
        }else{
           $("#display_markup"+value+"").css('display','none');
        }
    })
    
    function displayOnWebMore(id){
        console.log('The is call now '+id)
        if ($("#display_on_website"+id+"").is(':checked')){
            $("#display_markup"+id+"").css('display','flex');
        }else{
            $("#display_markup"+id+"").css('display','none');
        }
    }
    
    $('#vehicle_Data').change(function(){
        var vehicle_Data = $(this).find('option:selected').attr('attr');
        $('#vehicle_currency_symbol').val(vehicle_Data)
    });
    
    $('.CC_id_store_1').change(function(){
        var value                   = $(this).find('option:selected').attr('value');
        var attr_id                 = $(this).find('option:selected').attr('attr_id');
        var attr_conversion_type    = $(this).find('option:selected').attr('attr_conversion_type');
        
        $('#conversion_type_Id').val(attr_id);
        $('#select_exchange_type').val(attr_conversion_type);
        
        var value_c         = $("#currency_conversion1_modal").val();
        const usingSplit    = value_c.split(' ');
        var value_1         = usingSplit['0'];
        var value_2         = usingSplit['2'];
        $(".currency_value1_modal").html(value_1);
        $(".currency_value_exchange_1_modal").html(value_2);
        exchange_currency_funs(value_1,value_2);
        
    });
    
    $('#exchange_Rate').keyup(function(){
        var exchange_Rate   = $(this).val();
        var vehicle_Fare    = $('#vehicle_Fare').val();
        var Total           = parseFloat(vehicle_Fare)/parseFloat(exchange_Rate);
        Total               = Total.toFixed(2);
        $('#vehicle_total_Fare').val(Total);
    });
    
    $('#transfer_supplier_modal').change(function (){
        var ids = $('#transfer_supplier_modal').find('option:selected').attr('attr-id');
        $('#transfer_supplier_Id').val(ids);
    });
    
</script>

<script>
   
    $(document).ready(function(){
        addGoogleApi('pickup_City');
        addGoogleApi('dropof_City');
        addGoogleApi('return_pickup_City');
        addGoogleApi('return_dropof_City');
        addGoogleApi('transportation_pick_up_location');
        addGoogleApi('transportation_drop_off_location');
        addGoogleApi('return_transportation_pick_up_location');
        addGoogleApi('return_transportation_drop_off_location');
    });
    
    var subLocationCount = 1;
    function addMoreSubDestination(){
        var row = `<div class="row" id="row${subLocationCount}">
                        <h4>More Destination Details</h4>
                        <div class="col-md-5" style="padding: 15px;">
                            <label for="">Select More Pickup City</label>
                            <input type="text" id="sublocPick${subLocationCount}" name="subLocationPic[]" class="form-control" required>
                        </div>
                        
                        <div class="col-md-5" style="padding: 15px;">
                            <label for="">Select More Dropof City</label>
                            <input type="text" id="sublocDrop${subLocationCount}" name="subLocationdrop[]" class="form-control" required>
                        </div>
                        
                        <div class="col-md-2" style="padding: 15px; margin-top: 1.4rem;">
                            <button class="btn btn-danger" type="button" onclick="removeSublocation(${subLocationCount})">Delete</button>
                        </div>
                        
                    </div>`;
                    
        $('#subDestinations').append(row);
        addGoogleApi('sublocPick'+subLocationCount+'');
        addGoogleApi('sublocDrop'+subLocationCount+'');
        subLocationCount++;
    }
    
    function removeSublocation(id){
        $("#row"+id+"").remove();
    }
    
    function transferTypefunction(){
        var transfer_type = $('#transfer_type').val();
        
        console.log('transfer_type : '+transfer_type);
        
        if(transfer_type == 'Return'){
            $('#returnDestinations').css('display','');
            $('#subDestinations_button').css('display','none');
            $('#return_pickup_City').val('');
            $('#return_dropof_City').val('');
            $('#subDestinations').empty();
        }
        else if(transfer_type == 'All_Round'){
           $('#returnDestinations').css('display','none');
           $('#subDestinations_button').css('display','');
           $('#return_pickup_City').val('');
           $('#return_dropof_City').val('');
           $('#subDestinations').empty();
        }
        else{
            $('#returnDestinations').css('display','none');
            $('#subDestinations_button').css('display','none');
            $('#return_dropof_City').val('');
            $('#return_pickup_City').val('');
            $('#subDestinations').empty();
        }
    }
    
    $('#available_from').change(function () {
        
        var h = "hours";
        var m = "minutes";
        
        var available_from  = $(this).val();
        var available_to    = $('#available_to').val();
        
        var second=1000, minute=second*60, hour=minute*60, day=hour*24, week=day*7;
        
        var date1               = new Date(available_from);
        var date2               = new Date(available_to);
        var timediff            = date2 - date1;
        var minutes_Total       = Math.floor(timediff / minute);
        var total_hours         = Math.floor(timediff / hour)
        var total_hours_minutes = parseFloat(total_hours) * 60;
        
        var minutes = parseFloat(minutes_Total) - parseFloat(total_hours_minutes);
        
        // $('#transportation_Time_Div').css('display','');
        $('#total_duration').val(total_hours+h+ ' : ' +minutes+m);
        
    });
    
    $('#available_to').change(function () {
        
        var h = "hours";
        var m = "minutes";
        
        var available_to    = $(this).val();
        var available_from  = $('#available_from').val();
        
        var second=1000, minute=second*60, hour=minute*60, day=hour*24, week=day*7;
        
        var date1               = new Date(available_from);
        var date2               = new Date(available_to);
        var timediff            = date2 - date1;
        var minutes_Total       = Math.floor(timediff / minute);
        var total_hours         = Math.floor(timediff / hour)
        var total_hours_minutes = parseFloat(total_hours) * 60;
        
        var minutes = parseFloat(minutes_Total) - parseFloat(total_hours_minutes);
        
        // $('#transportation_Time_Div').css('display','');
        $('#total_duration').val(total_hours+h+ ' : ' +minutes+m);
        
    });
    
    $('#return_available_from').change(function () {
        
        var h = "hours";
        var m = "minutes";
        
        var return_available_from  = $(this).val();
        var return_available_to    = $('#return_available_to').val();
        
        var second=1000, minute=second*60, hour=minute*60, day=hour*24, week=day*7;
        
        var date1               = new Date(return_available_from);
        var date2               = new Date(return_available_to);
        var timediff            = date2 - date1;
        var minutes_Total       = Math.floor(timediff / minute);
        var total_hours         = Math.floor(timediff / hour)
        var total_hours_minutes = parseFloat(total_hours) * 60;
        
        var minutes = parseFloat(minutes_Total) - parseFloat(total_hours_minutes);
        
        // $('#transportation_Time_Div').css('display','');
        $('#return_total_duration').val(total_hours+h+ ' : ' +minutes+m);
        
    });
    
    $('#return_available_to').change(function () {
        
        var h = "hours";
        var m = "minutes";
        
        var return_available_to    = $(this).val();
        var return_available_from  = $('#return_available_from').val();
        
        var second=1000, minute=second*60, hour=minute*60, day=hour*24, week=day*7;
        
        var date1               = new Date(return_available_from);
        var date2               = new Date(return_available_to);
        var timediff            = date2 - date1;
        var minutes_Total       = Math.floor(timediff / minute);
        var total_hours         = Math.floor(timediff / hour)
        var total_hours_minutes = parseFloat(total_hours) * 60;
        
        var minutes = parseFloat(minutes_Total) - parseFloat(total_hours_minutes);
        
        // $('#transportation_Time_Div').css('display','');
        $('#return_total_duration').val(total_hours+h+ ' : ' +minutes+m);
        
    });
    
</script>

<script>

    var divId = 1;
    function add_more_vehicle(){
    
        var data = `<div id="vehicle_div_${divId}" class="row">
                        
                        <div class="col-md-3" style="padding: 15px;">
                            <label for="">Transfer Suppliers</label>
                            <select name="transfer_supplier[]" class="form-control" id="transfer_supplier${divId}" onchange="transfer_supplier_function(${divId})">
                                <option value="">Select Suppliers</option>
                                @foreach($tranfer_supplier as $tranfer_supplierS)
                                    <option attr-id="{{ $tranfer_supplierS->id }}" value="{{ $tranfer_supplierS->room_supplier_name }}">{{ $tranfer_supplierS->room_supplier_name }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" value="" id="vehicle_currency_symbol" name="currency_symbol[]">
                            <input type="hidden" id="transfer_supplier_Id${divId}" name="transfer_supplier_Id[]">
                        </div>
                        
                        <div class="col-md-3" style="padding: 15px;">
                            <label for="">Select Category Vehicle</label>
                            <select name="vehicle_Name[]" class="form-control" id="vehicle_Data${divId}" onchange="vehicle_DataF(${divId})">
                                <option value="">Select Vehicle</option>
                                @foreach($tranfer_vehicle as $value)
                                    <option value='{{ json_encode($value) }}' attr="{{ $value->currency_symbol }}">{{ $value->vehicle_Name }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" value="" id="vehicle_currency_symbol${divId}" name="currency_symbol[]">
                        </div>
                        
                        <div class="col-md-2" style="padding: 15px;">
                            <label for="">Fare</label>
                            <div class="input-group">
                                <span class="input-group-btn input-group-append">
                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value1_modal"></a>
                                </span>
                                <input type="text" id="vehicle_Fare${divId}" name="vehicle_Fare[]" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="col-md-2" style="padding: 15px;">
                            <label for="">Exchage Rate</label>
                            <div class="input-group">
                                <input type="text" id="exchange_Rate${divId}" name="exchange_Rate[]" class="form-control" required onkeyup="exchange_Rate_function(${divId})">
                                <span class="input-group-btn input-group-append">
                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value1_modal"></a>
                                </span>
                            </div>
                        </div>
                        
                        <div class="col-md-2" style="padding: 15px;">
                            <label for="">Total Fare</label>
                            <div class="input-group">
                                <input type="text" id="vehicle_total_Fare${divId}" name="vehicle_total_Fare[]" class="form-control" required>
                                <span class="input-group-btn input-group-append">
                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1_modal"></a>
                                </span>
                            </div>
                        </div>
                       
                       
                       <div class="col-md-12" style="padding: 15px;">
                            <input class="form-check-input display_on_website" counter="${divId}" type="checkbox" name="display_on_website[]" value="true" id="display_on_website${divId}">
                            <label class="form-check-label" for="display_on_website${divId}">
                                Display on Website
                            </label>
                            <div class="row" id="display_markup${divId}" style="display:none;">
                                <div class="col-md-2" style="padding: 15px;">
                                    <label for="">Markup Type</label>
                                    <select name="fare_markup_type[]" onchange="fare_markup_type_change(${divId})" id="fare_markup_type${divId}" class="form-control">
                                        <option value="">Markup Type</option>
                                        <option value="%">Percentage</option>
                                        <option value="<?php echo $currency; ?>">Fixed Amount</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-2" style="padding: 15px;">
                                    <label for="">Fare Markup</label>
                                    <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                        <input type="text" class="form-control" onkeyup="fare_markup_change(${divId})" id="fare_markup${divId}" name="fare_markup[]">
                                        <span class="input-group-btn input-group-append">
                                            <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="fare_mrk${divId}">%</div></button>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="col-md-2" style="padding: 15px;">
                                    <label for="">Total</label>
                                    <div class="input-group">
                                        <input type="text" id="total_fare_markup${divId}" name="total_fare_markup[]" class="form-control">
                                        <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1_modal"></a></span>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="mt-2">
                            <a href="javascript:;" onclick="deleteRowVehicle(${divId})" id="${divId}" class="btn btn-info" style="float: right;">Delete </a>
                        </div>
                    </div>`;
        
        $("#append_Vehicle").append(data);
        
        $(".display_on_website").click(function (){
            var value = $(this).attr('counter');
            if ($("#display_on_website"+value+"").is(':checked')){
                $("#display_markup"+value+"").css('display','flex');
            }else{
               $("#display_markup"+value+"").css('display','none');
            }
           
        })
    
        var value_c         = $("#currency_conversion1_modal").val();
        const usingSplit    = value_c.split(' ');
        var value_1         = usingSplit['0'];
        var value_2         = usingSplit['2'];
        $(".currency_value1_modal").html(value_1);
        $(".currency_value_exchange_1_modal").html(value_2);
        exchange_currency_funs(value_1,value_2);
        
        divId=divId + 1;
    }
    
    var divId1 = 1;
    function add_ziyarat(){
        var data = `<div id="ziyarat_div_${divId1}" class="row">
                        
                        <div class="col-md-6" style="padding: 15px;">
                            <label>Select Ziyarat City</label>
                            <input type="text" id="ziyarat_City_${divId1}" name="ziyarat_City[]" class="form-control" required>
                        </div>
                        
                        <div class="col-md-6" style="padding: 15px;">
                            <div class="mt-2">
                                <a href="javascript:;" onclick="deleteZiyarat(${divId1})" id="${divId1}" class="btn btn-info" style="float: right;">Delete </a>
                            </div>
                        </div>
                        
                    </div>`;
        
        $("#append_Ziyarat").append(data);
        
        addGoogleApi('ziyarat_City_'+divId1+'');
        
        divId1 = divId1 + 1;
    }
    
    function transfer_supplier_function(id){
        var ids = $('#transfer_supplier'+id+'').find('option:selected').attr('attr-id');
        $('#transfer_supplier_Id'+id+'').val(ids);
    }
    
    function exchange_Rate_function(id){
        var exchange_Rate   = $('#exchange_Rate'+id+'').val();
        var vehicle_Fare    = $('#vehicle_Fare'+id+'').val();
        var Total           = parseFloat(vehicle_Fare)/parseFloat(exchange_Rate);
        Total               = Total.toFixed(2);
        $('#vehicle_total_Fare'+id+'').val(Total);
    }
    
    function deleteRowVehicle(id){
        $('#vehicle_div_'+id+'').remove();
    }
    
    function deleteZiyarat(id){
        $('#ziyarat_div_'+id+'').remove();
    }
    
    function vehicle_DataF(id){
        var vehicle_Data = $('#vehicle_Data'+id+'').find('option:selected').attr('attr');
        $('#vehicle_currency_symbol'+id+'').val(vehicle_Data)
    }
    
</script>

<script>
    $("#fare_markup").keyup(function () {
        var id = $('#fare_markup_type').find('option:selected').attr('value');
        
        if(id == '%')
        {
            var fare_markup     =  $('#fare_markup').val();
            var vehicle_Fare    =  $('#vehicle_total_Fare').val();
            var total1          = (vehicle_Fare * fare_markup/100) + parseFloat(vehicle_Fare);
            var total           = total1.toFixed(2);
            $('#total_fare_markup').val(total);
        }
        else
        {
            var fare_markup     =  $('#fare_markup').val();
            var vehicle_Fare    =  $('#vehicle_total_Fare').val();
            var total1          =  parseFloat(vehicle_Fare) +  parseFloat(fare_markup);
            var total           = total1.toFixed(2);
            $('#total_fare_markup').val(total);
        }
    });
    
    function fare_markup_type_change(id){
        var ids = $('#fare_markup_type'+id+'').find('option:selected').attr('value');
    }
    
    function fare_markup_change(id){
        var ids = $('#fare_markup_type'+id+'').find('option:selected').attr('value');
        if(ids == '%')
        {
            var fare_markup     =  $('#fare_markup'+id+'').val();
            var vehicle_Fare    =  $('#vehicle_total_Fare'+id+'').val();
            var total1          = (vehicle_Fare * fare_markup/100) + parseFloat(vehicle_Fare);
            var total           = total1.toFixed(2);
            $('#total_fare_markup'+id+'').val(total);
        }
        else
        {
            var fare_markup     =  $('#fare_markup'+id+'').val();
            var vehicle_Fare    =  $('#vehicle_total_Fare'+id+'').val();
            var total1          =  parseFloat(vehicle_Fare) +  parseFloat(fare_markup);
            var total           = total1.toFixed(2);
            $('#total_fare_markup'+id+'').val(total);
        }
    }
</script>

<script>
    
    // Destination
    function transfer_modal_button_function(){
        $('#transfer_modal_div').css('display','');
        $('#submit_destination_button').css('display','');
        
        $('#currency_conversion1_modal').val('-1').change();
        $('#transfer_type').val('-1').change();
        $('#available_from').val('');
        $('#available_to').val('');
        $('#pickup_City').val('');
        $('#dropof_City').val('');
        
        $('#append_Ziyarat').empty();
        
        $('#transfer_supplier_modal').val('-1').change();
        $('#vehicle_Data').val('-1').change();
        $('#vehicle_Fare').val('');
        $('#exchange_Rate').val('');
        $('#vehicle_total_Fare').val('');
        
        $('#append_Vehicle').empty();
        
        $('#fare_markup_type').val('-1').change();
        $('#fare_markup').val('');
        $('#total_fare_markup').val('');
    }
    
    function submit_destination(){
        event.preventDefault();
        var formData = $('#transferDestinationForm').serialize();
        console.log(formData);
        
        $.ajax({
            url     : "{{ URL::to('add_new_destinationAjax') }}",
            type    : "POST",
            data    : formData,
            success : function(response) {
                if(response){
                    var data = response.message;
                    if(data == 'True'){
                        transfer_supplier_function_edit();
                        transfer_modal_button_function();
                    }else{
                        alert('Add Complete Details!');
                    }
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                console.log(xhr.responseText);
            }
        });
    }
    // End Destination

    // Transportation
    $('#transportation_drop_of_date').change(function () {
        
        var h = "hours";
        var m = "minutes";
        
        var transportation_drop_of_date = $(this).val();
        var transportation_pick_up_date = $('#transportation_pick_up_date').val();
        
        var second=1000, minute=second*60, hour=minute*60, day=hour*24, week=day*7;
        
        var date1 = new Date(transportation_pick_up_date);
        var date2 = new Date(transportation_drop_of_date);
        var timediff = date2 - date1;
        
        var minutes_Total = Math.floor(timediff / minute);
        
        var total_hours   = Math.floor(timediff / hour)
        var total_hours_minutes = parseFloat(total_hours) * 60;
        
        var minutes = parseFloat(minutes_Total) - parseFloat(total_hours_minutes);
        
        $('#transportation_Time_Div').css('display','');
        $('#transportation_total_Time').val(total_hours+h+ ' : ' +minutes+m);
        
    });
    
    $('#return_transportation_drop_of_date').change(function () {
        
        var h = "hours";
        var m = "minutes";
        
        var return_transportation_drop_of_date = $(this).val();
        var return_transportation_pick_up_date = $('#return_transportation_pick_up_date').val();
        
        var second=1000, minute=second*60, hour=minute*60, day=hour*24, week=day*7;
        
        var return_date1 = new Date(return_transportation_pick_up_date);
        var return_date2 = new Date(return_transportation_drop_of_date);
        var return_timediff = return_date2 - return_date1;
        
        var return_minutes_Total = Math.floor(return_timediff / minute);
        
        var return_total_hours   = Math.floor(return_timediff / hour)
        var return_total_hours_minutes = parseFloat(return_total_hours) * 60;
        
        var return_minutes = parseFloat(return_minutes_Total) - parseFloat(return_total_hours_minutes);
        
        $('#return_transportation_Time_Div').css('display','');
        $('#return_transportation_total_Time').val(return_total_hours+h+ ' : ' +return_minutes+m);
    
    });
    
    var MTD_id = 1;
    $("#more_transportationI").click(function(){
        var data = `<div class="row" id="click_delete_${MTD_id}">
                        <div class="col-xl-3" style="padding: 10px;">
                            <label for="">Pick-up Location</label>
                            <input type="text" id="more_transportation_pick_up_location_${MTD_id}" name="more_transportation_pick_up_location[]" class="form-control">
                        </div>
                        <div class="col-xl-3" style="padding: 10px;">
                            <label for="">Drop-off Location</label>
                            <input type="text" id="more_transportation_drop_off_location_${MTD_id}" name="more_transportation_drop_off_location[]" class="form-control">
                        </div>
                        <div class="col-xl-3" style="padding: 10px;">
                            <label for="">Pick-up Date & Time</label>
                            <input type="datetime-local" id="more_transportation_pick_up_date_${MTD_id}" name="more_transportation_pick_up_date[]" class="form-control">
                        </div>
                        
                        <div class="col-xl-3" style="padding: 10px;">
                            <label for="">Drop-of Date & Time</label>
                            <input type="datetime-local" id="more_transportation_drop_of_date_${MTD_id}" name="more_transportation_drop_of_date[]" class="form-control">
                        </div>
                        
                        <div class="col-xl-3" style="display:none;padding: 10px;" id="more_transportation_Time_Div_${MTD_id}">
                            <label for="">Estimate Time</label>
                            <input type="text" id="more_transportation_total_Time_${MTD_id}" name="more_transportation_total_Time[]" class="form-control" readonly style="padding: 10px;">
                        </div>
                        
                        <div class="mt-2">
                            <button style="float: right;" type="button" class="btn btn-info deletButton" onclick="deleteRowTransI(${MTD_id})"  id="${MTD_id}">Delete</button>
                        </div>
                    </div>`;
        $("#append_transportation").append(data);
        
        addGoogleApi('more_transportation_pick_up_location_'+MTD_id+'');
        addGoogleApi('more_transportation_drop_off_location_'+MTD_id+'');
        
        $('#more_transportation_drop_of_date_'+MTD_id+'').change(function () {
        
            var h = "hours";
            var m = "minutes";
            
            var transportation_drop_of_date = $('#more_transportation_drop_of_date_'+MTD_id+'').val();
            var transportation_pick_up_date = $('#more_transportation_pick_up_date_'+MTD_id+'').val();
            
            var second=1000, minute=second*60, hour=minute*60, day=hour*24, week=day*7;
            
            var date1 = new Date(transportation_pick_up_date);
            var date2 = new Date(transportation_drop_of_date);
            var timediff = date2 - date1;
            
            var minutes_Total = Math.floor(timediff / minute);
            
            var total_hours   = Math.floor(timediff / hour)
            var total_hours_minutes = parseFloat(total_hours) * 60;
            
            var minutes = parseFloat(minutes_Total) - parseFloat(total_hours_minutes);
            
            $('#more_transportation_Time_Div_'+MTD_id+'').css('display','');
            $('#more_transportation_total_Time_'+MTD_id+'').val(total_hours+h+ ' : ' +minutes+m);
            
        });
    
        MTD_id++;
    });
    
    function deleteRowTransI(id){
        $('#click_delete_'+id+'').remove();
    }
    
    function transfer_supplier_function_edit(){
        var ids = $('#transfer_supplier').find('option:selected').attr('attr-id');
        $('#transfer_supplier_selected_id').val(ids);
        $('#transfer_supplier_list_body').empty();
        
        $('#transfer_modal_button').css('display','none');
        $('#transfer_supplier_list_body').css('display','none');
        $('#select_transportation').css('display','none');
        $('#transfer_supplier_list_div').css('display','none');
        // One-Way/Return
        $('#transportation_pick_up_location').val('');
        $('#transportation_drop_off_location').val('');
        $('#transportation_pick_up_date').val('');
        $('#transportation_drop_of_date').val('');
        $('#transportation_total_Time').val('');
        $('#slect_trip').empty();
        $('#transportation_vehicle_typeI').val('').change();
        $('#transportation_no_of_vehicle').val('');
        $('#transportation_price_per_vehicle').val('');
        $('#transportation_vehicle_total_price').val('');
        $('#transfer_exchange_rate_destination').val('');
        $('#without_markup_price_converted_destination').val('');
        $('#transportation_vehicle_total_price_converted').val('');
        // $('#vehicle_markup_type').val('').change();
        $('#vehicle_per_markup_value').val('');
        $('#vehicle_total_price_with_markup').val('');
        $('#vehicle_markup_value').val('');
        $('#vehicle_markup_value_converted').val('');
        $('#vehicle_total_price_with_markup_converted').val('');
        $('#return_transportation_pick_up_location').val('');
        $('#return_transportation_drop_off_location').val('');
        $('#return_transportation_pick_up_date').val('');
        $('#return_transportation_drop_of_date').val('');
        $('#return_transportation_total_Time').val('');
        
        $('.all_one_way_empty_class').val('');
        $('.all_one_way_empty_class2').empty();
        // All Round
        $('#transportation_main_divI').empty();
        
        if(ids != '-1'){
            $('#transfer_supplier_list_body').css('display','');
            $('#transfer_modal_button').css('display','');
            var i = 1;
            $.ajax({
                url     : '{{URL::to('get_all_transfer_destinations')}}',
                type    : "get",
                data    : {
                    "_token"    : "{{ csrf_token() }}",
                },
                success:function(response){
                    var destination_details = response.data;
                    $.each(destination_details, function(key, value) {
                        var vehicle_detailsE    = value.vehicle_details;
                        var transfer_type       = value.transfer_type;
                        
                        if(vehicle_detailsE != null && vehicle_detailsE != ''){
                            var vehicle_details = JSON.parse(vehicle_detailsE);
                            $.each(vehicle_details, function(key, value1) {
                                var vehicle_id = value1.vehicle_id;
                                
                                var transfer_supplier_Id = value1.transfer_supplier_Id;
                                if(ids == transfer_supplier_Id){
                                    $('#transfer_supplier_list_div').css('display','');
                                    var TS_data =  `<tr>
                                                        <td>${value.id}<input type="hidden" value="${value.id}" id="transfer_id_td_${i}"><input type="hidden" value="${value1.vehicle_id}" id="transfer_vehicle_id_td_${i}"></td>
                                                        <td class="d-none">${value.transfer_company}<input type="hidden" value="${value.transfer_company}" id="transfer_company_td_${i}"></td>
                                                        <td>${value.pickup_City}<input type="hidden" value="${value.pickup_City}" id="pickup_City_td_${i}"></td>
                                                        <td>${value.dropof_City}<input type="hidden" id="dropof_City_td_${i}" value="${value.dropof_City}"></td>
                                                        <td>${value.available_from}<input type="hidden"id="available_from_td_${i}" value="${value.available_from}"></td>
                                                        <td>${value.available_to}<input type="hidden" id="available_to_td_${i}" value="${value.available_to}"></td>
                                                        <td>${value.transfer_type}<input type="hidden" id="transfer_Type_td_${i}" value="${value.transfer_type}"></td>
                                                        <td>${value1.vehicle_Fare}<input type="hidden" id="total_fare_markup_td_${i}" value="${value.vehicle_Fare}"></td>
                                                        <td>
                                                            <input type="hidden" id="ocupancy_btn_switch_${i}" value="0" class="ocupancy_btn_switch_class_all">
                                                            <a type="button" class="btn btn-primary ocupancy_btn_class_all" id="occupy_btn_${i}" onclick="occupy_function(${i})">Occupy</a>
                                                            <a type="button" class="btn btn-danger" id="delete_occupy_btn_${i}" onclick="delete_occupy_btn_function(${i})" style="display:none">Delete</a>
                                                        </td>
                                                    </tr>`;
                                    $('#transfer_supplier_list_body').append(TS_data);
                                    i = i + 1;
                                }
                            });
                        }
                    });
                },
            });
        }
    }
    
    var TD_id1  = 1;
    var MTD_id1 = 1;
    
    function occupy_function(id){
        
        $('#transfer_modal_button').css('display','none');
        
        var transfer_supplier_id    = $('#transfer_supplier').find('option:selected').attr('attr-id');
        var transfer_id_td          = $('#transfer_id_td_'+id+'').val();
        var transfer_Type_td        = $('#transfer_Type_td_'+id+'').val();
        var transfer_vehicle_id_td  = $('#transfer_vehicle_id_td_'+id+'').val();
        var ocupancy_btn_switch     = $('#ocupancy_btn_switch_'+id+'').val();
        $.ajax({
            url     : '{{URL::to('get_all_transfer_destinations')}}',
            type    : "get",
            data    : {
                "_token"    : "{{ csrf_token() }}",
            },
            success:function(response){
                if(response){
                    var destination_details = response.data;
                    $.each(destination_details, function(key, value) {
                        
                        var id_DD                   = value.id
                        var transfer_type           = value.transfer_type;
                        var select_exchange_type    = value.select_exchange_type;
                        $('#vehicle_select_exchange_type_ID').val(select_exchange_type);
                        
                        if(id_DD == transfer_id_td){
                            
                            if(transfer_Type_td == "One-Way"){
                                $('#occupy_btn_'+id+'').css('display','none');
                                $('#delete_occupy_btn_'+id+'').css('display','');
                                
                                $('#vehicle_markup_type').val('');
                                $('#vehicle_markup_value').val('');
                                $('#vehicle_total_price_with_markup').val('');
                                $('#vehicle_markup_value_converted').val('');
                                $('#vehicle_total_price_with_markup_converted').val('');
                                $('#vehicle_per_markup_value').val('');
                                $('#markup_price_per_vehicle_converted').val('');
                                
                                $('#currency_SAR').val('');
                                $('#currency_GBP').val('');
                                
                                $('#without_markup_price_converted_destination').val('');
                                $('#transportation_vehicle_total_price_converted').val('');
                                $('#transportation_price_per_person_converted').val('');
                                
                                $('#transportation_markup').val('');
                                $('#transportation_markup_total').val('');
                                
                                $('#transportation_price_per_person_select').val(0);
                                
                                $('#transportation_cost').css('display','');
                                
                                $('.ocupancy_btn_class_all').css('background-color','rebeccapurple');
                                $('.ocupancy_btn_switch_class_all').val(0);
                                
                                $('#transportation_pick_up_date').val('');
                                $('#transportation_drop_of_date').val('');
                                $('#transportation_total_Time').val('');
                                $('#transportation_Time_Div').css('display','');
                                
                                $('#select_transportation').css('display','');
                                $('#select_return').css('display','none');
                                
                                $("#append_transportation").empty();
                                $('#add_more_destination').css('display','none');
                                
                                $("#transportation_main_divI").empty();
                                $('#transportation_main_divI').css('display','none');
                                
                                $('#return_transportation_pick_up_location').val('');
                                $('#return_transportation_drop_off_location').val('');
                                $('#return_transportation_Time_Div').css('display','none');
                                $('#return_transportation_pick_up_date').val('');
                                $('#return_transportation_drop_of_date').val('');
                                $('#return_transportation_total_Time').val('');
                                
                                $('#transportation_no_of_vehicle').val('');
                                $('#transportation_vehicle_total_price').val('');
                                $('#transportation_price_per_person').val('');
                                
                                var pickup_City = value.pickup_City;
                                var dropof_City = value.dropof_City;
                                
                                var destination_id = value.id;
                                $('#destination_id').val(destination_id);
                                
                                $('#transportation_pick_up_location').val(pickup_City);
                                $('#transportation_drop_off_location').val(dropof_City);
                                
                                $('#transportation_pick_up_location_select').val(pickup_City);
                                $('#transportation_drop_off_location_select').val(dropof_City);
                                
                                $('#slect_trip').empty();
                                slect_trip_data =  `<option value="One-Way" Selected>One-Way</option>`;
                                $('#slect_trip').append(slect_trip_data);
                                
                                var vehicle_detailsE = value.vehicle_details;
                                if(vehicle_detailsE != null && vehicle_detailsE != ''){
                                    var vehicle_details = JSON.parse(vehicle_detailsE);
                                    $.each(vehicle_details, function(key, value1) {
                                        var transfer_supplier_Id    = value1.transfer_supplier_Id;
                                        var total_fare_markup       = value1.total_fare_markup;
                                        var vehicle_Name            = value1.vehicle_Name;
                                        var vehicle_Fare            = value1.vehicle_Fare;
                                        var vehicle_total_Fare      = value1.vehicle_total_Fare;
                                        var exchange_Rate           = value1.exchange_Rate;
                                        var vehicle_id              = value1.vehicle_id;
                                        
                                        if(transfer_supplier_id == transfer_supplier_Id){
                                            if(transfer_vehicle_id_td == vehicle_id){
                                            
                                                $('#transportation_vehicle_typeI').empty();
                                                var transportation_vehicle_type_Data = `<option value="${vehicle_Name}" Selected>${vehicle_Name}</option>`;
                                                $('#transportation_vehicle_typeI').append(transportation_vehicle_type_Data);
                                                
                                                $('#transportation_price_per_vehicle').val(vehicle_Fare);
                                                $('#without_markup_price_converted_destination').val(vehicle_total_Fare);
                                                $('#transfer_exchange_rate_destination').val(exchange_Rate);
                                                
                                                $('#vehicle_exchange_Rate_ID').val(exchange_Rate);
                                                
                                                // $('#transportation_price_per_vehicle').val(total_fare_markup);
                                                // $('#transportation_price_per_person_select').val(total_fare_markup);
                                            }
                                        }
                                        
                                    });
                                }
                                
                                var currency_conversion = value.currency_conversion;
                                console.log(currency_conversion);
                                
                                var value_c         = value.currency_conversion;
                                const usingSplit    = value_c.split(' ');
                                var value_1         = usingSplit['0'];
                                var value_2         = usingSplit['2'];
                                $(".currency_value1_T").html(value_1);
                                $(".currency_value_exchange_1_T").html(value_2);
                                
                                $("#currency_SAR").val(value_1);
                                $("#currency_GBP").val(value_2);
                                
                                exchange_currency_funs(value_1,value_2);
                            }
                            else if(transfer_Type_td == "Return"){
                                
                                $('#occupy_btn_'+id+'').css('display','none');
                                $('#delete_occupy_btn_'+id+'').css('display','');
                                
                                $('#vehicle_markup_type').val('');
                                $('#vehicle_markup_value').val('');
                                $('#vehicle_total_price_with_markup').val('');
                                $('#vehicle_markup_value_converted').val('');
                                $('#vehicle_total_price_with_markup_converted').val('');
                                $('#vehicle_per_markup_value').val('');
                                $('#markup_price_per_vehicle_converted').val('');
                                
                                $('#currency_SAR').val('');
                                $('#currency_GBP').val('');
                                
                                $('#without_markup_price_converted_destination').val('');
                                $('#transportation_vehicle_total_price_converted').val('');
                                $('#transportation_price_per_person_converted').val('');
                                
                                $('#transportation_markup').val('');
                                $('#transportation_markup_total').val('');
                                
                                $('#transportation_price_per_person_select').val(0);
                                
                                $('#transportation_cost').css('display','');
                                
                                $('#return_transportation_Time_Div').css('display','');
                                $('#return_transportation_pick_up_date').val('');
                                $('#return_transportation_drop_of_date').val('');
                                $('#return_transportation_total_Time').val('');
                                
                                $('#transportation_no_of_vehicle').val('');
                                $('#transportation_vehicle_total_price').val('');
                                $('#transportation_price_per_person').val('');
                                
                                $('#transportation_pick_up_date').val('');
                                $('#transportation_drop_of_date').val('');
                                $('#transportation_total_Time').val('');
                                $('#transportation_Time_Div').css('display','none');
                                
                                $('#select_transportation').css('display','');
                                $('#select_return').css('display','');
                                
                                $("#append_transportation").empty();
                                $('#add_more_destination').css('display','none');
                                
                                $("#transportation_main_divI").empty();
                                $('#transportation_main_divI').css('display','none');
                                
                                $('.ocupancy_btn_class_all').css('background-color','rebeccapurple');
                                $('.ocupancy_btn_switch_class_all').val(0)
                                
                                var pickup_City     = value.pickup_City;
                                var dropof_City     = value.dropof_City;
                                
                                var destination_id  = value.id;
                                $('#destination_id').val(destination_id);
                                
                                $('#transportation_pick_up_location').val(pickup_City);
                                $('#transportation_drop_off_location').val(dropof_City);
                                
                                $('#transportation_pick_up_location_select').val(pickup_City);
                                $('#transportation_drop_off_location_select').val(dropof_City);
                                
                                $('#slect_trip').empty();
                                slect_trip_data =  `<option value="Return" Selected>Return</option>`;
                                $('#slect_trip').append(slect_trip_data);
                                
                                var vehicle_detailsE = value.vehicle_details;
                                if(vehicle_detailsE != null && vehicle_detailsE != ''){
                                    var vehicle_details = JSON.parse(vehicle_detailsE);
                                    $.each(vehicle_details, function(key, value1) {
                                        var transfer_supplier_Id    = value1.transfer_supplier_Id;
                                        var total_fare_markup       = value1.total_fare_markup;
                                        var vehicle_Name            = value1.vehicle_Name;
                                        var vehicle_Fare            = value1.vehicle_Fare;
                                        var vehicle_total_Fare      = value1.vehicle_total_Fare;
                                        var exchange_Rate           = value1.exchange_Rate;
                                        var vehicle_id              = value1.vehicle_id;
                                        
                                        if(transfer_supplier_id == transfer_supplier_Id){
                                            if(transfer_vehicle_id_td == vehicle_id){
                                                $('#transportation_vehicle_typeI').empty();
                                                var transportation_vehicle_type_Data = `<option value="${vehicle_Name}" Selected>${vehicle_Name}</option>`;
                                                $('#transportation_vehicle_typeI').append(transportation_vehicle_type_Data);
                                                
                                                $('#transportation_price_per_vehicle').val(vehicle_Fare);
                                                $('#without_markup_price_converted_destination').val(vehicle_total_Fare);
                                                $('#transfer_exchange_rate_destination').val(exchange_Rate);
                                                
                                                $('#vehicle_exchange_Rate_ID').val(exchange_Rate);
                                                
                                                // $('#transportation_price_per_vehicle').val(total_fare_markup);
                                                // $('#transportation_price_per_person_select').val(total_fare_markup);
                                            }
                                        }
                                        
                                    });
                                }
                                
                                var return_pickup_City  = value.return_pickup_City;
                                var return_dropof_City  = value.return_dropof_City;
                                
                                $('#return_transportation_pick_up_location').val(return_pickup_City);
                                $('#return_transportation_drop_off_location').val(return_dropof_City);
                                
                                var currency_conversion = value.currency_conversion;
                                console.log(currency_conversion);
                                var value_c         = value.currency_conversion;
                                const usingSplit    = value_c.split(' ');
                                var value_1         = usingSplit['0'];
                                var value_2         = usingSplit['2'];
                                $(".currency_value1_T").html(value_1);
                                $(".currency_value_exchange_1_T").html(value_2);
                                
                                $("#currency_SAR").val(value_1);
                                $("#currency_GBP").val(value_2);
                                
                                exchange_currency_funs(value_1,value_2);
                            }
                            else{
                                
                                if(ocupancy_btn_switch == 0){
                                    
                                    $('#vehicle_select_exchange_type').val('');
                                    
                                    $('#vehicle_markup_type').val('');
                                    $('#vehicle_markup_value').val('');
                                    $('#vehicle_total_price_with_markup').val('');
                                    $('#vehicle_markup_value_converted').val('');
                                    $('#vehicle_total_price_with_markup_converted').val('');
                                    $('#vehicle_per_markup_value').val('');
                                    $('#markup_price_per_vehicle_converted').val('');
                                    
                                    $('#currency_SAR').val('');
                                    $('#currency_GBP').val('');
                                    
                                    $('#vehicle_select_exchange_type_ID').val('');
                                    $('#vehicle_exchange_Rate_ID').val('');
                                    
                                    $('#without_markup_price_converted_destination').val('');
                                    $('#transportation_vehicle_total_price_converted').val('');
                                    $('#transportation_price_per_person_converted').val('');
                                    
                                    $('#transfer_markup_type_invoice').val('');
                                    $('#transfer_markup_invoice').val('');
                                    $('#transfer_markup_price_invoice').val('');
                                    $('#transfer_exchange_rate_destination').val('');
                                    
                                    $('#destination_id').val('');
                                    
                                    $('#transportation_markup').val('');
                                    $('#transportation_markup_total').val('');
                                    
                                    $('#transportation_price_per_person_select').val(0);
                                    
                                    $('#transportation_cost').css('display','');
                                
                                    $('#occupy_btn_'+id+'').css('background-color','red');
                                    
                                    $('#transportation_main_divI').css('display','');
                                    
                                    $('#select_transportation').css('display','none');
                                    $('#select_return').css('display','none');
                                    
                                    $("#append_transportation").empty();
                                    
                                    $('#return_transportation_pick_up_location').val('');
                                    $('#return_transportation_drop_off_location').val('');
                                    $('#return_transportation_Time_Div').css('display','none');
                                    $('#return_transportation_pick_up_date').val('');
                                    $('#return_transportation_drop_of_date').val('');
                                    $('#return_transportation_total_Time').val('');
                                    
                                    $('#transportation_pick_up_location').val('');
                                    $('#transportation_drop_off_location').val('');
                                    $('#transportation_Time_Div').css('display','none');
                                    $('#transportation_pick_up_date').val('');
                                    $('#transportation_drop_of_date').val('');
                                    $('#transportation_total_Time').val('');
                                    
                                    $('#slect_trip').empty();
                                    slect_trip_data =  `<option></option>
                                                        <option value="One-Way">One-Way</option>
                                                        <option value="Return">Return</option>
                                                        <option value="All_Round">All Round</option>`;
                                    $('#slect_trip').append(slect_trip_data);
                                    $('#transportation_price_per_vehicle').val('');
                                    $('#transportation_no_of_vehicle').val('');
                                    $('#transportation_vehicle_total_price').val('');
                                    $('#transportation_price_per_person').val('');
                                    $('#transportation_vehicle_typeI').empty();
                                    TVTI_data = `<option value=""></option>
                                                <option value="Bus">Bus</option>
                                                <option value="Coach">Coach</option>
                                                <option value="Vain">Vain</option>
                                                <option value="Car">Car</option>`;
                                    $('#transportation_vehicle_typeI').append(TVTI_data);
                                    
                                    var pickup_City = value.pickup_City;
                                    var dropof_City = value.dropof_City;
                                    
                                    $('#transportation_pick_up_location_select').val(pickup_City);
                                    $('#transportation_drop_off_location_select').val(dropof_City);
                                
                                    var allR_data = `<div class="row" id="allRound_Div_${TD_id1}">
                                                        <h3>Transportation Details :</h3>
                                                        
                                                        <input type="hidden" value="All_Round${TD_id1}" name="all_round_Type[]" id="all_round_Type_${TD_id1}">
                                                        
                                                        <input type="hidden" name="destination_id[]" id="destination_id_${TD_id1}">
                                                        
                                                        <input type="hidden" name="vehicle_select_exchange_type[]" id="vehicle_select_exchange_type_ID_${TD_id1}" value="${select_exchange_type}">
                                                    
                                                        <input type="hidden" name="vehicle_exchange_Rate[]" id="vehicle_exchange_Rate_ID_${TD_id1}">
                                                        
                                                        <input type="hidden" name="currency_SAR[]" id="currency_SAR_${TD_id1}">
                                                        
                                                        <input type="hidden" name="currency_GBP[]" id="currency_GBP_${TD_id1}">
                                                        
                                                        <div class="col-xl-3" style="padding: 10px;">
                                                            <label for="">Pick-up Location</label>
                                                            <input type="text" value="${pickup_City}" id="transportation_pick_up_location_${TD_id1}" name="transportation_pick_up_location[]" class="form-control pickup_location">
                                                        </div>
                                                        
                                                        <div class="col-xl-3" style="padding: 10px;">
                                                            <label for="">Drop-off Location</label>
                                                            <input type="text" value="${dropof_City}" id="transportation_drop_off_location_${TD_id1}" name="transportation_drop_off_location[]" class="form-control dropof_location">
                                                        </div>
                                                        
                                                        <div class="col-xl-3" style="padding: 10px;">
                                                            <label for="">Pick-up Date & Time</label>
                                                            <input type="datetime-local" id="transportation_pick_up_date_${TD_id1}" name="transportation_pick_up_date[]" class="form-control" onchange="TPUD_function(${TD_id1})">
                                                        </div>
                                                        
                                                        <div class="col-xl-3" style="padding: 10px;">
                                                            <label for="">Drop-of Date & Time</label>
                                                            <input type="datetime-local" id="transportation_drop_of_date_${TD_id1}" name="transportation_drop_of_date[]" class="form-control" onchange="TDOP_function(${TD_id1})">
                                                        </div>
                                                        
                                                        <div class="col-xl-3" style="display:none" id="transportation_Time_Div_${TD_id1}">
                                                            <label for="">Estimate Time</label>
                                                            <input type="text" id="transportation_total_Time_${TD_id1}" name="transportation_total_Time[]" class="form-control transportation_total_Time1" readonly style="padding: 10px;">
                                                        </div>
                                                        
                                                        <div class="col-xl-3" style="padding: 10px;">
                                                            <label for="">Select Trip Type</label>
                                                            <select name="transportation_trip_type[]" id="slect_trip_${TD_id1}" class="form-control" data-placeholder="Choose ...">
                                                                <option value="All_Round" Selected>All Round</option>
                                                            </select>
                                                        </div>
                                                        
                                                        <div class="col-xl-3" style="padding: 10px;">
                                                            <label for="">Vehicle Type</label>
                                                            <select name="transportation_vehicle_type[]" id="transportation_vehicle_typeI_${TD_id1}" class="form-control"  data-placeholder="Choose ...">
                                                                <option value="">Choose ...</option>
                                                                <option value="Bus">Bus</option>
                                                                <option value="Coach">Coach</option>
                                                                <option value="Vain">Vain</option>
                                                                <option value="Car">Car</option>
                                                            </select>
                                                        </div>
                                                        
                                                        <div class="col-xl-3" style="padding: 10px;">
                                                            <label for="">No Of Vehicle</label>
                                                            <input type="text" id="transportation_no_of_vehicle_${TD_id1}" name="transportation_no_of_vehicle[]" class="form-control" onkeyup="TNOV_function(${TD_id1})">
                                                        </div>
                                                        
                                                        <div class="col-xl-3" style="padding: 10px;">
                                                            <label for="">Price Per Vehicle</label>
                                                            <div class="input-group">
                                                                <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1_T_${TD_id1}"></a></span>
                                                                <input type="text" id="transportation_price_per_vehicle_${TD_id1}" name="transportation_price_per_vehicle[]" class="form-control" onchange="TPPV_function(${TD_id1})" readonly>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-xl-3" style="padding: 10px;">
                                                            <label for="">Total Vehicle Price</label>
                                                            <div class="input-group">
                                                                <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1_T_${TD_id1}"></a></span>
                                                                <input type="text" id="transportation_vehicle_total_price_${TD_id1}" name="transportation_vehicle_total_price[]" class="form-control" readonly>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-xl-3 d-none" style="padding: 10px;">
                                                            <label for="">Price Per Person</label>
                                                            <div class="input-group">
                                                                <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1_T_${TD_id1}"></a></span>
                                                                <input type="text" id="transportation_price_per_person_${TD_id1}" name="transportation_price_per_person[]" class="form-control" readonly>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-xl-3" style="padding: 10px;">
                                                            <label for="">Exchange Rate</label>
                                                            <div class="input-group">
                                                                <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1_T_${TD_id1}"></a></span>
                                                                <input type="text" name="transfer_exchange_rate_destination[]" id="transfer_exchange_rate_destination_${TD_id1}" class="form-control" readonly>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-xl-3" style="padding: 10px;">
                                                            <label for="">Price Per Vehicle</label>
                                                            <div class="input-group">
                                                                <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1_T_${TD_id1}"></a></span>
                                                                <input type="text" name="without_markup_price_converted_destination[]" id="without_markup_price_converted_destination_${TD_id1}" class="form-control" readonly>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-xl-3" style="padding: 10px;">
                                                            <label for="">Total Vehicle Price</label>
                                                            <div class="input-group">
                                                                <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1_T_${TD_id1}"></a></span>
                                                                <input type="text" name="transportation_vehicle_total_price_converted[]" id="transportation_vehicle_total_price_converted_${TD_id1}" class="form-control" readonly>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-xl-3 d-none" style="padding: 10px;">
                                                            <label for="">Price Per Person</label>
                                                            <div class="input-group">
                                                                <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1_T_${TD_id1}"></a></span>
                                                                <input type="text" name="transportation_price_per_person_converted[]" id="transportation_price_per_person_converted_${TD_id1}" class="form-control" readonly>
                                                            </div>
                                                        </div>
                                                        
                                                        <!--Costing--!>
                                                        <div class="col-xl-3" style="padding: 10px;">
                                                            <label for="">Vehicle Markup Type</label>
                                                            <select name="vehicle_markup_type[]" id="vehicle_markup_type_${TD_id1}" class="form-control" onchange="vehicle_markup_AllR(${TD_id1})">
                                                                <option value="">Markup Type</option>
                                                                <option value="%">Percentage</option>
                                                                <option value="<?php echo $currency; ?>">Fixed Amount</option>
                                                            </select>
                                                        </div>
                                                        
                                                        <div class="col-xl-3" style="padding: 10px;">
                                                            <label for="">Markup Value per Vehicle</label>
                                                            <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1_T_${TD_id1}"></a></span>
                                                                <input type="text" class="form-control" id="vehicle_per_markup_value_${TD_id1}" name="vehicle_per_markup_value[]" onkeyup="vehicle_per_markup_AllR(${TD_id1})">
                                                            </div>
                                                        </div>
                                                        
                                                        <input type="text" class="form-control d-none" id="markup_price_per_vehicle_converted_${TD_id1}" name="markup_price_per_vehicle_converted[]" readonly>
                                                        
                                                        <div class="col-xl-3" style="padding: 10px;">
                                                            <label for="">Vehicle Total Price</label>
                                                            <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1_T_${TD_id1}"></a></span>
                                                                <input type="text" class="form-control" id="vehicle_total_price_with_markup_${TD_id1}" name="vehicle_total_price_with_markup[]" readonly>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-xl-3" style="padding: 10px;">
                                                            <label for="">Vehicle Markup Value</label>
                                                            <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value1_T_${TD_id1}"></a></span>
                                                                <input type="text" class="form-control" id="vehicle_markup_value_${TD_id1}" name="vehicle_markup_value[]" onkeyup="vehicle_markup_AllR(${TD_id1})" readonly>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-xl-3" style="padding: 10px;">
                                                            <label for="">Vehicle Markup Value</label>
                                                            <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1_T_${TD_id1}"></a></span>
                                                                <input type="text" class="form-control" id="vehicle_markup_value_converted_${TD_id1}" name="vehicle_markup_value_converted[]" readonly>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-xl-3" style="padding: 10px;">
                                                            <label for="">Vehicle Markup Price</label>
                                                            <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1_T_${TD_id1}"></a></span>
                                                                <input type="text" class="form-control" id="vehicle_total_price_with_markup_converted_${TD_id1}" name="vehicle_total_price_with_markup_converted[]" readonly>
                                                            </div>
                                                        </div>
                                                        <!--End Costing--!>
                                                        
                                                        <input type="hidden" class="transfer_markup_type_invoice" name="transfer_markup_type_invoice[]">
                                                        <input type="hidden" class="transfer_markup_invoice" name="transfer_markup_invoice[]">
                                                        <input type="hidden" class="transfer_markup_price_invoice" name="transfer_markup_price_invoice[]">
                                                        
                                                        <div id="append_transportation_${TD_id1}"></div>
                                                    </div>
                                                    <div class="col-xl-6 R_AllRound_class_${TD_id1}" style="padding: 10px;"></div>
                                                    <div class="col-xl-6 R_AllRound_class_${TD_id1}" style="padding: 10px;">
                                                        <a type="button" class="btn btn-primary" id="R_AllRound_Id_${TD_id1}" onclick="R_AllRound_function(${TD_id1},${id})" style="float: right;">Remove</a>
                                                    </div>`;
                                    
                                    $('#transportation_main_divI').append(allR_data);
                                    
                                    var destination_id = value.id;
                                    $('#destination_id_'+TD_id1+'').val(destination_id);
                                    
                                    var all_round_Type = $('#all_round_Type_'+TD_id1+'').val();
                                    
                                    addGoogleApi('transportation_pick_up_location_'+TD_id1+'');
                                    addGoogleApi('transportation_drop_off_location_'+TD_id1+'');
                                    
                                    var vehicle_detailsE = value.vehicle_details;
                                    if(vehicle_detailsE != null && vehicle_detailsE != ''){
                                        var vehicle_details = JSON.parse(vehicle_detailsE);
                                        $.each(vehicle_details, function(key, value1) {
                                            var transfer_supplier_Id    = value1.transfer_supplier_Id;
                                            var total_fare_markup       = value1.total_fare_markup;
                                            var vehicle_Name            = value1.vehicle_Name;
                                            var vehicle_Fare            = value1.vehicle_Fare;
                                            var vehicle_total_Fare      = value1.vehicle_total_Fare;
                                            var exchange_Rate           = value1.exchange_Rate;
                                            var vehicle_id              = value1.vehicle_id;
                                            
                                            if(transfer_supplier_id == transfer_supplier_Id){
                                                if(transfer_vehicle_id_td == vehicle_id){
                                                    $('#transportation_vehicle_typeI_'+TD_id1+'').empty();
                                                    var transportation_vehicle_type_Data = `<option value="${vehicle_Name}" Selected>${vehicle_Name}</option>`;
                                                    $('#transportation_vehicle_typeI_'+TD_id1+'').append(transportation_vehicle_type_Data);
                                                    
                                                    $('#transportation_price_per_vehicle_'+TD_id1+'').val(vehicle_Fare);
                                                    $('#without_markup_price_converted_destination_'+TD_id1+'').val(vehicle_total_Fare);
                                                    $('#transfer_exchange_rate_destination_'+TD_id1+'').val(exchange_Rate);
                                                    
                                                    $('#vehicle_exchange_Rate_ID_'+TD_id1+'').val(exchange_Rate);
                                                    
                                                    // $('#transportation_price_per_vehicle_'+TD_id1+'').val(total_fare_markup);
                                                    // $('#transportation_price_per_person_select').val(total_fare_markup);
                                                }
                                            }
                                            
                                        });
                                    }
                                    
                                    var more_destination_details_E  = value.more_destination_details;
                                    if(more_destination_details_E != null && more_destination_details_E != ''){
                                        var more_destination_details_D  = JSON.parse(more_destination_details_E);
                                        $.each(more_destination_details_D, function(key, value2) {
                                            var subLocationPic   = value2.subLocationPic;
                                            var subLocationdrop  = value2.subLocationdrop;
                                            
                                            var data = `<div class="row" id="click_delete_${MTD_id1}">
                                                            
                                                            <input type="hidden" name="more_all_round_Type[]" id="more_all_round_Type_${MTD_id1}">
                                                            
                                                            <div class="col-xl-3" style="padding: 10px;">
                                                                <label for="">Pick-up Location</label>
                                                                <input type="text" value="${subLocationPic}" id="more_transportation_pick_up_location_${MTD_id1}" name="more_transportation_pick_up_location[]" class="form-control">
                                                            </div>
                                                            
                                                            <div class="col-xl-3" style="padding: 10px;">
                                                                <label for="">Drop-off Location</label>
                                                                <input type="text" value="${subLocationdrop}" id="more_transportation_drop_off_location_${MTD_id1}" name="more_transportation_drop_off_location[]" class="form-control">
                                                            </div>
                                                            
                                                            <div class="col-xl-3" style="padding: 10px;">
                                                                <label for="">Pick-up Date & Time</label>
                                                                <input type="datetime-local" id="more_transportation_pick_up_date_${MTD_id1}" name="more_transportation_pick_up_date[]" class="form-control" onchange="MTPD_function(${MTD_id1})">
                                                            </div>
                                                            
                                                            <div class="col-xl-3" style="padding: 10px;">
                                                                <label for="">Drop-of Date & Time</label>
                                                                <input type="datetime-local" id="more_transportation_drop_of_date_${MTD_id1}" name="more_transportation_drop_of_date[]" class="form-control" onchange="MTDD_function(${MTD_id1})">
                                                            </div>
                                                            
                                                            <div class="col-xl-3" style="display:none" id="more_transportation_Time_Div_${MTD_id1}">
                                                                <label for="">Estimate Time</label>
                                                                <input type="text" id="more_transportation_total_Time_${MTD_id1}" name="more_transportation_total_Time[]" class="form-control" readonly style="padding: 10px;">
                                                            </div>
                                                        
                                                            <div class="mt-2 d-none">
                                                                <button style="float: right;" type="button" class="btn btn-info deletButton" onclick="deleteRowTransI(${MTD_id1})" id="${MTD_id1}">Delete</button>
                                                            </div>
                                                        </div>`;
                                            $('#append_transportation_'+TD_id1+'').append(data);
                                            
                                            $('#more_all_round_Type_'+MTD_id1+'').val(all_round_Type);
                                            
                                            addGoogleApi('more_transportation_pick_up_location_'+MTD_id1+'');
                                            addGoogleApi('more_transportation_drop_off_location_'+MTD_id1+'');
                                            
                                            MTD_id1++;
                                        });
                                    }
                                    
                                    var currency_conversion = value.currency_conversion;        
                                    var value_c         = value.currency_conversion;
                                    const usingSplit    = value_c.split(' ');
                                    var value_1         = usingSplit['0'];
                                    var value_2         = usingSplit['2'];
                                    $('.currency_value1_T_'+TD_id1+'').html(value_1);
                                    $('.currency_value_exchange_1_T_'+TD_id1+'').html(value_2);
                                    
                                    $('#currency_SAR_'+TD_id1+'').val(value_1);
                                    $('#currency_GBP_'+TD_id1+'').val(value_2);
                                    
                                    exchange_currency_funs(value_1,value_2);
                                    
                                    TD_id1++;
                                    
                                    $('#transportation_price_switch').val(TD_id1);
                                    
                                    $('#ocupancy_btn_switch_'+id+'').val(1);
                                    
                                }else{
                                    alert('Already Occupied')
                                }
                            }
                        }
                    });
                }else{
                    alert('Something Went Wrong')
                    $('#occupy_btn_'+id+'').css('display','');
                }
            },
        });
    }
    
    function delete_occupy_btn_function(id){
        
        $('#transportation_pick_up_location_select').val('');
        $('#transportation_drop_off_location_select').val('');
        $('#transportation_price_per_person_select').val('');
        $('#transportation_markup_type').empty();
        $('#transportation_markup').val('');
        $('#transportation_markup_total').val('');
        $('#transportation_cost').css('display','none');
        
        $('#select_transportation').css('display','none');
        $('#occupy_btn_'+id+'').css('display','');
        $('#delete_occupy_btn_'+id+'').css('display','none');
    }
    
    function R_AllRound_function(TD_id1,id){
        $('#allRound_Div_'+TD_id1+'').remove();
        $('.R_AllRound_class_'+TD_id1+'').remove();
        $('#ocupancy_btn_switch_'+id+'').val(0);
        $('#occupy_btn_'+id+'').css('background-color','rebeccapurple');
        $('#occupy_btn_'+id+'').css('display','');
        
        $('#transportation_markup').val('');
        $('#transportation_markup_total').val('');
        
        var transportation_price_switch = $('#transportation_price_switch').val();
        var Tran_no_of_Vehicle          = 0;
        var Tran_price_per_vehicle      = 0;
        var Tran_price_all_vehicle      = 0;
        var Tran_Orignal_price          = 0;
        var Tran_price_all_vehicleC     = 0;
        var transportation_markup       = 0;
        var transportation_markup_total = 0;
        for(x=1; x<transportation_price_switch; x++){
            var transportation_price_per_person     = $('#transportation_price_per_person_'+x+'').val();
            var transportation_no_of_vehicle        = $('#transportation_no_of_vehicle_'+x+'').val();
            var transportation_price_per_vehicle    = $('#transportation_price_per_vehicle_'+x+'').val();
            var transportation_vehicle_total_price  = $('#transportation_vehicle_total_price_'+x+'').val();
            var transportation_vehicle_total_price_converted = $('#transportation_vehicle_total_price_converted_'+x+'').val();
            var vehicle_markup_value_converted                  = $('#vehicle_markup_value_converted_'+x+'').val();
            var vehicle_total_price_with_markup_converted       = $('#vehicle_total_price_with_markup_converted_'+x+'').val();
            
            if(transportation_price_per_person != null && transportation_price_per_person != ''){
                Tran_Orignal_price = parseFloat(Tran_Orignal_price) + parseFloat(transportation_price_per_person);
                Tran_Orignal_price = Tran_Orignal_price.toFixed(2);
                $('#transportation_price_per_person_select').val(Tran_Orignal_price);
            }
            
            if(transportation_no_of_vehicle != null && transportation_no_of_vehicle != ''){
                Tran_no_of_Vehicle = parseFloat(Tran_no_of_Vehicle) + parseFloat(transportation_no_of_vehicle);
                Tran_no_of_Vehicle = Tran_no_of_Vehicle.toFixed(2);
                $('#tranf_no_of_vehicle').val(Tran_no_of_Vehicle);
            }
            
            if(transportation_price_per_vehicle != null && transportation_price_per_vehicle != ''){
                Tran_price_per_vehicle = parseFloat(Tran_price_per_vehicle) + parseFloat(transportation_price_per_vehicle);
                Tran_price_per_vehicle = Tran_price_per_vehicle.toFixed(2);
                $('#tranf_price_per_vehicle').val(Tran_price_per_vehicle);
            }
            
            if(transportation_vehicle_total_price != null && transportation_vehicle_total_price != ''){
                Tran_price_all_vehicle = parseFloat(Tran_price_all_vehicle) + parseFloat(transportation_vehicle_total_price);
                Tran_price_all_vehicle = Tran_price_all_vehicle.toFixed(2);
                $('#tranf_price_all_vehicle').val(Tran_price_all_vehicle);
                $('#transportation_price_per_person_select').val(Tran_price_all_vehicle);
            }
            
            if(transportation_vehicle_total_price_converted != null && transportation_vehicle_total_price_converted != ''){
                Tran_price_all_vehicleC = parseFloat(Tran_price_all_vehicleC) + parseFloat(transportation_vehicle_total_price_converted);
                Tran_price_all_vehicleC = Tran_price_all_vehicleC.toFixed(2);
                $('#tranf_price_all_vehicle').val(Tran_price_all_vehicleC);
                $('#transportation_price_per_person_select').val(Tran_price_all_vehicleC);
            }
            
            if(vehicle_markup_value_converted != null && vehicle_markup_value_converted != ''){
                transportation_markup = parseFloat(transportation_markup) + parseFloat(vehicle_markup_value_converted);
                transportation_markup = transportation_markup.toFixed(2);
                $('#transportation_markup').val(transportation_markup);
            }
            
            if(vehicle_total_price_with_markup_converted != null && vehicle_total_price_with_markup_converted != ''){
                transportation_markup_total = parseFloat(transportation_markup_total) + parseFloat(vehicle_total_price_with_markup_converted);
                transportation_markup_total = transportation_markup_total.toFixed(2);
                $('#transportation_markup_total').val(transportation_markup_total);
            }
            
        }
        add_numberElseI();
    }
    
    function TPUD_function(id){
        var h = "hours";
        var m = "minutes";
        var transportation_drop_of_date = $('#transportation_drop_of_date_'+id+'').val();
        var transportation_pick_up_date = $('#transportation_pick_up_date_'+id+'').val();
        
        var second=1000, minute=second*60, hour=minute*60, day=hour*24, week=day*7;
        var date1               = new Date(transportation_pick_up_date);
        var date2               = new Date(transportation_drop_of_date);
        var timediff            = date2 - date1;
        var minutes_Total       = Math.floor(timediff / minute);
        var total_hours         = Math.floor(timediff / hour)
        var total_hours_minutes = parseFloat(total_hours) * 60;
        var minutes             = parseFloat(minutes_Total) - parseFloat(total_hours_minutes);
        
        $('#transportation_Time_Div_'+id+'').css('display','');
        $('#transportation_total_Time_'+id+'').val(total_hours+h+ ' : ' +minutes+m);
    }
    
    function TDOP_function(id){
        var h = "hours";
        var m = "minutes";
        var transportation_drop_of_date = $('#transportation_drop_of_date_'+id+'').val();
        var transportation_pick_up_date = $('#transportation_pick_up_date_'+id+'').val();
        
        var second=1000, minute=second*60, hour=minute*60, day=hour*24, week=day*7;
        var date1               = new Date(transportation_pick_up_date);
        var date2               = new Date(transportation_drop_of_date);
        var timediff            = date2 - date1;
        var minutes_Total       = Math.floor(timediff / minute);
        var total_hours         = Math.floor(timediff / hour)
        var total_hours_minutes = parseFloat(total_hours) * 60;
        var minutes             = parseFloat(minutes_Total) - parseFloat(total_hours_minutes);
        
        $('#transportation_Time_Div_'+id+'').css('display','');
        $('#transportation_total_Time_'+id+'').val(total_hours+h+ ' : ' +minutes+m);
        
    }
    
    function TNOV_function(id){
        $('#transportation_markup').val('');
        $('#transportation_markup_total').val('');
        
        $('#vehicle_markup_value').val('');
        $('#vehicle_total_price_with_markup').val('');
        $('#vehicle_markup_value_converted').val('');
        $('#vehicle_total_price_with_markup_converted').val('');
        $('#vehicle_per_markup_value').val('');
        $('#markup_price_per_vehicle_converted').val('');
        
        $('#transportation_markup').val('');
        $('#transportation_markup_total').val('');
        var transportation_price_per_vehicle    =  $('#transportation_price_per_vehicle_'+id+'').val();
        var transportation_no_of_vehicle        =  $('#transportation_no_of_vehicle_'+id+'').val();
        var no_of_pax_days                      =  $('#no_of_pax_days').val();
        var t_trans1                            =  transportation_price_per_vehicle * transportation_no_of_vehicle;
        var t_trans                             =  t_trans1.toFixed(2);
        $('#transportation_vehicle_total_price_'+id+'').val(t_trans);
        var total_trans1                        = t_trans/no_of_pax_days;
        var total_trans                         = total_trans1.toFixed(2);
        $('#transportation_price_per_person_'+id+'').val(total_trans);
        $('#transportation_price_per_person_select_'+id+'').val(t_trans);
        
        var select_exchange_type    = $('#vehicle_select_exchange_type_ID_'+id+'').val();
        var exchange_Rate           = $('#vehicle_exchange_Rate_ID_'+id+'').val();
        
        if(select_exchange_type == 'Divided'){
            var without_markup_price_converted_destination    = parseFloat(transportation_price_per_vehicle)/parseFloat(exchange_Rate);
            var transportation_vehicle_total_price_converted  = parseFloat(t_trans)/parseFloat(exchange_Rate);
            var transportation_price_per_person_converted     = parseFloat(total_trans)/parseFloat(exchange_Rate);
        }else{
            var without_markup_price_converted_destination    = parseFloat(transportation_price_per_vehicle) * parseFloat(exchange_Rate);
            var transportation_vehicle_total_price_converted  = parseFloat(t_trans) * parseFloat(exchange_Rate);
            var transportation_price_per_person_converted     = parseFloat(total_trans) * parseFloat(exchange_Rate);
        }
        
        without_markup_price_converted_destination   = without_markup_price_converted_destination.toFixed(2);
        transportation_vehicle_total_price_converted = transportation_vehicle_total_price_converted.toFixed(2);
        transportation_price_per_person_converted    = transportation_price_per_person_converted.toFixed(2);
        
        $('#without_markup_price_converted_destination_'+id+'').val(without_markup_price_converted_destination);
        $('#transportation_vehicle_total_price_converted_'+id+'').val(transportation_vehicle_total_price_converted);
        $('#transportation_price_per_person_converted_'+id+'').val(transportation_price_per_person_converted);
        
        var transportation_price_switch = $('#transportation_price_switch').val();
        var Tran_no_of_Vehicle = 0;
        var Tran_price_per_vehicle = 0;
        var Tran_price_all_vehicle = 0;
        var Tran_Orignal_price = 0;
        var Tran_price_all_vehicleC = 0;
        for(x=1; x<transportation_price_switch; x++){
            var transportation_price_per_person     = $('#transportation_price_per_person_'+x+'').val();
            var transportation_no_of_vehicle        = $('#transportation_no_of_vehicle_'+x+'').val();
            var transportation_price_per_vehicle    = $('#transportation_price_per_vehicle_'+x+'').val();
            var transportation_vehicle_total_price  = $('#transportation_vehicle_total_price_'+x+'').val();
            var transportation_vehicle_total_price_converted  = $('#transportation_vehicle_total_price_converted_'+x+'').val();
            
            if(transportation_price_per_person != null && transportation_price_per_person != ''){
                Tran_Orignal_price = parseFloat(Tran_Orignal_price) + parseFloat(transportation_price_per_person);
                Tran_Orignal_price = Tran_Orignal_price.toFixed(2);
                $('#transportation_price_per_person_select').val(Tran_Orignal_price);
            }
            
            if(transportation_no_of_vehicle != null && transportation_no_of_vehicle != ''){
                Tran_no_of_Vehicle = parseFloat(Tran_no_of_Vehicle) + parseFloat(transportation_no_of_vehicle);
                Tran_no_of_Vehicle = Tran_no_of_Vehicle.toFixed(2);
                $('#tranf_no_of_vehicle').val(Tran_no_of_Vehicle);
            }
            
            if(transportation_price_per_vehicle != null && transportation_price_per_vehicle != ''){
                Tran_price_per_vehicle = parseFloat(Tran_price_per_vehicle) + parseFloat(transportation_price_per_vehicle);
                Tran_price_per_vehicle = Tran_price_per_vehicle.toFixed(2);
                $('#tranf_price_per_vehicle').val(Tran_price_per_vehicle);
            }
            
            if(transportation_vehicle_total_price != null && transportation_vehicle_total_price != ''){
                Tran_price_all_vehicle = parseFloat(Tran_price_all_vehicle) + parseFloat(transportation_vehicle_total_price);
                Tran_price_all_vehicle = Tran_price_all_vehicle.toFixed(2);
                $('#tranf_price_all_vehicle').val(Tran_price_all_vehicle);
                // $('#transportation_price_per_person_select').val(Tran_price_all_vehicle);
            }
            
            if(transportation_vehicle_total_price_converted != null && transportation_vehicle_total_price_converted != ''){
                Tran_price_all_vehicleC = parseFloat(Tran_price_all_vehicleC) + parseFloat(transportation_vehicle_total_price_converted);
                Tran_price_all_vehicleC = Tran_price_all_vehicleC.toFixed(2);
                $('#tranf_price_all_vehicle').val(Tran_price_all_vehicleC);
                $('#transportation_price_per_person_select').val(Tran_price_all_vehicleC);
            }
        }
        add_numberElseI();
    }
    
    function TPPV_function(id){
        // var Tran_Orignal_price = $('#transportation_price_per_person_select').val();
        
        $('#transportation_markup').val('');
        $('#transportation_markup_total').val('');
        
        $('#vehicle_markup_value').val('');
        $('#vehicle_total_price_with_markup').val('');
        $('#vehicle_markup_value_converted').val('');
        $('#vehicle_total_price_with_markup_converted').val('');
        $('#vehicle_per_markup_value').val('');
        $('#markup_price_per_vehicle_converted').val('');
        
        // var transportation_price_per_vehicle    =  $('#transportation_price_per_vehicle_'+id+'').val();
        var transportation_price_per_vehicle    =  $('#transportation_price_per_vehicle_'+id+'').val();
        var transportation_no_of_vehicle        =  $('#transportation_no_of_vehicle_'+id+'').val();
        var no_of_pax_days                      =  $('#no_of_pax_days').val();
        var t_trans1                            =  transportation_price_per_vehicle * transportation_no_of_vehicle;
        var t_trans                             =  t_trans1.toFixed(2);
        $('#transportation_vehicle_total_price_'+id+'').val(t_trans);
        var total_trans1                        = t_trans/no_of_pax_days;
        var total_trans                         = total_trans1.toFixed(2);
        $('#transportation_price_per_person_'+id+'').val(total_trans);
        $('#transportation_price_per_person_select_'+id+'').val(t_trans);
        
        var select_exchange_type    = $('#vehicle_select_exchange_type_ID_'+id+'').val();
        var exchange_Rate           = $('#vehicle_exchange_Rate_ID_'+id+'').val();
        if(select_exchange_type == 'Divided'){
            var without_markup_price_converted_destination    = parseFloat(transportation_price_per_vehicle)/parseFloat(exchange_Rate);
            var transportation_vehicle_total_price_converted  = parseFloat(t_trans)/parseFloat(exchange_Rate);
            var transportation_price_per_person_converted     = parseFloat(total_trans)/parseFloat(exchange_Rate);
        }else{
            var without_markup_price_converted_destination    = parseFloat(transportation_price_per_vehicle) * parseFloat(exchange_Rate);
            var transportation_vehicle_total_price_converted  = parseFloat(t_trans) * parseFloat(exchange_Rate);
            var transportation_price_per_person_converted     = parseFloat(total_trans) * parseFloat(exchange_Rate);
        }
        
        without_markup_price_converted_destination   = without_markup_price_converted_destination.toFixed(2);
        transportation_vehicle_total_price_converted = transportation_vehicle_total_price_converted.toFixed(2);
        transportation_price_per_person_converted    = transportation_price_per_person_converted.toFixed(2);
        
        $('#without_markup_price_converted_destination_'+id+'').val(without_markup_price_converted_destination);
        $('#transportation_vehicle_total_price_converted_'+id+'').val(transportation_vehicle_total_price_converted);
        $('#transportation_price_per_person_converted_'+id+'').val(transportation_price_per_person_converted);
        
        var transportation_price_switch = $('#transportation_price_switch').val();
        var Tran_no_of_Vehicle = 0;
        var Tran_price_per_vehicle = 0;
        var Tran_price_all_vehicle = 0;
        var Tran_Orignal_price = 0;
        var Tran_price_all_vehicleC = 0;
        for(x=1; x<transportation_price_switch; x++){
            var transportation_price_per_person     = $('#transportation_price_per_person_'+x+'').val();
            var transportation_no_of_vehicle        = $('#transportation_no_of_vehicle_'+x+'').val();
            var transportation_price_per_vehicle    = $('#transportation_price_per_vehicle_'+x+'').val();
            var transportation_vehicle_total_price  = $('#transportation_vehicle_total_price_'+x+'').val();
            var transportation_vehicle_total_price_converted  = $('#transportation_vehicle_total_price_converted_'+x+'').val();
            
            if(transportation_price_per_person != null && transportation_price_per_person != ''){
                Tran_Orignal_price = parseFloat(Tran_Orignal_price) + parseFloat(transportation_price_per_person);
                Tran_Orignal_price = Tran_Orignal_price.toFixed(2);
                $('#transportation_price_per_person_select').val(Tran_Orignal_price);
            }
            
            if(transportation_no_of_vehicle != null && transportation_no_of_vehicle != ''){
                Tran_no_of_Vehicle = parseFloat(Tran_no_of_Vehicle) + parseFloat(transportation_no_of_vehicle);
                Tran_no_of_Vehicle = Tran_no_of_Vehicle.toFixed(2);
                $('#tranf_no_of_vehicle').val(Tran_no_of_Vehicle);
            }
            
            if(transportation_price_per_vehicle != null && transportation_price_per_vehicle != ''){
                Tran_price_per_vehicle = parseFloat(Tran_price_per_vehicle) + parseFloat(transportation_price_per_vehicle);
                Tran_price_per_vehicle = Tran_price_per_vehicle.toFixed(2);
                $('#tranf_price_per_vehicle').val(Tran_price_per_vehicle);
            }
            
            if(transportation_vehicle_total_price != null && transportation_vehicle_total_price != ''){
                Tran_price_all_vehicle = parseFloat(Tran_price_all_vehicle) + parseFloat(transportation_vehicle_total_price);
                Tran_price_all_vehicle = Tran_price_all_vehicle.toFixed(2);
                $('#tranf_price_all_vehicle').val(Tran_price_all_vehicle);
                // $('#transportation_price_per_person_select').val(Tran_price_all_vehicle);
            }
            
            if(transportation_vehicle_total_price_converted != null && transportation_vehicle_total_price_converted != ''){
                Tran_price_all_vehicleC = parseFloat(Tran_price_all_vehicleC) + parseFloat(transportation_vehicle_total_price_converted);
                Tran_price_all_vehicleC = Tran_price_all_vehicleC.toFixed(2);
                $('#tranf_price_all_vehicle').val(Tran_price_all_vehicleC);
                $('#transportation_price_per_person_select').val(Tran_price_all_vehicleC);
            }
            
        }
        add_numberElseI();
    }
    
    function MTPD_function(id){
        var h = "hours";
        var m = "minutes";
        var transportation_drop_of_date = $('#more_transportation_drop_of_date_'+id+'').val();
        var transportation_pick_up_date = $('#more_transportation_pick_up_date_'+id+'').val();
        
        var second=1000, minute=second*60, hour=minute*60, day=hour*24, week=day*7;
        var date1               = new Date(transportation_pick_up_date);
        var date2               = new Date(transportation_drop_of_date);
        var timediff            = date2 - date1;
        var minutes_Total       = Math.floor(timediff / minute);
        var total_hours         = Math.floor(timediff / hour)
        var total_hours_minutes = parseFloat(total_hours) * 60;
        
        var minutes = parseFloat(minutes_Total) - parseFloat(total_hours_minutes);
        
        $('#more_transportation_Time_Div_'+id+'').css('display','');
        $('#more_transportation_total_Time_'+id+'').val(total_hours+h+ ' : ' +minutes+m);
    }
    
    function MTDD_function(id){
        var h = "hours";
        var m = "minutes";
        var transportation_drop_of_date = $('#more_transportation_drop_of_date_'+id+'').val();
        var transportation_pick_up_date = $('#more_transportation_pick_up_date_'+id+'').val();
        
        var second=1000, minute=second*60, hour=minute*60, day=hour*24, week=day*7;
        var date1               = new Date(transportation_pick_up_date);
        var date2               = new Date(transportation_drop_of_date);
        var timediff            = date2 - date1;
        var minutes_Total       = Math.floor(timediff / minute);
        var total_hours         = Math.floor(timediff / hour)
        var total_hours_minutes = parseFloat(total_hours) * 60;
        
        var minutes = parseFloat(minutes_Total) - parseFloat(total_hours_minutes);
        
        $('#more_transportation_Time_Div_'+id+'').css('display','');
        $('#more_transportation_total_Time_'+id+'').val(total_hours+h+ ' : ' +minutes+m);
    }

    function vehicle_per_markup_OWandR(){
        var vehicle_per_markup_value        = $('#vehicle_per_markup_value').val();
        var transportation_no_of_vehicle    = $('#transportation_no_of_vehicle').val();
        var total_price_all_vehicle_vehicle = parseFloat(transportation_no_of_vehicle) * parseFloat(vehicle_per_markup_value);
        total_price_all_vehicle_vehicle     = total_price_all_vehicle_vehicle.toFixed(2);
        $('#vehicle_markup_value').val(total_price_all_vehicle_vehicle);
        var without_markup_price_converted_destination      = $('#without_markup_price_converted_destination').val();
        
        var vehicle_markup_type                             = $('#vehicle_markup_type').val();
        var vehicle_markup_value                            = $('#vehicle_markup_value').val();
        var transportation_vehicle_total_price              = $('#transportation_vehicle_total_price').val();
        var transportation_vehicle_total_price_converted    = $('#transportation_vehicle_total_price_converted').val();
        var vehicle_exchange_Rate_ID                        = $('#vehicle_exchange_Rate_ID').val();;
        var vehicle_select_exchange_type_ID                 = $('#vehicle_select_exchange_type_ID').val();;
        
        if(vehicle_select_exchange_type_ID == 'Divided'){
            var vehicle_markup_value_converted      = parseFloat(vehicle_markup_value)/parseFloat(vehicle_exchange_Rate_ID);
            var price_per_vehicle_with_converted    = parseFloat(vehicle_per_markup_value)/parseFloat(vehicle_exchange_Rate_ID);
        }
        else{
            var vehicle_markup_value_converted      = parseFloat(vehicle_markup_value) * parseFloat(vehicle_exchange_Rate_ID);
            var price_per_vehicle_with_converted    = parseFloat(vehicle_per_markup_value) * parseFloat(vehicle_exchange_Rate_ID);
        }
        
        vehicle_markup_value_converted = vehicle_markup_value_converted.toFixed(2);
        $('#vehicle_markup_value_converted').val(vehicle_markup_value_converted);
        $('#transportation_markup').val(vehicle_markup_value_converted);
        
        if(vehicle_markup_type == ''){
            alert('Select Markup Type');
            $('#vehicle_per_markup_value').val('');
            $('#vehicle_total_price_with_markup').val('');
            $('#vehicle_markup_value').val('');
            $('#vehicle_markup_value_converted').val('');
            $('#vehicle_total_price_with_markup_converted').val('');
        }
        else if(vehicle_markup_type == '%'){
            var total1 = (transportation_vehicle_total_price * vehicle_markup_value/100) + parseFloat(transportation_vehicle_total_price);
            var total  = total1.toFixed(2);
            $('#vehicle_total_price_with_markup').val(total);
            add_numberElse_1I();
            
            var total1_C = (transportation_vehicle_total_price_converted * vehicle_markup_value_converted/100) + parseFloat(transportation_vehicle_total_price_converted);
            var total_C  = total1_C.toFixed(2);
            $('#vehicle_total_price_with_markup_converted').val(total_C);
            $('#transportation_markup_total').val(total_C);
            
            add_numberElse_1I();
            
            $('#transportation_markup_type').empty();
            var transportation_markup_type = `<option value="%" selected>Percentage</option>`;
            $('#transportation_markup_type').append(transportation_markup_type);
            
            var S_total1_C = (without_markup_price_converted_destination * price_per_vehicle_with_converted/100) + parseFloat(without_markup_price_converted_destination);
            var S_total_C  = S_total1_C.toFixed(2);
            $('#markup_price_per_vehicle_converted').val(S_total_C);
        }
        else{
            var total1 = parseFloat(transportation_vehicle_total_price) + parseFloat(vehicle_markup_value);
            var total  = total1.toFixed(2);
            $('#vehicle_total_price_with_markup').val(total);
            add_numberElse_1I();
            
            var total1_C = parseFloat(transportation_vehicle_total_price_converted) + parseFloat(vehicle_markup_value_converted);
            var total_C  = total1_C.toFixed(2);
            $('#vehicle_total_price_with_markup_converted').val(total_C);
            $('#transportation_markup_total').val(total_C);
            
            add_numberElse_1I();
            
            $('#transportation_markup_type').empty();
            var transportation_markup_type = `<option value="<?php echo $currency; ?>" selected>Fixed Amount</option>`;
            $('#transportation_markup_type').append(transportation_markup_type);
            
            var S_total1_C = parseFloat(without_markup_price_converted_destination) + parseFloat(price_per_vehicle_with_converted);
            var S_total_C  = S_total1_C.toFixed(2);
            $('#markup_price_per_vehicle_converted').val(S_total_C);
        }
    }
    
    function vehicle_per_markup_AllR(id){
        var vehicle_per_markup_value        = $('#vehicle_per_markup_value_'+id+'').val();
        var transportation_no_of_vehicle    = $('#transportation_no_of_vehicle_'+id+'').val();
        var total_price_all_vehicle_vehicle = parseFloat(transportation_no_of_vehicle) * parseFloat(vehicle_per_markup_value);
        total_price_all_vehicle_vehicle     = total_price_all_vehicle_vehicle.toFixed(2);
        $('#vehicle_markup_value_'+id+'').val(total_price_all_vehicle_vehicle);
        var without_markup_price_converted_destination      = $('#without_markup_price_converted_destination_'+id+'').val();
        
        $('#transportation_markup').val('');
        $('#transportation_markup_total').val('');
        
        $('#vehicle_markup_value').val('');
        $('#vehicle_total_price_with_markup').val('');
        $('#vehicle_markup_value_converted').val('');
        $('#vehicle_total_price_with_markup_converted').val('');
        
        var vehicle_markup_type                             = $('#vehicle_markup_type_'+id+'').val();
        var vehicle_markup_value                            = $('#vehicle_markup_value_'+id+'').val();
        var transportation_vehicle_total_price              = $('#transportation_vehicle_total_price_'+id+'').val();
        var transportation_vehicle_total_price_converted    = $('#transportation_vehicle_total_price_converted_'+id+'').val();
        var vehicle_exchange_Rate_ID                        = $('#vehicle_exchange_Rate_ID_'+id+'').val();
        var vehicle_select_exchange_type_ID                 = $('#vehicle_select_exchange_type_ID_'+id+'').val();
        
        if(vehicle_select_exchange_type_ID == 'Divided'){
            var vehicle_markup_value_converted      = parseFloat(vehicle_markup_value)/parseFloat(vehicle_exchange_Rate_ID);
            var price_per_vehicle_with_converted    = parseFloat(vehicle_per_markup_value)/parseFloat(vehicle_exchange_Rate_ID);
        }
        else{
            var vehicle_markup_value_converted      = parseFloat(vehicle_markup_value) * parseFloat(vehicle_exchange_Rate_ID);
            var price_per_vehicle_with_converted    = parseFloat(vehicle_per_markup_value) * parseFloat(vehicle_exchange_Rate_ID);
        }
        
        vehicle_markup_value_converted = vehicle_markup_value_converted.toFixed(2);
        $('#vehicle_markup_value_converted_'+id+'').val(vehicle_markup_value_converted);
        // $('#transportation_markup').val(vehicle_markup_value_converted);
        
        if(vehicle_markup_type == ''){
            alert('Select Markup Type');
            $('#vehicle_total_price_with_markup_'+id+'').val('');
            $('#vehicle_markup_value_'+id+'').val('');
            $('#vehicle_markup_value_converted_'+id+'').val('');
            $('#vehicle_total_price_with_markup_converted_'+id+'').val('');
            $('#vehicle_per_markup_value_'+id+'').val('');
        }
        else if(vehicle_markup_type == '%'){
            var total1 = (transportation_vehicle_total_price * vehicle_markup_value/100) + parseFloat(transportation_vehicle_total_price);
            var total  = total1.toFixed(2);
            $('#vehicle_total_price_with_markup_'+id+'').val(total);
            add_numberElse_1I();
            
            var total1_C = (transportation_vehicle_total_price_converted * vehicle_markup_value_converted/100) + parseFloat(transportation_vehicle_total_price_converted);
            var total_C  = total1_C.toFixed(2);
            $('#vehicle_total_price_with_markup_converted_'+id+'').val(total_C);
            // $('#transportation_markup_total').val(total_C);
            
            add_numberElse_1I();
            
            $('#transportation_markup_type').empty();
            var transportation_markup_type = `<option value="%" selected>Percentage</option>`;
            $('#transportation_markup_type').append(transportation_markup_type);
            
            var S_total1_C = (without_markup_price_converted_destination * price_per_vehicle_with_converted/100) + parseFloat(without_markup_price_converted_destination);
            var S_total_C  = S_total1_C.toFixed(2);
            $('#markup_price_per_vehicle_converted_'+id+'').val(S_total_C);
        }
        else{
            var total1 = parseFloat(transportation_vehicle_total_price) + parseFloat(vehicle_markup_value);
            var total  = total1.toFixed(2);
            $('#vehicle_total_price_with_markup_'+id+'').val(total);
            add_numberElse_1I();
            
            var total1_C = parseFloat(transportation_vehicle_total_price_converted) + parseFloat(vehicle_markup_value_converted);
            var total_C  = total1_C.toFixed(2);
            $('#vehicle_total_price_with_markup_converted_'+id+'').val(total_C);
            // $('#transportation_markup_total').val(total_C);
            add_numberElse_1I();
            
            $('#transportation_markup_type').empty();
            var transportation_markup_type = `<option value="<?php echo $currency; ?>" selected>Fixed Amount</option>`;
            $('#transportation_markup_type').append(transportation_markup_type);
            
            var S_total1_C = parseFloat(without_markup_price_converted_destination) + parseFloat(price_per_vehicle_with_converted);
            var S_total_C  = S_total1_C.toFixed(2);
            $('#markup_price_per_vehicle_converted_'+id+'').val(S_total_C);
        }
        
        var transportation_price_switch     = $('#transportation_price_switch').val();
        var transportation_markup           = 0;
        var transportation_markup_total     = 0;
        for(x=1; x<transportation_price_switch; x++){
            var vehicle_markup_value_converted              = $('#vehicle_markup_value_converted_'+x+'').val();
            var vehicle_total_price_with_markup_converted   = $('#vehicle_total_price_with_markup_converted_'+x+'').val();
            
            if(vehicle_markup_value_converted != null && vehicle_markup_value_converted != ''){
                transportation_markup = parseFloat(transportation_markup) + parseFloat(vehicle_markup_value_converted);
                transportation_markup = transportation_markup.toFixed(2);
                $('#transportation_markup').val(transportation_markup);
            }
            
            if(vehicle_total_price_with_markup_converted != null && vehicle_total_price_with_markup_converted != ''){
                transportation_markup_total = parseFloat(transportation_markup_total) + parseFloat(vehicle_total_price_with_markup_converted);
                transportation_markup_total = transportation_markup_total.toFixed(2);
                $('#transportation_markup_total').val(transportation_markup_total);
            }
            
        }
        add_numberElseI();
        
    }
    
    function vehicle_markup_OWandR(){
        var vehicle_markup_type                             = $('#vehicle_markup_type').val();
        var vehicle_markup_value                            = $('#vehicle_markup_value').val();
        var transportation_vehicle_total_price              = $('#transportation_vehicle_total_price').val();
        var transportation_vehicle_total_price_converted    = $('#transportation_vehicle_total_price_converted').val();
        var vehicle_exchange_Rate_ID                        = $('#vehicle_exchange_Rate_ID').val();;
        var vehicle_select_exchange_type_ID                 = $('#vehicle_select_exchange_type_ID').val();;
        
        if(vehicle_select_exchange_type_ID == 'Divided'){
            var vehicle_markup_value_converted = parseFloat(vehicle_markup_value)/parseFloat(vehicle_exchange_Rate_ID);
        }
        else{
            var vehicle_markup_value_converted = parseFloat(vehicle_markup_value) * parseFloat(vehicle_exchange_Rate_ID);
        }
        
        vehicle_markup_value_converted = vehicle_markup_value_converted.toFixed(2);
        $('#vehicle_markup_value_converted').val(vehicle_markup_value_converted);
        $('#transportation_markup').val(vehicle_markup_value_converted);
        
        if(vehicle_markup_type == ''){
            alert('Select Markup Type');
            $('#vehicle_per_markup_value').val('');
            $('#vehicle_total_price_with_markup').val('');
            $('#vehicle_markup_value').val('');
            $('#vehicle_markup_value_converted').val('');
            $('#vehicle_total_price_with_markup_converted').val('');
        }
        else if(vehicle_markup_type == '%'){
            var total1 = (transportation_vehicle_total_price * vehicle_markup_value/100) + parseFloat(transportation_vehicle_total_price);
            var total  = total1.toFixed(2);
            $('#vehicle_total_price_with_markup').val(total);
            add_numberElse_1I();
            
            var total1_C = (transportation_vehicle_total_price_converted * vehicle_markup_value_converted/100) + parseFloat(transportation_vehicle_total_price_converted);
            var total_C  = total1_C.toFixed(2);
            $('#vehicle_total_price_with_markup_converted').val(total_C);
            $('#transportation_markup_total').val(total_C);
            
            add_numberElse_1I();
            
            $('#transportation_markup_type').empty();
            var transportation_markup_type = `<option value="%" selected>Percentage</option>`;
            $('#transportation_markup_type').append(transportation_markup_type);
        }
        else{
            var total1 = parseFloat(transportation_vehicle_total_price) + parseFloat(vehicle_markup_value);
            var total  = total1.toFixed(2);
            $('#vehicle_total_price_with_markup').val(total);
            add_numberElse_1I();
            
            var total1_C = parseFloat(transportation_vehicle_total_price_converted) + parseFloat(vehicle_markup_value_converted);
            var total_C  = total1_C.toFixed(2);
            $('#vehicle_total_price_with_markup_converted').val(total_C);
            $('#transportation_markup_total').val(total_C);
            add_numberElse_1I();
            
            $('#transportation_markup_type').empty();
            var transportation_markup_type = `<option value="<?php echo $currency; ?>" selected>Fixed Amount</option>`;
            $('#transportation_markup_type').append(transportation_markup_type);
        }
    }
    
    function vehicle_markup_AllR(id){
        $('#transportation_markup').val('');
        $('#transportation_markup_total').val('');
        
        $('#vehicle_markup_value').val('');
        $('#vehicle_total_price_with_markup').val('');
        $('#vehicle_markup_value_converted').val('');
        $('#vehicle_total_price_with_markup_converted').val('');
        
        var vehicle_markup_type                             = $('#vehicle_markup_type_'+id+'').val();
        var vehicle_markup_value                            = $('#vehicle_markup_value_'+id+'').val();
        var transportation_vehicle_total_price              = $('#transportation_vehicle_total_price_'+id+'').val();
        var transportation_vehicle_total_price_converted    = $('#transportation_vehicle_total_price_converted_'+id+'').val();
        var vehicle_exchange_Rate_ID                        = $('#vehicle_exchange_Rate_ID_'+id+'').val();
        var vehicle_select_exchange_type_ID                 = $('#vehicle_select_exchange_type_ID_'+id+'').val();
        
        if(vehicle_select_exchange_type_ID == 'Divided'){
            var vehicle_markup_value_converted = parseFloat(vehicle_markup_value)/parseFloat(vehicle_exchange_Rate_ID);
        }
        else{
            var vehicle_markup_value_converted = parseFloat(vehicle_markup_value) * parseFloat(vehicle_exchange_Rate_ID);
        }
        
        vehicle_markup_value_converted = vehicle_markup_value_converted.toFixed(2);
        $('#vehicle_markup_value_converted_'+id+'').val(vehicle_markup_value_converted);
        // $('#transportation_markup').val(vehicle_markup_value_converted);
        
        if(vehicle_markup_type == ''){
            alert('Select Markup Type');
            $('#vehicle_total_price_with_markup_'+id+'').val('');
            $('#vehicle_markup_value_'+id+'').val('');
            $('#vehicle_markup_value_converted_'+id+'').val('');
            $('#vehicle_total_price_with_markup_converted_'+id+'').val('');
            $('#vehicle_per_markup_value_'+id+'').val('');
        }
        else if(vehicle_markup_type == '%'){
            var total1 = (transportation_vehicle_total_price * vehicle_markup_value/100) + parseFloat(transportation_vehicle_total_price);
            var total  = total1.toFixed(2);
            $('#vehicle_total_price_with_markup_'+id+'').val(total);
            add_numberElse_1I();
            
            var total1_C = (transportation_vehicle_total_price_converted * vehicle_markup_value_converted/100) + parseFloat(transportation_vehicle_total_price_converted);
            var total_C  = total1_C.toFixed(2);
            $('#vehicle_total_price_with_markup_converted_'+id+'').val(total_C);
            // $('#transportation_markup_total').val(total_C);
            
            add_numberElse_1I();
            
            $('#transportation_markup_type').empty();
            var transportation_markup_type = `<option value="%" selected>Percentage</option>`;
            $('#transportation_markup_type').append(transportation_markup_type);
        }
        else{
            var total1 = parseFloat(transportation_vehicle_total_price) + parseFloat(vehicle_markup_value);
            var total  = total1.toFixed(2);
            $('#vehicle_total_price_with_markup_'+id+'').val(total);
            add_numberElse_1I();
            
            var total1_C = parseFloat(transportation_vehicle_total_price_converted) + parseFloat(vehicle_markup_value_converted);
            var total_C  = total1_C.toFixed(2);
            $('#vehicle_total_price_with_markup_converted_'+id+'').val(total_C);
            // $('#transportation_markup_total').val(total_C);
            add_numberElse_1I();
            
            $('#transportation_markup_type').empty();
            var transportation_markup_type = `<option value="<?php echo $currency; ?>" selected>Fixed Amount</option>`;
            $('#transportation_markup_type').append(transportation_markup_type);
        }
        
        var transportation_price_switch     = $('#transportation_price_switch').val();
        var transportation_markup           = 0;
        var transportation_markup_total     = 0;
        for(x=1; x<transportation_price_switch; x++){
            var vehicle_markup_value_converted              = $('#vehicle_markup_value_converted_'+x+'').val();
            var vehicle_total_price_with_markup_converted   = $('#vehicle_total_price_with_markup_converted_'+x+'').val();
            
            if(vehicle_markup_value_converted != null && vehicle_markup_value_converted != ''){
                transportation_markup = parseFloat(transportation_markup) + parseFloat(vehicle_markup_value_converted);
                transportation_markup = transportation_markup.toFixed(2);
                $('#transportation_markup').val(transportation_markup);
            }
            
            if(vehicle_total_price_with_markup_converted != null && vehicle_total_price_with_markup_converted != ''){
                transportation_markup_total = parseFloat(transportation_markup_total) + parseFloat(vehicle_total_price_with_markup_converted);
                transportation_markup_total = transportation_markup_total.toFixed(2);
                $('#transportation_markup_total').val(transportation_markup_total);
            }
            
        }
        add_numberElseI();
        
    }
    // End Transportation
</script>
<!--Transportation End-->

<script>
    let places,input, address, city;
    
    function addGoogleApi(id){
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
</script>
  
@stop

@section('slug')

@extends('template/frontend/userdashboard/pages/manage_Office/Invoices/editInvoiceScript')

@stop