

@extends('template/frontend/userdashboard/layout/default')
 @section('content')

 @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show" role="alert">
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    <strong>{{session()->get('success')}} </strong>
</div>
    @endif
    
    
    

    
    
    
    
    
    
    

        <div class="card">
<div class="card-header">
                <h4 class="modal-title" id="myLargeModalLabel"></h4>
               
            </div>
            <div class="card-body">
                <div class="mt-2 mb-4">
                    <a href="" class="text-success">
                        <span>Edit Offers</span>
                    </a>
                </div>

              

                       <form class="ps-3 pe-3" action="{{URL::to('super_admin/submit_edit_offers',[$offers->id])}}" id="edit_role_form" method="post">
                 @csrf
                  


                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="emailaddress" class="form-label">From Date</label>
                        <input class="form-control"  type="date" id="employee_id" name="from_date" value="{{$offers->from_date}}"> 
                            </div>
                            <div class="col-md-6">
                                <label for="emailaddress" class="form-label">Employee Name</label>
                        <input class="form-control"  type="date" id="employee_name" name="to_date" value="{{$offers->to_date}}"> 
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
                        <input class="form-control"  type="text" id="time_in" name="amount" value="{{$offers->amount}}"> 
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