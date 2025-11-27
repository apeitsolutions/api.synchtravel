  <?php

//print_r($employees);
//die();
?>


@extends('template/frontend/userdashboard/layout/default')
@section('content')

        <div class="card mt-3">
<div class="card-header">
                
           <div class="mb-4">
                    <a href="" class="text-success">
                        <span>Edit Task</span>
                    </a>
                </div>     
            </div>
            <div class="card-body">
                

              

                       <form class="ps-3 pe-3" action="{{URL::to('super_admin/edit_task_submit',[$tasks->id])}}" id="edit_role_form" method="post">
                 @csrf
                  


                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="emailaddress" class="form-label">Task Title</label>
                        <input class="form-control"  type="text" id="task_title" name="task_title" value="{{$tasks->task_title}}"> 
                            </div>
                            <div class="col-md-6">
                                <label for="emailaddress" class="form-label">Employee id</label>
                      
                        <select class="form-control" name="employee_id">
                            <option>Select Employee </option>
                            @foreach($admin_employee as $admin_employee)
                            <option value="{{$admin_employee->id}}">{{$admin_employee->first_name}}  {{$admin_employee->last_name}}</option>
                            @endforeach
                        </select>
                            </div>
                            
                        </div>
                       
                    </div>
                     <div class="mb-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="emailaddress" class="form-label">Task Discription</label>
                                <textarea class="form-control" name="task_discription" rows="5" cols="5">{{$tasks->task_discription}}</textarea>
                            </div>
                            
                             
                            
                        </div>
                       
                    </div>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="emailaddress" class="form-label">Task Comments</label>
                                <textarea class="form-control" name="task_comments" rows="5" cols="5">{{$tasks->task_comments}}</textarea>
                            </div>
                            
                             
                            
                        </div>
                       
                    </div>
                   
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="emailaddress" class="form-label">Task Assigned Date</label>
                        <input class="form-control"  type="date" id="task_assigned_date" name="task_assigned_date" value="{{$tasks->task_assigned_date}}"> 
                            </div>
                            <div class="col-md-4">
                                <label for="emailaddress" class="form-label">Expected Completion Date</label>
                        <input class="form-control"  type="date" id="expected_completion_date" name="expected_completion_date" value="{{$tasks->expected_completion_date}}"> 
                            </div>
                            <div class="col-md-4">
                                <label for="emailaddress" class="form-label">Completion Date</label>
                        <input class="form-control"  type="date" id="completion_date" name="completion_date" value="{{$tasks->completion_date}}"> 
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