@extends('template/frontend/userdashboard/layout/default')
@section('content')

<?php $currency=Session::get('currency_symbol'); ?>

<div class="dashboard-content">
    
    <h3 style="color:#a30000">Manage Destination</h3>
    <form action="{{URL::to('add_new_destination')}}" method="post" enctype="multipart/form-data" style="padding-bottom: 40px">
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
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                        <div class="row">
                            <div class="col-md-6" style="padding: 15px;">
                                <label for="">Select Pickup City</label>
                                <input type="text" id="pickup_City" name="pickup_City" class="form-control pickup_City" required>
                            </div>
                            
                            <div class="col-md-6" style="padding: 15px;">
                                <label for="">Select Dropof City</label>
                                <input type="text" id="dropof_City" name="dropof_City" class="form-control dropof_City" required>
                            </div>
                            
                            <div class="col-md-3" style="padding: 15px;">
                                <label for="">Select Category Vehicle</label>
                                <select name="vehicle_Name[]" id="" class="form-control">
                                    <option value="">Select Vehicle</option>
                                    @foreach($data as $value)
                                        <option value="{{ $value->vehicle_Name }}">{{ $value->vehicle_Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-3" style="padding: 15px;">
                                <label for="">Fare</label>
                                <div class="input-group">
                                    <span class="input-group-btn input-group-append">
                                        <a class="btn btn-primary bootstrap-touchspin-up">
                                           <?php echo $currency; ?>
                                        </a>
                                    </span>
                                    <input type="text" id="vehicle_Fare" name="vehicle_Fare[]" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="col-md-2" style="padding: 15px;">
                                <label for="">Markup Type</label>
                                <select name="fare_markup_type[]" id="fare_markup_type" class="form-control">
                                    <option value="">Markup Type</option>
                                    <option value="%">Percentage</option>
                                    <option value="<?php echo $currency; ?>">Fixed Amount</option>
                                </select>
                            </div>
                            
                            <div class="col-md-2" style="padding: 15px;">
                                <label for="">Markup</label>
                                <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                    <input type="text" class="form-control" id="fare_markup" name="fare_markup[]">
                                    <span class="input-group-btn input-group-append">
                                        <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="fare_mrk">%</div></button>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-md-2" style="padding: 15px;">
                                <label for="">Total</label>
                                <div class="input-group">
                                    <input type="text" id="total_fare_markup" name="total_fare_markup[]" class="form-control">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up">
                                               <?php echo $currency; ?>
                                            </a>
                                        </span>
                                </div>
                            </div>
                            
                            <div id="append_Vehicle"></div>
                            
                            <div class="mt-2">
                                <a href="javascript:;" onclick="add_more_vehicle()" class="btn btn-info" style="float: right;"> + Add More </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 text-right mt-3">
                <button type="submit" class="btn btn-primary">Submit Destination</button>
            </div>
        </div>
    </form>
    
    <div class="dashboard-content">
        <h3 style="color:#a30000;font-size: 40px;text-align:center">Vehicle List</h3>
        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <div class="dashboard-list-box dash-list margin-top-0">
                    <div class="row">
                        <div class="col-md-12">
                             <table id="myTable" class="display nowrap table  table-bordered" style="width:100%;">
                                <thead style="background-color:#fe4e37;color:white;">
                                    <tr>
                                        <th>Sr</th>
                                        <th>Pickup Location</th>
                                        <th>Dropof Location</th>
                                        <th>Vehicle Type</th>
                                        <th>Fare</th>
                                        <th>Fare Markup</th>
                                        <th>Total</th>
                                        <th>Options</th>
                                    </tr>
                                </thead>
                                <tbody style="text-align: center;">
                                    @php
                                        $x = 1;
                                    @endphp
                                    @foreach($tranfer_destination as $value)
                                        <?php 
                                                $vehicle_details = json_decode($value->vehicle_details);
                                        ?>
                                            <tr>
                                                <td>{{ $x++ }}</td>
                                                <td>{{ $value->pickup_City }}</td>
                                                <td>{{ $value->dropof_City }}</td>
                                                <td>
                                                    @if(isset($vehicle_details))
                                                        @foreach($vehicle_details as $value2)
                                                            {{ $value2->vehicle_Name ?? '' }} <br>
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(isset($vehicle_details))
                                                        @foreach($vehicle_details as $value2)
                                                            <?php echo $currency; ?>
                                                            {{ $value2->vehicle_Fare ?? '' }}<br>
                                                        @endforeach
                                                    @endif
                                                </td>
                                                
                                                <td>
                                                    @if(isset($vehicle_details))
                                                        @foreach($vehicle_details as $value2)
                                                            {{ $value2->fare_markup ?? '' }}{{ $value2->fare_markup_type ?? '' }}<br>
                                                        @endforeach
                                                    @endif
                                                </td>
                                                
                                                <td>
                                                    @if(isset($vehicle_details))
                                                        @foreach($vehicle_details as $value2)
                                                            <?php echo $currency; ?>
                                                            {{ $value2->total_fare_markup ?? '' }}<br>
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ URL::to('edit_destination_details/'.$value->id.'') }}" class="btn btn-secondary btn-sm">Edit Details</a>
                                                </td>
                                            </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                 
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>


@endsection
@section('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBmwlQFQKwxZ4D8nRbsWVRTBFUHMO-lUOY&sensor=false&libraries=places"></script>

<script>
    let places,places1,input, address, city;
    google.maps.event.addDomListener(window, "load", function () {
        var places = new google.maps.places.Autocomplete(
            document.getElementById("pickup_City")
        );
        
        var places1 = new google.maps.places.Autocomplete(
            document.getElementById("dropof_City")
        );
        
        google.maps.event.addListener(places, "place_changed", function () {
            var place = places.getPlace();
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
        
    });
</script>

<script>
    $(document).ready( function () {
        $('#myTable').DataTable({
            "scrollX": true,
        });
    } );
</script>

<script>

    var divId = 1;
    function add_more_vehicle(id){
    
        var data = `<div id="vehicle_div_${divId}" class="row">
                        <div class="col-md-3" style="padding: 15px;">
                            <label for="">Select Category Vehicle</label>
                            <select name="vehicle_Name[]" id="" class="form-control">
                                <option value="">Select Vehicle</option>
                                @foreach($data as $value)
                                    <option value="{{ $value->vehicle_Name }}">{{ $value->vehicle_Name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-3" style="padding: 15px;">
                            <label for="">Fare</label>
                            <div class="input-group">
                                <span class="input-group-btn input-group-append">
                                    <a class="btn btn-primary bootstrap-touchspin-up">
                                       <?php echo $currency; ?>
                                    </a>
                                </span>
                                <input type="text" id="vehicle_Fare${divId}" name="vehicle_Fare[]" class="form-control" required>
                            </div>
                        </div>
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
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up">
                                               <?php echo $currency; ?>
                                            </a>
                                        </span>
                                </div>
                            </div>
                        <div class="mt-2">
                            <a href="javascript:;" onclick="deleteRowVehicle(${divId})" id="${divId}" class="btn btn-info" style="float: right;">Delete </a>
                        </div>
                    </div>`;
        
        $("#append_Vehicle").append(data);
        divId=divId + 1;
    }
    
    function deleteRowVehicle(id){
        $('#vehicle_div_'+id+'').remove();
    }
    
</script>

<script>
    $("#fare_markup_type").change(function () {
        var id = $(this).find('option:selected').attr('value');
        $('#fare_mrk').text(id);
        console.log(id);
        if(id == '%')
        {
            $('#fare_markup').keyup(function() {
                var fare_markup =  $('#fare_markup').val();
                var vehicle_Fare =  $('#vehicle_Fare').val();
                var total1 = (vehicle_Fare * fare_markup/100) + parseFloat(vehicle_Fare);
                var total = total1.toFixed(2);
                $('#total_fare_markup').val(total);
            });
        }
        else
        {
            $('#fare_markup').keyup(function() {
                var fare_markup =  $('#fare_markup').val();
                var vehicle_Fare =  $('#vehicle_Fare').val();
                var total1 =  parseFloat(vehicle_Fare) +  parseFloat(fare_markup);
                var total = total1.toFixed(2);
                $('#total_fare_markup').val(total);
            });
        }
    });
    
    function fare_markup_type_change(id){
        var ids = $('#fare_markup_type'+id+'').find('option:selected').attr('value');
        $('#fare_mrk'+id+'').text(ids);
    }
    
    function fare_markup_change(id){
        var ids = $('#fare_markup_type'+id+'').find('option:selected').attr('value');
        if(ids == '%')
        {
            var fare_markup =  $('#fare_markup'+id+'').val();
            var vehicle_Fare =  $('#vehicle_Fare'+id+'').val();
            var total1 = (vehicle_Fare * fare_markup/100) + parseFloat(vehicle_Fare);
            var total = total1.toFixed(2);
            $('#total_fare_markup'+id+'').val(total);
        }
        else
        {
            var fare_markup =  $('#fare_markup'+id+'').val();
            var vehicle_Fare =  $('#vehicle_Fare'+id+'').val();
            var total1 =  parseFloat(vehicle_Fare) +  parseFloat(fare_markup);
            var total = total1.toFixed(2);
            $('#total_fare_markup'+id+'').val(total);
        }
    }
</script>
@stop



