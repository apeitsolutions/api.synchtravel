@extends('template/frontend/userdashboard/layout/default')
@section('content')

    <div class="dashboard-content">
        <h4>Rooms List</h4>
        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <div class="dashboard-list-box dash-list margin-top-0">
                    <div class="row">
                        <div class="col-md-12">
                            <table id="myTable" class="display nowrap table  table-bordered" style="width:100%">
                                <thead class="theme-bg-clr">
                                    <tr>
                                        <th>Sr</th>
                                        <th>Customer Name</th>
                                        <th>Image</th>
                                        <th>Hotel</th>
                                        <th>Type</th>
                                        <th>Availabile (From)</th>
                                        <th>Availabile (To)</th>
                                        <th>Quantity</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $x = 1;
                                    @endphp
                                    @foreach($user_rooms as $room_res)
                                        <?php
                                            if($room_res->more_room_type_details != null){
                                                $more_room_type_details = $room_res->more_room_type_details;
                                                $more_room_type_details = json_decode($more_room_type_details);
                                            }
                                        ?>
                                        @if(isset($more_room_type_details))
                                            @foreach($more_room_type_details as $more_room_res)
                                            @endforeach
                                        @endif
                                        <tr>
                                            <td>{{ $x++ }}</td>
                                            <td>
                                                @if(isset($all_Users) && $all_Users != null && $all_Users != '')
                                                    @foreach($all_Users as $all_Users_value)
                                                        @if($room_res->owner_id == $all_Users_value->id)
                                                            <b>{{ $all_Users_value->name }}</b>
                                                            <?php
                                                                $webiste_Address    = $all_Users_value->webiste_Address;
                                                                $url                = $webiste_Address.'/public/images/rooms/roomGallery'; 
                                                            ?>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($room_res->room_img ) && $room_res->room_img  != null && $room_res->room_img  != '' && $webiste_Address != null)
                                                    <img src="{{ $url }}/{{ $room_res->room_img  }}" style="height: 50px;width: 50px;">
                                                @else
                                                    <img src="{{ asset('public/admin_package/frontend/images/detail_img/no-photo-available-icon-4.jpg') }}" style="height: 50px;width: 50px;">
                                                @endif
                                            </td>
                                            <td>{{ $room_res->property_name }}</td>
                                            <td>{{ $room_res->room_type }}<br>{{ $more_room_res->more_room_type ?? ''}}</br></td>
                                            <td>{{ date("d-m-Y", strtotime($room_res->availible_from)) }}<br>{{ $more_room_res->more_room_av_from ?? '' }}<br></td>
                                            <td>{{ date("d-m-Y", strtotime($room_res->availible_to)) }}<br>{{ $more_room_res->more_room_av_to ?? ''}}<br></td>
                                            <td>{{ $room_res->quantity }}<br>{{ $more_room_res->more_quantity ?? ''}}</br></td>
                                            <td>{{ $room_res->status }}</td>
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
    
@endsection

@section('scripts')

    <script>
        $(document).ready( function () {
            $('#myTable').DataTable({
                "scrollX": true,
            });
        } );
    </script>

@stop
