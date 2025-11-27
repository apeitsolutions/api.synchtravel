<!DOCTYPE html>
<html lang="en">
   <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
      
      <meta name="author" content="TechyDevs">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <title>Alhijaztours  - Travel Booking System</title>
      <!-- Favicon -->
      <link rel="icon" href="">
      <!-- Google Fonts -->
      <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&amp;display=swap" rel="stylesheet">
      <!-- Template CSS Files -->
      <link rel="stylesheet" href="https://umrahtech.com/public/assets/frontend/css/bootstrap.min.css">
      <link rel="stylesheet" href="https://umrahtech.com/public/assets/frontend/css/line-awesome.css">
      
    
     
      <link rel="stylesheet" href="https://umrahtech.com/public/assets/frontend/css/style.css">
     
   </head>
   <style>
          body{
      font-family: "Roboto", sans-serif !important;
      }
     
      .title-vochure-top .la-check-circle{
      font-size: 100px;
      color: #2e97c0;
      }
      .title-vochure{
      padding-left: 15px;
      padding-top: 15px;
      }
      .title-vochure h1{
      margin: 0;
      padding: 0;
      color: #ae8c32;
      }
      .title-vochure p{
      margin: 0;
      color: #fff;
      font-size: 24px;
      }
      .title-vochure-top .w-32{
      width:120px;
      }
      .vochure-header-border{
      border-bottom: 3px solid #ae8c32
      padding: 15px 
      }
      .vochure-header-border .logo img{
        width: 100%;
    margin-top: 30px;
      }
      .vochure-detail-section{
      border: 1px solid #ddd;
      }
      .v-heading-top{
      background-color: #ae8c32;
      padding: 5px 0;
      color: #fff;
      text-transform: capitalize;
      }
      .v-heading-icon-title{
      padding-top: 3px;
      }
      .v-heading-icon img{
      max-width: 75px;
      padding: 0 15px;
      }
      .v-heading-icon-title h3{
      border-bottom: 2px solid #ae8c32;
      color: #ae8c32;
      }
      .v-section-info {
      padding: 5px 75px;
      }
      .vochure-content .list-items-2{
      border: 1px solid;
      padding: 10px;
      }
      .vochure-content .list-items-5{
      border: 1px solid;
      padding: 10px;
      }
      .vochure-content button{
      background-color: #ae8c32;
      color: white;
      font-weight: 500;
      border-radius: 25px;
      }
      .vochure-content .list-items-5 p{
      display: inline;
      }
      .vochure-content  .col-sm-4 > ul.list-items.list-notice.clearfix {
      background-color: #efefef;
      border:none;
      }
      .vochure-content .notice{
      padding: 20px;
      font-weight: 500;
      line-height: 1.7;
      }
      .vochure-content .thank-you-section h3{
      margin-top: 2.3em;
      color:#ae8c32;
      font-weight: 300;
      } 
      .vochure-content .thank-you-section button{
      background-color: #ae8c32;
      color: white;
      font-weight: 600;
      border-radius: 25px;
      padding: 8px;
      width: 225px;
      font-size: 16px;
      margin-bottom: 25px;
      } 
      .vochure-content .vochure-detail-section .v-heading > h2{
      padding: 10px;
      padding-left: 5%;
      padding: 10px;
      padding-left: 5%;
      }
      .vochure-content .list-items-2 li {
      display: -webkit-flex;
      display: -ms-flex;
      display: flex;
      -ms-flex-align: center;
      align-items: center;
      -ms-flex-pack: justify;
      justify-content: space-between;
      }
      .vochure-content .grand-total{
      padding: 38px;
      padding-left: 30%;
      font-size: 30px;
      }
      .vochure-content .notice{
      border: none;
      font-size: 14px;
      }
      .vochure-sidebar-title h3{
      background-color: #ae8c32;
      color: #fff;
      padding: 9px 15px;
      text-transform: capitalize;
      }
      .f-20{
      font-size:20px;
      }
      .list-items li {
      margin-bottom: 0;
      }
      .list-items-3 li span{
      width: 40%;
      /* width: 250px; */
      }
      .list-items-3 li{
      justify-content: start;
      word-break: break-word;
      }
      .la-headset{
      line-height: 2;
      }
      .icon-layout-3 .info-icon{
      background-color: #ff0000;
      }
      .table td, .table th {
      padding: 5px;
      }
      @media(max-width: 991px) {
      .title-vochure p {
      font-size: 14px;
      }
      .title-vochure h1 {
      font-size: 20px; 
      }
      .vochure-header-border .logo img {
      width: 85px;
      }
      .title-vochure-top .la-check-circle {
      font-size: 60px;
      }
      .title-vochure {
      padding-top: 5px;
      }
      }
      @media (max-width: 575.98px){
      .vochure-header-border .logo,
      .vochure-header-border .logo a
      {
      width: 100%;
      text-align: center;
      padding-bottom: 10px;
      }
      .list-items-3 li span{
      width: 100%;
      }
      .list-items-3 li{
      display: block;
      }
      .v-section-info {
      padding: 5px 20px;
      }
      .v-heading-icon-title h3 {
      font-size: 20px;
      }
      .v-heading-icon-title h2 {
      font-size: 22px;
      }
      .v-heading-icon-title{
      padding-top: 10px;
      }
      }
      .la-check-circle {
      /*color: #ffffff;*/
      background: none;
      }
      body{
      font-size: 14px;
      }
      .additionalServices_box {
      border: solid 1px cornflowerblue;
      padding: 10px;
      }
      .voucher-modal .modal-content{
      border-radius: 0px;
      }
      .voucher-modal .modal-footer{
      border-top: 0px;
      }
      .table{
      font-size: 12px;
      margin-bottom: 0;
      }
      .otp-top .btn{
      background: #113669;
      color: #fff;
      font-size: 12px;
      padding: 4px 10px;  
      }
      .otp-top .btn2{
      background: #068a62;
      }
      .itenery-ul{
        padding-left: 15px;
        padding-top: 15px;
        list-style-type: none;
      }
      .itenery-ul h4{
        font-size:20px;
      }
      .vochure-header {
        background-image:url("{{asset('public/admin_package/frontend/images/bg-vochure.jpg')}}");
      }
   </style>
   <body>
      <!-- start cssload-loader -->
      <div class="preloader" id="preloader">
         <div class="loader">
            <svg class="spinner" viewBox="0 0 50 50">
               <circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
            </svg>
         </div>
      </div>
      <!-- end cssload-loader -->
      <!-- ================================
         START MODAL AREA
         ================================= -->
      <div class="container">
         <div class="row">
            <div class="col-12 col-md-12">
               <section class="modal_umrah_tech">
                  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                     <div class="modal-dialog modal-md">
                        <div class="modal-content">
                           <div class="modal-header justify-content-center">
                           </div>
                           <div class="modal-body">
                              <div class="container">
                                 <div class="row">
                                    <div class="col-12 col-md-12 text-center">
                                       <h1>Are You Sure!</h1>
                                       <h4>Your Reservation will be Cancelled By doing this Action</h4>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="modal-footer text-center">
                              <button onclick=""  type="button" class="btn btn-secondary" data-dismiss="modal">Ok</button>
                           </div>
                        </div>
                     </div>
                  </div>
               </section>
            </div>
         </div>
      </div>
      <!-- ================================
         END MODAL AREA
         ================================= -->
      <!--<script>-->
      <!--   localStorage.setItem('total_amount', 10933.17);-->
      <!--</script>-->
      <!--End PHP-->
      @if($errors->any())
<h4>{{$errors->first()}}</h4>
@endif



@if($slug == "webbeds")
@if(isset($data))

      <div class="vochure-header">
         <div class="container">
            <div class="vochure-header-border">
               <div class="row">
                  <div class="col-sm-4">
                     <div class="logo">
                        <a href="#"><img src="{{ asset('public/admin_package/frontend/images/logo.png') }}"  alt="logo"></a>
                     </div>
                  </div>
                  <div class="col-sm-8">
                     <div class="title-vochure-top float-right">
                        <div class="float-left text-right">
                           <img class="w-32" src="{{asset('public/admin_package/frontend/images/confirm.png') }}" alt="Booking Confirm">
                        </div>
                        <div class="title-vochure float-left">
                           <h1>Booking Confirmation</h1>
                           <p>Thanks for Booking with Alhijaz Tours</p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="vochure-content">
         <div class="container">
            <div class="button-group-voucher" style="display: flex; justify-content: flex-end;">
               <div class="text-right mt-3 mr-2">
                  <button type="submit" class="btn btn-secondary" onclick="window.print()">Print  Voucher </button>
               </div>
               <!--<div class="text-right mt-3 mr-2">-->
               <!--   <a href="https://umrahtech.com/invoice/165389811372985682"><button type="button" class="btn btn-secondary">Invoice</button></a>-->
               <!--</div>-->
               <!--<div class="text-right mt-3">-->
               <!--   <input type="hidden" name="" value="165389811372985682">-->
               <!--   <input type="hidden" id="invoice_number" value="165389811372985682" />-->
               <!--   <a href="https://umrahtech.com/voucher-ar/165389811372985682"><button type="button" class="btn btn-secondary">Arabic</button></a>-->
               <!--</div>-->
            </div>
            <div class="row">
               <div class="col-md-8 col-sm-12">
                    
                  <section class="vochure-detail-section mt-3">
                     <div class="v-heading-icon v-heading-top clearfix">
                        <div class="float-left">
                           <img src="{{asset('public/admin_package/frontend/images/icon-tour.png') }}">
                        </div>
                        <div class="v-heading-icon-title float-left">
                           <h2>{{$data->itinerary->containing->product->serviceName ?? ""}}</h2>
                        </div>
                     </div>
                     <div class="v-heading-icon clearfix mt-3">
                        <div class="float-left">
                           <img src="{{asset('public/admin_package/frontend/images/tour-info.jpg') }}">
                        </div>
                        <div class="row">
                           <div class="col-md-8">
                              <div class="v-heading-icon-title float-left">
                                 <h3>Hotel Information</h3>
                              </div>
                           </div>
                           <div class="col-md-4">
                           </div>
                        </div>
                     </div>
                     <div class="clearfix v-section-info">
                        <ul class="list-items list-items-3 list-items-4  clearfix" >
                           <li>
                              <span class="text-black font-weight-bold">Hotel Name:</span>
                              <p class="f-20 text-black font-weight-bold" id="makkah_booking_status">{{$data->itinerary->containing->product->serviceName ?? ""}}</p>
                           </li>
                          
                           <li><span class="text-black font-weight-bold">Nights:</span>{{$data->itinerary->containing->product->numberOfNights ?? ""}}</li>
                           <li><span class="text-black font-weight-bold">Location:</span>{{$data->itinerary->containing->product->serviceLocation ?? ""}}</li>
                           <li><span class="text-black font-weight-bold">Price:</span>{{$data->itinerary->containing->product->servicePrice ?? ""}}</li>
                           
                                                           
                           <li><span class="text-black font-weight-bold">Check-In:</span>{{$data->itinerary->containing->product->fromShort ?? ""}}</li>
                           <li><span class="text-black font-weight-bold">Check-Out:</span>{{$data->itinerary->containing->product->toShort ?? ""}}</li>
                           <li><span class="text-black font-weight-bold">Currency:</span> {{$data->itinerary->containing->product->currencyShort ?? ""}}</li>
                           <li><span class="text-black font-weight-bold">Booking Status:</span> {{$data->itinerary->containing->product->booked ?? ""}}</li>
                           <li><span class="text-black font-weight-bold">Customer Name:</span> {{$data->itinerary->containing->product->passengersDetails->passenger->firstName. $data->itinerary->containing->product->passengersDetails->passenger->lastName ?? ""}}</li>
                           <li><span class="text-black font-weight-bold">Customer Nationality:</span> {{$data->itinerary->containing->product->passengersDetails->passenger->passengerNationality ?? ""}}</li>
                           
                      
                         
                        </ul>
                     </div>
                     
                     <div class="v-heading-icon clearfix mt-3">
                        <div class="float-left">
                           <img src="https://umrahtech.com/public/assets/frontend/images/Icons/booking-details.png">
                        </div>
                        <div class="v-heading-icon-title float-left">
                            <h3>Room Distription</h3>
                      </div>
                    </div>  
                    
                  
                  
                   
                    <ul class="itenery-ul">
                        
                        <li>
                            <h4>{{$data->itinerary->containing->product->roomName ?? ""}} ,<br>  {{$data->itinerary->containing->product->adults ?? ""}}-AD ,<br> {{$data->itinerary->containing->product->children->{'@attributes'}->no ?? ""}}-CH  </h4>
                            <p>{{$data->itinerary->containing->product->servicePrice ?? ""}}</p>
                        </li>
                      
                        
                       
                    </ul> 
                    
                    <div class="v-heading-icon clearfix mt-3">
                        <div class="float-left">
                           <img src="https://umrahtech.com/public/assets/frontend/images/Icons/booking-details.png">
                        </div>
                        <div class="v-heading-icon-title float-left">
                            <h3>Rules</h3>
                      </div>
                    </div>  
                    
                  
                  
                   
                    <ul class="itenery-ul">
                        
                      @foreach($data->itinerary->containing->product->cancellationRules->rule as $rule)
                                                     * <li><span>From This Date: {{$rule->fromDate ?? ""}} .To This Date: {{$rule->toDateDetails ?? ""}}.Duduction Charges Will Be: {{$rule->charge ?? ""}} & {{$rule->amendCharge ?? ""}} AmendCharge </span></li>
                                                    @endforeach
                       
                    </ul>
                
                
                 
                  </section>
                 
                  <!--apply for umrah visa-->
               </div>
               <!--Sidrbar start-->
               
           
               <div class="col-md-4 col-sm-12">
                  <div class="v-heading-icon clearfix mt-3" style="background: #ae8c32" >
                     <div class="float-left">
                        <img src="{{asset('public/admin_package/frontend/images/lead.png') }}">
                     </div>
                     <div class="v-heading-icon-title float-left" style="">
                        <h3 style="font-size: 24px;margin-top: 7px;color: #fff;">Lead Passenger Details</h3>
                     </div>
                  </div>
                  <div class="clearfix v-section-info" style="padding: 5px 10px;border: 1px solid #5d646d;">
                     <ul class="list-items list-items-3 list-items-4  clearfix" >
                        <li>
                           <span class="text-black font-weight-bold">Passport No:</span> 
                           <p class="f-20 text-black font-weight-bold">{{Session::get('webbedspass')}}</p>
                        </li>
                        <li><span class="text-black font-weight-bold">Nationality:</span>{{$data->itinerary->containing->product->passengersDetails->passenger->passengerNationality ?? ""}}</li>
                        <li><span class="text-black font-weight-bold">Full Name:</span>{{$data->itinerary->containing->product->passengersDetails->passenger->firstName. $data->itinerary->containing->product->passengersDetails->passenger->lastName ?? ""}}</li>
                        <li><span class="text-black font-weight-bold">Gender:</span>{{Session::get('webbedsgender')}}</li>
                      
                        <li class="d-block">
                           <div class="row">
                              <div class="col-sm-5">
                                 <span class="text-black font-weight-bold">Phone Number:</span>
                              </div>
                              <div class="col-sm-7">
                                phone
                                 
                              </div>
                           </div>
                        </li>
                        
                        <li class="d-block">
                           <div class="row">
                              <div class="col-sm-5">
                                 <span class="text-black font-weight-bold">Email:</span>
                              </div>
                              <div class="col-sm-7">
                               {{Session::get('webbedsemail')}}
                                 
                              </div>
                           </div>
                        </li>
                     </ul>
                  </div>
                 
                  <!-- end icon-box -->
                  <div class="vochure-sidebar-title mt-3 text-center">
                     <h3>Payment Details</h3>
                  </div>
                  <ul class="list-items list-items-2 clearfix" >
                     
                     <li><span class="text-black font-weight-bold">{{$data->itinerary->containing->product->serviceName ?? ""}}</span>{{$data->itinerary->containing->product->currencyShort ?? ""}} {{$data->itinerary->containing->product->servicePrice ?? ""}} </li>
                  
                  </ul>
                  <div class="vochure-sidebar-title mt-5 text-center">
                     <h3>Grand Total</h3>
                  </div>
                  <ul class="list-items list-items-2 clearfix" >
                     <div class="grand-total">
                       
                        <li class="text-black font-weight-bold">{{$data->itinerary->containing->product->currencyShort ?? ""}}  {{$data->itinerary->containing->product->servicePrice ?? ""}}</li>
                     </div>
                  </ul>
                
                 
                
                  <div class="vochure-sidebar-title mt-5 text-center">
                     <h3>Contact Information</h3>
                  </div>
                  <ul class="list-items list-items-5  clearfix">
                     <li>
                        <p class="text-black font-weight-bold">Feel free to contact us any time.</p>
                     </li>
                     <li>
                        <span class="text-black font-weight-bold mr-3">Phone:</span>
                        <p>0121 777 2522</p>
                     </li>
                     <li>
                        <span class="text-black font-weight-bold mr-3">Email:</span>
                        <p>info@alhijaztours.net</p>
                     </li>
                     <li>
                        <span class="text-black font-weight-bold mr-3">Address:</span>
                        <p>1a Nansen Road Sparkhill
Birmingham B11 4DR UK</p>
                     </li>
                    
                  </ul>
              
               </div>
            </div>
         </div>
      </div>
      
@endif
@endif





@if($slug == "hotelbeds")
@if(isset($hotelbed_data))
                   
                   <div class="vochure-header">
         <div class="container">
            <div class="vochure-header-border">
               <div class="row">
                  <div class="col-sm-4">
                     <div class="logo">
                        <a href="#"><img src="{{ asset('public/admin_package/frontend/images/logo.png') }}"  alt="logo"></a>
                     </div>
                  </div>
                  <div class="col-sm-8">
                     <div class="title-vochure-top float-right">
                        <div class="float-left text-right">
                           <img class="w-32" src="{{asset('public/admin_package/frontend/images/confirm.png') }}" alt="Booking Confirm">
                        </div>
                        <div class="title-vochure float-left">
                           <h1>Booking Confirmation</h1>
                           <p>Thanks for Booking with Alhijaz Tours</p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="vochure-content">
         <div class="container">
            <div class="button-group-voucher" style="display: flex; justify-content: flex-end;">
               <div class="text-right mt-3 mr-2">
                  <button type="submit" class="btn btn-secondary" onclick="window.print()">Print  Voucher </button>
               </div>
               <!--<div class="text-right mt-3 mr-2">-->
               <!--   <a href="https://umrahtech.com/invoice/165389811372985682"><button type="button" class="btn btn-secondary">Invoice</button></a>-->
               <!--</div>-->
               <!--<div class="text-right mt-3">-->
               <!--   <input type="hidden" name="" value="165389811372985682">-->
               <!--   <input type="hidden" id="invoice_number" value="165389811372985682" />-->
               <!--   <a href="https://umrahtech.com/voucher-ar/165389811372985682"><button type="button" class="btn btn-secondary">Arabic</button></a>-->
               <!--</div>-->
            </div>
            <div class="row">
               <div class="col-md-8 col-sm-12">
                    
                  <section class="vochure-detail-section mt-3">
                     <div class="v-heading-icon v-heading-top clearfix">
                        <div class="float-left">
                           <img src="{{asset('public/admin_package/frontend/images/icon-tour.png') }}">
                        </div>
                        <div class="v-heading-icon-title float-left">
                           <h2>{{$hotelbed_data->hotel->name ?? ""}}</h2>
                        </div>
                     </div>
                     <div class="v-heading-icon clearfix mt-3">
                        <div class="float-left">
                           <img src="{{asset('public/admin_package/frontend/images/tour-info.jpg') }}">
                        </div>
                        <div class="row">
                           <div class="col-md-8">
                              <div class="v-heading-icon-title float-left">
                                 <h3>Hotel Information</h3>
                              </div>
                           </div>
                           <div class="col-md-4">
                           </div>
                        </div>
                     </div>
                     <div class="clearfix v-section-info">
                        <ul class="list-items list-items-3 list-items-4  clearfix" >
                           <li>
                              <span class="text-black font-weight-bold">Hotel Name:</span>
                              <p class="f-20 text-black font-weight-bold" id="makkah_booking_status">{{$hotelbed_data->hotel->name ?? ""}}</p>
                           </li>
                          
                           
                           <li><span class="text-black font-weight-bold">Location:</span>{{$hotelbed_data->hotel->destinationName ?? ""}}</li>
                       
                           
                                                           
                           <li><span class="text-black font-weight-bold">Check-In:</span>{{$hotelbed_data->hotel->checkIn ?? ""}}</li>
                           <li><span class="text-black font-weight-bold">Check-Out:</span>{{$hotelbed_data->hotel->checkOut ?? ""}}</li>
                          
                           <li><span class="text-black font-weight-bold">Booking Status:</span> {{$hotelbed_data->status ?? ""}}</li>
                           <li><span class="text-black font-weight-bold">Customer Name:</span> {{$hotelbed_data->holder->name." ".$hotelbed_data->holder->surname }}</li>
                           
                           
                      
                         
                        </ul>
                     </div>
                     
                     <div class="v-heading-icon clearfix mt-3">
                        <div class="float-left">
                           <img src="https://umrahtech.com/public/assets/frontend/images/Icons/booking-details.png">
                        </div>
                        <div class="v-heading-icon-title float-left">
                            <h3>Room Distription</h3>
                      </div>
                    </div>  
                    
                  
                  
                   
                    <ul class="itenery-ul">
                          @foreach($hotelbed_data->hotel->rooms as $room)
                                        @foreach($room->rates as $ratez)
                        <li>
                            <h4>{{$room->name ?? ""}} , {{$ratez->adults ?? ""}}=AD , {{$ratez->children ?? ""}}=CH</h4>
                            <h5>BordType: {{$ratez->boardName ?? ""}}</h5>
                            <h5>{{$ratez->net ?? ""}} {{$hotelbed_data->currency ?? ""}}</h5>
                        </li>
                        <p>{{$ratez->rateComments ?? ""}}</p>
                      
                         @endforeach
                                        @endforeach
                       
                    </ul> 
                    <div class="v-heading-icon clearfix mt-3">
                        <div class="float-left">
                           <img src="https://umrahtech.com/public/assets/frontend/images/Icons/booking-details.png">
                        </div>
                        <div class="v-heading-icon-title float-left">
                            <h3>Rules</h3>
                      </div>
                    </div>  
                    
                  
                  
                   
                    <ul class="itenery-ul">
                        
                     
                                                     @foreach($hotelbed_data->hotel->rooms as $room)
                                                     @foreach($room->rates as $ratez)
                                                     @foreach($ratez->cancellationPolicies as $cpolicy)
                                                     * <li><span>From This Date: {{$cpolicy->from ?? ""}}.Duduction Charges Will Be: {{$cpolicy->amount ?? ""}} </span></li>
                                                     @endforeach
                                                     @endforeach
                                                     @endforeach
                    </ul>
                
                
                 <p>{{$hotelbed_data->invoiceCompany->company}}</p>
                  </section>
                 
                  <!--apply for umrah visa-->
               </div>
               <!--Sidrbar start-->
               
           
               <div class="col-md-4 col-sm-12">
                  <div class="v-heading-icon clearfix mt-3" style="background: #ae8c32" >
                     <div class="float-left">
                        <img src="{{asset('public/admin_package/frontend/images/lead.png') }}">
                     </div>
                     <div class="v-heading-icon-title float-left" style="">
                        <h3 style="font-size: 24px;margin-top: 7px;color: #fff;">Lead Passenger Details</h3>
                     </div>
                  </div>
                  <div class="clearfix v-section-info" style="padding: 5px 10px;border: 1px solid #5d646d;">
                     <ul class="list-items list-items-3 list-items-4  clearfix" >
                        <li>
                           <span class="text-black font-weight-bold">Passport No:</span> 
                           <p class="f-20 text-black font-weight-bold">{{Session::get('hotelbedspass')}}</p>
                        </li>
                        <li><span class="text-black font-weight-bold">Address:</span>{{Session::get('hotelbedsaddress')}}</li>
                        <li><span class="text-black font-weight-bold">Full Name:</span>{{$hotelbed_data->holder->name." ".$hotelbed_data->holder->surname }}</li>
                        <li><span class="text-black font-weight-bold">Gender:</span>{{Session::get('hotelbedsgender')}}</li>
                      
                        <li class="d-block">
                           <div class="row">
                              <div class="col-sm-5">
                                 <span class="text-black font-weight-bold">Phone Number:</span>
                              </div>
                              <div class="col-sm-7">
                                {{Session::get('hotelbedsphone')}}
                                 
                              </div>
                           </div>
                        </li>
                        
                        <li class="d-block">
                           <div class="row">
                              <div class="col-sm-5">
                                 <span class="text-black font-weight-bold">Email:</span>
                              </div>
                              <div class="col-sm-7">
                                email
                                 
                              </div>
                           </div>
                        </li>
                     </ul>
                  </div>
                 
                  <!-- end icon-box -->
                  <div class="vochure-sidebar-title mt-3 text-center">
                     <h3>Payment Details</h3>
                  </div>
                  <ul class="list-items list-items-2 clearfix" >
                     
                     <li><span class="text-black font-weight-bold">{{$data->itinerary->containing->product->serviceName ?? ""}}</span>{{$data->itinerary->containing->product->currencyShort ?? ""}} {{$data->itinerary->containing->product->servicePrice ?? ""}} </li>
                  
                  </ul>
                  <div class="vochure-sidebar-title mt-5 text-center">
                     <h3>Grand Total</h3>
                  </div>
                  <ul class="list-items list-items-2 clearfix" >
                     <div class="grand-total">
                       
                        <li class="text-black font-weight-bold">{{$data->itinerary->containing->product->currencyShort ?? ""}}  {{$data->itinerary->containing->product->servicePrice ?? ""}}</li>
                     </div>
                  </ul>
                
                 
                
                  <div class="vochure-sidebar-title mt-5 text-center">
                     <h3>Contact Information</h3>
                  </div>
                  <ul class="list-items list-items-5  clearfix">
                     <li>
                        <p class="text-black font-weight-bold">Feel free to contact us any time.</p>
                     </li>
                     <li>
                        <span class="text-black font-weight-bold mr-3">Phone:</span>
                        <p>0121 777 2522</p>
                     </li>
                     <li>
                        <span class="text-black font-weight-bold mr-3">Email:</span>
                        <p>info@alhijaztours.net</p>
                     </li>
                     <li>
                        <span class="text-black font-weight-bold mr-3">Address:</span>
                        <p>1a Nansen Road Sparkhill
Birmingham B11 4DR UK</p>
                     </li>
                    
                  </ul>
              
               </div>
            </div>
         </div>
      </div>
                   
                                
@endif
@endif



@if($slug == "travellanda")
@if(isset($booked_data))

      <div class="vochure-header">
         <div class="container">
            <div class="vochure-header-border">
               <div class="row">
                  <div class="col-sm-4">
                     <div class="logo">
                        <a href="#"><img src="{{ asset('public/images/hotels/logo.png') }}"  alt="logo"></a>
                     </div>
                  </div>
                  <div class="col-sm-8">
                     <div class="title-vochure-top float-right">
                        <div class="float-left text-right">
                           <img class="w-32" src="{{asset('public/images/hotels/confirm.png') }}" alt="Booking Confirm">
                        </div>
                        <div class="title-vochure float-left">
                           <h1>Booking Confirmation</h1>
                           <p>Thanks for Booking with Alhijaz Tours</p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="vochure-content">
         <div class="container">
            <div class="button-group-voucher" style="display: flex; justify-content: flex-end;">
               <div class="text-right mt-3 mr-2">
                  <button type="submit" class="btn btn-secondary" onclick="window.print()">Print  Voucher </button>
               </div>
               <!--<div class="text-right mt-3 mr-2">-->
               <!--   <a href="https://umrahtech.com/invoice/165389811372985682"><button type="button" class="btn btn-secondary">Invoice</button></a>-->
               <!--</div>-->
               <!--<div class="text-right mt-3">-->
               <!--   <input type="hidden" name="" value="165389811372985682">-->
               <!--   <input type="hidden" id="invoice_number" value="165389811372985682" />-->
               <!--   <a href="https://umrahtech.com/voucher-ar/165389811372985682"><button type="button" class="btn btn-secondary">Arabic</button></a>-->
               <!--</div>-->
            </div>
            <div class="row">
               <div class="col-md-8 col-sm-12">
                    
                  <section class="vochure-detail-section mt-3">
                     <div class="v-heading-icon v-heading-top clearfix">
                        <div class="float-left">
                           <img src="{{asset('public/images/hotels/icon-tour.png') }}">
                        </div>
                        <div class="v-heading-icon-title float-left">
                           <h2>{{$booked_data->HotelName ?? ""}}</h2>
                        </div>
                     </div>
                     <div class="v-heading-icon clearfix mt-3">
                        <div class="float-left">
                           <img src="{{asset('public/images/hotels/tour-info.jpg') }}">
                        </div>
                        <div class="row">
                           <div class="col-md-8">
                              <div class="v-heading-icon-title float-left">
                                 <h3>Hotel Information</h3>
                              </div>
                           </div>
                           <div class="col-md-4">
                           </div>
                        </div>
                     </div>
                     <div class="clearfix v-section-info">
                        <ul class="list-items list-items-3 list-items-4  clearfix" >
                           <li>
                              <span class="text-black font-weight-bold">Hotel Name:</span>
                              <p class="f-20 text-black font-weight-bold" id="makkah_booking_status">{{$booked_data->HotelName ?? ""}}</p>
                           </li>
                          
                           
                           <li><span class="text-black font-weight-bold">Location:</span>{{$booked_data->City ?? ""}}</li>
                       
                           
                                                           
                           <li><span class="text-black font-weight-bold">Check-In:</span>{{$booked_data->CheckInDate ?? ""}}</li>
                           <li><span class="text-black font-weight-bold">Check-Out:</span>{{$booked_data->CheckOutDate ?? ""}}</li>
                          
                           <li><span class="text-black font-weight-bold">Booking Status:</span> {{$booked_data->BookingStatus ?? ""}},{{$booked_data->BookingTime ?? ""}} </li>
                           <li><span class="text-black font-weight-bold">Customer Name:</span> {{$booked_data->LeaderName ?? ""}}</li>
                           <li><span class="text-black font-weight-bold">CancellationDeadline:</span> {{$booked_data->CancellationDeadline ?? ""}}</li>
                           
                           
                      
                         
                        </ul>
                     </div>
                     
                     <div class="v-heading-icon clearfix mt-3">
                        <div class="float-left">
                           <img src="https://umrahtech.com/public/assets/frontend/images/Icons/booking-details.png">
                        </div>
                        <div class="v-heading-icon-title float-left">
                            <h3>Room Distription</h3>
                      </div>
                    </div>  
                    
                  
                  
                   
                    <ul class="itenery-ul">
                         
                        <li>
                            <h4>{{$booked_data->Rooms->Room->RoomName ?? ""}} ,<br> {{$booked_data->Rooms->Room->NumAdults ?? ""}}-ADULT ,<br> {{$booked_data->Rooms->Room->NumChildren}}-CHILD</h4>
                            <h5>Price: {{$booked_data->TotalPrice ?? ""}}</h5>
                            <h5>BoardType: {{$booked_data->BoardType ?? ""}}</h5>
                            
                        </li>
                        <div class="v-heading-icon-title float-left">
                            <h3>Room Alert</h3>
                      </div>
                      <br>
                        <p>{{$booked_data->Alerts->Alert ?? ""}}</p>
                      
                        
                    </ul> 
                    <div class="v-heading-icon clearfix mt-3">
                        <div class="float-left">
                           <img src="https://umrahtech.com/public/assets/frontend/images/Icons/booking-details.png">
                        </div>
                        <div class="v-heading-icon-title float-left">
                            <h3>Rules</h3>
                      </div>
                    </div>  
                    
                  
                  
                   
                    <ul class="itenery-ul">
                        
                     
                                                     @if(isset($booked_data->Policies->Policy))
                                                    @foreach($booked_data->Policies->Policy as $policee)
                                                   * <li><span>From This Date: {{$policee->From}}.Duduction Charges Will Be: {{$policee->Value}} in {{$policee->Type}} </span></li>
                                                    @endforeach
                                                    @else
                                                    <span>Policy Not Available For This Property.</span>
                                                    @endif
                    </ul>
                
                
              
                  </section>
                 
                  <!--apply for umrah visa-->
               </div>
               <!--Sidrbar start-->
               
           
               <div class="col-md-4 col-sm-12">
                  <div class="v-heading-icon clearfix mt-3" style="background: #ae8c32" >
                     <div class="float-left">
                        <img src="{{asset('public/images/hotels/lead.png') }}">
                     </div>
                     <div class="v-heading-icon-title float-left" style="">
                        <h3 style="font-size: 24px;margin-top: 7px;color: #fff;">Lead Passenger Details</h3>
                     </div>
                  </div>
                  <div class="clearfix v-section-info" style="padding: 5px 10px;border: 1px solid #5d646d;">
                     <ul class="list-items list-items-3 list-items-4  clearfix" >
                        <li>
                           <span class="text-black font-weight-bold">Passport No:</span> 
                           <p class="f-20 text-black font-weight-bold">{{$customer_decoded->passport ?? ""}}</p>
                        </li>
                        <li><span class="text-black font-weight-bold">Address:</span>{{$customer_decoded->Address ?? ""}}</li>
                        <li><span class="text-black font-weight-bold">Full Name:</span>{{$booked_data->LeaderName ?? ""}}</li>
                        <li><span class="text-black font-weight-bold">Gender:</span>{{$customer_decoded->gender ?? ""}}</li>
                      
                        <li class="d-block">
                           <div class="row">
                              <div class="col-sm-5">
                                 <span class="text-black font-weight-bold">Phone Number:</span>
                              </div>
                              <div class="col-sm-7">
                                {{$customer_decoded->phone ?? ""}}
                                 
                              </div>
                           </div>
                        </li>
                        
                        <li class="d-block">
                           <div class="row">
                              <div class="col-sm-5">
                                 <span class="text-black font-weight-bold">Email:</span>
                              </div>
                              <div class="col-sm-7">
                                {{$customer_decoded->email ?? ""}}
                                 
                              </div>
                           </div>
                        </li>
                     </ul>
                  </div>
                 
                  <!-- end icon-box -->
                  <div class="vochure-sidebar-title mt-3 text-center">
                     <h3>Payment Details</h3>
                  </div>
                  <ul class="list-items list-items-2 clearfix" >
                     
                     <li><span class="text-black font-weight-bold"></span>To Be Paid</li>
                  
                  </ul>
                  <div class="vochure-sidebar-title mt-5 text-center">
                     <h3>Grand Total</h3>
                  </div>
                  <ul class="list-items list-items-2 clearfix" >
                     <div class="grand-total">
                       
                        <li class="text-black font-weight-bold">{{$booked_data->TotalPrice ?? ""}} {{$booked_data->Currency ?? ""}} </li>
                     </div>
                  </ul>
                
                 
                
                  <div class="vochure-sidebar-title mt-5 text-center">
                     <h3>Contact Information</h3>
                  </div>
                  <ul class="list-items list-items-5  clearfix">
                     <li>
                        <p class="text-black font-weight-bold">Feel free to contact us any time.</p>
                     </li>
                     <li>
                        <span class="text-black font-weight-bold mr-3">Phone:</span>
                        <p>0121 777 2522</p>
                     </li>
                     <li>
                        <span class="text-black font-weight-bold mr-3">Email:</span>
                        <p>info@alhijaztours.net</p>
                     </li>
                     <li>
                        <span class="text-black font-weight-bold mr-3">Address:</span>
                        <p>1a Nansen Road Sparkhill
Birmingham B11 4DR UK</p>
                     </li>
                    
                  </ul> 
                  <div class="vochure-sidebar-title mt-5 text-center">
                     <h3>Booking Cancellation</h3>
                  </div>
                  <ul class="list-items list-items-5  clearfix">
                     <li>
                         @if($booked_data->BookingStatus == 'Confirmed')
                                         <div class="col-lg-12">
                                             <div class="section-block mt-3"></div>
                                             <div class="btn-box text-center pt-4">
                                                 <a href="{{URL::to('')}}/booking_cancle/{{$booked_data->BookingReference}}/{{$slug}}" class="theme-btn">Cancel Your Booking</a>
                                             </div>
                                         </div>
                                         @endif
                     </li>
                    
                    
                  </ul>
              
               </div>
            </div>
         </div>
      </div>

@endif
@endif
  
      <script src="https://umrahtech.com/public/assets/frontend/js/jquery-3.4.1.min.js"></script>
      <script src="https://umrahtech.com/public/assets/frontend/js/jquery-ui.js"></script>
      <script src="https://umrahtech.com/public/assets/frontend/js/popper.min.js"></script>
      <script src="https://umrahtech.com/public/assets/frontend/js/bootstrap.min.js"></script>
      <script src="https://umrahtech.com/public/assets/frontend/js/main.js"></script>
      
   
     
  
     
    
     
      
     
     
      
   </body>
</html>