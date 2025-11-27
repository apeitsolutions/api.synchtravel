
<?php

//print_r($data);die();

?>

@extends('template/frontend/userdashboard/layout/default')
@section('content')

    <?php $currency=Session::get('currency_symbol'); ?>
    
    <style>
        .nav-link{
          color: #575757;
          font-size: 18px;
        }
    </style>

    <div class="content-wrapper">
        
        
        
        <section class="content" style="padding: 30px 50px 0px 50px;">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12 col-6">
                <nav class="breadcrumb push">
                    <a class="breadcrumb-item" href="#">Dashboard</a> 
                    <span class="breadcrumb-item active">View Agent Invoices</span>
                </nav>
              </div>
            </div>
          </div>
        </section>
        
        <section class="content" style="padding: 10px 20px 0px 20px">
          <div class="container-fluid">
            <div class="row">
            
              <!-- ./col -->
              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-success" style="height: 184px;text-align: center;padding: 15px 0px 0px 0px;background-color: #313a46 !important;color: white;">
                  <div class="icon mb-2">
                    <span class="iconify" data-icon="uil-dollar-sign-alt" data-width="70"></span>
                  </div>
                  <div class="inner">
                    <h3 id="total_revenue"></h3>
    
                    <p>Total Amount</p>
                  </div>
                  
                </div>
              </div>
              <!-- ./col -->
              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box" style="height: 184px;text-align: center;padding: 15px 0px 0px 0px;background-color: #313a46 !important;color: white;">
                  <div class="icon mb-2">
                    <span class="iconify" data-icon="uil-dollar-sign-alt" data-width="70"></span>
                  </div>
                  <div class="inner">
                    <h3 id="total_paid"></h3>
    
                    <p>Total Paid</p>
                  </div>
                  
                </div>
              </div>
              <!-- ./col -->
              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info" style="height: 184px;text-align: center;padding: 15px 0px 0px 0px;background-color: #313a46 !important;color: white;">
                  <div class="icon mb-2">
                    <span class="iconify" data-icon="uil-dollar-sign-alt" data-width="70"></span>
                  </div>
                  <div class="inner">
                    <h3 id="outstanding"></h3>
    
                    <p>Outstanding</p>
                  </div>
                  
                </div>
              </div>
              
                <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-primary" onclick="manageOverPaid('')" data-bs-toggle="modal" data-bs-target="#exampleModal" style="height: 184px;text-align: center;padding: 15px 0px 0px 0px;background-color: #313a46 !important;color: white;">
                  <div class="icon mb-2">
                    <!-- <i class="mdi mdi-eye me-1"></i> -->
                    <span class="iconify" data-icon="uil-dollar-sign-alt" data-width="70"></span>
                  </div>
                  <div class="inner">
                    
                    <p>Wallet Amount</p>
    
                    <h3 id="over_paid"></h3>
    
                  </div>
                  
                </div>
              </div>
              <!-- ./col -->
            </div>
          </div>
        </section>
    
        <section class="content" style="padding: 30px 50px 0px 50px;">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-6">
                        <h4>Supplier Stats Details</h4>
                        <div class="panel-body padding-bottom-none">
                            <div class="block-content block-content-full">
                                <div id="example_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table style="" class="table nowrap example1 dataTable no-footer" id="myTable" role="grid" aria-describedby="example_info">
                                                
                                                <thead>
                                                    <tr role="row">
                                                        <th>Sr</th>
                                                        <th>Supplier Id</th>
                                                        <th>Company Name</th>
                                                        <th>Flight Id</th>
                                                        <th>Total Amount</th>
                                                        <th>Paid Amount</th>
                                                        <th>Remaning Amount</th>
                                                        <th>Over Paid</th>
                                                  
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @isset($stats_data->supplier_seats_booking)
                                                        <?php 
                                                            $total_amount = 0;
                                                            $paid_amount = 0;
                                                        ?>
                                                        @foreach($stats_data->supplier_seats_booking as $seat_res)
                                                        <tr role="row">
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $stats_data->supplier_id }}</td>
                                                            <td>{{ $stats_data->supplier_name }}</td>
                                                            <td>{{ $seat_res->id }}</td>
                                                            <td>{{ $seat_res->flight_total_price }}</td>
                                                            <td>{{ $seat_res->flight_paid_amount }}</td>
                                                            <td>{{ $seat_res->flight_total_price - $seat_res->flight_paid_amount }}</td>
                                                            <td>{{ $seat_res->over_paid_amount }}</td>

                                                        </tr>
                                                        
                                                        <?php 
                                                            $total_amount += $seat_res->flight_total_price;
                                                            $paid_amount += $seat_res->flight_paid_amount;
                                                        ?>
                                                        @endforeach
                                                    @endisset
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        
        <!-- Modal -->
   
        
        
    </div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  
    <script>
    
  
    
        $(document).ready(function () {
          
            //DataTable
            $('#myTable').DataTable({
                scrollX: true,
            });
            
            //View Modal Single Quotation
          
        });

    </script>
    
    <script>
    
        var totalSale = '<?php echo $total_amount; ?>';
        var totalPaid = '<?php echo $paid_amount; ?>';
        var totalRemaning = '<?php echo $total_amount - $paid_amount; ?>';
        var totalOverpaid = '<?php echo $stats_data->supplier_wallet; ?>';
        
        $('#total_revenue').html(totalSale);
        $('#total_paid').html(totalPaid);
        $('#outstanding').html(totalRemaning);
        $('#over_paid').html(totalOverpaid);
        
    </script>

@endsection
