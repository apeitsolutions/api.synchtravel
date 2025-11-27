@extends('template/frontend/userdashboard/layout/default')
@section('content')

    <?php
        $lead_controller = new App\Http\Controllers\frontend\admin_dashboard\LeadController();
        use Carbon\Carbon;
        
        $currency = Session::get('currency_symbol'); 
        // dd($currency); 
    ?>
    
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
                    <span class="breadcrumb-item active">View Lead Quotations</span>
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
                    <span class="iconify" data-icon="uil-pricetag-alt" data-width="70"></span>
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
                    <span class="iconify" data-icon="uil-dollar-sign-alt" data-width="70"></span>
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
                        <div class="dashboard-list-box dash-list margin-top-0">
                            <h4>Quotations</h4>
                            <div class="panel-body padding-bottom-none">
                                <div class="block-content block-content-full">
                                    <div class="table-responsive">
                                        <div id="example_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                                       
                                            <table  class="table nowrap example1 dataTable display no-footer" id="myTable" role="grid" aria-describedby="example_info">
                                                    
                                                    <thead class="theme-bg-clr">
                                                        <tr role="row">
                                                            <th style="text-align: center;">Quotation Id</th>
                                                            <th style="text-align: center;">Customer Name</th>
                                                            <th style="text-align: center;">Lead Id</th>
                                                            <th style="text-align: center;">Type</th>
                                                            <th style="text-align: center;">Quotation Details</th>
                                                            <th style="text-align: center;">Quoted  By</th>
                                                            <th style="text-align: center;">Validity</th>
                                                            <th style="text-align: center;">Options</th>
                                                        </tr>
                                                    </thead>
                                                    
                                                     <tbody style="text-align: center;">
                                                        <?php
                                                            $service_Type       = '';
                                                            $i                  = 1;
                                                            $flights_count      = 0;
                                                            $hotel_count        = 0;
                                                            $transfer_count     = 0;
                                                            $visa_count         = 0;
                                                            $all_services_count = 0;
                                                        ?>
                                                        @foreach ($data as $value)
                                                            <?php
                                                                $date4days          = date('d-m-Y',strtotime($value->created_at. ' + 5 days'));
                                                                $currdate           = date('d-m-Y');
                                                                $created_at         = date('d-m-Y',strtotime($value->created_at));
                                                                $quotation_validity = date('d-m-Y H:i',strtotime($value->quotation_validity));
                                                                $origin             = new DateTime($currdate);
                                                                $target             = new DateTime($value->quotation_validity);
                                                                $remaining_validity = $origin->diff($target);
                                                                $remaining_validity = $remaining_validity->format('%a days %h:%i:%s Seconds');
                                                                
                                                                $services = json_decode($value->services);
                                                                if(isset($services)){
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
                                                                        $service_Type = 'Flight';
                                                                    }
                                                                    if($services_res == 'visa_tab'){
                                                                        $visa_count++;
                                                                        $service_Type = 'Visa';
                                                                    }
                                                                }
                                                                }
                                                            ?>
                                                            
                                                            <tr role="row" class="odd">
                                                                <td>
                                                                    <b>{{ $value->generate_id }}</b>
                                                                    <a href="{{URL::to('view_manage_Lead_Quotation_Single_Admin')}}/{{$value->id}}">
                                                                        <img height="15px" width="15px" src="{{ asset('/public/invoice_icon.png') }}">
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    @if(isset($all_Users) && $all_Users != null && $all_Users != '')
                                                                        @foreach($all_Users as $all_Users_value)
                                                                            @if($value->customer_id == $all_Users_value->id)
                                                                                <b>{{ $all_Users_value->name }}</b>
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                </td>
                                                                <td>{{ $value->lead_id }}</td>
                                                                <td>{{ $service_Type }}</td>
                                                                <td>
                                                                    @if($value->booking_customer_id == '-1' || $value->booking_customer_id == 0 || $value->booking_customer_id == '' || $value->booking_customer_id == null)
                                                                        @if(isset($Agents_detail) && $Agents_detail != null && $Agents_detail != '')
                                                                            @foreach($Agents_detail as $val2)
                                                                                @if($val2->id == $value->agent_Id)
                                                                                    <div class="row">
                                                                                        <div class="col-xl-12">    
                                                                                            <b>{{ $val2->company_name }} - Agent</b>
                                                                                            <a href="{{URL::to('agents_stats_details')}}/{{ $val2->id }}">
                                                                                                <i class="mdi mdi-account-check-outline"></i>
                                                                                            </a>
                                                                                        </div>
                                                                                        <div class="col-xl-12">
                                                                                            <b>{{ $val2->company_email }} - Agent</b>
                                                                                        </div>
                                                                                    </div>
                                                                                @endif 
                                                                            @endforeach
                                                                        @endif
                                                                    @else
                                                                        @if(isset($booking_customers) && $booking_customers != null && $booking_customers != '')
                                                                            @foreach($booking_customers as $val1)
                                                                                @if($val1->id == $value->booking_customer_id )
                                                                                    <div class="row">
                                                                                        <div class="col-xl-12">    
                                                                                            <b>{{ $val1->name }} - Customer</b>
                                                                                        </div>
                                                                                        <div class="col-xl-12">
                                                                                            <b>{{ $val1->email }} - Customer</b>
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                                <td>{{ $value->tour_author }}</td>
                                                                <td>
                                                                    @if(isset($value->quotation_validity) && $value->quotation_validity != null && $value->quotation_validity != '')
                                                                        @if($target > $origin)
                                                                            <b style="color:green">Valid till : {{ $quotation_validity }}</b>
                                                                        @else
                                                                            <b style="color:red">Quotation Expired({{ $quotation_validity }})</b>
                                                                        @endif
                                                                    @else
                                                                        <b style="color:red">Quotation Expired({{ $quotation_validity }})</b>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if($value->quotation_Invoice != '1')
                                                                        <div class="dropdown card-widgets">
                                                                            <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                                                <i class="dripicons-dots-3"></i>
                                                                            </a>
                                                                            <div class="dropdown-menu dropdown-menu-end" style="">
                                                                                <a href="{{URL::to('view_manage_Lead_Quotation_Single_Admin')}}/{{$value->id}}" class="dropdown-item">View Quotation</a>
                                                                                @if($value->quotation_status != 1)
                                                                                    @if(isset($value->quotation_validity) && $value->quotation_validity != null && $value->quotation_validity != '')
                                                                                        @if($target > $origin)
                                                                                            @if($value->all_services_quotation != '1')
                                                                                                <a href="{{URL::to('confirm_manage_Lead_Quotation')}}/{{$value->id}}/{{$value->lead_id}}" class="dropdown-item d-none">Confirm</a>
                                                                                            @else
                                                                                                <a href="{{URL::to('confirm_manage_Lead_Quotation_New')}}/{{$value->id}}/{{$value->lead_id}}" class="dropdown-item d-none">Confirm</a>
                                                                                            @endif
                                                                                        @endif
                                                                                    @endif
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                        <a class="btn btn-success">CONFIRMED</a>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <?php $i++ ?>
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
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  
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
