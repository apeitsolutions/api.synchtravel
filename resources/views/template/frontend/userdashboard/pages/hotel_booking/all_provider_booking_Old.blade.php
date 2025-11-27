@extends('template/frontend/userdashboard/layout/default')
@section('content')
    <style>
        .cst_css{
            color: red;
            font-weight: bold;
        }
    </style>
    
    <div class="mt-5">
        <div class="row">
            <div class="col-12">
                <div class="card" style="background: #f1f5f8;">
                    <div class="card-body">
                        
                        <h4 class="header-title">Hotel Provider Bookings</h4>
                        <p class="text-muted font-14"></p>
                        
                        <table style="font-weight: bold;font-family: serif;" id="example_1" class="table dt-responsive nowrap w-100">
                            <thead class="theme-bg-clr">
                                <tr>
                                    <th>id</th>
                                    <th class="d-none">Booking Id</th>
                                    <th>Invoice No</th>
                                    <th>Hotel Name</th>
                                    <th>Total Amount</th>
                                    <th>Receiveable Amount</th>
                                    <th>Commission Amount</th>
                                    <th>Payable To Provider</th>
                                    <th>Exchange Rate</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $x = 1; ?>
                                @foreach($all_booking_data as $hotel_booking)
                                
                                <?php
                                    if($hotel_booking->booked != 'mobile'){
                                        $customer_subcriptions          = DB::table('customer_subcriptions')->where('Auth_key',$hotel_booking->auth_token)->first();
                                        $name                           = $customer_subcriptions->name ?? '';
                                        $lname                          = $customer_subcriptions->lname ?? '';
                                        $client_name                    = $name . $lname;
                                        $hotel_payment_details_total    = 0;
                                        
                                        if($hotel_booking->provider == 'travellanda'){
                                            $travellandadetailRS=$hotel_booking->checkavailability_rs;
                                            $travellandadetailRS=json_decode($travellandadetailRS);
                                            $travellandaSelectionRS=$hotel_booking->checkavailability_again_rs;
                                            $travellandaSelectionRS=json_decode($travellandaSelectionRS);
                                ?>
                                            <tr>
                                                <td>{{ $x++ }}</td>
                                                <td class="d-none">{{ $hotel_booking->id ?? '0'}}</td>
                                                <td>
                                                    <?php
                                                    if($hotel_booking->booked == 'Agent')
                                                    {
                                                    ?>
                                                     <b style="color:red;font-weight:bold;">Admin</b><br>
                                                <a style="color: #7e7d7b;font-weight: bold;" target="_blank" href="{{URL::to('super_admin/voucher/')}}/{{$hotel_booking->invoice_no}}">
                                                <?php print_r($hotel_booking->invoice_no); 
                                               
                                                ?>
                                                <img height="15px" width="15px" src="https://system.alhijaztours.net/public/invoice_icon.png">   
                                                </a>
                                                <?php
                                                
                                                    }
                                                    else
                                                    {
                                                ?>
                                                 <a style="color: #7e7d7b;font-weight: bold;" target="_blank" href="https://system.alhijaztours.net/voucher/{{$hotel_booking->invoice_no}}/{{$hotel_booking->provider}}">
                                                <?php print_r($hotel_booking->invoice_no); 
                                               
                                                ?>
                                                <img height="15px" width="15px" src="https://system.alhijaztours.net/public/invoice_icon.png">   
                                                </a>
                                                
                                                <?php
                                                    }
                                                ?>
                                                <br>
                                                <?php
                                                    $booking_rs=json_decode($hotel_booking->booking_rs);
                                                   $BookingReference=$booking_rs->Body->HotelBooking->BookingReference ?? '';
                                                   print_r($BookingReference);
                                                    ?>
                                                    <br>
                                                <?php
                                                    
                                                    if($hotel_booking->booking_status == 'Confirmed')
                                                    {
                                                    ?>
                                                    <a class="cst_css" href="javascript:;">Confirmed</a><br>
                                                    <?php
                                                    }
                                                     else if($hotel_booking->booking_status == 'Cancelled')
                                                    {
                                                    ?>
                                                    <a class="cst_css" href="javascript:;">Cancelled</a><br>
                                                    <?php
                                                    }
                                                    else
                                                    {
                                                    ?>
                                                   <a class="cst_css" href="{{URL::to('super_admin/booking/')}}/{{$hotel_booking->invoice_no}}">Book Now</a><br>
                                                    <?php
                                                    }
                                                    ?>
        
                                                     <?php 
                                                      $lead_passenger_details=$hotel_booking->lead_passenger_details;
                                                      $lead_passenger_details=json_decode($lead_passenger_details);
                                                      if(isset($lead_passenger_details))
                                                      {
                                                          
                                                            echo 'Customer Name :';
                                                            echo '<br>';
                                                      echo $lead_passenger_details->lead_first_name. ' ' . $lead_passenger_details->lead_last_name; 
                                                      echo '<br>';
                                                      echo '3rd Party';
                                                      }
                                                      ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    
                                                    if($hotel_booking->provider == 'travellanda')
                                                    {
                                                       print_r($travellandadetailRS->HotelName ?? ''); 
                                                        echo '<br>';
                                                    }
                                                    
                                                    ?>
                                                    
                                                    <?php
                                                    if($hotel_booking->provider == 'travellanda')
                                                    {
                                                      
                                                         if(isset($travellandaSelectionRS))
                                                        {
                                                    foreach($travellandaSelectionRS as $Room)
                                                    {
                                                       
                                                       
                                                        echo 'NumAdults: ' . ($Room->Rooms->Room->NumAdults ?? '');
                                                         echo '<br>';
                                                        echo 'NumChildren: ' . ($Room->Rooms->Room->NumChildren ?? '');
                                                        echo '<br>';
                                                       
                                                    }
                                                        } 
                                                       
                                                    }
                                                    ?>
                                                    
                                                </td>
                                                <td>
                                                    {{$hotel_booking->currency}} {{$hotel_booking->total_markup_price}}
                                                    
                                                    <br>
                                                    {{$customer_subcriptions->currency_symbol ?? ''}} {{$hotel_booking->exchange_total_markup_price ?? '0'}}
                                                    
                                                  
                                                    
                                                    
                                                </td>
                                                <td>
                                                    {{$hotel_booking->currency}} {{$hotel_booking->payable_price}}
                                                    
                                                     <br>
                                                    {{$customer_subcriptions->currency_symbol ?? ''}} {{$hotel_booking->exchange_payable_price ?? '0'}}
                                                  
                                                    
                                                    
                                                </td>
                                                <td>
                                                    {{$hotel_booking->currency}} {{$hotel_booking->client_commission_amount}}
                                                    
                                                     <br>
                                                     {{$customer_subcriptions->currency_symbol ?? ''}} {{$hotel_booking->exchange_client_commission_amount ?? '0'}}
                                                   
                                                     
                                                    
                                                    
                                                  
                                                    
                                                    
                                                </td>
                                                <td>
                                                    <?php
                                                    $booking_rs=$hotel_booking->booking_rs;
                                                    $booking_rs=json_decode($booking_rs);
                                                    ?>
                                                    {{$booking_rs->Body->HotelBooking->Currency ?? ''}} {{$booking_rs->Body->HotelBooking->TotalPrice ?? ''}}
                                                    <?php
                                                    echo '<br>';
                                                    ?>
                                                    <?php $re_price=$hotel_booking->exchange_payable_price - $hotel_booking->exchange_client_commission_amount?>
                                                    {{$customer_subcriptions->currency_symbol ?? ''}} {{$re_price ?? ''}}
                                                    
                                                </td>
                                                <td>
                                                    
                                                {{$hotel_booking->exchange_rate ?? '0'}} <br>
                                                
                                                  {{$hotel_booking->created_at ?? '0'}}
                                                    
                                                    
                                                </td>
                                                <td>
                                                    
                                                  
                                                   <a  class="btn btn-success" target="_blank" href="{{URL::to('voucher')}}/{{$hotel_booking->invoice_no}}">Voucher</a>  
                                                    
                                                </td>
                                            </tr>
                                <?php
                                        }
                                        if($hotel_booking->provider == 'hotelbeds'){
                                            $hotelbeddetailRQ=$hotel_booking->checkavailability_rq;
                                            $hotelbeddetailRQ=json_decode($hotelbeddetailRQ);
                                            $hotelbedSelectionRS=$hotel_booking->checkavailability_again_rs;
                                            $hotelbedSelectionRS=json_decode($hotelbedSelectionRS);
                                ?>
                                            <tr>
                                                <td>{{ $x++ }}</td>
                                                <td class="d-none">{{ $hotel_booking->id ?? '0'}}</td>
                                                <td>
                                                   <?php
                                                    if($hotel_booking->booked == 'Agent')
                                                    {
                                                    ?>
                                                    <b style="color:red;font-weight:bold;">Admin</b><br>
                                                <a style="color: #7e7d7b;font-weight: bold;" target="_blank" href="{{URL::to('voucher')}}/{{$hotel_booking->invoice_no}}">
                                                <?php print_r($hotel_booking->invoice_no); 
                                               
                                                ?>
                                                <img height="15px" width="15px" src="https://system.alhijaztours.net/public/invoice_icon.png">   
                                                </a>
                                                <?php
                                                
                                                    }
                                                    else
                                                    {
                                                ?>
                                                <b style="color:red;font-weight:bold;">Customer</b><br>
                                                    <?php
                                                    $booking_rs=json_decode($hotel_booking->booking_rs);
                                                   $BookingReference=$booking_rs->booking->reference ?? '';
                                                   print_r($BookingReference);
                                                    ?>
                                                    <br>
                                                 <a style="color: #7e7d7b;font-weight: bold;" target="_blank" href="{{URL::to('voucher')}}/{{$hotel_booking->invoice_no}}">
                                                <?php print_r($hotel_booking->invoice_no); 
                                               
                                                ?>
                                                <img height="15px" width="15px" src="https://system.alhijaztours.net/public/invoice_icon.png">   
                                                </a>
                                                
                                                <?php
                                                    }
                                                ?>
                                                
                                               
        <?php
        echo '<br>';
                                                    
                                                    if($hotel_booking->booking_status == 'Confirmed')
                                                    {
                                                    ?>
                                                    <a class="cst_css" href="javascript:;">Confirmed</a><br>
                                                    <?php
                                                    }
                                                     else if($hotel_booking->booking_status == 'Cancelled')
                                                    {
                                                    ?>
                                                    <a class="cst_css" href="javascript:;">Cancelled</a><br>
                                                    <?php
                                                    }
                                                    else
                                                    {
                                                    ?>
                                                   <a class="cst_css" href="{{URL::to('super_admin/booking/')}}/{{$hotel_booking->invoice_no}}">Book Now</a><br>
                                                    <?php
                                                    }
                                                    ?>                                                 
        <?php 
                                                 $lead_passenger_details=$hotel_booking->lead_passenger_details;
                                                $lead_passenger_details=json_decode($lead_passenger_details);
                                                if(isset($lead_passenger_details))
                                                {
                                                    
                                                      echo 'Customer Name:';
                                                      echo '<br>';
                                                  echo $lead_passenger_details->lead_first_name. ' ' . $lead_passenger_details->lead_last_name;  
                                                }
                                                ?>
                                                <br>
                                                 <b style="color:red;font-weight:bold;">3rd Party</b>
                                                </td>
                                                <td>
                                                    <?php
                                                    print_r($hotelbedSelectionRS->hotel->name ?? '');
                                                    
                                                    ?>
                                            <h6 style="font-size: .8rem;"><i class="mdi mdi-calendar-start"></i> {{$hotel_booking->check_in}} | <i class="mdi mdi-calendar-end"></i> {{$hotel_booking->check_out}}</h6>
                                            <h6 style="font-size: .8rem;"><i class="mdi mdi-calendar-start"></i> {{$hotel_booking->rooms}} | <i class="mdi mdi-calendar-start"></i> {{$hotel_booking->adults}} | <i class="mdi mdi-calendar-end"></i> {{$hotel_booking->childs}}</h6>
                                                    
                                                    <?php
                                                    
                                                      
                                                       if(isset($hotelbedSelectionRS->hotel))
                                                       {
                                                    foreach($hotelbedSelectionRS->hotel->rooms as $Room)
                                                    {
                                                       
                                                        foreach($Room->rates as $rates)
                                                    {
                                                        echo 'RoomName: ' . ($Room->name ?? '');
                                                        echo '</br>';
                                                        echo 'boardName: ' . ($rates->boardName ?? '');
                                                         echo '</br>';
                                                        
                                                        // echo 'RoomPrice: '. ($hotelbedSelectionRS->hotel->currency . $rates->net ?? '');  
                                                    }
                                                        
                                                        
                                                    }
                                                       } 
                                                       
                                                   
                                                    ?>
                                                    
                                                </td>
                                                <td>
                                                    {{$customer_subcriptions->currency_symbol ?? ''}} {{$hotel_booking->exchange_total_markup_price ?? '0'}}
                                                    <br>
                                                    {{$hotel_booking->admin_exchange_currency}} {{$hotel_booking->admin_exchange_total_markup_price ?? '0'}}
        
                                                </td>
                                                <td>
                                                    {{$customer_subcriptions->currency_symbol ?? ''}} {{$hotel_booking->exchange_payable_price ?? '0'}}
                                                    <br>
                                                    {{$hotel_booking->admin_exchange_currency}} {{$hotel_booking->admin_exchange_amount}}
                                                    
                                                  
                                                    
                                                    
                                                </td>
                                                <td>
                                                    {{$customer_subcriptions->currency_symbol ?? ''}} {{$hotel_booking->exchange_client_commission_amount ?? '0'}}
                                                     <br>
                                                   {{$hotel_booking->admin_exchange_currency}} {{$hotel_booking->exchange_admin_commission_amount}}
                                                     
                                                    
                                                    
                                                  
                                                    
                                                    
                                                </td>
                                                <td>
                                                    <?php
                                                    $booking_rs=json_decode($hotel_booking->booking_rs);
                                                   $totalNet=$booking_rs->booking->hotel->totalNet ?? '';
                                                   $currency_p=$booking_rs->booking->hotel->currency ?? '';
                                                   print_r($currency_p . ' ' . $totalNet);
                                                   echo '<br>';
                                                   
                                                   $total_big_am=(float)$hotel_booking->admin_exchange_amount- (float)$hotel_booking->exchange_admin_commission_amount;
                                                   print_r('GBP' . ' ' . $total_big_am);
                                                    ?>
                                                  
                                                    
                                                    
                                                </td>
                                                <td>
                                                    
                                                {{$hotel_booking->admin_exchange_rate ?? '0'}}<br>
                                                {{$hotel_booking->created_at ?? '0'}}
                                                  
                                                    
                                                    
                                                </td>
                                                <td>
                                                    
                                                  
                                                   <a  class="btn btn-success" target="_blank" href="{{URL::to('voucher')}}/{{$hotel_booking->invoice_no}}">Voucher</a>  
                                                    
                                                </td>
                                            </tr>
                                <?php
                                        }
                                        if($hotel_booking->provider == 'hotels'){
                                            $checkavailability_rs=$hotel_booking->checkavailability_rs;
                                            $checkavailability_rs=json_decode($checkavailability_rs);
                                            $checkavailability_again_rs=$hotel_booking->checkavailability_again_rs;
                                            $checkavailability_again_rs=json_decode($checkavailability_again_rs);
                                ?>
                                            <tr>
                                                <td>{{ $x++ }}</td>
                                                <td class="d-none">{{ $hotel_booking->id ?? '0'}}</td>
                                                <td>
                                                   <?php
                                                    if($hotel_booking->booked == 'Agent')
                                                    {
                                                    ?>
                                                    <b style="color:red;font-weight:bold;">Admin</b><br>
                                                <a style="color: #7e7d7b;font-weight: bold;" target="_blank" href="{{URL::to('voucher')}}/{{$hotel_booking->invoice_no}}">
                                                <?php print_r($hotel_booking->invoice_no); 
                                               
                                                ?>
                                                <img height="15px" width="15px" src="https://system.alhijaztours.net/public/invoice_icon.png">   
                                                </a>
                                                <?php
                                                
                                                    }
                                                    else
                                                    {
                                                ?>
                                                <b style="color:red;font-weight:bold;">Customer</b><br>
                                                    
                                                 <a style="color: #7e7d7b;font-weight: bold;" target="_blank" href="{{URL::to('voucher')}}/{{$hotel_booking->invoice_no}}">
                                                <?php print_r($hotel_booking->invoice_no); 
                                               
                                                ?>
                                                <img height="15px" width="15px" src="https://system.alhijaztours.net/public/invoice_icon.png">   
                                                </a>
                                                
                                                <?php
                                                    }
                                                ?>
                                                
                                               
        <?php
        echo '<br>';
                                                    
                                                    if($hotel_booking->booking_status == 'Confirmed')
                                                    {
                                                    ?>
                                                    <a class="cst_css" href="javascript:;">Confirmed</a><br>
                                                    <?php
                                                    }
                                                     else if($hotel_booking->booking_status == 'Cancelled')
                                                    {
                                                    ?>
                                                    <a class="cst_css" href="javascript:;">Cancelled</a><br>
                                                    <?php
                                                    }
                                                    else
                                                    {
                                                    ?>
                                                   <a class="cst_css" href="{{URL::to('super_admin/booking/')}}/{{$hotel_booking->invoice_no}}">Book Now</a><br>
                                                    <?php
                                                    }
                                                    ?>                                                 
        <?php 
                                                 $lead_passenger_details=$hotel_booking->lead_passenger_details;
                                                $lead_passenger_details=json_decode($lead_passenger_details);
                                                if(isset($lead_passenger_details))
                                                {
                                                    
                                                      echo 'Customer Name:';
                                                      echo '<br>';
                                                  echo $lead_passenger_details->lead_first_name. ' ' . $lead_passenger_details->lead_last_name;  
                                                }
                                                ?>
                                                <br>
                                                 <b style="color:red;font-weight:bold;">Hotels</b>
                                                </td>
                                                <td>
                                                    <?php
                                                    print_r($checkavailability_rs->property_name ?? '');
                                                    
                                                    ?>
                                            <h6 style="font-size: .8rem;"><i class="mdi mdi-calendar-start"></i> {{$hotel_booking->check_in}} | <i class="mdi mdi-calendar-end"></i> {{$hotel_booking->check_out}}</h6>
                                            <h6 style="font-size: .8rem;"><i class="mdi mdi-calendar-start"></i> {{$hotel_booking->rooms}} | <i class="mdi mdi-calendar-start"></i> {{$hotel_booking->adults}} | <i class="mdi mdi-calendar-end"></i> {{$hotel_booking->childs}}</h6>
                                                    
                                                    <?php
                                                    
                                                      
                                                       if(isset($checkavailability_again_rs))
                                                       {
                                                    foreach($checkavailability_again_rs as $Room)
                                                    {
                                                       
                                                        
                                                        echo 'RoomName: ' . ($Room->id ?? '');
                                                        echo '<br>';
                                                        echo 'boardName: ' . ($rates->room_type_name ?? '');
                                                         echo '<br>';
                                                        
                                                        
                                                    
                                                        
                                                        
                                                    }
                                                       } 
                                                       
                                                   
                                                    ?>
                                                    
                                                </td>
                                                <td>
                                                    {{$customer_subcriptions->currency_symbol ?? ''}} {{$hotel_booking->exchange_total_markup_price ?? '0'}}
                                                    <br>
                                                    {{$hotel_booking->admin_exchange_currency}} {{$hotel_booking->admin_exchange_total_markup_price ?? '0'}}
        
                                                </td>
                                                <td>
                                                    {{$customer_subcriptions->currency_symbol ?? ''}} {{$hotel_booking->exchange_payable_price ?? '0'}}
                                                    <br>
                                                    {{$hotel_booking->admin_exchange_currency}} {{$hotel_booking->admin_exchange_amount}}
                                                    
                                                  
                                                    
                                                    
                                                </td>
                                                <td>
                                                    {{$customer_subcriptions->currency_symbol ?? ''}} {{$hotel_booking->exchange_client_commission_amount ?? '0'}}
                                                     <br>
                                                   {{$hotel_booking->admin_exchange_currency}} {{$hotel_booking->exchange_admin_commission_amount}}
                                                     
                                                    
                                                    
                                                  
                                                    
                                                    
                                                </td>
                                                <td>
                                                    <?php
                                                    if(isset($checkavailability_again_rs))
                                                       {
                                                    $price_week_type=$checkavailability_again_rs[0]->price_week_type;
                                                    if($price_week_type == 'for_all_days')
                                                    {
                                                        $price=$checkavailability_again_rs[0]->price_all_days_wi_markup;
                                                    }
                                                    else
                                                    {
                                                      $price=$checkavailability_again_rs[0]->weekdays_price_wi_markup + $checkavailability_again_rs[0]->weekends_price_wi_markup;  
                                                    }
                                                       }
                                                       else
                                                       {
                                                         $price=0;  
                                                       }
                                                    
                                                   echo $checkavailability_rs->currency_symbol ?? '';  print_r(' ' .$price);
                                                   echo '<br>';
                                                   
                                                   $total_big_am=$hotel_booking->admin_exchange_amount-$hotel_booking->exchange_admin_commission_amount;
                                                   print_r('GBP' . ' ' . $total_big_am);
                                                    ?>
                                                  
                                                    
                                                    
                                                </td>
                                                <td>
                                                    
                                                {{$hotel_booking->admin_exchange_rate ?? '0'}}<br>
                                                {{$hotel_booking->created_at ?? '0'}}
                                                  
                                                    
                                                    
                                                </td>
                                                <td>
                                                    
                                                  
                                                   <a  class="btn btn-success" target="_blank" href="{{URL::to('voucher')}}/{{$hotel_booking->invoice_no}}">Voucher</a>  
                                                    
                                                </td>
                                            </tr>
                                <?php
                                        }
                                    }
                                ?>
                                @endforeach
                            </tbody>
                        </table>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
        
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
    </script>
    
@endsection