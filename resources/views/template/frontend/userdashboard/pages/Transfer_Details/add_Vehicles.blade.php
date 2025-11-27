@extends('template/frontend/userdashboard/layout/default')
@section('content')


<div class="dashboard-content">
    
    <h4 style="color:#a30000">Add New Vehicle</h4>
    <form action="{{URL::to('add_new_vehicle')}}" method="post" enctype="multipart/form-data">
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
            
            <div class="col-md-8">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation" style="width: 280px;text-align: center;">
                        <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true">GENERAL</a>
                    </li>
                    <li class="nav-item" role="presentation" style="width: 280px;text-align: center;">
                        <a class="nav-link" id="meta-info-tab" data-bs-toggle="tab" href="#meta-info" role="tab" aria-controls="meta-info" aria-selected="false">META INFO</a>
                    </li>
                    <li class="nav-item" role="presentation" style="width: 280px;text-align: center;">
                        <a class="nav-link" id="policy-tab" data-bs-toggle="tab" href="#policy" role="tab" aria-controls="policy" aria-selected="false">POLICY</a>
                    </li>
                </ul>
                <!-- Main Tab Contant Start -->
                <div class="tab-content" id="myTabContent">
                    <!-- General Tab Start -->
                    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                        <div class="row">
                            <div class="col-md-12" style="padding: 15px;">
                                <label for="">Vehicle Name</label>
                                <input id="vehicle_Name" type="text" class="form-control" name="vehicle_Name" value="" autocomplete="vehicle_Name" autofocus required>
                            </div>
                            <div class="col-md-12" style="padding: 15px;">
                                <label for="">Vehicle Discription</label>
                                <textarea name="vehicle_Description" row="5" class="form-control"  autocomplete="vehicle_Description" placeholder="Enter Vehicle Description" style="height: 200px;" required></textarea>
                            </div>
                            <div class="col-md-6" style="padding: 15px;">
                                <label for="">Vehicle Image</label>
                                <input id="vehicle_image" type="file" class="form-control" name="vehicle_image" value="" autocomplete="vehicle_image" autofocus required>
                            </div> 
                        </div>
                    </div>
                    <!-- Meta Info Tab Start -->
                    <div class="tab-pane fade" id="meta-info" role="tabpanel" aria-labelledby="meta-info-tab">
                        <div class="row">
                            <div class="col-md-12" style="padding: 15px;">
                                <label for="">META TITLE</label>
                                <input id="meta_title" type="text" class="form-control" name="meta_title" autocomplete="meta_title" autofocus>
                            </div>
                                <div class="col-md-12" style="padding: 15px;">
                                    <label for="">META KEYWORDS</label>
                                    <textarea name="meta_keywords" row="5" class="form-control" autocomplete="meta_keywords" placeholder="Enter meta keyword"></textarea>
                                </div>
                                <div class="col-md-12" style="padding: 15px;">
                                    <label for="">META DESCRIPTION</label>
                                    <textarea name="meta_desc" row="5" class="form-control" autocomplete="meta_desc" placeholder="Enter meta description"></textarea>
                                </div>
                        </div>
                    </div>
                    <!-- Policy Tab Start -->
                    <div class="tab-pane fade" id="policy" role="tabpanel" aria-labelledby="policy-tab">
                        <div class="row">
                            <div class="col-md-12" style="padding: 15px;">
                                <label for="">PAYMENT OPTION</label>
                                <select name="payment_option" id="" class="form-control">
                                    <option value="At Arrival">At Arrival</option>
                                </select>
                            </div>

                            <div class="col-md-12" style="padding: 15px;">
                                <label for="">POLICY AND TERMS</label>
                                <textarea name="policy_and_terms" row="5" class="form-control"  autocomplete="policy_and_terms" placeholder="Enter policy and terms"></textarea>
                            </div> 
                        </div>
                    </div>
                </div>
                <!-- Main Tab Contant End -->
            </div>
            
            <div class="col-md-4" style="margin-top:-.8rem;">
                
                <div class="card">
                    <div class="card-header">
                        Vehicle Additional Details
                    </div>
                    <div class="card-body">
                        
                        <div class="col-md-12" style="padding-bottom: 15px">
                            <label for="">Status</label>
                            <select name="vehicle_Status" id="" class="form-control">
                                <option value="Enabled">Enabled</option>
                            </select>
                        </div>

                        <div class="col-md-12" style="padding-bottom: 15px">
                            <label for="">Vehicle type</label>
                            <select name="vehicle_Type" id="" class="form-control">
                                <option value="Choose..." selected="selected">Choose...</option>
                                <option value="Van">Van</option>
                                <option value="Luxuxry">Luxuxry</option>
                                <option value="Economy">Economy</option>
                                <option value="Comfort">Comfort</option>
                                <option value="Mini">Mini</option>
                                <option value="Standard">Standard</option>
                            </select>
                        </div>
                        
                        <div class="col-md-12" style="padding-bottom: 15px">
                            <label for="">Passenger</label>
                            <select name="vehicle_Passenger" id="" class="form-control">
                                <option value="Choose..." selected="selected">Choose...</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                            </select>
                        </div>
                        
                        <div class="col-md-12" style="padding-bottom: 15px">
                            <label for="">Car Doors</label>
                            <select name="vehicle_Door" id="" class="form-control">
                                <option value="Choose..." selected="selected">Choose...</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </div>
                        
                        <div class="col-md-12" style="padding-bottom: 15px">
                            <label for="">Transmission</label>
                            <select name="vehicle_Transmission" id="" class="form-control">
                                <option value="Choose..." selected="selected">Choose...</option>
                                <option value="Auto">Auto</option>
                                <option value="Manual">Manual</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>
                        
                        <div class="col-md-12" style="padding-bottom: 15px">
                            <label for="">Baggage</label>
                            <select name="vehicle_Baggage" id="" class="form-control">
                                <option value="Choose..." selected="selected">Choose...</option>
                                <option value="x1">x1</option>
                                <option value="x2">x2</option>
                                <option value="x3">x3</option>
                                <option value="x4">x4</option>
                                <option value="x5">x5</option>
                                <option value="x6">x6</option>
                            </select>
                        </div>
                        
                        <div class="col-md-12" style="padding-bottom: 15px">
                            <label for="">Airport Picking</label>
                            <select name="vehicle_Picking" id="" class="form-control">
                                <option value="Choose..." selected="selected">Choose...</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        
                    </div>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-md-12 text-right mt-3">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
</div>


@endsection
@section('scripts')
<script>
    $(document).ready(function(){
        selectCities();
    })
    $("#checkall").click(function (){
     if ($("#checkall").is(':checked')){
        $(".form-check-input").each(function (){
           $(this).prop("checked", true);
           });
        }else{
           $(".form-check-input").each(function (){
                $(this).prop("checked", false);
           });
        }
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
</script>
@stop



