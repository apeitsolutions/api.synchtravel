@extends('template/frontend/userdashboard/layout/default')
@section('content')
<?php
// dd($visa_supplier);
?>


<div id="payable_payment_modal" class="modal fade" tabindex="-1" style="display: none;" aria-hidden="true">
   <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="compose-header-modalLabel">Payble Amount</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
             <div class="modal-body visa_payment_html"> 
             </div>
           
        </div>
   </div>
</div><!-- /.modal -->

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
                                       
                                        <th style="text-align: center;">Suplier Name</th>
                                        <th style="text-align: center;">Suplier Visa</th>
                                        <th style="text-align: center;">Availability From</th>
                                        <th style="text-align: center;">Availability To</th>
                                        <th style="text-align: center;">Visa Qty</th>
                                        <th style="text-align: center;">Visa Price</th>
                                        <th style="text-align: center;">Visa Price</th>
                                       
                                        <th style="width: 85px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody style="text-align: center;">
                                    @foreach($visa_supplier as $visa_suppliers)
                                    <?php
                                    //  dd($visa_suppliers);
                                    $from_to_conversion = $visa_suppliers->currency_conversion;
                                    
                                    $from_to_conversion = explode(" ",$from_to_conversion);
                                   
                                    ?>
                            
                                    <tr>
                                    <td>{{$visa_suppliers->visa_id ?? ""}}</td>
                                    <td>{{$visa_suppliers->visa_supplier_name ?? "" }}</td>
                                    <td>{{$visa_suppliers->other_visa_type ?? ""}}</td>
                                    <td>{{$visa_suppliers->availability_from ?? ""}}</td>
                                    <td>{{$visa_suppliers->availability_to ?? ""}}</td>
                                    <td>{{$visa_suppliers->visa_qty ?? ""}}</td>
                                    <td>{{ $visa_suppliers->purchase_currency }} {{$visa_suppliers->visa_price ?? ""}}</td>
                                    <td>{{ $visa_suppliers->sale_currency }} {{$visa_suppliers->visa_converted_price ?? ""}}</td>
                                 
                                    <td>
                                      
                                    
                                        <div class="dropdown card-widgets">
                                                            <a style="float: right;" href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="dripicons-dots-3" style="color: #6e6060;"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end" style="">
                                                                <a href="{{URL::to('edit_visa_supplier')}}/{{$visa_suppliers->visa_id ?? ""}}" class="dropdown-item"><i class="mdi mdi-account-edit me-1"></i>Edit
                                                                </a>
                                                                <a href="{{URL::to('delete_visa_avail')}}/{{$visa_suppliers->visa_id ?? ""}}" class="dropdown-item" onclick="return confirm('Are you sure you want to delete?');">
                                                                    <i class="mdi mdi-check-circle me-1"></i>
                                                                    Delete
                                                                </a>
                                                                
                                                                <a href="javascript:void(0);" class="dropdown-item payment_history_btn_of_flight" payment_detail_attr='' data-bs-toggle="modal" data-bs-target="#recieve_payment_history_modal" style="font-size: 15px" > <i class="fa-solid fa-list" style="margin-right: 3px;"></i>Payment History
                                                                </a>
                                                                
                                                        </div>
                                        <!--<a href="{{URL::to('edit_visa_supplier')}}/{{$visa_suppliers->visa_id ?? ""}}" class="mb-2"><span class="badge bg-success " style="font-size: 15px">Edit</span></a> <br>-->
                                        <!--<a href="{{URL::to('delete_visa_suppliers')}}/{{$visa_suppliers->visa_id ?? ""}}" class="mb-2"><span class="badge bg-success" style="font-size: 15px">Delete</span></a><br>-->
                                        
                                    </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div> 
                    
                </div>
                
        

@endsection

@section('scripts')

  

    <script>
   
    </script>

@stop

@section('slug')

@stop
