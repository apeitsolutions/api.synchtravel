<?php

 //$get_data_token=$get_data_hotel_token;
 
 
 
 

?>


@extends('template/frontend/userdashboard/layout/default')
 @section('content')
 
 
<div class="modal fade" id="bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">View Payment Details</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
           <div style="width: 400px;margin-top: 20px; display:none;" id='msg' class="alert alert-primary alert-dismissible bg-primary text-white border-0 fade show" role="alert">
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    <strong></strong>
</div>
           
            <div class="modal-body">
               <form class="row form-group" id="form">
                  
                 <div class="col-md-4">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Invoice No</label>
                        <input type="text" id="invoice_no" class="form-control" readonly value="">
                    </div>
                 </div>
                 <div class="col-md-4">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Check In</label>
                        <input type="text" id="checkin" class="form-control" readonly value="">
                    </div>
                 </div>  
                 <div class="col-md-4">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Check Out</label>
                        <input type="text" id="checkout" class="form-control" readonly value="">
                    </div>
                 </div>  
                 <div class="col-md-4">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Rooms</label>
                        <input type="text" id="rooms" class="form-control" readonly value="">
                    </div>
                 </div>  
                 <div class="col-md-4">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Adults</label>
                        <input type="text" id="adults" class="form-control" readonly value="">
                    </div>
                 </div>  
                 <div class="col-md-4">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Children</label>
                        <input type="text" id="children" class="form-control" readonly value="">
                    </div>
                 </div>
                 <div class="col-md-4">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Customer Name</label>
                        <input type="text" id="customer_name" class="form-control" readonly value="">
                    </div>
                 </div>
                 <div class="col-md-4">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Customer Email</label>
                        <input type="email" id="customer_email" class="form-control" readonly value="">
                    </div>
                 </div>
                 <div class="col-md-4">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Hotel Name</label>
                        <input type="text" id="hotel_name" class="form-control" readonly value="">
                    </div>
                 </div>
                 <div class="col-md-4">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Room Name</label>
                        <input type="text" id="room_name" class="form-control" readonly value="">
                    </div>
                 </div>
                 <div class="col-md-4">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Room Price</label>
                        <input type="text" id="room_price" class="form-control" readonly value="">
                    </div>
                 </div>
                 <div class="col-md-4">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Total Price</label>
                        <input type="text" id="total_amount" class="form-control" readonly value="">
                    </div>
                 </div>
                 <div class="col-md-4">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Recieved Amount</label>
                        <input type="text" id="recieved_amount" class="form-control"  value="">
                    </div>
                 </div>
                 <div class="col-md-4">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Remaining Amount</label>
                        <input type="text" id="remaining_amount" class="form-control" readonly value="">
                    </div>
                 </div>
                 <div class="col-md-4">
                     <div class="mb-3">
                        <label for="simpleinput" class="form-label">Amount Paid</label>
                        <input type="text" id="amount_paid" class="form-control" readonly value="">
                    </div>
                 </div>

                    <div class="mb-3">
                        <button  style="float: right;" id="submitpaymentform" class="btn btn-primary">submit</button>
                    </div>
                   
               </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
 
 

     
    


   
        <div class="row mt-5">
            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <h4 class="header-title">Hotel Markup</h4>
                                        <p class="text-muted font-14">
                                            
                                        </p>

                                        
                                       
                                                <table id="example_1" class="table dt-responsive nowrap w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>id</th>
                                                            <th>Invoice No</th>
                                                            <th>Provider</th>
                                                            <th>Hotel Name</th>
                                                             <th>Room Details</th>
                                                            
                                                            
                                                        </tr>
                                                    </thead>
                                                
                                                
                                                    <tbody>
                                                        <tr>
                                                            <td></td>
                                                             <td></td>
                                                              <td></td>
                                                               <td></td>
                                                                <td></td>
                                                        </tr>
                                                    </tbody>
                                                </table>                                           
                                            
                                        
                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
                            </div><!-- end col-->
        </div>
  

                        
   
           
                  
                     
 @endsection