@extends('template/frontend/userdashboard/layout/default')
@section('content')

    <?php $currency=Session::get('currency_symbol'); //dd($all_countries); ?>
    
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
            </div>
        </section>
        
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-12">
                        <h4>Notification Detail</h4>
                        <div class="panel-body padding-bottom-none">
                            <div class="block-content block-content-full">
                                <div class="table-responsive">
                                    <div id="example_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                                   
                                        <table  class="table nowrap example1 dataTable no-footer" id="myTable" role="grid" aria-describedby="example_info">
                                                
                                                <thead class="theme-bg-clr">
                                                    <tr role="row">
                                                        <th style="text-align: center;">Id</th>
                                                        @if($single_notification_detail->type_of_notification == 'pay_Invoice' || $single_notification_detail->type_of_notification == 'create_Invoice')
                                                            <th style="text-align: center;">Invoice No.</th>
                                                        @endif
                                                        <th style="text-align: center;">Customer Id</th>
                                                        <th style="text-align: center;">Customer Name</th>
                                                        <th style="text-align: center;">Type of Notification</th>
                                                        <th style="text-align: center;">Total Payable</th>
                                                        <th style="text-align: center;">Option</th>
                                                    </tr>
                                                </thead>
                                                
                                                <tbody style="text-align: center;">
                                                    <tr role="row" class="odd">
                                                        <td>{{ $single_notification_detail->type_of_notification_id }} </td>
                                                        @if($single_notification_detail->type_of_notification == 'pay_Invoice' || $single_notification_detail->type_of_notification == 'create_Invoice')
                                                            <td>{{ $single_notification_detail->generate_id }}</td>
                                                        @endif
                                                        <td>{{ $single_notification_detail->customer_id }} </td>
                                                        <td>{{ $single_notification_detail->notification_creator_name }} </td>
                                                        <td>{{ $single_notification_detail->type_of_notification }} </td>
                                                        <td>{{ $single_notification_detail->total_price }} </td>
                                                        <td>
                                                            <a type="button" href="#" class="btn btn-primary">Accept</a>
                                                            
                                                            <div class="dropdown card-widgets d-none">
                                                                <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <i class="dripicons-dots-3"></i>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-end" style="">
                                                                    <a href="{{ route('edit_Invoices',$single_notification_detail->id) }}" class="dropdown-item">Edit Invoice</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
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
        
    </div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  
@endsection

@section('scripts')
    <script src='https://cdn.rawgit.com/naptha/tesseract.js/1.0.10/dist/tesseract.js'></script>
@endsection
