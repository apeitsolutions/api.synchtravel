<?php

namespace App\Http\Controllers\frontend\admin_dashboard;
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
use App\Jobs\ApiJob;
use Carbon\Carbon;

class HotelPaymentController extends Controller
{
    public function all_provider_booking_Season_Working($all_data,$request){
        $today_Date         = date('Y-m-d');
        if(isset($request->season_Id)){
            if($request->season_Id == 'all_Seasons'){
                return $all_data;
            }elseif($request->season_Id > 0){
                $season_Details = DB::table('add_Seasons')->where('token', $request->token)->where('id', $request->season_Id)->first();
            }else{
                return $all_data;
            }
        }else{
            $season_Details     = DB::table('add_Seasons')->where('token', $request->token)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
        }
        
        if($season_Details != null){
            $start_Date     = $season_Details->start_Date;
            $end_Date       = $season_Details->end_Date;
            
            $filtered_data = collect($all_data)->filter(function ($record) use ($start_Date, $end_Date) {
                if (!isset($record->check_in)) {
                    return false;
                }
                $checkIn    = Carbon::parse($record->check_in);
                $checkOut   = isset($record->check_out) ? Carbon::parse($record->check_out) : $checkIn;
                return $checkIn->between($start_Date, $end_Date) || $checkOut->between($start_Date, $end_Date) || ($checkIn->lte($start_Date) && $checkOut->gte($end_Date));
            });
            
            $all_data = $filtered_data;
        }
        return $all_data;
    }
    
    public function all_provider_booking(Request $request){
        $auth_token = $request->token;
        $userData   = CustomerSubcription::where('Auth_key',$auth_token)->select('id','status')->first();
        if(isset($request->request_from)){
            $token          = $request->token;
            $all_booking    = DB::table('hotels_bookings')
                                ->leftJoin('manage_customer_markups', 'hotels_bookings.invoice_no', '=', 'manage_customer_markups.invoice_no')
                                ->where('hotels_bookings.customer_id','=', $userData->id)
                                ->select('hotels_bookings.*','hotels_bookings.status as hotel_booking_status','manage_customer_markups.payable_price','manage_customer_markups.client_commission_amount','manage_customer_markups.total_markup_price','manage_customer_markups.currency'
                                    ,'manage_customer_markups.exchange_payable_price','manage_customer_markups.exchange_client_commission_amount','manage_customer_markups.exchange_total_markup_price','manage_customer_markups.exchange_currency'
                                    ,'manage_customer_markups.exchange_rate'
                                )->orderBy('hotels_bookings.id','ASC')->get();
            // $all_booking=DB::table('hotel_provider_bookings')->where('auth_token',$token)->where('provider','!=','NULL')->orderBy('id', 'DESC')->get();
            $customer_subcriptions  = DB::table('customer_subcriptions')->where('Auth_key',$token)->first();
            $hotel_payment_details  = DB::table('hotel_payment_details')->where('token',$token)->first();
        }else{
            $token          = $request->token;
            $all_booking    = DB::table('hotel_provider_bookings')
                                ->leftJoin('manage_customer_markups', 'hotel_provider_bookings.invoice_no', '=', 'manage_customer_markups.invoice_no')
                                ->where('hotel_provider_bookings.auth_token','=', $token)
                                ->select('hotel_provider_bookings.*','manage_customer_markups.payable_price','manage_customer_markups.client_commission_amount','manage_customer_markups.total_markup_price','manage_customer_markups.currency'
                                    ,'manage_customer_markups.exchange_payable_price','manage_customer_markups.exchange_client_commission_amount','manage_customer_markups.exchange_total_markup_price','manage_customer_markups.exchange_currency'
                                    ,'manage_customer_markups.exchange_rate'
                                )->orderBy('hotel_provider_bookings.id','ASC')->get();
            // $all_booking=DB::table('hotel_provider_bookings')->where('auth_token',$token)->where('provider','!=','NULL')->orderBy('id', 'DESC')->get();
            $customer_subcriptions = DB::table('customer_subcriptions')->where('Auth_key',$token)->first();
            $hotel_payment_details = DB::table('hotel_payment_details')->where('token',$token)->first();
        }
        
        // Season
        $today_Date             = date('Y-m-d');
        $season_Id              = '';
        if(isset($request->season_Id) && $request->season_Id == 'all_Seasons'){
            $season_Id          = 'all_Seasons';
        }elseif(isset($request->season_Id) && $request->season_Id > 0){
            $season_SD          = DB::table('add_Seasons')->where('customer_id', $userData->id)->where('id', $request->season_Id)->first();
            $season_Id          = $season_SD->id ?? '';
        }else{
            $season_SD          = DB::table('add_Seasons')->where('customer_id', $userData->id)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
            $season_Id          = $season_SD->id ?? '';
        }
        
        $season_Details         = DB::table('add_Seasons')->where('customer_id', $userData->id)->get();
        if($userData->id == 4){
            if($all_booking->isEmpty()){
            }else{
                // dd($all_booking);
                $all_booking   = $this->all_provider_booking_Season_Working($all_booking,$request);
                // dd($all_booking);
            }
        }
        // Season
        
        return response()->json([
            'all_booking'           => $all_booking,
            'customer_subcriptions' => $customer_subcriptions,
            'hotel_payment_details' => $hotel_payment_details,
            'season_Details'        => $season_Details,
            'season_Id'             => $season_Id,
        ]);
    }
    
    public function payment_view_Season_Working($all_data,$request){
        $today_Date             = date('Y-m-d');
        if(isset($request->season_Id)){
            if($request->season_Id == 'all_Seasons'){
                return $all_data;
            }elseif($request->season_Id > 0){
                $season_Details = DB::table('add_Seasons')->where('token', $request->token)->where('id', $request->season_Id)->first();
            }else{
                return $all_data;
            }
        }else{
            $season_Details     = DB::table('add_Seasons')->where('token', $request->token)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
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
    
    public function payment_view(Request $request){
        $auth_token = $request->token;
        $userData   = CustomerSubcription::where('Auth_key',$auth_token)->select('id','status')->first();
        $customer   = DB::table('customer_subcriptions')->where('Auth_key',$auth_token)->first();
        if(isset($request->request_from)){
            $data                       = DB::table('hotel_payment_details')->orderBy('id','DESC')->where('token',$auth_token)->limit(1)->first();
            $total_booking              = DB::table('hotels_bookings')->where('customer_id',$userData->id)->count();
            $bookings                   = DB::table('hotel_provider_bookings')->where('auth_token',$auth_token)->select('exchange_price','check_in','check_out')->get();
            $hotel_customer_ledgers     = DB::table('hotel_customer_ledgers')->orderBy('id','DESC')->where('token',$auth_token)->limit(1)->first();
            $paid_amount                = DB::table('hotel_customer_ledgers')->sum('payment_amount');
            $manage_customer_markups    = DB::table('manage_customer_markups')->where('token',$auth_token)->get();
        }else{
            $data                       = DB::table('hotel_payment_details')->orderBy('id','DESC')->where('token',$auth_token)->limit(1)->first();
            $total_booking              = DB::table('hotel_provider_bookings')->where('auth_token',$auth_token)->count();
            $bookings                   = DB::table('hotel_provider_bookings')->where('auth_token',$auth_token)->select('exchange_price','check_in','check_out')->get();
            $hotel_customer_ledgers     = DB::table('hotel_customer_ledgers')->orderBy('id','DESC')->where('token',$auth_token)->limit(1)->first();
            $paid_amount                = DB::table('hotel_customer_ledgers')->sum('payment_amount');
            $manage_customer_markups    = DB::table('manage_customer_markups')->where('token',$auth_token)->get();
        }
        
        // Season
        $today_Date             = date('Y-m-d');
        $season_Id              = '';
        if(isset($request->season_Id) && $request->season_Id == 'all_Seasons'){
            $season_Id          = 'all_Seasons';
        }elseif(isset($request->season_Id) && $request->season_Id > 0){
            $season_SD          = DB::table('add_Seasons')->where('customer_id', $userData->id)->where('id', $request->season_Id)->first();
            $season_Id          = $season_SD->id ?? '';
        }else{
            $season_SD          = DB::table('add_Seasons')->where('customer_id', $userData->id)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
            $season_Id          = $season_SD->id ?? '';
        }
        
        if($userData->id == 4){
            if(isset($request->season_Id)){
                if($request->season_Id == 'all_Seasons'){
                    $season_Details         = null;
                }elseif($request->season_Id > 0){
                    $season_Details         = DB::table('add_Seasons')->where('customer_id', $userData->id)->where('id', $request->season_Id)->first();
                }else{
                    $season_Details         = null;
                }
            }else{
                $season_Details             = DB::table('add_Seasons')->where('customer_id', $userData->id)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
            }
            
            if($season_Details != null){
                $start_Date                 = $season_Details->start_Date;
                $end_Date                   = $season_Details->end_Date;
                
                $data                       = DB::table('hotel_payment_details')->orderBy('id','DESC')->where('token',$auth_token)->whereBetween('created_at', [$start_Date, $end_Date])->limit(1)->first();
                $hotel_customer_ledgers     = DB::table('hotel_customer_ledgers')->orderBy('id','DESC')->whereBetween('created_at', [$start_Date, $end_Date])->where('token',$auth_token)->limit(1)->first();
                $paid_amount                = DB::table('hotel_customer_ledgers')->whereBetween('created_at', [$start_Date, $end_Date])->sum('payment_amount');
                if(isset($request->request_from)){
                    $total_booking          = DB::table('hotels_bookings')->where('customer_id',$userData->id)->whereBetween('created_at', [$start_Date, $end_Date])->count();
                }else{
                    $total_booking          = DB::table('hotel_provider_bookings')
                                                ->where(function ($query) use ($start_Date, $end_Date) {
                                                    $query->whereBetween('check_in', [$start_Date, $end_Date])
                                                          ->orWhereBetween('check_out', [$start_Date, $end_Date]);
                                                })
                                                ->where('auth_token', $auth_token)->count();
                }
                
                if($bookings->isEmpty()){
                }else{
                    $bookings               = $this->all_provider_booking_Season_Working($bookings,$request);
                }
                
                if($manage_customer_markups->isEmpty()){
                }else{
                    $manage_customer_markups = $this->payment_view_Season_Working($manage_customer_markups,$request);
                }
            }
        }
        $season_Details                     = DB::table('add_Seasons')->where('customer_id', $userData->id)->get();
        // Season
        
        return response()->json([
            'manage_customer_markups'   => $manage_customer_markups,
            'data'                      => $data,
            'customer'                  => $customer,
            'total_booking'             => $total_booking,
            'bookings'                  => $bookings,
            'hotel_customer_ledgers'    => $hotel_customer_ledgers,
            'paid_amount'               => $paid_amount,
            'season_Details'            => $season_Details,
            'season_Id'                 => $season_Id
        ]);
    }
    
    function submit_payment(Request $request){
       
       
       
       
       
       DB::beginTransaction();
                  
                     try {
                         $token=$request->token;
        $payment_date=$request->payment_date;
        $payment_method=$request->payment_method;
        $payment_transction_id=$request->payment_transction_id;
        $payment_total_amount=$request->payment_total_amount;
        $payment_received_amount=$request->payment_received_amount;
        $payment_remaining_amount=$request->payment_remaining_amount;
        $payment_paid_amount=$request->payment_paid_amount;
        $payment_remarks=$request->payment_remarks;
        
        
        
        
        
        
        $payment_id = DB::table('hotel_payment_details')->insertGetId([
            'token'=>$token,
            'payment_date'=>$payment_date,
            'payment_method'=>$payment_method,
            'payment_transction_id'=>$payment_transction_id,
            'payment_total_amount'=>$payment_total_amount,
            'payment_received_amount'=>$payment_received_amount,
            'payment_remaining_amount'=>$payment_remaining_amount,
            'payment_paid_amount'=>$payment_paid_amount,
            'payment_remarks'=>$payment_remarks,
            ]);
            
            
            //print_r($payment_id);die();
            
            $get_hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->orderBy('id','DESC')->where('token',$token)->limit(1)->first();
        $balance_amount=str_replace(',', '', $get_hotel_customer_ledgers->balance_amount) - $payment_received_amount;
                //print_r($remain_price);die();
        
        
        //  $hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->insert([
        //     'token'=>$token,
        //     'invoice_no'=>$payment_id,
        //     'payment_amount'=>$payment_received_amount,
        //     'balance_amount'=>$balance_amount,
        //     'type'=>'payment',
        //     'status'=>0
        //     ]);
            
                          
                           DB::commit();
                                return response()->json(['payment_id'=>$payment_id]);
                        } catch (Throwable $e) {

                            DB::rollback();
                            return response()->json(['message'=>'error']);
                        }
       
       
       
        
            
            
            
        
        
    }
    
    function customer_hotel_ledger(Request $request){
      
      if(isset($request->request_from)){
          $auth_token=$request->token;
          $userData = CustomerSubcription::where('Auth_key',$auth_token)->select('id','status')->first();
            $customer = DB::table('customer_subcriptions')->where('Auth_key',$auth_token)->first();
            $hotel_payment = DB::table('hotel_payment_details')->where('token',$auth_token)->get();
            $hotels_booking = DB::table('hotels_bookings')->where('customer_id',$userData->id)->get();
            
            $hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->where('token',$auth_token)->get();
            
            
       
            
            
            
            $all_data = DB::table('hotel_customer_ledgers')
            ->leftjoin('hotels_bookings', 'hotel_customer_ledgers.invoice_no', '=', 'hotels_bookings.invoice_no')
            ->leftjoin('hotel_payment_details', 'hotel_customer_ledgers.invoice_no', '=', 'hotel_payment_details.id')
            ->where('hotel_customer_ledgers.token',$auth_token)
            // ->orwhere('hotel_customer_ledgers.type','=', 'payment')
            // ->orwhere('hotel_customer_ledgers.type','=', 'hotel_booking')
            ->select('hotel_customer_ledgers.*','hotel_customer_ledgers.status as cust_pay_status','hotels_bookings.*','hotel_payment_details.*')
            
            
            ->orderBy('hotel_customer_ledgers.id','ASC')->get();
            
            
            
           return response()->json(['all_data'=>$all_data,'hotels_booking'=>$hotels_booking,'customer'=>$customer,'hotel_payment'=>$hotel_payment,'hotel_customer_ledgers'=>$hotel_customer_ledgers]);
      }else{
          $auth_token=$request->token;
            $customer = DB::table('customer_subcriptions')->where('Auth_key',$auth_token)->first();
            $hotel_payment = DB::table('hotel_payment_details')->where('token',$auth_token)->get();
            $hotels_booking = DB::table('hotel_provider_bookings')->where('auth_token',$auth_token)->get();
            
            $hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->where('token',$auth_token)->get();
            
            
       
            
            
            
            $all_data = DB::table('hotel_customer_ledgers')
            ->leftjoin('hotel_provider_bookings', 'hotel_customer_ledgers.invoice_no', '=', 'hotel_provider_bookings.invoice_no')
            ->leftjoin('hotel_payment_details', 'hotel_customer_ledgers.invoice_no', '=', 'hotel_payment_details.id')
            ->where('hotel_customer_ledgers.type','=', 'payment')->orwhere('hotel_customer_ledgers.type','=', 'hotel_booking')
            ->select('hotel_customer_ledgers.*','hotel_payment_details.*', 'hotel_provider_bookings.check_in', 'hotel_provider_bookings.check_out', 'hotel_provider_bookings.rooms'
            , 'hotel_provider_bookings.adults', 'hotel_provider_bookings.childs', 'hotel_provider_bookings.checkavailability_rs', 'hotel_provider_bookings.checkavailability_again_rs','hotel_provider_bookings.booking_rs','hotel_provider_bookings.lead_passenger_details','hotel_provider_bookings.created_at','hotel_provider_bookings.provider')
            
            
            ->orderBy('hotel_customer_ledgers.id','ASC')->get();
            
            
            
           return response()->json(['all_data'=>$all_data,'hotels_booking'=>$hotels_booking,'customer'=>$customer,'hotel_payment'=>$hotel_payment,'hotel_customer_ledgers'=>$hotel_customer_ledgers]);
      }
    
        
        
    }
    
    function customer_hotel_payment_history(Request $request){
    
        $auth_token=$request->token;
        $customer = DB::table('customer_subcriptions')->where('Auth_key',$auth_token)->first();
        $hotel_payment_details = DB::table('hotel_payment_details')->orderBy('id','DESC')->where('token',$auth_token)->get();
        $total_booking = DB::table('hotel_provider_bookings')->where('auth_token',$auth_token)->count();
        $bookings = DB::table('hotel_provider_bookings')->where('auth_token',$auth_token)->select('exchange_price')->get();
        
        $hotel_customer_ledgers = DB::table('hotel_customer_ledgers')->orderBy('id','DESC')->where('token',$auth_token)->limit(1)->first();
        $paid_amount = DB::table('hotel_customer_ledgers')->sum('payment_amount');
        
        $manage_customer_markups = DB::table('manage_customer_markups')->where('token',$auth_token)->get();
        
        return response()->json(['hotel_payment_details'=>$hotel_payment_details,'customer'=>$customer]);
        
        
    }
    
    public function provider_booking_3rdParty(Request $request){
        $auth_token = $request->token;
        $userData   = CustomerSubcription::where('Auth_key',$auth_token)->select('id','status')->first();
        
        // dd($request->request_from);
        
        if(isset($request->request_from)){
            $token          = $request->token;
            $all_booking    = DB::table('hotels_bookings')
                                ->leftJoin('manage_customer_markups', 'hotels_bookings.invoice_no', '=', 'manage_customer_markups.invoice_no')
                                ->where('hotels_bookings.customer_id','=', $userData->id)
                                ->where('hotels_bookings.provider','!=', 'Custome_hotel')
                                ->select('hotels_bookings.*','hotels_bookings.status as hotel_booking_status','manage_customer_markups.payable_price','manage_customer_markups.client_commission_amount','manage_customer_markups.total_markup_price','manage_customer_markups.currency'
                                    ,'manage_customer_markups.exchange_payable_price','manage_customer_markups.exchange_client_commission_amount','manage_customer_markups.exchange_total_markup_price','manage_customer_markups.exchange_currency'
                                    ,'manage_customer_markups.exchange_rate'
                                )->orderBy('hotels_bookings.id','ASC')->get();
                            // dd($all_booking);   
            // $all_booking=DB::table('hotel_provider_bookings')->where('auth_token',$token)->where('provider','!=','NULL')->orderBy('id', 'DESC')->get();
            $customer_subcriptions  = DB::table('customer_subcriptions')->where('Auth_key',$token)->first();
            $hotel_payment_details  = DB::table('hotel_payment_details')->where('token',$token)->first();
           
            return response()->json([
                'all_booking'           => $all_booking,
                'customer_subcriptions' => $customer_subcriptions,
                'hotel_payment_details' => $hotel_payment_details]);
        }else{
            $token          = $request->token;
            $all_booking    = DB::table('hotel_provider_bookings')
                                ->leftJoin('manage_customer_markups', 'hotel_provider_bookings.invoice_no', '=', 'manage_customer_markups.invoice_no')
                                ->where('hotel_provider_bookings.auth_token','=', $token)
                                ->select('hotel_provider_bookings.*','manage_customer_markups.payable_price','manage_customer_markups.client_commission_amount','manage_customer_markups.total_markup_price','manage_customer_markups.currency'
                                    ,'manage_customer_markups.exchange_payable_price','manage_customer_markups.exchange_client_commission_amount','manage_customer_markups.exchange_total_markup_price','manage_customer_markups.exchange_currency'
                                    ,'manage_customer_markups.exchange_rate'
                                )->orderBy('hotel_provider_bookings.id','ASC')->get();
            
            // $all_booking=DB::table('hotel_provider_bookings')->where('auth_token',$token)->where('provider','!=','NULL')->orderBy('id', 'DESC')->get();
            $customer_subcriptions = DB::table('customer_subcriptions')->where('Auth_key',$token)->first();
            $hotel_payment_details = DB::table('hotel_payment_details')->where('token',$token)->first();
           
            return response()->json([
                'all_booking'           => $all_booking,
                'customer_subcriptions' => $customer_subcriptions,
                'hotel_payment_details' => $hotel_payment_details]);
        }
    }
}