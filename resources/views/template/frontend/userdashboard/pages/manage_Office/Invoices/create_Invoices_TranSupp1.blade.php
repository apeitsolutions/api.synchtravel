@extends('template/frontend/userdashboard/layout/default')
@section('content')
<?php $currency=Session::get('currency_symbol'); $account_No=Session::get('account_No'); //dd($account_No); ?>

<div id="flights-Airline-Name" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
<div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Add Flight Airline Name</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form class="ps-3 pe-3" action="#">
                    <div class="mb-3">
                        <label for="username" class="form-label">Flight Airline Name</label>
                        <input class="form-control" type="other_Airline_Name" id="other_Airline_Name" name="other_Airline_Name" placeholder="">
                    </div>

                    <div class="mb-3 text-center">
                        <button class="btn btn-primary" id="submitForm_Airline_Name" type="submit">Submit</button>
                    </div>
                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal --> 
 
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
                        <label for="username" class="form-label">Visa </label>
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

<div id="hotel-name-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
<div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Add Hotel Name</h4>
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
                        <label for="username" class="form-label">Hotel Name</label>
                        <input class="form-control" type="other_Hotel_Name" id="other_Hotel_Name" name="other_Hotel_Name" placeholder="">
                    </div>

                   

                  

                    

                    <div class="mb-3 text-center">
                        <button class="btn btn-primary" id="submitForm_hotel_name" type="submit">Submit</button>
                    </div>

                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="hotel-type-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
<div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Add Hotel Type</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form class="ps-3 pe-3" action="#">
                    <div class="mb-3">
                        <label for="username" class="form-label">Hotel Type</label>
                        <input class="form-control" type="other_Hotel_Type" id="other_Hotel_Type" name="other_Hotel_Type" placeholder="">
                    </div>
                    <div class="mb-3 text-center">
                        <button class="btn btn-primary" id="submitForm_hotel_type" type="submit">Submit</button>
                    </div>

                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="pickup-location-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
<div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Add Pickup Location</h4>
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
                        <label for="username" class="form-label">Pickup Location</label>
                        <input class="form-control" type="pickup_location" id="pickup_location" name="pickup_location" placeholder="">
                    </div>

                   

                  

                    

                    <div class="mb-3 text-center">
                        <button class="btn btn-primary" id="submitForm_PUL" type="submit">Submit</button>
                    </div>

                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="dropof-location-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
<div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Add Dropof Location</h4>
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
                        <label for="username" class="form-label">Dropof Location</label>
                        <input class="form-control" type="dropof_location" id="dropof_location" name="dropof_location" placeholder="">
                    </div>

                   

                  

                    

                    <div class="mb-3 text-center">
                        <button class="btn btn-primary" id="submitForm_DOL" type="submit">Submit</button>
                    </div>

                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="add-Categories-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Add Categories</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="https://client.synchronousdigital.com/super_admin/submit_categories" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="label" class="form-label">Title</label>
                        <input class="form-control" type="text" id="title" name="title" placeholder="">
                    </div>
                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug</label>
                        <input class="form-control" type="text" id="slug" name="slug" placeholder="">
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input class="form-control" type="file" id="" name="image" placeholder="">
                    </div>
                    <div class="mb-3">
                        <label for="placement" class="form-label">Placement</label>
                        <input class="form-control" type="text" id="placement" name="placement" placeholder="">
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" type="text" id="" name="description" placeholder=""></textarea>
                    </div>
                    

                    <div class="mb-3 text-center">
                        <button class="btn btn-primary" id="" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="add-facilities-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Add Facilities</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="https://client.synchronousdigital.com/super_admin/submit_attributes" method="post" enctype="multipart/form-data">
                @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label">Facilities Name</label>
                        <input class="form-control" type="title" id="title" name="title" placeholder="">
                    </div>
                    <div class="mb-3 text-center">
                        <button class="btn btn-primary" id="" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
 
<div class="card">
    <div class="card-body">
        <h4 class="header-title">Create Invoice</h4>
        @if($errors->any())
        <h4>{{$errors->first()}}</h4>
        @endif
        <!--<div class="tab-content">-->
            <form action="{{URL::to('add_Invoices')}}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf
                <!--<div class="tab-pane show active" id="justified-tabs-preview">-->
                <div id="progressbarwizard">
                    <!--<ul class="nav nav-pills bg-nav-pills nav-justified mb-3">-->
                    <ul class="nav nav-pills nav-justified form-wizard-header mb-3" style="margin-right: 1px;margin-left: 1px;">
                        <li class="nav-item">
                            <a href="#home1" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                              <i class="mdi mdi-home-variant d-md-none d-block"></i>
                                <span class="d-none d-md-block">Invoice Details</span>
                            </a>
                        </li>
                        
                        <li class="nav-item accomodation_tab d-none">
                            <!--<a href="#profile1" data-bs-toggle="tab" aria-expanded="true" class="nav-link rounded-0">-->
                            <a href="#profile1" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                              <i class="uil-bed d-md-none d-block"></i>
                                <span class="d-none d-md-block">Accomodation</span>
                            </a>
                        </li>
                        
                        <li class="nav-item flights_tab">
                            <!--<a href="#settings1" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0">-->
                            <a href="#settings1" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                          <i class="uil-plane-fly d-md-none d-block"></i>
                                <span class="d-none d-md-block">Flights Details</span>
                            </a>
                        </li>
                    
                        <li class="nav-item visa_tab d-none">
                            <!--<a href="#visa_details" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0">-->
                            <a href="#visa_details" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                              <i class="mdi mdi-passport d-md-none d-block"></i>
                                <span class="d-none d-md-block">Visa Details</span>
                            </a>
                        </li>
                        
                        <li class="nav-item transportation_tab d-none">
                            <!--<a href="#trans_details_1" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0">-->
                            <a href="#trans_details_1" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                              <i class="uil-bus d-md-none d-block"></i>
                                <span class="d-none d-md-block">Transportation</span>
                            </a>
                        </li>
                         
                        <li class="nav-item d-none">
                            <!--<a href="#Itinerary_details" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0">-->
                            <a href="#Itinerary_details" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                <i class="uil-globe d-md-none d-block"></i>
                                <span class="d-none d-md-block">Itinerary</span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <!--<a href="#Extras_details" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0">-->
                            <a href="#Extras_details" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                              <i class="uil-moneybag d-md-none d-block"></i>
                                <span class="d-none d-md-block">Costing</span>
                            </a>
                        </li>
                    </ul>
                    
                    <div class="tab-content">

                        <div class="tab-pane  show active" id="home1">
                            <div class="row">
                                
                                <div class="col-xl-3">
                                    <div class="mb-3">
                                        <div id="tooltip-container">
                                            <label for="simpleinput" class="form-label simpleinput">Services</label>
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                                data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Enter the package name">
                                            </i>
                                        </div>
                                        <!--<input type="text" id="simpleinput" name="title" class="form-control titleP" >-->
                                       <select class="form-control  external_packages" onchange="selectServices()" data-toggle="select2" multiple="multiple" id="select_services" name="services[]">
                                            <!--<option value="1">All Services</option>-->
                                            <!--<option value="accomodation_tab">Accomodation</option>-->
                                            <option value="flights_tab">Flights Details</option>
                                            <!--<option value="visa_tab">Visa Details</option>-->
                                            <!--<option value="transportation_tab">Transportation</option>-->
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-xl-2">
                                    <div class="mb-3">
                                        <div id="tooltip-container">
                                            <label for="simpleinput" class="form-label simpleinput">Select Agent</label>
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                                data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Select the Agent Name">
                                            </i>
                                        </div>
                                       <select class="form-control" id="agent_Name" name="agent_Name">
                                           <option value="">Choose...</option>
                                            @if(isset($Agents_detail) && $Agents_detail !== null && $Agents_detail !== '')
                                                @foreach($Agents_detail as $Agents_details)
                                                    <option attr-Id="{{ $Agents_details->id }}" value="{{ $Agents_details->agent_Name }}">{{ $Agents_details->agent_Name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        
                                        <input type="hidden" name="agent_Id" id="agent_Id" readonly>
                                        
                                        
                                    </div>
                                </div>
                                
                                <div class="col-xl-2">
                                    <div class="mb-3">
                                        <div id="tooltip-container">
                                            <label for="simpleinput" class="form-label">Currency Symbol</label>
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                                data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Currency Symbol will be auto-selected for your account">
                                            </i>
                                        </div>
                                            <input readonly value="<?php echo $currency; ?>" name="currency_symbol" class="form-control currency_symbol">
                                    </div>
                                </div>
                                
                                <div class="col-xl-2">
                                    <div class="mb-3">
                                        <div id="tooltip-container">
                                            <label for="simpleinput" class="form-label no_of_pax_days">No Of Pax</label>
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                                data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="No Of Pax for this package">
                                            </i>
                                        </div>
                                        <!--<input type="text" id="no_of_pax_days" name="no_of_pax_days" class="form-control no_of_pax_days">-->
                                        <input type="number" name="no_of_pax_days" value="1" min="1" class="form-control no_of_pax_days pax_number" id="no_of_pax_days" required>
                                        <input type="number" id="no_of_pax_prev" hidden value="1">
                                        <div class="invalid-feedback">
                                            This Field is Required
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-xl-3">
                                    <div class="mb-3">
                                        <div id="tooltip-container">
                                            <label for="simpleinput" class="form-label">Currency Conversion</label>
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                                data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Currency Convirsion">
                                            </i>
                                        </div>
                                        <select class="form-control CC_id_store" name="currency_conversion" id="currency_conversion1">
                                            <option value="0">Select Currency Conversion</option>
                                            @foreach($mange_currencies as $mange_currencies)
                                                <option attr_id="{{$mange_currencies->id}}" attr_conversion_type="{{$mange_currencies->conversion_type}}" value="{{$mange_currencies->purchase_currency}} TO {{$mange_currencies->sale_currency}}">{{$mange_currencies->purchase_currency}} TO  {{$mange_currencies->sale_currency}}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" id="conversion_type_Id"  name="conversion_type_Id" >
                                        <input type="hidden" id="select_exchange_type" value=""> 
                                    </div>
                                </div>
                                
                                <div class="col-xl-12">
                                    <div class="mb-3">
                                        <h6>Lead Passenger Detials</h6>
                                        <div class="row" style="border: 1px solid #ebebeb;padding: 1rem;border-radius: 6px;">
                                            <div class="col-xl-6">
                                                <div class="mb-3">
                                                    <label for="simpleinput" class="form-label no_of_pax_days">First Name</label>
                                                    <input type="text" name="lead_fname" class="form-control" required>
                                                </div>
                                            </div>
                                            
                                            <div class="col-xl-6">
                                                <div class="mb-3">
                                                    <label for="simpleinput" class="form-label no_of_pax_days">Last Name</label>
                                                    <input type="text" name="lead_lname" class="form-control" required>
                                                </div>
                                            </div>
                                            
                                            <div class="col-xl-6">
                                                <div class="mb-3">
                                                    <label for="simpleinput" class="form-label no_of_pax_days">Email</label>
                                                    <input type="text" name="email" class="form-control" required>
                                                </div>
                                            </div>
                                            
                                            <div class="col-xl-6">
                                                <div class="mb-3">
                                                    <label for="simpleinput" class="form-label no_of_pax_days">Contact Number</label>
                                                    <input type="text" name="mobile" class="form-control" required>
                                                </div>
                                            </div>
                                            <!-- <div class="col-xl-4">-->
                                            <!--    <div class="mb-3">-->
                                            <!--        <label for="simpleinput" class="form-label no_of_pax_days">Date Of Birth</label>-->
                                            <!--        <input type="date" name="data_of_birth" class="form-control" required>-->
                                            <!--    </div>-->
                                            <!--</div>-->
                                            <!-- <div class="col-xl-4">-->
                                            <!--    <div class="mb-3">-->
                                            <!--        <label for="simpleinput" class="form-label no_of_pax_days">Passport No</label>-->
                                            <!--        <input type="text" name="passport_no" class="form-control" required>-->
                                            <!--    </div>-->
                                            <!--</div>-->
                                            <!-- <div class="col-xl-4">-->
                                            <!--    <div class="mb-3">-->
                                            <!--        <label for="simpleinput" class="form-label no_of_pax_days">Passport Expiry Date</label>-->
                                            <!--        <input type="date" name="passport_expiry_date" class="form-control" required>-->
                                            <!--    </div>-->
                                            <!--</div>-->
                                            
                                                    
                                            <div class="col-xl-4">
                                                <div class="mb-3">
                                                    <label for="simpleinput" class="form-label no_of_pax_days">Select Nationality</label>
                                                     <select type="text" class="form-control select2 " name="nationality"  data-placeholder="Choose ...">
                                                        @foreach($all_countries as $country_res)
                                                            <option value="{{$country_res->id}}" id="categoriesPV" required>{{$country_res->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-xl-2" >
                                                <div class="mb-3">
                                                     <p style="margin-top: 2.2rem;">Gender</p>
                                                </div>
                                            </div>
                                            
                                               
                                            <div class="col-2">
                                              <div class="form-check" style="margin-top: 2.2rem;">
                                              <input class="form-check-input" type="radio" name="gender" value="male"  id="flexRadioDefault3">
                                              <label class="form-check-label" for="flexRadioDefault3">
                                                Male
                                              </label>
                                            </div>
                                            
                                            </div>
                                            <div class="col-2" style="margin-top: 2.2rem;">
                                                <div class="form-check">
                                              <input class="form-check-input" type="radio" name="gender" value="female"  id="flexRadioDefault4">
                                              <label class="form-check-label" for="flexRadioDefault4">
                                                Female
                                              </label>
                                            </div>
                                            
                                            </div>
                                            
                                           
                                            
                                        </div>
                                        
                                        
                                        <div id="other_passengers">
                                            
                                        
                                        </div>
                                        
                                    </div>
                                </div>
                                
                                <div class="col-xl-12 d-none">
                                    <div class="mb-3">
                                        <div id="tooltip-container">
                                            <label for="simpleinput" class="form-label">Content</label>
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                                data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Additional Information of Package">
                                            </i>
                                        </div>
                                        <!--<textarea name="content" id="" class="contentP summernote" cols="142" rows="10"></textarea>-->
                                        <textarea name="content" class="contentP" cols="135" rows="5"></textarea>
                                    </div>
                                </div>
                                
                                <div class="col-xl-4">
                                    <div class="mb-3">
                                        <div id="tooltip-container">
                                            <label for="simpleinput" class="form-label start_date">Start Date</label>
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                                data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Select start date of package">
                                            </i>
                                        </div>
                                        
                                        <input type="date" name="start_date" class="form-control start_date" id="start_date" required placeholder="yyyy/mm/dd">
                                        <div class="invalid-feedback">
                                            This Field is Required
                                        </div>
                                    </div>
                                 </div>
                                
                                <div class="col-xl-4">
                                    <div class="mb-3">
                                        
                                        <div id="tooltip-container">
                                            <label for="simpleinput" class="form-label end_date">End Date</label>
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                                data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Select end date of package">
                                            </i>
                                        </div>
                                        <input type="date" name="end_date" class="form-control end_date" id="end_date" required placeholder="yyyy/mm/dd">
                                        <div class="invalid-feedback">
                                            This Field is Required
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-xl-4">
                                     <div class="mb-3">
                                        <!--<div id="tooltip-container">-->
                                            <label for="simpleinput" class="form-label no_of_Nights_Other">No Of Nights</label>
                                            <!--<i class="dripicons-information" style="font-size: 17px;" id="title_Icon"-->
                                                <!--data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="No Of Pax Info">-->
                                            <!--</i>-->
                                        <!--</div>-->
                                      
                                      <input readonly type="text" name="time_duration" id="duration" class="form-control time_duration" >
                                     </div>
                                  </div>
                                 
                                <div class="col-xl-4 d-none" >
                                        <div class="mb-3">
                                            <div>
                                                <div id="tooltip-container">
                                                    <label for="categoriesPV">Categories</label>
                                                    <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                                        data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Select package Category">
                                                    </i>
                                                
                                                
                                                    <span title="Add Categories" class="input-group-btn input-group-append" style="float: right;margin-bottom:10px">
                                                        <button class="btn btn-primary bootstrap-touchspin-up" data-bs-toggle="modal" data-bs-target="#add-Categories-modal" type="button">+</button>
                                                    </span>
                                            
                                                    <select type="text" class="form-control select2 categories" name="categories"  data-placeholder="Choose ...">
                                                        @foreach($categories as $categories)
                                                            <option value="{{$categories->id}}" id="categoriesPV" required>{{$categories->title}}</option>
                                                            <div class="invalid-feedback">
                                                                This Field is Required
                                                            </div>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                
                                <div class="col-xl-4 d-none">
                                    <div class="mb-3">
                                        <div>
                                            <div id="tooltip-container">
                                                <label for="simpleinput" class="form-label">Tour Featured </label>
                                                <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                                    data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Display your package on Home Page">
                                                </i>
                                            </div>
                                            <select name="tour_feature" id="" class="form-control tour_feature" style="margin-top: 18px">
                                                <option value="">Select Featured</option>
                                                <option value="0">Enable featured</option>
                                                <option value="1">Disable featured</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-xl-4 d-none">
                                    <div class="mb-3">
                                        <div>
                                            <!--<div id="tooltip-container">-->
                                                <label for="simpleinput" class="form-label">Default State</label>
                                                <!--<i class="dripicons-information" style="font-size: 17px;" id="title_Icon"-->
                                                    <!--data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="No Of Pax Info">-->
                                                <!--</i>-->
                                            <!--</div>-->
                                            <select name="defalut_state" id="" class="form-control defalut_state" style="margin-top: 18px">
                                                <option value="">Select Default State</option>
                                                <option value="0">Always available</option>
                                                <option value="1">Only available on specific dates</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                  
                                <div class="col-xl-6 d-none">
                                   <div class="mb-3">
                                        <div id="tooltip-container">
                                            <label for="simpleinput" class="form-label">Banner Image</label>
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                                data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Upload your detail page image for Package (Image size should be 'Width:1600px' 'Height:300px')">
                                            </i>
                                        </div>
                                        <input type="file" id="simpleinput" name="tour_featured_image" class="form-control tour_featured_imageP">
                                    </div>
                                </div>
                                
                                <div class="col-xl-6 d-none">
                                    <div class="mb-3">
                                        <div id="tooltip-container">
                                            <label for="simpleinput" class="form-label">Featured Image</label>
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                                data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Upload your main image for Package">
                                            </i>
                                        </div>
                                        <input type="file" id="simpleinput" name="tour_banner_image" class="form-control tour_banner_imageP">
                                    </div>
                                </div>
                                
                                <div class="col-xl-3">
                                    <div class="mb-3">
                                        <!--<div id="tooltip-container">-->
                                            <label for="simpleinput" class="form-label">Author</label>
                                            <!--<i class="dripicons-information" style="font-size: 17px;" id="title_Icon"-->
                                                <!--data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="No Of Pax Info">-->
                                            <!--</i>-->
                                        <!--</div>-->
                                      
                                      <select name="tour_author" id="" class="form-control tour_author">
                                      <option value="">Select Author</option>
                                          <option value="admin">admin</option>
                                          <option value="admin1">admin1</option>
                                      </select>
                                  </div>
                                  </div>
                                  
                                <div class="col-xl-3 more_Div_Acc_All" style="display:none">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Option Date</label>
                                        <input type="date" id="option_date" value="" name="option_date" class="form-control ">
                                    </div>
                                </div>
                                  
                                <div class="col-xl-3 more_Div_Acc_All" style="display:none">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Reservation Number</label>
                                        <input type="text" id="reservation_number" value="" name="reservation_number" class="form-control ">
                                    </div>
                                </div>
                                  
                                <div class="col-xl-3 more_Div_Acc_All" style="display:none">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Hotel Reservation Number</label>
                                        <input type="text" id="hotel_reservation_number" value="" name="hotel_reservation_number" class="form-control ">
                                    </div>
                                </div>
                                  
                                <div class="col-xl-8 d-none">
                                    <div class="mb-3">
                                        <div id="tooltip-container">
                                            <label for="simpleinput" class="form-label">Gallery Images</label>
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                                data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Upload at least 10 images">
                                            </i>
                                        </div>
                                        <div>
                                            <?php $img_c = 1; ?>
                                	        <input type="file" id="" name="gallery_images[]" class="form-control gallery_imagesP{{ $img_c }}" onchange="validate1({{ $img_c }})">
                                	        <span class="file_error{{ $img_c }}"></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="more_img d-none"></div>
                                        
                                <div class="mt-2 d-none" style="" id="">
                                    <a href="javascript:;" id="more_images" class="btn btn-info" style="float: right;">Add Gallery Images</a>
                                </div>
                                    
                            </div>
                            <!--<a id="save_Package" type="submit" class="btn btn-primary" name="submit">Save Package Deatils</a>-->    
                        </div>

                        <div class="tab-pane" id="profile1 d-none">
                            <div class="row">
                                
                                <div class="col-xl-12">
                                    <div class="row">                                                 
                                        <div class="col-xl-1">
                                           <div class="mb-3">
                                                <input type="checkbox" id="destination" data-switch="bool"/>
                                                <label for="destination" data-on-label="On" data-off-label="Off"></label>
                                            </div>
                                        </div>
                                        <div class="col-xl-3">
                                            Destination
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Select Country & City"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row" style="display:none;" id="select_destination">
                                    <div class="col-xl-6">
                                            <label for="">COUNTRY</label>
                                        <select name="destination_country" onchange="selectCities()" id="property_country" class="form-control">
                                           @foreach($all_countries as $country_res)
                                           <option  value="{{ $country_res->id }}">{{ $country_res->name}}</option>
                                           @endforeach
                                        </select>
                                    </div>
                                    
                                    <!--<div class="col-xl-6" style="">-->
                                    <!--    <label for="">City</label>-->
                                    <!--    <select name="destination_city[]" id="property_city" class="select2 form-control select2-multiple city_slc property_city_length" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">-->
                                    <!--    </select>-->
                                    <!--</div>-->
                                    
                                    <div class="col-xl-6">
                                        <label for="">How Many Hotel You Want To Add?</label>
                                        <input type="number" name="city_Count" id="city_No" class="form-control" placeholder="Select...">
                                    </div>
                                    
                                    <input type="hidden" name="tour_location_city" id="tour_location_city" value=""/>
                                    <input type="hidden" name="packages_get_city" id="packages_get_city" value=""/>
                                    <div id="append_destination"></div>
                                </div>
                                
                            </div>
                            
                            <div class="row">
                                <div class="col-xl-12 mt-2">
                                    <div class="mb-3">
                                        <div class="row">
                                            <!--<div class="col-xl-1">-->
                                            <!--    <input type="checkbox" id="accomodation" data-switch="bool"/>-->
                                            <!--    <label for="accomodation" data-on-label="On" data-off-label="Off"></label>-->
                                            <!--</div>-->
                                        <div class="col-xl-3">
                                            <div class="mt-2">
                                                <a href="javascript:;" id="add_hotel_accomodation" class="btn btn-info"> 
                                                    + Add Hotels 
                                                </a>
                                            </div>
                                        </div>
                                        <!--<div class="col-xl-3">-->
                                        <!--    Accomodation-->
                                        <!--    <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Select Accomodation"></i>-->
                                        <!--</div>-->
                                    </div>
                                </div>
                            </div>
                            
                            <div id="append_accomodation"></div>
                            <div id="append_accomodation1"></div>
                            
                            </div>                                                      
                            <!--<a id="save_Accomodation" class="btn btn-primary" name="submit">Save Accomodation</a>-->   
                        </div>

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
                            <div class="row" id="select_flights_inc">
                                <div class="col-2">
                                        <select class="form-control select_supplier" name="supplier" style="height: 3.4rem;" >
                                           <option value="">Select supplier</option>
                                            @if(isset($supplier))
                                                @foreach($supplier as $all_supplier)
                                                    <option value="{{$all_supplier->id}}">{{$all_supplier->companyname}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-10">
                                        <table class="table table-centered w-100 dt-responsive nowrap">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="text-align: center;" class="rute_table">ROUTE</th>
                                                    <th style="text-align: center;" class="date_table">DATES</th>
                                                    <th style="text-align: center;" class="seat_table">SEATS</th>
                                                    <th style="text-align: center;" class="reservation_table">REQUIED SEATS</th>
                                                    <th style="text-align: center;" class="reservation_table"></th>
                                                </tr>
                                            </thead>
                                            <tbody style="text-align: center;" class="all_tr_append"></tbody>
                                        </table>
                                         <input type="hidden" id="fightjkjk" name="flight_id"  value="">
                                    </div>
                                     <div class="tr_btn_old_place">
                                        <a href="javascript:void(0);" style="color: black;width: 6rem;background-color: #727cf5 !important;"  class="btn btn-info occupy_btn"><b>Occupy</b></a>
                                    </div>
                                    <div class="hide_direct">
                                         <div class="row">
                                             <div class="row">
                                                 
                                                 
                                                 <div class="col-xl-4" style="padding-left: 24px;">
                                    <label for="">Adults</label>
                                    <div class="input-group">
                                        
                                        <input type="number" id="adult_number" onchange="PriceCalculatorFunction()" name="adultpax"  class="form-control">
                                    </div>
                                </div> 
                                <div class="col-xl-4" style="padding-left: 24px;">
                                    <label for="">Child</label>
                                    <div class="input-group">
                                       
                                        <input type="number" id="child_number" onchange="PriceCalculatorFunction()" name="childpax" class="form-control">
                                    </div>
                                </div>
                                <div class="col-xl-4" style="padding-left: 24px;">
                                    <label for="">Infant</label>
                                    <div class="input-group">
                                        
                                        <input type="number" id="infant_number" onchange="PriceCalculatorFunction()" name="infantpax" class="form-control">
                                    </div>
                                </div>
                                                 
                                                
                                             </div>
                                             <div class="col-xl-6 mt-2" style="padding-left: 24px;">
                                    <label for="">Flight Type</label>
                                    <select name="flight_type" id="flights_type" class="form-control flight_direct_type">
                                        <option attr="select" selected>Select Flight Type</option>
                                         <option attr="Direct" value="Direct">Direct</option>
                                          <option attr="Indirect" value="Indirect">Indirect</option>
                                    </select>
                                </div>

                                <div class="col-xl-3" id="flights_type_connected"></div>
    </div>
                                <div class="col-xl-3"></div>
                                
                                <div class="container Flight_section">
                                    <h3 style="padding: 12px">Departure Details : </h3>
                                    <div class="row" style="padding: 12px">
                                        <div class="col-xl-4">
                                            <label for="">Departure Airport</label>
                                            <input name="departure_airport_code" id="departure_airport_code" class="form-control" autocomplete="off" placeholder="Enter Departure Location">
                                        </div>
                                        <div class="col-xl-1" style="margin-top: 25px;text-align: center;">
                                            <label for=""></label>
                                            <span id="Change_Location" class='bi bi-arrow-left-right' style="font-size: 23px;"></span>
                                        </div>
                                        <div class="col-xl-4">
                                            <label for="">Arrival Airport</label>
                                            <input name="arrival_airport_code" id="arrival_airport_code" class="form-control" autocomplete="off" placeholder="Enter Arrival Location">
                                            
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="">Airline Name</label>
                                            <input type="text" id="other_Airline_Name2" name="other_Airline_Name2" class="form-control other_airline_Name1">
                                            <!--<div class="input-group">-->
                                            <!--    <select type="text" id="other_Airline_Name2" name="other_Airline_Name2" class="form-control other_airline_Name1">-->
                                                
                                            <!--    </select>-->
                                            <!--    <span title="Add Pickup Location" class="input-group-btn input-group-append">-->
                                            <!--        <button class="btn btn-primary bootstrap-touchspin-up" data-bs-toggle="modal" data-bs-target="#flights-Airline-Name" type="button">+</button>-->
                                            <!--    </span>-->
                                            <!--</div>-->
                                        </div>
                                        <div class="col-xl-3" style="margin-top: 15px;">
                                            <label for="">Class Type</label>
                                            <select  name="departure_Flight_Type" id="departure_Flight_Type" class="form-control">
                                                <option value="">Select Flight Type</option>
                                                <option value="Bussiness">Bussiness</option>
                                                <option value="Economy">Economy</option>
                                                <option value="Standard">Standard</option>
                                            </select>
                                        </div>
                                        <div class="col-xl-3" style="margin-top: 15px;">
                                            <label for="">Flight Number</label>
                                            <input type="text" id="simpleinput" name="departure_flight_number" class="form-control departure_flight_number" placeholder="Enter Flight Number">
                                        </div>
                                        <div class="col-xl-3" style="margin-top: 15px;">
                                            <label for="">Departure Date and Time</label>
                                            <input type="datetime-local" id="simpleinput" name="departure_time" class="form-control departure_time1" value="" >
                                        </div>
                                        <div class="col-xl-3" style="margin-top: 15px;">
                                            <label for="">Arrival Date and Time</label>
                                            <input type="datetime-local" id="simpleinput" name="arrival_time" class="form-control arrival_time1" value="" >
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="container" style="display:none" id="total_Time_Div">
                                    <div class="row" style="margin-left: 320px">
                                        <div class="col-sm-3">
                                            <h3 style="width: 125px;margin-top: 25px;float:right" id="stop_No">Direct :</h3>
                                        </div>
                                        <div class="col-sm-3">
                                             <label for="">Duration</label>
                                            <input type="text" id="total_Time" name="total_Time" class="form-control total_Time1" style="width: 170px;" value="">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="container Flight_section_append"></div>
                                
                                <div class="container return_Flight_section">
                                    <h3 style="padding: 12px">Return Details : </h3>
                                    <div class="row" style="padding: 12px">
                                        <div class="col-xl-4">
                                            <label for="">Departure Airport</label>
                                            <input name="return_departure_airport_code" id="return_departure_airport_code" class="form-control" autocomplete="off" placeholder="Enter Return Location">
                                        </div>
                                        <div class="col-xl-1" style="margin-top: 25px;text-align: center;">
                                            <label for=""></label>
                                            <span id="return_Change_Location" class='bi bi-arrow-left-right' style="font-size: 23px;"></span>
                                        </div>
                                        <div class="col-xl-4">
                                            <label for="">Arrival Airport</label>
                                            <input name="return_arrival_airport_code" id="return_arrival_airport_code" class="form-control" autocomplete="off" placeholder="Enter Return Location">
                                            
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="">Airline Name</label>
                                            <input type="text" id="return_other_Airline_Name2" name="return_other_Airline_Name2" class="form-control other_airline_Name1">
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
                                            <select  name="return_departure_Flight_Type" id="return_departure_Flight_Type" class="form-control">
                                                <option value="">Select Flight Type</option>
                                                <option value="Bussiness">Bussiness</option>
                                                <option value="Economy">Economy</option>
                                                <option value="Standard">Standard</option>
                                            </select>
                                        </div>
                                        <div class="col-xl-3" style="margin-top: 15px;">
                                            <label for="">Flight Number</label>
                                            <input type="text" id="simpleinput" name="return_departure_flight_number" class="form-control return_departure_flight_number" placeholder="Enter Flight Number">
                                        </div>
                                        <div class="col-xl-3" style="margin-top: 15px;">
                                            <label for="">Departure Date and Time</label>
                                            <input type="datetime-local" id="simpleinput" name="return_departure_time" class="form-control return_departure_time1" value="" >
                                        </div>
                                        <div class="col-xl-3" style="margin-top: 15px;">
                                            <label for="">Arrival Date and Time</label>
                                            <input type="datetime-local" id="simpleinput" name="return_arrival_time" class="form-control return_arrival_time1" value="" >
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="container" style="display:none" id="return_total_Time_Div">
                                    <div class="row" style="margin-left: 320px">
                                        <div class="col-sm-3">
                                            <h3 style="width: 162px;margin-top: 25px;float:right" id="return_stop_No">Return Direct :</h3>
                                        </div>
                                        <div class="col-sm-3">
                                             <label for="">Duration</label>
                                            <input type="text" id="return_total_Time" name="return_total_Time" class="form-control return_total_Time1" style="width: 170px;" value="">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="container return_Flight_section_append"></div>
                                <div class="row">
                                    
                                    
                                     <div class="col-xl-4" style="padding-left: 24px;">
                                    <label for="">Price Per Person</label>
                                    <div class="input-group">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up">
                                               <?php echo $currency; ?>
                                            </a>
                                        </span>
                                        <input type="text" id="flights_per_person_price" name="flights_per_person_price" class="form-control">
                                    </div>
                                </div> 
                                <div class="col-xl-4" style="padding-left: 24px;">
                                    <label for="">Price Per child</label>
                                    <div class="input-group">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up">
                                               <?php echo $currency; ?>
                                            </a>
                                        </span>
                                        <input type="text" id="flights_per_child_price" name="flights_per_child_price" class="form-control">
                                    </div>
                                </div>
                                <div class="col-xl-4" style="padding-left: 24px;">
                                    <label for="">Price Per infant</label>
                                    <div class="input-group">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up">
                                               <?php echo $currency; ?>
                                            </a>
                                        </span>
                                        <input type="text" id="flights_per_infant_price" name="flights_per_infant_price" class="form-control">
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
                                
                                <div id="append_flights"></div>
                                    </div>
                                
                                
                            </div>
                            <!-- END Flifhts -->    
                            <!--<a id="save_Flights" class="btn btn-primary" name="submit">Save Flights</a>-->
                           
                        </div>
                                                
                        <div class="tab-pane" id="visa_details">
                            <div class="row">                                                  
                                <div class="col-xl-12 mt-2">
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-xl-1">
                                                <input type="checkbox" id="visa_inc" data-switch="bool"/>
                                                <label for="visa_inc" data-on-label="On" data-off-label="Off"></label>
                                            </div>
                                            <div class="col-xl-3">
                                                Visa Included ?
                                                <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Add visa price & requirement"></i>
                                            </div>
                                         </div>
                                    </div>
                                </div>
  
                                <div class="row mt-1 mb-3" style="display:none" id="select_visa_inc">
                                  
                                    <div class="col-xl-4" style="padding: 10px;">
                                        <label for="">Visa Type</label>
                                        <div class="input-group" id="timepicker-input-group1">
                                            <select name="visa_type" id="visa_type" class="form-control other_type"></select>
                                            <span title="Add Visa Type" class="input-group-btn input-group-append"><button class="btn btn-primary bootstrap-touchspin-up" data-bs-toggle="modal" data-bs-target="#signup-modal" type="button">+</button></span>
                                        </div>
                                     </div>
                                    
                                    <div class="col-xl-4" style="padding: 10px;" id="visa_fee_OLD">
                                        <label for="">Visa Fee</label>
                                        <div class="input-group">
                                            <span class="input-group-btn input-group-append">
                                                <a class="btn btn-primary bootstrap-touchspin-up" id="visa_C_S">
                                                    <?php echo $currency; ?>
                                                </a>
                                            </span>
                                            <input type="text" id="visa_fee" name="visa_fee" class="form-control">
                                        </div>
                                    </div>
                                    <input type="hidden" id="total_visa_markup_value" name="total_visa_markup_value" class="form-control">
                                    <div class="col-xl-4" style="padding: 10px;">
                                        <label for="">Visa Pax</label>
                                        <input type="text" id="visa_Pax" name="visa_Pax" class="form-control">
                                    </div>
                                    
                                    <div id="invoice_exchage_rate_visa_Div" class="row"></div>
                                    
                                    <div id="add_more_visa_Pax_Div"></div>
                                    
                                    <div class="col-xl-12" style="padding: 10px;text-align: right;">
                                        <button type="button" id="add_more_visa_Pax" class="btn btn-primary">Add more</button>
                                    </div>
                                    
                                    <div class="col-xl-12" style="padding: 10px;">
                                        <label for="">Visa Requirements:</label>
                                        <textarea name="visa_rules_regulations" class="form-control" cols="5" rows="5"></textarea>
                                     </div>
                                     
                                    <div class="col-xl-12" style="padding: 10px;">
                                        <label for="">Image:</label>
                                        <input type="file" id="" name="visa_image" class="form-control">
                                     </div>
                                </div>
                            </div>
                            <!--<a id="save_Visa" class="btn btn-primary" name="submit">Save Visa</a>-->
                        </div> 
                                                    
                        <div class="tab-pane" id="trans_details_1">
                            <div class="row">                                       
                                <div class="tab-pane" id="trans_details_1">
                                    
                                    <div class="col-xl-12 mt-2">
                                        <div class="mb-3">
                                            <div class="row">
                                                <div class="col-xl-1">
                                                    <input type="checkbox" id="transportation" data-switch="bool"/>
                                                    <label for="transportation" data-on-label="On" data-off-label="Off"></label>
                                                </div>
                                                <div class="col-xl-3">
                                                    Transportation
                                                    <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Add your transportation details"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                     
                                    <div class="row" style="display:none;border:1px solid #ced4da;padding: 20px 20px 20px 20px;" id="select_transportation">
                                        <h3>Transportation Details :</h3>
                                        <div class="col-xl-3" style="padding: 10px;">
                                            <label for="">Pick-up Location</label>
                                            <input type="text" id="transportation_pick_up_location" name="transportation_pick_up_location" class="form-control pickup_location" value="">
                                            <!--<div class="input-group">-->
                                                <!--<select type="text" id="transportation_pick_up_location" name="transportation_pick_up_location" class="form-control pickup_location">-->
                                                
                                                <!--</select>-->
                                            <!--    <span title="Add Pickup Location" class="input-group-btn input-group-append">-->
                                            <!--        <button class="btn btn-primary bootstrap-touchspin-up" data-bs-toggle="modal" data-bs-target="#pickup-location-modal" type="button">+</button>-->
                                            <!--    </span>-->
                                            <!--</div>-->
                                        </div>
                                        <div class="col-xl-3" style="padding: 10px;">
                                        <label for="">Drop-off Location</label>
                                        <input type="text" id="transportation_drop_off_location" name="transportation_drop_off_location" class="form-control dropof_location">
                                            <!--<div class="input-group">-->
                                            <!--    <select type="text" id="transportation_drop_off_location" name="transportation_drop_off_location" class="form-control dropof_location">-->
                                                
                                            <!--    </select>-->
                                            <!--    <span title="Add DropOf Location" class="input-group-btn input-group-append">-->
                                            <!--        <button class="btn btn-primary bootstrap-touchspin-up" data-bs-toggle="modal" data-bs-target="#dropof-location-modal" type="button">+</button>-->
                                            <!--    </span>-->
                                            <!--</div>-->
                                        </div>
                                        <div class="col-xl-3" style="padding: 10px;">
                                        <label for="">Pick-up Date & Time</label>
                                        <input type="datetime-local" id="transportation_pick_up_date" name="transportation_pick_up_date" class="form-control">
                                        </div>
                                        <div class="col-xl-3" style="padding: 10px;">
                                        <label for="">Drop-of Date & Time</label>
                                        <input type="datetime-local" id="transportation_drop_of_date" name="transportation_drop_of_date" class="form-control">
                                        </div>
                                        <div class="col-xl-3" style="display:none" id="transportation_Time_Div">
                                        <label for="">Estimate Time</label>
                                        <input type="text" id="transportation_total_Time" name="transportation_total_Time" class="form-control transportation_total_Time1" readonly style="padding: 10px;" value="">
                                        </div>
                                        <div class="col-xl-3" style="padding: 10px;">
                                        <label for="">Select Trip Type</label>
                                        <select name="transportation_trip_type" id="slect_trip" class="form-control"  data-placeholder="Choose ...">
                                    
                                    <option value="One-Way">One-Way</option>
                                    <option value="Return">Return</option>
                                    <option value="All_Round">All Round</option>
                                    </select>
                                    </div>
                                        
                                        <div class="col-xl-3" style="padding: 10px;">
                                            <label for="">Vehicle Type</label>
                                            <select name="transportation_vehicle_type" id="" class="form-control"  data-placeholder="Choose ...">
                                                <option value="">Choose ...</option>
                                                <option value="Bus">Bus</option>
                                                <option value="Coach">Coach</option>
                                                <option value="Vain">Vain</option>
                                                <option value="Car">Car</option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-xl-3" style="padding: 10px;">
                                    <label for="">No Of Vehicle</label>
                                            <input type="text" id="transportation_no_of_vehicle" name="transportation_no_of_vehicle" class="form-control">
                                    </div>
                                        <div class="col-xl-3" style="padding: 10px;">
                                            <label for="">Price Per Vehicle</label>
                                            <div class="input-group">
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up">
                                                                   <?php echo $currency; ?>
                                                                </a>
                                                            </span>
                                                <input type="text" id="transportation_price_per_vehicle" name="transportation_price_per_vehicle" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-xl-3" style="padding: 10px;">
                                    <label for="">Total Vehicle Price</label>
                                        <div class="input-group">
                                                        <span class="input-group-btn input-group-append">
                                                            <a class="btn btn-primary bootstrap-touchspin-up">
                                                               <?php echo $currency; ?>
                                                            </a>
                                                        </span>
                                            <input type="text" id="transportation_vehicle_total_price" name="transportation_vehicle_total_price" class="form-control">
                                        </div>
                                    </div>
                                        <div class="col-xl-3" style="padding: 10px;">
                                    <label for="">Price Per Person</label>
                                        <div class="input-group">
                                                    <span class="input-group-btn input-group-append">
                                                        <a class="btn btn-primary bootstrap-touchspin-up">
                                                           <?php echo $currency; ?>
                                                        </a>
                                                    </span>
                                            <input type="text" id="transportation_price_per_person" name="transportation_price_per_person" class="form-control">
                                        </div>
                                    
                                    </div>
                                
                                        <!--Return Transportation-->
                                        <div class="row" id="select_return" style="display:none;padding: 10px;">
                                            <h3>Return Details :</h3>
                                            <div class="col-xl-3" style="padding: 10px;">
                                                <label for="">Pick-up Location</label>
                                                <input type="text" id="return_transportation_pick_up_location" name="return_transportation_pick_up_location" class="form-control pickup_location">
                                                <!--<div class="input-group">-->
                                                    <!--<select type="text" id="return_transportation_pick_up_location" name="return_transportation_pick_up_location" class="form-control pickup_location">-->
                                                    
                                                    <!--</select>-->
                                                <!--    <span title="Add Pickup Location" class="input-group-btn input-group-append">-->
                                                <!--        <button class="btn btn-primary bootstrap-touchspin-up" data-bs-toggle="modal" data-bs-target="#pickup-location-modal" type="button">+</button>-->
                                                <!--    </span>-->
                                                <!--</div>-->
                                            </div>
                                            <div class="col-xl-3" style="padding: 10px;">
                                            <label for="">Drop-off Location</label>
                                            <input type="text" id="return_transportation_drop_off_location" name="return_transportation_drop_off_location" class="form-control dropof_location">
                                                <!--<div class="input-group">-->
                                                    <!--<select type="text" id="return_transportation_drop_off_location" name="return_transportation_drop_off_location" class="form-control dropof_location">-->
                                                    
                                                    <!--</select>-->
                                                <!--    <span title="Add DropOf Location" class="input-group-btn input-group-append">-->
                                                <!--        <button class="btn btn-primary bootstrap-touchspin-up" data-bs-toggle="modal" data-bs-target="#dropof-location-modal" type="button">+</button>-->
                                                <!--    </span>-->
                                                <!--</div>-->
                                            </div>
                                            <div class="col-xl-3" style="padding: 10px;">
                                            <label for="">Pick-up Date & Time</label>
                                            <input type="datetime-local" id="return_transportation_pick_up_date" name="return_transportation_pick_up_date" class="form-control">
                                            </div>
                                            <div class="col-xl-3" style="padding: 10px;">
                                            <label for="">Drop-of Date & Time</label>
                                            <input type="datetime-local" id="return_transportation_drop_of_date" name="return_transportation_drop_of_date" class="form-control">
                                            </div>
                                            <div class="col-xl-3" style="display:none" id="return_transportation_Time_Div">
                                            <label for="" style="width: 205px;">Estimate Time Return</label>
                                            <input type="text" id="return_transportation_total_Time" name="return_transportation_total_Time" class="form-control return_transportation_total_Time1" readonly style="padding: 10px;" value="">
                                            </div>
                                            
                                            <div class="col-xl-3" style="padding: 10px;">
                                                <label for="">Vehicle Type</label>
                                                <select name="return_transportation_vehicle_type" id="" class="form-control"  data-placeholder="Choose ...">
                                                <option value="">Choose ...</option>
                                                <option value="Bus">Bus</option>
                                                <option value="Coach">Coach</option>
                                                <option value="Vain">Vain</option>
                                                <option value="Car">Car</option></select>
                                            </div>
                                            
                                            <div class="col-xl-3" style="padding: 10px;">
                                                <label for="">No Of Vehicle</label>
                                                <input type="text" id="return_transportation_no_of_vehicle" name="return_transportation_no_of_vehicle" class="form-control">
                                            </div>
                                            <div class="col-xl-3" style="padding: 10px;">
                                                <label for="">Price Per Vehicle</label>
                                                <div class="input-group">
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up">
                                                                   <?php echo $currency; ?>
                                                                </a>
                                                            </span>
                                                    <input type="text" id="return_transportation_price_per_vehicle" name="return_transportation_price_per_vehicle" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-xl-3" style="padding: 10px;">
                                                <label for="">Total Vehicle Price</label>
                                                <div class="input-group">
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up">
                                                                   <?php echo $currency; ?>
                                                                </a>
                                                            </span>
                                                    <input type="text" id="return_transportation_vehicle_total_price" name="return_transportation_vehicle_total_price" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-xl-3" style="padding: 10px;">
                                                <label for="">Price Per Person</label>
                                                    <div class="input-group">
                                                                <span class="input-group-btn input-group-append">
                                                                    <a class="btn btn-primary bootstrap-touchspin-up">
                                                                       <?php echo $currency; ?>
                                                                    </a>
                                                                </span>
                                                        <input type="text" id="return_transportation_price_per_person" name="return_transportation_price_per_person" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        <div class="col-xl-12" style="padding: 10px;">
                                    <label for="">Image</label>
                                    <input type="file" id="" name="transportation_image" class="form-control">
                                    </div>
                                        <div id="append_transportation">
                                    </div>
                                        <div class="mt-2" style="display:none;" id="add_more_destination">
                                    <a href="javascript:;" id="more_transportation" class="btn btn-info" style="float: right;">Add More Destinations </a>
                                    </div>
                                
                                    </div>  
                                    
                                </div>
                            </div> 
                            <!--<a id="save_Transportation" class="btn btn-primary" name="submit">Save Transportation Details</a>-->
                        </div> 
                                                
                        <div class="tab-pane" id="Itinerary_details">
                            <div class="row">
                                
                                <div class="col-xl-12">
                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label">What's Included In Package?</label>
                                        <textarea name="whats_included" class="form-control summernote" id="" cols="10" rows="0"></textarea>
                                        <!--<textarea name="whats_included" class="form-control" cols="10" rows="5"></textarea>-->
                                    </div>
                                </div>
                              
                                <div class="col-xl-12">
                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label">What's Excluded In Package?</label>
                                        <textarea name="whats_excluded" class="form-control summernote" id="" cols="10" rows="0"></textarea>
                                        <!--<textarea name="whats_excluded" class="form-control" cols="10" rows="5"></textarea>-->
                                    </div>
                                </div>
                              
                                <div class="col-xl-12">
                                    <label for="">Cancellation Policy</label>
                                    <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Cancellation Policy"></i>
                                    <textarea name="cancellation_policy" class="form-control" cols="5" rows="5"></textarea>
                                </div>
                                
                                <div class="col-xl-12" style="margin-top:15px">
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-xl-1">
                                                <input type="checkbox" id="Itinerary" data-switch="bool"/>
                                                <label for="Itinerary" data-on-label="On" data-off-label="Off"></label>
                                            </div>
                                            <div class="col-xl-3">
                                                Itinerary
                                                <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Add day to day Itinerary for this package"></i>
                                            </div>
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
                                            <label for="simpleinput" class="form-label">Short Description</label>
                                            <input type="text" id="simpleinput" name="Itinerary_city" class="form-control">
                                        </div>
                                    </div>
                                  
                                    <div class="col-xl-12">
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Content</label>
                                            <textarea name="Itinerary_content" class="form-control" id="" cols="10" rows="10"></textarea>
                                        </div>
                                    </div>
                                  
                                    <div class="" id="append_data"></div>
                                
                                    <div class="mb-3">
                                        <div class="btn btn-info" id="click_more_Itinerary">Add More</div>
                                    </div>
                                </div>

                                <div class="col-xl-12">
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-xl-1">
                                                <input type="checkbox" id="extra_price" data-switch="bool"/>
                                                <label for="extra_price" data-on-label="On" data-off-label="Off"></label>
                                            </div>
                                            <div class="col-xl-3 additional_Services">Additional Services
                                                <i class="dripicons-information" style="font-size: 17px;" id="additional_Services" data-bs-toggle="tooltip" data-bs-placement="right" title="Additional Services"></i>
                                            </div> 
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
                                            <div class="input-group">
                                                <span class="input-group-btn input-group-append">
                                                    <a class="btn btn-primary bootstrap-touchspin-up">
                                                       <?php echo $currency; ?>
                                                    </a>
                                                </span>
                                                <input type="text" id="simpleinput" name="extra_price_price" class="form-control">
                                            </div>
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
                            
                                    <div class="" id="append_data_extra_price"></div>
                            
                                    <div class="mb-3">
                                        <div class="btn btn-info" id="click_more_extra_price">Add More</div>
                                    </div>
                                    
                                </div>
                                <!-- extra price ended -->

                                <div class="col-xl-12">
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-xl-1">
                                                <input type="checkbox" id="faq" data-switch="bool"/>
                                                <label for="faq" data-on-label="On" data-off-label="Off"></label>
                                            </div>
                                            <div class="col-xl-3">
                                                FAQ
                                                <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Add common questions & answers for customers"></i>
                                            </div>
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
                                    
                                    <div class="" id="append_data_faq"></div>
                                    
                                    <div class="mb-3">
                                        <div class="btn btn-info" id="click_more_faq">Add More</div>   
                                    </div>
                                    
                                </div>
                                <!-- faq ended -->
                                
                            </div> 
                            <!--<a id="save_Itinerary" class="btn btn-primary" name="submit">Save Itinerar Details</a>-->
                        </div> 
                                                    
                        <div class="tab-pane" id="Extras_details">  
                        
                            <div class="row">
                                
                                <div class="col-xl-4" style="text-align:right;">
                                    <span>Markup on total price</span>
                                </div>
                                
                                <input type="hidden" id="markupSwitch" name="markupSwitch" value="single_markup_switch">
                                
                                <div class="col-xl-1">
                                    <input type="checkbox" id="switch2" data-switch="bool"/>
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
                                                <h4 >Flights Cost <b id="flights_departure_code_html"></b></h4> 
                                                <div class="row" id="flights_cost" style="display:none;padding-bottom: 25px">
                                                    
                                                    
                                                  
                                                    
                                                    <input type="hidden" name="acc_hotel_CityName[]">
                                                    <input type="hidden" name="acc_hotel_HotelName[]">
                                                    <input type="hidden" name="acc_hotel_CheckIn[]">
                                                    <input type="hidden" name="acc_hotel_CheckOut[]">
                                                    <input type="hidden" name="acc_hotel_NoOfNights[]">
                                                    <input type="hidden" name="acc_hotel_Quantity[]">
                                                    
                                                    <div class="col-xl-9">
                                                        <input type="hidden" id="flight_Type_Costing" name="markup_Type_Costing[]" value="flight_Type_Costing" class="form-control">
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                        <input value="" type="text" id="flights_departure_code" readonly="" name="hotel_name_markup[]" class="form-control flights_type_code">
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                        <input value="" type="text" id="flights_arrival_code" readonly="" name="room_type[]" class="form-control">
                                                    </div>
                                                    
                                                    <div class="col-xl-2" style="display:none">
                                                        <div class="input-group">
                                                            <input type="text" id="flights_price_per_night" readonly="" name="without_markup_price_single[]" class="form-control">    
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value1"></a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                        <div class="input-group">
                                                            <input type="text" id="flights_prices" readonly="" name="without_markup_price[]" class="form-control flights_per_person_price12345">
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up">
                                                                   <?php echo $currency; ?>
                                                                </a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                        <select name="markup_type[]" id="markup_type" class="form-control">
                                                            <option value="">Markup Type</option>
                                                            <option value="%">Percentage</option>
                                                            <option value="<?php echo $currency; ?>">Fixed Amount</option>
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                        <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                            <input type="text"  class="form-control" id="markup_value" name="markup[]">
                                                            <span class="input-group-btn input-group-append">
                                                                <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="markup_value_markup_mrk">%</div></button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-xl-1" style="display:none">
                                                        <input type="text" id="flights_exchage_rate_per_night" readonly="" name="exchage_rate_single[]" class="form-control">    
                                                    </div>
                                                    
                                                    <div class="col-xl-2" style="display:none">
                                                        <div class="input-group">
                                                            <input type="text" id="flights_exchage_rate_markup_total_per_night" readonly="" name="markup_total_per_night[]" class="form-control">
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                                            </span>
                                                        </div> 
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                        <div class="input-group">
                                                            <input type="text" id="total_markup" name="markup_price[]" class="form-control">
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up">
                                                                   <?php echo $currency; ?>
                                                                </a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                
                                                
                                                <div class="row" style="padding-bottom: 20px">
                                                    
                                                    
                                                  
                                                    
                                                  

                                                    <div class="col-xl-2">
                                                        <label for="">Total Adult</label>
                                                        <input  type="text" id="adult_final_qty"  readonly="" name="adultfinalqty" class="form-control">
                                                    </div>
                                                    
                                                     
                                                    <div class="col-xl-2">
                                                        <label for="">Per Adult Price</label>
                                                        <div class="input-group">
                                                            <input type="text" id="per_adult_price" readonly="" name="adultPerprice" class="form-control flights_per_person_price12345">
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up">
                                                                   <?php echo $currency; ?>
                                                                </a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                        <label for=""></label>
                                                        <select name="markup_type[]" id="markup_type_for_adult" class="form-control">
                                                            <option value="">Markup Type</option>
                                                            <option value="%">Percentage</option>
                                                            <option value="<?php echo $currency; ?>">Fixed Amount</option>
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                        <label for=""></label>
                                                        <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                            <input type="text"  class="form-control" id="markup_value_for_adult" name="markup[]">
                                                            <span class="input-group-btn input-group-append">
                                                                <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="markup_value_markup_mrk_for_adult">%</div></button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                     <input type="hidden"  class="form-control adult_total_with_markup"  name="adult_total_with_markup">
                                                    
                                                    
                                                    
                                                    <div class="col-xl-2">
                                                        <label for=""></label>
                                                        <div class="input-group">
                                                            <input type="text" id="total_markup_for_adult" name="adult_markup_price1" class="form-control">
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up">
                                                                   <?php echo $currency; ?>
                                                                </a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                        <label for=""></label>
                                                        <div class="input-group">
                                                            <input type="text" id="total_adult_markup" name="adult_markup_price" class="form-control">
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up">
                                                                   <?php echo $currency; ?>
                                                                </a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                <div class="row" style="padding-bottom: 20px">
                                                    
                                                    
                                                  
                                                
                                                    
                                                  
                                                    
                                                    <div class="col-xl-2">
                                                            
                                                  <label for="">Total Child</label>
                                                        <input  type="text" id="child_final_qty" readonly="" name="childfinalqty" class="form-control">
                                                    </div>
                                                    
                                                  
                                                   
                                                    <div class="col-xl-2">
                                                         <label for="">Per Child Price</label>
                                                        <div class="input-group">
                                                            <input type="text" id="per_child_price" readonly="" name="childPerprice" class="form-control flights_per_person_price12345">
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up">
                                                                   <?php echo $currency; ?>
                                                                </a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                           <label for=""></label>
                                                        <select name="markup_type[]" id="markup_type_for_child" class="form-control">
                                                            <option value="">Markup Type</option>
                                                            <option value="%">Percentage</option>
                                                            <option value="<?php echo $currency; ?>">Fixed Amount</option>
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                           <label for=""></label>
                                                        <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                            <input type="text"  class="form-control" id="markup_value_for_child" name="markup[]">
                                                            <span class="input-group-btn input-group-append">
                                                                <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="markup_value_markup_mrk">%</div></button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <input type="hidden"  class="form-control child_total_with_markup"  name="child_total_with_markup">
                                                    <div class="col-xl-1" style="display:none">
                                                        <input type="text" id="flights_exchage_rate_per_night" readonly="" name="exchage_rate_single[]" class="form-control">    
                                                    </div>
                                                    
                                                    <div class="col-xl-2" style="display:none">
                                                        <div class="input-group">
                                                            <input type="text" id="flights_exchage_rate_markup_total_per_night" readonly="" name="markup_total_per_night[]" class="form-control">
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                                            </span>
                                                        </div> 
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                        <label for=""></label>
                                                        <div class="input-group">
                                                            <input type="text" id="total_markup_for_child" name="total_markup_for_child" class="form-control">
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up">
                                                                   <?php echo $currency; ?>
                                                                </a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-2">
                                                        <label for=""></label>
                                                        <div class="input-group">
                                                            <input type="text" id="total_child_markup" name="markup_price[]" class="form-control">
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up">
                                                                   <?php echo $currency; ?>
                                                                </a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                <div class="row" style="padding-bottom: 20px">
                                                    
                                                    
                                                  
                                                    
                                                 
                                                    
                                                    <!--<div class="col-xl-9">-->
                                                    <!--    <input type="hidden" id="flight_Type_Costing" name="markup_Type_Costing[]" value="flight_Type_Costing" class="form-control">-->
                                                    <!--</div>-->
                                                    
                                                    <!--<div class="col-xl-2">-->
                                                    <!--    <input  type="text"  readonly="" name="hotel_name_markup[]" class="form-control">-->
                                                    <!--</div>-->
                                                    
                                                    <div class="col-xl-2">
                                                         <label for="">Total Infant</label>
                                                        <input  type="text" id="infant_final_qty" readonly="" name="infantfinalqty" class="form-control">
                                                    </div>
                                                    
                                                  
                                                  
                                                    <div class="col-xl-2">
                                                          <label for="">Per Infant Price</label>
                                                        <div class="input-group">
                                                            <input type="text" id="per_infant_price" readonly="" name="infantPerprice" class="form-control">
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up">
                                                                   <?php echo $currency; ?>
                                                                </a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                          <label for=""></label>
                                                        <select name="markup_type[]" id="markup_type_for_infant" class="form-control">
                                                            <option value="">Markup Type</option>
                                                            <option value="%">Percentage</option>
                                                            <option value="<?php echo $currency; ?>">Fixed Amount</option>
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                         <label for=""></label>
                                                        <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                            <input type="text"  class="form-control" id="markup_value_for_infant" name="markup[]">
                                                            <span class="input-group-btn input-group-append">
                                                                <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="markup_value_markup_mrk">%</div></button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                     <input type="hidden"  class="form-control infant_total_with_markup"  name="infant_total_with_markup">
                                                    <div class="col-xl-1" style="display:none">
                                                        <input type="text" id="flights_exchage_rate_per_night" readonly="" name="exchage_rate_single[]" class="form-control">    
                                                    </div>
                                                    
                                                    <div class="col-xl-2" style="display:none">
                                                        <div class="input-group">
                                                            <input type="text" id="flights_exchage_rate_markup_total_per_night" readonly="" name="markup_total_per_night[]" class="form-control">
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                                            </span>
                                                        </div> 
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                         <label for=""></label>
                                                        <div class="input-group">
                                                            <input type="text" id="total_markup_for_infant" name="total_markup_for_infant" class="form-control">
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up">
                                                                   <?php echo $currency; ?>
                                                                </a>
                                                            </span>
                                                        </div>
                                                    </div> 
                                                    <div class="col-xl-2">
                                                         <label for=""></label>
                                                        <div class="input-group">
                                                            <input type="text" id="total_infant_markup" name="markup_price[]" class="form-control">
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up">
                                                                   <?php echo $currency; ?>
                                                                </a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                
                                                <div id="append_accomodation_data_cost"></div>
                                                
                                                <hr style="width: 98%;margin-left: 13px;margin-top: 10px;margin-bottom: 10px;color: black;"></hr>
                                                
                                                <div id="append_accomodation_data_cost1"></div>
                                                
                                                <div class="row" id="transportation_cost" style="display:none;">
                                                    
                                                    <div class="col-xl-3">
                                                         <h4 class="" id="">Transportation Cost</h4>
                                                    </div>
                                                    
                                                    <input type="hidden" name="acc_hotel_CityName[]">
                                                    <input type="hidden" name="acc_hotel_HotelName[]">
                                                    <input type="hidden" name="acc_hotel_CheckIn[]">
                                                    <input type="hidden" name="acc_hotel_CheckOut[]">
                                                    <input type="hidden" name="acc_hotel_NoOfNights[]">
                                                    <input type="hidden" name="acc_hotel_Quantity[]">
                                                    
                                                    <div class="col-xl-9">
                                                        <input type="hidden" id="transportation_Type_Costing" name="markup_Type_Costing[]" value="transportation_Type_Costing" class="form-control">  
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                        <input type="text" id="transportation_pick_up_location_select" readonly="" name="hotel_name_markup[]" class="form-control">
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                        <input type="text" id="transportation_drop_off_location_select" readonly="" name="room_type[]" class="form-control">
                                                    </div>
                                                    
                                                    <div class="col-xl-2" style="display:none">
                                                        <div class="input-group">
                                                            <input type="text" id="transportation_price_per_night" readonly="" name="without_markup_price_single[]" class="form-control">    
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value1"></a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                        <div class="input-group">
                                                            <input type="text" id="transportation_price_per_person_select" readonly="" name="without_markup_price[]" class="form-control">
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up">
                                                                   <?php echo $currency; ?>
                                                                </a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                        <select name="markup_type[]" id="transportation_markup_type" class="form-control">
                                                            <option value="">Markup Type</option>
                                                            <option value="%">Percentage</option>
                                                            <option value="<?php echo $currency; ?>">Fixed Amount</option>
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                        <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                            <input type="text"  class="form-control" id="transportation_markup" name="markup[]">
                                                            <span class="input-group-btn input-group-append">
                                                                <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="transportation_markup_mrk">%</div></button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-xl-1" style="display:none">
                                                        <input type="text" id="transportation_exchage_rate_per_night" readonly="" name="exchage_rate_single[]" class="form-control">    
                                                    </div>
                                                    
                                                    <div class="col-xl-2" style="display:none">
                                                        <div class="input-group">
                                                            <input type="text" id="transportation_exchage_rate_markup_total_per_night" readonly="" name="markup_total_per_night[]" class="form-control">
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                                            </span>
                                                        </div> 
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                        <div class="input-group">
                                                            <input type="text" id="transportation_markup_total" name="markup_price[]" class="form-control">
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up">
                                                                   <?php echo $currency; ?>
                                                                </a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                
                                                <div class="row" id="visa_cost" style="display:none;">
                                                    
                                                    <div class="col-xl-3">
                                                        <h4 class="" id="">Visa Cost</h4>
                                                    </div>
                                                    
                                                    <input type="hidden" name="acc_hotel_CityName[]">
                                                    <input type="hidden" name="acc_hotel_HotelName[]">
                                                    <input type="hidden" name="acc_hotel_CheckIn[]">
                                                    <input type="hidden" name="acc_hotel_CheckOut[]">
                                                    <input type="hidden" name="acc_hotel_NoOfNights[]">
                                                    <input type="hidden" name="acc_hotel_Quantity[]">
                                                    
                                                    <div class="col-xl-9">
                                                        <input type="hidden" id="visa_Type_Costing" name="markup_Type_Costing[]" value="visa_Type_Costing" class="form-control">
                                                    </div>
                                                    
                                                    <div class="col-xl-3">
                                                        <input readonly type="text" id="visa_type_select" name="hotel_name_markup[]" class="form-control">
                                                    </div> 
                                                    
                                                    <div class="col-xl-2" style="display:none">
                                                        <div class="input-group">
                                                            <input type="text" id="visa_price_per_night" readonly="" name="without_markup_price_single[]" class="form-control">    
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value1"></a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-xl-3">
                                                        <div class="input-group">
                                                            <input readonly type="text" id="visa_price_select" name="without_markup_price[]" class="form-control">
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up">
                                                                   <?php echo $currency; ?>
                                                                </a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                        <select name="markup_type[]" id="visa_markup_type" class="form-control">
                                                            <option value="">Markup Type</option>
                                                            <option value="%">Percentage</option>
                                                            <option value="<?php echo $currency; ?>">Fixed Amount</option>
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                        <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                            <input type="text" class="form-control" id="visa_markup" name="markup[]">
                                                            <span class="input-group-btn input-group-append">
                                                                <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="visa_mrk">%</div></button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-xl-1" style="display:none">
                                                        <input type="text" id="visa_exchage_rate_per_night" readonly="" name="exchage_rate_single[]" class="form-control">    
                                                    </div>
                                                    
                                                    <div class="col-xl-2" style="display:none">
                                                        <div class="input-group">
                                                            <input type="text" id="visa_exchage_rate_markup_total_per_night" readonly="" name="markup_total_per_night[]" class="form-control">
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                                            </span>
                                                        </div> 
                                                    </div>
                                                    
                                                    <div class="col-xl-2">
                                                        <div class="input-group">
                                                            <input type="text" id="total_visa_markup" name="markup_price[]" class="form-control">
                                                            <span class="input-group-btn input-group-append">
                                                                <a class="btn btn-primary bootstrap-touchspin-up">
                                                                   <?php echo $currency; ?>
                                                                </a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                
                                                <div id="more_visa_cost_div"></div>
                                                    
                                            </div>    
                                        </div>
                                            
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xl-12" style="display:none;" id="all_services_markup">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="modal-title" id="standard-modalLabel">Markup on total price</h4>
                                            <div class="card-body">
                                               <div class="row">
                                                   
                                                    <div class="col-xl-6">
                                                        <div class="mb-3">
                                                            <label for="all_markup_type" class="form-label">Markup Type</label>
                                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Markup type"></i>
                                                             <select class="form-control" id="all_markup_type" name="all_markup_type" >
                                                                <option>Select Markup Type</option>
                                                                <option value="%">Percentage</option>
                                                                <option value="<?php echo $currency; ?>">Number</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                
                                                    <div class="col-xl-6">
                                                        <div class="mb-3">
                                                            <label for="all_markup_add" class="form-label">All Markup</label>
                                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="All Markup"></i>
                                                            <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                <input type="text"  class="form-control" id="all_markup_add" name="all_markup_add">
                                                                <span class="input-group-btn input-group-append">
                                                                    <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="select_mrk">%</div></button>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                
                                               </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            
                            <div id="accomodation_price_hide" class="row mt-2">
                                
                                <div class="col-xl-4">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Quad Cost Price</label>
                                        <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Quad Cost Price"></i>
                                        <div class="input-group">
                                            <input class="form-control" id="quad_cost_price" name="quad_cost_price" />
                                            <span class="input-group-btn input-group-append">
                                                <a class="btn btn-primary bootstrap-touchspin-up">
                                                   <?php echo $currency; ?>
                                                </a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="col-xl-4">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Triple Cost Price</label>
                                        <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Triple Cost Price"></i>
                                        <div class="input-group">
                                            <input class="form-control" id="triple_cost_price" name="triple_cost_price" />
                                            <span class="input-group-btn input-group-append">
                                                <a class="btn btn-primary bootstrap-touchspin-up">
                                                    <?php echo $currency; ?>
                                                </a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="col-xl-4">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Double Cost Price</label>
                                        <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Double Cost Price"></i>
                                        <div class="input-group">
                                            <input class="form-control" id="double_cost_price" name="double_cost_price" />
                                            <span class="input-group-btn input-group-append">
                                                <a class="btn btn-primary bootstrap-touchspin-up">
                                                    <?php echo $currency; ?>
                                                </a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            
                            </div>
                        
                            <div class="row" id="sale_pr">
                                
                                <div class="col-xl-4">
                                    <div class="mb-3">
                                        <label for="" class="form-label quad_grand_total_amount">Quad Sale Price</label>
                                        <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Quad Sale Price"></i>
                                        
                                        <div class="input-group">
                                            <!--<input class="form-control" id="quad_grand_total_amount" name="quad_grand_total_amount" />-->
                                            <input type="text" name="quad_grand_total_amount_single" class="form-control" id="quad_grand_total_amount">
                                            
                                                    <span class="input-group-btn input-group-append">
                                                        <a class="btn btn-primary bootstrap-touchspin-up">
                                                           <?php echo $currency; ?>
                                                        </a>
                                                    </span>
                                            <div class="invalid-feedback">
                                                This Field is Required
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-xl-4">
                                    <div class="mb-3">
                                        <label for="triple_grand_total_amount" class="form-label">Triple Sale Price</label>
                                        <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Triple Sale Price"></i>
                                        <div class="input-group">
                                            <!--<input class="form-control" id="triple_grand_total_amount" name="triple_grand_total_amount" />-->
                                            <input type="text" name="triple_grand_total_amount_single" class="form-control" id="triple_grand_total_amount">
                                            
                                                    <span class="input-group-btn input-group-append">
                                                        <a class="btn btn-primary bootstrap-touchspin-up">
                                                           <?php echo $currency; ?>
                                                        </a>
                                                    </span>
                                            <div class="invalid-feedback">
                                                This Field is Required
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-xl-4">
                                    <div class="mb-3">
                                        <label for="double_grand_total_amount" class="form-label">Double Sale Price</label>
                                        <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Double Sale Price"></i>
                                        <div class="input-group">
                                            <!--<input class="form-control" id="double_grand_total_amount" name="double_grand_total_amount" />-->
                                            <input type="text" name="double_grand_total_amount_single" class="form-control" id="double_grand_total_amount">
                                            
                                                    <span class="input-group-btn input-group-append">
                                                        <a class="btn btn-primary bootstrap-touchspin-up">
                                                           <?php echo $currency; ?>
                                                        </a>
                                                    </span>
                                            <div class="invalid-feedback">
                                                This Field is Required
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <div class="row" id="markup_seprate_services" style="display:none;">
                                    <div class="col-xl-4">
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label quad_markup">Quad Sale Price</label>
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Quad Sale Price"></i>
                                            <input class="form-control" id="quad_markup" name="quad_grand_total_amount" />
                                            <!--<input type="text" name="quad_grand_total_amount" class="form-control" id="quad_markup" required>-->
                                            <!--<div class="invalid-feedback">-->
                                            <!--    This Field is Required-->
                                            <!--</div>-->
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label triple_markup">Triple Sale Price</label>
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Triple Sale Price"></i>
                                            <input class="form-control" id="triple_markup" name="triple_grand_total_amount" />
                                            <!--<input type="text" name="triple_grand_total_amount" class="form-control" id="triple_markup" required>-->
                                            <!--<div class="invalid-feedback">-->
                                            <!--    This Field is Required-->
                                            <!--</div>-->
                                        </div>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label double_markup">Double Sale Price</label>
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Double Sale Price"></i>
                                            <input class="form-control" id="double_markup" name="double_grand_total_amount" />
                                            <!--<input type="text" name="double_grand_total_amount" class="form-control" id="double_markup" required>-->
                                            <!--<div class="invalid-feedback">-->
                                            <!--    This Field is Required-->
                                            <!--</div>-->
                                        </div>
                                    </div>
                                </div>
                                
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Payment Methods</label>
                                        <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Payment GateWay"></i>
                                        <select class="form-control" id="payment_gateways" name="payment_gateways">
                                            <option value="">Select Payment Gateway</option>
                                            @if(isset($payment_gateways))
                                                @foreach($payment_gateways as $payment_gateways)
                                                    <option value="{{$payment_gateways->payment_gateway_title}}">{{$payment_gateways->payment_gateway_title}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            
                                <div class="col-xl-6">
                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Payment Mode</label>
                                        <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Payment Mode"></i>
                                        <select class="select2 form-control select2-multiple external_packages" data-toggle="select2" multiple="multiple" id="payment_modes" name="payment_modes[]">
                                            <option value="">Select Payment Mode</option>
                                            @if(isset($payment_modes))
                                                @foreach($payment_modes as $payment_modes)
                                                    <option value="{{$payment_modes->payment_mode}}">{{$payment_modes->payment_mode}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
    
                                <div class="col-xl-12">
                                    <label for="">Payment Instructions</label>
                                    <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Payment Instructions"></i>
                                    <textarea name="checkout_message" class="form-control" cols="5" rows="5" value="{{ $account_No }}">{{ $account_No }}</textarea>
                                </div>
                            </div>
      
                            <!--<a id="save_Costing" class="btn btn-primary" name="submit">Save Costing Details</a>-->
                            <button style="float: right;width: 100px;margin-top: 10px;" type="submit" name="submit" class="btn btn-info deletButton">Submit</button>
                        </div>
                        
                        <ul class="list-inline mb-0 wizard" style="margin-top:60px;">
                            <li class="previous list-inline-item">
                                <a href="javascript:void(0);" style="width: 100px;" class="btn btn-info">Previous</a>
                            </li>
                            <li class="next list-inline-item float-end">
                                <a href="javascript:void(0);" style="width: 100px;" class="btn btn-info">Next</a>
                            </li>
                        </ul>
                                                
                    </div>
                    
                </div> 
            </form>
        <!--</div>-->
    </div>
</div>
                 
@endsection

@section('scripts')

<script src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initAutocomplete&language=nl&output=json&key=AIzaSyBmwlQFQKwxZ4D8nRbsWVRTBFUHMO-lUOY" async defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs5.min.js" integrity="sha512-6F1RVfnxCprKJmfulcxxym1Dar5FsT/V2jiEUvABiaEiFWoQ8yHvqRM/Slf0qJKiwin6IDQucjXuolCfCKnaJQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script type="text/javascript">

    $(document).ready(function() {
        $('.hide_direct').hide();
        $('.summernote').summernote({});
        //awaab start
        
        
            
            
         $('.select_supplier').on('change',function(){
             $('.hide_direct').hide();
          $(".occupy_btn").appendTo($(".tr_btn_old_place"));
             $('.all_tr_append').empty();
              $('.rute_append').empty();
              $('.date_append').empty();
              $('.seat_append').empty();
             var supplierId = $(this).val();
        // alert(supplierId);
             $.ajax({    
            type: "POST",
            url: "{{URL::to('')}}/get_suppliers_flights_detail",
            data:{
                "_token"                : "{{ csrf_token() }}",
                'supplierId'            : supplierId
            },
            crossDomain : true,                  
            success: function(data){  
                //  alert("success");
            console.log(data);
             for (var i = 0; i < data.length; i++) {
                 var dealer = $(".select_supplier option:selected").text();
                 console.log(dealer);
                
                 var flights_details_more = [];
                 var direct_flights_details = [];
                //  console.log(data);
                 var inc_id = data[i]['id'];
                 if(data[i]['flight_type'] == "Indirect"){
                    
                    //  console.log(data[i]['flight_type']);
                                                var seats = data[i]['flights_number_of_seat'];
                                                var ruteid = data[i]['id'];
                     
                                                var departur_airport = JSON.parse(data[i]['more_departure_airport_code']);
                                                var arrival_airport = JSON.parse(data[i]['more_arrival_airport_code']);
                                                var airline = JSON.parse(data[i]['more_other_Airline_Name2']);
                                                var flight_type = JSON.parse(data[i]['more_departure_Flight_Type']);
                                                var flight_number = JSON.parse(data[i]['more_departure_flight_number']);
                                                var flight_time = JSON.parse(data[i]['more_departure_time']);
                                                var arrival_time = JSON.parse(data[i]['more_arrival_time']);
                                                var total_time = JSON.parse(data[i]['more_total_Time']);
                                                
                                                var return_more_departure_airport_code = JSON.parse(data[i]['return_more_departure_airport_code']);
                                                var return_more_arrival_airport_code = JSON.parse(data[i]['return_more_arrival_airport_code']);
                                                var return_more_arrival_time = JSON.parse(data[i]['return_more_arrival_time']);
                                                var return_more_departure_time = JSON.parse(data[i]['return_more_departure_time']);
                                            
                                                for (var j = 0; j < departur_airport.length; j++) {
                                                    const data_arr ={
                                                    'seats':seats,
                                                    'ruteid':ruteid,
                                                    'more_departure_airport_code':departur_airport[j],
                                                    'more_arrival_airport_code':arrival_airport[j],
                                                    'more_other_Airline_Name2':airline[j],
                                                    'more_departure_Flight_Type':flight_type[j],
                                                    'more_departure_flight_number':flight_number[j],
                                                    'more_departure_time':flight_time[j],
                                                    'more_arrival_time':arrival_time[j],
                                                    'more_total_Time':total_time[j],
                                                    
                                                    'return_more_departure_airport_code':return_more_departure_airport_code[j],
                                                    'return_more_arrival_airport_code':return_more_arrival_airport_code[j],
                                                    'return_more_arrival_time':return_more_arrival_time[j],
                                                    
                                                    'return_more_departure_time':return_more_departure_time[j],
                                              
                                               
                                                }
                                           
                                                    
                                                  flights_details_more.push(data_arr);
                                                }
                                                // dd($flights);
                     $('#flights_departure_code_html').html("Indirect");
                 }
                 else{
                    //   console.log(data[i]);
                    //  console.log(data[i]['flight_type']);
                     var direct_id = data[i]['id'];
                     var direct_seat = data[i]['flights_number_of_seat'];
                     var direct_dep_airport = data[i]['departure_airport_code'];
                     var direct_dep_time = data[i]['departure_time'];
                     var direct_arr_airport = data[i]['arrival_airport_code'];
                     var direct_arr_time = data[i]['arrival_time'];
                     
                     var direct_return_dep_airport = data[i]['return_departure_airport_code'];
                     var direct_return_dep_time = data[i]['return_departure_time'];
                     var direct_return_arr_airport = data[i]['return_arrival_airport_code'];
                     var direct_return_arr_time = data[i]['return_arrival_time'];
                     $('#flights_departure_code_html').html("Direct");
                     
                 }
                 
                 
                 
                 
                //  console.log(direct_dep_airport);
                  if(data[i]['flight_type'] == "Indirect"){
                  var rutetr =`
                                  <td class="text-center">
                                  <p>${dealer}</p>`
                                        
                                    for (var i = 0; i < flights_details_more.length; i++) {
                                          console.log(flights_details_more[i]);
                                      rutetr +=`<p  style="font-size: 10px; margin-bottom: 0rem;" > ${flights_details_more[i]['more_departure_airport_code']} <i class='bi bi-arrow-left-right' style='color: #0acf97;'></i> ${flights_details_more[i]['more_arrival_airport_code']} </p>`
                                      
                                    }
                                    for (var i = 0; i < flights_details_more.length; i++) {
                                          console.log(flights_details_more[i]);
                                      rutetr +=`<p  style="font-size: 10px;" > ${flights_details_more[i]['return_more_departure_airport_code']} <i class='bi bi-arrow-left-right' style='color: #0acf97;'></i> ${flights_details_more[i]['return_more_arrival_airport_code']} </p>`
                                      
                                    }
                                     
                                    rutetr +=`</td>
                            `;
               
                  var datetr =`
                                  <td class="text-center">`
                                        
                                    for (var i = 0; i < flights_details_more.length; i++) {
                                          var dep_date =  flights_details_more[i]['more_departure_time'];
                                          dep_date = new Date(dep_date).toISOString().split('T')[0]
                                          var arr_date =  flights_details_more[i]['more_arrival_time'];
                                          arr_date = new Date(arr_date).toISOString().split('T')[0]
                                           
                                          var return_dep_date =  flights_details_more[i]['return_more_departure_time'];
                                          return_dep_date = new Date(return_dep_date).toISOString().split('T')[0]
                                          var return_arr_date =  flights_details_more[i]['return_more_arrival_time'];
                                          return_arr_date = new Date(return_arr_date).toISOString().split('T')[0]
                                
                                      datetr +=`<p  style="font-size: 10px; margin-bottom: 0rem;" > ${dep_date} <i class='bi bi-arrow-left-right' style='color: #0acf97;'></i> ${arr_date} </p> <br> <p  style="font-size: 10px;" > ${return_dep_date} <i class='bi bi-arrow-left-right' style='color: #0acf97;'></i> ${return_arr_date} </p>`
                                    }
                                     
                                    datetr +=`</td>
                            `;
              
                var seattr =`
                                  <td class="text-center">`
                                        
                                    // for (var i = 0; i < flights_details_more.length; i++) {
                                
                                      seattr +=`<p  style="font-size: 10px; margin-bottom: 0rem;" > ${flights_details_more[0]['seats']}  </p>`
                                    // }
                                     
                                    seattr +=`</td>
                            `;
                             var pax = $(".pax_number").val();
                            //   console.log(pax);
                             var reservation = `<td class="text-center"> <input type="number" min="${pax}" max="${flights_details_more[0]['seats']}" value="${pax}" class="form-control"  placeholder="Enter Reservation"></td>`;
                             
                             for (var i = 0; i < flights_details_more.length; i++) {
                             var checkbox =` <td class="text-center"> <input type="checkbox" onclick="changeFunction(${flights_details_more[i]['ruteid']})" class="reserved_${flights_details_more[i]['ruteid']}" ></td>`
                // $('.seat_append').append(seattr);
                             }
                  }else{
                      
                      var rutetr = `
                      <td>
                      <p>${dealer}</p>
                      <p style="font-size: 10px;" > ${direct_dep_airport} <i class='bi bi-arrow-left-right' style='color: #0acf97;'></i> ${direct_arr_airport} </p><p style="font-size: 10px;"> ${direct_dep_airport} <i class='bi bi-arrow-left-right' style='color: #0acf97;'></i> ${direct_arr_airport} </p>
                      </td>
                      `;
                      
                      var datetr =`
                                  <td class="text-center" style='width: 10rem;'>`
                                        
                                          var dep_date =  direct_dep_time;
                                          dep_date = new Date(dep_date).toISOString().split('T')[0]
                                          var arr_date =  direct_arr_time;
                                          arr_date = new Date(arr_date).toISOString().split('T')[0]
                                           
                                          var return_dep_date =  direct_return_dep_time;
                                          return_dep_date = new Date(return_dep_date).toISOString().split('T')[0]
                                          var return_arr_date =  direct_return_arr_time;
                                          return_arr_date = new Date(return_arr_date).toISOString().split('T')[0]
                                
                                      datetr +=`<p  style="font-size: 10px; margin-bottom: 0rem;" > ${dep_date} <i class='bi bi-arrow-left-right' style='color: #0acf97;'></i> ${arr_date} </p> <br> <p  style="font-size: 10px;" > ${return_dep_date} <i class='bi bi-arrow-left-right' style='color: #0acf97;'></i> ${return_arr_date} </p>`
                                    
                                     
                                    datetr +=`</td>
                            `;
                            
                             var seattr =`
                                  <td class="text-center">`
                                        
                                 
                                
                                      seattr +=`<p  style="font-size: 10px; margin-bottom: 0rem;" > ${direct_seat}  </p>`
                                    
                                     
                                    seattr +=`</td>
                            `;
                             var pax = $(".pax_number").val();
                             console.log("pax amount"+pax);
                             var reservation = `<td class="text-center"> <input type="number" id"reserved_pax" min="${pax}" max="${direct_seat}" value="${pax}" class="form-control"  placeholder="Enter Reservation"></td>`;
                             
                            var checkbox =` <td class="text-center"> <input type="checkbox" onclick="changeFunction(${direct_id})" class="reserved_${direct_id}" ></td>`
               
               
                  }
        
                 
                 
                 var all_in_one = `<tr class='tr_btn_${inc_id}'>
                 ${rutetr}
                 ${datetr}
                 ${seattr}
                 ${reservation}
                ${checkbox}
                 </tr>`
                 
                 
                 
                 $('.all_tr_append').append(all_in_one);
                 $('.add_more_supplier').show();
                //  $('.all_tr_append').append(datetr);      
                //   $('.all_tr_append').append(seattr);
                 
                 
             }
            
               
            }
        });
            
            
            
     $('.rute_table').fadeIn();
      $('.date_table').fadeIn();
      $('.seat_table').fadeIn();
      $('.reservation_table').fadeIn();
      
     
  });
  
  
  
  
  
   $('.occupy_btn').on('click',function(){
          
          
//             $('.Flight_section').show();
// $('.return_Flight_section').show();

       
        var id  = $('#fightjkjk').val();
        
        $.ajax({    
            type: "POST",
            url: "{{URL::to('')}}/get_suppliers_flights_rute",
            data:{
                "_token"                : "{{ csrf_token() }}",
                'flight'            : id
            },
            crossDomain : true,                  
            success: function(data){  
           
            console.log(data);
              var flights_details_morez = [];
              
              
               var direct_flight_audlt = data['flights_per_person_price'];
               var direct_flight_child = data['flights_per_child_price'];
                var direct_flight_infant = data['flights_per_infant_price'];
                var no_of_pax = $(".pax_number").val();
                
            if(data['flight_type'] == "Indirect"){
           
                    //  console.log(data[i]);
                    //  console.log(data[i]['flight_type']);
                                                var seats = data['flights_number_of_seat'];
                                                var ruteid = data['id'];
                                                
                                               
                                                $('.flights_type_code').val(data['flight_type']);
                                                  var flights_per_person_price = $('.flights_per_person_price12345').val();
                                                    console.log('flights_per_person_price :'+flights_per_person_price);
                                                                $('#flights_prices').val(flights_per_person_price);
                                               
                                                 var direct_flight_audlt = data['flights_per_person_price'];
                                                 var direct_flight_child = data['flights_per_child_price'];
                                                 var direct_flight_infant = data['flights_per_infant_price'];
                                                 var direct_flight_terms_and_conditions = data['terms_and_conditions'];
                                                
                                                 $('.child_flight_cost123').val(direct_flight_child);
                                                
                     
                                                var departur_airport = JSON.parse(data['more_departure_airport_code']);
                                                
                                                var dep_length = data['no_of_stays'];
                                                console.log('no of stay'+dep_length);
                                                var arrival_airport = JSON.parse(data['more_arrival_airport_code']);
                                                var airline = JSON.parse(data['more_other_Airline_Name2']);
                                                var flight_type = JSON.parse(data['more_departure_Flight_Type']);
                                                var flight_number = JSON.parse(data['more_departure_flight_number']);
                                                var flight_time = JSON.parse(data['more_departure_time']);
                                                var arrival_time = JSON.parse(data['more_arrival_time']);
                                                var total_time = JSON.parse(data['more_total_Time']);
                                                
                                                var return_more_departure_airport_code = JSON.parse(data['return_more_departure_airport_code']);
                                                var return_dep_length = return_more_departure_airport_code.length;
                                                var return_more_arrival_airport_code = JSON.parse(data['return_more_arrival_airport_code']);
                                                var return_more_arrival_time = JSON.parse(data['return_more_arrival_time']);
                                                var return_more_departure_time = JSON.parse(data['return_more_departure_time']);
                                                var return_more_flight_number = JSON.parse(data['return_more_departure_flight_number']);
                                                var return_more_other_Airline_Name2 = JSON.parse(data['return_more_other_Airline_Name2']);
                                                var return_more_total_Time = JSON.parse(data['return_more_total_Time']);
                                            
                                                for (var j = 0; j < departur_airport.length; j++) {
                                                    const data_arr ={
                                                    'seats':seats,
                                                    'ruteid':ruteid,
                                                    'more_departure_airport_code':departur_airport[j],
                                                    'more_arrival_airport_code':arrival_airport[j],
                                                    'more_other_Airline_Name2':airline[j],
                                                    'more_departure_Flight_Type':flight_type[j],
                                                    'more_departure_flight_number':flight_number[j],
                                                    'more_departure_time':flight_time[j],
                                                    'more_arrival_time':arrival_time[j],
                                                    'more_total_Time':total_time[j],
                                                    
                                                    'return_more_departure_airport_code':return_more_departure_airport_code[j],
                                                    'return_more_arrival_airport_code':return_more_arrival_airport_code[j],
                                                    'return_more_arrival_time':return_more_arrival_time[j],
                                                    
                                                    'return_more_departure_time':return_more_departure_time[j],
                                                    'return_more_flight_number':return_more_flight_number[j],
                                                    'return_more_other_Airline_Name2':return_more_other_Airline_Name2[j],
                                                    'return_more_total_Time':return_more_total_Time[j],
                                              
                                               
                                                }
                                           
                                                    
                                                   flights_details_morez.push(data_arr);
                                                }
                                           
                $('#flights_type_connected').fadeIn();
                $('#flights_type_connected').empty();
                var total_stop = dep_length;
                // console.log(total_stop);
                if(total_stop == 4){
                     var no_of_stays_Append = `<label for="no_of_stays">No Of Stops</label>
                                            <select  name="no_of_stays" id="no_of_stays" class="form-control select2" data-toggle="select2">
                                                <option value="">Choose...</option>
                                                <option value="2">1</option>
                                                <option  value="3">2</option>
                                                <option  selected value="4">3</option>
                                            </select>`;
                $('#flights_type_connected').append(no_of_stays_Append);
                } 
                if(total_stop == 3){
                     var no_of_stays_Append = `<label for="no_of_stays">No Of Stops</label>
                                            <select  name="no_of_stays" id="no_of_stays" class="form-control select2" data-toggle="select2">
                                                <option value="">Choose...</option>
                                                <option value="2">1</option>
                                                <option  selected value="3">2</option>
                                                <option  value="4">3</option>
                                            </select>`;
                $('#flights_type_connected').append(no_of_stays_Append);
                } 
                if(total_stop == 2){
                     var no_of_stays_Append = `<label for="no_of_stays">No Of Stops</label>
                                            <select  name="no_of_stays" id="no_of_stays" class="form-control select2" data-toggle="select2">
                                                <option value="">Choose...</option>
                                                <option selected value="2">1</option>
                                                <option   value="3">2</option>
                                                <option  value="4">3</option>
                                            </select>`;
                $('#flights_type_connected').append(no_of_stays_Append);
                }
                 
             $('.flight_direct_type').empty();
             
             var flight_option = `<option selected attr='Indirect' value='Indirect'>Indirect</option>
                                  <option attr="Direct" value="Direct">Direct</option>`;
             
             $('.flight_direct_type').append(flight_option);
             
             
             
            //  $('#flights_type_connected').onchange(function () {
        var no_of_stays = total_stop;
        
            $('.return_Flight_section').empty();
            // $('.Flight_section_append').empty();
            $('.Flight_section').empty();
            $('#total_Time_Div').empty();
            $('.return_total_Time').empty();
            $('.return_Flight_section_append').empty();
            var no_of_stay_ID = 1;
            console.log(flights_details_morez);
            for (let i = 1; i <= no_of_stays; i++) {
                var flights_details_morez_key = flights_details_morez[i-1];
                // console.log(flights_details_morez_key['more_departure_airport_code']);
                
                var flight_Data =   `<h3 style="padding: 12px">Departure Details : </h3>
                                     <div class="row" style="padding: 12px">
                                        <div class="col-xl-4">
                                            <label for="">Departure Airport</label>
                                            <input name="more_departure_airport_code[]" value="${flights_details_morez_key['more_departure_airport_code']}" id="departure_airport_code_${i}" class="form-control" autocomplete="off" placeholder="Enter Departure Location">
                                        </div>
                                        <div class="col-xl-1" style="margin-top: 25px;text-align: center;">
                                            <label for=""></label>
                                            <span id="Change_Location_${i}" class='bi bi-arrow-left-right' style="font-size: 23px;"></span>
                                        </div>
                                        <div class="col-xl-4">
                                            <label for="">Arrival Airport</label>
                                            <input name="more_arrival_airport_code[]"  value="${flights_details_morez_key['more_arrival_airport_code']}" id="arrival_airport_code_${i}" class="form-control" autocomplete="off" placeholder="Enter Arrival Location">
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="">Airline Name</label>
                                            <input type="text" id="other_Airline_Name2_${i}" value="${flights_details_morez_key['more_other_Airline_Name2']}" name="more_other_Airline_Name2[]" class="form-control other_airline_Name1_${i}">
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="">Class Type</label>
                                            <select  name="more_departure_Flight_Type[]" id="departure_Flight_Type_${i}" class="form-control">
                                                <option value="">Select Flight Type</option>
                                                <option value="Bussiness">Bussiness</option>
                                                <option value="Economy">Economy</option>
                                                <option value="Standard">Standard</option>
                                            </select>
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="">Flight No</label>
                                            <input type="text" id="simpleinput_${i}" value="${flights_details_morez_key['more_departure_flight_number']}" name="more_departure_flight_number[]" class="form-control" placeholder="Enter Flight Number">
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="">Departure Date & Time</label>
                                            <input type="datetime-local" id="simpleinput_${i}" value="${flights_details_morez_key['more_departure_time']}" name="more_departure_time[]" class="form-control departure_time1_${i}" >
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="">Arrival Date and Time</label>
                                            <input type="datetime-local" id="simpleinput_${i}" value="${flights_details_morez_key['more_arrival_time']}" name="more_arrival_time[]" class="form-control arrival_time1_${i}" >
                                        </div>
                                    </div>
                                    <div class="container total_Time_Div_data_append">
                                   <div class="row" style="margin-left: 303px;">
                                                    <div class="col-sm-3">
                                                        <h3 style="width: 140px;margin-top: 25px;float:right" id="no_of_stop_par">Stop No ${i}</h3>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <label for="">Flight Duration</label>
                                                        <input type="text" id="total_Time" name="more_total_Time[]" value="${flights_details_morez_key['more_total_Time']}" class="form-control total_Time1_${i}" readonly style="width: 167px;">
                                                    </div>
                                                </div>
                                    </div>`;
                                    
                var return_flight_Data = `<h3 style="padding: 12px">Return Details : </h3>
                                          <div class="row" style="padding: 12px">
                                            <div class="col-xl-4">
                                                <label for="">Departure Airport</label>
                                                <input name="return_more_departure_airport_code[]" value="${flights_details_morez_key['return_more_departure_airport_code']}" id="return_departure_airport_code_${i}" class="form-control" autocomplete="off" placeholder="Enter Return Location">
                                            </div>
                                            <div class="col-xl-1" style="margin-top: 25px;text-align: center;">
                                                <label for=""></label>
                                                <span id="return_Change_Location_${i}" class='bi bi-arrow-left-right' style="font-size: 23px;"></span>
                                            </div>
                                            <div class="col-xl-4">
                                                <label for="">Arrival Airport</label>
                                                <input name="return_more_arrival_airport_code[]" value="${flights_details_morez_key['return_more_arrival_airport_code']}" id="return_arrival_airport_code_${i}" class="form-control" autocomplete="off" placeholder="Enter Return Location">
                                                
                                            </div>
                                            <div class="col-xl-3">
                                                <label for="">Airline Name</label>
                                                <input type="text" id="return_other_Airline_Name2_${i}" value="${flights_details_morez_key['return_more_other_Airline_Name2']}" name="return_more_other_Airline_Name2[]" class="form-control other_airline_Name1">
                                            </div>
                                            <div class="col-xl-3">
                                                <label for="">Class Type</label>
                                                <select  name="return_more_departure_Flight_Type[]" id="return_departure_Flight_Type_${i}" class="form-control">
                                                    <option value="">Select Flight Type</option>
                                                    <option value="Bussiness">Bussiness</option>
                                                    <option value="Economy">Economy</option>
                                                    <option value="Standart">Standart</option>
                                                </select>
                                            </div>
                                            <div class="col-xl-3">
                                                <label for="">Flight Number</label>
                                                <input type="text" id="simpleinput_${i}" value="${flights_details_morez_key['return_more_flight_number']}" name="return_more_departure_flight_number[]" class="form-control" placeholder="Enter Flight Number">
                                            </div>
                                            <div class="col-xl-3">
                                                <label for="">Departure Date and Time</label>
                                                <input type="datetime-local" id="simpleinput_${i}" name="return_more_departure_time[]" value="${flights_details_morez_key['return_more_departure_time']}" class="form-control return_departure_time1_${i}" >
                                            </div>
                                            <div class="col-xl-3">
                                                <label for="">Arrival Date and Time</label>
                                                <input type="datetime-local" id="simpleinput_${i}" name="return_more_arrival_time[]" value="${flights_details_morez_key['return_more_arrival_time']}" class="form-control return_arrival_time1_${i}" >
                                            </div>
                                        </div>
                                        <div class="container return_total_Time_Div_data_append">
                                          <div class="row" style="margin-left: 303px;">
                                                    <div class="col-sm-3">
                                                        <h3 style="width: 140px;margin-top: 25px;float:right" id="no_of_stop_par">Stop No ${i}</h3>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <label for="">Flight Duration</label>
                                                        <input type="text" id="total_Time" name="more_total_Time[]" value="${flights_details_morez_key['return_more_total_Time']}" class="form-control total_Time1_${i}" readonly style="width: 167px;">
                                                    </div>
                                                </div>
                                        </div>`;
                                        
                                          $('#flights_arrival_code').val(flights_details_morez_key['more_departure_airport_code']);
                                          $('.direct_term_and_condition').html(direct_flight_terms_and_conditions);
                   
                    $('#flights_prices').val(direct_flight_audlt);
                    $('#return_total_Time_Div').css('display','');
                     
                      $('#child_flight_price').val(direct_flight_child);
                      $('#child_flight_cost').val(1);
                      $('#child_flight_price_infant').val(direct_flight_infant);
                       $('#return_total_Time_Div').hide();
                       
                       
                       
                        $('#flights_per_person_price').val(direct_flight_audlt);
                        $('#per_adult_price').val(direct_flight_audlt);
                        $('#flights_per_child_price').val(direct_flight_child);
                        $('#per_child_price').val(direct_flight_child);
                        $('#flights_per_infant_price').val(direct_flight_infant);
                        $('#per_infant_price').val(direct_flight_infant);
          $('#adult_number').val(no_of_pax);
                       
                       
                       
                       
                                    
                $('.Flight_section_append').append(flight_Data);
                
                $('#Change_Location_'+i+'').click(function () {
                    var arrival_airport_code   = $('#arrival_airport_code_'+i+'').val();
                    var departure_airport_code = $('#departure_airport_code_'+i+'').val();
                    $('#arrival_airport_code_'+i+'').val(departure_airport_code);
                    $('#departure_airport_code_'+i+'').val(arrival_airport_code);
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
                $('.return_Flight_section_append').append(return_flight_Data);
                
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
          
        // }
    // });
    
     $('.hide_direct').show();
                     
                 }
                 else{
                     $('#flights_type_connected').empty();
                       $('.flights_type_code').val(data['flight_type']);
            // var flights_per_person_price = $('.flights_per_person_price12345').val();
          
                     var direct_flight_audlt = data['flights_per_person_price'];
                       
                     var direct_flight_child = data['flights_per_child_price'];
                     $('.child_flight_cost123').val(direct_flight_child);
                     var direct_flight_infant = data['flights_per_infant_price'];
                     var direct_flight_terms_and_conditions = data['terms_and_conditions'];
                     var direct_flight_time = data['return_total_Time'];
                     var direct_flight_type = data['flight_type'];
                     var direct_airline = data['other_Airline_Name2'];
                     var direct_flight_number = data['departure_flight_number'];
                     var direct_seat = data['flights_number_of_seat'];
                     var direct_dep_airport = data['departure_airport_code'];
                     var direct_dep_time = data['departure_time'];
                     var direct_arr_airport = data['arrival_airport_code'];
                     var direct_arr_time = data['arrival_time'];
                     
                     var direct_return_airline = data['return_other_Airline_Name2'];
                     var direct_return_dep_airport = data['return_departure_airport_code'];
                     var direct_return_dep_time = data['return_departure_time'];
                     var direct_return_arr_airport = data['return_arrival_airport_code'];
                     var direct_return_arr_time = data['return_arrival_time'];
                     var direct_return_Flight_Type = data['return_departure_Flight_Type'];
                     var direct_return_Flight_number = data['return_departure_flight_number'];
                     var direct_return_return_total_Time = data['return_total_Time'];
                    
 
                    
                     
                    $('#departure_airport_code').val(direct_dep_airport);
                    $('#arrival_airport_code').val(direct_arr_airport);
                    $('.departure_time1').val(direct_dep_time);
                    $('.arrival_time1').val(direct_arr_time);
                    $('.departure_flight_number').val(direct_flight_number);
                    $('.other_airline_Name1').val(direct_airline);
                    $('.flight_direct_type').append(`<option  selected attr='Direct' value='Direct'>Direct</option>`);
                    $('.departure_Flight_Type').append(`<option selected attr='${direct_flight_type}' value='${direct_flight_type}'>${direct_flight_type}</option>`);
                    $('.total_Time1').val(direct_flight_time);
                     $('#total_Time_Div').css('display','');
                     
                     
                     $('#return_departure_airport_code').val(direct_return_dep_airport);
                    $('#return_arrival_airport_code').val(direct_return_arr_airport);
                    $('.return_departure_time1').val(direct_return_dep_time);
                    // $('.arrival_time1').val(direct_arr_time);
                    // $('.direct_flight_number').val(direct_flight_number);
                    $('#return_other_Airline_Name2').val(direct_return_airline);
                    // $('.flight_direct_type').append(`<option  selected attr='Direct' value='Direct'>Direct</option>`);
                    $('#return_departure_Flight_Type').append(`<option selected attr='${direct_return_Flight_Type}' value='${direct_return_Flight_Type}'>${direct_return_Flight_Type}</option>`);
                    $('.return_total_Time1').val(direct_return_return_total_Time);
                    $('.return_departure_flight_number').val(direct_return_Flight_number);
                    $('.return_arrival_time1').val(direct_return_arr_time);
                    $('.direct_term_and_condition').html(direct_flight_terms_and_conditions);
                    $('#flights_per_person_price').val(direct_flight_audlt);
                    $('#return_total_Time_Div').css('display','');
                     
                  $('#child_flight_price').val(direct_flight_child);
                  $('#child_flight_price_infant').val(direct_flight_infant);
                  $('.hide_direct').show();
                       
            
            
            
        console.log('flights_per_person_price :'+direct_flight_audlt);
       
        
          $('#adult_number').val(no_of_pax);
        console.log('direct_flight_infant :'+direct_flight_infant);
        $('#flights_prices').val(direct_flight_audlt);
        
        
      
        
        
        $('#flights_arrival_code').val(direct_dep_airport);
        
        
        
        
        
        
        
                        $('#flights_per_person_price').val(direct_flight_audlt);
                        $('#per_adult_price').val(direct_flight_audlt);
                        $('#flights_per_child_price').val(direct_flight_child);
                        $('#per_child_price').val(direct_flight_child);
                        $('#flights_per_infant_price').val(direct_flight_infant);
                        $('#per_infant_price').val(direct_flight_infant);
                 }
               
            }
        });
       
    });
  
  
  
  
  
  
  
  $("#adult_number").on('change',function(){
      var adult_qty = $('#adult_number').val();
         $('#adult_final_qty').val(adult_qty);
    });
    $("#child_number").on('change',function(){
     var child_qty = $('#child_number').val();
         $('#child_final_qty').val(child_qty);
    });
    $("#infant_number").on('change',function(){
      var infant_qty = $('#infant_number').val();
         $('#infant_final_qty').val(infant_qty);
    });
  
  
  
        //awaab end
    });
    
    $("#destination").on('click',function(){
        $("#destination").slideToggle();
        $("#select_destination").css('display','');
    });
    
    
    
    
    function PriceCalculatorFunction() {
// alert("adult_number");


   
}
    
     $("#markup_type_for_adult").change(function () {
        var id = $(this).find('option:selected').attr('value');
        
        $('#markup_value_markup_mrk_for_adult').text(id);
        var markup_val =  $('#markup_value_for_adult').val();
        
        var flights_prices =  $('#per_adult_price').val();
        if(id == '%')
        {
            $('#markup_value_for_adult').keyup(function() {
                var markup_val =  $('#markup_value_for_adult').val();
                var total1 = (flights_prices * markup_val/100) + parseFloat(flights_prices) ;
                var total = total1.toFixed(2);
                $('#total_markup_for_adult').val(total);
                var adult_quantity = $('#adult_final_qty').val();
                var after_adding_markup = parseFloat(adult_quantity) * parseFloat(total);
                console.log(after_adding_markup);
                $('.adult_total_with_markup').val(after_adding_markup);
                $('#total_adult_markup').val(after_adding_markup);
                console.log('before is call ');
    var adult_markup = $('.adult_total_with_markup').val();
    var child_markup = $('.child_total_with_markup').val();
    var infant_markup = $('.infant_total_with_markup').val();
    if(adult_markup){
        adult_markup = adult_markup;
    }else{
        adult_markup = 0;
    }
    if(child_markup){
        child_markup = child_markup;
    }else{
        child_markup = 0;
    }
    if(infant_markup){
        infant_markup = infant_markup;
    }else{
        infant_markup = 0;
    }
    console.log("adult_markup"+adult_markup);
    console.log("child_markup"+child_markup);
    console.log("adult_markup"+infant_markup);
    var total_mark_up = parseFloat(adult_markup) + parseFloat(child_markup) + parseFloat(infant_markup);
      $('#total_markup').val(total_mark_up);
            });
        }
        else
        {
            $('#markup_value_for_adult').keyup(function() {
                var markup_val =  $('#markup_value_for_adult').val();
                console.log(markup_val);
                var total1 = parseFloat(flights_prices) + parseFloat(markup_val);
                var total = total1.toFixed(2);
                 $('#total_markup_for_adult').val(total);
                var adult_quantity = $('#adult_final_qty').val();
                var after_adding_markup = parseFloat(adult_quantity) * parseFloat(total);
                 console.log(after_adding_markup);
                $('.adult_total_with_markup').val(after_adding_markup);
                $('#total_adult_markup').val(after_adding_markup);
                console.log('before is call ');
                 var adult_markup = $('.adult_total_with_markup').val();
    var child_markup = $('.child_total_with_markup').val();
    var infant_markup = $('.infant_total_with_markup').val();
    if(adult_markup){
        adult_markup = adult_markup;
    }else{
        adult_markup = 0;
    }
    if(child_markup){
        child_markup = child_markup;
    }else{
        child_markup = 0;
    }
    if(infant_markup){
        infant_markup = infant_markup;
    }else{
        infant_markup = 0;
    }
    console.log("adult_markup"+adult_markup);
    console.log("child_markup"+child_markup);
    console.log("adult_markup"+infant_markup);
    var total_mark_up = parseFloat(adult_markup) + parseFloat(child_markup) + parseFloat(infant_markup);
      $('#total_markup').val(total_mark_up);
            });
        }
    });
    
    
    
     $("#markup_type_for_child").change(function () {
        var id = $(this).find('option:selected').attr('value');
        
        $('#markup_value_markup_mrk_for_child').text(id);
        var markup_val =  $('#markup_value_for_child').val();
        
        var flights_prices =  $('#per_child_price').val();
        if(id == '%')
        {
            $('#markup_value_for_child').keyup(function() {
                var markup_val =  $('#markup_value_for_child').val();
                var total1 = (flights_prices * markup_val/100) + parseFloat(flights_prices) ;
                var total = total1.toFixed(2);
                $('#total_markup_for_child').val(total);
                var child_quantity = $('#child_final_qty').val();
                var after_adding_markup = parseFloat(child_quantity) * parseFloat(total);
                console.log(after_adding_markup);
                $('.child_total_with_markup').val(after_adding_markup);
                $('#total_child_markup').val(after_adding_markup);
                console.log('before is call ');
                 var adult_markup = $('.adult_total_with_markup').val();
    var child_markup = $('.child_total_with_markup').val();
    var infant_markup = $('.infant_total_with_markup').val();
    if(adult_markup){
        adult_markup = adult_markup;
    }else{
        adult_markup = 0;
    }
    if(child_markup){
        child_markup = child_markup;
    }else{
        child_markup = 0;
    }
    if(infant_markup){
        infant_markup = infant_markup;
    }else{
        infant_markup = 0;
    }
    console.log("adult_markup"+adult_markup);
    console.log("child_markup"+child_markup);
    console.log("adult_markup"+infant_markup);
    var total_mark_up = parseFloat(adult_markup) + parseFloat(child_markup) + parseFloat(infant_markup);
      $('#total_markup').val(total_mark_up);
            });
        }
        else
        {
            $('#markup_value_for_child').keyup(function() {
                var markup_val =  $('#markup_value_for_child').val();
                console.log(markup_val);
                var total1 = parseFloat(flights_prices) + parseFloat(markup_val);
                var total = total1.toFixed(2);
                 $('#total_markup_for_child').val(total);
                var child_quantity = $('#child_final_qty').val();
                var after_adding_markup = parseFloat(child_quantity) * parseFloat(total);
                 console.log(after_adding_markup);
                $('.child_total_with_markup').val(after_adding_markup);
                   $('#total_child_markup').val(after_adding_markup);
                   console.log('before is call ');
                var adult_markup = $('.adult_total_with_markup').val();
    var child_markup = $('.child_total_with_markup').val();
    var infant_markup = $('.infant_total_with_markup').val();
    if(adult_markup){
        adult_markup = adult_markup;
    }else{
        adult_markup = 0;
    }
    if(child_markup){
        child_markup = child_markup;
    }else{
        child_markup = 0;
    }
    if(infant_markup){
        infant_markup = infant_markup;
    }else{
        infant_markup = 0;
    }
    console.log("adult_markup"+adult_markup);
    console.log("child_markup"+child_markup);
    console.log("adult_markup"+infant_markup);
    var total_mark_up = parseFloat(adult_markup) + parseFloat(child_markup) + parseFloat(infant_markup);
      $('#total_markup').val(total_mark_up);
            });
        }
    });
    
    
    
     $("#markup_type_for_infant").change(function () {
        var id = $(this).find('option:selected').attr('value');
        
        $('#markup_value_markup_mrk_for_infant').text(id);
        var markup_val =  $('#markup_value_for_infant').val();
        
        var flights_prices =  $('#per_infant_price').val();
        if(id == '%')
        {
            $('#markup_value_for_infant').keyup(function() {
                var markup_val =  $('#markup_value_for_infant').val();
                var total1 = (flights_prices * markup_val/100) + parseFloat(flights_prices) ;
                var total = total1.toFixed(2);
                $('#total_markup_for_infant').val(total);
                var infant_quantity = $('#infant_final_qty').val();
                var after_adding_markup = parseFloat(infant_quantity) * parseFloat(total);
                console.log(after_adding_markup);
                $('.infant_total_with_markup').val(after_adding_markup);
                   $('#total_infant_markup').val(after_adding_markup);
                   console.log('before is call ');
               var adult_markup = $('.adult_total_with_markup').val();
    var child_markup = $('.child_total_with_markup').val();
    var infant_markup = $('.infant_total_with_markup').val();
    if(adult_markup){
        adult_markup = adult_markup;
    }else{
        adult_markup = 0;
    }
    if(child_markup){
        child_markup = child_markup;
    }else{
        child_markup = 0;
    }
    if(infant_markup){
        infant_markup = infant_markup;
    }else{
        infant_markup = 0;
    }
    console.log("adult_markup"+adult_markup);
    console.log("child_markup"+child_markup);
    console.log("adult_markup"+infant_markup);
    var total_mark_up = parseFloat(adult_markup) + parseFloat(child_markup) + parseFloat(infant_markup);
      $('#total_markup').val(total_mark_up);
            });
        }
        else
        {
            $('#markup_value_for_infant').keyup(function() {
                var markup_val =  $('#markup_value_for_infant').val();
                console.log(markup_val);
                var total1 = parseFloat(flights_prices) + parseFloat(markup_val);
                var total = total1.toFixed(2);
                 $('#total_markup_for_infant').val(total);
                var infant_quantity = $('#infant_final_qty').val();
                var after_adding_markup = parseFloat(infant_quantity) * parseFloat(total);
                 console.log(after_adding_markup);
                $('.infant_total_with_markup').val(after_adding_markup);
                $('#total_infant_markup').val(after_adding_markup);
                console.log('before is call ');
                var adult_markup = $('.adult_total_with_markup').val();
    var child_markup = $('.child_total_with_markup').val();
    var infant_markup = $('.infant_total_with_markup').val();
    if(adult_markup){
        adult_markup = adult_markup;
    }else{
        adult_markup = 0;
    }
    if(child_markup){
        child_markup = child_markup;
    }else{
        child_markup = 0;
    }
    if(infant_markup){
        infant_markup = infant_markup;
    }else{
        infant_markup = 0;
    }
    console.log("adult_markup"+adult_markup);
    console.log("child_markup"+child_markup);
    console.log("adult_markup"+infant_markup);
    var total_mark_up = parseFloat(adult_markup) + parseFloat(child_markup) + parseFloat(infant_markup);
      $('#total_markup').val(total_mark_up);
            });
        }
    });
    

     function changeFunction(x) {
// alert(x);

if ($(".reserved_"+x+"").is(':checked')) {
    $('#fightjkjk').val(x);
    $('.occupy_btn').show();
    $(".occupy_btn").appendTo($(".tr_btn_"+x+""));
     
                //  alert('button is checked')
               
            } else {
                // alert('button is not checked')
                
            }
   
}
  function add_numberElse_1I() {
    alert("add_numberElse_1I called");
    var adult_markup = $('.adult_total_with_markup').val();
    var child_markup = $('.child_total_with_markup').val();
    var infant_markup = $('.infant_total_with_markup').val();
    console.log("adult_markup"+adult_markup);
    console.log("child_markup"+child_markup);
    console.log("adult_markup"+infant_markup);
    var total_mark_up = parseFloat(adult_markup) + parseFloat(child_markup) + parseFloat(infant_markup);
      $('#total_markup').val(total_mark_up);
}  
</script>

<script>

    $(document).on('change','#select_services' ,function(){
  var val = $('#select_services option:selected').val();
 //alert(val);
 if(val == 'flights_tab')
 {
     $("#accomodation_price_hide").css('display','none');
     $("#sale_pr").css('display','none');
 }
 if(val == 'visa_tab')
 {
     $("#accomodation_price_hide").css('display','none');
     $("#sale_pr").css('display','none');
 }
 if(val == 'transportation_tab')
 {
      $("#accomodation_price_hide").css('display','none');
     $("#sale_pr").css('display','none');
 }
  

})

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
    
    function selectServices(){
        var selectedServices = $('#select_services').val();
        // console.log(selectedServices);
        
        $.each(selectedServices, function(key, value) {
            console.log(value);
            if(value != null && value != ''){
                if((value != '1') && (value != 'accomodation_tab')){
                    $('.more_Div_Acc_All').css('display','none');
                    $('.no_of_Nights_Other').html('Total Duration');
                    
                }else{
                    $('.more_Div_Acc_All').css('display','');
                    $('.no_of_Nights_Other').html('No Of Nights');
                }
            }
        });
        
        const allServicesSelect = selectedServices.find(element => element == 1);
        
        if(allServicesSelect){
            $(".accomodation_tab").css('display','block');
             $(".flights_tab").css('display','block');
             $(".visa_tab").css('display','block');
             $(".transportation_tab").css('display','block');
        }else{
             $(".accomodation_tab").css('display','none');
             $(".flights_tab").css('display','none');
             $(".visa_tab").css('display','none');
             $(".transportation_tab").css('display','none');
             for(var i=0; i < selectedServices.length; i++){
                  $("."+selectedServices[i]+"").css('display','flex');
             }
        }
        
       
    }
    
    var passengerCounter = 1;
    $('#no_of_pax_days').on('change',function(){
        var no_of_pax_days = $('#no_of_pax_days').val();
         $('#adult_number').val(no_of_pax_days);
         $('#reserved_pax').val(no_of_pax_days);
        var prev_value = $('#no_of_pax_prev').val();
        
        var difference = no_of_pax_days - prev_value;
        
        passengerCounter = +passengerCounter + +difference
         
         
         for(var i = prev_value; i<=no_of_pax_days; i++){
             id = +i + +1;
             console.log('id is now '+id);
         }
         
         $('#no_of_pax_prev').val(no_of_pax_days);
         var passengerHtml = `<div class="row other_passengers" style="border: 1px solid #ebebeb;padding: 1rem;border-radius: 6px;">
                                            <div class="col-xl-6">
                                                <div class="mb-3">
                                                    <label for="simpleinput" class="form-label no_of_pax_days">First Name</label>
                                                    <input type="text" name="lead_fname" class="form-control" required>
                                                </div>
                                            </div>
                                            
                                             <div class="col-xl-6">
                                                <div class="mb-3">
                                                    <label for="simpleinput" class="form-label no_of_pax_days">Last Name</label>
                                                    <input type="text" name="lead_fname" class="form-control" required>
                                                </div>
                                            </div>
                                            
                                                    
                                            <div class="col-xl-4">
                                                <div class="mb-3">
                                                    <label for="simpleinput" class="form-label no_of_pax_days">Select Nationality</label>
                                                     <select type="text" class="form-control select2 " name="Country"  data-placeholder="Choose ...">
                                                        @foreach($all_countries as $country_res)
                                                            <option value="{{$country_res->id}}" id="categoriesPV" required>{{$country_res->name}}</option>
                                                           
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-xl-2" >
                                                <div class="mb-3">
                                                     <p style="margin-top: 2.2rem;">Gender</p>
                                                </div>
                                            </div>
                                            
                                               
                                            <div class="col-2">
                                              <div class="form-check" style="margin-top: 2.2rem;">
                                              <input class="form-check-input" type="radio" name="gender" value="male"  id="flexRadioDefault3">
                                              <label class="form-check-label" for="flexRadioDefault3">
                                                Male
                                              </label>
                                            </div>
                                            
                                            </div>
                                            <div class="col-2" style="margin-top: 2.2rem;">
                                                <div class="form-check">
                                              <input class="form-check-input" type="radio" name="gender" value="female"  id="flexRadioDefault4">
                                              <label class="form-check-label" for="flexRadioDefault4">
                                                Female
                                              </label>
                                            </div>
                                            </div>
                                        </div>`
                                        console.log("no_of_pax_days"+no_of_pax_days);
                                        
    })
    
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
           
            url: "super_admin/get_other_visa_type_detail", 
             type: "GET",
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
    
    // $(document).on('click','#transportation',function(){
    //     $.ajax({    
    //         type: "GET",
    //         url: "get_pickup_dropof_location",             
    //         dataType: "html",                  
    //         success: function(data){
    //             var data1 = JSON.parse(data);
    //             var data2 = data1['pickup_location_get'];
    //             var data3 = data1['dropof_location_get'];
    //         	$(".pickup_location").empty();
    //             $(".dropof_location").empty();
    //             $.each(data2, function(key, value) {
    //                 var pickup_location_Data = `<option attr="${value.pickup_location}" value="${value.pickup_location}"> ${value.pickup_location}</option>`;
    //                 $(".pickup_location").append(pickup_location_Data);
    //             });
    //             $.each(data3, function(key, value) {
    //                 var dropof_location_Data = `<option attr="${value.dropof_location}" value="${value.dropof_location}"> ${value.dropof_location}</option>`;
    //                 $(".dropof_location").append(dropof_location_Data);
    //             });
    //         }
    //     });
    // });

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
                    $(".more_visa_type_class").empty();
                    $.each(data, function(key, value) {
                        var visa_type_Data = `<option attr="${value.other_visa_type}" value="${value.other_visa_type}"> ${value.other_visa_type}</option>`;
                        $("#visa_type").append(visa_type_Data);
                        $(".more_visa_type_class").append(visa_type_Data);
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

    $('#agent_Name').change(function () {
        var agent_Id = $(this).find('option:selected').attr('attr-Id');
        $('#agent_Id').val(agent_Id);
    });

    $('#transportation_price_per_vehicle').keyup(function() {
            
        var transportation_price_per_vehicle    =  $('#transportation_price_per_vehicle').val();
        var transportation_no_of_vehicle        =  $('#transportation_no_of_vehicle').val();
        var no_of_pax_days                      =  $('#no_of_pax_days').val();
        var t_trans1                            = transportation_price_per_vehicle * transportation_no_of_vehicle;
        var t_trans                             = t_trans1.toFixed(2);
        console.log('t_trans'+t_trans);
        $('#transportation_vehicle_total_price').val(t_trans);
        var total_trans1    = t_trans/no_of_pax_days;
        var total_trans     = total_trans1.toFixed(2);
        console.log('total_trans'+total_trans);
        $('#transportation_price_per_person').val(total_trans);
        $('#transportation_price_per_person_select').val(total_trans);
        
        add_numberElseI();
    });

    $('#return_transportation_price_per_vehicle').keyup(function() {    
        var return_transportation_price_per_vehicle =  $('#return_transportation_price_per_vehicle').val();
        var return_transportation_no_of_vehicle     =  $('#return_transportation_no_of_vehicle').val();
        var no_of_pax_days                          =  $('#no_of_pax_days').val();
        var return_t_trans1                         = return_transportation_price_per_vehicle * return_transportation_no_of_vehicle;
        var return_t_trans                          = return_t_trans1.toFixed(2);
        console.log('return_t_trans'+return_t_trans);
        $('#return_transportation_vehicle_total_price').val(return_t_trans)
        var return_total_trans1 = return_t_trans/no_of_pax_days;
        var return_total_trans  = return_total_trans1.toFixed(2);
        console.log('return_total_trans'+return_total_trans);
        $('#return_transportation_price_per_person').val(return_total_trans)
        
        add_numberElseI();
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
                add_numberElse_1I();
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
                add_numberElse_1I();
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
                $('#total_visa_markup_value').val(total);
                
                add_numberElse_1I();
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
                $('#total_visa_markup_value').val(total);
                add_numberElse_1I();
            });
        }
    });
        
    $('#visa_fee').keyup(function() {
        var visa_fee = $(this).val();
        $('#visa_price_select').val(visa_fee);
        $('#exchange_rate_visaI').val('');
        $('#exchange_rate_visa_total_amountI').val('');
        add_numberElseI();
    });
        
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
                add_numberElse_1I();
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
                add_numberElse_1I();
                });
            }
        });
  
    $('#flights_per_person_price').keyup(function() {
        var flights_per_person_price = $(this).val();
        console.log('flights_per_person_price :'+flights_per_person_price);
        $('#flights_prices').val(flights_per_person_price);
        
        add_numberElseI();
    });

    $("#flights_inc").click(function () {
        // $('#flights_cost').toggle();
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
        if(id == 'Others'){
        	$('#SubmitForm_sec').fadeOut();
        	$('#SubmitForm_sec').fadeIn();
        }
        else{
        	$('#SubmitForm_sec').fadeOut();
        }
    });
    
</script>

<script>
    
    function add_numberElseI(){
        var flights_prices=parseFloat($("#flights_prices").val());  
        if(isNaN(flights_prices)) 
        {
            flights_prices=0;
        }
        else
        {
            var flights_prices=parseFloat($("#flights_prices").val()); 
        }
        var visa_price_select=parseFloat($("#visa_fee").val());  
        if(isNaN(visa_price_select)) 
        {
            visa_price_select=0;
        }
        else
        {
            var visa_price_select=parseFloat($("#visa_fee").val());
        }
  
        var transportation_price_per_person_select=parseFloat($("#transportation_price_per_person").val());
        if(isNaN(transportation_price_per_person_select)) 
        {
            transportation_price_per_person_select=0;
        }
        else
        {
            var transportation_price_per_person_select=parseFloat($("#transportation_price_per_person").val());
        }
        
        var count =$("#city_No").val();
        
        // var city_slc =$(".city_slc").val();
        // var count = city_slc.length;
        var quad_hotel=0;
        var triple_hotel=0;
        var double_hotel=0;
        var more_quad_hotel=0;
        var more_triple_hotel=0;
        var more_double_hotel=0;
   
        for(var i=1; i<=5; i++){
            var hotel_acc_type = $('#hotel_acc_type_'+i+'').val();
            var hotel_markup = parseFloat($("#hotel_acc_price_"+i+'').val());
            
            if(isNaN(hotel_markup)) 
            {
                hotel_markup=0;
            }
            else
            {
                var hotel_markup=parseFloat($("#hotel_acc_price_"+i+'').val());
            }
            
            if(hotel_acc_type == 'Quad')
            {
                quad_hotel = quad_hotel + hotel_markup;
                var quad_hotel1 = quad_hotel.toFixed(2);
                $('#quad_cost_price').val(quad_hotel1);
            }
            if(hotel_acc_type == 'Triple')
            {
                triple_hotel = triple_hotel + hotel_markup;
                var triple_hotel1 = triple_hotel.toFixed(2);
                $('#triple_cost_price').val(triple_hotel1);
            }
            if(hotel_acc_type == 'Double')
            {
                double_hotel = double_hotel + hotel_markup;
                var double_hotel1 = double_hotel.toFixed(2);
                $('#double_cost_price').val(double_hotel1);
            }
                   
        }
        
        var sumData = flights_prices + visa_price_select + transportation_price_per_person_select;
        if(quad_hotel != 0){
            var quadCost = quad_hotel + sumData;
        }else{
            var quadCost = 0;
        }
        if(triple_hotel != 0){
            var tripleCost = triple_hotel + sumData;
        }else{
            var tripleCost = 0;
        }
        if(double_hotel != 0){
            var doubleCost = double_hotel + sumData;
        }else{
            var doubleCost = 0;
        }
        quadCost = quadCost.toFixed(2);
        $('#quad_cost_price').val(quadCost);
        tripleCost = tripleCost.toFixed(2);
        $('#triple_cost_price').val(tripleCost);
        doubleCost = doubleCost.toFixed(2);
        $('#double_cost_price').val(doubleCost);
        
        for(var k=1; k<=50; k++){
            
            var more_hotel_acc_type=$('#more_hotel_acc_type_'+k+'').val(); 
            var more_hotel_markup=$('#more_hotel_acc_price_'+k+'').val();  
            
            if(isNaN(more_hotel_markup)) 
            {
                more_hotel_markup=0;
            }
            else
            {
                var more_hotel_markup=parseFloat($("#more_hotel_acc_price_"+k+'').val());
            }
            if(more_hotel_acc_type == 'Quad')
            {
               more_quad_hotel = more_quad_hotel + more_hotel_markup;
               var more_quad_hotel1 = more_quad_hotel.toFixed(2);
                 $('#quad_cost_price').val(more_quad_hotel1);
            }
            if(more_hotel_acc_type == 'Triple')
            {
                more_triple_hotel = more_triple_hotel + more_hotel_markup;
                var more_triple_hotel1 = more_triple_hotel.toFixed(2);
                $('#triple_cost_price').val(more_triple_hotel1);
            }
            if(more_hotel_acc_type == 'Double')
            {
               more_double_hotel = more_double_hotel + more_hotel_markup;
               var more_double_hotel1 = more_double_hotel.toFixed(2);
                $('#double_cost_price').val(more_double_hotel1);
            }
        }
        
        var morequadCost = sumData + more_quad_hotel;
        morequadCost = morequadCost.toFixed(2);
        
        if(more_quad_hotel == 0){
            if(quadCost != 0){
                $('#quad_cost_price').val(quadCost);   
            }else{
                $('#quad_cost_price').val(0);   
            }
        }else{
            $('#quad_cost_price').val(morequadCost);   
        }
        
        var moretripleCost = sumData + more_triple_hotel;
        moretripleCost = moretripleCost.toFixed(2);
        
        if(more_triple_hotel == 0){
            if(tripleCost != 0){
                $('#triple_cost_price').val(tripleCost);   
            }else{
                $('#triple_cost_price').val(0);   
            }  
        }else{
            $('#triple_cost_price').val(moretripleCost);   
        }
        
        
        var moredoubleCost = sumData + more_double_hotel;
        moredoubleCost = moredoubleCost.toFixed(2);
        
        if(more_double_hotel == 0){
            if(doubleCost != 0){
                //$('#double_cost_price').val(doubleCost);   
            }else{
                $('#double_cost_price').val(0);   
            }   
        }else{
            //$('#double_cost_price').val(moredoubleCost);
        }
    }
    
    function add_numberElse_1I(){
        var count =$("#city_No").val();
        // var city_slc =$(".city_slc").val();
        // var count = city_slc.length;
        var quad_hotel=0;
        var triple_hotel=0;
        var double_hotel=0;
        var more_quad_hotel=0;
        var more_triple_hotel=0;
        var more_double_hotel=0;
        
        var total_markup = parseFloat($("#total_markup").val());  
        if(isNaN(total_markup)) 
        {
            total_markup=0;
        }
        else
        {
            var total_markup=parseFloat($("#total_markup").val()); 
        }
        
        var total_visa_markup = parseFloat($("#total_visa_markup").val());  
        if(isNaN(total_visa_markup)) 
        {
            total_visa_markup=0;
        }
        else
        {
            var total_visa_markup = parseFloat($("#total_visa_markup").val());
        }
        
        var transportation_markup_total=parseFloat($("#transportation_markup_total").val());
        if(isNaN(transportation_markup_total)) 
        {
            transportation_markup_total=0;
        }
        else
        {
            var transportation_markup_total=parseFloat($("#transportation_markup_total").val());
        }
       
        for(var i=1; i<=10; i++){
            
            var hotel_acc_type=$('#hotel_acc_type_'+i+'').val();
            var hotel_markup=parseFloat($("#hotel_markup_total_"+i+'').val());
            
            if(isNaN(hotel_markup)) 
            { 
                hotel_markup=0;
            }
            else
            {
                // console.log("hotel_markup : " + hotel_markup);
                var hotel_markup = parseFloat($("#hotel_markup_total_"+i+'').val());
            }
            
            if(hotel_acc_type == 'Quad')
            {
                quad_hotel      = quad_hotel + hotel_markup + more_quad_hotel;
                var quad_hotel1 = quad_hotel.toFixed(2);
                $('#quad_grand_total_amount').val(quad_hotel1);
            }
            if(hotel_acc_type == 'Triple')
            {
                triple_hotel        = triple_hotel  +hotel_markup + more_triple_hotel;
                var triple_hotel1   = triple_hotel.toFixed(2);
                $('#triple_grand_total_amount').val(triple_hotel1);
            }
            if(hotel_acc_type == 'Double')
            {
                double_hotel        = double_hotel + hotel_markup  + more_double_hotel;
                var double_hotel1   = double_hotel.toFixed(2);
                $('#double_grand_total_amount').val(double_hotel1);
            }
        }
        
        var sumData = total_markup + total_visa_markup + transportation_markup_total;
        $('#without_acc_sale_price').val(sumData);
        
        
        if(quad_hotel != 0){
           var quadCost = quad_hotel + sumData;
        }else{
            var quadCost = 0;
        }
        
        if(triple_hotel != 0){
            var tripleCost = triple_hotel + sumData;
        }else{
            var tripleCost = 0;
        }
        
        if(double_hotel != 0){
            var doubleCost = double_hotel + sumData;
        }else{
            var doubleCost = 0;
        }
        
        quadCost = quadCost.toFixed(2);
        $('#quad_grand_total_amount').val(quadCost);
        tripleCost = tripleCost.toFixed(2);
        $('#triple_grand_total_amount').val(tripleCost);
        doubleCost = doubleCost.toFixed(2);
        $('#double_grand_total_amount').val(doubleCost);
        
        for(var k=1; k<=20; k++){
            var more_hotel_acc_type = $('#more_hotel_acc_type_'+k+'').val(); 
            var more_hotel_markup   = $('#more_hotel_markup_total_'+k+'').val();
            if(isNaN(more_hotel_markup)) 
            {
                more_hotel_markup=0;
            }
            else
            {
                var more_hotel_markup=parseFloat($("#more_hotel_markup_total_"+k+'').val());
                // $('#more_hotel_invoice_markup_'+k+'').val(more_hotel_markup);
            }
            if(more_hotel_acc_type == 'Quad')
            {
                more_quad_hotel         = more_quad_hotel + more_hotel_markup;
                var more_quad_hotel1    = 0;
                more_quad_hotel1        = more_quad_hotel.toFixed(2);
                $('#quad_grand_total_amount').val(more_quad_hotel1);
            }
            if(more_hotel_acc_type == 'Triple')
            {
                more_triple_hotel       = more_triple_hotel + more_hotel_markup;
                var more_triple_hotel1  = 0;
                more_triple_hotel1      = more_triple_hotel.toFixed(2);
                $('#triple_grand_total_amount').val(more_triple_hotel1);
            }
            if(more_hotel_acc_type == 'Double')
            {
                more_double_hotel       = more_double_hotel +more_hotel_markup;
                var more_double_hotel1  = 0;
                more_double_hotel1      = more_double_hotel.toFixed(2);
                $('#double_grand_total_amount').val(more_double_hotel1);
            }
        }
    
        var morequadCost = sumData + more_quad_hotel;
        morequadCost = morequadCost.toFixed(2);
        
        if(more_quad_hotel == 0){
            if(quadCost != 0){
                $('#quad_grand_total_amount').val(quadCost);   
            }else{
                $('#quad_grand_total_amount').val(0);   
            }   
        }else{
            $('#quad_grand_total_amount').val(morequadCost);   
        }
        
        var moretripleCost = sumData + more_triple_hotel;
        moretripleCost = moretripleCost.toFixed(2);
        
        if(more_triple_hotel == 0){
            if(tripleCost != 0){
                $('#triple_grand_total_amount').val(tripleCost);   
            }else{
                $('#triple_grand_total_amount').val(0);   
            }   
        }else{
            $('#triple_grand_total_amount').val(moretripleCost);   
        }
        
        var moredoubleCost = sumData + more_double_hotel;
        moredoubleCost = moredoubleCost.toFixed(2);
        
        if(more_double_hotel == 0){
            if(doubleCost != 0){
                $('#double_grand_total_amount').val(doubleCost);   
            }else{
                $('#double_grand_total_amount').val(0);   
            }   
        }else{
            $('#double_grand_total_amount').val(moredoubleCost);
        }
    }
        
</script>

<script>

    $(".CC_id_store").change(function (){
        var CC_id_store =  $(this).find('option:selected').attr('attr_id');
        $("#conversion_type_Id").val(CC_id_store);
    });
    
    var user_hotels = {!!json_encode($user_hotels)!!};
    
    // Flights
    $("#flights_type").change(function () {
            var id = $(this).find('option:selected').attr('value');
            if(id == 'Indirect')
            {
                $("#text_editer").css("padding", "20");
                $('#flights_type_connected').fadeIn();
                $('#flights_type_connected').empty();
                var no_of_stays_Append = `<label for="no_of_stays">No Of Stays</label>
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
            for (let i = 1; i <= no_of_stays; i++) {
                var flight_Data =   `<h3 style="padding: 12px">Departure Details : </h3>
                                     <div class="row" style="padding: 12px">
                                        <div class="col-xl-4">
                                            <label for="">Departure Airport</label>
                                            <input name="more_departure_airport_code[]" id="departure_airport_code_${i}" class="form-control" autocomplete="off" placeholder="Enter Departure Location">
                                        </div>
                                        <div class="col-xl-1" style="margin-top: 25px;text-align: center;">
                                            <label for=""></label>
                                            <span id="Change_Location_${i}" class='bi bi-arrow-left-right' style="font-size: 23px;"></span>
                                        </div>
                                        <div class="col-xl-4">
                                            <label for="">Arrival Airport</label>
                                            <input name="more_arrival_airport_code[]" id="arrival_airport_code_${i}" class="form-control" autocomplete="off" placeholder="Enter Arrival Location">
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="">Airline Name</label>
                                            <input type="text" id="other_Airline_Name2_${i}" name="more_other_Airline_Name2[]" class="form-control other_airline_Name1_${i}">
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="">Class Type</label>
                                            <select  name="more_departure_Flight_Type[]" id="departure_Flight_Type_${i}" class="form-control">
                                                <option value="">Select Flight Type</option>
                                                <option value="Bussiness">Bussiness</option>
                                                <option value="Economy">Economy</option>
                                                <option value="Standard">Standard</option>
                                            </select>
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="">Flight No</label>
                                            <input type="text" id="simpleinput_${i}" name="more_departure_flight_number[]" class="form-control" placeholder="Enter Flight Number">
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="">Departure Date & Time</label>
                                            <input type="datetime-local" id="simpleinput_${i}" name="more_departure_time[]" class="form-control departure_time1_${i}" >
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="">Arrival Date and Time</label>
                                            <input type="datetime-local" id="simpleinput_${i}" name="more_arrival_time[]" class="form-control arrival_time1_${i}" >
                                        </div>
                                    </div>
                                    <div class="container total_Time_Div_data_append_${i}">
                                    </div>`;
                                    
                var return_flight_Data = `<h3 style="padding: 12px">Return Details : </h3>
                                          <div class="row" style="padding: 12px">
                                            <div class="col-xl-4">
                                                <label for="">Departure Airport</label>
                                                <input name="return_more_departure_airport_code[]" id="return_departure_airport_code_${i}" class="form-control" autocomplete="off" placeholder="Enter Return Location">
                                            </div>
                                            <div class="col-xl-1" style="margin-top: 25px;text-align: center;">
                                                <label for=""></label>
                                                <span id="return_Change_Location_${i}" class='bi bi-arrow-left-right' style="font-size: 23px;"></span>
                                            </div>
                                            <div class="col-xl-4">
                                                <label for="">Arrival Airport</label>
                                                <input name="return_more_arrival_airport_code[]" id="return_arrival_airport_code_${i}" class="form-control" autocomplete="off" placeholder="Enter Return Location">
                                                
                                            </div>
                                            <div class="col-xl-3">
                                                <label for="">Airline Name</label>
                                                <input type="text" id="return_other_Airline_Name2_${i}" name="return_more_other_Airline_Name2[]" class="form-control other_airline_Name1">
                                            </div>
                                            <div class="col-xl-3">
                                                <label for="">Class Type</label>
                                                <select  name="return_more_departure_Flight_Type[]" id="return_departure_Flight_Type_${i}" class="form-control">
                                                    <option value="">Select Flight Type</option>
                                                    <option value="Bussiness">Bussiness</option>
                                                    <option value="Economy">Economy</option>
                                                    <option value="Standart">Standart</option>
                                                </select>
                                            </div>
                                            <div class="col-xl-3">
                                                <label for="">Flight Number</label>
                                                <input type="text" id="simpleinput_${i}" name="return_more_departure_flight_number[]" class="form-control" placeholder="Enter Flight Number">
                                            </div>
                                            <div class="col-xl-3">
                                                <label for="">Departure Date and Time</label>
                                                <input type="datetime-local" id="simpleinput_${i}" name="return_more_departure_time[]" class="form-control return_departure_time1_${i}" >
                                            </div>
                                            <div class="col-xl-3">
                                                <label for="">Arrival Date and Time</label>
                                                <input type="datetime-local" id="simpleinput_${i}" name="return_more_arrival_time[]" class="form-control return_arrival_time1_${i}" >
                                            </div>
                                        </div>
                                        <div class="container return_total_Time_Div_data_append_${i}">
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
                                                        <label for="">Duration</label>
                                                        <input type="text" id="total_Time" name="more_total_Time[]" class="form-control total_Time1_${i}" style="width: 167px;">
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
                $('.return_Flight_section_append').append(return_flight_Data);
                
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
                                                        <label for="">Duration</label>
                                                        <input type="text" id="return_total_Time" name="return_more_total_Time[]" class="form-control return_total_Time1_${i}" style="width: 167px;">
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
 
    // Accomodation Invoice
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
                
                var data = `<div class="mb-2" style="border:1px solid #ced4da;padding: 20px 20px 20px 20px;" id="del_hotel${i}">
                                
                                <h4>
                                    City #${i}
                                </h4> 
                                <div class="row" style="padding-bottom: 25px;">
                                    <div class="col-xl-3"><label for="">Check In</label><input type="date" id="makkah_accomodation_check_in_${i}" name="acc_check_in[]" class="form-control makkah_accomodation_check_in_class_${i} check_in_hotel_${i}">
                                    </div><div class="col-xl-3"><label for="">Check Out</label><input type="date" id="makkah_accomodation_check_out_date_${i}"  name="acc_check_out[]" onchange="makkah_accomodation_check_out(${i})" class="form-control makkah_accomodation_check_out_date_class_${i} check_out_hotel_${i}"></div>
                                    
                                    <div class="col-xl-3">
                                        <label for="">Select City</label>
                                        <select type="text" id="property_city_new${i}" onchange="put_tour_location(${i})" name="hotel_city_name[]" class="form-control property_city_new"></select>
                                    </div>
                    
                                    <div class="col-xl-3">
                                        <label for="">Hotel Name</label>
                                        
                                        <input type="text" id="switch_hotel_name${i}" name="switch_hotel_name[]" value="1" style="display:none">
                                        
                                        <div class="input-group" id="add_hotel_div${i}">
                                            <input type="text" onchange="hotel_funI(${i})" id="acc_hotel_name_${i}" name="acc_hotel_name[]" class="form-control acc_hotel_name_class_${i}">
                                        </div>
                                        <a style="float: right;font-size: 10px;width: 102px;" onclick="select_hotel_btn(${i})" class="btn btn-primary select_hotel_btn${i}">
                                            SELECT HOTEL
                                        </a>
                                        
                                        <div class="input-group" id="select_hotel_div${i}" style="display:none">
                                            <select onchange="get_room_types(${i})" id="acc_hotel_name_${i}" name="acc_hotel_name_select[]" class="form-control acc_hotel_name_class_${i} get_room_types_${i}"></select>
                                        </div>
                                        <a style="display:none;float: right;font-size: 10px;width: 102px;" onclick="add_hotel_btn(${i})" class="btn btn-primary add_hotel_btn${i}">
                                            ADD HOTEL
                                        </a>
                                    </div>
                                    
                                    
                                    <div class="col-xl-3"><label for="">No Of Nights</label>
                                    <input readonly type="text" id="acomodation_nights_${i}" name="acc_no_of_nightst[]" class="form-control acomodation_nights_class_${i}"></div>
                                    
                                    <input readonly type="hidden" id="acc_nights_key_${i}" value="${i}" class="form-control">
                                    
                                    <div class="col-xl-3"><label for="">Room Type</label>
                                        
                                        <div class="input-group hotel_type_add_div_${i}">
                                            <select onchange="hotel_type_fun(${i})" name="acc_type[]" id="hotel_type_${i}" class="form-control other_Hotel_Type hotel_type_class_${i}"  data-placeholder="Choose ...">
                                                <option value="">Choose ...</option>
                                                <option attr="4" value="Quad">Quad</option>
                                                <option attr="3" value="Triple">Triple</option>
                                                <option attr="2" value="Double">Double</option>
                                            </select>
                                            <!-- span title="Add Hotel Type" class="input-group-btn input-group-append">
                                                <button class="btn btn-primary bootstrap-touchspin-up" data-bs-toggle="modal" data-bs-target="#hotel-type-modal" type="button">+</button>
                                            </span --!>
                                        </div>
                                        
                                        <div class="input-group hotel_type_select_div_${i}" style="display:none">
                                            <select onchange="hotel_type_funInvoice(${i})" name="acc_type_select[]" id="hotel_type_${i}" class="form-control other_Hotel_Type hotel_type_class_${i} hotel_type_select_class_${i}"  data-placeholder="Choose ...">
                                                <option value="">Choose ...</option>
                                            </select>
                                        </div>
                                        
                                    </div>
                                    <div class="col-xl-3"><label for="">Quantity</label><input type="text" id="simpleinput" name="acc_qty[]" class="form-control acc_qty_class_${i}"></div>
                                    
                                    <div class="col-xl-3">
                                        <label for="">Pax</label>
                                        <input type="text" id="simpleinput" name="acc_pax[]" class="form-control acc_pax_class_${i}" readonly>
                                    </div>
                                    
                                    <div class="col-xl-3">
                                        <label for="">Meal Type</label>
                                        <select name="hotel_meal_type[]" id="hotel_meal_type_${i}" class="form-control"  data-placeholder="Choose ...">
                                            <option value="">Choose ...</option>
                                            <option value="Room only">Room only</option>
                                            <option value="Breakfast">Breakfast</option>
                                            <option value="Lunch">Lunch</option>
                                            <option value="Dinner">Dinner</option>
                                        </select>
                                    </div>
                                    
                                    <div id="hotel_price_for_week_end_${i}" class="row"></div>
                                    
                                    <h4 class="mt-4">Purchase Price</h4>
                                    
                                        <input type="hidden" id="hotel_invoice_markup_${i}" name="hotel_invoice_markup[]">
                                        
                                        <input type="hidden" id="hotel_supplier_id_${i}" name="hotel_supplier_id[]">
                                        
                                        <input type="hidden" id="hotel_type_id_${i}" name="hotel_type_id[]">
                                        
                                        <input type="hidden" id="hotel_type_cat_${i}" name="hotel_type_cat[]">
                                        
                                        <input type="hidden" id="hotelRoom_type_id_${i}" name="hotelRoom_type_id[]">
                                        
                                        <input type="hidden" id="hotelRoom_type_idM_${i}" name="hotelRoom_type_idM[]">
                                        
                                        <div class="col-xl-4">
                                            <label for="">Price Per Room/Night</label>
                                            <div class="input-group">
                                                <span class="input-group-btn input-group-append">
                                                    <a id="" class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                                      
                                                    </a>
                                                </span>
                                                <input type="text" id="makkah_acc_room_price_${i}" onchange="makkah_acc_room_price_funsI(${i})" value="" name="price_per_room_purchase[]" class="form-control">
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4">
                                            <label for="">Price Per Person/Night</label>
                                            <div class="input-group">
                                                <span class="input-group-btn input-group-append">
                                                    <a id="" class="btn btn-primary bootstrap-touchspin-up currency_value2">
                                                     
                                                    </a>
                                                </span>
                                                <input type="text" id="makkah_acc_price_${i}" onchange="makkah_acc_price_funs(${i})" value="" name="acc_price_purchase[]" class="form-control makkah_acc_price_class_${i}">
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4"><label for="">Total Amount/Room</label>
                                             <div class="input-group">
                                                <span class="input-group-btn input-group-append">
                                                    <a id="" class="btn btn-primary bootstrap-touchspin-up currency_value3">
                                                      
                                                    </a>
                                                </span>
                                                <input readonly type="text"  id="makkah_acc_total_amount_${i}"  name="acc_total_amount_purchase[]" class="form-control makkah_acc_total_amount_class_${i}">
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-6">
                                            <label for="">Exchange Rate</label>
                                            <div class="input-group">
                                                <span class="input-group-btn input-group-append">
                                                    <a id="" class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                                      
                                                    </a>
                                                </span>
                                                <input type="text" id="exchange_rate_price_funs_${i}" onchange="exchange_rate_price_funsI(${i})" value="" name="exchange_rate_price[]" class="form-control">
                                            </div>
                                        </div>
                                            
                                        <div class="col-xl-6"></div>
                                        
                                        <h4 class="mt-4">Sale Price</h4>
                                        <div class="col-xl-4">
                                            <label for="">Price Per Room/Night</label>
                                            <div class="input-group">
                                                <span class="input-group-btn input-group-append">
                                                    <a id="" class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">
                                                      
                                                    </a>
                                                </span>
                                                <input type="text" id="price_per_room_exchange_rate_${i}" name="price_per_room_sale[]" class="form-control">
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4">
                                            <label for="">Price Per Person/Night</label>
                                            <div class="input-group">
                                                <span class="input-group-btn input-group-append">
                                                    <a id="" class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_2">
                                                      
                                                    </a>
                                                </span>
                                                <input type="text" id="price_per_person_exchange_rate_${i}"   name="acc_price[]" class="form-control makkah_acc_price_class_${i}">
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-4"><label for="">Total Amount/Room</label>
                                             <div class="input-group">
                                                <span class="input-group-btn input-group-append">
                                                    <a id="" class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_3">
                                                      
                                                    </a>
                                                </span>
                                                <input readonly type="text"  id="price_total_amout_exchange_rate_${i}"  name="acc_total_amount[]" class="form-control">
                                            </div>
                                        </div>
                    
                                    <div id="append_add_accomodation_${i}"></div>
                                    <div class="mt-2"><a href="javascript:;" onclick="add_more_accomodation_Invoice(${i})"  id="" class="btn btn-info" style="float: right;"> + Add More </a></div>
                                    
                                    <div class="col-xl-12">
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Room Amenities</label>
                                            <textarea name="hotel_whats_included[]" class="form-control" id="" cols="10" rows="0"></textarea>
                                          
                                        </div>
                                    </div>
                    
                                    <div class="col-xl-12"><label for="">Image</label><input type="file"  id=""  name="accomodation_image${j}[]" class="form-control" multiple></div>
                                    
                                    <div class="mt-2">
                                        <a href="javascript:;" onclick="remove_hotels(${i})" id="${i}" class="btn btn-danger" style="float: right;"> 
                                            Delete Hotel
                                        </a>
                                    </div>
                                    
                                </div>
                            </div>`;
      
      
                var data_cost=`<div class="row" id="costing_acc${i}" style="margin-bottom:20px;">
                
                                    <input type="hidden" id="hotel_Type_Costing" name="markup_Type_Costing[]" value="hotel_Type_Costing" class="form-control">
                                    
                                    <input type="text" name="hotel_name_markup[]" hidden id="hotel_name_markup${i}">
                                    
                                    <input type="hidden" name="acc_hotel_CityName[]" id="acc_hotel_CityName${i}">
                                    <input type="hidden" name="acc_hotel_HotelName[]" id="acc_hotel_HotelName${i}">
                                    <input type="hidden" name="acc_hotel_CheckIn[]" id="acc_hotel_CheckIn${i}">
                                    <input type="hidden" name="acc_hotel_CheckOut[]" id="acc_hotel_CheckOut${i}">
                                    <input type="hidden" name="acc_hotel_NoOfNights[]" id="acc_hotel_NoOfNights${i}">
                                    <input type="hidden" name="acc_hotel_Quantity[]" id="acc_hotel_Quantity${i}">
                                    
                                    <div class="col-xl-12">
                                        <h4 id="acc_cost_html_${i}">Accomodation Cost</h4>
                                    </div>
                            
                                    <div class="col-xl-2">
                                        <label>Room Type</label>
                                        <input type="text" id="hotel_acc_type_${i}" readonly="" name="room_type[]" class="form-control id_cot">
                                    </div>
                                    
                                    <div class="col-xl-2">
                                        <label>Price Per Room/Night</label>
                                        <div class="input-group">
                                            <input type="text" id="hotel_acc_price_per_night_${i}" readonly="" name="without_markup_price_single[]" class="form-control">    
                                            <span class="input-group-btn input-group-append">
                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-2">
                                        <label>Cost Price/Room</label>
                                        <div class="input-group">
                                            <input type="text" id="hotel_acc_price_${i}" readonly="" name="without_markup_price[]" class="form-control">
                                            <span class="input-group-btn input-group-append">
                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-2">    
                                        <label>Markup Type</label>
                                        <select name="markup_type[]" onchange="hotel_markup_typeI(${i})" id="hotel_markup_types_${i}" class="form-control">
                                            <option value="">Markup Type</option>
                                            <option value="%">Percentage</option>
                                            <option value="<?php echo $currency; ?>">Fixed Amount</option>
                                            <option value="per_Night">Per Night</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-xl-2">
                                        <label>Markup Value</label>
                                        <input type="hidden" id="" name="" class="form-control">
                                        <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                            <input type="text"  class="form-control" id="hotel_markup_${i}" name="markup[]" onkeyup="hotel_markup_funI(${i})">
                                            <span class="input-group-btn input-group-append">
                                            <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="hotel_markup_mrk_${i}" class="currency_value1">SAR</div></button>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-2" style="">
                                        <label>Exchange Rate</label>
                                        <div class="input-group">
                                            <input type="text" id="hotel_exchage_rate_per_night_${i}" readonly="" name="exchage_rate_single[]" class="form-control">
                                            <span class="input-group-btn input-group-append">
                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value1"></a>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-2" style="margin-top:10px">
                                        <label>Markup Price</label>
                                        <div class="input-group">
                                            <input type="text" id="hotel_exchage_rate_markup_total_per_night_${i}" readonly="" name="markup_total_per_night[]" class="form-control">
                                            <span class="input-group-btn input-group-append">
                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                            </span>
                                        </div> 
                                    </div>
                                    
                                    <div class="col-xl-2" style="margin-top:10px">
                                        <label>Markup Total Price</label>
                                        <div class="input-group">
                                            <input type="text" id="hotel_markup_total_${i}" name="markup_price[]" class="form-control id_cot">
                                            <span class="input-group-btn input-group-append">
                                                <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                            </span>
                                        </div> 
                                    </div>
                                    
                                </div>`;
      
                $("#append_accomodation_data_cost").append(data_cost);
              
                $("#append_accomodation").append(data);
                
                $('.acc_qty_class_'+i+'').on('change',function(){
                    
                    var switch_hotel_name = $('#switch_hotel_name'+i+'').val()
                    if(switch_hotel_name == 1){
                        var acc_qty_class = $(this).val();
                        var hotel_type = $('.hotel_type_class_'+i+'').find('option:selected').attr('attr');
                        var mult = parseFloat(acc_qty_class) * parseFloat(hotel_type);
                        $('.acc_pax_class_'+i+'').val(mult);
                    }else{
                        var acc_qty_class = $(this).val();
                        var hotel_type = $('.hotel_type_select_class_'+i+'').find('option:selected').attr('attr');
                        var mult = parseFloat(acc_qty_class) * parseFloat(hotel_type);
                        $('.acc_pax_class_'+i+'').val(mult);
                    }
                    
                    var property_city_newN  = $('#property_city_new'+i+'').find('option:selected').attr('value');
                    var start_dateN         = $('#makkah_accomodation_check_in_'+i+'').val();
                    var enddateN            = $('#makkah_accomodation_check_out_date_'+i+'').val();
                    var acomodation_nightsN = $("#acomodation_nights_"+i+'').val();
                    var acc_qty_classN      = $(".acc_qty_class_"+i+'').val();
                    var switch_hotel_name   = $('#switch_hotel_name'+i+'').val();
                    if(switch_hotel_name == 1){
                        var acc_hotel_nameN     = $('#acc_hotel_name_'+i+'').val();
                    }else{
                        var acc_hotel_nameN     = $('.get_room_types_'+i+'').val();
                    }
                    var html_data = `Accomodation Cost ${property_city_newN} <a class="btn">(Hotel Name : <b style="color: #cdc0c0;">${acc_hotel_nameN}</b>) (Quantity : <b style="color: #cdc0c0;">${acc_qty_classN}</b>) (Check in : <b style="color: #cdc0c0;">${start_dateN}</b>) (Check Out : <b style="color: #cdc0c0;">${enddateN}</b>) (Nights : <b style="color: #cdc0c0;">${acomodation_nightsN}</b>)</a>`;
                    $('#acc_cost_html_'+i+'').html(html_data);
                    
                    $('#acc_hotel_Quantity'+i+'').val(acc_qty_class);
                    
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
                
                $('#property_city_new'+i+'').on('change',function(){
                    
                    // HOTEl NAME
                    $('#add_hotel_div'+i+'').css('display','');
                    $('.select_hotel_btn'+i+'').css('display','');
                    $('#select_hotel_div'+i+'').css('display','none');
                    $('.add_hotel_btn'+i+'').css('display','none');
                    $('#acc_hotel_name_class_'+i+'').css('display','');
                    
                    // HOTEl TYPE
                    $('.hotel_type_select_div_'+i+'').css('display','none');
                    $('.hotel_type_add_div_'+i+'').css('display','');
                    $('.hotel_type_class_'+i+'').empty();
                    var dataHTC =   `<option value="">Choose ...</option>
                                    <option attr="4" value="Quad">Quad</option>
                                    <option attr="3" value="Triple">Triple</option>
                                    <option attr="2" value="Double">Double</option>`;
                    $('.hotel_type_class_'+i+'').append(dataHTC);
                    
                    $('#switch_hotel_name'+i+'').val(1);
                    $('.acc_qty_class_'+i+'').empty();
                    $('.acc_pax_class_'+i+'').empty();
                    
                    // Meal Type
                    $('#hotel_meal_type_'+i+'').empty();
                    var hote_MT_data = `<option value="">Choose ...</option>
                                        <option value="Room only">Room only</option>
                                        <option value="Breakfast">Breakfast</option>
                                        <option value="Lunch">Lunch</option>
                                        <option value="Dinner">Dinner</option>`;
                    $('#hotel_meal_type_'+i+'').append(hote_MT_data);
                    
                     // Price Section
                    $('#hotel_price_for_week_end_'+i+'').empty();
                    $('#makkah_acc_room_price_'+i+'').val('');
                    $('#makkah_acc_price_'+i+'').val('');
                    $('#makkah_acc_total_amount_'+i+'').val('');
                    $('#exchange_rate_price_funs_'+i+'').val('');
                    $('#price_per_room_exchange_rate_'+i+'').val('');
                    $('#price_per_person_exchange_rate_'+i+'').val('');
                    $('#price_total_amout_exchange_rate_'+i+'').val('');
                    
                    
                    var property_city_new = $('#property_city_new'+i+'').find('option:selected').attr('value');
                    $('.get_room_types_'+i+'').empty();
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{ url('/get_hotels_list') }}",
                        method: 'get',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "property_city_new": property_city_new,
                        },
                        success: function(result){
                            var user_hotels = result['user_hotels'];
                            $('.get_room_types_'+i+'').append('<option>Select Hotel</option>');
                            $.each(user_hotels, function(key, value) {
                                var attr_ID         = value.id;
                                var property_name   = value.property_name;
                                var data_append = `<option attr_ID=${attr_ID} value="${property_name}">${property_name}</option>`;
                                $('.get_room_types_'+i+'').append(data_append);
                            });
                        },
                        error:function(error){
                            console.log(error);
                        }
                    });
                    
                    var property_city_newN  = $('#property_city_new'+i+'').find('option:selected').attr('value');
                    var start_dateN         = $('#makkah_accomodation_check_in_'+i+'').val();
                    var enddateN            = $('#makkah_accomodation_check_out_date_'+i+'').val();
                    var acomodation_nightsN = $("#acomodation_nights_"+i+'').val();
                    var acc_qty_classN      = $(".acc_qty_class_"+i+'').val();
                    var switch_hotel_name   = $('#switch_hotel_name'+i+'').val();
                    if(switch_hotel_name == 1){
                        var acc_hotel_nameN     = $('#acc_hotel_name_'+i+'').val();
                    }else{
                        var acc_hotel_nameN     = $('.get_room_types_'+i+'').val();
                    }
                    var html_data = `Accomodation Cost ${property_city_newN} <a class="btn">(Hotel Name : <b style="color: #cdc0c0;">${acc_hotel_nameN}</b>) (Quantity : <b style="color: #cdc0c0;">${acc_qty_classN}</b>) (Check in : <b style="color: #cdc0c0;">${start_dateN}</b>) (Check Out : <b style="color: #cdc0c0;">${enddateN}</b>) (Nights : <b style="color: #cdc0c0;">${acomodation_nightsN}</b>)</a>`;
                    $('#acc_cost_html_'+i+'').html(html_data);
                    
                    $("#acc_hotel_CityName"+i+'').val(property_city_newN);
                    
                });
                
                $('.check_in_hotel_'+i+'').on('change',function(){
                    // Total Nights
                    var start_date  = $('#makkah_accomodation_check_in_'+i+'').val();
                    var enddate     = $('#makkah_accomodation_check_out_date_'+i+'').val();
                    const date1     = new Date(start_date);
                    const date2     = new Date(enddate);
                    const diffTime  = Math.abs(date2 - date1);
                    const diffDays  = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                    var diff        = (diffDays);
                    $("#acomodation_nights_"+i+'').val(diff);
                    
                    $("#acc_hotel_NoOfNights"+i+'').val(diff);
                    $("#acc_hotel_CheckIn"+i+'').val(start_date);
                    
                    // HOTEl NAME
                    $('#add_hotel_div'+i+'').css('display','');
                    $('.select_hotel_btn'+i+'').css('display','');
                    $('#select_hotel_div'+i+'').css('display','none');
                    $('.add_hotel_btn'+i+'').css('display','none');
                    $('#acc_hotel_name_class_'+i+'').css('display','');
                    
                    // HOTEl TYPE
                    // $('.get_room_types_'+i+'').empty();
                    $('.hotel_type_select_div_'+i+'').css('display','none');
                    $('.hotel_type_add_div_'+i+'').css('display','');
                    $('.hotel_type_class_'+i+'').empty();
                    var dataHTC =   `<option value="">Choose ...</option>
                                    <option attr="4" value="Quad">Quad</option>
                                    <option attr="3" value="Triple">Triple</option>
                                    <option attr="2" value="Double">Double</option>`;
                    $('.hotel_type_class_'+i+'').append(dataHTC);
                    
                    $('#switch_hotel_name'+i+'').val(1);
                    $('.acc_qty_class_'+i+'').empty();
                    $('.acc_pax_class_'+i+'').empty();
                    
                    // Meal Type
                    $('#hotel_meal_type_'+i+'').empty();
                    var hote_MT_data = `<option value="">Choose ...</option>
                                        <option value="Room only">Room only</option>
                                        <option value="Breakfast">Breakfast</option>
                                        <option value="Lunch">Lunch</option>
                                        <option value="Dinner">Dinner</option>`;
                    $('#hotel_meal_type_'+i+'').append(hote_MT_data);
                    
                     // Price Section
                    $('#hotel_price_for_week_end_'+i+'').empty();
                    $('#makkah_acc_room_price_'+i+'').val('');
                    $('#makkah_acc_price_'+i+'').val('');
                    $('#makkah_acc_total_amount_'+i+'').val('');
                    $('#exchange_rate_price_funs_'+i+'').val('');
                    $('#price_per_room_exchange_rate_'+i+'').val('');
                    $('#price_per_person_exchange_rate_'+i+'').val('');
                    $('#price_total_amout_exchange_rate_'+i+'').val('');
                    
                    var property_city_newN  = $('#property_city_new'+i+'').find('option:selected').attr('value');
                    var start_dateN         = $('#makkah_accomodation_check_in_'+i+'').val();
                    var enddateN            = $('#makkah_accomodation_check_out_date_'+i+'').val();
                    var acomodation_nightsN = $("#acomodation_nights_"+i+'').val();
                    var acc_qty_classN      = $(".acc_qty_class_"+i+'').val();
                    var switch_hotel_name   = $('#switch_hotel_name'+i+'').val();
                    if(switch_hotel_name == 1){
                        var acc_hotel_nameN     = $('#acc_hotel_name_'+i+'').val();
                    }else{
                        var acc_hotel_nameN     = $('.get_room_types_'+i+'').val();
                    }
                    var html_data = `Accomodation Cost ${property_city_newN} <a class="btn">(Hotel Name : <b style="color: #cdc0c0;">${acc_hotel_nameN}</b>) (Quantity : <b style="color: #cdc0c0;">${acc_qty_classN}</b>) (Check in : <b style="color: #cdc0c0;">${start_dateN}</b>) (Check Out : <b style="color: #cdc0c0;">${enddateN}</b>) (Nights : <b style="color: #cdc0c0;">${acomodation_nightsN}</b>)</a>`;
                    $('#acc_cost_html_'+i+'').html(html_data);
                });
                
                $('.check_out_hotel_'+i+'').on('change',function(){
                    // Total Nights
                    var start_date  = $('#makkah_accomodation_check_in_'+i+'').val();
                    var enddate     = $('#makkah_accomodation_check_out_date_'+i+'').val();
                    const date1     = new Date(start_date);
                    const date2     = new Date(enddate);
                    const diffTime  = Math.abs(date2 - date1);
                    const diffDays  = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                    var diff        = (diffDays);
                    $("#acomodation_nights_"+i+'').val(diff);
                    
                    $("#acc_hotel_NoOfNights"+i+'').val(diff);
                    $("#acc_hotel_CheckOut"+i+'').val(enddate);
                    
                    // HOTEl NAME
                    $('#add_hotel_div'+i+'').css('display','');
                    $('.select_hotel_btn'+i+'').css('display','');
                    $('#select_hotel_div'+i+'').css('display','none');
                    $('.add_hotel_btn'+i+'').css('display','none');
                    $('#acc_hotel_name_class_'+i+'').css('display','');
                    
                    // HOTEl TYPE
                    // $('.get_room_types_'+i+'').empty();
                    $('.hotel_type_select_div_'+i+'').css('display','none');
                    $('.hotel_type_add_div_'+i+'').css('display','');
                    $('.hotel_type_class_'+i+'').empty();
                    var dataHTC =   `<option value="">Choose ...</option>
                                    <option attr="4" value="Quad">Quad</option>
                                    <option attr="3" value="Triple">Triple</option>
                                    <option attr="2" value="Double">Double</option>`;
                    $('.hotel_type_class_'+i+'').append(dataHTC);
                    
                    $('#switch_hotel_name'+i+'').val(1);
                    $('.acc_qty_class_'+i+'').empty();
                    $('.acc_pax_class_'+i+'').empty();
                    
                    $('#hotel_meal_type_'+i+'').empty();
                    var hote_MT_data = `<option value="">Choose ...</option>
                                        <option value="Room only">Room only</option>
                                        <option value="Breakfast">Breakfast</option>
                                        <option value="Lunch">Lunch</option>
                                        <option value="Dinner">Dinner</option>`;
                    $('#hotel_meal_type_'+i+'').append(hote_MT_data);
                    
                    // Price Section
                    $('#hotel_price_for_week_end_'+i+'').empty();
                    $('#makkah_acc_room_price_'+i+'').val('');
                    $('#makkah_acc_price_'+i+'').val('');
                    $('#makkah_acc_total_amount_'+i+'').val('');
                    $('#exchange_rate_price_funs_'+i+'').val('');
                    $('#price_per_room_exchange_rate_'+i+'').val('');
                    $('#price_per_person_exchange_rate_'+i+'').val('');
                    $('#price_total_amout_exchange_rate_'+i+'').val('');
                    
                    var property_city_newN  = $('#property_city_new'+i+'').find('option:selected').attr('value');
                    var start_dateN         = $('#makkah_accomodation_check_in_'+i+'').val();
                    var enddateN            = $('#makkah_accomodation_check_out_date_'+i+'').val();
                    var acomodation_nightsN = $("#acomodation_nights_"+i+'').val();
                    var acc_qty_classN      = $(".acc_qty_class_"+i+'').val();
                    var switch_hotel_name   = $('#switch_hotel_name'+i+'').val();
                    if(switch_hotel_name == 1){
                        var acc_hotel_nameN     = $('#acc_hotel_name_'+i+'').val();
                    }else{
                        var acc_hotel_nameN     = $('.get_room_types_'+i+'').val();
                    }
                    var html_data = `Accomodation Cost ${property_city_newN} <a class="btn">(Hotel Name : <b style="color: #cdc0c0;">${acc_hotel_nameN}</b>) (Quantity : <b style="color: #cdc0c0;">${acc_qty_classN}</b>) (Check in : <b style="color: #cdc0c0;">${start_dateN}</b>) (Check Out : <b style="color: #cdc0c0;">${enddateN}</b>) (Nights : <b style="color: #cdc0c0;">${acomodation_nightsN}</b>)</a>`;
                    $('#acc_cost_html_'+i+'').html(html_data);
                    
                });
                
                var value_c         = $("#currency_conversion1").val();
                const usingSplit    = value_c.split(' ');
                var value_1         = usingSplit['0'];
                var value_2         = usingSplit['2'];
                $(".currency_value1").html(value_1);
                $(".currency_value_exchange_1").html(value_2);
                exchange_currency_funs(value_1,value_2);
                 
                // hotel_markup
                $('#hotel_markup_'+i+'').on('change',function(){
                    var hotel_markup_total = $('#hotel_markup_total_'+i+'').val();
                    console.log('hotel_markup_total1 :'+hotel_markup_total);
                    $('#hotel_invoice_markup_'+i+'').val(hotel_markup_total);
                });
            }
            $("#select_accomodation").slideToggle();
        }
        else{
            alert("Select Hotels Quantity");
        }
        
    });
    
    function hotel_funI(id){
        var acc_hotel_name = $('#acc_hotel_name_'+id+'').val();
        $('#hotel_name_acc_'+id+'').val(acc_hotel_name);
        $('#hotel_name_markup'+id+'').val(acc_hotel_name);
        
        var property_city_newN  = $('#property_city_new'+id+'').find('option:selected').attr('value');
        var start_dateN         = $('#makkah_accomodation_check_in_'+id+'').val();
        var enddateN            = $('#makkah_accomodation_check_out_date_'+id+'').val();
        var acomodation_nightsN = $("#acomodation_nights_"+id+'').val();
        var acc_qty_classN      = $(".acc_qty_class_"+id+'').val();
        var switch_hotel_name   = $('#switch_hotel_name'+id+'').val();
        if(switch_hotel_name == 1){
            var acc_hotel_nameN     = $('#acc_hotel_name_'+id+'').val();
        }else{
            var acc_hotel_nameN     = $('.get_room_types_'+id+'').val();
        }
        var html_data = `Accomodation Cost ${property_city_newN} <a class="btn">(Hotel Name : <b style="color: #cdc0c0;">${acc_hotel_nameN}</b>) (Quantity : <b style="color: #cdc0c0;">${acc_qty_classN}</b>) (Check in : <b style="color: #cdc0c0;">${start_dateN}</b>) (Check Out : <b style="color: #cdc0c0;">${enddateN}</b>) (Nights : <b style="color: #cdc0c0;">${acomodation_nightsN}</b>)</a>`;
        $('#acc_cost_html_'+id+'').html(html_data);
        
        console.log('acc_hotel_nameN : '+acc_hotel_nameN);
        
        $("#acc_hotel_HotelName"+id+'').val(acc_hotel_nameN);
    }
    
    function select_hotel_btn(id){
        var start_date  = $('#makkah_accomodation_check_in_'+id+'').val();
        var enddate     = $('#makkah_accomodation_check_out_date_'+id+'').val();
        
        if(start_date != null && start_date != '' && enddate != null && enddate != ''){
            $('#switch_hotel_name'+id+'').val(0);
            $('#add_hotel_div'+id+'').css('display','none');
            $('#select_hotel_div'+id+'').css('display','');
            $('.select_hotel_btn'+id+'').css('display','none');
            $('.add_hotel_btn'+id+'').css('display','');
            $('.hotel_type_add_div_'+id+'').css('display','none');
            $('.hotel_type_select_div_'+id+'').css('display','');
            
            $('.acc_qty_class_'+id+'').empty();
            $('.acc_pax_class_'+id+'').empty();
            
            $('.hotel_type_select_class_'+id+'').empty();
            
            // Meal Type
            $('#hotel_meal_type_'+id+'').empty();
            var hote_MT_data = `<option value="">Choose ...</option>
                                <option value="Room only">Room only</option>
                                <option value="Breakfast">Breakfast</option>
                                <option value="Lunch">Lunch</option>
                                <option value="Dinner">Dinner</option>`;
            $('#hotel_meal_type_'+id+'').append(hote_MT_data);
            
            // Price Section
            $('#hotel_price_for_week_end_'+id+'').empty();
            $('#makkah_acc_room_price_'+id+'').val('');
            $('#makkah_acc_price_'+id+'').val('');
            $('#makkah_acc_total_amount_'+id+'').val('');
            $('#exchange_rate_price_funs_'+id+'').val('');
            $('#price_per_room_exchange_rate_'+id+'').val('');
            $('#price_per_person_exchange_rate_'+id+'').val('');
            $('#price_total_amout_exchange_rate_'+id+'').val('');
            
        }else{
            alert('Select Date First!');
        }
    }
    
    function add_hotel_btn(id){
        $('#switch_hotel_name'+id+'').val(1);
        $('#add_hotel_div'+id+'').css('display','');
        $('#select_hotel_div'+id+'').css('display','none');
        $('.add_hotel_btn'+id+'').css('display','none');
        $('.select_hotel_btn'+id+'').css('display','');
        $('.hotel_type_add_div_'+id+'').css('display','');
        $('.hotel_type_select_div_'+id+'').css('display','none');
        
        $('.acc_qty_class_'+id+'').val('');
        $('.acc_pax_class_'+id+'').val('');
        
        // $('.acc_qty_class_'+id+'').empty();
        // $('.acc_pax_class_'+id+'').empty();
        
        $('.hotel_type_class_'+id+'').empty();
        var dataHTC =   `<option value="">Choose ...</option>
                        <option attr="4" value="Quad">Quad</option>
                        <option attr="3" value="Triple">Triple</option>
                        <option attr="2" value="Double">Double</option>`;
        
        $('.hotel_type_class_'+id+'').append(dataHTC);
        
        // Meal Type
        $('#hotel_meal_type_'+id+'').empty();
        var hote_MT_data = `<option value="">Choose ...</option>
                            <option value="Room only">Room only</option>
                            <option value="Breakfast">Breakfast</option>
                            <option value="Lunch">Lunch</option>
                            <option value="Dinner">Dinner</option>`;
        $('#hotel_meal_type_'+id+'').append(hote_MT_data);
        
        
        // Price Section
        $('#hotel_price_for_week_end_'+id+'').empty();
        $('#makkah_acc_room_price_'+id+'').val('');
        $('#makkah_acc_price_'+id+'').val('');
        $('#makkah_acc_total_amount_'+id+'').val('');
        $('#exchange_rate_price_funs_'+id+'').val('');
        $('#price_per_room_exchange_rate_'+id+'').val('');
        $('#price_per_person_exchange_rate_'+id+'').val('');
        $('#price_total_amout_exchange_rate_'+id+'').val('');
        
        $('#makkah_acc_room_price_'+id+'').attr('readonly', false);
    }
    
    function countDaysOfWeekBetweenDates(sDate,eDate){
        const startDate = new Date(sDate)
        const endDate = new Date(eDate);
        const daysOfWeekCount = { 
            0: 0,
            1: 0,
            2: 0,
            3: 0,
            4: 0,
            5: 0,
            6: 0
        };
        while (startDate < endDate) {
            daysOfWeekCount[startDate.getDay()] = daysOfWeekCount[startDate.getDay()] + 1;
            startDate.setDate(startDate.getDate() + 1);
        }
        return daysOfWeekCount;
    };
    
    var supplier_detail = {!!json_encode($supplier_detail)!!};
    
    function get_room_types(id){
        
        ids                 = $('.get_room_types_'+id+'').find('option:selected').attr('attr_ID');
        var start_date      = $('#makkah_accomodation_check_in_'+id+'').val();
        var enddate         = $('#makkah_accomodation_check_out_date_'+id+'').val();
        const weekDaysCount = countDaysOfWeekBetweenDates(start_date, enddate);
        
        var Sunday_D    = weekDaysCount[0];
        var Monday_D    = weekDaysCount[1];
        var Tuesday_D   = weekDaysCount[2];
        var Wednesday_D = weekDaysCount[3];
        var Thursday_D  = weekDaysCount[4];
        var Friday_D    = weekDaysCount[5];
        var Saturday_D  = weekDaysCount[6];
        var total_days = parseFloat(Sunday_D) + parseFloat(Monday_D) + parseFloat(Tuesday_D) + parseFloat(Wednesday_D) + parseFloat(Thursday_D) + parseFloat(Friday_D) + parseFloat(Saturday_D);
        
        console.log('Sunday_D : '+Sunday_D);
        console.log('Monday_D : '+Monday_D);
        console.log('Tuesday_D : '+Tuesday_D);
        console.log('Wednesday_D : '+Wednesday_D);
        console.log('Thursday_D : '+Thursday_D);
        console.log('Friday_D : '+Friday_D);
        console.log('Saturday_D : '+Saturday_D);
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ url('/get_rooms_list') }}",
            method: 'get',
            data: {
                "_token": "{{ csrf_token() }}",
                "id": ids,
            },
            success: function(result){
                var user_rooms              = result['user_rooms'];
                var price_All               = 0;
                var price_Single            = 0;
                var total_WeekDays          = 0;
                var total_WeekEnds          = 0;
                var price_WeekDays          = 0;
                var price_WeekEnds          = 0;
                var more_total_WeekDays     = 0;
                var more_total_WeekEnds     = 0;
                var more_price_WeekDays     = 0;
                var more_price_WeekEnds     = 0;
                if(user_rooms !== null && user_rooms !== ''){
                    $('.hotel_type_add_div_'+id+'').css('display','none');
                    $('.hotel_type_select_div_'+id+'').css('display','');
                    $('.hotel_type_select_class_'+id+'').empty();
                    $('.hotel_type_select_class_'+id+'').append('<option value="">Select Hotel Type...</option>')
                    
                    if(start_date != null && start_date != '' && enddate != null && enddate != ''){
                        $.each(user_rooms, function(key, value) {
                            var availible_from          = value.availible_from;
                            var availible_to            = value.availible_to;
                            var more_room_type_details  = value.more_room_type_details;
                            
                            if(Date.parse(start_date) >= Date.parse(availible_from) && Date.parse(enddate) <= Date.parse(availible_to)){
                                
                                var price_week_type     = value.price_week_type;
                                var room_supplier_name  = value.room_supplier_name;
                                var room_supplier_id    = value.room_supplier_name;
                                $.each(supplier_detail, function(key, supplier_detailS) {
                                    var id = supplier_detailS.id;
                                    if(id == room_supplier_name){
                                        room_supplier_name  = supplier_detailS.room_supplier_name;
                                    }
                                });
                                
                                var room_meal_type      = value.room_meal_type;
                                var hotelRoom_type_id   = value.id;
                                var hotelRoom_type_idM  = '';
                                
                                if(value.room_type_cat != null && value.room_type_cat != ''){
                                    var room_type_cat   = value.room_type_cat;
                                    var room_type_name  = value.room_type_name;
                                }else{
                                    var room_type_id    = ''
                                    var room_type_name  = ''
                                }
                                
                                if(price_week_type != null && price_week_type != ''){
                                    if(price_week_type == 'for_all_days'){
                                        var price_all_days  = value.price_all_days;
                                        var room_type_id    = value.room_type_id;
                                        if(room_type_id != null && room_type_id != ''){
                                            if(room_type_id == 'Single'){
                                                $('.hotel_type_select_class_'+id+'').append('<option attr="1" attr-Type="for_All_Days" attr-room_meal_type="'+room_meal_type+'" attr-price-all="'+price_all_days+'" attr-total_days="'+total_days+'" attr-room_supplier_name="'+room_supplier_name+'" attr-room_type_cat="'+room_type_cat+'" attr-room_type_name="'+room_type_name+'" attr-hotelRoom_type_id="'+hotelRoom_type_id+'" attr-hotelRoom_type_idM="'+hotelRoom_type_idM+'" value="Single">Single('+room_supplier_name+')</option>');
                                            }
                                            else if(room_type_id == 'Double'){
                                                $('.hotel_type_select_class_'+id+'').append('<option attr="2" attr-Type="for_All_Days" attr-room_meal_type="'+room_meal_type+'" attr-price-all="'+price_all_days+'" attr-total_days="'+total_days+'" attr-room_supplier_name="'+room_supplier_name+'" attr-room_type_cat="'+room_type_cat+'" attr-room_type_name="'+room_type_name+'" attr-hotelRoom_type_id="'+hotelRoom_type_id+'" attr-hotelRoom_type_idM="'+hotelRoom_type_idM+'" value="Double">Double('+room_supplier_name+')</option>');
                                            }
                                            else if(room_type_id == 'Triple'){
                                                $('.hotel_type_select_class_'+id+'').append('<option attr="3" attr-Type="for_All_Days" attr-room_meal_type="'+room_meal_type+'" attr-price-all="'+price_all_days+'" attr-total_days="'+total_days+'" attr-room_supplier_name="'+room_supplier_name+'" attr-room_type_cat="'+room_type_cat+'" attr-room_type_name="'+room_type_name+'" attr-hotelRoom_type_id="'+hotelRoom_type_id+'" attr-hotelRoom_type_idM="'+hotelRoom_type_idM+'" value="Triple">Triple('+room_supplier_name+')</option>');
                                            }
                                            else if(room_type_id == 'Quad'){
                                                $('.hotel_type_select_class_'+id+'').append('<option attr="4" attr-Type="for_All_Days" attr-room_meal_type="'+room_meal_type+'" attr-price-all="'+price_all_days+'" attr-total_days="'+total_days+'" attr-room_supplier_name="'+room_supplier_name+'" attr-room_type_cat="'+room_type_cat+'" attr-room_type_name="'+room_type_name+'" attr-hotelRoom_type_id="'+hotelRoom_type_id+'" attr-hotelRoom_type_idM="'+hotelRoom_type_idM+'" value="Quad">Quad('+room_supplier_name+')</option>');
                                            }
                                            else{
                                                $('.hotel_type_select_class_'+id+'').append('');
                                            }
                                        }
                                        
                                    }else{
                                        var weekdays        = value.weekdays;
                                        var weekdays_price  = value.weekdays_price;
                                        
                                        var weekends_price  = value.weekends_price;
                                        if(weekdays != null && weekdays != ''){
                                            var weekdays1       = JSON.parse(weekdays);
                                            $.each(weekdays1, function(key, weekdaysValue) {
                                                if(weekdaysValue == 'Sunday'){
                                                    price_WeekDays  = parseFloat(price_WeekDays) + parseFloat(Sunday_D) * parseFloat(weekdays_price);
                                                    total_WeekDays   = parseFloat(total_WeekDays) + parseFloat(Sunday_D);
                                                }else if(weekdaysValue == 'Monday'){
                                                    price_WeekDays  = parseFloat(price_WeekDays) + parseFloat(Monday_D) * parseFloat(weekdays_price);
                                                    total_WeekDays   = parseFloat(total_WeekDays) + parseFloat(Monday_D);
                                                }else if(weekdaysValue == 'Tuesday'){
                                                    price_WeekDays  = parseFloat(price_WeekDays) + parseFloat(Tuesday_D) * parseFloat(weekdays_price);
                                                    total_WeekDays   = parseFloat(total_WeekDays) + parseFloat(Tuesday_D);
                                                }else if(weekdaysValue == 'Wednesday'){
                                                    price_WeekDays  = parseFloat(price_WeekDays) + parseFloat(Wednesday_D) * parseFloat(weekdays_price);
                                                    total_WeekDays   = parseFloat(total_WeekDays) + parseFloat(Wednesday_D);
                                                }else if(weekdaysValue == 'Thursday'){
                                                    price_WeekDays  = parseFloat(price_WeekDays) + parseFloat(Thursday_D) * parseFloat(weekdays_price);
                                                    total_WeekDays   = parseFloat(total_WeekDays) + parseFloat(Thursday_D);
                                                }else if(weekdaysValue == 'Friday'){
                                                    price_WeekDays  = parseFloat(price_WeekDays) + parseFloat(Friday_D) * parseFloat(weekdays_price);
                                                    total_WeekDays   = parseFloat(total_WeekDays) + parseFloat(Friday_D);
                                                }else if(weekdaysValue == 'Saturday'){
                                                    price_WeekDays  = parseFloat(price_WeekDays) + parseFloat(Saturday_D) * parseFloat(weekdays_price);
                                                    total_WeekDays   = parseFloat(total_WeekDays) + parseFloat(Saturday_D);
                                                }else{
                                                    price_WeekDays   = price_WeekDays;
                                                    total_WeekDays   = total_WeekDays;
                                                }
                                            });
                                        }
                                        
                                        var weekends = value.weekends;
                                        if(weekends != null && weekends != ''){
                                            var weekends1   = JSON.parse(weekends);
                                            $.each(weekends1, function(key, weekendValue) {
                                                if(weekendValue == 'Sunday'){
                                                    price_WeekEnds  = parseFloat(price_WeekEnds) + parseFloat(Sunday_D) * parseFloat(weekends_price);
                                                    total_WeekEnds   = parseFloat(total_WeekEnds) + parseFloat(Sunday_D);
                                                }else if(weekendValue == 'Monday'){
                                                    price_WeekEnds    = parseFloat(price_WeekEnds) + parseFloat(Monday_D) * parseFloat(weekends_price);
                                                    total_WeekEnds   = parseFloat(total_WeekEnds) + parseFloat(Monday_D);
                                                }else if(weekendValue == 'Tuesday'){
                                                    price_WeekEnds   = parseFloat(price_WeekEnds) + parseFloat(Tuesday_D) * parseFloat(weekends_price);
                                                    total_WeekEnds   = parseFloat(total_WeekEnds) + parseFloat(Tuesday_D);
                                                }else if(weekendValue == 'Wednesday'){
                                                    price_WeekEnds   = parseFloat(price_WeekEnds) + parseFloat(Wednesday_D) * parseFloat(weekends_price);
                                                    total_WeekEnds   = parseFloat(total_WeekEnds) + parseFloat(Wednesday_D);
                                                }else if(weekendValue == 'Thursday'){
                                                    price_WeekEnds   = parseFloat(price_WeekEnds) + parseFloat(Thursday_D) * parseFloat(weekends_price);
                                                    total_WeekEnds   = parseFloat(total_WeekEnds) + parseFloat(Thursday_D);
                                                }else if(weekendValue == 'Friday'){
                                                    price_WeekEnds   = parseFloat(price_WeekEnds) + parseFloat(Friday_D) * parseFloat(weekends_price);
                                                    total_WeekEnds   = parseFloat(total_WeekEnds) + parseFloat(Friday_D);
                                                }else if(weekendValue == 'Saturday'){
                                                    price_WeekEnds   = parseFloat(price_WeekEnds) + parseFloat(Saturday_D) * parseFloat(weekends_price);
                                                    total_WeekEnds   = parseFloat(total_WeekEnds) + parseFloat(Saturday_D);
                                                }else{
                                                    price_WeekEnds = price_WeekEnds;
                                                    total_WeekEnds = total_WeekEnds;
                                                }
                                            });
                                        }
                                        
                                        var room_type_id    = value.room_type_id;
                                        if(room_type_id != null && room_type_id != ''){
                                            if(room_type_id == 'Single'){
                                                $('.hotel_type_select_class_'+id+'').append('<option attr="1" attr-Type="for_Week_Days" attr-room_meal_type="'+room_meal_type+'" attr-price-weekdays="'+weekdays_price+'" attr-price-weekends="'+weekends_price+'" attr-total_WeekDays="'+total_WeekDays+'" attr-total_WeekEnds="'+total_WeekEnds+'" attr-room_supplier_name="'+room_supplier_name+'" attr-room_type_cat="'+room_type_cat+'" attr-room_type_name="'+room_type_name+'" attr-hotelRoom_type_id="'+hotelRoom_type_id+'" attr-hotelRoom_type_idM="'+hotelRoom_type_idM+'" value="Single">Single('+room_supplier_name+')</option>');
                                            }
                                            else if(room_type_id == 'Double'){
                                                $('.hotel_type_select_class_'+id+'').append('<option attr="2" attr-Type="for_Week_Days" attr-room_meal_type="'+room_meal_type+'" attr-price-weekdays="'+weekdays_price+'" attr-price-weekends="'+weekends_price+'" attr-total_WeekDays="'+total_WeekDays+'" attr-total_WeekEnds="'+total_WeekEnds+'" attr-room_supplier_name="'+room_supplier_name+'" attr-room_type_cat="'+room_type_cat+'" attr-room_type_name="'+room_type_name+'" attr-hotelRoom_type_id="'+hotelRoom_type_id+'" attr-hotelRoom_type_idM="'+hotelRoom_type_idM+'" value="Double">Double('+room_supplier_name+')</option>');
                                            }
                                            else if(room_type_id == 'Triple'){
                                                $('.hotel_type_select_class_'+id+'').append('<option attr="3" attr-Type="for_Week_Days" attr-room_meal_type="'+room_meal_type+'" attr-price-weekdays="'+weekdays_price+'" attr-price-weekends="'+weekends_price+'" attr-total_WeekDays="'+total_WeekDays+'" attr-total_WeekEnds="'+total_WeekEnds+'" attr-room_supplier_name="'+room_supplier_name+'" attr-room_type_cat="'+room_type_cat+'" attr-room_type_name="'+room_type_name+'" attr-hotelRoom_type_id="'+hotelRoom_type_id+'" attr-hotelRoom_type_idM="'+hotelRoom_type_idM+'" value="Triple">Triple('+room_supplier_name+')</option>');
                                            }
                                            else if(room_type_id == 'Quad'){
                                                $('.hotel_type_select_class_'+id+'').append('<option attr="4" attr-Type="for_Week_Days" attr-room_meal_type="'+room_meal_type+'" attr-price-weekdays="'+weekdays_price+'" attr-price-weekends="'+weekends_price+'" attr-total_WeekDays="'+total_WeekDays+'" attr-total_WeekEnds="'+total_WeekEnds+'" attr-room_supplier_name="'+room_supplier_name+'" attr-room_type_cat="'+room_type_cat+'" attr-room_type_name="'+room_type_name+'" attr-hotelRoom_type_id="'+hotelRoom_type_id+'" attr-hotelRoom_type_idM="'+hotelRoom_type_idM+'" value="Quad">Quad('+room_supplier_name+')</option>');
                                            }
                                            else{
                                                $('.hotel_type_select_class_'+id+'').append('');
                                            }
                                        }
                                    }
                                }else{
                                    console.log('price_week_type is empty!')
                                }
                            }
                            
                            if(more_room_type_details != null && more_room_type_details != ''){
                                var more_room_type_details1 = JSON.parse(more_room_type_details);
                                $.each(more_room_type_details1, function(key, value1) {
                                    var more_room_av_from       = value1.more_room_av_from;
                                    var more_room_av_to         = value1.more_room_av_to;
                                    var more_room_supplier_name = value1.more_room_supplier_name;
                                    var more_room_supplier_id   = value1.more_room_supplier_name;
                                    $.each(supplier_detail, function(key, supplier_detailS) {
                                        var id = supplier_detailS.id;
                                        if(id == more_room_supplier_name){
                                            more_room_supplier_name  = supplier_detailS.room_supplier_name;
                                        }
                                    });
                                    
                                    var hotelRoom_type_id      = value.id;
                                    var hotelRoom_type_idM     = value1.room_gen_id;
                                    
                                    if(value1.more_room_type_name != null && value1.more_room_type_name != ''){
                                        var more_room_type_cat   = value1.more_room_type_id;
                                        var more_room_type_name  = value1.more_room_type_name;
                                    }else{
                                        var more_room_type_id    = ''
                                        var more_room_type_name  = ''
                                    }
                                    
                                    if(Date.parse(start_date) >= Date.parse(more_room_av_from) && Date.parse(enddate) <= Date.parse(more_room_av_to)){
                                        var more_room_meal_type = value1.more_room_meal_type;
                                        if(more_room_meal_type != null && more_room_meal_type != ''){
                                            // var more_room_meal_type1    = JSON.parse(more_room_meal_type);
                                            var more_room_meal_type1    = more_room_meal_type;
                                            var more_room_meal_type2    = more_room_meal_type1;
                                        }else{
                                            var more_room_meal_type2 = '';
                                        }
                                        
                                        var more_week_price_type = value1.more_week_price_type;
                                        if(more_week_price_type != null && more_week_price_type != ''){
                                            // var more_week_price_type1    = JSON.parse(more_week_price_type);
                                            var more_week_price_type1    = more_week_price_type;
                                            var more_week_price_type2    = more_week_price_type1;
                                            if(more_week_price_type2 == 'for_all_days'){
                                                var more_price_all_days  = value1.more_price_all_days;
                                                var more_room_type = value1.more_room_type;
                                                if(more_room_type != null && more_room_type != ''){
                                                    if(more_room_type == 'Single'){
                                                        $('.hotel_type_select_class_'+id+'').append('<option attr="1" attr-Type="more_for_All_Days" attr-room_meal_type="'+more_room_meal_type2+'" attr-price-all="'+more_price_all_days+'" attr-total_days="'+total_days+'" attr-room_supplier_name="'+more_room_supplier_name+'" attr-room_type_cat="'+more_room_type_cat+'" attr-room_type_name="'+more_room_type_name+'" attr-hotelRoom_type_id="'+hotelRoom_type_id+'" attr-hotelRoom_type_idM="'+hotelRoom_type_idM+'" value="Single">Single('+more_room_supplier_name+')</option>');
                                                    }
                                                    else if(more_room_type == 'Double'){
                                                        $('.hotel_type_select_class_'+id+'').append('<option attr="2" attr-Type="more_for_All_Days" attr-room_meal_type="'+more_room_meal_type2+'" attr-price-all="'+more_price_all_days+'" attr-total_days="'+total_days+'" attr-room_supplier_name="'+more_room_supplier_name+'" attr-room_type_cat="'+more_room_type_cat+'" attr-room_type_name="'+more_room_type_name+'" attr-hotelRoom_type_id="'+hotelRoom_type_id+'" attr-hotelRoom_type_idM="'+hotelRoom_type_idM+'" value="Double">Double('+more_room_supplier_name+')</option>');
                                                    }
                                                    else if(more_room_type == 'Triple'){
                                                        $('.hotel_type_select_class_'+id+'').append('<option attr="3" attr-Type="more_for_All_Days" attr-room_meal_type="'+more_room_meal_type2+'" attr-price-all="'+more_price_all_days+'" attr-total_days="'+total_days+'" attr-room_supplier_name="'+more_room_supplier_name+'" attr-room_type_cat="'+more_room_type_cat+'" attr-room_type_name="'+more_room_type_name+'" attr-hotelRoom_type_id="'+hotelRoom_type_id+'" attr-hotelRoom_type_idM="'+hotelRoom_type_idM+'" value="Triple">Triple('+more_room_supplier_name+')</option>');
                                                    }
                                                    else if(more_room_type == 'Quad'){
                                                        $('.hotel_type_select_class_'+id+'').append('<option attr="4" attr-Type="more_for_All_Days" attr-room_meal_type="'+more_room_meal_type2+'" attr-price-all="'+more_price_all_days+'" attr-total_days="'+total_days+'" attr-room_supplier_name="'+more_room_supplier_name+'" attr-room_type_cat="'+more_room_type_cat+'" attr-room_type_name="'+more_room_type_name+'" attr-hotelRoom_type_id="'+hotelRoom_type_id+'" attr-hotelRoom_type_idM="'+hotelRoom_type_idM+'" value="Quad">Quad('+more_room_supplier_name+')</option>');
                                                    }
                                                    else{
                                                        $('.hotel_type_select_class_'+id+'').append('');
                                                    }
                                                }
                                            }else{
                                                var more_week_end_price     = value1.more_week_end_price
                                                var more_week_days_price    = value1.more_week_days_price;
                                                
                                                var more_weekdays = value1.more_weekdays;
                                                if(more_weekdays != null && more_weekdays != ''){
                                                    var more_weekdays1          = JSON.parse(more_weekdays);
                                                    // console.log(more_weekdays1);
                                                    $.each(more_weekdays1, function(key, more_weekdaysValue) {
                                                        if(more_weekdaysValue == 'Sunday'){
                                                            more_price_WeekDays  = parseFloat(more_price_WeekDays) + parseFloat(Sunday_D) * parseFloat(more_week_days_price);
                                                            more_total_WeekDays   = parseFloat(more_total_WeekDays) + parseFloat(Sunday_D);
                                                        }else if(more_weekdaysValue == 'Monday'){
                                                            more_price_WeekDays  = parseFloat(more_price_WeekDays) + parseFloat(Monday_D) * parseFloat(more_week_days_price);
                                                            more_total_WeekDays   = parseFloat(more_total_WeekDays) + parseFloat(Monday_D);
                                                        }else if(more_weekdaysValue == 'Tuesday'){
                                                            more_price_WeekDays  = parseFloat(more_price_WeekDays) + parseFloat(Tuesday_D) * parseFloat(more_week_days_price);
                                                            more_total_WeekDays   = parseFloat(more_total_WeekDays) + parseFloat(Tuesday_D);
                                                        }else if(more_weekdaysValue == 'Wednesday'){
                                                            more_price_WeekDays  = parseFloat(more_price_WeekDays) + parseFloat(Wednesday_D) * parseFloat(more_week_days_price);
                                                            more_total_WeekDays   = parseFloat(more_total_WeekDays) + parseFloat(Wednesday_D);
                                                        }else if(more_weekdaysValue == 'Thursday'){
                                                            more_price_WeekDays  = parseFloat(more_price_WeekDays) + parseFloat(Thursday_D) * parseFloat(more_week_days_price);
                                                            more_total_WeekDays   = parseFloat(more_total_WeekDays) + parseFloat(Thursday_D);
                                                        }else if(more_weekdaysValue == 'Friday'){
                                                            more_price_WeekDays  = parseFloat(more_price_WeekDays) + parseFloat(Friday_D) * parseFloat(more_week_days_price);
                                                            more_total_WeekDays   = parseFloat(more_total_WeekDays) + parseFloat(Friday_D);
                                                        }else if(more_weekdaysValue == 'Saturday'){
                                                            more_price_WeekDays  = parseFloat(more_price_WeekDays) + parseFloat(Saturday_D) * parseFloat(more_week_days_price);
                                                            more_total_WeekDays   = parseFloat(more_total_WeekDays) + parseFloat(Saturday_D);
                                                        }else{
                                                            more_price_WeekDays  = more_price_WeekDays;
                                                            more_total_WeekDays  = more_total_WeekDays;
                                                        }
                                                    });
                                                }
                                                
                                                var more_weekend = value1.more_weekend;
                                                if(more_weekend != null && more_weekend != ''){
                                                    var more_weekend1 = JSON.parse(more_weekend);
                                                    $.each(more_weekend1, function(key, more_weekendValue) {
                                                        if(more_weekendValue == 'Sunday'){
                                                            more_price_WeekEnds  = parseFloat(more_price_WeekEnds) + parseFloat(Sunday_D) * parseFloat(more_week_end_price);
                                                            more_total_WeekEnds   = parseFloat(more_total_WeekEnds) + parseFloat(Sunday_D);
                                                        }else if(more_weekendValue == 'Monday'){
                                                            more_price_WeekEnds    = parseFloat(more_price_WeekEnds) + parseFloat(Monday_D) * parseFloat(more_week_end_price);
                                                            more_total_WeekEnds   = parseFloat(more_total_WeekEnds) + parseFloat(Monday_D);
                                                        }else if(more_weekendValue == 'Tuesday'){
                                                            more_price_WeekEnds   = parseFloat(more_price_WeekEnds) + parseFloat(Tuesday_D) * parseFloat(more_week_end_price);
                                                            more_total_WeekEnds   = parseFloat(more_total_WeekEnds) + parseFloat(Tuesday_D);
                                                        }else if(more_weekendValue == 'Wednesday'){
                                                            more_price_WeekEnds   = parseFloat(more_price_WeekEnds) + parseFloat(Wednesday_D) * parseFloat(more_week_end_price);
                                                            more_total_WeekEnds   = parseFloat(more_total_WeekEnds) + parseFloat(Wednesday_D);
                                                        }else if(more_weekendValue == 'Thursday'){
                                                            more_price_WeekEnds   = parseFloat(more_price_WeekEnds) + parseFloat(Thursday_D) * parseFloat(more_week_end_price);
                                                            more_total_WeekEnds   = parseFloat(more_total_WeekEnds) + parseFloat(Thursday_D);
                                                        }else if(more_weekendValue == 'Friday'){
                                                            more_price_WeekEnds   = parseFloat(more_price_WeekEnds) + parseFloat(Friday_D) * parseFloat(more_week_end_price);
                                                            more_total_WeekEnds   = parseFloat(more_total_WeekEnds) + parseFloat(Friday_D);
                                                        }else if(more_weekendValue == 'Saturday'){
                                                            more_price_WeekEnds   = parseFloat(more_price_WeekEnds) + parseFloat(Saturday_D) * parseFloat(more_week_end_price);
                                                            more_total_WeekEnds   = parseFloat(more_total_WeekEnds) + parseFloat(Saturday_D);
                                                        }else{
                                                            more_price_WeekEnds = more_price_WeekEnds;
                                                            more_total_WeekEnds = more_total_WeekEnds;
                                                        }
                                                    });
                                                }
                                                
                                                var more_room_type    = value1.more_room_type;
                                                if(more_room_type != null && more_room_type != ''){
                                                    if(more_room_type == 'Single'){
                                                        $('.hotel_type_select_class_'+id+'').append('<option attr="1" attr-Type="more_for_Week_Days" attr-room_meal_type="'+more_room_meal_type2+'" attr-price-weekdays="'+more_week_days_price+'" attr-price-weekends="'+more_week_end_price+'" attr-total_WeekDays="'+more_total_WeekDays+'" attr-total_WeekEnds="'+more_total_WeekEnds+'" attr-room_supplier_name="'+more_room_supplier_name+'" attr-room_type_cat="'+more_room_type_cat+'" attr-room_type_name="'+more_room_type_name+'" attr-hotelRoom_type_id="'+hotelRoom_type_id+'" attr-hotelRoom_type_idM="'+hotelRoom_type_idM+'" value="Single">Single('+more_room_supplier_name+')</option>');
                                                    }
                                                    else if(more_room_type == 'Double'){
                                                        $('.hotel_type_select_class_'+id+'').append('<option attr="2" attr-Type="more_for_Week_Days" attr-room_meal_type="'+more_room_meal_type2+'" attr-price-weekdays="'+more_week_days_price+'" attr-price-weekends="'+more_week_end_price+'" attr-total_WeekDays="'+more_total_WeekDays+'" attr-total_WeekEnds="'+more_total_WeekEnds+'" attr-room_supplier_name="'+more_room_supplier_name+'" attr-room_type_cat="'+more_room_type_cat+'" attr-room_type_name="'+more_room_type_name+'" attr-hotelRoom_type_id="'+hotelRoom_type_id+'" attr-hotelRoom_type_idM="'+hotelRoom_type_idM+'" value="Double">Double('+more_room_supplier_name+')</option>');
                                                    }
                                                    else if(more_room_type == 'Triple'){
                                                        $('.hotel_type_select_class_'+id+'').append('<option attr="3" attr-Type="more_for_Week_Days" attr-room_meal_type="'+more_room_meal_type2+'" attr-price-weekdays="'+more_week_days_price+'" attr-price-weekends="'+more_week_end_price+'" attr-total_WeekDays="'+more_total_WeekDays+'" attr-total_WeekEnds="'+more_total_WeekEnds+'" attr-room_supplier_name="'+more_room_supplier_name+'" attr-room_type_cat="'+more_room_type_cat+'" attr-room_type_name="'+more_room_type_name+'" attr-hotelRoom_type_id="'+hotelRoom_type_id+'" attr-hotelRoom_type_idM="'+hotelRoom_type_idM+'" value="Triple">Triple('+more_room_supplier_name+')</option>');
                                                    }
                                                    else if(more_room_type == 'Quad'){
                                                        $('.hotel_type_select_class_'+id+'').append('<option attr="4" attr-Type="more_for_Week_Days" attr-room_meal_type="'+more_room_meal_type2+'" attr-price-weekdays="'+more_week_days_price+'" attr-price-weekends="'+more_week_end_price+'" attr-total_WeekDays="'+more_total_WeekDays+'" attr-total_WeekEnds="'+more_total_WeekEnds+'" attr-room_supplier_name="'+more_room_supplier_name+'" attr-room_type_cat="'+more_room_type_cat+'" attr-room_type_name="'+more_room_type_name+'" attr-hotelRoom_type_id="'+hotelRoom_type_id+'" attr-hotelRoom_type_idM="'+hotelRoom_type_idM+'" value="Quad">Quad('+more_room_supplier_name+')</option>');
                                                    }
                                                    else{
                                                        $('.hotel_type_select_class_'+id+'').append('');
                                                    }
                                                }
                                                
                                                var price_WE_WD = parseFloat(more_price_WeekDays) + parseFloat(more_price_WeekEnds);
                                            }
                                        }else{
                                            console.log('more_week_price_type is empty!')
                                        }
                                    }
                                });
                                
                                // console.log(more_room_type_details1);
                            }
                            
                        });
                    }else{
                        alert('Select Date First!');
                    }
                    
                }else{
                    alert('Room are Empty');
                }
                
                var property_city_newN  = $('#property_city_new'+id+'').find('option:selected').attr('value');
                var start_dateN         = $('#makkah_accomodation_check_in_'+id+'').val();
                var enddateN            = $('#makkah_accomodation_check_out_date_'+id+'').val();
                var acomodation_nightsN = $("#acomodation_nights_"+id+'').val();
                var acc_qty_classN      = $(".acc_qty_class_"+id+'').val();
                var switch_hotel_name   = $('#switch_hotel_name'+id+'').val();
                if(switch_hotel_name == 1){
                    var acc_hotel_nameN     = $('#acc_hotel_name_'+id+'').val();
                }else{
                    var acc_hotel_nameN     = $('.get_room_types_'+id+'').val();
                }
                var html_data = `Accomodation Cost ${property_city_newN} <a class="btn">(Hotel Name : <b style="color: #cdc0c0;">${acc_hotel_nameN}</b>) (Quantity : <b style="color: #cdc0c0;">${acc_qty_classN}</b>) (Check in : <b style="color: #cdc0c0;">${start_dateN}</b>) (Check Out : <b style="color: #cdc0c0;">${enddateN}</b>) (Nights : <b style="color: #cdc0c0;">${acomodation_nightsN}</b>)</a>`;
                $('#acc_cost_html_'+id+'').html(html_data);
                
                $("#acc_hotel_HotelName"+id+'').val(acc_hotel_nameN);
            },
        });
    }
    
    function hotel_type_funInvoice(id){
        // Price Section
        $('#hotel_price_for_week_end_'+id+'').empty();
        $('#makkah_acc_room_price_'+id+'').val('');
        $('#makkah_acc_price_'+id+'').val('');
        $('#makkah_acc_total_amount_'+id+'').val('');
        $('#exchange_rate_price_funs_'+id+'').val('');
        $('#price_per_room_exchange_rate_'+id+'').val('');
        $('#price_per_person_exchange_rate_'+id+'').val('');
        $('#price_total_amout_exchange_rate_'+id+'').val('');
        
        var hotel_type = $('.hotel_type_select_class_'+id+'').val();
        $('#hotel_acc_type_'+id+'').val(hotel_type);
        $('#hotel_meal_type_'+id+'').empty();
        
        var hotel_attr_type         = $('.hotel_type_select_class_'+id+'').find('option:selected').attr('attr-type');
        var hotel_price_All         = $('.hotel_type_select_class_'+id+'').find('option:selected').attr('attr-price-all');
        var hotel_total_days        = $('.hotel_type_select_class_'+id+'').find('option:selected').attr('attr-total_days');
        var hotel_room_meal_type    = $('.hotel_type_select_class_'+id+'').find('option:selected').attr('attr-room_meal_type');
        var hotel_price_weekdays    = $('.hotel_type_select_class_'+id+'').find('option:selected').attr('attr-price-weekdays');
        var hotel_total_weekdays    = $('.hotel_type_select_class_'+id+'').find('option:selected').attr('attr-total_weekdays');
        var hotel_price_weekends    = $('.hotel_type_select_class_'+id+'').find('option:selected').attr('attr-price-weekends');
        var hotel_total_weekends    = $('.hotel_type_select_class_'+id+'').find('option:selected').attr('attr-total_weekends');
        var dataMRMT                = `<option value="${hotel_room_meal_type}">${hotel_room_meal_type}</option>`;
        
        var attr_room_supplier_name = $('.hotel_type_select_class_'+id+'').find('option:selected').attr('attr-room_supplier_name');
        var attr_room_type_cat      = $('.hotel_type_select_class_'+id+'').find('option:selected').attr('attr-room_type_cat');
        var attr_room_type_name     = $('.hotel_type_select_class_'+id+'').find('option:selected').attr('attr-room_type_name');
        var hotelRoom_type_id       = $('.hotel_type_select_class_'+id+'').find('option:selected').attr('attr-hotelRoom_type_id');
        var hotelRoom_type_idM      = $('.hotel_type_select_class_'+id+'').find('option:selected').attr('attr-hotelRoom_type_idM');
        
        if(hotel_attr_type == 'for_All_Days' || hotel_attr_type == 'more_for_All_Days'){
            var room_price = $('#makkah_acc_room_price_'+id+'').val(hotel_price_All);
            $('#hotel_meal_type_'+id+'').append(dataMRMT);
            var hotel_price_for_weekend_append  =   `<h4 class="mt-4">Price Details(For All Days)</h4>
                                                    <div class="col-xl-3">
                                                        <label for="">No of Nights</label>
                                                        <input type="text" value="${hotel_total_days}" class="form-control no_of_nights_all_days${id}" readonly>
                                                    </div>
                                                    <div class="col-xl-3">
                                                        <label for="">Price Per Room/Night</label>
                                                        <input type="text" value="${hotel_price_All}" class="form-control price_per_night_all_days${id}" readonly>
                                                    </div>`;
            $('#hotel_price_for_week_end_'+id+'').append(hotel_price_for_weekend_append);
            $('#hotel_supplier_id_'+id+'').val(attr_room_supplier_name);
            $('#hotel_type_id_'+id+'').val(attr_room_type_cat);
            $('#hotel_type_cat_'+id+'').val(attr_room_type_name);
            $('#hotelRoom_type_id_'+id+'').val(hotelRoom_type_id);
            $('#hotelRoom_type_idM_'+id+'').val(hotelRoom_type_idM);
            
        }else if(hotel_attr_type == 'for_Week_Days' || hotel_attr_type == 'more_for_Week_Days'){
            
            var hotel_type_price    = $('.hotel_type_select_class_'+id+'').val();
            if(hotel_type_price == 'Double')
            {
                hotel_type_price = 2;
            }
            else if(hotel_type_price == 'Triple')
            {
                hotel_type_price = 3;
            }
            else if(hotel_type_price == 'Quad')
            {
                hotel_type_price = 4;
            }else{
                hotel_type_price = 1;
            }
            
            var total       = parseFloat(room_price)/parseFloat(hotel_type_price);
            total           = total.toFixed(2);
            var grand_total = parseFloat(room_price) * parseFloat(acomodation_nights);
            grand_total     = grand_total.toFixed(2);
            $('#makkah_acc_price_'+id+'').val(total);
            $('#makkah_acc_total_amount_'+id+'').val(grand_total);
            
            var price_per_person_weekdays       = parseFloat(hotel_price_weekdays)/parseFloat(hotel_type_price);
            var price_per_person_weekends       = parseFloat(hotel_price_weekends)/parseFloat(hotel_type_price);
            var total_price_per_person_weekdays = parseFloat(price_per_person_weekdays) * parseFloat(hotel_total_weekdays);
            var total_price_per_person_weekends = parseFloat(price_per_person_weekends) * parseFloat(hotel_total_weekends);
            var TP_WEWD = parseFloat(total_price_per_person_weekdays) + parseFloat(total_price_per_person_weekends);
            
            var TP_WEWD1 = parseFloat(hotel_price_weekdays) + parseFloat(hotel_price_weekends);
            var room_price = $('#makkah_acc_room_price_'+id+'').val(TP_WEWD1);
            
            $('#hotel_meal_type_'+id+'').append(dataMRMT);
            var hotel_price_for_weekend_append  =   `<h4 class="mt-4">Price Details(For Weekdays)</h4>
                                                    <div class="col-xl-3">
                                                        <label for="">No of Nights</label>
                                                        <input type="text" value="${hotel_total_weekdays}" class="form-control no_of_nights_weekdays${id}" readonly>
                                                    </div>
                                                    <div class="col-xl-3">
                                                        <label for="">Price Per Room/Night</label>
                                                        <input type="text" value="${hotel_price_weekdays}" class="form-control price_per_night_weekdays${id}" readonly>
                                                    </div>
                                                    <div class="col-xl-3">
                                                        <label for="">Price Per Person/Night</label>
                                                        <input type="text" value="${price_per_person_weekdays}" class="form-control price_per_person_weekdays${id}" readonly>
                                                    </div>
                                                    <div class="col-xl-3">
                                                        <label for="">Total Amount/Per Person</label>
                                                        <input type="text" value="${total_price_per_person_weekdays}" class="form-control total_price_per_person_weekdays${id}" readonly>
                                                    </div>
                                                    <h4 class="mt-4">Price Details(For WeekEnds)</h4>
                                                    <div class="col-xl-3">
                                                        <label for="">No of Nights</label>
                                                        <input type="text" value="${hotel_total_weekends}" class="form-control no_of_nights_weekends${id}" readonly>
                                                    </div>
                                                    <div class="col-xl-3">
                                                        <label for="">Price Per Room/Night</label>
                                                        <input type="text" value="${hotel_price_weekends}" class="form-control price_per_night_weekends${id}" readonly>
                                                    </div>
                                                    <div class="col-xl-3">
                                                        <label for="">Price Per Person/Night</label>
                                                        <input type="text" value="${price_per_person_weekends}" class="form-control price_per_person_weekends${id}" readonly>
                                                    </div>
                                                    <div class="col-xl-3">
                                                        <label for="">Total Amount/Per Person</label>
                                                        <input type="text" value="${total_price_per_person_weekends}" class="form-control total_price_per_person_weekends${id}" readonly>
                                                    </div>`;
            $('#hotel_price_for_week_end_'+id+'').append(hotel_price_for_weekend_append);
            $('#hotel_supplier_id_'+id+'').val(attr_room_supplier_name);
            $('#hotel_type_id_'+id+'').val(attr_room_type_cat);
            $('#hotel_type_cat_'+id+'').val(attr_room_type_name);
            $('#hotelRoom_type_id_'+id+'').val(hotelRoom_type_id);
            $('#hotelRoom_type_idM_'+id+'').val(hotelRoom_type_idM);
        }else{
            alert('Select Room Type');
        }
        
        // Price Calculations
        var switch_hotel_name = $('#switch_hotel_name'+id+'').val();
        if(switch_hotel_name == 1){
            var hotel_type_price = $('#hotel_type_'+id+'').val();
        }else{
            var hotel_type_price = $('.hotel_type_select_class_'+id+'').val();
        }
        var room_price          = $('#makkah_acc_room_price_'+id+'').val();
        var acomodation_nights  = $('#acomodation_nights_'+id+'').val(); 
        if(hotel_type_price == 'Double')
        {
            hotel_type_price = 2;
        }
        else if(hotel_type_price == 'Triple')
        {
            hotel_type_price = 3;
        }
        else if(hotel_type_price == 'Quad')
        {
            hotel_type_price = 4;
        }else{
            hotel_type_price = 1;
        }
        
        var total       = parseFloat(room_price)/parseFloat(hotel_type_price);
        total           = total.toFixed(2);
        var grand_total = parseFloat(room_price) * parseFloat(acomodation_nights);
        grand_total     = grand_total.toFixed(2);
        $('#makkah_acc_price_'+id+'').val(total);
        $('#makkah_acc_total_amount_'+id+'').val(grand_total);
        
        $('#makkah_acc_room_price_'+id+'').attr('readonly', true);
        $('#makkah_acc_price_'+id+'').attr('readonly', true);
        $('#makkah_acc_total_amount_'+id+'').attr('readonly', true);
        
        var property_city_newN  = $('#property_city_new'+id+'').find('option:selected').attr('value');
        var start_dateN         = $('#makkah_accomodation_check_in_'+id+'').val();
        var enddateN            = $('#makkah_accomodation_check_out_date_'+id+'').val();
        var acomodation_nightsN = $("#acomodation_nights_"+id+'').val();
        var acc_qty_classN      = $(".acc_qty_class_"+id+'').val();
        var switch_hotel_name   = $('#switch_hotel_name'+id+'').val();
        if(switch_hotel_name == 1){
            var acc_hotel_nameN     = $('#acc_hotel_name_'+id+'').val();
        }else{
            var acc_hotel_nameN     = $('.get_room_types_'+id+'').val();
        }
        var html_data = `Accomodation Cost ${property_city_newN} <a class="btn">(Hotel Name : <b style="color: #cdc0c0;">${acc_hotel_nameN}</b>) (Quantity : <b style="color: #cdc0c0;">${acc_qty_classN}</b>) (Check in : <b style="color: #cdc0c0;">${start_dateN}</b>) (Check Out : <b style="color: #cdc0c0;">${enddateN}</b>) (Nights : <b style="color: #cdc0c0;">${acomodation_nightsN}</b>)</a>`;
        $('#acc_cost_html_'+id+'').html(html_data);
    }
    
    function makkah_acc_room_price_funsI(id){
        var switch_hotel_name = $('#switch_hotel_name'+id+'').val();
        if(switch_hotel_name == 1){
            var hotel_type_price = $('#hotel_type_'+id+'').val();
        }else{
            var hotel_type_price = $('.hotel_type_select_class_'+id+'').val();
        }
        var room_price          = $('#makkah_acc_room_price_'+id+'').val();
        var acomodation_nights  = $('#acomodation_nights_'+id+'').val(); 
        if(hotel_type_price == 'Double')
        {
            hotel_type_price = 2;
        }
        else if(hotel_type_price == 'Triple')
        {
            hotel_type_price = 3;
        }
        else if(hotel_type_price == 'Quad')
        {
            hotel_type_price = 4;
        }else{
            hotel_type_price = 1;
        }
        
        var total           = parseFloat(room_price)/parseFloat(hotel_type_price);
        var total1          = total.toFixed(2);
        var grand_total     = parseFloat(room_price) * parseFloat(acomodation_nights);
        var grand_total1    = grand_total.toFixed(2);
        $('#makkah_acc_price_'+id+'').val(total1);
        $('#makkah_acc_total_amount_'+id+'').val(grand_total);
    }
    
    function exchange_rate_price_funsI(id){
        var makkah_acc_room_price           = $('#makkah_acc_room_price_'+id+'').val();
        var makkah_acc_price                = $('#makkah_acc_price_'+id+'').val();
        var makkah_acc_total_amount         = $('#makkah_acc_total_amount_'+id+'').val();
        var exchange_rate_price_funs        = $('#exchange_rate_price_funs_'+id+'').val();
        $('#hotel_exchage_rate_per_night_'+id+'').val(exchange_rate_price_funs);
        
        var currency_conversion = $("#select_exchange_type").val();
        if(currency_conversion == 'Divided'){
            var price_per_room_exchangeRate=parseFloat(makkah_acc_room_price)/parseFloat(exchange_rate_price_funs);
            var price_per_person_exchangeRate=parseFloat(makkah_acc_price)/parseFloat(exchange_rate_price_funs);
            var price_total_exchangeRate=parseFloat(makkah_acc_total_amount)/parseFloat(exchange_rate_price_funs);  
        }else{
            var price_per_room_exchangeRate=parseFloat(makkah_acc_room_price) * parseFloat(exchange_rate_price_funs);
            var price_per_person_exchangeRate=parseFloat(makkah_acc_price) * parseFloat(exchange_rate_price_funs);
            var price_total_exchangeRate=parseFloat(makkah_acc_total_amount) * parseFloat(exchange_rate_price_funs);  
        }
        
        var price_per_room_exchangeRate     = price_per_room_exchangeRate.toFixed(2);
        var price_per_person_exchangeRate   = price_per_person_exchangeRate.toFixed(2);
        var price_total_exchangeRate        = price_total_exchangeRate.toFixed(2);
        $('#price_per_room_exchange_rate_'+id+'').val(price_per_room_exchangeRate);
        $('#hotel_acc_price_per_night_'+id+'').val(price_per_room_exchangeRate);
        $('#price_per_person_exchange_rate_'+id+'').val(price_per_person_exchangeRate);
        $('#price_total_amout_exchange_rate_'+id+'').val(price_total_exchangeRate);
        $('#hotel_acc_price_'+id+'').val(price_total_exchangeRate);
        
        var double_cost_price   = 0;
        var triple_cost_price   = 0;
        var quad_cost_price     = 0;
        for(var k=1; k<=20; k++){
            var price_total_amout_exchange_rate = $('#price_total_amout_exchange_rate_'+k+'').val();
            if(price_total_amout_exchange_rate != null && price_total_amout_exchange_rate != '' && price_total_amout_exchange_rate != 0){
                var more_switch_hotel_name = $('#switch_hotel_name'+k+'').val();
                if(more_switch_hotel_name != 1){
                    var hotel_type_price    = $('.hotel_type_select_class_'+k+'').val();
                }else{
                    var hotel_type_price    = $('#hotel_type_'+k+'').val();
                }
                
                if(hotel_type_price == 'Double'){
                    double_cost_price       = parseFloat(double_cost_price) + parseFloat(price_total_amout_exchange_rate);
                    var double_cost_price1  = double_cost_price.toFixed(2);
                    $('#double_cost_price').val(double_cost_price1);
                }else if(hotel_type_price == 'Triple'){
                    triple_cost_price       = parseFloat(triple_cost_price) + parseFloat(price_total_amout_exchange_rate);
                    var triple_cost_price1  = triple_cost_price.toFixed(2);
                    $('#triple_cost_price').val(triple_cost_price1);
                }else if(hotel_type_price == 'Quad'){
                    quad_cost_price         = parseFloat(quad_cost_price) + parseFloat(price_total_amout_exchange_rate);
                    var quad_cost_price1    = quad_cost_price.toFixed(2);
                    $('#quad_cost_price').val(quad_cost_price1);
                }else{
                    console.log('Hotel Type Not Found!!');
                }
            }
            else{
                console.log('Hotel Type Not Found!!');
            }
        }
        
    }
    
    function hotel_markup_typeI(id){
        var ids                             = $('#hotel_markup_types_'+id+'').val();
        var prices                          = $('#hotel_acc_price_'+id+'').val();
        var hotel_acc_price_per_night       = $('#hotel_acc_price_per_night_'+id+'').val();
        var hotel_exchage_rate_per_night    = $('#hotel_exchage_rate_per_night_'+id+'').val();
        
        add_numberElse();
        if(ids == '%'){
            $('#hotel_markup_mrk_'+id+'').text(ids);
        }else{
            var value_c         = $("#currency_conversion1").val();
            const usingSplit    = value_c.split(' ');
            var value_1         = usingSplit['0'];
            var value_2         = usingSplit['2'];
            $(".currency_value1").html(value_1);
            $(".currency_value_exchange_1").html(value_2);
            $('#hotel_markup_mrk_'+id+'').text(value_1);
        }
    }
    
    function hotel_markup_funI(id){
        var ids                             = $('#hotel_markup_types_'+id+'').val();
        var prices                          = $('#hotel_acc_price_'+id+'').val();
        var hotel_acc_price_per_night       = $('#hotel_acc_price_per_night_'+id+'').val();
        var hotel_exchage_rate_per_night    = $('#hotel_exchage_rate_per_night_'+id+'').val();
        var acomodation_nights              = $('#acomodation_nights_'+id+'').val();
        add_numberElseI();
        if(ids == '%'){
            var markup_val  =  $('#hotel_markup_'+id+'').val();
            var total1      = prices * markup_val/100;
            var total       = total1.toFixed(2);
            $('#hotel_exchage_rate_markup_total_per_night_'+id+'').val(total);
            var total2      = parseFloat(total) + parseFloat(prices);
            var total3      = total2.toFixed(2);
            $('#hotel_markup_total_'+id+'').val(total3);
            $('#hotel_invoice_markup_'+id+'').val(total3);
            add_numberElse_1I();
        }else if(ids == 'per_Night'){
            var markup_val  =  $('#hotel_markup_'+id+'').val();
            var total1      = (parseFloat(markup_val) / parseFloat(hotel_exchage_rate_per_night)) * acomodation_nights;
            var total       = total1.toFixed(2);
            $('#hotel_exchage_rate_markup_total_per_night_'+id+'').val(total);
            var total2      = parseFloat(total) + parseFloat(prices);
            var total3       = total2.toFixed(2);
            $('#hotel_markup_total_'+id+'').val(total3);
            $('#hotel_invoice_markup_'+id+'').val(total3);
            add_numberElse_1I();
        }else{
            var markup_val  =  $('#hotel_markup_'+id+'').val();
            var total1      = parseFloat(markup_val) / parseFloat(hotel_exchage_rate_per_night);
            var total       = total1.toFixed(2);
            $('#hotel_exchage_rate_markup_total_per_night_'+id+'').val(total);
            var total2      = parseFloat(total) + parseFloat(prices);
            var total3       = total2.toFixed(2);
            $('#hotel_markup_total_'+id+'').val(total3);
            $('#hotel_invoice_markup_'+id+'').val(total3);
            add_numberElse_1I();
        }
    }
    
    // More Accomodation Invoice
    var divId = 1;
    var ids = [];
    function add_more_accomodation_Invoice(id){
        
        var acc_nights_key = $('#acc_nights_key_'+id+'').val();
        
        var decodeURI_city = $('#property_city_new'+id+'').val();
    
        var data1 = `<div id="click_delete_${divId}" class="mb-2 mt-3" style="border:1px solid #ced4da;padding: 20px 20px 20px 20px;">
                        <div class="row" style="padding:20px;">
                        
                            <div class="col-xl-3">
                                <label for="">Check In</label>
                                <input type="date" id="more_makkah_accomodation_check_in_${divId}" name="more_acc_check_in[]" onchange="more_makkah_accomodation_check_in_class(${divId})" class="form-control more_date makkah_accomodation_check_in_class_${divId} more_check_in_hotel_${divId}">
                            </div>
                            
                            <div class="col-xl-3">
                                <label for="">Check Out</label>
                                <input type="date" id="more_makkah_accomodation_check_out_date_${divId}" name="more_acc_check_out[]" onchange="more_makkah_accomodation_check_out(${divId})" class="form-control more_makkah_accomodation_check_out_date_class_${divId} more_check_out_hotel_${divId}">
                            </div>
                            
                            <div class="col-xl-3">
                                <label for="">Hotel Name</label>
                                
                                <input type="text" id="more_switch_hotel_name${divId}" name="more_switch_hotel_name[]" value="1" style="display:none" class="more_switch_hotel_name">
                                
                                <div class="input-group" id="more_add_hotel_div${divId}">
                                    <input type="text" onchange="more_hotel_funI(${divId})" id="more_acc_hotel_name_${divId}" name="more_acc_hotel_name[]" class="form-control more_acc_hotel_name_class_${divId}">
                                </div>
                                <a style="float: right;font-size: 10px;width: 102px;" onclick="more_select_hotel_btn(${divId})" class="btn btn-primary more_select_hotel_btn${divId}">
                                    SELECT HOTEL
                                </a>
                                
                                <div class="input-group" id="more_select_hotel_div${divId}" style="display:none">
                                    <select onchange="more_get_room_types(${divId})" id="more_acc_hotel_name_select_${divId}" name="more_acc_hotel_name_select[]" class="form-control more_get_room_types_${divId}"></select>
                                </div>
                                <a style="display:none;float: right;font-size: 10px;width: 102px;" onclick="more_add_hotel_btn(${divId})" class="btn btn-primary more_add_hotel_btn${divId}">
                                    ADD HOTEL
                                </a>
                            </div>
                            
                            <div class="col-xl-3"><label for="">No Of Nights</label>
                                <input readonly type="text" id="more_acomodation_nights_${divId}" name="more_acc_no_of_nightst[]" class="form-control acomodation_nights_class_${divId}">
                            </div>
            
                            <input readonly type="hidden" id="acc_nights1_${divId}" value="${acc_nights_key}" class="form-control">
                            <input type='hidden' name="more_hotel_city[]" value="${decodeURI_city}" id="more_hotel_city${divId}"/>
                            <div class="col-xl-3">
                                <label for="">Room Type</label>
                        
                                <div class="input-group more_hotel_type_add_div_${divId} more_hotel_type_add_div">
                                    <select onchange="more_hotel_type_fun(${divId})" name="more_acc_type[]" id="more_hotel_type_${divId}" class="form-control other_Hotel_Type more_hotel_type_class_${divId}" data-placeholder="Choose ...">
                                        <option value="">Choose ...</option>
                                        <option attr="4" value="Quad">Quad</option>
                                        <option attr="3" value="Triple">Triple</option>
                                        <option attr="2" value="Double">Double</option>
                                    </select>
                                </div>
                            
                                <div class="input-group more_hotel_type_select_div_${divId} more_hotel_type_select_div" style="display:none">
                                    <select onchange="more_hotel_type_funInvoice(${divId})" name="more_acc_type_select[]" id="more_hotel_type_select_${divId}" class="form-control other_Hotel_Type more_hotel_type_select_class_${divId}" data-placeholder="Choose ...">
                                        <option value="">Choose ...</option>
                                    </select>
                                </div>
                            </div>
                        
                            <div class="col-xl-3"><label for="">Quantity</label><input onchange="more_acc_qty_classInvoice(${divId})" type="text" id="simpleinput" name="more_acc_qty[]" class="form-control more_acc_qty_class_${divId}"></div>
                            <div class="col-xl-3"><label for="">Pax</label><input type="text" id="simpleinput" name="more_acc_pax[]" class="form-control more_acc_pax_class_${divId}" readonly></div>
                            
                            <div class="col-xl-3">
                                <label for="">Meal Type</label>
                                <select name="more_hotel_meal_type[]" id="more_hotel_meal_type_${divId}" class="form-control more_hotel_meal_type"  data-placeholder="Choose ...">
                                    <option value="">Choose ...</option>
                                    <option value="Room only">Room only</option>
                                    <option value="Breakfast">Breakfast</option>
                                    <option value="Lunch">Lunch</option>
                                    <option value="Dinner">Dinner</option>
                                </select>
                            </div>
                        
                            <div id="more_hotel_price_for_week_end_${divId}" class="row more_hotel_price_for_week_end"></div>
                            
                            <h4 class="mt-4">Purchase Price1</h4>
                                
                            <input type="hidden" id="more_hotel_invoice_markup_${divId}" name="more_hotel_invoice_markup[]">
                            
                            <input type="hidden" id="more_hotel_supplier_id_${divId}" name="more_hotel_supplier_id[]">
                            
                            <input type="hidden" id="more_hotel_type_id_${divId}" name="more_hotel_type_id[]">
                                        
                            <input type="hidden" id="more_hotel_type_cat_${divId}" name="more_hotel_type_cat[]">
                            
                            <input type="hidden" id="more_hotelRoom_type_id_${divId}" name="more_hotelRoom_type_id[]">
                            
                            <input type="hidden" id="more_hotelRoom_type_idM_${divId}" name="more_hotelRoom_type_idM[]">
                            
                             <div class="col-xl-4">
                            <label for="">Price Per Room/Night</label>
                             <div class="input-group">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                              
                                            </a>
                                        </span>
                                <input type="text" onchange="more_makkah_acc_room_price_funsI(${divId},${id})" id="more_makkah_acc_room_price_funs_${divId}" name="more_price_per_room_purchase[]" class="form-control more_makkah_acc_room_price_funs">
                            </div>
                        
                            </div>
                            
                            <div class="col-xl-4">
                            <label for="">Price Per Person/Night</label>
                             <div class="input-group">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                              
                                            </a>
                                        </span>
                                <input type="text" onchange="more_acc_price(${divId})" id="more_acc_price_get_${divId}" name="more_acc_price_purchase[]" class="form-control more_acc_price_get">
                            </div>
                    
                            </div>
                            <div class="col-xl-4">
                            <label for="">Total Amount/Room</label>
                             <div class="input-group">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                               
                                            </a>
                                        </span>
                                <input readonly type="text" id="more_acc_total_amount_${divId}" name="more_acc_total_amount_purchase[]" class="form-control more_acc_total_amount"></div>
                            </div>
                            
                            
                            <div class="col-xl-6">
                            <label for="">Exchange Rate</label>
                             <div class="input-group">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                               
                                            </a>
                                        </span>
                                <input type="text" id="more_exchange_rate_price_funs_${divId}" onchange="more_exchange_rate_price_funsI(${divId})" name="more_exchange_rate_price[]" class="form-control more_exchange_rate_price_funs"></div>
                            </div>
                            <div class="col-xl-6">
                            </div>
                            
                            
                            <h4 class="mt-4">Sale Price</h4>
                            
                             <div class="col-xl-4">
                            <label for="">Price Per Room/Night</label>
                             <div class="input-group">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">
                                              
                                            </a>
                                        </span>
                                <input type="text" id="more_price_per_room_exchange_rate_${divId}" name="more_price_per_room_sale[]" class="form-control more_price_per_room_exchange_rate">
                            </div>
                        
                            </div>
                        
                            <div class="col-xl-4">
                            <label for="">Price Per Person/Night</label>
                             <div class="input-group">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">
                                              
                                            </a>
                                        </span>
                                <input type="text" id="more_price_per_person_exchange_rate_${divId}" name="more_acc_price[]" class="form-control more_price_per_person_exchange_rate">
                            </div>
                        
                            </div>
                            <div class="col-xl-4">
                            <label for="">Total Amount/Room</label>
                             <div class="input-group">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1">
                                              
                                            </a>
                                        </span>
                                <input readonly type="text" id="more_price_total_amout_exchange_rate_${divId}" name="more_acc_total_amount[]" class="form-control more_price_total_amout_exchange_rate"></div>
                            </div>
                            
                            
                            <div class="mt-2">
                            <a href="javascript:;"  onclick="deleteRowacc(${divId})"  id="${divId}" class="btn btn-info" style="float: right;">Delete </a></div></div>
                        </div>`;
    
    
        var data_cost = `<div style="padding-bottom: 5px;" class="row click_delete_${divId}" id="click_delete_${divId}">
                            <div class="col-xl-12">
                                <input type="text" name="more_hotel_name_markup[]" hidden id="more_hotel_name_markup${divId}">
                                <h4 class="d-none" id="">More Accomodation Cost ${decodeURI_city}</h4>
                                <h4>
                                    More Accomodation Cost ${decodeURI_city} 
                                    <a class="btn" id="more_acc_cost_html_${divId}"></a>
                                </h4>
                            </div>
                            
                            <input type="hidden" id="more_hotel_Type_Costing" name="more_markup_Type_Costing[]" value="more_hotel_Type_Costing" class="form-control">
                            
                            <input type="hidden" name="more_acc_hotel_CityName[]" id="more_acc_hotel_CityName${divId}" value="${decodeURI_city}">
                            <input type="hidden" name="more_acc_hotel_HotelName[]" id="more_acc_hotel_HotelName${divId}">
                            <input type="hidden" name="more_acc_hotel_CheckIn[]" id="more_acc_hotel_CheckIn${divId}">
                            <input type="hidden" name="more_acc_hotel_CheckOut[]" id="more_acc_hotel_CheckOut${divId}">
                            <input type="hidden" name="more_acc_hotel_NoOfNights[]" id="more_acc_hotel_NoOfNights${divId}">
                            <input type="hidden" name="more_acc_hotel_Quantity[]" id="more_acc_hotel_Quantity${divId}">
                            
                            <div class="col-xl-2">
                                <label>Room Type</label>
                                <input type="text" id="more_hotel_acc_type_${divId}" readonly="" name="more_room_type[]" class="form-control">
                            </div>
                            
                            <div class="col-xl-2">
                                <label>Price Per Room/Night</label>
                                <div class="input-group">
                                    <input type="text" id="more_hotel_acc_price_per_night_${divId}" readonly="" name="more_without_markup_price_single[]" class="form-control">
                                    <span class="input-group-btn input-group-append">
                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                    </span>        
                                </div>
                            </div>
                            
                            <div class="col-xl-2">
                                <label>Cost Price/Room</label>
                                <div class="input-group">
                                    <input type="text" id="more_hotel_acc_price_${divId}" readonly="" name="more_without_markup_price[]" class="form-control">
                                    <span class="input-group-btn input-group-append">
                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                    </span>        
                                </div>
                            </div>
                            
                            <div class="col-xl-2"> 
                                <label>Markup Type</label>
                                <select name="more_markup_type[]" onchange="more_hotel_markup_type_accI(${divId})" id="more_hotel_markup_types_${divId}" class="form-control">
                                    <option value="">Markup Type</option>
                                    <option value="%">Percentage</option>
                                    <option value="<?php echo $currency; ?>">Fixed Amount</option>
                                    <option value="per_Night">Per Night</option>
                                </select>
                            </div>
                            
                            <div class="col-xl-2">
                                <label>Markup Value</label>
                                <input type="hidden" id="" name="" class="form-control">
                                <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                    <input type="text" class="form-control" id="more_hotel_markup_${divId}" name="more_markup[]" onchange="get_markup_invoice_price(${divId})">
                                    <span class="input-group-btn input-group-append">
                                        <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="more_hotel_markup_mrk_${divId}" class="currency_value1">SAR</div></button>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-xl-2">
                                <label>Exchange Rate</label>
                                <div class="input-group">
                                    <input type="text" id="more_hotel_exchage_rate_per_night_${divId}" readonly="" name="more_exchage_rate_single[]" class="form-control">    
                                    <span class="input-group-btn input-group-append">
                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value1"></a>
                                    </span>        
                                </div>
                            </div>
                            
                            <div class="col-xl-2" style="margin-top:10px">
                                <label>Markup Price</label>
                                <div class="input-group">
                                    <input type="text" id="more_hotel_exchage_rate_markup_total_per_night_${divId}" readonly="" name="more_markup_total_per_night[]" class="form-control"> 
                                    <span class="input-group-btn input-group-append">
                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-xl-2" style="margin-top:10px">
                                <label>Markup Total Price</label>
                                <div class="input-group">
                                    <input type="text" id="more_hotel_markup_total_${divId}" name="more_markup_price[]" value="" class="form-control">
                                    <span class="input-group-btn input-group-append">
                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                    </span>
                                </div>
                            </div>
                            
                        </div>`;
    
            $("#append_accomodation_data_cost1").append(data_cost);
            $("#append_add_accomodation_"+id+'').append(data1);
            
            var places_D1 = new google.maps.places.Autocomplete(
                    document.getElementById('more_acc_hotel_name_'+divId+'')
                );
            google.maps.event.addListener(places_D1, "place_changed", function () {
                var places_D1 = places_D1.getPlace();
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
            
            var more_hotel_city = $('#more_hotel_city'+divId+'').val()
            $('#more_hotel_name_markup'+divId+'').val(more_hotel_city);
            
            var start_dateN         = $('#more_makkah_accomodation_check_in_'+id+'').val();
            var enddateN            = $('#more_makkah_accomodation_check_out_date_'+id+'').val();
            var acomodation_nightsN = $("#more_acomodation_nights_"+id+'').val();
            var acc_qty_classN      = $(".more_acc_qty_class_"+id+'').val();
            var switch_hotel_name   = $('#more_switch_hotel_name'+id+'').val();
            if(switch_hotel_name == 1){
                var acc_hotel_nameN     = $('#more_acc_hotel_name_'+id+'').val();
            }else{
                var acc_hotel_nameN     = $('.more_get_room_types_'+id+'').val();
            }
            var html_data = `(Hotel Name : <b style="color: #cdc0c0;">${acc_hotel_nameN}</b>) (Quantity : <b style="color: #cdc0c0;">${acc_qty_classN}</b>) (Check in : <b style="color: #cdc0c0;">${start_dateN}</b>) (Check Out : <b style="color: #cdc0c0;">${enddateN}</b>) (Nights : <b style="color: #cdc0c0;">${acomodation_nightsN}</b>)`;
            $('#more_acc_cost_html_'+id+'').html(html_data);
            
            divId = divId + 1;
            
            var value_c         = $("#currency_conversion1").val();
            const usingSplit    = value_c.split(' ');
            var value_1         = usingSplit['0'];
            var value_2         = usingSplit['2'];
            $(".currency_value1").html(value_1);
            $(".currency_value_exchange_1").html(value_2);
            exchange_currency_funs(value_1,value_2);
            
    }
    
    function more_hotel_funI(id){
        var acc_hotel_name = $('#more_acc_hotel_name_'+id+'').val();
        $('#more_hotel_name_acc_'+id+'').val(acc_hotel_name);
        $('#more_hotel_name_markup'+id+'').val(acc_hotel_name);
        
        var start_dateN         = $('#more_makkah_accomodation_check_in_'+id+'').val();
        var enddateN            = $('#more_makkah_accomodation_check_out_date_'+id+'').val();
        var acomodation_nightsN = $("#more_acomodation_nights_"+id+'').val();
        var acc_qty_classN      = $(".more_acc_qty_class_"+id+'').val();
        var switch_hotel_name   = $('#more_switch_hotel_name'+id+'').val();
        if(switch_hotel_name == 1){
            var acc_hotel_nameN     = $('#more_acc_hotel_name_'+id+'').val();
        }else{
            var acc_hotel_nameN     = $('.more_get_room_types_'+id+'').val();
        }
        var html_data = `(Hotel Name : <b style="color: #cdc0c0;">${acc_hotel_nameN}</b>) (Quantity : <b style="color: #cdc0c0;">${acc_qty_classN}</b>) (Check in : <b style="color: #cdc0c0;">${start_dateN}</b>) (Check Out : <b style="color: #cdc0c0;">${enddateN}</b>) (Nights : <b style="color: #cdc0c0;">${acomodation_nightsN}</b>)`;
        $('#more_acc_cost_html_'+id+'').html(html_data);
        
        $("#more_acc_hotel_HotelName"+id+'').val(acc_hotel_nameN);
    }
    
    function more_makkah_accomodation_check_in_class(id){
        var start_date  = $('#more_makkah_accomodation_check_in_'+id+'').val();
        var enddate     = $('#more_makkah_accomodation_check_out_date_'+id+'').val();
        const date1     = new Date(start_date);
        const date2     = new Date(enddate);
        const diffTime = Math.abs(date2 - date1);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
        var diff=(diffDays);
        $("#more_acomodation_nights_"+id+'').val(diff);
        
        $("#more_acc_hotel_NoOfNights"+id+'').val(diff);
        $("#more_acc_hotel_CheckIn"+id+'').val(start_date);
        
        var start_dateN         = $('#more_makkah_accomodation_check_in_'+id+'').val();
        var enddateN            = $('#more_makkah_accomodation_check_out_date_'+id+'').val();
        var acomodation_nightsN = $("#more_acomodation_nights_"+id+'').val();
        var acc_qty_classN      = $(".more_acc_qty_class_"+id+'').val();
        var switch_hotel_name   = $('#more_switch_hotel_name'+id+'').val();
        if(switch_hotel_name == 1){
            var acc_hotel_nameN     = $('#more_acc_hotel_name_'+id+'').val();
        }else{
            var acc_hotel_nameN     = $('.more_get_room_types_'+id+'').val();
        }
        var html_data = `(Hotel Name : <b style="color: #cdc0c0;">${acc_hotel_nameN}</b>) (Quantity : <b style="color: #cdc0c0;">${acc_qty_classN}</b>) (Check in : <b style="color: #cdc0c0;">${start_dateN}</b>) (Check Out : <b style="color: #cdc0c0;">${enddateN}</b>) (Nights : <b style="color: #cdc0c0;">${acomodation_nightsN}</b>)`;
        $('#more_acc_cost_html_'+id+'').html(html_data);
    }
    
    function more_makkah_accomodation_check_out(id){
        var start_date  = $('#more_makkah_accomodation_check_in_'+id+'').val();
        var enddate     = $('#more_makkah_accomodation_check_out_date_'+id+'').val();
        const date1     = new Date(start_date);
        const date2     = new Date(enddate);
        const diffTime = Math.abs(date2 - date1);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
        var diff=(diffDays);
        $("#more_acomodation_nights_"+id+'').val(diff);
        
        $("#more_acc_hotel_NoOfNights"+id+'').val(diff);
        $("#more_acc_hotel_CheckOut"+id+'').val(enddate);
        
        var start_dateN         = $('#more_makkah_accomodation_check_in_'+id+'').val();
        var enddateN            = $('#more_makkah_accomodation_check_out_date_'+id+'').val();
        var acomodation_nightsN = $("#more_acomodation_nights_"+id+'').val();
        var acc_qty_classN      = $(".more_acc_qty_class_"+id+'').val();
        var switch_hotel_name   = $('#more_switch_hotel_name'+id+'').val();
        if(switch_hotel_name == 1){
            var acc_hotel_nameN     = $('#more_acc_hotel_name_'+id+'').val();
        }else{
            var acc_hotel_nameN     = $('.more_get_room_types_'+id+'').val();
        }
        var html_data = `(Hotel Name : <b style="color: #cdc0c0;">${acc_hotel_nameN}</b>) (Quantity : <b style="color: #cdc0c0;">${acc_qty_classN}</b>) (Check in : <b style="color: #cdc0c0;">${start_dateN}</b>) (Check Out : <b style="color: #cdc0c0;">${enddateN}</b>) (Nights : <b style="color: #cdc0c0;">${acomodation_nightsN}</b>)`;
        $('#more_acc_cost_html_'+id+'').html(html_data);
    }
    
    function more_select_hotel_btn(id){
        var start_date  = $('#more_makkah_accomodation_check_in_'+id+'').val();
        var enddate     = $('#more_makkah_accomodation_check_out_date_'+id+'').val();
        
        if(start_date != null && start_date != '' && enddate != null && enddate != ''){
            var more_hotel_city = $('#more_hotel_city'+id+'').val()
            $('.more_get_room_types_'+id+'').empty();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ url('/get_hotels_list') }}",
                method: 'get',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "property_city_new": more_hotel_city,
                },
                success: function(result){
                    var user_hotels = result['user_hotels'];
                    $('.more_get_room_types_'+id+'').append('<option>Select Hotel</option>');
                    $.each(user_hotels, function(key, value) {
                        var attr_ID         = value.id;
                        var property_name   = value.property_name;
                        var data_append = `<option attr_ID=${attr_ID} value="${property_name}">${property_name}</option>`;
                        $('.more_get_room_types_'+id+'').append(data_append);
                    });
                },
                error:function(error){
                    console.log(error);
                }
            });
            
            // More Switch
            $('#more_switch_hotel_name'+id+'').val(0);
            $('#more_add_hotel_div'+id+'').css('display','none');
            $('.more_select_hotel_btn'+id+'').css('display','none');
            $('#more_select_hotel_div'+id+'').css('display','');
            $('.more_add_hotel_btn'+id+'').css('display','');
            $('.more_hotel_type_add_div_'+id+'').css('display','none');
            $('.more_hotel_type_select_div_'+id+'').css('display','');
            $('#more_hotel_price_for_week_end_'+id+'').empty();
            
        }else{
            alert('Select Date First!');
        }
    }
    
    function more_add_hotel_btn(id){
        var start_date  = $('#more_makkah_accomodation_check_in_'+id+'').val();
        var enddate     = $('#more_makkah_accomodation_check_out_date_'+id+'').val();
        
        if(start_date != null && start_date != '' && enddate != null && enddate != ''){
            $('#more_switch_hotel_name'+id+'').val(1);
            $('#more_add_hotel_div'+id+'').css('display','');
            $('.more_select_hotel_btn'+id+'').css('display','');
            $('#more_select_hotel_div'+id+'').css('display','none');
            $('.more_add_hotel_btn'+id+'').css('display','none');
            $('.more_hotel_type_add_div_'+id+'').css('display','');
            $('.more_hotel_type_select_div_'+id+'').css('display','none');
            
            // $('.acc_qty_class_'+id+'').val('');
            // $('.acc_pax_class_'+id+'').val('');
            
            // // More Room Type
            $('#more_hotel_meal_type_'+id+'').empty();
            var hote_MT_data = `<option value="">Choose ...</option>
                                <option value="Room only">Room only</option>
                                <option value="Breakfast">Breakfast</option>
                                <option value="Lunch">Lunch</option>
                                <option value="Dinner">Dinner</option>`;
            $('#more_hotel_meal_type_'+id+'').append(hote_MT_data);
            
            // More Price Section
            $('#more_makkah_acc_room_price_funs_'+id+'').val('');
            $('#more_makkah_acc_room_price_funs_'+id+'').attr('readonly', false);
            $('#more_acc_price_get_'+id+'').val('');
            $('#more_acc_total_amount_'+id+'').val('');
            $('#more_exchange_rate_price_funs_'+id+'').val('');
            $('#more_price_per_room_exchange_rate_'+id+'').val('');
            $('#more_price_per_person_exchange_rate_'+id+'').val('');
            $('#more_price_total_amout_exchange_rate_'+id+'').val('');
            
        }else{
            alert('Select Date First!');
        }
    }
    
    // var i_more_hotel = 1;
    function more_get_room_types(id){
        ids                 = $('.more_get_room_types_'+id+'').find('option:selected').attr('attr_ID');
        var start_date      = $('#more_makkah_accomodation_check_in_'+id+'').val();
        var enddate         = $('#more_makkah_accomodation_check_out_date_'+id+'').val();
        const weekDaysCount = countDaysOfWeekBetweenDates(start_date, enddate);
        
        var Sunday_D    = weekDaysCount[0];
        var Monday_D    = weekDaysCount[1];
        var Tuesday_D   = weekDaysCount[2];
        var Wednesday_D = weekDaysCount[3];
        var Thursday_D  = weekDaysCount[4];
        var Friday_D    = weekDaysCount[5];
        var Saturday_D  = weekDaysCount[6];
        var total_days = parseFloat(Sunday_D) + parseFloat(Monday_D) + parseFloat(Tuesday_D) + parseFloat(Wednesday_D) + parseFloat(Thursday_D) + parseFloat(Friday_D) + parseFloat(Saturday_D);
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ url('/get_rooms_list') }}",
            method: 'get',
            data: {
                "_token": "{{ csrf_token() }}",
                "id": ids,
            },
            success: function(result){
                var user_rooms              = result['user_rooms'];
                var price_All               = 0;
                var price_Single            = 0;
                var total_WeekDays          = 0;
                var total_WeekEnds          = 0;
                var price_WeekDays          = 0;
                var price_WeekEnds          = 0;
                var more_total_WeekDays     = 0;
                var more_total_WeekEnds     = 0;
                var more_price_WeekDays     = 0;
                var more_price_WeekEnds     = 0;
                if(user_rooms !== null && user_rooms !== ''){
                    $('.more_hotel_type_add_div_'+id+'').css('display','none');
                    $('.more_hotel_type_select_div_'+id+'').css('display','');
                    $('.more_hotel_type_select_class_'+id+'').empty();
                    $('.more_hotel_type_select_class_'+id+'').append('<option value="">Select Hotel Type...</option>')
                    
                    if(start_date != null && start_date != '' && enddate != null && enddate != ''){
                        $.each(user_rooms, function(key, value) {
                            var availible_from          = value.availible_from;
                            var availible_to            = value.availible_to;
                            var more_room_type_details  = value.more_room_type_details;
                            
                            if(Date.parse(start_date) >= Date.parse(availible_from) && Date.parse(enddate) <= Date.parse(availible_to)){
                                
                                var price_week_type     = value.price_week_type;
                                var room_supplier_name  = value.room_supplier_name;
                                var room_supplier_id    = value.room_supplier_name;
                                $.each(supplier_detail, function(key, supplier_detailS) {
                                    var id = supplier_detailS.id;
                                    if(id == room_supplier_name){
                                        room_supplier_name  = supplier_detailS.room_supplier_name;
                                    }
                                });
                                
                                var room_meal_type      = value.room_meal_type;
                                var more_hotelRoom_type_id = value.id;
                                var more_hotelRoom_type_idM = '';
                                
                                if(value.room_type_cat != null && value.room_type_cat != ''){
                                    var room_type_cat   = value.room_type_cat;
                                    var room_type_name  = value.room_type_name;
                                }else{
                                    var room_type_id    = ''
                                    var room_type_name  = ''
                                }
                                
                                if(price_week_type != null && price_week_type != ''){
                                    if(price_week_type == 'for_all_days'){
                                        var price_all_days  = value.price_all_days;
                                        var room_type_id    = value.room_type_id;
                                        if(room_type_id != null && room_type_id != ''){
                                            if(room_type_id == 'Single'){
                                                $('.more_hotel_type_select_class_'+id+'').append('<option attr="1" attr-Type="for_All_Days" attr-room_meal_type="'+room_meal_type+'" attr-price-all="'+price_all_days+'" attr-total_days="'+total_days+'" attr-more_room_supplier_name="'+room_supplier_name+'" attr-more_room_type_cat="'+room_type_cat+'" attr-more_room_type_name="'+room_type_name+'" attr-more_hotelRoom_type_id="'+more_hotelRoom_type_id+'" attr-more_hotelRoom_type_idM="'+more_hotelRoom_type_idM+'" value="Single">Single('+room_supplier_name+')</option>');
                                            }
                                            else if(room_type_id == 'Double'){
                                                $('.more_hotel_type_select_class_'+id+'').append('<option attr="2" attr-Type="for_All_Days" attr-room_meal_type="'+room_meal_type+'" attr-price-all="'+price_all_days+'" attr-total_days="'+total_days+'" attr-more_room_supplier_name="'+room_supplier_name+'" attr-more_room_type_cat="'+room_type_cat+'" attr-more_room_type_name="'+room_type_name+'" attr-more_hotelRoom_type_id="'+more_hotelRoom_type_id+'" attr-more_hotelRoom_type_idM="'+more_hotelRoom_type_idM+'" value="Double">Double('+room_supplier_name+')</option>');
                                            }
                                            else if(room_type_id == 'Triple'){
                                                $('.more_hotel_type_select_class_'+id+'').append('<option attr="3" attr-Type="for_All_Days" attr-room_meal_type="'+room_meal_type+'" attr-price-all="'+price_all_days+'" attr-total_days="'+total_days+'" attr-more_room_supplier_name="'+room_supplier_name+'" attr-more_room_type_cat="'+room_type_cat+'" attr-more_room_type_name="'+room_type_name+'" attr-more_hotelRoom_type_id="'+more_hotelRoom_type_id+'" attr-more_hotelRoom_type_idM="'+more_hotelRoom_type_idM+'" value="Triple">Triple('+room_supplier_name+')</option>');
                                            }
                                            else if(room_type_id == 'Quad'){
                                                $('.more_hotel_type_select_class_'+id+'').append('<option attr="4" attr-Type="for_All_Days" attr-room_meal_type="'+room_meal_type+'" attr-price-all="'+price_all_days+'" attr-total_days="'+total_days+'" attr-more_room_supplier_name="'+room_supplier_name+'" attr-more_room_type_cat="'+room_type_cat+'" attr-more_room_type_name="'+room_type_name+'" attr-more_hotelRoom_type_id="'+more_hotelRoom_type_id+'" attr-more_hotelRoom_type_idM="'+more_hotelRoom_type_idM+'" value="Quad">Quad('+room_supplier_name+')</option>');
                                            }
                                            else{
                                                $('.more_hotel_type_select_class_'+id+'').append('');
                                            }
                                        }
                                        
                                    }else{
                                        var weekdays        = value.weekdays;
                                        var weekdays_price  = value.weekdays_price;
                                        
                                        var weekends_price  = value.weekends_price;
                                        if(weekdays != null && weekdays != ''){
                                            var weekdays1       = JSON.parse(weekdays);
                                            $.each(weekdays1, function(key, weekdaysValue) {
                                                if(weekdaysValue == 'Sunday'){
                                                    price_WeekDays  = parseFloat(price_WeekDays) + parseFloat(Sunday_D) * parseFloat(weekdays_price);
                                                    total_WeekDays   = parseFloat(total_WeekDays) + parseFloat(Sunday_D);
                                                }else if(weekdaysValue == 'Monday'){
                                                    price_WeekDays  = parseFloat(price_WeekDays) + parseFloat(Monday_D) * parseFloat(weekdays_price);
                                                    total_WeekDays   = parseFloat(total_WeekDays) + parseFloat(Monday_D);
                                                }else if(weekdaysValue == 'Tuesday'){
                                                    price_WeekDays  = parseFloat(price_WeekDays) + parseFloat(Tuesday_D) * parseFloat(weekdays_price);
                                                    total_WeekDays   = parseFloat(total_WeekDays) + parseFloat(Tuesday_D);
                                                }else if(weekdaysValue == 'Wednesday'){
                                                    price_WeekDays  = parseFloat(price_WeekDays) + parseFloat(Wednesday_D) * parseFloat(weekdays_price);
                                                    total_WeekDays   = parseFloat(total_WeekDays) + parseFloat(Wednesday_D);
                                                }else if(weekdaysValue == 'Thursday'){
                                                    price_WeekDays  = parseFloat(price_WeekDays) + parseFloat(Thursday_D) * parseFloat(weekdays_price);
                                                    total_WeekDays   = parseFloat(total_WeekDays) + parseFloat(Thursday_D);
                                                }else if(weekdaysValue == 'Friday'){
                                                    price_WeekDays  = parseFloat(price_WeekDays) + parseFloat(Friday_D) * parseFloat(weekdays_price);
                                                    total_WeekDays   = parseFloat(total_WeekDays) + parseFloat(Friday_D);
                                                }else if(weekdaysValue == 'Saturday'){
                                                    price_WeekDays  = parseFloat(price_WeekDays) + parseFloat(Saturday_D) * parseFloat(weekdays_price);
                                                    total_WeekDays   = parseFloat(total_WeekDays) + parseFloat(Saturday_D);
                                                }else{
                                                    price_WeekDays   = price_WeekDays;
                                                    total_WeekDays   = total_WeekDays;
                                                }
                                            });
                                        }
                                        
                                        var weekends = value.weekends;
                                        if(weekends != null && weekends != ''){
                                            var weekends1       = JSON.parse(weekends);
                                            $.each(weekends1, function(key, weekendValue) {
                                                if(weekendValue == 'Sunday'){
                                                    price_WeekEnds  = parseFloat(price_WeekEnds) + parseFloat(Sunday_D) * parseFloat(weekends_price);
                                                    total_WeekEnds   = parseFloat(total_WeekEnds) + parseFloat(Sunday_D);
                                                }else if(weekendValue == 'Monday'){
                                                    price_WeekEnds    = parseFloat(price_WeekEnds) + parseFloat(Monday_D) * parseFloat(weekends_price);
                                                    total_WeekEnds   = parseFloat(total_WeekEnds) + parseFloat(Monday_D);
                                                }else if(weekendValue == 'Tuesday'){
                                                    price_WeekEnds   = parseFloat(price_WeekEnds) + parseFloat(Tuesday_D) * parseFloat(weekends_price);
                                                    total_WeekEnds   = parseFloat(total_WeekEnds) + parseFloat(Tuesday_D);
                                                }else if(weekendValue == 'Wednesday'){
                                                    price_WeekEnds   = parseFloat(price_WeekEnds) + parseFloat(Wednesday_D) * parseFloat(weekends_price);
                                                    total_WeekEnds   = parseFloat(total_WeekEnds) + parseFloat(Wednesday_D);
                                                }else if(weekendValue == 'Thursday'){
                                                    price_WeekEnds   = parseFloat(price_WeekEnds) + parseFloat(Thursday_D) * parseFloat(weekends_price);
                                                    total_WeekEnds   = parseFloat(total_WeekEnds) + parseFloat(Thursday_D);
                                                }else if(weekendValue == 'Friday'){
                                                    price_WeekEnds   = parseFloat(price_WeekEnds) + parseFloat(Friday_D) * parseFloat(weekends_price);
                                                    total_WeekEnds   = parseFloat(total_WeekEnds) + parseFloat(Friday_D);
                                                }else if(weekendValue == 'Saturday'){
                                                    price_WeekEnds   = parseFloat(price_WeekEnds) + parseFloat(Saturday_D) * parseFloat(weekends_price);
                                                    total_WeekEnds   = parseFloat(total_WeekEnds) + parseFloat(Saturday_D);
                                                }else{
                                                    price_WeekEnds = price_WeekEnds;
                                                    total_WeekEnds = total_WeekEnds;
                                                }
                                            });
                                        }
                                        
                                        var room_type_id    = value.room_type_id;
                                        if(room_type_id != null && room_type_id != ''){
                                            if(room_type_id == 'Single'){
                                                $('.more_hotel_type_select_class_'+id+'').append('<option attr="1" attr-Type="for_Week_Days" attr-room_meal_type="'+room_meal_type+'" attr-price-weekdays="'+weekdays_price+'" attr-price-weekends="'+weekends_price+'" attr-total_WeekDays="'+total_WeekDays+'" attr-total_WeekEnds="'+total_WeekEnds+'" attr-more_room_supplier_name="'+room_supplier_name+'" attr-more_room_type_cat="'+room_type_cat+'" attr-more_room_type_name="'+room_type_name+'" attr-more_hotelRoom_type_id="'+more_hotelRoom_type_id+'" attr-more_hotelRoom_type_idM="'+more_hotelRoom_type_idM+'" value="Single">Single('+room_supplier_name+')</option>');
                                            }
                                            else if(room_type_id == 'Double'){
                                                $('.more_hotel_type_select_class_'+id+'').append('<option attr="2" attr-Type="for_Week_Days" attr-room_meal_type="'+room_meal_type+'" attr-price-weekdays="'+weekdays_price+'" attr-price-weekends="'+weekends_price+'" attr-total_WeekDays="'+total_WeekDays+'" attr-total_WeekEnds="'+total_WeekEnds+'" attr-more_room_supplier_name="'+room_supplier_name+'" attr-more_room_type_cat="'+room_type_cat+'" attr-more_room_type_name="'+room_type_name+'" attr-more_hotelRoom_type_id="'+more_hotelRoom_type_id+'" attr-more_hotelRoom_type_idM="'+more_hotelRoom_type_idM+'" value="Double">Double('+room_supplier_name+')</option>');
                                            }
                                            else if(room_type_id == 'Triple'){
                                                $('.more_hotel_type_select_class_'+id+'').append('<option attr="3" attr-Type="for_Week_Days" attr-room_meal_type="'+room_meal_type+'" attr-price-weekdays="'+weekdays_price+'" attr-price-weekends="'+weekends_price+'" attr-total_WeekDays="'+total_WeekDays+'" attr-total_WeekEnds="'+total_WeekEnds+'" attr-more_room_supplier_name="'+room_supplier_name+'" attr-more_room_type_cat="'+room_type_cat+'" attr-more_room_type_name="'+room_type_name+'" attr-more_hotelRoom_type_id="'+more_hotelRoom_type_id+'" attr-more_hotelRoom_type_idM="'+more_hotelRoom_type_idM+'" value="Triple">Triple('+room_supplier_name+')</option>');
                                            }
                                            else if(room_type_id == 'Quad'){
                                                $('.more_hotel_type_select_class_'+id+'').append('<option attr="4" attr-Type="for_Week_Days" attr-room_meal_type="'+room_meal_type+'" attr-price-weekdays="'+weekdays_price+'" attr-price-weekends="'+weekends_price+'" attr-total_WeekDays="'+total_WeekDays+'" attr-total_WeekEnds="'+total_WeekEnds+'" attr-more_room_supplier_name="'+room_supplier_name+'" attr-more_room_type_cat="'+room_type_cat+'" attr-more_room_type_name="'+room_type_name+'" attr-more_hotelRoom_type_id="'+more_hotelRoom_type_id+'" attr-more_hotelRoom_type_idM="'+more_hotelRoom_type_idM+'" value="Quad">Quad('+room_supplier_name+')</option>');
                                            }
                                            else{
                                                $('.more_hotel_type_select_class_'+id+'').append('');
                                            }
                                        }
                                    }
                                }else{
                                    console.log('price_week_type is empty!')
                                }
                            }
                            
                            if(more_room_type_details != null && more_room_type_details != ''){
                                var more_room_type_details1 = JSON.parse(more_room_type_details);
                                $.each(more_room_type_details1, function(key, value1) {
                                    var more_room_av_from       = value1.more_room_av_from;
                                    var more_room_av_to         = value1.more_room_av_to;
                                    var more_room_supplier_name = value1.more_room_supplier_name
                                    var more_room_supplier_id   = value1.more_room_supplier_name;
                                    $.each(supplier_detail, function(key, supplier_detailS) {
                                        var id = supplier_detailS.id;
                                        if(id == more_room_supplier_name){
                                            more_room_supplier_name  = supplier_detailS.room_supplier_name;
                                        }
                                    });
                                    
                                    var more_hotelRoom_type_id  = value.id;
                                    var more_hotelRoom_type_idM = value1.room_gen_id;
                                    
                                    if(value1.more_room_type_name != null && value1.more_room_type_name != ''){
                                        var more_room_type_cat   = value1.more_room_type_id;
                                        var more_room_type_name  = value1.more_room_type_name;
                                    }else{
                                        var room_type_id    = ''
                                        var more_room_type_name  = ''
                                    }
                                    
                                    if(Date.parse(start_date) >= Date.parse(more_room_av_from) && Date.parse(enddate) <= Date.parse(more_room_av_to)){
                                        var more_room_meal_type = value1.more_room_meal_type;
                                        if(more_room_meal_type != null && more_room_meal_type != ''){
                                            // var more_room_meal_type1    = JSON.parse(more_room_meal_type);
                                            var more_room_meal_type1    = more_room_meal_type;
                                            var more_room_meal_type2    = more_room_meal_type1;
                                        }else{
                                            var more_room_meal_type2 = '';
                                        }
                                        
                                        var more_week_price_type = value1.more_week_price_type;
                                        if(more_week_price_type != null && more_week_price_type != ''){
                                            // var more_week_price_type1    = JSON.parse(more_week_price_type);
                                            var more_week_price_type1    = more_week_price_type;
                                            var more_week_price_type2    = more_week_price_type1;
                                            if(more_week_price_type2 == 'for_all_days'){
                                                var more_price_all_days  = value1.more_price_all_days;
                                                var more_room_type = value1.more_room_type;
                                                if(more_room_type != null && more_room_type != ''){
                                                    if(more_room_type == 'Single'){
                                                        $('.more_hotel_type_select_class_'+id+'').append('<option attr="1" attr-Type="more_for_All_Days" attr-room_meal_type="'+more_room_meal_type2+'" attr-price-all="'+more_price_all_days+'" attr-total_days="'+total_days+'" attr-more_room_supplier_name="'+more_room_supplier_name+'" attr-more_room_type_cat="'+more_room_type_cat+'" attr-more_room_type_name="'+more_room_type_name+'" attr-more_hotelRoom_type_id="'+more_hotelRoom_type_id+'" attr-more_hotelRoom_type_idM="'+more_hotelRoom_type_idM+'" value="Single">Single('+more_room_supplier_name+')</option>');
                                                    }
                                                    else if(more_room_type == 'Double'){
                                                        $('.more_hotel_type_select_class_'+id+'').append('<option attr="2" attr-Type="more_for_All_Days" attr-room_meal_type="'+more_room_meal_type2+'" attr-price-all="'+more_price_all_days+'" attr-total_days="'+total_days+'" attr-more_room_supplier_name="'+more_room_supplier_name+'" attr-more_room_type_cat="'+more_room_type_cat+'" attr-more_room_type_name="'+more_room_type_name+'" attr-more_hotelRoom_type_id="'+more_hotelRoom_type_id+'" attr-more_hotelRoom_type_idM="'+more_hotelRoom_type_idM+'" value="Double">Double('+more_room_supplier_name+')</option>');
                                                    }
                                                    else if(more_room_type == 'Triple'){
                                                        $('.more_hotel_type_select_class_'+id+'').append('<option attr="3" attr-Type="more_for_All_Days" attr-room_meal_type="'+more_room_meal_type2+'" attr-price-all="'+more_price_all_days+'" attr-total_days="'+total_days+'" attr-more_room_supplier_name="'+more_room_supplier_name+'" attr-more_room_type_cat="'+more_room_type_cat+'" attr-more_room_type_name="'+more_room_type_name+'" attr-more_hotelRoom_type_id="'+more_hotelRoom_type_id+'" attr-more_hotelRoom_type_idM="'+more_hotelRoom_type_idM+'" value="Triple">Triple('+more_room_supplier_name+')</option>');
                                                    }
                                                    else if(more_room_type == 'Quad'){
                                                        $('.more_hotel_type_select_class_'+id+'').append('<option attr="4" attr-Type="more_for_All_Days" attr-room_meal_type="'+more_room_meal_type2+'" attr-price-all="'+more_price_all_days+'" attr-total_days="'+total_days+'" attr-more_room_supplier_name="'+more_room_supplier_name+'" attr-more_room_type_cat="'+more_room_type_cat+'" attr-more_room_type_name="'+more_room_type_name+'" attr-more_hotelRoom_type_id="'+more_hotelRoom_type_id+'" attr-more_hotelRoom_type_idM="'+more_hotelRoom_type_idM+'" value="Quad">Quad('+more_room_supplier_name+')</option>');
                                                    }
                                                    else{
                                                        $('.more_hotel_type_select_class_'+id+'').append('');
                                                    }
                                                }
                                            }else{
                                                var more_week_end_price     = value1.more_week_end_price
                                                var more_week_days_price    = value1.more_week_days_price;
                                                
                                                var more_weekdays = value1.more_weekdays;
                                                if(more_weekdays != null && more_weekdays != ''){
                                                    var more_weekdays1          = JSON.parse(more_weekdays);
                                                    $.each(more_weekdays1, function(key, more_weekdaysValue) {
                                                        if(more_weekdaysValue == 'Sunday'){
                                                            more_price_WeekDays  = parseFloat(more_price_WeekDays) + parseFloat(Sunday_D) * parseFloat(more_week_days_price);
                                                            more_total_WeekDays   = parseFloat(more_total_WeekDays) + parseFloat(Sunday_D);
                                                        }else if(more_weekdaysValue == 'Monday'){
                                                            more_price_WeekDays  = parseFloat(more_price_WeekDays) + parseFloat(Monday_D) * parseFloat(more_week_days_price);
                                                            more_total_WeekDays   = parseFloat(more_total_WeekDays) + parseFloat(Monday_D);
                                                        }else if(more_weekdaysValue == 'Tuesday'){
                                                            more_price_WeekDays  = parseFloat(more_price_WeekDays) + parseFloat(Tuesday_D) * parseFloat(more_week_days_price);
                                                            more_total_WeekDays   = parseFloat(more_total_WeekDays) + parseFloat(Tuesday_D);
                                                        }else if(more_weekdaysValue == 'Wednesday'){
                                                            more_price_WeekDays  = parseFloat(more_price_WeekDays) + parseFloat(Wednesday_D) * parseFloat(more_week_days_price);
                                                            more_total_WeekDays   = parseFloat(more_total_WeekDays) + parseFloat(Wednesday_D);
                                                        }else if(more_weekdaysValue == 'Thursday'){
                                                            more_price_WeekDays  = parseFloat(more_price_WeekDays) + parseFloat(Thursday_D) * parseFloat(more_week_days_price);
                                                            more_total_WeekDays   = parseFloat(more_total_WeekDays) + parseFloat(Thursday_D);
                                                        }else if(more_weekdaysValue == 'Friday'){
                                                            more_price_WeekDays  = parseFloat(more_price_WeekDays) + parseFloat(Friday_D) * parseFloat(more_week_days_price);
                                                            more_total_WeekDays   = parseFloat(more_total_WeekDays) + parseFloat(Friday_D);
                                                        }else if(more_weekdaysValue == 'Saturday'){
                                                            more_price_WeekDays  = parseFloat(more_price_WeekDays) + parseFloat(Saturday_D) * parseFloat(more_week_days_price);
                                                            more_total_WeekDays   = parseFloat(more_total_WeekDays) + parseFloat(Saturday_D);
                                                        }else{
                                                            more_price_WeekDays  = more_price_WeekDays;
                                                            more_total_WeekDays  = more_total_WeekDays;
                                                        }
                                                    });
                                                }
                                                
                                                var more_weekend = value1.more_weekend;
                                                if(more_weekend != null && more_weekend != ''){
                                                    var more_weekend1 = JSON.parse(more_weekend);
                                                    $.each(more_weekend1, function(key, more_weekendValue) {
                                                        if(more_weekendValue == 'Sunday'){
                                                            more_price_WeekEnds  = parseFloat(more_price_WeekEnds) + parseFloat(Sunday_D) * parseFloat(more_week_end_price);
                                                            more_total_WeekEnds   = parseFloat(more_total_WeekEnds) + parseFloat(Sunday_D);
                                                        }else if(more_weekendValue == 'Monday'){
                                                            more_price_WeekEnds    = parseFloat(more_price_WeekEnds) + parseFloat(Monday_D) * parseFloat(more_week_end_price);
                                                            more_total_WeekEnds   = parseFloat(more_total_WeekEnds) + parseFloat(Monday_D);
                                                        }else if(more_weekendValue == 'Tuesday'){
                                                            more_price_WeekEnds   = parseFloat(more_price_WeekEnds) + parseFloat(Tuesday_D) * parseFloat(more_week_end_price);
                                                            more_total_WeekEnds   = parseFloat(more_total_WeekEnds) + parseFloat(Tuesday_D);
                                                        }else if(more_weekendValue == 'Wednesday'){
                                                            more_price_WeekEnds   = parseFloat(more_price_WeekEnds) + parseFloat(Wednesday_D) * parseFloat(more_week_end_price);
                                                            more_total_WeekEnds   = parseFloat(more_total_WeekEnds) + parseFloat(Wednesday_D);
                                                        }else if(more_weekendValue == 'Thursday'){
                                                            more_price_WeekEnds   = parseFloat(more_price_WeekEnds) + parseFloat(Thursday_D) * parseFloat(more_week_end_price);
                                                            more_total_WeekEnds   = parseFloat(more_total_WeekEnds) + parseFloat(Thursday_D);
                                                        }else if(more_weekendValue == 'Friday'){
                                                            more_price_WeekEnds   = parseFloat(more_price_WeekEnds) + parseFloat(Friday_D) * parseFloat(more_week_end_price);
                                                            more_total_WeekEnds   = parseFloat(more_total_WeekEnds) + parseFloat(Friday_D);
                                                        }else if(more_weekendValue == 'Saturday'){
                                                            more_price_WeekEnds   = parseFloat(more_price_WeekEnds) + parseFloat(Saturday_D) * parseFloat(more_week_end_price);
                                                            more_total_WeekEnds   = parseFloat(more_total_WeekEnds) + parseFloat(Saturday_D);
                                                        }else{
                                                            more_price_WeekEnds = more_price_WeekEnds;
                                                            more_total_WeekEnds = more_total_WeekEnds;
                                                        }
                                                    });
                                                }
                                                
                                                var more_room_type    = value1.more_room_type;
                                                if(more_room_type != null && more_room_type != ''){
                                                    if(more_room_type == 'Single'){
                                                        $('.more_hotel_type_select_class_'+id+'').append('<option attr="1" attr-Type="more_for_Week_Days" attr-room_meal_type="'+more_room_meal_type2+'" attr-price-weekdays="'+more_week_days_price+'" attr-price-weekends="'+more_week_end_price+'" attr-total_WeekDays="'+more_total_WeekDays+'" attr-total_WeekEnds="'+more_total_WeekEnds+'" attr-more_room_supplier_name="'+more_room_supplier_name+'" attr-more_room_type_cat="'+more_room_type_cat+'" attr-more_room_type_name="'+more_room_type_name+'" attr-more_hotelRoom_type_id="'+more_hotelRoom_type_id+'" attr-more_hotelRoom_type_idM="'+more_hotelRoom_type_idM+'" value="Single">Single('+more_room_supplier_name+')</option>');
                                                    }
                                                    else if(more_room_type == 'Double'){
                                                        $('.more_hotel_type_select_class_'+id+'').append('<option attr="2" attr-Type="more_for_Week_Days" attr-room_meal_type="'+more_room_meal_type2+'" attr-price-weekdays="'+more_week_days_price+'" attr-price-weekends="'+more_week_end_price+'" attr-total_WeekDays="'+more_total_WeekDays+'" attr-total_WeekEnds="'+more_total_WeekEnds+'" attr-more_room_supplier_name="'+more_room_supplier_name+'" attr-more_room_type_cat="'+more_room_type_cat+'" attr-more_room_type_name="'+more_room_type_name+'" attr-more_hotelRoom_type_id="'+more_hotelRoom_type_id+'" attr-more_hotelRoom_type_idM="'+more_hotelRoom_type_idM+'" value="Double">Double('+more_room_supplier_name+')</option>');
                                                    }
                                                    else if(more_room_type == 'Triple'){
                                                        $('.more_hotel_type_select_class_'+id+'').append('<option attr="3" attr-Type="more_for_Week_Days" attr-room_meal_type="'+more_room_meal_type2+'" attr-price-weekdays="'+more_week_days_price+'" attr-price-weekends="'+more_week_end_price+'" attr-total_WeekDays="'+more_total_WeekDays+'" attr-total_WeekEnds="'+more_total_WeekEnds+'" attr-more_room_supplier_name="'+more_room_supplier_name+'" attr-more_room_type_cat="'+more_room_type_cat+'" attr-more_room_type_name="'+more_room_type_name+'" attr-more_hotelRoom_type_id="'+more_hotelRoom_type_id+'" attr-more_hotelRoom_type_idM="'+more_hotelRoom_type_idM+'" value="Triple">Triple('+more_room_supplier_name+')</option>');
                                                    }
                                                    else if(more_room_type == 'Quad'){
                                                        $('.more_hotel_type_select_class_'+id+'').append('<option attr="4" attr-Type="more_for_Week_Days" attr-room_meal_type="'+more_room_meal_type2+'" attr-price-weekdays="'+more_week_days_price+'" attr-price-weekends="'+more_week_end_price+'" attr-total_WeekDays="'+more_total_WeekDays+'" attr-total_WeekEnds="'+more_total_WeekEnds+'" attr-more_room_supplier_name="'+more_room_supplier_name+'" attr-more_room_type_cat="'+more_room_type_cat+'" attr-more_room_type_name="'+more_room_type_name+'" attr-more_hotelRoom_type_id="'+more_hotelRoom_type_id+'" attr-more_hotelRoom_type_idM="'+more_hotelRoom_type_idM+'" value="Quad">Quad('+more_room_supplier_name+')</option>');
                                                    }
                                                    else{
                                                        $('.more_hotel_type_select_class_'+id+'').append('');
                                                    }
                                                }
                                                
                                                var price_WE_WD = parseFloat(more_price_WeekDays) + parseFloat(more_price_WeekEnds);
                                            }
                                        }else{
                                            console.log('more_week_price_type is empty!')
                                        }
                                    }
                                });
                                
                                // console.log(more_room_type_details1);
                            }
                            
                        });
                        // i_more_hotel = i_more_hotel + 1;
                    }else{
                        alert('Select Date First!');
                    }
                    
                }else{
                    alert('Room are Empty');
                }
                
                var start_dateN         = $('#more_makkah_accomodation_check_in_'+id+'').val();
                var enddateN            = $('#more_makkah_accomodation_check_out_date_'+id+'').val();
                var acomodation_nightsN = $("#more_acomodation_nights_"+id+'').val();
                var acc_qty_classN      = $(".more_acc_qty_class_"+id+'').val();
                var switch_hotel_name   = $('#more_switch_hotel_name'+id+'').val();
                if(switch_hotel_name == 1){
                    var acc_hotel_nameN     = $('#more_acc_hotel_name_'+id+'').val();
                }else{
                    var acc_hotel_nameN     = $('.more_get_room_types_'+id+'').val();
                }
                var html_data = `(Hotel Name : <b style="color: #cdc0c0;">${acc_hotel_nameN}</b>) (Quantity : <b style="color: #cdc0c0;">${acc_qty_classN}</b>) (Check in : <b style="color: #cdc0c0;">${start_dateN}</b>) (Check Out : <b style="color: #cdc0c0;">${enddateN}</b>) (Nights : <b style="color: #cdc0c0;">${acomodation_nightsN}</b>)`;
                $('#more_acc_cost_html_'+id+'').html(html_data);
                
                $("#more_acc_hotel_HotelName"+id+'').val(acc_hotel_nameN);
            },
        });
    }
    
    function more_hotel_type_funInvoice(id){
        // Price Section
        $('#more_hotel_price_for_week_end_'+id+'').empty();
        $('#more_makkah_acc_room_price_funs_'+id+'').val('');
        $('#more_acc_price_get_'+id+'').val('');
        $('#more_acc_total_amount_'+id+'').val('');
        $('#more_exchange_rate_price_funs_'+id+'').val('');
        $('#more_price_per_room_exchange_rate_'+id+'').val('');
        $('#more_price_per_person_exchange_rate_'+id+'').val('');
        $('#more_price_total_amout_exchange_rate_'+id+'').val('');
        
        var hotel_type = $('.more_hotel_type_select_class_'+id+'').val();
        $('#more_hotel_acc_type_'+id+'').val(hotel_type);
        $('#more_hotel_meal_type_'+id+'').empty();
        
        var hotel_attr_type         = $('.more_hotel_type_select_class_'+id+'').find('option:selected').attr('attr-type');
        var hotel_price_All         = $('.more_hotel_type_select_class_'+id+'').find('option:selected').attr('attr-price-all');
        var hotel_total_days        = $('.more_hotel_type_select_class_'+id+'').find('option:selected').attr('attr-total_days');
        var hotel_room_meal_type    = $('.more_hotel_type_select_class_'+id+'').find('option:selected').attr('attr-room_meal_type');
        var hotel_price_weekdays    = $('.more_hotel_type_select_class_'+id+'').find('option:selected').attr('attr-price-weekdays');
        var hotel_total_weekdays    = $('.more_hotel_type_select_class_'+id+'').find('option:selected').attr('attr-total_weekdays');
        var hotel_price_weekends    = $('.more_hotel_type_select_class_'+id+'').find('option:selected').attr('attr-price-weekends');
        var hotel_total_weekends    = $('.more_hotel_type_select_class_'+id+'').find('option:selected').attr('attr-total_weekends');
        var dataMRMT                = `<option value="${hotel_room_meal_type}">${hotel_room_meal_type}</option>`;
        
        // Price Calculations
        var total_Nights_WEWD       = parseFloat(hotel_total_weekdays) + parseFloat(hotel_total_weekends)
        var hotel_type_price        = $('.more_hotel_type_select_class_'+id+'').val();
        var room_price              = $('#more_makkah_acc_room_price_funs_'+id+'').val();
        if(hotel_type_price == 'Double')
        {
            hotel_type_price = 2;
        }
        else if(hotel_type_price == 'Triple')
        {
            hotel_type_price = 3;
        }
        else if(hotel_type_price == 'Quad')
        {
            hotel_type_price = 4;
        }else{
            hotel_type_price = 1;
        }
        
        var attr_room_supplier_name = $('.more_hotel_type_select_class_'+id+'').find('option:selected').attr('attr-more_room_supplier_name');
        var attr_room_type_cat      = $('.more_hotel_type_select_class_'+id+'').find('option:selected').attr('attr-more_room_type_cat');
        var attr_room_type_name     = $('.more_hotel_type_select_class_'+id+'').find('option:selected').attr('attr-more_room_type_name');
        var more_hotelRoom_type_id  = $('.more_hotel_type_select_class_'+id+'').find('option:selected').attr('attr-more_hotelRoom_type_id');
        var more_hotelRoom_type_idM  = $('.more_hotel_type_select_class_'+id+'').find('option:selected').attr('attr-more_hotelRoom_type_idM');
            
        if(hotel_attr_type == 'for_All_Days' || hotel_attr_type == 'more_for_All_Days'){
            var room_price = $('#more_makkah_acc_room_price_funs_'+id+'').val(hotel_price_All);
            $('#more_hotel_meal_type_'+id+'').append(dataMRMT);
            var hotel_price_for_weekend_append  =   `<h4 class="mt-4">Price Details(For All Days)</h4>
                                                    <div class="col-xl-3">
                                                        <label for="">No of Nights</label>
                                                        <input type="text" value="${hotel_total_days}" class="form-control no_of_nights_all_days${id}" readonly>
                                                    </div>
                                                    <div class="col-xl-3">
                                                        <label for="">Price Per Room/Night</label>
                                                        <input type="text" value="${hotel_price_All}" class="form-control price_per_night_all_days${id}" readonly>
                                                    </div>`;
            $('#more_hotel_price_for_week_end_'+id+'').append(hotel_price_for_weekend_append);
            
            $('#more_hotel_supplier_id_'+id+'').val(attr_room_supplier_name);
            $('#more_hotel_type_id_'+id+'').val(attr_room_type_cat);
            $('#more_hotel_type_cat_'+id+'').val(attr_room_type_name);
            $('#more_hotelRoom_type_id_'+id+'').val(more_hotelRoom_type_id);
            $('#more_hotelRoom_type_idM_'+id+'').val(more_hotelRoom_type_idM);
            
            var total       = parseFloat(hotel_price_All)/parseFloat(hotel_type_price);
            total           = total.toFixed(2);
            // var grand_total = parseFloat(total) * parseFloat(hotel_total_days);
            var grand_total = parseFloat(hotel_price_All) * parseFloat(hotel_total_days);
            grand_total     = grand_total.toFixed(2);
            $('#more_acc_price_get_'+id+'').val(total);
            $('#more_acc_total_amount_'+id+'').val(grand_total);
            $('#more_makkah_acc_room_price_funs_'+id+'').attr('readonly', true);
            $('#more_acc_price_get_'+id+'').attr('readonly', true);
            $('#more_acc_total_amount_'+id+'').attr('readonly', true);
            
        }else if(hotel_attr_type == 'for_Week_Days' || hotel_attr_type == 'more_for_Week_Days'){
            var price_per_person_weekdays       = parseFloat(hotel_price_weekdays)/parseFloat(hotel_type_price);
            var price_per_person_weekends       = parseFloat(hotel_price_weekends)/parseFloat(hotel_type_price);
            var total_price_per_person_weekdays = parseFloat(price_per_person_weekdays) * parseFloat(hotel_total_weekdays);
            var total_price_per_person_weekends = parseFloat(price_per_person_weekends) * parseFloat(hotel_total_weekends);
            
            var TP_WEWD     = parseFloat(total_price_per_person_weekdays) + parseFloat(total_price_per_person_weekends);
            
            var TP_WEWD1     = parseFloat(hotel_price_weekdays) + parseFloat(hotel_price_weekends);
            var room_price  = $('#more_makkah_acc_room_price_funs_'+id+'').val(TP_WEWD1);
            var total       = parseFloat(TP_WEWD)/parseFloat(hotel_type_price);
            // var grand_total = parseFloat(total) * parseFloat(total_Nights_WEWD);
            var grand_total = parseFloat(TP_WEWD) * parseFloat(total_Nights_WEWD);
            $('#more_acc_price_get_'+id+'').val(total);
            $('#more_acc_total_amount_'+id+'').val(grand_total);
            
            $('#more_hotel_meal_type_'+id+'').append(dataMRMT);
            var hotel_price_for_weekend_append  =   `<h4 class="mt-4">Week Price Details</h4>
                                                    <div class="col-xl-3">
                                                        <label for="">No of Nights</label>
                                                        <input type="text" value="${hotel_total_weekdays}" class="form-control no_of_nights_weekdays${id}" readonly>
                                                    </div>
                                                    <div class="col-xl-3">
                                                        <label for="">Price Per Room/Night</label>
                                                        <input type="text" value="${hotel_price_weekdays}" class="form-control price_per_night_weekdays${id}" readonly>
                                                    </div>
                                                    <div class="col-xl-3">
                                                        <label for="">Price Per Person/Night</label>
                                                        <input type="text" value="${price_per_person_weekdays}" class="form-control price_per_person_weekdays${id}" readonly>
                                                    </div>
                                                    <div class="col-xl-3">
                                                        <label for="">Total Amount/Per Person</label>
                                                        <input type="text" value="${total_price_per_person_weekdays}" class="form-control total_price_per_person_weekdays${id}" readonly>
                                                    </div>
                                                    <h4 class="mt-4">WeekEnd Price Details</h4>
                                                    <div class="col-xl-3">
                                                        <label for="">No of Nights</label>
                                                        <input type="text" value="${hotel_total_weekends}" class="form-control no_of_nights_weekends${id}" readonly>
                                                    </div>
                                                    <div class="col-xl-3">
                                                        <label for="">Price Per Room/Night</label>
                                                        <input type="text" value="${hotel_price_weekends}" class="form-control price_per_night_weekends${id}" readonly>
                                                    </div>
                                                    <div class="col-xl-3">
                                                        <label for="">Price Per Person/Night</label>
                                                        <input type="text" value="${price_per_person_weekends}" class="form-control price_per_person_weekends${id}" readonly>
                                                    </div>
                                                    <div class="col-xl-3">
                                                        <label for="">Total Amount/Per Person</label>
                                                        <input type="text" value="${total_price_per_person_weekends}" class="form-control total_price_per_person_weekends${id}" readonly>
                                                    </div>`;
            $('#more_hotel_price_for_week_end_'+id+'').append(hotel_price_for_weekend_append);
            $('#more_hotel_supplier_id_'+id+'').val(attr_room_supplier_name);
            $('#more_hotel_type_id_'+id+'').val(attr_room_type_cat);
            $('#more_hotel_type_cat_'+id+'').val(attr_room_type_name);
            $('#more_hotelRoom_type_id_'+id+'').val(more_hotelRoom_type_id);
            $('#more_hotelRoom_type_idM_'+id+'').val(more_hotelRoom_type_idM);
            
        }else{
            alert('Select Room Type');
        }
        
        var start_dateN         = $('#more_makkah_accomodation_check_in_'+id+'').val();
        var enddateN            = $('#more_makkah_accomodation_check_out_date_'+id+'').val();
        var acomodation_nightsN = $("#more_acomodation_nights_"+id+'').val();
        var acc_qty_classN      = $(".more_acc_qty_class_"+id+'').val();
        var switch_hotel_name   = $('#more_switch_hotel_name'+id+'').val();
        if(switch_hotel_name == 1){
            var acc_hotel_nameN     = $('#more_acc_hotel_name_'+id+'').val();
        }else{
            var acc_hotel_nameN     = $('.more_get_room_types_'+id+'').val();
        }
        var html_data = `(Hotel Name : <b style="color: #cdc0c0;">${acc_hotel_nameN}</b>) (Quantity : <b style="color: #cdc0c0;">${acc_qty_classN}</b>) (Check in : <b style="color: #cdc0c0;">${start_dateN}</b>) (Check Out : <b style="color: #cdc0c0;">${enddateN}</b>) (Nights : <b style="color: #cdc0c0;">${acomodation_nightsN}</b>)`;
        $('#more_acc_cost_html_'+id+'').html(html_data);
        
    }
    
    function more_acc_qty_classInvoice(id){
        var more_switch_hotel_name = $('#more_switch_hotel_name'+id+'').val();
        if(more_switch_hotel_name != 1){
            var more_acc_qty_class = $('.more_acc_qty_class_'+id+'').val();
            var more_hotel_type = $('.more_hotel_type_select_class_'+id+'').find('option:selected').attr('attr');
            var more_mult = parseFloat(more_acc_qty_class) * parseFloat(more_hotel_type);
            $('.more_acc_pax_class_'+id+'').val(more_mult);
            
        }else{
            var more_acc_qty_class = $('.more_acc_qty_class_'+id+'').val();
            var more_hotel_type = $('.more_hotel_type_class_'+id+'').find('option:selected').attr('attr');
            var more_mult = parseFloat(more_acc_qty_class) * parseFloat(more_hotel_type);
            $('.more_acc_pax_class_'+id+'').val(more_mult);
        }
        
        var start_dateN         = $('#more_makkah_accomodation_check_in_'+id+'').val();
        var enddateN            = $('#more_makkah_accomodation_check_out_date_'+id+'').val();
        var acomodation_nightsN = $("#more_acomodation_nights_"+id+'').val();
        var acc_qty_classN      = $(".more_acc_qty_class_"+id+'').val();
        var switch_hotel_name   = $('#more_switch_hotel_name'+id+'').val();
        if(switch_hotel_name == 1){
            var acc_hotel_nameN     = $('#more_acc_hotel_name_'+id+'').val();
        }else{
            var acc_hotel_nameN     = $('.more_get_room_types_'+id+'').val();
        }
        var html_data = `(Hotel Name : <b style="color: #cdc0c0;">${acc_hotel_nameN}</b>) (Quantity : <b style="color: #cdc0c0;">${acc_qty_classN}</b>) (Check in : <b style="color: #cdc0c0;">${start_dateN}</b>) (Check Out : <b style="color: #cdc0c0;">${enddateN}</b>) (Nights : <b style="color: #cdc0c0;">${acomodation_nightsN}</b>)`;
        $('#more_acc_cost_html_'+id+'').html(html_data);
        
        $('#more_acc_hotel_Quantity'+id+'').val(more_acc_qty_class);
    }
    
    function more_makkah_acc_room_price_funsI(id,cityId){
        var more_switch_hotel_name = $('#more_switch_hotel_name'+id+'').val();
        if(more_switch_hotel_name == 1){
            var hotel_type_price = $('#more_hotel_type_'+id+'').val();
        }else{
            var hotel_type_price = $('.more_hotel_type_select_class_'+id+'').val();
        }
        
        var room_price          = $('#more_makkah_acc_room_price_funs_'+id+'').val();
        var acomodation_nights  = $('#more_acomodation_nights_'+id+'').val();
        
        if(hotel_type_price == 'Double')
        {
            hotel_type_price = 2;
        }else if(hotel_type_price == 'Triple')
        {
            hotel_type_price = 3;
        }else if(hotel_type_price == 'Quad')
        {
            hotel_type_price = 4;
        }else{
            hotel_type_price = 1;
        }
        
        var total           = parseFloat(room_price)/parseFloat(hotel_type_price);
        var total1          = total.toFixed(2);
        var grand_total     = parseFloat(room_price) * parseFloat(acomodation_nights);
        var grand_total1    = grand_total.toFixed(2);
        
        $('#more_acc_price_get_'+id+'').val(total1);
        $('#more_acc_total_amount_'+id+'').val(grand_total1);
    }
    
    function more_exchange_rate_price_funsI(id){
        var makkah_acc_room_price       = $('#more_makkah_acc_room_price_funs_'+id+'').val();
        var makkah_acc_price            = $('#more_acc_price_get_'+id+'').val();
        var makkah_acc_total_amount     = $('#more_acc_total_amount_'+id+'').val();
        var exchange_rate_price_funs    = $('#more_exchange_rate_price_funs_'+id+'').val();
        $('#more_hotel_exchage_rate_per_night_'+id+'').val(exchange_rate_price_funs);
        
        var currency_conversion         = $("#select_exchange_type").val();
        if(currency_conversion == 'Divided'){
            var price_per_room_exchangeRate=parseFloat(makkah_acc_room_price)/parseFloat(exchange_rate_price_funs);
            var price_per_person_exchangeRate=parseFloat(makkah_acc_price)/parseFloat(exchange_rate_price_funs);
            var price_total_exchangeRate=parseFloat(makkah_acc_total_amount)/parseFloat(exchange_rate_price_funs);  
        }else{
            var price_per_room_exchangeRate=parseFloat(makkah_acc_room_price) * parseFloat(exchange_rate_price_funs);
            var price_per_person_exchangeRate=parseFloat(makkah_acc_price) * parseFloat(exchange_rate_price_funs);
            var price_total_exchangeRate=parseFloat(makkah_acc_total_amount) * parseFloat(exchange_rate_price_funs);  
        }
        
        var price_per_room_exchangeRate     = price_per_room_exchangeRate.toFixed(2);
        var price_per_person_exchangeRate   = price_per_person_exchangeRate.toFixed(2);
        var price_total_exchangeRate        = price_total_exchangeRate.toFixed(2);
        $('#more_price_per_room_exchange_rate_'+id+'').val(price_per_room_exchangeRate);
        $('#more_hotel_acc_price_per_night_'+id+'').val(price_per_room_exchangeRate);
        $('#more_price_per_person_exchange_rate_'+id+'').val(price_per_person_exchangeRate);
        $('#more_price_total_amout_exchange_rate_'+id+'').val(price_total_exchangeRate);
        $('#more_hotel_acc_price_'+id+'').val(price_total_exchangeRate);
        
        
        var double_cost_price   = 0;
        var triple_cost_price   = 0;
        var quad_cost_price     = 0;
        for(var k = 1; k<=20; k++){
            
            var more_price_total_amout_exchange_rate = $('#more_price_total_amout_exchange_rate_'+k+'').val();
            
            if(more_price_total_amout_exchange_rate != null && more_price_total_amout_exchange_rate != '' && more_price_total_amout_exchange_rate != 0){    
                
                var more_switch_hotel_name      = $('#more_switch_hotel_name'+k+'').val();
                if(more_switch_hotel_name != 1){
                    var more_hotel_type_price   = $('.more_hotel_type_select_class_'+k+'').val();
                }else{
                    var more_hotel_type_price   = $('#more_hotel_type_'+k+'').val();
                }
                
                if(more_hotel_type_price == 'Double'){
                    double_cost_price       = parseFloat(double_cost_price) + parseFloat(more_price_total_amout_exchange_rate);
                    var double_cost_price1  = double_cost_price.toFixed(2);
                    $('#double_cost_price').val(double_cost_price1);
                }else if(more_hotel_type_price == 'Triple'){
                    triple_cost_price       = parseFloat(triple_cost_price) + parseFloat(more_price_total_amout_exchange_rate);
                    var triple_cost_price1  = triple_cost_price.toFixed(2);
                    $('#triple_cost_price').val(triple_cost_price1);
                }else if(more_hotel_type_price == 'Quad'){
                    quad_cost_price         = parseFloat(quad_cost_price) + parseFloat(more_price_total_amout_exchange_rate);
                    var quad_cost_price1    = quad_cost_price.toFixed(2);
                    $('#quad_cost_price').val(quad_cost_price1);
                }else{
                    console.log('Hotel Type Not Found!!');
                }
            }
            else{
                console.log('Hotel Type Not Found!!');
            }
        }
    }
    
    function more_hotel_markup_type_accI(id){
        var ids                                 = $('#more_hotel_markup_types_'+id+'').val();
        var prices                              = $('#more_hotel_acc_price_'+id+'').val();
        // add_numberElseI();
        if(ids == '%'){
            $('#more_hotel_markup_mrk_'+id+'').text(ids);
        }else{
            var value_c         = $("#currency_conversion1").val();
            const usingSplit    = value_c.split(' ');
            var value_1         = usingSplit['0'];
            var value_2         = usingSplit['2'];
            $(".currency_value1").html(value_1);
            $(".currency_value_exchange_1").html(value_2);
            $('#more_hotel_markup_mrk_'+id+'').text(value_1);
        }
    }
    
    function get_markup_invoice_price(id){
        var ids                             = $('#more_hotel_markup_types_'+id+'').val();
        var prices                          = $('#more_hotel_acc_price_'+id+'').val();
        var hotel_acc_price_per_night       = $('#more_hotel_acc_price_per_night_'+id+'').val();
        var hotel_exchage_rate_per_night    = $('#more_hotel_exchage_rate_per_night_'+id+'').val();
        var acomodation_nights              = $('#more_acomodation_nights_'+id+'').val();
        add_numberElseI();
        if(ids == '%'){
            var markup_val  =  $('#more_hotel_markup_'+id+'').val();
            var total1      = prices * markup_val/100;
            var total       = total1.toFixed(2);
            $('#more_hotel_exchage_rate_markup_total_per_night_'+id+'').val(total);
            var total2      = parseFloat(total) + parseFloat(prices);
            var total3      = total2.toFixed(2);
            $('#more_hotel_markup_total_'+id+'').val(total3);
            $('#more_hotel_invoice_markup_'+id+'').val(total3);
            add_numberElse_1I();
        }else if(ids == 'per_Night'){
            var markup_val  =  $('#more_hotel_markup_'+id+'').val();
            var total1      = (parseFloat(markup_val) / parseFloat(hotel_exchage_rate_per_night)) * acomodation_nights;
            var total       = total1.toFixed(2);
            $('#more_hotel_exchage_rate_markup_total_per_night_'+id+'').val(total);
            var total2      = parseFloat(total) + parseFloat(prices);
            var total3       = total2.toFixed(2);
            $('#more_hotel_markup_total_'+id+'').val(total3);
            $('#more_hotel_invoice_markup_'+id+'').val(total3);
            add_numberElse_1I();
        }else{
            var markup_val  =  $('#more_hotel_markup_'+id+'').val();
            var total1      = parseFloat(markup_val) / parseFloat(hotel_exchage_rate_per_night);
            var total       = total1.toFixed(2);
            $('#more_hotel_exchage_rate_markup_total_per_night_'+id+'').val(total);
            var total2      = parseFloat(total) + parseFloat(prices);
            var total3       = total2.toFixed(2);
            $('#more_hotel_markup_total_'+id+'').val(total3);
            $('#more_hotel_invoice_markup_'+id+'').val(total3);
            add_numberElse_1I();
        }
    }
    
    // Visa
    $("#currency_conversion1").on('change', function(){
        var value_c = $('option:selected', this).val();
        var attr_conversion_type = $('option:selected', this).attr('attr_conversion_type');
        $("#select_exchange_type").val(attr_conversion_type);
        const usingSplit = value_c.split(' ');
        var value_1 = usingSplit['0'];
        var value_2 = usingSplit['2'];
        
        $('#visa_C_S').empty();
        $('#visa_C_S').html(value_1);
        
        if(value_c != 0){
            $('#invoice_exchage_rate_visa_Div').empty();
            
            var data = `<div class="col-xl-6" style="padding: 10px;">
                            <label for="">Exchange Rate</label>
                            <div class="input-group">
                                <span class="input-group-btn input-group-append">
                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                        
                                    </a>
                                </span>
                                <input type="text" id="exchange_rate_visaI" onkeyup="exchange_rate_visaI_function()" name="exchange_rate_visaI" class="form-control">
                            </div>
                        </div>
                        
                        <div class="col-xl-6" style="padding: 10px;">
                            <label for="">Exchange Visa Fee</label>
                            <div class="input-group">
                                <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a></span>
                                <input type="text" id="exchange_rate_visa_total_amountI" name="exchange_rate_visa_fee" class="form-control">
                            </div>
                        </div>`;
            $('#invoice_exchage_rate_visa_Div').append(data);
        }else{
            $('#invoice_exchage_rate_visa_Div').empty();
        }
        exchange_currency_funs(value_1,value_2);
        
    });
    
    function exchange_rate_visaI_function(){
        var visa_fee            = $('#visa_fee').val();
        var exchange_rate_visa  = $('#exchange_rate_visaI').val();
        var currency_conversion = $("#select_exchange_type").val();
        if(currency_conversion == 'Divided'){
            var total_visa = parseFloat(visa_fee) / parseFloat(exchange_rate_visa);
        }
        else{
            var total_visa = parseFloat(visa_fee) * parseFloat(exchange_rate_visa);
        }
        var total_visa = total_visa.toFixed(2);
        $('#exchange_rate_visa_total_amountI').val(total_visa);
        $('#visa_price_select').val(total_visa);
    }
    
    var v_ID = 1;
    $('#add_more_visa_Pax').click(function (){
        var data = `<div id="more_visa_pax_div_${v_ID}" class="row">
                        <div class="col-xl-4" style="padding: 10px;">
                            <label for="">Visa Type</label>
                            <select name="more_visa_type[]" id="more_visa_type_${v_ID}" class="form-control more_visa_type_class" onchange="more_visa_type_select(${v_ID})"></select>
                         </div>
                    
                        <div class="col-xl-4" style="padding: 10px;">
                            <label for="">Visa Fee</label>
                            <div class="input-group">
                                <span class="input-group-btn input-group-append">
                                    <a class="btn btn-primary bootstrap-touchspin-up more_visa_C_S">
                                        <?php echo $currency; ?>
                                    </a>
                                </span>
                                <input type="text" id="more_visa_fee_${v_ID}" name="more_visa_fee[]" class="form-control" onchange="more_visa_fee_calculation(${v_ID})">
                            </div>
                        </div>
                        <input type="hidden" id="more_total_visa_markup_value_${v_ID}" name="more_total_visa_markup_value[]" class="form-control">
                        <div class="col-xl-3" style="padding: 10px;">
                            <label for="">Visa Pax</label>
                            <input type="text" id="more_visa_Pax_${v_ID}" name="more_visa_Pax[]" class="form-control">
                        </div>
                        
                        <div class="col-xl-1" style="margin-top: 30px;">
                            <button type="button" onclick="remove_more_visa_pax(${v_ID})" class="btn btn-primary">Remove</button>
                        </div>
                        
                        <div id="more_invoice_exchage_rate_visa_Div_${v_ID}" class="row"></div>
                    </div>`;
        $('#add_more_visa_Pax_Div').append(data);
        
        var data_1 = `<div class="row" id="more_visa_cost_${v_ID}">                        
                            <div class="col-xl-3">
                                <h4>Visa Cost</h4>
                            </div>
                            
                            <input type="hidden" name="acc_hotel_CityName[]">
                            <input type="hidden" name="acc_hotel_HotelName[]">
                            <input type="hidden" name="acc_hotel_CheckIn[]">
                            <input type="hidden" name="acc_hotel_CheckOut[]">
                            <input type="hidden" name="acc_hotel_NoOfNights[]">
                            <input type="hidden" name="acc_hotel_Quantity[]">
                            
                            <div class="col-xl-9">
                                <input type="hidden" id="more_visa_Type_Costing_${v_ID}" name="markup_Type_Costing[]" value="more_visa_Type_Costing" class="form-control">
                            </div>
                            
                            <div class="col-xl-3">
                                <input readonly type="text" id="more_visa_type_select_${v_ID}" name="hotel_name_markup[]" class="form-control">
                            </div>
                            
                            <div class="col-xl-2" style="display:none">
                                <div class="input-group">
                                    <input type="text" id="more_visa_price_per_night_${v_ID}" readonly="" name="more_without_markup_price_single[]" class="form-control">
                                    <span class="input-group-btn input-group-append">
                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value1"></a>
                                    </span>        
                                </div>
                            </div>
                            
                            <div class="col-xl-3">
                                <div class="input-group">
                                    <input readonly type="text" id="more_visa_price_select_${v_ID}" name="without_markup_price[]" class="form-control">
                                    <span class="input-group-btn input-group-append">
                                        <a class="btn btn-primary bootstrap-touchspin-up">
                                           <?php echo $currency; ?>
                                        </a>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-xl-2">
                                <select name="markup_type[]" id="more_visa_markup_type_${v_ID}" class="form-control" onchange="more_visa_markup_type_function(${v_ID})">
                                    <option value="">Markup Type</option>
                                    <option value="%">Percentage</option>
                                    <option value="<?php echo $currency; ?>">Fixed Amount</option>
                                </select>
                            </div>
                            
                            <div class="col-xl-2">
                                <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                    <input type="text" class="form-control" id="more_visa_markup_${v_ID}" name="markup[]" onkeyup="more_visa_markup_function(${v_ID})">
                                    <span class="input-group-btn input-group-append">
                                        <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="more_visa_mrk_${v_ID}">%</div></button>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-xl-1" style="display:none">
                                <input type="text" id="more_visa_exchage_rate_per_night_${v_ID}" readonly="" name="more_exchage_rate_single[]" class="form-control">    
                            </div>
                            
                            <div class="col-xl-2" style="display:none">
                                <div class="input-group">
                                    <input type="text" id="more_visa_exchage_rate_markup_total_per_night_${v_ID}" readonly="" name="more_markup_total_per_night[]" class="form-control"> 
                                    <span class="input-group-btn input-group-append">
                                        <a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-xl-2">
                                <div class="input-group">
                                    <input type="text" id="more_total_visa_markup_${v_ID}" name="markup_price[]" class="form-control">
                                    <span class="input-group-btn input-group-append">
                                        <a class="btn btn-primary bootstrap-touchspin-up">
                                           <?php echo $currency; ?>
                                        </a>
                                    </span>
                                </div>
                            </div>
                            
                        </div>`;
        $('#more_visa_cost_div').append(data_1);
        
        $.ajax({
            url: "super_admin/get_other_visa_type_detail", 
            type: "GET",
            dataType: "html",
            success: function(data){
                var data1   = JSON.parse(data);
                var data2   = JSON.parse(data1['visa_type']);
            	$('.more_visa_type_class').empty();
                $.each(data2['visa_type'], function(key, value) {
                    var visa_type_Data = `<option attr="${value.other_visa_type}" value="${value.other_visa_type}"> ${value.other_visa_type}</option>`;
                    $('.more_visa_type_class').append(visa_type_Data);
                });  
            }
        });
        
        var value_c                 = $("#currency_conversion1").val();
        var attr_conversion_type    = $("#currency_conversion1").find('option:selected').attr('attr_conversion_type');
        const usingSplit            = value_c.split(' ');
        var value_1                 = usingSplit['0'];
        var value_2                 = usingSplit['2'];
        $("#select_exchange_type").val(attr_conversion_type);
        
        $('.more_visa_C_S').empty();
        $('.more_visa_C_S').html(value_1);
        
        if(value_c != 0){
            $('#more_invoice_exchage_rate_visa_Div_'+v_ID+'').empty();
            
            var data = `<div class="col-xl-6" style="padding: 10px;">
                            <label for="">Exchange Rate</label>
                            <div class="input-group">
                                <span class="input-group-btn input-group-append">
                                    <a class="btn btn-primary bootstrap-touchspin-up currency_value1">
                                        
                                    </a>
                                </span>
                                <input type="text"  id="more_exchange_rate_visaI_${v_ID}" onkeyup="more_exchange_rate_visaI_function(${v_ID})" name="more_exchange_rate_visa[]" class="form-control">
                            </div>
                        </div>
                        
                        <div class="col-xl-6" style="padding: 10px;">
                            <label for="">Exchange Visa Fee</label>
                            <div class="input-group">
                                <span class="input-group-btn input-group-append"><a class="btn btn-primary bootstrap-touchspin-up currency_value_exchange_1"></a></span>
                                <input type="text" id="more_exchange_rate_visa_total_amountI_${v_ID}" name="more_exchange_rate_visa_fee[]" class="form-control">
                            </div>
                        </div>`;
            $('#more_invoice_exchage_rate_visa_Div_'+v_ID+'').append(data);
        }else{
            $('#more_invoice_exchage_rate_visa_Div_'+v_ID+'').empty();
        }
        exchange_currency_funs(value_1,value_2);
        
        v_ID = v_ID + 1;
    });
    
    function more_exchange_rate_visaI_function(id){
        var more_visa_fee               =  $('#more_visa_fee_'+id+'').val();
        var more_exchange_rate_visa     =  $('#more_exchange_rate_visaI_'+id+'').val();
        var currency_conversion         = $("#select_exchange_type").val();
        if(currency_conversion == 'Divided'){
            var total_visa = parseFloat(more_visa_fee) / parseFloat(more_exchange_rate_visa);
        }
        else{
            var total_visa = parseFloat(more_visa_fee) * parseFloat(more_exchange_rate_visa);
        }
        var total_visa = total_visa.toFixed(2);
        $('#more_exchange_rate_visa_total_amountI_'+id+'').val(total_visa);
        $('#more_visa_price_select_'+id+'').val(total_visa);    
    }
    
    function remove_more_visa_pax(id){
        $('#more_visa_pax_div_'+id+'').remove();
        $('#more_visa_cost_'+id+'').remove();
    }
    
    function more_visa_type_select(id){
        var more_visa_type = $('#more_visa_type_'+id+'').val();
        $('#more_visa_type_select_'+id+'').val(more_visa_type);
    }
    
    function more_visa_fee_calculation(id){
        var more_visa_fee = $('#more_visa_fee_'+id+'').val();
        $('#more_visa_price_select_'+id+'').val(more_visa_fee);
        $('#more_exchange_rate_visaI_'+id+'').val('');
        $('#more_exchange_rate_visa_total_amountI_'+id+'').val('');
    }
    
    function more_visa_markup_type_function(id){
        var id1 = $('#more_visa_markup_type_'+id+'').find('option:selected').attr('value');
        $('#more_visa_mrk_'+id+'').text(id1);
    }
    
    function more_visa_markup_function(id){
        var id1 = $('#more_visa_markup_type_'+id+'').find('option:selected').attr('value');
        if(id1 == '%')
        {
            var more_visa_markup        =  $('#more_visa_markup_'+id+'').val();
            var more_visa_price_select  =  $('#more_visa_price_select_'+id+'').val();
            var total1                  = (more_visa_price_select * more_visa_markup/100) + parseFloat(more_visa_price_select);
            var total                   = total1.toFixed(2);
            $('#more_total_visa_markup_'+id+'').val(total);
            $('#more_total_visa_markup_value_'+id+'').val(total);
        }
        else
        {
            var more_visa_markup        =  $('#more_visa_markup_'+id+'').val();
            var more_visa_price_select  =  $('#more_visa_price_select_'+id+'').val();
            var total1                  =  parseFloat(more_visa_price_select) +  parseFloat(more_visa_markup);
            var total                   = total1.toFixed(2);
            $('#more_total_visa_markup_'+id+'').val(total);
            $('#more_total_visa_markup_value_'+id+'').val(total);
        }
    }
</script>

@stop