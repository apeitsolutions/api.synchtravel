
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
              <div class="col-lg-12 col-12">
                <nav class="breadcrumb push">
                    <a class="breadcrumb-item" href="#">Dashboard</a> 
                    <span class="breadcrumb-item active">Agent Financial Details</span>
                </nav>
              </div>
            </div>
          </div>
        </section>
        
        <section class="content" style="padding: 10px 20px 0px 20px">
            <div class="container-fluid">
                <div class="row">
            
                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-success" style="height: 100px;text-align: center;padding: 15px 0px 0px 0px;background-color: #313a46 !important;color: white;">
                            <div class="icon mb-2 d-none">
                                <span class="iconify" data-icon="uil-dollar-sign-alt" data-width="70"></span>
                            </div>
                            <div class="inner">
                                <h3 id="total_sale1">
                                    @if(isset($agents_data))
                                        <?php $total_saleN = 0; ?>
                                        @foreach($agents_data as $agents_dataS)
                                            @foreach($agents_dataS->agents_tour_booking as $agent_res)
                                                <?php
                                                    $total_saleN             += $agent_res->price;
                                                ?>
                                            @endforeach
                                            @foreach($agents_dataS->agents_invoices_booking as $agent_res)
                                                <?php
                                                    $total_saleN             += $agent_res->price;
                                                ?>
                                            @endforeach
                                        @endforeach
                                        {{ round($total_saleN,2) }}
                                    @else
                                        0
                                    @endif()
                                    
                                </h3>
                                <p>Total Revenue</p>
                            </div>
                          
                        </div>
                    </div>
                  
                    <div class="col-lg-4 col-6">
                        <div class="small-box" style="height: 100px;text-align: center;padding: 15px 0px 0px 0px;background-color: #313a46 !important;color: white;">
                            <div class="icon mb-2 d-none">
                                <span class="iconify" data-icon="uil-dollar-sign-alt" data-width="70"></span>
                            </div>
                            <div class="inner">
                                <h3 id="total_paymet_amount1">
                                    @if(isset($agents_data))
                                        <?php $total_paymet_amountN = 0; ?>
                                        @foreach($agents_data as $agents_dataS)
                                            @foreach($agents_dataS->agents_tour_booking as $agent_res)
                                                <?php
                                                    $total_paymet_amountN    += $agent_res->paid_amount;
                                                ?>
                                            @endforeach
                                            @foreach($agents_dataS->agents_invoices_booking as $agent_res)
                                                <?php
                                                    $total_paymet_amountN    += $agent_res->paid_amount;
                                                ?>
                                            @endforeach
                                        @endforeach
                                        {{ round($total_paymet_amountN,2) }}
                                    @else
                                        0
                                    @endif()
                                </h3>
                                <p>Total Paid</p>
                            </div>
                        </div>
                    </div>
                  
                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-info" style="height: 100px;text-align: center;padding: 15px 0px 0px 0px;background-color: #313a46 !important;color: white;">
                            <div class="icon mb-2 d-none">
                                <span class="iconify" data-icon="uil-dollar-sign-alt" data-width="70"></span>
                            </div>
                            <div class="inner">
                                <h3 id="total_remaning_amount1">
                                    @if(isset($agents_data))
                                        <?php $total_remaning_amountN = 0; ?>
                                        @foreach($agents_data as $agents_dataS)
                                            @foreach($agents_dataS->agents_tour_booking as $agent_res)
                                                <?php
                                                    $total_remaning_amountN  += $agent_res->remaing_amount;
                                                ?>
                                            @endforeach
                                            @foreach($agents_dataS->agents_invoices_booking as $agent_res)
                                                <?php
                                                    $total_remaning_amountN  += $agent_res->remaing_amount;
                                                ?>
                                            @endforeach
                                        @endforeach
                                        {{ round($total_remaning_amountN,2) }}
                                    @else
                                        0
                                    @endif()
                                </h3>
                                <p>Outstanding</p>
                            </div>
                        </div>
                    </div>
                  
                    <div class="col-lg-6 col-6" style="margin-top: 10px;">
                        <div class="small-box bg-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" style="height: 100px;text-align: center;padding: 15px 0px 0px 0px;background-color: #313a46 !important;color: white;">
                            <div class="icon mb-2 d-none">
                                <span class="iconify" data-icon="uil-dollar-sign-alt" data-width="70"></span>
                            </div>
                            <div class="inner">
                                <h3 id="total_overpaid1">
                                    @isset($agents_data)
                                        <?php $agent_over_paid = 0; ?>
                                        @foreach($agents_data as $agents_dataS)
                                            <?php
                                                $agent_over_paid = $agent_over_paid + $agents_dataS->agent_over_paid;
                                            ?>
                                        @endforeach
                                        {{ round($agent_over_paid,2) }}
                                    @else
                                        0
                                    @endisset()
                                </h3>
                                <p>Over Paid</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-6 col-6" style="margin-top: 10px;">
                        <div class="small-box bg-info" style="height: 100px;text-align: center;padding: 15px 0px 0px 0px;background-color: #313a46 !important;color: white;">
                            <div class="icon mb-2 d-none">
                                <span class="iconify" data-icon="uil-dollar-sign-alt" data-width="70"></span>
                            </div>
                            <div class="inner">
                                <h3 id="total_profit1">
                                    @if(isset($agents_data))
                                        <?php $total_profitN = 0; ?>
                                        @foreach($agents_data as $agents_dataS)
                                            @foreach($agents_dataS->agents_tour_booking as $agent_res)
                                                <?php
                                                    $total_profitN  += $agent_res->profit;
                                                ?>
                                            @endforeach
                                            @foreach($agents_dataS->agents_invoices_booking as $agent_res)
                                                <?php
                                                    $total_profitN  += $agent_res->profit;
                                                ?>
                                            @endforeach
                                        @endforeach
                                        {{ round($total_profitN,2) }}
                                    @else
                                        0
                                    @endif()
                                </h3>
                                <p>Profit</p>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </section>
        
        <div class="dashboard-content d-none">
            <h3 style="color:#a30000;font-size: 40px;text-align:center">Agent Financial Statement</h3>
            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    <div class="dashboard-list-box dash-list margin-top-0">
                        <div class="row">
                            <div class="col-md-12">
                                <table id="myTable" class="display nowrap table table-bordered" style="width:100%;">
                                    
                                    <div>
                                        <label class=""><h4><b>Sort Data by Agent Name</b></h4></label>
                                        <select class="form-control" id="" style="width: 220px;">
                                            <option attr-all-detail="0" value="0">Choose Agent</option>
                                            @isset($agents_data)
                                                <?php $xx = 0 ?>
                                                @foreach($agents_data as $agents_dataS)
                                                    <option attr-all-detail='<?php echo json_encode($agents_data[$xx]); ?>' value="{{ $agents_dataS->agent_id }}">{{ $agents_dataS->agent_name }}</option>
                                                    <?php $xx++ ?>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                     
                                    <thead class="theme-bg-clr">
                                        <tr role="row">
                                            <th style="text-align: center;">Sr</th>
                                            <th style="text-align: center;">Agent Detail</th>
                                            <th style="text-align: center;">Payment Details</th>
                                            <th style="text-align: center;">Agent Commission</th>
                                            <th style="text-align: center;">Profit</th>
                                            <th style="text-align: center;">View Invoice</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody style="text-align: center;" id="">
                                        @isset($agents_data)
                                            <?php $x = 1; ?>
                                            @foreach($agents_data as $agents_dataS)
                                                <?php
                                                    $total_sale = 0;
                                                    $total_paymet_amount = 0;
                                                    $total_remaning_amount = 0;
                                                    $total_over_paid = 0;
                                                ?>
                                                @foreach($agents_dataS->agents_tour_booking as $agent_res)
                                                    <tr role="row">
                                                        <td>{{ $x++ }}</td>
                                                        <td>
                                                            Agent Id     : {{ $agents_dataS->agent_id }}<br>
                                                            Agent Name   : {{ $agents_dataS->agent_name }}<br>
                                                            Invoice No.  : {{ $agent_res->invoice_id }}<br>
                                                            Company Name : {{ $agents_dataS->agent_company }}<br>
                                                            Type         : Package
                                                        </td>
                                                        <td>
                                                            Total       : {{ $agent_res->price }}<br>
                                                            Paid        : {{ $agent_res->paid_amount }}<br>
                                                            Remaining   : {{ $agent_res->remaing_amount }}<br>
                                                            Outstanding : {{ $agent_res->over_paid_amount }} 
                                                        </td>
                                                        <td>
                                                            Commission      : {{ $agent_res->commission_am }}<br>
                                                            Include Total   : @if($agent_res->agent_commsion_add_total) Yes @else No @endif
                                                        </td>
                                                        <td>
                                                            Profit      : {{ $agent_res->profit }}<br>
                                                            Cost        : {{ $agent_res->total_cost }}<br>
                                                            Sale        : {{ $agent_res->total_sale }}<br>
                                                            Discount    : {{ $agent_res->discount_am }}
                                                        </td>
                                                        <td>
                                                            <a href="{{ URL::to('invoice_package/'.$agent_res->invoice_id.'/'.$agent_res->booking_id.'/'.$agent_res->tour_id.'') }}" target="blank" class="btn btn-primary btn-sm">view Details</a>
                                                        </td>
                                                    </tr>
                                                    <?php 
                                                        $total_sale             += $agent_res->price;
                                                        $total_paymet_amount    += $agent_res->paid_amount;
                                                        $total_remaning_amount  += $agent_res->remaing_amount;
                                                        $total_over_paid        = $agent_res->over_paid_amount;
                                                    ?>
                                                @endforeach
                                                
                                                @foreach($agents_dataS->agents_invoices_booking as $agent_res)
                                                    <tr role="row">
                                                        <td>{{ $x++ }}</td>
                                                        <td>
                                                            Agent Id     : {{ $agents_dataS->agent_id }}<br>
                                                            Agent Name   : {{ $agents_dataS->agent_name }}<br>
                                                            Invoice No.  : {{ $agent_res->invoice_id }}<br>
                                                            Company Name : {{ $agents_dataS->agent_company }}<br>
                                                            Type         : Invoice
                                                        </td>
                                                        <td>
                                                            Total       : {{ $agent_res->price }}<br>
                                                            Paid        : {{ $agent_res->paid_amount }}<br>
                                                            Remaining   : @if($agent_res->remaing_amount >= 0) {{ $agent_res->remaing_amount }} @endif <br>
                                                            Outstanding : {{ $agent_res->over_paid_amount }}
                                                        </td>
                                                        <td></td>
                                                        <td>
                                                            Profit  : {{ $agent_res->profit }}
                                                            Cost    : {{ $agent_res->total_cost }}<br>
                                                            Sale    : {{ $agent_res->total_sale }}<br>
                                                        </td>
                                                        <td>
                                                            <a href="{{ URL::to('invoice_Agent/'.$agent_res->invoice_id) }}" target="blank" class="btn btn-primary btn-sm">view Details</a>
                                                        </td>
                                                    </tr>
                                                    <?php 
                                                        $total_sale             += $agent_res->price;
                                                        $total_paymet_amount    += $agent_res->paid_amount;
                                                        $total_remaning_amount  += $agent_res->remaing_amount;
                                                    ?>
                                                @endforeach
                                            @endforeach
                                        @endisset()
                                    </tbody>
                                    
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="dashboard-content" style="margin-top:10px">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body" style="background-color: #edeff1;">
    
                            <h2>Agent Financial Statement</h2>
    
                            <div class="tab-content">
                                <div class="tab-pane show active" id="buttons-table-preview">
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>
                                                <h4><b>Sort Data by Agent Name</b></h4>
                                            </label>
                                            <select class="form-control" id="separate_agent_details" style="width: 220px;">
                                                <option attr-all-detail="0" value="0">Choose Agent</option>
                                                @isset($agents_data)
                                                    <?php $xx = 0 ?>
                                                    @foreach($agents_data as $agents_dataS)
                                                        <option attr-all-detail='<?php echo json_encode($agents_data[$xx]); ?>' value="{{ $agents_dataS->agent_id }}">{{ $agents_dataS->agent_name }}</option>
                                                        <?php $xx++ ?>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                                        <thead class="theme-bg-clr">
                                            <tr>
                                                <th style="text-align: center;">Sr</th>
                                                <th style="text-align: center;">Agent Detail</th>
                                                <th style="text-align: center;">Payment Details</th>
                                                <th style="text-align: center;">Agent Commission</th>
                                                <th style="text-align: center;">Profit</th>
                                                <th style="text-align: center;">View Invoice</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody style="text-align: center;" id="tbody_all_data_acc">
                                            @isset($agents_data)
                                                <?php $x = 1; ?>
                                                @foreach($agents_data as $agents_dataS)
                                                    <?php
                                                        $total_sale = 0;
                                                        $total_paymet_amount = 0;
                                                        $total_remaning_amount = 0;
                                                        $total_over_paid = 0;
                                                    ?>
                                                    @foreach($agents_dataS->agents_tour_booking as $agent_res)
                                                        <tr>
                                                            <td>{{ $x++ }}</td>
                                                            <td>
                                                                Agent Id     : {{ $agents_dataS->agent_id }}<br>
                                                                Agent Name   : {{ $agents_dataS->agent_name }}<br>
                                                                Invoice No.  : {{ $agent_res->invoice_id }}<br>
                                                                Company Name : {{ $agents_dataS->agent_company }}<br>
                                                                Type         : Package
                                                            </td>
                                                            <td>
                                                                Total       : {{ $agent_res->price }}<br>
                                                                Paid        : {{ $agent_res->paid_amount }}<br>
                                                                Remaining   : {{ $agent_res->remaing_amount }}<br>
                                                                Outstanding : {{ $agent_res->over_paid_amount }} 
                                                            </td>
                                                            <td>
                                                                Commission      : {{ $agent_res->commission_am }}<br>
                                                                Include Total   : @if($agent_res->agent_commsion_add_total) Yes @else No @endif
                                                            </td>
                                                            <td>
                                                                Profit      : {{ $agent_res->profit }}<br>
                                                                Cost        : {{ $agent_res->total_cost }}<br>
                                                                Sale        : {{ $agent_res->total_sale }}<br>
                                                                Discount    : {{ $agent_res->discount_am }}
                                                            </td>
                                                            <td>
                                                                <a href="{{ URL::to('invoice_package/'.$agent_res->invoice_id.'/'.$agent_res->booking_id.'/'.$agent_res->tour_id.'') }}" target="blank" class="btn btn-primary btn-sm">view Details</a>
                                                            </td>
                                                        </tr>
                                                        <?php 
                                                            $total_sale             += $agent_res->price;
                                                            $total_paymet_amount    += $agent_res->paid_amount;
                                                            $total_remaning_amount  += $agent_res->remaing_amount;
                                                            $total_over_paid        = $agent_res->over_paid_amount;
                                                        ?>
                                                    @endforeach
                                                    
                                                    @foreach($agents_dataS->agents_invoices_booking as $agent_res)
                                                        <tr>
                                                            <td>{{ $x++ }}</td>
                                                            <td>
                                                                Agent Id     : {{ $agents_dataS->agent_id }}<br>
                                                                Agent Name   : {{ $agents_dataS->agent_name }}<br>
                                                                Invoice No.  : {{ $agent_res->invoice_id }}<br>
                                                                Company Name : {{ $agents_dataS->agent_company }}<br>
                                                                Type         : Invoice
                                                            </td>
                                                            <td>
                                                                Total       : {{ $agent_res->price }}<br>
                                                                Paid        : {{ $agent_res->paid_amount }}<br>
                                                                Remaining   : @if($agent_res->remaing_amount >= 0) {{ $agent_res->remaing_amount }} @endif <br>
                                                                Outstanding : {{ $agent_res->over_paid_amount }}
                                                            </td>
                                                            <td></td>
                                                            <td>
                                                                Profit  : {{ $agent_res->profit }}
                                                                Cost    : {{ $agent_res->total_cost }}<br>
                                                                Sale    : {{ $agent_res->total_sale }}<br>
                                                            </td>
                                                            <td>
                                                                <a href="{{ URL::to('invoice_Agent/'.$agent_res->invoice_id) }}" target="blank" class="btn btn-primary btn-sm">view Details</a>
                                                            </td>
                                                        </tr>
                                                        <?php 
                                                            $total_sale             += $agent_res->price;
                                                            $total_paymet_amount    += $agent_res->paid_amount;
                                                            $total_remaning_amount  += $agent_res->remaing_amount;
                                                        ?>
                                                    @endforeach
                                                @endforeach
                                            @endisset()
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
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    
    <script>
        $(document).ready( function () {
            $('#myTable').DataTable({
                "scrollX": true,
            });
        });
    </script>
    
    <script>
        
        var agents_data_New = {!!json_encode($agents_data)!!};
        
        $("#separate_agent_details").on('change',function(){
            var agents_data = $(this).find('option:selected').attr('attr-all-detail');
            var total_sale              = 0;
            var total_paymet_amount     = 0;
            var total_remaning_amount   = 0;
            var total_profit            = 0;
            var total_overpaid          = 0
            
            if(agents_data == 0){
                var i = 1;
                $("#tbody_all_data_acc").empty();
                $.each(agents_data_New, function(key, agents_dataS) {
                    var agents_tour_booking     = agents_dataS['agents_tour_booking'];
                    var agents_invoices_booking = agents_dataS['agents_invoices_booking'];
                    
                    if(agents_tour_booking != null && agents_tour_booking != ''){
                        $.each(agents_tour_booking, function(key, agent_res) {
                            
                            total_sale              = parseFloat(total_sale) + agent_res.price;
                            total_paymet_amount     = parseFloat(total_paymet_amount) + agent_res.paid_amount;
                            total_remaning_amount   = parseFloat(total_remaning_amount) + agent_res.remaing_amount;
                            total_profit            = parseFloat(total_profit) + agent_res.profit ;
                            total_overpaid          = parseFloat(total_overpaid) + agent_res.over_paid_amount ;
                            
                            var invoice_id  = agent_res.invoice_id;
                            var booking_id  = agent_res.booking_id;
                            var tour_id     = agent_res.tour_id;
                            var url         = `invoice_package/${invoice_id}/${booking_id}/${tour_id}`;
                            
                            var remaing_amount              = agent_res.remaing_amount;
                            var agent_commsion_add_total    = agent_res.agent_commsion_add_total;
                            
                            var data_apppend =  `<tr>
                                                    <td>${i}</td>
                                                    <td>
                                                        Agent Id     : ${ agents_dataS.agent_id }<br>
                                                        Agent Name   : ${ agents_dataS.agent_name }<br>
                                                        Invoice No.  : ${ agent_res.invoice_id }<br>
                                                        Company Name : ${ agents_dataS.agent_company }<br>
                                                        Type         : Package
                                                    </td>
                                                    <td>
                                                        Total       : ${ agent_res.price }<br>
                                                        Paid        : ${ agent_res.paid_amount }<br>`;
                                                        if(remaing_amount >= 0){
                                                            data_apppend += `Remaining   : ${remaing_amount}<br>`;
                                                        }else{
                                                            data_apppend += `Remaining   : 0<br>`;
                                                        }
                                        data_apppend += `Outstanding : ${ agent_res.over_paid_amount }
                                                    </td>
                                                    <td>
                                                        Commission      : ${ agent_res.commission_am}<br>`;
                                                        if(agent_commsion_add_total){
                                                            data_apppend += `YES`;
                                                        }else{
                                                            data_apppend += `NO`;
                                                        }
                                    data_apppend += `</td>
                                                    <td>
                                                        Profit  : ${ agent_res.profit }
                                                        Cost    : ${ agent_res.total_cost }<br>
                                                        Sale    : ${ agent_res.total_sale }<br>
                                                    </td>
                                                    <td>
                                                        <a href="${url}" target="blank" class="btn btn-primary btn-sm">view Details</a>
                                                    </td>
                                                </tr>`;
                            
                            $("#tbody_all_data_acc").append(data_apppend);
                            
                            i++;
                        });
                    }
                    
                    if(agents_invoices_booking != null && agents_invoices_booking != ''){
                        $.each(agents_invoices_booking, function(key, agent_res) {
                            
                            total_sale              = parseFloat(total_sale) + agent_res.price;
                            total_paymet_amount     = parseFloat(total_paymet_amount) + agent_res.paid_amount;
                            total_remaning_amount   = parseFloat(total_remaning_amount) + agent_res.remaing_amount;
                            total_profit            = parseFloat(total_profit) + agent_res.profit ;
                            total_overpaid          = parseFloat(total_overpaid) + agent_res.over_paid_amount ;
                            
                            var invoice_id  = agent_res.invoice_id;
                            var url         = `invoice_Agent/${invoice_id}`;
                            
                            var remaing_amount = agent_res.remaing_amount;
                            var data_apppend =  `<tr>
                                                    <td>${i}</td>
                                                    <td>
                                                        Agent Id     : ${agents_dataS.agent_id}<br>
                                                        Agent Name   : ${agents_dataS.agent_name}<br>
                                                        Invoice No.  : ${agent_res.invoice_id}<br>
                                                        Company Name : ${agents_dataS.agent_company}<br>
                                                        Type         : Invoice
                                                    </td>
                                                    <td>
                                                        Total       : ${agent_res.price}<br>
                                                        Paid        : ${agent_res.paid_amount}<br>`;
                                                        if(remaing_amount >= 0){
                                                            data_apppend += `Remaining   : ${remaing_amount}<br>`;
                                                        }else{
                                                            data_apppend += `Remaining   : 0<br>`;
                                                        }
                                        data_apppend += `Outstanding : ${agent_res.over_paid_amount}
                                                    </td>
                                                    <td></td>
                                                    <td>
                                                        Profit  : ${agent_res.profit}
                                                        Cost    : ${agent_res.total_cost}<br>
                                                        Sale    : ${agent_res.total_sale}<br>
                                                    </td>
                                                    <td><a href="${url}" target="blank" class="btn btn-primary btn-sm">view Details</a></td>
                                                </tr>`;
                            
                            $("#tbody_all_data_acc").append(data_apppend);
                            
                            i++;
                        });
                    }
                });
                
                total_sale              = total_sale.toFixed(2);
                total_paymet_amount     = total_paymet_amount.toFixed(2);
                total_remaning_amount   = total_remaning_amount.toFixed(2);
                total_profit            = total_profit.toFixed(2);
                total_overpaid          = total_overpaid.toFixed(2);
                
                $('#total_sale1').empty();
                $('#total_paymet_amount1').empty();
                $('#total_remaning_amount1').empty();
                $('#total_profit1').empty();
                $('#total_overpaid1').empty();
                
                $('#total_sale1').html(total_sale);
                $('#total_paymet_amount1').html(total_paymet_amount);
                $('#total_remaning_amount1').html(total_remaning_amount);
                $('#total_profit1').html(total_profit);
                $('#total_overpaid1').html(total_overpaid);
                
            }else{
                var agents_dataS = JSON.parse(agents_data);
                var i = 1;
                $("#tbody_all_data_acc").empty();
                
                var agents_tour_booking     = agents_dataS.agents_tour_booking;
                var agents_invoices_booking = agents_dataS.agents_invoices_booking;
                    
                if(agents_tour_booking != null && agents_tour_booking != ''){
                    $.each(agents_tour_booking, function(key, agent_res) {
                        
                        total_sale              = parseFloat(total_sale) + agent_res.price;
                        total_paymet_amount     = parseFloat(total_paymet_amount) + agent_res.paid_amount;
                        total_remaning_amount   = parseFloat(total_remaning_amount) + agent_res.remaing_amount;
                        total_profit            = parseFloat(total_profit) + agent_res.profit ;
                        total_overpaid          = parseFloat(total_overpaid) + agent_res.over_paid_amount ;
                        
                        var invoice_id  = agent_res.invoice_id;
                        var booking_id  = agent_res.booking_id;
                        var tour_id     = agent_res.tour_id;
                        var url         = `invoice_package/${invoice_id}/${booking_id}/${tour_id}`;
                        
                        var remaing_amount           = agent_res.remaing_amount;
                        var agent_commsion_add_total = agent_res.agent_commsion_add_total;
                        
                        var data_apppend =  `<tr>
                                                <td>${i}</td>
                                                <td>
                                                    Agent Id     : ${ agents_dataS.agent_id }<br>
                                                    Agent Name   : ${ agents_dataS.agent_name }<br>
                                                    Invoice No.  : ${ agent_res.invoice_id }<br>
                                                    Company Name : ${ agents_dataS.agent_company }<br>
                                                    Type         : Package
                                                </td>
                                                <td>
                                                    Total       : ${ agent_res.price }<br>
                                                    Paid        : ${ agent_res.paid_amount }<br>`;
                                                    if(remaing_amount >= 0){
                                                        data_apppend += `Remaining   : ${remaing_amount}<br>`;
                                                    }else{
                                                        data_apppend += `Remaining   : 0<br>`;
                                                    }
                                    data_apppend += `Outstanding : ${ agent_res.over_paid_amount }
                                                </td>
                                                <td>
                                                    Commission : ${ agent_res.commission_am}<br>`;
                                                        if(agent_commsion_add_total){
                                                            data_apppend += `YES`;
                                                        }else{
                                                            data_apppend += `NO`;
                                                        }
                                data_apppend += `</td>
                                                <td>
                                                    Profit  : ${ agent_res.profit }
                                                    Cost    : ${ agent_res.total_cost }<br>
                                                    Sale    : ${ agent_res.total_sale }<br>
                                                </td>
                                                <td>
                                                    <a href="${url}" target="blank" class="btn btn-primary btn-sm">view Details</a>
                                                </td>
                                            </tr>`;
                        
                        $("#tbody_all_data_acc").append(data_apppend);
                        
                        i++;
                    });
                }
                
                if(agents_invoices_booking != null && agents_invoices_booking != ''){
                    $.each(agents_invoices_booking, function(key, agent_res) {
                        
                        total_sale              = parseFloat(total_sale) + agent_res.price;
                        total_paymet_amount     = parseFloat(total_paymet_amount) + agent_res.paid_amount;
                        total_remaning_amount   = parseFloat(total_remaning_amount) + agent_res.remaing_amount;
                        total_profit            = parseFloat(total_profit) + agent_res.profit ;
                        total_overpaid          = parseFloat(total_overpaid) + agent_res.over_paid_amount ;
                        
                        var invoice_id  = agent_res.invoice_id;
                        var url         = `invoice_Agent/${invoice_id}`;
                        
                        var remaing_amount = agent_res.remaing_amount;
                        var data_apppend =  `<tr>
                                                <td>${i}</td>
                                                <td>
                                                    Agent Id     : ${agents_dataS.agent_id}<br>
                                                    Agent Name   : ${agents_dataS.agent_name}<br>
                                                    Invoice No.  : ${agent_res.invoice_id}<br>
                                                    Company Name : ${agents_dataS.agent_company}<br>
                                                    Type         : Invoice
                                                </td>
                                                <td>
                                                    Total       : ${agent_res.price}<br>
                                                    Paid        : ${agent_res.paid_amount}<br>`;
                                                    if(remaing_amount >= 0){
                                                        data_apppend += `Remaining   : ${remaing_amount}<br>`;
                                                    }else{
                                                        data_apppend += `Remaining   : 0<br>`;
                                                    }
                                    data_apppend += `Outstanding : ${agent_res.over_paid_amount}
                                                </td>
                                                <td></td>
                                                <td>
                                                    Profit  : ${agent_res.profit}
                                                    Cost    : ${agent_res.total_cost}<br>
                                                    Sale    : ${agent_res.total_sale}<br>
                                                </td>
                                                <td><a href="${url}" target="blank" class="btn btn-primary btn-sm">view Details</a></td>
                                            </tr>`;
                        
                        $("#tbody_all_data_acc").append(data_apppend);
                        
                        i++;
                    });
                }
                total_sale              = total_sale.toFixed(2);
                total_paymet_amount     = total_paymet_amount.toFixed(2);
                total_remaning_amount   = total_remaning_amount.toFixed(2);
                total_profit            = total_profit.toFixed(2);
                total_overpaid          = total_overpaid.toFixed(2);
                
                $('#total_sale1').empty();
                $('#total_paymet_amount1').empty();
                $('#total_remaning_amount1').empty();
                $('#total_profit1').empty();
                $('#total_overpaid1').empty();
                
                $('#total_sale1').html(total_sale);
                $('#total_paymet_amount1').html(total_paymet_amount);
                $('#total_remaning_amount1').html(total_remaning_amount);
                $('#total_profit1').html(total_profit);
                $('#total_overpaid1').html(total_overpaid);
            }
        });
    </script>
    
    <script>
     <?php 
                                                            //  $total_sale += $agent_res->price;
                                                            //  $total_paymet_amount += $agent_res->paid_amount;
                                                            //  $total_remaning_amount += $agent_res->remaing_amount;
                                                            //  $total_over_paid = $agent_res->over_paid_amount;
                                                            ?>
                                                            
        function paid_amount(id,type,total,paid){
            $('#paid_amount').modal('show');
            $('#total_amount').val(total);
            $('#paid_amount_val').val(paid);
            $('#paid_type').val(type);
            $('#invoice_no').val(id);
            
        }
        
         function manageBalance(){
            $('#manage_balance').modal('show');
          
            
        }
       
       
         function paymentMethod(){
             var paymentMethod = $('#payment_method').val();
             if(paymentMethod == 'Cash'){
                 $('#transcation_id').css('display','none');
                 $('#account_id').css('display','none');
             }else{
                  $('#transcation_id').css('display','block');
                  $('#account_id').css('display','block');
             }
         }
                                                            
        function manageOverPaid(invoiceNum){
             $('#total_amount').val(0);
            $('#paid_amount_val').val(0);
             $('#unpaid_inv').html('');            
            console.log('Invoice Number is '+invoiceNum);
            
              $.ajax({
                    url: '{{ URL::to("getupaidInvoicesAgent") }}',
                    type: 'POST',
                    data: {
                        "_token":'{{ CSRF_token() }}',
                        "id": invoiceNum
                    },
                    success:function(data) {
                        var packInvList = JSON.parse(data);
                        
                         var packageTr = ``;
                         
                         var x = 1;
                         packInvList['package_inv_lists'].forEach((obj)=>{
                             var remainingVal = obj['tour_total_price'] - obj['total_paid_amount'];
                             packageTr += `<tr>
                                                <td>${x++}</td>
                                                <td>${obj['invoice_no']}
                                                <br> Type:Package
                                                </td>
                                                <td>${obj['tour_total_price']}</td>
                                                <td>${obj['total_paid_amount']}</td>
                                                <td>${remainingVal}</td>
                                                <td><button class="btn btn-primary" onclick="paid_amount('${obj['invoice_no']}','package',${obj['tour_total_price']},${obj['total_paid_amount']})">Pay Amount</button></td>
                                            </tr>`
                         })
                            
                        packInvList['inv_lists'].forEach((obj)=>{
                             var remainingVal = obj['total_sale_price_all_Services'] - obj['total_paid_amount'];
                             packageTr += `<tr>
                                                <td>${x++}</td>
                                                <td>${obj['id']}
                                                <br> Type:Invoice
                                                </td>
                                                <td>${obj['total_sale_price_all_Services']}</td>
                                                <td>${obj['total_paid_amount']}</td>
                                                <td>${remainingVal}</td>
                                                <td><button class="btn btn-primary" onclick="paid_amount('${obj['id']}','Invoice',${obj['total_sale_price_all_Services']},${obj['total_paid_amount']})">Pay Amount</button></td>
                                            </tr>`
                         })
                            $('#unpaid_inv').html(packageTr);            
                                        
                        console.log(packInvList);
                    }
              })
        }
        var totalSale = '<?php echo $total_sale ?>';
        var totalPaid = '<?php echo $total_paymet_amount ?>';
        var totalRemaning = '<?php echo $total_remaning_amount ?>';
        var totalOverpaid = '<?php echo $total_over_paid ?>';
        
        $('#total_revenue').html(totalSale);
        $('#total_paid').html(totalPaid);
        $('#outstanding').html(totalRemaning);
        $('#over_paid').html(totalSale);
    
    
        $(document).ready(function () {
            
            //View Modal Single Quotation
            $('.detail-btn').click(function() {
                const id = $(this).attr('data-id');
                $.ajax({
                    url: 'view_QuotationsID/'+id,
                    type: 'GET',
                    data: {
                        "id": id
                    },
                    success:function(data) {
                        var a                = data['a'];
                        var roundTripDetails = a['roundTripDetails'];
                        var oneWayDetails    = a['oneWayDetails'];
                        
                        console.log(a);
            
                        //Flight Details
                        $('#airline_name').html(oneWayDetails['airline_name']);
                        $('#deprturetime').html(oneWayDetails['deprturetime']);
                        $('#ArrivalTime').html(oneWayDetails['ArrivalTime']);
                        $('#deprturedate').html(oneWayDetails['deprturedate']);
                        $('#ArrivalDate').html(oneWayDetails['ArrivalDate']);
                        $('#departure').html(oneWayDetails['departure']);
                        $('#arrival').html(oneWayDetails['arrival']);
                        
                        $('#flighttype').html(a['flighttype']);
                        $('#flight_price').html(a['flight_price']);
                        
                        //Hotel Booking Details Makkkah
                        $('#dateinmakkah').html(a['dateinmakkah']);
                        $('#dateoutmakkah').html(a['dateoutmakkah']);
                        $('#hotel_name_makkah').html(a['hotel_name_makkah']);
                        $('#no_of_rooms_makkah').html(a['no_of_rooms_makkah']);
                        $('#Price_Per_Nights_Makkah').html(a['Price_Per_Nights_Makkah']);
                        $('#Makkah_total_price_cal').html(a['Makkah_total_price_cal']);
                        
                        //Hotel Booking Details Madinah
                        $('#dateinmadinah').html(a['dateinmadinah']);
                        $('#dateoutmadinah').html(a['dateoutmadinah']);
                        $('#hotel_name_madina').html(a['hotel_name_madina']);
                        $('#no_of_rooms_madina').html(a['no_of_rooms_madina']);
                        $('#price_per_night_madinah').html(a['price_per_night_madinah']);
                        $('#madinah_total_price_cal').html(a['madinah_total_price_cal']);
                        
                        
                        //Transfer Details
                        $('#transfer_vehicle').html(a['transfer_vehicle']);
                        $('#passenger').html(a['passenger']);
                        $('#pickuplocat').html(a['pickuplocat']);
                        $('#dropoflocat').html(a['dropoflocat']);
                        $('#trans_date').html(a['trans_date']);
                        $('#transf_price').html(a['transf_price']);
                        
                        //Visa Details
                        $('#visa_fees_adult').html(a['visa_fees_adult']);
                        $('#visa_fees_child').html(a['visa_fees_child']);
                        $('#visa_fees_price').html(a['visa_fees_price']);
                        
                        //Totals
                        $('#flight_price_total').html(a['flight_price']);
                        $('#Makkah_total_price_cal').html(a['Makkah_total_price_cal']);
                        $('#madinah_total_price_cal').html(a['madinah_total_price_cal']);
                        $('#transfers_head_total').html(a['transfers_head_total']);
                        $('#visa_fees').html(a['visa_fees_price']);
                        $('#grand_total_price').html(a['grand_total_price']);
            
                    }
                })
            });
    
        });

    </script>
    
    <script>
    
        function add_more_passenger(id){
            $('#add_more_P_div').empty();
            $('#invoice_Id_Input').val();
            $('#lead_name_ID').empty();
            $('#no_of_pax_days_ID').empty();
            $('#no_of_pax_days_Input').val();
            $('#add_more_P').css('display','');
            $('#more_P_D_div').empty();
            let I_id = id;
            $.ajax({
                url:"{{URL::to('get_single_Invoice')}}" + '/' + I_id,
                method: "GET",
                data: {
                	I_id:I_id
                },
                success:function(data){
                    var data1                   = data['data'];
                    var invoice_Id              = data1['id'];
                    var f_name                  = data1['f_name'];
                    var middle_name             = data1['middle_name'];
                    var no_of_pax_days          = data1['no_of_pax_days'];
                    $('#invoice_Id_Input').val(invoice_Id);         	  
                    $('#lead_name_ID').html(f_name+' '+middle_name);
                    $('#no_of_pax_days_ID').html(no_of_pax_days);
                    $('#no_of_pax_days_Input').val(no_of_pax_days);
                    
                    var count_P_Input = data1['count_P_Input'];
                    if(count_P_Input > 0 && count_P_Input != null && count_P_Input != ''){
                        $('#count_P_Input').val(count_P_Input);
                        $('#count_P_Input1').val(count_P_Input);
                    }else{
                        $('#count_P_Input').val(1);
                        $('#count_P_Input1').val(1);
                    }
                    
                    var more_Passenger_Data = data1['more_Passenger_Data'];
                    if(more_Passenger_Data != null && more_Passenger_Data != ''){
                        $('#more_Passenger_Data_Input').val(more_Passenger_Data);
                        var edit_M_P_Button = `<button class="btn btn-primary" id="edit_more_P" onclick="edit_more_P()" type="button">Edit more Passenger</button>`;
                        // $('#edit_M_P_Button').append(edit_M_P_Button);
                        
                        var more_Passenger_Data_decode = JSON.parse(data1['more_Passenger_Data']);
                        $.each(more_Passenger_Data_decode, function (key, value) {
                            var more_p_fname    = value.more_p_fname;
                            var more_p_lname    = value.more_p_lname;
                            var more_p_gender   = value.more_p_gender;
                            var more_p_passport = value.more_p_passport;
                            var more_p_image    = value.more_p_image;
                            var url_IandP       = `https://alhijaztours.net/public/uploads/package_imgs/`;
                            
                            var data =  `<div class="row" id="main_div_P_${invoice_Id}">
                                            <div class="col-lg-12">
                                                <h5>More Passenger details</h5>
                                            </div>
                                            <div class="col-lg-2 mb-3">
                                                <label for="label" class="form-label">First Name</label>
                                                <input class="form-control remove_readonly_prop" type="text" value="${more_p_fname}" name="" placeholder="" readonly>
                                            </div>
                                            <div class="col-lg-2 mb-3">
                                                <label for="label" class="form-label">Last Name</label>
                                                <input class="form-control remove_readonly_prop" type="text" value="${more_p_lname}" name="" placeholder="" readonly>
                                            </div>
                                            <div class="col-lg-2 mb-3">
                                                <label for="label" class="form-label">Gender</label>
                                                <select class="form-control" name="" readonly>`;
                                                    if(more_p_gender == 'Male'){
                            data +=                     `<option value="Male" selected>Male</option>
                                                        <option value="Female">Female</option>`;
                                                    }
                                                    else if(more_p_gender == 'Female'){
                            data +=                     `<option value="Male">Male</option>
                                                        <option value="Female" selected>Female</option>`;
                                                    }
                                                    else{
                            data +=                     `<option value="">Choose...</option>
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>`;
                                                    }
                            data +=             `</select>
                                            </div>
                                            <div class="col-lg-3 mb-3">
                                                <label for="image" class="form-label">Passport</label>
                                                <input class="form-control" type="text" value="${more_p_passport}" name="" readonly>
                                                <input class="form-control" type="file" value="${more_p_passport}" name="" hidden>
                                            </div>
                                            <div class="col-lg-3 mb-3">
                                                <label for="image" class="form-label">Image</label>
                                                <img src="${url_IandP}${more_p_image}" style="height: 80px;width: 80px;">
                                                <input class="form-control" type="text" name="" readonly hidden>
                                                <input class="form-control" type="file" name="" readonly hidden>
                                            </div>
                                        </div>`;
                            
                            if(more_p_fname != null){
                                $('#more_P_D_div').append(data);
                            }
                        });
                    }
                    
                    if(no_of_pax_days = count_P_Input){
                        $('#edit_M_P_Button').css('display','none')
                    }
                }
            }); 
        }
        
        $('#add_more_P').click(function() {
            $('#button_P').css('display','');
            
            var count_P = $('#count_P_Input').val();
            count_P     = parseFloat(count_P) + 1;
            
            var no_of_pax_days_Input    = $('#no_of_pax_days_Input').val();
            var id_P                    = $('#invoice_Id_Input').val();
            var data =  `<div class="row" id="main_div_P_${id_P}${count_P}">
                            <div class="col-lg-12">
                                <h5>More Passenger # ${count_P}</h5>
                            </div>
                            <div class="col-lg-2 mb-3">
                                <label for="label" class="form-label">First Name</label>
                                <input class="form-control" type="text" id="more_p_fname${id_P}_${count_P}" name="more_p_fname[]" placeholder="" required>
                            </div>
                            <div class="col-lg-2 mb-3">
                                <label for="label" class="form-label">Last Name</label>
                                <input class="form-control" type="text" id="more_p_lname${id_P}_${count_P}" name="more_p_lname[]" placeholder="" required>
                            </div>
                            <div class="col-lg-2 mb-3">
                                <label for="label" class="form-label">Gender</label>
                                <select class="form-control" id="more_p_gender${id_P}_${count_P}" name="more_p_gender[]" placeholder="Choose..." required>
                                    <option value="">Select</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <label for="image" class="form-label">Passport</label>
                                <input class="form-control" type="file" id="more_p_passport${id_P}_${count_P}" name="more_p_passport[]" placeholder="" required>
                            </div>
                            <div class="col-lg-3 mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input class="form-control" type="file" id="more_p_image${id_P}_${count_P}" name="more_p_image[]" placeholder="" required>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary" id="remove_more_P" onclick="removeMorePassenger(${id_P}${count_P})" type="button">Remove</button>
                            </div>
                        </div>`;
                        
            if(count_P <= no_of_pax_days_Input){
                $('#add_more_P_div').append(data);
                $('#count_P_Input').val(count_P);
            }else{
                $('#add_more_P').css('display','none');
                count_P = count_P - 1;
                $('#count_P_Input').val(count_P);
            }
        });
        
        function removeMorePassenger(id){
            $('#main_div_P_'+id+'').remove();
            var no_of_pax_days_Input    = $('#no_of_pax_days_Input').val();
            var count_P                 = $('#count_P_Input').val();
            var count_P_Input1          = $('#count_P_Input1').val();
            count_P                     = count_P - 1;
            $('#count_P_Input').val(count_P);
            if(count_P <= no_of_pax_days_Input){
                $('#add_more_P').css('display','');
            }
            
            if(count_P_Input = count_P_Input1){
                $('#button_P').css('display','none');
            }
        }
        
    </script>

@endsection
