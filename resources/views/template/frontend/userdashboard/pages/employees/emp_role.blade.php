

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

              

                       <form class="ps-3 pe-3" action="{{URL::to('super_admin/edit_roles')}}" id="edit_role_form" method="post">
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
                        <span>Add Role</span>
                    </a>
                </div>

              

                       <form class="ps-3 pe-3" action="{{URL::to('super_admin/add_roles')}}" method="post">
                 @csrf
                  
 <input type="hidden" name="login_user_id" value="{{Auth::guard('web')->user()->id}}">

                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="emailaddress" class="form-label">Roles Title</label>
                        <input class="form-control"  type="text" id="role_title" name="role_title" value=""> 
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
                                        <a style="float:right;" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#bs-example-modal-lg">Add</a>
                                        <p class="text-muted font-14">
                                            
                                        </p>

                                        
                                        <div class="tab-content">
                                            <div class="tab-pane show active" id="buttons-table-preview">
                                                <table id="example" class="table table-striped dt-responsive nowrap w-100">
                                                    <thead>
                                                        <tr>
                                                       
                            <th class="">roles ID</th>
                            <th class="">Title</th>
                             <th class="">Edit</th>
                            <th class="">Delete</th>
                            
                              
                                                        </tr>
                                                    </thead>
                                                
                                                
                                                    <tbody>
                                                    @foreach($roles as $roles)
                            <tr>
                                <td class="">{{$roles->id ?? ''}}</td>
                                <td class="">{{$roles->role_title ?? ''}}</td>
                                
                               
                               <td><a class="btn btn-info edit_role_btn" data-bs-toggle="modal" data-bs-target="#bs2-example-modal-lg" data-role_id="{{$roles->id ?? ''}}" data-role_title="{{$roles->role_title ?? ''}}">Edit</a></td>
                              <td><a class="btn btn-info" href="{{URL::to('super_admin/del_roles')}}/{{$roles->id}}">Delete</a></td> 
                               

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
    
    
    
    $('.edit_role_btn').on('click',function(){

                $('#edit_role_form')[0].reset();
                $('#edit_form_id').val($(this).attr('data-role_id'));
                $('#edit_form_title').val($(this).attr('data-role_title'));
            });
</script>

 
    @endsection