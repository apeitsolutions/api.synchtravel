@extends('template/frontend/userdashboard/layout/default')
@section('content')

    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Tours</a></li>
                                    <li class="breadcrumb-item active">View Tours</li>
                                </ol>
                            </div>
                            <h4 class="page-title">View Tours</h4>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                               
                                <div  class="dataTables_filter mb-2">
                                    <input class="form" id="myInput" type="text" placeholder="Search..">
                                </div>
                                
                                <div class="table-responsive">
                                    <div id="example_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                        <table class="table nowrap table-striped table-vcenter dataTable no-footer" id="my_Table" role="grid" aria-describedby="example_info">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="text-align: center;">Sr No.</th>
                                                    <th style="text-align: center;">Customer Name</th>
                                                    <th style="text-align: center;">Package ID</th>
                                                    <th style="text-align: center;">No of Pax</th>
                                                    <th style="text-align: center;">Package Details</th>
                                                    <th style="text-align: center;display:none">Pax Details</th>
                                                    <th style="text-align: center;">Author</th>
                                                    <th style="text-align: center;display:none">Booking Status</th>
                                                    <th style="text-align: center;">Status</th>
                                                    <th style="width: 85px;text-align: center;display:none">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody style="text-align:center;" id="myTable">
                                                <?php
                                                    $i          = 1;
                                                    $tours_ids  = [];
                                                ?>
                                                @foreach ($data as $tours)
                                                    <?php
                                                        // dd($data);
                                                        $flightid       = $tours['flight_id'] ?? "";
                                                        $generate_id    = $tours['generate_id'];
                                                        $id             = $tours['id'];
                                                        $book_status    = $tours['book_status'];
                                                        $tour_publish   = $tours['tour_publish'];
                                                        $type           = 'tour';
                                                        $customer_id    = $tours['customer_id'];
                                                        array_push($tours_ids,$id);
                                                    ?>
                                                    
                                                    <tr>
                                                        <td>{{ $i }}</td>
                                                        <td>
                                                            @if(isset($all_Users) && $all_Users != null && $all_Users != '')
                                                                @foreach($all_Users as $all_Users_value)
                                                                    @if($customer_id == $all_Users_value->id)
                                                                        <b>{{ $all_Users_value->name }}</b>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                        <td>{{ $id }}</td>
                                                        <td>
                                                            <div><?php echo $tours['title']; ?><br><?php echo '<b>'.'Location : '.'</b>'.$tours['tour_location']; ?></div>
                                                            <?php echo date('d-m-Y',strtotime($tours['start_date'])); ?>
                                                            to 
                                                            <?php echo date('d-m-Y',strtotime($tours['end_date']));; ?>
                                                        </td>
                                                        <td>
                                                            @if(isset($tours['no_of_pax_days']))
                                                                <?php echo $tours['no_of_pax_days']; ?>
                                                            @else
                                                                <?php echo '0' ?>
                                                            @endif
                                                        </td>
                                                        <td style="display:none">
                                                            <p id="tour_pax_{{ $id }}"></p>
                                                        </td>
                                                        <td><?php echo $tours['tour_author']; ?></td>                                                
                                                        @if($book_status)
                                                            <td style="display:none">
                                                                <!--<span class="badge bg-success" style="font-size: 15px;margin-bottom: 5px;">Tour Booked</span>-->
                                                                <span class="badge bg-info" style="font-size: 15px" onclick="booked_tour_span({{ $id }})" id="booked_tour_span_{{ $id }}" data-id="{{$id}}" data-bs-toggle="modal" data-bs-target="#booked-tour-span">
                                                                    View Booked Tours
                                                                </span>
                                                            </td>
                                                        @else
                                                            <td style="display:none"><span class="badge btn-danger" style="font-size: 15px">Not Booked yet</span></td>
                                                        @endif
                                                        @if($tour_publish == 0)
                                                          <td><a href="{{URL::to('super_admin/disable_tour')}}/{{$id}}"><span class="badge bg-success" style="font-size: 15px">Enable</span></a></td>
                                                        @else
                                                          <td><a href="{{URL::to('super_admin/enable_tour')}}/{{$id}}"><span class="badge btn-danger" style="font-size: 15px">Disable</span></a></td>
                                                        @endif
                                                        <td style="display:none">
                                                            <div class="dropdown card-widgets">
                                                                <a style="float: right;" href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <i class="dripicons-dots-3"></i>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-end" style="">
                                                                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#alert-modal" onclick="edit_function({{ $id }})">Edit Tour</a>
                                                                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#alert-modal" onclick="delete_function({{ $id }},{{ $generate_id }},{{ $flightid }})">Delete Tour</a>
                                                                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#alert-modal" onclick="view_Tour_Revenue_function({{ $id }})">View Tour Revenue</a>
                                                                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#alert-modal" onclick="booking_allocation_function({{ $id }})">Booking Allocations</a>
                                                                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#alert-modal" onclick="book_Now_function({{ $id }})">Book Now</a>
                                                                    
                                                                    <a id="occupancy_tour_ID_{{ $id }}" onclick="occupancy_tour_ID({{ $id }})" data-id="{{ $id }}" value="{{ $id }}" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#Occupancy-hover">Occupancy</a>
                                                                    <a id="view_Tour_PopUp_{{ $id }}" onclick="view_Tour_PopUp1({{ $id }},'{{ $type }}')"  data-id="{{ $id }}" class="dropdown-item"  data-bs-toggle="modal" data-bs-target="#view-Tour-PopUp">View Tour</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php $i++; ?>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>      
        </section>
    </div>

@endsection

@section('scripts')

    <script>
        function edit_function(id){
            var input_url  = `{{ URL::to('super_admin/edit_tour')}}`;
            $('#info_alert_modal_div').empty();
            var data = `<h4 class="mt-2">Are you sure you want to Edit this Tour?</h4>
                        <a href="${input_url}/${id}" class="btn btn-info my-2" type="button">Yes</a>
                        <button type="button" class="btn btn-info my-2" data-bs-dismiss="modal">No</button>`;
            $('#info_alert_modal_div').append(data);
        }
        
        function delete_function(id,generate_id,flightid){
            var input_url  = `{{ URL::to('super_admin/delete_tour')}}`;
            $('#info_alert_modal_div').empty();
            var data = `<h4 class="mt-2">Are you sure you want to Delete this Tour?</h4>
                        <a href="${input_url}/${id}/${generate_id}/${flightid}" class="btn btn-info my-2" type="button">Yes</a>
                        <button type="button" class="btn btn-info my-2" data-bs-dismiss="modal">No</button>`;
            $('#info_alert_modal_div').append(data);
        }
        
        function view_Tour_Revenue_function(id){
            var input_url  = `{{ URL::to('tour_revenue')}}`;
            $('#info_alert_modal_div').empty();
            var data = `<h4 class="mt-2">Are you sure you want to View Tour Revenue?</h4>
                        <a href="${input_url}/${id}" class="btn btn-info my-2" type="button">Yes</a>
                        <button type="button" class="btn btn-info my-2" data-bs-dismiss="modal">No</button>`;
            $('#info_alert_modal_div').append(data);
        }
        
        function booking_allocation_function(id){
            var input_url  = `{{ URL::to('super_admin/booking_allocations')}}`;
            $('#info_alert_modal_div').empty();
            var data = `<h4 class="mt-2">Are you sure you want to View Booking Allocations?</h4>
                        <a href="${input_url}/${id}" class="btn btn-info my-2" type="button">Yes</a>
                        <button type="button" class="btn btn-info my-2" data-bs-dismiss="modal">No</button>`;
            $('#info_alert_modal_div').append(data);
        }
        
        function book_Now_function(id){
            var input_url  = `{{ URL::to('view_detail1')}}`;
            $('#info_alert_modal_div').empty();
            var data = `<h4 class="mt-2">Are you sure you want to Book this Tour?</h4>
                        <a href="${input_url}/${id}" class="btn btn-info my-2" type="button">Yes</a>
                        <button type="button" class="btn btn-info my-2" data-bs-dismiss="modal">No</button>`;
            $('#info_alert_modal_div').append(data);
        }
    </script>

    <script>
    
            var TourIds = '<?php echo json_encode($tours_ids); ?>'
            TourIds = JSON.parse(TourIds);
            
            
            TourIds.forEach((id)=>{
                console.log(id);
                
                 $.ajax({
                    url: 'tours_booking_pax_details/'+id,
                    type: 'GET',
                    data: {
                        "id": id
                    },
                    success:function(data) {
                        var ApaxDetails = JSON.parse(data);
                        
                        var paxHtml = `Adutls: ${ApaxDetails['adults']} Childs: ${ApaxDetails['childs']} Infants: ${ApaxDetails['infants']}
                                        <ul style="font-size:font-size: 12px;">
                                            <li>${ApaxDetails['double_space']} space available in 2 Sharing.</li>
                                            <li>${ApaxDetails['triple_space']} space available in 3 Sharing.</li>
                                            <li>${ApaxDetails['quad_space']} space available in 4 Sharing.</li>
                                        </ul>`;
                        var tour_id = ApaxDetails['tour_id']
                        $('#tour_pax_'+tour_id+'').html(paxHtml);
                        console.log(ApaxDetails);
                    }
                })
            });
         
            //More Tour Details
            function booked_tour_span(id){
                $('#booked_tour_span').empty();
                $('#booking_data_append_div').empty();
                $('#booked-tour-span').css('padding-left','50px');
                
                const ids = $('#booked_tour_span_'+id+'').attr('data-id');
                $.ajax({
                    url: 'more_Tour_Details/'+ids,
                    type: 'GET',
                    data: {
                        "id": ids
                    },
                    success:function(data) {
                        var a               = data;
                        var booking_data    = data['booking_data'];
                        var booking_count   = data['booking_count'];
                        $('.booking_count').empty();
                        $('.booking_count').append('Booked Tours :'+booking_count);
                        var counter = 1;
                        $.each(booking_data, function(key, value) {
                            var booking_id      = value.booking_id;
                            var tour_id         = value.tour_id;
                            var invoice_no      = value.invoice_no;
                            var pakage_type     = value.pakage_type;
                            var price           = value.price;
                            var passenger_name  = value.passenger_name;
                            var agent_name      = value.agent_name;
                            var adults          = value.adults;
                            var childs          = value.childs;
                            var invoice_URL     = 'https://alhijaztours.net/invoice_package/'+invoice_no+'/'+booking_id+'/'+tour_id;
                            var voucher_URL     = 'https://alhijaztours.net/invoice/'+invoice_no;
                            // var invoice_URL     = `invoice_package/${invoice_no}/${booking_id}/${tour_id}`;
                            // var voucher_URL     = `invoice/${invoice_no}`;
                            
                            if(agent_name == -1 || agent_name == null || agent_name == ''){
                                var agent_name  = '-----';
                            }else{
                                var agent_name  = value.agent_name;
                            }
                            
                            var cart_total_data = JSON.parse(value.cart_total_data);
                            
                            if(adults > 0 && adults != null && adults != ''){
                                var adults = value.adults; 
                            }
                            else{
                                if(cart_total_data != null && cart_total_data != ''){
                                    var double_adults   = cart_total_data.double_adults;
                                    var triple_adults   = cart_total_data.triple_adults;
                                    var quad_adults     = cart_total_data.quad_adults;
                                    
                                    if(double_adults != null && double_adults != ''){
                                        var double_adults = cart_total_data.double_adults;
                                    }else{
                                        var double_adults = 0;
                                    }
                                    
                                    if(triple_adults != null && triple_adults != ''){
                                        var triple_adults = cart_total_data.triple_adults;
                                    }else{
                                        var triple_adults = 0;
                                    }
                                    
                                    if(quad_adults != null && quad_adults != ''){
                                        var quad_adults = cart_total_data.quad_adults;
                                    }else{
                                        var quad_adults = 0;
                                    }
                                    var adults = parseFloat(double_adults) + parseFloat(triple_adults) + parseFloat(quad_adults);
                                }else{
                                    var adults = 0;
                                }
                            }
                            
                            if(childs > 0 && childs != null && childs != ''){
                                var childs = value.childs; 
                            }
                            else{
                                if(cart_total_data != null && cart_total_data != ''){
                                    var double_childs   = cart_total_data.double_childs;
                                    var triple_childs   = cart_total_data.triple_childs;
                                    var quad_childs     = cart_total_data.quad_childs;
                                    var children        = cart_total_data.children;
                                    
                                    if(double_childs != null && double_childs != ''){
                                        var double_childs = cart_total_data.double_childs;
                                    }else{
                                        var double_childs = 0;
                                    }
                                    
                                    if(triple_childs != null && triple_childs != ''){
                                        var triple_childs = cart_total_data.triple_childs;
                                    }else{
                                        var triple_childs = 0;
                                    }
                                    
                                    if(quad_childs != null && quad_childs != ''){
                                        var quad_childs = cart_total_data.quad_childs;
                                    }else{
                                        var quad_childs = 0;
                                    }
                                    
                                    if(children != null && children != ''){
                                        var children = cart_total_data.children;
                                    }else{
                                        var children = 0;
                                    }
                                    
                                    var childs = parseFloat(double_childs) + parseFloat(triple_childs) + parseFloat(quad_childs) + parseFloat(children);
                                }else{
                                    var childs = 0;
                                }
                            }
                            
                            if(cart_total_data != null && cart_total_data != ''){
                                var infants = cart_total_data.infants;
                                if(infants != null && infants != ''){
                                    var infants = cart_total_data.infants;
                                }else{
                                    infants = 0;
                                }
                            }else{
                                infants = 0;
                            }
                            
                            var booking_data_append =   `<tr>
                                                            <td>${counter}</td>
                                                            <td>${tour_id}</td>
                                                            <td>${invoice_no}</td>
                                                            <td>${passenger_name}</td>
                                                            <td>${agent_name}</td>
                                                            <td><i class="mdi mdi-human-male-female" style="font-size: 18px;">${adults}</i></td>
                                                            <td><i class="mdi mdi-human-child" style="font-size: 18px;">${childs}</i></td>
                                                            <td><i class="mdi mdi-baby-buggy" style="font-size: 18px;">${infants}</i></td>
                                                            <td>${price}</td>
                                                            <td>
                                                                <span style="font-size: 15px;" class="badge bg-info" id="view_outSid_${booking_id}" data-id="${booking_id}" onclick="view_outS(${booking_id})">
                                                                    View Outstanding
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <div class="dropdown card-widgets">
                                                                    <a style="float: right;" href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                                        <i class="dripicons-dots-3"></i>
                                                                    </a> 
                                                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                                                        <a href="${invoice_URL}" class="dropdown-item"><i class="mdi mdi-eye me-1"></i>View Booking Details</a>
                                                                        <a href="${voucher_URL}" class="dropdown-item"><i class="mdi mdi-eye me-1"></i>View Itinerary Details</a>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>`;
                            $('#booking_data_append_div').append(booking_data_append);
                            counter = counter + 1;
                        });

                        // var booked_tour    = a['booked_tour'];
                        // $('#booked_tour_span_'+id+'').html(booked_tour);
                    },
                });
            }
            
            function view_outS(id){
                const ids = $('#view_outSid_'+id+'').attr('data-id');
                $('#view_outS_'+id+'').empty();
                
                $.ajax({
                    url: 'view_more_bookings/'+ids,
                    type: 'GET',
                    data: {
                        "id": ids
                    },
                    success:function(data) {
                        var booked_tour_payments_details    = data['booked_tour_payments_details'];
                        var amount_paid                     = parseInt(data['amount_paid']);
                        var recieved_amount                 = parseInt(data['recieved_amount']);
                        var remaining_amount                = parseInt(data['remaining_amount']);
                        var total_amount                    = data['total_amount'];
                        
                        if(total_amount != null && total_amount != ''){
                            var total_amountF               = parseInt(total_amount['total_amount']);
                            var out_S = parseFloat(total_amountF) - parseFloat(amount_paid);
                            if(out_S < 0){
                                $('#view_outSid_'+id+'').html('OVER PAID'+'('+out_S+')'+'');
                            }else{
                                $('#view_outSid_'+id+'').html(out_S);  
                            }   
                        }else{
                            $('#view_outSid_'+id+'').html('Nothing Paid Yet');
                        }
                        
                        // if(total_amount == amount_paid){
                        //     $('#view_outSid_'+id+'').empty();
                        //     $(".payment_status"+P_Id+'').html('<td style="color: Green;">PAID</td>');
                        // }else if(remaining_amount > 0 || remaining_amount < total_amount){
                        //     $('#view_outSid_'+id+'').empty();
                        //     $(".payment_status"+P_Id+'').html('<td style="color: rebeccapurple;">PARTIALLY PAID</td>');
                        // }else{
                        //     $('#view_outSid_'+id+'').empty();
                        //     $(".payment_status"+P_Id+'').html('<td style="color: orange;">UNPAID</td>');
                        // }
                    },
                });
            }
            
            // Occupancy Tour Details
            function occupancy_tour_ID(id){
                $("#city_name_Double1").empty();
                $("#city_name_Triple").empty();
                $("#city_name_Quad").empty();
                $("#acc_pax_Data_Double").empty();
                $("#acc_pax_Data_Triple").empty();
                $("#acc_pax_Data_Quad").empty();
                $("#double_pax").empty();
                $("#triple_pax").empty();
                $("#quad_pax").empty();
                $("#city_Name").empty();
                $('#get_Cities_Occupancy').empty();
                
                const ids = $('#occupancy_tour_ID_'+id+'').attr('data-id');
                $.ajax({
                    url: 'occupancy_tour/'+ids,
                    type: 'GET',
                    data: {
                        "id": ids
                    },
                    success:function(data) {
                        // console.log(data);
                        var data1           = JSON.parse(data['data']);
                        var data2           = data1['data'];
                        var title           = data2['title'];
                        var no_of_pax_days  = data2['no_of_pax_days'];
                        var start_date      = data2['start_date'];
                        var end_date        = data2['end_date'];
                        
                        $('#title_Ocuppancy').html(title);
                        $('#no_of_pax_days_occupancy').html(no_of_pax_days);
                        $('#start_date_occupancy').html(start_date);
                        $('#end_date_occupancy').html(end_date);
            
                        var double_acc_qty  = 0;
                        var triple_acc_qty  = 0;
                        var quad_acc_qty  = 0;
                        
                        var maleCounterD = 1;
                        var femaleCounterD = 1;
                        
                        var maleCounterT = 1;
                        var femaleCounterT = 1;
                        
                        var maleCounterQ = 1;
                        var femaleCounterQ = 1;
                        
                        data_C = `<option value="choose" attr-id="${ids}" attr="${ids}">Choose City</option>`;
                        $('#get_Cities_Occupancy').append(data_C);
                        
                        // Accomodation_Details
                        var No = 1;
                        var accomodation_details = JSON.parse(data2['accomodation_details']);
                        $.each(accomodation_details, function(key, value) {
                            var hotel_city_name = value.hotel_city_name;
                            var acc_type        = value.acc_type;
                            var acc_qty         = value.acc_qty;
                            var acc_pax         = value.acc_pax;
                            
                            if(acc_type == "Double"){
                                double_acc_qty = parseFloat(double_acc_qty) + parseFloat(acc_qty);
                                $("#double_pax").html("DOUBLE("+double_acc_qty+")");
                                $("#double_pax").append('<br>');
                                
                                var new_DivD = `<br><br><div class="col-xl-6" id='acc_pax_Data_Double${No}' style="text-align:center"></div>`;
                                $("#acc_pax_Data_Double").append(new_DivD);
                                var city_Name = `<div id="city_No${No}" style="font-size: 25px;"><b>${hotel_city_name} :</b><div>`;
                                $('#acc_pax_Data_Double'+No+'').append(city_Name);
                            }
                            
                            if(acc_type == "Triple"){
                                triple_acc_qty = parseFloat(triple_acc_qty) + parseFloat(acc_qty);
                                $("#triple_pax").html("TRIPLE("+triple_acc_qty+")");
                                $("#triple_pax").append('<br>');
                                
                                var new_DivT = `<br><br><div class="col-xl-6" id='acc_pax_Data_Triple${No}' style="text-align:center"></div>`;
                                $("#acc_pax_Data_Triple").append(new_DivT);
                                var city_Name = `<div id="city_No${No}" style="font-size: 25px;"><b>${hotel_city_name} :</b><div>`;
                                $('#acc_pax_Data_Triple'+No+'').append(city_Name);
                            }
                            
                            if(acc_type == "Quad"){
                                quad_acc_qty = parseFloat(quad_acc_qty) + parseFloat(acc_qty);
                                $("#quad_pax").html("QUAD("+quad_acc_qty+")");
                                $("#quad_pax").append('<br>');
                                
                                var new_DivQ = `<br><br><div class="col-xl-6" id='acc_pax_Data_Quad${No}' style="text-align:center"></div>`;
                                $("#acc_pax_Data_Quad").append(new_DivQ);
                                var city_Name = `<div id="city_No${No}" style="font-size: 25px;"><b>${hotel_city_name} :</b><div>`;
                                $('#acc_pax_Data_Quad'+No+'').append(city_Name);
                            }
                            
                            var type = 'male';
                            
                            for (let i = 0; i < acc_qty; i++) {
                                
                                if(acc_type == "Double"){
                                    var a = 2;
                                    if(type == 'male'){
                                        type = 'female'
                                    }else{
                                        type = 'male'
                                    }
                                    for (let j = 0; j < a; j++) {
                                        if(type == 'male'){
                                            var acc_pax_Data_Double = `<i id="double${type}${maleCounterD}" class="mdi mdi-human-male" style="font-size: 40px;"></i>`;
                                            maleCounterD++;
                                        }else{
                                            var acc_pax_Data_Double = `<i id="double${type}${femaleCounterD}" class="mdi mdi-human-female" style="font-size: 40px;"></i>`;
                                            femaleCounterD++;
                                        }
                                        
                                        $('#acc_pax_Data_Double'+No+'').append(acc_pax_Data_Double);
                                        $('#acc_pax_Data_Double'+No+'').append("              ");
                                    }   
                                    $('#acc_pax_Data_Double'+No+'').append('<br><br>');
                                }
                                
                                if(acc_type == "Triple"){
                                    var a = 3;
                                    if(type == 'male'){
                                        type = 'female'
                                    }else{
                                        type = 'male'
                                    }
                                    for (let j = 0; j < a; j++) {
                                        if(type == 'male'){
                                            var acc_pax_Data_Triple = `<i id="triple${type}${maleCounterT}" class="mdi mdi-human-male" style="font-size: 40px;"></i>`;
                                            maleCounterT++;
                                        }else{
                                            var acc_pax_Data_Triple = `<i id="triple${type}${femaleCounterT}" class="mdi mdi-human-female" style="font-size: 40px;"></i>`;
                                            femaleCounterT++;
                                        }
                                        $('#acc_pax_Data_Triple'+No+'').append(acc_pax_Data_Triple);
                                        $('#acc_pax_Data_Triple'+No+'').append("              ");
                                    }
                                    $('#acc_pax_Data_Triple'+No+'').append('<br><br>');
                                }
                                
                                if(acc_type == "Quad"){
                                    var a = 4;
                                    if(type == 'male'){
                                        type = 'female'
                                    }else{
                                        type = 'male'
                                    }
                                    for (let j = 0; j < a; j++) {
                                        if(type == 'male'){
                                            var acc_pax_Data_Quad = `<i id="quad${type}${maleCounterQ}" class="mdi mdi-human-male" style="font-size: 40px;"></i>`;
                                            maleCounterQ++;
                                        }else{
                                            var acc_pax_Data_Quad = `<i id="quad${type}${femaleCounterQ}" class="mdi mdi-human-female" style="font-size: 40px;"></i>`;
                                            femaleCounterQ++;
                                        }
                                        
                                        $('#acc_pax_Data_Quad'+No+'').append(acc_pax_Data_Quad);
                                        $('#acc_pax_Data_Quad'+No+'').append("              ");
                                    } 
                                    $('#acc_pax_Data_Quad'+No+'').append('<br><br>');
                                }
                            }
    
                            No = No + 1
                            
                            data_C = `<option value="${hotel_city_name}" more-attr="" attr="${acc_type}" attr-ID="${ids}">${hotel_city_name}(${acc_type})</option>`;
                            $('#get_Cities_Occupancy').append(data_C);
                        });
                        
                        // More_Accomodation_Details
                        var No1 = 1;
                        var accomodation_details_more   = JSON.parse(data2['accomodation_details_more']);
                        $.each(accomodation_details_more, function(key, value) {
                            var more_hotel_city = value.more_hotel_city;
                            var more_acc_type   = value.more_acc_type;
                            var more_acc_qty    = value.more_acc_qty;
                            var more_acc_pax    = value.more_acc_pax;
                            
                            // console.log(value);
                            
                            if(more_acc_type == "Double"){
                                double_acc_qty = parseFloat(double_acc_qty) + parseFloat(more_acc_qty);
                                $("#double_pax").html("DOUBLE("+double_acc_qty+")");
                                $("#double_pax").append('<br>');
                                
                                var new_DivD = `<br><br><div class="col-xl-6" id='acc_pax_Data_Double${No1}' style="text-align:center"></div>`;
                                $("#acc_pax_Data_Double").append(new_DivD);
                                var city_Name = `<div id="city_No${No1}" style="font-size: 25px;"><b>${more_hotel_city} :</b><div>`;
                                $('#acc_pax_Data_Double'+No1+'').append(city_Name);
                            }
                            
                            if(more_acc_type == "Triple"){
                                triple_acc_qty = parseFloat(triple_acc_qty) + parseFloat(more_acc_qty);
                                $("#triple_pax").html("TRIPLE("+triple_acc_qty+")");
                                $("#triple_pax").append('<br>');
                                
                                var new_DivT = `<br><br><div class="col-xl-6" id='acc_pax_Data_Triple${No1}' style="text-align:center"></div>`;
                                $("#acc_pax_Data_Triple").append(new_DivT);
                                var city_Name = `<div id="city_No${No1}" style="font-size: 25px;"><b>${more_hotel_city} :</b><div>`;
                                $('#acc_pax_Data_Triple'+No1+'').append(city_Name);
                            }
                            
                            if(more_acc_type == "Quad"){
                                quad_acc_qty = parseFloat(quad_acc_qty) + parseFloat(more_acc_qty);
                                $("#quad_pax").html("QUAD("+quad_acc_qty+")");
                                $("#quad_pax").append('<br>');
                                
                                var new_DivQ = `<br><br><div class="col-xl-6" id='acc_pax_Data_Quad${No1}' style="text-align:center"></div>`;
                                $("#acc_pax_Data_Quad").append(new_DivQ);
                                var city_Name = `<div id="city_No${No1}" style="font-size: 25px;"><b>${more_hotel_city} :</b><div>`;
                                $('#acc_pax_Data_Quad'+No1+'').append(city_Name);
                            }
                            
                            var type = 'male';
                            for (let i = 0; i < more_acc_qty; i++) {
                                
                                if(more_acc_type == "Double"){
                                    var a = 2;
                                    if(type == 'male'){
                                        type = 'female'
                                    }else{
                                        type = 'male'
                                    }
                                    for (let j = 0; j < a; j++) {
                                        if(type == 'male'){
                                            var acc_pax_Data_Double = `<i id="double${type}${maleCounterD}" class="mdi mdi-human-male" style="font-size: 40px;"></i>`;
                                            maleCounterD++;
                                        }else{
                                            var acc_pax_Data_Double = `<i id="double${type}${femaleCounterD}" class="mdi mdi-human-female" style="font-size: 40px;"></i>`;
                                            femaleCounterD++;
                                        }
                                        $('#acc_pax_Data_Double'+No1+'').append(acc_pax_Data_Double);
                                        $('#acc_pax_Data_Double'+No1+'').append("              ");
                                    }   
                                    $('#acc_pax_Data_Double'+No1+'').append('<br><br>');
                                }
                               
                                if(more_acc_type == "Triple"){
                                    var a = 3;
                                    if(type == 'male'){
                                        type = 'female'
                                    }else{
                                        type = 'male'
                                    }
                                    for (let j = 0; j < a; j++) {
                                        if(type == 'male'){
                                            var acc_pax_Data_Triple = `<i id="triple${type}${maleCounterT}" class="mdi mdi-human-male" style="font-size: 40px;"></i>`;
                                            maleCounterT++;
                                        }else{
                                            var acc_pax_Data_Triple = `<i id="triple${type}${femaleCounterT}" class="mdi mdi-human-female" style="font-size: 40px;"></i>`;
                                            femaleCounterT++;
                                        }
                                        $('#acc_pax_Data_Triple'+No1+'').append(acc_pax_Data_Triple);
                                        $('#acc_pax_Data_Triple'+No1+'').append("              ");
                                    }
                                    $('#acc_pax_Data_Triple'+No1+'').append('<br><br>');
                                }
                                
                                if(more_acc_type == "Quad"){
                                    var a = 4;
                                    if(type == 'male'){
                                        type = 'female'
                                    }else{
                                        type = 'male'
                                    }
                                    for (let j = 0; j < a; j++) {
                                        if(type == 'male'){
                                            var acc_pax_Data_Quad = `<i id="quad${type}${maleCounterQ}" class="mdi mdi-human-male" style="font-size: 40px;"></i>`;
                                            maleCounterQ++;
                                        }else{
                                            var acc_pax_Data_Quad = `<i id="quad${type}${femaleCounterQ}" class="mdi mdi-human-female" style="font-size: 40px;"></i>`;
                                            femaleCounterQ++;
                                        }
                                        $('#acc_pax_Data_Quad'+No1+'').append(acc_pax_Data_Quad);
                                        $('#acc_pax_Data_Quad'+No1+'').append("              ");
                                    } 
                                    $('#acc_pax_Data_Quad'+No1+'').append('<br><br>');
                                }
                            }
                            
                            No1   = No1 + 1 ;
                            
                            data_C = `<option value="${more_hotel_city}" more-attr="${more_acc_type}" attr="" attr-ID="${ids}">${more_hotel_city}(${more_acc_type})</option>`;
                            $('#get_Cities_Occupancy').append(data_C);
                        });
                        
                        var total_adults    = 0;
                        var total_childs    = 0;
                        var totalMale       = 0;
                        var totalfeMale     = 0;
                        var sharing_type    = '';
                        
                        // No_Of_Pax_Details
                        var no_of_pax_details               = data1['no_of_pax_details'];
                        // console.log(no_of_pax_details);
                        var no_of_pax_details_booked1       = no_of_pax_details.length;
                        
                        $.each(no_of_pax_details, function(key, value) {
                            var adults_detail    = JSON.parse(value.adults_detail);
                            var child_detail     = JSON.parse(value.child_detail);
                            var passenger_detail = JSON.parse(value.passenger_detail);
                            var sharingSelect    = value.sharingSelect;
                            
                            // New
                            var cart_total_data_E  = value.cart_total_data;
                            if(cart_total_data_E != null && cart_total_data_E != ''){
                                var cart_total_data  = JSON.parse(cart_total_data_E);
                                var adults = cart_total_data.adults;
                                if(adults != null && adults != ''){
                                    total_adults    = parseFloat(total_adults) + parseFloat(adults);
                                }else{
                                    total_adults    = parseFloat(total_adults);
                                }
                                var children = cart_total_data.children;
                                if(children != null && children != ''){
                                    total_childs    = parseFloat(total_childs) + parseFloat(children);
                                }else{
                                    total_childs    = parseFloat(total_childs);
                                }
                                
                                var double_adults = cart_total_data.double_adults;
                                if(double_adults != null && double_adults != ''){
                                    total_adults    = parseFloat(total_adults) + parseFloat(double_adults);
                                }else{
                                    total_adults    = parseFloat(total_adults);
                                }
                                var double_childs = cart_total_data.double_childs;
                                if(double_childs != null && double_childs != ''){
                                    total_childs    = parseFloat(total_childs) + parseFloat(double_childs);
                                }else{
                                    total_childs    = parseFloat(total_childs);
                                }
                                
                                var triple_adults = cart_total_data.triple_adults;
                                if(triple_adults != null && triple_adults != ''){
                                    total_adults    = parseFloat(total_adults) + parseFloat(triple_adults);
                                }else{
                                    total_adults    = parseFloat(total_adults);
                                }
                                var triple_childs = cart_total_data.triple_childs;
                                if(triple_childs != null && triple_childs != ''){
                                    total_childs    = parseFloat(total_childs) + parseFloat(triple_childs);
                                }else{
                                    total_childs    = parseFloat(total_childs);
                                }
                                
                                var quad_adults = cart_total_data.quad_adults;
                                if(quad_adults != null && quad_adults != ''){
                                    total_adults    = parseFloat(total_adults) + parseFloat(quad_adults);
                                }else{
                                    total_adults    = parseFloat(total_adults);
                                }
                                var quad_childs = cart_total_data.quad_childs;
                                if(quad_childs != null && quad_childs != ''){
                                    total_childs    = parseFloat(total_childs) + parseFloat(quad_childs);
                                }else{
                                    total_childs    = parseFloat(total_childs);
                                }
                                
                                if(passenger_detail != null || passenger_detail != ""){
                                    $.each(passenger_detail, function(key, value) {
                                        
                                        var Pgender = value.gender;
                                        if(Pgender == 'male'){
                                            totalMale++;
                                        }
                                        if(Pgender == 'female'){
                                            totalfeMale++;
                                        }
                                        
                                        if(double_adults != null && double_adults != '' && double_adults > 0 || adults != null && adults != '' && adults > 0){
                                            sharing_type = 'double';
                                            // console.log('totalMale double : '+totalMale);
                                            for(i = 0; i<maleCounterD; i++){
                                                var selectId = '#'+sharing_type+'male'+i+'';
                                                if(totalMale >= i){
                                                    // console.log('selectId double : '+selectId);
                                                    $(selectId).css('color','Pink');
                                                }
                                            }
                            
                                            for(j = 0; j<femaleCounterD; j++){
                                                var selectId = '#'+sharing_type+'female'+j+'';
                                                if(totalfeMale >= j){
                                                    $(selectId).css('color','Pink');
                                                }
                                            }
                                        }
                                        
                                        if(triple_adults != null && triple_adults != '' && triple_adults > 0){
                                            sharing_type = 'triple';
                                            // console.log('totalMale Triple : '+totalMale);
                                            for(i = 0; i<maleCounterT; i++){
                                                var selectId = '#'+sharing_type+'male'+i+'';
                                                // console.log('selectId Triple : '+selectId);
                                                if(totalMale >= i){
                                                    $(selectId).css('color','Pink');
                                                }
                                            }
                            
                                            for(j = 0; j<femaleCounterT; j++){
                                                var selectId = '#'+sharing_type+'female'+j+'';
                                                if(totalfeMale >= j){
                                                    $(selectId).css('color','Pink');
                                                }
                                            }
                                        }
                                        
                                        if(quad_adults != null && quad_adults != '' && quad_adults > 0){
                                            sharing_type = 'quad';
                                            for(i = 0; i<maleCounterQ; i++){
                                                var selectId = '#'+sharing_type+'male'+i+'';
                                                if(totalMale >= i){
                                                    $(selectId).css('color','Pink');
                                                }
                                            }
                            
                                            for(j = 0; j<femaleCounterQ; j++){
                                                var selectId = '#'+sharing_type+'female'+j+'';
                                                if(totalfeMale >= j){
                                                    $(selectId).css('color','Pink');
                                                }
                                            }
                                        }    
                                    });
                                }
                                
                                if(adults_detail != null || adults_detail != ""){
                                    $.each(adults_detail, function(key, value) {
                                        
                                        var Agender = value.gender;
                                        if(Agender == 'male'){
                                            totalMale++;
                                        }
                                        if(Agender == 'female'){
                                            totalfeMale++;
                                        }
                                        
                                        if(double_adults != null && double_adults != '' && double_adults > 0 || adults != null && adults != '' && adults > 0){
                                            sharing_type = 'double';
                                            // console.log("Adult : "+sharing_type);
                                            
                                            for(i = 0; i<maleCounterD; i++){
                                                // sharing_type = 'double';
                                                var selectId = '#'+sharing_type+'male'+i+'';
                                                
                                                if(totalMale >= i){
                                                    // console.log("If Male Counter : "+sharing_type);
                                                    // alert(selectId);
                                                    $(selectId).css('color','Pink');
                                                    // console.log("i = "+i);
                                                    // console.log(totalMale);
                                                }
                                            }
                            
                                            for(j = 0; j<femaleCounterD; j++){
                                                // sharing_type = 'triple';
                                                var selectId = '#'+sharing_type+'female'+j+'';
                                                // console.log(selectId);
                                                if(totalfeMale >= j){
                                                    // console.log("If female Counter : "+sharing_type);
                                                    // alert(selectId);
                                                    $(selectId).css('color','Pink');
                                                    // console.log("j = "+j);
                                                    // console.log(totalfeMale);
                                                }
                                            }
                                        }
                                        
                                        if(triple_adults != null && triple_adults != '' && triple_adults > 0){
                                            sharing_type = 'triple';
                                            // console.log("Adult : "+sharing_type);
                                            
                                            for(i = 0; i<maleCounterT; i++){
                                                // sharing_type = 'double';
                                                var selectId = '#'+sharing_type+'male'+i+'';
                                                
                                                if(totalMale >= i){
                                                    // console.log("If Male Counter : "+sharing_type);
                                                    // alert(selectId);
                                                    $(selectId).css('color','Pink');
                                                    // console.log("i = "+i);
                                                    // console.log(totalMale);
                                                }
                                            }
                            
                                            for(j = 0; j<femaleCounterT; j++){
                                                // sharing_type = 'triple';
                                                var selectId = '#'+sharing_type+'female'+j+'';
                                                // console.log(selectId);
                                                if(totalfeMale >= j){
                                                    // console.log("If female Counter : "+sharing_type);
                                                    // alert(selectId);
                                                    $(selectId).css('color','Pink');
                                                    // console.log("j = "+j);
                                                    // console.log(totalfeMale);
                                                }
                                            }
                                        }
                                        
                                        if(quad_adults != null && quad_adults != '' && quad_adults > 0){
                                            sharing_type = 'quad';
                                            // console.log("Adult : "+sharing_type);
                                            
                                            for(i = 0; i<maleCounterQ; i++){
                                                // sharing_type = 'double';
                                                var selectId = '#'+sharing_type+'male'+i+'';
                                                
                                                if(totalMale >= i){
                                                    // console.log("If Male Counter : "+sharing_type);
                                                    // alert(selectId);
                                                    $(selectId).css('color','Pink');
                                                    // console.log("i = "+i);
                                                    // console.log(totalMale);
                                                }
                                            }
                            
                                            for(j = 0; j<femaleCounterQ; j++){
                                                // sharing_type = 'triple';
                                                var selectId = '#'+sharing_type+'female'+j+'';
                                                // console.log(selectId);
                                                if(totalfeMale >= j){
                                                    // console.log("If female Counter : "+sharing_type);
                                                    // alert(selectId);
                                                    $(selectId).css('color','Pink');
                                                    // console.log("j = "+j);
                                                    // console.log(totalfeMale);
                                                }
                                            }
                                        }    
                                    });
                                }
                            
                                if(child_detail != null || child_detail != ""){
                                    $.each(child_detail, function(key, value) {
                                        
                                        var Cgender = value.gender;
                                        if(Cgender == 'male'){
                                            totalMale++;
                                        }
                                        if(Cgender == 'female'){
                                            totalfeMale++;
                                        }
                                        
                                        if(double_adults != null && double_adults != '' && double_adults > 0 || adults != null && adults != '' && adults > 0){
                                            sharing_type = 'double';
                                            // console.log("Child : "+sharing_type);
                                            
                                            for(i = 0; i<maleCounterD; i++){
                                                // sharing_type = 'double';
                                                var selectId = '#'+sharing_type+'male'+i+'';
                                                
                                                if(totalMale >= i){
                                                    console.log("If Male Counter : "+sharing_type);
                                                    // alert(selectId);
                                                    $(selectId).css('color','Pink');
                                                    // console.log("i = "+i);
                                                    // console.log(totalMale);
                                                }
                                            }
                            
                                            for(j = 0; j<femaleCounterD; j++){
                                                // sharing_type = 'triple';
                                                var selectId = '#'+sharing_type+'female'+j+'';
                                                // console.log(selectId);
                                                if(totalfeMale >= j){
                                                    console.log("If female Counter : "+sharing_type);
                                                    // alert(selectId);
                                                    $(selectId).css('color','Pink');
                                                    // console.log("j = "+j);
                                                    // console.log(totalfeMale);
                                                }
                                            }
                                        }
                                        
                                        if(triple_adults != null && triple_adults != '' && triple_adults > 0){
                                            sharing_type = 'triple';
                                            // console.log("Child : "+sharing_type);
                                            
                                            for(i = 0; i<maleCounterT; i++){
                                                // sharing_type = 'double';
                                                var selectId = '#'+sharing_type+'male'+i+'';
                                                
                                                if(totalMale >= i){
                                                    console.log("If Male Counter : "+sharing_type);
                                                    // alert(selectId);
                                                    $(selectId).css('color','Pink');
                                                    // console.log("i = "+i);
                                                    // console.log(totalMale);
                                                }
                                            }
                            
                                            for(j = 0; j<femaleCounterT; j++){
                                                // sharing_type = 'triple';
                                                var selectId = '#'+sharing_type+'female'+j+'';
                                                // console.log(selectId);
                                                if(totalfeMale >= j){
                                                    console.log("If female Counter : "+sharing_type);
                                                    // alert(selectId);
                                                    $(selectId).css('color','Pink');
                                                    // console.log("j = "+j);
                                                    // console.log(totalfeMale);
                                                }
                                            }
                                        }
                                        
                                        if(quad_adults != null && quad_adults != '' && quad_adults > 0){
                                            sharing_type = 'quad';
                                            // console.log("Child : "+sharing_type);
                                            
                                            for(i = 0; i<maleCounterQ; i++){
                                                // sharing_type = 'double';
                                                var selectId = '#'+sharing_type+'male'+i+'';
                                                
                                                if(totalMale >= i){
                                                    console.log("If Male Counter : "+sharing_type);
                                                    // alert(selectId);
                                                    $(selectId).css('color','Pink');
                                                    // console.log("i = "+i);
                                                    // console.log(totalMale);
                                                }
                                            }
                            
                                            for(j = 0; j<femaleCounterQ; j++){
                                                // sharing_type = 'triple';
                                                var selectId = '#'+sharing_type+'female'+j+'';
                                                // console.log(selectId);
                                                if(totalfeMale >= j){
                                                    console.log("If female Counter : "+sharing_type);
                                                    // alert(selectId);
                                                    $(selectId).css('color','Pink');
                                                    // console.log("j = "+j);
                                                    // console.log(totalfeMale);
                                                }
                                            }
                                        }    
                                    });
                                }
                                
                            }else{
                                total_adults    = data1['total_adults'];
                                total_childs    = data1['total_childs'];
                            }
                            
                        });
                    
                        var no_of_pax_details_booked        = parseFloat(total_adults) + parseFloat(total_childs);
                        var no_of_pax_available_occupancy   = parseFloat(no_of_pax_days) - parseFloat(no_of_pax_details_booked);
                        $('#no_of_pax_details_booked_occupancy').html(no_of_pax_details_booked);
                        $('#no_of_pax_available_occupancy').html(no_of_pax_available_occupancy);
                        
                    },
                });
            }
            
            // Occupancy Tour Details
            $('#get_Cities_Occupancy').on('change',function(){
                $("#city_name_Double1").empty();
                $("#city_name_Triple").empty();
                $("#city_name_Quad").empty();
                $("#acc_pax_Data_Double").empty();
                $("#acc_pax_Data_Triple").empty();
                $("#acc_pax_Data_Quad").empty();
                $("#double_pax").empty();
                $("#triple_pax").empty();
                $("#quad_pax").empty();
                $("#city_Name").empty();
                
                const g_c_City  = $(this).find('option:selected').attr('value');
                const g_c_Type  = $(this).find('option:selected').attr('attr');
                const g_c_MType = $(this).find('option:selected').attr('more-attr');
                const ids       = $(this).find('option:selected').attr('attr-id');
                
                if(g_c_City == 'choose'){
                    occupancy_tour_ID(ids);
                }else{
                        $.ajax({
                        url: 'occupancy_tour/'+ids,
                        type: 'GET',
                        data: {
                            "id": ids
                        },
                        success:function(data) {
                            // console.log(data);
                            var data1           = JSON.parse(data['data']);
                            var data2           = data1['data'];
                
                            var double_acc_qty  = 0;
                            var triple_acc_qty  = 0;
                            var quad_acc_qty  = 0;
                            
                            var maleCounterD = 1;
                            var femaleCounterD = 1;
                            
                            var maleCounterT = 1;
                            var femaleCounterT = 1;
                            
                            var maleCounterQ = 1;
                            var femaleCounterQ = 1;
                            
                            // Accomodation_Details
                            var No = 1;
                            var accomodation_details = JSON.parse(data2['accomodation_details']);
                            $.each(accomodation_details, function(key, value) {
                                var hotel_city_name = value.hotel_city_name;
                                var acc_type        = value.acc_type;
                                var acc_qty         = value.acc_qty;
                                var acc_pax         = value.acc_pax;
                                // console.log(value);
                                
                                if(hotel_city_name == g_c_City){
                                    if(g_c_Type == "Double" && acc_type == "Double"){
                                        // console.log('double :'+g_c_Type);
                                        double_acc_qty = parseFloat(double_acc_qty) + parseFloat(acc_qty);
                                        $("#double_pax").html("DOUBLE("+double_acc_qty+")");
                                        $("#double_pax").append('<br>');
                                        
                                        var new_DivD = `<br><br><div class="col-xl-6" id='acc_pax_Data_Double${No}' style="text-align:center"></div>`;
                                        $("#acc_pax_Data_Double").append(new_DivD);
                                        var city_Name = `<div id="city_No${No}" style="font-size: 25px;"><b>${hotel_city_name} :</b><div>`;
                                        $('#acc_pax_Data_Double'+No+'').append(city_Name);
                                    }
                                    
                                    if(g_c_Type == "Triple" && acc_type == "Triple"){
                                        // console.log('triple :'+g_c_Type);
                                        triple_acc_qty = parseFloat(triple_acc_qty) + parseFloat(acc_qty);
                                        $("#triple_pax").html("TRIPLE("+triple_acc_qty+")");
                                        $("#triple_pax").append('<br>');
                                        
                                        var new_DivT = `<br><br><div class="col-xl-6" id='acc_pax_Data_Triple${No}' style="text-align:center"></div>`;
                                        $("#acc_pax_Data_Triple").append(new_DivT);
                                        var city_Name = `<div id="city_No${No}" style="font-size: 25px;"><b>${hotel_city_name} :</b><div>`;
                                        $('#acc_pax_Data_Triple'+No+'').append(city_Name);
                                    }
                                    
                                    if(g_c_Type == "Quad" && acc_type == "Quad"){
                                        // console.log('Quad :'+g_c_Type);
                                        quad_acc_qty = parseFloat(quad_acc_qty) + parseFloat(acc_qty);
                                        $("#quad_pax").html("QUAD("+quad_acc_qty+")");
                                        $("#quad_pax").append('<br>');
                                        
                                        var new_DivQ = `<br><br><div class="col-xl-6" id='acc_pax_Data_Quad${No}' style="text-align:center"></div>`;
                                        $("#acc_pax_Data_Quad").append(new_DivQ);
                                        var city_Name = `<div id="city_No${No}" style="font-size: 25px;"><b>${hotel_city_name} :</b><div>`;
                                        $('#acc_pax_Data_Quad'+No+'').append(city_Name);
                                    }
                                    
                                    var type = 'male';
                                    for (let i = 0; i < acc_qty; i++) {
                                        
                                        if(g_c_Type == "Double" && acc_type == "Double"){
                                            var a = 2;
                                            if(type == 'male'){
                                                type = 'female'
                                            }else{
                                                type = 'male'
                                            }
                                            for (let j = 0; j < a; j++) {
                                                if(type == 'male'){
                                                    var acc_pax_Data_Double = `<i id="double${type}${maleCounterD}" class="mdi mdi-human-male" style="font-size: 40px;"></i>`;
                                                    maleCounterD++;
                                                }else{
                                                    var acc_pax_Data_Double = `<i id="double${type}${femaleCounterD}" class="mdi mdi-human-female" style="font-size: 40px;"></i>`;
                                                    femaleCounterD++;
                                                }
                                                
                                                $('#acc_pax_Data_Double'+No+'').append(acc_pax_Data_Double);
                                                $('#acc_pax_Data_Double'+No+'').append("              ");
                                            }   
                                            $('#acc_pax_Data_Double'+No+'').append('<br><br>');
                                        }
                                        
                                        if(g_c_Type == "Triple" && acc_type == "Triple"){
                                            var a = 3;
                                            if(type == 'male'){
                                                type = 'female'
                                            }else{
                                                type = 'male'
                                            }
                                            for (let j = 0; j < a; j++) {
                                                if(type == 'male'){
                                                    var acc_pax_Data_Triple = `<i id="triple${type}${maleCounterT}" class="mdi mdi-human-male" style="font-size: 40px;"></i>`;
                                                    maleCounterT++;
                                                }else{
                                                    var acc_pax_Data_Triple = `<i id="triple${type}${femaleCounterT}" class="mdi mdi-human-female" style="font-size: 40px;"></i>`;
                                                    femaleCounterT++;
                                                }
                                                $('#acc_pax_Data_Triple'+No+'').append(acc_pax_Data_Triple);
                                                $('#acc_pax_Data_Triple'+No+'').append("              ");
                                            }
                                            $('#acc_pax_Data_Triple'+No+'').append('<br><br>');
                                        }
                                        
                                        if(g_c_Type == "Quad" && acc_type == "Quad"){
                                            var a = 4;
                                            if(type == 'male'){
                                                type = 'female'
                                            }else{
                                                type = 'male'
                                            }
                                            for (let j = 0; j < a; j++) {
                                                if(type == 'male'){
                                                    var acc_pax_Data_Quad = `<i id="quad${type}${maleCounterQ}" class="mdi mdi-human-male" style="font-size: 40px;"></i>`;
                                                    maleCounterQ++;
                                                }else{
                                                    var acc_pax_Data_Quad = `<i id="quad${type}${femaleCounterQ}" class="mdi mdi-human-female" style="font-size: 40px;"></i>`;
                                                    femaleCounterQ++;
                                                }
                                                
                                                $('#acc_pax_Data_Quad'+No+'').append(acc_pax_Data_Quad);
                                                $('#acc_pax_Data_Quad'+No+'').append("              ");
                                            } 
                                            $('#acc_pax_Data_Quad'+No+'').append('<br><br>');
                                        }
                                    }
                                    No = No + 1
                                }
                            });
                            
                            // More_Accomodation_Details
                            var No1 = 1;
                            var accomodation_details_more   = JSON.parse(data2['accomodation_details_more']);
                            $.each(accomodation_details_more, function(key, value) {
                                var more_hotel_city = value.more_hotel_city;
                                var more_acc_type   = value.more_acc_type;
                                var more_acc_qty    = value.more_acc_qty;
                                var more_acc_pax    = value.more_acc_pax;
                                // console.log(value);
                                
                                if(more_hotel_city == g_c_City){
                                    if(g_c_MType == "Double" && more_acc_type == "Double"){
                                        double_acc_qty = parseFloat(double_acc_qty) + parseFloat(more_acc_qty);
                                        $("#double_pax").html("DOUBLE("+double_acc_qty+")");
                                        $("#double_pax").append('<br>');
                                        
                                        var new_DivD = `<br><br><div class="col-xl-6" id='acc_pax_Data_Double${No1}' style="text-align:center"></div>`;
                                        $("#acc_pax_Data_Double").append(new_DivD);
                                        var city_Name = `<div id="city_No${No1}" style="font-size: 25px;"><b>${more_hotel_city} :</b><div>`;
                                        $('#acc_pax_Data_Double'+No1+'').append(city_Name);
                                    }
                                    
                                    if(g_c_MType == "Triple" && more_acc_type == "Triple"){
                                        // console.log('triple :'+more_acc_qty);
                                        triple_acc_qty = parseFloat(triple_acc_qty) + parseFloat(more_acc_qty);
                                        $("#triple_pax").html("TRIPLE("+triple_acc_qty+")");
                                        $("#triple_pax").append('<br>');
                                        
                                        var new_DivT = `<br><br><div class="col-xl-6" id='acc_pax_Data_Triple${No1}' style="text-align:center"></div>`;
                                        $("#acc_pax_Data_Triple").append(new_DivT);
                                        var city_Name = `<div id="city_No${No1}" style="font-size: 25px;"><b>${more_hotel_city} :</b><div>`;
                                        $('#acc_pax_Data_Triple'+No1+'').append(city_Name);
                                    }
                                    
                                    if(g_c_MType == "Quad" && more_acc_type == "Quad"){
                                        quad_acc_qty = parseFloat(quad_acc_qty) + parseFloat(more_acc_qty);
                                        $("#quad_pax").html("QUAD("+quad_acc_qty+")");
                                        $("#quad_pax").append('<br>');
                                        
                                        var new_DivQ = `<br><br><div class="col-xl-6" id='acc_pax_Data_Quad${No1}' style="text-align:center"></div>`;
                                        $("#acc_pax_Data_Quad").append(new_DivQ);
                                        var city_Name = `<div id="city_No${No1}" style="font-size: 25px;"><b>${more_hotel_city} :</b><div>`;
                                        $('#acc_pax_Data_Quad'+No1+'').append(city_Name);
                                    }
                                    
                                    var type = 'male';
                                    for (let i = 0; i < more_acc_qty; i++) {
                                        
                                        if(g_c_MType == "Double" && more_acc_type == "Double"){
                                            var a = 2;
                                            if(type == 'male'){
                                                type = 'female'
                                            }else{
                                                type = 'male'
                                            }
                                            for (let j = 0; j < a; j++) {
                                                if(type == 'male'){
                                                    var acc_pax_Data_Double = `<i id="double${type}${maleCounterD}" class="mdi mdi-human-male" style="font-size: 40px;"></i>`;
                                                    maleCounterD++;
                                                }else{
                                                    var acc_pax_Data_Double = `<i id="double${type}${femaleCounterD}" class="mdi mdi-human-female" style="font-size: 40px;"></i>`;
                                                    femaleCounterD++;
                                                }
                                                $('#acc_pax_Data_Double'+No1+'').append(acc_pax_Data_Double);
                                                $('#acc_pax_Data_Double'+No1+'').append("              ");
                                            }   
                                            $('#acc_pax_Data_Double'+No1+'').append('<br><br>');
                                        }
                                       
                                        if(g_c_MType == "Triple" && more_acc_type == "Triple"){
                                            var a = 3;
                                            if(type == 'male'){
                                                type = 'female'
                                            }else{
                                                type = 'male'
                                            }
                                            for (let j = 0; j < a; j++) {
                                                if(type == 'male'){
                                                    var acc_pax_Data_Triple = `<i id="triple${type}${maleCounterT}" class="mdi mdi-human-male" style="font-size: 40px;"></i>`;
                                                    maleCounterT++;
                                                }else{
                                                    var acc_pax_Data_Triple = `<i id="triple${type}${femaleCounterT}" class="mdi mdi-human-female" style="font-size: 40px;"></i>`;
                                                    femaleCounterT++;
                                                }
                                                $('#acc_pax_Data_Triple'+No1+'').append(acc_pax_Data_Triple);
                                                $('#acc_pax_Data_Triple'+No1+'').append("              ");
                                            }
                                            $('#acc_pax_Data_Triple'+No1+'').append('<br><br>');
                                        }
                                        
                                        if(g_c_MType == "Quad" && more_acc_type == "Quad"){
                                            var a = 4;
                                            if(type == 'male'){
                                                type = 'female'
                                            }else{
                                                type = 'male'
                                            }
                                            for (let j = 0; j < a; j++) {
                                                if(type == 'male'){
                                                    var acc_pax_Data_Quad = `<i id="quad${type}${maleCounterQ}" class="mdi mdi-human-male" style="font-size: 40px;"></i>`;
                                                    maleCounterQ++;
                                                }else{
                                                    var acc_pax_Data_Quad = `<i id="quad${type}${femaleCounterQ}" class="mdi mdi-human-female" style="font-size: 40px;"></i>`;
                                                    femaleCounterQ++;
                                                }
                                                $('#acc_pax_Data_Quad'+No1+'').append(acc_pax_Data_Quad);
                                                $('#acc_pax_Data_Quad'+No1+'').append("              ");
                                            } 
                                            $('#acc_pax_Data_Quad'+No1+'').append('<br><br>');
                                        }
                                    }
                                    No1   = No1 + 1 ;
                                }
                            });
                            
                            var total_adults    = 0;
                            var total_childs    = 0;
                            var totalMale       = 0;
                            var totalfeMale     = 0;
                            var sharing_type    = '';
                            
                            // No_Of_Pax_Details
                            var no_of_pax_details               = data1['no_of_pax_details'];
                            // console.log(no_of_pax_details);
                            var no_of_pax_details_booked1       = no_of_pax_details.length;
                            
                            $.each(no_of_pax_details, function(key, value) {
                                var adults_detail    = JSON.parse(value.adults_detail);
                                var child_detail     = JSON.parse(value.child_detail);
                                var passenger_detail = JSON.parse(value.passenger_detail);
                                var sharingSelect    = value.sharingSelect;
                                
                                // New
                                var cart_total_data_E  = value.cart_total_data;
                                if(cart_total_data_E != null && cart_total_data_E != ''){
                                    var cart_total_data  = JSON.parse(cart_total_data_E);
                                    var adults = cart_total_data.adults;
                                    if(adults != null && adults != ''){
                                        total_adults    = parseFloat(total_adults) + parseFloat(adults);
                                    }else{
                                        total_adults    = parseFloat(total_adults);
                                    }
                                    var children = cart_total_data.children;
                                    if(children != null && children != ''){
                                        total_childs    = parseFloat(total_childs) + parseFloat(children);
                                    }else{
                                        total_childs    = parseFloat(total_childs);
                                    }
                                    
                                    var double_adults = cart_total_data.double_adults;
                                    if(double_adults != null && double_adults != ''){
                                        total_adults    = parseFloat(total_adults) + parseFloat(double_adults);
                                    }else{
                                        total_adults    = parseFloat(total_adults);
                                    }
                                    var double_childs = cart_total_data.double_childs;
                                    if(double_childs != null && double_childs != ''){
                                        total_childs    = parseFloat(total_childs) + parseFloat(double_childs);
                                    }else{
                                        total_childs    = parseFloat(total_childs);
                                    }
                                    
                                    var triple_adults = cart_total_data.triple_adults;
                                    if(triple_adults != null && triple_adults != ''){
                                        total_adults    = parseFloat(total_adults) + parseFloat(triple_adults);
                                    }else{
                                        total_adults    = parseFloat(total_adults);
                                    }
                                    var triple_childs = cart_total_data.triple_childs;
                                    if(triple_childs != null && triple_childs != ''){
                                        total_childs    = parseFloat(total_childs) + parseFloat(triple_childs);
                                    }else{
                                        total_childs    = parseFloat(total_childs);
                                    }
                                    
                                    var quad_adults = cart_total_data.quad_adults;
                                    if(quad_adults != null && quad_adults != ''){
                                        total_adults    = parseFloat(total_adults) + parseFloat(quad_adults);
                                    }else{
                                        total_adults    = parseFloat(total_adults);
                                    }
                                    var quad_childs = cart_total_data.quad_childs;
                                    if(quad_childs != null && quad_childs != ''){
                                        total_childs    = parseFloat(total_childs) + parseFloat(quad_childs);
                                    }else{
                                        total_childs    = parseFloat(total_childs);
                                    }
                                    
                                    if(passenger_detail != null || passenger_detail != ""){
                                        $.each(passenger_detail, function(key, value) {
                                            
                                            var Pgender = value.gender;
                                            if(Pgender == 'male'){
                                                totalMale++;
                                            }
                                            if(Pgender == 'female'){
                                                totalfeMale++;
                                            }
                                            
                                            if(double_adults != null && double_adults != '' && double_adults > 0 || adults != null && adults != '' && adults > 0){
                                                sharing_type = 'double';
                                                // console.log('totalMale double : '+totalMale);
                                                for(i = 0; i<maleCounterD; i++){
                                                    var selectId = '#'+sharing_type+'male'+i+'';
                                                    if(totalMale >= i){
                                                        // console.log('selectId double : '+selectId);
                                                        $(selectId).css('color','Pink');
                                                    }
                                                }
                                
                                                for(j = 0; j<femaleCounterD; j++){
                                                    var selectId = '#'+sharing_type+'female'+j+'';
                                                    if(totalfeMale >= j){
                                                        $(selectId).css('color','Pink');
                                                    }
                                                }
                                            }
                                            
                                            if(triple_adults != null && triple_adults != '' && triple_adults > 0){
                                                sharing_type = 'triple';
                                                // console.log('totalMale Triple : '+totalMale);
                                                for(i = 0; i<maleCounterT; i++){
                                                    var selectId = '#'+sharing_type+'male'+i+'';
                                                    // console.log('selectId Triple : '+selectId);
                                                    if(totalMale >= i){
                                                        $(selectId).css('color','Pink');
                                                    }
                                                }
                                
                                                for(j = 0; j<femaleCounterT; j++){
                                                    var selectId = '#'+sharing_type+'female'+j+'';
                                                    if(totalfeMale >= j){
                                                        $(selectId).css('color','Pink');
                                                    }
                                                }
                                            }
                                            
                                            if(quad_adults != null && quad_adults != '' && quad_adults > 0){
                                                sharing_type = 'quad';
                                                for(i = 0; i<maleCounterQ; i++){
                                                    var selectId = '#'+sharing_type+'male'+i+'';
                                                    if(totalMale >= i){
                                                        $(selectId).css('color','Pink');
                                                    }
                                                }
                                
                                                for(j = 0; j<femaleCounterQ; j++){
                                                    var selectId = '#'+sharing_type+'female'+j+'';
                                                    if(totalfeMale >= j){
                                                        $(selectId).css('color','Pink');
                                                    }
                                                }
                                            }    
                                        });
                                    }
                                    
                                    if(adults_detail != null || adults_detail != ""){
                                        $.each(adults_detail, function(key, value) {
                                            
                                            var Agender = value.gender;
                                            if(Agender == 'male'){
                                                totalMale++;
                                            }
                                            if(Agender == 'female'){
                                                totalfeMale++;
                                            }
                                            
                                            if(double_adults != null && double_adults != '' && double_adults > 0 || adults != null && adults != '' && adults > 0){
                                                sharing_type = 'double';
                                                // console.log("Adult : "+sharing_type);
                                                
                                                for(i = 0; i<maleCounterD; i++){
                                                    // sharing_type = 'double';
                                                    var selectId = '#'+sharing_type+'male'+i+'';
                                                    
                                                    if(totalMale >= i){
                                                        // console.log("If Male Counter : "+sharing_type);
                                                        // alert(selectId);
                                                        $(selectId).css('color','Pink');
                                                        // console.log("i = "+i);
                                                        // console.log(totalMale);
                                                    }
                                                }
                                
                                                for(j = 0; j<femaleCounterD; j++){
                                                    // sharing_type = 'triple';
                                                    var selectId = '#'+sharing_type+'female'+j+'';
                                                    // console.log(selectId);
                                                    if(totalfeMale >= j){
                                                        // console.log("If female Counter : "+sharing_type);
                                                        // alert(selectId);
                                                        $(selectId).css('color','Pink');
                                                        // console.log("j = "+j);
                                                        // console.log(totalfeMale);
                                                    }
                                                }
                                            }
                                            
                                            if(triple_adults != null && triple_adults != '' && triple_adults > 0){
                                                sharing_type = 'triple';
                                                // console.log("Adult : "+sharing_type);
                                                
                                                for(i = 0; i<maleCounterT; i++){
                                                    // sharing_type = 'double';
                                                    var selectId = '#'+sharing_type+'male'+i+'';
                                                    
                                                    if(totalMale >= i){
                                                        // console.log("If Male Counter : "+sharing_type);
                                                        // alert(selectId);
                                                        $(selectId).css('color','Pink');
                                                        // console.log("i = "+i);
                                                        // console.log(totalMale);
                                                    }
                                                }
                                
                                                for(j = 0; j<femaleCounterT; j++){
                                                    // sharing_type = 'triple';
                                                    var selectId = '#'+sharing_type+'female'+j+'';
                                                    // console.log(selectId);
                                                    if(totalfeMale >= j){
                                                        // console.log("If female Counter : "+sharing_type);
                                                        // alert(selectId);
                                                        $(selectId).css('color','Pink');
                                                        // console.log("j = "+j);
                                                        // console.log(totalfeMale);
                                                    }
                                                }
                                            }
                                            
                                            if(quad_adults != null && quad_adults != '' && quad_adults > 0){
                                                sharing_type = 'quad';
                                                // console.log("Adult : "+sharing_type);
                                                
                                                for(i = 0; i<maleCounterQ; i++){
                                                    // sharing_type = 'double';
                                                    var selectId = '#'+sharing_type+'male'+i+'';
                                                    
                                                    if(totalMale >= i){
                                                        // console.log("If Male Counter : "+sharing_type);
                                                        // alert(selectId);
                                                        $(selectId).css('color','Pink');
                                                        // console.log("i = "+i);
                                                        // console.log(totalMale);
                                                    }
                                                }
                                
                                                for(j = 0; j<femaleCounterQ; j++){
                                                    // sharing_type = 'triple';
                                                    var selectId = '#'+sharing_type+'female'+j+'';
                                                    // console.log(selectId);
                                                    if(totalfeMale >= j){
                                                        // console.log("If female Counter : "+sharing_type);
                                                        // alert(selectId);
                                                        $(selectId).css('color','Pink');
                                                        // console.log("j = "+j);
                                                        // console.log(totalfeMale);
                                                    }
                                                }
                                            }    
                                        });
                                    }
                                
                                    if(child_detail != null || child_detail != ""){
                                        $.each(child_detail, function(key, value) {
                                            
                                            var Cgender = value.gender;
                                            if(Cgender == 'male'){
                                                totalMale++;
                                            }
                                            if(Cgender == 'female'){
                                                totalfeMale++;
                                            }
                                            
                                            if(double_adults != null && double_adults != '' && double_adults > 0 || adults != null && adults != '' && adults > 0){
                                                sharing_type = 'double';
                                                // console.log("Child : "+sharing_type);
                                                
                                                for(i = 0; i<maleCounterD; i++){
                                                    // sharing_type = 'double';
                                                    var selectId = '#'+sharing_type+'male'+i+'';
                                                    
                                                    if(totalMale >= i){
                                                        console.log("If Male Counter : "+sharing_type);
                                                        // alert(selectId);
                                                        $(selectId).css('color','Pink');
                                                        // console.log("i = "+i);
                                                        // console.log(totalMale);
                                                    }
                                                }
                                
                                                for(j = 0; j<femaleCounterD; j++){
                                                    // sharing_type = 'triple';
                                                    var selectId = '#'+sharing_type+'female'+j+'';
                                                    // console.log(selectId);
                                                    if(totalfeMale >= j){
                                                        console.log("If female Counter : "+sharing_type);
                                                        // alert(selectId);
                                                        $(selectId).css('color','Pink');
                                                        // console.log("j = "+j);
                                                        // console.log(totalfeMale);
                                                    }
                                                }
                                            }
                                            
                                            if(triple_adults != null && triple_adults != '' && triple_adults > 0){
                                                sharing_type = 'triple';
                                                // console.log("Child : "+sharing_type);
                                                
                                                for(i = 0; i<maleCounterT; i++){
                                                    // sharing_type = 'double';
                                                    var selectId = '#'+sharing_type+'male'+i+'';
                                                    
                                                    if(totalMale >= i){
                                                        console.log("If Male Counter : "+sharing_type);
                                                        // alert(selectId);
                                                        $(selectId).css('color','Pink');
                                                        // console.log("i = "+i);
                                                        // console.log(totalMale);
                                                    }
                                                }
                                
                                                for(j = 0; j<femaleCounterT; j++){
                                                    // sharing_type = 'triple';
                                                    var selectId = '#'+sharing_type+'female'+j+'';
                                                    // console.log(selectId);
                                                    if(totalfeMale >= j){
                                                        console.log("If female Counter : "+sharing_type);
                                                        // alert(selectId);
                                                        $(selectId).css('color','Pink');
                                                        // console.log("j = "+j);
                                                        // console.log(totalfeMale);
                                                    }
                                                }
                                            }
                                            
                                            if(quad_adults != null && quad_adults != '' && quad_adults > 0){
                                                sharing_type = 'quad';
                                                // console.log("Child : "+sharing_type);
                                                
                                                for(i = 0; i<maleCounterQ; i++){
                                                    // sharing_type = 'double';
                                                    var selectId = '#'+sharing_type+'male'+i+'';
                                                    
                                                    if(totalMale >= i){
                                                        console.log("If Male Counter : "+sharing_type);
                                                        // alert(selectId);
                                                        $(selectId).css('color','Pink');
                                                        // console.log("i = "+i);
                                                        // console.log(totalMale);
                                                    }
                                                }
                                
                                                for(j = 0; j<femaleCounterQ; j++){
                                                    // sharing_type = 'triple';
                                                    var selectId = '#'+sharing_type+'female'+j+'';
                                                    // console.log(selectId);
                                                    if(totalfeMale >= j){
                                                        console.log("If female Counter : "+sharing_type);
                                                        // alert(selectId);
                                                        $(selectId).css('color','Pink');
                                                        // console.log("j = "+j);
                                                        // console.log(totalfeMale);
                                                    }
                                                }
                                            }    
                                        });
                                    }
                                    
                                }else{
                                    total_adults    = data1['total_adults'];
                                    total_childs    = data1['total_childs'];
                                }
                                
                            });
                            
                        },
                    });   
                }
            });
            
            // View Tour Details
            // function view_Tour_PopUp(id){
                
            //     // $("#view_Tour_Data").empty();
            //     const ids = $('#view_Tour_PopUp_'+id+'').attr('data-id');
            //     $.ajax({
            //         url: 'view_Tour_PopUp/'+ids,
            //         type: 'GET',
            //         data: {
            //             "id": ids
            //         },
            //         success:function(data) {
            //             var data1             = JSON.parse(data['data']);
            //             var data2             = data1['data'];
            //             console.log(data2);
            //             var i = 1;
            //             var title                       = data2['title'];
            //             var currency_symbol             = data2['currency_symbol'];
            //             var start_date                  = data2['start_date'];
            //             var end_date                    = data2['end_date'];
            //             var time_duration               = data2['time_duration'];
            //             var tour_location               = data2['tour_location'];
            //             var quad_grand_total_amount     = data2['quad_grand_total_amount'];
            //             var triple_grand_total_amount   = data2['triple_grand_total_amount'];
            //             var double_grand_total_amount   = data2['double_grand_total_amount'];
                           
            //             var tourData = `<div class="row" style="padding:1rem;">
            //                                 <div class="col-md-1">
            //                                         <img style="width:50px" src="{{asset('public/admin_package/frontend/images/tour-info.jpg') }}">
            //                                 </div>
            //                                 <div class="col-md-11"><h5 style="font-size:25px">Tour Information</h5></div>
            //                                 <div class="col-md-12">
            //                                     <div class="row">
            //                                         <div class="col-md-4">
            //                                          <img src="{{ config('img_url') }}/public/uploads/package_imgs/${data2['tour_banner_image']}" style="width: 100%;">
            //                                         </div>
            //                                         <div class="col-md-8">
            //                                             <div class="row">
            //                                                  <div class="col-md-3">
            //                                                     <p style="margin-bottom:0">Tour Name:</p>
            //                                                     <p style="margin-bottom:0">Tour Price:</p>
            //                                                     <p style="margin-bottom:0">Check-In:</p>
            //                                                     <p style="margin-bottom:0">Check-Out:</p>
            //                                                     <p style="margin-bottom:0">Duration:</p>
            //                                                     <p style="margin-bottom:0">Destinations:</p>
            //                                                 </div>
            //                                                 <div class="col-md-9">
            //                                                     <p style="margin-bottom:0">${title}</p>
            //                                                     <p style="margin-bottom:0">Quad : ${currency_symbol} ${quad_grand_total_amount} / Triple :${currency_symbol} ${triple_grand_total_amount} / Double: ${currency_symbol} ${double_grand_total_amount}</p>
            //                                                     <p style="margin-bottom:0">${start_date}</p>
            //                                                     <p style="margin-bottom:0">${end_date}</p>
            //                                                     <p style="margin-bottom:0">${time_duration} Nights</p>
            //                                                     <p style="margin-bottom:0">${tour_location}</p>
            //                                                 </div>
            //                                             </div>
            //                                         </div>
            //                                     </div>
            //                                 </div>
            //                                 <div class="col-md-1 mt-2 mb-2">
            //                                     <i class="uil-building" style="font-size:40px; aria-hidden="true"></i>
            //                                 </div>
            //                                 <div class="col-md-11 mt-2 mb-2"><h5 style="font-size:25px">Hotel Information</h5></div>
            //                                 <div class="col-md-12">
            //                                     <div class="row" id="hotel_details_${i}"></div>
            //                                 </div>
            //                             </div>`;
            //             $('#view_Tour_Data').html(tourData);
                    
            //             // Accomodation_Details
            //             var No=1;
            //             var No1=2;
            //             var accomodation_details = JSON.parse(data2['accomodation_details']);
            //             $.each(accomodation_details, function(key, value) {
            //                 console.log(value);
            //                 var hotel_city_name         = value.hotel_city_name;
            //                 var acc_hotel_name          = value.acc_hotel_name;
            //                 var acc_check_in            = value.acc_check_in;
            //                 var acc_check_out           = value.acc_check_out;
            //                 var acc_no_of_nightst       = value.acc_no_of_nightst;
            //                 var hotel_whats_included    = value.hotel_whats_included;
                                
            //                 acc_Details =   `<table class="room-type-table tours-hotels-table">
            //                                     <thead>
            //                                         <tr>
            //                                             <th class="room-type">${hotel_city_name} Hotel Details</th>
            //                                             <th class="room-people">Available Rooms</th>
            //                                             <th class="room-condition">Amenities</th>
                                                      
            //                                         </tr>
            //                                     </thead>
            //                                     <tbody>
            //                                         <tr>
            //                                             <td class="room-type">
            //                                                 <div class="room-thumb"><img src="{{ config('img_url') }}/public/uploads/package_imgs/${value.accomodation_image}" style="width: 65%;"></div>
            //                                                 <div class="room-title"><h4>${acc_hotel_name}</h4></div>
            //                                                 <ul class="list-unstyled">
            //                                                     <li><i class="fa fa-calendar" aria-hidden="true"></i> Check In   : ${acc_check_in}</li>
            //                                                     <li><i class="fa fa-calendar" aria-hidden="true"></i> Check Out  : ${acc_check_out}</li>
            //                                                     <li><i class="fa fa-moon-o" aria-hidden="true"></i> No Of Nights : ${acc_no_of_nightst}</li>
            //                                                 </ul>
            //                                             </td>
            //                                             <td class="room-people" style="width: 30%;"><div id="room_People${No}"></div><div id="room_People${No1}"></div></td>
            //                                             <td class="room-condition" style="width: 20%;">${hotel_whats_included}</td>
            //                                         </tr>
            //                                     </tbody>
            //                                 </table>`;
            //                 $('#hotel_details_'+i+'').append(acc_Details);
            //                 No = No + 2;
            //                 No1 = No1 + 2;
            //             });
                            
            //             // More_Accomodation_Details
            //             var No1 = 1;
            //             var accomodation_details_more   = JSON.parse(data2['accomodation_details_more']);
            //             $.each(accomodation_details_more, function(key, value) {
            //                 var more_hotel_city = value.more_hotel_city;
            //                 more_acc_Details = `<p>${more_hotel_city}</p>`;
            //                 $('#room_People'+No1+'').append(more_acc_Details);
            //                 No1++;
            //             });
                        
            //             // Flights Details
                        
            //             var flight_details =    `<div class="col-md-1 mt-2 mb-2">
            //                                 <i class="fa fa-plane" aria-hidden="true" style="font-size:2rem;"></i>
            //                             </div>
            //                             <div class="col-md-11 mt-2 mb-2"><h5>Flight Details</h5></div>
                                        
            //                             <div class="col-md-12">
            //                                 <div class="initiative">
            //                                     <div class="initiative__item">
            //                                         <div class="initiative-top">
            //                                             <div class="title">
            //                                                 <div class="from-to">
            //                                                     <span class="from">${data['first_depaurture']}</span>
            //                                                     <i class="awe-icon awe-icon-arrow-right"></i>
            //                                                     <span class="to">${data['last_destination']}</span>
            //                                                 </div>
            //                                                 <div class="time">${data['first_dep_time']} | ${data['last_arrival']}</div>
            //                                             </div>
            //                                         </div>
            //                                         <table class="initiative-table">
            //                                             <tbody>
            //                                                 <th>
            //                                                     <div class="item-thumb">
            //                                                         <div class="image-thumb">
            //                                                             <img src="{{ config('img_url') }}/public/uploads/package_imgs/${data['flight_det']['flights_image']}" alt="">
            //                                                         </div>
            //                                                         <div class="text">
            //                                                             <span>${airlineName}</span>
            //                                                             <p>${data['flight_det']['departure_flight_number']}</p>
            //                                                             <span>${departure_Flight_Type}</span>
            //                                                         </div>
            //                                                     </div>
            //                                                 </th>
            //                                                 <tr>
            //                                                     <td>
            //                                                         <div class="item-body" style="padding:0px;">
            //                                                             <div class="item-from">
            //                                                                 <h3> <img src="https://client1.synchronousdigital.com/public/images/departure.png" alt="" width="25px">
            //                                                                 ${data['flight_det']['departure_airport_code']}</h3>
            //                                                                 <span class="time">${data['flight_times_arr'][0]}</span>
            //                                                                 <span class="date">${data['flight_date_arr'][0]} </span>
                                                                          
            //                                                             </div>
            //                                                             <div class="item-time">
            //                                                                 <i class="fa fa-clock-o"></i>
            //                                                                 <span>10h 25m</span>
            //                                                             </div>
            //                                                             <div class="item-to">
            //                                                                 <h3><img src="https://client1.synchronousdigital.com/public/images/landing.png" alt="" width="25px">
            //                                                                 ${data['flight_det']['arrival_airport_code']}</h3>
            //                                                                 <span class="time">${data['flight_times_arrival_arr'][0]} </span>
            //                                                                 <span class="date">${data['flight_date_arrival_arr'][0]} </span>
                                                                          
            //                                                             </div>
            //                                                         </div>
            //                                                     </td>
            //                                                 </tr>
            //                                             </tbody>
            //                                         </table>
            //                                     </div>
            //                                 </div>
            //                             </div>`;
            //             }  
                    
            //     });
            
            // }
            
            function padTo2Digits(num) {
                return num.toString().padStart(2, '0');
            }
            
            function formatDate(date) {
                return 
                [
                    padTo2Digits(date.getDate()),
                    padTo2Digits(date.getMonth() + 1),
                    date.getFullYear(),
                ].join('/');
            }
            
            function view_Tour_PopUp1(id,type){
                $('#Tour_data').empty();
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: 'fetch_tour_data1',
                    method: "post",
                    data: {
                        _token: CSRF_TOKEN,
                        id: id, 
                        type: type,
                    },
                    success: function (response) {
                        var data = JSON.parse(response);
                        var a = data['tour_data']['tour_location'];
                        
                        if(type == 'tour'){
                            
                            if(data['tour_data']['tour_location'] !== null){
                                var tour_location = JSON.parse(data['tour_data']['tour_location']);
                        
                                var tours_locations = '';
                                for(var i=0; i<tour_location.length; i++){
                                    tours_locations += tour_location[i]+",";
                                }
                            }
                            else{
                                var tours_locations = '';
                            }
                           
                            var startDate = data['tour_data']['start_date'];
                            var endDate = data['tour_data']['end_date'];
                            
                            if(data['accomodation'] !== null){
                                var hotels = '';
                                for(var i=0; i<data['accomodation'].length; i++){
                                    
                                    var accomodation_image  = data['accomodation'][i]['accomodation_image'];
                                    var acc_check_in        = data['accomodation'][i]['acc_check_in'];
                                    var acc_check_out       = data['accomodation'][i]['acc_check_out'];
                                    
                                    hotels +=`<table class="room-type-table tours-hotels-table" style="text-align:center;">
                                                <thead>
                                                    <tr style="font-size: 20px;">
                                                        <th class="room-type">${data['accomodation'][i]['hotel_city_name']} Hotel Details</th>
                                                        <th class="room-people">Available Rooms </th>
                                                        <th class="room-condition">Amenities</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="room-type">
                                                            <div class="room-thumb">`
                                                                if($.isArray(accomodation_image)){
                                                                    console.log('if');
                                                                    for(var j=0; j<accomodation_image.length; j++){
                                                                        hotels +=`<img src="{{ config('img_url') }}/public/uploads/package_imgs/${accomodation_image[j]}" style="width: 50%;padding: 5%;">`;
                                                                    }
                                                                }else{
                                                                     console.log('else');
                                                                    j=0;
                                                                    hotels +=`<img src="{{ config('img_url') }}/public/uploads/package_imgs/${accomodation_image}" style="width: 50%;padding: 5%;">`;
                                                                }
                                                            
                                                        hotels +=`</div>
                                                                    <div class="room-title">
                                                                        <h4>${data['accomodation'][i]['acc_hotel_name']}</h4>
                                                                    </div>
                                                            
                                                                <ul class="list-unstyled">
                                                                    <li><i class="fa fa-calendar" aria-hidden="true"></i> Check In  : ${acc_check_in}</li>
                                                                    <li><i class="fa fa-calendar" aria-hidden="true"></i> Check Out : ${acc_check_out}</li>
                                                                    <li><i class="fa fa-moon-o" aria-hidden="true"></i> No Of Nights : ${data['accomodation'][i]['acc_no_of_nightst']}</li>
                                                                </ul>
                                                        </td>
                                                        <td class="room-people" style="width: 30%;">`
                                                        
                                                            if(data['accomodation_more'] !== null){
                                                              for(var k=0; k<data['accomodation_more'].length; k++){
                                                                    if(data['accomodation'][i]['hotel_city_name'] == data['accomodation_more'][k]['more_hotel_city']){
                                                                        hotels +=`<p style="font-size: 20px;">${data['accomodation_more'][k]['more_hotel_city']}</p>`
                                                                    }
                                                              }
                                                            }
                                                            
                                                        hotels +=`</td>
                                                        <td class="room-condition" style="font-size: 20px;">
                                                            ${data['accomodation'][i]['hotel_whats_included']}
                                                           
                                                            
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>`
                                          
                                
                                }
                            }else{
                                console.log('else hotel');
                                var hotels = '';
                            }
                              
                            if(data['flight_det']['arrival_airport_code'] !== null || data['flight_det_more'] !==null){
                                if (data['flight_det']['other_Airline_Name2'] !== undefined) {
                                     var airlineName =  data['flight_det']['other_Airline_Name2']
                                }else{
                                    var airlineName = '';
                                }
                                
                                 if (data['flight_det']['departure_Flight_Type'] !== undefined) {
                                     var departure_Flight_Type =  data['flight_det']['other_Airline_Name2']
                                }else{
                                    var departure_Flight_Type = '';
                                }
                              
                             
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
                                                                <span class="from">${data['first_depaurture']}</span>
                                                                <!--<i class="mdi-arrow-right-bold"></i>!-->
                                                                <img src="{{asset('/public/images/departure.png') }}" width="25px">
                                                                <span class="to">${data['last_destination']}</span>
                                                            </div>
                                                            <div class="time">${data['first_dep_time']} | ${data['last_arrival']}</div>
                                                        </div>
                                                       
                                                    </div>
                                                    <table class="initiative-table">
                                                        <tbody>`
                                                        if(data['flight_det']['flight_type'] !== 'Indirect'){
                                                            flight += `<tr>
                                                                <th>
                                                                    <div class="item-thumb" style="text-align: center;">
                                                                        <div class="image-thumb">
                                                                            <img src="{{ config('img_url') }}/public/uploads/package_imgs/${data['flight_det']['flights_image']}" style="width: 70%;">
                                                                        </div>
                                                                        <div class="text">
                                                                            <span>${airlineName}</span>
                                                                            <p style="margin-bottom:0px">${data['flight_det']['departure_flight_number']}</p>
                                                                            <span>${departure_Flight_Type}</span>
                                                                        </div>
                                                                    </div>
                                                                </th>
                                                                <td>
                                                                    <div class="row" style="padding:0px;">
                                                                        <div class="col-xl-4">
                                                                            <h3><img src="{{asset('/public/images/departure.png') }}" width="25px">
                                                                            ${data['flight_det']['departure_airport_code']}</h3>
                                                                            <span class="time">${data['flight_times_arr'][0]}</span>
                                                                            <span class="date">${data['flight_date_arr'][0]} </span>
                                                                        </div>
                                                                        <div class="col-xl-4">  
                                                                            <h3>Duration Time</h3>
                                                                            <span>10h 25m</span>
                                                                        </div>
                                                                        <div class="col-xl-4">
                                                                            <h3><img src="{{asset('/public/images/landing.png') }}" width="25px">
                                                                            ${data['flight_det']['arrival_airport_code']}</h3>
                                                                            <span class="time">${data['flight_times_arrival_arr'][0]} </span>
                                                                            <span class="date">${data['flight_date_arrival_arr'][0]} </span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>`;
                                                        }
                                                            
                                                        if(data['flight_det_more'] !== null){
                                                            for(var fl = 0; fl < data['flight_det_more'].length; fl++){
                                                          flight += `<tr>
                                                                <th>
                                                                    <div class="item-thumb" style="text-align: center;">
                                                                        <div class="image-thumb">
                                                                            <img src="{{ config('img_url') }}/public/uploads/package_imgs/${data['flight_det']['flights_image']}" style="width: 65%;">
                                                                        </div>
                                                                        <div class="text">
                                                                            <span>${data['flight_det_more'][fl]['more_other_Airline_Name2']}</span>
                                                                            <p style="margin-bottom:0px">${data['flight_det_more'][fl]['more_departure_flight_number']}</p>
                                                                            <span>${data['flight_det_more'][fl]['more_departure_Flight_Type']}</span>
                                                                        </div>
                                                                    </div>
                                                                </th>
                                                                <td>
                                                                    <div class="row style="padding:0px;">
                                                                        <div class="col-xl-4">
                                                                            <h3><img src="{{asset('/public/images/departure.png') }}" width="25px">
                                                                            ${data['flight_det_more'][fl]['more_departure_airport_code']}</h3>
                                                                          <span class="time">${data['flight_times_arr'][1 + fl]}</span>
                                                                            <span class="date">${data['flight_date_arr'][1 + fl]} </span>
                                                                        </div>
                                                                        <div class="col-xl-4">
                                                                            <h3>Duration Time</h3>
                                                                            <span>${data['flight_det_more'][fl]['more_total_Time']}</span>
                                                                        </div>
                                                                        <div class="col-xl-4">
                                                                            <h3><img src="{{asset('/public/images/landing.png') }}" width="25px">
                                                                            ${data['flight_det_more'][fl]['more_arrival_airport_code']}</h3>
                                                                              <span class="time">${data['flight_times_arrival_arr'][1 + fl]} </span>
                                                                            <span class="date">${data['flight_date_arrival_arr'][1 + fl]} </span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>`;
                                                            }
                                                            
                                                        }
                                                        
                                                        
                                                        flight += `</tbody>
                                                    </table>
                                                </div>
                                                <!-- END / ITEM -->
                                
                                                <h5 style="font-size:20px">Return Flight Details</h5>
                                                 
                                                <div class="initiative__item">
                                                    <div class="initiative-top">
                                                        <div class="title" style="font-size: 20px;margin-left: 70px;">
                                                            <div class="from-to">
                                                                <span class="from">${data['return_data']['ret_first_depaurture']}</span>
                                                                <!--<i class="mdi-arrow-right-bold"></i>!-->
                                                                <img src="{{asset('/public/images/departure.png') }}" width="25px">
                                                                <span class="to">${data['return_data']['ret_last_destination']}</span>
                                                            </div>
                                                            <div class="time">${data['return_data']['ret_first_dep_time']} | ${data['return_data']['ret_last_arrival']}</div>
                                                        </div>
                                                   
                                                    </div>
                                                    <table class="initiative-table">
                                                        <tbody>`
                                                        if(data['flight_det']['flight_type'] !== 'Indirect'){
                                                            flight += `<tr>
                                                                <th>
                                                                    <div class="item-thumb" style="text-align: center;">
                                                                        <div class="image-thumb">
                                                                            <img src="{{ config('img_url') }}/public/uploads/package_imgs/${data['flight_det']['flights_image']}" style="width: 65%;">
                                                                        </div>
                                                                        <div class="text">
                                                                            <span>${airlineName}</span>
                                                                            <p style="margin-bottom:0px">${data['flight_det']['departure_flight_number']}</p>
                                                                            <span>${departure_Flight_Type}</span>
                                                                        </div>
                                                                    </div>
                                                                </th>
                                                                <td>
                                                                    <div class="row" style="padding:0px;">
                                                                        <div class="col-xl-4">
                                                                            <h3><img src="{{asset('/public/images/departure.png') }}" width="25px">
                                                                            ${data['flight_det']['return_departure_airport_code']}</h3>
                                                                            <span class="time">${data['ret_flight_times_arr'][0]}</span>
                                                                            <span class="date">${data['ret_flight_date_arr'][0]} </span>
                                                                          
                                                                        </div>
                                                                        <div class="col-xl-4">
                                                                            <h3>Duration Time</h3>
                                                                            <span>${data['flight_det']['return_total_Time']}</span>
                                                                        </div>
                                                                        <div class="col-xl-4">
                                                                            <h3><img src="{{asset('/public/images/landing.png') }}" width="25px">
                                                                            ${data['flight_det']['return_arrival_airport_code']}</h3>
                                                                            <span class="time">${data['ret_flight_times_arrival_arr'][0]} </span>
                                                                            <span class="date">${data['ret_flight_date_arrival_arr'][0]} </span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>`;
                                                        }
                                                            
                                                        if(data['flight_det_more'] !== null){
                                                            for(var fl = 0; fl < data['flight_det_more'].length; fl++){
                                                          flight += `<tr>
                                                                <th>
                                                                    <div class="item-thumb" style="text-align: center;">
                                                                        <div class="image-thumb">
                                                                            <img src="{{ config('img_url') }}/public/uploads/package_imgs/${data['flight_det']['flights_image']}" style="width: 65%;">
                                                                        </div>
                                                                        <div class="text">
                                                                            <span>${data['flight_det_more'][fl]['more_other_Airline_Name2']}</span>
                                                                            <p style="margin-bottom:0px">${data['flight_det_more'][fl]['more_departure_flight_number']}</p>
                                                                            <span>${data['flight_det_more'][fl]['more_departure_Flight_Type']}</span>
                                                                        </div>
                                                                    </div>
                                                                </th>
                                                                <td>
                                                                    <div class="row" style="padding:0px;">
                                                                        <div class="col-xl-4">
                                                                            <h3><img src="{{asset('/public/images/departure.png') }}" width="25px">
                                                                            ${data['flight_det_more'][fl]['return_more_departure_airport_code']}</h3>
                                                                          <span class="time">${data['ret_flight_times_arr'][1 + fl]}</span>
                                                                            <span class="date">${data['ret_flight_date_arr'][1 + fl]} </span>
                                                                        </div>
                                                                        <div class="col-xl-4">
                                                                            <h3>Duration Time</h3>
                                                                            <span>${data['flight_det_more'][fl]['return_more_total_Time']}</span>
                                                                        </div>
                                                                        <div class="col-xl-4">
                                                                            <h3><img src="{{asset('/public/images/landing.png') }}" width="25px">
                                                                            ${data['flight_det_more'][fl]['return_more_arrival_airport_code']}</h3>
                                                                              <span class="time">${data['ret_flight_times_arrival_arr'][1 + fl]} </span>
                                                                            <span class="date">${data['ret_flight_date_arrival_arr'][1 + fl]} </span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>`;
                                                            }
                                                        }
                                                        flight += `</tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>`;
                       
                              
                            }else{
                                console.log('else flight');
                                var flight = '';
                            }
                                                
                            if(data['transportaion']['transportation_drop_off_location'] !== null){
                                  if(data['transportaion']['transportation_trip_type'] !== 'All_Round')
                                  { var transportation_type = data['transportaion']['transportation_trip_type'] } 
                                  else { var transportation_type = 'All Round '}
                                 
                                var date = new Date(data['transportaion']['transportation_pick_up_date']);
    
                                var transportation_pick_up_date =  date.getDate()+
                                  "/"+(date.getMonth()+1)+
                                  "/"+date.getFullYear();
                                var transportaion = `<div class="col-md-1 mt-2 mb-2">
                                                        <!--<i class="fa fa-car" aria-hidden="true" style="font-size:2rem;"></i>!-->
                                                        <img src="{{asset('/public/images/transportCar.png') }}" width="50px">
                                                    </div>
                                                    <div class="col-md-11 mt-2 mb-2"><h5>Transportation Details</h5></div>
                                                    
                                                    <div class="col-md-12">
                                                          <table class="initiative-table">
                                                            <tbody>
                                                                <tr>
                                                                    <th style="text-align: center;">
                                                                        <div class="item-thumb">
                                                                            <div class="image-thumb">
                                                                                <img src="{{ config('img_url') }}/public/uploads/package_imgs/${ data['transportaion']['transportation_image'] }" style="width: 65%;">
                                                                            </div>
                                                                            <div class="text">
                                                                                <span>Vehicle: ${ data['transportaion']['transportation_vehicle_type'] }</span>
                                                                                <span style="display:block">Type: ${transportation_type}</span>
                                                                            </div>
                                                                        </div>
                                                                    </th>
                                                                    <td>
                                                                        <div class="row" style="padding:0px;">
                                                                            <div class="col-xl-4">
                                                                                <h3><img src="{{asset('/public/images/departure.png') }}" width="25px">Pickup Location</h3>
                                                                                <h3>${ data['transportaion']['transportation_pick_up_location'] }</h3>
                                                                                <span class="date">${ data['tran_date_time']['tran_pick_date'] }</span>
                                                                                <span class="date">${ data['tran_date_time']['tran_pick_time'] }</span>
                                                                               
                                                                            </div>
                                                                            <div class="col-xl-4">
                                                                                <h3>Duration Time</h3>
                                                                                <span>${data['transportaion']['transportation_total_Time']}</span>
                                                                            </div>
                                                                            <div class="col-xl-4">
                                                                                <h3><img src="{{asset('/public/images/landing.png') }}" width="25px">Drop Off Location</h3>
                                                                                <h3>${ data['transportaion']['transportation_drop_off_location'] }</h3>
                                                                                <span class="date">${ data['tran_date_time']['tran_drop_date'] }</span>
                                                                                <span class="date">${ data['tran_date_time']['tran_drop_time'] }</span>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    
                                                                </tr>`;
                                                                
                                            if(data['transportaion']['transportation_trip_type'] == 'Return'){
                                            var date = new Date(data['transportaion']['return_transportation_pick_up_date']);
    
                                            var return_transportation_pick_up_date =  date.getDate()+
                                              "/"+(date.getMonth()+1)+
                                              "/"+date.getFullYear();
                                             transportaion +=  `<tr>
                                                <th>
                                                    <div class="item-thumb">
                                                        <div class="image-thumb">
                                                        </div>
                                                        <div class="text">
                                                          
                                                        </div>
                                                    </div>
                                                </th>
                                                 <td>
                                                    <div class="row" style="padding:0px;">
                                                        <div class="col-xl-4">
                                                            <h3>${data['transportaion']['return_transportation_pick_up_location'] }</h3>
                                                           
                                                            <span class="date">${return_transportation_pick_up_date}</span>
                                                           
                                                        </div>
                                                        <div class="col-xl-4">
                                                            <h3>Duration Time</h3>
                                                            <span>${data['transportaion']['more_transportation_total_Time']}</span>
                                                        </div>
                                                        <div class="col-xl-4">
                                                            <h3>${ data['transportaion']['return_transportation_drop_off_location'] }</h3>
                                                           
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>`;
                                            
                                            
                                        } 
                                        
                                            if(data['transporation_more'] !== null){
                                                for(var trans = 0; trans < data['transporation_more'].length; trans++){
                                                    var date = new Date(data['transporation_more'][trans]['more_transportation_pick_up_date']);
            
                                                    var return_transportation_pick_up_date =  date.getDate()+
                                                      "/"+(date.getMonth()+1)+
                                                      "/"+date.getFullYear();
                                                     transportaion +=  `<tr>
                                                        <th style="text-align: center;">
                                                            <div class="item-thumb">
                                                                <div class="image-thumb">
                                                                </div>
                                                                <div class="text">
                                                                  
                                                                </div>
                                                            </div>
                                                        </th>
                                                         <td>
                                                            <div class="row" style="padding:0px;">
                                                                <div class="col-xl-4">
                                                                    <h3>${  data['transporation_more'][trans]['more_transportation_drop_off_location'] }</h3>
                                                                   
                                                                    <span class="date">${return_transportation_pick_up_date}</span>
                                                                   
                                                                </div>
                                                                <div class="col-xl-4">
                                                                    <h3>Duration Time</h3>
                                                                    <span>${data['transportaion']['more_transportation_total_Time']}</span>
                                                                </div>
                                                                <div class="col-xl-4">
                                                                    <h3>${ data['transporation_more'][trans]['more_transportation_pick_up_location'] }</h3>
                                                                   
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>`;
                                                }
                                            } 
                                            transportaion +=    `</tbody>
                                                                    </table>
                                                                </div>`;
                                        
                       
                  
                            }else{
                                console.log('else transportaion');
                                var transportaion = '';
                            }
                                                
                            if(data['tour_data']['Itinerary_details'][0]['Itinerary_title'] !== null){
                                // console.log('iterniser '+data['tour_data']['Itinerary_details'])
                                var iternaryDetails = JSON.parse(data['tour_data']['Itinerary_details']);
                                if(iternaryDetails[0]['Itinerary_title'] !== null){
                                    // console.log(iternaryDetails)
                                    var iternary = '';
                                    for(var i=0; i<iternaryDetails.length; i++){
                                        
                                         iternary +=`<li>
                                                        <h4>${iternaryDetails[i]['Itinerary_title']} : ${iternaryDetails[i]['Itinerary_city']} </h4>
                                                        <p>${iternaryDetails[i]['Itinerary_content']}</p>
                                                    </li>`
                                    }
                                }else{
                                console.log('else iternary');
                                var iternary = '';
                            }
                            }else{
                                console.log('else iternary');
                                var iternary = '';
                            }
                              
                            if(data['iternery'] !== null){
                                var iternary1 = '';
                                for(var i=0; i<data['iternery'].length; i++){
                                    
                                     iternary1 +=`<li>
                                                    <h4>${data['iternery'][i]['more_Itinerary_title']} : ${data['iternery'][i]['more_Itinerary_city']} </h4>
                                                    <p>${data['iternery'][i]['more_Itinerary_content']}</p>
                                                </li>`
                                }
                            }else{
                                 var iternary1 = '';
                              }
                         
                            if(data['tour_data']['quad_grand_total_amount'] != 0 || data['tour_data']['quad_grand_total_amount'] != 'null'){
                                    var quadPrice  = "Quad: "+data['tour_data']['currency_symbol']+" "+data['tour_data']['quad_grand_total_amount'];
                            }else{
                                    var quadPrice = '';
                                }
                                
                            if(data['tour_data']['triple_grand_total_amount'] != 0 || data['tour_data']['triple_grand_total_amount'] != 'null'){
                                    var triplePrice  = "Triple: "+data['tour_data']['currency_symbol']+" "+data['tour_data']['triple_grand_total_amount'];
                            }else{
                                    var triplePrice = '';
                                }
                                
                            if(data['tour_data']['double_grand_total_amount'] != 0 || data['tour_data']['double_grand_total_amount'] != 'null'){
                                    var doublePrice  = "Double: "+data['tour_data']['currency_symbol']+" "+data['tour_data']['double_grand_total_amount'];
                            }else{
                                    var doublePrice = '';
                                } 
                                
                            $('#exampleModalLabel').html(data['tour_data']['title']);
                            var tourHtml = `<div class="row" style="padding:1rem;">
                                                <div class="col-md-1">
                                                        <img style="width:50px" src="{{asset('public/admin_package/frontend/images/tour-info.jpg') }}" style="width: 65%;">
                                                </div>
                                                <div class="col-md-11"><h5 style="font-size: 30px;">Tour Information</h5></div>
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                         <img src="{{ config('img_url') }}/public/uploads/package_imgs/${data['tour_data']['tour_banner_image']}" style="width:100%">
                                                        </div>
                                                        <div class="col-md-8">
                                                            <div class="row">
                                                                 <div class="col-md-3">
                                                                    <p style="margin-bottom:0">Tour Name:</p>
                                                                    <p style="margin-bottom:0">Tour Price:</p>
                                                                    <p style="margin-bottom:0">Check-In:</p>
                                                                    <p style="margin-bottom:0">Check-Out:</p>
                                                                    <p style="margin-bottom:0">Duration:</p>
                                                                    <p style="margin-bottom:0">Destinations:</p>
                                                                </div>
                                                                <div class="col-md-9">
                                                                    <p style="margin-bottom:0">${data['tour_data']['title']}</p>
                                                                    <p style="margin-bottom:0">${quadPrice} / ${triplePrice} / ${doublePrice}</p>
                                                                    <p style="margin-bottom:0">${startDate}</p>
                                                                    <p style="margin-bottom:0">${endDate}</p>
                                                                    <p style="margin-bottom:0">${data['tour_data']['time_duration']} Nights</p>
                                                                    <p style="margin-bottom:0">${tours_locations}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <hr style="height:5px;margin-top:10px">
                                                <div class="col-md-1 mt-2 mb-2">
                                                        <!-- <i class="mdi-office-building" aria-hidden="true" style="font-size:2rem;"></i> 
                                                        <img style="width:50px" src="{{asset('public/admin_package/frontend/images/tick.png') }}">!-->
                                                        <img src="{{asset('/public/images/hotel1.jpg') }}" width="50px">
                                                </div>
                                                <div class="col-md-11 mt-2 mb-2"><h5 style="font-size:30px">Hotel Information</h5></div>
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        ${hotels}
                                                    </div>
                                                 </div>
                                                <!--<div class="col-md-1 mt-2 mb-2">
                                                        <img style="width:50px" src="{{asset('public/admin_package/frontend/images/tick.png') }}">
                                                </div>
                                                <div class="col-md-11 mt-2 mb-2"><h5>Day By Day itenery</h5></div>
                                                
                                                    <div class="col-sm-12">
                                                        <ul class="itenery-ul">
                                                            ${iternary}
                                                            ${iternary1}
                                                        </ul> 
                                                    </div>!-->
                                                
                                                <hr style="height:5px;margin-top:10px">
                                                ${flight}
                                                <hr style="height:5px;margin-top:10px">
                                                ${transportaion}
                                                <hr style="height:5px;margin-top:10px">
                                            </div>`
                            $('#tour_data_detail').html(tourHtml);
                               
                            // console.log('if exmple');
                        }
                        
                        else{
                            $('#exampleModalLabel').html(data['tour_data']['title']);
                            console.log('else exmple');
                             
                            var adultPrice = data['tour_data']['sale_price']
                            if(data['tour_data']['child_sale_price'] != 0 && data['tour_data']['child_sale_price'] != null){
                                 var childprice = data['tour_data']['child_sale_price'] 
                            }else{
                                  var childprice = data['tour_data']['sale_price'] 
                            }
                             
                            if(data['tour_data']['what_expect'][0]['title'] !== null){
                                    console.log('iterniser '+data['tour_data']['what_expect'])
                                    var whatExpectDetails = JSON.parse(data['tour_data']['what_expect']);
                                    if(whatExpectDetails[0]['title'] !== null){
                                    console.log(whatExpectDetails)
                                     var whatExpects = '';
                                        for(var i=0; i<whatExpectDetails.length; i++){
                                            
                                             whatExpects +=`<li>
                                                            <h4>${whatExpectDetails[i]['title']} </h4>
                                                            <p>${whatExpectDetails[i]['expect_content']}</p>
                                                        </li>`
                                        }
                                    }else{
                                        var whatExpects = '';
                                    }
                            }else{
                                 var whatExpects = '';
                              }
                              
                            var tourHtml = `<div class="row" style="padding:1rem;">
                                                <div class="col-md-1">
                                                        <img style="width:50px" src="{{asset('public/admin_package/frontend/images/tour-info.jpg') }}">
                                                </div>
                                                <div class="col-md-11"><h5>Activity Information</h5></div>
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                         <img src="{{ config('img_url') }}/public/images/activites/${data['tour_data']['featured_image']}" alt="">
                                                        </div>
                                                        <div class="col-md-8">
                                                            <div class="row">
                                                                 <div class="col-md-3">
                                                                    <p style="margin-bottom:0">Activtiy Name:</p>
                                                                    <p style="margin-bottom:0">Adult Price:</p>
                                                                    <p style="margin-bottom:0">Child Price:</p>
                                                                    <p style="margin-bottom:0">Check-In:</p>
                                                                    <p style="margin-bottom:0">Duration:</p>
                                                                    <p style="margin-bottom:0">Destinations:</p>
                                                                </div>
                                                                <div class="col-md-9">
                                                                    <p style="margin-bottom:0">${data['tour_data']['title']}</p>
                                                                    <p style="margin-bottom:0">${adultPrice}</p>
                                                                    <p style="margin-bottom:0">${childprice}</p>
                                                                    <p style="margin-bottom:0">${data['tour_data']['activity_date']}</p>
                                                                    <p style="margin-bottom:0">${data['tour_data']['duration']} Hours</p>
                                                                    <p style="margin-bottom:0">${data['tour_data']['tours_locations']}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                               
                                                 <hr>
                                              <div class="col-md-1 mt-2 mb-2">
                                                        <img style="width:50px" src="{{asset('public/admin_package/frontend/images/tick.png') }}">
                                                </div>
                                             <div class="col-md-11 mt-2 mb-2"><h5>Availibilty</h5></div>
                                            
                                             <div class="col-sm-12">
                                                ${data['Availibilty']}
                                             </div>
                                                
                                                <hr>
                                              <div class="col-md-1 mt-2 mb-2">
                                                        <img style="width:50px" src="{{asset('public/admin_package/frontend/images/tick.png') }}">
                                                </div>
                                                <div class="col-md-11 mt-2 mb-2"><h5>What To Expect</h5></div>
                                                
                                                 <div class="col-sm-12">
                                                      <ul class="itenery-ul">
                                                          ${whatExpects}
                                                      
                                                        </ul>
                                                 </div> 
                                                 
                                                    <hr>
                                              <div class="col-md-1 mt-2 mb-2">
                                                        <img style="width:50px" src="{{asset('public/admin_package/frontend/images/tick.png') }}">
                                                </div>
                                             <div class="col-md-11 mt-2 mb-2"><h5>Meeting And Pickupt</h5></div>
                                            
                                             <div class="col-sm-12">
                                                  
                                                      ${data['tour_data']['meeting_and_pickups']}
                                                  
                                                   
                                             </div> 
                                             
                                             
                                                  <hr>
                                              <div class="col-md-1 mt-2 mb-2">
                                                        <img style="width:50px" src="{{asset('public/admin_package/frontend/images/tick.png') }}">
                                                </div>
                                             <div class="col-md-11 mt-2 mb-2"><h5>What's Included</h5></div>
                                            
                                             <div class="col-sm-12">
                                                    <h6>Whats Included?</h6>
                                                    </p>${data['tour_data']['whats_included']}</p>
                                                    
                                                     <h6>Whats Excluded?</h6>
                                                    </p>${data['tour_data']['whats_excluded']}</p>
                                                     
                                                  
                                                    </ul>
                                             </div>
                                             
                                                
                                             
                                                 
                                                
                                            </div>`
                                            $('#tour_data_detail').html(tourHtml)
                        }
                    }
                });
            }
            
            $('#adultsID').change(function(){
                var adutls = $(this).val();
                if(adutls > 0){
                    $('#add_to_cart').css('display','block');
                }else{
                    $('#add_to_cart').css('display','none');
                }
            });
            
            function book_Now(id){
                $('#toure_id').empty();
                $('#double_price').empty();
                $('#triple_price').empty();
                $('#quad_price').empty();
                $.ajax({
                    url:"{{URL::to('expenses_Income_client_wise_data')}}" + '/' + id,
                    method: "get",
                    data: {
                        id: id, 
                    },
                    success: function (response) {
                        // console.log(response);
                        var data = response['data'];
                        $.each(data, function(key, value) {
                            var toure_id = value.id;
                            $('#toure_id').val(toure_id);
                            
                            var currency_symbol             = value.currency_symbol;
                            var double_grand_total_amount   = value.double_grand_total_amount;
                            var triple_grand_total_amount   = value.triple_grand_total_amount;
                            var quad_grand_total_amount     = value.quad_grand_total_amount;
                            
                            if(double_grand_total_amount !== null || double_grand_total_amount !== "" || double_grand_total_amount !== 0){
                                var double_price = `${currency_symbol} ${double_grand_total_amount}`;
                                $('#double_price').append(double_price);
                            }else{
                                $('.double_price').empty();
                            }
                            
                            if(triple_grand_total_amount !== null || triple_grand_total_amount !== "" || triple_grand_total_amount !== 0){
                                var triple_price = `${currency_symbol} ${triple_grand_total_amount}`;
                                $('#triple_price').append(triple_price);
                            }else{
                                $('.triple_price').empty();
                            }
                            
                            if(quad_grand_total_amount !== null || quad_grand_total_amount !== "" || quad_grand_total_amount !== 0){
                                var quad_price   = `${currency_symbol} ${quad_grand_total_amount}`;
                                $('#quad_price').append(quad_price);
                            }else{
                                $('.quad_price').empty();
                            }
                        });
                    }
                });
            }
    
    </script>

    <script>
        $(document).ready(function(){
            $("#myInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#myTable tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
            
            $('#my_Table').DataTable({
                pagingType: 'full_numbers',
            });
        });
    </script>

@stop

@section('slug')

@stop