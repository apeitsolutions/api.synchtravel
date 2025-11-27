@extends('template/frontend/userdashboard/layout/default')
@section('content')
    
    <?php $currency = Session::get('currency_symbol'); ?>

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
                                <input type="text" name="other_Airline_Name" class="form-control other_Airline_Name_class" id="other_Airline_Name_class">
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary mt-3 add_flight_in_db" id="other_Airline_Name_submit">Submit</button>
                    </div> 
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-content">
   
        <h2>ADD SEATS</h2>
        <form action="{{ url('addseat1') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                    
                <div class="col-md-12" id="select_flights_inc">
                    
                    <div class="row" style="padding: 25px;">
                        
                        <h4>DEPARTURE DETAILS</h4>
                        
                        <div class="col-sm-12 col-md-3" style="padding: 10px;">
                            <label for="">Select Supplier</label>
                                <select class="form-control" name="supplier" id="departure_supplier_id" required>
                                    <option value="">Select supplier</option>
                                    @if(isset($supplier)) 
                                        @foreach($supplier as $all_supplier)
                                            <option value="{{$all_supplier->multi_rute_suplier->id}}" attr-name="{{$all_supplier->multi_rute_suplier->companyname}}">{{$all_supplier->multi_rute_suplier->companyname}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        
                        <div class="col-sm-12 col-md-3" style="padding: 10px;">
                            <label for="">Flight Type</label>
                            <select name="flight_type" id="flights_type" class="form-control">
                                <option attr="select">Select Flight Type</option>
                                <option attr="Direct" value="Direct">Direct</option>
                                <option attr="Indirect" value="Indirect">Indirect</option>
                            </select>
                        </div>
                        
                        <div class="col-sm-12 col-md-3" style="padding: 10px;">
                            <div>
                                <label for="">Airline</label>
                                <div class="input-group">
                                    <select name="selected_flight_airline" id="airline_data" class="form-control airline_data" onchange="changeairlineFunction()" required>
                                        <option attr="select">Select Airline</option>
                                        @if(isset($airline))
                                            @foreach($airline as $all_airline)
                                                <option value="{{ $all_airline->other_Airline_Name }}" attr-id="{{ $all_airline->id }}">{{$all_airline->other_Airline_Name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                <input type="hidden" name="departure_airline_id" id="departure_airline_id">
                                </div>
                            </div>
                        </div>
                      
                        <div class="col-sm-12 col-md-3" style="padding: 10px;" id="flights_type_connected"></div>
                        
                    </div>
                    
                    <div class="container Flight_section" style="display:none">
                        
                        <div class="row" style="">
                            
                            <div class="col-sm-12 col-md-3" style="padding: 10px;">
                                <div>
                                    <label for="">Select Departure Airline Code</label>
                                    <div class="input-group">
                                        <select name="departure_airline_code[]" required id="departure_airline_code" class="form-control" onchange="changeairlineFunction()" >
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
                                <input name="departure_airport_code[]" id="departure_airport_code" class="form-control" autocomplete="off" placeholder="Enter Departure Location" readonly>
                            </div>
                            
                            <div class="col-xl-1" style="padding: 10px;margin-top: 25px;text-align: center;">
                                <label for=""></label>
                                <span id="Change_Location" class='bi bi-arrow-left-right' style="font-size: 23px;"></span>
                            </div>
                            
                            <div class="col-xl-4" style="padding: 10px;">
                                <label for="">Arrival Airport</label>
                                <input name="arrival_airport_code[]" id="arrival_airport_code" class="form-control" autocomplete="off" placeholder="Enter Arrival Location" readonly>
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
                                <select class="form-control" name="return_supplier" id="return_supplier_id" required></select>
                            </div>
                            
                        <div class="col-sm-12 col-md-3" style="padding: 10px;">
                            <label for="">Flight Type</label>
                            <select name="return_flight_type" id="flights_type2" class="form-control" required>
                                <option attr="select" selected>Select Flight Type</option>
                                <option attr="Direct" value="Direct">Direct</option>
                                <option attr="Indirect" value="Indirect">Indirect</option>
                            </select>
                        </div>
                        
                        <div class="col-sm-12 col-md-3" style="padding: 10px;">
                            <label for="">Return Airline</label>
                            <div class="input-group">
                                <select name="return_selected_flight_airline" id="return_airline_data" class="form-control airline_data" onchange="return_changeairlineFunction()" required>
                                    <option attr="select" >Select Airline</option>
                                    @if(isset($airline))
                                        @foreach($airline as $all_airline)
                                            <option value="{{ $all_airline->other_Airline_Name }}" attr-id="{{ $all_airline->id }}">{{ $all_airline->other_Airline_Name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <input type="hidden" name="return_airline_id" id="return_airline_id">
                            </div>
                        </div>
                      
                        <div class="col-sm-12 col-md-3" style="padding: 10px;" id="flights_type_connected2"></div>
                        
                        <div class="container Flight_section_append2"></div>
                        
                        <div class="container return_Flight_section2" style="display:none">
                            
                            <div class="row" style="">
                            
                                <div class="col-sm-12 col-md-3" style="padding: 10px;">
                                    <div>
                                        <label for="">Select Return Airline Code</label>
                                        <div class="input-group">
                                            <select name="return_airline_code[]" required id="return_airline_code" class="form-control" onchange="changeairlineFunction()" >
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
                                    <span id="return_Change_Location" class='bi bi-arrow-left-right' style="font-size: 23px;"></span>
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
                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">
                                   {{$currency ?? ""}}
                                </a>
                            </span>
                            <input type="text" onchange="total_price_cal_fun()" id="flights_number_of_seat" name="flights_number_of_seat" class="form-control" required>
                        </div>
                        </div>
                        
                        <div class="col-sm-6 col-md-3" style="padding: 10px;">
                        <label for="">Price Per Adult</label>
                        <div class="input-group">
                            <span class="input-group-btn input-group-append">
                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">
                                  {{$currency ?? ""}} 
                                </a>
                            </span>
                            <input type="text" onchange="total_price_cal_fun()" id="flights_per_adult_price" name="flights_per_person_price" class="form-control" required>
                        </div>
                        </div>
                        
                        <div class="col-sm-6 col-md-3" style="padding: 10px;">
                        <label for="">Child Price</label>
                        <div class="input-group">
                            <span class="input-group-btn input-group-append">
                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">
                                   {{$currency ?? ""}}
                                </a>
                            </span>
                            <input type="text" id="flights_per_child_price" name="flights_per_child_price" class="form-control" required>
                        </div>
                        </div>
                        
                        <div class="col-sm-6 col-md-3" style="padding: 10px;">
                        <label for="">Infant Price</label>
                        <div class="input-group">
                            <span class="input-group-btn input-group-append">
                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">
                                   {{$currency ?? ""}}
                                </a>
                            </span>
                            <input type="text" id="flights_per_infant_price" name="flights_per_infant_price" class="form-control" required>
                        </div>
                        </div> 
                        
                        <div class="col-sm-6 col-md-4" style="padding: 10px;display:none;">
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
                        
                    <div class="col-xl-12 d-none" style="padding: 25px;" id="text_editer">
                        <label for="">Indirect Flight Duration and Details</label>
                        <textarea name="connected_flights_duration_details" class="form-control" cols="10" rows="10"></textarea>
                    </div>
                    
                    <div class="col-xl-12 d-none" style="padding: 25px;">
                        <label for="">Additional Flight Details</label>
                        <textarea name="terms_and_conditions" class="form-control" cols="5" rows="5"></textarea>
                    </div>
                    
                    <div class="col-xl-12 d-none" style="padding: 25px;">
                        <label for="">image</label>
                        <input type="file" id="" name="flights_image" class="form-control">
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
    
</div>

@endsection

@section('scripts')

    <script src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initAutocomplete&language=nl&output=json&key=AIzaSyBmwlQFQKwxZ4D8nRbsWVRTBFUHMO-lUOY" async defer></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs5.min.js" integrity="sha512-6F1RVfnxCprKJmfulcxxym1Dar5FsT/V2jiEUvABiaEiFWoQ8yHvqRM/Slf0qJKiwin6IDQucjXuolCfCKnaJQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    <script>
    
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
       
    </script>
    
    <script>
        // Flights
        $("#departure_supplier_id").change(function () {
            $("#return_supplier_id").empty();
            var id      = $(this).find('option:selected').attr('value');
            var name    = $(this).find('option:selected').attr('attr-name');
            var data    = `<option value="${id}" selected>${name}</option>`;
            $("#return_supplier_id").append(data);
        });
        
        $("#flights_type").change(function () {
            var id = $(this).find('option:selected').attr('value');
            
            $('#flights_departure_code').val(id);
            if(id == 'Indirect'){
                
                var airline_data = $('#airline_data').val();
                $('#other_Airline_Name2').val(airline_data);
                
                $('#departure_airport_code').val('');
            	$('#arrival_airport_code').val('');
            	$('#departure_flight_number').val('');
            	$('#departure_time').val('');
            	$('#arrival_time').val('');
            	$('#departure_Flight_Type').val('');
                
                $("#text_editer").css("padding", "20");
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
                $('#text_editer').css('display','none');
                $('#text_editer').css('display','');
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
            	$('#text_editer').css('display','none');
            	$('#stop_No').replaceWith($('<h3 style="width: 125px;margin-top: 25px;float:right" id="stop_No">Direct :</h3>'));
            	$('#return_stop_No').replaceWith($('<h3 style="width: 162px;margin-top: 25px;float:right" id="return_stop_No">Return Direct :</h3>'));
            	addGoogleApi('departure_airport_code');
                addGoogleApi('arrival_airport_code');
            }
        });
       
        $('#flights_type_connected').change(function () {
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
                                                <input name="departure_airport_code[]" id="departure_airport_code_${i}" class="form-control" autocomplete="off" readonly>
                                            </div>
                                            <div class="col-xl-1" style="padding: 10px;margin-top: 25px;text-align: center;">
                                                <label for=""></label>
                                                <span id="Change_Location_${i}" class='bi bi-arrow-left-right' style="font-size: 23px;" onclick="change_location_I(${i})"></span>
                                            </div>
                                            <div class="col-xl-4" style="padding: 10px">
                                                <label for="">Arrival Airport</label>
                                                <input name="arrival_airport_code[]" id="arrival_airport_code_${i}" class="form-control" autocomplete="off" readonly>
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
                }
            }
        });
        
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
        
        $('#Change_Location').click(function () {
            var arrival_airport_code   = $('#arrival_airport_code').val();
            var departure_airport_code = $('#departure_airport_code').val();
            $('#arrival_airport_code').val(departure_airport_code);
            $('#departure_airport_code').val(arrival_airport_code);
        });
        
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
        // End Flights
    </script>
    
    <script>
        $("#flights_type2").on('change',function () {
            var id = $(this).val();
            $('#flights_departure_code').val(id);
        });
        
        $("#flights_type2").change(function () {
            var id = $(this).find('option:selected').attr('value');
            if(id == 'Indirect'){
                
                var return_airline_data = $('#return_airline_data').val();
                $('#return_other_Airline_Name2').val(return_airline_data);
                
                $('#return_departure_airport_code').val('');
            	$('#return_arrival_airport_code').val('');
            	$('#return_departure_flight_number').val('');
            	$('#return_departure_time').val('');
            	$('#return_arrival_time').val('');
            	$('#return_departure_Flight_Type').val('');
                
                $("#text_editer2").css("padding", "20");
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
                $('#text_editer2').css('display','none');
                $('#text_editer2').css('display','');
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
            	$('#text_editer2').css('display','none');
            	$('#stop_No2').replaceWith($('<h3 style="width: 125px;margin-top: 25px;float:right" id="stop_No2">Direct :</h3>'));
            	$('#return_stop_No2').replaceWith($('<h3 style="width: 162px;margin-top: 25px;float:right" id="return_stop_No2">Return Direct :</h3>'));
            	
            	addGoogleApi('return_departure_airport_code');
                addGoogleApi('return_arrival_airport_code');
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
                }
              
            }
        });
        
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
        
        $('#return_Change_Location').click(function () {
            var arrival_airport_code   = $('#return_arrival_airport_code').val();
            var departure_airport_code = $('#return_departure_airport_code').val();
            $('#return_arrival_airport_code').val(departure_airport_code);
            $('#return_departure_airport_code').val(arrival_airport_code);
        });
        
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
        
        // End Flights
    </script>

@stop