@extends('template/frontend/userdashboard/layout/default')

@section('content')


     
      <!-- Template CSS Files -->
    
      
    
     
      <link rel="stylesheet" href="https://umrahtech.com/public/assets/frontend/css/style.css">
     

   <style>
          body{
      font-family: "Roboto", sans-serif !important;
      }
     
      .title-vochure-top .la-check-circle{count
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
   
   <?php
   if($slug === 'hotelbeds')
   {
       $hotelbeddetailRQ=json_decode($data->hotelbeddetailRQ);
       $hotelbedSelectionRS=json_decode($data->hotelbedSelectionRS);
       $lead_passenger_details=json_decode($data->lead_passenger_details);
       $other_passenger_details=json_decode($data->other_passenger_details);
       
       $hotelbedcnfrmRQ=$data->hotelbedcnfrmRQ;
       
    //   print_r($hotelbeddetailRQ);die();
       
   ?>
   
<div class="vochure-content">
         <div class="container">
            
            <div class="row">
               <div class="col-md-8 col-sm-12">
                     
                  <section class="vochure-detail-section mt-3">
                     <div class="v-heading-icon v-heading-top clearfix">
                        <div class="float-left">
                           <img src="https://alhijaztours.net/public/admin_package/frontend/images/icon-tour.png">
                        </div>
                        <div class="v-heading-icon-title float-left">
                           <h2></h2>
                        </div>
                     </div>
                     <div class="v-heading-icon clearfix mt-3">
                        <div class="float-left">
                           <img src="https://alhijaztours.net/public/admin_package/frontend/images/tour-info.jpg">
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
                        <ul class="list-items list-items-3 list-items-4  clearfix">
                           <li>
                              <span class="text-black font-weight-bold">Hotel Name:</span> {{$hotelbedSelectionRS->hotel->name}}
                              
                              <p class="f-20 text-black font-weight-bold" id="makkah_booking_status"></p>
                           </li>
                           
                            <li>
                              <span class="text-black font-weight-bold">Hotel Address:</span>{{$hotelbedSelectionRS->hotel->destinationName}}
                              
                              <p class="f-20 text-black font-weight-bold" id="makkah_booking_status"></p>
                           </li>
                           
                            <li>
                              <span class="text-black font-weight-bold">Country Name:</span>{{$hotelbedSelectionRS->hotel->destinationName}}
                              
                              <p class="f-20 text-black font-weight-bold" id="makkah_booking_status"></p>
                           </li>
                           
                            <li>
                              <span class="text-black font-weight-bold">City:</span>{{$hotelbedSelectionRS->hotel->zoneName}}
                              
                              <p class="f-20 text-black font-weight-bold" id="makkah_booking_status"></p>
                           </li>
                           
                             <li>
                              <span class="text-black font-weight-bold">Hotel Ratings :</span>{{$hotelbedSelectionRS->hotel->categoryName}}
                              
                              <p class="f-20 text-black font-weight-bold" id="makkah_booking_status"></p>
                           </li>
                           <li>
                              <span class="text-black font-weight-bold">Hotel Price:</span>{{$hotelbedSelectionRS->hotel->currency}} {{$hotelbedSelectionRS->hotel->totalNet}}
                            
                              
                              
                              
                              
                              <p class="f-20 text-black font-weight-bold" id="makkah_booking_status"></p>
                           </li>
                           
                           <li>
                              <span class="text-black font-weight-bold">Booking Status:</span>{{$data->booking_status}}
                            
                              
                              
                              
                              
                              <p class="f-20 text-black font-weight-bold" id="makkah_booking_status"></p>
                           </li>
                           
                           
                           
                           <li>
                              <span class="text-black font-weight-bold">Booking Confirmation No:</span>{{$data->search_id}}
                              
                              <p class="f-20 text-black font-weight-bold" id="makkah_booking_status"></p>
                           </li>
                         
                              <li>
                              <span class="text-black font-weight-bold">Booking Date:</span>{{$data->created_at}}
                              
                              <p class="f-20 text-black font-weight-bold" id="makkah_booking_status"></p>
                           </li> 
                          
                          
                           <li><span class="text-black font-weight-bold">Passengar:</span>Adults  {{$data->total_passenger}}, Child  {{$data->child}}</li>
                           
                           <!--<li><span class="text-black font-weight-bold">Hotel Price:</span></li>-->
                           
                           
                                                           
                           <li><span class="text-black font-weight-bold">Check-In:</span>{{$data->check_in}}</li>
                           <li><span class="text-black font-weight-bold">Check-Out:</span>{{$data->check_out}}</li>
                           
                                                       
                          
                           <?php
                           
                          $startTimeStamp = strtotime($data->check_in);                                   
                            $endTimeStamp = strtotime($data->check_out);                                     

                            $timeDiff = abs($endTimeStamp - $startTimeStamp);                            

                            $numberDays = $timeDiff/86400;  // 86400 seconds in one day                  


                            $numberDays = intval($numberDays);                                           

                                                                                        

                            // echo floor((strtotime($data->check_in)-strtotime($data->check_out))/(60*60*24)); 
                           
                           
                           ?>
                           
                           <li><span class="text-black font-weight-bold">Duration:</span>{{$numberDays}}  Nights</li>
                           
                           
                           
                                  
                         
                        </ul>
                     </div>
                     
                  
                     
                     
                     
                     
                     
                     
                     
                     <div class="v-heading-icon clearfix mt-3">
                        <div class="float-left">
                           <img src="https://alhijaztours.net/public/admin_package/frontend/images/tour-info.jpg">
                        </div>
                        <div class="row">
                           <div class="col-md-8">
                              <div class="v-heading-icon-title float-left">
                                 <h3>Room Information</h3>
                              </div>
                           </div>
                           <div class="col-md-4">
                           </div>
                        </div>
                     </div>
                     
                   
                     
                     
                    
                     
                     <div class="clearfix v-section-info">
                         
                         
                         
                     
                     
                         
                       
                        <ul class="list-items list-items-3 list-items-4  clearfix">
                            
                            <?php
                        
                             
                             $count=1;
                         
                            foreach($hotelbedSelectionRS->hotel->rooms as $rooms)
                            {
                            
                            ?>
                            
                             <div class="v-heading-icon clearfix mt-3">
                             
                             
                        
                        <div class="row">
                           <div class="col-md-8">
                              <div class="v-heading-icon-title float-left">
                                 <h3>Room #{{$count ?? ''}}</h3>
                              </div>
                           </div>
                           <div class="col-md-4">
                           </div>
                        </div>
                     </div>                            
                           <li>
                              <span class="text-black font-weight-bold">Room Name:</span> {{$rooms->name}}
                              
                              
                           </li>
                           <?php
                           
                           foreach($rooms->rates as $rates)
                            {
                           
                           ?>
                            <li>
                             
                              <!--<span class="text-black font-weight-bold">Room code:</span>Suite (jacuzzi)-->
                             
                              
                           </li>
                           <li>
                             
                              <span class="text-black font-weight-bold">Room Status:</span>Confirmed
                             
                              
                           </li>
                           
                        
                           
                           <li>

                              <span class="text-black font-weight-bold">Room Price: </span>{{$hotelbedSelectionRS->hotel->currency}} {{$rates->net}}
                              
                              
                           </li>
                           
                           <li>
                             
                              <span class="text-black font-weight-bold">Board Type:</span>  {{$rates->boardName}}
                             
                              
                           </li>
                           <li>
                              
                              <span class="text-black font-weight-bold">Passengar: </span>Adults  {{$rates->adults}}, Child  {{$rates->children}}
                              
                           </li>
                           
                          
                           
                           
                        
                           
                           
                           
                            
                          <div class="v-heading-icon clearfix mt-3">
                        <div class="float-left">
                           <img src="https://alhijaztours.net/public/admin_package/frontend/images/tour-info.jpg">
                        </div>
                        <div class="row">
                           <div class="col-md-8">
                              <div class="v-heading-icon-title float-left">
                                 <h3>Cancellation Policy</h3>
                              </div>
                           </div>
                           <div class="col-md-4">
                           </div>
                        </div>
                     </div>  
                           
                         <table class="table">
  <thead>
      
      
    <tr>
        
      <th scope="col">from</th>
      
   
       <th scope="col">Amount</th>
      
    </tr>
   
  </thead>
  <tbody>
   
      
     <?php
     
     foreach($rates->cancellationPolicies as $cancellationPolicies)
     {
     
     ?>
    <tr>
      <th scope="row">{{$cancellationPolicies->from}}</th>
      
      <td>{{$hotelbedSelectionRS->hotel->currency}} {{$cancellationPolicies->amount}}</td>
      
      
    </tr>

   
     
    

   
     
    
<?php

}
?>

  </tbody>
</table>  
                           
                           
                                                           
                           
                       <?php
                       $count=$count+1;
                            }
                       
                            }
                       ?>    
                                  
                         
                        </ul>
                        
                       
                       
                     </div>
                    
                     
           
                    
                    
                    
                  
                   
                    
                 <!--<div class="col-md-4 col-sm-12 mt-3 mb-5">-->
                 
                 <!--    <a href="javascript:;" data-toggle="modal" data-target="#exampleModal_hotel_tbo" class="btn" style="background-color:#d2b254; color:white;">Cancellation</a>-->
           
                     
                     
                 
            
                 <!--</div>-->
                                  
                  </section>
                      
                  
                  <!--apply for umrah visa-->
               </div>
               <!--Sidrbar start-->
                              

              
           
               <div class="col-md-4 col-sm-12">
                  <div class="v-heading-icon clearfix mt-3" style="background: #ae8c32">
                     <div class="float-left">
                        <img src="https://alhijaztours.net/public/admin_package/frontend/images/lead.png">
                     </div>
                     <div class="v-heading-icon-title float-left" style="">
                        <h3 style="font-size: 24px;margin-top: 7px;color: #fff;">Lead Passenger Details</h3>
                     </div>
                  </div>
                  
                 <?php
               if(isset($lead_passenger_details))
               {
               
               ?>
                  <div class="clearfix v-section-info" style="padding: 5px 10px;border: 1px solid #5d646d;">
                       <ul class="list-items list-items-3 list-items-4  clearfix">
                        
                        
                        <li><span class="text-black font-weight-bold">Full Name:</span>
                        {{$lead_passenger_details->lead_first_name}}&nbsp;{{$lead_passenger_details->lead_last_name}}
                        <p class="f-20 text-black font-weight-bold"></p>
                        </li>
                        <li><span class="text-black font-weight-bold">Title:</span>
                        {{$lead_passenger_details->lead_title}}
                         <p class="f-20 text-black font-weight-bold"></p>
                        </li>
                      
                        <li class="d-block">
                           <div class="row">
                              <div class="col-sm-5">
                                  
                                 <span class="text-black font-weight-bold">Phone Number:</span>
                                <p> </p>
                              </div>
                              <div class="col-sm-7">
                               
                                 <p class="">{{$lead_passenger_details->lead_phone}}</p>
                              </div>
                           </div>
                        </li>
                        
                        <li class="d-block">
                           <div class="row">
                              <div class="col-sm-5">
                                 <span class="text-black font-weight-bold">Email:</span>
                                 
                                 
                              </div>
                              
                             
                              
                              
                              <div class="col-sm-7">
                             
                                 <p class="">{{$lead_passenger_details->lead_email}}</p>
                              </div>
                           </div>
                        </li>
                     </ul>
                  </div>
                 <?php
               
               }
               ?>
               <?php
               if(isset($other_passenger_details))
               {
                   foreach($other_passenger_details as $other_passenger_details)
                   {
               
               ?>
                  <div class="clearfix v-section-info" style="padding: 5px 10px;border: 1px solid #5d646d;">
                       <ul class="list-items list-items-3 list-items-4  clearfix">
                        
                        
                        <li><span class="text-black font-weight-bold">Full Name:</span>
                        {{$other_passenger_details->other_first_name}}&nbsp;{{$other_passenger_details->other_last_name}}
                        <p class="f-20 text-black font-weight-bold"></p>
                        </li>
                        <li><span class="text-black font-weight-bold">Title:</span>
                        {{$other_passenger_details->title}}
                         <p class="f-20 text-black font-weight-bold"></p>
                        </li>
                     </ul>
                  </div>
                 <?php
                   }
               
               }
               ?>
                  <!-- end icon-box -->
                                     
                  <div class="vochure-sidebar-title mt-5 text-center">
                     <h3>Grand Total</h3>
                  </div>
                  <ul class="list-items list-items-2 clearfix">
                     <div class="grand-total">
                       
                      {{$hotelbedSelectionRS->hotel->currency}} {{$hotelbedSelectionRS->hotel->totalNet}}
                        
                        <li class="text-black font-weight-bold"></li>
                     </div>
                  </ul>
                  
                 
                
                 
                
<!--                  <div class="vochure-sidebar-title mt-5 text-center">-->
<!--                     <h3>Contact Information</h3>-->
<!--                  </div>-->
<!--                  <ul class="list-items list-items-5  clearfix">-->
<!--                     <li>-->
<!--                        <p class="text-black font-weight-bold">Feel free to contact us any time.</p>-->
<!--                     </li>-->
<!--                     <li>-->
<!--                        <span class="text-black font-weight-bold mr-3">Phone:</span>-->
<!--                        <p>0121 777 2522</p>-->
<!--                     </li>-->
<!--                     <li>-->
<!--                        <span class="text-black font-weight-bold mr-3">Email:</span>-->
<!--                        <p>info@alhijaztours.net</p>-->
<!--                     </li>-->
<!--                     <li>-->
<!--                        <span class="text-black font-weight-bold mr-3">Address:</span>-->
<!--                        <p>1a Nansen Road Sparkhill-->
<!--Birmingham B11 4DR UK</p>-->
<!--                     </li>-->
                    
<!--                  </ul>-->


<div>
    <form method="post" action="{{URL::to('super_admin/confrim_booking_hotel')}}">
        @csrf
        
        <input type="hidden" name="hotelbeds_booking_id" value='<?php print_r($id);   ?>' >
        <input type="hidden" name="hotelbeds_booking_slug" value='<?php print_r($slug);   ?>' >
        <input type="hidden" name="hotelbeds_booking_rq" value='"<?php print_r($hotelbedcnfrmRQ);   ?>"' >
        
        
        <button style="float:right" class='mt-5 btn btn-info'>Book Now</button>
       
    </form>
     
</div>


             
               </div>
               
               
               
               
               
               
            </div>
         </div>
      </div>

<?php


}

if($slug === 'travellanda')
   {
       
     
       
       $travelandadetailRS=json_decode($data->travellandadetailRS);
       $travelandaSelectionRS=json_decode($data->travellandaSelectionRS);
       $lead_passenger_details=json_decode($data->lead_passenger_details);
       $other_passenger_details=json_decode($data->other_passenger_details);
       
       $hotelbedcnfrmRQ=$data->hotelbedcnfrmRQ;
       $travelanda_rooms=$data->rooms;
       $travelanda_currency=$travelandaSelectionRS->Body->Currency ?? 'GBP';
 
       
   ?>
   
<div class="vochure-content">
         <div class="container">
            
            <div class="row">
               <div class="col-md-8 col-sm-12">
                     
                  <section class="vochure-detail-section mt-3">
                     <div class="v-heading-icon v-heading-top clearfix">
                        <div class="float-left">
                           <img src="https://alhijaztours.net/public/admin_package/frontend/images/icon-tour.png">
                        </div>
                        <div class="v-heading-icon-title float-left">
                           <h2></h2>
                        </div>
                     </div>
                     <div class="v-heading-icon clearfix mt-3">
                        <div class="float-left">
                           <img src="https://alhijaztours.net/public/admin_package/frontend/images/tour-info.jpg">
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
                        <ul class="list-items list-items-3 list-items-4  clearfix">
                           <li>
                              <span class="text-black font-weight-bold">Hotel Name:</span> {{$travelandadetailRS->HotelName}}
                              
                              <p class="f-20 text-black font-weight-bold" id="makkah_booking_status"></p>
                           </li>
                           
                           <!-- <li>-->
                           <!--   <span class="text-black font-weight-bold">Hotel Address:</span>-->
                              
                           <!--   <p class="f-20 text-black font-weight-bold" id="makkah_booking_status"></p>-->
                           <!--</li>-->
                           
                           <!-- <li>-->
                           <!--   <span class="text-black font-weight-bold">Country Name:</span>-->
                              
                           <!--   <p class="f-20 text-black font-weight-bold" id="makkah_booking_status"></p>-->
                           <!--</li>-->
                           
                           <!-- <li>-->
                           <!--   <span class="text-black font-weight-bold">City:</span>-->
                              
                           <!--   <p class="f-20 text-black font-weight-bold" id="makkah_booking_status"></p>-->
                           <!--</li>-->
                           
                             <li>
                              <span class="text-black font-weight-bold">Hotel Ratings :</span>{{$travelandadetailRS->StarRating}}
                              
                              <p class="f-20 text-black font-weight-bold" id="makkah_booking_status"></p>
                           </li>
                           <!--<li>-->
                           <!--   <span class="text-black font-weight-bold">Hotel Price:</span>-->
                            
                              
                              
                              
                              
                           <!--   <p class="f-20 text-black font-weight-bold" id="makkah_booking_status"></p>-->
                           <!--</li>-->
                           
                           <li>
                              <span class="text-black font-weight-bold">Booking Status:</span>{{$data->booking_status}}
                            
                              
                              
                              
                              
                              <p class="f-20 text-black font-weight-bold" id="makkah_booking_status"></p>
                           </li>
                           
                           
                           
                           <li>
                              <span class="text-black font-weight-bold">Booking Confirmation No:</span>{{$data->search_id}}
                              
                              <p class="f-20 text-black font-weight-bold" id="makkah_booking_status"></p>
                           </li>
                         
                              <li>
                              <span class="text-black font-weight-bold">Booking Date:</span>{{$data->created_at}}
                              
                              <p class="f-20 text-black font-weight-bold" id="makkah_booking_status"></p>
                           </li> 
                          
                          
                           <li><span class="text-black font-weight-bold">Passengar:</span>Adults  {{$data->total_passenger}}, Child  {{$data->child}}</li>
                           
                           <!--<li><span class="text-black font-weight-bold">Hotel Price:</span></li>-->
                           
                           
                                                           
                           <li><span class="text-black font-weight-bold">Check-In:</span>{{$data->check_in}}</li>
                           <li><span class="text-black font-weight-bold">Check-Out:</span>{{$data->check_out}}</li>
                           
                                                       
                          
                           <?php
                           
                          $startTimeStamp = strtotime($data->check_in);                                   
                            $endTimeStamp = strtotime($data->check_out);                                     

                            $timeDiff = abs($endTimeStamp - $startTimeStamp);                            

                            $numberDays = $timeDiff/86400;  // 86400 seconds in one day                  


                            $numberDays = intval($numberDays);                                           

                                                                                        

                            // echo floor((strtotime($data->check_in)-strtotime($data->check_out))/(60*60*24)); 
                           
                           
                           ?>
                           
                           <li><span class="text-black font-weight-bold">Duration:</span>{{$numberDays}}  Nights</li>
                           
                           
                           
                                  
                         
                        </ul>
                     </div>
                     
                  
                     
                     
                     
                     
                     
                     
                     
                     <div class="v-heading-icon clearfix mt-3">
                        <div class="float-left">
                           <img src="https://alhijaztours.net/public/admin_package/frontend/images/tour-info.jpg">
                        </div>
                        <div class="row">
                           <div class="col-md-8">
                              <div class="v-heading-icon-title float-left">
                                 <h3>Room Information</h3>
                              </div>
                           </div>
                           <div class="col-md-4">
                           </div>
                        </div>
                     </div>
                     
                   
                     
                     
                    
                     
                     <div class="clearfix v-section-info">
                         
                         
                         
                     
                     
                         
                       
                        <ul class="list-items list-items-3 list-items-4  clearfix">
                            
                            <?php
                            
                            if($travelanda_rooms == 1)
                            {
                              $rooms=$travelandaSelectionRS[0]->Rooms->Room;
                            
                            ?>
                            
                             <div class="v-heading-icon clearfix mt-3">
                             
                             
                        
                        <div class="row">
                           <div class="col-md-8">
                              <div class="v-heading-icon-title float-left">
                                 <h3>Room #1</h3>
                              </div>
                           </div>
                           <div class="col-md-4">
                           </div>
                        </div>
                     </div>                            
                           <li>
                              <span class="text-black font-weight-bold">Room Name:</span> {{$rooms->RoomName}}
                              
                              
                           </li>
                           
                            <li>
                             
                              <!--<span class="text-black font-weight-bold">Room code:</span>Suite (jacuzzi)-->
                             
                              
                           </li>
                           <li>
                             
                              <span class="text-black font-weight-bold">Room Status:</span>Confirmed
                             
                              
                           </li>
                           
                        
                           
                           <li>

                              <span class="text-black font-weight-bold">Room Price: </span>{{$travelanda_currency}} {{$rooms->RoomPrice}}
                              
                              
                           </li>
                           
                           <li>
                             
                              <span class="text-black font-weight-bold">Board Type:</span>  {{$travelandaSelectionRS[0]->BoardType}}
                             
                              
                           </li>
                           <li>
                              
                              <span class="text-black font-weight-bold">Passengar: </span>Adults  {{$rooms->NumAdults}}, Child  {{$rooms->NumChildren}}
                              
                           </li>
                           
                          
                           
                           
                        
                           
                           
                           
                            
                     <!--     <div class="v-heading-icon clearfix mt-3">-->
                     <!--   <div class="float-left">-->
                     <!--      <img src="https://alhijaztours.net/public/admin_package/frontend/images/tour-info.jpg">-->
                     <!--   </div>-->
                     <!--   <div class="row">-->
                     <!--      <div class="col-md-8">-->
                     <!--         <div class="v-heading-icon-title float-left">-->
                     <!--            <h3>Cancellation Policy</h3>-->
                     <!--         </div>-->
                     <!--      </div>-->
                     <!--      <div class="col-md-4">-->
                     <!--      </div>-->
                     <!--   </div>-->
                     <!--</div>  -->
                           
                         
                           
                           
                                                           
                           
                       <?php
                      
                            
                       
                                
                            }
                            else
                            {
                                $count=1;
                              foreach($travelandaSelectionRS[0]->Rooms->Room as $rooms)
                            {
                            
                            ?>
                            <div class="v-heading-icon clearfix mt-3">
                             
                             
                        
                        <div class="row">
                           <div class="col-md-8">
                              <div class="v-heading-icon-title float-left">
                                  
                                 <h3>Room #{{$count ?? ''}}</h3>
                              </div>
                           </div>
                           <div class="col-md-4">
                           </div>
                        </div>
                     </div>
                                                         
                           <li>
                              <span class="text-black font-weight-bold">Room Name:</span> {{$rooms->RoomName}}
                              
                              
                           </li>
                           
                            <li>
                             
                              <!--<span class="text-black font-weight-bold">Room code:</span>Suite (jacuzzi)-->
                             
                              
                           </li>
                           <li>
                             
                              <span class="text-black font-weight-bold">Room Status:</span>Non Refundable
                             
                              
                           </li>
                           
                        
                           
                           <li>

                              <span class="text-black font-weight-bold">Room Price: </span>{{$travelanda_currency}} {{$rooms->RoomPrice}}
                              
                              
                           </li>
                           
                           <li>
                             
                              <span class="text-black font-weight-bold">Board Type:</span>  {{$travelandaSelectionRS[0]->BoardType}}
                             
                              
                           </li>
                           <li>
                              
                              <span class="text-black font-weight-bold">Passengar: </span>Adults  {{$rooms->NumAdults}}, Child  {{$rooms->NumChildren}}
                              
                           </li>
                           
                          
                                            
                           
                       <?php
                       $count=$count+1;
                           
                       
                            }  
                            }
                            
                            
                            
                            
                       ?>    
                                  
                         
                        </ul>
                        
                       
                       
                     </div>
                    
                     
           
                    
                    
                    
                  
                   
                    
                 <!--<div class="col-md-4 col-sm-12 mt-3 mb-5">-->
                 
                 <!--    <a href="javascript:;" data-toggle="modal" data-target="#exampleModal_hotel_tbo" class="btn" style="background-color:#d2b254; color:white;">Cancellation</a>-->
           
                     
                     
                 
            
                 <!--</div>-->
                                  
                  </section>
                      
                  
                  <!--apply for umrah visa-->
               </div>
               <!--Sidrbar start-->
                              

              
           
               <div class="col-md-4 col-sm-12">
                  <div class="v-heading-icon clearfix mt-3" style="background: #ae8c32">
                     <div class="float-left">
                        <img src="https://alhijaztours.net/public/admin_package/frontend/images/lead.png">
                     </div>
                     <div class="v-heading-icon-title float-left" style="">
                        <h3 style="font-size: 24px;margin-top: 7px;color: #fff;">Lead Passenger Details</h3>
                     </div>
                  </div>
                  
                 <?php
               if(isset($lead_passenger_details))
               {
               
               ?>
                  <div class="clearfix v-section-info" style="padding: 5px 10px;border: 1px solid #5d646d;">
                       <ul class="list-items list-items-3 list-items-4  clearfix">
                        
                        
                        <li><span class="text-black font-weight-bold">Full Name:</span>
                        {{$lead_passenger_details->lead_first_name}}&nbsp;{{$lead_passenger_details->lead_last_name}}
                        <p class="f-20 text-black font-weight-bold"></p>
                        </li>
                        <li><span class="text-black font-weight-bold">Title:</span>
                        {{$lead_passenger_details->lead_title}}
                         <p class="f-20 text-black font-weight-bold"></p>
                        </li>
                      
                        <li class="d-block">
                           <div class="row">
                              <div class="col-sm-5">
                                  
                                 <span class="text-black font-weight-bold">Phone Number:</span>
                                <p> </p>
                              </div>
                              <div class="col-sm-7">
                               
                                 <p class="">{{$lead_passenger_details->lead_phone}}</p>
                              </div>
                           </div>
                        </li>
                        
                        <li class="d-block">
                           <div class="row">
                              <div class="col-sm-5">
                                 <span class="text-black font-weight-bold">Email:</span>
                                 
                                 
                              </div>
                              
                             
                              
                              
                              <div class="col-sm-7">
                             
                                 <p class="">{{$lead_passenger_details->lead_email}}</p>
                              </div>
                           </div>
                        </li>
                     </ul>
                  </div>
                 <?php
               
               }
               ?>
               <?php
               if(isset($other_passenger_details))
               {
                   foreach($other_passenger_details as $other_passenger_details)
                   {
               
               ?>
                  <div class="clearfix v-section-info" style="padding: 5px 10px;border: 1px solid #5d646d;">
                       <ul class="list-items list-items-3 list-items-4  clearfix">
                        
                        
                        <li><span class="text-black font-weight-bold">Full Name:</span>
                        {{$other_passenger_details->other_first_name}}&nbsp;{{$other_passenger_details->other_last_name}}
                        <p class="f-20 text-black font-weight-bold"></p>
                        </li>
                        <li><span class="text-black font-weight-bold">Title:</span>
                        {{$other_passenger_details->title}}
                         <p class="f-20 text-black font-weight-bold"></p>
                        </li>
                     </ul>
                  </div>
                 <?php
                   }
               
               }
               ?>
                  <!-- end icon-box -->
                                     
                  <div class="vochure-sidebar-title mt-5 text-center">
                     <h3>Grand Total</h3>
                  </div>
                  <ul class="list-items list-items-2 clearfix">
                     <div class="grand-total">
                       
                      <?php
                      $grandtotal=0;
                       if($travelanda_rooms == 1)
                       {
                           $grandtotal=$grandtotal + $travelandaSelectionRS[0]->Rooms->Room->RoomPrice;
                       }
                       else
                       {
                           foreach($travelandaSelectionRS[0]->Rooms->Room as $rooms)
                           {
                               $grandtotal=$grandtotal + $rooms->RoomPrice;
                           }
                       }
                       print_r($travelanda_currency);
                       print_r($grandtotal);
                      ?>
                        
                        <li class="text-black font-weight-bold"></li>
                     </div>
                  </ul>
                  
                 
                
                 
                
<!--                  <div class="vochure-sidebar-title mt-5 text-center">-->
<!--                     <h3>Contact Information</h3>-->
<!--                  </div>-->
<!--                  <ul class="list-items list-items-5  clearfix">-->
<!--                     <li>-->
<!--                        <p class="text-black font-weight-bold">Feel free to contact us any time.</p>-->
<!--                     </li>-->
<!--                     <li>-->
<!--                        <span class="text-black font-weight-bold mr-3">Phone:</span>-->
<!--                        <p>0121 777 2522</p>-->
<!--                     </li>-->
<!--                     <li>-->
<!--                        <span class="text-black font-weight-bold mr-3">Email:</span>-->
<!--                        <p>info@alhijaztours.net</p>-->
<!--                     </li>-->
<!--                     <li>-->
<!--                        <span class="text-black font-weight-bold mr-3">Address:</span>-->
<!--                        <p>1a Nansen Road Sparkhill-->
<!--Birmingham B11 4DR UK</p>-->
<!--                     </li>-->
                    
<!--                  </ul>-->


<div>
    <form method="post" action="{{URL::to('super_admin/confrim_booking_hotel')}}">
        @csrf
        
        <input type="hidden" name="hotelbeds_booking_id" value='<?php print_r($id);   ?>' >
        <input type="hidden" name="hotelbeds_booking_slug" value='<?php print_r($slug);   ?>' >
        <input type="hidden" name="hotelbeds_booking_rq" value='"<?php print_r($hotelbedcnfrmRQ);   ?>"' >
        
        
        <button style="float:right" class='mt-5 btn btn-info'>Book Now</button>
       
    </form>
     
</div>


             
               </div>
               
               
               
               
               
               
            </div>
         </div>
      </div>

<?php


}


    if($slug == 'tbo')
      {
           ?>
           
           
   
      
      
      <?php
           $tbo_BookingDetail_rs=json_decode($data->tboSelectionRS);
           
            //print_r($tboBookingRS);die();

                //  print_r($tboBookingRS);
                
                //$tbo_BookingDetail_rs=json_decode($data->tbo_BookingDetail_rs);
         
         
               
        
        

     $request_data = array(
    'HotelCode' => $tbo_BookingDetail_rs->HotelResult[0]->HotelCode,
   
    );
//   $searchdata=json_encode($searchdata);
  $curl = curl_init();
     curl_setopt_array($curl, array(
     CURLOPT_URL => 'https://admin.synchronousdigital.com/api/get_tbo_hotel_details',
     CURLOPT_RETURNTRANSFER => true,
     CURLOPT_ENCODING => '',
     CURLOPT_MAXREDIRS => 10,
     CURLOPT_TIMEOUT => 0,
     CURLOPT_FOLLOWLOCATION => true,
     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
     CURLOPT_CUSTOMREQUEST => 'POST',
     CURLOPT_POSTFIELDS =>  $request_data,
     CURLOPT_HTTPHEADER => array(
        'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
     ),
     ));
  
     $response = curl_exec($curl);
     curl_close($curl);
     $tbo_hotel_detail= $response; 
     $tbo_hotel_detail=json_decode($tbo_hotel_detail);
  //print_r($tbo_hotel_detail); die();
     
     
     $tbo_hotel_detail=$tbo_hotel_detail->data;
    
     
    
     
     
     
    ?>       
          
      
      
      
      
      
      
      
      
      <div class="vochure-content">
         <div class="container">
            <div class="button-group-voucher" style="display: flex; justify-content: flex-end;">
               <div class="text-right mt-3 mr-2">
                  <button type="submit" class="btn btn-secondary" onclick="window.print()">Print  Voucher </button>
               </div>
              
            </div>
            <div class="row">
               <div class="col-md-8 col-sm-12">
                     
                  <section class="vochure-detail-section mt-3">
                     <div class="v-heading-icon v-heading-top clearfix">
                        <div class="float-left">
                           <img src="{{asset('public/admin_package/frontend/images/icon-tour.png') }}">
                        </div>
                        <div class="v-heading-icon-title float-left">
                           <h2></h2>
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
                     
                   
                     
                      <?php
                           
                           $startTimeStamp = strtotime($data->check_in);                                   
                            $endTimeStamp = strtotime($data->check_out);  
                            $totalFare= Session::get('roomDetailsIn->TotalFare');
                            

                                                                                    
                           
                           ?>
                     
                     <div class="clearfix v-section-info">
                        <ul class="list-items list-items-3 list-items-4  clearfix" >
                           <li>
                              <span class="text-black font-weight-bold">Hotel Name:</span>{{$tbo_hotel_detail->hotel_name ?? ''}}
                              
                              <p class="f-20 text-black font-weight-bold" id="makkah_booking_status"></p>
                           </li>
                           
                            <li>
                              <span class="text-black font-weight-bold">Hotel Address:</span>{{$tbo_hotel_detail->address ?? ''}}
                              
                              <p class="f-20 text-black font-weight-bold" id="makkah_booking_status"></p>
                           </li>
                           
                            <li>
                              <span class="text-black font-weight-bold">Country Name:</span>{{$tbo_hotel_detail->country_name ?? ''}}
                              
                              <p class="f-20 text-black font-weight-bold" id="makkah_booking_status"></p>
                           </li>
                           
                            <li>
                              <span class="text-black font-weight-bold">City:</span>{{$tbo_hotel_detail->city_name ?? ''}}
                              
                              <p class="f-20 text-black font-weight-bold" id="makkah_booking_status"></p>
                           </li>
                           
                             <li>
                              <span class="text-black font-weight-bold">Hotel Ratings :</span>{{$tbo_hotel_detail->hotel_rating ?? ''}}
                              
                              <p class="f-20 text-black font-weight-bold" id="makkah_booking_status"></p>
                           </li>
                           
                           
                           <li>
                              <span class="text-black font-weight-bold">Booking Status:</span>{{$data->booking_status ?? ''}}
                            
                              
                              
                              
                              
                              <p class="f-20 text-black font-weight-bold" id="makkah_booking_status"></p>
                           </li>
                           
                           
                           
                          
                            
                         
                              <li>
                              <span class="text-black font-weight-bold">Booking Date:</span>{{$data->created_at ?? ''}}
                              
                              <p class="f-20 text-black font-weight-bold" id="makkah_booking_status"></p>
                           </li> 
                          
                          
                           <li><span class="text-black font-weight-bold">Passengar:</span>Adults {{$data->total_passenger}} , Child {{$data->child}} </li>
                           
                            <?php
                  $grand_total=0;
                 foreach($tbo_BookingDetail_rs->HotelResult[0]->Rooms as $roomDetailsIn)
                 {
                     $grand_total=$grand_total + $roomDetailsIn->TotalFare;
                 }
                  
                  ?>
                           
                           <li><span class="text-black font-weight-bold">Hotel Price:</span><?php  print_r($tbo_BookingDetail_rs->HotelResult[0]->Currency); ?> {{$grand_total}}</li>
                           
                           
                                                           
                           <li><span class="text-black font-weight-bold">Check-In:</span>{{$data->check_in}}</li>
                           <li><span class="text-black font-weight-bold">Check-Out:</span>{{$data->check_out}}</li>
                           
                            <?php
                           
                           $startTimeStamp = strtotime($data->check_in);                                   
                            $endTimeStamp = strtotime($data->check_out);                                     

                            $timeDiff = abs($endTimeStamp - $startTimeStamp);                            

                            $numberDays = $timeDiff/86400;  // 86400 seconds in one day                  


                            $numberDays = intval($numberDays);                                           

                                                                                        

                            // echo floor((strtotime($data->check_in)-strtotime($data->check_out))/(60*60*24)); 
                        //   print_r($travellandaSelectionRS);die();
                           
                           ?>
                           
                          
                           
                           
                           <li><span class="text-black font-weight-bold">Duration:</span>{{$numberDays}}  Nights</li>
                           
                           
                           
                                  
                         
                        </ul>
                     </div>
                     
                  
                     
                     
                     
                     
                     
                     
                     
                     <div class="v-heading-icon clearfix mt-3">
                        <div class="float-left">
                           <img src="{{asset('public/admin_package/frontend/images/tour-info.jpg') }}">
                        </div>
                        <div class="row">
                           <div class="col-md-8">
                              <div class="v-heading-icon-title float-left">
                                 <h3>Room Information</h3>
                              </div>
                           </div>
                           <div class="col-md-4">
                           </div>
                        </div>
                     </div>
                     
                   
                     
                     
                    
                     
                     <div class="clearfix v-section-info">
                         
                         
                         
                     
                     
                         
                       
                        <ul class="list-items list-items-3 list-items-4  clearfix" >
                            
                            <?php
                            $count_room=1;
                            
                            ?>
                              @foreach($tbo_BookingDetail_rs->HotelResult[0]->Rooms as $roomDetailsIn)
                         
                         @php Session::put('roomDetailsIn->TotalFare', $roomDetailsIn->TotalFare) @endphp
                            <div class="v-heading-icon clearfix mt-3">
                             
                             @php
                             
                              $roomDetail= $data->tboDetailRS;
                           $roomDetails= json_decode($roomDetail);
                           
                           
                  
               @endphp
                        
                        <div class="row">
                           <div class="col-md-8">
                              <div class="v-heading-icon-title float-left">
                                 <h3>Room #<?php print_r($count_room); ?></h3>
                              </div>
                           </div>
                           <div class="col-md-4">
                           </div>
                        </div>
                     </div>
                           <li>
                              <span class="text-black font-weight-bold">Room Name:</span><?php print_r($roomDetailsIn->Name[0]); ?>
                              
                              
                           </li>
                            <li>
                             
                              <span class="text-black font-weight-bold">Room code:</span><?php print_r($roomDetailsIn->Inclusion); ?>
                             
                              
                           </li>
                           <li>
                             
                              <span class="text-black font-weight-bold">Room Quantity:</span><?php print_r($data->rooms); ?>
                             
                              
                           </li>
                           
                        <li>

                              <span class="text-black font-weight-bold">Total Tax: </span> USD <?php print_r($roomDetailsIn->TotalTax); ?>
                              
                              
                           </li>
                           
                           
                           <li>

                              <span class="text-black font-weight-bold">Total Fare: </span> USD <?php print_r($roomDetailsIn->TotalFare); ?>
                              
                              
                           </li>
                           
                           
                           <li>
                             
                              <span class="text-black font-weight-bold">Board Type:</span>  <?php print_r($roomDetailsIn->MealType); ?>
                             
                              
                           </li>
                           <li>
                              
                              <span class="text-black font-weight-bold">Passengar: </span>Adults {{$data->total_passenger}} , Child {{$data->child}}
                              
                           </li>
                           
                          
                           <li>
                             
                              <span class="text-black font-weight-bold">Note:</span>
                               @foreach($tbo_BookingDetail_rs->HotelResult[0]->RateConditions as $RateConditionsIn)
                           <?php print_r($RateConditionsIn)?>
                              @endforeach
                           
                            
                              
                           </li>
                           
                          
                           
                           
                           
                            
                          <div class="v-heading-icon clearfix mt-3">
                        <div class="float-left">
                           <img src="{{asset('public/admin_package/frontend/images/tour-info.jpg') }}">
                        </div>
                        <div class="row">
                           <div class="col-md-8">
                              <div class="v-heading-icon-title float-left">
                                 <h3>Cancellation Policy</h3>
                              </div>
                           </div>
                           <div class="col-md-4">
                           </div>
                        </div>
                     </div>  
                           
                         <table class="table">
  <thead>
      
      
    <tr>
        
      <th scope="col">FromDate</th>
      
      <th scope="col">Charge Type</th>
       <th scope="col">Cancellation Charge</th>
      
    </tr>
   
  </thead>
  <tbody>
      <?php
      
      
      foreach($roomDetailsIn->CancelPolicies as $CancelPolicies)
      {
      ?>
     
    <tr>
      <th scope="row">{{$CancelPolicies->FromDate}}</th>
      
      <td>{{$CancelPolicies->ChargeType}}</td>
      <td>USD {{$CancelPolicies->CancellationCharge}}</td>
      
    </tr>

<?php
}
?>

  </tbody>
</table>  
                           
                           
                                                           
                           <?php
                           $count_room=$count_room+1;
                           
                           ?>
                           
                            @endforeach       
                         
                        </ul>
                        
                       
                       
                     </div>
                    
                     
           
                    
                    
                    
                  
                 <?php
                 
                 if($data->booking_status == 'Confirmed')
                 {
                 ?>  
                    
                 <div class="col-md-4 col-sm-12 mt-3 mb-5">
                 
                     <a href="javascript:;" data-toggle="modal" data-target="#exampleModal_hotel_tbo" class="btn" style="background-color:#d2b254; color:white;">Cancellation</a>
           
                     
                     
                 
            
                 </div>
                 <?php
                 
                 }
                 ?>
                 
                  </section>
                      
                  
                  <!--apply for umrah visa-->
               </div>
               <!--Sidrbar start-->
               @php
               
               $leadPassenger= $data->lead_passenger_details;
               $leadPassengerDetail= json_decode($leadPassenger);
                 
               
               
               @endphp
               

               
           
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
                           <p class="f-20 text-black font-weight-bold">{{$leadPassengerDetail->lead_passport_no}}</p>
                        </li>
                        <li><span class="text-black font-weight-bold">Nationality:</span>{{$leadPassengerDetail->lead_passport_no}}</li>
                        <li><span class="text-black font-weight-bold">Full Name:</span>
                        {{$leadPassengerDetail->lead_first_name}}&nbsp;{{$leadPassengerDetail->lead_last_name}}
                        <p class="f-20 text-black font-weight-bold"></p>
                        </li>
                        <li><span class="text-black font-weight-bold">Title:</span>
                        {{$leadPassengerDetail->lead_title}}
                         <p class="f-20 text-black font-weight-bold"></p>
                        </li>
                      
                        <li class="d-block">
                           <div class="row">
                              <div class="col-sm-5">
                                  
                                 <span class="text-black font-weight-bold">Phone Number:</span>
                                <p> </p>
                              </div>
                              <div class="col-sm-7">
                               
                                 <p class="">{{$leadPassengerDetail->lead_phone}}</p>
                              </div>
                           </div>
                        </li>
                        
                        <li class="d-block">
                           <div class="row">
                              <div class="col-sm-5">
                                 <span class="text-black font-weight-bold">Email:</span>
                                 
                                 
                              </div>
                              
                             
                              
                              
                              <div class="col-sm-7">
                             
                                 <p class="">{{$leadPassengerDetail->lead_email}}</p>
                              </div>
                           </div>
                        </li>
                     </ul>
                  </div>
                 
                  <!-- end icon-box -->
                  <?php
                  $grand_total=0;
                 foreach($tbo_BookingDetail_rs->HotelResult[0]->Rooms as $roomDetailsIn)
                 {
                     $grand_total=$grand_total + $roomDetailsIn->TotalFare;
                 }
                  
                  ?>
                  
                  <div class="vochure-sidebar-title mt-5 text-center">
                     <h3>Grand Total</h3>
                  </div>
                  <ul class="list-items list-items-2 clearfix" >
                     <div class="grand-total">
                        <?php print_r($tbo_BookingDetail_rs->HotelResult[0]->Currency); ?>   <?php print_r($grand_total); ?>
                       
                        
                        <li class="text-black font-weight-bold"></li>
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
              
        <div>
    <form method="post" action="{{URL::to('super_admin/confrim_booking_hotel')}}">
        @csrf
        
        <input type="hidden" name="hotelbeds_booking_id" value='<?php print_r($id);   ?>' >
        <input type="hidden" name="hotelbeds_booking_slug" value='<?php print_r($slug);   ?>' >
        <input type="hidden" name="tbo_booking_rq" value='"<?php print_r($data->tboBookingRQ);   ?>"' >
        
        
        <button style="float:right" class='mt-5 btn btn-info'>Book Now</button>
       
    </form>
     
</div>      
              
              
              
              
              
              
              
              
              
              
              
              
               </div>
            </div>
         </div>
      </div>
      <?php
      }


?>
   
        <!-- Button trigger modal -->


@endsection

