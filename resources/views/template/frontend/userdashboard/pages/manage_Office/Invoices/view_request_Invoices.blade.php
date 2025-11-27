
<?php

//print_r($data);die();

?>

@extends('template/frontend/userdashboard/layout/default')
@section('content')

    <?php $currency=Session::get('currency_symbol'); ?>
    
    <style>
        .nav-link{
          color: #575757;
          font-size: 18px;
        }
    </style>

    <div class="content-wrapper">
        
        <section class="content" style="padding: 10px 20px 0px 20px">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-primary" style="height: 184px;text-align: center;padding: 15px 0px 0px 0px;background-color: #313a46 !important;color: white;">
                  <div class="icon mb-2">
                    <!-- <i class="mdi mdi-eye me-1"></i> -->
                    <span class="iconify" data-icon="uil:plane-departure" data-width="70"></span>
                  </div>
                  <div class="inner">
                    
                    <p>Total Flights</p>
    
                    <h3>150</h3>
    
                  </div>
                  
                </div>
              </div>
              <!-- ./col -->
              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-success" style="height: 184px;text-align: center;padding: 15px 0px 0px 0px;background-color: #313a46 !important;color: white;">
                  <div class="icon mb-2">
                    <span class="iconify" data-icon="uil-home-alt" data-width="70"></span>
                  </div>
                  <div class="inner">
                    <h3>53<sup style="font-size: 15px">%</sup></h3>
    
                    <p>Total Hotels</p>
                  </div>
                  
                </div>
              </div>
              <!-- ./col -->
              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box" style="height: 184px;text-align: center;padding: 15px 0px 0px 0px;background-color: #313a46 !important;color: white;">
                  <div class="icon mb-2">
                    <span class="iconify" data-icon="uil-pricetag-alt" data-width="70"></span>
                  </div>
                  <div class="inner">
                    <h3>44</h3>
    
                    <p>Total Transfer</p>
                  </div>
                  
                </div>
              </div>
              <!-- ./col -->
              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info" style="height: 184px;text-align: center;padding: 15px 0px 0px 0px;background-color: #313a46 !important;color: white;">
                  <div class="icon mb-2">
                    <span class="iconify" data-icon="uil-dollar-sign-alt" data-width="70"></span>
                  </div>
                  <div class="inner">
                    <h3>65</h3>
    
                    <p>Commision</p>
                  </div>
                  
                </div>
              </div>
              <!-- ./col -->
            </div>
          </div>
        </section>
        
        <section class="content" style="padding: 30px 50px 0px 50px;">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12 col-6">
                <nav class="breadcrumb push">
                    <a class="breadcrumb-item" href="#">Dashboard</a> 
                    <span class="breadcrumb-item active">View Agent Invoices</span>
                </nav>
              </div>
            </div>
          </div>
        </section>
    
        <section class="content" style="padding: 30px 50px 0px 50px;">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-6">
                        <div class="panel-body padding-bottom-none">
                            <div class="block-content block-content-full">
                                <div id="example_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table style="border: 2px solid black;" class="table table-bordered table-striped table-vcenter dataTable no-footer" id="myTable" role="grid" aria-describedby="example_info">
                                                
                                                <thead>
                                                    <tr role="row">
                                                        <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;text-align:center;">SR No.</th>
                                                        <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;text-align:center;">Invoice Id</th>
                                                        <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;text-align:center;">Invoice Agent</th>
                                                        <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;text-align:center;">Lead Name</th>
                                                        <th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="email: activate to sort column ascending" style="width: 175.073px;text-align:center;">Prepared By</th>
                                                        <th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Date: activate to sort column ascending" style="width: 138.958px;text-align:center;">Total Paybale</th>
                                                        <th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Date: activate to sort column ascending" style="width: 138.958px;text-align:center;">Date</th>
                                                        <!--<th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Validity: activate to sort column ascending" style="width: 143.438px;text-align:center;">Validity</th>-->
                                                        <th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Options: activate to sort column ascending" style="width: 175.642px;text-align:center;">Options</th>
                                                    </tr>
                                                </thead>
                                                
                                                <tbody style="text-align: center;">
                                                    <?php $i = 1; ?>
                                                    @if(isset($data))
                                                    @foreach ($data as $value)
                                                        <?php
                                                            // dd($value);
                                                            $date4days = date('Y-m-d',strtotime($value->created_at. ' + 5 days'));
                                                            $currdate = date('Y-m-d');
                                                        ?>
                                                        <tr role="row" class="odd">
                                                            <td class="sorting_1">{{ $i }} </td>
                                                            <td class="sorting_1">{{ $value->id }} </td>
                                                            <td class="sorting_1">{{ $value->agent_Name }} </td>
                                                            <td class="sorting_1">{{ $value->f_name }} {{ $value->middle_name }}</td>
                                                            <td>{{ $value->tour_author }}</td>
                                                            
                                                            <?php
                                                                $markup_details=json_decode($value->markup_details);
                                                                foreach($markup_details as $markup_details)
                                                                {
                                                                    if($markup_details->markup_Type_Costing == 'flight_Type_Costing')
                                                                    {
                                                                        $flight_Type_Costing=$markup_details->markup_price;
                                                                    }
                                                                    if($markup_details->markup_Type_Costing == 'transportation_Type_Costing')
                                                                    {
                                                                        $transportation_Type_Costing=$markup_details->markup_price;
                                                                    }
                                                                    if($markup_details->markup_Type_Costing == 'visa_Type_Costing')
                                                                    {
                                                                        $visa_Type_Costing=$markup_details->markup_price;
                                                                    }
                                                                }
                                                            ?>
                                                            <td><?php echo $currency ?>{{($value->quad_grand_total_amount + $value->triple_grand_total_amount + $value->double_grand_total_amount)  * $value->no_of_pax_days; }}</td>
                                                          
                                                            <td>{{ Date('Y-m-d',strtotime($value->created_at)) }}</td>
                                                            
                                                            @if($date4days > $currdate)
                                                                <td>
                                                                    <div class="dropdown card-widgets">
                                                                        <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                                            <i class="dripicons-dots-3"></i>
                                                                        </a>
                                                                        <div class="dropdown-menu dropdown-menu-end" style="">
                                                                            <!--<a href="{{ route('payable_ledger',$value->id) }}" class="dropdown-item"  target="_blank">View Payable Ledger</a>-->
                                                                            <!--<a href="{{ route('receivAble_ledger',$value->id) }}" class="dropdown-item"  target="_blank">View Receivable Ledger</a>-->
                                                                             <a href="{{URL::to('request_availability_send_email')}}/{{$value->id}}" class="dropdown-item"  target="_blank">Send Email</a>
                                                                            <!--<a href="{{ route('pay_invoice_Agent',$value->id) }}" class="dropdown-item"  target="_blank">Pay Amount</a>-->
                                                                            <!--<a href="{{ route('invoice_Agent',$value->id) }}" class="dropdown-item"  target="_blank">View Invoice</a>-->
                                                                            <!--<a class="dropdown-item"  target="_blank" onclick="add_more_passenger({{$value->id}})" data-bs-toggle="modal" data-bs-target="#add-more-passenger-modal">Add More Passengers</a>-->
                                                                            <a href="{{URL::to('request_invoice_edit')}}/{{$value->id}}" class="dropdown-item"  target="_blank">Edit Invoice</a>
                                                                            @if($value->confirm == 1)
                                                                            <a  id="confirm_Quotation" href="{{URL::to('request_invoice_pay_amount')}}/{{$value->id}}" class="dropdown-item"  target="_blank">Pay Amount</a>
                                                                              <a  id="confirm_Quotation" href="" class="dropdown-item"  target="_blank">Already Confirm</a>
                                                                              
                                                                              
                                                                               <a  id="confirm_Quotation" href="{{URL::to('request_invoice_confirmed_view')}}/{{$value->id}}" class="dropdown-item"  target="_blank">View Invoice</a>
                                                                            @else
                                                                              <a id="confirm_Quotation" href="{{URL::to('request_invoice_confirmed')}}/{{$value->id}}" class="dropdown-item"  target="_blank">Confirm</a>
                                                                               <a  id="confirm_Quotation" href="{{URL::to('request_invoice_confirmed_view')}}/{{$value->id}}" class="dropdown-item"  target="_blank">View Invoice</a>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            @elseif($date4days < $currdate)
                                                                <td>
                                                                    <div class="dropdown card-widgets">
                                                                        <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                                            <i class="dripicons-dots-3"></i>
                                                                        </a>
                                                                        <div class="dropdown-menu dropdown-menu-end" style="">
                                                                            <!--<a href="{{ route('payable_ledger',$value->id) }}" class="dropdown-item"  target="_blank">View Payable Ledger</a>-->
                                                                            <!--<a href="{{ route('receivAble_ledger',$value->id) }}" class="dropdown-item"  target="_blank">View Receivable Ledger</a>-->
                                                                            <a href="{{URL::to('request_availability_send_email')}}/{{$value->id}}" class="dropdown-item"  target="_blank">Send Email</a>
                                                                            <!--<a href="{{ route('pay_invoice_Agent',$value->id) }}" class="dropdown-item"  target="_blank">Pay Amount</a>-->
                                                                            <a href="{{ route('invoice_Agent',$value->id) }}" class="dropdown-item"  target="_blank">View Invoice</a>
                                                                            <!--<a class="dropdown-item"  target="_blank" onclick="add_more_passenger({{$value->id}})" data-bs-toggle="modal" data-bs-target="#add-more-passenger-modal">Add More Passengers</a>-->
                                                                            <a href="{{URL::to('request_invoice_edit')}}/{{$value->id}}" class="dropdown-item"  target="_blank">Edit Invoice</a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            @else
                                                                <td>
                                                                    <div class="dropdown card-widgets">
                                                                        <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                                            <i class="dripicons-dots-3"></i>
                                                                        </a>
                                                                        <div class="dropdown-menu dropdown-menu-end" style="">
                                                                            <!--<a href="{{ route('payable_ledger',$value->id) }}" class="dropdown-item"  target="_blank">View Payable Ledger</a>-->
                                                                            <!--<a href="{{ route('receivAble_ledger',$value->id) }}" class="dropdown-item"  target="_blank">View Rceevable Ledger</a>-->
                                                                            <a href="{{URL::to('request_availability_send_email')}}/{{$value->id}}" class="dropdown-item"  target="_blank">Send Email</a>
                                                                            <!--<a href="{{ route('pay_invoice_Agent',$value->id) }}" class="dropdown-item"  target="_blank">Pay Amount</a>-->
                                                                            <!--<a href="{{ route('invoice_Agent',$value->id) }}" class="dropdown-item"  target="_blank">View Invoice</a>-->
                                                                            <!--<a class="dropdown-item"  target="_blank" onclick="add_more_passenger({{$value->id}})" data-id="{{ $value->id }}" data-bs-toggle="modal" data-bs-target="#add-more-passenger-modal">Add More Passengers</a>-->
                                                                            <a href="{{URL::to('request_invoice_edit')}}/{{$value->id}}" class="dropdown-item"  target="_blank">Edit Invoice</a>
                                                                            <a id="confirm_Quotation" href="{{URL::to('request_invoice_confirmed')}}/{{$value->id}}" class="dropdown-item"  target="_blank">Confirm</a> 
                                                                        </div>
                                                                    </div>
                                                                </td> 
                                                            @endif
                                                        </tr>
                                                        
                                                        <?php $i++ ?>
                                                    @endforeach 
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <div id="add-more-passenger-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" style="margin-right: 700px;">
                <div class="modal-content" style="width: 1100px;">
                    <div class="modal-header">
                        <h4 class="modal-title" id="standard-modalLabel">Add more Passenger</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{URL::to('add_more_passenger_Invoice')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <h3>Lead Passenger Details</h3>
                                <div class="col-lg-12">
                                    <h5>Lead Passenger Name : <span id="lead_name_ID"></span></h5>
                                    <p>Maximum Pax :<span id="no_of_pax_days_ID"></span></p>
                                    <input type="hidden" value="Invoice" id="package_Type" name="package_Type">
                                    <input type="hidden" value="" id="no_of_pax_days_Input" name="no_of_pax_days_Input">
                                    <input type="hidden" value="" id="invoice_Id_Input" name="invoice_Id_Input">
                                    <input type="hidden" value="" id="count_P_Input" name="count_P_Input">
                                    <input type="hidden" value="" id="count_P_Input1">
                                    <input type="hidden" value="" id="more_Passenger_Data_Input" name="more_Passenger_Data_Input">
                                </div>
                                
                                <div id="more_P_D_div"></div>
                                
                                <div class="col-lg-12">
                                    <div class="mb-3 text-last" id="edit_M_P_Button">
                                        <button class="btn btn-primary" id="add_more_P" type="button">Add more Passenger</button>
                                    </div>
                                    
                                </div>
                                
                                <div id="add_more_P_div"></div>
                                
                                <div class="row" id="" style="float:right">
                                    <div class="col-lg-12 mb-3">
                                        <button class="btn btn-primary" id="button_P" type="submit" style="display: none;">Submit</button>
                                    </div>
                                </div>
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
          
            //DataTable
            $('#myTable').DataTable({
                pagingType: 'full_numbers',
            });
            
            //View Modal Single Quotation
            $('.detail-btn').click(function() {
                const id = $(this).attr('data-id');
                $.ajax({
                    url: 'view_QuotationsID/'+id,
                    type: 'GET',
                    data: {
                        "id": id
                    },
                    success:function(data) {
                        var a                = data['a'];
                        var roundTripDetails = a['roundTripDetails'];
                        var oneWayDetails    = a['oneWayDetails'];
                        
                        console.log(a);
            
                        //Flight Details
                        $('#airline_name').html(oneWayDetails['airline_name']);
                        $('#deprturetime').html(oneWayDetails['deprturetime']);
                        $('#ArrivalTime').html(oneWayDetails['ArrivalTime']);
                        $('#deprturedate').html(oneWayDetails['deprturedate']);
                        $('#ArrivalDate').html(oneWayDetails['ArrivalDate']);
                        $('#departure').html(oneWayDetails['departure']);
                        $('#arrival').html(oneWayDetails['arrival']);
                        
                        $('#flighttype').html(a['flighttype']);
                        $('#flight_price').html(a['flight_price']);
                        
                        //Hotel Booking Details Makkkah
                        $('#dateinmakkah').html(a['dateinmakkah']);
                        $('#dateoutmakkah').html(a['dateoutmakkah']);
                        $('#hotel_name_makkah').html(a['hotel_name_makkah']);
                        $('#no_of_rooms_makkah').html(a['no_of_rooms_makkah']);
                        $('#Price_Per_Nights_Makkah').html(a['Price_Per_Nights_Makkah']);
                        $('#Makkah_total_price_cal').html(a['Makkah_total_price_cal']);
                        
                        //Hotel Booking Details Madinah
                        $('#dateinmadinah').html(a['dateinmadinah']);
                        $('#dateoutmadinah').html(a['dateoutmadinah']);
                        $('#hotel_name_madina').html(a['hotel_name_madina']);
                        $('#no_of_rooms_madina').html(a['no_of_rooms_madina']);
                        $('#price_per_night_madinah').html(a['price_per_night_madinah']);
                        $('#madinah_total_price_cal').html(a['madinah_total_price_cal']);
                        
                        
                        //Transfer Details
                        $('#transfer_vehicle').html(a['transfer_vehicle']);
                        $('#passenger').html(a['passenger']);
                        $('#pickuplocat').html(a['pickuplocat']);
                        $('#dropoflocat').html(a['dropoflocat']);
                        $('#trans_date').html(a['trans_date']);
                        $('#transf_price').html(a['transf_price']);
                        
                        //Visa Details
                        $('#visa_fees_adult').html(a['visa_fees_adult']);
                        $('#visa_fees_child').html(a['visa_fees_child']);
                        $('#visa_fees_price').html(a['visa_fees_price']);
                        
                        //Totals
                        $('#flight_price_total').html(a['flight_price']);
                        $('#Makkah_total_price_cal').html(a['Makkah_total_price_cal']);
                        $('#madinah_total_price_cal').html(a['madinah_total_price_cal']);
                        $('#transfers_head_total').html(a['transfers_head_total']);
                        $('#visa_fees').html(a['visa_fees_price']);
                        $('#grand_total_price').html(a['grand_total_price']);
            
                    }
                })
            });
    
        });

    </script>
    
    <script>
    
        function add_more_passenger(id){
            $('#add_more_P_div').empty();
            $('#invoice_Id_Input').val();
            $('#lead_name_ID').empty();
            $('#no_of_pax_days_ID').empty();
            $('#no_of_pax_days_Input').val();
            $('#add_more_P').css('display','');
            $('#more_P_D_div').empty();
            let I_id = id;
            $.ajax({
                url:"{{URL::to('get_single_Invoice')}}" + '/' + I_id,
                method: "GET",
                data: {
                	I_id:I_id
                },
                success:function(data){
                    var data1                   = data['data'];
                    var invoice_Id              = data1['id'];
                    var f_name                  = data1['f_name'];
                    var middle_name             = data1['middle_name'];
                    var no_of_pax_days          = data1['no_of_pax_days'];
                    $('#invoice_Id_Input').val(invoice_Id);         	  
                    $('#lead_name_ID').html(f_name+' '+middle_name);
                    $('#no_of_pax_days_ID').html(no_of_pax_days);
                    $('#no_of_pax_days_Input').val(no_of_pax_days);
                    
                    var count_P_Input = data1['count_P_Input'];
                    if(count_P_Input > 0 && count_P_Input != null && count_P_Input != ''){
                        $('#count_P_Input').val(count_P_Input);
                        $('#count_P_Input1').val(count_P_Input);
                    }else{
                        $('#count_P_Input').val(1);
                        $('#count_P_Input1').val(1);
                    }
                    
                    var more_Passenger_Data = data1['more_Passenger_Data'];
                    if(more_Passenger_Data != null && more_Passenger_Data != ''){
                        $('#more_Passenger_Data_Input').val(more_Passenger_Data);
                        var edit_M_P_Button = `<button class="btn btn-primary" id="edit_more_P" onclick="edit_more_P()" type="button">Edit more Passenger</button>`;
                        // $('#edit_M_P_Button').append(edit_M_P_Button);
                        
                        var more_Passenger_Data_decode = JSON.parse(data1['more_Passenger_Data']);
                        $.each(more_Passenger_Data_decode, function (key, value) {
                            var more_p_fname    = value.more_p_fname;
                            var more_p_lname    = value.more_p_lname;
                            var more_p_gender   = value.more_p_gender;
                            var more_p_passport = value.more_p_passport;
                            var more_p_image    = value.more_p_image;
                            var url_IandP       = `https://alhijaztours.net/public/uploads/package_imgs/`;
                            
                            var data =  `<div class="row" id="main_div_P_${invoice_Id}">
                                            <div class="col-lg-12">
                                                <h5>More Passenger details</h5>
                                            </div>
                                            <div class="col-lg-2 mb-3">
                                                <label for="label" class="form-label">First Name</label>
                                                <input class="form-control remove_readonly_prop" type="text" value="${more_p_fname}" name="" placeholder="" readonly>
                                            </div>
                                            <div class="col-lg-2 mb-3">
                                                <label for="label" class="form-label">Last Name</label>
                                                <input class="form-control remove_readonly_prop" type="text" value="${more_p_fname}" name="" placeholder="" readonly>
                                            </div>
                                            <div class="col-lg-2 mb-3">
                                                <label for="label" class="form-label">Gender</label>
                                                <select class="form-control" name="" readonly>`;
                                                    if(more_p_gender == 'Male'){
                            data +=                     `<option value="Male" selected>Male</option>
                                                        <option value="Female">Female</option>`;
                                                    }
                                                    else if(more_p_gender == 'Female'){
                            data +=                     `<option value="Male">Male</option>
                                                        <option value="Female" selected>Female</option>`;
                                                    }
                                                    else{
                            data +=                     `<option value="">Choose...</option>
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>`;
                                                    }
                            data +=             `</select>
                                            </div>
                                            <div class="col-lg-3 mb-3">
                                                <label for="image" class="form-label">Passport</label>
                                                <input class="form-control" type="text" value="${more_p_passport}" name="" readonly>
                                                <input class="form-control" type="file" value="${more_p_passport}" name="" hidden>
                                            </div>
                                            <div class="col-lg-3 mb-3">
                                                <label for="image" class="form-label">Image</label>
                                                <img src="${url_IandP}${more_p_image}" style="height: 80px;width: 80px;">
                                                <input class="form-control" type="text" name="" readonly hidden>
                                                <input class="form-control" type="file" name="" readonly hidden>
                                            </div>
                                        </div>`;
                            
                            if(more_p_fname != null){
                                $('#more_P_D_div').append(data);
                            }
                        });
                    }
                }
            }); 
        }
        
        $('#add_more_P').click(function() {
            $('#button_P').css('display','');
            
            // console.log('count_P : '+count_P);
            
            var count_P = $('#count_P_Input').val();
            count_P     = parseFloat(count_P) + 1;
            console.log('count_P : '+count_P);
            
            var no_of_pax_days_Input    = $('#no_of_pax_days_Input').val();
            var id_P                    = $('#invoice_Id_Input').val();
            var data =  `<div class="row" id="main_div_P_${id_P}${count_P}">
                            <div class="col-lg-12">
                                <h5>More Passenger # ${count_P}</h5>
                            </div>
                            <div class="col-lg-2 mb-3">
                                <label for="label" class="form-label">First Name</label>
                                <input class="form-control" type="text" id="more_p_fname${id_P}_${count_P}" name="more_p_fname[]" placeholder="" required>
                            </div>
                            <div class="col-lg-2 mb-3">
                                <label for="label" class="form-label">Last Name</label>
                                <input class="form-control" type="text" id="more_p_lname${id_P}_${count_P}" name="more_p_lname[]" placeholder="" required>
                            </div>
                            <div class="col-lg-2 mb-3">
                                <label for="label" class="form-label">Gender</label>
                                <select class="form-control" id="more_p_gender${id_P}_${count_P}" name="more_p_gender[]" placeholder="Choose..." required>
                                    <option value="">Select</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <label for="image" class="form-label">Passport</label>
                                <input class="form-control" type="file" id="more_p_passport${id_P}_${count_P}" name="more_p_passport[]" placeholder="" required>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input class="form-control" type="file" id="more_p_image${id_P}_${count_P}" name="more_p_image[]" placeholder="" required>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary" id="remove_more_P" onclick="removeMorePassenger(${id_P}${count_P})" type="button">Remove</button>
                            </div>
                        </div>`;
                        
            if(count_P <= no_of_pax_days_Input){
                $('#add_more_P_div').append(data);
                $('#count_P_Input').val(count_P);
                // count_P = count_P + 1;
                // console.log('count_P_if : '+count_P);
            }else{
                $('#add_more_P').css('display','none');
                count_P = count_P - 1;
                // console.log('count_P_else : '+count_P);
                $('#count_P_Input').val(count_P);
                // count_P = no_of_pax_days_Input;
            }
        });
        
        function removeMorePassenger(id){
            $('#main_div_P_'+id+'').remove();
            var no_of_pax_days_Input    = $('#no_of_pax_days_Input').val();
            var count_P                 = $('#count_P_Input').val();
            var count_P_Input1          = $('#count_P_Input1').val();
            count_P                     = count_P - 1;
            $('#count_P_Input').val(count_P);
            if(count_P <= no_of_pax_days_Input){
                $('#add_more_P').css('display','');
            }
            
            if(count_P_Input = count_P_Input1){
                $('#button_P').css('display','none');
            }
        }
        
        // function edit_more_P(){
        //     $('.remove_readonly_prop').removeAttr("readonly");
        // }
        
    </script>

@endsection
