@extends('template/frontend/userdashboard/layout/default')
 @section('content')
 


 
                                <div class="card mt-5">
                                    <div class="card-body">

                                        <h4 class="header-title mb-3">Edit Hotel Names</h4>

                                        <form action="{{URL::to('super_admin/submit_edit_Hotel_Names',[$edit_other_Hotel_Name->id])}}" method="post" enctype="multipart/form-data">

                                            @csrf
<div class="row">


<div class="col-xl-12">
 <div class="mb-3">
    <label for="simpleinput" class="form-label">Hotel Name</label>
    <input name="other_Hotel_Name" value="{{$edit_other_Hotel_Name->other_Hotel_Name}}"  cols="10" rows="10" class="form-control"></input>
   
</div>
</div>
</div>
 <div class="mb-3" style="margin-top: 30px;">
    <button class="btn btn-info" type="submit" name="submit">Submit</button>

</div>
</div>
</div>

@endsection