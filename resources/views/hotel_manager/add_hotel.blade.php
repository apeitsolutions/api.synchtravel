@extends('template/frontend/userdashboard/layout/default')
@section('content')


<div class="dashboard-content">
    
    <h4 style="color:#a30000">New Hotel</h4>
    <form action="{{ url('hotel_manger/add_hotel_sub') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12 mb-4">
                @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" style="background-color:#d4edda;" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

                @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" style="background-color:#f5cfcf;" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
            </div>
            <div class="col-md-8">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">GENERAL</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="facilities-tab" data-toggle="tab" href="#facilities" role="tab" aria-controls="facilities" aria-selected="false">FACILITIES</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="meta-info-tab" data-toggle="tab" href="#meta-info" role="tab" aria-controls="meta-info" aria-selected="false">META INFO</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="policy-tab" data-toggle="tab" href="#policy" role="tab" aria-controls="policy" aria-selected="false">POLICY</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">CONTACT</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="translate-tab" data-toggle="tab" href="#translate" role="tab" aria-controls="translate" aria-selected="false">TRANSLATE</a>
                    </li>
                </ul>
                <!-- Main Tab Contant Start -->
                <div class="tab-content" id="myTabContent">
                    <!-- General Tab Start -->
                    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                        <div class="row">
                            <div class="col-md-8">
                                <label for="">PROPERTY TITLE</label>
                                <input id="property_name" type="text" class="form-control @error('property_name') is-invalid @enderror" name="property_name" value="{{ old('property_name') }}" autocomplete="property_name" autofocus>

                                @error('property_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="">PROPERTY IMAGE</label>
                                <input id="property_img" type="file" class="form-control @error('property_img') is-invalid @enderror" name="property_img" value="{{ old('property_img') }}" autocomplete="property_img" autofocus>

                                @error('property_img')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div> 
                            <div class="col-md-12">
                                <label for="">PROPERTY DESCRIPTION</label>
                                <textarea name="property_desc" row="5" class="form-control @error('property_desc') is-invalid @enderror"  value="{{ old('property_desc') }}"  autocomplete="property_desc" placeholder="Enter ga message"></textarea>

                                @error('property_desc')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="">GOOGLE MAP URL</label>
                                <input id="property_google_map" type="text" class="form-control @error('property_google_map') is-invalid @enderror" name="property_google_map" value="{{ old('property_google_map') }}" autocomplete="property_google_map" autofocus>

                                @error('property_google_map')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="">LATITUDE</label>
                                <input id="latitude" type="text" class="form-control @error('latitude') is-invalid @enderror" name="latitude" value="{{ old('latitude') }}" autocomplete="latitude" autofocus>

                                @error('latitude')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="">LONGITUDE</label>
                                <input id="longitude" type="text" class="form-control @error('longitude') is-invalid @enderror" name="longitude" value="{{ old('longitude') }}" autocomplete="longitude" autofocus>

                                @error('longitude')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="">PRICE TYPE</label>
                                <select name="price_type" id="" class="form-control">
                                    <option value="Budget">Budget</option>
                                    <option value="Economy">Economy</option>
                                    <option value="Moderate">Moderate</option>
                                    <option value="Deluxe">Deluxe</option>
                                    <option value="Premium">Premium</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="">PRICE STAR</label>
                                <select name="star_type" id="" class="form-control">
                                    <option value="1">1 Star</option>
                                    <option value="2">2 Star</option>
                                    <option value="3">3 Star</option>
                                    <option value="4">4 Star</option>
                                    <option value="5">5 Star</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="">COUNTRY</label>
                                <select name="property_country" onchange="selectCities()" id="property_country" class="form-control">
                                    @foreach($all_countries as $country_res)
                                    <option value="{{ $country_res['id'] }}">{{ $country_res['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="">City</label>
                                <select name="property_city" id="property_city" class="form-control">
                                   
                                </select>
                            </div>  
                        </div>
                    </div>
                    <!-- facilities Tab Start -->
                    <div class="tab-pane fade" id="facilities" role="tabpanel" aria-labelledby="facilities-tab">
                        <div class="form-check">
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
                            <div class="col-md-12">
                                <label for="">META TITLE</label>
                                <input id="meta_title" type="text" class="form-control @error('meta_title') is-invalid @enderror" name="meta_title" value="{{ old('meta_title') }}" autocomplete="meta_title" autofocus>

                                @error('meta_title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                                <div class="col-md-12">
                                    <label for="">META KEYWORDS</label>
                                    <textarea name="meta_keywords" row="5" class="form-control @error('meta_keywords') is-invalid @enderror"  value="{{ old('meta_keywords') }}"  autocomplete="meta_keywords" placeholder="Enter ga message"></textarea>

                                    @error('meta_keywords')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-12">
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
                            <div class="col-md-6">
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
                            <div class="col-md-6">
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

                            <div class="col-md-12">
                                <label for="">PAYMENT OPTION</label>
                                <select name="payment_option" id="" class="form-control">
                                    <option value="At Arrival">At Arrival</option>
                                </select>
                            </div>

                            <div class="col-md-12">
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
                            <div class="col-md-12">
                                <label for="">E mail</label>
                                <input id="hotel_email" type="text" class="form-control @error('hotel_email') is-invalid @enderror" name="hotel_email" value="{{ old('hotel_email') }}" autocomplete="hotel_email" autofocus>

                                @error('hotel_email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="">Hotel's Website</label>
                                <input id="hotel_website" type="text" class="form-control @error('hotel_website') is-invalid @enderror" name="hotel_website" value="{{ old('hotel_website') }}" autocomplete="hotel_website" autofocus>

                                @error('hotel_website')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="">Phone Number</label>
                                <input id="property_phone" type="text" class="form-control @error('property_phone') is-invalid @enderror" name="property_phone" value="{{ old('property_phone') }}" autocomplete="property_phone" autofocus>

                                @error('property_phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-12">
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
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
</div>


@endsection
@section('scripts')
<script>
    $(document).ready(function(){
        selectCities();
    })
    $("#checkall").click(function (){
     if ($("#checkall").is(':checked')){
        $(".form-check-input").each(function (){
           $(this).prop("checked", true);
           });
        }else{
           $(".form-check-input").each(function (){
                $(this).prop("checked", false);
           });
        }
    })

    function selectCities(){
        var country = $('#property_country').val();
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
              $('#property_city').html(result);
            },
            error:function(error){
                console.log(error);
            }
        });
    }
</script>
@stop



