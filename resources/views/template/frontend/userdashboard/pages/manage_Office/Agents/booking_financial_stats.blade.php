@extends('template/frontend/userdashboard/layout/default')
@section('content')
    
    <style>
        .nav-link{
            color: #575757;
            font-size: 18px;
        }
        .small-box {
            text-align: center;padding: 15px 0px 5px 0px;background-color: #313a46 !important;color: white;
        }
    </style>

    <div class="content-wrapper">
        
        <div class="dashboard-content">
            <h3 style="font-size: 32px;text-align:center mt-2">Booking Financial Statement</h3>
            
            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    <div class="dashboard-list-box dash-list margin-top-0">
                        <div class="row">
                            <div class="col-md-12">
                                 <table id="scroll-horizontal-datatable"  class="display nowrap table  table-bordered" style="width:100%;">
                                    <thead class="theme-bg-clr">
                                        <tr>
                                            <th style="text-align: center;"></th>
                                            <th style="text-align: center;"></th>
                                            <th style="text-align: center;"></th>
                                            <th style="text-align: center;"></th>
                                            <th style="text-align: center;" id="T_S_th"></th>
                                            <th style="text-align: center;" id="T_C_th"></th>
                                            <th style="text-align: center;" id="T_P_th"></th>
                                            <th style="text-align: center;" id="T_D_th"></th>
                                            <th style="text-align: center;" id="T_DS_th"></th>
                                            <th style="text-align: center;"></th>
                                        </tr>
                                        <tr>
                                            <th style="text-align: center;">Sr</th>
                                            <th style="text-align: center;">Customer Name</th>
                                            <th style="text-align: center;">Booking Id</th>
                                            <th style="text-align: center;">Booking Details</th>
                                            <th style="text-align: center;">Sale Price</th>
                                            <th style="text-align: center;">Cost Price</th>
                                            <th style="text-align: center;">Profit</th>
                                            <th style="text-align: center;">Discount</th>
                                            <th style="text-align: center;">Special Discount</th>
                                            <th style="text-align: center;">Booking Date</th>
                                        </tr>
                                    </thead>
                                        
                                        <tbody style="text-align: center;" id="tbody_all_data_acc">
                                            @if(isset($agents_data) && $agents_data != null && $agents_data != '')
                                                <?php
                                                    // dd($agents_data['agents_tour_booking']);
                                                    $T_S    = 0;
                                                    $T_C    = 0;
                                                    $T_P    = 0;
                                                    $T_D    = 0;
                                                    $T_DS   = 0;
                                                    $x      = 1;
                                                ?>
                                                @if(isset($agents_data['agents_tour_booking']) && $agents_data['agents_tour_booking'] != null && $agents_data['agents_tour_booking'] != '')
                                                    @foreach($agents_data['agents_tour_booking'] as $agent_res)
                                                        @if($agent_res['total_sale'] > 0)
                                                            <tr role="row">
                                                                <td>{{ $x++ }}</td>
                                                                <td>
                                                                    @if(isset($all_Users) && $all_Users != null && $all_Users != '')
                                                                        @foreach($all_Users as $all_Users_value)
                                                                            @if($agent_res['customer_id'] == $all_Users_value->id)
                                                                                <b>{{ $all_Users_value->name }}</b>
                                                                                <?php $currency = $all_Users_value->currency_symbol; ?>
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    {{ $agent_res['invoice_id'] }}
                                                                    <a style="display:none" href="{{ URL::to('invoice_package/'.$agent_res['invoice_id'].'/'.$agent_res['booking_id'].'/'.$agent_res['tour_id'].'') }}">
                                                                        <img height="15px" width="15px" src="{{asset('public/invoice_icon.png') }}">
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    <div class="row">
                                                                        <div class="col-xl-12">    
                                                                            <?php
                                                                                if($agent_res['agent_name'] == '-1'){
                                                                            ?>
                                                                                @if($agent_res['passenger_name'] != null && $agent_res['passenger_name'] != '')
                                                                                    <b>{{ $agent_res['passenger_name'] }} - Customer</b><br>
                                                                                @else
                                                                                    <b>Customer</b>
                                                                                @endif
                                                                                
                                                                            <?php }else{ ?>
                                                                                @if($agent_res['agent_name'] != null && $agent_res['agent_name'] != '')
                                                                                    <b>{{ $agent_res['agent_name'] }} - Agent</b>
                                                                                @else
                                                                                    <b>Agent</b>
                                                                                @endif
                                                                                <a style="display:none" href="{{ URL::to('agents_stats_details/'.$agent_res['agent_Id']) }}">
                                                                                    <i class="mdi mdi-account-check-outline"></i>
                                                                                </a>
                                                                            <?php } ?>
                                                                        </div>
                                                                        <div class="col-xl-12">
                                                                            <b>{{ $agent_res['title'] }} - Package</b>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                </td>
                                                                <td>{{ $currency }} {{ number_format($agent_res['total_sale']) }}</td>
                                                                <td>{{ $currency }} {{ number_format($agent_res['total_cost']) }}</td>
                                                                <td>{{ $currency }} {{ number_format($agent_res['profit']) }}</td>
                                                                <td>{{ $currency }} {{ number_format($agent_res['discount_am']) }}</td>
                                                                <td>{{ $currency }} {{ number_format($agent_res['special_discount']) }}</td>
                                                                <td>
                                                                    {{ date("d-m-Y", strtotime($agent_res['created_at'])) }}
                                                                    <a style="display:none" href="{{ URL::to('invoice_package/'.$agent_res['invoice_id'].'/'.$agent_res['booking_id'].'/'.$agent_res['tour_id'].'') }}">
                                                                        <img height="15px" width="15px" src="{{asset('public/invoice_icon.png') }}">
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            <?php 
                                                                $T_S    = $T_S + $agent_res['total_sale'];
                                                                $T_C    = $T_C + $agent_res['total_cost'];
                                                                $T_P    = $T_P + $agent_res['profit'];
                                                                $T_D    = $T_D + $agent_res['discount_am'];
                                                                $T_DS   = $T_DS + $agent_res['special_discount'];
                                                            ?>
                                                        @endif
                                                    @endforeach
                                                @endif
                                                
                                                @if(isset($agents_data['agents_invoices_booking']) && $agents_data['agents_invoices_booking'] != null && $agents_data['agents_invoices_booking'] != '')
                                                    @foreach($agents_data['agents_invoices_booking'] as $agent_res)
                                                        @if($agent_res['total_sale'] > 0)
                                                            <tr role="row">
                                                                <td>{{ $x++ }}</td>
                                                                <td>
                                                                    @if(isset($all_Users) && $all_Users != null && $all_Users != '')
                                                                        @foreach($all_Users as $all_Users_value)
                                                                            @if($agent_res['customer_id'] == $all_Users_value->id)
                                                                                <b>{{ $all_Users_value->name }}</b>
                                                                                <?php $currency = $all_Users_value->currency_symbol; ?>
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    {{ $agent_res['generate_id'] }}
                                                                    <a style="display:none" href="{{ URL::to('invoice_Agent/'.$agent_res['invoice_id']) }}">
                                                                        <img height="15px" width="15px" src="{{asset('public/invoice_icon.png') }}">
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    <div class="row">
                                                                        <div class="col-xl-12">
                                                                            @if($agent_res['agent_name'] != null && $agent_res['agent_name'] != '')
                                                                                <b>{{ $agent_res['agent_name'] }} - Agent</b>
                                                                            @else
                                                                                <b>Agent</b>
                                                                            @endif
                                                                            <a style="display:none" href="{{ URL::to('agents_stats_details/'.$agent_res['agent_Id']) }}"><i class="mdi mdi-account-check-outline"></i></a>
                                                                        </div>
                                                                        <div class="col-xl-12">
                                                                            <b>{{ $agent_res['service_type'] }} - Invoice</b>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>{{ $currency }} {{ number_format($agent_res['total_sale']) }}</td>
                                                                <td>{{ $currency }} {{ number_format($agent_res['total_cost']) }}</td>
                                                                <td>{{ $currency }} {{ number_format($agent_res['profit']) }}</td>
                                                                <td>{{ $currency }} 0</td>
                                                                <td>{{ $currency }} 0</td>
                                                                <td>
                                                                    {{ date("d-m-Y", strtotime($agent_res['created_at'])) }}
                                                                    <a style="display:none" href="{{ URL::to('invoice_Agent/'.$agent_res['invoice_id']) }}">
                                                                        <img height="15px" width="15px" src="{{asset('public/invoice_icon.png') }}">
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            <?php 
                                                                $T_S = $T_S + $agent_res['total_sale'];
                                                                $T_C = $T_C + $agent_res['total_cost'];
                                                                $T_P = $T_P + $agent_res['profit'];
                                                            ?>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endif
                                        </tbody>
                                    
                                    </table>
                                    
                                    <input type="hidden" id="T_S_td" value="{{ $currency }} {{ number_format($T_S) }}">
                                    <input type="hidden" id="T_C_td" value="{{ $currency }} {{ number_format($T_C) }}">
                                    <input type="hidden" id="T_P_td" value="{{ $currency }} {{ number_format($T_P) }}">
                                    <input type="hidden" id="T_D_td" value="{{ $currency }} {{ number_format($T_D) }}">
                                    <input type="hidden" id="T_DS_td" value="{{ $currency }} {{ number_format($T_DS) }}">
                            </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    
    </div>
        
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    
    <script>
        $(document).ready( function () {
            
            var T_S_td = $('#T_S_td').val();
            $('#T_S_th').html(T_S_td);
            
            var T_C_td = $('#T_C_td').val();
            $('#T_C_th').html(T_C_td);
            
            var T_P_td = $('#T_P_td').val();
            $('#T_P_th').html(T_P_td);
            
            var T_D_td = $('#T_D_td').val();
            $('#T_D_th').html(T_D_td);
            
            var T_DS_td = $('#T_DS_td').val();
            $('#T_DS_th').html(T_DS_td);
            
        });
    </script>

@endsection
