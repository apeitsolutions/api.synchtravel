<?php $visa_count = 0; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
      
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
      
     padding: 5px 0;
    color: #020202;
    text-transform: capitalize;
    border: none;
    border-bottom: 2px solid #ae8c32;
      }
      .v-heading-icon-title{
      padding-top: 3px;
      }
      .v-heading-icon img{
      max-width: 75px;
      padding: 0 15px;
      }
      .v-heading-icon-title h3{
      
      color: #ae8c32;
      font-size:1.25rem;
      }
      .v-section-info{
           padding:  10px 15px;
      }
      .sidebar .v-section-info {
         padding: 5px 10px;
          border: 1px solid #ae8c32;
        margin-top: 9px;
      }
      .vochure-content .list-items-2{
      border: 1px solid #ddd;
      padding: 10px;
      }
      .vochure-content .list-items-5{
      border: 1px solid #ddd;
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
     
      .sidebar .v-heading-icon{
              border: none;
             border-bottom: 2px solid #ae8c32;
             margin-bottom: 10px;
      }
      .total-amout {
          font-size: 18px;
    background: #ae8c32;
    padding: 5px 10px;
    color: #fff !important;
    margin-top: 22px;
      }
      
      
        /* flight css */
      .initiative__item {
         margin-bottom: 50px;
         }
         .initiative-top .title {
    float: left;
}
.initiative-top .title .from-to {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    line-height: 16px;
}
.initiative-top .title .from-to .awe-icon {
    font-size: 10px;
    margin-left: 3px;
    margin-right: 3px;
}
.initiative-top .title .time {
    font-size: 13px;
    color: #A5A5A5;
    margin-top: 3px;
    line-height: 16px;
}.initiative-top .price {
    float: right;
    text-align: right;
}.initiative-top .price .amount {
    display: block;
    font-size: 18px;
    font-weight: 600;
    color: #666;
}.initiative-table {
    background-color: #fff;
    width: 100%;
   
    margin-bottom: 10px;
    border-radius: 6px;
}.initiative-table tbody tr {
    border-bottom: 2px solid #D4D4D4;
}.initiative-table tbody tr th {
    width: 170px;
}
element.style {
}
.initiative-table tbody tr th {
    width: 170px;
}
.initiative-table tbody tr th, .initiative-table tbody tr td {
    padding: 20px;
}.initiative-table tbody tr .item-thumb {
    position: relative;
    text-align: center;
}.initiative-table tbody tr .item-thumb .text {
    margin-top: 8px;
}.initiative-table tbody tr .item-thumb .text span {
    font-size: 12px;
    font-weight: 400;
    color: #A5A5A5;
}.initiative-table tbody tr .item-thumb .text p {
    font-size: 18px;
    font-weight: 700;
    color:#ae8c32;
    margin: 0;
}.initiative-table tbody tr .item-thumb:after {
    content: '';
    display: block;
    position: absolute;
    width: 0;
    height: 100%;
    border-right: 2px dotted #D4D4D4;
    top: 0;
    right: -20px;
}.initiative-table tbody tr .item-body {
    padding: 0 30px;
    font-size: 0;
    overflow: hidden;
}.initiative-table tbody tr .item-body .item-from, .initiative-table tbody tr .item-body .item-time, .initiative-table tbody tr .item-body .item-to {
    display: inline-block;
    width: 50%;
    padding: 0 20px;
    text-align: center;
    vertical-align: middle;
}.initiative-table tbody tr .item-body .item-from .time, .initiative-table tbody tr .item-body .item-to .time {
    display: block;
    font-size: 18px;
    font-weight: 700;
    color: #A5A5A5;
}.initiative-table tbody tr .item-body .item-from .date, .initiative-table tbody tr .item-body .item-to .date {
    display: block;
    font-size: 11px;
    font-weight: 600;
    color: #666;
    margin-top: 4px;
}.initiative-table tbody tr .item-body .item-from .desc, .initiative-table tbody tr .item-body .item-to .desc {
    font-size: 14px;
    color: #A5A5A5;
    margin-top: 2px;
}.initiative-table tbody tr .item-body .item-time .fa {
    display: block;
    font-size: 20px;
    color: #A6A6A6;
}.initiative-table tbody tr .item-body .item-time span {
    display: inline-block;
    font-weight: 600;
    font-size: 14px;
    color: #A5A5A5;
    padding: 8px 6px;
    border-top: 1px dashed #A5A5A5;
    margin-top: 10px;
}.initiative-top {
    overflow: hidden;
    padding: 0 20px;
    padding-top:10px;
}
.item-to h3, 
.item-from h3{
   font-size: 14px;
}

.list-items-2 li span {
    display: inline-block;
    width: 270px;
}
   </style>
   
    <body>
      
        <div class="preloader" id="preloader">
            <div class="loader">
                <svg class="spinner" viewBox="0 0 50 50">
                    <circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
                </svg>
            </div>
        </div>
      
        <script>
            localStorage.setItem('total_amount', 10933.17);
        </script>
      
        <div class="container">
            <img style="width:100%;" src="{{ asset('/public/admin_package/frontend/images/vochure-header.png') }}" alt="letterhead">
        </div>

        <div class="vochure-content">
            <div class="container">
                <div class="button-group-voucher" style="display: flex; justify-content: flex-end;">
                   <div class="text-right mt-3 mr-2">
                      <button type="submit" class="btn btn-secondary" onclick="window.print()">Print  Voucher </button>
                      <a href="{{ URL::to('invoice_package/'.$id.'/'.$booking_id.'/'.$Tour_send_id.'') }}" target="blank" class="btn btn-secondary">Print  Invoice </a>
                      <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal">Make Payment </button>
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
                    <div class="col-md-8 col-sm-12">
                        @if(isset($cart_data))
                            @foreach($cart_data as $cart_res)
                                <section class="vochure-detail-section mt-3">
                                    
                                    <div class="v-heading-icon v-heading-top clearfix">
                                        <div class="float-left">
                                           <img src="{{asset('public/admin_package/frontend/images/icon-tour.png') }}">
                                        </div>
                                        <div class="v-heading-icon-title float-left">
                                           <h2>{{ $cart_res->tour_name }}</h2>
                                        </div>
                                    </div>
                          
                                    <div class="v-heading-icon clearfix mt-3">
                                        <div class="float-left">
                                            <img src="{{asset('public/admin_package/frontend/images/tour-info.jpg') }}">
                                        </div>
                                        <div class="v-heading-icon-title float-left">
                                            <h3>Tour Information</h3>
                                      </div>
                                    </div> 
                              
                                    <?php $cart_total_data = json_decode($cart_res->cart_total_data); ?>
                                     
                                    <div class="clearfix v-section-info">
                                        <ul class="list-items list-items-3 list-items-4  clearfix" >
                                           
                                           <li>
                                              <span class="text-black font-weight-bold">Inovice No:</span>
                                              <p class="f-20 text-black font-weight-bold" id="makkah_booking_status">{{ $id }}</p>
                                           </li>
                                        
                                      
                                           <li>
                                              <span class="text-black font-weight-bold">Tour Name:</span>
                                              <p class="f-20 text-black font-weight-bold" id="makkah_booking_status">{{ $cart_res->tour_name }}</p>
                                           </li>
                                         @if(isset($cart_total_data->agent_info))
                                             @if($cart_total_data->agent_info != false)
                                              <li>
                                                <li><span class="text-black font-weight-bold">Agent Name:</span><b>{{ $cart_total_data->agent_name }}</b></li>
                                              
                                           </li>
                                           @endif
                                           @endif
                                           @if($cart_res->adults > 0)
                                           <li><span class="text-black font-weight-bold">Adults:</span>{{ $cart_res->adults }} X {{ $cart_res->currency }} {{ $cart_res->sigle_price }}</li>
                                           @endif
                                           @isset($cart_res->double_adults)
                                            @if($cart_res->double_adults > 0  || $cart_total_data->double_childs > 0 || $cart_total_data->double_infant > 0)
                                           <li><span class="text-black font-weight-bold">Double Rooms X {{ $cart_res->double_room }}:</span>
                                                @isset($cart_res->double_adults)
                                                    {{ $cart_res->double_adults }} Adults,
                                                @endisset
                                                
                                                @isset($cart_total_data->double_childs)
                                                    {{ $cart_total_data->double_childs }} Child,
                                                @endisset
                                                
                                                @isset($cart_total_data->double_infant)
                                                    {{ $cart_total_data->double_infant }} Infants
                                                @endisset
                                            </li>
                                           @endif
                                            @endisset
                                           @isset($cart_res->triple_adults)
                                            @if($cart_res->triple_adults > 0  || $cart_total_data->triple_childs > 0 || $cart_total_data->triple_infant > 0)
                                           <li><span class="text-black font-weight-bold">Triple Rooms X  {{ $cart_res->triple_room }}:</span>
                                                @isset($cart_res->triple_adults)
                                                    {{ $cart_res->triple_adults }} Adults,
                                                @endisset
                                                
                                                @isset($cart_total_data->triple_childs)
                                                    {{ $cart_total_data->triple_childs }} Child,
                                                @endisset
                                                
                                                @isset($cart_total_data->triple_infant)
                                                    {{ $cart_total_data->triple_infant }} Infants
                                                @endisset
                                            </li>
                                           @endif
                                            @endisset
                                             @isset($cart_res->quad_adults)
                                             @if($cart_res->quad_adults > 0  || $cart_total_data->quad_childs > 0 || $cart_total_data->quad_infant > 0)
                                           <li><span class="text-black font-weight-bold">Quad Rooms X  {{ $cart_res->quad_room }}:</span>
                                                @isset($cart_res->quad_adults)
                                                    {{ $cart_res->quad_adults }} Adults,
                                                @endisset
                                                
                                                @isset($cart_total_data->quad_childs)
                                                    {{ $cart_total_data->quad_childs }} Child,
                                                @endisset
                                                
                                                @isset($cart_total_data->quad_infant)
                                                    {{ $cart_total_data->quad_infant }} Infants
                                                @endisset
                                            </li>
                                           @endif
                                            @endisset
                                           
                                       
                                  
                                           
                                           @isset($cart_res->without_acc_adults)
                                           <li><span class="text-black font-weight-bold">Adult Without Bed:</span>{{ $cart_res->without_acc_adults }} Adults</li>
                                           @endisset
                                            @isset($cart_total_data->children)
                                           @if($cart_total_data->children > 0)
                                                <li><span class="text-black font-weight-bold">Child Without Bed:</span>{{ $cart_total_data->children }} Childs
                                                </li>
                                           @endif
                                           @endisset
                                              @isset($cart_total_data->infants)
                                          @if($cart_total_data->infants > 0)
                                                <li><span class="text-black font-weight-bold">Infants Without Bed:</span>{{ $cart_total_data->infants }} Infants
                                                </li>
                                           @endif
                                           @endisset
                                            <?php 
                                                    if($package_type == 'tour'){
                                                ?>
                                                
                                                  
                                                    
                                              <?php 
                                              
                                                     
                                                    }else{
                                                        ?>
                                                     <li><span class="text-black font-weight-bold">Adult Price:</span>{{ $cart_res->currency }} {{ $cart_res->adult_price }}</li>
                                                     <li><span class="text-black font-weight-bold">Child Price:</span>{{ $cart_res->currency }} {{ $cart_res->child_price }}</li>
                                                        <?php 
                                                    }
                                                        ?>
                                            
                                            @if(isset($iternery_array))
                                                @foreach($iternery_array as $itenry_res)
                                                
                                                <?php 
                                                    if($package_type == 'tour'){
                                                        if(isset($itenry_res[0]->tour_id)){
                                                ?>
                                                    @if($itenry_res[0]->tour_id == $cart_res->tour_id)
                                                        <li><span class="text-black font-weight-bold">Check-In:</span>{{ date("d-m-Y", strtotime($itenry_res[0]->start_date))  }}</li>
                                                        <li><span class="text-black font-weight-bold">Check-Out:</span>{{ date("d-m-Y", strtotime($itenry_res[0]->end_date)) }}</li>
                                                        <li><span class="text-black font-weight-bold">Duration:</span>{{ $itenry_res[0]->time_duration }} Nights</li>
                                                        <li><span class="text-black font-weight-bold">Destinations:</span> @if(isset($itenry_res[0]->tour_location))
                                                        @php 
                                                            $locations = json_decode($itenry_res[0]->tour_location)
                                                        @endphp
                                                            
                                                            @foreach($locations as $loca_res)
                                                                {{ $loca_res }},
                                                            @endforeach
                                                        @endif
                                            </li>
                                           
                                           
                                                    @endif
                                           <?php 
                                                        }
                                                    }else{
                                                        // dd($iternery_array);
                                                        ?>
                                                        @if(isset($itenry_res[0]->activity_id) && $itenry_res[0]->activity_id == $cart_res->tour_id)
                                                            <li><span class="text-black font-weight-bold">Check-In:</span>{{ date("d-m-Y", strtotime($cart_res->activity_select_date))  }}</li>
                                                            <li><span class="text-black font-weight-bold">Duration:</span>{{ $itenry_res[0]->duration }} Hours</li>
                                                            <li><span class="text-black font-weight-bold">Destinations:</span>{{ $itenry_res[0]->location }}
                                                            </li>
                                                           
                                                           
                                                        @endif
                                                        <?php
                                                    }
                                           ?>
                                               @endforeach
                                          @endif
                                          
                                          
                                        </ul>
                                     </div>
                                     
                                    @if(isset($itenry_res[0]->accomodation_details))
                                        @php $accomodation_details = json_decode($itenry_res[0]->accomodation_details); @endphp
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="v-heading-icon clearfix mt-3">
                                                    <div class="float-left">
                                                       <img src="https://umrahtech.com/public/assets/frontend/images/Icons/booking-details.png">
                                                    </div>
                                                    <div class="v-heading-icon-title float-left">
                                                        <h3>Hotels Details</h3>
                                                    </div>
                                                </div>
                                            </div>
                                    
                                            @foreach($accomodation_details as $acc_res)
                                                <div class="col-md-6">
                                                    <div class="v-heading-icon mt-3" >
                                                        <div class="row">
                                                            <div class="col-md-2" style="padding:0px;">
                                                                <img style="width:75px; height:75px;" src="{{ config('img_url') }}/public/uploads/package_imgs/hotel.png">
                                                            </div>
                                                            <div class="col-md-10">
                                                                 <div class="v-heading-icon-title" style="margin-top:10px">
                                                                    <h3>{{  $acc_res->acc_hotel_name }}</h3>
                                                                 </div>
                                                            </div>
                                                        </div>
                                                      
                                                        
                                                      </div>
                                                      <div class="clearfix v-section-info" style="border: 1px solid #ddd;">
                                                         <ul class="list-items list-items-3 list-items-4  clearfix" >
                                                            <li>
                                                               <span class="text-black font-weight-bold">Check In:</span> 
                                                               <p class="f-20 text-black font-weight-bold">{{  date("d-m-Y", strtotime($acc_res->acc_check_in)) }}</p>
                                                            </li>
                                                            <li><span class="text-black font-weight-bold">Check Out:</span>{{  date("d-m-Y", strtotime($acc_res->acc_check_out)) }}</li>
                                                            <li><span class="text-black font-weight-bold">Type :</span>{{  $acc_res->acc_type }}</li>
                        
                                                          
                                                            <li class="d-block">
                                                               <div class="row">
                                                                  <div class="col-sm-5">
                                                                     <span class="text-black font-weight-bold">No of Nights:</span>
                                                                  </div>
                                                                  <div class="col-sm-7">
                                                                    {{  $acc_res->acc_no_of_nightst }} Nights 
                                                                     
                                                                  </div>
                                                               </div>
                                                            </li>
                                                         
                                                         </ul>
                                                      </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                     
                                    @if(isset($itenry_res[0]->flights_details))
                                       <?php
                                            $flights_details        = $itenry_res[0]->flights_details; 
                                            $return_flights_details = $itenry_res[0]->return_flights_details; 
                                            $flights_details        = json_decode($itenry_res[0]->flights_details);
                                        ?>
                                        @if(isset($flights_details) && $flights_details != null && $flights_details != '')
                                            <section class="vochure-detail-section mt-3">
                                     <div class="v-heading-icon v-heading-top clearfix">
                                        <div class="float-left">
                                           <img src="{{asset('public/invoice/assets/img/flight.png') }}">
                                        </div>
                                        <div class="v-heading-icon-title float-left">
                                           <h3>Flight Details</h3>
                                        </div>
                                     </div>
                                     <div class="product-tabs__content">
                                                <div id="tabs-1">
                                                    <div class="initiative">
                                                        
                                                                            
                                                                            @if(isset($flights_details))
                                                                                <?php 
                                                                                    $flights_details            = json_decode($itenry_res[0]->flights_details);
                                                                                    $return_flights_details     = json_decode($itenry_res[0]->return_flights_details);
                                                                                ?>
                                                                                @if(isset($flights_details) && $flights_details != null && $flights_details != '' && is_array($flights_details))
                                                                                    
                                                                                    @foreach($flights_details as $value)
                                                                                        <div class="col-md-12"><h4><b>Departure Details</b></h4></div>
                                                                                        
                                                                                        <div class="initiative__item">
                                                                                            <div class="initiative-top">
                                                                                                <div class="title">
                                                                                                    <div class="from-to">
                                                                                                        <span class="from">{{ $value->departure_airport_code }} </span>
                                                                                                        <img src="{{ asset('public/images/departure.png') }}" width="15px">
                                                                                                        <span class="to">{{ $value->arrival_airport_code }} ({{ $value->departure_flight_route_type }})</span>
                                                                                                    </div>
                                                                                                    <div class="time d-none">
                                                                                                        {{ $value->departure_time }} 
                                                                                                        <i class="fa-solid fa-plane"></i>
                                                                                                        {{ $value->arrival_time }}</div>
                                                                                                    <div class="time">Airline Name : {{ $value->other_Airline_Name2 ?? '' }}</div>
                                                                                                </div>
                                                                                               
                                                                                            </div>
                                                                                            
                                                                                            <table class="initiative-table" style="border-collapse: unset;">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <th>
                                                                                                            <div class="item-thumb">
                                                                                                                <div class="image-thumb">
                                                                                                                    <img src="{{ asset('public/images/flightT.jpg') }}" style="width:100%;" alt="">
                                                                                                                </div>
                                                                                                                <div class="text">
                                                                                                                    <span>{{ $value->other_Airline_Name2 ?? '' }}</span>
                                                                                                                    <p>{{ $value->departure_flight_number ?? '' }}</p>
                                                                                                                    <span>{{ $value->departure_flight_route_type ?? '' }}</span>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </th>
                                                                                                        <td style="padding:5px">
                                                                                                            <div class="item-body" style="padding:0px;">
                                                                                                                <div class="item-from">
                                                                                                                   
                                                                                                                    <h3> <img src="{{ asset('public/images/departure.png') }}" alt="" width="25px">
                                                                                                                        {{ $value->departure_airport_code ?? '' }}</h3>
                                                                                                                    <span class="time">{{ date("h:i:sa", strtotime($value->departure_time ?? '')) }}</span>
                                                                                                                    <span class="date">{{ date("d-m-Y", strtotime($value->departure_time ?? '')) }}</span>
                                                                                                                    
                                                                                                                </div>
                                                                                                                
                                                                                                                <div class="item-to">
                                                                                                                   
                                                                                                                    <h3> <img src="{{ asset('public/images/landing.png') }}" alt="" width="25px">
                                                                                                                        {{ $value->arrival_airport_code ?? '' }}</h3>
                                                                                                                    <span class="time">{{ date("h:i:sa", strtotime($value->arrival_time ?? '')) }}</span>
                                                                                                                    <span class="date">{{ date("d-m-Y", strtotime($value->arrival_time ?? '')) }}</span>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </div>
                                                                                        <hr>
                                                                                    @endforeach
                                                                                @endif
                                                                            @endif
                                                                            
                                                                            @if(isset($return_flights_details) && $return_flights_details != null && $return_flights_details != '')
                                                                                <div class="col-md-12"><h4><b>Return Details</b></h4></div>
                                                                            
                                                                                @foreach($return_flights_details as $value1)
                                                                                    <div class="initiative__item">
                                                                                        <div class="initiative-top">
                                                                                            <div class="title">
                                                                                                <div class="from-to">
                                                                                                    <span class="from">{{ $value1->return_departure_airport_code }}</span>
                                                                                                    <img src="{{ asset('public/images/landing.png') }}" width="15px">
                                                                                                    <span class="to">{{ $value1->return_arrival_airport_code }} ({{ $value1->return_flight_route_type }})</span>
                                                                                                </div>
                                                                                                <div class="time d-none">{{ $value1->return_departure_time ?? '' }} | {{ $value1->return_arrival_time ?? '' }}</div>
                                                                                                <div class="time">Airline Name : {{ $value1->return_other_Airline_Name2 ?? '' }}</div>
                                                                                                
                                                                                            </div>
                                                                                       
                                                                                        </div>
                                                                                        
                                                                                        <table class="initiative-table" style="border-collapse: unset;">
                                                                                            <tbody>
                                                                                                <tr>
                                                                                                    <th>
                                                                                                        <div class="item-thumb">
                                                                                                            <div class="image-thumb">
                                                                                                                <img src="{{ asset('public/images/flightT.jpg') }}" style="width:100%" alt="">
                                                                                                            </div>
                                                                                                            <div class="text">
                                                                                                                <span>{{ $value1->return_other_Airline_Name2 ?? '' }}</span>
                                                                                                                <p>{{ $value1->return_departure_flight_number ?? '' }}</p>
                                                                                                                <span>{{ $value1->return_flight_route_type ?? '' }}</span>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </th>
                                                                                                    <td style="padding:5px;">
                                                                                                        <div class="item-body" style="padding:0px;">
                                                                                                            <div class="item-from">
                                                                                                                <h3><img src="{{ asset('public/images/departure.png') }}" alt="" width="25px">
                                                                                                                    {{ $value1->return_departure_airport_code ?? '' }}
                                                                                                                </h3>
                                                                                                                <?php $return_departure_time = $value1->return_departure_time ?? ''; ?>
                                                                                                                <span class="time">{{ date("h:i:sa", strtotime($return_departure_time)) }}</span>
                                                                                                                <span class="date">{{ date("d-m-Y", strtotime($return_departure_time)) }}</span>
                                                                                                            </div>
                                                                                                            
                                                                                                            <div class="item-to">
                                                                                                                <h3><img src="{{ asset('public/images/landing.png') }}" alt="" width="25px">
                                                                                                                    {{ $value1->return_arrival_airport_code ?? '' }}
                                                                                                                </h3>
                                                                                                                <?php $return_arrival_time = $value1->return_arrival_time ?? ''; ?>
                                                                                                                <span class="time">{{ date("h:i:sa", strtotime($return_arrival_time)) }}</span>
                                                                                                                <span class="date">{{ date("d-m-Y", strtotime($return_arrival_time)) }}</span>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                    
                                                                                    <hr>
                                                                                @endforeach
                                                                                <hr>
                                                                            @endif
                                                                            
                                                                        </div>
                                                </div>
                                              
                                              
                
                                                
                                            </div>
                                  </section> 
                                        @endif
                                    @endif
                                   
                                    @if(isset($itenry_res[0]->transportation_details) && $itenry_res[0]->transportation_details != null && $itenry_res[0]->transportation_details != '')
                                        <div class="row mt-2 mb-2">
                                            <div class="col-md-12">
                                            <div class="v-heading-icon clearfix mt-3">
                                                <div class="float-left">
                                                   <img src="https://umrahtech.com/public/assets/frontend/images/Icons/booking-details.png">
                                                </div>
                                                <div class="v-heading-icon-title float-left">
                                                    <h3>Transportation Details</h3>
                                              </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12">
                                                @php 
                                                    $transportation_details         = json_decode($itenry_res[0]->transportation_details);
                                                    $transportation_details_more    = json_decode($itenry_res[0]->transportation_details_more);
                                                    
                                                @endphp
                                                @if(isset($transportation_details) && $transportation_details != null && $transportation_details != '' && is_array($transportation_details))
                                                    <table class="initiative-table">
                                                                    <tbody>
                                                                        <tr>
                                                                            <th>
                                                                                <div class="item-thumb">
                                                                                    <div class="image-thumb">
                                                                                        <img src="{{ config('img_url') }}/public/uploads/package_imgs/{{  $transportation_details[0]->transportation_image ?? '' }}" style="width: 130px;" alt="">
                                                                                    </div>
                                                                                    <div class="text">
                                                                                        <span>Vehicle: {{  $transportation_details[0]->transportation_vehicle_type ?? '' }}</span>
                                                                                    </div>
                                                                                </div>
                                                                            </th>
                                                                            <td>
                                                                                <div class="item-body">
                                                                                    <div class="item-from">
                                                                                        <h3 style="font-size:1.1rem; color:#d39d00;">Pickup Location</h3>
                                                                                        <h6 style="font-size:1rem;">{{  $transportation_details[0]->transportation_pick_up_location ?? '' }}</h6>
                                                                                        <?php 
                                                                                        if($transportation_details[0]->transportation_pick_up_date != ''){
                                                                                            $pickup_date = date("d-m-Y", strtotime($transportation_details[0]->transportation_pick_up_date ?? ''));
                                                                                            $pickup_time = date("h:i:sa", strtotime($transportation_details[0]->transportation_pick_up_date ?? ''));
                                                                                        }else{
                                                                                            $pickup_date = '';
                                                                                            $pickup_time = '';
                                                                                        }
                                                                                        
                                                                                        if($transportation_details[0]->transportation_drop_of_date != ''){
                                                                                            $drop_off_date = date("d-m-Y", strtotime($transportation_details[0]->transportation_drop_of_date ?? ''));
                                                                                            $drop_off_time = date("h:i:sa", strtotime($transportation_details[0]->transportation_drop_of_date ?? ''));
                                                                                        }else{
                                                                                            $drop_off_date = '';
                                                                                            $drop_off_time = '';
                                                                                        }
                                                                                       ?>
                                                                                       <h6 style="font-size:.8rem; margin-bottom: 0.5rem;">Pickup Date:{{ $pickup_date  }} </h6>
                                                                                       <h6 style="font-size:.8rem; ">Pickup Date:{{ $pickup_time  }} </h6>
                                                                                       
                                                                                       
                                                                                       
                                                                                    </div>
                                                                                    <div class="item-to">
                                                                                         <h3 style="font-size:1.1rem;  color:#d39d00;">Drop off Location</h3>
                                                                                        <h6 style="font-size:1rem;">{{  $transportation_details[0]->transportation_drop_off_location }}</h6>
                                                                
                                                                                        <h6 style="font-size:.8rem; margin-bottom: 0.5rem;">Drop off Date:{{ $drop_off_date }} </h6>
                                                                                       <h6 style="font-size:.8rem; ">Drop off Time{{ $drop_off_time }} </h6>
                    
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            
                                                                        </tr>
                                                                         @if($transportation_details[0]->transportation_trip_type == 'Return')
                                                                          <tr>
                                                                            <th>
                                                                                <div class="item-thumb">
                                                                                    <div class="image-thumb">
                                                                                    </div>
                                                                                    <div class="text">
                                                                                      
                                                                                    </div>
                                                                                </div>
                                                                            </th>
                                                                             <td>
                                                                                <div class="item-body">
                                                                                    <div class="item-from">
                                                                                        <h3>{{  $transportation_details[0]->return_transportation_pick_up_location }}</h3>
                                                                                       
                                                                                         <h6 style="font-size:.8rem; margin-bottom: 0.5rem;">{{ date("d-m-Y", strtotime($transportation_details[0]->return_transportation_pick_up_date ?? '')) }}</h6>
                                                                                       <h6 style="font-size:.8rem; ">{{ date("h:i:sa", strtotime($transportation_details[0]->return_transportation_pick_up_date ?? '')) }}</h6>
                                                                                       
                                                                                       
                                                                                    </div>
                                                                                    <!--<div class="item-time">-->
                                                                                    <!--    <i class="fa fa-clock-o"></i>-->
                                                                                    <!--    <span>Retrun</span>-->
                                                                                    <!--</div>-->
                                                                                    <div class="item-to">
                                                                                        <h3>{{  $transportation_details[0]->return_transportation_drop_off_location }}</h3>
                                                                                        <h6 style="font-size:.8rem; margin-bottom: 0.5rem;">{{ date("d-m-Y", strtotime($transportation_details[0]->return_transportation_drop_of_date ?? '')) }}</h6>
                                                                                       <h6 style="font-size:.8rem; ">{{ date("h:i:sa", strtotime($transportation_details[0]->return_transportation_drop_of_date ?? '')) }}</h6>
                                                                                       
                                                                                       
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        
                                                                        
                                                                        @endif
                                                                        
                                                                        @if(isset($transportation_details_more) && $transportation_details_more != null && $transportation_details_more != '')
                                                                            @foreach($transportation_details_more as $tran_more)
                                                                                <tr>
                                                                                    <th>
                                                                                        <div class="item-thumb">
                                                                                            <div class="image-thumb">
                                                                                                <img src="{{ config('img_url') }}/public/uploads/package_imgs/{{  $transportation_details[0]->transportation_image }}" style="width: 130px;" alt="">
                                                                                            </div>
                                                                                            <div class="text">
                                                                                              
                                                                                            </div>
                                                                                        </div>
                                                                                    </th>
                                                                                    <td>
                                                                                        <div class="item-body">
                                                                                            <div class="item-from">
                                                                                                <h3>{{  $tran_more->more_transportation_pick_up_location }}</h3>
                                                                                                 <h6 style="font-size:.8rem; margin-bottom: 0.5rem;">{{ date("d-m-Y", strtotime($tran_more->more_transportation_pick_up_date)) }}</h6>
                                                                                                <h6 style="font-size:.8rem; ">{{ date("h:i:sa", strtotime($tran_more->more_transportation_pick_up_date)) }}</h6>
                                                                                               
                                                                                            </div>
                                                                                            <div class="item-to">
                                                                                                <h3>{{  $tran_more->more_transportation_drop_off_location }}</h3>
                                                                                               
                                                                                            </div>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        @endif
                                                                
                                                                    </tbody>
                                                                </table>
                                                @endif   
                                        </div>
                    
                                        </div>
                                    @endif
                                    
                                    <?php 
                                        if($package_type == 'tour'){
                                    ?>
                                            <section class="vochure-detail-section mt-3">
                                             <div class="v-heading-icon v-heading-top clearfix">
                                                <div class="float-left">
                                                   <img src="https://umrahtech.com/public/assets/frontend/images/Icons/hotel.png">
                                                </div>
                                                <div class="v-heading-icon-title float-left">
                                                   <h3>Visa Details</h3>
                                                </div>
                                             </div>
                                             
                                              <table class="initiative-table">
                                                    <tbody>
                                                        <tr>
                                                            <th>
                                                                <div class="item-thumb">
                                                                    <div class="image-thumb">
                                                                        <img src="{{ config('img_url') }}/public/uploads/package_imgs/{{  $itenry_res[0]->visa_image ?? '' }}" style="width:100px;" alt="">
                                                                    </div>
                                                                </div>
                                                            </th>
                                                            <td>
                                                                <div class="item-body">
                                                                    <div class="row">
                                                                        <div class="col-md-4"><h6 style="font-size:18px;">Visa Type</h6>
                                                                            <h6 style="font-size:14px; font-weight:400; text-transform: capitalize;">{{ $visa_type_arr[$visa_count] ?? ''}}</h6>
                                                                        </div>
                                                                        <div class="col-md-8"><h6 style="font-size:18px;">Visa Requirements</h6>
                                                                        <h6 style="font-size:14px; font-weight:400;">{{  $itenry_res[0]->visa_rules_regulations ?? '' }}</h6>
                                                                        
                                                                        </div>
                                                                        
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            
                                                        </tr>
                                                   
                                                        
                        <?php $visa_count++ ?>
                                                           
                                                    
                                                    </tbody>
                                                </table>
                                         </section>
                                    <?php
                                        }
                                    ?>
                                    <?php
                                        if($cart_res->Additional_services_names != '' && $cart_res->Additional_services_names != '[]'){
                                            $additional_services = json_decode($cart_res->Additional_services_names);
                                    ?>
                                            <div class="row mt-2 mb-2">
                                                <div class="col-md-12">
                                                <div class="v-heading-icon clearfix mt-3">
                                                    <div class="float-left">
                                                       <img src="https://umrahtech.com/public/assets/frontend/images/Icons/booking-details.png">
                                                    </div>
                                                    <div class="v-heading-icon-title float-left">
                                                        <h3>Additional Services</h3>
                                                  </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <table class="table">
                                                  <thead>
                                                    <tr>
                                                      <th scope="col">Name</th>
                                                      <th scope="col">Price</th>
                                                      <th scope="col">Persons</th>
                                                      <th scope="col">Days</th>
                                                      <th scope="col">Dates</th>
                                                      <th scope="col">Total</th>
                                                      
                                                    </tr>
                                                  </thead>
                                                  <tbody>
                                                      
                                                        <?php 
                                                                
                                                                foreach($additional_services as $service_res){
                                                                    ?>
                                                                    <tr>
                                                                        <td>{{ $service_res->service }}</td>
                                                                      <td>{{  $cart_res->currency." ".$service_res->service_price }}</td>
                                                                      <td>{{ $service_res->person }}</td>
                                                                      <td>{{ $service_res->Service_Days }}</td>
                                                                      <td><?php 
                                                                            if($service_res->dates != null){
                                                                                echo $service_res->dates;
                                                                            }else{
                                                                                echo '';
                                                                            }
                                                                      ?></td>
                                                                      <td>{{ $cart_res->currency }} {{ $service_res->total_price }}</td>
                                                                     
                                                                    </tr> 
                                                                    <?php
                                                                }
                                                        
                                                                            
                                                    ?>
                                            
                                                    
                                                   
                                                  </tbody>
                                                </table>
                                             
                                            </div>
                        
                                            </div>
                                    <?php 
                                        } 
                                    ?>
                                    
                                        <div class="v-heading-icon clearfix mt-3">
                                            <div class="float-left">
                                               <img src="https://umrahtech.com/public/assets/frontend/images/Icons/booking-details.png">
                                            </div>
                                            <div class="v-heading-icon-title float-left">
                                                 <?php 
                                                        if($package_type == 'tour'){
                                                    ?>
                                                <h3>Itinerary schedule</h3>
                                                <?php 
                                                        }else{
                                                            ?>
                                                <h3>What To Expect</h3>
                                                            <?php
                                                        }
                                                ?>
                                          </div>
                                        </div>  
                                        
                                    <?php 
                                        if($package_type == 'tour'){
                                    ?>
                                    @if(isset($iternery_array))
                                        @foreach($iternery_array as $itenry_res)
                                            <?php 
                                                if(isset($itenry_res[0]->tour_id)){
                                            ?>
                                                
                                                    @if($itenry_res[0]->tour_id == $cart_res->tour_id)
                                                    
                                                    @php
                                                        $iternerys_data = json_decode($itenry_res[0]->Itinerary_details);
                                                        $iternerys_data1 = json_decode($itenry_res[0]->tour_itinerary_details_1);
                                                
                                                    @endphp
                                  
                                   
                                    <ul class="itenery-ul">
                                         @if(isset($iternerys_data))
                                            @foreach($iternerys_data as $itern_data_res)
                                        <li>
                                            <h4>{{ $itern_data_res->Itinerary_title }} : {{ $itern_data_res->Itinerary_city }} </h4>
                                            <p>{{ $itern_data_res->Itinerary_content }}</p>
                                        </li>
                                            @endforeach
                                        @endif
                                        @if(isset($iternerys_data1))
                                            @foreach($iternerys_data1 as $itern_data_res)
                                        <li>
                                            <h4>{{ $itern_data_res->more_Itinerary_title }} : {{ $itern_data_res->more_Itinerary_city }} </h4>
                                            <p>{{ $itern_data_res->more_Itinerary_content }}</p>
                                        </li>
                                         @endforeach
                                        @endif
                                    </ul>
                                
                                                         @endif
                                                         
                                                           <?php 
                                                            }
                                                         ?>
                                               @endforeach
                                         @endif
                                    <?php 
                                    
                                    }else{
                                        ?>
                                            @if(isset($iternery_array))
                                     
                                                @foreach($iternery_array as $itenry_res)
                                                
                                                    @if(isset($itenry_res[0]->activity_id) && $itenry_res[0]->activity_id == $cart_res->tour_id)
                                                    
                                    
                                  
                                             <div style="padding:1rem;">{!! $itenry_res[0]->meeting_and_pickups !!}</div>
                                 
                                
                                                         @endif
                                               @endforeach
                                         @endif
                                         
                                         <?php
                                    }
                                    
                                    ?>
                                    
                                </section>
                            @endforeach
                        @endif
                   </div>
                   
                   <div class="col-md-4 col-sm-12 sidbar">
                      <div class="v-heading-icon clearfix mt-3" >
                         <div class="float-left">
                            <img src="{{asset('public/invoice/assets/img/lead.png') }}">
                         </div>
                         <div class="v-heading-icon-title float-left" style="">
                            <h3>Lead Passenger Details</h3>
                         </div>
                      </div>
                      <div class="clearfix v-section-info" style="border: 1px solid #ddd;">
                         <ul class="list-items list-items-3 list-items-4  clearfix" >
                        
                            <li><span class="text-black font-weight-bold">Full Name:</span>{{ $passenger_Det[0]->name." ".$passenger_Det[0]->lname }}</li>
                            <li><span class="text-black font-weight-bold">Gender:</span>
                              {{ $passenger_Det[0]->gender }}
                            </li>
                          
                            <li class="d-block">
                               <div class="row">
                                  <div class="col-sm-5">
                                     <span class="text-black font-weight-bold">Phone Number:</span>
                                  </div>
                                  <div class="col-sm-7">
                                     {{ $passenger_Det[0]->phone }}
                                     
                                  </div>
                               </div>
                            </li>
                            
                            <li class="d-block">
                               <div class="row">
                                  <div class="col-sm-5">
                                     <span class="text-black font-weight-bold">Email:</span>
                                  </div>
                                  <div class="col-sm-7">
                                    {{ $passenger_Det[0]->email }}
                                     
                                  </div>
                               </div>
                            </li>
                         </ul>
                      </div>
                     
                      <!-- end icon-box -->
                      
                        <div class="v-heading-icon clearfix mt-3 d-none" >
                         <div class="float-left">
                            <img src="{{asset('public/invoice/assets/img/payment1.png') }}">
                         </div>
                         <div class="v-heading-icon-title float-left" style="">
                           <h3>Payment Details</h3>
                         </div>
                      </div>
                      
                    
                      <ul class="list-items list-items-2 clearfix d-none" >
                           @if(isset($cart_data))
                           
                            @foreach($cart_data as $cart_res)
                         <li><span class="text-black font-weight-bold">{{ $cart_res->tour_name }}</span>{{ $cart_res->currency }} {{ $cart_res->tour_total_price }} </li>
                         
                                    <?php 
                                     if($cart_res->Additional_services_names != ''){
                                         $additional_services = json_decode($cart_res->Additional_services_names);
                                          foreach($additional_services as $service_res){
                                         
                                                ?>
                                            <li><span class="text-black font-weight-bold" style="width:270px !important;">{{ $service_res->service }}</span>{{ $cart_res->currency }} {{ $service_res->total_price }} </li>
                                              
                                                <?php
                                            }
                                     }
                                  
                                                        
                                ?>
                                     
                          @php
                            $currency = $cart_res->currency;
                         @endphp
                           @endforeach
                           @endif
                        
                          @php 
                                        $total = 0;
                                
                            @endphp
                            @foreach($cart_data as $cart_res)
                                @php 
                                   $total = $total + $cart_res->price
                                @endphp
                                
                            @endforeach
                            <li class="total-amout"><span class="text-black font-weight-bold" style="width:220px">Total Price</span> {{ $currency ?? "" }} {{ $total }}</li>
                        
                      </ul>
                     
                      
                      
                     
                        <div class="v-heading-icon clearfix mt-3" >
                         <div class="float-left">
                            <img src="{{asset('public/admin_package/frontend/images/lead.png') }}">
                         </div>
                         <div class="v-heading-icon-title float-left" style="">
                           <h3>Contact Information</h3>
                         </div>
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
      
        <div class="container">
            <img style="width:100%;" src="{{ asset('/public/admin_package/frontend/images/vochure-footer.png') }}" alt="letterhead">
        </div>
      
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Payment Information</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
               <form action="{{ URL::to('save_payments') }}" method="post" enctype= multipart/form-data>
                   @csrf
              <div class="modal-body">
                      <div class="v-heading-icon clearfix mt-3" >
                         <div class="float-left">
                            <img src="{{asset('public/invoice/assets/img/Transport-4.png') }}">
                         </div>
                         <div class="v-heading-icon-title float-left" style="">
                           <h3>Tour Details</h3>
                         </div>
                      </div>
                      
                    
                      <ul class="list-items list-items-2 clearfix" >
                           @if(isset($cart_data))
                           
                            @foreach($cart_data as $cart_res)
                         <li><span class="text-black font-weight-bold">{{ $cart_res->tour_name }}</span>{{ $cart_res->currency }} {{ $cart_res->price }} </li>
                          @php
                            $currency = $cart_res->currency;
                         @endphp
                           @endforeach
                           @endif
                        
                          @php 
                                        $total = 0;
                                
                            @endphp
                            @foreach($cart_data as $cart_res)
                                @php 
                                   $total = $total + $cart_res->price
                                @endphp
                                
                            @endforeach
                            <li class="total-amout"><span class="text-black font-weight-bold">Total</span> {{ $currency ?? "" }} {{ $total }}</li>
                        
                      </ul>
                      
               
                  <div class="form-row">
                     <div class="col-6">
                        <label>Payment Method</label>
                        <select class="form-control" onchange="paymentMethod()" id="payment_method" name="payment_method">
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Cash">Cash</option>
                            <option value="Card Payment">Card Payment</option>
                        </select>
                    </div>
                    <div class="col-6">
                         <label>Payment Date</label>
                         <input type="date" class="form-control" required value="<?php echo date('Y-m-d') ?>" name="payment_date" placeholder="Enter Payment Amount">
                    </div>
                    
                    <div class="col-6">
                         <label>Payment Amount</label>
                         <input type="text" class="form-control" required name="payment_am" placeholder="Enter Payment Amount">
                    </div>
                    
                    <div class="col-6">
                       <label>E mail</label>
                      <input type="email" class="form-control" required name="email" value="{{ $passenger_Det[0]->email }}" placeholder="Enter Email">
                    </div>
                    
                    <div class="col-6" id="transcation_id">
                        <label>Transaction ID</label>
                      <input type="text" class="form-control"  name="transcation_id"  placeholder="Enter Transaction ID">
                      <input type="text" class="form-control" required name="invoice_id" hidden value="{{ $id }}" placeholder="Enter Transaction ID">
                    </div>
                    
                   <div class="col-6" id="account_id">
                       <label>Account No.</label>
                      <input type="text" class="form-control"  name="account_no"  placeholder="Payment to (Account No.)" value="13785580">
                    </div>
                    
                    <div class="col-12 d-none">
                        <label>Payment Voucher</label>
                      <input type="file" class="form-control" name="voucher" placeholder="Upload Payment Voucher">
                    </div>
                  </div>
               
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
              </div>
               </form>
            </div>
          </div>
        </div>
  
        <script src="https://umrahtech.com/public/assets/frontend/js/jquery-3.4.1.min.js"></script>
        <script src="https://umrahtech.com/public/assets/frontend/js/jquery-ui.js"></script>
        <script src="https://umrahtech.com/public/assets/frontend/js/popper.min.js"></script>
        <script src="https://umrahtech.com/public/assets/frontend/js/bootstrap.min.js"></script>
        <script src="https://umrahtech.com/public/assets/frontend/js/main.js"></script>
      
        <script>
            function paymentMethod(){
                var paymentMethod = $('#payment_method').val();
                if(paymentMethod == 'Cash'){
                    $('#transcation_id').css('display','none');
                    $('#account_id').css('display','none');
                }else{
                    $('#transcation_id').css('display','block');
                    $('#account_id').css('display','block');
                }
            }
        </script>
      
    </body>
</html>