@extends('template/frontend/userdashboard/layout/default')
@section('content')
<?php $currency=Session::get('currency_symbol'); $account_No=Session::get('account_No');
// dd($mange_currencies);
?>
<div class="card mt-5">
    <div class="card-body">
        <h4 class="header-title">Add Visa Supplier</h4>
        <form action="{{URL::to('submit_visa_type_against_sup')}}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf
                <div class="row">
                   <div class="col-md-12 mb-4">
                @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" style="background-color:#d4edda;" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

                @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" style="background-color:#f5cfcf;" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
            </div>
                    <div class="col-xl-4">
                        <div class="mb-3">
                            <div id="tooltip-container">
                                <label for="simpleinput" class="form-label no_of_pax_days">Select Country</label>
                                <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                    data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="No Of Pax for this package">
                                </i>
                            </div>
                           <?php
                        //   dd($visa_supplier);
                    
                           ?>
                           <select class="form-control" name="country"  required>
                               <option>Select</option>
                               @foreach($countries as $ctr)
                               <option attr="{{$ctr->name}}" value="{{$ctr->id}}">{{$ctr->name}}</option>
                               @endforeach
                              
                           </select>
                          
                          
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="mb-3">
                            <div id="tooltip-container">
                                <label for="simpleinput" class="form-label no_of_pax_days">Select Visa Supplier</label>
                                <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                    data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="No Of Pax for this package">
                                </i>
                            </div>
                           <?php
                        //   dd($visa_supplier);
                    
                           ?>
                           <select class="form-control" name="visa_supplier"  required>
                               <option>Select</option>
                                @foreach($visa_supplier as $visa_suppliers)
                                
                               <option value="{{$visa_suppliers->id}}">{{$visa_suppliers->visa_supplier_name}}</option>
                               @endforeach
                              
                           </select>
                          
                          
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="mb-3">
                            <div id="tooltip-container">
                                <label for="simpleinput" class="form-label no_of_pax_days">Select Visa Type</label>
                                <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                    data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="No Of Pax for this package">
                                </i>
                            </div>
                           <?php
                        //   dd($types);
                          
                           ?>
                           <select class="form-control" name="visa_type"  required>
                               <option>Select</option>
                                @foreach($types as $type)
                                
                               <option value="{{$type->id}}">{{$type->other_visa_type}}</option>
                               @endforeach
                              
                           </select>
                           
                          
                        </div>
                    </div>
                    
                    
                
                    
                    
                    
                    </div>
                    <div class="row">
                        <div class="col-xl-3">
                                <div class="mb-3">
                                    <div id="tooltip-container">
                                        <label for="simpleinput" class="form-label">Currency Conversion</label>
                                        <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                            data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Currency Convirsion">
                                        </i>
                                    </div>
                                    
                                   
                                
                                            <select class="form-control" name="currency_conversion" onchange="selectCurrencyrate()" id="currency_conversion">
                                                <option value="">Select Currency Conversion</option>
                                                @foreach($mange_currencies as $mange_currencies)
                                                <option attr_conversion_type="{{$mange_currencies->conversion_type}}" value='{{ json_encode($mange_currencies) }}'>{{$mange_currencies->purchase_currency}} TO  {{$mange_currencies->sale_currency}}</option>
                                                @endforeach
                                            </select>
                                            
                                            <input type="hidden" id="select_exchange_type"  name="conversion_type" value="" > 
                                </div>
                                </div>
                    <!--    <div class="col-xl-4">-->
                    <!--    <div class="mb-3">-->
                    <!--        <div id="tooltip-container">-->
                    <!--            <label for="simpleinput" class="form-label no_of_pax_days">Quantity</label>-->
                    <!--            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"-->
                    <!--                data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="No Of Pax for this package">-->
                    <!--            </i>-->
                    <!--        </div>-->
                          
                    <!--        <input type="visa_qty" name="visa_qty"  class="form-control " id="" required>-->
                           
                    <!--        <div class="invalid-feedback">-->
                    <!--            This Field is Required-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->
                    
                    <!--<div class="col-xl-4">-->
                    <!--    <div class="mb-3">-->
                    <!--        <div id="tooltip-container">-->
                    <!--            <label for="simpleinput" class="form-label no_of_pax_days">Visa Price</label>-->
                    <!--            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"-->
                    <!--                data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="No Of Pax for this package">-->
                    <!--            </i>-->
                    <!--        </div>-->
                           
                    <!--        <input type="text" name="visa_price"  class="form-control " id="" required>-->
                           
                    <!--        <div class="invalid-feedback">-->
                    <!--            This Field is Required-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->
                    <div class="col-xl-4">
                        <div class="mb-3">
                            <div id="tooltip-container">
                                <label for="simpleinput" class="form-label no_of_pax_days">From</label>
                                <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                    data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Contact Number for this package">
                                </i>
                            </div>
                           
                            <input type="date" name="availability_from"  class="form-control " id="" required>
                           
                            <div class="invalid-feedback">
                                This Field is Required
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="mb-3">
                            <div id="tooltip-container">
                                <label for="simpleinput" class="form-label no_of_pax_days">To</label>
                                <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                    data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Contact Number for this package">
                                </i>
                            </div>
                           
                            <input type="date" name="availability_to"  class="form-control " id="" required>
                           
                            <div class="invalid-feedback">
                                This Field is Required
                            </div>
                        </div>
                    </div>
                     <div class="col-xl-4" style="padding-left: 24px;">
                                    <label for="">Visa Price</label>
                                    <div class="input-group">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up purchase_currency">
                                               <?php echo $currency; ?>
                                            </a>
                                        </span>
                                        <input type="text" name="visa_price"  class="form-control " onchange="selectCurrencyrate()" id="visa_pricez" required>
                                    </div>
                                </div>
                                
                    <div class="col-xl-4" style="padding-left: 24px;">
                                    <label for="">Conversion Rate</label>
                                    <div class="input-group">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up conversion_currency">
                                               <?php echo $currency; ?>
                                            </a>
                                        </span>
                                        <input type="text" name="visa_price_conversion_rate"  class="form-control " onchange="selectCurrencyrate()" id="exchange_pricez" required>
                                    </div>
                                </div>
                    
                                <div class="col-xl-4" style="padding-left: 24px;">
                                    <label for="">Converted Price</label>
                                    <div class="input-group">
                                        <span class="input-group-btn input-group-append">
                                            <a class="btn btn-primary bootstrap-touchspin-up sale_currency">
                                               <?php echo $currency; ?>
                                            </a>
                                        </span>
                                        <input type="text" name="visa_converted_price"  class="form-control " onchange="selectCurrencyrate()" id="converted_pricez" required>
                                    </div>
                                </div>
                                 <div class="col-xl-4 mt-1">
                        <div class="mb-3">
                            <div id="tooltip-container">
                                <label for="simpleinput" class="form-label no_of_pax_days">Quantity</label>
                                <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                    data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="No Of Pax for this package">
                                </i>
                            </div>
                          
                            <input type="visa_qty" name="visa_qty"  class="form-control " id="" required>
                           
                            <div class="invalid-feedback">
                                This Field is Required
                            </div>
                        </div>
                    </div>
                    
                    
              
                   
                    <div style="float:right;">
                        <button class="btn btn-info" type="submit" name="submit">Submit</button>
                    </div>
                      
                </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>

   function selectCurrencyrate(){
   var value_c = $("#currency_conversion").val();
   var converstion_data = JSON.parse(value_c);
   console.log(converstion_data);
  
    var value_p        = converstion_data['purchase_currency'];
    var value_s        = converstion_data['sale_currency'];
 $(".purchase_currency").html(value_p);
 $(".sale_currency").html(value_s);
 $(".conversion_currency").html(value_p);
     var type = $('option:selected', "#currency_conversion").attr('attr_conversion_type');
    $("#select_exchange_type").val(type); 
    var visa_price = $("#visa_pricez").val();
     var exchange_price = $("#exchange_pricez").val();
     
     
   if(type == 'Divided'){
    //   alert('divide');
  
    if(visa_price){
        visa_price = visa_price;
    }else{
        visa_price = 0;
    }
     console.log(visa_price);
   
    if(exchange_price){
        exchange_price = exchange_price;
    }else{
        exchange_price = 0;
    }
    
    
   console.log(exchange_price);
   var converted_rate = parseFloat(visa_price) / parseFloat(exchange_price);
   
   converted_rate = converted_rate.toFixed(2)
    $("#converted_pricez").val(converted_rate);
    console.log("converted_rate"+converted_rate);
    
       
   }else{
        if(visa_price){
        visa_price = visa_price;
    }else{
        visa_price = 0;
    }
     console.log(visa_price);
   
    if(exchange_price){
        exchange_price = exchange_price;
    }else{
        exchange_price = 0;
    }
    
    var converted_rate = parseFloat(visa_price) * parseFloat(exchange_price);
    $("#converted_pricez").val(converted_rate);
   }
}
    $(document).ready(function(){
        selectCities();
        var value_c = $("#currency_conversion").val();
   const usingSplit    = value_c.split(' ');
    console.log(usingSplit);
    var value_p        = usingSplit['0'];
    var value_s        = usingSplit['3'];
 $(".purchase_currency").html(value_p);
 $(".sale_currency").html(value_s);
 $(".conversion_currency").html(value_p);
    })
    
    function selectCities(){
        var country = $('#property_country').val();
        console.log(country);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ url('/country_cites') }}",
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                "id": country,
            },
            success: function(result){
                console.log('cites is call now');
              console.log(result);
              $('#property_city').html(result);
            },
            error:function(error){
                console.log(error);
            }
        });
    }
    
    var v_ID = 1;
    $('#add_more_phone_no').click(function (){
        var data = `<div id="more_phone_no_div_${v_ID}" class="row">
        
                        <div class="col-xl-5"></div>
                    
                        <div class="col-xl-5">
                            <div class="mb-3">
                                <div id="tooltip-container">
                                    <label for="simpleinput" class="form-label no_of_pax_days">More Contact Number</label>
                                    <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                        data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="More Contact Number for this package">
                                    </i>
                                </div>
                                
                                <input type="number" id="more_phone_no_${v_ID}" name="more_phone_no[]" class="form-control" required>
                                
                                <div class="invalid-feedback">
                                    This Field is Required
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-2" style="margin-top: 31px;">
                            <button type="button" onclick="remove_more_phone_no(${v_ID})" class="btn btn-primary">Remove</button>
                        </div>
                        
                    </div>`;
        $('#add_more_phone_no_Div').append(data);
        
        v_ID = parseFloat(v_ID) + 1;
    });
    
    function remove_more_phone_no(id){
        $('#more_phone_no_div_'+id+'').remove();
    }
    
</script>
@stop