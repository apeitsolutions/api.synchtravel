@extends('template/frontend/userdashboard/layout/default')
@section('content')


        <div class="p-5" id="header-footer-modal">

            <div class="preview">
                <div class="modal" id="header-footer-modal-preview">

                    <div class="modal__content">
                        <div class="flex items-center px-5 py-5 sm:py-3 border-b border-gray-200">
                            <h2 class="font-medium text-base mr-auto">
                             Detail
                            </h2>
                        </div>
                        <form action="" method="post" id="myform">
                            @csrf
                           <input type="hidden" name="" id="txt_id">
                        <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                            <div class="col-span-12 sm:col-span-6">
                                <label>Lead Passenger Name</label>
                                <input type="text" name="lead_p_name" class="input w-full border mt-2 flex-1" value="{{$book->lead_passenger_name}}" placeholder="Lead Pessanger Name">
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <label>Email</label>
                                <input type="email" name="email" class="input w-full border mt-2 flex-1" value="{{$book->lead_passenger_email}}" placeholder="Enter Email">
                            </div>
                            <div class="col-span-12 sm:col-span-12">
                                <label>Contact</label>
                                <input type="text" name="contact" class="input w-full border mt-2 flex-1" value="{{$book->lead_passenger_contact}}" placeholder="Contact">
                            </div>
                            <div class="col-span-12 sm:col-span-12">
                                <label>Note</label>
                               <textarea class="input w-full border mt-2 flex-1" name="note" ></textarea>
                            </div>
                        <div class="px-5 py-3 text-right border-t border-gray-200">
                            <button type="submit" name="submit" class="button w-20 bg-theme-1 text-white">Appoint</button>
                        </div>
                        </form>
                    </div>

                </div>
            </div>

        </div>





    <div class="grid grid-cols-12 gap-6 mt-8">
        <div class="col-span-12 lg:col-span-6 xxl:col-span-6">
            <!-- BEGIN: Content -->
            <div class="content">
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="intro-y col-span-12 lg:col-span-12">
                        <!-- BEGIN: Vertical Form -->
                        <div class="intro-y box">
                            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200" style="background: #50a69c;padding: 32p; color: white;">
                                <h2 class="font-medium text-base mr-auto">
                                    Current location
                                </h2>
                            </div>
                            <div id="mymap" style="height: 700px;"></div>
                        </div>
                        <!-- END: Vertical Form -->
                    </div>
                </div>
            </div>
            <!-- END: Content -->
        </div>
        <div class="col-span-12 lg:col-span-6 xxl:col-span-6">
            <div class="pos">
                <!-- BEGIN: Ticket -->
                <div class="col-span-12 lg:col-span-4">
                    <div class="intro-y pr-1">
                        <div class="box p-2">
                            <div class="pos__tabs nav-tabs justify-center flex">
                                <a data-toggle="tab" data-target="#Jeddah" href="javascript:;" class="flex-1 py-2 rounded-md text-center active">
                                    Jeddah</a>
                                <a data-toggle="tab" data-target="#Makkah" href="javascript:;" class="flex-1 py-2 rounded-md text-center">
                                    Makkah</a>
                                <a data-toggle="tab" data-target="#Madina" href="javascript:;" class="flex-1 py-2 rounded-md text-center">
                                    Madina</a> </div>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="tab-content__pane active" id="Jeddah">
                            <div class="col-span-12 sm:col-span-6 xxl:col-span-3 intro-y">
                                <div class="col-span-12 lg:col-span-6 xxl:col-span-6">
                                    <div class="intro-y box mt-5" style="" >
                                        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200" style="background: #50a69c;color: white;">
                                            <h2 class="font-medium text-base mr-auto">
                                                Jeddah Mandoob List
                                            </h2>
                                        </div>
                                        <div class="p-5">
                                            <div class="preview">
                                                <div class="overflow-x-auto">
                                                    <table class="table" id="example_2">
                                                        <thead>
                                                        <tr>
                                                            <th class="border-b-2 whitespace-no-wrap">#id</th>
                                                            <th class="border-b-2 whitespace-no-wrap">Employee Name</th>
                                                            <th class="border-b-2 whitespace-no-wrap">Status</th>
                                                            <th class="border-b-2 whitespace-no-wrap">details</th>
                                                        </tr>
                                                        </thead>

                                                        <tbody>
                                                        @foreach($employees_1 as $employees_1)
                                                           <?php
                                                           if($employees_1->position == 'Jeddah')
                                                               {
                                                           ?>
                                                            <tr>
                                                                <td class="border-b whitespace-no-wrap">{{$employees_1->id}}</td>
                                                                <td class="border-b whitespace-no-wrap">{{$employees_1->first_name}} {{$employees_1->last_name}}</td>
                                                                <td class="border-b whitespace-no-wrap">
                                                                    <?php
                                                                    if($employees_1->employee_status == 'free')
                                                                    {
                                                                    ?>
                                                                    <div class="button w-30 inline-block mr-1 mb-2 bg-theme-12 text-white">free</div>
                                                                    <?php
                                                                    }
                                                                    elseif($employees_1->employee_status == 'busy')
                                                                    {
                                                                    ?>
                                                                    <div class="button w-30 inline-block mr-1 mb-2 bg-theme-6 text-white">busy</div>
                                                                    <?php
                                                                    }

                                                                    ?>

                                                                </td>
<td class="border-b whitespace-no-wrap">
    <?php
    if($employees_1->employee_status == 'free')
    {
    ?>
        <div class="text-center">
            <a href="javascript:;" class="button inline-block bg-theme-1 text-white openModal" data-id="{{$employees_1->id}}">
                Appiont
            </a>
        </div>
    <?php
    }
    elseif($employees_1->employee_status == 'busy')
    {
    ?>

    <?php
    }

    ?>

   </td>
                                                            </tr>
                                                        <?php } ?>
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
                        <div class="tab-content__pane" id="Makkah">
                            <div class="col-span-12 sm:col-span-6 xxl:col-span-3 intro-y">
                                <div class="col-span-12 lg:col-span-6 xxl:col-span-6">
                                    <div class="intro-y box mt-5" style="" >
                                        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200" style="background: #50a69c;color: white;">
                                            <h2 class="font-medium text-base mr-auto">
                                               Makkah Mandoob List
                                            </h2>
                                        </div>
                                        <div class="p-5">
                                            <div class="preview">
                                                <div class="overflow-x-auto">
                                                    <table class="table" id="example_3">
                                                        <thead>
                                                        <tr>
                                                            <th class="border-b-2 whitespace-no-wrap">#id</th>
                                                            <th class="border-b-2 whitespace-no-wrap">Employee Name</th>
                                                            <th class="border-b-2 whitespace-no-wrap">Status</th>
                                                            <th class="border-b-2 whitespace-no-wrap">details</th>
                                                        </tr>
                                                        </thead>

                                                        <tbody>
                                                        @foreach($employees_2 as $employees_2)

                                                           @if($employees_2->position == 'Makkah')

                                                            <tr>
                                                                <td class="border-b whitespace-no-wrap">{{$employees_2->id}}</td>
                                                                <td class="border-b whitespace-no-wrap">{{$employees_2->first_name}} {{$employees_2->last_name}}</td>
                                                                <td class="border-b whitespace-no-wrap">
                                                                    <?php
                                                                    if($employees_2->employee_status == 'free')
                                                                    {
                                                                    ?>
                                                                    <div class="button w-30 inline-block mr-1 mb-2 bg-theme-12 text-white">free</div>
                                                                    <?php
                                                                    }
                                                                    elseif($employees_2->employee_status == 'busy')
                                                                    {
                                                                    ?>
                                                                    <div class="button w-30 inline-block mr-1 mb-2 bg-theme-6 text-white">busy</div>
                                                                    <?php
                                                                    }

                                                                    ?>

                                                                </td>
                                                                <td class="border-b whitespace-no-wrap">
                                                                    <?php
                                                                    if($employees_2->employee_status == 'free')
                                                                    {
                                                                    ?>
                                                                        <div class="text-center">
                                                                            <a href="javascript:;" class="button inline-block bg-theme-1 text-white openModal" data-id="{{$employees_2->id}}">
                                                                                Appiont
                                                                            </a>
                                                                        </div>
                                                                    <?php
                                                                    }
                                                                    elseif($employees_2->employee_status == 'busy')
                                                                    {
                                                                    ?>

                                                                    <?php
                                                                    }

                                                                    ?>


   </td>

                                                            </tr>
                                                           @endif
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
                        <div class="tab-content__pane" id="Madina">
                            <div class="col-span-12 sm:col-span-6 xxl:col-span-3 intro-y">
                                <div class="col-span-12 lg:col-span-6 xxl:col-span-6">
                                    <div class="intro-y box mt-5" style="">
                                        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200" style="background: #50a69c;color: white;">
                                            <h2 class="font-medium text-base mr-auto">
                                               Madina Mandoob List
                                            </h2>
                                        </div>
                                        <div class="p-5">
                                            <div class="preview">
                                                <div class="overflow-x-auto">
                                                    <table class="table" id="example_4">
                                                        <thead>
                                                        <tr>
                                                            <th class="border-b-2 whitespace-no-wrap">#id</th>
                                                            <th class="border-b-2 whitespace-no-wrap">Employee Name</th>
                                                            <th class="border-b-2 whitespace-no-wrap">Status</th>
                                                            <th class="border-b-2 whitespace-no-wrap">details</th>
                                                        </tr>
                                                        </thead>

                                                        <tbody>
                                                        @foreach($employees_3 as $employees_3)

                                                            @if($employees_3->position == 'Madina')


                                                            <tr>
                                                                <td class="border-b whitespace-no-wrap">{{$employees_3->id}}</td>
                                                                <td class="border-b whitespace-no-wrap">{{$employees_3->first_name}} {{$employees_3->last_name}}</td>
                                                                <td class="border-b whitespace-no-wrap">
                                                                    <?php
                                                                    if($employees_3->employee_status == 'free')
                                                                    {
                                                                    ?>
                                                                    <div class="button w-30 inline-block mr-1 mb-2 bg-theme-12 text-white">free</div>
                                                                    <?php
                                                                    }
                                                                    elseif($employees_3->employee_status == 'busy')
                                                                    {
                                                                    ?>
                                                                    <div class="button w-30 inline-block mr-1 mb-2 bg-theme-6 text-white">busy</div>
                                                                    <?php
                                                                    }

                                                                    ?>

                                                                </td>
                                                               <td class="border-b whitespace-no-wrap">
                                                                   <?php
                                                                   if($employees_3->employee_status == 'free')
                                                                   {
                                                                   ?>
                                                                       <div class="text-center">
                                                                           <a href="javascript:;" class="button inline-block bg-theme-1 text-white openModal" data-id="{{$employees_3->id}}">
                                                                               Appiont
                                                                           </a>
                                                                       </div>
                                                                   <?php
                                                                   }
                                                                   elseif($employees_3->employee_status == 'busy')
                                                                   {
                                                                   ?>

                                                                   <?php
                                                                   }

                                                                   ?>

   </td>
                                                            </tr>

                                                            @endif
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
                </div>
                <!-- END: Ticket -->

            </div>
        </div>

    </div>




@endsection
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>

 <script type="text/javascript">
    $(document).ready(function(){
            $('.openModal').click(function(){
    var id = $(this).attr('data-id');
    // alert(id);
    $('#txt_id').val(id);
    $("#myform").attr('action', '{{URL::to("super_admin/appoint/")}}' + '/' + id);
    $('#header-footer-modal-preview').modal('toggle');
});

            });
        </script>

<!-- {{--<script async defer--}}
{{--        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCMzQbRDFJXDl-8PLZnbeBcSn-7zzMM4-4&callback=initMap">--}}
{{--</script>--}} -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maps.google.com/maps/api/js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.24/gmaps.js"></script>

       
        <script type="text/javascript">



            $(document).ready(function(){
                var locations = <?php print_r(json_encode($get_address)) ?>;
                console.log(locations);
                var element =  document.getElementById('mymap');
                if (typeof(element) != 'undefined' && element != null)
                {


            var mymap = new GMaps({
                el: '#mymap',
                lat: 21.170240,
                lng: 72.831061,
                zoom:5
            });


            $.each( locations, function( index, value ){
                mymap.addMarker({
                    lat:value.loc_latitude,
                    lng:value.loc_longitude,
                    title:value.employee_email,
                    click: function(e) {
                        alert('This is '+value.employee_email+', pk.');
                    }
                });
            });



                }
            });
        </script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {

    $('#example_4').DataTable();
    });
    $(document).ready(function() {
        $('#example_1').DataTable();
    });
    $(document).ready(function() {

        $('#example_2').DataTable();

    });
    $(document).ready(function() {

        $('#example_3').DataTable();


    });



</script>

