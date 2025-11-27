@extends('template/frontend/userdashboard/layout/default')
@section('content')


@if(session()->has('message'))
<div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show" role="alert">
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    <strong>{{session('message')}} </strong>
</div>


        
    @endif
   <div>



<div class="tab-content__pane" id="password">
                        <div class="">
                            <!-- BEGIN: Horizontal Bar Chart -->
                            <div class="">
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h2 class="font-medium text-base mr-auto">
                                            Markup Edit
                                        </h2>
                                    </div>
                                    <div class="card-body">
<form class="ps-3 pe-3" action="{{URL::to('super_admin/markup/update',[$markup->id])}}"  method="POST">
                 @csrf
       


                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="emailaddress" class="form-label">Select Services</label>
                        <select class="form-control" name="services_type" id="services_type">
                            <option value="">Select Services Type</option>
                            <option <?php if($markup->services_type == 'hotels'){ echo 'selected'; } ?> value="hotels">Hotels</option>
                            <option <?php if($markup->services_type == 'flight'){ echo 'selected'; } ?> value="flight">Flights</option>
                           
                        </select>
                            </div>
                            
                            <div class="col-md-6" id="slc_provider" style="display:none;">
                                <label for="emailaddress" class="form-label">Select Provider</label>
                        <select class="form-control" name="provider">
                            <option>Select Provider</option>
                            <option <?php if($markup->provider == 'Travellanda'){ echo 'selected'; } ?> value="Travellanda">Travellanda</option>
                            <option <?php if($markup->provider == 'Hotelbeds'){ echo 'selected'; } ?> value="Hotelbeds">Hotelbeds</option>
                            <option <?php if($markup->provider == 'TBO'){ echo 'selected'; } ?> value="TBO">TBO</option>
                            <option <?php if($markup->provider == 'Ratehawk'){ echo 'selected'; } ?> value="Ratehawk">Ratehawk</option>
                            <option <?php if($markup->provider == 'CustomHotel'){ echo 'selected'; } ?> value="CustomHotel">Custom Hotel</option>
                            <option <?php if($markup->provider == 'All'){ echo 'selected'; } ?> value="All">All</option>
                        </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="emailaddress" class="form-label">Select Customer</label>
                        <select class="form-control" name="customer_id">
                            <option>Select Customer</option>
                            <?php
                            foreach($customer as $data)
                            {
                            ?>
                            <option <?php if($markup->customer_id == $data->id ){ echo 'selected'; } ?> value="{{$data->id}}">{{$data->name ?? ''}} {{$data->lname ?? ''}}</option>
                            <?php
                            }
                            ?>
                        </select>
                            </div>
                            
                            
                           
                        </div>
                       
                    </div>
                    
                   
                     <div class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="emailaddress" class="form-label">Select Markup Type</label>
                        <select class="form-control" name="markup_type">
                            <option>Select Markup Type</option>
                            <option <?php if($markup->markup_type == 'Percentage'){ echo 'selected'; } ?> value="Percentage">Percentage</option>
                            <option <?php if($markup->markup_type == 'Fixed'){ echo 'selected'; } ?> value="Fixed">Fixed</option>
                           
                        </select>
                            </div>
                            
                            
                            
                            <div class="col-md-6">
                                <label for="emailaddress" class="form-label">Markup</label>
                        <input class="form-control"  type="text" id="markup" name="markup_value" value="{{$markup->markup_value ?? ''}}"> 
                            </div>
                           
                        </div>
                       
                    </div>
                    
                     
                    
                    
                    

                   

            
                    

                    <div class="mb-3">
                        <button name="submit" class="btn btn-primary" type="submit">submit</button>
                    </div>

                </form>

                                   </div>
                                </div>
                            </div>
                            <!-- END: Horizontal Bar Chart -->
                        </div>

                    </div>
   
       
    </div>
    <!-- END: Content -->


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
           $('#percentage').on('change', function() {
                // alert( this.value );
                var v = ( this.value );
                // alert(v);
                $('#temp').val(v);
                });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
           $('#percentage_1').on('change', function() {
                // alert( this.value );
                var v = ( this.value );
                // alert(v);
                $('#temp_1').val(v);
                });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
           $('#percentage_2').on('change', function() {
                // alert( this.value );
                var v = ( this.value );
                // alert(v);
                $('#temp_2').val(v);
                });
        });
    </script>
 <script>
   $(document).ready(function () {
        $("#services_type").change(function(){
            //alert('jcjsj');
            var slc_value = $('option:selected', this).val();
            if(slc_value == 'hotels')
            {
                
               $('#slc_provider').fadeIn();  
            }
            else
            {
                $('#slc_provider').fadeOut();
             
            }
            if(slc_value == '')
            {
                $('#slc_provider').fadeOut();
                
            }

            
        });
   });
   
   
 
   
</script>
@endsection
