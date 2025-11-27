@extends('template/frontend/userdashboard/layout/default')
@section('content')
    
    <style>
        .nav-link{
          color: #575757;
          font-size: 18px;
        }
    </style>
  
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                
                <div class="row">
                    <div class="col-12">
                      <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Activity Bookings</a></li>
                                <li class="breadcrumb-item active">Tentative Bookings</li>
                            </ol>
                        </div>
                        <h4 class="page-title">View Tentative Bookings</h4>
                      </div>
                    </div>
                </div>
                
                <div class="row">
                    
                    <div class="col-md-12">
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                              <strong>{{ session('error') }}</strong>
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                        @endif
                        
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                              <strong>{{ session('success') }}</strong>
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                        @endif
                    </div>
                    
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <div id="example_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                        
                                        <!--<table class="dataTables_wrapper dt-bootstrap4 no-footer display" id="example_11" style="text-align: center;">-->
                                        <table style="width: 100%;" class="table nowrap table-striped table-vcenter dataTable no-footer" id="my_Table">
                                            
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="text-align: center;">Sr No.</th>
                                                    <th style="text-align: center;">Customer Name</th>
                                                    <th style="text-align: center;">Package Details</th>
                                                    <th style="text-align: center;">Pax Details</th>
                                                    <th style="text-align: center;">Total Amount</th>
                                                    <th style="text-align: center;" class="d-none">Payment Status</th>
                                                    <th style="text-align: center;width: 85px;" class="d-none">Action</th>
                                                    <th class="d-none"></th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody id="" style="text-align: center;">
                                                <?php $i = 1; $j = 1; $k = 1; $booking_ids = []; ?>
                                                @foreach ($data as $value)
                                                    <?php
                                                        $date4days  = date('Y-m-d',strtotime($value->created_at. ' + 2 days'));
                                                        $currdate   = date('Y-m-d');
                                                        array_push($booking_ids,$value->booking_id);
                                                    ?>
                                                    <tr id="tr_pink_{{ $value->booking_id }}">
                                                        <td>{{ $i }}</td>
                                                        <td>
                                                            @if(isset($all_Users) && $all_Users != null && $all_Users != '')
                                                                @foreach($all_Users as $all_Users_value)
                                                                    @if($value->customer_id == $all_Users_value->id)
                                                                        <b>{{ $all_Users_value->name }}</b>
                                                                        <?php $currency = $all_Users_value->currency_symbol ?>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                        <td>
                                                            Invoice Number  : <b>{{ $value->invoice_no }}</b><br>
                                                            Passenger Name  : <b>{{ $value->passenger_name }}</b><br>
                                                            @if(isset($value->agent_name) && $value->agent_name != null && $value->agent_name != '' && $value->agent_name != -1)
                                                                Agent name  : <b>{{ $value->agent_name }}</b><br>
                                                            @else
                                                            @endif
                                                            Title           : <b>{{ $value->tour_name }}</b>
                                                        </td>
                                                        <td>
                                                            <!--Adult-->
                                                            <?php 
                                                                if($value->adults > 0 && $value->adults != null && $value->adults != ''){ 
                                                                    $adults = $value->adults;
                                                                }else{ 
                                                                    $adults = 0;
                                                                }
                                                            ?>
                                                            <?php 
                                                                if($value->double_adults == null || $value->double_adults == ''){
                                                                    $double_adults = 0;
                                                                }else{
                                                                    $double_adults = $value->double_adults;
                                                                }
                                                            ?>
                                                            <?php 
                                                                if($value->triple_adults == null || $value->triple_adults == ''){
                                                                    $triple_adults = 0;
                                                                }else{
                                                                    $triple_adults = $value->triple_adults;
                                                                }
                                                            ?>
                                                            <?php 
                                                                if($value->quad_adults == null || $value->quad_adults == ''){
                                                                    $quad_adults = 0;
                                                                }else{
                                                                    $quad_adults   = $value->quad_adults;
                                                                }
                                                            ?>
                                                            <?php if(isset($value->without_acc_adults)){ ?>
                                                                Adults : <b><i class="mdi mdi-human-male-female" style="font-size: 18px;">{{ $adults + $double_adults + $triple_adults + $quad_adults + $value->without_acc_adults }}</i></b>
                                                            <?php }else{ ?>
                                                                Adults : <b><i class="mdi mdi-human-male-female" style="font-size: 18px;">{{ $adults + $double_adults + $triple_adults + $quad_adults }}</i></b>
                                                            <?php }?>
                                                            
                                                            <br>
                                                            <!--Child-->
                                                            <?php 
                                                                if($value->childs > 0 && $value->childs != null && $value->childs != ''){ 
                                                                    $childs = $value->childs;
                                                                }else{ 
                                                                    $childs = 0;
                                                                }
                                                            ?>
                                                            
                                                            <?php   
                                                                $cart_total_data = json_decode($value->cart_total_data);
                                                                if(isset($cart_total_data) && $cart_total_data != null && $cart_total_data != ''){ 
                                                            ?>
                                                                    <?php 
                                                                        if(isset($cart_total_data->double_childs) && $cart_total_data->double_childs != null && $cart_total_data->double_childs != ''){
                                                                            $double_childs = $cart_total_data->double_childs;
                                                                        }else{
                                                                            $double_childs = 0;
                                                                        }
                                                                    ?>
                                                                    <?php 
                                                                        if(isset($cart_total_data->triple_childs) && $cart_total_data->triple_childs != null && $cart_total_data->triple_childs != ''){
                                                                            $triple_childs = $cart_total_data->triple_childs;
                                                                        }else{
                                                                            $triple_childs = 0;
                                                                        }
                                                                    ?>
                                                                    <?php 
                                                                        if(isset($cart_total_data->quad_childs) && $cart_total_data->quad_childs != null && $cart_total_data->quad_childs != ''){
                                                                            $quad_childs = $cart_total_data->quad_childs;
                                                                        }else{
                                                                            $quad_childs = 0;
                                                                        }
                                                                    ?>
                                                                    <?php 
                                                                        if(isset($cart_total_data->children) && $cart_total_data->children != null && $cart_total_data->children != ''){
                                                                            $childrens = $cart_total_data->children;
                                                                        }else{
                                                                            $childrens = 0;
                                                                        }
                                                                    ?>
                                                                    Child : <b><i class="mdi mdi-human-child" style="font-size: 18px;">{{ $childrens + $double_childs + $triple_childs + $quad_childs }}</i></b>
                                                            <?php }else{ ?>
                                                                Childss : <b><i class="mdi mdi-human-child" style="font-size: 18px;">{{ $childs }}</i></b>
                                                            <?php } ?>
                                                            
                                                            <br>
                                                            <!--Infant-->
                                                            <?php   
                                                                $cart_total_data = json_decode($value->cart_total_data);
                                                                if(isset($cart_total_data) && $cart_total_data != null && $cart_total_data != ''){ 
                                                            ?>
                                                                    <?php 
                                                                        if(isset($cart_total_data->infants) && $cart_total_data->infants != null && $cart_total_data->infants != ''){
                                                                            $infants = $cart_total_data->infants;
                                                                        }else{
                                                                            $infants = 0;
                                                                        }
                                                                    ?>
                                                                    Infantss : <b><i class="mdi mdi-baby-buggy" style="font-size: 18px;">{{ $infants }}</i></b>
                                                            <?php }else{ ?>
                                                                Infants : <b><i class="mdi mdi-baby-buggy" style="font-size: 18px;">0</i></b>
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            {{ $currency }} {{ $value->price }}<br>
                                                            <span class="badge bg-success" style="font-size: 15px" onclick="add_special_discount('{{ $value->invoice_no }}',{{ $value->price }})">Add Special Discount</span>
                                                        </td>
                                                        <td class="payment_status{{ $value->booking_id }} d-none"></td>
                                                        <td style="padding-right: 50px;padding-top: 40px;" class="d-none">
                                                            <div class="dropdown card-widgets">
                                                                <a style="float: right;" href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <i class="dripicons-dots-3"></i>
                                                                </a> 
                                                                <div class="dropdown-menu dropdown-menu-end" style="">
                                                                    <a class="dropdown-item" onclick="add_more_booking_passenger('{{$value->invoice_no}}')" data-id="{{ $value->invoice_no }}" data-bs-toggle="modal" data-bs-target="#add-more-passenger-modal"><i class="mdi mdi-eye me-1"></i>View Passenger Details</a>
                                                                    <a href="{{URL::to('invoice_package_admin')}}/{{$value->invoice_no}}/{{$value->booking_id}}/{{$value->tour_id}}" class="dropdown-item"><i class="mdi mdi-eye me-1"></i>View Booking Details</a>
                                                                    <a href="{{URL::to('invoice_admin')}}/{{$value->invoice_no}}" class="dropdown-item"><i class="mdi mdi-eye me-1"></i>View Itinerary  Details</a>
                                                                    <a href="{{ route('view_booking_customer_details',$value->booking_id) }}" class="dropdown-item"><i class="mdi mdi-account"></i>Customer Details</a>
                                                                    @if($value->confirm == 1)
                                                                        <!--<div class="cancel_div_dropdown{{ $value->booking_id }}">-->
                                                                        <!--    <a href="#" class="dropdown-item"><i class="mdi mdi-check-circle"></i>Do yo Want to Cancel Booking?</a>-->
                                                                        <!--</div>-->
                                                                    @else
                                                                        <div class="confirmed_div_dropdown{{ $value->booking_id }} d-none">
                                                                            <a href="{{ route('confirmed_tour_booking',$value->booking_id) }}" class="dropdown-item"><i class="mdi mdi-check-circle"></i>Confirm Booking</a>
                                                                        </div>
                                                                    @endif
                                                                    <a href="{{ URL::to('edit_booking/'.$value->invoice_no.'') }}" class="dropdown-item d-none">
                                                                        <i class="mdi mdi-check-circle me-1"></i>
                                                                        Edit
                                                                    </a>
                                                                    <a href="{{ URL::to('delete_booking/'.$value->booking_id.'') }}" class="dropdown-item d-none" onclick="return confirm('Are you sure you want to delete?');">
                                                                        <i class="mdi mdi-check-circle me-1"></i>
                                                                        Delete
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="d-none"></td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <td class="d-none">{{ $j }}</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td>
                                                            <i style="color: green;font-size: 30px;" class="mdi mdi-arrow-down-drop-circle" id="view_remaining_details_id_{{ $i }}" onclick="view_remaining_details_button({{ $i }})"></i>
                                                            <i style="color: green;font-size: 30px;display:none" class="mdi mdi-arrow-up-drop-circle" id="hide_remaining_details_id_{{ $i }}" onclick="hide_remaining_details_button({{ $i }})"></i>
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                        <td class="d-none"></td>
                                                        <td class="d-none"></td>
                                                    </tr>
                                                    
                                                    <tr id="remaining_tr_{{ $k }}" style="border: solid #ed722a 2px;display:none">
                                                        <td class="d-none">{{ $i }}</td>
                                                        <td>Tour Id         : <b>{{ $value->tour_id }}</b></td>
                                                        <td>Booking Id      : <b>{{ $value->booking_id }}</b></td>
                                                        <td>Paid            : <b>{{ $value->total_paid_amount }}</b></td>
                                                        <td>OverPaid        : <b>{{ $value->over_paid_amount }}</b></td>
                                                        <td>
                                                            @if($value->confirm == 1)
                                                                <span class="badge bg-success" style="font-size: 15px">Confirmed</span>
                                                            @else
                                                                <span class="badge btn-danger" style="font-size: 15px">Tentative</span>
                                                            @endif
                                                        </td>
                                                        <td class="d-none"></td>
                                                        <td class="d-none"></td>
                                                    </tr>
                                                    
                                                    <?php $i++; $j++; $k++; ?>
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
    </div>
    
    <div id="add_special_discount" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" >
                <div class="modal-header">
                    <h4 class="modal-title" id="standard-modalLabel">Add Special Discount</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <form action="{{URL::to('add_special_discount')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            
                            <div class="col-lg-12">
                                 <lable>Total Amount</lable>
                                <input type="text" value="" id="total_amount_dic" readonly class="form-control" name="invoice_no">
                                <lable class="mt-3">Add Special Discount</lable>
                                <input type="text" value="" id="" class="form-control" name="discount_am">
                                <input type="text" value="" id="invoice_no_disc" hidden class="form-control" name="invoice_no_disc">
                            </div>
                            
                            
                            <div class="row" id="" style="float:right">
                                <div class="col-lg-12 mb-3">
                                    <button class="btn btn-primary"  type="submit">Submit</button>
                                </div>
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!--Booking Passenger-->
    <div id="add-more-passenger-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" style="margin-right: 700px;">
            <div class="modal-content" style="width: 1100px;">
                <div class="modal-header">
                    <h4 class="modal-title" id="standard-modalLabel">Passengers Detail</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="mb-3">
                                <div class="row" id="leadpassenger_form" style="border: 1px solid #ebebeb;padding: 1rem;border-radius: 6px;">
                                    <h3>Lead Passenger Detail</h3>
                                    
                                    <form method="post" class="row g-3" action="{{ url('add_more_passenger_package_booking') }}">
                                        @csrf
                                        <input type="text" name="booking_Inovice_No" class="booking_Inovice_No" hidden>
                                        
                                        <div class="col-md-4">
                                            <label for="inputEmail4" class="form-label">Title</label>
                                            <select class="form-control" id="lead_title" name="lead_title" style="border-radius: unset !important;"></select>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <label for="inputEmail4" class="form-label">First name</label>
                                            <input type="text" class="form-control" id="f_name_lead" required name="name">
                                            <input type="text" name="invoice_req_from" value="leadPassenger" hidden>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <label for="inputPassword4" class="form-label">Last name</label>
                                            <input type="text" class="form-control" id="l_name_lead" required name="lname">
                                        </div>
                                        
                                        <div class="col-4">
                                            <label for="inputAddress" class="form-label">Email</label>
                                            <input type="text" class="form-control" id="lead_email" required name="email">
                                            <input type="text" name="passengerType" hidden value="adults" class="form-control">
                                        </div>
                                        
                                        <div class="col-4">
                                            <label for="inputAddress" class="form-label">Nationality</label>
                                            <input type="text" class="form-control" name="country" id="nationality_lead">
                                        </div>
                                        
                                        <div class="col-4">
                                            <label for="inputAddress" class="form-label">Date of birth</label>
                                            <input class="form-control" type="text" id="dob_lead" name="date_of_birth">
                                        </div>
                                        
                                        <div class="col-4">
                                            <label for="inputAddress" class="form-label">Passenger Type</label>
                                            <input  type="text" id="lead_type" class="form-control " required name="passengerType"/>
                                        </div>
                                        
                                        <div class="col-4">
                                            <label for="inputAddress" class="form-label">Phone</label>
                                            <input  type="text" id="lead_mobile" class="form-control otp_code" required name="phone"/> 
                                        </div>
                                        
                                        <div class="col-4">
                                            <label for="inputAddress" class="form-label">Gender</label>
                                            <select class="form-control" id="lead_gender" name="gender"></select>
                                        </div>
        
                                        <div class="col-12">
                                            <button class="btn" style="background-color:#277019; color:white;float: right;" type="submit">Update</button>
                                        </div>
                                    </form> 
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-12">
                            <h5>Lead Passenger Name : <span id="lead_name_ID"></span></h5>
                            <p>Maximum Pax :<span id="no_of_pax_days_Booking"></span></p>
                            <input type="hidden" value="Invoice" id="package_Type" name="package_Type">
                            <input type="hidden" value="" id="no_of_pax_days_Input" name="no_of_pax_days_Input">
                            <input type="hidden" value="" id="invoice_Id_Input" name="invoice_Id_Input">
                            <input type="hidden" value="" id="count_P_Input" name="count_P_Input">
                            <input type="hidden" value="" id="count_P_Input1">
                            <input type="hidden" value="" id="more_Passenger_Data_Input" name="more_Passenger_Data_Input">
                        </div>
             
                        <div class="col-lg-4">
                            <div class="mb-3 text-last" id="add_more_pass_btn_A" style="display:none">
                                <button class="btn btn-primary" onclick="add_new_passenger_A()" type="button">Add more Adults</button>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="mb-3 text-last" id="add_more_pass_btn_C" style="display:none">
                                <button class="btn btn-primary" onclick="add_new_passenger_C()" type="button">Add more Childs</button>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="mb-3 text-last" id="add_more_pass_btn_I" style="display:none">
                                <button class="btn btn-primary" onclick="add_new_passenger_I()" type="button">Add more Infants</button>
                            </div>
                        </div>
                        
                        <div class="col-lg-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Sr</th>
                                        <th scope="col">First Name</th>
                                        <th scope="col">Last Name</th>
                                        <th scope="col">Gender</th>
                                        <th scope="col">Nationality</th>
                                        <th scope="col">Date of birth</th>
                                        <th scope="col">Passport Number</th>
                                        <th scope="col">passport Expiry	</th>
                                        <th scope="col">Passenger Type</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="otherPassenger"></tbody>
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
    
    <div class="modal fade" id="exampleModalAddMore_A" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add More Adults</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ url('add_more_passenger_package_booking') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            
                            <input type="text" name="booking_Inovice_No" class="booking_Inovice_No" hidden>
                            
                            <div class="col-md-6">
                                <label for="inputEmail4" class="form-label">First name</label>
                                <input type="text" class="form-control" required  name="passengerName">
                                <input type="text" name="invoice_req_from" value="AddMPassenger_A" hidden>
                                <!--<input type="text" name="invoice_id" hidden class="inovice_id">-->
                            </div>
                              
                            <div class="col-md-6">
                                <label for="inputPassword4" class="form-label">Last name</label>
                                <input type="text" class="form-control" required name="lname">
                            </div>
                                  
                            <div class="col-6">
                                <label for="inputAddress" class="form-label">Nationality</label>
                                <input type="text" name="country" class="form-control">
                            </div>
                            
                            <div class="col-6">
                                <label for="inputAddress" class="form-label">Date of birth</label>
                                <input  type="text" name="date_of_birth" class="form-control " required /> 
                            </div>
                            
                            <div class="col-6">
                                <label for="inputAddress" class="form-label">Passenger Type</label>
                                <input  type="text" id="lead_type" class="form-control " required name="passengerType"/>
                            </div>
                                 
                            <div class="col-6">
                                <label for="inputAddress" class="form-label">Passport Number</label>
                                <input  type="text" name="passport_lead" class="form-control otp_code" required /> 
                            </div>
                            
                            <div class="col-6">
                                <label for="inputAddress" class="form-label">Passport Expiry</label>
                                <input  type="text" name="passport_exp_lead" class="form-control " required /> 
                            </div>
                            
                            <div class="col-6">
                                <label for="inputAddress" class="form-label">Gender</label>
                                <select class="form-control" name="gender">
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
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
    
    <div class="modal fade" id="exampleModalAddMore_C" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add More Childs</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ url('add_more_passenger_package_booking') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            
                            <input type="text" name="booking_Inovice_No" class="booking_Inovice_No" hidden>
                            
                            <div class="col-md-6">
                                <label for="inputEmail4" class="form-label">First name</label>
                                <input type="text" class="form-control" required  name="passengerName">
                                <input type="text" name="invoice_req_from" value="AddMPassenger_C" hidden>
                                <!--<input type="text" name="invoice_id" hidden class="inovice_id">-->
                            </div>
                              
                            <div class="col-md-6">
                                <label for="inputPassword4" class="form-label">Last name</label>
                                <input type="text" class="form-control" required name="lname">
                            </div>
                                  
                            <div class="col-6">
                                <label for="inputAddress" class="form-label">Nationality</label>
                                <input type="text" name="country" class="form-control">
                            </div>
                            
                            <div class="col-6">
                                <label for="inputAddress" class="form-label">Date of birth</label>
                                <input  type="text" name="date_of_birth" class="form-control " required /> 
                            </div>
                            
                            <div class="col-6">
                                <label for="inputAddress" class="form-label">Passenger Type</label>
                                <input  type="text" id="lead_type" class="form-control " required name="passengerType"/>
                            </div>
                                 
                            <div class="col-6">
                                <label for="inputAddress" class="form-label">Passport Number</label>
                                <input  type="text" name="passport_lead" class="form-control otp_code" required /> 
                            </div>
                            
                            <div class="col-6">
                                <label for="inputAddress" class="form-label">Passport Expiry</label>
                                <input  type="text" name="passport_exp_lead" class="form-control " required /> 
                            </div>
                            
                            <div class="col-6">
                                <label for="inputAddress" class="form-label">Gender</label>
                                <select class="form-control" name="gender">
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
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
    
    <div class="modal fade" id="exampleModalAddMore_I" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add More Childs</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ url('add_more_passenger_package_booking') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            
                            <input type="text" name="booking_Inovice_No" class="booking_Inovice_No" hidden>
                            
                            <div class="col-md-6">
                                <label for="inputEmail4" class="form-label">First name</label>
                                <input type="text" class="form-control" required  name="passengerName">
                                <input type="text" name="invoice_req_from" value="AddMPassenger_I" hidden>
                                <!--<input type="text" name="invoice_id" hidden class="inovice_id">-->
                            </div>
                              
                            <div class="col-md-6">
                                <label for="inputPassword4" class="form-label">Last name</label>
                                <input type="text" class="form-control" required name="lname">
                            </div>
                                  
                            <div class="col-6">
                                <label for="inputAddress" class="form-label">Nationality</label>
                                <input type="text" name="country" class="form-control">
                            </div>
                            
                            <div class="col-6">
                                <label for="inputAddress" class="form-label">Date of birth</label>
                                <input  type="text" name="date_of_birth" class="form-control " required /> 
                            </div>
                            
                            <div class="col-6">
                                <label for="inputAddress" class="form-label">Passenger Type</label>
                                <input  type="text" id="lead_type" class="form-control " required name="passengerType"/>
                            </div>
                                 
                            <div class="col-6">
                                <label for="inputAddress" class="form-label">Passport Number</label>
                                <input  type="text" name="passport_lead" class="form-control otp_code" required /> 
                            </div>
                            
                            <div class="col-6">
                                <label for="inputAddress" class="form-label">Passport Expiry</label>
                                <input  type="text" name="passport_exp_lead" class="form-control " required /> 
                            </div>
                            
                            <div class="col-6">
                                <label for="inputAddress" class="form-label">Gender</label>
                                <select class="form-control" name="gender">
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
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
    
    <div class="modal fade" id="exampleModalAddAdultEdit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Adult</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ url('add_more_passenger_package_booking') }}" method="post">
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            
                            <input type="text" name="booking_Inovice_No" class="booking_Inovice_No" hidden>
                            
                            <div class="col-md-6">
                                <label for="inputEmail4" class="form-label">First name</label>
                                <input type="text" class="form-control" id="A_f_name_edit" required name="passengerName">
                                <input type="text" name="invoice_req_from" hidden value="updateAdultPassenger">
                                <!--<input type="text" name="invoice_id" hidden class="inovice_id">-->
                                <input type="text" name="index" hidden id="passIndexAdult">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="inputPassword4" class="form-label">Last name</label>
                                <input type="text" class="form-control" id="A_l_name_edit" required name="lname">
                            </div>
                            
                            <div class="col-6">
                                <label for="inputAddress" class="form-label">Nationality</label>
                                <input type="text" name="country" id="A_nationality_other_edit" class="form-control">
                            </div>
                              
                            <div class="col-6">
                                <label for="inputAddress" class="form-label">Date of birth</label>
                                <input  type="text" id="A_date_of_birth_other_edit" name="date_of_birth" class="form-control " required /> 
                            </div>
                             
                            <div class="col-6">
                                <label for="inputAddress" class="form-label">Passport Number</label>
                                <input  type="text" id="A_passport_lead_other_edit" name="passport_lead" class="form-control otp_code" required /> 
                            </div>
                            
                            <div class="col-6">
                                <label for="inputAddress" class="form-label">Passport Expiry</label>
                                <input  type="text" id="A_passport_exp_lead_other_edit" name="passport_exp_lead" class="form-control" required /> 
                            </div>
                           
                            <div class="col-6">
                                <label for="inputAddress" class="form-label">Gender</label>
                                <select class="form-control" id="A_more_p_gender" name="gender"></select>
                            </div>
                            
                            <div class="col-6">
                                <label for="inputAddress" class="form-label">Passenger Type</label>
                                <input class="form-control" type="text" id="A_more_p_passengerType" name="passengerType" required/>
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
    
    <div class="modal fade" id="exampleModalAddChildEdit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Child</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ url('add_more_passenger_package_booking') }}" method="post">
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            
                            <input type="text" name="booking_Inovice_No" class="booking_Inovice_No" hidden>
                            
                            <div class="col-md-6">
                                <label for="inputEmail4" class="form-label">First name</label>
                                <input type="text" class="form-control" id="C_f_name_edit" required name="passengerName">
                                <input type="text" name="invoice_req_from" hidden value="updateChildPassenger">
                                <!--<input type="text" name="invoice_id" hidden class="inovice_id">-->
                                <input type="text" name="index" hidden id="passIndexChild">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="inputPassword4" class="form-label">Last name</label>
                                <input type="text" class="form-control" id="C_l_name_edit" required name="lname">
                            </div>
                            
                            <div class="col-6">
                                <label for="inputAddress" class="form-label">Nationality</label>
                                <input type="text" name="country" id="C_nationality_other_edit" class="form-control">
                            </div>
                              
                            <div class="col-6">
                                <label for="inputAddress" class="form-label">Date of birth</label>
                                <input  type="text" id="C_date_of_birth_other_edit" name="date_of_birth" class="form-control " required /> 
                            </div>
                             
                            <div class="col-6">
                                <label for="inputAddress" class="form-label">Passport Number</label>
                                <input  type="text" id="C_passport_lead_other_edit" name="passport_lead" class="form-control otp_code" required /> 
                            </div>
                            
                            <div class="col-6">
                                <label for="inputAddress" class="form-label">Passport Expiry</label>
                                <input  type="text" id="C_passport_exp_lead_other_edit" name="passport_exp_lead" class="form-control" required /> 
                            </div>
                           
                            <div class="col-6">
                                <label for="inputAddress" class="form-label">Gender</label>
                                <select class="form-control" id="C_more_p_gender" name="gender"></select>
                            </div>
                            
                            <div class="col-6">
                                <label for="inputAddress" class="form-label">Passenger Type</label>
                                <input class="form-control" type="text" id="C_more_p_passengerType" name="passengerType" required/>
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
    
    <div class="modal fade" id="exampleModalAddInfantEdit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Infant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ url('add_more_passenger_package_booking') }}" method="post">
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            
                            <input type="text" name="booking_Inovice_No" class="booking_Inovice_No" hidden>
                            
                            <div class="col-md-6">
                                <label for="inputEmail4" class="form-label">First name</label>
                                <input type="text" class="form-control" id="I_f_name_edit" required name="passengerName">
                                <input type="text" name="invoice_req_from" hidden value="updateInfantPassenger">
                                <!--<input type="text" name="invoice_id" hidden class="inovice_id">-->
                                <input type="text" name="index" hidden id="passIndexInfant">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="inputPassword4" class="form-label">Last name</label>
                                <input type="text" class="form-control" id="I_l_name_edit" required name="lname">
                            </div>
                            
                            <div class="col-6">
                                <label for="inputAddress" class="form-label">Nationality</label>
                                <input type="text" name="country" id="I_nationality_other_edit" class="form-control">
                            </div>
                              
                            <div class="col-6">
                                <label for="inputAddress" class="form-label">Date of birth</label>
                                <input  type="text" id="I_date_of_birth_other_edit" name="date_of_birth" class="form-control " required /> 
                            </div>
                             
                            <div class="col-6">
                                <label for="inputAddress" class="form-label">Passport Number</label>
                                <input  type="text" id="I_passport_lead_other_edit" name="passport_lead" class="form-control otp_code" required /> 
                            </div>
                            
                            <div class="col-6">
                                <label for="inputAddress" class="form-label">Passport Expiry</label>
                                <input  type="text" id="I_passport_exp_lead_other_edit" name="passport_exp_lead" class="form-control" required /> 
                            </div>
                           
                            <div class="col-6">
                                <label for="inputAddress" class="form-label">Gender</label>
                                <select class="form-control" id="I_more_p_gender" name="gender"></select>
                            </div>
                            
                            <div class="col-6">
                                <label for="inputAddress" class="form-label">Passenger Type</label>
                                <input class="form-control" type="text" id="I_more_p_passengerType" name="passengerType" required/>
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
    <!--End-->

@endsection

@section('scripts')

    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>-->
    
    <script>
        $(document).ready( function () {
            // $('#example_11').DataTable({
            //     "bPaginate": true,
            //     "scrollX": true,
            // });
            
            $('#my_Table').DataTable({
                pagingType: 'full_numbers',
            });
        });
        
        function view_remaining_details_button(id){
            $('#remaining_tr_'+id+'').css('display','');
            $('#view_remaining_details_id_'+id+'').css('display','none');
            $('#hide_remaining_details_id_'+id+'').css('display','');
        }
        
        function hide_remaining_details_button(id){
            $('#remaining_tr_'+id+'').css('display','none');
            $('#view_remaining_details_id_'+id+'').css('display','');
            $('#hide_remaining_details_id_'+id+'').css('display','none');
        }
        
    </script>
    
    <script>
        $("#alert_hide").fadeOut(7000);
    </script>
    
    <script>    
    
        function add_special_discount(id,totalAm){
            $('#add_special_discount').modal('show');
            $('#invoice_no_disc').val(id);
            $('#total_amount_dic').val(totalAm);
        }
        
        function view_outS(id){
            const ids = $('#view_outSid_'+id+'').attr('data-id');
            $('#view_outS_'+id+'').empty();
            
            $.ajax({
                url: 'view_more_bookings/'+ids,
                type: 'GET',
                data: {
                    "id": ids
                },
                success:function(data) {
                    var booked_tour_payments_details    = data['booked_tour_payments_details'];
                    var amount_paid                     = parseInt(data['amount_paid']);
                    var recieved_amount                 = parseInt(data['recieved_amount']);
                    var remaining_amount                = parseInt(data['remaining_amount']);
                    var total_amount                    = data['total_amount'];
                    
                    if(total_amount != null && total_amount != ''){
                        var total_amountF               = parseInt(total_amount['total_amount']);
                        var out_S = parseFloat(total_amountF) - parseFloat(amount_paid);
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
        
        $(document).ready(function () {
            $('#my_Table1 .P_Id').each(function() {
                var P_Id = $(this).html();
                
                $.ajax({
                    url: 'view_ternary_bookings_tourId/'+P_Id,
                    type: 'GET',
                    data: {
                        "P_Id": P_Id
                    },
                    success:function(data) {
                        var a           = data['a'];
                        var recieved    = parseInt(data['recieved']);
                        var price       = parseInt(data['price']);
                        // var confirm     = a['confirm'];
                        // console.log(data);
                        // console.log('confirm : '+confirm);
                        // if(confirm != 1){
                            // $('#tr_pink_'+P_Id+'').css('background-color','#ffe6e9');
                        // }
                        
                        if(price == recieved){
                            $(".confirmed_div_dropdown"+P_Id+'').empty();
                            var url = 'confirmed_tour_booking/'+P_Id;
                            var data = `<a href="${url}" class="dropdown-item"><i class="mdi mdi-check-circle"></i>Confirm Booking</a>`
                            $(".confirmed_div_dropdown"+P_Id+'').html(data);
                            $(".payment_status"+P_Id+'').empty();
                            var data = `<a style="color: Green;">PAID</a><br>
                                        <span style="font-size: 15px;" class="badge bg-info" id="view_outSid_${P_Id}" data-id="${P_Id}" onclick="view_outS(${P_Id})">View Outstanding</span>`;
                            $(".payment_status"+P_Id+'').html(data);
                        }
                        else if(recieved > 0 && recieved < price){
                            $(".confirmed_div_dropdown"+P_Id+'').empty();
                            var url = 'confirmed_tour_booking/'+P_Id;
                            var data = `<a href="${url}" class="dropdown-item"><i class="mdi mdi-check-circle"></i>Confirm Booking</a>`
                            $(".confirmed_div_dropdown"+P_Id+'').html(data);
                            $(".payment_status"+P_Id+'').empty();
                            var data = `<a style="color: rebeccapurple;">PARTIALLY PAID</a><br>
                                        <span style="font-size: 15px;" class="badge bg-info" id="view_outSid_${P_Id}" data-id="${P_Id}" onclick="view_outS(${P_Id})">View Outstanding</span>`;
                            $(".payment_status"+P_Id+'').html(data);
                        }
                        else{
                            $(".payment_status"+P_Id+'').empty();
                            var data = `<a style="color: orange;">UNPAID</a><br>
                                        <span style="font-size: 15px;" class="badge bg-info" id="view_outSid_${P_Id}" data-id="${P_Id}" onclick="view_outS(${P_Id})">View Outstanding</span>`;
                            $(".payment_status"+P_Id+'').html(data);
                        }
                    }
                });
            });
        });
    </script>
    
    <script>
        $(document).ready(function(){
            $("#myInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#myTable1 tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
    
    <script>
    
        function add_more_passenger(id){
            $('#add_more_P_div').empty();
            $('#invoice_Id_Input').val();
            $('#lead_name_ID').empty();
            $('#no_of_pax_days_ID').empty();
            $('#no_of_pax_days_Input').val();
            $('#add_more_P').css('display','');
            $('#more_P_D_div').empty();
            let I_id = id;
            $.ajax({
                url:"{{URL::to('get_single_Invoice')}}" + '/' + I_id,
                method: "GET",
                data: {
                	I_id:I_id
                },
                success:function(data){
                    var data1                   = data['data'];
                    var invoice_Id              = data1['id'];
                    var f_name                  = data1['f_name'];
                    var middle_name             = data1['middle_name'];
                    var no_of_pax_days          = data1['no_of_pax_days'];
                    $('#invoice_Id_Input').val(invoice_Id);         	  
                    $('#lead_name_ID').html(f_name+' '+middle_name);
                    $('#no_of_pax_days_ID').html(no_of_pax_days);
                    $('#no_of_pax_days_Input').val(no_of_pax_days);
                    
                    var count_P_Input = data1['count_P_Input'];
                    if(count_P_Input > 0 && count_P_Input != null && count_P_Input != ''){
                        $('#count_P_Input').val(count_P_Input);
                        $('#count_P_Input1').val(count_P_Input);
                    }else{
                        $('#count_P_Input').val(1);
                        $('#count_P_Input1').val(1);
                    }
                    
                    var more_Passenger_Data = data1['more_Passenger_Data'];
                    if(more_Passenger_Data != null && more_Passenger_Data != ''){
                        $('#more_Passenger_Data_Input').val(more_Passenger_Data);
                        var edit_M_P_Button = `<button class="btn btn-primary" id="edit_more_P" onclick="edit_more_P()" type="button">Edit more Passenger</button>`;
                        // $('#edit_M_P_Button').append(edit_M_P_Button);
                        
                        var more_Passenger_Data_decode = JSON.parse(data1['more_Passenger_Data']);
                        $.each(more_Passenger_Data_decode, function (key, value) {
                            var more_p_fname    = value.more_p_fname;
                            var more_p_lname    = value.more_p_lname;
                            var more_p_gender   = value.more_p_gender;
                            var more_p_passport = value.more_p_passport;
                            var more_p_image    = value.more_p_image;
                            var url_IandP       = `https://alhijaztours.net/public/uploads/package_imgs/`;
                            
                            var data =  `<div class="row" id="main_div_P_${invoice_Id}">
                                            <div class="col-lg-12">
                                                <h5>More Passenger details</h5>
                                            </div>
                                            <div class="col-lg-2 mb-3">
                                                <label for="label" class="form-label">First Name</label>
                                                <input class="form-control remove_readonly_prop" type="text" value="${more_p_fname}" name="" placeholder="" readonly>
                                            </div>
                                            <div class="col-lg-2 mb-3">
                                                <label for="label" class="form-label">Last Name</label>
                                                <input class="form-control remove_readonly_prop" type="text" value="${more_p_fname}" name="" placeholder="" readonly>
                                            </div>
                                            <div class="col-lg-2 mb-3">
                                                <label for="label" class="form-label">Gender</label>
                                                <select class="form-control" name="" readonly>`;
                                                    if(more_p_gender == 'Male'){
                            data +=                     `<option value="Male" selected>Male</option>
                                                        <option value="Female">Female</option>`;
                                                    }
                                                    else if(more_p_gender == 'Female'){
                            data +=                     `<option value="Male">Male</option>
                                                        <option value="Female" selected>Female</option>`;
                                                    }
                                                    else{
                            data +=                     `<option value="">Choose...</option>
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>`;
                                                    }
                            data +=             `</select>
                                            </div>
                                            <div class="col-lg-3 mb-3">
                                                <label for="image" class="form-label">Passport</label>
                                                <input class="form-control" type="text" value="${more_p_passport}" name="" readonly>
                                                <input class="form-control" type="file" value="${more_p_passport}" name="" hidden>
                                            </div>
                                            <div class="col-lg-3 mb-3">
                                                <label for="image" class="form-label">Image</label>
                                                <img src="${url_IandP}${more_p_image}" style="height: 80px;width: 80px;">
                                                <input class="form-control" type="text" name="" readonly hidden>
                                                <input class="form-control" type="file" name="" readonly hidden>
                                            </div>
                                        </div>`;
                            
                            if(more_p_fname != null){
                                $('#more_P_D_div').append(data);
                            }
                        });
                    }
                }
            }); 
        }
        
        $('#add_more_P').click(function() {
            $('#button_P').css('display','');
            
            // console.log('count_P : '+count_P);
            
            var count_P = $('#count_P_Input').val();
            count_P     = parseFloat(count_P) + 1;
            console.log('count_P : '+count_P);
            
            var no_of_pax_days_Input    = $('#no_of_pax_days_Input').val();
            var id_P                    = $('#invoice_Id_Input').val();
            var data =  `<div class="row" id="main_div_P_${id_P}${count_P}">
                            <div class="col-lg-12">
                                <h5>More Passenger # ${count_P}</h5>
                            </div>
                            <div class="col-lg-2 mb-3">
                                <label for="label" class="form-label">First Name</label>
                                <input class="form-control" type="text" id="more_p_fname${id_P}_${count_P}" name="more_p_fname[]" placeholder="" required>
                            </div>
                            <div class="col-lg-2 mb-3">
                                <label for="label" class="form-label">Last Name</label>
                                <input class="form-control" type="text" id="more_p_lname${id_P}_${count_P}" name="more_p_lname[]" placeholder="" required>
                            </div>
                            <div class="col-lg-2 mb-3">
                                <label for="label" class="form-label">Gender</label>
                                <select class="form-control" id="more_p_gender${id_P}_${count_P}" name="more_p_gender[]" placeholder="Choose..." required>
                                    <option value="">Select</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <label for="image" class="form-label">Passport</label>
                                <input class="form-control" type="file" id="more_p_passport${id_P}_${count_P}" name="more_p_passport[]" placeholder="" required>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input class="form-control" type="file" id="more_p_image${id_P}_${count_P}" name="more_p_image[]" placeholder="" required>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary" id="remove_more_P" onclick="removeMorePassenger(${id_P}${count_P})" type="button">Remove</button>
                            </div>
                        </div>`;
                        
            if(count_P <= no_of_pax_days_Input){
                $('#add_more_P_div').append(data);
                $('#count_P_Input').val(count_P);
                // count_P = count_P + 1;
                // console.log('count_P_if : '+count_P);
            }else{
                $('#add_more_P').css('display','none');
                count_P = count_P - 1;
                // console.log('count_P_else : '+count_P);
                $('#count_P_Input').val(count_P);
                // count_P = no_of_pax_days_Input;
            }
        });
        
        function removeMorePassenger(id){
            $('#main_div_P_'+id+'').remove();
            var no_of_pax_days_Input    = $('#no_of_pax_days_Input').val();
            var count_P                 = $('#count_P_Input').val();
            var count_P_Input1          = $('#count_P_Input1').val();
            count_P                     = count_P - 1;
            $('#count_P_Input').val(count_P);
            if(count_P <= no_of_pax_days_Input){
                $('#add_more_P').css('display','');
            }
            
            if(count_P_Input = count_P_Input1){
                $('#button_P').css('display','none');
            }
        }
        
        // function edit_more_P(){
        //     $('.remove_readonly_prop').removeAttr("readonly");
        // }
        
    </script>
    
    <script>
        function add_more_booking_passenger(id){
            $('#invoice_Id_Input').val(id);
            $('.booking_Inovice_No').val(id);
            $('#lead_name_ID').empty();
            $('#otherPassenger').empty();
            $('#no_of_pax_days_Booking').empty();
            
            // Lead Data
            $('#lead_title').empty();
            $('#f_name_lead').empty();
            $('#l_name_lead').empty();
            $('#lead_email').empty();
            $('#dob_lead').empty();
            $('#nationality_lead').empty();
            $('#lead_type').empty();
            $('#lead_mobile').empty();
            $('#lead_gender').empty();
            
            var aa = 1;
            $.ajax({
                url:"{{URL::to('booking_details_single')}}" + '/' + id,
                method: "GET",
                data: {
                	id:id,
                },
                success:function(data){
                    var booking_Details  = data['data'];
                    
                    if(booking_Details != null && booking_Details != ''){
                        $.each(booking_Details, function (key, value) {
                            var passenger_detail_E  = value.passenger_detail;
                            var adults_detail_E     = value.adults_detail;
                            var child_detail_E      = value.child_detail;
                            var infant_details_E    = value.infant_details;
                            
                            var adult_Count  = 0;
                            var child_Count  = 0;
                            var infant_Count = 0;
                            
                            if(passenger_detail_E != null && passenger_detail_E != ''){
                                var passenger_detail = JSON.parse(passenger_detail_E);
                                $.each(passenger_detail, function (key, passenger_details) {
                                    var lead_title          = passenger_details.lead_title
                                    var f_name_lead         = passenger_details.name
                                    var l_name_lead         = passenger_details.lname
                                    var lead_email          = passenger_details.email
                                    var dob_lead            = passenger_details.date_of_birth
                                    var nationality_lead    = passenger_details.country
                                    var lead_type           = passenger_details.passengerType
                                    var lead_mobile         = passenger_details.phone
                                    var lead_gender         = passenger_details.gender
                                    $('#lead_name_ID').html(f_name_lead+' '+l_name_lead);
                                    if(lead_title == 'Mr.'){
                                        var title_data =    `<option value="">Select Title</option>
                                                            <option value="Mr." id="mr" selected>Mr.</option>
                                                            <option value="Mrs." id="mrs">Mrs.</option>`;
                                        $('#lead_title').append(title_data);
                                    }
                                    else{
                                        var title_data =    `<option value="">Select Title</option>
                                                            <option value="Mr." id="mr">Mr.</option>
                                                            <option value="Mrs." id="mrs" selected>Mrs.</option>`;
                                        $('#lead_title').append(title_data);
                                    }
                                    $('#f_name_lead').val(f_name_lead);
                                    $('#l_name_lead').val(l_name_lead);  
                                    $('#lead_email').val(lead_email);
                                    $('#dob_lead').val(dob_lead);
                                    $('#nationality_lead').val(nationality_lead);
                                    $('#lead_type').val(lead_type);  
                                    $('#lead_mobile').val(lead_mobile);  
                                    $('#f_name_lead').val(f_name_lead); 
                                    if(lead_gender == 'male'){
                                        gender_data =  `<option value="male" selected>Male</option>
                                                        <option value="female">Female</option>`;
                                        $('#lead_gender').append(gender_data);
                                    }
                                    else{
                                        gender_data =  `<option value="male">Male</option>
                                                        <option value="female" selected>Female</option>`;
                                        $('#lead_gender').append(gender_data);
                                    }
                                    
                                    adult_Count = parseFloat(adult_Count) + 1;
                                });
                            }
                            
                            if(adults_detail_E != null && adults_detail_E != ''){
                                var adults_detail   = JSON.parse(adults_detail_E);
                                var adult_data      = ``;
                                var x               = 1;
                                var a               = 0;
                                $.each(adults_detail, function (key, adults_details) {
                                    var passenerObj = JSON.stringify(adults_details);
                                    adult_data +=`<tr>
                                                    <td>${x++}</td>
                                                    <td>${adults_details.passengerName}</td>
                                                    <td>${adults_details.lname}</td>
                                                    <td>${adults_details.gender}</td>
                                                    <td>${adults_details.country}</td>
                                                    <td>${adults_details.date_of_birth}</td>
                                                    <td>${adults_details.passport_lead}</td>
                                                    <td>${adults_details.passport_exp_lead}</td>
                                                    <td>${adults_details.passengerType}</td>
                                                    <td>
                                                        <input type="text" id="pass_A_${a}" hidden value='${passenerObj}'>
                                                        <button class="btn btn-success btn-sm" onclick="updateAdultPassenger(${a})">Edit</button>
                                                    </td>
                                                </tr>`;
                                    a++;
                                    adult_Count = parseFloat(adult_Count) + 1;
                                });
                                $('#otherPassenger').append(adult_data);
                            }
                            
                            if(child_detail_E != null && child_detail_E != ''){
                                var child_detail = JSON.parse(child_detail_E);
                                if(child_detail != null && child_detail != ''){
                                    var child_data      = ``;
                                    var c               = 0;
                                    $.each(child_detail, function (key, child_details) {
                                        var passenerObj = JSON.stringify(child_details);
                                        child_data +=`<tr>
                                                        <td>${x++}</td>
                                                        <td>${child_details.passengerName}</td>
                                                        <td>${child_details.lname}</td>
                                                        <td>${child_details.gender}</td>
                                                        <td>${child_details.country}</td>
                                                        <td>${child_details.date_of_birth}</td>
                                                        <td>${child_details.passport_lead}</td>
                                                        <td>${child_details.passport_exp_lead}</td>
                                                        <td>${child_details.passengerType}</td>
                                                        <td>
                                                            <input type="text" id="pass_C_${c}" hidden value='${passenerObj}'>
                                                            <button class="btn btn-success btn-sm" onclick="updateChildPassenger(${c})">Edit</button>
                                                        </td>
                                                    </tr>`;
                                        c++;
                                        child_Count = parseFloat(child_Count) + 1;
                                    });
                                }
                                $('#otherPassenger').append(child_data);
                            }
                            
                            if(infant_details_E != null && infant_details_E != ''){
                                var infant_details = JSON.parse(infant_details_E);
                                if(infant_details != null && infant_details != ''){
                                    var infant_data      = ``;
                                    var iN               = 0;
                                    $.each(infant_details, function (key, infant_detail) {
                                        var passenerObj = JSON.stringify(infant_detail);
                                        infant_data +=`<tr>
                                                        <td>${x++}</td>
                                                        <td>${infant_detail.passengerName}</td>
                                                        <td>${infant_detail.lname}</td>
                                                        <td>${infant_detail.gender}</td>
                                                        <td>${infant_detail.country}</td>
                                                        <td>${infant_detail.date_of_birth}</td>
                                                        <td>${infant_detail.passport_lead}</td>
                                                        <td>${infant_detail.passport_exp_lead}</td>
                                                        <td>${infant_detail.passengerType}</td>
                                                        <td>
                                                            <input type="text" id="pass_I_${iN}" hidden value='${passenerObj}'>
                                                            <button class="btn btn-success btn-sm" onclick="updateInfantPassenger(${iN})">Edit</button>
                                                        </td>
                                                    </tr>`;
                                        iN++;
                                        infant_Count = parseFloat(infant_Count) + 1;
                                    });
                                }
                                $('#otherPassenger').append(infant_data);
                            }
                            
                            // Add More
                            var cart_total_data_E     = value.cart_total_data;
                            if(cart_total_data_E != null && cart_total_data_E != ''){
                                var cart_total_data = JSON.parse(cart_total_data_E);
                                console.log(cart_total_data);
                                
                                var total_adults    = cart_total_data.total_adults;
                                if(total_adults != adult_Count){
                                    $('#add_more_pass_btn_A').css('display','');
                                }else{
                                    $('#add_more_pass_btn_A').css('display','none');
                                }
                                
                                var total_childs    = cart_total_data.total_childs;
                                if(total_childs != child_Count){
                                    $('#add_more_pass_btn_C').css('display','');
                                }else{
                                    $('#add_more_pass_btn_C').css('display','none');
                                }
                                
                                var total_Infants   = cart_total_data.total_Infants;
                                if(total_Infants != infant_Count){
                                    $('#add_more_pass_btn_I').css('display','');
                                }else{
                                    $('#add_more_pass_btn_I').css('display','none');
                                }
                                
                                console.log('total_childs : '+total_childs);
                                console.log('child_Count : '+child_Count);
                                
                                var max_Pax = parseFloat(total_adults) + parseFloat(total_childs) + parseFloat(total_Infants);
                                $('#no_of_pax_days_Booking').html(max_Pax);
                            }
                            
                        });
                    }else{
                        alert('Booking Details Not Found');
                    }
                }
            }); 
        }
        
        function add_new_passenger_A(){
            $('#exampleModalAddMore_A').modal('show');
        }
        
        function add_new_passenger_C(){
            $('#exampleModalAddMore_C').modal('show');
        }
        
        function add_new_passenger_I(){
            $('#exampleModalAddMore_I').modal('show');
        }
        
        function updateAdultPassenger(index){
            var passengerObject = $('#pass_A_'+index+'').val();
            var passengerObject = JSON.parse(passengerObject);
            console.log(passengerObject);
            $('#exampleModalAddAdultEdit').modal('show');
            $('#A_f_name_edit').val(passengerObject['passengerName']);
            $('#A_l_name_edit').val(passengerObject['lname']);
            $('#A_nationality_other_edit').val(passengerObject['country']);
            $('#A_date_of_birth_other_edit').val(passengerObject['date_of_birth']);
            $('#A_passport_lead_other_edit').val(passengerObject['passport_lead']);
            $('#A_passport_exp_lead_other_edit').val(passengerObject['passport_exp_lead']);
            $('#A_more_p_passengerType').val(passengerObject['passengerType']);
            $('#passIndexAdult').val(index);
            if(passengerObject['gender'] == 'male'){
                gender_data =  `<option value="male" selected>Male</option>
                                <option value="female">Female</option>`;
                $('#A_more_p_gender').append(gender_data);
            }
            else{
                gender_data =  `<option value="male">Male</option>
                                <option value="female" selected>Female</option>`;
                $('#A_more_p_gender').append(gender_data);
            } 
        }
        
        function updateChildPassenger(index){
            var passengerObject = $('#pass_C_'+index+'').val();
            var passengerObject = JSON.parse(passengerObject);
            console.log(passengerObject);
            $('#exampleModalAddChildEdit').modal('show');
            $('#C_f_name_edit').val(passengerObject['passengerName']);
            $('#C_l_name_edit').val(passengerObject['lname']);
            $('#C_nationality_other_edit').val(passengerObject['country']);
            $('#C_date_of_birth_other_edit').val(passengerObject['date_of_birth']);
            $('#C_passport_lead_other_edit').val(passengerObject['passport_lead']);
            $('#C_passport_exp_lead_other_edit').val(passengerObject['passport_exp_lead']);
            $('#C_more_p_passengerType').val(passengerObject['passengerType']);
            $('#passIndexChild').val(index);
            if(passengerObject['gender'] == 'male'){
                gender_data =  `<option value="male" selected>Male</option>
                                <option value="female">Female</option>`;
                $('#C_more_p_gender').append(gender_data);
            }
            else{
                gender_data =  `<option value="male">Male</option>
                                <option value="female" selected>Female</option>`;
                $('#C_more_p_gender').append(gender_data);
            } 
        }
        
        function updateInfantPassenger(index){
            var passengerObject = $('#pass_I_'+index+'').val();
            var passengerObject = JSON.parse(passengerObject);
            console.log(passengerObject);
            $('#exampleModalAddInfantEdit').modal('show');
            $('#I_f_name_edit').val(passengerObject['passengerName']);
            $('#I_l_name_edit').val(passengerObject['lname']);
            $('#I_nationality_other_edit').val(passengerObject['country']);
            $('#I_date_of_birth_other_edit').val(passengerObject['date_of_birth']);
            $('#I_passport_lead_other_edit').val(passengerObject['passport_lead']);
            $('#I_passport_exp_lead_other_edit').val(passengerObject['passport_exp_lead']);
            $('#I_more_p_passengerType').val(passengerObject['passengerType']);
            $('#passIndexInfant').val(index);
            if(passengerObject['gender'] == 'male'){
                gender_data =  `<option value="male" selected>Male</option>
                                <option value="female">Female</option>`;
                $('#I_more_p_gender').append(gender_data);
            }
            else{
                gender_data =  `<option value="male">Male</option>
                                <option value="female" selected>Female</option>`;
                $('#I_more_p_gender').append(gender_data);
            } 
        }
        
        
        // infant_details
    </script>

@stop

@section('slug')

@stop