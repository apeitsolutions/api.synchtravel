@extends('template/frontend/userdashboard/layout/default')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Accounts</a></li>
                            <li class="breadcrumb-item active">Stats</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Stats</h4>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">Package Stats Chart</h4>
                        <div class="info-box">
                            <div id="piechart1"></div>
                            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                            <script type="text/javascript">
                                google.charts.load('current', {'packages':['corechart']});
                                google.charts.setOnLoadCallback(drawChart);
                                
                                function drawChart() {
                                    var data = google.visualization.arrayToDataTable([
                                        ['Task', 'Tours'],
                                        ['Total', <?php echo $total_Amount;  ?>],
                                        ['Recieved', <?php echo $recieved_Amount;  ?>],
                                        ['Outstandings', <?php echo $total_Amount - $recieved_Amount;  ?>],
                                    ]);
                                    var options = {'width':500, 'height':500};
                                    
                                    var chart = new google.visualization.PieChart(document.getElementById('piechart1'));
                                    chart.draw(data, options);
                                }
                            </script>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">Activity Stats Chart</h4>
                        <div class="info-box">
                            <div id="piechart2"></div>
                            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                            <script type="text/javascript">
                                google.charts.load('current', {'packages':['corechart']});
                                google.charts.setOnLoadCallback(drawChart);
                                
                                function drawChart() {
                                    var data = google.visualization.arrayToDataTable([
                                        ['Task', 'Stats'],
                                        ['Total', <?php echo $total_Amount_Activity;  ?>],
                                        ['Recieved', <?php echo $recieved_Amount_Activity;  ?>],
                                        ['Outstandings', <?php echo $total_Amount_Activity - $recieved_Amount_Activity;  ?>],
                                    ]);
                                    var options = {'width':500, 'height':500};
                                    
                                    var chart = new google.visualization.PieChart(document.getElementById('piechart2'));
                                    chart.draw(data, options);
                                }
                            </script>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Accounts</a></li>
                            <li class="breadcrumb-item active">Paybale Stats</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Paybale Stats</h4>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="info-box">
                            <div class="table-responsive">
                                <div class="row">
                                    <table class="table table-centered w-100 dt-responsive nowrap" id="example1">
                                        <thead class="table-light">
                                            <tr role="row">
                                                <th style="text-align: center;" class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;">No Of Pax</th>
                                                <th style="text-align: center;" class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;">Booked Pax</th>
                                                <th style="text-align: center;" class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;">Hotel Payable</th>
                                                <th style="text-align: center;" class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;">Flights Payable</th>
                                                <th style="text-align: center;" class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;">Transportation Payable</th>
                                                <th style="text-align: center;" class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;">Visa Payable</th>
                                                <th style="text-align: center;" class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;">Total Payable</th>
                                                <!--<th style="text-align: center;" class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;">Total Profit</th>-->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data1 as $value)
                                                <?php 
                                                        $acc_hotel_name = json_decode($value->accomodation_details);
                                                        $flight_Details = json_decode($value->flights_details);
                                                        $transportation_details = json_decode($value->transportation_details);
                                                        // $markup_details = json_decode($value->markup_details);
                                                ?>
                                                @if(isset($acc_hotel_name))
                                                    @foreach($acc_hotel_name as $accDetails)
                                                        <tr role="row" class="odd" style="text-align: center;">
                                                            @if(isset($currency_SymbolU))
                                                                @foreach($currency_SymbolU as $currency_SymbolUU)
                                                                    <!--No Of Pax-->
                                                                    <td>
                                                                        <?php echo $currency_SymbolUU->currency_symbol; ?>
                                                                        {{ $value->no_of_pax_days }}
                                                                    </td>
                                                                    <!--Booked Pax-->
                                                                    <td>
                                                                        <?php echo $currency_SymbolUU->currency_symbol; ?>
                                                                        {{ $accDetails->acc_pax }}
                                                                    </td>
                                                                    <!--Accomodation-->
                                                                    <td>
                                                                        <?php echo $currency_SymbolUU->currency_symbol; ?>
                                                                        {{ $accDetails->acc_total_amount }}
                                                                    </td>
                                                                    <!--Flights-->
                                                                    @if(isset($flight_Details))
                                                                        
                                                                        <td>
                                                                            <?php echo $currency_SymbolUU->currency_symbol; ?>
                                                                            {{ $flight_Details->flights_per_person_price }}
                                                                        </td>
                                                                        <!--Transportation-->
                                                                        @if(isset($transportation_details))
                                                                            <td>
                                                                                <?php echo $currency_SymbolUU->currency_symbol; ?>
                                                                                {{ $transportation_details->transportation_price_per_person }}
                                                                            </td>
                                                                            <!--Visa-->
                                                                            <td>
                                                                                <?php echo $currency_SymbolUU->currency_symbol; ?>
                                                                                {{ $value->double_grand_total_amount }}
                                                                            </td>
                                                                            <!--Total Payable-->
                                                                            <td>
                                                                                <?php echo $currency_SymbolUU->currency_symbol; ?>
                                                                                {{ $accDetails->acc_total_amount + $flight_Details->flights_per_person_price + $transportation_details->transportation_price_per_person + $value->double_grand_total_amount }}
                                                                            </td>
                                                                            <!--Total Profit-->
                                                                            <!--@if(isset($markup_details))    -->
                                                                            <!--    @foreach($markup_details as $value1)-->
                                                                            <!--        <td>-->
                                                                            <!--            <?php echo $currency_SymbolUU->currency_symbol; ?>-->
                                                                            <!--            {{ $value1->markup_price }}-->
                                                                            <!--        </td>-->
                                                                            <!--    @endforeach-->
                                                                            <!--@else-->
                                                                            <!--        <td>-->
                                                                            <!--            <?php echo $currency_SymbolUU->currency_symbol; ?>-->
                                                                            <!--        </td>-->
                                                                            <!--@endif-->
                                                                        @endif
                                                                    @endif
                                                                @endforeach
                                                            @endif  
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
        });
    </script>

@endsection
