@extends('template/frontend/userdashboard/layout/default')
@section('content')

<?php $currency=Session::get('currency_symbol'); ?>

<div class="dashboard-content">
    
    <h3>Customers List</h3>
  
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
            
            <div class="offset-md-10 col-md-2" style="display:none">
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
                                        <!--<th>Agent Id</th>-->
                                        <th>Customer Name</th>
                                        <th>Name</th>
                                        <th>E mail</th>
                                        <th>Opening Balance</th>
                                        <th>Balance</th>
                                        <th style="display:none">Options</th>
                                    </tr>
                                </thead>
                                <tbody style="text-align: center;">
                                    @if(isset($customer_detail) && $customer_detail !== null && $customer_detail !== '')
                                        @php
                                            $x = 1;
                                        @endphp
                                        @foreach($customer_detail as $account_res)
                                                <tr>
                                                    <td>{{ $x++ }}</td>
                                                    <td>
                                                        @if(isset($all_Users) && $all_Users != null && $all_Users != '')
                                                            @foreach($all_Users as $all_Users_value)
                                                                @if($account_res->customer_id == $all_Users_value->id)
                                                                    <b>{{ $all_Users_value->name }}</b>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                    <td>{{ $account_res->name ?? '' }}</td>
                                                    <td>{{ $account_res->email ?? '' }}</td>
                                                    <td>{{ $account_res->opening_balance ?? '' }}</td>
                                                    <td>{{ $account_res->balance }}</td>
                                                    <td style="display:none">
                                                       <a href="{{ URL::to('customer_ledeger/'.$account_res->id.'') }}" class="btn btn-success btn-sm">View Ledger</a>
                                                    </td>
                                                </tr>
                                        @endforeach
                                    @endif
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Customer</h1>
                    
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form action="{{ URL::to('submit_customer') }}" method="post" enctype="multipart/form-data">
                  @csrf
                    <div class="modal-body">
                          
                   
                      <div class="row">
                        <div class="col-12 mb-2">
                            
                            <label>Customer Name</label>
                            <input type="text" name="cust_name" required class="form-control">
                        </div>
                       
                        
                        
                        <div class="col-md-6 mb-2">
                            <label>Opening Balance</label>
                            <input type="number" step=".01" required name="opening_balance" class="form-control">
                        </div>
                        
                        <div class="col-md-6 mb-2">
                            <label>E mail</label>
                            <input type="email" name="email" required class="form-control">
                        </div>
                        
                        <div class="col-md-6 mb-2">
                            <label>Contact number</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        
                        <div class="col-md-6 mb-2">
                            <label>Whatsapp</label>
                            <input type="text" name="whatsapp" class="form-control">
                        </div>
                        
                        
                        <div class="col-md-6 mb-2">
                            <label>Gender</label>
                        </div>
                        
                        <div class="col-md-3 mb-2">
                                <div class="form-check">
                                  <input class="form-check-input" type="radio" name="gender" value="male" id="flexRadioDefault1" checked>
                                  <label class="form-check-label" for="flexRadioDefault1">
                                    Male
                                  </label>
                                </div>
                        </div>
                        
                        <div class="col-md-3 mb-2">
                                <div class="form-check">
                                  <input class="form-check-input" type="radio" name="gender" value="female" id="flexRadioDefault2" >
                                  <label class="form-check-label" for="flexRadioDefault2">
                                    Female
                                  </label>
                                </div>
                        </div>


                         <div class="col-md-5 mb-2">
                            <label>Country </label>
                            <select class="form-control select2" id="country" data-toggle="select2" onchange="get_country_cites()" name="country">
                                @isset($countires)
                                    @foreach($countires as $country_res)
                                    <option value='{{ json_encode($country_res) }}'>{{ $country_res->name }}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="col-md-5 mb-2">
                            <label>City </label>
                            <select class="form-control select2" data-toggle="select2" name="city" id="city">
                                
                            </select>
                        </div>
                        
                        <div class="col-md-2 mb-2">
                            <label>Postcode</label>
                            <input type="text" name="post_code" class="form-control">
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

    <script>
        function get_country_cites(){
          var country = $('#country').val();
           country = JSON.parse(country);
                    $.ajax({
                        url: '{{ URL::to("country_cites1") }}',
                        type: 'POST',
                        data: {
                          _token: '{{ CSRF_TOKEN() }}',
                          "id": country['id'],
                      
                        },
                        success:function(data) {
                            console.log(data);
                            $('#city').html(data['options']);
                        },
                    });
          console.log('cousntri '+country);
        }
        
        get_country_cites();
         $('#country').select2({
                dropdownParent: $('#add_new_account')
         });
         
         $('#city').select2({
                dropdownParent: $('#add_new_account')
         });
    </script>
@stop