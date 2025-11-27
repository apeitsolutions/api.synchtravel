<?php

// print_r($hotel_booking);die();

?>


@extends('template/frontend/userdashboard/layout/default')
 @section('content')
 
 
<div class="modal fade" id="bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">View Payment Details</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
           <div style="width: 400px;margin-top: 20px; display:none;" id='msg' class="alert alert-primary alert-dismissible bg-primary text-white border-0 fade show" role="alert">
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    <strong></strong>
</div>
           
            <div class="modal-body">
               <form class="row form-group" id="form">
                  
                 <div class="col-md-4">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Invoice No</label>
                        <input type="text" id="invoice_no" class="form-control" readonly value="">
                    </div>
                 </div>
                 <div class="col-md-4">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Check In</label>
                        <input type="text" id="checkin" class="form-control" readonly value="">
                    </div>
                 </div>  
                 <div class="col-md-4">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Check Out</label>
                        <input type="text" id="checkout" class="form-control" readonly value="">
                    </div>
                 </div>  
                 <div class="col-md-4">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Rooms</label>
                        <input type="text" id="rooms" class="form-control" readonly value="">
                    </div>
                 </div>  
                 <div class="col-md-4">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Adults</label>
                        <input type="text" id="adults" class="form-control" readonly value="">
                    </div>
                 </div>  
                 <div class="col-md-4">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Children</label>
                        <input type="text" id="children" class="form-control" readonly value="">
                    </div>
                 </div>
                 <div class="col-md-4">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Customer Name</label>
                        <input type="text" id="customer_name" class="form-control" readonly value="">
                    </div>
                 </div>
                 <div class="col-md-4">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Customer Email</label>
                        <input type="email" id="customer_email" class="form-control" readonly value="">
                    </div>
                 </div>
                 <div class="col-md-4">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Hotel Name</label>
                        <input type="text" id="hotel_name" class="form-control" readonly value="">
                    </div>
                 </div>
                 <div class="col-md-4">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Room Name</label>
                        <input type="text" id="room_name" class="form-control" readonly value="">
                    </div>
                 </div>
                 <div class="col-md-4">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Room Price</label>
                        <input type="text" id="room_price" class="form-control" readonly value="">
                    </div>
                 </div>
                 <div class="col-md-4">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Total Price</label>
                        <input type="text" id="total_amount" class="form-control" readonly value="">
                    </div>
                 </div>
                 <div class="col-md-4">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Recieved Amount</label>
                        <input type="text" id="recieved_amount" class="form-control"  value="">
                    </div>
                 </div>
                 <div class="col-md-4">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Remaining Amount</label>
                        <input type="text" id="remaining_amount" class="form-control" readonly value="">
                    </div>
                 </div>
                 <div class="col-md-4">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Amount Paid</label>
                        <input type="text" id="amount_paid" class="form-control" readonly value="">
                    </div>
                 </div>

                    <div class="mb-3">
                        <button  style="float: right;" id="submitpaymentform" class="btn btn-primary">submit</button>
                    </div>
                   
               </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
 
 
 <div class="row mt-5">
     
    <ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a href="#Travellanda" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
            <i class="mdi mdi-home-variant d-md-none d-block"></i>
            <span class="d-none d-md-block">Travellanda</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="#HotelBeds" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
            <i class="mdi mdi-account-circle d-md-none d-block"></i>
            <span class="d-none d-md-block">HotelBeds</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="#TBO" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
            <i class="mdi mdi-settings-outline d-md-none d-block"></i>
            <span class="d-none d-md-block">TBO</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="#Ratehawk" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
            <i class="mdi mdi-settings-outline d-md-none d-block"></i>
            <span class="d-none d-md-block">Ratehawk</span>
        </a>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane show active" id="Travellanda">
        <div class="row">
            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <h4 class="header-title">View Recevied Booking</h4>
                                        <p class="text-muted font-14">
                                            
                                        </p>

                                        
                                       
                                                <table id="example_1" class="table dt-responsive nowrap w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>id</th>
                                                            <th>Invoice No</th>
                                                            <th>Provider</th>
                                                            <th>Hotel Name</th>
                                                             <th>Room Details</th>
                                                            
                                                            <th>Customer Name</th>
                                                            <th>Client Name</th>
                                                            <th>Total Amount</th>
                                                            <th>Status</th>
                                                            <th>Payment Details</th>
                                                            <th>Book Now</th>
                                                            <th>Booking Cancell</th>
                                                        </tr>
                                                    </thead>
                                                
                                                
                                                    <tbody>
                                                        @foreach($travellanda_hotel_booking as $travellanda_hotel_booking)
                                                        
                                                        <?php
                                                        
                                                        $customer_subcriptions=DB::table('customer_subcriptions')->where('Auth_key',$travellanda_hotel_booking->token)->first();
                                                        $hotel_payment_details=DB::table('hotel_payment_details')->where('hotel_search_id',$travellanda_hotel_booking->search_id)->sum('amount_paid');
                                                       
                                                     
                                                        $name=$customer_subcriptions->name;
                                                        $lname=$customer_subcriptions->lname;
                                                        $client_name=$name . $lname;
                                                       
                                                        
                                                        ?>
                                                        
                                                        <?php
                                                        if($travellanda_hotel_booking->provider == 'travellanda')
                                                        {
                                                           
                                                        
                                                        
                                                        ?>
                                                        
                                                        <tr>
                                                            <td>{{$travellanda_hotel_booking->id}}</td>
                                                            <td><?php print_r($travellanda_hotel_booking->search_id); ?></td>
                                                            <td>{{$travellanda_hotel_booking->provider}}</td>
                                                            
                                                            <?php
                                                            $hotelbeddetailRQ=$travellanda_hotel_booking->hotelbeddetailRQ;
                                                            $hotelbeddetailRQ=json_decode($hotelbeddetailRQ);
                                                            
                                                             $travellandadetailRS=$travellanda_hotel_booking->travellandadetailRS;
                                                            $travellandadetailRS=json_decode($travellandadetailRS);
                                                            
                                                            $travellandaSelectionRS=$travellanda_hotel_booking->travellandaSelectionRS;
                                                            $travellandaSelectionRS=json_decode($travellandaSelectionRS);
                                                        //   print_r($travellandaSelectionRS);
                                                            
                                                            
                                                            
                                                            ?>
                                                            
                                                            <td>
                                                                <?php
                                                                
                                                                if($travellanda_hotel_booking->provider == 'travellanda')
                                                                {
                                                                   print_r($travellandadetailRS->HotelName ?? ''); 
                                                                }
                                                                
                                                                ?>
                                                                
                                                                
                                                                
                                                            </td>
                                                            <td>
                                                                <?php
                                                                if($travellanda_hotel_booking->provider == 'travellanda')
                                                                {
                                                                  
                                                                     if(isset($travellandaSelectionRS))
                                                                    {
                                                                foreach($travellandaSelectionRS as $Room)
                                                                {
                                                                    echo 'RoomName:  ' . ($Room->Rooms->Room->RoomName ?? '');
                                                                    echo '</br>';
                                                                    echo 'NumAdults: ' . ($Room->Rooms->Room->NumAdults ?? '');
                                                                     echo '</br>';
                                                                    echo 'NumChildren: ' . ($Room->Rooms->Room->NumChildren ?? '');
                                                                    echo '</br>';
                                                                    echo 'RoomPrice: '. ($Room->Rooms->Room->RoomPrice ?? '');
                                                                }
                                                                    } 
                                                                   
                                                                }
                                                                ?>
                                                                
                                                                
                                                            </td>
                                                           
                                                            <td><?php 
                                                             $lead_passenger_details=$travellanda_hotel_booking->lead_passenger_details;
                                                            $lead_passenger_details=json_decode($lead_passenger_details);
                                                            if(isset($lead_passenger_details))
                                                            {
                                                              echo $lead_passenger_details->lead_first_name. ' ' . $lead_passenger_details->lead_last_name;  
                                                            }
                                                            ?></td>
                                                            <td><?php print_r($client_name); ?></td>
                                                            <td>
                                                                
                                                                <?php
                                                                if($travellanda_hotel_booking->provider == 'travellanda')
                                                                {
                                                                  
                                                                     if(isset($travellandaSelectionRS))
                                                                    {
                                                                        print_r($travellandaSelectionRS[0]->TotalPrice ?? '');
                                                                    } 
                                                                   
                                                                }
                                                                ?>
                                                                
                                                                
                                                            </td>
                                                            <td>
    
    
        <?php
        if(isset($travellandaSelectionRS[0]->TotalPrice))
        {
        if($hotel_payment_details == 0)
       {
        ?>
        <a class="btn btn-info" href="javascript:;">UnPaid</a>
         <?php
                                                                    
         }
        ?>
        <?php
        if($hotel_payment_details < $travellandaSelectionRS[0]->TotalPrice && $hotel_payment_details > 0)
       {
        ?>
        <a class="btn btn-secondary" href="javascript:;">Partial Paid</a>
          <?php
          }
           ?>
        <?php
         if($hotel_payment_details == $travellandaSelectionRS[0]->TotalPrice)
         {
          ?>
         <a class="btn btn-success" href="javascript:;">Paid</a>
          <?php
         }
        }
        
         ?>
    
    
   

                                                                
                                                                
                                                            </td>
                                                            <td>
                                                                <?php
                                                                if(isset($travellanda_hotel_booking->lead_passenger_details))
                                                                {
                                                                $lead_passenger_details=$travellanda_hotel_booking->lead_passenger_details ?? '';
                                                                $lead_passenger_details=json_decode($lead_passenger_details);
                                                                $CustomerName=$lead_passenger_details->lead_first_name. ' ' . $lead_passenger_details->lead_last_name;
                                                                $Customeremail=$lead_passenger_details->lead_email;
                                                                }
                                                                if(isset($travellandaSelectionRS))
                                                                 {
                                                                foreach($travellandaSelectionRS as $Room)
                                                                {
                                                                    $RoomName=$Room->Rooms->Room->RoomName ?? '';
                                                                    $RoomPrice=$Room->Rooms->Room->RoomPrice ?? '';
                                                                    
                                                                }
                                                                } 
                                                                    
                                                                    if(isset($travellandaSelectionRS))
                                                                    {
                                                                        $TotalPrice=$travellandaSelectionRS[0]->TotalPrice ?? '';
                                                                    } 
                                                                ?>
                                                                <?php
                                                                if(isset($travellandaSelectionRS[0]->TotalPrice))
                                                                {
                                                                 if($hotel_payment_details == $travellandaSelectionRS[0]->TotalPrice)
                                                                    {
                                                                    ?>
                                                                     <a class="btn btn-success" href="javascript:;">Payment Paid</a>   
                                                                        
                                                                    <?php
                                                                    }
                                                                
                                                                    else
                                                                    {
                                                                ?>
                                                                
                                                                 <a class="btn btn-success" href="javascript:;" onClick="paymentFunction({{$travellanda_hotel_booking->search_id}})" 
                                                                 atr_id="{{$travellanda_hotel_booking->search_id  ?? ''}}"
                                                                 atr_provider="{{$travellanda_hotel_booking->provider  ?? ''}}"
                                                                 atr_check_in="{{$travellanda_hotel_booking->check_in  ?? ''}}"
                                                                 atr_check_out="{{$travellanda_hotel_booking->check_out  ?? ''}}"
                                                                 atr_rooms="{{$travellanda_hotel_booking->rooms  ?? ''}}"
                                                                 atr_total_passenger="{{$travellanda_hotel_booking->total_passenger  ?? ''}}"
                                                                  atr_child="{{$travellanda_hotel_booking->child  ?? ''}}"
                                                                  atr_CustomerName="{{$CustomerName  ?? ''}}"
                                                                  atr_Customeremail="{{$Customeremail  ?? ''}}"
                                                                  atr_hotel_name="{{$travellandadetailRS->HotelName ?? ''}}"
                                                                   atr_RoomName="{{$RoomName ?? ''}}"
                                                                   atr_RoomPrice="{{$RoomPrice ?? ''}}"
                                                                   atr_TotalPrice="{{$TotalPrice ?? ''}}"
                                                                   
                                                                    atr_remaining_amount="{{$travellanda_hotel_booking->remaining_amount ?? ''}}"
                                                                     atr_paid_amount="{{$hotel_payment_details ?? ''}}"
                                                                 id="click_payment_popup_{{$travellanda_hotel_booking->search_id}}"
                                                                 data-bs-toggle="modal" data-bs-target="#bs-example-modal-lg">Payment Details</a> 
                                                                 <?php
                                                                    }
                                                                }
                                                                 ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                               
                                                                
                                                                if($travellanda_hotel_booking->booking_status == 'Confirmed')
                                                                {
                                                                ?>
                                                                <a class="btn btn-success" href="javascript:;">Confirmed</a>
                                                                <?php
                                                                }
                                                                 else if($travellanda_hotel_booking->booking_status == 'Cancelled')
                                                                {
                                                                ?>
                                                                <a class="btn btn-success" href="javascript:;">Cancelled</a>
                                                                <?php
                                                                }
                                                                else
                                                                {
                                                                ?>
                                                                <a class="btn btn-info" href="{{URL::to('super_admin/process')}}/{{$travellanda_hotel_booking->search_id}}/{{$travellanda_hotel_booking->provider}}" target="_blank">Book Now</a>
                                                                <?php
                                                                }
                                                                ?>
                                                                
                                                            </td>
                                                               <td ><a class="btn btn-info" href="https://alhijaztours.net/voucher/{{$travellanda_hotel_booking->search_id}}/{{$travellanda_hotel_booking->provider}}" target="_blank">Booking Cancell</a></td>
                                                        </tr>
                                                        <?php
                                                        }
                                                        ?>
                                                       
                                                    
                                                        @endforeach
                                                    </tbody>
                                                </table>                                           
                                            
                                        
                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
                            </div><!-- end col-->
        </div>
    </div>
    <div class="tab-pane" id="HotelBeds">
       <div class="row">
            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <h4 class="header-title">Hotelbeds Recevied Booking</h4>
                                        <p class="text-muted font-14">
                                            
                                        </p>

                                        
                                       
                                                <table id="example_2" class="table dt-responsive nowrap w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>id</th>
                                                            <th>Invoice No</th>
                                                            <th>Provider</th>
                                                            <th>Hotel Name</th>
                                                             <th>Room Details</th>
                                                            <th>Customer Name</th>
                                                            <th>Client Name</th>
                                                            <th>Total Amount</th>
                                                            <th>Status</th>
                                                            <th>Payment Details</th>
                                                            <th>Book Now</th>
                                                            <th>Booking Cancell</th>
                                                        </tr>
                                                    </thead>
                                                
                                                
                                                    <tbody>
                                                        @foreach($hotelbeds_hotel_booking as $hotelbeds_hotel_booking)
                                                        
                                                        <?php
                                                        
                                                        $customer_subcriptions=DB::table('customer_subcriptions')->where('Auth_key',$hotelbeds_hotel_booking->token)->first();
                                                        $hotel_payment_details=DB::table('hotel_payment_details')->where('hotel_search_id',$hotelbeds_hotel_booking->search_id)->sum('amount_paid');
                                                        $name=$customer_subcriptions->name;
                                                        $lname=$customer_subcriptions->lname;
                                                        $client_name=$name . $lname;
                                                       
                                                        
                                                        ?>
                                                        
                                                        <?php
                                                        if($hotelbeds_hotel_booking->provider == 'hotelbeds')
                                                        {
                                                           
                                                        
                                                        
                                                        ?>
                                                        
                                                        <tr>
                                                            <td>{{$hotelbeds_hotel_booking->id}}</td>
                                                            <td><?php print_r($hotelbeds_hotel_booking->search_id); ?></td>
                                                            <td>{{$hotelbeds_hotel_booking->provider}}</td>
                                                            
                                                            <?php
                                                            $hotelbeddetailRQ=$hotelbeds_hotel_booking->hotelbeddetailRQ;
                                                            $hotelbeddetailRQ=json_decode($hotelbeddetailRQ);
                                                            $hotelbedSelectionRS=$hotelbeds_hotel_booking->hotelbedSelectionRS;
                                                            $hotelbedSelectionRS=json_decode($hotelbedSelectionRS);
                                                            // print_r($hotelbedSelectionRS);
                                                            
                                                            
                                                            
                                                            
                                                            ?>
                                                            
                                                            <td>
                                                                <?php
                                                                print_r($hotelbedSelectionRS->hotel->name ?? '');
                                                                
                                                                ?>
                                                                
                                                                
                                                                
                                                            </td>
                                                            <td>
                                                                <?php
                                                                
                                                                  
                                                                   if(isset($hotelbedSelectionRS))
                                                                   {
                                                                foreach($hotelbedSelectionRS->hotel->rooms as $Room)
                                                                {
                                                                    echo 'RoomName:  ' . ($Room->name ?? '');
                                                                    foreach($Room->rates as $rates)
                                                                {
                                                                    echo '</br>';
                                                                    echo 'boardName: ' . ($rates->boardName ?? '');
                                                                     echo '</br>';
                                                                    
                                                                    echo 'RoomPrice: '. ($hotelbedSelectionRS->hotel->currency . $rates->net ?? '');  
                                                                }
                                                                    
                                                                    
                                                                }
                                                                   } 
                                                                   
                                                               
                                                                ?>
                                                              
                                                                
                                                                
                                                            </td>
                                                           
                                                            <td><?php 
                                                             $lead_passenger_details=$hotelbeds_hotel_booking->lead_passenger_details;
                                                            $lead_passenger_details=json_decode($lead_passenger_details);
                                                            if(isset($lead_passenger_details))
                                                            {
                                                              echo $lead_passenger_details->lead_first_name. ' ' . $lead_passenger_details->lead_last_name;  
                                                            }
                                                            ?></td>
                                                            <td><?php print_r($client_name); ?></td>
                                                            <td>
                                                                
                                                              <?php
                                                               if(isset($hotelbedSelectionRS))
                                                                   {
                                                             echo $hotelbedSelectionRS->hotel->currency. ' ' . $hotelbedSelectionRS->hotel->totalNet;
                                                                   }
                                                              ?>  
                                                                
                                                                
                                                            </td>
                                                            <td>
                                                                
         <?php
        if(isset($hotelbedSelectionRS))
        {
        if($hotel_payment_details == 0)
       {
        ?>
        <a class="btn btn-info" href="javascript:;">UnPaid</a>
         <?php
                                                                    
         }
        ?>
        <?php
        if($hotel_payment_details < $hotelbedSelectionRS->hotel->totalNet && $hotel_payment_details > 0)
       {
        ?>
        <a class="btn btn-secondary" href="javascript:;">Partial Paid</a>
          <?php
          }
           ?>
        <?php
         if($hotel_payment_details == $hotelbedSelectionRS->hotel->totalNet)
         {
          ?>
         <a class="btn btn-success" href="javascript:;">Paid</a>
          <?php
         }
        }
        
         ?>
      
                                                            </td>
                                                            
                                                             <td>
                                                                <?php
                                                                if(isset($hotelbeds_hotel_booking->lead_passenger_details))
                                                                {
                                                                $lead_passenger_details=$hotelbeds_hotel_booking->lead_passenger_details ?? '';
                                                                $lead_passenger_details=json_decode($lead_passenger_details);
                                                                $CustomerName=$lead_passenger_details->lead_first_name. ' ' . $lead_passenger_details->lead_last_name;
                                                                $Customeremail=$lead_passenger_details->lead_email;
                                                                }
                                                                
                                                                
                                                                  
                                                                   if(isset($hotelbedSelectionRS))
                                                                   {
                                                                foreach($hotelbedSelectionRS->hotel->rooms as $Room)
                                                                {
                                                                   $RoomName=$Room->name ?? '';
                                                                    foreach($Room->rates as $rates)
                                                                {
                                                                   
                                                                    
                                                                     
                                                                    $RoomPrice=$rates->net ?? '';  
                                                                }
                                                                    
                                                                    
                                                                }
                                                                   } 
                                                                   
                                                              
                                                                    
                                                                    if(isset($hotelbedSelectionRS))
                                                                    {
                                                                        $TotalPrice=$hotelbedSelectionRS->hotel->totalNet ?? '';
                                                                    } 
                                                                ?>
                                                                <?php
                                                                if(isset($hotelbedSelectionRS->hotel->totalNet))
                                                                {
                                                                 if($hotel_payment_details == $hotelbedSelectionRS->hotel->totalNet)
                                                                    {
                                                                    ?>
                                                                     <a class="btn btn-success" href="javascript:;">Payment Paid</a>   
                                                                        
                                                                    <?php
                                                                    }
                                                                
                                                                    else
                                                                    {
                                                                ?>
                                                                
                                                                 <a class="btn btn-success" href="javascript:;" onClick="paymentFunction({{$hotelbeds_hotel_booking->search_id}})" 
                                                                 atr_id="{{$hotelbeds_hotel_booking->search_id  ?? ''}}"
                                                                 atr_provider="{{$hotelbeds_hotel_booking->provider  ?? ''}}"
                                                                 atr_check_in="{{$hotelbeds_hotel_booking->check_in  ?? ''}}"
                                                                 atr_check_out="{{$hotelbeds_hotel_booking->check_out  ?? ''}}"
                                                                 atr_rooms="{{$hotelbeds_hotel_booking->rooms  ?? ''}}"
                                                                 atr_total_passenger="{{$hotelbeds_hotel_booking->total_passenger  ?? ''}}"
                                                                  atr_child="{{$hotelbeds_hotel_booking->child  ?? ''}}"
                                                                  atr_CustomerName="{{$CustomerName  ?? ''}}"
                                                                  atr_Customeremail="{{$Customeremail  ?? ''}}"
                                                                  atr_hotel_name="{{$hotelbedSelectionRS->hotel->name ?? ''}}"
                                                                   atr_RoomName="{{$RoomName ?? ''}}"
                                                                   atr_RoomPrice="{{$RoomPrice ?? ''}}"
                                                                   atr_TotalPrice="{{$TotalPrice ?? ''}}"
                                                                   
                                                                    atr_remaining_amount="{{$hotelbeds_hotel_booking->remaining_amount ?? ''}}"
                                                                     atr_paid_amount="{{$hotel_payment_details ?? ''}}"
                                                                 id="click_payment_popup_{{$hotelbeds_hotel_booking->search_id}}"
                                                                 data-bs-toggle="modal" data-bs-target="#bs-example-modal-lg">Payment Details</a> 
                                                                 <?php
                                                                    }
                                                                }
                                                                 ?>
                                                            </td>
                                                            
                                                            <td>
                                                                <?php
                                                                
                                                                if($hotelbeds_hotel_booking->booking_status == 'Confirmed')
                                                                {
                                                                ?>
                                                                <a class="btn btn-success" href="javascript:;">Confirmed</a>
                                                                <?php
                                                                }
                                                                 else if($hotelbeds_hotel_booking->booking_status == 'Cancelled')
                                                                {
                                                                ?>
                                                                <a class="btn btn-success" href="javascript:;">Cancelled</a>
                                                                <?php
                                                                }
                                                                else
                                                                {
                                                                ?>
                                                               <a class="btn btn-info" href="{{URL::to('super_admin/process')}}/{{$hotelbeds_hotel_booking->search_id}}/{{$hotelbeds_hotel_booking->provider}}">Book Now</a>
                                                                <?php
                                                                }
                                                                ?>
                                                                
                                                                </td>
                                                        
                                                            <td ><a class="btn btn-info" href="https://alhijaztours.net/voucher/{{$hotelbeds_hotel_booking->search_id}}/{{$hotelbeds_hotel_booking->provider}}" target="_blank">Booking Cancell</a></td>
                                                        </tr>
                                                        <?php
                                                        }
                                                        ?>
                                                       
                                                    
                                                        @endforeach
                                                    </tbody>
                                                </table>                                           
                                            
                                        
                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
                            </div><!-- end col-->
        </div>
    </div>
    <div class="tab-pane" id="TBO">
       <div class="row">
            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <h4 class="header-title">Tbo Recevied Booking</h4>
                                        <p class="text-muted font-14">
                                            
                                        </p>

                                        
                                       
                                                <table id="example_3" class="table dt-responsive nowrap w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>id</th>
                                                            <th>Invoice No</th>
                                                            <th>Provider</th>
                                                            <th>Hotel Name</th>
                                                             <th>Room Details</th>
                                                            
                                                            <th>Customer Name</th>
                                                            <th>Client Name</th>
                                                            <th>Total Amount</th>
                                                            <th>Status</th>
                                                             <th>Payment Details</th>
                                                            <th>Book Now</th>
                                                            <th>Booking Cancell</th>
                                                        </tr>
                                                    </thead>
                                                
                                                
                                                    <tbody>
                                                        @foreach($tbo_hotel_booking as $tbo_hotel_booking)
                                                        
                                                        <?php
                                                        
                                                        $customer_subcriptions=DB::table('customer_subcriptions')->where('Auth_key',$tbo_hotel_booking->token)->first();
                                                        $hotel_payment_details=DB::table('hotel_payment_details')->where('hotel_search_id',$tbo_hotel_booking->search_id)->sum('amount_paid');
                                                        $name=$customer_subcriptions->name;
                                                        $lname=$customer_subcriptions->lname;
                                                        $client_name=$name . $lname;
                                                       
                                                        
                                                        ?>
                                                        
                                                        <?php
                                                        if($tbo_hotel_booking->provider == 'tbo')
                                                        {
                                                           
                                                        
                                                        
                                                        ?>
                                                        
                                                        <tr>
                                                            <td>{{$tbo_hotel_booking->id}}</td>
                                                            <td><?php print_r($tbo_hotel_booking->search_id); ?></td>
                                                            <td>{{$tbo_hotel_booking->provider}}</td>
                                                            
                                                            <?php
                                                           $tboSelectionRS=$tbo_hotel_booking->tboSelectionRS ?? '';
                                                           $tboSelectionRS=json_decode($tboSelectionRS);
                                                            
                                                            
                                                            
                                                            ?>
                                                            
                                                            <td>
                                                                
                                                                
                                                                
                                                            </td>
                                                            <td>
                                                                
                                                                <?php
                                                                
                                                                  
                                                                   if(isset($tboSelectionRS->HotelResult[0]->Rooms))
                                                                   {
                                                                foreach($tboSelectionRS->HotelResult[0]->Rooms as $Room)
                                                                {
                                                                    echo 'RoomName:  ' . ($Room->Name[0] ?? '');
                                                               
                                                                    echo '</br>';
                                                                    echo 'boardName: ' . ($Room->Inclusion ?? '');
                                                                     echo '</br>';
                                                                    
                                                                    echo 'RoomPrice: '. ($tboSelectionRS->HotelResult[0]->Currency . $Room->TotalFare ?? '');  
                                                                
                                                                    
                                                                    
                                                                }
                                                                   }
                                                                   else
                                                                   {
                                                                       echo 'else';
                                                                   }
                                                                   
                                                               
                                                                ?>
                                                                
                                                            </td>
                                                           
                                                            <td><?php 
                                                             $lead_passenger_details=$tbo_hotel_booking->lead_passenger_details ?? '';
                                                            $lead_passenger_details=json_decode($lead_passenger_details);
                                                            if(isset($lead_passenger_details))
                                                            {
                                                              echo $lead_passenger_details->lead_first_name. ' ' . $lead_passenger_details->lead_last_name;  
                                                            }
                                                            ?></td>
                                                            <td><?php print_r($client_name); ?></td>
                                                            <td>
                                                                
                                                               
                                                                <?php
                                                                if(isset($tboSelectionRS->HotelResult[0]->Rooms))
                                                                   {
                                                                       $grand_total=0;
                                                                  foreach($tboSelectionRS->HotelResult[0]->Rooms as $Room)
                                                                {
                                                                    $grand_total=$grand_total+$Room->TotalFare;
                                                                   }
                                                                   echo  $tboSelectionRS->HotelResult[0]->Currency. ' '  . $grand_total;
                                                                   }
                                                                   else
                                                                   {
                                                                       echo 'else';
                                                                   }
                                                                   
                                                                   
                                                                ?>
                                                                
                                                            </td>
                                                            <td>
                                                                
        <?php
       
        if($hotel_payment_details == 0)
       {
        ?>
        <a class="btn btn-info" href="javascript:;">UnPaid</a>
         <?php
                                                                    
         }
        ?>
        <?php
         $grand_total=0;
        if($hotel_payment_details < $grand_total && $hotel_payment_details > 0)
       {
        ?>
        <a class="btn btn-secondary" href="javascript:;">Partial Paid</a>
          <?php
          }
           ?>
        <?php
         if($hotel_payment_details == $grand_total)
         {
          ?>
         <a class="btn btn-success" href="javascript:;">Paid</a>
          <?php
         }
       
        
         ?>
  
                                                            </td>
                                                             <td>
                                                                <?php
                                                                if(isset($tbo_hotel_booking->lead_passenger_details))
                                                                {
                                                                $lead_passenger_details=$tbo_hotel_booking->lead_passenger_details ?? '';
                                                                $lead_passenger_details=json_decode($lead_passenger_details);
                                                                $CustomerName=$lead_passenger_details->lead_first_name. ' ' . $lead_passenger_details->lead_last_name;
                                                                $Customeremail=$lead_passenger_details->lead_email;
                                                                }
                                                                
                                                                
                                                                  
                                                                  
                                                                  if(isset($tboSelectionRS->HotelResult[0]->Rooms))
                                                                   {
                                                                foreach($tboSelectionRS->HotelResult[0]->Rooms as $Room)
                                                                {
                                                             $RoomName=$Room->Name[0] ?? '';
                                                               
                                                                    
                                                                  $RoomPrice=$Room->TotalFare ?? '';  
                                                                
                                                                    
                                                                    
                                                                }
                                                                   }
                                                                   
                                                                  if(isset($tboSelectionRS->HotelResult[0]->Rooms))
                                                                   {
                                                                       $grand_total=0;
                                                                  foreach($tboSelectionRS->HotelResult[0]->Rooms as $Room)
                                                                {
                                                                    $grand_total=$grand_total+$Room->TotalFare;
                                                                    
                                                                   }
                                                                 
                                                                   }
                                                                  
                                                                  
                                                                  
                                                                   
                                                                   
                                                              
                                                                    
                                                                    
                                                                        $TotalPrice=$grand_total;
                                                                   
                                                                ?>
                                                                <?php
                                                               
                                                                 if($hotel_payment_details == $grand_total)
                                                                    {
                                                                    ?>
                                                                     <a class="btn btn-success" href="javascript:;">Payment Paid</a>   
                                                                        
                                                                    <?php
                                                                    }
                                                                
                                                                    else
                                                                    {
                                                                ?>
                                                                
                                                                 <a class="btn btn-success" href="javascript:;" onClick="paymentFunction({{$tbo_hotel_booking->search_id}})" 
                                                                 atr_id="{{$tbo_hotel_booking->search_id  ?? ''}}"
                                                                 atr_provider="{{$tbo_hotel_booking->provider  ?? ''}}"
                                                                 atr_check_in="{{$tbo_hotel_booking->check_in  ?? ''}}"
                                                                 atr_check_out="{{$tbo_hotel_booking->check_out  ?? ''}}"
                                                                 atr_rooms="{{$tbo_hotel_booking->rooms  ?? ''}}"
                                                                 atr_total_passenger="{{$tbo_hotel_booking->total_passenger  ?? ''}}"
                                                                  atr_child="{{$tbo_hotel_booking->child  ?? ''}}"
                                                                  atr_CustomerName="{{$CustomerName  ?? ''}}"
                                                                  atr_Customeremail="{{$Customeremail  ?? ''}}"
                                                                  atr_hotel_name="{{$RoomName ?? ''}}"
                                                                   atr_RoomName="{{$RoomName ?? ''}}"
                                                                   atr_RoomPrice="{{$RoomPrice ?? ''}}"
                                                                   atr_TotalPrice="{{$TotalPrice ?? ''}}"
                                                                   
                                                                    atr_remaining_amount="{{$tbo_hotel_booking->remaining_amount ?? ''}}"
                                                                     atr_paid_amount="{{$hotel_payment_details ?? ''}}"
                                                                 id="click_payment_popup_{{$tbo_hotel_booking->search_id}}"
                                                                 data-bs-toggle="modal" data-bs-target="#bs-example-modal-lg">Payment Details</a> 
                                                                 <?php
                                                                    }
                                                                
                                                                 ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                               
                                                                if($tbo_hotel_booking->booking_status == 'Confirmed')
                                                                {
                                                                ?>
                                                                <a class="btn btn-success" href="javascript:;">Confirmed</a>
                                                                <?php
                                                                }
                                                                else if($tbo_hotel_booking->booking_status == 'Cancelled')
                                                                {
                                                                ?>
                                                                <a class="btn btn-success" href="javascript:;">Cancelled</a>
                                                                <?php
                                                                }
                                                                
                                                                else
                                                                {
                                                                ?>
                                                               <a class="btn btn-info" href="{{URL::to('super_admin/process')}}/{{$tbo_hotel_booking->search_id}}/{{$tbo_hotel_booking->provider}}">Book Now</a>
                                                                <?php
                                                                }
                                                                ?>
                                                                
                                                                </td>
                                                        
                                                        <td ><a class="btn btn-info" href="https://alhijaztours.net/voucher/{{$tbo_hotel_booking->search_id}}/{{$tbo_hotel_booking->provider}}" target="_blank">Booking Cancell</a></td>
                                                        </tr>
                                                        <?php
                                                        }
                                                        ?>
                                                       
                                                    
                                                        @endforeach
                                                    </tbody>
                                                </table>                                           
                                            
                                        
                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
                            </div><!-- end col-->
        </div>
    </div>
    <div class="tab-pane" id="Ratehawk">
        <div class="row">
            
            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <h4 class="header-title">RateHawk Recevied Booking</h4>
                                        <p class="text-muted font-14">
                                            
                                        </p>

                                        
                                       
                                                <table id="example_4" class="table dt-responsive nowrap w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>id</th>
                                                            <th>Invoice No</th>
                                                            <th>Provider</th>
                                                            <th>Hotel Name</th>
                                                             <th>Room Details</th>
                                                            
                                                            <th>Customer Name</th>
                                                            <th>Client Name</th>
                                                            <th>Total Amount</th>
                                                            <th>Status</th>
                                                            <th>Book Now</th>
                                                            <th>Booking Cancell</th>
                                                        </tr>
                                                    </thead>
                                                
                                                
                                                    <tbody>
                                                        @foreach($ratehawk_hotel_booking as $ratehawk_hotel_booking)
                                                        
                                                        <?php
                                                        
                                                        $customer_subcriptions=DB::table('customer_subcriptions')->where('Auth_key',$ratehawk_hotel_booking->token)->first();
                                                        $name=$customer_subcriptions->name;
                                                        $lname=$customer_subcriptions->lname;
                                                        $client_name=$name . $lname;
                                                       
                                                        
                                                        ?>
                                                        
                                                        <?php
                                                        if($ratehawk_hotel_booking->provider == 'ratehawk')
                                                        {
                                                           
                                                        
                                                        
                                                        ?>
                                                        
                                                        <tr>
                                                            <td>{{$ratehawk_hotel_booking->id}}</td>
                                                            <td><?php print_r($ratehawk_hotel_booking->search_id); ?></td>
                                                            <td>{{$ratehawk_hotel_booking->provider}}</td>
                                                            
                                                            <?php
                                                            
                                                            $ratehawk_details_rs1=$ratehawk_hotel_booking->ratehawk_details_rs1;
                                                            $ratehawk_details_rs1=json_decode($ratehawk_details_rs1);
                                                            
                                                            $ratehawk_details_rs=$ratehawk_hotel_booking->ratehawk_details_rs;
                                                            $ratehawk_details_rs=json_decode($ratehawk_details_rs);
                                                            
                                                            
                                                            ?>
                                                            
                                                            <td>
                                                                <?php
                                                                
                                                                if(isset($ratehawk_details_rs))
                                                                {
                                                                   echo $ratehawk_details_rs->name ?? ''; 
                                                                }
                                                                
                                                                
                                                                ?>
                                                                
                                                                
                                                                
                                                            </td>
                                                            <td>
                                                                
                                                               <?php
                                                                
                                                                  
                                                                   if(isset($ratehawk_details_rs1))
                                                                   {
                                                                foreach($ratehawk_details_rs1->hotels[0]->rates as $rates)
                                                                {
                                                                    echo 'RoomName:  ' . ($rates->room_name ?? '');
                                                               
                                                                    echo '</br>';
                                                                    echo 'boardName: ' . ($rates->meal ?? '');
                                                                     echo '</br>';
                                                                    
                                                                    echo 'RoomPrice: '. ($rates->payment_options->payment_types[0]->currency_code . $rates->payment_options->payment_types[0]->amount ?? '');  
                                                                
                                                                    
                                                                    
                                                                }
                                                                   } 
                                                                   
                                                               
                                                                ?> 
                                                                
                                                            </td>
                                                           
                                                            <td><?php 
                                                             $lead_passenger_details=$ratehawk_hotel_booking->lead_passenger_details;
                                                            $lead_passenger_details=json_decode($lead_passenger_details);
                                                            if(isset($lead_passenger_details))
                                                            {
                                                              echo $lead_passenger_details->lead_first_name. ' ' . $lead_passenger_details->lead_last_name;  
                                                            }
                                                            ?></td>
                                                            <td><?php print_r($client_name); ?></td>
                                                            <td>
                                                                
                                                                <?php
                                                                
                                                                  
                                                                   if(isset($ratehawk_details_rs1))
                                                                   {
                                                                       $grand_total=0;
                                                                foreach($ratehawk_details_rs1->hotels[0]->rates as $rates)
                                                                {
                                                                   $grand_total=$grand_total + $rates->payment_options->payment_types[0]->amount;
                                                                    
                                                                
                                                                    
                                                                    
                                                                }
                                                                 echo $ratehawk_details_rs1->hotels[0]->rates[0]->payment_options->payment_types[0]->currency_code . ' ' . $grand_total; 
                                                                   } 
                                                                   
                                                               
                                                                ?> 
                                                                
                                                                
                                                            </td>
                                                            <td>
    <div class="dropdown">
    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <?php
        if($ratehawk_hotel_booking->payment_status == 0)
       {
        ?>
        UnPaid
         <?php
                                                                    
         }
        ?>
        <?php
        if($ratehawk_hotel_booking->payment_status == 1)
       {
        ?>
        Partial Paid
          <?php
          }
           ?>
        <?php
         if($ratehawk_hotel_booking->payment_status == 2)
         {
          ?>
          Fully Partial Paid
          <?php
         }
                                                                
         ?>
    </a>
    
    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
        <!--<a class="dropdown-item" href="#">Paid</a>-->
        <a class="dropdown-item" href="{{URL::to('super_admin/partial_paid_booking')}}/{{$ratehawk_hotel_booking->search_id}}/{{$ratehawk_hotel_booking->provider}}">Partial Paid</a>
        <a class="dropdown-item" href="{{URL::to('super_admin/fully_partial_paid_booking')}}/{{$ratehawk_hotel_booking->search_id}}/{{$ratehawk_hotel_booking->provider}}">Fully Partial Paid</a>
    </div>
</div>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                
                                                                if($ratehawk_hotel_booking->booking_status == 'Confirmed')
                                                                {
                                                                ?>
                                                                <a class="btn btn-success" href="javascript:;">Confirmed</a>
                                                                <?php
                                                                }
                                                                else if($ratehawk_hotel_booking->booking_status == 'Cancelled')
                                                                {
                                                                ?>
                                                                <a class="btn btn-success" href="javascript:;">Cancelled</a>
                                                                <?php
                                                                }
                                                                else
                                                                {
                                                                ?>
                                                                <a class="btn btn-info" href="{{URL::to('super_admin/process')}}/{{$ratehawk_hotel_booking->search_id}}/{{$ratehawk_hotel_booking->provider}}">Book Now</a>
                                                                <?php
                                                                }
                                                                ?>
                                                               
                                                            </td>
                                                        
                                                        <td ><a class="btn btn-info" href="https://alhijaztours.net/voucher/{{$ratehawk_hotel_booking->search_id}}/{{$ratehawk_hotel_booking->provider}}" target="_blank">Booking Cancell</a></td>
                                                        </tr>
                                                        <?php
                                                        }
                                                        ?>
                                                       
                                                    
                                                        @endforeach
                                                    </tbody>
                                                </table>                                           
                                            
                                        
                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
                            </div><!-- end col-->
        </div>
    </div>
</div>
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
                            
</div> <!-- end row-->
                        
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
           
      <script>
     
      function paymentFunction(searchedid)
      {
          const searched_id = $('#click_payment_popup_'+searchedid+'').attr('atr_id');
          $('#invoice_no').val(searched_id);
          const provider = $('#click_payment_popup_'+searchedid+'').attr('atr_provider');
          
           const check_in = $('#click_payment_popup_'+searchedid+'').attr('atr_check_in');
           $('#checkin').val(check_in);
            const check_out = $('#click_payment_popup_'+searchedid+'').attr('atr_check_out');
            $('#checkout').val(check_out);
             const rooms = $('#click_payment_popup_'+searchedid+'').attr('atr_rooms');
             $('#rooms').val(rooms);
              const total_passenger = $('#click_payment_popup_'+searchedid+'').attr('atr_total_passenger');
              $('#adults').val(total_passenger);
               const child = $('#click_payment_popup_'+searchedid+'').attr('atr_child');
               $('#children').val(child);
                const CustomerName = $('#click_payment_popup_'+searchedid+'').attr('atr_CustomerName');
                $('#customer_name').val(CustomerName);
                 const Customeremail = $('#click_payment_popup_'+searchedid+'').attr('atr_Customeremail');
                 $('#customer_email').val(Customeremail);
                 const hotel_name = $('#click_payment_popup_'+searchedid+'').attr('atr_hotel_name');
                 $('#hotel_name').val(hotel_name);
                 
                 const room_name = $('#click_payment_popup_'+searchedid+'').attr('atr_RoomName');
                 $('#room_name').val(room_name);
                 
                 const room_price = $('#click_payment_popup_'+searchedid+'').attr('atr_RoomPrice');
                 $('#room_price').val(room_price);
                 
                 const total_price = $('#click_payment_popup_'+searchedid+'').attr('atr_TotalPrice');
                 $('#total_amount').val(total_price);
                 
                
                 
                 const amount_paid = $('#click_payment_popup_'+searchedid+'').attr('atr_paid_amount');
                 console.log(amount_paid);
                 $('#amount_paid').val(amount_paid);
                 
                  const remaining_amount = parseFloat(total_price) - parseFloat(amount_paid);
                  
                  //  const remaining_amount = $('#click_payment_popup_'+searchedid+'').attr('atr_remaining_amount');
                 $('#remaining_amount').val(remaining_amount);
          console.log(total_price);
          console.log(amount_paid);
            console.log(remaining_amount);
          
       
      }
      
      
     
    $(document).ready(function () {

       

        $('#recieved_amount').on('change',function(){
            recieved_amount  = $(this).val();
            remaining_amount = $('#remaining_amount').val();
            remaining_amount_final = parseFloat(remaining_amount) - parseFloat(recieved_amount);
            $('#remaining_amount').val(remaining_amount_final);
            $('#amount_paid').val(recieved_amount);
        });

    });
   
    
   


</script>                  
      <script>
      $(function () {
          
        
        

        $('#form').on('submit', function (e) {
var invoice_no = $('#invoice_no').val();
       
        var amount_paid = $('#amount_paid').val();
        var remaining_amount = $('#remaining_amount').val();
        var recieved_amount = $('#recieved_amount').val();
        var total_amount =$('#total_amount').val();
          e.preventDefault();

          $.ajax({
            type: 'post',
            url: 'payment_detact/'+invoice_no,
            
            data: {
                'invoice_no':invoice_no,
                 'amount_paid':amount_paid,
                  'remaining_amount':remaining_amount,
                  'recieved_amount':recieved_amount,
                  'total_amount':total_amount,
                
            },
            headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            },
            success: function (data) {
              console.log(data);
             $('#msg').html(data).fadeIn('slow');
            $('#msg').html("payment successfully").fadeIn('slow') //also show a success message 
             $('#msg').delay(2000).fadeOut('slow');
            //  location.reload();
            setTimeout("window.location = ''",2000);
            }
          });

        });

      });
    </script>                     
 @endsection