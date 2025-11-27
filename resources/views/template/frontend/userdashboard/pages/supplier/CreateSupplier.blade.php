@extends('template/frontend/userdashboard/layout/default')
@section('content')


<div class="dashboard-content">
    
    <h4>Create Supplier</h4>
    <form action="{{ url('addsupplier') }}" method="post" enctype="multipart/form-data">
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
                            
                            <div class="col-md-4 mt-2" style="padding: 10px;">
                                
                                <label for="">Company Name</label>
                                <input id="companyname" type="text" class="form-control @error('property_name') is-invalid @enderror" name="companyname" value="{{ old('companyname') }}" placeholder="Enter Company Name" autofocus>

                                @error('companyname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mt-2" style="padding: 10px;">
                                <label for="">Company Address</label>
                                <input id="Companyaddress" type="text" class="form-control @error('Companyaddress') is-invalid @enderror" name="Companyaddress" value="{{ old('Companyaddress') }}" placeholder="Enter Company Address" autofocus>

                                @error('Companyaddress')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div> 
                            <div class="col-md-4 mt-2" style="padding: 10px;">
                                <label for="">Opening Balance</label>
                                <input id="opening_balance" type="number" step=".01" class="form-control @error('opening_balance') is-invalid @enderror" name="opening_balance" value="{{ old('opening_balance') }}" placeholder="Opening Balance" autofocus>

                                @error('opening_balance')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div> 
                            
                            
                            
                            <div class="col-md-4 mt-2" style="padding: 10px;">
                                <label for="">Company Email</label>
                                <input name="companyemail" type="email" class="form-control @error('companyemail') is-invalid @enderror"  value="{{ old('companyemail') }}"  autocomplete="companyemail" placeholder="Enter Company Email">

                                @error('companyemail')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mt-2" style="padding: 10px;">
                                <label for="">Contact Person Name</label>
                                <input id="contactpersonname" type="text" class="form-control @error('contactpersonname') is-invalid @enderror" name="contactpersonname" value="{{ old('contactpersonname') }}" placeholder="Enter Contact Person Name" autofocus>

                                @error('contactpersonname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mt-2" style="padding: 10px;">
                                <label for="">Contact Person Email</label>
                                <input id="contactpersonemail" type="text" class="form-control @error('contactpersonemail') is-invalid @enderror" name="contactpersonemail" value="{{ old('contactpersonemail') }}" placeholder="Enter Contact Person Email" autofocus>

                                @error('contactpersonemail')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mt-2" style="padding: 10px;">
                                <label for="">Person Contact No </label>
                                <input id="personcontactno" type="text" class="form-control @error('personcontactno') is-invalid @enderror" name="personcontactno" value="{{ old('personcontactno') }}" placeholder="Enter Contact No"  autofocus>

                                @error('personcontactno')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mt-2" style="display: none;">
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



