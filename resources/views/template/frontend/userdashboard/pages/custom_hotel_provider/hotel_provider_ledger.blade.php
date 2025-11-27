@extends('template/frontend/userdashboard/layout/default')
@section('content')

<?php
use Carbon\Carbon;
$currency=Session::get('currency_symbol'); ?>

<div class="dashboard-content">
    
    <h3>Customer Ledger</h3>
  
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
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        <th>Balance</th>
                                        <th>Invoice No</th>
                                        <th>Total Price</th>
                                        <th>date</th>
                                    </tr>
                                </thead>
                                <tbody style="text-align: center;">
                                   @isset($customer_ledger)
                                        @foreach($customer_ledger as $data_res)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $data_res->payment }}</td>
                                                <td>{{ $data_res->received }}</td>
                                                <td>{{ $data_res->balance }}</td>
                                                <td>{{ $data_res->invoice_no }}</td>
                                                <td>{{ $data_res->total_price }}</td>
                                                <td>{{ date('d-m-Y',strtotime($data_res->created_at)) }}</td>
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
    
    
    
</div>


@endsection
@section('scripts')
@stop