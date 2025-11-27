<?php

//print_r($get_invoice);die();

?>



@extends('template/frontend/userdashboard/layout/default')
@section('content')

<?php $currency=Session::get('currency_symbol'); ?>





<div id="flights-Airline-Name" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
<div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Add Flight Airline Name</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form class="ps-3 pe-3" action="#">
                    <div class="mb-3">
                        <label for="username" class="form-label">Flight Airline Name</label>
                        <input class="form-control" type="other_Airline_Name" id="other_Airline_Name" name="other_Airline_Name" placeholder="">
                    </div>

                    <div class="mb-3 text-center">
                        <button class="btn btn-primary" id="submitForm_Airline_Name" type="submit">Submit</button>
                    </div>

                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal --> 
 
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
</div><!-- /.modal -->

<div id="hotel-name-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Add Hotel Name</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form class="ps-3 pe-3" action="#">
                    <div class="mb-3">
                        <label for="username" class="form-label">Hotel Name</label>
                        <input class="form-control" type="other_Hotel_Name" id="other_Hotel_Name" name="other_Hotel_Name" placeholder="">
                    </div>
                    <div class="mb-3 text-center">
                        <button class="btn btn-primary" id="submitForm_hotel_name" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="hotel-name-new-modal" aria-hidden="true" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-modal="true" role="dialog" style="display: none; padding-left: 0px;">
    <div class="modal-dialog modal-lg" style="margin-right: 50%;">
        <div class="modal-content" style="width: 200%;">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Add Hotel</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form id="form_submit_id" action="#" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" style="background-color:#d4edda;" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                @endif
                
                                @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" style="background-color:#f5cfcf;" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">GENERAL</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="facilities-tab" data-bs-toggle="tab" href="#facilities" role="tab" aria-controls="facilities" aria-selected="false">FACILITIES</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="meta-info-tab" data-bs-toggle="tab" href="#meta-info" role="tab" aria-controls="meta-info" aria-selected="false">META INFO</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="policy-tab" data-bs-toggle="tab" href="#policy" role="tab" aria-controls="policy" aria-selected="false">POLICY</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">CONTACT</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="translate-tab" data-bs-toggle="tab" href="#translate" role="tab" aria-controls="translate" aria-selected="false">TRANSLATE</a>
                                    </li>
                                </ul>
                                <!-- Main Tab Contant Start -->
                                <div class="tab-content" id="myTabContent">
                                    <!-- General Tab Start -->
                                    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                                        <div class="row">
                                            <div class="col-md-8" style="padding: 15px;">
                                                <label for="">PROPERTY TITLE</label>
                                                <input id="property_name" type="text" class="form-control @error('property_name') is-invalid @enderror" name="property_name" value="{{ old('property_name') }}" autocomplete="property_name" autofocus>
                
                                                @error('property_name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-4" style="padding: 15px;">
                                                <label for="">PROPERTY IMAGE</label>
                                                <input id="property_img" type="file" class="form-control @error('property_img') is-invalid @enderror" name="property_img" value="{{ old('property_img') }}" autocomplete="property_img" autofocus>
                
                                                @error('property_img')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div> 
                                            <div class="col-md-12" style="padding: 15px;">
                                                <label for="">PROPERTY DESCRIPTION</label>
                                                <textarea name="property_desc" row="5" class="form-control @error('property_desc') is-invalid @enderror"  value="{{ old('property_desc') }}"  autocomplete="property_desc" placeholder="Enter ga message"></textarea>
                
                                                @error('property_desc')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-12" style="padding: 15px;">
                                                <label for="">GOOGLE MAP URL</label>
                                                <input id="property_google_map" type="text" class="form-control @error('property_google_map') is-invalid @enderror" name="property_google_map" value="{{ old('property_google_map') }}" autocomplete="property_google_map" autofocus>
                
                                                @error('property_google_map')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-6" style="padding: 15px;">
                                                <label for="">LATITUDE</label>
                                                <input id="latitude" type="text" class="form-control @error('latitude') is-invalid @enderror" name="latitude" value="{{ old('latitude') }}" autocomplete="latitude" autofocus>
                
                                                @error('latitude')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-6" style="padding: 15px;">
                                                <label for="">LONGITUDE</label>
                                                <input id="longitude" type="text" class="form-control @error('longitude') is-invalid @enderror" name="longitude" value="{{ old('longitude') }}" autocomplete="longitude" autofocus>
                
                                                @error('longitude')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-6" style="padding: 15px;">
                                                <label for="">PRICE TYPE</label>
                                                <select name="price_type" id="" class="form-control">
                                                    <option value="Budget">Budget</option>
                                                    <option value="Economy">Economy</option>
                                                    <option value="Moderate">Moderate</option>
                                                    <option value="Deluxe">Deluxe</option>
                                                    <option value="Premium">Premium</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6" style="padding: 15px;">
                                                <label for="">PRICE STAR</label>
                                                <select name="star_type" id="" class="form-control">
                                                    <option value="1">1 Star</option>
                                                    <option value="2">2 Star</option>
                                                    <option value="3">3 Star</option>
                                                    <option value="4">4 Star</option>
                                                    <option value="5">5 Star</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6" style="padding: 15px;">
                                                <label for="">COUNTRY</label>
                                                <select name="property_country" onchange="selectCities()" id="property_country" class="form-control">
                                                    @foreach($all_countries as $country_res)
                                                    <option value="{{ $country_res->id }}">{{ $country_res->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                
                                            <div class="col-md-6" style="padding: 15px;">
                                                <label for="">City</label>
                                                <select name="property_city" id="property_city" class="form-control">
                                                   
                                                </select>
                                            </div>  
                                        </div>
                                    </div>
                                    <!-- facilities Tab Start -->
                                    <div class="tab-pane fade" id="facilities" role="tabpanel" aria-labelledby="facilities-tab">
                                        <div class="form-check" style="margin-top: 5px;">
                                            <input class="form-check-input" type="checkbox" value="" id="checkall">
                                            <label class="form-check-label" for="checkall">
                                                Select All
                                            </label>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="Facilites[]" value="Airport Transport" id="Airport_Transport">
                                                    <label class="form-check-label" for="Airport_Transport">
                                                         Airport Transport
                                                    </label>
                                                </div>
                                            </div>
                
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="Facilites[]" value="Business Center" id="Business_Center">
                                                    <label class="form-check-label" for="Business_Center">
                                                        Business Center
                                                    </label>
                                                </div>
                                            </div>
                
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="Facilites[]" value="Disabled Facilities" id="Disabled_Facilities">
                                                    <label class="form-check-label" for="Disabled_Facilities">
                                                        Disabled Facilities
                                                    </label>
                                                </div>
                                            </div>
                
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="Facilites[]" value="Night Club" id="Night_Club">
                                                    <label class="form-check-label" for="Night_Club">
                                                        Night Club
                                                    </label>
                                                </div>
                                            </div>
                
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="Facilites[]" value="Laundry Service" id="Laundry Service">
                                                    <label class="form-check-label" for="Laundry Service">
                                                        Laundry Service
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="Facilites[]" value="Restaurant" id="Restaurant">
                                                    <label class="form-check-label" for="Restaurant">
                                                        Restaurant
                                                    </label>
                                                </div>
                                            </div>
                                          
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="Facilites[]" value="Wi-Fi Internet" id="Wi-Fi_Internet">
                                                    <label class="form-check-label" for="Wi-Fi_Internet">
                                                        Wi-Fi Internet
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="Facilites[]" value="Bar Lounge" id="Bar_Lounge">
                                                    <label class="form-check-label" for="Bar_Lounge">
                                                        Bar Lounge
                                                    </label>
                                                </div>
                                            </div>
                
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="Facilites[]" value="Swimming Pool" id="Swimming_Pool">
                                                    <label class="form-check-label" for="Swimming_Pool">
                                                        Swimming Pool
                                                    </label>
                                                </div>
                                            </div>
                
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="Facilites[]" value="Inside Parking" id="Inside_Parking">
                                                    <label class="form-check-label" for="Inside_Parking">
                                                        Inside Parking
                                                    </label>
                                                </div>
                                            </div>
                
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="Facilites[]" value="Shuttle Bus Service" id="Shuttle_Bus_Service">
                                                    <label class="form-check-label" for="Shuttle_Bus_Service">
                                                        Shuttle Bus Service
                                                    </label>
                                                </div>
                                            </div>
                
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="Facilites[]" value="Fitness Center" id="Fitness_Center">
                                                    <label class="form-check-label" for="Fitness_Center">
                                                        Fitness Center
                                                    </label>
                                                </div>
                                            </div>
                
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="Facilites[]" value="Children Activites" id="Children_Activites">
                                                    <label class="form-check-label" for="Children_Activites">
                                                         Children Activites
                                                    </label>
                                                </div>
                                            </div>
                
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="Facilites[]" value="Air Conditioner" id="Air_Conditioner">
                                                    <label class="form-check-label" for="Air_Conditioner">
                                                        Air Conditioner
                                                    </label>
                                                </div>
                                            </div>
                
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="Facilites[]" value="Cards Accepted" id="Cards_Accepted">
                                                    <label class="form-check-label" for="Cards_Accepted">
                                                        Cards Accepted
                                                    </label>
                                                </div>
                                            </div>
                
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="Facilites[]" value="Banquet Hall" id="Banquet_Hall">
                                                    <label class="form-check-label" for="Banquet_Hall">
                                                        Banquet Hall
                                                    </label>
                                                </div>
                                            </div>
                
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="Facilites[]" value="Elevator" id="Elevator">
                                                    <label class="form-check-label" for="Elevator">
                                                        Elevator
                                                    </label>
                                                </div>
                                            </div>
                
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="Facilites[]" value="Pets Allowed" id="Pets_Allowed">
                                                    <label class="form-check-label" for="Pets_Allowed">
                                                        Pets Allowed
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Meta Info Tab Start -->
                                    <div class="tab-pane fade" id="meta-info" role="tabpanel" aria-labelledby="meta-info-tab">
                                        <div class="row">
                                            <div class="col-md-12" style="padding: 15px;">
                                                <label for="">META TITLE</label>
                                                <input id="meta_title" type="text" class="form-control @error('meta_title') is-invalid @enderror" name="meta_title" value="{{ old('meta_title') }}" autocomplete="meta_title" autofocus>
                
                                                @error('meta_title')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                                <div class="col-md-12" style="padding: 15px;">
                                                    <label for="">META KEYWORDS</label>
                                                    <textarea name="meta_keywords" row="5" class="form-control @error('meta_keywords') is-invalid @enderror"  value="{{ old('meta_keywords') }}"  autocomplete="meta_keywords" placeholder="Enter ga message"></textarea>
                
                                                    @error('meta_keywords')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12" style="padding: 15px;">
                                                    <label for="">META DESCRIPTION</label>
                                                    <textarea name="meta_desc" row="5" class="form-control @error('meta_desc') is-invalid @enderror"  value="{{ old('meta_desc') }}"  autocomplete="meta_desc" placeholder="Enter ga message"></textarea>
                
                                                    @error('meta_desc')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                        </div>
                                    </div>
                                    <!-- Policy Tab Start -->
                                    <div class="tab-pane fade" id="policy" role="tabpanel" aria-labelledby="policy-tab">
                                        <div class="row">
                                            <div class="col-md-6" style="padding: 15px;">
                                                <label for="">CHECK-IN FROM</label>
                                                <select name="hotel_check_in" id="" class="form-control">
                                                    <option value="07:00">07:00</option>
                                                    <option value="07:30">07:30</option> 
                                                    <option value="08:00">08:00</option> 
                                                    <option value="08:30">08:30</option> 
                                                    <option value="09:00">09:00</option>
                                                    <option value="09:30">09:30</option> 
                                                    <option value="10:00">10:00</option>
                                                    <option value="10:30">10:30</option> 
                                                    <option value="11:00">11:00</option>
                                                    <option value="11:30">11:30</option> 
                                                    <option selected value="12:00">12:00</option>
                                                    <option value="12:30">12:30</option> 
                                                    <option value="13:00">13:00</option>
                                                    <option value="13:30">13:30</option> 
                                                    <option value="14:00">14:00</option>
                                                    <option value="14:30">14:30</option> 
                                                    <option value="15:00">15:00</option>
                                                    <option value="15:30">15:30</option> 
                                                    <option value="16:00">16:00</option>
                                                    <option value="16:30">16:30</option> 
                                                    <option value="17:00">17:00</option>
                                                    <option value="17:30">17:30</option> 
                                                    <option value="18:00">18:00</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6" style="padding: 15px;">
                                                <label for="">CHECK-OUT TO</label>
                                                <select name="hotel_check_out" id="" class="form-control">
                                                    <option value="07:00">07:00</option>
                                                    <option value="07:30">07:30</option> 
                                                    <option value="08:00">08:00</option> 
                                                    <option value="08:30">08:30</option> 
                                                    <option value="09:00">09:00</option>
                                                    <option value="09:30">09:30</option> 
                                                    <option value="10:00">10:00</option>
                                                    <option value="10:30">10:30</option> 
                                                    <option value="11:00">11:00</option>
                                                    <option value="11:30">11:30</option> 
                                                    <option selected value="12:00">12:00</option>
                                                    <option value="12:30">12:30</option> 
                                                    <option value="13:00">13:00</option>
                                                    <option value="13:30">13:30</option> 
                                                    <option value="14:00">14:00</option>
                                                    <option value="14:30">14:30</option> 
                                                    <option value="15:00">15:00</option>
                                                    <option value="15:30">15:30</option> 
                                                    <option value="16:00">16:00</option>
                                                    <option value="16:30">16:30</option> 
                                                    <option value="17:00">17:00</option>
                                                    <option value="17:30">17:30</option> 
                                                    <option value="18:00">18:00</option>
                                                </select>
                                            </div>
                
                                            <div class="col-md-12" style="padding: 15px;">
                                                <label for="">PAYMENT OPTION</label>
                                                <select name="payment_option" id="" class="form-control">
                                                    <option value="At Arrival">At Arrival</option>
                                                </select>
                                            </div>
                
                                            <div class="col-md-12" style="padding: 15px;">
                                                <label for="">POLICY AND TERMS</label>
                                                <textarea name="policy_and_terms" row="5" class="form-control @error('policy_and_terms') is-invalid @enderror"  value="{{ old('policy_and_terms') }}"  autocomplete="policy_and_terms" placeholder="Enter ga message"></textarea>
                
                                                @error('policy_and_terms')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div> 
                                        </div>
                                    </div>
                                    <!-- Contact Tab Start -->
                                    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                        <div class="row">
                                            <div class="col-md-12" style="padding: 15px;">
                                                <label for="">E mail</label>
                                                <input id="hotel_email" type="text" class="form-control @error('hotel_email') is-invalid @enderror" name="hotel_email" value="{{ old('hotel_email') }}" autocomplete="hotel_email" autofocus>
                
                                                @error('hotel_email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-12" style="padding: 15px;">
                                                <label for="">Hotel's Website</label>
                                                <input id="hotel_website" type="text" class="form-control @error('hotel_website') is-invalid @enderror" name="hotel_website" value="{{ old('hotel_website') }}" autocomplete="hotel_website" autofocus>
                
                                                @error('hotel_website')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-12" style="padding: 15px;">
                                                <label for="">Phone Number</label>
                                                <input id="property_phone" type="text" class="form-control @error('property_phone') is-invalid @enderror" name="property_phone" value="{{ old('property_phone') }}" autocomplete="property_phone" autofocus>
                
                                                @error('property_phone')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-12" style="padding: 15px;">
                                                <label for="">PROPERTY ADDRESS</label>
                                                <input id="property_address" type="text" class="form-control @error('property_address') is-invalid @enderror" name="property_address" value="{{ old('property_address') }}" autocomplete="property_address" autofocus>
                
                                                @error('property_address')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Translate Tab Start --> 
                                    <div class="tab-pane fade" id="translate" role="tabpanel" aria-labelledby="translate-tab">translate</div>
                                </div>
                                <!-- Main Tab Contant End -->
                            </div>
                            <div class="col-md-4" style="margin-top:-.8rem;">
                                <div class="card">
                                    <div class="card-header">
                                        Main Settings
                                    </div>
                                    <div class="card-body">
                                        <label for="">Status</label>
                                        <select name="status" id="" class="form-control">
                                            <option value="Enabled">Enabled</option>
                                            <option value="Enabled">Enabled</option>
                                        </select>
                
                                        <label for="">PROPERTY TYPE</label>
                                        <select name="property_type" id="" class="form-control">
                                            <option value="hotel" selected="selected">Hotel</option>
                                            <option value="villa">Villa</option>
                                            <option value="apartment">Apartment</option>
                                            <option value="resort">Resort</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        Markup
                                    </div>
                                    <div class="card-body">
                                        <label for="">B2C Markup (%)</label>
                                        <input id="b2c_markup" type="text" class="form-control @error('b2c_markup') is-invalid @enderror" name="b2c_markup" value="{{ old('b2c_markup') }}" autocomplete="b2c_markup" autofocus>
                
                                        @error('b2c_markup')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                
                                        <label for="">B2B Markup (%)</label>
                                        <input id="b2b_markup" type="text" class="form-control @error('b2b_markup') is-invalid @enderror" name="b2b_markup" value="{{ old('b2b_markup') }}" autocomplete="b2b_markup" autofocus>
                
                                        @error('b2b_markup')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                
                                        <label for="">B2E Markup (%)</label>
                                        <input id="b2e_markup" type="text" class="form-control @error('b2e_markup') is-invalid @enderror" name="b2e_markup" value="{{ old('b2e_markup') }}" autocomplete="b2e_markup" autofocus>
                
                                        @error('b2e_markup')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                
                                        <label for="">Service Fee (%)</label>
                                        <input id="service_fee" type="text" class="form-control @error('service_fee') is-invalid @enderror" name="service_fee" value="{{ old('service_fee') }}" autocomplete="service_fee" autofocus>
                
                                        @error('service_fee')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        
                                        <div class="row">
                                            <div class="col-md-7">
                                                <label for="">Vat Tax</label>
                                                <select name="tax_type" id="" class="form-control">
                                                    <option value="Fixed">Fixed</option>
                                                    <option value="Percentage">Percentage</option>
                                                </select>
                                            </div>
                                            <div class="col-md-5">
                                                <input id="tax_value" placeholder="Value" style="margin-top:2.4rem;" type="text" class="form-control @error('tax_value') is-invalid @enderror" name="tax_value" value="{{ old('tax_value') }}" autocomplete="tax_value" autofocus>
                                            </div>
                                        </div>
                                        
                
                
                
                                    </div>
                                </div>
                
                            </div>
                        </div>
                
                        <div class="row">
                            <div class="col-md-12 text-right mt-3">
                                <button class="btn btn-primary" id="submitForm_hotel_name_New" type="submit">Submit</button>
                                <!--<button type="submit" class="btn btn-primary">Submit</button>-->
                            </div>
                        </div>
                        
                    </form>
                </div>
                
                <div id="form_submit_data" style="display:none"></div>
                
            </div>
        </div>
    </div>
</div>

<div id="hotel-name-new-modal1" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Add Hotel Name</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form class="ps-3 pe-3" action="#">
                    
                    <div class="row">
                        <div class="col-md-8" style="padding: 15px;">
                            <label for="">PROPERTY TITLE</label>
                            <input id="property_name" type="text" class="form-control @error('property_name') is-invalid @enderror" name="property_name" value="{{ old('property_name') }}" autocomplete="property_name" autofocus>

                            @error('property_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-4" style="padding: 15px;">
                            <label for="">PROPERTY IMAGE</label>
                            <input id="property_img" type="file" class="form-control @error('property_img') is-invalid @enderror" name="property_img" value="{{ old('property_img') }}" autocomplete="property_img" autofocus>

                            @error('property_img')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div> 
                        <div class="col-md-12" style="padding: 15px;">
                            <label for="">PROPERTY DESCRIPTION</label>
                            <textarea name="property_desc" row="5" class="form-control @error('property_desc') is-invalid @enderror"  value="{{ old('property_desc') }}"  autocomplete="property_desc" placeholder="Enter ga message"></textarea>

                            @error('property_desc')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-12" style="padding: 15px;">
                            <label for="">GOOGLE MAP URL</label>
                            <input id="property_google_map" type="text" class="form-control @error('property_google_map') is-invalid @enderror" name="property_google_map" value="{{ old('property_google_map') }}" autocomplete="property_google_map" autofocus>

                            @error('property_google_map')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6" style="padding: 15px;">
                            <label for="">LATITUDE</label>
                            <input id="latitude" type="text" class="form-control @error('latitude') is-invalid @enderror" name="latitude" value="{{ old('latitude') }}" autocomplete="latitude" autofocus>

                            @error('latitude')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6" style="padding: 15px;">
                            <label for="">LONGITUDE</label>
                            <input id="longitude" type="text" class="form-control @error('longitude') is-invalid @enderror" name="longitude" value="{{ old('longitude') }}" autocomplete="longitude" autofocus>

                            @error('longitude')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6" style="padding: 15px;">
                            <label for="">PRICE TYPE</label>
                            <select name="price_type" id="" class="form-control">
                                <option value="Budget">Budget</option>
                                <option value="Economy">Economy</option>
                                <option value="Moderate">Moderate</option>
                                <option value="Deluxe">Deluxe</option>
                                <option value="Premium">Premium</option>
                            </select>
                        </div>
                        <div class="col-md-6" style="padding: 15px;">
                            <label for="">PRICE STAR</label>
                            <select name="star_type" id="" class="form-control">
                                <option value="1">1 Star</option>
                                <option value="2">2 Star</option>
                                <option value="3">3 Star</option>
                                <option value="4">4 Star</option>
                                <option value="5">5 Star</option>
                            </select>
                        </div>
                        <div class="col-md-6" style="padding: 15px;">
                            <label for="">COUNTRY</label>
                            <select name="property_country" onchange="selectCities()" id="property_country" class="form-control">
                                @foreach($all_countries as $country_res)
                                    <option value="{{ $country_res->id }}">{{ $country_res->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6" style="padding: 15px;">
                            <label for="">City</label>
                            <select name="property_city" id="property_city" class="form-control">
                               
                            </select>
                        </div>  
                    </div>
                    
                    <div class="mb-3 text-center">
                        <button class="btn btn-primary" id="submitForm_hotel_name1" type="submit">Submit</button>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>

<div id="hotel-type-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
<div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Add Hotel Type</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form class="ps-3 pe-3" action="#">
                    <div class="mb-3">
                        <label for="username" class="form-label">Hotel Type</label>
                        <input class="form-control" type="other_Hotel_Type" id="other_Hotel_Type" name="other_Hotel_Type" placeholder="">
                    </div>
                    <div class="mb-3 text-center">
                        <button class="btn btn-primary" id="submitForm_hotel_type" type="submit">Submit</button>
                    </div>

                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="pickup-location-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
<div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Add Pickup Location</h4>
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
                        <label for="username" class="form-label">Pickup Location</label>
                        <input class="form-control" type="pickup_location" id="pickup_location" name="pickup_location" placeholder="">
                    </div>

                   

                  

                    

                    <div class="mb-3 text-center">
                        <button class="btn btn-primary" id="submitForm_PUL" type="submit">Submit</button>
                    </div>

                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="dropof-location-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
<div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Add Dropof Location</h4>
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
                        <label for="username" class="form-label">Dropof Location</label>
                        <input class="form-control" type="dropof_location" id="dropof_location" name="dropof_location" placeholder="">
                    </div>

                   

                  

                    

                    <div class="mb-3 text-center">
                        <button class="btn btn-primary" id="submitForm_DOL" type="submit">Submit</button>
                    </div>

                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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
</div><!-- /.modal -->

<div id="add-facilities-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Add Facilities</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="https://client.synchronousdigital.com/super_admin/submit_attributes" method="post" enctype="multipart/form-data">
                @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label">Facilities Name</label>
                        <input class="form-control" type="title" id="title" name="title" placeholder="">
                    </div>
                    <div class="mb-3 text-center">
                        <button class="btn btn-primary" id="" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
 
<div class="card">
    <div class="card-body">
        <h4 class="header-title">Create Invoice</h4>
        @if($errors->any())
        <h4>{{$errors->first()}}</h4>
        @endif
        <!--<div class="tab-content">-->
            <form action="{{URL::to('request_invoice_confirmed_submit',[$get_invoice->id])}}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf
                <!--<div class="tab-pane show active" id="justified-tabs-preview">-->
                <div id="progressbarwizard">
                    <!--<ul class="nav nav-pills bg-nav-pills nav-justified mb-3">-->
                    <ul class="nav nav-pills nav-justified form-wizard-header mb-3" style="margin-right: 1px;margin-left: 1px;">
                        <li class="nav-item">
                            <a href="#home1" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                <i class="mdi mdi-home-variant d-md-none d-block"></i>
                                <span class="d-none d-md-block">Invoice Details</span>
                            </a>
                        </li>
                        @if(isset($get_invoice->accomodation_details) || isset($get_invoice->accomodation_details))
                        <li class="nav-item accomodation_tab">
                            <!--<a href="#profile1" data-bs-toggle="tab" aria-expanded="true" class="nav-link rounded-0">-->
                            <a href="#profile1" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                <i class="mdi mdi-account-circle d-md-none d-block"></i>
                                <span class="d-none d-md-block">Accomodation</span>
                            </a>
                        </li>
                        @endif
                        @if($get_invoice->flights_details_more != null)
                        <li class="nav-item flights_tab">
                            <!--<a href="#settings1" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0">-->
                            <a href="#settings1" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                <i class="mdi mdi-settings-outline d-md-none d-block"></i>
                                <span class="d-none d-md-block">Flights Details</span>
                            </a>
                        </li>
                         @endif
                           @if($get_invoice->visa_fee != null)
                        <li class="nav-item visa_tab">
                            <!--<a href="#visa_details" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0">-->
                            <a href="#visa_details" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                <i class="mdi mdi-settings-outline d-md-none d-block"></i>
                                <span class="d-none d-md-block">Visa Details</span>
                            </a>
                        </li>
                        @endif
                         @if($get_invoice->transportation_details_more != null)
                        <li class="nav-item transportation_tab">
                            <!--<a href="#trans_details_1" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0">-->
                            <a href="#trans_details_1" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                <i class="mdi mdi-settings-outline d-md-none d-block"></i>
                                <span class="d-none d-md-block">Transportation</span>
                            </a>
                        </li>
                          @endif
                        <li class="nav-item" style="display:none;">
                            <!--<a href="#Itinerary_details" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0">-->
                            <a  href="#Itinerary_details" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                <i class="mdi mdi-settings-outline d-md-none d-block"></i>
                                <span class="d-none d-md-block">Itinerary</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <!--<a href="#Extras_details" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0">-->
                            <a href="#Extras_details" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                <i class="mdi mdi-settings-outline d-md-none d-block"></i>
                                <span class="d-none d-md-block">Costing</span>
                            </a>
                        </li>
                    </ul>
                    
                    <div class="tab-content">

                        <div class="tab-pane  show active" id="home1">
                            <div class="row">
                                
                                <div class="col-xl-3">
                                    <div class="mb-3">
                                        <div id="tooltip-container">
                                            <label for="simpleinput" class="form-label simpleinput">Services</label>
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                                data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Enter the package name">
                                            </i>
                                        </div>
                                        <!--<input type="text" id="simpleinput" name="title" class="form-control titleP" >-->
                                       <select class="form-control  external_packages" onchange="selectServices()" data-toggle="select2" multiple="multiple" id="select_services" name="services[]">
                                            <option  value="1">All Services</option>
                                            <option <?php if('accomodation_tab' == 'accomodation_tab') echo "selected" ?> value="accomodation_tab">Accomodation</option>
                                            <option value="flights_tab">Flights Details</option>
                                            <option value="visa_tab">Visa Details</option>
                                            <option value="transportation_tab">Transportation</option>
                                            
                                        
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-xl-2">
                                    <div class="mb-3">
                                        <div id="tooltip-container">
                                            <label for="simpleinput" class="form-label simpleinput">Select Agent</label>
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                                data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Select the Agent Name">
                                            </i>
                                        </div>
                                       <select class="form-control" id="agent_Name" name="agent_Name">
                                           <option value="">Choose...</option>
                                            @if(isset($Agents_detail) && $Agents_detail !== null && $Agents_detail !== '')
                                                @foreach($Agents_detail as $Agents_details)
                                                    <option <?php if($get_invoice->agent_Name == $Agents_details->id) echo "selected" ?> value="{{ $Agents_details->agent_Name }}">{{ $Agents_details->agent_Name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-xl-2">
                                    <div class="mb-3">
                                        <div id="tooltip-container">
                                            <label for="simpleinput" class="form-label">Currency Symbol</label>
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                                data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Currency Symbol will be auto-selected for your account">
                                            </i>
                                        </div>
                                            <input readonly value="<?php echo $currency; ?>" name="currency_symbol" class="form-control currency_symbol">
                                    </div>
                                </div>
                                
                                <div class="col-xl-2">
                                    <div class="mb-3">
                                        <div id="tooltip-container">
                                            <label for="simpleinput" class="form-label no_of_pax_days">No Of Pax</label>
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                                data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="No Of Pax for this package">
                                            </i>
                                        </div>
                                        <!--<input type="text" id="no_of_pax_days" name="no_of_pax_days" class="form-control no_of_pax_days">-->
                                        <input type="number" name="no_of_pax_days" value="1" min="1" class="form-control no_of_pax_days" id="no_of_pax_days" required>
                                        <input type="number" id="no_of_pax_prev" hidden value="1">
                                        <div class="invalid-feedback">
                                            This Field is Required
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3">
                                <div class="mb-3">
                                    <div id="tooltip-container">
                                        <label for="simpleinput" class="form-label">Currency Conversion</label>
                                        <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                            data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Currency Convirsion">
                                        </i>
                                    </div>
                                
                                            <select class="form-control" name="currency_conversion" id="currency_conversion">
                                                <option value="">Select Currency Conversion</option>
                                                @foreach($mange_currencies as $mange_currencies)
                                                <option <?php if($get_invoice->currency_conversion == 'SAR TO  GBP') echo "selected"; ?> attr_conversion_type="{{$mange_currencies->conversion_type}}" value="{{$mange_currencies->purchase_currency}} TO  {{$mange_currencies->sale_currency}}">{{$mange_currencies->purchase_currency}} TO  {{$mange_currencies->sale_currency}}</option>
                                                @endforeach
                                            </select>
                                            
                                            <input type="hidden" id="select_exchange_type"  name="conversion_type" value="" > 
                                </div>
                                </div>
                                <div class="col-xl-12">
                                    <div class="mb-3">
                                        <h6>Lead Passenger Detials</h6>
                                        <div class="row" style="border: 1px solid #ebebeb;padding: 1rem;border-radius: 6px;">
                                            <div class="col-xl-6">
                                                <div class="mb-3">
                                                    <label for="simpleinput" class="form-label no_of_pax_days">First Name</label>
                                                    <input type="text" name="lead_fname" class="form-control" required value="{{$get_invoice->f_name}}">
                                                </div>
                                            </div>
                                            
                                             <div class="col-xl-6">
                                                <div class="mb-3">
                                                    <label for="simpleinput" class="form-label no_of_pax_days">Last Name</label>
                                                    <input type="text" name="lead_lname" class="form-control" required value="{{$get_invoice->middle_name}}">
                                                </div>
                                            </div>
                                            
                                                    
                                            <div class="col-xl-4">
                                                <div class="mb-3">
                                                    <label for="simpleinput" class="form-label no_of_pax_days">Select Nationality</label>
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
                                
                                <div class="col-xl-4">
                                    <div class="mb-3">
                                        <div id="tooltip-container">
                                            <label for="simpleinput" class="form-label start_date">Arrival Date</label>
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                                data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Select start date of package">
                                            </i>
                                        </div>
                                        
                                        <input type="" name="start_date" class="form-control date start_date" value="{{$get_invoice->start_date}}" id="start_date" required placeholder="yyyy/mm/dd" readonly>
                                        <div class="invalid-feedback">
                                            This Field is Required
                                        </div>
                                    </div>
                                 </div>
                                
                                <div class="col-xl-4">
                                    <div class="mb-3">
                                        
                                        <div id="tooltip-container">
                                            <label for="simpleinput" class="form-label end_date">Departure Date</label>
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                                data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Select end date of package">
                                            </i>
                                        </div>
                                        <input type="" name="end_date" class="form-control date end_date" value="{{$get_invoice->end_date}}" id="end_date" required placeholder="yyyy/mm/dd" readonly>
                                        <div class="invalid-feedback">
                                            This Field is Required
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-xl-4">
                                     <div class="mb-3">
                                        <!--<div id="tooltip-container">-->
                                            <label for="simpleinput" class="form-label">No Of Nights</label>
                                            <!--<i class="dripicons-information" style="font-size: 17px;" id="title_Icon"-->
                                                <!--data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="No Of Pax Info">-->
                                            <!--</i>-->
                                        <!--</div>-->
                                      
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
                                                            <option value="{{$categories->id}}" id="categoriesPV" required>{{$categories->title}}</option>
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
                                
                                <div class="col-xl-3">
                                    <div class="mb-3">
                                        <!--<div id="tooltip-container">-->
                                            <label for="simpleinput" class="form-label">Author</label>
                                            <!--<i class="dripicons-information" style="font-size: 17px;" id="title_Icon"-->
                                                <!--data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="No Of Pax Info">-->
                                            <!--</i>-->
                                        <!--</div>-->
                                      
                                      <select name="tour_author" id="" class="form-control tour_author">
                                      <option value="">Select Author</option>
                                          <option <?php if($get_invoice->tour_author == 'admin') echo "selected" ?>  value="admin">admin</option>
                                          <option <?php if($get_invoice->tour_author == 'admin1') echo "selected" ?> value="admin1">admin1</option>
                                      </select>
                                  </div>
                                  </div>
                                  <div class="col-xl-3">
                                    <div class="mb-3">
                                        <!--<div id="tooltip-container">-->
                                            <label for="simpleinput" class="form-label">Option Date</label>
                                           
                                       <input type="date" id="option_date" value="{{$get_invoice->option_date}}" name="option_date" class="form-control ">
                                      
                                  </div>
                                  </div>
                                  <div class="col-xl-3">
                                    <div class="mb-3">
                                       
                                            <label for="simpleinput" class="form-label">Reservation Number</label>
                                            
                                      
                                      <input type="text" id="reservation_number" value="{{$get_invoice->reservation_number}}" name="reservation_number" class="form-control ">
                                  </div>
                                  </div>
                                  <div class="col-xl-3">
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
                                        
                                <div class="mt-2 d-none" style="" id="">
                                    <a href="javascript:;" id="more_images" class="btn btn-info" style="float: right;">Add Gallery Images</a>
                                </div>
                                    
                            </div>
                            <!--<a id="save_Package" type="submit" class="btn btn-primary" name="submit">Save Package Deatils</a>-->    
                        </div>

                        <div class="tab-pane" id="profile1">
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
                                
                                <?php
                                $destination_details=$get_invoice->destination_details;
                                $destination_details=json_decode($destination_details);
                                $destination_details=$destination_details->destination_country;
                                
                                ?>
                                
                                @if(isset($get_invoice->destination_details))
                                <div class="row"  id="select_destination">
                                    <div class="col-xl-6">
                                            <label for="">COUNTRY</label>
                                        <select name="destination_country" onchange="selectCities()" id="property_country" class="form-control">
                                           @foreach($all_countries as $country_res)
                                           <option <?php if($destination_details == $country_res->id) echo "selected" ?>  value="{{ $country_res->id }}">{{ $country_res->name}}</option>
                                           @endforeach
                                        </select>
                                    </div>
                                    
                                    <!--<div class="col-xl-6" style="">-->
                                    <!--    <label for="">City</label>-->
                                    <!--    <select name="destination_city[]" id="property_city" class="select2 form-control select2-multiple city_slc property_city_length" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">-->
                                    <!--    </select>-->
                                    <!--</div>-->
                                    
                                    <div class="col-xl-6">
                                        <label for="">How Many Hotel You Want To Add?</label>
                                        <input type="number" name="hotel_count" value="{{$get_invoice->hotel_count}}" id="city_No" class="form-control" placeholder="Select...">
                                    </div>
                                    
                                    <input type="hidden" name="tour_location_city" id="tour_location_city" value=""/>
                                    <input type="hidden" name="packages_get_city" id="packages_get_city" value=""/>
                                    <div id="append_destination"></div>
                                </div>
                                @endif
                            </div>
                            
                            <div class="row">
                                <div class="col-xl-12 mt-2">
                                    <div class="mb-3">
                                        <div class="row">
                                            <!--<div class="col-xl-1">-->
                                            <!--    <input type="checkbox" id="accomodation" data-switch="bool"/>-->
                                            <!--    <label for="accomodation" data-on-label="On" data-off-label="Off"></label>-->
                                            <!--</div>-->
                                        <!--<div class="col-xl-3">-->
                                        <!--    <div class="mt-2">-->
                                        <!--        <a href="javascript:;" id="add_hotel_accomodation" class="btn btn-info"> -->
                                        <!--            + Add Hotels -->
                                        <!--        </a>-->
                                        <!--    </div>-->
                                        <!--</div>-->
                                        <!--<div class="col-xl-3">-->
                                        <!--    Accomodation-->
                                        <!--    <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Select Accomodation"></i>-->
                                        <!--</div>-->
                                    </div>
                                </div>
                            </div>
                            
                            <div id="append_accomodation">
                                <?php
                                $accomodation_details=$get_invoice->accomodation_details;
                                $accomodation_details=json_decode($accomodation_details);
                                //print_r($accomodation_details);die();
                                
                                ?>
                                @if($get_invoice->accomodation_details != null)
                                
                            <?php
                            $count_hotel=1;
                            foreach($accomodation_details as $details)
                            {
                            
                            ?>
                                <div class="mb-2" style="border:1px solid #ced4da;padding: 20px 20px 20px 20px;">
                            <h4>
                                City #{{$count_hotel}} {{$details->hotel_city_name}})
                                <i class="dripicons-information" style="font-size: 17px;" id="title_Icon_${i}"
                                    data-bs-toggle="tooltip" data-bs-placement="right" title="Fill all the information of City {{$details->hotel_city_name}}">
                                </i>
                            </h4> 
                <div class="row">
                    
                    <input type="hidden" name="hotel_city_name[]" id="hotel_city_name" value="{{$details->hotel_city_name}}"/>
                <div class="col-xl-3">
                    <label for="">Hotel Name</label>
                    <div class="input-group">
                       
                        <input type="text" id="acc_hotel_name_{{$count_hotel}}" name="acc_hotel_name[]" value="{{$details->acc_hotel_name}}" class="form-control other_Hotel_Name acc_hotel_name_class_{{$count_hotel}}" required>
                        
                        <span title="Add Hotel Name" class="input-group-btn input-group-append">
                            <button class="btn btn-primary bootstrap-touchspin-up" data-bs-toggle="modal" data-bs-target="#hotel-name-modal" type="button">+</button>
                        </span>
                    </div>
                </div>
                <div class="col-xl-3"><label for="">Check In</label><input type="date" value="{{$details->acc_check_in}}" id="makkah_accomodation_check_in_{{$count_hotel}}" name="acc_check_in[]" class="form-control date makkah_accomodation_check_in_class_{{$count_hotel}}">
                </div><div class="col-xl-3"><label for="">Check Out</label><input type="date" value="{{$details->acc_check_out}}" id="makkah_accomodation_check_out_date_{{$count_hotel}}"  name="acc_check_out[]" onchange="makkah_accomodation_check_out({{$count_hotel}})"  class="form-control date makkah_accomodation_check_out_date_class_{{$count_hotel}}"></div>
                <div class="col-xl-3"><label for="">No Of Nights</label><input type="text" value="{{$details->acc_no_of_nightst}}" id="acomodation_nights_{{$count_hotel}}" name="acc_no_of_nightst[]" class="form-control acomodation_nights_class_{{$count_hotel}}"></div>
                
                <div class="col-xl-3"><label for="">Room Type</label>
                    <div class="input-group">
                        <select onchange="hotel_type_fun({{$count_hotel}})" name="acc_type[]" id="hotel_type_{{$count_hotel}}" class="form-control other_Hotel_Type hotel_type_class_{{$count_hotel}}"  data-placeholder="Choose ...">
                            <option value="">Choose ...</option>
                            <option <?php if($details->acc_type == 'Quad') echo 'selected'; ?> attr="4" value="Quad">Quad</option>
                            <option <?php if($details->acc_type == 'Triple') echo 'selected'; ?> attr="3" value="Triple">Triple</option>
                            <option <?php if($details->acc_type == 'Double') echo 'selected'; ?> attr="2" value="Double">Double</option>
                        </select>
                        <span title="Add Hotel Type" class="input-group-btn input-group-append">
                            <button class="btn btn-primary bootstrap-touchspin-up" data-bs-toggle="modal" data-bs-target="#hotel-type-modal" type="button">+</button>
                        </span>
                    </div>
                    
                </div>
                <div class="col-xl-3"><label for="">Quantity</label><input value="{{$details->acc_qty}}" type="text" id="simpleinput" name="acc_qty[]" class="form-control acc_qty_class_{{$count_hotel}}"></div>
                
                <div class="col-xl-3">
                <label for="">Pax</label>
                <input type="text" id="simpleinput" value="{{$details->acc_pax}}"  name="acc_pax[]" class="form-control acc_pax_class_{{$count_hotel}}" readonly>
                </div>
                <div class="col-xl-3">
                    <?php
                    $hotel_meal_type=$get_invoice->hotel_meal_type;
                    $hotel_meal_type=json_decode($hotel_meal_type);
                    // print_r($hotel_meal_type);die();
                    ?>
                 <label for="">Meal Type</label>
                  <select name="hotel_meal_type[]" id="hotel_meal_types" class="form-control" data-placeholder="Choose ...">
                                                                        <option <?php if($hotel_meal_type[0] == 'Room only') echo 'selected'; ?> value="Room only">Room only</option>
                                                                        <option <?php if($hotel_meal_type[0] == 'Breakfast') echo 'selected'; ?> value="Breakfast">Breakfast</option>
                                                                        <option <?php if($hotel_meal_type[0] == 'Lunch') echo 'selected'; ?> value="Lunch">Lunch</option>
                                                                        <option <?php if($hotel_meal_type[0] == 'Dinner') echo 'selected'; ?> value="Dinner">Dinner</option>
                                                                     </select>
                                                            </div>
                                                            
                                                            
                                            
                                            
                                               <div class="col-xl-3">
                                    <label for="">Additional Meal Type</label>
                                    <select name="additional_hotel_meal_type[]" onchange="additional_hotel_meal_type({{$count_hotel}})" id="additional_hotel_meal_type_{{$count_hotel}}" class="form-control" data-placeholder="Choose ...">
                                    <option value="">Choose ...</option>
                                    <option <?php if($details->additional_hotel_meal_type == 'Room only') echo 'selected'; ?>  value="Room only">Room only</option>
                                    <option <?php if($details->additional_hotel_meal_type == 'Breakfast') echo 'selected'; ?> value="Breakfast">Breakfast</option>
                                    <option <?php if($details->additional_hotel_meal_type == 'Lunch') echo 'selected'; ?> value="Lunch">Lunch</option>
                                    <option <?php if($details->additional_hotel_meal_type == 'Dinner') echo 'selected'; ?> value="Dinner">Dinner</option>
                                </select>
                            </div>
                                            
                                            
                                                                                     
                                               
                                            <div  class="col-xl-6" style="" id="NotIncluded_with_price">
                                                <label for="">Price</label>
                                                <div class="input-group">
                                                <span class="input-group-btn input-group-append">
                                                <a id="" class="btn btn-primary bootstrap-touchspin-up currency_value1"></a>
                                            </span>
                                                <input type="text" value="{{$details->NotIncluded_with_price}}" id="NotIncluded_with_price_meal" name="NotIncluded_with_price" class="form-control">
                                                   </div>         
                                                    </div>        
                
                <h4 class="mt-4">Purchase Price</h4>
                                        
                                        <div class="col-xl-4">
                                        <label for="">Price Per Room/Night</label>
                                        <div class="input-group">
                                            <span class="input-group-btn input-group-append">
                                                <a id="" class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                                  
                                                </a>
                                            </span>
                                            <input type="text" id="makkah_acc_room_price_{{$count_hotel}}" onchange="makkah_acc_room_price_funs({{$count_hotel}})" value="{{$details->price_per_room_purchase}}" name="price_per_room_purchase[]" class="form-control">
                                        </div>
                        
                                        </div>
                                        
                                        
                                        
                                        <div class="col-xl-4">
                                        <label for="">Price Per Person/Night</label>
                                        <div class="input-group">
                                            <span class="input-group-btn input-group-append">
                                                <a id="" class="btn btn-primary bootstrap-touchspin-up currency_value2">
                                                 
                                                </a>
                                            </span>
                                            <input type="text" id="makkah_acc_price_{{$count_hotel}}" onchange="makkah_acc_price_funs({{$count_hotel}})" value="{{$details->acc_price_purchase}}" name="acc_price_purchase[]" class="form-control makkah_acc_price_class_{{$count_hotel}}">
                                        </div>
                        
                                        </div>
                                        
                                        
                                        
                                        
                                        
                                        
                                        <div class="col-xl-4"><label for="">Total Amount/Per Person</label>
                                         <div class="input-group">
                                            <span class="input-group-btn input-group-append">
                                                <a id="" class="btn btn-primary bootstrap-touchspin-up currency_value3">
                                                  
                                                </a>
                                            </span>
                                            <input readonly type="text"  id="makkah_acc_total_amount_{{$count_hotel}}" value="{{$details->acc_total_amount_purchase}}"  name="acc_total_amount_purchase[]" class="form-control makkah_acc_total_amount_class_{{$count_hotel}}">
                                        </div>
                                        </div>
                                        
                                        
                                        <div class="col-xl-6">
                                        <label for="">Exchange Rate</label>
                                        <div class="input-group">
                                            <span class="input-group-btn input-group-append">
                                                <a id="" class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                                  
                                                </a>
                                            </span>
                                            <input type="text" id="exchange_rate_price_funs_{{$count_hotel}}" value="{{$details->exchange_rate_price}}" onchange="exchange_rate_price_funs({{$count_hotel}})" value="" name="exchange_rate_price[]" class="form-control">
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
                                            <input type="text" id="price_per_room_exchange_rate_{{$count_hotel}}" value="{{$details->price_per_room_sale}}"   name="price_per_room_sale" class="form-control">
                                        </div>
                        
                                        </div>
                                        
                                        
                                        
                                        <div class="col-xl-4">
                                        <label for="">Price Per Person/Night</label>
                                        <div class="input-group">
                                            <span class="input-group-btn input-group-append">
                                                <a id="" class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                                  
                                                </a>
                                            </span>
                                            <input type="text" id="price_per_person_exchange_rate_{{$count_hotel}}" value="{{$details->acc_price}}"  name="acc_price[]" class="form-control makkah_acc_price_class_{{$count_hotel}}">
                                        </div>
                        
                                        </div>
                                        
                                        
                                        
                                        
                                        
                                        
                                        <div class="col-xl-4"><label for="">Total Amount/Per Person</label>
                                         <div class="input-group">
                                            <span class="input-group-btn input-group-append">
                                                <a id="" class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_3">
                                                  
                                                </a>
                                            </span>
                                            <input readonly type="text"  id="price_total_amout_exchange_rate_{{$count_hotel}}" value="{{$details->acc_total_amount}}" name="acc_total_amount[]" class="form-control">
                                        </div>
                                        </div>
               
                
                <div id="append_add_accomodation_{{$count_hotel}}"></div>
                
                
                
                  <div class="col-xl-12">
                     <div class="mb-3">
                     
                     
                     
                     
                      <label for="simpleinput" class="form-label">Room Amenities</label>
                      <textarea name="hotel_whats_included[]" class="form-control" id="" cols="10" rows="0"></textarea>
                      
                     </div>
                  </div>
                
                <div class="col-xl-12 d-none"><label for="">Image</label><input type="file"  id=""  name="accomodation_image{{$count_hotel}}[]" class="form-control" multiple></div>
                </div></div>
                <?php
                $count_hotel=$count_hotel+1;
                            }
                ?>
                @endif
                
                
                
                
                            </div>
                            <div id="append_accomodation1">
                                 <?php
                                $accomodation_details_more=$get_invoice->accomodation_details_more;
                                $accomodation_details_more=json_decode($accomodation_details_more);
                                
                                ?>
                                @if($get_invoice->accomodation_details_more != null)
                                
                            <?php
                            $count_hotel=1;
                            foreach($accomodation_details_more as $details)
                            {
                            
                            ?>
                                <div id="click_delete_{{$count_hotel}}" class="mb-2 mt-3" style="border:1px solid #ced4da;padding: 20px 20px 20px 20px;">
                            <input readonly type="hidden" id="acc_nights1_{{$count_hotel}}" value="${acc_nights_key}" class="form-control">
                        
                            <input type='hidden' name="more_hotel_city[]" value="{{$details->more_hotel_city}}" id="more_hotel_city{{$details->more_hotel_city}}"/>
                            <div class="row" style="padding:20px;"><div class="col-xl-3"><label for="">Room Type</label>
                                <div class="input-group">
                                            <select onchange="more_hotel_type_fun({{$count_hotel}})" name="more_acc_type[]" id="more_hotel_type_{{$count_hotel}}" class="form-control other_Hotel_Type more_hotel_type_class_{{$count_hotel}}"  data-placeholder="Choose ...">
                                                <option value="">Choose...</option>
                                                <option <?php if($details->more_acc_type == 'Quad') echo 'selected'; ?> attr="4" value="Quad">Quad</option>
                                                <option <?php if($details->more_acc_type == 'Triple') echo 'selected'; ?> attr="3" value="Triple">Triple</option>
                                                <option <?php if($details->more_acc_type == 'Double') echo 'selected'; ?> attr="2" value="Double">Double</option>
                                            </select>
                                            <span title="Add Hotel Type" class="input-group-btn input-group-append">
                                                <button class="btn btn-primary bootstrap-touchspin-up" data-bs-toggle="modal" data-bs-target="#hotel-type-modal" type="button">+</button>
                                            </span>
                                        </div>
                                
                            </div>
                            <div class="col-xl-3"><label for="">Quantity</label><input value="{{$details->more_acc_qty}}" onchange="more_acc_qty_class({{$count_hotel}})" type="text" id="simpleinput" name="more_acc_qty[]" class="form-control more_acc_qty_class_{{$count_hotel}}"></div>
                            <div class="col-xl-3"><label for="">Pax</label><input type="text" value="{{$details->more_acc_pax}}" id="simpleinput" name="more_acc_pax[]" class="form-control more_acc_pax_class_{{$count_hotel}}" readonly></div>
                            
                            <div class="col-xl-3">
                                <label for="">Meal Type</label>
                                <select name="more_hotel_meal_type[]" id="hotel_meal_type_{{$count_hotel}}" class="form-control"  data-placeholder="Choose ...">
                                    <option value="">Choose ...</option>
                                    <option <?php if($details->more_hotel_meal_type == 'Room only') echo 'selected'; ?>  value="Room only">Room only</option>
                                    <option <?php if($details->more_hotel_meal_type == 'Breakfast') echo 'selected'; ?> value="Breakfast">Breakfast</option>
                                    <option <?php if($details->more_hotel_meal_type == 'Lunch') echo 'selected'; ?> value="Lunch">Lunch</option>
                                    <option <?php if($details->more_hotel_meal_type == 'Dinner') echo 'selected'; ?> value="Dinner">Dinner</option>
                                </select>
                            </div>
                            
                            
                            <div class="col-xl-3">
                                <label for="">Additional Meal Type</label>
                                <select name="more_additional_hotel_meal_type[]" onchange="more_additional_hotel_meal_type({{$count_hotel}})" id="more_additional_hotel_meal_type_{{$count_hotel}}" class="form-control"  data-placeholder="Choose ...">
                                    <option value="">Choose ...</option>
                                    <option <?php if($details->more_additional_hotel_meal_type == 'Room only') echo 'selected'; ?> value="Room only">Room only</option>
                                    <option <?php if($details->more_additional_hotel_meal_type == 'Breakfast') echo 'selected'; ?> value="Breakfast">Breakfast</option>
                                    <option <?php if($details->more_additional_hotel_meal_type == 'Lunch') echo 'selected'; ?> value="Lunch">Lunch</option>
                                    <option <?php if($details->more_additional_hotel_meal_type == 'Dinner') echo 'selected'; ?> value="Dinner">Dinner</option>
                                </select>
                            </div>
                                            
                                               
                                         
                                                                                       
                                               
                                            <div  class="col-xl-6" style="margin-top: 2.2rem;" id="more_NotIncluded_with_price_{{$count_hotel}}">
                                                <label for="">Price</label>
                                                <div class="input-group">
                                                <span class="input-group-btn input-group-append">
                                                <a id="" class="btn btn-primary bootstrap-touchspin-up currency_value1"></a>
                                            </span>
                                                <input type="text" onchange="more_NotIncluded_meal_option({{$count_hotel}})"  id="more_NotIncluded_get_price_meals_{{$count_hotel}}" value="{{$details->more_NotIncluded_with_price ?? ''}}" name="more_NotIncluded_with_price_{{$count_hotel}}" class="form-control">
                                                   </div>         
                                                    </div>
                            
                            
                            
                            <h4  class="mt-4">Purchase Price</h4 >
                            
                             <div class="col-xl-4" style="display:none">
                            <label for="">Price Per Room/Night</label>
                             <div class="input-group">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                              
                                            </a>
                                        </span>
                                <input type="text"  onchange="more_makkah_acc_room_price_funs({{$count_hotel}},{{$count_hotel}})" value="{{$details->more_price_per_room_purchase}}" id="more_makkah_acc_room_price_funs_{{$count_hotel}}" name="more_price_per_room_purchase[]" class="form-control">
                            </div>
                        
                            </div>
                            
                            <div class="col-xl-4" >
                            <label for="">Price Per Person/Night</label>
                             <div class="input-group">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                              
                                            </a>
                                        </span>
                                <input type="text" onchange="more_acc_price({{$count_hotel}})" id="more_acc_price_get_{{$count_hotel}}" value="{{$details->more_acc_price_purchase}}" name="more_acc_price_purchase[]" class="form-control">
                            </div>
                        
                            </div>
                            <div class="col-xl-4" >
                            <label for="">Total Amount/Per Person</label>
                             <div class="input-group">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                               
                                            </a>
                                        </span>
                                <input readonly type="text" id="more_acc_total_amount_{{$count_hotel}}" value="{{$details->more_acc_total_amount_purchase}}" name="more_acc_total_amount_purchase[]" class="form-control"></div>
                            </div>
                            
                            
                            <div class="col-xl-6" >
                            <label for="">Exchange Rate</label>
                             <div class="input-group">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                               
                                            </a>
                                        </span>
                                <input type="text" id="more_exchange_rate_price_funs_{{$count_hotel}}" onchange="more_exchange_rate_price_funs({{$count_hotel}})" value="{{$details->more_exchange_rate_price}}" name="more_exchange_rate_price[]" class="form-control"></div>
                            </div>
                            <div class="col-xl-6" >
                            </div>
                            
                            
                            <h4  class="mt-4">Sale Price</h4>
                            
                             <div class="col-xl-4" >
                            <label for="">Price Per Room/Night</label>
                             <div class="input-group">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">
                                              
                                            </a>
                                        </span>
                                <input type="text" id="more_price_per_room_exchange_rate_{{$count_hotel}}" value="{{$details->more_price_per_room_sale}}" name="more_price_per_room_sale[]" class="form-control">
                            </div>
                        
                            </div>
                            
                            <div class="col-xl-4">
                            <label for="">Price Per Person/Night5</label>
                             <div class="input-group">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">
                                              
                                            </a>
                                        </span>
                                <input type="text"  id="more_price_per_person_exchange_rate_{{$count_hotel}}" value="{{$details->more_acc_price}}" name="more_acc_price[]" class="form-control">
                            </div>
                        
                            </div>
                            <div class="col-xl-4">
                            <label for="">Total Amount/Per Person</label>
                             <div class="input-group">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">
                                              
                                            </a>
                                        </span>
                                <input readonly type="text" id="more_price_total_amout_exchange_rate_{{$count_hotel}}" value="{{$details->more_acc_total_amount}}" name="more_acc_total_amount[]" class="form-control"></div>
                            </div>
                            
                           
                            
                            
                            </div></div>
                            
                            <?php
                $count_hotel=$count_hotel+1;
                            }
                ?>
                             @endif
                                
                            </div>
                            
                            </div>                                                      
                            <!--<a id="save_Accomodation" class="btn btn-primary" name="submit">Save Accomodation</a>-->   
                        </div>

                        <div class="tab-pane" id="settings1">
                      
                            <div class="col-xl-12">
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
                            <!-- flights -->
                            <div class="row" style="display:none;" id="select_flights_inc">
                                
                                <div class="col-xl-6" style="padding-left: 24px;">
                                    <label for="">Flight Type</label>
                                    <select name="flight_type" id="flights_type" class="form-control">
                                        <option attr="select" selected>Select Flight Type</option>
                                         <option attr="Direct" value="Direct">Direct</option>
                                          <option attr="Indirect" value="Indirect">Indirect</option>
                                    </select>
                                </div>
    
                                <div class="col-xl-3" id="flights_type_connected"></div>
    
                                <div class="col-xl-3"></div>
                                
                                <div class="container Flight_section">
                                    <h3 style="padding: 12px">Departure Details : </h3>
                                    <div class="row" style="padding: 12px">
                                        <div class="col-xl-4">
                                            <label for="">Departure Airport</label>
                                            <input name="departure_airport_code" id="departure_airport_code" class="form-control" autocomplete="off" placeholder="Enter Departure Location">
                                        </div>
                                        <div class="col-xl-1" style="margin-top: 25px;text-align: center;">
                                            <label for=""></label>
                                            <span id="Change_Location" class='bi bi-arrow-left-right' style="font-size: 23px;"></span>
                                        </div>
                                        <div class="col-xl-4">
                                            <label for="">Arrival Airport</label>
                                            <input name="arrival_airport_code" id="arrival_airport_code" class="form-control" autocomplete="off" placeholder="Enter Arrival Location">
                                            
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="">Airline Name</label>
                                            <input type="text" id="other_Airline_Name2" name="other_Airline_Name2" class="form-control other_airline_Name1">
                                            <!--<div class="input-group">-->
                                            <!--    <select type="text" id="other_Airline_Name2" name="other_Airline_Name2" class="form-control other_airline_Name1">-->
                                                
                                            <!--    </select>-->
                                            <!--    <span title="Add Pickup Location" class="input-group-btn input-group-append">-->
                                            <!--        <button class="btn btn-primary bootstrap-touchspin-up" data-bs-toggle="modal" data-bs-target="#flights-Airline-Name" type="button">+</button>-->
                                            <!--    </span>-->
                                            <!--</div>-->
                                        </div>
                                        <div class="col-xl-3" style="margin-top: 15px;">
                                            <label for="">Class Type</label>
                                            <select  name="departure_Flight_Type" id="departure_Flight_Type" class="form-control">
                                                <option value="">Select Flight Type</option>
                                                <option value="Bussiness">Bussiness</option>
                                                <option value="Economy">Economy</option>
                                                <option value="Standard">Standard</option>
                                            </select>
                                        </div>
                                        <div class="col-xl-3" style="margin-top: 15px;">
                                            <label for="">Flight Number</label>
                                            <input type="text" id="simpleinput" name="departure_flight_number" class="form-control" placeholder="Enter Flight Number">
                                        </div>
                                        <div class="col-xl-3" style="margin-top: 15px;">
                                            <label for="">Departure Date and Time</label>
                                            <input type="datetime-local" id="simpleinput" name="departure_time" class="form-control departure_time1" value="" >
                                        </div>
                                        <div class="col-xl-3" style="margin-top: 15px;">
                                            <label for="">Arrival Date and Time</label>
                                            <input type="datetime-local" id="simpleinput" name="arrival_time" class="form-control arrival_time1" value="" >
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="container" style="display:none" id="total_Time_Div">
                                    <div class="row" style="margin-left: 320px">
                                        <div class="col-sm-3">
                                            <h3 style="width: 125px;margin-top: 25px;float:right" id="stop_No">Direct :</h3>
                                        </div>
                                        <div class="col-sm-3">
                                             <label for="">Transit Time</label>
                                            <input type="text" id="total_Time" name="total_Time" class="form-control total_Time1" readonly style="width: 170px;" value="">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="container Flight_section_append"></div>
                                
                                <div class="container return_Flight_section">
                                    <h3 style="padding: 12px">Return Details : </h3>
                                    <div class="row" style="padding: 12px">
                                        <div class="col-xl-4">
                                            <label for="">Departure Airport</label>
                                            <input name="return_departure_airport_code" id="return_departure_airport_code" class="form-control" autocomplete="off" placeholder="Enter Return Location">
                                        </div>
                                        <div class="col-xl-1" style="margin-top: 25px;text-align: center;">
                                            <label for=""></label>
                                            <span id="return_Change_Location" class='bi bi-arrow-left-right' style="font-size: 23px;"></span>
                                        </div>
                                        <div class="col-xl-4">
                                            <label for="">Arrival Airport</label>
                                            <input name="return_arrival_airport_code" id="return_arrival_airport_code" class="form-control" autocomplete="off" placeholder="Enter Return Location">
                                            
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="">Airline Name</label>
                                            <input type="text" id="return_other_Airline_Name2" name="return_other_Airline_Name2" class="form-control other_airline_Name1">
                                            <!--<div class="input-group">-->
                                            <!--    <select type="text" id="return_other_Airline_Name2" name="return_other_Airline_Name2" class="form-control other_airline_Name1">-->
                                                
                                            <!--    </select>-->
                                            <!--    <span title="Add Pickup Location" class="input-group-btn input-group-append">-->
                                            <!--        <button class="btn btn-primary bootstrap-touchspin-up" data-bs-toggle="modal" data-bs-target="#flights-Airline-Name" type="button">+</button>-->
                                            <!--    </span>-->
                                            <!--</div>-->
                                        </div>
                                        <div class="col-xl-3" style="margin-top: 15px;">
                                            <label for="">Class Type</label>
                                            <select  name="return_departure_Flight_Type" id="return_departure_Flight_Type" class="form-control">
                                                <option value="">Select Flight Type</option>
                                                <option value="Bussiness">Bussiness</option>
                                                <option value="Economy">Economy</option>
                                                <option value="Standard">Standard</option>
                                            </select>
                                        </div>
                                        <div class="col-xl-3" style="margin-top: 15px;">
                                            <label for="">Flight Number</label>
                                            <input type="text" id="simpleinput" name="return_departure_flight_number" class="form-control" placeholder="Enter Flight Number">
                                        </div>
                                        <div class="col-xl-3" style="margin-top: 15px;">
                                            <label for="">Departure Date and Time</label>
                                            <input type="datetime-local" id="simpleinput" name="return_departure_time" class="form-control return_departure_time1" value="" >
                                        </div>
                                        <div class="col-xl-3" style="margin-top: 15px;">
                                            <label for="">Arrival Date and Time</label>
                                            <input type="datetime-local" id="simpleinput" name="return_arrival_time" class="form-control return_arrival_time1" value="" >
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="container" style="display:none" id="return_total_Time_Div">
                                    <div class="row" style="margin-left: 320px">
                                        <div class="col-sm-3">
                                            <h3 style="width: 162px;margin-top: 25px;float:right" id="return_stop_No">Return Direct :</h3>
                                        </div>
                                        <div class="col-sm-3">
                                             <label for="">Transit Time</label>
                                            <input type="text" id="return_total_Time" name="return_total_Time" class="form-control return_total_Time1" readonly style="width: 170px;" value="">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="container return_Flight_section_append"></div>
                                
                                <div class="col-xl-6" style="padding-left: 24px;">
                                    <label for="">Price Per Person</label>
                                    <div class="input-group">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                        
                                            </a>
                                        </span>
                                        <input type="text" id="flights_per_person_price" name="flights_per_person_price" class="form-control">
                                    </div>
                                </div>
                                
                                <div class="col-xl-12 mt-3" style="display:none" id="text_editer">
                                    <label for="">Indirect Flight Duration and Details</label>
                                    <textarea name="connected_flights_duration_details" class="form-control" cols="10" rows="10"></textarea>
                                </div>
                                
                                <div class="col-xl-12 mt-2" style="padding-left: 24px;">
                                    <label for="">Additional Flight Details</label>
                                    <textarea name="terms_and_conditions" class="form-control" cols="5" rows="5"></textarea>
                                </div>
                                
                                <div class="col-xl-12 mt-2 d-none" style="padding-left: 24px;">
                                    <label for="">image</label>
                                    <input type="file" id="" name="flights_image" class="form-control">
                                </div>
                                
                                <div id="append_flights"></div>
                                
                            </div>
                            <!-- END Flifhts -->    
                            <!--<a id="save_Flights" class="btn btn-primary" name="submit">Save Flights</a>-->
                           
                        </div>
                                                
                        <div class="tab-pane" id="visa_details">
                                                    
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
  
                                <div class="row mt-1 mb-3" style="display:none" id="select_visa_inc">
                                  
                                    <div class="col-xl-6" style="padding: 10px;">
                                        <label for="">Visa Type</label>
                                        <div class="input-group" id="timepicker-input-group1">
                                            <select name="visa_type" id="visa_type" class="form-control other_type"></select>
                                            <span title="Add Visa Type" class="input-group-btn input-group-append"><button class="btn btn-primary bootstrap-touchspin-up" data-bs-toggle="modal" data-bs-target="#signup-modal" type="button">+</button></span>
                                        </div>
                                     </div>
                                    
                                    <div class="col-xl-6" style="padding: 10px;">
                                        <label for="">Visa Fee</label>
                                        <div class="input-group">
                                            <span class="input-group-btn input-group-append">
                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value1" >
                                                   
                                                </a>
                                            </span>
                                            <input type="text" id="visa_fee" name="visa_fee" class="form-control">
                                        </div>
                                     </div>
                                    
                                    <div class="col-xl-12" style="padding: 10px;">
                                        <label for="">Visa Requirements:</label>
                                        <textarea name="visa_rules_regulations" class="form-control" cols="5" rows="5"></textarea>
                                     </div>
                                     
                                    <div class="col-xl-12 d-none" style="padding: 10px;">
                                        <label for="">Image:</label>
                                        <input type="file" id="" name="visa_image" class="form-control">
                                     </div>
                                </div>
                            </div>
                            <!--<a id="save_Visa" class="btn btn-primary" name="submit">Save Visa</a>-->
                            
                        </div> 
                                                    
                        <div class="tab-pane" id="trans_details_1">
                            <div class="row">                                       
                                <div class="tab-pane" id="trans_details_1">
                                    
                                    <div class="col-xl-12 mt-2">
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
                                     
                                    <div class="row" style="display:none;border:1px solid #ced4da;padding: 20px 20px 20px 20px;" id="select_transportation">
                                        <h3>Transportation Details :</h3>
                                        <div class="col-xl-3" style="padding: 10px;">
                                            <label for="">Pick-up Location</label>
                                            <input type="text" id="transportation_pick_up_location" name="transportation_pick_up_location" class="form-control pickup_location" value="">
                                            <!--<div class="input-group">-->
                                                <!--<select type="text" id="transportation_pick_up_location" name="transportation_pick_up_location" class="form-control pickup_location">-->
                                                
                                                <!--</select>-->
                                            <!--    <span title="Add Pickup Location" class="input-group-btn input-group-append">-->
                                            <!--        <button class="btn btn-primary bootstrap-touchspin-up" data-bs-toggle="modal" data-bs-target="#pickup-location-modal" type="button">+</button>-->
                                            <!--    </span>-->
                                            <!--</div>-->
                                        </div>
                                        <div class="col-xl-3" style="padding: 10px;">
                                        <label for="">Drop-off Location</label>
                                        <input type="text" id="transportation_drop_off_location" name="transportation_drop_off_location" class="form-control dropof_location">
                                            <!--<div class="input-group">-->
                                            <!--    <select type="text" id="transportation_drop_off_location" name="transportation_drop_off_location" class="form-control dropof_location">-->
                                                
                                            <!--    </select>-->
                                            <!--    <span title="Add DropOf Location" class="input-group-btn input-group-append">-->
                                            <!--        <button class="btn btn-primary bootstrap-touchspin-up" data-bs-toggle="modal" data-bs-target="#dropof-location-modal" type="button">+</button>-->
                                            <!--    </span>-->
                                            <!--</div>-->
                                        </div>
                                        <div class="col-xl-3" style="padding: 10px;">
                                        <label for="">Pick-up Date & Time</label>
                                        <input type="datetime-local" id="transportation_pick_up_date" name="transportation_pick_up_date" class="form-control">
                                        </div>
                                        <div class="col-xl-3" style="padding: 10px;">
                                        <label for="">Drop-of Date & Time</label>
                                        <input type="datetime-local" id="transportation_drop_of_date" name="transportation_drop_of_date" class="form-control">
                                        </div>
                                        <div class="col-xl-3" style="display:none" id="transportation_Time_Div">
                                        <label for="">Estimate Time</label>
                                        <input type="text" id="transportation_total_Time" name="transportation_total_Time" class="form-control transportation_total_Time1" readonly style="padding: 10px;" value="">
                                        </div>
                                        <div class="col-xl-3" style="padding: 10px;">
                                        <label for="">Select Trip Type</label>
                                        <select name="transportation_trip_type" id="slect_trip" class="form-control"  data-placeholder="Choose ...">
                                    
                                    <option value="One-Way">One-Way</option>
                                    <option value="Return">Return</option>
                                    <option value="All_Round">All Round</option>
                                    </select>
                                    </div>
                                        
                                        <div class="col-xl-3" style="padding: 10px;">
                                            <label for="">Vehicle Type</label>
                                            <select name="transportation_vehicle_type" id="" class="form-control"  data-placeholder="Choose ...">
                                                <option value="">Choose ...</option>
                                                <option value="Bus">Bus</option>
                                                <option value="Coach">Coach</option>
                                                <option value="Vain">Vain</option>
                                                <option value="Car">Car</option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-xl-3" style="padding: 10px;">
                                    <label for="">No Of Vehicle</label>
                                            <input type="text" id="transportation_no_of_vehicle" name="transportation_no_of_vehicle" class="form-control">
                                    </div>
                                        <div class="col-xl-3" style="padding: 10px;">
                                            <label for="">Price Per Vehicle</label>
                                            <div class="input-group">
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                                            
                                                                </a>
                                                            </span>
                                                <input type="text" id="transportation_price_per_vehicle" name="transportation_price_per_vehicle" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-xl-3" style="padding: 10px;">
                                    <label for="">Total Vehicle Price</label>
                                        <div class="input-group">
                                                        <span class="input-group-btn input-group-append">
                                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                                        
                                                            </a>
                                                        </span>
                                            <input type="text" id="transportation_vehicle_total_price" name="transportation_vehicle_total_price" class="form-control">
                                        </div>
                                    </div>
                                        <div class="col-xl-3" style="padding: 10px;">
                                    <label for="">Price Per Person</label>
                                        <div class="input-group">
                                                    <span class="input-group-btn input-group-append">
                                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                                    
                                                        </a>
                                                    </span>
                                            <input type="text" id="transportation_price_per_person" name="transportation_price_per_person" class="form-control">
                                        </div>
                                    
                                    </div>
                                
                                        <!--Return Transportation-->
                                        <div class="row" id="select_return" style="display:none;padding: 10px;">
                                            <h3>Return Details :</h3>
                                            <div class="col-xl-3" style="padding: 10px;">
                                                <label for="">Pick-up Location</label>
                                                <input type="text" id="return_transportation_pick_up_location" name="return_transportation_pick_up_location" class="form-control pickup_location">
                                                <!--<div class="input-group">-->
                                                    <!--<select type="text" id="return_transportation_pick_up_location" name="return_transportation_pick_up_location" class="form-control pickup_location">-->
                                                    
                                                    <!--</select>-->
                                                <!--    <span title="Add Pickup Location" class="input-group-btn input-group-append">-->
                                                <!--        <button class="btn btn-primary bootstrap-touchspin-up" data-bs-toggle="modal" data-bs-target="#pickup-location-modal" type="button">+</button>-->
                                                <!--    </span>-->
                                                <!--</div>-->
                                            </div>
                                            <div class="col-xl-3" style="padding: 10px;">
                                            <label for="">Drop-off Location</label>
                                            <input type="text" id="return_transportation_drop_off_location" name="return_transportation_drop_off_location" class="form-control dropof_location">
                                                <!--<div class="input-group">-->
                                                    <!--<select type="text" id="return_transportation_drop_off_location" name="return_transportation_drop_off_location" class="form-control dropof_location">-->
                                                    
                                                    <!--</select>-->
                                                <!--    <span title="Add DropOf Location" class="input-group-btn input-group-append">-->
                                                <!--        <button class="btn btn-primary bootstrap-touchspin-up" data-bs-toggle="modal" data-bs-target="#dropof-location-modal" type="button">+</button>-->
                                                <!--    </span>-->
                                                <!--</div>-->
                                            </div>
                                            <div class="col-xl-3" style="padding: 10px;">
                                            <label for="">Pick-up Date & Time</label>
                                            <input type="datetime-local" id="return_transportation_pick_up_date" name="return_transportation_pick_up_date" class="form-control">
                                            </div>
                                            <div class="col-xl-3" style="padding: 10px;">
                                            <label for="">Drop-of Date & Time</label>
                                            <input type="datetime-local" id="return_transportation_drop_of_date" name="return_transportation_drop_of_date" class="form-control">
                                            </div>
                                            <div class="col-xl-3" style="display:none" id="return_transportation_Time_Div">
                                            <label for="" style="width: 205px;">Estimate Time Return</label>
                                            <input type="text" id="return_transportation_total_Time" name="return_transportation_total_Time" class="form-control return_transportation_total_Time1" readonly style="padding: 10px;" value="">
                                            </div>
                                            
                                            <div class="col-xl-3" style="padding: 10px;">
                                                <label for="">Vehicle Type</label>
                                                <select name="return_transportation_vehicle_type" id="" class="form-control"  data-placeholder="Choose ...">
                                                <option value="">Choose ...</option>
                                                <option value="Bus">Bus</option>
                                                <option value="Coach">Coach</option>
                                                <option value="Vain">Vain</option>
                                                <option value="Car">Car</option></select>
                                            </div>
                                            
                                            <div class="col-xl-3" style="padding: 10px;">
                                                <label for="">No Of Vehicle</label>
                                                <input type="text" id="return_transportation_no_of_vehicle" name="return_transportation_no_of_vehicle" class="form-control">
                                            </div>
                                            <div class="col-xl-3" style="padding: 10px;">
                                                <label for="">Price Per Vehicle</label>
                                                <div class="input-group">
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                                            
                                                                </a>
                                                            </span>
                                                    <input type="text" id="return_transportation_price_per_vehicle" name="return_transportation_price_per_vehicle" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-xl-3" style="padding: 10px;">
                                                <label for="">Total Vehicle Price</label>
                                                <div class="input-group">
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                                            
                                                                </a>
                                                            </span>
                                                    <input type="text" id="return_transportation_vehicle_total_price" name="return_transportation_vehicle_total_price" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-xl-3" style="padding: 10px;">
                                                <label for="">Price Per Person</label>
                                                    <div class="input-group">
                                                                <span class="input-group-btn input-group-append">
                                                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                                                
                                                                    </a>
                                                                </span>
                                                        <input type="text" id="return_transportation_price_per_person" name="return_transportation_price_per_person" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        <div class="col-xl-12 d-none" style="padding: 10px;">
                                    <label for="">Image</label>
                                    <input type="file" id="" name="transportation_image" class="form-control">
                                    </div>
                                        <div id="append_transportation">
                                    </div>
                                        <div class="mt-2" style="display:none;" id="add_more_destination">
                                    <a href="javascript:;" id="more_transportation" class="btn btn-info" style="float: right;">Add More Destinations </a>
                                    </div>
                                
                                    </div>  
                                    
                                </div>
                            </div> 
                            <!--<a id="save_Transportation" class="btn btn-primary" name="submit">Save Transportation Details</a>-->
                        </div> 
                                                
                        <div class="tab-pane" id="Itinerary_details">
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
                                                    
                        <div class="tab-pane" id="Extras_details" >
                            
                           
                        
                            <div class="row">
                                
                                <div class="col-xl-4" style="text-align:right;">
                                    <span>Markup on total price</span>
                                </div>
                                
                                <input type="hidden" id="markupSwitch" name="markupSwitch" value="single_markup_switch">
                                
                                <div class="col-xl-1">
                                    <input type="checkbox" id="switch2" data-switch="bool"/>
                                    <label for="switch2" data-on-label="On" data-off-label="Off"></label>
                                </div>
                                 
                                <div class="col-xl-7">
                                    <span>Markup on break down prices</span>
                                </div>
  
                                <div class="col-xl-12" id="markup_services">
                                    <div class="card">
                                        
                                        <div class="card-header">
                                            <h4 class="modal-title" id="standard-modalLabel">Markup on break down prices</h4>
                                        </div>
                                            
                                        <div id="" class="card-body">
                                            <div class="row">
                                                
                                                <div class="row" id="flights_cost" style="display:none;padding-bottom: 25px">
                                                    
                                                    <div class="col-xl-3">
                                                         <h4 class="" id="">Flights Cost</h4>
                                                    </div>
                                                    
                                                    <div class="col-xl-9">
                                                        <input type="hidden" id="flight_Type_Costing" name="markup_Type_Costing[]" value="flight_Type_Costing" class="form-control">
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                        <input value="" type="text" id="flights_departure_code" readonly="" name="hotel_name_markup[]" class="form-control">
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                        <input value="" type="text" id="flights_arrival_code" readonly="" name="room_type[]" class="form-control">
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                        <div class="input-group">
                                                            <input type="text" id="flights_prices" readonly="" name="without_markup_price[]" class="form-control">
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                                            
                                                                </a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                        <select name="markup_type[]" id="markup_type" class="form-control">
                                                            <option value="">Markup Type</option>
                                                            <option value="%">Percentage</option>
                                                            <option value="<?php echo $currency; ?>">Fixed Amount</option>
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                        <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                            <input type="text"  class="form-control" id="markup_value" name="markup[]">
                                                            <span class="input-group-btn input-group-append">
                                                                <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="markup_value_markup_mrk">%</div></button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                        <div class="input-group">
                                                            <input type="text" id="total_markup" name="markup_price[]" class="form-control">
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                                            
                                                                </a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                
                                                </div>
                                                
                                                <div id="append_accomodation_data_cost">
                                                    
                                                    @if($get_invoice->markup_details != null)
                                                    
                                                    <?php
                                                    $count=1;
                                                    $markup_details=$get_invoice->markup_details;
                                                    $markup_details=json_decode($markup_details);
                                                    foreach($markup_details as $details)
                                                    {
                                                    if($details->markup_Type_Costing == 'hotel_Type_Costing')
                                                    {
                                                    ?>
                                                    
                                                    <div class="row" id="costing_acc{{$count}}" style="margin-bottom:20px;">
                
                                    <input type="hidden" id="hotel_Type_Costing" name="markup_Type_Costing[]" value="hotel_Type_Costing" class="form-control">
                                    
                                    <input type="text" name="hotel_name_markup[]" hidden>
                                    <div class="col-xl-3">
                                    </div>
                                    <div class="col-xl-9"></div>
                                
                            
                                    <div class="col-xl-3">
                                    
                                    <input type="text" id="hotel_acc_type_{{$count}}" readonly="" value="{{$details->room_type}}" name="room_type[]" class="form-control id_cot">
                                        </div>
                                         <div class="col-xl-3">
                                            <div class="input-group">
                                                <input type="text" id="hotel_acc_price_{{$count}}" readonly="" value="{{$details->without_markup_price}}"  name="without_markup_price[]" class="form-control">
                                                <span class="input-group-btn input-group-append">
                                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                               
                                                    </a>
                                                </span>
                                            </div>
                                        </div>
                                         <div class="col-xl-2">
                                                 
                                                  <select name="markup_type[]" onchange="hotel_markup_type({{$count}})" id="hotel_markup_types_{{$count}}" class="form-control">
                                                        <option value="">Markup Type</option>
                                                        <option <?php if($details->markup_type == '%') echo "selected";?> value="%">Percentage</option>
                                                        <option <?php if($details->markup_type == $currency ) echo "selected";?> value="<?php echo $currency; ?>">Fixed Amount</option>
                                                  </select>
                                  
                                        </div>
                                        <div class="col-xl-2">
                                            <input type="hidden" id="" name="" class="form-control">
                                            <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                <input type="text" value="{{$details->markup}}"  class="form-control" id="hotel_markup_{{$count}}" name="markup[]">
                                                <span class="input-group-btn input-group-append">
                                                <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="hotel_markup_mrk_{{$count}}">%</div></button>
                                                </span>
                                            </div>
                                        </div>
                                    <div class="col-xl-2">
                                        <div class="input-group">
                                            <input type="text" id="hotel_markup_total_{{$count}}" value="{{$details->markup_price}}" name="markup_price[]" class="form-control id_cot">
                                            <span class="input-group-btn input-group-append">
                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                           
                                                </a>
                                            </span>
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
                                                    foreach($more_markup_details as $details)
                                                    {
                                                    if($details->more_markup_Type_Costing == 'more_hotel_Type_Costing')
                                                    {
                                                    ?>
                                                    
                                                    <div style="padding-bottom: 5px;" class="row click_delete_{{$count}}" id="click_delete_{{$count}}">
                            <div class="col-xl-3">
                                <input type="text" name="more_hotel_name_markup[]" hidden id="more_hotel_name_markup{{$count}}">
                                <h4 class="" id="">More Accomodation Cost {{$details->more_hotel_name_markup}}</h4>
                            </div>
                            <div class="col-xl-9"></div>
                            
                            <div class="col-xl-3">
                                
                                <input type="hidden" id="more_hotel_Type_Costing" name="more_markup_Type_Costing[]" value="more_hotel_Type_Costing" class="form-control">
                                
                                <input type="text" id="more_hotel_acc_type_{{$count}}" readonly="" value="{{$details->more_room_type}}" name="more_room_type[]" class="form-control">
                            </div>
                             <div class="col-xl-3">
                                <div class="input-group">
                                    <input type="text" id="more_hotel_acc_price_{{$count}}" readonly="" value="{{$details->more_without_markup_price}}" name="more_without_markup_price[]" class="form-control">
                                    <span class="input-group-btn input-group-append">
                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">
                                          
                                        </a>
                                    </span>
                                    
                                </div>
                            </div>
                             <div class="col-xl-2">
                                     
                                      <select name="more_markup_type[]" onchange="more_hotel_markup_type_acc({{$count}})" value="{{$details->more_markup_type}}" id="more_hotel_markup_types_{{$count}}" class="form-control">
                                          <option value="">Markup Type</option>
                                          <option value="%">Percentage</option>
                                          <option value="<?php echo $currency; ?>">Fixed Amount</option>
                                      </select>
                      
                            </div>
                            <div class="col-xl-2">
                                     
                                <input type="hidden" id="" name="" class="form-control">
                                <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                    <input type="text"  class="form-control" id="more_hotel_markup_{{$count}}" value="{{$details->more_markup}}" name="more_markup[]">
                                    <span class="input-group-btn input-group-append">
                                <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="more_hotel_markup_mrk_{{$count}}">%</div></button>
                                </span>
                            </div>
                        
                            </div>
                            <div class="col-xl-2">
                                <div class="input-group">
                                    <input type="text" id="more_hotel_markup_total_{{$count}}"  name="more_markup_price[]" value="{{$details->more_markup_price}}" class="form-control">
                                    <span class="input-group-btn input-group-append">
                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">
                                          
                                        </a>
                                    </span>
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
                                                
                                                <div style="display:none;" class="row" id="transportation_cost" >
                                                    
                                                    <div class="col-xl-3">
                                                         <h4 class="" id="">Transportation Cost</h4>
                                                    </div>
                                                    
                                                    <div class="col-xl-9">
                                                        <input type="hidden" id="transportation_Type_Costing" name="markup_Type_Costing[]" value="transportation_Type_Costing" class="form-control">  
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                        <input type="text" id="transportation_pick_up_location_select" readonly="" name="hotel_name_markup[]" class="form-control">
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                        <input type="text" id="transportation_drop_off_location_select" readonly="" name="room_type[]" class="form-control">
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                        <div class="input-group">
                                                            <input type="text" id="transportation_price_per_person_select" readonly="" name="without_markup_price[]" class="form-control">
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                                            
                                                                </a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                        <select name="markup_type[]" id="transportation_markup_type" class="form-control">
                                                            <option value="">Markup Type</option>
                                                            <option value="%">Percentage</option>
                                                            <option value="<?php echo $currency; ?>">Fixed Amount</option>
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                        <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                            <input type="text"  class="form-control" id="transportation_markup" name="markup[]">
                                                            <span class="input-group-btn input-group-append">
                                                                <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="transportation_markup_mrk">%</div></button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                        <div class="input-group">
                                                            <input type="text" id="transportation_markup_total" name="markup_price[]" class="form-control">
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                                            
                                                                </a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                
                                                <div style="display:none;" class="row" id="visa_cost" >
                                                    
                                                    <div class="col-xl-3">
                                                        <h4 class="" id="">Visa Cost</h4>
                                                    </div>
                                                    
                                                    <div class="col-xl-9">
                                                        <input type="hidden" id="visa_Type_Costing" name="markup_Type_Costing[]" value="visa_Type_Costing" class="form-control">
                                                    </div>
                                                    
                                                    <div class="col-xl-3">
                                                        <input readonly type="text" id="visa_type_select" name="hotel_name_markup[]" class="form-control">
                                                    </div>
                                                    
                                                    <div class="col-xl-3">
                                                        <div class="input-group">
                                                            <input readonly type="text" id="visa_price_select" name="without_markup_price[]" class="form-control">
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                                            
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
                                                            <input type="text"  class="form-control" id="visa_markup" name="markup[]">
                                                            <span class="input-group-btn input-group-append">
                                                                <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="visa_mrk">%</div></button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                        <div class="input-group">
                                                            <input type="text" id="total_visa_markup" name="markup_price[]" class="form-control">
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                                                 
                                                                </a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                    
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
                            
                            <?php
                            $meal_markup_details=$get_invoice->meal_markup_details;
                            $meal_markup_details=json_decode($meal_markup_details);
                            if($get_invoice->meal_markup_details != null)
                            {
                             foreach($meal_markup_details as $details)   
                            {
                            ?>
                            <div id="mealoption_markup_cost">
                                                    
                                                                                                        
                                                                                                        
                                                    <div class="row" id="" style="margin-bottom:20px;">
                
                                    
                                    <div class="col-xl-3">
                                    
                                    <input type="text" id="meal_type" readonly="" value="{{$details->select_meal_type}}"  name="select_meal_type[]" class="form-control">
                                        </div>
                                         <div class="col-xl-3">
                                            <div class="input-group">
                                                <input type="text" id="meal_type_with_out_markup_price" readonly="" value="{{$details->select_meal_type_price}}" name="select_meal_type_price[]" class="form-control">
                                                <span class="input-group-btn input-group-append">
                                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                                     </a>
                                                </span>
                                            </div>
                                        </div>
                                         <div class="col-xl-2">
                                                 
                                                  <select name="select_meal_type_markup_type[]"  id="meal_type_with_markup" class="form-control">
                                                        <option value="">Markup Type</option>
                                                        <option <?php if($details->select_meal_type_markup_type == '%') echo "selected"; ?> value="%">Percentage</option>
                                                        <option <?php if($details->select_meal_type_markup_type == '£') echo "selected"; ?> value="£">Fixed Amount</option>
                                                  </select>
                                  
                                        </div>
                                        <div class="col-xl-2">
                                            
                                            <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                <input type="text" value="{{$details->select_meal_type_markup_value}}" class="form-control" id="meal_type_with_markup_amout" name="select_meal_type_markup_value[]">
                                                <span class="input-group-btn input-group-append">
                                                <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="meal_markup_mrk"> </div></button>
                                                </span>
                                            </div>
                                        </div>
                                    <div class="col-xl-2">
                                        <div class="input-group">
                                            <input type="text" id="meal_type_with_markup_sale_amout" value="{{$details->select_meal_type_markup_price}}" name="select_meal_type_markup_price[]" class="form-control id_cot">
                                            <span class="input-group-btn input-group-append">
                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                                  </a>
                                            </span>
                                        </div> 
                                    </div>
                                </div>
                             </div>
                            <?php
                            }
                             }
                            ?>
                            
                            <div id="accomodation_price_hide" class="row mt-2" >
                                
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
                        
                            <div class="row" id="sale_pr">
                                
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
              <option value="{{$payment_gateways->payment_gateway_title}}">{{$payment_gateways->payment_gateway_title}}</option>
              @endforeach
              @endif
         </select>
    
    </div>
    </div>
    <div class="col-xl-6">
    <div class="mb-3">
    
    
         <label for="simpleinput" class="form-label">Payment Mode</label>
         <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Payment Mode"></i>
         <select class="select2 form-control select2-multiple external_packages" data-toggle="select2" multiple="multiple" id="payment_modes" name="payment_modes[]">
             <option value="">Select Payment Mode</option>
             @if(isset($payment_modes))
                @foreach($payment_modes as $payment_modes)
                    <option value="{{$payment_modes->payment_mode}}">{{$payment_modes->payment_mode}}</option>
                @endforeach
              @endif
         </select>
    
     
      
    
     
     
    </div>
      
    </div>
    
        <div class="col-xl-12">
            <label for="">Payment Instructions</label>
            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Payment Instructions"></i>
            <textarea name="checkout_message" class="form-control" cols="5" rows="5"></textarea>
        </div>
    
      </div>
      
                            <!--<a id="save_Costing" class="btn btn-primary" name="submit">Save Costing Details</a>-->
                            <button style="float: right;width: 100px;margin-top: 10px;" type="submit" name="submit" class="btn btn-info deletButton">Submit</button>
                        </div>
                        
                        <ul class="list-inline mb-0 wizard" style="margin-top:60px;">
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
        <!--</div>-->
    </div>
</div>
                 
@endsection

@section('scripts')

<script src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initAutocomplete&language=nl&output=json&key=AIzaSyBmwlQFQKwxZ4D8nRbsWVRTBFUHMO-lUOY" async defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs5.min.js" integrity="sha512-6F1RVfnxCprKJmfulcxxym1Dar5FsT/V2jiEUvABiaEiFWoQ8yHvqRM/Slf0qJKiwin6IDQucjXuolCfCKnaJQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


<script type="text/javascript">

    $(document).ready(function() {
var value_c = $("#currency_conversion").val();
        
                // var v = ( this.code );
                 console.log('currency value_c'+value_c);
         
const usingSplit = value_c.split(' ');
var value_1 = usingSplit['0'];
var value_2 = usingSplit['3'];

console.log(value_1);
console.log(value_2);
              
exchange_currency_funs(value_1,value_2);

        }
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
$(document).ready(function() {
    var ifchecked=$('#NotIncluded').prop('checked', true);
 
if(ifchecked)
{
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

  
 var hotel_meal_types=$('#hotel_meal_types').val();


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

 //alert(val);
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
        console.log('Services is call now ');
        var selectedServices = $('#select_services').val();
        console.log(selectedServices);
        
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

<!--Package Save-->
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
<!-- End Package Save-->

<!--Flights-->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBmwlQFQKwxZ4D8nRbsWVRTBFUHMO-lUOY&sensor=false&libraries=places"></script>
<script>
    
    let places,places1,return_places,return_places1,places_T,places1_T,return_places_T,return_places1_T ,input, address, city;
    google.maps.event.addDomListener(window, "load", function () {
        var places = new google.maps.places.Autocomplete(
            document.getElementById("departure_airport_code")
        );
        
        var places1 = new google.maps.places.Autocomplete(
            document.getElementById("arrival_airport_code")
        );
        
        google.maps.event.addListener(places, "place_changed", function () {
            var place = places.getPlace();
            // console.log(place);
            var address = place.formatted_address;
            $('#flights_arrival_code').val(address);
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
        
        google.maps.event.addListener(places1, "place_changed", function () {
            var place1 = places1.getPlace();
            // console.log(place1);
            var address = place1.formatted_address;
            var latitude = place1.geometry.location.lat();
            var longitude = place1.geometry.location.lng();
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
        
        // Return_Details
        var return_places = new google.maps.places.Autocomplete(
            document.getElementById("return_departure_airport_code")
        );
        
        var return_places1 = new google.maps.places.Autocomplete(
            document.getElementById("return_arrival_airport_code")
        );
        
        google.maps.event.addListener(return_places, "place_changed", function () {
            var return_place = return_places.getPlace();
            // console.log(return_place);
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
        
        google.maps.event.addListener(return_places1, "place_changed", function () {
            var return_place1 = return_places1.getPlace();
            // console.log(return_place1);
            var address = place1.formatted_address;
            var latitude = place1.geometry.location.lat();
            var longitude = place1.geometry.location.lng();
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
        
        // Transporation_Details
        var places_T = new google.maps.places.Autocomplete(
            document.getElementById("transportation_pick_up_location")
        );
        
        var places1_T = new google.maps.places.Autocomplete(
            document.getElementById("transportation_drop_off_location")
        );
        
        google.maps.event.addListener(places_T, "place_changed", function () {
            var place = places_T.getPlace();
            // console.log(place);
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
        
        google.maps.event.addListener(places1_T, "place_changed", function () {
            var place1 = places1_T.getPlace();
            // console.log(place1);
            var address = place1.formatted_address;
            var latitude = place1.geometry.location.lat();
            var longitude = place1.geometry.location.lng();
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
                        $("#transportation_drop_off_location").on('change',function () {
                            var b = $('#transportation_drop_off_location_select').val(city);
                            console.log(b);
                        });
                    }
                }
            });
        });
        
        // Return_Transportation_Details
        var return_places_T = new google.maps.places.Autocomplete(
            document.getElementById("return_transportation_pick_up_location")
        );
        
        var return_places1_T = new google.maps.places.Autocomplete(
            document.getElementById("return_transportation_drop_off_location")
        );
        
        google.maps.event.addListener(return_places_T, "place_changed", function () {
            var place = return_places_T.getPlace();
            // console.log(place);
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
        
        google.maps.event.addListener(return_places1_T, "place_changed", function () {
            var place1 = return_places1_T.getPlace();
            // console.log(place1);
            var address = place1.formatted_address;
            var latitude = place1.geometry.location.lat();
            var longitude = place1.geometry.location.lng();
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
    
</script>
<!--End Flights-->

<script type="text/javascript">
 
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
                //   console.log(data2['visa_type']);
                // jQuery.each(data2, function(key, value){  
                //     $(".other_type").append('<option value=' +value.id+ '>' + value.other_visa_type+ '</option>');
                //   });
            	$("#visa_type").empty();
           
                $.each(data2['visa_type'], function(key, value) {
                    var visa_type_Data = `<option attr="${value.other_visa_type}" value="${value.other_visa_type}"> ${value.other_visa_type}</option>`;
                    $("#visa_type").append(visa_type_Data);
                });  
            }
        });
    });
    
    // $(document).on('click','#transportation',function(){
    //     $.ajax({    
    //         type: "GET",
    //         url: "get_pickup_dropof_location",             
    //         dataType: "html",                  
    //         success: function(data){
    //             var data1 = JSON.parse(data);
    //             var data2 = data1['pickup_location_get'];
    //             var data3 = data1['dropof_location_get'];
    //         	$(".pickup_location").empty();
    //             $(".dropof_location").empty();
    //             $.each(data2, function(key, value) {
    //                 var pickup_location_Data = `<option attr="${value.pickup_location}" value="${value.pickup_location}"> ${value.pickup_location}</option>`;
    //                 $(".pickup_location").append(pickup_location_Data);
    //             });
    //             $.each(data3, function(key, value) {
    //                 var dropof_location_Data = `<option attr="${value.dropof_location}" value="${value.dropof_location}"> ${value.dropof_location}</option>`;
    //                 $(".dropof_location").append(dropof_location_Data);
    //             });
    //         }
    //     });
    // });

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
  
@stop

@section('slug')

<script>

    $('#transportation_price_per_vehicle').keyup(function() {
            
   var transportation_price_per_vehicle =  $('#transportation_price_per_vehicle').val();
   var transportation_no_of_vehicle =  $('#transportation_no_of_vehicle').val();
    var no_of_pax_days =  $('#no_of_pax_days').val();
    
    
    var t_trans1 = transportation_price_per_vehicle * transportation_no_of_vehicle;
    var t_trans = t_trans1.toFixed(2);
  console.log('t_trans'+t_trans);
  $('#transportation_vehicle_total_price').val(t_trans)
        var total_trans1 = t_trans/no_of_pax_days;
        var total_trans = total_trans1.toFixed(2);
          console.log('total_trans'+total_trans);
        $('#transportation_price_per_person').val(total_trans)
        
  $('#transportation_price_per_person_select').val(total_trans);
  add_numberElse();
});

    $('#return_transportation_price_per_vehicle').keyup(function() {    
        var return_transportation_price_per_vehicle =  $('#return_transportation_price_per_vehicle').val();
        var return_transportation_no_of_vehicle =  $('#return_transportation_no_of_vehicle').val();
        var no_of_pax_days =  $('#no_of_pax_days').val();
        
        var return_t_trans1 = return_transportation_price_per_vehicle * return_transportation_no_of_vehicle;
        var return_t_trans = return_t_trans1.toFixed(2);
        console.log('return_t_trans'+return_t_trans);
        $('#return_transportation_vehicle_total_price').val(return_t_trans)
        var return_total_trans1 = return_t_trans/no_of_pax_days;
        var return_total_trans = return_total_trans1.toFixed(2);
          console.log('return_total_trans'+return_total_trans);
        $('#return_transportation_price_per_person').val(return_total_trans)
        
        // $('#return_transportation_price_per_person_select').val(return_total_trans);
        add_numberElse();
    });

    $("#flights_type").on('change',function () {
        var id = $(this).val();
        $('#flights_departure_code').val(id);
    });
    
    $("#departure_Flight_Type").change(function () {
        var id = $(this).val();
        $('#flights_arrival_code').val(id);
    });
  
    $("#markup_type").change(function () {
        var id = $(this).find('option:selected').attr('value');
        
        $('#markup_value_markup_mrk').text(id);
        var markup_val =  $('#markup_value').val();
        
        var flights_prices =  $('#flights_prices').val();
        if(id == '%')
        {
            $('#markup_value').keyup(function() {
                var markup_val =  $('#markup_value').val();
                var total1 = (flights_prices * markup_val/100) + parseFloat(flights_prices) ;
                var total = total1.toFixed(2);
                $('#total_markup').val(total);
                add_numberElse_1();
            });
        }
        else
        {
            $('#markup_value').keyup(function() {
                var markup_val =  $('#markup_value').val();
                console.log(markup_val);
                var total1 = parseFloat(flights_prices) + parseFloat(markup_val);
                var total = total1.toFixed(2);
                $('#total_markup').val(total);
                add_numberElse_1();
            });
        }
    });
      
    $('#property_city').change(function(){
        var arr=[];
        $('#property_city option:selected').each(function(){
        var $value =$(this).attr('type');
        // console.log($value);
        arr.push($value);
    });
// console.log(arr);
var json_data=JSON.stringify(arr);
$('#tour_location_city').val(json_data); 
$('#packages_get_city').val(json_data);
 
}); 

    $("#visa_type").change(function () {
        var id = $(this).find('option:selected').attr('attr');
        
        $('#visa_type_select').val(id);
        
        
        });
    
    $("#visa_markup_type").change(function () {
        var id = $(this).find('option:selected').attr('value');
        $('#visa_mrk').text(id);
        // var markup_val =  $('#visa_markup').val();
        // var visa_price_select =  $('#visa_price_select').val();
        if(id == '%')
        {
            $('#visa_markup').keyup(function() {
                var markup_val =  $('#visa_markup').val();
                var visa_price_select =  $('#visa_price_select').val();
                var total1 = (visa_price_select * markup_val/100) + parseFloat(visa_price_select);
                var total = total1.toFixed(2);
                $('#total_visa_markup').val(total);
                add_numberElse_1();
            });
        }
        else
        {
            $('#visa_markup').keyup(function() {
                var markup_val =  $('#visa_markup').val();
                var visa_price_select =  $('#visa_price_select').val();
                var total1 =  parseFloat(visa_price_select) +  parseFloat(markup_val);
                var total = total1.toFixed(2);
                $('#total_visa_markup').val(total);
                add_numberElse_1();
            });
        }
    });
        
    $('#visa_fee').keyup(function() {
        
        var visa_fee = this.value;
        $('#visa_price_select').val(visa_fee);
        add_numberElse();
    });
        
        $("#transportation_pick_up_location").on('keyup',function () {
            setTimeout(function() { 
                var id = $('#transportation_pick_up_location').val();
                console.log('transportation_pick_up_location :'+id+'');
                $('#transportation_pick_up_location_select').val(id);
            }, 10000);
        });
        
        $("#transportation_drop_off_location").change(function () {
            setTimeout(function() { 
                var id = $('#transportation_drop_off_location').val();
                console.log('transportation_drop_off_location :'+id+'');
                $('#transportation_drop_off_location_select').val(id);
            }, 10000);
        });
        
    $("#return_transportation_pick_up_location").change(function () {
        var id = this.value;
        
        $('#return_transportation_pick_up_location_select').val(id);
        
        
        });
        
    $("#return_transportation_drop_off_location").change(function () {
        var id = this.value;
        
        $('#return_transportation_drop_off_location_select').val(id);
        
        
        });
        
    $("#transportation_price_per_person").change(function () {
        var id = this.value;
        
        $('#transportation_price_per_person_select').val(id);
        
        
        
        
        });
        
    $("#transportation_markup_type").change(function () {
            var id = $(this).find('option:selected').attr('value');
            $('#transportation_markup_mrk').text(id);
            if(id == '%')
            {
                $('#transportation_markup').keyup(function() {
                var markup_val =  $('#transportation_markup').val();
                var transportation_price =  $('#transportation_price_per_person_select').val();
                var total1 = (transportation_price * markup_val/100) + parseFloat(transportation_price);
                var total = total1.toFixed(2);
                $('#transportation_markup_total').val(total);
                add_numberElse_1();
                });
            }
            else
            {
                $('#transportation_markup').keyup(function() {
                var markup_val =  $('#transportation_markup').val();
                console.log(markup_val);
                var transportation_price =  $('#transportation_price_per_person_select').val();
                var total1 = parseFloat(transportation_price) + parseFloat(markup_val);
                var total = total1.toFixed(2);
                $('#transportation_markup_total').val(total);
                add_numberElse_1();
                });
            }
        });
  
    $('#flights_per_person_price').keyup(function() {
   
    var flights_per_person_price = this.value;
   $('#flights_prices').val(flights_per_person_price);
    add_numberElse();
 
});

    $("#flights_inc").click(function () {
        $('#flights_cost').toggle();
    });

    $("#transportation").click(function () {
   $('#transportation_cost').toggle();
	
  });

    $("#visa_inc").click(function () {
   $('#visa_cost').toggle();
	
  });

    $("#slect_trip").change(function () {
    var id = $(this).find('option:selected').attr('value');
    // alert(id);
    if(id == 'Return')
    {
    $('#add_more_destination').fadeOut();
    	$('#select_return').fadeIn();
    
    }
    else if(id == 'All_Round')
    {
    	$('#select_return').fadeOut();
    	$('#add_more_destination').fadeIn();
    
    }
    else
    {
      	$('#select_return').fadeOut();
      	$('#add_more_destination').fadeOut();
    }






  });

    $("#visa_type").change(function () {
    var id = $(this).find('option:selected').attr('value');
// alert(id);
if(id == 'Others')
{
	$('#SubmitForm_sec').fadeOut();
	$('#SubmitForm_sec').fadeIn();

}

else
{
	$('#SubmitForm_sec').fadeOut();
}




  });
  
    
</script>

<script>
    
    var user_hotels = {!!json_encode($user_hotels)!!};
    
    // Flights
    $("#flights_type").change(function () {
            var id = $(this).find('option:selected').attr('value');
            if(id == 'Indirect')
            {
                $("#text_editer").css("padding", "20");
                $('#flights_type_connected').fadeIn();
                $('#flights_type_connected').empty();
                var no_of_stays_Append = `<label for="no_of_stays">No Of Stays</label>
                                            <select  name="no_of_stays" id="no_of_stays" class="form-control select2" data-toggle="select2">
                                                <option value="">Choose...</option>
                                                <option value="2">1</option>
                                                <option value="3">2</option>
                                                <option value="4">3</option>
                                            </select>`;
                $('#flights_type_connected').append(no_of_stays_Append);
                $('#text_editer').fadeOut();
                $('#text_editer').fadeIn();
                $('#stop_No').replaceWith($('<h3 style="width: 125px;margin-top: 25px;float:right" id="stop_No">Stop No 1 :</h3>'));
                $('#return_stop_No').replaceWith($('<h3 style="width: 182;margin-top: 25px;float:right" id="return_stop_No">Return Stop No 1 :</h3>'));

                $('.Flight_section').fadeOut();
                $('.return_Flight_section').fadeOut();
                $('#total_Time_Div').fadeOut();
                $('#return_total_Time_Div').fadeOut();
            }
            else
            {
                $('.Flight_section').fadeIn();
                $('.return_Flight_section').fadeIn();
                $('#total_Time_Div').fadeIn();
                $('#return_total_Time_Div').fadeIn();
                
                $('#flights_type_connected').fadeOut();
                $('.Flight_section_append').empty();
                $('.return_Flight_section_append').empty();
            	$('#text_editer').fadeOut();
            	$('#stop_No').replaceWith($('<h3 style="width: 125px;margin-top: 25px;float:right" id="stop_No">Direct :</h3>'));
            	$('#return_stop_No').replaceWith($('<h3 style="width: 162px;margin-top: 25px;float:right" id="return_stop_No">Return Direct :</h3>'));
            }
        });
   
    $('#flights_type_connected').change(function () {
        var no_of_stays = $('#no_of_stays').val();
        if(no_of_stays == 'NON_STOP'){
            $('.Flight_section_append').empty();
            $('.return_Flight_section_append').empty();
        }
        else{
            $('.Flight_section_append').empty();
            $('.return_Flight_section_append').empty();
            var no_of_stay_ID = 1;
            for (let i = 1; i <= no_of_stays; i++) {
                var flight_Data =   `<h3 style="padding: 12px">Departure Details : </h3>
                                     <div class="row" style="padding: 12px">
                                        <div class="col-xl-4">
                                            <label for="">Departure Airport</label>
                                            <input name="more_departure_airport_code[]" id="departure_airport_code_${i}" class="form-control" autocomplete="off" placeholder="Enter Departure Location">
                                        </div>
                                        <div class="col-xl-1" style="margin-top: 25px;text-align: center;">
                                            <label for=""></label>
                                            <span id="Change_Location_${i}" class='bi bi-arrow-left-right' style="font-size: 23px;"></span>
                                        </div>
                                        <div class="col-xl-4">
                                            <label for="">Arrival Airport</label>
                                            <input name="more_arrival_airport_code[]" id="arrival_airport_code_${i}" class="form-control" autocomplete="off" placeholder="Enter Arrival Location">
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="">Airline Name</label>
                                            <input type="text" id="other_Airline_Name2_${i}" name="more_other_Airline_Name2[]" class="form-control other_airline_Name1_${i}">
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="">Class Type</label>
                                            <select  name="more_departure_Flight_Type[]" id="departure_Flight_Type_${i}" class="form-control">
                                                <option value="">Select Flight Type</option>
                                                <option value="Bussiness">Bussiness</option>
                                                <option value="Economy">Economy</option>
                                                <option value="Standard">Standard</option>
                                            </select>
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="">Flight No</label>
                                            <input type="text" id="simpleinput_${i}" name="more_departure_flight_number[]" class="form-control" placeholder="Enter Flight Number">
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="">Departure Date & Time</label>
                                            <input type="datetime-local" id="simpleinput_${i}" name="more_departure_time[]" class="form-control departure_time1_${i}" >
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="">Arrival Date and Time</label>
                                            <input type="datetime-local" id="simpleinput_${i}" name="more_arrival_time[]" class="form-control arrival_time1_${i}" >
                                        </div>
                                    </div>
                                    <div class="container total_Time_Div_data_append_${i}">
                                    </div>`;
                                    
                var return_flight_Data = `<h3 style="padding: 12px">Return Details : </h3>
                                          <div class="row" style="padding: 12px">
                                            <div class="col-xl-4">
                                                <label for="">Departure Airport</label>
                                                <input name="return_more_departure_airport_code[]" id="return_departure_airport_code_${i}" class="form-control" autocomplete="off" placeholder="Enter Return Location">
                                            </div>
                                            <div class="col-xl-1" style="margin-top: 25px;text-align: center;">
                                                <label for=""></label>
                                                <span id="return_Change_Location_${i}" class='bi bi-arrow-left-right' style="font-size: 23px;"></span>
                                            </div>
                                            <div class="col-xl-4">
                                                <label for="">Arrival Airport</label>
                                                <input name="return_more_arrival_airport_code[]" id="return_arrival_airport_code_${i}" class="form-control" autocomplete="off" placeholder="Enter Return Location">
                                                
                                            </div>
                                            <div class="col-xl-3">
                                                <label for="">Airline Name</label>
                                                <input type="text" id="return_other_Airline_Name2_${i}" name="return_more_other_Airline_Name2[]" class="form-control other_airline_Name1">
                                            </div>
                                            <div class="col-xl-3">
                                                <label for="">Class Type</label>
                                                <select  name="return_more_departure_Flight_Type[]" id="return_departure_Flight_Type_${i}" class="form-control">
                                                    <option value="">Select Flight Type</option>
                                                    <option value="Bussiness">Bussiness</option>
                                                    <option value="Economy">Economy</option>
                                                    <option value="Standart">Standart</option>
                                                </select>
                                            </div>
                                            <div class="col-xl-3">
                                                <label for="">Flight Number</label>
                                                <input type="text" id="simpleinput_${i}" name="return_more_departure_flight_number[]" class="form-control" placeholder="Enter Flight Number">
                                            </div>
                                            <div class="col-xl-3">
                                                <label for="">Departure Date and Time</label>
                                                <input type="datetime-local" id="simpleinput_${i}" name="return_more_departure_time[]" class="form-control return_departure_time1_${i}" >
                                            </div>
                                            <div class="col-xl-3">
                                                <label for="">Arrival Date and Time</label>
                                                <input type="datetime-local" id="simpleinput_${i}" name="return_more_arrival_time[]" class="form-control return_arrival_time1_${i}" >
                                            </div>
                                        </div>
                                        <div class="container return_total_Time_Div_data_append_${i}">
                                        </div>`;
                                    
                $('.Flight_section_append').append(flight_Data);
                
                $('#Change_Location_'+i+'').click(function () {
                    var arrival_airport_code   = $('#arrival_airport_code_'+i+'').val();
                    var departure_airport_code = $('#departure_airport_code_'+i+'').val();
                    $('#arrival_airport_code_'+i+'').val(departure_airport_code);
                    $('#departure_airport_code_'+i+'').val(arrival_airport_code);
                });
                
                $('.arrival_time1_'+i+'').change(function () {
                    
                    var h = "hours";
                    var m = "minutes";
                    
                    var arrival_time1 = $(this).val();
                    var departure_time1 = $('.departure_time1_'+i+'').val();
                    var second=1000, minute=second*60, hour=minute*60, day=hour*24, week=day*7;
                    var date1 = new Date(departure_time1);
                    var date2 = new Date(arrival_time1);
                    var timediff = date2 - date1;
                    var minutes_Total = Math.floor(timediff / minute);
                    var total_hours   = Math.floor(timediff / hour)
                    var total_hours_minutes = parseFloat(total_hours) * 60;
                    var minutes = parseFloat(minutes_Total) - parseFloat(total_hours_minutes);
                    
                    var total_Time_Div_data  = `<div class="row" style="margin-left: 303px;">
                                                    <div class="col-sm-3">
                                                        <h3 style="width: 140px;margin-top: 25px;float:right" id="no_of_stop_par${i}">Stop No ${i}</h3>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <label for="">Transit Time</label>
                                                        <input type="text" id="total_Time" name="more_total_Time[]" class="form-control total_Time1_${i}" readonly style="width: 167px;">
                                                    </div>
                                                </div>`;
                    $('.total_Time_Div_data_append_'+i+'').empty()
                    $('.total_Time_Div_data_append_'+i+'').append(total_Time_Div_data);
                    $('.total_Time1_'+i+'').val(total_hours+h+ ' : ' +minutes+m);
                    
                    var no_of_stays = $('#no_of_stays').val();
                    $('#no_of_stop_par'+no_of_stays+'').html('Destination :');
            
                });
                
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
                
                var places_D1 = new google.maps.places.Autocomplete(
                    document.getElementById('departure_airport_code_'+i+'')
                );
                
                var places1_D1 = new google.maps.places.Autocomplete(
                    document.getElementById('arrival_airport_code_'+i+'')
                );
                
                google.maps.event.addListener(places_D1, "place_changed", function () {
                    var places_D1 = places_D1.getPlace();
                    console.log(places_D1);
                    var address = places_D1.formatted_address;
                    console.log(address);
                    // $('#flights_arrival_code').val(address);
                    var latitude = places_D1.geometry.location.lat();
                    var longitude = places_D1.geometry.location.lng();
                    var latlng = new google.maps.LatLng(latitude, longitude);
                    var geocoder = (geocoder = new google.maps.Geocoder());
                    geocoder.geocode({ latLng: latlng }, function (results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            if (results[0]) {
                                var address = results[0].formatted_address;
                                var pin = results[0].address_components[
                                    results[0].address_components.length - 1
                                ].long_name;
                                var country =  results[0].address_components[
                                    results[0].address_components.length - 2
                                  ].long_name;
                                var state = results[0].address_components[
                                        results[0].address_components.length - 3
                                    ].long_name;
                                var city = results[0].address_components[
                                        results[0].address_components.length - 4
                                    ].long_name;
                                var country_code = results[0].address_components[
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
                
                google.maps.event.addListener(places1_D1, "place_changed", function () {
                    var places1_D1 = places1_D1.getPlace();
                    // console.log(places1_D1);
                    var address = places1_D1.formatted_address;
                    var latitude = places1_D1.geometry.location.lat();
                    var longitude = places1_D1.geometry.location.lng();
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
                
                // Return_Details
                $('.return_Flight_section_append').append(return_flight_Data);
                
                $('#return_Change_Location_'+i+'').click(function () {
                    var return_arrival_airport_code   = $('#return_arrival_airport_code_'+i+'').val();
                    var return_departure_airport_code = $('#return_departure_airport_code_'+i+'').val();
                    $('#return_arrival_airport_code_'+i+'').val(return_departure_airport_code);
                    $('#return_departure_airport_code_'+i+'').val(return_arrival_airport_code);
                });
                
                $('.return_arrival_time1_'+i+'').change(function () {
                    
                    var h = "hours";
                    var m = "minutes";
                    
                    var return_arrival_time1 = $(this).val();
                    var return_departure_time1 = $('.return_departure_time1_'+i+'').val();
                    var second=1000, minute=second*60, hour=minute*60, day=hour*24, week=day*7;
                    var return_date1 = new Date(return_departure_time1);
                    var return_date2 = new Date(return_arrival_time1);
                    var return_timediff = return_date2 - return_date1;
                    var return_minutes_Total = Math.floor(return_timediff / minute);
                    var return_total_hours   = Math.floor(return_timediff / hour)
                    var return_total_hours_minutes = parseFloat(return_total_hours) * 60;
                    var return_minutes = parseFloat(return_minutes_Total) - parseFloat(return_total_hours_minutes);
                    
                    var return_total_Time_Div_data  = `<div class="row" style="margin-left: 303px;">
                                                    <div class="col-sm-3">
                                                        <h3 style="width: 225px;margin-top: 25px;float:right" id="return_no_of_stop_par${i}">Return Stop No ${i} :</h3>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <label for="">Transit Time</label>
                                                        <input type="text" id="return_total_Time" name="return_more_total_Time[]" class="form-control return_total_Time1_${i}" readonly style="width: 167px;">
                                                    </div>
                                                </div>`;
                    $('.return_total_Time_Div_data_append_'+i+'').empty()
                    $('.return_total_Time_Div_data_append_'+i+'').append(return_total_Time_Div_data);
                    $('.return_total_Time1_'+i+'').val(return_total_hours+h+ ' : ' +return_minutes+m);
                    
                    var no_of_stays = $('#no_of_stays').val();
                    $('#return_no_of_stop_par'+no_of_stays+'').html('Return Destination :');
                });
                
                var return_places = new google.maps.places.Autocomplete(
                    document.getElementById('return_departure_airport_code_'+i+'')
                );
        
                var return_places1 = new google.maps.places.Autocomplete(
                    document.getElementById('return_arrival_airport_code_'+i+'')
                );
        
                google.maps.event.addListener(return_places, "place_changed", function () {
                    var return_place = return_places.getPlace();
                    // console.log(return_place);
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
        
                google.maps.event.addListener(return_places1, "place_changed", function () {
                    var return_place1 = return_places1.getPlace();
                    // console.log(return_place1);
                    var address = place1.formatted_address;
                    var latitude = place1.geometry.location.lat();
                    var longitude = place1.geometry.location.lng();
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
        
            }
        }
    });
    
    $('.arrival_time1').change(function () {
        
        var h = "hours";
        var m = "minutes";
        
        var arrival_time1 = $(this).val();
        var departure_time1 = $('.departure_time1').val();
        
        var second=1000, minute=second*60, hour=minute*60, day=hour*24, week=day*7;
        
        var date1 = new Date(departure_time1);
        var date2 = new Date(arrival_time1);
        var timediff = date2 - date1;
        
        var minutes_Total = Math.floor(timediff / minute);
        
        var total_hours   = Math.floor(timediff / hour)
        var total_hours_minutes = parseFloat(total_hours) * 60;
        
        var minutes = parseFloat(minutes_Total) - parseFloat(total_hours_minutes);
        
        $('#total_Time_Div').css('display','');
        $('.total_Time1').val(total_hours+h+ ' : ' +minutes+m);

    });
    
    $('.return_arrival_time1').change(function () {
        
        var h = "hours";
        var m = "minutes";
        
        var return_arrival_time1 = $(this).val();
        var return_departure_time1 = $('.return_departure_time1').val();
        
        var second=1000, minute=second*60, hour=minute*60, day=hour*24, week=day*7;
        
        var return_date1 = new Date(return_departure_time1);
        var return_date2 = new Date(return_arrival_time1);
        var return_timediff = return_date2 - return_date1;
        
        var return_minutes_Total = Math.floor(return_timediff / minute);
        
        var return_total_hours   = Math.floor(return_timediff / hour)
        var return_total_hours_minutes = parseFloat(return_total_hours) * 60;
        
        var return_minutes = parseFloat(return_minutes_Total) - parseFloat(return_total_hours_minutes);
        
        $('#return_total_Time_Div').css('display','');
        $('.return_total_Time1').val(return_total_hours+h+ ' : ' +return_minutes+m);
        
        
    });
    
    $('#Change_Location').click(function () {
        var arrival_airport_code   = $('#arrival_airport_code').val();
        var departure_airport_code = $('#departure_airport_code').val();
        $('#arrival_airport_code').val(departure_airport_code);
        $('#departure_airport_code').val(arrival_airport_code);
    });
    
    $('#return_Change_Location').click(function () {
        var return_arrival_airport_code   = $('#return_arrival_airport_code').val();
        var return_departure_airport_code = $('#return_departure_airport_code').val();
        $('#return_arrival_airport_code').val(return_departure_airport_code);
        $('#return_departure_airport_code').val(return_arrival_airport_code);
    });
    // End Flights
    
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
    // End Transportation
    
    // End Changes
    
    $("#accomodation").on('click',function(){
        
        $("#append_accomodation_data_cost1").empty();
        $("#append_accomodation_data_cost").empty();
        $("#append_accomodation").empty();
        var packages_get_city = $('#tour_location_city').val();
        var decodeURI_city = JSON.parse(packages_get_city);
        var city_slc =$(".city_slc").val();
        var count = city_slc.length;
        var j=0;
        for (let i = 1; i <= count; i++) {
    
            var data = `<div class="mb-2" style="border:1px solid #ced4da;padding: 20px 20px 20px 20px;">
                            <h4>
                                City #${i} (${decodeURI_city[i-1]})
                                <i class="dripicons-information" style="font-size: 17px;" id="title_Icon_${i}"
                                    data-bs-toggle="tooltip" data-bs-placement="right" title="Fill all the information of City ${decodeURI_city[i-1]}">
                                </i>
                            </h4> 
                <div class="row">
                    
                    <input type="hidden" name="hotel_city_name[]" id="hotel_city_name" value="${decodeURI_city[i-1]}"/>
                <div class="col-xl-3">
                    <label for="">Hotel Name</label>
                    <div class="input-group">
                       
                        <input type="text" id="acc_hotel_name_${i}" name="acc_hotel_name[]" class="form-control other_Hotel_Name acc_hotel_name_class_${i}" required>
                        
                        <span title="Add Hotel Name" class="input-group-btn input-group-append">
                            <button class="btn btn-primary bootstrap-touchspin-up" data-bs-toggle="modal" data-bs-target="#hotel-name-modal" type="button">+</button>
                        </span>
                    </div>
                </div>
                <div class="col-xl-3"><label for="">Check In</label><input type="date" id="makkah_accomodation_check_in_${i}" name="acc_check_in[]" class="form-control date makkah_accomodation_check_in_class_${i}">
                </div><div class="col-xl-3"><label for="">Check Out</label><input type="date" id="makkah_accomodation_check_out_date_${i}"  name="acc_check_out[]" onchange="makkah_accomodation_check_out(${i})"  class="form-control date makkah_accomodation_check_out_date_class_${i}"></div>
                <div class="col-xl-3"><label for="">No Of Nights</label><input type="text" id="acomodation_nights_${i}" name="acc_no_of_nightst[]" class="form-control acomodation_nights_class_${i}"></div>
                
                <div class="col-xl-2"><label for="">Room Type</label>
                    <div class="input-group">
                        <select onchange="hotel_type_fun(${i})" name="acc_type[]" id="hotel_type_${i}" class="form-control other_Hotel_Type hotel_type_class_${i}"  data-placeholder="Choose ...">
                            <option value="">Choose ...</option>
                            <option attr="4" value="Quad">Quad</option>
                            <option attr="3" value="Triple">Triple</option>
                            <option attr="2" value="Double">Double</option>
                        </select>
                        <span title="Add Hotel Type" class="input-group-btn input-group-append">
                            <button class="btn btn-primary bootstrap-touchspin-up" data-bs-toggle="modal" data-bs-target="#hotel-type-modal" type="button">+</button>
                        </span>
                    </div>
                    
                </div>
                <div class="col-xl-2"><label for="">Quantity</label><input type="text" id="simpleinput" name="acc_qty[]" class="form-control acc_qty_class_${i}"></div>
                
                <div class="col-xl-2">
                <label for="">Pax</label>
                <input type="text" id="simpleinput" name="acc_pax[]" class="form-control acc_pax_class_${i}" readonly>
                </div>
                <div class="col-xl-3">
                <label for="">Price Per Person/Night</label>
                <div class="input-group">
                    <span class="input-group-btn input-group-append">
                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                         
                        </a>
                    </span>
                    <input type="text" id="makkah_acc_price_${i}" onchange="makkah_acc_price_funs(${i})" value="" name="acc_price[]" class="form-control makkah_acc_price_class_${i}">
                </div>
                
                </div>
                
                <div class="col-xl-3"><label for="">Total Amount/Per Person</label>
                 <div class="input-group">
                    <span class="input-group-btn input-group-append">
                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                          
                        </a>
                    </span>
                    <input readonly type="text"  id="makkah_acc_total_amount_${i}"  name="acc_total_amount[]" class="form-control makkah_acc_total_amount_class_${i}">
                </div>
                </div>
                
                <div id="append_add_accomodation_${i}"></div>
                <div class="mt-2"><a href="javascript:;" onclick="add_more_accomodation_req_invoice(${i})"  id="" class="btn btn-info" style="float: right;"> + Add More </a></div>
                
                
                  <div class="col-xl-12">
                     <div class="mb-3">
                     
                     
                     
                     
                      <label for="simpleinput" class="form-label">Room Amenities</label>
                      <textarea name="hotel_whats_included[]" class="form-control" id="" cols="10" rows="0"></textarea>
                      
                     </div>
                  </div>
                
                <div class="col-xl-12 d-none"><label for="">Image</label><input type="file"  id=""  name="accomodation_image${j}[]" class="form-control" multiple></div>
                </div></div>`;
      
      
            var data_cost=`<div class="row" id="${i}">
                            <input type="text" name="hotel_name_markup[]" hidden>
                            <div class="col-xl-3">
                                <h4 class="" id="">Accomodation Cost ${decodeURI_city[i-1]}</h4>
                            </div>
                            <div class="col-xl-9"></div>
                            
                        
                         <div class="col-xl-3">
                                
                  <input type="text" id="hotel_acc_type_${i}" readonly="" name="room_type[]" class="form-control id_cot">
                        </div>
                         <div class="col-xl-3">
                            <div class="input-group">
                                <input type="text" id="hotel_acc_price_${i}" readonly="" name="without_markup_price[]" class="form-control">
                                <span class="input-group-btn input-group-append">
                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                
                                    </a>
                                </span>
                            </div>
                        </div>
                         <div class="col-xl-2">
                                 
                                  <select name="markup_type[]" onchange="hotel_markup_type(${i})" id="hotel_markup_types_${i}" class="form-control">
                                        <option value="">Markup Type</option>
                                        <option value="%">Percentage</option>
                                        <option value="<?php echo $currency; ?>">Fixed Amount</option>
                                  </select>
                  
                        </div>
                        <div class="col-xl-2">
                                 
                 <input type="hidden" id="" name="" class="form-control">
                 
                 <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                <input type="text"  class="form-control" id="hotel_markup_${i}" name="markup[]">
                <span class="input-group-btn input-group-append">
                    <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="hotel_markup_mrk_${i}">%</div></button>
                    </span>
                    </div>
                 
                 
                 
                        </div>
                        <div class="col-xl-2">
                            <div class="input-group">
                                <input type="text" id="hotel_markup_total_${i}" name="markup_price[]" class="form-control id_cot">
                                <span class="input-group-btn input-group-append">
                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                
                                    </a>
                                </span>
                            </div> 
                        </div>
                        </div>`;
      
          
            $("#append_accomodation_data_cost").append(data_cost);
          
            $("#append_accomodation").append(data);
            
            $('.acc_qty_class_'+i+'').on('change',function(){
                
                var acc_qty_class = $(this).val();
                // console.log(acc_qty_class);
                var hotel_type = $('.hotel_type_class_'+i+'').find('option:selected').attr('attr');
                // console.log(hotel_type);
                var mult = parseFloat(acc_qty_class) * parseFloat(hotel_type);
                // console.log(mult);
                $('.acc_pax_class_'+i+'').val(mult);
                
            });
            
            j = j + 1;
        }
    
        var select_ct =$(".select_ct").val();
        
        var count_1 = select_ct.length;
        
        for (let i = 1; i <= count_1; i++) {
            // console.log(i);
          var data1 = `<div class="mb-2" style="border:1px solid #ced4da;padding: 20px 20px 20px 20px;"><h4>City #${i} </h4><div class="row"><div class="col-xl-3"><label for="">Hotel Name</label><input type="text" id="simpleinput" name="acc_hotel_name[]" class="form-control">
        </div><div class="col-xl-3"><label for="">Check In</label><input type="date" id="simpleinput" name="acc_check_in[]" class="form-control">
        </div><div class="col-xl-3"><label for="">Check Out</label><input type="date" id="simpleinput" name="acc_check_out[]" class="form-control"></div><div class="col-xl-3"><label for="">No Of Nights</label><input type="text" id="nights" name="acc_no_of_nightst[]" class="form-control"></div><div class="col-xl-3"><label for="">Room Type</label>
        <select name="acc_type[]" id="property_city" class="form-control"  data-placeholder="Choose ..."><option value="">Choose ...</option><option value="Quad">Quad</option><option value="Triple">Triple</option><option value="Double">Double</option></select></div>
        <div class="col-xl-3"><label for="">Quantity</label><input type="text" id="simpleinput" name="acc_qty[]" class="form-control"></div>
        <div class="col-xl-3"><label for="">Pax</label><input type="text" id="simpleinput" name="acc_pax[]" class="form-control"></div><div class="col-xl-3"><label for="">Price</label>
        <input type="text" id="simpleinput" name="acc_price[]" class="form-control"></div><div class="col-xl-3"><label for="">Currency</label><select name="acc_currency[]" id="property_city" class="form-control"><option value="">Choose ...</option><option value="SAR">SAR</option><option value="Dollar">Dollar</option><option value="Pound">Pound</option></select></div><div class="col-xl-3"><label for="">Comission</label><input type="text" id="simpleinput" name="acc_commision[]" class="form-control"></div><div class="col-xl-3"><label for="">Sale Price</label><input type="text" id="simpleinput" name="acc_sale_Porice[]" class="form-control"></div><div class="col-xl-3"><label for="">Total Amount</label><input type="text" id="simpleinput" name="acc_total_amount[]" class="form-control"></div>
        <div id="append_add_accomodations_${i}"></div><div class="mt-2"><a href="javascript:;" onclick="add_more_accomodations(${i})"  id="" class="btn btn-info" style="float: right;"> + Add More </a></div></div></div>`;
          $("#append_accomodation").append(data1);   
          
        }
    
        $("#select_accomodation").slideToggle();
    });

    $("#add_hotel_accomodation").on('click',function(){
        
        $('#tour_location_city').removeAttr('value');
        $('#packages_get_city').removeAttr('value');
        
        var city_No = $('#city_No').val();
        if(city_No > 0){
            
            $("#append_accomodation_data_cost1").empty();
            $("#append_accomodation_data_cost").empty();
            $("#append_accomodation").empty();
            
            var packages_get_city = $('#city_No').val();
            
            var j = 0;
            for (let i = 1; i <= city_No; i++) {
                var data = `<div class="mb-2" style="border:1px solid #ced4da;padding: 20px 20px 20px 20px;" id="del_hotel${i}">
                                
                                <h4>
                                    City #${i}
                                </h4> 
                                <div class="row" style="padding-bottom: 25px;">
                                    
                                    <div class="col-xl-3">
                                        <label for="">Select City</label>
                                        <select type="text" id="property_city_new${i}" onchange="put_tour_location(${i})" name="hotel_city_name[]" class="form-control property_city_new"></select>
                                    </div>
                    
                                    <div class="col-xl-3">
                                        <label for="">Hotel Name</label>
                                        
                                        
                                        <input type="text" id="switch_hotel_name${i}" name="switch_hotel_name[]" value="1" style="display:none">
                                        
                                        <div class="input-group" id="add_hotel_div${i}">
                                            <input type="text" onchange="hotel_fun(${i})" id="acc_hotel_name_${i}" name="acc_hotel_name[]" class="form-control acc_hotel_name_class_${i}">
                                        </div>
                                        <a style="float: right;font-size: 10px;width: 102px;" onclick="select_hotel_btn(${i})" class="btn btn-primary select_hotel_btn${i}">
                                            SELECT HOTEL
                                        </a>
                                        
                                        <div class="input-group" id="select_hotel_div${i}" style="display:none">
                                            <select onchange="get_room_types(${i})" id="acc_hotel_name_${i}" name="acc_hotel_name_select[]" class="form-control other_Hotel_Name_New acc_hotel_name_class_${i} get_room_types_${i}">
                                                <option value="">Select hotel...</option>`;
                                                
                                                for(let abcd = 0; abcd < user_hotels.length ; abcd++){
                                                    data+=`<option attr_ID='${user_hotels[abcd]['id']}' value='${user_hotels[abcd]['property_name']}'>${user_hotels[abcd]['property_name']}</option>`;
                                                }
                                        
                                    data+= `</select>
                                            <span title="Add Hotel Name" class="input-group-btn input-group-append">
                                                <button class="btn btn-primary bootstrap-touchspin-up" data-bs-toggle="modal" data-bs-target="#hotel-name-new-modal" type="button">+</button>
                                            </span>
                                        </div>
                                        <a style="display:none;float: right;font-size: 10px;width: 102px;" onclick="add_hotel_btn(${i})" class="btn btn-primary add_hotel_btn${i}">
                                            ADD HOTEL
                                        </a>
                                        
                                    </div>
                                    
                                    <div class="col-xl-3"><label for="">Check In</label><input type="date" id="makkah_accomodation_check_in_${i}" name="acc_check_in[]" class="form-control date makkah_accomodation_check_in_class_${i}">
                                    </div><div class="col-xl-3"><label for="">Check Out</label><input type="date" id="makkah_accomodation_check_out_date_${i}"  name="acc_check_out[]" onchange="makkah_accomodation_check_out(${i})"  class="form-control date makkah_accomodation_check_out_date_class_${i}"></div>
                                    
                                    <div class="col-xl-2"><label for="">No Of Nights</label>
                                    <input readonly type="text" id="acomodation_nights_${i}" name="acc_no_of_nightst[]" class="form-control acomodation_nights_class_${i}"></div>
                                    
                                    <input readonly type="hidden" id="acc_nights_key_${i}" value="${i}" class="form-control">
                                    
                                    
                                    <div class="col-xl-3"><label for="">Room Type</label>
                                        
                                        <div class="input-group hotel_type_add_div_${i}">
                                            <select onchange="hotel_type_fun(${i})" name="acc_type[]" id="hotel_type_${i}" class="form-control other_Hotel_Type hotel_type_class_${i}"  data-placeholder="Choose ...">
                                                <option value="">Choose ...</option>
                                                <option attr="4" value="Quad">Quad</option>
                                                <option attr="3" value="Triple">Triple</option>
                                                <option attr="2" value="Double">Double</option>
                                            </select>
                                            <!-- span title="Add Hotel Type" class="input-group-btn input-group-append">
                                                <button class="btn btn-primary bootstrap-touchspin-up" data-bs-toggle="modal" data-bs-target="#hotel-type-modal" type="button">+</button>
                                            </span --!>
                                        </div>
                                        
                                        <div class="input-group hotel_type_select_div_${i}" style="display:none">
                                            <select onchange="hotel_type_fun(${i})" name="acc_type[]" id="hotel_type_${i}" class="form-control other_Hotel_Type hotel_type_class_${i} hotel_type_select_class_${i}"  data-placeholder="Choose ...">
                                                <option value="">Choose ...</option>
                                                <option attr="4" value="Quad">Quad</option>
                                                <option attr="3" value="Triple">Triple</option>
                                                <option attr="2" value="Double">Double</option>
                                            </select>
                                        </div>
                                        
                                    </div>
                                    <div class="col-xl-2"><label for="">Quantity</label><input type="text" id="simpleinput" name="acc_qty[]" class="form-control acc_qty_class_${i}"></div>
                                    
                                    <div class="col-xl-2">
                                    <label for="">Pax</label>
                                    <input type="text" id="simpleinput" name="acc_pax[]" class="form-control acc_pax_class_${i}" readonly>
                                    </div>
                                    <div class="col-xl-3">
                                    <label for="">Meal Type</label>
                                    <select name="hotel_meal_type[]" id="hotel_meal_type" class="form-control" data-placeholder="Choose ...">
                                    <option value="">Choose ...</option>
                                    <option value="Room only">Room only</option>
                                    <option value="Breakfast">Breakfast</option>
                                    <option value="Lunch">Lunch</option>
                                    <option value="Dinner">Dinner</option>
                                </select>
                            </div>
                                    <div class="col-xl-3" style="display:none;">
                                    <label for="">Price Per Room/Night</label>
                                    <div class="input-group">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                             
                                            </a>
                                        </span>
                                        <input type="text" id="makkah_acc_price_${i}" onchange="makkah_acc_price_funs(${i})" value="" name="acc_price[]" class="form-control makkah_acc_price_class_${i}">
                                    </div>
                    
                                    </div>
                                    
                                    <div class="col-xl-3" style="display:none;">
                                    <label for="">Price Per Person/Night</label>
                                    <div class="input-group">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                             
                                            </a>
                                        </span>
                                        <input type="text" id="makkah_acc_price_${i}" onchange="makkah_acc_price_funs(${i})" value="" name="acc_price[]" class="form-control makkah_acc_price_class_${i}">
                                    </div>
                    
                                    </div>
                                    
                                    <div class="col-xl-3" style="display:none;"><label for="">Total Amount/Per Person</label>
                                     <div class="input-group">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                             
                                            </a>
                                        </span>
                                        <input readonly type="text"  id="makkah_acc_total_amount_${i}"  name="acc_total_amount[]" class="form-control makkah_acc_total_amount_class_${i}">
                                    </div>
                                    </div>
                    
                                    <div id="append_add_accomodation_${i}"></div>
                                    <div class="mt-2"><a href="javascript:;" onclick="add_more_accomodation_req_invoice(${i})"  id="" class="btn btn-info" style="float: right;"> + Add More </a></div>
                                    
                                    <div class="col-xl-12">
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Room Amenities</label>
                                            <textarea name="hotel_whats_included[]" class="form-control" id="" cols="10" rows="0"></textarea>
                                          
                                        </div>
                                    </div>
                    
                                    <div class="col-xl-12 d-none"><label for="">Image</label><input type="file"  id=""  name="accomodation_image${j}[]" class="form-control" multiple></div>
                                    
                                    <div class="mt-2">
                                        <a href="javascript:;" onclick="remove_hotels(${i})" id="${i}" class="btn btn-danger" style="float: right;"> 
                                            Delete Hotel
                                        </a>
                                    </div>
                                    
                                </div>
                            </div>`;
      
      
                var data_cost=`<div class="row" id="costing_acc${i}" style="margin-bottom:20px;">
                
                                    <input type="hidden" id="hotel_Type_Costing" name="markup_Type_Costing[]" value="hotel_Type_Costing" class="form-control">
                                    
                                    <input type="text" name="hotel_name_markup[]" hidden>
                                    <div class="col-xl-3">
                                    </div>
                                    <div class="col-xl-9"></div>
                                
                            
                                    <div class="col-xl-3">
                                    
                                    <input type="text" id="hotel_acc_type_${i}" readonly="" name="room_type[]" class="form-control id_cot">
                                        </div>
                                         <div class="col-xl-3">
                                            <div class="input-group">
                                                <input type="text" id="hotel_acc_price_${i}" readonly="" name="without_markup_price[]" class="form-control">
                                                <span class="input-group-btn input-group-append">
                                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                               
                                                    </a>
                                                </span>
                                            </div>
                                        </div>
                                         <div class="col-xl-2">
                                                 
                                                  <select name="markup_type[]" onchange="hotel_markup_type(${i})" id="hotel_markup_types_${i}" class="form-control">
                                                        <option value="">Markup Type</option>
                                                        <option value="%">Percentage</option>
                                                        <option value="<?php echo $currency; ?>">Fixed Amount</option>
                                                  </select>
                                  
                                        </div>
                                        <div class="col-xl-2">
                                            <input type="hidden" id="" name="" class="form-control">
                                            <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                <input type="text"  class="form-control" id="hotel_markup_${i}" name="markup[]">
                                                <span class="input-group-btn input-group-append">
                                                <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="hotel_markup_mrk_${i}">%</div></button>
                                                </span>
                                            </div>
                                        </div>
                                    <div class="col-xl-2">
                                        <div class="input-group">
                                            <input type="text" id="hotel_markup_total_${i}" name="markup_price[]" class="form-control id_cot">
                                            <span class="input-group-btn input-group-append">
                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                           
                                                </a>
                                            </span>
                                        </div> 
                                    </div>
                                </div>`;
      
                $("#append_accomodation_data_cost").append(data_cost);
              
                $("#append_accomodation").append(data);
                
                $('.acc_qty_class_'+i+'').on('change',function(){
                    
                    var switch_hotel_name = $('#switch_hotel_name'+i+'').val()
                    if(switch_hotel_name == 1){
                        var acc_qty_class = $(this).val();
                        var hotel_type = $('.hotel_type_class_'+i+'').find('option:selected').attr('attr');
                        var mult = parseFloat(acc_qty_class) * parseFloat(hotel_type);
                        $('.acc_pax_class_'+i+'').val(mult);
                    }else{
                        var acc_qty_class = $(this).val();
                        var hotel_type = $('.hotel_type_select_class_'+i+'').find('option:selected').attr('attr');
                        var mult = parseFloat(acc_qty_class) * parseFloat(hotel_type);
                        $('.acc_pax_class_'+i+'').val(mult);
                    }
                    
                });
                
                var country = $('#property_country').val();
            
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
                        "id": country,
                    },
                    success: function(result){
                        // console.log('cites is call now');
                        // console.log(result);
                        $('.property_city_new').html(result);
                    },
                    error:function(error){
                        // console.log(error);
                    }
                });
                
                j = j + 1;
                
                var places_D1 = new google.maps.places.Autocomplete(
                    document.getElementById('acc_hotel_name_'+i+'')
                );
                
                google.maps.event.addListener(places_D1, "place_changed", function () {
                    var places_D1 = places_D1.getPlace();
                    // console.log(places_D1);
                    var address = places_D1.formatted_address;
                    var latitude = places_D1.geometry.location.lat();
                    var longitude = places_D1.geometry.location.lng();
                    var latlng = new google.maps.LatLng(latitude, longitude);
                    var geocoder = (geocoder = new google.maps.Geocoder());
                    geocoder.geocode({ latLng: latlng }, function (results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            if (results[0]) {
                                var address = results[0].formatted_address;
                                var pin = results[0].address_components[
                                    results[0].address_components.length - 1
                                ].long_name;
                                var country =  results[0].address_components[
                                    results[0].address_components.length - 2
                                  ].long_name;
                                var state = results[0].address_components[
                                        results[0].address_components.length - 3
                                    ].long_name;
                                var city = results[0].address_components[
                                        results[0].address_components.length - 4
                                    ].long_name;
                                var country_code = results[0].address_components[
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
                
            }
        
            $("#select_accomodation").slideToggle();
            
        }
        else{
            alert("Select Hotels Quantity");
        }
        
    });
    
    function select_hotel_btn(id){
        // var switch_hotel_name = $('#switch_hotel_name'+id+'').val();
        // console.log('before : '+switch_hotel_name);
        $('#switch_hotel_name'+id+'').val(0);
        $('#add_hotel_div'+id+'').css('display','none');
        $('#select_hotel_div'+id+'').css('display','');
        $('.select_hotel_btn'+id+'').css('display','none');
        $('.add_hotel_btn'+id+'').css('display','');
        $('.hotel_type_add_div_'+id+'').css('display','none');
        $('.hotel_type_select_div_'+id+'').css('display','');
        // var switch_hotel_name = $('#switch_hotel_name'+id+'').val();
        // console.log('after : '+switch_hotel_name);
    }
    
    function add_hotel_btn(id){
        // var switch_hotel_name = $('#switch_hotel_name'+id+'').val();
        // console.log('before : '+switch_hotel_name);
        $('#switch_hotel_name'+id+'').val(1);
        $('#add_hotel_div'+id+'').css('display','');
        $('#select_hotel_div'+id+'').css('display','none');
        $('.add_hotel_btn'+id+'').css('display','none');
        $('.select_hotel_btn'+id+'').css('display','');
        $('.hotel_type_add_div_'+id+'').css('display','');
        $('.hotel_type_select_div_'+id+'').css('display','none');
        $('.hotel_type_select_class_'+id+'').empty();
        // var switch_hotel_name = $('#switch_hotel_name'+id+'').val();
        // console.log('after : '+switch_hotel_name);
    }
    
    function get_room_types(id){
        ids = $('.get_room_types_'+id+'').find('option:selected').attr('attr_ID');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ url('/get_rooms_list') }}",
            method: 'get',
            data: {
                "_token": "{{ csrf_token() }}",
                "id": ids,
            },
            success: function(result){
                var user_rooms = result['user_rooms'];
                if(user_rooms !== null && user_rooms !== ''){
                    $('.hotel_type_add_div_'+id+'').css('display','none');
                    $('.hotel_type_select_div_'+id+'').css('display','');
                    $('.hotel_type_select_class_'+id+'').empty();
                    
                    $.each(user_rooms, function(key, value) {
                        var room_type_id = value.room_type_id
                        if(room_type_id == 1){
                            $('.hotel_type_select_class_'+id+'').append('<option attr="1" value="Single">Single</option>');
                        }
                        else if(room_type_id == 2){
                            $('.hotel_type_select_class_'+id+'').append('<option attr="2" value="Double">Double</option>');
                        }
                        else if(room_type_id == 3){
                            $('.hotel_type_select_class_'+id+'').append('<option attr="3" value="Triple">Triple</option>');
                        }
                        else if(room_type_id == 4){
                            $('.hotel_type_select_class_'+id+'').append('<option attr="4" value="Quad">Quad</option>');
                        }
                        else{
                            $('.hotel_type_select_class_'+id+'').append('<option attr="0" value="0">Room Type ID Not Found</option>');
                        }
                    });   
                }else{
                    alert('Room are Empty');
                }
            },
        });
    }
    
    $('#submitForm_hotel_name_New').on('click',function(e){
        e.preventDefault();
        let form_submit_data = $("#form_submit_data").text($("#form_submit_id").serialize());
        console.log('form_submit_data : '+form_submit_data);
        $.ajax({
            url: "hotel_manger/add_hotel_sub_ajax",
            type:"POST",
            data:{
                "_token": "{{ csrf_token() }}",
                form_submit_data:form_submit_data,
            },
            success:function(response){
                if(response){
                    console.log(response);
                    // var data1 = response
                    // console.log(data1);
                    // var data = data1['hotel_Name_get'];
                    // console.log(data);
                    // $(".other_Hotel_Name").empty();
                    // $.each(data, function(key, value) {
                    //     var other_Hotel_Name_Data = `<option attr="${value.other_Hotel_Name}" value="${value.other_Hotel_Name}"> ${value.other_Hotel_Name}</option>`;
                    //     $(".other_Hotel_Name").append(other_Hotel_Name_Data);
                    // });
                    // alert('Other Hotel Name Added SuccessFUl!');
                }
                $('#success-message').text(response.success);
            },
        });
    });
    
    var city_No1        = $('#city_No1').val();
    var img_Counter1    = $('#img_Counter1').val();
    var j               = img_Counter1;
    $("#add_hotel_accomodation_edit").on('click',function(){
        
        city_No1 = parseFloat(city_No1) + 1;
        $('#city_No1').val(city_No1);
        
        var city_No = $('#city_No').val();
        var i       = parseFloat(city_No) + 1;
        $('#city_No').val(i);
        
        var data = `<div class="mb-2" style="border:1px solid #ced4da;padding: 20px 20px 20px 20px;" id="del_hotel${i}">
                        <h4>
                            City #${i}
                        </h4> 
                        <div class="row" style="padding-bottom: 25px;">
                            <div class="col-xl-3">
                                <label for="">Select City</label>
                                <select type="text" id="property_city_new${i}" onchange="put_tour_location_else(${i})" name="hotel_city_name[]" class="form-control property_city_new${i}"></select>
                            </div>
            
                            <div class="col-xl-3">
                                <label for="">Hotel Name</label>
                                <div class="input-group">
                                    
                                    <input type="text" onchange="hotel_fun(${i})" id="acc_hotel_name_${i}" name="acc_hotel_name[]" class="form-control acc_hotel_name_class_${i}">
                                </div>
                            </div>
                            
                            <div class="col-xl-3"><label for="">Check In</label><input type="date" id="makkah_accomodation_check_in_${i}" name="acc_check_in[]" class="form-control date makkah_accomodation_check_in_class_${i}">
                            </div><div class="col-xl-3"><label for="">Check Out</label>
                            <input type="date" id="makkah_accomodation_check_out_date_${i}" name="acc_check_out[]" onchange="makkah_accomodation_check_out(${i})"  class="form-control date makkah_accomodation_check_out_date_class_${i}"></div>
                            
                            <div class="col-xl-3"><label for="">No Of Nights</label>
                            <input readonly type="text" id="acomodation_nights_${i}" value="" name="acc_no_of_nightst[]" class="form-control acomodation_nights_class_${i}"></div>
                            
                            <input readonly type="hidden" id="acc_nights_key_${i}" value="${i}" class="form-control">
                            
                            
                            <div class="col-xl-3"><label for="">Room Type</label>
                                <div class="input-group">
                                    <select onchange="hotel_type_fun(${i})" name="acc_type[]" id="hotel_type_${i}" class="form-control other_Hotel_Type${i} hotel_type_class_${i}"  data-placeholder="Choose ...">
                                        <option value="">Choose ...</option>
                                        <option attr="4" value="Quad">Quad</option>
                                        <option attr="3" value="Triple">Triple</option>
                                        <option attr="2" value="Double">Double</option>
                                    </select>
                                    <span title="Add Hotel Type" class="input-group-btn input-group-append">
                                        <button class="btn btn-primary bootstrap-touchspin-up" data-bs-toggle="modal" data-bs-target="#hotel-type-modal" type="button">+</button>
                                    </span>
                                </div>
                            </div>
                            <div class="col-xl-3"><label for="">Quantity</label><input type="text" id="simpleinput" name="acc_qty[]" class="form-control acc_qty_class_${i}"></div>
                            
                            <div class="col-xl-3">
                            <label for="">Pax</label>
                            <input type="text" id="simpleinput" name="acc_pax[]" class="form-control acc_pax_class_${i}" readonly>
                            </div>
                            <div class="col-xl-3">
                            <label for="">Price Per Person/Night</label>
                            <div class="input-group">
                                <span class="input-group-btn input-group-append">
                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                               
                                    </a>
                                </span>
                                <input type="text" id="makkah_acc_price_${i}" onchange="makkah_acc_price_funs(${i})" value="" name="acc_price[]" class="form-control makkah_acc_price_class_${i}">
                            </div>
            
                            </div>
                            
                            <div class="col-xl-3"><label for="">Total Amount/Per Person</label>
                             <div class="input-group">
                                <span class="input-group-btn input-group-append">
                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                               
                                    </a>
                                </span>
                                <input readonly type="text"  id="makkah_acc_total_amount_${i}"  name="acc_total_amount[]" class="form-control makkah_acc_total_amount_class_${i}">
                            </div>
                            </div>
            
                            <div id="append_add_accomodation_${i}"></div>
                            <div class="mt-2"><a href="javascript:;" onclick="add_more_accomodation_edit(${i})"  id="" class="btn btn-info" style="float: right;"> + Add More </a></div>
                            
                            <div class="col-xl-12">
                                <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Room Amenities</label>
                                    <textarea name="hotel_whats_included[]" class="form-control" id="" cols="10" rows="0"></textarea>
                                  
                                </div>
                            </div>
            
                            <div class="col-xl-12 d-none">
                                <label for="">Image</label>
                                <input type="file"  id=""  name="accomodation_image${j}[]" class="form-control accomodation_image_edit${j}" multiple>
                                
                                <div class="row" style="padding:5px" style="" id="dvPreview"></div>
                            </div>
                            
                            
                            <div class="mt-2">
                                <a href="javascript:;" onclick="remove_hotels(${i})" id="${i}" class="btn btn-danger" style="float: right;"> 
                                    Delete Hotel
                                </a>
                            </div>
                            
                        </div>
                    </div>`;


        var data_cost=`<div class="row" id="costing_acc${i}" style="margin-bottom:20px;">
                            <input type="hidden" id="hotel_Type_Costing" name="markup_Type_Costing[]" value="hotel_Type_Costing" class="form-control">
                            <input type="text" name="hotel_name_markup[]" hidden>
                            <div class="col-xl-3">
                            </div>
                            <div class="col-xl-9"></div>
                        
                    
                            <div class="col-xl-3">
                            
                            <input type="text" id="hotel_acc_type_${i}" readonly="" name="room_type[]" class="form-control id_cot">
                                </div>
                                 <div class="col-xl-3">
                                    <div class="input-group">
                                        <input type="text" id="hotel_acc_price_${i}" readonly="" name="without_markup_price[]" class="form-control">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                       
                                            </a>
                                        </span>
                                    </div>
                                </div>
                                 <div class="col-xl-2">
                                         
                                          <select name="markup_type[]" onchange="hotel_markup_type(${i})" id="hotel_markup_types_${i}" class="form-control">
                                                <option value="">Markup Type</option>
                                                <option value="%">Percentage</option>
                                                <option value="<?php echo $currency; ?>">Fixed Amount</option>
                                          </select>
                          
                                </div>
                                <div class="col-xl-2">
                                    <input type="hidden" id="" name="" class="form-control">
                                    <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                        <input type="text"  class="form-control" id="hotel_markup_${i}" name="markup[]">
                                        <span class="input-group-btn input-group-append">
                                        <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="hotel_markup_mrk_${i}">%</div></button>
                                        </span>
                                    </div>
                                </div>
                            <div class="col-xl-2">
                                <div class="input-group">
                                    <input type="text" id="hotel_markup_total_${i}" name="markup_price[]" class="form-control id_cot">
                                    <span class="input-group-btn input-group-append">
                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                   
                                        </a>
                                    </span>
                                </div> 
                            </div>
                        </div>`;
      
                $("#append_accomodation_data_cost").append(data_cost);
              
                $("#append_accomodation").append(data);
                
                $('.acc_qty_class_'+i+'').on('change',function(){
                    
                    var acc_qty_class = $(this).val();
                    // console.log(acc_qty_class);
                    var hotel_type = $('.hotel_type_class_'+i+'').find('option:selected').attr('attr');
                    // console.log(hotel_type);
                    var mult = parseFloat(acc_qty_class) * parseFloat(hotel_type);
                    // console.log(mult);
                    $('.acc_pax_class_'+i+'').val(mult);
                    
                });
                
                var country = $('#property_country').val();
            
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
                        "id": country,
                    },
                    success: function(result){
                        // console.log('cites is call now');
                        // console.log(result);
                        $('.property_city_new'+i+'').html(result);
                    },
                    error:function(error){
                        // console.log(error);
                    }
                });
                
                $('.accomodation_image_edit'+j+'').change(function () {
                    var c = $('#del_counter1').val();
                    if (typeof (FileReader) != "undefined") {
                        var dvPreview = $("#dvPreview");
                        dvPreview.html("");
                        var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.gif|.png|.bmp)$/;
                        $($(this)[0].files).each(function () {
                            var file = $(this);
                            if (regex.test(file[0].name.toLowerCase())) {
                                var reader = new FileReader();
                                reader.onload = function (e) {
                                    
                                    var img = $("<img />");
                                    img.attr("style", "height:150px;width:233px;margin-bottom: 10px");
                                    img.attr("src", e.target.result);
                                    img.attr("id", j);
                                    
                                    var img_Name = e.target.result;
                                    
                                    // console.log(img);
                                    // console.log(e);
                                    
                                    var befor_Img = `<div class="col-md-3" id="accImg${c}" style="text-align: center;">
                                                        <input type="text" name="accomodation_image_else${j}[]" class="form-control" value="${img_Name}" readonly hidden>
                                                    </div>`;
                                    
                                    var after_Img = `<button class="btn btn-danger" type="button" onclick="remove_acc_img(${c})" style="margin-bottom: 10px">Delete</button>`;
                                    
                                    dvPreview.append(befor_Img)
                                    
                                    var accImg = $('#accImg'+c+'');
                                    
                                    accImg.append(img);
                                    accImg.append(after_Img);
                                    
                                    // var final_Append = `<div>${img}<br>${after_Img}</div>`;
                                    // accImg.append(final_Append);
                                    
                                    c = parseFloat(c)+1;
                                }
                                reader.readAsDataURL(file[0]);
                            } else {
                                alert(file[0].name + " is not a valid image file.");
                                dvPreview.html("");
                                return false;
                            }
                        });
                    } else {
                        alert("This browser does not support HTML5 FileReader.");
                    }
                });
                
                j = parseFloat(j) + 1;
                
                $.ajax({    
                    type: "GET",
                    url: "get_other_Hotel_Name",             
                    dataType: "html",                  
                    success: function(data){
                        var data1 = JSON.parse(data);
                        $('.other_Hotel_Name'+i+'').empty();
                        $('.other_Hotel_Type'+i+'').empty();
                        $.each(data1['hotel_Name'], function(key, data2) {
                            // console.log(data2['other_Hotel_Name']);
                            var other_Hotel_Name_Data = `<option attr="${data2.other_Hotel_Name}" value="${data2.other_Hotel_Name}"> ${data2.other_Hotel_Name}</option>`;
                            $('.other_Hotel_Name'+i+'').append(other_Hotel_Name_Data);
                        });
                        $.each(data1['hotel_Type'], function(key, data3) {
                        	var other_Hotel_Name_Type = `<option attr="${data3.id}" value="${data3.other_Hotel_Type}"> ${data3.other_Hotel_Type}</option>`;
                            $('.other_Hotel_Type'+i+'').append(other_Hotel_Name_Type);
                        });
                        
                    }
                });
                
                var places_D1 = new google.maps.places.Autocomplete(
                    document.getElementById('acc_hotel_name_'+i+'')
                );
                
                google.maps.event.addListener(places_D1, "place_changed", function () {
                    var places_D1 = places_D1.getPlace();
                    // console.log(places_D1);
                    var address = places_D1.formatted_address;
                    var latitude = places_D1.geometry.location.lat();
                    var longitude = places_D1.geometry.location.lng();
                    var latlng = new google.maps.LatLng(latitude, longitude);
                    var geocoder = (geocoder = new google.maps.Geocoder());
                    geocoder.geocode({ latLng: latlng }, function (results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            if (results[0]) {
                                var address = results[0].formatted_address;
                                var pin = results[0].address_components[
                                    results[0].address_components.length - 1
                                ].long_name;
                                var country =  results[0].address_components[
                                    results[0].address_components.length - 2
                                  ].long_name;
                                var state = results[0].address_components[
                                        results[0].address_components.length - 3
                                    ].long_name;
                                var city = results[0].address_components[
                                        results[0].address_components.length - 4
                                    ].long_name;
                                var country_code = results[0].address_components[
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
                
            $("#select_accomodation").slideToggle();
        
    });

    $("#accomodation_edit").on('click',function(){
        
        $("#append_accomodation_data_cost1").empty();
        $("#append_accomodation_data_cost").empty();
        $("#append_accomodation").empty();
        var packages_get_city = $('#packages_get_city').val();
        var decodeURI_city = JSON.parse(packages_get_city);
        var city_slc =$(".city_slc").val();
        var count = city_slc.length;
        var j=0;
        for (let i = 1; i <= count; i++) {
            
            var data = `<div class="mb-2" style="border:1px solid #ced4da;padding: 20px 20px 20px 20px;">
                            <h4>
                                City #${i} (${decodeURI_city[i-1]})
                                <i class="dripicons-information" style="font-size: 17px;" id="title_Icon_${i}"
                                    data-bs-toggle="tooltip" data-bs-placement="right" title="Fill all the information of City ${decodeURI_city[i-1]}">
                                </i>
                            </h4> 
                                <div class="row">
                                    
                                    <input type="hidden" name="hotel_city_name[]" id="hotel_city_name" value="${decodeURI_city[i-1]}"/>
                                <div class="col-xl-3">
                                    <label for="">Hotel Name</label>
                                    <div class="input-group">
                                        <select type="text" onchange="hotel_fun(${i})" id="acc_hotel_name_${i}" name="acc_hotel_name[]" class="form-control other_Hotel_Name acc_hotel_name_class_${i}">
                                        
                                        </select>
                                        <span title="Add Hotel Name" class="input-group-btn input-group-append">
                                            <button class="btn btn-primary bootstrap-touchspin-up" data-bs-toggle="modal" data-bs-target="#hotel-name-modal" type="button">+</button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-xl-3"><label for="">Check In</label><input type="date" id="makkah_accomodation_check_in_${i}" name="acc_check_in[]" class="form-control date makkah_accomodation_check_in_class_${i}">
                                </div><div class="col-xl-3"><label for="">Check Out</label><input type="date" id="makkah_accomodation_check_out_date_${i}"  name="acc_check_out[]" onchange="makkah_accomodation_check_out(${i})"  class="form-control date makkah_accomodation_check_out_date_class_${i}"></div>
                                <div class="col-xl-3"><label for="">No Of Nights</label><input type="text" id="acomodation_nights_${i}" name="acc_no_of_nightst[]" class="form-control acomodation_nights_class_${i}"></div>
                                
                                <div class="col-xl-2"><label for="">Room Type</label>
                                    <div class="input-group">
                                        <select onchange="hotel_type_fun(${i})" name="acc_type[]" id="hotel_type_${i}" class="form-control other_Hotel_Type hotel_type_class_${i}"  data-placeholder="Choose ...">
                                            <option value="">Choose ...</option>
                                            <option attr="4" value="Quad">Quad</option>
                                            <option attr="3" value="Triple">Triple</option>
                                            <option attr="2" value="Double">Double</option>
                                        </select>
                                        <span title="Add Hotel Type" class="input-group-btn input-group-append">
                                            <button class="btn btn-primary bootstrap-touchspin-up" data-bs-toggle="modal" data-bs-target="#hotel-type-modal" type="button">+</button>
                                        </span>
                                    </div>
                                    
                                    </div>
                                    <div class="col-xl-2"><label for="">Quantity</label><input type="text" id="simpleinput" name="acc_qty[]" class="form-control acc_qty_class_${i}"></div>
                                    
                                    <div class="col-xl-2">
                                    <label for="">Pax</label>
                                    <input type="text" id="simpleinput" name="acc_pax[]" class="form-control acc_pax_class_${i}" readonly>
                                    </div>
                                    <div class="col-xl-3">
                                    <label for="">Price Per Person/Night</label>
                                    <div class="input-group">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                       
                                            </a>
                                        </span>
                                        <input type="text" id="makkah_acc_price_${i}" onchange="makkah_acc_price_funs(${i})" value="" name="acc_price[]" class="form-control makkah_acc_price_class_${i}">
                                    </div>
                                    
                                    </div>
                
                                    <div class="col-xl-3"><label for="">Total Amount/Per Person</label>
                                     <div class="input-group">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                       
                                            </a>
                                        </span>
                                        <input readonly type="text"  id="makkah_acc_total_amount_${i}"  name="acc_total_amount[]" class="form-control makkah_acc_total_amount_class_${i}">
                                    </div>
                                    </div>
                                    
                                    <div id="append_add_accomodation_${i}"></div>
                                    <div class="mt-2"><a href="javascript:;" onclick="add_more_accomodation(${i})"  id="" class="btn btn-info" style="float: right;"> + Add More </a></div>
                                    
                                    
                                      <div class="col-xl-12">
                                         <div class="mb-3">
                                         
                                         
                                         
                                         
                                          <label for="simpleinput" class="form-label">Room Amenities</label>
                                          <textarea name="hotel_whats_included[]" class="form-control" id="" cols="10" rows="0"></textarea>
                                          
                                         </div>
                                      </div>
                                    
                                    <div class="col-xl-12"><label for="">Image</label><input type="file"  id=""  name="accomodation_image${j}[]" class="form-control" multiple></div>
                                    </div></div>`;
      
        
            var data_cost=`<div class="row" id="${i}">
                                <input type="text" name="hotel_name_markup[]" hidden>
                                <div class="col-xl-3">
                                    <h4 class="" id="">Accomodation Cost ${decodeURI_city[i-1]}</h4>
                                </div>
                                <div class="col-xl-9"></div>
                                
                            
                                <div class="col-xl-3">
                                
                                <input type="text" id="hotel_acc_type_${i}" readonly="" name="room_type[]" class="form-control id_cot">
                                    </div>
                                     <div class="col-xl-3">
                                        <div class="input-group">
                                            <input type="text" id="hotel_acc_price_${i}" readonly="" name="without_markup_price[]" class="form-control">
                                            <span class="input-group-btn input-group-append">
                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                           
                                                </a>
                                            </span>
                                        </div>
                                </div>
                                 <div class="col-xl-2">
                                         
                                          <select name="markup_type[]" onchange="hotel_markup_type(${i})" id="hotel_markup_types_${i}" class="form-control">
                                                <option value="">Markup Type</option>
                                                <option value="%">Percentage</option>
                                                <option value="<?php echo $currency; ?>">Fixed Amount</option>
                                          </select>
                          
                                </div>
                                <div class="col-xl-2">
                                     
                                 <input type="hidden" id="" name="" class="form-control">
                                 
                                 <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                    <input type="text"  class="form-control" id="hotel_markup_${i}" name="markup[]">
                                    <span class="input-group-btn input-group-append">
                                <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="hotel_markup_mrk_${i}">%</div></button>
                                </span>
                                </div>
                 
                 
                 
                                </div>
                                <div class="col-xl-2">
                                    <div class="input-group">
                                        <input type="text" id="hotel_markup_total_${i}" name="markup_price[]" class="form-control id_cot">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                       
                                            </a>
                                        </span>
                                    </div> 
                                </div>
                            </div>`;
      
          
            $("#append_accomodation_data_cost").append(data_cost);
            $("#append_accomodation").append(data);
            $('.acc_qty_class_'+i+'').on('change',function(){
                
                var acc_qty_class = $(this).val();
                // console.log(acc_qty_class);
                var hotel_type = $('.hotel_type_class_'+i+'').find('option:selected').attr('attr');
                // console.log(hotel_type);
                var mult = parseFloat(acc_qty_class) * parseFloat(hotel_type);
                // console.log(mult);
                $('.acc_pax_class_'+i+'').val(mult);
                
            });
            
            j = j + 1;
        }
    
        var select_ct =$(".select_ct").val();
        
        var count_1 = select_ct.length;
        
        for (let i = 1; i <= count_1; i++) {
            // console.log(i);
          var data1 = `<div class="mb-2" style="border:1px solid #ced4da;padding: 20px 20px 20px 20px;"><h4>City #${i} </h4><div class="row"><div class="col-xl-3"><label for="">Hotel Name</label><input type="text" id="simpleinput" name="acc_hotel_name[]" class="form-control">
        </div><div class="col-xl-3"><label for="">Check In</label><input type="date" id="simpleinput" name="acc_check_in[]" class="form-control">
        </div><div class="col-xl-3"><label for="">Check Out</label><input type="date" id="simpleinput" name="acc_check_out[]" class="form-control"></div><div class="col-xl-3"><label for="">No Of Nights</label><input type="text" id="nights" name="acc_no_of_nightst[]" class="form-control"></div><div class="col-xl-3"><label for="">Room Type</label>
        <select name="acc_type[]" id="property_city" class="form-control"  data-placeholder="Choose ..."><option value="">Choose ...</option><option value="Quad">Quad</option><option value="Triple">Triple</option><option value="Double">Double</option></select></div>
        <div class="col-xl-3"><label for="">Quantity</label><input type="text" id="simpleinput" name="acc_qty[]" class="form-control"></div>
        <div class="col-xl-3"><label for="">Pax</label><input type="text" id="simpleinput" name="acc_pax[]" class="form-control"></div><div class="col-xl-3"><label for="">Price</label>
        <input type="text" id="simpleinput" name="acc_price[]" class="form-control"></div><div class="col-xl-3"><label for="">Currency</label><select name="acc_currency[]" id="property_city" class="form-control"><option value="">Choose ...</option><option value="SAR">SAR</option><option value="Dollar">Dollar</option><option value="Pound">Pound</option></select></div><div class="col-xl-3"><label for="">Comission</label><input type="text" id="simpleinput" name="acc_commision[]" class="form-control"></div><div class="col-xl-3"><label for="">Sale Price</label><input type="text" id="simpleinput" name="acc_sale_Porice[]" class="form-control"></div><div class="col-xl-3"><label for="">Total Amount</label><input type="text" id="simpleinput" name="acc_total_amount[]" class="form-control"></div>
        <div id="append_add_accomodations_${i}"></div><div class="mt-2"><a href="javascript:;" onclick="add_more_accomodations(${i})"  id="" class="btn btn-info" style="float: right;"> + Add More </a></div></div></div>`;
          $("#append_accomodation").append(data1);   
          
        }
    
        $("#select_accomodation").slideToggle();
    });

    $('#save_Accomodation').on('click',function(e){
        e.preventDefault();
        
        var select_ct =$("#property_city").val();
        var count_1 = select_ct.length;
        // console.log(count_1);
        for(i=0; i < count_1; i++){
            var acc_hotel_name = [];
            var id  = $(this).val();
            var hotel_city_name = $("#hotel_city_name").val();
            var acc_hotel_name1 = $('.acc_hotel_name_class_'+i+'').val();
            // console.log(acc_hotel_name1);
            acc_hotel_name.push(acc_hotel_name1);
            // console.log(acc_hotel_name);
            var acc_check_in = $('.makkah_accomodation_check_in_class_'+i+'').val();
            var acc_check_out = $('.makkah_accomodation_check_out_date_class_'+i+'').val();
            var acc_no_of_nights = $('.acomodation_nights_class_'+i+'').val();
            var acc_type = $('.hotel_type_class_'+i+'').val();
            var acc_qty = $('.acc_qty_class_'+i+'').val();
            var acc_pax = $('.acc_pax_class_'+i+'').val();
            var acc_price = $('.makkah_acc_price_class_'+i+'').val();
            var acc_total_amount = $('.makkah_acc_total_amount_class_'+i+'').val();
        }
        
        $.ajax({    
            type: 'POST',
            url: 'save_Accomodation/'+id,
            data:{
                '_token'                    : '{{ csrf_token() }}',
                'id'                        : id,
                'hotel_city_name'           : hotel_city_name,
                'acc_hotel_name'            : acc_hotel_name,
                'acc_check_in'              : acc_check_in,
                'acc_check_out'             : acc_check_out,
                'acc_no_of_nights'          : acc_no_of_nights,
                'acc_type'                  : acc_type,
                'acc_qty'                   : acc_qty,
                'acc_pax'                   : acc_pax,
                'acc_price'                 : acc_price,
                'acc_total_amount'          : acc_total_amount,
            },
            success: function(data){
                // console.log(data);
                // alert('Accomodation Details Saved SuccessFUl!');
            }
        });
    });
</script>


@stop