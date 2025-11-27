@extends('template/frontend/userdashboard/layout/default')
@section('content')
    
     <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    
    <?php $currency=Session::get('currency_symbol'); //dd($all_countries); ?>
    
    <style>
        .nav-link{
            color: #575757;
            font-size: 18px;
        }
    </style>

    <div class="content-wrapper">
        
        <section class="content" >
            <div class="container-fluid">
                <nav class="breadcrumb push">
                    <a class="breadcrumb-item" href="#">Dashboard</a> 
                    <span class="breadcrumb-item active">
                        Atol Report
                        @if($type == 'Weekly') 
                            Weekly
                        @elseif($type == 'Monthly')
                            Monthly
                        @elseif($type == 'Quarter')
                            Quarter
                        @elseif($type == 'Half_Yearly')
                            Half_Yearly
                        @elseif($type == 'Yearly')
                            Yearly
                        @else
                        @endif
                    </span>
                </nav>
            </div>
        </section>
        
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-12">
                        <h4>Atol Report Weekly</h4>
                        <div class="panel-body padding-bottom-none">
                            <div class="block-content block-content-full">
                                <div class="table-responsive">
                                    <div id="example_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                                   
                                        <table class="table nowrap example1 dataTable no-footer" id="example1" role="grid" aria-describedby="example_info">
                                                
                                            <thead class="theme-bg-clr">
                                                
                                                <tr role="row">
                                                    <th style="text-align: center;"></th>
                                                    <th style="text-align: center;"></th>
                                                    <th style="text-align: center;"></th>
                                                    <th style="text-align: center;"></th>
                                                    <th style="text-align: center;"></th>
                                                    <th style="text-align: center;"></th>
                                                    <th style="text-align: center;" id="th_FTCA"></th>
                                                    <th style="text-align: center;" id="th_FAFA"></th>
                                                    <th style="text-align: center;" id="th_FTAFA"></th>
                                                </tr>
                                                
                                                <tr role="row">
                                                    <th style="text-align: center;">Invoice No.</th>
                                                    <th style="text-align: center;">Customer Name</th>
                                                    <th style="text-align: center;">Customer</th>
                                                    <th style="text-align: center;">Flight Details</th>
                                                    <th style="text-align: center;">Airline Name</th>
                                                    <th style="text-align: center;">Pax Details</th>
                                                    <th style="text-align: center;">Cost Price</th>
                                                    <th style="text-align: center;">Atol Fee</th>
                                                    <th style="text-align: center;">Total Atol Fee</th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody style="text-align: center;">
                                                <?php
                                                    $total_cost_price_all_Services  = 0;
                                                    $total_price                    = 0;
                                                    $check_currrency                = [];
                                                    
                                                    $i                                  = 1;
                                                    $flights_total_cost_all             = 0;
                                                    $flights_total_atol_fee_all         = 0;
                                                    $flights_total_total_atol_fee_all   = 0;
                                                    
                                                    if(isset($addAtolFlightPackage) && $addAtolFlightPackage != null && $addAtolFlightPackage != ''){
                                                        $atol_id_Flight     = $addAtolFlightPackage->atol_id_Flight;
                                                        $atol_Fee_Flight    = $addAtolFlightPackage->atol_Fee_Flight;
                                                        $atol_Fee_Package   = $addAtolFlightPackage->atol_Fee_Package;
                                                    }else{
                                                        $atol_id_Flight     = 0;
                                                        $atol_Fee_Flight    = 0;
                                                        $atol_Fee_Package   = 0;
                                                    }
                                                ?>
                                                @foreach($flight_invoice as $value)
                                                    @if($value->quotation_Invoice != '1')
                                                        <?php
                                                            $flights_adult_seats    = 0;
                                                            $flights_child_seats    = 0;
                                                            $flights_infant_seats   = 0;
                                                            $flights_total_atol     = 0;
                                                            
                                                            $flights_details_E      = $value->flights_details; 
                                                            $flights_details        = json_decode($flights_details_E);
                                                            
                                                            $flights_Pax_details_E  = $value->flights_Pax_details;
                                                            $flights_Pax_details    = json_decode($flights_Pax_details_E);
                                                        ?>
                                                        @if(is_array($flights_details))
                                                            <tr role="row" class="odd">
                                                                <td>
                                                                    <b>{{ $value->generate_id }}</b>
                                                                    <a href="{{URL::to('invoice_Agent')}}/{{ $value->id }}">
                                                                        <img height="15px" width="15px" src="{{ asset('/public/invoice_icon.png') }}">
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    @if(isset($all_Users) && $all_Users != null && $all_Users != '')
                                                                        @foreach($all_Users as $all_Users_value)
                                                                            @if($value->customer_id == $all_Users_value->id)
                                                                                <b>{{ $all_Users_value->name }}</b>
                                                                                <?php
                                                                                    $currency = $all_Users_value->currency_symbol;
                                                                                    if(isset($all_Users_value->currency_value) && $all_Users_value->currency_value != null){
                                                                                        $exchange_currency              = base_currency_by_alhijaz($all_Users_value->currency_value,$value->total_cost_price_all_Services);
                                                                                        $total_cost_price_all_Services  = $exchange_currency[0]->exchange_price;
                                                                                        array_push($check_currrency,$exchange_currency);
                                                                                    }else{
                                                                                        $total_cost_price_all_Services = $value->total_cost_price_all_Services;
                                                                                    }
                                                                                ?>
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                </td>
                                                                <td>{{ $value->f_name }}</td>
                                                                <td>
                                                                    @foreach($flights_details as $val1)
                                                                        <p>
                                                                            {{ $val1->departure_airport_code }}
                                                                            <i class="fa-solid fa-arrow-right" style="color: #0acf97;"></i>
                                                                            {{ $val1->arrival_airport_code }}
                                                                        </p>
                                                                    @endforeach
                                                                </td>
                                                                <td>
                                                                    @foreach($flights_details as $val1)
                                                                        {{ $val1->other_Airline_Name2 }}<b>({{ $val1->departure_flight_number }})</b><br>
                                                                    @endforeach
                                                                </td>
                                                                <td>
                                                                    <div class="row" style="width: 250px;">
                                                                        @foreach($flights_Pax_details as $val2)
                                                                            <div class="col-xl-6">
                                                                                <div class="row">
                                                                                    @if($val2->flights_adult_seats != 0 && $val2->flights_adult_seats > 0)
                                                                                        <div class="col-xl-12">Adult : <b>{{ $currency }} {{ $val2->flights_total_cost_adult }}({{ $val2->flights_adult_seats }})</b></div>
                                                                                    @endif
                                                                                    @if($val2->flights_child_seats != 0 && $val2->flights_child_seats > 0)    
                                                                                        <div class="col-xl-12">Child : <b>{{ $currency }} {{ $val2->flights_total_cost_child }}({{ $val2->flights_child_seats }})</b></div>
                                                                                    @endif
                                                                                    @if($val2->flights_infant_seats != 0 && $val2->flights_infant_seats > 0)    
                                                                                        <div class="col-xl-12">Infant : <b>{{ $currency }} {{ $val2->flights_total_cost_infant }}({{ $val2->flights_infant_seats }})</b></div>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </td>
                                                                <td>{{ $currency }} {{ $total_cost_price_all_Services }}</td>
                                                                <td>
                                                                    @foreach($atol_detail as $val3)
                                                                        @if(isset($addAtolFlightPackage) && $addAtolFlightPackage != null && $addAtolFlightPackage != '')
                                                                            @if($val3->id == $addAtolFlightPackage->atol_id_Flight)
                                                                                Atol Number : <b>{{ $val3->atol_Number ?? '0' }}</b><br>
                                                                                Atol Fee    : <b>{{ $currency }} {{ $addAtolFlightPackage->atol_Fee_Flight }}</b><br>
                                                                                <b>(Flight Only)</b>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                        foreach($flights_Pax_details as $val2){
                                                                            $flights_adult_seats   = $flights_adult_seats + ($val2->flights_adult_seats * $atol_Fee_Flight);
                                                                            $flights_child_seats   = $flights_child_seats + ($val2->flights_child_seats * $atol_Fee_Flight);
                                                                            $flights_infant_seats  = $flights_infant_seats + ($val2->flights_infant_seats * $atol_Fee_Flight);
                                                                        }
                                                                        $flights_total_atol = $flights_adult_seats + $flights_child_seats + $flights_infant_seats;
                                                                    ?>
                                                                    {{ $currency }} {{ $flights_total_atol }}
                                                                </td>
                                                            </tr>
                                                            
                                                            <?php 
                                                                $flights_total_cost_all             = $flights_total_cost_all + $total_cost_price_all_Services;
                                                                $flights_total_atol_fee_all         = $flights_total_atol_fee_all + $atol_Fee_Flight;
                                                                $flights_total_total_atol_fee_all   = $flights_total_total_atol_fee_all + $flights_total_atol;
                                                            ?>
                                                        @endif
                                                    @endif
                                                @endforeach
                                                
                                                @foreach($package_Flights as $value2)
                                                    <tr role="row" class="odd">
                                                        <?php
                                                            $total_seats                        = $value2['total_adults'] + $value2['total_childs'] + $value2['total_infant'];
                                                            $flights_total_atol                 = $atol_Fee_Package * $total_seats;
                                                            $flights_total_cost_all             = $flights_total_cost_all + $value2['total_price'];
                                                            $flights_total_atol_fee_all         = $flights_total_atol_fee_all + $atol_Fee_Package;
                                                            $flights_total_total_atol_fee_all   = $flights_total_total_atol_fee_all + $flights_total_atol;
                                                        ?>
                                                        <td>
                                                            <b>{{ $value2['invoice_no'] }}</b>
                                                            <a href="{{URL::to('invoice_package')}}/{{ $value2['invoice_no'] }}/{{ $value2['booking_id'] }}/{{ $value2['package_id'] }}">
                                                                <img height="15px" width="15px" src="{{ asset('/public/invoice_icon.png') }}">
                                                            </a>
                                                        </td>
                                                        <td>
                                                            @if(isset($all_Users) && $all_Users != null && $all_Users != '')
                                                                @foreach($all_Users as $all_Users_value)
                                                                    @if($value2['customer_id'] == $all_Users_value->id)
                                                                        <b>{{ $all_Users_value->name }}</b>
                                                                        <?php
                                                                            $currency = $all_Users_value->currency_symbol;
                                                                            if(isset($value2['currency_value']) && $value2['currency_value'] != null){
                                                                                $exchange_currency  = base_currency_by_alhijaz($value2['currency_value'],$value2['total_price']);
                                                                                // dd($exchange_currency);
                                                                                $total_price        = $exchange_currency[0]->exchange_price;
                                                                                array_push($check_currrency,$exchange_currency);
                                                                            }else{
                                                                                $total_price = $value2['total_price'];
                                                                            }
                                                                        ?>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                        <td>{{ $value2['agent_name'] }}</td>
                                                        <td>
                                                            <p>
                                                                {{ $value2['departure'] ?? '' }}
                                                                <i class="fa-solid fa-arrow-right" style="color: #0acf97;"></i>
                                                                {{ $value2['arrival'] ?? '' }}
                                                            </p>
                                                        </td>
                                                        <td>
                                                            {{ $value2['airline_name'] ?? '' }}<b>({{ $value2['flight_no'] ?? '' }})</b><br>
                                                        </td>
                                                        <td>
                                                            <div class="row" style="width: 250px;">
                                                                @if($value2['total_adults'] > 0)
                                                                    <div class="col-xl-12">Adult    : <b>{{ $currency }} {{ $value2['adult_price'] }}({{ $value2['total_adults'] }})</b><br></div>
                                                                @endif
                                                                @if($value2['total_childs'] > 0)
                                                                    <div class="col-xl-12">Child    : <b>{{ $currency }} {{ $value2['child_price'] }}({{ $value2['total_childs'] }})</b><br></div>
                                                                @endif
                                                                @if($value2['total_infant'] > 0)
                                                                    <div class="col-xl-12">Infant   : <b>{{ $currency }} {{ $value2['infant_price'] }}({{ $value2['total_infant'] }})</b></div>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td>
                                                            {{ $currency }} {{ $total_price }}
                                                        </td>
                                                        <td>
                                                            @foreach($atol_detail as $val3_P)
                                                                @if(isset($addAtolFlightPackage) && $addAtolFlightPackage != null && $addAtolFlightPackage != '')
                                                                    @if($val3_P->id == $addAtolFlightPackage->atol_id_Package)
                                                                        Atol Number : <b>{{ $val3_P->atol_Number }}</b><br>
                                                                        Atol Fee    : <b>{{ $currency }} {{ $atol_Fee_Package }}</b><br>
                                                                        <b>(Package)</b>
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                        <td>{{ $currency }} {{ $atol_Fee_Package * $total_seats }}</td>
                                                        <?php $i++; ?>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        
                                        <input type="hidden" id="val_FTCA" value="{{ $flights_total_cost_all }}">
                                        <input type="hidden" id="val_FAFA" value="{{ $flights_total_atol_fee_all }}">
                                        <input type="hidden" id="val_FTAFA" value="{{ $flights_total_total_atol_fee_all }}">
                                        <input type="hidden" id="val_currency" value="{{ $currency }}">
                                        
                                        <?php // dd($check_currrency); ?>
                                        
                                    </div>
                                </div>
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
    
    <script>
        $(document).ready(function() {
            var val_currency = $('#val_currency').val();
            console.log('val_currency : '+val_currency);
            
            var val_FTCA    = $('#val_FTCA').val();
            val_FTCA        = parseFloat(val_FTCA);
            console.log('val_FTCA : '+val_FTCA);
            $('#th_FTCA').html(val_currency +' '+ val_FTCA);
            
            var val_FAFA    = $('#val_FAFA').val();
            val_FAFA        = parseFloat(val_FAFA);
            console.log('val_FAFA : '+val_FAFA);
            $('#th_FAFA').html(val_currency +' '+ val_FAFA);
            
            var val_FTAFA   = $('#val_FTAFA').val();
            val_FTAFA       = parseFloat(val_FTAFA);
            console.log('val_FTAFA : '+val_FTAFA);
            $('#th_FTAFA').html(val_currency +' '+ val_FTAFA);
        });
    </script>
    
@endsection
