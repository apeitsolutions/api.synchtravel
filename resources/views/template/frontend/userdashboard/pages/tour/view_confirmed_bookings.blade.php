@extends('template/frontend/userdashboard/layout/default')
@section('content')

  <style>
    .nav-link{
      color: #575757;
      font-size: 18px;
    }
  </style>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <section class="content" style="padding: 30px 50px 0px 50px;">
      

      <!-- Start Content-->
        <div class="container-fluid">
          <!-- start page title -->
          <div class="row">
              <div class="col-12">
                  <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Tour Bookings</a></li>
                            <li class="breadcrumb-item active">Confirmed Bookings</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Confirmed Bookings</h4>
                  </div>
              </div>
          </div>
          <!-- end page title --> 

          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <div class="row mb-2">
                    <div class="col-sm-5">
                      <!-- <a href="javascript:void(0);" class="btn btn-danger mb-2"><i class="mdi mdi-plus-circle me-2"></i> Confirmed Bookings</a> -->
                    </div>
                    <div class="col-sm-7">
                      <div class="text-sm-end">
                       <!--  <button type="button" class="btn btn-success mb-2 me-1"><i class="mdi mdi-cog-outline"></i></button>
                        <button type="button" class="btn btn-light mb-2 me-1">Import</button>
                        <button type="button" class="btn btn-light mb-2">Export</button> -->
                      </div>
                    </div><!-- end col-->
                  </div>

                  <div class="table-responsive">
                    <!-- <div id="example_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer"> -->
                      <div class="row">
                        <table class="table table-centered w-100 dt-responsive nowrap" id="example1">
                          <thead class="table-light">
                            <tr>
                                <th>Booking ID</th>
                                <th style="text-align: center;">Image</th>
                                <th class="all">Tour ID</th>
                                <th>Customer Name</th>
                                <th>Package Title</th>
                                <th>Adults</th>
                                <th>Childs</th>
                                <th>Total Amount</th>
                            </tr>
                          </thead>
                            <tbody>
                              @foreach ($data as $key => $value)
                                  @if($value->confirm == 1 && $value->pakage_type == 'tour')
                                    <tr>
                                      <td>{{ $value->booking_id }}</td>
                                      <td>
                                        <p class="m-0 d-inline-block align-middle font-16">
                                        <a href="#" class="text-body">{{ $value->image }}</a>
                                          <br/>
                                        <span class="text-warning mdi mdi-star"></span>
                                        <span class="text-warning mdi mdi-star"></span>
                                        <span class="text-warning mdi mdi-star"></span>
                                        <span class="text-warning mdi mdi-star"></span>
                                        <span class="text-warning mdi mdi-star"></span>
                                        </p>
                                      </td>
                                      <td class="sorting_1 tour_Id" id="tour_Id">{{ $value->tour_id }}</td>
                                      <td>{{ $value->name }} {{ $value->lname }}</td>
                                      <td>{{ $value->tour_name }}</td>
                                      <td>{{ $value->adults }}</td>
                                      <td>{{ $value->childs }}</td>
                                      <td>{{ $value->price }}</td>
                                    </tr>
                                  @else
                                  @endif
                              @endforeach
                            </tbody>
                        </table>
                      </div>
                    <!-- </div> -->
                  </div>
                </div>
              </div>
            </div>
          </div>           
        </div> 
      <!-- container -->
    </section>

    <!-- /.Dashboard -->
  </div>


  

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

@endsection
