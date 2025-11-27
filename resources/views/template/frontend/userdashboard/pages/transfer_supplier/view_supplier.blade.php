@extends('template/frontend/userdashboard/layout/default')
@section('content')

    <style>
        .nav-link{
            color: #575757;
            font-size: 18px;
        }
    </style>

    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Tranfer Suppliers</a></li>
                                    <li class="breadcrumb-item active">View Tranfer Suppliers</li>
                                </ol>
                            </div>
                            <h4 class="page-title">View Tranfer Suppliers</h4>
                        </div>
                    </div>
                </div>
                
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
                    
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <div id="example_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                        
                                        <table class="table nowrap table-striped table-vcenter dataTable no-footer" id="my_Table" role="grid" aria-describedby="example_info">
                                            
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="text-align: center;">Sr No.</th>
                                                    <th style="text-align: center;">Customer Name</th>
                                                    <th style="text-align: center;">Balance Details</th>
                                                    <th style="text-align: center;">Supplier Details</th>
                                                    <th style="text-align: center;">Contact Details</th>
                                                    <th style="text-align: center;display:none">Wallet Trans</th>
                                                    <th style="width: 85px;display:none">Action</th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody style="text-align: center;">
                                                @foreach($data as $data_res)
                                                    <tr>
                                                        <td>{{$data_res->id ?? ""}}</td>
                                                        <td>
                                                            @if(isset($all_Users) && $all_Users != null && $all_Users != '')
                                                                @foreach($all_Users as $all_Users_value)
                                                                    @if($data_res->customer_id == $all_Users_value->id)
                                                                        <b>{{ $all_Users_value->name }}</b>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                        <td>
                                                            Wallet Balance: <b> {{ $data_res->wallat_balance }} </b>
                                                            <br>
                                                            Balance: <b> {{ $data_res->balance }} </b>
                                                            <br>
                                                            <!--Opening Balance: <b> {{ $data_res->opening_balance }} </b>-->
                                                        </td>
                                                        <td>
                                                            Supplier Name: <b>{{ $data_res->room_supplier_name ?? '' }}</b>
                                                            <br>
                                                            Company Email: <b>{{ $data_res->email ?? '' }}</b>
                                                        </td>
                                                        <td>
                                                            
                                                            <ul>
                                                                Contact Person Name: <b>{{ $data_res->contact_person_name ?? '' }}</b>
                                                                <br>
                                                                Contact Number: <b>{{ $data_res->phone_no ?? '' }}</b>
                                                                @if(isset($data_res->more_phone_no) && $data_res->more_phone_no != null && $data_res->more_phone_no != '')
                                                                    <br>
                                                                    <?php $more_phone_no  = json_decode($data_res->more_phone_no);  ?>
                                                                    @foreach($more_phone_no as $more_phone_noS)
                                                                        More Contact Number: <b>{{ $more_phone_noS ?? ''}}</b>
                                                                        <br>
                                                                    @endforeach
                                                                @endif
                                                            </ul>
                                                        </td>
                                                        <td style="display:none">
                                                            <a onclick="manage_balance('{{ $data_res->id }}',{{ $data_res->wallat_balance }})" class="mb-2" data-bs-toggle="modal" data-bs-target="#exampleModal"><span class="badge bg-success" style="font-size: 15px">Manage Wallet</span></a><br>
                                                        </td>
                                                        <td style="display:none">
                                                            <a href="{{URL::to('transfer_supplier_ledger/'.$data_res->id.'')}}" target="blank"><span class="badge bg-success" style="font-size: 15px">Ledger</span></a> 
                                                            <a href="{{URL::to('transfer_supplier_stats/'.$data_res->id.'')}}" target="blank"><span class="badge bg-success" style="font-size: 15px">Stats</span></a> 
                                                            <a href="{{URL::to('edit_transfer_suppliers')}}/{{$data_res->id}}"><span class="badge bg-success" style="font-size: 15px">Edit</span></a> 
                                                            <a href="{{URL::to('delete_transfer_suppliers')}}/{{$data_res->id}}"><span class="badge bg-success" style="font-size: 15px">Delete</span></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            
                                        </table>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </section>
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
                       
                        <label>Select Transcation Type</label>
                        <select class="form-control" id="" name="transtype">
                            <option value="Refund">Refund</option>
                            <option value="Deposit">Deposit</option>
                        </select>
                    </div>
                     <div class="col-6 mb-2">
                        <label>Payment Method</label>
                        <select class="form-control"  name="payment_method">
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Cash">Cash</option>
                            <option value="Card Payment">Card Payment</option>
                        </select>
                    </div>
                     <div class="col-6 mb-2">
                         <label>Current Balance</label>
                         <input type="text" class="form-control" readonly id="current_bal" required=""  name="current_bal" placeholder="Enter Payment Amount">
                         <input type="text" class="form-control" readonly id="supplier_id" hidden required=""  name="supplier_id" placeholder="Enter Payment Amount">
                         <input type="text" name="request_person" value="transfer_supplier" hidden>
                    </div>
                    <div class="col-6 mb-2">
                         <label>Payment Date</label>
                         <input type="date" class="form-control" required="" value="{{ date('Y-m-d') }}" name="payment_date" placeholder="Enter Payment Amount">
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
        function manage_balance(sup_id,wallet_balance){
            $('#manage_balance').modal('show');
            $('#current_bal').val(wallet_balance);
            $('#supplier_id').val(sup_id);
        }
    </script>
    
    <script>
        $(document).ready(function(){
            $('#my_Table').DataTable({
                pagingType: 'full_numbers',
            });
        });
    </script>
    
@stop

@section('slug')

@stop