<?php

//print_r($all_booking);die();

?>

@extends('template/frontend/userdashboard/layout/default')
@section('content')



    <style>
        .nav-link{
            color: #575757;
            font-size: 18px;
        }
        
        .print-table th, .print-table td {
            width: 150px;
            word-wrap: break-word;
        }
    </style>
  
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">

                
                <div class="row">
                  <div class="col-12">
                      <div class="page-title-box">
                        
                        <h4 class="page-title">Client Transection</h4>
                        <div style="display: flex;justify-content: space-between;">
                            <h6 class="" style="font-size: 1rem;">Client Name: {{ $customer->name ?? ''}} {{ $customer->lname ?? ''}}</h6>
                            <h6 class="" style="font-size: 1rem;">Company Name: {{ $customer->company_name ?? ''}}</h6>
                        </div>
                      </div>
                  </div>
              </div>
                
                <div class="row">
                    <div class="col-12">
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert" id="alert_hide">
                                <strong> {{ session('error') }}</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        <div class="row mb-2">
                            <div class="col-sm-5"></div>
                            <div class="col-sm-7"><div class="text-sm-end"></div></div>
                        </div>
                            
                        <div class="row">
                            <table id="myTable"  class="display table wrap table-bordered" style="width:100%;">
                               
                                <thead class="theme-bg-clr">
                                    <tr>
                                        <th style="text-align: center;">Sr</th>
                                      
                                        <th style="text-align: center;" id="descripton_th" width="20%">Description</th>
                                        <th style="text-align: center;">Debit</th>
                                        <th style="text-align: center;">Credit</th>
                                        <th style="text-align: center;">Available Balance</th>
                                        <!--<th style="text-align: center;">Lead Passenger Details</th>-->
                                        <th style="text-align: center;">Date</th>
                                    </tr>
                                </thead>
                                
                                <tbody style="text-align: center;" id="tbody_all_data_acc">
                                  
                                
                                    @if(isset($all_booking))
                                    <?php
                                    $total_balance=0;
                                    $count=1;
                                    ?>
                                        @foreach($all_booking as $ledgers)
                                        
                                      <?php
                                      $hotels= DB::table('hotels_bookings')->where('invoice_no',$ledgers->invoice_no)->first();
                                      
                                      ?>
                                     <tr role="row" @if($ledgers->type == 'payment_insert') style="background-color:#06b1a1;color:white;" @endif>
                                                <td>
                                                <?php
                                                if($ledgers->type == 'payment_insert')
                                                {
                                                    echo $count;
                                                }
                                                if($ledgers->type == 'booked')
                                                {
                                                    echo $count;
                                                }
                                                
                                                ?>
                                                </td>
                                                    
                                               
                                                <td style="text-align:left !important">
                                                    <p style="margin: 0px;">
                                                        
                                                        <?php
                                                        if($ledgers->type == 'booked')
                                                        {
                                                            
                                                         ?>
                                                          <b>
                                                                    
                                                                    <a href="{{$customer->webiste_Address}}/hotel_booking_invoice/{{$hotels->invoice_no}}" target="blank"><?php echo $hotels->invoice_no ?? ''; ?>
                                                                        <img height="15px" width="15px" src="{{ asset('public/invoice_icon.png') }}">
                                                                    </a>
                                                           <p style="color:red;">{{$hotels->status}}</p>
                                                        </b>
                                                         <?php
                                                        }
                                                        if($ledgers->type == 'payment_insert')
                                                        {
                                                        ?>
                                                         <b>
                                                           
                                                                    <a href="javascript:;" style="color:white;">Invoice Id :<?php echo $ledgers->invoice_no; ?>
                                                                       
                                                                    </a>
                                                           
                                                        </b>
                                                        <?php
                                                        }
                                                        ?>
                                                       
                                                        <?php
                                                        if($ledgers->type == 'booked')
                                                        {
                                                                $lead_passenger_Data = json_decode($hotels->lead_passenger_data);
                                                                $reservation_response = json_decode($hotels->reservation_response);
                                                               //print_r($reservation_response);die();
                                                                
                                                                if($reservation_response){
                                                           
                                                                   echo "<h6 style='font-size: .8rem;' title='".$reservation_response->hotel_details->hotel_name."'> ".$reservation_response->hotel_details->hotel_name." (".$reservation_response->hotel_details->destinationName.")</h6>";
                                                                    echo "<h6 style='font-size: .8rem;'><i class='mdi mdi-calendar-start'></i> ".date('d-m-Y',strtotime($reservation_response->hotel_details->checkIn))." | <i class='mdi mdi-calendar-end'></i> ".date('d-m-Y',strtotime($reservation_response->hotel_details->checkOut))."</h6>";
                                                                    echo "<hr style='margin: 0.5rem;'>";
                                                                    echo "<h6 style='font-size: .8rem;'><i class='mdi mdi-calendar-start'></i> ".$hotels->total_rooms." | <i class='mdi mdi-calendar-start'></i> ".$hotels->total_adults." | <i class='mdi mdi-calendar-end'></i> ".$hotels->total_childs."</h6>";
                                                                }
                                                                    
                                                                       
                                                                    
                                                        }     
                                                            
                                                            
                                                            
                                                        ?>
                                                    </p>
                                                </td>
                                                
                                                <td>
                                                    <?php
                                                        if($ledgers->type == 'booked')
                                                        {
                                                            ?>
                                                  {{$credit_limits->currency ?? ''}} {{$ledgers->transection_amount}}   
                                                    <?php
                                                        }
                                                    ?>
                                                </td>
                                                <td>
                                                   <?php
                                                        if($ledgers->type == 'payment_insert')
                                                        {
                                                            ?>
                                                  {{$credit_limits->currency ?? ''}} {{$ledgers->transection_amount}}   
                                                    <?php
                                                        }
                                                    ?> 
                                                  
                                                </td>
                                                <td>
                                                   {{$credit_limits->currency ?? ''}}  {{$ledgers->remaining_amount}} 
                                                </td>
                                                
                                                <!--<td>-->
                                                <!--    {{$ledgers->lead_passenger ?? ''}}<br>-->
                                                <!--{{ $lead_passenger_Data->lead_email ?? ''}}<br> {{ $lead_passenger_Data->lead_phone ?? ''}}-->
                                                <!--</td>-->
                                                
                                                <td>
                                                    <?php
                                                        if($ledgers->type == 'booked')
                                                        {
                                                            print_r(date('d-m-Y',strtotime($hotels->creationDate)));
                                                        }
                                                        if($ledgers->type == 'payment_insert')
                                                        {
                                                            print_r(date('d-m-Y',strtotime($ledgers->updated_at)));
                                                        }
                                                        
                                                         ?>
                                                         
                                                         
                                                    
                                                    </td>
                                            </tr>
                                                   
                                        
                                        
                                           
                                           
                                             
                                            <?php
                                            
                                            $count=$count+1;
                                            
                                            ?>
                                        @endforeach
                                        
                                    @endif
                                    
                                    
                                            
                                </tbody>
                                
                            </table>
                        </div>
                    </div>
                </div>  
            </div> 
        </section>
    </div>
    
   



@endsection



