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
    <!-- Main content -->
    <section class="content" style="padding: 10px 20px 0px 20px">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-primary">
              <div class="inner">
                <h3>150</h3>

                <p>Total Flights</p>
              </div>
              <div class="icon">
                <i class="ion ion-plane"></i>
              </div>
              <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>53<sup style="font-size: 20px">%</sup></h3>

                <p>Total Hotels</p>
              </div>
              <div class="icon">
                <i class="ion ion-calculator"></i>
              </div>
              <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box" style="background-color: #d262e3!important;">
              <div class="inner">
                <h3>44</h3>

                <p>Total Transfer</p>
              </div>
              <div class="icon">
                <i class="ion-android-car"></i>
              </div>
              <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>65</h3>

                <p>Commision</p>
              </div>
              <div class="icon">
                <i class="ion ion-cash"></i>
              </div>
              <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
            </div>
          </div>
          <!-- ./col -->
        </div>
      </div>
    </section>
    <!-- /.content -->

    <!-- Dashboard -->
    <section class="content" style="padding: 30px 50px 0px 50px;">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12 col-6">
            <nav class="breadcrumb push">
              <a class="breadcrumb-item" href="#">Dashboard</a> 
              <span class="breadcrumb-item active">View Bookings</span>
            </nav>
          </div>
        </div>
      </div>
    </section>

    <section class="content" style="padding: 30px 50px 0px 50px;">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12 col-6">
            <div class="panel-body padding-bottom-none">
              <div class="block-content block-content-full">
                <div id="example_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                  <div class="row">
                    <div class="col-sm-12">
                      <table class="table table-bordered table-striped table-vcenter dataTable no-footer" id="myTable" role="grid" aria-describedby="example_info">
                        <thead>
                          <tr role="row">
                            <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;">Name</th>
                            <th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="email: activate to sort column ascending" style="width: 175.073px;">Prepared By</th>
                            <th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="email: activate to sort column ascending" style="width: 175.073px;">email</th>
                            <th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Date: activate to sort column ascending" style="width: 138.958px;">Date</th>
                            <th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending" style="width: 147.118px;">Phone</th>
                            <!-- <th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Validity: activate to sort column ascending" style="width: 143.438px;">Validity</th>
                            <th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Options: activate to sort column ascending" style="width: 175.642px;">Options</th> -->
                          </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $value)
                          @if($value->confirm == 1)
                            <tr role="row" class="odd">
                              <!-- <td>b5c7a7ced6fda207e232</td> -->
                              <td class="sorting_1">{{ $value->f_name }}</td>
                               <td>{{ $value->quotation_prepared }}</td>
                              <td>{{ $value->email }}</td>
                              <td>{{ $value->created_at }}</td>
                              <td>
                                <ul>
                                  <li>{{ $value->contact_landline }}</li>
                                  <li>{{ $value->mobile }}</li>
                                  <li>{{ $value->contact_work }}</li>
                                </ul>
                              </td>
                              <!-- <td>
                                <p>{{ $value->created_at }}</p>
                                <input value="{{ $value->created_at->diffForHumans() }}" id="expireQuotation" style="background-color: #d3d9df;border: 0"></input>
                              </td>
                              <td>
                                <a data-toggle="modal" data-target="#exampleModalCenter"  data-id="{{ $value->id }}" class="fetchorderdetails detail-btn" data-uniqid="b5c7a7ced6fda207e232">View</a>|  
                                <a href="{{ route('edit_Quotations',$value->id) }}">Edit Quatation</a> |
                                <a href="#">Confirm</a> 
                              </td> -->
                            </tr>
                          @else
                            
                          @endif
                        @endforeach  
                        </tbody>
                      </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- /.Dashboard -->
  </div>


  

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
    
    $(document).ready(function () {

      //Expire Quotation
      var a = $('#expireQuotation').val();
      console.log(a);

    });

  </script>

@endsection
