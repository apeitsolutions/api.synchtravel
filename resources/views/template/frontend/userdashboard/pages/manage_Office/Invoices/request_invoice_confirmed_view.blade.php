<?php
    // print_r($data);die();
?>
<?php $currency=Session::get('currency_symbol'); ?>

<html class="no-js" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="ThemeMarch">
        <title>Alhijaz  - Travel Booking System</title>
        <link rel="stylesheet" href="https://client1.synchronousdigital.com/public/invoice/assets/css/style.css">
    </head>

    <style>
        .cs-invoice.cs-style1 .cs-invoice_head.cs-type1 {
            background: #000;
            padding: 25px;
            }
            .cs-white_color{
             color: #fff; 
            }
            body, html {
            color: #202020;
            font-family: 'Inter', sans-serif;
            font-size: 16px;
            }
            .cs-foot{
            font-size: 16px;
            text-align: center;
            margin-top: 35px;
            background: #000;
            color: #fff;
            padding: 9px;
        }
    </style>

    <body>
        <div class="cs-container">
            <div class="cs-invoice cs-style1">
                <div class="cs-invoice_in" id="download_section">
                    
                    <div class="cs-invoice_head cs-type1 cs-mb25">
                        <div class="cs-invoice_left">
                            <p class="cs-invoice_number cs-white_color cs-mb0 cs-f16">
                                <b class="cs-invoice_number cs-white_color cs-mb0 cs-f16">Invoice No:</b> {{  $data->generate_id ?? '' }} 
                            </p>
                        </div>
                        <div class="cs-invoice_right cs-text_right">
                            <img src="{{ asset('/public/admin_package/frontend/images/logo.png') }}" alt="Logo">
                        </div>
                    </div>
                    
                    <div class="cs-invoice_head cs-mb10">
                        <div class="cs-invoice_left">
                            <p>
                                <b class="cs-primary_color">Confirmed: {{ $data->time_duration ?? '' }} Days</b> <br>
                                @if(isset($customer_data))
                                    <b class="cs-primary_color">Created By {{ $data->tour_author }}</b> <br>
                                @endif
                                 <?php
                                    $dateValue = strtotime($data->created_at);
                                    $year = date('Y',$dateValue);
                                    $monthName = date('F',$dateValue);
                                    $day = date('d',$dateValue);
                                    printf("%s, %d, %s\n", $monthName, $day, $year);
                                ?>
                            </p>
                            <p>Reservation No :{{$data->reservation_number ?? ''}}</p>
                             <p>Hotel Reservation No :{{$data->hotel_reservation_number ?? ''}}</p>
                        </div>
                        <div class="cs-invoice_right cs-text_right">
                            <b class="cs-primary_color">{{ $data->title }}</b>
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
                            @if(isset($data->agent_Name) && $data->agent_Name != null && $data->agent_Name != '')
                                Agent Name: <b class="cs-cs-primary_color"> {{ $data->agent_Name ?? '' }}</b> <br>
                            @else
                                Passenger Name : <b class="cs-cs-primary_color"> {{$data->f_name}} {{ $data->middle_name }} </b><br>
                            @endif
                            <b class="cs-primary_color">Adults Travelers on this trip</b> <br>
                        </div>
                        <div class="cs-invoice_right">
                            <ul class="cs-bar_list">
                                <li>
                                    <b class="cs-primary_color"><img style="border: 0;max-width: 3%;height: auto;vertical-align: middle;" src="https://client1.synchronousdigital.com/public/invoice/assets/img/plane-departure-solid.svg" alt="Logo"> </b>
                                    <?php
                                      
                                      $dateValue = strtotime(date("d-m-Y", strtotime($data->start_date)));
                             
                                        $year = date('Y',$dateValue);
                                        $monthName = date('F',$dateValue);
                                        $monthNo = date('d',$dateValue);
                                        $day = date('l', $dateValue);
                                        echo $day . ',' . $monthNo . ',' . $monthName . ',' . $year;
                                        // printf("%s, %d, %s ,%l\n", $monthName, $monthNo, $year ,$day);
                                    ?>
                                </li>
                                <li><b class="cs-primary_color"> <img style="border: 0;max-width: 3%;height: auto;vertical-align: middle;" src="https://client1.synchronousdigital.com/public/invoice/assets/img/plane-arrival-solid.svg"alt="Logo"></b>
                                    <?php
                                  
                                        $dateValue = strtotime(date("d-m-Y", strtotime($data->end_date)));
                                 
                                        $year = date('Y',$dateValue);
                                        $monthName = date('F',$dateValue);
                                        $monthNo = date('d',$dateValue);
                                        $day = date('l', $dateValue);
                                        echo $day . ',' . $monthNo . ',' . $monthName . ',' . $year;
                                        // printf("%s, %d, %s ,%l\n", $monthName, $monthNo, $year ,$day);
                                    ?>
                                 
                                  </li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="cs-invoice_head cs-50_col cs-mb25">
                    
                        @if(isset($accomodation_details) && $accomodation_details != null && $accomodation_details != '')
                        <?php // dd($accomodation_details); ?>
                            <div class="cs-invoice_left">
                                <ul class="cs-list cs-style2">
                                    <div class="cs-list_left"><b class="cs-primary_color">Accomodation Details</b> <br></div>
                                    @foreach($accomodation_details as $accomodation_details)
                                        <li>
                                            <div class="cs-list_left" style="font-size: 13px;">
                                   
                                                <b class="cs-primary_color">Hotel Name :{{$accomodation_details->acc_hotel_name}} </b> <br><br>
                                                <p class="cs-mb0">
                                                    <img style="border: 0;max-width: 3%;height: auto;vertical-align: middle;" src="https://client1.synchronousdigital.com/public/invoice/assets/img/calendar-check-solid.svg"alt="Logo">   Check In:{{$accomodation_details->acc_check_in}} 
                                                    <img style="border: 0;max-width: 3%;height: auto;vertical-align: middle;" src="https://client1.synchronousdigital.com/public/invoice/assets/img/calendar-check-solid.svg"alt="Logo">  Check Out: {{$accomodation_details->acc_check_out}}<br>
                                                    Type: {{ $accomodation_details->acc_type }}<br>
                                                    @if(isset($accomodation_details->hotel_meal_type) && $accomodation_details->hotel_meal_type != null && $accomodation_details->hotel_meal_type != '')
                                                        Meal Type: {{ $accomodation_details->hotel_meal_type ?? '' }}
                                                    @else
                                                    @endif
                                                </p>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif 
                        
                        
                        @if(isset($transportation_details))
                            <div class="cs-invoice_right">
                                @if(isset($transportation_details->transportation_pick_up_location) && $transportation_details->transportation_pick_up_location != null && $transportation_details->transportation_pick_up_location != '')        
                                    <ul class="cs-list cs-style2">
                                        <div class="cs-list_left">
                                            <b class="cs-primary_color">Transportation Details</b> <br>
                                        </div>
                                    
                                        <li>
                                            <div class="cs-list_left">
                                                <b class="cs-primary_color"> </b> <br>
                                                <p class="cs-mb0">
                                                    <img style="border: 0;max-width: 3%;height: auto;vertical-align: middle;" src="https://client1.synchronousdigital.com/public/invoice/assets/img/location-dot-solid.svg"alt="Logo">  Pick Up :{{$transportation_details->transportation_pick_up_location}} <br>
                                                    <img style="border: 0;max-width: 3%;height: auto;vertical-align: middle;" src="https://client1.synchronousdigital.com/public/invoice/assets/img/location-dot-solid.svg"alt="Logo">  Drop Off: {{$transportation_details->transportation_drop_off_location}}<br>
                                                    <img style="border: 0;max-width: 3%;height: auto;vertical-align: middle;" src="https://client1.synchronousdigital.com/public/invoice/assets/img/calendar-solid.svg"alt="Logo">  Pick Up Date: {{$transportation_details->transportation_pick_up_date}}<br>
                                                    Trip Type: {{$transportation_details->transportation_trip_type}}<br>
                                                </p>
                                            </div>
                                        </li>
                                    </ul>
                                @endif
                            </div>
                        @endif
                    
                    </div>
                    
                    <?php  
                        if(isset($flights_details))
                        {
                    ?>            
                            @if(isset($flights_details->flight_type))
                                <?php // dd($flights_details); ?>
                                @if($flights_details->flight_type == 'Direct')
                                    <ul class="cs-list cs-style2">
                                        <div class="cs-list_left"><b class="cs-primary_color">Flights Details</b> <br></div>
                                        <li>
                                            <div class="cs-list_left">
                                                <p class="s-mb0" style="line-height: 35px;"> 
                                                    <img style="border: 0;max-width: 3%;height: auto;vertical-align: middle;" src="{{asset('public/invoice/assets/img/plane-departure-solid.svg')}}"alt="Logo"> : {{$flights_details->departure_airport_code ?? ''}} <br>
                                                    Departure Flight Number:{{ $flights_details->departure_flight_number ?? '' }}<br>
                                                    Departure Date:
                                                    <?php
                                                        $dateValue = strtotime(date("d-m-Y", strtotime($flights_details->departure_time ?? '' )));             
                                                        $year = date('Y',$dateValue);
                                                        $monthName = date('F',$dateValue);
                                                        $monthNo = date('d',$dateValue);
                                                        $day = date('l', $dateValue);
                                                        echo $day . ',' . $monthNo . ',' . $monthName . ',' . $year;
                                                    ?>
                                                </p>
                                            </div>
                                            <div class="cs-list_right">
                                                <p class="cs-mb0" style="line-height: 30px;">
                                                    <img style="border: 0;max-width: 3%;height: auto;vertical-align: middle;" src="{{asset('public/invoice/assets/img/plane-arrival-solid.svg')}}"alt="Logo">  : {{ $flights_details->arrival_airport_code ?? '' }}<br>
                                                    Arrival Flight Number:</span>{{ $flights_details->return_departure_flight_number ?? ''  }}<br>
                                                    Arrival Date:</span>
                                                                <?php
                                                                    $dateValue = strtotime(date("d-m-Y", strtotime($flights_details->arrival_time ?? '' )));
                                                                    $year = date('Y',$dateValue);
                                                                    $monthName = date('F',$dateValue);
                                                                    $monthNo = date('d',$dateValue);
                                                                    $day = date('l', $dateValue);
                                                                    echo $day . ',' . $monthNo . ',' . $monthName . ',' . $year;
                                                                ?>
                                                                
                                                    <br>
                                                    Flight Type: {{$flights_details->flight_type ?? ''}}<br>
                                                </p>
                                            </div>
                                        </li>
                                    </ul>  
                                @elseif($flights_details->flight_type == 'Indirect')
                                    <?php // dd($flights_details); ?>
                                    @foreach($flights_details_more as $value)
                                        <ul class="cs-list cs-style2">
                                            <div class="cs-list_left"><b class="cs-primary_color">More Flights Details</b> <br></div>
                                            <li>
                                                <div class="cs-list_left">
                                                    <p class="s-mb0" style="line-height: 35px;"> 
                                                        <img style="border: 0;max-width: 3%;height: auto;vertical-align: middle;" src="{{asset('public/invoice/assets/img/plane-departure-solid.svg')}}"alt="Logo"> : {{$value->more_departure_airport_code ?? ''}} <br>
                                                        Departure Flight Number:{{ $value->more_departure_flight_number ?? '' }}<br>
                                                        Departure Date:
                                                         <?php
                                                    $dateValue = strtotime(date("d-m-Y", strtotime($value->more_departure_time ?? '' )));
                                                     
                                                                $year = date('Y',$dateValue);
                                                                $monthName = date('F',$dateValue);
                                                                $monthNo = date('d',$dateValue);
                                                                $day = date('l', $dateValue);
                                                                echo $day . ',' . $monthNo . ',' . $monthName . ',' . $year;
                                                                ?>
                                                        <br>
                                                    </p>
                                                </div>
                                                <div class="cs-list_right">
                                                    <p class="cs-mb0" style="line-height: 30px;">
                                                        <img style="border: 0;max-width: 3%;height: auto;vertical-align: middle;" src="{{asset('public/invoice/assets/img/plane-arrival-solid.svg')}}"alt="Logo">  : {{ $value->more_arrival_airport_code ?? '' }}<br>
                                                        Arrival Flight Number:</span>{{ $value->return_more_departure_flight_number ?? ''  }}<br>
                                                        Arrival Date:</span>
                                                        <?php
                                                    $dateValue = strtotime(date("d-m-Y", strtotime($value->more_arrival_time ?? '' )));
                                                     
                                                                $year = date('Y',$dateValue);
                                                                $monthName = date('F',$dateValue);
                                                                $monthNo = date('d',$dateValue);
                                                                $day = date('l', $dateValue);
                                                                echo $day . ',' . $monthNo . ',' . $monthName . ',' . $year;
                                                                ?>
                                                        <br>
                                                        Flight Type: {{$value->return_more_departure_Flight_Type ?? ''}}<br>
                                                    </p>
                                                </div>
                                            </li>
                                        </ul>
                                    @endforeach
                                @else
                                @endif
                            @else
                            @endif
                    <?php
                        }
                    ?>
                
                    <div class="cs-table cs-style1">
                      <div class="cs-round_border">
                        <div class="cs-table_responsive">
                          <table class="cs-border_less">
                            <tbody>
                              <tr>
                                <td class="cs-primary_color cs-semi_bold cs-f18" colspan="3">Quotation Details:</td>
                              </tr>
                              <tr>
                                <td>Adults : {{ $data->no_of_pax_days }}</td>
                                <td class="cs-primary_color">Children : {{ $data->childs }}</td>
                                <td class="cs-bold cs-primary_color cs-text_right">
                                    
                                    <?php 
                                        $grandTotalPrice = 0;
                                    ?>
                                    @if($data->double_grand_total_amount != 0 AND $data->double_grand_total_amount != null) 
                                            <p>Double Price : {{ $data->currency_symbol }} {{ $data->double_grand_total_amount ?? '' }}</p>Per Person
                                             <?php 
                                                $grandTotalPrice += $data->double_grand_total_amount * $data->no_of_pax_days;
                                            ?>
                                    @endif
                                     <?php
                                     if($data->triple_grand_total_amount!= 0 AND $data->triple_grand_total_amount!= null){ 
                                     ?>
                                         <p>Triple Price : {{ $data->currency_symbol }} {{ $data->triple_grand_total_amount ?? '' }}</p>Per Person
                                         <?php 
                                                $grandTotalPrice += $data->triple_grand_total_amount * $data->no_of_pax_days;
                                            ?>
                                    <?php
                                    }
                                    ?>
                                    
                                     @if($data->quad_grand_total_amount != 0 AND $data->quad_grand_total_amount != null) 
                                        <p>Quad Price : {{ $data->currency_symbol }} {{ $data->quad_grand_total_amount ?? '' }}</p>Per Person
                                        <?php 
                                                $grandTotalPrice += $data->quad_grand_total_amount * $data->no_of_pax_days;
                                            ?>
                                    @endif
                                   
                                    <?php
                                                                $markup_details=json_decode($data->markup_details);
                                                                foreach($markup_details as $markup_details)
                                                                {
                                                                    if($markup_details->markup_Type_Costing == 'flight_Type_Costing')
                                                                    {
                                                                        $flight_Type_Costing=$markup_details->markup_price;
                                                                    }
                                                                    if($markup_details->markup_Type_Costing == 'transportation_Type_Costing')
                                                                    {
                                                                        $transportation_Type_Costing=$markup_details->markup_price;
                                                                    }
                                                                    if($markup_details->markup_Type_Costing == 'visa_Type_Costing')
                                                                    {
                                                                        $visa_Type_Costing=$markup_details->markup_price;
                                                                    }
                                                                }
                                                                
                                                                ?>
                                                                 @if($visa_Type_Costing != 0 AND $visa_Type_Costing != null) 
                                        <p>Visa Price : {{ $data->currency_symbol }} {{ $visa_Type_Costing ?? '' }}</p>Per Person
                                        <?php 
                                                $grandTotalPrice += $visa_Type_Costing * $data->no_of_pax_days;
                                            ?>
                                    @endif
                                                                 @if($flight_Type_Costing != 0 AND $flight_Type_Costing != null) 
                                        <p>Flight Price : {{ $data->currency_symbol }} {{ $flight_Type_Costing ?? '' }}</p>Per Person
                                        <?php 
                                                $grandTotalPrice += $flight_Type_Costing * $data->no_of_pax_days;
                                            ?>
                                            @endif
                                            @if($transportation_Type_Costing != 0 AND $transportation_Type_Costing != null) 
                                        <p>Flight Price : {{ $data->currency_symbol }} {{ $transportation_Type_Costing ?? '' }}</p>Per Person
                                        <?php 
                                                $grandTotalPrice += $transportation_Type_Costing * $data->no_of_pax_days;
                                            ?>
                                            @endif
                                </td>
                              </tr>
                             
                              
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                    <div class="cs-table cs-style1 cs-mb20">
                      <div class="cs-table_responsive">
                        <table class="cs-border_less">
                          <tbody>
                            <tr>
                              <!--<td>Amet minim mollit non deserunt ullamco est sit</td>-->
                              <td class="cs-bold cs-primary_color cs-f18">Total Amount:</td>
                              
                              <td class="cs-bold cs-primary_color cs-f18 cs-text_right">{{ $data->currency_symbol ?? "" }} {{ $grandTotalPrice  }}</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="cs-table cs-style1 cs-type1 cs-focus_bg">
                        <h3 class="cs-primary_color cs-bold cs-f18">Payment Details</h3>
                      <div class="cs-table_responsive">
                        <table>
                          <tbody style="text-align: center;">
                            <tr>
                              <td class="cs-text_center cs-semi_bold">Invoice No</td>
                              <td class="cs-text_center cs-semi_bold">Paid On</td>
                              <td class="cs-text_center cs-semi_bold">Total Amount</td>
                              <td class="cs-text_center cs-semi_bold">Total Recieved</td>
                              <td class="cs-text_center cs-semi_bold">Remaining</td>
                            </tr>
                            @if(isset($invoice_P_details) && $invoice_P_details != null && $invoice_P_details != '')
                                <?php $t_C = $grandTotalPrice ?? '0'; ?>
                                @foreach($invoice_P_details as $valueI)
                                    <tr>
                                        <td>{{ $valueI->invoice_Id ?? ''}}</td>
                                        <td>
                                            <?php
                                                $dateValue = strtotime($valueI->date);
                                                $year = date('Y',$dateValue);
                                                $monthName = date('F',$dateValue);
                                                $monthNo = date('d',$dateValue);
                                                $day = date('l',$dateValue);
                                                printf("%s, %d, %s\n", $monthName, $monthNo, $year);
                                                $t_C = $t_C - $valueI->recieved_Amount;
                                            ?>
                                        </td>
                                        <td>{{ $currency }} {{ $valueI->total_Amount ?? '0'}}</td>
                                        <td>{{ $currency }} {{ $valueI->recieved_Amount ?? '0'}}</td>
                                        <td>{{ $currency }} 
                                            @if($valueI->remaining_Amount < -1)
                                                0
                                            @else
                                                {{ $t_C }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <!--<div class="cs-table cs-style1 cs-type1">-->
                    <!--  <div class="cs-table_responsive">-->
                    <!--    <table>-->
                    <!--      <tbody>-->
                    <!--        <tr class="cs-table_baseline">-->
                    <!--          <td>-->
                    <!--            <b class="cs-primary_color">Cost Per Person</b> <br>Per Person-->
                               
                    <!--          </td>-->
                    <!--          <td>-->
                    <!--            <p class="cs-mb5 cs-primary_color cs-semi_bold">Paid:</p>-->
                    <!--            <p class="cs-m0 cs-primary_color cs-semi_bold">Balance Due:</p>-->
                    <!--          </td>-->
                    <!--          <td>-->
                    <!--            <p class="cs-mb5 cs-text_right cs-primary_color cs-semi_bold">$0</p>-->
                    <!--            <p class="cs-m0 cs-text_right cs-primary_color cs-semi_bold">{{ $data->currency_symbol ?? "" }} {{ $grandTotalPrice }}</p>-->
                    <!--          </td>-->
                    <!--        </tr>-->
                    <!--      </tbody>-->
                    <!--    </table>-->
                    <!--  </div>-->
                    <!--</div>-->
                    
                    <div class="cs-table cs-style1 cs-type1">
                        <div class="cs-table_responsive">
                            <table>
                                <tbody>
                                    <tr class="cs-table_baseline">
                                        @if(isset($total_invoice_Payments))
                                            <td>
                                                <p class="cs-mb5 cs-primary_color cs-semi_bold" style="margin-left: 60%;">Total Paid:</p>
                                                <p class="cs-m0 cs-primary_color cs-semi_bold" style="margin-left: 60%;">Total Balance Due:</p>
                                                <p class="cs-m0 cs-primary_color cs-semi_bold" style="margin-left: 60%;">Over Paid:</p>
                                            </td>
                                            <td>
                                                <p class="cs-mb5 cs-text_left cs-primary_color cs-semi_bold">{{ $currency }} {{ $recieved_invoice_Payments }}</p>
                                                @if(isset($total_invoice_Payments) && $total_invoice_Payments != null && $total_invoice_Payments != '' || $total_invoice_Payments != 0)
                                                    <?php $ttt = $total_invoice_Payments->total_Amount - $recieved_invoice_Payments ?>
                                                    @if(isset($ttt) && $ttt < -1)
                                                        <?php $ttt =  $recieved_invoice_Payments - $total_invoice_Payments->total_amount ?>
                                                        <p class="cs-m0 cs-text_left cs-primary_color cs-semi_bold">{{ $currency }} {{ 0 }}</p>
                                                        <p class="cs-mb5 cs-text_left cs-primary_color cs-semi_bold">{{ $currency }} {{ $ttt }}</p>
                                                    @else
                                                        <p class="cs-m0 cs-text_left cs-primary_color cs-semi_bold">{{ $currency }} {{ $ttt }}</p>
                                                        <p class="cs-mb5 cs-text_left cs-primary_color cs-semi_bold">{{ $currency }} {{ 0 }}</p>
                                                    @endif
                                                @else
                                                    <p class="cs-m0 cs-text_left cs-primary_color cs-semi_bold">{{ $currency }} {{ $recieved_invoice_Payments }}</p>
                                                    <p class="cs-mb5 cs-text_left cs-primary_color cs-semi_bold">{{ $currency }} {{ 0 }}</p>
                                                @endif
                                            </td>
                                        @else
                                            <td>
                                                <p class="cs-mb5 cs-primary_color cs-semi_bold" style="margin-left: 60%;">Total Paid:</p>
                                                <p class="cs-m0 cs-primary_color cs-semi_bold" style="margin-left: 60%;">Total Balance Due:</p>
                                                <p class="cs-m0 cs-primary_color cs-semi_bold" style="margin-left: 60%;">Over Paid:</p>
                                            </td>
                                            <td>
                                                <p class="cs-mb5 cs-text_left cs-primary_color cs-semi_bold">£ 0</p>
                                                <p class="cs-m0 cs-text_left cs-primary_color cs-semi_bold">£ 0</p>
                                                <p class="cs-mb5 cs-text_left cs-primary_color cs-semi_bold">£ 0</p>
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
                    <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><path d="M384 368h24a40.12 40.12 0 0040-40V168a40.12 40.12 0 00-40-40H104a40.12 40.12 0 00-40 40v160a40.12 40.12 0 0040 40h24" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"></path><rect x="128" y="240" width="256" height="208" rx="24.32" ry="24.32" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"></rect><path d="M384 128v-24a40.12 40.12 0 00-40-40H168a40.12 40.12 0 00-40 40v24" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"></path><circle cx="392" cy="184" r="24"></circle></svg>
                    <span>Print</span>
                </a>
                <button id="download_btn" class="cs-invoice_btn cs-color2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                        <title>Download</title>
                        <path d="M336 176h40a40 40 0 0140 40v208a40 40 0 01-40 40H136a40 40 0 01-40-40V216a40 40 0 0140-40h40"fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"></path>
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M176 272l80 80 80-80M256 48v288"></path>
                    </svg>
                    <span>Download</span>
                </button>
                
                <a class="cs-invoice_btn cs-color1" href="{{URL::to('invoiceI')}}/{{$data->generate_id}}">
                    View Voucher
                </a>
                
            </div>
        </div>
    </div>
    
    <script src="https://client1.synchronousdigital.com/public/invoice/assets/js/jquery.min.js"></script>
    <script src="https://client1.synchronousdigital.com/public/invoice/assets/js/jspdf.min.js"></script>
    <script src="https://client1.synchronousdigital.com/public/invoice/assets/js/html2canvas.min.js"></script>
    <script src="https://client1.synchronousdigital.com/public/invoice/assets/js/main.js"></script>
    
    <!--Modal-->
    <script src="{{asset('public/admin_package/assets/js/vendor.min.js')}}"></script>

<!-- Mirrored from thememarch.com/demo/html/ivonne/hotel-booking-invoice.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 06 Jul 2022 16:27:26 GMT -->




</body></html>

