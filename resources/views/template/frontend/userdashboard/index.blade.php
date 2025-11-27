@extends('template/frontend/userdashboard/layout/default')
@section('content')

    <style>
        .theme-bg-clr{
            background: rgb(1,44,127);
            background: linear-gradient(176deg, rgba(1,44,127,1) 0%, rgba(243,77,134,1) 100%);
        }
          
        .widget-flat .text-muted {
            --ct-text-opacity: 1;
            color: #ffffff!important;
        }
        
        #example_wrapper button.dt-button{
            color: #c7c7c7;
        }    
    </style>

    <?php $currency = Session::get('currency_symbol'); // dd('Stop'); ?>

    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                
                <div class="page-title-box">
                    <div class="page-title-right">
                        <form class="d-flex">
                            
                            <div class="row d-none" style="margin-right: 5px;">
                                <div class="col-lg-12" style="width: 206px;">
                                    <a href="{{ URL::to('agents_financial_stats') }}" href="#" class="btn theme-bg-clr" style="color: white;">My Financial Statement</a>
                                </div>
                            </div>
                            
                            <div class="row d-none" style="margin-right: 5px;">
                                <div class="col-lg-12">
                                    <a href="{{ URL::to('agents_stats') }}" class="btn theme-bg-clr" style="width: 130px;color: white;">Agents( {{ $total_Agents ?? '' }} )</a>
                                </div>
                            </div>
                            
                            <div class="row d-none" style="margin-right: 5px;">
                                <div class="col-lg-12">
                                    <button type="button" class="btn theme-bg-clr" style="width: 130px;color: white;" id="SUP_ID">Supplier( {{ $total_Suppliers ?? '' }} )</button>
                                </div>
                            </div>
                            
                            <div class="row" style="width: 213px;margin-bottom: 10px;">
                                <div class="col-lg-12">
                                    <label>Select Customer</label>
                                    <select class="form-control" id="select_CustomerS" onchange="customerS_Select_function()">
                                        <option value="-1">Select Customer</option>
                                        @if(isset($all_Customers) && $all_Customers !== null && $all_Customers !== '')
                                            @foreach($all_Customers as $all_Customers_value)
                                                <option value="{{ $all_Customers_value->id }}" attr-c="{{ $all_Customers_value->currency_symbol }}" attr-t="{{ $all_Customers_value->Auth_key }}">{{ $all_Customers_value->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                    <h4 class="page-title">Dashboard</h4>
                </div>
                
                <div class="page-title-box d-none">
                    <div class="page-title-right" style="margin-bottom : 5px;">
                        <form class="d-flex">
                            
                            <div class="row" style="margin-right: 5px;display:none">
                                <div class="col-lg-12">
                                    <button type="button" class="btn theme-bg-clr" style="width: 130px;color: white;" id="">Last 7 Days</button>
                                </div>
                            </div>
                            
                            <div class="row" style="margin-right: 5px;display:none;">
                                <div class="col-lg-12">
                                    <button type="button" class="btn theme-bg-clr" style="width: 130px;color: white;" id="">Last 30 Days</button>
                                </div>
                            </div>
                            
                            <div class="row" style="margin-right: 470px;display:none">
                                <div class="col-lg-12">
                                    <button type="button" class="btn theme-bg-clr" style="width: 130px;color: white;" id="">Last 365 Days</button>
                                </div>
                            </div>
                            
                            <div class="row" style="width: 213px;display:none">
                                <div class="col-lg-12">
                                    <select class="form-control" id="select_Days" onchange="Days_Select_function()">
                                        <option value="-1">Select Days</option>
                                        <option value="Last_7_Days">Last 7 Days</option>
                                        <option value="Last_30_Days">Last 30 Days</option>
                                        <option value="Last_365_Days">Last 365 Days</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="col-xl-12" style="display:none;" id="search_flight_loader">
                    <iframe src="https://embed.lottiefiles.com/animation/98195"></iframe>
                </div>
                
            </div>
        </div>
        
        <div class="row" style="display:none" id="SUP_Div">
            <input type="hidden" value="0" id="switch_SUP">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right" style="margin-right: 200px;">
                        <form class="d-flex">
                            <div class="row" style="margin-right: 5px;">
                                <div class="col-lg-12">
                                    <a href="{{ URL::to('view_hotel_suppliers') }}" class="btn theme-bg-clr" style="width: 130px;color: white;">Hotels( {{ $hotel_total_Suppliers ?? '' }} )</a>
                                </div>
                            </div>
                            
                            <div class="row" style="margin-right: 5px;">
                                <div class="col-lg-12">
                                    <a href="#" class="btn theme-bg-clr" style="width: 130px;color: white;">Transfer( 0 )</a>
                                </div>
                            </div>
                            
                            <div class="row" style="margin-right: 5px;">
                                <div class="col-lg-12">
                                    <a href="{{ URL::to('super_admin/viewsupplier') }}" class="btn theme-bg-clr" style="width: 130px;color: white;">Flights( {{ $Flights_total_Suppliers ?? '' }} )</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row d-none">
            <div class="col-xl-12">
                <div class="row">
                    
                    <div class="col-sm-2">
                        <div class="card widget-flat theme-bg-clr">
                            <div class="card-body" style="padding: 15px;">
                                <div class="float-end">
                                    <i class="mdi mdi-account-multiple widget-icon"></i>
                                </div>
                                <h6 class="text-muted fw-normal mt-0" style="font-size: 11px;">REVENUE</h6>
                                <h6 class="text-muted fw-normal mt-0" style="text-align: right;">{{ $currency }} {{ number_format($total_Revenue ?? '0') }}</h6>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="card widget-flat theme-bg-clr">
                            <div class="card-body" style="padding: 15px;">
                                <div class="float-end">
                                    <i class="mdi mdi-account-multiple widget-icon"></i>
                                </div>
                                <h6 class="text-muted fw-normal mt-0" style="font-size: 11px;">PROFIT</h6>
                                <h6 class="text-muted fw-normal mt-0" style="text-align: right;">{{ $currency }} {{ number_format($profit ?? '0') }}</h6>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-sm-2">
                        <div class="card widget-flat theme-bg-clr">
                            <div class="card-body" style="padding: 15px;">
                                <div class="float-end">
                                    <i class="mdi mdi-account-multiple widget-icon"></i>
                                </div>
                                <h6 class="text-muted fw-normal mt-0" style="font-size: 11px;">EXPENSE</h6>
                                <h6 class="text-muted fw-normal mt-0" style="text-align: right;">{{ $currency }} {{ number_format($total_cost_price_All ?? '0') }}</h6>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-sm-2">
                        <div class="card widget-flat theme-bg-clr">
                            <div class="card-body" style="padding: 15px;">
                                <div class="float-end">
                                    <i class="mdi mdi-account-multiple widget-icon"></i>
                                </div>
                                <h6 class="text-muted fw-normal mt-0" style="font-size: 11px;">EXPENSE OUTSTANDINGS</h6>
                                <h6 class="text-muted fw-normal mt-0" style="text-align: right;">{{ $currency }} {{ $total_Expense_Outstanding ?? '0' }}</h6>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-sm-2">
                        <div class="card widget-flat theme-bg-clr">
                            <div class="card-body" style="padding: 15px;">
                                <div class="float-end">
                                    <i class="mdi mdi-account-multiple widget-icon"></i>
                                </div>
                                <h6 class="text-muted fw-normal mt-0" style="font-size: 11px;">INCOME</h6>
                                <h6 class="text-muted fw-normal mt-0" style="text-align: right;">{{ $currency }} {{ $total_Income ?? '0' }}</h6>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-sm-2">
                        <div class="card widget-flat theme-bg-clr">
                            <div class="card-body" style="padding: 15px;">
                                <div class="float-end">
                                    <i class="mdi mdi-account-multiple widget-icon"></i>
                                </div>
                                <h6 class="text-muted fw-normal mt-0" style="font-size: 11px;">INCOME OUTSTANDINGS</h6>
                                <h6 class="text-muted fw-normal mt-0" style="text-align: right;">{{ $currency }} {{ $total_Income_Outstanding ?? '0' }}</h6>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 col-xl-3">
                <div class="card theme-bg-clr">
                    <div class="card-body">
                        <div class="row align-items-center">
                            
                            <div class="col-6 col-md-8">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="Campaign Sent">REVENUE</h5>
                                <h5 class="my-2 py-1" id="revenue_sale_id" style="color: white;">{{ $currency }} {{ number_format($separate_Total_Revenue ?? '0') }}</h5>
                                <p class="mb-0 text-muted d-none">
                                    <span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i> 3.27%</span>
                                </p>
                            </div>
                            
                            <div class="col-6 col-md-4">
                                <div class="text-end">
                                    <div id="campaign-sent-chart" data-colors="#727cf5"></div>
                                </div>
                            </div>
                            
                            <div class="col-12 d-none">
                                <p class="mb-0 text-muted" style="text-align: center;">
                                    <i class="mdi mdi-arrow-down-bold" style="font-size: 30px;" id="SR_Id"></i>
                                </p>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card theme-bg-clr">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-6 col-md-8">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="New Leads">PROFIT</h5>
                                <h5 class="my-2 py-1" id="revenue_profit_id" style="color: white;">{{ $currency }} {{ number_format($separate_profit_Total ?? '0') }}</h5>
                                <p class="mb-0 text-muted d-none">
                                    <span class="text-danger me-2"><i class="mdi mdi-arrow-down-bold"></i> 5.38%</span>
                                </p>
                            </div>
                            
                            <div class="col-6 col-md-4">
                                <div class="text-end">
                                    <div id="new-leads-chart" data-colors="#0acf97"></div>
                                </div>
                            </div>
                            
                            <div class="col-12 d-none">
                                <p class="mb-0 text-muted" style="text-align: center;">
                                    <i class="mdi mdi-arrow-down-bold" style="font-size: 30px;" id="SP_Id"></i>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card theme-bg-clr">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-6 col-md-8">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="Deals">EXPENSE</h5>
                                <h5 class="my-2 py-1" id="revenue_cost_id" style="color: white;">{{ $currency }} {{ number_format($separate_Total_Cost ?? '0') }}</h5>
                                <p class="mb-0 text-muted d-none">
                                    <span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i> 4.87%</span>
                                </p>
                            </div>
                            
                            <div class="col-6 col-md-4">
                                <div class="text-end">
                                    <div id="deals-chart" data-colors="#727cf5"></div>
                                </div>
                            </div>
                            
                            <div class="col-12 d-none">
                                <p class="mb-0 text-muted" style="text-align: center;">
                                    <i class="mdi mdi-arrow-down-bold" style="font-size: 30px;" id="SE_Id"></i>
                                </p>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card theme-bg-clr">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-6 col-md-8">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="Booked Revenue">INCOME</h5>
                                <h5 class="my-2 py-1" id="revenue_income_id" style="color: white;">{{ $currency }} {{ number_format($separate_Total_Revenue ?? '0') }}</h5>
                                <p class="mb-0 text-muted d-none">
                                    <span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i> 11.7%</span>
                                </p>
                            </div>
                            
                            <div class="col-6 col-md-4">
                                <div class="text-end">
                                    <div id="booked-revenue-chart" data-colors="#0acf97"></div>
                                </div>
                            </div>
                            
                            <div class="col-12 d-none">
                                <p class="mb-0 text-muted" style="text-align: center;">
                                    <i class="mdi mdi-arrow-down-bold" style="font-size: 30px;" id="SI_Id"></i>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row" id="SR_Div" style="border: 2px solid;display:none">
            <input type="hidden" value="0" id="switch_SR">
            <div class="col-md-6 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="Campaign Sent">PACKAGE REVENUE</h5>
                                <h5 class="my-2 py-1">{{ $currency }} {{ $separate_package_Revenue ?? '0' }}</h5>
                            </div>
                            <div class="col-6">
                                <div class="text-end">
                                    <div id="campaign-sent-chart" data-colors="#727cf5"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="New Leads">ACTIVITY REVENUE</h5>
                                <h5 class="my-2 py-1">{{ $currency }} {{ $separate_activity_Revenue ?? '0' }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="Deals">HOTEL REVENUE</h5>
                                <h5 class="my-2 py-1">{{ $currency }} {{ $separate_revenue_hotels ?? '0' }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="Booked Revenue">ACCOMODATION REVENUE</h5>
                                <h5 class="my-2 py-1">{{ $currency }} {{ $separate_Revenue_accomodation_Price ?? '0' }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="Deals">VISA REVENUE</h5>
                                <h5 class="my-2 py-1">{{ $currency }} {{ $separate_Revenue_visa_Price ?? '0' }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="Booked Revenue">FLIGHTS REVENUE</h5>
                                <h5 class="my-2 py-1">{{ $currency }} {{ $separate_Revenue_flight_Price ?? '0' }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="Booked Revenue">TRANSPORTATION REVENUE</h5>
                                <h5 class="my-2 py-1">{{ $currency }} {{ $separate_Revenue_transportation_Price ?? '0' }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row" id="SP_Div" style="border: 2px solid;display:none">
            <input type="hidden" value="0" id="switch_SP">
            <div class="col-md-6 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="Campaign Sent">PACKAGE PROFIT</h5>
                                <h5 class="my-2 py-1">{{ $currency }} {{ $separate_profit_package ?? '0' }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="New Leads">ACTIVITY PROFIT</h5>
                                <h5 class="my-2 py-1">{{ $currency }} {{ $separate_profit_activity ?? '0' }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="Deals">HOTEL PROFIT</h5>
                                <h5 class="my-2 py-1">{{ $currency }} {{ $separate_profit_hotel ?? '0' }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="Booked Revenue">ACCOMODATION PROFIT</h5>
                                <h5 class="my-2 py-1">{{ $currency }} {{ $separate_profit_accomodation ?? '0' }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="Deals">VISA PROFIT</h5>
                                <h5 class="my-2 py-1">{{ $currency }} 
                                    @if(isset($new_visa_Profit) && $new_visa_Profit != '' && $new_visa_Profit != '')
                                        <?php echo round($new_visa_Profit ?? '0',2); ?>
                                    @else
                                        <?php echo '0'; ?>
                                    @endif
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="Booked Revenue">FLIGHTS PROFIT</h5>
                                <h5 class="my-2 py-1">{{ $currency }} {{ $separate_profit_flight ?? '0' }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="Booked Revenue">TRANSPORTATION PROFIT</h5>
                                <h5 class="my-2 py-1">{{ $currency }} {{ $separate_profit_transportation ?? '0' }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row" id="SE_Div" style="border: 2px solid;display:none">
            <input type="hidden" value="0" id="switch_SE">
            <div class="col-md-6 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="Campaign Sent">PACKAGE EXPENSE</h5>
                                <h5 class="my-2 py-1">{{ $currency }} {{ $separate_package_cost_price ?? '0' }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="New Leads">ACTIVITY EXPENSE</h5>
                                <h5 class="my-2 py-1">{{ $currency }} {{ $separate_activity_cost_price ?? '0' }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="Deals">HOTEL EXPENSE</h5>
                                <h5 class="my-2 py-1">{{ $currency }} {{ $separate_revenue_hotels ?? '0' }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="Booked Revenue">ACCOMODATION EXPENSE</h5>
                                <h5 class="my-2 py-1">{{ $currency }} {{ $separate_Cost_accomodation_Price ?? '0' }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="Deals">VISA EXPENSE</h5>
                                <h5 class="my-2 py-1">{{ $currency }} {{ $separate_Cost_visa_Price ?? '0' }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="Booked Revenue">FLIGHTS EXPENSE</h5>
                                <h5 class="my-2 py-1">{{ $currency }} {{ $separate_Cost_flight_Price ?? '0' }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="Booked Revenue">TRANSPORTATION EXPENSE</h5>
                                <h5 class="my-2 py-1">{{ $currency }} {{ $separate_Cost_transportation_Price ?? '0' }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row" id="SI_Div" style="border: 2px solid;display:none">
            <input type="hidden" value="0" id="switch_SI">
            <div class="col-md-6 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="Campaign Sent">PACKAGE INCOME</h5>
                                <h5 class="my-2 py-1">{{ $currency }} {{ $separate_package_Revenue ?? '0' }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="New Leads">ACTIVITY INCOME</h5>
                                <h5 class="my-2 py-1">{{ $currency }} {{ $separate_activity_Revenue ?? '0' }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="Deals">HOTEL INCOME</h5>
                                <h5 class="my-2 py-1">{{ $currency }} {{ $separate_revenue_hotels ?? '0' }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="Booked Revenue">ACCOMODATION INCOME</h5>
                                <h5 class="my-2 py-1">{{ $currency }} {{ $separate_Revenue_accomodation_Price ?? '0' }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="Deals">VISA INCOME</h5>
                                <h5 class="my-2 py-1">{{ $currency }} {{ $separate_Revenue_visa_Price ?? '0' }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="Booked Revenue">FLIGHTS INCOME</h5>
                                <h5 class="my-2 py-1">{{ $currency }} {{ $separate_Revenue_flight_Price ?? '0' }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h5 class="text-muted fw-normal mt-0 text-truncate" title="Booked Revenue">TRANSPORTATION INCOME</h5>
                                <h5 class="my-2 py-1">{{ $currency }} {{ $separate_Revenue_transportation_Price ?? '0' }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-5">
                <div class="card" style="height: 435px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h4 class="header-title">Revenue Chart</h4>
                        </div>
                        <div id="project-overview-chart" class="apex-charts" data-colors="#ffbc00,#727cf5,#0acf97" style="height: 360px;"></div>
                        
                        <div class="row" style="text-align: center;display:none">
                            <div class="col-lg-4">
                                <input type="hidden" value="0" id="switch_LWCH">
                                <button class="btn theme-bg-clr" style="font-size: 13px;color: white;height: 35px;border: currentColor;" id="LWCH_ID">Last Week</button>
                            </div>
                            <div class="col-lg-4">
                                <input type="hidden" value="0" id="switch_LMCH">
                                <button class="btn theme-bg-clr" style="font-size: 13px;color: white;height: 35px;border: currentColor;" id="LMCH_ID">Last Month</button>
                            </div>
                            <div class="col-lg-4">
                                <input type="hidden" value="0" id="switch_LYCH">
                                <button class="btn theme-bg-clr" style="font-size: 13px;color: white;height: 35px;border: currentColor;" id="LYCH_ID">Last Year</button>
                            </div>
                        </div>
                        
                        <div class="row text-center mt-3" style="display:none" id="LWCD_Div">
                            <div class="col-sm-3">
                                <i class="mdi mdi-send widget-icon rounded-circle bg-light-lighten text-muted"></i>
                                <h6 class="fw-normal mt-3">
                                    <span>{{ $currency }} {{ $weekly_Earning1 ?? '0' }}</span>
                                </h6>
                                <p class="text-muted mb-0 mb-2"><i class="mdi mdi-checkbox-blank-circle text-warning"></i>REVENUE</p>
                            </div>
                            <div class="col-sm-3">
                                <i class="mdi mdi-flag-variant widget-icon rounded-circle bg-light-lighten text-muted"></i>
                                <h6 class="fw-normal mt-3">
                                    <span>{{ $currency }} {{ $weekly_Profit_Total ?? '0' }}</span>
                                </h6>
                                <p class="text-muted mb-0 mb-2"><i class="mdi mdi-checkbox-blank-circle text-primary"></i>PROFIT</p>
                            </div>
                            <div class="col-sm-3">
                                <i class="mdi mdi-marker-check widget-icon rounded-circle bg-light-lighten text-muted"></i>
                                <h6 class="fw-normal mt-3">
                                    <span>{{ $currency }} {{ $weekly_Cost_Total ?? '0' }}</span>
                                </h6>
                                <p class="text-muted mb-0 mb-2"><i class="mdi mdi-checkbox-blank-circle text-success"></i>EXPENSE</p>
                            </div>
                            <div class="col-sm-3">
                                <i class="mdi mdi-call-received widget-icon rounded-circle bg-light-lighten text-muted"></i>
                                <h6 class="fw-normal mt-3">
                                    <span>{{ $currency }} {{ $weekly_Earning ?? '0' }}</span>
                                </h6>
                                <p class="text-muted mb-0 mb-2"><i class="mdi mdi-checkbox-blank-circle text-danger"></i>INCOME</p>
                            </div>
                        </div>
                        
                        <div class="row text-center mt-3" style="display:none" id="LMCD_Div">
                            <div class="col-sm-3">
                                <i class="mdi mdi-send widget-icon rounded-circle bg-light-lighten text-muted"></i>
                                <h6 class="fw-normal mt-3">
                                    <span>{{ $currency }} {{ $monthly_Earning ?? '0' }}</span>
                                </h6>
                                <p class="text-muted mb-0 mb-2"><i class="mdi mdi-checkbox-blank-circle text-warning"></i>REVENUE</p>
                            </div>
                            <div class="col-sm-3">
                                <i class="mdi mdi-flag-variant widget-icon rounded-circle bg-light-lighten text-muted"></i>
                                <h6 class="fw-normal mt-3">
                                    <span>{{ $currency }} {{ $monthly_Profit_Total ?? '0' }}</span>
                                </h6>
                                <p class="text-muted mb-0 mb-2"><i class="mdi mdi-checkbox-blank-circle text-primary"></i>PROFIT</p>
                            </div>
                            <div class="col-sm-3">
                                <i class="mdi mdi-marker-check widget-icon rounded-circle bg-light-lighten text-muted"></i>
                                <h6 class="fw-normal mt-3">
                                    <span>{{ $currency }} {{ $monthly_Cost_Total ?? '0' }}</span>
                                </h6>
                                <p class="text-muted mb-0 mb-2"><i class="mdi mdi-checkbox-blank-circle text-success"></i>EXPENSE</p>
                            </div>
                            <div class="col-sm-3">
                                <i class="mdi mdi-call-received widget-icon rounded-circle bg-light-lighten text-muted"></i>
                                <h6 class="fw-normal mt-3">
                                    <span>{{ $currency }} {{ $monthly_Earning ?? '0' }}</span>
                                </h6>
                                <p class="text-muted mb-0 mb-2"><i class="mdi mdi-checkbox-blank-circle text-danger"></i>INCOME</p>
                            </div>
                        </div>
                        
                        <div class="row text-center mt-3" style="display:none" id="LYCD_Div">
                            <div class="col-sm-3">
                                <i class="mdi mdi-send widget-icon rounded-circle bg-light-lighten text-muted"></i>
                                <h6 class="fw-normal mt-3">
                                    <span>{{ $currency }} {{ $yearly_Earning ?? '0' }}</span>
                                </h6>
                                <p class="text-muted mb-0 mb-2"><i class="mdi mdi-checkbox-blank-circle text-warning"></i>REVENUE</p>
                            </div>
                            <div class="col-sm-3">
                                <i class="mdi mdi-flag-variant widget-icon rounded-circle bg-light-lighten text-muted"></i>
                                <h6 class="fw-normal mt-3">
                                    <span>{{ $currency }} {{ $yearly_Profit_Total ?? '0' }}</span>
                                </h6>
                                <p class="text-muted mb-0 mb-2"><i class="mdi mdi-checkbox-blank-circle text-primary"></i>PROFIT</p>
                            </div>
                            <div class="col-sm-3">
                                <i class="mdi mdi-marker-check widget-icon rounded-circle bg-light-lighten text-muted"></i>
                                <h6 class="fw-normal mt-3">
                                    <span>{{ $currency }} {{ $yearly_Cost_Total ?? '0' }}</span>
                                </h6>
                                <p class="text-muted mb-0 mb-2"><i class="mdi mdi-checkbox-blank-circle text-success"></i>EXPENSE</p>
                            </div>
                            <div class="col-sm-3">
                                <i class="mdi mdi-call-received widget-icon rounded-circle bg-light-lighten text-muted"></i>
                                <h6 class="fw-normal mt-3">
                                    <span>{{ $currency }} {{ $yearly_Earning ?? '0' }}</span>
                                </h6>
                                <p class="text-muted mb-0 mb-2"><i class="mdi mdi-checkbox-blank-circle text-danger"></i>INCOME</p>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card d-none">
                    <div class="card-body">
                        <div class="row d-flex justify-content-between align-items-center mb-2">
                            <div class="row">
                                <div class="col-lg-2">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <h4 class="header-title">Revenue</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-10" style="text-align: center;">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <input type="hidden" value="0" id="switch_LW">
                                            <button class="btn theme-bg-clr" style="font-size: 14px;color: white;height: 35px;border: currentColor;" id="LW_ID">Last Week</button>
                                        </div>
                                        <div class="col-lg-4">
                                            <input type="hidden" value="0" id="switch_LM">
                                            <button class="btn theme-bg-clr" style="font-size: 14px;color: white;height: 35px;border: currentColor;" id="LM_ID">Last Month</button>
                                        </div>
                                        <div class="col-lg-4">
                                            <input type="hidden" value="0" id="switch_LY">
                                            <button class="btn theme-bg-clr" style="font-size: 14px;color: white;height: 35px;border: currentColor;" id="LY_ID">Last Year</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                        <div class="chart-content-bg" id="LWR_Div" style="display:none">
                            <div class="row text-center">
                                <div class="col-sm-4">
                                    <p class="text-muted mb-0 mt-3">Packages</p>
                                    <h2 class="fw-normal mb-3">
                                        <small class="mdi mdi-checkbox-blank-circle text-primary align-middle me-1"></small>
                                        <span style="font-size: 20px;">
                                            <?php echo $currency; ?>
                                            <?php echo number_format($toTal_weekly_revenue_Package ?? '0'); ?>
                                        </span>
                                    </h2>
                                </div>
                                <div class="col-sm-4">
                                    <p class="text-muted mb-0 mt-3">Activities</p>
                                    <h2 class="fw-normal mb-3">
                                        <small class="mdi mdi-checkbox-blank-circle text-success align-middle me-1"></small>
                                        <span style="font-size: 20px;"><?php echo $currency; ?>{{ $toTal_weekly_revenue_Activity ?? '0' }}</span>
                                    </h2>
                                </div>
                                <div class="col-sm-4">
                                    <p class="text-muted mb-0 mt-3">Accomodation</p>
                                    <h2 class="fw-normal mb-3">
                                        <small class="mdi mdi-checkbox-blank-circle text-warning align-middle me-1"></small>
                                        <span style="font-size: 20px;">
                                            <?php echo $currency; ?>
                                            <?php echo number_format($toTal_weekly_revenue_Accomodation_Price ?? '0'); ?>
                                        </span>
                                    </h2>
                                </div>
                                <div class="col-sm-4">
                                    <p class="text-muted mb-0 mt-3">Visa</p>
                                    <h2 class="fw-normal mb-3">
                                        <small class="mdi mdi-checkbox-blank-circle text-danger align-middle me-1"></small>
                                        <span style="font-size: 20px;">
                                            <?php echo $currency; ?>
                                            <?php echo number_format($toTal_weekly_revenue_Visa_Price ?? '0'); ?>
                                        </span>
                                    </h2>
                                </div>
                                <div class="col-sm-4">
                                    <p class="text-muted mb-0 mt-3">Flight</p>
                                    <h2 class="fw-normal mb-3">
                                        <small class="mdi mdi-checkbox-blank-circle text-danger align-middle me-1"></small>
                                        <span style="font-size: 20px;">
                                            <?php echo $currency; ?>
                                            <?php echo number_format($toTal_weekly_revenue_Flight_Price ?? '0'); ?>
                                        </span>
                                    </h2>
                                </div>
                                <div class="col-sm-4">
                                    <p class="text-muted mb-0 mt-3">Transportation</p>
                                    <h2 class="fw-normal mb-3">
                                        <small class="mdi mdi-checkbox-blank-circle text-danger align-middle me-1"></small>
                                        <span style="font-size: 20px;">
                                            <?php echo $currency; ?>
                                            <?php echo number_format($toTal_weekly_revenue_Transportation_Price ?? '0'); ?>
                                        </span>
                                    </h2>
                                </div>
                            </div>
                        </div>
                        
                        <div class="chart-content-bg" id="LMR_Div" style="display:none">
                            <div class="row text-center">
                                <div class="col-sm-4">
                                    <p class="text-muted mb-0 mt-3">Packages</p>
                                    <h2 class="fw-normal mb-3">
                                        <small class="mdi mdi-checkbox-blank-circle text-primary align-middle me-1"></small>
                                        <span style="font-size: 20px;">
                                            <?php echo $currency; ?>
                                            <?php echo number_format($toTal_monthly_revenue_Package ?? '0'); ?>
                                        </span>
                                    </h2>
                                </div>
                                <div class="col-sm-4">
                                    <p class="text-muted mb-0 mt-3">Activities</p>
                                    <h2 class="fw-normal mb-3">
                                        <small class="mdi mdi-checkbox-blank-circle text-success align-middle me-1"></small>
                                        <span style="font-size: 20px;"><?php echo $currency; ?>{{ $toTal_monthly_revenue_Activity ?? '0' }}</span>
                                    </h2>
                                </div>
                                <div class="col-sm-4">
                                    <p class="text-muted mb-0 mt-3">Accomodation</p>
                                    <h2 class="fw-normal mb-3">
                                        <small class="mdi mdi-checkbox-blank-circle text-warning align-middle me-1"></small>
                                        <span style="font-size: 20px;">
                                            <?php echo $currency; ?>
                                            <?php echo number_format($toTal_monthly_revenue_Accomodation_Price ?? '0'); ?>
                                        </span>
                                    </h2>
                                </div>
                                <div class="col-sm-4">
                                    <p class="text-muted mb-0 mt-3">Visa</p>
                                    <h2 class="fw-normal mb-3">
                                        <small class="mdi mdi-checkbox-blank-circle text-danger align-middle me-1"></small>
                                        <span style="font-size: 20px;">
                                            <?php echo $currency; ?>
                                            <?php echo number_format($toTal_monthly_revenue_Visa_Price ?? '0'); ?>
                                        </span>
                                    </h2>
                                </div>
                                <div class="col-sm-4">
                                    <p class="text-muted mb-0 mt-3">Flight</p>
                                    <h2 class="fw-normal mb-3">
                                        <small class="mdi mdi-checkbox-blank-circle text-danger align-middle me-1"></small>
                                        <span style="font-size: 20px;">
                                            <?php echo $currency; ?>
                                            <?php echo number_format($toTal_monthly_revenue_Flight_Price ?? '0'); ?>
                                        </span>
                                    </h2>
                                </div>
                                <div class="col-sm-4">
                                    <p class="text-muted mb-0 mt-3">Transportation</p>
                                    <h2 class="fw-normal mb-3">
                                        <small class="mdi mdi-checkbox-blank-circle text-danger align-middle me-1"></small>
                                        <span style="font-size: 20px;">
                                            <?php echo $currency; ?>
                                            <?php echo number_format($toTal_monthly_revenue_Transportation_Price ?? '0'); ?>
                                        </span>
                                    </h2>
                                </div>
                            </div>
                        </div>
                        
                        <div class="chart-content-bg" id="LYR_Div" style="display:none">
                            <div class="row text-center">
                                <div class="col-sm-4">
                                    <p class="text-muted mb-0 mt-3">Packages</p>
                                    <h2 class="fw-normal mb-3">
                                        <small class="mdi mdi-checkbox-blank-circle text-primary align-middle me-1"></small>
                                        <span style="font-size: 20px;">
                                            <?php echo $currency; ?>
                                            <?php echo number_format($toTal_yearly_revenue_Package ?? '0'); ?>
                                        </span>
                                    </h2>
                                </div>
                                <div class="col-sm-4">
                                    <p class="text-muted mb-0 mt-3">Activities</p>
                                    <h2 class="fw-normal mb-3">
                                        <small class="mdi mdi-checkbox-blank-circle text-success align-middle me-1"></small>
                                        <span style="font-size: 20px;"><?php echo $currency; ?>{{ $toTal_yearly_revenue_Activity ?? '0' }}</span>
                                    </h2>
                                </div>
                                <div class="col-sm-4">
                                    <p class="text-muted mb-0 mt-3">Accomodation</p>
                                    <h2 class="fw-normal mb-3">
                                        <small class="mdi mdi-checkbox-blank-circle text-warning align-middle me-1"></small>
                                        <span style="font-size: 20px;">
                                            <?php echo $currency; ?>
                                            <?php echo number_format($toTal_yearly_revenue_Accomodation_Price ?? '0'); ?>
                                        </span>
                                    </h2>
                                </div>
                                <div class="col-sm-4">
                                    <p class="text-muted mb-0 mt-3">Visa</p>
                                    <h2 class="fw-normal mb-3">
                                        <small class="mdi mdi-checkbox-blank-circle text-danger align-middle me-1"></small>
                                        <span style="font-size: 20px;">
                                            <?php echo $currency; ?>
                                            <?php echo number_format($toTal_yearly_revenue_Visa_Price ?? '0'); ?>
                                        </span>
                                    </h2>
                                </div>
                                <div class="col-sm-4">
                                    <p class="text-muted mb-0 mt-3">Flight</p>
                                    <h2 class="fw-normal mb-3">
                                        <small class="mdi mdi-checkbox-blank-circle text-danger align-middle me-1"></small>
                                        <span style="font-size: 20px;">
                                            <?php echo $currency; ?>
                                            <?php echo number_format($toTal_yearly_revenue_Flight_Price ?? '0'); ?>
                                        </span>
                                    </h2>
                                </div>
                                <div class="col-sm-4">
                                    <p class="text-muted mb-0 mt-3">Transportation</p>
                                    <h2 class="fw-normal mb-3">
                                        <small class="mdi mdi-checkbox-blank-circle text-danger align-middle me-1"></small>
                                        <span style="font-size: 20px;">
                                            <?php echo $currency; ?>
                                            <?php echo number_format($toTal_monthly_revenue_Transportation_Price ?? '0'); ?>
                                        </span>
                                    </h2>
                                </div>
                            </div>
                        </div>

                        <div class="dash-item-overlay" dir="ltr" id="WER_Div" style="display:none">
                            <h5>Earning Weekly: <?php echo $currency. ' ' .number_format($weekly_Earning ?? '0'); ?></h5>
                        </div>
                        
                        <div class="dash-item-overlay" dir="ltr" id="MER_Div" style="display:none">
                            <h5>Earning Monthly: <?php echo $currency. ' ' .number_format($monthly_Earning ?? '0'); ?></h5>
                        </div>
                        
                        <div class="dash-item-overlay" dir="ltr" id="YER_Div" style="display:none">
                            <h5>Earning Yearly: <?php echo $currency. ' ' .number_format($yearly_Earning ?? '0'); ?></h5>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <div dir="ltr">
                            <div id="revenue-chart" class="apex-charts mt-3" data-colors="#727cf5,#0acf97"></div>
                        </div>
                    </div>
                </div>    
            </div>
        </div>
        
        <div class="row">
            <div class="col-xl-5 col-lg-6">
                <div class="row">
                    
                    <div class="col-sm-6">
                        <div class="card widget-flat theme-bg-clr">
                            <div class="card-body" style="padding: 15px;">
                                <div class="float-end">
                                    <i class="mdi mdi-account-multiple widget-icon"></i>
                                </div>
                                <h5 class="text-muted fw-normal mt-0" title="Number of Packages">Packages</h5>
                                <h3 class="mt-3 mb-3 packages_detail" id="p1" data-bs-toggle="modal" data-bs-target="#packages_detail">
                                    {{ $packages_tour ?? '0' }}
                                </h3>
                                <p class="mb-0 text-muted">
                                    <span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i>{{ $no_of_pax_days ?? '0' }}</span>
                                    <span class="me-2 booked_tour" data-bs-toggle="modal" data-bs-target="#booked_tour" style="float: right;color: #98a6ad;">{{ (float)$booked_tourA ?? '0' + (float)$booked_tourC ?? '0' }}</span>
                                </p>
                                <p class="mb-0 text-muted">
                                    <span class="me-2">Seats</span>
                                    <span class="me-2"style="float: right;color: #98a6ad;">Booked</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="card widget-flat theme-bg-clr">
                            <div class="card-body" style="padding: 15px;">
                                <div class="float-end">
                                    <i class="mdi mdi-cart-plus widget-icon"></i>
                                </div>
                                <h5 class="text-muted fw-normal mt-0" title="Average Revenue">Revenue</h5>
                                <h3 class="mt-3 mb-3">
                                    <?php echo $currency; ?>
                                    <?php echo number_format($toTal ?? '0'); ?>
                                </h3>
                                <p class="mb-0 text-muted">
                                    <span  class="text-nowrap text-success me-2">
                                        <i class="mdi mdi-arrow-down-bold"></i>
                                        <?php echo $currency; ?>
                                        <?php echo number_format($recieved ?? '0'); ?>
                                    </span>
                                    <span class="me-2" style="float: right;color: #98a6ad;">
                                        <?php echo $currency; ?>
                                        <?php echo number_format($outStandings ?? '0'); ?>
                                    </span>
                                </p>
                                <p class="mb-0 text-muted">
                                    <span class="me-2">Recieved</span>
                                    <span class="me-2"style="float: right;color: #98a6ad;">Out Standings</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="card widget-flat theme-bg-clr">
                            <div class="card-body">
                                <div class="float-end">
                                    <i class="mdi mdi-cart-plus widget-icon"></i>
                                </div>
                                <h5 class="text-muted fw-normal mt-0" title="Number of Orders">Activities</h5>
                                <h3 class="mt-3 mb-3 activity_detail" id="p1" data-bs-toggle="modal" data-bs-target="#activity_detail">{{ $activities_count ?? '0' }}</h3>
                                <p class="mb-0 text-muted">
                                    <span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i>{{ $activities_no_of_pax_days ?? '' }}</span>
                                    <span class="me-2 booked_package" data-bs-toggle="modal" data-bs-target="#booked_package" style="float: right;color: #98a6ad;">{{ $booked_activitiesA ?? '0' + $booked_activitiesC ?? '0' }}</span>
                                </p>
                                <p class="mb-0 text-muted">
                                    <span class="me-2">Seats</span>
                                    <span class="me-2"style="float: right;color: #98a6ad;">Booked</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="card widget-flat theme-bg-clr">
                            <div class="card-body">
                                <div class="float-end">
                                    <i class="mdi mdi-pulse widget-icon"></i>
                                </div>
                                <h5 class="text-muted fw-normal mt-0" title="Growth">Revenue</h5>
                                <h3 class="mt-3 mb-3">
                                    <?php echo $currency; ?>
                                    <?php echo  number_format($toTal_activities ?? '0'); ?></h3>
                                <p class="mb-0 text-muted">
                                    <span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i>
                                        <?php echo $currency; ?>
                                        <?php echo  number_format($recieved_activities ?? '0'); ?>
                                    </span>
                                    <span class="me-2" style="float: right;color: #98a6ad;">
                                        <?php echo $currency; ?>
                                        <?php echo number_format($activities_outStandings ?? '0'); ?>
                                    </span>
                                </p>
                                <p class="mb-0 text-muted" style="font-size: 11px;">
                                    <span class="me-2">Recieved</span>
                                    <span class="me-2"style="float: right;color: #98a6ad;">Out Standings</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-7 col-lg-6">
                <div class="card card-h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h4 class="header-title">Package Bookings</h4>
                        </div>

                        <div dir="ltr">
                            <div id="high-performing-product" class="apex-charts" data-colors="#727cf5,#e3eaef"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    
                    <div class="col-xl-4">
                        <div class="card" style="background-color: #f2f3f4;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h4 class="header-title">Latest Supplier Details</h4>
                                </div>
        
                                <div class="table-responsive">
                                    <table class="display nowrap table table-centered w-100 dt-responsive" id="example_Supplier" style="width:100%;">
                                        
                                        <thead>
                                            <tr>
                                                <th style="text-align: center;"><h5 class="font-14 my-1 fw-normal">Name</h5></th>
                                                <th style="text-align: center;"><h5 class="font-14 my-1 fw-normal">Bookings</h5></th>
                                                <th style="text-align: center;"><h5 class="font-14 my-1 fw-normal">Revenue</h5></th>
                                            </tr>
                                        </thead>
                                        
                                        <?php $supplier_detail = $supplier_final_detail; ?>
                                        <tbody style="text-align: center;">
                                            @if(isset($supplier_detail) && $supplier_detail != null && $supplier_detail != '')
                                                @foreach($supplier_detail as $supplier_detailS)
                                                    <tr>
                                                        <td><h5 class="font-14 my-1 fw-normal">{{ $supplier_detailS->supplier_name ?? '' }}</h5></td>
                                                        <td><h5 class="font-14 my-1 fw-normal">{{ $supplier_detailS->supplier_bookings_count ?? '0' }}</h5></td>
                                                        <td><h5 class="font-14 my-1 fw-normal"><?php echo $currency;  ?> {{ $supplier_detailS->supplier_balance ?? '0' }}</h5></td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-4">
                        <div class="card" style="background-color: #f2f3f4;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h4 class="header-title">Latest Agent Details</h4>
                                </div>
        
                                <div class="table-responsive">
                                    <table class="display nowrap table table-centered w-100 dt-responsive" id="example_Agent" style="width:100%;">
                                        
                                        <thead>
                                            <tr>
                                                <th style="text-align: center;"><h5 class="font-14 my-1 fw-normal">Name</h5></th>
                                                <th style="text-align: center;"><h5 class="font-14 my-1 fw-normal">Bookings</h5></th>
                                                <th style="text-align: center;"><h5 class="font-14 my-1 fw-normal">Revenue</h5></th>
                                            </tr>
                                        </thead>
                                        <?php $agent_detail_array = $agent_final_detail; // dd($agent_final_detail); ?>
                                        <tbody style="text-align: center;">
                                            @if(isset($agent_detail_array) && $agent_detail_array != null && $agent_detail_array != '')
                                                @foreach($agent_detail_array as $agent_detail_arrayS)
                                                    <tr>
                                                        <td><h5 class="font-14 my-1 fw-normal">{{ $agent_detail_arrayS->company_name ?? '' }}</h5></td>
                                                        <td><h5 class="font-14 my-1 fw-normal">{{ $agent_detail_arrayS->agent_total_bookings_count ?? '' }}</h5></td>
                                                        <td><h5 class="font-14 my-1 fw-normal"><?php echo $currency;  ?> {{ $agent_detail_arrayS->balance ?? '0' }}</h5></td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-4">
                        <div class="card" style="background-color: #f2f3f4;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h4 class="header-title">Latest Customer Details</h4>
                                </div>
        
                                <div class="table-responsive">
                                    <table class="display nowrap table table-centered w-100 dt-responsive" id="example_Customer" style="width:100%;">
                                        
                                        <thead>
                                            <tr>
                                                <th style="text-align: center;"><h5 class="font-14 my-1 fw-normal">Name</h5></th>
                                                <th style="text-align: center;"><h5 class="font-14 my-1 fw-normal">Bookings</h5></th>
                                                <th style="text-align: center;"><h5 class="font-14 my-1 fw-normal">Revenue</h5></th>
                                            </tr>
                                        </thead>
                                        <?php $customer_detail_array = $customer_final_detail; ?>
                                        <tbody style="text-align: center;">
                                            @if(isset($customer_detail_array) && $customer_detail_array != null && $customer_detail_array != '')
                                                @foreach($customer_detail_array as $customer_detail_arrayS)
                                                    <tr>
                                                        <td><h5 class="font-14 my-1 fw-normal">{{ $customer_detail_arrayS->name ?? '' }}</h5></td>
                                                        <td><h5 class="font-14 my-1 fw-normal">{{ $customer_detail_arrayS->customer_total_bookings_count ?? '' }}</h5></td>
                                                        <td><h5 class="font-14 my-1 fw-normal"><?php echo $currency;  ?> {{ $customer_detail_arrayS->balance ?? '0' }}</h5></td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    
        <div class="row">
            <div class="col-xl-9 col-lg-12 order-lg-2 order-xl-1">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h4 class="header-title">Latest Packages and Activities</h4>
                            
                        </div>

                        <div class="table-responsive">
                            <table class="table table-centered w-100 dt-responsive nowrap" id="example">
                                
                                <thead>
                                    <tr>
                                        <th>
                                            <h5 class="font-14 my-1 fw-normal">Package Name</h5>
                                        </th>
                                        <th>
                                            <h5 class="font-14 my-1 fw-normal">Package Type</h5>
                                        </th>
                                        <th>
                                            <h5 class="font-14 my-1 fw-normal">Price</h5>
                                        </th>
                                        <th>
                                            <h5 class="font-14 my-1 fw-normal">Adults</h5>
                                        </th>
                                        <th>
                                            <h5 class="font-14 my-1 fw-normal">Total Price</h5>
                                        </th>
                                    </tr>
                                </thead>
                        
                                <tbody>
                                    @if(isset($latest_packages) && $latest_packages != null && $latest_packages != '')
                                        @foreach($latest_packages as $latest_packages)
                                        <tr>
                                            <td>
                                                <h5 class="font-14 my-1 fw-normal">{{ $latest_packages->tour_name ?? '' }}</h5>
                                                <span class="text-muted font-13">{{ $latest_packages->passenger_name ?? '' }}</span>
                                            </td>
                                            <td>
                                                <h5 class="font-14 my-1 fw-normal">{{ $latest_packages->pakage_type ?? '' }}</h5>
                                            </td>
                                            <td>
                                                <h5 class="font-14 my-1 fw-normal">
                                                    <?php echo $currency;  ?> {{ $latest_packages->sigle_price ?? '0' }}
                                                </h5>
                                            </td>
                                            <td>
                                                <h5 class="font-14 my-1 fw-normal">{{ $latest_packages->adults ?? '0' }}</h5>
                                            </td>
                                            <td>
                                                <h5 class="font-14 my-1 fw-normal">
                                                    <?php echo $currency; ?> {{ $latest_packages->price ?? '0' }}
                                                </h5>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 order-lg-1">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="header-title">Total Booking</h4>
                        </div>

                        <div id="average-sales" class="apex-charts mb-4 mt-3" data-colors="#727cf5,#0acf97,#fa5c7c,#ffbc00"></div>
                       
                        <div class="chart-widget-list">
                            <p>
                                <i class="mdi mdi-square text-primary"></i> Package
                                <span class="float-end"><?php echo $currency;  ?>{{ round($toTal ?? '0',2) }}</span>
                            </p>
                            <p>
                                <i class="mdi mdi-square text-success"></i> Activity
                                <span class="float-end"><?php echo $currency; ?>{{ round($toTal_activities ?? '0',2) }}</span>
                            </p>
                            <p class="">
                                <i class="mdi mdi-square text-danger"></i> Hotel
                                <span class="float-end"><?php echo $currency; ?>0</span>
                            </p>
                            <p class="">
                                <i class="mdi mdi-square text-warning"></i> Transfer
                                <span class="float-end"><?php echo $currency; ?>0</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<div id="packages_detail" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-modal="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="margin-right: 24%;">
        <div class="modal-content" style="width: 125%;">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Packages</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <div class="row">
                        <table class="table table-centered w-100 dt-responsive nowrap" id="example1">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>No of Pax</th>
                                    <th>Location</th>
                                    <th>Author</th>
                                    <th>Booking Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $tours)
                                    <?php
                                        $id           = $tours['id'];
                                        $book_status  = $tours['book_status'];
                                        $tour_publish = $tours['tour_publish'];
                                    ?>
                                    <tr>
                                        <td><?php echo $id; ?></td>
                                        <td><?php echo $tours['title']; ?></td>
                                        <td>
                                            @if(isset($tours['no_of_pax_days']))
                                                <?php echo $tours['no_of_pax_days']; ?>
                                            @else
                                                <?php echo '0' ?>
                                            @endif
                                        </td>

                                        <td><?php echo $tours['tour_location']; ?></td>
                                        <td><?php echo $tours['tour_author']; ?></td>                                                
                                        @if($book_status)
                                            <td>
                                                <span class="badge bg-success" style="font-size: 15px">Tour Booked</span>
                                                <span class="badge bg-info" style="font-size: 15px"  onclick="booked_tour_span({{ $id }})" id="booked_tour_span_{{ $id }}" data-id="{{$id}}">View Booked Tours</span>
                                            </td>
                                        @else
                                            <td><span class="badge btn-danger" style="font-size: 15px">Not Booked yet</span></td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="booked_tour" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-modal="true" style="display: none;">
    <div class="modal-dialog modal-lg"  style="margin-right: 25%;">
        <div class="modal-content" style="width: 130%;">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Booked Packages</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <div class="row">
                        <table class="table table-centered w-100 dt-responsive nowrap" id="example2">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Booking ID</th>
                                    <th >Tour ID</th>
                                    <th>Customer Name</th>
                                    <th>Package Title</th>
                                    <th>Adults</th>
                                    <th>Childs</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data1 as $value)
                                    <tr>
                                        <td class="P_Id">{{ $value->id ?? '' }}</td>
                                        <td>{{ $value->booking_id ?? '' }}</td>
                                        <td class="sorting_1 tour_Id" id="tour_Id">{{ $value->tour_id ?? '' }}</td>
                                        <td>{{ $value->name ?? '' }} {{ $value->lname ?? '' }}</td>
                                        <td>{{ $value->tour_name ?? '0' }}</td>
                                        <td>{{ $value->adults ?? '0' }}</td>
                                        <td>{{ $value->childs ?? '0' }}</td>
                                        <td>{{ $value->price ?? '0' }}</td>
                                        @if($value->confirm == 1)
                                          <td><span class="badge bg-success" style="font-size: 15px">Confirmed</span></td>
                                        @else
                                          <td><span class="badge btn-danger" style="font-size: 15px">Tentative</span></td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>

<div id="activity_detail" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-modal="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="margin-right: 24%;">
        <div class="modal-content" style="width: 125%;">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Activities</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <div class="row">
                        <table class="table table-centered w-100 dt-responsive nowrap" id="example3" style="text-align:center;">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Maximum No of Pax</th>
                                    <th>Location</th>
                                    <th>Author</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($new_activites as $new_activities)
                                    <?php
                                        $id           = $new_activities->id ?? '';
                                        $tour_publish = $new_activities->publish_status ?? '';
                                    ?>
                                    <tr>
                                        <td><?php echo $id; ?></td>
                                        <td><?php echo $new_activities->title ?? ''; ?></td>
                                        <td>
                                            @if(isset($new_activities->max_people))
                                                <?php echo $new_activities->max_people; ?>
                                            @else
                                                <?php echo '0' ?>
                                            @endif
                                        </td>

                                        <td><?php echo $new_activities->location ?? ''; ?></td>
                                        <td><?php echo $new_activities->package_author ?? ''; ?></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>

<div id="booked_package" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-modal="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="margin-right: 25%;">
        <div class="modal-content" style="width: 130%;">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Booked Activities</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <div class="row">
                        <table class="table table-centered w-100 dt-responsive nowrap" id="example4">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Booking ID</th>
                                    <th>Activity ID</th>
                                    <th>Customer Name</th>
                                    <th>Activity Title</th>
                                    <th>Adults</th>
                                    <th>Childs</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data3 as $value)
                                    <tr>
                                        <td class="P_Id">{{ $value->id ?? '' }}</td>
                                        <td>{{ $value->booking_id ?? '' }}</td>
                                        <td class="sorting_1 tour_Id" id="tour_Id">{{ $value->tour_id ?? '' }}</td>
                                        <td>{{ $value->name ?? '' }} {{ $value->lname ?? '' }}</td>
                                        <td>{{ $value->tour_name ?? '' }}</td>
                                        <td>{{ $value->adults ?? '0' }}</td>
                                        <td>{{ $value->childs ?? '0' }}</td>
                                        <td>{{ $value->price ?? '0' }}</td>
                                        @if($value->confirm == 1)
                                          <td><span class="badge bg-success" style="font-size: 15px">Confirmed</span></td>
                                        @else
                                          <td><span class="badge btn-danger" style="font-size: 15px">Tentative</span></td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    function agent_Select_function(){
        var select_Agent    = $('#select_Agent').val();
        var currency        = `{{ $currency }}`;
        
        if(select_Agent != '-1'){
            $('#search_flight_loader').css('display','');
            $.ajax({
                url         : '{{ URL::to('super_admin/get_agent_details') }}',
                type        : 'GET',
                data        : {
                    'id'    : select_Agent,
                },
                success:function(data) {
                    
                    if(data['Total_grand_sale'] != null && data['Total_grand_sale'] != ''){
                        
                        var Total_grand_sale  = data['Total_grand_sale'];
                        Total_grand_sale      = parseFloat(Total_grand_sale);
                        Total_grand_sale      = Total_grand_sale.toFixed(2);
                        
                        var Total_grand_profit   = data['Total_grand_profit'];
                        Total_grand_profit       = parseFloat(Total_grand_profit);
                        Total_grand_profit       = Total_grand_profit.toFixed(2);
                        
                        var Total_grand_cost     = data['Total_grand_cost'];
                        Total_grand_cost         = parseFloat(Total_grand_cost);
                        Total_grand_cost         = Total_grand_cost.toFixed(2);
                        
                        $('#revenue_sale_id').html(currency+' '+Total_grand_sale);
                        $('#revenue_profit_id').html(currency+' '+Total_grand_profit);
                        $('#revenue_cost_id').html(currency+' '+Total_grand_cost);
                        $('#revenue_income_id').html(currency+' '+Total_grand_sale);
                        
                        $('#search_flight_loader').css('display','none');
                    }else{
                        $('#revenue_sale_id').html(currency+' '+0);
                        $('#revenue_profit_id').html(currency+' '+0);
                        $('#revenue_cost_id').html(currency+' '+0);
                        $('#revenue_income_id').html(currency+' '+0);
                        
                        $('#search_flight_loader').css('display','none');
                        alert('Something Went Wrong!');
                    }

                    // if(data['separate_Total_Revenue'] != null && data['separate_Total_Revenue'] != ''){
                        
                    //     var separate_Total_Revenue  = data['separate_Total_Revenue'];
                    //     separate_Total_Revenue      = parseFloat(separate_Total_Revenue);
                    //     separate_Total_Revenue      = separate_Total_Revenue.toFixed(2);
                        
                    //     var separate_profit_Total   = data['separate_profit_Total'];
                    //     separate_profit_Total       = parseFloat(separate_profit_Total);
                    //     separate_profit_Total       = separate_profit_Total.toFixed(2);
                        
                    //     var separate_Total_Cost     = data['separate_Total_Cost'];
                    //     separate_Total_Cost         = parseFloat(separate_Total_Cost);
                    //     separate_Total_Cost         = separate_Total_Cost.toFixed(2);
                        
                    //     $('#revenue_sale_id').html(currency+' '+separate_Total_Revenue);
                    //     $('#revenue_profit_id').html(currency+' '+separate_profit_Total);
                    //     $('#revenue_cost_id').html(currency+' '+separate_Total_Cost);
                    //     $('#revenue_income_id').html(currency+' '+separate_Total_Revenue);
                        
                    //     $('#search_flight_loader').css('display','none');
                    // }else{
                    //     $('#search_flight_loader').css('display','none');
                    //     alert('Something Went Wrong!');
                    // }
                },
            });
        }else{
            var separate_Total_Revenue  = `{{ $separate_Total_Revenue ?? '0' }}`;
            var separate_profit_Total   = `{{ $separate_profit_Total ?? '0' }}`;
            var separate_Total_Cost     = `{{ $separate_Total_Cost ?? '0' }}`;
            var separate_Total_Revenue  = `{{ $separate_Total_Revenue ?? '0' }}`;
            
            $('#revenue_sale_id').html(currency+' '+separate_Total_Revenue);
            $('#revenue_profit_id').html(currency+' '+separate_profit_Total);
            $('#revenue_cost_id').html(currency+' '+separate_Total_Cost);
            $('#revenue_income_id').html(currency+' '+separate_Total_Revenue);
            
            alert('Select Agent!');
            $('#search_flight_loader').css('display','none');
        }
    }
    
    function Days_Select_function(){
        var select_Days    = $('#select_Days').val();
        var currency        = `{{ $currency }}`;
        
        if(select_Days != '-1'){
            $('#search_flight_loader').css('display','');
            $.ajax({
                url         : '{{ URL::to('super_admin/get_Days_details') }}',
                type        : 'GET',
                data        : {
                    'id'    : select_Days,
                },
                success:function(data) {
                    if(data['separate_Total_Revenue'] != null && data['separate_Total_Revenue'] != ''){
                        
                        var separate_Total_Revenue  = data['separate_Total_Revenue'];
                        separate_Total_Revenue      = parseFloat(separate_Total_Revenue);
                        separate_Total_Revenue      = separate_Total_Revenue.toFixed(2);
                        
                        var separate_profit_Total   = data['separate_profit_Total'];
                        separate_profit_Total       = parseFloat(separate_profit_Total);
                        separate_profit_Total       = separate_profit_Total.toFixed(2);
                        
                        var separate_Total_Cost     = data['separate_Total_Cost'];
                        separate_Total_Cost         = parseFloat(separate_Total_Cost);
                        separate_Total_Cost         = separate_Total_Cost.toFixed(2);
                        
                        $('#revenue_sale_id').html(currency+' '+separate_Total_Revenue);
                        $('#revenue_profit_id').html(currency+' '+separate_profit_Total);
                        $('#revenue_cost_id').html(currency+' '+separate_Total_Cost);
                        $('#revenue_income_id').html(currency+' '+separate_Total_Revenue);
                        
                        $('#search_flight_loader').css('display','none');
                    }else{
                        $('#search_flight_loader').css('display','none');
                        alert('Bookings Not Available!');
                    }
                },
            });
        }else{
            var separate_Total_Revenue  = `{{ $separate_Total_Revenue ?? '0' }}`;
            var separate_profit_Total   = `{{ $separate_profit_Total ?? '0' }}`;
            var separate_Total_Cost     = `{{ $separate_Total_Cost ?? '0' }}`;
            var separate_Total_Revenue  = `{{ $separate_Total_Revenue ?? '0' }}`;
            
            $('#revenue_sale_id').html(currency+' '+separate_Total_Revenue);
            $('#revenue_profit_id').html(currency+' '+separate_profit_Total);
            $('#revenue_cost_id').html(currency+' '+separate_Total_Cost);
            $('#revenue_income_id').html(currency+' '+separate_Total_Revenue);
            
            alert('Select Days!');
            $('#search_flight_loader').css('display','none');
        }
    }
    
    function customerS_Select_function(){
        var select_CustomerS    = $('#select_CustomerS').val();
        var token_id            = $('#select_CustomerS').find('option:selected').attr('attr-t');
        var currency            = $('#select_CustomerS').find('option:selected').attr('attr-c');
        
        if(select_CustomerS != '-1'){
            $('#search_flight_loader').css('display','');
            $.ajax({
                url             : '{{ URL::to('super_admin/get_customerS_details') }}',
                type            : 'GET',
                data            : {
                    id          : select_CustomerS,
                    token_id    : token_id,
                },
                success:function(data) {
                    console.log(data);
                    
                    if(data['separate_Total_Revenue'] != null && data['separate_Total_Revenue'] != ''){
                        
                        var separate_Total_Revenue  = data['separate_Total_Revenue'];
                        separate_Total_Revenue      = Math.round(separate_Total_Revenue).toLocaleString();
                        
                        // separate_Total_Revenue      = parseFloat(separate_Total_Revenue);
                        // separate_Total_Revenue      = separate_Total_Revenue.toFixed(2);
                        
                        var separate_profit_Total   = data['separate_profit_Total'];
                        separate_profit_Total      = Math.round(separate_profit_Total).toLocaleString();
                        
                        // separate_profit_Total       = parseFloat(separate_profit_Total);
                        // separate_profit_Total       = separate_profit_Total.toFixed(2);
                        
                        var separate_Total_Cost     = data['separate_Total_Cost'];
                        separate_Total_Cost         = Math.round(separate_Total_Cost).toLocaleString();
                        
                        // separate_Total_Cost         = parseFloat(separate_Total_Cost);
                        // separate_Total_Cost         = separate_Total_Cost.toFixed(2);
                        
                        $('#revenue_sale_id').html(currency+' '+separate_Total_Revenue);
                        $('#revenue_profit_id').html(currency+' '+separate_profit_Total);
                        $('#revenue_cost_id').html(currency+' '+separate_Total_Cost);
                        $('#revenue_income_id').html(currency+' '+separate_Total_Revenue);
                        
                        $('#search_flight_loader').css('display','none');
                    }else{
                        $('#search_flight_loader').css('display','none');
                        alert('Bookings Not Available!');
                    }
                },
            });
        }else{
            var separate_Total_Revenue  = `{{ $separate_Total_Revenue ?? '0' }}`;
            var separate_profit_Total   = `{{ $separate_profit_Total ?? '0' }}`;
            var separate_Total_Cost     = `{{ $separate_Total_Cost ?? '0' }}`;
            var separate_Total_Revenue  = `{{ $separate_Total_Revenue ?? '0' }}`;
            
            $('#revenue_sale_id').html(separate_Total_Revenue);
            $('#revenue_profit_id').html(separate_profit_Total);
            $('#revenue_cost_id').html(separate_Total_Cost);
            $('#revenue_income_id').html(separate_Total_Revenue);
            
            alert('Select Customer!');
            $('#search_flight_loader').css('display','none');
        }
    }
    
</script>

<script>
    $(document).ready(function(){
    
        $('#example_Supplier').DataTable({
            paging: false,
            ordering: false,
            info: false,
            searching:false,
        });
        
        $('#example_Agent').DataTable({
            paging: false,
            ordering: false,
            info: false,
            searching:false,
        });
        
        $('#example_Customer').DataTable({
            paging: false,
            ordering: false,
            info: false,
            searching:false,
        });

        var hoverTimer;
        var hoverDelay = 500;

        $('.packages_detail').hover(function() {
            var $target = $(this);
            hoverTimer = setTimeout(function() {       
                $target.trigger('click');
            }, hoverDelay);
        }, function() {
            clearTimeout(hoverTimer);
        });

        $('.booked_tour').hover(function() {
            var $target = $(this);
            hoverTimer = setTimeout(function() {
                $target.trigger('click');         
            }, hoverDelay);
        }, function() {
            clearTimeout(hoverTimer);
        });
        
        $('.activity_detail').hover(function() {
            var $target = $(this);
            hoverTimer = setTimeout(function() {       
                $target.trigger('click');
            }, hoverDelay);
        }, function() {
            clearTimeout(hoverTimer);
        });
        
        $('.booked_package').hover(function() {
            var $target = $(this);
            hoverTimer = setTimeout(function() {
                $target.trigger('click');         
            }, hoverDelay);
        }, function() {
            clearTimeout(hoverTimer);
        });
        
    });
</script>

<script>
    //More Tour Details
    function booked_tour_span(id){
        $('#booked_tour_span').empty();
        const ids = $('#booked_tour_span_'+id+'').attr('data-id');
        $.ajax({
            url: 'super_admin/more_Tour_Details/'+ids,
            type: 'GET',
            data: {
                "id": ids
            },
            success:function(data) {
                var a           = data;
                var booked_tour = a['booked_tour'];
                $('#booked_tour_span_'+id+'').html(booked_tour);
            },
        });
    }
    
    // Separate Details
    $('#SR_Id').click(function() {
        var switch_SR = $('#switch_SR').val();
        if(switch_SR == 0){
            $('#SR_Div').css('display','');
            $('#switch_SR').val(1);
            
            $('#SP_Div').css('display','none');
            $('#switch_SP').val(0);
            $('#SE_Div').css('display','none');
            $('#switch_SE').val(0);
            $('#SI_Div').css('display','none');
            $('#switch_SI').val(0);
        }else{
            $('#SR_Div').css('display','none');
            $('#switch_SR').val(0);
        }
    });
    
    $('#SP_Id').click(function() {
        var switch_SP = $('#switch_SP').val();
        if(switch_SP == 0){
            $('#SP_Div').css('display','');
            $('#switch_SP').val(1);
            
            $('#SR_Div').css('display','none');
            $('#switch_SR').val(0);
            $('#SE_Div').css('display','none');
            $('#switch_SE').val(0);
            $('#SI_Div').css('display','none');
            $('#switch_SI').val(0);
        }else{
            $('#SP_Div').css('display','none');
            $('#switch_SP').val(0);
        }
    });
    
    $('#SE_Id').click(function() {
        var switch_SE = $('#switch_SE').val();
        if(switch_SE == 0){
            $('#SE_Div').css('display','');
            $('#switch_SE').val(1);
            
            $('#SP_Div').css('display','none');
            $('#switch_SP').val(0);
            $('#SR_Div').css('display','none');
            $('#switch_SR').val(0);
            $('#SI_Div').css('display','none');
            $('#switch_SI').val(0);
        }else{
            $('#SE_Div').css('display','none');
            $('#switch_SE').val(0);
        }
    });
    
    $('#SI_Id').click(function() {
        var switch_SI = $('#switch_SI').val();
        if(switch_SI == 0){
            $('#SI_Div').css('display','');
            $('#switch_SI').val(1);
            
            $('#SP_Div').css('display','none');
            $('#switch_SP').val(0);
            $('#SE_Div').css('display','none');
            $('#switch_SE').val(0);
            $('#SR_Div').css('display','none');
            $('#switch_SR').val(0);
        }else{
            $('#SI_Div').css('display','none');
            $('#switch_SI').val(0);
        }
    });
    
    // Weekly Chart
    $('#LWCH_ID').click(function() {
        var switch_LWCH = $('#switch_LWCH').val();
        if(switch_LWCH == 0){
            // $('#LWCH_Div').css('display','');
            $('#LWCD_Div').css('display','');
            $('#switch_LWCH').val(1);
            
            // $('#LMCH_Div').css('display','none');
            $('#LMCD_Div').css('display','none');
            $('#switch_LMCH').val(0);
            // $('#LYCH_Div').css('display','none');
            $('#LYCD_Div').css('display','none');
            $('#switch_LYCH').val(0);
        }else{
            // $('#LWCH_Div').css('display','none');
            $('#LWCD_Div').css('display','none');
            $('#switch_LWCH').val(0);
        }
    });
    
    $('#LMCH_ID').click(function() {
        var switch_LMCH = $('#switch_LMCH').val();
        if(switch_LMCH == 0){
            // $('#LMCH_Div').css('display','');
            $('#LMCD_Div').css('display','');
            $('#switch_LMCH').val(1);
            
            // $('#LWCH_Div').css('display','none');
            $('#LWCD_Div').css('display','none');
            $('#switch_LWCH').val(0);
            // $('#LYCH_Div').css('display','none');
            $('#LYCD_Div').css('display','none');
            $('#switch_LYCH').val(0);
        }else{
            // $('#LWCH_Div').css('display','none');
            $('#LMCD_Div').css('display','none');
            $('#switch_LMCH').val(0);
        }
    });
    
    $('#LYCH_ID').click(function() {
        var switch_LYCH = $('#switch_LYCH').val();
        if(switch_LYCH == 0){
            // $('#LYCH_Div').css('display','');
            $('#LYCD_Div').css('display','');
            $('#switch_LYCH').val(1);
            
            // $('#LWCH_Div').css('display','none');
            $('#LWCD_Div').css('display','none');
            $('#switch_LWCH').val(0);
            // $('#LMCH_Div').css('display','none');
            $('#LMCD_Div').css('display','none');
            $('#switch_LMCH').val(0);
        }else{
            // $('#LYCH_Div').css('display','none');
            $('#LYCD_Div').css('display','none');
            $('#switch_LYCH').val(0);
        }
    });
    
    // Weekly Revenue
    $('#LW_ID').click(function() {
        var switch_LW = $('#switch_LW').val();
        if(switch_LW == 0){
            $('#WER_Div').css('display','');
            $('#LWR_Div').css('display','');
            $('#switch_LW').val(1);
            
            $('#MER_Div').css('display','none');
            $('#LMR_Div').css('display','none');
            $('#switch_LM').val(0);
            $('#YER_Div').css('display','none');
            $('#LYR_Div').css('display','none');
            $('#switch_LY').val(0);
        }else{
            $('#WER_Div').css('display','none');
            $('#LWR_Div').css('display','none');
            $('#switch_LW').val(0);
        }
    });
    
    $('#LM_ID').click(function() {
        var switch_LM = $('#switch_LM').val();
        if(switch_LM == 0){
            $('#MER_Div').css('display','');
            $('#LMR_Div').css('display','');
            $('#switch_LM').val(1);
            
            $('#WER_Div').css('display','none');
            $('#LWR_Div').css('display','none');
            $('#switch_LW').val(0);
            $('#YER_Div').css('display','none');
            $('#LYR_Div').css('display','none');
            $('#switch_LY').val(0);
        }else{
            $('#MER_Div').css('display','none');
            $('#LMR_Div').css('display','none');
            $('#switch_LM').val(0);
        }
    });
    
    $('#LY_ID').click(function() {
        var switch_LY = $('#switch_LY').val();
        if(switch_LY == 0){
            $('#YER_Div').css('display','');
            $('#LYR_Div').css('display','');
            $('#switch_LY').val(1);
            
            $('#WER_Div').css('display','none');
            $('#LMR_Div').css('display','none');
            $('#switch_LM').val(0);
            $('#MER_Div').css('display','none');
            $('#LWR_Div').css('display','none');
            $('#switch_LW').val(0);
        }else{
            $('#YER_Div').css('display','none');
            $('#LYR_Div').css('display','none');
            $('#switch_LY').val(0);
        }
    });
    
    // Supplier
    $('#SUP_ID').click(function() {
        var switch_SUP = $('#switch_SUP').val();
        if(switch_SUP == 0){
            $('#SUP_Div').css('display','');
            $('#switch_SUP').val(1);
        }else{
            $('#SUP_Div').css('display','none');
            $('#switch_SUP').val(0);
        }
    });
    
</script>
    
@stop

@section('scripts')

<script>

    !function(o){"use strict";function e(){this.$body=o("body"),this.charts=[]}e.prototype.initCharts=function(){window.Apex={chart:{parentHeightOffset:0,toolbar:{show:!1}},grid:{padding:{left:0,right:0}},colors:["#727cf5","#0acf97","#fa5c7c","#ffbc00"]};var e=["#727cf5","#0acf97","#fa5c7c","#ffbc00"],t=o("#revenue-chart").data("colors");t&&(e=t.split(","));var r={chart:{height:364,type:"line",dropShadow:{enabled:!0,opacity:.2,blur:7,left:-7,top:7}},dataLabels:{enabled:!1},stroke:{curve:"smooth",width:4},series:[{name:"Current Week",data:[<?php print_r($package_weeks[0]);  ?>,<?php print_r($package_weeks[1]);  ?>,<?php print_r($package_weeks[2]);  ?>,<?php print_r($package_weeks[3]);  ?>,<?php print_r($package_weeks[4]);  ?>,<?php print_r($package_weeks[5]);  ?>,<?php print_r($package_weeks[6]);  ?>]},{name:"Current Week",data:[<?php print_r($package_weeks1[0]);  ?>,<?php print_r($package_weeks1[1]);  ?>,<?php print_r($package_weeks1[2]);  ?>,<?php print_r($package_weeks1[3]);  ?>,<?php print_r($package_weeks1[4]);  ?>,<?php print_r($package_weeks1[5]);  ?>,<?php print_r($package_weeks1[6]);  ?>]}],colors:e,zoom:{enabled:!1},legend:{show:!1},xaxis:{type:"string",categories:["Mon","Tue","Wed","Thu","Fri","Sat","Sun"],tooltip:{enabled:!1},axisBorder:{show:!1}},yaxis:{labels:{formatter:function(e){return e+" Booked"},offsetX:-15}}};new ApexCharts(document.querySelector("#revenue-chart"),r).render();e=["#727cf5","#e3eaef"];(t=o("#high-performing-product").data("colors"))&&(e=t.split(","));r={chart:{height:257,type:"bar",stacked:!0},plotOptions:{bar:{horizontal:!1,columnWidth:"20%"}},dataLabels:{enabled:!1},stroke:{show:!0,width:2,colors:["transparent"]},series:[{name:"Package",data:[<?php print_r($package_month[0]);?>,<?php print_r($package_month[1]);?>,<?php print_r($package_month[2]);?>,<?php print_r($package_month[3]);?>,<?php print_r($package_month[4]);?>,<?php print_r($package_month[5]);?>,<?php print_r($package_month[6]);?>,<?php print_r($package_month[7]);?>,<?php print_r($package_month[8]);?>,<?php print_r($package_month[9]);?>,<?php print_r($package_month[10]);?>,<?php print_r($package_month[11]);?>]},{name:"Activity",data:[<?php print_r($package_month1[0]);?>,<?php print_r($package_month1[1]);?>,<?php print_r($package_month1[2]);?>,<?php print_r($package_month1[3]);?>,<?php print_r($package_month1[4]);?>,<?php print_r($package_month1[5]);?>,<?php print_r($package_month1[6]);?>,<?php print_r($package_month1[7]); ?>,<?php print_r($package_month1[8]); ?>,<?php print_r($package_month1[9]); ?>,<?php print_r($package_month1[10]); ?>,<?php print_r($package_month1[11]); ?>]}],zoom:{enabled:!1},legend:{show:!1},colors:e,xaxis:{categories:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],axisBorder:{show:!1}},yaxis:{labels:{formatter:function(e){return e+" Booked"},offsetX:-15}},fill:{opacity:1},tooltip:{y:{formatter:function(e){return""+e+"  Booked"}}}};new ApexCharts(document.querySelector("#high-performing-product"),r).render();e=["#727cf5","#0acf97","#fa5c7c","#ffbc00"];(t=o("#average-sales").data("colors"))&&(e=t.split(","));r={chart:{height:203,type:"donut"},legend:{show:!1},stroke:{colors:["transparent"]},series:[<?php print_r($booked_tourA + $booked_tourC);?>,<?php print_r($booked_activitiesA + $booked_activitiesC);?>,0,0],labels:["Package","Activity","Hotel","Transfer"],colors:e,responsive:[{breakpoint:480,options:{chart:{width:200},legend:{position:"bottom"}}}]};new ApexCharts(document.querySelector("#average-sales"),r).render()},e.prototype.initMaps=function(){0<o("#world-map-markers").length&&o("#world-map-markers").vectorMap({map:"world_mill_en",normalizeFunction:"polynomial",hoverOpacity:.7,hoverColor:!1,regionStyle:{initial:{fill:"#e3eaef"}},markerStyle:{initial:{r:9,fill:"#727cf5","fill-opacity":.9,stroke:"#fff","stroke-width":7,"stroke-opacity":.4},hover:{stroke:"#fff","fill-opacity":1,"stroke-width":1.5}},backgroundColor:"transparent",markers:[{latLng:[23.885942,45.079163],name:"Saudi Arabia"},{latLng:[56.130367,-106.346771],name:"Canada"},{latLng:[51.507351,-0.127758],name:"London"},{latLng:[38.892059,-77.019913],name:"United States of America"}],zoomOnScroll:!1})},e.prototype.init=function(){o("#dash-daterange").daterangepicker({singleDatePicker:!0}),this.initCharts(),this.initMaps()},o.Dashboard=new e,o.Dashboard.Constructor=e}(window.jQuery),function(t){"use strict";t(document).ready(function(e){t.Dashboard.init()})}(window.jQuery);

</script>

@endsection

<?php // dd('STOP'); ?>