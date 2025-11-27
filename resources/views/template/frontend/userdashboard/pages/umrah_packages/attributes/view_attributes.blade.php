@extends('template/frontend/userdashboard/layout/default')
 @section('content')
 <div class="row mt-5">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <h4 class="header-title">View Attributes</h4>
                                        <p class="text-muted font-14">
                                            
                                        </p>

                                        
                                        <div class="tab-content">
                                            <div class="tab-pane show active" id="buttons-table-preview">
                                                <table id="example3" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Attributes</th>
                                                           
                                                            <th>Edit</th>
                                                            <th>Delete</th>
                                                        </tr>
                                                    </thead>
                                                
                                                
                                                    <tbody>
                                                        @foreach($attributes as $attributes)
                                                        <tr>
                                                            <td>{{$attributes->id}}</td>
                                                            <td>{{$attributes->title}}</td>
                                                            
                                                            <td><a class="btn btn-info" href="{{URL::to('super_admin/edit_attributes')}}/{{$attributes->id}}">Edit</a></td>
                                                            <td><a class="btn btn-info" href="{{URL::to('super_admin/delete_attributes')}}/{{$attributes->id}}">Delete</a></td>
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