@php
echo '<pre>';
foreach($data as $value){
    if($value->makkah_brn !='no_makkah'){
        pirnt_r($value);
        die();
    }
}
@endphp
@extends('template/frontend/userdashboard/layout/default')
 @section('content')
<div class="col-span-12">
    <!-- BEGIN: Vertical Form -->
    <div class="intro-y box">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
            <h2 class="font-medium text-base mr-auto">
                B2B Booking
            </h2>
        </div>
        <div class="p-5" id="vertical-form">
            <form action="{{url('super_admin/searching_b2b')}}" method="post">
                @csrf
                <div class="preview grid grid-cols-12 gap-6 mt-5">
                    <div class="mt-3 intro-y col-span-12 lg:col-span-6">
                        <label>From</label>
                        <input type="date" class="input w-full border mt-2" name="date1">
                    </div>
                    <div class="mt-3 intro-y col-span-12 lg:col-span-6">
                        <label>To</label>
                        <input type="date" class="input w-full border mt-2" name="date2">
                    </div>
                </div>
                <div class="preview grid grid-cols-12 gap-6 mt-5">
                    <div class="mt-3 intro-y col-span-12 lg:col-span-6">
                        <label>Country</label>
                        <select name="country" class="select2 w-full" id="country">
                            <option>Select Country</option>
                            @foreach($get_agents_country as $get_agents_country)
                                <option value="{{ $get_agents_country->country}}">{{ $get_agents_country->country }}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="mt-3 intro-y col-span-12 lg:col-span-6" >
                        <label>City</label>
                        <select name="city" class="select2 w-full" id="city">

                            <option value="0" disabled="true" selected="true">Select City</option>
                        </select>

                    </div>
                </div>
                <div class="preview grid grid-cols-12 gap-6 mt-5">
                    <div class="mt-3 intro-y col-span-12 lg:col-span-6">
                        <label>Agent</label>
                        <select name="agent" class="select2 w-full" id="agent">

                            <option value="0" disabled="true" selected="true">Select agent</option>
                        </select>
                    </div>
                    <div class="mt-3 intro-y col-span-12 lg:col-span-6">
                        <label>Status</label>
                        <select name="status" class="select2 w-full">
                            <option value="">Select Status</option>
                            <option value="confirmed">confirmed</option>
                            <option value="failed">failed</option>
                            <option value="cancelled">cancelled</option>

                        </select>

                    </div>
                </div>



                <button type="submit"  name="submit" class="button inline-block bg-theme-1 text-white mt-5">Search</button>
            </form>
        </div>
    </div>
    <!-- END: Vertical Form -->
</div>




    <div class="intro-y box mt-5" style="width: 1154px;" >
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
            <h2 class="font-medium text-base mr-auto">
                B2B Booking
            </h2>

        </div>
        <div class="p-5">
            <div class="preview">
                <div class="overflow-x-auto">
                    <table class="table" id="example_booking_b2b">
                        <thead>
                        <tr>
                            <th class="border-b-2 whitespace-no-wrap">#No</th>
                            <th class="border-b-2 whitespace-no-wrap">Booking Type</th>
                            <th class="border-b-2 whitespace-no-wrap">Invoice No</th>
                            <th class="border-b-2 whitespace-no-wrap">Booking Ref No</th>
                            <!-- <th class="border-b-2 whitespace-no-wrap">Lead Passenger</th> -->
                            <!-- <th class="border-b-2 whitespace-no-wrap">Booking Date</th> -->
                            <th class="border-b-2 whitespace-no-wrap">Total Payment</th>
                            <!-- <th class="border-b-2 whitespace-no-wrap">Total Payable</th> -->
                            <!-- <th class="border-b-2 whitespace-no-wrap">Email</th> -->
                            <!-- <th class="border-b-2 whitespace-no-wrap">Status</th> -->
                            <th class="border-b-2 whitespace-no-wrap">Detail</th>
                        </tr>
                        </thead>


                        <tbody>

                        @foreach($data as  $booking)
                        @if($booking->hotel_makkah_brn != 'no_makkah')
                            <?php
                            if(empty($status)){  ?>
                           <tr>
                            <th class="border-b whitespace-no-wrap">{{ $booking->id }}</th>
                            <th class="border-b whitespace-no-wrap">Hotel Makkah
                                <div>
                                <?php
                                    if($booking->hotel_makkah_booking_status == 'confirmed'){
                                ?>
                                    <span class="text-theme-9">{{ucfirst($booking->hotel_makkah_booking_status)}}</span>
                                <?php
                                }
                                    elseif($booking->hotel_makkah_booking_status == 'cancelled'){
                                ?>
                                    <span class="text-theme-6">{{ucfirst($booking->hotel_makkah_booking_status)}}</span>
                                <?php
                                    }elseif ($booking->hotel_makkah_booking_status == 'failed') {
                                ?>
                                    <span class="text-theme-9">{{ucfirst($booking->hotel_makkah_booking_status)}}</span>
                                <?php
                                    }
                                ?>
                                </div>
                            </th>
                        <td class="border-b whitespace-no-wrap">{{ $booking->invoice_no }}<div>{{"Booking Date (". date('d-m-Y',strtotime($booking->created_at)) .")"}}</div></td>
                        <td class="border-b whitespace-no-wrap">
                            @php
                                $new_line = "<br>";
                                $brn = $booking->hotel_makkah_brn; 
                                $count = strlen($booking->hotel_makkah_brn);
                                $index = intval($count/2);
                                $first_half=substr($brn,0,$index);
                                $second_half=substr($brn,$index);
                                echo $first_half.$new_line.$second_half;
                            @endphp
                        </td>
                        <!-- <td class="border-b whitespace-no-wrap">{{ $booking->lead_passenger_name }}</td> -->
                        <!-- <td class="border-b whitespace-no-wrap">{{ date('Y-m-d',strtotime($booking->created_at)) }}</td> -->
                        <td class="border-b whitespace-no-wrap">SAR {{$booking->hotel_makkah_total_amount }}</td>
                        <!-- <td class="border-b whitespace-no-wrap">{{ $booking->hotel_makkah_total_amount }}</td> -->
                        <!-- <td class="border-b whitespace-no-wrap">{{ $booking->lead_passenger_email }}</td> -->
                        <!-- <td class="border-b whitespace-no-wrap">
                        <?php
                                    //if($booking->hotel_makkah_booking_status == 'confirmed'){
                                    ?>
                            <span class="text-theme-9">{{$booking->hotel_makkah_booking_status}}</span>
                            <?php
                            //}
                            //elseif($booking->hotel_makkah_booking_status == 'cancelled'){
                            ?>

                            <span class="text-theme-6">{{$booking->hotel_makkah_booking_status}}</span>


                            <?php
                            //}elseif ($booking->hotel_makkah_booking_status == 'failed') {
                            ?>
                            <span class="text-theme-9">{{$booking->hotel_makkah_booking_status}}</span>




                            <?php
                            //}
                            ?>
                        </td> -->
                        <td class="border-b whitespace-no-wrap flex">
                            <div class="table-content">
                                <form class="booking-detail-form" action="{{URL::to('super_admin/booking-detail')}}" method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id" value="{{$booking->id}}">
                                    <input type="hidden" name="brn" value="{{$booking->hotel_makkah_brn}}">
                                    <input type="hidden" name="case" value="hotel_makkah_view_booking">
                                    {{--                                                                                    <a href="{{url('user_dashboard/booking-detail/hotel_makkah_view_booking/'.$booking->hotel_makkah_brn)}}">--}}

                                    <button type="submit" data-url="{{url('super_admin/booking-detail/hotel_makkah_view_booking/'.$booking->hotel_makkah_brn.'/'.$booking->id)}}" class="button button--sm w-35 inline-block mr-1 mb-2 bg-theme-1 text-white">Details</button>

                                    {{--                                                                                    </a>--}}
                                </form>


                            </div>

                            <a href="https://b2b.dow.sa/booking_confirmation/{{$booking->invoice_no}}" target="_blank"  class="button button--sm w-30 inline-block mr-1 mb-2 bg-theme-12 text-white">voucher</a>
                               
                                   <a href="{{url('super_admin/assign_mandob/'.$booking->id)}}" target="_blank"  class="button button--sm w-34 inline-block mr-1 mb-2 bg-theme-6 text-white">Assign Mandoob</a>
                               
                        </td>
                        </tr>

                        <?php
                            }elseif (!empty($status) && $booking->hotel_makkah_booking_status == $status){
                              ?>

                            <tr>
                                <th class="border-b whitespace-no-wrap">{{ $booking->id }}</th>
                                <th class="border-b whitespace-no-wrap">Hotel Makkah
                                    <div>
                                    <?php
                                        if($booking->hotel_makkah_booking_status == 'confirmed'){
                                    ?>
                                    <span class="text-theme-9">{{ucfirst($booking->hotel_makkah_booking_status)}}</span>
                                    <?php
                                    }
                                    elseif($booking->hotel_makkah_booking_status == 'cancelled'){
                                    ?>
                                    <span class="text-theme-6">{{ucfirst($booking->hotel_makkah_booking_status)}}</span>
                                    <?php
                                    }elseif ($booking->hotel_makkah_booking_status == 'failed') {
                                    ?>
                                    <span class="text-theme-9">{{ucfirst($booking->hotel_makkah_booking_status)}}</span>
                                    <?php
                                    }
                                    ?>
                                    </div>
                                </th>
                                <td class="border-b whitespace-no-wrap">{{ $booking->invoice_no }}<div>{{ "Booking Date (".date('d-m-Y',strtotime($booking->created_at)) .")"}}</div></td>
                                <td class="border-b whitespace-no-wrap">{{--$booking->hotel_makkah_brn --}}
                                    @php
                                        $new_line = "<br>";
                                        $brn = $booking->hotel_makkah_brn; 
                                        $count = strlen($booking->hotel_makkah_brn);
                                        $index = intval($count/2);
                                        $first_half=substr($brn,0,$index);
                                        $second_half=substr($brn,$index);
                                        echo $first_half.$new_line.$second_half;
                                    @endphp
                                </td>
                                <!-- <td class="border-b whitespace-no-wrap">{{ $booking->lead_passenger_name }}</td> -->
                                <!-- <td class="border-b whitespace-no-wrap">{{ date('Y-m-d',strtotime($booking->created_at)) }}</td> -->
                                <td class="border-b whitespace-no-wrap">SAR {{ $booking->hotel_makkah_total_amount }}</td>
                                <!-- <td class="border-b whitespace-no-wrap">{{ $booking->hotel_makkah_total_amount }}</td> -->
                                <!-- <td class="border-b whitespace-no-wrap">{{ $booking->lead_passenger_email }}</td> -->
                                <!-- <td class="border-b whitespace-no-wrap">
                                    <?php
                                    //if($booking->hotel_makkah_booking_status == 'confirmed'){
                                    ?>
                                    <span class="text-theme-9">{{$booking->hotel_makkah_booking_status}}</span>
                                    <?php
                                    //}
                                    //elseif($booking->hotel_makkah_booking_status == 'cancelled'){
                                    ?>

                                    <span class="text-theme-6">{{$booking->hotel_makkah_booking_status}}</span>


                                    <?php
                                    //}elseif ($booking->hotel_makkah_booking_status == 'failed') {
                                    ?>
                                    <span class="text-theme-9">{{$booking->hotel_makkah_booking_status}}</span>




                                    <?php
                                    //}
                                    ?>
                                </td> -->
                                <td class="border-b whitespace-no-wrap flex">
                                    <div class="table-content">
                                        <form class="booking-detail-form" action="{{URL::to('super_admin/booking-detail')}}" method="post">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="id" value="{{$booking->id}}">
                                            <input type="hidden" name="brn" value="{{$booking->hotel_makkah_brn}}">
                                            <input type="hidden" name="case" value="hotel_makkah_view_booking">
                                            {{--                                                                                    <a href="{{url('user_dashboard/booking-detail/hotel_makkah_view_booking/'.$booking->hotel_makkah_brn)}}">--}}

                                            <button type="submit" data-url="{{url('super_admin/booking-detail/hotel_makkah_view_booking/'.$booking->hotel_makkah_brn.'/'.$booking->id)}}" class="button button--sm w-35 inline-block mr-1 mb-2 bg-theme-1 text-white">Details</button>

                                            {{--                                                                                    </a>--}}
                                        </form>


                                    </div>
                                    <a href="https://b2b.dow.sa/booking_confirmation/{{$booking->invoice_no}}" target="_blank"  class="button button--sm w-30 inline-block mr-1 mb-2 bg-theme-12 text-white">voucher</a>
                                    <a href="{{url('super_admin/assign_mandob/'.$booking->id)}}" target="_blank"  class="button button--sm w-34 inline-block mr-1 mb-2 bg-theme-6 text-white">Assign Mandoob</a>
                                </td>
                            </tr>

                            <?php
                        }
                            ?>

                        @endif

                        @if($booking->hotel_madina_brn != 'no_madina')
                            <?php
                            if(empty($status)){  ?>
                            <tr>
                                <th class="border-b whitespace-no-wrap">{{ $booking->id }}</th>
                                <th class="border-b whitespace-no-wrap">Hotel Madina
                                    <div>
                                    <?php
                                    if($booking->hotel_madina_booking_status == 'confirmed'){
                                    ?>
                                    <span class="text-theme-9">{{ucfirst($booking->hotel_madina_booking_status)}}</span>
                                    <?php
                                    }
                                    elseif($booking->hotel_madina_booking_status == 'cancelled'){
                                    ?>
                                    <span class="text-theme-6">{{ucfirst($booking->hotel_madina_booking_status)}}</span>
                                    <?php
                                    }elseif ($booking->hotel_madina_booking_status == 'failed') {
                                    ?>
                                    <span class="text-theme-9">{{ucfirst($booking->hotel_madina_booking_status)}}</span>
                                    <?php
                                    }
                                    ?>    
                                    </div>
                                </th>
                                <td class="border-b whitespace-no-wrap">{{ $booking->invoice_no}}<div>{{ "Booking Date (".date('d-m-Y',strtotime($booking->created_at)) .")"}}</div></td>
                                <td class="border-b whitespace-no-wrap">{{--$booking->hotel_madina_brn --}}
                                    @php
                                        $new_line = "<br>";
                                        $brn = $booking->hotel_madina_brn; 
                                        $count = strlen($booking->hotel_madina_brn);
                                        $index = intval($count/2);
                                        $first_half=substr($brn,0,$index);
                                        $second_half=substr($brn,$index);
                                        echo $first_half.$new_line.$second_half;
                                    @endphp
                                </td>
                                <!-- <td class="border-b whitespace-no-wrap">{{ $booking->lead_passenger_name }}</td> -->
                                <!-- <td class="border-b whitespace-no-wrap">{{ date('Y-m-d',strtotime($booking->created_at)) }}</td> -->
                                <td class="border-b whitespace-no-wrap">SAR {{ $booking->hotel_madina_total_amount }}</td>
                                <!-- <td class="border-b whitespace-no-wrap">{{ $booking->hotel_madina_total_amount }}</td> -->
                                <!-- <td class="border-b whitespace-no-wrap">{{ $booking->lead_passenger_email }}</td> -->
                                <!-- <td class="border-b whitespace-no-wrap">
                                    <?php
                                    //if($booking->hotel_madina_booking_status == 'confirmed'){
                                    ?>
                                    <span class="text-theme-9">{{$booking->hotel_madina_booking_status}}</span>
                                    <?php
                                    //}
                                    //elseif($booking->hotel_madina_booking_status == 'cancelled'){
                                    ?>

                                    <span class="text-theme-6">{{$booking->hotel_madina_booking_status}}</span>


                                    <?php
                                    //}elseif ($booking->hotel_madina_booking_status == 'failed') {
                                    ?>
                                    <span class="text-theme-9">{{$booking->hotel_madina_booking_status}}</span>




                                    <?php
                                    //}
                                    ?>
                                </td> -->
                                <td class="border-b whitespace-no-wrap flex">
                                    <div class="table-content">
                                        <form class="booking-detail-form" action="{{URL::to('super_admin/booking-detail')}}" method="post">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="id" value="{{$booking->id}}">
                                            <input type="hidden" name="brn" value="{{$booking->hotel_madina_brn}}">
                                            <input type="hidden" name="case" value="hotel_madina_view_booking">
{{--                                                                                    <a href="{{url('user_dashboard/booking-detail/hotel_madina_view_booking/'.$booking->hotel_madina_brn)}}">--}}
                                            <button type="submit" data-url="{{url('super_admin/booking-detail/hotel_madina_view_booking/'.$booking->hotel_madina_brn.'/'.$booking->id)}}" class="button button--sm w-35 inline-block mr-1 mb-2 bg-theme-1 text-white">Details</button>
{{--                                                                                    </a>--}}
                                        </form>
                                    </div>
                                    <a href="https://b2b.dow.sa/booking_confirmation/{{$booking->invoice_no}}" target="_blank"  class="button button--sm w-30 inline-block mr-1 mb-2 bg-theme-12 text-white">voucher</a>
                                    <a href="{{url('super_admin/assign_mandob/'.$booking->id)}}" target="_blank"  class="button button--sm w-34 inline-block mr-1 mb-2 bg-theme-6 text-white">Assign Mandoob</a>
                                </td>
                            </tr>
                            <?php
                            }elseif (!empty($status) && $booking->hotel_madina_booking_status == $status){
                            ?>
                            <tr>
                                <th class="border-b whitespace-no-wrap">{{ $booking->id }}</th>
                                <th class="border-b whitespace-no-wrap">Hotel Madina
                                    <div>
                                    <?php
                                    if($booking->hotel_madina_booking_status == 'confirmed'){
                                    ?>
                                    <span class="text-theme-9">{{ucfirst($booking->hotel_madina_booking_status)}}</span>
                                    <?php
                                    }
                                    elseif($booking->hotel_madina_booking_status == 'cancelled'){
                                    ?>
                                    <span class="text-theme-6">{{ucfirst($booking->hotel_madina_booking_status)}}</span>
                                    <?php
                                    }elseif ($booking->hotel_madina_booking_status == 'failed') {
                                    ?>
                                    <span class="text-theme-9">{{ucfirst($booking->hotel_madina_booking_status)}}</span>
                                    <?php
                                    }
                                    ?>  
                                    </div>
                                </th>
                                <td class="border-b whitespace-no-wrap">{{ $booking->invoice_no}}<div>{{ "Booking Date (".date('d-m-Y',strtotime($booking->created_at)) .")"}}</div></td>
                                <td class="border-b whitespace-no-wrap">{{--$booking->hotel_madina_brn --}}
                                    @php
                                        $new_line = "<br>";
                                        $brn = $booking->hotel_madina_brn; 
                                        $count = strlen($booking->hotel_madina_brn);
                                        $index = intval($count/2);
                                        $first_half=substr($brn,0,$index);
                                        $second_half=substr($brn,$index);
                                        echo $first_half.$new_line.$second_half;
                                    @endphp
                                </td>
                                <td class="border-b whitespace-no-wrap">{{ $booking->lead_passenger_name }}</td>
                                <!-- <td class="border-b whitespace-no-wrap">{{ date('Y-m-d',strtotime($booking->created_at)) }}</td> -->
                                <td class="border-b whitespace-no-wrap">SAR {{ $booking->hotel_madina_total_amount }}</td>
                                <!-- <td class="border-b whitespace-no-wrap">{{ $booking->hotel_madina_total_amount }}</td> -->
                                <!-- <td class="border-b whitespace-no-wrap">{{ $booking->lead_passenger_email }}</td> -->
                                <!-- <td class="border-b whitespace-no-wrap">
                                    <?php
                                    //if($booking->hotel_madina_booking_status == 'confirmed'){
                                    ?>
                                    <span class="text-theme-9">{{$booking->hotel_madina_booking_status}}</span>
                                    <?php
                                    //}
                                    //elseif($booking->hotel_madina_booking_status == 'cancelled'){
                                    ?>

                                    <span class="text-theme-6">{{$booking->hotel_madina_booking_status}}</span>


                                    <?php
                                    //}elseif ($booking->hotel_madina_booking_status == 'failed') {
                                    ?>
                                    <span class="text-theme-9">{{$booking->hotel_madina_booking_status}}</span>




                                    <?php
                                    //}
                                    ?>
                                </td> -->
                                <td class="border-b whitespace-no-wrap flex">
                                    <div class="table-content">
                                        <form class="booking-detail-form" action="{{URL::to('super_admin/booking-detail')}}" method="post">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="id" value="{{$booking->id}}">
                                            <input type="hidden" name="brn" value="{{$booking->hotel_madina_brn}}">
                                            <input type="hidden" name="case" value="hotel_madina_view_booking">
                                            {{--                                                                                    <a href="{{url('user_dashboard/booking-detail/hotel_madina_view_booking/'.$booking->hotel_madina_brn)}}">--}}
                                            <button type="submit" data-url="{{url('super_admin/booking-detail/hotel_madina_view_booking/'.$booking->hotel_madina_brn.'/'.$booking->id)}}" class="button button--sm w-35 inline-block mr-1 mb-2 bg-theme-1 text-white">Details</button>
                                            {{--                                                                                    </a>--}}
                                        </form>
                                    </div>
                                    <a href="https://b2b.dow.sa/booking_confirmation/{{$booking->invoice_no}}" target="_blank"  class="button button--sm w-30 inline-block mr-1 mb-2 bg-theme-12 text-white">voucher</a>
                                    <a href="{{url('super_admin/assign_mandob/'.$booking->id)}}" target="_blank"  class="button button--sm w-34 inline-block mr-1 mb-2 bg-theme-6 text-white">Assign Mandoob</a>
                                </td>
                            </tr>
                             <?php
                            }
                            ?>
                        @endif
                        @if($booking->transfer_brn != 'no_transfer')
                            <?php
                            if(empty($status)){  ?>
                            <tr>
                                <th class="border-b whitespace-no-wrap">{{ $booking->id }}</th>
                                <th class="border-b whitespace-no-wrap">Transfer
                                    <div>
                                    <?php
                                    if($booking->transfer_booking_status == 'confirmed'){
                                    ?>
                                    <span class="text-theme-9">{{ucfirst($booking->transfer_booking_status)}}</span>
                                    <?php
                                    }
                                    elseif($booking->transfer_booking_status == 'cancelled'){
                                    ?>
                                    <span class="text-theme-6">{{ucfirst($booking->transfer_booking_status)}}</span>
                                    <?php
                                    }elseif ($booking->transfer_booking_status == 'failed') {
                                    ?>
                                    <span class="text-theme-9">{{ucfirst($booking->transfer_booking_status)}}</span>
                                    <?php
                                    }
                                    ?>  
                                    </div>
                                    @php
                                        $provider = json_decode($booking->transfer_checkavailability);
                                        echo $provider->response->provider;
                                    @endphp
                                </th>
                                <td class="border-b whitespace-no-wrap">{{ $booking->invoice_no}}<div>{{ "Booking Date (".date('d-m-Y',strtotime($booking->created_at)) .")"}}</div></td>
                                <td class="border-b whitespace-no-wrap">{{--$booking->transfer_brn --}}
                                    @php
                                        $new_line = "<br>";
                                        $brn = $booking->transfer_brn; 
                                        $count = strlen($booking->transfer_brn);
                                        $index = intval($count/2);
                                        $first_half=substr($brn,0,$index);
                                        $second_half=substr($brn,$index);
                                        echo $first_half.$new_line.$second_half;
                                    @endphp
                                </td>
                                <!-- <td class="border-b whitespace-no-wrap">{{ $booking->lead_passenger_name }}</td> -->
                                <!-- <td class="border-b whitespace-no-wrap">{{ date('Y-m-d',strtotime($booking->created_at)) }}</td> -->
                                <td class="border-b whitespace-no-wrap">SAR {{ $booking->transfer_total_amount }}</td>
                                <!-- <td class="border-b whitespace-no-wrap">{{ $booking->transfer_total_amount }}</td> -->
                                <!-- <td class="border-b whitespace-no-wrap">{{ $booking->lead_passenger_email }}</td> -->
                                <!-- <td class="border-b whitespace-no-wrap">
                                    <?php
                                    //if($booking->transfer_booking_status == 'confirmed'){
                                    ?>
                                    <span class="text-theme-9">{{$booking->transfer_booking_status}}</span>
                                    <?php
                                    //}
                                    //elseif($booking->transfer_booking_status == 'cancelled'){
                                    ?>

                                    <span class="text-theme-6">{{$booking->transfer_booking_status}}</span>


                                    <?php
                                    //}elseif ($booking->transfer_booking_status == 'failed') {
                                    ?>
                                    <span class="text-theme-9">{{$booking->transfer_booking_status}}</span>
                                    <?php
                                    //}
                                    ?>
                                </td> -->
                                <td class="border-b whitespace-no-wrap flex">
                                    <div class="table-content">
                                        <form class="booking-detail-form" action="{{URL::to('super_admin/booking-detail')}}" method="post">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="id" value="{{$booking->id}}">
                                            <input type="hidden" name="brn" value="{{$booking->transfer_brn}}">
                                            <input type="hidden" name="case" value="transfers_view_booking">
{{--                                                                                    <a href="{{url('user_dashboard/booking-detail/transfer_view_booking/'.$booking->transfer_brn)}}">--}}
                                            <button type="submit" data-url="{{url('super_admin/booking-detail/transfer_view_booking/'.$booking->transfer_brn.'/'.$booking->id)}}" class="button button--sm w-35 inline-block mr-1 mb-2 bg-theme-1 text-white">Details</button>
{{--                                                                                    </a>--}}
                                        </form>
                                    </div>
                                    <a href="https://b2b.dow.sa/booking_confirmation/{{$booking->invoice_no}}"  target="_blank"  class="button button--sm w-30 inline-block mr-1 mb-2 bg-theme-12 text-white">voucher</a>
                                    <a href="{{url('super_admin/assign_mandob/'.$booking->id)}}" target="_blank"  class="button button--sm w-34 inline-block mr-1 mb-2 bg-theme-6 text-white">Assign Mandoob</a>
                                </td>
                            </tr>
                            <?php
                            }elseif (!empty($status) && $booking->transfer_booking_status == $status){
                                ?>
                            <tr>
                                <th class="border-b whitespace-no-wrap">{{ $booking->id }}</th>
                                <th class="border-b whitespace-no-wrap">Transfer
                                    <div>
                                    <?php
                                    if($booking->transfer_booking_status == 'confirmed'){
                                    ?>
                                    <span class="text-theme-9">{{ucfirst($booking->transfer_booking_status)}}</span>
                                    <?php
                                    }
                                    elseif($booking->transfer_booking_status == 'cancelled'){
                                    ?>
                                    <span class="text-theme-6">{{ucfirst($booking->transfer_booking_status)}}</span>
                                    <?php
                                    }elseif ($booking->transfer_booking_status == 'failed') {
                                    ?>
                                    <span class="text-theme-9">{{ucfirst($booking->transfer_booking_status)}}</span>
                                    <?php
                                    }
                                    ?>  
                                    </div>
                                    @php
                                        $provider = json_decode($booking->transfer_checkavailability);
                                        echo $provider->response->provider;
                                    @endphp
                                </th>
                                <td class="border-b whitespace-no-wrap">{{ $booking->invoice_no}}<div>{{ " (Booking Date".date('d-m-Y',strtotime($booking->created_at)).")" }}</div></td>
                                <td class="border-b whitespace-no-wrap">{{--$booking->transfer_brn-- }}
                                    @php
                                        $new_line = "<br>";
                                        $brn = $booking->transfer_brn; 
                                        $count = strlen($booking->transfer_brn);
                                        $index = intval($count/2);
                                        $first_half=substr($brn,0,$index);
                                        $second_half=substr($brn,$index);
                                        echo $first_half.$new_line.$second_half;
                                    @endphp
                                </td>
                                <!-- <td class="border-b whitespace-no-wrap">{{ $booking->lead_passenger_name }}</td> -->
                                <!-- <td class="border-b whitespace-no-wrap">{{ date('Y-m-d',strtotime($booking->created_at)) }}</td> -->
                                <td class="border-b whitespace-no-wrap">SAR {{ $booking->transfer_total_amount }}</td>
                                <!-- <td class="border-b whitespace-no-wrap">{{ $booking->transfer_total_amount }}</td> -->
                                <!-- <td class="border-b whitespace-no-wrap">{{ $booking->lead_passenger_email }}</td> -->
                                <!-- <td class="border-b whitespace-no-wrap">
                                    <?php
                                    //if($booking->transfer_booking_status == 'confirmed'){
                                    ?>
                                    <span class="text-theme-9">{{$booking->transfer_booking_status}}</span>
                                    <?php
                                    //}
                                    //elseif($booking->transfer_booking_status == 'cancelled'){
                                    ?>

                                    <span class="text-theme-6">{{$booking->transfer_booking_status}}</span>


                                    <?php
                                    //}elseif ($booking->transfer_booking_status == 'failed') {
                                    ?>
                                    <span class="text-theme-9">{{$booking->transfer_booking_status}}</span>




                                    <?php
                                    //}
                                    ?>
                                </td> -->
                                <td class="border-b whitespace-no-wrap flex">
                                    <div class="table-content">
                                        <form class="booking-detail-form" action="{{URL::to('super_admin/booking-detail')}}" method="post">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="id" value="{{$booking->id}}">
                                            <input type="hidden" name="brn" value="{{$booking->transfer_brn}}">
                                            <input type="hidden" name="case" value="transfers_view_booking">
                                            {{--                                                                                    <a href="{{url('user_dashboard/booking-detail/transfer_view_booking/'.$booking->transfer_brn)}}">--}}
                                            <button type="submit" data-url="{{url('super_admin/booking-detail/transfer_view_booking/'.$booking->transfer_brn.'/'.$booking->id)}}" class="button button--sm w-35 inline-block mr-1 mb-2 bg-theme-1 text-white">Details</button>
                                            {{--                                                                                    </a>--}}
                                        </form>
                                    </div>
                                    <a href="https://b2b.dow.sa/booking_confirmation/{{$booking->invoice_no}}"  target="_blank"  class="button button--sm w-30 inline-block mr-1 mb-2 bg-theme-12 text-white">voucher</a>
                                    <a href="{{url('super_admin/assign_mandob/'.$booking->id)}}" target="_blank"  class="button button--sm w-34 inline-block mr-1 mb-2 bg-theme-6 text-white">Assign Mandoob</a>
                                </td>
                            </tr>


                            <?php
                            }
                            ?>
                        @endif
                        @endforeach
                        </tbody>


                    </table>
                </div>
            </div>
        </div>
    </div>

{{--                <script src="https://dow.sa/public/assets/frontend/js/jquery-3.4.1.min.js"></script>--}}



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){

        $(document).on('change','#country',function(){
             //console.log("hmm its change");

            var cat_id=$(this).val();
            $('#city').find('option').not(':first').remove();
           //console.log(cat_id);
            var div=$(this).parent();

            var op=" ";

            $.ajax({
                type:'get',
                url:'{!!URL::to('findCityName')!!}',
                data:{'city':cat_id},
                success:function(data){
                  //console.log('success');

                  // console.log(data);

              //console.log(data.length);

                    var formoption = "";
                    $.each(data, function(v)
                    {
                        var val = data[v];
                        formoption += "<option value='"+val['id']+"'>"+val['city']+"</option>";
                    });

                    $('#city').append(formoption);

                },
                error:function(){

                }
            });
        });


{{--        $(document).on('change','#city',function(){--}}
{{--            //console.log("hmm its change");--}}

{{--            var cat_id=$(this).val();--}}
{{--            //console.log(cat_id);--}}
{{--            var div=$(this).parent();--}}

{{--            var op=" ";--}}

{{--            $.ajax({--}}
{{--                type:'get',--}}
{{--                url:'{!!URL::to('findAgentName')!!}',--}}
{{--                data:{'id':cat_id},--}}
{{--                success:function(data){--}}
{{--                    //console.log('success');--}}

{{--                    //console.log(data);--}}

{{--                    //console.log(data.length);--}}

{{--                    var formoption = "";--}}
{{--                    $.each(data, function(v)--}}
{{--                    {--}}
{{--                        var val = data[v];--}}
{{--                        formoption += "<option value='"+val['id']+"'>"+val['first_name']+"</option>";--}}
{{--                    });--}}
{{--console.log(formoption);--}}
{{--                    $('#agent').append(formoption);--}}

{{--                },--}}
{{--                error:function(){--}}

{{--                }--}}
{{--            });--}}
{{--        });--}}



    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#city').change(function () {
            var id = $(this).val();
            // alert(id);
         //console.log(id);
            $('#agent').find('option').not(':first').remove();

            $.ajax({
                url:'findAgentName/'+id,
                type:'get',
                dataType:'json',
                success:function(data){
                    //console.log('success');

                console.log(data);


                    var formoption = "";
                    $.each(data, function(v)
                    {
                        var val = data[v];
                        formoption += "<option value='"+val['id']+"'>"+val['first_name']+"</option>";
                    });

                    $('#agent').append(formoption);

                },

            })
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#example_booking_b2b').DataTable({});
    } );
</script>

   @stop


