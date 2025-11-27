@extends('template/frontend/userdashboard/layout/default')
@section('content')
    
    <?php
        // dd($hotelbeds);
        
        $hotelbeds_total_amount     = 0;
        $hotelbeds_total_c_amount   = 0;
        $payable_hotelbeds          = 0;
        if(isset($hotelbeds)){
            foreach($hotelbeds as $hotelbeds_data){
                $booking_rs                 = json_decode($hotelbeds_data->booking_rs);
                
                if(isset($booking_rs->booking->hotel->totalNet)){
                    $payable_hotelbeds      = $payable_hotelbeds + $booking_rs->booking->hotel->totalNet;
                }
                
                if($hotelbeds_data->admin_exchange_total_markup_price > 0 && $hotelbeds_total_amount > 0){
                    $hotelbeds_total_amount = $hotelbeds_total_amount + $hotelbeds_data->admin_exchange_total_markup_price;
                }
                
                $hotelbeds_total_c_amount   = $hotelbeds_total_c_amount + $hotelbeds_data->exchange_admin_commission_amount; 
            }
        }
     
        $admin_provider_payments_hotelbeds= DB::table('admin_provider_payments')->latest()->first();
        $payment_paid_amount_hotelbeds= DB::table('admin_provider_payments')->sum('payment_paid_amount');
    ?>
    
    <style>
        .cst_css{
            color: red;
            font-weight: bold;
        }
    </style>
 
    <div class="modal fade" id="bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Add New Payment</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
           <div style="width: 400px;margin-top: 20px; display:none;" id='msg' class="alert alert-primary alert-dismissible bg-primary text-white border-0 fade show" role="alert">
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    <strong></strong>
</div>
           <?php
                 if(isset($admin_provider_payments_hotelbeds))
                 {
                     $remaining_am=$admin_provider_payments_hotelbeds->payment_remaining_amount;
                 }
                 else
                 {
                     
                  
                  $remaining_am=$payable_hotelbeds;   
                 }
                
                                                                
                 ?>
                 
                 
                 
                 
                 
                 
            <div class="modal-body">
               <form class="row form-group" action="{{URL::to('super_admin/admin/provider/payment/submit')}}" method="post" id="form">
                  @csrf
                  <div class="col-md-3">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Select Provider</label>
                        <select name="provider" required class="form-control">
                            <option value="No">Select Provider</option>
                            <option value="HotelBeds">HotelBeds</option>
                            <option value="Travellanda">Travellanda</option>
                            <option value="TBO">TBO</option>
                            <option value="Ratehawk">Ratehawk</option>
                            <option value="CustomHotel">Custom Hotel</option>
                        </select>
                    </div>
                 </div>
                 <div class="col-md-3">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Payment date</label>
                        <input type="date" name="payment_date" class="form-control" required  value="">
                    </div>
                 </div>
                 <div class="col-md-3">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Payment Method</label>
                        <select name="payment_method" required class="form-control">
                            <option value="No">Select Payment Method</option>
                            <option value="Cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                    </div>
                 </div>
                 <div class="col-md-3">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Transcation Id</label>
                        <input type="text" required id="" name="payment_transction_id" class="form-control"  value="">
                    </div>
                 </div>
                 
                 
                 <div class="col-md-3">
                     
                     
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Total Amount</label>
                        <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                <input type="text" class="form-control" required name="payment_total_amount" readonly id="total_amount" value='<?php print_r($payable_hotelbeds); ?>'>
                                                                <span class="input-group-btn input-group-append">
                                                                    <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="select_mrk">EUR</div></button>
                                                                </span>
                                                            </div>
                        
                    </div>
                 </div>
                 <div class="col-md-3">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Recieved Amount</label>
                        
                        <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                                <input type="text" required name="payment_received_amount" id="recieved_amount" class="form-control"  value=''>
                                                                <span class="input-group-btn input-group-append">
                                                                    <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="select_mrk">EUR</div></button>
                                                                </span>
                                                            </div>
                        
                    </div>
                 </div>
                 
                 <div class="col-md-3">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Remaining Amount</label>
                         <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                               <input type="text" required name="payment_remaining_amount" id="remaining_amount" class="form-control" readonly  value="{{$remaining_am ?? ''}}">
                                                                <span class="input-group-btn input-group-append">
                                                                    <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="select_mrk">EUR</div></button>
                                                                </span>
                                                            </div>
                        
                    </div>
                 </div>
                 
                 <div class="col-md-3">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Amount Paid</label>
                        <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                                               <input type="text" required name="payment_paid_amount" id="amount_paid" readonly class="form-control"  value="0">
                                                                <span class="input-group-btn input-group-append">
                                                                    <button class="btn btn-primary bootstrap-touchspin-up" type="button"><div id="select_mrk">EUR</div></button>
                                                                </span>
                                                            </div>
                        
                    </div>
                 </div>
                 
                  
                 <div class="col-md-12">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Remarks</label>
                        <textarea name="payment_remarks" required class="form-control"></textarea>
                    </div>
                 </div>
                 
                

                    <div class="mb-3">
                        <button  style="float: right;" type="submit" name="submit" class="btn btn-primary">submit</button>
                    </div>
                   
               </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
 
    <div class="row">
        <div class="col-12">
            <div class="card" style="background: #f1f5f8;">
                <div class="card-body">
                    
                    <h3>Hotel Provider Bookings</h3>
                    <a class="btn btn-success" style="float: right;margin-bottom: 20px;" href="javascript:;" data-bs-toggle="modal" data-bs-target="#bs-example-modal-lg">Add Payment + </a>
                    
                    <table style="" id="example_1" class="table dt-responsive nowrap w-100">
                        <thead class="theme-bg-clr">
                            <tr>
                                <th style="text-align: center;">id</th>
                                <th style="text-align: center;">provider</th>
                                <th style="text-align: center;">Total Booking</th>
                                <th style="text-align: center;">Total Amount</th>
                                <th style="text-align: center;">Commission Amount</th>
                                <th style="text-align: center;">Payable Amount</th>
                                <th style="text-align: center;">Paid Amount</th>
                                <th style="text-align: center;">Remaining Amount</th>
                                <th style="text-align: center;">Action</th>
                            </tr>
                        </thead>
                        
                        <tbody style="text-align: center;">
                            <tr>
                                <td>1</td>
                               <td>HotelBeds</td>
                               <td><?php print_r(count($hotelbeds)); ?></td>
                               <td>GBP {{$hotelbeds_total_amount}}</td>
                               <td>GBP {{$hotelbeds_total_c_amount}}</td>
                               <td> EUR  {{$payable_hotelbeds}} </td>
                               <td>EUR {{$payment_paid_amount_hotelbeds ?? '0'}}</td>
                               <td>EUR {{$remaining_am ?? '0'}}</td>
                               <td>
                            <a href="{{URL::to('super_admin/hotels/list')}}" target="_blank" class="btn btn-info btn-sm"><i class="mdi mdi-clipboard-pulse-outline"></i></a>
                            <a href="{{URL::to('super_admin/customer/hotels/ledger')}}" target="_blank" class="btn btn-success btn-sm"><i class="mdi mdi-eye"></i></a>
                            <a href="{{URL::to('super_admin/customer/hotels/payment/history')}}" target="_blank" class="btn btn-secondary btn-sm"><i class="mdi mdi-account-edit"></i></a>
                               </td>
                                 
                                
                                
                            
                               
                            </tr>
                        </tbody>
                    </table> 
                    
                </div>
            </div>
        </div>
    </div>
    
    <script>
        $(document).ready(function () {
            $('#example_1').DataTable({
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

    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>-->
    
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