@extends('template/frontend/userdashboard/layout/default')
@section('content')
    
    <?php
        $aviable_days       = [];
        $avaiable_days_name = [];
        
        foreach($Availibilty as $id => $av_res){
            if(isset($av_res->enable)){
                $day = '';
                if($id == '1'){
                    $day = "Monday";
                    array_push($aviable_days,1);
                    array_push($avaiable_days_name,$day);
                }
              
                if($id == '2'){
                    $day = "Tuesday";
                    array_push($aviable_days,2);
                    array_push($avaiable_days_name,$day);
                }
              
                if($id == '3'){
                    $day = "Wednesday";
                    array_push($aviable_days,3);
                    array_push($avaiable_days_name,$day);
                }
              
                if($id == '4'){
                    $day = "Thursday";
                    array_push($aviable_days,4);
                    array_push($avaiable_days_name,$day);
                }
              
                if($id == '5'){
                    $day = "Friday";
                    array_push($aviable_days,5);
                    array_push($avaiable_days_name,$day);
                }
              
                if($id == '6'){
                    $day = "Saturday";
                    array_push($aviable_days,6);
                    array_push($avaiable_days_name,$day);
                }
              
                if($id == '7'){
                    $day = "Sunday";
                    array_push($aviable_days,0);
                    array_push($avaiable_days_name,$day);
                }
            }
        }
    ?>
    
    <div class="content-wrapper">
        <section class="content" style="padding: 30px 50px 0px 50px;">
            <div class="container-fluid">
                
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Activity</a></li>
                                    <li class="breadcrumb-item active">Book Activity</li>
                                </ol>
                            </div>
                            <h4 class="page-title">Book Activity</h4>
                        </div>
                        
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
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="col-sm-6">
                                    <div class="detail-sidebar">
                                        <div class="call-to-book">
                                            <i class="fa fa-phone" style="width: 30px;height: 30px;line-height: 26px;border: 2px solid #666;text-align: center;font-size: 18px;color: #666;border-radius: 50%;margin-right: 12px;margin-top: 2px;"></i>
                                            <em>Call to book</em>
                                            <span>0121 777 2522</span>
                                        </div>
                                        
                                        <form action="{{URL::to('super_admin/addToCart_ActivityAdmin')}}" method="post">
                                            @csrf
                                            <div class="booking-info">
                                                <h3>Booking info</h3>
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#exampleModal1" class="d-none">
                                                    Cancellation Policy ?
                                                </a>
                                                
                                                <div class="form-select-date">
                                                    <div class="form-elements">
                                                        <label>Travel Date</label>
                                                        <div class="form-item">
                                                            <i class="awe-icon awe-icon-calendar"></i>
                                                            <input type="date" class="form-control" id="datepicker" name="activity_date" required>
                                                            @if(isset($avaiable_days_name) && count($avaiable_days_name) > 0)
                                                                <p>(Available Days : @foreach($avaiable_days_name as $avaiable_days_name_value) <b>{{ $avaiable_days_name_value }},</b> @endforeach)</p>
                                                            @endif
                                                            <input type="text" name="pakage_type" hidden value="activity">
                                                        </div>
                                                    </div>
                                                </div>
                                            
                                                <div class="form-select-date">
                                                    <div class="form-elements">
                                                        <label style="ont-weight: 600;font-size: 13px;color: #1E1E1F;margin-bottom: 8px;">Adult Price: {{ $tour_details->currency_symbol." ".$tour_details->sale_price }}</label>
                                                        <label style="ont-weight: 600;font-size: 13px;color: #1E1E1F;margin-bottom: 8px;">
                                                            Child Price: 
                                                            @if($tour_details->child_sale_price != 0 AND $tour_details->child_sale_price != null)
                                                                {{ $tour_details->currency_symbol." ".$tour_details->child_sale_price }} 
                                                            @else 
                                                                {{ $tour_details->currency_symbol." ".$tour_details->sale_price }} 
                                                            @endif
                                                        </label>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-label custom-control custom-radio custom-control-inline">
                                                    <div class="row">
                                                        
                                                        <div class="col-md-12" style="padding:10px;">
                                                            <input type="checkbox" class="custom-control-input" id="selectAgent" name="agent_select" value="agent_select">
                                                            <label  class="custom-control-label" for="selectAgent">Book for Agent</span></label>
                                                        </div>
                                                        
                                                        <div class="col-md-12 agent_Name" style="display:none;padding:10px;">
                                                             <label class="form-label">Select Agent</label>
                                                             <select class="form-control"  id="agent_Name" name="agent_Name">
                                                               <option value="-1">Choose...</option>
                                                                @if(isset($agents_list) && $agents_list !== null && $agents_list !== '')
                                                                    @foreach($agents_list as $agents_list_res)
                                                                        <option value="{{ $agents_list_res->id }}">{{ $agents_list_res->agent_Name }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                        
                                                        <div class="col-md-12" style=padding:10px;>
                                                            <input type="checkbox" class="custom-control-input" id="selectCustomer" name="customer_select" value="booking_by_customer">
                                                            <label  class="custom-control-label" for="selectCustomer">Book for Customer</span></label>
                                                        </div>
                                                       
                                                        <div class="col-md-12 customer_Name" style="display:none;padding:10px;">
                                                             <label class="form-label">Select Customer</label>
                                                             <select class="form-control"  id="customer_Name" name="customer_Name">
                                                               <option value="-1">Choose...</option>
                                                                @if(isset($customer_details) && $customer_details !== null && $customer_details !== '')
                                                                    @foreach($customer_details as $cust_res)
                                                                        <option value="{{ $cust_res->id }}">{{ $cust_res->name }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <label class="form-label">Adult</label>
                                                        <div class="form-item">
                                                            <input type="text" hidden name="toure_id" value="{{ $tour_details->id }}">
                                                            <select name="adults" id="adults" class="form-control">
                                                                <option>0</option>
                                                                <option>1</option>
                                                                <option>2</option>
                                                                <option>3</option>
                                                                <option>4</option>
                                                                <option>5</option>
                                                                <option>6</option>
                                                                <option>7</option>
                                                                <option>8</option>
                                                                <option>9</option>
                                                                <option>10</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <label class="form-label">Children </label>
                                                        <div class="form-item">
                                                            <select name="childs" class="form-control">
                                                                <option>0</option>
                                                                <option>1</option>
                                                                <option>2</option>
                                                                <option>3</option>
                                                                <option>4</option>
                                                                <option>5</option>
                                                                <option>6</option>
                                                                <option>7</option>
                                                                <option>8</option>
                                                                <option>9</option>
                                                                <option>10</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                             
                                                @if(isset($addtional_service_arr))
                                                    <div class="widget widget_has_radio_checkbox">
                                                        
                                                        <h3 style="font-size: 18px;font-weight: 700;color: #1F2021;margin: 0;margin-bottom: 18px;">Additional Services   
                                                            <button type="button" class="border border-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Select Additional Services(Per Person)">
                                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                            </button>
                                                        </h3>
                                                        
                                                        <ul>
                                                            <?php $x= 2; ?>
                                                            @foreach($addtional_service_arr as $service_res)
                                                                <li>
                                                                    <label style="position: relative;display: block;margin: 0;user-select: none;">
                                                                        <input type="checkbox" id="chechbox{{ $x }}" onclick="checkPerPerson('{{ $service_res->service_type }}','chechbox{{ $x }}','first_checkbox{{ $x }}',{{ $service_res->service_type }})"  name="additonal_service[]" value="{{ $service_res->service_name }}">
                                                                        <i class="awe-icon awe-icon-check"></i>
                                                                        {{ $service_res->service_name }} 
                                                                        (<?php if($service_res->service_type == 0){ echo "One-Time"; } if($service_res->service_type == 2){ echo "Per-Day"; }?>)
                                                                        <i class="bi bi-arrow-right" aria-hidden="true"></i> <span> {{ $tour_details->currency_symbol." ".$service_res->service_price }}</span>
                                                                    </label>
                                                                    <div id="first_checkbox{{ $x }}"></div>
                                                                </li>
                                                                <?php $x++; ?>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif  
                                                
                                                <?php 
                                                    $complete_date  = explode('-',$tour_details->activity_date);
                                                    $start_date     = explode('/',$complete_date[0]);
                                                    $month          = $start_date[0];
                                                    $day            = $start_date[1];
                                                    $year           = $start_date[2];
                                                    $year           = trim($year,' ');
                                                    $end_date       = explode('/',$complete_date[1]);
                                                    $month_end      = $end_date[0];
                                                    $month_end      = trim($month_end,' ');
                                                    $day_end        = $end_date[1];
                                                    $year_end       = $end_date[2];
                                                    
                                                    $new_SD = $year.'-'.$month.'-'.$day;
                                                    $new_ED = $year_end.'-'.$month_end.'-'.$day_end;
                                                ?>
                                                
                                                <div class="form-submit" style="overflow: hidden;">
                                                    <div class="add-to-cart" style="box-sizing: border-box;">
                                                        Select atleast one adult in order to proceed with your booking.
                                                        <button type="submit" id="add_to_cart" style="display:none;font-size: 13px;color: #FCF2E5;background-color: #FF6666;border: 0;cursor: pointer;line-height: 46px;height: 46px;padding: 0 26px;border-radius: 0 0 5px 5px;">
                                                            <i class="fa fa-shopping-cart"></i>Add to Cart
                                                        </button>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </form>
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
        
        $(document).ready(function() {
            travel_date_working();
        });
        
        function travel_date_working(){ 
            var start_date  = {!!json_encode($new_SD)!!};
            var end_date    = {!!json_encode($new_ED)!!};
            
            $("#datepicker").attr('min', start_date);
            $("#datepicker").attr('max', end_date);
            
            if (start_date && end_date && start_date <= end_date) {
                var avaiableDays    = [];
                var currentDate     = new Date(start_date);
                var end_date        = new Date(end_date);
                
                while (currentDate <= end_date) {
                    avaiableDays.push(currentDate.toISOString().split('T')[0]);
                    currentDate.setDate(currentDate.getDate() + 1);
                }
            } 
            
            var avaiableDays1   = {{ json_encode($aviable_days) }};
            var restrictedDays  = [];
            
            $.each(avaiableDays, function(key, avaiableDays_value) {
                var A_date  = new Date(avaiableDays_value);
                var day     = A_date.getDay();
                
                $.each(avaiableDays1, function(key, avaiableDays1_value) {
                    if(day == avaiableDays1_value){
                        restrictedDays.push(A_date.toISOString().split('T')[0]);
                    }
                });
            });
            
            if(restrictedDays.length > 0){
                restrictedDays = restrictedDays;
            }else{
                restrictedDays = avaiableDays;
            }
                
            $("#datepicker").on('input', function() {
                var selectedDate = $(this).val();
                if (selectedDate && restrictedDays.includes(selectedDate)) {
                    console.log('Not Restricted');
                }else{
                    $(this).val('');
                    alert('Activity is not available in you selected date.');
                }
            });
        }
        
        $('#adults').change(function(){
            var adutls = $(this).val();
            if(adutls >0){
                $('#add_to_cart').css('display','block');
            }else{
                $('#add_to_cart').css('display','none');
            }
        })
        
        function checkPerPerson(type,idBox,idAppend,paymentType){
          if($('#'+idBox+'').prop('checked')){
                if(paymentType == 0){
                    var input = ` <div class="row">
                                    <div class="col-md-12"><input type="text" placeholder="Enter Persons" name="service_adults[]" class="form-control"></div>
                                    <div class="col-md-6"><input type="text" hidden placeholder="Enter Days" name="service_days[]"></div>
                                    <div class="col-md-6"><input type="text" hidden placeholder="Enter Days" class="form-control" name="service_dates[]"></div>
                                  </div>`;
                }else{
                    var input = `<div class="row">
                                    <div class="col-md-12"><input type="text" placeholder="Enter Persons" name="service_adults[]" class="form-control"></div>
                                    <div class="col-md-6"><input type="text" placeholder="Enter Days" name="service_days[]"></div>
                                    <div class="col-md-6"><input type="text" placeholder="Enter Days" class="config-demo form-control" name="service_dates[]"></div>
                                  </div>`;
                }
                $('#'+idAppend+'').html(input)
            }else{
                $('#'+idAppend+'').html('')
                console.log('this is not checked');
            }
            updateConfig();
        }
    
        function updateConfig() {
            var options = {};
            $('.config-demo').daterangepicker(options, function(start, end, label) {
                console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')'); 
            }).click();
        }
        
</script>
    
    <script>
        
        $("#selectAgent").click(function() {
            if ($("#selectAgent").is(':checked')) {
                $("#selectCustomer").prop('checked', false);
                $('.customer_Name').css('display','none')
                  console.log('button is checked')
                $('.agent_Name').css('display','block')
                $('#customer_Name').val('-1').change();
            } else {
                console.log('button is not checked')
                 $('.agent_Name').css('display','none')
            }
        });
        
        $("#selectCustomer").click(function() {
            if ($("#selectCustomer").is(':checked')) {
                  console.log('button is checked')
                  
                $("#selectAgent").prop('checked', false);
                $('.agent_Name').css('display','none')
                $('.customer_Name').css('display','block')
                
                $('#agent_Name').val('-1').change();
                
            } else {
                console.log('button is not checked')
                 $('.customer_Name').css('display','none')
            }
        });

    </script>
    
@endsection