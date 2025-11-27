@extends('template/frontend/userdashboard/layout/default')
@section('content')

<?php $currency=Session::get('currency_symbol'); ?>

<div class="dashboard-content">
    <h4 style="color:#a30000">Add New Destination</h4>
        <form action="{{URL::to('update_destination')}}/{{ $tranfer_destination->id }}" method="post" enctype="multipart/form-data">
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
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation" style="width: 280px;text-align: center;">
                            <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">Manage Destinations</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                            <div class="row">
                                
                                <div class="col-md-6" style="padding: 15px;">
                                    <label for="">Select Pickup City</label>
                                    <input value="{{ $tranfer_destination->pickup_City }}" type="text" id="pickup_City" name="pickup_City" class="form-control pickup_City">
                                </div>
                                
                                <div class="col-md-6" style="padding: 15px;">
                                    <label for="">Select Dropof City</label>
                                    <input value="{{ $tranfer_destination->dropof_City }}" type="text" id="dropof_City" name="dropof_City" class="form-control dropof_City">
                                </div>
                                
                                @if(isset($vehicle_details))
                                <?php $i = 1; ?>
                                    @foreach($vehicle_details as $vehicle_detail)
                                        <div class="col-md-3" style="padding: 15px;">
                                            <label for="">Select Category Vehicle</label>
                                            <select name="vehicle_Name[]" id="" class="form-control">
                                                <option value="{{ $vehicle_detail->vehicle_Name }}">{{ $vehicle_detail->vehicle_Name }}</option>
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
                                                <input value="{{ $vehicle_detail->vehicle_Fare }}" type="text" id="vehicle_Fare{{ $i }}" name="vehicle_Fare[]" class="form-control" >
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-2" style="padding: 15px;">
                                            <label for="">Markup Type</label>
                                            <select name="fare_markup_type[]" onchange="fare_markup_type_change({{ $i }})" id="fare_markup_type{{ $i }}" class="form-control">
                                                <option value="{{ $vehicle_detail->fare_markup_type }}">{{ $vehicle_detail->fare_markup_type }}</option>
                                                <option value="%">Percentage</option>
                                                <option value="<?php echo $currency; ?>">Fixed Amount</option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-2" style="padding: 15px;">
                                            <label for="">Markup</label>
                                            <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                <input type="text" class="form-control" onkeyup="fare_markup_change({{ $i }})" value="{{ $vehicle_detail->fare_markup }}" id="fare_markup{{ $i }}" name="fare_markup[]">
                                                <span class="input-group-btn input-group-append">
                                                    <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="fare_mrk{{ $i }}">{{ $vehicle_detail->fare_markup_type }}</div></button>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-2" style="padding: 15px;">
                                            <label for="">Total</label>
                                            <div class="input-group">
                                                <input type="text" value="{{ $vehicle_detail->total_fare_markup }}" id="total_fare_markup{{ $i }}" name="total_fare_markup[]" class="form-control">
                                                    <span class="input-group-btn input-group-append">
                                                        <a class="btn btn-primary bootstrap-touchspin-up">
                                                           <?php echo $currency; ?>
                                                        </a>
                                                    </span>
                                            </div>
                                        </div>
                                    <?php $i++; ?>
                                    @endforeach
                                @endif
                                    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="row">
                <div class="col-md-12 text-right mt-3">
                    <button type="submit" class="btn btn-primary">Update Destination</button>
                </div>
            </div>
        </form>
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



