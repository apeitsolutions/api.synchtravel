@extends('template/frontend/userdashboard/layout/default')
@section('content')
    <?php
        // dd($supplier);
    ?>

    <div class="container-fluid">
        <div class="table-responsive">
            <div class="row">
                
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" style="background-color:#f5cfcf;" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" style="background-color:#d4edda;" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                
                <table class="table table-centered w-100 dt-responsive nowrap" id="example1">
                    <thead class="table-light">
                        <tr>
                            <th style="text-align: center;">Sr No.</th>
                            <th style="text-align: center;">Customer Name</th>
                            <th style="text-align: center;">Wallent Balance</th>
                            <th style="text-align: center;">Opening Balance</th>
                            <th style="text-align: center;">Balance</th>
                            <th style="text-align: center;">Company Name</th>
                            <th style="text-align: center;">Company Address</th>
                            <th style="text-align: center;">Company Email</th>
                            <th style="text-align: center;">Contact Person Name</th>
                            <th style="text-align: center;">Contact Person Email</th>
                            <th style="text-align: center;">Contact Person No.</th>
                            <th style="width: 85px;display:none">Action</th>
                        </tr>
                    </thead>
                    <tbody style="text-align: center;">
                        @foreach($supplier as $supplier)
                        <tr>
                        <td>{{$supplier->id ?? ""}}</td>
                        <td>
                            @if(isset($all_Users) && $all_Users != null && $all_Users != '')
                                @foreach($all_Users as $all_Users_value)
                                    @if($supplier->customer_id == $all_Users_value->id)
                                        <b>{{ $all_Users_value->name }}</b>
                                    @endif
                                @endforeach
                            @endif
                        </td>
                        <td>{{$supplier->wallet_amount }}</td>
                        <td>{{$supplier->opening_balance }}</td>
                        <td>{{$supplier->balance }}</td>
                        <td>{{$supplier->companyname ?? ""}}</td>
                        <td>{{$supplier->Companyaddress ?? ""}}</td>
                        <td>{{$supplier->companyemail ?? ""}}</td>
                        <td>{{$supplier->contactpersonname ?? ""}}</td>
                        <td>{{$supplier->contactpersonemail ?? ""}}</td>
                        <td>{{$supplier->personcontactno ?? ""}}</td>
                        <td style="display:none">
                            <a href="{{URL::to('super_admin/flight_supplier_ledger')}}/{{$supplier->id}}"><span class="badge bg-success" style="font-size: 15px">Ledger</span></a> 
                            <a href="{{URL::to('editsupplier')}}/{{$supplier->id ?? ""}}" class="mb-2"><span class="badge bg-success " style="font-size: 15px">Edit</span></a> <br>
                            <a href="{{URL::to('deletesupplier')}}/{{$supplier->id ?? ""}}" class="mb-2"><span class="badge bg-success" style="font-size: 15px">Delete</span></a><br>
                            <a onclick="manage_balance('{{ $supplier->id }}',{{ $supplier->wallet_amount }})" class="mb-2" data-bs-toggle="modal" data-bs-target="#exampleModal"><span class="badge bg-success" style="font-size: 15px">Manage Wallet</span></a><br>
                            <a href="{{URL::to('super_admin/supplier_wallet_trans')}}/{{$supplier->id ?? ""}}" class="mb-2"><span class="badge bg-success" style="font-size: 15px">Wallet Trans</span></a><br>
                            <a href="{{URL::to('super_admin/supplier_stats')}}/{{$supplier->id ?? ""}}" class="mb-2"><span class="badge bg-success" style="font-size: 15px">View Stats</span></a>
                        </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div> 
    </div>
                
        <div class="modal fade" id="manage_balance" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                 <div> 
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Manage Balance</h1>
                    
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form action="https://alhijaztours.net/manage_wallent_balance" method="post" enctype="multipart/form-data">
                 @csrf           
                  <div class="modal-body">
                          
                   
                      <div class="row">
                        <div class="col-12 mb-2">
                            <input type="text" name="agent_id" hidden="" value="11">
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
                             <label>Current Balance</label>
                             <input type="text" class="form-control" readonly id="current_bal" required=""  name="current_bal" placeholder="Enter Payment Amount">
                             <input type="text" class="form-control" readonly id="supplier_id" hidden required=""  name="supplier_id" placeholder="Enter Payment Amount">
                             <input type="text" name="request_person" value="flight_supplier" hidden>
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

@endsection

@section('scripts')

    <script>
        $(document).ready(function(){
          
        });
       function manage_balance(sup_id,wallet_balance){
            $('#manage_balance').modal('show');
            $('#current_bal').val(wallet_balance);
            $('#supplier_id').val(sup_id);
            
           console.log('supplier id is '+sup_id+' wallet Balance is '+wallet_balance);
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
    </script>

@stop

@section('slug')

@stop
