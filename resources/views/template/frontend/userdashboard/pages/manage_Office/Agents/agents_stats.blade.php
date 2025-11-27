
@extends('template/frontend/userdashboard/layout/default')
@section('content')

<?php $currency=Session::get('currency_symbol'); ?>

<div class="dashboard-content">
  
    <div class="dashboard-content">
        <h3>Agents</h3>
        <div class="row d-none">
            <div class="col-lg-12 col-sm-12">
                <div class="dashboard-list-box dash-list margin-top-0">
                    <div class="row">
                        <div class="col-md-12">
                             <table id="scroll-horizontal-datatable"  class="display nowrap table  table-bordered" style="width:100%;">
                                <thead class="theme-bg-clr">
                                    <tr>
                                        <th>Sr</th>
                                        <th>Agent Id</th>
                                        <th>Agent Name</th>
                                        <th>Package Booking</th>
                                        <th>Invoices Booking</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                 <tbody style="text-align: center;">
                                    @isset($agents_data)
                                        @foreach($agents_data as $agent_res)
                                            <tr role="row">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $agent_res->agent_id }}</td>
                                                <td>Company Name:{{ $agent_res->agent_company }}<br>
                                                    Agent Name:{{ $agent_res->agent_name }}</td>
                                                <td>{{ $agent_res->agents_tour_booking }}</td>
                                                <td>{{ $agent_res->agents_invoice_booking }}</td>
                                                <td>
                                                    <a href="{{ URL::to('agents_stats_details/'.$agent_res->agent_id.'') }}" class="btn btn-primary btn-sm">view Details</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endisset()
                                </tbody>
                            </table>
                        </div>
                 
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            @isset($agents_data)
                @foreach($agents_data as $agent_res)
                    <div class="col-md-6 col-xxl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="dropdown float-end">
                                    <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="mdi mdi-dots-horizontal"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a href="{{ URL::to('agents_stats_details/'.$agent_res->agent_id.'') }}" class="dropdown-item">View Details</a>
                                    </div>
                                </div>
        
                                <div class="text-center">
                                    @if(isset($agent_Img))
                                        <img src="assets/images/users/avatar-1.jpg" class="rounded-circle avatar-md img-thumbnail" alt="friend">
                                    @else
                                        <img src="{{asset('public/admin_package/frontend/images/detail_img/no-photo-available-icon-4.jpg')}}" alt="" height="30" width="30">
                                    @endif
                                    <h4 class="mt-3 my-1">{{ $agent_res->agent_name }}<i class="mdi mdi-check-decagram text-primary"></i></h4>
                                    <p class="mb-0 text-muted"><i class=""></i>{{ $agent_res->agent_company }}</p>
                                    <hr class="bg-dark-lighten my-3">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <h5 class="mt-3 fw-semibold text-muted">Booked Packages : <b>{{ $agent_res->agents_tour_booking }}</b></h5>  
                                        </div>
                                        <div class="col-lg-6">
                                            <h5 class="mt-3 fw-semibold text-muted">Booked Invoices : <b>{{ $agent_res->agents_invoice_booking }}</b></h5>  
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-3">
                                            <a href="javascript:void(0);" class="btn w-100 btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Message"><i class="mdi mdi-message-processing-outline"></i></a>
                                        </div>
                                        <div class="col-3">
                                            <a href="javascript:void(0);" class="btn w-100 btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Call"><i class="mdi mdi-phone"></i></a>
                                        </div>
                                        <div class="col-3">
                                            <a href="javascript:void(0);" class="btn w-100 btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Email"><i class="mdi mdi-email-outline"></i></a>
                                        </div>
                                        <div class="col-3">
                                            <a href="{{ URL::to('agents_stats_details/'.$agent_res->agent_id.'') }}" class="btn w-100 btn-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Details"><i class="mdi mdi-email-outline"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endisset()
        </div>
        
    </div>
    
</div>
  
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
                                                <input class="form-control remove_readonly_prop" type="text" value="${more_p_lname}" name="" placeholder="" readonly>
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
                    
                    if(no_of_pax_days = count_P_Input){
                        $('#edit_M_P_Button').css('display','none')
                    }
                }
            }); 
        }
        
        $('#add_more_P').click(function() {
            $('#button_P').css('display','');
            
            var count_P = $('#count_P_Input').val();
            count_P     = parseFloat(count_P) + 1;
            
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
            }else{
                $('#add_more_P').css('display','none');
                count_P = count_P - 1;
                $('#count_P_Input').val(count_P);
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
        
    </script>

@endsection
@section('scripts')
@stop





















