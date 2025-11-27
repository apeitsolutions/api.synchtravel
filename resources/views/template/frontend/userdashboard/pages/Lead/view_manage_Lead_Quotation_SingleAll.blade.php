@extends('template/frontend/userdashboard/layout/default')
@section('content')

    <?php $currency=Session::get('currency_symbol'); // dd($data); ?>
    
    <style>
        .nav-link{
          color: #575757;
          font-size: 18px;
        }
    </style>
    
    <div class="content-wrapper">
        
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-12">
                        <h4>Lead Quotations</h4>
                        <div class="panel-body padding-bottom-none">
                            <div class="block-content block-content-full">
                                <div class="table-responsive">
                                    <div id="example_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                                   
                                        <table  class="table nowrap example1 dataTable no-footer" id="myTable" role="grid" aria-describedby="example_info">
                                                
                                            <thead class="theme-bg-clr">
                                                <tr role="row">
                                                    <th style="text-align: center;">Quotation Id</th>
                                                    <th style="text-align: center;">Customer Name</th>
                                                    <th style="text-align: center;">Lead Id</th>
                                                    <th style="text-align: center;">Type</th>
                                                    <th style="text-align: center;">Quotation Details</th>
                                                    <th style="text-align: center;">Quoted  By</th>
                                                    <th style="text-align: center;">Validity</th>
                                                    <th style="text-align: center;">Options</th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody style="text-align: center;">
                                                @foreach ($data as $value)
                                                    <?php
                                                        // dd($value);
                                                        $date4days          = date('d-m-Y',strtotime($value->created_at. ' + 5 days'));
                                                        $currdate           = date('d-m-Y');
                                                        $created_at         = date('d-m-Y',strtotime($value->created_at));
                                                        $quotation_validity = date('d-m-Y',strtotime($value->quotation_validity));
                                                        $quotation_validityT = date('d-m-Y H:i',strtotime($value->quotation_validity));
                                                        $origin             = new DateTime($currdate);
                                                        $target             = new DateTime($value->quotation_validity);
                                                        $remaining_validity = $origin->diff($target);
                                                        $remaining_validity = $remaining_validity->format('%a days %h:%i:%s Seconds');
                                                        $services   = json_decode($value->services);
                                                        if(isset($services)){
                                                            foreach($services as $services_res){
                                                                if($services_res == '1'){
                                                                    $service_Type = 'All Services';
                                                                }
                                                                if($services_res == 'accomodation_tab'){
                                                                    $service_Type = 'Hotel';
                                                                }
                                                                if($services_res == 'transportation_tab'){
                                                                    $service_Type = 'Transfer';
                                                                }
                                                                if($services_res == 'flights_tab'){
                                                                    $service_Type = 'Flight';
                                                                }
                                                                if($services_res == 'visa_tab'){
                                                                    $service_Type = 'Visa';
                                                                }
                                                            }
                                                        }
                                                    ?>
                                                    <tr role="row" class="odd">
                                                        <td>
                                                            <b>{{ $value->generate_id }}</b>
                                                            <a href="{{URL::to('view_manage_Lead_Quotation_Single_Admin')}}/{{$value->id}}">
                                                                <img height="15px" width="15px" src="{{ asset('/public/invoice_icon.png') }}">
                                                            </a>
                                                        </td>
                                                        <td>
                                                            @if(isset($all_Users) && $all_Users != null && $all_Users != '')
                                                                @foreach($all_Users as $all_Users_value)
                                                                    @if($value->customer_id == $all_Users_value->id)
                                                                        <b>{{ $all_Users_value->name }}</b>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                        <td>{{ $value->lead_id }}</td>
                                                        <td>{{ $service_Type }}</td>
                                                        <td>
                                                            @if($value->booking_customer_id == '-1' || $value->booking_customer_id == 0 || $value->booking_customer_id == '' || $value->booking_customer_id == null)
                                                                @if(isset($Agents_detail) && $Agents_detail != null && $Agents_detail != '')
                                                                    @foreach($Agents_detail as $val2)
                                                                        @if($val2->id == $value->agent_Id )
                                                                            <div class="row">
                                                                                <div class="col-xl-12">    
                                                                                    <b>{{ $val2->company_name }} - Agent</b>
                                                                                    <a href="{{URL::to('agents_stats_details')}}/{{ $val2->id }}">
                                                                                        <i class="mdi mdi-account-check-outline"></i>
                                                                                    </a>
                                                                                </div>
                                                                                <div class="col-xl-12">
                                                                                    <b>{{ $val2->company_email }} - Agent</b>
                                                                                </div>
                                                                            </div>
                                                                        @endif 
                                                                    @endforeach
                                                                @endif
                                                            @else
                                                                @if(isset($booking_customers) && $booking_customers != null && $booking_customers != '')
                                                                    @foreach($booking_customers as $val1)
                                                                        @if($val1->id == $value->booking_customer_id )
                                                                            <div class="row">
                                                                                <div class="col-xl-12">    
                                                                                    <b>{{ $val1->name }} - Customer</b>
                                                                                </div>
                                                                                <div class="col-xl-12">
                                                                                    <b>{{ $val1->email }} - Customer</b>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td>{{ $value->tour_author }}</td>
                                                        <td>
                                                            @if(isset($value->quotation_validity) && $value->quotation_validity != null && $value->quotation_validity != '')
                                                                @if($target > $origin)
                                                                    <b style="color:green">Valid till : {{ $quotation_validityT }}</b>
                                                                @else
                                                                    <b style="color:red">Quotation Expired({{ $quotation_validityT }})</b>
                                                                @endif
                                                            @else
                                                                <b style="color:red">Quotation Expired({{ $quotation_validityT }})</b>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($value->quotation_Invoice != '1')
                                                                <div class="dropdown card-widgets">
                                                                    <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                                        <i class="dripicons-dots-3"></i>
                                                                    </a>
                                                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                                                        <a href="{{URL::to('view_manage_Lead_Quotation_Single_Admin')}}/{{$value->id}}" class="dropdown-item">View Quotation</a>
                                                                        @if($value->quotation_status != 1)
                                                                            @if(isset($value->quotation_validity) && $value->quotation_validity != null && $value->quotation_validity != '')
                                                                                @if($target > $origin)
                                                                                    @if($value->all_services_quotation != '1')
                                                                                        <a href="{{URL::to('confirm_manage_Lead_Quotation')}}/{{$value->id}}/{{$value->lead_id}}" class="dropdown-item d-none">Confirm</a>
                                                                                    @else
                                                                                        <a href="{{URL::to('confirm_manage_Lead_Quotation_New')}}/{{$value->id}}/{{$value->lead_id}}" class="dropdown-item d-none">Confirm</a>
                                                                                    @endif
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <a class="btn btn-success">CONFIRMED</a>
                                                            @endif
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
            </div>
        </section>
        
    </div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

@endsection

@section('scripts')

<script src='https://cdn.rawgit.com/naptha/tesseract.js/1.0.10/dist/tesseract.js'></script>

@endsection
