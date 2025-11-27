<?php

namespace App\Http\Controllers\Flight;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class FlightReactController_Live extends Controller
{
/*
|--------------------------------------------------------------------------
| FlightReactController_Live Function Started
|--------------------------------------------------------------------------
*/ 

/*
|--------------------------------------------------------------------------
| Flight Function Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to get the response of Flight All List like Flight Type OneWay and Return.
*/       
    public function flight_search(Request $request){
        // return $request;
        if($request->AirTripType == 'OneWay'){
            $data = array(
                'case' => 'search_flights',
                'MaxStopsQuantity'=>$request->MaxStopsQuantity,
                'DepartureDate' => $request->DepartureDate,
                'DepartureCode' => $request->DepartureCode,
                'ArrivalCode' => $request->ArrivalCode,
                'AirlinesCode' => $request->AirlinesCode,
                'adult' =>$request->adult,
                'child' =>$request->child,
                'infant' =>$request->infant,
                'AirTripType' =>$request->AirTripType,
                'ConversationId' =>$request->ConversationId,
                'CabinType' =>$request->CabinType,
                'token_authorization'=>$request->token_authorization
            );
            // $data = $request->all();
            $url = "https://api.synchtravel.com/alhijaztoursflightapi_live.php";
            
            //   print_r($data);die();
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            $responseData = curl_exec($ch);
            // return $responseData;
            if (curl_errno($ch)) {
                return curl_error($ch);
            }
            curl_close($ch);
            return $responseData;
        }
        
        if($request->AirTripType == 'Return'){
            $data = array(
                'case' => 'return_search_flights',
                'MaxStopsQuantity'=>$request->MaxStopsQuantity,
                'DepartureDate' => $request->DepartureDate,
                'ReturnDepartureDate' => $request->return_date,
                'DepartureCode' => $request->DepartureCode,
                'ArrivalCode' => $request->ArrivalCode,
                'AirlinesCode' => $request->AirlinesCode,
                'adult' =>$request->adult,
                'child' =>$request->child,
                'infant' =>$request->infant,
                'AirTripType' =>$request->AirTripType,
                'ConversationId' =>$request->ConversationId,
                'CabinType' =>$request->CabinType,
                'token_authorization'=>$request->token_authorization
            );
            // $data = $request->all();
            $url = "https://api.synchtravel.com/alhijaztoursflightapi_live.php";
            
            // print_r($data);die();
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            $responseData = curl_exec($ch);
            //  echo $responseData;die();
            if (curl_errno($ch)) {
                return curl_error($ch);
            }
            curl_close($ch);
            return $responseData;   
        }
    }
/*
|--------------------------------------------------------------------------
| Flight Function Ended
|--------------------------------------------------------------------------
*/ 
/*
|--------------------------------------------------------------------------
| Flight Revalidated Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to get the response of Flight Revalidate in which FaresourceCode Is Required.
*/
public function flight_revalidation(Request $request){
        
       $FareSourceCode=$request->FareSourceCode;
       $token_authorization=$request->token_authorization;
       $conversationId=$request->ConversationId;    
   //print_r($FareSourceCode);die();
           
    function revalidation($conversationId,$FareSourceCode,$token_authorization)
        {
            $url = "https://api.synchtravel.com/alhijaztoursflightapi_live.php";
            $data = array('case' => 'revalidation', 'ConversationId' => $conversationId,'FareSourceCode' => $FareSourceCode,'token_authorization'=>$token_authorization);
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

        $responseData_revaldation = revalidation($conversationId,$FareSourceCode,$token_authorization);
       
        $result = json_decode($responseData_revaldation);
        //print_r($result);die();
        if(isset($result->Data->Errors))
        {
         if($result->Data->Errors == NULL)
        {
            $FareSourceCode_re=$result->Data->PricedItineraries[0]->AirItineraryPricingInfo->FareSourceCode;
          function flightfarerules($conversationId,$FareSourceCode_re,$token_authorization)
        {
          $url = "https://api.synchtravel.com/alhijaztoursflightapi_live.php";
            $data = array('case' => 'flightfarerules', 'ConversationId' => $conversationId,'FareSourceCode' => $FareSourceCode_re,'token_authorization'=>$token_authorization);
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

        $responseData3 = flightfarerules($conversationId,$FareSourceCode_re,$token_authorization);
        
        $flightfarerules = json_decode($responseData3);
        return $result;
            
           
        }
        else
        {
            return $result;
            
        }   
        }
        else
        {
          return $result;  
        }
        
        
    }
/*
|--------------------------------------------------------------------------
| Flight Revalidated Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to get the response of Flight Revalidate in which FaresourceCode Is Required.
*/    
public function flight_credit_limit(Request $request){
        //print_r($request->all());die();
        $token=$request->token;
       
        $customer= DB::table('customer_subcriptions')->where('Auth_key',$token)->first();
        $credit_data= DB::table('credit_limits')->orderBy('id','DESC')->where('customer_id',$customer->id)->where('status_type','approved')->limit(1)->first();
       return response()->json(['credit_data'=>$credit_data]); 
    }
/*
|--------------------------------------------------------------------------
| Flight Revalidated Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Flight Markup Started
|--------------------------------------------------------------------------
| In this Function, we coded the logic to get the Markup of Flight For Our Client.
*/  
public function get_markup_flight_new(Request $request){
     $token=$request->token;
     $customer= DB::table('customer_subcriptions')->where('Auth_key',$token)->first();
    $markups= DB::table('admin_markups')->where('customer_id',$customer->id)->where('status',1)->get();
    return response()->json(['markups'=>$markups]);
 }
/*
|--------------------------------------------------------------------------
| Flight Markup Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Flight Booking Confirm Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to Confirm The Flight Booking and gather essential customer details.
| Collect and validate customer details provided in the request and Save the confirmed booking and customer information.
*/
public function flight_booking_confirm(Request $request){
      //print_r($request->all()); die(); 
    
                $token_authorization=$request->token_authorization;
                $conversationId=$request->ConversationId;
                $lead_passenger_details=$request->lead_passenger_details;
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
                
                
                $extra_services_details=$request->extra_services_details;
                $other_extra_services_details=$request->other_extra_services_details;
                $child_extra_services_details=$request->child_extra_services_details;
                $revalidation_res=$request->revalidation_res;
        
          function bookflight($token_authorization,$conversationId,$revalidation_res,$lead_passenger_details,$other_passenger_details,$child_details,$infant_details,$extra_services_details,$other_extra_services_details,$child_extra_services_details)
                {
            $url = "https://api.synchtravel.com/alhijaztoursflightapi_live.php";
            $data = array(
                'case' => 'bookflight_new',
                'token_authorization'=>$token_authorization,
                'ConversationId'=>$conversationId,
                 'lead_passenger_details' =>$lead_passenger_details,
            'other_passenger_details' =>$other_passenger_details,
            'child_details' =>$child_details,
            'infant_details' =>$infant_details,
            'revalidation_res' =>$revalidation_res,
            'extra_services_details'=>$extra_services_details,
            'other_extra_services_details'=>$other_extra_services_details,
            'child_extra_services_details'=>$child_extra_services_details
            
            
            );
           
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
            return array('data' => $data, 'response' => $responseData);
        }
        $booking_data_and_response = bookflight($token_authorization,$conversationId,$revalidation_res,$lead_passenger_details,$other_passenger_details,$child_details,$infant_details,$extra_services_details,$other_extra_services_details,$child_extra_services_details);
        
        $booking_response = $booking_data_and_response['response'];
        //print_r($booking_response);die();
        $result = json_decode($booking_response);
        $booking_rq = $booking_data_and_response['data'];
        if(isset($result->Data->Status))
        {
        if($result->Data->Status == 'CONFIRMED')
        {
            

           
                 //$startTime = time();
                //$maxWaitTime = 120; // Maximum wait time in seconds
                //$conditionMet = false;
                //while (!$conditionMet && (time() - $startTime) < $maxWaitTime) {
                    // Check your data source or condition here
                    // If the condition is met, set $conditionMet to true
                    // For example: $conditionMet = checkDataCondition();
                    
                    // Simulating checking condition
                    //$conditionMet = true;
                    
                    // Sleep for a short time before checking again
                    //sleep(1); // You can adjust this delay as needed
                // }
                // if ($conditionMet) {
                // Delay for 120 seconds before running the function
                    // sleep(120);
        
                    // Run your function here
                                        // Your function code here
                                   $UniqueID=$result->Data->UniqueID;
                        function tripdetails($UniqueID,$token_authorization)
                        {
                            $url = "https://api.synchtravel.com/alhijaztoursflightapi_live.php";
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

                  $flight_bookings = DB::table('flight_bookings')->insert([
                    'auth_token'=>$token_authorization,
                    'invoice_no'=>$conversationId,
                    'adults'=>$request->adults,
                    'childs'=>$request->childs,
                    'infant'=>$request->infant,
                    'departure_date'=>$request->departure_date,
                    'lead_passenger_details'=>$lead_passenger_details,
                    'other_passenger_details'=>$other_passenger_details,
                    'child_details'=>$child_details,
                    'infant_details'=>$infant_details,
                    'search_rq'=>$request->search_rq,
                    'search_rs'=>$request->search_rs,
                    'farerules_rq'=>$request->farerules_rq,
                    'farerules_rs'=>$request->farerules_rs,
                    'revalidation_rq'=>$request->revalidation_rq,
                    'revalidation_rs'=>$request->revalidation_rs,
                    'booking_rq'=>json_encode($booking_rq),
                    'booking_rs'=>$booking_response,
                    'booking_detail_rq'=>json_encode($trip_rq),
                    'booking_detail_rs'=>$trip_rs,
                    'booking_status'=>'Confirmed',
                    'payment_details'=>$request->payment_details,
                    'adult_price'=>$request->adult_price,
                    'child_price'=>$request->child_price,
                    'infant_price'=>$request->infant_price,
                    'total_price'=>$request->total_price,
                    'adult_price_markup'=>$request->adult_price_markup,
                    'child_price_markup'=>$request->child_price_markup,
                    'infant_price_markup'=>$request->infant_price_markup,
                    'total_price_markup'=>$request->total_price_markup,
                    'client_commission_amount'=>$request->client_commission_amount,
                    'admin_commission_amount'=>$request->admin_commission_amount,
                    'client_payable_price'=>$request->client_payable_price,
                    'currency'=>$request->currency,
                    
                  ]);
                  
                  
                  
                  $airline_markups = DB::table('airline_markups')->insert([

                    'auth_token'=>$token_authorization,
                    'invoice_no'=>$conversationId,
                    'client_markup'=>$request->client_markup,
                    'client_markup_type'=>$request->client_markup_type,
                    'client_commision_amount_exchange'=>$request->client_commision_amount_exchange,
                    'client_without_markup_price'=>$request->client_without_markup_price,
                    'client_markup_price'=>$request->client_markup_price,
                    'client_payable_price_exchange'=>$request->client_payable_price_exchange,
                    'client_currency'=>$request->client_currency,
                    'admin_markup'=>$request->admin_markup,
                    'admin_markup_type'=>$request->admin_markup_type,
                    'admin_commision_amount_exchange'=>$request->admin_commision_amount_exchange,
                    'admin_without_markup_price'=>$request->admin_without_markup_price,
                    'admin_markup_price'=>$request->admin_markup_price,
                    'admin_payable_price_exchange'=>$request->admin_payable_price_exchange,
                    'admin_currency'=>$request->admin_currency,
                  ]);
                  
                  
        
                            
                            $customer_get_data = DB::table('customer_subcriptions')->where('Auth_key',$token_authorization)->first();
                            $credit_data = DB::table('credit_limits')->where('customer_id',$customer_get_data->id)->where('status_type','approved')->orderBy('id','DESC')->limit(1)->first();
                                         $ramainAmount=$credit_data->remaining_amount - $request->creditAmount;
                                         
                                        $credit_limits = DB::table('credit_limits')->insert([
                                            'transection_id'=>$conversationId,
                                            'customer_id'=>$customer_get_data->id,
                                                'amount'=>$request->creditAmount,
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
                                        'transection_amount'=>$request->creditAmount,
                                        'remaining_amount'=>$ramainAmount,
                                        'type'=>'booked',
                                        'services_type'=>'flight',
                                        ]);
                                        
                                        
                                        $get_ledger = DB::table('flight_customer_ledgers')->latest()->first();
                                        $balance_amount=$get_ledger->balance_amount ?? '0';
                                        $tAmount=$balance_amount + $request->client_payable_price_exchange;
                                        
                                        $flight_customer_ledgers = DB::table('flight_customer_ledgers')->insert([
                                        'token'=> $token_authorization,
                                        'invoice_no'=> $conversationId,
                                        'type'=>'flight_booking',
                                        'received_amount'=>round($request->client_payable_price_exchange,2),
                                        'balance_amount'=>round($tAmount,2),
                                        'status'=>'1',
                                        ]);
                                        
                                        
                                        $get_payment= DB::table('flight_payment_details')->where('auth_token',$token_authorization)->where('payment_status','1')->orderBy('id','DESC')->first();
                                        if(isset($get_payment))
                                        {
                                         $total_remain_am=$get_payment->payment_remaining_amount + $request->client_payable_price_exchange;
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
                                        'payment_received_amount'=>round($request->client_payable_price_exchange,2),
                                        'payment_remaining_amount'=>round($total_remain_am,2),
                                        'payment_paid_amount'=>$payment_paid_amount,
                                        'payment_total_amount'=>$payment_total_amount,
                                        'payment_status'=>'1',
                                        ]);
                                        
                   
                          
                   
                   return response()->json(
                       [
                           'message'=>'success',
                           'booking_response'=>$booking_response,
                        //   'flight_bookings'=>$flight_bookings,
                        //   'airline_markups'=>$airline_markups,
                        //   'credit_limits'=>$credit_limits,
                        //   'credit_limit_transections'=>$credit_limit_transections,
                        //   'flight_customer_ledgers'=>$flight_customer_ledgers,
                        //   'flight_payment_details'=>$flight_payment_details
                           ]); 
                         
                    //end DB store//
                    
                // } else {
                //     return redirect()->back()->with('message','Condition was not met within the specified time.');
                   
                // }
        
                   
             
        
        //stripe code endded
        }
        else
        {
          return response()->json(['error'=> $booking_response]);   
        }
        }
        else
        {
            return response()->json(['message'=>'SomeThing Error,Please Again Search Flight','error'=>$booking_response]);  
         
        }
         

    }
/*
|--------------------------------------------------------------------------
| Flight Booking Confirm Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Flight Invoice Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to get Flight Invoice Details form given the booking invoice Number.
*/
    public function flight_invoice_new(Request $request){
        $invoice_no             = $request->invoice_no;
        $token_authorization    = $request->token_authorization;
        $data                   = DB::table('flight_bookings')->where('invoice_no',$invoice_no)->first();
        
        // return $data;
        
        $booking_rs             = json_decode($data->booking_rs);
        $UniqueID               = $booking_rs->Data->UniqueID;
        
        function ViewReservation($UniqueID,$token_authorization){
            $url    = "https://api.synchtravel.com/alhijaztoursflightapi_live.php";
            $data   = array('case' => 'ViewReservationflight','UniqueID' => $UniqueID,'token_authorization'=>$token_authorization);
            $ch     = curl_init();
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
        $booking_res                = ViewReservation($UniqueID,$token_authorization);
        $result_view_reservation    = json_decode($booking_res);
        
        return response()->json(['booking_data'=>$data,'result_view_reservation'=>$result_view_reservation]);
    }
 
/*
|--------------------------------------------------------------------------
| Flight Invoice Ended
|--------------------------------------------------------------------------
*/ 
 
 
 
 
/*
|--------------------------------------------------------------------------
| FlightReactController Function Ended
|--------------------------------------------------------------------------
*/ 
}