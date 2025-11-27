@extends('template/frontend/userdashboard/layout/default')
@section('content')

    <?php $currency=Session::get('currency_symbol'); // dd($all_Users); ?>
    
    <style>
        .nav-link{
          color: #575757;
          font-size: 18px;
        }
    </style>

    <div class="content-wrapper">
        
        <section class="content" >
          <div class="container-fluid">
               <nav class="breadcrumb push">
                    <a class="breadcrumb-item" href="#">Dashboard</a> 
                    <span class="breadcrumb-item active">View Agent Invoices</span>
                </nav>
            <div class="row">
              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-primary" style="height: 184px;text-align: center;padding: 15px 0px 0px 0px;background-color: #313a46 !important;color: white;">
                  <div class="icon mb-2">
                    <!-- <i class="mdi mdi-eye me-1"></i> -->
                    <span class="iconify" data-icon="uil:plane-departure" data-width="70"></span>
                  </div>
                  <div class="inner">
                    <h3 id="total_flights"></h3>
                    <p>Total Flights</p>
    
                    
    
                  </div>
                  
                </div>
              </div>
              <!-- ./col -->
              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-success" style="height: 184px;text-align: center;padding: 15px 0px 0px 0px;background-color: #313a46 !important;color: white;">
                  <div class="icon mb-2">
                    <span class="iconify" data-icon="uil-home-alt" data-width="70"></span>
                  </div>
                  <div class="inner">
                    <h3 id="total_hotels"></h3>
    
                    <p>Total Hotels</p>
                  </div>
                  
                </div>
              </div>
              <!-- ./col -->
              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box" style="height: 184px;text-align: center;padding: 15px 0px 0px 0px;background-color: #313a46 !important;color: white;">
                  <div class="icon mb-2">
                    <span class="iconify" data-icon="uil-car-sideview" data-width="70"></span>
                  </div>
                  
                  <div class="inner">
                    <h3 id="total_transfer"></h3>
    
                    <p >Total Transfer</p>
                  </div>
                  
                </div>
              </div>
              <!-- ./col -->
              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info" style="height: 184px;text-align: center;padding: 15px 0px 0px 0px;background-color: #313a46 !important;color: white;">
                  <div class="icon mb-2">
                    <!--<span class="iconify" data-icon="uil-dollar-sign-alt" data-width="70"></span>-->
                    <i class="fab fa-cc-visa" style="font-size:60px"></i>
                  </div>
                  <div class="inner">
                    <h3 id="total_visa"></h3>
    
                    <p>Total Visa</p>
                  </div>
                  
                </div>
              </div>
              <!-- ./col -->
            </div>
          </div>
        </section>
        
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-12">
                        <h4>Agents Stats Details</h4>
                        <div class="panel-body padding-bottom-none">
                            <div class="block-content block-content-full">
                                <div class="table-responsive">
                                    <div id="example_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                                        <table  class="table nowrap example1 dataTable no-footer" id="myTable" role="grid" aria-describedby="example_info">
                                            <thead class="theme-bg-clr">
                                                <tr role="row">
                                                    <th>Sr</th>
                                                    <th>Customer Name</th>
                                                    <th>Invoice Id</th>
                                                    <th>Invoice Type</th>
                                                    <th>Invoice Agent</th>
                                                    <th>Lead Name</th>
                                                    <th>Prepared By</th>
                                                    <th>Total Payable</th>
                                                    <th>Date</th>
                                                    <th>Outstandings</th>
                                                    <th style="display:none">Options</th>
                                                  
                                                </tr>
                                            </thead>
                                            
                                            <tbody style="text-align: center;">
                                                <?php 
                                                    $i                  = 1;
                                                    $flights_count      = 0;
                                                    $hotel_count        = 0;
                                                    $transfer_count     = 0;
                                                    $visa_count         = 0;
                                                    $all_services_count = 0;
                                                ?>
                                                @foreach ($data as $value)
                                                    @if($value->quotation_Invoice != '1')
                                                        <?php
                                                            $service_Type   = '';
                                                            $date4days      = date('Y-m-d',strtotime($value->created_at. ' + 5 days'));
                                                            $currdate       = date('Y-m-d');
                                                            $services       = json_decode($value->services);
                                                            if(isset($services)){
                                                                if(is_array($services)){
                                                                    $services_count = count($services);
                                                                    if($services_count > 1){
                                                                        foreach($services as $services_res){
                                                                            if($services_res == '1'){
                                                                                $flights_count++;
                                                                                $hotel_count++;
                                                                                $transfer_count++;
                                                                                $visa_count++;
                                                                                $all_services_count++;
                                                                                $service_Type .= 'All Services,';
                                                                            }
                                                                            
                                                                            if($services_res == 'accomodation_tab'){
                                                                                $hotel_count++;
                                                                                $service_Type .= 'Hotel,';
                                                                            }
                                                                            if($services_res == 'transportation_tab'){
                                                                                $transfer_count++;
                                                                                $service_Type .= 'Transfer,';
                                                                            }
                                                                            if($services_res == 'flights_tab'){
                                                                                $flights_count++;
                                                                                $service_Type .= 'Flight,';
                                                                            }
                                                                            if($services_res == 'visa_tab'){
                                                                                $visa_count++;
                                                                                $service_Type .= 'Visa,';
                                                                            }
                                                                        }
                                                                    }else{
                                                                        foreach($services as $services_res){
                                                                            if($services_res == '1'){
                                                                                $flights_count++;
                                                                                $hotel_count++;
                                                                                $transfer_count++;
                                                                                $visa_count++;
                                                                                $all_services_count++;
                                                                                $service_Type = 'All Services';
                                                                            }
                                                                            
                                                                            if($services_res == 'accomodation_tab'){
                                                                                $hotel_count++;
                                                                                $service_Type = 'Hotel';
                                                                            }
                                                                            if($services_res == 'transportation_tab'){
                                                                                $transfer_count++;
                                                                                $service_Type = 'Transfer';
                                                                            }
                                                                            if($services_res == 'flights_tab'){
                                                                                $flights_count++;
                                                                                $service_Type .= 'Flight';
                                                                            }
                                                                            if($services_res == 'visa_tab'){
                                                                                $visa_count++;
                                                                                $service_Type = 'Visa';
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        ?>
                                                        <tr role="row" class="odd">
                                                            <td class="sorting_1">{{ $i }} </td>
                                                            <td class="sorting_1">
                                                                @if(isset($all_Users) && $all_Users != null && $all_Users != '')
                                                                    @foreach($all_Users as $all_Users_value)
                                                                        @if($value->customer_id == $all_Users_value->id)
                                                                            <b>{{ $all_Users_value->name }}</b>
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </td>
                                                            <td class="sorting_1">{{ $value->generate_id }} </td>
                                                            <td class="sorting_1">{{ $service_Type }} </td>
                                                            <td class="sorting_1">
                                                                @if(isset($value->agent_Id) && $value->agent_Id != null && $value->agent_Id != '')
                                                                    @if(isset($Agents_detail) && $Agents_detail != null && $Agents_detail != '')
                                                                        @foreach($Agents_detail as $Agents_detailS)
                                                                            @if($value->agent_Id == $Agents_detailS->id)
                                                                                {{ $Agents_detailS->company_name }}
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                @else
                                                                    @if(isset($customers_data) && $customers_data != null && $customers_data != '')
                                                                        @foreach($customers_data as $customers_dataS)
                                                                            @if($value->booking_customer_id == $customers_dataS->id)
                                                                                {{ $customers_dataS->name }}
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                @endif
                                                            </td>
                                                            <td class="sorting_1">{{ $value->f_name }} {{ $value->middle_name }}</td>
                                                            <td>{{ $value->tour_author }}</td>
                                                        
                                                            <?php
                                                                $accomodation_detailsE          = $value->accomodation_details;
                                                                $accomodation_details_moreE     = $value->accomodation_details_more;
                                                                $no_of_pax_days                 = $value->no_of_pax_days;
                                                                $visa_Pax                       = $value->visa_Pax;
                                                                $acc_total_amount               = 0;
                                                                $markup_detailsE                = $value->markup_details;
                                                                $more_visa_detailsE             = $value->more_visa_details;
                                                             
                                                                if(isset($accomodation_detailsE) && $accomodation_detailsE != null && $accomodation_detailsE != ''){
                                                                    $accomodation_details = json_decode($accomodation_detailsE);
                                                                    if(isset($accomodation_details)){
                                                                        foreach($accomodation_details as $accomodation_details1){
                                                                        if(isset($accomodation_details1->hotel_invoice_markup) && $accomodation_details1->hotel_invoice_markup != null && $accomodation_details1->hotel_invoice_markup != ''){
                                                                            $hotel_invoice_markup = $accomodation_details1->hotel_invoice_markup;
                                                                        }else{
                                                                            $hotel_invoice_markup = 0;
                                                                        }
                                                                        if(isset($accomodation_details1->acc_qty) && $accomodation_details1->acc_qty != null && $accomodation_details1->acc_qty != ''){
                                                                            $acc_qty = $accomodation_details1->acc_qty;
                                                                        }else{
                                                                            $acc_qty = 0;
                                                                        }
                                                                        $acc_total_amount = $acc_total_amount + ($hotel_invoice_markup * $acc_qty);   
                                                                    }
                                                                    }
                                                                    // print_r($accomodation_details_moreE);
                                                                    // die;
                                                                    if(isset($accomodation_details_moreE) && $accomodation_details_moreE != null && !empty($accomodation_details_moreE)){
                                                                        $accomodation_details_more  = json_decode($accomodation_details_moreE);
                                                                        if(isset($accomodation_details_more)){
                                                                            foreach($accomodation_details_more as $accomodation_details_more1){
                                                                            if(isset($accomodation_details_more1->more_hotel_invoice_markup) && $accomodation_details_more1->more_hotel_invoice_markup != null && $accomodation_details_more1->more_hotel_invoice_markup != ''){
                                                                                $more_hotel_invoice_markup = $accomodation_details_more1->more_hotel_invoice_markup;
                                                                            }else{
                                                                                $more_hotel_invoice_markup = 0;
                                                                            }
                                                                            if(isset($accomodation_details_more1->more_acc_qty) && $accomodation_details_more1->more_acc_qty != null && $accomodation_details_more1->more_acc_qty != ''){
                                                                                $more_acc_qty = $accomodation_details_more1->more_acc_qty;
                                                                            }else{
                                                                                $more_acc_qty = 0;
                                                                            }
                                                                            $acc_total_amount = $acc_total_amount + ($more_hotel_invoice_markup * $more_acc_qty);
                                                                        }
                                                                        }
                                                                    }
                                                                }
                                                                
                                                                if(isset($markup_detailsE) && $markup_detailsE != null & $markup_detailsE != ''){
                                                                    $markup_details1    = json_decode($value->markup_details);
                                                                    foreach($markup_details1 as $markup_details)
                                                                    {
                                                                        if($markup_details->markup_Type_Costing == 'flight_Type_Costing'){
                                                                            if($markup_details->markup_price != null && $markup_details->markup_price != ''){
                                                                                $flight_Type_Costing = $markup_details->markup_price * $no_of_pax_days;
                                                                            }else{
                                                                                $flight_Type_Costing = 0;
                                                                            }
                                                                        }
                                                                        if($markup_details->markup_Type_Costing == 'transportation_Type_Costing'){
                                                                            if($markup_details->markup_price != null && $markup_details->markup_price != ''){
                                                                                $transportation_Type_Costing = $markup_details->markup_price * $no_of_pax_days; 
                                                                            }else{
                                                                                $transportation_Type_Costing = 0;
                                                                            }
                                                                        }
                                                                    }
                                                                }else{
                                                                    $flight_Type_Costing            = 0;
                                                                    $transportation_Type_Costing    = 0;
                                                                }
                                                                
                                                                $visa_Type_Costing = 0;
                                                                if(isset($value->total_visa_markup_value) && $value->total_visa_markup_value != null && $value->total_visa_markup_value != ''){
                                                                    $visa_Type_Costing = $value->total_visa_markup_value * $visa_Pax;
                                                                }else{
                                                                    $visa_Type_Costing = $value->exchange_rate_visa_fee * $visa_Pax;
                                                                }
                                                                
                                                                if(isset($more_visa_detailsE) && $more_visa_detailsE != null & $more_visa_detailsE != ''){
                                                                    $more_visa_Type_Costing = 0;
                                                                    $more_visa_details      = json_decode($more_visa_detailsE);
                                                                    if(isset($more_visa_details) && $more_visa_details != null & $more_visa_details != ''){
                                                                        foreach($more_visa_details as $more_visa_details1){
                                                                            $more_visa_Pax                  = $more_visa_details1->more_visa_Pax;
                                                                            $more_total_visa_markup_value   = $more_visa_details1->more_total_visa_markup_value;
                                                                            
                                                                            if(isset($more_visa_details1->more_exchange_rate_visa_fee) && $more_visa_details1->more_exchange_rate_visa_fee != null && $more_visa_details1->more_exchange_rate_visa_fee != ''){
                                                                                $more_visa_fee = $more_visa_details1->more_exchange_rate_visa_fee;   
                                                                            }else{
                                                                                $more_visa_fee = 0;
                                                                            }
                                                                            
                                                                            if($more_total_visa_markup_value != null && $more_total_visa_markup_value != ''){
                                                                                $more_visa_Type_Costing = $more_visa_Type_Costing + ($more_visa_Pax * $more_total_visa_markup_value);   
                                                                            }else{
                                                                                if($more_visa_Pax > 0 && $more_visa_fee > 0){
                                                                                    $more_visa_Type_Costing = $more_visa_Type_Costing + ($more_visa_Pax * $more_visa_fee);
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }else{
                                                                    $more_visa_Type_Costing = 0;
                                                                }
                                                                
                                                                if(isset($value->total_sale_price_all_Services) && $value->total_sale_price_all_Services != null && $value->total_sale_price_all_Services != ''){
                                                                    $total_Payable = $value->total_sale_price_all_Services;
                                                                }else{
                                                                    $total_Payable = $acc_total_amount + $transportation_Type_Costing + $flight_Type_Costing + $visa_Type_Costing + $more_visa_Type_Costing;
                                                                }
                                                            ?>
                                                            
                                                            <td>
                                                                <?php echo $currency ?>{{ $total_Payable }} <br>
                                                                <span class="badge bg-success d-none" style="font-size: 15px" >Add Special Discount</span>
                                                            </td>
                                                            <td>{{ Date('Y-m-d',strtotime($value->created_at)) }}</td>
                                                            <td>
                                                                <span style="font-size: 15px;" class="badge bg-info" id="view_outSid_{{ $value->id }}" data-id="{{ $value->id }}" onclick="view_outS({{ $value->id }})">
                                                                    View Outstanding
                                                                </span>
                                                            </td>
                                                            <td style="display:none">
                                                                <div class="dropdown card-widgets">
                                                                    <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                                        <i class="dripicons-dots-3"></i>
                                                                    </a>
                                                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                                                        <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#alert-modal" onclick="edit_function({{ $value->id }})">Edit Invoice</a>
                                                                        <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#alert-modal" onclick="delete_function({{ $value->id }})">Delete</a>
                                                                        <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#alert-modal" onclick="pay_Amount_function({{ $value->id }})">Pay Amount</a>
                                                                        <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#alert-modal" onclick="view_Invoice_function({{ $value->id }})">View Invoice</a>
                                                                        
                                                                        <input type="hidden" value='{{ json_encode($value) }}' id="invoice_data_{{ $value->id }}" attr-type="{{ $service_Type }}">
                                                                        <a class="dropdown-item" onclick="add_single_discount('{{ $value->id }}')">Add Special Discount</a>
                                                                    
                                                                        @if($service_Type == 'Flight')
                                                                            <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#alert-modal" onclick="view_Atol_Certificate_function({{ $value->id }})">View Atol Certificate</a>
                                                                            <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#alert-modal" onclick="view_Idemnity_Form_function({{ $value->id }})">View Idemnity Form</a>
                                                                        @endif
                                                                        @if($value->no_of_pax_days == 1 || $value->no_of_pax_days == $value->count_P_Input || $value->count_P_Input > $value->no_of_pax_days)
                                                                            <a class="dropdown-item" onclick="add_more_passenger({{$value->id}})" data-bs-toggle="modal" data-bs-target="#add-more-passenger-modal">View More Passengers details</a>
                                                                        @else
                                                                            <a class="dropdown-item" onclick="add_more_passenger({{$value->id}})" data-bs-toggle="modal" data-bs-target="#add-more-passenger-modal">Add More Passengers</a>
                                                                        @endif
                                                                        @if($value->confirm != 1)
                                                                            <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#alert-modal" onclick="confirm_Booking_function({{ $value->id }})">Confirm Booking</a>
                                                                        @else
                                                                            <a class="dropdown-item btn btn-primary">CONFIRMED</a>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        
                                                        <?php $i++ ?>
                                                    @endif
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
        </section>
        
        <div id="add-more-passenger-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" style="margin-right: 700px;">
                <div class="modal-content" style="width: 1100px;">
                    <div class="modal-header">
                        <h4 class="modal-title" id="standard-modalLabel">Add more Passenger</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                            @csrf
                            <div class="row">
                                <h3>Lead Passenger Details</h3>
                                <div class="col-xl-12">
                                    <div class="mb-3">
                                         <div class="row" @if(session('passengerDetail')) style="display:none;" @endif>
                                            <div class="col">
                                                
                                                <form method="post" id="form1" action="https://alhijaztours.net/Uploadpassport" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="form">
                                                      <input type="file" id="s_s"  onchange="loadFileactivity(event)" name="file" class="hidden" style="display: none;" /> 
                                                      <button type="button" class="btn btn-primary"> <label for="s_s">Upload ScreenShot Of Passport. </label>
                                                      </button>
                                                      <button type="submit" id="submit_button" class="btn btn-primary "> Submit
                                                      </button>
                                                    </div>
                                                </form>

                                                <span class="setcategory2 mt-5">
                                                    <img id="imgoutput" width="190" />
                                                </span>
                                                
                                            
                                            <div class="col passport_form">
                                               
                                               
                                               
                                            </div>
                                              
                                               
                                                                                      
                                          </div>
                                             
                                           <!-- <button class="color2-bg no-shdow-btn btn btn-success">Submit</button> -->
                                    </div>
                                    
                                        <div class="row">
                                             <div class="col-md-12" id="leadpassenger_loader" style="display:none;">
                                                  <iframe src="https://embed.lottiefiles.com/animation/98195"></iframe>
                                              </div>
                                        </div>
                                        <div class="row" id="leadpassenger_form" style="border: 1px solid #ebebeb;padding: 1rem;border-radius: 6px;">
                                            <form method="post" class="row g-3" action="{{ url('add_more_passenger_Invoice') }}">
                                                 @csrf
                                            <div class="col-md-4">
                                                    <label for="inputEmail4" class="form-label">Title</label>
                                                    <select class="form-control" name="lead_title" style="border-radius: unset !important;">
                                                        <option value="">Select Title</option>
                                                        <option value="Mr." id="mr">Mr.</option>
                                                        <option value="Mrs." id="mrs">Mrs.</option>
                                                    </select>
                                                  </div>
                                              <div class="col-md-4">
                                                <label for="inputEmail4" class="form-label">First name</label>
                                                <input type="text" class="form-control" id="f_name_lead" required value="@if(session('passengerDetail')) {{ session('passengerDetail')[0]['name'] }} @endif" name="name">
                                                <input type="text" name="invoice_id" hidden class="inovice_id">
                                                <input type="text" name="invoice_req_from" hidden id="" value="leadPassenger">
                                              </div>
                                              <div class="col-md-4">
                                                <label for="inputPassword4" class="form-label">Last name</label>
                                                <input type="text" class="form-control" id="l_name_lead" required value="@if(session('passengerDetail')) {{ session('passengerDetail')[0]['lname'] }} @endif" name="lname">
                                              </div>
                                              <div class="col-4">
                                                <label for="inputAddress" class="form-label">Email</label>
                                                <input type="text" class="form-control" id="lead_email" required value="@if(session('passengerDetail')) {{ session('passengerDetail')[0]['email'] }} @endif" name="email">
                                                <input type="text" name="passengerType" hidden value="adults" class="form-control">
                                              </div>
                                              <div class="col-4">
                                                <label for="inputAddress" class="form-label">Nationality</label>
                                                <input type="text" name="country" id="nationality_lead" class="form-control">
                                              </div>
                                              <div class="col-4">
                                                <label for="inputAddress" class="form-label">Date of birth</label>
                                                <input  type="text" id="date_of_birth" name="date_of_birth" class="form-control " required value="@if(session('passengerDetail')) {{ session('passengerDetail')[0]['date_of_birth'] }} @endif"/> 
                                              </div>
                                              <div class="col-4">
                                                <label for="inputAddress" class="form-label">Phone</label>
                                                <input  type="text" id="lead_mobile" name="phone" class="form-control otp_code" required value="@if(session('passengerDetail')) {{ session('passengerDetail')[0]['phone'] }} @endif"/> 
                                              </div>
                                               <div class="col-4">
                                                <label for="inputAddress" class="form-label">Passport Number</label>
                                                <input  type="text" id="passport_lead" name="passport_lead" class="form-control otp_code" required value="@if(session('passengerDetail')) {{ session('passengerDetail')[0]['passport_lead'] }} @endif"/> 
                                              </div>
                                              <div class="col-4">
                                                <label for="inputAddress" class="form-label">Passport Expiry</label>
                                                <input  type="text" id="passport_exp_lead" name="passport_exp_lead" class="form-control " required value="@if(session('passengerDetail')) {{ session('passengerDetail')[0]['passport_exp_lead'] }} @endif"/> 
                                              </div>
                
                
                
                                            <p>Gender</p>
                                            <div class="col-4">
                                              <div class="form-check">
                                              <input class="form-check-input" type="radio" name="gender" value="male" @if(session('passengerDetail')) @if(session('passengerDetail')[0]['gender'] == 'male') checked @endif @else checked  @endif id="male">
                                              <label class="form-check-label" for="male">
                                                Male
                                              </label>
                                            </div>
                                            
                                            </div>
                                            <div class="col-4">
                                                <div class="form-check">
                                              <input class="form-check-input" type="radio" name="gender" value="female" @if(session('passengerDetail')) @if(session('passengerDetail')[0]['gender'] == 'female') checked @endif  @endif id="female">
                                              <label class="form-check-label" for="female">
                                                Female
                                              </label>
                                            </div>
                                            
                                            </div>
                                            
                                           
                                              <div class="col-12">
                                                
                                                                    <button class="btn" style="background-color:#277019; color:white;float: right;" type="submit">Update</button>
                                                               
                                                                
                                                                
                                                                      
                                                             
                                              </div>
                                          
                                            </form> 
                                        </div>
                                        
                                      
                                        
                                    </div>
                                </div>
                                
                                <div class="col-lg-6">
                                    <h5>Lead Passenger Name : <span id="lead_name_ID"></span></h5>
                                    <p>Maximum Pax :<span id="no_of_pax_days_ID"></span></p>
                                    <input type="hidden" value="Invoice" id="package_Type" name="package_Type">
                                    <input type="hidden" value="" id="no_of_pax_days_Input" name="no_of_pax_days_Input">
                                    <input type="hidden" value="" id="invoice_Id_Input" name="invoice_Id_Input">
                                    <input type="hidden" value="" id="count_P_Input" name="count_P_Input">
                                    <input type="hidden" value="" id="count_P_Input1">
                                    <input type="hidden" value="" id="more_Passenger_Data_Input" name="more_Passenger_Data_Input">
                                </div>
                     
                                
                                <div class="col-lg-12">
                                    <div class="mb-3 text-last" id="edit_M_P_Button">
                                        <button class="btn btn-primary" id="add_more_pass_btn" onclick="add_new_passenger()" type="button">Add more Passenger</button>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                        <table class="table">
                                          <thead>
                                            <tr>
                                              <th scope="col">Sr</th>
                                              <th scope="col">First Name</th>
                                              <th scope="col">Last Name</th>
                                              <th scope="col">gender</th>
                                              <th scope="col">Nationality</th>
                                              <th scope="col">Date of birth</th>
                                              <th scope="col">Passport Number</th>
                                              <th scope="col">passport Expiry</th>
                                              <th scope="col">Action</th>
                                            </tr>
                                          </thead>
                                          <tbody id="otherPassenger">
                                           
                                          </tbody>
                                        </table>
                                </div>
                                
                           
                                
                                <div class="row" id="" style="float:right">
                                    <div class="col-lg-12 mb-3">
                                        <button class="btn btn-primary" id="button_P" type="submit" style="display: none;">Submit</button>
                                    </div>
                                </div>
                            </div>
                            
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="exampleModalAddMore" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add More Passenger  </h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    
                  </div>
                  
                  <div class="row">
                                                <div class="col">
                                                    
                                                    <form method="post" id="formother" action="https://alhijaztours.net/Uploadpassport" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="form">
                                                          <input type="file" id="other_img"  onchange="loadFileactivity1(event)" name="file" class="hidden" style="display: none;" /> 
                                                          <button type="button" class="btn btn-primary"> <label for="other_img">Upload ScreenShot Of Passport. </label>
                                                          </button>
                                                          <button type="submit" id="submit_buttonother" class="btn btn-primary "> Submit
                                                          </button>
                                                        </div>
                                                    </form>
    
                                                    <span class="setcategory2 mt-5">
                                                        <img id="imgoutput_other" width="190" />
                                                    </span>
                                                 
                                                  
                                                   
                                                                                          
                                              </div>
                                                 
                                               <!-- <button class="color2-bg no-shdow-btn btn btn-success">Submit</button> -->
                                        </div>
                  
                  <div class="modal-body">
                    <form action="{{ url('add_more_passenger_Invoice') }}" method="post">
                        @csrf
                        <div class="row">
                          
                            
                              <div class="col-md-6">
                                    <label for="inputEmail4" class="form-label">First name</label>
                                    <input type="text" class="form-control" id="f_name" required  name="passengerName">
                                    <input type="text" name="invoice_req_from" hidden id="" value="otherPassenger">
                                    <input type="text" name="invoice_id" hidden class="inovice_id">
    
                              </div>
                                  <div class="col-md-6">
                                    <label for="inputPassword4" class="form-label">Last name</label>
                                    <input type="text" class="form-control" id="l_name" required name="lname">
                                  </div>
                                  
                                  <div class="col-6">
                                    <label for="inputAddress" class="form-label">Nationality</label>
                                    <input type="text" name="country" id="nationality_other" class="form-control">
                                  </div>
                                  <div class="col-6">
                                    <label for="inputAddress" class="form-label">Date of birth</label>
                                    <input  type="text" id="date_of_birth_other" name="date_of_birth" class="form-control " required /> 
                                  </div>
                                 
                                   <div class="col-6">
                                    <label for="inputAddress" class="form-label">Passport Number</label>
                                    <input  type="text" id="passport_lead_other" name="passport_lead" class="form-control otp_code" required /> 
                                  </div>
                                  <div class="col-6">
                                    <label for="inputAddress" class="form-label">Passport Expiry</label>
                                    <input  type="text" id="passport_exp_lead_other" name="passport_exp_lead" class="form-control " required /> 
                                 </div>
                            <div class="col-md-6">
                                <div class="row" style="margin-top:2.5rem">
                                    <div class="col-md-4">
                                        <label for="">Gender</label>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" id="gender1" value="male" checked>
                                        <label class="form-check-label" for="gender1">
                                            Male
                                        </label>
                                    </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" id="gender2" value="female">
                                        <label class="form-check-label" for="exampleRadios2">
                                            Female
                                        </label>
                                        </div>
                                    </div>
                                    
                                
                                 
                                </div>
                               
                                
                            </div>
                         
                            
                        </div>
                    
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="submit" class="btn btn-primary">Save changes</button>
                  </div>
                  </form>
                </div>
              </div>
            </div>
        </div>
        
        <div class="modal fade" id="alert-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body" id="info_alert_modal_div"></div>
                </div>
            </div>
        </div>
        
    </div>
    
    <div class="modal fade" id="exampleModalAddMoreedit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Passenger  </h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

              </div>

              <div class="modal-body">
                <form action="{{ url('add_more_passenger_Invoice') }}" method="post">
                    @csrf
                    <div class="row">
                      
                        
                          <div class="col-md-6">
                                <label for="inputEmail4" class="form-label">First name</label>
                                <input type="text" class="form-control" id="f_name_edit" required  name="passengerName">
                                <input type="text" name="invoice_req_from" hidden id="" value="updatePassenger">
                                <input type="text" name="invoice_id" hidden class="inovice_id">
                                <input type="text" name="index" hidden id="passIndex">

                          </div>
                              <div class="col-md-6">
                                <label for="inputPassword4" class="form-label">Last name</label>
                                <input type="text" class="form-control" id="l_name_edit" required name="lname">
                              </div>
                              
                              <div class="col-6">
                                <label for="inputAddress" class="form-label">Nationality</label>
                                <input type="text" name="country" id="nationality_other_edit" class="form-control">
                              </div>
                              <div class="col-6">
                                <label for="inputAddress" class="form-label">Date of birth</label>
                                <input  type="text" id="date_of_birth_other_edit" name="date_of_birth" class="form-control " required /> 
                              </div>
                             
                               <div class="col-6">
                                <label for="inputAddress" class="form-label">Passport Number</label>
                                <input  type="text" id="passport_lead_other_edit" name="passport_lead" class="form-control otp_code" required /> 
                              </div>
                              <div class="col-6">
                                <label for="inputAddress" class="form-label">Passport Expiry</label>
                                <input  type="text" id="passport_exp_lead_other_edit" name="passport_exp_lead" class="form-control " required /> 
                             </div>
                        <div class="col-md-6">
                            <div class="row" style="margin-top:2.5rem">
                                <div class="col-md-4">
                                    <label for="">Gender</label>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="gender1_edit" value="male" checked>
                                    <label class="form-check-label" for="gender1_edit">
                                        Male
                                    </label>
                                </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="gender2_edit" value="female">
                                    <label class="form-check-label" for="gender2_edit">
                                        Female
                                    </label>
                                    </div>
                                </div>
                                
                            
                             
                            </div>
                           
                            
                        </div>
                     
                        
                    </div>
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" name="submit" class="btn btn-primary">Save changes</button>
              </div>
              </form>
            </div>
          </div>
        </div>
        
    </div>
    
    <div id="add_special_discount" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" >
                <div class="modal-header">
                    <h4 class="modal-title" id="standard-modalLabel">Add Special Discount</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <form action="{{URL::to('add_special_discount_Invoice')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            
                            <input type="text" id="invoice_id" hidden class="form-control" name="invoice_id">
                            
                            <div class="col-lg-12" style="padding: 5px;">
                                <lable>Total Amount</lable>
                                <input type="text" id="total_amount" readonly class="form-control" name="total_amount">
                            </div>
                            
                            <div class="col-lg-12" style="padding: 5px;">
                                <lable class="mt-3">Add Special Discount</lable>
                                <input type="text" class="form-control" name="discount_amount">
                            </div>
                            
                            <div class="col-lg-12" style="padding: 5px;">
                                <button class="btn btn-primary"  type="submit">Submit</button>
                            </div>
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div id="add_single_discount" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="standard-modalLabel">Add Special Discount</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <form action="{{URL::to('add_single_discount_Invoice')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row d-none">
                            
                            <!--Accomodation Details-->
                            <h1 id="discount_invoice_type"></h1>
                            
                            <div class="col-lg-12" style="padding: 5px;">
                                <lable>Invoice Type</lable>
                                <input type="text" id="discount_invoice_type_input" class="form-control" readonly required>
                            </div>
                            
                            <div class="col-lg-12" style="padding: 5px;">
                                <lable>Total Amount</lable>
                                <input type="text" id="discount_total_amount" class="form-control" name="discount_total_amount" readonly required>
                            </div>
                            
                            <div class="col-lg-12" style="padding: 5px;">
                                <lable>Discount Amount</lable>
                                <input type="text" id="discount_amount_Invoice" class="form-control" name="discount_amount_Invoice" required>
                            </div>
                            
                            <div class="col-lg-12 d-none" style="padding: 5px;">
                                <lable>New Total Amount</lable>
                                <input type="text" id="new_total_amount" class="form-control" name="new_total_amount" readonly required>
                            </div>
                            
                            <div class="col-lg-12" style="padding: 5px;">
                                <button class="btn btn-primary"  type="submit">Submit</button>
                            </div>
                            
                        </div>
                        
                        
                        <div id="append_discount_data"></div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    
    <script>
        function add_special_discount(invoice_id,totalAmount){
            $('#add_special_discount').modal('show');
            $('#invoice_id').val(invoice_id);
            $('#total_amount').val(totalAmount);
        }
    
        function add_single_discount(invoice_id){
            
            $('#append_discount_data').empty();
            
            $('#add_single_discount').modal('show');
            
            var invoice_data_attr_type  = $('#invoice_data_'+invoice_id+'').attr('attr-type');
            $('#discount_invoice_type').html(invoice_data_attr_type);
            $('#discount_invoice_type_input').val(invoice_data_attr_type);
            
            // if(invoice_data_attr_type != null && invoice_data_attr_type != ''){
            //     if(invoice_data_attr_type == 'Hotel'){
            //         invoice_data_attr_type = 'hotel_Type_Costing';
            //     }else if(invoice_data_attr_type == 'Visa'){
            //         invoice_data_attr_type = 'visa_Type_Costing';
            //     }else if(invoice_data_attr_type == 'Flight'){
            //         invoice_data_attr_type = 'flight_Type_Costing';
            //     }else if(invoice_data_attr_type == 'Transfer'){
            //         invoice_data_attr_type = 'transportation_Type_Costing';
            //     }else{
            //         invoice_data_attr_type = 'All_services';
            //     }
            // }
            
            var invoice_data_E  = $('#invoice_data_'+invoice_id+'').val();
            var invoice_data    = JSON.parse(invoice_data_E);
            console.log(invoice_data);
            
            // var markup_details_E    = invoice_data['markup_details'];
            // if(markup_details_E != null && markup_details_E != ''){
            //     var markup_details      = JSON.parse(markup_details_E);
            //     $.each(markup_details, function (key, markup_details_value) {
            //         if(markup_details_value.markup_Type_Costing == 'hotel_Type_Costing'){
            //             // var markup_price    = markup_details_value.markup_price;
            //             // var visa_Pax        = invoice_data['visa_Pax'];
            //             // var total           = markup_price * visa_Pax;
            //             // total               = parseFloat(total);
            //             // total               = total.toFixed(2);
            //             // console.log(total);
            //             // $('#discount_total_amount').val(total);
                        
            //             var total_Room_Price    = markup_details_value.markup_price * markup_details_value.acc_hotel_Quantity;
            //             total_Room_Price        = parseFloat(total_Room_Price);
            //             total_Room_Price        = total_Room_Price.toFixed(2);
                        
            //             var data = `<div class="row">
            //                             <h1>${invoice_data_attr_type}</h1>
                                        
            //                             <div class="col-lg-2">
            //                                 <label>Room Type</label>
            //                                 <input type="text" class="form-control" value="${markup_details_value.room_type}" readonly>
            //                             </div>
                                        
            //                             <div class="col-lg-2">
            //                                 <label>Markup Price/Per Room</label>
            //                                 <input type="text" class="form-control" value="${markup_details_value.markup_price}" readonly>
            //                             </div>
                                        
            //                             <div class="col-lg-2">
            //                                 <label>Quantity</label>
            //                                 <input type="text" class="form-control" value="${markup_details_value.acc_hotel_Quantity}" readonly>
            //                             </div>
                                        
            //                             <div class="col-lg-2">
            //                                 <label>Total Price</label>
            //                                 <input type="text" class="form-control" value="${total_Room_Price}" readonly>
            //                             </div>
                                        
            //                             <div class="col-lg-2">
            //                                 <label>Discount Amount</label>
            //                                 <input type="text" class="form-control" name="accomodation_discount_amount[]">
            //                             </div>
                                        
            //                         </div>`;
            //             $('#append_discount_data').append(data);
                        
            //         }
            //     });
            // }
            // console.log(markup_details);
            
            var accomodation_details_E = invoice_data['accomodation_details'];
            if(accomodation_details != null && accomodation_details != '' && accomodation_details != 'null'){
                var accomodation_details = JSON.parse(accomodation_details_E);
                console.log(accomodation_details);
                $.each(accomodation_details, function (key, AD_value) {
                    var acc_type                = AD_value.acc_type;
                    var acc_qty                 = AD_value.acc_qty;
                    var hotel_invoice_markup    = AD_value.hotel_invoice_markup;
                    var acc_total               = acc_qty * hotel_invoice_markup;
                    acc_total                   = parseFloat(acc_total);
                    acc_total                   = acc_total.toFixed(2);
                    
                    var data = `<div class="row">
                                        <h1>Accomodation Details</h1>
                                        
                                        <div class="col-lg-2">
                                            <label>Room Type</label>
                                            <input type="text" class="form-control" value="${acc_type}" readonly>
                                        </div>
                                        
                                        <div class="col-lg-2">
                                            <label>Markup Price/Per Room</label>
                                            <input type="text" class="form-control" value="${hotel_invoice_markup}" readonly>
                                        </div>
                                        
                                        <div class="col-lg-2">
                                            <label>Quantity</label>
                                            <input type="text" class="form-control" value="${acc_qty}" readonly>
                                        </div>
                                        
                                        <div class="col-lg-2">
                                            <label>Total Price</label>
                                            <input type="text" class="form-control" value="${acc_total}" readonly>
                                        </div>
                                        
                                        <div class="col-lg-2">
                                            <label>Discount Amount</label>
                                            <input type="text" class="form-control" name="accomodation_discount_amount[]">
                                        </div>
                                        
                                    </div>`;
                    $('#append_discount_data').append(data);
                });
            }
            
            var accomodation_details_more_E = invoice_data['accomodation_details_more'];
            if(accomodation_details_more != null && accomodation_details_more != '' && accomodation_details_more != 'null'){
                var accomodation_details_more = JSON.parse(accomodation_details_more_E);
                $.each(accomodation_details_more, function (key, ADM_value) {
                    var more_acc_type               = ADM_value.more_acc_type;
                    var more_acc_qty                = ADM_value.more_acc_qty;
                    var more_hotel_invoice_markup   = ADM_value.more_hotel_invoice_markup;
                    var more_acc_total              = more_acc_qty * more_hotel_invoice_markup;
                    more_acc_total                  = parseFloat(more_acc_total);
                    more_acc_total                  = more_acc_total.toFixed(2);
                    
                    var data = `<div class="row">
                                        <h1>More Accomodation Details</h1>
                                        
                                        <div class="col-lg-2">
                                            <label>Room Type</label>
                                            <input type="text" class="form-control" value="${more_acc_type}" readonly>
                                        </div>
                                        
                                        <div class="col-lg-2">
                                            <label>Markup Price/Per Room</label>
                                            <input type="text" class="form-control" value="${more_hotel_invoice_markup}" readonly>
                                        </div>
                                        
                                        <div class="col-lg-2">
                                            <label>Quantity</label>
                                            <input type="text" class="form-control" value="${more_acc_qty}" readonly>
                                        </div>
                                        
                                        <div class="col-lg-2">
                                            <label>Total Price</label>
                                            <input type="text" class="form-control" value="${more_acc_total}" readonly>
                                        </div>
                                        
                                        <div class="col-lg-2">
                                            <label>Discount Amount</label>
                                            <input type="text" class="form-control" name="accomodation_discount_amount[]">
                                        </div>
                                        
                                    </div>`;
                    $('#append_discount_data').append(data);
                });
            }
        }
    
        function edit_function(id){
            var input_url  = `{{ URL::to('edit_Invoices')}}`;
            $('#info_alert_modal_div').empty();
            var data = `<h4 class="mt-2">Are you sure you want to Edit this Invoice?</h4>
                        <a href="${input_url}/${id}" class="btn btn-info my-2" type="button">Yes</a>
                        <button type="button" class="btn btn-info my-2" data-bs-dismiss="modal">No</button>`;
            $('#info_alert_modal_div').append(data);
        }
        
        function delete_function(id){
            var input_url  = `{{ URL::to('super_admin/delete_invoice')}}`;
            $('#info_alert_modal_div').empty();
            var data = `<h4 class="mt-2">Are you sure you want to Delete this invoice?</h4>
                        <a href="${input_url}/${id}" class="btn btn-info my-2" type="button">Yes</a>
                        <button type="button" class="btn btn-info my-2" data-bs-dismiss="modal">No</button>`;
            $('#info_alert_modal_div').append(data);
        }
        
        function pay_Amount_function(id){
            var input_url  = `{{ URL::to('pay_invoice_Agent')}}`;
            $('#info_alert_modal_div').empty();
            var data = `<h4 class="mt-2">Are you sure you want to Pay Amount of this invoice?</h4>
                        <a href="${input_url}/${id}" class="btn btn-info my-2" type="button">Yes</a>
                        <button type="button" class="btn btn-info my-2" data-bs-dismiss="modal">No</button>`;
            $('#info_alert_modal_div').append(data);
        }
        
        function view_Invoice_function(id){
            var input_url  = `{{ URL::to('invoice_Agent')}}`;
            $('#info_alert_modal_div').empty();
            var data = `<h4 class="mt-2">Are you sure you want to View this invoice?</h4>
                        <a href="${input_url}/${id}" class="btn btn-info my-2" type="button">Yes</a>
                        <button type="button" class="btn btn-info my-2" data-bs-dismiss="modal">No</button>`;
            $('#info_alert_modal_div').append(data);
        }
        
        function view_Atol_Certificate_function(id){
            var input_url  = `{{ URL::to('view_atol_certificate')}}`;
            $('#info_alert_modal_div').empty();
            var data = `<h4 class="mt-2">Are you sure you want to View Atol Certificate of this invoice?</h4>
                        <a href="${input_url}/${id}" class="btn btn-info my-2" type="button">Yes</a>
                        <button type="button" class="btn btn-info my-2" data-bs-dismiss="modal">No</button>`;
            $('#info_alert_modal_div').append(data);
        }
        
        function view_Idemnity_Form_function(id){
            var input_url  = `{{ URL::to('view_idemnity_form')}}`;
            $('#info_alert_modal_div').empty();
            var data = `<h4 class="mt-2">Are you sure you want to View Idemnity Form of this invoice?</h4>
                        <a href="${input_url}/${id}" class="btn btn-info my-2" type="button">Yes</a>
                        <button type="button" class="btn btn-info my-2" data-bs-dismiss="modal">No</button>`;
            $('#info_alert_modal_div').append(data);
        }
        
        function confirm_Booking_function(id){
            var input_url  = `{{ URL::to('confirm_invoice_Agent')}}`;
            $('#info_alert_modal_div').empty();
            var data = `<h4 class="mt-2">Are you sure you want to Confirm Booking of this invoice?</h4>
                        <a href="${input_url}/${id}" class="btn btn-info my-2" type="button">Yes</a>
                        <button type="button" class="btn btn-info my-2" data-bs-dismiss="modal">No</button>`;
            $('#info_alert_modal_div').append(data);
        }
    </script>
  
    <script>
    
        $(document).ready(function () {
          
            $('#total_flights').html({{ $flights_count }});
            $('#total_hotels').html({{ $hotel_count }});
            $('#total_transfer').html({{ $transfer_count }});
            $('#total_visa').html({{ $visa_count }});
            //DataTable
            $('#myTable').DataTable({
                pagingType: 'full_numbers',
            });
            
            //View Modal Single Quotation
            $('.detail-btn').click(function() {
                const id = $(this).attr('data-id');
                $.ajax({
                    url: 'view_QuotationsID/'+id,
                    type: 'GET',
                    data: {
                        "id": id
                    },
                    success:function(data) {
                        var a                = data['a'];
                        var roundTripDetails = a['roundTripDetails'];
                        var oneWayDetails    = a['oneWayDetails'];
                        
                        console.log(a);
            
                        //Flight Details
                        $('#airline_name').html(oneWayDetails['airline_name']);
                        $('#deprturetime').html(oneWayDetails['deprturetime']);
                        $('#ArrivalTime').html(oneWayDetails['ArrivalTime']);
                        $('#deprturedate').html(oneWayDetails['deprturedate']);
                        $('#ArrivalDate').html(oneWayDetails['ArrivalDate']);
                        $('#departure').html(oneWayDetails['departure']);
                        $('#arrival').html(oneWayDetails['arrival']);
                        
                        $('#flighttype').html(a['flighttype']);
                        $('#flight_price').html(a['flight_price']);
                        
                        //Hotel Booking Details Makkkah
                        $('#dateinmakkah').html(a['dateinmakkah']);
                        $('#dateoutmakkah').html(a['dateoutmakkah']);
                        $('#hotel_name_makkah').html(a['hotel_name_makkah']);
                        $('#no_of_rooms_makkah').html(a['no_of_rooms_makkah']);
                        $('#Price_Per_Nights_Makkah').html(a['Price_Per_Nights_Makkah']);
                        $('#Makkah_total_price_cal').html(a['Makkah_total_price_cal']);
                        
                        //Hotel Booking Details Madinah
                        $('#dateinmadinah').html(a['dateinmadinah']);
                        $('#dateoutmadinah').html(a['dateoutmadinah']);
                        $('#hotel_name_madina').html(a['hotel_name_madina']);
                        $('#no_of_rooms_madina').html(a['no_of_rooms_madina']);
                        $('#price_per_night_madinah').html(a['price_per_night_madinah']);
                        $('#madinah_total_price_cal').html(a['madinah_total_price_cal']);
                        
                        
                        //Transfer Details
                        $('#transfer_vehicle').html(a['transfer_vehicle']);
                        $('#passenger').html(a['passenger']);
                        $('#pickuplocat').html(a['pickuplocat']);
                        $('#dropoflocat').html(a['dropoflocat']);
                        $('#trans_date').html(a['trans_date']);
                        $('#transf_price').html(a['transf_price']);
                        
                        //Visa Details
                        $('#visa_fees_adult').html(a['visa_fees_adult']);
                        $('#visa_fees_child').html(a['visa_fees_child']);
                        $('#visa_fees_price').html(a['visa_fees_price']);
                        
                        //Totals
                        $('#flight_price_total').html(a['flight_price']);
                        $('#Makkah_total_price_cal').html(a['Makkah_total_price_cal']);
                        $('#madinah_total_price_cal').html(a['madinah_total_price_cal']);
                        $('#transfers_head_total').html(a['transfers_head_total']);
                        $('#visa_fees').html(a['visa_fees_price']);
                        $('#grand_total_price').html(a['grand_total_price']);
            
                    }
                })
            });
            
            
           
        });

    </script>
    
    <script>
        var all_countries = {!!json_encode($all_countries)!!};
        
        function add_more_passenger(id){
            $('#edit_M_P_Button').css('display','')
            $('#add_more_P_div').empty();
            $('#invoice_Id_Input').val();
            $('#lead_name_ID').empty();
            $('#no_of_pax_days_ID').empty();
            $('#no_of_pax_days_Input').val();
            $('#add_more_P').css('display','');
            $('#more_P_D_div').empty();
            
            let I_id = id;
            var aa = 1;
            $.ajax({
                url:"{{URL::to('get_single_Invoice')}}" + '/' + I_id,
                method: "GET",
                data: {
                	I_id:I_id
                },
                success:function(data){
                    console.log(data);
                    var data1                   = data['data'];
                    console.log(data1);
                    var invoice_Id              = data1['id'];
                    var f_name                  = data1['f_name'];
                    var middle_name             = data1['middle_name'];
                    var no_of_pax_days          = data1['no_of_pax_days'];
                    var email                   = data1['email'];
                    var mobile                  = data1['mobile'];
                    var country                 = data1['lead_nationality'];
                    var gender                  = data1['gender'];
                    var lead_dob                = data1['lead_dob'];
                    var lead_nationalityE       = data1['lead_nationality'];
                    var lead_passport_number    = data1['lead_passport_number'];
                    var lead_passport_expiry    = data1['lead_passport_expiry'];
                    
                    $('.inovice_id').val(invoice_Id);         	  
                    $('#lead_name_ID').html(f_name+' '+middle_name);
                    $('#f_name_lead').val(f_name);
                    $('#l_name_lead').val(middle_name);
                    $('#nationality_lead').val(country);
                    $('#lead_email').val(email);
                    $('#lead_mobile').val(mobile);
                    $('#date_of_birth').val(lead_dob);
                    $('#passport_lead').val(lead_passport_number);
                    $('#passport_exp_lead').val(lead_passport_expiry);
                 
                    // $('#lead_nationality').val(country);
                    if(gender == 'male'){
                        $('.male_lead').prop('checked', true);
                    }
                    else{
                        $('.female_lead').prop('checked', true);
                    }

                   
                    
                    var more_Passenger_Data = data1['more_Passenger_Data'];
                    if(more_Passenger_Data){
                        
                        var more_Passenger_Data_decode = JSON.parse(data1['more_Passenger_Data']);
                            // var countPassengers = more_Passenger_Data_decode.sizeOf();
                            //  if(countPassengers == data1['no_of_pax_days']){
                            //      $('#add_more_pass_btn').css('display','none');
                            //  }else{
                            //     $('#add_more_pass_btn').css('display','block');
                            //  }
                             
                            var trHtml = ``;
                        var x = 1;
                        $.each(more_Passenger_Data_decode, function (key, value) {
                            var passenerObj = JSON.stringify(value);
                            trHtml +=`<tr>
                                        <td>${x++}</td>
                                        <td>${value.more_p_fname}</td>
                                        <td>${value.more_p_lname}</td>
                                        <td>${value.more_p_gender}</td>
                                        <td>${value.more_p_nationality}</td>
                                        <td>${value.more_p_dob}</td>
                                        <td>${value.more_p_passport_number}</td>
                                        <td>${value.more_p_passport_expiry}</td>
                                        <td>
                                            <input type="text" id="pass${x}" hidden value='${passenerObj}'>
                                            <button class="btn btn-success btn-sm" onclick="updateOtherPassenger(${x})">Edit</button>
                                        </td>
                                    </tr>`;
                             
                             console.log(more_Passenger_Data_decode);
                          
              
                        });
                        
                        $('#otherPassenger').html(trHtml);
                    }else{
                        console.log('else is execute');
                    }
                    
                   
                }
            }); 
        }
        
 
        
    </script>
    
    <script>
        function view_outS(id){
            const I_id = $('#view_outSid_'+id+'').attr('data-id');
            $('#view_outS_'+id+'').empty();
            
            $.ajax({
                url:"{{URL::to('get_single_Invoice')}}" + '/' + I_id,
                type: 'GET',
                data: {
                    I_id: I_id
                },
                success:function(data) {
                    // console.log(data);
                    var total_Invoice_Agent         = data['total_Invoice_Agent'];
                    var outStanding_Invoice_Agent   = data['outStanding_Invoice_Agent'];
                    
                    if(outStanding_Invoice_Agent != null && outStanding_Invoice_Agent != ''){
                        var total_AmountF   = parseInt(total_Invoice_Agent['total_Amount']);
                        var out_S           = parseFloat(total_AmountF) - parseFloat(outStanding_Invoice_Agent);
                        if(out_S < 0){
                            $('#view_outSid_'+id+'').html('OVER PAID'+'('+out_S+')'+'');
                        }else{
                            $('#view_outSid_'+id+'').html(out_S);  
                        }   
                    }else{
                        $('#view_outSid_'+id+'').html('Nothing Paid Yet');
                    }
                },
            });
        }
        
        
         function add_new_passenger(){
                $('#exampleModalAddMore').modal('show');
         }
         
         function updateOtherPassenger(index){
             var passengerObject = $('#pass'+index+'').val();
             
             var passengerObject = JSON.parse(passengerObject);
             console.log(passengerObject);
             $('#exampleModalAddMoreedit').modal('show');
               $('#f_name_edit').val(passengerObject['more_p_fname']);
                $('#l_name_edit').val(passengerObject['more_p_lname']);
                $('#nationality_other_edit').val(passengerObject['more_p_nationality']);
                $('#date_of_birth_other_edit').val(passengerObject['more_p_dob']);
                $('#passport_lead_other_edit').val(passengerObject['more_p_passport_number']);
                $('#passport_exp_lead_other_edit').val(passengerObject['more_p_passport_expiry']);
                $('#passIndex').val(index);
                if(passengerObject['more_p_gender'] == 'male'){
                    $('#gender1_edit').attr('checked','checked')
                }else{
                    $('#gender2_edit').attr('checked','checked')
                }
                
               
         }
    
    </script>

@endsection

@section('scripts')

    <script src='https://cdn.rawgit.com/naptha/tesseract.js/1.0.10/dist/tesseract.js'></script>
    
    <script>
        $('.passport_form').empty();
            
        var loadFileactivity = function(event) {
            var image = document.getElementById('imgoutput');
            image.src = URL.createObjectURL(event.target.files[0]);
        
            // predefined file types for validation
            var mime_types = [ 'image/jpeg', 'image/png' ];
        
            var submit_button = document.querySelector('#submit_button');
        
            submit_button.addEventListener('click', function() {
        
       
      
        $("#form1").on('submit',(function(e) {
            
            $('#leadpassenger_loader').css('display','block');
            
        e.preventDefault();
        $.ajax({
             url: "https://alhijaztours.net/Uploadpassport",
       type: "POST",
       data:  new FormData(this),
       contentType: false,
             cache: false,
       processData:false,
       beforeSend : function()
       {
        //$("#preview").fadeOut();
        $("#err").fadeOut();
       },
       success: function(data)
          {
        console.log(data);
        
        var data = 'urls={{URL::to('')}}/public/images/passportimg/'+data;
    
        var xhr = new XMLHttpRequest();
    
        xhr.addEventListener("readystatechange", function () {
        if (this.readyState === this.DONE) {
            var result = this.responseText;
            if(result){
                 result = JSON.parse(result);
            
            // console.log(result['result']);
            
            $('#leadpassenger_loader').css('display','none');
            $('#leadpassenger_form').css('display','block');
            
            var result = result['result'][0];
            var prediction = result['prediction'];
        //   console.log(prediction);
            for (var i = 0; i < prediction.length; i++) {
               
                if(prediction[i]['label'] == 'Passport_Number'){
                    $('#passport_lead').val(prediction[i]['ocr_text']);
                }
                
               
                if(prediction[i]['label'] == 'Surname'){
                     $('#l_name_lead').val(prediction[i]['ocr_text']);
                  
                }  
                
                
                
                if(prediction[i]['label'] == 'Code'){
               
                }
                
                
    
                 
                 if(prediction[i]['label'] == 'First_Name'){
                     $('#f_name_lead').val(prediction[i]['ocr_text']);
                }
                
                
                
                if(prediction[i]['label'] == 'Nationality'){
                    $('#nationality_lead').val(prediction[i]['ocr_text']);
                }
                
                
                
                 if(prediction[i]['label'] == 'Date_of_Birth'){
                      $('#date_of_birth').val(prediction[i]['ocr_text']);
                      console.log(prediction[i]['ocr_text'])
                }
                
     
                
                if(prediction[i]['label'] == 'Sex'){
                    if(prediction[i]['ocr_text'] == 'M'){
                        $('#male').attr('checked','checked');
                        $('#mr').attr('selected','selected');
                    }else{
                        $('#female').attr('checked','checked');
                        $('#mrs').attr('selected','selected');
                    }
                }
    
                
                if(prediction[i]['label'] == 'Place_of_birth'){
               
                }
                
                
    
               
                if(prediction[i]['label'] == 'Date_of_Issue'){
           
                }
                
                
    
                 
                 if(prediction[i]['label'] == 'Date_of_expiry'){
                     
                     $('#passport_exp_lead').val(prediction[i]['ocr_text']);
                     console.log(prediction[i]['ocr_text'])
                     var today  = prediction[i]['ocr_text'];

                        console.log(today.toLocaleDateString("en-US")); 
              
                }
                
        
                
               
                if(prediction[i]['label'] == 'MRZ'){
               
                };
                
                
     
                
            }
          
               
           
           
            }
           
        }
    });
    
            xhr.open("POST", "https://app.nanonets.com/api/v2/OCR/Model/8f515866-897e-4291-b1ac-c3e9096f6c8b/LabelUrls/?async=false");
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.setRequestHeader("authorization", "Basic " + btoa("qrpOJl8dy39IJLmjGn322Otm7U7rJvJU:"));
            xhr.send(data);
          },
         error: function(e) 
          {
        $('.passport_form').append('Sorry error occur while reading!');
          }          
        });
     }));
    });
    
    };
            
        $('.passport_form').empty();
            
        var loadFileactivity1 = function(event) {
            var image = document.getElementById('imgoutput_other');
            image.src = URL.createObjectURL(event.target.files[0]);
        
    
            // predefined file types for validation
            var mime_types = [ 'image/jpeg', 'image/png' ];
        
            var submit_button = document.querySelector('#submit_buttonother');
        
            submit_button.addEventListener('click', function() {
        
       
      
        $("#formother").on('submit',(function(e) {
            
            $('#leadpassenger_loader').css('display','block');
            
        e.preventDefault();
        $.ajax({
             url: "https://alhijaztours.net/Uploadpassport",
       type: "POST",
       data:  new FormData(this),
       contentType: false,
             cache: false,
       processData:false,
       beforeSend : function()
       {
        //$("#preview").fadeOut();
        $("#err").fadeOut();
       },
       success: function(data)
          {
        console.log(data);
        
        var data = 'urls={{URL::to('')}}/public/images/passportimg/'+data;
    
        var xhr = new XMLHttpRequest();
    
        xhr.addEventListener("readystatechange", function () {
        if (this.readyState === this.DONE) {
            var result = this.responseText;
            if(result){
                 result = JSON.parse(result);
            
            // console.log(result['result']);
            
            $('#leadpassenger_loader').css('display','none');
            $('#leadpassenger_form').css('display','block');
            
            var result = result['result'][0];
            var prediction = result['prediction'];
        //   console.log(prediction);
            for (var i = 0; i < prediction.length; i++) {
               
                if(prediction[i]['label'] == 'Passport_Number'){
                    $('#passport_lead_other').val(prediction[i]['ocr_text']);
                }
                
               
                if(prediction[i]['label'] == 'Surname'){
                     $('#l_name').val(prediction[i]['ocr_text']);
                  
                }  
                
                
                
                if(prediction[i]['label'] == 'Code'){
               
                }
                
                
    
                 
                 if(prediction[i]['label'] == 'First_Name'){
                     $('#f_name').val(prediction[i]['ocr_text']);
                }
                
                
                
                if(prediction[i]['label'] == 'Nationality'){
                    $('#nationality_other').val(prediction[i]['ocr_text']);
                }
                
                
                
                 if(prediction[i]['label'] == 'Date_of_Birth'){
                      $('#date_of_birth_other').val(prediction[i]['ocr_text']);
                      console.log(prediction[i]['ocr_text'])
                }
                
     
                
                if(prediction[i]['label'] == 'Sex'){
                    if(prediction[i]['ocr_text'] == 'M'){
                        $('#gender1').attr('checked','checked');
                    }else{
                        $('#gender2').attr('checked','checked');
                    }
                }
    
                
                if(prediction[i]['label'] == 'Place_of_birth'){
               
                }
                
                
    
               
                if(prediction[i]['label'] == 'Date_of_Issue'){
           
                }
                
                
    
                 
                 if(prediction[i]['label'] == 'Date_of_expiry'){
                     
                     $('#passport_exp_lead_other').val(prediction[i]['ocr_text']);
                   
              
                }
                
        
                
               
                if(prediction[i]['label'] == 'MRZ'){
               
                };
                
                
     
                
            }
          
               
           
           
            }
           
        }
    });
    
            xhr.open("POST", "https://app.nanonets.com/api/v2/OCR/Model/8f515866-897e-4291-b1ac-c3e9096f6c8b/LabelUrls/?async=false");
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.setRequestHeader("authorization", "Basic " + btoa("qrpOJl8dy39IJLmjGn322Otm7U7rJvJU:"));
            xhr.send(data);
          },
         error: function(e) 
          {
        $('.passport_form').append('Sorry error occur while reading!');
          }          
        });
     }));
    });
    
    };
    
    </script>

@endsection
