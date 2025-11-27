@extends('template/frontend/userdashboard/layout/default')
@section('content')

<?php
    $currency       = Session::get('currency_symbol');
    $total_Payable  = $data[0]->total_sale_price_all_Services;
    $amount_Paid    = $data[0]->total_paid_amount;

    
?>

<div class="dashboard-content">
    
    <h4 style="">View Invoice Payment</h4>
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
                    <label for="">Customer ID</label>
                    <input class="form-control" type="text" value="{{ $data[0]->customer_id }}" readonly>
                </div>
                <div class="col-md-4" style="padding: 15px;">
                    <label for="">Passenger Name</label>
                    <input class="form-control" type="text" value="{{ $data[0]->f_name }} {{ $data[0]->middle_name }}" readonly>
                </div>
                <div class="col-md-4" style="padding: 15px;">
                    <label for="">Invoice Agent Name</label>
                    <input class="form-control" type="text" value="{{ $data[0]->agent_Name }}" readonly>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4" style="padding: 15px;">
                    <label for="">Total Amount</label>
                    <input class="form-control" type="text" value="<?php echo $currency.''.$total_Payable ?>" readonly>
                </div>
                @if( $amount_Paid == $total_Payable )
                    <div class="col-md-4" style="padding: 35px 0px 0px 17px;">
                        AMOUNT ALREADY PAID
                    </div>
                @else
                <div class="col-md-4" style="padding: 35px 0px 0px 17px;">
                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#recieve_payment_modal">Receieve Payment Invoice</button>
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
                    <form action="{{ URL::to('recieve_invoice_Agent')}}" method="post">
                        @csrf
                        
                        <input hidden readonly value="{{ $data[0]->id }}" name="invoice_Id" class="form-control" type="text" id="invoice_Id" required="">
                        <input hidden readonly value="{{ $data[0]->generate_id }}" name="generate_id" class="form-control" type="text" id="generate_id" required="">
                        <input hidden readonly value="{{ $data[0]->customer_id }}" name="customer_id" class="form-control" type="text" id="customer_id" required="">
                        <input hidden readonly value="{{ $data[0]->agent_Name }}" name="agent_Name" class="form-control" type="text" id="agent_Name" required="">
                        
                        <div class="mb-2">
                          <label for="date" class="form-label">Date</label>
                          <input value="{{ now()->format('Y-m-d') }}" name="date" class="form-control" type="date" id="date" required="">
                        </div>

                        <div class="mb-2">
                          <label for="passenger_Name" class="form-label">Passenger Name</label>
                          <input readonly name="passenger_Name" value="{{ $data[0]->f_name }} {{ $data[0]->middle_name }}" class="form-control" type="text" id="passenger_Name" required="">
                        </div>

                        <div class="mb-2">
                            <label for="total_Amount" class="form-label">Total Amount</label>
                            <input readonly name="total_Amount" value="{{ $total_Payable }}" class="form-control" type="text" id="total_Amount" required="">
                        </div>

                        <div class="mb-2">
                            <label for="recieved_Amount" class="form-label">Recieved Amount</label>
                            <input name="recieved_Amount" class="form-control" type="text" id="recieved_Amount" required="" placeholder="Recieved Amount" onchange="recieved_Amount_function()">
                        </div>

                        <div class="mb-2">
                            <label for="remaining_Amount" class="form-label">Remaining Amount</label>
                            <input readonly name="remaining_Amount" class="form-control" type="text" id="remaining_Amount" required="">
                        </div>

                        <div class="mb-2">
                            <label for="amount_Paid" class="form-label">Amount Paid</label>
                            @if(isset($amount_Paid))
                                <input readonly value="{{ $amount_Paid }}" name="amount_Paid" class="form-control" type="text" id="amount_Paid" required="">
                            @else
                                <input readonly name="amount_Paid" class="form-control" type="text" id="amount_Paid" placeholder="Nothing Paid yet">
                            @endif
                        </div>

                        <div style="padding: 10px 0px 10px 0px;">
                            <button style="padding: 10px 30px 10px 30px;" type="submit" class="btn btn-primary"><i class="mdi mdi-send me-1"></i>Recieve</button>
                            <button style="margin-left: 5px;padding: 10px 30px 10px 30px;" type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    
    $(document).ready(function () {

        var total_amount        = $('#total_Amount').val();
        var amount_paid         = $('#amount_Paid').val();
        var remaining_amount    = parseFloat(total_amount) - parseFloat(amount_paid);
        var remaining_amount2   = remaining_amount.toFixed(2);
        $('#remaining_Amount').val(remaining_amount2);

        // $('#recieved_Amount').on('change',function(){
        //     var recieved_amount         = $(this).val();
        //     var recieved_amountFloat    = parseFloat(recieved_amount);
        //     var recieved_amount1        = recieved_amountFloat.toFixed(2);
        //     var remaining_amount        = $('#remaining_Amount').val();
        //     var remaining_amount_final  = parseFloat(remaining_amount) - parseFloat(recieved_amount);
        //     var remaining_amount_final1 = remaining_amount_final.toFixed(2);
        //     $('#remaining_Amount').val(remaining_amount_final1);
        //     $('#amount_Paid').val(recieved_amount1);
        // });
        
        

    });
    
    function recieved_Amount_function(){
        
        var recieved_amount         = $('#recieved_Amount').val();
        var recieved_amountFloat    = parseFloat(recieved_amount);
        var recieved_amount1        = recieved_amountFloat.toFixed(2);
        $('#amount_Paid').val(recieved_amount1);
        
        var remaining_amount        = $('#remaining_Amount').val();
        var remaining_amount_final  = parseFloat(remaining_amount) - parseFloat(recieved_amount);
        var remaining_amount_final1 = remaining_amount_final.toFixed(2);
        $('#remaining_Amount').val(remaining_amount_final1);
        
    }

</script>

@endsection