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
        
        <section class="content d-none" style="padding: 30px 50px 0px 50px;">
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
        
        
                 <div class="row mt-5">
                    <div class="col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="dropdown float-end">
                                    <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="mdi mdi-dots-horizontal"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm rounded">
                                            <span class="avatar-title bg-primary-lighten h3 my-0 text-primary rounded">
                                                <i class="">SAR</i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h4 class="mt-0 mb-1 font-20" id="total_revenue">{{ $supplier_data->total_payable }}</h4>
                                        <p class="mb-0 text-muted"><i class="mdi mdi-arrow-up-bold text-success"></i>Total Payable</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3" onclick="payment_amount()">
                        
                            <div class="card">
                                <div class="card-body">
                                    <div class="dropdown float-end">
                                        <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="mdi mdi-dots-horizontal"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm rounded">
                                                <span class="avatar-title bg-primary-lighten h3 my-0 text-primary rounded">
                                                    <i class="">SAR</i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h4 class="mt-0 mb-1 font-20" id="total_paid">{{ $supplier_data->payment_amount }}</h4>
                                            <p class="mb-0 text-muted"><i class="mdi mdi-arrow-up-bold text-success"></i>Total Paid</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                  
                    </div>

                    <div class="col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="dropdown float-end">
                                    <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="mdi mdi-dots-horizontal"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm rounded">
                                            <span class="avatar-title bg-primary-lighten h3 my-0 text-primary rounded">
                                                <i class="">SAR</i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h4 class="mt-0 mb-1 font-20" id="outstanding">{{ $supplier_data->remaining_amount }}</h4>
                                        <p class="mb-0 text-muted"><i class="mdi mdi-arrow-up-bold text-success"></i>Outstandings</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="dropdown float-end">
                                    <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="mdi mdi-dots-horizontal"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="avatar-sm rounded">
                                            <span class="avatar-title bg-primary-lighten h3 my-0 text-primary rounded">
                                                <i class="">SAR</i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h4 class="mt-0 mb-1 font-20">{{ $supplier_data->supplier_wallet_balance }}</h4>
                                        <p class="mb-0 text-muted"><i class="mdi mdi-arrow-up-bold text-success"></i>Over Paid</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        <section class="content" style="padding: 30px 50px 0px 50px;">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-12">
                        <h4>Transfer Supplier Stats Details</h4>
                        <div class="panel-body padding-bottom-none">
                            <div class="block-content block-content-full">
                                
                                <div class="table-responsive">
                                    <div id="example_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table style="" class="table nowrap example1 dataTable no-footer" id="myTable" role="grid" aria-describedby="example_info">
                                                
                                                <thead class="theme-bg-clr">
                                                    <tr role="row">
                                                        <th>Sr</th>
                                                        <th>Supplier Id</th>
                                                        <th>Supplier Name</th>
                                                        <th>Invoice Id</th>
                                                        <th>Destination Id</th>
                                                        <th>Total Vehicle</th>
                                                        <th>Vehicle Name</th>
                                                        <th>Total Cost</th>
                                                        <th>Total Sale</th>
                                                        <th>Profit</th>
                                                        <!--<th>View Inv</th>-->
                                                    </tr>
                                                </thead>
                                                
                                                <tbody style="text-align: center;">
                                                    
                                                   @isset($supplier_data)
                                                
                                                       <?php $x = 1;
                                                            
                                                       ?>
                                                        @foreach($supplier_data->supplier_inv_data as $sup_res_all)
                                                            @foreach($sup_res_all as $sup_res)
                                                            
                                                            <?php 
                                                                // print_r($sup_res);
                                                            ?>
                                                            <tr role="row">
                                                                <td>{{ $x++ }}</td>
                                                                <td>{{ $supplier_data->supplier_id }}</td>
                                                                <td>{{ $supplier_data->supplier_name }}</td>
                                                                <td>{{ $sup_res->invoice_id }}</td>
                                                                <td>{{ $sup_res->destination_id }}</td>
                                                                <td>{{ $sup_res->no_of_vehicle }}</td>
                                                                <td>{{ $sup_res->vehicle_name }}</td>
                                                                <td>{{ $sup_res->total_vehicle_price }} </td>
                                                                <td>{{ $sup_res->total_vehicle_price_wi_markup }} </td>
                                                                <td>{{ $sup_res->profit }} </td>
                                                               
                                                            </tr>
                                                            
                                                            @endforeach
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
            </div>
        </section>
        
    

        
        <div id="Payment_modal" class="modal fade" tabindex="-1" style="display: none;" aria-hidden="true">
               <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header modal-colored-header bg-primary">
                            <h4 class="modal-title" id="compose-header-modalLabel">Payment Amount</h4>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                         <div class="modal-body flight_payment_html"> <div class="p-1">
                            <div class="modal-body px-3 pt-3 pb-0">
                                <form action="{{ URL::to('transfer_supplier_payments') }}" method="post">
                                    @csrf     
                                    <!--<input hidden readonly value="" name="flight_id" class="form-control" type="text" id="package_id" required="">-->
                                    
                                    <div class="mb-2">
                                        
                                         
                                                
                                     
                                      
                                      <input readonly="" name="supplier_id" class="form-control" hidden="" value="{{ $supplier_data->supplier_id }}" type="text" id="supplier_id_pay" required="">
                                    </div>
                                                             
                                    <div class="mb-2">
                                      <label for="tourId" class="form-label">Suplier Name</label>
                                      <input name="supplier_name" class="form-control" readonly="" value="{{ $supplier_data->supplier_name }}" id="supplier_name" type="text" required="">
                                      <input hidden="" name="payingidentityid" class="form-control" value="4" type="text" required="">
                                    </div>
            
            
                                    <div class="mb-2">
                                      <label for="date" class="form-label">Date</label>
                                      <input value="{{ date('Y-m-d') }}" name="date" class="form-control" type="date" id="date" required="">
                                    </div>
            
                                    <div class="mb-2">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="total_amount" class="form-label">Total Amount</label>
                                                <input readonly="" name="total_amount" value="{{ $supplier_data->total_payable }}" class="form-control" type="text" id="total_amount" required="">
                                            </div>
                                            
                                            <div class="col-md-6">
                                               <label for="total_amount" class="form-label">Paid Amount</label>
                                                <input readonly="" name="total_amount_payable" value="{{ $supplier_data->payment_amount }}" class="form-control" type="text" id="total_amount_payable" required="">
                                            </div>
                                            
                                          
                                            <div class="col-md-12">
                                               <label for="total_amount" class="form-label">Remaining Payable</label>
                                                <input readonly="" name="" value="{{ $supplier_data->remaining_amount }}" class="form-control" type="text" id="rem_total_pay" required="">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    
                                    
            
                                    <div class="mb-2">
                                        <div class="row">
                                            <div class="col-md-6">
                                               <label for="total_amount" class="form-label">Payment From</label>
                                                <select class="form-control" onchange="paymentMethod()" id="payment_method" name="payment_method">
                                                    <option value="Bank Transfer">Bank Transfer</option>
                                                    <option value="Cash">Cash</option>
                                                    <option value="Card Payment">Card Payment</option>
                                                    <option value="Wallet">Wallet</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="total_amount" class="form-label">Wallet Balance</label>
                                                 <input name="" class="form-control" type="number" readonly="" value="{{ $supplier_data->supplier_wallet_balance }}" id="supplier_wallet" required="">
                                            </div>
                                        </div>
                                     
                                    </div>
                                    
                                    <div class="mb-2">
                                         <div class="row">
                                      
                                            <div class="col-md-12">
                                                 <label for="amount_paid" class="form-label">Payment Amount</label>
                                       
                                                <input name="amount_paid" onchange="myFunction()" required="" class="form-control" type="number" id="amount_paid" placeholder="Nothing Paid yet">
                                            </div>
                                        </div>
                                     
                               
                                    </div>
            
                                    <div style="padding: 10px 0px 10px 0px;">
                                        <button style="padding: 10px 30px 10px 30px;" type="submit" class="btn btn-primary"><i class="mdi mdi-send me-1"></i>Payment</button>
                                        <button style="margin-left: 5px;padding: 10px 30px 10px 30px;" type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                    </div>
            
                                    <!-- <div class="mb-2 text-center">
                                      <button class="btn btn-primary" type="submit">Recieve</button>
                                    </div> -->
                                </form>
                            </div>
                        </div></div>
                       
                    </div>
               </div>
            </div>
        
    </div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  
    <script>
        function payment_amount(){
            console.log('Payment function is call now');
            $('#Payment_modal').modal('show');
        }
        
         function paymentMethod(){
            var payment_method = $('#payment_method').val();
            console.log('The payment is call now ');
            if(payment_method == 'Wallet'){
             
                var walletAmount = $('#supplier_wallet').val();
                $('#amount_paid').attr('max',walletAmount);
            }else{
                  
                $('#amount_paid').attr('max',false);
            }
        }
    </script>


@endsection
