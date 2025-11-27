@extends('template/frontend/userdashboard/layout/default')
@section('content')

<?php $currency=Session::get('currency_symbol');

// dd($airline);
?>


<div id="add_airline_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
<div class="modal-header">
                <h4 class="modal-title" >Add Flight Airline Name</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body htmldetailofflight">
                     
                <div class="col-xl-12" style="padding-left: 24px;">
                                    
                          
                                     <div>
                                    <label>Airline Name</label>
                                    <div class="input-group">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">
                                               {{$currency ?? ""}}
                                            </a>
                                        </span>
                                        <input type="text"  name="new_flights_airline_name" class="form-control new_flight_data">
                                    </div>
                                    </div>
                                   
                                     <button type="button" class="btn btn-primary mt-3 add_flight_in_db">Submit</button>
                                   
                                </div>
                               
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal --> 

<div class="dashboard-content">
   
    <h4>ADD SEATS</h4>
    <form action="{{ url('addseat1') }}" method="post" enctype="multipart/form-data">
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
            <div class="col-md-12">
                
                <!-- Main Tab Contant Start -->
                
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
                                
                                <div class="row" style="padding-left: 24px;">
                                    <div class="col-sm-12 col-md-3">
                                        <label for="">Select Supplier</label>
                                            <!--<i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="left" title="Add your flights details"></i>-->
                                            <select class="form-control" name="supplier" required >
             <option value="">Select supplier</option>
              @if(isset($supplier))
                                                @foreach($supplier as $all_supplier)
                                                
                                                    <option value="{{$all_supplier->multi_rute_suplier->id}}">{{$all_supplier->multi_rute_suplier->companyname}}</option>
                                                @endforeach
                                            @endif
         </select>
                                        </div>
                                    <div class="col-sm-12 col-md-3">
                                    <label for="">Flight Type</label>
                                    <select name="flight_type" required id="flights_type" class="form-control">
                                        <option attr="select" selected>Select Flight Type</option>
                                         <option attr="Direct" value="Direct">Direct</option>
                                          <option attr="Indirect" value="Indirect">Indirect</option>
                                    </select>
                                    </div>
                                    
                                    <div class="col-sm-12 col-md-3">
                                        
                                         <div>
                                    <label for="">Airline</label>
                                    <div class="input-group">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1 add_airline">
                                               +
                                            </a>
                                        </span>
                                        <select name="selected_flight_airline" required class="form-control airline_data" onchange="changeairlineFunction()" >
                                        <option attr="select" selected>Select Airline</option>
                                         <!--<option value="PIA">PIA</option>-->
                                          <!--<option value="Air_blue">Air blue</option>-->
                                           @if(isset($airline))
             @foreach($airline as $all_airline)
              <option value="{{$all_airline->other_Airline_Name}}">{{$all_airline->other_Airline_Name}}</option>
              @endforeach
              @endif
                                    </select>
                                    </div>
                                    </div>
                                        
                                
                                    </div>
                                  
                                    <div class="col-sm-12 col-md-3" id="flights_type_connected"></div>
                                  
                                     
                                </div>
    
                                
    
                                <div class="col-xl-3"></div>
                                
                                <div class="container Flight_section">
                                    <h3 style="padding: 12px">Departure Details : </h3>
                                    <div class="row" style="padding: 12px">
                                        <div class="col-xl-4">
                                            <label for="">Departure Airport</label>
                                            <input name="departure_airport_code[]" id="departure_airport_code" class="form-control" autocomplete="off" placeholder="Enter Departure Location">
                                        </div>
                                        <div class="col-xl-1" style="margin-top: 25px;text-align: center;">
                                            <label for=""></label>
                                            <span id="Change_Location" class='bi bi-arrow-left-right' style="font-size: 23px;"></span>
                                        </div>
                                        <div class="col-xl-4">
                                            <label for="">Arrival Airport</label>
                                            <input name="arrival_airport_code[]" id="arrival_airport_code" class="form-control" autocomplete="off" placeholder="Enter Arrival Location">
                                            
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="">Airline Name</label>
                                            <input type="text" id="other_Airline_Name2" name="other_Airline_Name2[]" class="form-control other_airline_Name1">
                                          
                                        </div>
                                        <div class="col-xl-3" style="margin-top: 15px;">
                                            <label for="">Class Type</label>
                                            <select  name="departure_Flight_Type[]" id="departure_Flight_Type" class="form-control">
                                                <option value="">Select Flight Type</option>
                                                <option value="Bussiness">Bussiness</option>
                                                <option value="Economy">Economy</option>
                                                <option value="Standard">Standard</option>
                                            </select>
                                        </div>
                                        <div class="col-xl-3" style="margin-top: 15px;">
                                            <label for="">Flight Number</label>
                                            <input type="text" id="simpleinput" name="departure_flight_number[]" class="form-control" placeholder="Enter Flight Number">
                                        </div>
                                        <div class="col-xl-3" style="margin-top: 15px;">
                                            <label for="">Departure Date and Time</label>
                                            <input type="datetime-local" id="simpleinput" name="departure_time[]" class="form-control departure_time1" value="" >
                                        </div>
                                        <div class="col-xl-3" style="margin-top: 15px;">
                                            <label for="">Arrival Date and Time</label>
                                            <input type="datetime-local" id="simpleinput" name="arrival_time[]" class="form-control arrival_time1" value="" >
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="container" style="display:none" id="total_Time_Div">
                                    <div class="row" style="margin-left: 320px">
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
                                
                            
                                
                                <div class="container return_Flight_section_append"></div>
                                
                              
                                
                                <div id="append_flights" class="mb-3"></div>
                            
                            
                            <hr>
                            
                            
                            <div>
                                
                                
                                 <!-- flights2 -->
                            <div class="row mt-5"  id="select_flights_inc">
                                
                                <div class="row" style="padding-left: 24px;">
                                    <div class="col-sm-12 col-md-3">
                                        <label for="">Select Supplier</label>
                                            <!--<i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="left" title="Add your flights details"></i>-->
                                            <select class="form-control" name="return_supplier" required >
             <option value="">Select supplier</option>
              @if(isset($supplier))
                                                @foreach($supplier as $all_supplier)
                                                
                                                    <option value="{{$all_supplier->multi_rute_suplier->id}}">{{$all_supplier->multi_rute_suplier->companyname}}</option>
                                                @endforeach
                                            @endif
         </select>
                                        </div>
                                    <div class="col-sm-12 col-md-3">
                                    <label for="">Flight Type</label>
                                    <select name="return_flight_type" required id="flights_type2" class="form-control">
                                        <option attr="select" selected>Select Flight Type</option>
                                         <option attr="Direct" value="Direct">Direct</option>
                                          <option attr="Indirect" value="Indirect">Indirect</option>
                                    </select>
                                    </div>
                                    
                                    <div class="col-sm-12 col-md-3">
                                        
                                         <div>
                                    <label for="">Airline</label>
                                    <div class="input-group">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1 add_airline">
                                               +
                                            </a>
                                        </span>
                                        <select name="return_selected_flight_airline" required class="form-control airline_data" onchange="changeairlineFunction()" >
                                        <option attr="select" selected>Select Airline</option>
                                         <!--<option value="PIA">PIA</option>-->
                                          <!--<option value="Air_blue">Air blue</option>-->
                                           @if(isset($airline))
             @foreach($airline as $all_airline)
              <option value="{{$all_airline->other_Airline_Name}}">{{$all_airline->other_Airline_Name}}</option>
              @endforeach
              @endif
                                    </select>
                                    </div>
                                    </div>
                                        
                                        
                                        
                            
                                    </div>
                                  
                                    <div class="col-sm-12 col-md-3" id="flights_type_connected2"></div>
                                  
                                    
                                </div>
    
                            
    
                                <div class="col-xl-3"></div>
                                
                               
                                
                                <!--<div class="container" style="display:none" id="total_Time_Div2">-->
                                <!--    <div class="row" style="margin-left: 320px">-->
                                <!--        <div class="col-sm-3">-->
                                <!--            <h3 style="width: 125px;margin-top: 25px;float:right" id="stop_No2">Direct :</h3>-->
                                <!--        </div>-->
                                <!--        <div class="col-sm-3">-->
                                <!--             <label for="">Flight Duration</label>-->
                                <!--            <input type="text" id="total_Time2" name="total_Time" class="form-control total_Time12" readonly style="width: 170px;" value="">-->
                                <!--        </div>-->
                                <!--    </div>-->
                                <!--</div>-->
                                
                                <div class="container Flight_section_append2"></div>
                                
                                <div class="container return_Flight_section2">
                                    <h3 style="padding: 12px">Return Details : </h3>
                                    <div class="row" style="padding: 12px">
                                        <div class="col-xl-4">
                                            <label for="">Departure Airport</label>
                                            <input name="return_departure_airport_code[]" id="return_departure_airport_code" class="form-control" autocomplete="off" placeholder="Enter Return Location">
                                        </div>
                                        <div class="col-xl-1" style="margin-top: 25px;text-align: center;">
                                            <label for=""></label>
                                            <span id="return_Change_Location" class='bi bi-arrow-left-right' style="font-size: 23px;"></span>
                                        </div>
                                        <div class="col-xl-4">
                                            <label for="">Arrival Airport</label>
                                            <input name="return_arrival_airport_code[]" id="return_arrival_airport_code" class="form-control" autocomplete="off" placeholder="Enter Return Location">
                                            
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="">Airline Name</label>
                                            <input type="text" id="return_other_Airline_Name2" name="return_other_Airline_Name2[]" class="form-control other_airline_Name1">
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
                                            <select  name="return_departure_Flight_Type[]" id="return_departure_Flight_Type" class="form-control">
                                                <option value="">Select Flight Type</option>
                                                <option value="Bussiness">Bussiness</option>
                                                <option value="Economy">Economy</option>
                                                <option value="Standard">Standard</option>
                                            </select>
                                        </div>
                                        <div class="col-xl-3" style="margin-top: 15px;">
                                            <label for="">Flight Number</label>
                                            <input type="text" id="simpleinput" name="return_departure_flight_number[]" class="form-control" placeholder="Enter Flight Number">
                                        </div>
                                        <div class="col-xl-3" style="margin-top: 15px;">
                                            <label for="">Departure Date and Time</label>
                                            <input type="datetime-local" id="simpleinput" name="return_departure_time[]" class="form-control return_departure_time1" value="" >
                                        </div>
                                        <div class="col-xl-3" style="margin-top: 15px;">
                                            <label for="">Arrival Date and Time</label>
                                            <input type="datetime-local" id="simpleinput" name="return_arrival_time[]" class="form-control return_arrival_time1" value="" >
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="container" style="display:none" id="return_total_Time_Div2">
                                    <div class="row" style="margin-left: 320px">
                                        <div class="col-sm-3">
                                            <h3 style="width: 162px;margin-top: 25px;float:right" id="return_stop_No2">Return Direct :</h3>
                                        </div>
                                        <div class="col-sm-3">
                                             <label for="">Flight Duration</label>
                                            <input type="text" id="return_total_Time2" name="return_total_Time[]" class="form-control return_total_Time1" readonly style="width: 170px;" value="">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="container return_Flight_section_append2"></div>
                                
                                <div class="row" style="padding-left: 24px;">
                                   
                                    
                                     <div class="col-sm-6 col-md-4">
                                    <label>Number of Seats</label>
                                    <div class="input-group">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">
                                               {{$currency ?? ""}}
                                            </a>
                                        </span>
                                        <input type="text"onchange="myFunction()" id="flights_number_of_seat" name="flights_number_of_seat" class="form-control">
                                    </div>
                                    </div>
                                     <div class="col-sm-6 col-md-4">
                                    <label for="">Price Per Adult</label>
                                    <div class="input-group">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">
                                              {{$currency ?? ""}} 
                                            </a>
                                        </span>
                                        <input type="text"onchange="myFunction()" id="flights_per_adult_price" name="flights_per_person_price" class="form-control">
                                    </div>
                                    </div>
                                    <!-- <div>-->
                                    <!--<label for="">Price Per Seat</label>-->
                                    <!--<div class="input-group">-->
                                    <!--    <span class="input-group-btn input-group-append">-->
                                    <!--        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">-->
                                    <!--           {{$currency ?? ""}}-->
                                    <!--        </a>-->
                                    <!--    </span>-->
                                    <!--    <input type="text" id="flights_per_person_price" name="flights_per_seat_price" class="form-control">-->
                                    <!--</div>-->
                                    <!--</div>-->
                                     <div class="col-sm-6 col-md-4">
                                    <label for="">Child Price</label>
                                    <div class="input-group">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">
                                               {{$currency ?? ""}}
                                            </a>
                                        </span>
                                        <input type="text"onchange="myFunction()" id="flights_per_child_price" name="flights_per_child_price" class="form-control">
                                    </div>
                                    </div>
                                     <div class="col-sm-6 col-md-4">
                                    <label for="">Infant Price</label>
                                    <div class="input-group">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">
                                               {{$currency ?? ""}}
                                            </a>
                                        </span>
                                        <input type="text"onchange="myFunction()" id="flights_per_infant_price" name="flights_per_infant_price" class="form-control">
                                    </div>
                                    </div> 
                                    <div class="col-sm-6 col-md-4">
                                    <label for="">Total Price</label>
                                    <div class="input-group">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">
                                               {{$currency ?? ""}}
                                            </a>
                                        </span>
                                        <input type="text" readonly id="flights_per_person_total_price"  name="flights_total_price" class="form-control">
                                    </div>
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
                                
                                <div class="col-xl-12 mt-2" style="padding-left: 24px;">
                                    <label for="">image</label>
                                    <input type="file" id="" name="flights_image" class="form-control">
                                </div>
                                
                                <div id="append_flights" class="mb-3"></div>
                                
                                
                                
                               
                            
                            
                            
                            </div>
                            <!-- END Flifhts2 -->
                                
                                
                            </div>
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            </div>
                            <!-- END Flifhts -->
                            
                            
                            
                           
                            <!--<a id="save_Flights" class="btn btn-primary" name="submit">Save Flights</a>-->
                           
                        </div>
                    
                  
                  
                
                </div>
                <!-- Main Tab Contant End -->
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
@include('template/frontend/userdashboard/includes/newfooter1')

<script src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initAutocomplete&language=nl&output=json&key=AIzaSyBmwlQFQKwxZ4D8nRbsWVRTBFUHMO-lUOY" async defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs5.min.js" integrity="sha512-6F1RVfnxCprKJmfulcxxym1Dar5FsT/V2jiEUvABiaEiFWoQ8yHvqRM/Slf0qJKiwin6IDQucjXuolCfCKnaJQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script type="text/javascript">

    function changeairlineFunction(){
 
     var airline_name =  $('.airline_data').val();
       
            
  

      } 

    function myFunction(){
            var adult_price = $('#flights_per_adult_price').val();
            var seats = $('#flights_number_of_seat').val();
            // var child_price = $('#flights_per_child_price').val();
            // var infant_price = $('#flights_per_infant_price').val();
             
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
            
             
             
             
            var price_addition = parseFloat(adult_price);
            // console.log(price_addition)
            
            var multiple = seats * price_addition;
            console.log(multiple);
            $('#flights_per_person_total_price').val(multiple);
            
        }


    $(document).ready(function() {
        
        $('.summernote').summernote({});
    });
    
    $("#child_prices_button").click(function () {
        $('#child_prices_div').toggle();
   }); 
   $(".add_airline").click(function () {
       
        $('#add_airline_modal').modal("show");
        
              
      
   });
   $(".add_flight_in_db").click(function () {
       
        var airline = $('.new_flight_data').val();
         
          $.ajax({    
            type: "POST",
            url: "{{URL::to('')}}/addnewflight",
            data:{
                "_token"                : "{{ csrf_token() }}",
                'title'                 : airline,
             
            },
            success: function(data){
                console.log(data);
                
            }
        });
              
      
   });
   
   function child_prices_grand_total(){
       var child_flight_cost = $('#child_flight_cost').val();
       var child_visa_cost = $('#child_visa_cost').val();
       var child_Transporation_cost = $('#child_Transporation_cost').val();
       
       var grandCost = +child_flight_cost + +child_visa_cost + +child_Transporation_cost;
       
       $('#child_total_cost_price').val(grandCost);
       
        var child_flight_sale = $('#child_flight_sale').val();
       var child_visa_sale = $('#child_visa_sale').val();
       var child_Transporation_sale = $('#child_Transporation_sale').val();
       
       var grandSale = +child_flight_sale + +child_visa_sale + +child_Transporation_sale;
       
       $('#child_total_sale_price').val(grandSale);
   }
   function infant_prices_grand_total(){
       var infant_flight_cost = $('#infant_flight_cost').val();
       var infant_visa_cost = $('#infant_visa_cost').val();
       var infant_Transporation_cost = $('#infant_Transporation_cost').val();
       
       var grandCost = +infant_flight_cost + +infant_visa_cost + +infant_Transporation_cost;
       
       $('#infant_total_cost_price').val(grandCost);
       
        var infant_flight_sale = $('#infant_flight_sale').val();
       var infant_visa_sale = $('#infant_visa_sale').val();
       var infant_Transporation_sale = $('#infant_Transporation_sale').val();
       
       var grandSale = +infant_flight_sale + +infant_visa_sale + +infant_Transporation_sale;
       
       $('#infant_total_sale_price').val(grandSale);
   }
   
   
   // Flights Prices Calac
   function child_Flight_prices_calc(){
       var childFlightPrice = $('#child_flight_price').val();
        $('#child_flight_cost').val(childFlightPrice);
   }
   
   function child_flight_markup_calc(){
       var childFlMarktype  = $('#child_flight_markup_type').val();
       var FlightCostPrice = $('#child_flight_cost').val();
       var FlightMarkupPrice = $('#child_flight_markup_value').val();
       var FlightSalePrice = 0;
       if(childFlMarktype == '%'){
           
           var FlightSalePrice = (FlightCostPrice * FlightMarkupPrice/100) + parseFloat(FlightCostPrice);
           console.log('The Markup Type is % '+childFlMarktype)
       }else{
           
           FlightSalePrice = +FlightCostPrice + +FlightMarkupPrice;
           console.log('The Markup Type is NUM '+childFlMarktype)
           
       }
       
       $('#child_flight_sale').val(FlightSalePrice);
   }
   
  $('#child_flight_price').on('keyup change',function(){
     child_Flight_prices_calc();
     child_flight_markup_calc();
     child_prices_grand_total();
  })
  
   $('#child_flight_markup_value').on('keyup change',function(){
     child_Flight_prices_calc();
     child_flight_markup_calc();
     child_prices_grand_total()
   })
   
   
   
   // Visa Prices Calac
   function child_Visa_prices_calc(){
       var childVisaPrice = $('#child_visa_price').val();
        // $('#child_visa_cost').val(childVisaPrice);
   }
   
   function child_Visa_markup_calc(){
       var childVisaMarktype  = $('#child_visa_markup_type').val();
       var VisaCostPrice = $('#child_visa_cost').val();
       var VisaMarkupPrice = $('#child_visa_markup_value').val();
       var VisaSalePrice = 0;
       if(childVisaMarktype == '%'){
           
           var VisaSalePrice = (VisaCostPrice * VisaMarkupPrice/100) + parseFloat(VisaCostPrice);
           console.log('The Markup Type is % '+childVisaMarktype)
       }else{
           
           VisaSalePrice = +VisaCostPrice + +VisaMarkupPrice;
           console.log('The Markup Type is NUM '+childVisaMarktype)
           
       }
       
       $('#child_visa_sale').val(VisaSalePrice);
   }
   
   $('#child_visa_price').on('keyup change',function(){
    //  child_Visa_prices_calc();
    //  child_Visa_markup_calc();
    //  child_prices_grand_total()
   })
  
   $('#child_visa_markup_value').on('keyup change',function(){
     child_Visa_prices_calc();
     child_Visa_markup_calc();
     child_prices_grand_total()
   })
   
   
    // Transportation Prices Calac
   function child_Trans_prices_calc(){
       var childTransPrice = $('#child_transportation_price').val();
        $('#child_Transporation_cost').val(childTransPrice);
   }
   
   function child_Trans_markup_calc(){
       var childTransMarktype  = $('#child_Transporation_markup_type').val();
       var transCostPrice = $('#child_Transporation_cost').val();
       var transMarkupPrice = $('#child_Transporation_markup_value').val();
       var transSalePrice = 0;
       if(childTransMarktype == '%'){
           
           var transSalePrice = (transCostPrice * transMarkupPrice/100) + parseFloat(transCostPrice);
           console.log('The Markup Type is % '+childTransMarktype)
       }else{
           
           transSalePrice = +transCostPrice + +transMarkupPrice;
           console.log('The Markup Type is NUM '+childTransMarktype)
           
       }
       
       $('#child_Transporation_sale').val(transSalePrice);
   }
   
   $('#child_transportation_price').on('keyup change',function(){
    //  child_Trans_prices_calc();
    //  child_Trans_markup_calc();
    //  child_prices_grand_total()
   })
  
   $('#child_Transporation_markup_value').on('keyup change',function(){
    //  child_Trans_prices_calc();
     child_Trans_markup_calc();
   child_prices_grand_total()
   })
   //end
   
   // infant start
   function infant_Flight_prices_calc(){
       var childFlightPrice = $('#child_flight_price_infant').val();
        $('#infant_flight_cost').val(childFlightPrice);
   }
   
   function infant_flight_markup_calc(){
       var childFlMarktype  = $('#infant_flight_markup_type').val();
       var FlightCostPrice = $('#infant_flight_cost').val();
       var FlightMarkupPrice = $('#infant_flight_markup_value').val();
       var FlightSalePrice = 0;
       if(childFlMarktype == '%'){
           
           var FlightSalePrice = (FlightCostPrice * FlightMarkupPrice/100) + parseFloat(FlightCostPrice);
           console.log('The Markup Type is % '+childFlMarktype)
       }else{
           
           FlightSalePrice = +FlightCostPrice + +FlightMarkupPrice;
           console.log('The Markup Type is NUM '+childFlMarktype)
           
       }
       
       $('#infant_flight_sale').val(FlightSalePrice);
   }
   
  $('#infant_flight_price').on('keyup change',function(){
     infant_Flight_prices_calc();
     infant_flight_markup_calc();
     infant_prices_grand_total();
  })
  
   $('#infant_flight_markup_value').on('keyup change',function(){
     //infant_Flight_prices_calc();
     infant_flight_markup_calc();
     infant_prices_grand_total()
   })
   
   
   
   // Visa Prices Calac
   function infant_Visa_prices_calc(){
       var childVisaPrice = $('#child_visa_price_infant').val();
        // $('#child_visa_cost').val(childVisaPrice);
   }
   
   function infant_Visa_markup_calc(){
       var childVisaMarktype  = $('#child_visa_markup_type').val();
       var VisaCostPrice = $('#infant_visa_cost').val();
       var VisaMarkupPrice = $('#infant_visa_markup_value').val();
       var VisaSalePrice = 0;
       if(childVisaMarktype == '%'){
           
           var VisaSalePrice = (VisaCostPrice * VisaMarkupPrice/100) + parseFloat(VisaCostPrice);
           console.log('The Markup Type is % '+childVisaMarktype)
       }else{
           
           VisaSalePrice = +VisaCostPrice + +VisaMarkupPrice;
           console.log('The Markup Type is NUM '+childVisaMarktype)
           
       }
       
       $('#infant_visa_sale').val(VisaSalePrice);
   }
   
   $('#infant_visa_price').on('keyup change',function(){
    //  child_Visa_prices_calc();
    //  child_Visa_markup_calc();
    //  child_prices_grand_total()
   })
  
   $('#infant_visa_markup_value').on('keyup change',function(){
     //infant_Visa_prices_calc();
     infant_Visa_markup_calc();
     infant_prices_grand_total()
   })
   
   
    // Transportation Prices Calac
   function infant_Trans_prices_calc(){
       var childTransPrice = $('#child_transportation_price_infant').val();
        $('#infant_Transporation_cost').val(childTransPrice);
   }
   
   function infant_Trans_markup_calc(){
       var childTransMarktype  = $('#infant_Transporation_markup_type').val();
       var transCostPrice = $('#infant_Transporation_cost').val();
       var transMarkupPrice = $('#infant_Transporation_markup_value').val();
       var transSalePrice = 0;
       if(childTransMarktype == '%'){
           
           var transSalePrice = (transCostPrice * transMarkupPrice/100) + parseFloat(transCostPrice);
           console.log('The Markup Type is % '+childTransMarktype)
       }else{
           
           transSalePrice = +transCostPrice + +transMarkupPrice;
           console.log('The Markup Type is NUM '+childTransMarktype)
           
       }
       
       $('#infant_Transporation_sale').val(transSalePrice);
   }
   
   $('#infant_transportation_price').on('keyup change',function(){
    //  child_Trans_prices_calc();
    //  child_Trans_markup_calc();
    //  child_prices_grand_total()
   })
  
   $('#infant_Transporation_markup_value').on('keyup change',function(){
    //  infant_Trans_prices_calc();
     infant_Trans_markup_calc();
 infant_prices_grand_total()
   })
   
   
   //infant end
   $('#flights_per_person_price').keyup(function() {
   
    var flights_per_person_price = this.value;
   $('#flights_prices').val(flights_per_person_price);
    add_numberElse();
        calculateGrandWithoutAccomodation();
       
});
   function calculateGrandWithoutAccomodation(){
         var flightCostPrice = $('#flights_per_person_price').val();
         var visa_price_select = $('#exchange_rate_visa_total_amount').val();
         var transportCostPrice = $('#exchange_rate_transportation_price_per_person').val();
         
         var GrandWAcprice = +transportCostPrice + +visa_price_select + +flightCostPrice;
         console.log('Grand total is '+GrandWAcprice);
         $('#without_acc_cost_price').val(GrandWAcprice);
         $('#without_acc_sale_price').val(GrandWAcprice);
         
   }
</script>

<script>
    
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
       var without_acc_cost_price =  $('#without_acc_cost_price').val();
        
       if(without_acc_cost_price != 0){
           console.log('without_acc_cost_price'+ total_without_acc_cost_price);
            var without_acc_cost_price1 = (without_acc_cost_price * markup_val/100) + parseFloat(without_acc_cost_price);
            var total_without_acc_cost_price = without_acc_cost_price1.toFixed(2); 
            $('#without_acc_sale_price_grand').val(total_without_acc_cost_price);
            console.log('without_acc_sale_price_grand'+ total_without_acc_cost_price);
        }else{
           $('#without_acc_sale_price_grand').val(0);
        }
       
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
        var without_acc_cost_price =  $('#without_acc_cost_price').val();
        
        if(without_acc_cost_price != 0){
            var without_acc_cost_price1 =  parseFloat(without_acc_cost_price) +  parseFloat(markup_val);
            var total_without_acc_cost_price = without_acc_cost_price1.toFixed(2); 
            $('#without_acc_sale_price_grand').val(total_without_acc_cost_price);  
        }else{
           $('#without_acc_sale_price_grand').val(0);
        }
        
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
            type: "GET",
            url: "get_other_visa_type_detail",             
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
  
  calculateGrandWithoutAccomodation();
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
        
    // $('#visa_fee').keyup(function() {
        
    //     var visa_fee = this.value;
    //     $('#visa_price_select').val(visa_fee);
    //     add_numberElse();
    //     calculateGrandWithoutAccomodation();
    // });
        
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
    
    // Usama Ali Changes
    
    // Flights
    $("#flights_type").change(function () {
            var id = $(this).find('option:selected').attr('value');
            if(id == 'Indirect')
            {
                $("#text_editer").css("padding", "20");
                $('#flights_type_connected').fadeIn();
                $('#flights_type_connected').empty();
                var no_of_stays_Append = `<label for="no_of_stays">No Of Stops</label>
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
            $flight_name = $('.airline_data').val();
            console.log($flight_name);
            for (let i = 1; i <= no_of_stays; i++) {
                var flight_Data =   `<h3 style="padding: 12px">Departure Details : </h3>
                                     <div class="row" style="padding: 12px">
                                        <div class="col-xl-4">
                                            <label for="">Departure Airport</label>
                                            <input name="departure_airport_code[]" id="departure_airport_code_${i}" class="form-control" autocomplete="off" placeholder="Enter Departure Location">
                                        </div>
                                        <div class="col-xl-1" style="margin-top: 25px;text-align: center;">
                                            <label for=""></label>
                                            <span id="Change_Location_${i}" class='bi bi-arrow-left-right' style="font-size: 23px;"></span>
                                        </div>
                                        <div class="col-xl-4">
                                            <label for="">Arrival Airport</label>
                                            <input name="arrival_airport_code[]" id="arrival_airport_code_${i}" class="form-control" autocomplete="off" placeholder="Enter Arrival Location">
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="">Airline Name</label>
                                            <input type="text" id="other_Airline_Name2_${i}" value="${$flight_name}" name="other_Airline_Name2[]" class="form-control other_airline_Name1_${i}">
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="">Class Type</label>
                                            <select  name="departure_Flight_Type[]" id="departure_Flight_Type_${i}" class="form-control">
                                                <option value="">Select Flight Type</option>
                                                <option value="Bussiness">Bussiness</option>
                                                <option value="Economy">Economy</option>
                                                <option value="Standard">Standard</option>
                                            </select>
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="">Flight No</label>
                                            <input type="text" id="simpleinput_${i}" name="departure_flight_number[]" class="form-control" placeholder="Enter Flight Number">
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="">Departure Date & Time</label>
                                            <input type="datetime-local" id="simpleinput_${i}" name="departure_time[]" class="form-control departure_time1_${i}" >
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="">Arrival Date and Time</label>
                                            <input type="datetime-local" id="simpleinput_${i}" name="arrival_time[]" class="form-control arrival_time1_${i}" >
                                        </div>
                                    </div>
                                    <div class="container total_Time_Div_data_append_${i}">
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
                                                        <label for="">Flight Duration</label>
                                                        <input type="text" id="total_Time" name="total_Time[]" class="form-control total_Time1_${i}" readonly style="width: 167px;">
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
                // $('.return_Flight_section_append').append(return_flight_Data);
                
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
                                                        <label for="">Flight Duration</label>
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
        
                var data = ``;
          
              
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
                    var data = ``;
                                    
        
          
                    $("#append_accomodation_data_cost").append(data_cost);
                  
                    $("#append_accomodation").append(data);
                    
                            $("#destination_"+i+'').on('click',function(){
            $("#select_destination_"+i+'').slideToggle();
        });
        
        // function currency_funs(id){
            
           
        //      $("#select_destination_"+id+'').slideToggle();
           
            
        // }
        
        
        
         
        
        
        
        
     
                    
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
        
                // var select_ct =$(".select_ct").val();
                
                // var count_1 = select_ct.length;
                
                // for (let i = 1; i <= count_1; i++) {
                //     var data1 = `<div class="mb-2" style="border:1px solid #ced4da;padding: 20px 20px 20px 20px;"><h4>City #${i} </h4><div class="row"><div class="col-xl-3"><label for="">Hotel Name</label><input type="text" id="simpleinput" name="acc_hotel_name[]" class="form-control">
                //         </div><div class="col-xl-3"><label for="">Check In</label><input type="date" id="simpleinput" name="acc_check_in[]" class="form-control">
                //         </div><div class="col-xl-3"><label for="">Check Out</label><input type="date" id="simpleinput" name="acc_check_out[]" class="form-control"></div><div class="col-xl-3"><label for="">No Of Nights</label><input type="text" id="nights" name="acc_no_of_nightst[]" class="form-control"></div><div class="col-xl-3"><label for="">Room Type</label>
                //         <select name="acc_type[]" id="property_city" class="form-control"  data-placeholder="Choose ..."><option value="">Choose ...</option><option value="Quad">Quad</option><option value="Triple">Triple</option><option value="Double">Double</option></select></div>
                //         <div class="col-xl-3"><label for="">Quantity</label><input type="text" id="simpleinput" name="acc_qty[]" class="form-control"></div>
                //         <div class="col-xl-3"><label for="">Pax</label><input type="text" id="simpleinput" name="acc_pax[]" class="form-control"></div><div class="col-xl-3"><label for="">Price</label>
                //         <input type="text" id="simpleinput" name="acc_price[]" class="form-control"></div><div class="col-xl-3"><label for="">Currency</label><select name="acc_currency[]" id="property_city" class="form-control"><option value="">Choose ...</option><option value="SAR">SAR</option><option value="Dollar">Dollar</option><option value="Pound">Pound</option></select></div><div class="col-xl-3"><label for="">Comission</label><input type="text" id="simpleinput" name="acc_commision[]" class="form-control"></div><div class="col-xl-3"><label for="">Sale Price</label><input type="text" id="simpleinput" name="acc_sale_Porice[]" class="form-control"></div><div class="col-xl-3"><label for="">Total Amount</label><input type="text" id="simpleinput" name="acc_total_amount[]" class="form-control"></div>
                //         <div id="append_add_accomodations_${i}"></div><div class="mt-2"><a href="javascript:;" onclick="add_more_accomodations(${i})"  id="" class="btn btn-info" style="float: right;"> + Add More </a></div></div></div>`;
                //     $("#append_accomodation").append(data1);   
                  
                // }
            
                $("#select_accomodation").slideToggle();
                
            }
            else{
                alert("Select Hotels Quantity");
            }
            
            
            
          
             var value_c = $("#currency_conversion").val();
        
                // var v = ( this.code );
                 //alert(code);
         
const usingSplit = value_c.split(' ');
var value_1 = usingSplit['0'];
var value_2 = usingSplit['3'];

console.log(value_1);
console.log(value_2);
              
exchange_currency_funs(value_1,value_2);
              
            
            
        });
        
        // var city_No         = $('#city_No').val();
        var city_No1        = $('#city_No1').val();
        var img_Counter1    = $('#img_Counter1').val();
        // var i               = city_No;
        var j               = img_Counter1;
        
        $("#add_hotel_accomodation_edit").on('click',function(){
            
            city_No1 = parseFloat(city_No1) + 1;
            $('#city_No1').val(city_No1);
            
            var city_No = $('#city_No').val();
            var i       = parseFloat(city_No) + 1;
            $('#city_No').val(i);
            
            var data = ``;
          
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
                
                var data = ``;
          
              
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





<!--Flights2-->
<script>
  $("#flights_type2").on('change',function () {
        var id = $(this).val();
        $('#flights_departure_code').val(id);
    });
    
     $("#flights_type2").change(function () {
            var id = $(this).find('option:selected').attr('value');
            if(id == 'Indirect')
            {
                $("#text_editer2").css("padding", "20");
                $('#flights_type_connected2').fadeIn();
                $('#flights_type_connected2').empty();
                var no_of_stays_Append = `<label for="no_of_stays">No Of Stops</label>
                                            <select  name="return_no_of_stays" id="no_of_stays2" class="form-control select2" data-toggle="select2">
                                                <option value="">Choose...</option>
                                                <option value="2">1</option>
                                                <option value="3">2</option>
                                                <option value="4">3</option>
                                            </select>`;
                $('#flights_type_connected2').append(no_of_stays_Append);
                $('#text_editer2').fadeOut();
                $('#text_editer2').fadeIn();
                $('#stop_No2').replaceWith($('<h3 style="width: 125px;margin-top: 25px;float:right" id="stop_No2">Stop No 1 :</h3>'));
                $('#return_stop_No2').replaceWith($('<h3 style="width: 182;margin-top: 25px;float:right" id="return_stop_No2">Return Stop No 1 :</h3>'));

                $('.Flight_section2').fadeOut();
                $('.return_Flight_section2').fadeOut();
                $('#total_Time_Div2').fadeOut();
                $('#return_total_Time_Div2').fadeOut();
            }
            else
            {
                $('.Flight_section2').fadeIn();
                $('.return_Flight_section2').fadeIn();
                $('#total_Time_Div2').fadeIn();
                $('#return_total_Time_Div2').fadeIn();
                
                $('#flights_type_connected2').fadeOut();
                $('.Flight_section_append2').empty();
                $('.return_Flight_section_append2').empty();
            	$('#text_editer2').fadeOut();
            	$('#stop_No2').replaceWith($('<h3 style="width: 125px;margin-top: 25px;float:right" id="stop_No2">Direct :</h3>'));
            	$('#return_stop_No2').replaceWith($('<h3 style="width: 162px;margin-top: 25px;float:right" id="return_stop_No2">Return Direct :</h3>'));
            }
        });
        
        
   $('#flights_type_connected2').change(function () {
        var no_of_stays = $('#no_of_stays2').val();
        if(no_of_stays == 'NON_STOP'){
            $('.Flight_section_append').empty();
            $('.return_Flight_section_append').empty();
        }
        else{
            $('.Flight_section_append2').empty();
            $('.return_Flight_section_append2').empty();
            var no_of_stay_ID = 1;
            $flight_name = $('.airline_data').val();
            console.log($flight_name);
            for (let i = 1; i <= no_of_stays; i++) {
                var flight_Data =   `<h3 style="padding: 12px">Departure Details : </h3>
                                     <div class="row" style="padding: 12px">
                                        <div class="col-xl-4">
                                            <label for="">Departure Airport</label>
                                            <input name="return_departure_airport_code[]" id="2departure_airport_code_${i}" class="form-control" autocomplete="off" placeholder="Enter Departure Location">
                                        </div>
                                        <div class="col-xl-1" style="margin-top: 25px;text-align: center;">
                                            <label for=""></label>
                                            <span id="2Change_Location_${i}" class='bi bi-arrow-left-right' style="font-size: 23px;"></span>
                                        </div>
                                        <div class="col-xl-4">
                                            <label for="">Arrival Airport</label>
                                            <input name="return_arrival_airport_code[]" id="2arrival_airport_code_${i}" class="form-control" autocomplete="off" placeholder="Enter Arrival Location">
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="">Airline Name</label>
                                            <input type="text" id="2other_Airline_Name2_${i}" value="${$flight_name}" name="return_other_Airline_Name2[]" class="form-control other_airline_Name1_${i}">
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="">Class Type</label>
                                            <select  name="return_departure_Flight_Type[]" id="2departure_Flight_Type_${i}" class="form-control">
                                                <option value="">Select Flight Type</option>
                                                <option value="Bussiness">Bussiness</option>
                                                <option value="Economy">Economy</option>
                                                <option value="Standard">Standard</option>
                                            </select>
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="">Flight No</label>
                                            <input type="text" id="2simpleinput_${i}" name="return_departure_flight_number[]" class="form-control" placeholder="Enter Flight Number">
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="">Departure Date & Time</label>
                                            <input type="datetime-local" id="2simpleinput_${i}" name="return_departure_time[]" class="form-control 2departure_time1_${i}" >
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="">Arrival Date and Time</label>
                                            <input type="datetime-local" id="2simpleinput_${i}" name="return_arrival_time[]" class="form-control 2arrival_time1_${i}" >
                                        </div>
                                    </div>
                                    <div class="container 2total_Time_Div_data_append_${i}">
                                    </div>`;
                                    
               
                                    
                $('.Flight_section_append2').append(flight_Data);
                
                $('#2Change_Location_'+i+'').click(function () {
                    var arrival_airport_code   = $('#2arrival_airport_code_'+i+'').val();
                    var departure_airport_code = $('#2departure_airport_code_'+i+'').val();
                    $('#2arrival_airport_code_'+i+'').val(departure_airport_code);
                    $('#2departure_airport_code_'+i+'').val(arrival_airport_code);
                });
                
                $('.2arrival_time1_'+i+'').change(function () {
                    
                    var h = "hours";
                    var m = "minutes";
                    
                    var arrival_time1 = $(this).val();
                    var departure_time1 = $('.2departure_time1_'+i+'').val();
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
                                                    <label for="">Flight Duration</label>
                                                        <input type="text" id="2total_Time" name="return_total_Time[]" class="form-control 2total_Time1_${i}" readonly style="width: 167px;">
                                                    </div>
                                                </div>`;
                    $('.2total_Time_Div_data_append_'+i+'').empty()
                    $('.2total_Time_Div_data_append_'+i+'').append(total_Time_Div_data);
                    $('.2total_Time1_'+i+'').val(total_hours+h+ ' : ' +minutes+m);
                    
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
                            $('#2other_Airline_Name2_'+i+'').append('<option attr=' +value.other_Airline_Name+ ' value=' +value.other_Airline_Name+ '>' +value.other_Airline_Name+'</option>');
                            $('#2return_other_Airline_Name2_'+i+'').append('<option attr=' +value.other_Airline_Name+ ' value=' +value.other_Airline_Name+ '>' +value.other_Airline_Name+'</option>');
                        });  
                    }
                });
                
                $('#2departure_airport_code_'+i+'').on('change',function () {
                    setTimeout(function() {
                        console.log("Working");
                        var address = $('#departure_airport_code_'+i+'').val();
                        $('#flights_arrival_code').val(address);
                        console.log(address);
                    }, 2000);
                });
                
                var places_D1 = new google.maps.places.Autocomplete(
                    document.getElementById('2departure_airport_code_'+i+'')
                );
                
                var places1_D1 = new google.maps.places.Autocomplete(
                    document.getElementById('2arrival_airport_code_'+i+'')
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
                // $('.return_Flight_section_append2').append(return_flight_Data);
                
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
                                                        <label for="">Flight Duration</label>
                                                        <input type="text" id="return_total_Time" name="return_more_total_Time" class="form-control return_total_Time1_${i}" readonly style="width: 167px;">
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
</script>

<!--End Flights2-->

@stop



