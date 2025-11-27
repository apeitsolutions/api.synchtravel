<?php

namespace App\Http\Controllers\AppApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UmrahPackage;
use App\Models\Tour;
use App\Models\country;
use App\Models\view_booking_payment_recieve;
use App\Models\view_booking_payment_recieve_Activity;
use App\Models\CustomerSubcription\CustomerSubcription;
use Auth;
use DB;
use Illuminate\Support\Facades\Session;
use App\Jobs\ApiJob;
use Illuminate\Support\Facades\Hash;
use App\Models\booking_customers;

class WebApiController extends Controller
{
    protected $token='r9fdvwRyF35JUnD6xXdRiDELANYjtfASzPmyGol4-1PN461EY50LbXcqkfEfISsOJDrnFDJbuMzPuxTz37zFWGWBVemQGhi2SYLrr-MZ75vJSAiV73z94UOVrDz5P6R-0KjFqr9XR6P2857snQbcKTUn9YNqjBOQQIkXENeO7tmjxdTJs2KUVoXqo6fFyT9TTq99eKe288N-wyanZXxOsfABWPjtSom2oKLVz6vJnn1WeQwHSp7VnzPUqq53rn80eFXNBSMIiEXBdDmlsokRYSa0evDrQKluhnIzMYkRiazxtnkb-z5Xj0tQReTTHsLz1sgnit2mRGGzP4EIdBK8TiLuEN7GD1kmOT3CMreL7ELrI4yxmEbnYyflICtG-ySk3aZkk8iM9mRZlA7CS10Zuj-C0HEBOFW8vMzy4Eq2CET5WN62S1xe0HPAOrDVwO6jDvVpKEMwm-NiyyjkU8oTTlgYpN77pXtfFjKPTF0julnAMC6cPzxZOGBIkRv0';

    
  public function hotel_search(Request $request)
 {
     
       Session()->forget('checkin_m');
       Session()->forget('checkout_m');
       Session()->forget('search_rq_m');
       Session()->forget('search_rs_m');
       Session()->forget('checkrates_rq_m');
       Session()->forget('checkrates_rs_m');
       Session()->forget('booking_rq_m');
       Session()->forget('booking_rs_m');
       Session()->forget('booking_details_rq_m');
       Session()->forget('booking_details_rs_m');
       Session()->forget('room_search_m');
       Session()->forget('adults_search_m');
       Session()->forget('children_search_m');
       Session()->forget('lead_passenger_details_m');
       Session()->forget('other_passenger_details_m');
       Session()->forget('other_child_m');
       
       
       
      
       
     
    $request_token= $_POST['token'];
    
    if($this->token == $request_token)
    {
        
    $newstart= $_POST['CheckIn'];
    $newend= $_POST['CheckOut'];
    $res_hotel_beds= $_POST['rooms_obj'];
    
    
    $rooms_count=0;
    $adults_count=0;
    $children_count=0;
    $pax_count=json_decode($res_hotel_beds);
    foreach($pax_count as $pax_c)
    {
       $rooms_count=$pax_c->rooms + $rooms_count;
       $adults_count=$pax_c->adults + $adults_count;
       $children_count=$pax_c->children + $children_count;
    }
//print_r($children_count);die();




    $lat =$_POST['lat'];
    $long =$_POST['long'];
    
    
    Session()->put('checkin_m',$newstart);
    Session()->put('checkout_m',$newend);
    Session()->put('room_search_m',$rooms_count);
    Session()->put('adults_search_m',$adults_count);
    Session()->put('children_search_m',$children_count);
    
    function hotel_search_fun($newstart,$newend,$res_hotel_beds,$lat,$long)
          {
              $url = "https://admin.synchronousdigital.com/synchtravelhotelapi/hotelbeds_app_api.php";
              $data = array('case' => 'serach_hotelbeds', 'CheckIn' => $newstart, 'CheckOut' => $newend, 'rooms_obj' => $res_hotel_beds, 'lat' => $lat, 'long' => $long);
                 Session()->put('search_rq_m',$data);
              //print_r($data);die();
               $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $url);
              curl_setopt($ch, CURLOPT_POST, true);
              curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
              
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              $responseData = curl_exec($ch);
              //echo $responseData;die();
              if (curl_errno($ch)) {
                  return curl_error($ch);
              }
              curl_close($ch);
              return $responseData;
          }
  
          $responseData3 = hotel_search_fun($newstart,$newend,$res_hotel_beds,$lat,$long);
          $results=json_decode($responseData3);
          Session()->put('search_rs_m',$results);
          return response()->json(['message'=>'Successful','data'=>$results]);
          
          
//print_r($result_booking_rs);die;
    }
    else
    {
        return response()->json(['message','Invalid Token']);
    }
     
     
     
 }
 
 public function hotel_checkrates()
 {
   
    $request_token= $_POST['token'];
    
    if($this->token == $request_token)
    {
        
    $roomRateIn= $_POST['roomRate'];
    
    function hotel_checkrates_fun($roomRateIn)
          {
              $url = "https://admin.synchronousdigital.com/synchtravelhotelapi/hotelbeds_app_api.php";
              $data = array('case' => 'getBooking', 'roomRate' => $roomRateIn);
              Session()->put('checkrates_rq_m',$data);
              //print_r($data);die();
               $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $url);
              curl_setopt($ch, CURLOPT_POST, true);
              curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
              
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              $responseData = curl_exec($ch);
              //echo $responseData;die();
              if (curl_errno($ch)) {
                  return curl_error($ch);
              }
              curl_close($ch);
              return $responseData;
          }
  
          $responseData3 = hotel_checkrates_fun($roomRateIn);
          $results=json_decode($responseData3);
          Session()->put('checkrates_rs_m',$results);
          return response()->json(['message'=>'Successful','data'=>$results]);
          
          
//print_r($result_booking_rs);die;
    }
    else
    {
        return response()->json(['message','Invalid Token']);
    }  
 }
  public function hotel_reservation()
 {
    $request_token= $_POST['token'];
    
    if($this->token == $request_token)
    {
        // print_r($_POST['lead_passenger_details']);die;
    $lead_passenger_details= $_POST['lead_passenger_details'];
    $room_searching= $_POST['room_searching'];
    $t_passenger= $_POST['t_passenger'];
    $other_passenger_details= $_POST['other_passenger_details'];
    $other_child= $_POST['other_child'];
    $hotel_bed_remarks= $_POST['hotel_bed_remarks'];
    $hotel_beds_select= $_POST['hotel_beds_select'];

    
 
     Session()->put('lead_passenger_details_m',$lead_passenger_details);
     Session()->put('other_passenger_details_m',$other_passenger_details);
     Session()->put('other_child_m',$other_child);
     
    function hotel_reservation_fun($lead_passenger_details,$room_searching,$t_passenger,$other_passenger_details,$other_child,$hotel_bed_remarks,$hotel_beds_select)
          {
              $url = "https://admin.synchronousdigital.com/synchtravelhotelapi/hotelbeds_app_api.php";
              $data = array('case' => 'confirmbooking', 'lead_passenger_details' => $lead_passenger_details,'room_searching' => $room_searching,'t_passenger' => $t_passenger,'other_passenger_details' => $other_passenger_details,'other_child' => $other_child,'hotel_bed_remarks'=>$hotel_bed_remarks,'hotel_beds_select'=>$hotel_beds_select);
               Session()->put('booking_rq_m',$data);
              //print_r($data);die();
               $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $url);
              curl_setopt($ch, CURLOPT_POST, true);
              curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
              
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              $responseData = curl_exec($ch);
              //echo $responseData;die();
              if (curl_errno($ch)) {
                  return curl_error($ch);
              }
              curl_close($ch);
              return $responseData;
          }
  
          $reservation_details_res = hotel_reservation_fun($lead_passenger_details,$room_searching,$t_passenger,$other_passenger_details,$other_child,$hotel_bed_remarks,$hotel_beds_select);
          
         $reservation_details=json_decode($reservation_details_res);
        Session()->put('booking_rs_m',$reservation_details);
         //print_r($reservation_details);die();
          $reference_no=$reservation_details->booking->reference;
          
          function hotel_booking_reservation_fun($reference_no)
          {
              $url = "https://admin.synchronousdigital.com/synchtravelhotelapi/hotelbeds_app_api.php";
              $data = array('case' => 'booking_details', 'bookingReference' => $reference_no);
              Session()->put('booking_details_rq_m',$data);
              //print_r($data);die();
               $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $url);
              curl_setopt($ch, CURLOPT_POST, true);
              curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
              
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              $responseData = curl_exec($ch);
              //echo $responseData;die();
              if (curl_errno($ch)) {
                  return curl_error($ch);
              }
              curl_close($ch);
              return $responseData;
          }
  
          $responseData3 = hotel_booking_reservation_fun($reference_no);
         
          
          $reservation_booking_details=json_decode($responseData3);
         Session()->put('booking_details_rs_m',$reservation_booking_details);
          
          
          
          
          $invoice_no = random_int(100000000000000, 999999999999999);
          
           $checkin_m=Session()->get('checkin_m');
           $checkout_m=Session()->get('checkout_m');
           $search_rq_m=Session()->get('search_rq_m');
           $search_rs_m=Session()->get('search_rs_m');
           $checkrates_rq_m=Session()->get('checkrates_rq_m');
           $checkrates_rs_m=Session()->get('checkrates_rs_m');
            
             $booking_rq_m=Session()->get('booking_rq_m');
            $booking_details_rq_m=Session()->get('booking_details_rq_m');
            
           $room_search_m=Session()->get('room_search_m');
           $adults_search_m=Session()->get('adults_search_m');
           $lead_passenger_details_m=Session()->get('lead_passenger_details_m');
           $other_passenger_details_m=Session()->get('other_passenger_details_m');
           $other_child_m=Session()->get('other_child_m');
       
          
          $lead_passenger = json_decode($lead_passenger_details_m);
          $userData = CustomerSubcription::where('Auth_key',$token)->select('id','status')->first();

                $customer_exist = DB::table('booking_customers')->where('customer_id',$userData->id)->where('email',$lead_passenger->lead_email)->first();
                if(isset($customer_exist) && $customer_exist != null && $customer_exist != ''){
                        $customer_id = $customer_exist->id;
                }else{
                   
                    
                    $password = Hash::make('admin123');
                    
                    $customer_detail                    = new booking_customers();
                    $customer_detail->name              = $lead_passenger->lead_first_name." ".$lead_passenger->lead_last_name;
                    $customer_detail->opening_balance   = 0;
                    $customer_detail->balance           = 0;
                    $customer_detail->email             = $lead_passenger->lead_email;
                    $customer_detail->password             = $password;
                    $customer_detail->phone             = $lead_passenger->lead_phone;

                    $customer_detail->customer_id       = $userData->id;
                    $result = $customer_detail->save();
                    
                    $customer_id = $customer_detail->id;
    
                    
                }
          
          $db_data = DB::table('hotel_provider_bookings')->insert([
     
            'booked' => 'App',
            'auth_token' => $token,
            'booking_customer_id' => $customer_id,
            'invoice_no' => $invoice_no,
            'check_in' => $checkin_m,
            'check_out' => $checkout_m,
            'rooms' => $room_search_m,
            'adults' => $adults_search_m,
            'childs' => '0',
            'lead_passenger_details' => $lead_passenger_details_m,
            'other_passenger_details' => $other_passenger_details_m,
            'child_details' => $other_child_m,
            'provider' => 'hotelbeds',
            'search_rq' => json_encode($search_rq_m),
            'search_rs' => json_encode($search_rs_m),
            'checkavailability_rq' => json_encode($checkrates_rq_m),
            'checkavailability_rs' => json_encode($checkrates_rs_m),
            'checkavailability_again_rq' => json_encode($checkrates_rq_m),
            'checkavailability_again_rs' => json_encode($checkrates_rs_m),
            'booking_rq' => json_encode($booking_rq_m),
            'booking_rs' => json_encode($reservation_details),
            'booking_detail_rq' => json_encode($booking_details_rq_m),
            'booking_detail_rs' => json_encode($reservation_booking_details),
            'booking_status' => 'Confirmed',
            
            
        ]);
          
    
        return response()->json(['message'=>'Booking Reservation Successful','invoice_no'=>$invoice_no,'reservation_details'=>$reservation_details,'reservation_booking_details'=>$reservation_booking_details]);
    }
    else
    {
        return response()->json(['message','Invalid Token']);
    }  
 }
 
    public function hotel_reservation_cancel()
     {
      
    $request_token= $_POST['token'];
    
    if($this->token == $request_token)
    {
        $refrence_id= $_POST['refrence_id'];
        $invoice_no= $_POST['invoice_no'];
        
      function hotel_reservation_cancel_fun($refrence_id)
          {
              
              $url = "https://admin.synchronousdigital.com/synchtravelhotelapi/hotelbeds_app_api.php";
              $data = array('case' => 'cancellation_booking', 'refrence_id' => $refrence_id);
               Session()->put('cancell_booking_rq_m',$data);
              //print_r($data);die();
               $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $url);
              curl_setopt($ch, CURLOPT_POST, true);
              curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
              
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              $responseData = curl_exec($ch);
              //echo $responseData;die();
              if (curl_errno($ch)) {
                  return curl_error($ch);
              }
              curl_close($ch);
              return $responseData;
          }
  
          $responseData3 = hotel_reservation_cancel_fun($refrence_id);
           $hotel_cancelation=json_decode($responseData3);
           
           $cancell_booking_rq_m=Session()->get('cancell_booking_rq_m');
           $db_data = DB::table('hotel_provider_bookings')->where('invoice_no',$invoice_no)->update([
     
            'cancell_booking_rq' => json_encode($cancell_booking_rq_m),
            'cancell_booking_rs' => json_encode($hotel_cancelation),
            'booking_status' => 'Cancelled',
            
            
        ]);
           
           
            return response()->json(['message'=>'Cancelation SuccessFul','data'=>$hotel_cancelation]);
    }
    else
    {
     return response()->json(['message','Invalid Token']);   
    }
         
     }
 
 
 public function voucher($invoice_no)
 {
      $db_data = DB::table('hotel_provider_bookings')->where('invoice_no',$invoice_no)->get();
      
        return response()->json(['message'=>'SuccessFul','data'=>$db_data]);
 }  
}