<?php
//print_r($all_customer);die();
?>
@extends('template/frontend/userdashboard/layout/default')
 @section('content')
 <style>
     .cst_css
     {
         color: red;
    font-weight: bold;
     }
     .custom_css
     {
         float: right;
        margin-bottom: 30px;
     }
 </style>
 
 @if(session()->has('message'))
        <div x-data="{ show: true }" x-show="show"
             class="flex justify-between items-center bg-yellow-200 relative text-yellow-600 py-3 px-3 rounded-lg">
            <div>
                <span class="font-semibold text-yellow-700">   {{session('message')}}</span>
            </div>
            <div>
                <button type="button" @click="show = false" class=" text-yellow-700">
                    <span class="text-2xl">&times;</span>
                </button>
            </div>
        </div>
    @endif
 
 <div id="credit-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
<div class="modal-header">
                <h4 class="modal-title" id="topModalLabel">Add Customer Credit Limit</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                

                <form class="ps-3 pe-3" action="{{URL::to('super_admin/credit_limit/submit')}}" method="post">
                    @csrf

                    <div class="mb-3">
                        <label for="username" class="form-label">Select Customer</label>
                        <select class="form-control" id="slc_customer" name="customer_id">
                            <option value="no">Select Customer</option>
                            <?php
                            if(isset($all_customer))
                            {
                                foreach($all_customer as $customer)
                                {
                                   ?>
                                   <option atr-currency="{{$customer->currency_symbol}}" value="{{$customer->id}}">{{$customer->name}} {{$customer->lname}}</option>
                                   <?php
                                }
                            }
                            ?>
                          
                        </select>
                    </div>
                     <div class="mb-3">
                        <label for="username" class="form-label">Services Type</label>
                        
                        <select class="form-control" id="" required name="services_type">
                            <option value="no">Select Services</option>
                            <option value="hotels">Hotels</option>
                            <option value="flight">Flight</option>
                        </select>
                        
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Pyment Method</label>
                        
                        <select class="form-control" id="payment_method" required name="payment_method">
                            <option value="">Select Method</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="cash">Cash</option>
                        </select>
                        
                    </div>
                    <div class="mb-3" id="transection_id" style="display:none;"> 
                        <label for="username" class="form-label">Transection Id</label>
                        
                        <input type="text" class="form-control" name="transection_id">
                        
                    </div>
                    
                    
                    <div class="mb-3" id="transection_remarks" style="display:none;">
                        <label for="username" class="form-label">Transection Remarks</label>
                        
                        <input type="text" class="form-control" name="transection_remarks">
                        
                    </div>
<input type="hidden" id="currency_slc" name="currency" value="" />
                    <div class="mb-3">
                    <label class="form-label">Amount</label>
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text add_currency" id="basic-addon1">GBP</span>
                        <input type="text" class="form-control" placeholder="Enter Amount" name="amount" required aria-label="" aria-describedby="basic-addon1">
                    </div>
                </div>

                    

                    

                    <div class="mb-3 text-center">
                        <button class="btn btn-primary" type="submit" name="submit">Submit</button>
                    </div>

                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


 <div id="edit-credit-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
<div class="modal-header">
                <h4 class="modal-title" id="topModalLabel">Update Customer Credit Limit</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                

                <form class="ps-3 pe-3" action="{{URL::to('super_admin/update_credit_limit/submit')}}" method="post">
                    @csrf

                    <div class="mb-3">
                        <label for="username" class="form-label">Customer</label>
                        <input type="hidden" class="form-control" id="customer_id_edit"  name="customer_id" readonly="">
                        <input type="hidden" class="form-control" id="credit_id_edit"  name="credit_id" readonly="">
                        <input type="text" class="form-control" id="customer_name_edit" readonly="">
                        
                    </div>
                        <input type="hidden" id="currency_slc_edit" readonly="" name="currency" value="" />
                        <div class="mb-3">
                        <label for="username" class="form-label">Services Type</label>
                        
                        <select class="form-control" id="" required name="services_type">
                            <option value="no">Select Services</option>
                            <option value="hotels">Hotels</option>
                            <option value="flight">Flight</option>
                        </select>
                        
                    </div>
                        <div class="mb-3">
                        <label for="username" class="form-label">Pyment Method</label>
                        
                        <select class="form-control" id="payment_method_edit" required name="payment_method">
                            <option value="">Select Method</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="cash">Cash</option>
                        </select>
                        
                    </div>
                    <div class="mb-3" id="transection_id_edit" style="display:none;"> 
                        <label for="username" class="form-label">Transection Id</label>
                        
                        <input type="text" class="form-control" name="transection_id">
                        
                    </div>
                    
                    
                    <div class="mb-3" id="transection_remarks_edit" style="display:none;">
                        <label for="username" class="form-label">Transection Remarks</label>
                        
                        <input type="text" class="form-control" name="transection_remarks">
                        
                    </div>
                    
                    <div class="mb-3">
                    <label class="form-label">Total Amount</label>
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text add_currency_edit" id="basic-addon1"></span>
                        <input type="text" class="form-control" id="amount_edit" placeholder="Enter Amount"   readonly=""  aria-describedby="basic-addon1">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Available Amount</label>
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text add_currency_edit" id="basic-addon1"></span>
                        <input type="text" class="form-control" id="available_amount_edit" placeholder="Enter Amount"  readonly=""  aria-describedby="basic-addon1">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Add Amount</label>
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text add_currency_edit" id="basic-addon1"></span>
                        <input type="text" class="form-control" id="add_amount_edit" placeholder="Enter Amount" name="amount" required  aria-describedby="basic-addon1">
                    </div>
                </div>

                    

                    

                    <div class="mb-3 text-center">
                        <button class="btn btn-primary" type="submit" name="submit">Submit</button>
                    </div>

                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

    <div class="mt-5" id="">
        <div class="row">
            <div class="col-12">
                                <div class="card" style="background: #f1f5f8;">
                                    <div class="card-body">
<div class="row">
    <div class="col-md-8">
        <h4 class="">Credit Limit Lisit</h4>
    </div>
    <div class="col-md-4">
       
    <button type="button" class="btn btn-primary custom_css" data-bs-toggle="modal" data-bs-target="#credit-modal">Add Credit Limit</button>  
                                        
    </div>
</div>
                                        
                                        





                                        
                                       
                                                <table style="font-weight: bold;font-family: serif;" id="example_1" class="table dt-responsive nowrap w-100">
                                                    <thead class="theme-bg-clr">
                                                        <tr>
                                                            <th>id</th>
                                                            <th>Customer Details</th>
                                                           <th>Company Name</th>
                                                            <th>Total Amount</th>
                                                            <th>Remaining Amount</th>
                                                          <th>Action</th>
                                                           
                                                            
                                                           
                                                        </tr>
                                                    </thead>
                                                
                                                
                                                    <tbody>
                                                        <?php
                                                        if(isset($all_customer_credit))
                                                        {
                                                            $count=1;
                                                            foreach($all_customer_credit as $credit)
                                                            {
                                                                $customer= DB::table('customer_subcriptions')->where('id',$credit->customer_id)->first();
                                                                //print_r($customer);
                                                              ?>
                                                              
                                                        <tr>
                                                            <td>{{$count}}</td>
                                                            <td>
                                                                {{$customer->name}} {{$customer->lname}}</br>
                                                                {{$customer->email}}</br>
                                                                {{$customer->phone}}</br>
                                                                <?php print_r($customer->webiste_Address); ?>
                                                            
                                                            </td>
                                                            <td>{{$customer->company_name}}</td>
                                                            <td>{{$credit->currency}} {{$credit->total_amount}}</td>
                                                             <td>{{$credit->currency}} {{$credit->remaining_amount}}</td>
                                                            <td>
                                                                <a class="btn btn-primary" id="getDatafromEdit_{{$credit->id}}" onclick="getEditFunction({{$credit->id}})" atr-availableamount="{{$credit->remaining_amount}}" atr-name="{{$customer->name}}" atr-lname="{{$customer->lname}}" atr-amount="{{$credit->total_amount}}" atr-currency="{{$credit->currency}}" atr-customer_id="{{$credit->customer_id}}"  href="javascript:;" data-bs-toggle="modal" data-bs-target="#edit-credit-modal">Edit</a>
                                                                <a class="btn btn-primary" href="{{URL::to('super_admin/costomer_credit_ledger')}}/{{$customer->id}}" target="_blank">Ledger</a>
                                                            </td>
                                                           
                                                        </tr>
                                                        <?php
                                                        $count=$count+1;
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


<script>
      function getEditFunction(id)
   {
       console.log('id'+id);
       $('#credit_id_edit').val(id);
         const amount = $('#getDatafromEdit_'+id+'').attr('atr-amount');
         $('#amount_edit').val(amount);
       console.log('amount'+amount);
        const currency = $('#getDatafromEdit_'+id+'').attr('atr-currency');
        $('#currency_slc_edit').val(currency);
        $('.add_currency_edit').text(currency);
       console.log('currency'+currency);
        const customer_id = $('#getDatafromEdit_'+id+'').attr('atr-customer_id');
        $('#customer_id_edit').val(customer_id);
       console.log('customer_id'+customer_id);
       
       
       
         const availableamount = $('#getDatafromEdit_'+id+'').attr('atr-availableamount');
        $('#available_amount_edit').val(availableamount);
       console.log('availableamount'+availableamount);
       
       
       
           const customer_name = $('#getDatafromEdit_'+id+'').attr('atr-name');
       console.log('customer_name'+customer_name);
       
       const customer_lname = $('#getDatafromEdit_'+id+'').attr('atr-lname');
       console.log('customer_lname'+customer_lname);
       
       var username=customer_name + ' ' + customer_lname; 
       $('#customer_name_edit').val(username);

   }
</script>


    <script>
   $(document).ready(function () {
        $("#slc_customer").change(function(){
            //alert('jcjsj');
            var currency = $('option:selected', this).attr('atr-currency');
            
console.log('currency'+currency);
            $('.add_currency').text(currency);
            $('#currency_slc').val(currency);
            
        });
   });
   
   
 
   
</script> 
 <script>
   $(document).ready(function () {
        $("#payment_method").change(function(){
            //alert('jcjsj');
            var slc_value = $('option:selected', this).val();
            if(slc_value == 'bank_transfer')
            {
                $('#transection_remarks').fadeOut();
               $('#transection_id').fadeIn();  
            }
            if(slc_value == 'cash')
            {
                $('#transection_id').fadeOut();
              $('#transection_remarks').fadeIn();   
            }
            if(slc_value == '')
            {
                $('#transection_id').fadeOut();
              $('#transection_remarks').fadeOut();   
            }

            
        });
   });
   
      $(document).ready(function () {
        $("#payment_method_edit").change(function(){
            //alert('jcjsj');
            var slc_value = $('option:selected', this).val();
            if(slc_value == 'bank_transfer')
            {
                $('#transection_remarks_edit').fadeOut();
               $('#transection_id_edit').fadeIn();  
            }
            if(slc_value == 'cash')
            {
                $('#transection_id_edit').fadeOut();
              $('#transection_remarks_edit').fadeIn();   
            }
            if(slc_value == '')
            {
                $('#transection_id_edit').fadeOut();
              $('#transection_remarks_edit').fadeOut();   
            }

            
        });
   });
   
   
 
   
</script>
 @endsection