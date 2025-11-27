@extends('template/frontend/userdashboard/layout/default')
@section('content')
<?php
// print_r($visa_supplier);die;
?>
<div class="card mt-5">
    <div class="card-body">
        <h4 class="header-title">Add Visa Supplier</h4>
        <form action="{{URL::to('update_visa_suppliers')}}" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
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
                    <div class="col-xl-6">
                        <div class="mb-3">
                            <div id="tooltip-container">
                                <label for="simpleinput" class="form-label">Supplier Name</label>
                                <i class="dripicons-information" style="font-size: 17px;" id="title_Icon"
                                    data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Supplier Name">
                                </i>
                            </div>
                                <input  value="{{$visa_supplier->visa_supplier_name ?? "" }}" name="visa_supplier_name" required class="form-control"> 
                                <input type="hidden" value="{{$visa_supplier->id}}" name="visa_supplier_id" required class="form-control">
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
                          
                            <input type="email"  value="{{$visa_supplier->email ?? "" }}" name="email"  class="form-control " id="" required>
                           
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
                           
                            <input type="text"  value="{{$visa_supplier->contact_person_name ?? "" }}" name="contact_person_name"  class="form-control " id="" required>
                           
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
                                    data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="right" title="Contact Number for this package">
                                </i>
                            </div>
                           
                            <input type="number" value="{{$visa_supplier->phone_no ?? "" }}" name="phone_no"  class="form-control " id="" required>
                           
                            <div class="invalid-feedback">
                                This Field is Required
                            </div>
                        </div>
                    </div>
                    
                    <!--<div class="col-xl-2" style="margin-top: 31px;text-align: center;">-->
                    <!--    <button type="button" id="add_more_phone_no" class="btn btn-primary">Add more</button>-->
                    <!--</div>-->
                    
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
                            <textarea required name="address" class="contentP" cols="135" rows="5">{{$visa_supplier->address ?? "" }}</textarea>
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
                           
                           <select class="form-control" name="country" onchange="selectCities()" id="property_country" required>
                               <option>Select</option>
                               @foreach($countries as $ctr)
                               <option attr="{{$ctr->name}}" value="{{$ctr->id}}">{{$ctr->name}}</option>
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
                       <option>Select</option>
                               <option>lahore</option>
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
        selectCities();
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