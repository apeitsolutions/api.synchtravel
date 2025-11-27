@extends('template/frontend/userdashboard/layout/default')
@section('content')


@if(session()->has('message'))
<div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show" role="alert">
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    <strong>{{session('message')}} </strong>
</div>


        
    @endif
   <div>
    <div class="tab-content__pane" id="password">
                        <div class="">
                            <!-- BEGIN: Horizontal Bar Chart -->
                            <div class="">
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h2 class="font-medium text-base mr-auto">
                                            Change Password
                                        </h2>
                                    </div>
                                    <div class="card-body">
                                     <form class="ps-3 pe-3" action="{{URL::to('super_admin/change-password')}}"  method="post">
                 @csrf
       
 <!--<input type="hidden" name="login_user_id" value="{{Auth::guard('web')->user()->id}}">-->

                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="emailaddress" class="form-label">Current Password</label>
                        <input class="form-control"  type="password" id="current_password" name="current_password" value=""> 
                            </div>
                            
                            <div class="col-md-4">
                                <label for="emailaddress" class="form-label">New Password</label>
                        <input class="form-control"  type="password" id="new_password" name="new_password" value=""> 
                            </div>
                            <div class="col-md-4">
                                <label for="emailaddress" class="form-label">Confirm New Password</label>
                        <input class="form-control"  type="password" id="cnew_password" name="cnew_password" value=""> 
                            </div>
                        </div>
                       
                    </div>
                    
                   
                    
                    
                     
                    
                    
                    

                   

            
                    

                    <div class="mb-3">
                        <button name="submit" class="btn btn-primary" type="submit">submit</button>
                    </div>

                </form>

                                   </div>
                                </div>
                            </div>
                            <!-- END: Horizontal Bar Chart -->
                        </div>

                    </div>



   
       
    </div>
    <!-- END: Content -->


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
           $('#percentage').on('change', function() {
                // alert( this.value );
                var v = ( this.value );
                // alert(v);
                $('#temp').val(v);
                });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
           $('#percentage_1').on('change', function() {
                // alert( this.value );
                var v = ( this.value );
                // alert(v);
                $('#temp_1').val(v);
                });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
           $('#percentage_2').on('change', function() {
                // alert( this.value );
                var v = ( this.value );
                // alert(v);
                $('#temp_2').val(v);
                });
        });
    </script>

@endsection
