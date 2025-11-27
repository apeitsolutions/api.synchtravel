@extends('template/frontend/userdashboard/layout/default')
@section('content')

<div class="dashboard-content">
    
    <h4 style="color:#a30000">View Bookings</h4>
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
            <div class="row">
                <div class="col-md-6" style="padding: 15px;">
                    <label for="">Booking ID</label>
                    <input class="form-control" type="text" value="{{ $data->id }}">
                </div>
                <div class="col-md-6" style="padding: 15px;">
                    <label for="">Customer Name</label>
                    <input class="form-control" type="text" value="{{ $decode_customer_data->name }} {{ $decode_customer_data->lname }}">
                </div>
            </div>
            <div class="row">              
                <div class="col-md-6" style="padding: 15px;">
                    <label for="">Tour Title</label>
                    <input class="form-control" type="text" value="{{ $cart_details->tour_name }}">
                </div>
                <div class="col-md-6" style="padding: 15px;">
                    <label for="">Adults</label>
                    <input class="form-control" type="text" value="{{ $cart_details->adults }}">
                </div>
            </div>

            <div class="row">              
                <div class="col-md-6" style="padding: 15px;">
                    <label for="">Childrens</label>
                    <input class="form-control" type="text" value="{{ $cart_details->childs }}">
                </div>
                <div class="col-md-6" style="padding: 15px;">
                    <label for="">Total Amount</label>
                    <input class="form-control" type="text" value="{{ $cart_details->price }}">
                </div>
            </div>

            <div class="row">             
                <div class="col-md-6" style="padding: 15px;">
                    <label for="">Selected Sharing</label>
                    <input class="form-control" type="text" value="{{ $cart_details->sharingSelect }}">
                </div>
                <div class="col-md-6" style="padding: 15px;">
                    <label for="">Image</label>
                    <input class="form-control" type="text" value="{{ $cart_details->image }}">
                </div>
            </div>
        </div>

        <!-- <div class="row">
            <div class="col-md-12 text-right mt-3">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div> -->
    </form>
</div>

@endsection