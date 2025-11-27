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
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Tours</a></li>
                                <li class="breadcrumb-item active">View Tours</li>
                            </ol>
                        </div>
                        <h4 class="page-title">View Tours</h4>
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
                                            <th>Name</th>
                                            <th>No of Pax</th>
                                            <th>Tour Dates</th>
                                            <th>Location</th>
                                            <th>Author</th>
                                            <th>Booking Status</th>
                                            <th>Status</th>
                                            <th style="width: 85px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $tours)
                                            <?php
                                                $id           = $tours['id'];
                                                $book_status  = $tours['book_status'];
                                                $tour_publish = $tours['tour_publish'];
                                            ?>
                                            <tr>
                                                <td><?php echo $id; ?></td>
                                                <td><?php echo $tours['title']; ?></td>
                                                <td>
                                                    @if(isset($tours['no_of_pax_days']))
                                                        <?php echo $tours['no_of_pax_days']; ?>
                                                    @else
                                                        <?php echo '0' ?>
                                                    @endif
                                                </td>
                                                <td><b>Start date : </b><?php echo $tours['start_date']; ?> <br>
                                                    <b>End date   : </b><?php echo $tours['end_date']; ?></td>
                                                <td><?php echo $tours['tour_location']; ?></td>
                                                <td><?php echo $tours['tour_author']; ?></td>                                                
                                                @if($book_status)
                                                    <td>
                                                        <span class="badge bg-success" style="font-size: 15px">Tour Booked</span>
                                                        <span class="badge bg-info" style="font-size: 15px" id="booked_tour_span" data-id="{{$id}}">View Booked Tours</span>
                                                    </td>
                                                @else
                                                    <td><span class="badge btn-danger" style="font-size: 15px">Not Booked yet</span></td>
                                                @endif
                                                @if($tour_publish == 0)
                                                  <td><a href="{{URL::to('super_admin/disable_tour')}}/{{$id}}"><span class="badge bg-success" style="font-size: 15px">Enable</span></a></td>
                                                @else
                                                  <td><a href="{{URL::to('super_admin/enable_tour')}}/{{$id}}"><span class="badge btn-danger" style="font-size: 15px">Disable</span></a></td>
                                                @endif
                                                <td>
                                                <div class="dropdown card-widgets">
                                                    <a style="float: right;" href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="dripicons-dots-3"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                                        <a href="{{URL::to('super_admin/edit_activities')}}/{{$id}}" class="dropdown-item"><i class="mdi mdi-account-edit me-1"></i>Edit</a>
                                                        <a href="{{URL::to('super_admin/delete_activities')}}/{{$id}}" class="dropdown-item"><i class="mdi mdi-check-circle me-1"></i>Delete</a>
                                                        <!--<a href="{{URL::to('super_admin/view_Chart')}}/{{$id}}" class="dropdown-item"  data-bs-toggle="modal" data-bs-target="#recieve_payment_modal"><i class="mdi mdi-account"></i>View Chart</a>-->
                                                        <!-- <a class="dropdown-item more_Tour_Details" data-id="{{$id}}" data-bs-toggle="modal" data-bs-target="#more_Tour_Details"><i class="mdi mdi-account"></i>More Tour Details</a> -->
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

<div id="recieve_payment_modal" class="modal fade" tabindex="-1" style="display: none;" aria-hidden="true">
   <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-colored-header bg-primary">
                <h4 class="modal-title" id="compose-header-modalLabel">Details</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- <div class="modal-body"> -->
            <div class="p-1">
                <div class="modal-body px-3 pt-3 pb-0">
                    <form action="{{ URL::to('')}}" method="post">
                        @csrf
                        
                        <div>
                            <div style="font-size: 40px;text-align: center;border: solid 1px white;color: black;">
                                Total : 40
                            </div>

                            <div style="padding: 15px;">
                                <h3 style="font-size: 30px;">Single</h3>
                                <div style="font-size: 20px;">
                                    <input type="radio" id="customRadiocolor1" name="customRadiocolor1" class="form-check-input" checked="">
                                </div>
                            </div>

                            <div style="padding: 15px;">
                                <h3 style="font-size: 30px;">Double</h3>
                                <div style="font-size: 20px;">
                                    <input type="radio" id="customRadiocolor2" name="customRadiocolor2" class="form-check-input" checked="">
                                    <input type="radio" id="customRadiocolor3" name="customRadiocolor3" class="form-check-input" checked="">
                                </div>
                            </div>

                            <div style="padding: 15px;">
                                <h3 style="font-size: 30px;">Triple</h3>
                                <div style="font-size: 20px;">
                                    <input type="radio" id="customRadiocolor4" name="customRadiocolor4" class="form-check-input" checked="">
                                    <input type="radio" id="customRadiocolor5" name="customRadiocolor5" class="form-check-input" checked="">
                                    <input type="radio" id="customRadiocolor6" name="customRadiocolor6" class="form-check-input" checked="">
                                </div>
                            </div>

                            <div style="padding: 15px;">
                                <h3 style="font-size: 30px;">Quad</h3>
                                <div style="font-size: 20px;">
                                    <input type="radio" id="customRadiocolor7" name="customRadiocolor7" class="form-check-input" checked="">
                                    <input type="radio" id="customRadiocolor8" name="customRadiocolor8" class="form-check-input" checked="">
                                    <input type="radio" id="customRadiocolor9" name="customRadiocolor9" class="form-check-input" checked="">
                                    <input type="radio" id="customRadiocolor10" name="customRadiocolor10" class="form-check-input" checked="">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
   </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    
    $(document).ready(function () {

        //More Tour Details
        $('#booked_tour_span').click(function() {
            const id = $(this).attr('data-id');
            $.ajax({
                url: 'more_Tour_Details/'+id,
                type: 'GET',
                data: {
                    "id": id
                },
                success:function(data) {
                    var a = data;
                    var booked_tour    = a['booked_tour'];
                    $('#booked_tour_span').empty();
                    $('#booked_tour_span').html(booked_tour);
                },
            });
        });

    });

</script>
@endsection