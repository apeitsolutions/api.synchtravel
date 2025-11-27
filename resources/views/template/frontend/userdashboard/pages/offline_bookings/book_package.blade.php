@extends('template/frontend/userdashboard/layout/default')
 @section('content')

    <!-- BEGIN: Content -->
    
    <div id="bs-example-modal-lg" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
<div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div class="mt-2 mb-4">
                    <a href="" class="text-success">
                        <span>Book Now</span>
                    </a>
                </div>

              

                       <form class="ps-3 pe-3" action="{{URL::to('super_admin/submit_book_package')}}" id="edit_role_form" method="post">
                 @csrf
                  


                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="emailaddress" class="form-label">First Name</label>
                        <input class="form-control"  type="text" id="fname" name="fname" value=""> 
                            </div>
                            <div class="col-md-6">
                                <label for="emailaddress" class="form-label">Last Name</label>
                        <input class="form-control"  type="text" id="lname" name="lname" value=""> 
                            </div>
                            
                        </div>
                       
                    </div>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="emailaddress" class="form-label">Email</label>
                        <input class="form-control"  type="email" id="email" name="email" value=""> 
                            </div>
                            <div class="col-md-6">
                                <label for="emailaddress" class="form-label">Phone No</label>
                        <input class="form-control"  type="text" id="phone_no" name="phone_no" value=""> 
                            </div>
                            
                        </div>
                       
                    </div>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="emailaddress" class="form-label">Address</label>
                               <textarea class="form-control" name="address" cols="5" rows="5"></textarea>
                        
                            </div>
                            
                             
                            
                        </div>
                       
                    </div>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="emailaddress" class="form-label">Country</label>
                       <select class="form-control" id="select_contries" name="country">
                                    <option value="">Select Country</option>
                                    @foreach($countries as $countries)
                                    <option value="{{$countries->id}}">{{$countries->name}}</option>
                                    @endforeach
                                   
                                   
                                </select> 
                            </div>
                            <div class="col-md-6">
                                <label for="emailaddress" class="form-label">City</label>
                                <select class="form-control" id="all_cities" name="city">
                                    
                                    </select>
                                    
                                </div> 
                            </div>
                            
                        </div>
                       
                    
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="emailaddress" class="form-label">Gender</label>
                        <select class="form-control" name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="FeMalel">FeMalel</option>
                                   
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="emailaddress" class="form-label">Passport No</label>
                        <input class="form-control"  type="text" id="passport_no" name="passport_no" value=""> 
                            </div>
                            <div class="col-md-4">
                                <label for="emailaddress" class="form-label">Passport Expire Date</label>
                        <input class="form-control"  type="date" id="passport_expire" name="passport_expire" value=""> 
                            </div>
                            
                        </div>
                       
                    </div>
                     
                   
                    
                    
                     
                    
                    
                    

                   

            
                    

                    <div class="mb-3">
                        <button name="submit" class="btn btn-primary" type="submit">submit</button>
                    </div>
</div>
                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    <div class="card mt-3">

                    <div class="card-header mb-2">
                        <h2 class="">
                            Book Package
                        </h2>
                    </div>
                   <div class="card-body">
                        <div class="mb-3">
                        <div class="row">
                             
                            <div class="col-md-6">
                                <label class="form-label">Select Package</label>
                               
                                   
                                   
                                   <select class="form-control" id="bookingPackage">
                                       <option>Select Package</option>
    <optgroup label="Umrah Packages">
        @foreach($umrah_packages as $umrah_packages)
        <option value="{{$umrah_packages->id}}">{{$umrah_packages->package_name}}</option>
         @endforeach
       
    </optgroup>
    <optgroup label="Tour Packages">
         @foreach($tours as $tours)
        <option value="{{$tours->title}}">{{$tours->title}}</option>
         @endforeach
    </optgroup>
    
</select>
                                   
                                   
                                   
                                   
                               
                            
                            </div>
                            
                          
                            </div>
                            </div>
                            </div>
        
    </div>
    
    
    

    
    <form id="form_fields" action="{{URL::to('agent_dashboard/customer_invoice_submit')}}" method="POST">
   <!--  @csrf -->
<div id="extra_fields">
       
</div>
</form>
            
            
<!--<div class="card mt-3"><div class="card-body"><div class="mb-3"><div class="row"><div class="col-md-6"><label class="form-label">Umrah Package</label><select name="gender" class="form-control"><option>Select Umrah Package</option></select></div></div></div></div></div>-->
    
    
    
    
  
    

@endsection
