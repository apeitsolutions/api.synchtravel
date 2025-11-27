@extends('template/frontend/userdashboard/layout/default')
@section('content')

    <?php $currency=Session::get('currency_symbol'); ?>
    
    <style>
        .nav-link{
          color: #575757;
          font-size: 18px;
        }
    </style>

    <div class="content-wrapper">
        
        <section class="content d-none" style="padding: 30px 50px 0px 50px;">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12 col-12">
                <nav class="breadcrumb push">
                    <a class="breadcrumb-item" href="#">Dashboard</a> 
                    <span class="breadcrumb-item active">View Agent Invoices</span>
                </nav>
              </div>
            </div>
          </div>
        </section>
        
        <section class="content d-none" style="padding: 10px 20px 0px 20px">
          <div class="container-fluid">
            <div class="row">
            
              <!-- ./col -->
              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-success" style="height: 184px;text-align: center;padding: 15px 0px 0px 0px;background-color: #313a46 !important;color: white;">
                  <div class="icon mb-2">
                    <span class="iconify" data-icon="uil-dollar-sign-alt" data-width="70"></span>
                  </div>
                  <div class="inner">
                    <h3 id=""></h3>
    
                    <p>Total Revenue</p>
                  </div>
                  
                </div>
              </div>
              <!-- ./col -->
              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box" style="height: 184px;text-align: center;padding: 15px 0px 0px 0px;background-color: #313a46 !important;color: white;">
                  <div class="icon mb-2">
                    <span class="iconify" data-icon="uil-dollar-sign-alt" data-width="70"></span>
                  </div>
                  <div class="inner">
                    <h3 id=""></h3>
    
                    <p>Total Paid</p>
                  </div>
                  
                </div>
              </div>
              <!-- ./col -->
              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info" style="height: 184px;text-align: center;padding: 15px 0px 0px 0px;background-color: #313a46 !important;color: white;">
                  <div class="icon mb-2">
                    <span class="iconify" data-icon="uil-dollar-sign-alt" data-width="70"></span>
                  </div>
                  <div class="inner">
                    <h3 id=""></h3>
    
                    <p>Outstanding</p>
                  </div>
                  
                </div>
              </div>
              
                <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-primary" onclick="manageOverPaid('{{ $agents_data->agent_id }}')" data-bs-toggle="modal" data-bs-target="#exampleModal" style="height: 184px;text-align: center;padding: 15px 0px 0px 0px;background-color: #313a46 !important;color: white;">
                  <div class="icon mb-2">
                    <!-- <i class="mdi mdi-eye me-1"></i> -->
                    <span class="iconify" data-icon="uil-dollar-sign-alt" data-width="70"></span>
                  </div>
                  <div class="inner">
                    
                    <p>Over Paid</p>
    
                    <h3 id="">{{ $agents_data->agent_over_paid }}</h3>
    
                  </div>
                  
                </div>
              </div>
              <!-- ./col -->
            </div>
          </div>
        </section>
    
        <section class="content d-none" style="padding: 30px 50px 0px 50px;">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-12">
                        <h4>Agents Stats Details</h4>
                        <div class="panel-body padding-bottom-none">
                            <div class="block-content block-content-full">
                                
                                <div class="table-responsive">
                                    <div id="example_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table style="" class="table nowrap example1 dataTable no-footer" id="myTable" role="grid" aria-describedby="example_info">
                                                
                                                <thead class="theme-bg-clr">
                                                    <tr role="row">
                                                        <th>Sr</th>
                                                        <th>Agent Id</th>
                                                        <th>Agent Name</th>
                                                        <th>Invoice No</th>
                                                        <th>Total Amount</th>
                                                        <th>Paid Amount</th>
                                                        <th>Remaning Amount</th>
                                                        <th>Over Paid</th>
                                                        <th>Agent Commission</th>
                                                        <th>Profit</th>
                                                        <th>View Inv</th>
                                                    </tr>
                                                </thead>
                                                
                                                <tbody style="text-align: center;">
                                                    
                                                   @isset($agents_data)
                                                
                                                       <?php
                                                             print_r($agents_data);
                                                             $x = 1;
                                                             $total_sale = 0;
                                                             $total_paymet_amount = 0;
                                                             $total_remaning_amount = 0;
                                                             $total_over_paid = 0;
                                                       ?>
                                                        @foreach($agents_data->agents_tour_booking as $agent_res)
                                                            <tr role="row">
                                                                <td>{{ $x++ }}</td>
                                                                <td>{{ $agents_data->agent_id }}</td>
                                                                <td>Company Name:{{ $agents_data->agent_company }}<br>
                                                                    Agent Name:{{ $agents_data->agent_name }}</td>
                                                                <td>{{ $agent_res->invoice_id }}<br>
                                                                    Package Name:{{ $agent_res->tour_name }}<br>
                                                                    Type:Package
                                                                </td>
                                                                <td>{{ $agent_res->price }}</td>
                                                                <td>{{ $agent_res->paid_amount }}</td>
                                                                <td>{{ $agent_res->remaing_amount }}</td>
                                                                <td>{{ $agent_res->over_paid_amount }} </td>
                                                                 
                                                                <td>Commission:{{ $agent_res->commission_am }}
                                                                </br>Include Total: 
                                                                <?php 
                                                                    if($agent_res->agent_commsion_add_total){
                                                                        echo "Yes";
                                                                    }else{
                                                                        echo "No";
                                                                    }
                                                                ?>
                                                                </td>
                                                                <td>Profit:{{ $agent_res->profit }}<br>
                                                                Cost:{{ $agent_res->total_cost }}<br>
                                                                Sale:{{ $agent_res->total_sale }}<br>
                                                                    Discount:{{ $agent_res->discount_am }}
                                                                </td>
                                                                <td>
                                                                      <a href="{{ URL::to('invoice_package/'.$agent_res->invoice_id.'/'.$agent_res->booking_id.'/'.$agent_res->tour_id.'') }}" target="blank" class="btn btn-primary btn-sm">view Details</a>
                                                                      
                                                                </td>
                                                                
                                                          

                                                            </tr>
                                                            
                                                            <?php 
                                                             $total_sale += $agent_res->price;
                                                             $total_paymet_amount += $agent_res->paid_amount;
                                                             $total_remaning_amount += $agent_res->remaing_amount;
                                                             $total_over_paid = $agent_res->over_paid_amount;
                                                            ?>
                                                        @endforeach
                                                        
                                                        @foreach($agents_data->agents_invoices_booking as $agent_res)
                                                            <tr role="row">
                                                                <td>{{ $x++ }}</td>
                                                                <td>{{ $agents_data->agent_id }}</td>
                                                                <td>Company Name:{{ $agents_data->agent_company }}<br>
                                                                    Agent Name:{{ $agents_data->agent_name }}</td>
                                                                <td>{{ $agent_res->invoice_id }}<br>
                                                                    Type:Invoice
                                                                </td>
                                                                <td>{{ $agent_res->price }}</td>
                                                                <td>{{ $agent_res->paid_amount }}
                                                                
                                                                </td>
                                                                <td>@if($agent_res->remaing_amount >= 0) {{ $agent_res->remaing_amount }} @endif</td>
                                                                <td>{{ $agent_res->over_paid_amount }}</td>
                                                                 
                                                                <td>
                                                                </td>
                                                                <td>Profit:{{ $agent_res->profit }}
                                                                 Cost:{{ $agent_res->total_cost }}<br>
                                                                Sale:{{ $agent_res->total_sale }}<br>
                                                                   
                                                                </td>
                                                                <td>

                                                                </td>
                                                                
                                                            </tr>
                                                            
                                                             <?php 
                                                             $total_sale += $agent_res->price;
                                                             $total_paymet_amount += $agent_res->paid_amount;
                                                             $total_remaning_amount += $agent_res->remaing_amount;
                                                            //  $total_over_paid = $agent_res->over_paid_amount;
                                                            ?>
                                                        @endforeach
                                                        
                                                        
                                                    @endisset()
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
            </div>
        </section>
        
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Agent</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        </ol>
                    </div>
                    <h4 class="page-title">Agent Dashboard</h4>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-xxl-9">
                <div class="row">
                    <div class="col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="dropdown float-end">
                                    <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="mdi mdi-dots-horizontal"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm rounded">
                                            <span class="avatar-title bg-primary-lighten h3 my-0 text-primary rounded">
                                                <i class="">{{ $currency }}</i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h4 class="mt-0 mb-1 font-20" id="total_revenue"></h4>
                                        <p class="mb-0 text-muted"><i class="mdi mdi-arrow-up-bold text-success"></i>Total Revenue</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="dropdown float-end">
                                    <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="mdi mdi-dots-horizontal"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm rounded">
                                            <span class="avatar-title bg-primary-lighten h3 my-0 text-primary rounded">
                                                <i class="">{{ $currency }}</i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h4 class="mt-0 mb-1 font-20" id="total_paid"></h4>
                                        <p class="mb-0 text-muted"><i class="mdi mdi-arrow-up-bold text-success"></i>Total Paid</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="dropdown float-end">
                                    <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="mdi mdi-dots-horizontal"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm rounded">
                                            <span class="avatar-title bg-primary-lighten h3 my-0 text-primary rounded">
                                                <i class="">{{ $currency }}</i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h4 class="mt-0 mb-1 font-20" id="outstanding"></h4>
                                        <p class="mb-0 text-muted"><i class="mdi mdi-arrow-up-bold text-success"></i>Outstandings</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="dropdown float-end">
                                    <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="mdi mdi-dots-horizontal"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm rounded">
                                            <span class="avatar-title bg-primary-lighten h3 my-0 text-primary rounded">
                                                <i class="">{{ $currency }}</i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h4 class="mt-0 mb-1 font-20">{{ $agents_data->agent_over_paid }}</h4>
                                        <p class="mb-0 text-muted"><i class="mdi mdi-arrow-up-bold text-success"></i>Over Paid</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="align-items-center d-sm-flex justify-content-sm-between mb-3">
                                    <h4 class="header-title mb-0">Balance Status</h4>

                                    <ul class="nav nav-pills bg-nav-pills p-1 rounded" id="pills-tab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <a href="#day-status" data-bs-toggle="tab" aria-expanded="false" class="nav-link py-1 active">
                                                <span class="">Day</span>
                                            </a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a href="#week-status" data-bs-toggle="tab" aria-expanded="false" class="nav-link py-1">
                                                <span class="">Week</span>
                                            </a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a href="#month-status" data-bs-toggle="tab" aria-expanded="false" class="nav-link py-1">
                                                <span class="">Month</span>
                                            </a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a href="#year-status" data-bs-toggle="tab" aria-expanded="false" class="nav-link py-1">
                                                <span class="">Year</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="day-status" role="tabpanel" aria-labelledby="day-status-tab">
                                        <div dir="ltr">
                                            <div id="day-balance-chart" class="apex-charts" data-colors="#0acf97"></div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="week-status" role="tabpanel" aria-labelledby="week-status-tab">
                                        <div dir="ltr">
                                            <div id="week-balance-chart" class="apex-charts" data-colors="#0acf97"></div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="month-status" role="tabpanel" aria-labelledby="month-status-tab">
                                        <div dir="ltr">
                                            <div id="month-balance-chart" class="apex-charts" data-colors="#0acf97"></div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="year-status" role="tabpanel" aria-labelledby="year-status-tab">
                                        <div dir="ltr">
                                            <div id="year-balance-chart" class="apex-charts" data-colors="#0acf97"></div>
                                        </div>
                                    </div>
                                  </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-3">
                <div class="row">
                    <div class="col-md-6 col-xxl-12">
                        <div class="card bg-primary card-bg-img" style="background-image: url(assets/images/bg-pattern.png);">
                            <div class="card-body">
                                <span class="float-end text-white-50 display-5 mt-n1"><i class="mdi mdi-contactless-payment"></i></span>
                                <h4 class="text-white">Dominic Keller</h4>

                                <div class="row align-items-center mt-4">
                                    <div class="col-3 text-white font-12">
                                        <i class="mdi mdi-circle"></i>
                                        <i class="mdi mdi-circle"></i>
                                        <i class="mdi mdi-circle"></i>
                                        <i class="mdi mdi-circle"></i>
                                    </div>
                                    <div class="col-3 text-white font-12">
                                        <i class="mdi mdi-circle"></i>
                                        <i class="mdi mdi-circle"></i>
                                        <i class="mdi mdi-circle"></i>
                                        <i class="mdi mdi-circle"></i>
                                    </div>
                                    <div class="col-3 text-white font-12">
                                        <i class="mdi mdi-circle"></i>
                                        <i class="mdi mdi-circle"></i>
                                        <i class="mdi mdi-circle"></i>
                                        <i class="mdi mdi-circle"></i>
                                    </div>
                                    <div class="col-3 text-white font-16 fw-bold">
                                        <span>1</span>
                                        <span>2</span>
                                        <span>3</span>
                                        <span>4</span>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-4">
                                        <p class="text-white-50 font-16 mb-1">Expiry Date</p>
                                        <h4 class="text-white my-0">10/26</h4>
                                    </div>

                                    <div class="col-4">
                                        <p class="text-white-50 font-16 mb-1">CCV</p>
                                        <h4 class="text-white my-0">000</h4>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-end">
                                            <span class="avatar-sm bg-white opacity-25 rounded-circle d-inline-block"></span>
                                            <span class="avatar-sm bg-white opacity-25 rounded-circle d-inline-block ms-n3"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xxl-12">
                        <div class="card">
                            <div class="card-body pb-0">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="header-title">My Watchlist</h4>
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="mdi mdi-dots-vertical"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-body py-0" data-simplebar style="max-height: 318px;">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm rounded">
                                            <span class="avatar-title bg-warning-lighten text-warning border border-warning rounded-circle h3 my-0">
                                                <i class="">{{ $currency }}</i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <h4 class="mt-0 mb-1 font-16 fw-semibold">Packages</h4>
                                        <p class="mb-0 text-muted">{{ $separate_package_Revenue }}</p>
                                    </div>
                                </div>
                                <hr class="opacity-50 bg-secondary-lighten">
                                
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm rounded">
                                            <span class="avatar-title bg-warning-lighten text-warning border border-warning rounded-circle h3 my-0">
                                                <i class="">{{ $currency }}</i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <h4 class="mt-0 mb-1 font-16 fw-semibold">Accomodation</h4>
                                        <p class="mb-0 text-muted">{{ $separate_Revenue_accomodation ?? '0' }}</p>
                                    </div>
                                </div>
                                <hr class="opacity-50 bg-secondary-lighten">
                                
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm rounded">
                                            <span class="avatar-title bg-warning-lighten text-warning border border-warning rounded-circle h3 my-0">
                                                <i class="">{{ $currency }}</i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <h4 class="mt-0 mb-1 font-16 fw-semibold">Visa</h4>
                                        <p class="mb-0 text-muted">{{ $separate_Revenue_visa ?? '0' }}</p>
                                    </div>
                                </div>
                                <hr class="opacity-50 bg-secondary-lighten">
                                
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm rounded">
                                            <span class="avatar-title bg-warning-lighten text-warning border border-warning rounded-circle h3 my-0">
                                                <i class="">{{ $currency }}</i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <h4 class="mt-0 mb-1 font-16 fw-semibold">Flights</h4>
                                        <p class="mb-0 text-muted">{{ $separate_Revenue_flight ?? '0' }}</p>
                                    </div>
                                </div>
                                <hr class="opacity-50 bg-secondary-lighten">
                                
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm rounded">
                                            <span class="avatar-title bg-warning-lighten text-warning border border-warning rounded-circle h3 my-0">
                                                <i class="">{{ $currency }}</i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <h4 class="mt-0 mb-1 font-16 fw-semibold">Transportation</h4>
                                        <p class="mb-0 text-muted">{{ $separate_Revenue_transportation ?? '0' }}</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-xxl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h4 class="header-title">Money History</h4>
                        </div>

                        <div class="border border-light p-3 rounded mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="font-18 mb-1">Income</p>
                                    <h3 class="text-primary my-0" id="total_revenue1"></h3>
                                </div>  
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-primary rounded-circle h3 my-0">
                                        <i class="mdi mdi-arrow-up-bold-outline"></i>
                                    </span>
                                </div>                                      
                            </div>
                        </div>

                        <div class="border border-light p-3 rounded mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="font-18 mb-1">Expenses</p>
                                    <h3 class="text-danger my-0" id="outstanding1"></h3>
                                </div>  
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-danger rounded-circle h3 my-0">
                                        <i class="mdi mdi-arrow-down-bold-outline"></i>
                                    </span>
                                </div>                                      
                            </div>
                        </div>

                        <div class="border border-light p-3 rounded">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="font-18 mb-1">Transfar</p>
                                    <h3 class="text-success my-0" id="total_paid1"></h3>
                                </div>  
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-success rounded-circle h3 my-0">
                                        <i class="mdi mdi-swap-horizontal"></i>
                                    </span>
                                </div>                                      
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xxl-3">
                <div class="card">
                    <div class="card-body pb-0">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="header-title mb-0">Agent list</h4>
                        </div>
                    </div>

                    <div class="card-body py-0" data-simplebar style="max-height: 363px;">
                        
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded">
                                    <span class="avatar-title bg-transparent border border-light text-danger rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-dribbble" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M8 0C3.584 0 0 3.584 0 8s3.584 8 8 8c4.408 0 8-3.584 8-8s-3.592-8-8-8zm5.284 3.688a6.802 6.802 0 0 1 1.545 4.251c-.226-.043-2.482-.503-4.755-.217-.052-.112-.096-.234-.148-.355-.139-.33-.295-.668-.451-.99 2.516-1.023 3.662-2.498 3.81-2.69zM8 1.18c1.735 0 3.323.65 4.53 1.718-.122.174-1.155 1.553-3.584 2.464-1.12-2.056-2.36-3.74-2.551-4A6.95 6.95 0 0 1 8 1.18zm-2.907.642A43.123 43.123 0 0 1 7.627 5.77c-3.193.85-6.013.833-6.317.833a6.865 6.865 0 0 1 3.783-4.78zM1.163 8.01V7.8c.295.01 3.61.053 7.02-.971.199.381.381.772.555 1.162l-.27.078c-3.522 1.137-5.396 4.243-5.553 4.504a6.817 6.817 0 0 1-1.752-4.564zM8 14.837a6.785 6.785 0 0 1-4.19-1.44c.12-.252 1.509-2.924 5.361-4.269.018-.009.026-.009.044-.017a28.246 28.246 0 0 1 1.457 5.18A6.722 6.722 0 0 1 8 14.837zm3.81-1.171c-.07-.417-.435-2.412-1.328-4.868 2.143-.338 4.017.217 4.251.295a6.774 6.774 0 0 1-2.924 4.573z"/>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <a href="javascript:void(0);" class="h4 my-0 fw-semibold text-secondary">Packages <i class="mdi mdi-check-decagram text-muted ms-1"></i></a>
                            </div>
                            <button type="button" class="btn font-16 text-muted" id="APB_ID">
                                <i class="uil uil-angle-right-b"></i>
                            </button>
                        </div>

                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded">
                                    <span class="avatar-title bg-transparent border border-light text-info rounded h3 my-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-behance" viewBox="0 0 16 16">
                                            <path d="M4.654 3c.461 0 .887.035 1.278.14.39.07.711.216.996.391.286.176.497.426.641.747.14.32.216.711.216 1.137 0 .496-.106.922-.356 1.242-.215.32-.566.606-.997.817.606.176 1.067.496 1.348.922.281.426.461.957.461 1.563 0 .496-.105.922-.285 1.278a2.317 2.317 0 0 1-.782.887c-.32.215-.711.39-1.137.496a5.329 5.329 0 0 1-1.278.176L0 12.803V3h4.654zm-.285 3.978c.39 0 .71-.105.957-.285.246-.18.355-.497.355-.887 0-.216-.035-.426-.105-.567a.981.981 0 0 0-.32-.355 1.84 1.84 0 0 0-.461-.176c-.176-.035-.356-.035-.567-.035H2.17v2.31c0-.005 2.2-.005 2.2-.005zm.105 4.193c.215 0 .426-.035.606-.07.176-.035.356-.106.496-.216s.25-.215.356-.39c.07-.176.14-.391.14-.641 0-.496-.14-.852-.426-1.102-.285-.215-.676-.32-1.137-.32H2.17v2.734h2.305v.005zm6.858-.035c.286.285.711.426 1.278.426.39 0 .746-.106 1.032-.286.285-.215.46-.426.53-.64h1.74c-.286.851-.712 1.457-1.278 1.848-.566.355-1.243.566-2.06.566a4.135 4.135 0 0 1-1.527-.285 2.827 2.827 0 0 1-1.137-.782 2.851 2.851 0 0 1-.712-1.172c-.175-.461-.25-.957-.25-1.528 0-.531.07-1.032.25-1.493.18-.46.426-.852.747-1.207.32-.32.711-.606 1.137-.782a4.018 4.018 0 0 1 1.493-.285c.606 0 1.137.105 1.598.355.46.25.817.532 1.102.958.285.39.496.851.641 1.348.07.496.105.996.07 1.563h-5.15c0 .58.21 1.11.496 1.396zm2.24-3.732c-.25-.25-.642-.391-1.103-.391-.32 0-.566.07-.781.176-.215.105-.356.25-.496.39a.957.957 0 0 0-.25.497c-.036.175-.07.32-.07.46h3.196c-.07-.526-.25-.882-.497-1.132zm-3.127-3.728h3.978v.957h-3.978v-.957z"/>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <a href="javascript:void(0);" class="h4 my-0 fw-semibold text-secondary">Accomodation <i class="mdi mdi-check-decagram text-muted ms-1"></i></a>
                            </div>
                            <button type="button" class="btn font-16 text-muted" id="AA_ID">
                                <i class="uil uil-angle-right-b"></i>
                            </button>
                        </div>

                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded">
                                    <span class="avatar-title bg-transparent border border-light text-primary rounded h3 my-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                                            <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <a href="javascript:void(0);" class="h4 my-0 fw-semibold text-secondary">Flight<i class="mdi mdi-check-decagram text-muted ms-1"></i></a>
                            </div>
                            <button type="button" class="btn font-16 text-muted" id="AF_ID">
                                <i class="uil uil-angle-right-b"></i>
                            </button>
                        </div>

                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded">
                                    <span class="avatar-title bg-transparent border border-light text-danger rounded h3 my-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
                                            <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <a href="javascript:void(0);" class="h4 my-0 fw-semibold text-secondary">Visa<i class="mdi mdi-check-decagram text-muted ms-1"></i></a>
                            </div>
                            <button type="button" class="btn font-16 text-muted" id="AV_ID">
                                <i class="uil uil-angle-right-b"></i>
                            </button>
                        </div>

                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded">
                                    <span class="avatar-title bg-transparent border border-light text-dark rounded h3 my-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-github" viewBox="0 0 16 16">
                                            <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.012 8.012 0 0 0 16 8c0-4.42-3.58-8-8-8z"/>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <a href="javascript:void(0);" class="h4 my-0 fw-semibold text-secondary">Transportation <i class="mdi mdi-check-decagram text-muted ms-1"></i></a>
                            </div>
                            <button type="button" class="btn font-16 text-muted" id="AT_ID">
                                <i class="uil uil-angle-right-b"></i>
                            </button>
                        </div>
                        
                    </div>
                </div>
            </div>

            <div class="col-xxl-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h4 class="header-title mb-0" id="transaction_list_id">Transaction List</h4>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-centered table-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th>Agent Details</th>
                                        <th>Payment Details</th>
                                        <th>Agent Commission Details</th>
                                        <th>Profit Details</th>
                                        <th>View Invoice</th>
                                    </tr>
                                </thead>
                                
                                <tbody id="APB_Div" style="display:none">
                                    <input type="hidden" value="0" id="switch_APB">
                                    <input type="hidden" value="0" id="switch_AI">
                                    @isset($agents_data)        
                                        <?php
                                            $total_sale = 0;
                                            $total_paymet_amount = 0;
                                            $total_remaning_amount = 0;
                                            $total_over_paid = 0;
                                        ?>
                                        @foreach($agents_data->agents_tour_booking as $agent_res)
                                            <tr role="row">
                                                <td>Agent Id : <span class="text-success fw-semibold">{{ $agents_data->agent_id }}</span><br>
                                                    Company Name : <span class="text-success fw-semibold">{{ $agents_data->agent_company }}</span><br>
                                                    Agent Name : <span class="text-success fw-semibold">{{ $agents_data->agent_name }}</span><br>
                                                    Agent Invoice No : <span class="text-success fw-semibold">{{ $agent_res->invoice_id }}</span><br>
                                                    Package Name : <span class="text-success fw-semibold">{{ $agent_res->tour_name }}</span><br>
                                                    Type : <span class="text-success fw-semibold">Package</span>
                                                </td>
                                                <td>Total : <span class="badge bg-success-lighten text-success">{{ $agent_res->price }}</span><br>
                                                    Paid : <span class="badge bg-success-lighten text-success">{{ $agent_res->paid_amount }}</span><br>
                                                    Remaining : <span class="badge bg-success-lighten text-success">{{ $agent_res->remaing_amount }}</span><br>
                                                    Over Paid : <span class="badge bg-success-lighten text-success">{{ $agent_res->over_paid_amount }}</span>
                                                </td>
                                                <td>Commission : {{ $agent_res->commission_am }}</br>
                                                    Include Total : 
                                                    <?php 
                                                        if($agent_res->agent_commsion_add_total){
                                                            echo "Yes";
                                                        }else{
                                                            echo "No";
                                                        }
                                                    ?>
                                                </td>
                                                <td>Profit : <span class="badge bg-success-lighten text-success">{{ $agent_res->profit }}</span><br>
                                                    Cost : <span class="badge bg-success-lighten text-success">{{ $agent_res->total_cost }}</span><br>
                                                    Sale : <span class="badge bg-success-lighten text-success">{{ $agent_res->total_sale }}</span><br>
                                                    Discount : <span class="badge bg-success-lighten text-success">{{ $agent_res->discount_am }}</span>
                                                </td>
                                                <td><a href="{{ URL::to('invoice_package/'.$agent_res->invoice_id.'/'.$agent_res->booking_id.'/'.$agent_res->tour_id.'') }}" target="blank" class="btn btn-primary btn-sm">view Invoice</a></td>
                                                
                                            </tr>
                                            <?php 
                                                $total_sale += $agent_res->price;
                                                $total_paymet_amount += $agent_res->paid_amount;
                                                $total_remaning_amount += $agent_res->remaing_amount;
                                                $total_over_paid = $agent_res->over_paid_amount;
                                            ?>
                                        @endforeach
                                        
                                        @foreach($agents_data->agents_invoices_booking as $agent_res)
                                            <tr role="row" id="AI_Div" style="display:none">
                                                <td>Agent Id : <span class="text-success fw-semibold">{{ $agents_data->agent_id }}</span><br>
                                                    Company Name : <span class="text-success fw-semibold">{{ $agents_data->agent_company }}</span><br>
                                                    Agent Name : <span class="text-success fw-semibold">{{ $agents_data->agent_name }}</span><br>
                                                    Agent Invoice No : <span class="text-success fw-semibold">{{ $agent_res->invoice_id }}</span><br>
                                                    Type : <span class="text-success fw-semibold">Invoice</span><br>
                                                </td>
                                                <td>Total : <span class="badge bg-success-lighten text-success">{{ $agent_res->price }}</span><br>
                                                    Paid : <span class="badge bg-success-lighten text-success">{{ $agent_res->paid_amount }}</span><br>
                                                    Remainig : <span class="badge bg-success-lighten text-success">@if($agent_res->remaing_amount >= 0) {{ $agent_res->remaing_amount }} @endif</span><br>
                                                    Over Paid : <span class="badge bg-success-lighten text-success">{{ $agent_res->over_paid_amount }}</span><br>
                                                </td>
                                                <td>0</td>
                                                <td>Profit : <span class="badge bg-success-lighten text-success">{{ $agent_res->profit }}</span><br>
                                                    Cost : <span class="badge bg-success-lighten text-success">{{ $agent_res->total_cost }}</span><br>
                                                    Sale : <span class="badge bg-success-lighten text-success">{{ $agent_res->total_sale }}</span><br>
                                                </td>
                                                <td>...</td>
                                            </tr>
                                            <?php 
                                                $total_sale += $agent_res->price;
                                                $total_paymet_amount += $agent_res->paid_amount;
                                                $total_remaning_amount += $agent_res->remaing_amount;
                                            ?>
                                        @endforeach
                                    @endisset()
                                    
                                </tbody>
                                
                                <tbody id="AA_Div" style="display:none">
                                    <input type="hidden" value="0" id="switch_AA">
                                    @isset($agents_data)        
                                        <?php
                                            $total_sale = 0;
                                            $total_paymet_amount = 0;
                                            $total_remaning_amount = 0;
                                            $total_over_paid = 0;
                                        ?>
                                        @foreach($agents_data->invoice_Acc_details as $agent_res)
                                            <tr role="row">
                                                <td>Agent Id : <span class="text-success fw-semibold">{{ $agents_data->agent_id }}</span><br>
                                                    Company Name : <span class="text-success fw-semibold">{{ $agents_data->agent_company }}</span><br>
                                                    Agent Name : <span class="text-success fw-semibold">{{ $agents_data->agent_name }}</span><br>
                                                    Agent Invoice No : <span class="text-success fw-semibold">{{ $agent_res->invoice_id }}</span><br>
                                                    Type : <span class="text-success fw-semibold">Invoice</span><br>
                                                </td>
                                                <td>Total : <span class="badge bg-success-lighten text-success">{{ $agent_res->price }}</span><br>
                                                    Paid : <span class="badge bg-success-lighten text-success">{{ $agent_res->paid_amount }}</span><br>
                                                    Remainig : <span class="badge bg-success-lighten text-success">@if($agent_res->remaing_amount >= 0) {{ $agent_res->remaing_amount }} @endif</span><br>
                                                    Over Paid : <span class="badge bg-success-lighten text-success">{{ $agent_res->over_paid_amount }}</span><br>
                                                </td>
                                                <td>0</td>
                                                <td>Profit : <span class="badge bg-success-lighten text-success">{{ $agent_res->profit }}</span><br>
                                                    Cost : <span class="badge bg-success-lighten text-success">{{ $agent_res->total_cost }}</span><br>
                                                    Sale : <span class="badge bg-success-lighten text-success">{{ $agent_res->total_sale }}</span><br>
                                                </td>
                                                <td>
                                                    <a href="{{ URL::to('invoice_Agent/'.$agent_res->invoice_id) }}" target="blank" class="btn btn-primary btn-sm">view Invoice</a>
                                                </td>
                                            </tr>
                                            <?php 
                                                $total_sale += $agent_res->price;
                                                $total_paymet_amount += $agent_res->paid_amount;
                                                $total_remaning_amount += $agent_res->remaing_amount;
                                            ?>
                                        @endforeach
                                    @endisset()
                                    
                                </tbody>
                                
                                <tbody id="AF_Div" style="display:none">
                                    <input type="hidden" value="0" id="switch_AF">
                                    @isset($agents_data)        
                                        <?php
                                            $total_sale = 0;
                                            $total_paymet_amount = 0;
                                            $total_remaning_amount = 0;
                                            $total_over_paid = 0;
                                        ?>
                                        @foreach($agents_data->invoice_Flight_details as $agent_res)
                                            <tr role="row">
                                                <td>Agent Id : <span class="text-success fw-semibold">{{ $agents_data->agent_id }}</span><br>
                                                    Company Name : <span class="text-success fw-semibold">{{ $agents_data->agent_company }}</span><br>
                                                    Agent Name : <span class="text-success fw-semibold">{{ $agents_data->agent_name }}</span><br>
                                                    Agent Invoice No : <span class="text-success fw-semibold">{{ $agent_res->invoice_id }}</span><br>
                                                    Type : <span class="text-success fw-semibold">Invoice</span><br>
                                                </td>
                                                <td>Total : <span class="badge bg-success-lighten text-success">{{ $agent_res->price }}</span><br>
                                                    Paid : <span class="badge bg-success-lighten text-success">{{ $agent_res->paid_amount }}</span><br>
                                                    Remainig : <span class="badge bg-success-lighten text-success">@if($agent_res->remaing_amount >= 0) {{ $agent_res->remaing_amount }} @endif</span><br>
                                                    Over Paid : <span class="badge bg-success-lighten text-success">{{ $agent_res->over_paid_amount }}</span><br>
                                                </td>
                                                <td>0</td>
                                                <td>Profit : <span class="badge bg-success-lighten text-success">{{ $agent_res->profit }}</span><br>
                                                    Cost : <span class="badge bg-success-lighten text-success">{{ $agent_res->total_cost }}</span><br>
                                                    Sale : <span class="badge bg-success-lighten text-success">{{ $agent_res->total_sale }}</span><br>
                                                </td>
                                                <td>
                                                    <a href="{{ URL::to('invoice_Agent/'.$agent_res->invoice_id) }}" target="blank" class="btn btn-primary btn-sm">view Invoice</a>
                                                </td>
                                            </tr>
                                            <?php 
                                                $total_sale += $agent_res->price;
                                                $total_paymet_amount += $agent_res->paid_amount;
                                                $total_remaning_amount += $agent_res->remaing_amount;
                                            ?>
                                        @endforeach
                                    @endisset()
                                    
                                </tbody>
                                
                                <tbody id="AV_Div" style="display:none">
                                    <input type="hidden" value="0" id="switch_AV">
                                    @isset($agents_data)        
                                        <?php
                                            $total_sale = 0;
                                            $total_paymet_amount = 0;
                                            $total_remaning_amount = 0;
                                            $total_over_paid = 0;
                                        ?>
                                        
                                        @foreach($agents_data->invoice_Visa_details as $agent_res)
                                            <tr role="row">
                                                <td>Agent Id : <span class="text-success fw-semibold">{{ $agents_data->agent_id }}</span><br>
                                                    Company Name : <span class="text-success fw-semibold">{{ $agents_data->agent_company }}</span><br>
                                                    Agent Name : <span class="text-success fw-semibold">{{ $agents_data->agent_name }}</span><br>
                                                    Agent Invoice No : <span class="text-success fw-semibold">{{ $agent_res->invoice_id }}</span><br>
                                                    Type : <span class="text-success fw-semibold">Invoice</span><br>
                                                </td>
                                                <td>Total : <span class="badge bg-success-lighten text-success">{{ $agent_res->price }}</span><br>
                                                    Paid : <span class="badge bg-success-lighten text-success">{{ $agent_res->paid_amount }}</span><br>
                                                    Remainig : <span class="badge bg-success-lighten text-success">@if($agent_res->remaing_amount >= 0) {{ $agent_res->remaing_amount }} @endif</span><br>
                                                    Over Paid : <span class="badge bg-success-lighten text-success">{{ $agent_res->over_paid_amount }}</span><br>
                                                </td>
                                                <td>0</td>
                                                <td>Profit : <span class="badge bg-success-lighten text-success">{{ $agent_res->profit }}</span><br>
                                                    Cost : <span class="badge bg-success-lighten text-success">{{ $agent_res->total_cost }}</span><br>
                                                    Sale : <span class="badge bg-success-lighten text-success">{{ $agent_res->total_sale }}</span><br>
                                                </td>
                                                <td>
                                                    <a href="{{ URL::to('invoice_Agent/'.$agent_res->invoice_id) }}" target="blank" class="btn btn-primary btn-sm">view Invoice</a>
                                                </td>
                                            </tr>
                                            <?php 
                                                $total_sale += $agent_res->price;
                                                $total_paymet_amount += $agent_res->paid_amount;
                                                $total_remaning_amount += $agent_res->remaing_amount;
                                            ?>
                                        @endforeach
                                    @endisset()
                                    
                                </tbody>
                                
                                <tbody id="AT_Div" style="display:none">
                                    <input type="hidden" value="0" id="switch_AT">
                                    @isset($agents_data)        
                                        <?php
                                            $total_sale = 0;
                                            $total_paymet_amount = 0;
                                            $total_remaning_amount = 0;
                                            $total_over_paid = 0;
                                        ?>
                                        
                                        @foreach($agents_data->invoice_Transportation_details as $agent_res)
                                            <tr role="row">
                                                <td>Agent Id : <span class="text-success fw-semibold">{{ $agents_data->agent_id }}</span><br>
                                                    Company Name : <span class="text-success fw-semibold">{{ $agents_data->agent_company }}</span><br>
                                                    Agent Name : <span class="text-success fw-semibold">{{ $agents_data->agent_name }}</span><br>
                                                    Agent Invoice No : <span class="text-success fw-semibold">{{ $agent_res->invoice_id }}</span><br>
                                                    Type : <span class="text-success fw-semibold">Invoice</span><br>
                                                </td>
                                                <td>Total : <span class="badge bg-success-lighten text-success">{{ $agent_res->price }}</span><br>
                                                    Paid : <span class="badge bg-success-lighten text-success">{{ $agent_res->paid_amount }}</span><br>
                                                    Remainig : <span class="badge bg-success-lighten text-success">@if($agent_res->remaing_amount >= 0) {{ $agent_res->remaing_amount }} @endif</span><br>
                                                    Over Paid : <span class="badge bg-success-lighten text-success">{{ $agent_res->over_paid_amount }}</span><br>
                                                </td>
                                                <td>0</td>
                                                <td>Profit : <span class="badge bg-success-lighten text-success">{{ $agent_res->profit }}</span><br>
                                                    Cost : <span class="badge bg-success-lighten text-success">{{ $agent_res->total_cost }}</span><br>
                                                    Sale : <span class="badge bg-success-lighten text-success">{{ $agent_res->total_sale }}</span><br>
                                                </td>
                                                <td>
                                                    <a href="{{ URL::to('invoice_Agent/'.$agent_res->invoice_id) }}" target="blank" class="btn btn-primary btn-sm">view Invoice</a>
                                                </td>
                                            </tr>
                                            <?php 
                                                $total_sale += $agent_res->price;
                                                $total_paymet_amount += $agent_res->paid_amount;
                                                $total_remaning_amount += $agent_res->remaing_amount;
                                            ?>
                                        @endforeach
                                    @endisset()
                                    
                                </tbody>
                                
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header " style="display:flex; justify-content: space-between;">
                  <h1 class="modal-title fs-5" id="exampleModalLabel">Manage Over Paid</h1>
                    <div>
                        <button class="btn btn-info btn-sm" onclick="manageBalance()">Manage Balance</button>
                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
               
              </div>
              <div class="modal-body">
                    <div id="example_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                        <div class="row">
                            <div class="col-sm-12">
                                <table style="" class="table nowrap example1 dataTable no-footer" id="myTable" role="grid" aria-describedby="example_info">
                                    <thead>
                                        <tr role="row">
                                            <th>Sr</th>
                                            <th>Invoice No</th>
                                            <th>Total Amount</th>
                                            <th>Paid Amount</th>
                                            <th>Remaning Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;" id="unpaid_inv">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
              </div>
            </div>
          </div>
        </div>
        
        <div class="modal fade" id="paid_amount" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                 <div > 
                   
                    <div style="display:flex;">
                         <h1 class="modal-title fs-5" id="exampleModalLabel">Manage Over Paid</h1>
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Available Balance:{{ $agents_data->agent_over_paid }}</h1>
                        
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <form action="{{ URL::to('adjust_over_pay')}}" method="post">
                        @csrf
                        
                     
                        
                        <div class="mb-2">
                          <label for="date" class="form-label">Date</label>
                          <input value="{{ now()->format('Y-m-d') }}" name="date" class="form-control" type="date" id="date" required="">
                        </div>

                        

                        <div class="mb-2">
                            <label for="total_Amount" class="form-label">Total Amount</label>
                            <input readonly name="total_Amount" value="" class="form-control" id="total_amount" type="text" id="total_amount" required="">
                        </div>

                        <div class="mb-2">
                            <label for="recieved_Amount" class="form-label">Adjust Amount</label>
                            <input name="recieved_Amount" class="form-control" type="number" id="recieved_Amount" max="{{ $agents_data->agent_over_paid }}" required="" placeholder="Recieved Amount">
                        </div>

                     

                        <div class="mb-2">
                            <label for="amount_Paid" class="form-label">Amount Paid</label>
                           
                                <input readonly value="" name="amount_Paid" class="form-control" type="text" id="paid_amount_val" required="">
                                <input readonly value="" name="paid_type" class="form-control" hidden type="text" id="paid_type" required="">
                                <input readonly value="" name="invoice_no" class="form-control" hidden type="text" id="invoice_no" required="">
                            
                        </div>

                        <div style="padding: 10px 0px 10px 0px;">
                            <button style="padding: 10px 30px 10px 30px;" type="submit" class="btn btn-primary" ><i class="mdi mdi-send me-1"></i>Recieve</button>
                            <button style="margin-left: 5px;padding: 10px 30px 10px 30px;" type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
              </div>
              
            </div>
          </div>
        </div>
        
        <div class="modal fade" id="manage_balance" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                 <div > 
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Manage Balance</h1>
                    
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form action="{{ URL::to('manage_wallent_balance') }}" method="post" enctype="multipart/form-data">
                  @csrf
                    <div class="modal-body">
                          
                   
                      <div class="row">
                        <div class="col-12 mb-2">
                            <input type="text" name="agent_id" hidden value="{{ $agents_data->agent_id }}">
                            <label>Select Transcation Type</label>
                            <select class="form-control" id="" name="transtype">
                                <option value="Refund">Refund</option>
                                <option value="Deposit">Deposit</option>
                            </select>
                        </div>
                         <div class="col-6 mb-2">
                            <label>Payment Method</label>
                            <select class="form-control" onchange="paymentMethod()" id="payment_method" name="payment_method">
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Cash">Cash</option>
                                <option value="Card Payment">Card Payment</option>
                            </select>
                        </div>
                        <div class="col-6 mb-2">
                             <label>Payment Date</label>
                             <input type="date" class="form-control" required="" value="2022-12-14" name="payment_date" placeholder="Enter Payment Amount">
                        </div>
                        
                        <div class="col-6 mb-2">
                             <label>Payment Amount</label>
                             <input type="text" class="form-control" required="" name="payment_am" placeholder="Enter Payment Amount">
                        </div>
                        
                      
                        
                        <div class="col-6 mb-2" id="transcation_id" style="display: block;">
                            <label>Transaction ID</label>
                          <input type="text" class="form-control" name="transcation_id" placeholder="Enter Transaction ID">
                          <input type="text" class="form-control" required="" name="invoice_id" hidden="" value="AHT3383261" placeholder="Enter Transaction ID">
                        </div>
                        
                       <div class="col-6 mb-2" id="account_id" style="display: block;">
                           <label>Account No.</label>
                          <input type="text" class="form-control" name="account_no" placeholder="Payment to (Account No.)" value="13785580">
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
        
    </div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  
    <script>
     <?php 
                                                            //  $total_sale += $agent_res->price;
                                                            //  $total_paymet_amount += $agent_res->paid_amount;
                                                            //  $total_remaning_amount += $agent_res->remaing_amount;
                                                            // //  $total_over_paid = $agent_res->over_paid_amount;
                                                            ?>
                                                            
        function paid_amount(id,type,total,paid){
            $('#paid_amount').modal('show');
            $('#total_amount').val(total);
            $('#paid_amount_val').val(paid);
            $('#paid_type').val(type);
            $('#invoice_no').val(id);
            
        }
        
         function manageBalance(){
            $('#manage_balance').modal('show');
          
            
        }
       
       
         function paymentMethod(){
             var paymentMethod = $('#payment_method').val();
             if(paymentMethod == 'Cash'){
                 $('#transcation_id').css('display','none');
                 $('#account_id').css('display','none');
             }else{
                  $('#transcation_id').css('display','block');
                  $('#account_id').css('display','block');
             }
         }
                                                            
        function manageOverPaid(invoiceNum){
             $('#total_amount').val(0);
            $('#paid_amount_val').val(0);
             $('#unpaid_inv').html('');            
            console.log('Invoice Number is '+invoiceNum);
            
              $.ajax({
                    url: '{{ URL::to("getupaidInvoicesAgent") }}',
                    type: 'POST',
                    data: {
                        "_token":'{{ CSRF_token() }}',
                        "id": invoiceNum
                    },
                    success:function(data) {
                        var packInvList = JSON.parse(data);
                        
                         var packageTr = ``;
                         
                         var x = 1;
                         packInvList['package_inv_lists'].forEach((obj)=>{
                             var remainingVal = obj['tour_total_price'] - obj['total_paid_amount'];
                             packageTr += `<tr>
                                                <td>${x++}</td>
                                                <td>${obj['invoice_no']}
                                                <br> Type:Package
                                                </td>
                                                <td>${obj['tour_total_price']}</td>
                                                <td>${obj['total_paid_amount']}</td>
                                                <td>${remainingVal}</td>
                                                <td><button class="btn btn-primary" onclick="paid_amount('${obj['invoice_no']}','package',${obj['tour_total_price']},${obj['total_paid_amount']})">Pay Amount</button></td>
                                            </tr>`
                         })
                            
                        packInvList['inv_lists'].forEach((obj)=>{
                             var remainingVal = obj['total_sale_price_all_Services'] - obj['total_paid_amount'];
                             packageTr += `<tr>
                                                <td>${x++}</td>
                                                <td>${obj['id']}
                                                <br> Type:Invoice
                                                </td>
                                                <td>${obj['total_sale_price_all_Services']}</td>
                                                <td>${obj['total_paid_amount']}</td>
                                                <td>${remainingVal}</td>
                                                <td><button class="btn btn-primary" onclick="paid_amount('${obj['id']}','Invoice',${obj['total_sale_price_all_Services']},${obj['total_paid_amount']})">Pay Amount</button></td>
                                            </tr>`
                         })
                            $('#unpaid_inv').html(packageTr);            
                                        
                        console.log(packInvList);
                    }
              })
        }
        var totalSale = '<?php echo $total_sale ?>';
        var totalPaid = '<?php echo $total_paymet_amount ?>';
        var totalRemaning = '<?php echo $total_remaning_amount ?>';
        var totalOverpaid = '<?php echo $total_over_paid ?>';
        
        $('#total_revenue').html(totalSale);
        $('#total_revenue1').html(totalSale);
        $('#total_paid').html(totalPaid);
        $('#total_paid1').html(totalPaid);
        $('#outstanding').html(totalRemaning);
        $('#outstanding1').html(totalRemaning);
        $('#over_paid').html(totalSale);
        $('#over_paid1').html(totalSale);
    
        $(document).ready(function () {
          
            //DataTable
            $('#myTable').DataTable({
                scrollX: true,
            });
            
            //View Modal Single Quotation
            $('.detail-btn').click(function() {
                const id = $(this).attr('data-id');
                $.ajax({
                    url: 'view_QuotationsID/'+id,
                    type: 'GET',
                    data: {
                        "id": id
                    },
                    success:function(data) {
                        var a                = data['a'];
                        var roundTripDetails = a['roundTripDetails'];
                        var oneWayDetails    = a['oneWayDetails'];
                        
                        console.log(a);
            
                        //Flight Details
                        $('#airline_name').html(oneWayDetails['airline_name']);
                        $('#deprturetime').html(oneWayDetails['deprturetime']);
                        $('#ArrivalTime').html(oneWayDetails['ArrivalTime']);
                        $('#deprturedate').html(oneWayDetails['deprturedate']);
                        $('#ArrivalDate').html(oneWayDetails['ArrivalDate']);
                        $('#departure').html(oneWayDetails['departure']);
                        $('#arrival').html(oneWayDetails['arrival']);
                        
                        $('#flighttype').html(a['flighttype']);
                        $('#flight_price').html(a['flight_price']);
                        
                        //Hotel Booking Details Makkkah
                        $('#dateinmakkah').html(a['dateinmakkah']);
                        $('#dateoutmakkah').html(a['dateoutmakkah']);
                        $('#hotel_name_makkah').html(a['hotel_name_makkah']);
                        $('#no_of_rooms_makkah').html(a['no_of_rooms_makkah']);
                        $('#Price_Per_Nights_Makkah').html(a['Price_Per_Nights_Makkah']);
                        $('#Makkah_total_price_cal').html(a['Makkah_total_price_cal']);
                        
                        //Hotel Booking Details Madinah
                        $('#dateinmadinah').html(a['dateinmadinah']);
                        $('#dateoutmadinah').html(a['dateoutmadinah']);
                        $('#hotel_name_madina').html(a['hotel_name_madina']);
                        $('#no_of_rooms_madina').html(a['no_of_rooms_madina']);
                        $('#price_per_night_madinah').html(a['price_per_night_madinah']);
                        $('#madinah_total_price_cal').html(a['madinah_total_price_cal']);
                        
                        
                        //Transfer Details
                        $('#transfer_vehicle').html(a['transfer_vehicle']);
                        $('#passenger').html(a['passenger']);
                        $('#pickuplocat').html(a['pickuplocat']);
                        $('#dropoflocat').html(a['dropoflocat']);
                        $('#trans_date').html(a['trans_date']);
                        $('#transf_price').html(a['transf_price']);
                        
                        //Visa Details
                        $('#visa_fees_adult').html(a['visa_fees_adult']);
                        $('#visa_fees_child').html(a['visa_fees_child']);
                        $('#visa_fees_price').html(a['visa_fees_price']);
                        
                        //Totals
                        $('#flight_price_total').html(a['flight_price']);
                        $('#Makkah_total_price_cal').html(a['Makkah_total_price_cal']);
                        $('#madinah_total_price_cal').html(a['madinah_total_price_cal']);
                        $('#transfers_head_total').html(a['transfers_head_total']);
                        $('#visa_fees').html(a['visa_fees_price']);
                        $('#grand_total_price').html(a['grand_total_price']);
            
                    }
                })
            });
    
        });

    </script>
    
    <script>
    
        function add_more_passenger(id){
            $('#add_more_P_div').empty();
            $('#invoice_Id_Input').val();
            $('#lead_name_ID').empty();
            $('#no_of_pax_days_ID').empty();
            $('#no_of_pax_days_Input').val();
            $('#add_more_P').css('display','');
            $('#more_P_D_div').empty();
            let I_id = id;
            $.ajax({
                url:"{{URL::to('get_single_Invoice')}}" + '/' + I_id,
                method: "GET",
                data: {
                	I_id:I_id
                },
                success:function(data){
                    var data1                   = data['data'];
                    var invoice_Id              = data1['id'];
                    var f_name                  = data1['f_name'];
                    var middle_name             = data1['middle_name'];
                    var no_of_pax_days          = data1['no_of_pax_days'];
                    $('#invoice_Id_Input').val(invoice_Id);         	  
                    $('#lead_name_ID').html(f_name+' '+middle_name);
                    $('#no_of_pax_days_ID').html(no_of_pax_days);
                    $('#no_of_pax_days_Input').val(no_of_pax_days);
                    
                    var count_P_Input = data1['count_P_Input'];
                    if(count_P_Input > 0 && count_P_Input != null && count_P_Input != ''){
                        $('#count_P_Input').val(count_P_Input);
                        $('#count_P_Input1').val(count_P_Input);
                    }else{
                        $('#count_P_Input').val(1);
                        $('#count_P_Input1').val(1);
                    }
                    
                    var more_Passenger_Data = data1['more_Passenger_Data'];
                    if(more_Passenger_Data != null && more_Passenger_Data != ''){
                        $('#more_Passenger_Data_Input').val(more_Passenger_Data);
                        var edit_M_P_Button = `<button class="btn btn-primary" id="edit_more_P" onclick="edit_more_P()" type="button">Edit more Passenger</button>`;
                        // $('#edit_M_P_Button').append(edit_M_P_Button);
                        
                        var more_Passenger_Data_decode = JSON.parse(data1['more_Passenger_Data']);
                        $.each(more_Passenger_Data_decode, function (key, value) {
                            var more_p_fname    = value.more_p_fname;
                            var more_p_lname    = value.more_p_lname;
                            var more_p_gender   = value.more_p_gender;
                            var more_p_passport = value.more_p_passport;
                            var more_p_image    = value.more_p_image;
                            var url_IandP       = `https://alhijaztours.net/public/uploads/package_imgs/`;
                            
                            var data =  `<div class="row" id="main_div_P_${invoice_Id}">
                                            <div class="col-lg-12">
                                                <h5>More Passenger details</h5>
                                            </div>
                                            <div class="col-lg-2 mb-3">
                                                <label for="label" class="form-label">First Name</label>
                                                <input class="form-control remove_readonly_prop" type="text" value="${more_p_fname}" name="" placeholder="" readonly>
                                            </div>
                                            <div class="col-lg-2 mb-3">
                                                <label for="label" class="form-label">Last Name</label>
                                                <input class="form-control remove_readonly_prop" type="text" value="${more_p_lname}" name="" placeholder="" readonly>
                                            </div>
                                            <div class="col-lg-2 mb-3">
                                                <label for="label" class="form-label">Gender</label>
                                                <select class="form-control" name="" readonly>`;
                                                    if(more_p_gender == 'Male'){
                            data +=                     `<option value="Male" selected>Male</option>
                                                        <option value="Female">Female</option>`;
                                                    }
                                                    else if(more_p_gender == 'Female'){
                            data +=                     `<option value="Male">Male</option>
                                                        <option value="Female" selected>Female</option>`;
                                                    }
                                                    else{
                            data +=                     `<option value="">Choose...</option>
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>`;
                                                    }
                            data +=             `</select>
                                            </div>
                                            <div class="col-lg-3 mb-3">
                                                <label for="image" class="form-label">Passport</label>
                                                <input class="form-control" type="text" value="${more_p_passport}" name="" readonly>
                                                <input class="form-control" type="file" value="${more_p_passport}" name="" hidden>
                                            </div>
                                            <div class="col-lg-3 mb-3">
                                                <label for="image" class="form-label">Image</label>
                                                <img src="${url_IandP}${more_p_image}" style="height: 80px;width: 80px;">
                                                <input class="form-control" type="text" name="" readonly hidden>
                                                <input class="form-control" type="file" name="" readonly hidden>
                                            </div>
                                        </div>`;
                            
                            if(more_p_fname != null){
                                $('#more_P_D_div').append(data);
                            }
                        });
                    }
                    
                    if(no_of_pax_days = count_P_Input){
                        $('#edit_M_P_Button').css('display','none')
                    }
                }
            }); 
        }
        
        $('#add_more_P').click(function() {
            $('#button_P').css('display','');
            
            var count_P = $('#count_P_Input').val();
            count_P     = parseFloat(count_P) + 1;
            
            var no_of_pax_days_Input    = $('#no_of_pax_days_Input').val();
            var id_P                    = $('#invoice_Id_Input').val();
            var data =  `<div class="row" id="main_div_P_${id_P}${count_P}">
                            <div class="col-lg-12">
                                <h5>More Passenger # ${count_P}</h5>
                            </div>
                            <div class="col-lg-2 mb-3">
                                <label for="label" class="form-label">First Name</label>
                                <input class="form-control" type="text" id="more_p_fname${id_P}_${count_P}" name="more_p_fname[]" placeholder="" required>
                            </div>
                            <div class="col-lg-2 mb-3">
                                <label for="label" class="form-label">Last Name</label>
                                <input class="form-control" type="text" id="more_p_lname${id_P}_${count_P}" name="more_p_lname[]" placeholder="" required>
                            </div>
                            <div class="col-lg-2 mb-3">
                                <label for="label" class="form-label">Gender</label>
                                <select class="form-control" id="more_p_gender${id_P}_${count_P}" name="more_p_gender[]" placeholder="Choose..." required>
                                    <option value="">Select</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <label for="image" class="form-label">Passport</label>
                                <input class="form-control" type="file" id="more_p_passport${id_P}_${count_P}" name="more_p_passport[]" placeholder="" required>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input class="form-control" type="file" id="more_p_image${id_P}_${count_P}" name="more_p_image[]" placeholder="" required>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary" id="remove_more_P" onclick="removeMorePassenger(${id_P}${count_P})" type="button">Remove</button>
                            </div>
                        </div>`;
                        
            if(count_P <= no_of_pax_days_Input){
                $('#add_more_P_div').append(data);
                $('#count_P_Input').val(count_P);
            }else{
                $('#add_more_P').css('display','none');
                count_P = count_P - 1;
                $('#count_P_Input').val(count_P);
            }
        });
        
        function removeMorePassenger(id){
            $('#main_div_P_'+id+'').remove();
            var no_of_pax_days_Input    = $('#no_of_pax_days_Input').val();
            var count_P                 = $('#count_P_Input').val();
            var count_P_Input1          = $('#count_P_Input1').val();
            count_P                     = count_P - 1;
            $('#count_P_Input').val(count_P);
            if(count_P <= no_of_pax_days_Input){
                $('#add_more_P').css('display','');
            }
            
            if(count_P_Input = count_P_Input1){
                $('#button_P').css('display','none');
            }
        }
        
    </script>
    
    <script>
        
        $('#APB_ID').click(function() {
            var switch_APB = $('#switch_APB').val();
            if(switch_APB == 0){
                $('#APB_Div').css('display','');
                $('#switch_APB').val(1);
                $('#transaction_list_id').html('Package Bookings Transaction List');
                
                $('#AI_Div').css('display','none');
                $('#switch_AI').val(0);
                $('#AA_Div').css('display','none');
                $('#switch_AA').val(0);
                $('#AF_Div').css('display','none');
                $('#switch_AF').val(0);
                $('#AV_Div').css('display','none');
                $('#switch_AV').val(0);
                $('#AT_Div').css('display','none');
                $('#switch_AT').val(0);
            }else{
                $('#APB_Div').css('display','none');
                $('#switch_APB').val(0);
            }
        });
        
        $('#AI_ID').click(function() {
            var switch_AI = $('#switch_AI').val();
            if(switch_AI == 0){
                $('#AI_Div').css('display','');
                $('#switch_AI').val(1);
                
                $('#APB_Div').css('display','none');
                $('#switch_APB').val(0);
            }else{
                $('#AI_Div').css('display','none');
                $('#switch_AI').val(0);
            }
        });
        
        $('#AA_ID').click(function() {
            var switch_AA = $('#switch_AA').val();
            if(switch_AA == 0){
                $('#AA_Div').css('display','');
                $('#switch_AA').val(1);
                $('#transaction_list_id').html('Accomodation Transaction List');
                
                $('#APB_Div').css('display','none');
                $('#switch_APB').val(0);
                $('#AF_Div').css('display','none');
                $('#switch_AF').val(0);
                $('#AV_Div').css('display','none');
                $('#switch_AV').val(0);
                $('#AT_Div').css('display','none');
                $('#switch_AT').val(0);
            }else{
                $('#AA_Div').css('display','none');
                $('#switch_AA').val(0);
            }
        });
        
        $('#AF_ID').click(function() {
            var switch_AF = $('#switch_AF').val();
            if(switch_AF == 0){
                $('#AF_Div').css('display','');
                $('#switch_AF').val(1);
                $('#transaction_list_id').html('Flight Transaction List');
                
                $('#APB_Div').css('display','none');
                $('#switch_APB').val(0);
                $('#AA_Div').css('display','none');
                $('#switch_AA').val(0);
                $('#AV_Div').css('display','none');
                $('#switch_AV').val(0);
                $('#AT_Div').css('display','none');
                $('#switch_AT').val(0);
            }else{
                $('#AF_Div').css('display','none');
                $('#switch_AF').val(0);
            }
        });
        
        $('#AV_ID').click(function() {
            var switch_AV = $('#switch_AV').val();
            if(switch_AV == 0){
                $('#AV_Div').css('display','');
                $('#switch_AV').val(1);
                $('#transaction_list_id').html('Visa Transaction List');
                
                $('#APB_Div').css('display','none');
                $('#switch_APB').val(0);
                $('#AF_Div').css('display','none');
                $('#switch_AF').val(0);
                $('#AA_Div').css('display','none');
                $('#switch_AA').val(0);
                $('#AT_Div').css('display','none');
                $('#switch_AT').val(0);
            }else{
                $('#AV_Div').css('display','none');
                $('#switch_AV').val(0);
            }
        });
        
        $('#AT_ID').click(function() {
            var switch_AT = $('#switch_AT').val();
            if(switch_AT == 0){
                $('#AT_Div').css('display','');
                $('#switch_AT').val(1);
                $('#transaction_list_id').html('Transportation Transaction List');
                
                $('#APB_Div').css('display','none');
                $('#switch_APB').val(0);
                $('#AF_Div').css('display','none');
                $('#switch_AF').val(0);
                $('#AV_Div').css('display','none');
                $('#switch_AV').val(0);
                $('#AA_Div').css('display','none');
                $('#switch_AA').val(0);
            }else{
                $('#AT_Div').css('display','none');
                $('#switch_AT').val(0);
            }
        });
        
    </script>

@endsection
