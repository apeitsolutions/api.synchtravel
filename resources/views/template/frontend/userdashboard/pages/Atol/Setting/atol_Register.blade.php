@extends('template/frontend/userdashboard/layout/default')
@section('content')

<?php $currency=Session::get('currency_symbol'); ?>

<div class="dashboard-content">
    
    <h3 style="color:#a30000">Register Atol</h3>
    <form action="{{URL::to('add_Register_Atol')}}" method="post" enctype="multipart/form-data" style="padding-bottom: 40px">
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
            
            <div class="col-md-12">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                        <div class="row">
                                
                            <div class="col-xl-4">
                                <div class="mb-3">
                                    <label class="form-label">Company Name</label>
                                    <input type="text" name="company_Name" id="company_Name" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="col-xl-4">
                                <div class="mb-3">
                                    <label class="form-label">Atol Number</label>
                                    <input type="number" name="atol_Number" id="atol_Number" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="col-xl-4">
                                <div class="mb-3">
                                    <label class="form-label">Sub Agent</label>
                                    <input type="text" name="atol_Sub_Agent" id="atol_Sub_Agent" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="col-xl-4">
                                <div class="mb-3">
                                    <label class="form-label">Country</label>
                                    <select class="form-control" name="atol_country" id="atol_country"  onchange="get_city_atol()" required>
                                        @foreach($all_countries as $country_res)
                                            <option value="{{ $country_res->id }}">{{ $country_res->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-xl-4">
                                <div class="mb-3">
                                    <label class="form-label">City</label>
                                    <select class="form-control" name="atol_city" id="atol_city" required></select>
                                </div>
                            </div>
                            
                            <div class="col-xl-4">
                                <div class="mb-3">
                                    <label class="form-label">Date</label>
                                    <input type="datetime-local" name="atol_date" id="atol_date" class="form-control" required>
                                </div>
                            </div>
                                
                            
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
    
    <div class="dashboard-content">
        <h3 style="color:#a30000;font-size: 40px;text-align:center">Atol List</h3>
        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <div class="dashboard-list-box dash-list margin-top-0">
                    <div class="row">
                        <div class="col-md-12">
                             <table id="myTable" class="display nowrap table  table-bordered" style="width:100%;">
                                <thead class="theme-bg-clr">
                                    <tr>
                                        <th style="text-align: center;">Sr</th>
                                        <th style="text-align: center;">Company Name</th>
                                        <th style="text-align: center;">Atol Number</th>
                                        <th style="text-align: center;">Sub Agent</th>
                                        <th style="text-align: center;">Country</th>
                                        <th style="text-align: center;">City</th>
                                        <th style="text-align: center;">Date</th>
                                    </tr>
                                </thead>
                                <tbody style="text-align: center;">
                                    @if(isset($atol_detail) && $atol_detail != null && $atol_detail != '')
                                        <?php $i = 1; ?>
                                        @foreach($atol_detail as $value)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>{{ $value->company_Name }}</td>
                                                <td>{{ $value->atol_Number }}</td>
                                                <td>{{ $value->atol_Sub_Agent }}</td>
                                                <td>
                                                    @foreach($all_countries as $country_res)
                                                        @if($country_res->id == $value->atol_country)
                                                            {{ $country_res->name }}
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>{{ $value->atol_city }}</td>
                                                <td>
                                                    <?php
                                                        $atol_date = date("d-m-Y H:i", strtotime($value->atol_date ?? '' ));
                                                        echo $atol_date;
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php $i++; ?>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>

@endsection

@section('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBmwlQFQKwxZ4D8nRbsWVRTBFUHMO-lUOY&sensor=false&libraries=places"></script>

    <script>
    
        $(document).ready(function() {
            get_city_atol();
        });
        
        function get_city_atol(){
            var country         = $('#atol_country').find('option:selected').attr('value');
            // var country2        = JSON.parse(country);
            // var id              = country2['id'];
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
                    var options = result.options;
                    $('#atol_city').html(options);
                },
                error:function(error){
                }
            });
        }
    </script>
@stop