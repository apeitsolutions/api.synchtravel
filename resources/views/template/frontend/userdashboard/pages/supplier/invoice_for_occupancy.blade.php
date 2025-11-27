@extends('template/frontend/userdashboard/layout/default')
@section('content')


                <div class="container-fluid">
                    
                    <div class="table-responsive">
                        <div class="row">
                            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" style="background-color:#f5cfcf;" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" style="background-color:#d4edda;" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                

                <div id="pax_modal" class="modal fade" tabindex="-1" style="display: none;" aria-hidden="true">
   <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="compose-header-modalLabel">Passenger Details</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
             <div class="modal-body flight_payment_html"> 
             </div>
           
        </div>
   </div>
</div><!-- /.modal --> 
                <div id="pax_info_modal" class="modal fade" tabindex="-1" style="display: none;" aria-hidden="true">
   <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="compose-header-modalLabel">Passenger Details</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
             <div class="modal-body information_html"> 
             <table class="table table-centered w-100 dt-responsive nowrap" id="example1">
                                <thead class="table-light">
                                    <tr>
                                       
                                        <th style="text-align: center;">Lead Passenger</th>
                                        <th style="text-align: center;">Other Passenger</th>
                                        
                                    </tr>
                                </thead>
                                <tbody style="text-align: center;" class="info_append">
                                    
                                    </tbody>
                            </table>
             </div>
           
        </div>
   </div>
   
</div><!-- /.modal -->
                
                <!--<h3>Occupancy Record Against Flight Id.{{$suppliers[0]->flight_id ?? ""}}</h3>-->
                            <table class="table table-centered w-100 dt-responsive nowrap" id="example1">
                                <thead class="table-light">
                                    <tr>
                                        <th style="text-align: center;">Invoice No.</th>
                                        <th style="text-align: center;">Lead Passenger</th>
                                        <th style="text-align: center;">Tour</th>
                                        <th style="text-align: center;">No of Pax</th>
                                        <th style="text-align: center;">No of Infants</th>
                                        <th style="width: 85px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody style="text-align: center;">
                                    <?php
// dd($invoices);
?>

                                    @foreach($invoices as $invoicez)
                                    <?php
                                        $invoice_cart = json_decode($invoicez->cart_total_data);
                                    if($invoice_cart->total_adults != ""){
                                        $pax = $invoice_cart->total_adults + $invoice_cart->total_childs + $invoice_cart->total_Infants;
                                        $infants = $invoice_cart->total_Infants;
                                    }
                                    
                                  
                                    ?>
                                    <tr>
                                    <td><span class="badge bg-success" style="font-size: 15px">{{$invoicez->invoice_no ?? ""}}</span></td>
                                    <td><span class="badge bg-success" style="font-size: 15px">{{$invoicez->passenger_name ?? ""}}</span></td>
                                    <td><span class="badge bg-success" style="font-size: 15px">{{$invoicez->tour_name ?? ""}}</span></td>
                                    
                                    <td><span class="badge bg-success" style="font-size: 15px">{{$pax ?? ""}}</span></td>
                                    <td><span class="badge bg-success" style="font-size: 15px">{{ $infants ?? ""}}</span></td>
                                  
                                    <td> <a style="display: inline-block; margin-bottom: 5px;" href="{{URL::to('')}}/pax_details/{{$invoicez->invoice_no}}" target="_blank"><span class="badge bg-success view_pax_info" pax_info_attr='<?php echo json_encode($invoices) ?>' data-bs-toggle="modal" data-bs-target="#pax_info_modal1" style="font-size: 15px">Passenger Details</span></a> <a style="display: inline-block; margin-bottom: 5px;" href="javascript:void(0);"><span class="badge bg-success view_pax_btn" pax_attr='<?php echo json_encode($invoicez) ?>' data-bs-toggle="modal" data-bs-target="#pax_modal" style="font-size: 15px">View Pax</span></a> <a style="display: inline-block; margin-bottom: 5px;" href="{{URL::to('')}}/invoice/{{$invoices->invoice_no ?? ""}}" target="_blank" ><span class="badge bg-success" style="font-size: 15px">View Invoice</span></a> </td>
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
        $(document).ready(function(){
            $(".view_pax_btn").on('click',function () {
                
                var data_of_pax = $(this).attr('pax_attr');
                 data_of_pax = JSON.parse(data_of_pax);
                  console.log("data_of_pax");
                  console.log(data_of_pax);
                  console.log("data_of_pax");
                   var tourId = data_of_pax['tour_id'];
                
            
                
                
                
                
        $.ajax({    
            type: "POST",
            url: "{{URL::to('')}}/fetchflightrate",
            data:{
                "_token"                : "{{ csrf_token() }}",
                'tourId'                 : tourId,
            
            },
            success: function(data){
                console.log('data');
                console.log(data);
                console.log('data');
                 var child_flight_price = data['child_flight_sale_price'];
                 var infant_flight_price = data['infant_total_sale_price'];
                 var adult_flight_price = JSON.parse(data['markup_details']);
                 console.log('mark up');
                 console.log(adult_flight_price);
                 console.log('mark up');
                 var adult_flight_price = adult_flight_price[0]['without_markup_price'];
                // console.log(data);
                // console.log(child_flight_price);
                // console.log(infant_flight_price);
                // console.log(adult_flight_price);
                        var cart = JSON.parse(data_of_pax['cart_total_data']);
                    // console.log(cart);
         if(cart['double_adults']){
             var double_adult = cart['double_adults'];
             var adult_flight_price = adult_flight_price;
         }else{
             var double_adult = 0;
         }
         if(cart['triple_adults']){
             var triple_adult = cart['triple_adults'];
              var adult_flight_price = adult_flight_price;
         }else{
              var triple_adult = 0;
         }
         if(cart['quad_adults']){
             var quad_adult = cart['quad_adults'];
              var adult_flight_price = adult_flight_price;
         }else{
             var quad_adult = 0;
         }
         var adults = parseFloat(double_adult) + parseFloat(triple_adult) + parseFloat(quad_adult);
         var adult_total_price = adults * adult_flight_price;
         var single_adult_price = cart['sharing2'];
         
         
         if(cart['double_childs']){
             var double_child = cart['double_childs'];
              var child_flight_price = child_flight_price;
         }else{
             var double_child = 0;
         }
         if(cart['triple_childs']){
             var triple_child = cart['triple_childs'];
              var child_flight_price = child_flight_price;
         }else{
              var triple_child = 0;
         }
         if(cart['quad_childs']){
             var quad_child = cart['quad_childs'];
              var child_flight_price = child_flight_price;
         }else{
             var quad_child = 0;
         }
         var childs = parseFloat(double_child) + parseFloat(triple_child) + parseFloat(quad_child);
         var child_total_price = childs * child_flight_price;
         var single_child_price = cart['sharing2'];
         
         
         if(cart['double_infant']){
             var double_infant = cart['double_infant'];
             var infant_flight_price = infant_flight_price;
         }else{
             var double_infant = 0;
         }
         if(cart['triple_infant']){
             var triple_infant = cart['triple_infant'];
              var infant_flight_price = infant_flight_price;
         }else{
              var triple_infant = 0;
         }
         if(cart['quad_infant']){
             var quad_infant = cart['quad_infant'];
              var infant_flight_price = infant_flight_price;
         }else{
             var quad_infant = 0;
         }
         var infants = parseFloat(double_infant) + parseFloat(triple_infant) + parseFloat(quad_infant);
         var infant_total_price = infants * infant_flight_price;
         var single_infant_price = cart['sharing2'];
         
        
          var pax_data = ` <div class="p-1">
                <div class="modal-body px-3 pt-3 pb-0">
                  
                <div class="row">
                        
                        <div class="col-4">
                          <label for="tourId" class="form-label">Adult</label>
                          <input readonly name="flightId" class="form-control" value="${adults}" type="text"  >
                        </div>
                        <div class="col-4">
                          <label for="tourId" class="form-label">Adult Price</label>
                          <input readonly  class="form-control" value="${adult_flight_price}" type="text"  >
                        </div>
                        <div class="col-4">
                          <label for="tourId" class="form-label">Total Price</label>
                          <input readonly class="form-control" value="${adult_total_price}" type="text"  >
                        </div>
                      
                        
                        <div class="col-4">
                          <label for="tourId" class="form-label">Child</label>
                          <input readonly  class="form-control" value="${childs}" type="text"  >
                        </div>
                        <div class="col-4">
                          <label for="tourId" class="form-label">Child Price</label>
                          <input readonly  class="form-control" value="${child_total_price}" type="text"  >
                        </div>
                        <div class="col-4">
                          <label for="tourId" class="form-label">Total Price</label>
                          <input readonly  class="form-control" value="${child_total_price}" type="text"  >
                        </div>
                        
                        
                        <div class="col-4">
                          <label for="tourId" class="form-label">Infant</label>
                          <input readonly  class="form-control" value="${infants}" type="text"  >
                        </div>
                        <div class="col-4">
                          <label for="tourId" class="form-label">Infant Price</label>
                          <input readonly  class="form-control" value="${infant_total_price}" type="text"  >
                        </div>
                        <div class="col-4">
                          <label for="tourId" class="form-label">Total Price</label>
                          <input readonly  class="form-control" value="${infant_total_price}" type="text"  >
                        </div>

                </div>
                        
                </div>
            </div>`;
         $('.flight_payment_html').html(pax_data);
        
            }
        });
                
                
            
                
        
       
 
       
         
       
  
    });
    
    
    
     $(".view_pax_info").on('click',function () {
                
                var data_of_pax = $(this).attr('pax_info_attr');
                 data_of_pax = JSON.parse(data_of_pax);
                //  console.log(data_of_pax);
                  var lead_info = data_of_pax['passenger_detail'];
                 lead_info = JSON.parse(lead_info);
                 lead_info = lead_info[0];
                // console.log(lead_info);
                 
                 
                  var other_adult_info = data_of_pax['adults_detail'];
                  other_adult_info = JSON.parse(other_adult_info);
                  other_adult_info = other_adult_info[0];
                //   console.log(other_adult_info);
                  
                  
               var info=`
                                  
                                    <tr>`
                                    if(lead_info){
                                        
                            info +=`<td>
                                    <p>Name: ${lead_info['lead_title']} ${lead_info['name']}${lead_info['lname']}</p>
                                    <p>E-mail: ${lead_info['email']}</p>
                                    <p>Country: ${lead_info['country']}</p>
                                    <p>Phone: ${lead_info['phone']}</p>
                                    <p>Gender: ${lead_info['gender']}</p>
                                    <p>Type: ${lead_info['passengerType']}</p>
                                    
                                    </td>`
                                    }else{
                            info +=`<td></td>`
                                    }
                                    if(other_adult_info){
                                        

                                        info +=`<td>
                                    <p>Name: ${other_adult_info['passengerName']}</p>
                                    <p>Gender: ${other_adult_info['gender']}</p>
                                    <p>Type: ${other_adult_info['passengerType']}</p>
                                    </td>`
                                    }else{
                                       info +=`<td></td>`  
                                    }
                                
                                    info +=`</tr>
                                   
                                `;
                
                
                 $('.info_append').html(info);
                 $('.dataTables_filter').hide();
    
       
  
    });
        });
    </script>

@stop

@section('slug')

@stop
