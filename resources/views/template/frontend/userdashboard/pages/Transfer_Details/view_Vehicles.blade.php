@extends('template/frontend/userdashboard/layout/default')
@section('content')


<div class="dashboard-content">
    <h4 style="color:#a30000">Vehicle List</h4>
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
                        <th>Customer Id</th>
                        <th>Vehicle Name</th>
                        <th>Vehicle Type</th>
                        <th>Vehicle Passeneger</th>
                        <th>Vehicle Baggage</th>
                        <th>Vehicle Status</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $x = 1;
                    @endphp
                    @foreach($data as $value)
                        @foreach($value as $value1)
                            <tr>
                                <td>{{ $x++ }}</td>
                                <td><img src="{{ asset('public/uploads/package_imgs/') }}/{{ $value1->vehicle_image }}" alt="" style="height: 80px;width: 100px;"></td>
                                <td>{{ $value1->customer_id }}</td>
                                <td>{{ $value1->vehicle_Name }}</td>
                                <td>{{ $value1->vehicle_Type }}</td>
                                <td>{{ $value1->vehicle_Passenger }}</td>
                                <td>{{ $value1->vehicle_Baggage }}</td>
                                <td>{{ $value1->vehicle_Status }}</td>
                                
                                <td>
                                    <a href="{{ URL::to('edit_vehicle_details/'.$value1->id.'') }}" class="btn btn-secondary btn-sm">Edit Details</a>
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
