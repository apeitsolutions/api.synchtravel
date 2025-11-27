@extends('template/frontend/userdashboard/layout/default')
@section('content')

  <style>
    .nav-link{
      color: #575757;
      font-size: 18px;
    }
  </style>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="content" style="padding: 10px 20px 0px 20px">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-primary">
              <div class="inner">
                <h3>150</h3>

                <p>Total Flights</p>
              </div>
              <div class="icon">
                <i class="ion ion-plane"></i>
              </div>
              <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>53<sup style="font-size: 20px">%</sup></h3>

                <p>Total Hotels</p>
              </div>
              <div class="icon">
                <i class="ion ion-calculator"></i>
              </div>
              <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box" style="background-color: #d262e3!important;">
              <div class="inner">
                <h3>44</h3>

                <p>Total Transfer</p>
              </div>
              <div class="icon">
                <i class="ion-android-car"></i>
              </div>
              <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>65</h3>

                <p>Commision</p>
              </div>
              <div class="icon">
                <i class="ion ion-cash"></i>
              </div>
              <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
            </div>
          </div>
          <!-- ./col -->
        </div>
      </div>
    </section>
    <!-- /.content -->

    <!-- Dashboard -->
    <section class="content" style="padding: 30px 50px 0px 50px;">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12 col-6">
            <nav class="breadcrumb push">
              <a class="breadcrumb-item" href="#">Dashboard</a> 
              <span class="breadcrumb-item active">View Quotation</span>
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
                      <table class="table table-bordered table-striped table-vcenter dataTable no-footer" id="myTable" role="grid" aria-describedby="example_info">
                        <thead>
                          <tr role="row">
                            <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;">Name</th>
                            <th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="email: activate to sort column ascending" style="width: 175.073px;">Prepared By</th>
                            <th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="email: activate to sort column ascending" style="width: 175.073px;">email</th>
                            <th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Date: activate to sort column ascending" style="width: 138.958px;">Date</th>
                            <th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending" style="width: 147.118px;">Phone</th>
                            <th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Validity: activate to sort column ascending" style="width: 143.438px;">Validity</th>
                            <th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Options: activate to sort column ascending" style="width: 175.642px;">Options</th>
                          </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $value)

                        <?php
                          $date4days = date('Y-m-d',strtotime($value->created_at. ' + 2 days'));
                          $currdate = date('Y-m-d');
                         ?>
                         <tr role="row" class="odd">
                            <!-- <td>b5c7a7ced6fda207e232</td> -->
                            <td class="sorting_1">{{ $value->f_name }}</td>
                             <td>{{ $value->quotation_prepared }}</td>
                            <td>{{ $value->email }}</td>
                            <td>{{ $value->created_at}}</td>
                            <td>
                              <ul>
                                <li>{{ $value->contact_landline }}</li>
                                <li>{{ $value->mobile }}</li>
                                <li>{{ $value->contact_work }}</li>
                              </ul>
                            </td>
                            @if($date4days > $currdate)
                              <td>
                                <input readonly id="created_at_ID" value="{{ $value->created_at }}" style="background-color: #d3d9df;border: 0"></input>
                                <input readonly value="{{ $value->created_at->diffForHumans() }}" id="expireQuotation" style="background-color: #d3d9df;border: 0"></input>
                              </td>
                              <td>
                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenter"  data-id="{{ $value->id }}" class="fetchorderdetails detail-btn" data-uniqid="b5c7a7ced6fda207e232">View</a>|  
                                <a href="{{ route('edit_Quotations',$value->id) }}">Edit Quatation</a> |
                                @if($value->confirm == 1)
                                  <a style="color: Green" id="confirm_Quotation" href="{{ route('add_Bookings',$value->id) }}">Already Confirm</a>
                                @else
                                  <a id="confirm_Quotation" href="{{ route('add_Bookings',$value->id) }}">Confirm</a>
                                @endif
                              </td>
                            @elseif($date4days < $currdate)
                              <td>
                                <input readonly id="created_at_ID" value="{{ $value->created_at }}" style="background-color: red;border: 0"></input>
                                <input readonly value="{{ $value->created_at->diffForHumans() }}" id="expireQuotation" style="background-color: red;border: 0"></input>
                              </td>
                              <td>
                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenter"  data-id="{{ $value->id }}" class="fetchorderdetails detail-btn" data-uniqid="b5c7a7ced6fda207e232">View</a>|  
                                <a href="{{ route('edit_Quotations',$value->id) }}">Edit Quatation</a> 
                              </td>
                            @else
                              <td>
                                <p style="background-color: orange;">Quotation will be Expire Tommorow</p>
                              </td>
                              <td>
                                <a data-bs-toggle="modal" data-bs-target="#exampleModalCenter"  data-id="{{ $value->id }}" class="fetchorderdetails detail-btn" data-uniqid="b5c7a7ced6fda207e232">View</a>|  
                                <a href="{{ route('edit_Quotations',$value->id) }}">Edit Quatation</a> |
                                <a id="confirm_Quotation" href="{{ route('add_Bookings',$value->id) }}">Confirm</a> 
                              </td> 
                            @endif
                            <!-- <td>
                              <a data-toggle="modal" data-target="#exampleModalCenter"  data-id="{{ $value->id }}" class="fetchorderdetails detail-btn" data-uniqid="b5c7a7ced6fda207e232">View</a>|  
                              <a href="{{ route('edit_Quotations',$value->id) }}">Edit Quatation</a> |
                              <a id="confirm_Quotation" href="{{ route('add_Bookings',$value->id) }}">Confirm</a> 
                            </td> -->
                          </tr>
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
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background-color: black;">
            <h5 class="modal-title" id="exampleModalLongTitle"></h5>
            <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
            <div class="modal-body">
              <!-- SELECT2 EXAMPLE -->

              <div class="col-sm-12">
                <h4 class="text-center">Flight Details</h4>
                <h6 class="text-left">Flight Return Type :One Way</h6>
              </div>
              <table class="js-table-sections table table-hover js-table-sections-enabled">
                <thead>
                  <tr>
                    <th width="15%">Airline</th>
                    <th width="25%">Departure</th>
                    <th width="25%">Arrival</th>
                  </tr>
                </thead>
                <tbody class="js-table-sections-header chintu-click">
                  <tr>
                    <td class="text-center" id="airline_name"></td>
                    <td>
                      <div class="booking-item-departure">
                        <h5><i class="fa fa-plane" id="deprturetime"></i></h5>
                        <p class="booking-item-date" id="deprturedate">2022-05-11</p>
                        <p class="booking-item-destination" id="departure">JED </p>
                      </div>
                    </td>
                    <td>
                      <div class="booking-item-departure">
                        <h5><i class="fa fa-plane" id="ArrivalTime"></i></h5>
                        <p class="booking-item-date" id="ArrivalDate"></p>
                        <p class="booking-item-destination" id="arrival">JED </p>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>Flight Type</td>
                    <td></td>
                    <td id="flighttype"><p><strong></strong></p></td>
                  </tr>
                  <tr>
                    <td>Total Price</td>
                    <td></td>
                    <td id="flight_price"><p><strong></strong></p></td>
                  </tr>
                </tbody>
              </table>
              
              <div class="hotel-booking-top">
                  <h3 class="text-center">Hotel Booking Details</h3>
                <div class="hotel-list-wrap ">
                  <div class="col-sm-12"><h4>Makkah Hotel</h4></div>
                    <table class="js-table-sections table table-hover js-table-sections-enabled">
                      <thead>
                        <tr>
                          <th width="15%">Date In</th>
                          <th width="15%">Date Out</th>
                          <th width="25%">Hotel Name</th>
                          <th width="10%">No of Rooms</th>
                          <th width="15%">Price Per Night</th>
                          <th width="15%">Total</th>
                        </tr>
                      </thead>
                          <tbody class="js-table-sections-header chintu-click">
                        <tr>
                          <td class="text-center" id="dateinmakkah"></td>
                          <td class="font-w600" id="dateoutmakkah"></td>
                          <td class="font-w600" id="hotel_name_makkah"></td>
                          <td class="font-w600" id="no_of_rooms_makkah"></td>
                          <td class="font-w600" id="Price_Per_Nights_Makkah"></td>
                          <td class="font-w600" id="Makkah_total_price_cal"></td>
                        
                        </tr>
                      </tbody>   
                    </table>                       
                  </div>
                  <!-- end of hotel-list-wrap -->
                  <div class="hotel-list-wrap ">
                    <div class="col-sm-12">
                       <h4>Madina Hotel</h4>
                    </div>
                   
                    <table class="js-table-sections table table-hover js-table-sections-enabled">
                      <thead>
                        <tr>
                          <th width="15%">Date In</th>
                          <th width="15%">Date Out</th>
                          <th width="25%">Hotel Name</th>
                          <th width="10%">No of Rooms</th>
                          <th width="15%">Price Per Night</th>
                          <th width="15%">Total</th>
                        </tr>
                      </thead>
                      <tbody class="js-table-sections-header chintu-click">
                        <tr>
                          <td class="text-center" id="dateinmadinah"></td>
                          <td class="font-w600" id="dateoutmadinah"></td>
                          <td class="font-w600" id="hotel_name_madina"> </td>
                          <td class="font-w600" id="no_of_rooms_madina"></td>
                          <td class="font-w600" id="price_per_night_madinah"></td>
                          <td class="font-w600" id="madinah_total_price_cal"></td>
                        
                        </tr>
                      </tbody>   
                    </table>
                  </div>
                  <!-- end of hotel-list-wrap -->
                </div>
              </div>

              <div class="col-12">
                <h3 class="text-center">Transfer Details</h3>
              </div>
              <table class="js-table-sections table table-hover js-table-sections-enabled">
                <thead>
                  <tr>
                    <th width="15%">Vehcle</th>
                    <th width="15%">Pessengers</th>
                    <th width="15%">Pickup</th>
                    <th width="15%">Dropoff </th>
                    <th width="15%">Fare</th>
                  </tr>
                </thead>
                <tbody class="js-table-sections-header chintu-click">
                  <tr>
                    <td id="transfer_vehicle"></td>
                    <td id="passenger"></td>
                    <td id="pickuplocat"></td>
                    <td id="dropoflocat"></td>
                    <td id="transf_price"></td>
                  </tr>
                </tbody>
              </table>

              <div class="col-12">
                <h3 class="text-center">Visa Detail</h3>
              </div>
              <table class="js-table-sections table table-hover js-table-sections-enabled">
                <thead>
                  <tr>
                    <th width="50%">Adult Visa Fee</th>
                    <th width="50%">Child Visa Fee</th>
                    <th width="50%">Total</th>
                  </tr>
                </thead>
                <tbody class="js-table-sections-header chintu-click">
                  <tr>
                    <td id="visa_fees_adult"></td>
                    <td id="visa_fees_child"></td>
                    <td id="visa_fees_price"></td>
                  </tr>
                </tbody>
              </table>

              <div class="row">
                <div class="offset-md-7 col-md-5">
                  Flight Total:<h5 id="flight_price_total"></h5>
                  Makkah Hotel Total:<h5 id="Makkah_total_price_cal"></h5>
                  Madinah Hotel Totall:<h5 id="madinah_total_price_cal"></h5>
                  Transfer Total:<h5 id="transfers_head_total"></h5>
                  Visa Fee :<h5 id="visa_fees"></h5>
                  <hr>
                  Grand Total :<h4 id="grand_total_price"></h4>
                </div>
              </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-alt-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
      </div>
    </div>

    <!-- /.Dashboard -->
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
            var a                = JSON.parse(data);
            var roundTripDetails = JSON.parse(a['roundTripDetails']);
            var oneWayDetails    = JSON.parse(a['oneWayDetails']);

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

      // // Confirm Quotation
      // $('#confirm_Quotation').click(function() {
      //     pagingType: 'full_numbers',
      // });

    });

  </script>

@endsection
