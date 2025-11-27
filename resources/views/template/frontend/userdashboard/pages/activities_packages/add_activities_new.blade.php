@extends('template/frontend/userdashboard/layout/default')
@section('content')


<div id="add-Activities-Categories-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Activities Categories</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="https://client.synchronousdigital.com/super_admin/submit_categories_activities" id="cateogory_form" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="label" class="form-label">Title</label>
                        <input class="form-control" type="text" id="title" name="title" placeholder="">
                        <input class="form-control" type="text" id="title" hidden value="1" name="ajax" placeholder="">
                        
                    </div>
                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug</label>
                        <input class="form-control" type="text" id="slug" name="slug" placeholder="">
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input class="form-control" type="file" id="" name="image" placeholder="">
                    </div>
                    <div class="mb-3">
                        <label for="placement" class="form-label">Placement</label>
                        <input class="form-control" type="text" id="placement" name="placement" placeholder="">
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" type="text" id="" name="description" placeholder=""></textarea>
                    </div>
                    

                    <div class="mb-3 text-center">
                        <button class="btn btn-primary" id="submit_cat" type="submit" name="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="add-Activities-facilities-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="standard-modalLabel">Add Activities Facilities</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="https://client.synchronousdigital.com/super_admin/submit_attributes_activities" id="facilities_form" method="post" enctype="multipart/form-data">
                @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label">Facilities Name</label>
                        <input class="form-control" type="title" id="title" name="title" placeholder="">
                        <input class="form-control" type="text" id="title" hidden value="1" name="ajax" placeholder="">
                    </div>
                    <div class="mb-3 text-center">
                        <button class="btn btn-primary" id="" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
 
<div class="card">
    <div class="card-body">
        <h4 class="header-title">Create Activities</h4>
        
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>{{ session('error') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>{{ session('success') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        <div class="tab-content">
            <div class="tab-pane show active" id="justified-tabs-preview">
                <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                    <li class="nav-item">
                        <a href="#home1" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0  active">
                            <i class="mdi mdi-home-variant d-md-none d-block"></i>
                            <span class="d-none d-md-block">Activity Details</span>
                        </a>
                    </li>

                    
                    <li class="nav-item">
                        <a href="#trans_details_1" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0">
                            <i class="mdi mdi-settings-outline d-md-none d-block"></i>
                            <span class="d-none d-md-block">Availability</span>
                        </a>
                    </li>
                     
                    <li class="nav-item">
                        <a href="#Itinerary_details" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0">
                            <i class="mdi mdi-settings-outline d-md-none d-block"></i>
                            <span class="d-none d-md-block">Itinerary</span>
                        </a>
                    </li>
                     <li class="nav-item">
                        <a href="#additional_info" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0">
                            <i class="mdi mdi-settings-outline d-md-none d-block"></i>
                            <span class="d-none d-md-block">Addtional Info</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#Extras_details" data-bs-toggle="tab" aria-expanded="false" class="nav-link rounded-0">
                            <i class="mdi mdi-settings-outline d-md-none d-block"></i>
                            <span class="d-none d-md-block">Costing</span>
                        </a>
                    </li>
                </ul>
                
                <form action="{{URL::to('super_admin/submit_Activities_last')}}" method="post" enctype="multipart/form-data" >
                    @csrf
                    <div class="tab-content">
                        <div class="tab-pane  show active" id="home1">
                           <div class="row">
                                <div class="col-xl-6">
                                     <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Title</label>
                                        <input type="text" id="simpleinput" name="title" class="form-control">
                                     </div>
                                </div>
                                
                                <div class="col-xl-2">
                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Currency Symbol</label>
                                        <input readonly value="{{ $currency_Symbol }}" name="currency_symbol" class="form-control">
                                    </div>
                                </div>
                                
                                <div class="col-xl-2">
                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Duration(In Hours)</label>
                                        <input type="text" value="" name="duration" class="form-control">
                                    </div>
                                </div>
                                
                                <div class="col-xl-2">
                                    <div class="mb-3">
                                        <div id="tooltip-container">
                                            <label for="simpleinput" class="form-label">Rating Stars</label>
                                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                                data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Select star rating for this package">
                                            </i>
                                        </div>
                                        <select type="text" class="form-control select2 starts_rating" name="stars_rating"  data-placeholder="Choose ...">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="No_Rating">No_Rating</option>
                                        </select>
                                    </div>
                                </div>
                            
                                <div class="col-xl-3">
                                    <label class="form-label">COUNTRY</label>
                                    <select name="activity_country" onchange="selectCities_Hotel()" id="property_country" class="form-control">
                                       @foreach($all_countries as $country_res)
                                       <option  value="{{ $country_res->id }}" >{{ $country_res->name}}</option>
                                       @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-xl-3">
                                    <label class="form-label">City</label>
                                    <select name="activity_city" id="property_city" class="select2 form-control" data-toggle="select2" data-placeholder="Choose ..."></select>
                                </div>
                                
                                <div class="col-xl-3">
                                 <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Location</label>
                                    <input type="text" value="" id="activity_location" name="location" class="form-control">
                                 </div>
                                </div>
                                
                                <div class="col-xl-3">
                                  <div class="mb-3">
                                    <label class="form-label">Activity Dates</label>
                                    <input type="text" class="form-control" id="activity_dates" name="activtiy_dates" data-toggle="date-picker" data-cancel-class="btn-warning">
                                    </div>
                                </div>
                                
                                <div class="col-xl-12">
                                 <div class="mb-3">
                                    <label for="simpleinput" class="form-label">Content</label>
                                
                                    <textarea name="activity_content" id="snow-editor" cols="142" rows="10"></textarea>
                                    
                                 </div>
                                </div>
                                
                                <div class="col-xl-3">
                                    <div class="mb-3">
                                        <div style="height: 40px;">
                                            <label for="">Categories</label>
                                            <span title="Add Categories" class="input-group-btn input-group-append" style="float: right;">
                                                <button class="btn btn-primary bootstrap-touchspin-up" data-bs-toggle="modal" data-bs-target="#add-Activities-Categories-modal" type="button">+</button>
                                            </span>
                                        </div>
                                        <select type="text" class="form-control select2" id="cat_id" name="categories"  data-placeholder="Choose ...">
                                            @foreach($categories as $categories)
                                                <option value="{{$categories->id}}">{{$categories->title}}</option>
                                            @endforeach
                                        </select>
                                        
                                    </div>
                                </div>
                                
                                <div class="col-xl-3">
                                    <div class="mb-3">
                                        <div style="height: 40px;">
                                            <label for="simpleinput" class="form-label">Facilities</label>
                                            <span title="Add Facilities" class="input-group-btn input-group-append" style="float: right;">
                                                <button class="btn btn-primary bootstrap-touchspin-up" data-bs-toggle="modal" data-bs-target="#add-Activities-facilities-modal" type="button">+</button>
                                            </span>
                                        </div>
                                        <select class="select2 form-control select2-multiple" id="faclitiy_id" name="tour_attributes[]" data-toggle="select2" multiple="multiple" data-placeholder="Choose ...">
                                            @foreach($attributes as $attributes)
                                                <option value="{{$attributes->id}}">{{$attributes->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-xl-3">
                                     <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Min People</label>
                                        <input type="text" value="" name="min_people" class="form-control">
                                     </div>
                                    </div>
                                
                                <div class="col-xl-3">
                                     <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Max People</label>
                                        <input type="text" value="" name="max_people" class="form-control">
                                     </div>
                                 </div>
                                
                                <div class="col-xl-12">
                                     <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Youtube Video Link</label>
                                        <input type="text" value="" name="video_link" class="form-control">
                                     </div>
                                 </div>
                                
                                <div class="col-xl-4">
                                   <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Package Featured</label>
                                      <select name="package_feature" id="" class="form-control tour_feature" style="margin-top: 18px">
                                        <option value="">Select Featured</option>
                                        <option value="1">Enable featured</option>
                                        <option value="0">Disable featured</option>
                                    </select>
                                  </div>
                                 </div>
                                
                                <div class="col-xl-4">
                                   <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Author</label>
                                      <select name="package_author" id="" class="form-control tour_author" style="margin-top: 18px">
                                          <option value="">Select Author</option>
                                          <option value="admin">admin</option>
                                          <option value="admin">admin1</option>
                                      </select>
                                  </div>
                                 </div>
                                
                                <div class="col-xl-4">
                                   <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Publish Status</label>
                                      <select name="publish_status" id="" class="form-control tour_feature" style="margin-top: 18px">
                                        <option value="Drafts">Drafts</option>
                                        <option value="Publish">Publish</option>
                                    </select>
                                  </div>
                                 </div>
                                
                                <div class="col-xl-6">
                                   <div class="mb-3">
                                   <label for="simpleinput" class="form-label">Featured Image</label>
                                      <input type="file" id="simpleinput" name="featured_image" class="form-control">
                                  </div>
                                 </div>
                                
                                <div class="col-xl-6">
                                   <div class="mb-3">
                                   <label for="simpleinput" class="form-label">Banner Image</label>
                                      <input type="file" id="simpleinput" name="banner_image" class="form-control">
                                  </div>
                                 </div>
                                
                                <div class="col-xl-12">
                                   <div class="mb-3">
                                   <label for="simpleinput" class="form-label">Select Gallery Images</label>
                                      <input type="file" multiple id="simpleinput" name="package_gallery[]" class="form-control">
                                  </div>
                                 </div>
                           </div>
                        </div>
           
                        <div class="tab-pane" id="trans_details_1">
                            <div class="panel-body">
                            <h3 class="panel-body-title">Open Hours</h3>
                           
                                    <div class="table-responsive form-group" data-condition="enable_open_hours:is(1)" style="">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Enable?</th>
                                        <th>Day of Week</th>
                                        <th>Open</th>
                                        <th>Close</th>
                                    </tr>
                                    </thead>
                                                        <tbody><tr>
                                            <td>
                                                <input style="display: inline-block" type="checkbox" name="open_hours[1][enable]" value="1">
                                            </td>
                                            <td><strong>
                                                                                        Monday
                                                                                    </strong></td>
                                            <td>
                                                <select class="form-control" name="open_hours[1][from]">
                                                                                    <option value="00:00">00:00</option>
                    
                                                                                    <option value="01:00">01:00</option>
                    
                                                                                    <option value="02:00">02:00</option>
                    
                                                                                    <option value="03:00">03:00</option>
                    
                                                                                    <option value="04:00">04:00</option>
                    
                                                                                    <option value="05:00">05:00</option>
                    
                                                                                    <option value="06:00">06:00</option>
                    
                                                                                    <option value="07:00">07:00</option>
                    
                                                                                    <option value="08:00">08:00</option>
                    
                                                                                    <option value="09:00">09:00</option>
                    
                                                                                    <option value="10:00">10:00</option>
                    
                                                                                    <option value="11:00">11:00</option>
                    
                                                                                    <option value="12:00">12:00</option>
                    
                                                                                    <option value="13:00">13:00</option>
                    
                                                                                    <option value="14:00">14:00</option>
                    
                                                                                    <option value="15:00">15:00</option>
                    
                                                                                    <option value="16:00">16:00</option>
                    
                                                                                    <option value="17:00">17:00</option>
                    
                                                                                    <option value="18:00">18:00</option>
                    
                                                                                    <option value="19:00">19:00</option>
                    
                                                                                    <option value="20:00">20:00</option>
                    
                                                                                    <option value="21:00">21:00</option>
                    
                                                                                    <option value="22:00">22:00</option>
                    
                                                                                    <option value="23:00">23:00</option>
                    
                                                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control" name="open_hours[1][to]">
                                                                                    <option value="00:00">00:00</option>
                    
                                                                                    <option value="01:00">01:00</option>
                    
                                                                                    <option value="02:00">02:00</option>
                    
                                                                                    <option value="03:00">03:00</option>
                    
                                                                                    <option value="04:00">04:00</option>
                    
                                                                                    <option value="05:00">05:00</option>
                    
                                                                                    <option value="06:00">06:00</option>
                    
                                                                                    <option value="07:00">07:00</option>
                    
                                                                                    <option value="08:00">08:00</option>
                    
                                                                                    <option value="09:00">09:00</option>
                    
                                                                                    <option value="10:00">10:00</option>
                    
                                                                                    <option value="11:00">11:00</option>
                    
                                                                                    <option value="12:00">12:00</option>
                    
                                                                                    <option value="13:00">13:00</option>
                    
                                                                                    <option value="14:00">14:00</option>
                    
                                                                                    <option value="15:00">15:00</option>
                    
                                                                                    <option value="16:00">16:00</option>
                    
                                                                                    <option value="17:00">17:00</option>
                    
                                                                                    <option value="18:00">18:00</option>
                    
                                                                                    <option value="19:00">19:00</option>
                    
                                                                                    <option value="20:00">20:00</option>
                    
                                                                                    <option value="21:00">21:00</option>
                    
                                                                                    <option value="22:00">22:00</option>
                    
                                                                                    <option value="23:00">23:00</option>
                    
                                                                                </select>
                                            </td>
                                        </tr>
                                                        <tr>
                                            <td>
                                                <input style="display: inline-block" type="checkbox" name="open_hours[2][enable]" value="1">
                                            </td>
                                            <td><strong>
                                                                                        Tuesday
                                                                                    </strong></td>
                                            <td>
                                                <select class="form-control" name="open_hours[2][from]">
                                                                                    <option value="00:00">00:00</option>
                    
                                                                                    <option value="01:00">01:00</option>
                    
                                                                                    <option value="02:00">02:00</option>
                    
                                                                                    <option value="03:00">03:00</option>
                    
                                                                                    <option value="04:00">04:00</option>
                    
                                                                                    <option value="05:00">05:00</option>
                    
                                                                                    <option value="06:00">06:00</option>
                    
                                                                                    <option value="07:00">07:00</option>
                    
                                                                                    <option value="08:00">08:00</option>
                    
                                                                                    <option value="09:00">09:00</option>
                    
                                                                                    <option value="10:00">10:00</option>
                    
                                                                                    <option value="11:00">11:00</option>
                    
                                                                                    <option value="12:00">12:00</option>
                    
                                                                                    <option value="13:00">13:00</option>
                    
                                                                                    <option value="14:00">14:00</option>
                    
                                                                                    <option value="15:00">15:00</option>
                    
                                                                                    <option value="16:00">16:00</option>
                    
                                                                                    <option value="17:00">17:00</option>
                    
                                                                                    <option value="18:00">18:00</option>
                    
                                                                                    <option value="19:00">19:00</option>
                    
                                                                                    <option value="20:00">20:00</option>
                    
                                                                                    <option value="21:00">21:00</option>
                    
                                                                                    <option value="22:00">22:00</option>
                    
                                                                                    <option value="23:00">23:00</option>
                    
                                                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control" name="open_hours[2][to]">
                                                                                    <option value="00:00">00:00</option>
                    
                                                                                    <option value="01:00">01:00</option>
                    
                                                                                    <option value="02:00">02:00</option>
                    
                                                                                    <option value="03:00">03:00</option>
                    
                                                                                    <option value="04:00">04:00</option>
                    
                                                                                    <option value="05:00">05:00</option>
                    
                                                                                    <option value="06:00">06:00</option>
                    
                                                                                    <option value="07:00">07:00</option>
                    
                                                                                    <option value="08:00">08:00</option>
                    
                                                                                    <option value="09:00">09:00</option>
                    
                                                                                    <option value="10:00">10:00</option>
                    
                                                                                    <option value="11:00">11:00</option>
                    
                                                                                    <option value="12:00">12:00</option>
                    
                                                                                    <option value="13:00">13:00</option>
                    
                                                                                    <option value="14:00">14:00</option>
                    
                                                                                    <option value="15:00">15:00</option>
                    
                                                                                    <option value="16:00">16:00</option>
                    
                                                                                    <option value="17:00">17:00</option>
                    
                                                                                    <option value="18:00">18:00</option>
                    
                                                                                    <option value="19:00">19:00</option>
                    
                                                                                    <option value="20:00">20:00</option>
                    
                                                                                    <option value="21:00">21:00</option>
                    
                                                                                    <option value="22:00">22:00</option>
                    
                                                                                    <option value="23:00">23:00</option>
                    
                                                                                </select>
                                            </td>
                                        </tr>
                                                        <tr>
                                            <td>
                                                <input style="display: inline-block" type="checkbox" name="open_hours[3][enable]" value="1">
                                            </td>
                                            <td><strong>
                                                                                        Wednesday
                                                                                    </strong></td>
                                            <td>
                                                <select class="form-control" name="open_hours[3][from]">
                                                                                    <option value="00:00">00:00</option>
                    
                                                                                    <option value="01:00">01:00</option>
                    
                                                                                    <option value="02:00">02:00</option>
                    
                                                                                    <option value="03:00">03:00</option>
                    
                                                                                    <option value="04:00">04:00</option>
                    
                                                                                    <option value="05:00">05:00</option>
                    
                                                                                    <option value="06:00">06:00</option>
                    
                                                                                    <option value="07:00">07:00</option>
                    
                                                                                    <option value="08:00">08:00</option>
                    
                                                                                    <option value="09:00">09:00</option>
                    
                                                                                    <option value="10:00">10:00</option>
                    
                                                                                    <option value="11:00">11:00</option>
                    
                                                                                    <option value="12:00">12:00</option>
                    
                                                                                    <option value="13:00">13:00</option>
                    
                                                                                    <option value="14:00">14:00</option>
                    
                                                                                    <option value="15:00">15:00</option>
                    
                                                                                    <option value="16:00">16:00</option>
                    
                                                                                    <option value="17:00">17:00</option>
                    
                                                                                    <option value="18:00">18:00</option>
                    
                                                                                    <option value="19:00">19:00</option>
                    
                                                                                    <option value="20:00">20:00</option>
                    
                                                                                    <option value="21:00">21:00</option>
                    
                                                                                    <option value="22:00">22:00</option>
                    
                                                                                    <option value="23:00">23:00</option>
                    
                                                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control" name="open_hours[3][to]">
                                                                                    <option value="00:00">00:00</option>
                    
                                                                                    <option value="01:00">01:00</option>
                    
                                                                                    <option value="02:00">02:00</option>
                    
                                                                                    <option value="03:00">03:00</option>
                    
                                                                                    <option value="04:00">04:00</option>
                    
                                                                                    <option value="05:00">05:00</option>
                    
                                                                                    <option value="06:00">06:00</option>
                    
                                                                                    <option value="07:00">07:00</option>
                    
                                                                                    <option value="08:00">08:00</option>
                    
                                                                                    <option value="09:00">09:00</option>
                    
                                                                                    <option value="10:00">10:00</option>
                    
                                                                                    <option value="11:00">11:00</option>
                    
                                                                                    <option value="12:00">12:00</option>
                    
                                                                                    <option value="13:00">13:00</option>
                    
                                                                                    <option value="14:00">14:00</option>
                    
                                                                                    <option value="15:00">15:00</option>
                    
                                                                                    <option value="16:00">16:00</option>
                    
                                                                                    <option value="17:00">17:00</option>
                    
                                                                                    <option value="18:00">18:00</option>
                    
                                                                                    <option value="19:00">19:00</option>
                    
                                                                                    <option value="20:00">20:00</option>
                    
                                                                                    <option value="21:00">21:00</option>
                    
                                                                                    <option value="22:00">22:00</option>
                    
                                                                                    <option value="23:00">23:00</option>
                    
                                                                                </select>
                                            </td>
                                        </tr>
                                                        <tr>
                                            <td>
                                                <input style="display: inline-block" type="checkbox" name="open_hours[4][enable]" value="1">
                                            </td>
                                            <td><strong>
                                                                                        Thursday
                                                                                    </strong></td>
                                            <td>
                                                <select class="form-control" name="open_hours[4][from]">
                                                                                    <option value="00:00">00:00</option>
                    
                                                                                    <option value="01:00">01:00</option>
                    
                                                                                    <option value="02:00">02:00</option>
                    
                                                                                    <option value="03:00">03:00</option>
                    
                                                                                    <option value="04:00">04:00</option>
                    
                                                                                    <option value="05:00">05:00</option>
                    
                                                                                    <option value="06:00">06:00</option>
                    
                                                                                    <option value="07:00">07:00</option>
                    
                                                                                    <option value="08:00">08:00</option>
                    
                                                                                    <option value="09:00">09:00</option>
                    
                                                                                    <option value="10:00">10:00</option>
                    
                                                                                    <option value="11:00">11:00</option>
                    
                                                                                    <option value="12:00">12:00</option>
                    
                                                                                    <option value="13:00">13:00</option>
                    
                                                                                    <option value="14:00">14:00</option>
                    
                                                                                    <option value="15:00">15:00</option>
                    
                                                                                    <option value="16:00">16:00</option>
                    
                                                                                    <option value="17:00">17:00</option>
                    
                                                                                    <option value="18:00">18:00</option>
                    
                                                                                    <option value="19:00">19:00</option>
                    
                                                                                    <option value="20:00">20:00</option>
                    
                                                                                    <option value="21:00">21:00</option>
                    
                                                                                    <option value="22:00">22:00</option>
                    
                                                                                    <option value="23:00">23:00</option>
                    
                                                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control" name="open_hours[4][to]">
                                                                                    <option value="00:00">00:00</option>
                    
                                                                                    <option value="01:00">01:00</option>
                    
                                                                                    <option value="02:00">02:00</option>
                    
                                                                                    <option value="03:00">03:00</option>
                    
                                                                                    <option value="04:00">04:00</option>
                    
                                                                                    <option value="05:00">05:00</option>
                    
                                                                                    <option value="06:00">06:00</option>
                    
                                                                                    <option value="07:00">07:00</option>
                    
                                                                                    <option value="08:00">08:00</option>
                    
                                                                                    <option value="09:00">09:00</option>
                    
                                                                                    <option value="10:00">10:00</option>
                    
                                                                                    <option value="11:00">11:00</option>
                    
                                                                                    <option value="12:00">12:00</option>
                    
                                                                                    <option value="13:00">13:00</option>
                    
                                                                                    <option value="14:00">14:00</option>
                    
                                                                                    <option value="15:00">15:00</option>
                    
                                                                                    <option value="16:00">16:00</option>
                    
                                                                                    <option value="17:00">17:00</option>
                    
                                                                                    <option value="18:00">18:00</option>
                    
                                                                                    <option value="19:00">19:00</option>
                    
                                                                                    <option value="20:00">20:00</option>
                    
                                                                                    <option value="21:00">21:00</option>
                    
                                                                                    <option value="22:00">22:00</option>
                    
                                                                                    <option value="23:00">23:00</option>
                    
                                                                                </select>
                                            </td>
                                        </tr>
                                                        <tr>
                                            <td>
                                                <input style="display: inline-block" type="checkbox" name="open_hours[5][enable]" value="1">
                                            </td>
                                            <td><strong>
                                                                                        Friday
                                                                                    </strong></td>
                                            <td>
                                                <select class="form-control" name="open_hours[5][from]">
                                                                                    <option value="00:00">00:00</option>
                    
                                                                                    <option value="01:00">01:00</option>
                    
                                                                                    <option value="02:00">02:00</option>
                    
                                                                                    <option value="03:00">03:00</option>
                    
                                                                                    <option value="04:00">04:00</option>
                    
                                                                                    <option value="05:00">05:00</option>
                    
                                                                                    <option value="06:00">06:00</option>
                    
                                                                                    <option value="07:00">07:00</option>
                    
                                                                                    <option value="08:00">08:00</option>
                    
                                                                                    <option value="09:00">09:00</option>
                    
                                                                                    <option value="10:00">10:00</option>
                    
                                                                                    <option value="11:00">11:00</option>
                    
                                                                                    <option value="12:00">12:00</option>
                    
                                                                                    <option value="13:00">13:00</option>
                    
                                                                                    <option value="14:00">14:00</option>
                    
                                                                                    <option value="15:00">15:00</option>
                    
                                                                                    <option value="16:00">16:00</option>
                    
                                                                                    <option value="17:00">17:00</option>
                    
                                                                                    <option value="18:00">18:00</option>
                    
                                                                                    <option value="19:00">19:00</option>
                    
                                                                                    <option value="20:00">20:00</option>
                    
                                                                                    <option value="21:00">21:00</option>
                    
                                                                                    <option value="22:00">22:00</option>
                    
                                                                                    <option value="23:00">23:00</option>
                    
                                                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control" name="open_hours[5][to]">
                                                                                    <option value="00:00">00:00</option>
                    
                                                                                    <option value="01:00">01:00</option>
                    
                                                                                    <option value="02:00">02:00</option>
                    
                                                                                    <option value="03:00">03:00</option>
                    
                                                                                    <option value="04:00">04:00</option>
                    
                                                                                    <option value="05:00">05:00</option>
                    
                                                                                    <option value="06:00">06:00</option>
                    
                                                                                    <option value="07:00">07:00</option>
                    
                                                                                    <option value="08:00">08:00</option>
                    
                                                                                    <option value="09:00">09:00</option>
                    
                                                                                    <option value="10:00">10:00</option>
                    
                                                                                    <option value="11:00">11:00</option>
                    
                                                                                    <option value="12:00">12:00</option>
                    
                                                                                    <option value="13:00">13:00</option>
                    
                                                                                    <option value="14:00">14:00</option>
                    
                                                                                    <option value="15:00">15:00</option>
                    
                                                                                    <option value="16:00">16:00</option>
                    
                                                                                    <option value="17:00">17:00</option>
                    
                                                                                    <option value="18:00">18:00</option>
                    
                                                                                    <option value="19:00">19:00</option>
                    
                                                                                    <option value="20:00">20:00</option>
                    
                                                                                    <option value="21:00">21:00</option>
                    
                                                                                    <option value="22:00">22:00</option>
                    
                                                                                    <option value="23:00">23:00</option>
                    
                                                                                </select>
                                            </td>
                                        </tr>
                                                        <tr>
                                            <td>
                                                <input style="display: inline-block" type="checkbox" name="open_hours[6][enable]" value="1">
                                            </td>
                                            <td><strong>
                                                                                        Saturday
                                                                                    </strong></td>
                                            <td>
                                                <select class="form-control" name="open_hours[6][from]">
                                                                                    <option value="00:00">00:00</option>
                    
                                                                                    <option value="01:00">01:00</option>
                    
                                                                                    <option value="02:00">02:00</option>
                    
                                                                                    <option value="03:00">03:00</option>
                    
                                                                                    <option value="04:00">04:00</option>
                    
                                                                                    <option value="05:00">05:00</option>
                    
                                                                                    <option value="06:00">06:00</option>
                    
                                                                                    <option value="07:00">07:00</option>
                    
                                                                                    <option value="08:00">08:00</option>
                    
                                                                                    <option value="09:00">09:00</option>
                    
                                                                                    <option value="10:00">10:00</option>
                    
                                                                                    <option value="11:00">11:00</option>
                    
                                                                                    <option value="12:00">12:00</option>
                    
                                                                                    <option value="13:00">13:00</option>
                    
                                                                                    <option value="14:00">14:00</option>
                    
                                                                                    <option value="15:00">15:00</option>
                    
                                                                                    <option value="16:00">16:00</option>
                    
                                                                                    <option value="17:00">17:00</option>
                    
                                                                                    <option value="18:00">18:00</option>
                    
                                                                                    <option value="19:00">19:00</option>
                    
                                                                                    <option value="20:00">20:00</option>
                    
                                                                                    <option value="21:00">21:00</option>
                    
                                                                                    <option value="22:00">22:00</option>
                    
                                                                                    <option value="23:00">23:00</option>
                    
                                                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control" name="open_hours[6][to]">
                                                                                    <option value="00:00">00:00</option>
                    
                                                                                    <option value="01:00">01:00</option>
                    
                                                                                    <option value="02:00">02:00</option>
                    
                                                                                    <option value="03:00">03:00</option>
                    
                                                                                    <option value="04:00">04:00</option>
                    
                                                                                    <option value="05:00">05:00</option>
                    
                                                                                    <option value="06:00">06:00</option>
                    
                                                                                    <option value="07:00">07:00</option>
                    
                                                                                    <option value="08:00">08:00</option>
                    
                                                                                    <option value="09:00">09:00</option>
                    
                                                                                    <option value="10:00">10:00</option>
                    
                                                                                    <option value="11:00">11:00</option>
                    
                                                                                    <option value="12:00">12:00</option>
                    
                                                                                    <option value="13:00">13:00</option>
                    
                                                                                    <option value="14:00">14:00</option>
                    
                                                                                    <option value="15:00">15:00</option>
                    
                                                                                    <option value="16:00">16:00</option>
                    
                                                                                    <option value="17:00">17:00</option>
                    
                                                                                    <option value="18:00">18:00</option>
                    
                                                                                    <option value="19:00">19:00</option>
                    
                                                                                    <option value="20:00">20:00</option>
                    
                                                                                    <option value="21:00">21:00</option>
                    
                                                                                    <option value="22:00">22:00</option>
                    
                                                                                    <option value="23:00">23:00</option>
                    
                                                                                </select>
                                            </td>
                                        </tr>
                                                        <tr>
                                            <td>
                                                <input style="display: inline-block" type="checkbox" name="open_hours[7][enable]" value="1">
                                            </td>
                                            <td><strong>
                                                                                        Sunday
                                                                                    </strong></td>
                                            <td>
                                                <select class="form-control" name="open_hours[7][from]">
                                                                                    <option value="00:00">00:00</option>
                    
                                                                                    <option value="01:00">01:00</option>
                    
                                                                                    <option value="02:00">02:00</option>
                    
                                                                                    <option value="03:00">03:00</option>
                    
                                                                                    <option value="04:00">04:00</option>
                    
                                                                                    <option value="05:00">05:00</option>
                    
                                                                                    <option value="06:00">06:00</option>
                    
                                                                                    <option value="07:00">07:00</option>
                    
                                                                                    <option value="08:00">08:00</option>
                    
                                                                                    <option value="09:00">09:00</option>
                    
                                                                                    <option value="10:00">10:00</option>
                    
                                                                                    <option value="11:00">11:00</option>
                    
                                                                                    <option value="12:00">12:00</option>
                    
                                                                                    <option value="13:00">13:00</option>
                    
                                                                                    <option value="14:00">14:00</option>
                    
                                                                                    <option value="15:00">15:00</option>
                    
                                                                                    <option value="16:00">16:00</option>
                    
                                                                                    <option value="17:00">17:00</option>
                    
                                                                                    <option value="18:00">18:00</option>
                    
                                                                                    <option value="19:00">19:00</option>
                    
                                                                                    <option value="20:00">20:00</option>
                    
                                                                                    <option value="21:00">21:00</option>
                    
                                                                                    <option value="22:00">22:00</option>
                    
                                                                                    <option value="23:00">23:00</option>
                    
                                                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control" name="open_hours[7][to]">
                                                                                    <option value="00:00">00:00</option>
                    
                                                                                    <option value="01:00">01:00</option>
                    
                                                                                    <option value="02:00">02:00</option>
                    
                                                                                    <option value="03:00">03:00</option>
                    
                                                                                    <option value="04:00">04:00</option>
                    
                                                                                    <option value="05:00">05:00</option>
                    
                                                                                    <option value="06:00">06:00</option>
                    
                                                                                    <option value="07:00">07:00</option>
                    
                                                                                    <option value="08:00">08:00</option>
                    
                                                                                    <option value="09:00">09:00</option>
                    
                                                                                    <option value="10:00">10:00</option>
                    
                                                                                    <option value="11:00">11:00</option>
                    
                                                                                    <option value="12:00">12:00</option>
                    
                                                                                    <option value="13:00">13:00</option>
                    
                                                                                    <option value="14:00">14:00</option>
                    
                                                                                    <option value="15:00">15:00</option>
                    
                                                                                    <option value="16:00">16:00</option>
                    
                                                                                    <option value="17:00">17:00</option>
                    
                                                                                    <option value="18:00">18:00</option>
                    
                                                                                    <option value="19:00">19:00</option>
                    
                                                                                    <option value="20:00">20:00</option>
                    
                                                                                    <option value="21:00">21:00</option>
                    
                                                                                    <option value="22:00">22:00</option>
                    
                                                                                    <option value="23:00">23:00</option>
                    
                                                                                </select>
                                            </td>
                                        </tr>
                                                </tbody></table>
                            </div>
                        </div>

                        </div>
                        
                        <div class="tab-pane" id="Itinerary_details">
                                   <div class="row">
                                       <div class="col-xl-12">
                                         <div class="mb-3">
                                          <label for="simpleinput" class="form-label">What's Included In Activity?</label>
                                          <textarea name="whats_included" class="form-control" id="" cols="10" rows="0"></textarea>
                                          
                                         </div>
                                      </div>
                                      
                                      <div class="col-xl-12">
                                         <div class="mb-3">
                                          <label for="simpleinput" class="form-label">What's Excluded In Activity?</label>
                                          <textarea name="whats_excluded" class="form-control" id="" cols="10" rows="0"></textarea>
                                          
                                         </div>
                                      </div>
                                      
                                      
                                      
                                   </div>
                                   
                                   <div class="row">
                                        
                                            <div class="col-xl-12">
                                                What To Expect
                                                <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Add day to day Itinerary for this package" aria-label="Add day to day Itinerary for this package"></i>
                                            </div>
                                      </div>
                                   
                                   <div class="row" id="Itinerary_select" style="">
                                       <div class="col-md-12">
                                           <div class="" id="append_data">
                                                 
                                            </div>
                                       </div>
                                       
                                      
                                    
                                      
                                    
                                        <div class="col-md-12">
                                          <div class="mb-3 text-right">
                                              <div class="btn btn-info" onclick="add_activity_expect()">Add Item</div>

                                          </div>
                                        </div>
                                    </div>
                                    
                                     <div class="row">
                                        
                                            <div class="col-xl-12">
                                                FAQs
                                                <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Add FAQs" aria-label="Add day to day Itinerary for this package"></i>
                                            </div>
                                      </div>
                                   
                                   <div class="row" id="faqs" style="">
                                       <div class="col-md-12">
                                           <div class="" id="append_activty_FAQ">
                                                 
                                            </div>
                                       </div>
                                       
                                      
                                    
                                      
                                    
                                        <div class="col-md-12">
                                          <div class="mb-3 text-right">
                                              <div class="btn btn-info" onclick="add_activity_FAQ()">Add Item</div>
                                          
                                          </div>
                                        </div>
                                    </div>

                        </div>
                        
                        <div class="tab-pane" id="additional_info">
                                   <div class="row">
                                       <div class="col-xl-12">
                                         <div class="mb-3">
                                          <label for="simpleinput" class="form-label">Cancellation Policy</label>
                                          <textarea name="cancellation_policy" class="form-control" id="" cols="10" rows="0"></textarea>
                                          
                                         </div>
                                      </div>
                                      
                                      <div class="col-xl-12">
                                         <div class="mb-3">
                                          <label for="simpleinput" class="form-label">Checkout Message</label>
                                          <textarea name="checkout_message" class="form-control" id="" cols="10" rows="0"></textarea>
                                          
                                         </div>
                                      </div>
                                      
                                      <div class="col-xl-12">
                                         <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Meeting And Pickup</label>
                                        
                                            <textarea name="Meeting_and_pickup" id="summernote" cols="142" rows="10"></textarea>
                                            
                                         </div>
                                        </div>
                                      
                                      
                                      
                                   </div>
                                 
                                  

                        </div>
                        
                        <div class="tab-pane" id="Extras_details">
                               <div class="row">
                                   <div class="col-xl-6">
                                         <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Cost Price</label>
                                            <div class="input-group">
                                                    <input type="text" id="" name="cost_price" class="form-control">
                                                    <span class="input-group-btn input-group-append">
                                                        <a class="btn btn-primary bootstrap-touchspin-up">
                                                           {{ $currency_Symbol }}
                                                        </a>
                                                    </span>
                                            </div>
                                         </div>
                                    </div>
                                    
                                    <div class="col-xl-6">
                                         <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Sale Price</label>
                                             <div class="input-group">
                                                    <input type="text" id="" name="sale_price" class="form-control">
                                                    <span class="input-group-btn input-group-append">
                                                        <a class="btn btn-primary bootstrap-touchspin-up">
                                                           {{ $currency_Symbol }}
                                                        </a>
                                                    </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-12">
                                        <div class="row">                                                 
                                            <div class="col-xl-1">
                                               <div class="mb-3">
                                                    <input type="checkbox" id="child_prices" data-switch="bool"/>
                                                    <label for="child_prices" data-on-label="On" data-off-label="Off"></label>
                                                </div>
                                            </div>
                                            <div class="col-xl-3">
                                                Child Prices
                                                <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Select Country & City"></i>
                                            </div>
                                        </div>
                                        <div class="row" id="child_prices_div" style="display:none">
                                           <div class="col-xl-6 child_data">
                                                 <div class="mb-3">
                                                    <label for="simpleinput" class="form-label">Child Cost Price</label>
                                                    <div class="input-group">
                                                        <input type="text" id="" name="child_cost_price" class="form-control">
                                                        <span class="input-group-btn input-group-append">
                                                            <a class="btn btn-primary bootstrap-touchspin-up">
                                                               {{ $currency_Symbol }}
                                                            </a>
                                                        </span>
                                                    </div>
                                                 </div>
                                            </div>
                                            
                                            <div class="col-xl-6 child_data">
                                                 <div class="mb-3">
                                                    <label for="simpleinput" class="form-label">Child Sale Price</label>
                                                     <div class="input-group">
                                                        <input type="text" id="" name="child_sale_price" class="form-control">
                                                        <span class="input-group-btn input-group-append">
                                                            <a class="btn btn-primary bootstrap-touchspin-up">
                                                               {{ $currency_Symbol }}
                                                            </a>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">

                                             <div class="col-xl-6">
                                                <div class="mb-3">
                                                     <label for="simpleinput" class="form-label">Payment Methods</label>
                                                     <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Payment GateWay"></i>
                                                     <select class="form-control" id="payment_gateways" name="payment_gateways">
                                                         <option value="">Select Payment Gateway</option>
                                                         @if(isset($payment_gateways))
                                                         @foreach($payment_gateways as $payment_gateways)
                                                          <option value="{{$payment_gateways->payment_gateway_title}}">{{$payment_gateways->payment_gateway_title}}</option>
                                                          @endforeach
                                                          @endif
                                                     </select>
                                                
                                                </div>
                                                </div>
                                                <div class="col-xl-6">
                                                    <div class="mb-3">
                                                    
                                                    
                                                         <label for="simpleinput" class="form-label">Payment Mode</label>
                                                         <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Payment Mode"></i>
                                                         <select class="select2 form-control select2-multiple external_packages" data-toggle="select2" multiple="multiple" id="payment_modes" name="payment_modes[]">
                                                             @if(isset($payment_modes))
                                                                @foreach($payment_modes as $payment_modes)
                                                                    <option value="{{$payment_modes->payment_mode}}">{{$payment_modes->payment_mode}}</option>
                                                                @endforeach
                                                              @endif
                                                         </select>
                                                    
                                                     
                                                      
                                                    
                                                     
                                                     
                                                    </div>
                                                  
                                                </div>
                                        </div>
                                        <div class="row">                                                 
                                            <div class="col-xl-1">
                                               <div class="mb-3">
                                                    <input type="checkbox" id="addtional_service" data-switch="bool"/>
                                                    <label for="addtional_service" data-on-label="On" data-off-label="Off"></label>
                                                </div>
                                            </div>
                                            <div class="col-xl-3">
                                                Additonal Services
                                                <i class="dripicons-information" style="font-size: 17px;" id="title_Icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Select Country & City"></i>
                                            </div>
                                        </div>
                                          <div class="row" id="addtional_service_div"  style="display:none">
                                                 
                                            
                                                  <div class="" id="append_data_extra_price">
                                                
                                                  </div>
                                            
                                            
                                                  <div class="mb-3">
                                                      <div class="btn btn-info" onclick="add_activity_additoinal_service()">Add More</div>
                                                      
                                                  </div>
                                              </div>
                                        <button style="float: right;" type="submit" name="submit" class="btn btn-info deletButton">Submit</button>                                                      
                                    </div>

                         </div> 

                    </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initAutocomplete&language=nl&output=json&key=AIzaSyBmwlQFQKwxZ4D8nRbsWVRTBFUHMO-lUOY" async defer></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs5.min.js" integrity="sha512-6F1RVfnxCprKJmfulcxxym1Dar5FsT/V2jiEUvABiaEiFWoQ8yHvqRM/Slf0qJKiwin6IDQucjXuolCfCKnaJQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    
    function addGoogleApi(id){
        var places = new google.maps.places.Autocomplete(
            document.getElementById(id)
        );
        google.maps.event.addListener(places, "place_changed", function () {
            var place = places.getPlace();
            var address = place.formatted_address;
            var latitude = place.geometry.location.lat();
            var longitude = place.geometry.location.lng();
            var latlng = new google.maps.LatLng(latitude, longitude);
            var geocoder = (geocoder = new google.maps.Geocoder());
            geocoder.geocode({ latLng: latlng }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        var address = results[0].formatted_address;
                        var pin =
                        results[0].address_components[
                    results[0].address_components.length - 1
                  ].long_name;
                        var country =
                          results[0].address_components[
                            results[0].address_components.length - 2
                          ].long_name;
                        var state =
                          results[0].address_components[
                            results[0].address_components.length - 3
                          ].long_name;
                        var city =
                          results[0].address_components[
                            results[0].address_components.length - 4
                          ].long_name;
                        var country_code =
                          results[0].address_components[
                            results[0].address_components.length - 2
                          ].short_name;
                        $('#country').val(country);
                        $('#lat').val(latitude);
                        $('#long').val(longitude);
                        $('#pin').val(pin);
                        $('#city').val(city);
                        $('#country_code').val(country_code);
                        $('#pickup_CountryCode').val(country_code)
                    }
                }
            });
        });
    }

    $(document).ready(function() {
        selectCities_Hotel();
        addGoogleApi('activity_location');
        $('#summernote').summernote({});
    });

    function selectCities_Hotel(){
        var country = $('#property_country').val();
        console.log(country);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ url('/country_cites1') }}",
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                "id": country,
            },
            success: function(result){
                var options = result.options;
                $('#property_city').html(options);
            },
            error:function(error){
                console.log(error);
            }
        });
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#cateogory_form').submit(function(e) {
       e.preventDefault();
       let formData = new FormData(this);
       $('#image-input-error').text('');

       $.ajax({
          type:'POST',
          url: `/super_admin/submit_categories_activities`,
           data: formData,
           contentType: false,
           processData: false,
           success: (response) => {
               console.log(response)
             if (response) {
               this.reset();
               fetch_categories();
               $('#add-Activities-Categories-modal').modal('hide');
               alert('Category has been added successfully');
               
             }
           },
           error: function(response){
              console.log(response);
                $('#image-input-error').text(response.responseJSON.errors.file);
           }
       });
  });
  
    $('#facilities_form').submit(function(e) {
       e.preventDefault();
       let formData = new FormData(this);
     
       $.ajax({
          type:'POST',
          url: `/super_admin/submit_attributes_activities`,
           data: formData,
           contentType: false,
           processData: false,
           success: (response) => {
               console.log(response)
             if (response) {
               this.reset();
              fetch_facilities()
               $('#add-Activities-facilities-modal').modal('hide');
               alert('Category has been added successfully');
               
             }
           },
           error: function(response){
              console.log(response);
                $('#image-input-error').text(response.responseJSON.errors.file);
           }
       });
  });
  
    function fetch_categories(){
      $.ajax({
          type:'GET',
          url: `/super_admin/get_categories_activities`,
          data:{
               
          },
          contentType: false,
          processData: false,
          success: (response) => {
             $('#cat_id').html(response)
          }
      });
  }
  
    fetch_categories();
  
    function fetch_facilities(){
      $.ajax({
          type:'GET',
          url: `/super_admin/get_facliites_activities`,
          data:{
               
          },
          contentType: false,
          processData: false,
          success: (response) => {
              console.log(response);
             $('#faclitiy_id').html(response)
          }
      });
  }
  
    fetch_facilities();
  
    function add_cateogory(){
        console.log('form is submit is now ');
    }
     
    function initAutocomplete() {
        var departure_city = document.getElementById('activity_location');
        var autocomplete = new google.maps.places.Autocomplete(departure_city);
    }
      
    var add_expect = 1;
    var add_FAQ = 1;
    var add_ADD_service = 1;
    
    $("#child_prices").click(function () {
        $('#child_prices_div').toggle();
    });
      
    $("#addtional_service").click(function () {
        $('#addtional_service_div').toggle();
    });
  
    function add_activity_expect(){
        var expectHtml = `<div class="row" style="border: 2px solid #eef2f7;padding: 10px 10px 10px 10px;" id="expect${add_expect}">
                                  <div class="col-xl-12">
                                      <div class="mb-2">
                                          <label for="simpleinput" class="form-label">Title</label>
                                          <input type="text" id="simpleinput" name="expect_title[]" class="form-control">
                                      </div>
                                  </div>
                                 
                                  <div class="col-xl-12">
                                      <div class="mb-2">
                                          <label for="simpleinput" class="form-label">Content</label>
                                          <textarea name="expect_content[]" class="form-control" id="" cols="10" rows="3"></textarea>
                                      </div>
                                  </div>
                               
                                   <div class="col-xl-12">
                                      <div class="mb-2">
                                         <button type="button" class="btn btn-danger" style="float: right;" onclick="delete_expect_activity('expect${add_expect}')">Remove</button>
                                      </div>
                                  </div>
                              </div>`;
                              
            $('#append_data').append(expectHtml);
          add_expect++;                    
    }
    
    function delete_expect_activity(id){
        $('#'+id+'').remove();
    }
    
    function add_activity_FAQ(){
        console.log('you click');
          var FAQHtml = `<div class="row" style="border: 2px solid #eef2f7;padding: 10px 10px 10px 10px;" id="FAQ${add_FAQ}">
                                 <div class="col-xl-12">
                                      <div class="mb-3">
                                          <label for="simpleinput" class="form-label">Title</label>
                                          <input type="text" id="simpleinput" name="faq_title[]" class="form-control">
                                      </div>
                                  </div>
                                  <div class="col-xl-12">
                                      <div class="mb-3">
                                          <label for="simpleinput" class="form-label">content</label>
                                          <textarea name="faq_content[]" class="form-control" id="" cols="10" rows="3"></textarea>
                                      </div>
                                  </div>
                                   <div class="col-xl-12">
                                      <div class="mb-2">
                                         <button class="btn btn-danger" style="float: right;" onclick="delete_activity_FAQ('FAQ${add_FAQ}')">Remove</button>
                                      </div>
                                  </div>
                              </div>`;
                              
            $('#append_activty_FAQ').append(FAQHtml);
          add_FAQ++;
    }
    
    function delete_activity_FAQ(id){
        $('#'+id+'').remove();
    }
    
    function add_activity_additoinal_service(){
        console.log('add Addtional service');
             console.log('you click');
          var addtionServiceHtml = `<div class="row" style="border: 2px solid #eef2f7;padding: 10px 10px 10px 10px;" id="addtionalService${add_ADD_service}">
                                 <div class="col-xl-6">
                                      <div class="mb-3">
                                          <label for="simpleinput" class="form-label">Name</label>
                                          <input type="text" id="simpleinput" name="extra_price_name[]" class="form-control">
                                      </div>
                                  </div>
                                  <div class="col-xl-6">
                                      <div class="mb-3">
                                          <label for="simpleinput" class="form-label">Price</label>
                                            <div class="input-group">
                                                                                    <span class="input-group-btn input-group-append">
                                                            <a class="btn btn-primary bootstrap-touchspin-up">
                                                               {{ $currency_Symbol }}
                                                            </a>
                                                        </span>
                                                                            <input type="text" id="simpleinput" name="extra_price_price[]" class="form-control">
                                            </div>
                                      </div>
                                  </div>
                                  <div class="col-xl-6">
                                      <div class="mb-3">
                                          <label for="simpleinput" class="form-label">Type</label>
                                          <select name="extra_price_type[]" id="" class="form-control">
                                          <option value="">Select Type</option>
                                              <option value="0">One-Time</option>
                                          </select>
                                      </div>
                                  </div>
                                  <div class="col-xl-6">
                                      <div class="mb-3 mt-4">
                                      <div class="form-check form-check-inline">
                                        <input type="checkbox" class="form-check-input" id="Price_per_person" name="extra_price_person[]">
                                       <label class="form-check-label" for="Price_per_person">Price per person</label>
                                       </div>
                                      </div>
                                  </div>
                                  <div class="col-xl-12">
                                      <div class="mb-2">
                                         <button class="btn btn-danger" style="float: right;" onclick="delete_additional_service('addtionalService${add_ADD_service}')">Remove</button>
                                      </div>
                                  </div>
                              </div>`;
                              
            $('#append_data_extra_price').append(addtionServiceHtml);
          add_ADD_service++;
    }
    
    function delete_additional_service(id){
        $('#'+id+'').remove();
    }
    
    $('#activity_dates').daterangepicker({
        "startDate": "11/11/2022",
        "endDate": "11/17/2022",
        "format":'YYYY-MM-DD',
    }, function(start, end, label) {
        $('#activity_dates').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
      console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
    });
    
</script>
@stop