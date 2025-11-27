@extends('template/frontend/userdashboard/layout/default')
@section('content')

<?php // dd($all_Users); ?>

    <div class="dashboard-content">
        <div class="row mt-2">
            <div class="col-md-8">
                <h4 style="color:#fffff">Hotels List</h4>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <div class="dashboard-list-box dash-list margin-top-0">
                    <div class="row" id="show_append_data"></div>
                    
                    <div class="row" id="hide_data">
                        @foreach($user_hotels as $hotel_res)
                            <div class="col-sm-4">
                                <div class="list-box-listing-content" style="margin-top: 15px;">
                                    <div class="inner" style="text-align: center;">
                                        <span style="color: #c31958;">Customer Name : 
                                            @if(isset($all_Users) && $all_Users != null && $all_Users != '')
                                                @foreach($all_Users as $all_Users_value)
                                                    @if($hotel_res['owner_id'] == $all_Users_value->id)
                                                        <b>{{ $all_Users_value->name }}</b>
                                                        <?php
                                                            $webiste_Address    = $all_Users_value->webiste_Address;
                                                            $url                = $webiste_Address.'/public/uploads/package_imgs'; 
                                                        ?>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </span><br>
                                        <span>Hotel Name : {{ $hotel_res->property_name }}</span><br>
                                        <span>City Name :  {{ $hotel_res->property_city }}</span>
                                        <div class="star-rating" data-rating="5.0">
                                            <div class="rating-counter">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if($i <= $hotel_res->star_type)
                                                        <i class="fa fa-star" style="color:#FDCC0D;"></i>
                                                    @else
                                                        <i class="sl sl-icon-star" style="color:#FDCC0D;"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-box-listing" style="padding: 10px;">
                                    <div class="list-box-listing-img" style="height:180px;text-align: center;">
                                        @if(isset($hotel_res->property_img) && $hotel_res->property_img != null && $hotel_res->property_img != '' && $webiste_Address != null)
                                            <img src="{{ $url }}/{{ $hotel_res->property_img }}" alt="" style="height: 190px;width: 300px;">
                                        @else
                                            <img src="{{ asset('public/images/customerSubcription/no_img_found.jpg') }}" alt="" style="height: 190px;width: 300px;">
                                        @endif
                                    </div>
                                </div>
                                <div class="list-box-listing-content" style="margin-top: 15px;text-align: center;">  
                                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Manage Rooms</button>
                                    <div class="dropdown-menu">
                                        <a href="{{URL::to('view_rooms_Admin')}}/{{$hotel_res->id}}" target="_blank" class="dropdown-item"><i class="sl sl-icon-pencil"></i>View Rooms</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts_hotel_list')
@stop