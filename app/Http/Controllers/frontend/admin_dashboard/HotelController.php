<?php

namespace App\Http\Controllers\frontend\admin_dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UmrahPackage;
use App\Models\Tour;
use App\Models\country;
use App\Models\view_booking_payment_recieve;
use App\Models\view_booking_payment_recieve_Activity;
use Auth;
use DB;
use App\Jobs\ApiJob;

class HotelController extends Controller
{
    
    public function search_api()
    {
       $queue_jobs=ApiJob::dispatch(); 
       return response()->json(['queue_jobs'=>'Successful']);
    }
    function save_passenger_detail_hotel(Request $request){
    
        $search_id                  = $request->search_id;
        $request_data               = json_decode($request->req_data);
        $lead_passenger_details     = json_decode($request_data->lead_passenger_details);
        $other_passenger_details    = json_decode($request_data->other_passenger_details);
        $child_details              = json_decode($request_data->child_details);
        
        $result = DB::table('hotel_booking')->where('search_id',$search_id)->update([
            'lead_passenger_details'        => $lead_passenger_details,
            'other_passenger_details'       => $other_passenger_details,
            'child_details'                 => $child_details,
        ]);
        
        if($result){
            return response()->json(['message'=>'success','search_id'=>$search_id,'lead_passenger_details'=>$lead_passenger_details,'other_passenger_details'=>$other_passenger_details,'child_details'=>$child_details]);
        }else{
            return response()->json(['message'=>'error']);
        }
    }
    
    public function add_lead_passengar(Request $request)
    {
        $search_id=$request->search_id;
        $lead_passenger_details=$request->lead_passenger_details;
        $add_lead_passengar = DB::table('hotel_provider_bookings')->where('invoice_no',$search_id)->update(
        [
        'lead_passenger_details'=>$lead_passenger_details,
        
        ]);
        
        return response()->json(['lead_passenger_details'=>$lead_passenger_details]);
        
   }
    public function add_other_passengar(Request $request)
    {
        $search_id=$request->search_id;
        $other_passenger_details=$request->other_passenger_details;
       $other_data = DB::table('hotel_provider_bookings')->where('invoice_no',$search_id)->update(
        [
        'other_passenger_details'=>$other_passenger_details,
        
        ]);
        return response()->json(['other_data'=>$other_data]);
        
        
   }
   
    public function add_child_passengar(Request $request)
    {
        $search_id=$request->search_id;
        $child_details=$request->child_details;
        $other_data = DB::table('hotel_provider_bookings')->where('invoice_no',$search_id)->update(
        [
            'child_details'=>$child_details,
        ]);
        return response()->json(['other_data'=>$other_data]);    
    }
   
   
   
    public function hotelbed_booking(Request $request)
    {
        $is_refundable=$request->is_refundable;
         if($is_refundable == 'non_refundable')
            {
                $booking_status='Non Refundable';
            }
            if($is_refundable == 'refundable')
            {
                $booking_status='Confirmed';
            }
            
        $search_id=$request->search_id;
        $hotelbedcnfrmRS=$request->hotelbedcnfrmRS;
        $hotelbedcnfrmRQ=$request->hotelbedcnfrmRQ;
        $auth_token=$request->auth_token;
       $other_data = DB::table('hotel_booking')->where('search_id',$search_id)->update(
        [
        'hotelbedcnfrmRQ'=>$hotelbedcnfrmRQ,
        'hotelbedcnfrmRS'=>$hotelbedcnfrmRS,
         'hotelbedcnfrmRS1'=>$hotelbedcnfrmRS,
        'booking_status'=> $booking_status,
         'auth_token'=> $auth_token
        
        ]);
        return response()->json(['other_data'=>$other_data]);
        
        
   }
   
       public function hotel_bed_cancelliation(Request $request)
    {
        $search_id=$request->search_id;
        $hotelbedcnfrmRS=$request->hotelbedcnfrmRS;
        
       $data = DB::table('hotel_booking')->where('search_id',$search_id)->update([
        'hotelbedcnfrmRS'=>$hotelbedcnfrmRS,
        'booking_status'=> 'Cancelled'
        ]);
        return response()->json(['data'=>$data]);
        
        
   }
    
    
    public function get_booking_checkout(Request $request)
    {
        $search_id=$request->search_id;
       $data = DB::table('hotel_provider_bookings')->where('invoice_no',$search_id)->first();
         $countries = DB::table('countries')->get();
       
       return response()->json(['data'=>$data,'countries'=>$countries]);
        
        
   }

  public function hotel_cart(Request $request)
    {
        $search_id=$request->search_id;
       $data = DB::table('hotel_booking')->where('search_id',$search_id)->first();
        return response()->json(['data'=>$data]);
        
        
   }
   public function travellanda_cancel_policy_data(Request $request)
    {
        $search_id=$request->search_id;
        $json=$request->json;
                   $data=DB::table('hotel_booking')->where('search_id',$search_id)->update([
                 
                 'travellanda_cancellation_response'=>$json
                 ]);
        return response()->json(['data'=>$data]);
        
        
   }
   public function get_travelanda_hotel_details(Request $request)
    {
        $HotelId=$request->HotelId;
      $get_res=DB::table('travellanda_get_hotel_details')->where('HotelId',$HotelId)->first();
        return response()->json(['get_res'=>$get_res]);
        
        
   }
    
      public function get_hotel_cities_code(Request $request)
    {

        $CityName=$request->CityName;
      $get_res=DB::table('travellanda_get_cities')->where('CityName',$CityName)->first();
        return response()->json(['get_res'=>$get_res]);
        
        
   }
   public function get_hotel_details_by_hotelbeds(Request $request)
    {
        $code=$request->code;
      $get_res=DB::table('hotel_beds_hotel_details')->where('code',$code)->first();
      

       return response()->json(['get_res'=>$get_res]);
        
        
   }
   public function travellanda_get_hotel_details(Request $request)
    {
        $hotelid=$request->hotelid;
      $travellanda_get_hotel_details=DB::table('travellanda_get_hotel_details')->where('HotelId',$hotelid)->first();
      

       return response()->json(['travellanda_get_hotel_details'=>$travellanda_get_hotel_details]);
        
        
   }
   public function get_country_hotel_beds(Request $request)
    {
        $country_code=json_decode($request->country_code);
        
        foreach($country_code as $index => $country_res)
        {
           $SA=DB::table('hotel_beds_hotel_details')->where('countryCode',$country_res)->count();
           echo $SA." ".$index;
        }
        
        
      
     
      

       return response()->json(['get_country_hotel_beds'=>$get_country_hotel_beds,'get_country_hotel_beds1'=>$get_country_hotel_beds1]);
        
        
   }
   
   public function tbo_get_hotel_code_list(Request $request)
    {
        $CityName=$request->CityName;
        $tbo_hotel_codes=\DB::table('apisynchtravel_h2023.tbo_hotel_details')->where('city_name',$CityName)->get();
      //$tbo_hotel_codes=DB::table('hotel_details')->where('city_name',$CityName)->get();
      

       return response()->json(['tbo_hotel_codes'=>$tbo_hotel_codes]);
        
        
   }
    
    
    
    public function all_provider_booking(Request $request)
    {
        $token=$request->token;
        $all_booking=DB::table('hotel_provider_bookings')->where('auth_token',$token)->where('provider','!=','NULL')->orderBy('id', 'DESC')->get();
        $customer_subcriptions=DB::table('customer_subcriptions')->where('Auth_key',$token)->first();
        $hotel_payment_details=DB::table('hotel_payment_details')->where('auth_token',$token)->first();
       
        return response()->json([
        'all_booking'=>$all_booking,
        'customer_subcriptions'=>$customer_subcriptions,
        'hotel_payment_details'=>$hotel_payment_details]);
        
        
   }
   
    
    public function get_booking_with_token(Request $request)
    {
        $token=$request->token;
        $travellanda_hotel_booking=DB::table('hotel_booking')->where('auth_token',$token)->where('provider','travellanda')->limit(5)->orderBy('id', 'DESC')->get();
        $customer_subcriptions=DB::table('customer_subcriptions')->where('Auth_key',$token)->first();
        $hotel_payment_details=DB::table('hotel_payment_details')->where('auth_token',$token)->first();
       
        return response()->json([
        'travellanda_hotel_booking'=>$travellanda_hotel_booking,
        'customer_subcriptions'=>$customer_subcriptions,
        'hotel_payment_details'=>$hotel_payment_details]);
        
        
   }
   public function get_booking_with_token_hotelbeds(Request $request)
    {
        $token=$request->token;
  
        $hotelbeds_hotel_booking=DB::table('hotel_booking')->where('auth_token',$token)->where('provider','hotelbeds')->limit(5)->orderBy('id', 'DESC')->get();
        $customer_subcriptions=DB::table('customer_subcriptions')->where('Auth_key',$token)->first();
        $hotel_payment_details=DB::table('hotel_payment_details')->where('auth_token',$token)->first();
       
        return response()->json([
           
        'hotelbeds_hotel_booking'=>$hotelbeds_hotel_booking,
        'customer_subcriptions'=>$customer_subcriptions,
        'hotel_payment_details'=>$hotel_payment_details]);
        
        
   }
   public function get_booking_with_token_tbo(Request $request)
    {
        $token=$request->token;
        $tbo_hotel_booking=DB::table('hotel_booking')->where('auth_token',$token)->where('provider','tbo')->limit(5)->orderBy('id', 'DESC')->get();
        $customer_subcriptions=DB::table('customer_subcriptions')->where('Auth_key',$token)->first();
        $hotel_payment_details=DB::table('hotel_payment_details')->where('auth_token',$token)->first();
       
        return response()->json([
          
        'tbo_hotel_booking'=>$tbo_hotel_booking,
        'customer_subcriptions'=>$customer_subcriptions,
        'hotel_payment_details'=>$hotel_payment_details]);
        
        
   }
   public function get_booking_with_token_ratehawk(Request $request)
    {
        $token=$request->token;
  $ratehawk_hotel_booking=DB::table('hotel_booking')->where('auth_token',$token)->where('provider','ratehawk')->limit(5)->orderBy('id', 'DESC')->get();
        $customer_subcriptions=DB::table('customer_subcriptions')->where('Auth_key',$token)->first();
        $hotel_payment_details=DB::table('hotel_payment_details')->where('auth_token',$token)->first();
        return response()->json([
        'ratehawk_hotel_booking'=>$ratehawk_hotel_booking,
        'customer_subcriptions'=>$customer_subcriptions,
        'hotel_payment_details'=>$hotel_payment_details]);
        
        
   }
    public function booking_now_admin(Request $request)
    {
        $token=$request->token;
        $search_id=$request->search_id;
        
        $data=DB::table('hotel_booking')->where('auth_token',$token)->where('search_id',$search_id)->first();
        
       
        return response()->json(['data'=>$data]);
        
        
   }
    public function get_payment_detact_by_admin(Request $request)
    {
       $amount_paid=$request->amount_paid;
       $total_amount=$request->total_amount;
       $remaining_amount=$request->remaining_amount;
       $recieved_amount=$request->recieved_amount;
       $invoice_no=$request->invoice_no;
        $auth_token=$request->token;
        
        $data = DB::table('hotel_payment_details')->insert([
            
           'hotel_search_id'=>$invoice_no,
            'recieved_amount'=>$recieved_amount,
            'total_amount'=>$total_amount,
            'remaining_amount'=>$remaining_amount,
            'amount_paid'=>$amount_paid,
            'auth_token'=>$auth_token,
            ]);
             return response()->json(['data'=>  $data,]);
    }
    public function get_hotel_payment_details_by_id(Request $request)
    {
       $search_id=$request->search_id;
       $hotel_payment_details=DB::table('hotel_payment_details')->where('hotel_search_id',$search_id)->sum('amount_paid');
        
       
             return response()->json(['hotel_payment_details'=> $hotel_payment_details]);
    }
       public function get_booking_checkout_admin(Request $request)
    {
        $search_id=$request->search_id;
       $data = DB::table('hotel_provider_bookings')->where('invoice_no',$search_id)->first();
         $countries = DB::table('countries')->get();
       
        return response()->json(['data'=>$data,'countries'=>$countries]);
        
        
   }
    
    
}
