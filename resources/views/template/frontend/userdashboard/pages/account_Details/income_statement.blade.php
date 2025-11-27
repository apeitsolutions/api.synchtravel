@extends('template/frontend/userdashboard/layout/default')
@section('content')

<?php $currency=Session::get('currency_symbol'); ?>

<div class="content-wrapper">
    <section class="content" style="padding: 30px 50px 0px 50px;">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Tours</a></li>
                                <li class="breadcrumb-item active">Income Statement</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Income Statement</h4>
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
                            </div>
                            <div class="col-sm-7">
                                <div class="text-sm-end">
                                </div>
                            </div>
                            <div class="table-responsive">
                                <div class="row">
                                    <table class="table table-centered w-100 dt-responsive nowrap" id="example1">
                                        <thead class="table-light">
                                            <tr role="row">
                                                <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;">Tour ID</th>
                                                <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;">Booking ID</th>
                                                <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;">Tour Name</th>
                                                <th class="d-none d-sm-table-cell sorting_asc" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 180.035px;">Total</th>
                                                <th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="email: activate to sort column ascending" style="width: 175.073px;">Recieved</th>
                                                <th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="email: activate to sort column ascending" style="width: 175.073px;">Outstandings</th>
                                                <th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Date: activate to sort column ascending" style="width: 138.958px;">Expense</th>
                                                <th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending" style="width: 147.118px;">Profit</th>
                                                <!--<th class="d-none d-sm-table-cell sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending" style="width: 147.118px;">Action</th>-->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data as $value)
                                                <?php 
                                                        $markup_details = json_decode($value->markup_details);
                                                        $expense = 0;
                                                ?> 
                                                <tr role="row" class="odd">
                                                    <td>{{ $value->id }}</td>
                                                    <td>{{ $value->generate_id }}</td>
                                                    <td>{{ $value->title }}</td>
                                                    <td><?php echo $currency; ?>{{ $value->total_amount }}</td>
                                                    <td><?php echo $currency; ?>{{ $value->recieved_amount }}</td>
                                                    <td><?php echo $currency; ?>{{ $value->remaining_amount }}</td>
                                                    <td>
                                                        @if(isset($markup_details))    
                                                            @foreach($markup_details as $value1)
                                                                <?php $expense = $expense + $value1->without_markup_price ?>
                                                            @endforeach
                                                        @endif
                                                        <?php echo $currency; ?>{{ $expense }}
                                                    </td>
                                                    <td><?php echo $currency; ?>{{ $value->total_amount - $expense }}</td>
                                                    <!--<td>-->
                                                    <!--    <div class="dropdown card-widgets">-->
                                                    <!--        <a style="float: right;" href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">-->
                                                    <!--            <i class="dripicons-dots-3"></i>-->
                                                    <!--        </a>-->
                                                    <!--        <div class="dropdown-menu dropdown-menu-end" style="">-->
                                                                <!-- item-->
                                                    <!--            <a href="{{ URL::to('super_admin/view_income_Voucher')}}/{{$value->id}}" class="dropdown-item"><i class="mdi mdi-eye me-1"></i>View Voucher</a>-->
                                                                <!-- item-->
                                                    <!--            <a href="{{ URL::to('super_admin/view_income_Invoice')}}/{{$value->id}}" class="dropdown-item"><i class="mdi mdi-eye me-1"></i>Invoice</a>-->
                                                                <!-- item-->
                                                    <!--        </div>-->
                                                    <!--    </div>-->
                                                    <!--</td>-->
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
        </div>         
    </section>
</div>

@endsection
