<?php
namespace App\Http\Controllers\frontend\user_dashboard;

use Illuminate\Support\Collection;

use Illuminate\Http\Request;

Use Illuminate\Support\Facades\Input as input;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

use App\Http\Controllers\Controller;

use Hash;

use Auth;

use DB;

use App\User;
use App\Models\Conversation;

use App\Index;

use App\About_us;

use App\Models\Lead_Passenger_location;

// use App\Contact_us;

use App\Blog;

use App\Package;

use App\Gallery;

use App\Ourteam;

use App\WhyChoose;

use App\Models\Booking;

use App\Models\Agent;

use App\Models\Ticket;

use App\Mail\SendAgentMail;
use Webpatser\Uuid\Uuid;

use Session;
use App\Models\CustomerSubcription\CustomerSubcription;

class B2BUserController extends Controller
{
    public function b2b_Top_Agents_Booking(Request $request){
        // dd('Test');
        $agentallBookingsObject = [];
        $userData               = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        $hotels_bookings        = DB::table('b2b_agents')->where('b2b_agents.token',$request->token)
                                    ->leftJoin('hotels_bookings','b2b_agents.id','hotels_bookings.b2b_agent_id')
                                    ->select('b2b_agents.id','b2b_agents.agent_Refrence_No', 'b2b_agents.first_name',
                                        'b2b_agents.last_name','b2b_agents.company_name','b2b_agents.balance','b2b_agents.currency',
                                        DB::raw('COUNT(hotels_bookings.id) as b2b_Hotel_Bookings'),
                                        DB::raw('SUM(hotels_bookings.exchange_price) as b2b_Total_Price'))
                                    ->groupBy('b2b_agents.id')->orderBy('b2b_agents.id','asc')->get();
        foreach($hotels_bookings as $val_HB){
            $payment_Today                  = DB::table('agents_ledgers_new')->where('b2b_Agent_id',$val_HB->id)->where('received_id','!=',NULL)->sum('payment');
            $total_Price                    = $val_HB->b2b_Total_Price;
            $first_name                     = $val_HB->first_name ?? '';
            $last_name                      = $val_HB->last_name ?? '';
            $agentallBookingsObject[]       = (Object)[
                'agent_id'                  => $val_HB->id,
                'agent_Name'                => $first_name.' '.$last_name ?? '',
                'company_name'              => $val_HB->company_name,
                'currency'                  => $val_HB->currency,
                'Invoices_booking'          => $val_HB->b2b_Hotel_Bookings,
                'Invoices_prices_sum'       => number_format($val_HB->b2b_Total_Price,2),
                'packages_booking'          => 0,
                'packages_prices_sum'       => 0,
                'all_bookings'              => $val_HB->b2b_Hotel_Bookings,
                'all_bookings_num_format'   => number_format($val_HB->b2b_Hotel_Bookings),
                'total_prices'              => $val_HB->b2b_Total_Price,
                'total_prices_num_format'   => number_format($val_HB->b2b_Total_Price,2),
                'Paid'                      => number_format($payment_Today,2),
                'remaining'                 => number_format($total_Price - $payment_Today,2)
            ];
        }
        
        $agentallBookingsObject = new Collection($agentallBookingsObject);
        $agentallBookingsObject = $agentallBookingsObject->sortByDesc('total_prices');
        $agentallBookingsObject = $agentallBookingsObject->values();
        $agentallBookingsObject = $agentallBookingsObject->toArray();
        
        // dd($agentallBookingsObject);
        
        if(sizeOf($agentallBookingsObject) >= 4){
            $limitedAgentData   = array_slice($agentallBookingsObject, 0, 4);
        }else{
            $limitedAgentData   = $limitedAgentData;
        }
        
        $series_data            = [];
        $categories_data        = [];
        $currentYear            = date('Y');
        $monthsData             = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $startOfMonth       = Carbon::create($currentYear, $month, 1)->startOfMonth();
            $endOfMonth         = Carbon::create($currentYear, $month, 1)->endOfMonth();
            $categories_data[]  = $startOfMonth->format('F');
            $startOfMonth       = $startOfMonth->format('Y-m-d');
            $endOfMonth         = $endOfMonth->format('Y-m-d');
            
            $monthsData[] = (Object)[
                'month'         => $month,
                'start_date'    => $startOfMonth,
                'end_date'      => $endOfMonth,
            ];
        }
        
        foreach($limitedAgentData as $agent_res){
            $agent_booking_data = [];
            foreach($monthsData as $month_res){
                $agentsInvoices         = DB::table('hotels_bookings')->where('b2b_agent_id',$agent_res->agent_id)
                                            ->whereDate('hotels_bookings.created_at','>=', $month_res->start_date)
                                            ->whereDate('hotels_bookings.created_at','<=', $month_res->end_date)
                                            ->Sum('exchange_price');
                $total_booking_price    = $agentsInvoices;
                $agent_booking_data[]   = floor($total_booking_price * 100) / 100;
               
            }
            $series_data[]              = [
                'name'                  => $agent_res->agent_Name,
                'data'                  => $agent_booking_data
            ];
        }
        
        return response()->json([
            'status'            => 'success',
            'data'              => $agentallBookingsObject,
            'series_data'       => $series_data,
            'categories_data'   => $categories_data,
        ]);
    }
    
    public function b2b_Agent_booking_filter(Request $request){
        $userData               = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        $request_data           = json_decode($request->request_data);
        $agentallBookingsObject = [];
        $agent_Groups           = [];
        
        if($request_data->date_type == 'all_data'){
            $hotels_bookings                    = DB::table('b2b_agents')->where('b2b_agents.token',$request->token)
                                                    ->leftJoin('hotels_bookings','b2b_agents.id','hotels_bookings.b2b_agent_id')
                                                    ->select('b2b_agents.id','b2b_agents.agent_Refrence_No', 'b2b_agents.first_name',
                                                        'b2b_agents.last_name','b2b_agents.company_name','b2b_agents.balance','b2b_agents.currency',
                                                        DB::raw('COUNT(hotels_bookings.id) as b2b_Hotel_Bookings'),
                                                        DB::raw('SUM(hotels_bookings.exchange_price) as b2b_Total_Price'))
                                                    ->groupBy('b2b_agents.id')
                                                    ->orderBy('b2b_agents.id','asc')
                                                    ->get();
                                                    // dd($hotels_bookings);
            foreach($hotels_bookings as $val_HB){
                $payment_Today                  = DB::table('agents_ledgers_new')->where('b2b_Agent_id',$val_HB->id)->where('received_id','!=',NULL)->sum('payment');
                $total_Price                    = $val_HB->b2b_Total_Price;
                $first_name                     = $val_HB->first_name ?? '';
                $last_name                      = $val_HB->last_name ?? '';
                $agentallBookingsObject[]       = (Object)[
                    'agent_id'                  => $val_HB->id,
                    'agent_Name'                => $first_name.' '.$last_name ?? '',
                    'company_name'              => $val_HB->company_name,
                    'currency'                  => $val_HB->currency,
                    'Invoices_booking'          => $val_HB->b2b_Hotel_Bookings,
                    'Invoices_prices_sum'       => number_format($val_HB->b2b_Total_Price,2),
                    'packages_booking'          => 0,
                    'packages_prices_sum'       => 0,
                    'all_bookings'              => $val_HB->b2b_Hotel_Bookings,
                    'all_bookings_num_format'   => number_format($val_HB->b2b_Hotel_Bookings),
                    'total_prices'              => $val_HB->b2b_Total_Price,
                    'total_prices_num_format'   => number_format($val_HB->b2b_Total_Price,2),
                    'Paid'                      => number_format($payment_Today,2),
                    'remaining'                 => number_format($total_Price - $payment_Today,2)
                ];
            }
        }
        
        if($request_data->date_type == 'data_today_wise'){
            $today_date                         = date('Y-m-d');
            $hotels_bookings                    = DB::table('b2b_agents')->where('b2b_agents.token',$request->token)
                                                    ->leftJoin('hotels_bookings', function ($join) use($today_date) {
                                                        $join->on('hotels_bookings.b2b_agent_id', '=', 'b2b_agents.id')
                                                            ->whereDate('hotels_bookings.created_at', $today_date);
                                                        })
                                                    ->select('b2b_agents.id','b2b_agents.agent_Refrence_No', 'b2b_agents.first_name',
                                                        'b2b_agents.last_name','b2b_agents.company_name','b2b_agents.balance','b2b_agents.currency',
                                                        DB::raw('COUNT(hotels_bookings.id) as b2b_Hotel_Bookings'),
                                                        DB::raw('SUM(hotels_bookings.exchange_price) as b2b_Total_Price'))
                                                    ->groupBy('b2b_agents.id')->orderBy('b2b_agents.id','asc')->get();
            foreach($hotels_bookings as $val_HB){
                $payment_Today                  = DB::table('agents_ledgers_new')->where('b2b_Agent_id',$val_HB->id)->where('received_id','!=',NULL)->sum('payment');
                $total_Price                    = $val_HB->b2b_Total_Price;
                $first_name                     = $val_HB->first_name ?? '';
                $last_name                      = $val_HB->last_name ?? '';
                $agentallBookingsObject[]       = (Object)[
                    'agent_id'                  => $val_HB->id,
                    'agent_Name'                => $first_name.' '.$last_name ?? '',
                    'company_name'              => $val_HB->company_name,
                    'currency'                  => $val_HB->currency,
                    'Invoices_booking'          => $val_HB->b2b_Hotel_Bookings,
                    'Invoices_prices_sum'       => number_format($val_HB->b2b_Total_Price,2),
                    'packages_booking'          => 0,
                    'packages_prices_sum'       => 0,
                    'all_bookings'              => $val_HB->b2b_Hotel_Bookings,
                    'all_bookings_num_format'   => number_format($val_HB->b2b_Hotel_Bookings),
                    'total_prices'              => $val_HB->b2b_Total_Price,
                    'total_prices_num_format'   => number_format($val_HB->b2b_Total_Price,2),
                    'Paid'                      => number_format($payment_Today,2),
                    'remaining'                 => number_format($total_Price - $payment_Today,2)
                ];
            }
        }
        
        if($request_data->date_type == 'data_Yesterday_wise'){
            $date                               = date('Y-m-d',strtotime("-1 days"));
            $hotels_bookings                    = DB::table('b2b_agents')->where('b2b_agents.token',$request->token)
                                                    ->leftJoin('hotels_bookings', function ($join) use($date) {
                                                        $join->on('hotels_bookings.b2b_agent_id', '=', 'b2b_agents.id')
                                                            ->whereDate('hotels_bookings.created_at', $date);
                                                        })
                                                    ->select('b2b_agents.id','b2b_agents.agent_Refrence_No', 'b2b_agents.first_name',
                                                        'b2b_agents.last_name','b2b_agents.company_name','b2b_agents.balance','b2b_agents.currency',
                                                        DB::raw('COUNT(hotels_bookings.id) as b2b_Hotel_Bookings'),
                                                        DB::raw('SUM(hotels_bookings.exchange_price) as b2b_Total_Price'))
                                                    ->groupBy('b2b_agents.id')->orderBy('b2b_agents.id','asc')->get();
            foreach($hotels_bookings as $val_HB){
                $payment_Today                  = DB::table('agents_ledgers_new')->where('b2b_Agent_id',$val_HB->id)->where('received_id','!=',NULL)->sum('payment');
                $total_Price                    = $val_HB->b2b_Total_Price;
                $first_name                     = $val_HB->first_name ?? '';
                $last_name                      = $val_HB->last_name ?? '';
                $agentallBookingsObject[]       = (Object)[
                    'agent_id'                  => $val_HB->id,
                    'agent_Name'                => $first_name.' '.$last_name ?? '',
                    'company_name'              => $val_HB->company_name,
                    'currency'                  => $val_HB->currency,
                    'Invoices_booking'          => $val_HB->b2b_Hotel_Bookings,
                    'Invoices_prices_sum'       => number_format($val_HB->b2b_Total_Price,2),
                    'packages_booking'          => 0,
                    'packages_prices_sum'       => 0,
                    'all_bookings'              => $val_HB->b2b_Hotel_Bookings,
                    'all_bookings_num_format'   => number_format($val_HB->b2b_Hotel_Bookings),
                    'total_prices'              => $val_HB->b2b_Total_Price,
                    'total_prices_num_format'   => number_format($val_HB->b2b_Total_Price,2),
                    'Paid'                      => number_format($payment_Today,2),
                    'remaining'                 => number_format($total_Price - $payment_Today,2)
                ];
            }
        }
        
        if($request_data->date_type == 'data_week_wise'){
            $startOfWeek                        = Carbon::now()->startOfWeek();
            $start_date                         = $startOfWeek->format('Y-m-d');
            $end_date                           = date('Y-m-d');
            $hotels_bookings                    = DB::table('b2b_agents')->where('b2b_agents.token',$request->token)
                                                    ->leftJoin('hotels_bookings', function ($join) use($start_date,$end_date) {
                                                        $join->on('hotels_bookings.b2b_agent_id', '=', 'b2b_agents.id')
                                                            ->whereDate('hotels_bookings.created_at','>=', $start_date)
                                                            ->whereDate('hotels_bookings.created_at','<=', $end_date);
                                                        })
                                                    ->select('b2b_agents.id','b2b_agents.agent_Refrence_No', 'b2b_agents.first_name',
                                                        'b2b_agents.last_name','b2b_agents.company_name','b2b_agents.balance','b2b_agents.currency',
                                                        DB::raw('COUNT(hotels_bookings.id) as b2b_Hotel_Bookings'),
                                                        DB::raw('SUM(hotels_bookings.exchange_price) as b2b_Total_Price'))
                                                    ->groupBy('b2b_agents.id')->orderBy('b2b_agents.id','asc')->get();
                                                    // dd($hotels_bookings);
            foreach($hotels_bookings as $val_HB){
                $payment_Today                  = DB::table('agents_ledgers_new')->where('b2b_Agent_id',$val_HB->id)->where('received_id','!=',NULL)->sum('payment');
                $total_Price                    = $val_HB->b2b_Total_Price;
                $first_name                     = $val_HB->first_name ?? '';
                $last_name                      = $val_HB->last_name ?? '';
                $agentallBookingsObject[]       = (Object)[
                    'agent_id'                  => $val_HB->id,
                    'agent_Name'                => $first_name.' '.$last_name ?? '',
                    'company_name'              => $val_HB->company_name,
                    'currency'                  => $val_HB->currency,
                    'Invoices_booking'          => $val_HB->b2b_Hotel_Bookings,
                    'Invoices_prices_sum'       => number_format($val_HB->b2b_Total_Price,2),
                    'packages_booking'          => 0,
                    'packages_prices_sum'       => 0,
                    'all_bookings'              => $val_HB->b2b_Hotel_Bookings,
                    'all_bookings_num_format'   => number_format($val_HB->b2b_Hotel_Bookings),
                    'total_prices'              => $val_HB->b2b_Total_Price,
                    'total_prices_num_format'   => number_format($val_HB->b2b_Total_Price,2),
                    'Paid'                      => number_format($payment_Today,2),
                    'remaining'                 => number_format($total_Price - $payment_Today,2)
                ];
            }
        }
        
        if($request_data->date_type == 'data_month_wise'){
            $startOfMonth                       = Carbon::now()->startOfMonth();
            $start_date                         = $startOfMonth->format('Y-m-d');
            $end_date                           = date('Y-m-d');
            $hotels_bookings                    = DB::table('b2b_agents')->where('b2b_agents.token',$request->token)
                                                    ->leftJoin('hotels_bookings', function ($join) use($start_date,$end_date) {
                                                        $join->on('hotels_bookings.b2b_agent_id', '=', 'b2b_agents.id')
                                                            ->whereDate('hotels_bookings.created_at','>=', $start_date)
                                                            ->whereDate('hotels_bookings.created_at','<=', $end_date);
                                                        })
                                                    ->select('b2b_agents.id','b2b_agents.agent_Refrence_No', 'b2b_agents.first_name',
                                                        'b2b_agents.last_name','b2b_agents.company_name','b2b_agents.balance','b2b_agents.currency',
                                                        DB::raw('COUNT(hotels_bookings.id) as b2b_Hotel_Bookings'),
                                                        DB::raw('SUM(hotels_bookings.exchange_price) as b2b_Total_Price'))
                                                    ->groupBy('b2b_agents.id')->orderBy('b2b_agents.id','asc')->get();
                                                    // dd($hotels_bookings);
            foreach($hotels_bookings as $val_HB){
                $payment_Today                  = DB::table('agents_ledgers_new')->where('b2b_Agent_id',$val_HB->id)->where('received_id','!=',NULL)->sum('payment');
                $total_Price                    = $val_HB->b2b_Total_Price;
                $first_name                     = $val_HB->first_name ?? '';
                $last_name                      = $val_HB->last_name ?? '';
                $agentallBookingsObject[]       = (Object)[
                    'agent_id'                  => $val_HB->id,
                    'agent_Name'                => $first_name.' '.$last_name ?? '',
                    'company_name'              => $val_HB->company_name,
                    'currency'                  => $val_HB->currency,
                    'Invoices_booking'          => $val_HB->b2b_Hotel_Bookings,
                    'Invoices_prices_sum'       => number_format($val_HB->b2b_Total_Price,2),
                    'packages_booking'          => 0,
                    'packages_prices_sum'       => 0,
                    'all_bookings'              => $val_HB->b2b_Hotel_Bookings,
                    'all_bookings_num_format'   => number_format($val_HB->b2b_Hotel_Bookings),
                    'total_prices'              => $val_HB->b2b_Total_Price,
                    'total_prices_num_format'   => number_format($val_HB->b2b_Total_Price,2),
                    'Paid'                      => number_format($payment_Today,2),
                    'remaining'                 => number_format($total_Price - $payment_Today,2)
                ];
            }
        }
        
        if($request_data->date_type == 'data_year_wise'){
            $startOfYear                        = Carbon::now()->startOfYear();
            $start_date                         = $startOfYear->format('Y-m-d');
            $end_date                           = date('Y-m-d');
            $hotels_bookings                    = DB::table('b2b_agents')->where('b2b_agents.token',$request->token)
                                                    ->leftJoin('hotels_bookings', function ($join) use($start_date,$end_date) {
                                                        $join->on('hotels_bookings.b2b_agent_id', '=', 'b2b_agents.id')
                                                            ->whereDate('hotels_bookings.created_at','>=', $start_date)
                                                            ->whereDate('hotels_bookings.created_at','<=', $end_date);
                                                        })
                                                    ->select('b2b_agents.id','b2b_agents.agent_Refrence_No', 'b2b_agents.first_name',
                                                        'b2b_agents.last_name','b2b_agents.company_name','b2b_agents.balance','b2b_agents.currency',
                                                        DB::raw('COUNT(hotels_bookings.id) as b2b_Hotel_Bookings'),
                                                        DB::raw('SUM(hotels_bookings.exchange_price) as b2b_Total_Price'))
                                                    ->groupBy('b2b_agents.id')->orderBy('b2b_agents.id','asc')->get();
            foreach($hotels_bookings as $val_HB){
                $payment_Today                  = DB::table('agents_ledgers_new')->where('b2b_Agent_id',$val_HB->id)->where('received_id','!=',NULL)->sum('payment');
                $total_Price                    = $val_HB->b2b_Total_Price;
                $first_name                     = $val_HB->first_name ?? '';
                $last_name                      = $val_HB->last_name ?? '';
                $agentallBookingsObject[]       = (Object)[
                    'agent_id'                  => $val_HB->id,
                    'agent_Name'                => $first_name.' '.$last_name ?? '',
                    'company_name'              => $val_HB->company_name,
                    'currency'                  => $val_HB->currency,
                    'Invoices_booking'          => $val_HB->b2b_Hotel_Bookings,
                    'Invoices_prices_sum'       => number_format($val_HB->b2b_Total_Price,2),
                    'packages_booking'          => 0,
                    'packages_prices_sum'       => 0,
                    'all_bookings'              => $val_HB->b2b_Hotel_Bookings,
                    'all_bookings_num_format'   => number_format($val_HB->b2b_Hotel_Bookings),
                    'total_prices'              => $val_HB->b2b_Total_Price,
                    'total_prices_num_format'   => number_format($val_HB->b2b_Total_Price,2),
                    'Paid'                      => number_format($payment_Today,2),
                    'remaining'                 => number_format($total_Price - $payment_Today,2)
                ];
            }
        }
        
        if($request_data->date_type == 'data_wise'){
            $start_date                         = $request_data->start_date;
            $end_date                           = $request_data->end_date;
            $hotels_bookings                    = DB::table('b2b_agents')->where('b2b_agents.token',$request->token)
                                                    ->leftJoin('hotels_bookings', function ($join) use($start_date,$end_date) {
                                                        $join->on('hotels_bookings.b2b_agent_id', '=', 'b2b_agents.id')
                                                            ->whereDate('hotels_bookings.created_at','>=', $start_date)
                                                            ->whereDate('hotels_bookings.created_at','<=', $end_date);
                                                        })
                                                    ->select('b2b_agents.id','b2b_agents.agent_Refrence_No', 'b2b_agents.first_name',
                                                        'b2b_agents.last_name','b2b_agents.company_name','b2b_agents.balance','b2b_agents.currency',
                                                        DB::raw('COUNT(hotels_bookings.id) as b2b_Hotel_Bookings'),
                                                        DB::raw('SUM(hotels_bookings.exchange_price) as b2b_Total_Price'))
                                                    ->groupBy('b2b_agents.id')->orderBy('b2b_agents.id','asc')->get();
            foreach($hotels_bookings as $val_HB){
                $payment_Today                  = DB::table('agents_ledgers_new')->where('b2b_Agent_id',$val_HB->id)->where('received_id','!=',NULL)->sum('payment');
                $total_Price                    = $val_HB->b2b_Total_Price;
                $first_name                     = $val_HB->first_name ?? '';
                $last_name                      = $val_HB->last_name ?? '';
                $agentallBookingsObject[]       = (Object)[
                    'agent_id'                  => $val_HB->id,
                    'agent_Name'                => $first_name.' '.$last_name ?? '',
                    'company_name'              => $val_HB->company_name,
                    'currency'                  => $val_HB->currency,
                    'Invoices_booking'          => $val_HB->b2b_Hotel_Bookings,
                    'Invoices_prices_sum'       => number_format($val_HB->b2b_Total_Price,2),
                    'packages_booking'          => 0,
                    'packages_prices_sum'       => 0,
                    'all_bookings'              => $val_HB->b2b_Hotel_Bookings,
                    'all_bookings_num_format'   => number_format($val_HB->b2b_Hotel_Bookings),
                    'total_prices'              => $val_HB->b2b_Total_Price,
                    'total_prices_num_format'   => number_format($val_HB->b2b_Total_Price,2),
                    'Paid'                      => number_format($payment_Today,2),
                    'remaining'                 => number_format($total_Price - $payment_Today,2)
                ];
            }
        }
        
        if($request_data->filter_type == 'total_revenue'){
            
            $agentallBookingsObject = new Collection($agentallBookingsObject);
            
            $agentallBookingsObject = $agentallBookingsObject->sortByDesc('total_prices');
            
            // Reindex the collection starting from 0
            $agentallBookingsObject = $agentallBookingsObject->values();
            
            $agentallBookingsObject = $agentallBookingsObject->toArray();
         
            if(sizeOf($agentallBookingsObject) >= 4){
                $limitedAgentData = array_slice($agentallBookingsObject, 0, 4);
            }else{
                if(count($agentallBookingsObject) > 0){
                    $slice_V            = count($agentallBookingsObject);
                    $limitedAgentData   = array_slice($agentallBookingsObject, 0, $slice_V);
                }else{
                    $limitedAgentData = [];
                }
            }
            
            $series_data = [];
            $categories_data = [];
            
            // Generate Graph Data All
            if($request_data->date_type == 'all_data'){
                $currentYear            = date('Y');
                $monthsData             = [];
                for ($month = 1; $month <= 12; $month++) {
                    $startOfMonth       = Carbon::create($currentYear, $month, 1)->startOfMonth();
                    $endOfMonth         = Carbon::create($currentYear, $month, 1)->endOfMonth();
                    $categories_data[]  = $startOfMonth->format('F');
                    $startOfMonth       = $startOfMonth->format('Y-m-d');
                    $endOfMonth         = $endOfMonth->format('Y-m-d');
                    $monthsData[]       = (Object)[
                        'month'         => $month,
                        'start_date'    => $startOfMonth,
                        'end_date'      => $endOfMonth,
                    ];
                }
                
                foreach($limitedAgentData as $agent_res){
                    $agent_booking_data = [];
                    foreach($monthsData as $month_res){
                        $agentsInvoices         = DB::table('hotels_bookings')->where('b2b_agent_id',$agent_res->agent_id)
                                                    ->whereDate('hotels_bookings.created_at','>=', $month_res->start_date)
                                                    ->whereDate('hotels_bookings.created_at','<=', $month_res->end_date)
                                                    ->Sum('exchange_price');
                        $total_booking_price    = $agentsInvoices;
                        $agent_booking_data[]   = floor($total_booking_price * 100) / 100;
                    }
                    $series_data[] = [
                        'name' => $agent_res->agent_Name,
                        'data' => $agent_booking_data
                    ];
                }
            }
            
            // Generate Graph Data Today
            if($request_data->date_type == 'data_today_wise'){
                $date                   = date('Y-m-d');
                foreach($limitedAgentData as $agent_res){
                    $agent_booking_data = [$agent_res->total_prices];
                    $series_data[]      = [
                        'name'          => $agent_res->agent_Name,
                        'data'          => $agent_booking_data
                    ];
                }
                $categories_data        = [$date];
            }
            
            // Generate Graph Data YesterDay
            if($request_data->date_type == 'data_Yesterday_wise'){
                $date                   = date('Y-m-d',strtotime("-1 days"));
                foreach($limitedAgentData as $agent_res){
                    $agent_booking_data = [$agent_res->total_prices];
                    $series_data[]      = [
                        'name'          => $agent_res->agent_Name,
                        'data'          => $agent_booking_data
                    ];
                }
                $categories_data        = [$date];
            }
            
            // Generate Graph Data For Week
            if($request_data->date_type == 'data_week_wise'){
                $today          = Carbon::now();
                $startOfWeek    = $today->startOfWeek()->toDateString();
                $endOfWeek      = $today->endOfWeek()->toDateString();
                $period         = CarbonPeriod::create($startOfWeek, $endOfWeek);
                $datesOfWeek    = [];
                foreach ($period as $date) {
                    $datesOfWeek[] = $date->toDateString();
                }
                
                foreach($limitedAgentData as $agent_res){
                    $agent_booking_data         = [];
                    foreach($datesOfWeek as $date_res){
                        $agentsInvoices         = DB::table('hotels_bookings')->where('b2b_agent_id',$agent_res->agent_id)->whereDate('created_at',$date_res)
                                                    ->Sum('exchange_price');
                        $total_booking_price    = $agentsInvoices;
                        $agent_booking_data[]   = $total_booking_price;
                    }
                    $series_data[]              = [
                        'name'                  => $agent_res->agent_Name,
                        'data'                  => $agent_booking_data
                    ];
                }
                
                // Get Data For Categories
                
                $dayNamesOfWeek = [];
                for ($day = 0; $day < 7; $day++) {
                    $dayNamesOfWeek[] = $today->startOfWeek()->addDays($day)->format('l'); // 'l' format represents the full day name
                }
                
                $categories_data = $dayNamesOfWeek;
            }
            
            // Generate Graph Data For Month
            if($request_data->date_type == 'data_month_wise'){
                foreach($limitedAgentData as $agent_res){
                    $startDate                  = Carbon::now()->startOfMonth();
                    $startDateWeek              = $startDate->toDateString();
                    $endDate                    = $startDate->copy()->addDays(6);
                    $endDateWeek                = $endDate->toDateString();
                    $agent_booking_data         = [];
                    for($i=1; $i<=5; $i++){
                        $agentsInvoices         = DB::table('hotels_bookings')->where('b2b_agent_id',$agent_res->agent_id)
                                                    ->whereDate('hotels_bookings.created_at','>=', $startDate)
                                                    ->whereDate('hotels_bookings.created_at','<=', $endDate)
                                                    ->Sum('exchange_price');
                        $total_booking_price    = $agentsInvoices;
                        $agent_booking_data[]   = $total_booking_price;
                        $startDate              = $endDate->copy()->addDays(1);
                        if($i == '4'){
                            $endDate            = $startDate->copy()->addDays(2);
                        }else{
                            $endDate            = $startDate->copy()->addDays(6);
                        }
                        
                        $startDateWeek          = $startDate->toDateString();
                        $endDateWeek            = $endDate->toDateString();
                    }
                    
                    $series_data[] = [
                        'name'                  => $agent_res->agent_Name,
                        'data'                  => $agent_booking_data
                    ];
                }
                
                $categories_data                = ['1st Week','2nd Week','3rd Week','4th Week','5th Week'];
            }
            
            // Generate Graph Data For Year
            if($request_data->date_type == 'data_year_wise'){
                $currentYear            = date('Y');
                $monthsData             = [];
                for ($month = 1; $month <= 12; $month++) {
                    $startOfMonth       = Carbon::create($currentYear, $month, 1)->startOfMonth();
                    $endOfMonth         = Carbon::create($currentYear, $month, 1)->endOfMonth();
                    $categories_data[]  = $startOfMonth->format('F');
                    $startOfMonth       = $startOfMonth->format('Y-m-d');
                    $endOfMonth         = $endOfMonth->format('Y-m-d');
                    $monthsData[] = (Object)[
                        'month'         => $month,
                        'start_date'    => $startOfMonth,
                        'end_date'      => $endOfMonth,
                    ];
                }
                
                foreach($limitedAgentData as $agent_res){
                    $agent_booking_data = [];
                    foreach($monthsData as $month_res){
                        $agentsInvoices         = DB::table('hotels_bookings')->where('b2b_agent_id',$agent_res->agent_id)
                                                    ->whereDate('hotels_bookings.created_at','>=', $month_res->start_date)
                                                    ->whereDate('hotels_bookings.created_at','<=', $month_res->end_date)
                                                    ->Sum('exchange_price');
                        $total_booking_price    = $agentsInvoices;
                        $agent_booking_data[]   = floor($total_booking_price * 100) / 100;
                       
                    }
                    
                    $series_data[] = [
                        'name'                  => $agent_res->agent_Name,
                        'data'                  => $agent_booking_data
                    ];
                }
            }
            
            return response()->json([
                'status'            => 'success',
                'data'              => $limitedAgentData,
                'series_data'       => $series_data,
                'categories_data'   => $categories_data,
                'agent_Groups'      => $agent_Groups,
            ]);
        }else{
            $agentallBookingsObject = new Collection($agentallBookingsObject);
            $agentallBookingsObject = $agentallBookingsObject->sortByDesc('all_bookings');
            $agentallBookingsObject = $agentallBookingsObject->values();
            $agentallBookingsObject = $agentallBookingsObject->toArray();
            
            if(sizeOf($agentallBookingsObject) >= 4){
                $limitedAgentData   = array_slice($agentallBookingsObject, 0, 4);
            }else{
                if(count($agentallBookingsObject) > 0){
                    $slice_V            = count($agentallBookingsObject);
                    $limitedAgentData   = array_slice($agentallBookingsObject, 0, $slice_V);
                }else{
                    $limitedAgentData = [];
                }
            }
            
            $series_data            = [];
            $categories_data        = [];
            
            // Generate Graph Data All
            if($request_data->date_type == 'all_data'){
                $currentYear            = date('Y');
                $monthsData             = [];
                for ($month = 1; $month <= 12; $month++) {
                    $startOfMonth       = Carbon::create($currentYear, $month, 1)->startOfMonth();
                    $endOfMonth         = Carbon::create($currentYear, $month, 1)->endOfMonth();
                    $categories_data[]  = $startOfMonth->format('F');
                    $startOfMonth       = $startOfMonth->format('Y-m-d');
                    $endOfMonth         = $endOfMonth->format('Y-m-d');
                    $monthsData[] = (Object)[
                        'month'         => $month,
                        'start_date'    => $startOfMonth,
                        'end_date'      => $endOfMonth,
                    ];
                }
                
                foreach($limitedAgentData as $agent_res){
                    $agent_booking_data = [];
                    foreach($monthsData as $month_res){
                        $agentsInvoices         = DB::table('hotels_bookings')->where('b2b_agent_id',$agent_res->agent_id)
                                                    ->whereDate('hotels_bookings.created_at','>=', $month_res->start_date)
                                                    ->whereDate('hotels_bookings.created_at','<=', $month_res->end_date)
                                                    ->count();
                        $total_booking_price    = $agentsInvoices;
                        $agent_booking_data[]   = floor($total_booking_price * 100) / 100;
                       
                    }
                    
                    $series_data[]              = [
                        'name'                  => $agent_res->agent_Name,
                        'data'                  => $agent_booking_data
                    ];
                }
            }
            
            // Generate Graph Data YesterDay
            if($request_data->date_type == 'data_today_wise'){
                $date                   = date('Y-m-d');
                foreach($limitedAgentData as $agent_res){
                    $agent_booking_data = [$agent_res->all_bookings];
                    $series_data[]      = [
                        'name'          => $agent_res->agent_Name,
                        'data'          => $agent_booking_data
                    ];
                }
                $categories_data        = [$date];
            }
            
            // Generate Graph Data YesterDay
            if($request_data->date_type == 'data_Yesterday_wise'){
                $date                   = date('Y-m-d',strtotime("-1 days"));
                foreach($limitedAgentData as $agent_res){
                    $agent_booking_data = [$agent_res->all_bookings];
                    $series_data[]      = [
                        'name'          => $agent_res->agent_Name,
                        'data'          => $agent_booking_data
                    ];
                }
                $categories_data        = [$date];
            }
            
            // Generate Graph Data For Week
            if($request_data->date_type == 'data_week_wise'){
                $today          = Carbon::now();
                $startOfWeek    = $today->startOfWeek()->toDateString();
                $endOfWeek      = $today->endOfWeek()->toDateString();
                $period         = CarbonPeriod::create($startOfWeek, $endOfWeek);
                $datesOfWeek    = [];
                foreach ($period as $date) {
                    $datesOfWeek[] = $date->toDateString();
                }
                
                foreach($limitedAgentData as $agent_res){
                    $agent_booking_data         = [];
                    foreach($datesOfWeek as $date_res){
                        $agentsInvoices         = DB::table('hotels_bookings')->where('b2b_agent_id',$agent_res->agent_id)
                                                    ->whereDate('created_at',$date_res)
                                                    ->count();
                        $total_booking_price    = $agentsInvoices;
                        $agent_booking_data[]   = $total_booking_price;
                    }
                    $series_data[] = [
                        'name'                  => $agent_res->agent_Name,
                        'data'                  => $agent_booking_data
                    ];
                }
                
                $dayNamesOfWeek         = [];
                for ($day = 0; $day < 7; $day++) {
                    $dayNamesOfWeek[]   = $today->startOfWeek()->addDays($day)->format('l'); // 'l' format represents the full day name
                }
                $categories_data        = $dayNamesOfWeek;
            }
            
            // Generate Graph Data For Month
            if($request_data->date_type == 'data_month_wise'){
                foreach($limitedAgentData as $agent_res){
                    $startDate                  = Carbon::now()->startOfMonth();
                    $startDateWeek              = $startDate->toDateString();
                    $endDate                    = $startDate->copy()->addDays(6);
                    $endDateWeek                = $endDate->toDateString();
                    $agent_booking_data = [];
                    for($i=1; $i<=5; $i++){
                        $agentsInvoices         = DB::table('hotels_bookings')->where('b2b_agent_id',$agent_res->agent_id)
                                                    ->whereDate('hotels_bookings.created_at','>=', $startDate)
                                                    ->whereDate('hotels_bookings.created_at','<=', $endDate)
                                                    ->count();
                        $total_booking_price    = $agentsInvoices;
                        $agent_booking_data[]   = $total_booking_price;
                        
                        $startDate              = $endDate->copy()->addDays(1);
                        if($i == '4'){
                            $endDate            = $startDate->copy()->addDays(2);
                        }else{
                            $endDate            = $startDate->copy()->addDays(6);
                        }
                        
                        $startDateWeek          = $startDate->toDateString();
                        $endDateWeek            = $endDate->toDateString();
                    }
                    $series_data[] = [
                        'name'                  => $agent_res->agent_Name,
                        'data'                  => $agent_booking_data
                    ];
                }
                $categories_data                = ['1st Week','2nd Week','3rd Week','4th Week','5th Week'];
            }
            
            // Generate Graph Data For Year
            if($request_data->date_type == 'data_year_wise'){
                $currentYear    = date('Y');
                $monthsData     = [];
                for ($month = 1; $month <= 12; $month++) {
                    $startOfMonth       = Carbon::create($currentYear, $month, 1)->startOfMonth();
                    $endOfMonth         = Carbon::create($currentYear, $month, 1)->endOfMonth();
                    $categories_data[]  = $startOfMonth->format('F');
                    $startOfMonth       = $startOfMonth->format('Y-m-d');
                    $endOfMonth         = $endOfMonth->format('Y-m-d');
                    $monthsData[]       = (Object)[
                        'month'         => $month,
                        'start_date'    => $startOfMonth,
                        'end_date'      => $endOfMonth,
                    ];
                }
                
                foreach($limitedAgentData as $agent_res){
                    $agent_booking_data         = [];
                    foreach($monthsData as $month_res){
                        $agentsInvoices         = DB::table('hotels_bookings')->where('b2b_agent_id',$agent_res->agent_id)
                                                    ->whereDate('hotels_bookings.created_at','>=', $month_res->start_date)
                                                    ->whereDate('hotels_bookings.created_at','<=', $month_res->end_date)
                                                    ->count();
                        $total_booking_price    = $agentsInvoices;
                        $agent_booking_data[]   = floor($total_booking_price * 100) / 100;
                    }
                    $series_data[] = [
                        'name'                      => $agent_res->agent_Name,
                        'data'                      => $agent_booking_data
                    ];
                }
            }
            
            return response()->json([
                'status'            => 'success',
                'data'              => $limitedAgentData,
                'series_data'       => $series_data,
                'categories_data'   => $categories_data,
                'agent_Groups'      => $agent_Groups,
            ]);
        }
    }
}