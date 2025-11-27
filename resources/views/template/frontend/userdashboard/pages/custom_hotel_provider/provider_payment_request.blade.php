@extends('template/frontend/userdashboard/layout/default')
@section('content')

<?php
use Carbon\Carbon;
$currency=Session::get('currency_symbol'); ?>

<div class="dashboard-content">
    
    <h3>Customer Payment Requests</h3>
  
        <div class="row">
            
            <div class="col-md-12 mb-4">
                @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" style="background-color:#d4edda;" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

                @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" style="background-color:#f5cfcf;" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-4">
                <button type="button" class="btn btn-success float-left" data-bs-toggle="modal" data-bs-target="#add_new_account">Add New</button>
            </div>
        </div>
    
   
    
    <div class="dashboard-content">
        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <div class="dashboard-list-box dash-list margin-top-0">
                    <div class="row">
                        <div class="col-md-12">
                             <table id="scroll-horizontal-datatable"  class="display nowrap table  table-bordered" style="width:100%;">
                                <thead class="theme-bg-clr">
                                    <tr>
                                        <th>Sr</th>
                                        <th>Provider</th>
                                        <th>Payment Date</th>
                                        <th>Payment Amount</th>
                                        <th>Payment Method</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody style="text-align: center;">
                                   @isset($payment_request)
                                        @foreach($payment_request as $data_res)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $data_res->provider_name }}</td>
                                                <td>{{ date('d-m-Y',strtotime($data_res->payment_date)) }}</td>
                                                <td>{{ $data_res->payment_amount }}</td>
                                                <td>{{ $data_res->payment_method }}</td>
                                                <td>{{ $data_res->status }}</td>
                                            </tr>
                                        @endforeach
                                   @endisset
                                </tbody>
                            </table>
                        </div>
                 
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="add_new_account" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                 <div> 
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Send Payment Request</h1>
                    
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form action="{{ URL::to('super_admin/provider_payment_request_sub') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                          
                   
                      <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Select Provider</label>
                            <select class="form-control" name="provider_id" required>
                                <option value="">Select One</option>
                                @isset($custom_hotel_provider)
                                    @foreach($custom_hotel_provider as $prov_res)
                                        <option value="{{ $prov_res->cust_provider_id }}">{{ $prov_res->company_name }} / {{ $prov_res->provider_name }}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                       
                        <div class="col-md-6 mb-2">
                            <label>Payment date</label>
                            <input type="date" name="payment_date" required="" class="form-control">
                        </div>
                        
                         <div class="col-md-6 mb-2">
                            <label>Payment Amount</label>
                            <input type="text" name="payment_amount" required="" class="form-control">
                        </div>
                        
                        <div class="col-md-6 mb-2">
                            <label>Payment Method</label>
                            <select name="payment_method" class="form-control">
                                <option>Cash</option>
                                <option>Bank Transfer</option>
                            </select>
                        </div>
                         <div class="col-md-12 mb-2">
                            <label>Transcation Id</label>
                            <input type="text" name="transcation_id" class="form-control">
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


@endsection
@section('scripts')
@stop