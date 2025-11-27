@extends('template/frontend/userdashboard/layout/default')
@section('content')
    
    <?php $all_Currency = Session::get('currency_symbol'); $invoice_URL = ''; ?>
    
    <style>
        .cst_css
        {
            color: red;
            font-weight: bold;
        }
    </style>
    
    <div class="mt-5">
        
        @if(session()->has('success'))
            <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show" role="alert">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>{{session()->get('success')}} </strong>
            </div>
        @endif
        
        @if(session()->has('error'))
            <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show" role="alert">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>{{session()->get('error')}} </strong>
            </div>
        @endif
        
        <div class="row">
            <div class="col-12">
                <div class="card" style="background: #f1f5f8;">
                    <div class="card-body">
                        <h4 class="header-title">Hotel Provider Bookings</h4>
                        <p class="text-muted font-14"></p> 
                        
                        <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                          <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Confirmed</button>
                          </li>
                          <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Cancelled</button>
                          </li>
                          <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Failed</button>
                          </li>
                          <li class="nav-item" role="presentation">
                            <button class="nav-link" id="non-refund-tab" data-bs-toggle="tab" data-bs-target="#non-refund-tab-pane" type="button" role="tab" aria-controls="non-refund-tab-pane" aria-selected="false">Non Refundable</button>
                          </li>
                          <li class="nav-item" role="presentation">
                            <button class="nav-link" id="refund-tab" data-bs-toggle="tab" data-bs-target="#refund-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Refund</button>
                          </li>
                        </ul>
                        
                        <div class="tab-content" id="myTabContent">
                            
                            <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                                <table style="font-weight: bold;font-family: serif;" id="example_1" class="table dt-responsive nowrap w-100">
                                    <thead class="theme-bg-clr">
                                        <tr>
                                            <th>id</th>
                                            <th>Invoice No</th>
                                            <th>Hotel Name</th>
                                            <th>Total Amount</th>
                                            <th>Commission Amount</th>
                                            <th>Payable Amount</th>
                                            <th>Exchange Rate</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        @foreach($all_booking_data as $hotel_booking)
                                            <?php
                                                // dd($all_booking_data);
                                                
                                                if(isset($hotel_booking->hotel_booking_status)){
                                                    if($hotel_booking->hotel_booking_status == 'CONFIRMED' || $hotel_booking->hotel_booking_status == 'Confirmed'){
                                                        
                                                        // if($hotel_booking->invoice_no == 'ST9502091'){
                                                        //     dd($hotel_booking);
                                                        // }
                                                        
                                                        foreach($get_data_customer_subcriptions as $val_CS){
                                                            if($val_CS->id == $hotel_booking->customer_id){
                                                                $customer_subcriptions  = $val_CS;
                                                                if($hotel_booking->customer_id == 48 || $hotel_booking->customer_id == '48'){
                                                                    $invoice_URL            = 'https://alsubaee.com/hotel_invoice';
                                                                }else{
                                                                    if($val_CS->webiste_Address != null && $val_CS->webiste_Address != ''){
                                                                        $invoice_URL            = $val_CS->webiste_Address.'/hotel_booking_invoice';
                                                                    }else{
                                                                        $invoice_URL            = 'hotel_booking_invoice';
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        
                                                        // $customer_subcriptions          = $get_data_customer_subcriptions;
                                                        $name                           = $customer_subcriptions->name;
                                                        $lname                          = $customer_subcriptions->lname;
                                                        $client_name                    = $name . $lname;
                                                        $hotel_payment_details_total    = 0;
                                            ?>
                                                    <tr>
                                                        <td>{{ $loop->iteration}}</td>
                                                        <td>
                                                            <?php
                                                            if(isset($hotel_booking->booked) && $hotel_booking->booked == 'admin')
                                                            {
                                                            ?>
                                                             <b style="color:red;font-weight:bold;">Admin</b><br>
                                                        <a style="color: #7e7d7b;font-weight: bold;" target="_blank" href="{{ $invoice_URL }}/{{ $hotel_booking->invoice_no }}">
                                                        <?php print_r($hotel_booking->invoice_no); 
                                                       
                                                        ?>
                                                        <img height="15px" width="15px" src="https://system.alhijaztours.net/public/invoice_icon.png">   
                                                        </a>
                                                        <?php
                                                        
                                                            }
                                                            else
                                                            {
                                                        ?>
                                                        <b style="color:red;font-weight:bold;">Customer</b><br>
                                                         <a style="color: #7e7d7b;font-weight: bold;" target="_blank" href="{{ $invoice_URL }}/{{ $hotel_booking->invoice_no }}">
                                                        <?php print_r($hotel_booking->invoice_no); 
                                                       
                                                        ?>
                                                        <img height="15px" width="15px" src="https://system.alhijaztours.net/public/invoice_icon.png">   
                                                        </a>
                                                        
                                                        <?php
                                                            }
                                                        ?>
                                                        </br>
                                                        <?php
                                                            $lead_passenger_Data = json_decode($hotel_booking->lead_passenger_data);
                                                            $reservation_response = json_decode($hotel_booking->reservation_response);

                                                            //  dd($reservation_response);
                                                            ?>
                                                            </br>
                                                            {{ $reservation_response->reference_no ?? '' }}
                                                            <a class="cst_css" href="javascript:;">
                                                                
                                                                <?php 
                                                                    if(isset($hotel_booking->hotel_booking_status) && $hotel_booking->hotel_booking_status == 'non_refundable'){
                                                                        echo "Non Procced Yet ";
                                                                    }else{
                                                                        echo $hotel_booking->status ?? '';
                                                                    }
                                                                ?>
                                                            </a><br>
                                                                <?php 
                                                                if(isset($lead_passenger_Data))
                                                                {
                                                                    
                                                                        echo 'Customer Name :';
                                                                        echo '</br>';
                                                                echo $hotel_booking->lead_passenger ?? $lead_passenger_Data->lead_first_name . ' ' .$lead_passenger_Data->lead_last_name; 
                                                                echo '</br>';
                                                                echo '3rd Party';
                                                                }
                                                                ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                // dd($reservation_response);
                                                                print_r(\Illuminate\Support\Str::limit($reservation_response->hotel_details->hotel_name ?? '', 15, '...'));
                                                                echo '</br>';
                                                                echo 'NumAdults: ' . ($hotel_booking->total_adults ?? '');
                                                                echo '</br>';
                                                                echo 'NumChildren: ' . ($hotel_booking->total_childs ?? '');
                                                                echo '</br>';
                                                            ?>
                                                        </td>
                                                        <td>
                                                            {{ $all_Currency }} {{ $hotel_booking->total_markup_price ?? $hotel_booking->exchange_price ?? '0' }}
                                                        </td>
                                                        <td>
                                                            {{ $all_Currency }} {{ $hotel_booking->exchange_client_commission_amount ?? $hotel_booking->client_commission_amount ?? '0' }}
                                                        </td>
                                                        <td>
                                                            {{ $all_Currency }} {{ $hotel_booking->exchange_payable_price ?? $hotel_booking->payable_price ?? '0' }}
                                                        </td>
                                                        <td>
                                                            {{ $hotel_booking->exchange_rate ?? $hotel_booking->base_exchange_rate ?? '0' }}
                                                        </td>
                                                        <td>
                                                            
                                                        <?php 
                                                            if(isset($hotel_booking->hotel_booking_status) && $hotel_booking->hotel_booking_status == 'non_refundable'){
                                                        ?>
                                                            <a  class="btn btn-info" target="_blank" href="{{ URL::to('proccess_non_refundable_booking/'.$hotel_booking->invoice_no.'') }}">Proccess Now</a>
                                                        <?php
                                                            }else if(isset($hotel_booking->hotel_booking_status) && $hotel_booking->hotel_booking_status == 'CONFIRMED' || $hotel_booking->hotel_booking_status == 'Confirmed'){
                                                        ?>
                                                                <a  class="btn btn-success" target="_blank" href="{{ $invoice_URL }}/{{ $hotel_booking->invoice_no }}">Voucher</a>  
                                                        <?php
                                                            }
                                                            else if(isset($hotel_booking->hotel_booking_status) && $hotel_booking->hotel_booking_status == 'Cancelled' || $hotel_booking->hotel_booking_status == 'Cancelled'){
                                                        ?>
                                                                <a  class="btn btn-success" target="_blank" href="{{ $invoice_URL }}/{{ $hotel_booking->invoice_no }}">Voucher</a>  
                                                        <?php
                                                            if($hotel_booking->payment_details != NULL && $hotel_booking->payment_refunds == NULL)
                                                            {
                                                        ?>
                                                                <a  class="btn btn-success" href="{{ URL::to('refund_payment/'.$hotel_booking->invoice_no.'') }}">Refund Payment</a> 
                                                        <?php
                                                            }
                                                            else
                                                            {
                                                        ?>
                                                                <a  class="btn btn-success" href="javascript:;">refunded</a>  
                                                        <?php
                                                            }
                                                            }else if(isset($hotel_booking->hotel_booking_status) && $hotel_booking->hotel_booking_status == 'Failed' || $hotel_booking->hotel_booking_status == 'Failed'){
                                                                if($hotel_booking->payment_refunds == NULL)
                                                                {
                                                        ?>
                                                                    <a  class="btn btn-success" href="{{ URL::to('refund_payment/'.$hotel_booking->invoice_no.'') }}">Refund Payment</a>  
                                                        <?php
                                                                }
                                                                else
                                                                {
                                                        ?>
                                                                    <a  class="btn btn-success" href="javascript:;">refunded</a>  
                                                        <?php
                                                                }
                                                            }
                                                        ?>
                                                        </td>
                                                    </tr>
                                            <?php
                                                    }
                                                }
                                            ?>
                                        @endforeach
                                    </tbody>
                                </table>   
                            </div>
                            
                            <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                             
                             <table style="font-weight: bold;font-family: serif;" id="example_2" class="table dt-responsive nowrap w-100">
                                                                            <thead class="theme-bg-clr">
                                                                                <tr>
                                                                                    <th>id</th>
                                                                                    <th>Invoice No</th>
                                                                                   
                                                                                    <th>Hotel Name</th>
                                                                                  <th>Total Amount</th>
                                                                                    <th>Commission Amount</th>
                                                                                    <th>Payable Amount</th>
                                                                                    <th>Exchange Rate</th>
                                                                                    <th>Action</th>
                                                                                    
                                                                                   
                                                                                </tr>
                                                                            </thead>
                                                                        
                                                                        
                                                                            <tbody>
                                                                                
                                                                                @foreach($all_booking_data as $hotel_booking)
                                                                                
                                                                                <?php
                                                                               if($hotel_booking->hotel_booking_status == 'Cancelled' && $hotel_booking->payment_refunds != NULL)
                                                                               {
                                                                                //  $customer_subcriptions=$get_data_customer_subcriptions;
                                                                                
                                                                                foreach($get_data_customer_subcriptions as $val_CS){
                                                                                    if($val_CS->id == $hotel_booking->customer_id){
                                                                                        $customer_subcriptions = $val_CS;
                                                                                        if($hotel_booking->customer_id == 48 || $hotel_booking->customer_id == '48'){
                                                                                            $invoice_URL            = 'https://alsubaee.com/hotel_invoice';
                                                                                        }else{
                                                                                            if($val_CS->webiste_Address != null && $val_CS->webiste_Address != ''){
                                                                                                $invoice_URL            = $val_CS->webiste_Address.'/hotel_booking_invoice';
                                                                                            }else{
                                                                                                $invoice_URL            = 'hotel_booking_invoice';
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                             
                                                                                $name=$customer_subcriptions->name;
                                                                                $lname=$customer_subcriptions->lname;
                                                                                $client_name=$name . $lname;
                                                                                
                                                                                
                                                                           
                                                                                $hotel_payment_details_total=0;
                                                                                
                                                                                ?>
                                                                       
                                                                                <tr>
                                                                                    <td>{{ $loop->iteration}}</td>
                                                                                    <td>
                                                                                        <?php
                                                                                        if(isset($hotel_booking->booked) && $hotel_booking->booked == 'admin')
                                                                                        {
                                                                                        ?>
                                                                                         <b style="color:red;font-weight:bold;">Admin</b><br>
                                                                                    <a style="color: #7e7d7b;font-weight: bold;" target="_blank" href="{{ $invoice_URL }}/{{ $hotel_booking->invoice_no }}">
                                                                                    <?php print_r($hotel_booking->invoice_no); 
                                                                                   
                                                                                    ?>
                                                                                    <img height="15px" width="15px" src="https://system.alhijaztours.net/public/invoice_icon.png">   
                                                                                    </a>
                                                                                    <?php
                                                                                    
                                                                                        }
                                                                                        else
                                                                                        {
                                                                                    ?>
                                                                                     <b style="color:red;font-weight:bold;">Customer</b><br>
                                                                                     <a style="color: #7e7d7b;font-weight: bold;" target="_blank" href="{{ $invoice_URL }}/{{ $hotel_booking->invoice_no }}">
                                                                                    <?php print_r($hotel_booking->invoice_no); 
                                                                                   
                                                                                    ?>
                                                                                    <img height="15px" width="15px" src="https://system.alhijaztours.net/public/invoice_icon.png">   
                                                                                    </a>
                                                                                    
                                                                                    <?php
                                                                                        }
                                                                                    ?>
                                                                                    </br>
                                                                                    <?php
                                                                                         $lead_passenger_Data = json_decode($hotel_booking->lead_passenger_data);
                                                                                         $reservation_response = json_decode($hotel_booking->reservation_response);
                        
                                                                                        //  dd($reservation_response);
                                                                                        ?>
                                                                                        </br>
                                                                                        {{ $reservation_response->reference_no ?? '' }}
                                                                                        <a class="cst_css" href="javascript:;">
                                                                                            
                                                                                            <?php 
                                                                                                if(isset($hotel_booking->hotel_booking_status) && $hotel_booking->hotel_booking_status == 'non_refundable'){
                                                                                                    echo "Non Procced Yet ";
                                                                                                }else{
                                                                                                    echo $hotel_booking->status ?? '';
                                                                                                }
                                                                                            ?>
                                                                                        </a></br>
                              
                                                                                            <?php 
                                                                                            
                                                                                            if(isset($lead_passenger_Data))
                                                                                            {
                                                                                                
                                                                                                    echo 'Customer Name :';
                                                                                                    echo '</br>';
                                                                                            echo $hotel_booking->lead_passenger ?? $lead_passenger_Data->lead_first_name . ' ' .$lead_passenger_Data->lead_last_name; 
                                                                                            echo '</br>';
                                                                                            echo '3rd Party';
                                                                                            }
                                                                                            ?>
                            
                            
                        
                                                                                    </td>
                                                                                    
                                                                                    
                                                                                    
                                                                                      <td>
                                                                                        <?php
                                                                                        
                                                                                      
                                                                                           print_r($reservation_response->hotel_details->hotel_name ?? ''); 
                                                                                            echo '</br>';
                                                                                      
                                                                                        
                                                                                        ?>
                                                                                        
                                                                                        <?php
                                                                                     
                                                                                           
                                                                                           
                                                                                            echo 'NumAdults: ' . ($hotel_booking->total_adults ?? '');
                                                                                             echo '</br>';
                                                                                            echo 'NumChildren: ' . ($hotel_booking->total_childs ?? '');
                                                                                            echo '</br>';
                                                                                           
                                                                                       
                                                                                        ?>
                                                                                        
                                                                                    </td>
                                                                                   
                                                                                   
                                                                                    <td>
                                                                                        {{$customer_subcriptions->currency_symbol}} {{$hotel_booking->exchange_total_markup_price ?? '0'}}
                                                                                        </br>
                                                                                        {{$hotel_booking->currency}} {{$hotel_booking->total_markup_price}}
                                                                                        
                                                                                      
                                                                                        
                                                                                        
                                                                                    </td>
                                                                                    
                                                                                    <td>
                                                                                        {{$customer_subcriptions->currency_symbol}} {{$hotel_booking->exchange_client_commission_amount ?? '0'}}
                                                                                         </br>
                                                                                       {{$hotel_booking->currency}} {{$hotel_booking->client_commission_amount}}
                                                                                         
                                                                                        
                                                                                   
                                                                                      
                                                                                        
                                                                                        
                                                                                    </td>
                                                                                    <td>
                                                                                        {{$customer_subcriptions->currency_symbol}} {{$hotel_booking->exchange_payable_price ?? '0'}}
                                                                                         </br>
                                                                                        {{$hotel_booking->currency}} {{$hotel_booking->payable_price}}
                                                                                      
                                                                                        
                                                                                        
                                                                                    </td>
                                                                                    <td>
                                                                                        
                                                                                    {{$hotel_booking->exchange_rate ?? '0'}}
                                                                                      
                                                                                        
                                                                                        
                                                                                    </td>
                                                                                   
                                                                                    
                                                                                    
                                                                                  
                                                                                    
                                                                                    <td>
                                                                                        
                                                                                    <?php 
                                                                                        if(isset($hotel_booking->hotel_booking_status) && $hotel_booking->hotel_booking_status == 'non_refundable'){
                                                                                            ?>
                                                                                            <a  class="btn btn-info" target="_blank" href="{{ URL::to('proccess_non_refundable_booking/'.$hotel_booking->invoice_no.'') }}">Proccess Now</a>
                                                                                            <?php
                                                                                        }else if(isset($hotel_booking->hotel_booking_status) && $hotel_booking->hotel_booking_status == 'CONFIRMED' || $hotel_booking->hotel_booking_status == 'Confirmed'){
                                                                                            ?>
                                                                                            <a  class="btn btn-success" target="_blank" href="{{ $invoice_URL }}/{{ $hotel_booking->invoice_no }}">Voucher</a>  
                                                                                            <?php
                                                                                        }
                                                                                        else if(isset($hotel_booking->hotel_booking_status) && $hotel_booking->hotel_booking_status == 'Cancelled' || $hotel_booking->hotel_booking_status == 'Cancelled'){
                                                                                            ?>
                                                                                            <a  class="btn btn-success" target="_blank" href="{{ $invoice_URL }}/{{ $hotel_booking->invoice_no }}">Voucher</a>  
                                                                                            <?php
                                                                                             if($hotel_booking->payment_details != NULL && $hotel_booking->payment_refunds == NULL)
                                                                                            {
                                                                                                ?>
                                                                                                <a  class="btn btn-success" href="{{ URL::to('refund_payment/'.$hotel_booking->invoice_no.'') }}">Refund Payment</a> 
                                                                                              <?php
                                                                                        }
                                                                                        else
                                                                                        {
                                                                                            ?>
                                                                                             <a  class="btn btn-success" href="javascript:;">refunded</a>  
                                                                                            <?php
                                                                                        }
                                                                                        
                                                                                              
                                                                                        
                                                                                        }else if(isset($hotel_booking->hotel_booking_status) && $hotel_booking->hotel_booking_status == 'Failed' || $hotel_booking->hotel_booking_status == 'Failed'){
                                                                                            if($hotel_booking->payment_refunds == NULL)
                                                                                            {
                                                                                            ?>
                                                                                            <a  class="btn btn-success" href="{{ URL::to('refund_payment/'.$hotel_booking->invoice_no.'') }}">Refund Payment</a>  
                                                                                            <?php
                                                                                        }
                                                                                        
                                                                                        else
                                                                                        {
                                                                                            ?>
                                                                                             <a  class="btn btn-success" href="javascript:;">refunded</a>  
                                                                                            <?php
                                                                                        }
                                                                                        }
                                                                                              ?>
                                                                                       
                                                                                        
                                                                                    </td>
                                                                                    
                                                                                    
                                                                                   
                                                                                    
                                                                                         
                                                                                         
                                                                                         
                                                                                         
                                                                                        
                                                                                        
                                                                                    
                                                                                    
                                                                                       
                                                                                </tr>
                                                                                
                                                                                
                                                                               
                                                                            <?php
                                                                               }
                                                                            ?>
                                                                                
                                                                                
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>   
                              
                          </div>
                            
                            <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
                              <table style="font-weight: bold;font-family: serif;" id="example_3" class="table dt-responsive nowrap w-100">
                                                                            <thead class="theme-bg-clr">
                                                                                <tr>
                                                                                    <th>id</th>
                                                                                    <th>Invoice No</th>
                                                                                   
                                                                                    <th>failed</th>
                                                                                  
                                                                                    <th>Action</th>
                                                                                    
                                                                                   
                                                                                </tr>
                                                                            </thead>
                                                                        
                                                                        
                                                                            <tbody>
                                                                                
                                                                                @foreach($all_booking_data as $hotel_booking)
                                                                                
                                                                                <?php
                                                                               if($hotel_booking->hotel_booking_status == 'Failed' && $hotel_booking->payment_refunds != NULL)
                                                                               {
                                                                                //  $customer_subcriptions=$get_data_customer_subcriptions;
                                                                                
                                                                                foreach($get_data_customer_subcriptions as $val_CS){
                                                                                    if($val_CS->id == $hotel_booking->customer_id){
                                                                                        $customer_subcriptions = $val_CS;
                                                                                        if($hotel_booking->customer_id == 48 || $hotel_booking->customer_id == '48'){
                                                                                            $invoice_URL            = 'https://alsubaee.com/hotel_invoice';
                                                                                        }else{
                                                                                            if($val_CS->webiste_Address != null && $val_CS->webiste_Address != ''){
                                                                                                $invoice_URL            = $val_CS->webiste_Address.'/hotel_booking_invoice';
                                                                                            }else{
                                                                                                $invoice_URL            = 'hotel_booking_invoice';
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                             
                                                                                $name=$customer_subcriptions->name;
                                                                                $lname=$customer_subcriptions->lname;
                                                                                $client_name=$name . $lname;
                                                                                
                                                                                
                                                                           
                                                                                $hotel_payment_details_total=0;
                                                                                
                                                                                ?>
                                                                       
                                                                                <tr>
                                                                                    <td>{{ $loop->iteration}}</td>
                                                                                    <td>
                                                                                        <?php
                                                                                        if(isset($hotel_booking->booked) && $hotel_booking->booked == 'admin')
                                                                                        {
                                                                                        ?>
                                                                                         <b style="color:red;font-weight:bold;">Admin</b><br>
                                                                                    <a style="color: #7e7d7b;font-weight: bold;" target="_blank" href="{{ $invoice_URL }}/{{ $hotel_booking->invoice_no }}">
                                                                                    <?php print_r($hotel_booking->invoice_no); 
                                                                                   
                                                                                    ?>
                                                                                    <img height="15px" width="15px" src="https://system.alhijaztours.net/public/invoice_icon.png">   
                                                                                    </a>
                                                                                    <?php
                                                                                    
                                                                                        }
                                                                                        else
                                                                                        {
                                                                                    ?>
                                                                                     <b style="color:red;font-weight:bold;">Customer</b><br>
                                                                                     <a style="color: #7e7d7b;font-weight: bold;" target="_blank" href="{{ $invoice_URL }}/{{ $hotel_booking->invoice_no }}">
                                                                                    <?php print_r($hotel_booking->invoice_no); 
                                                                                   
                                                                                    ?>
                                                                                    <img height="15px" width="15px" src="https://system.alhijaztours.net/public/invoice_icon.png">   
                                                                                    </a>
                                                                                    
                                                                                    <?php
                                                                                        }
                                                                                    ?>
                                                                                    </br>
                                                                                    <?php
                                                                                         $lead_passenger_Data = json_decode($hotel_booking->lead_passenger_data);
                                                                                         $reservation_response = json_decode($hotel_booking->reservation_response);
                        
                                                                                        //  dd($reservation_response);
                                                                                        ?>
                                                                                        </br>
                                                                                        {{ $reservation_response->reference_no ?? '' }}
                                                                                        <a class="cst_css" href="javascript:;">
                                                                                            
                                                                                            <?php 
                                                                                                if(isset($hotel_booking->hotel_booking_status) && $hotel_booking->hotel_booking_status == 'non_refundable'){
                                                                                                    echo "Non Procced Yet ";
                                                                                                }else{
                                                                                                    echo $hotel_booking->status ?? '';
                                                                                                }
                                                                                            ?>
                                                                                        </a></br>
                              
                                                                                            <?php 
                                                                                            
                                                                                            if(isset($lead_passenger_Data))
                                                                                            {
                                                                                                
                                                                                                    echo 'Customer Name :';
                                                                                                    echo '</br>';
                                                                                            echo $hotel_booking->lead_passenger ?? $lead_passenger_Data->lead_first_name . ' ' .$lead_passenger_Data->lead_last_name; 
                                                                                            echo '</br>';
                                                                                            echo '3rd Party';
                                                                                            }
                                                                                            ?>
                            
                            
                        
                                                                                    </td>
                                                                                    
                                                                                    
                                                                                    
                                                                                     <td><?php print_r($hotel_booking->reservation_response); ?></td>
                                                                                     
                                                                                     
                                                                                     
                                                                                    
                                                                                    
                                                                                  
                                                                                    
                                                                                    <td>
                                                                                        
                                                                                    <?php 
                                                                                        if(isset($hotel_booking->hotel_booking_status) && $hotel_booking->hotel_booking_status == 'non_refundable'){
                                                                                            ?>
                                                                                            <a  class="btn btn-info" target="_blank" href="{{ URL::to('proccess_non_refundable_booking/'.$hotel_booking->invoice_no.'') }}">Proccess Now</a>
                                                                                            <?php
                                                                                        }else if(isset($hotel_booking->hotel_booking_status) && $hotel_booking->hotel_booking_status == 'CONFIRMED' || $hotel_booking->hotel_booking_status == 'Confirmed'){
                                                                                            ?>
                                                                                            <a  class="btn btn-success" target="_blank" href="{{ $invoice_URL }}/{{ $hotel_booking->invoice_no }}">Voucher</a>  
                                                                                            <?php
                                                                                        }
                                                                                        else if(isset($hotel_booking->hotel_booking_status) && $hotel_booking->hotel_booking_status == 'Cancelled' || $hotel_booking->hotel_booking_status == 'Cancelled'){
                                                                                            ?>
                                                                                            <a  class="btn btn-success" target="_blank" href="{{ $invoice_URL }}/{{ $hotel_booking->invoice_no }}">Voucher</a>  
                                                                                            <?php
                                                                                             if($hotel_booking->payment_details != NULL && $hotel_booking->payment_refunds == NULL)
                                                                                            {
                                                                                                ?>
                                                                                                <a  class="btn btn-success" href="{{ URL::to('refund_payment/'.$hotel_booking->invoice_no.'') }}">Refund Payment</a> 
                                                                                              <?php
                                                                                        }
                                                                                        else
                                                                                        {
                                                                                            ?>
                                                                                             <a  class="btn btn-success" href="javascript:;">refunded</a>  
                                                                                            <?php
                                                                                        }
                                                                                        
                                                                                              
                                                                                        
                                                                                        }else if(isset($hotel_booking->hotel_booking_status) && $hotel_booking->hotel_booking_status == 'Failed' || $hotel_booking->hotel_booking_status == 'Failed'){
                                                                                            if($hotel_booking->payment_refunds == NULL)
                                                                                            {
                                                                                            ?>
                                                                                            <a  class="btn btn-success" href="{{ URL::to('refund_payment/'.$hotel_booking->invoice_no.'') }}">Refund Payment</a>  
                                                                                            <?php
                                                                                        }
                                                                                        
                                                                                        else
                                                                                        {
                                                                                            ?>
                                                                                             <a  class="btn btn-success" href="javascript:;">refunded</a>  
                                                                                            <?php
                                                                                        }
                                                                                        }
                                                                                              ?>
                                                                                       
                                                                                        
                                                                                    </td>
                                                                                    
                                                                                    
                                                                                   
                                                                                    
                                                                                         
                                                                                         
                                                                                         
                                                                                         
                                                                                        
                                                                                        
                                                                                    
                                                                                    
                                                                                       
                                                                                </tr>
                                                                                
                                                                                
                                                                               
                                                                            <?php
                                                                               }
                                                                            ?>
                                                                                
                                                                                
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>   
                          </div>
                            
                            <div class="tab-pane fade" id="non-refund-tab-pane" role="tabpanel" aria-labelledby="non-refund-tab" tabindex="0">
                             
                             <table style="font-weight: bold;font-family: serif;" id="example_4" class="table dt-responsive nowrap w-100">
                                                                            <thead class="theme-bg-clr">
                                                                                <tr>
                                                                                    <th>id</th>
                                                                                    <th>Invoice No</th>
                                                                                   
                                                                                    <th>Hotel Name</th>
                                                                                  <th>Total Amount</th>
                                                                                    <th>Commission Amount</th>
                                                                                    <th>Payable Amount</th>
                                                                                    <th>Exchange Rate</th>
                                                                                    <th>Action</th>
                                                                                    
                                                                                   
                                                                                </tr>
                                                                            </thead>
                                                                        
                                                                        
                                                                            <tbody>
                                                                                
                                                                                @foreach($all_booking_data as $hotel_booking)
                                                                                
                                                                                <?php
                                                                               if($hotel_booking->hotel_booking_status == 'non_refundable')
                                                                               {
                                                                                //  $customer_subcriptions=$get_data_customer_subcriptions;
                                                                                
                                                                                foreach($get_data_customer_subcriptions as $val_CS){
                                                                                    if($val_CS->id == $hotel_booking->customer_id){
                                                                                        $customer_subcriptions = $val_CS;
                                                                                        if($hotel_booking->customer_id == 48 || $hotel_booking->customer_id == '48'){
                                                                                            $invoice_URL            = 'https://alsubaee.com/hotel_invoice';
                                                                                        }else{
                                                                                            if($val_CS->webiste_Address != null && $val_CS->webiste_Address != ''){
                                                                                                $invoice_URL            = $val_CS->webiste_Address.'/hotel_booking_invoice';
                                                                                            }else{
                                                                                                $invoice_URL            = 'hotel_booking_invoice';
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                             
                                                                                $name=$customer_subcriptions->name;
                                                                                $lname=$customer_subcriptions->lname;
                                                                                $client_name=$name . $lname;
                                                                                
                                                                                
                                                                           
                                                                                $hotel_payment_details_total=0;
                                                                                
                                                                                ?>
                                                                       
                                                                                <tr>
                                                                                    <td>{{ $loop->iteration}}</td>
                                                                                    <td>
                                                                                        <?php
                                                                                        if(isset($hotel_booking->booked) && $hotel_booking->booked == 'Agent')
                                                                                        {
                                                                                        ?>
                                                                                         <b style="color:red;font-weight:bold;">Admin</b><br>
                                                                                    <a style="color: #7e7d7b;font-weight: bold;" target="_blank" href="{{ $invoice_URL }}/{{ $hotel_booking->invoice_no }}">
                                                                                    <?php print_r($hotel_booking->invoice_no); 
                                                                                   
                                                                                    ?>
                                                                                    <img height="15px" width="15px" src="https://system.alhijaztours.net/public/invoice_icon.png">   
                                                                                    </a>
                                                                                    <?php
                                                                                    
                                                                                        }
                                                                                        else
                                                                                        {
                                                                                    ?>
                                                                                     <a style="color: #7e7d7b;font-weight: bold;" target="_blank" href="{{ $invoice_URL }}/{{ $hotel_booking->invoice_no }}">
                                                                                    <?php print_r($hotel_booking->invoice_no); 
                                                                                   
                                                                                    ?>
                                                                                    <img height="15px" width="15px" src="https://system.alhijaztours.net/public/invoice_icon.png">   
                                                                                    </a>
                                                                                    
                                                                                    <?php
                                                                                        }
                                                                                    ?>
                                                                                    </br>
                                                                                    <?php
                                                                                         $lead_passenger_Data = json_decode($hotel_booking->lead_passenger_data);
                                                                                         $reservation_response = json_decode($hotel_booking->reservation_response);
                        
                                                                                        //  dd($reservation_response);
                                                                                        ?>
                                                                                        </br>
                                                                                        {{ $reservation_response->reference_no ?? '' }}
                                                                                        <a class="cst_css" href="javascript:;">
                                                                                            
                                                                                            <?php 
                                                                                                if(isset($hotel_booking->hotel_booking_status) && $hotel_booking->hotel_booking_status == 'non_refundable'){
                                                                                                    echo "Non Procced Yet ";
                                                                                                }else{
                                                                                                    echo $hotel_booking->status ?? '';
                                                                                                }
                                                                                            ?>
                                                                                        </a></br>
                              
                                                                                            <?php 
                                                                                            
                                                                                            if(isset($lead_passenger_Data))
                                                                                            {
                                                                                                
                                                                                                    echo 'Customer Name :';
                                                                                                    echo '</br>';
                                                                                            echo $hotel_booking->lead_passenger ?? $lead_passenger_Data->lead_first_name . ' ' .$lead_passenger_Data->lead_last_name; 
                                                                                            echo '</br>';
                                                                                            echo '3rd Party';
                                                                                            }
                                                                                            ?>
                            
                            
                        
                                                                                    </td>
                                                                                    
                                                                                    
                                                                                    
                                                                                      <td>
                                                                                        <?php
                                                                                        
                                                                                      
                                                                                           print_r($reservation_response->hotel_details->hotel_name ?? ''); 
                                                                                            echo '</br>';
                                                                                      
                                                                                        
                                                                                        ?>
                                                                                        
                                                                                        <?php
                                                                                     
                                                                                           
                                                                                           
                                                                                            echo 'NumAdults: ' . ($hotel_booking->total_adults ?? '');
                                                                                             echo '</br>';
                                                                                            echo 'NumChildren: ' . ($hotel_booking->total_childs ?? '');
                                                                                            echo '</br>';
                                                                                           
                                                                                       
                                                                                        ?>
                                                                                        
                                                                                    </td>
                                                                                   
                                                                                   
                                                                                    <td>
                                                                                        {{$customer_subcriptions->currency_symbol}} {{$hotel_booking->exchange_total_markup_price ?? '0'}}
                                                                                        </br>
                                                                                        {{$hotel_booking->currency}} {{$hotel_booking->total_markup_price}}
                                                                                        
                                                                                      
                                                                                        
                                                                                        
                                                                                    </td>
                                                                                    
                                                                                    <td>
                                                                                        {{$customer_subcriptions->currency_symbol}} {{$hotel_booking->exchange_client_commission_amount ?? '0'}}
                                                                                         </br>
                                                                                       {{$hotel_booking->currency}} {{$hotel_booking->client_commission_amount}}
                                                                                         
                                                                                        
                                                                                   
                                                                                      
                                                                                        
                                                                                        
                                                                                    </td>
                                                                                    <td>
                                                                                        {{$customer_subcriptions->currency_symbol}} {{$hotel_booking->exchange_payable_price ?? '0'}}
                                                                                         </br>
                                                                                        {{$hotel_booking->currency}} {{$hotel_booking->payable_price}}
                                                                                      
                                                                                        
                                                                                        
                                                                                    </td>
                                                                                    <td>
                                                                                        
                                                                                    {{$hotel_booking->exchange_rate ?? '0'}}
                                                                                      
                                                                                        
                                                                                        
                                                                                    </td>
                                                                                   
                                                                                    
                                                                                    
                                                                                  
                                                                                    
                                                                                    <td>
                                                                                        
                                                                                    <?php 
                                                                                        if(isset($hotel_booking->hotel_booking_status) && $hotel_booking->hotel_booking_status == 'non_refundable'){
                                                                                            ?>
                                                                                            <a  class="btn btn-info" target="_blank" href="{{ URL::to('proccess_non_refundable_booking/'.$hotel_booking->invoice_no.'') }}">Proccess Now</a>
                                                                                            <?php
                                                                                        }else if(isset($hotel_booking->hotel_booking_status) && $hotel_booking->hotel_booking_status == 'CONFIRMED' || $hotel_booking->hotel_booking_status == 'Confirmed'){
                                                                                            ?>
                                                                                            <a  class="btn btn-success" target="_blank" href="{{ $invoice_URL }}/{{ $hotel_booking->invoice_no }}">Voucher</a>  
                                                                                            <?php
                                                                                        }
                                                                                        else if(isset($hotel_booking->hotel_booking_status) && $hotel_booking->hotel_booking_status == 'Cancelled' || $hotel_booking->hotel_booking_status == 'Cancelled'){
                                                                                            ?>
                                                                                            <a  class="btn btn-success" target="_blank" href="{{ $invoice_URL }}/{{ $hotel_booking->invoice_no }}">Voucher</a>  
                                                                                            <?php
                                                                                             if($hotel_booking->payment_details != NULL && $hotel_booking->payment_refunds == NULL)
                                                                                            {
                                                                                                ?>
                                                                                                <a  class="btn btn-success" href="{{ URL::to('refund_payment/'.$hotel_booking->invoice_no.'') }}">Refund Payment</a> 
                                                                                              <?php
                                                                                        }
                                                                                        else
                                                                                        {
                                                                                            ?>
                                                                                             <a  class="btn btn-success" href="javascript:;">refunded</a>  
                                                                                            <?php
                                                                                        }
                                                                                        
                                                                                              
                                                                                        
                                                                                        }else if(isset($hotel_booking->hotel_booking_status) && $hotel_booking->hotel_booking_status == 'Failed' || $hotel_booking->hotel_booking_status == 'Failed'){
                                                                                            if($hotel_booking->payment_refunds == NULL)
                                                                                            {
                                                                                            ?>
                                                                                            <a  class="btn btn-success" href="{{ URL::to('refund_payment/'.$hotel_booking->invoice_no.'') }}">Refund Payment</a>  
                                                                                            <?php
                                                                                        }
                                                                                        
                                                                                        else
                                                                                        {
                                                                                            ?>
                                                                                             <a  class="btn btn-success" href="javascript:;">refunded</a>  
                                                                                            <?php
                                                                                        }
                                                                                        }
                                                                                              ?>
                                                                                       
                                                                                        
                                                                                    </td>
                                                                                    
                                                                                    
                                                                                   
                                                                                    
                                                                                         
                                                                                         
                                                                                         
                                                                                         
                                                                                        
                                                                                        
                                                                                    
                                                                                    
                                                                                       
                                                                                </tr>
                                                                                
                                                                                
                                                                               
                                                                            <?php
                                                                               }
                                                                            ?>
                                                                                
                                                                                
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>   
                              
                          </div>
                            
                            <div class="tab-pane fade" id="refund-tab-pane" role="tabpanel" aria-labelledby="refund-tab" tabindex="0">
                            
                            <table style="font-weight: bold;font-family: serif;" id="example_5" class="table dt-responsive nowrap w-100">
                                                                            <thead class="theme-bg-clr">
                                                                                <tr>
                                                                                    <th>id</th>
                                                                                    <th>Invoice No</th>
                                                                                   
                                                                                    <th>Hotel Name</th>
                                                                                  <th>Total Amount</th>
                                                                                    <th>Commission Amount</th>
                                                                                    <th>Payable Amount</th>
                                                                                    <th>Exchange Rate</th>
                                                                                    <th>Action</th>
                                                                                    
                                                                                   
                                                                                </tr>
                                                                            </thead>
                                                                        
                                                                        
                                                                            <tbody>
                                                                                
                                                                                @foreach($all_booking_data as $hotel_booking)
                                                                                
                                                                                <?php
                                                                               if(($hotel_booking->hotel_booking_status == 'Cancelled' || $hotel_booking->hotel_booking_status == 'Failed') && ($hotel_booking->payment_refunds == NULL && $hotel_booking->payment_details != NULL))
                                                                               {
                                                                                //  $customer_subcriptions=$get_data_customer_subcriptions;
                                                                                
                                                                                foreach($get_data_customer_subcriptions as $val_CS){
                                                                                    if($val_CS->id == $hotel_booking->customer_id){
                                                                                        $customer_subcriptions = $val_CS;
                                                                                        if($hotel_booking->customer_id == 48 || $hotel_booking->customer_id == '48'){
                                                                                            $invoice_URL            = 'https://alsubaee.com/hotel_invoice';
                                                                                        }else{
                                                                                            if($val_CS->webiste_Address != null && $val_CS->webiste_Address != ''){
                                                                                                $invoice_URL            = $val_CS->webiste_Address.'/hotel_booking_invoice';
                                                                                            }else{
                                                                                                $invoice_URL            = 'hotel_booking_invoice';
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                             
                                                                                $name=$customer_subcriptions->name;
                                                                                $lname=$customer_subcriptions->lname;
                                                                                $client_name=$name . $lname;
                                                                                
                                                                                
                                                                           
                                                                                $hotel_payment_details_total=0;
                                                                                
                                                                                ?>
                                                                       
                                                                                <tr>
                                                                                    <td>{{ $loop->iteration}}</td>
                                                                                    <td>
                                                                                        <?php
                                                                                        if(isset($hotel_booking->booked) && $hotel_booking->booked == 'Agent')
                                                                                        {
                                                                                        ?>
                                                                                         <b style="color:red;font-weight:bold;">Admin</b><br>
                                                                                    <a style="color: #7e7d7b;font-weight: bold;" target="_blank" href="{{ $invoice_URL }}/{{ $hotel_booking->invoice_no }}">
                                                                                    <?php print_r($hotel_booking->invoice_no); 
                                                                                   
                                                                                    ?>
                                                                                    <img height="15px" width="15px" src="https://system.alhijaztours.net/public/invoice_icon.png">   
                                                                                    </a>
                                                                                    <?php
                                                                                    
                                                                                        }
                                                                                        else
                                                                                        {
                                                                                    ?>
                                                                                     <a style="color: #7e7d7b;font-weight: bold;" target="_blank" href="{{ $invoice_URL }}/{{ $hotel_booking->invoice_no }}">
                                                                                    <?php print_r($hotel_booking->invoice_no); 
                                                                                   
                                                                                    ?>
                                                                                    <img height="15px" width="15px" src="https://system.alhijaztours.net/public/invoice_icon.png">   
                                                                                    </a>
                                                                                    
                                                                                    <?php
                                                                                        }
                                                                                    ?>
                                                                                    </br>
                                                                                    <?php
                                                                                         $lead_passenger_Data = json_decode($hotel_booking->lead_passenger_data);
                                                                                         $reservation_response = json_decode($hotel_booking->reservation_response);
                        
                                                                                        //  dd($reservation_response);
                                                                                        ?>
                                                                                        </br>
                                                                                        {{ $reservation_response->reference_no ?? '' }}
                                                                                        <a class="cst_css" href="javascript:;">
                                                                                            
                                                                                            <?php 
                                                                                                if(isset($hotel_booking->hotel_booking_status) && $hotel_booking->hotel_booking_status == 'non_refundable'){
                                                                                                    echo "Non Procced Yet ";
                                                                                                }else{
                                                                                                    echo $hotel_booking->status ?? '';
                                                                                                }
                                                                                            ?>
                                                                                        </a></br>
                              
                                                                                            <?php 
                                                                                            
                                                                                            if(isset($lead_passenger_Data))
                                                                                            {
                                                                                                
                                                                                                    echo 'Customer Name :';
                                                                                                    echo '</br>';
                                                                                            echo $hotel_booking->lead_passenger ?? $lead_passenger_Data->lead_first_name . ' ' .$lead_passenger_Data->lead_last_name; 
                                                                                            echo '</br>';
                                                                                            echo '3rd Party';
                                                                                            }
                                                                                            ?>
                            
                            
                        
                                                                                    </td>
                                                                                    
                                                                                    
                                                                                    
                                                                                      <td>
                                                                                        <?php
                                                                                        
                                                                                      
                                                                                           print_r($reservation_response->hotel_details->hotel_name ?? ''); 
                                                                                            echo '</br>';
                                                                                      
                                                                                        
                                                                                        ?>
                                                                                        
                                                                                        <?php
                                                                                     
                                                                                           
                                                                                           
                                                                                            echo 'NumAdults: ' . ($hotel_booking->total_adults ?? '');
                                                                                             echo '</br>';
                                                                                            echo 'NumChildren: ' . ($hotel_booking->total_childs ?? '');
                                                                                            echo '</br>';
                                                                                           
                                                                                       
                                                                                        ?>
                                                                                        
                                                                                    </td>
                                                                                   
                                                                                   
                                                                                    <td>
                                                                                        {{$customer_subcriptions->currency_symbol}} {{$hotel_booking->exchange_total_markup_price ?? '0'}}
                                                                                        </br>
                                                                                        {{$hotel_booking->currency}} {{$hotel_booking->total_markup_price}}
                                                                                        
                                                                                      
                                                                                        
                                                                                        
                                                                                    </td>
                                                                                    
                                                                                    <td>
                                                                                        {{$customer_subcriptions->currency_symbol}} {{$hotel_booking->exchange_client_commission_amount ?? '0'}}
                                                                                         </br>
                                                                                       {{$hotel_booking->currency}} {{$hotel_booking->client_commission_amount}}
                                                                                         
                                                                                        
                                                                                   
                                                                                      
                                                                                        
                                                                                        
                                                                                    </td>
                                                                                    <td>
                                                                                        {{$customer_subcriptions->currency_symbol}} {{$hotel_booking->exchange_payable_price ?? '0'}}
                                                                                         </br>
                                                                                        {{$hotel_booking->currency}} {{$hotel_booking->payable_price}}
                                                                                      
                                                                                        
                                                                                        
                                                                                    </td>
                                                                                    <td>
                                                                                        
                                                                                    {{$hotel_booking->exchange_rate ?? '0'}}
                                                                                      
                                                                                        
                                                                                        
                                                                                    </td>
                                                                                   
                                                                                    
                                                                                    
                                                                                  
                                                                                    
                                                                                    <td>
                                                                                        
                                                                                    <?php 
                                                                                        if(isset($hotel_booking->hotel_booking_status) && $hotel_booking->hotel_booking_status == 'non_refundable'){
                                                                                            ?>
                                                                                            <a  class="btn btn-info" target="_blank" href="{{ URL::to('proccess_non_refundable_booking/'.$hotel_booking->invoice_no.'') }}">Proccess Now</a>
                                                                                            <?php
                                                                                        }else if(isset($hotel_booking->hotel_booking_status) && $hotel_booking->hotel_booking_status == 'CONFIRMED' || $hotel_booking->hotel_booking_status == 'Confirmed'){
                                                                                            ?>
                                                                                            <a  class="btn btn-success" target="_blank" href="{{ $invoice_URL }}/{{ $hotel_booking->invoice_no }}">Voucher</a>  
                                                                                            <?php
                                                                                        }
                                                                                        else if(isset($hotel_booking->hotel_booking_status) && $hotel_booking->hotel_booking_status == 'Cancelled' || $hotel_booking->hotel_booking_status == 'Cancelled'){
                                                                                            ?>
                                                                                            <a  class="btn btn-success" target="_blank" href="{{ $invoice_URL }}/{{ $hotel_booking->invoice_no }}">Voucher</a>  
                                                                                            <?php
                                                                                             if($hotel_booking->payment_details != NULL && $hotel_booking->payment_refunds == NULL)
                                                                                            {
                                                                                                ?>
                                                                                                <a  class="btn btn-success" href="{{ URL::to('refund_payment/'.$hotel_booking->invoice_no.'') }}">Refund Payment</a> 
                                                                                              <?php
                                                                                        }
                                                                                        else
                                                                                        {
                                                                                            ?>
                                                                                             <a  class="btn btn-success" href="javascript:;">refunded</a>  
                                                                                            <?php
                                                                                        }
                                                                                        
                                                                                              
                                                                                        
                                                                                        }else if(isset($hotel_booking->hotel_booking_status) && $hotel_booking->hotel_booking_status == 'Failed' || $hotel_booking->hotel_booking_status == 'Failed'){
                                                                                            if($hotel_booking->payment_refunds == NULL)
                                                                                            {
                                                                                            ?>
                                                                                            <a  class="btn btn-success" href="{{ URL::to('refund_payment/'.$hotel_booking->invoice_no.'') }}">Refund Payment</a>  
                                                                                            <?php
                                                                                        }
                                                                                        
                                                                                        else
                                                                                        {
                                                                                            ?>
                                                                                             <a  class="btn btn-success" href="javascript:;">refunded</a>  
                                                                                            <?php
                                                                                        }
                                                                                        }
                                                                                              ?>
                                                                                       
                                                                                        
                                                                                    </td>
                                                                                    
                                                                                    
                                                                                   
                                                                                    
                                                                                         
                                                                                         
                                                                                         
                                                                                         
                                                                                        
                                                                                        
                                                                                    
                                                                                    
                                                                                       
                                                                                </tr>
                                                                                
                                                                                
                                                                               
                                                                            <?php
                                                                               }
                                                                            ?>
                                                                                
                                                                                
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
   
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    
    <script>
        $(document).ready(function () {
            // $('#example_1,#example_2,#example_3,#example_4,#example_5').DataTable({
            //     scrollX: true,
            //     scrollY: true,
            //     // order: [[0, 'desc']],
            //     dom: 'Bfrtip',
            //     buttons: [
            //         'copy', 'csv', 'excel', 'pdf', 'print'
            //     ]
            
            // });
        }); 
    </script> 
    
    <script>
        function paymentFunction(searchedid){
            const searched_id = $('#click_payment_popup_'+searchedid+'').attr('atr_id');
            $('#invoice_no').val(searched_id);
            
            const provider = $('#click_payment_popup_'+searchedid+'').attr('atr_provider');
            const check_in = $('#click_payment_popup_'+searchedid+'').attr('atr_check_in');
            $('#checkin').val(check_in);
            
            const check_out = $('#click_payment_popup_'+searchedid+'').attr('atr_check_out');
            $('#checkout').val(check_out);
            
            const rooms = $('#click_payment_popup_'+searchedid+'').attr('atr_rooms');
            $('#rooms').val(rooms);
            
            const total_passenger = $('#click_payment_popup_'+searchedid+'').attr('atr_total_passenger');
            $('#adults').val(total_passenger);
            
            const child = $('#click_payment_popup_'+searchedid+'').attr('atr_child');
            $('#children').val(child);
            
            const CustomerName = $('#click_payment_popup_'+searchedid+'').attr('atr_CustomerName');
            $('#customer_name').val(CustomerName);
            
            const Customeremail = $('#click_payment_popup_'+searchedid+'').attr('atr_Customeremail');
            $('#customer_email').val(Customeremail);
            
            const hotel_name = $('#click_payment_popup_'+searchedid+'').attr('atr_hotel_name');
            $('#hotel_name').val(hotel_name);
            
            const room_name = $('#click_payment_popup_'+searchedid+'').attr('atr_RoomName');
            $('#room_name').val(room_name);
            
            const room_price = $('#click_payment_popup_'+searchedid+'').attr('atr_RoomPrice');
            $('#room_price').val(room_price);
            
            const total_price = $('#click_payment_popup_'+searchedid+'').attr('atr_TotalPrice');
            $('#total_amount').val(total_price);
            
            const amount_paid = $('#click_payment_popup_'+searchedid+'').attr('atr_paid_amount');
            
            $('#amount_paid').val(amount_paid);
            
            const remaining_amount = parseFloat(total_price) - parseFloat(amount_paid);
            
            $('#remaining_amount').val(remaining_amount);
        }
        
        $('#recieved_amount').on('change',function(){
            recieved_amount  = $(this).val();
            remaining_amount = $('#remaining_amount').val();
            remaining_amount_final = parseFloat(remaining_amount) - parseFloat(recieved_amount);
            $('#remaining_amount').val(remaining_amount_final);
            $('#amount_paid').val(recieved_amount);
        });
    </script>
    
    <script>
        $(function () {
            $('#form').on('submit', function (e) {
                var invoice_no = $('#invoice_no').val();
                var amount_paid = $('#amount_paid').val();
                var remaining_amount = $('#remaining_amount').val();
                var recieved_amount = $('#recieved_amount').val();
                var total_amount =$('#total_amount').val();
                e.preventDefault();
        
                $.ajax({
                    type: 'post',
                    url: 'payment_detact/'+invoice_no,
                
                    data: {
                        'invoice_no':invoice_no,
                        'amount_paid':amount_paid,
                        'remaining_amount':remaining_amount,
                        'recieved_amount':recieved_amount,
                        'total_amount':total_amount,
                    },
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    success: function (data) {
                        $('#msg').html(data).fadeIn('slow');
                        $('#msg').html("payment successfully").fadeIn('slow');
                        $('#msg').delay(2000).fadeOut('slow');
                        
                        setTimeout("window.location = ''",2000);
                    }
                });
            });
        });
    </script>  
    
 @endsection