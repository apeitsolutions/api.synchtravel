@extends('template/frontend/userdashboard/layout/default')
 @section('content')
 <div class="row mt-5">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <h4 class="header-title">View Umrah Packages</h4>
                                        <p class="text-muted font-14">
                                            
                                        </p>

                                        
                                        <div class="tab-content">
                                            <div class="tab-pane show active" id="buttons-table-preview">
                                                <table id="example" class="table table-striped dt-responsive nowrap w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>Package Name</th>
                                                            <th>Check In</th>
                                                            <th>Check Out</th>
                                                           
                                                            <th>Status</th>
                                                            <th>Edit</th>
                                                            <th>Delete</th>
                                                        </tr>
                                                    </thead>
                                                
                                                
                                                    <tbody>
                                                        @foreach($umrah_package as $umrah_package)
                                                        <tr>
                                                            <td>{{$umrah_package->package_name}}</td>
                                                            <td>{{$umrah_package->check_in}}</td>
                                                            <td>{{$umrah_package->check_out}}</td>
                                                            <td><?php
                                                            if($umrah_package->status == 0)
                                                            {
 
                                                                ?>
                                                                <form action="{{URL::to('super_admin/disable_umrah_package',[$umrah_package->id])}}" >
                                                                    <input type="hidden" name="">
                                                                    <button class="btn btn-info">Enable</button>
                                                                </form>



                                                                <?php
                                                            }
                                                            else{
                                                                ?>
                                                                <form action="{{URL::to('super_admin/enable_umrah_packages',[$umrah_package->id])}}">
                                                                    <input type="hidden" name="">
                                                                    <button class="btn btn-info" >Disable</button>
                                                                </form>
                                                             

                                                                <?php
                                                            }
                                                            ?></td>
                                                            <td><a class="btn btn-info" href="{{URL::to('super_admin/edit_umrah_package')}}/{{$umrah_package->id}}">Edit</a></td>
                                                            <td><a class="btn btn-info" href="{{URL::to('super_admin/delete_umrah_package')}}/{{$umrah_package->id}}">Delete</a></td>
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