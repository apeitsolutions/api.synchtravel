
<!DOCTYPE html>
<html class="no-js" lang="en">



<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <!-- Meta Tags -->
  
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="ThemeMarch">
  <!-- Site Title -->
  <title>Alhijaztours  - Travel Booking System</title>
  <link rel="stylesheet" href="{{asset('public/invoice/assets/css/style.css')}}">
</head>

<body>
      @if(isset($cart_data))
@foreach($cart_data as $cart_res)
  <div class="cs-container">
    <div class="cs-invoice cs-style1">
      <div class="cs-invoice_in" id="download_section">
        <div class="cs-invoice_head cs-type1 cs-mb25">
          <div class="cs-invoice_left">
            <p class="cs-invoice_number cs-primary_color cs-mb0 cs-f16"><b class="cs-primary_color">Invoice No:</b> {{ $id }}</p>
          </div>
          <div class="cs-invoice_right cs-text_right">
            <div class="cs-logo cs-mb5"><img src="{{asset('public/admin_package/frontend/images/logo.png')}}"alt="Logo"></div>
            <!--<img src="https://client.synchronousdigital.com/public/admin_package/assets/images/logo1-1.png" style="max-width: 15%;">-->
          </div>
        </div>
         @if(isset($iternery_array))
                                @foreach($iternery_array as $itenry_res)
                                    
        <div class="cs-invoice_head cs-mb10">
          <div class="cs-invoice_left">
            <p>
            
                @if($payment_Status->confirm == 1)
                  
                    @if($package_Type->pakage_type == 'tour')
                    <b class="cs-primary_color cs-f22">Tentative: {{ $itenry_res[0]->time_duration ?? '' }} Days</b> <br>
                    @else
                    <b class="cs-primary_color cs-f22">Tentative: {{ $tour_data->duration }} Hours</b> <br>
                    @endif
                @else
                    @if($package_Type->pakage_type == 'tour')
                    <b class="cs-primary_color cs-f22">Confirmed: {{ $itenry_res[0]->time_duration ?? '' }} Days</b> <br>
                     @else
                    <b class="cs-primary_color cs-f22">Confirmed: {{ $tour_data->duration }} Hours</b> <br>
                    @endif
                @endif
                
                <b class="cs-primary_color">Booked By {{ $passenger_Det[0]->name." ".$passenger_Det[0]->lname }}</b> <br>
                
              <?php
              
              $dateValue = strtotime($inv_data->created_at);
$year = date('Y',$dateValue);
$monthName = date('F',$dateValue);
$monthNo = date('d',$dateValue);
printf("%s, %d, %s\n", $monthName, $monthNo, $year);
              ?>
              
            </p>
            
            <!--<h5>Payment Status :</h5>-->
                @if($package_Type->pakage_type == 'tour')
                
                    @if($recieved_package_Payments == 0)
                        <b class="cs-primary_color cs-f22">UNPAID</b> <br>
                    @elseif($recieved_package_Payments > 0 && $recieved_package_Payments < $total_package_Payments->price - $recieved_package_Payments)
                        <b class="cs-primary_color cs-f22">PARTIALLY PAID</b> <br>
                    @else
                        <b class="cs-primary_color cs-f22">PAID</b> <br>
                    @endif
                @elseif($package_Type->pakage_type == 'activity')
                    
                    @if($recieved_activity_Payments == 0)
                        <b class="cs-primary_color cs-f22">UNPAID</b> <br>
                    @elseif($recieved_activity_Payments > 0 && $recieved_activity_Payments < $total_package_Payments->price - $recieved_activity_Payments)
                        <b class="cs-primary_color cs-f22">PARTIALLY PAID</b> <br>
                    @else
                        <b class="cs-primary_color cs-f22">PAID</b> <br>
                    @endif
                @else
                @endif
          </div>
          <div class="cs-invoice_right cs-text_right">
            <b class="cs-primary_color">{{ $cart_res->tour_name }}</b>
            <p>
              {{$contact_details->company_name ?? ''}} <br>
              {{$contact_details->email ?? ''}} <br>
              {{$contact_details->contact_number ?? ''}}<br>
              {{$contact_details->address ?? ''}}<br>
            </p>
          </div>
        </div>
        <div class="cs-invoice_head cs-50_col cs-mb25">
          <div class="cs-invoice_left">
            <b class="cs-primary_color">Adults Travelers on this trip</b> <br>
            {{$inv_data->passenger_name}} <br>
            
            <?php
            $adults_details=$inv_data->adults_detail;
            // print_r($adults_details);die();
            
            if($adults_details != 'null')
            {
                $adults_details1=json_decode($adults_details);
              foreach($adults_details1 as $adults_details1)
              {
            
            ?>
            
             {{$adults_details1->passengerName}} <br>
            
            <?php
              }
            }
            ?>
            
            <?php
            $child_details=$inv_data->child_detail;
           
           
           
            if(isset($child_details) AND $child_details !== '""')
            { 
                $child_details1=json_decode($child_details);
                echo '<b class="cs-primary_color">Children Travelers on this trip</b> <br>';
              foreach($child_details1 as $child_details1)
              {
            
            ?>
            
             {{$child_details1->passengerName}} <br>
            
            <?php
              }
            }
            ?>
            
          </div>
          <div class="cs-invoice_right">
            <ul class="cs-bar_list">
                <?php 
                    if($package_Type->pakage_type == 'tour'){
                ?>
              <li><b class="cs-primary_color"><img style="border: 0;max-width: 3%;height: auto;vertical-align: middle;" src="{{asset('public/invoice/assets/img/plane-departure-solid.svg')}}"alt="Logo"> </b>
              <?php
              
              $dateValue = strtotime(date("d-m-Y", strtotime($itenry_res[0]->start_date ?? '' )));
     
                $year = date('Y',$dateValue);
                $monthName = date('F',$dateValue);
                $monthNo = date('d',$dateValue);
                $day = date('l', $dateValue);
                echo $day . ',' . $monthNo . ',' . $monthName . ',' . $year;
                // printf("%s, %d, %s ,%l\n", $monthName, $monthNo, $year ,$day);
              ?>
              
              
              </li>
              <li><b class="cs-primary_color"> <img style="border: 0;max-width: 3%;height: auto;vertical-align: middle;" src="{{asset('public/invoice/assets/img/plane-arrival-solid.svg')}}"alt="Logo"></b>
              
               <?php
              
              $dateValue = strtotime(date("d-m-Y", strtotime($itenry_res[0]->end_date ?? '' )));
                     
                $year = date('Y',$dateValue);
                $monthName = date('F',$dateValue);
                $monthNo = date('d',$dateValue);
                $day = date('l', $dateValue);
                echo $day . ',' . $monthNo . ',' . $monthName . ',' . $year;
                // printf("%s, %d, %s ,%l\n", $monthName, $monthNo, $year ,$day);
                ?>
                </li>
                <?php
                }else{
                    ?>
                    <li>Activtiy Date  : 
                    <?php
                    $dateValue = strtotime(date("d-m-Y", strtotime($cart_res->activity_select_date ?? '' )));
                     
                $year = date('Y',$dateValue);
                $monthName = date('F',$dateValue);
                $monthNo = date('d',$dateValue);
                $day_select = date('l', $dateValue);
                echo $day_select . ',' . $monthNo . ',' . $monthName . ',' . $year;
                
                $start_time = '';
                $end_time = '';
                $Availibilty = json_decode($tour_data->Availibilty);
                foreach($Availibilty as $id => $av_res){
                    if(isset($av_res->enable)){
                                $day = '';
                               
                                  if($id == '1'){
                                        $day = "Monday";
                                        if($day_select == $day){
                                            
                                            $start_time = $av_res->from;
                                            $end_time = $av_res->to;
                                        }
                                  }
                                  
                                   if($id == '2'){
                                        $day = "Tuesday";
                                        if($day_select == $day){
                                             
                                            $start_time = $av_res->from;
                                            $end_time = $av_res->to;
                                        }
                                  }
                                  
                                   if($id == '3'){
                                        $day = "Wednesday";
                                       if($day_select == $day){
                                           
                                            $start_time = $av_res->from;
                                            $end_time = $av_res->to;
                                        }
                                  }
                                  
                                   if($id == '4'){
                                        $day = "Thursday";
                                        if($day_select == $day){
                                            
                                            $start_time = $av_res->from;
                                            $end_time = $av_res->to;
                                        }
                                  }
                                  
                                   if($id == '5'){
                                        $day = "Friday";
                                        
                                        if($day_select == $day){
                                            $start_time = $av_res->from;
                                            $end_time = $av_res->to;
                                        }
                                  }
                                  
                                   if($id == '6'){
                                        $day = "Saturday";
                                        if($day_select == $day){
                                            $start_time = $av_res->from;
                                            $end_time = $av_res->to;
                                        }
                                  }
                                  
                                   if($id == '7'){
                                        $day = "Sunday";
                                        if($day_select == $day){
                                            $start_time = $av_res->from;
                                            $end_time = $av_res->to;
                                        }
                                  }
                    }
                }
                ?>
                    </li>
                    <li>Location: {{ $tour_data->location }}</li>
                    <li>Duration: {{ $tour_data->duration }} Hours</li>
                    <li>Start Time: {{ $start_time }} - End Time {{ $end_time }}</li>
                    <?php
                }
              ?>
                    
              
            </ul>
          </div>
        </div>
     
                           
                               @endforeach
                          @endif
                          
                          
                          
              <?php
              
              
              
              
              
              ?>
              
                    <?php 
                    if($package_Type->pakage_type !== 'tour'){
                ?>
                <hr>
                  <div class="">
                    <h4><b class="cs-primary_color">Meeting And Pickup</b></h4> <br>
             
                  </div>
                  <div>
                   {!! $tour_data->meeting_and_pickups !!}
                   
                   </div>
                  <!--<li>-->
                  <!--  <div class="cs-list_left" style="font-size: 13px;">-->
                       
                  <!--    <p class="cs-mb0">-->
                        
                  <!--    </p>-->
                  <!--  </div>-->
                    
                  <!--</li>-->
                 <?php 
                    }
                 ?>
              <div class="cs-invoice_head cs-50_col cs-mb25">
            
              <?php
              
              if(isset($tour_batchs->accomodation_details))
              {
                  ?>
                   <div class="cs-invoice_left">
                      <ul class="cs-list cs-style2">
                  <div class="cs-list_left">
              <b class="cs-primary_color">Accomodation Details</b> <br>
              
            </div>
            <?php
                  $accomodation_details=$tour_batchs->accomodation_details;
                $accomodation_details=json_decode($accomodation_details);
                  foreach($accomodation_details as $accomodation_details)
                  {
              ?>            
        
          <li>
            <div class="cs-list_left" style="font-size: 13px;">
               
              <b class="cs-primary_color">Hotel Name :{{$accomodation_details->acc_hotel_name}} </b> <br><br>
              <p class="cs-mb0">
                <img style="border: 0;max-width: 3%;height: auto;vertical-align: middle;" src="{{asset('public/invoice/assets/img/calendar-check-solid.svg')}}"alt="Logo">   Check In:{{$accomodation_details->acc_check_in}} 
                <img style="border: 0;max-width: 3%;height: auto;vertical-align: middle;" src="{{asset('public/invoice/assets/img/calendar-check-solid.svg')}}"alt="Logo">  Check Out: {{$accomodation_details->acc_check_out}}<br>
                Type: {{$accomodation_details->acc_type}}<br>
              </p>
            </div>
            
          </li>
       
                          
           <?php
                  }
                  ?>
            </ul> 
                  </div>
            <?php
              }
           ?>               
                          
            
                   <div class="cs-invoice_right">
                       
            
              
              
           
              
              
              
              
              <?php
              
              if(isset($tour_batchs->transportation_details))
              {?> 
                  <ul class="cs-list cs-style2">
                  <div class="cs-list_left">
              <b class="cs-primary_color">Transportation Details</b> <br>
              
            </div>
             <?php
                     $transportation_details=$tour_batchs->transportation_details;
              $transportation_details=json_decode($transportation_details);
                  
              ?>            
        
          <li>
            <div class="cs-list_left">
              <b class="cs-primary_color"> </b> <br>
              <p class="cs-mb0" style="line-height: 29px;">
                <img style="border: 0;max-width: 3%;height: auto;vertical-align: middle;" src="{{asset('public/invoice/assets/img/location-dot-solid.svg')}}"alt="Logo">  Pick Up :{{$transportation_details->transportation_pick_up_location}} <br>
                <img style="border: 0;max-width: 3%;height: auto;vertical-align: middle;" src="{{asset('public/invoice/assets/img/location-dot-solid.svg')}}"alt="Logo">  Drop Off: {{$transportation_details->transportation_drop_off_location}}<br>
                <img style="border: 0;max-width: 3%;height: auto;vertical-align: middle;" src="{{asset('public/invoice/assets/img/calendar-solid.svg')}}"alt="Logo">  Pick Up Date: {{$transportation_details->transportation_pick_up_date}}<br>
               
                Trip Type: {{$transportation_details->transportation_trip_type}}<br>
              </p>
            </div>
            
          </li>
       
                          
           
                </ul>
                  
             
              <?php
           
              }
           ?>               
                       </div>    
              </div>
            
              
              <?php
              
                if(isset($tour_batchs->flights_details))
                {
                  
              ?>            
        <ul class="cs-list cs-style2">
                  <div class="cs-list_left">
              <b class="cs-primary_color">Flights Details</b> <br>
              
            </div>
            @if(isset($flights_details->flight_type))
                @if($flights_details->flight_type == 'Direct')
                    <li>
                        <div class="cs-list_left">
                            <p class="s-mb0" style="line-height: 35px;"> 
                                <img style="border: 0;max-width: 3%;height: auto;vertical-align: middle;" src="{{asset('public/invoice/assets/img/plane-departure-solid.svg')}}"alt="Logo"> : {{$flights_details->departure_airport_code ?? ''}} <br>
                                Departure Flight Number:{{ $flights_details->departure_flight_number ?? '' }}<br>
                                Departure Date: {{$flights_details->departure_date ?? ''}}<br>
                            </p>
                        </div>
                        <div class="cs-list_right">
                            <p class="cs-mb0" style="line-height: 30px;">
                                <img style="border: 0;max-width: 3%;height: auto;vertical-align: middle;" src="{{asset('public/invoice/assets/img/plane-arrival-solid.svg')}}"alt="Logo">  : {{ $flights_details->arrival_airport_code ?? '' }}<br>
                                Arrival Flight Number:</span>{{ $flights_details->arrival_flight_number ?? ''  }}<br>
                                Arrival Date:</span>{{ $flights_details->arrival_date ?? '' }}<br>
                                Flight Type: {{$flights_details->flight_type ?? ''}}<br>
                            </p>
                        </div>
                    </li>
                @elseif($flights_details->flight_type == 'Indirect')
                    @foreach($flights_detailsI as $value)
                        <li>
                            <div class="cs-list_left">
                                <p class="s-mb0" style="line-height: 35px;"> 
                                    <img style="border: 0;max-width: 3%;height: auto;vertical-align: middle;" src="{{asset('public/invoice/assets/img/plane-departure-solid.svg')}}"alt="Logo"> : {{$value->more_departure_airport_code ?? ''}} <br>
                                    Departure Flight Number:{{ $value->more_departure_flight_number ?? '' }}<br>
                                    Departure Date: {{$value->more_departure_time ?? ''}}<br>
                                </p>
                            </div>
                            <div class="cs-list_right">
                                <p class="cs-mb0" style="line-height: 30px;">
                                    <img style="border: 0;max-width: 3%;height: auto;vertical-align: middle;" src="{{asset('public/invoice/assets/img/plane-arrival-solid.svg')}}"alt="Logo">  : {{ $value->return_more_departure_airport_code ?? '' }}<br>
                                    Arrival Flight Number:</span>{{ $value->return_more_departure_flight_number ?? ''  }}<br>
                                    Arrival Date:</span>{{ $value->return_more_arrival_time ?? '' }}<br>
                                    Flight Type: {{$value->return_more_departure_Flight_Type ?? ''}}<br>
                                </p>
                            </div>
                        </li>
                    @endforeach
                @else
                    <h4>No Flight Details</h4>
                @endif
            @else
            @endif
       </ul>
                          
           <?php
                  
           
              }
           ?>               
                          
             
                          
                    
        </ul>
        <div class="cs-table cs-style1">
          <div class="cs-round_border">
            <div class="cs-table_responsive">
              <table class="cs-border_less">
                <tbody>
                  <tr>
                    <td class="cs-primary_color cs-semi_bold cs-f18" colspan="3">Tour Details:
                    </td>
                    <td class="cs-primary_color cs-semi_bold cs-f18" style="float : right;">Cost Price Per Person:
                    </td>
                  </tr>
                  <tr>
                    <td>Adults : {{ $cart_res->adults }}</td>
                    <td class="cs-primary_color">Children : {{ $cart_res->childs }}</td>
                    <td class="cs-bold cs-primary_color cs-text_right"></td>
                    <?php 
                    if($package_Type->pakage_type == 'tour'){
                ?>
                    <td class="cs-bold cs-primary_color cs-text_right">Price Per Person : {{ $currency_Symbol }} {{ $cart_res->sigle_price }}</td>
                    <?php 
                    }else{
                        ?>
                    <td class="cs-bold cs-primary_color cs-text_right">Adult Price: {{ $currency_Symbol }} {{ $cart_res->adult_price }} - Child Price :{{ $currency_Symbol }} {{ $cart_res->child_price }}</td>
                        <?php
                    }
                ?>
                  </tr>
                 
                  
                </tbody>
              </table>
            </div>
          </div>
        </div>
         <?php
                     if($cart_res->Additional_services_names != '' && $cart_res->Additional_services_names != '[]'){
                        $additional_services = json_decode($cart_res->Additional_services_names);
                   ?>
        <div class="cs-table cs-style1 cs-type1 cs-focus_bg">
          <h3 class="cs-primary_color cs-bold cs-f18">Additional Services</h3>
          <div class="cs-table_responsive">
            <table>
                <thead>
                     <tr>
                      <td class="cs-text_center cs-semi_bold" >Name</td>
                      <td class="cs-text_center cs-semi_bold">Price</td>
                      <td class="cs-text_center cs-semi_bold">Persons</td>
                      <td class="cs-text_center cs-semi_bold">Days</td>
                      <td class="cs-text_center cs-semi_bold">Dates</td>
                      <td class="cs-text_center cs-semi_bold">Total</td>
                    </tr>
                </thead>
              <tbody>
                  <?php 
                    foreach($additional_services as $service_res){
                   ?>
                    <tr>
                      <td class="cs-text_center cs-semi_bold" >{{ $service_res->service ?? '' }}</td>
                      <td class="cs-text_center cs-semi_bold">{{ $cart_res->currency ?? '' ." ". $service_res->service_price ?? '' }}</td>
                      <td class="cs-text_center cs-semi_bold">{{ $service_res->person ?? '' }}</td>
                      <td class="cs-text_center cs-semi_bold">{{ $service_res->Service_Days ?? '' }}</td>
                      <td class="cs-text_center cs-semi_bold"><?php 
                                                    if($service_res->dates ?? '' != null){
                                                        echo $service_res->dates ?? '';
                                                    }else{
                                                        echo '';
                                                    }
                                              ?></td>
                      <td class="cs-text_center cs-semi_bold">{{ $cart_res->currency ?? '' }} {{ $service_res->total_price ?? '' }}</td>
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
        <div class="cs-table cs-style1 cs-mb20">
          <div class="cs-table_responsive">
            <table class="cs-border_less">
              <tbody>
                   @php 
                                    $total = 0;
                            
                        @endphp
                        @foreach($cart_data as $cart_res)
                            @php 
                               $total = $total + $cart_res->price
                            @endphp
                            
                        
                <tr>
                  <td></td>
                  <td class="cs-bold cs-primary_color cs-f18" style="float: right;;">Total Amount:</td>
                  <td class="cs-bold cs-primary_color cs-f18 cs-text_right">{{ $currency_Symbol }} {{ $total }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        
        <div class="cs-table cs-style1 cs-type1 cs-focus_bg">
          <h3 class="cs-primary_color cs-bold cs-f18">Payment Details</h3>
          <div class="cs-table_responsive">
            <table>
              <tbody>
                @if($package_Type->pakage_type == 'tour')
                    <tr>
                      <td class="cs-text_center cs-semi_bold" >Package</td>
                      <td class="cs-text_center cs-semi_bold">Paid On</td>
                      <td class="cs-text_center cs-semi_bold">Total Amount</td>
                      <td class="cs-text_center cs-semi_bold">Total Recieved</td>
                      <td class="cs-text_center cs-semi_bold">Remaining</td>
                    </tr>
                    @if(isset($package_Payments))
                        @foreach($package_Payments as $value)
                            <tr>
                                <td class="cs-primary_color">{{ $cart_res->tour_name }}</td>
                                <td class="cs-primary_color cs-text_center">
                                    <?php
                                    
                                        $dateValue = strtotime($value->created_at);
                                        $year = date('Y',$dateValue);
                                        $monthName = date('F',$dateValue);
                                        $monthNo = date('d',$dateValue);
                                        $day = date('l',$dateValue);
                                        printf("%s, %d, %s\n", $monthName, $monthNo, $year);
                                    ?>
                                </td>
                                <td class="cs-primary_color cs-text_center">{{ $currency_Symbol }} {{ $value->total_amount }}</td>
                                <td class="cs-primary_color cs-text_center">{{ $currency_Symbol }} {{ $value->recieved_amount }}</td>
                                <td class="cs-primary_color cs-text_center">{{ $currency_Symbol }} {{ $value->remaining_amount }}</td>
                            </tr>
                        @endforeach
                    @endif
                @elseif($package_Type->pakage_type == 'activity')
                    <tr>
                      <td class="cs-text_center cs-semi_bold" >Activity</td>
                      <td class="cs-text_center cs-semi_bold">Paid On</td>
                      <td class="cs-text_right cs-semi_bold">Total Amount</td>
                      <td class="cs-text_center cs-semi_bold">Total Recieved</td>
                      <td class="cs-text_center cs-semi_bold">Remaining</td>
                    </tr>
                    @if(isset($activity_Payments))
                        @foreach($activity_Payments as $value)
                            <tr>
                                <td class="cs-primary_color cs-text_center">{{ $cart_res->tour_name }}</td>
                                <td class="cs-primary_color cs-text_center">
                                    <?php
                                        $dateValue = strtotime($value->created_at);
                                        $year = date('Y',$dateValue);
                                        $monthName = date('F',$dateValue);
                                        $monthNo = date('d',$dateValue);
                                        printf("%s, %d, %s\n", $monthName, $monthNo, $year);
                                    ?>
                                </td>
                                <td class="cs-primary_color cs-text_center">{{ $currency_Symbol }} {{ $value->total_amount }}</td>
                                <td class="cs-primary_color cs-text_center">{{ $currency_Symbol }} {{ $value->recieved_amount }}</td>
                                <td class="cs-primary_color cs-text_center">{{ $currency_Symbol }} {{ $value->remaining_amount }}</td>
                            </tr>
                        @endforeach
                    @endif
                @endif
              </tbody>
            </table>
          </div>
        </div>
        
        <div class="cs-table cs-style1 cs-type1">
          <div class="cs-table_responsive">
            <table>
              <tbody>
                <tr class="cs-table_baseline">
                  <!--<td>-->
                  <!--  <b class="cs-primary_color">Cost Per Person</b> <br>-->
                  <!--  Per Person 66.66 per night included <br>fee & take.-->
                  <!--</td>-->
                    @if($package_Type->pakage_type == 'tour')
                        <td>
                            <p class="cs-mb5 cs-primary_color cs-semi_bold" style="margin-left: 80%;">Total Paid:</p>
                            <p class="cs-m0 cs-primary_color cs-semi_bold" style="margin-left: 70%;">Total Balance Due:</p>
                        </td>
                        <td>
                            <p class="cs-mb5 cs-text_left cs-primary_color cs-semi_bold">{{ $currency_Symbol }} {{ $recieved_package_Payments }}</p>
                            <p class="cs-m0 cs-text_left cs-primary_color cs-semi_bold">{{ $currency_Symbol }} {{ $total_package_Payments->price ?? '0' - $recieved_package_Payments }}</p>
                        </td>
                    @elseif($package_Type->pakage_type == 'activity')
                        <td>
                            <p class="cs-mb5 cs-primary_color cs-semi_bold" style="margin-left: 80%;">Total Paid:</p>
                            <p class="cs-m0 cs-primary_color cs-semi_bold" style="margin-left: 70%;">Total Balance Due:</p>
                        </td>
                        <td>
                            <p class="cs-mb5 cs-text_left cs-primary_color cs-semi_bold">{{ $currency_Symbol }} {{ $recieved_activity_Payments }}</p>
                            <p class="cs-m0 cs-text_left cs-primary_color cs-semi_bold">{{ $currency_Symbol }} {{ $total_package_Payments->price ?? '0' - $recieved_activity_Payments }}</p>
                        </td>
                    @endif
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="cs-invoice_btns cs-hide_print">
        <a href="javascript:window.print()" class="cs-invoice_btn cs-color1">
          <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><path d="M384 368h24a40.12 40.12 0 0040-40V168a40.12 40.12 0 00-40-40H104a40.12 40.12 0 00-40 40v160a40.12 40.12 0 0040 40h24" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/><rect x="128" y="240" width="256" height="208" rx="24.32" ry="24.32" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/><path d="M384 128v-24a40.12 40.12 0 00-40-40H168a40.12 40.12 0 00-40 40v24" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/><circle cx="392" cy="184" r="24"/></svg>
          <span>Print</span>
        </a>
        <button id="download_btn" class="cs-invoice_btn cs-color2">
          <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><title>Download</title><path d="M336 176h40a40 40 0 0140 40v208a40 40 0 01-40 40H136a40 40 0 01-40-40V216a40 40 0 0140-40h40" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M176 272l80 80 80-80M256 48v288"/></svg>
          <span>Download</span>
        </button>
      </div>
    </div>
  </div>
   @endforeach
                    @endif
  <script src="{{asset('public/invoice/assets/js/jquery.min.js')}}"></script>
  <script src="{{asset('public/invoice/assets/js/jspdf.min.js')}}"></script>
  <script src="{{asset('public/invoice/assets/js/html2canvas.min.js')}}"></script>
  <script src="{{asset('public/invoice/assets/js/main.js')}}"></script>
</body>

<!-- Mirrored from thememarch.com/demo/html/ivonne/hotel-booking-invoice.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 06 Jul 2022 16:27:26 GMT -->
</html>



<?php

die();
?>







<!DOCTYPE html>
<html lang="en">
   <head>
      
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
      border-bottom: 2px solid #ae8c32;
      color: #ae8c32;
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
      .vochure-header {
        background-image:url("{{asset('public/admin_package/frontend/images/bg-vochure.jpg')}}");
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
    
      <!-- ================================
         END MODAL AREA
         ================================= -->
      <script>
         localStorage.setItem('total_amount', 10933.17);
      </script>
      <!--End PHP-->
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
      <!-- Button trigger modal -->
<!-- Button trigger modal -->


      <div class="vochure-content">
         <div class="container">
             <h6>Inovice No:{{ $id }}</h6>
            <div class="button-group-voucher" style="display: flex; justify-content: flex-end;">
               <div class="text-right mt-3 mr-2">
                  <button type="submit" class="btn btn-secondary" onclick="window.print()">Print  Voucher </button>
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
                     
                     
                     <div class="clearfix v-section-info">
                        <ul class="list-items list-items-3 list-items-4  clearfix" >
                           <li>
                              <span class="text-black font-weight-bold">Tour Name:</span>
                              <p class="f-20 text-black font-weight-bold" id="makkah_booking_status">{{ $cart_res->tour_name }}</p>
                           </li>
                          
                           <li><span class="text-black font-weight-bold">Adults:</span>{{ $cart_res->adults }} </li>
                           <li><span class="text-black font-weight-bold">Children:</span>{{ $cart_res->childs }}</li>
                           <li><span class="text-black font-weight-bold">Price per person :</span>{{ $currency_Symbol }}{{ $cart_res->sigle_price }}</li>
                            @if(isset($iternery_array))
                                @foreach($iternery_array as $itenry_res)
                                    @if($itenry_res[0]->id == $cart_res->tour_id)
                                                           
                           
                           <li><span class="text-black font-weight-bold">Duration:</span></li>
                           
                                    @endif
                           
                               @endforeach
                          @endif
                         
                        </ul>
                     </div>
                     
                     <div class="v-heading-icon clearfix mt-3">
                        <div class="float-left">
                           <img src="https://umrahtech.com/public/assets/frontend/images/Icons/booking-details.png">
                        </div>
                        <div class="v-heading-icon-title float-left">
                            <h3>Day By Day itenery</h3>
                      </div>
                    </div>  
                    
                     @if(isset($iternery_array))
                                @foreach($iternery_array as $itenry_res)
                                
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
                               @endforeach
                         @endif
            
                
                 
                  </section>
                        @endforeach
                    @endif
                  
                  <!--apply for umrah visa-->
               </div>
               <!--Sidrbar start-->
               
           
               <div class="col-md-4 col-sm-12 sidbar">
                  <div class="v-heading-icon clearfix mt-3" >
                     <div class="float-left">
                        <img src="{{asset('public/admin_package/frontend/images/lead.png') }}">
                     </div>
                     <div class="v-heading-icon-title float-left" style="">
                        <h3>Lead Passenger Details</h3>
                     </div>
                  </div>
                  <div class="clearfix v-section-info" style="border: 1px solid #ddd;">
                     <ul class="list-items list-items-3 list-items-4  clearfix" >
                        <li>
                           <span class="text-black font-weight-bold">Passport No:</span> 
                           <p class="f-20 text-black font-weight-bold">{{ $passenger_Det[0]->passport_no }}</p>
                        </li>
                        <li><span class="text-black font-weight-bold">Nationality:</span>{{ $passenger_Det[0]->Nationality }}</li>
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
                  
                    <div class="v-heading-icon clearfix mt-3" >
                     <div class="float-left">
                        <img src="{{asset('public/admin_package/frontend/images/lead.png') }}">
                     </div>
                     <div class="v-heading-icon-title float-left" style="">
                       <h3>Payment Details</h3>
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
                        <img src="{{asset('public/admin_package/frontend/images/lead.png') }}">
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
                    <label>Transcation ID</label>
                  <input type="text" class="form-control" required name="transcation_id" placeholder="Enter Transcation ID">
                  <input type="text" class="form-control" required name="invoice_id" hidden value="{{ $id }}" placeholder="Enter Transcation ID">
                </div>
                <div class="col-6">
                     <label>Payment Amunt</label>
                  <input type="text" class="form-control" required name="payment_am" placeholder="Enter Payment Amunt">
                </div>
           
               <div class="col-6">
                   <label>Account No.</label>
                  <input type="text" class="form-control" required name="account_no" placeholder="Payment to (Account No.)">
                </div>
                <div class="col-6">
                   <label>E mail</label>
                  <input type="email" class="form-control" required name="email" value="{{ $passenger_Det[0]->email }}" placeholder="Enter Email">
                </div>
                <div class="col-12">
                    <label>Payment Voucher</label>
                  <input type="file" class="form-control " required name="voucher" placeholder="Upload Payment Voucher">
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
      
   
     
  
     
    
     
      
     
     
      
   </body>
</html>