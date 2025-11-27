@extends('template/frontend/userdashboard/layout/default')
 @section('content')
 


 
                                <div class="card mt-5">
                                    <div class="card-body">

                                        <h4 class="header-title mb-3">Edit Categories</h4>

                                        <form action="{{URL::to('super_admin/submit_edit_categories_activities',[$categories->id])}}" method="post" enctype="multipart/form-data">

                                            @csrf
<div class="row">
<div class="col-xl-4">
 <div class="mb-3">
    <label for="simpleinput" class="form-label">Title</label>
    <input type="text" id="simpleinput" name="title" class="form-control" value="{{$categories->title}}">
</div>
</div>
<div class="col-xl-4">
 <div class="mb-3">
    <label for="simpleinput" class="form-label">Slug</label>
    <input type="text" id="simpleinput" name="slug" class="form-control" value="{{$categories->slug}}">
</div>
</div>
<div class="col-xl-4">
 <div class="mb-3">
    <label for="simpleinput" class="form-label">image</label>
    <input type="file" id="simpleinput" name="image" class="form-control">
</div>
</div>
<div class="col-xl-12">
 <div class="mb-3">
    <label for="simpleinput" class="form-label">Description</label>
    <textarea name="description"  cols="10" rows="10" class="form-control">{{$categories->description}}</textarea>
   
</div>
</div>
</div>
 <div class="mb-3" style="margin-top: 30px;">
    <button class="btn btn-info" type="submit" name="submit">Submit</button>

</div>
</div>
</div>

@endsection