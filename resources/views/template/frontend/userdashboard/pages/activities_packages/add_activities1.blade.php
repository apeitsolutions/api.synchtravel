@extends('template/frontend/userdashboard/layout/default')
@section('content')
 
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
                        <label for="username" class="form-label">Visa Type</label>
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

 
 
 
 
 
 

                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">Create Packages</h4>
                                        
                        
                                        
                                        <div class="tab-content">
                                            <div class="tab-pane show active" id="justified-tabs-preview">
                                                <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                                                    <li class="nav-item">
                                                        <a href="#home1" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0  active">
                                                            <i class="mdi mdi-home-variant d-md-none d-block"></i>
                                                            <span class="d-none d-md-block">Package Details</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="#profile1" data-bs-toggle="tab" aria-expanded="true" class="nav-link rounded-0">
                                                            <i class="mdi mdi-account-circle d-md-none d-block"></i>
                                                            <span class="d-none d-md-block">Accomodation</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="#settings1" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0">
                                                            <i class="mdi mdi-settings-outline d-md-none d-block"></i>
                                                            <span class="d-none d-md-block">Flights Details</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="#visa_details" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0">
                                                            <i class="mdi mdi-settings-outline d-md-none d-block"></i>
                                                            <span class="d-none d-md-block">Visa Details</span>
                                                        </a>
                                                    </li>
                                                    
                                                    <li class="nav-item">
                                                        <a href="#trans_details_1" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0">
                                                            <i class="mdi mdi-settings-outline d-md-none d-block"></i>
                                                            <span class="d-none d-md-block">Transportation</span>
                                                        </a>
                                                    </li>
                                                     
                                                    <li class="nav-item">
                                                        <a href="#Itinerary_details" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0">
                                                            <i class="mdi mdi-settings-outline d-md-none d-block"></i>
                                                            <span class="d-none d-md-block">Itinerary</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="#Extras_details" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0">
                                                            <i class="mdi mdi-settings-outline d-md-none d-block"></i>
                                                            <span class="d-none d-md-block">Extra's</span>
                                                        </a>
                                                    </li>
                                                </ul>
<form action="{{URL::to('super_admin/submit_tour')}}" method="post" enctype="multipart/form-data">
@csrf
        
<div class="tab-content">
<div class="tab-pane  show active" id="home1">
<div class="row">
<div class="col-xl-4">
 <div class="mb-3">
    <label for="simpleinput" class="form-label">Title</label>
    <input type="text" id="simpleinput" name="title" class="form-control">
</div>
</div>
<div class="col-xl-4">
 <div class="mb-3">
    <label for="simpleinput" class="form-label">No Of Pax</label>
    <input type="text" id="no_of_pax_days" name="no_of_pax_days" class="form-control">
</div>
</div>

<div class="col-xl-4">
 <div class="mb-3">
    <label for="simpleinput" class="form-label">Currency</label>
    
    <select name="currency_symbol" class="form-control">
        <option>Select Currency</option>
        @foreach($all_countries_currency as $all_countries_currency)
        <option value="{{$all_countries_currency->currency_symbol}}">{{$all_countries_currency->currency_symbol}} - {{$all_countries_currency->currency}}</option>
        @endforeach
    </select>
</div>
</div>

<div class="col-xl-12">
 <div class="mb-3">
    <label for="simpleinput" class="form-label">Content</label>

    <textarea name="content" id="snow-editor" cols="142" rows="10"></textarea>
    
 </div>
</div>



      <div class="col-xl-4">
         <div class="mb-3">
          <label for="simpleinput" class="form-label">Start Date</label>
          <input type="date" name="start_date" class="form-control date" id="start_date">
         </div>
      </div>

      <div class="col-xl-4">
         <div class="mb-3">
          <label for="simpleinput" class="form-label">End Date</label>
          <input type="date" name="end_date" class="form-control date" id="end_date">
         </div>
      </div>
      <div class="col-xl-4">
         <div class="mb-3">
          <label for="simpleinput" class="form-label">No Of Nights</label>
          <input readonly type="text" name="time_duration" id="duration" class="form-control" >
         </div>
      </div>
      
      
      
      <div class="col-xl-3">
       <div class="mb-3">
          <label for="simpleinput" class="form-label">Categories</label>
          <select class="select2 form-control select2-multiple" name="categories[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
           
               @foreach($categories as $categories)
            <option value="{{$categories->title}}">{{$categories->title}}</option>
        
           @endforeach
           
           </select>
      </div>
      </div>

      <div class="col-xl-3">
       <div class="mb-3">
          <label for="simpleinput" class="form-label">Facilities</label>
          <select class="select2 form-control select2-multiple" name="tour_attributes[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
           
               @foreach($attributes as $attributes)
            <option value="{{$attributes->title}}">{{$attributes->title}}</option>
        
           @endforeach
           
           </select>
      </div>
      </div>
     

      <div class="col-xl-3">
       <div class="mb-3">
          <label for="simpleinput" class="form-label">Tour Featured </label><small>(Home Page )</small>
          <select name="tour_feature" id="" class="form-control">
          <option value="">Select Featured</option>
              <option value="0">Enable featured</option>
              <option value="1">Disable featured</option>
              
          </select>
      </div>
      </div>

      <div class="col-xl-3">
       <div class="mb-3">
          <label for="simpleinput" class="form-label">Default State</label>
          <select name="defalut_state" id="" class="form-control">
          <option value="">Select Default State</option>
              <option value="0">Always available</option>
              <option value="1">Only available on specific dates</option>
              
          </select>
      </div>
      </div>
      
      
      
      
      
      
      
      
      
      
      
     <div class="col-xl-6">
       <div class="mb-3">
       <label for="simpleinput" class="form-label">Featured Image</label>
          <input type="file" id="simpleinput" name="tour_featured_image" class="form-control">
      </div>
      </div>

      <div class="col-xl-6">
       <div class="mb-3">
       <label for="simpleinput" class="form-label">Banner Image</label>
          <input type="file" id="simpleinput" name="tour_banner_image" class="form-control">
      </div>
      </div>


      <div class="col-xl-4">
       <div class="mb-3">
          <label for="simpleinput" class="form-label">Publish</label>
          <select name="tour_publish" id="" class="form-control">
          <option value="">Select Publish</option>
              <option value="0">Publish</option>
              <option value="1">Draft</option>
          </select>
      </div>
      </div>

      <div class="col-xl-4">
       <div class="mb-3">
          <label for="simpleinput" class="form-label">Author</label>
          <select name="tour_author" id="" class="form-control">
          <option value="">Select Author</option>
              <option value="admin">admin</option>
              <option value="admin">admin1</option>
          </select>
      </div>
      </div> 
      
          <div class="col-xl-4">
 <div class="mb-3">
  
   
         <label for="simpleinput" class="form-label">Other Providers</label>
    <select class="select2 form-control select2-multiple" name="external_packages[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
     
       @foreach($customer as $customer)  
      <option value="{{$customer->name}} {{$customer->lname}}">{{$customer->name}} {{$customer->lname}}</option>
     
  @endforeach

     
     </select>
   
</div>
      
  </div>
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane" id="profile1">
                                                        
                                                    <div class="row">
                                                        <div class="col-xl-12">
       <div class="mb-3">

      <div class="form-check form-check-inline">
        <input type="checkbox" class="form-check-input" id="destination" name="">
       <label class="form-check-label" for="destination">Destination</label>
       </div>

      </div>
      </div>




<!-- Country & City -->
      <div class="row" style="display:none;" id="select_destination">
          
         <div class="col-xl-6">
            <label for="">COUNTRY</label>
            <select name="destination_country" onchange="selectCities()" id="property_country" class="form-control">
               @foreach($all_countries as $country_res)
               <option  value="{{ $country_res->id }}">{{ $country_res->name}}</option>
               @endforeach
            </select>
         </div>
         
         
         
         <div class="col-xl-6">
            <label for="">City</label>
            <select name="destination_city[]" id="property_city" class="select2 form-control select2-multiple city_slc" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
              
            </select>
         </div>
         <input type="hidden" name="tour_location_city" id="tour_location_city" value=""/>
         <div id="append_destination">
             
         </div>
         
         
         
         <div class="mt-2">
          <a href="javascript:;" id="more_destination" class="btn btn-info" style="float: right;"> + Add More </a>
      </div>
      </div>
      
   <!-- END Country & City -->
 </div>    
                                                        
  <div class="row">
      <div class="col-xl-12 mt-2">
       <div class="mb-3">

      <div class="form-check form-check-inline">
        <input type="checkbox" class="form-check-input" id="accomodation" name="">
       <label class="form-check-label" for="accomodation">Accomodation</label>
       </div>

      </div>
      </div>


         
         <div id="append_accomodation">
             
         </div>
         <div id="append_accomodation1">
             
         </div>
  </div>                                                      
                                                        
                                                        
                                                        
                                                        
                                                        
                                                        
                                                        
                                                       
</div>
<div class="tab-pane" id="settings1">
                                                        
                                                        
  <div class="col-xl-12">
       <div class="mb-3">

      <div class="form-check form-check-inline">
        <input type="checkbox" class="form-check-input" id="flights_inc" name="">
       <label class="form-check-label" for="flights_inc">Flights Included ?</label>
       </div>

      </div>
      </div>
      
      
      
      
      <!-- flights -->
      <div class="row" style="display:none;" id="select_flights_inc">
          
         <div class="col-xl-3">
            <label for="">Departure Airport Code</label>
            
            <select  name="departure_airport_code" id="departure_airport_code" class="form-control select2" data-toggle="select2">
                <option value="">Departure Airport Code</option>
                @foreach($bir_airports as $bir_airports)
                <option value="{{$bir_airports->name}}">{{$bir_airports->name}}</option>
                @endforeach
                
            </select>
            
            <!--<input name="departure_airport_code" id="search-box" onclick="departure_airport_codes()" class="form-control get_flight_name" autocomplete="off"  data-suggestion="suggesstion-box" placeholder="Departure.."/>-->
                
            <!--    <div id="suggesstion-box"></div> -->
                
            
             
         </div>
         <div class="col-xl-3">
            <label for="">Departure Flight Number</label>
             <input type="text" id="simpleinput" name="departure_flight_number" class="form-control">
         </div>
         <div class="col-xl-3">
            <label for="">Departure Date</label>
             <input type="date" id="simpleinput" name="departure_date" class="form-control">
         </div>
         <div class="col-xl-3">
            <label for="">Departure Time</label>
             <input type="time" id="simpleinput" name="departure_time" class="form-control">
         </div>
         <div class="col-xl-3">
            <label for="">Arrival Airport Code</label>
            <select  name="arrival_airport_code" id="arrival_airport_code" class="form-control select2" data-toggle="select2">
                <option value="">Arrival Airport Code</option>
                @foreach($bir_airports1 as $bir_airports1)
                <option value="{{$bir_airports1->name}}">{{$bir_airports1->name}}</option>
                @endforeach
                
            </select>
            <!--<input name="arrival_airport_code" id="search-box2" class="form-control get_flight_name arrival_airport_code" autocomplete="off"  data-suggestion="suggesstion-box2" placeholder="Arrival.."/>-->
                
            <!--    <div id="suggesstion-box2"></div> -->
                <!--<option selected>Select Arrival Airport Code</option>-->
                <!-- <option value="JED">King Abdul Aziz International Airport Jeddah</option>-->
                <!--  <option value="MED">Prince Mohammad bin Abdulaziz International Airport Medina</option>-->
            <!--</select>-->
             
         </div>
         <div class="col-xl-3">
            <label for="">Arrival Flight Number</label>
             <input type="text" id="simpleinput" name="arrival_flight_number" class="form-control">
         </div>
         <div class="col-xl-3">
            <label for="">Arrival Date</label>
             <input type="date" id="simpleinput" name="arrival_date" class="form-control">
         </div>
         <div class="col-xl-3">
            <label for="">Arrival Time</label>
             <input type="time" id="simpleinput" name="arrival_time" class="form-control">
         </div>
         
         
         <div class="col-xl-6">
            <label for="">Flight Type</label>
            <select name="flight_type" id="flights_type" class="form-control">
                <option attr="select" selected>Select Flight Type</option>
                 <option attr="Direct" value="Direct">Direct</option>
                  <option attr="Connected" value="Connected">Connected</option>
            </select>
             
         </div>
         
         <div class="col-xl-6">
            <label for="">Price Per Person</label>
              <input type="text" id="flights_per_person_price" name="flights_per_person_price" class="form-control">
         </div>
         
         <div class="col-xl-12 mt-3" style="display:none" id="text_editer">
            <label for="">Connected Flight Duration and Details</label>
            <textarea name="connected_flights_duration_details" class="form-control" cols="10" rows="10"></textarea>
             
         </div>
         <div class="col-xl-12 mt-2" id="text_editer">
            <label for="">Terms and Conditions</label>
            <textarea name="terms_and_conditions" class="form-control" cols="5" rows="5"></textarea>
             
         </div>
         <div class="col-xl-12 mt-2" id="text_editer">
            <label for="">image</label>
             <input type="file" id="" name="flights_image" class="form-control">
           
             
         </div>
         
         <div id="append_flights">
             
         </div>
         
         
         
      <!--   <div class="mt-2">-->
      <!--    <a href="javascript:;" id="more_flights_inc" class="btn btn-info" style="float: right;"> + Add More </a>-->
      <!--</div>-->
      </div>
      
   <!-- END Flifhts -->  
                                                        
                                                        
                                                        
                                                        
                                                        
                                                        
  </div>
                                                    
                                                    
        <div class="tab-pane" id="visa_details">
                                                        
                                                        
       <div class="row">                                                  
     <div class="col-xl-12 mt-2">
       <div class="mb-3">

      <div class="form-check form-check-inline">
        <input type="checkbox" class="form-check-input" id="visa_inc" name="">
       <label class="form-check-label" for="visa_inc">Visa Included ?</label>
       </div>

      </div>
      </div>
      
      <div class="row mt-1 mb-3" style="display:none" id="select_visa_inc">
          
      
<div class="col-xl-6">
            <label for="">Visa Type</label>
            
            <div class="input-group" id="timepicker-input-group1">
       <select name="visa_type" id="visa_type" class="form-control other_type">
                
            </select>
       <span title="Add Visa Type" class="input-group-btn input-group-append"><button class="btn btn-primary bootstrap-touchspin-up" data-bs-toggle="modal" data-bs-target="#signup-modal" type="button">+</button></span>
    </div>
          
         </div>
         
        
     <div class="col-xl-6">
            <label for="">Visa Fee</label>
             <input type="text" id="visa_fee" name="visa_fee" class="form-control">
         </div>
         
         
         
   
     
         <div class="col-xl-12">
            <label for="">Visa Rules and Regulations:</label>
             <textarea name="visa_rules_regulations" class="form-control" cols="5" rows="5"></textarea>
         </div>
         <div class="col-xl-12">
            <label for="">Image:</label>
              <input type="file" id="" name="visa_image" class="form-control">
         </div>
</div>
</div>
                                                        
                                                        
       </div> 
                                                        
           
                                                        <div class="tab-pane" id="trans_details_1">
                                                            <div class="row">                                           
   <div class="tab-pane" id="trans_details_1">
        <div class="col-xl-12 mt-2">
       <div class="mb-3">

      <div class="form-check form-check-inline">
        <input type="checkbox" class="form-check-input" id="transportation" name="">
       <label class="form-check-label" for="transportation">Transportation</label>
       </div>

      </div>
      </div>
         
     <!-- transportation -->
     
<div class="row" style="display:none;border:1px solid #ced4da;padding: 20px 20px 20px 20px;" id="select_transportation">
    
    
    
    <div class="col-xl-2">
        <label for="">Pick-up Location</label>
        <input type="text" id="transportation_pick_up_location" name="transportation_pick_up_location" class="form-control">
        </div>
         <div class="col-xl-2">
        <label for="">Drop-off Location</label>
        <input type="text" id="transportation_drop_off_location" name="transportation_drop_off_location" class="form-control">
        </div>
        <div class="col-xl-2">
        <label for="">Pick-up Date</label>
        <input type="date" id="transportation_pick_up_date" name="transportation_pick_up_date" class="form-control">
        </div>
    
    <div class="col-xl-3">
        <label for="">Select Trip Type</label>
        <select name="transportation_trip_type" id="slect_trip" class="form-control"  data-placeholder="Choose ...">

<option value="One-Way">One-Way</option>
<option value="Return">Return</option>
<option value="All_Round">All Round</option>
</select>
</div>
    
    
<div class="col-xl-3">
    <label for="">Vehicle Type</label>
    <select name="transportation_vehicle_type" id="" class="form-control"  data-placeholder="Choose ...">
<option value="">Choose ...</option>
<option value="Bus">Bus</option>
<option value="Coach">Coach</option>
<option value="Vain">Vain</option>

<option value="Car">Car</option></select>
</div>
<div class="col-xl-3">
    <label for="">No Of Vehicle</label>
    <input type="text" id="transportation_no_of_vehicle" name="transportation_no_of_vehicle" class="form-control">
    </div>
    <div class="col-xl-3">
    <label for="">Price Per Vehicle</label>
    <input type="text" id="transportation_price_per_vehicle" name="transportation_price_per_vehicle" class="form-control">
    </div>
    
    <div class="col-xl-3">
    <label for="">Total Vehicle Price</label>
    <input type="text" id="transportation_vehicle_total_price" name="transportation_vehicle_total_price" class="form-control">
    </div>




<div class="row" id="select_return" style="display:none;">
       <div class="col-xl-4">
        <label for="">Pick-up Location</label>
        <input type="text" id="return_transportation_pick_up_location" name="return_transportation_pick_up_location" class="form-control">
        </div>
         <div class="col-xl-4">
        <label for="">Drop-off Location</label>
        <input type="text" id="return_transportation_drop_off_location" name="return_transportation_drop_off_location" class="form-control">
        </div>
        <div class="col-xl-4">
        <label for="">Pick-up Date</label>
        <input type="date" id="simpleinput" name="return_transportation_pick_up_date" class="form-control">
        </div>
</div>

<div class="col-xl-3">
    <label for="">Price Per Person</label>
    <input type="text" id="transportation_price_per_person" name="transportation_price_per_person" class="form-control">
    </div>
    
    <div class="col-xl-12">
    <label for="">Image</label>
    <input type="file" id="" name="transportation_image" class="form-control">
    </div>



<div id="append_transportation">
</div>

<div class="mt-2" style="display:none;" id="add_more_destination">
<a href="javascript:;" id="more_transportation" class="btn btn-info" style="float: right;">Add More Destinations </a>
</div>

</div>      
      
   <!-- transportation -->
                                                        </div>
                                                        </div> 
                                                        </div> 
                                                        
                                                        
                                                        
                                                        
                                                        
<div class="tab-pane" id="Itinerary_details">
                                                            
  <div class="row">
      <div class="col-xl-12">
         <div class="mb-3">
          <label for="simpleinput" class="form-label">What's Included?</label>
          <textarea name="whats_included" class="form-control" id="" cols="10" rows="0"></textarea>
          
         </div>
      </div>
      <div class="col-xl-12">
         <div class="mb-3">
          <label for="simpleinput" class="form-label">What's Excluded?</label>
          <textarea name="whats_excluded" class="form-control" id="" cols="10" rows="0"></textarea>
          
         </div>
      </div>
  <div class="col-xl-12">
       <div class="mb-3">

      <div class="form-check form-check-inline">
        <input type="checkbox" class="form-check-input" id="Itinerary" name="">
       <label class="form-check-label" for="Itinerary">Itinerary</label>
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
          <label for="simpleinput" class="form-label">Desc: TP. HCM City</label>
          <input type="text" id="simpleinput" name="Itinerary_city" class="form-control">
      </div>
      </div>
      <div class="col-xl-12">
      <div class="mb-3">
          <label for="simpleinput" class="form-label">Content</label>
          <textarea name="Itinerary_content" class="form-control" id="" cols="10" rows="10"></textarea>
      </div>
      </div>
      <div class="col-xl-12">
      <div class="mb-3">
          <label for="simpleinput" class="form-label">image</label>
          <input type="file" id="simpleinput" name="Itinerary_image" class="form-control">
      </div>
      </div>

      <div class="" id="append_data">

      </div>


      <div class="mb-3">
          <div class="btn btn-info" id="click_more_Itinerary">Add More</div>
          
      </div>
      </div>


      <div class="col-xl-12">
       <div class="mb-3">

      <div class="form-check form-check-inline">
        <input type="checkbox" class="form-check-input" id="extra_price" name="">
       <label class="form-check-label" for="extra_price">Additional Services</label>
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
          <input type="text" id="simpleinput" name="extra_price_price" class="form-control">
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

      <div class="" id="append_data_extra_price">

      </div>


      <div class="mb-3">
          <div class="btn btn-info" id="click_more_extra_price">Add More</div>
          
      </div>
      </div>

      <!-- extra price ended -->


      <div class="col-xl-12">
      <div class="mb-3">

      <div class="form-check form-check-inline">
        <input type="checkbox" class="form-check-input" id="faq" name="">
      <label class="form-check-label" for="faq">FAQ</label>
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

      <div class="" id="append_data_faq">

      </div>


      <div class="mb-3">
         <div class="btn btn-info" id="click_more_faq">Add More</div>   
      </div>
      </div>

      <!-- faq ended -->
                                                           </div> 
                                                            
                                                        </div> 
                                                        
                                                    <div class="tab-pane" id="Extras_details">
                                                            
                                                           <div class="row">
            <div class="col-xl-4" style="text-align:right;">
                <span>Markup on total price</span>
          </div>
  <div class="col-xl-1">
 <input type="checkbox" id="switch2" checked data-switch="bool"/>
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
                
                
                
                
                <div class="row" id="flights_cost" style="display:none">
                <div class="col-xl-3">
                     <h4 class="" id="">Flights Cost</h4>
                </div>
                <div class="col-xl-9">
                    
                </div>
                <div class="col-xl-2">
                          <!--<label for="simpleinput" class="form-label">Tour Sale Price</label>-->
          <input type="text" id="flights_departure_code" readonly="" name="hotel_name_markup[]" class="form-control">
                </div>
                 <div class="col-xl-2">
                          <!--<label for="simpleinput" class="form-label">Tour Sale Price</label>-->
          <input type="text" id="flights_arrival_code" readonly="" name="room_type[]" class="form-control">
                </div>
                 <div class="col-xl-2">
                          <!--<label for="simpleinput" class="form-label">Tour Sale Price</label>-->
          <input type="text" id="flights_prices" readonly="" name="without_markup_price[]" class="form-control">
                </div>
                 <div class="col-xl-2">
                          <!--<label for="simpleinput" class="form-label">Markup Type</label>-->
                          <select name="hotel_markup_type[]" id="markup_type" class="form-control">
                              <option value="">Markup Type</option>
                              <option value="%">Percentage</option>
                              <option value="SAR">Number</option>
                          </select>
          
                </div>
                <div class="col-xl-2">
                          <!--<label for="simpleinput" class="form-label">Tour Sale Price</label>-->
          <input type="text" id="markup_value" name="markup[]" class="form-control">
                </div>
                <div class="col-xl-2">
                          <!--<label for="simpleinput" class="form-label">Tour Sale Price</label>-->
          <input type="text" id="total_markup" name="markup_price[]" class="form-control">
                </div>
                
                </div>
                
                
                
                <div id="append_accomodation_data_cost">
                    
                </div>
                 <div id="append_accomodation_data_cost1">
                    
                </div>
                
         
                
                
            <div class="row" id="transportation_cost" style="display:none;">
                <div class="col-xl-3">
                     <h4 class="" id="">Transportation Cost</h4>
                </div>
                <div class="col-xl-9">
                          
                </div>
                
                 <div class="col-xl-2">
                          <!--<label for="simpleinput" class="form-label">Tour Sale Price</label>-->
          <input type="text" id="transportation_pick_up_location_select" readonly="" name="" class="form-control">
                </div>
                 <div class="col-xl-2">
                          <!--<label for="simpleinput" class="form-label">Tour Sale Price</label>-->
          <input type="text" id="transportation_drop_off_location_select" readonly="" name="" class="form-control">
                </div>
                 <div class="col-xl-2">
                          <!--<label for="simpleinput" class="form-label">Tour Sale Price</label>-->
          <input type="text" id="transportation_price_per_person_select" readonly="" name="" class="form-control">
                </div>
                 <div class="col-xl-2">
                          <!--<label for="simpleinput" class="form-label">Markup Type</label>-->
                          <select name="markup_type" id="transportation_markup_type" class="form-control">
                              <option value="">Markup Type</option>
                              <option value="%">Percentage</option>
                              <option value="SAR">Number</option>
                          </select>
          
                </div>
                <div class="col-xl-2">
                          <!--<label for="simpleinput" class="form-label">Tour Sale Price</label>-->
          <input type="text" id="transportation_markup" name="transportation_markup" class="form-control">
                </div>
                <div class="col-xl-2">
                          <!--<label for="simpleinput" class="form-label">Tour Sale Price</label>-->
          <input type="text" id="transportation_markup_total" name="" class="form-control">
                </div>
                
                </div>
                
                
                
     <div class="row" id="visa_cost" style="display:none;">
                <div class="col-xl-3">
                     <h4 class="" id="">Visa Cost</h4>
                </div>
                <div class="col-xl-9">
                          <!--<label for="simpleinput" class="form-label">Tour Sale Price</label>-->
         
                </div>
                 <div class="col-xl-3">
                          <!--<label for="simpleinput" class="form-label">Tour Sale Price</label>-->
          <input type="text" id="visa_type_select" name="" class="form-control">
                </div>
                 <div class="col-xl-3">
                          <!--<label for="simpleinput" class="form-label">Tour Sale Price</label>-->
          <input type="text" id="visa_price_select" name="" class="form-control">
                </div>
                 <div class="col-xl-2">
                          <!--<label for="simpleinput" class="form-label">Tour Sale Price</label>-->
          <select name="markup_type" id="visa_markup_type" class="form-control">
                              <option value="">Markup Type</option>
                              <option value="%">Percentage</option>
                              <option value="SAR">Number</option>
                          </select>
                </div>
                <div class="col-xl-2">
                          <!--<label for="simpleinput" class="form-label">Tour Sale Price</label>-->
          <input type="text" id="visa_markup" name="" class="form-control">
                </div>
                 <div class="col-xl-2">
                          <!--<label for="simpleinput" class="form-label">Tour Sale Price</label>-->
          <input type="text" id="total_visa_markup" name="" class="form-control">
                </div>
            </div>
             </div>    

            </div>
            
            
            
            <!--<div id="Off" style="display:none;">-->
            <!--    <h1>jdfgueshgsi</h1>-->
            <!--</div>-->
        </div><!-- /.modal-content -->
  

          

      </div> 
 </div>
  <div class="row">
<!--     <div class="col-xl-12">-->
<!--          <input type="checkbox" id="switch1" checked data-switch="bool"/>-->
<!--<label for="switch1" data-on-label="On" data-off-label="Off"></label>-->
<!--      </div>-->
      
      
      <div class="col-xl-12" style="display:none;" id="all_services_markup">
          <div class="card">
            <div class="card-header">
                <h4 class="modal-title" id="standard-modalLabel">Markup on total price</h4>
               <div class="card-body">
                   
                   <div class="row">
                       <div class="col-xl-6">
 <div class="mb-3">
  
   
         <label for="all_markup_type" class="form-label">Markup Type</label>
   
    <select class="form-control" id="all_markup_type" name="all_markup_type" >
        <option>Select Markup Type</option>
         <option value="%">Percentage</option>
          <option value="SAR">Number</option>
    </select>
     
      

     
     
</div>
      
  </div>
<div class="col-xl-6">
 <div class="mb-3">
  
   
         <label for="all_markup_add" class="form-label">All Markup</label>
    <input class="form-control" id="all_markup_add" name="all_markup_add" />
     
      

     
     
</div>
      
  </div>
 
                   </div>
                   
                </div>
            </div>
      </div>
      
      
      
 </div> 
 </div>
 
 <div class="row mt-2">

      
      
      
  
     <div class="col-xl-4">
 <div class="mb-3">
  
   
         <label for="simpleinput" class="form-label">Quad Cost Price</label>
    <input class="form-control" id="quad_cost_price" name="quad_cost_price" />
     
      

     
     
</div>
      
  </div>
       <div class="col-xl-4">
 <div class="mb-3">
  
   
         <label for="simpleinput" class="form-label">Triple Cost Price</label>
    <input class="form-control" id="triple_cost_price" name="triple_cost_price" />
     
      

     
     
</div>
      
  </div>
       <div class="col-xl-4">
 <div class="mb-3">
  
   
         <label for="simpleinput" class="form-label">Double Cost Price</label>
    <input class="form-control" id="double_cost_price" name="double_cost_price" />
     
      

     
     
</div>
      
  </div>

     
      
      
      </div>
 
 
 <div class="row" id="sale_pr">

      
      
      
  
     <div class="col-xl-4">
 <div class="mb-3">
  
   
         <label for="simpleinput" class="form-label">Quad Sale Price</label>
    <input class="form-control" id="quad_grand_total_amount" name="quad_grand_total_amount" />
     
      

     
     
</div>
      
  </div>
       <div class="col-xl-4">
 <div class="mb-3">
  
   
         <label for="simpleinput" class="form-label">Triple Sale Price</label>
    <input class="form-control" id="triple_grand_total_amount" name="triple_grand_total_amount" />
     
      

     
     
</div>
      
  </div>
       <div class="col-xl-4">
 <div class="mb-3">
  
   
         <label for="simpleinput" class="form-label">Double Sale Price</label>
    <input class="form-control" id="double_grand_total_amount" name="double_grand_total_amount" />
     
      

     
     
</div>
      
  </div>

     
      
      
      </div>
      
      
      <div class="row" id="markup_seprate_services" style="display:none;">

      
      
      
  
     <div class="col-xl-4">
 <div class="mb-3">
  
   
         <label for="simpleinput" class="form-label">Quad Sale Price</label>
    <input class="form-control" id="quad_markup" name="quad_grand_total_amount" />
     
      

     
     
</div>
      
  </div>
       <div class="col-xl-4">
 <div class="mb-3">
  
   
         <label for="simpleinput" class="form-label">Triple Sale Price</label>
    <input class="form-control" id="triple_markup" name="triple_grand_total_amount" />
     
      

     
     
</div>
      
  </div>
       <div class="col-xl-4">
 <div class="mb-3">
  
   
         <label for="simpleinput" class="form-label">Double Sale Price</label>
    <input class="form-control" id="double_markup" name="double_grand_total_amount" />
     
      

     
     
</div>
      
  </div>

     
      
      
      </div>
      
      
      
      <button style="float: right;" type="submit" name="submit" class="btn btn-info deletButton">Save Tour</button>                                                      
 </div> 
                                                    
                                                    
                                                    
                                                    
                                                    
                                                    
                                                </div> 
                                                
                                                
                                                
                                                
                                                
                                                
                                               </form> 
                                                
                                                
                                                
                                                
                                                
                                                
                                            </div> <!-- end preview-->
                                        
                                            
                                        </div> <!-- end tab-content-->
                                    </div> <!-- end card-body -->
                                </div> <!-- end card-->
                           
 
 
 





@endsection


@section('scripts')

<script type="text/javascript">

$("#switch2").click(function () {
  
    $('#markup_services').slideToggle();
     $('#all_services_markup').slideToggle();
    $('#markup_seprate_services').slideToggle();
     $('#sale_pr').slideToggle();
 });

// $("#switch1").click(function () {
  
//     $('#all_services_markup').slideToggle();
//      $('#markup_services').slideToggle();
//      $('#markup_seprate_services').slideToggle();
//      $('#sale_pr').slideToggle();
     
     
    
//  });
 
  $("#all_markup_type").change(function () {
    var id = $(this).find('option:selected').attr('value');
    
        // $('#visa_type_select').val(id);
    if(id == '%')
    {
        $('#all_markup_add').keyup(function() {
            
   var markup_val =  $('#all_markup_add').val();
   
   var quad_cost_price =  $('#quad_cost_price').val();
   var triple_cost_price =  $('#triple_cost_price').val();
   var double_cost_price =  $('#double_cost_price').val();
   
   var total_quad_cost_price = (quad_cost_price * markup_val/100) + parseInt(quad_cost_price);
        $('#quad_markup').val(total_quad_cost_price);
        
        var total_triple_cost_price = (triple_cost_price * markup_val/100) + parseInt(triple_cost_price);
        $('#triple_markup').val(total_triple_cost_price);
        
        var total_double_cost_price = (double_cost_price * markup_val/100) + parseInt(double_cost_price);
        $('#double_markup').val(total_double_cost_price);
        
        
 
});
       
       
    }
    else
    {
       $('#all_markup_add').keyup(function() {
            
   var markup_val =  $('#all_markup_add').val();
   
   var quad_cost_price =  $('#quad_cost_price').val();
   var triple_cost_price =  $('#triple_cost_price').val();
   var double_cost_price =  $('#double_cost_price').val();
   
   var total_quad_cost_price =  parseInt(quad_cost_price) +  parseInt(markup_val);
        $('#quad_markup').val(total_quad_cost_price);
        
        var total_triple_cost_price =  parseInt(triple_cost_price) +  parseInt(markup_val);
        $('#triple_markup').val(total_triple_cost_price);
        
        var total_double_cost_price = parseInt(double_cost_price) +  parseInt(markup_val);
        $('#double_markup').val(total_double_cost_price);
        
        
 
});
     
    }
      
  

  });

$(document).on('click','#visa_inc',function(){
      $.ajax({    
        type: "GET",
        url: "get_other_visa_type",             
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
  $("#visa_type").append('<option attr=' +value.other_visa_type+ ' value=' +value.id+ '>' +value.other_visa_type+'</option>');
});
            
            
            
           
            
           
           
        }
    });
});



$('#submitForm').on('click',function(e){
    e.preventDefault();

    let other_visa_type = $('#other_visa_type').val();
    // alert(other_visa_type);
    
    $.ajax({
      url: "submit_other_visa_type",
      type:"POST",
      data:{
        "_token": "{{ csrf_token() }}",
        other_visa_type:other_visa_type,
       
      },
      success:function(response){
        // $('#successMsg').show();
        console.log(response);
    
        if(response)
        {
            alert('Visa Other Type Added SuccessFUl!');
        }
        $('#success-message').text(response.success); 
        // console.log(response.success);
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
    
    
  var t_trans = transportation_price_per_vehicle * transportation_no_of_vehicle;
   
  console.log('t_trans'+t_trans);
  $('#transportation_vehicle_total_price').val(t_trans)
        var total_trans = t_trans/no_of_pax_days;
          console.log('total_trans'+total_trans);
        $('#transportation_price_per_person').val(total_trans)
        
  $('#transportation_price_per_person_select').val(total_trans);
  add_numberElse();
});




  $("#departure_airport_code").change(function () {
    var id = $(this).find('option:selected').attr('value');

    $('#flights_departure_code').val(id);


 });
   $("#arrival_airport_code").change(function () {
    var id = $(this).find('option:selected').attr('value');

    $('#flights_arrival_code').val(id);


 });


  
  $("#markup_type").change(function () {
    var id = $(this).find('option:selected').attr('value');
    
        // $('#markup_value').val(id);
        
      var markup_val =  $('#markup_value').val();
       var flights_prices =  $('#flights_prices').val();
      
       
    if(id == '%')
    {
        $('#markup_value').keyup(function() {
   var markup_val =  $('#markup_value').val();
   var total = flights_prices * markup_val/100;
        $('#total_markup').val(total);
       add_numberElse_1();
     
 
});
       
       
    }
    else
    {
    $('#markup_value').keyup(function() {
   var markup_val =  $('#markup_value').val();
   var total = parseInt(flights_prices) + parseInt(markup_val);
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
console.log(arr);
var json_data=JSON.stringify(arr);
$('#tour_location_city').val(json_data);
}); 



    



 
  
    $("#visa_type").change(function () {
    var id = $(this).find('option:selected').attr('attr');

    $('#visa_type_select').val(id);


 });
  
  
  $("#visa_markup_type").change(function () {
    var id = $(this).find('option:selected').attr('value');
    
        // $('#visa_type_select').val(id);
        
   var markup_val =  $('#visa_markup').val();
   var visa_price_select =  $('#visa_price_select').val();
      
       
    if(id == '%')
    {
        $('#visa_markup').keyup(function() {
            
   var markup_val =  $('#visa_markup').val();
   var visa_price_select =  $('#visa_price_select').val();
   
   var total = visa_price_select * markup_val/100;
        $('#total_visa_markup').val(total);
        add_numberElse_1();
        
 
});
       
       
    }
    else
    {
     $('#visa_markup').keyup(function() {
            
   var markup_val =  $('#visa_markup').val();
   var visa_price_select =  $('#visa_price_select').val();
   
   var total =  parseInt(visa_price_select) +  parseInt(markup_val);
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




   $("#transportation_pick_up_location").change(function () {
    var id = this.value;

    $('#transportation_pick_up_location_select').val(id);


 });
 $("#transportation_drop_off_location").change(function () {
    var id = this.value;

    $('#transportation_drop_off_location_select').val(id);


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
    
        // $('#visa_type_select').val(id);
    if(id == '%')
    {
        $('#transportation_markup').keyup(function() {
            
   var markup_val =  $('#transportation_markup').val();
   var transportation_price =  $('#transportation_price_per_person_select').val();
   
   var total = (transportation_price * markup_val/100) + parseInt(transportation_price);
        $('#transportation_markup_total').val(total);
        add_numberElse_1();
        
 
});
       
       
    }
    else
    {
        $('#transportation_markup').keyup(function() {
            
   var markup_val =  $('#transportation_markup').val();
   var transportation_price =  $('#transportation_price_per_person_select').val();
   
   var total = parseInt(transportation_price) + parseInt(markup_val);
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



$("#flights_type").change(function () {
    var id = $(this).find('option:selected').attr('value');
// alert(id);
if(id == 'Connected')
{
	$('#text_editer').fadeOut();
	$('#text_editer').fadeIn();

}

else
{
	$('#text_editer').fadeOut();
}




  });
</script>
@stop