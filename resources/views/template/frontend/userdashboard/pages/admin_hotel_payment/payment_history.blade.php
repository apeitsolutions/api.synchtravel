

@extends('template/frontend/userdashboard/layout/default')
 @section('content')
 
 
 
 <div id="bs-example-modal-lg_approve" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form action="{{URL::to('super_admin/hotels/payment/approved')}}" method="post" class="modal-body p-4">
                @csrf
                <input type="hidden" name="payment_id" id="payment_id" value="" >
                <div class="text-center">
                    <i class="dripicons-information h1 text-info"></i>
                    <h4 class="mt-2">Heads up!</h4>
                    <p class="mt-3">Are You Sure You Want to Approve This Payment?</p>
                    <button type="submit" name="submit" class="btn btn-info my-2">Continue</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

 <div id="bs-example-modal-lg_decline" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form action="{{URL::to('super_admin/hotels/payment/declined')}}" method="post" class="modal-body p-4">
                @csrf
                <input type="hidden" name="payment_id" id="payment_id_decline" value="" >
                <div class="text-center">
                    <i class="dripicons-information h1 text-info"></i>
                    <h4 class="mt-2">Heads up!</h4>
                    <p class="mt-3">Are You Sure You Want to Decline This Payment?</p>
                    <button type="submit" name="submit" class="btn btn-info my-2">Continue</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
 
 
 

    <div class="mt-5" id="HotelBeds">
       <div class="row">
            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <h4 class="header-title">Hotel Payment History</h4>
                                        
                                                <table id="example_2" class="table dt-responsive nowrap w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>id</th>
                                                            <th>Transaction Id</th>
                                                            <th>Payment Method</th>
                                                            <th>Client Details</th>
                                                            <th>Paid Amount</th>
                                                            
                                                            <th>Payment Rmarks</th>
                                                            <th>Payment Status</th>
                                                            
                                                             <th>Action</th>
                                                            
                                                           
                                                            
                                                           
                                                        </tr>
                                                    </thead>
                                                
                                                
                                                    <tbody>
                                                        <?php
                                                        if(isset($hotel_payment_details))
                                                        {
                                                            foreach($hotel_payment_details as $payment_history)
                                                            {
                                                                
                                                            $client = DB::table('customer_subcriptions')->where('Auth_key',$payment_history->token)->first();
                                                          
                                                           ?>
                                                           
                                                         <tr>
                                                            <td>{{$payment_history->id}}</td>
                                                            <td>{{$payment_history->payment_transction_id ?? ''}}</br>({{$payment_history->payment_date ?? ''}})</td>

                                                           
                                                            
                                                            
                                                            <td>
                                                                <?php
                                                                if($payment_history->payment_method == 'bank_transfer')
                                                                {
                                                                    echo 'Bank Transfer';
                                                                }
                                                                else
                                                                {
                                                                    echo 'CASH';
                                                                }
                                                                ?>
                                                                
                                                            </td>
                                                            <td>
                                                                Id : {{$client->id ?? ''}}
                                                                </br>
                                                                Name : {{$client->name ?? ''}} {{$client->lname ?? ''}}
                                                                </br>
                                                                Company Name : {{$client->company_name ?? ''}}
                                                            </td>
                                                            
                                                            <td>
                                                             {{$client->currency_symbol}} {{$payment_history->payment_paid_amount ?? ''}}
                                                            
                                                            </td>
                                                            
                                                            <td>{{$payment_history->payment_remarks ?? ''}} </td>
                                                            
                                                            <td>
                                                                
                                                                <?php
                                                                if($payment_history->payment_status == 0)
                                                                {
                                                                    ?>
                                                                    <a href="javascript:;"  class="btn btn-danger">Panding</a>
                                                                    <?php
                                                                }
                                                                if($payment_history->payment_status == 1)
                                                                {
                                                                ?>
                                                                <a href="javascript:;"  class="btn btn-success">Approved</a>
                                                                <?php
                                                                }
                                                                if($payment_history->payment_status == 2)
                                                                {
                                                                ?>
                                                                <a href="javascript:;"  class="btn btn-danger">Declined</a>
                                                                <?php
                                                                }
                                                                ?>
                                                                
                                                            </td>
                                                            <td>
                                                                 <?php
                                                                 if($payment_history->payment_status == 0)
                                                                 {
                                                                     ?>
                                                                  <a  href="javascript:;" data-bs-toggle="modal" data-bs-target="#bs-example-modal-lg_approve" onClick="ApproveFun({{$payment_history->id}})"  data-id="{{$payment_history->id}}" class="btn btn-success btn-sm">Approve</a>
                                                                   <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#bs-example-modal-lg_decline" onClick="DeclineFun({{$payment_history->id}})" data-id="{{$payment_history->id}}" class="btn btn-secondary btn-sm">Decline</a>
                                                                     <?php
                                                                 }
                                                                 if($payment_history->payment_status == 1)
                                                                {
                                                                ?>
                                                                <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#bs-example-modal-lg_decline" onClick="DeclineFun({{$payment_history->id}})" data-id="{{$payment_history->id}}" class="btn btn-secondary btn-sm">Decline</a>
                                                                <?php
                                                                }
                                                                if($payment_history->payment_status == 2)
                                                                {
                                                                ?>
                                                                 <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#bs-example-modal-lg_approve" onClick="ApproveFun({{$payment_history->id}})"  data-id="{{$payment_history->id}}" class="btn btn-success btn-sm">Approve</a>
                                                                <?php
                                                                }
                                                                 
                                                                 ?>
                                                       
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
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script type="text/javascript">
    
        function ApproveFun(id)
        {
          console.log(id);
           $('#payment_id').val(id);
        }
        function DeclineFun(id)
        {
          console.log(id);
           $('#payment_id_decline').val(id);
        }
   
</script>                 
                     
 @endsection