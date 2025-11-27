@extends('template/frontend/userdashboard/layout/default')
 @section('content')
 


 
                                <div class="card mt-5">
                                    <div class="card-body">

                                        <h4 class="header-title mb-3">Edit Categories</h4>

                                        <form action="{{URL::to('super_admin/submit_edit_categories',[$categories->id])}}" method="post" enctype="multipart/form-data">

                                            @csrf
<div class="row">
<div class="col-xl-6">
 <div class="mb-3">
    <label for="simpleinput" class="form-label">Name</label>
    <input type="text" id="simpleinput" name="title" value="{{$categories->title}}" class="form-control">
</div>
</div>
<div class="col-xl-6">
 <div class="mb-3" style="margin-top: 30px;">
    <button class="btn btn-info" type="submit" name="submit">Submit</button>
</div>
</div>
</div>
</div>
</div>

@endsection