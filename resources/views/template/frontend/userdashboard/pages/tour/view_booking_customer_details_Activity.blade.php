@extends('template/frontend/userdashboard/layout/default')
@section('content')

<div class="dashboard-content">
    
    <h4 style="color:#a30000">Customer Details</h4>
    <form action="#" method="post" enctype="multipart/form-data">
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
            @foreach($passenger_detail as $value)
                <div class="row">
                    <div class="col-md-6" style="padding: 15px;">
                        <label for="">Booking ID</label>
                        <input class="form-control" type="text" value="{{ $data->id ?? ''}}">
                    </div>
                    <div class="col-md-6" style="padding: 15px;">
                        <label for="">Customer first Name</label>
                        <input class="form-control" type="text" value="{{ $value->name ?? ''}}">
                    </div>
                </div>
                <div class="row">                
                    <div class="col-md-6" style="padding: 15px;">
                        <label for="">Last Name</label>
                        <input class="form-control" type="text" value="{{ $value->lname ?? ''}}">
                    </div>
                    <div class="col-md-6" style="padding: 15px;">
                        <label for="">Email</label>
                        <input class="form-control" type="text" value="{{ $value->email ?? ''}}">
                    </div>
                </div>
                <div class="row">                
                    <div class="col-md-6" style="padding: 15px;">
                        <label for="">Mobile</label>
                        <input class="form-control" type="text" value="{{ $value->phone ?? ''}}">
                    </div>
                    <div class="col-md-6" style="padding: 15px;">
                        <label for="">Gender</label>
                        <input class="form-control" type="text" value="{{ $value->gender ?? '' }}">
                    </div>
                </div>
                <div class="row">                
                    <div class="col-md-6" style="padding: 15px;">
                        <label for="">Country</label>
                        <input class="form-control" type="text" value="{{ $value->country ?? '' }}">
                    </div>
                    <div class="col-md-6" style="padding: 15px;">
                        <label for="">Country Code</label>
                        <input class="form-control" type="text" value="{{ $value->country_code ?? '' }}">
                    </div>
                </div>
                <div class="row">                
                    <div class="col-md-6" style="padding: 15px;">
                        <label for="">Address</label>
                        <input class="form-control" type="text" value="{{ $value->address  ?? '' }}">
                    </div>
                    <div class="col-md-6" style="padding: 15px;">
                        <label for="">Passport No</label>
                        <input class="form-control" type="text" value="{{ $value->passport_no  ?? '' }}">
                    </div>
                </div>
                <div class="row">                
                    <div class="col-md-6" style="padding: 15px;">
                        <label for="">Nationality</label>
                        <input class="form-control" type="text" value="{{ $value->Nationality  ?? '' }}">
                    </div>
                    <div class="col-md-6" style="padding: 15px;">
                        <label for="">Passenger Type</label>
                        <input class="form-control" type="text" value="{{ $value->passengerType  ?? '' }}">
                    </div>
                </div>
            @endforeach
        </div>

        <!-- <div class="row">
            <div class="col-md-12 text-right mt-3">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div> -->
    </form>
</div>

@endsection