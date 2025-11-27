<?php

?>

@extends('template/frontend/userdashboard/layout/default')
 @section('content')
 <style>
     .cst_css
     {
         color: red;
    font-weight: bold;
     }
 </style>
 
    <div class="mt-5" id="">
        @if(session()->has('message'))
 <div style="width: 700px;" class="alert alert-bg-success alert-dismissible bg-success text-white border-0 fade show" role="alert">
    
   <strong>     {{session('message')}}</strong>
 </div> 
 @endif
  @if(session()->has('errors'))
 <div style="width: 700px;" class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show" role="alert">
    
   <strong>     {{session('errors')}}</strong>
 </div> 
 @endif
        <div class="row">
            <div class="col-12">
                                <div class="card" style="background: #f1f5f8;">
                                    <div class="card-body">

                                        <h4 class="header-title">Flight Bookings</h4>
                                        <p class="text-muted font-14">
                                            
                                        </p>

                                        
                                       
                                                <table style="font-weight: bold;font-family: serif;" id="example_1" class="table dt-responsive nowrap w-100">
                                                    <thead class="theme-bg-clr">
                                                        <tr>
                                                            <th>id</th>
                                                            <th>Invoice No</th>
                                                            <!--<th>MFRef</th>-->
                                                            <!--<th>Trip Type</th>-->
                                                            <th style="min-width: 250px;text-align: center;">Route</th>
                                                            <th>Total Amount</th>
                                                            <th>Receiveable Amount</th>
                                                            <th>Commission Amount</th>
                                                            <th>Payable To Provider</th>
                                                            <th>Action</th>
                                                           
                                                        </tr>
                                                    </thead>
                                                
                                                
                                                    <tbody>
                                                        <?php
                                                        if(isset($result))
                                                        {
                                                          foreach($result as $booking)
                                                          {
                                                              $booking_detail_rs=json_decode($booking->booking_detail_rs);
                                                              
                                                                // if(!isset($booking_detail_rs->Data)){
                                                                //     dd($booking_detail_rs);
                                                                // }
                                                          ?>
                                                            @if(isset($booking_detail_rs->Data))
                                                                <tr>
                                                                    <td>{{$booking->id}}</td>
                                                                     <td>{{$booking_detail_rs->Data->TripDetailsResult->TravelItinerary->TripType}}</br>
                                                                     {{$booking_detail_rs->Data->TripDetailsResult->TravelItinerary->MFRef}}</br>
                                                                         {{$booking->invoice_no}}</br>
                                                                       <p style="color:red;">{{$booking_detail_rs->Data->TripDetailsResult->BookingCreatedOn}}</p>
                                                                       <p style="color:red;">{{$booking_detail_rs->Data->TripDetailsResult->TravelItinerary->BookingStatus ?? ''}}</p>
                                                                     </td>
                                                                      
                                                                       
                                                                       
                                                                       <td style="min-width: 250px;">
                                                                       <?php
                                                                       foreach($booking_detail_rs->Data->TripDetailsResult->TravelItinerary->Itineraries[0]->ItineraryInfo->ReservationItems as $ReservationItems)
                                                                       {
                                                                           $timestamp = strtotime($ReservationItems->DepartureDateTime);
                                                                             $time_departure = date('H:i:a', $timestamp);
                                                                             $DepartureDate = date('Y-m-d', $timestamp);
                                                                             $ArrivalDateTime_get = strtotime($ReservationItems->ArrivalDateTime);
                                                                             $ArrivalDateTime = date('H:i:a', $ArrivalDateTime_get);
                                                                             $ArrivalDate = date('Y-m-d', $ArrivalDateTime_get);
                                                                             
                                                                              $minutes=$ReservationItems->JourneyDuration;
                                                                                $hours = floor($minutes / 60);
                                                                                $min = $minutes - ($hours * 60);
                                                                        ?>
                                                                        
                                                    <div class="row align-items-center text-center">
                                                    <div class="col-md mb-4 mb-md-0">
                                                        <div class="flex-content-center d-block d-lg-flex">
                                                            <div class="mr-lg-3 mb-1 mb-lg-0">
                                                                <i class="flaticon-aeroplane font-size-30 text-primary"></i>
                                                            </div>
                                                            <div class="text-lg-left">
                                                                <h6 class="font-weight-bold font-size-21 text-gray-5 mb-0">{{$time_departure}} </h6>
                                                                <span class="font-size-14 text-gray-1">{{$ReservationItems->DepartureAirportLocationCode}}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                                                                <div class="col-md mb-4 mb-md-0">
                                                        <div class="flex-content-center flex-column">
                                                            <h6 class="font-size-14 font-weight-bold text-gray-5 mb-0"><?php echo $hours.'hrs'." ".$min.'mins'; ?></h6>
                                                            <div class="width-60 border-top border-primary border-width-2 my-1"></div>
                                                            
                                                            <?php
                                                                    if($ReservationItems->StopQuantity == 0)
                                                                    {
                                                                        ?>
                                                                        <div class="font-size-14 text-gray-1">Non Stop</div>
                                                                        <?php
                                                                    }
                                                                else
                                                                {
                                                                    ?>
                                                                    <div class="font-size-14 text-gray-1"><?php print_r($ReservationItems->StopQuantity ?? '') ?> Stop</div>
                                                                    <?php
                                                                }
                                                                    ?>
                                                                    
                                                          
                                                                                                                            
                                                        </div>
                                                    </div>
                                                    <div class="col-md mb-4 mb-md-0">
                                                        <div class="flex-content-center d-block d-lg-flex">
                                                            <div class="mr-lg-3 mb-1 mb-lg-0">
                                                                <i class="d-block rotate-90 flaticon-aeroplane font-size-30 text-primary"></i>
                                                            </div>
                                                            <div class="text-lg-left">
                                                                <h6 class="font-weight-bold font-size-21 text-gray-5 mb-0">{{$ArrivalDateTime}}</h6>
                                                                <span class="font-size-14 text-gray-1">{{$ReservationItems->ArrivalAirportLocationCode}}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    </div>
                                                                       <?php
                                                                       }
                                                                       ?>
                                                                       </td>
                                                                       
                                                                       
                                                                       
                                                                       <td><?php print_r($booking->currency . ' ' .round($booking->total_price_markup,2)); ?></br>
                                                                       <?php print_r($booking->client_currency . ' ' .round($booking->client_markup_price,2)); ?>
                                                                       </td>
                                                                      <td>
                                                                         <?php
                                                                         $total_price_markup=round($booking->total_price_markup,2);
                                                                         $commission_amount=round($booking->admin_commission_amount,2);
                                                                         $receviable_am=$total_price_markup - $commission_amount;
                                                                         
                                                                         $client_markup_price=round($booking->client_markup_price,2);
                                                                         $commision_amount_exchange=round($booking->admin_commision_amount_exchange,2);
                                                                         $receviable_am1=$client_markup_price - $commision_amount_exchange;
                                                                         
                                                                         
                                                                         
                                                                         ?> 
                                                                         <?php print_r($booking->currency . ' ' .$receviable_am); ?> </br>
                                                                         <?php print_r($booking->client_currency . ' ' .$receviable_am1); ?>
                                                                      </td>
                                                                      <td><?php print_r($booking->currency . ' ' .round($booking->admin_commission_amount,2)); ?></br>
                                                                      <?php print_r($booking->admin_currency . ' ' .round($booking->admin_commision_amount_exchange,2)); ?>
                                                                      </td>
                                                                     <td><?php print_r($booking->currency . ' ' .round($booking->total_price,2)); ?></br>
                                                                      <?php print_r($booking->client_currency . ' ' .round($booking->client_without_markup_price,2)); ?>
                                                                      </td>
                                                                         
                                                                          <td>
                                                                              <?php
                                                                              if(isset($booking_detail_rs->Data->TripDetailsResult->TravelItinerary->TicketStatus))
                                                                              {
                                                                                  ?>
                                                                                  <a class="btn btn-success" href="javascript:;">{{$booking_detail_rs->Data->TripDetailsResult->TravelItinerary->TicketStatus}}</a>
                                                                                  <?php
                                                                              }
                                                                              else
                                                                              {
                                                                                  ?>
                                                                                  <a class="btn btn-success" href="{{URL::to('super_admin/flight/order/ticket/submit/')}}/{{$booking_detail_rs->Data->TripDetailsResult->TravelItinerary->MFRef}}/{{$booking->invoice_no}}">Order Ticket</a>
                                                                                  <?php
                                                                                  
                                                                              }
                                                                              ?>
                                                                              
                                                                              
                                                                              <a class="btn btn-success" target="_blank" href="https://demo6.synchronousdigital.com/flight/voucher/{{$booking->invoice_no}}">Voucher</a>
                                                                          </td>
                                                                </tr>
                                                            @endif
                                                        <?php
                                                              
                                                          }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>                                           
                                            
                                        
                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
                            </div><!-- end col-->
        </div>
    </div>
   
   
   
     
                            

                        
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>-->
          <script>
   $(document).ready(function () {
    
    $('#example_1').DataTable({
        
         scrollX: true,
        scrollY: true,
         order: [[0, 'desc']],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
        
    });
    
});

     
      function GetRefFunction(MFref)
      {
          const UniqueID = $('#click_Mfref_'+MFref+'').attr('atr_id');
          $('#UniqueID').val(UniqueID);
      
      }
</script>  
                       
 @endsection