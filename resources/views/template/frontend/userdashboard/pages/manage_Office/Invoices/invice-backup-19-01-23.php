<?php 
    // $currency=Session::get('currency_symbol');
    $currency = $data->currency_symbol ?? '';
?>

<html class="no-js" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="ThemeMarch">
        <title>Alhijaz  - Travel Booking System</title>
        
        <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="{{asset('/public/admin_package/frontend/css/lib/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('/public/invoice/assets/css/style.css')}}">
        
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
        .t-heading-top{
                background: #000;
                color: #fff;
                padding: 10px 15px;
                font-size: 22px;
        }
    </style>

    <body>
        <div class="cs-container">
            <div class="cs-invoice cs-style1">
                <div class="cs-invoice_in" id="download_section">
                    
                    <div class="cs-invoice_head cs-type1 cs-mb25">
                        <div class="cs-invoice_left">
                            <p class="cs-invoice_number cs-white_color cs-mb0 cs-f18" style="font-size: 22px;">
                                <b class="cs-invoice_number cs-white_color cs-mb0">Invoice No:</b> {{  $data->generate_id ?? '' }} 
                            </p>
                        </div>
                        <div class="cs-invoice_right cs-text_right">
                            <img src="{{ asset('/public/admin_package/frontend/images/logo.png') }}" alt="Logo">
                        </div>
                    </div>
                
                    <div class="cs-invoice_head cs-mb10">
                        <div class="cs-invoice_left">
                            <p>
                                <b class="cs-primary_color">
                                    @if($data->confirm != 1)
                                        TENTATIVE
                                    @else
                                        CONFIRMED
                                    @endif
                                
                                </b><br>
                                Duration : <b class="cs-primary_color">{{ $data->time_duration ?? '' }} Nights</b> <br>
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
                            @endif
                            
                            @if(isset($data->f_name) && $data->f_name != null && $data->f_name != '')
                                Lead Passenger Name : <b class="cs-cs-primary_color"> {{$data->f_name}} {{ $data->middle_name }} </b><br>
                            @endif
                            
                            <?php //dd($data->count_P_Input); ?>
                            
                            @if(isset($data->count_P_Input) && $data->count_P_Input != null && $data->count_P_Input != '' && $data->count_P_Input > 0)
                                <b class="cs-primary_color">More Travelers on this trip</b> <br>
                                <?php
                                    $adults_details = $data->more_Passenger_Data;
                                    // dd($adults_details);
                                    $Adult_No = 1;
                                    
                                    if($adults_details != 'null')
                                    {
                                        $adults_details1 = json_decode($adults_details);
                                        foreach($adults_details1 as $adults_details1)
                                        {
                                ?>
                                            More Passenger {{ $Adult_No }} : <b>{{ $adults_details1->more_p_fname }} ( {{$adults_details1->more_p_gender}} )</b> <?php $Adult_No++ ?> <br>
                                <?php
                                        }
                                    }
                                ?>
                            @else
                                
                            @endif
                            
                        </div>
                        <?php $service_select     = json_decode($data->services); ?>
                        @if(isset($service_select) && $service_select != null && $service_select != '')
                            @if($service_select[0] == '1' || $service_select[0] == 'accomodation_tab')
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
                            @endif
                        @endif
                    </div>
                    
                    @if(isset($data->count_P_Input) && $data->count_P_Input != null && $data->count_P_Input != '' && $data->count_P_Input > 0)
                        <section class="content cs-round_border">
                            <div class="t-heading-top"><i class="fa-solid fa-users-line"></i> Travelers Details</div>
                        <!--    <div class="container-fluid">-->
                        <!--        <div class="row">-->
                        <!--            <div class="col-lg-12 col-6">-->
                        <!--                <div class="panel-body padding-bottom-none">-->
                        <!--                    <div class="block-content block-content-full">-->
                        <!--                        <div id="example_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">-->
                        <!--                            <div class="row">-->
                        <!--                                <div class="col-sm-12">-->
                                                            <table style="" class="table table-bordered table-striped table-vcenter dataTable no-footer" id="myTable" role="grid" aria-describedby="example_info">
                                                                <thead>
                                                                    <tr role="row" style="font-size:12px;">
                                                                        <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;text-align:center;">SR No.</th>
                                                                        <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;text-align:center;">First Name</th>
                                                                        <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;text-align:center;">Last Name</th>
                                                                        <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;text-align:center;">DOB</th>
                                                                        <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;text-align:center;">Nationality</th>
                                                                        <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;text-align:center;">Gender</th>
                                                                        <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;text-align:center;">Passport Number</th>
                                                                        <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;text-align:center;">Passport Expiry Date</th>
                                                                    </tr>
                                                                </thead>
                                                                
                                                                <tbody style="text-align: center;">
                                                                    <?php
                                                                        $adults_details = $data->more_Passenger_Data;
                                                                        $Adult_No       = 1;
                                                                        
                                                                        if($adults_details != 'null')
                                                                        {
                                                                            $adults_details1 = json_decode($adults_details);
                                                                            foreach($adults_details1 as $adults_details1)
                                                                            {
                                                                    ?>
                                                                                <tr role="row" class="odd" style="font-size:11px;">
                                                                                    <td class="sorting_1">{{ $Adult_No }} </td>
                                                                                    <td class="sorting_1">{{ $adults_details1->more_p_fname }} </td>
                                                                                    <td class="sorting_1">{{ $adults_details1->more_p_lname }} </td>
                                                                                    <td class="sorting_1">{{ $adults_details1->more_p_dob }} </td>
                                                                                    <td class="sorting_1">
                                                                                        
                                                                                        <?php
                                                                                        foreach($all_countries as $all_res)
                                                                                        {
                                                                                            if($all_res->id == $adults_details1->more_p_nationality)
                                                                                            {
                                                                                                echo $all_res->name;
                                                                                            }
                                                                                            elseif($all_res->name == $adults_details1->more_p_nationality)
                                                                                            {
                                                                                                echo $adults_details1->more_p_nationality;
                                                                                            }
                                                                                            
                                                                                        }
                                                                                        ?>
                                                                                        
                                                                                        
                                                                                         </td>
                                                                                    <td class="sorting_1">{{ $adults_details1->more_p_gender }} </td>
                                                                                    <td class="sorting_1">{{ $adults_details1->more_p_passport_number }} </td>
                                                                                    <td class="sorting_1">{{ $adults_details1->more_p_passport_expiry }} </td>
                                                                                </tr>
                                                                                <?php $Adult_No++ ?>
                                                                    <?php
                                                                            }
                                                                        }
                                                                    ?>
                                                                </tbody>
                                                            </table>
                        <!--                                </div>-->
                        <!--                            </div>-->
                        <!--                        </div>-->
                        <!--                    </div>-->
                        <!--                </div>-->
                        <!--            </div>-->
                        <!--        </div>-->
                        <!--    </div>-->
                        </section>
                    @endif
                    
                    <div class="cs-invoice_head cs-50_col cs-mb25">
                    
                        @if(isset($accomodation_details) && $accomodation_details != null && $accomodation_details != '')
                        <?php  //dd($accomodation_details); ?>
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
                    
                    <?php $service_select = json_decode($data->services); ?>
                    @if(isset($service_select) && $service_select != null && $service_select != '')
                        @if($service_select[0] != '1' && $service_select[0] != 'accomodation_tab' && $service_select[0] == 'visa_tab')
                            <?php
                                $visa_type                  = $data->visa_type;
                                $visa_Pax                   = $data->visa_Pax;
                                $no_of_pax_days             = $data->no_of_pax_days;
                                $visa_fee                   = $data->visa_fee;
                                $total_visa_markup_value    = $data->total_visa_markup_value;
                                $exchange_rate_visa_fee     = $data->exchange_rate_visa_fee;
                                $more_visa_detailsE         = $data->more_visa_details;
                            ?>
                            <div class="cs-table cs-style1">
                                <div class="cs-round_border">
                                       <div class="t-heading-top"><i class="fa-solid fa-passport"></i>Visa Invoice Details</div>
                                    <div class="cs-table_responsive">
                                        <table class="cs-border_less">
                                            <tbody>
                                              
                                                <tr>
                                                    <td>Total Adults : {{ $data->no_of_pax_days }}</td>
                                                </tr>
                                                <tr style="border: 2px solid #000000; text-align:center;">
                                                    <td class="cs-primary_color cs-semi_bold">Visa Type</td>
                                                    <td class="cs-primary_color cs-semi_bold">Visa Pax</td>
                                                    <td class="cs-primary_color cs-semi_bold">Visa Price/Person</td>
                                                    <td class="cs-primary_color cs-semi_bold">Visa Price</td>
                                                </tr>
                                                <tr style="text-align:center;">
                                                    <td>{{ $visa_type }}</td>
                                                    <td>{{ $visa_Pax }}</td>
                                                    <td>
                                                        @if(isset($exchange_rate_visa_fee) && $exchange_rate_visa_fee != null && $exchange_rate_visa_fee != '')
                                                            {{ $currency }} {{ $exchange_rate_visa_fee }}
                                                        @else
                                                            @if(isset($total_visa_markup_value) && $total_visa_markup_value != null && $total_visa_markup_value != '')
                                                                {{ $currency }} {{ $total_visa_markup_value }}
                                                            @else
                                                                {{ $currency }} {{ $visa_fee }}
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(isset($exchange_rate_visa_fee) && $exchange_rate_visa_fee != null && $exchange_rate_visa_fee != '')
                                                            {{ $currency }} {{ $exchange_rate_visa_fee * $visa_Pax}}
                                                        @else
                                                            @if(isset($total_visa_markup_value) && $total_visa_markup_value != null && $total_visa_markup_value != '')
                                                                {{ $currency }} {{ $total_visa_markup_value * $visa_Pax }}
                                                            @else
                                                                {{ $currency }} {{ $visa_fee * $no_of_pax_days }}
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                                
                                                @if(isset($more_visa_detailsE) && $more_visa_detailsE != null && $more_visa_detailsE != '')  
                                                    <?php $more_visa_details = json_decode($more_visa_detailsE); //dd($more_visa_details); ?>
                                                    @foreach($more_visa_details as $more_visa_details1)
                                                        <tr style="text-align:center;">
                                                            <td>{{ $more_visa_details1->more_visa_type }}</td>
                                                            <td>{{ $more_visa_details1->more_visa_Pax }}</td>
                                                            <td>{{ $currency }} 
                                                                <?php
                                                                    $more_visa_Pax                  = $more_visa_details1->more_visa_Pax;
                                                                    $more_visa_fee                  = $more_visa_details1->more_visa_fee;
                                                                    if(isset($more_visa_details1->more_total_visa_markup_value) && isset($more_visa_details1->more_exchange_rate_visa_fee)){
                                                                        $more_total_visa_markup_value   = $more_visa_details1->more_total_visa_markup_value;
                                                                        $more_exchange_rate_visa_fee    = $more_visa_details1->more_exchange_rate_visa_fee;
                                                                    }else{
                                                                        $more_total_visa_markup_value   = 0;
                                                                        $more_exchange_rate_visa_fee    = 0;
                                                                    }
                                                                    //dd($more_exchange_rate_visa_fee);
                                                                ?>
                                                                @if(isset($more_exchange_rate_visa_fee) && $more_exchange_rate_visa_fee != null && $more_exchange_rate_visa_fee != '')
                                                                    {{ $more_exchange_rate_visa_fee}}
                                                                @else
                                                                    @if($more_total_visa_markup_value != null && $more_total_visa_markup_value != '')
                                                                        {{ $more_total_visa_markup_value }}
                                                                    @else
                                                                        {{ $more_visa_fee }}
                                                                    @endif
                                                                @endif
                                                            </td>
                                                            <td>{{ $currency }}
                                                                @if(isset($more_exchange_rate_visa_fee) && $more_exchange_rate_visa_fee != null && $more_exchange_rate_visa_fee != '')
                                                                    {{ $more_exchange_rate_visa_fee * $visa_Pax}}
                                                                @else
                                                                    @if($more_total_visa_markup_value != null && $more_total_visa_markup_value != ''){
                                                                        {{ $more_visa_Pax * $more_total_visa_markup_value }}
                                                                    @else
                                                                        {{ $more_visa_Pax * $more_visa_fee }}
                                                                    @endif
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        
                        @elseif($service_select[0] == 'transportation_tab')  
                            <?php
                                // dd($data);
                                $no_of_pax_days                 = $data->no_of_pax_days;
                                $transportation_details_E       = $data->transportation_details;
                                $transportation_details_more_E  = $data->transportation_details_more;
                            ?>
                            <div class="cs-table cs-style1">
                                <div class="cs-round_border">
                                    <div class="t-heading-top"><i class="fa-solid fa-passport"></i>Tranportation Invoice Details</div>
                                    <div class="cs-table_responsive">
                                        <table class="cs-border_less">
                                            <tbody>
                                              
                                                <tr><td>Total Adults : {{ $data->no_of_pax_days }}</td></tr>
                                                
                                                <tr style="border: 2px solid #000000; text-align:center;">
                                                    <td class="cs-primary_color cs-semi_bold">Pickup Location</td>
                                                    <td class="cs-primary_color cs-semi_bold">Dropof Location</td>
                                                    <td class="cs-primary_color cs-semi_bold">Transportation Type</td>
                                                    <td class="cs-primary_color cs-semi_bold">No of vehicle</td>
                                                    <td class="cs-primary_color cs-semi_bold">Price/Vehicle</td>
                                                    <td class="cs-primary_color cs-semi_bold">Total Vehicle Price</td>
                                                    <td class="cs-primary_color cs-semi_bold">Sale/Person</td>
                                                </tr>
                                                
                                                @if(isset($transportation_details_E) && $transportation_details_E != null && $transportation_details_E != '')
                                                    <?php $transportation_details_D = json_decode($transportation_details_E); //dd($transportation_details_D); ?>   
                                                    @foreach($transportation_details_D as $transportation_details_DS)
                                                        @if($transportation_details_DS->transportation_trip_type != null && $transportation_details_DS->transportation_trip_type != '')
                                                            
                                                            <tr style="text-align:center;">
                                                                <td>{{ $transportation_details_DS->transportation_pick_up_location ?? '' }}</td>
                                                                <td>{{ $transportation_details_DS->transportation_drop_off_location ?? '' }}</td>
                                                                <td>{{ $transportation_details_DS->transportation_trip_type ?? '' }}</td>
                                                                <td>{{ $transportation_details_DS->transportation_no_of_vehicle ?? ''}}</td>
                                                                <td>{{ $currency }} {{ $transportation_details_DS->transportation_price_per_vehicle ?? '' }}</td>
                                                                <td>{{ $currency }} {{ $transportation_details_DS->transportation_vehicle_total_price ?? '' }}</td>
                                                                <td>{{ $currency }} {{ $transportation_details_DS->transfer_markup_price_invoice ?? '' }}</td>
                                                            </tr>
                                                            
                                                        @endif
                                                    @endforeach
                                                @endif 
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        
                            @if(isset($transportation_details_more_E) && $transportation_details_more_E != null && $transportation_details_more_E != '')
                                <?php $transportation_details_more_D = json_decode($transportation_details_more_E); ?>
                                <div class="cs-table cs-style1">
                                    <div class="cs-round_border">
                                        <div class="t-heading-top"><i class="fa-solid fa-passport"></i>More Destination Details</div>
                                        <div class="cs-table_responsive">
                                            <table class="cs-border_less">
                                                <tbody>
                                                    <tr style="border: 2px solid #000000; text-align:center;">
                                                        <td class="cs-primary_color cs-semi_bold">Pickup Location</td>
                                                        <td class="cs-primary_color cs-semi_bold">Dropof Location</td>
                                                        <td class="cs-primary_color cs-semi_bold">Pickup Time</td>
                                                        <td class="cs-primary_color cs-semi_bold">Dropof Time</td>
                                                        <td class="cs-primary_color cs-semi_bold">Total Transit Time</td>
                                                    </tr>
                                                    
                                                    @foreach($transportation_details_more_D as $transportation_details_more_DS)        
                                                        <tr style="text-align:center;">
                                                            <td>{{ $transportation_details_more_DS->more_transportation_pick_up_location ?? '' }}</td>
                                                            <td>{{ $transportation_details_more_DS->more_transportation_drop_off_location ?? '' }}</td>
                                                            <td>{{ $transportation_details_more_DS->more_transportation_pick_up_date ?? '' }}</td>
                                                            <td>{{ $transportation_details_more_DS->more_transportation_drop_of_date ?? ''}}</td>
                                                            <td>{{ $transportation_details_more_DS->more_transportation_total_Time ?? ''}}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @else
                        @endif
                    @endif
                    
                    <?php 
                        $accomodation_details       = Json_decode($data->accomodation_details);
                        $accomodation_details_more  = Json_decode($data->accomodation_details_more);
                    ?>
                    
                    @if(isset($accomodation_details) && $accomodation_details != null && $accomodation_details != '')
                        <div class="cs-table cs-style1">
                            <div class="cs-round_border">
                                <div class="cs-table_responsive">
                                    <table class="cs-border_less">
                                        <tbody>
                                            <tr>
                                                <td class="cs-primary_color cs-semi_bold cs-f18" colspan="3">Rooms</td>
                                                <td class="cs-primary_color cs-semi_bold cs-f18">Hotel Name/City</td>
                                                <!--<td class="cs-primary_color cs-semi_bold cs-f18">Dates</td>-->
                                                <!--<td class="cs-primary_color cs-semi_bold cs-f18">Check-out</td>-->
                                                <td class="cs-primary_color cs-semi_bold cs-f18">Price/Room</td>
                                                <td class="cs-primary_color cs-semi_bold cs-f18">Total Room Price</td>
                                            </tr>
                                            @foreach($accomodation_details as $accomodation_details)
                                                <tr>
                                                    <td class="cs-primary_color cs-semi_bold cs-f14" colspan="3">
                                                        <i class="fa-solid fa-bed"></i> 
                                                        <?php if($accomodation_details->acc_type == 'Double'){ ?>
                                                            Double Rooms X {{ $accomodation_details->acc_qty ?? '' }}
                                                        <?php } elseif($accomodation_details->acc_type == 'Triple'){ ?>
                                                            Triple Rooms X {{ $accomodation_details->acc_qty ?? '' }}
                                                        <?php } elseif($accomodation_details->acc_type == 'Quad'){ ?>
                                                            Quad Rooms X {{ $accomodation_details->acc_qty ?? '' }}
                                                        <?php } else{ ?>
                                                            Double Rooms X {{ $accomodation_details->acc_qty ?? '' }}
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        {{ $accomodation_details->acc_hotel_name ?? '' }} {{ $accomodation_details->hotel_city_name ?? '' }} ({{ $accomodation_details->hotel_meal_type ?? '' }})<br>
                                                        <img style="border: 0;max-width: 4%;height: auto;vertical-align: middle;" src="https://client1.synchronousdigital.com/public/invoice/assets/img/calendar-check-solid.svg"alt="Logo">
                                                        <?php
                                                            $dateValue = strtotime(date("d-m-Y", strtotime($accomodation_details->acc_check_in ?? '')));
                                                            $year       = date('Y',$dateValue);
                                                            $monthName  = date('F',$dateValue);
                                                            $date_d     = date('d',$dateValue);
                                                            $monthNu    = date('m',$dateValue);
                                                            $day        = date('l', $dateValue);
                                                            echo $date_d . '/' . $monthNu . '/' . $year;
                                                        ?>
                                                        <img style="border: 0;max-width: 4%;height: auto;vertical-align: middle;" src="https://client1.synchronousdigital.com/public/invoice/assets/img/calendar-check-solid.svg"alt="Logo">  
                                                        <?php
                                                            $dateValue = strtotime(date("d-m-Y", strtotime($accomodation_details->acc_check_out ?? '')));
                                                            $year       = date('Y',$dateValue);
                                                            $monthName  = date('F',$dateValue);
                                                            $date_d     = date('d',$dateValue);
                                                            $monthNu    = date('m',$dateValue);
                                                            $day        = date('l', $dateValue);
                                                            echo $date_d . '/' . $monthNu . '/' . $year;
                                                        ?>
                                                    </td>
                                                    <td class="">{{ $currency ?? '' }} {{ $accomodation_details->hotel_invoice_markup ?? '0'}}</td>
                                                    <td class="">
                                                        {{ $currency ?? '' }}
                                                        @if(isset($accomodation_details->hotel_invoice_markup) && $accomodation_details->hotel_invoice_markup != null && $accomodation_details->hotel_invoice_markup != '')
                                                            <?php $hotel_invoice_markup = $accomodation_details->hotel_invoice_markup; ?>
                                                        @else
                                                            <?php $hotel_invoice_markup = 0; ?>
                                                        @endif
                                                        @if(isset($accomodation_details->acc_qty) && $accomodation_details->acc_qty != null && $accomodation_details->acc_qty != '')
                                                            <?php $acc_qty = $accomodation_details->acc_qty; ?>
                                                        @else
                                                            <?php $acc_qty = 0; ?>
                                                        @endif
                                                        <?php echo $hotel_invoice_markup * $acc_qty ?>
                                                    </td>
                                                </tr>
                                                
                                                @if(isset($accomodation_details_more) && $accomodation_details_more != null && $accomodation_details_more != '')
                                                    <?php //dd($accomodation_details_more); ?>
                                                    @foreach($accomodation_details_more as $accomodation_details_more1)
                                                        @if($accomodation_details_more1->more_hotel_city == $accomodation_details->hotel_city_name)
                                                            <tr>
                                                                <td class="cs-primary_color cs-semi_bold cs-f14" colspan="3">
                                                                    <i class="fa-solid fa-bed"></i>
                                                                    @if(isset($accomodation_details_more1->more_acc_type) && $accomodation_details_more1->more_acc_type != null && $accomodation_details_more1->more_acc_type != '')
                                                                        <?php if($accomodation_details_more1->more_acc_type == 'Double'){ ?>
                                                                            Double Rooms X {{ $accomodation_details_more1->more_acc_qty ?? '' }}
                                                                        <?php } elseif($accomodation_details_more1->more_acc_type == 'Triple'){ ?>
                                                                            Triple Rooms X {{ $accomodation_details_more1->more_acc_qty ?? '' }}
                                                                        <?php } elseif($accomodation_details_more1->more_acc_type == 'Quad'){ ?>
                                                                            Quad Rooms X {{ $accomodation_details_more1->more_acc_qty ?? '' }}
                                                                        <?php } else{  } ?>
                                                                    @else
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    {{ $accomodation_details_more1->more_acc_hotel_name ?? '' }} {{ $accomodation_details_more1->more_hotel_city ?? '' }} ({{ $accomodation_details_more1->more_hotel_meal_type ?? '' }})<br>
                                                                    <img style="border: 0;max-width: 4%;height: auto;vertical-align: middle;" src="https://client1.synchronousdigital.com/public/invoice/assets/img/calendar-check-solid.svg"alt="Logo"> 
                                                                    <?php
                                                                        $dateValue = strtotime(date("d-m-Y", strtotime($accomodation_details_more1->more_acc_check_in ?? '')));
                                                                        $year       = date('Y',$dateValue);
                                                                        $monthName  = date('F',$dateValue);
                                                                        $date_d     = date('d',$dateValue);
                                                                        $monthNu    = date('m',$dateValue);
                                                                        $day        = date('l', $dateValue);
                                                                        echo $date_d . '/' . $monthNu . '/' . $year;
                                                                    ?>
                                                                    <img style="border: 0;max-width: 4%;height: auto;vertical-align: middle;" src="https://client1.synchronousdigital.com/public/invoice/assets/img/calendar-check-solid.svg"alt="Logo"> 
                                                                    <?php
                                                                        $dateValue = strtotime(date("d-m-Y", strtotime($accomodation_details_more1->more_acc_check_out ?? '')));
                                                                        $year       = date('Y',$dateValue);
                                                                        $monthName  = date('F',$dateValue);
                                                                        $date_d     = date('d',$dateValue);
                                                                        $monthNu    = date('m',$dateValue);
                                                                        $day        = date('l', $dateValue);
                                                                        echo $date_d . '/' . $monthNu . '/' . $year;
                                                                    ?>
                                                                </td>
                                                                <td class="">{{ $currency ?? '' }} {{ $accomodation_details_more1->more_hotel_invoice_markup ?? '0'}}</td>
                                                                <td class="">
                                                                    {{ $currency ?? '' }} 
                                                                    @if(isset($accomodation_details_more1->more_hotel_invoice_markup) && $accomodation_details_more1->more_hotel_invoice_markup != null && $accomodation_details_more1->more_hotel_invoice_markup != '')
                                                                        <?php $more_hotel_invoice_markup = $accomodation_details_more1->more_hotel_invoice_markup; ?>
                                                                    @else
                                                                        <?php $more_hotel_invoice_markup = 0; ?>
                                                                    @endif
                                                                    @if(isset($accomodation_details_more1->more_acc_qty) && $accomodation_details_more1->more_acc_qty != null && $accomodation_details_more1->more_acc_qty != '')
                                                                        <?php $more_acc_qty = $accomodation_details_more1->more_acc_qty; ?>
                                                                    @else
                                                                        <?php $more_acc_qty = 0; ?>
                                                                    @endif
                                                                    <?php echo $more_hotel_invoice_markup * $more_acc_qty ?>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                @endif
                                                
                                            @endforeach
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif 
                    
                    <div class="cs-table cs-style1 cs-mb20">
                      <div class="cs-table_responsive">
                        <table class="cs-border_less">
                          <tbody>
                            <tr>
                              <!--<td>Amet minim mollit non deserunt ullamco est sit</td>-->
                              <td class="cs-bold cs-primary_color cs-f18">Total Amount:</td>
                                <?php
                                    $accomodation_detailsE          = $data->accomodation_details;
                                    $accomodation_details_moreE     = $data->accomodation_details_more;
                                    $no_of_pax_days                 = $data->no_of_pax_days;
                                    $visa_Pax                       = $data->visa_Pax;
                                    $acc_total_amount               = 0;
                                    $markup_detailsE                = $data->markup_details;
                                    $more_visa_detailsE             = $data->more_visa_details;
                                    
                                    if(isset($accomodation_detailsE) && $accomodation_detailsE != null && $accomodation_detailsE != ''){
                                        $accomodation_details = Json_decode($accomodation_detailsE);
                                        foreach($accomodation_details as $accomodation_details1){
                                            if(isset($accomodation_details1->hotel_invoice_markup) && $accomodation_details1->hotel_invoice_markup != null && $accomodation_details1->hotel_invoice_markup != ''){
                                                $hotel_invoice_markup = $accomodation_details1->hotel_invoice_markup;
                                            }else{
                                                $hotel_invoice_markup = 0;
                                            }
                                            if(isset($accomodation_details1->acc_qty) && $accomodation_details1->acc_qty != null && $accomodation_details1->acc_qty != ''){
                                                $acc_qty = $accomodation_details1->acc_qty;
                                            }else{
                                                $acc_qty = 0;
                                            }
                                            $acc_total_amount = $acc_total_amount + ($hotel_invoice_markup * $acc_qty);   
                                        }
                                        
                                        if(isset($accomodation_details_moreE) && $accomodation_details_moreE != null && $accomodation_details_moreE != ''){
                                            $accomodation_details_more = Json_decode($accomodation_details_moreE);
                                            foreach($accomodation_details_more as $accomodation_details_more1){
                                                if(isset($accomodation_details_more1->more_hotel_invoice_markup) && $accomodation_details_more1->more_hotel_invoice_markup != null && $accomodation_details_more1->more_hotel_invoice_markup != ''){
                                                    $more_hotel_invoice_markup = $accomodation_details_more1->more_hotel_invoice_markup;
                                                }else{
                                                    $more_hotel_invoice_markup = 0;
                                                }
                                                if(isset($accomodation_details_more1->more_acc_qty) && $accomodation_details_more1->more_acc_qty != null && $accomodation_details_more1->more_acc_qty != ''){
                                                    $more_acc_qty = $accomodation_details_more1->more_acc_qty;
                                                }else{
                                                    $more_acc_qty = 0;
                                                }
                                                $acc_total_amount = $acc_total_amount + ($more_hotel_invoice_markup * $more_acc_qty);
                                            }
                                        }
                                    }
                                    
                                    if(isset($markup_detailsE) && $markup_detailsE != null & $markup_detailsE != ''){
                                        $markup_details1    = json_decode($markup_detailsE);
                                        foreach($markup_details1 as $markup_details)
                                        {
                                            if($markup_details->markup_Type_Costing == 'flight_Type_Costing'){
                                                if($markup_details->markup_price != null && $markup_details->markup_price != ''){
                                                    $flight_Type_Costing = $markup_details->markup_price * $no_of_pax_days;
                                                }else{
                                                    $flight_Type_Costing = 0;
                                                }
                                            }
                                            if($markup_details->markup_Type_Costing == 'transportation_Type_Costing'){
                                                if($markup_details->markup_price != null && $markup_details->markup_price != ''){
                                                    $transportation_Type_Costing = $markup_details->markup_price * $no_of_pax_days; 
                                                }else{
                                                    $transportation_Type_Costing = 0;
                                                }
                                            }
                                            if($markup_details->markup_Type_Costing == 'visa_Type_Costing'){
                                                if($markup_details->markup_price != null && $markup_details->markup_price != ''){
                                                    if(isset($visa_Pax) && $visa_Pax != null && $visa_Pax != ''){
                                                        $visa_Type_Costing = $markup_details->markup_price * $visa_Pax;
                                                    }else{
                                                        $visa_Type_Costing = $markup_details->markup_price * $no_of_pax_days;
                                                    }
                                                }else{
                                                    $visa_Type_Costing = 0;
                                                }
                                            }
                                        }
                                    }else{
                                        $flight_Type_Costing            = 0;
                                        $transportation_Type_Costing    = 0;
                                        $visa_Type_Costing              = 0;   
                                    }
                                    
                                    if(isset($more_visa_detailsE) && $more_visa_detailsE != null & $more_visa_detailsE != ''){
                                        $more_visa_Type_Costing = 0;
                                        $more_visa_details  = json_decode($more_visa_detailsE);
                                        // dd($more_visa_details);
                                        foreach($more_visa_details as $more_visa_details1){
                                            $more_visa_Pax                  = $more_visa_details1->more_visa_Pax;
                                            $more_total_visa_markup_value   = $more_visa_details1->more_total_visa_markup_value;
                                            $more_visa_fee                  = $more_visa_details1->more_visa_fee;
                                            if($more_total_visa_markup_value != null && $more_total_visa_markup_value != ''){
                                                $more_visa_Type_Costing = $more_visa_Type_Costing + ($more_visa_Pax * $more_total_visa_markup_value);   
                                            }else{
                                                $more_visa_Type_Costing = $more_visa_Type_Costing + ($more_visa_Pax * $more_visa_fee);
                                            }
                                        }
                                    }else{
                                        $more_visa_Type_Costing = 0;
                                    }
                                    
                                    if(isset($data->total_sale_price_all_Services) && $data->total_sale_price_all_Services != null && $data->total_sale_price_all_Services != ''){
                                        $total_Payable = $data->total_sale_price_all_Services;
                                    }else{
                                        $total_Payable = $acc_total_amount + $transportation_Type_Costing + $flight_Type_Costing + $visa_Type_Costing + $more_visa_Type_Costing;   
                                    }
                                    
                                ?>
                                
                                
                              <td class="cs-bold cs-primary_color cs-f18 cs-text_right">{{ $data->currency_symbol ?? "" }} {{ $total_Payable  }}</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    
                    <div class="cs-table cs-style1">
                        <div class="t-heading-top"><i class="fa-solid fa-money-check-dollar"></i> Payment Details</div>
                       
                      <div class="cs-table_responsive">
                        <table>
                          <tbody style="text-align: center;">
                            <!--<tr class="cs-invoice_head cs-type1 cs-mb10" style="text-align:center; padding:9px; color:#ffffff;">-->
                            <tr class="cs-type1 cs-mb10" style="text-align:center; padding:9px;">
                              <td class="cs-text_center cs-semi_bold">Invoice No</td>
                              <td class="cs-text_center cs-semi_bold">Paid On</td>
                              <td class="cs-text_center cs-semi_bold">Total Amount</td>
                              <td class="cs-text_center cs-semi_bold">Total Recieved</td>
                              <td class="cs-text_center cs-semi_bold">Remaining</td>
                            </tr>
                            @if(isset($invoice_P_details) && $invoice_P_details != null && $invoice_P_details != '')
                                <?php $t_C = $total_Payable ?? '0'; ?>
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
                                        <td>{{ $currency }} {{ round($valueI->total_Amount ?? '0', 2) }}</td>
                                        <td>{{ $currency }} {{ round($valueI->recieved_Amount ?? '0', 2) }}</td>
                                        <td>{{ $currency }} 
                                            @if($valueI->remaining_Amount < -1)
                                                0
                                            @else
                                                {{ round($t_C ?? '0', 2) }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
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
                                        @if(isset($total_invoice_Payments))
                                        <?php // dd($recieved_invoice_Payments); ?>
                                            <td>
                                                <p class="cs-mb5 cs-primary_color cs-semi_bold" style="margin-left: 60%;">Total Paid:</p>
                                                <p class="cs-m0 cs-primary_color cs-semi_bold" style="margin-left: 60%;">Total Balance Due:</p>
                                                <p class="cs-m0 cs-primary_color cs-semi_bold" style="margin-left: 60%;">Over Paid:</p>
                                            </td>
                                            <td>
                                                <p class="cs-mb5 cs-text_left cs-primary_color cs-semi_bold">{{ $currency }} {{ round($recieved_invoice_Payments ?? '0', 2) }}</p>
                                                @if(isset($total_invoice_Payments) && $total_invoice_Payments != null && $total_invoice_Payments != '' || $total_invoice_Payments != 0)
                                                    <?php $ttt = $total_invoice_Payments->total_Amount - $recieved_invoice_Payments ?>
                                                    @if(isset($ttt) && $ttt < -1)
                                                        <?php $ttt =  $recieved_invoice_Payments - $total_invoice_Payments->total_Amount ?>
                                                        <p class="cs-m0 cs-text_left cs-primary_color cs-semi_bold">{{ $currency }} {{ 0 }}</p>
                                                        <p class="cs-mb5 cs-text_left cs-primary_color cs-semi_bold">{{ $currency }} {{ round($ttt ?? '0', 2) }}</p>
                                                    @else
                                                        <p class="cs-m0 cs-text_left cs-primary_color cs-semi_bold">{{ $currency }} {{ round($ttt ?? '0', 2) }}</p>
                                                        <p class="cs-mb5 cs-text_left cs-primary_color cs-semi_bold">{{ $currency }} {{ 0 }}</p>
                                                    @endif
                                                @else
                                                    <p class="cs-m0 cs-text_left cs-primary_color cs-semi_bold">{{ $currency }} {{ round($recieved_invoice_Payments ?? '0', 2) }}</p>
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
                                                <p class="cs-mb5 cs-text_left cs-primary_color cs-semi_bold">{{ $currency ?? '' }} 0</p>
                                                <p class="cs-m0 cs-text_left cs-primary_color cs-semi_bold">{{ $currency ?? '' }} {{ $total_Payable ?? '0' }}</p>
                                                <p class="cs-mb5 cs-text_left cs-primary_color cs-semi_bold">{{ $currency ?? '' }} 0</p>
                                            </td> 
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="cs-table cs-style1">
                        <div class="cs-table_responsive">
                            <ul class="d-none">
                                <li>{{ $data->payment_messag }}</li>
                            </ul>
                            <textarea class="form-control" readonly style="height: 130px;">{{ $data->payment_messag }}</textarea>
                            <div>
                                <p style="font-size: 12px;margin-top: 10px;text-align: center;">Al Hijaz tours ltd is a acting agent on behalf of sakeenah tours atol number 10941</p>
                            </div>
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
    
        <script src="{{asset('public/invoice/assets/js/jquery.min.js')}}"></script>
        <script src="{{asset('public/invoice/assets/js/jspdf.min.js')}}"></script>
        <script src="{{asset('public/invoice/assets/js/html2canvas.min.js')}}"></script>
        <script src="{{asset('/public/invoice/assets/js/main.js')}}"></script>
        
        <!--Modal-->
        <script src="{{asset('public/admin_package/assets/js/vendor.min.js')}}"></script>

</body>

</html>

