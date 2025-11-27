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
                    <span class="breadcrumb-item active">View Cash Ledger</span>
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
    
                                    <h2 class="header-title">Cash Ledger</h2>
                                    <ul class="nav nav-tabs nav-bordered mb-3"></ul>
                                    
                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="scroll-horizontal-preview">
                                            
                                            <table id="scroll-horizontal-datatable" class="table table-striped w-100 nowrap">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align:center">SR No.</th>
                                                        <th style="text-align:center">Invoice No.</th>
                                                        <th style="text-align:center">Invoice Date</th>
                                                        <th style="text-align:center">Agent Name</th>
                                                        <th style="text-align:center">Debit</th>
                                                        <th style="text-align:center">Credit</th>
                                                        <th style="text-align:center">Balance</th>
                                                    </tr>
                                                </thead>
                                                <tbody style="text-align:center">
                                                    @if(isset($invoice_data))
                                                        @foreach($invoice_data as $valI)
                                                            @if(isset($payment_data))
                                                                <?php $amount_Paid = 0; ?>
                                                                @foreach($payment_data as $val)
                                                                    @if($valI->id == $val->invoice_Id)
                                                                        <?php
                                                                            $due_date   = date('Y-m-d',strtotime($val->created_at. ' + 15 days'));
                                                                            $created_at = date('Y-m-d',strtotime($val->created_at));
                                                                        ?>
                                                                        <tr>
                                                                            <th>{{ $i }}</th>
                                                                            <td>{{ $val->generate_id }}</td>
                                                                            <td>{{ $created_at }}</td>
                                                                            <td>{{ $val->agent_Name }}</td>
                                                                            @if($val->amount_Paid > 0)
                                                                                <td></td>
                                                                            @else
                                                                                <td>{{$invoice_data[0]->currency_symbol}} {{ $val->total_Amount }}</td>
                                                                            @endif
                                                                            <td>{{$invoice_data[0]->currency_symbol}} {{ $val->recieved_Amount }}</td>
                                                                            <?php 
                                                                                $amount_Paid = $amount_Paid + $val->amount_Paid;
                                                                            ?>
                                                                            <td>{{$invoice_data[0]->currency_symbol}} {{ $val->total_Amount - $amount_Paid }}</td>
                                                                        </tr>
                                                                        <?php $i++; ?>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>                                          
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
        
@endsection