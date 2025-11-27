@extends('template/frontend/userdashboard/layout/default')
@section('content')


<div class="dashboard-content">
    <h4 style="color:#a30000">Hotels List</h4>
    <div class="row">
<div class="col-lg-12 col-sm-12">
<div class="dashboard-list-box dash-list margin-top-0">
    <div class="row">
        @foreach($user_hotels as $hotel_res)
        <div class="col-sm-4">
            <div class="list-box-listing">
                <div class="list-box-listing-img" style="height:180px;">
                <a href="#"><img src="{{ asset('public/images/hotels/') }}/{{ $hotel_res['property_img'] }}" alt=""></a>
                </div>
                <div class="list-box-listing-content">
                    <div class="inner">
                    <a href="dashboard-listdetail.html/index.html"><h3>{{ $hotel_res['property_name'] }}</h3></a>
                    <span>{{ $hotel_res['city_name'] }}, {{ $hotel_res['country_name'] }}</span>
                    <div class="star-rating" data-rating="5.0">
                    <div class="rating-counter">
                    @for ($i = 1; $i <= 5; $i++)
                        @if($i <= $hotel_res['star_type'])
                        <i class="fa fa-star" style="color:#FDCC0D;"></i>
                        
                        @else
                        <i class="sl sl-icon-star" style="color:#FDCC0D;"></i>
                        @endif
                    @endfor
                    </div>
                    </div>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    </div>
                    <a href="#" class="button gray"><i class="sl sl-icon-pencil"></i>Rooms</a>
                    <a href="#" class="button  btn-warning"><i class="sl sl-icon-close"></i>Facility</a>
                    <a href="#" class="button  gray"><i class="sl sl-icon-close"></i>Photos</a>

                    <a href="#" class="button  gray"><i class="sl sl-icon-close"></i>Amenities</a>
                    <a href="#" class="button  btn-info"><i class="sl sl-icon-close"></i>Policies</a>
                    <a href="#" class="button  btn-info"><i class="sl sl-icon-close"></i>Disc</a>
                    <a href="#" class="button  btn-success"><i class="sl sl-icon-close"></i>Payments & Tax</a>
                    <a href="#" class="button  btn-success"><i class="sl sl-icon-close"></i>Payment Mode</a>

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