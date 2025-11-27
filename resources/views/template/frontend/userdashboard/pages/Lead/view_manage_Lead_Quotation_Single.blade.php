@extends('template/frontend/userdashboard/layout/default')
@section('content')

    <?php $currency = Session::get('currency_symbol'); // dd($data); ?>
    
    <style>
        .nav-link{
          color: #575757;
          font-size: 18px;
        }
    </style>

    <div class="content-wrapper">
        
        <section class="content">
            
            <div class="row">
                <div class="col-lg-6">
                    <h1>Lead Quotation #{{ $data->generate_id }}</h1>
                </div>
                
                <div class="col-lg-6" style="text-align: right;margin-top: 15px;">
                    <a href="{{URL::to('invoice_Quotations_Admin')}}/{{$data->id}}" class="btn btn-primary">
                        <span>Print</span>
                    </a>
                    @if($data->quotation_status != 1)
                        @if($data->all_services_quotation != '1')
                            <a href="{{URL::to('edit_manage_Package_Quotation')}}/{{$data->id}}" class="btn btn-primary d-none">Edit Lead Quotation</a>
                        @else
                            <a href="{{URL::to('edit_manage_Package_Quotation_New')}}/{{$data->id}}" class="btn btn-primary d-none">Edit Lead Quotation</a>
                        @endif
                    @endif
                </div>
                
            </div>
            
            @if($data->booking_customer_id == '-1')
                @foreach($Agents_detail as $Agents_detailS)
                    @if($data->agent_Id == $Agents_detailS->id)
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card" style="border-radius: 16px;border: solid black 0px;background-color: #dde6ed;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <span>Company Name</span>
                                                <h5>{{ $Agents_detailS->company_name ?? '' }}</h5>
                                            </div>
                                            
                                            <div class="col-lg-3">
                                                <span>Agent Name</span>
                                                <h5>{{ $Agents_detailS->agent_Name ?? '' }}</h5>
                                            </div>
                                            
                                            <div class="col-lg-3">
                                                <span>Company Address</span>
                                                <h5>{{ $Agents_detailS->company_address ?? '' }}</h5>
                                            </div>
                                            
                                            <div class="col-lg-3">
                                                <span>Company Email</span>
                                                <h5>{{ $Agents_detailS->company_email }}</h5>
                                            </div>
                                        </div>
                                        
                                        <div class="row d-none" style="margin-top: 12px;">
                                            <div class="col-lg-3">
                                                <span>Agent Name</span>
                                                <h5>{{ $Agents_detailS->agent_Name ?? '' }}</h5>
                                            </div>
                                            
                                            <div class="col-lg-3">
                                                <span>Agent Phone</span>
                                                <h5>{{ $Agents_detailS->agent_contact_number ?? '' }}</h5>
                                            </div>
                                            
                                            <div class="col-lg-3">
                                                <span>Agent Address</span>
                                                <h5>{{ $Agents_detailS->agent_Address ?? '' }}</h5>
                                            </div>
                                            
                                            <div class="col-lg-3">
                                                <span>Agent Email</span>
                                                <h5>{{ $Agents_detailS->agent_Email ?? '' }}</h5>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="card" style="border-radius: 16px;border: solid black 0px;background-color: #dde6ed;">
                        <div class="card-body">
                            
                            <div class="row">
                                <div class="col-lg-3">
                                    @if($data->booking_customer_id == '-1' || $data->booking_customer_id == 0 || $data->booking_customer_id == '' || $data->booking_customer_id == null)
                                        <span>Lead Name</span>
                                        <h5>{{ $data->agent_Company_Name ?? '' }}</h5>
                                    @else
                                        <span>Customer Name</span>
                                        <h5>
                                            @foreach($booking_customers as $value)
                                                @if($value->id == $data->booking_customer_id )
                                                    {{ $value->name ?? '' }}
                                                @endif
                                            @endforeach
                                        </h5>
                                    @endif
                                </div>
                                
                                <div class="col-lg-3">
                                    <span>Gender</span>
                                    <h5>{{ $data->gender ?? '' }}</h5>
                                </div>
                                
                                <div class="col-lg-3">
                                    <span>Phone</span>
                                    <h5>{{ $data->mobile ?? '' }}</h5>
                                </div>
                                
                                <div class="col-lg-3">
                                    <span>Email</span>
                                    <h5>{{ $data->email ?? '' }}</h5>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="card" style="border-radius: 16px;border: solid black 0px;background-color: #dde6ed;">
                        <div class="card-body">
                            
                            <div class="row">
                                <div class="col-lg-4">
                                    <span>Quotation Date</span>
                                    <h5>
                                        <?php
                                            $date_created = date("d-m-Y", strtotime($data->created_at ?? '' ));
                                            echo $date_created;
                                        ?>
                                    </h5>
                                </div>
                                
                                <div class="col-lg-4">
                                    <span>Quoted By</span>
                                    <h5>{{ $data->tour_author ?? '' }}</h5>
                                </div>
                                
                                <div class="col-lg-4">
                                    <span>Quotation Status</span>
                                    <h5>
                                        @if($data->quotation_status != 1)
                                            Tentative-Confirmed
                                        @else
                                            CONFIRMED
                                        @endif
                                    </h5>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                
                <div class="col-lg-8">
                    <div class="card" style="border-radius: 16px;border: solid black 0px;">
                        <div class="card-body">
                            
                            <div class="row">
                                <div class="col-lg-6">
                                    <h4>Service Settings</h4>
                                </div>
                                
                                <div class="col-lg-6">
                                    <span style="float:right;margin-top: 15px;"> Subtotal: <b>{{ $currency }} {{ round($data->total_sale_price_all_Services ?? '',2) }}</b></span>
                                </div>
                            </div>
                            
                            <?php
                                $flights_details                = $data->flights_details;
                                $return_flights_details         = $data->return_flights_details;
                                $more_visa_details              = $data->more_visa_details;
                                $transportation_details         = $data->transportation_details;
                                $transportation_details_more    = $data->transportation_details_more;
                                
                                $accomodation_details   = $data->accomodation_details;
                                if(isset($accomodation_details) && $accomodation_details != null && $accomodation_details != ''){
                                    $accomodation_details_Pricing   = json_decode($accomodation_details);
                                    // Accomodation Total Price
                                    $acc_Total_Price_All                        = 0;
                                    $more_acc_Total_Price_All                   = 0;
                                    $accomodation_final_total_sale_price_All    = 0;
                                    if(isset($accomodation_details_Pricing) && $accomodation_details_Pricing != null && $accomodation_details_Pricing != ''){
                                        foreach($accomodation_details_Pricing as $value){
                                            $acc_qty1 = $value->acc_qty;
                                            if(isset($value->hotel_invoice_markup) && $value->hotel_invoice_markup != null && $value->hotel_invoice_markup != ''){
                                                $acc_price1 = $value->hotel_invoice_markup;
                                            }else{
                                                $acc_price1 = $value->acc_total_amount;
                                            }
                                            $acc_Total_Price_All = $acc_Total_Price_All + ($acc_price1 * $acc_qty1);
                                        }
                                    }
                                }else{
                                    $acc_Total_Price_All    = 0;
                                }
                                
                                $accomodation_details_more = $data->accomodation_details_more;
                                if(isset($accomodation_details_more) && $accomodation_details_more != null && $accomodation_details_more != ''){
                                    $more_accomodation_details_Pricing  = json_decode($accomodation_details_more);
                                    // More Accomodation Total Price
                                    if(isset($more_accomodation_details_Pricing) && $more_accomodation_details_Pricing != null && $more_accomodation_details_Pricing != ''){
                                        foreach($more_accomodation_details_Pricing as $value){
                                            $more_acc_pax1 = $value->more_acc_qty;
                                            if(isset($value->more_hotel_invoice_markup) && $value->more_hotel_invoice_markup != null && $value->more_hotel_invoice_markup != ''){
                                                $more_acc_price1 = $value->more_hotel_invoice_markup;
                                            }else{
                                                $more_acc_price1 = $value->more_acc_total_amount;
                                            }
                                            $more_acc_Total_Price_All = $more_acc_Total_Price_All + ($more_acc_price1 * $more_acc_pax1);
                                        }
                                    }
                                }else{
                                    $more_acc_Total_Price_All = 0;
                                }
                                $accomodation_final_total_sale_price_All = $acc_Total_Price_All + $more_acc_Total_Price_All;
                                
                                $markup_details = $data->markup_details;
                                if(isset($markup_details) && $markup_details != null && $markup_details != ''){
                                    $markup_details_Pricing = json_decode($data->markup_details);
                                    // Flights & Transportation Pricing
                                    $flight_final_total_sale_price_All          = 0;
                                    $transportation_final_total_sale_price_All  = 0;
                                    if(isset($markup_details_Pricing) && $markup_details_Pricing != null && $markup_details_Pricing != ''){
                                        foreach($markup_details_Pricing as $value){
                                            $markup_Type_Costing1   = $value->markup_Type_Costing;
                                            if(isset($markup_Type_Costing1) && $markup_Type_Costing1 = ! null && $markup_Type_Costing1 != ''){
                                                if($value->markup_Type_Costing == 'flight_Type_Costing'){
                                                    $markup_price1 = $value->markup_price;
                                                    if(isset($markup_price1) && $markup_price1 != null && $markup_price1 != ''){
                                                        $flight_final_total_sale_price_All = $markup_price1;
                                                    }else{
                                                        if($data->all_services_quotation != '1'){
                                                            $flight_final_total_sale_price_All = 0;
                                                        }else{
                                                            $flight_final_total_sale_price_All = $value->without_markup_price;
                                                        }
                                                    }
                                                }
                                                if($value->markup_Type_Costing == 'transportation_Type_Costing'){
                                                    $without_markup_price_T = $value->without_markup_price;
                                                    if(isset($without_markup_price_T) && $without_markup_price_T != null && $without_markup_price_T != ''){    
                                                        $markup_price1          = $value->markup_price;
                                                        if(isset($markup_price1) && $markup_price1 != null && $markup_price1 != ''){
                                                            $transportation_final_total_sale_price_All = $markup_price1;
                                                        }else{
                                                            if($data->all_services_quotation != '1'){
                                                                $transportation_final_total_sale_price_All = 0;
                                                            }else{
                                                                $transportation_final_total_sale_price_All = $without_markup_price_T;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }else{
                                        $flight_final_total_sale_price_All          = 0;
                                        $transportation_final_total_sale_price_All  = 0;
                                    }
                                }else{
                                    $flight_final_total_sale_price_All          = 0;
                                    $transportation_final_total_sale_price_All  = 0;
                                }
                                
                                // Visa Price Total
                                $visa_fee_for_All_Pax               = 0;
                                $more_visa_fee_for_All_Pax          = 0;
                                $visa_final_total_sale_price_All    = 0;
                                if(isset($data->total_visa_markup_value) && $data->total_visa_markup_value != null && $data->total_visa_markup_value != ''){
                                    $visa_fee_for_All_Pax = $data->total_visa_markup_value * $data->visa_Pax;
                                }else if(isset($data->exchange_rate_visa_fee) && $data->exchange_rate_visa_fee != null && $data->exchange_rate_visa_fee != ''){
                                    $visa_fee_for_All_Pax = $data->exchange_rate_visa_fee * $data->visa_Pax;
                                }else{
                                    $visa_fee_for_All_Pax = $data->visa_fee * $data->visa_Pax;
                                }
                                
                                // More Visa Price Total
                                if(isset($more_visa_details) && $more_visa_details != null && $more_visa_details != ''){
                                    $more_visa_details_Pricing = json_decode($more_visa_details);
                                    if(isset($more_visa_details_Pricing) && $more_visa_details_Pricing != null && $more_visa_details_Pricing != ''){
                                        foreach($more_visa_details_Pricing as $value){
                                            $more_visa_Pax1 = $value->more_visa_Pax;
                                            if(isset($value->more_total_visa_markup_value) && $value->more_total_visa_markup_value != null && $value->more_total_visa_markup_value != ''){
                                                $more_visa_fee1 = $value->more_total_visa_markup_value;
                                            }else{
                                                $more_visa_fee1 = $value->more_exchange_rate_visa_fee;
                                            }
                                            $more_visa_fee_for_All_Pax = $more_visa_fee_for_All_Pax + ($more_visa_fee1 * $more_visa_Pax1);
                                        }
                                    }
                                }else{
                                    $more_visa_fee_for_All_Pax = 0;
                                }
                                $visa_final_total_sale_price_All = $visa_fee_for_All_Pax + $more_visa_fee_for_All_Pax;
                                // dd($visa_final_total_sale_price_All);
                            ?>
                            
                            <?php // dd($data->all_services_quotation); ?>
                            
                            @if($accomodation_final_total_sale_price_All > 0 || $data->all_services_quotation == '1')
                                <div class="row">
                                    <div class="col-lg-12" style="padding: 12px;">
                                        <span><b>Accomodation Details</b></span>
                                    </div>
                                    
                                    <div class="col-lg-12">
                                        <table class="table dataTable">
                                            <thead class="theme-bg-clr">
                                                <tr>
                                                    <th style="text-align: center;">Type</th>
                                                    <th style="text-align: center;">Dates</th>
                                                    <th style="text-align: center;">Hotel Details</th>
                                                    @if($data->all_services_quotation != '1')
                                                        <th style="text-align: center;">Total Price</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody style="text-align: center;">
                                                @if(isset($accomodation_details_Pricing) && $accomodation_details_Pricing != null && $accomodation_details_Pricing != '')
                                                    @foreach($accomodation_details_Pricing as $value)
                                                        <tr>
                                                            <td>{{ $value->acc_type }}</td>
                                                            <td>
                                                                Check-in    : <b>{{ $value->acc_check_in }}</b> <br>
                                                                Check-out   : <b>{{ $value->acc_check_out }}</b>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                    $string     = $value->acc_hotel_name;
                                                                    $shorter    = substr($string, 0, 15);
                                                                    $string     = substr($string, 0, strrpos($shorter, ' ')).'...';
                                                                    echo $string;
                                                                ?>
                                                                ({{ $value->hotel_city_name }})
                                                            </td>
                                                            @if($data->all_services_quotation != '1')
                                                                <td>
                                                                    {{ $currency }} {{ $value->hotel_invoice_markup }}
                                                                </td>
                                                            @endif
                                                        </tr>
                                                        
                                                        @if(isset($more_accomodation_details_Pricing) && $more_accomodation_details_Pricing != null && $more_accomodation_details_Pricing != '')
                                                            @foreach($more_accomodation_details_Pricing as $value1)
                                                                @if($value->hotel_city_name == $value1->more_hotel_city)
                                                                    <tr>
                                                                        <td>{{ $value1->more_acc_type }}</td>
                                                                        <td>
                                                                            Check-in    : <b>{{ $value1->more_acc_check_in }}</b> <br>
                                                                            Check-out   : <b>{{ $value1->more_acc_check_out }}</b>
                                                                        </td>
                                                                        <td>
                                                                            <?php
                                                                                $string     = $value1->more_acc_hotel_name;
                                                                                $shorter    = substr($string, 0, 15);
                                                                                $string     = substr($string, 0, strrpos($shorter, ' ')).'...';
                                                                                echo $string;
                                                                            ?>
                                                                            ({{ $value1->more_hotel_city }})
                                                                        </td>
                                                                        @if($data->all_services_quotation != '1')
                                                                            <td>
                                                                                {{ $currency }}  {{ $value1->more_hotel_invoice_markup }}
                                                                            </td>
                                                                        @endif
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                        
                                                    @endforeach
                                                @endif
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <hr>
                            @endif
                            
                            @if($flight_final_total_sale_price_All > 0 || $data->all_services_quotation == '1')
                                <div class="row">
                                    <div class="col-lg-12" style="padding: 12px;">
                                        <span><b>Flight Details</b></span>
                                    </div>
                                    
                                    <div class="col-lg-12">
                                        <table class="table dataTable">
                                            <thead class="theme-bg-clr">
                                                <tr>
                                                    <th style="text-align: center;">Type</th>
                                                    <th style="text-align: center;">Departure</th>
                                                    <th style="text-align: center;">Arrival</th>
                                                    <th style="text-align: center;">Airline</th>
                                                </tr>
                                            </thead>
                                            <tbody style="text-align: center;">
                                                @if(isset($flights_details) && $flights_details != null && $flights_details != '')
                                                <?php $flights_details_D = json_decode($flights_details); //dd($flights_details_D->flight_route_id_occupied); ?>
                                                    @foreach($flights_details_D as $value)
                                                        <tr>
                                                            <td>Departure : <b>{{ $value->departure_flight_route_type }}</b></td>
                                                            <td>
                                                                {{ $value->departure_airport_code }} <br>
                                                                <?php
                                                                    $departure_time = date("d-m-Y", strtotime($value->departure_time ?? '' ));
                                                                    echo '(<b>'.$departure_time.'</b>)';
                                                                ?>
                                                            </td>
                                                            <td>
                                                                {{ $value->arrival_airport_code }}<br>
                                                                <?php
                                                                    $arrival_time = date("d-m-Y", strtotime($value->arrival_time ?? '' ));
                                                                    echo '(<b>'.$arrival_time.'</b>)';
                                                                ?>
                                                            </td>
                                                            <td>{{ $value->other_Airline_Name2 }}</td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                
                                                @if(isset($return_flights_details) && $return_flights_details != null && $return_flights_details != '')
                                                    <?php $return_flights_details_D = json_decode($return_flights_details); //dd($return_flights_details_D->return_flight_route_id_occupied); ?>
                                                    @foreach($return_flights_details_D as $value)
                                                        <tr>
                                                             <td>Return : <b>{{ $value->return_flight_route_type }}</b></td>
                                                            <td>
                                                                {{ $value->return_departure_airport_code }}<br>
                                                                <?php
                                                                    $departure_time = date("d-m-Y", strtotime($value->return_departure_time ?? '' ));
                                                                    echo '(<b>'.$departure_time.'</b>)';
                                                                ?>
                                                            </td>
                                                            <td>
                                                                {{ $value->return_arrival_airport_code }}<br>
                                                                <?php
                                                                    $arrival_time = date("d-m-Y", strtotime($value->return_arrival_time ?? '' ));
                                                                    echo '(<b>'.$arrival_time.'</b>)';
                                                                ?>
                                                            </td>
                                                            <td>{{ $value->return_other_Airline_Name2 }}</td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <hr>
                            @endif
                            
                            @if($visa_final_total_sale_price_All > 0 || $data->all_services_quotation == '1')
                                <div class="row">
                                    <div class="col-lg-12" style="padding: 12px;">
                                        <span><b>Visa Details</b></span>
                                    </div>
                                    
                                    <div class="col-lg-12">
                                        <table class="table dataTable">
                                            <thead class="theme-bg-clr">
                                                <tr>
                                                    <th style="text-align: center;">Type</th>
                                                    <th style="text-align: center;">Pax</th>
                                                </tr>
                                            </thead>
                                            <tbody style="text-align: center;">
                                                <tr>
                                                    <td>{{ $data->visa_type }}</td>
                                                    <td>{{ $data->visa_Pax }}</td>
                                                </tr>
                                                @if(isset($more_visa_details) && $more_visa_details != null && $more_visa_details != '')
                                                    <?php $more_visa_details_D = json_decode($more_visa_details); ?>
                                                    @foreach($more_visa_details_D as $value)
                                                        <tr>
                                                            <td>{{ $value->more_visa_type }}</td>
                                                            <td>{{ $value->more_visa_Pax }}</td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                </div>
                                <hr>
                            @endif
                            
                            @if($transportation_final_total_sale_price_All > 0 || $data->all_services_quotation == '1')
                                <div class="row">
                                    <div class="col-lg-12" style="padding: 12px;">
                                        <span><b>Transportation Details</b></span>
                                    </div>
                                    
                                    <div class="col-lg-12">
                                        <table class="table dataTable">
                                            <thead class="theme-bg-clr">
                                                <tr>
                                                    <th style="text-align: center;">Type</th>
                                                    <th style="text-align: center;">Pickup Location</th>
                                                    <th style="text-align: center;">Dropoff Location</th>
                                                    <th style="text-align: center;">Pickup Date</th>
                                                    <th style="text-align: center;">Dropoff Date</th>
                                                </tr>
                                            </thead>
                                            <tbody style="text-align: center;">
                                                @if(isset($transportation_details) && $transportation_details != null && $transportation_details != '')
                                                    <?php $transportation_details_D = json_decode($transportation_details); // dd($transportation_details_D)?>
                                                    @foreach($transportation_details_D as $value)
                                                        @if(isset($value->transportation_pick_up_location) && $value->transportation_pick_up_location != null && $value->transportation_pick_up_location != '')
                                                            <tr>
                                                                <td>Departure</td>
                                                                <td>{{ $value->transportation_pick_up_location }}</td>
                                                                <td>{{ $value->transportation_drop_off_location }}</td>
                                                                <td>
                                                                    <?php
                                                                        $departure_time = date("d-m-Y", strtotime($value->transportation_pick_up_date ?? '' ));
                                                                        echo $departure_time;
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                        $departure_time = date("d-m-Y", strtotime($value->transportation_drop_of_date ?? '' ));
                                                                        echo $departure_time;
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            @if($value->transportation_trip_type == 'Return')
                                                                <tr>
                                                                    <td>Return</td>
                                                                    <td>{{ $value->return_transportation_pick_up_location }}</td>
                                                                    <td>{{ $value->return_transportation_drop_off_location }}</td>
                                                                    <td>
                                                                        <?php
                                                                            $departure_time = date("d-m-Y", strtotime($value->return_transportation_pick_up_date ?? '' ));
                                                                            echo $departure_time;
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php
                                                                            $departure_time = date("d-m-Y", strtotime($value->return_transportation_drop_of_date ?? '' ));
                                                                            echo $departure_time;
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                                
                                                @if(isset($transportation_details_more) && $transportation_details_more != null && $transportation_details_more != '')
                                                    <?php $transportation_details_more_D = json_decode($transportation_details_more); // dd($transportation_details_more_D)?>
                                                    @foreach($transportation_details_more_D as $value)
                                                        <tr>
                                                            <td>All Round</td>
                                                            <td>{{ $value->more_transportation_pick_up_location }}</td>
                                                            <td>{{ $value->more_transportation_drop_off_location }}</td>
                                                            <td>
                                                                <?php
                                                                    $departure_time = date("d-m-Y", strtotime($value->more_transportation_pick_up_date ?? '' ));
                                                                    echo $departure_time;
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                    $departure_time = date("d-m-Y", strtotime($value->more_transportation_drop_of_date ?? '' ));
                                                                    echo $departure_time;
                                                                ?>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                </div>
                            @endif
                            
                            @if($data->all_services_quotation == '1')
                                <div class="row">
                                    <div class="col-lg-12" style="padding: 12px;">
                                        <span><b>All Services Details</b></span>
                                    </div>
                                    
                                    <div class="col-lg-12">
                                        <table class="table dataTable">
                                            <thead class="theme-bg-clr">
                                                <tr>
                                                    <th style="text-align: center;">Type</th>
                                                    <th style="text-align: center;">Sale/Per Person</th>
                                                    <th style="text-align: center;">Pax</th>
                                                    <th style="text-align: center;">Total Sale Price</th>
                                                </tr>
                                            </thead>
                                            <tbody style="text-align: center;">
                                                @if(isset($data->all_costing_details) && $data->all_costing_details != null && $data->all_costing_details != '')
                                                    <?php $all_costing_details = json_decode($data->all_costing_details); // dd($all_costing_details);  ?>
                                                    <tr>
                                                        <td><b>Double</b></td>
                                                        <td>{{ $currency }} {{ $all_costing_details->double_grand_total_amount }}</td>
                                                        <td>{{ $all_costing_details->double_total_pax }}</td>
                                                        <td>{{ $currency }} {{ $all_costing_details->double_total_with_Pax }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Triple</b></td>
                                                        <td>{{ $currency }} {{ $all_costing_details->triple_grand_total_amount }}</td>
                                                        <td>{{ $all_costing_details->triple_total_pax }}</td>
                                                        <td>{{ $currency }} {{ $all_costing_details->triple_total_with_Pax }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Quad</b></td>
                                                        <td>{{ $currency }} {{ $all_costing_details->quad_grand_total_amount }}</td>
                                                        <td>{{ $all_costing_details->quad_total_pax }}</td>
                                                        <td>{{ $currency }} {{ $all_costing_details->quad_total_with_Pax }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Without Accomodation</b></td>
                                                        <td>{{ $currency }} {{ $all_costing_details->without_acc_sale_price_single }}</td>
                                                        <td>{{ $all_costing_details->without_acc_total_pax }}</td>
                                                        <td>{{ $currency }} {{ $all_costing_details->without_acc_total_with_Pax }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Child</b></td>
                                                        <td>{{ $currency }} {{ $all_costing_details->child_total_sale_price }}</td>
                                                        <td>{{ $all_costing_details->child_total_pax }}</td>
                                                        <td>{{ $currency }} {{ $all_costing_details->child_total_with_Pax }}</td>
                                                    </tr>
                                                    <tr>    
                                                        <td><b>Infant</b></td>
                                                        <td>{{ $currency }} {{ $all_costing_details->infant_total_sale_price }}</td>
                                                        <td>{{ $all_costing_details->infant_total_pax }}</td>
                                                        <td>{{ $currency }} {{ $all_costing_details->infant_total_with_Pax }}</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                            
                                        </table>
                                    </div>
                                </div>
                                <hr>
                            @endif
                            
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card" style="border-radius: 16px;border: solid black 0px;background-color: #8888ff;">
                        <div class="card-body" style="padding: 10px;">
                            
                            <div class="row" style="font-size: 12px;text-align: center;">
                                <div class="col-lg-6">
                                    <h5>Subtotal</h5>
                                    <span>{{ $currency }} {{ round($data->total_sale_price_all_Services ?? '',2) }}</span>
                                </div>
                                
                                <div class="col-lg-4 d-none">
                                    <h5>Tax</h5>
                                    <span>{{ $currency }} 0</span>
                                </div>
                                
                                <div class="col-lg-6">
                                    <h5>Total</h5>
                                    <span>{{ $currency }} {{ round($data->total_sale_price_all_Services ?? '',2) }}</span>
                                </div>
                                
                                <div class="col-lg-12">
                                    <p style="text-align: center;margin-top: 10px;">Monthly payment fo 26 currencies!</p>
                                </div>
                                
                            </div>
                            
                        </div>
                    </div>
                    
                    <h4>Services</h4>
                    
                    <div class="card" style="border-radius: 16px;border: solid black 0px;">
                        <div class="card-body" style="padding: 10px;">
                            
                            <div class="row">
                                @if($data->all_services_quotation != '1')
                                    @if($accomodation_final_total_sale_price_All > 0 || $data->all_services_quotation == '1')
                                        <div class="col-lg-12">
                                            <h5>Accomodation</h5>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <h6>Subtotal</h6>
                                                    <span>{{ $currency }} {{ round($accomodation_final_total_sale_price_All ?? '',2) }}</span>        
                                                </div>
                                                
                                                <div class="col-lg-4 d-none">
                                                    <h6>Tax</h6>
                                                    <span>{{ $currency }} 0</span>        
                                                </div>
                                                
                                                <div class="col-lg-6">
                                                    <h6>Total</h6>
                                                    <span>{{ $currency }} {{ round($accomodation_final_total_sale_price_All ?? '',2) }}</span>        
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                    @endif
                                    
                                    @if($flight_final_total_sale_price_All > 0 || $data->all_services_quotation == '1')
                                        <div class="col-lg-12">
                                            <h5>Flights</h5>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <h6>Subtotal</h6>
                                                    <span>{{ $currency }} {{ round($flight_final_total_sale_price_All ?? '',2) }}</span>        
                                                </div>
                                                
                                                <div class="col-lg-4 d-none">
                                                    <h6>Tax</h6>
                                                    <span>{{ $currency }} 0</span>        
                                                </div>
                                                
                                                <div class="col-lg-6">
                                                    <h6>Total</h6>
                                                    <span>{{ $currency }} {{ round($flight_final_total_sale_price_All ?? '',2) }}</span>        
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                    @endif
                                    
                                    @if($visa_final_total_sale_price_All > 0 || $data->all_services_quotation == '1')
                                        <div class="col-lg-12">
                                            <h5>Visa</h5>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <h6>Subtotal</h6>
                                                    <span>{{ $currency }} {{ round($visa_final_total_sale_price_All ?? '',2) }}</span>        
                                                </div>
                                                
                                                <div class="col-lg-4 d-none">
                                                    <h6>Tax</h6>
                                                    <span>{{ $currency }} 0</span>        
                                                </div>
                                                
                                                <div class="col-lg-6">
                                                    <h6>Total</h6>
                                                    <span>{{ $currency }} {{ round($visa_final_total_sale_price_All ?? '',2) }}</span>        
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                    @endif
                                    
                                    @if($transportation_final_total_sale_price_All > 0 || $data->all_services_quotation == '1')
                                        <div class="col-lg-12">
                                            <h5>Transfer</h5>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <h6>Subtotal</h6>
                                                    <span>{{ $currency }} {{ round($transportation_final_total_sale_price_All ?? '',2) }}</span>        
                                                </div>
                                                
                                                <div class="col-lg-4 d-none">
                                                    <h6>Tax</h6>
                                                    <span>{{ $currency }} 0</span>        
                                                </div>
                                                
                                                <div class="col-lg-6">
                                                    <h6>Total</h6>
                                                    <span>{{ $currency }} {{ round($transportation_final_total_sale_price_All ?? '',2) }}</span>        
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="col-lg-12">
                                        <h5>All Services</h5>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <h6>Subtotal</h6>
                                                <span>{{ $currency }} {{ round($data->total_sale_price_all_Services ?? '',2) }}</span>        
                                            </div>
                                            
                                            <div class="col-lg-4 d-none">
                                                <h6>Tax</h6>
                                                <span>{{ $currency }} 0</span>        
                                            </div>
                                            
                                            <div class="col-lg-6">
                                                <h6>Total</h6>
                                                <span>{{ $currency }} {{ round($data->total_sale_price_all_Services ?? '',2) }}</span>        
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            
        </section>
        
    </div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

@endsection

@section('scripts')

<script src='https://cdn.rawgit.com/naptha/tesseract.js/1.0.10/dist/tesseract.js'></script>

@endsection
