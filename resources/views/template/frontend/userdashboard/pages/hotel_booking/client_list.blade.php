<?php

//print_r($client);die();


$client_total_price=0;
$client_payable_price=0;
$client_commission_price=0;
// if(isset($data_res->manage_customer_markups))
// {
 
//   foreach($data_res->manage_customer_markups as $manage_customer_markups)
//     {
//         $client_payable_price=$client_payable_price + $manage_customer_markups->exchange_payable_price;
//         $client_commission_price=$client_commission_price + $manage_customer_markups->exchange_client_commission_amount;
//         $client_total_price=$client_total_price + $manage_customer_markups->exchange_total_markup_price;
//     }    
    
// }

?>

@extends('template/frontend/userdashboard/layout/default')
 @section('content')
 

 

     
    


    
    <div class="mt-5" id="HotelBeds">
       <div class="row">
            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <h4 class="header-title">Client List</h4>
                                        

                                                <table id="example_2" class="table dt-responsive nowrap w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>id</th>
                                                            <th>Customer Details</th>
                                                        
                                                            <th>Total Bookings</th>
                                                            <th>Total Amount</th>
                                                            <th>Commission Amount</th>
                                                            <th>Receiveable Amount</th>
                                                            <th>Remaining Amount</th>
                                                            <th>Options</th>
                                                           
                                                            
                                                           
                                                        </tr>
                                                    </thead>
                                                
                                                
                                                    <tbody>
                                                        <?php
                                                        if(isset($client))
                                                        {
                                                          foreach($client as $client_data)
                                                          {
                                                             
                                                              
                                                             $manage_customer_markups = DB::table('manage_customer_markups')->where('token',$client_data->Auth_key)->first();
                                                             
                                                            $admin_exchange_amount = DB::table('manage_customer_markups')->where('token',$client_data->Auth_key)->sum('admin_exchange_amount');
                                                            $exchange_admin_commission_amount = DB::table('manage_customer_markups')->where('token',$client_data->Auth_key)->sum('exchange_admin_commission_amount');
                                                            $exchange_payable_price = DB::table('manage_customer_markups')->where('token',$client_data->Auth_key)->sum('exchange_payable_price');
                                                            $exchange_client_commission_amount = DB::table('manage_customer_markups')->where('token',$client_data->Auth_key)->sum('exchange_client_commission_amount');
                                                            
                                                            $receiveableamount = DB::table('hotel_customer_ledgers')->where('token',$client_data->Auth_key)->sum('payment_amount');
                                                            $hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->where('token',$client_data->Auth_key)->latest()->first();
                                                            
                                                           ?>
                                                           
                                                         <tr>
                                                            <td>{{$client_data->id}}</td>
                                                            <td>{{$client_data->name}} {{$client_data->lname}}<br>{{$client_data->company_name}}<br>{{$client_data->email}}</td>

                                                            <td>
                                                                <?php
                                                                $total_booking = DB::table('hotel_provider_bookings')->where('auth_token',$client_data->Auth_key)->count();
                                                                echo $total_booking ?? '0';
                                                                ?>
                                                                 
                                                            </td>
                                                            
                                                            
                                                            <td>
                                                                {{$client_data->currency_symbol ?? ''}} {{$exchange_payable_price ?? '0'}}</br>
                                                               
                                                                GBP {{$admin_exchange_amount?? '0'}}</br>
                                                            
                                                            </td>
                                                            
                                                           <td>
                                                                {{$client_data->currency_symbol ?? ''}} {{$exchange_client_commission_amount ?? '0'}}</br>
                                                               
                                                                GBP {{$exchange_admin_commission_amount ?? '0'}}</br>
                                                            
                                                            </td>
                                                            
                                                            
                                                            <td>{{$client_data->currency_symbol}} <?php print_r($receiveableamount ?? '0'); ?></td>
                                                            
                                                             <td>{{$client_data->currency_symbol}} <?php print_r($hotel_customer_ledgers->balance_amount ?? '0'); ?></td>
                                                              
                                                            
                                                            <td>
                                                        
                                                        <a href="{{URL::to('super_admin/hotels/list')}}" target="_blank" class="btn btn-info btn-sm"><i class="mdi mdi-clipboard-pulse-outline"></i></a>
                                                        <a href="{{URL::to('super_admin/customer/hotels/ledger')}}/{{$client_data->id}}" target="_blank" class="btn btn-success btn-sm"><i class="mdi mdi-eye"></i></a>
                                                        <a href="{{URL::to('super_admin/customer/hotels/payment/history')}}/{{$client_data->id}}" target="_blank" class="btn btn-secondary btn-sm"><i class="mdi mdi-account-edit"></i></a>
                                                    </td>
                                                            </tr>
                                                            <?php
                                                          }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>                                           
                                            
                                        
                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
                            </div><!-- end col-->
        </div>
    </div>
   
   

     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
                            

                        
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>-->
         <script>
   $(document).ready(function () {

     $('#example_2').DataTable({
        scrollX: true,
        scrollY: true,
        order: [[0, 'desc']],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
        
    });
    
}); 
</script>   
      <script>
     
   
      
     
    $(document).ready(function () {

    //   var total_price = $('#total_amount').val();
    //     var amount_paid = $('#amount_paid').val();        
          
    //     var remaining_amount = parseFloat(total_price) - parseFloat(amount_paid);
    //               $('#remaining_amount').val(remaining_amount);

        $('#recieved_amount').on('change',function(){
            recieved_amount  = $(this).val();
            remaining_amount = $('#remaining_amount').val();
            remaining_amount_final = parseFloat(remaining_amount) - parseFloat(recieved_amount);
            console.log('remaining_amount_final'+remaining_amount_final);
            $('#amount_paid').val(recieved_amount);
            var r_am=remaining_amount_final.toFixed(2);
            $('#remaining_amount').val(r_am);
        });

    });
   
    
   


</script>                  
                     
 @endsection