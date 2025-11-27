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
 
 @if(session('success'))
        <div x-data="{ show: true }" x-show="show"
             class="flex justify-between items-center bg-yellow-200 relative text-yellow-600 py-3 px-3 rounded-lg">
            <div>
                <span class="font-semibold text-yellow-700">   {{session('success')}}</span>
            </div>
            <div>
                <button type="button" @click="show = false" class=" text-yellow-700">
                    <span class="text-2xl">&times;</span>
                </button>
            </div>
        </div>
    @endif
 
 @if(session('error'))
        <div x-data="{ show: true }" x-show="show"
             class="flex justify-between items-center bg-yellow-200 relative text-yellow-600 py-3 px-3 rounded-lg">
            <div>
                <span class="font-semibold text-yellow-700">   {{session('error')}}</span>
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
        <h4 class="">Hotel Provider List</h4>
    </div>

</div>
                                        
                                        





                                        
                                       
                                                <table style="font-weight: bold;font-family: serif;" id="example_1" class="table dt-responsive nowrap w-100">
                                                    <thead class="theme-bg-clr">
                                                        <tr>
                                                            <th>id</th>
                                                            <th>Company Name</th>
                                                            <th>Providers</th>
                                                            <th>Action</th>
                                                           
                                                        </tr>
                                                    </thead>
                                                
                                                
                                                    <tbody>
                                                        <?php
                                                        function check_provider_found($all_customer_providers,$check_provider){
                                                            if(isset($all_customer_providers) && !empty($all_customer_providers)){
                                                                foreach($all_customer_providers as $provider_res){
                                                                    if($provider_res->provider_id == $check_provider){
                                                                        return "Checked";
                                                                    }
                                                                }
                                                            }
                                                            
                                                            return "";
                                                        }
                                                        
                                                        if(isset($all_customer))
                                                        {
                                                            $count=1;
                                                            foreach($all_customer as $customer_res)
                                                            {
                                                                $customer_providers = $customer_res->hotelsProviders;
                                                              ?>
                                                              
                                                        <tr>
                                                            
                                                            <td>{{$count}}</td>
                                                            <td>{{ $customer_res->company_name}}</td>
                                                            <td>
                                                                <form action="{{ URL::to('update_customer_hotel_providers') }}" method="post">
                                                                    @csrf
                                                                    <input type="text" name="customer_id" hidden value="{{ $customer_res->id }}">
                                                                    <ul class="list-group">
                                                                        <?php
                                                                        foreach($all_providers as $provider_res){
                                                                                ?>
                                                                          <li class="list-group-item">
                                                                            <input class="form-check-input me-1" name="customerProviders[]" type="checkbox" <?php echo check_provider_found($customer_providers,$provider_res->provider_name); ?> value="{{ $provider_res->provider_name }}" id="{{ $customer_res->id."".$provider_res->provider_name }}">
                                                                            <label class="form-check-label" for="{{ $customer_res->id."".$provider_res->provider_name }}">
                                                                                @if($provider_res->provider_name == 'travelenda') 
                                                                                        Travelenda 
                                                                                    @elseif($provider_res->provider_name == 'hotel_beds')
                                                                                        Hotel Beds
                                                                                    @else
                                                                                        {{ $provider_res->provider_name }}
                                                                                    @endif
                                                                            </label>
                                                                          </li>
                                                                           <?php
                                                                            }
                                                                        ?>
                                                                    
                                                                    </ul>
                                                                    
                                                                   
                                                            </td>
                                                           
                                                            <td>
                                                                <button type="submit" class="btn btn-success btn-sm">Update</button>
                                                                </form>
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