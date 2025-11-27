@extends('template/frontend/userdashboard/layout/default')
 @section('content')

    <!-- BEGIN: Content -->
    <div class="card">
        <div class="">
            <div class="">
                <!-- BEGIN: Vertical Form -->
                <div class="">
                    <div class="card-header mb-2">
                        <h2 class="">
                            Add Employee
                        </h2>
                    </div>
                    <form class="ps-3 pe-3" action="{{url('super_admin/employees/submit')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                        <div class="row">
                             
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" name="first_name" placeholder="Frist Name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="last_name" placeholder="Last Name">
                            </div>
                            </div>
                            </div>
                            <div class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Enter Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Email">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Enter Password</label>
                                <input type="password" class="form-control" name="password" placeholder="Password">
                            </div>
                             </div>
                            </div>
                             <div class="mb-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Enter Address</label>
                                <textarea class="form-control" cols="3"  name="address"></textarea>
                            </div>
                           </div>
                            </div>
                            <div class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Salary</label>
                                <input type="text" name="salary" class="form-control"  placeholder="Salary">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Birth-Date</label>
                                <input type="date" name="birth_date" class="form-control">
                            </div>
                             </div>
                            </div>
                             <div class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Contact Info</label>
                                <input type="text" name="contact_info" class="form-control"  placeholder="Contact Info">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-control">
                                    <option>Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                             </div>
                            </div>
                            <div class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Position</label>
                                <select name="position" class="form-control">
                                    <option value=" ">Select Position</option>
                                    @foreach($roles as $key => $value)
                                    <option value="{{$value->role_title ?? ''}}">{{$value->role_title ?? ''}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Schedule</label>
                                <select name="schedule" class="form-control">
                                <option value="07:00:00-01:00:00">07:00:00-01:00:00</option>
                                <option value="06:00:00-01:00:00">06:00:00-01:00:00</option>
                                <option value="05:00:00-01:00:00">05:00:00-01:00:00</option>
                            </select>
                        </div>
                         </div>
                            </div>
                            <!-- <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select name="employee_status" class="form-control">
                                    <option value="free">free</option>
                                    <option value="busy">busy</option>
                                </select>
                            </div> -->
                                <div class="mb-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Image</label>
                                <input type="file"  name="profile_image" class="form-control" id="profile_image" onchange="loadPreview(this);" accept="image/x-png,image/jpg,image/jpeg">
                            </div>
                             </div>
                        </div>
                        <div class="px-5 py-3 text-right border-t border-gray-200">
                            <button type="submit" name="submit" class="btn btn-info">Submit</button>
                        </div>
                    </form>
                </div>
                <!-- END: Vertical Form -->
            </div>
        </div>
    </div>
    <!-- END: Content -->
    <script>
        $(document).ready(function(){
            $('#preview_img_div').hide();
            $('#cancel_preview').on('click',function(){
                $('#preview_img_div').hide();
                $("#profile_image").val(null);
            });
        });
        function loadPreview(input, id) {
            id = id || '#preview_img';
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                $('#preview_img_div').show();     
                reader.onload = function (e) {
                    $(id).attr('src', e.target.result).width(200).height(150);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

@endsection
