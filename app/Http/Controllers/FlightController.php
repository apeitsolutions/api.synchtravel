<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UmrahPackage;
use App\Models\Tour;
use App\Models\country;
use App\Models\CustomerSubcription;
use App\Models\view_booking_payment_recieve;
use App\Models\view_booking_payment_recieve_Activity;
use DateTime;
use Auth;
use DB;
use Carbon\Carbon;

class FlightController extends Controller
{
    public function flights_arrival_list(Request $request){
        $userData                   = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->select('id','status')->first();
        if($userData->id == 4){
            // Season
            $today_Date             = date('Y-m-d');
            $season_Id              = '';
            if(isset($request->season_Id) && $request->season_Id == 'all_Seasons'){
                $season_Id          = 'all_Seasons';
            }elseif(isset($request->season_Id) && $request->season_Id > 0){
                $season_SD          = DB::table('add_Seasons')->where('token', $request->token)->where('id', $request->season_Id)->first();
                $season_Id          = $season_SD->id ?? '';
            }else{
                $season_SD          = DB::table('add_Seasons')->where('token', $request->token)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
                $season_Id          = $season_SD->id ?? '';
            }
            $season_Details         = DB::table('add_Seasons')->where('token', $request->token)->get();
            
            return response()->json([
                'season_Details'    => $season_Details,
                'season_Id'         => $season_Id
            ]);
        }
    }
    
    public function flights_arrival_list_sub_new(Request $request){
        if($request->report_type == 'all_data'){
            $all_flight = DB::table('flight_bookings')->where('auth_token',$request->token)->orderBy('departure_date','ASC')->get(); 
            $aray_data  = array();
            foreach($all_flight as $flight){
                if($flight->booking_status == 'Confirmed'){
                    $booking_rs             = json_decode($flight->booking_rs);
                    if(isset($flight->booking_detail_rs) && $flight->booking_detail_rs != NULL && $flight->booking_detail_rs != 'null' && $flight->booking_detail_rs != ''){
                        $booking_detail_rs  = json_decode($flight->booking_detail_rs);
                        foreach($booking_detail_rs->Data->TripDetailsResult->TravelItinerary->Itineraries as $Itineraries){
                            $aray_data[]    = (object)[
                                'invoice_no'=> $flight->invoice_no,
                                'UniqueID'=> $booking_rs->Data->UniqueID,
                                'adults'=>$flight->adults,
                                'childs'=>$flight->childs,
                                'infant'=>$flight->infant,
                                'departure_date'=>$flight->departure_date,
                                'lead_details'=>json_decode($flight->lead_passenger_details),
                                'view_reservation_detail'=> $Itineraries,
                                'trip_type'=>$booking_detail_rs->Data->TripDetailsResult->TravelItinerary->TripType,
                                'booking_status'=>$flight->booking_status,
                                'adult_price'=>$flight->adult_price ?? '',
                                'child_price'=>$flight->child_price,
                                'infant_price'=>$flight->infant_price,
                                'total_price'=> $flight->total_price,
                                'currency'=> $flight->currency,
                                'booking_date'=> date('d-m-Y', strtotime($flight->created_at)),
                            ];
                     
                        }
                    }
                }
            }
            return response()->json(['flight_arrival_list'=>$aray_data]);
      }
        
        if($request->report_type == 'data_today_wise' || $request->report_type == 'data_tomorrow_wise' || $request->report_type == 'data_week_wise'|| $request->report_type == 'data_month_wise'){
      
          
           $all_flight = DB::table('flight_bookings')->where('auth_token',$request->token)->orderBy('departure_date','ASC')->get(); 
             
                                        
            $aray_data=array();
            foreach($all_flight as $flight){
               if($flight->booking_status == 'Confirmed'){
                $booking_rs=json_decode($flight->booking_rs);
                if(isset($flight->booking_detail_rs) && $flight->booking_detail_rs != NULL && $flight->booking_detail_rs != 'null' && $flight->booking_detail_rs != ''){
                
                  $booking_detail_rs=json_decode($flight->booking_detail_rs);
               
                  foreach($booking_detail_rs->Data->TripDetailsResult->TravelItinerary->Itineraries as $Itineraries){
                      
                    // foreach($Itineraries->ItineraryInfo->ReservationItems[0]->ArrivalDateTime as $ReservationItems)
                    // {
                    $ArrivalDateTime=$Itineraries->ItineraryInfo->ReservationItems[0]->ArrivalDateTime;
                    $dateTime = new DateTime($ArrivalDateTime);
                    $ArrivalDate = $dateTime->format('Y-m-d');
                    if($request->report_type == 'data_today_wise'){
                         $today_date=date('Y-m-d');
                        if($ArrivalDate == $today_date)
                        {  
                        $aray_data[]=(object)[
                        
                        'invoice_no'=> $flight->invoice_no,
                        'UniqueID'=> $booking_rs->Data->UniqueID,
                        'adults'=>$flight->adults,
                        'childs'=>$flight->childs,
                        'infant'=>$flight->infant,
                        'departure_date'=>$flight->departure_date,
                        'lead_details'=>json_decode($flight->lead_passenger_details),
                        'view_reservation_detail'=> $Itineraries,
                        'trip_type'=>$booking_detail_rs->Data->TripDetailsResult->TravelItinerary->TripType,
                        'booking_status'=>$flight->booking_status,
                        'adult_price'=>$flight->adult_price ?? '',
                        'child_price'=>$flight->child_price,
                        'infant_price'=>$flight->infant_price,
                        'total_price'=> $flight->total_price,
                        'currency'=> $flight->currency,
                       'booking_date'=> date('d-m-Y', strtotime($flight->created_at)),
                        
                        
                        ];
                        }
                       }
                    if($request->report_type == 'data_tomorrow_wise'){
                         $today_date=date('Y-m-d');
                           $datetime = new DateTime($today_date);
                            $datetime->modify('+1 day');
                            $tomorrowdate=$datetime->format('Y-m-d');
                        if($ArrivalDate == $tomorrowdate)
                        {  
                            $aray_data[]=(object)[
                        
                                'invoice_no'=> $flight->invoice_no,
                                'UniqueID'=> $booking_rs->Data->UniqueID,
                                'adults'=>$flight->adults,
                                'childs'=>$flight->childs,
                                'infant'=>$flight->infant,
                                'departure_date'=>$flight->departure_date,
                                'lead_details'=>json_decode($flight->lead_passenger_details),
                                'view_reservation_detail'=> $Itineraries,
                                'trip_type'=>$booking_detail_rs->Data->TripDetailsResult->TravelItinerary->TripType,
                                'booking_status'=>$flight->booking_status,
                                'adult_price'=>$flight->adult_price ?? '',
                                'child_price'=>$flight->child_price,
                                'infant_price'=>$flight->infant_price,
                                'total_price'=> $flight->total_price,
                                'currency'=> $flight->currency,
                               'booking_date'=> date('d-m-Y', strtotime($flight->created_at)),
                                
                                
                                ];
                     
                  }    
                       }
                    if($request->report_type == 'data_week_wise'){
                         $today_date=date('Y-m-d');
                           $datetime = new DateTime($today_date);
                            $datetime->modify('+6 day');
                            $this_week=$datetime->format('Y-m-d');
                        if($ArrivalDate >= $today_date && $ArrivalDate <= $this_week)
                        {  
                            $aray_data[]=(object)[
                        
                                'invoice_no'=> $flight->invoice_no,
                                'UniqueID'=> $booking_rs->Data->UniqueID,
                                'adults'=>$flight->adults,
                                'childs'=>$flight->childs,
                                'infant'=>$flight->infant,
                                'departure_date'=>$flight->departure_date,
                                'lead_details'=>json_decode($flight->lead_passenger_details),
                                'view_reservation_detail'=> $Itineraries,
                                'trip_type'=>$booking_detail_rs->Data->TripDetailsResult->TravelItinerary->TripType,
                                'booking_status'=>$flight->booking_status,
                                'adult_price'=>$flight->adult_price ?? '',
                                'child_price'=>$flight->child_price,
                                'infant_price'=>$flight->infant_price,
                                'total_price'=> $flight->total_price,
                                'currency'=> $flight->currency,
                               'booking_date'=> date('d-m-Y', strtotime($flight->created_at)),
                                
                                
                                ];
                     
                  }    
                       }
                    if($request->report_type == 'data_month_wise'){
                         $today_date=date('Y-m-d');
                           $datetime = new DateTime($today_date);
                             $datetime->modify('+29 day');
                            $this_month=$datetime->format('Y-m-d');
                        if($ArrivalDate >= $today_date && $ArrivalDate <= $this_month)
                        {  
                            $aray_data[]=(object)[
                        
                                'invoice_no'=> $flight->invoice_no,
                                'UniqueID'=> $booking_rs->Data->UniqueID,
                                'adults'=>$flight->adults,
                                'childs'=>$flight->childs,
                                'infant'=>$flight->infant,
                                'departure_date'=>$flight->departure_date,
                                'lead_details'=>json_decode($flight->lead_passenger_details),
                                'view_reservation_detail'=> $Itineraries,
                                'trip_type'=>$booking_detail_rs->Data->TripDetailsResult->TravelItinerary->TripType,
                                'booking_status'=>$flight->booking_status,
                                'adult_price'=>$flight->adult_price ?? '',
                                'child_price'=>$flight->child_price,
                                'infant_price'=>$flight->infant_price,
                                'total_price'=> $flight->total_price,
                                'currency'=> $flight->currency,
                               'booking_date'=> date('d-m-Y', strtotime($flight->created_at)),
                                
                                
                                ];
                     
                  }    
                       }
                     
                       
                    // }
                 
                }
                }
                
                   
               }
                
                
              
                
            }
            
            
                
                      
                      
                 
              return response()->json(['flight_arrival_list'=>$aray_data]);   
                
                
                 
               
                
                
              
                
            
            
     
         }
        
        if($request->report_type == 'data_wise'){
         
             
             
             $all_flight = DB::table('flight_bookings')->where('auth_token',$request->token)->orderBy('departure_date','ASC')->get(); 
             
                                        
            $aray_data=array();
            foreach($all_flight as $flight){
               if($flight->booking_status == 'Confirmed'){
                $booking_rs=json_decode($flight->booking_rs);
                if(isset($flight->booking_detail_rs) && $flight->booking_detail_rs != NULL && $flight->booking_detail_rs != 'null' && $flight->booking_detail_rs != ''){
                
                  $booking_detail_rs=json_decode($flight->booking_detail_rs);
               
                  foreach($booking_detail_rs->Data->TripDetailsResult->TravelItinerary->Itineraries as $Itineraries){
                      
                    // foreach($Itineraries->ItineraryInfo->ReservationItems[0]->ArrivalDateTime as $ReservationItems)
                    // {
                    $ArrivalDateTime=$Itineraries->ItineraryInfo->ReservationItems[0]->ArrivalDateTime;
                  
                    $dateTime = new DateTime($ArrivalDateTime);
                    $ArrivalDate = $dateTime->format('Y-m-d');
                    
                   
                    
                    if($ArrivalDate >= $request->arrival_date && $ArrivalDate <= $request->departure_date){
                         
                        $aray_data[]=(object)[
                        
                        'invoice_no'=> $flight->invoice_no,
                        'UniqueID'=> $booking_rs->Data->UniqueID,
                        'adults'=>$flight->adults,
                        'childs'=>$flight->childs,
                        'infant'=>$flight->infant,
                        'departure_date'=>$flight->departure_date,
                        'lead_details'=>json_decode($flight->lead_passenger_details),
                        'view_reservation_detail'=> $Itineraries,
                        'trip_type'=>$booking_detail_rs->Data->TripDetailsResult->TravelItinerary->TripType,
                        'booking_status'=>$flight->booking_status,
                        'adult_price'=>$flight->adult_price ?? '',
                        'child_price'=>$flight->child_price,
                        'infant_price'=>$flight->infant_price,
                        'total_price'=> $flight->total_price,
                        'currency'=> $flight->currency,
                       'booking_date'=> date('d-m-Y', strtotime($flight->created_at)),
                        
                        
                        ];
                      
                       }
                   
                     
                       
                    // }
                 
                }
                }
                
                   
               }
                
                
              
                
            }
            
            
                
                      
                      
                 
              return response()->json(['flight_arrival_list'=>$aray_data]);  
            
         }
    }
    
    public function flight_insert_data(Request $request){
     
        DB::beginTransaction();
                  
                     try {
                          $result = DB::table('flight_bookings')->insert([

                    'auth_token'=>$request->auth_token,
                    'invoice_no'=>$request->invoice_no,
                    'adults'=>$request->adults,
                    'childs'=>$request->childs,
                    'infant'=>$request->infant,
                    'departure_date'=>$request->departure_date,
                    'lead_passenger_details'=>$request->lead_passenger_details,
                    'other_passenger_details'=>$request->other_passenger_details,
                    'child_details'=>$request->child_details,
                    'infant_details'=>$request->infant_details,
                    'search_rq'=>$request->search_rq,
                    'search_rs'=>$request->search_rs,
                    'farerules_rq'=>$request->farerules_rq,
                    'farerules_rs'=>$request->farerules_rs,
                    'revalidation_rq'=>$request->revalidation_rq,
                    'revalidation_rs'=>$request->revalidation_rs,
                    'booking_rq'=>$request->booking_rq,
                    'booking_rs'=>$request->booking_rs,
                    'booking_detail_rq'=>$request->booking_detail_rq,
                    'booking_detail_rs'=>$request->booking_detail_rs,
                    'booking_status'=>$request->booking_status,
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

                    'auth_token'=>$request->auth_token,
                    'invoice_no'=>$request->invoice_no,
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
                  
                  
        
                            
                            $customer_get_data = DB::table('customer_subcriptions')->where('Auth_key',$request->auth_token)->first();
                            $credit_data = DB::table('credit_limits')->where('customer_id',$customer_get_data->id)->where('status_type','approved')->orderBy('id','DESC')->limit(1)->first();
                                         $ramainAmount=$credit_data->remaining_amount - $request->creditAmount;
                                         
                                        $credit_limits = DB::table('credit_limits')->insert([
                                            'transection_id'=>$request->invoice_no,
                                            'customer_id'=>$customer_get_data->id,
                                                'amount'=>$request->creditAmount,
                                                'total_amount'=>$credit_data->total_amount,
                                                'remaining_amount'=>$ramainAmount,
                                                'currency'=>$credit_data->currency,
                                                'status'=>'1',
                                                'status_type'=>'approved',
                                                'services_type'=>'flight',
                                            
                                            ]);
                                        
                                       $credit_limits = DB::table('credit_limit_transections')->insert([
                                        'invoice_no'=> $request->invoice_no,
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
                                        'token'=> $request->auth_token,
                                        'invoice_no'=> $request->invoice_no,
                                        'type'=>'flight_booking',
                                        'received_amount'=>round($request->client_payable_price_exchange,2),
                                        'balance_amount'=>round($tAmount,2),
                                        'status'=>'1',
                                        ]);
                                        
                                        
                                        $get_payment= DB::table('flight_payment_details')->where('auth_token',$request->auth_token)->where('payment_status','1')->orderBy('id','DESC')->first();
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
                                        'auth_token'=> $request->auth_token,
                                        'payment_transction_id'=> $request->invoice_no,
                                        'type'=>'flight_booking',
                                        'payment_received_amount'=>round($request->client_payable_price_exchange,2),
                                        'payment_remaining_amount'=>round($total_remain_am,2),
                                        'payment_paid_amount'=>$payment_paid_amount,
                                        'payment_total_amount'=>$payment_total_amount,
                                        'payment_status'=>'1',
                                        ]);
                                        
                   
                          
                           DB::commit();
                                return response()->json(['status'=>'Success','result'=>$result]);
                        } catch (Throwable $e) {

                            DB::rollback();
                            return response()->json(['status'=>'Error']);
                        }
        
    
        
        
        
        
        
        
 }
 
 
 
 public function booking_request(Request $request)
 {
     
$flight_bookings = DB::table('flight_bookings')->where('invoice_no',$request->invoice_no)->first();
if(isset($flight_bookings))
{
  return response()->json(['status'=>'error','result'=>'already on request']);  
}
else
{
  DB::beginTransaction();
                  
                     try {
                         
                   $result = DB::table('flight_bookings')->insert([

                    'auth_token'=>$request->auth_token,
                    'invoice_no'=>$request->invoice_no,
                    'adults'=>$request->adults,
                    'childs'=>$request->childs,
                    'infant'=>$request->infant,
                    'departure_date'=>$request->departure_date,
                    'lead_passenger_details'=>$request->lead_passenger_details,
                    'other_passenger_details'=>$request->other_passenger_details,
                    'child_details'=>$request->child_details,
                    'infant_details'=>$request->infant_details,
                    'search_rq'=>$request->search_rq,
                    'search_rs'=>$request->search_rs,
                    'farerules_rq'=>$request->farerules_rq,
                    'farerules_rs'=>$request->farerules_rs,
                    'revalidation_rq'=>$request->revalidation_rq,
                    'revalidation_rs'=>$request->revalidation_rs,
                    'booking_rq'=>$request->booking_rq,
                    'booking_rs'=>'',
                    'booking_detail_rq'=>'',
                    'booking_detail_rs'=>'',
                    'booking_status'=>$request->booking_status,
                    'booking_type'=>'OnRequest',
                    'payment_details'=>'',
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

                    'auth_token'=>$request->auth_token,
                    'invoice_no'=>$request->invoice_no,
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
                  
                      DB::commit();
                                return response()->json(['status'=>'Success','result'=>$result]);
                        } catch (Throwable $e) {

                            DB::rollback();
                            return response()->json(['status'=>'Error']);
                        }
}                    
 }
 
 
 
public function onrequest_booking_process_submit(Request $request)
{
    $invoice_no=$request->invoice_no;
    
     DB::beginTransaction();
                  
                     try {
    
    $flight_bookings = DB::table('flight_bookings')->where('invoice_no',$request->invoice_no)->update([
        
        'booking_rs'=>$request->booking_rs,
        'booking_detail_rq'=>$request->booking_detail_rq,
        'booking_detail_rs'=>$request->booking_detail_rs,
        'booking_status'=>'Confirmed',
        'booking_type'=>'Processed',
        'payment_details'=>$request->payment_details,
        
        ]);
        
        $booking_get=DB::table('airline_markups')->where('invoice_no',$request->invoice_no)->first();
        $customer_get_data = DB::table('customer_subcriptions')->where('Auth_key',$booking_get->auth_token)->first();
                            $credit_data = DB::table('credit_limits')->where('customer_id',$customer_get_data->id)->where('status_type','approved')->orderBy('id','DESC')->limit(1)->first();
                                         $ramainAmount=$credit_data->remaining_amount - $booking_get->client_markup_price;
                                         
                                        $credit_limits = DB::table('credit_limits')->insert([
                                            'transection_id'=>$request->invoice_no,
                                            'customer_id'=>$customer_get_data->id,
                                                'amount'=>$booking_get->client_markup_price,
                                                'total_amount'=>$credit_data->total_amount,
                                                'remaining_amount'=>$ramainAmount,
                                                'currency'=>$credit_data->currency,
                                                'status'=>'1',
                                                'status_type'=>'approved',
                                                'services_type'=>'flight',
                                            
                                            ]);
                                        
                                       $credit_limits = DB::table('credit_limit_transections')->insert([
                                        'invoice_no'=> $request->invoice_no,
                                        'customer_id'=>$customer_get_data->id,
                                        'transection_amount'=>$booking_get->client_markup_price,
                                        'remaining_amount'=>$ramainAmount,
                                        'type'=>'booked',
                                        'services_type'=>'flight',
                                        ]);
                                        
                                        
                                        $get_ledger = DB::table('flight_customer_ledgers')->latest()->first();
                                        $balance_amount=$get_ledger->balance_amount ?? '0';
                                        $tAmount=$balance_amount + $booking_get->client_markup_price;
                                        
                                        $flight_customer_ledgers = DB::table('flight_customer_ledgers')->insert([
                                        'token'=> $booking_get->auth_token,
                                        'invoice_no'=> $request->invoice_no,
                                        'type'=>'flight_booking',
                                        'received_amount'=>round($booking_get->client_markup_price,2),
                                        'balance_amount'=>round($tAmount,2),
                                        'status'=>'1',
                                        ]);
                                        
                                        
                                        $get_payment= DB::table('flight_payment_details')->where('auth_token',$booking_get->auth_token)->where('payment_status','1')->orderBy('id','DESC')->first();
                                        $total_remain_am=$get_payment->payment_remaining_amount + $booking_get->client_markup_price;
                                        $flight_payment_details = DB::table('flight_payment_details')->insert([
                                        'auth_token'=> $booking_get->auth_token,
                                        'payment_transction_id'=> $request->invoice_no,
                                        'type'=>'flight_booking',
                                        'payment_received_amount'=>round($booking_get->client_markup_price,2),
                                        'payment_remaining_amount'=>round($total_remain_am,2),
                                        'payment_paid_amount'=>$get_payment->payment_paid_amount,
                                        'payment_total_amount'=>$get_payment->payment_total_amount,
                                        'payment_status'=>'1',
                                        ]);
                                        
                        DB::commit();
                                return response()->json(['status'=>'Success','result'=>$flight_bookings]);
                        } catch (Throwable $e) {

                            DB::rollback();
                            return response()->json(['status'=>'Error']);
                        }                                       
}
 
 
 
public function booking_onrequest_list(Request $request)
 {
    $all_booking = DB::table('flight_bookings')->where('auth_token',$request->token)->where('booking_type','OnRequest')->orwhere('booking_type','Processed')->orwhere('booking_type','Rejected')->orderBy('id','DESC')->get();
    return response()->json(['all_booking'=>$all_booking]);
 }
 public function  onrequest_booking_process(Request $request)
 {
    $booking = DB::table('flight_bookings')->where('invoice_no',$request->invoice_no)->first();
    $markups = DB::table('airline_markups')->where('invoice_no',$request->invoice_no)->first();
    return response()->json(['booking'=>$booking,'markups'=>$markups]);
 } 
 
  public function  onrequest_booking_rejected(Request $request)
 {
     $bookings = DB::table('flight_bookings')->where('invoice_no',$request->invoice_no)->first();
    $flight_bookings = DB::table('flight_bookings')->where('invoice_no',$request->invoice_no)->update([
        'booking_status'=>'Rejected',
        'booking_type'=>'Rejected',
        ]);
  
    return response()->json(['flight_bookings'=>$flight_bookings,'bookings'=>$bookings]);
 } 
 
  
   
   public function flight_voucher(Request $request,$invoice_no)
 {
    $result = DB::table('flight_bookings')->where('invoice_no',$invoice_no)->first();
    return response()->json(['result'=>$result]);
 }   
    
    public function flight_booking_list_Season_Working($all_data,$request,$user_Data){
        $today_Date     = date('Y-m-d');
        
        if(isset($request->season_Id)){
            if($request->season_Id == 'all_Seasons'){
                return $all_data;
            }elseif($request->season_Id > 0){
                $season_Details = DB::table('add_Seasons')->where('customer_id', $user_Data->id)->where('id', $request->season_Id)->first();
            }else{
                return $all_data;
            }
        }else{
            $season_Details     = DB::table('add_Seasons')->where('customer_id', $user_Data->id)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
        }
        
        if($season_Details != null){
            $start_Date = $season_Details->start_Date;
            $end_Date   = $season_Details->end_Date;
            
            $filtered_data = collect($all_data)->filter(function ($record) use ($start_Date, $end_Date) {
                if (!isset($record->created_at)) {
                    return false;
                }
                
                if($record->created_at != null && $record->created_at != ''){
                    $checkIn    = Carbon::parse($record->created_at);
                    return $checkIn->between($start_Date, $end_Date) || ($checkIn->lte($start_Date) && $checkIn->gte($end_Date));
                }else{
                    return false;
                }
            });
            
            $all_data = $filtered_data;
        }
        return $all_data;
    }
    
    public function flight_booking_list(Request $request){
        $user_Data  = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
        $result     = DB::table('flight_bookings')
                        ->Join('airline_markups', 'flight_bookings.invoice_no', '=', 'airline_markups.invoice_no')
                        ->select('airline_markups.*','flight_bookings.adults','flight_bookings.childs','flight_bookings.infant','flight_bookings.departure_date','flight_bookings.adult_price'
                        ,'flight_bookings.child_price','flight_bookings.infant_price','flight_bookings.total_price','flight_bookings.adult_price_markup','flight_bookings.child_price_markup'
                        ,'flight_bookings.infant_price_markup','flight_bookings.total_price_markup','flight_bookings.client_commission_amount','flight_bookings.admin_commission_amount'
                        ,'flight_bookings.client_payable_price','flight_bookings.currency','flight_bookings.booking_detail_rs','flight_bookings.booking_status')
                        ->where('flight_bookings.auth_token',$request->token)->orderBy('id','ASC')->get();
        $season_Id      = '';
        $today_Date     = date('Y-m-d');
        if(isset($request->season_Id) && $request->season_Id == 'all_Seasons'){
            $season_Id  = 'all_Seasons';
        }elseif(isset($request->season_Id) && $request->season_Id > 0){
            $season_SD  = DB::table('add_Seasons')->where('customer_id', $user_Data->id)->where('id', $request->season_Id)->first();
            $season_Id  = $season_SD->id ?? '';
        }else{
            $season_SD  = DB::table('add_Seasons')->where('customer_id', $user_Data->id)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
            $season_Id  = $season_SD->id ?? '';
        }
        $season_Details = DB::table('add_Seasons')->where('customer_id', $user_Data->id)->get();
        // Season
        if($user_Data->id == 4){
            // dd($result);
            $result       = $this->flight_booking_list_Season_Working($result,$request,$user_Data);
            // dd($result);
        }
        // Season
        return response()->json(['result'=>$result,'season_Details'=>$season_Details,'season_Id'=>$season_Id]);
    }  
    
   public function trip_detail_update(Request $request)
 {
     $invoice_no=$request->invoice_no;
     $trip_details=$request->trip_details;
    $result = DB::table('flight_bookings')->where('invoice_no',$invoice_no)->update([
        'booking_detail_rs'=>$trip_details,
        ]);
    return response()->json(['result'=>$result,'message'=>'Success']);
 }     
    
  public function get_markup_flight(Request $request)
 {
     $token=$request->token;
     $customer= DB::table('customer_subcriptions')->where('Auth_key',$token)->first();
    $markups= DB::table('admin_markups')->where('customer_id',$customer->id)->where('status',1)->get();
    return response()->json(['markups'=>$markups]);
 }

   public function flight_payment_details(Request $request)
    {
     $token=$request->token;
     $customer= DB::table('customer_subcriptions')->where('Auth_key',$token)->first();
     $flight_payment_details= DB::table('flight_payment_details')->where('auth_token',$token)->where('payment_status','1')->orderBy('id','DESC')->first();
     
     $all_booking= DB::table('flight_bookings')
     ->Join('airline_markups', 'flight_bookings.invoice_no', '=', 'airline_markups.invoice_no') ->select('airline_markups.*','flight_bookings.adult_price','flight_bookings.child_price'
     ,'flight_bookings.infant_price','flight_bookings.total_price','flight_bookings.adult_price_markup','flight_bookings.child_price_markup','flight_bookings.infant_price_markup'
     ,'flight_bookings.total_price_markup','flight_bookings.client_commission_amount','flight_bookings.admin_commission_amount','flight_bookings.client_payable_price','flight_bookings.currency')->where('flight_bookings.auth_token',$token)->where('flight_bookings.booking_status','Confirmed')->get();
   
    return response()->json(['customer'=>$customer,'flight_payment_details'=>$flight_payment_details,'all_booking'=>$all_booking]);
 }
  public function flight_payment_submit(Request $request)
 {
     
     $flight_payment_details= DB::table('flight_payment_details')->where('auth_token',$request->token)->where('payment_status','1')->latest()->first();
     $payment_paid_amount=$flight_payment_details->payment_paid_amount ?? '0';
     $total_paid_am=$payment_paid_amount + $request->payment_received_amount;
     
      DB::beginTransaction();
                  
                     try {
    $flight_payment_details= DB::table('flight_payment_details')->insert([
        
        'auth_token'=>$request->token,
        'payment_transction_id'=>$request->payment_transction_id,
        'payment_date'=>$request->payment_date,
        'payment_method'=>$request->payment_method,
        'payment_received_amount'=>$request->payment_received_amount,
        'payment_total_amount'=>$request->payment_total_amount,
        'payment_remaining_amount'=>$request->payment_remaining_amount,
        'payment_paid_amount'=>$total_paid_am,
        'payment_remarks'=>$request->payment_remarks,
        'payment_status'=>'0',
        
        ]);
        
        
        // $ledgers= DB::table('flight_customer_ledgers')->latest()->first();
        // $balance_amount=$ledgers->balance_amount - $request->payment_paid_amount;
        // $flight_customer_ledgers= DB::table('flight_customer_ledgers')->insert([
        
        // 'token'=>$request->token,
        // 'invoice_no'=>$request->payment_transction_id,
        // 'type'=>'payment',
        // 'payment_amount'=>$request->payment_paid_amount,
        // 'balance_amount'=>$balance_amount,
        // 'status'=>0,

        // ]);
         DB::commit();
                                return response()->json(['flight_payment_details'=>$flight_payment_details]);
                        } catch (Throwable $e) {

                            DB::rollback();
                            return response()->json(['status'=>'Error']);
                        }
    
 }
 public function customer_flight_ledger(Request $request)
 {
     $token=$request->token;
     $customer= DB::table('customer_subcriptions')->where('Auth_key',$token)->first();
     $all_booking= DB::table('flight_customer_ledgers')
     ->leftJoin('flight_bookings', 'flight_customer_ledgers.invoice_no', '=', 'flight_bookings.invoice_no')->where('flight_customer_ledgers.type','=', 'payment')->orwhere('flight_customer_ledgers.type','=', 'flight_booking')->select('flight_customer_ledgers.*','flight_bookings.adult_price','flight_bookings.child_price'
     ,'flight_bookings.infant_price','flight_bookings.total_price','flight_bookings.adult_price_markup','flight_bookings.child_price_markup','flight_bookings.infant_price_markup'
     ,'flight_bookings.total_price_markup','flight_bookings.client_commission_amount','flight_bookings.admin_commission_amount','flight_bookings.client_payable_price','flight_bookings.currency','flight_bookings.booking_detail_rs')->orderBy('flight_customer_ledgers.id','ASC')->get();
    return response()->json(['all_booking'=>$all_booking,'customer'=>$customer]);
 }
  public function customer_flight_history(Request $request)
 {
     $token=$request->token;
     $customer= DB::table('customer_subcriptions')->where('Auth_key',$token)->first();
     $flight_payment_details= DB::table('flight_payment_details')->where('auth_token',$token)->get();
    
    return response()->json(['flight_payment_details'=>$flight_payment_details,'customer'=>$customer]);
 }
 
     public function flight_update_status(Request $request)
 {
     $ConversationId=$request->ConversationId;
     $flight_bookings= DB::table('flight_bookings')->where('invoice_no',$ConversationId)->update([
         'booking_status'=>'Cancelled'
         ]);
     return response()->json(['flight_bookings'=>$flight_bookings]);
 } 
}
