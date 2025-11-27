@extends('template/frontend/userdashboard/layout/default')
 @section('content')
 
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    
                    <h4 class="header-title">Create Customer Subcription</h4>
                    <p class="text-muted font-14"></p>
                    
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <form action="{{ url('super_admin/submit_subcription') }}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                                @csrf
                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" style="background-color:#d4edda;" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif
                                
                                <div class="row">
                               
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="validationCustom01">First name</label>
                                            <input type="text" class="form-control" id="validationCustom01" name="name" placeholder="First name" value="" required>
                                            <div class="valid-feedback">
                                                Looks good!
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="validationCustom02">Last name</label>
                                            <input type="text" class="form-control" id="validationCustom02" name="lname" placeholder="Last name" value="" required>
                                            <div class="valid-feedback">
                                                Looks good!
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="validationCustom02">Email</label>
                                            <input type="email" class="form-control" id="validationCustom02" name="email" placeholder="E mail" value="" required>
                                            <div class="valid-feedback">
                                                Looks good!
                                            </div>
                                        </div>
                                    </div>
                                <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="validationCustom02">Password</label>
                                            <input type="password" class="form-control" id="validationCustom02" name="password" placeholder="password" value="" required>
                                            <div class="valid-feedback">
                                                Looks good!
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="validationCustom02">Contact No</label>
                                            <input type="text" class="form-control" id="validationCustom02" name="phone" placeholder="Contact Number" value="" required>
                                            <div class="valid-feedback">
                                                Looks good!
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="validationCustom02">Company Name</label>
                                            <input type="text" class="form-control" id="validationCustom02" name="company_name" placeholder="Website Address" value="" required>
                                            <div class="valid-feedback">
                                                Looks good!
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label class="form-label" for="validationCustom02">Website Address</label>
                                            <input type="text" class="form-control" id="validationCustom02" name="webiste_Address" placeholder="Website Address" value="" required>
                                            <div class="valid-feedback">
                                                Looks good!
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="validationCustom02">Country</label>
                                            <select name="country" onchange="selectCities()" id="property_country" class="form-control" >
                                            @foreach($all_countries as $country_res)
                                            <option value="{{ $country_res['id'] }}">{{ $country_res['name'] }}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label"  for="validationCustom02">City</label>
                                            <select name="city" class="form-control" id="property_city">
                                                <option value="">Select One</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="validationCustom02">Company Logo</label>
                                            <input type="file" name="img" class="form-control" id="validationCustom02" placeholder="Last name" value="" required>
                                            <div class="valid-feedback">
                                                Looks good!
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="validationCustom02">Booking Tag</label>
                                            <input type="text" name="hotel_Booking_Tag" class="form-control" id="validationCustom02" placeholder="Booking Tag" required>
                                            <div class="valid-feedback">
                                                Looks good!
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="invalidCheck" required>
                                        <label class="form-check-label form-label" for="invalidCheck">Agree to terms
                                            and conditions</label>
                                        <div class="invalid-feedback">
                                            You must agree before submitting.
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <button class="btn btn-primary" type="submit">Submit form</button>
                                </div>
                                    
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                  console.log(result);
                  $('#property_city').html(result);
                },
                error:function(error){
                    console.log(error);
                }
            });
        }
        var imgDiv = 1;
        function addMoreSliderImage(){
        var data = `<div class="row" id="slider_row${imgDiv}">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label"  for="validationCustom02">Slider Image</label>
                                <input type="file" name="sliderImage[]" class="form-control" id="validationCustom02" placeholder="Last name" value="" required>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="mb-3">
                                <label class="form-label"  for="validationCustom02">Caption</label>
                                <input type="text" name="sliderCapt[]" class="form-control" id="validationCustom02" placeholder="Last name" value="" required>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="mt-3">
                                <button type="button" onclick="removeSliderImage(${imgDiv})"  class="btn btn-danger">X</button>
                            </div>
                        </div>
                    </div>`;
                    $('#sliderDiv').append(data);
                    imgDiv++
            console.log('Slider Call now');
        }
    
        function removeSliderImage(id){
            $('#slider_row'+id+'').remove();
            console.log('id is '+id);
        }
    </script>
    
@stop
