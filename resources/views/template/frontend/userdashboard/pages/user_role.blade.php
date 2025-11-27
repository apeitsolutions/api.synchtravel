

@extends('template/frontend/userdashboard/layout/default')
 @section('content')

 @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show" role="alert">
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    <strong>{{session()->get('success')}} </strong>
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
                        <span>Add Admins</span>
                    </a>
                </div>

              

                       <form class="ps-3 pe-3" action="{{URL::to('super_admin/add_user_permission')}}"  method="post">
                 @csrf
                  
 <!--<input type="hidden" name="login_user_id" value="{{Auth::guard('web')->user()->id}}">-->

                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="emailaddress" class="form-label">Name</label>
                        <input class="form-control"  type="text" id="employee_id" name="name" value=""> 
                            </div>
                            <div class="col-md-6">
                                <label for="emailaddress" class="form-label">Title</label>
                        <input class="form-control"  type="text" id="employee_name" name="title" value=""> 
                            </div>
                            
                        </div>
                       
                    </div>
                     <div class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="emailaddress" class="form-label">Email</label>
                        <input class="form-control"  type="email" id="employee_date" name="email" value=""> 
                            </div>
                            <div class="col-md-6">
                                <label for="emailaddress" class="form-label">password</label>
                        <input class="form-control"  type="password" id="time_in" name="password" value=""> 
                            </div>
                             
                            
                        </div>
                       
                    </div>
                    
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-12">
                               <div class="col-span-12 sm:col-span-12 mt-2">
                                <label><b>Permissions</b></label>
                            </div>
                            <div class="col-span-12 sm:col-span-3">
                                 <input type="checkbox" name="user_permission[umrah_package]" class="input border mr-2" id="vertical-checkbox-chris-evans">
                                <label class="cursor-pointer select-none" for="vertical-checkbox-chris-evans">Umrah Package</label>
                            </div>
                            <div class="col-span-12 sm:col-span-3">
                                <input type="checkbox" name="user_permission[tour_package]" class="input border mr-2" id="vertical-checkbox-liam-neeson">
                                <label class="cursor-pointer select-none" for="vertical-checkbox-liam-neeson">Tour Package</label>
                            </div>
                            <div class="col-span-12 sm:col-span-3">
                                <input type="checkbox" name="user_permission[customer_subcription]" class="input border mr-2" id="vertical-checkbox-daniel-craig">
                                <label class="cursor-pointer select-none" for="vertical-checkbox-daniel-craig">Customer Subcription</label>
                            </div>
                            
                            
                            <div class="col-span-12 sm:col-span-3">
                                 <input type="checkbox" name="user_permission[accounts]" class="input border mr-2" id="vertical-checkbox-daniel-craig">
                                <label class="cursor-pointer select-none" for="vertical-checkbox-daniel-craig">Accounts</label>
                            </div>
                            <div class="col-span-12 sm:col-span-3">
                                <input type="checkbox" name="user_permission[visa]" class="input border mr-2" id="vertical-checkbox-daniel-craig">
                                <label class="cursor-pointer select-none" for="vertical-checkbox-daniel-craig">Visa</label>
                            </div>
                            <div class="col-span-12 sm:col-span-3">
                                <input type="checkbox" name="user_permission[arrival_departure]" class="input border mr-2" id="vertical-checkbox-daniel-craig">
                                <label class="cursor-pointer select-none" for="vertical-checkbox-daniel-craig">Arrivals & Departure</label>
                            </div>
                            
                            <div class="col-span-12 sm:col-span-3">
                                <input type="checkbox" name="user_permission[support_ticket]" class="input border mr-2" id="vertical-checkbox-daniel-craig">
                                <label class="cursor-pointer select-none" for="vertical-checkbox-daniel-craig">Support Ticket</label>
                            </div>
                            <div class="col-span-12 sm:col-span-3">
                                <input type="checkbox" name="user_permission[hrms]" class="input border mr-2" id="vertical-checkbox-daniel-craig">
                                <label class="cursor-pointer select-none" for="vertical-checkbox-daniel-craig">HRMS</label>
                            </div>
                            <div class="col-span-12 sm:col-span-3">
                                <input type="checkbox" name="user_permission[settings]" class="input border mr-2" id="vertical-checkbox-daniel-craig">
                                <label class="cursor-pointer select-none" for="vertical-checkbox-daniel-craig">Settings</label>
                            </div>
                            <div class="col-span-12 sm:col-span-3">
                                <input type="checkbox" name="user_permission[offers]" class="input border mr-2" id="vertical-checkbox-daniel-craig">
                                <label class="cursor-pointer select-none" for="vertical-checkbox-daniel-craig">Offers</label>
                            </div>
                             <div class="col-span-12 sm:col-span-3">
                                <input type="checkbox" name="user_permission[email_marketing]" class="input border mr-2" id="vertical-checkbox-daniel-craig">
                                <label class="cursor-pointer select-none" for="vertical-checkbox-daniel-craig">Email Marketing</label>
                            </div>
                            
                            <div class="col-span-12 sm:col-span-3">
                                <input type="checkbox" name="user_permission[manage_user_roles]" class="input border mr-2" id="vertical-checkbox-daniel-craig">
                                <label class="cursor-pointer select-none" for="vertical-checkbox-daniel-craig">Manage User Permissions</label>
                            </div>
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
                        <span>Edit</span>
                    </a>
                </div>

              

                       <form class="ps-3 pe-3" action="{{URL::to('super_admin/edit_user_permission')}}" id="edit_user_role_form" method="post">
                 @csrf
                   <input type="hidden" name="user_id" id="edit_form_id">
 <!--<input type="hidden" name="login_user_id" value="{{Auth::guard('web')->user()->id}}">-->

                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="emailaddress" class="form-label">Name</label>
                        <input class="form-control"  type="text" id="edit_form_name" name="name" value=""> 
                            </div>
                            <div class="col-md-6">
                                <label for="emailaddress" class="form-label">Title</label>
                        <input class="form-control"  type="text" id="edit_form_title" name="title" value=""> 
                            </div>
                            
                        </div>
                       
                    </div>
                     <div class="mb-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="emailaddress" class="form-label">Email</label>
                        <input class="form-control"  type="email" id="edit_form_email" name="email" value=""> 
                            </div>
                        <!--    <div class="col-md-6">-->
                        <!--        <label for="emailaddress" class="form-label">password</label>-->
                        <!--<input class="form-control"  type="password" id="time_in" name="password" value=""> -->
                        <!--    </div>-->
                             
                            
                        </div>
                       
                    </div>
                    
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-12">
                               <div class="col-span-12 sm:col-span-12 mt-2">
                                <label><b>Permissions</b></label>
                            </div>
                            <div class="col-span-12 sm:col-span-3">
                                 <input type="checkbox" name="user_permission[umrah_package]" class="input border mr-2 umrah_package" id="vertical-checkbox-chris-evans">
                                <label class="cursor-pointer select-none" for="vertical-checkbox-chris-evans">Umrah Package</label>
                            </div>
                            <div class="col-span-12 sm:col-span-3">
                                <input type="checkbox" name="user_permission[tour_package]" class="input border mr-2 tour_package" id="vertical-checkbox-liam-neeson">
                                <label class="cursor-pointer select-none" for="vertical-checkbox-liam-neeson">Tour Package</label>
                            </div>
                            <div class="col-span-12 sm:col-span-3">
                                <input type="checkbox" name="user_permission[customer_subcription]" class="input border mr-2 customer_subcription" id="vertical-checkbox-daniel-craig">
                                <label class="cursor-pointer select-none" for="vertical-checkbox-daniel-craig">Customer Subcription</label>
                            </div>
                            
                            
                            <div class="col-span-12 sm:col-span-3">
                                 <input type="checkbox" name="user_permission[accounts]" class="input border mr-2 accounts" id="vertical-checkbox-daniel-craig">
                                <label class="cursor-pointer select-none" for="vertical-checkbox-daniel-craig">Accounts</label>
                            </div>
                            <div class="col-span-12 sm:col-span-3">
                                <input type="checkbox" name="user_permission[visa]" class="input border mr-2 visa" id="vertical-checkbox-daniel-craig">
                                <label class="cursor-pointer select-none" for="vertical-checkbox-daniel-craig">Visa</label>
                            </div>
                            <div class="col-span-12 sm:col-span-3">
                                <input type="checkbox" name="user_permission[arrival_departure]" class="input border mr-2 arrival_departure" id="vertical-checkbox-daniel-craig">
                                <label class="cursor-pointer select-none" for="vertical-checkbox-daniel-craig">Arrivals & Departure</label>
                            </div>
                            
                            <div class="col-span-12 sm:col-span-3">
                                <input type="checkbox" name="user_permission[support_ticket]" class="input border mr-2 support_ticket" id="vertical-checkbox-daniel-craig">
                                <label class="cursor-pointer select-none" for="vertical-checkbox-daniel-craig">Support Ticket</label>
                            </div>
                            <div class="col-span-12 sm:col-span-3">
                                <input type="checkbox" name="user_permission[hrms]" class="input border mr-2 hrms" id="vertical-checkbox-daniel-craig">
                                <label class="cursor-pointer select-none" for="vertical-checkbox-daniel-craig">HRMS</label>
                            </div>
                            <div class="col-span-12 sm:col-span-3">
                                <input type="checkbox" name="user_permission[settings]" class="input border mr-2 settings" id="vertical-checkbox-daniel-craig">
                                <label class="cursor-pointer select-none" for="vertical-checkbox-daniel-craig">Settings</label>
                            </div>
                            <div class="col-span-12 sm:col-span-3">
                                <input type="checkbox" name="user_permission[offers]" class="input border mr-2 offers" id="vertical-checkbox-daniel-craig">
                                <label class="cursor-pointer select-none" for="vertical-checkbox-daniel-craig">Offers</label>
                            </div>
                             <div class="col-span-12 sm:col-span-3">
                                <input type="checkbox" name="user_permission[email_marketing]" class="input border mr-2 email_marketing" id="vertical-checkbox-daniel-craig">
                                <label class="cursor-pointer select-none" for="vertical-checkbox-daniel-craig">Email Marketing</label>
                            </div>
                            
                            <div class="col-span-12 sm:col-span-3">
                                <input type="checkbox" name="user_permission[manage_user_roles]" class="input border mr-2 manage_user_roles" id="vertical-checkbox-daniel-craig">
                                <label class="cursor-pointer select-none" for="vertical-checkbox-daniel-craig">Manage User Permissions</label>
                            </div>
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

                                        <h4 class="header-title">Mange User Role</h4>
                                        <a style="float:right;" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#bs-example-modal-lg">Add Admins</a>
                                        <p class="text-muted font-14">
                                            
                                        </p>

                                        
                                        <div class="tab-content">
                                            <div class="tab-pane show active" id="buttons-table-preview">
                                                <table id="example" class="table table-striped dt-responsive nowrap w-100">
                                                    <thead>
                                                        <tr>
                                                       
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Title</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                            <th>Action</th>
                            
                              
                                                        </tr>
                                                    </thead>
                                                
                                                
                                                    <tbody>
                             @foreach($users as $key => $value)
                                        <tr>
                                            <td>{{$value->id ?? ''}}</td>
                                            <td>{{$value->name ?? ''}}</td>
                                            <td>{{$value->title ?? ''}}</td>
                                            <td>{{$value->email ?? ''}}</td>
                                            <td class=""> 
                                                <?php
                                                if($value->is_active==0)
                                                {
                                                    ?>
                                                    
                                                    
                                                    <form action="{{URL::to('super_admin/inactivate_user',[$value->id])}}" >
                                                                    <input type="hidden" name="">
                                                                    <button class="btn btn-info">Active</button>
                                                                </form>
                                                    
                                                    <?php
                                                    
                                                }
                                                else
                                                {
                                                    ?>
                                                    
                                                    <form action="{{URL::to('super_admin/activate_user',[$value->id])}}" >
                                                                    <input type="hidden" name="">
                                                                    <button class="btn btn-info">Inctive</button>
                                                                </form>
                                                    
                                                    <?php
                                                    
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <a href="javascript:void(0)" class="btn btn-info user_edit_role_btn" data-bs-toggle="modal" data-bs-target="#bs2-example-modal-lg" data-user_id="{{$value->id}}" data-user_name="{{$value->name}}" data-user_email="{{$value->email}}" data-user_title="{{$value->title}}" data-user_permission="{{$value->permissions}}" data-user_is_active="{{$value->is_active}}">
                                                   Edit
                                                </a>
                                                <a href="{{URL::to('super_admin/mange_user_role_delete')}}/{{$value->id}}" class="btn btn-info">
                                                  delete
                                                </a>
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
    
    
    
    $('.user_edit_role_btn').on('click',function(){

                $('#activate_user_btn').hide(); 
                $('#inactivate_user_btn').hide();
                $('#edit_user_role_form')[0].reset();
                $('#edit_form_id').val($(this).attr('data-user_id'));
                $('#edit_form_name').val($(this).attr('data-user_name'));
                $('#edit_form_title').val($(this).attr('data-user_title'));
                $('#edit_form_email').val($(this).attr('data-user_email'));
                var permissions = JSON.parse($(this).attr('data-user_permission'));
                if(permissions.umrah_package){$(".umrah_package").prop('checked', true);}
                if(permissions.tour_package){$(".tour_package").prop('checked', true);}
                if(permissions.customer_subcription){$(".customer_subcription").prop('checked', true);}
                
                
                if(permissions.accounts){$(".accounts").prop('checked', true);}
                if(permissions.visa){$(".visa").prop('checked', true);}
                if(permissions.arrival_departure){$(".arrival_departure").prop('checked', true);}
               
                if(permissions.support_ticket){$(".support_ticket").prop('checked', true);}
                if(permissions.hrms){$(".hrms").prop('checked', true);}
                if(permissions.settings){$(".settings").prop('checked', true);}
                if(permissions.offers){$(".offers").prop('checked', true);}
                if(permissions.manage_user_roles){$(".manage_user_roles").prop('checked', true);}
                var is_active = $(this).attr('data-user_is_active');
                if(is_active == 1){$('#inactivate_user_btn').show();}else{$('#activate_user_btn').show();}
            });
</script>

 
    @endsection