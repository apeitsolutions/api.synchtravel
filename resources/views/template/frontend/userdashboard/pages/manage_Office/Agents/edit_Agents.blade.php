@extends('template/frontend/userdashboard/layout/default')
@section('content')

<?php $currency=Session::get('currency_symbol'); ?>

<div class="dashboard-content">
    <h4 style="color:#a30000">Update Agent Details</h4>
        <form action="{{URL::to('update_Agents')}}/{{ $edit_Agents->id }}" method="post" enctype="multipart/form-data">
            @csrf
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
                
                <div class="col-md-12">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation" style="width: 280px;text-align: center;">
                            <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">Update Agent Details</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                            <div class="row">
                                
                                <div class="col-md-4" style="padding: 15px;">
                                <label for="">Company Name</label>
                                <input type="text" value="{{ $edit_Agents->company_name ?? ''}}" id="company_name" name="company_name" class="form-control company_name" required>
                            </div> 
                            <div class="col-md-4" style="padding: 15px;">
                                <label for="">Company Email</label>
                                <input type="email" value="{{ $edit_Agents->company_email ?? ''}}" id="company_email" name="company_email" class="form-control company_email" required>
                            </div> 
                             <div class="col-md-4" style="padding: 15px;">
                                <label for="">Company Contact Number</label>
                                <input type="number" value="{{ $edit_Agents->company_contact_number ?? ''}}" id="company_contact_number" name="company_contact_number" class="form-control company_contact_number" required>
                            </div> 
                            
                            <div class="col-md-4" style="padding: 15px;">
                                <label for="">Company Address</label>
                                <input type="text" id="company_address" value="{{ $edit_Agents->company_address ?? ''}}" name="company_address" class="form-control company_address" required>
                            </div> 
                            
                            
                            
                            
                            
                            
                            <div class="col-md-4" style="padding: 15px;">
                                <label for="">Agent Name</label>
                                <input type="text" id="agent_Name" value="{{ $edit_Agents->agent_Name ?? ''}}" name="agent_Name" class="form-control agent_Name" required>
                            </div>
                            
                            <div class="col-md-4" style="padding: 15px;">
                                <label for="">Agent Email</label>
                                <input type="email" id="agent_Email" value="{{ $edit_Agents->agent_Email ?? ''}}" name="agent_Email" class="form-control agent_Email" required>
                            </div>
                            
                            <div class="col-md-4" style="padding: 15px;">
                                <label for="">Agnet Contact Number</label>
                                <input type="number" id="agent_contact_number" value="{{ $edit_Agents->agent_contact_number ?? ''}}" name="agent_contact_number" class="form-control agent_Email" required>
                            </div>
                            
                            <div class="col-md-4" style="padding: 15px;">
                                <label for="">Agent Address</label>
                                <input type="text" id="agent_Address" value="{{ $edit_Agents->agent_Address ?? ''}}" name="agent_Address" class="form-control agent_Address" required>
                            </div>
                                
                        
                                    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="row">
                <div class="col-md-12 text-right mt-3">
                    <button type="submit" class="btn btn-primary">Update Agent</button>
                </div>
            </div>
        </form>
</div>

@endsection
@section('scripts')
@stop



