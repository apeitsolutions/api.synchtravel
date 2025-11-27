<?php

//print_r($all_data);die();

?>

@extends('template/frontend/userdashboard/layout/default')
@section('content')



    <style>
        .nav-link{
            color: #575757;
            font-size: 18px;
        }
        
        .print-table th, .print-table td {
            width: 150px;
            word-wrap: break-word;
        }
    </style>
  
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                
                <div class="row">
                  <div class="col-12">
                      <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Hotel Bookings</a></li>
                                <li class="breadcrumb-item active">financial_statement</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Customer Ledger</h4>
                        <div style="display: flex;justify-content: space-between;">
                            <h6 class="" style="font-size: 1rem;">Customer Name: {{ $customer->name ?? ''}} {{ $customer->lname ?? ''}}</h6>
                            <h6 class="" style="font-size: 1rem;">Company Name: {{ $customer->company_name ?? ''}}</h6>
                        </div>
                      </div>
                  </div>
              </div>
                
                <div class="row">
                    <div class="col-12">
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert" id="alert_hide">
                                <strong> {{ session('error') }}</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        <div class="row mb-2">
                            <div class="col-sm-5"></div>
                            <div class="col-sm-7"><div class="text-sm-end"></div></div>
                        </div>
                            
                        <div class="row">
                            <table id="myTable"  class="display table wrap table-bordered" style="width:100%;">
                               
                                <thead class="theme-bg-clr">
                                    <tr>
                                        <th style="text-align: center;">Sr</th>
                                      
                                        <th style="text-align: center;" id="descripton_th" width="20%">Description</th>
                                        <th style="text-align: center;">Debit</th>
                                        <th style="text-align: center;">Credit</th>
                                        <th style="text-align: center;">Balance</th>
                                        <th style="text-align: center;">Lead Passenger Details</th>
                                        <th style="text-align: center;">Date</th>
                                    </tr>
                                </thead>
                                
                                <tbody style="text-align: center;" id="tbody_all_data_acc">
                                  
                                
                                    @if(isset($all_data))
                                    <?php
                                    $total_balance=0;
                                    $count=1;
                                    ?>
                                        @foreach($all_data as $ledgers)
                                        
                                      
                                     <tr role="row" @if($ledgers->type == 'payment' && $ledgers->status == '1') style="background-color:#06b1a1;color:white;" @endif>
                                                <td>
                                                <?php
                                                if($ledgers->type == 'payment' && $ledgers->status == '1')
                                                {
                                                    echo $count;
                                                }
                                                if($ledgers->type == 'hotel_booking')
                                                {
                                                    echo $count;
                                                }
                                                
                                                ?>
                                                </td>
                                                    
                                               
                                                <td style="text-align:left !important">
                                                    <p style="margin: 0px;">
                                                        
                                                        <?php
                                                        if($ledgers->type == 'hotel_booking')
                                                        {
                                                         ?>
                                                          <b>
                                                           
                                                                    <a href="{{ URL::to('voucher/'.$ledgers->invoice_no.'') }}" target="blank"><?php echo 'confirmed'.' '.$ledgers->invoice_no ?? ''; ?>
                                                                        <img height="15px" width="15px" src="{{ asset('public/invoice_icon.png') }}">
                                                                    </a>
                                                           
                                                        </b>
                                                         <?php
                                                        }
                                                        if($ledgers->type == 'payment' && $ledgers->status == '1')
                                                        {
                                                        ?>
                                                         <b>
                                                           
                                                                    <a href="javascript:;"><?php echo $ledgers->payment_transction_id; ?>
                                                                        <img height="15px" width="15px" src="{{ asset('public/invoice_icon.png') }}">
                                                                    </a>
                                                           
                                                        </b>
                                                        <?php
                                                        }
                                                        ?>
                                                       
                                                        <?php
                                                           
                                                                   
                                                                    if($ledgers->provider == 'hotelbeds')
                                                                    {
                                                                     if(isset($ledgers->checkavailability_rs))
                                                                       {
                                                                        $checkavailability_rs=json_decode($ledgers->checkavailability_rs);
                                                                        
                                                                        echo "<h6 style='font-size: .8rem;' title='".$checkavailability_rs->name."'> $checkavailability_rs->name (".$checkavailability_rs->destinationName.")</h6>";
                                                                        echo "<h6 style='font-size: .8rem;'><i class='mdi mdi-calendar-start'></i> ".$ledgers->check_in." | <i class='mdi mdi-calendar-end'></i> ".$ledgers->check_out."</h6>";
                                                                        echo "<hr style='margin: 0.5rem;'>";
                                                                        echo "<h6 style='font-size: .8rem;'><i class='mdi mdi-calendar-start'></i> ".$ledgers->rooms." | <i class='mdi mdi-calendar-start'></i> ".$ledgers->adults." | <i class='mdi mdi-calendar-end'></i> ".$ledgers->childs."</h6>";
                                                                       }   
                                                                    }
                                                                    if($ledgers->provider == 'hotels')
                                                                    {
                                                                     if(isset($ledgers->checkavailability_rs))
                                                                       {
                                                                        $checkavailability_rs=json_decode($ledgers->checkavailability_rs);
                                                                        
                                                                        echo "<h6 style='font-size: .8rem;' title='".$checkavailability_rs->property_name."'> $checkavailability_rs->property_name (".$checkavailability_rs->property_city.$checkavailability_rs->property_country.")</h6>";
                                                                        echo "<h6 style='font-size: .8rem;'><i class='mdi mdi-calendar-start'></i> ".$ledgers->check_in." | <i class='mdi mdi-calendar-end'></i> ".$ledgers->check_out."</h6>";
                                                                        echo "<hr style='margin: 0.5rem;'>";
                                                                        echo "<h6 style='font-size: .8rem;'><i class='mdi mdi-calendar-start'></i> ".$ledgers->rooms." | <i class='mdi mdi-calendar-start'></i> ".$ledgers->adults." | <i class='mdi mdi-calendar-end'></i> ".$ledgers->childs."</h6>";
                                                                       }      
                                                                    }
                                                                     if($ledgers->provider == 'travellanda')
                                                                    {
                                                                     if(isset($ledgers->booking_rs))
                                                                       {
                                                                        $booking_rs=json_decode($ledgers->booking_rs);
                                                                        
                                                                         "<h6 style='font-size: .8rem;' title='".print_r($booking_rs->Body->HotelBooking->HotelName)."'> ".print_r($booking_rs->Body->HotelBooking->HotelName) ."</h6>";
                                                                        echo "<h6 style='font-size: .8rem;'><i class='mdi mdi-calendar-start'></i> ".$ledgers->check_in." | <i class='mdi mdi-calendar-end'></i> ".$ledgers->check_out."</h6>";
                                                                        echo "<hr style='margin: 0.5rem;'>";
                                                                        echo "<h6 style='font-size: .8rem;'><i class='mdi mdi-calendar-start'></i> ".$ledgers->rooms." | <i class='mdi mdi-calendar-start'></i> ".$ledgers->adults." | <i class='mdi mdi-calendar-end'></i> ".$ledgers->childs."</h6>";
                                                                       }      
                                                                    }
                                                                       
                                                                    
                                                              
                                                            
                                                            
                                                            
                                                        ?>
                                                    </p>
                                                </td>
                                                <?php
                                                
                                               $price=$ledgers->received_amount;
                                               
                                            
                                                $currency='GBP';
                                                
                                                if($currency != 'GBP')
                                                {
                                                   $change_price=base_currency_by_alhijaz($currency,$price); 
                                                }
                                                else
                                                {
                                                    
                                                 $change_price=$price;   
                                                }
                                                
                                                ?>
                                                <td>
                                                    
                                                    <?php
                                                     
                                                    if(isset($ledgers->received_amount))
                                                    {
                                                     print_r($customer->currency_symbol . ' ' .$ledgers->received_amount ?? '0');   
                                                    }
                                                        
                                                    
                                                  
                                                  ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if($ledgers->type == 'payment' && $ledgers->status == '1')
                                                        {
                                                    if(isset($ledgers->payment_amount))
                                                    {
                                                     print_r($customer->currency_symbol . ' ' .$ledgers->payment_amount ?? '0');   
                                                    }
                                                        }
                                                    ?>
                                                   
                                                </td>
                                                <td>
                                                <?php 
                                                if($ledgers->type == 'payment' && $ledgers->status == '1')
                                                        {
                                                        $total_balance=$total_balance + $change_price;
                                                        print_r($customer->currency_symbol . ' ' .$ledgers->balance_amount);
                                                        }
                                                        else
                                                        {
                                                         print_r($customer->currency_symbol . ' ' .$ledgers->balance_amount);   
                                                        }
                                                ?></td>
                                                <?php
                                                $lead_passenger_details=json_decode($ledgers->lead_passenger_details);
                                                
                                                ?>
                                                <td>
                                                    {{$lead_passenger_details->lead_first_name ?? ''}} {{$lead_passenger_details->lead_last_name ?? ''}}<br>
                                                {{$lead_passenger_details->lead_email ?? ''}}<br> {{$lead_passenger_details->lead_phone ?? ''}}
                                                </td>
                                                
                                                <td>
                                                    <?php
                                                        if($ledgers->type == 'hotel_booking')
                                                        {
                                                            print_r($ledgers->created_at);
                                                        }
                                                        if($ledgers->type == 'payment' && $ledgers->status == '1')
                                                        {
                                                            print_r($ledgers->updated_at);
                                                        }
                                                        
                                                         ?>
                                                         
                                                         
                                                    
                                                    </td>
                                            </tr>
                                                   
                                        
                                        
                                           
                                           
                                             
                                            <?php
                                            
                                            $count=$count+1;
                                            
                                            ?>
                                        @endforeach
                                        
                                    @endif
                                    
                                    
                                            
                                </tbody>
                                
                            </table>
                        </div>
                    </div>
                </div>  
            </div> 
        </section>
    </div>
    
 

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

@endsection


@section('scripts')
    
    <script>
      $(document).ready(function() {
          $('#myTable').DataTable({
            "ordering": false,
            dom: 'Bfrtip',
            buttons: [
                { extend: 'copy',text: 'Copy' },
                { extend: 'csv',text: 'CSV' },
                { extend: 'excel',text: 'Excel' },
                { extend: 'pdf',text: 'PDF' },
                {
                    extend: 'print',
                    text: 'Print',
                    title: 'Agent Name: ',
                    autoPrint: true,
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    exportOptions: {
                        header: true,
                        footer: true,
                    },
                    customize: function (win) {
                        
                        var header = '<img src="https://system.synchtravel.com/public/admin_package/frontend/images/letter-head.png" alt="letterhead" style="width:100%;" >';
                        var footer = '<img src="https://system.synchtravel.com/public/admin_package/frontend/images/footer2.png" alt="letterhead" style="width:100%;" >';
                        $(win.document.body).prepend(header);
                        $(win.document.body).append(footer);
                        $(win.document.body).find('table').addClass('display').css('font-size', '15px');
                        $(win.document.body).find('tr:nth-child(1) th:nth-child(3)').css('width', '20%');
                        $(win.document.body).find('tr:nth-child(even) td').each(function(index){
                            $(this).css('background-color','#fff');
                        });
                    }
                }
            ]
          });
        });
            
        // $('#scroll-horizontal-datatable1').DataTable( {
        //     "ordering": false,
        //     "columns": [
        //                     { "width": "25px" },
        //                     { "width": "25px" },
        //                     { "width": "10px" },
        //                     { "width": "20px" },
        //                     { "width": "20px" },
        //                     { "width": "20px" },
        //                     { "width": "20px" },
        //                     { "width": "20px" }
        //             ],
        //     dom: 'Bfrtip',
        //     buttons: [
        //         { extend: 'copy',text: 'Copy' },
        //         { extend: 'csv',text: 'CSV' },
        //         { extend: 'excel',text: 'Excel' },
        //         { extend: 'pdf',text: 'PDF' },
        //         {
        //             extend: 'print',
        //             title: 'The title ',
        //             text: 'The text',
        //             orientation: 'landscape',
        //             pageSize: 'A4',
                    
                               
                   
        //         }
        //     ]
        // });
        function editPayment(payment_data){
            var payment_data = JSON.parse(payment_data);
             console.log(payment_data);
             $('#payment_edit').modal('show');
             
             $('#tourId').val(payment_data['tourId'])
             $('#date').val(payment_data['date'])
             $('#payment_id').val(payment_data['id'])
             
             $('#customer_name').val(payment_data['customer_name'])
             $('#package_title').val(payment_data['package_title'])
             $('#total_amount').val(payment_data['total_amount'])
             $('#amount_paid').val(payment_data['recieved_amount'])
        }
    </script>
    
@endsection
