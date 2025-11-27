@extends('template/frontend/userdashboard/layout/default')
@section('content')

<div class="dashboard-content">
    
    <h4 style="color:#a30000">View Booking Payment</h4>
    <!-- <form action="#" method="post" enctype="multipart/form-data"> -->
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
                <div class="col-md-4" style="padding: 15px;">
                    <label for="">Booking ID</label>
                    <input class="form-control" type="text" value="{{ $data->id }}">
                </div>
                <div class="col-md-4" style="padding: 15px;">
                    <label for="">Customer ID</label>
                    <input class="form-control" type="text" value="{{ $data->customer_id }}">
                </div>
                <div class="col-md-4" style="padding: 15px;">
                    <label for="">Customer Name</label>
                    <input class="form-control" type="text" value="{{ $decode_customer_data->name }} {{ $decode_customer_data->lname }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4" style="padding: 15px;">
                    <label for="">Package Title</label>
                    <input class="form-control" type="text" value="{{ $cart_details->tour_name }}">
                </div>
                <div class="col-md-4" style="padding: 15px;">
                    <label for="">Total Amount</label>
                    <input class="form-control" type="text" value="{{ $cart_details->price }}">
                </div>
                @if( $amount_paid == $cart_details->price )
                
                @else
                <div class="col-md-4" style="padding: 35px 0px 0px 17px;">
                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#recieve_payment_modal">Receieve Payment Activity</button>
                </div>
                @endif
            </div>
        </div>
    <!-- </form> -->
</div>

<div id="recieve_payment_modal" class="modal fade" tabindex="-1" style="display: none;" aria-hidden="true">
   <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="compose-header-modalLabel">Recieve Amount</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- <div class="modal-body"> -->
            <div class="p-1">
                <div class="modal-body px-3 pt-3 pb-0">
                    <form action="{{ URL::to('super_admin/view_booking_payment_recieve_Activity')}}/{{$cart_details->tour_id}}" method="post">
                        @csrf
                        
                        <input hidden readonly value="{{ $cart_details->id }}" name="package_id" class="form-control" type="text" id="package_id" required="">
                        
                        <div class="mb-2">
                          <label for="tourId" class="form-label">Tour ID</label>
                          <input readonly value="{{ $cart_details->tour_id }}" name="tourId" class="form-control" type="text" id="tourId" required="">
                        </div>


                        <div class="mb-2">
                          <label for="date" class="form-label">Date</label>
                          <input readonly value="{{ now()->format('Y-m-d') }}" name="date" class="form-control" type="date" id="date" required="">
                        </div>

                        <div class="mb-2">
                          <label for="customer_name" class="form-label">Customer Name</label>
                          <input readonly name="customer_name" value="{{ $decode_customer_data->name }} {{ $decode_customer_data->lname }}" class="form-control" type="text" id="customer_name" required="">
                        </div>

                        <div class="mb-2">
                          <label for="package_title" class="form-label">Package Title</label>
                          <input readonly name="package_title" value="{{ $cart_details->tour_name }}" class="form-control" type="text" id="package_title" required="" >
                        </div>


                        <div class="mb-2">
                            <label for="total_amount" class="form-label">Total Amount</label>
                            <input readonly name="total_amount" value="{{ $cart_details->price }}" class="form-control" type="text" id="total_amount" required="">
                        </div>

                        <div class="mb-2">
                            <label for="recieved_amount" class="form-label">Recieved Amount</label>
                            <input name="recieved_amount" class="form-control" type="text" id="recieved_amount" required="" placeholder="Recieved Amount">
                        </div>

                        <div class="mb-2">
                            <label for="remaining_amount" class="form-label">Remaining Amount</label>
                            <input readonly name="remaining_amount" class="form-control" type="text" id="remaining_amount" required="">
                        </div>

                        <div class="mb-2">
                            <label for="amount_paid" class="form-label">Amount Paid</label>
                            @if(isset($amount_paid))
                            <input readonly value="{{ $amount_paid }}" name="amount_paid" class="form-control" type="text" id="amount_paid" required="">
                            @else
                            <input readonly name="amount_paid" class="form-control" type="text" id="amount_paid" placeholder="Nothing Paid yet">
                            @endif
                        </div>

                        <div style="padding: 10px 0px 10px 0px;">
                            <button style="padding: 10px 30px 10px 30px;" type="submit" class="btn btn-primary" data-bs-dismiss="modal"><i class="mdi mdi-send me-1"></i>Recieve</button>
                            <button style="margin-left: 5px;padding: 10px 30px 10px 30px;" type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        </div>

                        <!-- <div class="mb-2 text-center">
                          <button class="btn btn-primary" type="submit">Recieve</button>
                        </div> -->
                    </form>
                </div>
            </div>
        </div>
   </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    
    $(document).ready(function () {

        total_amount     = $('#total_amount').val();
        amount_paid      = $('#amount_paid').val();
        remaining_amount = parseFloat(total_amount) - parseFloat(amount_paid);
        $('#remaining_amount').val(remaining_amount);

        $('#recieved_amount').on('change',function(){
            recieved_amount  = $(this).val();
            remaining_amount = $('#remaining_amount').val();
            remaining_amount_final = parseFloat(remaining_amount) - parseFloat(recieved_amount);
            $('#remaining_amount').val(remaining_amount_final);
            $('#amount_paid').val(recieved_amount);
        });

    });

</script>

@endsection