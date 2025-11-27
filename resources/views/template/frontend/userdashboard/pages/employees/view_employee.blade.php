@extends('template/frontend/userdashboard/layout/default')
@section('content')
    @if(session()->has('success'))
    
    
    
    <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show" role="alert">
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    <strong>{{session()->get('success')}} </strong>
</div>
    
    
    
       
    @endif
    
    
    
    
     <div class="row mt-5">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <h4 class="header-title">Employee</h4>
                                        <p class="text-muted font-14">
                                            
                                        </p>

                                        
                                        <div class="tab-content">
                                            <div class="tab-pane show active" id="buttons-table-preview">
                                                <table id="example" class="display nowrap" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                         <th >E.ID</th>
                                                            <th >Photo</th>
                                                            <th >Name</th>
                                                            <!-- <th >Company</th> -->
                                                            <th >Position</th>
                                                            <th >Salary</th>
                                                            <th >Schedule</th>
                                                            <th >Member Since</th>
                                                            <th >Location</th>
                                                            <th >Edit</th>
                                                            <th >Delete</th>
                                                        </tr>
                                                    </thead>
                                                
                                                
                                                    <tbody>
@foreach($employees as $employees)
                            <tr>
                                <td >{{$employees->id}}</td>
                                <td >
                                    <div class="w-10 h-10 image-fit zoom-in -ml-5">
                                        @if(employee_image_exists($employees->image))
                                        <img alt="Image not found" class="tooltip rounded-full" src="{{ asset('public/uploads/employee_imgs/'. $employees->image) }}">
                                        @else
                                        <img alt="Image not found" class="tooltip rounded-full" src="{{ asset('public/uploads/employee_imgs')}}/149071.png">
                                        @endif
                                    </div>
                                </td>
                                <td >{{$employees->first_name}} {{$employees->last_name}}</td>
                                <!-- <td >{{--$employees->emp_company_name--}}</td> -->
                                <td >{{$employees->position}}</td>
                                <td >{{$employees->salary}}</td>
                                <td >{{$employees->schedule}}</td>
                                <td >{{date('d-m-Y', strtotime($employees->created_at))}}</td>
                                <td >
                                    <a href="view_location/{{$employees->id}}" target="_blank" >
                                        <div class="col-span-6 sm:col-span-3 lg:col-span-2 xl:col-span-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                 stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin mx-auto"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                                <circle cx="12" cy="10" r="3"></circle>
                                            </svg>
                                           
                                        </div>
                                    </a>
                                </td>
                                <td >
                                    
                                        <a href="{{URL::to('super_admin/employees_edit')}}/{{$employees->id}}" class="btn btn-info">Edit</a>
                                       
                                        
                                    
                                </td>
                                <td><a href="{{URL::to('super_admin/employees_delete')}}/{{$employees->id}}" class="btn btn-info">Delete</a></td>

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
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script>
   $(document).ready(function () {
    $('#example').DataTable({
        // scrollX: true,
    });
}); 
</script>



 

    @endsection

