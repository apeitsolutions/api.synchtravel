<?php

namespace App\Http\Controllers\HotelMangers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\hotel_manager\Rooms;
use App\Models\admin\RoomsType;
use App\Models\hotel_manager\Hotels;
use App\Models\MetaInfo;
use App\Models\Policies;
use App\Models\country;


use Auth;
use DB;
use Session;

class HotelController extends Controller
{
    public function hotel_list_Admin(){
        $user_hotels    = Hotels::orderBy('created_at', 'desc')->get();
        $all_Users      = DB::table('customer_subcriptions')->get();
        return view('template/frontend/userdashboard/pages/hotel_manager.hotels_list',compact('user_hotels','all_Users'));
    }
    
    public function index()
    {
        $user_hotels = Hotels::join('countries','hotels.property_country','=','countries.id')
              ->join('cities','hotels.property_city','=','cities.id')
              ->where('hotels.owner_id',Auth::user()->id)
              ->orderBy('hotels.created_at', 'desc')
              ->get(['hotels.*','countries.name','cities.name']);
         //$user_hotels = Hotels::where('owner_id',Auth::user()->id)->orderBy('created_at', 'desc')->paginate(6);
        return view('template/frontend/userdashboard/pages/hotel_manager.hotels_list',compact('user_hotels'));

        // dd($user_hotels);
    }

    public function showAddHotelFrom(){
        $all_countries = country::all();
        return view('template/frontend/userdashboard/pages/hotel_manager.add_hotel',compact('all_countries'));
    }

    public function addHotel(Request $request){

        // $validated = $request->validate([
        //     'property_name'=>'required|max:255',
        //     'property_type'=>'required|max:255',
        //     'star_type'=>'required|max:255',
        //     'price_type'=>'required|max:255',
        //     'hotel_check_in'=>'required|max:255',
        //     'hotel_check_out'=>'required|max:255',
        //     'property_address'=>'required|max:255',
        //     'property_google_map'=>'required|max:1000',
        //     'property_country'=>'required|max:255',
        //     'property_city'=>'required|max:255',
        //     'property_desc'=>'required|max:255',
        //     'property_phone'=>'required|max:255',
        //     'latitude'=>'required|max:1000',
        //     'longitude'=>'required|max:1000',
        //     'b2c_markup'=>'required|numeric|min:0|max:100',
        //     'b2b_markup'=>'required|numeric|min:0|max:100',
        //     'b2e_markup'=>'required|numeric|min:0|max:100',
        //     'service_fee'=>'required|numeric|min:0|max:100',
        //     'tax_value'=>'required',
        //     'meta_title'=>'required|max:255',
        //     'meta_keywords'=>'required',
        //     'meta_desc'=>'required',
        //     'policy_and_terms'=>'required',
        //     'hotel_email'=>'required',
        //     'hotel_website'=>'required',            
        //     'property_img'=>'image|required|mimes:jpg,png,jpeg,gif,svg|max:2048',
        // ]);

   
        $hotel = new Hotels;
        $hotel->property_name =  $request->property_name; 
        $hotel->property_desc =  $request->property_desc; 
        $hotel->property_google_map =  $request->property_google_map; 
        $hotel->latitude =  $request->latitude; 
        $hotel->longitude =  $request->longitude;
        $hotel->property_country =  $request->property_country; 
        $hotel->property_city =  $request->property_city;  
        $hotel->price_type =  $request->price_type; 
        $hotel->star_type =  $request->star_type; 
        $hotel->status =  $request->status; 
        $hotel->property_type =  $request->property_type; 
        $hotel->b2c_markup =  $request->b2c_markup; 
        $hotel->b2b_markup =  $request->b2b_markup; 
        $hotel->b2e_markup =  $request->b2e_markup; 
        $hotel->service_fee =  $request->service_fee; 
        $hotel->tax_type =  $request->tax_type; 
        $hotel->tax_value =  $request->tax_value; 
        
        $hotel->facilities = serialize($request->Facilites); 


        // $hotel->hotel_check_in =  $request->hotel_check_in; 
        // $hotel->hotel_check_out =  $request->hotel_check_out; 
        $hotel->hotel_email =  $request->hotel_email; 
        $hotel->hotel_website =  $request->hotel_website; 
        $hotel->property_phone =  $request->property_phone; 
        $hotel->property_address =  $request->property_address; 
        
        
        if($request->file('property_img')){
           
            $img_file = $request->file('property_img');
            $name_gen = hexdec(uniqid());
            $img_ext = strtolower($img_file->getClientOriginalExtension());
            $img_name = $name_gen.".".$img_ext;
            $upload = 'public/images/hotels';
            $file_upload = $img_file->move($upload,$img_name);
            if($file_upload){
              
                $user_id = Auth::user()->id;
                $hotel->property_img = $img_name;
                $hotel->owner_id = $user_id;

                DB::beginTransaction();
                try {
                    $result = $hotel->save();
                    $hotelId = $hotel->id;
    
                    // Save Meta Info
                    $metaInfo = new MetaInfo;
                    $metaInfo->meta_title =  $request->meta_title; 
                    $metaInfo->keywords =  $request->meta_keywords; 
                    $metaInfo->meta_desc =  $request->meta_desc; 
                    $metaInfo->hotel_id  = $hotelId;
                    $meta_info_Result = $metaInfo->save();
    
                    // Save Poilices
                    $policies = new Policies;
                    $policies->check_in_form = $request->hotel_check_in;
                    $policies->check_out_to = $request->hotel_check_out;
                    $policies->payment_option = $request->payment_option;
                    $policies->policy_and_terms = $request->policy_and_terms;
                    $policies->hotel_id = $hotelId;
                    // dd($hotel);
                    $policies_Result = $policies->save();

                    DB::commit();
                    return redirect()->back()->with('success','Hotel Added Successfuly');                
                } catch (\Exception $e) {
                    DB::rollback();
                    return redirect()->back()->with('error','Sorry Something WentWrong Try Again');
                    // something went wrong
                }
                            
            }else{
                // return redirect()->back()->with('error','Sorry Something WentWrong Try Again');

            }
        }else{
            echo "File Missing";
             // dd($request->file('property_img'));
        }

        // dd($request->all());
    }
    
    public function add_hotel_sub_ajax(Request $request){

        $hotel = new Hotels;
        $hotel->property_name =  $request->property_name; 
        $hotel->property_desc =  $request->property_desc; 
        $hotel->property_google_map =  $request->property_google_map; 
        $hotel->latitude =  $request->latitude; 
        $hotel->longitude =  $request->longitude;
        $hotel->property_country =  $request->property_country; 
        $hotel->property_city =  $request->property_city;  
        $hotel->price_type =  $request->price_type; 
        $hotel->star_type =  $request->star_type; 
        $hotel->status =  $request->status; 
        $hotel->property_type =  $request->property_type; 
        $hotel->b2c_markup =  $request->b2c_markup; 
        $hotel->b2b_markup =  $request->b2b_markup; 
        $hotel->b2e_markup =  $request->b2e_markup; 
        $hotel->service_fee =  $request->service_fee; 
        $hotel->tax_type =  $request->tax_type; 
        $hotel->tax_value =  $request->tax_value; 
        
        $hotel->facilities = serialize($request->Facilites); 

        // $hotel->hotel_check_in =  $request->hotel_check_in; 
        // $hotel->hotel_check_out =  $request->hotel_check_out; 
        $hotel->hotel_email =  $request->hotel_email; 
        $hotel->hotel_website =  $request->hotel_website; 
        $hotel->property_phone =  $request->property_phone; 
        $hotel->property_address =  $request->property_address; 
        
        
        if($request->file('property_img')){
           
            $img_file = $request->file('property_img');
            $name_gen = hexdec(uniqid());
            $img_ext = strtolower($img_file->getClientOriginalExtension());
            $img_name = $name_gen.".".$img_ext;
            $upload = 'public/images/hotels';
            $file_upload = $img_file->move($upload,$img_name);
            if($file_upload){
              
                $user_id = Auth::user()->id;
                $hotel->property_img = $img_name;
                $hotel->owner_id = $user_id;

                DB::beginTransaction();
                try {
                    $result = $hotel->save();
                    $hotelId = $hotel->id;
    
                    // Save Meta Info
                    $metaInfo = new MetaInfo;
                    $metaInfo->meta_title =  $request->meta_title; 
                    $metaInfo->keywords =  $request->meta_keywords; 
                    $metaInfo->meta_desc =  $request->meta_desc; 
                    $metaInfo->hotel_id  = $hotelId;
                    $meta_info_Result = $metaInfo->save();
    
                    // Save Poilices
                    $policies = new Policies;
                    $policies->check_in_form = $request->hotel_check_in;
                    $policies->check_out_to = $request->hotel_check_out;
                    $policies->payment_option = $request->payment_option;
                    $policies->policy_and_terms = $request->policy_and_terms;
                    $policies->hotel_id = $hotelId;
                    // dd($hotel);
                    $policies_Result = $policies->save();

                    DB::commit();
                    return redirect()->back()->with('success','Hotel Added Successfuly');                
                } catch (\Exception $e) {
                    DB::rollback();
                    return redirect()->back()->with('error','Sorry Something WentWrong Try Again');
                }
            }else{
            }
        }else{
            echo "File Missing";
        }
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
    
     public function hotel_Room_api(Request $request){
      $start_date = $request->start_date;
      $end_date   = $request->end_date;
      $cityID     = $request->cityID;
    //   print_r($start_date);die();
       $rooms  = DB::table('rooms')->where('availible_from','<=',$start_date)->where('availible_to','>=',$end_date)
                ->join('rooms_types','rooms.room_type_id','=','rooms_types.id')
                ->join('hotels','rooms.hotel_id','=','hotels.id')->where('property_city','=',$cityID)
                ->get();
     return response()->json(['rooms'=>$rooms]);
    }

    // Room
    public function roomID($id){
        
        
        
        $rooms = Rooms::where('hotel_id',$id)->join('rooms_types','rooms.room_type_id','=','rooms_types.id')->get();
        echo $rooms ;
    }
    public function roomID_api(Request $request){
        
        
        $id=$request->id;
        // print_r($id);die();
        $rooms = Rooms::where('hotel_id',$id)->join('rooms_types','rooms.room_type_id','=','rooms_types.id')->get();
         return response()->json(['rooms'=>$rooms]);
    }
    
    function process($id,$slug){
        
             $data = DB::table('hotel_booking')->where('search_id',$id)->first();
            //  print_r($data);die();
          
         return view('template/frontend/userdashboard/pages/hotel_booking/confrim_booking',compact('data','id','slug'));
     

}



    function confrim_booking_hotel(Request $request)
    {
         $data_res=$request->hotelbeds_booking_rq;
        //  print_r($data_res);die();
     $booking_slug=$request->hotelbeds_booking_slug;
     if($booking_slug == 'hotelbeds')
       {
           $hotelbeds_booking_id=$request->hotelbeds_booking_id;
            $data = DB::table('hotel_booking')->where('search_id',$hotelbeds_booking_id)->first();
            $hotelbedcnfrmRQ=$data->hotelbedcnfrmRQ;
           
           
           
            $apiKey = '833583586f0fd4fccd3757cd8a57c0a8';
        $secret = 'ae2dc887d0';
        $signature = hash("sha256", $apiKey.$secret.time());
        
         $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.test.hotelbeds.com/hotel-api/1.0/bookings',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$hotelbedcnfrmRQ,
  CURLOPT_HTTPHEADER => array(
    'Api-key: 833583586f0fd4fccd3757cd8a57c0a8',
    "X-Signature: $signature",
    'Accept: application/json',
    'Accept-Encoding: gzip',
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);
// echo $response;die();
curl_close($curl);  
 $data = DB::table('hotel_booking')->where('search_id',$hotelbeds_booking_id)->update([
     
     'hotelbedcnfrmRS'=>$response,
     'hotelbedcnfrmRS1'=>$response,
     'booking_status'=>'Confirmed',
     
     ]);
 return redirect('https://alhijaztours.net/voucher/'.$hotelbeds_booking_id.'/'.$booking_slug.'');
 
       } 
       
       if($booking_slug == 'travellanda')
       {
           $hotelbeds_booking_id=$request->hotelbeds_booking_id;
            $data = DB::table('hotel_booking')->where('search_id',$hotelbeds_booking_id)->first();
            $travellandacnfrmRQ=$data->travellandacnfrmRQ;
           
           //print_r($travellandacnfrmRQ);die();
           
           $url = "http://xmldemo.travellanda.com/xmlv1/";
            $timeout = 20; 
            $data = array('xml' => $travellandacnfrmRQ);
            $headers = array(
                "Content-type: application/x-www-form-urlencoded",
            );
            $ch = curl_init();
            $payload = http_build_query($data);
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
            $rawdata = curl_exec($ch);
            // print_r($rawdata);die;
            $xml = simplexml_load_string($rawdata);
            $json = json_encode($xml);
            
            $data = json_decode($json);
            
            
        // print_r($data);
          
        // die();
        
        
        
 $data = DB::table('hotel_booking')->where('search_id',$hotelbeds_booking_id)->update([
     
     'travellandacnfrmRS'=>$json,
     'booking_status'=>'Confirmed',
     
     ]);
 return redirect('https://alhijaztours.net/voucher/'.$hotelbeds_booking_id.'/'.$booking_slug.'');
 
       } 
        
        if($booking_slug == 'tbo')
        {
           
            $tbo_booking_id=$request->hotelbeds_booking_id;
         $data = DB::table('hotel_booking')->where('search_id',$tbo_booking_id)->first();
            $tboBookingRQ=$data->tboBookingRQ;
         //print_r($tboBookingRQ);die();
             $curl = curl_init();
              curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://api.tbotechnology.in/TBOHolidays_HotelAPI/Book',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $tboBookingRQ,
                CURLOPT_HTTPHEADER => array(
                  'Content-Type: application/json',
                  'Authorization: Basic VGVzdEFQRUlUU29sdXRpb246QXBlQDU3NjM0NjYz'
                ),
              ));
          
     $response = curl_exec($curl);
    //  echo $response;die();
     
     curl_close($curl);
      $cancellationCode = json_decode($response);
     //print_r($cancellationCode);die();
     
     
     
     $data = DB::table('hotel_booking')->where('search_id',$tbo_booking_id)->update([
     
     'tboBookingRS'=>$response,
     'booking_status'=>'Confirmed',
     
     ]);
     
     if($cancellationCode->Status->Description != 'Successful')
         {
             return redirect()->back()->with('message1','Some Thing Error Please Try Again');
           
         }
         else
         {
           return redirect('https://alhijaztours.net/voucher/'.$tbo_booking_id.'/'.$booking_slug.'');
         }
         
         
        }
        
        
       
        
        
    }
 public function voucher($id,$slug){
         if($slug =="travellanda"){
             $fetched_request = DB::table('hotel_booking')->where('search_id',$id)->first();
             $booked_datas = json_decode($fetched_request->travellandacnfrmRS);
             $booked_data = $booked_datas->Body->Bookings->HotelBooking;
             $customer_decoded = json_decode($fetched_request->customer);
             
return view('template/frontend/userdashboard/pages/hotel_booking/booking_voucher',compact('booked_data','customer_decoded','slug'));
         } 
        }
}
