@extends('template/frontend/userdashboard/layout/default')  
 @section('content') 



<?php

//    echo 'called'; exit();
//    echo '<pre>'; print_r($data['bookings']); exit();
// print_r(count($data['bookings']));die;
?>



    <div class="dashboard-content-wrap">
        <div class="dashboard-bread dashboard--bread">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="breadcrumb-content">
                            <div class="section-heading">
                                <h2 class="sec__title font-size-30">My Booking</h2>
                            </div>
                        </div><!-- end breadcrumb-content -->
                    </div><!-- end col-lg-6 -->
                    <div class="col-lg-6">
                        <div class="breadcrumb-list">
                            <ul class="list-items d-flex justify-content-end">
                                <li><a href="index.html" class="text-white">Home</a></li>
                                <li>Dashboard</li>
                                <li>My Booking</li>
                            </ul>
                        </div><!-- end breadcrumb-list -->
                    </div><!-- end col-lg-6 -->
                </div><!-- end row -->
            </div>
        </div><!-- end dashboard-bread -->
        <div class="dashboard-main-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-box">
                            <div class="form-title-wrap">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h3 class="title">Booking Results</h3>
                                        <p class="font-size-14">
                                        <!-- Showing 1 to 7 of {{count($data['bookings'])}} entries -->
                                    </p>
                                    </div>
                                    <span>Total Bookings <strong class="color-text">({{count($data['bookings'])}})</strong></span>
                                </div>
                            </div>
                            <div class="form-content">
                                <div class="table-form table-responsive">
                                    <table class="table">
                                        <thead>
                                        
                                           

                                             <tr>
                                <!-- <th scope="col">No</th> -->
                                <th scope="col">Booking Type</th>
                                <th scope="col">Booking Ref No</th>
                                <th scope="col">Lead Passenger</th>
                                <th scope="col">Booking Date</th>
                                <th scope="col">Total Payment</th>
                                <th scope="col">Total Payable</th>
                                <th scope="col">Email</th>
                                <th scope="col">Status</th>
                                <th scope="col">Detail</th>

                            </tr>
                                        
                                        </thead>
                                       

                                            @foreach($data['bookings'] as  $booking)
                                       
                                       <tbody style="border: 5px solid #0e3367;">

                                      @if($booking->hotel_makkah_brn != 'no_makkah') 
                                        <tr>
                                            <th scope="row"><i class="la la-car mr-1 font-size-18"></i>Hotel Makkah</th>
                                            <td>
                                                <div class="table-content">
                                                    <h3 class="title">{{$booking->hotel_makkah_brn }}</h3>
                                                </div>
                                            </td>
                                            <td>{{ $booking->lead_passenger_name }}</td>
                                            <td>{{ date('Y-m-d',strtotime($booking->created_at)) }}</td>
                                            <td>{{ $booking->hotel_makkah_total_amount }}</td>
                                    <td>{{ $booking->hotel_makkah_total_amount }}</td>
                                    <td>{{ $booking->lead_passenger_email }}</td>
                                    
                                            <td><span class="badge badge-success py-1 px-2">{{$booking->hotel_makkah_booking_status}}</span></td>
                                           
                                            <td>
                                                <div class="table-content">
                                        <form class="booking-detail-form" action="{{URL::to('user_dashboard/booking-detail')}}" method="post">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="id" value="{{$booking->id}}">
                                            <input type="hidden" name="brn" value="{{$booking->hotel_makkah_brn}}">
                                            <input type="hidden" name="case" value="hotel_makkah_view_booking">
{{--                                        <a href="{{url('user_dashboard/booking-detail/hotel_makkah_view_booking/'.$booking->hotel_makkah_brn)}}">--}}
                                        <button type="submit" data-url="{{url('user_dashboard/booking-detail/hotel_makkah_view_booking/'.$booking->hotel_makkah_brn.'/'.$booking->id)}}" class="theme-btn theme-btn-small">Details</button>
{{--                                        </a>--}}
                                        </form>
                                    </div>
                                    </td>

                                        </tr>
                                        @endif
                                     
                                      @if($booking->hotel_madina_brn != 'no_madina') 
                                        <tr>
                                            <th scope="row"><i class="la la-car mr-1 font-size-18"></i>Hotel Madina</th>
                                            <td>
                                                <div class="table-content">
                                                    <h3 class="title">{{$booking->hotel_madina_brn }}</h3>
                                                </div>
                                            </td>
                                            <td>{{ $booking->lead_passenger_name }}</td>
                                            <td>{{ date('Y-m-d',strtotime($booking->created_at)) }}</td>
                                            <td>{{ $booking->hotel_madina_total_amount }}</td>
                                    <td>{{ $booking->hotel_madina_total_amount }}</td>
                                    <td>{{ $booking->lead_passenger_email }}</td>
                                    
                                            <td><span class="badge badge-success py-1 px-2">{{$booking->hotel_madina_booking_status}}</span></td>
                                           
                                            <td>
                                                <div class="table-content">
                                        <form class="booking-detail-form" action="{{URL::to('user_dashboard/booking-detail')}}" method="post">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="id" value="{{$booking->id}}">
                                            <input type="hidden" name="brn" value="{{$booking->hotel_madina_brn}}">
                                            <input type="hidden" name="case" value="hotel_madina_view_booking">
{{--                                        <a href="{{url('user_dashboard/booking-detail/hotel_madina_view_booking/'.$booking->hotel_madina_brn)}}">--}}
                                        <button type="submit" data-url="{{url('user_dashboard/booking-detail/hotel_madina_view_booking/'.$booking->hotel_madina_brn.'/'.$booking->id)}}" class="theme-btn theme-btn-small">Details</button>
{{--                                        </a>--}}
                                        </form>
                                    </div>
                                    </td>

                                        </tr>
                                        @endif
                                       
                                      @if($booking->transfer_brn != 'no_transfer') 
                                        <tr>
                                            <th scope="row"><i class="la la-car mr-1 font-size-18"></i>Transfer</th>
                                            <td>
                                                <div class="table-content">
                                                    <h3 class="title">{{$booking->transfer_brn }}</h3>
                                                </div>
                                            </td>
                                            <td>{{ $booking->lead_passenger_name }}</td>
                                            <td>{{ date('Y-m-d',strtotime($booking->created_at)) }}</td>
                                            <td>{{ $booking->transfer_total_amount }}</td>
                                    <td>{{ $booking->transfer_total_amount }}</td>
                                    <td>{{ $booking->lead_passenger_email }}</td>
                                    
                                            <td><span class="badge badge-success py-1 px-2">{{$booking->transfer_booking_status}}</span></td>
                                           
                                            <td>
                                                <div class="table-content">
                                        <form class="booking-detail-form" action="{{URL::to('user_dashboard/booking-detail')}}" method="post">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="id" value="{{$booking->id}}">
                                            <input type="hidden" name="brn" value="{{$booking->transfer_brn}}">
                                            <input type="hidden" name="case" value="transfer_view_booking">
{{--                                        <a href="{{url('user_dashboard/booking-detail/transfer_view_booking/'.$booking->transfer_brn)}}">--}}
                                        <button type="submit" data-url="{{url('user_dashboard/booking-detail/transfer_view_booking/'.$booking->transfer_brn.'/'.$booking->id)}}" class="theme-btn theme-btn-small">Details</button>
{{--                                        </a>--}}
                                        </form>
                                    </div>
                                    </td>

                                        </tr>
                                        @endif
                                       
                                      @if($booking->ground_brn != 'no_ground') 
                                        <tr>
                                            <th scope="row"><i class="la la-car mr-1 font-size-18"></i>Ground Service</th>
                                            <td>
                                                <div class="table-content">
                                                    <h3 class="title">{{$booking->ground_service_brn }}</h3>
                                                </div>
                                            </td>
                                            <td>{{ $booking->lead_passenger_name }}</td>
                                            <td>{{ date('Y-m-d',strtotime($booking->created_at)) }}</td>
                                            <td>{{ $booking->ground_total_amount }}</td>
                                    <td>{{ $booking->ground_total_amount }}</td>
                                    <td>{{ $booking->lead_passenger_email }}</td>
                                    
                                            <td><span class="badge badge-success py-1 px-2">{{$booking->ground_booking_status}}</span></td>
                                           
                                            <td>
                                                <div class="table-content">
                                        <form class="booking-detail-form" action="{{URL::to('user_dashboard/booking-detail')}}" method="post">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="id" value="{{$booking->id}}">
                                            <input type="hidden" name="brn" value="{{$booking->ground_brn}}">
                                            <input type="hidden" name="case" value="ground_view_booking">
{{--                                        <a href="{{url('user_dashboard/booking-detail/ground_view_booking/'.$booking->ground_brn)}}">--}}
                                        <button type="submit" data-url="{{url('user_dashboard/booking-detail/ground_view_booking/'.$booking->ground_brn.'/'.$booking->id)}}" class="theme-btn theme-btn-small">Details</button>
{{--                                        </a>--}}
                                        </form>
                                    </div>
                                    </td>

                                        </tr>


                                        @endif
                                     </tbody>
                                       @endforeach
                                        

                                       
                                        
                                    </table>
                                </div>
                            </div>
                        </div><!-- end form-box -->
                    </div><!-- end col-lg-12 -->
                </div><!-- end row -->
                <div class="row">
                    <div class="col-lg-12">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination">
                                <li class="page-item">
                                    <a class="page-link page-link-nav" href="#" aria-label="Previous">
                                        <span aria-hidden="true"><i class="la la-angle-left"></i></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                </li>
                                <li class="page-item"><a class="page-link page-link-nav" href="#">1</a></li>
                                <li class="page-item active">
                                    <a class="page-link page-link-nav" href="#">2 <span class="sr-only">(current)</span></a>
                                </li>
                                <li class="page-item"><a class="page-link page-link-nav" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link page-link-nav" href="#" aria-label="Next">
                                        <span aria-hidden="true"><i class="la la-angle-right"></i></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div><!-- end col-lg-12 -->
                </div><!-- end row -->



                {{--  modal starts from here--}}
                <div class="modal fade" id="myModal">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">

                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title">Booking Details</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>

                            <!-- Modal body -->
                            <div class="modal-body">
                                <div class="loading">
                                    Loading...
                                </div>
                                <div class="booking-detail-body">

                                </div>
                            </div>

                            <!-- Modal footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            </div>

                        </div>
                    </div>
                </div>

                {{--modal ends here--}}



                <script>
                    $('body').on('submit','.booking-detail-form',function (e){
                        e.preventDefault();
                        let action = $(this).attr('action');
                        $('.booking-detail-body').html('');
                        $('#myModal').modal('toggle');
                        $('.loading').show();
                        let form_data = $(this).serialize();
                        $.post(action,form_data,function (response){
                            $('.booking-detail-body').html(response);
                            $('.loading').hide();
                        })
                    })

                    $('body').on('click','.add-profit-btn',function (e){
                        let booking_id = $(this).closest('tr').find('.booking-id').val();
                        let booking_type = $(this).data('booking');
                        let actual_amount = $(this).data('amount');
                        $('.booking-type').val(booking_type);
                        $('.booking-id').val(booking_id);
                        $('.actual_amount').val(actual_amount);

                    })
                </script>



        
      @stop