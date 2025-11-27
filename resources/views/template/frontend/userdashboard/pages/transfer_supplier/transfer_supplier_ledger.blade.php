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
        <section class="content" style="">
            <!-- Start Content-->
            <div class="container-fluid">
              <!-- start page title -->
              <div class="row">
                  <div class="col-12">
                      <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Tour Bookings</a></li>
                                <li class="breadcrumb-item active">financial_statement</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Transfer Supplier Ledger</h4>
                        <div style="display: flex;justify-content: space-between;">
                            <h6 class="" style="font-size: 1rem;">Supplier Name: {{ $supplier_Pers_details->room_supplier_name }}</h6>
                        </div>
                      </div>
                  </div>
              </div>
              <!-- end page title --> 
    
                <div class="row">
                    <div class="col-12">
                        <!--<div class="card">-->
                            <!--<div class="card-body">-->
                                @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert" id="alert_hide">
                                      <strong> {{ session('error') }}</strong>
                                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif
                                
                                <div class="row mb-2">
                                    <div class="col-sm-5">
                                      <!-- <a href="javascript:void(0);" class="btn btn-danger mb-2"><i class="mdi mdi-plus-circle me-2"></i> View Tentative Bookings</a> -->
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="text-sm-end">
                                        <!-- <button type="button" class="btn btn-success mb-2 me-1"><i class="mdi mdi-cog-outline"></i></button>
                                        <button type="button" class="btn btn-light mb-2 me-1">Import</button>
                                        <butt on type="button" class="btn btn-light mb-2">Export</button>-->
                                      </div>
                                    </div><!-- end col-->
                                </div>
        
                                <!--<div class="table-responsive">-->
                                    <!--<div id="example_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer"> -->
                                        <div class="row">
                                           
 <table id="scroll-horizontal-datatable1"  class="display nowrap table  table-bordered" style="width:100%;">
                                    <thead class="theme-bg-clr">
                                       
                                        <tr>
                                            <th style="text-align: center;" width="5%">Sr</th>
                                            <th style="text-align: center;" width="30%">Description</th>
                                            <th style="text-align: center;">Debit</th>
                                            <th style="text-align: center;">Credit</th>
                                            <th style="text-align: center;">Balance</th>
                                            <th style="text-align: center;">Date</th>
                                        </tr>
                                    </thead>
                                        
                                        <tbody style="text-align: center;" id="tbody_all_data_acc">
                                                        <tr role="row">
                                                            <td>Opening Balance</td>
                                                            <td></td>
                                                            <td></td>
                                                            <td>{{ $currency }} {{ $supplier_Pers_details->opening_balance }}</td>
                                                            <td></td>
                                                        </tr>
                                                        
                                                        @if(isset($ledger_data))
                                                            
                                                            @foreach($ledger_data as $value)
                                                             <tr role="row" @if(isset($value->received)) style="background-color:#06b1a1;color:white;" @endif>
                                                                <td>{{  $loop->iteration }}</td>
                                                                
                                                                <td><p style="margin: 0px;">
                                                                    <b>
                                                                    <?php 
                                                                                if(isset($value->deposit_id)){
                                                                                    echo "Deposit "."<br>"."Deposit Id :".$value->deposit_id;
                                                                                }
                                                                                
                                                                                 if(isset($value->refund_id)){
                                                                                     echo "Refund "."<br>"."Refund Id :".$value->refund_id;
                                                                                }
                                                                                
                                                                                 if(isset($value->invoice_no)){
                                                                                     
                                                                                     $remarks = '- New';
                                                                                     if($value->remarks == 'Invoice Update'){
                                                                                         $remarks = '- Update';
                                                                                     }
                                                                                    echo "Invoice $remarks "."<br>";
                                                                                    
                                                                                    ?>
                                                                                    <a href="{{ URL::to('invoice_Agent/'.$value->invoice_no.'') }}" target="blank"><?php echo "Invoice Id :".$value->invoice_no; ?>
                                                                                        <img height="15px" width="15px" src="{{ asset('public/invoice_icon.png') }}">
                                                                                    </a>
                                                                                    
                                                                                    <?php
                                                                                    
                                                                                }
                                                                                
                                                                                 if(isset($value->package_id)){
                                                                                     $remarks = '- New';
                                                                                     if($value->remarks == 'Package Updated'){
                                                                                         $remarks = '- Update';
                                                                                     }
                                                                                     
                                                                                    echo "Package $remarks "."<br>";
                                                                                     ?>
                                                                                    <a href="#" target="blank"><?php echo "Package Id :".$value->package_id; ?>
                                                                                        <img height="15px" width="15px" src="{{ asset('public/invoice_icon.png') }}">
                                                                                    </a>
                                                                                    
                                                                                    <?php
                                                                                    
                                                                                }
                                                                         
                                                                                if($value->remarks !== 'Invoice Updated'){
                                                                                    if(!isset($value->package_id)){
                                                                                        echo $value->remarks;
                                                                                        
                                                                                    }
                                                                                }
                                                                                
                                                                                if($value->remarks == '' && $value->invoice_no !== '' && $value->package_invoice_no == '' && $value->payment_id == '' && $value->received_id == ''){
                                                                                    echo "New Seats Added";
                                                                                }
                                                                            
                                                                            ?>
                                                                            </b>
                                                                </p>
                                                                </td>
                                                                <td>
                                                                    @isset($value->received)
                                                                    {{ $currency }} {{ $value->received }}
                                                                     @endisset
                                                                </td>
                                                                <td>
                                                                    @isset($value->payment)
                                                                    {{ $currency }} {{  $value->payment }}
                                                                    @endisset
                                                                </td>
                                                                <td>{{ $currency }} {{ $value->balance }}</td>
                                                                <td>{{ date('d-m-Y',strtotime($value->date)) }}</td>
                                                            </tr>
                                                            @endforeach
                                                       @endif
                                        </tbody>
                                    
                                    </table>
                                           
                                        </div>
                                    <!--</div> -->
                                <!--</div>-->
                            <!--</div>-->
                        <!--</div>-->
                    </div>
                </div>           
            </div> 
            <!-- container -->
        </section>
    </div>

     
    
    <div id="payment_edit" class="modal fade" tabindex="-1" aria-modal="true" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header modal-colored-header bg-primary">
            <h4 class="modal-title" id="compose-header-modalLabel">Recieve Amount</h4>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <!-- <div class="modal-body"> -->
         <div class="p-1">
            <div class="modal-body px-3 pt-3 pb-0">
               <form action="{{ URL::to('super_admin/update_customer_payment') }}" method="post">
                  @csrf                       
                  <input hidden="" readonly="" value="350" name="package_id" class="form-control" type="text" id="package_id" required="">
                  <div class="mb-2">
                     <label for="tourId" class="form-label">Tour ID</label>
                     <input readonly="" value="" name="tourId" class="form-control" type="text" id="tourId" required="">
                     <input  value="" name="payment_id" class="form-control" type="text" id="payment_id" required="">
                  </div>
                  <div class="mb-2">
                     <label for="date" class="form-label">Date</label>
                     <input value="" name="date" class="form-control" type="date" id="date" required="">
                  </div>
                  <div class="mb-2">
                     <label for="customer_name" class="form-label">Customer Name</label>
                     <input readonly="" name="customer_name" value="" class="form-control" type="text" id="customer_name" required="">
                  </div>
                  <div class="mb-2">
                     <label for="package_title" class="form-label">Package Title</label>
                     <input readonly="" name="package_title" value="" class="form-control" type="text" id="package_title" required="">
                  </div>
                  <div class="mb-2">
                     <label for="total_amount" class="form-label">Total Amount</label>
                     <input readonly="" name="total_amount" value="" class="form-control" type="text" id="total_amount" required="">
                  </div>
                  <!--<div class="mb-2">-->
                  <!--   <label for="recieved_amount" class="form-label">Recieved Amount</label>-->
                  <!--   <input name="recieved_amount" class="form-control" type="text" id="recieved_amount" required="" placeholder="Recieved Amount">-->
                  <!--</div>-->
                  <!--<div class="mb-2">-->
                  <!--   <label for="remaining_amount" class="form-label">Remaining Amount</label>-->
                  <!--   <input readonly="" name="remaining_amount" class="form-control" type="text" id="remaining_amount" required="">-->
                  <!--</div>-->
                  <div class="mb-2">
                     <label for="amount_paid" class="form-label">Amount Paid</label>
                     <input  value="" name="amount_paid" class="form-control" type="text" id="amount_paid" required="">
                  </div>
                  <!--<div style="padding: 10px 0px 10px 0px;">-->
                  <!--   <button style="padding: 10px 30px 10px 30px;" type="submit" class="btn btn-primary" data-bs-dismiss="modal"><i class="mdi mdi-send me-1"></i>Recieve</button>-->
                  <!--   <button style="margin-left: 5px;padding: 10px 30px 10px 30px;" type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>-->
                  <!--</div>-->
                   <div class="mb-2 text-center">
                     <button class="btn btn-primary" type="submit">Update</button>
                     </div> 
               </form>
            </div>
         </div>
      </div>
   </div>
</div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    


@endsection


@section('scripts')
    <script>
    
    $('#scroll-horizontal-datatable1').DataTable({
                "ordering": false
            });
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
