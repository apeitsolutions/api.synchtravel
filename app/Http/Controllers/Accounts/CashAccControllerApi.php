<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReceviedPayments;
use App\Models\Payments;
use App\Models\cash_accounts_remarks;
use App\Models\CustomerSubcription\CustomerSubcription;
use DB;
use Session;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class CashAccControllerApi extends Controller
{

/*
|--------------------------------------------------------------------------
| CashAccControllerApi Function Started
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Agent Payments List Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to display Agents Payment List for Our Clients.
*/
    public function agent_payments_list(Request $request){
        // dd($request->all());
        $userData                   = CustomerSubcription::where('Auth_key',$request->token)->first();
        if($userData){
            $payments_list          = DB::table($request->table_name)->where('Criteria',$request->criteria)
                                                           ->where('Content_Ids',$request->Content_Ids)
                                                           ->orderBy('payment_date','asc')
                                                           ->get();
            $agents_data            = DB::table($request->person_detail_table)->where('id',$request->Content_Ids)->first();
            $currency_data          = DB::table('mange_currencies')->where('customer_id',$userData->id)->get();
            return response()->json([
                'status'            => 'success',
                'payments_list'     => $payments_list,
                'agent_data'        => $agents_data,
                'currency_data'     => $currency_data
            ]);                         
        }
    }
    
    public function payment_recv_list(Request $request){
        $today_Date     = date('Y-m-d');
        $userData               = CustomerSubcription::where('Auth_key',$request->token)->first();
        if($userData){
            $payments_list      = DB::table('recevied_payments')->join('recevied_payments_details','recevied_payments.id','=','recevied_payments_details.recevied_payments_id')
                                    ->where('recevied_payments.customer_id',$userData->id)->select('recevied_payments_details.*')->get();
            $all_customers      = DB::table('booking_customers')->where('customer_id', $userData->id)->get();
            $all_agents         = DB::table('Agents_detail')->where('customer_id', $userData->id)->get();
            $b2b_agents         = DB::table('b2b_agents')->where('token', $request->token)->get();
            $season_Id          = '';
            $season_Details     = DB::table('add_Seasons')->where('customer_id', $userData->id)->get();
            if(isset($request->season_Id)){
                if($request->season_Id == 'all_Seasons'){
                    $season_Id  = '';
                }elseif($request->season_Id > 0){
                    $season_DS  = DB::table('add_Seasons')->where('token', $request->token)->where('id', $request->season_Id)->first();
                    $season_Id  = $season_DS->id ?? '';
                }else{
                    $season_Id  = '';
                }
            }else{
                $season_DS      = DB::table('add_Seasons')->where('token', $request->token)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
                $season_Id      = $season_DS->id ?? '';
            }
            
            if($userData->id == 4){
                // dd($payments_list);
                $payments_list  = $this->make_payment_Season_Working($payments_list,$request);
                // dd($payments_list);
            }
            
            return response()->json([
                'status'            => 'success',
                'payments_list'     => $payments_list,
                'season_Details'    => $season_Details,
                'season_Id'         => $season_Id,
                'all_customers'     => $all_customers,
                'all_agents'        => $all_agents,
                'b2b_agents'        => $b2b_agents,
            ]);                         
        }
    }
    
    public function payment_recv_list_filter(Request $request){
        $userData               = CustomerSubcription::where('Auth_key',$request->token)->first();
        if($userData){
            $request_data       =  json_decode($request->request_data);
            
            if($request_data->report_type == 'all_data'){
                $payments_list  = DB::table('recevied_payments')->join('recevied_payments_details','recevied_payments.id','=','recevied_payments_details.recevied_payments_id')
                                    ->where('recevied_payments.customer_id',$userData->id);
            }
            
            if($request_data->report_type == 'data_wise'){
                $start_date     = $request_data->check_in;
                $end_date       = $request_data->check_out;
                $payments_list  = DB::table('recevied_payments')->join('recevied_payments_details','recevied_payments.id','=','recevied_payments_details.recevied_payments_id')
                                    ->where('recevied_payments.customer_id',$userData->id)->whereBetween('recevied_payments_details.payment_date', [$start_date, $end_date]);
            }
            
            if($request_data->report_type == 'data_today_wise'){
                $start_date     = date('Y-m-d');
                $end_date       = date('Y-m-d');
                $payments_list  = DB::table('recevied_payments')->join('recevied_payments_details','recevied_payments.id','=','recevied_payments_details.recevied_payments_id')
                                    ->where('recevied_payments.customer_id',$userData->id)->whereBetween('recevied_payments_details.payment_date', [$start_date, $end_date]);
            }
            
            if($request_data->report_type == 'data_tomorrow_wise'){
                $start_date     = date('Y-m-d',strtotime("+1 days"));
                $end_date       = date('Y-m-d',strtotime("+1 days"));
                $payments_list  = DB::table('recevied_payments')->join('recevied_payments_details','recevied_payments.id','=','recevied_payments_details.recevied_payments_id')
                                    ->where('recevied_payments.customer_id',$userData->id)->whereBetween('recevied_payments_details.payment_date', [$start_date, $end_date]);
            }
            
            if($request_data->report_type == 'data_week_wise'){
                $startOfWeek    = Carbon::now()->startOfWeek();
                $start_date     = $startOfWeek->format('Y-m-d');
                $endOfWeek      = Carbon::now()->endOfWeek();
                $end_date       = $endOfWeek->format('Y-m-d');
                $payments_list  = DB::table('recevied_payments')->join('recevied_payments_details','recevied_payments.id','=','recevied_payments_details.recevied_payments_id')
                                    ->where('recevied_payments.customer_id',$userData->id)->whereBetween('recevied_payments_details.payment_date', [$start_date, $end_date]);
            }
            
            if($request_data->report_type == 'data_month_wise'){
                $startOfMonth   = Carbon::now()->startOfMonth();
                $start_date     = $startOfMonth->format('Y-m-d');
                $endOfWeek      = Carbon::now()->endOfMonth();
                $end_date       = $endOfWeek->format('Y-m-d');
                $payments_list  = DB::table('recevied_payments')->join('recevied_payments_details','recevied_payments.id','=','recevied_payments_details.recevied_payments_id')
                                    ->where('recevied_payments.customer_id',$userData->id)->whereBetween('recevied_payments_details.payment_date', [$start_date, $end_date]);
            }
            
            if(isset($request_data->person_report_type)){
                $person_report_type = $request_data->person_report_type;
            }else{
                $person_report_type = 'All';
            }
            
            if($person_report_type == 'AgentWise'){
                if($request_data->agent_Id == 'all_agent'){
                    $payments_list->where('recevied_payments_details.Criteria', 'Agent');
                }else{
                    $payments_list->where('recevied_payments_details.Criteria', 'Agent')->where('recevied_payments_details.Content_Ids', $request_data->agent_Id);
                }
            }elseif($person_report_type == 'CustomerWise'){
                if($request_data->customer_Id == 'all_customer'){
                    $payments_list->where('recevied_payments_details.Criteria', 'Customer');
                }else{
                    $payments_list->where('recevied_payments_details.Criteria', 'Customer')->where('recevied_payments_details.Content_Ids', $request_data->customer_Id);
                }
            }elseif($person_report_type == 'B2BAgentWise'){
                if($request_data->b2b_agent_Id == 'b2b_agent_Id'){
                    $payments_list->where('recevied_payments_details.Criteria', 'B2B Agent');
                }else{
                    $payments_list->where('recevied_payments_details.Criteria', 'B2B Agent')->where('recevied_payments_details.Content_Ids', $request_data->b2b_agent_Id);
                }
            }else{
            }
            
            $payments_list = $payments_list->select('recevied_payments_details.*')->get();
            
            if($userData->id == 4){
                $payments_list  = $this->make_payment_Season_Working($payments_list,$request);
            }
            
            return response()->json([
                'status'            => 'success',
                'payments_list'     => $payments_list,
            ]);                         
        }
    }
    
    public function make_payment_list(Request $request){
        $today_Date     = date('Y-m-d');
        $userData                   = CustomerSubcription::where('Auth_key',$request->token)->first();
        if($userData){
            $payments_list          = DB::table('payments')->join('make_payments_details','payments.id','=','make_payments_details.make_payments_id')
                                        ->where('payments.customer_id',$userData->id)->select('make_payments_details.*')->get();
            $all_customers          = DB::table('booking_customers')->where('customer_id', $userData->id)->get();
            $all_agents             = DB::table('Agents_detail')->where('customer_id', $userData->id)->get();
            $b2b_agents             = DB::table('b2b_agents')->where('token', $request->token)->get();
            $hotel_suppliers        = DB::table('rooms_Invoice_Supplier')->where('customer_id', $userData->id)->get();
            $season_Id              = '';
            $season_Details         = DB::table('add_Seasons')->where('customer_id', $userData->id)->get();
            if(isset($request->season_Id)){
                if($request->season_Id == 'all_Seasons'){
                    $season_Id      = '';
                }elseif($request->season_Id > 0){
                    $season_DS      = DB::table('add_Seasons')->where('token', $request->token)->where('id', $request->season_Id)->first();
                    $season_Id      = $season_DS->id ?? '';
                }else{
                    $season_Id      = '';
                }
            }else{
                $season_DS          = DB::table('add_Seasons')->where('token', $request->token)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
                $season_Id          = $season_DS->id ?? '';
            }
            
            if($userData->id == 4){
                $payments_list      = $this->make_payment_Season_Working($payments_list,$request);
                // dd($payments_list);
            }
            return response()->json([
                'status'            => 'success',
                'payments_list'     => $payments_list,
                'season_Details'    => $season_Details,
                'season_Id'         => $season_Id,
                'all_customers'     => $all_customers,
                'all_agents'        => $all_agents,
                'b2b_agents'        => $b2b_agents,
                'hotel_suppliers'   => $hotel_suppliers,
            ]);                         
        }
    }
    
    public function make_payment_list_filter(Request $request){
        $userData                   = CustomerSubcription::where('Auth_key',$request->token)->first();
        if($userData){
            $request_data       =  json_decode($request->request_data);
            
            if($request_data->report_type == 'all_data'){
                $payments_list  = DB::table('payments')->join('make_payments_details','payments.id','=','make_payments_details.make_payments_id')
                                        ->where('payments.customer_id',$userData->id);
            }
            
            if($request_data->report_type == 'data_wise'){
                $start_date     = $request_data->check_in;
                $end_date       = $request_data->check_out;
                $payments_list  = DB::table('payments')->join('make_payments_details','payments.id','=','make_payments_details.make_payments_id')
                                    ->where('payments.customer_id',$userData->id)->whereBetween('make_payments_details.payment_date', [$start_date, $end_date]);
            }
            
            if($request_data->report_type == 'data_today_wise'){
                $start_date     = date('Y-m-d');
                $end_date       = date('Y-m-d');
                $payments_list  = DB::table('payments')->join('make_payments_details','payments.id','=','make_payments_details.make_payments_id')
                                    ->where('payments.customer_id',$userData->id)->whereBetween('make_payments_details.payment_date', [$start_date, $end_date]);
            }
            
            if($request_data->report_type == 'data_tomorrow_wise'){
                $start_date     = date('Y-m-d',strtotime("+1 days"));
                $end_date       = date('Y-m-d',strtotime("+1 days"));
                $payments_list  = DB::table('payments')->join('make_payments_details','payments.id','=','make_payments_details.make_payments_id')
                                    ->where('payments.customer_id',$userData->id)->whereBetween('make_payments_details.payment_date', [$start_date, $end_date]);
            }
            
            if($request_data->report_type == 'data_week_wise'){
                $startOfWeek    = Carbon::now()->startOfWeek();
                $start_date     = $startOfWeek->format('Y-m-d');
                $endOfWeek      = Carbon::now()->endOfWeek();
                $end_date       = $endOfWeek->format('Y-m-d');
                $payments_list  = DB::table('payments')->join('make_payments_details','payments.id','=','make_payments_details.make_payments_id')
                                    ->where('payments.customer_id',$userData->id)->whereBetween('make_payments_details.payment_date', [$start_date, $end_date]);
            }
            
            if($request_data->report_type == 'data_month_wise'){
                $startOfMonth   = Carbon::now()->startOfMonth();
                $start_date     = $startOfMonth->format('Y-m-d');
                $endOfWeek      = Carbon::now()->endOfMonth();
                $end_date       = $endOfWeek->format('Y-m-d');
                $payments_list  = DB::table('payments')->join('make_payments_details','payments.id','=','make_payments_details.make_payments_id')
                                    ->where('payments.customer_id',$userData->id)->whereBetween('make_payments_details.payment_date', [$start_date, $end_date]);
            }
            
            if(isset($request_data->person_report_type)){
                $person_report_type = $request_data->person_report_type;
            }else{
                $person_report_type = 'All';
            }
            
            if($person_report_type == 'AgentWise'){
                if($request_data->agent_Id == 'all_agent'){
                    $payments_list->where('make_payments_details.Criteria', 'Agent');
                }else{
                    $payments_list->where('make_payments_details.Criteria', 'Agent')->where('make_payments_details.Content_Ids', $request_data->agent_Id);
                }
            }elseif($person_report_type == 'CustomerWise'){
                if($request_data->customer_Id == 'all_customer'){
                    $payments_list->where('make_payments_details.Criteria', 'Customer');
                }else{
                    $payments_list->where('make_payments_details.Criteria', 'Customer')->where('make_payments_details.Content_Ids', $request_data->customer_Id);
                }
            }elseif($person_report_type == 'HotelSupplierWise'){
                if($request_data->hotel_supplier_Id == 'all_hotel_supplier'){
                    $payments_list->where('make_payments_details.Criteria', 'Hotel Supplier');
                }else{
                    $payments_list->where('make_payments_details.Criteria', 'Hotel Supplier')->where('make_payments_details.Content_Ids', $request_data->hotel_supplier_Id);
                }
            }elseif($person_report_type == 'B2BAgentWise'){
                if($request_data->b2b_agent_Id == 'b2b_agent_Id'){
                    $payments_list->where('make_payments_details.Criteria', 'B2B Agent');
                }else{
                    $payments_list->where('make_payments_details.Criteria', 'B2B Agent')->where('make_payments_details.Content_Ids', $request_data->b2b_agent_Id);
                }
            }else{
                
            }
            
            $payments_list          = $payments_list->select('make_payments_details.*')->orderBy('payment_date', 'desc')->get();
            // $payments_list          = collect($payments_list)->sortByDesc('payment_date')->values();
            
            // dd($payments_list);
            
            if($userData->id == 4){
                $payments_list      = $this->make_payment_Season_Working($payments_list,$request);
            }
            return response()->json([
                'status'            => 'success',
                'payments_list'     => $payments_list,
            ]);                         
        }
    }
    
    public function make_payment_Season_Working($all_data,$request){
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
                if (!isset($record->payment_date)) {
                    return false;
                }
                $checkIn    = Carbon::parse($record->payment_date);
                return $checkIn->between($start_Date, $end_Date) || ($checkIn->lte($start_Date) && $checkIn->gte($end_Date));
            });
            
            $all_data = $filtered_data;
        }
        return $all_data;
    }
/*
|--------------------------------------------------------------------------
| Agent Payments List Function Endded
|--------------------------------------------------------------------------
*/    

/*
|--------------------------------------------------------------------------
|Customer Payments List Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to display Customer Payment List for Our Clients.
*/
    public function customer_payments_list(Request $request){
        $userData                   = CustomerSubcription::where('Auth_key',$request->token)->first();
        if($userData){
            $payments_list          = DB::table($request->table_name)->where('Criteria',$request->criteria)->where('Content_Ids',$request->Content_Ids)->orderBy('payment_date','asc')->get();
            $customer_data          = DB::table($request->person_detail_table)->where('id',$request->Content_Ids)->first();
            $currency_data          = DB::table('mange_currencies')->where('customer_id',$userData->id)->get();
            
            $season_Details         = DB::table('add_Seasons')->where('token', $request->token)->where('id', $request->season_Id)->get();
            if($userData->id == 4 || $userData->id == 54){
                $payments_list      = $this->supplier_Payments_Season_Working($payments_list,$request);
                // dd($payments_list);
            }
            
            return response()->json([
                'status'            => 'success',
                'payments_list'     => $payments_list,
                'customer_data'     => $customer_data,
                'currency_data'     => $currency_data,
                'season_Details'    => $season_Details,
            ]);                         
        }   
    }
    
    public function customer_payments_list_datewise(Request $request){
        $userData                   = CustomerSubcription::where('Auth_key',$request->token)->first();
        if($userData){
            $payments_list          = DB::table($request->table_name)->where('Criteria',$request->criteria)->where('Content_Ids',$request->Content_Ids)
                                        ->whereDate('payment_date','>=', $request->start_date)->whereDate('payment_date','<=', $request->end_date)
                                        ->orderBy('payment_date','asc')->get();
            $customer_data          = DB::table($request->person_detail_table)->where('id',$request->Content_Ids)->first();
            $currency_data          = DB::table('mange_currencies')->where('customer_id',$userData->id)->get();
            
            $season_Details         = DB::table('add_Seasons')->where('token', $request->token)->where('id', $request->season_Id)->get();
            if($userData->id == 4 || $userData->id == 54){
                // dd($payments_list);
                $payments_list      = $this->supplier_Payments_Season_Working($payments_list,$request);
                // dd($payments_list);
            }
            
            return response()->json([
                'status'            => 'success',
                'payments_list'     => $payments_list,
                'customer_data'     => $customer_data,
                'currency_data'     => $currency_data,
                'season_Details'    => $season_Details,
            ]);                         
        }   
    }
    
    public function agent_payments_list_datewise(Request $request){
        $userData                   = CustomerSubcription::where('Auth_key',$request->token)->first();
        if($userData){
            $payments_list          = DB::table($request->table_name)->where('Criteria',$request->criteria)->where('Content_Ids',$request->Content_Ids)
                                        ->whereDate('payment_date','>=', $request->start_date)->whereDate('payment_date','<=', $request->end_date)
                                        ->orderBy('payment_date','asc')->get();
                                        // dd($payments_list);
            $customer_data          = DB::table($request->person_detail_table)->where('id',$request->Content_Ids)->first();
            $currency_data          = DB::table('mange_currencies')->where('customer_id',$userData->id)->get();
            
            $season_Details         = DB::table('add_Seasons')->where('token', $request->token)->where('id', $request->season_Id)->get();
            if($userData->id == 4 || $userData->id == 54){
                $payments_list      = $this->supplier_Payments_Season_Working($payments_list,$request);
                // dd($payments_list);
            }
            
            return response()->json([
                "status"            => 'success',
                "payments_list"     => $payments_list,
                "customer_data"     => $customer_data,
                "currency_data"     => $currency_data,
                'season_Details'    => $season_Details,
            ]);                         
        }
    }
    
    public function invoice_Receipt_Agent(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)->first();
        if($userData){
            $payments_list = DB::table($request->table_name)->where('id',$request->pay_id)->where('Criteria',$request->criteria)->where('Content_Ids',$request->Content_Ids)->orderBy('payment_date','asc')->first();
            $customer_data = DB::table($request->person_detail_table)->where('id',$request->Content_Ids)->first();
            $currency_data = DB::table('mange_currencies')->where('customer_id',$userData->id)->get();
            return response()->json([
                "status" => 'success',
                "payments_list" => $payments_list,
                "customer_data" => $customer_data,
                "currency_data" => $currency_data
            ]);                         
        }
    }
    
    public function invoice_Receipt_Customer(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)->first();
        if($userData){
            $payments_list = DB::table($request->table_name)->where('id',$request->pay_id)->where('Criteria',$request->criteria)->where('Content_Ids',$request->Content_Ids)->orderBy('payment_date','asc')->first();
            $customer_data = DB::table($request->person_detail_table)->where('id',$request->Content_Ids)->first();
            $currency_data = DB::table('mange_currencies')->where('customer_id',$userData->id)->get();
            return response()->json([
                "status" => 'success',
                "payments_list" => $payments_list,
                "customer_data" => $customer_data,
                "currency_data" => $currency_data
            ]);                         
        }
    }
/*
|--------------------------------------------------------------------------
| Agent Payments List Function Endded
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Client Account Statement
|--------------------------------------------------------------------------
| In this function, We coded the logic to Client Account Statement.
*/
    public function account_statement(Request $request){
       
        $userData = CustomerSubcription::where('Auth_key',$request->token)->first();
        if($userData){
            
            if(isset($request->start_date)){
                $startDate = $request->start_date;
                $endDate = $request->end_date;
                
                // Step 1: All Invoices Booking
                $all_invoices = DB::table('add_manage_invoices')
                                    ->where('customer_id',$userData->id)
                                    ->whereDate('created_at','>=', $startDate)
                                    ->whereDate('created_at','<=', $endDate)
                                    ->select('id','services','agent_Name','total_sale_price_all_Services','f_name','middle_name','accomodation_details','accomodation_details_more','transportation_details','transportation_details_more','more_visa_details','flights_details','return_flights_details','visa_type','visa_Pax','more_visa_details','services','created_at','total_sale_price_Company')
                                    ->orderBy('created_at')
                                    ->get();

                // Step 2 All Packages Booking
                $packages_booking_all = DB::table('cart_details')
                                                ->join('tours','tours.id','=','cart_details.tour_id')
                                                ->where('cart_details.client_id',$userData->id)
                                                ->whereDate('cart_details.created_at','>=', $startDate)
                                                ->whereDate('cart_details.created_at','<=', $endDate)
                                                ->select('cart_details.id','cart_details.pakage_type','cart_details.tour_name','cart_details.price','cart_details.invoice_no','cart_details.created_at'
                                                ,'tours.accomodation_details','tours.accomodation_details_more','tours.id as package_id')
                                                ->orderBy('created_at')
                                                ->get();
                
                // Step 3 All Hotels Booking
                $website_hotels_booking = DB::table('hotels_bookings')->join('b2b_agents','hotels_bookings.b2b_agent_id','b2b_agents.id')
                                                    ->where('hotels_bookings.customer_id',$userData->id)
                                                    ->where(function ($query) {
                                                        $query->where('hotels_bookings.status', 'CONFIRMED')
                                                        ->orWhere('hotels_bookings.status', 'Confirmed');
                                                    })
                                                    ->whereDate('hotels_bookings.created_at','>=', $startDate)
                                                    ->whereDate('hotels_bookings.created_at','<=', $endDate)
                                                    
                                                    ->select('hotels_bookings.id','hotels_bookings.customer_id','hotels_bookings.exchange_currency','hotels_bookings.lead_passenger',
                                                    'hotels_bookings.invoice_no as hotel_invoice','hotels_bookings.exchange_price','hotels_bookings.GBP_exchange_rate',
                                                    'hotels_bookings.GBP_invoice_price','hotels_bookings.status','hotels_bookings.created_at','hotels_bookings.reservation_response',
                                                    'hotels_bookings.total_rooms','hotels_bookings.total_adults','hotels_bookings.total_childs','hotels_bookings.payment_details',
                                                    'b2b_agents.id as b2b_Agent_Id','b2b_agents.first_name','b2b_agents.last_name','b2b_agents.company_name')
                                                    ->orderBy('created_at')
                                                    ->get();
                   
                // Step 4 All Transfer Booking
                $website_transfers_booking = DB::table('transfers_new_booking')
                                                    ->where('customer_id',$userData->id)
                                                    ->where('booking_status','Confirmed')
                                                    ->whereDate('created_at','>=', $startDate)
                                                    ->whereDate('created_at','<=', $endDate)
                                                    ->select('id','exchange_currency','lead_passenger','departure_date','transfer_total_price_exchange','transfer_total_price','currency','booking_status','created_at','transfer_data')
                                                    ->orderBy('created_at')
                                                    ->get();
                
                 
                // Step 5 All Visa Booking
                $website_visa_booking = DB::table('visa_bookings')
                                                    ->where('customer_id',$userData->id)
                                                    ->where('booking_status','Confirmed')
                                                    ->whereDate('created_at','>=', $startDate)
                                                    ->whereDate('created_at','<=', $endDate)
                                                    ->select('id','exchange_currency','lead_passenger','visa_total_price_exchange','visa_total_price','currency','booking_status','created_at','visa_avail_data')
                                                    ->orderBy('created_at')
                                                    ->get();
                
                // Step 7 All Payments & Receving
                
                $payments_data = DB::table('recevied_payments')
                        ->where('customer_id',$userData->id)
                        ->whereDate('date','>=', $startDate)
                        ->whereDate('date','<=', $endDate)
                        ->select('id as pay_recv_id','Content_Ids','Criteria','Content','Amount','remarks','payment_date','purchase_amount','converion_data',"exchange_rate","created_at","total_received")
                        ->orderBy('created_at')
                        ->get();
                        
                $make_payments_data = DB::table('payments')
                        ->where('customer_id',$userData->id)
                        ->whereDate('date','>=', $startDate)
                         ->whereDate('date','<=', $endDate)
                        ->select('id as pay_make_id','Content_Ids','Criteria','Content','Amount','remarks','payment_date','purchase_amount','converion_data',"exchange_rate","created_at","total_payments")
                        ->orderBy('created_at')
                        ->get();
                    
            }else{
                // Step 1: All Invoices Booking
                    $all_invoices = DB::table('add_manage_invoices')
                                        ->where('customer_id',$userData->id)
                                        ->select('id','services','agent_Name','total_sale_price_all_Services','f_name','middle_name','accomodation_details','accomodation_details_more','transportation_details','transportation_details_more','more_visa_details','flights_details','return_flights_details','visa_type','visa_Pax','more_visa_details','services','created_at','total_sale_price_Company')
                                        ->orderBy('created_at')
                                        ->get();
    
                // Step 2 All Packages Booking
                $packages_booking_all = DB::table('cart_details')
                                                ->join('tours','tours.id','=','cart_details.tour_id')
                                                ->join('Agents_detail','Agents_detail.id','=','cart_details.agent_name')
                                                ->where('cart_details.client_id',$userData->id)
                                                ->select('cart_details.id','cart_details.pakage_type','cart_details.tour_name','cart_details.price','cart_details.invoice_no','cart_details.created_at'
                                                 ,'tours.accomodation_details','tours.accomodation_details_more','tours.id as package_id','Agents_detail.agent_Name')
                                                ->orderBy('created_at')
                                                ->get();
                
                // Step 3 All Hotels Booking
                $website_hotels_booking = DB::table('hotels_bookings')->join('b2b_agents','hotels_bookings.b2b_agent_id','b2b_agents.id')
                                                    ->where('hotels_bookings.customer_id',$userData->id)
                                                    ->where(function ($query) {
                                                        $query->where('hotels_bookings.status', 'CONFIRMED')
                                                        ->orWhere('hotels_bookings.status', 'Confirmed');
                                                    })
                                                    ->select('hotels_bookings.id','hotels_bookings.customer_id','hotels_bookings.exchange_currency','hotels_bookings.lead_passenger',
                                                    'hotels_bookings.invoice_no as hotel_invoice','hotels_bookings.exchange_price','hotels_bookings.GBP_exchange_rate',
                                                    'hotels_bookings.GBP_invoice_price','hotels_bookings.status','hotels_bookings.created_at','hotels_bookings.reservation_response',
                                                    'hotels_bookings.total_rooms','hotels_bookings.total_adults','hotels_bookings.total_childs','hotels_bookings.payment_details',
                                                    'b2b_agents.id as b2b_Agent_Id','b2b_agents.first_name','b2b_agents.last_name','b2b_agents.company_name')
                                                    ->orderBy('created_at')
                                                    ->get();
                // Step 4 All Transfer Booking
                $website_transfers_booking = DB::table('transfers_new_booking')
                                                    ->where('customer_id',$userData->id)
                                                    ->where('booking_status','Confirmed')
                                                    ->select('id','exchange_currency','lead_passenger','departure_date','transfer_total_price_exchange','transfer_total_price','currency','booking_status','created_at','transfer_data')
                                                    ->orderBy('created_at')
                                                    ->get();
                
                 
                // Step 5 All Visa Booking
                $website_visa_booking = DB::table('visa_bookings')
                                                    ->where('customer_id',$userData->id)
                                                    ->where('booking_status','Confirmed')
                                                    ->select('id','exchange_currency','lead_passenger','visa_total_price_exchange','visa_total_price','currency','booking_status','created_at','visa_avail_data')
                                                    ->orderBy('created_at')
                                                    ->get();
                
                // Step 7 All Payments & Receving
                
                $payments_data = DB::table('recevied_payments')
                        ->where('customer_id',$userData->id)
                        ->select('id as pay_recv_id','Content_Ids','Content','Criteria','Amount','remarks','payment_date','purchase_amount','converion_data',"exchange_rate","created_at","total_received")
                        ->orderBy('created_at')
                        ->get();
                        
                $make_payments_data = DB::table('payments')
                        ->where('customer_id',$userData->id)
                        ->select('id as pay_make_id','Content_Ids','Content','Criteria','Amount','remarks','payment_date','purchase_amount','converion_data',"exchange_rate","created_at","total_payments")
                        ->orderBy('created_at')
                        ->get();
            }
            
            $all_data = $all_invoices->concat($packages_booking_all)
                                      ->concat($website_hotels_booking)
                                      ->concat($website_transfers_booking)
                                      ->concat($website_visa_booking)
                                      ->concat($payments_data)
                                      ->concat($make_payments_data)
                                      ->sortBy('created_at');
            return response()->json([
                    'status' => 'success',
                    'data' => $all_data
                ]);
            
        }
        
    }
/*
|--------------------------------------------------------------------------
| Agent Payments List Function Endded
|--------------------------------------------------------------------------
*/ 

/*
|--------------------------------------------------------------------------
| Delete Supplier Payments Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to Delete Supplier Payment for Our Clients.
*/    
    public function delete_supplier_payments(Request $request){
        // dd($request->all());
        $userData = CustomerSubcription::where('Auth_key',$request->token)->first();
        if($userData){
            $payments_data = DB::table('make_payments_details')->where('id',$request->payment_id)->first();
            
            if(isset($payments_data)){
                DB::beginTransaction();
                try{
                    if($payments_data->Criteria == 'Hotel Supplier'){
                        $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$payments_data->Content_Ids)->select('id','balance','payable')->first();
                        if(isset($supplier_data)){
                            // dd($supplier_data);
                            
                            if($supplier_data->balance > 0){
                                $supplier_data_balance = $supplier_data->balance;
                            }else{
                                $supplier_data_balance = (float)$supplier_data->balance;
                            }
                            
                            if($supplier_data->payable > 0){
                                $supplier_data_payable = $supplier_data->payable;
                            }else{
                                $supplier_data_payable = (float)$supplier_data->payable;
                            }
                            
                            if($payments_data->Amount > 0){
                                $payments_data_Amount = $payments_data->Amount;
                            }else{
                                $payments_data_Amount = (float)$payments_data->Amount;
                            }
                            
                            // dd($supplier_data_payable,$payments_data_Amount);
                            
                            $supplier_balance = $supplier_data_balance + $payments_data_Amount;
                            $supplier_payable = $supplier_data_payable + $payments_data_Amount;
                            
                            // dd($supplier_payable);
                            
                            DB::table('hotel_supplier_ledger')->insert([
                                'supplier_id'=>$supplier_data->id,
                                'payment'=>$payments_data->Amount,
                                'sale_price'=>$payments_data->purchase_amount,
                                'exchange_rate'=>$payments_data->exchange_rate,
                                'balance'=>$supplier_balance,
                                'payable_balance'=>$supplier_payable,
                                'payment_id'=>$payments_data->make_payments_id,
                                'customer_id'=>$userData->id,
                                'date'=>date('Y-m-d'),
                                'remarks'=> 'Delete Payment',
                            ]);
                            
                            // DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance,'payable'=>$supplier_payable]);
                            DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['payable'=>$supplier_payable]);
                        }
                }
                    
                    if($payments_data->Criteria == 'Flight Supplier'){
                        
                            $supplier_data = DB::table('supplier')->where('id',$payments_data->Content_Ids)->select('id','balance')->first();
                           
                            if(isset($supplier_data)){
                            // echo "Enter hre ";
                            $supplier_balance = $supplier_data->balance + $payments_data->Amount;

                            // update Agent Balance
                            
                                DB::table('flight_supplier_ledger')->insert([
                                        'supplier_id'=>$supplier_data->id,
                                        'payment'=>$payments_data->Amount,
                                        'sale_price'=>$payments_data->purchase_amount,
                                        'exchange_rate'=>$payments_data->exchange_rate,
                                        'balance'=>$supplier_balance,
                                        'payment_id'=>$payments_data->make_payments_id,
                                        'customer_id'=>$userData->id,
                                        'date'=> date('Y-m-d'),
                                        'remarks'=> 'Delete Payment',
                                        ]);
                            
                                DB::table('supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]); 
                            }
                    }
                    
                    if($payments_data->Criteria == 'Transfer Supplier'){
                            
                            $supplier_data = DB::table('transfer_Invoice_Supplier')->where('id',$payments_data->Content_Ids)->select('id','balance')->first();
                               
                            if(isset($supplier_data)){
                            // echo "Enter hre ";
                            $supplier_balance = $supplier_data->balance + $payments_data->Amount;

                            // update Agent Balance
                            
                                DB::table('transfer_supplier_ledger')->insert([
                                        'supplier_id'=>$supplier_data->id,
                                        'payment'=>$payments_data->Amount,
                                        'sale_price'=>$payments_data->purchase_amount,
                                        'exchange_rate'=>$payments_data->exchange_rate,
                                        'balance'=>$supplier_balance,
                                        'payment_id'=>$payments_data->make_payments_id,
                                        'customer_id'=>$userData->id,
                                        'date'=> date('Y-m-d'),
                                        'remarks'=> 'Delete Payment',
                                        ]);
                            
                                DB::table('transfer_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]); 
                            }
                    }
                  
                    // Update Account Balance 
                    $payment_recv_data = DB::table('payments')->where('id',$payments_data->make_payments_id)->select('payment_from')->first();
                    $cash_account_data = DB::table('cash_accounts')->where('id',$payment_recv_data->payment_from)->select('id','balance','name')->first();
                    
                    $updatedBalance =  $cash_account_data->balance + $payments_data->company_amount;
                    
                    DB::table('cash_accountledgers')->insert([
                        'account_id'=>$cash_account_data->id,
                        'received'=>$payments_data->company_amount,
                        'balance'=>$updatedBalance,
                        'payment_id'=>$payments_data->make_payments_id,
                        'customer_id'=>$userData->id,
                        'date'=>date('Y-m-d'),
                    ]);
                    
                    DB::table('cash_accounts')->where('id',$cash_account_data->id)->update(['balance'=>$updatedBalance]);
                    
                    $payments_data = DB::table('make_payments_details')->where('id',$request->payment_id)->delete();
                    
                    DB::commit();
                    
                    return response()->json([
                        'status'=>'success',
                    ]);
                } catch (\PDOException $e) {
                    echo $e->getMessage();
                    DB::rollBack();
                    return response()->json(['error'=>'Something Went Wrong Try Again']);
                }
            }
            
        }
        
    }
/*
|--------------------------------------------------------------------------
| Delete Supplier Payments Function Endded
|--------------------------------------------------------------------------
*/ 

 /*
|--------------------------------------------------------------------------
| Delete Agent Payments Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to Delete Agent Payment for Our Clients.
*/   
    public function delete_agent_payments(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)->first();
        if($userData){
            $payments_data = DB::table('recevied_payments_details')->where('id',$request->payment_id)->first();
            // dd($payments_data->recevied_payments_id);
            if(isset($payments_data)){
                DB::beginTransaction();
                try{
                    
                    if($payments_data->Criteria == 'Agent'){
                        $agent_data = DB::table('Agents_detail')->where('id',$payments_data->Content_Ids)->select('id','balance')->first();
                        
                        if(isset($agent_data)){
                            $agent_balance = $agent_data->balance + $payments_data->Amount;;
                            DB::table('agents_ledgers_new')->insert([
                                'agent_id'=>$agent_data->id,
                                'received'=>$payments_data->Amount,
                                'sale_price'=>$payments_data->purchase_amount,
                                'exchange_rate'=>$payments_data->exchange_rate,
                                'balance'=>$agent_balance,
                                'received_id'=>$payments_data->recevied_payments_id,
                                'customer_id'=>$userData->id,
                                'date'=> date('Y-m-d'),
                                'remarks'=> 'Deleted Payment',
                            ]);   
                            DB::table('Agents_detail')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                        }
                    }
                    
                    if($payments_data->Criteria == 'B2B Agent'){
                        $agent_data = DB::table('b2b_agents')->where('id',$payments_data->Content_Ids)->select('id','balance')->first();
                        if(isset($agent_data)){
                            $agent_balance = $agent_data->balance + $payments_data->Amount;
                            DB::table('agents_ledgers_new')->insert([
                                'b2b_Agent_id'=>$agent_data->id,
                                'received'=>$payments_data->Amount,
                                'sale_price'=>$payments_data->purchase_amount,
                                'exchange_rate'=>$payments_data->exchange_rate,
                                'balance'=>$agent_balance,
                                'received_id'=>$payments_data->recevied_payments_id,
                                'customer_id'=>$userData->id,
                                'date'=> date('Y-m-d'),
                                'remarks'=> 'Deleted Payment',
                            ]);
                            DB::table('b2b_agents')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                        }
                    }
                    
                    if($payments_data->Criteria == 'Customer'){
                        
                        $customer_data = DB::table('booking_customers')->where('id',$payments_data->Content_Ids)->select('id','balance')->first();
                               
                        if(isset($customer_data)){
                                // echo "Enter hre ";
                                $customer_balance = $customer_data->balance + $payments_data->Amount;;
                                
                                // update Agent Balance
                                
                                
                               
                                DB::table('customer_ledger')->insert([
                                        'booking_customer'=>$customer_data->id,
                                        'received'=>$payments_data->Amount,
                                        'sale_price'=>$payments_data->purchase_amount,
                                        'exchange_rate'=>$payments_data->exchange_rate,
                                        'balance'=>$customer_balance,
                                        'received_id'=>$payments_data->recevied_payments_id,
                                        'customer_id'=>$userData->id,
                                        'date'=> date('Y-m-d'),
                                        'remarks'=> 'Deleted Payment',
                                    ]);
                                    
                              DB::table('booking_customers')->where('id',$customer_data->id)->update(['balance'=>$customer_balance]);
                        }
                    }
                   
                    // Update Account Balance 
                    $payment_recv_data  = DB::table('recevied_payments')->where('id',$payments_data->recevied_payments_id)->select('received_from')->first();
                    $cash_account_data  = DB::table('cash_accounts')->where('id',$payment_recv_data->received_from)->select('id','balance','name')->first();
                    $updatedBalance     =  $cash_account_data->balance - $payments_data->company_amount;
                    
                    DB::table('cash_accountledgers')->insert([
                        'account_id'=>$cash_account_data->id,
                        'received'=>$payments_data->company_amount,
                        'balance'=>$updatedBalance,
                        'recevied_id'=>$payments_data->recevied_payments_id,
                        'customer_id'=>$userData->id,
                        'date'=>date('Y-m-d'),
                    ]);
                    
                    DB::table('cash_accounts')->where('id',$cash_account_data->id)->update(['balance'=>$updatedBalance]);
                    
                    DB::table('invoices_payment_recv')->where('recevied_id',$payments_data->recevied_payments_id)->delete();
                    
                    $payments_data = DB::table('recevied_payments_details')->where('id',$request->payment_id)->delete();
                    
                    // recevied_payments_id
                    // recevied_id
                    
                    DB::commit();
                    
                    return response()->json(['status'=>'success']);
                } catch (\PDOException $e) {
                    echo $e->getMessage();
                    DB::rollBack();
                    return response()->json(['error'=>'Something Went Wrong Try Again']);
                }
            }
            
        }
        
    }
/*
|--------------------------------------------------------------------------
| Delete Agent Payments Function Endded
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Supplier Agent Payments List Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to display Agent Payment list for Our Clients.
*/
    public function supplier_Payments_Season_Working($payments_list,$request){
        $today_Date     = date('Y-m-d');
        if(isset($request->season_Id)){
            if($request->season_Id == 'all_Seasons'){
                return $payments_list;
            }elseif($request->season_Id > 0){
                $season_Details = DB::table('add_Seasons')->where('token', $request->token)->where('id', $request->season_Id)->first();
            }else{
                return $payments_list;
            }
        }else{
            $season_Details     = DB::table('add_Seasons')->where('token', $request->token)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
        }
        
        if($season_Details != null){
            $start_Date         = $season_Details->start_Date;
            $end_Date           = $season_Details->end_Date;
            $filtered_data      = collect($payments_list)->filter(function ($record) use ($start_Date, $end_Date) {
                if (!isset($record->payment_date)) {
                    return false;
                }
                $payment_date   = Carbon::parse($record->payment_date);
                return $payment_date->between($start_Date, $end_Date) || ($payment_date->lte($start_Date) && $payment_date->gte($end_Date));
            });
            
            $payments_list = $filtered_data;
        }
        return $payments_list;
    }
    
    public function supplier_payments_list(Request $request){
        // dd($request->all());
        $userData = CustomerSubcription::where('Auth_key',$request->token)->first();
        if($userData){
            $payments_list = DB::table($request->table_name)->where('Criteria',$request->criteria)->where('Content_Ids',$request->Content_Ids)->get();
            
            if($request->criteria == 'Hotel Supplier'){
                $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$request->Content_Ids)->first();
            }
            
            if($request->criteria == 'Flight Supplier'){
                $supplier_data = DB::table('supplier')->where('id',$request->Content_Ids)->first();
            }
            
            if($request->criteria == 'Transfer Supplier'){
                $supplier_data = DB::table('transfer_Invoice_Supplier')->where('id',$request->Content_Ids)->first();
            }
            
            $currency_data = DB::table('mange_currencies')->where('customer_id',$userData->id)->get();
            
            // dd($payments_list);
            
            $season_Details             = DB::table('add_Seasons')->where('token', $request->token)->get();
            if($userData->id == 4 || $userData->id == 54){
                $payments_list          = $this->supplier_Payments_Season_Working($payments_list,$request);
                // dd($payments_list);
            }
            
            return response()->json([
                'status'            => 'success',
                'payments_list'     => $payments_list,
                'supplier_data'     => $supplier_data,
                'currency_data'     => $currency_data,
                'season_Details'    => $season_Details,
            ]);                         
        }
    }
    
    public function get_HS_Payment_List_Season(Request $request){
        // dd($request);
        $userData               = CustomerSubcription::where('Auth_key',$request->token)->first();
        if($userData){
            $payments_list      = DB::table($request->table_name)->where('Criteria',$request->criteria)->where('Content_Ids',$request->Content_Ids)->get();
            
            if($request->criteria == 'Hotel Supplier'){
                $supplier_data  = DB::table('rooms_Invoice_Supplier')->where('id',$request->Content_Ids)->first();
            }
            
            if($request->criteria == 'Flight Supplier'){
                $supplier_data  = DB::table('supplier')->where('id',$request->Content_Ids)->first();
            }
            
            if($request->criteria == 'Transfer Supplier'){
                $supplier_data  = DB::table('transfer_Invoice_Supplier')->where('id',$request->Content_Ids)->first();
            }
            
            $currency_data      = DB::table('mange_currencies')->where('customer_id',$userData->id)->get();
            
            // dd($payments_list);
            
            $season_Details     = DB::table('add_Seasons')->where('token', $request->token)->where('id', $request->season_Id)->get();
            if($userData->id == 4 || $userData->id == 54){
                $payments_list  = $this->supplier_Payments_Season_Working($payments_list,$request);
                // dd($payments_list);
            }
            
            return response()->json([
                'status'            => 'success',
                'payments_list'     => $payments_list,
                'supplier_data'     => $supplier_data,
                'currency_data'     => $currency_data,
                'season_Details'    => $season_Details,
            ]);                         
        }
    }
/*
|--------------------------------------------------------------------------
| Supplier Agent Payments List Function Ended
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Update Received Payment Agent Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to Update Received Agent Payment for Our Clients.
*/
    public function update_received_payment(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)->first();
        if($userData){
            $request_data       = json_decode($request->request_data);
            // dd($request_data->pay_amount);
            $payments_prev_data = DB::table('recevied_payments_details')->where('id',$request_data->payment_recv_sub_id)->first();
            DB::beginTransaction();
            try { 
                if(isset($payments_prev_data)){
                    $purchase_diff = $request_data->purchase_amount - $payments_prev_data->purchase_amount;
                    
                    $sale_diff = (float)$request_data->sale_amount - (float)$payments_prev_data->Amount;
                    
                    if($payments_prev_data->Criteria == 'Agent'){
                        $agent_data = DB::table('Agents_detail')->where('id',$payments_prev_data->Content_Ids)->select('id','balance')->first();
                           
                        if(isset($agent_data)){
                            // echo "Enter hre ";
                            $agent_balance = $agent_data->balance - $sale_diff;
                            
                            // update Agent Balance
                            
                            
                           
                            DB::table('agents_ledgers_new')->insert([
                                    'agent_id'=>$agent_data->id,
                                    'payment'=>$sale_diff,
                                    'sale_price'=>$purchase_diff,
                                    'exchange_rate'=>$request_data->exchange_rate,
                                    'balance'=>$agent_balance,
                                    'received_id'=>$payments_prev_data->recevied_payments_id,
                                    'customer_id'=>$userData->id,
                                    'date'=>$request_data->date,
                                    'remarks'=>$request_data->remarks,
                                ]);
                                
                            DB::table('Agents_detail')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                          
                          
                        }
                    }
                    
                    if($payments_prev_data->Criteria == 'B2B Agent'){
                        $agent_data = DB::table('b2b_agents')->where('id',$payments_prev_data->Content_Ids)->select('id','balance')->first();
                        if(isset($agent_data)){
                            $agent_balance = $agent_data->balance - $sale_diff;
                            DB::table('agents_ledgers_new')->insert([
                                'b2b_Agent_id'=>$agent_data->id,
                                'payment'=>$sale_diff,
                                'sale_price'=>$purchase_diff,
                                'exchange_rate'=>$request_data->exchange_rate,
                                'balance'=>$agent_balance,
                                'received_id'=>$payments_prev_data->recevied_payments_id,
                                'customer_id'=>$userData->id,
                                'date'=>$request_data->date,
                                'remarks'=>$request_data->remarks,
                            ]);
                            DB::table('b2b_agents')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                        }
                    }
                    
                    if($payments_prev_data->Criteria == 'Customer'){
                        $customer_data = DB::table('booking_customers')->where('id',$payments_prev_data->Content_Ids)->select('id','balance')->first();
                           
                        if(isset($customer_data)){
                            // echo "Enter hre ";
                            $customer_balance = $customer_data->balance - $sale_diff;
                            
                            // update Agent Balance
                            
                            
                           
                            DB::table('customer_ledger')->insert([
                                    'booking_customer'=>$customer_data->id,
                                    'payment'=>$sale_diff,
                                    'sale_price'=>$purchase_diff,
                                    'exchange_rate'=>$request_data->exchange_rate,
                                    'balance'=>$customer_balance,
                                    'received_id'=>$payments_prev_data->recevied_payments_id,
                                    'customer_id'=>$userData->id,
                                    'date'=>$request_data->date,
                                    'remarks'=>$request_data->remarks,
                                ]);
                                
                            DB::table('booking_customers')->where('id',$customer_data->id)->update(['balance'=>$customer_balance]);
                          
                          
                        }
                    }
                    
                    
                    if($request_data->payment_conversion_id != '-1'){
                           $currency_data = DB::table('mange_currencies')->where('id',$request_data->payment_conversion_id)->first();
                           $currency_data = json_encode($currency_data);
                    }else{
                           $currency_data = $request_data->payment_conversion_id;
                       }
                       
                    if($request_data->payment_conversion_id_comp != '-1'){
                           $currency_data_comp = DB::table('mange_currencies')->where('id',$request_data->payment_conversion_id_comp)->first();
                           $currency_data_comp = json_encode($currency_data_comp);
                    }else{
                           $currency_data_comp = $request_data->payment_conversion_id_comp;
                       }
                       
                      
                    DB::table('recevied_payments_details')->where('id',$request_data->payment_recv_sub_id)->update([
                        'Amount' => $request_data->sale_amount,
                        'remarks' => $request_data->remarks,
                        'purchase_amount' => $request_data->purchase_amount,
                        'exchange_rate' => $request_data->exchange_rate,
                        'converion_data' =>$currency_data,
                        'payment_date' =>$request_data->date,
                        'company_conversion' =>$currency_data_comp,
                        'exchange_rate_company' =>$request_data->company_exchange_rate,
                        'company_amount' =>$request_data->pay_amount,
                    ]);
                    
                    // Update Account Balance 
                    $payment_recv_data = DB::table('recevied_payments')->where('id',$payments_prev_data->recevied_payments_id)->select('received_from')->first();
                    
                    $cash_account_data = DB::table('cash_accounts')->where('id',$payment_recv_data->received_from)->select('id','balance','name')->first();
                    
                    $company_pay_diff = (float)$request_data->pay_amount - (float)$payments_prev_data->company_amount;
                    
                    $updatedBalance =  $cash_account_data->balance + $company_pay_diff;
                    
                    DB::table('cash_accountledgers')->insert([
                        'account_id'=>$cash_account_data->id,
                        'received'=>$company_pay_diff,
                        'balance'=>$updatedBalance,
                        'recevied_id'=>$payments_prev_data->recevied_payments_id,
                        'customer_id'=>$userData->id,
                        'date'=>date('Y-m-d'),
                    ]);
                    
                    DB::table('cash_accounts')->where('id',$cash_account_data->id)->update(['balance'=>$updatedBalance]);
                    
                    DB::table('invoices_payment_recv')->where('recevied_id',$payments_prev_data->recevied_payments_id)->update(['payment_amount'=>$request_data->pay_amount]);
                }
                
                DB::commit();
                return response()->json(["status" => 'success']);
            } catch (\Exception $e) {
                DB::rollback();
                echo $e;die;
                return response()->json(['message'=>'error','booking_id'=> '']);
                // something went wrong
            }
        }
    }
/*
|--------------------------------------------------------------------------
| Update Received Payment Agent Function Ended
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Update Make Payment Agent Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to Update Make Payment like hotel supplier ledger and room invoice supplier for Our Clients.
*/
    public function update_make_payment(Request $request){
        
        $userData = CustomerSubcription::where('Auth_key',$request->token)->first();
        if($userData){
            $request_data       = json_decode($request->request_data);
            $payments_prev_data = DB::table('make_payments_details')->where('id',$request_data->payment_sub_id)->first();
            
            // dd($request_data,$payments_prev_data);
            
            DB::beginTransaction();
            try {
                
                if(isset($payments_prev_data)){
                    // if($request_data->purchase_amount > $payments_prev_data->purchase_amount){
                        $purchase_diff              = (int)$request_data->purchase_amount - (int)$payments_prev_data->purchase_amount;
                    // }else{
                    //     $purchase_diff              = (int)$payments_prev_data->purchase_amount - (int)$request_data->purchase_amount;
                    // }
                    
                    // if($request_data->sale_amount > $payments_prev_data->Amount){
                        $sale_diff                  = (int)$request_data->sale_amount - (int)$payments_prev_data->Amount;
                    // }else{
                    //     $sale_diff                  = (int)$payments_prev_data->Amount - (int)$request_data->sale_amount;
                    // }
                    
                    // dd($sale_diff);
                    
                    if(!isset($request_data->supplier_type)){
                        $supplier_data          = DB::table('rooms_Invoice_Supplier')->where('id',$payments_prev_data->Content_Ids)->select('id','balance','payable')->first();
                        
                        if(isset($supplier_data)){
                            
                            // dd($supplier_data->payable,$sale_diff);
                            
                            // echo "Enter hre ";
                            // if($request_data->sale_amount > $payments_prev_data->Amount){
                                // $supplier_balance   = $supplier_data->balance + $sale_diff;
                                // $supplier_payable   = $supplier_data->payable + $sale_diff;
                            // }else{
                                $supplier_balance   = $supplier_data->balance - $sale_diff;
                                $supplier_payable   = $supplier_data->payable - $sale_diff;
                            // }
                            
                            // dd($supplier_payable);
                            
                            // update Agent Balance
                            DB::table('hotel_supplier_ledger')->insert([
                                'supplier_id'=>$supplier_data->id,
                                'received'=>$sale_diff,
                                'sale_price'=>$purchase_diff,
                                'exchange_rate'=>$request_data->exchange_rate,
                                'balance'=>$supplier_balance,
                                'payable_balance'=>$supplier_payable,
                                'payment_id'=>$payments_prev_data->make_payments_id,
                                'customer_id'=>$userData->id,
                                'date'=>$request_data->date,
                                'remarks'=>$request_data->remarks,
                            ]);
                            
                            // DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance,'payable'=>$supplier_payable]); 
                            DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['payable'=>$supplier_payable]); 
                            
                            if($request_data->payment_conversion_id != '-1'){
                                $currency_data = DB::table('mange_currencies')->where('id',$request_data->payment_conversion_id)->first();
                                $currency_data = json_encode($currency_data);
                            }else{
                                $currency_data = $request_data->payment_conversion_id;
                            }
                           
                            if($request_data->payment_conversion_id_comp != '-1'){
                               $currency_data_comp = DB::table('mange_currencies')->where('id',$request_data->payment_conversion_id_comp)->first();
                               $currency_data_comp = json_encode($currency_data_comp);
                            }else{
                               $currency_data_comp = $request_data->payment_conversion_id_comp;
                            }
                          
                            DB::table('make_payments_details')->where('id',$request_data->payment_sub_id)->update([
                                'Amount' => $request_data->sale_amount,
                                'remarks' => $request_data->remarks,
                                'purchase_amount' => $request_data->purchase_amount,
                                'exchange_rate' => $request_data->exchange_rate,
                                'converion_data' =>$currency_data,
                                'payment_date' =>$request_data->date,
                                'company_conversion' =>$currency_data_comp,
                                'exchange_rate_company' =>$request_data->company_exchange_rate,
                                'company_amount' =>$request_data->pay_amount,
                            ]);
                            
                            // Update Account Balance 
                            $payment_recv_data = DB::table('payments')->where('id',$payments_prev_data->make_payments_id)->select('payment_from')->first();
                            
                            $cash_account_data = DB::table('cash_accounts')->where('id',$payment_recv_data->payment_from)->select('id','balance','name')->first();
                            
                            $company_pay_diff = $request_data->pay_amount - $payments_prev_data->company_amount;
                            
                            $updatedBalance =  $cash_account_data->balance - $company_pay_diff;
                            
                            DB::table('cash_accountledgers')->insert([
                                'account_id'=>$cash_account_data->id,
                                'received'=>$company_pay_diff,
                                'balance'=>$updatedBalance,
                                'payment_id'=>$payments_prev_data->make_payments_id,
                                'customer_id'=>$userData->id,
                                'date'=>date('Y-m-d'),
                            ]);
                            
                            DB::table('cash_accounts')->where('id',$cash_account_data->id)->update(['balance'=>$updatedBalance]);
                        }
                    }
                    
                    if(isset($request_data->supplier_type) && $request_data->supplier_type == 'flight_supplier'){

                         $supplier_data = DB::table('supplier')->where('id',$payments_prev_data->Content_Ids)->select('id','balance')->first();
                           
                            if(isset($supplier_data)){
                            // echo "Enter hre ";
                            $supplier_balance = $supplier_data->balance - $sale_diff;

                            // update Agent Balance
                            
                                DB::table('flight_supplier_ledger')->insert([
                                        'supplier_id'=>$supplier_data->id,
                                        'received'=>$sale_diff,
                                        'sale_price'=>$purchase_diff,
                                        'exchange_rate'=>$request_data->exchange_rate,
                                        'balance'=>$supplier_balance,
                                        'payment_id'=>$payments_prev_data->make_payments_id,
                                        'customer_id'=>$userData->id,
                                        'date'=>$request_data->date,
                                        'remarks'=>$request_data->remarks,
                                        ]);
                            
                                DB::table('supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]); 
                           
                         
                                

                           if($request_data->payment_conversion_id != '-1'){
                               $currency_data = DB::table('mange_currencies')->where('id',$request_data->payment_conversion_id)->first();
                               $currency_data = json_encode($currency_data);
                           }else{
                               $currency_data = $request_data->payment_conversion_id;
                           }
                           
                           if($request_data->payment_conversion_id_comp != '-1'){
                               $currency_data_comp = DB::table('mange_currencies')->where('id',$request_data->payment_conversion_id_comp)->first();
                               $currency_data_comp = json_encode($currency_data_comp);
                           }else{
                               $currency_data_comp = $request_data->payment_conversion_id_comp;
                           }
                           
                          
                           DB::table('make_payments_details')->where('id',$request_data->payment_sub_id)->update([
                                'Amount' => $request_data->sale_amount,
                                'remarks' => $request_data->remarks,
                                'purchase_amount' => $request_data->purchase_amount,
                                'exchange_rate' => $request_data->exchange_rate,
                                'converion_data' =>$currency_data,
                                'payment_date' =>$request_data->date,
                                'company_conversion' =>$currency_data_comp,
                                'exchange_rate_company' =>$request_data->company_exchange_rate,
                                'company_amount' =>$request_data->pay_amount,
                               ]);
                               
                                // Update Account Balance 
                        $payment_recv_data = DB::table('payments')->where('id',$payments_prev_data->make_payments_id)
                                                           ->select('payment_from')->first();
                                                           
                        $cash_account_data = DB::table('cash_accounts')->where('id',$payment_recv_data->payment_from)->select('id','balance','name')->first();
                        
                        $company_pay_diff = $request_data->pay_amount - $payments_prev_data->company_amount;
                        
                        $updatedBalance =  $cash_account_data->balance - $company_pay_diff;
                        
                        DB::table('cash_accountledgers')->insert([
                                            'account_id'=>$cash_account_data->id,
                                            'received'=>$company_pay_diff,
                                            'balance'=>$updatedBalance,
                                            'payment_id'=>$payments_prev_data->make_payments_id,
                                            'customer_id'=>$userData->id,
                                            'date'=>date('Y-m-d'),
                                            ]);
                        
                        DB::table('cash_accounts')->where('id',$cash_account_data->id)->update(['balance'=>$updatedBalance]);
                          
                               
                          
                        }
                    }
                    
                    if(isset($request_data->supplier_type) && $request_data->supplier_type == 'Transfer_supplier'){

                         $supplier_data = DB::table('transfer_Invoice_Supplier')->where('id',$payments_prev_data->Content_Ids)->select('id','balance')->first();
                           
                            if(isset($supplier_data)){
                            // echo "Enter hre ";
                            $supplier_balance = $supplier_data->balance - $sale_diff;

                            // update Agent Balance
                            
                                DB::table('transfer_supplier_ledger')->insert([
                                        'supplier_id'=>$supplier_data->id,
                                        'received'=>$sale_diff,
                                        'sale_price'=>$purchase_diff,
                                        'exchange_rate'=>$request_data->exchange_rate,
                                        'balance'=>$supplier_balance,
                                        'payment_id'=>$payments_prev_data->make_payments_id,
                                        'customer_id'=>$userData->id,
                                        'date'=>$request_data->date,
                                        'remarks'=>$request_data->remarks,
                                        ]);
                            
                                DB::table('transfer_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]); 
                           
                         
                                

                           if($request_data->payment_conversion_id != '-1'){
                               $currency_data = DB::table('mange_currencies')->where('id',$request_data->payment_conversion_id)->first();
                               $currency_data = json_encode($currency_data);
                           }else{
                               $currency_data = $request_data->payment_conversion_id;
                           }
                           
                           if($request_data->payment_conversion_id_comp != '-1'){
                               $currency_data_comp = DB::table('mange_currencies')->where('id',$request_data->payment_conversion_id_comp)->first();
                               $currency_data_comp = json_encode($currency_data_comp);
                           }else{
                               $currency_data_comp = $request_data->payment_conversion_id_comp;
                           }
                           
                          
                           DB::table('make_payments_details')->where('id',$request_data->payment_sub_id)->update([
                                'Amount' => $request_data->sale_amount,
                                'remarks' => $request_data->remarks,
                                'purchase_amount' => $request_data->purchase_amount,
                                'exchange_rate' => $request_data->exchange_rate,
                                'converion_data' =>$currency_data,
                                'payment_date' =>$request_data->date,
                                'company_conversion' =>$currency_data_comp,
                                'exchange_rate_company' =>$request_data->company_exchange_rate,
                                'company_amount' =>$request_data->pay_amount,
                               ]);
                               
                                // Update Account Balance 
                        $payment_recv_data = DB::table('payments')->where('id',$payments_prev_data->make_payments_id)
                                                           ->select('payment_from')->first();
                                                           
                        $cash_account_data = DB::table('cash_accounts')->where('id',$payment_recv_data->payment_from)->select('id','balance','name')->first();
                        
                        $company_pay_diff = $request_data->pay_amount - $payments_prev_data->company_amount;
                        
                        $updatedBalance =  $cash_account_data->balance - $company_pay_diff;
                        
                        DB::table('cash_accountledgers')->insert([
                                            'account_id'=>$cash_account_data->id,
                                            'received'=>$company_pay_diff,
                                            'balance'=>$updatedBalance,
                                            'payment_id'=>$payments_prev_data->make_payments_id,
                                            'customer_id'=>$userData->id,
                                            'date'=>date('Y-m-d'),
                                            ]);
                        
                        DB::table('cash_accounts')->where('id',$cash_account_data->id)->update(['balance'=>$updatedBalance]);
                          
                               
                          
                        }
                    }
                    
                }
                    
                DB::commit();
                return response()->json([
                    "status" => 'success',
                ]); 
            } catch (\Exception $e) {
                DB::rollback();
                echo $e;die;
                return response()->json(['message'=>'error','booking_id'=> '']);
                // something went wrong
            }
        }
    }
/*
|--------------------------------------------------------------------------
| Update Make Payment Agent Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Get Person Invoice Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to get invoices like person type agent invoices and customer invoices for Our Clients.
*/
    public function get_person_invoices(Request $request){
        // print_r($request->all());
        if($request->person_type == 'agent'){
            $agent_invoices = DB::table('add_manage_invoices')->where('agent_Id',$request->person_id)->select('id','generate_id','agent_Id','created_at')->get();
            $package_invoices = DB::table('cart_details')->where('agent_name',$request->person_id)->select('id','invoice_no','agent_name','created_at')->get();
            
  
            
            
            // Merge and sort the collections by 'created_at' date
            $agent_all_invoices = $agent_invoices->merge($package_invoices)->sortBy('created_at')->values();

            return response()->json([
                    "status" => 'success',
                    "person_type" => $request->person_type,
                    "all_invoices" => $agent_all_invoices,
                ]);
        }
        
        if($request->person_type == 'b2b_agent'){
            $agent_invoices         = DB::table('add_manage_invoices')->where('b2b_Agent_Id',$request->person_id)->select('id','generate_id','b2b_Agent_Id','created_at')->get();
            $package_invoices       = DB::table('cart_details')->where('b2b_agent_name',$request->person_id)->select('id','invoice_no','b2b_agent_name','created_at')->get();
            $agent_all_invoices     = $agent_invoices->merge($package_invoices)->sortBy('created_at')->values();
            
            return response()->json([
                    "status"        => 'success',
                    "person_type"   => $request->person_type,
                    "all_invoices"  => $agent_all_invoices,
                ]);
        }
        
        if($request->person_type == 'customer'){
            $customer_invoices = DB::table('add_manage_invoices')->where('booking_customer_id',$request->person_id)->select('id','booking_customer_id','generate_id','created_at')->get();
             $package_invoices = DB::table('cart_details')->whereJsonContains('cart_total_data->customer_id', $request->person_id)->select('id','invoice_no','created_at')->get();
             
             // Merge and sort the collections by 'created_at' date
            $customer_all_invoices = $customer_invoices->merge($package_invoices)->sortBy('created_at')->values();

            return response()->json([
                    "status" => 'success',
                    "person_type" => $request->person_type,
                    "all_invoices" => $customer_all_invoices,
                ]);
        }
        
        
    }
/*
|--------------------------------------------------------------------------
| Get Person Invoice Function Ended
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Custom Hotel Provider Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to get Custom Hotel Provider and Markups provider for Our Clients.
*/
    public function custom_hotel_provider(){
        $custom_hotel_provider = DB::table('custom_hotel_provider')
                                        ->join('customer_subcriptions','customer_subcriptions.id','=','custom_hotel_provider.customer_id')
                                        ->join('become_provider_markup','become_provider_markup.customer_id','=','custom_hotel_provider.customer_id')
                                        ->where('become_provider_markup.status','1')
                                        ->select('custom_hotel_provider.*','customer_subcriptions.id as cust_provider_id','customer_subcriptions.company_name','become_provider_markup.markup','become_provider_markup.markup_value')
                                        ->get();
                // dd($custom_hotel_provider);
        return view('template/frontend/userdashboard/pages/custom_hotel_provider/custom_hotel_provider',compact('custom_hotel_provider'));
        // custom_hotel_provider

    }
/*
|--------------------------------------------------------------------------
| Custom Hotel Provider Function Ended
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Custom Hotel Provider Ledger Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to Display Custom Hotel Provider Ledger List for Our Clients.
*/
    public function custom_hotel_provider_ledger($prvider_id){
        $customer_ledger = DB::table('cust_hotel_provider_ledger')->where('provider_id',$prvider_id)->orderBy('id','asc')->get();
        return view('template/frontend/userdashboard/pages/custom_hotel_provider/hotel_provider_ledger',compact('customer_ledger'));
        
    }
/*
|--------------------------------------------------------------------------
| Custom Hotel Provider Ledger Function Ended
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Provider Payment Request Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to Payment Request like custom hotel provider and become provider markups for Our Clients.
*/
    public function provider_payment_request(){
         $custom_hotel_provider = DB::table('custom_hotel_provider')
                                        ->join('customer_subcriptions','customer_subcriptions.id','=','custom_hotel_provider.customer_id')
                                        ->join('become_provider_markup','become_provider_markup.customer_id','=','custom_hotel_provider.customer_id')
                                        ->where('become_provider_markup.status','1')
                                        ->select('custom_hotel_provider.*','customer_subcriptions.id as cust_provider_id','customer_subcriptions.company_name','become_provider_markup.markup','become_provider_markup.markup_value')
                                        ->get();
                // dd($custom_hotel_provider);
                
        $payment_request = DB::table('provider_payments_requests')
                                        ->join('custom_hotel_provider','custom_hotel_provider.customer_id','=','provider_payments_requests.customer_id')
                                        ->select('provider_payments_requests.*','custom_hotel_provider.*')
                                        ->get();
                                        
                                        
        return view('template/frontend/userdashboard/pages/custom_hotel_provider/provider_payment_request',compact('custom_hotel_provider','payment_request'));
    }
/*
|--------------------------------------------------------------------------
| Provider Payment Request Function Ended
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Provider Payment Request Sub Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to Payment Request Provider Are Table Insert for Our Clients.
*/
    public function provider_payment_request_sub(Request $request){
        // print_r($request->all());
        $result = DB::table('provider_payments_requests')->insert([
                'payment_date' => $request->payment_date,
                'payment_amount' => $request->payment_amount,
                'payment_method' => $request->payment_method,
                'transcation_id' => $request->transcation_id,
                'customer_id' => $request->provider_id,
            ]);
        if($result){
            return redirect()->back()->with(['success'=>'Added Successfully']);
        }else{
            return redirect()->back()->with(['error'=>'Someting Went Wrong Try Again']);
        }
        
    }
/*
|--------------------------------------------------------------------------
| Provider Payment Request Sub Function Ended
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Subcription Details Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to Subcription Details like Pyment Plan For Our Clients.
*/
    public function subcritions_details(Request $request){
        // dd('ok');
        $userData = CustomerSubcription::where('Auth_key',$request->token)->first();
        if($userData){
            return response()->json([
                'status'=>'success',
                'data'=>$userData
            ]);
        }else{
            return response()->json([
                'status'=>'validation_error',
                'data'=>''
            ]);
        }
    }
/*
|--------------------------------------------------------------------------
| Subcription Details Function Ended
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Subcribed Customer Leger Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to Subcribed Customer Leger For Our Clients.
*/
    public function subcirbed_customer_ledger(Request $request){
         $userData = CustomerSubcription::where('Auth_key',$request->token)->first();
        if($userData){
            $user_data      = DB::table('customer_subcriptions')->where('id',$userData->id)->select('id','company_name')->first();
            $customer_ledger = DB::table('customer_subcription_ledger')->where('customer_id',$userData->id)->get();
            
            return response()->json([
                        'status'=>'success',
                        'data' => $customer_ledger,
                        'user_data' => $customer_ledger
            ]);
        }else{
            return response()->json([
                'status'=>'validation_error',
                'data'=>''
            ]);
        }
        
    }
/*
|--------------------------------------------------------------------------
| Subcribed Customer Leger Function Ended
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Subcribed Payment Request Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to get Payment Request Subcribed For Our Clients.
*/
    public function subcritions_payments_request(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)->first();
        if($userData){
            $pay_request_data = DB::table('cust_subcriptions_pay_request')->where('customer_id',$userData->id)->orderBy('id','desc')->get();
            return response()->json([
                'status'=>'success',
                'data'=>$pay_request_data
            ]);
        }else{
            return response()->json([
                'status'=>'validation_error',
                'data'=>''
            ]);
        }
    }
/*
|--------------------------------------------------------------------------
| Subcribed Payment Request Function Ended
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Subcribed Payment Request Sub Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to Insert The Subcribed Payment Request In Table For Our Clients.
*/
    public function subcritions_payments_request_sub(Request $request){
        $request_data = json_decode($request->request_data);
        // print_r($request_data);
        // die;
        $userData = CustomerSubcription::where('Auth_key',$request->token)->first();
        if($userData){
            
            $customer_data = DB::table('cust_subcriptions_pay_request')->insert([
                                'payment_month'=>$request_data->payment_month,
                                'payment_date'=>$request_data->payment_date,
                                'payment_amount'=>$request_data->payment_amount,
                                'payment_method'=>$request_data->payment_method,
                                'transcation_id'=>$request_data->transcation_id,
                                'customer_id'=> $userData->id
                            ]);
            if($customer_data){
                    return response()->json([
                        'status'=>'success',
                    ]);
            }else{
                    return response()->json([
                        'status'=>'error',
                    ]);
            }
        
        }else{
            return response()->json([
                'status'=>'validation_error',
                'data'=>''
            ]);
        }
    }
/*
|--------------------------------------------------------------------------
| Subcribed Payment Request Sub Function Ended
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Cash Account List Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to Display Cash Account List For Our Clients.
*/
    public function cash_accounts_list(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)->where('status',1)->select('id','status')->first();
        if($userData){
            $accounts_data          = DB::table('cash_accounts')->where('customer_id',$userData->id)->get();
            // $cash_accounts_remarks  = DB::table('cash_accounts_remarks')->where('cash_accounts_remarks.customer_id',$userData->id)
            //                             ->join('cash_accounts','cash_accounts_remarks.cash_accounts_id','cash_accounts.id')
            //                             ->select('cash_accounts_remarks.*','cash_accounts.*')
            //                             ->get();
            $cash_accounts_remarks = DB::table('cash_accounts_remarks as remarks')->join('cash_accounts as account', 'remarks.cash_accounts_id', '=', 'account.id')
                                        ->where('remarks.customer_id', $userData->id)->select('remarks.*', 'account.name', 'account.account_no')->orderBy('remarks.created_at', 'desc')->get();

            return response()->json([
                'status'                => 'success',
                'data'                  => $accounts_data,
                'cash_accounts_remarks' => $cash_accounts_remarks,
            ]);
        }else{
            return response()->json([
                'status'=>'validation_error',
                'data'=>''
            ]);
        }
    }
/*
|--------------------------------------------------------------------------
| Cash Account List Function Ended
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Cash Account Ledger Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to Display Cash Account Ledger List For Our Clients.
*/
    public function cash_accounts_ledger(Request $request){
        // print_r($request->all());
        
        $userData = CustomerSubcription::where('Auth_key',$request->token)
                                            ->where('status',1)
                                            ->select('id','status')->first();
        if($userData){
            $accounts_ledger_data = DB::table('cash_accountledgers')->where('account_id',$request->id)->get();
            $accounts_data = DB::table('cash_accounts')->where('id',$request->id)->first();
            
            return response()->json([
                'status'=>'success',
                'data'=>$accounts_ledger_data,
                'account_data'=>$accounts_data
            ]);
        }else{
            return response()->json([
                'status'=>'validation_error',
                'data'=>'',
                'account_data'
            ]);
        }
    }
/*
|--------------------------------------------------------------------------
| Cash Account Ledger Function Ended
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| View Payment Received Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to Display View Payment Received List For Our Clients.
*/
    public function view_pay_recv(Request $request){
        // print_r($request->all());
        
        $userData = CustomerSubcription::where('Auth_key',$request->token)
                                            ->where('status',1)
                                            ->select('id','status')->first();
        if($userData){
            $accounts_ledger_data = DB::table('recevied_payments')->where('id',$request->id)->first();
            
            $accounts_data = DB::table('cash_accounts')->where('id',$accounts_ledger_data->received_from)->first();
            
            return response()->json([
                'status'=>'success',
                'data'=>$accounts_ledger_data,
                'account_data'=>$accounts_data
            ]);
        }else{
            return response()->json([
                'status'=>'validation_error',
                'data'=>'',
                'account_data'
            ]);
        }
    }
/*
|--------------------------------------------------------------------------
| View Payment Received Function Ended
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Add Payment Received Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to Display All Payment Received like Cash Account,
| Agents Detail,booking customer,room invoice supplier,transfer invoice supplier table For Our Clients.
*/
    public function add_payment_recv(Request $request){
        // print_r($request->all());
        
        $userData   = CustomerSubcription::where('Auth_key',$request->token)->where('status',1)->select('id','status')->first();
        if($userData){
            $accounts_data          = DB::table('cash_accounts')->where('customer_id',$userData->id)->get();
            $agents_data            = DB::table('Agents_detail')->where('customer_id',$userData->id)->get();
            $customers_data         = DB::table('booking_customers')->where('customer_id',$userData->id)->get();
            $customer_Id            = $userData->id;
            $hotels_supplier_data   = DB::table('rooms_Invoice_Supplier')
                                        ->where(function ($query) use ($customer_Id) {
                                            $query->where('customer_id', $customer_Id);
                                        })
                                        ->orWhere(function ($query) use ($customer_Id) {
                                            $query->where('id', 135);
                                        })
                                        ->select('id','balance','payable','room_supplier_name','currrency')
                                        ->get();
                                        
            $flight_supplier_data   = DB::table('supplier')->where('customer_id',$userData->id)->select('id','balance','currency','contactpersonname','companyname')->get();
            $transfer_supplier_data = DB::table('transfer_Invoice_Supplier')->where('customer_id',$userData->id)->select('id','balance','currency','room_supplier_name','room_supplier_company_name')->get();
            $visa_supplier_data     = DB::table('visa_Sup')->where('customer_id',$userData->id)->select('id','balance','payable','currency','visa_supplier_name','contact_person_name')->get();
            $currency_data          = DB::table('mange_currencies')->where('customer_id',$userData->id)->get();
            $b2b_Agents_Data        = DB::table('b2b_agents')->where('token',$request->token)->get();
            if(!isset($request->payment_type)){
                $third_party_companies  = DB::table('3rd_party_commissions')
                                            ->join('customer_subcriptions','customer_subcriptions.id','3rd_party_commissions.third_party_id')
                                            ->where('3rd_party_commissions.customer_id',$userData->id)
                                            ->select('3rd_party_commissions.*','customer_subcriptions.company_name')
                                            ->get();
            }else{
                $third_party_companies  = DB::table('3rd_party_commissions')
                                            ->join('customer_subcriptions','customer_subcriptions.id','3rd_party_commissions.customer_id')
                                            ->where('3rd_party_commissions.third_party_id',$userData->id)
                                            ->select('3rd_party_commissions.*','customer_subcriptions.company_name')
                                            ->get();
            }
            return response()->json([
                'status'                    => 'success',
                'cash_accounts'             => $accounts_data,
                'agents'                    => $agents_data,
                'b2b_Agents_Data'           => $b2b_Agents_Data,
                'customers'                 => $customers_data,
                'hotels_supplier_data'      => $hotels_supplier_data,
                'transfer_supplier_data'    => $transfer_supplier_data,
                'flight_supplier_data'      => $flight_supplier_data,
                'visa_supplier_data'        => $visa_supplier_data,
                'third_party_companies'     => $third_party_companies,
                'currency_data'             => $currency_data
            ]);
        }else{
            return response()->json([
                'status'=>'validation_error',
                'data'=>''
            ]);
        }
    }
/*
|--------------------------------------------------------------------------
| Add Payment Received Function Ended
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Add Cash Account Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to Insert the Data in cash account table For Our Clients.
*/
    public function cash_accounts_add(Request $request){
        // print_r($request->all());
        
        $userData = CustomerSubcription::where('Auth_key',$request->token)
                                            ->where('status',1)
                                            ->select('id','status')->first();
        if($userData){
            
            $request_data = json_decode($request->req_data);
            
               
                                                 
            DB::table('cash_accounts')->insert([
                'name'=>$request_data->name,
                'account_no'=>$request_data->account_no,
                'opening_balance'=>$request_data->opening_balance,
                'balance'=>$request_data->opening_balance,
                'customer_id'=>$userData->id
                ]);
            
            $accounts_data = DB::table('cash_accounts')->where('customer_id',$userData->id)->get();
            
            return response()->json([
                'status'=>'success',
                'data'=>$accounts_data
            ]);
        }else{
            return response()->json([
                'status'=>'validation_error',
                'data'=>''
            ]);
        }
    }
/*
|--------------------------------------------------------------------------
| Add Cash Account Function Ended
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Add Received Payment Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to Insert the Data in Received Payment table For Our Clients.
*/
    public function payments_recv_submit(Request $request){
        $request_data   = json_decode($request->request_data);
        // dd($request_data);
        $userData       = CustomerSubcription::where('Auth_key',$request->token)->where('status',1)->select('id','status')->first();
        if($userData){
            $total_payments = 0;
            
            if(isset($request_data->company_amount)){
                foreach($request_data->company_amount as $pay_res){
                    $total_payments += $pay_res;
                }   
            }else{
                foreach($request_data->amount as $pay_res){
                    $total_payments += $pay_res;
                }
            }
            
            
            DB::beginTransaction();
            try {
                $conversion_data = [];
                $converion_data_company = [];
                $purchase_amount = [];
                $exchange_rate = [];
                $payment_date = [];
                if(isset($request_data->converion_data)){
                    $conversion_data = $request_data->converion_data;
                    $converion_data_company = $request_data->converion_data_company;
                    $purchase_amount = $request_data->purchase_amount;
                    $exchange_rate = $request_data->exchange_rate;
                    $payment_date = $request_data->payment_date;
                }
                
                // Insert Payment in Table 
                $paymentObj = new ReceviedPayments;
                
                if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
                    $paymentObj->SU_id                   = $request->SU_id;
                }
                
                $paymentObj->date               = $request_data->current_date;
                $paymentObj->prev_balance       = $request_data->account_prev_bal;
                $paymentObj->updated_balance    = $request_data->updated_balnc;
                $paymentObj->total_received     = $total_payments;
                $paymentObj->Criteria           = json_encode($request_data->criteria);
                $paymentObj->Content            = json_encode($request_data->content);
                $paymentObj->Content_Ids        = json_encode($request_data->content_ids);
                $paymentObj->Amount             = json_encode($request_data->amount);
                $paymentObj->remarks            = json_encode($request_data->remarks);
                $paymentObj->converion_data     = json_encode($conversion_data);
                $paymentObj->purchase_amount    = json_encode($purchase_amount);
                $paymentObj->exchange_rate      = json_encode($exchange_rate);
                $paymentObj->payment_date       = json_encode($payment_date);
                $paymentObj->received_from      = $request_data->payment_from;
                $paymentObj->customer_id        = $userData->id;
                $paymentObj->save();
                
                foreach($request_data->criteria as $index => $ctr_res){
                    
                    if($ctr_res == 'Agent'){
                        $agent_data = DB::table('Agents_detail')->where('id',$request_data->content_ids[$index])->select('id','balance','agent_Name')->first();
                        if(isset($request_data->purchase_amount[$index])){
                            $updatedBalance = $agent_data->balance - $request_data->amount[$index];
                        }else{
                            $updatedBalance = $agent_data->balance - $request_data->amount[$index];
                        }
                        
                        
                        $payment_date = $request_data->current_date;
                             if(isset($request_data->payment_date)){
                                $payment_date = $request_data->payment_date[$index];
                        }
                        
                        $invoice_number = '';
                        $invoice_type = '';
                        if(isset($request_data->invoice_numbers[$index]) && !empty($request_data->invoice_numbers[$index])){
                            $invoice_data = json_decode($request_data->invoice_numbers[$index]);
                            // dd($invoice_data);
                            
                            if(isset($invoice_data->invoice_no)){
                                $invoice_number = $invoice_data->invoice_no;
                                $invoice_type = 'Package invoice';
                            }
                            
                            if(isset($invoice_data->generate_id)){
                                $invoice_number = $invoice_data->generate_id;
                                $invoice_type = 'Invoice';
                            }
                            
                            DB::table('invoices_payment_recv')->insert([
                                    'invoice_type' => $invoice_type,
                                    'invoice_no' => $invoice_number,
                                    'payment_amount' => $request_data->amount[$index],
                                    'date' => $payment_date,
                                    'recevied_id' => $paymentObj->id,
                                    'remarks' => $request_data->remarks[$index]
                                ]);
                        }
                          
                        DB::table('agents_ledgers_new')->insert([
                            'SU_id'=>$request->SU_id ?? NULL,
                            'agent_id'=>$agent_data->id,
                            'payment'=>$request_data->amount[$index],
                            'sale_price'=>$request_data->purchase_amount[$index] ?? '',
                            'exchange_rate'=>$request_data->exchange_rate[$index] ?? '',
                            'balance'=>$updatedBalance,
                            'received_id'=>$paymentObj->id,
                            'customer_id'=>$userData->id,
                            'date'=>$payment_date,
                            'payment_invoice_type' => $invoice_type,
                            'payment_invoice_no' => $invoice_number,
                            'remarks'=>$request_data->remarks[$index],
                        ]);
                        DB::table('Agents_detail')->where('id',$agent_data->id)->update(['balance'=>$updatedBalance]);
                    }
                    
                    if($ctr_res == 'B2B Agent'){
                        $agent_data = DB::table('b2b_agents')->where('id',$request_data->content_ids[$index])->select('id','balance','company_name')->first();
                        if(isset($request_data->purchase_amount[$index])){
                            $updatedBalance = $agent_data->balance - $request_data->amount[$index];
                        }else{
                            $updatedBalance = $agent_data->balance - $request_data->amount[$index];
                        }
                        
                        $payment_date = $request_data->current_date;
                        if(isset($request_data->payment_date)){
                            $payment_date = $request_data->payment_date[$index];
                        }
                        
                        $invoice_number = '';
                        $invoice_type   = '';
                        if(isset($request_data->invoice_numbers[$index]) && !empty($request_data->invoice_numbers[$index])){
                            $invoice_data = json_decode($request_data->invoice_numbers[$index]);
                            
                            if(isset($invoice_data->invoice_no)){
                                $invoice_number = $invoice_data->invoice_no;
                                $invoice_type = 'Package invoice';
                            }
                            
                            if(isset($invoice_data->generate_id)){
                                $invoice_number = $invoice_data->generate_id;
                                $invoice_type = 'Invoice';
                            }
                            
                            DB::table('invoices_payment_recv')->insert([
                                'invoice_type' => $invoice_type,
                                'invoice_no' => $invoice_number,
                                'payment_amount' => $request_data->amount[$index],
                                'date' => $payment_date,
                                'recevied_id' => $paymentObj->id,
                                'remarks' => $request_data->remarks[$index]
                            ]);
                        }
                        
                        DB::table('agents_ledgers_new')->insert([
                            'SU_id'=>$request->SU_id ?? NULL,
                            'b2b_Agent_id'=>$agent_data->id,
                            'payment'=>$request_data->amount[$index],
                            'sale_price'=>$request_data->purchase_amount[$index] ?? '',
                            'exchange_rate'=>$request_data->exchange_rate[$index] ?? '',
                            'balance'=>$updatedBalance,
                            'received_id'=>$paymentObj->id,
                            'customer_id'=>$userData->id,
                            'date'=>$payment_date,
                            'payment_invoice_type' => $invoice_type,
                            'payment_invoice_no' => $invoice_number,
                            'remarks'=>$request_data->remarks[$index],
                        ]);
                        
                        DB::table('b2b_agents')->where('id',$agent_data->id)->update(['balance'=>$updatedBalance]);
                    }
                    
                    if($ctr_res == 'Customer'){
                        $customer_data  = DB::table('booking_customers')->where('id',$request_data->content_ids[$index])->select('id','balance','name')->first();
                        if(isset($request_data->purchase_amount[$index])){
                            $updatedBalance = $customer_data->balance - $request_data->amount[$index];
                        }else{
                            $updatedBalance = $customer_data->balance - $request_data->amount[$index];
                        }
                        
                        $payment_date = $request_data->current_date;
                             if(isset($request_data->payment_date)){
                                $payment_date = $request_data->payment_date[$index];
                        }
                        
                        $invoice_number = '';
                        $invoice_type = '';
                       
                        if(isset($request_data->invoice_numbers[$index]) && !empty($request_data->invoice_numbers[$index])){
                            $invoice_data = json_decode($request_data->invoice_numbers[$index]);
                            // dd($invoice_data);
                            
                            if(isset($invoice_data->invoice_no)){
                                $invoice_number = $invoice_data->invoice_no;
                                $invoice_type = 'Package invoice';
                            }
                            
                            if(isset($invoice_data->generate_id)){
                                // dd($invoice_data);
                                $invoice_number = $invoice_data->generate_id;
                                $invoice_type = 'Invoice';
                            }
                            
                            DB::table('invoices_payment_recv')->insert([
                                    'SU_id'=>$request->SU_id ?? NULL,
                                    'invoice_type' => $invoice_type,
                                    'invoice_no' => $invoice_number,
                                    'payment_amount' => $request_data->amount[$index],
                                    'date' => $payment_date,
                                    'recevied_id' => $paymentObj->id,
                                    'remarks' => $request_data->remarks[$index]
                                ]);
                        }
                        
                        // die;
                        
                        DB::table('customer_ledger')->insert([
                            'SU_id'=>$request->SU_id ?? NULL,
                            'booking_customer'=>$customer_data->id,
                            'payment'=>$request_data->amount[$index],
                            'sale_price'=>$request_data->purchase_amount[$index] ?? '',
                            'exchange_rate'=>$request_data->exchange_rate[$index] ?? '',
                            'balance'=>$updatedBalance,
                            'received_id'=>$paymentObj->id,
                            'customer_id'=>$userData->id,
                            'date'=>$payment_date,
                            'payment_invoice_type' => $invoice_type,
                            'payment_invoice_no' => $invoice_number,
                            'remarks'=>$request_data->remarks[$index],
                        ]);
                        DB::table('booking_customers')->where('id',$customer_data->id)->update(['balance'=>$updatedBalance]);
                    }
                    
                    if($ctr_res == 'Third Party Companies'){
                        $third_company_data = DB::table('3rd_party_commissions')->where('customer_id',$request_data->content_ids[$index]) ->where('third_party_id',$userData->id)->select('id','balance')->first();
                        if(isset($request_data->purchase_amount[$index])){
                            $updatedBalance     = $third_company_data->balance - $request_data->amount[$index];
                        }else{
                            $updatedBalance     = $third_company_data->balance - $request_data->amount[$index];
                        }
                        
                        
                        $payment_date = $request_data->current_date;
                              if(isset($request_data->payment_date)){
                                $payment_date = $request_data->payment_date[$index];
                        }
                          
                        DB::table('3rd_party_package_book_ledger')->insert([
                            'SU_id'=>$request->SU_id ?? NULL,
                            'package_owner'=>$request_data->content_ids[$index],
                            'package_request'=>$userData->id,
                            'recevied_id'=>$paymentObj->id,
                            'balance'=>$updatedBalance,
                            'payments'=>$request_data->amount[$index] ?? '',
                            'date'=>$payment_date,
                            'remarks'=>$request_data->remarks[$index],
                        ]);
                        DB::table('3rd_party_commissions')->where('customer_id',$request_data->content_ids[$index])->where('third_party_id',$userData->id)->update(['balance'=>$updatedBalance]);
                    }
                    
                    $per_converion_data = '';
                    $per_converion_data_comp = '';
                    $per_purchase_amount = '';
                    $per_exchange_rate = '';
                    $per_payment_date = date('Y-m-d',strtotime($paymentObj->created_at));
                    
                    if(isset($conversion_data[$index])){
                        $per_converion_data = $conversion_data[$index];
                    }
                    
                    if(isset($converion_data_company[$index])){
                        $per_converion_data_comp = $converion_data_company[$index];
                    }
                    
                    if(isset($purchase_amount[$index])){
                        $per_purchase_amount = $purchase_amount[$index];
                    }
                    
                    if(isset($exchange_rate[$index])){
                        $per_exchange_rate = $exchange_rate[$index];
                    }
                    
                    if(isset($payment_date[$index])){
                        $per_payment_date = $payment_date[$index];
                    }
                    
                    DB::table('recevied_payments_details')->insert([
                        'SU_id'                 => $request->SU_id ?? NULL,
                        'recevied_payments_id'  => $paymentObj->id,
                        'Criteria'              => $request_data->criteria[$index] ?? '',
                        'Content'               => $request_data->content[$index] ?? '',
                        'Content_Ids'           => $request_data->content_ids[$index] ?? '',
                        'Amount'                => $request_data->amount[$index] ?? '',
                        'company_conversion'    => $per_converion_data_comp,
                        'company_amount'        => $request_data->company_amount[$index] ?? '',
                        'exchange_rate_company' => $request_data->exchange_rate_company[$index] ?? '',
                        'remarks'               => $request_data->remarks[$index] ?? '',
                        'converion_data'        => $per_converion_data,
                        'purchase_amount'       => $per_purchase_amount,
                        'exchange_rate'         => $per_exchange_rate,
                        'payment_date'          => $payment_date,
                    ]);
                    
                    if($userData->id == '48'){
                        // $index                      = 0;
                        $total_Amount               = DB::table('b2b_Agent_Credit_Limits')->where('customer_Id',$userData->id)->where('b2b_Agent_Id',$request_data->content_ids[$index])->where('status','Credit_Payment')->max('total_Amount');
                        $credit_Limit_Bookings      = DB::table('b2b_Agent_Credit_Limits')->where('customer_Id',$userData->id)->where('b2b_Agent_Id',$request_data->content_ids[$index])->orderby('id','DESC')->get();
                        $remaining_Amount           = $credit_Limit_Bookings[0]->remaining_Amount;
                        $booking_Amount             = $request_data->company_amount[$index] ?? $request_data->amount[$index] ?? '0';
                        DB::table('b2b_Agent_Credit_Limits')->insert([
                            'token'                 => $request->token,
                            'customer_id'           => $userData->id,
                            'b2b_Agent_Id'          => $request_data->content_ids[$index],
                            'transaction_Id'        => NULL,
                            'booking_Amount'        => $booking_Amount,
                            'total_Amount'          => $total_Amount + $booking_Amount,
                            'remaining_Amount'      => $remaining_Amount + $booking_Amount,
                            'currency'              => $userData->currency_symbol ?? 'SAR',
                            'status'                => 'Credit_Payment',
                            'status_Type'           => 'Approved',
                            'payment_Method'        => NULL,
                            'payment_Remarks'       => NULL,
                            'services_Type'         => 'Hotel',
                            'recevied_payments_id'  => $paymentObj->id ?? '',
                        ]);
                    }
                }
                
                // Update Account Balance 
                $cash_account_data  = DB::table('cash_accounts')->where('id',$request_data->payment_from)->select('id','balance','name')->first();
                $updatedBalance     =  $cash_account_data->balance + $total_payments;
                
                DB::table('cash_accountledgers')->insert([
                    'SU_id'=>$request->SU_id ?? NULL,
                    'account_id'=>$cash_account_data->id,
                    'received'=>$total_payments,
                    'balance'=>$updatedBalance,
                    'recevied_id'=>$paymentObj->id,
                    'customer_id'=>$userData->id,
                    'date'=>date('Y-m-d'),
                ]);
                
                DB::table('cash_accounts')->where('id',$cash_account_data->id)->update(['balance'=>$updatedBalance]);
                
                DB::commit();
                
                return response()->json([
                    'status'=>'success',
                ]); 
            } catch (\PDOException $e) {
                echo $e->getMessage();
                DB::rollBack();
                return response()->json(['error'=>'Something Went Wrong Try Again']);
            }
        }else{
            return response()->json([
                'status'=>'validation_error',
                'data'=>''
            ]);
        }
    }
    
    public function addTransferAmount(Request $request){
        $request_data   = json_decode($request->request_data);
        $userData       = CustomerSubcription::where('Auth_key',$request->token)->where('status',1)->select('id','status')->first();
        if($userData){
            // dd($request_data);
            DB::beginTransaction();
            try {
                
                $fromAccounts               = DB::table('cash_accounts')->where('customer_id',$userData->id)->where('id',$request_data->fromAccount)->first();
                $toAccounts                 = DB::table('cash_accounts')->where('customer_id',$userData->id)->where('id',$request_data->toAccount)->first();
                if($request_data->accountTypeFilter == 'add'){
                    DB::table('cash_accounts')->where('customer_id',$userData->id)->where('id',$request_data->fromAccount)->update([
                        'opening_balance'   => $fromAccounts->opening_balance + $request_data->amountAddTransfer,
                        'balance'           => $fromAccounts->balance + $request_data->amountAddTransfer,
                    ]);
                    
                    $cash_accounts_remarks                      = new cash_accounts_remarks();
                    $cash_accounts_remarks->customer_id         = $userData->id;
                    $cash_accounts_remarks->cash_accounts_id    = $fromAccounts->id;
                    $cash_accounts_remarks->previousBalance     = $fromAccounts->opening_balance;
                    $cash_accounts_remarks->addBalance          = $request_data->amountAddTransfer;
                    $cash_accounts_remarks->remarks             = $request_data->remarks;
                    $cash_accounts_remarks->transactionType     = 'Add Amount';
                    $cash_accounts_remarks->accountType         = 'Current Account';
                    $cash_accounts_remarks->save();
                    
                }else if($request_data->accountTypeFilter == 'transfer'){
                    DB::table('cash_accounts')->where('customer_id',$userData->id)->where('id',$request_data->fromAccount)->update([
                        'opening_balance'   => $fromAccounts->opening_balance - $request_data->amountAddTransfer,
                        'balance'           => $fromAccounts->balance - $request_data->amountAddTransfer,
                    ]);
                    
                    $cash_accounts_remarks                      = new cash_accounts_remarks();
                    $cash_accounts_remarks->customer_id         = $userData->id;
                    $cash_accounts_remarks->cash_accounts_id    = $fromAccounts->id;
                    $cash_accounts_remarks->previousBalance     = $fromAccounts->opening_balance;
                    $cash_accounts_remarks->addBalance          = $request_data->amountAddTransfer;
                    $cash_accounts_remarks->remarks             = $request_data->remarks;
                    $cash_accounts_remarks->transactionType     = 'Transfer Amount';
                    $cash_accounts_remarks->accountType         = 'From';
                    $cash_accounts_remarks->save();
                    
                    DB::table('cash_accounts')->where('customer_id',$userData->id)->where('id',$request_data->toAccount)->update([
                        'opening_balance'   => $toAccounts->opening_balance + $request_data->amountAddTransfer,
                        'balance'           => $toAccounts->balance + $request_data->amountAddTransfer,
                    ]);
                    
                    $cash_accounts_remarks                      = new cash_accounts_remarks();
                    $cash_accounts_remarks->customer_id         = $userData->id;
                    $cash_accounts_remarks->cash_accounts_id    = $toAccounts->id;
                    $cash_accounts_remarks->previousBalance     = $toAccounts->opening_balance;
                    $cash_accounts_remarks->addBalance          = $request_data->amountAddTransfer;
                    $cash_accounts_remarks->remarks             = $request_data->remarks;
                    $cash_accounts_remarks->transactionType     = 'Transfer Amount';
                    $cash_accounts_remarks->accountType         = 'To';
                    $cash_accounts_remarks->save();
                }
                
                DB::commit();
                
                return response()->json([
                    'status'=>'success',
                ]); 
            } catch (\PDOException $e) {
                echo $e->getMessage();
                DB::rollBack();
                return response()->json(['error'=>'Something Went Wrong Try Again']);
            }
        }else{
            return response()->json([
                'status'=>'validation_error',
                'data'=>''
            ]);
        }
    }
/*
|--------------------------------------------------------------------------
| Add Received Payment Function Ended
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Add Payment Function Started
|--------------------------------------------------------------------------
| In this function, We coded the logic to Insert the Data in Payment table For Our Clients.
*/
    public function payments_add_submit(Request $request){
        $request_data   = json_decode($request->request_data);
        // dd($request_data);
        
        $userData       = CustomerSubcription::where('Auth_key',$request->token)->where('status',1)->select('id','status','currency_symbol')->first();
        // dd('STOP');
        
        // Test
        // $supplier_data  = DB::table('rooms_Invoice_Supplier')->where('id',$request_data->content_ids[0])->select('id','balance','payable')->first();
        // $updatedBalance = $supplier_data->payable - $request_data->purchase_amount[0];
        // dd($request_data->content_ids[0],$supplier_data->payable,$request_data->purchase_amount[0],$updatedBalance);
        // Test
        
        if($userData){
            $total_payments = 0;
            
            if(isset($request_data->company_amount)){
                foreach($request_data->company_amount as $pay_res){
                    $total_payments += $pay_res;
                }   
            }else{
                foreach($request_data->amount as $pay_res){
                    $total_payments += $pay_res;
                }
            }
            
            DB::beginTransaction();
                try {
                    $conversion_data            = [];
                    $converion_data_company     = [];
                    $purchase_amount            = [];
                    $exchange_rate              = [];
                    $payment_date               = [];
                    if(isset($request_data->converion_data)){
                        $conversion_data        = $request_data->converion_data;
                        $converion_data_company = $request_data->converion_data_company;
                        $purchase_amount        = $request_data->purchase_amount;
                        $exchange_rate          = $request_data->exchange_rate;
                        $payment_date           = $request_data->payment_date;
                    }
                    // Insert Payment in Table 
                    $paymentObj = new Payments;
                    
                    if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
                        $paymentObj->SU_id          = $request->SU_id;
                    }
                    
                    $paymentObj->date               = $request_data->current_date;
                    $paymentObj->prev_balance       = $request_data->account_prev_bal;
                    $paymentObj->updated_balance    = $request_data->updated_balnc;
                    $paymentObj->total_payments     = $total_payments;
                    $paymentObj->Criteria           = json_encode($request_data->criteria);
                    $paymentObj->Content            = json_encode($request_data->content);
                    $paymentObj->Content_Ids        = json_encode($request_data->content_ids);
                    $paymentObj->Amount             = json_encode($request_data->amount);
                    $paymentObj->remarks            = json_encode($request_data->remarks);
                    $paymentObj->converion_data     = json_encode($conversion_data);
                    $paymentObj->purchase_amount    = json_encode($purchase_amount);
                    $paymentObj->exchange_rate      = json_encode($exchange_rate);
                    $paymentObj->payment_date       = json_encode($payment_date);
                    $paymentObj->payment_from       = $request_data->payment_from;
                    $paymentObj->customer_id        = $userData->id;
                    $paymentObj->save();
                    
                    foreach($request_data->criteria as $index => $ctr_res){
                        if($ctr_res == 'Agent'){
                            $agent_data = DB::table('Agents_detail')->where('id',$request_data->content_ids[$index])->select('id','balance','agent_Name')->first();
                            // $AgentBal = AgentBalance::where('agent_id',$request->content_ids[$index])->first();
                            
                            if(isset($request_data->purchase_amount[$index])){
                                $updatedBalance = $agent_data->balance + $request_data->amount[$index];
                            }else{
                                $updatedBalance = $agent_data->balance + $request_data->amount[$index];
                            }
                            
                            $payment_date = $request_data->current_date;
                            if(isset($request_data->payment_date)){
                                $payment_date = $request_data->payment_date[$index];
                            }
                            
                            DB::table('agents_ledgers_new')->insert([
                                'SU_id'         => $request->SU_id ?? NULL,
                                'agent_id'      => $agent_data->id,
                                'received'      => $request_data->amount[$index],
                                'sale_price'    => $request_data->purchase_amount[$index] ?? '',
                                'exchange_rate' => $request_data->exchange_rate[$index] ?? '',
                                'balance'       => $updatedBalance,
                                'payment_id'    => $paymentObj->id,
                                'customer_id'   => $userData->id,
                                'date'          => $payment_date,
                                'remarks'       => $request_data->remarks[$index],
                            ]);
                            
                            DB::table('Agents_detail')->where('id',$agent_data->id)->update(['balance'=>$updatedBalance]);
                        }
                        
                        if($ctr_res == 'B2B Agent'){
                            $agent_data = DB::table('b2b_agents')->where('id',$request_data->content_ids[$index])->select('id','balance','company_name')->first();
                            
                            if(isset($request_data->purchase_amount[$index])){
                                $updatedBalance = $agent_data->balance + $request_data->amount[$index];
                            }else{
                                $updatedBalance = $agent_data->balance + $request_data->amount[$index];
                            }
                            
                            $payment_date = $request_data->current_date;
                            if(isset($request_data->payment_date)){
                                $payment_date = $request_data->payment_date[$index];
                            }
                            
                            DB::table('agents_ledgers_new')->insert([
                                'SU_id'         => $request->SU_id ?? NULL,
                                'b2b_Agent_id'  => $agent_data->id,
                                'received'      => $request_data->amount[$index],
                                'sale_price'    => $request_data->purchase_amount[$index] ?? '',
                                'exchange_rate' => $request_data->exchange_rate[$index] ?? '',
                                'balance'       => $updatedBalance,
                                'payment_id'    => $paymentObj->id,
                                'customer_id'   => $userData->id,
                                'date'          => $payment_date,
                                'remarks'       => $request_data->remarks[$index],
                            ]);
                            
                            DB::table('b2b_agents')->where('id',$agent_data->id)->update(['balance'=>$updatedBalance]);
                        }
                        
                        if($ctr_res == 'Customer'){
                            
                            $customer_data = DB::table('booking_customers')->where('id',$request_data->content_ids[$index])->select('id','balance','name')->first();
                            // $AgentBal = AgentBalance::where('agent_id',$request->content_ids[$index])->first();
                            
                            if(isset($request_data->purchase_amount[$index])){
                                $updatedBalance = $customer_data->balance + $request_data->amount[$index];
                            }else{
                                $updatedBalance = $customer_data->balance + $request_data->amount[$index];
                            }
                            
                            $payment_date = $request_data->current_date;
                            if(isset($request_data->payment_date)){
                                $payment_date = $request_data->payment_date[$index];
                            }
                                
                            DB::table('customer_ledger')->insert([
                                'SU_id'=>$request->SU_id ?? NULL,
                                'booking_customer'=>$customer_data->id,
                                'received'=>$request_data->amount[$index],
                                'sale_price'=>$request_data->purchase_amount[$index] ?? '',
                                'exchange_rate'=>$request_data->exchange_rate[$index] ?? '',
                                'balance'=>$updatedBalance,
                                'payment_id'=>$paymentObj->id,
                                'customer_id'=>$userData->id,
                                'date'=>$payment_date,
                                'remarks'=>$request_data->remarks[$index],
                            ]);
                            DB::table('booking_customers')->where('id',$customer_data->id)->update(['balance'=>$updatedBalance]);
                         
                        }
                        
                        if($ctr_res == 'Hotel Supplier'){
                            
                            $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$request_data->content_ids[$index])->select('id','balance','payable')->first();
                            // $AgentBal = AgentBalance::where('agent_id',$request->content_ids[$index])->first();
                            
                            if(isset($request_data->purchase_amount[$index])){
                                $updatedBalance = $supplier_data->balance - $request_data->purchase_amount[$index];
                                $updatedPayable = $supplier_data->payable - $request_data->purchase_amount[$index];
                            }else{
                                $updatedBalance = $supplier_data->balance - $request_data->amount[$index];
                                $updatedPayable = $supplier_data->payable - $request_data->amount[$index];
                            }
                            
                            $payment_date = $request_data->current_date;
                            if(isset($request_data->payment_date)){
                                $payment_date = $request_data->payment_date[$index];
                            }
                            
                            DB::table('hotel_supplier_ledger')->insert([
                                'SU_id'             => $request->SU_id ?? NULL,
                                'supplier_id'       => $supplier_data->id,
                                'received'          => $request_data->amount[$index],
                                'sale_price'        => $request_data->purchase_amount[$index] ?? '',
                                'exchange_rate'     => $request_data->exchange_rate[$index] ?? '',
                                'balance'           => $updatedBalance,
                                'payable_balance'   => $updatedPayable,
                                'payment_id'        => $paymentObj->id,
                                'customer_id'       => $userData->id,
                                'date'              => $payment_date,
                                'remarks'           => $request_data->remarks[$index],
                            ]);
                            
                            // DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$updatedBalance,'payable'=>$updatedPayable]);
                            DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['payable'=>$updatedPayable]);
                            
                        }
                        
                        if($ctr_res == 'Flight Supplier'){
                            
                             $supplier_data = DB::table('supplier')->where('id',$request_data->content_ids[$index])->select('id','balance')->first();
                            // $AgentBal = AgentBalance::where('agent_id',$request->content_ids[$index])->first();

                            if(isset($request_data->purchase_amount[$index])){
                                $updatedBalance = $supplier_data->balance - $request_data->amount[$index];
                            }else{
                                $updatedBalance = $supplier_data->balance - $request_data->amount[$index];
                            }

                                $payment_date = $request_data->current_date;
                                  if(isset($request_data->payment_date)){
                                    $payment_date = $request_data->payment_date[$index];
                              }
                            
                             
                                    DB::table('flight_supplier_ledger')->insert([
                                        'SU_id'=>$request->SU_id ?? NULL,
                                        'supplier_id'=>$supplier_data->id,
                                        'received'=>$request_data->amount[$index],
                                        'sale_price'=>$request_data->purchase_amount[$index] ?? '',
                                        'exchange_rate'=>$request_data->exchange_rate[$index] ?? '',
                                        'balance'=>$updatedBalance,
                                        'payment_id'=>$paymentObj->id,
                                        'customer_id'=>$userData->id,
                                        'date'=>$payment_date,
                                        'remarks'=>$request_data->remarks[$index],
                                        ]);
                            
                                    DB::table('supplier')->where('id',$supplier_data->id)->update(['balance'=>$updatedBalance]);
                         
                        }
                        
                        if($ctr_res == 'Transfer Supplier'){
                            
                             $supplier_data = DB::table('transfer_Invoice_Supplier')->where('id',$request_data->content_ids[$index])->select('id','balance')->first();
                            // $AgentBal = AgentBalance::where('agent_id',$request->content_ids[$index])->first();

                            if(isset($request_data->purchase_amount[$index])){
                                $updatedBalance = $supplier_data->balance - $request_data->amount[$index];
                            }else{
                                $updatedBalance = $supplier_data->balance - $request_data->amount[$index];
                            }

                            
                              $payment_date = $request_data->current_date;
                                 if(isset($request_data->payment_date)){
                                    $payment_date = $request_data->payment_date[$index];
                              }
                             
                                    DB::table('transfer_supplier_ledger')->insert([
                                        'SU_id'=>$request->SU_id ?? NULL,
                                        'supplier_id'=>$supplier_data->id,
                                        'received'=>$request_data->amount[$index] ?? $request_data->amount[$index],
                                        'sale_price'=>$request_data->purchase_amount[$index] ?? '',
                                        'exchange_rate'=>$request_data->exchange_rate[$index] ?? '',
                                        'balance'=>$updatedBalance,
                                        'payment_id'=>$paymentObj->id,
                                        'customer_id'=>$userData->id,
                                        'date'=>$payment_date,
                                        'remarks'=>$request_data->remarks[$index],
                                        ]);
                            
                                    DB::table('transfer_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$updatedBalance]);
                         
                        }
                        
                        if($ctr_res == 'Visa Supplier'){
                            $supplier_data = DB::table('visa_Sup')->where('id',$request_data->content_ids[$index])->select('id','balance','payable')->first();
                            
                            if(isset($request_data->purchase_amount[$index])){
                                $updatedBalance = $supplier_data->balance - $request_data->amount[$index];
                                $updatedPayable = $supplier_data->payable - $request_data->amount[$index];
                            }else{
                                $updatedBalance = $supplier_data->balance - $request_data->amount[$index];
                                $updatedPayable = $supplier_data->payable - $request_data->amount[$index];
                            }
                            
                            $payment_date = $request_data->current_date;
                            if(isset($request_data->payment_date)){
                                $payment_date = $request_data->payment_date[$index];
                            }
                             
                            DB::table('visa_supplier_ledger')->insert([
                                'SU_id'         => $request->SU_id ?? NULL,
                                'supplier_id'   => $supplier_data->id,
                                'received'      => $request_data->amount[$index] ?? $request_data->amount[$index],
                                'sale_price'    => $request_data->purchase_amount[$index] ?? '',
                                'exchange_rate' => $request_data->exchange_rate[$index] ?? '',
                                'balance'       => $updatedBalance,
                                'payable'       => $updatedPayable,
                                'payment_id'    => $paymentObj->id,
                                'customer_id'   => $userData->id,
                                'date'          => $payment_date,
                                'remarks'       => $request_data->remarks[$index],
                            ]);
                            DB::table('visa_Sup')->where('id',$supplier_data->id)->update(['balance'=>$updatedBalance,'payable' => $updatedPayable]);
                        }
                        
                        if($ctr_res == 'Third Party Companies'){
                            
                             $third_company_data = DB::table('3rd_party_commissions')->where('customer_id',$userData->id)
                                                                                ->where('third_party_id',$request_data->content_ids[$index])->select('id','balance')->first();
                            // $AgentBal = AgentBalance::where('agent_id',$request->content_ids[$index])->first();

                            if(isset($request_data->purchase_amount[$index])){
                                $updatedBalance = $third_company_data->balance - $request_data->amount[$index];
                            }else{
                                $updatedBalance = $third_company_data->balance - $request_data->amount[$index];
                            }

                              $payment_date = $request_data->current_date;
                                  if(isset($request_data->payment_date)){
                                    $payment_date = $request_data->payment_date[$index];
                              }
                            
                             
                                    DB::table('3rd_party_package_book_ledger')->insert([
                                        'SU_id'=>$request->SU_id ?? NULL,
                                        'package_owner'=>$userData->id,
                                        'package_request'=>$request_data->content_ids[$index],
                                        'payment_id'=>$paymentObj->id,
                                        'balance'=>$updatedBalance,
                                        'payments'=>$request_data->purchase_amount[$index] ?? $request_data->amount[$index],
                                        'sale_price'=>$request_data->amount[$index] ?? '',
                                        'exchange_rate'=>$request_data->exchange_rate[$index] ?? '',
                                        'date'=>$payment_date,
                                        'remarks'=>$request_data->remarks[$index],
                                        ]);
                            
                                    DB::table('3rd_party_commissions')->where('customer_id',$userData->id)
                                                                        ->where('third_party_id',$request_data->content_ids[$index])
                                                                        ->update(['balance'=>$updatedBalance]);
                         
                        }
                        
                        $per_converion_data         = '';
                        $per_purchase_amount        = '';
                        $per_converion_data_comp    = '';
                        $per_exchange_rate          = '';
                        $per_payment_date           = date('Y-m-d',strtotime($paymentObj->created_at));
                        
                        if(isset($conversion_data[$index])){
                            $per_converion_data     = $conversion_data[$index];
                        }
                        
                        if(isset($converion_data_company[$index])){
                            $per_converion_data_comp = $converion_data_company[$index];
                        }
                        
                        if(isset($purchase_amount[$index])){
                            $per_purchase_amount    = $purchase_amount[$index];
                        }
                        
                        if(isset($exchange_rate[$index])){
                            $per_exchange_rate      = $exchange_rate[$index];
                        }
                        
                        if(isset($payment_date[$index])){
                            $per_payment_date       = $payment_date[$index];
                        }
                        
                        DB::table('make_payments_details')->insert([
                            'SU_id'                 => $request->SU_id ?? NULL,
                            'make_payments_id'      => $paymentObj->id,
                            'Criteria'              => $request_data->criteria[$index] ?? '',
                            'Content'               => $request_data->content[$index] ?? '',
                            'Content_Ids'           => $request_data->content_ids[$index] ?? '',
                            'Amount'                => $request_data->amount[$index] ?? '',
                            'company_conversion'    => $per_converion_data_comp,
                            'company_amount'        => $request_data->company_amount[$index] ?? '',
                            'exchange_rate_company' => $request_data->exchange_rate_company[$index] ?? '',
                            'remarks'               => $request_data->remarks[$index] ?? '',
                            'converion_data'        => $per_converion_data,
                            'purchase_amount'       => $per_purchase_amount,
                            'exchange_rate'         => $per_exchange_rate,
                            'payment_date'          => $payment_date,
                        ]);
                        
                        // if($userData->id == '48'){
                        //     // $index                      = 0;
                        //     $total_Amount               = DB::table('b2b_Agent_Credit_Limits')->where('customer_Id',$userData->id)->where('b2b_Agent_Id',$request_data->content_ids[$index])->where('status','Credit_Payment')->max('total_Amount');
                        //     $credit_Limit_Bookings      = DB::table('b2b_Agent_Credit_Limits')->where('customer_Id',$userData->id)->where('b2b_Agent_Id',$request_data->content_ids[$index])->orderby('id','DESC')->get();
                        //     $remaining_Amount           = $credit_Limit_Bookings[0]->remaining_Amount;
                        //     $booking_Amount             = $per_purchase_amount ?? $request_data->amount[$index] ?? '0';
                        //     DB::table('b2b_Agent_Credit_Limits')->insert([
                        //         'token'                 => $request->token,
                        //         'customer_id'           => $userData->id,
                        //         'b2b_Agent_Id'          => $request_data->content_ids[$index],
                        //         'transaction_Id'        => NULL,
                        //         'booking_Amount'        => $booking_Amount,
                        //         'total_Amount'          => $total_Amount + $booking_Amount,
                        //         'remaining_Amount'      => $remaining_Amount + $booking_Amount,
                        //         'currency'              => $userData->currency_symbol ?? 'SAR',
                        //         'status'                => 'Credit_Payment',
                        //         'status_Type'           => 'Pending',
                        //         'payment_Method'        => NULL,
                        //         'payment_Remarks'       => NULL,
                        //         'services_Type'         => 'Hotel',
                        //     ]);
                        // }
                     
                    }
                    // Update Account Balance 
                    
                    $cash_account_data  = DB::table('cash_accounts')->where('id',$request_data->payment_from)->select('id','balance','name')->first();
                    $updatedBalance     = $cash_account_data->balance - $total_payments;
                    
                    DB::table('cash_accountledgers')->insert([
                        'SU_id'         => $request->SU_id ?? NULL,
                        'account_id'    => $cash_account_data->id,
                        'payment'       => $total_payments,
                        'balance'       => $updatedBalance,
                        'payment_id'    => $paymentObj->id,
                        'customer_id'   => $userData->id,
                        'date'          => $request_data->current_date,
                    ]);
                    DB::table('cash_accounts')->where('id',$cash_account_data->id)->update(['balance'=>$updatedBalance]);
                    
                    DB::commit();
                    return response()->json(['status'=>'success']);
                    
                } catch (\PDOException $e) {
                    // Woopsy
                    echo $e->getMessage();
                    DB::rollBack();
                    return response()->json(['status'=>'error']);
                }
        }else{
            return response()->json([
                'status'=>'validation_error',
                'data'=>''
            ]);
        }
    }
/*
|--------------------------------------------------------------------------
| Add Payment Function Ended
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| CashAccControllerApi Function Ended
|--------------------------------------------------------------------------
*/
}