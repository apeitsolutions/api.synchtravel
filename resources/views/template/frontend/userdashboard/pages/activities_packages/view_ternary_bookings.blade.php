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

    <section class="content" style="padding: 30px 50px 0px 50px;">
      

      <!-- Start Content-->
        <div class="container-fluid">
          <!-- start page title -->
          <div class="row">
              <div class="col-12">
                  <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Tour Bookings</a></li>
                            <li class="breadcrumb-item active">Tentative Bookings</li>
                        </ol>
                    </div>
                    <h4 class="page-title">View Tentative Bookings</h4>
                  </div>
              </div>
          </div>
          <!-- end page title --> 

          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <div class="row mb-2">
                    <div class="col-sm-5">
                      <a href="javascript:void(0);" class="btn btn-danger mb-2"><i class="mdi mdi-plus-circle me-2"></i> View Tentative Bookings</a>
                    </div>
                    <div class="col-sm-7">
                      <div class="text-sm-end">
                        <button type="button" class="btn btn-success mb-2 me-1"><i class="mdi mdi-cog-outline"></i></button>
                        <button type="button" class="btn btn-light mb-2 me-1">Import</button>
                        <button type="button" class="btn btn-light mb-2">Export</button>
                      </div>
                    </div><!-- end col-->
                  </div>

                  <div class="table-responsive">
                    <div id="example_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                      <div class="row">
                        <table class="table table-centered w-100 dt-responsive nowrap" id="products-datatable">
                          <thead class="table-light">
                            <tr>
                                <th>Booking ID</th>
                                <th style="text-align: center;">Image</th>
                                <th class="all">Tour ID</th>
                                <th>Customer Name</th>
                                <th>Package Title</th>
                                <th>Adults</th>
                                <th>Childs</th>
                                <th>Total Amount</th>
                                <th>Payment Status</th>
                                <th>Status</th>
                                <th style="width: 85px;">Action</th>
                            </tr>
                          </thead>
                            <tbody>
                              @foreach ($data as $value)
                                <?php $cart_details = json_decode($value->cart_details); ?>
                                @foreach ($cart_details as $cart_details)
                                  <?php
                                    $date4days = date('Y-m-d',strtotime($value->created_at. ' + 2 days'));
                                    $currdate = date('Y-m-d');
                                  ?>
                                  <tr>
                                    <td>{{ $value->id }}</td>
                                    <td>
                                      <p class="m-0 d-inline-block align-middle font-16">
                                      <a href="apps-ecommerce-products-details.html" class="text-body">{{ $cart_details->image }}</a>
                                        <br/>
                                      <span class="text-warning mdi mdi-star"></span>
                                      <span class="text-warning mdi mdi-star"></span>
                                      <span class="text-warning mdi mdi-star"></span>
                                      <span class="text-warning mdi mdi-star"></span>
                                      <span class="text-warning mdi mdi-star"></span>
                                      </p>
                                    </td>
                                    <td class="sorting_1 tour_Id" id="tour_Id">{{ $cart_details->tourId }}</td>
                                    <td>{{ $value->fname }}</td>
                                    <td>{{ $cart_details->name }}</td>
                                    <td>{{ $cart_details->adults }}</td>
                                    <td>{{ $cart_details->children }}</td>
                                    <td>{{ $cart_details->price }}</td>
                                    <td class="payment_status">
                                      <!-- <div id="payment_status_Paid" style="display: none;"></div>
                                      <div id="payment_status_UnPaid" style="display: none;"></div>
                                      <div id="payment_status_Partially_Paid" style="display: none;"></div> -->
                                    </td>
                                    @if($value->confirm == 1)
                                      <td><span class="badge bg-success" style="font-size: 15px">Confirmed</span></td>
                                    @else
                                      <td><span class="badge btn-danger" style="font-size: 15px">Tentative</span></td>
                                    @endif
                                    <td>
                                      <div class="dropdown card-widgets">
                                        <a style="float: right;" href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="dripicons-dots-3"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end" style="">
                                            <!-- item-->
                                            <a href="{{ URL::to('super_admin/view_booking_payment')}}/{{$value->id}}/{{$cart_details->tourId}}" class="dropdown-item"><i class="mdi mdi-eye me-1"></i>View Payment</a>
                                            <!-- item-->
                                            <a href="{{ URL::to('super_admin/view_booking_detail')}}/{{$value->id}}/{{$cart_details->tourId}}" class="dropdown-item"><i class="mdi mdi-eye me-1"></i>View Booking Details</a>
                                            <!-- item-->
                                            <a href="{{ route('view_booking_customer_details',$value->customer_id) }}" class="dropdown-item"><i class="mdi mdi-account"></i>Customer Deatils</a>
                                            <!-- item-->
                                            <a href="{{ route('confirmed_tour_booking',$value->id) }}" class="dropdown-item"><i class="mdi mdi-check-circle"></i>Confirm Booking</a>
                                        </div>
                                      </div>
                                    </td>
                                  </tr>
                                @endforeach 
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
        </div> 
      <!-- container -->
    </section>

    <!-- /.Dashboard -->
  </div>


  


@endsection
  @section('scripts')
  <script>
    
    $(document).ready(function () {
      
      //DataTable
      $('#myTable').DataTable({
          pagingType: 'full_numbers',
      });

      $('#products-datatable .tour_Id').each(function() {
        var tourId = $(this).html();
        var pID = 0;
        $.ajax({
          url: 'view_ternary_bookings_tourId/'+tourId,
          type: 'GET',
          data: {
            "tourId": tourId
          },
          success:function(data) {
            var a = data['a'];
            // console.log(a);
            if(a == ''){
              // alert('a is null');
              $(".payment_status").html('<div id="payment_status_UnPaid_' + pID + '" style="display: none;"></div><td style="color: orange;">Un Paid</td>');
              pID = pID + 1;
              // alert(pID);
            }
            else{
              // alert('a is not null');
              var sum_amount_paid = 0;
              jQuery.each(a, function(index, value){
                var amount_paid      = value.amount_paid;
                var recieved_amount  = value.recieved_amount;
                var total_amount     = value.total_amount;
                var remaining_amount = value.remaining_amount;

                if(remaining_amount == ''){
                  // alert('a is not null if');
                  sum_amount_paid  = parseFloat(sum_amount_paid)  + parseFloat(amount_paid);
                  console.log(sum_amount_paid);

                  if (total_amount == sum_amount_paid){
                    // $(".payment_status").empty();
                    $(".payment_status").html('<div id="payment_status_Paid_' + pID + '" style="display: none;"></div><td style="color: Green;">Paid</td>');
                  }
                  else if (sum_amount_paid == '') {
                    // $(".payment_status").empty();
                    $(".payment_status").html('<div id="payment_status_UnPaid_' + pID + '" style="display: none;"></div><td style="color: orange;">Un Paid</td>');
                  }
                  else{
                    // $(".payment_status").empty();
                    $(".payment_status").html('<div id="payment_status_PartiallyPaid_' + pID + '" style="display: none;"></div><td style="color: rebeccapurple;">Partially Paid</td>');
                  }
                }
                else{
                  // alert('a is not null else')
                  var b = parseFloat(amount_paid) + parseFloat(recieved_amount);
                  var c = parseFloat(total_amount) - parseFloat(b);
                  var sum_total_amount = parseFloat(c) + parseFloat(remaining_amount);
                  sum_amount_paid  = parseFloat(sum_amount_paid)  + parseFloat(amount_paid);

                  if (total_amount == sum_amount_paid){
                    // $(".payment_status").empty();
                    $(".payment_status").html('<div id="payment_status_Paid_' + pID + '" style="display: none;"></div><td style="color: Green;">Paid</td>');
                    pID = pID + 1;
                  }
                  else if (sum_amount_paid == '') {
                    // $(".payment_status").empty();
                    $(".payment_status").html('<div id="payment_status_UnPaid_' + pID + '" style="display: none;"></div><td style="color: orange;">Un Paid</td>');
                    pID = pID + 1;
                  }
                  else{
                    // $(".payment_status").empty();
                    $(".payment_status").html('<div id="payment_status_PartiallyPaid_' + pID + '" style="display: none;"></div><td style="color: rebeccapurple;">Partially Paid</td>');
                    pID = pID + 1;
                    // alert(pID);
                  }
                }
              });
            }
          }
        })
      });

    });

  </script>
@stop