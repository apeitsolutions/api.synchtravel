@extends('template/frontend/userdashboard/layout/default')
 @section('content')
 <div class="row mt-5">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <h4 class="header-title">View Categories</h4>
                                        <p class="text-muted font-14">
                                            
                                        </p>

                                        
                                        <div class="tab-content">
                                            <div class="tab-pane show active" id="buttons-table-preview">
                                                <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Categories</th>
                                                           
                                                            <th>Edit</th>
                                                            <th>Delete</th>
                                                        </tr>
                                                    </thead>
                                                
                                                
                                                    <tbody>
                                                        @foreach($categories as $categories)
                                                        <tr>
                                                            <td>{{$categories->id}}</td>
                                                            <td>{{$categories->title}}</td>
                                                            
                                                            <td><a class="btn btn-info" href="{{URL::to('super_admin/edit_categories_activities')}}/{{$categories->id}}">Edit</a></td>
                                                            <td><a class="btn btn-info" href="{{URL::to('super_admin/delete_categories_activities')}}/{{$categories->id}}">Delete</a></td>
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