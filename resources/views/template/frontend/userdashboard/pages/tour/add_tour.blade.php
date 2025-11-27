@extends('template/frontend/userdashboard/layout/default')
@section('content')
 


 
<div class="card mt-5">

<form action="{{URL::to('super_admin/submit_tour')}}" method="post" enctype="multipart/form-data">
@csrf
<div class="card-body">

 <h4 class="header-title mb-3">Add Tour</h4>

<div class="row">
<div class="col-xl-12">
 <div class="mb-3">
    <label for="simpleinput" class="form-label">Title</label>
    <input type="text" id="simpleinput" name="title" class="form-control">
</div>
</div>

<div class="col-xl-12">
 <div class="mb-3">
    <label for="simpleinput" class="form-label">Content</label>

    <textarea name="content" id="snow-editor" cols="142" rows="10"></textarea>
    
 </div>
</div>
</div>

   <!-- Country & City -->
      <div class="row">
         <div class="col-xl-6">
            <label for="">COUNTRY</label>
            <select name="property_country" onchange="selectCities()" id="property_country" class="form-control">
               @foreach($all_countries as $country_res)
               <option value="{{ $country_res['id'] }}">{{ $country_res['name'] }}</option>
               @endforeach
            </select>
         </div>
         <div class="col-xl-6">
            <label for="">City</label>
            <select name="property_city" id="property_city" class="form-control">
              
            </select>
         </div>
      </div>
   <!-- END Country & City -->

<div class="row">
      <div class="col-xl-4">
         <div class="mb-3">
          <label for="simpleinput" class="form-label">Start Date</label>
          <input type="date" name="start_date" class="form-control date" id="start_date">
         </div>
      </div>

      <div class="col-xl-4">
         <div class="mb-3">
          <label for="simpleinput" class="form-label">End Date</label>
          <input type="date" name="end_date" class="form-control date" id="end_date">
         </div>
      </div>
      <div class="col-xl-4">
         <div class="mb-3">
          <label for="simpleinput" class="form-label">No Of Nights</label>
          <input readonly type="text" name="time_duration" id="duration" class="form-control" >
         </div>
      </div>


      <div class="col-xl-6">
         <div class="mb-3">
          <label for="simpleinput" class="form-label">Tour Locations</label>
          <select name="tour_location" id="" class="form-control">
          <option value="">Select Tour Locations</option>
              <option value="makkah">Makkah</option>
              <option value="madina">Madina</option>
              <option value="jeddah">Jeddah</option>
          </select>
         </div>
      </div>

      <div class="col-xl-6">
         <div class="mb-3">
          <label for="simpleinput" class="form-label">Real tour address</label>
          <textarea name="tour_real_address" class="form-control" id="" cols="10" rows="0"></textarea>
          
         </div>
      </div>

   <!-- Add Sharings -->
      <div class="row">
         <div class="col-xl-6">
            <div class="mb-3">
               <label for="hotel_name">Select Hotel Name</label>
               <select class="form-control" id="hotel_name" required="" name="hotel_name" placeholder="Select Hotel Name">
               </select>
            </div>
         </div>
      </div>

      <div class="row mkah" style="display: none;">
         <div class="col-xl-6">
            <div class="mb-3">
               <label for="room_type">Select Hotel Type</label>
               <!-- <select name="agent_email[]" id="hotel_rooms_type" class="select2 form-control select2-multiple" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
               </select> -->
               <select name="hotel_rooms_type" id="hotel_rooms_type" class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="Hotel Rooms">
               <!-- data-toggle="select2" -->  
               </select>
            </div>
         </div>

         <div class="row PPNmakkah" style="display: none;">
            <div class="col-xl-6">
               <div class="mb-3">
                  <label for="price_per_night">Price Per Night</label>
                  <input readonly type="text" class="form-control mt-4" id="price_per_night" name="price_per_night" value="">
               </div>
            </div>
            <div class="col-xl-6">
               <div class="mb-3">
                  <label for="total_price_per_night">Total Price Per Night</label>
                  <input readonly type="text" class="form-control mt-4" id="total_price_per_night" name="total_price_per_night" value="">
               </div>
            </div>
         </div>
   </div>
   <!-- End Add Sharings -->
   
   <!-- 1 -->
      <div class="col-xl-6">
       <div class="mb-3">
          <label for="simpleinput" class="form-label">Tour Price</label>
          <input type="text" id="simpleinput" name="tour_pricing" class="form-control">
      </div>
      </div>

      <div class="col-xl-6">
       <div class="mb-3">
          <label for="simpleinput" class="form-label">Tour Sale Price</label>
          <input type="text" id="simpleinput" name="tour_sale_price" class="form-control">
      </div>
      </div>

      <div class="col-xl-6">
       <div class="mb-3">
          <label for="simpleinput" class="form-label">Categories</label>
          <select class="select2 form-control select2-multiple" name="categories[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
           
               @foreach($categories as $categories)
            <option value="{{$categories->title}}">{{$categories->title}}</option>
        
           @endforeach
           
           </select>
      </div>
      </div>

      <div class="col-xl-6">
       <div class="mb-3">
          <label for="simpleinput" class="form-label">Attributes</label>
          <select class="select2 form-control select2-multiple" name="tour_attributes[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
           
               @foreach($attributes as $attributes)
            <option value="{{$attributes->title}}">{{$attributes->title}}</option>
        
           @endforeach
           
           </select>
      </div>
      </div>
      <div class="col-xl-6">
       <div class="mb-3">
          <label for="simpleinput" class="form-label">Tour Min People</label>
          <input type="number" id="simpleinput" name="tour_min_people" class="form-control">
      </div>
      </div>
      <div class="col-xl-6">
       <div class="mb-3">
          <label for="simpleinput" class="form-label">Tour Max People</label>
          <input type="number" id="simpleinput" name="tour_max_people" class="form-control">
      </div>
      </div>

      <div class="col-xl-6">
       <div class="mb-3">
          <label for="simpleinput" class="form-label">Tour Featured</label>
          <select name="tour_feature" id="" class="form-control">
          <option value="">Select Featured</option>
              <option value="0">Enable featured</option>
              <option value="1">Disable featured</option>
              
          </select>
      </div>
      </div>

      <div class="col-xl-6">
       <div class="mb-3">
          <label for="simpleinput" class="form-label">Default State</label>
          <select name="defalut_state" id="" class="form-control">
          <option value="">Select Default State</option>
              <option value="0">Always available</option>
              <option value="1">Only available on specific dates</option>
              
          </select>
      </div>
      </div>


      <div class="col-xl-12">
       <div class="mb-3">

      <div class="form-check form-check-inline">
        <input type="checkbox" class="form-check-input" id="Itinerary" name="">
       <label class="form-check-label" for="customCheck3">Itinerary</label>
       </div>

      </div>
      </div>


      <div class="row" id="Itinerary_select" style="display:none">
      <div class="col-xl-6">
      <div class="mb-3">
          <label for="simpleinput" class="form-label">Title</label>
          <input type="text" id="simpleinput" name="Itinerary_title" class="form-control">
      </div>
      </div>
      <div class="col-xl-6">
      <div class="mb-3">
          <label for="simpleinput" class="form-label">Desc: TP. HCM City</label>
          <input type="text" id="simpleinput" name="Itinerary_city" class="form-control">
      </div>
      </div>
      <div class="col-xl-12">
      <div class="mb-3">
          <label for="simpleinput" class="form-label">Content</label>
          <textarea name="Itinerary_content" class="form-control" id="" cols="10" rows="10"></textarea>
      </div>
      </div>
      <div class="col-xl-12">
      <div class="mb-3">
          <label for="simpleinput" class="form-label">image</label>
          <input type="file" id="simpleinput" name="Itinerary_image" class="form-control">
      </div>
      </div>

      <div class="" id="append_data">

      </div>


      <div class="mb-3">
          <div class="btn btn-info" id="click_more_Itinerary">Add More</div>
          
      </div>
      </div>


      <div class="col-xl-12">
       <div class="mb-3">

      <div class="form-check form-check-inline">
        <input type="checkbox" class="form-check-input" id="extra_price" name="">
       <label class="form-check-label" for="customCheck3">Extra Price</label>
       </div>

      </div>
      </div>


      <!-- extra price started -->

      <div class="row" id="extraprice_select" style="display:none">
      <div class="col-xl-6">
      <div class="mb-3">
          <label for="simpleinput" class="form-label">Name</label>
          <input type="text" id="simpleinput" name="extra_price_name" class="form-control">
      </div>
      </div>
      <div class="col-xl-6">
      <div class="mb-3">
          <label for="simpleinput" class="form-label">Price</label>
          <input type="text" id="simpleinput" name="extra_price_price" class="form-control">
      </div>
      </div>
      <div class="col-xl-6">
      <div class="mb-3">
          <label for="simpleinput" class="form-label">Type</label>
          <select name="extra_price_type" id="" class="form-control">
          <option value="">Select Type</option>
              <option value="0">One-Time</option>
              <option value="1">Per-Hour</option>
              <option value="2">Per-Day</option>
          </select>
      </div>
      </div>
      <div class="col-xl-6">
      <div class="mb-3 mt-4">
      <div class="form-check form-check-inline">
        <input type="checkbox" class="form-check-input" id="Price_per_person" name="extra_price_person">
       <label class="form-check-label" for="Price_per_person">Price per person</label>
       </div>
      </div>
      </div>

      <div class="" id="append_data_extra_price">

      </div>


      <div class="mb-3">
          <div class="btn btn-info" id="click_more_extra_price">Add More</div>
          
      </div>
      </div>

      <!-- extra price ended -->


      <div class="col-xl-12">
      <div class="mb-3">

      <div class="form-check form-check-inline">
        <input type="checkbox" class="form-check-input" id="faq" name="">
      <label class="form-check-label" for="faq">FAQ</label>
      </div>

      </div>
      </div>



      <!-- faq started -->

      <div class="row" id="faq_select" style="display:none">
      <div class="col-xl-6">
      <div class="mb-3">
          <label for="simpleinput" class="form-label">Title</label>
          <input type="text" id="simpleinput" name="faq_title" class="form-control">
      </div>
      </div>
      <div class="col-xl-6">
      <div class="mb-3">
          <label for="simpleinput" class="form-label">content</label>
          <textarea name="faq_content" class="form-control" id="" cols="10" rows="10"></textarea>
      </div>
      </div>

      <div class="" id="append_data_faq">

      </div>


      <div class="mb-3">
         <div class="btn btn-info" id="click_more_faq">Add More</div>   
      </div>
      </div>

      <!-- faq ended -->

      </div>


      <div class="row">

      <div class="col-xl-6">
       <div class="mb-3">
       <label for="simpleinput" class="form-label">Featured Image</label>
          <input type="file" id="simpleinput" name="tour_featured_image" class="form-control">
      </div>
      </div>

      <div class="col-xl-6">
       <div class="mb-3">
       <label for="simpleinput" class="form-label">Banner Image</label>
          <input type="file" id="simpleinput" name="tour_banner_image" class="form-control">
      </div>
      </div>


      <div class="col-xl-6">
       <div class="mb-3">
          <label for="simpleinput" class="form-label">Publish</label>
          <select name="tour_publish" id="" class="form-control">
          <option value="">Select Publish</option>
              <option value="0">Publish</option>
              <option value="1">Draft</option>
          </select>
      </div>
      </div>

      <div class="col-xl-6">
       <div class="mb-3">
          <label for="simpleinput" class="form-label">Author</label>
          <select name="tour_author" id="" class="form-control">
          <option value="">Select Author</option>
              <option value="admin">admin</option>
              <option value="admin">admin1</option>
          </select>
      </div>
      </div>
      </div>

      <button style="float: right;" type="submit" name="submit" class="btn btn-info deletButton">Save Tour</button>


      </div>

      </div>
      </form>
      </div>
   <!-- 1 -->



@endsection