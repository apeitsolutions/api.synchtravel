@extends('template/frontend/userdashboard/layout/default')
@section('content')
    
    <?php 
        $i          = 1;
        $j          = 1;
        $currdate   = date('Y-m-d');
        $currdateT  = date('Y-m-d');
        // dd($total_Sdue);
    ?>

    <div class="content-wrapper">
        
        <section class="content" style="padding: 30px 50px 0px 50px;">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12 col-6">
                <nav class="breadcrumb push">
                    <a class="breadcrumb-item" href="#">Dashboard</a> 
                    <span class="breadcrumb-item active">View Payable Ledger</span>
                </nav>
              </div>
            </div>
          </div>
        </section>
        
        <section class="content">
            <div class="container-fluid"> 
                <div class="content">
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
    
                                    <h2 class="header-title">Payable Ledger</h2>
                                    <ul class="nav nav-tabs nav-bordered mb-3"></ul>
                                    
                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="scroll-horizontal-preview">
                                            
                                            <table class="table table-bordered dataTables_wrapper no-footer" style="border: 1px solid black;">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align: center;">Cuurent Date</th>
                                                        <th style="text-align: center;">Total Dues Till date</th>
                                                    </tr>
                                                </thead>
                                                <tbody style="text-align:center">
                                                    <tr>
                                                        <td>{{ $currdateT }}</td>
                                                        <td>{{$invoice_data[0]->currency_symbol}} {{ $Total_InvoiceA }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            
                                            
                                            
                                            <div class="table-responsive">
                                                <div id="example_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer"> 
                                       
                                            <table id="example_1" class="table dt-responsive nowrap w-100">
                                                <thead class="">
                                                    <tr>
                                                        <th style="text-align:center">SR No.</th>
                                                        <th style="text-align:center">Invoice Date</th>
                                                        <th style="text-align:center">Invoice Number</th>
                                                        <th style="text-align:center">Agent Name</th>
                                                        <th style="text-align:center">Total Amount</th>
                                                        <th style="text-align:center">Due date</th>
                                                        <th style="text-align:center">Balance Due</th>
                                                        @if(isset($invoice_payment_count))
                                                            <?php 
                                                                for($IPCvar=1; $IPCvar<$invoice_payment_count; $IPCvar++)
                                                                { 
                                                            ?>
                                                                    <th style="text-align:center">Payment Date</th>
                                                                    <th style="text-align:center">Payment{{$IPCvar}}</th>
                                                            <?php 
                                                                }
                                                            ?>
                                                        @endif
                                                    </tr>
                                                </thead>
                                                <tbody style="text-align:center">
                                                    @if(isset($invoice_data))
                                                        @foreach($invoice_data as $val)
                                                            <?php
                                                                $due_date   = date('Y-m-d',strtotime($val->created_at. ' + 15 days'));
                                                                $created_at = date('Y-m-d',strtotime($val->created_at));
                                                            ?>
                                                            <tr>
                                                                <th>{{ $i }}</th>
                                                                <td>{{ $created_at }}</td>
                                                                <td>{{ $val->generate_id }}</td>
                                                                <td>{{ $val->agent_Name }}</td>
                                                                <?php $price = $val->quad_grand_total_amount + $val->triple_grand_total_amount + $val->double_grand_total_amount * $val->no_of_pax_days; ?>
                                                                <td>{{$invoice_data[0]->currency_symbol}} {{ $price }}</td>
                                                                <td>{{ $due_date }}</td>
                                                                <!--Price-->
                                                                @if(isset($total_Sdue))
                                                                    <?php $AP = 0 ?>
                                                                    @foreach($total_Sdue as $total_SdueVal)
                                                                        @if($val->id == $total_SdueVal->invoice_Id)
                                                                            <?php
                                                                                $amount_Paid    = $total_SdueVal->amount_Paid;
                                                                                $AP             = $AP + $amount_Paid;
                                                                            ?>
                                                                        @endif
                                                                    @endforeach
                                                                    <td>{{$invoice_data[0]->currency_symbol}} {{ $price - $AP }}</td>
                                                                @endif
                                                                <!--Date and Amount-->
                                                                @if(isset($payment_data))
                                                                    @foreach($payment_data as $valP)
                                                                        @if($val->created_at !== $valP->created_at)
                                                                            @if($val->id == $valP->invoice_Id)
                                                                                @if($valP->amount_Paid !== null && $valP->amount_Paid !== 0 && $valP->amount_Paid !== '')
                                                                                    <td>{{ $valP->date }}</td>
                                                                                    <td>{{$invoice_data[0]->currency_symbol}} {{ $valP->amount_Paid }}</td>
                                                                                @else
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                @else
                                                                @endif
                                                            </tr>
                                                            <?php $i++; ?>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>    
                                            
                                            </div>
                                            </div>
                                            
                                            
                                        </div>
                                    
                                        <div class="tab-pane" id="multi-item-code">
                                            <p>Please include following css file at <code>head</code> element</p>
    
                                            <pre>
                                                <span class="html escape">
                                                    &lt;!-- Datatables css --&gt;
                                                    &lt;link href=&quot;assets/css/vendor/select.bootstrap5.css&quot; rel=&quot;stylesheet&quot; type=&quot;text/css&quot; /&gt;
                                                </span>
                                            </pre> <!-- end highlight-->
    
                                            <p>Make sure to include following js files at end of <code>body</code> element</p>
    
                                            <pre class="mb-0">
                                                <span class="html escape">
                                                    &lt;!-- Datatables js --&gt;
                                                    &lt;script src=&quot;assets/js/vendor/dataTables.select.min.js&quot;&gt;&lt;/script&gt;
                                                </span>
                                            </pre> <!-- end highlight-->
                                            <pre class="mb-0">
                                                <span class="html escape">
                                                    &lt;table id=&quot;selection-datatable&quot; class=&quot;table dt-responsive nowrap w-100&quot;&gt;
                                                        &lt;thead&gt;
                                                            &lt;tr&gt;
                                                                &lt;th&gt;Name&lt;/th&gt;
                                                                &lt;th&gt;Position&lt;/th&gt;
                                                                &lt;th&gt;Office&lt;/th&gt;
                                                                &lt;th&gt;Age&lt;/th&gt;
                                                                &lt;th&gt;Start date&lt;/th&gt;
                                                                &lt;th&gt;Salary&lt;/th&gt;
                                                            &lt;/tr&gt;
                                                        &lt;/thead&gt;
                                                    
                                                    
                                                        &lt;tbody&gt;
                                                            &lt;tr&gt;
                                                                &lt;td&gt;Tiger Nixon&lt;/td&gt;
                                                                &lt;td&gt;System Architect&lt;/td&gt;
                                                                &lt;td&gt;Edinburgh&lt;/td&gt;
                                                                &lt;td&gt;61&lt;/td&gt;
                                                                &lt;td&gt;2011/04/25&lt;/td&gt;
                                                                &lt;td&gt;$320,800&lt;/td&gt;
                                                            &lt;/tr&gt;
                                                            &lt;tr&gt;
                                                                &lt;td&gt;Garrett Winters&lt;/td&gt;
                                                                &lt;td&gt;Accountant&lt;/td&gt;
                                                                &lt;td&gt;Tokyo&lt;/td&gt;
                                                                &lt;td&gt;63&lt;/td&gt;
                                                                &lt;td&gt;2011/07/25&lt;/td&gt;
                                                                &lt;td&gt;$170,750&lt;/td&gt;
                                                            &lt;/tr&gt;
                                                        &lt;/tbody&gt;
                                                    &lt;/table&gt;
                                                </span>
                                            </pre> <!-- end highlight-->
                                        </div> <!-- end preview code-->
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
        <script>
   $(document).ready(function () {
    
    $('#example_1').DataTable({
        scrollX: true,
        
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
        
    });
    
}); 
</script>   
@endsection