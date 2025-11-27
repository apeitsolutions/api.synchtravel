<?php
    $data_countries = array();

foreach ($get_country as $value) {

    foreach($get_latitude as $lat_lon)
    {
        if(strtolower($lat_lon->name) == strtolower($value->country))
        {
            $latitude = $lat_lon->latitude;
            $longitude = $lat_lon->longitude;
            $country = $value->first_name;
            $co_country=count(array($country));
        }
    }
    $count_country=$value->country;
     
 
    $data_countries[]['data'] = array(
        'name' => $count_country,
         'first_name' => $co_country,
        'latitude' => $latitude,
        'longitude' => $longitude
    );


}





?>
@extends('template/frontend/userdashboard/layout/default')
 @section('content')

     <style>
         #chartdiv {
             width: 100%;
             height: 500px;
             overflow: hidden;
         }
     </style>
<input type="hidden" id="countries_list" value='<?php echo json_encode($data_countries); ?>' />

     <div class="text-lg font-medium truncate mr-5">
         Welcome, {{ auth()->guard('web')->user()->name }} <br>
         In the Admin Dashboard...
     </div>
         <div class="grid grid-cols-12 gap-6">
             <div class="col-span-12 xxl:col-span-9 grid grid-cols-12 gap-6">
                 


                 <!-- BEGIN: General Report -->
                 <div class="col-span-12 mt-8">
                     <div class="intro-y flex items-center h-10">
                         <h2 class="text-lg font-medium truncate mr-5">
                             Bookings on B2B Platform
                         </h2>
                     </div>
                     <div class="grid grid-cols-12 gap-6 mt-5">
                         <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                             <div class="report-box zoom-in">
                                 <div class="box p-5">
                                     <div class="flex">
                                         <img class="report-box__icon text-theme-10" src="{{asset('public/dist/images/booking.png')}}">
                                     </div>
                                     <div class="text-3xl font-bold leading-8 mt-6"><?php echo $total_booking_count_b2b;?></div>
                                     <div class="text-base text-gray-600 mt-1">Total B2B  Booking</div>
                                 </div>
                             </div>
                         </div>
                         <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                             <div class="report-box zoom-in">
                                 <div class="box p-5">
                                     <div class="flex">
                                         <img class="report-box__icon text-theme-10" src="{{asset('public/dist/images/booking.png')}}">
                                     </div>
                                     <div class="text-3xl font-bold leading-8 mt-6">{{$total_bookingsb2b['hotel_makkah']}}</div>
                                     <div class="text-base text-gray-600 mt-1">Hotel Makkah Bookings</div>
                                 </div>
                             </div>
                         </div>
                         <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                             <div class="report-box zoom-in">
                                 <div class="box p-5">
                                     <div class="flex">
                                         <img class="report-box__icon text-theme-10" src="{{asset('public/dist/images/booking.png')}}">
                                     </div>
                                     <div class="text-3xl font-bold leading-8 mt-6">{{$total_bookingsb2b['hotel_madina']}}</div>
                                     <div class="text-base text-gray-600 mt-1">Hotel Madina Bookings</div>
                                 </div>
                             </div>
                         </div>
                         <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                             <div class="report-box zoom-in">
                                 <div class="box p-5">
                                     <div class="flex">
                                         <img class="report-box__icon text-theme-10" src="{{asset('public/dist/images/booking.png')}}">
                                     </div>
                                     <div class="text-3xl font-bold leading-8 mt-6">{{$total_bookingsb2b['transfer']}}</div>
                                     <div class="text-base text-gray-600 mt-1">Transfer Bookings</div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
                 <!-- revenue wise -->
                 <div class="col-span-12 mt-8">
                     <div class="intro-y flex items-center h-10">
                         <h2 class="text-lg font-medium truncate mr-5">
                             Revenue on B2B Platform
                         </h2>
                     </div>
                     <div class="grid grid-cols-12 gap-6 mt-5">
                         <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                             <a href="{{url('super_admin/total_revenue_bookings')}}">
                                 <div class="report-box zoom-in">
                                     <div class="box p-5">
                                         <div class="flex">
                                             <img class="report-box__icon text-theme-10" src="{{URL::asset('public/frontend/images/accounts.png')}}">
                                         </div>
                                         <div class="text-3xl font-bold leading-8 mt-6">SAR{{" ".round($revenue['total_revenue'])}}</div>
                                         <div class="text-base text-gray-600 mt-1">Total Revenue</div>
                                     </div>
                                 </div>
                             </a>
                         </div>
                         <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                             <a href="{{url('super_admin/today_revenue_bookings')}}">
                                 <div class="report-box zoom-in">
                                     <div class="box p-5">
                                         <div class="flex">
                                             <img class="report-box__icon text-theme-10" src="{{URL::asset('public/frontend/images/accounts.png')}}">
                                         </div>
                                         <div class="text-3xl font-bold leading-8 mt-6">SAR{{" ".round($revenue['total_today'])}}</div>
                                         <div class="text-base text-gray-600 mt-1">Revenue (Today)</div>
                                     </div>
                                 </div>
                             </a>
                         </div>
                         <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                             <a href="{{url('super_admin/this_week_revenue_bookings')}}">
                                 <div class="report-box zoom-in">
                                     <div class="box p-5">
                                         <div class="flex">
                                             <img class="report-box__icon text-theme-10" src="{{URL::asset('public/frontend/images/accounts.png')}}">
                                         </div>
                                         <div class="text-3xl font-bold leading-8 mt-6">SAR{{" ".round($revenue['total_this_week'])}}</div>
                                         <div class="text-base text-gray-600 mt-1">Revenue (This Week)</div>
                                     </div>
                                 </div>
                             </a>
                         </div>
                         <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                             <a href="{{url('super_admin/this_month_revenue_bookings')}}">
                                 <div class="report-box zoom-in">
                                     <div class="box p-5">
                                         <div class="flex">
                                             <img class="report-box__icon text-theme-10" src="{{URL::asset('public/frontend/images/accounts.png')}}">
                                         </div>
                                         <div class="text-3xl font-bold leading-8 mt-6">SAR{{" ".round($revenue['total_this_month'])}}</div>
                                         <div class="text-base text-gray-600 mt-1">Revenue (This Month)</div>
                                     </div>


                                     
                                 </div>
                             </a>
                         </div>
                     </div>
                 </div>
                 <!-- END: General Report -->
                 <!-- hotel makkah revenue cards -->
                 <div class="col-span-12 mt-8">
                     <div class="intro-y flex items-center h-10">
                         <h2 class="text-lg font-medium truncate mr-5">
                             Hotel Makkah Bookings Revenue on B2B Platform
                         </h2>
                     </div>
                     <div class="grid grid-cols-12 gap-6 mt-5">
                         <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                             <div class="report-box zoom-in get_list" data-category_type="makkah" data-range="year">
                                 <div class="box p-5">
                                     <div class="flex">
                                         <img class="report-box__icon text-theme-10" src="{{URL::asset('public/frontend/images/accounts.png')}}">
                                     </div>
                                     <div class="text-3xl font-bold leading-8 mt-6">SAR{{" ".round($category_stats['makkah']['year'])}}</div>
                                     <div class="text-base text-gray-600 mt-1">Total Revenue</div>
                                 </div>
                             </div>
                         </div>
                         <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                             <div class="report-box zoom-in get_list" data-category_type="makkah" data-range="today">
                                 <div class="box p-5">
                                     <div class="flex">
                                         <img class="report-box__icon text-theme-10" src="{{URL::asset('public/frontend/images/accounts.png')}}">
                                     </div>
                                     <div class="text-3xl font-bold leading-8 mt-6">SAR{{" ".round($category_stats['makkah']['today'])}}</div>
                                     <div class="text-base text-gray-600 mt-1">Revenue (Today)</div>
                                 </div>
                             </div>
                         </div>
                         <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                             <div class="report-box zoom-in get_list" data-category_type="makkah" data-range="this_week">
                                 <div class="box p-5">
                                     <div class="flex">
                                         <img class="report-box__icon text-theme-10" src="{{URL::asset('public/frontend/images/accounts.png')}}">
                                     </div>
                                     <div class="text-3xl font-bold leading-8 mt-6">SAR{{" ".round($category_stats['makkah']['this_week'])}}</div>
                                     <div class="text-base text-gray-600 mt-1">Revenue (This Week)</div>
                                 </div>
                             </div>
                         </div>
                         <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                             <div class="report-box zoom-in get_list" data-category_type="makkah" data-range="this_month">
                                 <div class="box p-5">
                                     <div class="flex">
                                         <img class="report-box__icon text-theme-10" src="{{URL::asset('public/frontend/images/accounts.png')}}">
                                     </div>
                                     <div class="text-3xl font-bold leading-8 mt-6">SAR{{" ".round($category_stats['makkah']['this_month'])}}</div>
                                     <div class="text-base text-gray-600 mt-1">Revenue (This Month)</div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
                 <!-- hotel makkah revenue cards -->
                 <!-- hotel madina revenue cards -->
                 <div class="col-span-12 mt-8">
                     <div class="intro-y flex items-center h-10">
                         <h2 class="text-lg font-medium truncate mr-5">
                             Hotel Madina Bookings Revenue on B2B Platform
                         </h2>
                     </div>
                     <div class="grid grid-cols-12 gap-6 mt-5">
                         <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                             <div class="report-box zoom-in get_list" data-category_type="madina" data-range="year">
                                 <div class="box p-5">
                                     <div class="flex">
                                         <img class="report-box__icon text-theme-10" src="{{URL::asset('public/frontend/images/accounts.png')}}">
                                     </div>
                                     <div class="text-3xl font-bold leading-8 mt-6">SAR{{" ".round($category_stats['madina']['year'])}}</div>
                                     <div class="text-base text-gray-600 mt-1">Total Revenue</div>
                                 </div>
                             </div>
                         </div>
                         <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                             <div class="report-box zoom-in get_list" data-category_type="madina" data-range="today">
                                 <div class="box p-5">
                                     <div class="flex">
                                         <img class="report-box__icon text-theme-10" src="{{URL::asset('public/frontend/images/accounts.png')}}">
                                     </div>
                                     <div class="text-3xl font-bold leading-8 mt-6">SAR{{" ".round($category_stats['madina']['today'])}}</div>
                                     <div class="text-base text-gray-600 mt-1">Revenue (Today)</div>
                                 </div>
                             </div>
                         </div>
                         <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                             <div class="report-box zoom-in get_list" data-category_type="madina" data-range="this_week">
                                 <div class="box p-5">
                                     <div class="flex">
                                         <img class="report-box__icon text-theme-10" src="{{URL::asset('public/frontend/images/accounts.png')}}">
                                     </div>
                                     <div class="text-3xl font-bold leading-8 mt-6">SAR{{" ".round($category_stats['madina']['this_week'])}}</div>
                                     <div class="text-base text-gray-600 mt-1">Revenue (This Week)</div>
                                 </div>
                             </div>
                         </div>
                         <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                             <div class="report-box zoom-in get_list" data-category_type="madina" data-range="this_month">
                                 <div class="box p-5">
                                     <div class="flex">
                                         <img class="report-box__icon text-theme-10" src="{{URL::asset('public/frontend/images/accounts.png')}}">
                                     </div>
                                     <div class="text-3xl font-bold leading-8 mt-6">SAR{{" ".round($category_stats['madina']['this_month'])}}</div>
                                     <div class="text-base text-gray-600 mt-1">Revenue (This Month)</div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
                 <!-- hotel madina revenue cards -->
                 <!-- transfer revenue cards -->
                 <div class="col-span-12 mt-8">
                     <div class="intro-y flex items-center h-10">
                         <h2 class="text-lg font-medium truncate mr-5">
                             Transfer Bookings Revenue on B2B Platform
                         </h2>
                     </div>
                     <div class="grid grid-cols-12 gap-6 mt-5">
                         <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                             <div class="report-box zoom-in get_list" data-category_type="transfer" data-range="year">
                                 <div class="box p-5">
                                     <div class="flex">
                                         <img class="report-box__icon text-theme-10" src="{{URL::asset('public/frontend/images/accounts.png')}}">
                                     </div>
                                     <div class="text-3xl font-bold leading-8 mt-6">SAR{{" ".round($category_stats['transfer']['year'])}}</div>
                                     <div class="text-base text-gray-600 mt-1">Total Revenue</div>
                                 </div>
                             </div>
                         </div>
                         <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                             <div class="report-box zoom-in get_list" data-category_type="transfer" data-range="today">
                                 <div class="box p-5">
                                     <div class="flex">
                                         <img class="report-box__icon text-theme-10" src="{{URL::asset('public/frontend/images/accounts.png')}}">
                                     </div>
                                     <div class="text-3xl font-bold leading-8 mt-6">SAR{{" ".round($category_stats['transfer']['today'])}}</div>
                                     <div class="text-base text-gray-600 mt-1">Revenue (Today)</div>
                                 </div>
                             </div>
                         </div>
                         <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                             <div class="report-box zoom-in get_list" data-category_type="transfer" data-range="this_week">
                                 <div class="box p-5">
                                     <div class="flex">
                                         <img class="report-box__icon text-theme-10" src="{{URL::asset('public/frontend/images/accounts.png')}}">
                                     </div>
                                     <div class="text-3xl font-bold leading-8 mt-6">SAR{{" ".round($category_stats['transfer']['this_week'])}}</div>
                                     <div class="text-base text-gray-600 mt-1">Revenue (This Week)</div>
                                 </div>
                             </div>
                         </div>
                         <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                             <div class="report-box zoom-in get_list" data-category_type="transfer" data-range="this_month">
                                 <div class="box p-5">
                                     <div class="flex">
                                         <img class="report-box__icon text-theme-10" src="{{URL::asset('public/frontend/images/accounts.png')}}">
                                     </div>
                                     <div class="text-3xl font-bold leading-8 mt-6">SAR{{" ".round($category_stats['transfer']['this_month'])}}</div>
                                     <div class="text-base text-gray-600 mt-1">Revenue (This Month)</div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
                 <form method="POST" id="revenue_form" action="{{url('super_admin/get_revenue_list') }}">
                     <input id="category_input" type="hidden" name="category">
                     <input id="range_input" type="hidden" name="range">
                 </form>
                 <!-- transfer revenue cards -->


                 <div class="col-span-12 mt-8">
                     <div class="intro-y flex items-center h-10">
                         <h2 class="text-lg font-medium truncate mr-5">
                             B2B Platform Bookings Stats
                         </h2>
                     </div>
                 </div>
                 <!-- END: General Report -->

                 <div class="col-span-12 mt-8">
                     <div class="pos">
                         <!-- BEGIN: Item List -->

                         <!-- END: Item List -->
                         <!-- BEGIN: Ticket -->
                         <div class="col-span-12 lg:col-span-4">
                             <div class="intro-y pr-1">
                                 <div class="box p-2">
                                     <div class="pos__tabs nav-tabs justify-center flex">
                                         <a data-toggle="tab" data-target="#ticket_5" href="javascript:;" class="flex-1 py-2 rounded-md text-center active">
                                             Total Booking</a>
                                         <a data-toggle="tab" data-target="#details_6" href="javascript:;" class="flex-1 py-2 rounded-md text-center">
                                             Month Booking</a>
                                         <a data-toggle="tab" data-target="#ticket_7" href="javascript:;" class="flex-1 py-2 rounded-md text-center">
                                             Week Booking</a>
                                         <a data-toggle="tab" data-target="#details_8" href="javascript:;" class="flex-1 py-2 rounded-md text-center">
                                             Today Booking</a> </div>
                                 </div>
                             </div>
                             <div class="tab-content">
                                 <div class="tab-content__pane active" id="ticket_5">
                                     <div class="col-span-12 sm:col-span-6 xxl:col-span-3 intro-y">
                                         <!-- BEGIN: Horizontal Bar Chart -->
                                         <div class="col-span-6 intro-y box mt-5">
                                             <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                                                 <h2 class="font-medium text-base mr-auto">
                                                     B2B Platform Bookings Stats
                                                 </h2>

                                             </div>
                                             <div class="p-5" id="horizontal-bar-chart">
                                                 <div class="preview">
                                                     <div id="chart_div_b2b" style="height: 370px; width: 100%;">

                                                     </div>
                                                 </div>
                                                 
                                             </div>
                                         </div>
                                         <!-- END: Horizontal Bar Chart -->
                                     </div>
                                 </div>
                                 <div class="tab-content__pane" id="details_6">
                                     <div class="col-span-12 sm:col-span-6 xxl:col-span-3 intro-y">
                                         <!-- BEGIN: Horizontal Bar Chart -->
                                         <div class="col-span-6 intro-y box mt-5">
                                             <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                                                 <h2 class="font-medium text-base mr-auto">
                                                     B2B Platform Bookings Stats
                                                 </h2>

                                             </div>
                                             <div class="p-5" id="horizontal-bar-chart">
                                                 <div class="preview">
                                                     <div id="chartContainer6" style="height: 370px; width: 100%;">

                                                     </div>
                                                 </div>
                                                
                                             </div>
                                         </div>
                                         <!-- END: Horizontal Bar Chart -->
                                     </div>

                                 </div>
                                 <div class="tab-content__pane" id="ticket_7">
                                     <div class="col-span-12 sm:col-span-6 xxl:col-span-3 intro-y">
                                         <!-- BEGIN: Horizontal Bar Chart -->
                                         <div class="col-span-6 intro-y box mt-5">
                                             <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                                                 <h2 class="font-medium text-base mr-auto">
                                                     B2B Platform Bookings Stats
                                                 </h2>

                                             </div>
                                             <div class="p-5" id="horizontal-bar-chart">
                                                 <div class="preview">
                                                     <div id="chartContainer7" style="height: 370px; width: 100%;">

                                                     </div>
                                                 </div>
                                                 
                                             </div>
                                         </div>
                                         <!-- END: Horizontal Bar Chart -->
                                     </div>
                                 </div>
                                 <div class="tab-content__pane" id="details_8">
                                     <div class="col-span-12 sm:col-span-6 xxl:col-span-3 intro-y">
                                         <!-- BEGIN: Horizontal Bar Chart -->
                                         <div class="col-span-6 intro-y box mt-5">
                                             <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                                                 <h2 class="font-medium text-base mr-auto">
                                                     B2B Platform Bookings Stats
                                                 </h2>

                                             </div>
                                             <div class="p-5" id="horizontal-bar-chart">
                                                 <div class="preview">
                                                     <div id="chartContainer8" style="height: 370px; width: 100%;">

                                                     </div>
                                                 </div>
                                                
                                             </div>
                                         </div>
                                         <!-- END: Horizontal Bar Chart -->
                                     </div>
                                 </div>
                             </div>
                         </div>
                         <!-- END: Ticket -->
                     </div>
                 </div>








                 <div class="col-span-12 mt-8">
                     <div class="intro-y box mt-5">
                         <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                             <h2 class="font-medium text-base mr-auto">
                                 B2B Agents
                             </h2>

                         </div>
                         <div class="p-5">
                             <div class="preview">
                                 <div class="overflow-x-auto">
                                     <table class="table dt" id="example_1">
                                         <thead>
                                         <tr>
                                             <th class="border-b-2 whitespace-no-wrap">#id</th>
                                             <th class="border-b-2 whitespace-no-wrap">Title</th>
                                             <th class="border-b-2 whitespace-no-wrap">UserName</th>
                                             <th class="border-b-2 whitespace-no-wrap">Email</th>
                                             <th class="border-b-2 whitespace-no-wrap">Country</th>
                                             <th class="border-b-2 whitespace-no-wrap">City</th>
                                             <th class="border-b-2 whitespace-no-wrap">Phone</th>
                                         </tr>
                                         </thead>

                                         <tbody>
                                         @foreach($b2b_agents as $b2b_agents)

                                             <tr>
                                                 <td class="border-b whitespace-no-wrap">{{$b2b_agents->id}}</td>
                                                 <td class="border-b whitespace-no-wrap">{{$b2b_agents->title}}</td>
                                                 <td class="border-b whitespace-no-wrap">{{$b2b_agents->first_name}}</td>
                                                 <td class="border-b whitespace-no-wrap">{{$b2b_agents->email}}</td>
                                                 <td class="border-b whitespace-no-wrap">{{$b2b_agents->country}}</td>
                                                 <td class="border-b whitespace-no-wrap">{{$b2b_agents->city}}</td>
                                                 <td class="border-b whitespace-no-wrap">{{$b2b_agents->phone_no}}</td>

                                             </tr>



                                         @endforeach
                                         </tbody>

                                     </table>
                                 </div>
                             </div>
                            
                         </div>
                     </div>
                 </div>
                 
                 
                 
                 <!-- BEGIN: General Report -->
                 <div class="col-span-12 mt-8">
                     <div class="intro-y flex items-center h-10">
                         <h2 class="text-lg font-medium truncate mr-5">
                             Bookings on B2C Platform
                         </h2>

                     </div>
                     <div class="grid grid-cols-12 gap-6 mt-5">
                         <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                             <div class="report-box zoom-in">
                                 <div class="box p-5">
                                     <div class="flex">
                                        <img class="report-box__icon text-theme-10" src="{{asset('public/dist/images/booking.png')}}">
                                         <!-- <i data-feather="shopping-cart" class="report-box__icon text-theme-10"></i> -->
                                     </div>
                                     <div class="text-3xl font-bold leading-8 mt-6"><?php echo $total_booking_count_b2c;?></div>
                                     <div class="text-base text-gray-600 mt-1">Total B2C Booking</div>
                                 </div>
                             </div>
                         </div>
                         <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                             <div class="report-box zoom-in">
                                 <div class="box p-5">
                                     <div class="flex">
                                         <img class="report-box__icon text-theme-10" src="{{asset('public/dist/images/booking.png')}}">
                                     </div>
                                     <div class="text-3xl font-bold leading-8 mt-6">{{$total_bookingsb2c['hotel_makkah']}}</div>
                                     <div class="text-base text-gray-600 mt-1">Hotel Makkah Bookings</div>
                                 </div>
                             </div>
                         </div>
                         <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                             <div class="report-box zoom-in">
                                 <div class="box p-5">
                                     <div class="flex">
                                        <img class="report-box__icon text-theme-10" src="{{asset('public/dist/images/booking.png')}}">
                                     </div>
                                     <div class="text-3xl font-bold leading-8 mt-6">{{$total_bookingsb2c['hotel_madina']}}</div>
                                     <div class="text-base text-gray-600 mt-1">Hotel Madina Bookings</div>
                                 </div>
                             </div>
                         </div>
                         <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                             <div class="report-box zoom-in">
                                 <div class="box p-5">
                                     <div class="flex">
                                        <img class="report-box__icon text-theme-10" src="{{asset('public/dist/images/booking.png')}}">
                                     </div>
                                     <div class="text-3xl font-bold leading-8 mt-6">{{$total_bookingsb2c['transfer']}}</div>
                                     <div class="text-base text-gray-600 mt-1">Transfer Bookings</div>
                                 </div>
                             </div>
                         </div>
                         <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                             <div class="report-box zoom-in">
                                 <div class="box p-5">
                                     <div class="flex">
                                         <img class="report-box__icon text-theme-10" src="{{asset('public/dist/images/booking.png')}}">
                                     </div>
                                     <div class="text-3xl font-bold leading-8 mt-6">{{$total_bookingsb2c['ground_services']}}</div>
                                     <div class="text-base text-gray-600 mt-1">Ground Services Bookings</div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
                 <!-- END: General Report -->

                 <!-- BEGIN: General Report -->
                 <div class="col-span-12 mt-8">
                     <div class="intro-y flex items-center h-10">
                         <h2 class="text-lg font-medium truncate mr-5">
                             B2B Platform
                         </h2>

                     </div>

                         <div id="chartdiv"></div>

                 </div>
                 <!-- END: General Report -->


                 <!-- BEGIN: General Report -->
                 <div class="col-span-12 mt-8">
                     <div class="intro-y flex items-center h-10">
                         <h2 class="text-lg font-medium truncate mr-5">
                             B2C Platform Bookings Stats
                         </h2>
                     </div>
                 </div>
                 <!-- END: General Report -->
{{--graph start--}}
                <div class="col-span-12 mt-8">
                    <div class="pos">
                        <!-- BEGIN: Item List -->

                        <!-- END: Item List -->
                        <!-- BEGIN: Ticket -->
                        <div class="col-span-12 lg:col-span-4">
                            <div class="intro-y pr-1">
                                <div class="box p-2">
                                    <div class="pos__tabs nav-tabs justify-center flex">
                                        <a data-toggle="tab" data-target="#ticket" href="javascript:;" class="flex-1 py-2 rounded-md text-center active">
                                            Total Booking</a>
                                        <a data-toggle="tab" data-target="#details" href="javascript:;" class="flex-1 py-2 rounded-md text-center">
                                            Month Booking</a>
                                    <a data-toggle="tab" data-target="#ticket_1" href="javascript:;" class="flex-1 py-2 rounded-md text-center">
                                        Week Booking</a>
                                    <a data-toggle="tab" data-target="#details_1" href="javascript:;" class="flex-1 py-2 rounded-md text-center">
                                        Today Booking</a> </div>
                                </div>
                            </div>
                            <div class="tab-content">
                                <div class="tab-content__pane active" id="ticket">
                                    <div class="col-span-12 sm:col-span-6 xxl:col-span-3 intro-y">
                                        <!-- BEGIN: Horizontal Bar Chart -->
                                        <div class="col-span-6 intro-y box mt-5">
                                            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                                                <h2 class="font-medium text-base mr-auto">
                                                    B2C Platform Bookings Stats
                                                </h2>

                                            </div>
                                            <div class="p-5" id="horizontal-bar-chart">
                                                <div class="preview">
                                                    <div id="chart_div" style="height: 370px; width: 100%;">

                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <!-- END: Horizontal Bar Chart -->
                                    </div>
                                </div>
                                <div class="tab-content__pane" id="details">
                                    <div class="col-span-12 sm:col-span-6 xxl:col-span-3 intro-y">
                                        <!-- BEGIN: Horizontal Bar Chart -->
                                        <div class="col-span-6 intro-y box mt-5">
                                            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                                                <h2 class="font-medium text-base mr-auto">
                                                    B2C Platform Bookings Stats
                                                </h2>

                                            </div>
                                            <div class="p-5" id="horizontal-bar-chart">
                                                <div class="preview">
                                                    <div id="chartContainer2" style="height: 370px; width: 100%;">

                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <!-- END: Horizontal Bar Chart -->
                                    </div>

                                </div>
                                <div class="tab-content__pane" id="ticket_1">
                                    <div class="col-span-12 sm:col-span-6 xxl:col-span-3 intro-y">
                                        <!-- BEGIN: Horizontal Bar Chart -->
                                        <div class="col-span-6 intro-y box mt-5">
                                            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                                                <h2 class="font-medium text-base mr-auto">
                                                    B2C Platform Bookings Stats
                                                </h2>

                                            </div>
                                            <div class="p-5" id="horizontal-bar-chart">
                                                <div class="preview">
                                                    <div id="chartContainer3" style="height: 370px; width: 100%;">

                                                    </div>
                                                </div>
                                               
                                            </div>
                                        </div>
                                        <!-- END: Horizontal Bar Chart -->
                                    </div>
                                </div>
                                <div class="tab-content__pane" id="details_1">
                                    <div class="col-span-12 sm:col-span-6 xxl:col-span-3 intro-y">
                                        <!-- BEGIN: Horizontal Bar Chart -->
                                        <div class="col-span-6 intro-y box mt-5">
                                            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                                                <h2 class="font-medium text-base mr-auto">
                                                    B2C Platform Bookings Stats
                                                </h2>

                                            </div>
                                            <div class="p-5" id="horizontal-bar-chart">
                                                <div class="preview">
                                                    <div id="chartContainer4" style="height: 370px; width: 100%;">

                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <!-- END: Horizontal Bar Chart -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END: Ticket -->
                    </div>
                </div>
                 <!-- BEGIN: General Report -->
                 
                 <div class="col-span-12 mt-8">
                     <div class="intro-y box mt-5">
                         <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                             <h2 class="font-medium text-base mr-auto">
                                 B2C Users
                             </h2>

                         </div>
                         <div class="p-5">
                             <div class="preview">
                                 <div class="overflow-x-auto">
                                     <table class="table dt" id="example_2">
                                         <thead>
                                         <tr>
                                             <th class="border-b-2 whitespace-no-wrap">#id</th>
                                             <th class="border-b-2 whitespace-no-wrap">UserName</th>
                                             <th class="border-b-2 whitespace-no-wrap">Email</th>
                                             <th class="border-b-2 whitespace-no-wrap">Phone</th>
                                         </tr>
                                         </thead>

                                         <tbody>
                                         @foreach($b2c_users as $b2c_users)

                                             <tr>
                                                 <td class="border-b whitespace-no-wrap">{{$b2c_users->id}}</td>
                                                 <td class="border-b whitespace-no-wrap">{{$b2c_users->name}}</td>
                                                 <td class="border-b whitespace-no-wrap">{{$b2c_users->email}}</td>
                                                 <td class="border-b whitespace-no-wrap">{{$b2c_users->phone}}</td>

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























     <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
     <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
         <script src="https://canvasjs.com/assets/script/canvasjs.min.js">
         </script>
<script>
    window.onload = function () {


        var chart = new CanvasJS.Chart("chartContainer5", {
            theme: "light1", // "light2", "dark1", "dark2"
            animationEnabled: false, // change to true
            title:{
                text: ""
            },
            data: [
                {
                    // Change type to "bar", "area", "spline", "pie",etc.
                    type: "column",
                    dataPoints: [
                        { label: "Hotel Makkah",  y: {{$total_bookings['hotel_makkah']}} },
                        { label: "Hotel Madina", y: {{$total_bookings['hotel_madina']}}  },
                        { label: "Transfers", y: {{$total_bookings['transfer']}} }


                    ]
                }
            ]
        });
        chart.render();
        var chart = new CanvasJS.Chart("chartContainer6", {
            theme: "light2", // "light2", "dark1", "dark2"
            animationEnabled: false, // change to true
            title:{
                text: ""
            },
            data: [
                {
                    // Change type to "bar", "area", "spline", "pie",etc.
                    type: "column",
                    dataPoints: [
                        { label: "Hotel Makkah",  y: {{$total_bookings_this_monthb2b['hotel_makkah']}} },
                        { label: "Hotel Madina", y: {{$total_bookings_this_monthb2b['hotel_madina']}}  },
                        { label: "Transfers", y: {{$total_bookings_this_monthb2b['transfer']}} }



                    ]
                }
            ]
        });
        chart.render();


    }

</script>
<script>
    $(document).ready(function() {
        $('.dt').DataTable( {
            "order": [[ 0, 'desc' ], [ 1, 'desc' ]],
            "pageLength": 5,
        });
        $('.get_list').on('click',function(){
            var category = $(this).attr('data-category_type');
            var range = $(this).attr('data-range');
            $('#category_input').val(category);
            $('#range_input').val(range);
            $('#revenue_form').submit();          
            // $.ajax({
            //     headers: {
            //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //     },
            //     url:"{{ url('super_admin/get_revenue_list') }}",
            //     method:"POST",
            //     data:{
            //         "category" : category,
            //         "range" : range 
            //     },
            //     success:function(ajax_responce){
            //         console.log(ajax_responce);
            //         // if(ajax_responce){
            //         //     location.reload();
            //         // }
            //     }
            // });
        });
    });
</script>



     <!-- Resources -->
     <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
     <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
     <script src="https://cdn.amcharts.com/lib/4/maps.js"></script>
     <script src="https://cdn.amcharts.com/lib/4/geodata/worldLow.js"></script>
     <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>

     <!-- Chart code -->
     <script>


         am4core.ready(function() {

// Themes begin
             am4core.useTheme(am4themes_animated);
// Themes end

// Create map instance
             var chart = am4core.create("chartdiv", am4maps.MapChart);

// Set map definition
             chart.geodata = am4geodata_worldLow;

// Set projection
             chart.projection = new am4maps.projections.Miller();

// Create map polygon series
             var polygonSeries = chart.series.push(new am4maps.MapPolygonSeries());

// Exclude Antartica
             polygonSeries.exclude = ["AQ"];

// Make map load polygon (like country names) data from GeoJSON
             polygonSeries.useGeodata = true;

// Configure series
             var polygonTemplate = polygonSeries.mapPolygons.template;
             polygonTemplate.tooltipText = "{name}";
             polygonTemplate.polygon.fillOpacity = 0.6;


// Create hover state and set alternative fill color
             var hs = polygonTemplate.states.create("hover");
             hs.properties.fill = chart.colors.getIndex(0);

// Add image series
             var imageSeries = chart.series.push(new am4maps.MapImageSeries());
             imageSeries.mapImages.template.propertyFields.longitude = "longitude";
             imageSeries.mapImages.template.propertyFields.latitude = "latitude";
             imageSeries.mapImages.template.tooltipText = "{title}";
             imageSeries.mapImages.template.propertyFields.url = "url";

             var circle = imageSeries.mapImages.template.createChild(am4core.Circle);
             circle.radius = 3;
             circle.propertyFields.fill = "color";
             circle.nonScaling = true;

             var circle2 = imageSeries.mapImages.template.createChild(am4core.Circle);
             circle2.radius = 3;
             circle2.propertyFields.fill = "color";


             circle2.events.on("inited", function(event){
                 animateBullet(event.target);
             })


             function animateBullet(circle) {
                 var animation = circle.animate([{ property: "scale", from: 1 / chart.zoomLevel, to: 5 / chart.zoomLevel }, { property: "opacity", from: 1, to: 0 }], 1000, am4core.ease.circleOut);
                 animation.events.on("animationended", function(event){
                     animateBullet(event.target.object);
                 })
             }
             var colorSet = new am4core.ColorSet();
             var countries_list= $('#countries_list').val();
            var list_of_c = new Array();
             countries_list = JSON.parse(countries_list);

             // console.log(countries_list);
             imageSeries.data = [];
             countries_list.map((c) => {
                 console.log(c);
                 let data = {
                     "title": c.data.name + " = Total Agents " +  c.data.first_name,
                   
                     "latitude": parseFloat(c.data.latitude),
                     "longitude": parseFloat(c.data.longitude),
                     "color":colorSet.next()
                 }
                 imageSeries.data.push(data);
             });
            console.log(imageSeries.data);



         }); // end am4core.ready

     </script>


     <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
       <script>
         am4core.ready(function() {

// Themes begin
             am4core.useTheme(am4themes_animated);
// Themes end

             var chart = am4core.create("chart_div", am4charts.PieChart3D);
             chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

             chart.legend = new am4charts.Legend();

             chart.data = [
                 {
                     country: "Hotel Makkha",
                     litres: {{$total_bookingsb2c['hotel_makkah']}}
                 },
                 {
                     country: "Hotel Madina",
                     litres: {{$total_bookingsb2c['hotel_madina']}}
                 },
                 {
                     country: "Transfers",
                     litres: {{$total_bookingsb2c['transfer']}}
                 },
                 {
                     country: "Ground Services",
                     litres: {{$total_bookingsb2c['ground_services']}}
                 },
             ];

             // Add and configure Series
             var pieSeries = chart.series.push(new am4charts.PieSeries());
             pieSeries.dataFields.value = "litres";
             pieSeries.dataFields.category = "country";
             pieSeries.slices.template.stroke = am4core.color("#fff");
             pieSeries.slices.template.strokeOpacity = 1;

// This creates initial animation
             pieSeries.hiddenState.properties.opacity = 1;
             pieSeries.hiddenState.properties.endAngle = -90;
             pieSeries.hiddenState.properties.startAngle = -90;
             chart.legend.valueLabels.template.disabled = true;

             pieSeries.labels.template.text = "{category}: {value.value}";
             pieSeries.legendSettings.labelText = "{category}:{value.value}";
             pieSeries.slices.template.tooltipText = "{category}: {value}";
             chart.hiddenState.properties.radius = am4core.percent(0);


         }); // end am4core.ready()

     </script>

     <!-- Chart code -->
     <script>
         am4core.ready(function() {

// Themes begin
             am4core.useTheme(am4themes_animated);
// Themes end

             var chart = am4core.create("chartContainer2", am4charts.PieChart3D);
             chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

             chart.legend = new am4charts.Legend();

             chart.data = [
                 {
                     country: "Hotel Makkha",
                     litres: {{$total_bookings_this_monthb2c['hotel_makkah']}}
                 },
                 {
                     country: "Hotel Madina",
                     litres: {{$total_bookings_this_monthb2c['hotel_madina']}}
                 },
                 {
                     country: "Transfers",
                     litres: {{$total_bookings_this_monthb2c['transfer']}}
                 },
                 {
                     country: "Ground Services",
                     litres: {{$total_bookings_this_monthb2c['ground_services']}}
                 },
             ];

             // Add and configure Series
             var pieSeries = chart.series.push(new am4charts.PieSeries());
             pieSeries.dataFields.value = "litres";
             pieSeries.dataFields.category = "country";
             pieSeries.slices.template.stroke = am4core.color("#fff");
             pieSeries.slices.template.strokeOpacity = 1;

// This creates initial animation
             pieSeries.hiddenState.properties.opacity = 1;
             pieSeries.hiddenState.properties.endAngle = -90;
             pieSeries.hiddenState.properties.startAngle = -90;
             chart.legend.valueLabels.template.disabled = true;

             pieSeries.labels.template.text = "{category}: {value.value}";
             pieSeries.legendSettings.labelText = "{category}:{value.value}";
             pieSeries.slices.template.tooltipText = "{category}: {value}";
             chart.hiddenState.properties.radius = am4core.percent(0);


         }); // end am4core.ready()





     </script>
     <!-- Chart code -->

     <!-- Chart code -->
     <script>

         am4core.ready(function() {

// Themes begin
             am4core.useTheme(am4themes_animated);
// Themes end

             var chart = am4core.create("chartContainer3", am4charts.PieChart3D);
             chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

             chart.legend = new am4charts.Legend();

             chart.data = [
                 {
                     country: "Hotel Makkha",
                     litres: {{$total_bookings_this_weekb2c['hotel_makkah']}}
                 },
                 {
                     country: "Hotel Madina",
                     litres: {{$total_bookings_this_weekb2c['hotel_madina']}}
                 },
                 {
                     country: "Transfers",
                     litres: {{$total_bookings_this_weekb2c['transfer']}}
                 },
                 {
                     country: "Ground Services",
                     litres: {{$total_bookings_this_weekb2c['ground_services']}}
                 },
             ];

             // Add and configure Series
             var pieSeries = chart.series.push(new am4charts.PieSeries());
             pieSeries.dataFields.value = "litres";
             pieSeries.dataFields.category = "country";
             pieSeries.slices.template.stroke = am4core.color("#fff");
             pieSeries.slices.template.strokeOpacity = 1;

// This creates initial animation
             pieSeries.hiddenState.properties.opacity = 1;
             pieSeries.hiddenState.properties.endAngle = -90;
             pieSeries.hiddenState.properties.startAngle = -90;
             chart.legend.valueLabels.template.disabled = true;

             pieSeries.labels.template.text = "{category}: {value.value}";
             pieSeries.legendSettings.labelText = "{category}:{value.value}";
             pieSeries.slices.template.tooltipText = "{category}: {value}";
             chart.hiddenState.properties.radius = am4core.percent(0);


         }); // end am4core.ready()




     </script>
     <!-- Chart code -->
     <!-- Chart code -->
     <script>

         am4core.ready(function() {

// Themes begin
             am4core.useTheme(am4themes_animated);
// Themes end

             var chart = am4core.create("chartContainer4", am4charts.PieChart3D);
             chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

             chart.legend = new am4charts.Legend();

             chart.data = [
                 {
                     country: "Hotel Makkha",
                     litres: {{$total_bookings_todayb2c['hotel_makkah']}}
                 },
                 {
                     country: "Hotel Madina",
                     litres: {{$total_bookings_todayb2c['hotel_madina']}}
                 },
                 {
                     country: "Transfers",
                     litres:  {{$total_bookings_todayb2c['transfer']}}
                 },
                 {
                     country: "Ground Services",
                     litres: {{$total_bookings_todayb2c['ground_services']}}
                 },
             ];

             // Add and configure Series
             var pieSeries = chart.series.push(new am4charts.PieSeries());
             pieSeries.dataFields.value = "litres";
             pieSeries.dataFields.category = "country";
             pieSeries.slices.template.stroke = am4core.color("#fff");
             pieSeries.slices.template.strokeOpacity = 1;

// This creates initial animation
             pieSeries.hiddenState.properties.opacity = 1;
             pieSeries.hiddenState.properties.endAngle = -90;
             pieSeries.hiddenState.properties.startAngle = -90;
             chart.legend.valueLabels.template.disabled = true;

             pieSeries.labels.template.text = "{category}: {value.value}";
             pieSeries.legendSettings.labelText = "{category}:{value.value}";
             pieSeries.slices.template.tooltipText = "{category}: {value}";
             chart.hiddenState.properties.radius = am4core.percent(0);


         }); // end am4core.ready()



     </script>
     <!-- Chart code -->


    <!-- Chart code -->
     <script>
         am4core.ready(function() {

// Themes begin
             am4core.useTheme(am4themes_animated);
// Themes end

             var chart = am4core.create("chart_div_b2b", am4charts.PieChart3D);
             chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

             chart.data = [
                 {
                     country: "Hotel Makkah",
                     litres: {{$total_bookings['hotel_makkah']}}
                 },
                 {
                     country: "Hotel Madina",
                     litres: {{$total_bookings['hotel_madina']}}
                 },
                 {
                     country: "Transfer",
                     litres: {{$total_bookings['transfer']}}
                 }

             ];

             chart.innerRadius = am4core.percent(40);
             chart.depth = 120;

             chart.legend = new am4charts.Legend();

             var series = chart.series.push(new am4charts.PieSeries3D());
             series.dataFields.value = "litres";
             series.dataFields.depthValue = "litres";
             series.dataFields.category = "country";
             series.slices.template.cornerRadius = 5;
             series.colors.step = 3;
             chart.legend.valueLabels.template.disabled = true;
             series.labels.template.text = "{category}: {value.value}";
             series.legendSettings.labelText = "{category}:{value.value}";
             series.slices.template.tooltipText = "{category}: {value}";


         }); // end am4core.ready()
     </script>



     <script>
         am4core.ready(function() {

// Themes begin
             am4core.useTheme(am4themes_animated);
// Themes end

             var chart = am4core.create("chartContainer6", am4charts.PieChart3D);
             chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

             chart.data = [
                 {
                     country: "Hotel Makkah",
                     litres: {{$total_bookings_this_monthb2b['hotel_makkah']}}
                 },
                 {
                     country: "Hotel Madina",
                     litres: {{$total_bookings_this_monthb2b['hotel_madina']}}
                 },
                 {
                     country: "Transfer",
                     litres: {{$total_bookings_this_monthb2b['transfer']}}
                 }

             ];

             chart.innerRadius = am4core.percent(40);
             chart.depth = 120;

             chart.legend = new am4charts.Legend();

             var series = chart.series.push(new am4charts.PieSeries3D());
             series.dataFields.value = "litres";
             series.dataFields.depthValue = "litres";
             series.dataFields.category = "country";
             series.slices.template.cornerRadius = 5;
             series.colors.step = 3;
             chart.legend.valueLabels.template.disabled = true;
             series.labels.template.text = "{category}: {value.value}";
             series.legendSettings.labelText = "{category}:{value.value}";
             series.slices.template.tooltipText = "{category}: {value}";

         }); // end am4core.ready()






     </script>

     <script>

         am4core.ready(function() {

// Themes begin
             am4core.useTheme(am4themes_animated);
// Themes end

             var chart = am4core.create("chartContainer7", am4charts.PieChart3D);
             chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

             chart.data = [
                 {
                     country: "Hotel Makkah",
                     litres: {{$total_bookings_this_week['hotel_makkah']}}
                 },
                 {
                     country: "Hotel Madina",
                     litres: {{$total_bookings_this_week['hotel_madina']}}
                 },
                 {
                     country: "Transfer",
                     litres: {{$total_bookings_this_week['transfer']}}
                 }

             ];

             chart.innerRadius = am4core.percent(40);
             chart.depth = 120;

             chart.legend = new am4charts.Legend();

             var series = chart.series.push(new am4charts.PieSeries3D());
             series.dataFields.value = "litres";
             series.dataFields.depthValue = "litres";
             series.dataFields.category = "country";
             series.slices.template.cornerRadius = 5;
             series.colors.step = 3;
             chart.legend.valueLabels.template.disabled = true;
             series.labels.template.text = "{category}: {value.value}";
             series.legendSettings.labelText = "{category}:{value.value}";
             series.slices.template.tooltipText = "{category}: {value}";

         }); // end am4core.ready()

     </script>


     <script>

         am4core.ready(function() {

// Themes begin
             am4core.useTheme(am4themes_animated);
// Themes end

             var chart = am4core.create("chartContainer8", am4charts.PieChart3D);
             chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

             chart.data = [
                 {
                     country: "Hotel Makkah",
                     litres: {{$total_bookings_today['hotel_makkah']}}
                 },
                 {
                     country: "Hotel Madina",
                     litres: {{$total_bookings_today['hotel_madina']}}
                 },
                 {
                     country: "Transfer",
                     litres: {{$total_bookings_today['transfer']}}
                 }

             ];

             chart.innerRadius = am4core.percent(40);
             chart.depth = 120;

             chart.legend = new am4charts.Legend();

             var series = chart.series.push(new am4charts.PieSeries3D());
             series.dataFields.value = "litres";
             series.dataFields.depthValue = "litres";
             series.dataFields.category = "country";
             series.slices.template.cornerRadius = 5;
             series.colors.step = 3;
             chart.legend.valueLabels.template.disabled = true;
             series.labels.template.text = "{category}: {value.value}";
             series.legendSettings.labelText = "{category}:{value.value}";
             series.slices.template.tooltipText = "{category}: {value}";

         }); // end am4core.ready()







     </script>
 @stop

