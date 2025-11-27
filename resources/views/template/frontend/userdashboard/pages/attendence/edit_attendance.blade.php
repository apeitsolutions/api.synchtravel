@extends('template/frontend/userdashboard/layout/default')
@section('content')


        <div class="card mt-3">
<div class="card-header">
                <h4 class="modal-title" id="myLargeModalLabel"></h4>
                
            </div>
            <div class="card-body">
                <div class="mt-2 mb-4">
                    <a href="" class="text-success">
                        <span>Edit Employee Attendance</span>
                    </a>
                </div>

              

                       <form class="ps-3 pe-3" action="{{url('super_admin/attendance_update',[$attendance->id])}}" id="edit_role_form" method="post">
                 @csrf
                  
 <input type="hidden" name="login_user_id" value="{{Auth::guard('web')->user()->id}}">

                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="emailaddress" class="form-label">Employee Id</label>
                        <input class="form-control"  type="text" id="employee_id" name="employee_id"  value="{{$attendance->employee_id}}"> 
                            </div>
                            <div class="col-md-6">
                                <label for="emailaddress" class="form-label">Employee Name</label>
                        <input class="form-control"  type="text" id="employee_name" name="employee_name" value="{{$attendance->employee_name}}"> 
                            </div>
                            
                        </div>
                       
                    </div>
                     <div class="mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="emailaddress" class="form-label">Date</label>
                        <input class="form-control"  type="date" id="employee_date" name="employee_date" value="{{$attendance->employee_date}}"> 
                            </div>
                            <div class="col-md-4">
                                <label for="emailaddress" class="form-label">Time In</label>
                        <input class="form-control"  type="time" id="time_in" name="time_in" value="{{$attendance->time_in}}"> 
                            </div>
                             <div class="col-md-4">
                                <label for="emailaddress" class="form-label">Time Out</label>
                        <input class="form-control"  type="time" id="time_out" name="time_out" value="{{$attendance->time_out}}"> 
                            </div>
                            
                        </div>
                       
                    </div>
                   
                    
                    
                     
                    
                    
                    

                   

            
                    

                    <div class="mb-3">
                        <button name="submit" class="btn btn-primary" type="submit">submit</button>
                    </div>

                </form>

            </div>
        </div><!-- /.modal-content -->
   

@endsection
