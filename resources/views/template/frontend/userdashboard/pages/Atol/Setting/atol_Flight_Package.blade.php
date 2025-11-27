@extends('template/frontend/userdashboard/layout/default')
@section('content')

<?php $currency=Session::get('currency_symbol'); ?>

<div class="dashboard-content">
    
    <form action="{{URL::to('add_Register_Flight_Package')}}" method="post" enctype="multipart/form-data" style="padding-bottom: 40px">
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
            
            <h3 style="color:#a30000">Atol Flight</h3>
            
            <div class="col-md-12">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                        <div class="row">
                                
                            <div class="col-xl-4">
                                <div class="mb-3">
                                    <label class="form-label">Select Atol</label>
                                    <select name="atol_Detail_Flight" id="atol_Detail_Flight" class="form-control" required>
                                        <option value="">Select Atol</option>
                                        @if(isset($addAtolFlightPackage) && $addAtolFlightPackage != null && $addAtolFlightPackage != '')
                                            @if(isset($atol_detail) && $atol_detail != null && $atol_detail != '')
                                                @foreach($atol_detail as $value)
                                                    @if($addAtolFlightPackage->atol_id_Flight == $value->id)
                                                        <option value='{{ json_encode($value) }}' selected>{{ $value->company_Name }}</option>
                                                    @else
                                                        <option value='{{ json_encode($value) }}'>{{ $value->company_Name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @else
                                            @if(isset($atol_detail) && $atol_detail != null && $atol_detail != '')
                                                @foreach($atol_detail as $value)
                                                    <option value='{{ json_encode($value) }}'>{{ $value->company_Name }}</option>
                                                @endforeach
                                            @endif
                                        @endif
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-xl-4">
                                <div class="mb-3">
                                    <label class="form-label">Atol Fee</label>
                                    @if(isset($addAtolFlightPackage) && $addAtolFlightPackage != null && $addAtolFlightPackage != '')
                                        <input step="any" type="number" name="atol_Fee_Flight" id="atol_Fee_Flight" class="form-control" value="{{ $addAtolFlightPackage->atol_Fee_Flight }}" required>
                                    @else
                                        <input step="any" type="number" name="atol_Fee_Flight" id="atol_Fee_Flight" class="form-control" required>
                                    @endif
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            
            <h3 style="color:#a30000">Atol Package</h3>
            
            <div class="col-md-12">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                        <div class="row">
                            
                            <div class="col-xl-4">
                                <div class="mb-3">
                                    <label class="form-label">Select Atol</label>
                                    <select name="atol_Detail_Package" id="atol_Detail_Package" class="form-control">
                                        <option value="">Select Atol</option>
                                        @if(isset($addAtolFlightPackage) && $addAtolFlightPackage != null && $addAtolFlightPackage != '')
                                            @if(isset($atol_detail) && $atol_detail != null && $atol_detail != '')
                                                @foreach($atol_detail as $value)
                                                    @if($addAtolFlightPackage->atol_id_Package == $value->id)
                                                        <option value='{{ json_encode($value) }}' selected>{{ $value->company_Name }}</option>
                                                    @else
                                                        <option value='{{ json_encode($value) }}'>{{ $value->company_Name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @else
                                            @if(isset($atol_detail) && $atol_detail != null && $atol_detail != '')
                                                @foreach($atol_detail as $value)
                                                    <option value='{{ json_encode($value) }}'>{{ $value->company_Name }}</option>
                                                @endforeach
                                            @endif
                                        @endif
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-xl-4">
                                <div class="mb-3">
                                    <label class="form-label">Atol Fee</label>
                                    @if(isset($addAtolFlightPackage) && $addAtolFlightPackage != null && $addAtolFlightPackage != '')
                                        <input step="any" type="number" name="atol_Fee_Package" id="atol_Fee_Package" class="form-control" value="{{ $addAtolFlightPackage->atol_Fee_Package }}">
                                    @else
                                        <input step="any" type="number" name="atol_Fee_Package" id="atol_Fee_Package" class="form-control">
                                    @endif
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
    
</div>

@endsection

@section('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBmwlQFQKwxZ4D8nRbsWVRTBFUHMO-lUOY&sensor=false&libraries=places"></script>
@stop