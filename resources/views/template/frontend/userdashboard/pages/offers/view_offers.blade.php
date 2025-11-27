

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
                        <span>Add Offers</span>
                    </a>
                </div>

              

                       <form class="ps-3 pe-3" action="{{URL::to('super_admin/submit_offers')}}" id="edit_role_form" method="post">
                 @csrf
                  


                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="emailaddress" class="form-label">From Date</label>
                        <input class="form-control"  type="date" id="employee_id" name="from_date" value=""> 
                            </div>
                            <div class="col-md-6">
                                <label for="emailaddress" class="form-label">Employee Name</label>
                        <input class="form-control"  type="date" id="employee_name" name="to_date" value=""> 
                            </div>
                            
                        </div>
                       
                    </div>
                     <div class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="emailaddress" class="form-label">Package Name</label>
                                <select class="form-control" name="package_name">
                                    <option value="">Select Package Name</option>
                                    @foreach($data as $data)
                                    <option value="{{$data->title ?? ''}}">{{$data->title ?? ''}}</option>
                                    @endforeach
                                </select>
                        
                            </div>
                            <div class="col-md-6">
                                <label for="emailaddress" class="form-label">Amount</label>
                        <input class="form-control"  type="text" id="time_in" name="amount" value=""> 
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

                                        <h4 class="header-title">Offers</h4>
                                        <a style="float:right;" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#bs-example-modal-lg">Add Offers</a>
                                        <p class="text-muted font-14">
                                            
                                        </p>

                                        
                                        <div class="tab-content">
                                            <div class="tab-pane show active" id="buttons-table-preview">
                                                <table id="example" class="table table-striped dt-responsive nowrap w-100">
                                                    <thead>
                                                        <tr>
                                                       
                             <th>From Date</th>
                            <th>To date</th>
                            <th>Package Name</th>
                            <th>Amount</th>
                            
                            <th>Action</th>
                            
                              
                                                        </tr>
                                                    </thead>
                                                
                                                
                                                    <tbody>
                                                    @foreach($offers as $offers)
                            <tr>
                                <td>{{$offers->from_date}}</td>
                                <td>{{$offers->to_date}}</td>
                                <td>{{$offers->package_name}}</td>
                                <td>{{$offers->amount}}</td>
                               
                                
                                <td>
                                    <div class="flex sm:justify-center items-center">
                                        <a class="flex items-center mr-3" href="{{URL::to('super_admin/edit_offers')}}/{{$offers->id}}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square w-4 h-4 mr-1"><polyline points="9 11 12 14 22 4"></polyline>
                                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                            </svg>
                                            Edit
                                        </a>
                                        <a class="flex items-center text-theme-6" href="{{URL::to('super_admin/delete_offers')}}/{{$offers->id}}">
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
            

 
    @endsection