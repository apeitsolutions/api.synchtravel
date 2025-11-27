<?php
//print_r($all_customer);die();
?>
@extends('template/frontend/userdashboard/layout/default')
 @section('content')
 <style>
     .cst_css
     {
         color: red;
    font-weight: bold;
     }
     .custom_css
     {
         float: right;
        margin-bottom: 30px;
     }
 </style>
 
 @if(session()->has('message'))
        <div x-data="{ show: true }" x-show="show"
             class="flex justify-between items-center bg-yellow-200 relative text-yellow-600 py-3 px-3 rounded-lg">
            <div>
                <span class="font-semibold text-yellow-700">   {{session('message')}}</span>
            </div>
            <div>
                <button type="button" @click="show = false" class=" text-yellow-700">
                    <span class="text-2xl">&times;</span>
                </button>
            </div>
        </div>
    @endif
 


    <div class="mt-5" id="">
        <div class="row">
            <div class="col-12">
                                <div class="card" style="background: #f1f5f8;">
                                    <div class="card-body">
<div class="row">
    <div class="col-md-8">
        <h4 class="">Custom Hotel Provider List</h4>
    </div>

</div>
                                        
                                        





                                        
                                       
                                                <table style="font-weight: bold;font-family: serif;" id="example_1" class="table dt-responsive nowrap w-100">
                                                    <thead class="theme-bg-clr">
                                                        <tr>
                                                            <th>id</th>
                                                            <th>Company Name</th>
                                                            <th>Provider</th>
                                                            <th>Total Payable</th>
                                                            <th>Markup Type</th>
                                                            <th>Markup Value</th>
                                                            <th>Action</th>
                                                           
                                                        </tr>
                                                    </thead>
                                                
                                                
                                                    <tbody>
                                                        <?php
                                                        if(isset($custom_hotel_provider))
                                                        {
                                                            $count=1;
                                                            foreach($custom_hotel_provider as $provider_res)
                                                            {
                                                               
                                                              ?>
                                                              
                                                        <tr>
                                                            <td>{{$count}}</td>
                                                            <td>{{ $provider_res->company_name}}</td>
                                                            <td>    {{$provider_res->provider_name}}</td>
                                                            <td>    {{$provider_res->balance }}</td>
                                                            <td>    {{ $provider_res->markup }}</td>
                                                            <td>    {{ $provider_res->markup_value }}</td>
                                                            <td>
                                                                <a href="{{ URL::to('super_admin/custom_hotel_provider_ledger/'.$provider_res->cust_provider_id.'') }}" class="btn btn-success btn-sm">ledger</a>
                                                            </td>
                                                           
                                                        </tr>
                                                        <?php
                                                        $count=$count+1;
                                                            }
                                                        }
                                                        
                                                        ?>
                                                    </tbody>
                                                </table>                                           
                                            
                                        
                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
                            </div><!-- end col-->
        </div>
    </div>
   

<script>
          
   $(document).ready(function () {

    $('#example_1').DataTable({
        scrollX: true,
        scrollY: true,
         order: [[0, 'desc']],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
        
    });
    
}); 

</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>




    <script>
   $(document).ready(function () {
        $("#slc_customer").change(function(){
            //alert('jcjsj');
            var currency = $('option:selected', this).attr('atr-currency');
            
console.log('currency'+currency);
            $('.add_currency').text(currency);
            $('#currency_slc').val(currency);
            
        });
   });
   
   
 
   
</script> 

 @endsection