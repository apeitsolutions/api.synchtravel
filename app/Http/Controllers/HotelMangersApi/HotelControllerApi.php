<?php

namespace App\Http\Controllers\HotelMangersApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\hotel_manager\Rooms;
use App\Models\admin\RoomsType;
use App\Models\hotel_manager\RoomGallery;
use App\Models\country;
use App\Models\city;
use Session;
use concat;
use Auth;
use DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\hotel_manager\Hotels;
use App\Models\MetaInfo;
use App\Models\Policies;
use App\Models\Agent;
use App\Models\addManageInvoice;

class HotelControllerApi extends Controller
{
    public function hotels_fillters(Request $request){
    $hotel_city = $request->hotel_city;
    
    $hotels = Hotels::where('property_city',$hotel_city)
    ->where('owner_id',$request->customer_id)
    ->get();
    
    return response()->json([
    'hotels'=>'Hotels Are Found',
    'hotels_data' => $hotels,
    ]);
    
    }
    
    public function hotels_fillters_by_name(Request $request){
        $slc_hotel_name = $request->slc_hotel_name;
        
        $hotels = Hotels::where('property_name',$slc_hotel_name)->get();
        
        return response()->json([
        'hotels'=>'Hotels Are Found',
        'hotels_data' => $hotels,
        ]);
    
    } 
    
    public function getCurrentWeek(){
    
        $monday = strtotime("last monday");
        
        $monday = date('w', $monday)==date('w') ? $monday+(7*86400) : $monday;
        
        $sunday = strtotime(date("Y-m-d",$monday)." +6 days");
        
        $this_week_sd = date("Y-m-d",$monday);
        
        $this_week_ed = date("Y-m-d",$sunday);
        
        
        
        return $data = ['first_day' => $this_week_sd, 'last_day' => $this_week_ed];
    
    }
    
    public function hotel_arrival_list(){
        $date=date('Y-m-d');
        
        $year = date('Y') . '-12-31';
        
        $first_day_of_this_month = date('Y-m-d',strtotime(date('Y-m-01')));
        
        $last_day_of_this_month = date('Y-m-d',strtotime(date('Y-m-t')));
        $current_week = $this->getCurrentWeek();
        
        
        $arrival_detail_today= DB::table('hotel_booking')->orderBy('id', 'desc')->where('check_in','=',date('y-m-d'))->where('provider','!=',NULL)->select('id','search_id','check_in','check_out','lead_passenger_details','provider','hotel_checkavailability','rooms_checkavailability','tboDetailRS','travellandadetailRS','travellandaSelectionRS','hotelbeddetailRS','hotelbedSelectionRS','ratehawk_details_rs1','ratehawk_selection_rs','created_at','booking_status','hotelbeddetailRQ')->get();
        $arrival_detail_week= DB::table('hotel_booking')->orderBy('id', 'desc')->wherebetween('check_in', [$current_week['first_day'],$current_week['last_day']])->where('provider','!=',NULL)->select('id','search_id','check_in','check_out','lead_passenger_details','provider','hotel_checkavailability','rooms_checkavailability','tboDetailRS','tboSelectionRS','travellandadetailRS','travellandaSelectionRS','hotelbeddetailRS','hotelbedSelectionRS','ratehawk_details_rs1','ratehawk_selection_rs','created_at','booking_status','tbo_BookingDetail_rs','hotelbeddetailRQ')->get();
        $arrival_detail_this_month= DB::table('hotel_booking')->orderBy('id', 'desc')->wherebetween('check_in', [$first_day_of_this_month,$last_day_of_this_month])->where('provider','!=',NULL)->select('id','search_id','check_in','check_out','lead_passenger_details','provider','hotel_checkavailability','rooms_checkavailability','tboDetailRS','tboSelectionRS','travellandadetailRS','travellandaSelectionRS','hotelbeddetailRS','hotelbedSelectionRS','ratehawk_details_rs1','ratehawk_selection_rs','created_at','booking_status','hotelbeddetailRQ')->get();
        
        
        
        return response()->json([
        'arrival_detail_today' => $arrival_detail_today,
        'arrival_detail_week' => $arrival_detail_week,
        'arrival_detail_this_month' => $arrival_detail_this_month,
        
        'first_day_this_week' => $current_week['first_day'],
        'last_day_this_week' => $current_week['last_day'],
        'first_day_of_this_month' => $first_day_of_this_month,
        'last_day_of_this_month' => $last_day_of_this_month,
        
        ]);
    }
    
    public function hotel_departure_list(){
        $date=date('Y-m-d');
        
        $year = date('Y') . '-12-31';
        
        $first_day_of_this_month = date('Y-m-d',strtotime(date('Y-m-01')));
        
        $last_day_of_this_month = date('Y-m-d',strtotime(date('Y-m-t')));
        $current_week = $this->getCurrentWeek();
        
        $arrival_detail_today= DB::table('hotel_booking')->orderBy('id', 'desc')->where('check_out','=',date('y-m-d'))->where('provider','!=',NULL)->select('id','search_id','check_in','check_out','lead_passenger_details','provider','hotel_checkavailability','rooms_checkavailability','tboDetailRS','tboSelectionRS','travellandadetailRS','travellandaSelectionRS','hotelbeddetailRS','hotelbedSelectionRS','ratehawk_details_rs1','ratehawk_selection_rs','created_at','booking_status')->get();
        $arrival_detail_week= DB::table('hotel_booking')->orderBy('id', 'desc')->wherebetween('check_out', [$current_week['first_day'],$current_week['last_day']])->where('provider','!=',NULL)->select('id','search_id','check_in','check_out','lead_passenger_details','provider','hotel_checkavailability','rooms_checkavailability','tboDetailRS','tboSelectionRS','travellandadetailRS','travellandaSelectionRS','hotelbeddetailRS','hotelbedSelectionRS','ratehawk_details_rs1','ratehawk_selection_rs','created_at','booking_status')->get();
        $arrival_detail_this_month= DB::table('hotel_booking')->orderBy('id', 'desc')->wherebetween('check_out', [$first_day_of_this_month,$last_day_of_this_month])->where('provider','!=',NULL)->select('id','search_id','check_in','check_out','lead_passenger_details','provider','hotel_checkavailability','rooms_checkavailability','tboDetailRS','tboSelectionRS','travellandadetailRS','travellandaSelectionRS','hotelbeddetailRS','hotelbedSelectionRS','ratehawk_details_rs1','ratehawk_selection_rs','created_at','booking_status')->get();
        
        
        
        return response()->json([
        'departure_detail_today' => $arrival_detail_today,
        'departure_detail_week' => $arrival_detail_week,
        'departure_detail_this_month' => $arrival_detail_this_month,
        
        ]);
    }
    
    public function showeditHotelFrom(Request $request){
        $all_countries = country::all();
        
        $id = $request->customer_id;
        $hotel_id = $request->id;
        $user_hotels = Hotels::find($hotel_id);
        $MetaInfo =  MetaInfo::where('hotel_id',$hotel_id)->first();
        $Policies =  Policies::where('hotel_id',$hotel_id)->first();
        //   $user_hotels = Hotels::join('countries','hotels.property_country','=','countries.id')
        //                  ->join('cities','hotels.property_city','=','cities.name')
        //                  ->where('hotels.owner_id',$id)
        //                  ->orderBy('hotels.created_at', 'desc')
        //                  ->get(['hotels.*','countries.name','cities.name']);
        
        // $city_D     = DB::table('cities')->get();
        // dd($hotel_id);
        
        return response()->json([
            'all_countries' => $all_countries,
            'user_hotels' => $user_hotels,
            'MetaInfo' => $MetaInfo,
            'Policies' => $Policies,
            // 'city_D' => $city_D,
        ]);
        // return view('template/frontend/userdashboard/pages/hotel_manager.add_hotel',compact('all_countries'));
   }
    
    public function updateHotel(Request $request){
        $id     = $request->id;
        $hotel  = Hotels::find($id);
        if($hotel){
            // dd($request->property_country);
            
            // if(isset($hotel->from_owner_id) && isset($hotel->customer_id) && $hotel->from_owner_id == $request->customer_id){
                
            // }
            
            // $hotel->image_Url_Other_Dashboard =  NULL; 
            
            $hotel->property_name       = $request->property_name; 
            $hotel->currency_symbol     = $request->currency_symbol;
            $hotel->currency_value      = $request->currency_value;
            $hotel->property_desc       = $request->property_desc; 
            $hotel->property_google_map = $request->property_google_map; 
            $hotel->latitude            = $request->latitude; 
            $hotel->longitude           = $request->longitude;
            $hotel->property_country    = $request->property_country; 
            $hotel->property_city       = $request->property_city;  
            $hotel->price_type          = $request->price_type; 
            $hotel->star_type           = $request->star_type; 
            $hotel->status              = $request->status; 
            $hotel->property_type       = $request->property_type; 
            $hotel->b2c_markup          = $request->b2c_markup; 
            $hotel->b2b_markup          = $request->b2b_markup; 
            $hotel->b2e_markup          = $request->b2e_markup; 
            $hotel->service_fee         = $request->service_fee; 
            $hotel->tax_type            = $request->tax_type; 
            $hotel->tax_value           = $request->tax_value; 
            $hotel->facilities          = $request->facilities; 
            $hotel->hotel_email         = $request->hotel_email; 
            $hotel->hotel_website       = $request->hotel_website; 
            $hotel->property_phone      = $request->property_phone; 
            $hotel->property_address    = $request->property_address;
            $hotel->room_gallery        = $request->room_gallery;
            $hotel->property_img        = $request->property_img; 
            $user_id                    = $request->customer_id;
            $hotel->owner_id            = $user_id;
            $result                     = $hotel->update();
        }
        else{
            $result = 'Not Updated'; 
        }
       
        $hotelId = $id;
        // Save Meta Info
        $metaInfo = MetaInfo::where('hotel_id',$hotelId)->first();
        if($metaInfo){
            $metaInfo->meta_title       = $request->meta_title; 
            $metaInfo->keywords         = $request->keywords; 
            $metaInfo->meta_desc        = $request->meta_desc; 
            $metaInfo->hotel_id         = $hotelId;
            $meta_info_Result           = $metaInfo->update();   
        }
        else{
            $meta_info_Result           = 'Not Updated'; 
        }
        
        // Save Poilices
        $policies                       = Policies::where('hotel_id',$hotelId)->first();
        if($policies){
            $policies->check_in_form    = $request->hotel_check_in;
            $policies->check_out_to     = $request->hotel_check_out;
            $policies->payment_option   = $request->payment_option;
            $policies->policy_and_terms = $request->policy_and_terms;
            $policies->hotel_id         = $hotelId;
            // dd($hotel);
            $policies_Result            = $policies->update();   
        }
        else{
            $policies_Result            = 'Not Updated'; 
        }
        
        return response()->json([
            'message'                   => 'Success',
            'result'                    => $result,
            'meta_info_Result'          => $meta_info_Result,
            'policies_Result'           => $policies_Result,
        ]);
    }
   
    public function index(Request $request){
        // dd('ok');
        $id = $request->customer_id;
        // $user_hotels    = Hotels::join('countries','hotels.property_country','=','countries.id')
        //                     ->join('cities','hotels.property_city','=','cities.name')
        //                     ->where('hotels.owner_id',$id)
        //                     ->orderBy('hotels.created_at', 'desc')
        //                     ->get(['hotels.*','countries.name','cities.name']);
        
        // $user_hotels    = Hotels::where('owner_id',$id)
        //                     ->orWhereJsonContains('allowed_Clients', [['client_Id' => $id]])
        //                     ->orderBy('created_at', 'desc')->get();
        
        $all_Hotels     = [];
        $user_hotels    = DB::table('hotels')->where('owner_id',$id)->orderBy('hotels.created_at', 'desc')->get();
        //$allowd_Hotels  = DB::table('allowed_Hotels_Rooms')->where('client_Id',$id)->orderBy('created_at', 'desc')->get(); 
        $allowd_Hotels  = DB::table('allowed_Hotels_Rooms')->where('customer_id',$id)->orderBy('created_at', 'desc')->get();
        
        if($allowd_Hotels->isEmpty()){
            // dd('IF');
            $all_Hotels = $user_hotels;
        }else{
            foreach($allowd_Hotels as $val_AH){
                $allowd_hotel    = DB::table('hotels')->where('id',$val_AH->hotel_Id)->orderBy('hotels.created_at', 'desc')->first();
                if($allowd_hotel != null){
                    array_push($all_Hotels,$allowd_hotel);
                }
            }
            
            foreach($user_hotels as $val_HD){
                array_push($all_Hotels,$val_HD);
            }
        }
        
        $collect_Hotels = collect($all_Hotels);
        $unique_Hotels  = $collect_Hotels->unique('id')->values()->all();
        
        // dd($unique_Hotels);
        
        return response()->json([
            'user_hotels' => $unique_Hotels,
        ]);
    }
    
    // Other Dashboard
    public function hotel_list_All(Request $request){
        $id             = $request->customer_id;
        $user_hotel_arr = [];
        $user_hotels    = DB::table('hotels')
                            ->where('owner_id','!=',$id)
                            // ->where('owner_id',41)
                            ->join('meta_infos','hotels.id','meta_infos.hotel_id')
                            ->join('policies','hotels.id','policies.hotel_id')
                            ->where('hotels.image_Url_Other_Dashboard',NULL)
                            ->orderBy('hotels.created_at', 'desc')
                            ->get();
                            // ->take(10)->get();
                            
                            // dd($user_hotels);
                            
        foreach($user_hotels as $val_UH){
            $user_Data              = DB::table('customer_subcriptions')->where('id',$val_UH->owner_id)->select('dashboard_Address','webiste_Address')->first();
            if(isset($user_Data->dashboard_Address) && $user_Data->dashboard_Address != null && $user_Data->dashboard_Address != ''){
                $val_UH->image_Url_Other_Dashboard = $user_Data->dashboard_Address;
            }else{
                $val_UH->image_Url_Other_Dashboard = $user_Data->webiste_Address;
            }
            
            $already_Add_Check      = DB::table('hotels')->where('owner_id',$request->customer_id)->where('from_hotel_id',$val_UH->hotel_id)->first();
            if($already_Add_Check == null){
                array_push($user_hotel_arr,$val_UH);
            }
        }
        
        $unique_User_Hotels = collect($user_hotel_arr)->unique('hotel_id')->values()->all();
        
        // dd($unique_User_Hotels);
        
        return response()->json([
            'user_hotels' => $unique_User_Hotels,
        ]);
    }
    
    public function getURL($clientData){
        if($clientData->Auth_key == config('token_AlhijazTours')){
            $dashboardURL   = config('dashboardURL_AlhijazTours');
        }else if($clientData->Auth_key == config('token_HaramynHotel')){
            $dashboardURL   = config('dashboardURL_HaramynHotel');
        }else if($clientData->Auth_key == config('token_AlhijazRooms')){
            $dashboardURL   = config('dashboardURL_AlhijazRooms');
        }else if($clientData->Auth_key == config('token_SidraTours')){
            $dashboardURL   = config('dashboardURL_SidraTours');
        }else if($clientData->Auth_key == config('token_Alsubaee')){
            $dashboardURL   = config('dashboardURL_Alsubaee');
        }else if($clientData->Auth_key == config('token_Alif')){
            $dashboardURL   = config('dashboardURL_Alif');
        }else if($clientData->Auth_key == config('token_UmrahShop')){
            $dashboardURL   = config('dashboardURL_UmrahShop');
        }else if($clientData->Auth_key == config('token_HashimTravel')){
            $dashboardURL   = config('dashboardURL_HashimTravel');
        }else if($clientData->Auth_key == config('token_HaramaynRooms')){
            $dashboardURL   = config('dashboardURL_HaramaynRooms');
        }else if($clientData->Auth_key == config('token_AlmnhajHotel')){
            $dashboardURL   = config('dashboardURL_AlmnhajHotel');
        }else if($clientData->Auth_key == config('token_SynchTravel')){
            $dashboardURL   = config('dashboardURL_SynchTravel');
        }else if($clientData->Auth_key == config('token_HaramaynHotelsOld')){
            $dashboardURL   = config('dashboardURL_HaramaynHotelsOld');
        }else{
            $dashboardURL   = '';
        }
        return $dashboardURL;
    }
    
    public function view_Add_Hotel_Test(Request $request){
        $draw               = json_decode($request->draw);
        $start              = json_decode($request->start);
        $rowperpage         = json_decode($request->length);
        $columnIndex_arr    = json_decode($request->order);
        $columnName_arr     = json_decode($request->columns);
        $order_arr          = json_decode($request->order);
        $search_arr         = json_decode($request->search);
        $columnIndex        = $columnIndex_arr[0]->column ?? 0;
        $columnName         = $columnName_arr[$columnIndex]->data ?? 'created_at';
        $columnSortOrder    = $order_arr[0]->dir ?? 'desc';
        $searchValue        = $search_arr->value;
        $query              = Hotels::with([
                                    'metaInfo' => function ($q) {
                                        $q->select('meta_title', 'keywords', 'meta_desc', 'hotel_id'); // include hotel_id for relationship
                                    },
                                    'policy' => function ($q) {
                                        $q->select('check_in_form', 'check_out_to', 'payment_option', 'policy_and_terms', 'hotel_id'); // include hotel_id too
                                    }
                                ])
                                ->where('hotels.owner_id', '!=', $request->customer_id)
                                ->where('hotels.SU_id',$request->SU_id)
                                ->whereNull('hotels.image_Url_Other_Dashboard')
                                ->select('hotels.*')->orderBy('hotels.created_at', 'desc');
        $totalRecords       = $query->count();
        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('hotels.id', 'like', "%$searchValue%")
                ->orwhere('hotels.property_name', 'like', "%$searchValue%")
                ->orwhere('hotels.property_city', 'like', "%$searchValue%")
                ->orwhere('hotels.star_type', 'like', "%$searchValue%")
                ->orWhereHas('metaInfo', function ($q) use ($searchValue) {
                    $q->where('meta_infos.meta_title', 'like', "%$searchValue%")
                      ->orWhere('meta_infos.keywords', 'like', "%$searchValue%")
                      ->orWhere('meta_infos.meta_desc', 'like', "%$searchValue%");
                })
                ->orWhereHas('policy', function ($q) use ($searchValue) {
                    $q->where('policies.check_in_form', 'like', "%$searchValue%")
                      ->orWhere('policies.check_out_to', 'like', "%$searchValue%")
                      ->orWhere('policies.payment_option', 'like', "%$searchValue%")
                      ->orWhere('policies.policy_and_terms', 'like', "%$searchValue%");
                });
            });
        }
        $totalRecordswithFilter     = $query->count();
        $records                    = $query->orderBy($columnName, $columnSortOrder)->skip($start)->take($rowperpage)->get();
        $data_arr                   = [];
        $requestCLient              = DB::table('customer_subcriptions')->where('id',  $request->customer_id)->first();
        if($requestCLient->Auth_key == config('token_HaramaynRooms')){
            $requestDashboardURL    = config('dashboardURL_HaramaynRooms');
        }
        
        if($requestCLient->Auth_key == config('token_AlmnhajHotel')){
            $requestDashboardURL    = config('dashboardURL_AlmnhajHotel');
        }
        $i                          = 1;
        foreach ($records as $hotel_res) {
            $already_Add_Check      = DB::table('hotels')->where('owner_id',$request->customer_id)->where('from_hotel_id',$hotel_res->id)->first();
            if($already_Add_Check){}else{
                // Meta & Policy
                $hotelArray = $hotel_res->toArray();
                if (!empty($hotel_res->metaInfo)) {
                    $hotelArray = array_merge($hotelArray, $hotel_res->metaInfo->toArray());
                }
                if (!empty($hotel_res->policy)) {
                    $hotelArray = array_merge($hotelArray, $hotel_res->policy->toArray());
                }
                unset($hotelArray['meta_info'], $hotelArray['policy']);
                $hotel_res_encode = htmlspecialchars(json_encode($hotelArray), ENT_QUOTES, 'UTF-8');
                // Meta & Policy
                
                // Image
                $user_Data      = DB::table('customer_subcriptions')->where('id',$hotel_res->owner_id)->first();
                if(isset($user_Data->dashboard_Address) && $user_Data->dashboard_Address != null && $user_Data->dashboard_Address != ''){
                    $hotel_res->image_Url_Other_Dashboard = $user_Data->dashboard_Address;
                }else{
                    $hotel_res->image_Url_Other_Dashboard = $user_Data->webiste_Address;
                }
                
                $dashboardURL       = $this->getURL($user_Data);
                if($hotel_res->property_img != null && $hotel_res->property_img != ''){
                    if(isset($hotel_res->image_Url_Other_Dashboard) && $hotel_res->image_Url_Other_Dashboard != null && $hotel_res->image_Url_Other_Dashboard != ''){
                        $hotelImage = '<img src="'.$hotel_res->image_Url_Other_Dashboard.'/public/uploads/package_imgs/'.$hotel_res->property_img.'" style="height: 300px;width: 100%;">';
                    }else{
                        $hotelImage = '<img src="'.$dashboardURL.'/public/uploads/package_imgs/'.$hotel_res->property_img.'" alt="hotel img" style="height: 300px;width: 100%;">';
                    }
                }else{
                    $hotelImage     = '<img src="'.$dashboardURL.'/public/image_Not_Found.png/" style="height: 300px;width: 100%;">';
                }
                // Image
                
                $action =   '<div class="list-box-listing-content" style="margin-top: 15px;margin-bottom: 15px;text-align: center;">
                                <form action="'.$requestDashboardURL.'/hotel_manger/add_Hotel_OD" method="get" enctype="multipart/form-data" onsubmit="return confirm(\'Do you want to add hotel?\')">
                                    <input type="hidden" name="_token" value="'.csrf_token().'">
                                    <input type="hidden" value="'.$hotel_res_encode.'" name="hotel_Details">
                                    <button class="btn btn-primary" type="submit">Add Hotel</button>
                                </form>
                            </div>';
                $data_arr[]             = [
                    "id"                => $i,
                    "property_img"      => $hotelImage,
                    "property_name"     => $hotel_res->property_name,
                    "property_city"     => $hotel_res->property_city,
                    "created_at"        => \Carbon\Carbon::parse($hotel_res->created_at)->format('d-F-Y'),
                    "action"            => $action
                ];
                
                $i++;
            }
        }
        
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        ];
        
        return response()->json($response);
    }
    
    public function add_Hotel_OD(Request $req){
        $id             = $req->customer_id;
        $token          = $req->token;
        $hotel_Details  = json_decode($req->hotel_Details);
        // dd($hotel_Details->hotel_id);
        
        DB::beginTransaction();
        try {
            $request                    = $hotel_Details;
            $checkHotel = Hotels::where('from_hotel_id',$request->hotel_id)->where('from_owner_id',$request->owner_id)->where('owner_id',$req->customer_id)->first();
            if($checkHotel){
                return response()->json(['status'=>'error','message'=>'Hotel Already Added']);  
            }
            
            $hotel                      = new Hotels;
            $hotel->property_name       = $request->property_name; 
            $hotel->currency_symbol     = $request->currency_symbol; 
            
            if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
                $hotel->SU_id =  $request->SU_id;
            }
            
            $hotel->currency_value      = $request->currency_value; 
            $hotel->property_desc       = $request->property_desc; 
            $hotel->property_google_map = $request->property_google_map; 
            $hotel->latitude            = $request->latitude; 
            $hotel->longitude           = $request->longitude;
            $hotel->property_country    = $request->property_country; 
            $hotel->property_city       = $request->property_city;  
            $hotel->price_type          = $request->price_type; 
            $hotel->star_type           = $request->star_type; 
            $hotel->status              = $request->status; 
            $hotel->property_type       = $request->property_type; 
            $hotel->b2c_markup          = $request->b2c_markup; 
            $hotel->b2b_markup          = $request->b2b_markup; 
            $hotel->b2e_markup          = $request->b2e_markup; 
            $hotel->service_fee         = $request->service_fee; 
            $hotel->tax_type            = $request->tax_type; 
            $hotel->tax_value           = $request->tax_value; 
            $hotel->facilities          = $request->facilities; 
            $hotel->hotel_email         = $request->hotel_email; 
            $hotel->hotel_website       = $request->hotel_website; 
            $hotel->property_phone      = $request->property_phone; 
            $hotel->property_address    = $request->property_address;
            $hotel->room_gallery        = $request->room_gallery;
            $hotel->property_img        = $request->property_img; 
            $user_id                    = $req->customer_id;
            $hotel->owner_id            = $user_id;
            
            $hotel->image_Url_Other_Dashboard   = $request->image_Url_Other_Dashboard;
            $hotel->from_owner_id               = $request->owner_id;
            $hotel->from_hotel_id               = $request->hotel_id;
            
            $result                     = $hotel->save();
            $hotelId                    = $hotel->id;
            
            // Save Meta Info
            $metaInfo                   = new MetaInfo;
            $metaInfo->meta_title       = $request->meta_title ?? NULL; 
            $metaInfo->keywords         = $request->keywords ?? NULL; 
            $metaInfo->meta_desc        = $request->meta_desc ?? NULL; 
            $metaInfo->hotel_id         = $hotelId;
            $meta_info_Result           = $metaInfo->save();
            
            // Save Poilices
            $policies                   = new Policies;
            $policies->check_in_form    = $request->check_in_form ?? NULL;
            $policies->check_out_to     = $request->check_out_to ?? NULL;
            $policies->payment_option   = $request->payment_option ?? NULL;
            $policies->policy_and_terms = $request->policy_and_terms ?? NULL;
            $policies->hotel_id         = $hotelId;
            $policies_Result            = $policies->save();
            
            // Rooms
            // $check_Rooms = DB::table('rooms')->where('rooms.owner_id',$hotel_Details->owner_id)->where('rooms.hotel_id',$hotel_Details->hotel_id)
            //                 ->join('room_galleries','rooms.id','room_galleries.room_id')->get();
            // if(count($check_Rooms) > 0){
            //     // dd('IF');
            //     function dateDiffInDays($date1, $date2){
            //         $diff = strtotime($date2) - strtotime($date1);
            //         return abs(round($diff / 86400));
            //     }
                
            //     function getBetweenDates($startDate, $endDate){
            //         $rangArray  = [];
            //         $startDate  = strtotime($startDate);
            //         $endDate    = strtotime($endDate);
            //         $startDate  += (86400);
            //         for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate += (86400)) {
            //             $date           = date('Y-m-d', $currentDate);
            //             $rangArray[]    = $date;
            //         }
            //         return $rangArray;
            //     }
                
            //     foreach($check_Rooms as $val_CR){
                    
            //         // dd($val_CR);
                    
            //         $Rooms                                  = new Rooms;
                    
            //         if(isset($val_CR->SU_id) && $val_CR->SU_id != null && $val_CR->SU_id != ''){
            //             $Rooms->SU_id                       =  $val_CR->SU_id;
            //         }
                    
            //         $Rooms->from_owner_id                   = $request->owner_id;
            //         $Rooms->from_room_id                    = $val_CR->room_id;
                    
            //         $Rooms->hotel_id                        = $hotelId;
            //         $Rooms->rooms_on_rq                     = $val_CR->rooms_on_rq;
            //         $Rooms->room_type_id                    = $val_CR->room_type_id; 
            //         $Rooms->room_type_name                  = $val_CR->room_type_name; 
            //         $Rooms->room_type_cat                   = $val_CR->room_type_cat; 
            //         $Rooms->room_view                       = $val_CR->room_view; 
            //         $Rooms->price_type                      = $val_CR->price_type; 
            //         $Rooms->adult_price                     = $val_CR->adult_price;
            //         $Rooms->child_price                     = $val_CR->child_price; 
            //         $Rooms->quantity                        = $val_CR->quantity;  
            //         $Rooms->min_stay                        = $val_CR->min_stay; 
            //         $Rooms->max_child                       = $val_CR->max_child; 
            //         $Rooms->max_adults                      = $val_CR->max_adults; 
            //         $Rooms->extra_beds                      = $val_CR->extra_beds; 
            //         $Rooms->extra_beds_charges              = $val_CR->extra_beds_charges; 
            //         $Rooms->availible_from                  = $val_CR->availible_from; 
            //         $Rooms->availible_to                    = $val_CR->availible_to; 
            //         $Rooms->room_option_date                = $val_CR->room_option_date; 
            //         $Rooms->price_week_type                 = $val_CR->price_week_type; 
            //         $Rooms->price_all_days                  = $val_CR->price_all_days;
            //         $Rooms->room_supplier_name              = $val_CR->room_supplier_name;
            //         $Rooms->room_meal_type                  = $val_CR->room_meal_type;
            //         $Rooms->weekdays                        = $val_CR->weekdays;
            //         $Rooms->weekdays_price                  = $val_CR->weekdays_price;
            //         $Rooms->weekends                        = $val_CR->weekends;
            //         $Rooms->weekends_price                  = $val_CR->weekends_price; 
            //         $Rooms->room_description                = $val_CR->room_description;
            //         $Rooms->amenitites                      = $val_CR->amenitites;
            //         $Rooms->status                          = $val_CR->status;
            //         $Rooms->room_img                        = $val_CR->room_img; 
            //         $Rooms->more_room_type_details          = ''; 
            //         // $user_id                                = $request->owner_id;
            //         $Rooms->owner_id                        = $user_id;
                    
            //         $Rooms->cancellation_details            = $val_CR->cancellation_details;
            //         $Rooms->additional_meal_type            = $val_CR->additional_meal_type;
            //         $Rooms->additional_meal_type_charges    = $val_CR->additional_meal_type_charges;
            //         $Rooms->display_on_web                  = $val_CR->display_on_web;
            //         $Rooms->markup_type                     = $val_CR->markup_type;
            //         $Rooms->markup_value                    = $val_CR->markup_value;
            //         $Rooms->price_all_days_wi_markup        = $val_CR->price_all_days_wi_markup;
            //         $Rooms->weekdays_price_wi_markup        = $val_CR->weekdays_price_wi_markup;
            //         $Rooms->weekends_price_wi_markup        = $val_CR->weekends_price_wi_markup;
            //         $result                                 = $Rooms->save();
            //         $Roomsid                                = $Rooms->id;
                    
            //         $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$Rooms->room_supplier_name)->select('id','balance','payable')->first();
                    
            //         if(isset($supplier_data)){
            //             $week_days_total        = 0;
            //             $week_end_days_totals   = 0;
            //             $total_price            = 0;
            //             if($Rooms->price_week_type == 'for_all_days'){
            //                 $avaiable_days      = dateDiffInDays($Rooms->availible_from, $Rooms->availible_to);
            //                 $total_price        = $Rooms->price_all_days * $avaiable_days;
            //             }else{
            //                 $avaiable_days      = dateDiffInDays($Rooms->availible_from, $Rooms->availible_to);
            //                 $all_days           = getBetweenDates($Rooms->availible_from, $Rooms->availible_to);
            //                 $week_days          = json_decode($Rooms->weekdays);
            //                 $week_end_days      = json_decode($Rooms->weekends);
                            
            //                 foreach($all_days as $day_res){
            //                     $day                    = date('l', strtotime($day_res));
            //                     $day                    = trim($day);
            //                     $week_day_found         = false;
            //                     $week_end_day_found     = false;
                                
            //                     foreach($week_days as $week_day_res){
            //                         if($week_day_res == $day){
            //                             $week_day_found = true;
            //                         }
            //                     }
                                
            //                     if($week_day_found){
            //                         $week_days_total        += $Rooms->weekdays_price;
            //                     }else{
            //                         $week_end_days_totals   += $Rooms->weekends_price;
            //                     }
            //                 }
                            
            //                 $total_price        = $week_days_total + $week_end_days_totals;
            //             }
                        
            //             $all_days_price         = $total_price * $Rooms->quantity;
            //             $supplier_balance       = $supplier_data->balance + $all_days_price;
                        
            //             DB::table('hotel_supplier_ledger')->insert([
            //                 'supplier_id'       => $supplier_data->id,
            //                 'payment'           => $all_days_price,
            //                 'balance'           => $supplier_balance,
            //                 'payable_balance'   => $supplier_data->payable,
            //                 'room_id'           => $Roomsid,
            //                 'customer_id'       => $user_id,
            //                 'date'              => date('Y-m-d'),
            //                 'available_from'    => $Rooms->availible_from,
            //                 'available_to'      => $Rooms->availible_to,
            //                 'room_quantity'     => $Rooms->quantity,
            //             ]);
            //             DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
            //         }
                    
            //         if(isset($val_CR->more_room_type_details) && !empty($val_CR->more_room_type_details)){
            //             $more_rooms = json_decode($val_CR->more_room_type_details);
                        
            //             foreach($more_rooms as $room_more_res){
            //                 $meal_policy            = $room_more_res->more_meal_policy;
            //                 $meal_policy            = json_encode($meal_policy);
            //                 $concellation_policy    = $room_more_res->more_concellation_policy;
            //                 $guests_pay_days        = $room_more_res->more_guests_pay_days;
            //                 $guests_pay             = $room_more_res->more_guests_pay;
            //                 $prepaymentpolicy       = $room_more_res->more_prepaymentpolicy;
                            
                            
            //                 $cancellation_details       = (object)[
            //                     'meal_policy'           => $meal_policy,
            //                     'concellation_policy'   => $concellation_policy,
            //                     'guests_pay_days'       => $guests_pay_days,
            //                     'guests_pay'            => $guests_pay,
            //                     'prepaymentpolicy'      => $prepaymentpolicy,
            //                 ];
            //                 $cancellation_details               = json_encode($cancellation_details);
            //                 $room_insert_id                     = DB::table('rooms')->insertGetId([
            //                     'room_gen_id'                   => $room_more_res->room_gen_id,
            //                     'hotel_id'                      => $hotelId,
            //                     'rooms_on_rq'                   => $room_more_res->more_rooms_on_rq,
            //                     'room_type_id'                  => $room_more_res->more_room_type,
            //                     'room_type_name'                => $room_more_res->more_room_type_name,
            //                     'room_type_cat'                 => $room_more_res->more_room_type_id,
            //                     'room_view'                     => $room_more_res->more_room_view,
            //                     'price_type'                    => NULL,
            //                     'adult_price'                   => NULL,
            //                     'child_price'                   => NULL, 
            //                     'quantity'                      => $room_more_res->more_quantity,
            //                     'booked'                        => $room_more_res->more_quantity_booked,
            //                     'min_stay'                      => $room_more_res->more_min_stay, 
            //                     'max_child'                     => $room_more_res->more_max_childrens, 
            //                     'max_adults'                    => $room_more_res->more_max_adults,
            //                     'extra_beds'                    => $room_more_res->more_extra_beds, 
            //                     'extra_beds_charges'            => $room_more_res->more_extra_beds_charges, 
            //                     'availible_from'                => $room_more_res->more_room_av_from,
            //                     'availible_to'                  => $room_more_res->more_room_av_to,
            //                     'room_option_date'              => $room_more_res->more_room_option_date, 
            //                     'price_week_type'               => $room_more_res->more_week_price_type,
            //                     'price_all_days'                => $room_more_res->more_price_all_days,
            //                     'room_supplier_name'            => $room_more_res->more_room_supplier_name,
            //                     'room_meal_type'                => $room_more_res->more_room_meal_type,
            //                     'weekdays'                      => $room_more_res->more_weekdays,
            //                     'weekdays_price'                => $room_more_res->more_week_days_price,
            //                     'weekends'                      => $room_more_res->more_weekend,
            //                     'weekends_price'                => $room_more_res->more_week_end_price,
            //                     'room_description'              => $val_CR->room_description,
            //                     'amenitites'                    => $val_CR->amenitites,
            //                     'status'                        => $val_CR->status,
            //                     'more_room_type_details'        => NULL,
            //                     'owner_id'                      => $user_id,
            //                     'cancellation_details'          => $cancellation_details,
            //                     'additional_meal_type'          => $room_more_res->more_additional_meal_type,
            //                     'additional_meal_type_charges'  => $room_more_res->more_additional_meal_type_charges,
            //                     'display_on_web'                => $room_more_res->display_on_web,
            //                     'markup_type'                   => $room_more_res->markup_type,
            //                     'markup_value'                  => $room_more_res->markup_value,
            //                     'price_all_days_wi_markup'      => $room_more_res->price_all_days_wi_markup,
            //                     'weekdays_price_wi_markup'      => $room_more_res->weekdays_price_wi_markup,
            //                     'weekends_price_wi_markup'      => $room_more_res->weekends_price_wi_markup,
            //                 ]);
            //                 $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_more_res->more_room_supplier_name)->select('id','balance','payable')->first();
                            
            //                 if(isset($supplier_data)){
            //                     // echo "Enter hre ";
                                
            //                     $week_days_total        = 0;
            //                     $week_end_days_totals   = 0;
            //                     $total_price        = 0;
            //                     if($room_more_res->more_week_price_type == 'for_all_days'){
            //                         $avaiable_days  = dateDiffInDays($room_more_res->more_room_av_from, $room_more_res->more_room_av_to);
            //                         $total_price    = $room_more_res->more_price_all_days * $avaiable_days;
            //                     }else{
            //                         $avaiable_days  = dateDiffInDays($room_more_res->more_room_av_from, $room_more_res->more_room_av_to);
            //                         $all_days       = getBetweenDates($room_more_res->more_room_av_from, $room_more_res->more_room_av_to);
            //                         $week_days      = json_decode($room_more_res->more_weekdays);
            //                         $week_end_days  = json_decode($room_more_res->more_weekend);
                                    
            //                         foreach($all_days as $day_res){
            //                             $day                = date('l', strtotime($day_res));
            //                             $day                = trim($day);
            //                             $week_day_found     = false;
            //                             $week_end_day_found = false;
                                        
            //                             foreach($week_days as $week_day_res){
            //                                 if($week_day_res == $day){
            //                                     $week_day_found = true;
            //                                 }
            //                             }
                                        
            //                             if($week_day_found){
            //                                 $week_days_total        += $room_more_res->more_week_days_price;
            //                             }else{
            //                                 $week_end_days_totals   += $room_more_res->more_week_end_price;
            //                             }
            //                         }
            //                         $total_price    = $week_days_total + $week_end_days_totals;
            //                     }
                                
            //                     $all_days_price     = $total_price * $room_more_res->more_quantity;
            //                     $supplier_balance   = $supplier_data->balance + $all_days_price;
                                
                                
            //                     DB::table('hotel_supplier_ledger')->insert([
            //                         'supplier_id'       => $supplier_data->id,
            //                         'payment'           => $all_days_price,
            //                         'balance'           => $supplier_balance,
            //                         'payable_balance'   => $supplier_data->payable,
            //                         'room_id'           => $room_insert_id,
            //                         'customer_id'       => $user_id,
            //                         'date'              => date('Y-m-d'),
            //                         'available_from'    => $room_more_res->more_room_av_from,
            //                         'available_to'      => $room_more_res->more_room_av_to,
            //                         'room_quantity'     => $room_more_res->more_quantity,
            //                     ]);
                                
            //                     DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
            //                 } 
            //             }
            //         }
                    
            //         $roomGallery                = new RoomGallery();
            //         $roomGallery->img_name      = $val_CR->img_name;
            //         $roomGallery->room_id       = $Roomsid;
            //         $roomGallery                = $roomGallery->save();
            //     }
            // }
            // Rooms
            
            DB::commit();
            
            return response()->json([
                'message' => 'Success',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']);
        }
        
        return response()->json([
            'user_hotels' => $user_hotel_arr,
        ]);
    }
    
    public function viewAgentsDebitCreditList(Request $request){
        $season_Details = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->get();
        return response()->json(['message' => 'Success','season_Details' => $season_Details]);
    }
    
    public function getAgentsDebitCreditList(Request $request){
        $draw               = json_decode($request->draw);
        $start              = json_decode($request->start);
        $rowperpage         = json_decode($request->length);
        $columnIndex_arr    = json_decode($request->order);
        $columnName_arr     = json_decode($request->columns);
        $order_arr          = json_decode($request->order);
        $search_arr         = json_decode($request->search);
        $columnIndex        = $columnIndex_arr[0]->column ?? 0;
        $columnName         = $columnName_arr[$columnIndex]->data ?? 'created_at';
        $columnSortOrder    = $order_arr[0]->dir ?? 'desc';
        $searchValue        = $search_arr->value;
        
        $query = Agent::where('Agents_detail.customer_id', $request->customer_id)->orderBy('Agents_detail.created_at', 'asc');
        
        $totalRecords = $query->count();
        
        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('Agents_detail.id', 'like', "%$searchValue%")
                  ->orWhere('Agents_detail.agent_Name', 'like', "%$searchValue%")
                  ->orWhere('Agents_detail.company_name', 'like', "%$searchValue%");
            });
        }
        
        $totalRecordswithFilter = $query->count();
        
        $records = $query->orderBy($columnName, $columnSortOrder)->skip($start)->take($rowperpage)->get();
        
        $data_arr = [];
        $i = 1;
        
        if($request->season_Id > 0){
            $season_Details = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->where('id', $request->season_Id)->first();
        }
        
        foreach ($records as $record) {
            $agent_id = $record->id;
            
            /** ---------------------------
             *  CALCULATE DEBIT (same as statement logic)
             * --------------------------- */
            $total_debit = 0;
            
            // From invoices
            $agent_invoices_all = DB::table('add_manage_invoices')
                ->where('agent_Id', $agent_id)
                ->get();
                
            foreach ($agent_invoices_all as $inv) {
                $accomodation_details = json_decode($inv->accomodation_details);
                $services = json_decode($inv->services);
                
                $check_in = null;
                if (isset($accomodation_details[0]->acc_check_in)) {
                    $check_in = $accomodation_details[0]->acc_check_in;
                }
                if (isset($services[0]) && in_array($services[0], ['visa_tab', 'transportation_tab'])) {
                    $check_in = $inv->created_at;
                }
                
                // Apply season filter
                if ($request->season_Id == 'all_Seasons') {
                    if (isset($inv->total_sale_price_AC) && $inv->total_sale_price_AC > 0) {
                        $total_debit += $inv->total_sale_price_AC;
                    } elseif (isset($inv->total_sale_price_all_Services) && $inv->total_sale_price_all_Services > 0) {
                        $total_debit += $inv->total_sale_price_all_Services;
                    }
                }else{
                    $checkSeason = $this->checkSeason($season_Details,$check_in);
                    if ($checkSeason) {
                        if (isset($inv->total_sale_price_AC) && $inv->total_sale_price_AC > 0) {
                            $total_debit += $inv->total_sale_price_AC;
                        } elseif (isset($inv->total_sale_price_all_Services) && $inv->total_sale_price_all_Services > 0) {
                            $total_debit += $inv->total_sale_price_all_Services;
                        }
                    }
                }
            }
            
            // dd($total_debit);
            
            // From package bookings
            $packages_booking_all = DB::table('cart_details')
                ->join('tours', 'tours.id', '=', 'cart_details.tour_id')
                ->where('cart_details.agent_name', $agent_id)
                ->get();
                
            foreach ($packages_booking_all as $pkg) {
                $accomodation_details = json_decode($pkg->accomodation_details);
                $check_in = isset($accomodation_details[0]->acc_check_in) ? $accomodation_details[0]->acc_check_in : null;
                
                if ($request->season_Id == 'all_Seasons') {
                    if (isset($pkg->price) && $pkg->price > 0) {
                        $total_debit += $pkg->price;
                    }
                }else{
                    $checkSeason = $this->checkSeason($season_Details,$check_in);
                    if($checkSeason){
                        if (isset($pkg->price) && $pkg->price > 0) {
                            $total_debit += $pkg->price;
                        }
                    }
                }
            }
            
            // From make_payments (added as debit in statement)
            $make_payments_data = DB::table('make_payments_details')
                ->where('Criteria', 'Agent')
                ->where('Content_Ids', $agent_id)
                ->get();
                
            foreach ($make_payments_data as $pay) {
                $check_in = $pay->payment_date;
                
                $total_payment_received = 0;
                if (isset($pay->converion_data)) {
                    $conversion_data = json_decode($pay->converion_data);
                    if (isset($conversion_data->purchase_currency)) {
                        if ($conversion_data->purchase_currency) {
                            $total_payment_received = $pay->purchase_amount;
                        } else {
                            $total_payment_received = $pay->Amount;
                        }
                    } else {
                        $total_payment_received = $pay->Amount;
                    }
                } else {
                    $total_payment_received = $pay->Amount;
                }
                
                if ($request->season_Id == 'all_Seasons') {
                    $total_debit += $total_payment_received;
                }else{
                    $checkSeason = $this->checkSeason($season_Details,$check_in);
                    if($checkSeason){
                        $total_debit += $total_payment_received;
                    }
                }
            }
            
            $debit = number_format((float)$total_debit, 2, '.', '');
            
            /** ---------------------------
             *  CALCULATE CREDIT (same as statement logic)
             * --------------------------- */
            $total_credit = 0;
            
            $payments_data = DB::table('recevied_payments_details')
                ->where('Criteria', 'Agent')
                ->where('Content_Ids', $agent_id)
                ->get();
                
            foreach ($payments_data as $pay) {
                $check_in = $pay->payment_date;
                
                $total_payment_received = 0;
                if (isset($pay->converion_data)) {
                    $conversion_data = json_decode($pay->converion_data);
                    if (isset($conversion_data->purchase_currency)) {
                        if ($conversion_data->purchase_currency) {
                            $total_payment_received = $pay->purchase_amount;
                        } else {
                            $total_payment_received = $pay->Amount;
                        }
                    } else {
                        $total_payment_received = $pay->Amount;
                    }
                } else {
                    $total_payment_received = $pay->Amount;
                }
                
                if ($request->season_Id == 'all_Seasons') {
                    $total_credit += $total_payment_received;
                }else{
                    $checkSeason = $this->checkSeason($season_Details,$check_in);
                    if($checkSeason){
                        $total_credit += $total_payment_received;
                    }
                }
            }
            
            $credit = number_format((float)$total_credit, 2, '.', '');
            
            /** ---------------------------
             *  REMAINING
             * --------------------------- */
            $remaining = 0;
            if ($debit > 0 || $credit > 0) {
                $remaining = $debit - $credit;
                $remaining = number_format((float)$remaining, 2, '.', '');
            }
            
            $data_arr[] = [
                "id"           => $i,
                "agent_Name"   => $record->agent_Name,
                "company_name" => $record->company_name,
                "debit"        => $debit,
                "credit"       => $credit,
                "remaining"    => $remaining,
            ];
            $i++;
        }
    
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        ];
    
        return response()->json($response);
    }
    
    public function checkSeason($season_Details, $check_in){
        if (!isset($check_in)) {
            return false;
        }
        
        $start_Date = Carbon::parse($season_Details->start_Date);
        $end_Date   = Carbon::parse($season_Details->end_Date);
        $checkIn    = Carbon::parse($check_in);
        if ($checkIn->between($start_Date, $end_Date) || ($checkIn->lte($start_Date) && $checkIn->gte($end_Date))) {
            return true;
        }else{
            return false;
        }
    }
    // Other Dashboard
    
    public function view_Rates_Wise_Hotels(Request $request){
        $id                         = $request->customer_id;
        $remaining_Hotel_Wise_Data  = [];
        $makkah_Hotel_Wise_Data     = [];
        $madina_Hotel_Wise_Data     = [];
        $user_hotels                = [];
        $user_Rooms                 = [];
        
        $hotel_Details              = DB::table('hotels')->where('owner_id',$id)->orderBy('hotels.created_at', 'desc')->get();
        // ->take(10)
        // dd($hotel_Details);
        
        foreach($hotel_Details as $val_UH){
            $hotel_Rooms = DB::table('rooms')->where('owner_id',$val_UH->owner_id)->where('hotel_id',$val_UH->id)->get();
            if(!empty($hotel_Rooms) && count($hotel_Rooms) > 0){
                array_push($user_hotels,$val_UH);
                $room_Wise_Data = [];
                foreach($hotel_Rooms as $val_HR){
                    array_push($user_Rooms,$val_HR);
                    array_push($room_Wise_Data,$val_HR);
                }
                $single_Hotel_Wise_Data = [
                    'hotel' => $val_UH,
                    'rooms' => $room_Wise_Data
                ];
                
                if($val_UH->property_city != 'Makkah' && $val_UH->property_city != 'Medina' && $val_UH->property_city != 'medina' && $val_UH->property_city != 'Al-Madinah al-Munawwarah'){
                    array_push($remaining_Hotel_Wise_Data,$single_Hotel_Wise_Data);
                }
                
                if($val_UH->property_city == 'Makkah' || $val_UH->property_city == 'makkah'){
                    array_push($makkah_Hotel_Wise_Data,$single_Hotel_Wise_Data);
                }
                
                if($val_UH->property_city == 'Madina' || $val_UH->property_city == 'madina' || $val_UH->property_city == 'Medina' || $val_UH->property_city == 'medina' || $val_UH->property_city == 'Al-Madinah al-Munawwarah'){
                    array_push($madina_Hotel_Wise_Data,$single_Hotel_Wise_Data);
                }
            }
        }
        
        return response()->json([
            'user_hotels'               => $user_hotels,
            'user_Rooms'                => $user_Rooms,
            'remaining_Hotel_Wise_Data' => $remaining_Hotel_Wise_Data,
            'makkah_Hotel_Wise_Data'    => $makkah_Hotel_Wise_Data,
            'madina_Hotel_Wise_Data'    => $madina_Hotel_Wise_Data,
        ]);
    }
    
    public function view_Rates_Wise_Hotels_Makkah(Request $request){
        $today_Date                 = date('Y-m-d');
        $id                         = $request->customer_id;
        $makkah_Hotel_Wise_Data     = [];
        $hotel_Details              = DB::table('hotels')->where('owner_id',$id)->orderBy('hotels.created_at', 'desc')->get();
        
        foreach($hotel_Details as $val_UH){
            $hotel_Rooms = DB::table('rooms')->where('owner_id',$val_UH->owner_id)->where('availible_to', '>=', $today_Date)->where('hotel_id',$val_UH->id)->get();
            if(!empty($hotel_Rooms) && count($hotel_Rooms) > 0){
                $room_Wise_Data = [];
                foreach($hotel_Rooms as $val_HR){
                    array_push($room_Wise_Data,$val_HR);
                }
                $single_Hotel_Wise_Data = [
                    'hotel' => $val_UH,
                    'rooms' => $room_Wise_Data
                ];
                
                if($val_UH->property_city == 'Makkah' || $val_UH->property_city == 'makkah'){
                    array_push($makkah_Hotel_Wise_Data,$single_Hotel_Wise_Data);
                }
            }
        }
        
        return response()->json([
            'makkah_Hotel_Wise_Data'    => $makkah_Hotel_Wise_Data,
        ]);
    }
    
    public function view_Rates_Wise_Hotels_Madina(Request $request){
        $today_Date                 = date('Y-m-d');
        $id                         = $request->customer_id;
        $madina_Hotel_Wise_Data     = [];
        $hotel_Details              = DB::table('hotels')->where('owner_id',$id)->orderBy('hotels.created_at', 'desc')->get();
        
        foreach($hotel_Details as $val_UH){
            $hotel_Rooms = DB::table('rooms')->where('owner_id',$val_UH->owner_id)->where('availible_to', '>=', $today_Date)->where('hotel_id',$val_UH->id)->get();
            if(!empty($hotel_Rooms) && count($hotel_Rooms) > 0){
                $room_Wise_Data = [];
                foreach($hotel_Rooms as $val_HR){
                    array_push($room_Wise_Data,$val_HR);
                }
                $single_Hotel_Wise_Data = [
                    'hotel' => $val_UH,
                    'rooms' => $room_Wise_Data
                ];
                
                if($val_UH->property_city == 'Madina' || $val_UH->property_city == 'madina' || $val_UH->property_city == 'Medina' || $val_UH->property_city == 'medina' || $val_UH->property_city == 'Al-Madinah al-Munawwarah'){
                    array_push($madina_Hotel_Wise_Data,$single_Hotel_Wise_Data);
                }
            }
        }
        
        return response()->json([
            'madina_Hotel_Wise_Data'    => $madina_Hotel_Wise_Data,
        ]);
    }
    
    public function view_Rates_Wise_Hotels_Madina_Test(Request $request){
        $id                         = $request->customer_id;
        $madina_Hotel_Wise_Data     = [];
        
        $c1 = 'Madina';
        $c2 = 'madina';
        $c3 = 'Medina';
        $c4 = 'medina';
        $c5 = 'Al-Madinah al-Munawwarah';
        
        // dd($c1);
        
        $hotel_Details              = DB::table('hotels')
                                        ->where(function ($query) use ($id, $c1, $c2, $c3, $c4, $c5) {
                                            $query->where('owner_id', $id)
                                            ->where(function ($q) use ($c1, $c2, $c3, $c4, $c5) {
                                                $q->orWhere('property_city', $c1)
                                                ->orWhere('property_city', $c2)
                                                ->orWhere('property_city', $c3)
                                                ->orWhere('property_city', $c4)
                                                ->orWhere('property_city', $c5);
                                            });
                                        })->orderBy('hotels.created_at', 'desc')->get();
        // dd($hotel_Details);
        
        foreach($hotel_Details as $val_UH){
            $hotel_Rooms = DB::table('rooms')->where('owner_id',$val_UH->owner_id)->where('hotel_id',$val_UH->id)->get();
            if(!empty($hotel_Rooms) && count($hotel_Rooms) > 0){
                
                dd($hotel_Rooms);
                
                $room_Wise_Data = [];
                foreach($hotel_Rooms as $val_HR){
                    array_push($room_Wise_Data,$val_HR);
                }
                
                $single_Hotel_Wise_Data = [
                    'hotel' => $val_UH,
                    'rooms' => $room_Wise_Data
                ];
                
                if($val_UH->property_city == 'Madina' || $val_UH->property_city == 'madina' || $val_UH->property_city == 'Medina' || $val_UH->property_city == 'medina' || $val_UH->property_city == 'Al-Madinah al-Munawwarah'){
                    array_push($madina_Hotel_Wise_Data,$single_Hotel_Wise_Data);
                }
            }
        }
        
        
        
        foreach($hotel_Details as $val_UH){
            $hotel_Rooms = DB::table('rooms')->where('owner_id',$val_UH->owner_id)->where('hotel_id',$val_UH->id)->get();
            if(!empty($hotel_Rooms) && count($hotel_Rooms) > 0){
                $room_Wise_Data = [];
                foreach($hotel_Rooms as $val_HR){
                    array_push($room_Wise_Data,$val_HR);
                }
                
                $single_Hotel_Wise_Data = [
                    'hotel' => $val_UH,
                    'rooms' => $room_Wise_Data
                ];
                
                if($val_UH->property_city == 'Madina' || $val_UH->property_city == 'madina' || $val_UH->property_city == 'Medina' || $val_UH->property_city == 'medina' || $val_UH->property_city == 'Al-Madinah al-Munawwarah'){
                    array_push($madina_Hotel_Wise_Data,$single_Hotel_Wise_Data);
                }
            }
        }
        
        return response()->json([
            'madina_Hotel_Wise_Data'    => $madina_Hotel_Wise_Data,
        ]);
    }

    public function showAddHotelFrom(){
        $all_countries = country::all();
        
       
        return response()->json([
            'all_countries' => $all_countries,
        ]);
        // return view('template/frontend/userdashboard/pages/hotel_manager.add_hotel',compact('all_countries'));
   }

    public function addHotel(Request $request){
        // Data
        $hotel                      = new Hotels;
        $hotel->property_name       = $request->property_name; 
        $hotel->currency_symbol     = $request->currency_symbol; 
        
        if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
            $hotel->SU_id           = $request->SU_id;
        }
        
        $hotel->currency_value      = $request->currency_value; 
        
        $hotel->property_desc       = $request->property_desc; 
        $hotel->property_google_map = $request->property_google_map; 
        $hotel->latitude            = $request->latitude; 
        $hotel->longitude           = $request->longitude;
        $hotel->property_country    = $request->property_country; 
        $hotel->property_city       = $request->property_city;  
        $hotel->price_type          = $request->price_type; 
        $hotel->star_type           = $request->star_type; 
        $hotel->status              = $request->status; 
        $hotel->property_type       = $request->property_type; 
        $hotel->b2c_markup          = $request->b2c_markup; 
        $hotel->b2b_markup          = $request->b2b_markup; 
        $hotel->b2e_markup          = $request->b2e_markup; 
        $hotel->service_fee         = $request->service_fee; 
        $hotel->tax_type            = $request->tax_type; 
        $hotel->tax_value           = $request->tax_value; 
        // Facilities
        $hotel->facilities          = $request->facilities; 
        // Contact
        $hotel->hotel_email         = $request->hotel_email; 
        $hotel->hotel_website       = $request->hotel_website; 
        $hotel->property_phone      = $request->property_phone; 
        $hotel->property_address    = $request->property_address;
        $hotel->room_gallery        = $request->room_gallery;
        
        $hotel->property_img        = $request->property_img; 
        $user_id                    = $request->customer_id;
        
        $hotel->owner_id            = $user_id;
        $result                     = $hotel->save();
        $hotelId                    = $hotel->id;
        // Save Meta Info
        $metaInfo = new MetaInfo;
        $metaInfo->meta_title       = $request->meta_title; 
        $metaInfo->keywords         = $request->keywords; 
        $metaInfo->meta_desc        = $request->meta_desc; 
        $metaInfo->hotel_id         = $hotelId;
        $meta_info_Result           = $metaInfo->save();
        // Save Poilices
        $policies = new Policies;
        $policies->check_in_form    = $request->hotel_check_in;
        $policies->check_out_to     = $request->hotel_check_out;
        $policies->payment_option   = $request->payment_option;
        $policies->policy_and_terms = $request->policy_and_terms;
        $policies->hotel_id         = $hotelId;
        // dd($hotel);
        $policies_Result            = $policies->save();
        // End Data
        
        return response()->json([
            'message'               => 'Success',
        ]);
    }
    
    public function addHotelTest(Request $request){
        // return $request;
        
        $currentDateTime    = Carbon::now()->format('d-m-Y-H:i:s');
        if($request->file('property_img')){
            $file           = $request->file('property_img');
            $file_Path      = $currentDateTime . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/hotel_images'), $file_Path);
        }else{
            $file_Path      = $request->property_img;
        }
        
        $room_gallery       = [];
        foreach ($request->file('room_gallery') as $file) {
            $file_Path_RG   = $currentDateTime . '_' . $file->getClientOriginalName();
            array_push($room_gallery,$file_Path_RG);
            $file->move(public_path('uploads/hotel_images/room_gallery'), $file_Path_RG);
        }
        
        if(empty($room_gallery)){
            $room_gallery   = NULL;
        }else{
            $room_gallery   = json_encode($room_gallery);
        }
        
        // Data
        $hotel                      = new Hotels;
        $hotel->property_name       = $request->property_name; 
        $hotel->currency_symbol     = $request->currency_symbol; 
        
        if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
            $hotel->SU_id           = $request->SU_id;
        }
        
        $hotel->currency_value      = $request->currency_value; 
        
        $hotel->property_desc       = $request->property_desc; 
        $hotel->property_google_map = $request->property_google_map; 
        $hotel->latitude            = $request->latitude; 
        $hotel->longitude           = $request->longitude;
        $hotel->property_country    = $request->property_country; 
        $hotel->property_city       = $request->property_city;  
        $hotel->price_type          = $request->price_type; 
        $hotel->star_type           = $request->star_type; 
        $hotel->status              = $request->status; 
        $hotel->property_type       = $request->property_type; 
        $hotel->b2c_markup          = $request->b2c_markup; 
        $hotel->b2b_markup          = $request->b2b_markup; 
        $hotel->b2e_markup          = $request->b2e_markup; 
        $hotel->service_fee         = $request->service_fee; 
        $hotel->tax_type            = $request->tax_type; 
        $hotel->tax_value           = $request->tax_value; 
        // Facilities
        $hotel->facilities          = $request->facilities; 
        // Contact
        $hotel->hotel_email         = $request->hotel_email; 
        $hotel->hotel_website       = $request->hotel_website; 
        $hotel->property_phone      = $request->property_phone; 
        $hotel->property_address    = $request->property_address;
        $hotel->room_gallery        = $room_gallery ?? $request->room_gallery;
        $hotel->room_gallery        = NULL;
        
        $hotel->property_img        = $file_Path ?? $request->property_img; 
        $user_id                    = $request->customer_id;
        
        $hotel->owner_id            = $user_id;
        $result                     = $hotel->save();
        $hotelId                    = $hotel->id;
        // Save Meta Info
        $metaInfo = new MetaInfo;
        $metaInfo->meta_title       = $request->meta_title; 
        $metaInfo->keywords         = $request->keywords; 
        $metaInfo->meta_desc        = $request->meta_desc; 
        $metaInfo->hotel_id         = $hotelId;
        $meta_info_Result           = $metaInfo->save();
        // Save Poilices
        $policies = new Policies;
        $policies->check_in_form    = $request->hotel_check_in;
        $policies->check_out_to     = $request->hotel_check_out;
        $policies->payment_option   = $request->payment_option;
        $policies->policy_and_terms = $request->policy_and_terms;
        $policies->hotel_id         = $hotelId;
        // dd($hotel);
        $policies_Result            = $policies->save();
        // End Data
        
        return response()->json([
            'message'               => 'Success',
        ]);
    }

    // Hotel
    public function hotel_Room(){
      $start_date = $_POST['start_date'];
      $end_date   = $_POST['end_date'];
      $cityID     = $_POST['cityID'];
       $rooms  = DB::table('rooms')->where('availible_from','<=',$start_date)->where('availible_to','>=',$end_date)
                ->join('rooms_types','rooms.room_type_id','=','rooms_types.id')
                ->join('hotels','rooms.hotel_id','=','hotels.id')->where('property_city','=',$cityID)
                ->get();
      echo $rooms ;
   }

    // Room
    public function roomID($id){
        $rooms = Rooms::where('hotel_id',$id)->join('rooms_types','rooms.room_type_id','=','rooms_types.id')->get();
        echo $rooms ;
   }
   
    public function get_hotel_booking(Request $request){
       $token=$request->token;
       $hotels_booking=DB::table('hotel_booking')->where('auth_token',$token)->where('provider','hotels')->limit(5)->orderBy('id', 'DESC')->get();
       $customer_subcriptions=DB::table('customer_subcriptions')->where('Auth_key',$token)->first();
       return response()->json([
            'hotels_booking' => $hotels_booking,
            'customer_subcriptions' => $customer_subcriptions,
           
        ]);
    }
   
    // hotel_Bookings
    public function hotel_Bookings(Request $request){
        $system_url = DB::table('customer_subcriptions')->where('id',$request->customer_id)->select('webiste_Address')->first();
        // $data       = DB::table('hotels_bookings')->where('customer_id',$request->customer_id)->orderBy('id', 'desc')->get();
        $data       = DB::table('hotels_bookings')
                        ->leftJoin('b2b_agents', 'hotels_bookings.b2b_agent_id', '=', 'b2b_agents.id') // Join hotels_bookings with b2b_agent using b2b_agent_id
                        ->where('hotels_bookings.customer_id', $request->customer_id)
                        ->orderBy('hotels_bookings.id', 'desc')
                        ->select('hotels_bookings.*', 'b2b_agents.company_name') // Select fields from both tables
                        ->get();
        // Season
        $today_Date             = date('Y-m-d');
        $season_Id              = '';
        if(isset($request->season_Id) && $request->season_Id == 'all_Seasons'){
            $season_Id          = 'all_Seasons';
        }elseif(isset($request->season_Id) && $request->season_Id > 0){
            $season_SD          = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->where('id', $request->season_Id)->first();
            $season_Id          = $season_SD->id ?? '';
        }else{
            $season_SD          = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
            $season_Id          = $season_SD->id ?? '';
        }
        
        if($request->customer_id == 4 || $request->customer_id == 54){
            if(isset($request->season_Id)){
                if($request->season_Id == 'all_Seasons'){
                    $season_Details         = null;
                }elseif($request->season_Id > 0){
                    $season_Details         = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->where('id', $request->season_Id)->first();
                }else{
                    $season_Details         = null;
                }
            }else{
                $season_Details             = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
            }
            
            if($season_Details != null){
                $start_Date                 = $season_Details->start_Date;
                $end_Date                   = $season_Details->end_Date;
                // dd($data);
                $data                       = DB::table('hotels_bookings')
                                                ->leftJoin('b2b_agents', 'hotels_bookings.b2b_agent_id', '=', 'b2b_agents.id')
                                                ->where('hotels_bookings.customer_id', $request->customer_id)
                                                ->whereBetween('hotels_bookings.created_at', [$start_Date, $end_Date])
                                                ->orderBy('hotels_bookings.id', 'desc')
                                                ->select('hotels_bookings.*', 'b2b_agents.company_name')
                                                ->get();
                
            }
        }
        $season_Details                     = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->get();
        // Season
        return response()->json([
            'data'              => $data,
            'system_url'        => $system_url->webiste_Address,
            'season_Details'    => $season_Details,
            'season_Id'         => $season_Id,
        ]);
    }
    
    public function hotel_Bookings_Selected(Request $request){
        // dd($request->id);
        $system_url         = DB::table('customer_subcriptions')->where('id',$request->customer_id)->select('webiste_Address')->first();
        $bookingIdsArray    = explode(',', $request->id);
        $data               = DB::table('hotels_bookings')
                                ->leftJoin('b2b_agents', 'hotels_bookings.b2b_agent_id', '=', 'b2b_agents.id')
                                ->whereIn('hotels_bookings.invoice_no', $bookingIdsArray)
                                ->where('hotels_bookings.customer_id', $request->customer_id)
                                ->orderBy('hotels_bookings.id', 'desc')
                                ->select('hotels_bookings.*', 'b2b_agents.company_name')
                                ->get();
                                // dd($data);
        // Season
        // $today_Date             = date('Y-m-d');
        $season_Id              = '';
        // if(isset($request->season_Id) && $request->season_Id == 'all_Seasons'){
        //     $season_Id          = 'all_Seasons';
        // }elseif(isset($request->season_Id) && $request->season_Id > 0){
        //     $season_SD          = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->where('id', $request->season_Id)->first();
        //     $season_Id          = $season_SD->id ?? '';
        // }else{
        //     $season_SD          = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
        //     $season_Id          = $season_SD->id ?? '';
        // }
        
        // if($request->customer_id == 4 || $request->customer_id == 54){
        //     if(isset($request->season_Id)){
        //         if($request->season_Id == 'all_Seasons'){
        //             $season_Details         = null;
        //         }elseif($request->season_Id > 0){
        //             $season_Details         = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->where('id', $request->season_Id)->first();
        //         }else{
        //             $season_Details         = null;
        //         }
        //     }else{
        //         $season_Details             = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
        //     }
            
        //     if($season_Details != null){
        //         $start_Date                 = $season_Details->start_Date;
        //         $end_Date                   = $season_Details->end_Date;
        //         // dd($data);
        //         $data                       = DB::table('hotels_bookings')
        //                                         ->leftJoin('b2b_agents', 'hotels_bookings.b2b_agent_id', '=', 'b2b_agents.id')
        //                                         ->where('hotels_bookings.customer_id', $request->customer_id)
        //                                         ->whereIn('hotels_bookings.invoice_no', $bookingIdsArray)
        //                                         ->whereBetween('hotels_bookings.created_at', [$start_Date, $end_Date])
        //                                         ->orderBy('hotels_bookings.id', 'desc')
        //                                         ->select('hotels_bookings.*', 'b2b_agents.company_name')
        //                                         ->get();
                
        //     }
        // }
        $season_Details                     = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->get();
        // Season
        return response()->json([
            'data'              => $data,
            'system_url'        => $system_url->webiste_Address,
            'season_Details'    => $season_Details,
            'season_Id'         => $season_Id,
        ]);
    }
    
    public function custome_Hotel_Bookings(Request $request){
        $system_url = DB::table('customer_subcriptions')->where('id',$request->customer_id)->select('webiste_Address')->first();
        // $data       = DB::table('hotels_bookings')->where('customer_id',$request->customer_id)->orderBy('id', 'desc')->get();
        
        $data   = DB::table('hotels_bookings')
                    ->leftJoin('b2b_agents', 'hotels_bookings.b2b_agent_id', '=', 'b2b_agents.id')
                    ->where('hotels_bookings.customer_id', $request->customer_id)
                    ->where('hotels_bookings.provider', 'Custome_hotel')
                    ->orderBy('hotels_bookings.id', 'desc')
                    ->select('hotels_bookings.*', 'b2b_agents.company_name')
                    ->get();
    
        // dd($data);  
        return response()->json([
            'data'          => $data,
            'system_url'    => $system_url->webiste_Address,
        ]);
    }
    
    public function confirm_Alif_Booking(Request $request){
        DB::beginTransaction();
        try {
            $hotels_bookings = DB::table('hotels_bookings')->where('customer_id', $request->customer_id)->where('invoice_no', $request->invoice_no)->first();
            // dd($hotels_bookings);
            if($hotels_bookings != null){
                DB::table('hotels_bookings')->where('customer_id',$request->customer_id)->where('invoice_no',$request->invoice_no)->update(['status'=>'Confirmed']);
                DB::commit();
                return response()->json(['message' => 'success']);
            }else{
                DB::commit();
                return response()->json(['message'=>'error']);
            }
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;die;
            return response()->json(['message'=>'error']);
        }
    }
    
    public function add_HCN_Hotel_Booking(Request $request){
        DB::beginTransaction();
        try {
            $hotels_bookings = DB::table('hotels_bookings')->where('customer_id',$request->customer_id)->where('invoice_no',$request->invoice_Id)->first();
            if($hotels_bookings != null){
                DB::table('hotels_bookings')->where('customer_id',$request->customer_id)->where('invoice_no',$request->invoice_Id)->update(['hcn_Number'=>$request->hcn_Number]);
                $hotels_bookings = DB::table('hotels_bookings')->where('customer_id',$request->customer_id)->where('invoice_no',$request->invoice_Id)->first();
                DB::commit();
                return response()->json(['message'=>'success','Invoice_data'=>$hotels_bookings,'Invoice_id'=>$request->invoice_Id]);
            }else{
                DB::commit();
                return response()->json(['message'=>'error']);
            }
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;die;
            return response()->json(['message'=>'error']);
        }
    }
    
    public function add_BRN_Hotel_Booking(Request $request){
        DB::beginTransaction();
        try {
            $hotels_bookings = DB::table('hotels_bookings')->where('customer_id',$request->customer_id)->where('invoice_no',$request->invoice_Id)->first();
            if($hotels_bookings != null){
                DB::table('hotels_bookings')->where('customer_id',$request->customer_id)->where('invoice_no',$request->invoice_Id)->update(['brn_Number'=>$request->brn_Number]);
                $hotels_bookings = DB::table('hotels_bookings')->where('customer_id',$request->customer_id)->where('invoice_no',$request->invoice_Id)->first();
                DB::commit();
                return response()->json(['message'=>'success','Invoice_data'=>$hotels_bookings,'Invoice_id'=>$request->invoice_Id]);
            }else{
                DB::commit();
                return response()->json(['message'=>'error']);
            }
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;die;
            return response()->json(['message'=>'error']);
        }
    }
    
    public function add_CR_Proceed(Request $request){
        DB::beginTransaction();
        try {
            $hotels_bookings = DB::table('hotels_bookings')->where('customer_id',$request->customer_id)->where('invoice_no',$request->invoice_Id)->first();
            if($hotels_bookings != null){
                DB::table('hotels_bookings')->where('customer_id',$request->customer_id)->where('invoice_no',$request->invoice_Id)->update([
                    'status'                => 'Cancelled',
                    'after_Confirm_Status'  => 'Cancelled',
                    'cancellation_Price_CR' => $request->cancellation_Price_CR,
                ]);
                $hotels_bookings = DB::table('hotels_bookings')->where('customer_id',$request->customer_id)->where('invoice_no',$request->invoice_Id)->first();
                DB::commit();
                return response()->json(['message'=>'success','Invoice_data'=>$hotels_bookings,'Invoice_id'=>$request->invoice_Id]);
            }else{
                DB::commit();
                return response()->json(['message'=>'error']);
            }
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;die;
            return response()->json(['message'=>'error']);
        }
    }
    
    public function reject_Cncellation_Request(Request $request){
        DB::beginTransaction();
        try {
            $hotels_bookings = DB::table('hotels_bookings')->where('customer_id',$request->customer_id)->where('invoice_no',$request->invoice_Id)->first();
            if($hotels_bookings != null){
                DB::table('hotels_bookings')->where('customer_id',$request->customer_id)->where('invoice_no',$request->invoice_Id)->update([
                    'after_Confirm_Status'  => 'Rejected',
                ]);
                $hotels_bookings = DB::table('hotels_bookings')->where('customer_id',$request->customer_id)->where('invoice_no',$request->invoice_Id)->first();
                DB::commit();
                return response()->json(['message'=>'success','Invoice_data'=>$hotels_bookings,'Invoice_id'=>$request->invoice_Id]);
            }else{
                DB::commit();
                return response()->json(['message'=>'error']);
            }
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;die;
            return response()->json(['message'=>'error']);
        }
    }
    
    public function confirm_Booking(Request $request){
        DB::beginTransaction();
        try {
            DB::table('hotels_bookings')->where('customer_id',$request->customer_id)->where('id',$request->id)->update(['status'=>'Confirmed']);
            $Invoice_data = DB::table('hotels_bookings')->where('customer_id',$request->customer_id)->where('id',$request->id)->first();
            DB::commit();
            return response()->json(['message'=>'success','Invoice_data'=>$Invoice_data]);
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;die;
            return response()->json(['message'=>'error']);
        }
    }
    
    public function hotel_Bookings_Client(Request $request){
        $system_url     = DB::table('customer_subcriptions')->where('id',$request->customer_id)->select('webiste_Address')->first();
        $all_Details    = [];
        $client_Details = DB::table('customer_subcriptions')->where('id',$request->client_Id)->get();
        foreach($client_Details as $val_CD){
            if($request->hotel_Id != NULL){
                $allowedHotels = DB::table('hotels')->where('owner_id',$request->customer_id)->where('id',$request->hotel_Id)->get();
            }else{
                // $allowedHotels = DB::table('hotels')->where('owner_id',$request->customer_id)->whereJsonContains('allowed_Clients', [['client_Id' => (string) $val_CD->id]])->get();
                
                $all_Hotels     = [];
                $allowd_Hotels  = DB::table('allowed_Hotels_Rooms')->where('client_Id',$val_CD->id)->orderBy('created_at', 'desc')->get();
                $collect_Hotels = collect($allowd_Hotels);
                $unique_AH      = $collect_Hotels->unique('hotel_Id')->values()->all();
                
                foreach($unique_AH as $val_AH){
                    $user_hotels    = DB::table('hotels')->where('id',$val_AH->hotel_Id)->orderBy('created_at', 'desc')->first();
                    array_push($all_Hotels,$user_hotels);
                }
                
                $collect_Hotels = collect($all_Hotels);
                $allowedHotels  = $collect_Hotels->unique('id')->values()->all();
            }
            if(!empty($allowedHotels) && count($allowedHotels) > 0){
                foreach($allowedHotels as $val_HD){
                    $total_Booking_Quantity = 0;
                    $total_Revenue          = 0;
                    $hotels_Bookings        = DB::table('hotels_bookings')->where('customer_id',$val_CD->id)->where('provider','Custome_hotel')->get();
                    if(!empty($hotels_Bookings) && count($hotels_Bookings) > 0){
                        foreach($hotels_Bookings as $val_HB) {
                            
                            if($val_HB->client_Id == '59'){
                                $val_HB->system_url = '';
                                
                                if(!empty($system_url->webiste_Address)){
                                    $val_HB->system_url = $system_url->webiste_Address.'/hotel_invoice';
                                }
                                
                                if(!empty($val_HB->customer_id)){
                                    $bookingClient      = DB::table('customer_subcriptions')->where('id',$val_HB->customer_id)->select('webiste_Address')->first();
                                    
                                    if(!empty($bookingClient->webiste_Address)){
                                        if($val_HB->customer_id == 4){
                                            $val_HB->system_url = $bookingClient->webiste_Address.'/hotel-booking-invoice' ?? '';
                                        }else{
                                            $val_HB->system_url = $bookingClient->webiste_Address.'/hotel_invoice' ?? '';
                                        }
                                    }
                                }
                            }
                            
                            if($val_HB->reservation_request != null && $val_HB->reservation_request != ''){
                                $reservation_request    = json_decode($val_HB->reservation_request);
                                $hotel_checkout_select  = json_decode($reservation_request->hotel_checkout_select);
                                if($hotel_checkout_select->hotel_id == $val_HD->id){
                                    array_push($all_Details,$val_HB);
                                }
                            }
                        }
                    }
                }
            }
        }
        
        return response()->json([
            'data'          => $all_Details,
            'system_url'    => $system_url->webiste_Address,
        ]);
    }
    
    public function delete_hotels(Request $request){
        DB::beginTransaction();
        try {
            $room_Exist = DB::table('hotels')
                            ->join('rooms','hotels.id','=','rooms.hotel_id')
                            ->where('hotels.owner_id',$request->customer_id)
                            ->where('hotels.id',$request->id)->get();
            
            if(count($room_Exist) > 0){
                return response()->json(['message'=>'Room_Exist']);
            }else{
                DB::table('hotels')->where('owner_id',$request->customer_id)->where('id',$request->id)->delete();
                // DB::table('rooms')->where('owner_id',$request->customer_id)->where('hotel_id',$request->id)->delete();
                DB::table('meta_infos')->where('hotel_id',$request->id)->delete();
                DB::table('policies')->where('hotel_id',$request->id)->delete();
                
                $meal_Exist = DB::table('meal_Types')->where('customer_id',$request->customer_id)->where('selected_Hotel',$request->id)->get();
                if(count($meal_Exist) > 0){
                    DB::table('meal_Types')->where('customer_id',$request->customer_id)->where('selected_Hotel',$request->id)->delete();
                }
                
                $allowed_Hotels_Rooms = DB::table('allowed_Hotels_Rooms')->where('customer_id',$request->customer_id)->where('hotel_Id',$request->id)->get();
                if(count($allowed_Hotels_Rooms) > 0){
                    return response()->json(['message'=>'Room Allowed to some clients']);
                    // DB::table('allowed_Hotels_Rooms')->where('customer_id',$request->customer_id)->where('hotel_Id',$request->id)->delete();
                }
            }
            
            DB::commit();
            return response()->json(['message'=>'success']);
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;die;
            return response()->json(['message'=>'error']);
        }
    }
}
