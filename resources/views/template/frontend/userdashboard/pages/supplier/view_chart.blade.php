@extends('template/frontend/userdashboard/layout/default')
@section('content')
<?php

// dd($hotels);
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">







 <div id="website_modal" class="modal fade" tabindex="-1" style="display: none;" aria-hidden="true">
   <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="compose-header-modalLabel">WebSite Occupancy</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
             <div class="modal-body "> 
              <table class="table table-bordered table-striped  nowrap example1 dataTable no-footer" id="example1">
                            <thead>
                                <tr>
                                    <th>Lead Name</th>
                                    <th>Lead Contact</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th>CheckIn</th>
                                    <th>CheckOut</th>
                                   
                                </tr>
                            </thead>
                            <tbody class="booking_html">
                               
                                <!--<tr class="calendar-row single_append ">-->
                                    
                                   
                                <!--</tr>-->
                                <!--<tr class="calendar-row single_append_more">-->
                                    
                                   
                                <!--</tr>-->
                                        
                                        
                                        
                                <!--<tr class="calendar-row double_append">-->
                                          
                                <!--</tr>-->
                                
                                
                                <!--<tr class="calendar-row triple_append">-->
                                          
                                <!--</tr>-->
                                
                                <!--<tr class="calendar-row quad_append">-->
                                          
                                <!--</tr>-->
                                           
                                            </tbody>
                                            </table>
             </div>
           
        </div>
   </div>
</div><!-- /.modal -->
 <div id="admin_modal" class="modal fade" tabindex="-1" style="display: none;" aria-hidden="true">
   <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="compose-header-modalLabel">AdminSite Occupancy</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
             <div class="modal-body "> 
              <table class="table table-bordered table-striped  nowrap example1 dataTable no-footer" id="example1">
                            <thead>
                                <tr>
                                    <th>Lead Name</th>
                                    <th>Lead Contact</th>
                                    <th>Duration</th>
                                   
                                    <th>CheckIn</th>
                                    <th>CheckOut</th>
                                   
                                </tr>
                            </thead>
                            <tbody class="adminbooking_html">
                               
                                <!--<tr class="calendar-row single_append ">-->
                                    
                                   
                                <!--</tr>-->
                                <!--<tr class="calendar-row single_append_more">-->
                                    
                                   
                                <!--</tr>-->
                                        
                                        
                                        
                                <!--<tr class="calendar-row double_append">-->
                                          
                                <!--</tr>-->
                                
                                
                                <!--<tr class="calendar-row triple_append">-->
                                          
                                <!--</tr>-->
                                
                                <!--<tr class="calendar-row quad_append">-->
                                          
                                <!--</tr>-->
                                           
                                            </tbody>
                                            </table>
             </div>
           
        </div>
   </div>
</div><!-- /.modal -->
 <div id="package_modal" class="modal fade" tabindex="-1" style="display: none;" aria-hidden="true">
   <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="compose-header-modalLabel">PackageSite Occupancy</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
             <div class="modal-body "> 
              <table class="table table-bordered table-striped  nowrap example1 dataTable no-footer" id="example1">
                            <thead>
                                <tr>
                                    <th>PAX</th>
                                    <th>Duration</th>
                                    <th>Package</th>
                                   
                                    <th>CheckIn</th>
                                    <th>CheckOut</th>
                                   
                                </tr>
                            </thead>
                            <tbody class="packagebooking_html">
                               
                                <!--<tr class="calendar-row single_append ">-->
                                    
                                   
                                <!--</tr>-->
                                <!--<tr class="calendar-row single_append_more">-->
                                    
                                   
                                <!--</tr>-->
                                        
                                        
                                        
                                <!--<tr class="calendar-row double_append">-->
                                          
                                <!--</tr>-->
                                
                                
                                <!--<tr class="calendar-row triple_append">-->
                                          
                                <!--</tr>-->
                                
                                <!--<tr class="calendar-row quad_append">-->
                                          
                                <!--</tr>-->
                                           
                                            </tbody>
                                            </table>
             </div>
           
        </div>
   </div>
</div><!-- /.modal -->
 <div id="inhouse_modal" class="modal fade" tabindex="-1" style="display: none;" aria-hidden="true">
   <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="compose-header-modalLabel">InHouse Occupancy</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
             <div class="modal-body "> 
              <table class="table table-bordered table-striped  nowrap example1 dataTable no-footer" id="example1">
                            <thead>
                                <tr>
                                    <th>TYPE</th>
                                    <th>NAME</th>
                                    <th>PHONE</th>
                                    <th>BOOKING</th>
                                   
                                    <th>PAYMENT</th>
                                    <th>CheckIn</th>
                                    <th>CheckOut</th>
                                   
                                </tr>
                            </thead>
                            <tbody class="inhousebooking_html">
                               
                                
                                           
                                            </tbody>
                                            </table>
             </div>
           
        </div>
   </div>
</div><!-- /.modal -->
 <div id="checkIn_modal" class="modal fade" tabindex="-1" style="display: none;" aria-hidden="true">
   <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="compose-header-modalLabel">checkIn Occupancy</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
             <div class="modal-body"> 
             <div class="check_in_modal_append">
                 
             </div>
              
             </div>
           
        </div>
   </div>
</div><!-- /.modal -->
 <div id="checkOUT_modal" class="modal fade" tabindex="-1" style="display: none;" aria-hidden="true">
   <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="compose-header-modalLabel">checkOUT Occupancy</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
             <div class="modal-body "> 
            <div class="check_out_modal_append">
                 
             </div>
             </div>
           
        </div>
   </div>
</div><!-- /.modal -->
















    <div class="container-fluid">
        <div class="dashboard-page">
           
        </div>
        <br>
     
       
                <div class="panel container-fluid pt-5 pb-5">
                    <h1>Search</h1>
                    <div class="row">
                        
 
                                <div class="col">
                                    <lable>Hotel Name:</lable>
                                   <select class="form-control searched_hotel" required name="hotel_name:">
                                       <option value="" readonly>Select Hotel</option>
                                        @foreach($hotels as $hotels)
                                          <option value="{{$hotels->id ?? ""}}">{{$hotels->property_name ?? ""}}</option>
                                        @endforeach
                                        </select>
                                </div>
                                <div class="col">
                                    <lable>From:</lable>
                                    <input type="date" class="form-control from_date" required name="hotel_namem" placeholder="Makkah Hotel Name">
                                </div>
                                <div class="col">
                                    <lable>Upto & Including:</lable>
                                    <input type="date" class="form-control including_date" required name="hotel_namem" placeholder="Makkah Hotel Name">
                                </div>
                                <div class="col">
                                    <button class="btn btn-primary mt-3 search_btn"><i class="fas fa-search"></i></button>
                                </div>
                               
                                
                                
                    </div>
                    
                    <br>
                    <br>
                    
                    <div class="row">
                    
 
                                <div class="col-2">
                                    <div><button class="btn btn-primary  " style="background-color: #2861a7;"></button> Total Rooms</div>
                                </div>
                                <div class="col-2">
                                     <div><button class="btn btn-primary  " style="background-color: #a5a728;"></button> Remaining Rooms</div>
                                </div>
                                <div class="col-2">
                                     <div><button class="btn btn-primary  " style="background-color: #f10000;"></button> Website booked</div>
                                </div>
                                <div class="col-2">
                                     <div> <button class="btn btn-primary  " style="background-color: #4caf50;"></button> Admin booked</div>
                                </div>
                                <div class="col-2">
                                     <div><button class="btn btn-primary  " style="background-color: #c229c5;"></button> Package booked</div>
                                </div>
                                <div class="col-2">
                                     <div><button class="btn btn-primary  " style="background-color: #4cafaa;"></button> InHouse </div>
                                </div>
                                <div class="col-2">
                                     <div><button class="btn btn-primary  " style="background-color: #2196f3;"></button> checkout </div>
                                </div>
                                <div class="col-2">
                                     <div><button class="btn btn-primary  " style="background-color: #673ab7;"></button> checkin </div>
                                </div>
                                
                                
                                
                    </div>
                        
 
                  
                    <div class="container mt-5">
                        
                        
                         
                    <div class="row mx-0">
                    <div class="col-sm-2 px-0 check_col_hide">
                        <!--<table cellpadding="0" cellspacing="0" class="calendar table table-bordered table-striped">-->
                        <!--    <tbody>-->
                                
                        <!--        <tr class="calendar-row pb-auto">-->
                        <!--            <td class="calendar-day p-3" style="vertical-align: inherit !important;">-->
                        <!--            <div class="day-number">Dates</div></td>-->
                                    
                                    
                        <!--                </tr>-->
                                        
                                        
                                        
                                         
                        <!--                    <tr class="calendar-row append_for_supplier">-->
                                                
                                             
                                           
                        <!--                    </tr>-->
                        <!--                    <tr class="calendar-row append_for_supplier_more">-->
                                                
                                             
                                           
                        <!--                    </tr>-->
                                            
                                          
                                            
                        <!--                    </tbody>-->
                        <!--                    </table>-->
                    </div>
                    
                    <div class="col-sm-12 px-1">
                    <div style="width: 100% !important; overflow: scroll;" class="count_table">
                        <p id="lotti_loader"><iframe src="https://embed.lottiefiles.com/animation/101700"></iframe></p>
                        <table class="table table-bordered table-striped  nowrap example1 dataTable no-footer tableofcounts" id="example1">
                            <thead>
                                <tr class="dates_append">
                                   
                                </tr>
                            </thead>
                            <tbody class="single_append">
                               
                                <!--<tr class="calendar-row single_append ">-->
                                    
                                   
                                <!--</tr>-->
                                <!--<tr class="calendar-row single_append_more">-->
                                    
                                   
                                <!--</tr>-->
                                        
                                        
                                        
                                <!--<tr class="calendar-row double_append">-->
                                          
                                <!--</tr>-->
                                
                                
                                <!--<tr class="calendar-row triple_append">-->
                                          
                                <!--</tr>-->
                                
                                <!--<tr class="calendar-row quad_append">-->
                                          
                                <!--</tr>-->
                                           
                                            </tbody>
                                            </table>
                                            </div>      
                    

          
                       </div>
                              
                              </div>
                              </div>
        </div>
        <br>
       
    </div>
@endsection


<script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script>

 <script type="text/javascript">
               $(document).ready(function () {
                   
                    $('#lotti_loader').hide();
                //   $('.count_table').hide();
                    $('.search_btn').on('click',function(){  
                        
                      
                        
                    var hotel = $('.searched_hotel').val();
                    
            
                     if(hotel != ""){
                     
                        var startDate = $('.from_date').val();
                        
                        if(startDate !=""){
                           
                             var includingDate = $('.including_date').val();
                           
                             
                             if(includingDate !=''){
                                $('.search_btn').hide();
                                 $('.tableofcounts').hide();
                                 $('#lotti_loader').show();
                              
                                 var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                                 
                                $.ajax({
                                     url :  '{{URL::to('chart_data')}}',
                                     type: 'POST',
                                    data: {_token: CSRF_TOKEN,
                                  "hotel": hotel,
                                  "startDate": startDate,
                                  "includingDate": includingDate
                                          },
                               
                            success: function(response){  
                                 $('.search_btn').show();
                                 $('#lotti_loader').hide();
                              
                               
                                var rooms_records = response['rooms_records'];
                                console.log(rooms_records);
                            
                                var date = response['dates'];
                                //   console.log(date);
                                  
                                  
                                $('.dates_append').html("");
                                $('.single_append').html("");
                                $('.single_suplier').html("");
                                $('.double_append').html("");
                                $('.double_suplier').html("");
                                $('.single_append_more').html("");
                                $('.triple_append').html("");
                                $('.quad_append').html("");
                                // $('.append_for_supplier_more').html("");
                               
                                //  to make dates
                                var th =`<th style='padding: 37px;'>Dates</th>`;
                                for (var i = 0; i < date.length; i++) {
      
        			    		      th +=`<th>${date[i]}</th>`;
       		    			   
                            	}
                            	$('.dates_append').append(th);
                            
                            	//room
                            	var tr = ``;
                                    rooms_records.forEach((sup)=>{
                                        var admin_count = 0;
                                        var website_count = 1000;
                                        var package_count = 2000;
                                        var Inhouse_count = 3000;
                                        var checkIn_count = 4000;
                                        var checkout_count = 5000;
                                        sup['rooms_data'].forEach((roomsTypeData)=>{
                                            tr += `<tr>`;
                                            tr +=`<td>
                                                    <div class='row' style='background-color: #363b42;border-radius: 6px;height: 3rem;'>
                                            			    		     
                                            			    		     <div class='col-6' style=''>
                                            			    		     <span class='badge supplier_span' title='Supplier Name' style='background-color: #2861a7;'>${sup['supplier_record']}</span>
                                            			    		    
                                            			    		     </div>
                                            			    <div class='col-6'>
                                            			    		  <div class='row'>
                                            			    		  
                                            			    		      <div class='col-12' style='margin-top: 25px;margin-left: -40px;'>
                                            			    		      <span class='badge' title='RoomType' style='background-color: #4caf50;'>${roomsTypeData['type']}</span>
                                            			    		      </div>
                                            			    		      
                                            			    		    
                                            			    		
                                            			    		  </div>
                                            			    </div>
                                    	    		 </div>
                                                
                                                    </td>`;
                                             roomsTypeData['rooms_record'].forEach((roomsTypeDates)=>{
                                                    tr +=`<td>
                                                            <div style='background-color: #363b42;padding: 25px 7px;position: relative;border-radius: 6px;'>
                                                              <span class='badge' title='Remaining Room' style='background-color: #a5a728;/* padding: 19px; */position: absolute;top: 0;left: 68;'>${roomsTypeDates['remaining_room']}</span>
                                                              <span class='badge booking_span_${checkout_count}' current_date='${roomsTypeDates['date']}' booking_type='${'checkOUT'}' booking_suplier = '${sup['supplier_id']}' roomid='${roomsTypeDates['room_type_id']}' title='CheckOUT' style='background-color: #2196f3;padding: 4px;position: absolute;right: 49px;top: 0px;' `;
                                                                if(roomsTypeDates['total_checkOUT_count']>0){
                                                                    tr +=`onclick='myFunction(${checkout_count++})'`;
                                                                }
                                                              tr +=`data-bs-toggle='modal' data-bs-target='#checkOut_modal'>${roomsTypeDates['total_checkOUT_count']}</span>
                                                              <span class='badge booking_span_${checkIn_count}' current_date='${roomsTypeDates['date']}' booking_type='${'checkIn'}' booking_suplier = '${sup['supplier_id']}' roomid='${roomsTypeDates['room_type_id']}' title='CheckIn' style='background-color: #673ab7;padding: 4px;position: absolute;right: 27px;top: 0px;' `;
                                                                if(roomsTypeDates['total_checkIN_count']>0){
                                                                    tr +=`onclick='myFunction(${checkIn_count++})'`;
                                                                }
                                                              tr +=`data-bs-toggle='modal' data-bs-target='#checkIn_modal'>${roomsTypeDates['total_checkIN_count']}</span>
                                                              <span class='badge' title='Total Available' style='background-color: #2861a7;/* padding: 19px; */position: absolute;top: 0;left: 5;' >${roomsTypeDates['total_rooms']}</span>
                                                              
                                                              <span class='badge booking_span_${website_count}' current_date='${roomsTypeDates['date']}' booking_type='${'website'}' booking_suplier = '${sup['supplier_id']}' roomid='${roomsTypeDates['room_type_id']}' title='WEB-Booking' style='background-color: #f10000;/* padding: 19px; */position: absolute;right: 5;' `;
                                                                if(roomsTypeDates['total_website_booked']>0){
                                                                    tr +=`onclick='myFunction(${website_count++})'`;
                                                                }
                                                              tr +=`data-bs-toggle='modal' data-bs-target='#website_modal'>${roomsTypeDates['total_website_booked']}</span>
                                                              
                                                              <span class='badge booking_span_${admin_count}' current_date='${roomsTypeDates['date']}' booking_type='${'adminsite'}' booking_suplier = '${sup['supplier_id']}' roomid='${roomsTypeDates['room_type_id']}' title='Admin-Booking' style='background-color: #4caf50;/* padding: 19px; */position: absolute;right: 26' `;
                                                                if(roomsTypeDates['total_admin_booked']>0){
                                                                    tr +=`onclick='myFunction(${admin_count++})'`;
                                                                }
                                                              tr +=`data-bs-toggle='modal' data-bs-target='#admin_modal'>${roomsTypeDates['total_admin_booked']}</span>
                                                              
                                                                <span class='badge booking_span_${package_count}' current_date='${roomsTypeDates['date']}' booking_type='${'pacsite'}' booking_suplier = '${sup['supplier_id']}' roomid='${roomsTypeDates['room_type_id']}' title='Package-Booking' style='background-color: #c229c5;/* padding: 19px; */position: absolute; left: 6px;' `;
                                                                if(roomsTypeDates['total_package_booked']>0){
                                                                    tr +=`onclick='myFunction(${package_count++})'`;
                                                                }
                                                              tr +=`data-bs-toggle='modal' data-bs-target='#package_modal'>${roomsTypeDates['total_package_booked']}</span>
                                                              
                                                              <span class='badge booking_span_${Inhouse_count}' current_date='${roomsTypeDates['date']}' booking_type='${'inhouse'}' booking_suplier = '${sup['supplier_id']}' roomid='${roomsTypeDates['room_type_id']}'  title='In House' style='background-color: #4cafaa;padding: -1px;position: absolute;right: 47;' `;
                                                                if(roomsTypeDates['total_Inhouse']>0){
                                                                    tr +=`onclick='myFunction(${Inhouse_count++})'`;
                                                                }
                                                              tr +=`data-bs-toggle='modal' data-bs-target='#inhouse_modal'>${roomsTypeDates['total_Inhouse']}</span>
                                                              
                              
                                                            </div>
                                                    </td>`;
                                                    
                                                    
                                             })
                                            
                                            tr +=`</tr>`;
                                        })
                                        
                                    })
                                    
                                     console.log(tr);
                 $('.single_append').html(tr);
                           
                        
   
                            	 $('.check_col_hide').show();
                            	$('.count_table').show();
                            	$('.tableofcounts').show();
                            	
                                    
                              }
                                });
                             }else{
                                 alert("Upto Date Field is Empty.Kindly Fill It");
                             }
                        }else{
                          alert("From Date Field is Empty.Kindly Fill It");  
                        }
                     }else{
                          alert("Hotel Field is Empty.Kindly Fill It");
                     }
                     
                     
                    
                     $('.website_booking_span').on('click',function(){
                     alert("open");
                     
                     
                     });
                     

  });

               });
                 function myFunction(inc) {
                 $('.booking_html').html("");
                 $('.adminbooking_html').html("");
                 $('.packagebooking_html').html("");
                 $('.inhousebooking_html').html("");
                 $('.check_in_modal_append').html("");
                 $('.check_out_modal_append').html("");
                    $('.booking_html').html("<h2>Wait!</h2>");
                    $('.adminbooking_html').html("<h2>Wait!</h2>");
                    $('.packagebooking_html').html("<h2>Wait!</h2>");
                    $('.inhousebooking_html').html("<h2>Wait!</h2>");
                    $('.check_in_modal_append').html("<h2>Wait!</h2>");
                    $('.check_out_modal_append').html("<h2>Wait!</h2>");
                    var Id = $('.booking_span_'+inc).attr('booking_id');
                    var type = $('.booking_span_'+inc).attr('booking_type');
                    var suplier = $('.booking_span_'+inc).attr('booking_suplier');
                    var roomid = $('.booking_span_'+inc).attr('roomid');
                    var current_date = $('.booking_span_'+inc).attr('current_date');
                    var hotel = $('.searched_hotel').val();
                 
                    var startDate = $('.from_date').val();
                    var includingDate = $('.including_date').val();
                    // $('#booking_modal').show();
                            $.ajax({
                                     url :  '{{URL::to('getbooking')}}',
                                     type: 'POST',
                                    data: {
                                        '_token' : '{{ CSRF_token() }}',
                                        'currentDate': Id,
                                        'startDate': startDate,
                                        'includingDate': includingDate,
                                        'suplier': suplier,
                                        'roomid': roomid,
                                        'hotel': hotel,
                                        'type': type,
                                        'current_date': current_date,
                                          },
                                // dataType: 'json',
                            success: function(response){
                
                                 $('.booking_html').html("");
                                 $('.adminbooking_html').html("");
                                 $('.packagebooking_html').html("");
                                 $('.inhousebooking_html').html("");
                                 $('.check_in_modal_append').html("");
                                 $('.check_out_modal_append').html("");
                                  
                                var data = JSON.parse(response);
                                
                                var bookings = data['record'];
                               
                                var type = data['type'];
                               
                             
                                    if(type == "website"){
                                        for (var book = 0; book < bookings.length; book++) {
                                //   console.log(bookings[book]);
                                  var payment_status = bookings[book]['payment_status'];
                                  var booking_status = bookings[book]['booking_status'];
                                  var hotel_search_rq = bookings[book]['hotel_search_rq'];
                                  hotel_search_rq = JSON.parse(hotel_search_rq);
                                  var lead_passenger_details = bookings[book]['lead_passenger_details'];
                                  lead_passenger_details = JSON.parse(lead_passenger_details);
                                //   console.log(hotel_search_rq);
                                //   console.log(lead_passenger_details);
                                 
                                  
                                  
                                  if(lead_passenger_details){
                                     var Lead_name = lead_passenger_details['lead_first_name'];
                                     var Lead_gender = lead_passenger_details['lead_title'];
                                     var Lead_phone = lead_passenger_details['lead_phone'];
                                  }else{
                                      var Lead_name = 'N/A';
                                      var Lead_gender = 'N/A';
                                      var Lead_phone = 'N/A';
                                  }
                                  
                                  
                                  if(payment_status == 0){
                                      var status = "Not paid yet!"
                                  } 
                                  if(booking_status){
                                  if(booking_status == 0){
                                      var bookingstatus = "Not Confirmed!"
                                  }
                                  }else{
                                       var bookingstatus = "Pendding!"
                                  }
                                  
                                  
                            
                                  
                        
                                  
                                  
                var booking =`<tr>
                            
                           <td>${Lead_gender}${Lead_name} </td>
                           <td>${Lead_phone} </td>
                           <td>${bookingstatus} </td>
                           <td>${status} </td>
                           <td>${bookings[book]['check_in']} </td>
                           <td>${bookings[book]['check_out']} </td>
                </tr>`;
                             $('.booking_html').append(booking);
                      
                              }
                                    }
                                    
                                    if(type == "adminsite"){
                                       console.log(bookings);
                                        for (var books = 0; books < bookings.length; books++) {
                                 
                                  
                                 
                                 
                                 
                                  if(bookings[books]){
                                     var Lead_name = bookings[books]['f_name'];
                                     var Lead_lname = bookings[books]['middle_name'];
                                     var Lead_gender = bookings[books]['gender'];
                                         
                                        //  console.log(Lead_gender);
                                         if(Lead_gender == 'male'){
                                             Lead_gender = 'Mr.';
                                         }else{
                                               Lead_gender = 'Mrs.';
                                         }
                                     var Lead_phone = bookings[books]['mobile'];
                                  }else{
                                      var Lead_name = 'N/A';
                                      var Lead_gender = 'N/A';
                                      var Lead_phone = 'N/A';
                                  }
                                  
                                  
                                  if(payment_status == 0){
                                      var status = "Not paid yet!"
                                  } 
                                  if(booking_status){
                                  if(booking_status == 0){
                                      var bookingstatus = "Not Confirmed!"
                                  }
                                  }else{
                                       var bookingstatus = "Pendding!"
                                  }
                                  
                                  
                            
                                  
                        
                                  
                                  
                var booking =`<tr>
                            
                           <td>${Lead_gender}${Lead_name}${Lead_lname} </td>
                           <td>${Lead_phone} </td>
                           
                           <td>${bookings[books]['time_duration']} </td>
                           <td>${bookings[books]['start_date']} </td>
                           <td>${bookings[books]['end_date']} </td>
                </tr>`;
                             $('.adminbooking_html').append(booking);
                      
                              }
                                    }
                                    
                                    if(type == "pacsite"){
                                        // alert(type);
                                        for (var bookz = 0; bookz < bookings.length; bookz++) {
                                            console.log(bookings[bookz]);
                                 
                                  
                                 
                                
                                 
                                  if(bookings[bookz]){
                                     var no_of_pax_days = bookings[bookz]['no_of_pax_days'];
                                     var time_duration = bookings[bookz]['time_duration'];
                                  }else{
                                      var no_of_pax_days = 'N/A';
                                      var time_duration = 'N/A';
                                  }
                                  
                                  
                                  
                                  
                                  
                            
                                  
                        
                                  
                                  
                var booking =`<tr>
                            
                           <td>${no_of_pax_days} </td>
                           <td>${time_duration} </td>
                           
                           <td>${bookings[bookz]['title']} </td>
                           <td>${bookings[bookz]['start_date']} </td>
                           <td>${bookings[bookz]['end_date']} </td>
                </tr>`;
                             $('.packagebooking_html').append(booking);
                      
                              }
                                    }
                                    
                                    if(type == "inhouse"){
                                        for (var inbook = 0; inbook < bookings.length; inbook++) {
                                  var inerbookings = bookings[inbook];
                                  console.log(bookings[inbook]);
                                  for (var inerbook = 0; inerbook < inerbookings.length; inerbook++) {
                                 if(inerbookings[inerbook]['provider'] == 'hotels'){
                                 
                                //   alert("web");
                                  
                                   var payment_status = inerbookings[inerbook]['payment_status'];
                                  var booking_status = inerbookings[inerbook]['booking_status'];
                                  var hotel_search_rq = inerbookings[inerbook]['hotel_search_rq'];
                                  hotel_search_rq = JSON.parse(hotel_search_rq);
                                  var lead_passenger_details = inerbookings[inerbook]['lead_passenger_details'];
                                  lead_passenger_details = JSON.parse(lead_passenger_details);
                                //   console.log(hotel_search_rq);
                                //   console.log(lead_passenger_details);
                                 
                                  
                                  
                                  if(lead_passenger_details){
                                     var Lead_name = lead_passenger_details['lead_first_name'];
                                     var Lead_gender = lead_passenger_details['lead_title'];
                                     
                                     var Lead_phone = lead_passenger_details['lead_phone'];
                                  }else{
                                      var Lead_name = 'N/A';
                                      var Lead_gender = 'N/A';
                                      var Lead_phone = 'N/A';
                                  }
                                  
                                  
                                  if(payment_status == 0){
                                      var status = "Not paid yet!"
                                  } 
                                  if(booking_status){
                                  if(booking_status == 0  || booking_status == null){
                                      var bookingstatus = "Not Confirmed!"
                                  }else{
                                       var bookingstatus = "!"
                                  }
                                  }else{
                                       var bookingstatus = "!"
                                  }
                                  
                                  
                            
                                  
                        
                                  
                                  
                var booking =`<tr>
                            
                           <td>Website</td>
                           <td>${Lead_gender}${Lead_name} </td>
                           <td>${Lead_phone} </td>
                           <td>${bookingstatus} </td>
                           <td>${status} </td>
                           <td>${inerbookings[inerbook]['check_in']} </td>
                           <td>${inerbookings[inerbook]['check_out']} </td>
                </tr>`;
                             $('.inhousebooking_html').append(booking);
                      
                                  
                                  
                                 }
                                 
                                 
                                 if(inerbookings[inerbook]['services']){
                                 
                                //   alert("admin");
                                   
                                  
                                 
                                
                                 
                                  if(inerbookings[inerbook]){
                                     var Lead_name = inerbookings[inerbook]['f_name'];
                                     var Lead_lname = inerbookings[inerbook]['middle_name'];
                                     var Lead_gender = inerbookings[inerbook]['gender'];
                                         
                                        //  console.log(Lead_gender);
                                         if(Lead_gender == 'male'){
                                             Lead_gender = 'Mr.';
                                         }else{
                                               Lead_gender = 'Mrs.';
                                         }
                                     var Lead_phone = inerbookings[inerbook]['mobile'];
                                  }else{
                                      var Lead_name = 'N/A';
                                      var Lead_gender = 'N/A';
                                      var Lead_phone = 'N/A';
                                  }
                                  
                                  
                                  if(payment_status){
                                      var status = "Not paid yet!";
                                  }else{
                                       var status = "!";
                                  }
                                  
                                  if(booking_status){
                                  if(booking_status == 0 ||booking_status == null){
                                      var bookingstatus = "Not Confirmed!"
                                  }else{
                                       var bookingstatus = "!"
                                  }
                                  }else{
                                       var bookingstatus = "!"
                                  }
                                 
                                  
                                  
                            
                                  
                        
                                  
                                  
                var booking =`<tr>
                            
                           <td>Adminsite</td>
                           <td>${Lead_gender}${Lead_name}${Lead_lname} </td>
                           <td>${Lead_phone} </td>
                           
                           <td>${bookingstatus} </td>
                           <td>${status} </td>
                           <td>${inerbookings[inerbook]['start_date']} </td>
                           <td>${inerbookings[inerbook]['end_date']} </td>
                </tr>`;
                             $('.inhousebooking_html').append(booking);
                      
                                  
                                 }
                                  }
                                  
                                  
                                  
                                 
                                 
                                  
                            
                                  
                        
                                  
        
                      
                              }
                                    }
                                    
                                     if(type == "checkIn"){
                                    //   console.log(bookings);
                                     var whole_booking =`<table class="table table-bordered table-striped  nowrap example1 dataTable no-footer" id="example1">
                            <thead>
                                <tr>
                                    
                                    <th>TYPE</th>
                                    <th>NAME</th>
                                    <th>PHONE</th>
                                    <th>DURATION</th>
                                    <th>CheckIn</th>
                                    <th>CheckOut</th>
                                   
                                </tr>
                            </thead>
                            <tbody>`;
                                        for (var datazz = 0; datazz < bookings.length; datazz++) {
                                           var keydata = bookings[datazz];
                                          
                            
                            
                            
                            
                            
                                            console.log(bookings[datazz]['data']);
                                           if(bookings[datazz]['type'] == 'website'){
                                               
                                                 for (var booka = 0; booka < keydata['data'].length; booka++) {
                                                 console.log(keydata['data'][booka]);
                                                 console.log('in admin');
                                                 console.log('printing table');
                                  if(keydata['data'][booka]['lead_title'] =="male"){
                                         var gender = "Mr.";
                                     }
                            
                               whole_booking +=`<tr>
                                    <td>Website</td>
                                    <td>${gender}${keydata['data'][booka]['lead_first_name']}</td>
                                    <td>${keydata['data'][booka]['lead_phone']}</td>
                                    <td>N/A </td>
                                    <td>${keydata['data'][booka]['check_in']} </td>
                                    <td>${keydata['data'][booka]['check_out']} </td>
                                   
                               </tr>`;
                                
                                           
                                           
                                  
                    
                                  
                                  
               
                            
                      
                              }  
                                            
                                     
                                           }
                                           
                                           
                                           
                                           if(bookings[datazz]['type'] == 'admin'){
                                               
                                            
                                            for (var booka = 0; booka < keydata['data'].length; booka++) {
                                                 console.log(keydata['data'][booka]);
                                                 console.log('in admin');
                                                 console.log('printing table');
                                   if(keydata['data'][booka]['gender'] =="male"){
                                         var gender = "Mr.";
                                     }
                            
                               whole_booking +=`<tr>
                                    <td>Admin</td>
                                    <td>${gender}${keydata['data'][booka]['f_name']}${keydata['data'][booka]['middle_name']}</td>
                                    <td>${keydata['data'][booka]['mobile']}</td>
                                    <td>${keydata['data'][booka]['time_duration']} </td>
                                    <td>${keydata['data'][booka]['start_date']} </td>
                                    <td>${keydata['data'][booka]['end_date']} </td>
                                   
                               </tr>`;
                                
                                           
                                           
                                  
                    
                                  
                                
               
                            
                      
                              }
                              
                                           }
                                           
                                           
                                           
                                    if(bookings[datazz]['type'] == 'package'){
                                               
                                        for (var bookp = 0; bookp < keydata['data'].length; bookp++) {
                                                 console.log(keydata['data'][booka]);
                                                 console.log('in admin');
                                                 console.log('printing table');
                                  
                                  
                               
                
                            
                              whole_booking +=`<tr>
                              <td>Package</td>
                                    <td>${keydata['data'][bookp]['title']}(Package)</td>
                                    <td>N/A</td>
                                    <td>${keydata['data'][bookp]['time_duration']} </td>
                                    <td>${keydata['data'][bookp]['start_date']} </td>
                                    <td>${keydata['data'][bookp]['end_date']} </td>
                                   
                              </tr>`;
                                
                                           
                                           
                                  
                    
                                  
                                  
               
                           
                      
                              }  
                                            
                                    }
                                        
                                        
                                         
                                        
                                    }
                                     whole_booking +=`</tbody>
                                            </table>`;
                                     $('.check_in_modal_append').append(whole_booking);
                            }
                            
                                     if(type == "checkOUT"){
                                    //   console.log(bookings);
                                     var whole_booking =`<table class="table table-bordered table-striped  nowrap example1 dataTable no-footer" id="example1">
                            <thead>
                                <tr>
                                    
                                    <th>TYPE</th>
                                    <th>NAME</th>
                                    <th>PHONE</th>
                                    <th>DURATION</th>
                                    <th>CheckIn</th>
                                    <th>CheckOut</th>
                                   
                                </tr>
                            </thead>
                            <tbody>`;
                                        for (var datazz = 0; datazz < bookings.length; datazz++) {
                                           var keydata = bookings[datazz];
                                          
                            
                            
                            
                            
                            
                                            console.log(bookings[datazz]['data']);
                                           if(bookings[datazz]['type'] == 'website'){
                                               
                                                 for (var booka = 0; booka < keydata['data'].length; booka++) {
                                                 console.log(keydata['data'][booka]);
                                                 console.log('in admin');
                                                 console.log('printing table');
                                  
                            
                               whole_booking +=`<tr>
                                    <td>Website</td>
                                    <td>${keydata['data'][booka]['lead_title']}${keydata['data'][booka]['lead_first_name']}</td>
                                    <td>${keydata['data'][booka]['lead_phone']}</td>
                                    <td>N/A </td>
                                    <td>${keydata['data'][booka]['check_in']} </td>
                                    <td>${keydata['data'][booka]['check_out']} </td>
                                   
                               </tr>`;
                                
                                           
                                           
                                  
                    
                                  
                                  
               
                            
                      
                              }  
                                            
                                     
                                           }
                                           
                                           
                                           
                                           if(bookings[datazz]['type'] == 'admin'){
                                               
                                            
                                            for (var booka = 0; booka < keydata['data'].length; booka++) {
                                                 console.log(keydata['data'][booka]);
                                                 console.log('in admin');
                                                 console.log('printing table');
                                  
                            
                               whole_booking +=`<tr>
                                    <td>Admin</td>
                                    <td>${keydata['data'][booka]['gender']}${keydata['data'][booka]['f_name']}${keydata['data'][booka]['middle_name']}</td>
                                    <td>${keydata['data'][booka]['mobile']}</td>
                                    <td>${keydata['data'][booka]['time_duration']} </td>
                                    <td>${keydata['data'][booka]['start_date']} </td>
                                    <td>${keydata['data'][booka]['end_date']} </td>
                                   
                               </tr>`;
                                
                                           
                                           
                                  
                    
                                  
                                  
               
                            
                      
                              }
                              
                                           }
                                           
                                           
                                           
                                    if(bookings[datazz]['type'] == 'package'){
                                               
                                        for (var bookp = 0; bookp < keydata['data'].length; bookp++) {
                                                 console.log(keydata['data'][booka]);
                                                 console.log('in admin');
                                                 console.log('printing table');
                                  
                                  
                               
                
                            
                               whole_booking +=`<tr>
                               <td>Package</td>
                                    <td>${keydata['data'][bookp]['title']}(Package)</td>
                                    <td>N/A</td>
                                    <td>${keydata['data'][bookp]['time_duration']} </td>
                                    <td>${keydata['data'][bookp]['start_date']} </td>
                                    <td>${keydata['data'][bookp]['end_date']} </td>
                                   
                               </tr>`;
                                
                                           
                                           
                                  
                    
                                  
                                  
               
                           
                      
                              }  
                                            
                                    }
                                        
                                        
                                         
                                        
                                    }
                                     whole_booking +=`</tbody>
                                            </table>`;
                                     $('.check_out_modal_append').append(whole_booking);
                            }
                            }
   
                });  
                 }
                 
            </script>









