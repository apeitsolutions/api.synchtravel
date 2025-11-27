

@extends('template/frontend/userdashboard/layout/default')
 @section('content')

 @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show" role="alert">
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    <strong>{{session()->get('success')}} </strong>
</div>
    @endif
    
    
    
    <div id="bs2-example-modal-lg" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
<div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div class="mt-2 mb-4">
                    <a href="" class="text-success">
                        <span>Edit Role</span>
                    </a>
                </div>

              

                       <form class="ps-3 pe-3" action="{{URL::to('super_admin/edit_roles')}}"  method="post">
                 @csrf
    <input type="hidden" name="role_id" id="edit_form_id">              
 <!--<input type="hidden" name="login_user_id" value="{{Auth::guard('web')->user()->id}}">-->

                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="emailaddress" class="form-label">Roles Title</label>
                        <input class="form-control"  type="text" id="edit_form_title" name="role_title" value=""> 
                            </div>
                            
                            
                        </div>
                       
                    </div>
                   
                    
                    
                     
                    
                    
                    

                   

            
                    

                    <div class="mb-3">
                        <button name="submit" class="btn btn-primary" type="submit">submit</button>
                    </div>

                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
    
    
    
    
    
    
    

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
                        <span>Add Employee Attendance</span>
                    </a>
                </div>

              

                       <form class="ps-3 pe-3" action="{{URL::to('super_admin/attendance/submit')}}" id="edit_role_form" method="post">
                 @csrf
                  
 <input type="hidden" name="login_user_id" value="{{Auth::guard('web')->user()->id}}">

                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="emailaddress" class="form-label">Employee Id</label>
                        <input class="form-control"  type="text" id="employee_id" name="employee_id" value=""> 
                            </div>
                            <div class="col-md-6">
                                <label for="emailaddress" class="form-label">Employee Name</label>
                        <input class="form-control"  type="text" id="employee_name" name="employee_name" value=""> 
                            </div>
                            
                        </div>
                       
                    </div>
                     <div class="mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="emailaddress" class="form-label">Date</label>
                        <input class="form-control"  type="date" id="employee_date" name="employee_date" value=""> 
                            </div>
                            <div class="col-md-4">
                                <label for="emailaddress" class="form-label">Time In</label>
                        <input class="form-control"  type="time" id="time_in" name="time_in" value=""> 
                            </div>
                             <div class="col-md-4">
                                <label for="emailaddress" class="form-label">Time Out</label>
                        <input class="form-control"  type="time" id="time_out" name="time_out" value=""> 
                            </div>
                            
                        </div>
                       
                    </div>
                   
                    
                    
                     
                    
                    
                    

                   

            
                    

                    <div class="mb-3">
                        <button name="submit" class="btn btn-primary" type="submit">submit</button>
                    </div>

                </form>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


    
    


 











    <div class="row mt-5">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <h4 class="header-title">Employee ROle</h4>
                                        <a style="float:right;" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#bs-example-modal-lg">Add Employee Attendance</a>
                                        <p class="text-muted font-14">
                                            
                                        </p>

                                        
                                        <div class="tab-content">
                                            <div class="tab-pane show active" id="buttons-table-preview">
                                                <table id="example" class="table table-striped dt-responsive nowrap w-100">
                                                    <thead>
                                                        <tr>
                                                       
                             <th>Employee ID</th>
                            <th>Employee Name</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th>date</th>
                            <th>Status</th>
                            <th>Action</th>
                            
                              
                                                        </tr>
                                                    </thead>
                                                
                                                
                                                    <tbody>
                                                    @foreach($attendance as $attendance)
                            <tr>
                                <td>{{$attendance->employee_id}}</td>
                                <td>{{$attendance->employee_name}}</td>
                                <td>{{$attendance->time_in}}</td>
                                <td>{{$attendance->time_out}}</td>
                                <td>{{$attendance->employee_date}}</td>
                                <td></td>
                                <td>
                                    <div class="flex sm:justify-center items-center">
                                        <a class="flex items-center mr-3" href="attendance_edit/{{$attendance->id}}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square w-4 h-4 mr-1"><polyline points="9 11 12 14 22 4"></polyline>
                                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                            </svg>
                                            Edit
                                        </a>
                                        <a class="flex items-center text-theme-6" href="attendance_delete/{{$attendance->id}}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 w-4 h-4 mr-1"><polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17">
                                                </line><line x1="14" y1="11" x2="14" y2="17"></line>
                                            </svg>
                                            Delete
                                        </a>
                                    </div>
                                </td>
                               

                            </tr>

@endforeach

                                                    </tbody>
                                                </table>                                           
                                            </div> <!-- end preview-->
                                        
                                            
                                        </div> <!-- end tab-content-->
                                        
                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
                            </div><!-- end col-->
                        </div> <!-- end row-->
            



<script src="https://code.jquery.com/jquery-3.5.1.js"></script>

















<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#example_1').DataTable({
           
           "order": [[ 0, 'desc' ], [ 1, 'desc' ]] 
        });
    } );
    
    
    
    $('.click_add').on('click',function(){

                $('#edit_role_form')[0].reset();
                $('#edit_form_id').val($(this).attr('data-role_id'));
                $('#edit_form_title').val($(this).attr('data-role_title'));
            });
</script>

 
    @endsection