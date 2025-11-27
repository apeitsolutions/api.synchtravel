
<?php

//print_r($data);die();

?>

@extends('template/frontend/userdashboard/layout/default')
@section('content')

    <?php $currency=Session::get('currency_symbol'); ?>
    
    <style>
        .nav-link{
          color: #575757;
          font-size: 18px;
        }
    </style>

    <div class="content-wrapper">
        
        
        
        <section class="content" style="padding: 30px 50px 0px 50px;">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12 col-12">
                <nav class="breadcrumb push">
                    <a class="breadcrumb-item" href="#">Dashboard</a> 
                    <span class="breadcrumb-item active">View Agent Invoices</span>
                </nav>
              </div>
            </div>
          </div>
        </section>
        
        <section class="content" style="padding: 10px 20px 0px 20px">
          <div class="container-fluid">
            <div class="row">
            
              <!-- ./col -->
              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-success" style="height: 184px;text-align: center;padding: 15px 0px 0px 0px;background-color: #313a46 !important;color: white;">
                  <div class="icon mb-2">
                    <span class="iconify" data-icon="uil-dollar-sign-alt" data-width="70"></span>
                  </div>
                  <div class="inner">
                    <h3 id="total_revenue"></h3>
    
                    <p>Total Revenue</p>
                  </div>
                  
                </div>
              </div>
              <!-- ./col -->
              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box" style="height: 184px;text-align: center;padding: 15px 0px 0px 0px;background-color: #313a46 !important;color: white;">
                  <div class="icon mb-2">
                    <span class="iconify" data-icon="uil-dollar-sign-alt" data-width="70"></span>
                  </div>
                  <div class="inner">
                    <h3 id="total_paid"></h3>
    
                    <p>Total Paid</p>
                  </div>
                  
                </div>
              </div>
              <!-- ./col -->
              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info" style="height: 184px;text-align: center;padding: 15px 0px 0px 0px;background-color: #313a46 !important;color: white;">
                  <div class="icon mb-2">
                    <span class="iconify" data-icon="uil-dollar-sign-alt" data-width="70"></span>
                  </div>
                  <div class="inner">
                    <h3 id="outstanding"></h3>
    
                    <p>Outstanding</p>
                  </div>
                  
                </div>
              </div>
              
                <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-primary" onclick="manageOverPaid('{{ $agents_data->agent_id }}')" data-bs-toggle="modal" data-bs-target="#exampleModal" style="height: 184px;text-align: center;padding: 15px 0px 0px 0px;background-color: #313a46 !important;color: white;">
                  <div class="icon mb-2">
                    <!-- <i class="mdi mdi-eye me-1"></i> -->
                    <span class="iconify" data-icon="uil-dollar-sign-alt" data-width="70"></span>
                  </div>
                  <div class="inner">
                    
                    <p>Over Paid</p>
    
                    <h3 id="">{{ $agents_data->agent_over_paid }}</h3>
    
                  </div>
                  
                </div>
              </div>
              <!-- ./col -->
            </div>
          </div>
        </section>
    
        <section class="content" style="padding: 30px 50px 0px 50px;">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-12">
                        <div style="display:flex; justify-content:space-between;">
                        <h4>Agents Stats Details</h4>
                        <a href="{{ URL::to('financial_statement/'.$agents_data->agent_id.'') }}">Agent Finanical Details</a>
                        </div>
                        <div class="panel-body padding-bottom-none">
                            <div class="block-content block-content-full">
                                <div id="example_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table style="" class="table nowrap example1 dataTable no-footer" id="myTable" role="grid" aria-describedby="example_info">
                                                
                                                <thead class="theme-bg-clr">
                                                    <tr role="row">
                                                        <th>Sr</th>
                                                        <th>Agent Id</th>
                                                        <th>Agent Name</th>
                                                        <th>Invoice No</th>
                                                        <th>Total Amount</th>
                                                        <th>Paid Amount</th>
                                                        <th>Remaning Amount</th>
                                                        <th>Over Paid</th>
                                                        <th>Agent Commission</th>
                                                        <th>Profit</th>
                                                        <th>View Inv</th>
                                                    </tr>
                                                </thead>
                                                
                                                <tbody style="text-align: center;">
                                                    
                                                   @isset($agents_data)
                                                
                                                       <?php $x = 1;
                                                             $total_sale = 0;
                                                             $total_paymet_amount = 0;
                                                             $total_remaning_amount = 0;
                                                             $total_over_paid = 0;
                                                       ?>
                                                        @foreach($agents_data->agents_tour_booking as $agent_res)
                                                            <tr role="row">
                                                                <td>{{ $x++ }}</td>
                                                                <td>{{ $agents_data->agent_id }}</td>
                                                                <td>Company Name:{{ $agents_data->agent_company }}<br>
                                                                    Agent Name:{{ $agents_data->agent_name }}</td>
                                                                <td>{{ $agent_res->invoice_id }}<br>
                                                                    Package Name:{{ $agent_res->tour_name }}<br>
                                                                    Type:Package
                                                                </td>
                                                                <td>{{ $agent_res->price }}</td>
                                                                <td>{{ $agent_res->paid_amount }}</td>
                                                                <td>{{ $agent_res->remaing_amount }}</td>
                                                                <td>{{ $agent_res->over_paid_amount }} </td>
                                                                 
                                                                <td>Commission:{{ $agent_res->commission_am }}
                                                                </br>Include Total: 
                                                                <?php 
                                                                    if($agent_res->agent_commsion_add_total){
                                                                        echo "Yes";
                                                                    }else{
                                                                        echo "No";
                                                                    }
                                                                ?>
                                                                </td>
                                                                <td>Profit:{{ $agent_res->profit }}<br>
                                                                Cost:{{ $agent_res->total_cost }}<br>
                                                                Sale:{{ $agent_res->total_sale }}<br>
                                                                    Discount:{{ $agent_res->discount_am }}
                                                                </td>
                                                                <td>
                                                                      <a href="{{ URL::to('invoice_package/'.$agent_res->invoice_id.'/'.$agent_res->booking_id.'/'.$agent_res->tour_id.'') }}" target="blank" class="btn btn-primary btn-sm">view Details</a>
                                                                      
                                                                </td>
                                                                
                                                          

                                                            </tr>
                                                            
                                                            <?php 
                                                             $total_sale += $agent_res->price;
                                                             $total_paymet_amount += $agent_res->paid_amount;
                                                             $total_remaning_amount += $agent_res->remaing_amount;
                                                             $total_over_paid = $agent_res->over_paid_amount;
                                                            ?>
                                                        @endforeach
                                                        
                                                         @foreach($agents_data->agents_invoices_booking as $agent_res)
                                                            <tr role="row">
                                                                <td>{{ $x++ }}</td>
                                                                <td>{{ $agents_data->agent_id }}</td>
                                                                <td>Company Name:{{ $agents_data->agent_company }}<br>
                                                                    Agent Name:{{ $agents_data->agent_name }}</td>
                                                                <td>{{ $agent_res->invoice_id }}<br>
                                                                    Type:Invoice
                                                                </td>
                                                                <td>{{ $agent_res->price }}</td>
                                                                <td>{{ $agent_res->paid_amount }}
                                                                
                                                                </td>
                                                                <td>@if($agent_res->remaing_amount >= 0) {{ $agent_res->remaing_amount }} @endif</td>
                                                                <td>{{ $agent_res->over_paid_amount }}</td>
                                                                 
                                                                <td>
                                                                </td>
                                                                <td>Profit:{{ $agent_res->profit }}
                                                                 Cost:{{ $agent_res->total_cost }}<br>
                                                                Sale:{{ $agent_res->total_sale }}<br>
                                                                   
                                                                </td>
                                                                <td>

                                                                </td>
                                                                
                                                            </tr>
                                                            
                                                             <?php 
                                                             $total_sale += $agent_res->price;
                                                             $total_paymet_amount += $agent_res->paid_amount;
                                                             $total_remaning_amount += $agent_res->remaing_amount;
                                                            //  $total_over_paid = $agent_res->over_paid_amount;
                                                            ?>
                                                        @endforeach
                                                        
                                                        
                                                    @endisset()
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header " style="display:flex; justify-content: space-between;">
                  <h1 class="modal-title fs-5" id="exampleModalLabel">Manage Over Paid</h1>
                    <div>
                        <button class="btn btn-info btn-sm" onclick="manageBalance()">Manage Balance</button>
                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
               
              </div>
              <div class="modal-body">
                 <div id="example_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table style="" class="table nowrap example1 dataTable no-footer" id="myTable" role="grid" aria-describedby="example_info">
                                                
                                                <thead>
                                                    <tr role="row">
                                                        <th>Sr</th>
                                                        <th>Invoice No</th>
                                                        <th>Total Amount</th>
                                                        <th>Paid Amount</th>
                                                        <th>Remaning Amount</th>
                                                       
                                                    </tr>
                                                </thead>
                                                
                                                <tbody style="text-align: center;" id="unpaid_inv">
                                                </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
              </div>
            </div>
          </div>
        </div>
        
        <div class="modal fade" id="paid_amount" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                 <div > 
                   
                    <div style="display:flex;">
                         <h1 class="modal-title fs-5" id="exampleModalLabel">Manage Over Paid</h1>
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Available Balance:{{ $agents_data->agent_over_paid }}</h1>
                        
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <form action="{{ URL::to('adjust_over_pay')}}" method="post">
                        @csrf
                        
                     
                        
                        <div class="mb-2">
                          <label for="date" class="form-label">Date</label>
                          <input value="{{ now()->format('Y-m-d') }}" name="date" class="form-control" type="date" id="date" required="">
                        </div>

                        

                        <div class="mb-2">
                            <label for="total_Amount" class="form-label">Total Amount</label>
                            <input readonly name="total_Amount" value="" class="form-control" id="total_amount" type="text" id="total_amount" required="">
                        </div>

                        <div class="mb-2">
                            <label for="recieved_Amount" class="form-label">Adjust Amount</label>
                            <input name="recieved_Amount" class="form-control" type="number" id="recieved_Amount" max="{{ $agents_data->agent_over_paid }}" required="" placeholder="Recieved Amount">
                        </div>

                     

                        <div class="mb-2">
                            <label for="amount_Paid" class="form-label">Amount Paid</label>
                           
                                <input readonly value="" name="amount_Paid" class="form-control" type="text" id="paid_amount_val" required="">
                                <input readonly value="" name="paid_type" class="form-control" hidden type="text" id="paid_type" required="">
                                <input readonly value="" name="invoice_no" class="form-control" hidden type="text" id="invoice_no" required="">
                            
                        </div>

                        <div style="padding: 10px 0px 10px 0px;">
                            <button style="padding: 10px 30px 10px 30px;" type="submit" class="btn btn-primary" ><i class="mdi mdi-send me-1"></i>Recieve</button>
                            <button style="margin-left: 5px;padding: 10px 30px 10px 30px;" type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
              </div>
              
            </div>
          </div>
        </div>
        
        <div class="modal fade" id="manage_balance" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                 <div > 
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Manage Balance</h1>
                    
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form action="{{ URL::to('manage_wallent_balance') }}" method="post" enctype="multipart/form-data">
                  @csrf
                    <div class="modal-body">
                          
                   
                      <div class="row">
                        <div class="col-12 mb-2">
                            <input type="text" name="agent_id" hidden value="{{ $agents_data->agent_id }}">
                            <label>Select Transcation Type</label>
                            <select class="form-control" id="" name="transtype">
                                <option value="Refund">Refund</option>
                                <option value="Deposit">Deposit</option>
                            </select>
                        </div>
                         <div class="col-6 mb-2">
                            <label>Payment Method</label>
                            <select class="form-control" onchange="paymentMethod()" id="payment_method" name="payment_method">
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Cash">Cash</option>
                                <option value="Card Payment">Card Payment</option>
                            </select>
                        </div>
                        <div class="col-6 mb-2">
                             <label>Payment Date</label>
                             <input type="date" class="form-control" required="" value="2022-12-14" name="payment_date" placeholder="Enter Payment Amount">
                        </div>
                        
                        <div class="col-6 mb-2">
                             <label>Payment Amount</label>
                             <input type="text" class="form-control" required="" name="payment_am" placeholder="Enter Payment Amount">
                        </div>
                        
                      
                        
                        <div class="col-6 mb-2" id="transcation_id" style="display: block;">
                            <label>Transaction ID</label>
                          <input type="text" class="form-control" name="transcation_id" placeholder="Enter Transaction ID">
                          <input type="text" class="form-control" required="" name="invoice_id" hidden="" value="AHT3383261" placeholder="Enter Transaction ID">
                        </div>
                        
                       <div class="col-6 mb-2" id="account_id" style="display: block;">
                           <label>Account No.</label>
                          <input type="text" class="form-control" name="account_no" placeholder="Payment to (Account No.)" value="13785580">
                        </div>
                        
                      </div>
                   
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                  </div>
           </form>
              
            </div>
          </div>
        </div>
        
        
        
    </div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  
    <script>
     <?php 
                                                            //  $total_sale += $agent_res->price;
                                                            //  $total_paymet_amount += $agent_res->paid_amount;
                                                            //  $total_remaning_amount += $agent_res->remaing_amount;
                                                            //  $total_over_paid = $agent_res->over_paid_amount;
                                                            ?>
                                                            
        function paid_amount(id,type,total,paid){
            $('#paid_amount').modal('show');
            $('#total_amount').val(total);
            $('#paid_amount_val').val(paid);
            $('#paid_type').val(type);
            $('#invoice_no').val(id);
            
        }
        
         function manageBalance(){
            $('#manage_balance').modal('show');
          
            
        }
       
       
         function paymentMethod(){
             var paymentMethod = $('#payment_method').val();
             if(paymentMethod == 'Cash'){
                 $('#transcation_id').css('display','none');
                 $('#account_id').css('display','none');
             }else{
                  $('#transcation_id').css('display','block');
                  $('#account_id').css('display','block');
             }
         }
                                                            
        function manageOverPaid(invoiceNum){
             $('#total_amount').val(0);
            $('#paid_amount_val').val(0);
             $('#unpaid_inv').html('');            
            console.log('Invoice Number is '+invoiceNum);
            
              $.ajax({
                    url: '{{ URL::to("getupaidInvoicesAgent") }}',
                    type: 'POST',
                    data: {
                        "_token":'{{ CSRF_token() }}',
                        "id": invoiceNum
                    },
                    success:function(data) {
                        var packInvList = JSON.parse(data);
                        
                         var packageTr = ``;
                         
                         var x = 1;
                         packInvList['package_inv_lists'].forEach((obj)=>{
                             var remainingVal = obj['tour_total_price'] - obj['total_paid_amount'];
                             packageTr += `<tr>
                                                <td>${x++}</td>
                                                <td>${obj['invoice_no']}
                                                <br> Type:Package
                                                </td>
                                                <td>${obj['tour_total_price']}</td>
                                                <td>${obj['total_paid_amount']}</td>
                                                <td>${remainingVal}</td>
                                                <td><button class="btn btn-primary" onclick="paid_amount('${obj['invoice_no']}','package',${obj['tour_total_price']},${obj['total_paid_amount']})">Pay Amount</button></td>
                                            </tr>`
                         })
                            
                        packInvList['inv_lists'].forEach((obj)=>{
                             var remainingVal = obj['total_sale_price_all_Services'] - obj['total_paid_amount'];
                             packageTr += `<tr>
                                                <td>${x++}</td>
                                                <td>${obj['id']}
                                                <br> Type:Invoice
                                                </td>
                                                <td>${obj['total_sale_price_all_Services']}</td>
                                                <td>${obj['total_paid_amount']}</td>
                                                <td>${remainingVal}</td>
                                                <td><button class="btn btn-primary" onclick="paid_amount('${obj['id']}','Invoice',${obj['total_sale_price_all_Services']},${obj['total_paid_amount']})">Pay Amount</button></td>
                                            </tr>`
                         })
                            $('#unpaid_inv').html(packageTr);            
                                        
                        console.log(packInvList);
                    }
              })
        }
        var totalSale = '<?php echo $total_sale ?>';
        var totalPaid = '<?php echo $total_paymet_amount ?>';
        var totalRemaning = '<?php echo $total_remaning_amount ?>';
        var totalOverpaid = '<?php echo $total_over_paid ?>';
        
        $('#total_revenue').html(totalSale);
        $('#total_paid').html(totalPaid);
        $('#outstanding').html(totalRemaning);
        $('#over_paid').html(totalSale);
    
    
        $(document).ready(function () {
          
            //DataTable
            $('#myTable').DataTable({
                scrollX: true,
            });
            
            //View Modal Single Quotation
            $('.detail-btn').click(function() {
                const id = $(this).attr('data-id');
                $.ajax({
                    url: 'view_QuotationsID/'+id,
                    type: 'GET',
                    data: {
                        "id": id
                    },
                    success:function(data) {
                        var a                = data['a'];
                        var roundTripDetails = a['roundTripDetails'];
                        var oneWayDetails    = a['oneWayDetails'];
                        
                        console.log(a);
            
                        //Flight Details
                        $('#airline_name').html(oneWayDetails['airline_name']);
                        $('#deprturetime').html(oneWayDetails['deprturetime']);
                        $('#ArrivalTime').html(oneWayDetails['ArrivalTime']);
                        $('#deprturedate').html(oneWayDetails['deprturedate']);
                        $('#ArrivalDate').html(oneWayDetails['ArrivalDate']);
                        $('#departure').html(oneWayDetails['departure']);
                        $('#arrival').html(oneWayDetails['arrival']);
                        
                        $('#flighttype').html(a['flighttype']);
                        $('#flight_price').html(a['flight_price']);
                        
                        //Hotel Booking Details Makkkah
                        $('#dateinmakkah').html(a['dateinmakkah']);
                        $('#dateoutmakkah').html(a['dateoutmakkah']);
                        $('#hotel_name_makkah').html(a['hotel_name_makkah']);
                        $('#no_of_rooms_makkah').html(a['no_of_rooms_makkah']);
                        $('#Price_Per_Nights_Makkah').html(a['Price_Per_Nights_Makkah']);
                        $('#Makkah_total_price_cal').html(a['Makkah_total_price_cal']);
                        
                        //Hotel Booking Details Madinah
                        $('#dateinmadinah').html(a['dateinmadinah']);
                        $('#dateoutmadinah').html(a['dateoutmadinah']);
                        $('#hotel_name_madina').html(a['hotel_name_madina']);
                        $('#no_of_rooms_madina').html(a['no_of_rooms_madina']);
                        $('#price_per_night_madinah').html(a['price_per_night_madinah']);
                        $('#madinah_total_price_cal').html(a['madinah_total_price_cal']);
                        
                        
                        //Transfer Details
                        $('#transfer_vehicle').html(a['transfer_vehicle']);
                        $('#passenger').html(a['passenger']);
                        $('#pickuplocat').html(a['pickuplocat']);
                        $('#dropoflocat').html(a['dropoflocat']);
                        $('#trans_date').html(a['trans_date']);
                        $('#transf_price').html(a['transf_price']);
                        
                        //Visa Details
                        $('#visa_fees_adult').html(a['visa_fees_adult']);
                        $('#visa_fees_child').html(a['visa_fees_child']);
                        $('#visa_fees_price').html(a['visa_fees_price']);
                        
                        //Totals
                        $('#flight_price_total').html(a['flight_price']);
                        $('#Makkah_total_price_cal').html(a['Makkah_total_price_cal']);
                        $('#madinah_total_price_cal').html(a['madinah_total_price_cal']);
                        $('#transfers_head_total').html(a['transfers_head_total']);
                        $('#visa_fees').html(a['visa_fees_price']);
                        $('#grand_total_price').html(a['grand_total_price']);
            
                    }
                })
            });
    
        });

    </script>
    
    <script>
    
        function add_more_passenger(id){
            $('#add_more_P_div').empty();
            $('#invoice_Id_Input').val();
            $('#lead_name_ID').empty();
            $('#no_of_pax_days_ID').empty();
            $('#no_of_pax_days_Input').val();
            $('#add_more_P').css('display','');
            $('#more_P_D_div').empty();
            let I_id = id;
            $.ajax({
                url:"{{URL::to('get_single_Invoice')}}" + '/' + I_id,
                method: "GET",
                data: {
                	I_id:I_id
                },
                success:function(data){
                    var data1                   = data['data'];
                    var invoice_Id              = data1['id'];
                    var f_name                  = data1['f_name'];
                    var middle_name             = data1['middle_name'];
                    var no_of_pax_days          = data1['no_of_pax_days'];
                    $('#invoice_Id_Input').val(invoice_Id);         	  
                    $('#lead_name_ID').html(f_name+' '+middle_name);
                    $('#no_of_pax_days_ID').html(no_of_pax_days);
                    $('#no_of_pax_days_Input').val(no_of_pax_days);
                    
                    var count_P_Input = data1['count_P_Input'];
                    if(count_P_Input > 0 && count_P_Input != null && count_P_Input != ''){
                        $('#count_P_Input').val(count_P_Input);
                        $('#count_P_Input1').val(count_P_Input);
                    }else{
                        $('#count_P_Input').val(1);
                        $('#count_P_Input1').val(1);
                    }
                    
                    var more_Passenger_Data = data1['more_Passenger_Data'];
                    if(more_Passenger_Data != null && more_Passenger_Data != ''){
                        $('#more_Passenger_Data_Input').val(more_Passenger_Data);
                        var edit_M_P_Button = `<button class="btn btn-primary" id="edit_more_P" onclick="edit_more_P()" type="button">Edit more Passenger</button>`;
                        // $('#edit_M_P_Button').append(edit_M_P_Button);
                        
                        var more_Passenger_Data_decode = JSON.parse(data1['more_Passenger_Data']);
                        $.each(more_Passenger_Data_decode, function (key, value) {
                            var more_p_fname    = value.more_p_fname;
                            var more_p_lname    = value.more_p_lname;
                            var more_p_gender   = value.more_p_gender;
                            var more_p_passport = value.more_p_passport;
                            var more_p_image    = value.more_p_image;
                            var url_IandP       = `https://alhijaztours.net/public/uploads/package_imgs/`;
                            
                            var data =  `<div class="row" id="main_div_P_${invoice_Id}">
                                            <div class="col-lg-12">
                                                <h5>More Passenger details</h5>
                                            </div>
                                            <div class="col-lg-2 mb-3">
                                                <label for="label" class="form-label">First Name</label>
                                                <input class="form-control remove_readonly_prop" type="text" value="${more_p_fname}" name="" placeholder="" readonly>
                                            </div>
                                            <div class="col-lg-2 mb-3">
                                                <label for="label" class="form-label">Last Name</label>
                                                <input class="form-control remove_readonly_prop" type="text" value="${more_p_lname}" name="" placeholder="" readonly>
                                            </div>
                                            <div class="col-lg-2 mb-3">
                                                <label for="label" class="form-label">Gender</label>
                                                <select class="form-control" name="" readonly>`;
                                                    if(more_p_gender == 'Male'){
                            data +=                     `<option value="Male" selected>Male</option>
                                                        <option value="Female">Female</option>`;
                                                    }
                                                    else if(more_p_gender == 'Female'){
                            data +=                     `<option value="Male">Male</option>
                                                        <option value="Female" selected>Female</option>`;
                                                    }
                                                    else{
                            data +=                     `<option value="">Choose...</option>
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>`;
                                                    }
                            data +=             `</select>
                                            </div>
                                            <div class="col-lg-3 mb-3">
                                                <label for="image" class="form-label">Passport</label>
                                                <input class="form-control" type="text" value="${more_p_passport}" name="" readonly>
                                                <input class="form-control" type="file" value="${more_p_passport}" name="" hidden>
                                            </div>
                                            <div class="col-lg-3 mb-3">
                                                <label for="image" class="form-label">Image</label>
                                                <img src="${url_IandP}${more_p_image}" style="height: 80px;width: 80px;">
                                                <input class="form-control" type="text" name="" readonly hidden>
                                                <input class="form-control" type="file" name="" readonly hidden>
                                            </div>
                                        </div>`;
                            
                            if(more_p_fname != null){
                                $('#more_P_D_div').append(data);
                            }
                        });
                    }
                    
                    if(no_of_pax_days = count_P_Input){
                        $('#edit_M_P_Button').css('display','none')
                    }
                }
            }); 
        }
        
        $('#add_more_P').click(function() {
            $('#button_P').css('display','');
            
            var count_P = $('#count_P_Input').val();
            count_P     = parseFloat(count_P) + 1;
            
            var no_of_pax_days_Input    = $('#no_of_pax_days_Input').val();
            var id_P                    = $('#invoice_Id_Input').val();
            var data =  `<div class="row" id="main_div_P_${id_P}${count_P}">
                            <div class="col-lg-12">
                                <h5>More Passenger # ${count_P}</h5>
                            </div>
                            <div class="col-lg-2 mb-3">
                                <label for="label" class="form-label">First Name</label>
                                <input class="form-control" type="text" id="more_p_fname${id_P}_${count_P}" name="more_p_fname[]" placeholder="" required>
                            </div>
                            <div class="col-lg-2 mb-3">
                                <label for="label" class="form-label">Last Name</label>
                                <input class="form-control" type="text" id="more_p_lname${id_P}_${count_P}" name="more_p_lname[]" placeholder="" required>
                            </div>
                            <div class="col-lg-2 mb-3">
                                <label for="label" class="form-label">Gender</label>
                                <select class="form-control" id="more_p_gender${id_P}_${count_P}" name="more_p_gender[]" placeholder="Choose..." required>
                                    <option value="">Select</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <label for="image" class="form-label">Passport</label>
                                <input class="form-control" type="file" id="more_p_passport${id_P}_${count_P}" name="more_p_passport[]" placeholder="" required>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input class="form-control" type="file" id="more_p_image${id_P}_${count_P}" name="more_p_image[]" placeholder="" required>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary" id="remove_more_P" onclick="removeMorePassenger(${id_P}${count_P})" type="button">Remove</button>
                            </div>
                        </div>`;
                        
            if(count_P <= no_of_pax_days_Input){
                $('#add_more_P_div').append(data);
                $('#count_P_Input').val(count_P);
            }else{
                $('#add_more_P').css('display','none');
                count_P = count_P - 1;
                $('#count_P_Input').val(count_P);
            }
        });
        
        function removeMorePassenger(id){
            $('#main_div_P_'+id+'').remove();
            var no_of_pax_days_Input    = $('#no_of_pax_days_Input').val();
            var count_P                 = $('#count_P_Input').val();
            var count_P_Input1          = $('#count_P_Input1').val();
            count_P                     = count_P - 1;
            $('#count_P_Input').val(count_P);
            if(count_P <= no_of_pax_days_Input){
                $('#add_more_P').css('display','');
            }
            
            if(count_P_Input = count_P_Input1){
                $('#button_P').css('display','none');
            }
        }
        
    </script>

@endsection
