@extends('template/frontend/userdashboard/layout/default')
@section('content')






 
<div class="card mt-5">
    <div class="card-body">
        <h4 class="header-title">Add Hotel Supplier</h4>
        <form action="{{URL::to('submit_edit_hotel_suppliers',[$data_res->id])}}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf
            <div class="row">
                
                <div class="col-xl-6">
                    <div class="mb-3">
                        <div id="tooltip-container">
                            <label for="simpleinput" class="form-label">Supplier Name</label>
                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Supplier Name">
                            </i>
                        </div>
                            <input   name="room_supplier_name" value="{{$data_res->room_supplier_name ?? ''}}" required class="form-control">
                    </div>
                </div>
                
                <div class="col-xl-6">
                    <div class="mb-3">
                        <div id="tooltip-container">
                            <label for="simpleinput" class="form-label no_of_pax_days">Email</label>
                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="No Of Pax for this package">
                            </i>
                        </div>
                      
                        <input type="email" name="email" value="{{$data_res->email ?? ''}}"  class="form-control " id="" required>
                       
                        <div class="invalid-feedback">
                            This Field is Required
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-5">
                    <div class="mb-3">
                        <div id="tooltip-container">
                            <label for="simpleinput" class="form-label no_of_pax_days">Contact Person Name</label>
                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="No Of Pax for this package">
                            </i>
                        </div>
                       
                        <input type="text" name="contact_person_name" value="{{$data_res->contact_person_name ?? ''}}"  class="form-control " id="" required>
                       
                        <div class="invalid-feedback">
                            This Field is Required
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-5">
                    <div class="mb-3">
                        <div id="tooltip-container">
                            <label for="simpleinput" class="form-label no_of_pax_days">Contact Number</label>
                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="No Of Pax for this package">
                            </i>
                        </div>
                       
                        <input type="number" name="phone_no" value="{{$data_res->phone_no ?? ''}}"  class="form-control " id="" required>
                       
                        <div class="invalid-feedback">
                            This Field is Required
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-2" style="margin-top: 31px;text-align: center;">
                    <button type="button" id="add_more_phone_no" class="btn btn-primary">Add more</button>
                </div>
                
                @if(isset($data_res->more_phone_no) && $data_res->more_phone_no != null && $data_res->more_phone_no != '')
                    <?php
                        $v_ID           = 1;
                        $more_phone_no  = json_decode($data_res->more_phone_no); 
                    ?>
                    @foreach($more_phone_no as $more_phone_noS)
                        <div id="more_phone_no_div_{{ $v_ID }}" class="row">
                            <div class="col-xl-5"></div>
                            <div class="col-xl-5">
                                <div class="mb-3">
                                    <div id="tooltip-container">
                                        <label for="simpleinput" class="form-label no_of_pax_days">More Contact Number</label>
                                        <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                            data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="More Contact Number for this package">
                                        </i>
                                    </div>
                                    <input type="number" id="more_phone_no_{{ $v_ID }}" name="more_phone_no[]" value="{{ $more_phone_noS }}" class="form-control" required>
                                    <div class="invalid-feedback">
                                        This Field is Required
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-2" style="margin-top: 31px;">
                                <button type="button" onclick="remove_more_phone_no({{ $v_ID }})" class="btn btn-primary">Remove</button>
                            </div>
                        </div>
                        
                        <?php $v_ID++; ?>
                    @endforeach
                    <input type="hidden" value="{{ $v_ID }}" id="v_ID_id">
                @endif
                
                <div id="add_more_phone_no_Div"></div>
                
                <div class="col-xl-12">
                    <div class="mb-3">
                        <div id="tooltip-container">
                            <label for="simpleinput" class="form-label">Content</label>
                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Additional Information of Package">
                            </i>
                        </div>
                        <!--<textarea name="content" id="" class="contentP summernote" cols="142" rows="10"></textarea>-->
                        <textarea required name="address"  class="contentP" cols="135" rows="5">{{ $data_res->address ?? ''}}</textarea>
                    </div>
                </div>
                
                <div class="col-xl-6">
                    <div class="mb-3">
                        <div id="tooltip-container">
                            <label for="simpleinput" class="form-label no_of_pax_days">Country</label>
                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="No Of Pax for this package">
                            </i>
                        </div>
                       
                       <select class="form-control" name="country" onchange="selectCities123()" id="property_country" required>
                           
                           @foreach($countries as $ctr)
                           <option <?php if($ctr->id == $data_res->country) echo "selected";  ?> attr="{{$ctr->name}}" value="{{$ctr->id}}">{{$ctr->name}}</option>
                           @endforeach
                       </select>
                       
                       
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="mb-3">
                        <div id="tooltip-container">
                            <label for="simpleinput" class="form-label no_of_pax_days">City</label>
                            <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="No Of Pax for this package">
                            </i>
                        </div>
                       
                        <select name="city" id="property_city" class="form-control">
                            
                            
                   
                        </select>
                       
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
    $(document).ready(function(){
        selectCities123();
    })

    function selectCities123(){
        var country = $('#property_country').val();
        console.log(country);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ url('/country_cites1') }}",
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
                "id": country,
            },
            success: function(result){
                console.log('cites is call now');
                console.log(result.data_city);
                
                
                var slc_city='<?php echo $data_res->city; ?>';
                
                
                $.each(result.data_city, function (i, item) {
                 
                  $('#property_city').append( '<option attr="'+item.id+'" value="'+item.name+'">'+item.name+'</option>' );   
              
               
            });
            $('#property_city').val(slc_city).change();
            //$('#property_city').select(slc_city);
                
                
                
            //   var options = result.options;
            //   $('#property_city').html(options);
            },
            error:function(error){
                console.log(error);
            }
        });
    }
    
    var v_ID = $('#v_ID_id').val();
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
        
        var v_ID = $('#v_ID_id').val();
        v_ID = parseFloat(v_ID) - 1;
        $('#v_ID_id').val(v_ID);
    }
    
</script>
@stop