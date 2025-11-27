@extends('template/frontend/userdashboard/layout/default')
@section('content')

@if(Session::has('success'))
<div class="alert alert-success mt-5" role="alert">
  {{Session::get('success')}}
</div>
@endif
        <div class="card mt-5">
            <div class="card-header">
                <h4 class="card-title" id="standard-modalLabel">Request Availability</h4>
                
            </div>
            <div class="card-body">
                
                <?php
                if(isset($get_invoice->accomodation_details))
                $accomodation_details=json_decode($get_invoice->accomodation_details);
                
                ?>
                <form action="{{URL::to('request_availability_send_email_submit')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="label" class="form-label">Email address</label>
                        <input class="form-control" type="email" id="title" name="request_availability_email" placeholder="" required>
                        <input class="form-control" type="hidden" id="title" name="invoice_id" placeholder="" value="{{$get_invoice->id}}">
                    </div>
                    <div class="mb-3">
                        <label for="slug" class="form-label">Comment</label>
                         <input class="form-control" type="hidden" id="title" name="request_availability" value='<?php print_r($get_invoice->accomodation_details); ?>'>
                        <textarea class="form-control" name="" placeholder="Enter Your View" cols="135" rows="5" required="" readonly="">
                        Dear,@foreach($accomodation_details as $details){{$details->acc_hotel_name}}
                        
                        Please check the availability for Rooms as per below requirements.
                       
                        Hotel check in:       {{$details->acc_check_in}}
                        Hotel check out:      {{$details->acc_check_out}}
                        Hotel Type:           {{$details->acc_type}}
                        Quantity:             {{$details->acc_qty}}
                        
                         
                        @endforeach
                Regards,
                                    Alhijaz Tours Ltd.   
                            .
                        </textarea>
                    </div>
                    
              
                    <div class="mb-3 text-center" style="float: right;">
                        <button style="width: 140px;" class="btn btn-primary" id="" name="submit" type="submit">Submit</button>
                    </div>
                </form>
                <?php
                
                
                ?>
                
            </div>
        </div><!-- /.modal-content -->



@endsection