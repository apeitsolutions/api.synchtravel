@extends('template/frontend/userdashboard/layout/default')
@section('content')

  <style>
    .nav-link{
      color: #575757;
      font-size: 18px;
    }
  </style>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="content" style="padding: 10px 20px 0px 20px">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-primary">
              <div class="inner">
                <h3>150</h3>

                <p>Total Flights</p>
              </div>
              <div class="icon">
                <i class="ion ion-plane"></i>
              </div>
              <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>53<sup style="font-size: 20px">%</sup></h3>

                <p>Total Hotels</p>
              </div>
              <div class="icon">
                <i class="ion ion-calculator"></i>
              </div>
              <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box" style="background-color: #d262e3!important;">
              <div class="inner">
                <h3>44</h3>

                <p>Total Transfer</p>
              </div>
              <div class="icon">
                <i class="ion-android-car"></i>
              </div>
              <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>65</h3>

                <p>Commision</p>
              </div>
              <div class="icon">
                <i class="ion ion-cash"></i>
              </div>
              <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
            </div>
          </div>
          <!-- ./col -->
        </div>
      </div>
    </section>
    <!-- /.content -->

    <!-- Dashboard -->
    <section class="content" style="padding: 30px 50px 0px 50px;">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12 col-6">
            <nav class="breadcrumb push">
              <a class="breadcrumb-item" href="#">Dashboard</a> 
              <span class="breadcrumb-item active">Edit Quotation</span>
            </nav>
          </div>
        </div>
      </div>
    </section>

    <section class="content" style="padding: 30px 50px 0px 50px;">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12 col-6">
            <div class="progress rounded-0" data-wizard="progress" style="height: 10px;">
              <div class="progress-bar progress-bar-striped progress-bar-animated PB" role="progressbar" style="width:220px;" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <ul class="nav nav-tabs nav-tabs-alt nav-fill" role="tablist" style="background-color: white;">
              <li class="nav-item">
                <a id="LPD_1" class="nav-link active" href="#wizard-step1" data-bs-toggle="tab" data-tabid="1">1. Lead Passenger Details</a>
              </li>
              <li class="nav-item">
                <a id="LPD_2" class="nav-link" href="#wizard-step2" data-bs-toggle="tab" data-tabid="2" >2. Additional Details</a>
              </li>
              <li class="nav-item">
                <a id="LPD_3" class="nav-link" href="#wizard-step3" data-bs-toggle="tab" data-tabid="3" >3. Search Flights </a>
              </li>
               <li class="nav-item">
                <a id="LPD_4" class="nav-link" href="#wizard-step4" data-bs-toggle="tab" data-tabid="4" >4. Makkah Hotels</a>
              </li>
              <li class="nav-item">
                <a id="LPD_5" class="nav-link" href="#wizard-step5" data-bs-toggle="tab" data-tabid="5" >5. Madinah Hotels</a>
              </li>
              <li class="nav-item">
                <a id="LPD_6" class="nav-link" href="#wizard-step6" data-bs-toggle="tab" data-tabid="6" >6. Search Transfers </a>
              </li>
               <li class="nav-item">
                <a id="LPD_7" class="nav-link" href="#wizard-step7" data-bs-toggle="tab" data-tabid="7" >7. Visa Fee </a>
              </li>
            </ul>
            <!-- Form -->
            <form action="{{url('update_Manage_Quotation/'.$data->id)}}" method="POST" id="quatation" class="js-wizard-validation-material-form" novalidate="novalidate">
              @csrf
              <div class="block-content block-content-full tab-content" style="min-height: 274px;background-color: white;">
                
                <!-- Step 1 -->
                <div class="tab-pane active show" id="wizard-step1" role="tabpanel">
                  <div class="row" style="padding: 10px;">
                    <div class="form-group col-sm-4">
                      <div class="form-material floating open">
                        <label for="material-select2">Prefix*</label>
                        <select name="prefix" class="form-control valid" id="material-select2" name="material-select2" required aria-describedby="material-select2-error" aria-invalid="false">
                            <option value="<?php echo $data->prefix; ?>"><?php echo $data->prefix; ?></option>
                            <option value="1">Mr.</option>
                            <option value="2">Miss</option>
                            <option value="3">Mrs.</option>
                        </select>
                      </div>
                      @error('prefix')
                        <div class="alert alert-danger">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="form-material floating open">
                        <label for="fname">First Name</label>
                        <input value="<?php echo $data->f_name; ?>" name="f_name" class="form-control valid" type="text" id="fname" required="" aria-describedby="fname-error" aria-invalid="false">
                      </div>
                      @error('f_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="form-material floating open">
                        <label for="middle-name">Middle Name</label>
                        <input value="<?php echo $data->middle_name; ?>" name="middle_name" class="form-control valid" type="text" id="middle-name" aria-describedby="middle-name-error" aria-invalid="false">
                      </div>
                      @error('middle_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="form-material floating open">
                        <label for="surname">Surname</label>
                        <input value="<?php echo $data->surname;?>" name="surname" class="form-control valid" type="text" id="surname"  required="" aria-describedby="surname-error" aria-invalid="false">
                      </div>
                      @error('surname')
                        <div class="alert alert-danger">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="form-material floating open">
                        <label for="customer-email">Customer Email</label>
                        <input value="<?php echo $data->email;?>" name="email" class="form-control valid" type="email" id="customer-email" required="" aria-describedby="customer-email-error" aria-invalid="false">
                      </div>
                      @error('email')
                        <div class="alert alert-danger">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="form-material floating">
                        <label for="landline"> Contact Landline <small class="text-muted">(999) 999-9999</small></label>
                        <input value="<?php echo $data->contact_landline;?>" name="contact_landline" type="text" class="js-masked-phone form-control js-masked-enabled valid" id="landline"  autocomplete="off" aria-describedby="landline-error" aria-invalid="false">
                      </div>
                      @error('contact_landline')
                        <div class="alert alert-danger">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="form-material floating">
                        <label for="mobile">Customer Mobile <small class="text-muted">(999) 999-9999</small></label>
                        <input value="<?php echo $data->mobile;?>" name="mobile" type="text" class="js-masked-phone form-control js-masked-enabled valid" id="mobile" autocomplete="off" aria-describedby="mobile-error" aria-invalid="false">
                      </div>
                      @error('mobile')
                        <div class="alert alert-danger">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="form-material floating open">
                        <label for="example-masked2-phone">Contact Work <small class="text-muted">(999) 999-9999</small></label>
                        <input value="<?php echo $data->contact_work;?>" name="contact_work" type="text" class="js-masked-phone form-control js-masked-enabled valid" id="example-masked2-phone" autocomplete="off" aria-describedby="example-masked2-phone-error" aria-invalid="false">
                      </div>
                      @error('contact_work')
                        <div class="alert alert-danger">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="form-material floating open">
                        <label for="pcode">Post Code</label>
                        <input value="<?php echo $data->postCode;?>" name="postCode" class="form-control valid" type="text" id="pcode" required="" aria-describedby="pcode-error" aria-invalid="false">
                      </div>
                      @error('postCode')
                        <div class="alert alert-danger">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="form-material floating open">
                        <label for="material-select2">Select Country</label>
                        <select name="country" class="form-control valid" id="country" required="" aria-describedby="country-error" aria-invalid="false">
                          <option value="<?php echo $data->country;?>"><?php echo $data->country;?></option>
                          <option value="Zambia">Zambia</option>
                          <option value="Zimbabwe">Zimbabwe</option>
                        </select>
                      </div>
                      @error('country')
                        <div class="alert alert-danger">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="form-material floating open">
                        <label for="city">Select City</label>
                        <select name="city" class="form-control valid" id="city" aria-describedby="city-error" aria-invalid="false">
                          <option value="{{$data->city}}">{{$data->city}}</option>
                          <option value="Abullah-as-Salam "> Abullah-as-Salam </option>
                          <option value="az-Zawr"> az-Zawr </option>
                        </select>
                      </div>
                      @error('city')
                        <div class="alert alert-danger">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="form-group col-sm-6">
                      <div class="form-material">
                        <label for="primery-address">Primery Address</label>
                        <textarea value="<?php echo $data->primery_address;?>" name="primery_address" class="form-control valid" id="primery-address" placeholder="Enter Primery Address" rows="3" autocomplete="off" aria-describedby="primery-address-error" aria-invalid="false"><?php echo $data->primery_address;?></textarea>
                      </div>
                      @error('primery_address')
                        <div class="alert alert-danger">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="form-group col-sm-3">
                      <div class="form-material floating open">
                        <label for="quotation-prepared">Quotation Prepared By</label>
                        <input value="<?php echo $data->quotation_prepared;?>" name="quotation_prepared" class="form-control valid" type="text" id="quotation-prepared" autocomplete="off" aria-describedby="quotation-prepared-error" aria-invalid="false">
                      </div>
                      @error('quotation_prepared')
                        <div class="alert alert-danger">{{ $message }}</div>
                      @enderror
                    </div>
                    <div class="form-group col-sm-3">
                      <div class="form-material">
                        <label for="example-datepicker6">Quotation Valid Date</label>
                        <input value="<?php echo $data->quotation_valid_date;?>" name="quotation_valid_date" type="text" class="js-datepicker form-control js-datepicker-enabled valid" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy" autocomplete="off" aria-describedby="example-datepicker6-error" aria-invalid="false">
                      </div>
                      @error('quotation_valid_date')
                        <div class="alert alert-danger">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>
                <!-- END Step 1 -->
                
                <!-- Step 2 -->      
                <div class="tab-pane" id="wizard-step2" role="tabpanel">
                  <div class="row" style="padding: 10px;">
                    <div class="form-group col-sm-4">
                      <div class="form-material floating">
                        <label for="adults">Number Of Adults</label>
                        <select id="adults" name="adults" class="form-control" id="adults" required="">
                          <option value="<?php echo $data->adults; ?>"><?php echo $data->adults; ?></option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="form-material floating">
                        <label for="childs">Number Of Children</label>
                        <select name="childs" class="form-control" id="childs" required="">
                          <option  value="<?php echo $data->childs; ?>"><?php echo $data->childs; ?></option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="form-material floating">
                        <label for="infant">Number Of Infant</label>
                        <select name="infant" class="form-control" id="infant" required="">
                          <option  value="<?php echo $data->infant; ?>"><?php echo $data->infant; ?></option>
                        </select>
                      </div>
                    </div>

                    <!-- Pop Out Modal -->
                    <div class="block">
                      <div class="block-content block-content-full">
                        <button data-bs-toggle="modal" data-bs-target="#exampleModalCenter" type="button" 
                        class="btn btn-square btn-outline-info min-width-125 mb-10"  data-target="#modal-popout">Edit More Details</button>
                      </div>
                    </div>
                    <!-- END Pop Out Modal -->
                  </div>
                </div>
                <!-- END Step 2 -->

                <!-- Step 3 -->
                <div class="tab-pane" id="wizard-step3" role="tabpanel" style="padding: 10px;">
                  <h3>Manage Flights</h3>
                    
                    @if(!isset($decode_DataRT) || $decode_DataRT == null || $decode_DataRT == "")
                        <div>EMPTY</div>
                    @else
                        <div class="row" id="one_way_div">
                        <div class="form-group col-sm-2">                                    
                          <div class="form-material floating">
                            <div class="flight">
                              <label for="search-box">Departure</label>
                              <input value="<?php echo $decode_DataOW->departure[0]; ?>" type="text" required="" name="departure[]" class="form-control get_flight_name" autocomplete="off" id="search-box" data-suggestion="suggesstion-box" placeholder="Departure..">
                              <div id="suggesstion-box"></div>  
                              <input class="departure shrtcode" type="hidden">
                            </div>
                          </div>
                        </div>                       
                        <div class="form-group col-sm-2">                                    
                            <div class="form-material floating">
                              <div class="flight1">
                                <label for="dep_date">Departure Date</label>
                                <input value="<?php echo $decode_DataOW->deprturedate[0]; ?>" name="deprturedate[]" type="date" required="" id="dep_date" class="js-datepicker form-control js-datepicker-enabled" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy" autocomplete="off">
                              </div>
                            </div>
                        </div>                       
                        <div class="form-group col-sm-2">                                    
                            <div class="form-material floating">
                              <div class="flight1">
                                <label for="dep_time">Departure Time</label>
                                <input value="<?php echo $decode_DataOW->deprturetime[0]; ?>"  name="deprturetime[]" type="time" id="dep_time" required="" class="form-control" placeholder="Airline">
                              </div>
                            </div>
                        </div>                       
                        <div class="form-group col-sm-2">                                    
                            <div class="form-material floating">
                              <div class="flight3">
                                <label for="search-box2">Arrival</label>
                                <input value="<?php echo $decode_DataOW->arrival[0]; ?>" name="arrival[]" type="text" required="" class="form-control get_flight_name" autocomplete="off" id="search-box2" data-suggestion="suggesstion-box2" placeholder="Arrival">
                                <div id="suggesstion-box2"></div>
                                <input class="arrival shrtcode" type="hidden">
                              </div>
                            </div>
                        </div>
                        <div class="form-group col-sm-2">                                    
                          <div class="form-material floating">
                            <div class="flight1">
                              <label for="arr_Date">Arrival Date</label>
                              <input value="<?php echo $decode_DataOW->ArrivalDate[0]; ?>" name="ArrivalDate[]" type="date" required="" id="arr_Date" class="js-datepicker form-control js-datepicker-enabled" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy" autocomplete="off">
                            </div>
                          </div>
                        </div>                       
                        <div class="form-group col-sm-2">                                    
                            <div class="form-material floating">
                              <div class="flight1">
                                <label for="arrival_time">Arrival Time</label>
                                <input value="<?php echo $decode_DataOW->ArrivalTime[0]; ?>" name="ArrivalTime[]" type="time" required="" id="arrival_time" class="form-control" placeholder="Airline">
                              </div>
                            </div>
                        </div>                       
                        <div class="form-group col-sm-4">                                    
                          <div class="form-material floating">
                            <div class="flight1">
                              <label for="ariline">Airline</label>
                              <input value="<?php echo $decode_DataOW->airline_name[0]; ?>" name="airline_name[]" type="text" required="" class="form-control" autocomplete="off" id="ariline" placeholder="Airline">
                            </div>
                          </div>
                        </div>
                      </div>

                        <div class="row" id="round_way_div">
                          <div class="form-group col-sm-12">  
                            <hr>
                            <h5 id="return_heading">Flight Return Details</h5>
                            <h5 id="multi_heading" style="display: none;">Flight Multi city Details</h5>
                          </div>
                            
                            
                            
                          <div class="form-group col-sm-2"> 
                            <div class="form-material floating">
                              <div class="flight">
                                @foreach ($decode_DataRT->departure_return as $value)
                                  <label for="search-box_return">Departure</label>
                                  <input type="text" value="<?php echo $value; ?>" name="departure_return[]" class="form-control get_flight_name" autocomplete="off" id="search-box_return" data-suggestion="suggesstion-box_return" placeholder="Departure..">
                                @endforeach
                                <div id="suggesstion-box_return"></div>
                                <input class="departure shrtcode" type="hidden">
                              </div>
                            </div>
                          </div>
                      
                          <div class="form-group col-sm-2">                                                        
                            <div class="form-material floating">
                              <div class="flight1">
                                @foreach ($decode_DataRT->deprturedate_return as $value)
                                  <label for="dep_date_return">Departure Date</label>
                                  <input value="<?php echo $value; ?>" name="deprturedate_return[]" type="date" id="dep_date_return" class="js-datepicker form-control js-datepicker-enabled" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy" autocomplete="off">
                                @endforeach
                              </div>
                            </div>
                          </div>
    
                          <div class="form-group col-sm-2">                                                          
                            <div class="form-material floating">
                              <div class="flight1">
                                @foreach ($decode_DataRT->deprturetime_return as $value)
                                  <label for="dep_time_return">Departure Time</label>
                                  <input value="<?php echo $value; ?>" type="time" id="dep_time_return" class="form-control" name="deprturetime_return[]" placeholder="Airline">
                                @endforeach                  
                              </div>
                            </div>
                          </div>
    
                          <div class="form-group col-sm-2">                                                          
                            <div class="form-material floating">
                              <div class="flight3">
                                @foreach ($decode_DataRT->arrival_return as $value)
                                  <label for="search-box_return2">Arrival</label>
                                  <input value="<?php echo $value; ?>" type="text" name="arrival_return[]" class="form-control get_flight_name" autocomplete="off" id="search-box_return2" data-suggestion="suggesstion-box_return2" placeholder="Arrival">
                                @endforeach
                                <div id="suggesstion-box_return2"></div>
                                <input class="arrival shrtcode" type="hidden">                      
                              </div>
                            </div>
                          </div>
    
                          <div class="form-group col-sm-2">                                                          
                            <div class="form-material floating">
                              <div class="flight1">
                                @foreach ($decode_DataRT->ArrivalDate_return as $value)
                                  <label for="arr_Date_return">Arrival Date</label>
                                  <input value="<?php echo $value; ?>" name="ArrivalDate_return[]" type="date" id="arr_Date_return" class="js-datepicker form-control js-datepicker-enabled"  data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy" autocomplete="off">
                                @endforeach
                              </div>
                            </div>
                          </div>
    
                          <div class="form-group col-sm-2">                                                        
                            <div class="form-material floating">
                              <div class="flight1">
                                @foreach ($decode_DataRT->ArrivalTime_return as $value)
                                  <label for="arrival_time_return">Arrival Time</label>
                                  <input value="<?php echo $value; ?>" type="time" id="arrival_time_return" class="form-control" name="ArrivalTime_return[]" placeholder="Airline">
                                @endforeach
                              </div>
                            </div>
                          </div>
    
                          <div class="form-group col-sm-4">                                                          
                            <div class="form-material floating">
                              <div class="flight1">
                                @foreach ($decode_DataRT->airline_name_return as $value)
                                  <label for="ariline_return">Airline</label>
                                  <input value="<?php echo $value; ?>" type="text" class="form-control" name="airline_name_return[]" autocomplete="off" id="ariline_return" placeholder="Airline">
                                @endforeach
                              </div>
                            </div>
                          </div>
                            
                            
                            
                      </div> 
                    @endif
                  <!-- id="add_multiCity" -->
                  <div class="add_multiCity">
                    <div id="div1_1" class="element1">                      
                    </div>
                  </div>
                  <div class="row">
                      <div class="col-md-12 text-right"><button id="multicity_div" style="display:none" type="button" class="btn btn-primary" >Add New City</button></div>
                  </div>                      
                  <div class="row">
                    <div class="form-group col-sm-2">                                    
                      <div class="form-material floating">
                       <div class="flight1">
                        <label for="date_f">Date</label>
                        <input value="<?php echo $data->f_date; ?>" name="f_date" type="text" id="date_f" class="js-datepicker form-control js-datepicker-enabled" autocomplete="off" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="mm/dd/yy" placeholder="mm/dd/yy">
                      </div>
                    </div>
                  </div>                        
                  <div class="form-group col-sm-2">                                    
                    <div class="form-material floating">
                      <div class="flight1">
                        <label for="f_adults">Adults</label>
                        <select name="f_adults" class="form-control f_adults" required="" id="f_adults" >
                          <option value="<?php echo $data->f_adults; ?>"><?php echo $data->f_adults; ?></option>
                          <option value="0">0</option>
                          <option value="1">1</option>
                          <option value="2">2</option>  
                          <option value="3">3</option>  
                          <option value="4">4</option>  
                          <option value="5">5</option>  
                          <option value="6">6</option>  
                          <option value="7">7</option>  
                          <option value="8">8</option>  
                          <option value="9">9</option>
                        </select>
                      </div>
                    </div>
                  </div>                         
                  <div class="form-group col-sm-2">                                    
                      <div class="form-material floating">
                        <div class="flight1">
                          <label for="f_children">Children</label>
                          <select class="form-control f_children" required="" id="f_children" name="f_children">
                            <option value="<?php echo $data->f_children; ?>"><?php echo $data->f_children; ?></option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>  
                            <option value="3">3</option>  
                            <option value="4">4</option>  
                            <option value="5">5</option>  
                            <option value="6">6</option>  
                            <option value="7">7</option>  
                            <option value="8">8</option>  
                            <option value="9">9</option>
                          </select>
                        </div>
                      </div>
                     </div>
                     
                     <div class="form-group col-sm-2">                                    
                        <div class="form-material floating">
                           <div class="flight1">
                             <label for="f_infant">Infant</label>
                              <select class="form-control f_infant" required="" id="f_infant" name="f_infant">
                                <option value="<?php echo $data->f_infant; ?>"><?php echo $data->f_infant; ?></option>
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>  
                                <option value="3">3</option>  
                                <option value="4">4</option>  
                                <option value="5">5</option>  
                                <option value="6">6</option>  
                                <option value="7">7</option>  
                                <option value="8">8</option>  
                                <option value="9">9</option>
                              </select>
                          </div>
                        </div>
                     </div>
                     
                 
                      <div class="form-group col-sm-2">                                    

                          <div class="form-material floating">
                               <div class="flight1">
                                 <label for="flighttype">Type</label>
                                  <select value="<?php echo $data->flighttype; ?>" class="form-control flighttype" required="" id="flighttype" name="flighttype">

                                      <!-- <option></option> -->

                                      <option value="Economy">Economy</option>

                                      <option value="Premium">Premium</option>
                                      
                                      <option value="Business">Business</option>

                                      <!-- <option value="OpenJaw">OpenJaw</option> -->

                                      <!-- <option value="Circle">Circle</option> -->

                                  </select>
                              </div>
                          </div>
                     </div>
                     
                     <div class="form-group col-sm-2">                                    

                          <div class="form-material floating">
                               <div class="flight1">
                                 <label for="flight_price">Price</label>
                                  <input value="<?php echo $data->flight_price; ?>" type="text" class="form-control input" id="flight_price" required="" name="flight_price" placeholder="Price">

                              </div>
                          </div>
                     </div>
                      <!-- <div class="col-2 mt-2">
                          <input type="text" class="js-datepicker form-control rddate js-datepicker-enabled" required="" name="rddate" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy" autocomplete="off" style="display: none;">

                      </div> -->
                  </div>
                </div>

                <!-- Step 4 -->
                <div class="tab-pane" id="wizard-step4" role="tabpanel">
                  <div class="row">

                    <div class="form-group col-sm-4">
                      <div class="form-material floating">
                        <label for="dateInMakkah"> Date In</label>      
                        <input value="<?php echo $data->dateinmakkah; ?>" name="dateinmakkah" type="date" class="js-datepicker mt-4 form-control datein js-datepicker-enabled" id="dateInMakkah" required="" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy" autocomplete="off">
                      </div>
                    </div>                        
                    <div class="form-group col-sm-4">
                      <div class="form-material floating">
                        <label for="dateOutMakkah"> Date Out</label>
                        <input value="<?php echo $data->dateoutmakkah; ?>" name="dateoutmakkah" type="date" class="js-datepicker mt-4 form-control dateout js-datepicker-enabled" id="dateOutMakkah" required="" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy" autocomplete="off">
                      </div>
                    </div>                        
                    <div class="form-group col-sm-4">
                      <div class="form-material floating">
                        <label for="Makkah_hotel_name">Makkah Hotel Name</label>
                        <select class="form-control mt-4" id="Makkah_hotel_name" required="" name="hotel_name_makkah" placeholder="Hotel Name Makkah">
                          <option value="<?php echo $data->hotel_id; ?>" type="text" class="form-control mt-4" id="hotel_name_makkah"><?php echo $data->hotel_name_makkah; ?></option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group col-sm-4">
                      <div class="form-material floating">
                        <label style="padding-bottom: 23px;" for="hotel_rooms_makkah">Hotel Rooms Type</label>
                        <select name="hotel_rooms_makkah" id="hotel_rooms_makkah" class="form-control mt-4 select2" multiple="multiple" data-placeholder="Hotel Rooms">
                          <option value="<?php echo $data->hotel_rooms_makkah;?>"><?php echo $data->hotel_rooms_makkah; ?></option>
                        </select>
                      </div>
                    </div> 
                    <div class="form-group col-sm-4">
                      <div class="form-material floating">
                        <label for="no_of_rooms_makkah"> No Of Rooms Makkah</label>
                        <input value="<?php echo $data->no_of_rooms_makkah; ?>" type="number" class="form-control mt-4" required="" id="no_of_rooms_makkah" name="no_of_rooms_makkah" value="" placeholder="No Of Rooms">
                      </div>
                    </div>
                    <div class="form-group col-sm-4 NONmakkah" >
                      <div class="form-material floating">
                        <label for="no_Of_Nights_Makkah">No of Nights Makkah</label>
                        <input readonly type="text" class="form-control mt-4" required="" id="no_Of_Nights_Makkah" name="no_Of_Nights_Makkah" value="<?php echo $data->no_Of_Nights_Makkah; ?>" placeholder="No Of Nights Makkah">
                      </div>
                    </div>                        
                    <div class="form-group col-sm-4 PPNmakkah" >
                      <div class="form-material floating">
                        <label for="price_per_night_makkah"> Price Per Nights Makkah</label>
                        <input readonly type="text" class="form-control mt-4" required="" id="price_per_night_makkah" name="Price_Per_Nights_Makkah" value="<?php echo $data->Price_Per_Nights_Makkah; ?>" placeholder="Price Per Nights Makkah">
                      </div>
                    </div>
                    <div class="form-group col-sm-4 MakkahTPNights" >
                      <div class="form-material floating">
                        <label for="Makkah_total_price_night_cal">Makkah Total Price Nights</label>
                        <input readonly type="text" class="form-control mt-4" required="" id="Makkah_total_price_night_cal" name="Makkah_total_price_night_cal" value="<?php echo $data->Makkah_total_price_night_cal; ?>" placeholder="Makkah Total Price Nights">
                      </div>
                    </div>
                    <div class="form-group col-sm-4 MakkahTP" >
                      <div class="form-material floating">
                        <label for="Makkah_total_price_cal"> Makkah Total Price</label>
                        <input readonly type="text" class="form-control mt-4" required="" id="Makkah_total_price_cal" name="Makkah_total_price_cal" value="<?php echo $data->Makkah_total_price_cal; ?>" placeholder="Makkah Total Price">
                      </div>
                    </div>
                  </div>
                </div>
                <!-- END Step 4 -->

                <!-- Step 5 -->
                <div class="tab-pane" id="wizard-step5" role="tabpanel">
                  <div class="row" style="padding: 10px;">

                    <div class="form-group col-sm-4">
                        <div class="form-material floating">
                          <label for="dateInMaddinah"> Date In</label>      
                          <input value="<?php echo $data->dateinmadinah; ?>" name="dateinmadinah" type="date" 
                          class="js-datepicker mt-4 form-control datein js-datepicker-enabled" id="dateinmadinah" required="" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy" autocomplete="off">
                        </div>
                    </div>

                    <div class="form-group col-sm-4">
                      <div class="form-material floating">
                        <label for="dateOutMaddinah"> Date Out</label>
                        <input value="<?php echo $data->dateoutmadinah; ?>" type="date" name="dateoutmadinah" id="dateoutmadinah" class="js-datepicker mt-4 form-control dateout js-datepicker-enabled" required="" data-week-start="1" data-autoclose="true" data-today-highlight="true" data-date-format="dd-mm-yyyy" placeholder="dd-mm-yyyy" autocomplete="off">
                      </div>
                    </div> 

                    <div class="form-group col-sm-4">
                      <div class="form-material floating">
                        <label for="Madinah_hotel">Madina Hotel Name</label>
                        <select class="form-control mt-4" id="Madinah_hotel_name" required="" name="hotel_name_madina" placeholder="Hotel Name Madinah">
                          
                        </select>
                      </div>
                    </div> 

                    <div class="form-group col-sm-4">
                      <div class="form-material floating">
                        <label style="padding-bottom: 23px;" for="hotel_rooms_madinah">Hotel Rooms Type</label>
                        <select name="hotel_rooms_madinah" id="hotel_rooms_madinah" class="form-control mt-4 select2" multiple="multiple" data-placeholder="Hotel Rooms Type Madinah">    
                        </select>
                      </div>
                    </div>

                    <div class="form-group col-sm-4">
                      <div class="form-material floating">
                        <label for="no_of_rooms_madina"> No Of Rooms Madina</label>
                        <input value="<?php echo $data->no_of_rooms_madina; ?>" type="number" class="form-control mt-4" required="" id="no_of_rooms_madina" name="no_of_rooms_madina" placeholder="No Of Rooms Madinah">
                      </div>
                    </div>

                    <div class="form-group col-sm-4 NONmadinah">
                      <div class="form-material floating">
                        <label for="no_Of_Nights_Madinah">No of Nights Madinah</label>
                        <input value="<?php echo $data->no_Of_Nights_Madinah; ?>" readonly type="text" class="form-control mt-4" required="" id="no_Of_Nights_Madinah" name="no_Of_Nights_Madinah" placeholder="No Of Nights Madinah">
                      </div>
                    </div>                         
                    <div class="form-group col-sm-4 PPNmadinah">
                      <div class="form-material floating">
                        <label for="price_per_night_madinah"> Price Per Nights Madinah</label>
                        <input value="<?php echo $data->price_per_night_madinah; ?>" readonly type="text" class="form-control mt-4" required="" id="price_per_night_madinah" name="price_per_night_madinah" placeholder="Price Per Madinah Makkah">
                      </div>
                    </div>
                    <div class="form-group col-sm-4 madinahTPNights">
                      <div class="form-material floating">
                        <label for="Madinah_total_price_night_cal">Madinah Total Price Nights</label>
                        <input value="<?php echo $data->Madinah_total_price_night_cal; ?>" readonly type="text" class="form-control mt-4" required="" id="Madinah_total_price_night_cal" name="Madinah_total_price_night_cal" placeholder="Madinah Total Price Nights">                      </div>
                    </div>

                    <div class="form-group col-sm-4 MadinahTP">
                      <div class="form-material floating">
                        <label for="Madinah_total_price_cal"> Madinah Total Price</label>
                        <input value="<?php echo $data->Madinah_total_price_cal; ?>" readonly type="text" class="form-control mt-4" required="" id="Madinah_total_price_cal" name="madinah_total_price_cal" placeholder="Madinah Total Price">                      </div>
                    </div>

                  </div>          
                </div>
                <!-- END Step 5 -->

                <!-- Step 6 -->
                <div class="tab-pane" id="wizard-step6" role="tabpanel">
                       <div class="row" style="padding: 10px;">
                              <div class="form-group col-sm-3">

                                  <div class="form-material floating">
                                          <label for="piclocation"> Transfer From</label>
                                       <input value="<?php echo $data->pickuplocat; ?>" type="text" name="pickuplocat" id="piclocation" required="" class="form-control mt-4 input-group" value="">
                                  </div>

                              </div>
                              
                                 <div class="form-group col-sm-3">

                                  <div class="form-material floating">
                                          <label for="drop_location"> Transfer To</label>
                                       <input value="<?php echo $data->dropoflocat; ?>" type="text" name="dropoflocat" id="drop_location" required="" class="form-control mt-4" >
                                  </div>

                              </div>
                              
                               <div class="form-group col-sm-3">

                                  <div class="form-material floating open">
                                      <label for="ret_type">Return Type</label>
                                      <select  value="<?php echo $data->dest_t; ?>" class="dest_type form-control mt-4" id="ret_type" name="dest_t">

                                          <option value="oneway">Oneway</option>

                                          <option value="return">Return</option>

                                      </select>
                                  </div>

                              </div>
                              <script>
                                      function doWork_transfers() {
                                           var trans_passenger = $('#trans_passenger').val();
                                           var transfers_price = $('#transfers_price').val();
                                          
                                      
                                          transfers_price  = transfers_price * trans_passenger;
                                            $('#transfers_price_total').html("");
                                            
                                          
                                              
                                              $('#transfers_price_total').append('Transfer: '+transfers_price);
                                              $('#transfers_price_total').css('font-size','1.4rem')
                                              $('#transfers_head_total').html(transfers_price);
                                              
                                              
                                              var num1 = $('.divtoappend').html();
                                          var num2 = $('#result_sum').html();
                                          var num0 = $('#result_p_madina_sum').html();
                                          var num_0 = $('#transfers_price_total').html();
                                        
                                          var num3 = parseFloat(num1) + parseFloat(num2) + parseFloat(num0) + parseFloat(num_0);
                                               $('#result_sum_total').html("");
                                          
                                              $('#grand_total_price').val(num3);
                                                   
                                             
                                        }
                                  
                              </script>
                              
                               <div class="form-group col-sm-3">

                                  <div class="form-material floating open">
                                        
                                  <label for="trans_passenger">Passengers</label>

                                  <select value="<?php echo $data->passenger; ?>" class="passengers form-control mt-4" onchange="doWork_transfers()" required="" id="trans_passenger" name="passenger">

                                      <option id="pass1" value="1">1</option>

                                      <option id="pass2" value="2">2</option>

                                      <option id="pass3" value="3">3</option>

                                      <option id="pass4" value="4">4</option>

                                      <option id="pass5" value="5">5</option>

                                      <option id="pass6" value="6">6</option>

                                      <option id="pass7" value="7">7</option>

                                      <option id="pass8" value="8">8</option>

                                      <option id="pass9" value="9">9</option>


                                  </select>
                                  </div>

                              </div>
                              
                              <div class="form-group col-sm-3">

                                  <div class="form-material floating open">
                                        
                                      <label for="trans_vehicle">Vehicle</label>
                                      <select value="<?php echo $data->transfer_vehicle; ?>" class="passengers form-control mt-4" id="trans_vehicle" required="" name="transfer_vehicle">
  
                                              <option value="Bus">Bus</option>
  
                                              <option value="Car">Car</option>
  
                                              <option value="Vain">Vain</option>
                                              <option value="GMC">GMC</option>
  
                                             
  
                                          </select>
                                  </div>

                              </div>
                              
                               <div class="form-group col-sm-3">

                                  <div class="form-material floating">
                                        
                                     <label for="transfers_price">Price</label>
                                      <input value="<?php echo $data->transf_price; ?>" type="text" name="transf_price" required="" id="transfers_price" class="form-control mt-4" value="">
                                  </div>

                              </div>
                              
                               <div class="form-group col-sm-3 mt-3">

                                     <label for="transfers_price">Total</label>
                                     <input value="<?php echo $data->transfers_head_total; ?>" type="text" name="transfers_head_total" required="" id="transfers_head_total" class="form-control mt-2" value="">
                                     <!-- <h6 id="transfers_head_total">0</h6> -->

                              </div>
                          </div>
                </div>
                <!-- END Step 6 -->

                <!--stept 7-->
                <div class="tab-pane" id="wizard-step7" role="tabpanel">
                  <div class="row" style="padding: 10px;">
                    <div class="form-group col-sm-3">
                      <div class="form-material floating visa_fees_adult">
                        <label for="visa_fees_adult"> Adult Visa Price </label>
                        <input value="<?php echo $data->visa_fees_adult; ?>" class="form-control mt-4" type="text" id="visa_fees_adult" name="visa_fees_adult" required="" placeholder="visa price" value="">
                      </div>
                    </div>

                    <div class="form-group col-sm-3">
                      <div class="form-material floating visa_fees_child">
                        <label for="visa_fees_child"> Child Visa Price </label>
                        <input value="<?php echo $data->visa_fees_child; ?>" class="form-control mt-4" type="text" id="visa_fees_child" name="visa_fees_child" required="" placeholder="visa price">
                      </div>
                    </div>
                                  
                    <div class="form-group col-sm-3" id="childs_visa">
                      <div class="form-material floating visa_fees_price">
                        <label for="visa_fees_price"> Visa Fee Total </label>
                        <input value="<?php echo $data->visa_fees_price; ?>" readonly value="" class="form-control mt-4" type="text" id="visa_fees_price" name="visa_fees_price" required="" placeholder="visa price">
                        <input value="<?php echo $data->grand_total_price; ?>" readonly value="" class="form-control" type="text" id="grand_total_price" name="grand_total_price" placeholder="grand_price">
                      </div>
                    </div>
                  </div>
                </div>
                <!--END stept 7-->

                <!-- END Steps Content -->

                <!-- Steps Navigation -->
                <div class="block-content block-content-sm block-content-full bg-body-light">
                    <div class="row" style="padding: 10px;">
                      <div class="col-6">
                        <!-- button id="previous_Nav_Menu" type="button" class="btn btn-alt-secondary back disabled" data-wizard="prev">
                          <i class="fa fa-angle-left mr-5"></i> Previous
                        </button> -->
                      </div>
                      <div class="col-6 text-right">
                        <!-- button id="next_Nav_Menu" type="button" class="btn btn-alt-secondary next" data-wizard="next">Next 
                        <i class="fa fa-angle-right ml-5"></i> -->
                        <!-- </button> -->
                        <button style="float: right;" type="submit" name="submit" class="btn btn-primary" 
                        data-wizard="finish">Submit
                        </button>
                      </div>
                    </div>
                </div>
                <!-- END Steps Navigation -->
              </div>
              <!-- Modal -->
              <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLongTitle">Passengee Details</h5>
                      <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <!-- <form action="{{URL::to('')}}/add_Customers" method="POST" enctype="multipart/form-data"> -->
                      <div class="modal-body">
                        @csrf
                        <!-- SELECT2 EXAMPLE -->
                        <div id="not_Select" style="display: none;" class="block-header bg-primary-dark">
                          <h3 class="block-title">Enter Passenger Names &amp; D.O.B</h3>
                        </div>
                        <div class="block-content row morewrap">
                          
                          <div class="adults">
                            <h3> Add Recoard Against adults</h3>
                            <h3></h3>
                            <label id="adultsID">adults 1 Details
                              <label></label>
                            </label>

                            <div class="col-sm-12 ">
                                @if(!isset($passengerDetailAdults) || $passengerDetailAdults == null || $passengerDetailAdults == "")
                                    <div>EMPTY</div>
                                @else    
                                    <div class="row">
                                        <div class="form-group col-sm-4">
                                          <div class="form-material">
                                            <label for="titles0adults">Title</label>
                                            <select class="form-control" id="titles0adults" name="titlesAdults[]">
                                                @if(isset($passengerDetailAdults->titlesAdults))      
                                                    @foreach ($passengerDetailAdults->titlesAdults as $value)
                                                        <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                                                    @endforeach
                                                @endif
                                            </select>
                                          </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                          <div class="form-material">
                                            <label for="addmore-name0adults">First Name</label>
                                            @if(isset($passengerDetailAdults->first_nameAdults))    
                                                @foreach ($passengerDetailAdults->first_nameAdults as $value)
                                                  <input value="<?php echo $value; ?>" class="form-control" type="text" id="addmore-name0adults" name="first_nameAdults[]">
                                                @endforeach
                                            @endif
                                          </div>
                                        </div>
                                        <div class="form-group col-sm-4">
                                          <div class="form-material">
                                            <label for="addmore-name0adults">Last Name</label>
                                            @if(isset($passengerDetailAdults->last_nameAdults)) 
                                            @foreach ($passengerDetailAdults->last_nameAdults as $value)
                                              <input value="<?php echo $value; ?>" class="form-control" type="text" id="addmore-name0adults" name="last_nameAdults[]">
                                            @endforeach
                                            @endif
                                          </div>
                                        </div>
                                        <div class="form-group col-sm-6">
                                          <div class="form-material">
                                            <label for="addmore-dob0adults">Date Of Birth</label>
                                             @if(isset($passengerDetailAdults->dobAdults)) 
                                            @foreach ($passengerDetailAdults->dobAdults as $value)
                                              <input value="<?php echo $value; ?>" type="date" class="form-control" id="addmore-dob0adults" name="dobAdults[]">
                                            @endforeach
                                            @endif
                                          </div>
                                        </div>
                                        <div class="form-group col-sm-6"><div class="form-material">
                                          <label>Gender</label>
                                          <span class="radio-inline radio-small">
                                            <select class="form-control" name="GenderAdults[]">
                                             @if(isset($passengerDetailAdults->GenderAdults)) 
                                            @foreach ($passengerDetailAdults->GenderAdults as $value)
                                              <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                                            @endforeach
                                            @endif
                                            </select>
                                          </span>
                                        </div>
                                        <div class="form-group col-sm-6">
                                          <div class="form-material">
                                            <label for="addmore-country0adults">Citizenship e.g US</label>
                                             @if(isset($passengerDetailAdults->countryAAdults)) 
                                            @foreach ($passengerDetailAdults->countryAAdults as $value)
                                              <input value="<?php echo $value; ?>" class="form-control" type="text" id="addmore-country0adults" name="countryAAdults[]">
                                            @endforeach
                                            @endif
                                          </div>
                                        </div>
                                        <div class="form-group col-sm-6">
                                          <div class="form-material">
                                            <label for="addmore-passport0adults">Passport number</label>
                                             @if(isset($passengerDetailAdults->customer_passportAdults)) 
                                            @foreach ($passengerDetailAdults->customer_passportAdults as $value)
                                              <input value="<?php echo $value; ?>" class="form-control" type="text" id="addmore-passport0adults" name="customer_passportAdults[]">
                                            @endforeach
                                            @endif
                                          </div>
                                        </div>
                                        <div class="form-group col-sm-6">
                                          <div class="form-material">
                                            <label for="addmore-expiry0adults">Expiry date</label>
                                             @if(isset($passengerDetailAdults->passport_expiryAdults)) 
                                            @foreach ($passengerDetailAdults->passport_expiryAdults as $value)
                                              <input value="<?php echo $value; ?>" class="form-control" type="date" id="addmore-expiry0adults" name="passport_expiryAdults[]">
                                            @endforeach
                                            @endif
                                          </div>
                                        </div>
                                        <div class="form-group col-sm-6">
                                          <div class="form-material">
                                            <label for="addmore-c_country0adults">Country</label>
                                             @if(isset($passengerDetailAdults->c_countryAdults)) 
                                            @foreach ($passengerDetailAdults->c_countryAdults as $value)
                                              <input value="<?php echo $value; ?>" class="form-control" type="text" id="addmore-c_country0adults" name="c_countryAdults[]">
                                            @endforeach
                                            @endif
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                @endif
                          </div>

                          <div class="adults_section"></div>
                          <div class="childs"><h3> Add Recoard Against childs</h3><h3></h3><label id="childsID">childs 1 Details <label></label></label><div class="col-sm-12 "><div class="row"><div class="form-group col-sm-4"><div class="form-material"><select class="form-control" id="titles0childs" name="titlesChilds[]"><option selected="" disabled=""></option><option value="MR"> Mr.</option><option value="MS"> Ms.</option><option value="MRS"> Mrs.</option><option value="MISS"> Miss</option><option value="MASTER"> Master</option></select><label for="titles0childs">Title</label></div></div><div class="form-group col-sm-4"><div class="form-material"><input class="form-control" type="text" id="addmore-name0childs" name="first_nameChilds[]"><label for="addmore-name0childs">First Name</label></div></div><div class="form-group col-sm-4"><div class="form-material"><input class="form-control" type="text" id="addmore-name0childs" name="last_nameChilds[]"><label for="addmore-name0childs">Last Name</label></div></div><div class="form-group col-sm-6"><div class="form-material"><input type="date" class="form-control" id="addmore-dob0childs" name="dobChilds[]"><label for="addmore-dob0childs">Date Of Birth</label></div></div><div class="form-group col-sm-6"><div class="form-material"><label>Gender</label><span class="radio-inline radio-small"><select class="form-control" name="GenderChilds[]"><option value="M">Male</option><option value="F">Female</option></select></div></div><div class="form-group col-sm-6"><div class="form-material"><input class="form-control" type="text" id="addmore-country0childs" name="countryAChilds[]"><label for="addmore-country0childs">Citizenship e.g US</label></div></div><div class="form-group col-sm-6"><div class="form-material"><input class="form-control" type="text" id="addmore-passport0childs" name="customer_passportChilds[]"><label for="addmore-passport0childs">Passport number</label></div></div><div class="form-group col-sm-6"><div class="form-material"><input class="form-control" type="date" id="addmore-expiry0childs" name="passport_expiryChilds[]"><label for="addmore-expiry0childs">Expiry date</label></div></div><div class="form-group col-sm-6"><div class="form-material"><input class="form-control" type="text" id="addmore-c_country0childs" name="c_countryChilds[]"><label for="addmore-c_country0childs">Country</label></div></div></div></div></div>
                          <div class="childs_section"></div>
                          <div class="infant"><h3> Add Recoard Against infant</h3><h3></h3><label id="infantID">infant 1 Details <label></label></label><div class="col-sm-12 "><div class="row"><div class="form-group col-sm-4"><div class="form-material"><select class="form-control" id="titles0infant" name="titlesInfant[]"><option selected="" disabled=""></option><option value="MR"> Mr.</option><option value="MS"> Ms.</option><option value="MRS"> Mrs.</option><option value="MISS"> Miss</option><option value="MASTER"> Master</option></select><label for="titles0infant">Title</label></div></div><div class="form-group col-sm-4"><div class="form-material"><input class="form-control" type="text" id="addmore-name0infant" name="first_nameInfant[]"><label for="addmore-name0infant">First Name</label></div></div><div class="form-group col-sm-4"><div class="form-material"><input class="form-control" type="text" id="addmore-name0infant" name="last_nameInfant[]"><label for="addmore-name0infant">Last Name</label></div></div><div class="form-group col-sm-6"><div class="form-material"><input type="date" class="form-control" id="addmore-dob0infant" name="dobInfant[]"><label for="addmore-dob0infant">Date Of Birth</label></div></div><div class="form-group col-sm-6"><div class="form-material"><label>Gender</label><span class="radio-inline radio-small"><select class="form-control" name="GenderInfant[]"><option value="M">Male</option><option value="F">Female</option></select></div></div><div class="form-group col-sm-6"><div class="form-material"><input class="form-control" type="text" id="addmore-country0infant" name="countryAInfant[]"><label for="addmore-country0infant">Citizenship e.g US</label></div></div><div class="form-group col-sm-6"><div class="form-material"><input class="form-control" type="text" id="addmore-passport0infant" name="customer_passportInfant[]"><label for="addmore-passport0infant">Passport number</label></div></div><div class="form-group col-sm-6"><div class="form-material"><input class="form-control" type="date" id="addmore-expiry0infant" name="passport_expiryInfant[]"><label for="addmore-expiry0infant">Expiry date</label></div></div><div class="form-group col-sm-6"><div class="form-material"><input class="form-control" type="text" id="addmore-c_country0infant" name="c_countryInfant[]"><label for="addmore-c_country0infant">Country</label></div></div></div></div></div>
                          <div class="infant_section"></div>
                          
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-alt-secondary" data-bs-dismiss="modal">Close</button>
                        <button id="save_Changes" type="button" class="btn btn-alt-success saveclintdetais">
                          <i class="fa fa-check"></i> Save Changes
                        </button>
                        <!-- <button style="background-color: #1a9ed4; border: white;" type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button style="background-color: #1a9ed4; border: white" type="submit" class="btn btn-primary">Add new</button> -->
                      </div>
                    <!-- </form> -->
                  </div>
                </div>
              </div>
              <!-- /.Modal -->
            </form>
            <!-- END Form -->
          </div>
        </div>
      </div>
    </section>
    <!-- /.Dashboard -->
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  <script>
    $(function () {
      //Initialize Select2 Elements
      $('.select2').select2()

      //Initialize Select2 Elements
      $('.select2bs4').select2({
        heme: 'bootstrap4'
      })
    })
  </script> 

  <script type="text/javascript">
    $(document).ready(function(){

      //Show more tags of form
      $("#formButton").click(function(){
        $("#form1").toggle();
      });

      // On UL
        //ON 1st
        $('#LPD_1').click(function(){
          var width = 220 ;
          $('.PB').width(width);
        });
        //ON 2nd
        $('#LPD_2').click(function(){
          var width = 220 + 170 ;
          $('.PB').width(width);
        });
        //ON 3rd
        $('#LPD_3').click(function(){
          var width = 220 + 170 + 170 ;
          $('.PB').width(width);
        });
        //ON 4th
        $('#LPD_4').click(function(){
          var width = 220 + 170 + 170 + 170;
          $('.PB').width(width);
        });
        //ON 5th
        $('#LPD_5').click(function(){
          var width = 220 + 170 + 170 + 170 + 170;
          $('.PB').width(width);
        });
        //ON 6th
        $('#LPD_6').click(function(){
          var width = 220 + 170 + 170 + 170 + 170 + 170;
          $('.PB').width(width);
        });
        //ON 7th
        $('#LPD_7').click(function(){
          var width = 220 + 170 + 170 + 170 + 170 + 170 + 170;
          $('.PB').width(width);
        });
      // End UL

      // ON 4th
        var today = new Date().toISOString().split('T')[0];
        document.getElementsByName("dateinmakkah")[0].setAttribute('min', today);
        document.getElementsByName("dateoutmakkah")[0].setAttribute('min', today);

        $('#dateOutMakkah').on('change',function(){
          $('.NONmakkah').css('display','');
          var availible_from = $('#dateInMakkah').val();
          var availible_to = $(this).val();
          var start  = Date.parse(availible_from) / 86400000;
          var end = Date.parse(availible_to) / 86400000;
          var no_Of_Nights_Makkah = parseFloat(end) - parseFloat(start);
          $('#no_Of_Nights_Makkah').val(no_Of_Nights_Makkah);

          var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
          $.ajax({
            url: '{{URL::to('hotel_Makkah_Room')}}',
            type: 'POST',
            data: {
              _token: CSRF_TOKEN,
              "availible_from": availible_from,
              "availible_to": availible_to
            },
            success:function(data) {
              var a = JSON.parse(data);
              // console.log(a);
              $("#Makkah_hotel_name").empty();
              var result = [];
              $.each(a, function (i, e) {
                  var matchingItems = $.grep(result, function (item) {
                     return item.property_name === e.property_name && item.id === e.id;
                  });
                  if (matchingItems.length === 0){
                    result.push(e);
                    $("#Makkah_hotel_name").append('<option value=' +e.hotel_id+ '>' +e.property_name+'</option>');
                  }
              });
              var ass = JSON.stringify(result)            
            },
          });
        });

        $('#Makkah_hotel_name').on('change',function(){
          var id = $(this).val();
          $.ajax({
            url : "{{URL::to('makkah_Room')}}" + '/' + id,
            type: 'GET',
            data: {
              "id": id
            },
            success:function(data) {
              var a = JSON.parse(data);

              $('#price_per_night_makkah').empty();
              $('#Makkah_total_price_night_cal').empty();
              $('#Makkah_total_price_cal').empty();
              $("#hotel_rooms_makkah").empty();

              jQuery.each(a, function(index, value){
                var quantity = value.quantity;
                var weekdays_price = value.weekdays_price;
                var weekends_price = value.weekends_price;
                var sum = parseFloat(weekdays_price) + parseFloat(weekends_price);
                // console.log(sum);
                $("#hotel_rooms_makkah").append('<option value=' + sum+ '>' + value.room_type+ "(" +quantity+ ")" + '</option>');
              });
            },
          });
        });

        $('#hotel_rooms_makkah').on('change',function(){

          $('#price_per_night_makkah').empty();
          $('#Makkah_total_price_night_cal').empty();
          $('#Makkah_total_price_cal').empty();

          $('.PPNmakkah').css('display','');
          $(".MakkahTPNights").css('display','');

          var no_Of_Nights_Makkah = $('#no_Of_Nights_Makkah').val();
          var hotel_rooms_makkah = $(this).val();

          price_per_night_makkah = 0;

          jQuery.each(hotel_rooms_makkah,function(index,value){
            price_per_night_makkah = parseFloat(price_per_night_makkah) + parseFloat(value);
          });
          $("#price_per_night_makkah").val(price_per_night_makkah);
          var Makkah_total_price_night_cal = parseFloat(no_Of_Nights_Makkah) * parseFloat(price_per_night_makkah);
          $("#Makkah_total_price_night_cal").val(Makkah_total_price_night_cal);

        });

        $('#no_of_rooms_makkah').on('change',function(){
          $('#Makkah_total_price_cal').empty();
          $(".MakkahTP").css('display','');
          var no_of_rooms_makkah = $(this).val();
          var Makkah_total_price_night_cal = $("#Makkah_total_price_night_cal").val();
          var TotalP = parseFloat(no_of_rooms_makkah) * parseFloat(Makkah_total_price_night_cal);
          $("#Makkah_total_price_cal").val(TotalP);
        });
      // End 4th

      // ON 5th
        var today = new Date().toISOString().split('T')[0];
        document.getElementsByName("dateinmadinah")[0].setAttribute('min', today);
        document.getElementsByName("dateoutmadinah")[0].setAttribute('min', today);

        $('#dateoutmadinah').on('change',function(){
          $('.NONmadinah').css('display','');
          var availible_from = $('#dateinmadinah').val();
          var availible_to = $(this).val();
          var start  = Date.parse(availible_from) / 86400000;
          var end = Date.parse(availible_to) / 86400000;
          var no_Of_Nights_Madinah = parseFloat(end) - parseFloat(start);
          $('#no_Of_Nights_Madinah').val(no_Of_Nights_Madinah);

          var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
          $.ajax({
            url: '{{URL::to('hotel_Madinah_Room')}}',
            type: 'POST',
            data: {
              _token: CSRF_TOKEN,
              "availible_from": availible_from,
              "availible_to": availible_to
            },
            success:function(data) {
              var a = JSON.parse(data);
              // console.log(a);
              $("#Madinah_hotel_name").empty();
              var result = [];
              $.each(a, function (i, e) {
                  var matchingItems = $.grep(result, function (item) {
                     return item.property_name === e.property_name && item.id === e.id;
                  });
                  if (matchingItems.length === 0){
                    result.push(e);
                    $("#Madinah_hotel_name").append('<option value=' +e.hotel_id+ '>' +e.property_name+ '</option>');
                  }
              });
              var ass = JSON.stringify(result)          
            },
          });
        });

        $('#Madinah_hotel_name').on('change',function(){
          var id = $(this).val();
          $.ajax({
            // url: 'madinah_Room/'+id,
            url: "{{URL::to('madinah_Room')}}" + '/' + id,
            type: 'GET',
            data: {
              "id": id
            },
            success:function(data) {
              var a = JSON.parse(data);

              // console.log(a);
              $('#price_per_night_madinah').empty();
              $('#Madinah_total_price_night_cal').empty();
              $('#Madinah_total_price_cal').empty();
              $("#hotel_rooms_madinah").empty();
              jQuery.each(a, function(index, value){
                var quantity = value.quantity;
                var weekdays_price = value.weekdays_price;
                var weekends_price = value.weekends_price;
                var sum = parseFloat(weekdays_price) + parseFloat(weekends_price);
                // console.log(sum);
                $("#hotel_rooms_madinah").append('<option value=' + sum+ '>' + value.room_type+ "(" +quantity+ ")" + '</option>');
              });
            },
          });
        });

        $('#hotel_rooms_madinah').on('change',function(){

          $('#price_per_night_madina').empty();
          $('#Madinah_total_price_night_cal').empty();
          $('.PPNmadinah').css('display','');
          $(".madinahTPNights").css('display','');

          var no_Of_Nights_Madinah = $('#no_Of_Nights_Madinah').val();
          var hotel_rooms_madinah = $(this).val();

          var price_per_night_madinah = 0;
          jQuery.each(hotel_rooms_madinah,function(index,value){
            price_per_night_madinah = parseFloat(price_per_night_madinah) + parseFloat(value);
          });
          $("#price_per_night_madinah").val(price_per_night_madinah);
          var Madinah_total_price_night_cal = parseFloat(no_Of_Nights_Madinah) * parseFloat(price_per_night_madinah);
          $("#Madinah_total_price_night_cal").val(Madinah_total_price_night_cal);
        });

        $('#no_of_rooms_madina').on('change',function(){
          $('#Madinah_total_price_cal').empty();
          $(".madinahTPNights").css('display','');
          $(".MadinahTP").css('display','');
          var no_of_rooms_madinah = $(this).val();
          var Madinah_total_price_night_cal = $("#Madinah_total_price_night_cal").val();
          var TotalP = parseFloat(no_of_rooms_madinah) * parseFloat(Madinah_total_price_night_cal);
          $("#Madinah_total_price_cal").val(TotalP);
        });
      // End 5th

      // ON 7th
        $('#visa_fees_child').on('change',function(){
          $('.visa_fees_price').css('display','');

          var visa_fees_adult = $('#visa_fees_adult').val();
          var visa_fees_child = $('#visa_fees_child').val();
          var visa_fees_price = parseFloat(visa_fees_adult) + parseFloat(visa_fees_child);
          $('#visa_fees_price').val(visa_fees_price);

          var Makkah_total_price_cal = $('#Makkah_total_price_cal').val();
          var Madinah_total_price_cal = $('#Madinah_total_price_cal').val();
          var makkah_mdinah_total = parseFloat(Makkah_total_price_cal) + parseFloat(Madinah_total_price_cal);
          var grand_total_price = parseFloat(makkah_mdinah_total) + parseFloat(visa_fees_price);
          console.log(grand_total_price);
          $('#grand_total_price').val(grand_total_price);
        });
      // End 7th

      // On NxtPreBtn
        var width = 220 ;
        //Next Nav Menu
        $('#next_Nav_Menu').click(function(){
          if(width<1240){
            width = width + 170 ;
            $('.PB').width(width);
          }else{
            alert('Now Sumbit Form');
            width = 0;
          }

          if(width>220){
            $('#previous_Nav_Menu').removeClass('disabled');
             // alert(width);
          }
        });

        //Previous Nav Menu
        $('#previous_Nav_Menu').click(function(){
          width = width - 170 ;
          $('.PB').width(width);
          if(width<=220){
            $('#previous_Nav_Menu').addClass('disabled');
           // alert(width);
          }
        });
      // End NxtPreBtn

    });
  </script>
 <!--  <script type="text/javascript">
    $(document).ready(function(){

      //ON 1st
      $('#LPD_1').click(function(){
        var width = 220 ;
        $('.PB').width(width);
      });
      //ON 2nd
      $('#LPD_2').click(function(){
        var width = 220 + 170 ;
        $('.PB').width(width);
      });
      //ON 3rd
      $('#LPD_3').click(function(){
        var width = 220 + 170 + 170 ;
        $('.PB').width(width);
      });
      //ON 4th
      $('#LPD_4').click(function(){
        var width = 220 + 170 + 170 + 170;
        $('.PB').width(width);
      });
      //ON 5th
      $('#LPD_5').click(function(){
        var width = 220 + 170 + 170 + 170 + 170;
        $('.PB').width(width);
      });
      //ON 6th
      $('#LPD_6').click(function(){
        var width = 220 + 170 + 170 + 170 + 170 + 170;
        $('.PB').width(width);
      });
      //ON 7th
      $('#LPD_7').click(function(){
        var width = 220 + 170 + 170 + 170 + 170 + 170 + 170;
        $('.PB').width(width);
      });

      var width = 220 ;
      //Next Nav Menu
      $('#next_Nav_Menu').click(function(){
        if(width<1240){
          width = width + 170 ;
          $('.PB').width(width);
        }else{
          alert('Now Sumbit Form');
          width = 0;
        }

        if(width>220){
          $('#previous_Nav_Menu').removeClass('disabled');
           // alert(width);
        }
      });

      //Previous Nav Menu
      $('#previous_Nav_Menu').click(function(){
        width = width - 170 ;
        $('.PB').width(width);
        if(width<=220){
          $('#previous_Nav_Menu').addClass('disabled');
         // alert(width);
        }
      });

    });
  </script> -->

@endsection
