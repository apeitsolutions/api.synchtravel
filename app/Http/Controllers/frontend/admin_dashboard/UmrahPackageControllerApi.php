<?php

namespace App\Http\Controllers\frontend\admin_dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UmrahPackage;
use App\Models\Tour;
use App\Models\alhijaz_Notofication;
use App\Models\country;
use App\Models\view_booking_payment_recieve;
use App\Models\view_booking_payment_recieve_Activity;
use Auth;
use DB;
use Carbon\Carbon;

class UmrahPackageControllerApi extends Controller
{
    
    
    public function get_hotelbeds_token()
    {
        
        
        // $lastUpdateTime=date('Y-m-d H:i:s', time());
        // echo $lastUpdateTime;
        $api_key = '833583586f0fd4fccd3757cd8a57c0a8';
          $secret = 'ae2dc887d0';
          $lastUpdateTime = date('Y-m-d H:i:s', time());
          $plus = '+';
          
          
          $values = $api_key.$secret.$lastUpdateTime;
          
           $token = hash('SHA256', $values);
        //   $token = base64_encode($token);
            echo $token;die();
        
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.test.hotelbeds.com/hotel-api/1.0/status',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Api-key: 833583586f0fd4fccd3757cd8a57c0a8',
    'X-Signature: 5fceb8ee1547522620a67a2d3a38a6970049e8fe6e4d92f1bb54ee8f67c81e3a',
    'Accept: application/json',
    'Accept-Encoding: gzip'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
// $res=json_decode($response);
// print_r($res->auditData->timestamp);
      die();  
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
      
          
          
         $token = hash('SHA256', $values);
         
         echo 
          print_r($token);die();
        //  $tokendata = array('token' => $token);
        
        
          echo json_encode($tokendata);
  


$create_his_signature     = $api_key . $secret;
$enigma = hash('sha256', $create_his_signature, $lastUpdateTime);
echo $enigma;
          
          
          
          
    die();      
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          $signature = hash('sha256', $api_key, $secret, true);
        //   $hashedPassword = custom_hmac('sha256', $api_key,$secret,$lastUpdateTime, true);
          
            print_r($signature);die();
          $curl = curl_init();
    
          $data = array('Api-key'=>$api_key,'secret'=>$secret,'lastUpdateTime'=>$lastUpdateTime);
        // print_r($data);die();
          curl_setopt_array($curl, array(
             CURLOPT_URL => 'https://api.test.hotelbeds.com',
             CURLOPT_RETURNTRANSFER => true,
             CURLOPT_ENCODING => '',
             CURLOPT_MAXREDIRS => 10,
             CURLOPT_TIMEOUT => 0,
             CURLOPT_FOLLOWLOCATION => true,
             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
             CURLOPT_CUSTOMREQUEST => 'POST',
             CURLOPT_POSTFIELDS =>  $data,
             CURLOPT_HTTPHEADER => array(
                 'Cookie: laravel_session=gnq21xtzzbBtgOgSa0iVWPIX9vSDzHcKrUozAnSL'
             ),
          ));
    
          $response = curl_exec($curl);
          echo $response;
          curl_close($curl);
          
      
   }
    
    
    
    // Tour Bookings
        public static function get_Tour_Bookings_Season_Working($all_data,$request){
            $today_Date             = date('Y-m-d');
            if(isset($request->season_Id)){
                if($request->season_Id == 'all_Seasons'){
                    return $all_data;
                }elseif($request->season_Id > 0){
                    $season_Details = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->where('id', $request->season_Id)->first();
                }else{
                    return $all_data;
                }
            }else{
                $season_Details     = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
            }
            
            if($season_Details != null){
                $start_Date         = $season_Details->start_Date;
                $end_Date           = $season_Details->end_Date;
                
                $filtered_data      = collect($all_data)->filter(function ($record) use ($start_Date, $end_Date) {
                    if (!isset($record->created_at)) {
                        return false;
                    }
                    
                    if($record->created_at != null && $record->created_at != ''){
                        $created_at     = Carbon::parse($record->created_at);
                        $start_Date     = Carbon::parse($start_Date);
                        $end_Date       = Carbon::parse($end_Date);
                        return $created_at->between($start_Date, $end_Date) || ($created_at->lte($start_Date) && $created_at->gte($end_Date));
                    }else{
                        return false;
                    }
                });
                
                $all_data = $filtered_data;
            }
            return $all_data;
        }
        
        public function view_ternary_bookings(Request $req){
            $customer_id = $req->customer_id;
            if(isset($req->SU_id) && $req->SU_id != null && $req->SU_id != ''){
                $data1  =   DB::table('tours_bookings')->where('tours_bookings.customer_id',$customer_id)->where('tours_bookings.SU_id',$req->SU_id)
                                ->join('customer_subcriptions','tours_bookings.customer_id','customer_subcriptions.id')
                                ->join('cart_details','tours_bookings.id','cart_details.booking_id')->where('pakage_type','tour')
                                ->orderBy('tours_bookings.id', 'DESC')->get();
            }else{
                $data1  =   DB::table('tours_bookings')->where('customer_id',$customer_id)
                                ->join('customer_subcriptions','tours_bookings.customer_id','customer_subcriptions.id')
                                ->join('cart_details','tours_bookings.id','cart_details.booking_id')->where('pakage_type','tour')
                                ->orderBy('tours_bookings.id', 'DESC')->get();
            }
            
            // Season
            // dd($req->season_Id);
            $season_Id              = '';
            $today_Date             = date('Y-m-d');
            if(isset($req->season_Id) && $req->season_Id == 'all_Seasons'){
                // dd('IF');
                $season_Id          = 'all_Seasons';
            }elseif(isset($req->season_Id) && $req->season_Id > 0){
                // dd('IF 2');
                $season_SD          = DB::table('add_Seasons')->where('customer_id', $req->customer_id)->where('id', $req->season_Id)->first();
                $season_Id          = $season_SD->id ?? '';
            }else{
                // dd('else');
                $season_SD          = DB::table('add_Seasons')->where('customer_id', $req->customer_id)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
                $season_Id          = $season_SD->id ?? '';
            }
            
            // dd($season_Id);
            
            $season_Details         = DB::table('add_Seasons')->where('customer_id', $req->customer_id)->get();
            if($req->customer_id == 4 || $req->customer_id == 54){
                if($data1->isEmpty()){
                }else{
                    // dd($data1);
                    $data1   = $this->get_Tour_Bookings_Season_Working($data1,$req);
                    // dd($data1);
                }
            }
            // Season
            
            $agentData = DB::table('Agents_detail')->where('customer_id', $req->customer_id)->get();
            
            return response()->json([
                'data1'             => $data1,
                'season_Details'    => $season_Details,
                'season_Id'         => $season_Id,
                'agentData'         => $agentData,
            ]);
        }
        
        public function view_ternary_bookings_tourId(Request $req){
            DB::beginTransaction();
            try {
                $a          = DB::table('view_booking_payment_recieve')->where('package_id',$req->P_Id)->orderBy('id', 'DESC')->get();
                $recieved   = DB::table('view_booking_payment_recieve')->where('package_id',$req->P_Id)->sum('recieved_amount');
                $price      = DB::table('cart_details')->where('booking_id',$req->P_Id)->where('pakage_type','tour')->sum('price');
                return response()->json([
                    'message'       => 'success',
                    'a'             => $a,
                    'recieved'      => $recieved,
                    'price'         => $price,
                ]);
            } catch (Throwable $e) {
                DB::rollback();
                return response()->json(['message'=>'error']);
            }
        }
        
        public function view_confirmed_bookings(Request $req){
            $customer_id = $req->customer_id;
            $data = DB::table('tours_bookings')->where('customer_id',$customer_id)
                               ->join('customer_subcriptions','tours_bookings.customer_id','customer_subcriptions.id')
                               ->join('cart_details','tours_bookings.id','cart_details.booking_id')
                               ->where('pakage_type','tour')
                               ->orderBy('tours_bookings.id', 'DESC')->get();
        
            $data1 = DB::table('booking_users')->join('tours_bookings','booking_users.id','tours_bookings.customer_id')->get();
            return response()->json([
                'data'  => $data,
                'data1' => $data1,
            ]); 
        }
        
        public function view_booking_payment(Request $req){
            DB::beginTransaction();
            try {
                $P_Id                   = $req->P_Id;
                $data1                  = DB::table('tours_bookings')->find($req->id);
                $decode_data1           = json_decode($data1->cart_details);
                $cart_details1          = DB::table('cart_details')->where('id',$req->cartD_ID)->first();
                $decode_customer_data1  = DB::table('customer_subcriptions')->where('id',$req->customer_id)->first();
                $amount_paid            = DB::table('view_booking_payment_recieve')->where('package_id',$req->id)->sum('amount_paid');
                $total_Amount           = DB::table('cart_details')->where('id',$req->cartD_ID)->first();
                return response()->json([
                    'message'               => 'success',
                    'data1'                 => $data1,
                    'decode_customer_data1' => $decode_customer_data1,
                    'cart_details1'         => $cart_details1,
                    'amount_paid'           => $amount_paid,
                    'total_Amount'          => $total_Amount
                ]);
            } catch (Throwable $e) {
                DB::rollback();
                return response()->json(['message'=>'error']);
            }
        }
        
       public function view_booking_payment_recieve(Request $req){
            
            DB::beginTransaction();

            try {
                $cart_details1      = DB::table('cart_details')->where('booking_id',$req->package_id)->first();
        
                $total_amount       = $cart_details1->tour_total_price;
                $paid_amount        = $cart_details1->total_paid_amount;
                $over_paid_amount   = $cart_details1->over_paid_amount;
                
                $total_paid_amount  = $paid_amount + $req->recieved_amount;
                $total_over_paid    = 0;
                $over_paid_am       = 0;
                if($total_paid_amount > $total_amount){
                    $over_paid_am = $total_paid_amount - $total_amount;
                    $total_over_paid = $over_paid_amount + $over_paid_am;
                    
                    $total_paid_amount = $total_paid_amount - $over_paid_am;
                }
                
                DB::table('cart_details')->where('booking_id',$req->package_id)->update([
                    'total_paid_amount' => $total_paid_amount,
                    'over_paid_amount'  => $total_over_paid,
                ]);
                
                $agent_over_paid = 0;
                if($cart_details1->agent_name != '-1'){
                    $agent_data = DB::table('Agents_detail')->where('id',$cart_details1->agent_name)->select('id','over_paid_am')->first();
                    $agent_over_paid = $agent_data->over_paid_am + $over_paid_am;
                    DB::table('Agents_detail')->where('id',$agent_data->id)->update(['over_paid_am'=>$agent_over_paid]);
                }
                
                $insert = new view_booking_payment_recieve();
                $insert->customer_id      = $req->customer_id;
                $insert->package_id       = $req->package_id;
                $insert->tourId           = $req->tourId;
                $insert->date             = $req->date;
                $insert->customer_name    = $req->customer_name;
                $insert->package_title    = $req->package_title;
                $insert->recieved_amount  = $req->recieved_amount;
                $insert->total_amount     = $req->total_amount;
                $insert->remaining_amount = $req->remaining_amount;
                $insert->amount_paid      = $req->amount_paid;
                $insert->save();
                
                $generate_id = $req->invoice_no.'/'.$req->package_id.'/'.$req->tourId;
                $notification_insert = new alhijaz_Notofication();
                $notification_insert->type_of_notification_id   = $insert->id ?? '';
                $notification_insert->customer_id               = $insert->customer_id ?? '';
                $notification_insert->type_of_notification      = 'package_Payment' ?? '';
                $notification_insert->generate_id               = $generate_id ?? '';
                $notification_insert->notification_creator_name = 'AlhijazTours' ?? '';
                $notification_insert->total_price               = $insert->total_amount ?? '';
                $notification_insert->amount_paid               = $insert->amount_paid ?? '';
                $notification_insert->remaining_price           = $insert->remaining_amount ?? '';
                $notification_insert->notification_status       = '1' ?? '';
                $notification_insert->save();
                
                if($cart_details1->agent_name != '-1'){
                    DB::table('agents_ledgers')->insert([
                        'agent_id'=>$agent_data->id,
                        'payment_id'=>$insert->id,
                        "payment"=>$req->recieved_amount,
                        "package_invoice_no"=>$cart_details1->invoice_no,
                        "total_amount"=>$total_amount,
                        "paid_amount"=>$total_paid_amount,
                        "remaining_amount"=>$total_amount - $total_paid_amount,
                        'over_paid'=>$agent_over_paid,
                        'date'=>$req->date,
                    ]);
                }
                
                if($cart_details1->agent_name != '-1'){
                    DB::table('agents_ledgers')->insert([
                    'agent_id'=>$agent_data->id,
                    'payment_id'=>$insert->id,
                    "payment"=>$req->recieved_amount,
                    "package_invoice_no"=>$cart_details1->invoice_no,
                    "total_amount"=>$total_amount,
                    "paid_amount"=>$total_paid_amount,
                    "remaining_amount"=>$total_amount - $total_paid_amount,
                    'over_paid'=>$agent_over_paid,
                    'date'=>$req->date,
                    ]);
                 }

                
                DB::commit();
            
                return response()->json([
                    'message' => 'Success',
                ]);
                    
            } catch (\Exception $e) {
                DB::rollback();
                echo $e;
            }
        }
        
        public function view_booking_detail(Request $req){
          $P_Id = $req->P_Id;
          $data1 = DB::table('tours_bookings')->find($req->id);
          $decode_data1 = json_decode($data1->cart_details);
          // $cart_details1 = $decode_data1->$tourId;
          $cart_details1 = DB::table('cart_details')->where('id',$P_Id)->first();
          $decode_customer_data1 = DB::table('customer_subcriptions')->where('id',$req->customer_id)->first();
          // $decode_customer_data1 = json_decode($customer_data1);
          return response()->json([
             'data1' => $data1,
             'decode_customer_data1' => $decode_customer_data1,
             'cart_details1' => $cart_details1,
          ]);
        }
        
        
        
        public function view_booking_customer_details(Request $req){
            DB::beginTransaction();
            try {
                $data1 = DB::table('tours_bookings')->where('id',$req->id)->first();
                return response()->json([
                    'message'   => 'success',
                    'data1'     => $data1,
                ]);
            } catch (Throwable $e) {
                DB::rollback();
                return response()->json(['message'=>'error']);
            }
        }

        public function confirmed_tour_booking(Request $req){
            DB::beginTransaction();
            try {
                $data = DB::table('cart_details')->where('booking_id',$req->id)->update([
                    'confirm' => '1',
                ]);
                
                DB::commit();
                
                return response()->json([
                    'message'   => 'success',
                    'data'      => $data,
                ]);
            } catch (Throwable $e) {
                DB::rollback();
                return response()->json(['message'=>'error']);
            }
        }
        
        public static function get_Tours_Season_Working($all_data,$request){
            $today_Date             = date('Y-m-d');
            if(isset($request->season_Id)){
                if($request->season_Id == 'all_Seasons'){
                    return $all_data;
                }elseif($request->season_Id > 0){
                    $season_Details = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->where('id', $request->season_Id)->first();
                }else{
                    return $all_data;
                }
            }else{
                $season_Details     = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
            }
            
            if($season_Details != null){
                $start_Date         = $season_Details->start_Date;
                $end_Date           = $season_Details->end_Date;
                
                $filtered_data      = collect($all_data)->filter(function ($record) use ($start_Date, $end_Date) {
                    if (!isset($record->created_at)) {
                        return false;
                    }
                    
                    if($record->created_at != null && $record->created_at != ''){
                        $created_at     = Carbon::parse($record->created_at);
                        $start_Date     = Carbon::parse($start_Date);
                        $end_Date       = Carbon::parse($end_Date);
                        return $created_at->between($start_Date, $end_Date) || ($created_at->lte($start_Date) && $created_at->gte($end_Date));
                    }else{
                        return false;
                    }
                });
                
                $all_data = $filtered_data;
            }
            return $all_data;
        }
        
        public function get_tour_list_api(Request $request){
            $pakage_type    = 'tour';
            $customer_id    = $request->customer_id;
            $tours          = DB::table('tours')
                                ->join('tours_2','tours.id','tours_2.tour_id')
                                ->where('tours.customer_id',$customer_id)
                                ->orderBy('tours.id', 'DESC')
                                ->get();
                                
            $data1          = DB::table('cart_details')->where('pakage_type',$pakage_type)->orderBy('cart_details.id', 'DESC')->get();
            $booking_Id     = DB::table('tours')
                                ->join('tours_2','tours.id','tours_2.tour_id')
                                ->join('cart_details','tours.id','cart_details.tour_id')
                                ->where('tours.customer_id',$customer_id)
                                ->select('cart_details.tour_id','cart_details.booking_id','cart_details.created_at')
                                ->orderBy('tours.id', 'DESC')
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
            $season_Details         = DB::table('add_Seasons')->where('customer_id', $request->customer_id)->get();
            if($request->customer_id == 4 || $request->customer_id == 54){
                if($tours->isEmpty()){
                }else{
                    // dd($tours);
                    $tours      = $this->get_Tours_Season_Working($tours,$request);
                    // dd($tours);
                }
                
                if(empty($data1)){
                }else{
                    // dd($data1);
                    $data1      = $this->get_Tours_Season_Working($data1,$request);
                    // dd($data1);
                }
                
                if(empty($booking_Id)){
                }else{
                    // dd($booking_Id);
                    $booking_Id = $this->get_Tours_Season_Working($booking_Id,$request);
                    // dd($booking_Id);
                }
            }
            // Season
            
            return response()->json([
                'message'           => 'success',
                'tours'             => $tours,
                'data1'             => $data1,
                'booking_Id'        => $booking_Id,
                'season_Details'    => $season_Details,
                'season_Id'         => $season_Id,
            ]);
        }
        
    //  END Tour Bookings 
    
    // Activity Bookings
        public function view_ternary_bookings_Activity(Request $request){
            $data1 = DB::table('tours_bookings')
                               ->join('customer_subcriptions','tours_bookings.customer_id','customer_subcriptions.id')
                               ->join('cart_details','tours_bookings.id','cart_details.booking_id')
                               ->where('pakage_type','activity')
                               ->where('customer_id',$request->customer_id)
                               ->orderBy('tours_bookings.id', 'DESC')
                               ->get();
            return response()->json([
                'data1' => $data1,
            ]);
        }
        
        
        public function view_ternary_bookings_tourId_Activity(Request $req){
            $a        = view_booking_payment_recieve_Activity::where('package_id',$req->P_Id)->get();
            $recieved = DB::table('view_booking_payment_recieve_Activity')->where('package_id',$req->P_Id)->sum('recieved_amount');
            $price    = DB::table('cart_details')->where('id',$req->P_Id)->sum('price');
            return response()->json([
                'a'        => $a,
                'recieved' => $recieved,
                'price'    => $price,
            ]);
        }
        
        public function view_confirmed_bookings_Activity(){
          $data = DB::table('tours_bookings')
                               ->join('customer_subcriptions','tours_bookings.customer_id','customer_subcriptions.id')
                               ->join('cart_details','tours_bookings.id','cart_details.booking_id')
                               ->get();
        
          $data1 = DB::table('booking_users')->join('tours_bookings','booking_users.id','tours_bookings.customer_id')->get();
          return response()->json([
             'data'  => $data,
             'data1' => $data1,
          ]);
        }
        
        public function view_booking_payment_Activity(Request $req){
          $P_Id = $req->P_Id;
          $data1           = DB::table('tours_bookings')->find($req->id);
          $decode_data1    = json_decode($data1->cart_details);
          $cart_details1   = DB::table('cart_details')->where('id',$P_Id)->first();
          $decode_customer_data1  = DB::table('customer_subcriptions')->where('id',$req->customer_id)->first();
          // $decode_customer_data1 = json_decode($customer_data1);
          $amount_paid           = DB::table('view_booking_payment_recieve_Activity')->where('package_id',$P_Id)->sum('amount_paid');
          return response()->json([
             'data1' => $data1,
             'decode_customer_data1' => $decode_customer_data1,
             'cart_details1' => $cart_details1,
             'amount_paid' => $amount_paid,
          ]);
        }
        
        public function view_booking_payment_recieve_Activity(Request $req){
          $insert = new view_booking_payment_recieve_Activity();
          $insert->package_id       = $req->package_id;
          $insert->tourId           = $req->tourId;
          $insert->date             = $req->date;
          $insert->customer_name    = $req->customer_name;
          $insert->package_title    = $req->package_title;
          $insert->recieved_amount  = $req->recieved_amount;
          $insert->total_amount     = $req->total_amount;
          $insert->remaining_amount = $req->remaining_amount;
          $insert->amount_paid      = $req->amount_paid;
          $insert->save();
        
          return response()->json([
             'message' => 'Success',
          ]);
        }
        
        public function view_booking_detail_Activity(Request $req){
          $P_Id = $req->P_Id;
          $data1 = DB::table('tours_bookings')->find($req->id);
          $decode_data1 = json_decode($data1->cart_details);
          // $cart_details1 = $decode_data1->$tourId;
          $cart_details1 = DB::table('cart_details')->where('id',$P_Id)->first();
          $decode_customer_data1 = DB::table('customer_subcriptions')->where('id',$req->customer_id)->first();
          // $decode_customer_data1 = json_decode($customer_data1);
          return response()->json([
             'data1' => $data1,
             'decode_customer_data1' => $decode_customer_data1,
             'cart_details1' => $cart_details1,
          ]);
        }
        
        public function view_booking_customer_details_Activity(Request $req){
            // $data1 = DB::table('customer_subcriptions')->where('id',$req->customer_id)->first();
            $data1 = DB::table('tours_bookings')->where('id',$req->id)->first();
            return response()->json([
                'data1' => $data1,
            ]);
   }

        public function confirmed_tour_booking_Activity(Request $req){
            $data = DB::table('cart_details')->where('id',$req->id)->update([
                'confirm' => '1',
            ]);
            return response()->json([
                'data'  => $data,
            ]);
   }
    //  END Activity Bookings
    public function view_track_id_booking_hotel(Request $request){
     $view_booking=DB::table('hotel_booking')->where('search_id',$request->search_id)->first();
      return response()->json([
         'view_booking' => $view_booking,
      ]);
   }
    
    
}
