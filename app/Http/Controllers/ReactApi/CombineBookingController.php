<?php

namespace App\Http\Controllers\ReactApi;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Session;
use App\Models\CustomerSubcription\CustomerSubcription;
use App\Models\booking_customers;
use Carbon\Carbon;
use Hash;

class CombineBookingController extends Controller
{
     
    
     public function combine_booking_apis_new(Request $request){
      
    
  
        
        $token_authorization=$request->token_authorization;
        $token=$token_authorization;
        $conversationId=$request->ConversationId;
        $lead_passenger_details=$request->lead_passenger_details;
        $other_passenger_details=$request->other_passenger_details;
        //dd($other_passenger_details);
        $child_details=$request->child_details;
        $infant_details=$request->infant_details;
        $extra_services_details=$request->extra_services_details;
        $other_extra_services_details=$request->other_extra_services_details;
        $child_extra_services_details=$request->child_extra_services_details;
        $revalidation_res=$request->revalidation_res;
        $adults=$request->adults;
        $childs=$request->childs;
        $infant=$request->infant;
        $departure_date=$request->departure_date;
        $adult_price=$request->adult_price;
        $child_price=$request->child_price;
        $infant_price=$request->infant_price;
        $total_price=$request->total_price;
        $adult_price_markup=$request->adult_price_markup;
        $child_price_markup=$request->child_price_markup;
        $infant_price_markup=$request->infant_price_markup;
        $total_price_markup=$request->total_price_markup;
        $client_commission_amount=$request->client_commission_amount;
        $admin_commission_amount=$request->admin_commission_amount;
        $client_payable_price=$request->client_payable_price;
        $currency=$request->currency;
        
        $client_markup=$request->client_markup;
        $client_markup_type=$request->client_markup_type;
        $client_commision_amount_exchange=$request->client_commision_amount_exchange;
        $client_without_markup_price=$request->client_without_markup_price;
        $client_markup_price=$request->client_markup_price;
        $client_payable_price_exchange=$request->client_payable_price_exchange;
        $client_currency=$request->client_currency;
        $admin_markup=$request->admin_markup;
        $admin_markup_type=$request->admin_markup_type;
        $admin_commision_amount_exchange=$request->admin_commision_amount_exchange;
        $admin_without_markup_price=$request->admin_without_markup_price;
        $admin_markup_price=$request->admin_markup_price;
        $admin_payable_price_exchange=$request->admin_payable_price_exchange;
        $admin_currency=$request->admin_currency;
        $creditAmount=$request->admin_currency;
        $hotel_checkout_select=$request->hotel_checkout_select;
        $hotel_customer_search_data=$request->hotel_customer_search_data;
         $customer_search_data=$hotel_customer_search_data;
        $lead_passenger_details_get=json_decode($lead_passenger_details);
        $other_passenger_details_get=json_decode($other_passenger_details,true);
        $invoiceId=$conversationId;
        
        
        
        
        //hotels
        $exchange_currency_customer=$request->exchange_currency_customer;
        $exchange_price= $request->exchange_price;
        $base_exchange_rate=$request->base_exchange_rate;
        $base_currency=$request->base_currency;
        $selected_exchange_rate=$request->selected_exchange_rate;
        $selected_currency=$request->selected_currency;
        $admin_exchange_currency=$request->admin_exchange_currency;
        $admin_exchange_rate=$request->admin_exchange_rate;
        $admin_exchange_total_markup_price=$request->admin_exchange_total_markup_price;
                                    
                                    //hotels
        
        
       

if(isset($other_passenger_details_get))
{
     $result_other_adult = [];
foreach ($other_passenger_details_get as $item) {
    foreach ($item as $key => $value) {
        if (!isset($result_other_adult[$key])) {
            $result_other_adult[$key] = [];
        }
        
        $result_other_adult[$key][] = $value;
    }
}
 $hotels_request_data=(object)[
        
        'lead_title'=>$lead_passenger_details_get->title,
        'lead_first_name'=>$lead_passenger_details_get->first_name,
        'lead_last_name'=>$lead_passenger_details_get->last_name,
        'lead_email'=>$lead_passenger_details_get->email,
        'lead_date_of_birth'=>$lead_passenger_details_get->date_of_birth,
        'lead_country'=>$lead_passenger_details_get->passenger_nationality_id,
        'lead_phone'=>$lead_passenger_details_get->passenger_phone_no,
        'other_title'=>$result_other_adult['other_title'] ?? '',
        'other_first_name'=>$result_other_adult['other_first_name'] ?? '',
        'other_last_name'=>$result_other_adult['other_last_name'] ?? '',
        'other_nationality'=>$result_other_adult['other_passport_country'] ?? '',
        'base_exchange_rate'=>'',
        'base_currency'=>'',
        'selected_exchange_rate'=>'',
        'selected_currency'=>'',
        'exchange_price'=>'',
        'admin_markup'=>'',
        'client_markup'=>'',
        'exchange_currency'=>'',
        
        ];
}

if(isset($lead_passenger_details_get))
{

    $hotels_request_data=(object)[
        
        'lead_title'=>$lead_passenger_details_get->title,
        'lead_first_name'=>$lead_passenger_details_get->first_name,
        'lead_last_name'=>$lead_passenger_details_get->last_name,
        'lead_email'=>$lead_passenger_details_get->email,
        'lead_date_of_birth'=>$lead_passenger_details_get->date_of_birth,
        'lead_country'=>$lead_passenger_details_get->passenger_nationality_id,
        'lead_phone'=>$lead_passenger_details_get->passenger_phone_no,
        'base_exchange_rate'=>'',
        'base_currency'=>'',
        'selected_exchange_rate'=>'',
        'selected_currency'=>'',
        'exchange_price'=>'',
        'admin_markup'=>'',
        'client_markup'=>'',
        'exchange_currency'=>'',
        
        ];   
}

        
    
    

                
          $setVariable='[null]';
                
              
              if($request->other_passenger_details != $setVariable)
               {
                $other_passenger_details=$request->other_passenger_details;
               }
               else
               {
                 $other_passenger_details='';  
               }
               if($request->child_details != $setVariable)
               {
                $child_details=$request->child_details;   
               }
               else
               {
                 $child_details='';  
               }
                
                if($request->infant_details != $setVariable)
               {
                $infant_details=$request->infant_details; 
               }
               else
               {
                 $infant_details='';  
               }
        
        // return response()->json(['data'=>$data]);
        
    /*
    |--------------------------------------------------------------------------
    | flight booking api curl started
    |--------------------------------------------------------------------------
    |
    */
          function bookflight($token_authorization,$conversationId,$revalidation_res,$lead_passenger_details,$other_passenger_details,$child_details,$infant_details,$extra_services_details,$other_extra_services_details,$child_extra_services_details)
          {
            $url = "https://api.synchtravel.com/synchtravelhotelapi/ReactApi/alhijaztoursflightapi.php";
            $data = array( 'case' => 'bookflight_new','token_authorization'=>$token_authorization,'ConversationId'=>$conversationId,'lead_passenger_details' =>$lead_passenger_details,'other_passenger_details' =>$other_passenger_details,'child_details' =>$child_details,
            'infant_details' =>$infant_details,'revalidation_res' =>$revalidation_res,'extra_services_details'=>$extra_services_details,'other_extra_services_details'=>$other_extra_services_details,'child_extra_services_details'=>$child_extra_services_details);
            
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
            return array('data' => $data, 'response' => $responseData);
        }
        $booking_data_and_response = bookflight($token_authorization,$conversationId,$revalidation_res,$lead_passenger_details,$other_passenger_details,$child_details,$infant_details,$extra_services_details,$other_extra_services_details,$child_extra_services_details);
        $booking_response = $booking_data_and_response['response'];
        $flight_booking_rs = json_decode($booking_response);
        $flight_booking_rq = $booking_data_and_response['data'];
    /*
    |--------------------------------------------------------------------------
    | flight booking api curl end
    |--------------------------------------------------------------------------
    |
    */
    
    /*
    |--------------------------------------------------------------------------
    | hotel booking api curl started
    |--------------------------------------------------------------------------
    |
    */
    
    
    $hotel_request_data=$hotels_request_data;
    $hotel_checkout_select=json_decode($hotel_checkout_select);
    $customer_search_data=json_decode($customer_search_data);
    
    $userData   = CustomerSubcription::where('Auth_key',$token)->select('id','status')->first();
                $booking_customer_id = "";
                $customer_exist = DB::table('booking_customers')->where('customer_id',$userData->id)->where('email',$hotel_request_data->lead_email)->first();
                if(isset($customer_exist) && $customer_exist != null && $customer_exist != ''){
                        $booking_customer_id = $customer_exist->id;
                }else{
                   
                   if($hotel_request_data->lead_title == "Mr"){
                       $gender = 'male';
                   }else{
                        $gender = 'female';
                   }
                    
                    $password = Hash::make('admin123');
                    if(!isset($request->booking_type) || $request->booking_type == 'b2c')
                    {
                    $customer_detail                    = new booking_customers();
                    $customer_detail->name              = $hotel_request_data->lead_first_name." ".$hotel_request_data->lead_last_name;
                    $customer_detail->opening_balance   = 0;
                    $customer_detail->balance           = 0;
                    $customer_detail->email             = $hotel_request_data->lead_email;
                    $customer_detail->password             = $password;
                    $customer_detail->phone             = $hotel_request_data->lead_phone;
                    $customer_detail->gender            = $gender;
                    $customer_detail->country           = $hotel_request_data->lead_country;
    
                    $customer_detail->customer_id       = $userData->id;
                    
                    $result = $customer_detail->save();
                    
                    $booking_customer_id = $customer_detail->id;
                    }
                    
                }
                
                
                 
if($hotel_checkout_select->hotel_provider  == 'hotel_beds'){
            
            $current_date = date('Y-m-d');
            $cancilation_date = date('Y-m-d',strtotime($hotel_checkout_select->rooms_list[0]->cancliation_policy_arr[0]->from_date ?? ''));
           
              $cancilation_date = Carbon::parse($cancilation_date);
             $current1 = Carbon::parse($current_date);
            if($cancilation_date > $current1){
               // Refundable Booking
               
                  function confirmbooking($hotel_request_data,$hotel_checkout_select){
                  $url = "https://api.synchtravel.com/synchtravelhotelapi/ReactApi/hotelbedsapi.php";
                  $data = array('case' => 'multi_rooms_confirmbooking','hotel_request_data' => json_encode($hotel_request_data),'hotel_checkout_select'=>json_encode($hotel_checkout_select));
                   $ch = curl_init();
                  curl_setopt($ch, CURLOPT_URL, $url);
                  curl_setopt($ch, CURLOPT_POST, true);
                  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                  
                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                  $responseData = curl_exec($ch);
                  if (curl_errno($ch)) {
                      return curl_error($ch);
                  }
                  curl_close($ch);
                  return $responseData;
              }
          
                  $responseData3 = confirmbooking($hotel_request_data,$hotel_checkout_select);
                  $responseData3 = json_decode($responseData3);
                  $hotel_request_send = json_decode($responseData3->request);
                  $result_booking_rs = json_decode($responseData3->response);
                  
                //   if($result_booking_rs->data->booking->status == 'CONFIRMED')
                //   {
                     
                //   }
                  
                //return response()->json(['data'=>$result_booking_rs]);
                //   $result_booking_rs = json_decode($hotel_response);
               
               
                  if(isset($result_booking_rs->booking)){
                      
                      $rooms_details_arr = [];
                      if(isset($result_booking_rs->booking->hotel->rooms)){
                          foreach($result_booking_rs->booking->hotel->rooms as $room_res){
                              
                              // Paxes Arr
                              $paxes_arr = [];
                              if(isset($room_res->paxes)){
                                  foreach($room_res->paxes as $pax_res){
                                      $type = '';
                                      if($pax_res->type == 'AD'){
                                          $type = 'Adult';
                                      }
                                      
                                      if($pax_res->type == 'CH'){
                                          $type = 'Child';
                                      }
                                      
                                      $paxes_arr[] = [
                                            'type' => $type,
                                            'name' => $pax_res->name." ".$pax_res->surname,
                                          ];
                                  }
                              }
                              
                              // Room Rates Arr
                              $room_rate_arr = [];
                              if(isset($room_res->rates)){
                                  foreach($room_res->rates as $rate_res){
                                      
                                        // Rooms Cancilation Policy
                                       $cancliation_policy_arr = [];
                                       if(isset($rate_res->cancellationPolicies)){
                                           foreach($rate_res->cancellationPolicies as $cancel_res){
                                               $cancel_tiem = (Object)[
                                                    'amount'=> $cancel_res->amount,
                                                    'from_date'=> $cancel_res->from,
                                                   ];
                                               $cancliation_policy_arr[] = $cancel_tiem;
                                           }
                                       }
                                      
                                      $room_rate_arr[] = (Object)[
                                            'rateClass' => $rate_res->rateClass,
                                            'net' => $rate_res->net,
                                            'rateComments' => $rate_res->rateComments ?? '',
                                            'room_board' => $rate_res->boardName,
                                            'room_qty' => $rate_res->rooms,
                                            'adults' => $rate_res->adults,
                                            'children' => $rate_res->children,
                                            'cancellation_policy' => $cancliation_policy_arr,
                                          ];
                                  }
                              }
                              
                              $rooms_details_arr[] = (Object)[
                                            'room_stutus' => $room_res->status,
                                            'room_code' => $room_res->code,
                                            'room_name' => $room_res->name,
                                            'room_paxes' => $paxes_arr,
                                            'room_rates' => $room_rate_arr,
                                          ];
                          }
                          
                      }
                      
                      $hotel_checkout_request_d = $hotel_checkout_select;
                        $hotel_booking_conf_res = (Object)[
                                'provider' => 'hotel_beds',
                                'admin_markup' => $hotel_checkout_request_d->admin_markup,
                                'customer_markup' => $hotel_checkout_request_d->customer_markup,
                                'admin_markup_type' => $hotel_checkout_request_d->admin_markup_type,
                                'customer_markup_type' => $hotel_checkout_request_d->customer_markup_type,
                                'reference_no' => $result_booking_rs->booking->reference,
                                'total_price' => $result_booking_rs->booking->totalNet,
                                'hotel_currency' => $result_booking_rs->booking->currency,
                                'clientReference' => $result_booking_rs->booking->clientReference,
                                'creationDate' => $result_booking_rs->booking->creationDate,
                                'status' => $result_booking_rs->booking->status,
                                'lead_passenger' => $result_booking_rs->booking->holder->name." ".$result_booking_rs->booking->holder->surname,
                                'status' => $result_booking_rs->booking->status,
                                'hotel_details' =>(Object)[
                                        'checkIn' => $result_booking_rs->booking->hotel->checkIn,
                                        'checkOut' => $result_booking_rs->booking->hotel->checkOut,
                                        'hotel_code' => $result_booking_rs->booking->hotel->code,
                                        'hotel_name' => $result_booking_rs->booking->hotel->name,
                                        'stars_rating' => $hotel_checkout_select->stars_rating,
                                        'destinationCode' => $result_booking_rs->booking->hotel->destinationCode,
                                        'destinationName' => $result_booking_rs->booking->hotel->destinationName,
                                        'latitude' => $result_booking_rs->booking->hotel->latitude,
                                        'longitude' => $result_booking_rs->booking->hotel->longitude,
                                        'rooms' => $rooms_details_arr
                                    ]
                            ];
                            
                            
                            
                            $customer_id = '';
                            $userData   = CustomerSubcription::where('Auth_key',$token)->select('id','status')->first();
                            if($userData){
                                $customer_id = $userData->id;
                            }
                            
                               $lead_passenger_object = (Object)[
                                    'lead_title' =>$hotel_request_data->lead_title,
                                    'lead_first_name' =>$hotel_request_data->lead_first_name,
                                    'lead_last_name' =>$hotel_request_data->lead_last_name,
                                    'lead_date_of_birth' =>date('d-m-Y',strtotime($hotel_request_data->lead_date_of_birth)),
                                    'lead_phone' =>$hotel_request_data->lead_phone,
                                    'lead_email' =>$hotel_request_data->lead_email,
                                    'lead_country' => $hotel_request_data->lead_country, 
                                ];
                                
                                $others_adults = [];
                                
                                if(isset($hotel_request_data->other_title)){
                                    foreach($hotel_request_data->other_title as $index => $other_res){
                                        $others_adults[] = (Object)[
                                                'title' => $other_res,
                                                'name' => $hotel_request_data->other_first_name[$index]." ".$hotel_request_data->other_last_name[$index],
                                                'nationality' => $hotel_request_data->other_nationality[$index],
                                            ];
                                    }
                                }
                                
                                $childs = [];
                                if(isset($hotel_request_data->child_title)){
                                    foreach($hotel_request_data->child_title as $index => $other_res){
                                        $childs[] = (Object)[
                                                'title' => $other_res,
                                                'name' => $hotel_request_data->child_first_name[$index]." ".$hotel_request_data->child_last_name[$index],
                                                'nationality' => $hotel_request_data->child_nationality[$index],
                                            ];
                                    }
                                }
                  }
                  
                  
                  
               
              
            }
            
            
            
            
            

        }
    
    /*
    |--------------------------------------------------------------------------
    | hotel booking api curl ended
    |--------------------------------------------------------------------------
    |
    */
        
   $trip_rq='';
   $trip_rs='';
if(isset($flight_booking_rs->Data->Status)){
if($flight_booking_rs->Data->Status == 'CONFIRMED'){
$UniqueID=$flight_booking_rs->Data->UniqueID;
                        function tripdetails($UniqueID,$token_authorization)
                        {
                            $url = "https://api.synchtravel.com/synchtravelhotelapi/ReactApi/alhijaztoursflightapi.php";
                            $data = array('case' => 'tripdetails', 'UniqueID' => $UniqueID,'token_authorization'=>$token_authorization);
                           
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
                            return array('data' => $data, 'response' => $responseData);
                        }
                        $tripdetails_res = tripdetails($UniqueID,$token_authorization);
                        
                         $trip_rq = $tripdetails_res['data'];
                         $trip_rs = $tripdetails_res['response'];
                         
                         
                         $flight_booking_status='Confirmed';
                         
                         $flight_trip_rq=json_encode($trip_rq);
                         $flight_trip_rs=$trip_rs;
                         
                         
}
else
{
    $flight_booking_status='Failed';
    $flight_trip_rq='';
    $flight_trip_rs='';
}
}
else
{
    $flight_booking_status='Failed';
    $flight_trip_rq='';
    $flight_trip_rs='';
}
                           
               
                
             
             
                   
                  $flight_bookings = DB::table('flight_bookings')->insert([
                    'auth_token'=>$token_authorization,
                    'invoice_no'=>$conversationId,
                    'adults'=>$adults,
                    'childs'=>0,
                    'infant'=>$infant,
                    'departure_date'=>$departure_date,
                    'lead_passenger_details'=>json_encode($lead_passenger_details),
                    'other_passenger_details'=>json_encode($other_passenger_details),
                    'child_details'=>'',
                    'infant_details'=>'',
                
                    'booking_rq'=>json_encode($flight_booking_rq),
                    'booking_rs'=>json_encode($flight_booking_rs),
                    'booking_detail_rq'=>$flight_trip_rq,
                    'booking_detail_rs'=>$flight_trip_rs,
                    'booking_status'=>$flight_booking_status,
                    'payment_details'=>'',
                    'adult_price'=>$adult_price,
                    'child_price'=>$child_price,
                    'infant_price'=>$infant_price,
                    'total_price'=>$total_price,
                    'adult_price_markup'=>$adult_price_markup,
                    'child_price_markup'=>$child_price_markup,
                    'infant_price_markup'=>$infant_price_markup,
                    'total_price_markup'=>$total_price_markup,
                    'client_commission_amount'=>$client_commission_amount,
                    'admin_commission_amount'=>$admin_commission_amount,
                    'client_payable_price'=>$client_payable_price,
                    'currency'=>'',
                    
                  ]);
                  
                 
                                     if(isset($flight_booking_rs->Data->Status)){
                                    if($flight_booking_rs->Data->Status == 'CONFIRMED'){
                                         $airline_markups = DB::table('airline_markups')->insert([

                                                'auth_token'=>$token_authorization,
                                                'invoice_no'=>$conversationId,
                                                'client_markup'=>$client_markup,
                                                'client_markup_type'=>$client_markup_type,
                                                'client_commision_amount_exchange'=>$client_commision_amount_exchange,
                                                'client_without_markup_price'=>$client_without_markup_price,
                                                'client_markup_price'=>$client_markup_price,
                                                'client_payable_price_exchange'=>$client_payable_price_exchange,
                                                'client_currency'=>$client_currency,
                                                'admin_markup'=>$admin_markup,
                                                'admin_markup_type'=>$admin_markup_type,
                                                'admin_commision_amount_exchange'=>$admin_commision_amount_exchange,
                                                'admin_without_markup_price'=>$admin_without_markup_price,
                                                'admin_markup_price'=>$admin_markup_price,
                                                'admin_payable_price_exchange'=>$admin_payable_price_exchange,
                                                'admin_currency'=>$admin_currency,
                                              ]);
                  
                  
        
                            
                      $customer_get_data = DB::table('customer_subcriptions')->where('Auth_key',$token_authorization)->first();
                            $credit_data = DB::table('credit_limits')->where('customer_id',$customer_get_data->id)->where('status_type','approved')->orderBy('id','DESC')->limit(1)->first();
                                         $ramainAmount=$credit_data->remaining_amount - $creditAmount;
                                         
                                        $credit_limits = DB::table('credit_limits')->insert([
                                            'transection_id'=>$conversationId,
                                            'customer_id'=>$customer_get_data->id,
                                                'amount'=>$creditAmount,
                                                'total_amount'=>$credit_data->total_amount,
                                                'remaining_amount'=>$ramainAmount,
                                                'currency'=>$credit_data->currency,
                                                'status'=>'1',
                                                'status_type'=>'approved',
                                                'services_type'=>'flight',
                                            
                                            ]);
                                        
                                      $credit_limit_transections = DB::table('credit_limit_transections')->insert([
                                        'invoice_no'=> $conversationId,
                                        'customer_id'=>$customer_get_data->id,
                                        'transection_amount'=>$creditAmount,
                                        'remaining_amount'=>$ramainAmount,
                                        'type'=>'booked',
                                        'services_type'=>'flight',
                                        ]);
                                        
                                        
                                        $get_ledger = DB::table('flight_customer_ledgers')->latest()->first();
                                        $balance_amount=$get_ledger->balance_amount ?? '0';
                                        $tAmount=$balance_amount + $client_payable_price_exchange;
                                        
                                        $flight_customer_ledgers = DB::table('flight_customer_ledgers')->insert([
                                        'token'=> $token_authorization,
                                        'invoice_no'=> $conversationId,
                                        'type'=>'flight_booking',
                                        'received_amount'=>round($client_payable_price_exchange,2),
                                        'balance_amount'=>round($tAmount,2),
                                        'status'=>'1',
                                        ]);
                                        
                                        
                                        $get_payment= DB::table('flight_payment_details')->where('auth_token',$token_authorization)->where('payment_status','1')->orderBy('id','DESC')->first();
                                        if(isset($get_payment))
                                        {
                                         $total_remain_am=$get_payment->payment_remaining_amount + $client_payable_price_exchange;
                                         $payment_paid_amount=$get_payment->payment_paid_amount;
                                         $payment_total_amount=$get_payment->payment_total_amount;
                                        }
                                        else
                                        {
                                         $total_remain_am=0;
                                         $payment_paid_amount=0;
                                         $payment_total_amount=0;
                                        }
                                    
                                        $flight_payment_details = DB::table('flight_payment_details')->insert([
                                        'auth_token'=> $token_authorization,
                                        'payment_transction_id'=> $conversationId,
                                        'type'=>'flight_booking',
                                        'payment_received_amount'=>round($client_payable_price_exchange,2),
                                        'payment_remaining_amount'=>round($total_remain_am,2),
                                        'payment_paid_amount'=>$payment_paid_amount,
                                        'payment_total_amount'=>$payment_total_amount,
                                        'payment_status'=>'1',
                                        ]);
                                        
                                    }
                                }
                 
                                        
                    //return response()->json(['data'=>$hotel_booking_conf_res]);                    
                   
                     if(isset($result_booking_rs->booking)){
                    $customer_get_data = DB::table('customer_subcriptions')->where('Auth_key',$token_authorization)->first();
                            $result = DB::table('hotels_bookings')->insert([
                                    'invoice_no' => $invoiceId,
                                    'provider' => $hotel_booking_conf_res->provider,
                                    'booking_customer_id' => $booking_customer_id,
                                    'exchange_currency' => $exchange_currency_customer,
                                    'exchange_price' => $exchange_price,
                                    'base_exchange_rate'=>$base_exchange_rate,
                                    'base_currency'=>$base_currency,
                                    'selected_exchange_rate'=>$selected_exchange_rate,
                                    'selected_currency'=>$selected_currency,
                                    'GBP_currency'=>$admin_exchange_currency,
                                    'GBP_exchange_rate'=>$admin_exchange_rate,
                                    'GBP_invoice_price'=>$admin_exchange_total_markup_price,
                                    'creationDate' => $hotel_booking_conf_res->creationDate,
                                    'status' => $hotel_booking_conf_res->status,
                                    'lead_passenger' => $hotel_booking_conf_res->lead_passenger,
                                    'lead_passenger_data' => json_encode($lead_passenger_object),
                                    'other_adults_data' => json_encode($others_adults),
                                    'childs_data' => json_encode($childs),
                                    'status' => $hotel_booking_conf_res->status,
                                    'total_adults' => '',
                                    'total_childs' => '',
                                    'total_rooms' => '',
                                    'reservation_request' => json_encode($hotel_request_send),
                                    'reservation_response' => json_encode($hotel_booking_conf_res),
                                    'actual_reservation_response' => json_encode($result_booking_rs),
                                    'customer_id' => $customer_get_data->id,
                                    'payment_details'=>'',
                                    'booking_type'=>'react'
                                ]);
                                
                                 //added code by jamshaid cheena 08-06-2023
                                    $client_markup=$request->client_markup;
                                    $admin_markup=$request->admin_markup;
                                    $client_markup_type=$request->client_markup_type;
                                    $admin_markup_type=$request->admin_markup_type;
                                    $payable_price=$request->payable_price;
                                    $client_commission_amount=$request->client_commission_amount;
                                    
                                    $total_markup_price=$request->total_markup_price;
                                    $currency=$request->currency;
                                    $exchange_client_commission_amount=$request->exchange_client_commission_amount;                                         
                                    $exchange_payable_price=$request->exchange_payable_price;                                          
                                    $exchange_admin_commission_amount=$request->admin_commission_amount; 
                                    $exchange_total_markup_price=$request->exchange_total_markup_price; 
                                    $exchange_currency=$request->exchange_currency; 
                                    $exchange_rate=$request->exchange_rate; 
                        
                                    $admin_exchange_amount=$request->admin_exchange_amount;
                                    $admin_commission_amount=$request->admin_commission_amount;
                                    $admin_exchange_currency=$request->admin_exchange_currency;
                                    $admin_exchange_rate=$request->admin_exchange_rate;
                                    $admin_exchange_total_markup_price=$request->admin_exchange_total_markup_price;
                        
                        
                                    $price=$request->exchange_price;
                                    $p_price=json_decode($price);
        

                                     $get_hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->orderBy('id','DESC')->where('token',$token)->limit(1)->first();
                                     $sum_hotel_customer_ledgers=$get_hotel_customer_ledgers->balance_amount ?? '0';
                                    //  $sum_hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->sum('balance_amount');
                                     $big_exchange_payable_price=(float)$sum_hotel_customer_ledgers + (float)$exchange_payable_price;
                                     //print_r($price_with_out_commission);die();
                                    $hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->insert([
                                        
                                        'token'=>$token,
                                        'invoice_no'=>$invoiceId,
                                        'received_amount'=>$exchange_payable_price,
                                        'balance_amount'=>$big_exchange_payable_price,
                                        'type'=>'hotel_booking'
                                        ]);
                                    
                                    
                                    
                                    $manage_customer_markups = DB::table('manage_customer_markups')->insert([
                                        
                                        'token'=>$token,
                                        'invoice_no'=>$invoiceId,
                                        'client_markup'=>$client_markup,
                                        'admin_markup'=>$admin_markup,
                                        'client_markup_type'=>$client_markup_type,
                                        'admin_markup_type'=>$admin_markup_type,
                                        'payable_price'=>$payable_price,
                                        'client_commission_amount'=>$client_commission_amount,
                                        'admin_commission_amount'=>$admin_commission_amount,
                                        'total_markup_price'=>$total_markup_price,
                                        'currency'=>$currency,
                                        
                                        'exchange_payable_price'=>$exchange_payable_price,
                                        'exchange_client_commission_amount'=>$exchange_client_commission_amount,
                                        'exchange_total_markup_price'=>$exchange_total_markup_price,
                                        'exchange_currency'=>$exchange_currency,
                                        'exchange_rate'=>$exchange_rate,
                                        
                                        'admin_exchange_amount'=>$admin_exchange_amount,
                                        'exchange_admin_commission_amount'=>$admin_commission_amount,
                                        'admin_exchange_currency'=>$admin_exchange_currency,
                                        'admin_exchange_rate'=>$admin_exchange_rate,
                                        'admin_exchange_total_markup_price'=>$admin_exchange_total_markup_price,
                                        
                                        ]);
                                        
                                        
                                        
                                        $admin_provider_payments_hotelbeds= DB::table('admin_provider_payments')->latest()->first();
                                        $payment_remaining_amount=$admin_provider_payments_hotelbeds->payment_remaining_amount ?? '0';
                                        $add_price=(float)$payment_remaining_amount + (float)$admin_exchange_total_markup_price;
                                        
                                        
                                        
                                        $admin_payments = DB::table('admin_provider_payments')->insert([
                                        
                                        'payment_transction_id'=> $invoiceId,
                                        'provider'=> 'hotelbeds',
                                        'payment_remaining_amount'=>$add_price,
                                        
                                        
                                        ]);
                                        
                                        
                                        
                                         $customer_get_data = DB::table('customer_subcriptions')->where('Auth_key',$token)->first();
                                        $credit_data = DB::table('credit_limits')->where('customer_id',$customer_get_data->id)->where('status_type','approved')->orderBy('id','DESC')->limit(1)->first();
                                         //print_r($credit_data);die();
                                         if(isset($credit_data))
                                         {
                                            $ramainAmount=$credit_data->remaining_amount - $request->creditAmount;
                                            $currency=$credit_data->currency;
                                         }
                                         else
                                         {
                                          $ramainAmount=$request->creditAmount;
                                           $currency='';
                                         }
                                        
                                         
                                        $credit_limits = DB::table('credit_limits')->insert([
                                            'transection_id'=>$invoiceId,
                                            'customer_id'=>$customer_get_data->id,
                                                'amount'=>$request->creditAmount,
                                                'total_amount'=>$credit_data->total_amount ?? '',
                                                'remaining_amount'=>$ramainAmount,
                                                'currency'=>$currency,
                                                'status'=>'1',
                                                'status_type'=>'approved',
                                            
                                            ]);
                                        
                                       $credit_limits = DB::table('credit_limit_transections')->insert([
                                        'invoice_no'=> $invoiceId,
                                        'customer_id'=>$customer_get_data->id,
                                        'transection_amount'=>$request->creditAmount,
                                        'remaining_amount'=>$ramainAmount,
                                        'type'=>'booked',
                                        ]);
                                        
                                        
                                        
                                   //ended code by jamshaid cheena 08-06-2023
                                   
                                   // Added Balance To Customer Ledger 
                                    $booking_customer_data = DB::table('booking_customers')->where('id',$booking_customer_id)->first();
                                    if($booking_customer_data){
                                        $customer_balance = $booking_customer_data->balance + $request->admin_exchange_total_markup_price;
                                    
                                        DB::table('booking_customers')->where('id',$booking_customer_id)->update([
                                                'balance' => $customer_balance
                                            ]);
                                            
                                        DB::table('customer_ledger')->insert([
                                                'received' => $request->admin_exchange_total_markup_price,
                                                'balance' => $customer_balance,
                                                'booking_customer' => $booking_customer_id,
                                                'hotel_invoice_no' => $invoiceId,
                                                'date' => date('Y-m-d'),
                                                'customer_id' => $userData->id,
                                            ]);
                                            
                                        if($request->slc_pyment_method == 'slc_stripe'){
                                            
                                            $customer_balance = $customer_balance - $request->admin_exchange_total_markup_price;
                                            DB::table('booking_customers')->where('id',$booking_customer_id)->update([
                                                'balance' => $customer_balance
                                            ]);
                                            
                                            DB::table('customer_ledger')->insert([
                                                    'payment' => $request->admin_exchange_total_markup_price,
                                                    'balance' => $customer_balance,
                                                    'booking_customer' => $booking_customer_id,
                                                    'hotel_invoice_no' => $invoiceId,
                                                    'payment_method' => 'Stripe',
                                                    'date' => date('Y-m-d'),
                                                    'customer_id' => $userData->id
                                                ]);
                                        }
                                    }
                                    
                                    
                                    
                                   
                                    return response()->json([
                                        'status' => 'success',
                                        'Invoice_id' => $invoiceId,
                                        
                                        
                                    ]);
                   } 
                   else
                   {
                       
                       $customer_get_data = DB::table('customer_subcriptions')->where('Auth_key',$token_authorization)->first();
                       $result = DB::table('hotels_bookings')->insert([
                                    'invoice_no' => $invoiceId,
                                    'provider' => 'hotelbeds',
                                    'booking_customer_id' => $booking_customer_id,
                                    'exchange_currency' => $exchange_currency_customer,
                                    'exchange_price' => $exchange_price,
                                    'base_exchange_rate'=>$base_exchange_rate,
                                    'base_currency'=>$base_currency,
                                    'selected_exchange_rate'=>$selected_exchange_rate,
                                    'selected_currency'=>$selected_currency,
                                    'GBP_currency'=>$admin_exchange_currency,
                                    'GBP_exchange_rate'=>$admin_exchange_rate,
                                    'GBP_invoice_price'=>$admin_exchange_total_markup_price,
                                    'creationDate' => '',
                                    'status' => 'Failed',
                                    'lead_passenger' => '',
                                    'lead_passenger_data' => json_encode($lead_passenger_details),
                                    'other_adults_data' => json_encode($other_passenger_details),
                                    'childs_data' => '',
                                    'status' => 'Failed',
                                    'total_adults' => '',
                                    'total_childs' => '',
                                    'total_rooms' => '',
                                    'reservation_request' => json_encode($hotel_request_send),
                                    'reservation_response' => json_encode($result_booking_rs),
                                    'actual_reservation_response' => json_encode($result_booking_rs),
                                    'customer_id' => $customer_get_data->id,
                                    'payment_details'=>'',
                                    'booking_type'=>'react'
                                ]);
                      
                                    return response()->json([
                                        'status' => 'Hotelbeds Api Error',
                                        'Errors' => $result_booking_rs,
                                        
                                    ]);
                   }
                   
                   
                                    
                         
                
                  
                  
                  
                  
                  
                  
                      
                         
                         

      
         

    }
    public function combine_invoice_apis_new(Request $request)
    {
        $invoice_no=$request->invoice_no;
         $combine_data = DB::table('flight_bookings')->join('hotels_bookings', 'flight_bookings.invoice_no', '=', 'hotels_bookings.invoice_no')
         ->where('flight_bookings.invoice_no',$invoice_no)
         ->where('hotels_bookings.invoice_no',$invoice_no)
         ->first();
         return response()->json(['invoice_data' => $combine_data]);
    }
    
}