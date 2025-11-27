@extends('template/frontend/userdashboard/layout/default')
@section('content')

    <?php $currency=Session::get('currency_symbol');?>
    
    <style>
        .nav-link{
          color: #575757;
          font-size: 18px;
        }
    </style>

    <div class="content-wrapper">
        
        <section class="content" >
            <div class="container-fluid">
                <nav class="breadcrumb push">
                    <a class="breadcrumb-item" href="#">Dashboard</a> 
                    <span class="breadcrumb-item active">View Agent Invoices</span>
                </nav>
            </div>
        </section>
        
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-12">
                        <h4>All Notification Details</h4>
                        <div class="panel-body padding-bottom-none">
                            <div class="block-content block-content-full">
                                <div class="table-responsive">
                                    <div id="example_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                                   
                                        <table  class="table nowrap example1 dataTable no-footer" id="myTable" role="grid" aria-describedby="example_info">
                                                
                                                <thead class="theme-bg-clr">
                                                    <tr role="row">
                                                        <th style="text-align: center;">Sr No.</th>
                                                        <th style="text-align: center;">Id</th>
                                                        <th style="text-align: center;">Customer Id</th>
                                                        <th style="text-align: center;">Customer Name</th>
                                                        <th style="text-align: center;">Type of Notification</th>
                                                        <th style="text-align: center;">Total Payable</th>
                                                        <th style="text-align: center;">Option</th>
                                                    </tr>
                                                </thead>
                                                <tbody style="text-align: center;">
                                                    <?php $x = 1; ?>
                                                    @if(isset($all_notification_detail) && $all_notification_detail != null && $all_notification_detail != '')
                                                        @foreach($all_notification_detail as $all_notification_detailS)
                                                            <tr role="row" class="odd">
                                                                <td>{{ $x }}</td>
                                                                <td>{{ $all_notification_detailS->type_of_notification_id }} </td>
                                                                <td>{{ $all_notification_detailS->customer_id }} </td>
                                                                <td>
                                                                    @if($all_notification_detailS->notification_creator_name != '-1')
                                                                        {{ $all_notification_detailS->notification_creator_name }}
                                                                    @else
                                                                        Agent
                                                                    @endif
                                                                </td>
                                                                <td>{{ $all_notification_detailS->type_of_notification }} </td>
                                                                <td>{{ $all_notification_detailS->total_price }} </td>
                                                                <td>
                                                                    <a type="button" href="#" class="btn btn-primary">Accept</a>
                                                                    
                                                                    <div class="dropdown card-widgets d-none">
                                                                        <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                                            <i class="dripicons-dots-3"></i>
                                                                        </a>
                                                                        <div class="dropdown-menu dropdown-menu-end" style="">
                                                                            <a href="{{ route('edit_Invoices',$all_notification_detailS->id) }}" class="dropdown-item">Edit Invoice</a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php $x++; ?>
                                                        @endforeach
                                                    @endif
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
        
    </div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  
@endsection

@section('scripts')
    <script src='https://cdn.rawgit.com/naptha/tesseract.js/1.0.10/dist/tesseract.js'></script>
@endsection
