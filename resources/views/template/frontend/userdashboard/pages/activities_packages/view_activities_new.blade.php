@extends('template/frontend/userdashboard/layout/default')
@section('content')
    


<div class="content-wrapper">
    <section class="content" style="padding: 30px 50px 0px 50px;">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Activity</a></li>
                                <li class="breadcrumb-item active">View Activity</li>
                            </ol>
                        </div>
                        <h4 class="page-title">View Activity</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-sm-5">
                                </div>
                            <div class="col-sm-7">
                                <div class="text-sm-end">
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <div class="row">
                                <table class="table table-centered w-100 dt-responsive nowrap" id="example1">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Customer Name</th>
                                            <th>Name</th>
                                            <th>Duration (Hours)</th>
                                            <th>Activity Dates</th>
                                            <th>Location</th>
                                            <th>Author</th>
                                           
                                            <th class="d-none" style="width: 85px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php // dd($activities); ?>
                                        @foreach ($activities as $activity_res)
                                            <tr>
                                                <td>{{ $activity_res->id }}</td>
                                                <td>
                                                    @if(isset($all_Users) && $all_Users != null && $all_Users != '')
                                                        @foreach($all_Users as $all_Users_value)
                                                            @if($activity_res->customer_id == $all_Users_value->id)
                                                                <b>{{ $all_Users_value->name }}</b>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td>{{ $activity_res->title }}</td>
                                                <td>{{ $activity_res->duration }}</td>
                                                <td>{{ $activity_res->activity_date }}</td>
                                                <td>{{ $activity_res->location }}</td>
                                                <td>{{ $activity_res->package_author }}</td>                                                
                                               
                                                <td class="d-none">
                                                <div class="dropdown card-widgets">
                                                    <a style="float: right;" href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="dripicons-dots-3"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                                        <a href="{{URL::to('super_admin/edit_activities')}}/{{ $activity_res->id }}" class="dropdown-item"><i class="mdi mdi-account-edit me-1"></i>Edit</a>
                                                        <a href="{{URL::to('super_admin/delete_activities')}}/{{ $activity_res->id }}" class="dropdown-item"><i class="mdi mdi-check-circle me-1"></i>Delete</a>
                                                        <a href="{{URL::to('super_admin/book_activity')}}/{{ $activity_res->id }}" class="dropdown-item"><i class="mdi mdi-check-circle me-1"></i>Book Now</a>
                                                    </div>
                                                </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>           
            </div>
        </div> 
    </section>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    


</script>
@endsection