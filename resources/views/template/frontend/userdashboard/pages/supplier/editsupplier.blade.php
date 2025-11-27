@extends('template/frontend/userdashboard/layout/default')
@section('content')
<?php $currency=Session::get('currency_symbol'); ?>

<div class="dashboard-content">
    
    <h4 style="color:#a30000">Edit Supplier</h4>
    <form action="{{ url('updatesupplier') }}/{{$supplier->id}}" method="post" enctype="multipart/form-data">
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
  
                <!-- Main Tab Contant Start -->
                <div class="tab-content">
                    <!-- General Tab Start -->
                  
                        <div class="row">
                            <div class="col-md-4" style="padding: 10px;">
                                
                                <label for="">Company Name</label>
                                <input id="property_name" type="text" class="form-control @error('property_name') is-invalid @enderror" name="companyname" value="{{ $supplier->companyname ?? "N/A" }}" autocomplete="companyname" autofocus>

                                @error('property_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="col-md-4" style="padding: 10px;">
                                <label for="">Company Address</label>
                                <input id="property_img" type="text" class="form-control @error('property_img') is-invalid @enderror" name="Companyaddress" value="{{ $supplier->Companyaddress ?? "N/A" }}" autocomplete="Companyaddress" autofocus>

                                @error('property_img')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div> 
                            
                            <div class="col-md-4" style="padding: 10px;">
                                <label for="">Company Email</label>
                                <input name="companyemail" type="email" class="form-control @error('property_desc') is-invalid @enderror"  value="{{ $supplier->companyemail ?? "N/A" }}"  autocomplete="companyemail" placeholder="Enter Company Email">

                                @error('property_desc')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="col-md-4" style="padding: 10px;">
                                <label for="">Contact Person Name</label>
                                <input id="property_google_map" type="text" class="form-control @error('property_google_map') is-invalid @enderror" name="contactpersonname" value="{{ $supplier->contactpersonname ?? "N/A" }}" autocomplete="contactpersonname" autofocus>

                                @error('property_google_map')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="col-md-4" style="padding: 10px;">
                                <label for="">Contact Person Email</label>
                                <input id="latitude" type="text" class="form-control @error('latitude') is-invalid @enderror" name="contactpersonemail" value="{{ $supplier->contactpersonemail ?? "N/A" }}" autocomplete="contactpersonemail" autofocus>

                                @error('latitude')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="col-md-4" style="padding: 10px;">
                                <label for="">Person Contact No </label>
                                <input id="longitude" type="text" class="form-control @error('longitude') is-invalid @enderror" name="personcontactno" value="{{ $supplier->personcontactno ?? "N/A" }}" autocomplete="personcontactno" autofocus>

                                @error('longitude')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="col-md-4" style="padding: 10px; display: none;">
                                <label for="">Total Seats</label>
                                <input id="totalseats" type="number" class="form-control @error('totalseats') is-invalid @enderror" name="totalseats" value="{{ old('totalseats') }}"  autofocus>

                                @error('totalseats')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                  
                    
                  
                  
                 
                </div>
                <!-- Main Tab Contant End -->
            </div>
            
        </div>

        <div class="row">
            <div class="col-md-12 text-right mt-3">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
</div>


@endsection
@section('scripts')

@stop