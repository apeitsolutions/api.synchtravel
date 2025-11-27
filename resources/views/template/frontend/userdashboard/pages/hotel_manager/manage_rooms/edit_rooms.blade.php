
<?php

//print_r($roomData);die();
?>



@extends('template/frontend/userdashboard/layout/default')
@section('content')


<?php 
    function checkWeekDays($days,$name){
        
        $found = false;
        if(!empty($days)){
             foreach($days as $day_res){
            
                if($day_res == $name){
                    $found = true;
                    echo "checked";
                }
            }
            
            if(!$found){
                echo "disabled";
            }
        }
       
    }
    
    function checkmealOptions($types,$name){
        
        $found = false;
        if(!empty($types)){
             foreach($types as $day_res){
            
                if($day_res == $name){
                    $found = true;
                    echo "selected";
                }
            }
            
           
        }
       
    }


?>
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
    <h4 style="color:#a30000">Edit Room</h4>
    <form action="{{ url('hotel_manger/update_room/'.$roomData->id.'') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
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
                                  if($hotel_res->id == $selected_hotel)
                                  {
                                  ?>
                                    <option value="{{ $hotel_res->id }}">{{ $hotel_res->property_name }}</option>
                                    <?php
                                  }
                                    ?>
                                  @endforeach
                                </select>
                            </div>
                            <div class="col-md-6" style="padding: 15px;">
                                <label for="">Room Type</label>
                                <select name="room_type" id="room_type0" onchange="get_rooms_type(0)" required class="form-control">
                                  @foreach($roomTypes as $room_res)
                                    <option room-type="{{ $room_res->parent_cat }}" value='{{ json_encode($room_res) }}' @if($roomData->room_type_cat == $room_res->id) selected  @endif >{{ $room_res->room_type }}</option>
                                  @endforeach
                                </select>
                            </div>
                            <div class="col-md-6" style="padding: 15px;">
                                <label for="">Room View</label>
                                <select name="room_view" id="city_view" class="form-control">
                                    <option value="City View" @if($roomData->room_view == 'City View') selected  @endif>City View</option>
                                    <option value="Haram View" @if($roomData->room_view == 'Haram View') selected  @endif>Haram View</option>
                                    <option value="Kabbah View" @if($roomData->room_view == 'Kabbah View') selected  @endif>Kabbah View</option>
                                    <option value="Partial Haram View" @if($roomData->room_view == 'Partial Haram View') selected  @endif>Partial Haram View</option>
                                    <option value="Patio View" @if($roomData->room_view == 'Patio View') selected  @endif>Patio View</option>
                                    <option value="Towers View" @if($roomData->room_view == 'Towers View') selected  @endif>Towers View</option>
                                </select>
                            </div>
                           

                            
                            <div class="col-md-4" style="padding: 15px;">
                                <label for="">Room Meal Type</label>
                                <select id="more_room_meal_type" type="date" class="form-control" name="room_meal_type" autofocus required>
                                    <option <?php if($roomData->room_meal_type == 'Room only') echo "selected"; ?> value="Room only">Room only</option>
                                    <option <?php if($roomData->room_meal_type == 'Breakfast') echo "selected"; ?> value="Breakfast">Breakfast</option>
                                    <option <?php if($roomData->room_meal_type == 'Lunch') echo "selected"; ?> value="Lunch">Lunch</option>
                                    <option <?php if($roomData->room_meal_type == 'Dinner') echo "selected"; ?> value="Dinner">Dinner</option>
                                    <option <?php if($roomData->room_meal_type == 'Suhoor') echo 'selected'; ?> value="Suhoor">Suhoor</option>
                                    <option <?php if($roomData->room_meal_type == 'Iftar') echo 'selected'; ?> value="Iftar">Iftar</option>
                                </select>
                            </div> 
                            
                            <div class="col-md-4" style="padding: 15px;">
                                <label for="">Room availability (From)</label>
                                <input id="start_date" type="date" class="form-control" requried name="room_av_from" value="{{ $roomData->availible_from }}" autocomplete="room_av_from" autofocus>

                                
                            </div> 

                            <div class="col-md-4" style="padding: 15px;">
                                <label for="">Room availability (To)</label>
                                <input id="end_date" type="date" class="form-control" requried name="room_av_to" value="{{ $roomData->availible_to }}" autocomplete="room_av_to" autofocus>

                                
                            </div>
                            <div class="col-md-4" style="padding: 15px;">
                                <label for="">Option Date</label>
                                <input id="option_date" type="date" class="form-control"  name="option_date" value="{{ $roomData->room_option_date }}" autocomplete="room_av_to" autofocus>

                                
                            </div>
                            
                          <div class="col-md-4" style="padding: 15px;"> 
                        <label for="">Quantity</label>
                        <input id="quantity_room" type="text" class="form-control" requried name="quantity" value="{{ $roomData->quantity }}" autocomplete="quantity" autofocus>

                       
                            </div>
                        <div class="col-md-4" style="padding: 15px;"> 
                        <label for="">Min Stay</label>
                        <input id="min_stay" type="text" class="form-control" name="min_stay" value="{{ $roomData->min_stay }}" autocomplete="min_stay" autofocus>

                        
</div>
 <div class="col-md-4" style="padding: 15px;"> 
                        <label for="">Max Adults</label>
                        <select name="max_adults" id="room_adults0" class="form-control">
                            <option value="1" @if($roomData->max_adults == '1') selected  @endif >1</option>
                            <option value="2" @if($roomData->max_adults == '2') selected  @endif >2</option>
                            <option value="3" @if($roomData->max_adults == '3') selected  @endif >3</option>
                            <option value="4" @if($roomData->max_adults == '4') selected  @endif >4</option>
                            <option value="5" @if($roomData->max_adults == '5') selected  @endif >5</option>
                            <option value="6" @if($roomData->max_adults == '6') selected  @endif >6</option>
                            <option value="7" @if($roomData->max_adults == '7') selected  @endif >7</option>
                            <option value="8" @if($roomData->max_adults == '8') selected  @endif >8</option>
                            <option value="9" @if($roomData->max_adults == '9') selected  @endif >9</option>
                            <option value="10" @if($roomData->max_adults == '10') selected  @endif >10</option>
                        </select>
</div>
 <div class="col-md-4" style="padding: 15px;"> 
                        <label for="">Max Children</label>
                        <select name="max_childrens" id="" class="form-control">
                            <option value="1" @if($roomData->max_child == '1') selected  @endif >1</option>
                            <option value="2" @if($roomData->max_child == '2') selected  @endif >2</option>
                            <option value="3" @if($roomData->max_child == '3') selected  @endif >3</option>
                            <option value="4" @if($roomData->max_child == '4') selected  @endif >4</option>
                            <option value="5" @if($roomData->max_child == '5') selected  @endif >5</option>
                            <option value="6" @if($roomData->max_child == '6') selected  @endif >6</option>
                            <option value="7" @if($roomData->max_child == '7') selected  @endif >7</option>
                            <option value="8" @if($roomData->max_child == '8') selected  @endif >8</option>
                            <option value="9" @if($roomData->max_child == '9') selected  @endif >9</option>
                            <option value="10" @if($roomData->max_child == '10') selected  @endif >10</option>
                        </select>
</div>
 <div class="col-md-4" style="padding: 15px;"> 
                        <label for="">No of Extra Beds</label>
                        <input id="extra_beds" type="text" class="form-control " name="extra_beds" value="{{ $roomData->extra_beds }}" autocomplete="extra_beds" autofocus>

                       
</div>
 <div class="col-md-4" style="padding: 15px;"> 
                        <label for="">Extra Bed Charges</label>
                        <input id="extra_beds_charges" type="text" class="form-control " name="extra_beds_charges" value="{{ $roomData->extra_beds_charges }}" autocomplete="extra_beds_charges" autofocus>

                       
                        
</div>

<div class="col-md-4" style="padding: 15px;">
                                <label for="">Room Suppliers</label>
                                <div class="input-group" id="timepicker-input-group1">
                                    <select id="room_supplier" type="date" class="form-control" name="room_supplier_name" autofocus required>
                                        @foreach($rooms_Invoice_Supplier1 as $rooms_Invoice_Supplier1)
                                        <option value="{{$rooms_Invoice_Supplier1->id}}" @if($rooms_Invoice_Supplier1->id == $roomData->room_supplier_name) selected  @endif>{{$rooms_Invoice_Supplier1->room_supplier_name}}</option>
                                        @endforeach
                                    </select>            
                                    
                                </div>
                            </div>
                            
                            <div class="col-md-4" style="padding: 15px;">
                                <label for="">Additional Meal Type</label>
                                <select name="additional_meal_type" id="additional_meal_type" class="form-control">
                                    <option value="">Select Additional Meal Type</option>
                                    <option value="no" @if($roomData->additional_meal_type == 'no') selected  @endif>No Meal Option</option>

                                        <option value="Breakfast is included in room rates" @if($roomData->additional_meal_type == 'Breakfast is included in room rates') selected  @endif>Breakfast is included in room rates</option>
                                        
                                        <option value="Breakfast costs" @if($roomData->additional_meal_type == 'Breakfast costs') selected  @endif>Breakfast costs</option>
                                        
                                        <option value="Half Board" @if($roomData->additional_meal_type == 'Half Board') selected  @endif>Half Board </option>
                                        
                                        <option value="Full Board" @if($roomData->additional_meal_type == 'Full Board') selected  @endif>Full Board </option>
                                        
                                        <option value="With Iftar" @if($roomData->additional_meal_type == 'With Iftar') selected  @endif>With Iftar </option>
                                        
                                        <option value="room only" @if($roomData->additional_meal_type == 'room only') selected  @endif>Room only</option>
                                        
                                        <option value="Sahoor only" @if($roomData->additional_meal_type == 'Sahoor only') selected  @endif>Sahoor only</option>
                                        
                                        <option value="Sahour and Iftar" @if($roomData->additional_meal_type == 'Sahour and Iftar') selected  @endif>Sahour & Iftar</option>
                                        
                                        <option value="Sahour or iftar" @if($roomData->additional_meal_type == 'Sahour or iftar') selected  @endif>Sahour or iftar</option>
                                        
                                        <option value="othermeal" @if($roomData->additional_meal_type == 'othermeal') selected  @endif>Other meal</option>
                            
                            <option value="Lunch" @if($roomData->additional_meal_type == 'Lunch') selected  @endif>Lunch</option>
                            <option value="Dinner" @if($roomData->additional_meal_type == 'Dinner') selected  @endif>Dinner</option>
                           
                        </select>
                            </div>
                             <div class="col-md-8"  id="additional_meal_type_charges" @if($roomData->additional_meal_type == 'no') style="display:none;padding: 15px;" @else style="padding: 15px;"  @endif > 
                                <label for="">Additional Meal Charges</label>
                                <input id="" type="text" class="form-control" value="{{ $roomData->additional_meal_type_charges }}" name="additional_meal_type_charges"  >
                            </div>
                        
                        <!--<div class="col-md-6" id="meal_policy[]" style="padding: 15px;"> -->
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
                        
                        @php 
                            $cancelation_details =json_decode($roomData->cancellation_details);
                            
                        @endphp
                         <div class="col-md-12"  style="padding: 15px;"> 
                        <label for="">CANCELLATION POLICY</label>
                        <div class="input-group" id="timepicker-input-group1">
                        <select class="form-control" id="cancellation_policy" name="concellation_policy">
                                       
                                           <option value="free" @if('free' == $cancelation_details->concellation_policy) selected  @endif>Free cancellation</option>

                                            <option value="non-refundable" @if('non-refundable' == $cancelation_details->concellation_policy) selected  @endif>Non Refundable</option>
                                            
                                            <option value="paid" @if('paid' == $cancelation_details->concellation_policy) selected  @endif>Cancellation costs</option>
                                            
                                           
                                    </select>
                                    <span title="Add More Cancellation" class="input-group-btn input-group-append" id="add_more_cancellation">
                                        <a class="btn btn-primary bootstrap-touchspin-up"type="button">+</a>
                                        </span>
                        </div>
                          </div>
                          <div id="append_add_more_cancellation">
                              
                          </div>
                        
                        
                        <div class="row" id="cancellation_policy_show" @if('paid' != $cancelation_details->concellation_policy) style="display:none;"  @endif>
                            <div class="col-md-6"> 
                        <label for="">How many days in advance can guests cancel free of charge?</label>
                        <select class="form-control" name="guests_pay_days">
                                       <option value="Day of arrival" class="form-control" @if('Day of arrival' == $cancelation_details->guests_pay_days) selected  @endif>Day of arrival (6 pm)</option>

                                        <option value="1 day" @if('1 day' == $cancelation_details->guests_pay_days) selected  @endif>1 day</option>
                                        
                                        <option value="2 days" @if('2 days' == $cancelation_details->guests_pay_days) selected  @endif>2 days</option>
                                        
                                        <option value="3 days" @if('3 days' == $cancelation_details->guests_pay_days) selected  @endif>3 days</option>
                                        
                                        <option value="7 days" @if('7 days' == $cancelation_details->guests_pay_days) selected  @endif>7 days</option>
                                        
                                        <option value="14 days" @if('14 days' == $cancelation_details->guests_pay_days) selected  @endif>14 days</option>
                                    </select>
                        </div>
                        <div class="col-md-6" style="margin-top: 20px;"> 
                        <label for="">or guests will pay 100%</label>
                        <select class="form-control" name="guests_pay">
                                       <option value="of the first night" @if('of the first night' == $cancelation_details->guests_pay) selected  @endif>of the first night</option>

                                        <option value="of the full stay" @if('of the full stay' == $cancelation_details->guests_pay) selected  @endif>of the full stay</option> 
                                    </select>
                        </div>
                        </div>
                        <div class="col-md-6"  style="padding: 15px;"> 
                        <label for="">PREPAYMENT POLICY</label>
                        <select class="form-control" id="PREPAYMENTPOLICY" name="prepaymentpolicy">
                                       
                                           <option value="no" @if('no' == $cancelation_details->prepaymentpolicy) selected  @endif>No deposit will be charged</option>

                                            <option value="yes" @if('yes' == $cancelation_details->prepaymentpolicy) selected  @endif>The total price of the reservation may be charged anytime after booking</option>
                                    </select>
                        </div>

                   

                            <div class="col-md-6" style="padding: 15px;">
                                 <label for="">Select Room Price (Price Per Night)</label>
                                 <select class="form-control" id="week_price_type0" name="week_price_type[]">
                                       <option value="for_all_days" @if($roomData->price_week_type == 'for_all_days') selected  @endif>Room Price for All Days</option>
                                       <option value="for_week_end" @if($roomData->price_week_type == 'for_week_end') selected  @endif>Room Price for Weekdays/Weekend</option>
                                </select>
                                
                            
                            </div>
                            <div class="col-md-12 week_prices_fixed @if($roomData->price_week_type == 'for_week_end') d-none  @endif" id="price_for_all_days" style="padding: 15px;">
                                <label for="">Price For All Days</label>
                                <input id="price_all_days0" type="number" step=".01" counter="0" class="calculateMarkup form-control @error('price_all_days') is-invalid @enderror" name="price_all_days" value="{{ $roomData->price_all_days }}" autocomplete="price_all_days" autofocus>

                                @error('price_all_days')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
<?php
$weekdays=json_decode($roomData->weekdays);


?>
                            <div class="col-md-6 week_prices @if($roomData->price_week_type == 'for_all_days') d-none  @endif" id="" style="padding: 15px;">
                                <label for="">Chose  WeekDays</label>
                                <div class="form-check">
                                    <input class="form-check-input week_prices" type="checkbox" name="weekdays[]" <?php if(isset($roomData->weekdays) && $roomData->weekdays != 'null') checkWeekDays($weekdays,'Monday'); ?>  value="Monday" id="Monday">
                                    <label class="form-check-label" for="Monday">
                                         Monday
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input week_prices" type="checkbox" name="weekdays[]" <?php if(isset($roomData->weekdays) && $roomData->weekdays != 'null') checkWeekDays($weekdays,'Tuesday'); ?>  value="Tuesday" id="Tuesday">
                                    <label class="form-check-label" for="Tuesday">
                                         Tuesday
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input week_prices" type="checkbox" name="weekdays[]" <?php if(isset($roomData->weekdays) && $roomData->weekdays != 'null') checkWeekDays($weekdays,'Wednesday'); ?>   value="Wednesday" id="Wednesday">
                                    <label class="form-check-label" for="Wednesday">
                                         Wednesday
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input week_prices" type="checkbox" name="weekdays[]" <?php if(isset($roomData->weekdays) && $roomData->weekdays != 'null') checkWeekDays($weekdays,'Thursday'); ?>   value="Thursday" id="Thursday">
                                    <label class="form-check-label" for="Thursday">
                                         Thursday
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input week_prices" type="checkbox" name="weekdays[]"  <?php if(isset($roomData->weekdays) && $roomData->weekdays != 'null') checkWeekDays($weekdays,'Friday'); ?>  value="Friday" id="Friday">
                                    <label class="form-check-label" for="Friday">
                                         Friday
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input week_prices" type="checkbox" name="weekdays[]" <?php if(isset($roomData->weekdays) && $roomData->weekdays != 'null') checkWeekDays($weekdays,'Saturday'); ?>   value="Saturday" id="Saturday">
                                    <label class="form-check-label" for="Saturday">
                                         Saturday
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input week_prices" type="checkbox" name="weekdays[]" <?php if(isset($roomData->weekdays) && $roomData->weekdays != 'null') checkWeekDays($weekdays,'Sunday'); ?>  value="Sunday" id="Sunday">
                                    <label class="form-check-label" for="Sunday">
                                         Sunday
                                    </label>
                                </div>

                                <div class="col-md-12">
                                    <label for="">Price for WeekDays</label>
                                    <input id="week_days_price0" type="number" counter="0" step=".01" class="form-control calculateMarkup @error('week_days_price') is-invalid @enderror" name="week_days_price" value="{{ $roomData->weekdays_price }}" autocomplete="week_days_price" autofocus>

                                    @error('week_days_price')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div> 


                            </div>
<?php
$weekend=json_decode($roomData->weekends);
//print_r($weekend);die();

?>
                            <div class="col-md-6 week_prices @if($roomData->price_week_type == 'for_all_days') d-none  @endif" id="" style="padding: 15px;">
                                <label for="">Chose WeekdEnd</label>
                                <div class="form-check">
                                    <input class="form-check-input week_prices" type="checkbox" name="weekend[]" <?php if(isset($roomData->weekends) && $roomData->weekends != 'null') checkWeekDays($weekend,'Monday'); ?>  value="Monday" id="Monday1">
                                    <label class="form-check-label" for="Monday1">
                                         Monday
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input week_prices" type="checkbox" name="weekend[]" <?php if(isset($roomData->weekends) && $roomData->weekends != 'null') checkWeekDays($weekend,'Tuesday'); ?> value="Tuesday" id="Tuesday1">
                                    <label class="form-check-label" for="Tuesday1">
                                         Tuesday
                                    </label>
                                </div>

                                <div class="form-check"> 
                                    <input class="form-check-input week_prices" type="checkbox" name="weekend[]" <?php if(isset($roomData->weekends) && $roomData->weekends != 'null') checkWeekDays($weekend,'Wednesday'); ?>  value="Wednesday" id="Wednesday1">
                                    <label class="form-check-label" for="Wednesday1">
                                         Wednesday
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input week_prices" type="checkbox" name="weekend[]" <?php if(isset($roomData->weekends) && $roomData->weekends != 'null') checkWeekDays($weekend,'Thursday'); ?> value="Thursday" id="Thursday1">
                                    <label class="form-check-label" for="Thursday1">
                                         Thursday
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input week_prices" type="checkbox" name="weekend[]" <?php if(isset($roomData->weekends) && $roomData->weekends != 'null') checkWeekDays($weekend,'Friday'); ?> value="Friday" id="Friday1">
                                    <label class="form-check-label" for="Friday1">
                                         Friday
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input week_prices" type="checkbox" name="weekend[]" <?php if(isset($roomData->weekends) && $roomData->weekends != 'null') checkWeekDays($weekend,'Saturday'); ?>  value="Saturday" id="Saturday1">
                                    <label class="form-check-label" for="Saturday1">
                                         Saturday
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input week_prices" type="checkbox" name="weekend[]" <?php if(isset($roomData->weekends) && $roomData->weekends != 'null') checkWeekDays($weekend,'Sunday'); ?>   value="Sunday" id="Sunday1">
                                    <label class="form-check-label" for="Sunday1">
                                         Sunday
                                    </label>
                                </div>

                                <div class="col-md-12">
                                    <label for="">Price for WeekdEnd</label>
                                    <input id="week_end_price0"  type="number" counter="0" step=".01" class="form-control calculateMarkup @error('week_end_price') is-invalid @enderror" name="week_end_price" value="{{ $roomData->weekends_price }}" autocomplete="week_end_price" autofocus>

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
                                     <input class="form-check-input display_on_website" counter="0" type="checkbox" @if($roomData->display_on_web == 'true') checked @endif name="display_on_website" value="true" id="display_on_website0">
                                    <label class="form-check-label" for="display_on_website0">
                                         Display on Website
                                    </label>
                                    <div class="row" id="display_markup0" @if($roomData->display_on_web !== 'true') style="display:none;" @endif>
                                        <div class="col-md-3">
                                            <label for="">Select Markup Type</label>
                                            <select class="form-control calculateMarkup" name="markup_type" counter="0"  id="markup_type0">
                                                <option value="Fixed" @if($roomData->markup_type == 'Fixed') selected @endif>Fixed</option>
                                                <option value="Percentage" @if($roomData->markup_type == 'Percentage') selected @endif>Percentage</option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <label for="">Markup Value</label>
                                            <input id="one_night_markup0" type="text" counter="0" value="{{ $roomData->markup_value }}" class="form-control calculateMarkup" name="one_night_markup">
                                        </div>
                                        
                                        <div class="col-md-3 week_prices_fixed @if($roomData->price_week_type !== 'for_all_days') d-none  @endif">
                                            <label for="">One Room Price</label>
                                            <input id="one_night_mark_all0" readonly type="text" class="form-control " value="{{ $roomData->price_all_days_wi_markup }}" name="one_night_all_days_markup">
                                            <p style="font-size: 12px;">Per Room per Night</p>
                                        </div>
                                        
                                        <div class="col-md-3 week_prices @if($roomData->price_week_type == 'for_all_days') d-none  @endif">
                                            <label for="">Week Days Price</label>
                                            <input id="one_night_mark_week0" readonly type="text" class="form-control " value="{{ $roomData->weekdays_price_wi_markup }}" name="one_night_all_week_days_markup">
                                            <p style="font-size: 12px;">Per Room per Night</p>
                                        </div>
                                        
                                        <div class="col-md-3 week_prices @if($roomData->price_week_type == 'for_all_days') d-none  @endif">
                                            <label for="">Weekend Days Price</label>
                                            <input id="one_night_mark_weekend0"  readonly type="text" class="form-control " value="{{ $roomData->weekends_price_wi_markup }}" name="one_night_all_weekend_days_markup">
                                            <p style="font-size: 12px;">Per Room per Night</p>
                                        </div>
                                    </div>
                                
                            </div>
                            
                            
                            <?php
                           //print_r(json_decode($roomData->more_room_type_details));die();
                           
                          $count=1;
                          $room_count = 1;
                            if($roomData->more_room_type_details != null)
                            {
                                $more_room_type_details=json_decode($roomData->more_room_type_details);
                                
                                    $get_room_type=$roomTypes;
                                
                            
                                
                                foreach($more_room_type_details as $detail)
                                {
                                $room_count++;
                                
                            
                            ?>
                            
                            <div class="row" id="M_R_T_div${m_count}">
                        <h3>More Room Type Details</h3>
                        <div class="col-md-6" style="padding: 15px;">
                            <label for="">Room Type</label>
                            <select name="more_room_type[]" id="room_type{{$room_count}}" onchange=" get_rooms_type({{$room_count}})" class="form-control">
                                @foreach($get_room_type as $type)
                               
                                <option <?php if($detail->more_room_type == $type->room_type) echo "selected"; ?> room-type="{{$type->parent_cat}}" value='{{ json_encode($type) }}'>{{$type->room_type}}</option>
                                @endforeach
                               
                            </select>
                        </div>
                        
         <div class="col-md-6" style="padding: 15px;">
                            <label for="">Room View</label>
                            <select name="more_room_view[]" id="" class="form-control">
                                <option <?php if($detail->more_room_view == 'City View') echo "selected"; ?> value="City View">City View</option>
                                <option <?php if($detail->more_room_view == 'Haram View') echo "selected"; ?> value="Haram View">Haram View</option>
                                <option <?php if($detail->more_room_view == 'Kabbah View') echo "selected"; ?> value="Kabbah View">Kabbah View</option>
                                <option <?php if($detail->more_room_view == 'Partial Haram View') echo "selected"; ?> value="Partial Haram View">Partial Haram View</option>
                                <option <?php if($detail->more_room_view == 'Patio View') echo "selected"; ?> value="Patio View">Patio View</option>
                                <option <?php if($detail->more_room_view == 'Towers View') echo "selected"; ?> value="Towers View">Towers View</option>
                            </select>
                        </div>
    
                        <div class="col-md-6" style="padding: 15px;">
                            <label for="">Room Image</label>
                            <input id="room_img" type="file" class="form-control"  name="more_room_img[]" autocomplete="room_img" autofocus>
                            <div class="col-md-3 m-2" style="padding: 15px;">
                                <img src="{{ asset('public/uploads/more_room_images') }}/{{ $detail->more_room_img }}" alt="" style="height: 100px;width: 100px;">
                            </div>
                        </div>
                        
                        <div class="col-md-4" style="padding: 15px;">
                            <label for="">Room Meal Type</label>
                            <select id="more_room_meal_type" type="date" class="form-control" name="more_room_meal_type[]" autofocus required>
                                <option <?php if($detail->more_room_meal_type == 'Room only') echo "selected"; ?> value="Room only">Room only</option>
                                <option <?php if($detail->more_room_meal_type == 'Breakfast') echo "selected"; ?> value="Breakfast">Breakfast</option>
                                <option <?php if($detail->more_room_meal_type == 'Lunch') echo "selected"; ?> value="Lunch">Lunch</option>
                                <option <?php if($detail->more_room_meal_type == 'Dinner') echo "selected"; ?> value="Dinner">Dinner</option>
                                <option <?php if($detail->more_room_meal_type == 'Suhoor') echo 'selected'; ?> value="Suhoor">Suhoor</option>
                                <option <?php if($detail->more_room_meal_type == 'Iftar') echo 'selected'; ?> value="Iftar">Iftar</option>
                            </select>
                        </div> 
                        
                        <div class="col-md-4" style="padding: 15px;">
                            <label for="">Room availability (From)</label>
                            <input id="room_av_from_{{$count}}" type="date" value="{{$detail->more_room_av_from ?? ''}}" class="form-control" name="more_room_av_from[]" autocomplete="room_av_from" autofocus>
                        </div> 
    
                        <div class="col-md-4" style="padding: 15px;">
                            <label for="">Room availability (To)</label>
                            <input id="room_av_to_{{$count}}" type="date" class="form-control" value="{{$detail->more_room_av_to ?? ''}}" name="more_room_av_to[]" autocomplete="room_av_to" autofocus>
                        </div>
                        <div class="col-md-4" style="padding: 15px;">
                            <label for="">Option Date</label>
                            <input id="" type="date" class="form-control" value="{{$detail->more_room_option_date ?? ''}}" name="more_option_date[]" autocomplete="room_av_to" autofocus>
                        </div>
                        <div class="col-md-4" style="padding: 15px;"> 
                        <label for="">Quantity</label>
                        <input id="quantity" type="text" class="form-control @error('quantity') is-invalid @enderror" name="more_quantity[]" value="{{$detail->more_quantity ?? ''}}" autocomplete="quantity" autofocus>

                        @error('quantity')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
</div>
  <div class="col-md-4" style="padding: 15px;"> 
                        <label for="">Min Stay</label>
                        <input id="min_stay_{{$count}}" type="text" class="form-control @error('min_stay') is-invalid @enderror" name="more_min_stay[]" value="{{$detail->more_min_stay ?? ''}}" autocomplete="min_stay" autofocus>

                        @error('min_stay')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
</div>
 <div class="col-md-4" style="padding: 15px;"> 
                        <label for="">Max Adults</label>
                        <select name="more_max_adults[]" id="room_adults{{$room_count}}" class="form-control">
                            <option <?php if(isset($detail->more_max_adults)){if($detail->more_max_adults == 1) echo "selected";} ?> value="1">1</option>
                            <option <?php if(isset($detail->more_max_adults)){if($detail->more_max_adults == 2) echo "selected";} ?> value="2">2</option>
                            <option <?php if(isset($detail->more_max_adults)){if($detail->more_max_adults == 3) echo "selected";} ?> value="3">3</option>
                            <option <?php if(isset($detail->more_max_adults)){if($detail->more_max_adults == 4) echo "selected";} ?> value="4">4</option>
                            <option <?php if(isset($detail->more_max_adults)){if($detail->more_max_adults == 5) echo "selected";} ?> value="5">5</option>
                            <option <?php if(isset($detail->more_max_adults)){if($detail->more_max_adults == 6) echo "selected";} ?> value="6">6</option>
                            <option <?php if(isset($detail->more_max_adults)){if($detail->more_max_adults == 7) echo "selected";} ?> value="7">7</option>
                            <option <?php if(isset($detail->more_max_adults)){if($detail->more_max_adults == 8) echo "selected";} ?> value="8">8</option>
                            <option <?php if(isset($detail->more_max_adults)){if($detail->more_max_adults == 9) echo "selected";} ?> value="9">9</option>
                            <option <?php if(isset($detail->more_max_adults)){if($detail->more_max_adults == 10) echo "selected";} ?> value="10">10</option>
                        </select>
</div>
 <div class="col-md-4" style="padding: 15px;"> 
                        <label for="">Max Children</label>
                        <select name="more_max_childrens[]" id="max_childrens_{{$count}}" class="form-control">
                           <option <?php  if(isset($detail->more_max_childrens)){if($detail->more_max_childrens == 1) echo "selected";} ?> value="1">1</option>
                            <option <?php if(isset($detail->more_max_childrens)){if($detail->more_max_childrens == 2) echo "selected";} ?> value="2">2</option>
                            <option <?php if(isset($detail->more_max_childrens)){if($detail->more_max_childrens == 3) echo "selected";} ?> value="3">3</option>
                            <option <?php if(isset($detail->more_max_childrens)){if($detail->more_max_childrens == 4) echo "selected";} ?> value="4">4</option>
                            <option <?php if(isset($detail->more_max_childrens)){if($detail->more_max_childrens == 5) echo "selected";} ?> value="5">5</option>
                            <option <?php if(isset($detail->more_max_childrens)){if($detail->more_max_childrens == 6) echo "selected";} ?> value="6">6</option>
                            <option <?php if(isset($detail->more_max_childrens)){if($detail->more_max_childrens == 7) echo "selected";} ?> value="7">7</option>
                            <option <?php if(isset($detail->more_max_childrens)){if($detail->more_max_childrens == 8) echo "selected";} ?> value="8">8</option>
                            <option <?php if(isset($detail->more_max_childrens)){if($detail->more_max_childrens == 9) echo "selected";} ?> value="9">9</option>
                            <option <?php if(isset($detail->more_max_childrens)){if($detail->more_max_childrens == 10) echo "selected";} ?> value="10">10</option>
                        </select>
</div>
 <div class="col-md-4" style="padding: 15px;"> 
                        <label for="">No of Extra Beds</label>
                        <input id="extra_beds_{{$count}}" type="text" class="form-control @error('extra_beds') is-invalid @enderror" name="more_extra_beds[]" value="{{ $detail->more_extra_beds ?? '' }}" autocomplete="extra_beds" autofocus>

                        @error('extra_beds')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
</div>
 <div class="col-md-4" style="padding: 15px;"> 
                        <label for="">Extra Bed Charges</label>
                        <input id="extra_beds_charges_{{$count}}" type="text" class="form-control @error('extra_beds_charges') is-invalid @enderror" name="more_extra_beds_charges[]" value="{{ $detail->more_extra_beds_charges ?? '' }}" autocomplete="extra_beds_charges" autofocus>

                        @error('extra_beds_charges')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        
</div>
                        <div class="col-md-4" style="padding: 15px;">
                            <label for="">Room Suppliers</label>
                            <div class="input-group" id="timepicker-input-group1">
                                <select id="room_supplier" type="date" class="form-control" name="more_room_supplier_name[]" autofocus required>
                                    @foreach($rooms_Invoice_Supplier as $rooms_Supplier)
                                    <option <?php if($detail->more_room_supplier_name == $rooms_Supplier->id) echo "selected"; ?> value="{{$rooms_Supplier->id}}">{{$rooms_Supplier->room_supplier_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-4" style="padding: 15px;">
                                <label for="">Additional Meal Type</label>
                                <select name="more_additional_meal_type[]" onchange="setfunchange(${m_count})" id="more_additional_meal_type_${m_count}" class="form-control">
                                    <option value="">Select Additional Meal Type</option>
                            <option value="Room only" <?php if(isset($detail->more_additional_meal_type)) if($detail->more_additional_meal_type == 'Room only') echo 'selected'; ?> >Room only</option>
                            <option value="Breakfast" <?php if(isset($detail->more_additional_meal_type)) if($detail->more_additional_meal_type == 'Breakfast') echo 'selected'; ?>>Breakfast</option>
                            <option value="Lunch" <?php if(isset($detail->more_additional_meal_type)) if($detail->more_additional_meal_type == 'Lunch') echo 'selected'; ?>>Lunch</option>
                            <option value="Dinner" <?php if(isset($detail->more_additional_meal_type)) if($detail->more_additional_meal_type == 'Dinner') echo 'selected'; ?>>Dinner</option>
                           
                        </select>
                            </div>
                            <div class="col-md-8" id="more_additional_meal_type_charges_${m_count}" style="display:none;padding: 15px;"> 
                        <label for="">Additional Meal Charges</label>
                        <input id="" type="text" class="form-control" value="{{$detail->more_additional_meal_type_charges ?? 0}}" name="more_additional_meal_type_charges[]"  >
                        </div>
                            <div class="col-md-4" id="meal_policy" style="padding: 15px;"> 
                        <label for="">MEAL POLICY</label>
                        <select name="meal_policy[]" class="form-control select2-multiple" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
                                       
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
                                       
                                           <option value="free" <?php if(isset($detail->more_concellation_policy)) if($detail->more_concellation_policy == 'free') echo 'selected'; ?>>Free cancellation</option>

                                            <option value="non-refundable" <?php if(isset($detail->more_concellation_policy)) if($detail->more_concellation_policy == 'non-refundable') echo 'selected'; ?>>Non Refundable</option>
                                            
                                            <option value="paid" <?php if(isset($detail->more_concellation_policy)) if($detail->more_concellation_policy == 'paid') echo 'selected'; ?>>Cancellation costs</option>
                                            
                                            <option value="other" <?php if(isset($detail->more_concellation_policy)) if($detail->more_concellation_policy == 'other') echo 'selected'; ?>>Other Cancellation Policy</option>
                                    </select>
                        </div>
                        
                        
                        <div class="row" id="more_cancellation_policy_show_${m_count}" <?php if(isset($detail->more_concellation_policy)) if($detail->more_concellation_policy !== 'paid') echo 'style="display:none;"'; ?> >
                            <div class="col-md-6"> 
                        <label for="">How many days in advance can guests cancel free of charge?</label>
                        <select class="form-control" name="more_guests_pay_days[]">
                                       <option value="Day of arrival" class="form-control" <?php if(isset($detail->more_guests_pay_days)) if($detail->more_guests_pay_days == 'Day of arrival') echo 'selected'; ?>>Day of arrival (6 pm)</option>

                                        <option value="1 day" <?php if(isset($detail->more_guests_pay_days)) if($detail->more_guests_pay_days == '1 day') echo 'selected'; ?>>1 day</option>
                                        
                                        <option value="2 days" <?php if(isset($detail->more_guests_pay_days)) if($detail->more_guests_pay_days == '2 days') echo 'selected'; ?>>2 days</option>
                                        
                                        <option value="3 days" <?php if(isset($detail->more_guests_pay_days)) if($detail->more_guests_pay_days == '3 days') echo 'selected'; ?>>3 days</option>
                                        
                                        <option value="7 days" <?php if(isset($detail->more_guests_pay_days)) if($detail->more_guests_pay_days == '7 days') echo 'selected'; ?>>7 days</option>
                                        
                                        <option value="14 days" <?php if(isset($detail->more_guests_pay_days)) if($detail->more_guests_pay_days == '14 days') echo 'selected'; ?>>14 days</option>
                                    </select>
                        </div>
                        <div class="col-md-6" style="margin-top: 20px;"> 
                        <label for="">or guests will pay 100%</label>
                        <select class="form-control" name="more_guests_pay[]">
                                       <option value="of the first night" <?php if(isset($detail->more_guests_pay)) if($detail->more_guests_pay == 'of the first night') echo 'selected'; ?>>of the first night</option>

                                        <option value="of the full stay" <?php if(isset($detail->more_guests_pay)) if($detail->more_guests_pay == 'of the full stay') echo 'selected'; ?>>of the full stay</option> 
                                    </select>
                        </div>
                        </div>
                        <div class="col-md-12 week_prices_fixed" id="price_for_all_days" style="padding: 15px;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="more_rooms_on_rq[]" <?php if(isset($detail->more_rooms_on_rq)) if(!empty($detail->more_rooms_on_rq)) echo 'checked'; ?> value="1" id="m_rooms_on_rq">
                                    <label class="form-check-label" for="m_rooms_on_rq">
                                        Rooms On Request
                                    </label>
                                </div>
                        </div>
                        <div class="col-md-6"  style="padding: 15px;"> 
                        <label for="">PREPAYMENT POLICY</label>
                        <select class="form-control" id="PREPAYMENTPOLICY_${m_count}" name="more_prepaymentpolicy[]">
                                       
                                           <option value="no" <?php if(isset($detail->more_prepaymentpolicy)) if($detail->more_prepaymentpolicy == 'no') echo 'selected'; ?>>No deposit will be charged</option>

                                            <option value="yes" <?php if(isset($detail->more_prepaymentpolicy)) if($detail->more_prepaymentpolicy == 'yes') echo 'selected'; ?>>The total price of the reservation may be charged anytime after booking</option>
                                    </select>
                        </div>
                        
                        
    
                        <div class="col-md-6" style="padding: 15px;">
                             <label for="">Select Room Price (Price Per Night)</label>
                             <input type="text" value="{{$count}}" name="more_counter[]" hidden>
                             <input type="text" value="{{$detail->room_gen_id ?? ''}}" hidden name="generate_codes[]" >
                              <select class="form-control" id="week_price_type{{$count}}" onclick="more_week_price_type_function({{$count}})" name="week_price_type[]">
                                   <option value="for_all_days" @if($detail->more_week_price_type == 'for_all_days') selected  @endif>Room Price for All Days</option>
                                   <option value="for_week_end" @if($detail->more_week_price_type == 'for_week_end') selected  @endif>Room Price for Weekdays/Weekend</option>
                             </select>
                             
                            
                        </div>
                        
                        <div class="col-md-12 more_week_prices_fixed{{$count}} @if($detail->more_week_price_type == 'for_week_end') d-none  @endif" id="price_for_all_days" style="padding: 15px;">
                            <label for="price_all_days{{$count}}">Price For All Days</label>
                            <input id="price_all_days{{$count}}" counter="{{$count}}" @if($detail->more_week_price_type == 'for_all_days') required  @endif  type="number" step=".01" class="form-control calculateMarkup" value="{{$detail->more_price_all_days ?? ''}}" name="more_price_all_days[]" autocomplete="price_all_days" autofocus>
                        </div>
                        
                        <?php 
                         $weekdays = json_decode($detail->more_weekdays);
                        ?>
                      
                        <div class="col-md-6 more_week_prices{{$count}} @if($detail->more_week_price_type == 'for_all_days') d-none  @endif" id="" style="padding: 15px;">
                            <label for="">Choose  WeekDays</label>
                            <div class="form-check">
                                <input class="form-check-input week_prices{{$count}}" type="checkbox" name="more_weekdays_{{$count}}[]" <?php if(isset($detail->more_weekdays) && $detail->more_weekdays != 'null') checkWeekDays($weekdays,'Monday');  ?>  value="Monday" check_count="{{$count}}" id="Monday_{{$count}}">
                                <label class="form-check-label" for="Monday_{{$count}}">
                                     Monday
                                </label>
                            </div>
    
                            <div class="form-check">
                                <input class="form-check-input week_prices{{$count}}" type="checkbox" name="more_weekdays_{{$count}}[]" <?php if(isset($detail->more_weekdays) && $detail->more_weekdays != 'null') checkWeekDays($weekdays,'Tuesday'); ?> value="Tuesday" check_count="{{$count}}" id="Tuesday_{{$count}}">
                                <label class="form-check-label" for="Tuesday_{{$count}}">
                                     Tuesday
                                </label>
                            </div>
    
                            <div class="form-check">
                                <input class="form-check-input week_prices{{$count}}" type="checkbox" name="more_weekdays_{{$count}}[]" <?php if(isset($detail->more_weekdays) && $detail->more_weekdays != 'null') checkWeekDays($weekdays,'Wednesday'); ?> value="Wednesday" check_count="{{$count}}" id="Wednesday_{{$count}}">
                                <label class="form-check-label" for="Wednesday_{{$count}}">
                                     Wednesday
                                </label>
                            </div>
    
                            <div class="form-check">
                                <input class="form-check-input week_prices{{$count}}" type="checkbox" name="more_weekdays_{{$count}}[]" <?php if(isset($detail->more_weekdays) && $detail->more_weekdays != 'null') checkWeekDays($weekdays,'Thursday'); ?> value="Thursday" check_count="{{$count}}" id="Thursday_{{$count}}">
                                <label class="form-check-label" for="Thursday_{{$count}}">
                                     Thursday
                                </label>
                            </div>
    
                            <div class="form-check">
                                <input class="form-check-input week_prices{{$count}}" type="checkbox" name="more_weekdays_{{$count}}[]" <?php if(isset($detail->more_weekdays) && $detail->more_weekdays != 'null') checkWeekDays($weekdays,'Friday'); ?> value="Friday" check_count="{{$count}}" id="Friday_{{$count}}">
                                <label class="form-check-label" for="Friday_{{$count}}">
                                     Friday
                                </label>
                            </div>
    
                            <div class="form-check"> 
                                <input class="form-check-input week_prices{{$count}}" type="checkbox" name="more_weekdays_{{$count}}[]" <?php if(isset($detail->more_weekdays) && $detail->more_weekdays != 'null') checkWeekDays($weekdays,'Saturday'); ?> value="Saturday" check_count="{{$count}}" id="Saturday_{{$count}}">
                                <label class="form-check-label" for="Saturday_{{$count}}">
                                     Saturday
                                </label>
                            </div>
    
                            <div class="form-check">
                                <input class="form-check-input week_prices{{$count}}" type="checkbox" name="more_weekdays_{{$count}}[]" <?php if(isset($detail->more_weekdays) && $detail->more_weekdays != 'null') checkWeekDays($weekdays,'Sunday'); ?> value="Sunday" check_count="{{$count}}" id="Sunday_{{$count}}">
                                <label class="form-check-label" for="Sunday_{{$count}}">
                                     Sunday
                                </label>
                            </div>
    
                            <div class="col-md-12">
                                <label for="week_days_price{{$count}}">Price for WeekDays</label>
                                <input id="week_days_price{{$count}}" type="number" counter="{{$count}}" step=".01" class="form-control calculateMarkup" value="{{$detail->more_week_days_price ?? ''}}" name="more_week_days_price[]" autocomplete="week_days_price" autofocus>
                            </div> 
                        </div>
            <?php 
            $weekenddays = json_decode($detail->more_weekend);
            ?>
                        <div class="col-md-6 more_week_prices{{$count}} @if($detail->more_week_price_type == 'for_all_days') d-none  @endif" id="" style="padding: 15px;">
                            <label for="">Chose WeekdEnd</label>
                            <div class="form-check">
                                <input class="form-check-input week_prices{{$count}}" type="checkbox" name="more_weekend_{{$count}}[]" <?php if(isset($detail->more_weekend) && $detail->more_weekend != 'null') checkWeekDays($weekenddays,'Monday'); ?> value="Monday" check_count="{{$count}}" id="Monday1_{{$count}}">
                                <label class="form-check-label" for="Monday1_{{$count}}">
                                     Monday
                                </label>
                            </div>
    
                            <div class="form-check">
                                <input class="form-check-input week_prices{{$count}}" type="checkbox" name="more_weekend_{{$count}}[]" <?php if(isset($detail->more_weekend) && $detail->more_weekend != 'null') checkWeekDays($weekenddays,'Tuesday'); ?> value="Tuesday" check_count="{{$count}}" id="Tuesday1_{{$count}}">
                                <label class="form-check-label" for="Tuesday1_{{$count}}">
                                     Tuesday
                                </label>
                            </div>
    
                            <div class="form-check">
                                <input class="form-check-input week_prices{{$count}}" type="checkbox" name="more_weekend_{{$count}}[]" <?php if(isset($detail->more_weekend) && $detail->more_weekend != 'null') checkWeekDays($weekenddays,'Wednesday'); ?> value="Wednesday" check_count="{{$count}}" id="Wednesday1_{{$count}}">
                                <label class="form-check-label" for="Wednesday1_{{$count}}">
                                     Wednesday
                                </label>
                            </div>
    
                            <div class="form-check">
                                <input class="form-check-input week_prices{{$count}}" type="checkbox" name="more_weekend_{{$count}}[]" <?php if(isset($detail->more_weekend) && $detail->more_weekend != 'null') checkWeekDays($weekenddays,'Thursday'); ?> value="Thursday" check_count="{{$count}}" id="Thursday1_{{$count}}">
                                <label class="form-check-label" for="Thursday1_{{$count}}">
                                     Thursday
                                </label>
                            </div>
    
                            <div class="form-check">
                                <input class="form-check-input week_prices{{$count}}" type="checkbox" name="more_weekend_{{$count}}[]" <?php if(isset($detail->more_weekend) && $detail->more_weekend != 'null') checkWeekDays($weekenddays,'Friday'); ?> value="Friday" check_count="{{$count}}" id="Friday1_{{$count}}">
                                <label class="form-check-label" for="Friday1_{{$count}}">
                                     Friday
                                </label>
                            </div>
    
                            <div class="form-check">
                                <input class="form-check-input week_prices{{$count}}" type="checkbox" name="more_weekend_{{$count}}[]" <?php if(isset($detail->more_weekend) && $detail->more_weekend != 'null') checkWeekDays($weekenddays,'Saturday'); ?> value="Saturday" check_count="{{$count}}" id="Saturday1_{{$count}}">
                                <label class="form-check-label" for="Saturday1_{{$count}}">
                                     Saturday
                                </label>
                            </div>
    
                            <div class="form-check">
                                <input class="form-check-input week_prices{{$count}}" type="checkbox" name="more_weekend_{{$count}}[]" <?php if(isset($detail->more_weekend) && $detail->more_weekend != 'null') checkWeekDays($weekenddays,'Sunday'); ?> value="Sunday" check_count="{{$count}}" id="Sunday1_{{$count}}">
                                <label class="form-check-label" for="Sunday1_{{$count}}">
                                     Sunday
                                </label>
                            </div>
    
                            <div class="col-md-12">
                                <label for="">Price for WeekdEnd</label>
                                <input id="week_end_price{{$count}}"  type="number" counter="{{$count}}" step=".01" class="form-control calculateMarkup" value="{{$detail->more_week_end_price ?? ''}}" name="more_week_end_price[]" autocomplete="week_end_price" autofocus>
                            </div>
                        </div>
                        
                        
                        <div class="col-md-12" id="" style="padding: 15px;">
                                     <input class="form-check-input display_on_website" counter="{{$count}}" type="checkbox" @isset($detail->display_on_web) @if($detail->display_on_web == 'true') checked @endif @endif name="display_on_website_more[]" value="true" id="display_on_website{{$count}}">
                                    <label class="form-check-label" for="display_on_website{{$count}}">
                                         Display on Website
                                    </label>
                                    <div class="row" id="display_markup{{$count}}" @isset($detail->display_on_web) @if($detail->display_on_web !== 'true') style="display:none;" @endif @endif>
                                        <div class="col-md-3">
                                            <label for="">Select Markup Type</label>
                                            <select class="form-control calculateMarkup" name="markup_type_more[]" counter="{{$count}}"  id="markup_type{{$count}}">
                                                <option value="Fixed" @isset($detail->markup_type) @if($detail->markup_type == 'Fixed') selected @endif @endif>Fixed</option>
                                                <option value="Percentage" @isset($detail->markup_type) @if($detail->markup_type == 'Percentage') selected @endif @endif>Percentage</option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <label for="">Markup Value</label>
                                            <input id="one_night_markup{{$count}}" type="text" counter="{{$count}}" value="{{ $detail->markup_value ?? 0; }}" class="form-control calculateMarkup" name="one_night_markup_more[]">
                                        </div>
                                        
                                        <div class="col-md-3 more_week_prices_fixed{{$count}} @if($detail->more_week_price_type == 'for_week_end') d-none  @endif">
                                            <label for="">One Room Price</label>
                                            <input id="one_night_mark_all{{$count}}" readonly type="text" class="form-control " value="{{ $detail->price_all_days_wi_markup ?? 0 }}" name="one_room_all_more[]">
                                            <p style="font-size: 12px;">Per Room per Night</p>
                                        </div>
                                        
                                        <div class="col-md-3  more_week_prices{{$count}} @if($detail->more_week_price_type == 'for_all_days') d-none  @endif">
                                            <label for="">Week Days Price</label>
                                            <input id="one_night_mark_week{{$count}}" readonly type="text" class="form-control " value="{{ $detail->weekdays_price_wi_markup ?? 0 }}" name="one_room_all_week_more[]">
                                            <p style="font-size: 12px;">Per Room per Night</p>
                                        </div>
                                        
                                        <div class="col-md-3  more_week_prices{{$count}} @if($detail->more_week_price_type == 'for_all_days') d-none  @endif">
                                            <label for="">Weekend Days Price</label>
                                            <input id="one_night_mark_weekend{{$count}}"  readonly type="text" class="form-control " value="{{ $detail->weekends_price_wi_markup ?? 0}}" name="one_room_all_weekend_more[]">
                                            <p style="font-size: 12px;">Per Room per Night</p>
                                        </div>
                                    </div>
                                
                            </div>
                    </div>
                    
                    <?php
                    $count=$count+1;
                                }
                            }
                    
                    ?>
                            
                            
                            

                            <div id="more_room_type_div"></div>
                            
                            <div class="col-md-12 d-none" style="padding: 15px;">
                                <button class="btn btn-primary" type="button" id="add_more_room_type">Add More Room Type</button>
                            </div>

                          
                            
                            <div class="col-md-12" style="padding: 15px;">
                                <label for="">Room description</label>
                                <textarea name="room_desc" row="5" class="form-control @error('room_desc') is-invalid @enderror"  autocomplete="room_desc" placeholder="Enter ga message">{{ $roomData->room_description }}</textarea>

                                @error('room_desc')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                          
                        </div>
                    </div>
                    

                    
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
                            <option value="Enabled" @if($roomData->status == 'Enabled') selected  @endif>Enabled</option>
                            <option value="Disabled" @if($roomData->status == 'Disabled') selected  @endif>Disabled</option>
                        </select>

                        <label for="">PRICE TYPE</label>
                        <select name="price_type" onchange="checkPriceType()" id="price_type" class="form-control">
                            <option value="By fixed" @if($roomData->price_type == 'By fixed') selected  @endif>By fixed</option>
                            <option value="By Travellers" @if($roomData->price_type == 'By Travellers') selected  @endif>By Travellers</option>
                        </select>

                        <div id="travller_price" style="@if($roomData->price_type == 'By fixed') display:none  @endif;">
                            <label for="">Adults Price</label>
                            <input id="adult_price" type="number" step=".01" class="form-control @error('adult_price') is-invalid @enderror" name="adult_price" value="{{ $roomData->adult_price }}" autocomplete="adult_price" autofocus>

                            @error('adult_price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <label for="">Child Price</label>
                            <input id="child_price" type="number" step=".01" class="form-control @error('child_price') is-invalid @enderror" name="child_price" value="{{ $roomData->child_price }}" autocomplete="child_price" autofocus>

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
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>
</div>

@endsection

@section('scripts')

<script>
   
    
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
    
      function CalculateMarkup(id){
        console.log('markup function cis ');
        var markupType = $('#markup_type'+id+'').val();
        var markupValue = $('#one_night_markup'+id+'').val();
        var PriceAllDays = $('#price_all_days'+id+'').val();
        var PriceWeekDays = $('#week_days_price'+id+'').val();
        var PriceWeekEndDays = $('#week_end_price'+id+'').val();
        var WeekPriceType = $("#week_price_type"+id+"").val();
        
        console.log('Markup type is '+markupType);
        console.log('Price Type is '+WeekPriceType);
        
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
    
     var m_count1 = <?php echo $count ?>;
    var loop = m_count1;
    console.log('this function is run now'+loop)
    for(var i=0; i<=loop; i++){
    console.log('this function is run now')
            $(".week_prices"+i+"").click(function(){
        
        var id = $(this).attr('check_count');
                console.log('The id is '+id);
            if ($("#Monday_"+id+"").is(':checked')){
                console.log('The id is Monday_'+id);
                $("#Monday1_"+id+"").prop("disabled", true);
            }else{
                console.log('The id is else Monday_'+id);
                $("#Monday1_"+id+"").prop("disabled", false);
            }
            
            if ($("#Monday1_"+id+"").is(':checked')){
                $("#Monday_"+id+"").prop("disabled", true);
            }else{
                $("#Monday_"+id+"").prop("disabled", false);
            }
            
            if ($("#Tuesday_"+id+"").is(':checked')){
                $("#Tuesday1_"+id+"").prop("disabled", true);
            }else{
                $("#Tuesday1_"+id+"").prop("disabled", false);
            }
            
            if ($("#Tuesday1_"+id+"").is(':checked')){
                $("#Tuesday_"+id+"").prop("disabled", true);
            }else{
                $("#Tuesday_"+id+"").prop("disabled", false);
            }
            
            if ($("#Wednesday_"+id+"").is(':checked')){
                $("#Wednesday1_"+id+"").prop("disabled", true);
            }else{
                $("#Wednesday1_"+id+"").prop("disabled", false);
            }
            
            if ($("#Wednesday1_"+id+"").is(':checked')){
                $("#Wednesday_"+id+"").prop("disabled", true);
            }else{
                $("#Wednesday_"+id+"").prop("disabled", false);
            }
            
            if ($("#Thursday_"+id+"").is(':checked')){
                $("#Thursday1_"+id+"").prop("disabled", true);
            }else{
                $("#Thursday1_"+id+"").prop("disabled", false);
            }
            
            if ($("#Thursday1_"+id+"").is(':checked')){
                $("#Thursday_"+id+"").prop("disabled", true);
            }else{
                $("#Thursday_"+id+"").prop("disabled", false);
            }
            
            if ($("#Friday_"+id+"").is(':checked')){
                console.log('this condition is ture Friday')
                $("#Friday1_"+id+"").prop("disabled", true);
            }else{
                console.log('this condition is else Friday')
                $("#Friday1_"+id+"").prop("disabled", false);
            }
            
            if ($("#Friday1_"+id+"").is(':checked')){
                $("#Friday_"+id+"").prop("disabled", true);
            }else{
                $("#Friday_"+id+"").prop("disabled", false);
            }
            
            if ($("#Saturday_"+id+"").is(':checked')){
                $("#Saturday1_"+id+"").prop("disabled", true);
            }else{
                $("#Saturday1_"+id+"").prop("disabled", false);
            }
            
            if ($("#Saturday1_"+id+"").is(':checked')){
                $("#Saturday_"+id+"").prop("disabled", true);
            }else{
                $("#Saturday_"+id+"").prop("disabled", false);
            }
            
            if ($("#Sunday_"+id+"").is(':checked')){
                $("#Sunday1_"+id+"").prop("disabled", true);
            }else{
                $("#Sunday1_"+id+"").prop("disabled", false);
            }
            
            if ($("#Sunday1_"+id+"").is(':checked')){
                $("#Sunday_"+id+"").prop("disabled", true);
            }else{
                $("#Sunday_"+id+"").prop("disabled", false);
            }
            
            });
        }

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
    
    var m_count = <?php echo $count ?>;
    var room_count = <?php echo $room_count ?>;
    $("#add_more_room_type").click(function(){
        m_count = m_count + 1
         room_count++;
           var quantity_room = $('#quantity_room').val();
        var  end_date = $('#end_date').val();
        var start_date = $('#start_date').val();
        var city_view = $('#city_view').val();
         var option_date = $('#option_date').val();
         
        var roomTypes   = <?php echo json_encode($roomTypes) ?>;
        var data = `<div class="row" id="M_R_T_div${m_count}">
                        <h3>More Room Type Details</h3>
                        <div class="col-md-4" style="padding: 15px;">
                            <label for="">Room Type</label>
                            <select name="more_room_type[]" id="room_type${room_count}" onchange=" get_rooms_type(${room_count})" class="form-control">`;
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
                            <label for="">Room Image</label>
                            <input id="room_img" type="file" class="form-control" name="more_room_img[]" autocomplete="room_img" autofocus>
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
                            <input type="date" class="form-control" value="${option_date}" id="" name="more_option_date[]" >
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
                                    @isset($rooms_Invoice_Supplier)
                                        @foreach($rooms_Invoice_Supplier as $hotel_sup_res)
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
                              <select class="form-control calculateMarkup" id="week_price_type${m_count}" onclick="more_week_price_type_function(${m_count})" name="week_price_type[]">
                                   <option value="for_all_days">Room Price for All Days</option>
                                   <option value="for_week_end">Room Price for Weekdays/Weekend</option>
                             </select>
                            
                        </div>
                        
                        <div class="col-md-12 more_week_prices_fixed${m_count}" id="price_for_all_days" style="padding: 15px;">
                            <label for="">Price For All Days</label>
                            <input id="price_all_days${m_count}" counter="${m_count}" required type="number" step=".01" class="form-control calculateMarkup" name="more_price_all_days[]" autocomplete="price_all_days" autofocus>
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
                                <input id="week_end_price${m_count}" counter="${m_count}"  type="number" step=".01" class="form-control calculateMarkup" name="more_week_end_price[]" autocomplete="week_end_price" autofocus>
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
                            <button style="float: right;" class="btn btn-primary" type="button" id="remove_more_room_type" onclick=remove_M_R_T_button(${m_count})>Remove</button>
                        </div>
                    </div>
                    `;
                    
  
        
        $('#more_room_type_div').append(data);
        m_count1 = m_count1 + 1;
        
        
        $('#city_view'+room_count+'').val(city_view).change();
        
        
        var supplier_name = $('#room_supplier').val();
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
        
 
        
        
        
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ url('/get_invoiceRoomSupplier_detail') }}",
            method: 'get',
            data: {
                "_token": "{{ csrf_token() }}",
                "id": ids,
            },
            success: function(result){
                var RI_suppliers = result['RI_suppliers'];
            	$("#room_supplier_name_"+m_count+'').empty();
                $.each(RI_suppliers, function(key, value) {
                    var room_supplier_Data = `<option attr="${value.id}" value="${value.id}"> ${value.room_supplier_name}</option>`;
                    $("#room_supplier_name_"+m_count+'').append(room_supplier_Data);
                });  
            },
        });
    })
    
    function remove_M_R_T_button(id){
        $('#M_R_T_div'+id+'').remove();
    }
    function setfunchange(id)
        {
    
 var additional_meal_type =  $('#more_additional_meal_type_'+id+'').val();;
console.log(additional_meal_type);
 if(additional_meal_type == 'Breakfast')
 {
    
   $('#more_additional_meal_type_charges_'+id+'').fadeIn();
 }
 if(additional_meal_type == 'Lunch')
 {
  $('#more_additional_meal_type_charges_'+id+'').fadeIn(); 
 }
 if(additional_meal_type == 'Dinner')
 {
     $('#more_additional_meal_type_charges_'+id+'').fadeIn();
 }
  if(additional_meal_type == 'Room only')
 {
  $('#more_additional_meal_type_charges_'+id+'').fadeOut();
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



