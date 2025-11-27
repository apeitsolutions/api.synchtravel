@extends('template/frontend/userdashboard/layout/default')
@section('content')

<div id="RI-supplier-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Add Room Suppliers</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form class="ps-3 pe-3" action="#">
                    <div class="mb-3">
                        <label for="username" class="form-label">Supplier Name</label>
                        <input class="form-control" type="text" id="room_supplier_name1" name="room_supplier_name" placeholder="">
                    </div>
                    <div class="mb-3 text-center">
                        <button class="btn btn-primary" id="addInvoiceRoomSupplier" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="dashboard-content">
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
              </div>
    <h4>Add Room</h4>
    <form action="{{ URL::to('hotel_manger/add_room_sub') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
        @csrf
        <div class="row">
            
            <div class="col-md-8">
                <!--<ul class="nav nav-tabs" id="myTab" role="tablist">-->
                <!--    <li class="nav-item" role="presentation">-->
                <!--        <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">GENERAL</a>-->
                <!--    </li>-->
                <!--    <li class="nav-item" role="presentation">-->
                <!--        <a class="nav-link" id="facilities-tab" data-bs-toggle="tab" href="#facilities" role="tab" aria-controls="facilities" aria-selected="false">AMENITIES</a>-->
                <!--    </li>-->
                <!--    <li class="nav-item" role="presentation">-->
                <!--        <a class="nav-link" id="multi_pics-tab" data-bs-toggle="tab" href="#multi_pics" role="tab" aria-controls="multi_pics" aria-selected="false">Gallery</a>-->
                <!--    </li>-->
                    <!--<li class="nav-item" role="presentation">-->
                    <!--    <a class="nav-link" id="translate-tab" data-bs-toggle="tab" href="#translate" role="tab" aria-controls="translate" aria-selected="false">TRANSLATE</a>-->
                    <!--</li>-->
                <!--</ul>-->
                <!-- Main Tab Contant Start -->
                <div class="tab-content" id="myTabContent">
                    <!-- General Tab Start -->
                    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                        <div class="row">
                            
                            <div class="col-md-12" style="padding: 15px;">
                                <label for="">Select Hotel</label>
                                <select name="hotel" id="" class="form-control">
                                  @foreach($user_hotels as $hotel_res)
                                  <?php
                                  if($hotel_res->id == $hotel_id)
                                  {
                                  ?>
                                    <option value="{{ $hotel_res->id }}">{{ $hotel_res->property_name }}</option>
                                    <?php
                                  }
                                    ?>
                                  @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-4" style="padding: 15px;">
                                <label for="">Room Type</label>
                                <select name="room_type" id="room_type0" onchange="get_rooms_type(0)" required class="form-control">
                                  @foreach($roomTypes as $room_res)
                                    <option value='{{ json_encode($room_res) }}' room-type="{{ $room_res->parent_cat }}">{{ $room_res->room_type }}</option>
                                  @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-4" style="padding: 15px;">
                                <label for="">Room View</label>
                                <select name="room_view" id="city_view" required class="form-control">
                                    <option value="City View">City View</option>
                                    <option value="Haram View">Haram View</option>
                                    <option value="Kabbah View">Kabbah View</option>
                                    <option value="Partial Haram View">Partial Haram View</option>
                                    <option value="Patio View">Patio View</option>
                                    <option value="Towers View">Towers View</option>
                                </select>
                            </div>

                            
                            
                            <div class="col-md-4" style="padding: 15px;">
                                <label for="">Room Meal Type</label>
                                <select id="room_meal_type" type="date" class="form-control" name="room_meal_type" autofocus required>
                                    <option value="Room only">Room only</option>
                                    <option value="Breakfast">Breakfast</option>
                                    <option value="Lunch">Lunch</option>
                                    <option value="Dinner">Dinner</option>
                                    <option value="Suhoor">Suhoor</option>
                                    <option value="Iftar">Iftar</option>
                                </select>
                            </div> 
                            
                            <div class="col-md-4" style="padding: 15px;">
                                <label for="">Room availability (From)</label>
                                <input  type="date" class="form-control" id="start_date" required name="room_av_from"  autocomplete="room_av_from" autofocus>

                                
                            </div> 

                            <div class="col-md-4" style="padding: 15px;">
                                <label for="">Room availability (To)</label>
                                <input type="date" class="form-control" id="end_date" required name="room_av_to"  autocomplete="room_av_to" autofocus>

                              
                            </div>
                            <div class="col-md-4" style="padding: 15px;">
                                <label for="">Option Date</label>
                                <input type="date" class="form-control" id="option_date" name="option_date" >
                            </div>
                            
                            
                          <div class="col-md-4" style="padding: 15px;"> 
                                <label for="">Quantity</label>
                                <input type="text" class="form-control" required name="quantity" id="quantity_room" autocomplete="quantity" autofocus>
        
                               
                         </div>
  <div class="col-md-4" style="padding: 15px;"> 
                        <label for="">Min Stay</label>
                        <input id="min_stay" type="text" class="form-control" name="min_stay" value="0" autocomplete="min_stay" autofocus>

                        
</div>
 <div class="col-md-4" style="padding: 15px;"> 
                        <label for="">Max Adults</label>
                        <select name="max_adults" id="room_adults0" class="form-control">
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
</div>
 <div class="col-md-4" style="padding: 15px;"> 
                        <label for="">Max Children</label>
                        <select name="max_childrens" id="" class="form-control">
                            <option value="0">0</option>
                            <option value="1" selected>1</option>
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
</div>
 <div class="col-md-4" style="padding: 15px;"> 
                        <label for="">No of Extra Beds</label>
                        <input id="extra_beds" type="text" class="form-control " name="extra_beds" value="0" autocomplete="extra_beds" autofocus>

                       
</div>
 <div class="col-md-4" style="padding: 15px;"> 
                        <label for="">Extra Bed Charges</label>
                        <input id="extra_beds_charges" type="text" class="form-control" name="extra_beds_charges" value="0" autocomplete="extra_beds_charges" autofocus>

                        
</div>



                   
                            
                            
                            
                            
                            <div class="col-md-4" style="padding: 15px;">
                                <label for="">Room Suppliers</label>
                                <div class="input-group" id="timepicker-input-group1">
                                    <select id="room_supplier_name" type="date" class="form-control" name="room_supplier_name" autofocus required>
                                        @isset($hotel_suppliers)
                                            @foreach($hotel_suppliers as $hotel_sup_res)
                                            <option value="{{ $hotel_sup_res->id }}">{{ $hotel_sup_res->room_supplier_name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>            
                                </div>
                            </div>
                            <div class="col-md-4" style="padding: 15px;">
                                <label for="">Additional Meal Type</label>
                                    <select name="additional_meal_type" id="additional_meal_type" class="form-control">
                                        <option value="">Select Additional Meal Type</option>
                                        <option value="no">No Meal Option</option>

                                        <option value="Breakfast is included in room rates">Breakfast is included in room rates</option>
                                        
                                        <option value="Breakfast costs">Breakfast costs</option>
                                        
                                        <option value="Half Board">Half Board </option>
                                        
                                        <option value="Full Board">Full Board </option>
                                        
                                        <option value="With Iftar">With Iftar </option>
                                        
                                        <option value="room only">Room only</option>
                                        
                                        <option value="Sahoor only">Sahoor only</option>
                                        
                                        <option value="Sahour and Iftar">Sahour & Iftar</option>
                                        
                                        <option value="Sahour or iftar">Sahour or iftar</option>
                                        
                                        <option value="othermeal">Other meal</option>
                            
                                        <option value="Lunch">Lunch</option>
                                        <option value="Dinner">Dinner</option>
                           
                                    </select>
                            </div>
                             <div class="col-md-12" id="additional_meal_type_charges" style="display:none;padding: 15px;"> 
                        <label for="">Additional Meal Charges</label>
                        <input id="" type="text" class="form-control" name="additional_meal_type_charges"  >
                        </div>
                        
                        <!--<div class="col-md-4" id="meal_policy[]" style="padding: 15px;"> -->
                        <!--<label for="">MEAL POLICY</label>-->
                        <!--<select name="meal_policy" class="select2 form-control select2-multiple" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">-->
                                       
                        <!--                   <option value="no">No Meal Option</option>-->

                        <!--                <option value="Breakfast is included in room rates">Breakfast is included in room rates</option>-->
                                        
                        <!--                <option value="Breakfast costs">Breakfast costs</option>-->
                                        
                        <!--                <option value="Half Board">Half Board </option>-->
                                        
                        <!--                <option value="Full Board">Full Board </option>-->
                                        
                        <!--                <option value="With Iftar">With Iftar </option>-->
                                        
                        <!--                <option value="room only">Room only</option>-->
                                        
                        <!--                <option value="Sahoor only">Sahoor only</option>-->
                                        
                        <!--                <option value="Sahour and Iftar">Sahour & Iftar</option>-->
                                        
                        <!--                <option value="Sahour or iftar">Sahour or iftar</option>-->
                                        
                        <!--                <option value="othermeal">Other meal</option>-->
                        <!--            </select>-->
                        <!--</div>-->
                         <div class="col-md-4"  style="padding: 15px;"> 
                        <label for="">CANCELLATION POLICY</label>
                        <div class="input-group" id="timepicker-input-group1">
                        <select class="form-control" id="cancellation_policy" required name="concellation_policy">
                                       
                                           <option value="free">Free cancellation</option>

                                            <option value="non-refundable">Non Refundable</option>
                                            
                                            <option value="paid">Cancellation costs</option>
                                            
                                           
                                    </select>
                                    <span title="Add More Cancellation" class="input-group-btn input-group-append" id="add_more_cancellation">
                                        <a class="btn btn-primary bootstrap-touchspin-up"type="button">+</a>
                                        </span>
                        </div>
                          </div>
                          <div id="append_add_more_cancellation">
                              
                          </div>
                        
                        
                        <div class="row" id="cancellation_policy_show" style="display:none;">
                            <div class="col-md-6"> 
                        <label for="">How many days in advance can guests cancel free of charge?</label>
                        <select class="form-control" name="guests_pay_days">
                                       <option value="Day of arrival" class="form-control">Day of arrival (6 pm)</option>

                                        <option value="1 day">1 day</option>
                                        
                                        <option value="2 days">2 days</option>
                                        
                                        <option value="3 days">3 days</option>
                                        
                                        <option value="7 days">7 days</option>
                                        
                                        <option value="14 days">14 days</option>
                                    </select>
                        </div>
                        <div class="col-md-6" style="margin-top: 20px;"> 
                        <label for="">or guests will pay 100%</label>
                        <select class="form-control" name="guests_pay">
                                       <option value="of the first night">of the first night</option>

                                        <option value="of the full stay">of the full stay</option> 
                                    </select>
                        </div>
                        </div>
                        <div class="col-md-6"  style="padding: 15px;"> 
                        <label for="">PREPAYMENT POLICY</label>
                        <select class="form-control" id="PREPAYMENTPOLICY" name="prepaymentpolicy">
                                       
                                           <option value="no">No deposit will be charged</option>

                                            <option value="yes">The total price of the reservation may be charged anytime after booking</option>
                                    </select>
                        </div>
                        
                            <div class="col-md-6" style="padding: 15px;">
                                 <label for="">Select Room Price (Price Per Night)</label>
                                    <select class="form-control" id="week_price_type0" name="week_price_type[]">
                                           <option value="for_all_days">Room Price for All Days</option>
                                           <option value="for_week_end">Room Price for Weekdays/Weekend</option>
                                    </select>
                      
                            </div>
                            
                            <div class="col-md-12 week_prices_fixed" id="price_for_all_days" style="padding: 15px;">
                                <label for="">Price For All Days</label>
                                <input id="price_all_days0" required type="number" step=".01" counter="0" class="form-control calculateMarkup" name="price_all_days" autocomplete="price_all_days" autofocus>

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
                                    <input id="week_days_price0" type="number" step=".01" counter="0" class="form-control calculateMarkup @error('week_days_price') is-invalid @enderror" name="week_days_price" value="{{ old('week_days_price') }}" autocomplete="week_days_price" autofocus>

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
                                    <input id="week_end_price0"  type="number" step=".01" counter="0" class="form-control calculateMarkup @error('week_end_price') is-invalid @enderror" name="week_end_price" value="{{ old('week_end_price') }}" autocomplete="week_end_price" autofocus>

                                    @error('week_end_price')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            
                              <div class="col-md-12" id="" style="padding: 15px;">
                                    <label class="form-check-label" for="rooms_on_rq">
                                Rooms On Request
                            </label>
                                    <select class="form-control" name="rooms_on_rq"  id="rooms_on_rq">
                                        <option value="1">Select Rooms On Request</option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                
                            </div>
                            
                            <div class="col-md-12" id="" style="padding: 15px;">
                                     <input class="form-check-input display_on_website" counter="0" type="checkbox" name="display_on_website" value="true" id="display_on_website0">
                                    <label class="form-check-label" for="display_on_website0">
                                         Display on Website
                                    </label>
                                    <div class="row" id="display_markup0" style="display:none;">
                                        <div class="col-md-3">
                                            <label for="">Select Markup Type</label>
                                            <select class="form-control calculateMarkup" name="markup_type" counter="0"  id="markup_type0">
                                                <option value="Fixed">Fixed</option>
                                                <option value="Percentage">Percentage</option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <label for="">Markup Value</label>
                                            <input id="one_night_markup0" type="text" counter="0" class="form-control calculateMarkup" name="one_night_markup">
                                        </div>
                                        
                                        <div class="col-md-3 week_prices_fixed">
                                            <label for="">One Room Price</label>
                                            <input id="one_night_mark_all0" readonly type="text" class="form-control " name="one_night_all_days_markup">
                                            <p style="font-size: 12px;">Per Room per Night</p>
                                        </div>
                                        
                                        <div class="col-md-3 week_prices d-none">
                                            <label for="">Week Days Price</label>
                                            <input id="one_night_mark_week0" readonly type="text" class="form-control " name="one_night_all_week_days_markup">
                                            <p style="font-size: 12px;">Per Room per Night</p>
                                        </div>
                                        
                                        <div class="col-md-3 week_prices d-none">
                                            <label for="">Weekend Days Price</label>
                                            <input id="one_night_mark_weekend0"  readonly type="text" class="form-control " name="one_night_all_weekend_days_markup">
                                            <p style="font-size: 12px;">Per Room per Night</p>
                                        </div>
                                    </div>
                                
                            </div>
                            
                            <div id="more_room_type_div"></div>
                            
                            <div class="col-md-12" style="padding: 15px;">
                                <button class="btn btn-primary" type="button" id="add_more_room_type">Add More Room Type</button>
                            </div>
                            
                            <div class="col-md-12" style="padding: 15px;">
                                <label for="">Room description</label>
                                <textarea name="room_desc" row="5" class="form-control @error('room_desc') is-invalid @enderror"  value="{{ old('room_desc') }}"  autocomplete="room_desc" placeholder="Enter the message"></textarea>

                                @error('room_desc')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                          
                        </div>
                    </div>
                 

                    <!-- Translate Tab Start --> 
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
                            <input id="adult_price" type="number" step=".01" class="form-control @error('adult_price') is-invalid @enderror" name="adult_price" value="{{ old('adult_price') }}" autocomplete="adult_price" autofocus>

                            @error('adult_price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <label for="">Child Price</label>
                            <input id="child_price" type="number" step=".01" class="form-control @error('child_price') is-invalid @enderror" name="child_price" value="{{ old('child_price') }}" autocomplete="child_price" autofocus>

                            @error('child_price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
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
    
        // selectCities();
        
          function get_rooms_type(id){
                 var room_type = $('#room_type'+id+'').find('option:selected').attr('room-type');
                 
                if(room_type == 'Single'){
                     $('#room_adults'+id+'').val("1").change();
                 }
                 
                 if(room_type == 'Double'){
                     $('#room_adults'+id+'').val("2").change();
                 }
                 
                 if(room_type == 'Triple'){
                     $('#room_adults'+id+'').val("3").change();
                 }
                 
                 if(room_type == 'Quad'){
                     $('#room_adults'+id+'').val("4").change();
                 }
                 console.log('Room type is '+room_type);
            }
            
            get_rooms_type(2);
    


    // getRoomSupplier();
  
    
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
    
    function CalculateMarkup(id){
        console.log('markup function cis ');
        var markupType = $('#markup_type'+id+'').val();
        var markupValue = $('#one_night_markup'+id+'').val();
        var PriceAllDays = $('#price_all_days'+id+'').val();
        var PriceWeekDays = $('#week_days_price'+id+'').val();
        var PriceWeekEndDays = $('#week_end_price'+id+'').val();
        var WeekPriceType = $("#week_price_type"+id+"").val();
        
        
        if(markupType !== 'Fixed'){
            
            if(markupValue >100){
                  $('#one_night_markup'+id+'').val(100);
                  markupValue = 100;
              }
              
              if(WeekPriceType == 'for_week_end'){
                  var markup_am_week_days = (PriceWeekDays * markupValue) / 100;
                  var PriceWeekDaysWithMarkup = +PriceWeekDays + +markup_am_week_days;
                  $('#one_night_mark_week'+id+'').val(PriceWeekDaysWithMarkup);
                  
                  var markup_am_weekend_days = (PriceWeekEndDays * markupValue) / 100;
                  var PriceWeekendDaysWithMarkup = +PriceWeekEndDays + +markup_am_weekend_days;
                  $('#one_night_mark_weekend'+id+'').val(PriceWeekendDaysWithMarkup);
                  
              }else{
                  var markup_am_all_days = (PriceAllDays * markupValue) / 100;
                  var PriceAllDaysWithMarkup = +PriceAllDays + +markup_am_all_days;
                  $('#one_night_mark_all'+id+'').val(PriceAllDaysWithMarkup);
              }
              
              
              
        }else{
            if(markupValue >100){
                  $('#one_night_markup'+id+'').val(100);
                  markupValue = 100;
              }
              
              if(WeekPriceType == 'for_week_end'){
                  var markup_am_week_days = markupValue;
                  var PriceWeekDaysWithMarkup = +PriceWeekDays + +markup_am_week_days;
                  $('#one_night_mark_week'+id+'').val(PriceWeekDaysWithMarkup);
                  
                  var markup_am_weekend_days = markupValue;
                  var PriceWeekendDaysWithMarkup = +PriceWeekEndDays + +markup_am_weekend_days;
                  $('#one_night_mark_weekend'+id+'').val(PriceWeekendDaysWithMarkup);
                  
              }else{
                  var markup_am_all_days = markupValue;
                  var PriceAllDaysWithMarkup = +PriceAllDays + +markup_am_all_days;
                  $('#one_night_mark_all'+id+'').val(PriceAllDaysWithMarkup);
              }
        }
        
    }
    

    
     $('.calculateMarkup').on('keyup change',function(){
         var value = $(this).attr('counter');
         console.log('value is '+value);
        CalculateMarkup(value);
    })
    
    //  $('.calculateMarkup1').on('keyup change',function(){
    //      var value = $(this).attr('counter');
    //      console.log('value is '+value);
    //     CalculateMarkup(value);
    // })
    


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


    $("#week_price_type0").change(function(){
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
        
        CalculateMarkup(value);
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
    // document.getElementsByName("room_av_from")[0].setAttribute('min', today);
    // document.getElementsByName("room_av_to")[0].setAttribute('min', today);

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
    
    var m_count = 0;
    var m_count1 = 0;
    var room_count = 0;
    $("#add_more_room_type").click(function(){
        m_count = m_count + 1
        room_count++;
        
        
        var quantity_room = $('#quantity_room').val();
        var  end_date = $('#end_date').val();
        var start_date = $('#start_date').val();
        var option_date = $('#option_date').val();
        var city_view = $('#city_view').val();
        
        var roomTypes   = <?php echo json_encode($roomTypes) ?>;
        var data = `<div class="row" id="M_R_T_div${m_count}">
                        <h3>More Room Type Details</h3>
                        <div class="col-md-4" style="padding: 15px;">
                            <label for="">Room Type</label>
                            <select name="more_room_type[]" id="room_type${room_count}" onchange="get_rooms_type(${room_count})" class="form-control">`;
                                roomTypes.forEach(function(item) {
                                    var room_type = item.room_type;
                                    var room_type_id = item.id;
            data    +=              `<option value='${ JSON.stringify(item) }' room-type="${item.parent_cat}">${room_type}</option>`;
                                });
            data    +=      `</select>
                        </div>`;
                        
            data    += `<div class="col-md-4" style="padding: 15px;">
                            <label for="">Room View</label>
                            <select name="more_room_view[]" id="city_view${room_count}" class="form-control">
                                <option value="City View">City View</option>
                                <option value="Haram View">Haram View</option>
                                <option value="Kabbah View">Kabbah View</option>
                                <option value="Partial Haram View">Partial Haram View</option>
                                <option value="Patio View">Patio View</option>
                                <option value="Towers View">Towers View</option>
                            </select>
                        </div>
    
                      
                        
                        <div class="col-md-4" style="padding: 15px;">
                            <label for="">Room Meal Type</label>
                            <select id="more_room_meal_type" type="date" class="form-control" name="more_room_meal_type[]" autofocus required>
                                <option value="Room only">Room only</option>
                                <option value="Breakfast">Breakfast</option>
                                <option value="Lunch">Lunch</option>
                                <option value="Dinner">Dinner</option>
                                <option value="Suhoor">Suhoor</option>
                                <option value="Iftar">Iftar</option>
                            </select>
                        </div> 
                        
                        <div class="col-md-4" style="padding: 15px;">
                            <label for="">Room availability (From)</label>
                            <input id="room_av_from_${m_count}" type="date" value="${start_date}" required class="form-control" name="more_room_av_from[]" autocomplete="room_av_from" autofocus>
                        </div> 
    
                        <div class="col-md-4" style="padding: 15px;">
                            <label for="">Room availability (To)</label>
                            <input id="room_av_to_${m_count}" type="date" value="${end_date}" required class="form-control" name="more_room_av_to[]" autocomplete="room_av_to" autofocus>
                        </div>
                         <div class="col-md-4" style="padding: 15px;">
                            <label for="">Option Date</label>
                            <input type="date" class="form-control" value="${option_date}" id=""  name="more_option_date[]" >
                        </div>
                        <div class="col-md-4" style="padding: 15px;"> 
                        <label for="">Quantity</label>
                        <input id="quantity" type="text" class="form-control " name="more_quantity[]" required value="${quantity_room}" autocomplete="quantity" autofocus>

                     
                        </div>
  <div class="col-md-4" style="padding: 15px;"> 
                        <label for="">Min Stay</label>
                        <input id="min_stay_${m_count}" type="text" class="form-control " name="more_min_stay[]" value="0" autocomplete="min_stay" autofocus>

                      
</div>
 <div class="col-md-4" style="padding: 15px;"> 
                        <label for="">Max Adults</label>
                        <select name="more_max_adults[]" id="room_adults${room_count}" class="form-control">
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
</div>
 <div class="col-md-4" style="padding: 15px;"> 
                        <label for="">Max Children</label>
                        <select name="more_max_childrens[]" id="max_childrens_${m_count}" class="form-control">
                        <option value="0">0</option>
                            <option value="1" selected>1</option>
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
</div>
 <div class="col-md-4" style="padding: 15px;"> 
                        <label for="">No of Extra Beds</label>
                        <input id="extra_beds_${m_count}" type="text" class="form-control " name="more_extra_beds[]" value="0" autocomplete="extra_beds" autofocus>

                     
</div>
 <div class="col-md-4" style="padding: 15px;"> 
                        <label for="">Extra Bed Charges</label>
                        <input id="extra_beds_charges_${m_count}" type="text" class="form-control " name="more_extra_beds_charges[]" value="0" autocomplete="extra_beds_charges" autofocus>

                     
                        
</div>
                        <div class="col-md-4" style="padding: 15px;">
                            <label for="">Room Suppliers</label>
                            <div class="input-group" id="timepicker-input-group1">
                                <select id="room_supplier_name_${m_count}" type="date" class="form-control" name="more_room_supplier_name[]" autofocus required>
                                        @isset($hotel_suppliers)
                                            @foreach($hotel_suppliers as $hotel_sup_res)
                                            <option value="{{ $hotel_sup_res->id }}">{{ $hotel_sup_res->room_supplier_name }}</option>
                                            @endforeach
                                        @endisset
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-4" style="padding: 15px;">
                                <label for="">Additional Meal Type</label>
                                <select name="more_additional_meal_type[]" onchange="setfunchange(${m_count})" id="more_additional_meal_type_${m_count}" class="form-control">
                                    <option value="">Select Additional Meal Type</option>
                                        <option value="no">No Meal Option</option>

                                        <option value="Breakfast is included in room rates">Breakfast is included in room rates</option>
                                        
                                        <option value="Breakfast costs">Breakfast costs</option>
                                        
                                        <option value="Half Board">Half Board </option>
                                        
                                        <option value="Full Board">Full Board </option>
                                        
                                        <option value="With Iftar">With Iftar </option>
                                        
                                        <option value="room only">Room only</option>
                                        
                                        <option value="Sahoor only">Sahoor only</option>
                                        
                                        <option value="Sahour and Iftar">Sahour & Iftar</option>
                                        
                                        <option value="Sahour or iftar">Sahour or iftar</option>
                                        
                                        <option value="othermeal">Other meal</option>
                            
                                        <option value="Lunch">Lunch</option>
                                        <option value="Dinner">Dinner</option>
                           
                        </select>
                            </div>
                            <div class="col-md-12" id="more_additional_meal_type_charges_${m_count}" style="display:none;padding: 15px;"> 
                                <label for="">Additional Meal Charges</label>
                                <input id="" type="text" class="form-control" name="more_additional_meal_type_charges[]"  >
                            </div>
                            <div class="col-md-6 d-none" id="meal_policy" style="padding: 15px;"> 
                        <label for="">MEAL POLICY</label>
                        <select name="meal_policy[]" class=" form-control select2-multiple" data-toggle="select2" >
                                       
                                           <option value="no">No Meal Option</option>

                                        <option value="Breakfast is included in room rates">Breakfast is included in room rates</option>
                                        
                                        <option value="Breakfast costs">Breakfast costs</option>
                                        
                                        <option value="Half Board">Half Board </option>
                                        
                                        <option value="Full Board">Full Board </option>
                                        
                                        <option value="With Iftar">With Iftar </option>
                                        
                                        <option value="room only">Room only</option>
                                        
                                        <option value="Sahoor only">Sahoor only</option>
                                        
                                        <option value="Sahour and Iftar">Sahour & Iftar</option>
                                        
                                        <option value="Sahour or iftar">Sahour or iftar</option>
                                        
                                        <option value="othermeal">Other meal</option>
                                    </select>
                        </div>
                         <div class="col-md-4"  style="padding: 15px;"> 
                        <label for="">CANCELLATION POLICY</label>
                        <select class="form-control" id="more_cancellation_policy_${m_count}" onchange="morecancellationpolicy(${m_count})" name="more_concellation_policy[]">
                                       
                                           <option value="free">Free cancellation</option>

                                            <option value="non-refundable">Non Refundable</option>
                                            
                                            <option value="paid">Cancellation costs</option>
                                            
                                            <option value="other">Other Cancellation Policy</option>
                                    </select>
                        </div>
                        
                        
                        <div class="row" id="more_cancellation_policy_show_${m_count}" style="display:none;">
                            <div class="col-md-6"> 
                        <label for="">How many days in advance can guests cancel free of charge?</label>
                        <select class="form-control" name="more_guests_pay_days[]">
                                       <option value="Day of arrival" class="form-control">Day of arrival (6 pm)</option>

                                        <option value="1 day">1 day</option>
                                        
                                        <option value="2 days">2 days</option>
                                        
                                        <option value="3 days">3 days</option>
                                        
                                        <option value="7 days">7 days</option>
                                        
                                        <option value="14 days">14 days</option>
                                    </select>
                        </div>
                        <div class="col-md-6" style="margin-top: 20px;"> 
                        <label for="">or guests will pay 100%</label>
                        <select class="form-control" name="more_guests_pay[]">
                                       <option value="of the first night">of the first night</option>

                                        <option value="of the full stay">of the full stay</option> 
                                    </select>
                        </div>
                        </div>
                        <div class="col-md-6"  style="padding: 15px;"> 
                        <label for="">PREPAYMENT POLICY</label>
                        <select class="form-control" id="PREPAYMENTPOLICY_${m_count}" name="more_prepaymentpolicy[]">
                                       
                                           <option value="no">No deposit will be charged</option>

                                            <option value="yes">The total price of the reservation may be charged anytime after booking</option>
                                    </select>
                        </div>
                            
                             
    
                        <div class="col-md-6" style="padding: 15px;">
                             <label for="">Select Room Price (Price Per Night)</label>
                             <input type="text" value="${m_count}" name="more_counter[]" hidden>
                              <select class="form-control calculateMarkup" id="week_price_type${m_count}" counter="${m_count}" onclick="more_week_price_type_function(${m_count})" name="week_price_type[]">
                                   <option value="for_all_days">Room Price for All Days</option>
                                   <option value="for_week_end">Room Price for Weekdays/Weekend</option>
                             </select>
                            
                        </div>
                        
                        <div class="col-md-12 more_week_prices_fixed${m_count}" id="price_for_all_days" style="padding: 15px;">
                            <label for="">Price For All Days</label>
                            <input id="price_all_days${m_count}" counter="${m_count}"  type="number" step=".01" class="form-control calculateMarkup" name="more_price_all_days[]" autocomplete="price_all_days" autofocus>
                        </div>
                        
                        <div class="col-md-12 week_prices_fixed" id="price_for_all_days" style="padding: 15px;">
                                <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="more_rooms_on_rq[]" value="1" id="m_rooms_on_rq">
                            <label class="form-check-label" for="m_rooms_on_rq">
                                Rooms On Request
                            </label>
                        </div>
                            </div>
    
                        <div class="col-md-6 more_week_prices${m_count} d-none" id="" style="padding: 15px;">
                            <label for="">Choose  WeekDays</label>
                            <div class="form-check">
                                <input class="form-check-input week_prices${m_count}" type="checkbox" name="more_weekdays_${m_count}[]" value="Monday" id="Monday_${m_count}">
                                <label class="form-check-label" for="Monday_${m_count}">
                                     Monday
                                </label>
                            </div>
    
                            <div class="form-check">
                                <input class="form-check-input week_prices${m_count}" type="checkbox" name="more_weekdays_${m_count}[]" value="Tuesday" id="Tuesday_${m_count}">
                                <label class="form-check-label" for="Tuesday_${m_count}">
                                     Tuesday
                                </label>
                            </div>
    
                            <div class="form-check">
                                <input class="form-check-input week_prices${m_count}" type="checkbox" name="more_weekdays_${m_count}[]" value="Wednesday" id="Wednesday_${m_count}">
                                <label class="form-check-label" for="Wednesday_${m_count}">
                                     Wednesday
                                </label>
                            </div>
    
                            <div class="form-check">
                                <input class="form-check-input week_prices${m_count}" type="checkbox" name="more_weekdays_${m_count}[]" value="Thursday" id="Thursday_${m_count}">
                                <label class="form-check-label" for="Thursday_${m_count}">
                                     Thursday
                                </label>
                            </div>
    
                            <div class="form-check">
                                <input class="form-check-input week_prices${m_count}" type="checkbox" name="more_weekdays_${m_count}[]" value="Friday" id="Friday_${m_count}">
                                <label class="form-check-label" for="Friday_${m_count}">
                                     Friday
                                </label>
                            </div>
    
                            <div class="form-check">
                                <input class="form-check-input week_prices${m_count}" type="checkbox" name="more_weekdays_${m_count}[]" value="Saturday" id="Saturday_${m_count}">
                                <label class="form-check-label" for="Saturday_${m_count}">
                                     Saturday
                                </label>
                            </div>
    
                            <div class="form-check">
                                <input class="form-check-input week_prices${m_count}" type="checkbox" name="more_weekdays_${m_count}[]" value="Sunday" id="Sunday_${m_count}">
                                <label class="form-check-label" for="Sunday_${m_count}">
                                     Sunday
                                </label>
                            </div>
    
                            <div class="col-md-12">
                                <label for="">Price for WeekDays</label>
                                <input id="week_days_price${m_count}" type="number" step=".01" class="form-control calculateMarkup" counter="${m_count}" name="more_week_days_price[]" autocomplete="week_days_price" autofocus>
                            </div> 
                        </div>
    
                        <div class="col-md-6 more_week_prices${m_count} d-none" id="" style="padding: 15px;">
                            <label for="">Chose WeekdEnd</label>
                            <div class="form-check">
                                <input class="form-check-input week_prices${m_count}" type="checkbox" name="more_weekend_${m_count}[]" value="Monday" id="Monday1_${m_count}">
                                <label class="form-check-label" for="Monday1_${m_count}">
                                     Monday
                                </label>
                            </div>
    
                            <div class="form-check">
                                <input class="form-check-input week_prices${m_count}" type="checkbox" name="more_weekend_${m_count}[]" value="Tuesday" id="Tuesday1_${m_count}">
                                <label class="form-check-label" for="Tuesday1_${m_count}">
                                     Tuesday
                                </label>
                            </div>
    
                            <div class="form-check">
                                <input class="form-check-input week_prices${m_count}" type="checkbox" name="more_weekend_${m_count}[]" value="Wednesday" id="Wednesday1_${m_count}">
                                <label class="form-check-label" for="Wednesday1_${m_count}">
                                     Wednesday
                                </label>
                            </div>
    
                            <div class="form-check">
                                <input class="form-check-input week_prices${m_count}" type="checkbox" name="more_weekend_${m_count}[]" value="Thursday" id="Thursday1_${m_count}">
                                <label class="form-check-label" for="Thursday1_${m_count}">
                                     Thursday
                                </label>
                            </div>
    
                            <div class="form-check">
                                <input class="form-check-input week_prices${m_count}" type="checkbox" name="more_weekend_${m_count}[]" value="Friday" id="Friday1_${m_count}">
                                <label class="form-check-label" for="Friday1_${m_count}">
                                     Friday
                                </label>
                            </div>
    
                            <div class="form-check">
                                <input class="form-check-input week_prices${m_count}" type="checkbox" name="more_weekend_${m_count}[]" value="Saturday" id="Saturday1_${m_count}">
                                <label class="form-check-label" for="Saturday1_${m_count}">
                                     Saturday
                                </label>
                            </div>
    
                            <div class="form-check">
                                <input class="form-check-input week_prices${m_count}" type="checkbox" name="more_weekend_${m_count}[]" value="Sunday" id="Sunday1_${m_count}">
                                <label class="form-check-label" for="Sunday1_${m_count}">
                                     Sunday
                                </label>
                            </div>
    
                            <div class="col-md-12">
                                <label for="">Price for WeekdEnd</label>
                                <input id="week_end_price${m_count}" counter="${m_count}" type="number" step=".01" class="form-control calculateMarkup" name="more_week_end_price[]" autocomplete="week_end_price" autofocus>
                            </div>
                        </div>
                        
                        
                        <div class="col-md-12" id="" style="padding: 15px;">
                                     <input class="form-check-input display_on_website" counter="${m_count}" onclick="displayOnWebMore(${m_count})" type="checkbox" name="display_on_website_more[]" value="true" id="display_on_website${m_count}">
                                    <label class="form-check-label" for="display_on_website${m_count}">
                                         Display on Website
                                    </label>
                                    <div class="row" id="display_markup${m_count}" style="display:none;">
                                        <div class="col-md-3">
                                            <label for="">Select Markup Type</label>
                                            <select class="form-control calculateMarkup" name="markup_type_more[]" counter="${m_count}"  id="markup_type${m_count}">
                                                <option value="Fixed">Fixed</option>
                                                <option value="Percentage">Percentage</option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <label for="">Markup Value</label>
                                            <input id="one_night_markup${m_count}" type="text" counter="${m_count}" class="form-control calculateMarkup" name="one_night_markup_more[]">
                                        </div>
                                        
                                        <div class="col-md-3 more_week_prices_all${m_count}">
                                            <label for="">One Room Price</label>
                                            <input id="one_night_mark_all${m_count}" readonly type="text" class="form-control " name="one_room_all_more[]">
                                            <p style="font-size: 12px;">Per Room per Night</p>
                                        </div>
                                        
                                        <div class="col-md-3 more_week_prices${m_count} d-none">
                                            <label for="">Week Days Price</label>
                                            <input id="one_night_mark_week${m_count}" readonly type="text" class="form-control " name="one_room_all_week_more[]">
                                            <p style="font-size: 12px;">Per Room per Night</p>
                                        </div>
                                        
                                        <div class="col-md-3 more_week_prices${m_count} d-none">
                                            <label for="">Weekend Days Price</label>
                                            <input id="one_night_mark_weekend${m_count}"  readonly type="text" class="form-control " name="one_room_all_weekend_more[]">
                                            <p style="font-size: 12px;">Per Room per Night</p>
                                        </div>
                                    </div>
                                
                            </div>
                            
                            <div class="col-md-12">
                                <button style="float: right;" class="btn btn-primary" type="button" id="remove_more_room_type" onclick="remove_M_R_T_button(${m_count})">Remove</button>
                            </div>
                    </div>
                    `;
                    
  
        
        $('#more_room_type_div').append(data);
        m_count1 = m_count1 + 1;
        
        $('#city_view'+room_count+'').val(city_view).change();
        
          var supplier_name = $('#room_supplier_name').val();
        $('#room_supplier_name_'+room_count+'').val(supplier_name).change();
        
        
        console.log('room_av_from_'+m_count+'');
        
        var today = new Date().toISOString().split('T')[0];
        // document.getElementById('room_av_from_'+m_count+'').setAttribute('min', today);
        // document.getElementById('room_av_to_'+m_count+'').setAttribute('min', today);
        
        $(".week_prices"+m_count+"").click(function(){
            var id = $(this).attr('id');
            console.log('The id is '+id);
            if ($("#Monday_"+m_count+"").is(':checked')){
                $("#Monday1_"+m_count+"").prop("disabled", true);
            }else{
                $("#Monday1_"+m_count+"").prop("disabled", false);
            }
    
            if ($("#Monday1_"+m_count+"").is(':checked')){
                $("#Monday_"+m_count+"").prop("disabled", true);
            }else{
                $("#Monday_"+m_count+"").prop("disabled", false);
            }
    
            if ($("#Tuesday_"+m_count+"").is(':checked')){
                $("#Tuesday1_"+m_count+"").prop("disabled", true);
            }else{
                $("#Tuesday1_"+m_count+"").prop("disabled", false);
            }
    
            if ($("#Tuesday1_"+m_count+"").is(':checked')){
                $("#Tuesday_"+m_count+"").prop("disabled", true);
            }else{
                $("#Tuesday_"+m_count+"").prop("disabled", false);
            }
    
            if ($("#Wednesday_"+m_count+"").is(':checked')){
                $("#Wednesday1_"+m_count+"").prop("disabled", true);
            }else{
                $("#Wednesday1_"+m_count+"").prop("disabled", false);
            }
    
            if ($("#Wednesday1_"+m_count+"").is(':checked')){
                $("#Wednesday_"+m_count+"").prop("disabled", true);
            }else{
                $("#Wednesday_"+m_count+"").prop("disabled", false);
            }
    
            if ($("#Thursday_"+m_count+"").is(':checked')){
                $("#Thursday1_"+m_count+"").prop("disabled", true);
            }else{
                $("#Thursday1_"+m_count+"").prop("disabled", false);
            }
    
            if ($("#Thursday1_"+m_count+"").is(':checked')){
                $("#Thursday_"+m_count+"").prop("disabled", true);
            }else{
                $("#Thursday_"+m_count+"").prop("disabled", false);
            }
    
            if ($("#Friday_"+m_count+"").is(':checked')){
                $("#Friday1_"+m_count+"").prop("disabled", true);
            }else{
                $("#Friday1_"+m_count+"").prop("disabled", false);
            }
    
            if ($("#Friday1_"+m_count+"").is(':checked')){
                $("#Friday_"+m_count+"").prop("disabled", true);
            }else{
                $("#Friday_"+m_count+"").prop("disabled", false);
            }
    
            if ($("#Saturday_"+m_count+"").is(':checked')){
                $("#Saturday1_"+m_count+"").prop("disabled", true);
            }else{
                $("#Saturday1_"+m_count+"").prop("disabled", false);
            }
    
            if ($("#Saturday1_"+m_count+"").is(':checked')){
                $("#Saturday_"+m_count+"").prop("disabled", true);
            }else{
                $("#Saturday_"+m_count+"").prop("disabled", false);
            }
    
            if ($("#Sunday_"+m_count+"").is(':checked')){
                $("#Sunday1_"+m_count+"").prop("disabled", true);
            }else{
                $("#Sunday1_"+m_count+"").prop("disabled", false);
            }
    
            if ($("#Sunday1_"+m_count+"").is(':checked')){
                $("#Sunday_"+m_count+"").prop("disabled", true);
            }else{
                $("#Sunday_"+m_count+"").prop("disabled", false);
            }
        
        });
        
            $('.calculateMarkup').on('keyup change',function(){
                 var value = $(this).attr('counter');
                 console.log('value is '+value);
                CalculateMarkup(value);
            })
        
 
        
        
        
    
    })
    
    function remove_M_R_T_button(id){
        $('#M_R_T_div'+id+'').remove();
    }
    function setfunchange(id)
        {
    
             var additional_meal_type =  $('#more_additional_meal_type_'+id+'').val();;
                console.log(additional_meal_type);
                
                
             if(additional_meal_type !== 'room only' && additional_meal_type !== 'no')
             {
                console.log(additional_meal_type);
               $('#more_additional_meal_type_charges_'+id+'').css('display','block');
             }else{
                 $('#more_additional_meal_type_charges_'+id+'').css('display','none');
             }
             
   
        }
        
        function morecancellationpolicy(id)
        {
          var cancellation_policy = $('#more_cancellation_policy_'+id+'').val();
          if(cancellation_policy == 'other')
 {
    
   $('#more_cancellation_policy_show_'+id+'').fadeOut();
 }
 if(cancellation_policy == 'free')
 {
$('#more_cancellation_policy_show_'+id+'').fadeOut();
 }
 if(cancellation_policy == 'non-refundable')
 {
 $('#more_cancellation_policy_show_'+id+'').fadeOut();
 }
  if(cancellation_policy == 'paid')
 {
 $('#more_cancellation_policy_show_'+id+'').fadeIn();
 }
        }
        
    
    function more_week_price_type_button(id){
        console.log('id : '+id);
        var value = $('.more_week_price_type'+id+'').val();
        console.log('value : '+value);
        if(value == 'for_week_end'){
            console.log('if');
            $('.more_week_prices'+id+'').removeClass("d-none");
            $('.more_week_prices_fixed'+id+'').addClass("d-none");
            $('#more_week_days_price'+id+'').attr('required',true);
            $('#more_week_end_price'+id+'').attr('required',true);
            $('#more_price_all_days'+id+'').attr('required',false);
            
        }else{
            console.log('else');
            $('.more_week_prices'+id+'').addClass("d-none");
            $('.more_week_prices_fixed'+id+'').removeClass("d-none");
            $('#more_week_days_price'+id+'').attr('required',false);
            $('#more_week_end_price'+id+'').attr('required',false);
            $('#more_price_all_days'+id+'').attr('required',true);
        }
    }
    
    function more_week_price_type_function(id){
        console.log('id : '+id);
        var value = $('#week_price_type'+id+'').val();
        console.log('value : '+value);
        if(value == 'for_week_end'){
            console.log('if');
            $('.more_week_prices'+id+'').removeClass("d-none");
            $('.more_week_prices_fixed'+id+'').addClass("d-none");
            $('#more_week_days_price'+id+'').attr('required',true);
            $('#more_week_end_price'+id+'').attr('required',true);
            $('#more_price_all_days'+id+'').attr('required',false);
            $('.more_week_prices_all'+id+'').css('display','none');
            
        }else{
            console.log('else');
            $('.more_week_prices'+id+'').addClass("d-none");
            $('.more_week_prices_fixed'+id+'').removeClass("d-none");
            $('#more_week_days_price'+id+'').attr('required',false);
            $('#more_week_end_price'+id+'').attr('required',false);
            $('#more_price_all_days'+id+'').attr('required',true);
            $('.more_week_prices_all'+id+'').css('display','block');
        }
        
        // displayOnWebMore(id)
        CalculateMarkup(id);
    }
    
    $('#addInvoiceRoomSupplier').on('click',function(e){
        e.preventDefault();
        let room_supplier_name = $('#room_supplier_name1').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ url('/submit_invoiceRoomSupplier') }}",
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                room_supplier_name:room_supplier_name,
            },
            success: function(result){
                if(result){
                    var RI_suppliers = result['RI_suppliers'];
                    console.log(RI_suppliers);
                    $("#room_supplier_name").empty();
                    $.each(RI_suppliers, function(key, value) {
                        var room_supplier_Data = `<option attr="${value.id}" value="${value.id}"> ${value.room_supplier_name}</option>`;
                        $("#room_supplier_name").append(room_supplier_Data);
                    });
                    alert('Room Supplier Added SuccessFUl!');
                }
                $('#success-message').text(result.success);
            },
        });
        
    });
  
  
  
  $('#additional_meal_type').on('change', function() {
 var additional_meal_type = ( this.value );
 //alert(additional_meal_type);
 if(additional_meal_type == 'room only'  || additional_meal_type == 'no')
 {
    
   $('#additional_meal_type_charges').fadeOut();
 }
 else
 {
  $('#additional_meal_type_charges').fadeIn();    
 }
//  if(additional_meal_type == 'Lunch')
//  {
//   $('#additional_meal_type_charges').fadeIn();   
//  }
//  if(additional_meal_type == 'Dinner')
//  {
//     $('#additional_meal_type_charges').fadeIn(); 
//  }
//   if(additional_meal_type == 'Room only')
//  {
//     $('#additional_meal_type_charges').fadeOut(); 
//  }
});



  $('#cancellation_policy').on('change', function() {
 var cancellation_policy = ( this.value );
 //alert(cancellation_policy);
 if(cancellation_policy == 'other')
 {
    
   $('#cancellation_policy_show').fadeOut();
 }
 if(cancellation_policy == 'free')
 {
$('#cancellation_policy_show').fadeOut(); 
 }
 if(cancellation_policy == 'non-refundable')
 {
  $('#cancellation_policy_show').fadeOut();
 }
  if(cancellation_policy == 'paid')
 {
  $('#cancellation_policy_show').fadeIn();
 }
});
  
  
  
  $('#add_more_cancellation').click(function() {
 
console.log('hereee');
  
  var data=`<div class="col-md-12"  style="padding: 15px;"> 
                        <label for="">CANCELLATION POLICY</label>
                        <div class="input-group" id="timepicker-input-group1">
                        <select class="form-control" id="cancellation_policy" name="concellation_policy">
                                       
                                           <option value="free">Free cancellation</option>

                                            <option value="non-refundable">Non Refundable</option>
                                            
                                            <option value="paid">Cancellation costs</option>
                                            
                                           
                                    </select>
                                   
                        </div>
                        
                        <button style="float: right;" class="btn btn-primary bootstrap-touchspin-up"type="button">Remove</button>
                          </div>`;
                          
                            $('#append_add_more_cancellation').append(data);
                          
    });    
  
</script>

@stop



