@extends('template/frontend/userdashboard/layout/default')
@section('content')

<?php $currency=Session::get('currency_symbol'); ?>

<div class="dashboard-content">
    
    <h3>Manage Agents</h3>
   
    <div class="offset-md-10 col-md-2 mb-2" style="display:none">
        <button type="button" class="btn btn-success float-left" data-bs-toggle="modal" data-bs-target="#add_new_account">Add New</button>
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
                                        <th>Balance</th>
                                        <th>Opening Balance</th>
                                        <th>Company Name</th>
                                        <th>Company Email</th>
                                        <th>Company Contact Number</th>
                                        <th>Company Address</th>
                                        <th>Agent Name</th>
                                        <th>Agent Email</th>
                                        <th>Agent Contact Number</th>
                                        <th>Agent Address</th>
                                        <th style="display:none">Options</th>
                                    </tr>
                                </thead>
                                <tbody style="text-align: center;">
                                    @if(isset($Agents_detail) && $Agents_detail !== null && $Agents_detail !== '')
                                        @php
                                            $x = 1;
                                        @endphp
                                        @foreach($Agents_detail as $value)
                                                <tr>
                                                    <td>{{ $x++ }}</td>
                                                    <td>
                                                        @if(isset($all_Users) && $all_Users != null && $all_Users != '')
                                                            @foreach($all_Users as $all_Users_value)
                                                                @if($value->customer_id == $all_Users_value->id)
                                                                    <b>{{ $all_Users_value->name }}</b>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                    <td>{{ $value->balance }}</td>
                                                    <td>{{ $value->opening_balance }}</td>
                                                    <td>{{ $value->company_name ?? '' }}</td>
                                                    <td>{{ $value->company_email ?? ''}}</td>
                                                    <td>{{ $value->company_contact_number ?? '' }}</td>
                                                    <td>{{ $value->company_address ?? ''}}</td>
                                                    <td>{{ $value->agent_Name }}</td>
                                                    <td>{{ $value->agent_Email }}</td>
                                                    <td>{{ $value->agent_contact_number ?? '' }}</td>
                                                    <td>{{ $value->agent_Address }}</td>
                                                    <td style="display:none">
                                                        <a href="{{ URL::to('agent_ledeger/'.$value->id.'') }}" class="btn btn-success btn-sm">View Led</a>
                                                        <a href="{{ URL::to('edit_Agents/'.$value->id.'') }}" class="btn btn-secondary btn-sm">Edit Details</a>
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
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                 <div> 

                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form action="{{URL::to('add_Agents')}}" method="post" enctype="multipart/form-data" style="padding-bottom: 40px">
                  @csrf
                    <div class="modal-body">
                          
                   
                      <div class="row">
                        <div class="col-md-12">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                                    <div class="row">
                                        
                                       <div class="col-md-4" style="padding: 15px;">
                                            <label for="">Company Name</label>
                                            <input type="text" id="company_name" name="company_name" class="form-control company_name" required>
                                        </div> 
                                        <div class="col-md-4" style="padding: 15px;">
                                            <label for="">Company Email</label>
                                            <input type="email" id="company_email" name="company_email" class="form-control company_email" required>
                                        </div> 
                                         <div class="col-md-4" style="padding: 15px;">
                                            <label for="">Company Contact Number</label>
                                            <input type="number" id="company_contact_number" name="company_contact_number" class="form-control company_contact_number" required>
                                        </div> 
                                        
                                        <div class="col-md-4" style="padding: 15px;">
                                            <label for="">Company Address</label>
                                            <input type="text" id="company_address" name="company_address" class="form-control company_address" required>
                                        </div> 
                                        
                                        
                                        
                                        
                                        
                                        
                                        <div class="col-md-4" style="padding: 15px;">
                                            <label for="">Agent Name</label>
                                            <input type="text" id="agent_Name" name="agent_Name" class="form-control agent_Name" required>
                                        </div>
                                        
                                        <div class="col-md-4" style="padding: 15px;">
                                            <label for="">Opening Balance</label>
                                            <input type="number" id="agent_Name" step=".01" name="opening_balance" class="form-control agent_Name" required>
                                        </div>
                                        
                                        
                                        
                                        <div class="col-md-4" style="padding: 15px;">
                                            <label for="">Agent Email</label>
                                            <input type="email" id="agent_Email" name="agent_Email" class="form-control agent_Email" required>
                                        </div>
                                        
                                        <div class="col-md-4" style="padding: 15px;">
                                            <label for="">Agent Contact Number</label>
                                            <input type="number" id="agent_contact_number" name="agent_contact_number" class="form-control agent_Email" required>
                                        </div>
                                        
                                        <div class="col-md-4" style="padding: 15px;">
                                            <label for="">Agent Address</label>
                                            <input type="text" id="agent_Address" name="agent_Address" class="form-control agent_Address" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
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