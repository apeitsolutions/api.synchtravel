@extends('template/frontend/userdashboard/layout/default')
 @section('content')
 
 <div class="row mt-3">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body">

                                        <h4 class="header-title mb-3">Wizard With Progress Bar</h4>

                                        <form action="{{URL::to('super_admin/submit_umrah_packages')}}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <div id="progressbarwizard">

                                                <ul class="nav nav-pills nav-justified form-wizard-header mb-3">
                                                <li class="nav-item">
                                                        <a href="#package_details1" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2 active">
                                                            <!-- <i class="mdi mdi-account-circle me-1"></i> -->
                                                            <span class="d-none d-sm-inline">Package Details</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="#basictab1" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                                            <!-- <i class="mdi mdi-account-circle me-1"></i> -->
                                                            <span class="d-none d-sm-inline">Makkah Stay</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="#profile-tab-2" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                                            <i class="mdi mdi-face-profile me-1"></i>
                                                            <span class="d-none d-sm-inline">Madina Stay</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="#transfer-tab-3" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                                            <i class="mdi mdi-face-profile me-1"></i>
                                                            <span class="d-none d-sm-inline">Transfer</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="#Flights-tab-4" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                                            <i class="mdi mdi-face-profile me-1"></i>
                                                            <span class="d-none d-sm-inline">Flights</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="#visa-tab-5" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                                            <i class="mdi mdi-face-profile me-1"></i>
                                                            <span class="d-none d-sm-inline">Visa</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="#administration-tab-6" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                                            <i class="mdi mdi-face-profile me-1"></i>
                                                            <span class="d-none d-sm-inline">Administration</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="#submit-tab-7" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                                            <i class="mdi mdi-face-profile me-1"></i>
                                                            <span class="d-none d-sm-inline">Submit</span>
                                                        </a>
                                                    </li>
                                                    <!-- <li class="nav-item">
                                                        <a href="#finish-2" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                                                            <i class="mdi mdi-checkbox-marked-circle-outline me-1"></i>
                                                            <span class="d-none d-sm-inline">Finish</span>
                                                        </a>
                                                    </li> -->
                                                </ul>
                                            
                                                <div class="tab-content b-0 mb-0">

                                                    <div id="bar" class="progress mb-3" style="height: 7px;">
                                                        <div class="bar progress-bar progress-bar-striped progress-bar-animated bg-success"></div>
                                                    </div>


                                                    <div class="tab-pane active" id="package_details1">

                                                    
                            <div class="card">
                            <div class="card-body">
                            
                                                            <div class="row">
                                                            <div class="col-lg-6">
                                                        <!-- Single Date Picker -->
                                                        <div class="mb-3">
                                                            <label class="form-label">Package Name</label>
                                                            <input type="text" class="form-control" required="" name="package_name" >
                                                        </div>
                                                    </div>
        
                                                    
                                               
                                                            <div class="col-lg-6">
                                                        <!-- Single Date Picker -->
                                                        <div class="mb-3">
                                                            <label class="form-label">Check In</label>
                                                            <input type="text" class="form-control date" required="" name="check_in" id="birthdatepicker" data-toggle="date-picker" data-single-date-picker="true">
                                                        </div>
                                                    </div>
        
                                                    
                                                </div>
                                                <div class="row">
                                                            <div class="col-lg-6">
                                                        <!-- Single Date Picker -->
                                                        <div class="mb-3">
                                                            <label class="form-label">Check Out</label>
                                                            <input type="text" class="form-control date" required=""  name="check_out" id="birthdatepicker" data-toggle="date-picker" data-single-date-picker="true">
                                                        </div>
                                                    </div>
        
                                                    
                                              
                                               
                                                            <div class="col-lg-6">
                                                        <!-- Single Date Picker -->
                                                        <div class="mb-3">
                                                            <label class="form-label">Status</label>
                                                            <select required="" class="form-select mb-3" name="status">
                                                            <option selected="">Select Status</option>
                                                            <option value="0">Enable</option>
                                                            <option value="1">Disable</option>
                                                            
                                                        </select>
                                                        </div>
                                                    </div>
        
                                                    
                                                </div>
                                                                
                                        
                                                                
                                                                
                                                            
                            </div>
                        </div>
                   
                                                    </div>


                                            
                                                    <div class="tab-pane" id="basictab1">
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <label class="form-label">Hotel Name</label>
                                                            <input type="text" required="" class="form-control"  name="makkah_hotel_name" placeholder="Hotel Name">
                                                            </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <label class="form-label">Location</label>
                                                            <select required="" class="form-select mb-3" name="makkah_location">
                                                            <option selected="">Select location</option>
                                                            <option value="0">location1</option>
                                                            <option value="1">location2</option>
                                                            
                                                            </select>
                                                        </div>
                                                            </div>
        
                                                    
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <label class="form-label">Check In</label>
                                                            <input required="" type="text" class="form-control date"  name="makkah_check_in" id="makkah_check_in" data-toggle="date-picker" data-single-date-picker="true">
                                                            </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <label class="form-label">Check Out</label>
                                                            <input required="" type="text" class="form-control date"  name="makkah_check_out" id="makkah_check_out" data-toggle="date-picker" data-single-date-picker="true">
                                                        </div>
                                                            </div>
        
                                                    
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <label class="form-label">No Of Nights</label>
                                                            <input required="" type="text" class="form-control" id="makkah_no_of_nights" value=""  name="makkah_no_of_nights" placeholder="No Of Nights">
                                                            </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <label class="form-label">Rooms</label>
                                                            <select required="" class="form-select mb-3" name="makkah_room_views">
                                                            <option selected="">Select location</option>
                                                            <option value="0">location1</option>
                                                            <option value="1">location2</option>
                                                            
                                                            </select>
                                                        </div>
                                                            </div>
        
                                                    
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-3">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <div class="mt-2">
                                                    <div class="form-check form-check-inline">
                                                        <input type="checkbox" class="form-check-input" id="Sharing_1" name="">
                                                        <label class="form-check-label" for="customCheck3">Sharing #1</label>
                                                    </div>
                                                    <!-- <div class="form-check form-check-inline">
                                                        <input type="checkbox" class="form-check-input" id="Sharing_2" name="">
                                                        <label class="form-check-label" for="customCheck4">Sharing #2</label>
                                                    </div> -->
                                                </div>
                                                            </div>
                                                            </div>
                                                            <div class="col-lg-9">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <div class="mt-2">
                                                            <div class="row" id="Sharing_1_slide_down" style="display:none">
                                                            <div class="col-lg-6">
                                                            <div class="mb-3">
                                                            
                                                            <input type="text" class="form-control"  name="" id="enter_makkah_sharing_1_price" placeholder="Enter Price">
                                                            
                                                            </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                            <div class="mb-3">
                                                           
                                                            <input type="text" class="form-control"  name="makkah_sharing_1" id="makkah_sharing_1_total_price" placeholder="Total Price">
                                                            </div>
                                                            </div>
                                                            </div>
                                                </div>
                                                            </div>
                                                            </div>
                                                        </div>




                                                        <div class="row">
                                                            <div class="col-lg-3">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <div class="mt-2">
                                                    <div class="form-check form-check-inline">
                                                        <input type="checkbox" class="form-check-input" id="Sharing_2" name="">
                                                        <label class="form-check-label" for="customCheck3">Sharing #2</label>
                                                    </div>
                                                    <!-- <div class="form-check form-check-inline">
                                                        <input type="checkbox" class="form-check-input" id="Sharing_2" name="">
                                                        <label class="form-check-label" for="customCheck4">Sharing #2</label>
                                                    </div> -->
                                                </div>
                                                            </div>
                                                            </div>
                                                            <div class="col-lg-9">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <div class="mt-2">
                                                            <div class="row" id="Sharing_2_slide_down" style="display:none">
                                                            <div class="col-lg-6">
                                                            <div class="mb-3">
                                                            
                                                            <input type="text" class="form-control"  name="" id="enter_makkah_sharing_2_price" placeholder="Enter Price">
                                                            
                                                            </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                            <div class="mb-3">
                                                           
                                                            <input type="text" class="form-control"  name="makkah_sharing_2" id="makkah_sharing_2_total_price" placeholder="Total Price">
                                                            </div>
                                                            </div>
                                                            </div>
                                                </div>
                                                            </div>
                                                            </div>

                                                            
        
                                                    
                                                        </div>




                                                        <div class="row">
                                                            <div class="col-lg-3">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <div class="mt-2">
                                                    <div class="form-check form-check-inline">
                                                        <input type="checkbox" class="form-check-input" id="Sharing_3" name="">
                                                        <label class="form-check-label" for="customCheck3">Sharing #3</label>
                                                    </div>
                                                    <!-- <div class="form-check form-check-inline">
                                                        <input type="checkbox" class="form-check-input" id="Sharing_2" name="">
                                                        <label class="form-check-label" for="customCheck4">Sharing #2</label>
                                                    </div> -->
                                                </div>
                                                            </div>
                                                            </div>
                                                            <div class="col-lg-9">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <div class="mt-2">
                                                            <div class="row" id="Sharing_3_slide_down" style="display:none">
                                                            <div class="col-lg-6">
                                                            <div class="mb-3">
                                                            
                                                            <input type="text" class="form-control"  name="" id="enter_makkah_sharing_3_price" placeholder="Enter Price">
                                                            
                                                            </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                            <div class="mb-3">
                                                           
                                                            <input type="text" class="form-control"  name="makkah_sharing_3" id="makkah_sharing_3_total_price" placeholder="Total Price">
                                                            </div>
                                                            </div>
                                                            </div>
                                                </div>
                                                            </div>
                                                            </div>

                                                            
        
                                                    
                                                        </div>




                                                        <div class="row">
                                                            <div class="col-lg-3">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <div class="mt-2">
                                                    <div class="form-check form-check-inline">
                                                        <input type="checkbox" class="form-check-input" id="Sharing_4" name="">
                                                        <label class="form-check-label" for="customCheck3">Sharing #4</label>
                                                    </div>
                                                    <!-- <div class="form-check form-check-inline">
                                                        <input type="checkbox" class="form-check-input" id="Sharing_2" name="">
                                                        <label class="form-check-label" for="customCheck4">Sharing #2</label>
                                                    </div> -->
                                                </div>
                                                            </div>
                                                            </div>
                                                            <div class="col-lg-9">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <div class="mt-2">
                                                            <div class="row" id="Sharing_4_slide_down" style="display:none">
                                                            <div class="col-lg-6">
                                                            <div class="mb-3">
                                                            
                                                            <input type="text" class="form-control"  name="" id="enter_makkah_sharing_4_price" placeholder="Enter Price">
                                                            
                                                            </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                            <div class="mb-3">
                                                           
                                                            <input type="text" class="form-control"  name="makkah_sharing_4" id="makkah_sharing_4_total_price" placeholder="Total Price">
                                                            </div>
                                                            </div>
                                                            </div>
                                                </div>
                                                            </div>
                                                            </div>

                                                            
        
                                                    
                                                        </div>








                                                        
                                                            



                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <label class="form-label">Total Price</label>
                                                            <input required="" type="text" class="form-control"  name="makkah_price" placeholder="Total Price">
                                                            </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <label class="form-label">Board Basis</label>
                                                            <select required="" class="form-select mb-3" name="makkah_board_basis">
                                                            <option selected="">Select Board Basis</option>
                                                            <option value="0">Full Board</option>
                                                            <option value="1">Half Board</option>
                                                            
                                                            </select>
                                                            </div>
                                                            </div>
                                                            
        
                                                    
                                                        </div>

                                                        <div class="row">
                                                        <div class="col-lg-12">
                                                        <div class="mb-3">
                                                            <label class="form-label">Images</label>
                                                        <input name="makkah_images" class="form-control" type="file" multiple />
                                                            </div>
                                                            </div>
                                                            </div>













                                                    </div>

                                                    <div class="tab-pane" id="profile-tab-2">
                                                    <div class="row">
                                                            <div class="col-lg-6">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <label class="form-label">Hotel Name</label>
                                                            <input required="" type="text" class="form-control"  name="madina_hotel_name" placeholder="Hotel Name">
                                                            </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <label class="form-label">Location</label>
                                                            <select class="form-select mb-3" name="madina_location">
                                                            <option selected="">Select location</option>
                                                            <option value="0">location1</option>
                                                            <option value="1">location2</option>
                                                            
                                                            </select>
                                                        </div>
                                                            </div>
        
                                                    
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <label class="form-label">Check In</label>
                                                            <input required="" type="text" class="form-control date"  name="madina_check_in" id="madina_check_in" data-toggle="date-picker" data-single-date-picker="true">
                                                            </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <label class="form-label">Check Out</label>
                                                            <input required="" type="text" class="form-control date"  name="madina_check_out" id="madina_check_out" data-toggle="date-picker" data-single-date-picker="true">
                                                        </div>
                                                            </div>
        
                                                    
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <label class="form-label">No Of Nights</label>
                                                            <input required="" type="text" class="form-control" id="madina_no_of_nights" value=""  name="madina_no_of_nights" placeholder="No Of Nights">
                                                            </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <label class="form-label">Rooms</label>
                                                            <select required="" class="form-select mb-3" name="Status">
                                                            <option selected="">Select location</option>
                                                            <option value="0">location1</option>
                                                            <option value="1">location2</option>
                                                            
                                                            </select>
                                                        </div>
                                                            </div>
        
                                                    
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-3">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <div class="mt-2">
                                                    <div class="form-check form-check-inline">
                                                        <input type="checkbox" class="form-check-input" id="Sharing_1_madina" name="">
                                                        <label class="form-check-label" for="customCheck3">Sharing #1</label>
                                                    </div>
                                                    <!-- <div class="form-check form-check-inline">
                                                        <input type="checkbox" class="form-check-input" id="Sharing_2" name="">
                                                        <label class="form-check-label" for="customCheck4">Sharing #2</label>
                                                    </div> -->
                                                </div>
                                                            </div>
                                                            </div>
                                                            <div class="col-lg-9">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <div class="mt-2">
                                                            <div class="row" id="Sharing_1_slide_down_madina" style="display:none">
                                                            <div class="col-lg-6">
                                                            <div class="mb-3">
                                                            
                                                            <input type="text" class="form-control"  name="" id="enter_madina_sharing_1_price" placeholder="Enter Price">
                                                            
                                                            </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                            <div class="mb-3">
                                                           
                                                            <input type="text" class="form-control"  name="madina_sharing_1" id="madina_sharing_1_total_price" placeholder="Total Price">
                                                            </div>
                                                            </div>
                                                            </div>
                                                </div>
                                                            </div>
                                                            </div>
                                                        </div>




                                                        <div class="row">
                                                            <div class="col-lg-3">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <div class="mt-2">
                                                    <div class="form-check form-check-inline">
                                                        <input type="checkbox" class="form-check-input" id="Sharing_2_madina" name="">
                                                        <label class="form-check-label" for="customCheck3">Sharing #2</label>
                                                    </div>
                                                    <!-- <div class="form-check form-check-inline">
                                                        <input type="checkbox" class="form-check-input" id="Sharing_2" name="">
                                                        <label class="form-check-label" for="customCheck4">Sharing #2</label>
                                                    </div> -->
                                                </div>
                                                            </div>
                                                            </div>
                                                            <div class="col-lg-9">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <div class="mt-2">
                                                            <div class="row" id="Sharing_2_slide_down_madina" style="display:none">
                                                            <div class="col-lg-6">
                                                            <div class="mb-3">
                                                            
                                                            <input type="text" class="form-control"  name="" id="enter_madina_sharing_2_price" placeholder="Enter Price">
                                                            
                                                            </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                            <div class="mb-3">
                                                           
                                                            <input type="text" class="form-control"  name="madina_sharing_2" id="madina_sharing_2_total_price" placeholder="Total Price">
                                                            </div>
                                                            </div>
                                                            </div>
                                                </div>
                                                            </div>
                                                            </div>

                                                            
        
                                                    
                                                        </div>




                                                        <div class="row">
                                                            <div class="col-lg-3">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <div class="mt-2">
                                                    <div class="form-check form-check-inline">
                                                        <input type="checkbox" class="form-check-input" id="Sharing_3_madina" name="">
                                                        <label class="form-check-label" for="customCheck3">Sharing #3</label>
                                                    </div>
                                                    <!-- <div class="form-check form-check-inline">
                                                        <input type="checkbox" class="form-check-input" id="Sharing_2" name="">
                                                        <label class="form-check-label" for="customCheck4">Sharing #2</label>
                                                    </div> -->
                                                </div>
                                                            </div>
                                                            </div>
                                                            <div class="col-lg-9">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <div class="mt-2">
                                                            <div class="row" id="Sharing_3_slide_down_madina" style="display:none">
                                                            <div class="col-lg-6">
                                                            <div class="mb-3">
                                                            
                                                            <input type="text" class="form-control"  name="" id="enter_madina_sharing_3_price" placeholder="Enter Price">
                                                            
                                                            </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                            <div class="mb-3">
                                                           
                                                            <input type="text" class="form-control"  name="madina_sharing_3" id="madina_sharing_3_total_price" placeholder="Total Price">
                                                            </div>
                                                            </div>
                                                            </div>
                                                </div>
                                                            </div>
                                                            </div>

                                                            
        
                                                    
                                                        </div>




                                                        <div class="row">
                                                            <div class="col-lg-3">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <div class="mt-2">
                                                    <div class="form-check form-check-inline">
                                                        <input type="checkbox" class="form-check-input" id="Sharing_4_madina" name="">
                                                        <label class="form-check-label" for="customCheck3">Sharing #4</label>
                                                    </div>
                                                    <!-- <div class="form-check form-check-inline">
                                                        <input type="checkbox" class="form-check-input" id="Sharing_2" name="">
                                                        <label class="form-check-label" for="customCheck4">Sharing #2</label>
                                                    </div> -->
                                                </div>
                                                            </div>
                                                            </div>
                                                            <div class="col-lg-9">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <div class="mt-2">
                                                            <div class="row" id="Sharing_4_slide_down_madina" style="display:none">
                                                            <div class="col-lg-6">
                                                            <div class="mb-3">
                                                            
                                                            <input type="text" class="form-control"  name="" id="enter_madina_sharing_4_price" placeholder="Enter Price">
                                                            
                                                            </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                            <div class="mb-3">
                                                           
                                                            <input type="text" class="form-control"  name="madina_sharing_4" id="madina_sharing_4_total_price" placeholder="Total Price">
                                                            </div>
                                                            </div>
                                                            </div>
                                                </div>
                                                            </div>
                                                            </div>

                                                            
        
                                                    
                                                        </div>






                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <label class="form-label">Total Price</label>
                                                            <input type="text" class="form-control"  name="madina_price" placeholder="Total Price">
                                                            </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <label class="form-label">Board Basis</label>
                                                            <select class="form-select mb-3" name="madina_board_basis">
                                                            <option selected="">Select Board Basis</option>
                                                            <option value="0">Full Board</option>
                                                            <option value="1">Half Board</option>
                                                            
                                                            </select>
                                                            </div>
                                                            </div>
                                                            
        
                                                    
                                                        </div>
                                                        <div class="row">
                                                        <div class="col-lg-12">
                                                        <div class="mb-3">
                                                            <label class="form-label">Images</label>
                                                        <input name="madina_images" class="form-control" type="file" multiple />
                                                            </div>
                                                            </div>
                                                            </div>
                                                    </div>



                                                    
                                                    <div class="tab-pane" id="transfer-tab-3">
                                                    <div class="row">
                                                            <div class="col-lg-6">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <label class="form-label">Pickup Location</label>
                                                            <select class="form-select mb-3" name="transfer_pickup_location">
                                                            <option selected="">Select Pickup Location</option>
                                                            <option value="0">Location1</option>
                                                            <option value="1">Location2</option>
                                                            
                                                            </select>
                                                            </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <label class="form-label">Drop Location</label>
                                                            <select class="form-select mb-3" name="transfer_drop_location">
                                                            <option selected="">Select Drop Location</option>
                                                            <option value="0">Location</option>
                                                            <option value="1">Location2</option>
                                                            
                                                            </select>
                                                            </div>
                                                            </div>
                                                            
        
                                                    
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <label class="form-label">PickUp Date</label>
                                                            <input type="text" class="form-control date"  name="transfer_pickup_date_time" id="birthdatepicker" data-toggle="date-picker" data-single-date-picker="true">
                                                            </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <label class="form-label">Vehicle</label>
                                                            <select class="form-select mb-3" name="transfer_vehicle">
                                                            <option selected="">Select Vehicle</option>
                                                            <option value="0">Bus</option>
                                                            <option value="1">Vagon</option>
                                                            
                                                            </select>
                                                            </div>
                                                            </div>
                                                            
        
                                                    
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-12">
                                                            <label class="form-label">Image</label>
                                                            <input type="file" class="form-control"  name="transfer_images" placeholder="Total Price">
                                                            </div>
                                                            </div>
                                                            
                                                            
        
                                                    
                                                        </div>

                                                    </div>


                                                    <div class="tab-pane" id="Flights-tab-4">
                                                    <div class="row">
                                                            <div class="col-lg-6">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <label class="form-label">Airline</label>
                                                            <select class="form-select mb-3" name="flights_airline">
                                                            <option selected="">Select Airline</option>
                                                            <option value="0">Airline</option>
                                                            <option value="1">Airline2</option>
                                                            
                                                            </select>
                                                            </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <label class="form-label">Departure Airport</label>
                                                            <select class="form-select mb-3" name="flights_departure_airport">
                                                            <option selected="">Select Departure Airport</option>
                                                            <option value="0">Departure Airport1</option>
                                                            <option value="1">LocatiDeparture Airport2on2</option>
                                                            
                                                            </select>
                                                            </div>
                                                            </div>
                                                            
        
                                                    
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <label class="form-label">Arrival Airport</label>
                                                            <select class="form-select mb-3" name="flights_arrival_airport">
                                                            <option selected="">Select Arrival Airport</option>
                                                            <option value="0">Arrival Airport1</option>
                                                            <option value="1">Arrival Airport2</option>
                                                            
                                                            </select>
                                                            </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <label class="form-label">Departure Return Airport</label>
                                                            <select class="form-select mb-3" name="flights_departure__return_airport">
                                                            <option selected="">Select Departure Return Airport</option>
                                                            <option value="0">Departure Return Airport1</option>
                                                            <option value="1">Departure Return Airport</option>
                                                            
                                                            </select>
                                                            </div>
                                                            </div>
                                                            
        
                                                    
                                                        </div>
                                                        <div class="row">
                                                        <div class="col-lg-4">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <label class="form-label">Arrival Return Airport</label>
                                                            <select class="form-select mb-3" name="flights_arrival_return_airport">
                                                            <option selected="">Select Arrival Return Airport</option>
                                                            <option value="0">Arrival Return Airport1</option>
                                                            <option value="1">Arrival Return Airport</option>
                                                            
                                                            </select>
                                                            </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <label class="form-label">Departure Return Date</label>
                                                            <input type="text"  class="form-control date"  name="flights_departure__return_date" id="birthdatepicker" data-toggle="date-picker" data-single-date-picker="true">
                                                            </div>
                                                            </div>
                                                            <div class="col-lg-4">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <label class="form-label">Arrival Return Date</label>
                                                            <input type="text"  class="form-control date"  name="flights_arrival_return_date" id="birthdatepicker" data-toggle="date-picker" data-single-date-picker="true">

                                                            </div>
                                                            </div>
                                                            
                                                            
        
                                                    
                                                        </div>

                                                    </div>

                                                  
                                                    <div class="tab-pane" id="visa-tab-5">

                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <label class="form-label">Visa Fee</label>
                                                           <input type="text" class="form-control" name="visa_fee">
                                                            </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <label class="form-label">Visit Fee</label>
                                                           <input type="text" class="form-control" name="visit_visa_fee">
                                                            </div>
                                                            </div>
                                                    
                                                        </div>
                                                        <div class="row">
                                                        <div class="col-lg-12">
                                                            <!-- Single Date Picker -->
                                                            <div id="snow-editor" style="height: 300px;">
                                    <h3><span class="ql-size-large">Hello World!</span></h3>
                                    <p><br></p>
                                    <h3>This is an simple editable area.</h3>
                                    <p><br></p>
                                    <ul>
                                        <li>
                                            Select a text to reveal the toolbar.
                                        </li>
                                        <li>
                                            Edit rich document on-the-fly, so elastic!
                                        </li>
                                    </ul>
                                    <p><br></p>
                                    <p>
                                        End of simple area
                                    </p>
</div>
                                                            </div>
                                                    
                                                        </div>

                                                    </div>
                                                    <div class="tab-pane" id="administration-tab-6">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <!-- Single Date Picker -->
                                                            <div class="mb-3">
                                                            <label class="form-label">Amount</label>
                                                           <input type="text" class="form-control" name="administration_charges">
                                                            </div>
                                                            </div>
                                                    
                                                        </div>
                                                        <div class="row">
                                                        <div class="col-lg-12">
                                                            <!-- Single Date Picker -->
                                                            <div id="snow-editor_1" style="height: 300px;">
    <h3><span class="ql-size-large">Hello World!</span></h3>
    <p><br></p>
    <h3>This is an simple editable area.</h3>
    <p><br></p>
    <ul>
        <li>
            Select a text to reveal the toolbar.
        </li>
        <li>
            Edit rich document on-the-fly, so elastic!
        </li>
    </ul>
    <p><br></p>
    <p>
        End of simple area
    </p>
</div>
                                                            </div>
                                                    
                                                        </div>
                                                    </div>

                                                    <div class="tab-pane" id="submit-tab-7">
                                                    <div class="row">
                                                            <div class="col-12">
                                                                <div class="text-center">
                                                                    <h2 class="mt-0"><i class="mdi mdi-check-all"></i></h2>
                                                                    <h3 class="mt-0">Thank you !</h3>

                                                                    <p class="w-75 mb-2 mx-auto">Quisque nec turpis at urna dictum luctus. Suspendisse convallis dignissim eros at volutpat. In egestas mattis dui. Aliquam
                                                                        mattis dictum aliquet.</p>
 <button type="submit" name="submit" class="btn btn-info">Submit</button>
                                                                    
                                                                </div>
                                                            </div> <!-- end col -->
                                                        </div>
                                                    </div>

                                                   

                                                    <ul class="list-inline mb-0 wizard">
                                                        <li class="previous list-inline-item">
                                                            <a href="javascript:void(0);" class="btn btn-info">Previous</a>
                                                        </li>
                                                        <li class="next list-inline-item float-end">
                                                       
                                                            <a href="javascript:void(0);" class="btn btn-info">Next</a>
                                                        </li>
                                                    </ul>

                                                </div> <!-- tab-content -->
                                            </div> <!-- end #progressbarwizard-->
                                        </form>

                                    </div> <!-- end card-body -->
                                </div> <!-- end card-->
                            </div> <!-- end col -->

                        </div>   
                        
                        

                       
 @endsection