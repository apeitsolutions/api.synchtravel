@extends('template/frontend/userdashboard/layout/default')
@section('content')

    <?php $currency=Session::get('currency_symbol'); // dd($data); ?>
    
    <style>
        .nav-link{
          color: #575757;
          font-size: 18px;
        }
    </style>
    
   <div class="dashboard-content">
        <h4>Lead Details</h4>
        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <div class="dashboard-list-box dash-list margin-top-0">
                    <div class="row">
                        <div class="col-md-12">
                            <table id="myTable" class="display nowrap table  table-bordered" style="width:100%">
                                                
                                <thead class="theme-bg-clr">
                                    <tr>
                                        <th style="text-align: center;">Lead Id</th>
                                        <th style="text-align: center;">Customer Name</th>
                                        <th style="text-align: center;">Customer/Agent Name</th>
                                        <th style="text-align: center;">Customer/Agent Details</th>
                                        <th style="text-align: center;">Refered by</th>
                                        <th style="text-align: center;">Responded by</th>
                                        <th style="width: 125px;text-align: center;" class="d-none">Action</th>
                                    </tr>
                                </thead>
                                
                                <tbody style="text-align: center;">
                                    @foreach ($data as $value)
                                        <?php
                                            $client_type    = $value->client_type;
                                            $refered_by_new = $value->refered_by_new;
                                            $customer_name  = $value->customer_name;
                                            $agent_Id       = $value->agent_Id;
                                        ?>
                                        <tr>
                                            <td>{{ $value->id }}</td>
                                            <td>
                                                @if(isset($all_Users) && $all_Users != null && $all_Users != '')
                                                    @foreach($all_Users as $all_Users_value)
                                                        @if($value->customer_id == $all_Users_value->id)
                                                            <b>{{ $all_Users_value->name }}</b>
                                                            <?php
                                                                $webiste_Address    = $all_Users_value->webiste_Address;
                                                                $url                = $webiste_Address.'/public/uploads/package_imgs'; 
                                                            ?>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>{{ $value->first_Name }}</td>
                                            <td>
                                                Email   : <b>{{ $value->email }}</b> <br>
                                                Contact : <b>{{ $value->mobile_No }}</b> <br>
                                            </td>
                                            <td>
                                                @if(isset($client_type) && $client_type != null && $client_type != '')
                                                    @if($client_type == 'Existing')
                                                    @elseif($client_type == 'New_client')
                                                        @if($refered_by_new == 'Customer')
                                                            @if(isset($customers_data) && $customers_data != null && $customers_data != '')
                                                                @foreach($customers_data as $customers_dataS)
                                                                    @if($customers_dataS->id == $value->refered_customer_name)
                                                                        {{ $customers_dataS->name }}<br><b>({{ $refered_by_new }})</b>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        @elseif($refered_by_new == 'Agent')
                                                            @if(isset($Agents_detail) && $Agents_detail != null && $Agents_detail != '')
                                                                @foreach($Agents_detail as $Agents_detailS)
                                                                    @if($Agents_detailS->id == $value->refered_agent_Name)
                                                                        {{ $Agents_detailS->company_name }}<br><b>({{ $refered_by_new }})</b>
                                                                        <!--agent_Name-->
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endif
                                            </td>
                                            <td>{{ $value->channel_picked_by }}</td>
                                            <td class="d-none">
                                                <div class="dropdown card-widgets">
                                                    <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="dripicons-dots-3"></i>
                                                    </a> 
                                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                                        <?php $process_S = 0; $process_S1 = 0; $open='OPEN'; $close='CLOSE'; ?>
                                                        
                                                        @if(isset($lead_Quotations) && $lead_Quotations != null && $lead_Quotations != '')
                                                            @foreach($lead_Quotations as $lead_Quotations_value)
                                                                @if($lead_Quotations_value->lead_id == $value->id)
                                                                    @if($lead_Quotations_value->quotation_status != '1')
                                                                        <?php $process_S = 1; ?>
                                                                        @break
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                        
                                                        @if($process_S == '1')
                                                            <a href="{{URL::to('create_lead_quotation')}}/{{$value->id}}" class="dropdown-item hide_dropdown_{{ $value->id }} d-none">Process for Single Service</a>
                                                            <a href="{{URL::to('create_lead_quotation_New')}}/{{$value->id}}" class="dropdown-item hide_dropdown_{{ $value->id }} d-none">Process for Custom Package</a>
                                                            <a href="{{URL::to('edit_Lead')}}/{{$value->id}}" class="dropdown-item hide_dropdown_{{ $value->id }} d-none">Edit Lead</a>
                                                            <a href="{{URL::to('view_manage_Lead_Quotation_SingleAll_Admin')}}/{{$value->id}}" class="dropdown-item hide_dropdown_{{ $value->id }}">View Quotation</a>
                                                            <a id="change_status_{{ $value->id }}" onclick="change_status_function({{ $value->id }})" class="dropdown-item" style="color: white;background-color: #fa5c7c;">Close Lead</a>
                                                            <input type="hidden" id="change_status_id_{{ $value->id }}" value="OPEN">
                                                        @else
                                                            <a href="{{URL::to('create_lead_quotation')}}/{{$value->id}}" class="dropdown-item hide_dropdown_{{ $value->id }} d-none" style="display:none">Process for Single Service</a>
                                                            <a href="{{URL::to('create_lead_quotation_New')}}/{{$value->id}}" class="dropdown-item hide_dropdown_{{ $value->id }} d-none" style="display:none">Process for Custom Package</a>
                                                            <a href="{{URL::to('edit_Lead')}}/{{$value->id}}" class="dropdown-item hide_dropdown_{{ $value->id }} d-none" style="display:none">Edit Lead</a>
                                                            <a id="change_status_{{ $value->id }}" onclick="change_status_function({{ $value->id }})" class="dropdown-item" style="color: white;background-color: #0acf97;">Open Lead</a>
                                                            <input type="hidden" id="change_status_id_{{ $value->id }}" value="CLOSE">
                                                        @endif
                                                        
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
   
@endsection

@section('scripts')

    <script>
        $(document).ready( function () {
            $('#myTable').DataTable({
                "scrollX": true,
            });
        });
        
        function change_status_function(id){
            var change_status_id = $('#change_status_id_'+id+'').val();
            if(change_status_id == 'OPEN'){
                $('#change_status_'+id+'').html('Open Lead');
                $('#change_status_'+id+'').css('background-color','#0acf97');
                $('#change_status_id_'+id+'').val('CLOSE');
                $('.hide_dropdown_'+id+'').css('display','none');
            }else if(change_status_id == 'CLOSE'){
                $('#change_status_'+id+'').html('Close Lead');
                $('#change_status_'+id+'').css('background-color','#fa5c7c');
                $('#change_status_id_'+id+'').val('OPEN');
                $('.hide_dropdown_'+id+'').css('display','');
            }else{
                alert('SOMETHING WENT WRONG');
            }
        }
    </script>

@stop
