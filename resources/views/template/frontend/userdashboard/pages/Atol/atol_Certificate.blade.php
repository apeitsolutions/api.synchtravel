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
                    <span class="breadcrumb-item active">Atol Certificate</span>
                </nav>
            </div>
        </section>
        
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-12">
                        
                        <div class="cs-invoice_in d-none">
                            <img src="{{ asset('/public/admin_package/frontend/images/letter-head.png') }}" alt="letterhead" style="width:100%;" >
                        </div>
                        
                        <h4>Atol Certificate</h4>
                        <div class="panel-body padding-bottom-none">
                            <div class="block-content block-content-full">
                                <div class="table-responsive">
                                    <div id="example_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                                   
                                        <table class="table nowrap example1 example_my1 dataTable no-footer" id="example1_11" role="grid" aria-describedby="example_info" style="width: 100%;">
                                            
                                            <thead class="theme-bg-clr">
                                                
                                                <tr role="row">
                                                    <th style="text-align: center;"></th>
                                                    <th style="text-align: center;"></th>
                                                    <th style="text-align: center;"></th>
                                                    <th style="text-align: center;"></th>
                                                    <th style="text-align: center;"></th>
                                                    <th style="text-align: center;" id="th_FTCA" class="d-none"></th>
                                                    <th style="text-align: center;" id=""></th>
                                                    <th style="text-align: center;" id="th_FTAFA"></th>
                                                </tr>
                                                
                                                <tr role="row">
                                                    <th style="text-align: center;">Customer Name</th>
                                                    <th style="text-align: center;">Certificate</th>
                                                    <th style="text-align: center;">Invoice No.</th>
                                                    <th style="text-align: center;">Customer</th>
                                                    <th style="text-align: center;">Flight Details</th>
                                                    <th style="text-align: center;" class="d-none">Cost Price</th>
                                                    <th style="text-align: center;">Atol Fee</th>
                                                    <th style="text-align: center;">Total Atol Fee</th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody style="text-align: center;">
                                                <?php
                                                    $i = 1;
                                                    $flights_total_cost_all             = 0;
                                                    $flights_total_atol_fee_all         = 0;
                                                    $flights_total_total_atol_fee_all   = 0;
                                                ?>
                                                @if(isset($flight_invoice) && $flight_invoice != null && $flight_invoice != '')
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
                                                                // if($value->id == 393){dd($flights_details);}
                                                                
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
                                                            @if(is_array($flights_details))
                                                                <tr role="row" class="odd">
                                                                    <td>
                                                                        @if(isset($all_Users) && $all_Users != null && $all_Users != '')
                                                                            @foreach($all_Users as $all_Users_value)
                                                                                @if($value->customer_id == $all_Users_value->id)
                                                                                    <b>{{ $all_Users_value->name }}</b>
                                                                                    <?php
                                                                                        $currency = $all_Users_value->currency_symbol;
                                                                                    ?>
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                    </td>
                                                                    <td><a href="{{URL::to('view_atol_certificate_Admin')}}/{{ $value->id }}" class="btn btn-primary">View Atol Certificate</a></td>
                                                                    <td>
                                                                        <b>{{ $value->generate_id }}</b>
                                                                        <a href="{{URL::to('invoice_Agent')}}/{{ $value->id }}">
                                                                            <img height="15px" width="15px" src="{{ asset('/public/invoice_icon.png') }}">
                                                                        </a>
                                                                    </td>
                                                                    <td>
                                                                        {{ $value->f_name }}
                                                                        <div class="row">
                                                                            @foreach($flights_Pax_details as $val2)
                                                                                <div class="col-xl-12">
                                                                                    @if($val2->flights_adult_seats != 0 && $val2->flights_adult_seats > 0)
                                                                                        <b>{{ $val2->flights_adult_seats }}</b> Adult,
                                                                                    @endif
                                                                                    @if($val2->flights_child_seats != 0 && $val2->flights_child_seats > 0)    
                                                                                        <b>{{ $val2->flights_child_seats }}</b> Child,
                                                                                    @endif
                                                                                    @if($val2->flights_infant_seats != 0 && $val2->flights_infant_seats > 0)    
                                                                                        <b>{{ $val2->flights_infant_seats }}</b> Infant,
                                                                                    @endif
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        @foreach($flights_details as $val1)
                                                                            <p>
                                                                                {{ $val1->departure_airport_code }}
                                                                                <i class="fa-solid fa-arrow-right" style="color: #0acf97;"></i>
                                                                                {{ $val1->arrival_airport_code }}
                                                                                <br>
                                                                                <b>{{ $val1->other_Airline_Name2 }}({{ $val1->departure_flight_number }})</b>
                                                                            </p>
                                                                        @endforeach
                                                                    </td>
                                                                    <td class="d-none">{{ $currency }} {{ $value->total_cost_price_all_Services }}</td>
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
                                                                        {{ $currency }} {{ $flights_total_atol ?? '0' }}
                                                                    </td>
                                                                </tr>
                                                                
                                                                <?php 
                                                                    $flights_total_cost_all             = $flights_total_cost_all + $value->total_cost_price_all_Services;
                                                                    $flights_total_atol_fee_all         = $flights_total_atol_fee_all + $atol_Fee_Flight;
                                                                    $flights_total_total_atol_fee_all   = $flights_total_total_atol_fee_all + $flights_total_atol;
                                                                ?>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                                
                                                @if(isset($package_Flights) && $package_Flights != null && $package_Flights != '')
                                                    @foreach($package_Flights as $value2)
                                                        <tr role="row" class="odd">
                                                            <?php
                                                                $total_seats                        = $value2['total_adults'] ?? '0' + $value2['total_childs'] ?? '0' + $value2['total_infant'] ?? '0';
                                                                $flights_total_atol                 = $atol_Fee_Package * $total_seats;
                                                                $flights_total_cost_all             = $flights_total_cost_all + $value2['total_price'];
                                                                $flights_total_atol_fee_all         = $flights_total_atol_fee_all + $atol_Fee_Package;
                                                                $flights_total_total_atol_fee_all   = $flights_total_total_atol_fee_all + $flights_total_atol;
                                                            ?>
                                                            <td>
                                                                @if(isset($all_Users) && $all_Users != null && $all_Users != '')
                                                                    @foreach($all_Users as $all_Users_value)
                                                                        @if($value2['customer_id'] == $all_Users_value->id)
                                                                            <b>{{ $all_Users_value->name }}</b>
                                                                            <?php
                                                                                $currency = $all_Users_value->currency_symbol;
                                                                            ?>
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </td>
                                                            <td><a href="{{URL::to('view_atol_certificate_tours')}}/{{ $value2['invoice_no'] }}/{{ $value2['booking_id'] }}/{{ $value2['package_id'] }}" class="btn btn-primary">View Atol Certificate</a></td>
                                                            <td>
                                                                <b>{{ $value2['invoice_no'] }}</b>
                                                                <a href="{{URL::to('invoice_package')}}/{{ $value2->invoice_no }}/{{ $value2->booking_id }}/{{ $value2->package_id }}">
                                                                    <img height="15px" width="15px" src="{{ asset('/public/invoice_icon.png') }}">
                                                                </a>
                                                            </td>
                                                            <td>
                                                                {{ $value2['agent_name'] ?? '' }}
                                                                <div class="row">
                                                                    <div class="col-xl-12">
                                                                        @if($value2['total_adults'] != 0 && $value2['total_adults'] > 0)
                                                                            <b>{{ $value2->total_adults }}</b> Adult,
                                                                        @endif
                                                                        @if($value2['total_childs'] != 0 && $value2['total_childs'] > 0)
                                                                            <b>{{ $value2->total_childs }}</b> Child,
                                                                        @endif
                                                                        @if($value2['total_infant'] != 0 && $value2['total_infant'] > 0)
                                                                            <b>{{ $value2->total_infant }}</b> Infant,
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p>
                                                                    {{ $value2['departure'] ?? '' }}
                                                                    <i class="fa-solid fa-arrow-right" style="color: #0acf97;"></i>
                                                                    {{ $value2['arrival'] ?? '' }}
                                                                    <br>
                                                                    <b>{{ $value2['airline_name'] ?? '' }}({{ $value2['flight_no'] ?? '' }})</b>
                                                                </p>
                                                            </td>
                                                            <td class="d-none">{{ $currency }} {{ $value2['total_price'] ?? '' }}</td>
                                                            <td>
                                                                @foreach($atol_detail as $val3_P)
                                                                    @if(isset($addAtolFlightPackage) && $addAtolFlightPackage != null && $addAtolFlightPackage != '')
                                                                        @if($val3_P->id == $addAtolFlightPackage->atol_id_Package)
                                                                            Atol Number : <b>{{ $val3_P->atol_Number }}</b><br>
                                                                            Atol Fee    : <b>{{ $currency }} {{ $atol_Fee_Package }}</b><br>
                                                                            <b>(Package)</b>
                                                                        @else
                                                                            Atol Fee : <b>{{ $currency }} 0</b><br>
                                                                            <b>(Package)</b>
                                                                        @endif
                                                                    @endif
                                                                @endforeach
                                                            </td>
                                                            <td>{{ $currency }} {{ $atol_Fee_Package * $total_seats }}</td>
                                                            
                                                            <?php $i++; ?>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                
                                                <input type="hidden" id="val_FTCA" value="{{ $flights_total_cost_all }}">
                                                <input type="hidden" id="val_FAFA" value="{{ $flights_total_atol_fee_all }}">
                                                <input type="hidden" id="val_FTAFA" value="{{ $flights_total_total_atol_fee_all }}">
                                                <input type="hidden" id="val_currency" value="{{ $currency }}">
                                            </tbody>
                                            
                                        </table>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="cs-invoice_in d-none">
                            <img src="{{ asset('/public/admin_package/frontend/images/footer2.png') }}" alt="letterhead" style="width:100%;" >
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
            
            var val_FTCA    = $('#val_FTCA').val();
            val_FTCA        = parseFloat(val_FTCA);
            $('#th_FTCA').html(val_currency +' '+ val_FTCA);
            
            // var val_FAFA    = $('#val_FAFA').val();
            // val_FAFA        = parseFloat(val_FAFA);
            // $('#th_FAFA').html(val_currency +' '+ val_FAFA);
            
            var val_FTAFA   = $('#val_FTAFA').val();
            val_FTAFA       = parseFloat(val_FTAFA);
            console.log('val_FTAFA : '+val_FTAFA);
            $('#th_FTAFA').html(val_currency +' '+ val_FTAFA);
            
            $('#example1_11').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                { extend: 'copy',text: 'Copy' },
                { extend: 'csv',text: 'CSV' },
                { extend: 'excel',text: 'Excel' },
                { extend: 'pdf',text: 'PDF' },
                {
                    extend: 'print',
                    text: 'Print',
                    exportOptions: {
                        header: true,
                        footer: true
                    },
                    customize: function( win ) {
                        var header = '<img src="{{ asset('/public/admin_package/frontend/images/letter-head.png') }}" alt="letterhead" style="width:100%;" >';
                        var footer = '<img src="{{ asset('/public/admin_package/frontend/images/footer2.png') }}" alt="letterhead" style="width:100%;" >';
                        $(win.document.body).prepend(header);
                        $(win.document.body).append(footer);
                    }
                },
            ]
        });
        });
        
    </script>
    
@endsection
