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
                
                 <?php

$lead_info = json_decode($invoices->passenger_detail);
// $lead_info = $lead_info[0];



$other_adult_info = json_decode($invoices->adults_detail);


    // dd($invoices->child_detail);
$child_detail = json_decode($invoices->child_detail);
        
?>
               
                
                            <table class="table table-centered  w-100 dt-responsive nowrap" id="example1">
                                <thead class="table-light">
                                    <tr>
                                    @if($lead_info != "" || $other_adult_info != "" || $child_detail != "")
                                       <th style="text-align: center;">Sr No.</th>
                                       <th style="text-align: center;">Name</th>
                                       <th style="text-align: center;">Email</th>
                                       <th style="text-align: center;">Country</th>
                                       <th style="text-align: center;">Phone</th>
                                       <th style="text-align: center;">Gender</th>
                                       <th style="text-align: center;">Type</th>
                                    @endif
                                   
                                        
                                    </tr>
                                </thead>
                                <tbody style="text-align: center;">
                                   
<?php $count = 1; ?>
                                   @if($lead_info != "")
                                @foreach($lead_info as $lead_info)
                                   
                                <tr>
                                    <td>{{$count}}</td>
                                    <td> {{$lead_info->lead_title}} {{$lead_info->name ?? ""}}{{$lead_info->lname ?? ""}} </td>
                                    <td> {{$lead_info->email ?? ""}} </td>
                                    <td> {{$lead_info->country ?? ""}} </td>
                                    <td> {{$lead_info->phone ?? ""}} </td>
                                    <td> {{$lead_info->gender ?? ""}} </td>
                                    <td> {{$lead_info->passengerType ?? ""}} </td>
                                </tr>
                                      @endforeach
                                      <?php $count++ ?>
                                @endif
                                 
                                    
                                @if($other_adult_info != "")
                                @foreach($other_adult_info as $other_adult_info)
                                <tr>
                                    
                                    <td>{{$count}}</td>
                                    <td> {{$other_adult_info->passengerName ?? ""}} </td>
                                    <td>N/A</td>
                                    <td>N/A</td>
                                    <td>N/A</td>
                                    <td> {{$other_adult_info->gender ?? ""}} </td>
                                    <td> {{$other_adult_info->passengerType ?? ""}} </td>
                                </tr>
                                <?php $count++ ?>
                                @endforeach
                                
                                @endif
                                
                               
                                @if($child_detail != "")
                                @foreach($child_detail as $child_detail)
                                   
                                <tr>
                                    <td>{{$count}}</td>
                                    <td> {{$child_detail->passengerName ?? ""}} </td>
                                    <td>N/A</td>
                                    <td>N/A</td>
                                    <td>N/A</td>
                                    <td> {{$child_detail->gender ?? ""}} </td>
                                    <td> {{$child_detail->passengerType ?? ""}} </td>
                                </tr>
                                <?php $count++ ?>
                                      @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div> 
                    
                </div>

@endsection

@section('scripts')

   

    <script>
        $(document).ready(function(){
    //         $(".view_pax_btn").on('click',function () {
                
    //             var data_of_pax = $(this).attr('pax_attr');
    //              data_of_pax = JSON.parse(data_of_pax);
    //             //   console.log(data_of_pax);
    //               var tourId = data_of_pax['tour_id'];
                
            
                
                
                
                
    //     $.ajax({    
    //         type: "POST",
    //         url: "{{URL::to('')}}/fetchflightrate",
    //         data:{
    //             "_token"                : "{{ csrf_token() }}",
    //             'tourId'                 : tourId,
             
    //         },
    //         success: function(data){
    //              var child_flight_price = data['child_flight_sale_price'];
    //              var infant_flight_price = data['infant_total_sale_price'];
    //              var adult_flight_price = JSON.parse(data['markup_details']);
    //              var adult_flight_price = adult_flight_price[0]['markup_price'];
    //             console.log(data);
    //             console.log(child_flight_price);
    //             console.log(infant_flight_price);
    //             console.log(adult_flight_price);
    //                     var cart = JSON.parse(data_of_pax['cart_total_data']);
    //                 console.log(cart);
    //      if(cart['double_adults']){
    //          var double_adult = cart['double_adults'];
    //          var adult_flight_price = adult_flight_price;
    //      }else{
    //          var double_adult = 0;
    //      }
    //      if(cart['triple_adults']){
    //          var triple_adult = cart['triple_adults'];
    //           var adult_flight_price = adult_flight_price;
    //      }else{
    //           var triple_adult = 0;
    //      }
    //      if(cart['quad_adults']){
    //          var quad_adult = cart['quad_adults'];
    //           var adult_flight_price = adult_flight_price;
    //      }else{
    //          var quad_adult = 0;
    //      }
    //      var adults = parseFloat(double_adult) + parseFloat(triple_adult) + parseFloat(quad_adult);
    //      var adult_total_price = adults * adult_flight_price;
    //      var single_adult_price = cart['sharing2'];
         
         
    //      if(cart['double_childs']){
    //          var double_child = cart['double_childs'];
    //           var child_flight_price = child_flight_price;
    //      }else{
    //          var double_child = 0;
    //      }
    //      if(cart['triple_childs']){
    //          var triple_child = cart['triple_childs'];
    //           var child_flight_price = child_flight_price;
    //      }else{
    //           var triple_child = 0;
    //      }
    //      if(cart['quad_childs']){
    //          var quad_child = cart['quad_childs'];
    //           var child_flight_price = child_flight_price;
    //      }else{
    //          var quad_child = 0;
    //      }
    //      var childs = parseFloat(double_child) + parseFloat(triple_child) + parseFloat(quad_child);
    //      var child_total_price = childs * child_flight_price;
    //      var single_child_price = cart['sharing2'];
         
         
    //      if(cart['double_infant']){
    //          var double_infant = cart['double_infant'];
    //          var infant_flight_price = infant_flight_price;
    //      }else{
    //          var double_infant = 0;
    //      }
    //      if(cart['triple_infant']){
    //          var triple_infant = cart['triple_infant'];
    //           var infant_flight_price = infant_flight_price;
    //      }else{
    //           var triple_infant = 0;
    //      }
    //      if(cart['quad_infant']){
    //          var quad_infant = cart['quad_infant'];
    //           var infant_flight_price = infant_flight_price;
    //      }else{
    //          var quad_infant = 0;
    //      }
    //      var infants = parseFloat(double_infant) + parseFloat(triple_infant) + parseFloat(quad_infant);
    //      var infant_total_price = infants * infant_flight_price;
    //      var single_infant_price = cart['sharing2'];
         
        
    //       var pax_data = ` <div class="p-1">
    //             <div class="modal-body px-3 pt-3 pb-0">
                  
    //             <div class="row">
                        
    //                     <div class="col-4">
    //                       <label for="tourId" class="form-label">Adult</label>
    //                       <input readonly name="flightId" class="form-control" value="${adults}" type="text"  >
    //                     </div>
    //                     <div class="col-4">
    //                       <label for="tourId" class="form-label">Adult Price</label>
    //                       <input readonly  class="form-control" value="${adult_flight_price}" type="text"  >
    //                     </div>
    //                     <div class="col-4">
    //                       <label for="tourId" class="form-label">Total Price</label>
    //                       <input readonly class="form-control" value="${adult_total_price}" type="text"  >
    //                     </div>
                      
                        
    //                     <div class="col-4">
    //                       <label for="tourId" class="form-label">Child</label>
    //                       <input readonly  class="form-control" value="${childs}" type="text"  >
    //                     </div>
    //                     <div class="col-4">
    //                       <label for="tourId" class="form-label">Child Price</label>
    //                       <input readonly  class="form-control" value="${child_total_price}" type="text"  >
    //                     </div>
    //                     <div class="col-4">
    //                       <label for="tourId" class="form-label">Total Price</label>
    //                       <input readonly  class="form-control" value="${child_total_price}" type="text"  >
    //                     </div>
                        
                        
    //                     <div class="col-4">
    //                       <label for="tourId" class="form-label">Infant</label>
    //                       <input readonly  class="form-control" value="${infants}" type="text"  >
    //                     </div>
    //                     <div class="col-4">
    //                       <label for="tourId" class="form-label">Infant Price</label>
    //                       <input readonly  class="form-control" value="${infant_total_price}" type="text"  >
    //                     </div>
    //                     <div class="col-4">
    //                       <label for="tourId" class="form-label">Total Price</label>
    //                       <input readonly  class="form-control" value="${infant_total_price}" type="text"  >
    //                     </div>

    //             </div>
                        
    //             </div>
    //         </div>`;
    //      $('.flight_payment_html').html(pax_data);
        
    //         }
    //     });
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
        
       
 
       
         
       
  
    // });
    
    
    
    //  $(".view_pax_info").on('click',function () {
                
    //             var data_of_pax = $(this).attr('pax_info_attr');
    //              data_of_pax = JSON.parse(data_of_pax);
    //             //  console.log(data_of_pax);
    //               var lead_info = data_of_pax['passenger_detail'];
    //              lead_info = JSON.parse(lead_info);
    //              lead_info = lead_info[0];
    //             console.log(lead_info);
                 
                 
    //               var other_adult_info = data_of_pax['adults_detail'];
    //               other_adult_info = JSON.parse(other_adult_info);
    //               other_adult_info = other_adult_info[0];
    //               console.log(other_adult_info);
                  
                  
    //           var info=`
                                  
    //                                 <tr>`
    //                                 if(lead_info){
                                        
    //                         info +=`<td>
    //                                 <p>Name: ${lead_info['lead_title']} ${lead_info['name']}${lead_info['lname']}</p>
    //                                 <p>E-mail: ${lead_info['email']}</p>
    //                                 <p>Country: ${lead_info['country']}</p>
    //                                 <p>Phone: ${lead_info['phone']}</p>
    //                                 <p>Gender: ${lead_info['gender']}</p>
    //                                 <p>Type: ${lead_info['passengerType']}</p>
                                    
    //                                 </td>`
    //                                 }else{
    //                         info +=`<td></td>`
    //                                 }
    //                                 if(other_adult_info){
                                        
                                        
 

    //                                     info +=`<td>
    //                                 <p>Name: ${other_adult_info['passengerName']}</p>
    //                                 <p>Gender: ${other_adult_info['gender']}</p>
    //                                 <p>Type: ${other_adult_info['passengerType']}</p>
    //                                 </td>`
    //                                 }else{
    //                                   info +=`<td></td>`  
    //                                 }
                                
    //                                 info +=`</tr>
                                   
    //                             `;
                
                
    //              $('.info_append').html(info);
    //              $('.dataTables_filter').hide();
    
       
  
    // });
        });
    </script>

@stop

@section('slug')

@stop
