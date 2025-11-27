

@extends('template/frontend/userdashboard/layout/default')
 @section('content')

 @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show" role="alert">
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    <strong>{{session()->get('success')}} </strong>
</div>
    @endif
    
  
    <div class="row mt-5">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <h4 class="header-title">Leaves</h4>
                                       
                                        <p class="text-muted font-14">
                                            
                                        </p>

                                        
                                        <div class="tab-content">
                                            <div class="tab-pane show active" id="buttons-table-preview">
                                                <table id="example" class="table table-striped dt-responsive nowrap w-100">
                                                    <thead>
                                                        <tr>
                                                       
                            <th>Employee Id</th>
                            <th>Employee Name</th>
                            <th>Employee Email</th>
                            <th>Employee Position</th>
                            <th>Pax Leave</th>
                            <th>From Date</th>
                            <th>To Date</th>
                            <th>Actions</th>
                            
                              
                                                        </tr>
                                                    </thead>
                                                
                                                
                                                    <tbody>
                                                    @foreach($leaves as $leaves)
                            <tr>
                                <td>{{$leaves->employee_id}}</td>
                                <td>{{$leaves->employee_name}}</td>
                                <td>{{$leaves->email}}</td>
                                <td>{{$leaves->position}}</td>
                                <td>{{$leaves->pax_leave}}</td>
                                <td>{{$leaves->form_date}}</td>
                                <td>{{$leaves->to_date}}</td>
                                <td>
                                    <a href="{{route('admin.approve', $leaves->id)}}" class="btn btn-info">
                                        Approve
                                    </a>
                                    <a href="{{route('admin.decline', $leaves->id)}}" class="btn btn-info">
                                        Reject
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
    
    
    
    $('.click_add').on('click',function(){

                $('#edit_role_form')[0].reset();
                $('#edit_form_id').val($(this).attr('data-role_id'));
                $('#edit_form_title').val($(this).attr('data-role_title'));
            });
</script>

 
    @endsection