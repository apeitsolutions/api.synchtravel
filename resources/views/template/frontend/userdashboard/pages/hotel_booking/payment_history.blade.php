

@extends('template/frontend/userdashboard/layout/default')
 @section('content')
 

    
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
                                                            <th>Paid Amount</th>
                                                            
                                                            <th>Payment Rmarks</th>
                                                            <th>Payment Status</th>
                                                            
                                                           
                                                            
                                                           
                                                        </tr>
                                                    </thead>
                                                
                                                
                                                    <tbody>
                                                        <?php
                                                        if(isset($hotel_payment_details))
                                                        {
                                                            foreach($hotel_payment_details as $payment_history)
                                                            {
                                                                
                                                            
                                                          
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
                                                           {{$customer->currency_symbol}} {{$payment_history->payment_paid_amount ?? ''}}
                                                            
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