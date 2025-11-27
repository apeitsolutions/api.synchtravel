@extends('template/frontend/userdashboard/layout/default')
@section('content')


<div class="dashboard-content">
    <h4 style="color:#a30000">Rooms List</h4>
    <div class="row">
<div class="col-lg-12 col-sm-12">
<div class="dashboard-list-box dash-list margin-top-0">
    <div class="row">
        <div class="col-md-12">
             <table id="myTable" class="display nowrap table  table-bordered" style="width:100%">
                <thead style="background-color:#fe4e37;color:white;">
                    <tr>
                        <th>Sr</th>
                        <th>Image</th>
                        <th>Hotel</th>
                        <th>Type</th>
                        <th>Availabile (From)</th>
                        <th>Availabile (To)</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $x = 1;
                    @endphp
                    @foreach($user_rooms as $room_res)
                    <tr>
                        <td>{{ $x++ }}</td>
                        <td><img src="{{ asset('public/images/rooms/') }}/{{ $room_res['room_img'] }}" alt=""></td>
                        <td>{{ $room_res['property_name'] }}</td>
                        <td>{{ $room_res['room_type'] }}</td>
                        <td>{{ $room_res['availible_from'] }}</td>
                        <td>{{ $room_res['availible_to'] }}</td>
                        <td>{{ $room_res['quantity'] }}</td>
                        <td>{{ $room_res['status'] }}</td>
                        
                        <td>
                            <a href="{{ URL::to('hotel_manger/view_room/'.$room_res['id'].'') }}" class="btn btn-secondary btn-sm">View Details</a>
                        </td>
                    </tr>
                    @endforeach
                
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
