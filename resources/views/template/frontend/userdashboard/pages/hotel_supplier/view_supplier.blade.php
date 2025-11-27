@extends('template/frontend/userdashboard/layout/default')
@section('content')

    <div class="container-fluid">
        
        <div class="table-responsive">
            <div class="row">
                
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" style="background-color:#f5cfcf;" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" style="background-color:#d4edda;" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <table class="table table-centered w-100 dt-responsive nowrap mt-5" id="example1">
                    <thead class="table-light">
                        <tr>
                            <th style="text-align: center;">Sr No.</th>
                            <th style="text-align: center;">Customer Name</th>
                            <th style="text-align: center;">Total Balance</th>
                            <th style="text-align: center;">Payable</th>
                            <th style="text-align: center;">Opening Balance</th>
                            <th style="text-align: center;">Opening Payable</th>
                            <th style="text-align: center;">Company Name</th>
                            <th style="text-align: center;">Company Email</th>
                            <th style="text-align: center;">Contact Person Name</th>
                            <th style="text-align: center;">Contact No.</th>
                            <th style="text-align: center;display:none">More Contact No.</th>
                            <th style="width: 85px;display:none">Action</th>
                        </tr>
                    </thead>
                    <tbody style="text-align: center;">
                        @foreach($data as $data_res)
                            <tr>
                                <td>{{$data_res->id ?? ""}}</td>
                                <td>
                                    @if(isset($all_Users) && $all_Users != null && $all_Users != '')
                                        @foreach($all_Users as $all_Users_value)
                                            @if($data_res->customer_id == $all_Users_value->id)
                                                <b>{{ $all_Users_value->name }}</b>
                                            @endif
                                        @endforeach
                                    @endif
                                </td>
                                <td>{{$data_res->balance ?? ""}}</td>
                                <td>{{$data_res->payable ?? ""}}</td>
                                <td>{{$data_res->opening_balance ?? ""}}</td>
                                <td>{{$data_res->opening_payable ?? ""}}</td>
                                <td>{{$data_res->room_supplier_name ?? ""}}</td>
                                <td>{{$data_res->email ?? ""}}</td>
                                <td>{{$data_res->contact_person_name ?? ""}}</td>
                                <td>{{$data_res->phone_no ?? ""}}</td>
                                <td style="display:none">
                                    @if(isset($data_res->more_phone_no) && $data_res->more_phone_no != null && $data_res->more_phone_no != '')
                                        <div>
                                            <?php $more_phone_no  = json_decode($data_res->more_phone_no);  ?>
                                            @foreach($more_phone_no as $more_phone_noS)
                                                <li>{{ $more_phone_noS ?? ''}}</li>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td style="display:none">
                                    <a href="{{URL::to('hotel_suppliers_ledger')}}/{{$data_res->id}}"><span class="badge bg-success" style="font-size: 15px">Ledger</span></a> 
                                    <a href="{{URL::to('edit_hotel_suppliers')}}/{{$data_res->id}}"><span class="badge bg-success" style="font-size: 15px">Edit</span></a> 
                                    <a href="{{URL::to('delete_hotel_suppliers')}}/{{$data_res->id}}"><span class="badge bg-success" style="font-size: 15px">Delete</span></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div> 
        
    </div>

@endsection

@section('scripts')

@stop

@section('slug')

@stop
