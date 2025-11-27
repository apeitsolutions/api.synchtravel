<!DOCTYPE html>
    <html lang="en">

    

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        
        <title>Dashboard | Super Admin Dashboard</title>
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('public/admin_package/assets/images/favicon.ico')}}">
        
        <link href="{{asset('public/admin_package/assets/css/vendor/quill.core.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/admin_package/assets/css/vendor/quill.snow.css')}}" rel="stylesheet" type="text/css" />
        
        <!-- third party css -->
        <link href="{{asset('public/admin_package/assets/css/vendor/jquery-jvectormap-1.2.2.css')}}" rel="stylesheet" type="text/css" />
        <!-- third party css end -->
        
        <!-- App css -->
        <link href="{{asset('public/admin_package/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/admin_package/assets/css/app.min.css')}}" rel="stylesheet" type="text/css" id="app-style"/>
        
        <!--New Links-->
        <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
        <link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
        <link href="{{asset('public/admin_package/font/font-awesome.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/admin_package/font/icomoon.css')}}" rel="stylesheet" type="text/css" />
        <link href="https://transloadit.edgly.net/releases/uppy/v1.6.0/uppy.min.css" rel="stylesheet">
        <link href="{{asset('public/admin_package/assets/css/vendor/quill.bubble.css')}}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs5.min.css" integrity="sha512-ngQ4IGzHQ3s/Hh8kMyG4FC74wzitukRMIcTOoKT3EyzFZCILOPF0twiXOQn75eDINUfKBYmzYn2AA8DkAk8veQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <!--New Links-->
        
        <style>
            .round-icon {
                font-style          : initial;
                display             : inline-block;
                width               : 20px;
                height              : 20px;
                border-radius       : 50%;
                background-color    : red;
                text-align          : center;
                font-size           : 15px;
                color               : white;
            }
            
            .round-icon1 {
                font-style          : initial;
                display             : inline-block;
                width               : 20px;
                height              : 20px;
                border-radius       : 50%;
                background-color    : #3abe3a;
                text-align          : center;
                font-size           : 15px;
                color               : white;
            }
            
            body[data-leftbar-theme="light"] .side-nav .side-nav-link {
                color: var(--ct-menu-item);
                border-bottom: 1px solid #ddd;
            }
            
        </style>
        
    </head>

    <body class="loading" data-layout-color="light" data-leftbar-theme="light" data-layout-mode="fluid" data-rightbar-onstart="true"
        {{ Session::has('profit_percentage') ? 'profitpercentage' : '' }} data-profitpercentagemessage='{{ json_encode(Session::get('profit_percentage')) }}'
        {{ Session::has('expense_percentage') ? 'expensepercentage' : '' }} data-expensepercentagemessage='{{ json_encode(Session::get('expense_percentage')) }}'>
        
        <!-- Begin page -->
        <div class="wrapper">
            <!-- ========== Left Sidebar Start ========== -->
            <div class="leftside-menu">
                
                <!-- LOGO -->
                <a href="{{URL::to('super_admin')}}" class="logo text-center logo-light" style="background-color:white;">
                    <span class="logo-lg">
                        <img src="{{asset('public/admin_package/assets/images/logo1-1.png')}}" alt="" height="70">
                    </span>
                    <span class="logo-sm">
                        <img src="{{asset('public/admin_package/assets/images/logo1-1.png')}}" alt="" height="70">
                    </span>
                </a>
                
                <!-- LOGO -->
                <a href="index.html" class="logo text-center logo-dark">
                    <span class="logo-lg">
                        <!--<img src="{{asset('public/admin_package/assets/images/logo-dark.png')}}" alt="" height="16">-->
                        <img src="{{asset('public/admin_package/assets/images/logo1-1.png')}}" alt="" height="70px" width="70px">
                    </span>
                    <span class="logo-sm">
                        <img src="{{asset('public/admin_package/assets/images/logo_sm_dark.png')}}" alt="" height="16">
                    </span>
                </a>
                
                <div class="h-100" id="leftside-menu-container" data-simplebar>
                    
                    <!--- Sidemenu -->
                    <ul class="side-nav">
                        
                        <li class="side-nav-item">
                            <a href="{{URL::to('super_admin')}}"  class="side-nav-link">
                                <i class="uil-home-alt"></i>
                                <!-- <span class="badge bg-success float-end">4</span> -->
                                <span> Dashboards </span>
                            </a>
                           
                        </li>
                        
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#customerSubcription" aria-expanded="false" aria-controls="customerSubcription" class="side-nav-link">
                                <i class="uil-envelope"></i>
                                <span> Customer Subcription </span>
                                <span class="menu-arrow"></span>
                            </a>
                           
                            <div class="collapse" id="customerSubcription">
                                <ul class="side-nav-second-level">
                                    <li>
                                        <a href="{{URL::to('super_admin/customer_subcription')}}">Create Customer Subcription</a>
                                    </li>
                                    <li>
                                        <a href="{{URL::to('super_admin/view_customer_subcription')}}">View Customer Subcription</a>
                                    </li>
                                    <li>
                                        <a href="{{URL::to('super_admin/subcription_payments_request')}}">Customer Payments Request</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#payments_type" aria-expanded="false" aria-controls="customerSubcription" class="side-nav-link">
                                <i class="uil-envelope"></i>
                                <span> Payments Plans </span>
                                <span class="menu-arrow"></span>
                            </a>
                           
                            <div class="collapse" id="payments_type">
                                <ul class="side-nav-second-level">
                                    <li>
                                        <a href="{{URL::to('super_admin/payments_plan')}}">Payments Plans</a>
                                    </li>
                                   
                                </ul>
                            </div>
                        </li>
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#credit_type" aria-expanded="false" aria-controls="credit_type" class="side-nav-link">
                                <i class="uil-envelope"></i>
                                <span> Credit Limit </span>
                                <span class="menu-arrow"></span>
                            </a>
                           
                            <div class="collapse" id="credit_type">
                                <ul class="side-nav-second-level">
                                    <li>
                                        <a href="{{URL::to('super_admin/credit_limit')}}">Credit Limit</a>
                                    </li>
                                    <li>
                                        <a href="{{URL::to('super_admin/costomer_credit_history')}}">Credit Limit History</a>
                                    </li>
                                   
                                </ul>
                            </div>
                        </li>
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarEcommerce_booking" aria-expanded="false" aria-controls="sidebarEcommerce" class="side-nav-link">
                                <i class="uil-building"></i>
                                <span> Hotels Revenue </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarEcommerce_booking">
                                <ul class="side-nav-second-level">
                                    
                                     <li>
                                        <a href="{{ URL::to('super_admin/client/list') }}">Client List</a>
                                    </li>
                                    <li>
                                        <a href="{{ URL::to('super_admin/hotels/list') }}">Hotels Booking</a>
                                    </li>
                                    
                                     <li>
                                        <a href="{{ URL::to('super_admin/hotels/payment/history') }}">Hotel Payment History</a>
                                    </li>
                                   
                                   
                                </ul>
                            </div>
                        </li>
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarEcommerce_flight_booking" aria-expanded="false" aria-controls="sidebarEcommerce_flight_booking" class="side-nav-link">
                                <i class="uil-building"></i>
                                <span> Flight Revenue </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarEcommerce_flight_booking">
                                <ul class="side-nav-second-level">
                                    
                                     
                                    <li>
                                        <a href="{{ URL::to('super_admin/flight/booking/list') }}">Flight Booking</a>
                                    </li>
                                    
                                     <li>
                                        <a href="{{ URL::to('super_admin/flight/payment/history') }}">Flight Payment History</a>
                                    </li>
                                   
                                   
                                </ul>
                            </div>
                        </li>
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarCustomHotelProvider" aria-expanded="false" aria-controls="sidebarEcommerce" class="side-nav-link">
                                <i class="uil-building"></i>
                                <span> Custom Hotel Provider </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarCustomHotelProvider">
                                <ul class="side-nav-second-level">
                                    
                                     <li>
                                        <a href="{{ URL::to('super_admin/custom_hotel_provider') }}">Custom Hotel Provider</a>
                                    </li>
                                    <li>
                                        <a href="{{ URL::to('super_admin/provider_payment_request') }}">Payment Requests</a>
                                    </li>
                                    
                                   
                                   
                                </ul>
                            </div>
                        </li>
                        
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#hotel_providers" aria-expanded="false" aria-controls="sidebarEcommerce" class="side-nav-link">
                                <i class="uil-store"></i>
                                <span style="font-size: 14px;">3rd Party Hotel Provider</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="hotel_providers">
                                <ul class="side-nav-second-level">
                                    <li>
                                        <a href="{{URL::to('super_admin/third_party_hotels_providers_list')}}">Hotel Provider</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        
                        <!--Lead-->
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#lead_details" aria-expanded="false" aria-controls="sidebarEcommerce" class="side-nav-link">
                                <i class="uil-image-check"></i>
                                <span> Leads </span>
                                <span class="menu-arrow"></span>
                            </a> 
                            <div class="collapse" id="lead_details">
                                <ul class="side-nav-second-level">
                                    <div id="tooltip-container">
                                        <?php $lead_in_process = Session::get('lead_in_process'); ?>
                                        <li>
                                            <div class="row">
                                                <div class="col-lg-9">
                                                    <a href="{{ URL::to('view_Lead_Admin') }}" class="form-label">View Leads</a>
                                                </div>
                                                <div class="col-lg-3">
                                                    <a href="{{ URL::to('view_Lead_Process_Admin') }}" class="form-label" style="float: right;">
                                                        @if($lead_in_process > 0)
                                                            <i class="round-icon" data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Process Lead">{{ $lead_in_process }}</i>
                                                        @else
                                                            <i class="round-icon1" data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Process Lead">{{ $lead_in_process }}</i>
                                                        @endif
                                                    </a>
                                                </div>
                                            </div>
                                        </li>
                                    </div>
                                
                                    <li>
                                        <a href="{{ URL::to('view_manage_Lead_Quotation_Admin') }}">View Quotation</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!--Lead-->
                        
                        <!--Atol-->
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#atol_Details" aria-expanded="false" aria-controls="sidebarMultiLevel" class="side-nav-link collapsed">
                                <i class="uil-calendar-alt"></i>
                                <span> ATOL </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="atol_Details">
                                <ul class="side-nav-second-level">
                                    
                                    <li class="side-nav-item d-none">
                                        <a data-bs-toggle="collapse" href="#atol_Details_setting" aria-expanded="false" aria-controls="sidebarSecondLevel" class="collapsed">
                                            <span> Atol - Setting </span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <div class="collapse" id="atol_Details_setting">
                                            <ul class="side-nav-third-level">
                                                <li>
                                                    <a href="{{ URL::to('atol_Register') }}">Register</a>
                                                </li>
                                                <li>
                                                    <a href="{{ URL::to('atol_Flight_Package') }}">Flight and Package</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    
                                    <li class="side-nav-item">
                                        <a data-bs-toggle="collapse" href="#atol_Details_report" aria-expanded="false" aria-controls="sidebarSecondLevel" class="collapsed">
                                            <span> Atol - Report </span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <div class="collapse" id="atol_Details_report">
                                            <ul class="side-nav-third-level">
                                                <li>
                                                    <a href="{{ URL::to('atol_Report_Admin') }}">Atol - Report</a>
                                                </li>
                                                <li>
                                                    <a href="{{ URL::to('atol_Report_Weekly_Admin') }}">Weekly Report</a>
                                                </li>
                                                <li>
                                                    <a href="{{ URL::to('atol_Report_Monthly_Admin') }}">Monthly Report </a>
                                                </li>
                                                <li>
                                                    <a href="{{ URL::to('atol_Report_Quarter_Admin') }}">Quarter Yearly Report</a>
                                                </li>
                                                <li>
                                                    <a href="{{ URL::to('atol_Report_Half_Yearly_Admin') }}">Half Yearly Report</a>
                                                </li>
                                                <li>
                                                    <a href="{{ URL::to('atol_Report_Yearly_Admin') }}">Yearly Report</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    
                                    <li>
                                        <a href="{{ URL::to('atol_Certificate_Admin') }}">Atol - Certificate</a>
                                    </li>
                                   
                                </ul>
                                
                            </div>
                        </li>
                        <!--Atol-->
                        
                        <!--Agent-->
                        <li class="side-nav-title side-nav-item">Agent</li>
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarMultiLevelAD" aria-expanded="false" aria-controls="sidebarMultiLevel" class="side-nav-link collapsed">
                                <i class="uil-user"></i>
                                <span> Agent </span>
                                <span class="menu-arrow"></span>
                            </a> 
                            <div class="collapse" id="sidebarMultiLevelAD">
                                <ul class="side-nav-second-level">
                                    <li class="side-nav-item d-none">
                                        <a data-bs-toggle="collapse" href="#sidebarSecondLevel_ADM" aria-expanded="false" aria-controls="sidebarSecondLevel" class="collapsed">
                                            <span> Agents </span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <div class="collapse" id="sidebarSecondLevel_ADM" style="">
                                            <ul class="side-nav-third-level">
                                                <li>
                                                    <a href="{{URL::to('create_Agents_Admin')}}">Agents List</a>
                                                    <a href="{{URL::to('agents_stats')}}" style="display:none">Agents Stats</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    
                                    <li class="side-nav-item">
                                        <a href="{{URL::to('create_Agents_Admin')}}">Agents List</a>
                                    </li>
                                    
                                </ul>
                            </div>
                        </li>
                        <!--Agent-->
                        
                        <!--Customer-->
                        <li class="side-nav-title side-nav-item">Customer</li>
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarMultiLevelCD" aria-expanded="false" aria-controls="sidebarMultiLevel" class="side-nav-link collapsed">
                                <i class="uil-user"></i>
                                <span> Customer </span>
                                <span class="menu-arrow"></span>
                            </a> 
                            <div class="collapse" id="sidebarMultiLevelCD" style="">
                                <ul class="side-nav-second-level">
                                    <li class="side-nav-item d-none">
                                        <a data-bs-toggle="collapse" href="#sidebarSecondLevel_CDM" aria-expanded="false" aria-controls="sidebarSecondLevel" class="collapsed">
                                            <span> Customers </span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <div class="collapse" id="sidebarSecondLevel_CDM" style="">
                                            <ul class="side-nav-third-level">
                                                <li>
                                                    <a href="{{URL::to('create_customer_Admin')}}">Customer List</a>
                                                    <a href="{{URL::to('agents_stats')}}" style="display:none">Agents Stats</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    
                                    <li class="side-nav-item">
                                        <a href="{{URL::to('create_customer_Admin')}}">Customer List</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!--Customer-->
                        
                        <!--Suppliers-->
                        <li class="side-nav-title side-nav-item">Suppliers</li>
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarMultiLevelSD" aria-expanded="false" aria-controls="sidebarMultiLevel" class="side-nav-link collapsed">
                                <i class="uil-folder-plus"></i>
                                <span> Suppliers </span>
                                <span class="menu-arrow"></span>
                            </a> 
                            <div class="collapse" id="sidebarMultiLevelSD" style="">
                                <ul class="side-nav-second-level">
                                    <li class="side-nav-item">
                                        <a data-bs-toggle="collapse" href="#sidebarSecondLevel_AS" aria-expanded="false" aria-controls="sidebarSecondLevel" class="collapsed">
                                            <span> Hotel Supplier </span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <div class="collapse" id="sidebarSecondLevel_AS" style="">
                                            <ul class="side-nav-third-level">
                                                <li>
                                                    <a href="{{URL::to('view_hotel_suppliers_Admin')}}">View Hotel Supplier</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    
                                    <li class="side-nav-item">
                                        <a data-bs-toggle="collapse" href="#sidebarSecondLevel_FS" aria-expanded="false" aria-controls="sidebarSecondLevel" class="collapsed">
                                            <span> Flight Supplier </span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <div class="collapse" id="sidebarSecondLevel_FS" style="">
                                            <ul class="side-nav-third-level">
                                                <li>
                                                    <a href="{{URL::to('super_admin/viewsupplier_Admin')}}">View Flight Supplier</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    
                                    <li class="side-nav-item">
                                        <a data-bs-toggle="collapse" href="#sidebarSecondLevel_TS" aria-expanded="false" aria-controls="sidebarSecondLevel" class="collapsed">
                                            <span> Transfer Supplier </span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <div class="collapse" id="sidebarSecondLevel_TS" style="">
                                            <ul class="side-nav-third-level">
                                                <li>
                                                    <a href="{{URL::to('view_transfer_suppliers_Admin')}}">View Transfer Supplier</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    
                                    <li class="side-nav-item">
                                        <a data-bs-toggle="collapse" href="#sidebarSecondLevel_VS" aria-expanded="false" aria-controls="sidebarSecondLevel" class="collapsed">
                                            <span> Visa Supplier </span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <div class="collapse" id="sidebarSecondLevel_VS" style="">
                                            <ul class="side-nav-third-level">
                                                <li>
                                                    <a href="{{URL::to('viewvisasupplier_Admin')}}">View Visa Supplier</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="side-nav-item">
                                        <a data-bs-toggle="collapse" href="#sidebarSecondLevel_VS_3rd" aria-expanded="false" aria-controls="sidebarSecondLevel_VS_3rd" class="collapsed">
                                            <span> 3rd Party Suppliers </span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <div class="collapse" id="sidebarSecondLevel_VS_3rd" style="">
                                            <ul class="side-nav-third-level">
                                                <li>
                                                    <a href="{{URL::to('super_admin/providers/suppliers')}}">3rd Party Suppliers</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!--Suppliers-->
                        
                        <!--Invoice-->
                        <li class="side-nav-title side-nav-item">Invoices</li>
                        
                        
                        
                        
                        
                        <li class="side-nav-item">
                                        <a data-bs-toggle="collapse" href="#sidebarSecondLevel_MO2" aria-expanded="false" aria-controls="sidebarSecondLevel" class="side-nav-link collapsed">
                                             <i class="uil-file-alt"></i>
                                            <span> Invoice </span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <div class="collapse" id="sidebarSecondLevel_MO2" style="">
                                            <ul class="side-nav-third-level">
                                                <li style="display:none">
                                                    <a href="{{URL::to('create_Invoices')}}">Create Invoice</a>
                                                </li>
                                                <li>
                                                    <a href="{{URL::to('view_Invoices')}}">View Invoices</a>
                                                </li>
                                            </ul>
                                        </div>
                         </li>
                        <li class="side-nav-item" style="display:none">
                            <a data-bs-toggle="collapse" href="#sidebarMultiLevelMO" aria-expanded="false" aria-controls="sidebarMultiLevel" class="side-nav-link collapsed">
                                <i class="uil-file-alt"></i>
                                <span> Manage Office </span>
                                <span class="menu-arrow"></span>
                            </a> 
                            <div class="collapse" id="sidebarMultiLevelMO" style="">
                                <ul class="side-nav-second-level">
                                    
                                    <li class="side-nav-item" style="display:none">
                                        <a data-bs-toggle="collapse" href="#sidebarSecondLevel_MO3" aria-expanded="false" aria-controls="sidebarSecondLevel" class="collapsed">
                                            <span> General Ledger </span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <div class="collapse" id="sidebarSecondLevel_MO3" style="">
                                            <ul class="side-nav-third-level">
                                                <li>
                                                    <a href="{{URL::to('payable_ledger')}}">Payable Ledger</a>
                                                </li>
                                                <li>
                                                    <a href="{{URL::to('receivAble_ledger')}}">Receivable Ledger</a>
                                                </li>
                                                <li>
                                                    <a href="{{URL::to('cash_ledger')}}">Cash Ledger</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="side-nav-item" style="display:none">
                                        <a data-bs-toggle="collapse" href="#sidebarSecondLevel_MO4" aria-expanded="false" aria-controls="sidebarSecondLevel" class="collapsed">
                                            <span> Hotels & Rooms </span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <div class="collapse" id="sidebarSecondLevel_MO4" style="">
                                            <ul class="side-nav-third-level">
                                                <li>
                                                    <a href="{{ URL::to('/hotel_manger/add_hotel') }}">Add Hotels</a>
                                                </li>
                                                <li>
                                                    <a href="{{ URL::to('/hotel_manger/hotel_list') }}">View Hotels</a>
                                                </li>
                                                <li>
                                                    <a href="{{ URL::to('/hotel_manger/add_room') }}">Add Rooms</a>
                                                </li>
                                                <li>
                                                    <a href="{{ URL::to('/hotel_manger/rooms_list') }}">View Rooms</a>
                                                </li>
                                                <li>
                                                    <a href="{{ URL::to('/hotel_manger/add_room_type') }}">Add Room Type</a>
                                                </li>
                                            </ul>
                                        </div>
                                        
                                    </li>
                                    <li class="side-nav-item" style="display:none">
                                        <a data-bs-toggle="collapse" href="#sidebarSecondLevel_MO33" aria-expanded="false" aria-controls="sidebarSecondLevel" class="collapsed">
                                            <span>Hotel Booking </span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <div class="collapse" id="sidebarSecondLevel_MO33" style="">
                                            <ul class="side-nav-third-level">
                                                <li>
                                                    <a href="{{URL::to('hotel_get_booking')}}">Hotel Booking</a>
                                                </li>
                                               
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="side-nav-item" style="display:none">
                                        <a data-bs-toggle="collapse" href="#sidebarSecondLevel_MO5" aria-expanded="false" aria-controls="sidebarSecondLevel" class="collapsed">
                                            <span> Request Invoice </span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <div class="collapse" id="sidebarSecondLevel_MO5" style="">
                                            <ul class="side-nav-third-level">
                                                <li>
                                                    <a href="{{URL::to('request_Invoices')}}">Request Invoice</a>
                                                </li>
                                                 <li>
                                                    <a href="{{URL::to('view_request_Invoices')}}">View Invoice</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="side-nav-item" style="display:none">
                                        <a data-bs-toggle="collapse" href="#sidebarSecondLevel_MO6" aria-expanded="false" aria-controls="sidebarSecondLevel_MO6" class="collapsed">
                                            <span> Hotel Suppliers </span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <div class="collapse" id="sidebarSecondLevel_MO6" style="">
                                            <ul class="side-nav-third-level">
                                                <li>
                                                    <a href="{{URL::to('add_hotel_suppliers')}}">Add Hotel Suppliers</a>
                                                </li>
                                                 <li>
                                                    <a href="{{URL::to('view_hotel_suppliers')}}">View Hotel Suppliers</a>
                                                </li>
                                                 <li>
                                                    <a href="{{URL::to('hotel_supplier_stats')}}">Suppliers Stats</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="side-nav-item" style="display:none">
                                        <a data-bs-toggle="collapse" href="#sidebarSecondLevel_MO77" aria-expanded="false" aria-controls="sidebarSecondLevel_MO6" class="collapsed">
                                            <span> Hotel Arrivals & Departure </span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <div class="collapse" id="sidebarSecondLevel_MO77" style="">
                                            <ul class="side-nav-third-level">
                                                <li>
                                                    <a href="{{URL::to('hotel_arrival_list')}}">Arrival Details</a>
                                                </li>
                                                 <li>
                                                    <a href="{{URL::to('hotel_departure_list')}}">Departure Details</a>
                                                </li>
                                                 
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        
                        <!--<li class="side-nav-item">-->
                        <!--    <a data-bs-toggle="collapse" href="#sidebarMultiLevelMO" aria-expanded="false" aria-controls="sidebarMultiLevel" class="side-nav-link collapsed">-->
                        <!--        <i class="uil-file-alt"></i>-->
                        <!--        <span> Manage Office </span>-->
                        <!--        <span class="menu-arrow"></span>-->
                        <!--    </a> -->
                        <!--    <div class="collapse" id="sidebarMultiLevelMO" style="">-->
                        <!--        <ul class="side-nav-second-level">-->
                        <!--            <li class="side-nav-item">-->
                        <!--                <a data-bs-toggle="collapse" href="#sidebarSecondLevel_MO2" aria-expanded="false" aria-controls="sidebarSecondLevel" class="collapsed">-->
                        <!--                    <span> Invoice </span>-->
                        <!--                    <span class="menu-arrow"></span>-->
                        <!--                </a>-->
                        <!--                <div class="collapse" id="sidebarSecondLevel_MO2" style="">-->
                        <!--                    <ul class="side-nav-third-level">-->
                        <!--                        <li style="display:none">-->
                        <!--                            <a href="{{URL::to('create_Invoices')}}">Create Invoice</a>-->
                        <!--                        </li>-->
                        <!--                        <li>-->
                        <!--                            <a href="{{URL::to('view_Invoices')}}">View Invoices</a>-->
                        <!--                        </li>-->
                        <!--                    </ul>-->
                        <!--                </div>-->
                        <!--            </li>-->
                        <!--            <li class="side-nav-item" style="display:none">-->
                        <!--                <a data-bs-toggle="collapse" href="#sidebarSecondLevel_MO3" aria-expanded="false" aria-controls="sidebarSecondLevel" class="collapsed">-->
                        <!--                    <span> General Ledger </span>-->
                        <!--                    <span class="menu-arrow"></span>-->
                        <!--                </a>-->
                        <!--                <div class="collapse" id="sidebarSecondLevel_MO3" style="">-->
                        <!--                    <ul class="side-nav-third-level">-->
                        <!--                        <li>-->
                        <!--                            <a href="{{URL::to('payable_ledger')}}">Payable Ledger</a>-->
                        <!--                        </li>-->
                        <!--                        <li>-->
                        <!--                            <a href="{{URL::to('receivAble_ledger')}}">Receivable Ledger</a>-->
                        <!--                        </li>-->
                        <!--                        <li>-->
                        <!--                            <a href="{{URL::to('cash_ledger')}}">Cash Ledger</a>-->
                        <!--                        </li>-->
                        <!--                    </ul>-->
                        <!--                </div>-->
                        <!--            </li>-->
                        <!--            <li class="side-nav-item" style="display:none">-->
                        <!--                <a data-bs-toggle="collapse" href="#sidebarSecondLevel_MO4" aria-expanded="false" aria-controls="sidebarSecondLevel" class="collapsed">-->
                        <!--                    <span> Hotels & Rooms </span>-->
                        <!--                    <span class="menu-arrow"></span>-->
                        <!--                </a>-->
                        <!--                <div class="collapse" id="sidebarSecondLevel_MO4" style="">-->
                        <!--                    <ul class="side-nav-third-level">-->
                        <!--                        <li>-->
                        <!--                            <a href="{{ URL::to('/hotel_manger/add_hotel') }}">Add Hotels</a>-->
                        <!--                        </li>-->
                        <!--                        <li>-->
                        <!--                            <a href="{{ URL::to('/hotel_manger/hotel_list') }}">View Hotels</a>-->
                        <!--                        </li>-->
                        <!--                        <li>-->
                        <!--                            <a href="{{ URL::to('/hotel_manger/add_room') }}">Add Rooms</a>-->
                        <!--                        </li>-->
                        <!--                        <li>-->
                        <!--                            <a href="{{ URL::to('/hotel_manger/rooms_list') }}">View Rooms</a>-->
                        <!--                        </li>-->
                        <!--                        <li>-->
                        <!--                            <a href="{{ URL::to('/hotel_manger/add_room_type') }}">Add Room Type</a>-->
                        <!--                        </li>-->
                        <!--                    </ul>-->
                        <!--                </div>-->
                                        
                        <!--            </li>-->
                        <!--            <li class="side-nav-item" style="display:none">-->
                        <!--                <a data-bs-toggle="collapse" href="#sidebarSecondLevel_MO33" aria-expanded="false" aria-controls="sidebarSecondLevel" class="collapsed">-->
                        <!--                    <span>Hotel Booking </span>-->
                        <!--                    <span class="menu-arrow"></span>-->
                        <!--                </a>-->
                        <!--                <div class="collapse" id="sidebarSecondLevel_MO33" style="">-->
                        <!--                    <ul class="side-nav-third-level">-->
                        <!--                        <li>-->
                        <!--                            <a href="{{URL::to('hotel_get_booking')}}">Hotel Booking</a>-->
                        <!--                        </li>-->
                                               
                        <!--                    </ul>-->
                        <!--                </div>-->
                        <!--            </li>-->
                        <!--            <li class="side-nav-item" style="display:none">-->
                        <!--                <a data-bs-toggle="collapse" href="#sidebarSecondLevel_MO5" aria-expanded="false" aria-controls="sidebarSecondLevel" class="collapsed">-->
                        <!--                    <span> Request Invoice </span>-->
                        <!--                    <span class="menu-arrow"></span>-->
                        <!--                </a>-->
                        <!--                <div class="collapse" id="sidebarSecondLevel_MO5" style="">-->
                        <!--                    <ul class="side-nav-third-level">-->
                        <!--                        <li>-->
                        <!--                            <a href="{{URL::to('request_Invoices')}}">Request Invoice</a>-->
                        <!--                        </li>-->
                        <!--                         <li>-->
                        <!--                            <a href="{{URL::to('view_request_Invoices')}}">View Invoice</a>-->
                        <!--                        </li>-->
                        <!--                    </ul>-->
                        <!--                </div>-->
                        <!--            </li>-->
                        <!--            <li class="side-nav-item" style="display:none">-->
                        <!--                <a data-bs-toggle="collapse" href="#sidebarSecondLevel_MO6" aria-expanded="false" aria-controls="sidebarSecondLevel_MO6" class="collapsed">-->
                        <!--                    <span> Hotel Suppliers </span>-->
                        <!--                    <span class="menu-arrow"></span>-->
                        <!--                </a>-->
                        <!--                <div class="collapse" id="sidebarSecondLevel_MO6" style="">-->
                        <!--                    <ul class="side-nav-third-level">-->
                        <!--                        <li>-->
                        <!--                            <a href="{{URL::to('add_hotel_suppliers')}}">Add Hotel Suppliers</a>-->
                        <!--                        </li>-->
                        <!--                         <li>-->
                        <!--                            <a href="{{URL::to('view_hotel_suppliers')}}">View Hotel Suppliers</a>-->
                        <!--                        </li>-->
                        <!--                         <li>-->
                        <!--                            <a href="{{URL::to('hotel_supplier_stats')}}">Suppliers Stats</a>-->
                        <!--                        </li>-->
                        <!--                    </ul>-->
                        <!--                </div>-->
                        <!--            </li>-->
                        <!--            <li class="side-nav-item" style="display:none">-->
                        <!--                <a data-bs-toggle="collapse" href="#sidebarSecondLevel_MO77" aria-expanded="false" aria-controls="sidebarSecondLevel_MO6" class="collapsed">-->
                        <!--                    <span> Hotel Arrivals & Departure </span>-->
                        <!--                    <span class="menu-arrow"></span>-->
                        <!--                </a>-->
                        <!--                <div class="collapse" id="sidebarSecondLevel_MO77" style="">-->
                        <!--                    <ul class="side-nav-third-level">-->
                        <!--                        <li>-->
                        <!--                            <a href="{{URL::to('hotel_arrival_list')}}">Arrival Details</a>-->
                        <!--                        </li>-->
                        <!--                         <li>-->
                        <!--                            <a href="{{URL::to('hotel_departure_list')}}">Departure Details</a>-->
                        <!--                        </li>-->
                                                 
                        <!--                    </ul>-->
                        <!--                </div>-->
                        <!--            </li>-->
                        <!--        </ul>-->
                        <!--    </div>-->
                        <!--</li>-->
                        <!--Invoice-->
                        
                        <!--Package-->
                        <li class="side-nav-title side-nav-item">Tours</li>
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarMultiLevel" aria-expanded="false" aria-controls="sidebarMultiLevel" class="side-nav-link collapsed">
                                <i class="uil-package"></i>
                                <span>Packages </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarMultiLevel">
                                <ul class="side-nav-second-level">
                                    <li style="display:none">
                                        <a href="{{URL::to('super_admin/create_excursion2')}}">Create Packages</a>
                                    </li>
                                    <li>
                                        <a href="{{URL::to('super_admin/view_tour_Admin')}}">View Packages</a>
                                    </li>
                                    <li style="display:none">
                                        <a href="{{URL::to('super_admin/mange_currency')}}">Manage Currency</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="collapse" id="sidebarMultiLevel" style="">
                                <ul class="side-nav-second-level">
                                    <li class="side-nav-item">
                                        <a data-bs-toggle="collapse" href="#sidebarSecondLevel_PB" aria-expanded="false" aria-controls="sidebarSecondLevel" class="collapsed">
                                            <span> Packages Bookings </span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <div class="collapse" id="sidebarSecondLevel_PB" style="">
                                            <ul class="side-nav-third-level">
                                                <li>
                                                    <a href="{{URL::to('super_admin/view_ternary_bookings_Admin')}}">View Package Bookings</a>
                                                </li>
                                                <li style="display:none">
                                                    <a href="{{URL::to('super_admin/view_confirmed_bookings_Admin')}}">Confirmed Bookings</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="side-nav-item" style="display:none">
                                        <a data-bs-toggle="collapse" href="#sidebarSecondLevel1_12" aria-expanded="false" aria-controls="sidebarSecondLevel1_12" class="collapsed">
                                            <span> Hotel </span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <div class="collapse" id="sidebarSecondLevel1_12" style="">
                                            <ul class="side-nav-third-level">
                                                <li>
                                                    <a href="{{URL::to('super_admin/view_Hotel_Names')}}">View Hotel Names</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="side-nav-item" style="display:none">
                                        <a data-bs-toggle="collapse" href="#sidebarSecondLevel1_13" aria-expanded="false" aria-controls="sidebarSecondLevel1_13" class="collapsed">
                                            <span> Locations </span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <div class="collapse" id="sidebarSecondLevel1_13" style="">
                                            <ul class="side-nav-third-level">
                                                <li>
                                                    <a href="{{URL::to('super_admin/view_pickup_locations')}}">View Pickup Locations</a>
                                                </li>
                                                <li>
                                                    <a href="{{URL::to('super_admin/view_dropof_locations')}}">View Dropof Locations</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="side-nav-item" style="display:none">
                                        <a data-bs-toggle="collapse" href="#sidebarSecondLevel" aria-expanded="false" aria-controls="sidebarSecondLevel" class="collapsed">
                                            <span> Categories </span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <div class="collapse" id="sidebarSecondLevel" style="">
                                            <ul class="side-nav-third-level">
                                                <li>
                                                    <a href="{{URL::to('super_admin/add_categories')}}">Add Categories</a>
                                                </li>
                                                <li>
                                                    <a href="{{URL::to('super_admin/view_categories')}}">View Categories</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="side-nav-item" style="display:none">
                                        <a data-bs-toggle="collapse" href="#sidebarSecondLeve2" aria-expanded="false" aria-controls="sidebarSecondLevel" class="collapsed">
                                            <span> Facilities </span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <div class="collapse" id="sidebarSecondLeve2" style="">
                                            <ul class="side-nav-third-level">
                                                <li>
                                                    <a href="{{URL::to('super_admin/add_attributes')}}">Add Facilities</a>
                                                </li>
                                                <li>
                                                    <a href="{{URL::to('super_admin/view_attributes')}}">View Facilities</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="side-nav-item" style="display:none">
                                        <a data-bs-toggle="collapse" href="#sidebarSecondLeveInvoice" aria-expanded="false" aria-controls="sidebarSecondLevel" class="collapsed">
                                            <span> Invoices </span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <div class="collapse" id="sidebarSecondLeveInvoice" style="">
                                            <ul class="side-nav-third-level">
                                                <li>
                                                    <a href="{{URL::to('super_admin/add_attributes')}}">Create Invoice</a>
                                                </li>
                                                <li>
                                                    <a href="{{URL::to('super_admin/view_attributes')}}">View Invoice</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!--Package-->
                        
                        <!--Activity-->
                        <li class="side-nav-title side-nav-item">Activity</li>
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarMultiLevel_11" aria-expanded="false" aria-controls="sidebarMultiLevel_11" class="side-nav-link collapsed">
                                <i class="uil-life-ring"></i>
                                <span>Activities </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarMultiLevel_11">
                                <ul class="side-nav-second-level">
                                    <li>
                                        <a href="{{URL::to('view_activities_new_Admin')}}">View Activities</a>
                                    </li>
                                    
                                    <li class="side-nav-item">
                                        <a href="{{URL::to('view_ternary_bookings_Activity_Admin')}}">Activity Bookings</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!--Activity-->
                        
                        <!--Acounts-->
                        <li class="side-nav-title side-nav-item">Accounts</li>
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarEcommerce_AD" aria-expanded="false" aria-controls="sidebarEcommerce" class="side-nav-link">
                                <i class="uil-chart-line"></i>
                                <span> Accounts </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarEcommerce_AD">
                                <ul class="side-nav-second-level">
                                    <li class="side-nav-item" style="display:none">
                                        <a href="{{ URL::to('super_admin/cash_accounts_list') }}">Company Accounts</a>
                                    </li>
                                    
                                    <li class="side-nav-item" style="display:none">
                                        <a href="{{ URL::to('super_admin/add_payment_recv') }}">Receive Payments</a>
                                    </li>
                                    
                                    <li class="side-nav-item" style="display:none">
                                        <a href="{{ URL::to('super_admin/add_payment') }}">Make Payments</a>
                                    </li>
                                    
                                    <li class="side-nav-item" style="display:none">
                                        <a href="{{ URL::to('agents_financial_stats') }}">Agent Financial Details</a>
                                    </li>
                                    
                                    <li class="side-nav-item" style="display:none">
                                        <a href="{{ URL::to('income_statement') }}">Income Statement</a>
                                    </li>
                                    
                                    <li class="side-nav-item" style="display:none">
                                        <a href="{{ URL::to('expenses_Income') }}">Expenses</a>
                                    </li>
                                    
                                    <li class="side-nav-item" style="display:none">
                                        <a href="{{ URL::to('recieved_Payments') }}">Recieved</a>
                                    </li>
                                    
                                    <li class="side-nav-item" style="display:none">
                                        <a href="{{ URL::to('stats_Tours') }}">Stats</a>
                                    </li>
                                    
                                    <li class="side-nav-item">
                                        <a href="{{ URL::to('booking_financial_stats_Admin') }}">Revenue Stream</a>
                                    </li>
                                    
                                    
                                    <li class="side-nav-item" style="display:none">
                                        <a href="{{ URL::to('all_cost_stats') }}">Expense Stream</a>
                                    </li>
                                    
                                    <li class="side-nav-item" style="display:none">
                                    <a data-bs-toggle="collapse" href="#sidebarSecondLevel_MOUT33" aria-expanded="false" aria-controls="sidebarSecondLevel" class="collapsed">
                                        <span> Outstandings </span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <div class="collapse" id="sidebarSecondLevel_MOUT33" style="">
                                        <ul class="side-nav-third-level">
                                            <li>
                                                <a href="{{URL::to('out_Standings')}}">Recieveables</a>
                                            </li>
                                            <li class="d-none">
                                                <a href="{{URL::to('out_standings_payables')}}">Payables</a>
                                            </li>
                                            
                                        </ul>
                                    </div>
                                    </li>
                                    
                                    <li class="side-nav-item" style="display:none">
                                    <a data-bs-toggle="collapse" href="#sidebarSecondLevel_MOUT365" aria-expanded="false" aria-controls="sidebarSecondLevel" class="collapsed">
                                        <span> 3rd Party Commissions </span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <div class="collapse" id="sidebarSecondLevel_MOUT365" style="">
                                        <ul class="side-nav-third-level">
                                              <li>
                                                    <a href="{{URL::to('super_admin/third_party_commission_payable')}}">Payable</a>
                                                </li> 
                                                <li>
                                                    <a href="{{URL::to('super_admin/third_party_commission_receivable')}}">Receivable</a>
                                                </li>
                                            
                                        </ul>
                                    </div>
                                    </li>
                                    
                                    <li class="side-nav-item" style="display:none">
                                    <a data-bs-toggle="collapse" href="#sidebarSecondLevel_MO33" aria-expanded="false" aria-controls="sidebarSecondLevel" class="collapsed">
                                        <span> General Ledger </span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <div class="collapse" id="sidebarSecondLevel_MO33" style="">
                                        <ul class="side-nav-third-level">
                                            <li>
                                                <a href="{{URL::to('payable_ledger')}}">Payable Ledger</a>
                                            </li>
                                            <li>
                                                <a href="{{URL::to('receivAble_ledger')}}">Receivable Ledger</a>
                                            </li>
                                            <li>
                                                <a href="{{URL::to('cash_ledger')}}">Cash Ledger</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                   
                                </ul>
                            </div>
                        </li>
                        <!--Acounts-->
                        
                        <li class="side-nav-title side-nav-item d-none">Umrah</li>
                        <li class="side-nav-item d-none">
                            <a data-bs-toggle="collapse" href="#sidebarEcommerce" aria-expanded="false" aria-controls="sidebarEcommerce" class="side-nav-link">
                                <i class="uil-store"></i>
                                <span> Umrah Package </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarEcommerce">
                                <ul class="side-nav-second-level">
                                    <li>
                                        <a href="{{URL::to('super_admin/create_umrah_packages')}}">Create Umrah Package</a>
                                    </li>
                                    <li>
                                        <a href="{{URL::to('super_admin/view_umrah_packages')}}">View Umrah Package</a>
                                    </li>
                                   
                                   
                                </ul>
                            </div>
                        </li>
                        <li class="side-nav-item d-none">
                            <a data-bs-toggle="collapse" href="#sidebarEcommerce1" aria-expanded="false" aria-controls="sidebarEcommerce" class="side-nav-link">
                                <i class="uil-store"></i>
                                <span> Bookings </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarEcommerce1">
                                <ul class="side-nav-second-level">
                                    <li>
                                        <a href="{{URL::to('super_admin/create_umrah_packages')}}">Confirm Bookings</a>
                                    </li>
                                    <li style="display:none">
                                        <a href="{{URL::to('super_admin/view_umrah_packages')}}">Confirm Package</a>
                                    </li>
                                   
                                   
                                </ul>
                            </div>
                        </li>
                        
                        <li class="side-nav-title side-nav-item">Hotel & Rooms</li>
                        <li class="side-nav-item">
                                <a data-bs-toggle="collapse" href="#sidebarEcommerce_1" aria-expanded="false" aria-controls="sidebarEcommerce" class="side-nav-link">
                                    <i class="uil-store"></i>
                                    <span> Hotels </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="sidebarEcommerce_1">
                                    <ul class="side-nav-second-level">
                                        <li>
                                            <a href="{{ URL::to('/hotel_manger/add_hotel') }}" style="display:none">Add Hotels</a>
                                        </li>
                                        <li>
                                            <a href="{{ URL::to('/hotel_manger/hotel_list') }}" style="display:none">View Hotels</a>
                                        </li>
                                        <li>
                                            <a href="{{ URL::to('/hotel_manger/hotel_list_Admin') }}">View Hotels</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        <li class="side-nav-item">
                                <a data-bs-toggle="collapse" href="#sidebarEcommerce_2" aria-expanded="false" aria-controls="sidebarEcommerce" class="side-nav-link">
                                    <i class="uil-store"></i>
                                    <span> Rooms </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="sidebarEcommerce_2">
                                    <ul class="side-nav-second-level">
                                        <li>
                                            <a href="{{ URL::to('/hotel_manger/add_room') }}" style="display:none">Add Rooms</a>
                                        </li>
                                        <li>
                                            <a href="{{ URL::to('/hotel_manger/rooms_list') }}" style="display:none">View Rooms</a>
                                        </li>
                                        <li>
                                            <a href="{{ URL::to('/hotel_manger/rooms_list_Admin') }}">View Rooms</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        
                        <li class="side-nav-item" style="display:none;">
                                <a data-bs-toggle="collapse" href="#sidebarEcommerce_booking" aria-expanded="false" aria-controls="sidebarEcommerce" class="side-nav-link">
                                    <i class="uil-store"></i>
                                    <span> Hotel Booking </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="sidebarEcommerce_booking">
                                    <ul class="side-nav-second-level">
                                        <li>
                                            <a href="{{ URL::to('super_admin/recevied_booking') }}">Received Booking</a>
                                        </li>
                                        <li>
                                            <a href="{{ URL::to('super_admin/confirmed_booking') }}">Confirmed Booking</a>
                                        </li>
                                        <li>
                                            <a href="{{ URL::to('super_admin/cancel_booking') }}">Cancel Booking</a>
                                        </li>
                                       
                                       
                                    </ul>
                                </div>
                            </li>
                        
                        
                        <li class="side-nav-title side-nav-item d-none">Quotations & Bookings</li>
                        <li class="side-nav-item d-none">
                                <a data-bs-toggle="collapse" href="#sidebarEcommerce_3" aria-expanded="false" aria-controls="sidebarEcommerce" class="side-nav-link">
                                    <i class="uil-store"></i>
                                    <span> Quotations </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="sidebarEcommerce_3">
                                    <ul class="side-nav-second-level">
                                        <li>
                                            <a href="{{ URL::to('manage_Quotation') }}">Add Quotations</a>
                                        </li>
                                        <li>
                                            <a href="{{ URL::to('view_Quotations') }}">View Quotations</a>
                                        </li>
                                       
                                       
                                    </ul>
                                </div>
                            </li>
                        <li class="side-nav-item d-none">
                                <a data-bs-toggle="collapse" href="#sidebarEcommerce_4" aria-expanded="false" aria-controls="sidebarEcommerce" class="side-nav-link">
                                    <i class="uil-store"></i>
                                    <span> Bookings </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="sidebarEcommerce_4">
                                    <ul class="side-nav-second-level">
                                        <li>
                                            <a href="#">Add Bookings</a>
                                        </li>
                                        <li>
                                            <a href="{{ URL::to('view_Bookings') }}">View Bookings</a>
                                        </li>
                                       
                                       
                                    </ul>
                                </div>
                            </li>
                        
                        <li class="side-nav-title side-nav-item d-none">Tour</li>
                        <li class="side-nav-item d-none">
                            <a data-bs-toggle="collapse" href="#sidebarMultiLevel" aria-expanded="false" aria-controls="sidebarMultiLevel" class="side-nav-link collapsed">
                                <i class="uil-folder-plus"></i>
                                <span> Tour packages </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarMultiLevel">
                                <ul class="side-nav-second-level">
                                    <li>
                                        <a href="{{URL::to('super_admin/create_excursion')}}">Create Tour</a>
                                    </li>
                                    <li>
                                        <a href="{{URL::to('super_admin/view_tour')}}">View Tour</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="collapse" id="sidebarMultiLevel" style="">
                                <ul class="side-nav-second-level">
                                    
                                    <li class="side-nav-item">
                                        <a data-bs-toggle="collapse" href="#sidebarSecondLevel_1" aria-expanded="false" aria-controls="sidebarSecondLevel" class="collapsed">
                                            <span> Packages Bookings </span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <div class="collapse" id="sidebarSecondLevel_1" style="">
                                            <ul class="side-nav-third-level">
                                                <li>
                                                    <a href="{{URL::to('super_admin/view_ternary_bookings')}}">Tentative Bookings</a>
                                                </li>
                                                <li>
                                                    <a href="{{URL::to('super_admin/view_confirmed_bookings')}}">Confirmed Bookings</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="side-nav-item">
                                        <a data-bs-toggle="collapse" href="#sidebarSecondLevel" aria-expanded="false" aria-controls="sidebarSecondLevel" class="collapsed">
                                            <span> Categories </span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <div class="collapse" id="sidebarSecondLevel" style="">
                                            <ul class="side-nav-third-level">
                                                <li>
                                                    <a href="{{URL::to('super_admin/add_categories')}}">Add Categories</a>
                                                </li>
                                                <li>
                                                    <a href="{{URL::to('super_admin/view_categories')}}">View Categories</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="side-nav-item">
                                        <a data-bs-toggle="collapse" href="#sidebarSecondLeve2" aria-expanded="false" aria-controls="sidebarSecondLevel" class="collapsed">
                                            <span> Attributes </span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <div class="collapse" id="sidebarSecondLeve2" style="">
                                            <ul class="side-nav-third-level">
                                                <li>
                                                    <a href="{{URL::to('super_admin/add_attributes')}}">Add Attributes</a>
                                                </li>
                                                <li>
                                                    <a href="{{URL::to('super_admin/view_attributes')}}">View Attributes</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    
                                </ul>
                            </div>
                        </li>
                        <li class="side-nav-item d-none">
                            <a data-bs-toggle="collapse" href="#sidebarMultiLevel_11" aria-expanded="false" aria-controls="sidebarMultiLevel_11" class="side-nav-link collapsed">
                                <i class="uil-folder-plus"></i>
                                <span>Activities </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarMultiLevel_11" style="">
                                <ul class="side-nav-second-level">
                                    <li class="side-nav-item">
                                        <a data-bs-toggle="collapse" href="#sidebarSecondLevel_11" aria-expanded="false" aria-controls="sidebarSecondLevel_11" class="collapsed">
                                            <span> Activities Bookings </span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <div class="collapse" id="sidebarSecondLevel_11" style="">
                                            <ul class="side-nav-third-level">
                                                <li>
                                                    <a href="{{URL::to('super_admin/view_ternary_bookings_Activity')}}">Tentative Bookings</a>
                                                </li>
                                                <li>
                                                    <a href="{{URL::to('super_admin/view_confirmed_bookings_Activity')}}">Confirmed Bookings</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="side-nav-item d-none">
                            <a data-bs-toggle="collapse" href="#sidebarEcommerce3" aria-expanded="false" aria-controls="sidebarEcommerce" class="side-nav-link">
                                <i class="uil-store"></i>
                                <span> Bookings </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarEcommerce3">
                                <ul class="side-nav-second-level">
                                    <li>
                                        <a href="{{URL::to('super_admin/create_umrah_packages')}}">Confirm Bookings</a>
                                    </li>
                                    <li>
                                        <a href="{{URL::to('super_admin/view_umrah_packages')}}">Confirm Package</a>
                                    </li>
                                   
                                   
                                </ul>
                            </div>
                        </li>
                        
                        <li class="side-nav-title side-nav-item">Other</li>
                        
                        <li class="side-nav-item d-none">
                            <a data-bs-toggle="collapse" href="#sidebarEcommerce_4" aria-expanded="false" aria-controls="sidebarEcommerce" class="side-nav-link">
                                <i class="uil-store"></i>
                                <span> Offline Bookings </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarEcommerce_4">
                                <ul class="side-nav-second-level">
                                    <li>
                                        <a href="{{URL::to('super_admin/book_package')}}">Book Package</a>
                                    </li>
                                     <li>
                                        <a href="{{URL::to('super_admin/ticket_view')}}">Custom Package</a>
                                    </li>
                                   
                                   
                                   
                                </ul>
                            </div>
                        </li>
                        
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarEcommerce4" aria-expanded="false" aria-controls="sidebarEcommerce" class="side-nav-link">
                                <i class="uil-store"></i>
                                <span> Support Ticket </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarEcommerce4">
                                <ul class="side-nav-second-level">
                                    <li>
                                        <a href="{{URL::to('super_admin/ticket_view')}}">View Ticket</a>
                                    </li>
                                   
                                   
                                   
                                </ul>
                            </div>
                        </li>
                        
                        <li class="side-nav-item d-none">
                            <a data-bs-toggle="collapse" href="#sidebarEcommerce_7" aria-expanded="false" aria-controls="sidebarEcommerce" class="side-nav-link">
                                <i class="uil-store"></i>
                                <span> Accounts </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarEcommerce_7">
                                <ul class="side-nav-second-level">
                                    <li>
                                        <a href="{{ URL::to('income_statement') }}">Income Statement</a>
                                    </li>
                                    <li>
                                        <a href="{{ URL::to('expenses_Income') }}">Expenses</a>
                                    </li>
                                    <!--<li>-->
                                    <!--    <a href="{{ URL::to('out_Standings') }}">Outstandings</a>-->
                                    <!--</li>-->
                                    <!--<li>-->
                                    <!--    <a href="{{ URL::to('recieved_Payments') }}">Recieved</a>-->
                                    <!--</li>-->
                                    <!--<li>-->
                                    <!--    <a href="{{ URL::to('stats_Tours') }}">Stats</a>-->
                                    <!--</li>-->
                                    <!--<li>-->
                                    <!--    <a href="{{ URL::to('cancelled_Tours') }}">Cancelled</a>-->
                                    <!--</li>-->
                                   
                                   
                                </ul>
                            </div>
                        </li>

                        <li class="side-nav-item d-none">
                            <a data-bs-toggle="collapse" href="#sidebarEcommerce5" aria-expanded="false" aria-controls="sidebarEcommerce" class="side-nav-link">
                                <i class="uil-store"></i>
                                <span> HRMS </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarEcommerce5">
                                <ul class="side-nav-second-level">
                                    <li>
                                        <a href="{{URL::to('super_admin/employees/add')}}">Add Employee</a>
                                    </li>
                                    <li>
                                        <a href="{{URL::to('super_admin/employees')}}">View Employee</a>
                                    </li>
                                    <li>
                                        <a href="{{URL::to('super_admin/employee_roles')}}">Employee Roles</a>
                                    </li>
                                    <li>
                                        <a href="{{URL::to('super_admin/attendance')}}">Employee Attendance</a>
                                    </li>
                                     <li>
                                        <a href="{{URL::to('super_admin/employees_task')}}">Employee Task</a>
                                    </li>
                                    <li>
                                        <a href="{{URL::to('super_admin/leave_employees')}}">Employee Leave</a>
                                    </li>
                                    
                                   
                                   
                                   
                                </ul>
                            </div>
                        </li>

                        <li class="side-nav-item">
                            <a  href="{{URL::to('super_admin/settings')}}" class="side-nav-link">
                                <i class="uil-store"></i>
                                <span>Setting</span>
                               
                            </a>
                            
                        </li>
                        
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarEcommerce_markup" aria-expanded="false" aria-controls="sidebarEcommerce" class="side-nav-link">
                                <i class="uil-store"></i>
                                <span> Markup </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarEcommerce_markup">
                                <ul class="side-nav-second-level">
                                    <li>
                                        <a href="{{URL::to('super_admin/markup/add')}}">Add Markup</a>
                                    </li>
                                    <li>
                                        <a href="{{URL::to('super_admin/view_markup')}}">View Markup</a>
                                    </li>
                                   
                                    
                                     
                                  
                                    
                                   
                                   
                                   
                                </ul>
                            </div>
                        </li>

                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarEcommerce7" aria-expanded="false" aria-controls="sidebarEcommerce" class="side-nav-link">
                                <i class="uil-store"></i>
                                <span>Offers</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarEcommerce7">
                                <ul class="side-nav-second-level">
                                    
                                    <li>
                                        <a href="{{URL::to('super_admin/view_offers')}}">View Offers</a>
                                    </li>
                                   
                                   
                                   
                                </ul>
                            </div>
                        </li>
                        
                        <li class="side-nav-item">
                            <a href="{{URL::to('super_admin/manage_user_roles')}}" class="side-nav-link">
                                <i class="uil-store"></i>
                                <span>Mange User Roles</span>
                               
                            </a>
                            
                        </li>
                        
                        <li class="side-nav-item">
                            <a  href="{{URL::to('super_admin/send_email_to_agents')}}"class="side-nav-link">
                                <i class="uil-store"></i>
                                <span>Email Marketing</span>
                                
                            </a>
                            
                        </li>
                        
                        <li class="side-nav-item">
                            <a href="#EmailMarketing" class="side-nav-link">
                                <i class="uil-envelope"></i>
                                <span> Logout </span>
                             
                            </a>
                           
                           
                        </li>
                        
                    </ul>
                    
                    <!-- End Sidebar -->
                    <div class="clearfix"></div>
                </div>
                <!-- Sidebar -left -->
            </div>
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page">
                <div class="content">
                    <!-- Topbar Start -->
                    <div class="navbar-custom">
                        <ul class="list-unstyled topbar-menu float-end mb-0">
                            <li class="dropdown notification-list d-lg-none">
                                <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                    <i class="dripicons-search noti-icon"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-animated dropdown-lg p-0">
                                    <form class="p-3">
                                        <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                                    </form>
                                </div>
                            </li>
                            <li class="dropdown notification-list topbar-dropdown">
                                <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                    <img src="{{asset('public/admin_package/assets/images/flags/us.jpg')}}" alt="user-image" class="me-0 me-sm-1" height="12"> 
                                    <span class="align-middle d-none d-sm-inline-block">English</span> <i class="mdi mdi-chevron-down d-none d-sm-inline-block align-middle"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated topbar-dropdown-menu">

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <img src="{{asset('public/admin_package/assets/images/flags/germany.jpg')}}" alt="user-image" class="me-1" height="12"> <span class="align-middle">German</span>
                                    </a>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <img src="{{asset('public/admin_package/assets/images/flags/italy.jpg')}}" alt="user-image" class="me-1" height="12"> <span class="align-middle">Italian</span>
                                    </a>
                
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <img src="{{asset('public/admin_package/assets/images/flags/spain.jpg')}}" alt="user-image" class="me-1" height="12"> <span class="align-middle">Spanish</span>
                                    </a>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <img src="{{asset('public/admin_package/assets/images/flags/russia.jpg')}}" alt="user-image" class="me-1" height="12"> <span class="align-middle">Russian</span>
                                    </a>

                                </div>
                            </li>

                            <li class="dropdown notification-list">
                                <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                    <i class="dripicons-bell noti-icon"></i>
                                    <span class="noti-icon-badge"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated dropdown-lg">

                                    <!-- item-->
                                    <div class="dropdown-item noti-title px-3">
                                        <h5 class="m-0">
                                            <span class="float-end">
                                                <a href="javascript: void(0);" class="text-dark">
                                                    <small>Clear All</small>
                                                </a>
                                            </span>Notification
                                        </h5>
                                    </div>

                                    <div class="px-3" style="max-height: 300px;" data-simplebar>

                                        <h5 class="text-muted font-13 fw-normal mt-0">Today</h5>
                                        <!-- item-->
                                        <a href="javascript:void(0);" class="dropdown-item p-0 notify-item card unread-noti shadow-none mb-2">
                                            <div class="card-body">
                                                <span class="float-end noti-close-btn text-muted"><i class="mdi mdi-close"></i></span>   
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <div class="notify-icon bg-primary">
                                                            <i class="mdi mdi-comment-account-outline"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 text-truncate ms-2">
                                                        <h5 class="noti-item-title fw-semibold font-14">Datacorp <small class="fw-normal text-muted ms-1">1 min ago</small></h5>
                                                        <small class="noti-item-subtitle text-muted">Caleb Flakelar commented on Admin</small>
                                                    </div>
                                                  </div>
                                            </div>
                                        </a>

                                        <!-- item-->
                                        <a href="javascript:void(0);" class="dropdown-item p-0 notify-item card read-noti shadow-none mb-2">
                                            <div class="card-body">
                                                <span class="float-end noti-close-btn text-muted"><i class="mdi mdi-close"></i></span>   
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <div class="notify-icon bg-info">
                                                            <i class="mdi mdi-account-plus"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 text-truncate ms-2">
                                                        <h5 class="noti-item-title fw-semibold font-14">Admin <small class="fw-normal text-muted ms-1">1 hours ago</small></h5>
                                                        <small class="noti-item-subtitle text-muted">New user registered</small>
                                                    </div>
                                                  </div>
                                            </div>
                                        </a>

                                        <h5 class="text-muted font-13 fw-normal mt-0">Yesterday</h5>

                                        <!-- item-->
                                        <a href="javascript:void(0);" class="dropdown-item p-0 notify-item card read-noti shadow-none mb-2">
                                            <div class="card-body">
                                                <span class="float-end noti-close-btn text-muted"><i class="mdi mdi-close"></i></span>   
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <div class="notify-icon">
                                                            <img src="{{asset('public/admin_package/assets/images/users/avatar-2.jpg')}}" class="img-fluid rounded-circle" alt="" />
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 text-truncate ms-2">
                                                        <h5 class="noti-item-title fw-semibold font-14">Cristina Pride <small class="fw-normal text-muted ms-1">1 day ago</small></h5>
                                                        <small class="noti-item-subtitle text-muted">Hi, How are you? What about our next meeting</small>
                                                    </div>
                                                  </div>
                                            </div>
                                        </a>

                                        <h5 class="text-muted font-13 fw-normal mt-0">30 Dec 2021</h5>

                                        <!-- item-->
                                        <a href="javascript:void(0);" class="dropdown-item p-0 notify-item card read-noti shadow-none mb-2">
                                            <div class="card-body">
                                                <span class="float-end noti-close-btn text-muted"><i class="mdi mdi-close"></i></span>   
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <div class="notify-icon bg-primary">
                                                            <i class="mdi mdi-comment-account-outline"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 text-truncate ms-2">
                                                        <h5 class="noti-item-title fw-semibold font-14">Datacorp</h5>
                                                        <small class="noti-item-subtitle text-muted">Caleb Flakelar commented on Admin</small>
                                                    </div>
                                                  </div>
                                            </div>
                                        </a>

                                         <!-- item-->
                                         <a href="javascript:void(0);" class="dropdown-item p-0 notify-item card read-noti shadow-none mb-2">
                                            <div class="card-body">
                                                <span class="float-end noti-close-btn text-muted"><i class="mdi mdi-close"></i></span>   
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <div class="notify-icon">
                                                            <img src="{{asset('public/admin_package/assets/images/users/avatar-4.jpg')}}" class="img-fluid rounded-circle" alt="" />
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 text-truncate ms-2">
                                                        <h5 class="noti-item-title fw-semibold font-14">Karen Robinson</h5>
                                                        <small class="noti-item-subtitle text-muted">Wow ! this admin looks good and awesome design</small>
                                                    </div>
                                                  </div>
                                            </div>
                                        </a>

                                        <div class="text-center">
                                            <i class="mdi mdi-dots-circle mdi-spin text-muted h3 mt-0"></i>
                                        </div>
                                    </div>

                                    <!-- All-->
                                    <a href="javascript:void(0);" class="dropdown-item text-center text-primary notify-item border-top border-light py-2">
                                        View All
                                    </a>

                                </div>
                            </li>

                            <li class="dropdown notification-list d-none d-sm-inline-block">
                                <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                    <i class="dripicons-view-apps noti-icon"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated dropdown-lg p-0">

                                    <div class="p-2">
                                        <div class="row g-0">
                                            <div class="col">
                                                <a class="dropdown-icon-item" href="#">
                                                    <img src="{{asset('public/admin_package/assets/images/brands/slack.png')}}" alt="slack">
                                                    <span>Slack</span>
                                                </a>
                                            </div>
                                            <div class="col">
                                                <a class="dropdown-icon-item" href="#">
                                                    <img src="{{asset('public/admin_package/assets/images/brands/github.png')}}" alt="Github">
                                                    <span>GitHub</span>
                                                </a>
                                            </div>
                                            <div class="col">
                                                <a class="dropdown-icon-item" href="#">
                                                    <img src="{{asset('public/admin_package/assets/images/brands/dribbble.png')}}" alt="dribbble">
                                                    <span>Dribbble</span>
                                                </a>
                                            </div>
                                        </div>

                                        <div class="row g-0">
                                            <div class="col">
                                                <a class="dropdown-icon-item" href="#">
                                                    <img src="{{asset('public/admin_package/assets/images/brands/bitbucket.png')}}" alt="bitbucket">
                                                    <span>Bitbucket</span>
                                                </a>
                                            </div>
                                            <div class="col">
                                                <a class="dropdown-icon-item" href="#">
                                                    <img src="{{asset('public/admin_package/assets/images/brands/dropbox.png')}}" alt="dropbox">
                                                    <span>Dropbox</span>
                                                </a>
                                            </div>
                                            <div class="col">
                                                <a class="dropdown-icon-item" href="#">
                                                    <img src="{{asset('public/admin_package/assets/images/brands/g-suite.png')}}" alt="G Suite">
                                                    <span>G Suite</span>
                                                </a>
                                            </div>
                                        </div> <!-- end row-->
                                    </div>

                                </div>
                            </li>

                            <li class="notification-list">
                                <a class="nav-link end-bar-toggle" href="javascript: void(0);">
                                    <i class="dripicons-gear noti-icon"></i>
                                </a>
                            </li>

                            <li class="dropdown notification-list">
                                <a class="nav-link dropdown-toggle nav-user arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                                    aria-expanded="false">
                                    <span class="account-user-avatar"> 
                                        <img src="{{asset('public/admin_package/assets/images/users/avatar-1.jpg')}}" alt="user-image" class="rounded-circle">
                                    </span>
                                    <span>
                                        
                                        
                                        
                                        <span class="account-user-name">
                                        <?php
                                        $login_Name=Auth::guard('web')->user()->name;
                                        
                                        print_r($login_Name);
                                        
                                        ?>
                                        </span>
                                        <span class="account-position">
                                            <?php
                                             $email=Auth::guard('web')->user()->email;
                                           
                                        print_r($email);
                                        
                                        ?>
                                            </span>
                                    </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated topbar-dropdown-menu profile-dropdown">
                                    <!-- item-->
                                    <div class=" dropdown-header noti-title">
                                        <h6 class="text-overflow m-0">Welcome !</h6>
                                    </div>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <i class="mdi mdi-account-circle me-1"></i>
                                        <span>My Account</span>
                                    </a>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <i class="mdi mdi-account-edit me-1"></i>
                                        <span>Settings</span>
                                    </a>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <i class="mdi mdi-lifebuoy me-1"></i>
                                        <span>Support</span>
                                    </a>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <i class="mdi mdi-lock-outline me-1"></i>
                                        <span>Lock Screen</span>
                                    </a>

                                    <!-- item-->
                                    <a href="{{URL::to('logout')}}" class="dropdown-item notify-item">
                                        <i class="mdi mdi-logout me-1"></i>
                                        <span>Logout</span>
                                    </a>
                                </div>
                            </li>

                        </ul>
                        <button class="button-menu-mobile open-left">
                            <i class="mdi mdi-menu"></i>
                        </button>
                        <div class="app-search dropdown d-none d-lg-block">
                            <form>
                                <div class="input-group">
                                    <input type="text" class="form-control dropdown-toggle"  placeholder="Search..." id="top-search">
                                    <span class="mdi mdi-magnify search-icon"></span>
                                    <button class="input-group-text btn-primary" type="submit">Search</button>
                                </div>
                            </form>

                            <div class="dropdown-menu dropdown-menu-animated dropdown-lg" id="search-dropdown">
                                <!-- item-->
                                <div class="dropdown-header noti-title">
                                    <h5 class="text-overflow mb-2">Found <span class="text-danger">17</span> results</h5>
                                </div>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <i class="uil-notes font-16 me-1"></i>
                                    <span>Analytics Report</span>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <i class="uil-life-ring font-16 me-1"></i>
                                    <span>How can I help you?</span>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <i class="uil-cog font-16 me-1"></i>
                                    <span>User profile settings</span>
                                </a>

                                <!-- item-->
                                <div class="dropdown-header noti-title">
                                    <h6 class="text-overflow mb-2 text-uppercase">Users</h6>
                                </div>

                                <div class="notification-list">
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <div class="d-flex">
                                            <img class="d-flex me-2 rounded-circle" src="{{asset('public/admin_package/assets/images/users/avatar-2.jpg')}}" alt="Generic placeholder image" height="32">
                                            <div class="w-100">
                                                <h5 class="m-0 font-14">Erwin Brown</h5>
                                                <span class="font-12 mb-0">UI Designer</span>
                                            </div>
                                        </div>
                                    </a>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <div class="d-flex">
                                            <img class="d-flex me-2 rounded-circle" src="{{asset('public/admin_package/assets/images/users/avatar-5.jpg')}}" alt="Generic placeholder image" height="32">
                                            <div class="w-100">
                                                <h5 class="m-0 font-14">Jacob Deo</h5>
                                                <span class="font-12 mb-0">Developer</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end Topbar -->