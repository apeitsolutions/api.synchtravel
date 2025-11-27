@extends('template/frontend/userdashboard/layout/default')
@section('content')


<div class="dashboard-content">
    
    <h4 style="color:#a30000">New Room</h4>
    <form action="{{ url('hotel_manger/add_room_sub') }}" method="post" enctype="multipart/form-data">
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
                        <a class="nav-link" id="facilities-tab" data-bs-toggle="tab" href="#facilities" role="tab" aria-controls="facilities" aria-selected="false">AMENITIES</a>
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
                            <div class="col-md-12" style="padding: 15px;">
                                <label for="">Select Hotel</label>
                                <select name="hotel" id="" class="form-control">
                                  @foreach($user_hotels as $hotel_res)
                                    <option value="{{ $hotel_res['id'] }}">{{ $hotel_res['property_name'] }}</option>
                                  @endforeach
                                </select>
                            </div>
                            <div class="col-md-6" style="padding: 15px;">
                                <label for="">Room Type</label>
                                <select name="room_type" id="" class="form-control">
                                  @foreach($roomTypes as $room_res)
                                    <option value="{{ $room_res['id'] }}">{{ $room_res['room_type'] }}</option>
                                  @endforeach
                                </select>
                            </div>
                            <div class="col-md-6" style="padding: 15px;">
                                <label for="">Room View</label>
                                <select name="room_view" id="" class="form-control">
                                    <option value="City View">City View</option>
                                    <option value="Haram View">Haram View</option>
                                    <option value="Kabbah View">Kabbah View</option>
                                    <option value="Partial Haram View">Partial Haram View</option>
                                    <option value="Patio View">Patio View</option>
                                    <option value="Towers View">Towers View</option>
                                </select>
                            </div>

                            <div class="col-md-12" style="padding: 15px;">
                                <label for="">Room Image</label>
                                <input id="room_img" type="file" class="form-control @error('room_img') is-invalid @enderror" name="room_img" value="{{ old('room_img') }}" autocomplete="room_img" autofocus>

                                @error('room_img')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <!-- <hr style="border: 0.5px solid #e1d5d5;" class="mt-5"> -->
                            </div> 
                            
                            <div class="col-md-6" style="padding: 15px;">
                                <label for="">Room availability (From)</label>
                                <input id="room_av_from" type="date" class="form-control @error('room_av_from') is-invalid @enderror" name="room_av_from" value="{{ old('room_av_from') }}" autocomplete="room_av_from" autofocus>

                                @error('room_av_from')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div> 

                            <div class="col-md-6" style="padding: 15px;">
                                <label for="">Room availability (To)</label>
                                <input id="room_av_to" type="date" class="form-control @error('room_av_to') is-invalid @enderror" name="room_av_to" value="{{ old('room_av_to') }}" autocomplete="room_av_to" autofocus>

                                @error('room_av_to')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-12" style="padding: 15px;">
                                 <label for="">Select Room Price (Price Per Night)</label>
                                <div class="row">
                                    <div class="col-md-6" style="padding: 15px;">
                                        <div class="form-check">
                                            <input class="form-check-input week_price_type" required name="week_price_type" checked type="radio" value="for_all_days" id="for_all_days">
                                            <label class="form-check-label" for="for_all_days">
                                                Room Price for All Days
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6" style="padding: 15px;">
                                        <div class="form-check">
                                            <input class="form-check-input week_price_type" name="week_price_type" type="radio" value="for_week_end" id="for_week_end">
                                            <label class="form-check-label" for="for_week_end">
                                                Room Price for Weekdays/Weekend
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            
                            </div>
                            <div class="col-md-12 week_prices_fixed" id="price_for_all_days" style="padding: 15px;">
                                <label for="">Price For All Days</label>
                                <input id="price_all_days" required type="number" class="form-control @error('price_all_days') is-invalid @enderror" name="price_all_days" value="{{ old('price_all_days') }}" autocomplete="price_all_days" autofocus>

                                @error('price_all_days')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6 week_prices d-none" id="" style="padding: 15px;">
                                <label for="">Chose  WeekDays</label>
                                <div class="form-check">
                                    <input class="form-check-input week_prices" type="checkbox" name="weekdays[]" value="Monday" id="Monday">
                                    <label class="form-check-label" for="Monday">
                                         Monday
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input week_prices" type="checkbox" name="weekdays[]" value="Tuesday" id="Tuesday">
                                    <label class="form-check-label" for="Tuesday">
                                         Tuesday
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input week_prices" type="checkbox" name="weekdays[]" value="Wednesday" id="Wednesday">
                                    <label class="form-check-label" for="Wednesday">
                                         Wednesday
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input week_prices" type="checkbox" name="weekdays[]" value="Thursday" id="Thursday">
                                    <label class="form-check-label" for="Thursday">
                                         Thursday
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input week_prices" type="checkbox" name="weekdays[]" value="Friday" id="Friday">
                                    <label class="form-check-label" for="Friday">
                                         Friday
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input week_prices" type="checkbox" name="weekdays[]" value="Saturday" id="Saturday">
                                    <label class="form-check-label" for="Saturday">
                                         Saturday
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input week_prices" type="checkbox" name="weekdays[]" value="Sunday" id="Sunday">
                                    <label class="form-check-label" for="Sunday">
                                         Sunday
                                    </label>
                                </div>

                                <div class="col-md-12">
                                    <label for="">Price for WeekDays</label>
                                    <input id="week_days_price" type="number" class="form-control @error('week_days_price') is-invalid @enderror" name="week_days_price" value="{{ old('week_days_price') }}" autocomplete="week_days_price" autofocus>

                                    @error('week_days_price')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div> 
                            </div>

                            <div class="col-md-6 week_prices d-none" id="" style="padding: 15px;">
                                <label for="">Chose WeekdEnd</label>
                                <div class="form-check">
                                    <input class="form-check-input week_prices" type="checkbox" name="weekend[]" value="Monday" id="Monday1">
                                    <label class="form-check-label" for="Monday1">
                                         Monday
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input week_prices" type="checkbox" name="weekend[]" value="Tuesday" id="Tuesday1">
                                    <label class="form-check-label" for="Tuesday1">
                                         Tuesday
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input week_prices" type="checkbox" name="weekend[]" value="Wednesday" id="Wednesday1">
                                    <label class="form-check-label" for="Wednesday1">
                                         Wednesday
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input week_prices" type="checkbox" name="weekend[]" value="Thursday" id="Thursday1">
                                    <label class="form-check-label" for="Thursday1">
                                         Thursday
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input week_prices" type="checkbox" name="weekend[]" value="Friday" id="Friday1">
                                    <label class="form-check-label" for="Friday1">
                                         Friday
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input week_prices" type="checkbox" name="weekend[]" value="Saturday" id="Saturday1">
                                    <label class="form-check-label" for="Saturday1">
                                         Saturday
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input week_prices" type="checkbox" name="weekend[]" value="Sunday" id="Sunday1">
                                    <label class="form-check-label" for="Sunday1">
                                         Sunday
                                    </label>
                                </div>

                                <div class="col-md-12">
                                    <label for="">Price for WeekdEnd</label>
                                    <input id="week_end_price"  type="number" class="form-control @error('week_end_price') is-invalid @enderror" name="week_end_price" value="{{ old('week_end_price') }}" autocomplete="week_end_price" autofocus>

                                    @error('week_end_price')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12" style="padding: 15px;">
                                <label for="">Room description</label>
                                <textarea name="room_desc" row="5" class="form-control @error('room_desc') is-invalid @enderror"  value="{{ old('room_desc') }}"  autocomplete="room_desc" placeholder="Enter ga message"></textarea>

                                @error('room_desc')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
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
                            <div class="col-md-4" style="padding: 5px;">
                                <div class="form-check">
                                    <input class="form-check-input amenities" type="checkbox" name="amenities[]" value="Access via exterior corridors" id="Airport_Transport">
                                    <label class="form-check-label" for="Airport_Transport">
                                         Access via exterior corridors
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-4" style="padding: 5px;">
                                <div class="form-check">
                                    <input class="form-check-input amenities" type="checkbox" name="amenities[]" value="Climate control" id="Business_Center">
                                    <label class="form-check-label" for="Business_Center">
                                        Climate control
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-4" style="padding: 5px;">
                                <div class="form-check">
                                    <input class="form-check-input amenities" type="checkbox" name="amenities[]" value="Courtyard view" id="Disabled_Facilities">
                                    <label class="form-check-label" for="Disabled_Facilities">
                                        Courtyard view
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-4" style="padding: 5px;">
                                <div class="form-check">
                                    <input class="form-check-input amenities" type="checkbox" name="amenities[]" value="Extra towels/bedding" id="Night_Club">
                                    <label class="form-check-label" for="Night_Club">
                                        Extra towels/bedding
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-4" style="padding: 5px;">
                                <div class="form-check">
                                    <input class="form-check-input amenities" type="checkbox" name="amenities[]" value="Hair dryer" id="Laundry_Service">
                                    <label class="form-check-label" for="Laundry_Service">
                                        Hair dryer
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4" style="padding: 5px;">
                                <div class="form-check">
                                    <input class="form-check-input amenities" type="checkbox" name="amenities[]" value="Individually decorated" id="Individually_decorated">
                                    <label class="form-check-label" for="Individually_decorated">
                                        Individually decorated
                                    </label>
                                </div>
                            </div>
                          
                            <div class="col-md-4" style="padding: 5px;">
                                <div class="form-check">
                                    <input class="form-check-input amenities" type="checkbox" name="amenities[]" value="In-room safe (laptop compatible)" id="Wi-Fi_Internet">
                                    <label class="form-check-label" for="Wi-Fi_Internet">
                                        In-room safe (laptop compatible)
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4" style="padding: 5px;">
                                <div class="form-check">
                                    <input class="form-check-input amenities" type="checkbox" name="amenities[]" value="Makeup/shaving mirror" id="Bar_Lounge">
                                    <label class="form-check-label" for="Bar_Lounge">
                                        Makeup/shaving mirror
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-4" style="padding: 5px;">
                                <div class="form-check">
                                    <input class="form-check-input amenities" type="checkbox" name="amenities[]" value="Refrigerator" id="Swimming_Pool">
                                    <label class="form-check-label" for="Swimming_Pool">
                                        Refrigerator
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-4" style="padding: 5px;">
                                <div class="form-check">
                                    <input class="form-check-input amenities" type="checkbox" name="amenities[]" value="Shower/tub combination" id="Inside_Parking">
                                    <label class="form-check-label" for="Inside_Parking">
                                        Shower/tub combination
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-4" style="padding: 5px;">
                                <div class="form-check">
                                    <input class="form-check-input amenities" type="checkbox" name="amenities[]" value="Welcome amenities" id="Shuttle_Bus_Service">
                                    <label class="form-check-label" for="Shuttle_Bus_Service">
                                        Welcome amenities
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-4" style="padding: 5px;">
                                <div class="form-check">
                                    <input class="form-check-input amenities" type="checkbox" name="amenities[]" value="Air conditioning" id="Fitness_Center">
                                    <label class="form-check-label" for="Fitness_Center">
                                        Air conditioning
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-4" style="padding: 5px;">
                                <div class="form-check">
                                    <input class="form-check-input amenities" type="checkbox" name="amenities[]" value="Blackout drapes/curtains" id="Children_Activites">
                                    <label class="form-check-label" for="Children_Activites">
                                         Blackout drapes/curtains
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-4" style="padding: 5px;">
                                <div class="form-check">
                                    <input class="form-check-input amenities" type="checkbox" name="amenities[]" value="Complimentary toiletries" id="Air_Conditioner">
                                    <label class="form-check-label" for="Air_Conditioner">
                                        Complimentary toiletries
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-4" style="padding: 5px;">
                                <div class="form-check">
                                    <input class="form-check-input amenities" type="checkbox" name="amenities[]" value="Cribs/infant beds available" id="Cards_Accepted">
                                    <label class="form-check-label" for="Cards_Accepted">
                                        Cribs/infant beds available
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-4" style="padding: 5px;">
                                <div class="form-check">
                                    <input class="form-check-input amenities" type="checkbox" name="amenities[]" value="Dial-up Internet access (surcharge)" id="Banquet_Hall">
                                    <label class="form-check-label" for="Banquet_Hall">
                                        Dial-up Internet access (surcharge)
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-4" style="padding: 5px;">
                                <div class="form-check">
                                    <input class="form-check-input amenities" type="checkbox" name="amenities[]" value="Free Wi-Fi" id="Elevator">
                                    <label class="form-check-label" for="Elevator">
                                        Free Wi-Fi
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-4" style="padding: 5px;">
                                <div class="form-check">
                                    <input class="form-check-input amenities" type="checkbox" name="amenities[]" value="Handheld showerhead" id="Pets_Allowed">
                                    <label class="form-check-label" for="Pets_Allowed">
                                        Handheld showerhead
                                    </label>
                                </div>
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

                        <label for="">PRICE TYPE</label>
                        <select name="price_type" onchange="checkPriceType()" id="price_type" class="form-control">
                            <option value="By fixed" selected="selected">By fixed</option>
                            <option value="By Travellers">By Travellers</option>
                        </select>

                        <div id="travller_price" style="display:none;">
                            <label for="">Adults Price</label>
                            <input id="adult_price" type="number" class="form-control @error('adult_price') is-invalid @enderror" name="adult_price" value="{{ old('adult_price') }}" autocomplete="adult_price" autofocus>

                            @error('adult_price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <label for="">Child Price</label>
                            <input id="child_price" type="number" class="form-control @error('child_price') is-invalid @enderror" name="child_price" value="{{ old('child_price') }}" autocomplete="child_price" autofocus>

                            @error('child_price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        Markup
                    </div>
                    <div class="card-body">
                        <label for="">Quantity</label>
                        <input id="quantity" type="text" class="form-control @error('quantity') is-invalid @enderror" name="quantity" value="{{ old('quantity') }}" autocomplete="quantity" autofocus>

                        @error('quantity')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <label for="">Min Stay</label>
                        <input id="min_stay" type="text" class="form-control @error('min_stay') is-invalid @enderror" name="min_stay" value="{{ old('min_stay') }}" autocomplete="min_stay" autofocus>

                        @error('min_stay')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <label for="">Max Adults</label>
                        <select name="max_adults" id="" class="form-control">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>

                        <label for="">Max Children</label>
                        <select name="max_childrens" id="" class="form-control">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>

                        <label for="">No of Extra Beds</label>
                        <input id="extra_beds" type="text" class="form-control @error('extra_beds') is-invalid @enderror" name="extra_beds" value="{{ old('extra_beds') }}" autocomplete="extra_beds" autofocus>

                        @error('extra_beds')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <label for="">Extra Bed Charges</label>
                        <input id="extra_beds_charges" type="text" class="form-control @error('extra_beds_charges') is-invalid @enderror" name="extra_beds_charges" value="{{ old('extra_beds_charges') }}" autocomplete="extra_beds_charges" autofocus>

                        @error('extra_beds_charges')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        



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
        // selectCities();
    })
    $("#checkall").click(function (){
     if ($("#checkall").is(':checked')){
        $(".amenities").each(function (){
           $(this).prop("checked", true);
           });
        }else{
           $(".amenities").each(function (){
                $(this).prop("checked", false);
           });
        }
    })

    $(".week_price_type").click(function(){
        var value = $(this).val();
        console.log('value is '+value);
        if(value == 'for_week_end'){
            $('.week_prices ').removeClass("d-none");
            $('.week_prices_fixed ').addClass("d-none");
            $('#week_days_price').attr('required',true);
            $('#week_end_price').attr('required',true);
            $('#price_all_days').attr('required',false);
            
        }else{
            $('.week_prices ').addClass("d-none");
            $('.week_prices_fixed ').removeClass("d-none");
            $('#week_days_price').attr('required',false);
            $('#week_end_price').attr('required',false);
            $('#price_all_days').attr('required',true);
        }
    })
    

    $(".week_prices").click(function(){
        var id = $(this).attr('id');
        console.log('The id is '+id);
        if ($("#Monday").is(':checked')){
              $("#Monday1").prop("disabled", true);
        }else{
            $("#Monday1").prop("disabled", false);
        }

        if ($("#Monday1").is(':checked')){
              $("#Monday").prop("disabled", true);
        }else{
            $("#Monday").prop("disabled", false);
        }

        if ($("#Tuesday").is(':checked')){
              $("#Tuesday1").prop("disabled", true);
        }else{
            $("#Tuesday1").prop("disabled", false);
        }

        if ($("#Tuesday1").is(':checked')){
              $("#Tuesday").prop("disabled", true);
        }else{
            $("#Tuesday").prop("disabled", false);
        }

        if ($("#Wednesday").is(':checked')){
              $("#Wednesday1").prop("disabled", true);
        }else{
            $("#Wednesday1").prop("disabled", false);
        }

        if ($("#Wednesday1").is(':checked')){
              $("#Wednesday").prop("disabled", true);
        }else{
            $("#Wednesday").prop("disabled", false);
        }

        if ($("#Thursday").is(':checked')){
              $("#Thursday1").prop("disabled", true);
        }else{
            $("#Thursday1").prop("disabled", false);
        }

        if ($("#Thursday1").is(':checked')){
              $("#Thursday").prop("disabled", true);
        }else{
            $("#Thursday").prop("disabled", false);
        }

        if ($("#Friday").is(':checked')){
              $("#Friday1").prop("disabled", true);
        }else{
            $("#Friday1").prop("disabled", false);
        }

        if ($("#Friday1").is(':checked')){
              $("#Friday").prop("disabled", true);
        }else{
            $("#Friday").prop("disabled", false);
        }

        if ($("#Saturday").is(':checked')){
              $("#Saturday1").prop("disabled", true);
        }else{
            $("#Saturday1").prop("disabled", false);
        }

        if ($("#Saturday1").is(':checked')){
              $("#Saturday").prop("disabled", true);
        }else{
            $("#Saturday").prop("disabled", false);
        }

        if ($("#Sunday").is(':checked')){
              $("#Sunday1").prop("disabled", true);
        }else{
            $("#Sunday1").prop("disabled", false);
        }

        if ($("#Sunday1").is(':checked')){
              $("#Sunday").prop("disabled", true);
        }else{
            $("#Sunday").prop("disabled", false);
        }


        
    })


    var today = new Date().toISOString().split('T')[0];
    document.getElementsByName("room_av_from")[0].setAttribute('min', today);
    document.getElementsByName("room_av_to")[0].setAttribute('min', today);

    function checkPriceType(){
        var PriceType = $("#price_type").val();
        if(PriceType == 'By Travellers'){
            $("#travller_price").css('display','block');
            $('#adult_price').attr('required',true);
            $('#child_price').attr('required',true);
        }else{
            $("#travller_price").css('display','none');
            $('#adult_price').attr('required',false);
            $('#child_price').attr('required',false);
        }
    }

  
</script>

@stop



