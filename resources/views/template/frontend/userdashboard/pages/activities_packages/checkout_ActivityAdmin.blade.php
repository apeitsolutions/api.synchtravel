@extends('template/frontend/userdashboard/layout/default')
@section('content')

<style>
    .form-label {
    margin-bottom: 0.5rem;
    font-weight: 700;
    font-size: 15px;
    font-family: sans-serif;
}
.checkout-note{
  background-color: #277019;
  color:#fff;
 
}
.checkout-note p{
line-height: 35px;
    padding-left: 10px;
}

<?php 

// session()->forget('passengerDetail');
?>
</style>

    <section class="awe-parallax category-heading-section-demo h-300">
            <div class="awe-overlay"></div>
           
            </div>
        </section>
        
    <section class="checkout-section-demo">
        <div class="container">
            <div class="row">
                <div class="col-lg-12" style="border: 1px solid #d4d4d4;margin-bottom: 25px;">
         <div class="checkout-page__top d-none" style="margin-top: 15px;margin-bottom: 15px !important;color: #17c917f5;">
    
    
          <div class="row">
          <div class="col-md-1" style="padding-left: 22px;">
             <img style="width:30px;" src="https://alhijaztours.net/public/admin_package/frontend/images/detail_img/image_2022_09_06T19_27_06_769Z.png" class="card-img-top" alt="..."> 
          </div>
      
    
 <div class="col-md-11" style="padding-left: 0px;">
         @if(session('cart'))
                                      
                                       <?php 
                                            $cart_data_main = session('cart');
                                            $cart_data = $cart_data_main[0];
                                            
                                            if($cart_data_main[1] == 'tour'){
                                                $model_id = 'tourId';
                                            }else{
                                                $model_id = 'activtyId';
                                            }
                                            ?>
                            
                                        @foreach($cart_data as $id => $details)
                                             <p>Cancellation Policy :{{ $details['cancellation_policy'] }}</p>
                                        @endforeach
                                    @endif
        </div>
        </div>
      
              </div>
                    </div>
                
                <div class="col-lg-7">
                        <div class="checkout-page__content" style="overflow: hidden;border: 1px solid #d4d4d4;padding: 30px;">
                            
      <h6>Let us know who you are?</h6>
                             
                             
    
  
  
  
                            
                    <!-- Modal -->
                    

<!--Add child Passenger Model End-->

                         @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif
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
                                  <div class="col-md-12" id="leadpassenger_loader" style="display:none">
                                      <iframe src="https://embed.lottiefiles.com/animation/98195"></iframe>
                                  </div>
                                  
                                    <div class="col-md-12" id="leadpassenger_form" style="display:none;" @if(!session('passengerDetail')) @endif>
                                      <form method="post" class="row g-3" action="{{ url('passenger_detail') }}">
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
                                              </div>
                                              <div class="col-md-4">
                                                <label for="inputPassword4" class="form-label">Last name</label>
                                                <input type="text" class="form-control" id="l_name_lead" required value="@if(session('passengerDetail')) {{ session('passengerDetail')[0]['lname'] }} @endif" name="lname">
                                              </div>
                                              <div class="col-12">
                                                <label for="inputAddress" class="form-label">Email</label>
                                                <input type="text" class="form-control" id="inputAddress" required value="@if(session('passengerDetail')) {{ session('passengerDetail')[0]['email'] }} @endif" name="email">
                                                <input type="text" name="passengerType" hidden value="adults" class="form-control">
                                              </div>
                                              <div class="col-6">
                                                <label for="inputAddress" class="form-label">Nationality</label>
                                                <input type="text" name="country" id="nationality_lead" class="form-control" value="@if(session('passengerDetail')) {{ session('passengerDetail')[0]['country'] }} @endif">
                                              </div>
                                              <div class="col-6">
                                                <label for="inputAddress" class="form-label">Date of birth</label>
                                                <input  type="text" id="date_of_birth" name="date_of_birth" class="form-control " required value="@if(session('passengerDetail')) {{ session('passengerDetail')[0]['date_of_birth'] }} @endif"/> 
                                              </div>
                                              <div class="col-6">
                                                <label for="inputAddress" class="form-label">Phone</label>
                                                <input  type="text" id="" name="phone" class="form-control " required value="@if(session('passengerDetail')) {{ session('passengerDetail')[0]['phone'] }} @endif"/> 
                                              </div>
                                               <div class="col-6">
                                                <label for="inputAddress" class="form-label">Passport Number</label>
                                                <input  type="text" id="passport_lead" name="passport_lead" class="form-control otp_code" required value="@if(session('passengerDetail')) {{ session('passengerDetail')[0]['passport_lead'] }} @endif"/> 
                                              </div>
                                              <div class="col-6">
                                                <label for="inputAddress" class="form-label">Passport Expiry</label>
                                                <input  type="text" id="passport_exp_lead" name="passport_exp_lead" class="form-control " required value="@if(session('passengerDetail')) {{ session('passengerDetail')[0]['passport_exp_lead'] }} @endif"/> 
                                              </div>
                
                
                
                                            <p>Gender</p>
                                            <div class="col-6">
                                              <div class="form-check">
                                              <input class="form-check-input" type="radio" name="gender" value="male" @if(session('passengerDetail')) @if(session('passengerDetail')[0]['gender'] == 'male') checked @endif @else checked  @endif id="male">
                                              <label class="form-check-label" for="male">
                                                Male
                                              </label>
                                            </div>
                                            
                                            </div>
                                            <div class="col-6">
                                                <div class="form-check">
                                              <input class="form-check-input" type="radio" name="gender" value="female" @if(session('passengerDetail')) @if(session('passengerDetail')[0]['gender'] == 'female') checked @endif  @endif id="female">
                                              <label class="form-check-label" for="female">
                                                Female
                                              </label>
                                            </div>
                                            
                                            </div>
                                            
                                            <div class="col-12">
                                                    <div id="payment_gatway">
                                                                                 
                                                    </div>
                                              </div>
                
                                              <div class="col-12">
                                                     @php 
                                                 $cart_data_main = session('cart');
                                             
                                                 $cart_data = $cart_data_main[0];
                                                                        $adults = 0;
                                                                        $children = 0;
                                                                        $infants = 0;
                                                                    @endphp
                                                                @foreach($cart_data as $id => $details)
                                                             <?php
                                                                         $adults += $details['adults'];
                                                                       
                                                                       if(isset($details['double_adults']))
                                                                       {
                                                                      
                                                                         $adults += $details['double_adults'];
                                                                       
                                                                         
                                                                       }
                                                                       
                                                                         
                                                           
                                                                        
                                                                       if(isset($details['triple_adults']))
                                                                       {
                                                                    
                                                                         $adults += $details['triple_adults'];
                                                                        
                                                                         
                                                                       }
                                                                      
                                                                         
                                                                       if(isset($details['quad_adults']))
                                                                       {
                                                                      
                                                                         $adults += $details['quad_adults'];
                                                                        
                                                                         
                                                                       }
                                                                      
                                                                       
                                                                       if(isset($details['without_acc_adults']))
                                                                       {
                                                                      
                                                                         $adults += $details['without_acc_adults'];
                                                                        
                                                                         
                                                                       }
                                                                      
                                                                        
                                                                        
                                                                         
                                                                         $children += $details['children'];
                                                                         
                                                                         if(isset($details['double_childs']))
                                                                       {
                                                                      
                                                                         $children += $details['double_childs'];
                                                                        
                                                                         
                                                                       }
                                                                       
                                                                      
                                                                       if(isset($details['triple_childs']))
                                                                       {
                                                                      
                                                                         $children += $details['triple_childs'];
                                                                        
                                                                         
                                                                       }
                                                                       
                                                                       if(isset($details['quad_childs']))
                                                                       {
                                                                      
                                                                         $children += $details['quad_childs'];
                                                                        
                                                                         
                                                                       }
                                                                      
                                                                         
                                                                       if(isset($details['infants']))
                                                                       {
                                                                      
                                                                         $infants += $details['infants'];
                                                                        
                                                                         
                                                                       }
                                                                      
                                                                       
                                                                       if(isset($details['double_infant']))
                                                                       {
                                                                      
                                                                         $infants += $details['double_infant'];
                                                                        
                                                                         
                                                                       }
                                                                     
                                                                       if(isset($details['triple_infant']))
                                                                       {
                                                                      
                                                                         $infants += $details['triple_infant'];
                                                                        
                                                                         
                                                                       }
                                                                       else
                                                                      
                                                                       if(isset($details['quad_infant']))
                                                                       {
                                                                      
                                                                         $infants += $details['quad_infant'];
                                                                        
                                                                         
                                                                       }
                                                                     
                                                                         
                                                                         
                                                                         
                                                                        
                                                                         
                                                                ?>
                                                                @endforeach
                
                                                            <!-- For Adults <Calculation> -->
                                                                @if(session('adults'))
                                                                    @php 
                                                                        $saveAdults = count(session('adults'));
                                                                        $saveAdults = $saveAdults + 1;
                                                                    @endphp
                
                                                                @else
                                                                    @php 
                                                                        $saveAdults = 1;
                                                                    @endphp
                                                                @endif
                                                        <!-- For Childs Calculation -->
                                                                @if(session('Childs'))
                                                                    @php 
                                                                        $saveChilds = count(session('Childs'));
                                                                    @endphp
                
                                                                @else
                                                                    @php 
                                                                        $saveChilds = 0;
                                                                    @endphp
                                                                @endif
                                                         <!-- For Infants Calculation -->
                                                                @if(session('infants'))
                                                                    @php 
                                                                        $saveinfants = count(session('infants'));
                                                                    @endphp
                
                                                                @else
                                                                    @php 
                                                                        $saveinfants = 0;
                                                                    @endphp
                                                                @endif
                                                                
                                                                @if(session('passengerDetail'))
                                                                    @php
                                                                        $button_type = '';
                                                                    @endphp
                                                                        @if($saveAdults < $adults)
                                                                            @php  $button_type = 'adult';    @endphp
                                                                            
                                                                        @elseif($saveChilds < $children)
                                                                            @php  $button_type = 'childs';    @endphp
                                                                            
                                                                        @elseif($saveinfants < $infants)
                                                                            @php  $button_type = 'infant';    @endphp
                                                                            
                                                                        @else
                                                                            @php  $button_type = 'Checkout';    @endphp
                                                                            
                                                                        @endif
                
                                                                        @if($button_type == 'Checkout')
                                                                        
                                                                           
                                                                             
                                                                            <button type="button" style="background-color:#d2b254; color:white;" id="checkout_button_m" class="btn" data-bs-toggle="modal" data-bs-target="#ConfirmBooking">
                                                                               Confirm Booking
                                                                            </button>
                                                                        @else
                                                                            <button type="button" style="background-color:#d2b254; color:white;" class="btn" data-bs-toggle="modal" data-bs-target="#exampleModal1">
                                                                                @if($button_type == 'adult')
                                                                                    Add Adult {{ $saveAdults + 1 }} / {{ $adults }}
                                                                                @elseif($button_type == 'childs')
                                                                                    Add Child {{ $saveChilds + 1 }} / {{ $children }}
                                                                                @else
                                                                                   Add Infant {{ $saveinfants + 1 }} / {{ $infants }}
                                                                                    
                                                                                @endif
                                                                            </button>
                                                                        @endif
                                                                   
                
                                                                @else 
                                                                    <button class="btn" style="background-color:#277019; color:white;float: right;" type="submit">Next</button>
                                                                @endif
                                                                
                                                                
                                                                      
                                                             
                                              </div>
                                          
                                            </form>
                                  </div>
                                  
                                  <?php // dd(session('passengerDetail')); ?>
                                  
                                    <div class="col-md-12" id="manual_form"  @if(!session('passengerDetail'))  @endif>
                                        <form method="post" class="row g-3" action="{{ url('passenger_detail') }}">
                                                 @csrf
                                                  <div class="col-md-4">
                                                    <label for="inputEmail4" class="form-label">Title</label>
                                                    <select class="form-control" name="lead_title" style="border-radius: unset !important;">
                                                        @if(session('passengerDetail'))
                                                            @if(session('passengerDetail')[0]['lead_title'])
                                                                <?php $lead_title = session('passengerDetail')[0]['lead_title']; ?>
                                                                <option readonly value="{{ $lead_title }}" Selected>{{ $lead_title }}</option>
                                                            @else
                                                                <option value="">Select Title</option>
                                                                <option value="Mr." id="mr">Mr.</option>
                                                                <option value="Mrs." id="mrs">Mrs.</option>
                                                            @endif
                                                        @else
                                                            <option value="">Select Title</option>
                                                            <option value="Mr." id="mr">Mr.</option>
                                                            <option value="Mrs." id="mrs">Mrs.</option>
                                                        @endif
                                                    </select>
                                                  </div>
                                              <div class="col-md-4">
                                                <label for="inputEmail4" class="form-label">First name</label>
                                                    @if(session('passengerDetail'))
                                                        @if(session('passengerDetail')[0]['name'])
                                                            <input readonly type="text" class="form-control" id="f_name_lead_m" required value="@if(session('passengerDetail')) {{ session('passengerDetail')[0]['name'] }} @endif" name="name">
                                                        @else
                                                            <input type="text" class="form-control" id="f_name_lead_m" required value="@if(session('passengerDetail')) {{ session('passengerDetail')[0]['name'] }} @endif" name="name">
                                                        @endif
                                                    @else
                                                        <input type="text" class="form-control" id="f_name_lead_m" required value="@if(session('passengerDetail')) {{ session('passengerDetail')[0]['name'] }} @endif" name="name">
                                                    @endif
                                              </div>
                                              <div class="col-md-4">
                                                <label for="inputPassword4" class="form-label">Last name</label>
                                                    @if(session('passengerDetail')) 
                                                        @if(session('passengerDetail')[0]['lname'])
                                                            <input readonly type="text" class="form-control" id="l_name_lead_m" required value="@if(session('passengerDetail')) {{ session('passengerDetail')[0]['lname'] }} @endif" name="lname">
                                                        @else
                                                            <input type="text" class="form-control" id="l_name_lead_m" required value="@if(session('passengerDetail')) {{ session('passengerDetail')[0]['lname'] }} @endif" name="lname">
                                                        @endif
                                                    @else
                                                        <input type="text" class="form-control" id="l_name_lead_m" required value="@if(session('passengerDetail')) {{ session('passengerDetail')[0]['lname'] }} @endif" name="lname">
                                                    @endif
                                              </div>
                                              <div class="col-12">
                                                <label for="inputAddress" class="form-label">Email</label>
                                                    @if(session('passengerDetail')) 
                                                        @if(session('passengerDetail')[0]['email'])
                                                            <input readonly type="text" class="form-control" id="inputAddress" required value="@if(session('passengerDetail')) {{ session('passengerDetail')[0]['email'] }} @endif" name="email">
                                                        @else
                                                            <input type="text" class="form-control" id="inputAddress" required value="@if(session('passengerDetail')) {{ session('passengerDetail')[0]['email'] }} @endif" name="email">
                                                        @endif
                                                    @else
                                                        <input type="text" class="form-control" id="inputAddress" required value="@if(session('passengerDetail')) {{ session('passengerDetail')[0]['email'] }} @endif" name="email">
                                                    @endif
                                                <input type="text" name="passengerType" hidden value="adults" class="form-control">
                                              </div>
                                              <div class="col-6">
                                                <label for="inputAddress" class="form-label">Nationality</label>
                                                 <select class="form-control" required name="country" id="country" onchange="fetch_country_code()" style="border-radius: unset !important;">
                                                    @if(session('passengerDetail'))
                                                        @if(session('passengerDetail')[0]['country'])
                                                            <?php $countryN = session('passengerDetail')[0]['country']; ?>
                                                            <option value="{{ $countryN}}" Selected>{{ $countryN }}</option>
                                                        @else
                                                            <option value="" >Select Title</option>
                                                            @isset($countries)
                                                                @foreach($countries as $count_res)
                                                                    <option value="{{ $count_res->name }}" phone_code='{{ $count_res->phonecode }}'>{{ $count_res->name }}</option>
                                                                @endforeach
                                                            @endisset
                                                        @endif
                                                    @else
                                                        <option value="" >Select Title</option>
                                                        @isset($countries)
                                                            @foreach($countries as $count_res)
                                                                <option value="{{ $count_res->name }}" phone_code='{{ $count_res->phonecode }}'>{{ $count_res->name }}</option>
                                                            @endforeach
                                                        @endisset
                                                    @endif
                                                    
                                                </select>
                                              </div>
                                              <div class="col-6">
                                                <label for="inputAddress" class="form-label">Date of birth</label>
                                                    @if(session('passengerDetail'))
                                                        @if(session('passengerDetail')[0]['date_of_birth'])
                                                            <?php $date_of_birth = session('passengerDetail')[0]['date_of_birth']; ?>
                                                            <input type="text" readonly id="date_of_birth_m" name="date_of_birth" class="form-control " required value="{{ $date_of_birth }}"/>
                                                        @else
                                                            <input type="date" id="date_of_birth_m" name="date_of_birth" class="form-control " required value="@if(session('passengerDetail')) {{ session('passengerDetail')[0]['date_of_birth'] }} @endif"/> 
                                                        @endif                                               
                                                    @else
                                                        <input type="date" id="date_of_birth_m" name="date_of_birth" class="form-control " required value="@if(session('passengerDetail')) {{ session('passengerDetail')[0]['date_of_birth'] }} @endif"/> 
                                                    @endif
                                              </div>
                                              <div class="col-6">
                                                  
                                                <label for="inputAddress" class="form-label">Phone</label>
                                                    @if(session('passengerDetail'))
                                                        @if(session('passengerDetail')[0]['phone'])
                                                            <input readonly type="text" id="" name="phone" class="form-control" required value="@if(session('passengerDetail')) {{ session('passengerDetail')[0]['phone'] }} @endif"/>
                                                        @else
                                                            <input type="text" id="phone" name="phone" class="form-control otp_code" required value="@if(session('passengerDetail')) {{ session('passengerDetail')[0]['phone'] }} @endif"/> 
                                                        @endif
                                                    @else
                                                        <input type="text" id="phone" name="phone" class="form-control otp_code" required value="@if(session('passengerDetail')) {{ session('passengerDetail')[0]['phone'] }} @endif"/> 
                                                    @endif
                                              </div>
                                               <div class="col-6">
                                                <label for="inputAddress" class="form-label">Passport Number</label>
                                                    @if(session('passengerDetail'))
                                                        @if(session('passengerDetail')[0]['passport_lead'])
                                                            <input readonly type="text" id="passport_lead_m" name="passport_lead" class="form-control otp_code" required value="@if(session('passengerDetail')) {{ session('passengerDetail')[0]['passport_lead'] }} @endif"/> 
                                                        @else
                                                            <input type="text" id="passport_lead_m" name="passport_lead" class="form-control otp_code" required value="@if(session('passengerDetail')) {{ session('passengerDetail')[0]['passport_lead'] }} @endif"/> 
                                                        @endif
                                                    @else
                                                        <input type="text" id="passport_lead_m" name="passport_lead" class="form-control otp_code" required value="@if(session('passengerDetail')) {{ session('passengerDetail')[0]['passport_lead'] }} @endif"/> 
                                                    @endif
                                              </div>
                                              <div class="col-6">
                                                <label for="inputAddress" class="form-label">Passport Expiry</label>
                                                @if(session('passengerDetail'))
                                                    @if(session('passengerDetail')[0]['passport_exp_lead'])
                                                        <?php $passport_exp_lead = session('passengerDetail')[0]['passport_exp_lead']; ?>
                                                        <input readonly type="text" readonly id="passport_exp_lead_m" name="passport_exp_lead" class="form-control " required value="{{ $passport_exp_lead }}"> 
                                                    @else
                                                        <input type="date" id="passport_exp_lead_m" name="passport_exp_lead" class="form-control " required value="@if(session('passengerDetail')) {{ session('passengerDetail')[0]['passport_exp_lead'] }} @endif"/> 
                                                    @endif
                                                @else
                                                    <input type="date" id="passport_exp_lead_m" name="passport_exp_lead" class="form-control " required value="@if(session('passengerDetail')) {{ session('passengerDetail')[0]['passport_exp_lead'] }} @endif"/> 
                                                @endif
                                              </div>
                
                
                
                                            <p>Gender</p>
                                            <div class="col-6">
                                              <div class="form-check">
                                              <input class="form-check-input" type="radio" name="gender" value="male" @if(session('passengerDetail')) @if(session('passengerDetail')[0]['gender'] == 'male') checked @endif @else checked  @endif id="male_m">
                                              <label class="form-check-label" for="male">
                                                Male
                                              </label>
                                            </div>
                                            
                                            </div>
                                            <div class="col-6">
                                                <div class="form-check">
                                              <input class="form-check-input" type="radio" name="gender" value="female" @if(session('passengerDetail')) @if(session('passengerDetail')[0]['gender'] == 'female') checked @endif @endif id="female_m">
                                              <label class="form-check-label" for="female">
                                                Female
                                              </label>
                                            </div>
                                            
                                            </div>
                                            
                                            <div class="col-12">
                                                    <div id="payment_gatway">
                                                                                 
                                                    </div>
                                              </div>
                
                                              <div class="col-12">
                                                     @php 
                                                 $cart_data_main = session('cart');
                                             
                                                 $cart_data = $cart_data_main[0];
                                                                        $adults = 0;
                                                                        $children = 0;
                                                                        $infants = 0;
                                                                    @endphp
                                                                @foreach($cart_data as $id => $details)
                                                             <?php
                                                                         $adults += $details['adults'];
                                                                       
                                                                       if(isset($details['double_adults']))
                                                                       {
                                                                      
                                                                         $adults += $details['double_adults'];
                                                                       
                                                                         
                                                                       }
                                                                       
                                                           
                                                                        
                                                                       if(isset($details['triple_adults']))
                                                                       {
                                                                    
                                                                         $adults += $details['triple_adults'];
                                                                        
                                                                         
                                                                       }
                                                                      
                                                                         
                                                                       if(isset($details['quad_adults']))
                                                                       {
                                                                      
                                                                         $adults += $details['quad_adults'];
                                                                        
                                                                         
                                                                       }
                                                                       
                                                                       
                                                                       if(isset($details['without_acc_adults']))
                                                                       {
                                                                      
                                                                         $adults += $details['without_acc_adults'];
                                                                        
                                                                         
                                                                       }
                                                                      
                                                                        
                                                                        
                                                                         
                                                                         $children += $details['children'];
                                                                         
                                                                         if(isset($details['double_childs']))
                                                                       {
                                                                      
                                                                         $children += $details['double_childs'];
                                                                        
                                                                         
                                                                       }
                                                                     
                                                                       if(isset($details['triple_childs']))
                                                                       {
                                                                      
                                                                         $children += $details['triple_childs'];
                                                                        
                                                                         
                                                                       }
                                                                      
                                                                       if(isset($details['quad_childs']))
                                                                       {
                                                                      
                                                                         $children += $details['quad_childs'];
                                                                        
                                                                         
                                                                       }
                                                                      
                                                                         
                                                                       if(isset($details['infants']))
                                                                       {
                                                                      
                                                                         $infants += $details['infants'];
                                                                        
                                                                         
                                                                       }
                                                                     
                                                                       
                                                                       if(isset($details['double_infant']))
                                                                       {
                                                                      
                                                                         $infants += $details['double_infant'];
                                                                        
                                                                         
                                                                       }
                                                                     
                                                                       if(isset($details['triple_infant']))
                                                                       {
                                                                      
                                                                         $infants += $details['triple_infant'];
                                                                        
                                                                         
                                                                       }
                                                                       
                                                                       if(isset($details['quad_infant']))
                                                                       {
                                                                      
                                                                         $infants += $details['quad_infant'];
                                                                        
                                                                         
                                                                       }
                                                                       
                                                                         
                                                                         
                                                                         
                                                                        
                                                                         
                                                                ?>
                                                                @endforeach
                
                                                            <!-- For Adults <Calculation> -->
                                                                @if(session('adults'))
                                                                    @php 
                                                                        $saveAdults = count(session('adults'));
                                                                        $saveAdults = $saveAdults + 1;
                                                                    @endphp
                
                                                                @else
                                                                    @php 
                                                                        $saveAdults = 1;
                                                                    @endphp
                                                                @endif
                                                        <!-- For Childs Calculation -->
                                                                @if(session('Childs'))
                                                                    @php 
                                                                        $saveChilds = count(session('Childs'));
                                                                    @endphp
                
                                                                @else
                                                                    @php 
                                                                        $saveChilds = 0;
                                                                    @endphp
                                                                @endif
                                                         <!-- For Infants Calculation -->
                                                                @if(session('infants'))
                                                                    @php 
                                                                        $saveinfants = count(session('infants'));
                                                                    @endphp
                
                                                                @else
                                                                    @php 
                                                                        $saveinfants = 0;
                                                                    @endphp
                                                                @endif
                                                                
                                                                @if(session('passengerDetail'))
                                                                    @php
                                                                        $button_type = '';
                                                                    @endphp
                                                                        @if($saveAdults < $adults)
                                                                            @php  $button_type = 'adult';    @endphp
                                                                            
                                                                        @elseif($saveChilds < $children)
                                                                            @php  $button_type = 'childs';    @endphp
                                                                            
                                                                        @elseif($saveinfants < $infants)
                                                                            @php  $button_type = 'infant';    @endphp
                                                                            
                                                                        @else
                                                                            @php  $button_type = 'Checkout';    @endphp
                                                                            
                                                                        @endif
                
                                                                        @if($button_type == 'Checkout')
                                                                        
                                                                           
                                                                             
                                                                            <button type="button" style="background-color:#d2b254; color:white;" id="checkout_button" class="btn" data-bs-toggle="modal" data-bs-target="#ConfirmBooking">
                                                                               Confirm Booking
                                                                            </button>
                                                                        @else
                                                                            <button type="button" style="background-color:#d2b254; color:white;" class="btn" data-bs-toggle="modal" data-bs-target="#exampleModal1">
                                                                                @if($button_type == 'adult')
                                                                                    Add Adult {{ $saveAdults + 1 }} / {{ $adults }}
                                                                                @elseif($button_type == 'childs')
                                                                                    Add Child {{ $saveChilds + 1 }} / {{ $children }}
                                                                                @else
                                                                                   Add Infant {{ $saveinfants + 1 }} / {{ $infants }}
                                                                                    
                                                                                @endif
                                                                            </button>
                                                                            <button type="button" style="background-color:#d2b254; color:white;" id="checkout_button" class="btn" data-bs-toggle="modal" data-bs-target="#ConfirmBooking">
                                                                               Confirm Booking
                                                                            </button>
                                                                        @endif
                                                                   
                
                                                                @else 
                                                                    <button class="btn" style="background-color:#277019; color:white;float: right;" type="submit">Next</button>
                                                                @endif
                                                                
                                                                
                                                                      
                                                             
                                              </div>
                                          
                                            </form>
                                  </div>
                                  
                                  
                              </div>
                              
                                                         
                        </div>
                        
                       
                                                   <div class="col-12">
                                                        <div class="checkout-note">
                                                          
                                                    
                                                            <p> <i class="fa-solid fa-headset"></i> FREE CUSTOMER SERVICE AVAILABLE FOR 365/24/7</p>
                                                            
                                                           
                                                        </div>
                                                    
                                                             
                                                       
                                                        
                                                    </div>

                            
                            
                                                   
                       
                       
                       
                       
                       
                       
                       
                       
                    </div>
                    
                <div class="col-md-5">
                    
                    <div class="modal fade" id="hotelbeds_map" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Hotel Location</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                    <div class="row">
                    <div class="col-12 col-md-12">
                                                    
                    <div style="width: 95%"><iframe width="100%" height="300" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q=51.523359,-0.127788&amp;t=&amp;z=19&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe></div>
                    </div>  
                      </div>                                 
                          </div>
                          
                        </div>
                      </div>
                    </div>
                    
                    <div class="checkout-note">
                        <p style="font-size:12px;">  
                           <i class="fa-regular fa-calendar-days"></i>    We have limited availability at this price - book now! </p>
                        </div>
                    
                    @if(session('cart'))
                        <?php 
                            $cart_data_main = session('cart');
                            $cart_data_visa = session('cart_visa');
                            $cart_data = $cart_data_main[0];
                            $total = 0;
                            if($cart_data_main[1] == 'tour'){
                                $model_id = 'tourId';
                                $total_index = 'price_without_disc';
                            }else{
                                $model_id = 'activtyId';
                                $total_index = 'activity_total_price';
                            }
                        ?>
                        @foreach($cart_data as $id => $details)
                            <?php
                                // dd($cart_data);
                                if(isset($details['tourId'])){
                                    $cart_data_visa = $cart_data_visa[$details['tourId']];    
                                }
                            ?>
                            <div class="card w-100 mb-4">
                                <div class="card-body">
                                    
                                    <svg id="Flat" height="35" viewBox="0 0 512 512" width="35" xmlns="http://www.w3.org/2000/svg"><g><path d="m90 448h-2v-24a24 24 0 0 0 -24-24h-16a24 24 0 0 0 -24 24v64h104v-8l-8-2a30 30 0 0 0 -30-30z" fill="#79d8eb"></path><path d="m422 448h2v-24a24 24 0 0 1 24-24h16a24 24 0 0 1 24 24v64h-104v-8l8-2a30 30 0 0 1 30-30z" fill="#79d8eb"></path><path d="m120 256h272v232h-272z" fill="#fac850" transform="matrix(-1 0 0 -1 512 744)"></path><g><path d="m320 456v-248h-128v248h-72v32h272v-32z" fill="#fa5d3f"></path><path d="m232 424h48v64h-48z" fill="#fac850"></path><g fill="#fff9f8"><path d="m216 320h16v16h-16z"></path><path d="m248 320h16v16h-16z"></path><path d="m280 320h16v16h-16z"></path><path d="m216 352h16v16h-16z"></path><path d="m248 352h16v16h-16z"></path><path d="m280 352h16v16h-16z"></path><path d="m216 384h16v16h-16z"></path><path d="m248 384h16v16h-16z"></path><path d="m280 384h16v16h-16z"></path><path d="m216 256h16v16h-16z"></path><path d="m248 256h16v16h-16z"></path><path d="m280 256h16v16h-16z"></path><path d="m216 288h16v16h-16z"></path><path d="m248 288h16v16h-16z"></path><path d="m280 288h16v16h-16z"></path></g></g><path d="m256.004 24 22.419 45.427 50.132 7.284-36.276 35.36 8.564 49.929-44.839-23.573-44.839 23.573 8.563-49.929-36.275-35.36 50.131-7.284z" fill="#fac850"></path><path d="m386.327 112 15.551 31.511 34.775 5.053-25.163 24.528 5.94 34.635-31.103-16.352-31.104 16.352 5.94-34.635-25.163-24.528 34.775-5.053z" fill="#fac850"></path><path d="m453.486 216 10.666 21.61 23.848 3.466-17.257 16.821 4.074 23.752-21.331-11.214-21.33 11.214 4.074-23.752-17.257-16.821 23.848-3.466z" fill="#fac850"></path><path d="m125.673 112-15.551 31.511-34.775 5.053 25.163 24.528-5.94 34.635 31.103-16.352 31.104 16.352-5.94-34.635 25.163-24.528-34.775-5.053z" fill="#fac850"></path><path d="m58.514 216-10.666 21.61-23.848 3.466 17.257 16.821-4.074 23.752 21.331-11.214 21.33 11.214-4.074-23.752 17.257-16.821-23.848-3.466z" fill="#fac850"></path><g fill="#fffdfa"><path d="m144 288h24v16h-24z"></path><path d="m144 328h24v16h-24z"></path><path d="m144 368h24v16h-24z"></path><path d="m144 408h24v16h-24z"></path><path d="m344 368h24v16h-24z"></path><path d="m344 408h24v16h-24z"></path><path d="m344 288h24v16h-24z"></path><path d="m344 328h24v16h-24z"></path></g>
                                        <path d="m224 184h64v48h-64z" fill="#fac850"></path></g></svg>
                                        
                                    <div style="display:flex;justify-content:space-between;">
                                         <h5>  Activity Booking Details</h5>
                                        
                                        <button class="btn btn-sm d-none" title="View Detail" onclick="fetch_tour_details({{ $details[$model_id] }},'{{ $details['pakage_type'] }}')" data-bs-toggle="modal" data-bs-target="#getTourDetails"><i class="fa fa-eye" aria-hidden="true"></i></button>
                                    </div>
                                   
                                    <div class="detail-group">
                                        <ul class="list-group">
                                            
                                            <!--ACTIVITY-->
                                            @isset($details['adult_price'])
                                                @isset($details['adults'])
                                                    <li class="d-flex justify-content-between"><b>Adults Details:</b></li>
                                                    <li class="d-flex justify-content-between"><p style="width: 200px;font-weight: 500;font-size: 13px; margin-bottom:0;">Adult Price:</p>&nbsp;<b>{{ $details['adults'] }} X {{ $details['currency'] }}{{ $details['adult_price'] }} </b></li>
                                                @endisset
                                            @endisset
                                            
                                            @isset($details['child_price'])
                                                @isset($details['children'])
                                                    <li class="d-flex justify-content-between mt-2"><b>Childs Details:</b></li>
                                                    <li class="d-flex justify-content-between mt-2"><p style="width: 200px;font-weight: 500;font-size: 13px; margin-bottom:0;">Child Price :</p>&nbsp;<b>{{ $details['children'] }} X {{ $details['currency'] }}{{ $details['child_price'] }}</b></li> 
                                                @endisset
                                            @endisset
                                            
                                            @isset($details['infant_price'])
                                                <li class="d-flex justify-content-between mt-2 d-none"><b>Infants Details:</b></li>
                                                <li class="d-flex justify-content-between mt-2 d-none"><p style="width: 200px;font-weight: 500;font-size: 13px; margin-bottom:0;">Infant Price :</p>&nbsp;<b>{{ $details['infants'] }} X {{ $details['currency'] }} {{ $details['infant_price'] }}</b></li>
                                            @endisset
                                            <!--ACTIVITY-->
                                            
                                            <?php 
                                                if($details['Additional_services_names_more'] !== 'null'){
                                                    $services_names = json_decode($details['Additional_services_names_more']);
                                                    $services_persons_more = json_decode($details['services_persons_more']);
                                                    $services_price_more = json_decode($details['services_price_more']);
                                                    $services_days_more = json_decode($details['services_days_more']);
                                                    
                                                    $z = 0;
                                                    foreach($services_names as $service_res){
                                            ?>
                                                        <li class="d-flex justify-content-between mt-2"><b>Additional Services:</b></li>
                                                        <li class="d-flex justify-content-between"><p style="width: 200px;font-weight: 500;font-size: 13px;"> {{ $service_res }}</p>&nbsp;<b>{{ $details['currency'] }} {{ $services_price_more[$z] * ($services_persons_more[$z] * $services_days_more[$z])}}</b></li>     
                                            <?php
                                                    }
                                                }
                                            ?>
                                            
                                            @php 
                                                $adults_count   = 0; 
                                                $total          = $details['price'];
                                                $currency       = $details['currency'];
                                                if($details["adults"] == 0){
                                                    $adults_count++;
                                                }
                                            @endphp
                                        
                                            <li class="d-flex justify-content-between"><b>Total Price:</b>&nbsp;<p>{{ $details['currency'] }} {{ $total }}</p></li> 
                    
                                            <li class="d-flex mt-0 justify-content-end">
                                            <div class="mb-2 d-flex  price-vat-tag h-15px">
                                                <svg class="mr-2" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="10" height="10" viewBox="0 0 503.151 503.151" style="enable-background:new 0 0 503.151 503.151;" xml:space="preserve">
                                                    <path style="fill:#4CAF50;" d="M471.172,0H309.337c-8.483,0.022-16.616,3.387-22.635,9.365L9.369,286.699
                                                    	c-12.492,12.496-12.492,32.752,0,45.248l161.835,161.835c12.496,12.492,32.752,12.492,45.248,0l277.333-277.333
                                                    	c6.018-5.992,9.39-14.142,9.365-22.635V32C503.151,14.335,488.837,0.012,471.172,0z M385.839,170.667
                                                    	c-29.455,0-53.333-23.878-53.333-53.333S356.383,64,385.839,64s53.333,23.878,53.333,53.333S415.294,170.667,385.839,170.667z"></path>
                                                    <g>
                                                </g></svg>
                                                <small class="f-0-6-r">Inclusive of VAT &amp; Taxes</small>
                                            </div>
                                        </li>
                                        </ul>
                                        <button class="btn form-control d-none" style="background-color:#277019; color:white;" title="View Detail" onclick="fetch_tour_details({{ $details[$model_id] }},'{{ $details['pakage_type'] }}')" data-bs-toggle="modal" data-bs-target="#getTourDetails"><i class="fa fa-eye" aria-hidden="true"></i> View Package Detail</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    
                    <div class="checkout-note"></div>
                </div> 
            </div>
        </div>
    </section>

    @if(session('cart'))
        <?php 
            $cart_data_main = session('cart');
            $cart_data = $cart_data_main[0];
            
            if($cart_data_main[1] == 'tour'){
                $model_id = 'tourId';
            }else{
                $model_id = 'activtyId';
            }
            ?>
        @foreach($cart_data as  $details)
            <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            @php $passengerType = ''; @endphp
                        @if($saveAdults < $adults)
                            Add Adult {{ $saveAdults + 1 }} / {{ $adults }}
                            @php $passengerType = 'adults'; @endphp
                            
                        @elseif($saveChilds < $children)
                            Add Child {{ $saveChilds + 1 }} / {{ $children }}
                            @php $passengerType = 'child'; @endphp
                        @elseif($saveinfants < $infants)
                            Add Infant {{ $saveinfants + 1 }} / {{ $infants }}
                            @php $passengerType = 'infant'; @endphp
                        @else
                            Checkout
                        @endif        </h5>
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
                          <div class="row">
                               <div class="col-md-12" id="other_passenger_loader" style="display:none">
                                  <iframe src="https://embed.lottiefiles.com/animation/98195"></iframe>
                               </div>
                          </div>
                        <form action="{{ route('save_adutls') }}" style="display:none" id="auto_other_forms" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" name="passengerType" hidden value="{{ $passengerType }}" class="form-control">
                                </div>
                                
                                  <div class="col-md-6">
                                        <label for="inputEmail4" class="form-label">First name</label>
                                        <input type="text" class="form-control" id="f_name" required  name="passengerName">
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
                                <div class="col-md-12">
                                    <button type="submit" name="submit" class="btn btn-primary">Save changes</button>
                                </div>
                             
                                
                            </div>
                         </form>
                        <form action="{{ route('save_adutls') }}" id="manual_other_forms" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 d-none">
                                    <input type="text" name="passengerType" hidden value="{{ $passengerType }}" class="form-control">
                                </div>
                                
                                  <div class="col-md-6" style="padding-top: 10px;">
                                        <label for="inputEmail4" class="form-label">First name</label>
                                        <input type="text" class="form-control" id="f_name" required  name="passengerName">
                                  </div>
                                      <div class="col-md-6" style="padding-top: 10px;">
                                        <label for="inputPassword4" class="form-label">Last name</label>
                                        <input type="text" class="form-control" id="l_name" required name="lname">
                                      </div>
                                      
                                      <div class="col-6" style="padding-top: 10px;">
                                        <label for="inputAddress" class="form-label">Nationality</label>
                                        <select class="form-control" required name="country" id="nationality_other_m"  style="border-radius: unset !important;">
                                            @isset($countries)
                                                @foreach($countries as $count_res)
                                                    <option value="{{ $count_res->name }}" @if($count_res->name== 'United Kingdom') selected @endif phone_code='{{ $count_res->phonecode }}'>{{ $count_res->name }}</option>
                                                @endforeach
                                            @endisset
                                        </select>
                                      </div>
                                      <div class="col-6" style="padding-top: 10px;">
                                        <label for="inputAddress" class="form-label">Date of birth</label>
                                        <input  type="date" id="date_of_birth_other" name="date_of_birth" class="form-control " required /> 
                                      </div>
                                     
                                       <div class="col-6" style="padding-top: 10px;">
                                        <label for="inputAddress" class="form-label">Passport Number</label>
                                        <input  type="text" id="passport_lead_other_m" name="passport_lead" class="form-control otp_code" required /> 
                                      </div>
                                      <div class="col-6" style="padding-top: 10px;">
                                        <label for="inputAddress" class="form-label">Passport Expiry</label>
                                        <input  type="date" id="passport_exp_lead_other_m" name="passport_exp_lead" class="form-control " required /> 
                                </div>
                                <div class="col-md-6" style="padding-top: 10px;">
                                    <div class="row" style="margin-top:2.5rem">
                                        <div class="col-md-4">
                                            <label for="">Gender</label>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender" id="gender1_m" value="male" checked>
                                            <label class="form-check-label" for="gender1">
                                                Male
                                            </label>
                                        </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender" id="gender2_m" value="female">
                                            <label class="form-check-label" for="exampleRadios2">
                                                Female
                                            </label>
                                            </div>
                                        </div>
                                        
                                    
                                     
                                    </div>
                                   
                                    
                                </div>
                                <div class="col-md-6" style="margin-top: 25px;">
                                    <button type="submit" name="submit" class="btn btn-primary" style="float:right;">Save changes</button>
                                </div>
                             
                                
                            </div>
                        </form>
                      </div>
                     
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        
                      </div>
                     
                    </div>
                  </div>
                </div>
            
            <div class="modal fade" id="ConfirmBooking" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Checkout Message</h5>
                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
              
                  <div class="modal-body">
                    
                       <p>{{ $details['checkout_message'] }}</p>
                    
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <a href="{{ route('submit_booking_ActivityAdmin') }}"  class="btn" style="background-color:#d2b254; color:white;">Confirm Booking</a>
                  </div>
                </div>
              </div>
            </div>
        @endforeach
    @endif

    @if(session('cart'))
        <?php 
        $cart_data_main = session('cart');
        $cart_data = $cart_data_main[0];
        
        if($cart_data_main[1] == 'tour'){
            $model_id = 'tourId';
        }else{
            $model_id = 'activtyId';
        }
        ?>
        @foreach($cart_data as $id => $details)
            <div class="modal fade" id="exampleModal{{ $details[$model_id] }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cancellation Policy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
              
                  <div class="modal-body">
                    
                       <p>{{ $details['cancellation_policy'] }}</p>
                    
                  </div>
                  <div class="modal-footer">
                    
                  </div>
              
                </div>
              </div>
            </div>
        @endforeach
    @endif

    <div class="modal fade" id="getTourDetails" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Tour Detail</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="Tour_data">
            
          </div>
          <div class="modal-footer">
           
          </div>
        </div>
      </div>
    </div>
    
    <script type="text/javascript" src="https://alhijaztours.net/public/plugins/jquery/jquery.js"></script>
    
    <script>
        function startTimer(duration, display) {
        var timer = duration, minutes, seconds;
        setInterval(function () {
            minutes = parseInt(timer / 60, 10)
            seconds = parseInt(timer % 60, 10);
    
            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;
    
            display.text(minutes + ":" + seconds);
    
            if (--timer < 0) {
                timer = duration;
            }
        }, 1000);
    }
    
    jQuery(function ($)
     {
        var fiveMinutes = 60 * 5,
            display = $('#time');
        startTimer(fiveMinutes, display);
    });
    </script>

@endsection


@section('scripts')
    <script src="{{asset('public/assets/frontend/build/js/intlTelInput.js')}}"></script>
    <script src='https://cdn.rawgit.com/naptha/tesseract.js/1.0.10/dist/tesseract.js'></script>
        
    <script>
    
      $('.owl-carousel4').owlCarousel({
            loop:true,
            margin:10,
            nav:true,
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:3
                },
                1000:{
                    items:4
                }
            }
        })

        var input = document.querySelector("#phone");
        window.intlTelInput(input, {
          // allowDropdown: false,
          // autoHideDialCode: false,
          // autoPlaceholder: "off",
          // dropdownContainer: document.body,
          // excludeCountries: ["us"],
          // formatOnDisplay: false,
          // geoIpLookup: function(callback) {
          //   $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
          //     var countryCode = (resp && resp.country) ? resp.country : "";
          //     callback(countryCode);
          //   });
          // },
          // hiddenInput: "full_number",
          // initialCountry: "auto",
          // localizedCountries: { 'de': 'Deutschland' },
          // nationalMode: false,
          // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
          // placeholderNumberType: "MOBILE",
          // preferredCountries: ['cn', 'jp'],
          // separateDialCode: true,
          utilsScript: "build/js/utils.js",
        });
    

      </script>
        
    <script>
    
        function formatDate(date) {
                  return [
                    padTo2Digits(date.getDate()),
                    padTo2Digits(date.getMonth() + 1),
                    date.getFullYear(),
                  ].join('/');
        }
                
        function padTo2Digits(num) {
          return num.toString().padStart(2, '0');
        }
        
        function fetch_country_code(){
            // 
            var name = $('#country').find("option:selected").attr('phone_code');
            
            console.log('this is call now'+name);
            $('#phone').val('+'+name);
                      var input = document.querySelector("#phone");
                    window.intlTelInput(input, {
                    
                      utilsScript: "build/js/utils.js",
                    });
        }
        
        fetch_country_code();
    
        
        //  <div id="payment_gatway">
                                                                     
        //                                                          </div>
        //                                                          <a href="{{ URL::to('submit_booking') }}" id="checkout_button" style="display:none" class="btn" style="background-color:#d2b254; color:white;">Confirm Booking</a>
    
        
    
        
        function payment_type_and_mode(){
            console.log('payment method is call now ');
             var id = $('#tour_id').val();
               <?php 
                $cart_data_main = session('cart');
                $cart_data = $cart_data_main[0];
                
                if($cart_data_main[1] == 'tour'){
                    $type = 'tour';
                    $payment_gateway = 'payment_gateways';
                }else{
                    $type = 'activity';
                    $payment_gateway = 'payment_getway';
                }
                ?>
             $.ajax({
                url: "{{ URL::to('get_payment_modes') }}",
                method: "post",
                data: {
                    _token: '{{ csrf_token() }}',
                    tour_id: id,
                    Tourtype: '<?php echo $type; ?>'
                },
                success: function (response) {
                    // window.location.reload();
     
                    
                    console.log(response);
                    // $('#country_code').val(response);
                    var gateway = response['tours']['<?php echo $payment_gateway ?>'];
                    var paymentMode = JSON.parse(response['tours']['payment_modes']);
                    
                    var modes = '';
                    for(var i=0; i<paymentMode.length; i++){
                        modes += paymentMode[i]+',';
                    }
                    var checkbox = `<div class="row">
                                        <div class="col-md-9">
                                            <label>Payment Method</label>
                                            <div class="form-check">
                                              <input class="form-check-input" onclick="checkProcced()" checked name="payment_type" type="checkbox" value="${gateway}" id="defaultCheck1">
                                              <label class="form-check-label" for="defaultCheck1">
                                                ${gateway}
                                              </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Payment Mode</label>
                                            <div class="form-check">
                                              <input class="form-check-input" name="paymentMode" type="checkbox" checked value="${modes}" id="defaultCheck2" disabled>
                                              <label class="form-check-label" for="defaultCheck2">
                                                ${modes}
                                              </label>
                                            </div>
                                        </div>
                                    </div>
                                     `;
                    $('#payment_gatway').html(checkbox);
                    
                    console.log(response['tours']['payment_gateways']);
                    
                    
                    // console.log(data);
                    // $('#country_code').val(response);
                }
            });
            
        }
        payment_type_and_mode();
        function checkProcced(){
            var active = $('#defaultCheck1').prop("checked") ? 1 : 0 ;
            if(active){
                $('#checkout_button').css('display','Inline');
            }else{
                $('#checkout_button').css('display','none');
            }
          console.log('function is call '+active);
        }
        
        
             function fetch_tour_details(id,type){
                    
                    console.log('you click on link '+id+type);
                             $.ajax({
                                    url: 'fetch_tour_data',
                                    method: "post",
                                    data: {
                                        _token: '{{ csrf_token() }}', 
                                        id: id, 
                                        type: type,
                                    },
                                    success: function (response) {
                                        // console.log(response);
                                        var data = JSON.parse(response);
                                        console.log(data);
                                        
                                        if(type == 'tour'){
                                            if(data['tour_data']['tour_location'] !== null){
                                             var tour_location = JSON.parse(data['tour_data']['tour_location']);
                                        
                                            var tours_locations = '';
                                            for(var i=0; i<tour_location.length; i++){
                                                tours_locations += tour_location[i]+",";
                                            }
                                            console.log(tours_locations);
                                            }else{
                                                var tours_locations = '';
                                            }
                                           
                                            var startDate = formatDate(new Date(data['tour_data']['start_date']));
                                            var endDate = formatDate(new Date(data['tour_data']['end_date']));
                                            
                                            console.log(data['accomodation']);
                                            
                                             
                                             
                                              if(data['accomodation'] !== null){
                                             var hotels = '';
                                                for(var i=0; i<data['accomodation'].length; i++){
                                                    var acc_check_in = formatDate(new Date(data['accomodation'][i]['acc_check_in']));
                                                    var acc_check_out = formatDate(new Date(data['accomodation'][i]['acc_check_out']));
                                                     hotels +=`<table class="room-type-table tours-hotels-table">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="room-type">${data['accomodation'][i]['hotel_city_name']} Hotel Details</th>
                                                                        <th class="room-people">Available Rooms </th>
                                                                        <th class="room-condition">Amenities</th>
                                                                      
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="room-type">
                                                                            <div class="room-thumb">
                                                                                <img src="{{ config('img_url') }}/public/uploads/package_imgs/${data['accomodation'][i]['accomodation_image']}" alt="">
                                                                            </div>
                                                                            <div class="room-title">
                                                                                <h4>${data['accomodation'][i]['acc_hotel_name']}</h4>
                                                                            </div>
                                                                            
                                                                                <ul class="list-unstyled">
                                                                                    <li><i class="fa fa-calendar" aria-hidden="true"></i> Check In  : ${acc_check_in}</li>
                                                                                    <li><i class="fa fa-calendar" aria-hidden="true"></i> Check Out : ${acc_check_out}</li>
                                                                                    <li><i class="fa fa-moon-o" aria-hidden="true"></i> No Of Nights : ${data['accomodation'][i]['acc_no_of_nightst']}</li>
                                                                                </ul>
                                                                            
                                                                           
                                                                           
                                                                        </td>
                                                                        <td class="room-people">`
                                                                            if(data['accomodation_more'] !== null){
                                                                               for(var k=0; k<data['accomodation_more'].length; k++){
                                                                                    if(data['accomodation'][i]['hotel_city_name'] == data['accomodation_more'][k]['more_hotel_city']){
                                                                                        hotels +=`<p>${data['accomodation_more'][k]['more_hotel_city']}</p>`
                                                                                    }
                                                                               }
                                                                            }
                                                                        hotels +=`</td>
                                                                        <td class="room-condition">
                                                                            ${data['accomodation'][i]['hotel_whats_included']}
                                                                           
                                                                            
                                                                        </td>
                                                                       
                                                                    </tr>
                                                                  
                                                                 
                                                                </tbody>
                                                            </table>`
                                                          
                                                
                                                }
                                              }else{
                                                 var hotels = '';
                                              }
                                              
                                              
                                             
                                              
                                            if(data['flight_det']['arrival_airport_code'] !== null || data['flight_det_more'] !==null){
                                                if (data['flight_det']['other_Airline_Name2'] !== undefined) {
                                                     var airlineName =  data['flight_det']['other_Airline_Name2']
                                                }else{
                                                    var airlineName = '';
                                                }
                                                
                                                 if (data['flight_det']['departure_Flight_Type'] !== undefined) {
                                                     var departure_Flight_Type =  data['flight_det']['other_Airline_Name2']
                                                }else{
                                                    var departure_Flight_Type = '';
                                                }
                                              
                                             
                                             var flight = `<div class="col-md-1 mt-2 mb-2">
                                                                        <i class="fa fa-plane" aria-hidden="true" style="font-size:2rem;"></i>
                                                        </div>
                                                        <div class="col-md-11 mt-2 mb-2"><h5>Flight Details</h5></div>
                                                        
                                                        <div class="col-md-12">
                                                                        <div class="initiative">
                                                <!-- ITEM -->
                                                <div class="initiative__item">
                                                    <div class="initiative-top">
                                                        <div class="title">
                                                            <div class="from-to">
                                                                <span class="from">${data['first_depaurture']}</span>
                                                                <i class="awe-icon awe-icon-arrow-right"></i>
                                                                <span class="to">${data['last_destination']}</span>
                                                            </div>
                                                            <div class="time">${data['first_dep_time']} | ${data['last_arrival']}</div>
                                                        </div>
                                                       
                                                    </div>
                                                    <table class="initiative-table">
                                                        <tbody>`
                                                        if(data['flight_det']['flight_type'] !== 'Indirect'){
                                                            flight += `<tr>
                                                                <th>
                                                                    <div class="item-thumb">
                                                                        <div class="image-thumb">
                                                                            <img src="{{ config('img_url') }}/public/uploads/package_imgs/${data['flight_det']['flights_image']}" alt="">
                                                                        </div>
                                                                        <div class="text">
                                                                            <span>${airlineName}</span>
                                                                            <p>${data['flight_det']['departure_flight_number']}</p>
                                                                            <span>${departure_Flight_Type}</span>
                                                                        </div>
                                                                    </div>
                                                                </th>
                                                                <td>
                                                                    <div class="item-body" style="padding:0px;">
                                                                        <div class="item-from">
                                                                            <h3> <img src="https://client1.synchronousdigital.com/public/images/departure.png" alt="" width="25px">
                                                                            ${data['flight_det']['departure_airport_code']}</h3>
                                                                            <span class="time">${data['flight_times_arr'][0]}</span>
                                                                            <span class="date">${data['flight_date_arr'][0]} </span>
                                                                          
                                                                        </div>
                                                                        <div class="item-time">
                                                                            <i class="fa fa-clock-o"></i>
                                                                            <span>10h 25m</span>
                                                                        </div>
                                                                        <div class="item-to">
                                                                            <h3><img src="https://client1.synchronousdigital.com/public/images/landing.png" alt="" width="25px">
                                                                            ${data['flight_det']['arrival_airport_code']}</h3>
                                                                            <span class="time">${data['flight_times_arrival_arr'][0]} </span>
                                                                            <span class="date">${data['flight_date_arrival_arr'][0]} </span>
                                                                          
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>`;
                                                        }
                                                            
                                                        if(data['flight_det_more'] !== null){
                                                            for(var fl = 0; fl < data['flight_det_more'].length; fl++){
                                                           flight += `<tr>
                                                                <th>
                                                                    <div class="item-thumb">
                                                                        <div class="image-thumb">
                                                                            <img src="{{ config('img_url') }}/public/uploads/package_imgs/${data['flight_det']['flights_image']}" alt="">
                                                                        </div>
                                                                        <div class="text">
                                                                            <span>${data['flight_det_more'][fl]['more_other_Airline_Name2']}</span>
                                                                            <p>${data['flight_det_more'][fl]['more_departure_flight_number']}</p>
                                                                            <span>${data['flight_det_more'][fl]['more_departure_Flight_Type']}</span>
                                                                        </div>
                                                                    </div>
                                                                </th>
                                                                <td>
                                                                    <div class="item-body" style="padding:0px;">
                                                                        <div class="item-from">
                                                                            <h3>${data['flight_det_more'][fl]['more_departure_airport_code']}</h3>
                                                                           <span class="time">${data['flight_times_arr'][1 + fl]}</span>
                                                                            <span class="date">${data['flight_date_arr'][1 + fl]} </span>
                                                                        </div>
                                                                        <div class="item-time">
                                                                            <i class="fa fa-clock-o"></i>
                                                                            <span>${data['flight_det_more'][fl]['more_total_Time']}</span>
                                                                        </div>
                                                                        <div class="item-to">
                                                                            <h3>${data['flight_det_more'][fl]['more_arrival_airport_code']}</h3>
                                                                              <span class="time">${data['flight_times_arrival_arr'][1 + fl]} </span>
                                                                            <span class="date">${data['flight_date_arrival_arr'][1 + fl]} </span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>`;
                                                            }
                                                            
                                                        }
                                                        
                                                        
                                                        flight += `</tbody>
                                                    </table>
                                                </div>
                                                <!-- END / ITEM -->
                                                
                                                 <h6>Return Details</h6>
                                                 
                                                 <div class="initiative__item">
                                                    <div class="initiative-top">
                                                        <div class="title">
                                                            <div class="from-to">
                                                                <span class="from">${data['return_data']['ret_first_depaurture']}</span>
                                                                <i class="awe-icon awe-icon-arrow-right"></i>
                                                                <span class="to">${data['return_data']['ret_last_destination']}</span>
                                                            </div>
                                                            <div class="time">${data['return_data']['ret_first_dep_time']} | ${data['return_data']['ret_last_arrival']}</div>
                                                        </div>
                                                   
                                                    </div>
                                                    <table class="initiative-table">
                                                        <tbody>`
                                                        if(data['flight_det']['flight_type'] !== 'Indirect'){
                                                            flight += `<tr>
                                                                <th>
                                                                    <div class="item-thumb">
                                                                        <div class="image-thumb">
                                                                            <img src="{{ config('img_url') }}/public/uploads/package_imgs/${data['flight_det']['flights_image']}" alt="">
                                                                        </div>
                                                                        <div class="text">
                                                                            <span>${airlineName}</span>
                                                                            <p>${data['flight_det']['departure_flight_number']}</p>
                                                                            <span>${departure_Flight_Type}</span>
                                                                        </div>
                                                                    </div>
                                                                </th>
                                                                <td>
                                                                    <div class="item-body" style="padding:0px;">
                                                                        <div class="item-from">
                                                                            <h3>${data['flight_det']['return_departure_airport_code']}</h3>
                                                                            <span class="time">${data['ret_flight_times_arr'][0]}</span>
                                                                            <span class="date">${data['ret_flight_date_arr'][0]} </span>
                                                                          
                                                                        </div>
                                                                        <div class="item-time">
                                                                            <i class="fa fa-clock-o"></i>
                                                                            <span>${data['flight_det']['return_total_Time']}</span>
                                                                        </div>
                                                                        <div class="item-to">
                                                                            <h3>${data['flight_det']['return_arrival_airport_code']}</h3>
                                                                            <span class="time">${data['ret_flight_times_arrival_arr'][0]} </span>
                                                                            <span class="date">${data['ret_flight_date_arrival_arr'][0]} </span>
                                                                          
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>`;
                                                        }
                                                            
                                                        if(data['flight_det_more'] !== null){
                                                            for(var fl = 0; fl < data['flight_det_more'].length; fl++){
                                                           flight += `<tr>
                                                                <th>
                                                                    <div class="item-thumb">
                                                                        <div class="image-thumb">
                                                                            <img src="{{ config('img_url') }}/public/uploads/package_imgs/${data['flight_det']['flights_image']}" alt="">
                                                                        </div>
                                                                        <div class="text">
                                                                            <span>${data['flight_det_more'][fl]['more_other_Airline_Name2']}</span>
                                                                            <p>${data['flight_det_more'][fl]['more_departure_flight_number']}</p>
                                                                            <span>${data['flight_det_more'][fl]['more_departure_Flight_Type']}</span>
                                                                        </div>
                                                                    </div>
                                                                </th>
                                                                <td>
                                                                    <div class="item-body" style="padding:0px;">
                                                                        <div class="item-from">
                                                                            <h3>${data['flight_det_more'][fl]['return_more_departure_airport_code']}</h3>
                                                                           <span class="time">${data['ret_flight_times_arr'][1 + fl]}</span>
                                                                            <span class="date">${data['ret_flight_date_arr'][1 + fl]} </span>
                                                                        </div>
                                                                        <div class="item-time">
                                                                            <i class="fa fa-clock-o"></i>
                                                                            <span>${data['flight_det_more'][fl]['return_more_total_Time']}</span>
                                                                        </div>
                                                                        <div class="item-to">
                                                                            <h3>${data['flight_det_more'][fl]['return_more_arrival_airport_code']}</h3>
                                                                              <span class="time">${data['ret_flight_times_arrival_arr'][1 + fl]} </span>
                                                                            <span class="date">${data['ret_flight_date_arrival_arr'][1 + fl]} </span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>`;
                                                            }
                                                        }
                                                        flight += `</tbody>
                                                    </table>
                                                </div>
                                           
                                            </div>
                                                        </div>`;
                                       
                                              
                                                                }else{
                                                                    var flight = '';
                                                                }
                                                                
                                              if(data['transportaion']['transportation_drop_off_location'] !== null){
                                                  if(data['transportaion']['transportation_trip_type'] !== 'All_Round')
                                                  { var transportation_type = data['transportaion']['transportation_trip_type'] } 
                                                  else { var transportation_type = 'All Round '}
                                                 
                        var date = new Date(data['transportaion']['transportation_pick_up_date']);
        
                        var transportation_pick_up_date =  date.getDate()+
                          "/"+(date.getMonth()+1)+
                          "/"+date.getFullYear();
                                             var transportaion = `<div class="col-md-1 mt-2 mb-2">
                                                                        <i class="fa fa-car" aria-hidden="true" style="font-size:2rem;"></i>
                                                        </div>
                                                        <div class="col-md-11 mt-2 mb-2"><h5>Transportation Details</h5></div>
                                                        
                                                        <div class="col-md-12">
                                                                          <table class="initiative-table">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <th>
                                                                                        <div class="item-thumb">
                                                                                            <div class="image-thumb">
                                                                                                <img src="{{ config('img_url') }}/public/uploads/package_imgs/${ data['transportaion']['transportation_image'] }" alt="">
                                                                                            </div>
                                                                                            <div class="text">
                                                                                                <span>Vehicle: ${ data['transportaion']['transportation_vehicle_type'] }</span>
                                                                                                <span style="display:block">Type: ${transportation_type}</span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </th>
                                                                                    <td>
                                                                                        <div class="item-body" style="padding:0px;">
                                                                                            <div class="item-from">
                                                                                             <h5 style="font-size:1.1rem;">Pickup Location</h5>
                                                                                                <h6 style="font-size:1rem;">${ data['transportaion']['transportation_pick_up_location'] }</h6>
                                                                                                <span class="date">${ data['tran_date_time']['tran_pick_date'] }</span>
                                                                                                <span class="date">${ data['tran_date_time']['tran_pick_time'] }</span>
                                                                                               
                                                                                            </div>
                                                                                            <div class="item-time">
                                                                                                <i class="fa fa-clock-o"></i>
                                                                                                <span>${data['transportaion']['transportation_total_Time']}</span>
                                                                                            </div>
                                                                                            <div class="item-to">
                                                                                                    <h5 style="font-size:1.1rem;">Drop Off Location</h5>
                                                                                                <h6 style="font-size:1rem;">${ data['transportaion']['transportation_drop_off_location'] }</h6>
                                                                                                <span class="date">${ data['tran_date_time']['tran_drop_date'] }</span>
                                                                                                <span class="date">${ data['tran_date_time']['tran_drop_time'] }</span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </td>
                                                                                    
                                                                                </tr>`;
                                                                                
                                                            if(data['transportaion']['transportation_trip_type'] == 'Return'){
                                                            var date = new Date(data['transportaion']['return_transportation_pick_up_date']);
        
                                                            var return_transportation_pick_up_date =  date.getDate()+
                                                              "/"+(date.getMonth()+1)+
                                                              "/"+date.getFullYear();
                                                             transportaion +=  `<tr>
                                                                <th>
                                                                    <div class="item-thumb">
                                                                        <div class="image-thumb">
                                                                        </div>
                                                                        <div class="text">
                                                                          
                                                                        </div>
                                                                    </div>
                                                                </th>
                                                                 <td>
                                                                    <div class="item-body" style="padding:0px;">
                                                                        <div class="item-from">
                                                                            <h3>${  data['transportaion']['return_transportation_pick_up_location'] }</h3>
                                                                           
                                                                            <span class="date">${return_transportation_pick_up_date}</span>
                                                                           
                                                                        </div>
                                                                        <div class="item-time">
                                                                            <i class="fa fa-clock-o"></i>
                                                                            <span>Retrun</span>
                                                                        </div>
                                                                        <div class="item-to">
                                                                            <h3>${ data['transportaion']['return_transportation_drop_off_location'] }</h3>
                                                                           
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>`;
                                                            
                                                            
                                                        } 
                                                        
                                                            if(data['transporation_more'] !== null){
                                                                for(var trans = 0; trans < data['transporation_more'].length; trans++){
                                                            var date = new Date(data['transporation_more'][trans]['more_transportation_pick_up_date']);
        
                                                            var return_transportation_pick_up_date =  date.getDate()+
                                                              "/"+(date.getMonth()+1)+
                                                              "/"+date.getFullYear();
                                                             transportaion +=  `<tr>
                                                                <th>
                                                                    <div class="item-thumb">
                                                                        <div class="image-thumb">
                                                                        </div>
                                                                        <div class="text">
                                                                          
                                                                        </div>
                                                                    </div>
                                                                </th>
                                                                 <td>
                                                                    <div class="item-body" style="padding:0px;">
                                                                        <div class="item-from">
                                                                            <h3>${  data['transporation_more'][trans]['more_transportation_drop_off_location'] }</h3>
                                                                           
                                                                            <span class="date">${return_transportation_pick_up_date}</span>
                                                                           
                                                                        </div>
                                                                        <div class="item-time">
                                                                            <i class="fa fa-clock-o"></i>
                                                                            <span>Others</span>
                                                                        </div>
                                                                        <div class="item-to">
                                                                            <h3>${ data['transporation_more'][trans]['more_transportation_pick_up_location'] }</h3>
                                                                           
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>`;
                                                            
                                                                }
                                                        } 
                                                                            
                                                                        
                                                                                
                                                
                                                                                   
                                                                            
                                                                         transportaion += `</tbody>
                                                                        </table>
                                                        </div>`;
                                                        
                                       
                                  
                                                    }else{
                                                        var transportaion = '';
                                                    }
                                                                
                                                                
                                              
                                              
                                                    if(data['tour_data']['Itinerary_details'][0]['Itinerary_title'] !== null){
                                                        console.log('iterniser '+data['tour_data']['Itinerary_details'])
                                                        var iternaryDetails = JSON.parse(data['tour_data']['Itinerary_details']);
                                                        if(iternaryDetails[0]['Itinerary_title'] !== null){
                                                        console.log(iternaryDetails)
                                                         var iternary = '';
                                                            for(var i=0; i<iternaryDetails.length; i++){
                                                                
                                                                 iternary +=`<li>
                                                                                <h4>${iternaryDetails[i]['Itinerary_title']} : ${iternaryDetails[i]['Itinerary_city']} </h4>
                                                                                <p>${iternaryDetails[i]['Itinerary_content']}</p>
                                                                            </li>`
                                                            }
                                                        }else{
                                                            var iternary = '';
                                                        }
                                                  }else{
                                                     var iternary = '';
                                                  }
                                              
                                              if(data['iternery'] !== null){
                                             var iternary1 = '';
                                                for(var i=0; i<data['iternery'].length; i++){
                                                    
                                                     iternary1 +=`<li>
                                                                    <h4>${data['iternery'][i]['more_Itinerary_title']} : ${data['iternery'][i]['more_Itinerary_city']} </h4>
                                                                    <p>${data['iternery'][i]['more_Itinerary_content']}</p>
                                                                </li>`
                                                }
                                              }else{
                                                 var iternary1 = '';
                                              }
                                         
                                                if(data['tour_data']['quad_grand_total_amount'] != 0 || data['tour_data']['quad_grand_total_amount'] != 'null'){
                                                    var quadPrice  = "Quad: "+data['tour_data']['currency_symbol']+" "+data['tour_data']['quad_grand_total_amount'];
                                                }else{
                                                    var quadPrice = '';
                                                }
                                                
                                                 if(data['tour_data']['triple_grand_total_amount'] != 0 || data['tour_data']['triple_grand_total_amount'] != 'null'){
                                                    var triplePrice  = "Triple: "+data['tour_data']['currency_symbol']+" "+data['tour_data']['triple_grand_total_amount'];
                                                }else{
                                                    var triplePrice = '';
                                                }
                                                
                                                if(data['tour_data']['double_grand_total_amount'] != 0 || data['tour_data']['double_grand_total_amount'] != 'null'){
                                                    var doublePrice  = "Double: "+data['tour_data']['currency_symbol']+" "+data['tour_data']['double_grand_total_amount'];
                                                }else{
                                                    var doublePrice = '';
                                                } 
                                                
                                                
        
                                            $('#exampleModalLabel').html(data['tour_data']['title']);
                                            var tourHtml = `<div class="row" style="padding:1rem;">
                                                                <div class="col-md-1">
                                                                        <img style="width:50px" src="{{asset('public/admin_package/frontend/images/tour-info.jpg') }}">
                                                                </div>
                                                                <div class="col-md-11"><h5>Tour Information</h5></div>
                                                                <div class="col-md-12">
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                         <img src="{{ config('img_url') }}/public/uploads/package_imgs/${data['tour_data']['tour_banner_image']}" alt="">
                                                                        </div>
                                                                        <div class="col-md-8">
                                                                            <div class="row">
                                                                                 <div class="col-md-3">
                                                                                    <p style="margin-bottom:0">Tour Name:</p>
                                                                                    <p style="margin-bottom:0">Tour Price:</p>
                                                                                    <p style="margin-bottom:0">Check-In:</p>
                                                                                    <p style="margin-bottom:0">Check-Out:</p>
                                                                                    <p style="margin-bottom:0">Duration:</p>
                                                                                    <p style="margin-bottom:0">Destinations:</p>
                                                                                </div>
                                                                                <div class="col-md-9">
                                                                                    <p style="margin-bottom:0">${data['tour_data']['title']}</p>
                                                                                    <p style="margin-bottom:0">${quadPrice} / ${triplePrice} / ${doublePrice}</p>
                                                                                    <p style="margin-bottom:0">${startDate}</p>
                                                                                    <p style="margin-bottom:0">${endDate}</p>
                                                                                    <p style="margin-bottom:0">${data['tour_data']['time_duration']} Nights</p>
                                                                                    <p style="margin-bottom:0">${tours_locations}</p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                               
                                                                
                                                                <div class="col-md-1 mt-2 mb-2">
                                                                        <i class="fa fa-building" aria-hidden="true" style="font-size:2rem;"></i>
                                                                </div>
                                                                <div class="col-md-11 mt-2 mb-2"><h5>Hotel Information</h5></div>
                                                                  
                                                                 <div class="col-md-12">
                                                                    <div class="row">
                                                                        ${hotels}
                                                                    </div>
                                                                 </div>
                                                            <!--   <div class="col-md-1 mt-2 mb-2">
                                                                        <img style="width:50px" src="{{asset('public/admin_package/frontend/images/tick.png') }}">
                                                                </div>
                                                                <div class="col-md-11 mt-2 mb-2"><h5>Day By Day itenery</h5></div>
                                                                
                                                                 <div class="col-sm-12">
                                                                      <ul class="itenery-ul">
                                                                          ${iternary}
                                                                          ${iternary1}
                                                                        </ul>
                                                                 </div> !-->
                                                              
                                                                
                                                               ${flight}
                                                                
                                                               ${transportaion}
                                                                 
                                                                
                                                            </div>`
                                                            $('#Tour_data').html(tourHtml)
                                        }else{
                                             $('#exampleModalLabel').html(data['tour_data']['title']);
                                             
                                             var adultPrice = data['tour_data']['sale_price']
                                             if(data['tour_data']['child_sale_price'] != 0 && data['tour_data']['child_sale_price'] != null){
                                                 var childprice = data['tour_data']['child_sale_price'] 
                                             }else{
                                                  var childprice = data['tour_data']['sale_price'] 
                                             }
                                             
                                            if(data['tour_data']['what_expect'][0]['title'] !== null){
                                                    console.log('iterniser '+data['tour_data']['what_expect'])
                                                    var whatExpectDetails = JSON.parse(data['tour_data']['what_expect']);
                                                    if(whatExpectDetails[0]['title'] !== null){
                                                    console.log(whatExpectDetails)
                                                     var whatExpects = '';
                                                        for(var i=0; i<whatExpectDetails.length; i++){
                                                            
                                                             whatExpects +=`<li>
                                                                            <h4>${whatExpectDetails[i]['title']} </h4>
                                                                            <p>${whatExpectDetails[i]['expect_content']}</p>
                                                                        </li>`
                                                        }
                                                    }else{
                                                        var whatExpects = '';
                                                    }
                                              }else{
                                                 var whatExpects = '';
                                              }
                                              
                                            
                                              
                                             
                                                  
                                            var tourHtml = `<div class="row" style="padding:1rem;">
                                                                <div class="col-md-1">
                                                                        <img style="width:50px" src="{{asset('public/admin_package/frontend/images/tour-info.jpg') }}">
                                                                </div>
                                                                <div class="col-md-11"><h5>Activity Information</h5></div>
                                                                <div class="col-md-12">
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                         <img src="{{ config('img_url') }}/public/images/activites/${data['tour_data']['featured_image']}" alt="">
                                                                        </div>
                                                                        <div class="col-md-8">
                                                                            <div class="row">
                                                                                 <div class="col-md-3">
                                                                                    <p style="margin-bottom:0">Activtiy Name:</p>
                                                                                    <p style="margin-bottom:0">Adult Price:</p>
                                                                                    <p style="margin-bottom:0">Child Price:</p>
                                                                                    <p style="margin-bottom:0">Check-In:</p>
                                                                                    <p style="margin-bottom:0">Duration:</p>
                                                                                    <p style="margin-bottom:0">Destinations:</p>
                                                                                </div>
                                                                                <div class="col-md-9">
                                                                                    <p style="margin-bottom:0">${data['tour_data']['title']}</p>
                                                                                    <p style="margin-bottom:0">${adultPrice}</p>
                                                                                    <p style="margin-bottom:0">${childprice}</p>
                                                                                    <p style="margin-bottom:0">${data['tour_data']['activity_date']}</p>
                                                                                    <p style="margin-bottom:0">${data['tour_data']['duration']} Hours</p>
                                                                                    <p style="margin-bottom:0">${data['tour_data']['tours_locations']}</p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                               
                                                                 <hr>
                                                              <div class="col-md-1 mt-2 mb-2">
                                                                        <img style="width:50px" src="{{asset('public/admin_package/frontend/images/tick.png') }}">
                                                                </div>
                                                             <div class="col-md-11 mt-2 mb-2"><h5>Availibilty</h5></div>
                                                            
                                                             <div class="col-sm-12">
                                                                ${data['Availibilty']}
                                                             </div>
                                                                
                                                                <hr>
                                                               <div class="col-md-1 mt-2 mb-2">
                                                                        <img style="width:50px" src="{{asset('public/admin_package/frontend/images/tick.png') }}">
                                                                </div>
                                                                <div class="col-md-11 mt-2 mb-2"><h5>What To Expect</h5></div>
                                                                
                                                                 <div class="col-sm-12">
                                                                      <ul class="itenery-ul">
                                                                          ${whatExpects}
                                                                      
                                                                        </ul>
                                                                 </div> 
                                                                 
                                                                    <hr>
                                                               <div class="col-md-1 mt-2 mb-2">
                                                                        <img style="width:50px" src="{{asset('public/admin_package/frontend/images/tick.png') }}">
                                                                </div>
                                                             <div class="col-md-11 mt-2 mb-2"><h5>Meeting And Pickupt</h5></div>
                                                            
                                                             <div class="col-sm-12">
                                                                  
                                                                      ${data['tour_data']['meeting_and_pickups']}
                                                                  
                                                                   
                                                             </div> 
                                                             
                                                             
                                                                  <hr>
                                                              <div class="col-md-1 mt-2 mb-2">
                                                                        <img style="width:50px" src="{{asset('public/admin_package/frontend/images/tick.png') }}">
                                                                </div>
                                                             <div class="col-md-11 mt-2 mb-2"><h5>What's Included</h5></div>
                                                            
                                                             <div class="col-sm-12">
                                                                    <h6>Whats Included?</h6>
                                                                    </p>${data['tour_data']['whats_included']}</p>
                                                                    
                                                                     <h6>Whats Excluded?</h6>
                                                                    </p>${data['tour_data']['whats_excluded']}</p>
                                                                     
                                                                  
                                                                    </ul>
                                                             </div>
                                                             
                                                                
                                                             
                                                                 
                                                                
                                                            </div>`
                                                            $('#Tour_data').html(tourHtml)
                                        }
                                        
                                        
                                                        
                                                        
                                        // window.location.reload();
                                    }
                            });
                }
    </script>

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
                    $('#manual_form').css('display','none');
    
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
                    
                    $('#other_passenger_loader').css('display','block');
                    
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
                    
                    $('#other_passenger_loader').css('display','none');
                    $('#auto_other_forms').css('display','block');
                    $('#manual_other_forms').css('display','none');
                    
                    var result = result['result'][0];
                    var prediction = result['prediction'];
                  console.log(prediction);
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





