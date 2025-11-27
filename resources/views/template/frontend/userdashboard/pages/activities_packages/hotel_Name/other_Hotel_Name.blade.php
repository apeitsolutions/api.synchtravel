@extends('template/frontend/userdashboard/layout/default')
 @section('content')
 <div class="row mt-5">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <h4 class="header-title">View Hotel Names</h4>
                                        <p class="text-muted font-14">
                                            
                                        </p>

                                        
                                        <div class="tab-content">
                                            <div class="tab-pane show active" id="buttons-table-preview">
                                                <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Hotel Name</th>
                                                           
                                                            <th>Edit</th>
                                                            <th>Delete</th>
                                                        </tr>
                                                    </thead>
                                                
                                                
                                                    <tbody>
                                                        @foreach($other_Hotel_Name as $other_Hotel_Name)
                                                        <tr>
                                                            <td>{{$other_Hotel_Name->id}}</td>
                                                            <td>{{$other_Hotel_Name->other_Hotel_Name}}</td>
                                                            
                                                            <td><a class="btn btn-info" href="{{URL::to('super_admin/edit_Hotel_Names')}}/{{$other_Hotel_Name->id}}">Edit</a></td>
                                                            <td><a class="btn btn-info" href="{{URL::to('super_admin/delete_Hotel_Names')}}/{{$other_Hotel_Name->id}}">Delete</a></td>
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