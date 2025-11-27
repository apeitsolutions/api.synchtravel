<?php

//print_r($employees);
//die();
?>


@extends('template/frontend/userdashboard/layout/default')
@section('content')


    @if(session()->has('success'))
        <div x-data="{ show: true }" x-show="show"
             class="flex justify-between items-center bg-yellow-200 relative text-yellow-600 py-3 px-3 rounded-lg">
            <div>
                <span class="font-semibold text-yellow-700">   {{session('success')}}</span>
            </div>
            <div>
                <button type="button" @click="show = false" class=" text-yellow-700">
                    <span class="text-2xl">&times;</span>
                </button>
            </div>
        </div>
    @endif
    
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
                        <span>Add Task</span>
                    </a>
                </div>

              

                       <form class="ps-3 pe-3" action="{{URL::to('super_admin/submit_task')}}" id="edit_role_form" method="post">
                 @csrf
                  


                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="emailaddress" class="form-label">Task Title</label>
                        <input class="form-control"  type="text" id="task_title" name="task_title" value=""> 
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
                                <textarea class="form-control" name="task_discription" rows="5" cols="5"></textarea>
                            </div>
                            
                             
                            
                        </div>
                       
                    </div>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="emailaddress" class="form-label">Task Comments</label>
                                <textarea class="form-control" name="task_comments" rows="5" cols="5"></textarea>
                            </div>
                            
                             
                            
                        </div>
                       
                    </div>
                   
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="emailaddress" class="form-label">Task Assigned Date</label>
                        <input class="form-control"  type="date" id="task_assigned_date" name="task_assigned_date" value=""> 
                            </div>
                            <div class="col-md-4">
                                <label for="emailaddress" class="form-label">Expected Completion Date</label>
                        <input class="form-control"  type="date" id="expected_completion_date" name="expected_completion_date" value=""> 
                            </div>
                            <div class="col-md-4">
                                <label for="emailaddress" class="form-label">Completion Date</label>
                        <input class="form-control"  type="date" id="completion_date" name="completion_date" value=""> 
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






    <div class="card mt-5">
        <div class="card-header">
            <h4 class="">
               View Task
            </h4>
            
            <a style="float:right;" href="{{URL::to('super_admin/add_task')}}"  data-bs-toggle="modal" data-bs-target="#bs-example-modal-lg" class="btn btn-info">Add Task</a>
            
        </div>
        <div class="">
            <div class="card-body">
                <div class="">
                    <table class="table" id="example4">
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>Employee ID</th>
                             <th>Task Title</th>
                            <th>Task Date</th>
                           
                            <th>Expected Completion Date</th>
                            <th>Completion Date</th>
                            
                            <th>Edit</th>
                            <th>Delete</th>
                            
                            
                            
                        </tr>
                        </thead>

                        <tbody>

@foreach($task as $task)

                            <tr>
                                <td>{{$task->id}}</td>
                                <td>{{$task->employee_id}}</td>                              
                                <td>
                                   {{$task->task_title}}
                                </td>
                                <td>{{$task->task_assigned_date}}</td>
                                <td>{{$task->expected_completion_date}}</td>
                                <td>{{$task->completion_date}}</td>
                                <td> <a href="{{URL::to('super_admin/employees_task_edit')}}/{{$task->id}}" class="btn btn-info">Edit</a> </td>
                                <td> <a href="{{URL::to('super_admin/del_tsk')}}/{{$task->id}}" class="btn btn-info">Delete</a> </td>
                               
                                

                            </tr>




@endforeach


                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>


    
@endsection



