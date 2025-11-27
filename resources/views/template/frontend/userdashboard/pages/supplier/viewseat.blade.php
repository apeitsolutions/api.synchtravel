@extends('template/frontend/userdashboard/layout/default')
@section('content')
 <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
<?php $currency=Session::get('currency_symbol'); ?>
<style>
   .tooltip-inner {
    max-width: 1000px !important; //define whatever width you want
}
</style>
<div id="flight_detail_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
<div class="modal-header">
                <h4 class="modal-title" >Add Flight Airline Name</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body htmldetailofflight">
               
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<div id="recieve_payment_modal" class="modal fade" tabindex="-1" style="display: none;" aria-hidden="true">
   <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="compose-header-modalLabel">Recieve Amount</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
             <div class="modal-body flight_payment_html"> 
             </div>
           
        </div>
   </div>
</div>

<div id="recieve_payment_history_modal" class="modal fade" tabindex="-1" style="display: none;" aria-hidden="true">
   <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="compose-header-modalLabel">Recieve Payment History</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
             <div class="modal-body flight_payment_history_html"> 
             </div>
           
        </div>
   </div>
</div>

<div class="container-fluid">
    <div class="table-responsive">
        <div class="row">
            <table style="margin-right: 17rem;"class="table  w-100 dt-responsive nowrap" id="example1">
                <thead class="table-light">
                    <tr>
                        <th style="">Sr No.</th>
                        <th style="">Supplier </th>
                        <th style="">Route(Start <i class="fa-solid fa-arrow-right" style="color: #0acf97;"></i> End)</th>
                        <th style="">Dates(Start <i class="fa-solid fa-arrow-right" style="color: #0acf97;"></i> End)</th>
                        <th style="">Direct/Indirect</th>
                        <th style="">Seats</th>
                        <th style="">Total Amount</th>
                        <th style="">Paid Amount</th>
                        <th style="">Over Paid</th>
                        <th style="">Adult</th>
                        <th style="">Child</th>
                        <th style="">Intant</th>
                        <th style="">Payment</th>
                        <th style="">Occupied</th>
                        <th style="width: 85px;">Action</th>
                    </tr>
                </thead>
                
                <tbody>
                    @foreach($flight as $key=>$flights)
                        <?php
                            $supplier_id    = $flights->dep_supplier;
                            $curl           = curl_init();
                            $data           = array('supplier_id'=>$supplier_id);
                            $endpoint_url   = config('endpoint_project');
                            curl_setopt_array($curl, array(
                                CURLOPT_URL => $endpoint_url.'/api/fetchsuppliername',
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 0,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'POST',
                                CURLOPT_POSTFIELDS =>  $data,
                                CURLOPT_HTTPHEADER => array(
                                    'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
                                ),
                            ));
                            $response   = curl_exec($curl);
                            $sign       = json_decode($response);
                            if($sign != null){
                                if($sign->message == 'success'){
                                    $supplier           = $sign->fetchedsupplier; 
                                    $supplier_name      = $supplier->companyname ?? "";
                                    $supplier_wallet    = $supplier->wallet_amount ?? "";
                                }else{
                                    $supplier_name      = "";
                                    $supplier_wallet    = "";
                                }
                            }
                            $flights_details_more           = json_decode($flights->dep_object);
                            $flights_details_more_return    = json_decode($flights->return_object);
                        ?>
                        <tr>
                            <td>{{$flights->id ?? ""}}</td>
                            <td>{{$supplier_name ?? ""}}</td>
                            <td data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="bottom"   title="<div class='d-flex'><?php foreach($flights_details_more as $flights_details_morez){ ?><div class='tooltiptext-inner'><p>{{ $flights_details_morez->departure ?? ""}} <i class='fa-solid fa-arrow-right' style='color: #0acf97;'></i> {{$flights_details_morez->arival ?? ""}}.</p></div> <?php } ?> </div>  <div class='d-flex'><?php foreach($flights_details_more_return as $flights_details_more_of_return){ ?><div><p>{{ $flights_details_more_of_return->departure ?? ""}} <i class='fa-solid fa-arrow-right'></i> {{$flights_details_more_of_return->arival ?? ""}}:</p></div> <?php } ?> </div>"  >
                                <div class="d-flex">
                               @foreach($flights_details_more as $flights_details_morez)
                                <p  style="font-size: 15px;" >{{substr($flights_details_morez->departure,0 ,6) ?? ""}} <i class="fa-solid fa-arrow-right" style="color: #0acf97;"></i> {{substr($flights_details_morez->arival,0,6) ?? ""}}/ </p>
                               @endforeach
                               </div>
                            </td>
                            <td data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="bottom" title="<div class='d-flex'><?php  foreach($flights_details_more as $key=> $flights_details_morez){ if ($key === array_key_first($flights_details_more)){ ?><div><p>{{date("d-m-y",strtotime($flights_details_morez->departure_time)) ?? ""}} <i class='fa-solid fa-arrow-right' style='color: #0acf97;'></i> {{date("d-m-y",strtotime($flights_details_morez->arrival_time)) ?? ""}}</p></div> <?php } } ?></div> <div class='d-flex'><?php  foreach($flights_details_more as $key=>$flights_details_morez){  if ($key === array_key_last($flights_details_more)){ ?><div><p>{{date("d-m-y",strtotime($flights_details_morez->departure_time)) ?? ""}} <i class='fa-solid fa-arrow-right' style='color: #0acf97;'></i> {{date("d-m-y",strtotime($flights_details_morez->arrival_time)) ?? ""}}</p></div> <?php } } ?> </div>">
                               
                                <div class="d-flex">
                                @foreach($flights_details_more as $flights_details_morez)
                                    @if($loop->first)
                                        <p style="font-size: 15px;">{{date("d-m-y",strtotime($flights_details_morez->departure_time)) ?? ""}} <i class="fa-solid fa-arrow-right" style="color: #0acf97;"></i> {{date("d-m-y",strtotime($flights_details_morez->arrival_time)) ?? ""}}/</p>
                                    @endif
                                    @if($loop->last)
                                        <p style="font-size: 15px;">{{date("d-m-y",strtotime($flights_details_morez->departure_time)) ?? ""}} <i class="fa-solid fa-arrow-right" style="color: #0acf97;"></i> {{date("d-m-y",strtotime($flights_details_morez->arrival_time)) ?? ""}}</p>
                                    @endif
                                @endforeach
                                </div>
                                
                            </td>
                            <td>{{$flights->dep_flight_type ?? ""}}</td>
                            <td>{{$flights->flights_number_of_seat ?? ""}}</a></span></td>
                            <?php
                                $total_flight_price_for_adults      = $flight_count[$key]->totaladults * $flights->flights_per_person_price ?? "0";
                                $total_flight_price_for_childs      = $flight_count[$key]->totalchilds * $flights->flights_per_child_price ?? "0";
                                $total_flight_price_for_infants     = $flight_count[$key]->totalinfants * $flights->flights_per_infant_price ?? "0";
                                $total_all_pax_price                = $total_flight_price_for_adults + $total_flight_price_for_childs + $total_flight_price_for_infants ?? "";
                                $total_used_pax                     = $flight_count[$key]->totaladults + $flight_count[$key]->totalchilds + $flight_count[$key]->totalinfants ?? "0";
                            ?>
                            <td><a class="btn btn-success bootstrap-touchspin-up currency_value_exchange_1">{{ $currency }}{{$total_all_pax_price ?? ""}}</a></td>
                            <td>{{ $currency }}{{$flights->flight_paid_amount ?? ""}}</td>
                            <td>{{ $currency }}{{$flights->over_paid_amount ?? ""}}</td>
                            
                            <td>{{ $currency }} {{$flights->flights_per_person_price ?? ""}}({{ $flight_count[$key]->totaladults ?? "" }})</td>
                            <td>{{ $currency }} {{$flights->flights_per_child_price ?? ""}}({{ $flight_count[$key]->totalchilds ?? "" }})</td>
                            <td>{{ $currency }} {{$flights->flights_per_infant_price ?? ""}}({{ $flight_count[$key]->totalinfants ?? "" }})</td>
                            <td><span class="input-group-btn input-group-append"><a class="btn btn-danger bootstrap-touchspin-up currency_value_exchange_1"> <?php echo"Not Paid"; ?></a></span></td>
                            <td><span class="input-group-btn input-group-append" ><a href="{{URL::to('view_seat_occupancy')}}/{{$flights->id ?? ""}}" class="btn btn-danger bootstrap-touchspin-up currency_value_exchange_1"> {{$total_used_pax ?? ""}} </a></span></td>
                            <td>
                                <div class="dropdown card-widgets">
                                    <a style="float: right;" href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="dripicons-dots-3" style="color: #6e6060;"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                        <a href="{{URL::to('editseat')}}/{{$flights->id ?? ""}}" class="dropdown-item"><i class="mdi mdi-account-edit me-1"></i>Edit
                                        </a>
                                        <a href="{{URL::to('deleteseat')}}/{{$flights->id ?? ""}}" class="dropdown-item" onclick="return confirm('Are you sure you want to delete?');">
                                            <i class="mdi mdi-check-circle me-1"></i>
                                            Delete
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div> 
</div>

@endsection
@section('scripts')

    <script>
    function myFunction(){
            var total_amount = $('#total_amount').val();
            var paid_amount = $('#amount_paid').val();
            var remaining_amount = $('#remaining_amount').val();
           
             
            if(total_amount){
             
              var total_amount = total_amount; 
            }else{
                var total_amount = total_amount; 
            }
            if(paid_amount){
              var paid_amount = paid_amount; 
            }
            if(remaining_amount){
              var remaining_amount = remaining_amount; 
            }
           
             
             
             
            var price_addition = parseFloat(remaining_amount) - parseFloat(paid_amount);
            // console.log(price_addition)
            
            var multiple = price_addition;
            console.log(multiple);
            $('#remaining_amount').val(multiple);
        
        }
        $(document).ready(function(){
            
    $(".payment_btn_of_flight").on('click',function () {
        var data_of_flight = $(this).attr('detail_attr');
        data_of_flight = JSON.parse(data_of_flight);
        console.log(data_of_flight);
        if(data_of_flight['amount_paidandremaining_of_flight'] ){
            var remain = JSON.parse(data_of_flight['amount_paidandremaining_of_flight']);
           
             var lastindex = remain[remain.length - 1];
             
             remain = lastindex['remaining_amount'];
           
        }else{
            var remain = data_of_flight['flight_total_price'];
        }
        
       var flight_data = ` <div class="p-1">
                <div class="modal-body px-3 pt-3 pb-0">
                    <form action="{{ URL::to('save_flight_payment_recieved_and_remaining')}}" method="post">
                        @csrf
                        
                        <!--<input hidden readonly value="" name="flight_id" class="form-control" type="text" id="package_id" required="">-->
                        
                        <div class="mb-2">
                          <label for="tourId" class="form-label">Flight ID</label>
                          <input readonly name="flightId" class="form-control" value="${data_of_flight['id']}" type="text" id="flightId" required="">
                        </div>
                         <?php
                                            $f_name=Session::get('name');
                                            $l_name=Session::get('lname');
                                            $email_e=Session::get('email');
                                            $id =Session::get('id');
                                            // echo $f_name . $l_name; 
                                        ?>
                    
                        <div class="mb-2">
                          <label for="tourId" class="form-label">Client Name</label>
                          <input hidden name="payingidentityemail" class="form-control" value="<?php echo $email_e; ?>" type="text"  required="">
                          <input hidden name="payingidentityid" class="form-control" value="<?php echo $id; ?>" type="text"  required="">
                          <input readonly  class="form-control" value="<?php echo $f_name . $l_name; ?>" type="text"  required="">
                        </div>


                        <div class="mb-2">
                          <label for="date" class="form-label">Date</label>
                          <input value="" name="date" class="form-control" type="date" id="date" required="">
                        </div>



                        <div class="mb-2">
                            <label for="total_amount" class="form-label">Total Amount</label>
                            <input readonly name="total_amount" value="${data_of_flight['flight_total_price']}" class="form-control" type="text" id="total_amount" required="">
                        </div>

                        <div class="mb-2">
                            <div class="row">
                                <div class="col-md-6">
                                   <label for="total_amount" class="form-label">Payment From</label>
                                    <select class="form-control" onchange="paymentMethod()" id="payment_method" name="payment_method">
                                        <option value="Bank Transfer">Bank Transfer</option>
                                        <option value="Cash">Cash</option>
                                        <option value="Card Payment">Card Payment</option>
                                        <option value="Wallet">Wallet</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="total_amount" class="form-label">Wallet Balance</label>
                                     <input value="${data_of_flight['supplier_wallet']}" name="" class="form-control" type="number" readonly id="supplier_wallet" required="">
                                </div>
                            </div>
                         
                        </div>
                        

                        <div class="mb-2">
                            <label for="remaining_amount" class="form-label">Remaining Amount</label>
                            <input readonly name="remaining_amount" value="${data_of_flight['flight_remain_amount']}" class="form-control" type="text" id="remaining_amount" required="">
                        </div>

                        <div class="mb-2">
                            <label for="amount_paid" class="form-label">Recieve Amount</label>
                           
                            <input name="amount_paid" onchange="myFunction()"class="form-control" type="number" id="amount_paid" placeholder="Nothing Paid yet">
                   
                        </div>

                        <div style="padding: 10px 0px 10px 0px;">
                            <button style="padding: 10px 30px 10px 30px;" type="submit" class="btn btn-primary"><i class="mdi mdi-send me-1"></i>Recieve</button>
                            <button style="margin-left: 5px;padding: 10px 30px 10px 30px;" type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        </div>

                        <!-- <div class="mb-2 text-center">
                          <button class="btn btn-primary" type="submit">Recieve</button>
                        </div> -->
                    </form>
                </div>
            </div>`;
         $('.flight_payment_html').html(flight_data);
       
  
    });
    
 
    
    
    $(".payment_history_btn_of_flight").on('click',function () {
         $('.flight_payment_history_html').empty();
        var payments_of_flight = $(this).attr('payment_detail_attr');
        payments_of_flight = JSON.parse(payments_of_flight);
        console.log(payments_of_flight);
        for (var i = 0; i < payments_of_flight.length; i++) {
            if(payments_of_flight[i]['remaining_amount'] == true){
                var remain = 0;
            }else{
                var remain = payments_of_flight[i]['remaining_amount'];
            }
           
            
            var flight_payment_data = ` <div class="p-1">
                <div class="modal-body px-3 pt-3 pb-0">
                   
                        <div class="mb-2">
                          <label for="tourId" class="form-label">FlightId</label>
                         
                          <input readonly  class="form-control" value="${payments_of_flight[i]['flightId']}" type="text"  required="">
                        </div>
                        
                        <div class="mb-2">
                          <label for="tourId" class="form-label">Client Email</label>
                         
                          <input readonly  class="form-control" value="${payments_of_flight[i]['payingidentityemail']}" type="text"  required="">
                        </div>


                        <div class="mb-2">
                          <label for="date" class="form-label">Date</label>
                          <input readonly value="${payments_of_flight[i]['date']}" name="date" class="form-control" type="date"  required="">
                        </div>



                        <div class="mb-2">
                            <label for="total_amount" class="form-label">Total Amount</label>
                            <input readonly  value="${payments_of_flight[i]['total_amount']}" class="form-control" type="text"  required="">
                        </div>

                        

                        <div class="mb-2">
                            <label for="remaining_amount" class="form-label">Remaining Amount</label>
                            <input readonly  value="${remain}" class="form-control" type="text"  required="">
                        </div>

                        <div class="mb-2">
                            <label for="amount_paid" class="form-label">Amount Paid</label>
                           
                            <input readonly class="form-control" type="text" value="${payments_of_flight[i]['amount_paid']}" >
                   
                        </div>

                       
                       
                </div>
            </div>`;
             $('.flight_payment_history_html').append(flight_payment_data);
            
        }
       
       
  
    });
           

            
            
            // var td = $('.data_of_td').attr('table_row_data');
            // console.log(td);
            
          
        $(".flight_detail_btn").on('click',function () {
        var data = $(this).attr('detail_attr');
        data = JSON.parse(data);
        // console.log(data);
                            var flight =    `<div class="col-md-1 mt-2 mb-2">
                                                <!--<i class="fa fa-plane" aria-hidden="true" style="font-size:2rem;"></i>!-->
                                                <img src="{{asset('/public/images/departure.png') }}" width="50px">
                                                </div>
                                                <div class="col-md-11 mt-2 mb-2"><h5 style="font-size:20px">Flight Details</h5></div>
                                                
                                                <div class="col-md-12">
                                                                <div class="initiative">
                                                <!-- ITEM -->
                                                <div class="initiative__item">
                                                    <div class="initiative-top">
                                                        <div class="title" style="font-size: 20px;margin-left: 70px;">
                                                            <div class="from-to">
                                                                <span class="from">first_depaurture</span>
                                                                <!--<i class="mdi-arrow-right-bold"></i>!-->
                                                                <img src="{{asset('/public/images/departure.png') }}" width="25px">
                                                                <span class="to">last_destination</span>
                                                            </div>
                                                            <div class="time">first_dep_time last_arrival</div>
                                                        </div>
                                                       
                                                    </div>
                                                    <table class="initiative-table">
                                                        <tbody>`
                                                     
                                                            flight += `<tr>
                                                                <th>
                                                                    <div class="item-thumb" style="text-align: center;">
                                                                        <div class="image-thumb">
                                                                            <img src="{{ config('img_url') }}/public/uploads/package_imgs/" style="width: 70%;">
                                                                        </div>
                                                                        <div class="text">
                                                                            <span>airlineName</span>
                                                                            <p style="margin-bottom:0px"></p>
                                                                            <span></span>
                                                                        </div>
                                                                    </div>
                                                                </th>
                                                                <td>
                                                                    <div class="row" style="padding:0px;">
                                                                        <div class="col-xl-4">
                                                                            <h3><img src="{{asset('/public/images/departure.png') }}" width="25px">
                                                                           </h3>
                                                                            <span class="time">flight_times_arr</span>
                                                                            <span class="date">flight_date_arr </span>
                                                                        </div>
                                                                        <div class="col-xl-4">  
                                                                            <h3>Duration Time</h3>
                                                                            <span>10h 25m</span>
                                                                        </div>
                                                                        <div class="col-xl-4">
                                                                            <h3><img src="{{asset('/public/images/landing.png') }}" width="25px">
                                                                            </h3>
                                                                            <span class="time">flight_times_arrival_arr</span>
                                                                            <span class="date">flight_date_arrival_arr</span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>`;
                                                       
                                                            
                                                      
                                                          
                                                          flight += `<tr>
                                                                <th>
                                                                    <div class="item-thumb" style="text-align: center;">
                                                                        <div class="image-thumb">
                                                                            <img src="{{ config('img_url') }}/public/uploads/package_imgs/" style="width: 65%;">
                                                                        </div>
                                                                        <div class="text">
                                                                            <span></span>
                                                                            <p style="margin-bottom:0px"></p>
                                                                            <span></span>
                                                                        </div>
                                                                    </div>
                                                                </th>
                                                                <td>
                                                                    <div class="row style="padding:0px;">
                                                                        <div class="col-xl-4">
                                                                            <h3><img src="{{asset('/public/images/departure.png') }}" width="25px">
                                                                           </h3>
                                                                          <span class="time"></span>
                                                                            <span class="date"> </span>
                                                                        </div>
                                                                        <div class="col-xl-4">
                                                                            <h3>Duration Time</h3>
                                                                            <span></span>
                                                                        </div>
                                                                        <div class="col-xl-4">
                                                                            <h3><img src="{{asset('/public/images/landing.png') }}" width="25px">
                                                                           </h3>
                                                                              <span class="time"> </span>
                                                                            <span class="date"> </span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>`;
                                                            
                                                            
                                                       
                                                        
                                                        
                                                        flight += `</tbody>
                                                    </table>
                                                </div>
                                                <!-- END / ITEM -->
                                
                                                <h5 style="font-size:20px">Return Flight Details</h5>
                                                 
                                                <div class="initiative__item">
                                                    <div class="initiative-top">
                                                        <div class="title" style="font-size: 20px;margin-left: 70px;">
                                                            <div class="from-to">
                                                                <span class="from"></span>
                                                                <!--<i class="mdi-arrow-right-bold"></i>!-->
                                                                <img src="{{asset('/public/images/departure.png') }}" width="25px">
                                                                <span class="to"></span>
                                                            </div>
                                                            <div class="time"></div>
                                                        </div>
                                                   
                                                    </div>
                                                    <table class="initiative-table">
                                                        <tbody>`
                                                      
                                                            flight += `<tr>
                                                                <th>
                                                                    <div class="item-thumb" style="text-align: center;">
                                                                        <div class="image-thumb">
                                                                            <img src="{{ config('img_url') }}/public/uploads/package_imgs/" style="width: 65%;">
                                                                        </div>
                                                                        <div class="text">
                                                                            <span></span>
                                                                            <p style="margin-bottom:0px"></p>
                                                                            <span></span>
                                                                        </div>
                                                                    </div>
                                                                </th>
                                                                <td>
                                                                    <div class="row" style="padding:0px;">
                                                                        <div class="col-xl-4">
                                                                            <h3><img src="{{asset('/public/images/departure.png') }}" width="25px">
                                                                            </h3>
                                                                            <span class="time"></span>
                                                                            <span class="date"> </span>
                                                                          
                                                                        </div>
                                                                        <div class="col-xl-4">
                                                                            <h3>Duration Time</h3>
                                                                            <span></span>
                                                                        </div>
                                                                        <div class="col-xl-4">
                                                                            <h3><img src="{{asset('/public/images/landing.png') }}" width="25px">
                                                                            </h3>
                                                                            <span class="time"> </span>
                                                                            <span class="date"> </span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>`;
                                                        
                                                            
                                                      
                                                          flight += `<tr>
                                                                <th>
                                                                    <div class="item-thumb" style="text-align: center;">
                                                                        <div class="image-thumb">
                                                                            <img src="{{ config('img_url') }}/public/uploads/package_imgs/" style="width: 65%;">
                                                                        </div>
                                                                        <div class="text">
                                                                            <span></span>
                                                                            <p style="margin-bottom:0px"></p>
                                                                            <span></span>
                                                                        </div>
                                                                    </div>
                                                                </th>
                                                                <td>
                                                                    <div class="row" style="padding:0px;">
                                                                        <div class="col-xl-4">
                                                                            <h3><img src="{{asset('/public/images/departure.png') }}" width="25px">
                                                                           </h3>
                                                                          <span class="time"></span>
                                                                            <span class="date"> </span>
                                                                        </div>
                                                                        <div class="col-xl-4">
                                                                            <h3>Duration Time</h3>
                                                                            <span></span>
                                                                        </div>
                                                                        <div class="col-xl-4">
                                                                            <h3><img src="{{asset('/public/images/landing.png') }}" width="25px">
                                                                            </h3>
                                                                              <span class="time"> </span>
                                                                            <span class="date"> </span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>`;
                                                            
                                                        
                                                        flight += `</tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>`;
                     
                             
                           
        $('#flight_detail_modal').modal('show');
        $('.htmldetailofflight').html(flight);
    });
           
        });
        
        
           function paymentMethod(){
                var payment_method = $('#payment_method').val();
                console.log('The payment is call now ');
                if(payment_method == 'Wallet'){
                    console.log('The selectedd is call now ');
                    var walletAmount = $('#supplier_wallet').val();
                    $('#amount_paid').attr('max',walletAmount);
                }else{
                    $('#amount_paid').attr('max',false);
                }
            }
    </script>

@stop

@section('slug')

@stop
