@extends('template/frontend/userdashboard/layout/default')
 @section('content')
 


 
                                <div class="card mt-5">
                                    <div class="card-body">

                                        <h4 class="header-title mb-3">Edit Pickup Location</h4>

                                        <form action="{{URL::to('super_admin/submit_edit_pickup_locations',[$edit_other_pickup_location->id])}}" method="post" enctype="multipart/form-data">

                                            @csrf
<div class="row">


<div class="col-xl-12">
 <div class="mb-3">
    <label for="simpleinput" class="form-label">Pickup Location Name</label>
    <input name="pickup_location" value="{{$edit_other_pickup_location->pickup_location}}"  cols="10" rows="10" class="form-control"></input>
   
</div>
</div>
</div>
 <div class="mb-3" style="margin-top: 30px;">
    <button class="btn btn-info" type="submit" name="submit">Submit</button>

</div>
</div>
</div>

@endsection