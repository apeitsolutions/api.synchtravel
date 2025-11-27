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

class UserController extends Controller
{
    public function dashboard_revenue_calculate_subUser(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            $invoices_data  = DB::table('add_manage_invoices')->where('customer_id',$userData->id)->where('SU_id',$request->SU_id)
                                ->selectRaw('SUM(total_sale_price_Company) as total_sales, SUM(total_cost_price_Company) as total_costs, (SUM(total_sale_price_Company) - SUM(total_cost_price_Company)) as profit_difference')
                                ->first();
            $tour_booking   = DB::table('cart_details')->where('SU_id',$request->SU_id)->where('client_id',$userData->id)->get();
            $expense        = DB::table('expenses')->where('SU_id',$request->SU_id)->where('customer_id',$userData->id)->sum('total_amount');
            
            $final_grand_profit = 0;
            $final_grand_cost   = 0;
            $final_grand_sale   = 0;
            
            foreach($tour_booking as $tour_res){
                
                $tours_costing = DB::table('tours_2')->where('tour_id',$tour_res->tour_id)->where('SU_id',$request->SU_id)->select('quad_cost_price','triple_cost_price','double_cost_price','without_acc_cost_price','child_grand_total_cost_price','infant_total_cost_price')->first();
                
                $cart_all_data = json_decode($tour_res->cart_total_data);
                
                $grand_profit = 0;
                $grand_cost = 0;
                $grand_sale = 0;
                // Profit From Double Adults
                 
                if(isset($cart_all_data->double_adults)){
                    if($cart_all_data->double_adults > 0){
                        $double_adult_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_adults;
                        $double_profit = $cart_all_data->double_adult_total - $double_adult_total_cost;
                        $grand_profit += $double_profit;
                        $grand_cost += $double_adult_total_cost;
                        $grand_sale += $cart_all_data->double_adult_total;
                    }
                 }
                 
                // Profit From Triple Adults
                if(isset($cart_all_data->triple_adults)){ 
                    if($cart_all_data->triple_adults > 0){
                        $triple_adult_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                        $grand_profit += $triple_profit;
                        $grand_cost += $triple_adult_total_cost;
                        $grand_sale += $cart_all_data->triple_adult_total;
                    }
                }    
                
                // Profit From Quad Adults
                if(isset($cart_all_data->quad_adults)){ 
                    if($cart_all_data->quad_adults > 0){
                        $quad_adult_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                        $grand_profit += $quad_profit;
                         $grand_cost += $quad_adult_total_cost;
                        $grand_sale += $cart_all_data->quad_adult_total;
                    }
                } 
                
                // Profit From Without Acc
                
                if(isset($cart_all_data->without_acc_adults)){ 
                    if($cart_all_data->without_acc_adults > 0){
                        $without_acc_adult_total_cost = $tours_costing->without_acc_cost_price * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                        $grand_profit += $without_acc_profit;
                        $grand_cost += $without_acc_adult_total_cost;
                        $grand_sale += $cart_all_data->without_acc_adult_total;
                    }
                } 
                // Profit From Double Childs
                
                if(isset($cart_all_data->double_childs)){ 
                    if($cart_all_data->double_childs > 0){
                        $double_child_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                        $grand_profit += $double_profit;
                        $grand_cost += $double_child_total_cost;
                        $grand_sale += $cart_all_data->double_childs_total;
                    }
                }  
                 // Profit From Triple Childs
                if(isset($cart_all_data->triple_childs)){ 
                   if($cart_all_data->triple_childs > 0){
                        $triple_child_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                        $grand_profit += $triple_profit;
                        $grand_cost += $triple_child_total_cost;
                        $grand_sale += $cart_all_data->triple_childs_total;
                    }
                }    
                 // Profit From Quad Childs
                if(isset($cart_all_data->quad_childs)){ 
                    if($cart_all_data->quad_childs > 0){
                        $quad_child_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                        $grand_profit += $quad_profit;
                        $grand_cost += $quad_child_total_cost;
                        $grand_sale += $cart_all_data->quad_child_total;
                    }
                } 
                 // Profit From Without Acc Child
                if(isset($cart_all_data->children)){
                    if($cart_all_data->children > 0){
                        $without_acc_child_total_cost = $tours_costing->child_grand_total_cost_price * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total ?? 0 - $without_acc_child_total_cost ?? 0;
                        $grand_profit += $without_acc_profit;
                        $grand_cost += $without_acc_child_total_cost;
                        $grand_sale += $cart_all_data->without_acc_child_total ?? 0;
                    }
                }
                // Profit From Double Infant
                if(isset($cart_all_data->double_infant)){
                    if($cart_all_data->double_infant > 0){
                        $double_infant_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                        $grand_profit += $double_profit;
                         $grand_cost += $double_infant_total_cost;
                        $grand_sale += $cart_all_data->double_infant_total;
                    }
                } 
                 // Profit From Triple Infant
                if(isset($cart_all_data->triple_infant)){ 
                    if($cart_all_data->triple_infant > 0){
                        $triple_infant_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                        $grand_profit += $triple_profit;
                         $grand_cost += $triple_infant_total_cost;
                        $grand_sale += $cart_all_data->triple_infant_total;
                    }
                } 
                 // Profit From Quad Infant
                if(isset($cart_all_data->quad_infant)){ 
                    if($cart_all_data->quad_infant > 0){
                        $quad_infant_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                        $grand_profit += $quad_profit;
                         $grand_cost += $quad_infant_total_cost;
                        $grand_sale += $cart_all_data->quad_infant_total;
                    }
                } 
                 // Profit From Without Acc Infant  
                 
                if(isset($cart_all_data->infants)){
                  if($cart_all_data->infants > 0){
                        $without_acc_infant_total_cost = $tours_costing->infant_total_cost_price * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                        $grand_profit += $without_acc_profit;
                        $grand_cost += $without_acc_infant_total_cost;
                        $grand_sale += $cart_all_data->without_acc_infant_total;
                  }
                }  
                  $over_all_dis = 0;
                //   echo "Grand Total Profit is $grand_profit "; 
                if(isset($cart_all_data->discount_type)){
                    if($cart_all_data->discount_type == 'amount'){
                        $final_profit = $grand_profit - $cart_all_data->discount_enter_am;
                    }else{
                        $discunt_am_over_all = ($cart_all_data->price * $cart_all_data->discount_enter_am) / 100;
                        $final_profit = $grand_profit - $discunt_am_over_all;
                    }
                }else{
                    $final_profit = 0;
                }
                
                //  echo "Grand Total Profit is $final_profit";
                //  print_r($cart_all_data);
                
                $commission_add = '';
                if(isset($cart_all_data->agent_commsion_add_total)){
                    $commission_add = $cart_all_data->agent_commsion_add_total;
                }
                
                $final_grand_profit += $final_profit;
                $final_grand_cost += $grand_cost;
                $final_grand_sale += $grand_sale;

             }
            
            $overall_cost       = $final_grand_cost + $invoices_data->total_costs + $expense;
            $overall_sale       = $final_grand_sale + $invoices_data->total_sales;
            $overall_profit     = $final_grand_profit + $invoices_data->profit_difference;
            $overall_profit     -= $expense;
            
            // Calculate Agent
            $agents_total_payables  = 0;
            $all_agents             = DB::table('Agents_detail')->where('SU_id',$request->SU_id)->where('customer_id',$userData->id)->select('id')->get();
            foreach($all_agents as $agent_res){
                $agent_invoices_all = DB::table('add_manage_invoices')->where('SU_id',$request->SU_id)
                                            ->where('agent_Id',$agent_res->id)
                                            ->select('id','total_sale_price_Company')
                                            ->Sum('total_sale_price_Company');
                                            
                $packages_booking_all = DB::table('cart_details')->where('SU_id',$request->SU_id)
                    ->where('agent_name',$agent_res->id)
                    ->select('cart_details.id','cart_details.price','cart_details.invoice_no')
                   ->Sum('cart_details.price');
                   
               
                $payments_data = DB::table('recevied_payments_details')->where('SU_id',$request->SU_id)
                    ->where('Criteria','Agent')
                    ->where('Content_Ids',$agent_res->id)
                    ->select('company_amount')
                    ->Sum('company_amount');
                    
                $make_payments_data = DB::table('make_payments_details')->where('SU_id',$request->SU_id)
                    ->where('Criteria','Agent')
                    ->where('Content_Ids',$agent_res->id)
                    ->select('company_amount')
                    ->Sum('company_amount');
                    
                $total_agent_payable = $packages_booking_all + $agent_invoices_all + $make_payments_data;
                $total_agent_payable -= $payments_data;
                   
                $agents_total_payables += $total_agent_payable;         
            }
            
            // Calculate Customer
            $customer_total_payables    = 0;
            $all_customers              = DB::table('booking_customers')->where('SU_id',$request->SU_id)->where('customer_id',$userData->id)->select('id')->get();
            foreach($all_customers as $booking_customers){
                $customer_invoices_all = DB::table('add_manage_invoices')->where('SU_id',$request->SU_id)
                                            ->where('booking_customer_id',$booking_customers->id)
                                            ->select('id','total_sale_price_Company')
                                            ->Sum('total_sale_price_Company');
                                            
                $packages_booking_all = DB::table('cart_details')->where('SU_id',$request->SU_id)
                    ->whereJsonContains('cart_total_data->customer_id',"$booking_customers->id")
                    ->select('cart_details.id','cart_details.price','cart_details.invoice_no')
                   ->Sum('cart_details.price');
                   
               
                $payments_data = DB::table('recevied_payments_details')->where('SU_id',$request->SU_id)
                    ->where('Criteria','Customer')
                    ->where('Content_Ids',$booking_customers->id)
                    ->select('company_amount')
                    ->Sum('company_amount');
                    
                $make_payments_data = DB::table('make_payments_details')->where('SU_id',$request->SU_id)
                    ->where('Criteria','Customer')
                    ->where('Content_Ids',$booking_customers->id)
                    ->select('company_amount')
                    ->Sum('company_amount');
                    
                $total_customer_payable = $packages_booking_all + $customer_invoices_all + $make_payments_data;
                $total_customer_payable -= $payments_data;
                  
                $customer_total_payables += $total_customer_payable;         
            }
            
            $total_payble_amount = $customer_total_payables + $agents_total_payables;
            
            return response()->json([
                'status' => 'success',
                'data'  => ['total_cost'=>number_format($overall_cost,2),'total_sale'=>number_format($overall_sale,2),'profit'=>number_format($overall_profit,2),'outstanding'=>number_format($total_payble_amount,2)],
            ]);
        }
    }
    
    public function dashboard_revenue_calculate_Season_Working($all_data,$userData,$request){
        $today_Date             = date('Y-m-d');
        
        if(isset($request->season_Id)){
            if($request->season_Id == 'all_Seasons'){
                $season_Details = null;
            }elseif($request->season_Id > 0){
                $season_Details = DB::table('add_Seasons')->where('customer_id', $userData->id)->where('id', $request->season_Id)->first();
            }else{
                $season_Details = null;
            }
        }else{
            $season_Details     = DB::table('add_Seasons')->where('customer_id', $userData->id)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
        }
        
        // $season_Details         = DB::table('add_Seasons')->where('customer_id', $userData->id)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
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
    
    public function dashboard_revenue_calculate(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            $final_grand_profit     = 0;
            $final_grand_cost       = 0;
            $final_grand_sale       = 0;
            $agents_total_payables  = 0;
            $customer_total_payables= 0;
            $invoices_data          = DB::table('add_manage_invoices')->where('customer_id',$userData->id)
                                        ->selectRaw('SUM(total_sale_price_Company) as total_sales, SUM(total_cost_price_Company) as total_costs, (SUM(total_sale_price_Company) - SUM(total_cost_price_Company)) as profit_difference')
                                        ->first();
            $tour_booking           = DB::table('cart_details')->where('client_id',$userData->id)->get();
            $expense                = DB::table('expenses')->where('customer_id',$userData->id)->sum('total_amount');
            $all_agents             = DB::table('Agents_detail')->where('customer_id',$userData->id)->select('id')->get();
            $all_customers          = DB::table('booking_customers')->where('customer_id',$userData->id)->select('id')->get();
            
            // Season
            $total_Agents                       = DB::table('Agents_detail')->where('customer_id',$userData->id)->count();
            $hotel_total_Suppliers              = DB::table('rooms_Invoice_Supplier')->where('customer_id',$userData->id)->count();
            $Flights_total_Suppliers            = DB::table('supplier')->where('customer_id',$userData->id)->count();
            $packages_tour                      = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.customer_id',$userData->id)->count();
            $no_of_pax_days                     = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.customer_id',$userData->id)->sum('no_of_pax_days');
            $booked_tour                        = DB::table('cart_details')->where('pakage_type','tour')->where('client_id',$userData->id)->count();
            $toTal                              = DB::table('cart_details')->where('pakage_type','tour')->where('client_id',$userData->id)->sum('price');
            $recieved                           = DB::table('view_booking_payment_recieve')->where('customer_id',$userData->id)->sum('remaining_amount');
            if(!empty($toTal)){
                $outStandings                   = $toTal - $recieved;
            }else{
                $outStandings                   = 0;
            }
            $activities_count                   = DB::table('new_activites')->where('customer_id',$userData->id)->count();
            $booked_activities                  = DB::table('cart_details')->where('pakage_type','activity')->where('client_id',$userData->id)->count();
            $activities_no_of_pax_days          = DB::table('new_activites')->where('customer_id',$userData->id)->sum('max_people');
            $toTal_activities                   = DB::table('cart_details')->where('pakage_type','activity')->where('client_id',$userData->id)->sum('price');
            $recieved_activities                = DB::table('view_booking_payment_recieve_Activity')->sum('remaining_amount');
            if(!empty($toTal_activities)){
                $activities_outStandings        = $toTal_activities - $recieved_activities;
            }else{
                $activities_outStandings        = 0;
            }
            $tours                              = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.customer_id',$userData->id)->get();
            $final_data                         = DB::table('cart_details')->where('client_id',$userData->id)->get();
            $data_Tours                         = [];
            if(!empty($tours)){
                foreach($tours as $tours_res){
                    $tour_book = false;
                    foreach($final_data as $book_res){
                        if($tours_res->id == $book_res->tour_id){
                            $tour_book = true;
                        }
                    }
                    
                    $single_tour = [
                        'id'                 => $tours_res->id,
                        'title'              => $tours_res->title,
                        'no_of_pax_days'     => $tours_res->no_of_pax_days,
                        'start_date'         => $tours_res->start_date,
                        'end_date'           => $tours_res->end_date,
                        'tour_location'      => $tours_res->tour_location,
                        'tour_author'        => $tours_res->tour_author,
                        'tour_publish'       => $tours_res->tour_publish,
                        'no_of_pax_days'     => $tours_res->no_of_pax_days,
                        'book_status'        => $tour_book,
                    ];
                    array_push($data_Tours,$single_tour);      
                }
            }
            $data2                              = DB::table('tours_bookings')->join('customer_subcriptions','tours_bookings.customer_id','customer_subcriptions.id')->join('cart_details','tours_bookings.id','cart_details.booking_id')->where('cart_details.pakage_type','tour')->where('tours_bookings.customer_id',$userData->id)->get();
            $new_activites                      = DB::table('new_activites')->where('new_activites.customer_id',$userData->id)->get();
            $data3                              = DB::table('tours_bookings')->join('customer_subcriptions','tours_bookings.customer_id','customer_subcriptions.id')->join('cart_details','tours_bookings.id','cart_details.booking_id')->where('cart_details.pakage_type','activity')->where('tours_bookings.customer_id',$userData->id)->get();
            if($userData->id == 4 || $userData->id == 54){
                $today_Date                     = date('Y-m-d');
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
                    $invoices_data              = DB::table('add_manage_invoices')->where('customer_id',$userData->id)->whereBetween('created_at', [$start_Date, $end_Date])
                                                    ->selectRaw('SUM(total_sale_price_Company) as total_sales, SUM(total_cost_price_Company) as total_costs, (SUM(total_sale_price_Company) - SUM(total_cost_price_Company)) as profit_difference')
                                                    ->first();
                    // dd($invoices_data);
                    $expense                    = DB::table('expenses')->where('customer_id',$userData->id)->whereBetween('created_at', [$start_Date, $end_Date])->sum('total_amount');
                    $all_agents                 = DB::table('Agents_detail')->where('customer_id',$userData->id)->whereBetween('created_at', [$start_Date, $end_Date])->select('id')->get();
                    $all_customers              = DB::table('booking_customers')->where('customer_id',$userData->id)->whereBetween('created_at', [$start_Date, $end_Date])->select('id')->get();
                    
                    if($tour_booking->isEmpty()){
                    }else{
                        $tour_booking           = $this->dashboard_revenue_calculate_Season_Working($tour_booking,$userData,$request);
                        // dd($tour_booking);
                    }
                    
                    // Dashboard
                    $total_Agents               = DB::table('Agents_detail')->where('customer_id',$userData->id)->whereBetween('created_at', [$start_Date, $end_Date])->count();
                    $hotel_total_Suppliers      = DB::table('rooms_Invoice_Supplier')->where('customer_id',$userData->id)->whereBetween('created_at', [$start_Date, $end_Date])->count();
                    $Flights_total_Suppliers    = DB::table('supplier')->where('customer_id',$userData->id)->whereBetween('created_at', [$start_Date, $end_Date])->count();
                    $packages_tour              = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.customer_id',$userData->id)->whereBetween('tours.created_at', [$start_Date, $end_Date])->count();
                    $no_of_pax_days             = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.customer_id',$userData->id)->whereBetween('tours.created_at', [$start_Date, $end_Date])->sum('no_of_pax_days');
                    $booked_tour                = DB::table('cart_details')->where('pakage_type','tour')->where('client_id',$userData->id)->whereBetween('cart_details.created_at', [$start_Date, $end_Date])->count();
                    $toTal                      = DB::table('cart_details')->where('pakage_type','tour')->where('client_id',$userData->id)->whereBetween('created_at', [$start_Date, $end_Date])->sum('price');
                    $recieved                   = DB::table('view_booking_payment_recieve')->where('customer_id',$userData->id)->whereBetween('created_at', [$start_Date, $end_Date])->sum('remaining_amount');
                    if(!empty($toTal)){
                        $outStandings           = $toTal - $recieved;
                    }else{
                        $outStandings           = 0;
                    }
                    $activities_count           = DB::table('new_activites')->where('customer_id',$userData->id)->whereBetween('created_at', [$start_Date, $end_Date])->count();
                    $booked_activities          = DB::table('cart_details')->where('pakage_type','activity')->where('client_id',$userData->id)->whereBetween('created_at', [$start_Date, $end_Date])->count();
                    $activities_no_of_pax_days  = DB::table('new_activites')->where('customer_id',$userData->id)->whereBetween('created_at', [$start_Date, $end_Date])->sum('max_people');
                    $toTal_activities           = DB::table('cart_details')->where('pakage_type','activity')->where('client_id',$userData->id)->whereBetween('created_at', [$start_Date, $end_Date])->sum('price');
                    $recieved_activities        = DB::table('view_booking_payment_recieve_Activity')->whereBetween('created_at', [$start_Date, $end_Date])->sum('remaining_amount');
                    if(!empty($toTal_activities)){
                        $activities_outStandings= $toTal_activities - $recieved_activities;
                    }else{
                        $activities_outStandings= 0;
                    }
                    $tours                      = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->where('tours.customer_id',$userData->id)->whereBetween('tours.created_at', [$start_Date, $end_Date])->get();
                    $final_data                 = DB::table('cart_details')->where('client_id',$userData->id)->whereBetween('created_at', [$start_Date, $end_Date])->get();
                    $data_Tours                 = [];
                    if(!empty($tours)){
                        foreach($tours as $tours_res){
                            $tour_book = false;
                            foreach($final_data as $book_res){
                                if($tours_res->id == $book_res->tour_id){
                                    $tour_book = true;
                                }
                            }
                            
                            $single_tour = [
                                'id'                 => $tours_res->id,
                                'title'              => $tours_res->title,
                                'no_of_pax_days'     => $tours_res->no_of_pax_days,
                                'start_date'         => $tours_res->start_date,
                                'end_date'           => $tours_res->end_date,
                                'tour_location'      => $tours_res->tour_location,
                                'tour_author'        => $tours_res->tour_author,
                                'tour_publish'       => $tours_res->tour_publish,
                                'no_of_pax_days'     => $tours_res->no_of_pax_days,
                                'book_status'        => $tour_book,
                            ];
                            array_push($data_Tours,$single_tour);      
                        }
                    }
                    $data2                      = DB::table('tours_bookings')->join('customer_subcriptions','tours_bookings.customer_id','customer_subcriptions.id')->join('cart_details','tours_bookings.id','cart_details.booking_id')->where('cart_details.pakage_type','tour')->where('tours_bookings.customer_id',$userData->id)->whereBetween('tours_bookings.created_at', [$start_Date, $end_Date])->get();
                    $new_activites              = DB::table('new_activites')->where('new_activites.customer_id',$userData->id)->whereBetween('created_at', [$start_Date, $end_Date])->get();
                    $data3                      = DB::table('tours_bookings')->join('customer_subcriptions','tours_bookings.customer_id','customer_subcriptions.id')->join('cart_details','tours_bookings.id','cart_details.booking_id')->where('cart_details.pakage_type','activity')->where('tours_bookings.customer_id',$userData->id)->whereBetween('tours_bookings.created_at', [$start_Date, $end_Date])->get();
                    // Dashboard
                }
            }
            // Season
            
            foreach($tour_booking as $tour_res){
                
                $tours_costing  = DB::table('tours_2')->where('tour_id',$tour_res->tour_id)->select('quad_cost_price','triple_cost_price','double_cost_price','without_acc_cost_price','child_grand_total_cost_price','infant_total_cost_price')->first();
                $cart_all_data  = json_decode($tour_res->cart_total_data);
                $grand_profit   = 0;
                $grand_cost     = 0;
                $grand_sale     = 0;
                
                if(isset($cart_all_data->double_adults)){
                    if($cart_all_data->double_adults > 0){
                        $double_adult_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_adults;
                        $double_profit = $cart_all_data->double_adult_total - $double_adult_total_cost;
                        $grand_profit += $double_profit;
                        $grand_cost += $double_adult_total_cost;
                        $grand_sale += $cart_all_data->double_adult_total;
                    }
                 }
                
                if(isset($cart_all_data->triple_adults)){ 
                    if($cart_all_data->triple_adults > 0){
                        $triple_adult_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                        $grand_profit += $triple_profit;
                        $grand_cost += $triple_adult_total_cost;
                        $grand_sale += $cart_all_data->triple_adult_total;
                    }
                }
                
                if(isset($cart_all_data->quad_adults)){ 
                    if($cart_all_data->quad_adults > 0){
                        $quad_adult_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                        $grand_profit += $quad_profit;
                         $grand_cost += $quad_adult_total_cost;
                        $grand_sale += $cart_all_data->quad_adult_total;
                    }
                }
                
                if(isset($cart_all_data->without_acc_adults)){ 
                    if($cart_all_data->without_acc_adults > 0){
                        $without_acc_adult_total_cost = $tours_costing->without_acc_cost_price * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                        $grand_profit += $without_acc_profit;
                        $grand_cost += $without_acc_adult_total_cost;
                        $grand_sale += $cart_all_data->without_acc_adult_total;
                    }
                }
                
                if(isset($cart_all_data->double_childs)){ 
                    if($cart_all_data->double_childs > 0){
                        $double_child_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                        $grand_profit += $double_profit;
                        $grand_cost += $double_child_total_cost;
                        $grand_sale += $cart_all_data->double_childs_total;
                    }
                }
                
                if(isset($cart_all_data->triple_childs)){ 
                   if($cart_all_data->triple_childs > 0){
                        $triple_child_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                        $grand_profit += $triple_profit;
                        $grand_cost += $triple_child_total_cost;
                        $grand_sale += $cart_all_data->triple_childs_total;
                    }
                }
                
                if(isset($cart_all_data->quad_childs)){ 
                    if($cart_all_data->quad_childs > 0){
                        $quad_child_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                        $grand_profit += $quad_profit;
                        $grand_cost += $quad_child_total_cost;
                        $grand_sale += $cart_all_data->quad_child_total;
                    }
                }
                
                if(isset($cart_all_data->children)){
                    if($cart_all_data->children > 0){
                        if(isset($tours_costing->child_grand_total_cost_price) && $tours_costing->child_grand_total_cost_price != null && $tours_costing->child_grand_total_cost_price != ''){
                            $without_acc_child_total_cost = $tours_costing->child_grand_total_cost_price * $cart_all_data->children;
                            $without_acc_profit = $cart_all_data->without_acc_child_total ?? '0' - $without_acc_child_total_cost;
                        }else{
                            $without_acc_child_total_cost = 1 * $cart_all_data->children;
                            $without_acc_profit = $cart_all_data->without_acc_child_total ?? '0' - $without_acc_child_total_cost;
                        }
                        // $without_acc_child_total_cost = $tours_costing->child_grand_total_cost_price * $cart_all_data->children;
                        // $without_acc_profit = $cart_all_data->without_acc_child_total ?? 0 - $without_acc_child_total_cost ?? 0;
                        
                        $grand_profit += $without_acc_profit;
                        $grand_cost += $without_acc_child_total_cost;
                        $grand_sale += $cart_all_data->without_acc_child_total ?? 0;
                    }
                }
                
                if(isset($cart_all_data->double_infant)){
                    if($cart_all_data->double_infant > 0){
                        $double_infant_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                        $grand_profit += $double_profit;
                         $grand_cost += $double_infant_total_cost;
                        $grand_sale += $cart_all_data->double_infant_total;
                    }
                }
                
                if(isset($cart_all_data->triple_infant)){ 
                    if($cart_all_data->triple_infant > 0){
                        $triple_infant_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                        $grand_profit += $triple_profit;
                         $grand_cost += $triple_infant_total_cost;
                        $grand_sale += $cart_all_data->triple_infant_total;
                    }
                }
                
                if(isset($cart_all_data->quad_infant)){ 
                    if($cart_all_data->quad_infant > 0){
                        $quad_infant_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                        $grand_profit += $quad_profit;
                         $grand_cost += $quad_infant_total_cost;
                        $grand_sale += $cart_all_data->quad_infant_total;
                    }
                }
                
                if(isset($cart_all_data->infants)){
                  if($cart_all_data->infants > 0){
                        $without_acc_infant_total_cost = $tours_costing->infant_total_cost_price * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                        $grand_profit += $without_acc_profit;
                        $grand_cost += $without_acc_infant_total_cost;
                        $grand_sale += $cart_all_data->without_acc_infant_total;
                  }
                }
                
                $over_all_dis   = 0;
                if(isset($cart_all_data->discount_type)){
                    if($cart_all_data->discount_type == 'amount'){
                        $final_profit = $grand_profit - $cart_all_data->discount_enter_am;
                    }else{
                        $discunt_am_over_all = ($cart_all_data->price * $cart_all_data->discount_enter_am) / 100;
                        $final_profit = $grand_profit - $discunt_am_over_all;
                    }
                }else{
                    $final_profit = 0;
                }
                
                $commission_add = '';
                if(isset($cart_all_data->agent_commsion_add_total)){
                    $commission_add = $cart_all_data->agent_commsion_add_total;
                }
                
                if($tour_res->pakage_type == 'activity'){
                    $activity = DB::table('new_activites')->where('customer_id',$userData->id)->where('id',$tour_res->tour_id)->first();
                    // dd($activity,$tour_res);
                    
                    $total_pax_activity = 0;
                    
                    if(isset($tour_res->adults) && $tour_res->adults > 0){ 
                        $total_pax_activity += $tour_res->adults;
                    }
                    
                    if(isset($tour_res->childs) && $tour_res->childs > 0){ 
                        $total_pax_activity += $tour_res->childs;
                    }
                    
                    if(isset($activity->cost_price) && $activity->cost_price > 0 && $total_pax_activity > 0){
                        if($total_pax_activity == 0){ 
                            $total_pax_activity = 1;
                        }
                        $activity_cost_price = $activity->cost_price * $total_pax_activity;
                    }else{
                        $activity_cost_price = $activity->cost_price ?? 0;
                    }
                    
                    if(isset($activity->sale_price) && $activity->sale_price > 0 && $total_pax_activity > 0){
                        if($total_pax_activity == 0){ 
                            $total_pax_activity = 1;
                        }
                        $activity_sale_price = $activity->sale_price * $total_pax_activity;
                    }else{
                        $activity_sale_price = $activity->sale_price ?? 0;
                    }
                    
                    if($activity->sale_price ?? 0 > 0 && $activity->cost_price ?? 0 > 0){ 
                        $activity_profit_price = $activity_sale_price - $activity_cost_price;
                    }else{
                        $activity_profit_price = 0;
                    }
                    
                    $final_grand_profit += $activity_profit_price;
                    $final_grand_cost   += $activity_cost_price;
                    $final_grand_sale   += $activity_sale_price;
                }
                
                $final_grand_profit += $final_profit;
                $final_grand_cost   += $grand_cost;
                $final_grand_sale   += $grand_sale;
            }
            
            $overall_cost           = $final_grand_cost + $invoices_data->total_costs + $expense;
            $overall_sale           = $final_grand_sale + $invoices_data->total_sales;
            $overall_profit         = $final_grand_profit + $invoices_data->profit_difference;
            $overall_profit         -= $expense;
            
            foreach($all_agents as $agent_res){
                $agent_invoices_all     = DB::table('add_manage_invoices')
                                            ->where('agent_Id',$agent_res->id)
                                            ->select('id','total_sale_price_Company')
                                            ->Sum('total_sale_price_Company');
                $packages_booking_all   = DB::table('cart_details')
                                            ->where('agent_name',$agent_res->id)
                                            ->select('cart_details.id','cart_details.price','cart_details.invoice_no')
                                            ->Sum('cart_details.price');
                $payments_data          = DB::table('recevied_payments_details')
                                            ->where('Criteria','Agent')
                                            ->where('Content_Ids',$agent_res->id)
                                            ->select('company_amount')
                                            ->Sum('company_amount');
                $make_payments_data     = DB::table('make_payments_details')
                                            ->where('Criteria','Agent')
                                            ->where('Content_Ids',$agent_res->id)
                                            ->select('company_amount')
                                            ->Sum('company_amount');
                $total_agent_payable    = $packages_booking_all + $agent_invoices_all + $make_payments_data;
                $total_agent_payable    -= $payments_data;
                $agents_total_payables  += $total_agent_payable;         
            }
            
            foreach($all_customers as $booking_customers){
                 $customer_invoices_all = DB::table('add_manage_invoices')
                                            ->where('booking_customer_id',$booking_customers->id)
                                            ->select('id','total_sale_price_Company')
                                            ->Sum('total_sale_price_Company');
                                            
                 $packages_booking_all = DB::table('cart_details')
                    ->whereJsonContains('cart_total_data->customer_id',"$booking_customers->id")
                    ->select('cart_details.id','cart_details.price','cart_details.invoice_no')
                   ->Sum('cart_details.price');
                   
               
                $payments_data = DB::table('recevied_payments_details')
                    ->where('Criteria','Customer')
                    ->where('Content_Ids',$booking_customers->id)
                    ->select('company_amount')
                    ->Sum('company_amount');
                    
                $make_payments_data = DB::table('make_payments_details')
                    ->where('Criteria','Customer')
                    ->where('Content_Ids',$booking_customers->id)
                    ->select('company_amount')
                    ->Sum('company_amount');
                    
                 $total_customer_payable = $packages_booking_all + $customer_invoices_all + $make_payments_data;
                 $total_customer_payable -= $payments_data;
                   
                                   
                 $customer_total_payables += $total_customer_payable;         
            }
            
            $total_payble_amount    = $customer_total_payables + $agents_total_payables;
            
            return response()->json([
                'status'                        => 'success',
                'data'                          => [
                    'total_cost'                => number_format($overall_cost,2),
                    'total_sale'                => number_format($overall_sale,2),
                    'profit'                    => number_format($overall_profit,2),
                    'outstanding'               => number_format($total_payble_amount,2),
                    // Dashboard
                    'total_Agents'              => $total_Agents,
                    'hotel_total_Suppliers'     => $hotel_total_Suppliers,
                    'Flights_total_Suppliers'   => $Flights_total_Suppliers,
                    'packages_tour'             => $packages_tour,
                    'no_of_pax_days'            => $no_of_pax_days,
                    'booked_tour'               => $booked_tour,
                    'toTal'                     => number_format($toTal),
                    'recieved'                  => number_format($recieved),
                    'outStandings'              => number_format($outStandings),
                    'activities_count'          => $activities_count,
                    'booked_activities'         => $booked_activities,
                    'activities_no_of_pax_days' => $activities_no_of_pax_days,
                    'toTal_activities'          => number_format($toTal_activities),
                    'recieved_activities'       => number_format($recieved_activities),
                    'activities_outStandings'   => number_format($activities_outStandings),
                    'tours'                     => $data_Tours,
                    'data1'                     => $data2,
                    'new_activites'             => $new_activites,
                    'data3'                     => $data3,
                    // Dashboard
                ]
            ]);
        }
    }

    public function update_ledgers(Request $request){
        // print_r($request->all());
  
                            
      
               
         DB::beginTransaction();
                  
                     try {
                            $all_payments_recevied = DB::table('payments')->get();
                            
                            foreach($all_payments_recevied as $pay_recv_res){
                                $all_ids = json_decode($pay_recv_res->Content_Ids);
                                $Criteria = json_decode($pay_recv_res->Criteria);
                                $Content = json_decode($pay_recv_res->Content);
                                $Amount = json_decode($pay_recv_res->Amount);
                                $remarks = json_decode($pay_recv_res->remarks);
                                $converion_data = json_decode($pay_recv_res->converion_data);
                                $purchase_amount = json_decode($pay_recv_res->purchase_amount);
                                $exchange_rate = json_decode($pay_recv_res->exchange_rate);
                                $payment_date = json_decode($pay_recv_res->payment_date);
                                
                                foreach($all_ids as $index => $id_res){
                                    $id = $id_res;
                                    $per_criteria = '';
                                    $per_Content = '';
                                    $per_Amount = '';
                                    $per_remarks = '';
                                    $per_converion_data = '';
                                    $per_purchase_amount = '';
                                    $per_exchange_rate = '';
                                    $per_payment_date = date('Y-m-d',strtotime($pay_recv_res->created_at));
                                    
                                    if(isset($Criteria[$index])){
                                        $per_criteria = $Criteria[$index];
                                    }
                                    
                                    if(isset($Content[$index])){
                                        $per_Content = $Content[$index];
                                    }
                                    
                                    if(isset($Amount[$index])){
                                        $per_Amount = $Amount[$index];
                                    }
                                    
                                    if(isset($remarks[$index])){
                                        $per_remarks = $remarks[$index];
                                    }
                                    
                                    if(isset($converion_data[$index])){
                                        $per_converion_data = $converion_data[$index];
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
                                    
                                    // DB::table('make_payments_details')->insert([
                                    //         'make_payments_id' => $pay_recv_res->id,
                                    //         'Criteria' => $per_criteria,
                                    //         'Content' => $per_Content,
                                    //         'Content_Ids' => $id,
                                    //         'Amount' => $per_Amount,
                                    //         'remarks' => $per_remarks,
                                    //         'converion_data' => $per_converion_data,
                                    //         'purchase_amount' => $per_purchase_amount,
                                    //         'exchange_rate' => $per_exchange_rate,
                                    //         'payment_date' => $per_payment_date,
                                    //     ]);
                                    
                                    
                                }
                            }
                            // dd($all_payments_recevied);
                            // DB::commit();
                              // echo $result;
                        } catch (Throwable $e) {

                            DB::rollback();
                            echo $e->getMessage();
                            // return response()->json(['message'=>'error','booking_id'=> '']);
                        }
                            
        
    
        // print_r($all_invoices);
    }
   
    public function index_old(){
    
           
            // dd('ok');
            
            return view('template/frontend/userdashboard/index');
    
    		//return view('template/frontend/userdashboard/index',compact('data'));
    
    	}
    
    public function getCurrentWeek(){
            $monday         = strtotime("last monday");
            $monday         = date('w', $monday)==date('w') ? $monday+(7*86400) : $monday;
            $sunday         = strtotime(date("Y-m-d",$monday)." +6 days");
            $this_week_sd   = date("Y-m-d",$monday);
            $this_week_ed   = date("Y-m-d",$sunday);
            return $data    = ['first_day' => $this_week_sd, 'last_day' => $this_week_ed];
        }
    
    public function getMonthsName($month){
            switch($month){
                        case 1:
                         return 'Jan';
                        break;
                        case 2:
                         return 'Feb';
                        break;
                        case 3:
                         return 'Mar';
                        break;
                        case 4:
                         return 'April';
                        break;
                        case 5:
                         return 'May';
                        break;
                        case 6:
                         return 'Jun';
                        break;
                        case 7:
                     return  'July';
                        break;
                        case 8:
                     return  'Aug';
                        break;
                         case 9:
                     return  'Sep';
                        break;
                         case 10:
                     return  'Oct';
                        break;
                         case 11:
                         return 'Nov';
                        break;
                         case 12:
                     return  'Dec';
                        break;
                        
                    }
        }
public function getWeeksName($week){
            switch($week){
                        case 1:
                         return 'Mon';
                        break;
                        case 2:
                         return 'Tue';
                        break;
                        case 3:
                         return 'Wed';
                        break;
                        case 4:
                         return 'Thu';
                        break;
                        case 5:
                         return 'Fri';
                        break;
                        case 6:
                         return 'Sat';
                        break;
                        case 7:
                     return  'Sun';
                        break;
                        
                        
                    }
        }
    
    public function hotel_supplier_filter_subUser(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            
            $request_data           = json_decode($request->request_data);
            $all_hotel_suppliers    = DB::table('rooms_Invoice_Supplier')->where('customer_id',$userData->id)->where('SU_id',$request->SU_id)->get();
            $all_supplier_data      = [];
            
            if($request_data->date_type == 'all_data'){
                // Get All Data    
                    foreach($all_hotel_suppliers as $sup_res){
                    
                            $supplierBooking = DB::table('rooms')
                            ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                            ->leftJoin('add_manage_invoices', function ($join) {
                                $join->on('rooms_bookings_details.booking_id', '=', 'add_manage_invoices.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'Invoices');
                            })
                            ->leftJoin('tours', function ($join) {
                                $join->on('rooms_bookings_details.package_id', '=', 'tours.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'package');
                            })
                            ->leftJoin('hotels_bookings', function ($join) {
                                $join->on('rooms_bookings_details.booking_id', '=', 'hotels_bookings.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'website');
                            })
                            ->where('rooms.room_supplier_name',$sup_res->id)
                            ->where('rooms.SU_id',$request->SU_id)
                            ->select('rooms.id','rooms_bookings_details.booking_from','rooms_bookings_details.booking_id','rooms_bookings_details.package_id','rooms_bookings_details.quantity','rooms.room_supplier_name'
                                    ,'tours.accomodation_details as package_accomodation','tours.accomodation_details_more as package_accomodation_more','tours.id as package_id'
                                    ,'add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.id as Invoice_id'
                                    ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response')
                            ->orderBy('rooms.id','asc')
                            ->get();
                            
                            // print_r($supplierBooking);
                            // die;
                
                            $total_nights = 0;
                            $total_payable_price = 0;
                            $total_rooms_count = 0;
                            $supplier_rooms_bookings = [];
                            foreach($supplierBooking as $book_res){
                                
                              
                                // From Accomodation Details Invoice
                                if(isset($book_res->accomodation_details)){
                                    $accomodation_data = json_decode($book_res->accomodation_details);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                                $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->acc_hotel_name,
                                                        'room_id' => $acc_res->hotelRoom_type_id,
                                                        'room_type' => $acc_res->hotel_type_cat,
                                                        'check_in' => $acc_res->acc_check_in,
                                                        'check_out' => $acc_res->acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $acc_res->acc_qty,
                                                        'rooms_price' => $acc_res->price_per_room_purchase,
                                                        'rooms_total_price' => $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights),
                                                    ];
                                                
                                                $total_price = $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $acc_res->acc_qty;
                                            }
                                        }
                                        
                                        
                                    }  
                                }
                                
                                 // From More Accomodation Details Invoice
                                if(isset($book_res->accomodation_details_more)){
                                    $accomodation_data = json_decode($book_res->accomodation_details_more);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                                $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                 $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->more_acc_hotel_name,
                                                        'room_id' => $acc_res->more_hotelRoom_type_id,
                                                        'room_type' => $acc_res->more_hotel_type_cat,
                                                        'check_in' => $acc_res->more_acc_check_in,
                                                        'check_out' => $acc_res->more_acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $acc_res->more_acc_qty,
                                                        'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                        'rooms_total_price' => $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights),
                                                    ];
                                                    
                                                $total_price = $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $acc_res->more_acc_qty;    
                                            }
                                        }
                                        
                                        
                                    }
                                    
                                }
                                
                                // From Accomodation Details Package
                                if(isset($book_res->package_accomodation)){
                                    $accomodation_data = json_decode($book_res->package_accomodation);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                                $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->acc_hotel_name,
                                                        'room_id' => $acc_res->hotelRoom_type_id,
                                                        'room_type' => $acc_res->hotel_type_cat,
                                                        'check_in' => $acc_res->acc_check_in,
                                                        'check_out' => $acc_res->acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $book_res->quantity,
                                                        'rooms_price' => $acc_res->price_per_room_purchase,
                                                        'rooms_total_price' => $book_res->quantity * ($acc_res->price_per_room_purchase * $no_of_nights),
                                                    ];
                                                
                                                $total_price = $book_res->quantity * ($acc_res->price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $book_res->quantity;
                                            }
                                        }
                                        
                                        
                                    }  
                                }
                                
                                 // From More Accomodation Details Package
                                if(isset($book_res->package_accomodation_more)){
                                    $accomodation_data = json_decode($book_res->package_accomodation_more);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                                $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                 $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->more_acc_hotel_name,
                                                        'room_id' => $acc_res->more_hotelRoom_type_id,
                                                        'room_type' => $acc_res->more_hotel_type_cat,
                                                        'check_in' => $acc_res->more_acc_check_in,
                                                        'check_out' => $acc_res->more_acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $book_res->quantity,
                                                        'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                        'rooms_total_price' => (int)$book_res->quantity * ((int)$acc_res->more_price_per_room_purchase * (int)$no_of_nights),
                                                    ];
                                                    
                                                $total_price = (int)$book_res->quantity * ((int)$acc_res->more_price_per_room_purchase * (int)$no_of_nights);
                                                $total_payable_price += (int)$total_price;
                                                $total_rooms_count += (int)$book_res->quantity;    
                                            }
                                        }
                                        
                                        
                                    }
                                    
                                }
                                
                                
                                // From Website Booking
                                if(isset($book_res->reservation_response)){
                                    $reservation_data = json_decode($book_res->reservation_response);
                                    if($reservation_data){
                                     
                                                $diff = strtotime($reservation_data->hotel_details->checkOut) - strtotime($reservation_data->hotel_details->checkIn);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                foreach($reservation_data->hotel_details->rooms as $room_res){
                                                    $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $reservation_data->hotel_details->hotel_name,
                                                        'room_id' => $room_res->room_code,
                                                        'room_type' => $room_res->room_name,
                                                        'check_in' => $reservation_data->hotel_details->checkOut,
                                                        'check_out' => $reservation_data->hotel_details->checkOut,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $room_res->room_rates[0]->room_qty,
                                                        'rooms_price' => $room_res->room_rates[0]->net,
                                                        'rooms_total_price' => $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights),
                                                    ];
                                                    
                                                    $total_price = $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights);
                                                    $total_payable_price += $total_price;
                                                    $total_rooms_count += $room_res->room_rates[0]->room_qty;  
                                                }
                                            
                                                
                                        
                                    }
                                    
                                }
                                
                            }
                            
                            $supplier_data = DB::table('rooms_Invoice_Supplier')->where('rooms_Invoice_Supplier.SU_id',$request->SU_id)->where('id',$request->supplier_id)->select('id','room_supplier_name','payable','currrency')->first();
                            
                            $supplier_total_paid = DB::table('hotel_supplier_ledger')->where('hotel_supplier_ledger.SU_id',$request->SU_id)->where('supplier_id',$request->supplier_id)->where('payment_id','!=',NULL)->sum('received');
                            // echo "<pre>";
                            // print_r($supplier_total_paid);
                            // echo "</pre>";
                            // die;
                            
                            $all_supplier_data[] = (Object)[
                                    'id' => $sup_res->id,
                                    'room_supplier_name' => $sup_res->room_supplier_name,
                                    'payable' => number_format($total_payable_price),
                                    'payable_without_format' => $total_payable_price,
                                    'rem_payable' => $sup_res->payable,
                                    'currrency' => $sup_res->currrency,
                                    'booking_count' => $total_rooms_count,
                                    'no_of_nights' => $total_nights
                                ];
                                
                           
                    
                    }
            }
            
            if($request_data->date_type == 'data_today_wise'){
                // Get All Data    
                    $today_date = date('Y-m-d');
                    foreach($all_hotel_suppliers as $sup_res){
                    
                            $supplierBooking = DB::table('rooms')
                            ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                            ->leftJoin('add_manage_invoices', function ($join) {
                                $join->on('rooms_bookings_details.booking_id', '=', 'add_manage_invoices.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'Invoices');
                            })
                            ->leftJoin('tours', function ($join) {
                                $join->on('rooms_bookings_details.package_id', '=', 'tours.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'package');
                            })
                            ->leftJoin('hotels_bookings', function ($join) {
                                $join->on('rooms_bookings_details.booking_id', '=', 'hotels_bookings.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'website');
                            })
                            ->where('rooms.room_supplier_name',$sup_res->id)
                            ->where('rooms.SU_id',$request->SU_id)
                            ->whereDate('rooms_bookings_details.current_date',$today_date)
                            ->select('rooms.id','rooms_bookings_details.booking_from','rooms_bookings_details.booking_id','rooms_bookings_details.package_id','rooms_bookings_details.quantity','rooms.room_supplier_name'
                                    ,'tours.accomodation_details as package_accomodation','tours.accomodation_details_more as package_accomodation_more','tours.id as package_id'
                                    ,'add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.id as Invoice_id'
                                    ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response')
                            ->orderBy('rooms.id','asc')
                            ->get();
                            
                            
                
                            $total_nights = 0;
                            $total_payable_price = 0;
                            $total_rooms_count = 0;
                            $supplier_rooms_bookings = [];
                            foreach($supplierBooking as $book_res){
                                
                              
                                // From Accomodation Details
                                if(isset($book_res->accomodation_details)){
                                    $accomodation_data = json_decode($book_res->accomodation_details);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                                $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->acc_hotel_name,
                                                        'room_id' => $acc_res->hotelRoom_type_id,
                                                        'room_type' => $acc_res->hotel_type_cat,
                                                        'check_in' => $acc_res->acc_check_in,
                                                        'check_out' => $acc_res->acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $acc_res->acc_qty,
                                                        'rooms_price' => $acc_res->price_per_room_purchase,
                                                        'rooms_total_price' => $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights),
                                                    ];
                                                
                                                $total_price = $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $acc_res->acc_qty;
                                            }
                                        }
                                        
                                        
                                    }  
                                }
                                
                                 // From More Accomodation Details
                                if(isset($book_res->accomodation_details_more)){
                                    $accomodation_data = json_decode($book_res->accomodation_details_more);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                                $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                 $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->more_acc_hotel_name,
                                                        'room_id' => $acc_res->more_hotelRoom_type_id,
                                                        'room_type' => $acc_res->more_hotel_type_cat,
                                                        'check_in' => $acc_res->more_acc_check_in,
                                                        'check_out' => $acc_res->more_acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $acc_res->more_acc_qty,
                                                        'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                        'rooms_total_price' => $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights),
                                                    ];
                                                    
                                                $total_price = $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $acc_res->more_acc_qty;    
                                            }
                                        }
                                        
                                        
                                    }
                                    
                                }
                                
                                   // From Accomodation Details Package
                                if(isset($book_res->package_accomodation)){
                                    $accomodation_data = json_decode($book_res->package_accomodation);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                                $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->acc_hotel_name,
                                                        'room_id' => $acc_res->hotelRoom_type_id,
                                                        'room_type' => $acc_res->hotel_type_cat,
                                                        'check_in' => $acc_res->acc_check_in,
                                                        'check_out' => $acc_res->acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $book_res->quantity,
                                                        'rooms_price' => $acc_res->price_per_room_purchase,
                                                        'rooms_total_price' => $book_res->quantity * ($acc_res->price_per_room_purchase * $no_of_nights),
                                                    ];
                                                
                                                $total_price = $book_res->quantity * ($acc_res->price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $book_res->quantity;
                                            }
                                        }
                                        
                                        
                                    }  
                                }
                                
                                 // From More Accomodation Details Package
                                if(isset($book_res->package_accomodation_more)){
                                    $accomodation_data = json_decode($book_res->package_accomodation_more);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                                $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                 $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->more_acc_hotel_name,
                                                        'room_id' => $acc_res->more_hotelRoom_type_id,
                                                        'room_type' => $acc_res->more_hotel_type_cat,
                                                        'check_in' => $acc_res->more_acc_check_in,
                                                        'check_out' => $acc_res->more_acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $book_res->quantity,
                                                        'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                        'rooms_total_price' => (int)$book_res->quantity * ((int)$acc_res->more_price_per_room_purchase * (int)$no_of_nights),
                                                    ];
                                                    
                                                $total_price = (int)$book_res->quantity * ((int)$acc_res->more_price_per_room_purchase * (int)$no_of_nights);
                                                $total_payable_price += (int)$total_price;
                                                $total_rooms_count += (int)$book_res->quantity;    
                                            }
                                        }
                                        
                                        
                                    }
                                    
                                }
                                
                                // From Website Booking
                                if(isset($book_res->reservation_response)){
                                    $reservation_data = json_decode($book_res->reservation_response);
                                    if($reservation_data){
                                     
                                                $diff = strtotime($reservation_data->hotel_details->checkOut) - strtotime($reservation_data->hotel_details->checkIn);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                foreach($reservation_data->hotel_details->rooms as $room_res){
                                                    $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $reservation_data->hotel_details->hotel_name,
                                                        'room_id' => $room_res->room_code,
                                                        'room_type' => $room_res->room_name,
                                                        'check_in' => $reservation_data->hotel_details->checkOut,
                                                        'check_out' => $reservation_data->hotel_details->checkOut,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $room_res->room_rates[0]->room_qty,
                                                        'rooms_price' => $room_res->room_rates[0]->net,
                                                        'rooms_total_price' => $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights),
                                                    ];
                                                    
                                                    $total_price = $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights);
                                                    $total_payable_price += $total_price;
                                                    $total_rooms_count += $room_res->room_rates[0]->room_qty;  
                                                }
                                            
                                                
                                        
                                    }
                                    
                                }
                                
                            }
                            
                            $supplier_data = DB::table('rooms_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('id',$request->supplier_id)->select('id','room_supplier_name','payable','currrency')->first();
                            
                            $supplier_total_paid = DB::table('hotel_supplier_ledger')->where('SU_id',$request->SU_id)->where('supplier_id',$request->supplier_id)->where('payment_id','!=',NULL)->sum('received');
                            // echo "<pre>";
                            // print_r($supplier_total_paid);
                            // echo "</pre>";
                            // die;
                            
                            $all_supplier_data[] = (Object)[
                                    'id' => $sup_res->id,
                                    'room_supplier_name' => $sup_res->room_supplier_name,
                                    'payable' => number_format($total_payable_price),
                                    'payable_without_format' => $total_payable_price,
                                    'rem_payable' => $sup_res->payable,
                                    'currrency' => $sup_res->currrency,
                                    'booking_count' => $total_rooms_count,
                                    'no_of_nights' => $total_nights
                                ];
                                
                           
                    
                    }
            }
            
            if($request_data->date_type == 'data_Yesterday_wise'){
                // Get All Data    
                    $date = date('Y-m-d',strtotime("-1 days"));
                    // dd($date);
                    foreach($all_hotel_suppliers as $sup_res){
                    
                            $supplierBooking = DB::table('rooms')
                            ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                            ->leftJoin('add_manage_invoices', function ($join) {
                                $join->on('rooms_bookings_details.booking_id', '=', 'add_manage_invoices.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'Invoices');
                            })
                            ->leftJoin('tours', function ($join) {
                                $join->on('rooms_bookings_details.package_id', '=', 'tours.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'package');
                            })
                            ->leftJoin('hotels_bookings', function ($join) {
                                $join->on('rooms_bookings_details.booking_id', '=', 'hotels_bookings.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'website');
                            })
                            ->where('rooms.room_supplier_name',$sup_res->id)
                            ->where('rooms.SU_id',$request->SU_id)
                            ->whereDate('rooms_bookings_details.current_date',$date)
                               ->select('rooms.id','rooms_bookings_details.booking_from','rooms_bookings_details.booking_id','rooms_bookings_details.package_id','rooms_bookings_details.quantity','rooms.room_supplier_name'
                                    ,'tours.accomodation_details as package_accomodation','tours.accomodation_details_more as package_accomodation_more','tours.id as package_id'
                                    ,'add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.id as Invoice_id'
                                    ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response')
                            ->orderBy('rooms.id','asc')
                            ->get();
                            
                            // print_r($supplierBooking);
                
                            $total_nights = 0;
                            $total_payable_price = 0;
                            $total_rooms_count = 0;
                            $supplier_rooms_bookings = [];
                            foreach($supplierBooking as $book_res){
                                
                              
                                // From Accomodation Details
                                if(isset($book_res->accomodation_details)){
                                    $accomodation_data = json_decode($book_res->accomodation_details);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                                $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->acc_hotel_name,
                                                        'room_id' => $acc_res->hotelRoom_type_id,
                                                        'room_type' => $acc_res->hotel_type_cat,
                                                        'check_in' => $acc_res->acc_check_in,
                                                        'check_out' => $acc_res->acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $acc_res->acc_qty,
                                                        'rooms_price' => $acc_res->price_per_room_purchase,
                                                        'rooms_total_price' => $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights),
                                                    ];
                                                
                                                $total_price = $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $acc_res->acc_qty;
                                            }
                                        }
                                        
                                        
                                    }  
                                }
                                
                                 // From More Accomodation Details
                                if(isset($book_res->accomodation_details_more)){
                                    $accomodation_data = json_decode($book_res->accomodation_details_more);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                                $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                 $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->more_acc_hotel_name,
                                                        'room_id' => $acc_res->more_hotelRoom_type_id,
                                                        'room_type' => $acc_res->more_hotel_type_cat,
                                                        'check_in' => $acc_res->more_acc_check_in,
                                                        'check_out' => $acc_res->more_acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $acc_res->more_acc_qty,
                                                        'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                        'rooms_total_price' => $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights),
                                                    ];
                                                    
                                                $total_price = $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $acc_res->more_acc_qty;    
                                            }
                                        }
                                        
                                        
                                    }
                                    
                                }
                                
                                          // From Accomodation Details Package
                                if(isset($book_res->package_accomodation)){
                                    $accomodation_data = json_decode($book_res->package_accomodation);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                                $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->acc_hotel_name,
                                                        'room_id' => $acc_res->hotelRoom_type_id,
                                                        'room_type' => $acc_res->hotel_type_cat,
                                                        'check_in' => $acc_res->acc_check_in,
                                                        'check_out' => $acc_res->acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $book_res->quantity,
                                                        'rooms_price' => $acc_res->price_per_room_purchase,
                                                        'rooms_total_price' => $book_res->quantity * ($acc_res->price_per_room_purchase * $no_of_nights),
                                                    ];
                                                
                                                $total_price = $book_res->quantity * ($acc_res->price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $book_res->quantity;
                                            }
                                        }
                                        
                                        
                                    }  
                                }
                                
                                 // From More Accomodation Details Package
                                if(isset($book_res->package_accomodation_more)){
                                    $accomodation_data = json_decode($book_res->package_accomodation_more);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                                $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                 $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->more_acc_hotel_name,
                                                        'room_id' => $acc_res->more_hotelRoom_type_id,
                                                        'room_type' => $acc_res->more_hotel_type_cat,
                                                        'check_in' => $acc_res->more_acc_check_in,
                                                        'check_out' => $acc_res->more_acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $book_res->quantity,
                                                        'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                        'rooms_total_price' => (int)$book_res->quantity * ((int)$acc_res->more_price_per_room_purchase * (int)$no_of_nights),
                                                    ];
                                                    
                                                $total_price = (int)$book_res->quantity * ((int)$acc_res->more_price_per_room_purchase * (int)$no_of_nights);
                                                $total_payable_price += (int)$total_price;
                                                $total_rooms_count += (int)$book_res->quantity;    
                                            }
                                        }
                                        
                                        
                                    }
                                    
                                }
                                
                                // From Website Booking
                                if(isset($book_res->reservation_response)){
                                    $reservation_data = json_decode($book_res->reservation_response);
                                    if($reservation_data){
                                     
                                                $diff = strtotime($reservation_data->hotel_details->checkOut) - strtotime($reservation_data->hotel_details->checkIn);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                foreach($reservation_data->hotel_details->rooms as $room_res){
                                                    $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $reservation_data->hotel_details->hotel_name,
                                                        'room_id' => $room_res->room_code,
                                                        'room_type' => $room_res->room_name,
                                                        'check_in' => $reservation_data->hotel_details->checkOut,
                                                        'check_out' => $reservation_data->hotel_details->checkOut,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $room_res->room_rates[0]->room_qty,
                                                        'rooms_price' => $room_res->room_rates[0]->net,
                                                        'rooms_total_price' => $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights),
                                                    ];
                                                    
                                                    $total_price = $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights);
                                                    $total_payable_price += $total_price;
                                                    $total_rooms_count += $room_res->room_rates[0]->room_qty;  
                                                }
                                            
                                                
                                        
                                    }
                                    
                                }
                                
                            }
                            
                            $supplier_data = DB::table('rooms_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('id',$request->supplier_id)->select('id','room_supplier_name','payable','currrency')->first();
                            
                            $supplier_total_paid = DB::table('hotel_supplier_ledger')->where('SU_id',$request->SU_id)->where('supplier_id',$request->supplier_id)->where('payment_id','!=',NULL)->sum('received');
                            // echo "<pre>";
                            // print_r($supplier_total_paid);
                            // echo "</pre>";
                            // die;
                            
                            $all_supplier_data[] = (Object)[
                                    'id' => $sup_res->id,
                                    'room_supplier_name' => $sup_res->room_supplier_name,
                                    'payable' => number_format($total_payable_price),
                                    'payable_without_format' => $total_payable_price,
                                    'rem_payable' => $sup_res->payable,
                                    'currrency' => $sup_res->currrency,
                                    'booking_count' => $total_rooms_count,
                                    'no_of_nights' => $total_nights
                                ];
                                
                           
                    
                    }
            }
            
            if($request_data->date_type == 'data_week_wise'){
                // Get All Data    
                    $startOfWeek = Carbon::now()->startOfWeek();
                    $start_date = $startOfWeek->format('Y-m-d');
                    $end_date = date('Y-m-d');
                    // dd($date);
                    foreach($all_hotel_suppliers as $sup_res){
                    
                            $supplierBooking = DB::table('rooms')
                            ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                            ->leftJoin('add_manage_invoices', function ($join) {
                                $join->on('rooms_bookings_details.booking_id', '=', 'add_manage_invoices.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'Invoices');
                            })
                            ->leftJoin('tours', function ($join) {
                                $join->on('rooms_bookings_details.package_id', '=', 'tours.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'package');
                            })
                            ->leftJoin('hotels_bookings', function ($join) {
                                $join->on('rooms_bookings_details.booking_id', '=', 'hotels_bookings.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'website');
                            })
                            ->where('rooms.room_supplier_name',$sup_res->id)
                            ->where('rooms.SU_id',$request->SU_id)
                            ->whereDate('rooms_bookings_details.current_date', '>=', $start_date)
                            ->whereDate('rooms_bookings_details.current_date', '<=', $end_date)
                            ->select('rooms.id','rooms_bookings_details.booking_from','rooms_bookings_details.booking_id','rooms_bookings_details.package_id','rooms_bookings_details.quantity','rooms.room_supplier_name'
                                    ,'tours.accomodation_details as package_accomodation','tours.accomodation_details_more as package_accomodation_more','tours.id as package_id'
                                    ,'add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.id as Invoice_id'
                                    ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response')
                            ->orderBy('rooms.id','asc')
                            ->get();
                            
                
                            $total_nights = 0;
                            $total_payable_price = 0;
                            $total_rooms_count = 0;
                            $supplier_rooms_bookings = [];
                            foreach($supplierBooking as $book_res){
                                
                              
                                // From Accomodation Details
                                if(isset($book_res->accomodation_details)){
                                    $accomodation_data = json_decode($book_res->accomodation_details);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                                $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->acc_hotel_name,
                                                        'room_id' => $acc_res->hotelRoom_type_id,
                                                        'room_type' => $acc_res->hotel_type_cat,
                                                        'check_in' => $acc_res->acc_check_in,
                                                        'check_out' => $acc_res->acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $acc_res->acc_qty,
                                                        'rooms_price' => $acc_res->price_per_room_purchase,
                                                        'rooms_total_price' => $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights),
                                                    ];
                                                
                                                $total_price = $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $acc_res->acc_qty;
                                            }
                                        }
                                        
                                        
                                    }  
                                }
                                
                                 // From More Accomodation Details
                                if(isset($book_res->accomodation_details_more)){
                                    $accomodation_data = json_decode($book_res->accomodation_details_more);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                                $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                 $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->more_acc_hotel_name,
                                                        'room_id' => $acc_res->more_hotelRoom_type_id,
                                                        'room_type' => $acc_res->more_hotel_type_cat,
                                                        'check_in' => $acc_res->more_acc_check_in,
                                                        'check_out' => $acc_res->more_acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $acc_res->more_acc_qty,
                                                        'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                        'rooms_total_price' => $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights),
                                                    ];
                                                    
                                                $total_price = $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $acc_res->more_acc_qty;    
                                            }
                                        }
                                        
                                        
                                    }
                                    
                                }
                                
                                     // From Accomodation Details Package
                                if(isset($book_res->package_accomodation)){
                                    $accomodation_data = json_decode($book_res->package_accomodation);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                                $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->acc_hotel_name,
                                                        'room_id' => $acc_res->hotelRoom_type_id,
                                                        'room_type' => $acc_res->hotel_type_cat,
                                                        'check_in' => $acc_res->acc_check_in,
                                                        'check_out' => $acc_res->acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $book_res->quantity,
                                                        'rooms_price' => $acc_res->price_per_room_purchase,
                                                        'rooms_total_price' => $book_res->quantity * ($acc_res->price_per_room_purchase * $no_of_nights),
                                                    ];
                                                
                                                $total_price = $book_res->quantity * ($acc_res->price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $book_res->quantity;
                                            }
                                        }
                                        
                                        
                                    }  
                                }
                                
                                 // From More Accomodation Details Package
                                if(isset($book_res->package_accomodation_more)){
                                    $accomodation_data = json_decode($book_res->package_accomodation_more);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                                $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                 $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->more_acc_hotel_name,
                                                        'room_id' => $acc_res->more_hotelRoom_type_id,
                                                        'room_type' => $acc_res->more_hotel_type_cat,
                                                        'check_in' => $acc_res->more_acc_check_in,
                                                        'check_out' => $acc_res->more_acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $book_res->quantity,
                                                        'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                        'rooms_total_price' => (int)$book_res->quantity * ((int)$acc_res->more_price_per_room_purchase * (int)$no_of_nights),
                                                    ];
                                                    
                                                $total_price = (int)$book_res->quantity * ((int)$acc_res->more_price_per_room_purchase * (int)$no_of_nights);
                                                $total_payable_price += (int)$total_price;
                                                $total_rooms_count += (int)$book_res->quantity;    
                                            }
                                        }
                                        
                                        
                                    }
                                    
                                }
                                
                                // From Website Booking
                                if(isset($book_res->reservation_response)){
                                    $reservation_data = json_decode($book_res->reservation_response);
                                    if($reservation_data){
                                     
                                                $diff = strtotime($reservation_data->hotel_details->checkOut) - strtotime($reservation_data->hotel_details->checkIn);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                foreach($reservation_data->hotel_details->rooms as $room_res){
                                                    $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $reservation_data->hotel_details->hotel_name,
                                                        'room_id' => $room_res->room_code,
                                                        'room_type' => $room_res->room_name,
                                                        'check_in' => $reservation_data->hotel_details->checkOut,
                                                        'check_out' => $reservation_data->hotel_details->checkOut,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $room_res->room_rates[0]->room_qty,
                                                        'rooms_price' => $room_res->room_rates[0]->net,
                                                        'rooms_total_price' => $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights),
                                                    ];
                                                    
                                                    $total_price = $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights);
                                                    $total_payable_price += $total_price;
                                                    $total_rooms_count += $room_res->room_rates[0]->room_qty;  
                                                }
                                            
                                                
                                        
                                    }
                                    
                                }
                                
                            }
                            
                            $supplier_data = DB::table('rooms_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('id',$request->supplier_id)->select('id','room_supplier_name','payable','currrency')->first();
                            
                            $supplier_total_paid = DB::table('hotel_supplier_ledger')->where('SU_id',$request->SU_id)->where('supplier_id',$request->supplier_id)->where('payment_id','!=',NULL)->sum('received');
                            // echo "<pre>";
                            // print_r($supplier_total_paid);
                            // echo "</pre>";
                            // die;
                            
                            $all_supplier_data[] = (Object)[
                                    'id' => $sup_res->id,
                                    'room_supplier_name' => $sup_res->room_supplier_name,
                                    'payable' => number_format($total_payable_price),
                                    'payable_without_format' => $total_payable_price,
                                    'rem_payable' => $sup_res->payable,
                                    'currrency' => $sup_res->currrency,
                                    'booking_count' => $total_rooms_count,
                                    'no_of_nights' => $total_nights
                                ];
                                
                           
                    
                    }
            }
            
            if($request_data->date_type == 'data_month_wise'){
                // Get All Data    
                    $startOfMonth = Carbon::now()->startOfMonth();
                    $start_date = $startOfMonth->format('Y-m-d');
                    $end_date = date('Y-m-d');
                    // dd($start_date);
                    foreach($all_hotel_suppliers as $sup_res){
                    
                            $supplierBooking = DB::table('rooms')
                            ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                            ->leftJoin('add_manage_invoices', function ($join) {
                                $join->on('rooms_bookings_details.booking_id', '=', 'add_manage_invoices.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'Invoices');
                            })
                            ->leftJoin('tours', function ($join) {
                                $join->on('rooms_bookings_details.package_id', '=', 'tours.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'package');
                            })
                            ->leftJoin('hotels_bookings', function ($join) {
                                $join->on('rooms_bookings_details.booking_id', '=', 'hotels_bookings.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'website');
                            })
                            ->where('rooms.room_supplier_name',$sup_res->id)
                            ->where('rooms.SU_id',$request->SU_id)
                            ->whereDate('rooms_bookings_details.current_date', '>=', $start_date)
                            ->whereDate('rooms_bookings_details.current_date', '<=', $end_date)
                           ->select('rooms.id','rooms_bookings_details.booking_from','rooms_bookings_details.booking_id','rooms_bookings_details.package_id','rooms_bookings_details.quantity','rooms.room_supplier_name'
                                    ,'tours.accomodation_details as package_accomodation','tours.accomodation_details_more as package_accomodation_more','tours.id as package_id'
                                    ,'add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.id as Invoice_id'
                                    ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response')
                            ->orderBy('rooms.id','asc')
                            ->get();
                            
                
                            $total_nights = 0;
                            $total_payable_price = 0;
                            $total_rooms_count = 0;
                            $supplier_rooms_bookings = [];
                            foreach($supplierBooking as $book_res){
                                
                              
                                // From Accomodation Details
                                if(isset($book_res->accomodation_details)){
                                    $accomodation_data = json_decode($book_res->accomodation_details);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                                $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->acc_hotel_name,
                                                        'room_id' => $acc_res->hotelRoom_type_id,
                                                        'room_type' => $acc_res->hotel_type_cat,
                                                        'check_in' => $acc_res->acc_check_in,
                                                        'check_out' => $acc_res->acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $acc_res->acc_qty,
                                                        'rooms_price' => $acc_res->price_per_room_purchase,
                                                        'rooms_total_price' => $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights),
                                                    ];
                                                
                                                $total_price = $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $acc_res->acc_qty;
                                            }
                                        }
                                        
                                        
                                    }  
                                }
                                
                                 // From More Accomodation Details
                                if(isset($book_res->accomodation_details_more)){
                                    $accomodation_data = json_decode($book_res->accomodation_details_more);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                                $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                 $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->more_acc_hotel_name,
                                                        'room_id' => $acc_res->more_hotelRoom_type_id,
                                                        'room_type' => $acc_res->more_hotel_type_cat,
                                                        'check_in' => $acc_res->more_acc_check_in,
                                                        'check_out' => $acc_res->more_acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $acc_res->more_acc_qty,
                                                        'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                        'rooms_total_price' => $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights),
                                                    ];
                                                    
                                                $total_price = $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $acc_res->more_acc_qty;    
                                            }
                                        }
                                        
                                        
                                    }
                                    
                                }
                                
                                     // From Accomodation Details Package
                                if(isset($book_res->package_accomodation)){
                                    $accomodation_data = json_decode($book_res->package_accomodation);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                                $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->acc_hotel_name,
                                                        'room_id' => $acc_res->hotelRoom_type_id,
                                                        'room_type' => $acc_res->hotel_type_cat,
                                                        'check_in' => $acc_res->acc_check_in,
                                                        'check_out' => $acc_res->acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $book_res->quantity,
                                                        'rooms_price' => $acc_res->price_per_room_purchase,
                                                        'rooms_total_price' => $book_res->quantity * ($acc_res->price_per_room_purchase * $no_of_nights),
                                                    ];
                                                
                                                $total_price = $book_res->quantity * ($acc_res->price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $book_res->quantity;
                                            }
                                        }
                                        
                                        
                                    }  
                                }
                                
                                 // From More Accomodation Details Package
                                if(isset($book_res->package_accomodation_more)){
                                    $accomodation_data = json_decode($book_res->package_accomodation_more);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                                $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                 $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->more_acc_hotel_name,
                                                        'room_id' => $acc_res->more_hotelRoom_type_id,
                                                        'room_type' => $acc_res->more_hotel_type_cat,
                                                        'check_in' => $acc_res->more_acc_check_in,
                                                        'check_out' => $acc_res->more_acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $book_res->quantity,
                                                        'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                        'rooms_total_price' => (int)$book_res->quantity * ((int)$acc_res->more_price_per_room_purchase * (int)$no_of_nights),
                                                    ];
                                                    
                                                $total_price = (int)$book_res->quantity * ((int)$acc_res->more_price_per_room_purchase * (int)$no_of_nights);
                                                $total_payable_price += (int)$total_price;
                                                $total_rooms_count += (int)$book_res->quantity;    
                                            }
                                        }
                                        
                                        
                                    }
                                    
                                }
                                
                                // From Website Booking
                                if(isset($book_res->reservation_response)){
                                    $reservation_data = json_decode($book_res->reservation_response);
                                    if($reservation_data){
                                     
                                                $diff = strtotime($reservation_data->hotel_details->checkOut) - strtotime($reservation_data->hotel_details->checkIn);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                foreach($reservation_data->hotel_details->rooms as $room_res){
                                                    $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $reservation_data->hotel_details->hotel_name,
                                                        'room_id' => $room_res->room_code,
                                                        'room_type' => $room_res->room_name,
                                                        'check_in' => $reservation_data->hotel_details->checkOut,
                                                        'check_out' => $reservation_data->hotel_details->checkOut,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $room_res->room_rates[0]->room_qty,
                                                        'rooms_price' => $room_res->room_rates[0]->net,
                                                        'rooms_total_price' => $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights),
                                                    ];
                                                    
                                                    $total_price = $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights);
                                                    $total_payable_price += $total_price;
                                                    $total_rooms_count += $room_res->room_rates[0]->room_qty;  
                                                }
                                            
                                                
                                        
                                    }
                                    
                                }
                                
                            }
                            
                            $supplier_data = DB::table('rooms_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('id',$request->supplier_id)->select('id','room_supplier_name','payable','currrency')->first();
                            
                            $supplier_total_paid = DB::table('hotel_supplier_ledger')->where('SU_id',$request->SU_id)->where('supplier_id',$request->supplier_id)->where('payment_id','!=',NULL)->sum('received');
                            // echo "<pre>";
                            // print_r($supplier_total_paid);
                            // echo "</pre>";
                            // die;
                            
                            $all_supplier_data[] = (Object)[
                                    'id' => $sup_res->id,
                                    'room_supplier_name' => $sup_res->room_supplier_name,
                                    'payable' => number_format($total_payable_price),
                                    'payable_without_format' => $total_payable_price,
                                    'rem_payable' => $sup_res->payable,
                                    'currrency' => $sup_res->currrency,
                                    'booking_count' => $total_rooms_count,
                                    'no_of_nights' => $total_nights
                                ];
                                
                           
                    
                    }
            }
            
            if($request_data->date_type == 'data_year_wise'){
                // Get All Data    
                    $startOfYear = Carbon::now()->startOfYear();
                    $start_date = $startOfYear->format('Y-m-d');
                    $end_date = date('Y-m-d');
                    // dd($start_date);
                    foreach($all_hotel_suppliers as $sup_res){
                    
                            $supplierBooking = DB::table('rooms')
                            ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                            ->leftJoin('add_manage_invoices', function ($join) {
                                $join->on('rooms_bookings_details.booking_id', '=', 'add_manage_invoices.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'Invoices');
                            })
                            ->leftJoin('tours', function ($join) {
                                $join->on('rooms_bookings_details.package_id', '=', 'tours.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'package');
                            })
                            ->leftJoin('hotels_bookings', function ($join) {
                                $join->on('rooms_bookings_details.booking_id', '=', 'hotels_bookings.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'website');
                            })
                            ->where('rooms.room_supplier_name',$sup_res->id)
                            ->where('rooms.SU_id',$request->SU_id)
                            ->whereDate('rooms_bookings_details.current_date', '>=', $start_date)
                            ->whereDate('rooms_bookings_details.current_date', '<=', $end_date)
                           ->select('rooms.id','rooms_bookings_details.booking_from','rooms_bookings_details.booking_id','rooms_bookings_details.package_id','rooms_bookings_details.quantity','rooms.room_supplier_name'
                                    ,'tours.accomodation_details as package_accomodation','tours.accomodation_details_more as package_accomodation_more','tours.id as package_id'
                                    ,'add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.id as Invoice_id'
                                    ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response')
                            ->orderBy('rooms.id','asc')
                            ->get();
                            
                
                            $total_nights = 0;
                            $total_payable_price = 0;
                            $total_rooms_count = 0;
                            $supplier_rooms_bookings = [];
                            foreach($supplierBooking as $book_res){
                                
                              
                                // From Accomodation Details
                                if(isset($book_res->accomodation_details)){
                                    $accomodation_data = json_decode($book_res->accomodation_details);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                                $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->acc_hotel_name,
                                                        'room_id' => $acc_res->hotelRoom_type_id,
                                                        'room_type' => $acc_res->hotel_type_cat,
                                                        'check_in' => $acc_res->acc_check_in,
                                                        'check_out' => $acc_res->acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $acc_res->acc_qty,
                                                        'rooms_price' => $acc_res->price_per_room_purchase,
                                                        'rooms_total_price' => $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights),
                                                    ];
                                                
                                                $total_price = $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $acc_res->acc_qty;
                                            }
                                        }
                                        
                                        
                                    }  
                                }
                                
                                 // From More Accomodation Details
                                if(isset($book_res->accomodation_details_more)){
                                    $accomodation_data = json_decode($book_res->accomodation_details_more);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                                $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                 $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->more_acc_hotel_name,
                                                        'room_id' => $acc_res->more_hotelRoom_type_id,
                                                        'room_type' => $acc_res->more_hotel_type_cat,
                                                        'check_in' => $acc_res->more_acc_check_in,
                                                        'check_out' => $acc_res->more_acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $acc_res->more_acc_qty,
                                                        'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                        'rooms_total_price' => $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights),
                                                    ];
                                                    
                                                $total_price = $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $acc_res->more_acc_qty;    
                                            }
                                        }
                                        
                                        
                                    }
                                    
                                }
                                
                                     // From Accomodation Details Package
                                if(isset($book_res->package_accomodation)){
                                    $accomodation_data = json_decode($book_res->package_accomodation);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                                $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->acc_hotel_name,
                                                        'room_id' => $acc_res->hotelRoom_type_id,
                                                        'room_type' => $acc_res->hotel_type_cat,
                                                        'check_in' => $acc_res->acc_check_in,
                                                        'check_out' => $acc_res->acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $book_res->quantity,
                                                        'rooms_price' => $acc_res->price_per_room_purchase,
                                                        'rooms_total_price' => $book_res->quantity * ($acc_res->price_per_room_purchase * $no_of_nights),
                                                    ];
                                                
                                                $total_price = $book_res->quantity * ($acc_res->price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $book_res->quantity;
                                            }
                                        }
                                        
                                        
                                    }  
                                }
                                
                                 // From More Accomodation Details Package
                                if(isset($book_res->package_accomodation_more)){
                                    $accomodation_data = json_decode($book_res->package_accomodation_more);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                                $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                 $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->more_acc_hotel_name,
                                                        'room_id' => $acc_res->more_hotelRoom_type_id,
                                                        'room_type' => $acc_res->more_hotel_type_cat,
                                                        'check_in' => $acc_res->more_acc_check_in,
                                                        'check_out' => $acc_res->more_acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $book_res->quantity,
                                                        'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                        'rooms_total_price' => (int)$book_res->quantity * ((int)$acc_res->more_price_per_room_purchase * (int)$no_of_nights),
                                                    ];
                                                    
                                                $total_price = (int)$book_res->quantity * ((int)$acc_res->more_price_per_room_purchase * (int)$no_of_nights);
                                                $total_payable_price += (int)$total_price;
                                                $total_rooms_count += (int)$book_res->quantity;    
                                            }
                                        }
                                        
                                        
                                    }
                                    
                                }
                                
                                // From Website Booking
                                if(isset($book_res->reservation_response)){
                                    $reservation_data = json_decode($book_res->reservation_response);
                                    if($reservation_data){
                                     
                                                $diff = strtotime($reservation_data->hotel_details->checkOut) - strtotime($reservation_data->hotel_details->checkIn);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                foreach($reservation_data->hotel_details->rooms as $room_res){
                                                    $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $reservation_data->hotel_details->hotel_name,
                                                        'room_id' => $room_res->room_code,
                                                        'room_type' => $room_res->room_name,
                                                        'check_in' => $reservation_data->hotel_details->checkOut,
                                                        'check_out' => $reservation_data->hotel_details->checkOut,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $room_res->room_rates[0]->room_qty,
                                                        'rooms_price' => $room_res->room_rates[0]->net,
                                                        'rooms_total_price' => $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights),
                                                    ];
                                                    
                                                    $total_price = $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights);
                                                    $total_payable_price += $total_price;
                                                    $total_rooms_count += $room_res->room_rates[0]->room_qty;  
                                                }
                                            
                                                
                                        
                                    }
                                    
                                }
                                
                            }
                            
                            $supplier_data = DB::table('rooms_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('id',$request->supplier_id)->select('id','room_supplier_name','payable','currrency')->first();
                            
                            $supplier_total_paid = DB::table('hotel_supplier_ledger')->where('SU_id',$request->SU_id)->where('supplier_id',$request->supplier_id)->where('payment_id','!=',NULL)->sum('received');
                            // echo "<pre>";
                            // print_r($supplier_total_paid);
                            // echo "</pre>";
                            // die;
                            
                            $all_supplier_data[] = (Object)[
                                    'id' => $sup_res->id,
                                    'room_supplier_name' => $sup_res->room_supplier_name,
                                    'payable' => number_format($total_payable_price),
                                    'payable_without_format' => $total_payable_price,
                                    'rem_payable' => $sup_res->payable,
                                    'currrency' => $sup_res->currrency,
                                    'booking_count' => $total_rooms_count,
                                    'no_of_nights' => $total_nights
                                ];
                                
                           
                    
                    }
            }
            
            if($request_data->date_type == 'data_wise'){
                // Get All Data    
                    $start_date = $request_data->start_date;
                    $end_date = $request_data->end_date;
                    // dd($start_date);
                    foreach($all_hotel_suppliers as $sup_res){
                    
                            $supplierBooking = DB::table('rooms')
                            ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                            ->leftJoin('add_manage_invoices', function ($join) {
                                $join->on('rooms_bookings_details.booking_id', '=', 'add_manage_invoices.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'Invoices');
                            })
                            ->leftJoin('tours', function ($join) {
                                $join->on('rooms_bookings_details.package_id', '=', 'tours.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'package');
                            })
                            ->leftJoin('hotels_bookings', function ($join) {
                                $join->on('rooms_bookings_details.booking_id', '=', 'hotels_bookings.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'website');
                            })
                            ->where('rooms.room_supplier_name',$sup_res->id)
                            ->where('rooms.SU_id',$request->SU_id)
                            ->whereDate('rooms_bookings_details.current_date', '>=', $start_date)
                            ->whereDate('rooms_bookings_details.current_date', '<=', $end_date)
                            ->select('rooms.id','rooms_bookings_details.booking_from','rooms_bookings_details.booking_id','rooms_bookings_details.package_id','rooms_bookings_details.quantity','rooms.room_supplier_name'
                                    ,'tours.accomodation_details as package_accomodation','tours.accomodation_details_more as package_accomodation_more','tours.id as package_id'
                                    ,'add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.id as Invoice_id'
                                    ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response')
                            ->orderBy('rooms.id','asc')
                            ->get();
                            
                
                            $total_nights = 0;
                            $total_payable_price = 0;
                            $total_rooms_count = 0;
                            $supplier_rooms_bookings = [];
                            foreach($supplierBooking as $book_res){
                                
                              
                                // From Accomodation Details
                                if(isset($book_res->accomodation_details)){
                                    $accomodation_data = json_decode($book_res->accomodation_details);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                                $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->acc_hotel_name,
                                                        'room_id' => $acc_res->hotelRoom_type_id,
                                                        'room_type' => $acc_res->hotel_type_cat,
                                                        'check_in' => $acc_res->acc_check_in,
                                                        'check_out' => $acc_res->acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $acc_res->acc_qty,
                                                        'rooms_price' => $acc_res->price_per_room_purchase,
                                                        'rooms_total_price' => $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights),
                                                    ];
                                                
                                                $total_price = $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $acc_res->acc_qty;
                                            }
                                        }
                                        
                                        
                                    }  
                                }
                                
                                 // From More Accomodation Details
                                if(isset($book_res->accomodation_details_more)){
                                    $accomodation_data = json_decode($book_res->accomodation_details_more);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                                $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                 $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->more_acc_hotel_name,
                                                        'room_id' => $acc_res->more_hotelRoom_type_id,
                                                        'room_type' => $acc_res->more_hotel_type_cat,
                                                        'check_in' => $acc_res->more_acc_check_in,
                                                        'check_out' => $acc_res->more_acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $acc_res->more_acc_qty,
                                                        'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                        'rooms_total_price' => $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights),
                                                    ];
                                                    
                                                $total_price = $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $acc_res->more_acc_qty;    
                                            }
                                        }
                                        
                                        
                                    }
                                    
                                }
                                
                                     // From Accomodation Details Package
                                if(isset($book_res->package_accomodation)){
                                    $accomodation_data = json_decode($book_res->package_accomodation);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                                $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->acc_hotel_name,
                                                        'room_id' => $acc_res->hotelRoom_type_id,
                                                        'room_type' => $acc_res->hotel_type_cat,
                                                        'check_in' => $acc_res->acc_check_in,
                                                        'check_out' => $acc_res->acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $book_res->quantity,
                                                        'rooms_price' => $acc_res->price_per_room_purchase,
                                                        'rooms_total_price' => $book_res->quantity * ($acc_res->price_per_room_purchase * $no_of_nights),
                                                    ];
                                                
                                                $total_price = $book_res->quantity * ($acc_res->price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $book_res->quantity;
                                            }
                                        }
                                        
                                        
                                    }  
                                }
                                
                                 // From More Accomodation Details Package
                                if(isset($book_res->package_accomodation_more)){
                                    $accomodation_data = json_decode($book_res->package_accomodation_more);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                                $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                 $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->more_acc_hotel_name,
                                                        'room_id' => $acc_res->more_hotelRoom_type_id,
                                                        'room_type' => $acc_res->more_hotel_type_cat,
                                                        'check_in' => $acc_res->more_acc_check_in,
                                                        'check_out' => $acc_res->more_acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $book_res->quantity,
                                                        'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                        'rooms_total_price' => (int)$book_res->quantity * ((int)$acc_res->more_price_per_room_purchase * (int)$no_of_nights),
                                                    ];
                                                    
                                                $total_price = (int)$book_res->quantity * ((int)$acc_res->more_price_per_room_purchase * (int)$no_of_nights);
                                                $total_payable_price += (int)$total_price;
                                                $total_rooms_count += (int)$book_res->quantity;    
                                            }
                                        }
                                        
                                        
                                    }
                                    
                                }
                                
                                // From Website Booking
                                if(isset($book_res->reservation_response)){
                                    $reservation_data = json_decode($book_res->reservation_response);
                                    if($reservation_data){
                                     
                                                $diff = strtotime($reservation_data->hotel_details->checkOut) - strtotime($reservation_data->hotel_details->checkIn);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                foreach($reservation_data->hotel_details->rooms as $room_res){
                                                    $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $reservation_data->hotel_details->hotel_name,
                                                        'room_id' => $room_res->room_code,
                                                        'room_type' => $room_res->room_name,
                                                        'check_in' => $reservation_data->hotel_details->checkOut,
                                                        'check_out' => $reservation_data->hotel_details->checkOut,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $room_res->room_rates[0]->room_qty,
                                                        'rooms_price' => $room_res->room_rates[0]->net,
                                                        'rooms_total_price' => $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights),
                                                    ];
                                                    
                                                    $total_price = $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights);
                                                    $total_payable_price += $total_price;
                                                    $total_rooms_count += $room_res->room_rates[0]->room_qty;  
                                                }
                                            
                                                
                                        
                                    }
                                    
                                }
                                
                            }
                            
                            $supplier_data = DB::table('rooms_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('id',$request->supplier_id)->select('id','room_supplier_name','payable','currrency')->first();
                            
                            $supplier_total_paid = DB::table('hotel_supplier_ledger')->where('SU_id',$request->SU_id)->where('supplier_id',$request->supplier_id)->where('payment_id','!=',NULL)->sum('received');
                            // echo "<pre>";
                            // print_r($supplier_total_paid);
                            // echo "</pre>";
                            // die;
                            
                            $all_supplier_data[] = (Object)[
                                    'id' => $sup_res->id,
                                    'room_supplier_name' => $sup_res->room_supplier_name,
                                    'payable' => number_format($total_payable_price),
                                    'payable_without_format' => $total_payable_price,
                                    'rem_payable' => $sup_res->payable,
                                    'currrency' => $sup_res->currrency,
                                    'booking_count' => $total_rooms_count,
                                    'no_of_nights' => $total_nights
                                ];
                                
                           
                    
                    }
            }
            
            if($request_data->filter_type == 'total_revenue'){
                    
                    // If Filter Type is Revenue
                    
                    $all_supplier_data = new Collection($all_supplier_data);
                    
                    $all_supplier_data = $all_supplier_data->sortByDesc('payable_without_format');
                    
                    $all_supplier_data = $all_supplier_data->values();
                    
                    $all_supplier_data = $all_supplier_data->toArray();
                    
                    // print_r($all_supplier_data);
                    
                    $series_data = [];
                    $categories_data = [];
                    
                    if(sizeOf($all_supplier_data) >= 4){
                        $limitedSupplierData = array_slice($all_supplier_data, 0, 4);
                    }else{
                        $limitedSupplierData = $all_supplier_data;
                    }
                    
                    if($request_data->date_type == 'all_data'){
                        $currentYear = date('Y');
                        $monthsData = [];
                    
                        for ($month = 1; $month <= 12; $month++) {
                            
                             $startOfMonth = Carbon::create($currentYear, $month, 1)->startOfMonth();
                             $endOfMonth = Carbon::create($currentYear, $month, 1)->endOfMonth();
                             
                              $categories_data[] = $startOfMonth->format('F');
                             
                             $startOfMonth = $startOfMonth->format('Y-m-d');
                             $endOfMonth = $endOfMonth->format('Y-m-d');
            
                            $monthsData[] = (Object)[
                                'month' => $month,
                                'start_date' => $startOfMonth,
                                'end_date' => $endOfMonth,
                            ];
                        }
                        
                        foreach($limitedSupplierData as $sup_res){
                            $supplier_booking_data = [];
                            foreach($monthsData as $month_res){
                                // Add 7 days to the start date
  

                                  $supplierBooking = DB::table('rooms')
                                ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                                ->leftJoin('add_manage_invoices', function ($join) {
                                    $join->on('rooms_bookings_details.booking_id', '=', 'add_manage_invoices.id')
                                        ->where('rooms_bookings_details.booking_from', '=', 'Invoices');
                                })
                                ->leftJoin('tours', function ($join) {
                                    $join->on('rooms_bookings_details.package_id', '=', 'tours.id')
                                        ->where('rooms_bookings_details.booking_from', '=', 'package');
                                })
                                ->leftJoin('hotels_bookings', function ($join) {
                                    $join->on('rooms_bookings_details.booking_id', '=', 'hotels_bookings.id')
                                        ->where('rooms_bookings_details.booking_from', '=', 'website');
                                })
                                ->where('rooms.room_supplier_name',$sup_res->id)
                                ->where('rooms.SU_id',$request->SU_id)
                                ->whereDate('rooms_bookings_details.current_date','>=', $month_res->start_date)
                                ->whereDate('rooms_bookings_details.current_date','<=', $month_res->end_date)
                                ->select('rooms.id','rooms_bookings_details.booking_from','rooms_bookings_details.booking_id','rooms_bookings_details.package_id','rooms.room_supplier_name'
                                        ,'tours.accomodation_details','tours.accomodation_details_more','tours.id as package_id'
                                        ,'add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.id as Invoice_id'
                                        ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response')
                                ->orderBy('rooms.id','asc')
                                ->get();
                                
                                $total_payable_price = 0;
                                foreach($supplierBooking as $book_res){
                                    // From Accomodation Details
                                    if(isset($book_res->accomodation_details)){
                                        $accomodation_data = json_decode($book_res->accomodation_details);
                                        if($accomodation_data){
                                            foreach($accomodation_data as $acc_res){
                                                if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                                    $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                                    $no_of_nights = abs(round($diff / 86400));
                                                    
                                                    $total_nights += $no_of_nights;
                                                    
                                                    $supplier_rooms_bookings[] = (Object)[
                                                            'hotel_name' => $acc_res->acc_hotel_name,
                                                            'room_id' => $acc_res->hotelRoom_type_id,
                                                            'room_type' => $acc_res->hotel_type_cat,
                                                            'check_in' => $acc_res->acc_check_in,
                                                            'check_out' => $acc_res->acc_check_out,
                                                            'no_of_nights' => $no_of_nights,
                                                            'rooms_qty' => $acc_res->acc_qty,
                                                            'rooms_price' => $acc_res->price_per_room_purchase,
                                                            'rooms_total_price' => $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights),
                                                        ];
                                                    
                                                    $total_price = $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights);
                                                    $total_payable_price += $total_price;
                                                    $total_rooms_count += $acc_res->acc_qty;
                                                }
                                            }
                                            
                                            
                                        }  
                                    }
                                    
                                    // From More Accomodation Details
                                    if(isset($book_res->accomodation_details_more)){
                                        $accomodation_data = json_decode($book_res->accomodation_details_more);
                                        if($accomodation_data){
                                            foreach($accomodation_data as $acc_res){
                                                if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                                    $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                                    $no_of_nights = abs(round($diff / 86400));
                                                    
                                                    $total_nights += $no_of_nights;
                                                    
                                                     $supplier_rooms_bookings[] = (Object)[
                                                            'hotel_name' => $acc_res->more_acc_hotel_name,
                                                            'room_id' => $acc_res->more_hotelRoom_type_id,
                                                            'room_type' => $acc_res->more_hotel_type_cat,
                                                            'check_in' => $acc_res->more_acc_check_in,
                                                            'check_out' => $acc_res->more_acc_check_out,
                                                            'no_of_nights' => $no_of_nights,
                                                            'rooms_qty' => $acc_res->more_acc_qty,
                                                            'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                            'rooms_total_price' => $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights),
                                                        ];
                                                        
                                                    $total_price = $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights);
                                                    $total_payable_price += $total_price;
                                                    $total_rooms_count += $acc_res->more_acc_qty;    
                                                }
                                            }
                                            
                                            
                                        }
                                        
                                    }
                                    
                                    // From Website Booking
                                    if(isset($book_res->reservation_response)){
                                        $reservation_data = json_decode($book_res->reservation_response);
                                        if($reservation_data){
                                         
                                                    $diff = strtotime($reservation_data->hotel_details->checkOut) - strtotime($reservation_data->hotel_details->checkIn);
                                                    $no_of_nights = abs(round($diff / 86400));
                                                    
                                                    $total_nights += $no_of_nights;
                                                    
                                                    foreach($reservation_data->hotel_details->rooms as $room_res){
                                                        $supplier_rooms_bookings[] = (Object)[
                                                            'hotel_name' => $reservation_data->hotel_details->hotel_name,
                                                            'room_id' => $room_res->room_code,
                                                            'room_type' => $room_res->room_name,
                                                            'check_in' => $reservation_data->hotel_details->checkOut,
                                                            'check_out' => $reservation_data->hotel_details->checkOut,
                                                            'no_of_nights' => $no_of_nights,
                                                            'rooms_qty' => $room_res->room_rates[0]->room_qty,
                                                            'rooms_price' => $room_res->room_rates[0]->net,
                                                            'rooms_total_price' => $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights),
                                                        ];
                                                        
                                                        $total_price = $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights);
                                                        $total_payable_price += $total_price;
                                                        $total_rooms_count += $room_res->room_rates[0]->room_qty;  
                                                    }
                                                
                                                    
                                            
                                        }
                                        
                                    }
                                }
                                $supplier_booking_data[] = floor($total_payable_price * 100) / 100;
                               
                            }
                            
                            $series_data[] = [
                                    'name' => $sup_res->room_supplier_name,
                                    'data' => $supplier_booking_data
                                ];
                        }
                    }
                    
                    if($request_data->date_type == 'data_today_wise'){
            
                            $date = date('Y-m-d');
                            foreach($limitedSupplierData as $supplier_res){
                                
                                $supplier_booking_data = [$supplier_res->payable_without_format];
                                $series_data[] = [
                                        'name' => $supplier_res->room_supplier_name,
                                        'data' => $supplier_booking_data
                                    ];
                            }
                            
                            $categories_data = [$date];
                     
                    }
                    
                    if($request_data->date_type == 'data_Yesterday_wise'){
            
                            $date = date('Y-m-d',strtotime("-1 days"));
                            foreach($limitedSupplierData as $supplier_res){
                                
                                $supplier_booking_data = [$supplier_res->payable_without_format];
                                $series_data[] = [
                                        'name' => $supplier_res->room_supplier_name,
                                        'data' => $supplier_booking_data
                                    ];
                            }
                            
                            $categories_data = [$date];
                     
                    }
                    
                    // Generate Graph Data For Week
                    if($request_data->date_type == 'data_week_wise'){
                       $today = Carbon::now();
                        
                        // Create a CarbonPeriod instance for the current week
                       $startOfWeek = $today->startOfWeek()->toDateString();
                        $endOfWeek = $today->endOfWeek()->toDateString();
                        
                        // Create a CarbonPeriod instance for the current week
                        $period = CarbonPeriod::create($startOfWeek, $endOfWeek);
                        
                        // Get the dates of the current week as an array
                        $datesOfWeek = [];
                        foreach ($period as $date) {
                            $datesOfWeek[] = $date->toDateString();
                        }
                        
                        // Get Data for Agent 
                        
                        foreach($limitedSupplierData as $supplier_res){
                            
                            // Loop On DatesOfWeek
                            
                            $supplier_booking_data = [];
                            foreach($datesOfWeek as $date_res){

                                $supplierBooking = DB::table('rooms')
                                ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                                ->leftJoin('add_manage_invoices', function ($join) {
                                    $join->on('rooms_bookings_details.booking_id', '=', 'add_manage_invoices.id')
                                        ->where('rooms_bookings_details.booking_from', '=', 'Invoices');
                                })
                                ->leftJoin('tours', function ($join) {
                                    $join->on('rooms_bookings_details.package_id', '=', 'tours.id')
                                        ->where('rooms_bookings_details.booking_from', '=', 'package');
                                })
                                ->leftJoin('hotels_bookings', function ($join) {
                                    $join->on('rooms_bookings_details.booking_id', '=', 'hotels_bookings.id')
                                        ->where('rooms_bookings_details.booking_from', '=', 'website');
                                })
                                ->where('rooms.room_supplier_name',$supplier_res->id)
                                ->where('rooms.SU_id',$request->SU_id)
                                ->whereDate('rooms_bookings_details.current_date', $date_res)
                                ->select('rooms.id','rooms_bookings_details.booking_from','rooms_bookings_details.booking_id','rooms_bookings_details.package_id','rooms.room_supplier_name'
                                        ,'tours.accomodation_details','tours.accomodation_details_more','tours.id as package_id'
                                        ,'add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.id as Invoice_id'
                                        ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response')
                                ->orderBy('rooms.id','asc')
                                ->get();
                                
                    
                                $total_payable_price = 0;
                                foreach($supplierBooking as $book_res){
                                    
                                  
                                    // From Accomodation Details
                                    if(isset($book_res->accomodation_details)){
                                        $accomodation_data = json_decode($book_res->accomodation_details);
                                        if($accomodation_data){
                                            foreach($accomodation_data as $acc_res){
                                                if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                                    $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                                    $no_of_nights = abs(round($diff / 86400));
                                                    
                                                    $total_nights += $no_of_nights;
                                                    
                                                    $supplier_rooms_bookings[] = (Object)[
                                                            'hotel_name' => $acc_res->acc_hotel_name,
                                                            'room_id' => $acc_res->hotelRoom_type_id,
                                                            'room_type' => $acc_res->hotel_type_cat,
                                                            'check_in' => $acc_res->acc_check_in,
                                                            'check_out' => $acc_res->acc_check_out,
                                                            'no_of_nights' => $no_of_nights,
                                                            'rooms_qty' => $acc_res->acc_qty,
                                                            'rooms_price' => $acc_res->price_per_room_purchase,
                                                            'rooms_total_price' => $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights),
                                                        ];
                                                    
                                                    $total_price = $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights);
                                                    $total_payable_price += $total_price;
                                                    $total_rooms_count += $acc_res->acc_qty;
                                                }
                                            }
                                            
                                            
                                        }  
                                    }
                                    
                                     // From More Accomodation Details
                                    if(isset($book_res->accomodation_details_more)){
                                        $accomodation_data = json_decode($book_res->accomodation_details_more);
                                        if($accomodation_data){
                                            foreach($accomodation_data as $acc_res){
                                                if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                                    $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                                    $no_of_nights = abs(round($diff / 86400));
                                                    
                                                    $total_nights += $no_of_nights;
                                                    
                                                     $supplier_rooms_bookings[] = (Object)[
                                                            'hotel_name' => $acc_res->more_acc_hotel_name,
                                                            'room_id' => $acc_res->more_hotelRoom_type_id,
                                                            'room_type' => $acc_res->more_hotel_type_cat,
                                                            'check_in' => $acc_res->more_acc_check_in,
                                                            'check_out' => $acc_res->more_acc_check_out,
                                                            'no_of_nights' => $no_of_nights,
                                                            'rooms_qty' => $acc_res->more_acc_qty,
                                                            'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                            'rooms_total_price' => $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights),
                                                        ];
                                                        
                                                    $total_price = $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights);
                                                    $total_payable_price += $total_price;
                                                    $total_rooms_count += $acc_res->more_acc_qty;    
                                                }
                                            }
                                            
                                            
                                        }
                                        
                                    }
                                    
                                    // From Website Booking
                                    if(isset($book_res->reservation_response)){
                                        $reservation_data = json_decode($book_res->reservation_response);
                                        if($reservation_data){
                                         
                                                    $diff = strtotime($reservation_data->hotel_details->checkOut) - strtotime($reservation_data->hotel_details->checkIn);
                                                    $no_of_nights = abs(round($diff / 86400));
                                                    
                                                    $total_nights += $no_of_nights;
                                                    
                                                    foreach($reservation_data->hotel_details->rooms as $room_res){
                                                        $supplier_rooms_bookings[] = (Object)[
                                                            'hotel_name' => $reservation_data->hotel_details->hotel_name,
                                                            'room_id' => $room_res->room_code,
                                                            'room_type' => $room_res->room_name,
                                                            'check_in' => $reservation_data->hotel_details->checkOut,
                                                            'check_out' => $reservation_data->hotel_details->checkOut,
                                                            'no_of_nights' => $no_of_nights,
                                                            'rooms_qty' => $room_res->room_rates[0]->room_qty,
                                                            'rooms_price' => $room_res->room_rates[0]->net,
                                                            'rooms_total_price' => $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights),
                                                        ];
                                                        
                                                        $total_price = $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights);
                                                        $total_payable_price += $total_price;
                                                        $total_rooms_count += $room_res->room_rates[0]->room_qty;  
                                                    }
                                                
                                                    
                                            
                                        }
                                        
                                    }
                                    
                                }
                                                
                              
                                
                                $supplier_booking_data[] = $total_payable_price;
                            }
                            
                            $series_data[] = [
                                    'name' => $supplier_res->room_supplier_name,
                                    'data' => $supplier_booking_data
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
                
                        foreach($limitedSupplierData as $supplier_res){
                            
                            // Start Date Of Month
                            // $currentDate = Carbon::now();
                            $startDate = Carbon::now()->startOfMonth();
                            // $startDate = $currentDate->subMonth()->firstOfMonth();
                            $startDateWeek = $startDate->toDateString();
                            $endDate = $startDate->copy()->addDays(6);
                            $endDateWeek = $endDate->toDateString();
                            
                            $supplier_booking_data = [];
                            for($i=1; $i<=5; $i++){
                                // Add 7 days to the start date
                       
                                
                                 $supplierBooking = DB::table('rooms')
                                ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                                ->leftJoin('add_manage_invoices', function ($join) {
                                    $join->on('rooms_bookings_details.booking_id', '=', 'add_manage_invoices.id')
                                        ->where('rooms_bookings_details.booking_from', '=', 'Invoices');
                                })
                                ->leftJoin('tours', function ($join) {
                                    $join->on('rooms_bookings_details.package_id', '=', 'tours.id')
                                        ->where('rooms_bookings_details.booking_from', '=', 'package');
                                })
                                ->leftJoin('hotels_bookings', function ($join) {
                                    $join->on('rooms_bookings_details.booking_id', '=', 'hotels_bookings.id')
                                        ->where('rooms_bookings_details.booking_from', '=', 'website');
                                })
                                ->where('rooms.room_supplier_name',$supplier_res->id)
                                ->where('rooms.SU_id',$request->SU_id)
                                ->whereDate('rooms_bookings_details.current_date','>=', $startDate)
                                ->whereDate('rooms_bookings_details.current_date','<=', $endDate)
                                ->select('rooms.id','rooms_bookings_details.booking_from','rooms_bookings_details.booking_id','rooms_bookings_details.package_id','rooms.room_supplier_name'
                                        ,'tours.accomodation_details','tours.accomodation_details_more','tours.id as package_id'
                                        ,'add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.id as Invoice_id'
                                        ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response')
                                ->orderBy('rooms.id','asc')
                                ->get();
                                
                    
                                $total_payable_price = 0;
                                foreach($supplierBooking as $book_res){
                                    
                                  
                                    // From Accomodation Details
                                    if(isset($book_res->accomodation_details)){
                                        $accomodation_data = json_decode($book_res->accomodation_details);
                                        if($accomodation_data){
                                            foreach($accomodation_data as $acc_res){
                                                if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                                    $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                                    $no_of_nights = abs(round($diff / 86400));
                                                    
                                                    $total_nights += $no_of_nights;
                                                    
                                                    $supplier_rooms_bookings[] = (Object)[
                                                            'hotel_name' => $acc_res->acc_hotel_name,
                                                            'room_id' => $acc_res->hotelRoom_type_id,
                                                            'room_type' => $acc_res->hotel_type_cat,
                                                            'check_in' => $acc_res->acc_check_in,
                                                            'check_out' => $acc_res->acc_check_out,
                                                            'no_of_nights' => $no_of_nights,
                                                            'rooms_qty' => $acc_res->acc_qty,
                                                            'rooms_price' => $acc_res->price_per_room_purchase,
                                                            'rooms_total_price' => $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights),
                                                        ];
                                                    
                                                    $total_price = $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights);
                                                    $total_payable_price += $total_price;
                                                    $total_rooms_count += $acc_res->acc_qty;
                                                }
                                            }
                                            
                                            
                                        }  
                                    }
                                    
                                     // From More Accomodation Details
                                    if(isset($book_res->accomodation_details_more)){
                                        $accomodation_data = json_decode($book_res->accomodation_details_more);
                                        if($accomodation_data){
                                            foreach($accomodation_data as $acc_res){
                                                if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                                    $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                                    $no_of_nights = abs(round($diff / 86400));
                                                    
                                                    $total_nights += $no_of_nights;
                                                    
                                                     $supplier_rooms_bookings[] = (Object)[
                                                            'hotel_name' => $acc_res->more_acc_hotel_name,
                                                            'room_id' => $acc_res->more_hotelRoom_type_id,
                                                            'room_type' => $acc_res->more_hotel_type_cat,
                                                            'check_in' => $acc_res->more_acc_check_in,
                                                            'check_out' => $acc_res->more_acc_check_out,
                                                            'no_of_nights' => $no_of_nights,
                                                            'rooms_qty' => $acc_res->more_acc_qty,
                                                            'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                            'rooms_total_price' => $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights),
                                                        ];
                                                        
                                                    $total_price = $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights);
                                                    $total_payable_price += $total_price;
                                                    $total_rooms_count += $acc_res->more_acc_qty;    
                                                }
                                            }
                                            
                                            
                                        }
                                        
                                    }
                                    
                                    // From Website Booking
                                    if(isset($book_res->reservation_response)){
                                        $reservation_data = json_decode($book_res->reservation_response);
                                        if($reservation_data){
                                         
                                                    $diff = strtotime($reservation_data->hotel_details->checkOut) - strtotime($reservation_data->hotel_details->checkIn);
                                                    $no_of_nights = abs(round($diff / 86400));
                                                    
                                                    $total_nights += $no_of_nights;
                                                    
                                                    foreach($reservation_data->hotel_details->rooms as $room_res){
                                                        $supplier_rooms_bookings[] = (Object)[
                                                            'hotel_name' => $reservation_data->hotel_details->hotel_name,
                                                            'room_id' => $room_res->room_code,
                                                            'room_type' => $room_res->room_name,
                                                            'check_in' => $reservation_data->hotel_details->checkOut,
                                                            'check_out' => $reservation_data->hotel_details->checkOut,
                                                            'no_of_nights' => $no_of_nights,
                                                            'rooms_qty' => $room_res->room_rates[0]->room_qty,
                                                            'rooms_price' => $room_res->room_rates[0]->net,
                                                            'rooms_total_price' => $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights),
                                                        ];
                                                        
                                                        $total_price = $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights);
                                                        $total_payable_price += $total_price;
                                                        $total_rooms_count += $room_res->room_rates[0]->room_qty;  
                                                    }
                                                
                                                    
                                            
                                        }
                                        
                                    }
                                    
                                }
                                                
                              
                                
                                $supplier_booking_data[] = floor($total_payable_price * 100) / 100;
                                
                                
                                $startDate = $endDate->copy()->addDays(1);
                                if($i == '4'){
                                    $endDate = $startDate->copy()->addDays(2);
                                }else{
                                     $endDate = $startDate->copy()->addDays(6);
                                }
                                
                                $startDateWeek = $startDate->toDateString();
                                $endDateWeek = $endDate->toDateString();
                            }
                            
                            $series_data[] = [
                                    'name' =>  $supplier_res->room_supplier_name,
                                    'data' => $supplier_booking_data
                                ];
                        }
                            
                       
                            // dd($startDateWeek,$endDateWeek);
                        //   print_r($weekDates);
                        //   die;
                        $categories_data = ['1st Week','2nd Week','3rd Week','4th Week','5th Week'];
                    }
                    
                    // Generate Graph Data For Year
                    if($request_data->date_type == 'data_year_wise'){
                        
                        $currentYear = date('Y');
                        $monthsData = [];
                    
                        for ($month = 1; $month <= 12; $month++) {
                            
                             $startOfMonth = Carbon::create($currentYear, $month, 1)->startOfMonth();
                             $endOfMonth = Carbon::create($currentYear, $month, 1)->endOfMonth();
                             
                              $categories_data[] = $startOfMonth->format('F');
                             
                             $startOfMonth = $startOfMonth->format('Y-m-d');
                             $endOfMonth = $endOfMonth->format('Y-m-d');
            
                            $monthsData[] = (Object)[
                                'month' => $month,
                                'start_date' => $startOfMonth,
                                'end_date' => $endOfMonth,
                            ];
                        }
                        
                        foreach($limitedSupplierData as $sup_res){
                            $supplier_booking_data = [];
                            foreach($monthsData as $month_res){
                                // Add 7 days to the start date
  

                                  $supplierBooking = DB::table('rooms')
                                ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                                ->leftJoin('add_manage_invoices', function ($join) {
                                    $join->on('rooms_bookings_details.booking_id', '=', 'add_manage_invoices.id')
                                        ->where('rooms_bookings_details.booking_from', '=', 'Invoices');
                                })
                                ->leftJoin('tours', function ($join) {
                                    $join->on('rooms_bookings_details.package_id', '=', 'tours.id')
                                        ->where('rooms_bookings_details.booking_from', '=', 'package');
                                })
                                ->leftJoin('hotels_bookings', function ($join) {
                                    $join->on('rooms_bookings_details.booking_id', '=', 'hotels_bookings.id')
                                        ->where('rooms_bookings_details.booking_from', '=', 'website');
                                })
                                ->where('rooms.room_supplier_name',$sup_res->id)
                                ->where('rooms.SU_id',$request->SU_id)
                                ->whereDate('rooms_bookings_details.current_date','>=', $month_res->start_date)
                                ->whereDate('rooms_bookings_details.current_date','<=', $month_res->end_date)
                                ->select('rooms.id','rooms_bookings_details.booking_from','rooms_bookings_details.booking_id','rooms_bookings_details.package_id','rooms.room_supplier_name'
                                        ,'tours.accomodation_details','tours.accomodation_details_more','tours.id as package_id'
                                        ,'add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.id as Invoice_id'
                                        ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response')
                                ->orderBy('rooms.id','asc')
                                ->get();
                                
                    
                                $total_payable_price = 0;
                                foreach($supplierBooking as $book_res){
                                    
                                  
                                    // From Accomodation Details
                                    if(isset($book_res->accomodation_details)){
                                        $accomodation_data = json_decode($book_res->accomodation_details);
                                        if($accomodation_data){
                                            foreach($accomodation_data as $acc_res){
                                                if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                                    $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                                    $no_of_nights = abs(round($diff / 86400));
                                                    
                                                    $total_nights += $no_of_nights;
                                                    
                                                    $supplier_rooms_bookings[] = (Object)[
                                                            'hotel_name' => $acc_res->acc_hotel_name,
                                                            'room_id' => $acc_res->hotelRoom_type_id,
                                                            'room_type' => $acc_res->hotel_type_cat,
                                                            'check_in' => $acc_res->acc_check_in,
                                                            'check_out' => $acc_res->acc_check_out,
                                                            'no_of_nights' => $no_of_nights,
                                                            'rooms_qty' => $acc_res->acc_qty,
                                                            'rooms_price' => $acc_res->price_per_room_purchase,
                                                            'rooms_total_price' => $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights),
                                                        ];
                                                    
                                                    $total_price = $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights);
                                                    $total_payable_price += $total_price;
                                                    $total_rooms_count += $acc_res->acc_qty;
                                                }
                                            }
                                            
                                            
                                        }  
                                    }
                                    
                                     // From More Accomodation Details
                                    if(isset($book_res->accomodation_details_more)){
                                        $accomodation_data = json_decode($book_res->accomodation_details_more);
                                        if($accomodation_data){
                                            foreach($accomodation_data as $acc_res){
                                                if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                                    $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                                    $no_of_nights = abs(round($diff / 86400));
                                                    
                                                    $total_nights += $no_of_nights;
                                                    
                                                     $supplier_rooms_bookings[] = (Object)[
                                                            'hotel_name' => $acc_res->more_acc_hotel_name,
                                                            'room_id' => $acc_res->more_hotelRoom_type_id,
                                                            'room_type' => $acc_res->more_hotel_type_cat,
                                                            'check_in' => $acc_res->more_acc_check_in,
                                                            'check_out' => $acc_res->more_acc_check_out,
                                                            'no_of_nights' => $no_of_nights,
                                                            'rooms_qty' => $acc_res->more_acc_qty,
                                                            'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                            'rooms_total_price' => $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights),
                                                        ];
                                                        
                                                    $total_price = $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights);
                                                    $total_payable_price += $total_price;
                                                    $total_rooms_count += $acc_res->more_acc_qty;    
                                                }
                                            }
                                            
                                            
                                        }
                                        
                                    }
                                    
                                    // From Website Booking
                                    if(isset($book_res->reservation_response)){
                                        $reservation_data = json_decode($book_res->reservation_response);
                                        if($reservation_data){
                                         
                                                    $diff = strtotime($reservation_data->hotel_details->checkOut) - strtotime($reservation_data->hotel_details->checkIn);
                                                    $no_of_nights = abs(round($diff / 86400));
                                                    
                                                    $total_nights += $no_of_nights;
                                                    
                                                    foreach($reservation_data->hotel_details->rooms as $room_res){
                                                        $supplier_rooms_bookings[] = (Object)[
                                                            'hotel_name' => $reservation_data->hotel_details->hotel_name,
                                                            'room_id' => $room_res->room_code,
                                                            'room_type' => $room_res->room_name,
                                                            'check_in' => $reservation_data->hotel_details->checkOut,
                                                            'check_out' => $reservation_data->hotel_details->checkOut,
                                                            'no_of_nights' => $no_of_nights,
                                                            'rooms_qty' => $room_res->room_rates[0]->room_qty,
                                                            'rooms_price' => $room_res->room_rates[0]->net,
                                                            'rooms_total_price' => $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights),
                                                        ];
                                                        
                                                        $total_price = $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights);
                                                        $total_payable_price += $total_price;
                                                        $total_rooms_count += $room_res->room_rates[0]->room_qty;  
                                                    }
                                                
                                                    
                                            
                                        }
                                        
                                    }
                                    
                                }
                                                
                              
                                
                                $supplier_booking_data[] = floor($total_payable_price * 100) / 100;
                               
                            }
                            
                            $series_data[] = [
                                    'name' => $sup_res->room_supplier_name,
                                    'data' => $supplier_booking_data
                                ];
                        }
                        // print_r($series_data);
                        // die;
            
                    }
                    
                    return response()->json([
                            'status' => 'success',
                            'by' => 'total_reven',
                            'data' => $limitedSupplierData,
                            'series_data' => $series_data,
                            'categories_data' => $categories_data,
                        ]);
                    
            }else{
                    
                    $all_supplier_data = new Collection($all_supplier_data);
                    
                    $all_supplier_data = $all_supplier_data->sortByDesc('booking_count');
                    
                    $all_supplier_data = $all_supplier_data->values();
                    
                    $all_supplier_data = $all_supplier_data->toArray();
                    
                    $limitedSupplierData = array_slice($all_supplier_data, 0, 4);
                    
                    if(sizeOf($all_supplier_data) >= 4){
                        $limitedSupplierData = array_slice($all_supplier_data, 0, 4);
                    }else{
                        $limitedSupplierData = $all_supplier_data;
                    }
                    
                    $series_data        = [];
                    $categories_data    = [];
                    
                    if($request_data->date_type == 'all_data'){
                        $currentYear = date('Y');
                        $monthsData = [];
                    
                        for ($month = 1; $month <= 12; $month++) {
                            
                             $startOfMonth = Carbon::create($currentYear, $month, 1)->startOfMonth();
                             $endOfMonth = Carbon::create($currentYear, $month, 1)->endOfMonth();
                             
                              $categories_data[] = $startOfMonth->format('F');
                             
                             $startOfMonth = $startOfMonth->format('Y-m-d');
                             $endOfMonth = $endOfMonth->format('Y-m-d');
            
                            $monthsData[] = (Object)[
                                'month' => $month,
                                'start_date' => $startOfMonth,
                                'end_date' => $endOfMonth,
                            ];
                        }
                        
                        foreach($limitedSupplierData as $supplier_res){
                            
                       
                            
                            $supplier_booking_data = [];
                            foreach($monthsData as $month_res){
                                // Add 7 days to the start date
                                
                                
                                $totalQuantityBooked = DB::table('rooms')
                                                        ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                                                        ->where('rooms.room_supplier_name', $supplier_res->id)
                                                        ->where('rooms.SU_id',$request->SU_id)
                                                        ->whereDate('rooms_bookings_details.current_date','>=', $month_res->start_date)
                                                        ->whereDate('rooms_bookings_details.current_date','<=', $month_res->end_date)
                                                        ->sum('rooms_bookings_details.quantity');
                                                        
                            
                                                            
                              
                                
                                $supplier_booking_data[] = floor($totalQuantityBooked * 100) / 100;
                               
                            }
                            
                            $series_data[] = [
                                    'name' => $supplier_res->room_supplier_name,
                                    'data' => $supplier_booking_data
                                ];
                        }
                    }
                    
                    if($request_data->date_type == 'data_today_wise'){
            
                             $date = date('Y-m-d');
                            foreach($limitedSupplierData as $supplier_res){
                                
                                 $totalQuantityBooked = DB::table('rooms')
                                                        ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                                                        ->where('rooms.room_supplier_name', $supplier_res->id)
                                                        ->where('rooms.SU_id',$request->SU_id)
                                                        ->whereDate('rooms_bookings_details.current_date', $date)
                                                        ->sum('rooms_bookings_details.quantity');
                                
                                $supplier_booking_data = [$totalQuantityBooked];
                                $series_data[] = [
                                        'name' => $supplier_res->room_supplier_name,
                                        'data' => $supplier_booking_data
                                    ];
                            }
                            
                            $categories_data = [$date];
                     
                    }
                    
                    if($request_data->date_type == 'data_Yesterday_wise'){
            
                             $date = date('Y-m-d',strtotime("-1 days"));
                            foreach($limitedSupplierData as $supplier_res){
                                
                               $totalQuantityBooked = DB::table('rooms')
                                                        ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                                                        ->where('rooms.room_supplier_name', $supplier_res->id)
                                                        ->where('rooms.SU_id',$request->SU_id)
                                                        ->whereDate('rooms_bookings_details.current_date', $date)
                                                        ->sum('rooms_bookings_details.quantity');
                                
                                $supplier_booking_data = [$totalQuantityBooked];
                                $series_data[] = [
                                        'name' => $supplier_res->room_supplier_name,
                                        'data' => $supplier_booking_data
                                    ];
                            }
                            
                            $categories_data = [$date];
                     
                    }
                    
                    // Generate Graph Data For Week
                    if($request_data->date_type == 'data_week_wise'){
                       $today = Carbon::now();
                        
                        // Create a CarbonPeriod instance for the current week
                       $startOfWeek = $today->startOfWeek()->toDateString();
                        $endOfWeek = $today->endOfWeek()->toDateString();
                        
                        // Create a CarbonPeriod instance for the current week
                        $period = CarbonPeriod::create($startOfWeek, $endOfWeek);
                        
                        // Get the dates of the current week as an array
                        $datesOfWeek = [];
                        foreach ($period as $date) {
                            $datesOfWeek[] = $date->toDateString();
                        }
                        
                        // Get Data for Agent 
                        
                        foreach($limitedSupplierData as $supplier_res){
                            
                            // Loop On DatesOfWeek
                            
                            $supplier_booking_data = [];
                            foreach($datesOfWeek as $date_res){

                              $totalQuantityBooked = DB::table('rooms')
                                                        ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                                                        ->where('rooms.room_supplier_name', $supplier_res->id)
                                                        ->where('rooms.SU_id',$request->SU_id)
                                                        ->whereDate('rooms_bookings_details.current_date',$date_res)
                                                        ->sum('rooms_bookings_details.quantity');
                                                
                              
                                
                                $supplier_booking_data[] = $totalQuantityBooked;
                            }
                            
                            $series_data[] = [
                                    'name' => $supplier_res->room_supplier_name,
                                    'data' => $supplier_booking_data
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
                        
                        foreach($limitedSupplierData as $supplier_res){
                            
                            // Start Date Of Month
                            // $currentDate = Carbon::now();
                            $startDate = Carbon::now()->startOfMonth();
                            // $startDate = $currentDate->subMonth()->firstOfMonth();
                            $startDateWeek = $startDate->toDateString();
                            $endDate = $startDate->copy()->addDays(6);
                            $endDateWeek = $endDate->toDateString();
                            
                            $supplier_booking_data = [];
                            for($i=1; $i<=5; $i++){
                                // Add 7 days to the start date
                                
                                 $totalQuantityBooked = DB::table('rooms')
                                                        ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                                                        ->where('rooms.room_supplier_name', $supplier_res->id)
                                                        ->where('rooms.SU_id',$request->SU_id)
                                                        ->whereDate('rooms_bookings_details.current_date','>=', $startDate)
                                                        ->whereDate('rooms_bookings_details.current_date','<=', $endDate)
                                                        ->sum('rooms_bookings_details.quantity');
                                                
                              
                                
                                $supplier_booking_data[] = $totalQuantityBooked;
                                
                                
                                $startDate = $endDate->copy()->addDays(1);
                                if($i == '4'){
                                    $endDate = $startDate->copy()->addDays(2);
                                }else{
                                     $endDate = $startDate->copy()->addDays(6);
                                }
                                
                                $startDateWeek = $startDate->toDateString();
                                $endDateWeek = $endDate->toDateString();
                            }
                            
                            $series_data[] = [
                                   'name' => $supplier_res->room_supplier_name,
                                    'data' => $supplier_booking_data
                                ];
                        }
                            
                       
                            // dd($startDateWeek,$endDateWeek);
                        //   print_r($weekDates);
                        //   die;
                        $categories_data = ['1st Week','2nd Week','3rd Week','4th Week','5th Week'];
                    }
                    
                    // Generate Graph Data For Year
                    if($request_data->date_type == 'data_year_wise'){
                        
                        $currentYear = date('Y');
                        $monthsData = [];
                    
                        for ($month = 1; $month <= 12; $month++) {
                            
                             $startOfMonth = Carbon::create($currentYear, $month, 1)->startOfMonth();
                             $endOfMonth = Carbon::create($currentYear, $month, 1)->endOfMonth();
                             
                              $categories_data[] = $startOfMonth->format('F');
                             
                             $startOfMonth = $startOfMonth->format('Y-m-d');
                             $endOfMonth = $endOfMonth->format('Y-m-d');
            
                            $monthsData[] = (Object)[
                                'month' => $month,
                                'start_date' => $startOfMonth,
                                'end_date' => $endOfMonth,
                            ];
                        }
                        
                        foreach($limitedSupplierData as $supplier_res){
                            
                       
                            
                            $supplier_booking_data = [];
                            foreach($monthsData as $month_res){
                                // Add 7 days to the start date
                                
                                 $totalQuantityBooked = DB::table('rooms')
                                                        ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                                                        ->where('rooms.room_supplier_name', $supplier_res->id)
                                                        ->where('rooms.SU_id',$request->SU_id)
                                                        ->whereDate('rooms_bookings_details.current_date','>=', $month_res->start_date)
                                                        ->whereDate('rooms_bookings_details.current_date','<=', $month_res->end_date)
                                                        ->sum('rooms_bookings_details.quantity');
                                                
                              
                                
                                $supplier_booking_data[] = floor($totalQuantityBooked * 100) / 100;
                               
                            }
                            
                            $series_data[] = [
                                    'name' => $supplier_res->room_supplier_name,
                                    'data' => $supplier_booking_data
                                ];
                        }
                        // print_r($series_data);
                        // die;
            
                    }
                    
                    return response()->json([
                        'status' => 'success',
                        'by' => 'total_booking',
                        'data' => $limitedSupplierData,
                        'series_data' => $series_data,
                        'categories_data' => $categories_data,
                    ]);
                }
            
        }else{
            return response()->json([
                'status'    => 'error',
                'data'      => '',
            ]);
        }
        
   }
    
    public function hotel_supplier_filter(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            $request_data                   = json_decode($request->request_data);
            $all_hotel_suppliers            = DB::table('rooms_Invoice_Supplier')->where('customer_id',$userData->id)->get();
            
            // Season
            if($userData->id == 4 || $userData->id == 54){
                $today_Date                 = date('Y-m-d');
                if(isset($request->season_Id)){
                    if($request->season_Id == 'all_Seasons'){
                        $season_Details     = null;
                    }elseif($request->season_Id > 0){
                        $season_Details     = DB::table('add_Seasons')->where('customer_id', $userData->id)->where('id', $request->season_Id)->first();
                    }else{
                        $season_Details     = null;
                    }
                }else{
                    $season_Details         = DB::table('add_Seasons')->where('customer_id', $userData->id)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
                }
                
                if($season_Details != null){
                    $start_Date             = $season_Details->start_Date;
                    $end_Date               = $season_Details->end_Date;
                    $all_hotel_suppliers    = DB::table('rooms_Invoice_Supplier')->where('customer_id',$userData->id)->whereBetween('created_at', [$start_Date, $end_Date])->get();
                }
            }
            // Season
            
            $all_supplier_data      = [];
            
            if($request_data->date_type == 'all_data'){
                foreach($all_hotel_suppliers as $sup_res){
                        $supplierBooking    = DB::table('rooms')
                                                ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                                                ->leftJoin('add_manage_invoices', function ($join) {
                                                    $join->on('rooms_bookings_details.booking_id', '=', 'add_manage_invoices.id')
                                                        ->where('rooms_bookings_details.booking_from', '=', 'Invoices');
                                                })
                                                ->leftJoin('tours', function ($join) {
                                                    $join->on('rooms_bookings_details.package_id', '=', 'tours.id')
                                                        ->where('rooms_bookings_details.booking_from', '=', 'package');
                                                })
                                                ->leftJoin('hotels_bookings', function ($join) {
                                                    $join->on('rooms_bookings_details.booking_id', '=', 'hotels_bookings.id')
                                                        ->where('rooms_bookings_details.booking_from', '=', 'website');
                                                })
                                                ->where('rooms.room_supplier_name',$sup_res->id)
                                                ->select('rooms.id','rooms_bookings_details.booking_from','rooms_bookings_details.booking_id','rooms_bookings_details.package_id','rooms_bookings_details.quantity','rooms.room_supplier_name'
                                                        ,'tours.accomodation_details as package_accomodation','tours.accomodation_details_more as package_accomodation_more','tours.id as package_id'
                                                        ,'add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.id as Invoice_id'
                                                        ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response')
                                                ->orderBy('rooms.id','asc')
                                                ->get();
                        $total_nights               = 0;
                        $total_payable_price        = 0;
                        $total_rooms_count          = 0;
                        $supplier_rooms_bookings    = [];
                        foreach($supplierBooking as $book_res){
                            // From Accomodation Details Invoice
                            if(isset($book_res->accomodation_details)){
                                $accomodation_data = json_decode($book_res->accomodation_details);
                                if($accomodation_data){
                                    foreach($accomodation_data as $acc_res){
                                        if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                            $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                            $no_of_nights = abs(round($diff / 86400));
                                            
                                            $total_nights += $no_of_nights;
                                            
                                            $supplier_rooms_bookings[] = (Object)[
                                                'hotel_name' => $acc_res->acc_hotel_name,
                                                'room_id' => $acc_res->hotelRoom_type_id,
                                                'room_type' => $acc_res->hotel_type_cat,
                                                'check_in' => $acc_res->acc_check_in,
                                                'check_out' => $acc_res->acc_check_out,
                                                'no_of_nights' => $no_of_nights,
                                                'rooms_qty' => $acc_res->acc_qty,
                                                'rooms_price' => $acc_res->price_per_room_purchase,
                                                'rooms_total_price' => $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights),
                                            ];
                                            $total_price = $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights);
                                            $total_payable_price += $total_price;
                                            $total_rooms_count += $acc_res->acc_qty;
                                        }
                                    }
                                }  
                            }
                            
                            // From More Accomodation Details Invoice
                            if(isset($book_res->accomodation_details_more)){
                                $accomodation_data = json_decode($book_res->accomodation_details_more);
                                if($accomodation_data){
                                    foreach($accomodation_data as $acc_res){
                                        if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                            $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                            $no_of_nights = abs(round($diff / 86400));
                                            
                                            $total_nights += $no_of_nights;
                                            
                                             $supplier_rooms_bookings[] = (Object)[
                                                    'hotel_name' => $acc_res->more_acc_hotel_name,
                                                    'room_id' => $acc_res->more_hotelRoom_type_id,
                                                    'room_type' => $acc_res->more_hotel_type_cat,
                                                    'check_in' => $acc_res->more_acc_check_in,
                                                    'check_out' => $acc_res->more_acc_check_out,
                                                    'no_of_nights' => $no_of_nights,
                                                    'rooms_qty' => $acc_res->more_acc_qty,
                                                    'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                    'rooms_total_price' => $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights),
                                                ];
                                                
                                            $total_price = $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights);
                                            $total_payable_price += $total_price;
                                            $total_rooms_count += $acc_res->more_acc_qty;    
                                        }
                                    }
                                    
                                    
                                }
                                
                            }
                            
                            // From Accomodation Details Package
                            if(isset($book_res->package_accomodation)){
                                $accomodation_data = json_decode($book_res->package_accomodation);
                                if($accomodation_data){
                                    foreach($accomodation_data as $acc_res){
                                        if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                            $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                            $no_of_nights = abs(round($diff / 86400));
                                            
                                            $total_nights += $no_of_nights;
                                            
                                            $supplier_rooms_bookings[] = (Object)[
                                                    'hotel_name' => $acc_res->acc_hotel_name,
                                                    'room_id' => $acc_res->hotelRoom_type_id,
                                                    'room_type' => $acc_res->hotel_type_cat,
                                                    'check_in' => $acc_res->acc_check_in,
                                                    'check_out' => $acc_res->acc_check_out,
                                                    'no_of_nights' => $no_of_nights,
                                                    'rooms_qty' => $book_res->quantity,
                                                    'rooms_price' => $acc_res->price_per_room_purchase,
                                                    'rooms_total_price' => $book_res->quantity * ($acc_res->price_per_room_purchase * $no_of_nights),
                                                ];
                                            
                                            $total_price = $book_res->quantity * ($acc_res->price_per_room_purchase * $no_of_nights);
                                            $total_payable_price += $total_price;
                                            $total_rooms_count += $book_res->quantity;
                                        }
                                    }
                                    
                                    
                                }  
                            }
                            
                            // From More Accomodation Details Package
                            if(isset($book_res->package_accomodation_more)){
                                $accomodation_data = json_decode($book_res->package_accomodation_more);
                                if($accomodation_data){
                                    foreach($accomodation_data as $acc_res){
                                        if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                            $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                            $no_of_nights = abs(round($diff / 86400));
                                            
                                            $total_nights += $no_of_nights;
                                            
                                             $supplier_rooms_bookings[] = (Object)[
                                                    'hotel_name' => $acc_res->more_acc_hotel_name,
                                                    'room_id' => $acc_res->more_hotelRoom_type_id,
                                                    'room_type' => $acc_res->more_hotel_type_cat,
                                                    'check_in' => $acc_res->more_acc_check_in,
                                                    'check_out' => $acc_res->more_acc_check_out,
                                                    'no_of_nights' => $no_of_nights,
                                                    'rooms_qty' => $book_res->quantity,
                                                    'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                    'rooms_total_price' => (int)$book_res->quantity * ((int)$acc_res->more_price_per_room_purchase * (int)$no_of_nights),
                                                ];
                                                
                                            $total_price = (int)$book_res->quantity * ((int)$acc_res->more_price_per_room_purchase * (int)$no_of_nights);
                                            $total_payable_price += (int)$total_price;
                                            $total_rooms_count += (int)$book_res->quantity;    
                                        }
                                    }
                                    
                                    
                                }
                                
                            }
                            
                            // From Website Booking
                            if(isset($book_res->reservation_response)){
                                $reservation_data = json_decode($book_res->reservation_response);
                                if($reservation_data){
                                 
                                            $diff = strtotime($reservation_data->hotel_details->checkOut) - strtotime($reservation_data->hotel_details->checkIn);
                                            $no_of_nights = abs(round($diff / 86400));
                                            
                                            $total_nights += $no_of_nights;
                                            
                                            foreach($reservation_data->hotel_details->rooms as $room_res){
                                                $supplier_rooms_bookings[] = (Object)[
                                                    'hotel_name' => $reservation_data->hotel_details->hotel_name,
                                                    'room_id' => $room_res->room_code,
                                                    'room_type' => $room_res->room_name,
                                                    'check_in' => $reservation_data->hotel_details->checkOut,
                                                    'check_out' => $reservation_data->hotel_details->checkOut,
                                                    'no_of_nights' => $no_of_nights,
                                                    'rooms_qty' => $room_res->room_rates[0]->room_qty,
                                                    'rooms_price' => $room_res->room_rates[0]->net,
                                                    'rooms_total_price' => $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights),
                                                ];
                                                
                                                $total_price = $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $room_res->room_rates[0]->room_qty;  
                                            }
                                        
                                            
                                    
                                }
                                
                            }
                        }
                        
                        $supplier_data                      = DB::table('rooms_Invoice_Supplier')->where('id',$request->supplier_id)->select('id','room_supplier_name','payable','currrency')->first();
                        $supplier_total_paid                = DB::table('hotel_supplier_ledger')->where('supplier_id',$request->supplier_id)->where('payment_id','!=',NULL)->sum('received');
                        $all_supplier_data[]                = (Object)[
                            'id'                            => $sup_res->id,
                            'room_supplier_name'            => $sup_res->room_supplier_name,
                            'payable'                       => number_format($total_payable_price),
                            'payable_without_format'        => $total_payable_price,
                            'rem_payable'                   => $sup_res->payable,
                            'currrency'                     => $sup_res->currrency,
                            'booking_count'                 => $total_rooms_count,
                            'no_of_nights'                  => $total_nights
                        ];
                    }
            }
            
            if($request_data->date_type == 'data_today_wise'){
                // Get All Data    
                    $today_date = date('Y-m-d');
                    foreach($all_hotel_suppliers as $sup_res){
                    
                            $supplierBooking = DB::table('rooms')
                            ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                            ->leftJoin('add_manage_invoices', function ($join) {
                                $join->on('rooms_bookings_details.booking_id', '=', 'add_manage_invoices.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'Invoices');
                            })
                            ->leftJoin('tours', function ($join) {
                                $join->on('rooms_bookings_details.package_id', '=', 'tours.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'package');
                            })
                            ->leftJoin('hotels_bookings', function ($join) {
                                $join->on('rooms_bookings_details.booking_id', '=', 'hotels_bookings.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'website');
                            })
                            ->where('rooms.room_supplier_name',$sup_res->id)
                            ->whereDate('rooms_bookings_details.current_date',$today_date)
                            ->select('rooms.id','rooms_bookings_details.booking_from','rooms_bookings_details.booking_id','rooms_bookings_details.package_id','rooms_bookings_details.quantity','rooms.room_supplier_name'
                                    ,'tours.accomodation_details as package_accomodation','tours.accomodation_details_more as package_accomodation_more','tours.id as package_id'
                                    ,'add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.id as Invoice_id'
                                    ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response')
                            ->orderBy('rooms.id','asc')
                            ->get();
                            
                            
                
                            $total_nights = 0;
                            $total_payable_price = 0;
                            $total_rooms_count = 0;
                            $supplier_rooms_bookings = [];
                            foreach($supplierBooking as $book_res){
                                
                              
                                // From Accomodation Details
                                if(isset($book_res->accomodation_details)){
                                    $accomodation_data = json_decode($book_res->accomodation_details);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                                $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->acc_hotel_name,
                                                        'room_id' => $acc_res->hotelRoom_type_id,
                                                        'room_type' => $acc_res->hotel_type_cat,
                                                        'check_in' => $acc_res->acc_check_in,
                                                        'check_out' => $acc_res->acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $acc_res->acc_qty,
                                                        'rooms_price' => $acc_res->price_per_room_purchase,
                                                        'rooms_total_price' => $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights),
                                                    ];
                                                
                                                $total_price = $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $acc_res->acc_qty;
                                            }
                                        }
                                        
                                        
                                    }  
                                }
                                
                                 // From More Accomodation Details
                                if(isset($book_res->accomodation_details_more)){
                                    $accomodation_data = json_decode($book_res->accomodation_details_more);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                                $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                 $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->more_acc_hotel_name,
                                                        'room_id' => $acc_res->more_hotelRoom_type_id,
                                                        'room_type' => $acc_res->more_hotel_type_cat,
                                                        'check_in' => $acc_res->more_acc_check_in,
                                                        'check_out' => $acc_res->more_acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $acc_res->more_acc_qty,
                                                        'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                        'rooms_total_price' => $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights),
                                                    ];
                                                    
                                                $total_price = $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $acc_res->more_acc_qty;    
                                            }
                                        }
                                        
                                        
                                    }
                                    
                                }
                                
                                   // From Accomodation Details Package
                                if(isset($book_res->package_accomodation)){
                                    $accomodation_data = json_decode($book_res->package_accomodation);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                                $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->acc_hotel_name,
                                                        'room_id' => $acc_res->hotelRoom_type_id,
                                                        'room_type' => $acc_res->hotel_type_cat,
                                                        'check_in' => $acc_res->acc_check_in,
                                                        'check_out' => $acc_res->acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $book_res->quantity,
                                                        'rooms_price' => $acc_res->price_per_room_purchase,
                                                        'rooms_total_price' => $book_res->quantity * ($acc_res->price_per_room_purchase * $no_of_nights),
                                                    ];
                                                
                                                $total_price = $book_res->quantity * ($acc_res->price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $book_res->quantity;
                                            }
                                        }
                                        
                                        
                                    }  
                                }
                                
                                 // From More Accomodation Details Package
                                if(isset($book_res->package_accomodation_more)){
                                    $accomodation_data = json_decode($book_res->package_accomodation_more);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                                $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                 $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->more_acc_hotel_name,
                                                        'room_id' => $acc_res->more_hotelRoom_type_id,
                                                        'room_type' => $acc_res->more_hotel_type_cat,
                                                        'check_in' => $acc_res->more_acc_check_in,
                                                        'check_out' => $acc_res->more_acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $book_res->quantity,
                                                        'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                        'rooms_total_price' => (int)$book_res->quantity * ((int)$acc_res->more_price_per_room_purchase * (int)$no_of_nights),
                                                    ];
                                                    
                                                $total_price = (int)$book_res->quantity * ((int)$acc_res->more_price_per_room_purchase * (int)$no_of_nights);
                                                $total_payable_price += (int)$total_price;
                                                $total_rooms_count += (int)$book_res->quantity;    
                                            }
                                        }
                                        
                                        
                                    }
                                    
                                }
                                
                                // From Website Booking
                                if(isset($book_res->reservation_response)){
                                    $reservation_data = json_decode($book_res->reservation_response);
                                    if($reservation_data){
                                     
                                                $diff = strtotime($reservation_data->hotel_details->checkOut) - strtotime($reservation_data->hotel_details->checkIn);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                foreach($reservation_data->hotel_details->rooms as $room_res){
                                                    $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $reservation_data->hotel_details->hotel_name,
                                                        'room_id' => $room_res->room_code,
                                                        'room_type' => $room_res->room_name,
                                                        'check_in' => $reservation_data->hotel_details->checkOut,
                                                        'check_out' => $reservation_data->hotel_details->checkOut,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $room_res->room_rates[0]->room_qty,
                                                        'rooms_price' => $room_res->room_rates[0]->net,
                                                        'rooms_total_price' => $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights),
                                                    ];
                                                    
                                                    $total_price = $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights);
                                                    $total_payable_price += $total_price;
                                                    $total_rooms_count += $room_res->room_rates[0]->room_qty;  
                                                }
                                            
                                                
                                        
                                    }
                                    
                                }
                                
                            }
                            
                            $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$request->supplier_id)->select('id','room_supplier_name','payable','currrency')->first();
                            
                            $supplier_total_paid = DB::table('hotel_supplier_ledger')->where('supplier_id',$request->supplier_id)->where('payment_id','!=',NULL)->sum('received');
                            // echo "<pre>";
                            // print_r($supplier_total_paid);
                            // echo "</pre>";
                            // die;
                            
                            $all_supplier_data[] = (Object)[
                                    'id' => $sup_res->id,
                                    'room_supplier_name' => $sup_res->room_supplier_name,
                                    'payable' => number_format($total_payable_price),
                                    'payable_without_format' => $total_payable_price,
                                    'rem_payable' => $sup_res->payable,
                                    'currrency' => $sup_res->currrency,
                                    'booking_count' => $total_rooms_count,
                                    'no_of_nights' => $total_nights
                                ];
                                
                           
                    
                    }
            }
            
            if($request_data->date_type == 'data_Yesterday_wise'){
                // Get All Data    
                    $date = date('Y-m-d',strtotime("-1 days"));
                    // dd($date);
                    foreach($all_hotel_suppliers as $sup_res){
                    
                            $supplierBooking = DB::table('rooms')
                            ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                            ->leftJoin('add_manage_invoices', function ($join) {
                                $join->on('rooms_bookings_details.booking_id', '=', 'add_manage_invoices.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'Invoices');
                            })
                            ->leftJoin('tours', function ($join) {
                                $join->on('rooms_bookings_details.package_id', '=', 'tours.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'package');
                            })
                            ->leftJoin('hotels_bookings', function ($join) {
                                $join->on('rooms_bookings_details.booking_id', '=', 'hotels_bookings.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'website');
                            })
                            ->where('rooms.room_supplier_name',$sup_res->id)
                            ->whereDate('rooms_bookings_details.current_date',$date)
                               ->select('rooms.id','rooms_bookings_details.booking_from','rooms_bookings_details.booking_id','rooms_bookings_details.package_id','rooms_bookings_details.quantity','rooms.room_supplier_name'
                                    ,'tours.accomodation_details as package_accomodation','tours.accomodation_details_more as package_accomodation_more','tours.id as package_id'
                                    ,'add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.id as Invoice_id'
                                    ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response')
                            ->orderBy('rooms.id','asc')
                            ->get();
                            
                            // print_r($supplierBooking);
                
                            $total_nights = 0;
                            $total_payable_price = 0;
                            $total_rooms_count = 0;
                            $supplier_rooms_bookings = [];
                            foreach($supplierBooking as $book_res){
                                
                              
                                // From Accomodation Details
                                if(isset($book_res->accomodation_details)){
                                    $accomodation_data = json_decode($book_res->accomodation_details);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                                $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->acc_hotel_name,
                                                        'room_id' => $acc_res->hotelRoom_type_id,
                                                        'room_type' => $acc_res->hotel_type_cat,
                                                        'check_in' => $acc_res->acc_check_in,
                                                        'check_out' => $acc_res->acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $acc_res->acc_qty,
                                                        'rooms_price' => $acc_res->price_per_room_purchase,
                                                        'rooms_total_price' => $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights),
                                                    ];
                                                
                                                $total_price = $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $acc_res->acc_qty;
                                            }
                                        }
                                        
                                        
                                    }  
                                }
                                
                                 // From More Accomodation Details
                                if(isset($book_res->accomodation_details_more)){
                                    $accomodation_data = json_decode($book_res->accomodation_details_more);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                                $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                 $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->more_acc_hotel_name,
                                                        'room_id' => $acc_res->more_hotelRoom_type_id,
                                                        'room_type' => $acc_res->more_hotel_type_cat,
                                                        'check_in' => $acc_res->more_acc_check_in,
                                                        'check_out' => $acc_res->more_acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $acc_res->more_acc_qty,
                                                        'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                        'rooms_total_price' => $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights),
                                                    ];
                                                    
                                                $total_price = $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $acc_res->more_acc_qty;    
                                            }
                                        }
                                        
                                        
                                    }
                                    
                                }
                                
                                          // From Accomodation Details Package
                                if(isset($book_res->package_accomodation)){
                                    $accomodation_data = json_decode($book_res->package_accomodation);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                                $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->acc_hotel_name,
                                                        'room_id' => $acc_res->hotelRoom_type_id,
                                                        'room_type' => $acc_res->hotel_type_cat,
                                                        'check_in' => $acc_res->acc_check_in,
                                                        'check_out' => $acc_res->acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $book_res->quantity,
                                                        'rooms_price' => $acc_res->price_per_room_purchase,
                                                        'rooms_total_price' => $book_res->quantity * ($acc_res->price_per_room_purchase * $no_of_nights),
                                                    ];
                                                
                                                $total_price = $book_res->quantity * ($acc_res->price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $book_res->quantity;
                                            }
                                        }
                                        
                                        
                                    }  
                                }
                                
                                 // From More Accomodation Details Package
                                if(isset($book_res->package_accomodation_more)){
                                    $accomodation_data = json_decode($book_res->package_accomodation_more);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                                $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                 $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->more_acc_hotel_name,
                                                        'room_id' => $acc_res->more_hotelRoom_type_id,
                                                        'room_type' => $acc_res->more_hotel_type_cat,
                                                        'check_in' => $acc_res->more_acc_check_in,
                                                        'check_out' => $acc_res->more_acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $book_res->quantity,
                                                        'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                        'rooms_total_price' => (int)$book_res->quantity * ((int)$acc_res->more_price_per_room_purchase * (int)$no_of_nights),
                                                    ];
                                                    
                                                $total_price = (int)$book_res->quantity * ((int)$acc_res->more_price_per_room_purchase * (int)$no_of_nights);
                                                $total_payable_price += (int)$total_price;
                                                $total_rooms_count += (int)$book_res->quantity;    
                                            }
                                        }
                                        
                                        
                                    }
                                    
                                }
                                
                                // From Website Booking
                                if(isset($book_res->reservation_response)){
                                    $reservation_data = json_decode($book_res->reservation_response);
                                    if($reservation_data){
                                     
                                                $diff = strtotime($reservation_data->hotel_details->checkOut) - strtotime($reservation_data->hotel_details->checkIn);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                foreach($reservation_data->hotel_details->rooms as $room_res){
                                                    $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $reservation_data->hotel_details->hotel_name,
                                                        'room_id' => $room_res->room_code,
                                                        'room_type' => $room_res->room_name,
                                                        'check_in' => $reservation_data->hotel_details->checkOut,
                                                        'check_out' => $reservation_data->hotel_details->checkOut,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $room_res->room_rates[0]->room_qty,
                                                        'rooms_price' => $room_res->room_rates[0]->net,
                                                        'rooms_total_price' => $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights),
                                                    ];
                                                    
                                                    $total_price = $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights);
                                                    $total_payable_price += $total_price;
                                                    $total_rooms_count += $room_res->room_rates[0]->room_qty;  
                                                }
                                            
                                                
                                        
                                    }
                                    
                                }
                                
                            }
                            
                            $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$request->supplier_id)->select('id','room_supplier_name','payable','currrency')->first();
                            
                            $supplier_total_paid = DB::table('hotel_supplier_ledger')->where('supplier_id',$request->supplier_id)->where('payment_id','!=',NULL)->sum('received');
                            // echo "<pre>";
                            // print_r($supplier_total_paid);
                            // echo "</pre>";
                            // die;
                            
                            $all_supplier_data[] = (Object)[
                                    'id' => $sup_res->id,
                                    'room_supplier_name' => $sup_res->room_supplier_name,
                                    'payable' => number_format($total_payable_price),
                                    'payable_without_format' => $total_payable_price,
                                    'rem_payable' => $sup_res->payable,
                                    'currrency' => $sup_res->currrency,
                                    'booking_count' => $total_rooms_count,
                                    'no_of_nights' => $total_nights
                                ];
                                
                           
                    
                    }
            }
            
            if($request_data->date_type == 'data_week_wise'){
                // Get All Data    
                    $startOfWeek = Carbon::now()->startOfWeek();
                    $start_date = $startOfWeek->format('Y-m-d');
                    $end_date = date('Y-m-d');
                    // dd($date);
                    foreach($all_hotel_suppliers as $sup_res){
                    
                            $supplierBooking = DB::table('rooms')
                            ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                            ->leftJoin('add_manage_invoices', function ($join) {
                                $join->on('rooms_bookings_details.booking_id', '=', 'add_manage_invoices.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'Invoices');
                            })
                            ->leftJoin('tours', function ($join) {
                                $join->on('rooms_bookings_details.package_id', '=', 'tours.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'package');
                            })
                            ->leftJoin('hotels_bookings', function ($join) {
                                $join->on('rooms_bookings_details.booking_id', '=', 'hotels_bookings.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'website');
                            })
                            ->where('rooms.room_supplier_name',$sup_res->id)
                            ->whereDate('rooms_bookings_details.current_date', '>=', $start_date)
                            ->whereDate('rooms_bookings_details.current_date', '<=', $end_date)
                            ->select('rooms.id','rooms_bookings_details.booking_from','rooms_bookings_details.booking_id','rooms_bookings_details.package_id','rooms_bookings_details.quantity','rooms.room_supplier_name'
                                    ,'tours.accomodation_details as package_accomodation','tours.accomodation_details_more as package_accomodation_more','tours.id as package_id'
                                    ,'add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.id as Invoice_id'
                                    ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response')
                            ->orderBy('rooms.id','asc')
                            ->get();
                            
                
                            $total_nights = 0;
                            $total_payable_price = 0;
                            $total_rooms_count = 0;
                            $supplier_rooms_bookings = [];
                            foreach($supplierBooking as $book_res){
                                
                              
                                // From Accomodation Details
                                if(isset($book_res->accomodation_details)){
                                    $accomodation_data = json_decode($book_res->accomodation_details);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                                $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->acc_hotel_name,
                                                        'room_id' => $acc_res->hotelRoom_type_id,
                                                        'room_type' => $acc_res->hotel_type_cat,
                                                        'check_in' => $acc_res->acc_check_in,
                                                        'check_out' => $acc_res->acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $acc_res->acc_qty,
                                                        'rooms_price' => $acc_res->price_per_room_purchase,
                                                        'rooms_total_price' => $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights),
                                                    ];
                                                
                                                $total_price = $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $acc_res->acc_qty;
                                            }
                                        }
                                        
                                        
                                    }  
                                }
                                
                                 // From More Accomodation Details
                                if(isset($book_res->accomodation_details_more)){
                                    $accomodation_data = json_decode($book_res->accomodation_details_more);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                                $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                 $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->more_acc_hotel_name,
                                                        'room_id' => $acc_res->more_hotelRoom_type_id,
                                                        'room_type' => $acc_res->more_hotel_type_cat,
                                                        'check_in' => $acc_res->more_acc_check_in,
                                                        'check_out' => $acc_res->more_acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $acc_res->more_acc_qty,
                                                        'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                        'rooms_total_price' => $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights),
                                                    ];
                                                    
                                                $total_price = $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $acc_res->more_acc_qty;    
                                            }
                                        }
                                        
                                        
                                    }
                                    
                                }
                                
                                     // From Accomodation Details Package
                                if(isset($book_res->package_accomodation)){
                                    $accomodation_data = json_decode($book_res->package_accomodation);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                                $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->acc_hotel_name,
                                                        'room_id' => $acc_res->hotelRoom_type_id,
                                                        'room_type' => $acc_res->hotel_type_cat,
                                                        'check_in' => $acc_res->acc_check_in,
                                                        'check_out' => $acc_res->acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $book_res->quantity,
                                                        'rooms_price' => $acc_res->price_per_room_purchase,
                                                        'rooms_total_price' => $book_res->quantity * ($acc_res->price_per_room_purchase * $no_of_nights),
                                                    ];
                                                
                                                $total_price = $book_res->quantity * ($acc_res->price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $book_res->quantity;
                                            }
                                        }
                                        
                                        
                                    }  
                                }
                                
                                 // From More Accomodation Details Package
                                if(isset($book_res->package_accomodation_more)){
                                    $accomodation_data = json_decode($book_res->package_accomodation_more);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                                $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                 $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->more_acc_hotel_name,
                                                        'room_id' => $acc_res->more_hotelRoom_type_id,
                                                        'room_type' => $acc_res->more_hotel_type_cat,
                                                        'check_in' => $acc_res->more_acc_check_in,
                                                        'check_out' => $acc_res->more_acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $book_res->quantity,
                                                        'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                        'rooms_total_price' => (int)$book_res->quantity * ((int)$acc_res->more_price_per_room_purchase * (int)$no_of_nights),
                                                    ];
                                                    
                                                $total_price = (int)$book_res->quantity * ((int)$acc_res->more_price_per_room_purchase * (int)$no_of_nights);
                                                $total_payable_price += (int)$total_price;
                                                $total_rooms_count += (int)$book_res->quantity;    
                                            }
                                        }
                                        
                                        
                                    }
                                    
                                }
                                
                                // From Website Booking
                                if(isset($book_res->reservation_response)){
                                    $reservation_data = json_decode($book_res->reservation_response);
                                    if($reservation_data){
                                     
                                                $diff = strtotime($reservation_data->hotel_details->checkOut) - strtotime($reservation_data->hotel_details->checkIn);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                foreach($reservation_data->hotel_details->rooms as $room_res){
                                                    $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $reservation_data->hotel_details->hotel_name,
                                                        'room_id' => $room_res->room_code,
                                                        'room_type' => $room_res->room_name,
                                                        'check_in' => $reservation_data->hotel_details->checkOut,
                                                        'check_out' => $reservation_data->hotel_details->checkOut,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $room_res->room_rates[0]->room_qty,
                                                        'rooms_price' => $room_res->room_rates[0]->net,
                                                        'rooms_total_price' => $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights),
                                                    ];
                                                    
                                                    $total_price = $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights);
                                                    $total_payable_price += $total_price;
                                                    $total_rooms_count += $room_res->room_rates[0]->room_qty;  
                                                }
                                            
                                                
                                        
                                    }
                                    
                                }
                                
                            }
                            
                            $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$request->supplier_id)->select('id','room_supplier_name','payable','currrency')->first();
                            
                            $supplier_total_paid = DB::table('hotel_supplier_ledger')->where('supplier_id',$request->supplier_id)->where('payment_id','!=',NULL)->sum('received');
                            // echo "<pre>";
                            // print_r($supplier_total_paid);
                            // echo "</pre>";
                            // die;
                            
                            $all_supplier_data[] = (Object)[
                                    'id' => $sup_res->id,
                                    'room_supplier_name' => $sup_res->room_supplier_name,
                                    'payable' => number_format($total_payable_price),
                                    'payable_without_format' => $total_payable_price,
                                    'rem_payable' => $sup_res->payable,
                                    'currrency' => $sup_res->currrency,
                                    'booking_count' => $total_rooms_count,
                                    'no_of_nights' => $total_nights
                                ];
                                
                           
                    
                    }
            }
            
            if($request_data->date_type == 'data_month_wise'){
                // Get All Data    
                    $startOfMonth = Carbon::now()->startOfMonth();
                    $start_date = $startOfMonth->format('Y-m-d');
                    $end_date = date('Y-m-d');
                    // dd($start_date);
                    foreach($all_hotel_suppliers as $sup_res){
                    
                            $supplierBooking = DB::table('rooms')
                            ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                            ->leftJoin('add_manage_invoices', function ($join) {
                                $join->on('rooms_bookings_details.booking_id', '=', 'add_manage_invoices.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'Invoices');
                            })
                            ->leftJoin('tours', function ($join) {
                                $join->on('rooms_bookings_details.package_id', '=', 'tours.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'package');
                            })
                            ->leftJoin('hotels_bookings', function ($join) {
                                $join->on('rooms_bookings_details.booking_id', '=', 'hotels_bookings.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'website');
                            })
                            ->where('rooms.room_supplier_name',$sup_res->id)
                            ->whereDate('rooms_bookings_details.current_date', '>=', $start_date)
                            ->whereDate('rooms_bookings_details.current_date', '<=', $end_date)
                           ->select('rooms.id','rooms_bookings_details.booking_from','rooms_bookings_details.booking_id','rooms_bookings_details.package_id','rooms_bookings_details.quantity','rooms.room_supplier_name'
                                    ,'tours.accomodation_details as package_accomodation','tours.accomodation_details_more as package_accomodation_more','tours.id as package_id'
                                    ,'add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.id as Invoice_id'
                                    ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response')
                            ->orderBy('rooms.id','asc')
                            ->get();
                            
                
                            $total_nights = 0;
                            $total_payable_price = 0;
                            $total_rooms_count = 0;
                            $supplier_rooms_bookings = [];
                            foreach($supplierBooking as $book_res){
                                
                              
                                // From Accomodation Details
                                if(isset($book_res->accomodation_details)){
                                    $accomodation_data = json_decode($book_res->accomodation_details);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                                $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->acc_hotel_name,
                                                        'room_id' => $acc_res->hotelRoom_type_id,
                                                        'room_type' => $acc_res->hotel_type_cat,
                                                        'check_in' => $acc_res->acc_check_in,
                                                        'check_out' => $acc_res->acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $acc_res->acc_qty,
                                                        'rooms_price' => $acc_res->price_per_room_purchase,
                                                        'rooms_total_price' => $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights),
                                                    ];
                                                
                                                $total_price = $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $acc_res->acc_qty;
                                            }
                                        }
                                        
                                        
                                    }  
                                }
                                
                                 // From More Accomodation Details
                                if(isset($book_res->accomodation_details_more)){
                                    $accomodation_data = json_decode($book_res->accomodation_details_more);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                                $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                 $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->more_acc_hotel_name,
                                                        'room_id' => $acc_res->more_hotelRoom_type_id,
                                                        'room_type' => $acc_res->more_hotel_type_cat,
                                                        'check_in' => $acc_res->more_acc_check_in,
                                                        'check_out' => $acc_res->more_acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $acc_res->more_acc_qty,
                                                        'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                        'rooms_total_price' => $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights),
                                                    ];
                                                    
                                                $total_price = $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $acc_res->more_acc_qty;    
                                            }
                                        }
                                        
                                        
                                    }
                                    
                                }
                                
                                     // From Accomodation Details Package
                                if(isset($book_res->package_accomodation)){
                                    $accomodation_data = json_decode($book_res->package_accomodation);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                                $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->acc_hotel_name,
                                                        'room_id' => $acc_res->hotelRoom_type_id,
                                                        'room_type' => $acc_res->hotel_type_cat,
                                                        'check_in' => $acc_res->acc_check_in,
                                                        'check_out' => $acc_res->acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $book_res->quantity,
                                                        'rooms_price' => $acc_res->price_per_room_purchase,
                                                        'rooms_total_price' => $book_res->quantity * ($acc_res->price_per_room_purchase * $no_of_nights),
                                                    ];
                                                
                                                $total_price = $book_res->quantity * ($acc_res->price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $book_res->quantity;
                                            }
                                        }
                                        
                                        
                                    }  
                                }
                                
                                 // From More Accomodation Details Package
                                if(isset($book_res->package_accomodation_more)){
                                    $accomodation_data = json_decode($book_res->package_accomodation_more);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                                $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                 $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->more_acc_hotel_name,
                                                        'room_id' => $acc_res->more_hotelRoom_type_id,
                                                        'room_type' => $acc_res->more_hotel_type_cat,
                                                        'check_in' => $acc_res->more_acc_check_in,
                                                        'check_out' => $acc_res->more_acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $book_res->quantity,
                                                        'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                        'rooms_total_price' => (int)$book_res->quantity * ((int)$acc_res->more_price_per_room_purchase * (int)$no_of_nights),
                                                    ];
                                                    
                                                $total_price = (int)$book_res->quantity * ((int)$acc_res->more_price_per_room_purchase * (int)$no_of_nights);
                                                $total_payable_price += (int)$total_price;
                                                $total_rooms_count += (int)$book_res->quantity;    
                                            }
                                        }
                                        
                                        
                                    }
                                    
                                }
                                
                                // From Website Booking
                                if(isset($book_res->reservation_response)){
                                    $reservation_data = json_decode($book_res->reservation_response);
                                    if($reservation_data){
                                     
                                                $diff = strtotime($reservation_data->hotel_details->checkOut) - strtotime($reservation_data->hotel_details->checkIn);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                foreach($reservation_data->hotel_details->rooms as $room_res){
                                                    $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $reservation_data->hotel_details->hotel_name,
                                                        'room_id' => $room_res->room_code,
                                                        'room_type' => $room_res->room_name,
                                                        'check_in' => $reservation_data->hotel_details->checkOut,
                                                        'check_out' => $reservation_data->hotel_details->checkOut,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $room_res->room_rates[0]->room_qty,
                                                        'rooms_price' => $room_res->room_rates[0]->net,
                                                        'rooms_total_price' => $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights),
                                                    ];
                                                    
                                                    $total_price = $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights);
                                                    $total_payable_price += $total_price;
                                                    $total_rooms_count += $room_res->room_rates[0]->room_qty;  
                                                }
                                            
                                                
                                        
                                    }
                                    
                                }
                                
                            }
                            
                            $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$request->supplier_id)->select('id','room_supplier_name','payable','currrency')->first();
                            
                            $supplier_total_paid = DB::table('hotel_supplier_ledger')->where('supplier_id',$request->supplier_id)->where('payment_id','!=',NULL)->sum('received');
                            // echo "<pre>";
                            // print_r($supplier_total_paid);
                            // echo "</pre>";
                            // die;
                            
                            $all_supplier_data[] = (Object)[
                                    'id' => $sup_res->id,
                                    'room_supplier_name' => $sup_res->room_supplier_name,
                                    'payable' => number_format($total_payable_price),
                                    'payable_without_format' => $total_payable_price,
                                    'rem_payable' => $sup_res->payable,
                                    'currrency' => $sup_res->currrency,
                                    'booking_count' => $total_rooms_count,
                                    'no_of_nights' => $total_nights
                                ];
                                
                           
                    
                    }
            }
            
            if($request_data->date_type == 'data_year_wise'){
                // Get All Data    
                    $startOfYear = Carbon::now()->startOfYear();
                    $start_date = $startOfYear->format('Y-m-d');
                    $end_date = date('Y-m-d');
                    // dd($start_date);
                    foreach($all_hotel_suppliers as $sup_res){
                    
                            $supplierBooking = DB::table('rooms')
                            ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                            ->leftJoin('add_manage_invoices', function ($join) {
                                $join->on('rooms_bookings_details.booking_id', '=', 'add_manage_invoices.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'Invoices');
                            })
                            ->leftJoin('tours', function ($join) {
                                $join->on('rooms_bookings_details.package_id', '=', 'tours.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'package');
                            })
                            ->leftJoin('hotels_bookings', function ($join) {
                                $join->on('rooms_bookings_details.booking_id', '=', 'hotels_bookings.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'website');
                            })
                            ->where('rooms.room_supplier_name',$sup_res->id)
                            ->whereDate('rooms_bookings_details.current_date', '>=', $start_date)
                            ->whereDate('rooms_bookings_details.current_date', '<=', $end_date)
                           ->select('rooms.id','rooms_bookings_details.booking_from','rooms_bookings_details.booking_id','rooms_bookings_details.package_id','rooms_bookings_details.quantity','rooms.room_supplier_name'
                                    ,'tours.accomodation_details as package_accomodation','tours.accomodation_details_more as package_accomodation_more','tours.id as package_id'
                                    ,'add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.id as Invoice_id'
                                    ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response')
                            ->orderBy('rooms.id','asc')
                            ->get();
                            
                
                            $total_nights = 0;
                            $total_payable_price = 0;
                            $total_rooms_count = 0;
                            $supplier_rooms_bookings = [];
                            foreach($supplierBooking as $book_res){
                                
                              
                                // From Accomodation Details
                                if(isset($book_res->accomodation_details)){
                                    $accomodation_data = json_decode($book_res->accomodation_details);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                                $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->acc_hotel_name,
                                                        'room_id' => $acc_res->hotelRoom_type_id,
                                                        'room_type' => $acc_res->hotel_type_cat,
                                                        'check_in' => $acc_res->acc_check_in,
                                                        'check_out' => $acc_res->acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $acc_res->acc_qty,
                                                        'rooms_price' => $acc_res->price_per_room_purchase,
                                                        'rooms_total_price' => $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights),
                                                    ];
                                                
                                                $total_price = $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $acc_res->acc_qty;
                                            }
                                        }
                                        
                                        
                                    }  
                                }
                                
                                 // From More Accomodation Details
                                if(isset($book_res->accomodation_details_more)){
                                    $accomodation_data = json_decode($book_res->accomodation_details_more);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                                $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                 $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->more_acc_hotel_name,
                                                        'room_id' => $acc_res->more_hotelRoom_type_id,
                                                        'room_type' => $acc_res->more_hotel_type_cat,
                                                        'check_in' => $acc_res->more_acc_check_in,
                                                        'check_out' => $acc_res->more_acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $acc_res->more_acc_qty,
                                                        'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                        'rooms_total_price' => $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights),
                                                    ];
                                                    
                                                $total_price = $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $acc_res->more_acc_qty;    
                                            }
                                        }
                                        
                                        
                                    }
                                    
                                }
                                
                                     // From Accomodation Details Package
                                if(isset($book_res->package_accomodation)){
                                    $accomodation_data = json_decode($book_res->package_accomodation);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                                $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->acc_hotel_name,
                                                        'room_id' => $acc_res->hotelRoom_type_id,
                                                        'room_type' => $acc_res->hotel_type_cat,
                                                        'check_in' => $acc_res->acc_check_in,
                                                        'check_out' => $acc_res->acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $book_res->quantity,
                                                        'rooms_price' => $acc_res->price_per_room_purchase,
                                                        'rooms_total_price' => $book_res->quantity * ($acc_res->price_per_room_purchase * $no_of_nights),
                                                    ];
                                                
                                                $total_price = $book_res->quantity * ($acc_res->price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $book_res->quantity;
                                            }
                                        }
                                        
                                        
                                    }  
                                }
                                
                                 // From More Accomodation Details Package
                                if(isset($book_res->package_accomodation_more)){
                                    $accomodation_data = json_decode($book_res->package_accomodation_more);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                                $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                 $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->more_acc_hotel_name,
                                                        'room_id' => $acc_res->more_hotelRoom_type_id,
                                                        'room_type' => $acc_res->more_hotel_type_cat,
                                                        'check_in' => $acc_res->more_acc_check_in,
                                                        'check_out' => $acc_res->more_acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $book_res->quantity,
                                                        'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                        'rooms_total_price' => (int)$book_res->quantity * ((int)$acc_res->more_price_per_room_purchase * (int)$no_of_nights),
                                                    ];
                                                    
                                                $total_price = (int)$book_res->quantity * ((int)$acc_res->more_price_per_room_purchase * (int)$no_of_nights);
                                                $total_payable_price += (int)$total_price;
                                                $total_rooms_count += (int)$book_res->quantity;    
                                            }
                                        }
                                        
                                        
                                    }
                                    
                                }
                                
                                // From Website Booking
                                if(isset($book_res->reservation_response)){
                                    $reservation_data = json_decode($book_res->reservation_response);
                                    if($reservation_data){
                                     
                                                $diff = strtotime($reservation_data->hotel_details->checkOut) - strtotime($reservation_data->hotel_details->checkIn);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                foreach($reservation_data->hotel_details->rooms as $room_res){
                                                    $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $reservation_data->hotel_details->hotel_name,
                                                        'room_id' => $room_res->room_code,
                                                        'room_type' => $room_res->room_name,
                                                        'check_in' => $reservation_data->hotel_details->checkOut,
                                                        'check_out' => $reservation_data->hotel_details->checkOut,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $room_res->room_rates[0]->room_qty,
                                                        'rooms_price' => $room_res->room_rates[0]->net,
                                                        'rooms_total_price' => $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights),
                                                    ];
                                                    
                                                    $total_price = $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights);
                                                    $total_payable_price += $total_price;
                                                    $total_rooms_count += $room_res->room_rates[0]->room_qty;  
                                                }
                                            
                                                
                                        
                                    }
                                    
                                }
                                
                            }
                            
                            $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$request->supplier_id)->select('id','room_supplier_name','payable','currrency')->first();
                            
                            $supplier_total_paid = DB::table('hotel_supplier_ledger')->where('supplier_id',$request->supplier_id)->where('payment_id','!=',NULL)->sum('received');
                            // echo "<pre>";
                            // print_r($supplier_total_paid);
                            // echo "</pre>";
                            // die;
                            
                            $all_supplier_data[] = (Object)[
                                    'id' => $sup_res->id,
                                    'room_supplier_name' => $sup_res->room_supplier_name,
                                    'payable' => number_format($total_payable_price),
                                    'payable_without_format' => $total_payable_price,
                                    'rem_payable' => $sup_res->payable,
                                    'currrency' => $sup_res->currrency,
                                    'booking_count' => $total_rooms_count,
                                    'no_of_nights' => $total_nights
                                ];
                                
                           
                    
                    }
            }
            
            if($request_data->date_type == 'data_wise'){
                // Get All Data    
                    $start_date = $request_data->start_date;
                    $end_date = $request_data->end_date;
                    // dd($start_date);
                    foreach($all_hotel_suppliers as $sup_res){
                    
                            $supplierBooking = DB::table('rooms')
                            ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                            ->leftJoin('add_manage_invoices', function ($join) {
                                $join->on('rooms_bookings_details.booking_id', '=', 'add_manage_invoices.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'Invoices');
                            })
                            ->leftJoin('tours', function ($join) {
                                $join->on('rooms_bookings_details.package_id', '=', 'tours.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'package');
                            })
                            ->leftJoin('hotels_bookings', function ($join) {
                                $join->on('rooms_bookings_details.booking_id', '=', 'hotels_bookings.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'website');
                            })
                            ->where('rooms.room_supplier_name',$sup_res->id)
                            ->whereDate('rooms_bookings_details.current_date', '>=', $start_date)
                            ->whereDate('rooms_bookings_details.current_date', '<=', $end_date)
                            ->select('rooms.id','rooms_bookings_details.booking_from','rooms_bookings_details.booking_id','rooms_bookings_details.package_id','rooms_bookings_details.quantity','rooms.room_supplier_name'
                                    ,'tours.accomodation_details as package_accomodation','tours.accomodation_details_more as package_accomodation_more','tours.id as package_id'
                                    ,'add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.id as Invoice_id'
                                    ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response')
                            ->orderBy('rooms.id','asc')
                            ->get();
                            
                
                            $total_nights = 0;
                            $total_payable_price = 0;
                            $total_rooms_count = 0;
                            $supplier_rooms_bookings = [];
                            foreach($supplierBooking as $book_res){
                                
                              
                                // From Accomodation Details
                                if(isset($book_res->accomodation_details)){
                                    $accomodation_data = json_decode($book_res->accomodation_details);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                                $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->acc_hotel_name,
                                                        'room_id' => $acc_res->hotelRoom_type_id,
                                                        'room_type' => $acc_res->hotel_type_cat,
                                                        'check_in' => $acc_res->acc_check_in,
                                                        'check_out' => $acc_res->acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $acc_res->acc_qty,
                                                        'rooms_price' => $acc_res->price_per_room_purchase,
                                                        'rooms_total_price' => $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights),
                                                    ];
                                                
                                                $total_price = $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $acc_res->acc_qty;
                                            }
                                        }
                                        
                                        
                                    }  
                                }
                                
                                 // From More Accomodation Details
                                if(isset($book_res->accomodation_details_more)){
                                    $accomodation_data = json_decode($book_res->accomodation_details_more);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                                $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                 $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->more_acc_hotel_name,
                                                        'room_id' => $acc_res->more_hotelRoom_type_id,
                                                        'room_type' => $acc_res->more_hotel_type_cat,
                                                        'check_in' => $acc_res->more_acc_check_in,
                                                        'check_out' => $acc_res->more_acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $acc_res->more_acc_qty,
                                                        'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                        'rooms_total_price' => $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights),
                                                    ];
                                                    
                                                $total_price = $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $acc_res->more_acc_qty;    
                                            }
                                        }
                                        
                                        
                                    }
                                    
                                }
                                
                                     // From Accomodation Details Package
                                if(isset($book_res->package_accomodation)){
                                    $accomodation_data = json_decode($book_res->package_accomodation);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                                $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->acc_hotel_name,
                                                        'room_id' => $acc_res->hotelRoom_type_id,
                                                        'room_type' => $acc_res->hotel_type_cat,
                                                        'check_in' => $acc_res->acc_check_in,
                                                        'check_out' => $acc_res->acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $book_res->quantity,
                                                        'rooms_price' => $acc_res->price_per_room_purchase,
                                                        'rooms_total_price' => $book_res->quantity * ($acc_res->price_per_room_purchase * $no_of_nights),
                                                    ];
                                                
                                                $total_price = $book_res->quantity * ($acc_res->price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $book_res->quantity;
                                            }
                                        }
                                        
                                        
                                    }  
                                }
                                
                                 // From More Accomodation Details Package
                                if(isset($book_res->package_accomodation_more)){
                                    $accomodation_data = json_decode($book_res->package_accomodation_more);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                                $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                 $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->more_acc_hotel_name,
                                                        'room_id' => $acc_res->more_hotelRoom_type_id,
                                                        'room_type' => $acc_res->more_hotel_type_cat,
                                                        'check_in' => $acc_res->more_acc_check_in,
                                                        'check_out' => $acc_res->more_acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $book_res->quantity,
                                                        'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                        'rooms_total_price' => (int)$book_res->quantity * ((int)$acc_res->more_price_per_room_purchase * (int)$no_of_nights),
                                                    ];
                                                    
                                                $total_price = (int)$book_res->quantity * ((int)$acc_res->more_price_per_room_purchase * (int)$no_of_nights);
                                                $total_payable_price += (int)$total_price;
                                                $total_rooms_count += (int)$book_res->quantity;    
                                            }
                                        }
                                        
                                        
                                    }
                                    
                                }
                                
                                // From Website Booking
                                if(isset($book_res->reservation_response)){
                                    $reservation_data = json_decode($book_res->reservation_response);
                                    if($reservation_data){
                                     
                                                $diff = strtotime($reservation_data->hotel_details->checkOut) - strtotime($reservation_data->hotel_details->checkIn);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                foreach($reservation_data->hotel_details->rooms as $room_res){
                                                    $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $reservation_data->hotel_details->hotel_name,
                                                        'room_id' => $room_res->room_code,
                                                        'room_type' => $room_res->room_name,
                                                        'check_in' => $reservation_data->hotel_details->checkOut,
                                                        'check_out' => $reservation_data->hotel_details->checkOut,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $room_res->room_rates[0]->room_qty,
                                                        'rooms_price' => $room_res->room_rates[0]->net,
                                                        'rooms_total_price' => $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights),
                                                    ];
                                                    
                                                    $total_price = $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights);
                                                    $total_payable_price += $total_price;
                                                    $total_rooms_count += $room_res->room_rates[0]->room_qty;  
                                                }
                                            
                                                
                                        
                                    }
                                    
                                }
                                
                            }
                            
                            $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$request->supplier_id)->select('id','room_supplier_name','payable','currrency')->first();
                            
                            $supplier_total_paid = DB::table('hotel_supplier_ledger')->where('supplier_id',$request->supplier_id)->where('payment_id','!=',NULL)->sum('received');
                            // echo "<pre>";
                            // print_r($supplier_total_paid);
                            // echo "</pre>";
                            // die;
                            
                            $all_supplier_data[] = (Object)[
                                    'id' => $sup_res->id,
                                    'room_supplier_name' => $sup_res->room_supplier_name,
                                    'payable' => number_format($total_payable_price),
                                    'payable_without_format' => $total_payable_price,
                                    'rem_payable' => $sup_res->payable,
                                    'currrency' => $sup_res->currrency,
                                    'booking_count' => $total_rooms_count,
                                    'no_of_nights' => $total_nights
                                ];
                                
                           
                    
                    }
            }
            
            if($request_data->filter_type == 'total_revenue'){
                $all_supplier_data = new Collection($all_supplier_data);
                
                $all_supplier_data = $all_supplier_data->sortByDesc('payable_without_format');
                
                $all_supplier_data = $all_supplier_data->values();
                
                $all_supplier_data = $all_supplier_data->toArray();
                
                $series_data = [];
                $categories_data = [];
                
                if(sizeOf($all_supplier_data) >= 4){
                    $limitedSupplierData = array_slice($all_supplier_data, 0, 4);
                }else{
                    $limitedSupplierData = $all_supplier_data;
                }
                
                if($request_data->date_type == 'all_data'){
                    $currentYear = date('Y');
                    $monthsData = [];
                    
                    for ($month = 1; $month <= 12; $month++) {
                        
                         $startOfMonth = Carbon::create($currentYear, $month, 1)->startOfMonth();
                         $endOfMonth = Carbon::create($currentYear, $month, 1)->endOfMonth();
                         
                          $categories_data[] = $startOfMonth->format('F');
                         
                         $startOfMonth = $startOfMonth->format('Y-m-d');
                         $endOfMonth = $endOfMonth->format('Y-m-d');
        
                        $monthsData[] = (Object)[
                            'month' => $month,
                            'start_date' => $startOfMonth,
                            'end_date' => $endOfMonth,
                        ];
                    }
                    
                    foreach($limitedSupplierData as $sup_res){
                        $supplier_booking_data = [];
                        foreach($monthsData as $month_res){
                            // Add 7 days to the start date
                            
                            $supplierBooking    = DB::table('rooms')
                                                    ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                                                    ->leftJoin('add_manage_invoices', function ($join) {
                                                        $join->on('rooms_bookings_details.booking_id', '=', 'add_manage_invoices.id')
                                                            ->where('rooms_bookings_details.booking_from', '=', 'Invoices');
                                                    })
                                                    ->leftJoin('tours', function ($join) {
                                                        $join->on('rooms_bookings_details.package_id', '=', 'tours.id')
                                                            ->where('rooms_bookings_details.booking_from', '=', 'package');
                                                    })
                                                    ->leftJoin('hotels_bookings', function ($join) {
                                                        $join->on('rooms_bookings_details.booking_id', '=', 'hotels_bookings.id')
                                                            ->where('rooms_bookings_details.booking_from', '=', 'website');
                                                    })
                                                    ->where('rooms.room_supplier_name',$sup_res->id)
                                                    ->whereDate('rooms_bookings_details.current_date','>=', $month_res->start_date)
                                                    ->whereDate('rooms_bookings_details.current_date','<=', $month_res->end_date)
                                                    ->select('rooms.id','rooms_bookings_details.booking_from','rooms_bookings_details.booking_id','rooms_bookings_details.package_id','rooms.room_supplier_name'
                                                            ,'tours.accomodation_details','tours.accomodation_details_more','tours.id as package_id'
                                                            ,'add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.id as Invoice_id'
                                                            ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response')
                                                    ->orderBy('rooms.id','asc')
                                                    ->get();
                            $total_payable_price = 0;
                            foreach($supplierBooking as $book_res){
                                // From Accomodation Details
                                if(isset($book_res->accomodation_details)){
                                    $accomodation_data = json_decode($book_res->accomodation_details);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                                $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->acc_hotel_name,
                                                        'room_id' => $acc_res->hotelRoom_type_id,
                                                        'room_type' => $acc_res->hotel_type_cat,
                                                        'check_in' => $acc_res->acc_check_in,
                                                        'check_out' => $acc_res->acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $acc_res->acc_qty,
                                                        'rooms_price' => $acc_res->price_per_room_purchase,
                                                        'rooms_total_price' => $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights),
                                                    ];
                                                
                                                $total_price = $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $acc_res->acc_qty;
                                            }
                                        }
                                        
                                        
                                    }  
                                }
                                
                                // From More Accomodation Details
                                if(isset($book_res->accomodation_details_more)){
                                    $accomodation_data = json_decode($book_res->accomodation_details_more);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                                $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                 $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->more_acc_hotel_name,
                                                        'room_id' => $acc_res->more_hotelRoom_type_id,
                                                        'room_type' => $acc_res->more_hotel_type_cat,
                                                        'check_in' => $acc_res->more_acc_check_in,
                                                        'check_out' => $acc_res->more_acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $acc_res->more_acc_qty,
                                                        'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                        'rooms_total_price' => $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights),
                                                    ];
                                                    
                                                $total_price = $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $acc_res->more_acc_qty;    
                                            }
                                        }
                                        
                                        
                                    }
                                    
                                }
                                
                                // From Website Booking
                                if(isset($book_res->reservation_response)){
                                    $reservation_data = json_decode($book_res->reservation_response);
                                    if($reservation_data){
                                     
                                                $diff = strtotime($reservation_data->hotel_details->checkOut) - strtotime($reservation_data->hotel_details->checkIn);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                foreach($reservation_data->hotel_details->rooms as $room_res){
                                                    $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $reservation_data->hotel_details->hotel_name,
                                                        'room_id' => $room_res->room_code,
                                                        'room_type' => $room_res->room_name,
                                                        'check_in' => $reservation_data->hotel_details->checkOut,
                                                        'check_out' => $reservation_data->hotel_details->checkOut,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $room_res->room_rates[0]->room_qty,
                                                        'rooms_price' => $room_res->room_rates[0]->net,
                                                        'rooms_total_price' => $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights),
                                                    ];
                                                    
                                                    $total_price = $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights);
                                                    $total_payable_price += $total_price;
                                                    $total_rooms_count += $room_res->room_rates[0]->room_qty;  
                                                }
                                            
                                                
                                        
                                    }
                                    
                                }
                            }
                            $supplier_booking_data[] = floor($total_payable_price * 100) / 100;
                           
                        }
                        
                        $series_data[] = [
                            'name' => $sup_res->room_supplier_name,
                            'data' => $supplier_booking_data
                        ];
                    }
                }
                
                if($request_data->date_type == 'data_today_wise'){
        
                         $date = date('Y-m-d');
                        foreach($limitedSupplierData as $supplier_res){
                            
                            $supplier_booking_data = [$supplier_res->payable_without_format];
                            $series_data[] = [
                                    'name' => $supplier_res->room_supplier_name,
                                    'data' => $supplier_booking_data
                                ];
                        }
                        
                        $categories_data = [$date];
                 
                }
                
                if($request_data->date_type == 'data_Yesterday_wise'){
        
                         $date = date('Y-m-d',strtotime("-1 days"));
                        foreach($limitedSupplierData as $supplier_res){
                            
                            $supplier_booking_data = [$supplier_res->payable_without_format];
                            $series_data[] = [
                                    'name' => $supplier_res->room_supplier_name,
                                    'data' => $supplier_booking_data
                                ];
                        }
                        
                        $categories_data = [$date];
                 
                }
                
                // Generate Graph Data For Week
                if($request_data->date_type == 'data_week_wise'){
                   $today = Carbon::now();
                    
                    // Create a CarbonPeriod instance for the current week
                   $startOfWeek = $today->startOfWeek()->toDateString();
                    $endOfWeek = $today->endOfWeek()->toDateString();
                    
                    // Create a CarbonPeriod instance for the current week
                    $period = CarbonPeriod::create($startOfWeek, $endOfWeek);
                    
                    // Get the dates of the current week as an array
                    $datesOfWeek = [];
                    foreach ($period as $date) {
                        $datesOfWeek[] = $date->toDateString();
                    }
                    
                    // Get Data for Agent 
                    
                    foreach($limitedSupplierData as $supplier_res){
                        
                        // Loop On DatesOfWeek
                        
                        $supplier_booking_data = [];
                        foreach($datesOfWeek as $date_res){

                            $supplierBooking = DB::table('rooms')
                            ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                            ->leftJoin('add_manage_invoices', function ($join) {
                                $join->on('rooms_bookings_details.booking_id', '=', 'add_manage_invoices.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'Invoices');
                            })
                            ->leftJoin('tours', function ($join) {
                                $join->on('rooms_bookings_details.package_id', '=', 'tours.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'package');
                            })
                            ->leftJoin('hotels_bookings', function ($join) {
                                $join->on('rooms_bookings_details.booking_id', '=', 'hotels_bookings.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'website');
                            })
                            ->where('rooms.room_supplier_name',$supplier_res->id)
                            ->whereDate('rooms_bookings_details.current_date', $date_res)
                            ->select('rooms.id','rooms_bookings_details.booking_from','rooms_bookings_details.booking_id','rooms_bookings_details.package_id','rooms.room_supplier_name'
                                    ,'tours.accomodation_details','tours.accomodation_details_more','tours.id as package_id'
                                    ,'add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.id as Invoice_id'
                                    ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response')
                            ->orderBy('rooms.id','asc')
                            ->get();
                            
                
                            $total_payable_price = 0;
                            foreach($supplierBooking as $book_res){
                                
                              
                                // From Accomodation Details
                                if(isset($book_res->accomodation_details)){
                                    $accomodation_data = json_decode($book_res->accomodation_details);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                                $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->acc_hotel_name,
                                                        'room_id' => $acc_res->hotelRoom_type_id,
                                                        'room_type' => $acc_res->hotel_type_cat,
                                                        'check_in' => $acc_res->acc_check_in,
                                                        'check_out' => $acc_res->acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $acc_res->acc_qty,
                                                        'rooms_price' => $acc_res->price_per_room_purchase,
                                                        'rooms_total_price' => $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights),
                                                    ];
                                                
                                                $total_price = $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $acc_res->acc_qty;
                                            }
                                        }
                                        
                                        
                                    }  
                                }
                                
                                 // From More Accomodation Details
                                if(isset($book_res->accomodation_details_more)){
                                    $accomodation_data = json_decode($book_res->accomodation_details_more);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                                $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                 $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->more_acc_hotel_name,
                                                        'room_id' => $acc_res->more_hotelRoom_type_id,
                                                        'room_type' => $acc_res->more_hotel_type_cat,
                                                        'check_in' => $acc_res->more_acc_check_in,
                                                        'check_out' => $acc_res->more_acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $acc_res->more_acc_qty,
                                                        'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                        'rooms_total_price' => $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights),
                                                    ];
                                                    
                                                $total_price = $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $acc_res->more_acc_qty;    
                                            }
                                        }
                                        
                                        
                                    }
                                    
                                }
                                
                                // From Website Booking
                                if(isset($book_res->reservation_response)){
                                    $reservation_data = json_decode($book_res->reservation_response);
                                    if($reservation_data){
                                     
                                                $diff = strtotime($reservation_data->hotel_details->checkOut) - strtotime($reservation_data->hotel_details->checkIn);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                foreach($reservation_data->hotel_details->rooms as $room_res){
                                                    $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $reservation_data->hotel_details->hotel_name,
                                                        'room_id' => $room_res->room_code,
                                                        'room_type' => $room_res->room_name,
                                                        'check_in' => $reservation_data->hotel_details->checkOut,
                                                        'check_out' => $reservation_data->hotel_details->checkOut,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $room_res->room_rates[0]->room_qty,
                                                        'rooms_price' => $room_res->room_rates[0]->net,
                                                        'rooms_total_price' => $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights),
                                                    ];
                                                    
                                                    $total_price = $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights);
                                                    $total_payable_price += $total_price;
                                                    $total_rooms_count += $room_res->room_rates[0]->room_qty;  
                                                }
                                            
                                                
                                        
                                    }
                                    
                                }
                                
                            }
                                            
                          
                            
                            $supplier_booking_data[] = $total_payable_price;
                        }
                        
                        $series_data[] = [
                                'name' => $supplier_res->room_supplier_name,
                                'data' => $supplier_booking_data
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
            
                    foreach($limitedSupplierData as $supplier_res){
                        
                        // Start Date Of Month
                        // $currentDate = Carbon::now();
                        $startDate = Carbon::now()->startOfMonth();
                        // $startDate = $currentDate->subMonth()->firstOfMonth();
                        $startDateWeek = $startDate->toDateString();
                        $endDate = $startDate->copy()->addDays(6);
                        $endDateWeek = $endDate->toDateString();
                        
                        $supplier_booking_data = [];
                        for($i=1; $i<=5; $i++){
                            // Add 7 days to the start date
                   
                            
                             $supplierBooking = DB::table('rooms')
                            ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                            ->leftJoin('add_manage_invoices', function ($join) {
                                $join->on('rooms_bookings_details.booking_id', '=', 'add_manage_invoices.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'Invoices');
                            })
                            ->leftJoin('tours', function ($join) {
                                $join->on('rooms_bookings_details.package_id', '=', 'tours.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'package');
                            })
                            ->leftJoin('hotels_bookings', function ($join) {
                                $join->on('rooms_bookings_details.booking_id', '=', 'hotels_bookings.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'website');
                            })
                            ->where('rooms.room_supplier_name',$supplier_res->id)
                            ->whereDate('rooms_bookings_details.current_date','>=', $startDate)
                            ->whereDate('rooms_bookings_details.current_date','<=', $endDate)
                            ->select('rooms.id','rooms_bookings_details.booking_from','rooms_bookings_details.booking_id','rooms_bookings_details.package_id','rooms.room_supplier_name'
                                    ,'tours.accomodation_details','tours.accomodation_details_more','tours.id as package_id'
                                    ,'add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.id as Invoice_id'
                                    ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response')
                            ->orderBy('rooms.id','asc')
                            ->get();
                            
                
                            $total_payable_price = 0;
                            foreach($supplierBooking as $book_res){
                                
                              
                                // From Accomodation Details
                                if(isset($book_res->accomodation_details)){
                                    $accomodation_data = json_decode($book_res->accomodation_details);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                                $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->acc_hotel_name,
                                                        'room_id' => $acc_res->hotelRoom_type_id,
                                                        'room_type' => $acc_res->hotel_type_cat,
                                                        'check_in' => $acc_res->acc_check_in,
                                                        'check_out' => $acc_res->acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $acc_res->acc_qty,
                                                        'rooms_price' => $acc_res->price_per_room_purchase,
                                                        'rooms_total_price' => $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights),
                                                    ];
                                                
                                                $total_price = $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $acc_res->acc_qty;
                                            }
                                        }
                                        
                                        
                                    }  
                                }
                                
                                 // From More Accomodation Details
                                if(isset($book_res->accomodation_details_more)){
                                    $accomodation_data = json_decode($book_res->accomodation_details_more);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                                $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                 $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->more_acc_hotel_name,
                                                        'room_id' => $acc_res->more_hotelRoom_type_id,
                                                        'room_type' => $acc_res->more_hotel_type_cat,
                                                        'check_in' => $acc_res->more_acc_check_in,
                                                        'check_out' => $acc_res->more_acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $acc_res->more_acc_qty,
                                                        'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                        'rooms_total_price' => $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights),
                                                    ];
                                                    
                                                $total_price = $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $acc_res->more_acc_qty;    
                                            }
                                        }
                                        
                                        
                                    }
                                    
                                }
                                
                                // From Website Booking
                                if(isset($book_res->reservation_response)){
                                    $reservation_data = json_decode($book_res->reservation_response);
                                    if($reservation_data){
                                     
                                                $diff = strtotime($reservation_data->hotel_details->checkOut) - strtotime($reservation_data->hotel_details->checkIn);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                foreach($reservation_data->hotel_details->rooms as $room_res){
                                                    $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $reservation_data->hotel_details->hotel_name,
                                                        'room_id' => $room_res->room_code,
                                                        'room_type' => $room_res->room_name,
                                                        'check_in' => $reservation_data->hotel_details->checkOut,
                                                        'check_out' => $reservation_data->hotel_details->checkOut,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $room_res->room_rates[0]->room_qty,
                                                        'rooms_price' => $room_res->room_rates[0]->net,
                                                        'rooms_total_price' => $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights),
                                                    ];
                                                    
                                                    $total_price = $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights);
                                                    $total_payable_price += $total_price;
                                                    $total_rooms_count += $room_res->room_rates[0]->room_qty;  
                                                }
                                            
                                                
                                        
                                    }
                                    
                                }
                                
                            }
                                            
                          
                            
                            $supplier_booking_data[] = floor($total_payable_price * 100) / 100;
                            
                            
                            $startDate = $endDate->copy()->addDays(1);
                            if($i == '4'){
                                $endDate = $startDate->copy()->addDays(2);
                            }else{
                                 $endDate = $startDate->copy()->addDays(6);
                            }
                            
                            $startDateWeek = $startDate->toDateString();
                            $endDateWeek = $endDate->toDateString();
                        }
                        
                        $series_data[] = [
                                'name' =>  $supplier_res->room_supplier_name,
                                'data' => $supplier_booking_data
                            ];
                    }
                        
                   
                        // dd($startDateWeek,$endDateWeek);
                    //   print_r($weekDates);
                    //   die;
                    $categories_data = ['1st Week','2nd Week','3rd Week','4th Week','5th Week'];
                }
                
                // Generate Graph Data For Year
                if($request_data->date_type == 'data_year_wise'){
                    
                    $currentYear = date('Y');
                    $monthsData = [];
                
                    for ($month = 1; $month <= 12; $month++) {
                        
                         $startOfMonth = Carbon::create($currentYear, $month, 1)->startOfMonth();
                         $endOfMonth = Carbon::create($currentYear, $month, 1)->endOfMonth();
                         
                          $categories_data[] = $startOfMonth->format('F');
                         
                         $startOfMonth = $startOfMonth->format('Y-m-d');
                         $endOfMonth = $endOfMonth->format('Y-m-d');
        
                        $monthsData[] = (Object)[
                            'month' => $month,
                            'start_date' => $startOfMonth,
                            'end_date' => $endOfMonth,
                        ];
                    }
                    
                    foreach($limitedSupplierData as $sup_res){
                        $supplier_booking_data = [];
                        foreach($monthsData as $month_res){
                            // Add 7 days to the start date


                              $supplierBooking = DB::table('rooms')
                            ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                            ->leftJoin('add_manage_invoices', function ($join) {
                                $join->on('rooms_bookings_details.booking_id', '=', 'add_manage_invoices.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'Invoices');
                            })
                            ->leftJoin('tours', function ($join) {
                                $join->on('rooms_bookings_details.package_id', '=', 'tours.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'package');
                            })
                            ->leftJoin('hotels_bookings', function ($join) {
                                $join->on('rooms_bookings_details.booking_id', '=', 'hotels_bookings.id')
                                    ->where('rooms_bookings_details.booking_from', '=', 'website');
                            })
                            ->where('rooms.room_supplier_name',$sup_res->id)
                            ->whereDate('rooms_bookings_details.current_date','>=', $month_res->start_date)
                            ->whereDate('rooms_bookings_details.current_date','<=', $month_res->end_date)
                            ->select('rooms.id','rooms_bookings_details.booking_from','rooms_bookings_details.booking_id','rooms_bookings_details.package_id','rooms.room_supplier_name'
                                    ,'tours.accomodation_details','tours.accomodation_details_more','tours.id as package_id'
                                    ,'add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.id as Invoice_id'
                                    ,'hotels_bookings.invoice_no as hotel_booking_invoice','hotels_bookings.reservation_response')
                            ->orderBy('rooms.id','asc')
                            ->get();
                            
                
                            $total_payable_price = 0;
                            foreach($supplierBooking as $book_res){
                                
                              
                                // From Accomodation Details
                                if(isset($book_res->accomodation_details)){
                                    $accomodation_data = json_decode($book_res->accomodation_details);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->hotelRoom_type_id){
                                                $diff = strtotime($acc_res->acc_check_in) - strtotime($acc_res->acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->acc_hotel_name,
                                                        'room_id' => $acc_res->hotelRoom_type_id,
                                                        'room_type' => $acc_res->hotel_type_cat,
                                                        'check_in' => $acc_res->acc_check_in,
                                                        'check_out' => $acc_res->acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $acc_res->acc_qty,
                                                        'rooms_price' => $acc_res->price_per_room_purchase,
                                                        'rooms_total_price' => $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights),
                                                    ];
                                                
                                                $total_price = $acc_res->acc_qty * ($acc_res->price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $acc_res->acc_qty;
                                            }
                                        }
                                        
                                        
                                    }  
                                }
                                
                                 // From More Accomodation Details
                                if(isset($book_res->accomodation_details_more)){
                                    $accomodation_data = json_decode($book_res->accomodation_details_more);
                                    if($accomodation_data){
                                        foreach($accomodation_data as $acc_res){
                                            if($acc_res->more_hotel_supplier_id == $book_res->room_supplier_name && $book_res->id == $acc_res->more_hotelRoom_type_id){
                                                $diff = strtotime($acc_res->more_acc_check_in) - strtotime($acc_res->more_acc_check_out);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                 $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $acc_res->more_acc_hotel_name,
                                                        'room_id' => $acc_res->more_hotelRoom_type_id,
                                                        'room_type' => $acc_res->more_hotel_type_cat,
                                                        'check_in' => $acc_res->more_acc_check_in,
                                                        'check_out' => $acc_res->more_acc_check_out,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $acc_res->more_acc_qty,
                                                        'rooms_price' => $acc_res->more_price_per_room_purchase,
                                                        'rooms_total_price' => $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights),
                                                    ];
                                                    
                                                $total_price = $acc_res->more_acc_qty * ($acc_res->more_price_per_room_purchase * $no_of_nights);
                                                $total_payable_price += $total_price;
                                                $total_rooms_count += $acc_res->more_acc_qty;    
                                            }
                                        }
                                        
                                        
                                    }
                                    
                                }
                                
                                // From Website Booking
                                if(isset($book_res->reservation_response)){
                                    $reservation_data = json_decode($book_res->reservation_response);
                                    if($reservation_data){
                                     
                                                $diff = strtotime($reservation_data->hotel_details->checkOut) - strtotime($reservation_data->hotel_details->checkIn);
                                                $no_of_nights = abs(round($diff / 86400));
                                                
                                                $total_nights += $no_of_nights;
                                                
                                                foreach($reservation_data->hotel_details->rooms as $room_res){
                                                    $supplier_rooms_bookings[] = (Object)[
                                                        'hotel_name' => $reservation_data->hotel_details->hotel_name,
                                                        'room_id' => $room_res->room_code,
                                                        'room_type' => $room_res->room_name,
                                                        'check_in' => $reservation_data->hotel_details->checkOut,
                                                        'check_out' => $reservation_data->hotel_details->checkOut,
                                                        'no_of_nights' => $no_of_nights,
                                                        'rooms_qty' => $room_res->room_rates[0]->room_qty,
                                                        'rooms_price' => $room_res->room_rates[0]->net,
                                                        'rooms_total_price' => $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights),
                                                    ];
                                                    
                                                    $total_price = $room_res->room_rates[0]->room_qty * ($room_res->room_rates[0]->net * $no_of_nights);
                                                    $total_payable_price += $total_price;
                                                    $total_rooms_count += $room_res->room_rates[0]->room_qty;  
                                                }
                                            
                                                
                                        
                                    }
                                    
                                }
                                
                            }
                                            
                          
                            
                            $supplier_booking_data[] = floor($total_payable_price * 100) / 100;
                           
                        }
                        
                        $series_data[] = [
                                'name' => $sup_res->room_supplier_name,
                                'data' => $supplier_booking_data
                            ];
                    }
                    // print_r($series_data);
                    // die;
        
                }
                
                return response()->json([
                    'status'            => 'success',
                    'by'                => 'total_reven',
                    'data'              => $limitedSupplierData,
                    'series_data'       => $series_data,
                    'categories_data'   => $categories_data,
                ]);
            }else{
                    
                $all_supplier_data = new Collection($all_supplier_data);
                
                $all_supplier_data = $all_supplier_data->sortByDesc('booking_count');
                
                $all_supplier_data = $all_supplier_data->values();
                
                $all_supplier_data = $all_supplier_data->toArray();
                
                $limitedSupplierData = array_slice($all_supplier_data, 0, 4);
                
                if(sizeOf($all_supplier_data) >= 4){
                    $limitedSupplierData = array_slice($all_supplier_data, 0, 4);
                }else{
                    $limitedSupplierData = $limitedAgentData;
                }
                
                $series_data = [];
                $categories_data = [];
                
                if($request_data->date_type == 'all_data'){
                    $currentYear = date('Y');
                    $monthsData = [];
                
                    for ($month = 1; $month <= 12; $month++) {
                        
                         $startOfMonth = Carbon::create($currentYear, $month, 1)->startOfMonth();
                         $endOfMonth = Carbon::create($currentYear, $month, 1)->endOfMonth();
                         
                          $categories_data[] = $startOfMonth->format('F');
                         
                         $startOfMonth = $startOfMonth->format('Y-m-d');
                         $endOfMonth = $endOfMonth->format('Y-m-d');
        
                        $monthsData[] = (Object)[
                            'month' => $month,
                            'start_date' => $startOfMonth,
                            'end_date' => $endOfMonth,
                        ];
                    }
                    
                    foreach($limitedSupplierData as $supplier_res){
                        
                   
                        
                        $supplier_booking_data = [];
                        foreach($monthsData as $month_res){
                            // Add 7 days to the start date
                            
                            
                            $totalQuantityBooked = DB::table('rooms')
                                                    ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                                                    ->where('rooms.room_supplier_name', $supplier_res->id)
                                                    ->whereDate('rooms_bookings_details.current_date','>=', $month_res->start_date)
                                                    ->whereDate('rooms_bookings_details.current_date','<=', $month_res->end_date)
                                                    ->sum('rooms_bookings_details.quantity');
                                                    
                        
                                                        
                          
                            
                            $supplier_booking_data[] = floor($totalQuantityBooked * 100) / 100;
                           
                        }
                        
                        $series_data[] = [
                                'name' => $supplier_res->room_supplier_name,
                                'data' => $supplier_booking_data
                            ];
                    }
                }
                
                if($request_data->date_type == 'data_today_wise'){
        
                         $date = date('Y-m-d');
                        foreach($limitedSupplierData as $supplier_res){
                            
                             $totalQuantityBooked = DB::table('rooms')
                                                    ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                                                    ->where('rooms.room_supplier_name', $supplier_res->id)
                                                    ->whereDate('rooms_bookings_details.current_date', $date)
                                                    ->sum('rooms_bookings_details.quantity');
                            
                            $supplier_booking_data = [$totalQuantityBooked];
                            $series_data[] = [
                                    'name' => $supplier_res->room_supplier_name,
                                    'data' => $supplier_booking_data
                                ];
                        }
                        
                        $categories_data = [$date];
                 
                }
                
                if($request_data->date_type == 'data_Yesterday_wise'){
        
                         $date = date('Y-m-d',strtotime("-1 days"));
                        foreach($limitedSupplierData as $supplier_res){
                            
                           $totalQuantityBooked = DB::table('rooms')
                                                    ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                                                    ->where('rooms.room_supplier_name', $supplier_res->id)
                                                    ->whereDate('rooms_bookings_details.current_date', $date)
                                                    ->sum('rooms_bookings_details.quantity');
                            
                            $supplier_booking_data = [$totalQuantityBooked];
                            $series_data[] = [
                                    'name' => $supplier_res->room_supplier_name,
                                    'data' => $supplier_booking_data
                                ];
                        }
                        
                        $categories_data = [$date];
                 
                }
                
                // Generate Graph Data For Week
                if($request_data->date_type == 'data_week_wise'){
                   $today = Carbon::now();
                    
                    // Create a CarbonPeriod instance for the current week
                   $startOfWeek = $today->startOfWeek()->toDateString();
                    $endOfWeek = $today->endOfWeek()->toDateString();
                    
                    // Create a CarbonPeriod instance for the current week
                    $period = CarbonPeriod::create($startOfWeek, $endOfWeek);
                    
                    // Get the dates of the current week as an array
                    $datesOfWeek = [];
                    foreach ($period as $date) {
                        $datesOfWeek[] = $date->toDateString();
                    }
                    
                    // Get Data for Agent 
                    
                    foreach($limitedSupplierData as $supplier_res){
                        
                        // Loop On DatesOfWeek
                        
                        $supplier_booking_data = [];
                        foreach($datesOfWeek as $date_res){

                          $totalQuantityBooked = DB::table('rooms')
                                                    ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                                                    ->where('rooms.room_supplier_name', $supplier_res->id)
                                                    ->whereDate('rooms_bookings_details.current_date',$date_res)
                                                    ->sum('rooms_bookings_details.quantity');
                                            
                          
                            
                            $supplier_booking_data[] = $totalQuantityBooked;
                        }
                        
                        $series_data[] = [
                                'name' => $supplier_res->room_supplier_name,
                                'data' => $supplier_booking_data
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
                    
                    foreach($limitedSupplierData as $supplier_res){
                        
                        // Start Date Of Month
                        // $currentDate = Carbon::now();
                        $startDate = Carbon::now()->startOfMonth();
                        // $startDate = $currentDate->subMonth()->firstOfMonth();
                        $startDateWeek = $startDate->toDateString();
                        $endDate = $startDate->copy()->addDays(6);
                        $endDateWeek = $endDate->toDateString();
                        
                        $supplier_booking_data = [];
                        for($i=1; $i<=5; $i++){
                            // Add 7 days to the start date
                            
                             $totalQuantityBooked = DB::table('rooms')
                                                    ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                                                    ->where('rooms.room_supplier_name', $supplier_res->id)
                                                    ->whereDate('rooms_bookings_details.current_date','>=', $startDate)
                                                    ->whereDate('rooms_bookings_details.current_date','<=', $endDate)
                                                    ->sum('rooms_bookings_details.quantity');
                                            
                          
                            
                            $supplier_booking_data[] = $totalQuantityBooked;
                            
                            
                            $startDate = $endDate->copy()->addDays(1);
                            if($i == '4'){
                                $endDate = $startDate->copy()->addDays(2);
                            }else{
                                 $endDate = $startDate->copy()->addDays(6);
                            }
                            
                            $startDateWeek = $startDate->toDateString();
                            $endDateWeek = $endDate->toDateString();
                        }
                        
                        $series_data[] = [
                               'name' => $supplier_res->room_supplier_name,
                                'data' => $supplier_booking_data
                            ];
                    }
                        
                   
                        // dd($startDateWeek,$endDateWeek);
                    //   print_r($weekDates);
                    //   die;
                    $categories_data = ['1st Week','2nd Week','3rd Week','4th Week','5th Week'];
                }
                
                // Generate Graph Data For Year
                if($request_data->date_type == 'data_year_wise'){
                    
                    $currentYear = date('Y');
                    $monthsData = [];
                
                    for ($month = 1; $month <= 12; $month++) {
                        
                         $startOfMonth = Carbon::create($currentYear, $month, 1)->startOfMonth();
                         $endOfMonth = Carbon::create($currentYear, $month, 1)->endOfMonth();
                         
                          $categories_data[] = $startOfMonth->format('F');
                         
                         $startOfMonth = $startOfMonth->format('Y-m-d');
                         $endOfMonth = $endOfMonth->format('Y-m-d');
        
                        $monthsData[] = (Object)[
                            'month' => $month,
                            'start_date' => $startOfMonth,
                            'end_date' => $endOfMonth,
                        ];
                    }
                    
                    foreach($limitedSupplierData as $supplier_res){
                        
                   
                        
                        $supplier_booking_data = [];
                        foreach($monthsData as $month_res){
                            // Add 7 days to the start date
                            
                             $totalQuantityBooked = DB::table('rooms')
                                                    ->join('rooms_bookings_details', 'rooms.id', '=', 'rooms_bookings_details.room_id')
                                                    ->where('rooms.room_supplier_name', $supplier_res->id)
                                                    ->whereDate('rooms_bookings_details.current_date','>=', $month_res->start_date)
                                                    ->whereDate('rooms_bookings_details.current_date','<=', $month_res->end_date)
                                                    ->sum('rooms_bookings_details.quantity');
                                            
                          
                            
                            $supplier_booking_data[] = floor($totalQuantityBooked * 100) / 100;
                           
                        }
                        
                        $series_data[] = [
                                'name' => $supplier_res->room_supplier_name,
                                'data' => $supplier_booking_data
                            ];
                    }
                    // print_r($series_data);
                    // die;
        
                }
                
                return response()->json([
                    'status'            => 'success',
                    'by'                => 'total_booking',
                    'data'              => $limitedSupplierData,
                    'series_data'       => $series_data,
                    'categories_data'   => $categories_data,
                ]);
            }
        }else{
            return response()->json([
                'status'    => 'error',
                'data'      => '',
            ]);
        }
    }
    
    public function agent_booking_filter_subUser(Request $request){
        $userData               = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        $request_data           = json_decode($request->request_data);
        $agentallBookingsObject = [];
        $agent_Groups           = [];
        
        if($request_data->date_type == 'all_data'){
            $agentsInvoices         = DB::table('Agents_detail')
                                        ->leftJoin('add_manage_invoices','add_manage_invoices.agent_Id','Agents_detail.id')
                                        ->where('Agents_detail.customer_id',$userData->id)
                                        ->where('Agents_detail.SU_id',$request->SU_id)
                                        // ->select('Agents_detail.id', 'Agents_detail.agent_Name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                                        ->select('Agents_detail.id','Agents_detail.agent_Refrence_No', 'Agents_detail.agent_Name','Agents_detail.company_name','Agents_detail.balance','Agents_detail.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_Company) as Invoices_prices_sum'))
                                        ->groupBy('Agents_detail.id')
                                        ->orderBy('Agents_detail.id','asc')
                                        ->get();
            
            foreach($agentsInvoices as $val_AID){
                $booked_GD  = DB::table('addGroupsdetails')->where('SU_id',$request->SU_id)
                                ->where('group_Client_Prefix',$val_AID->agent_Refrence_No)
                                ->select('group_Name','total_Invoices','group_Passport_Count','group_Client_Prefix')
                                ->get();
                                
                if($booked_GD->isEmpty()){
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => '',
                    ];
                    array_push($agent_Groups,$data_AG);
                }else{
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => $booked_GD,
                    ];
                    array_push($agent_Groups,$data_AG);
                }
            }
            
            $agentsPackageBookings  = DB::table('Agents_detail')
                                        ->leftJoin('cart_details','cart_details.agent_name','Agents_detail.id')
                                        ->where('Agents_detail.customer_id',$userData->id)
                                        ->where('Agents_detail.SU_id',$request->SU_id)
                                        ->select('Agents_detail.id', 'Agents_detail.agent_Name','Agents_detail.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                                        ->groupBy('Agents_detail.id')
                                        ->orderBy('Agents_detail.id','asc')
                                        ->get();
            foreach($agentsInvoices as $index => $invoice_res){
                $total_prices = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                $agentallBookingsObject[] = (Object)[
                        'agent_id' => $invoice_res->id,
                        'agent_Name' => $invoice_res->agent_Name,
                        'company_name' => $invoice_res->company_name,
                        'currency' =>  $invoice_res->currency,
                        'Invoices_booking' => $invoice_res->Invoices_booking,
                        'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                        'packages_booking' => $agentsPackageBookings[$index]->packages_booking,
                        'packages_prices_sum' => number_format($agentsPackageBookings[$index]->packages_prices_sum,2),
                        'all_bookings' => $invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking,
                        'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking),
                        'total_prices' => $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,
                        'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,2),
                        'Paid' => number_format($total_prices - $invoice_res->balance,2),
                        'remaining' => number_format($invoice_res->balance,2)
                    ]; 
            }
        }
        
        if($request_data->date_type == 'data_today_wise'){
            $today_date     = date('Y-m-d');
            
            $agentsInvoices = DB::table('Agents_detail')
                                    ->leftJoin('add_manage_invoices', function ($join) use($today_date) {
                                    $join->on('add_manage_invoices.agent_Id', '=', 'Agents_detail.id')
                                    ->whereDate('add_manage_invoices.created_at', $today_date);
                                    })
                                ->where('Agents_detail.customer_id',$userData->id)
                                ->where('Agents_detail.SU_id',$request->SU_id)
                                // ->select('Agents_detail.id', 'Agents_detail.agent_Name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                                ->select('Agents_detail.id','Agents_detail.agent_Refrence_No', 'Agents_detail.agent_Name','Agents_detail.company_name','Agents_detail.balance','Agents_detail.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_Company) as Invoices_prices_sum'))
                                ->groupBy('Agents_detail.id')
                                ->orderBy('Agents_detail.id','asc')
                                ->get();
            
            foreach($agentsInvoices as $val_AID){
                $booked_GD  = DB::table('addGroupsdetails')->where('SU_id',$request->SU_id)
                                ->where('group_Client_Prefix',$val_AID->agent_Refrence_No)
                                ->select('group_Name','total_Invoices','group_Passport_Count','group_Client_Prefix')
                                ->get();
                                
                if($booked_GD->isEmpty()){
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => '',
                    ];
                    array_push($agent_Groups,$data_AG);
                }else{
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => $booked_GD,
                    ];
                    array_push($agent_Groups,$data_AG);
                }
            }
            
            $agentsPackageBookings = DB::table('Agents_detail')
                                ->leftJoin('cart_details', function ($join) use($today_date) {
                                                    $join->on('cart_details.agent_name', '=', 'Agents_detail.id')
                                                        ->whereDate('cart_details.created_at', $today_date);
                                                })
                                ->where('Agents_detail.customer_id',$userData->id)
                                ->where('Agents_detail.SU_id',$request->SU_id)
                                ->select('Agents_detail.id', 'Agents_detail.agent_Name','Agents_detail.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                                ->groupBy('Agents_detail.id')
                                ->orderBy('Agents_detail.id','asc')
                                ->get();
                                
                
                // print_r($agentsInvoices);
                // print_r($agentsPackageBookings);
                // die;
                foreach($agentsInvoices as $index => $invoice_res){
                    $total_prices = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                    
                    $payment_today = DB::table('agents_ledgers_new')->where('agent_id',$invoice_res->id)->where('SU_id',$request->SU_id)
                                                   ->whereDate('date',$today_date)
                                                  ->where('received_id','!=',NULL)
                                                   ->sum('payment');
                    $total_price = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                    
                    $agentallBookingsObject[] = (Object)[
                            'agent_id' => $invoice_res->id,
                            'agent_Name' => $invoice_res->agent_Name,
                            'company_name' => $invoice_res->company_name,
                            'currency' =>  $invoice_res->currency,
                            'Invoices_booking' => $invoice_res->Invoices_booking,
                            'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                            'packages_booking' => $agentsPackageBookings[$index]->packages_booking,
                            'packages_prices_sum' => number_format($agentsPackageBookings[$index]->packages_prices_sum,2),
                            'all_bookings' => $invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking,
                            'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking),
                            'total_prices' => $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,
                            'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,2),
                            'Paid' => number_format($payment_today,2),
                            'remaining' => number_format($total_price - $payment_today,2)
                        ];
                }
        }
        
        if($request_data->date_type == 'data_Yesterday_wise'){
            
             $date = date('Y-m-d',strtotime("-1 days"));
            //  dd($today_date);
             $agentsInvoices = DB::table('Agents_detail')
                                ->leftJoin('add_manage_invoices', function ($join) use($date) {
                                                    $join->on('add_manage_invoices.agent_Id', '=', 'Agents_detail.id')
                                                        ->whereDate('add_manage_invoices.created_at', $date);
                                                })
                                ->where('Agents_detail.customer_id',$userData->id)
                                ->where('Agents_detail.SU_id',$request->SU_id)
                                // ->select('Agents_detail.id', 'Agents_detail.agent_Name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                                ->select('Agents_detail.id','Agents_detail.agent_Refrence_No', 'Agents_detail.agent_Name','Agents_detail.company_name','Agents_detail.balance','Agents_detail.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_Company) as Invoices_prices_sum'))
                                ->groupBy('Agents_detail.id')
                                ->orderBy('Agents_detail.id','asc')
                                ->get();
            
            foreach($agentsInvoices as $val_AID){
                $booked_GD  = DB::table('addGroupsdetails')->where('SU_id',$request->SU_id)
                                ->where('group_Client_Prefix',$val_AID->agent_Refrence_No)
                                ->select('group_Name','total_Invoices','group_Passport_Count','group_Client_Prefix')
                                ->get();
                                
                if($booked_GD->isEmpty()){
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => '',
                    ];
                    array_push($agent_Groups,$data_AG);
                }else{
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => $booked_GD,
                    ];
                    array_push($agent_Groups,$data_AG);
                }
            }
            
                $agentsPackageBookings = DB::table('Agents_detail')
                                ->leftJoin('cart_details', function ($join) use($date) {
                                                    $join->on('cart_details.agent_name', '=', 'Agents_detail.id')
                                                        ->whereDate('cart_details.created_at', $date);
                                                })
                                ->where('Agents_detail.customer_id',$userData->id)
                                ->where('Agents_detail.SU_id',$request->SU_id)
                                ->select('Agents_detail.id', 'Agents_detail.agent_Name','Agents_detail.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                                ->groupBy('Agents_detail.id')
                                ->orderBy('Agents_detail.id','asc')
                                ->get();
                                
                
                // print_r($agentsInvoices);
                // print_r($agentsPackageBookings);
                // die;
                foreach($agentsInvoices as $index => $invoice_res){
                    $total_prices = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                    
                    $payment_today = DB::table('agents_ledgers_new')->where('agent_id',$invoice_res->id)->where('SU_id',$request->SU_id)
                                                   ->whereDate('date',$date)
                                                  ->where('received_id','!=',NULL)
                                                   ->sum('payment');
                    $total_price = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                    
                    $agentallBookingsObject[] = (Object)[
                            'agent_id' => $invoice_res->id,
                            'agent_Name' => $invoice_res->agent_Name,
                            'company_name' => $invoice_res->company_name,
                            'currency' =>  $invoice_res->currency,
                            'Invoices_booking' => $invoice_res->Invoices_booking,
                            'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                            'packages_booking' => $agentsPackageBookings[$index]->packages_booking,
                            'packages_prices_sum' => number_format($agentsPackageBookings[$index]->packages_prices_sum,2),
                            'all_bookings' => $invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking,
                            'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking),
                            'total_prices' => $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,
                            'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,2),
                            'Paid' => number_format($payment_today,2),
                            'remaining' => number_format($total_price - $payment_today,2)
                        ];
                }
        }
        
        if($request_data->date_type == 'data_week_wise'){
            
            $startOfWeek = Carbon::now()->startOfWeek();
            $start_date = $startOfWeek->format('Y-m-d');
            $end_date = date('Y-m-d');
            
            //  dd($today_date);
             $agentsInvoices = DB::table('Agents_detail')
                                ->leftJoin('add_manage_invoices', function ($join) use($start_date,$end_date) {
                                                    $join->on('add_manage_invoices.agent_Id', '=', 'Agents_detail.id')
                                                        ->whereDate('add_manage_invoices.created_at','>=', $start_date)
                                                        ->whereDate('add_manage_invoices.created_at','<=', $end_date);
                                                })
                                ->where('Agents_detail.customer_id',$userData->id)
                                ->where('Agents_detail.SU_id',$request->SU_id)
                                // ->select('Agents_detail.id', 'Agents_detail.agent_Name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                                ->select('Agents_detail.id','Agents_detail.agent_Refrence_No', 'Agents_detail.agent_Name','Agents_detail.company_name','Agents_detail.balance','Agents_detail.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_Company) as Invoices_prices_sum'))
                                ->groupBy('Agents_detail.id')
                                ->orderBy('Agents_detail.id','asc')
                                ->get();
            
            foreach($agentsInvoices as $val_AID){
                $booked_GD  = DB::table('addGroupsdetails')->where('SU_id',$request->SU_id)
                                ->where('group_Client_Prefix',$val_AID->agent_Refrence_No)
                                ->select('group_Name','total_Invoices','group_Passport_Count','group_Client_Prefix')
                                ->get();
                                
                if($booked_GD->isEmpty()){
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => '',
                    ];
                    array_push($agent_Groups,$data_AG);
                }else{
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => $booked_GD,
                    ];
                    array_push($agent_Groups,$data_AG);
                }
            }
                
                $agentsPackageBookings = DB::table('Agents_detail')
                                ->leftJoin('cart_details', function ($join) use($start_date,$end_date) {
                                                    $join->on('cart_details.agent_name', '=', 'Agents_detail.id')
                                                        ->whereDate('cart_details.created_at','>=', $start_date)
                                                        ->whereDate('cart_details.created_at','<=', $end_date);
                                                })
                                ->where('Agents_detail.customer_id',$userData->id)
                                ->where('Agents_detail.SU_id',$request->SU_id)
                                ->select('Agents_detail.id', 'Agents_detail.agent_Name','Agents_detail.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                                ->groupBy('Agents_detail.id')
                                ->orderBy('Agents_detail.id','asc')
                                ->get();
                
                foreach($agentsInvoices as $index => $invoice_res){
                    $total_prices = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                    
                    $payment_today = DB::table('agents_ledgers_new')->where('agent_id',$invoice_res->id)->where('SU_id',$request->SU_id)
                                                   ->whereDate('date','>=', $start_date)
                                                   ->whereDate('date','<=', $end_date)
                                                   ->where('received_id','!=',NULL)
                                                   ->sum('payment');
                    $total_price = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                    
                    $agentallBookingsObject[] = (Object)[
                            'agent_id' => $invoice_res->id,
                            'agent_Name' => $invoice_res->agent_Name,
                            'company_name' => $invoice_res->company_name,
                            'currency' =>  $invoice_res->currency,
                            'Invoices_booking' => $invoice_res->Invoices_booking,
                            'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                            'packages_booking' => $agentsPackageBookings[$index]->packages_booking,
                            'packages_prices_sum' => number_format($agentsPackageBookings[$index]->packages_prices_sum,2),
                            'all_bookings' => $invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking,
                            'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking),
                            'total_prices' => $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,
                            'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,2),
                            'Paid' => number_format($payment_today,2),
                            'remaining' => number_format($total_price - $payment_today,2)
                        ];
                }
        }
        
        if($request_data->date_type == 'data_month_wise'){
            
            $startOfMonth = Carbon::now()->startOfMonth();
            $start_date = $startOfMonth->format('Y-m-d');
            $end_date = date('Y-m-d');
            
            //  dd($today_date);
             $agentsInvoices = DB::table('Agents_detail')
                                ->leftJoin('add_manage_invoices', function ($join) use($start_date,$end_date) {
                                                    $join->on('add_manage_invoices.agent_Id', '=', 'Agents_detail.id')
                                                        ->whereDate('add_manage_invoices.created_at','>=', $start_date)
                                                        ->whereDate('add_manage_invoices.created_at','<=', $end_date);
                                                })
                                ->where('Agents_detail.customer_id',$userData->id)
                                ->where('Agents_detail.SU_id',$request->SU_id)
                                // ->select('Agents_detail.id', 'Agents_detail.agent_Name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                                ->select('Agents_detail.id','Agents_detail.agent_Refrence_No', 'Agents_detail.agent_Name','Agents_detail.company_name','Agents_detail.balance','Agents_detail.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_Company) as Invoices_prices_sum'))
                                ->groupBy('Agents_detail.id')
                                ->orderBy('Agents_detail.id','asc')
                                ->get();
            
            foreach($agentsInvoices as $val_AID){
                $booked_GD  = DB::table('addGroupsdetails')->where('SU_id',$request->SU_id)
                                ->where('group_Client_Prefix',$val_AID->agent_Refrence_No)
                                ->select('group_Name','total_Invoices','group_Passport_Count','group_Client_Prefix')
                                ->get();
                                
                if($booked_GD->isEmpty()){
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => '',
                    ];
                    array_push($agent_Groups,$data_AG);
                }else{
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => $booked_GD,
                    ];
                    array_push($agent_Groups,$data_AG);
                }
            }
                
                $agentsPackageBookings = DB::table('Agents_detail')
                                ->leftJoin('cart_details', function ($join) use($start_date,$end_date) {
                                                    $join->on('cart_details.agent_name', '=', 'Agents_detail.id')
                                                        ->whereDate('cart_details.created_at','>=', $start_date)
                                                        ->whereDate('cart_details.created_at','<=', $end_date);
                                                })
                                ->where('Agents_detail.customer_id',$userData->id)
                                ->where('Agents_detail.SU_id',$request->SU_id)
                                ->select('Agents_detail.id', 'Agents_detail.agent_Name','Agents_detail.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                                ->groupBy('Agents_detail.id')
                                ->orderBy('Agents_detail.id','asc')
                                ->get();
                                
                
                // print_r($agentsInvoices);
                // print_r($agentsPackageBookings);
                // die;
                foreach($agentsInvoices as $index => $invoice_res){
                    $total_prices = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                    
                    $payment_today = DB::table('agents_ledgers_new')->where('agent_id',$invoice_res->id)->where('SU_id',$request->SU_id)
                                                   ->whereDate('date','>=', $start_date)
                                                   ->whereDate('date','<=', $end_date)
                                                   ->where('received_id','!=',NULL)
                                                   ->sum('payment');
                    $total_price = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                    
                    $agentallBookingsObject[] = (Object)[
                            'agent_id' => $invoice_res->id,
                            'agent_Name' => $invoice_res->agent_Name,
                            'company_name' => $invoice_res->company_name,
                            'currency' =>  $invoice_res->currency,
                            'Invoices_booking' => $invoice_res->Invoices_booking,
                            'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                            'packages_booking' => $agentsPackageBookings[$index]->packages_booking,
                            'packages_prices_sum' => number_format($agentsPackageBookings[$index]->packages_prices_sum,2),
                            'all_bookings' => $invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking,
                            'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking),
                            'total_prices' => $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,
                            'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,2),
                            'Paid' => number_format($payment_today,2),
                            'remaining' => number_format($total_price - $payment_today,2)
                        ];
                }
        }
        
        if($request_data->date_type == 'data_year_wise'){
            
            $startOfYear = Carbon::now()->startOfYear();
            $start_date = $startOfYear->format('Y-m-d');
            $end_date = date('Y-m-d');
            
            //  dd($today_date);
             $agentsInvoices = DB::table('Agents_detail')
                                ->leftJoin('add_manage_invoices', function ($join) use($start_date,$end_date) {
                                                    $join->on('add_manage_invoices.agent_Id', '=', 'Agents_detail.id')
                                                        ->whereDate('add_manage_invoices.created_at','>=', $start_date)
                                                        ->whereDate('add_manage_invoices.created_at','<=', $end_date);
                                                })
                                ->where('Agents_detail.customer_id',$userData->id)
                                ->where('Agents_detail.SU_id',$request->SU_id)
                                // ->select('Agents_detail.id', 'Agents_detail.agent_Name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                                ->select('Agents_detail.id','Agents_detail.agent_Refrence_No', 'Agents_detail.agent_Name','Agents_detail.company_name','Agents_detail.balance','Agents_detail.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_Company) as Invoices_prices_sum'))
                                ->groupBy('Agents_detail.id')
                                ->orderBy('Agents_detail.id','asc')
                                ->get();
            
            foreach($agentsInvoices as $val_AID){
                $booked_GD  = DB::table('addGroupsdetails')->where('SU_id',$request->SU_id)
                                ->where('group_Client_Prefix',$val_AID->agent_Refrence_No)
                                ->select('group_Name','total_Invoices','group_Passport_Count','group_Client_Prefix')
                                ->get();
                                
                if($booked_GD->isEmpty()){
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => '',
                    ];
                    array_push($agent_Groups,$data_AG);
                }else{
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => $booked_GD,
                    ];
                    array_push($agent_Groups,$data_AG);
                }
            }
            
                $agentsPackageBookings = DB::table('Agents_detail')
                                ->leftJoin('cart_details', function ($join) use($start_date,$end_date) {
                                                    $join->on('cart_details.agent_name', '=', 'Agents_detail.id')
                                                        ->whereDate('cart_details.created_at','>=', $start_date)
                                                        ->whereDate('cart_details.created_at','<=', $end_date);
                                                })
                                ->where('Agents_detail.customer_id',$userData->id)
                                ->where('Agents_detail.SU_id',$request->SU_id)
                                ->select('Agents_detail.id', 'Agents_detail.agent_Name','Agents_detail.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                                ->groupBy('Agents_detail.id')
                                ->orderBy('Agents_detail.id','asc')
                                ->get();
                                
                
                // print_r($agentsInvoices);
                // print_r($agentsPackageBookings);
                // die;
                foreach($agentsInvoices as $index => $invoice_res){
                    $total_prices = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                    
                    $payment_today = DB::table('agents_ledgers_new')->where('agent_id',$invoice_res->id)->where('SU_id',$request->SU_id)
                                                   ->whereDate('date','>=', $start_date)
                                                   ->whereDate('date','<=', $end_date)
                                                   ->where('received_id','!=',NULL)
                                                   ->sum('payment');
                    $total_price = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                    
                    $agentallBookingsObject[] = (Object)[
                            'agent_id' => $invoice_res->id,
                            'agent_Name' => $invoice_res->agent_Name,
                            'company_name' => $invoice_res->company_name,
                            'currency' =>  $invoice_res->currency,
                            'Invoices_booking' => $invoice_res->Invoices_booking,
                            'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                            'packages_booking' => $agentsPackageBookings[$index]->packages_booking,
                            'packages_prices_sum' => number_format($agentsPackageBookings[$index]->packages_prices_sum,2),
                            'all_bookings' => $invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking,
                            'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking),
                            'total_prices' => $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,
                            'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,2),
                            'Paid' => number_format($payment_today,2),
                            'remaining' => number_format($total_price - $payment_today,2)
                        ];
                }
        }
        
        if($request_data->date_type == 'data_wise'){
            
            $start_date = $request_data->start_date;
            $end_date = $request_data->end_date;
            
            //  dd($today_date);
             $agentsInvoices = DB::table('Agents_detail')
                                ->leftJoin('add_manage_invoices', function ($join) use($start_date,$end_date) {
                                                    $join->on('add_manage_invoices.agent_Id', '=', 'Agents_detail.id')
                                                        ->whereDate('add_manage_invoices.created_at','>=', $start_date)
                                                        ->whereDate('add_manage_invoices.created_at','<=', $end_date);
                                                })
                                ->where('Agents_detail.customer_id',$userData->id)
                                ->where('Agents_detail.SU_id',$request->SU_id)
                                // ->select('Agents_detail.id', 'Agents_detail.agent_Name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                                ->select('Agents_detail.id','Agents_detail.agent_Refrence_No', 'Agents_detail.agent_Name','Agents_detail.company_name','Agents_detail.balance','Agents_detail.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_Company) as Invoices_prices_sum'))
                                ->groupBy('Agents_detail.id')
                                ->orderBy('Agents_detail.id','asc')
                                ->get();
            
            foreach($agentsInvoices as $val_AID){
                $booked_GD  = DB::table('addGroupsdetails')->where('SU_id',$request->SU_id)
                                ->where('group_Client_Prefix',$val_AID->agent_Refrence_No)
                                ->select('group_Name','total_Invoices','group_Passport_Count','group_Client_Prefix')
                                ->get();
                                
                if($booked_GD->isEmpty()){
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => '',
                    ];
                    array_push($agent_Groups,$data_AG);
                }else{
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => $booked_GD,
                    ];
                    array_push($agent_Groups,$data_AG);
                }
            }    
            
                $agentsPackageBookings = DB::table('Agents_detail')
                                ->leftJoin('cart_details', function ($join) use($start_date,$end_date) {
                                                    $join->on('cart_details.agent_name', '=', 'Agents_detail.id')
                                                        ->whereDate('cart_details.created_at','>=', $start_date)
                                                        ->whereDate('cart_details.created_at','<=', $end_date);
                                                })
                                ->where('Agents_detail.customer_id',$userData->id)
                                ->where('Agents_detail.SU_id',$request->SU_id)
                                ->select('Agents_detail.id', 'Agents_detail.agent_Name','Agents_detail.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                                ->groupBy('Agents_detail.id')
                                ->orderBy('Agents_detail.id','asc')
                                ->get();
                                
                
                // print_r($agentsInvoices);
                // print_r($agentsPackageBookings);
                // die;
                foreach($agentsInvoices as $index => $invoice_res){
                    $total_prices = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                    
                    $payment_today = DB::table('agents_ledgers_new')->where('agent_id',$invoice_res->id)->where('SU_id',$request->SU_id)
                                                   ->whereDate('date','>=', $start_date)
                                                   ->whereDate('date','<=', $end_date)
                                                   ->where('received_id','!=',NULL)
                                                   ->sum('payment');
                    $total_price = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                    
                    $agentallBookingsObject[] = (Object)[
                            'agent_id' => $invoice_res->id,
                            'agent_Name' => $invoice_res->agent_Name,
                            'company_name' => $invoice_res->company_name,
                            'currency' =>  $invoice_res->currency,
                            'Invoices_booking' => $invoice_res->Invoices_booking,
                            'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                            'packages_booking' => $agentsPackageBookings[$index]->packages_booking,
                            'packages_prices_sum' => number_format($agentsPackageBookings[$index]->packages_prices_sum,2),
                            'all_bookings' => $invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking,
                            'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking),
                            'total_prices' => $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,
                            'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,2),
                            'Paid' => number_format($payment_today,2),
                            'remaining' => number_format($total_price - $payment_today,2)
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
                $limitedAgentData = $agentallBookingsObject ?? '';
            }
            
            $series_data = [];
            $categories_data = [];
            
            // Generate Graph Data All
            if($request_data->date_type == 'all_data'){
                $currentYear = date('Y');
                $monthsData = [];
                
                for ($month = 1; $month <= 12; $month++) {
                    
                     $startOfMonth = Carbon::create($currentYear, $month, 1)->startOfMonth();
                     $endOfMonth = Carbon::create($currentYear, $month, 1)->endOfMonth();
                     
                      $categories_data[] = $startOfMonth->format('F');
                     
                     $startOfMonth = $startOfMonth->format('Y-m-d');
                     $endOfMonth = $endOfMonth->format('Y-m-d');
    
                    $monthsData[] = (Object)[
                        'month' => $month,
                        'start_date' => $startOfMonth,
                        'end_date' => $endOfMonth,
                    ];
                }
                if(isset($limitedAgentData) && $limitedAgentData != null && $limitedAgentData != ''){
                    foreach($limitedAgentData as $agent_res){
                        
                   
                        
                        $agent_booking_data = [];
                        foreach($monthsData as $month_res){
                            // Add 7 days to the start date
                            
                            $agentsInvoices = DB::table('add_manage_invoices')->where('agent_Id',$agent_res->agent_id)->where('SU_id',$request->SU_id)
                                                                              ->whereDate('add_manage_invoices.created_at','>=', $month_res->start_date)
                                                                              ->whereDate('add_manage_invoices.created_at','<=', $month_res->end_date)
                                                                              ->Sum('total_sale_price_all_Services');
        
                            $agentsPackageBookings =  DB::table('cart_details')->where('agent_name',$agent_res->agent_id)->where('SU_id',$request->SU_id)
                                                                      ->whereDate('cart_details.created_at','>=', $month_res->start_date)
                                                                      ->whereDate('cart_details.created_at','<=', $month_res->end_date)
                                                                      ->Sum('tour_total_price');
                                                                       
                            $total_booking_price = $agentsInvoices + $agentsPackageBookings;
                                            
                          
                            
                            $agent_booking_data[] = floor($total_booking_price * 100) / 100;
                           
                        }
                        
                        $series_data[] = [
                                'name' => $agent_res->agent_Name,
                                'data' => $agent_booking_data
                            ];
                    }
                }
            }
            
            // Generate Graph Data Today
            if($request_data->date_type == 'data_today_wise'){
                $date = date('Y-m-d');
                if(isset($limitedAgentData) && $limitedAgentData != null && $limitedAgentData != ''){
                    foreach($limitedAgentData as $agent_res){
                        $agent_booking_data = [$agent_res->total_prices];
                        $series_data[] = [
                            'name' => $agent_res->agent_Name,
                            'data' => $agent_booking_data
                        ];
                    }
                }
                $categories_data = [$date];
            }
            
            // Generate Graph Data YesterDay
            if($request_data->date_type == 'data_Yesterday_wise'){
            
                $date = date('Y-m-d',strtotime("-1 days"));
                if(isset($limitedAgentData) && $limitedAgentData != null && $limitedAgentData != ''){
                    foreach($limitedAgentData as $agent_res){
                        $agent_booking_data = [$agent_res->total_prices];
                        $series_data[] = [
                                'name' => $agent_res->agent_Name,
                                'data' => $agent_booking_data
                            ];
                    }
                }
                $categories_data = [$date];
            }
            
            // Generate Graph Data For Week
            if($request_data->date_type == 'data_week_wise'){
               $today = Carbon::now();
                
                // Create a CarbonPeriod instance for the current week
               $startOfWeek = $today->startOfWeek()->toDateString();
                $endOfWeek = $today->endOfWeek()->toDateString();
                
                // Create a CarbonPeriod instance for the current week
                $period = CarbonPeriod::create($startOfWeek, $endOfWeek);
                
                // Get the dates of the current week as an array
                $datesOfWeek = [];
                foreach ($period as $date) {
                    $datesOfWeek[] = $date->toDateString();
                }
                
                // Get Data for Agent 
                if(isset($limitedAgentData) && $limitedAgentData != null && $limitedAgentData != ''){
                    foreach($limitedAgentData as $agent_res){
                        
                        // Loop On DatesOfWeek
                        
                        $agent_booking_data = [];
                        foreach($datesOfWeek as $date_res){
                            $agentsInvoices = DB::table('add_manage_invoices')->where('agent_Id',$agent_res->agent_id)->where('SU_id',$request->SU_id)
                                                                              ->whereDate('created_at',$date_res)
                                                                              ->Sum('total_sale_price_all_Services');
        
                            $agentsPackageBookings =  DB::table('cart_details')->where('agent_name',$agent_res->agent_id)->where('SU_id',$request->SU_id)
                                                                      ->whereDate('created_at',$date_res)
                                                                      ->Sum('tour_total_price');
                                                                       
                            $total_booking_price = $agentsInvoices + $agentsPackageBookings;
                                            
                          
                            
                            $agent_booking_data[] = $total_booking_price;
                        }
                        
                        $series_data[] = [
                                'name' => $agent_res->agent_Name,
                                'data' => $agent_booking_data
                            ];
                    }
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
                if(isset($limitedAgentData) && $limitedAgentData != null && $limitedAgentData != ''){
                    foreach($limitedAgentData as $agent_res){
                        
                        // Start Date Of Month
                        // $currentDate = Carbon::now();
                        $startDate = Carbon::now()->startOfMonth();
                        // $startDate = $currentDate->subMonth()->firstOfMonth();
                        $startDateWeek = $startDate->toDateString();
                        $endDate = $startDate->copy()->addDays(6);
                        $endDateWeek = $endDate->toDateString();
                        
                        $agent_booking_data = [];
                        for($i=1; $i<=5; $i++){
                            // Add 7 days to the start date
                            
                            $agentsInvoices = DB::table('add_manage_invoices')->where('agent_Id',$agent_res->agent_id)->where('SU_id',$request->SU_id)
                                                                              ->whereDate('add_manage_invoices.created_at','>=', $startDate)
                                                                              ->whereDate('add_manage_invoices.created_at','<=', $endDate)
                                                                              ->Sum('total_sale_price_all_Services');
        
                            $agentsPackageBookings =  DB::table('cart_details')->where('agent_name',$agent_res->agent_id)->where('SU_id',$request->SU_id)
                                                                      ->whereDate('cart_details.created_at','>=', $startDate)
                                                                      ->whereDate('cart_details.created_at','<=', $endDate)
                                                                      ->Sum('tour_total_price');
                                                                       
                            $total_booking_price = $agentsInvoices + $agentsPackageBookings;
                                            
                          
                            
                            $agent_booking_data[] = $total_booking_price;
                            
                            
                            $startDate = $endDate->copy()->addDays(1);
                            if($i == '4'){
                                $endDate = $startDate->copy()->addDays(2);
                            }else{
                                 $endDate = $startDate->copy()->addDays(6);
                            }
                            
                            $startDateWeek = $startDate->toDateString();
                            $endDateWeek = $endDate->toDateString();
                        }
                        
                        $series_data[] = [
                                'name' => $agent_res->agent_Name,
                                'data' => $agent_booking_data
                            ];
                    }
                }
                $categories_data = ['1st Week','2nd Week','3rd Week','4th Week','5th Week'];
            }
            
            // Generate Graph Data For Year
            if($request_data->date_type == 'data_year_wise'){
                
                $currentYear = date('Y');
                $monthsData = [];
                
                for ($month = 1; $month <= 12; $month++) {
                    
                     $startOfMonth = Carbon::create($currentYear, $month, 1)->startOfMonth();
                     $endOfMonth = Carbon::create($currentYear, $month, 1)->endOfMonth();
                     
                      $categories_data[] = $startOfMonth->format('F');
                     
                     $startOfMonth = $startOfMonth->format('Y-m-d');
                     $endOfMonth = $endOfMonth->format('Y-m-d');
    
                    $monthsData[] = (Object)[
                        'month' => $month,
                        'start_date' => $startOfMonth,
                        'end_date' => $endOfMonth,
                    ];
                }
                if(isset($limitedAgentData) && $limitedAgentData != null && $limitedAgentData != ''){
                    foreach($limitedAgentData as $agent_res){
                        
                   
                        
                        $agent_booking_data = [];
                        foreach($monthsData as $month_res){
                            // Add 7 days to the start date
                            
                            $agentsInvoices = DB::table('add_manage_invoices')->where('agent_Id',$agent_res->agent_id)->where('SU_id',$request->SU_id)
                                                                              ->whereDate('add_manage_invoices.created_at','>=', $month_res->start_date)
                                                                              ->whereDate('add_manage_invoices.created_at','<=', $month_res->end_date)
                                                                              ->Sum('total_sale_price_all_Services');
        
                            $agentsPackageBookings =  DB::table('cart_details')->where('agent_name',$agent_res->agent_id)->where('SU_id',$request->SU_id)
                                                                      ->whereDate('cart_details.created_at','>=', $month_res->start_date)
                                                                      ->whereDate('cart_details.created_at','<=', $month_res->end_date)
                                                                      ->Sum('tour_total_price');
                                                                       
                            $total_booking_price = $agentsInvoices + $agentsPackageBookings;
                                            
                          
                            
                            $agent_booking_data[] = floor($total_booking_price * 100) / 100;
                           
                        }
                        
                        $series_data[] = [
                                'name' => $agent_res->agent_Name,
                                'data' => $agent_booking_data
                            ];
                    }
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
            // dd($agentallBookingsObject);
            
            $agentallBookingsObject = new Collection($agentallBookingsObject);
            
            $agentallBookingsObject = $agentallBookingsObject->sortByDesc('all_bookings');
            
            // Reindex the collection starting from 0
            $agentallBookingsObject = $agentallBookingsObject->values();
            
            $agentallBookingsObject = $agentallBookingsObject->toArray();
            
            if(sizeOf($agentallBookingsObject) >= 4){
                $limitedAgentData = array_slice($agentallBookingsObject, 0, 4);
            }else{
                $limitedAgentData = $agentallBookingsObject;
            }
            
            // dd($limitedAgentData);
            $series_data        = [];
            $categories_data    = [];
            
            // Generate Graph Data All
            if($request_data->date_type == 'all_data'){
                $currentYear = date('Y');
                $monthsData = [];
            
                for ($month = 1; $month <= 12; $month++) {
                    
                     $startOfMonth = Carbon::create($currentYear, $month, 1)->startOfMonth();
                     $endOfMonth = Carbon::create($currentYear, $month, 1)->endOfMonth();
                     
                      $categories_data[] = $startOfMonth->format('F');
                     
                     $startOfMonth = $startOfMonth->format('Y-m-d');
                     $endOfMonth = $endOfMonth->format('Y-m-d');
    
                    $monthsData[] = (Object)[
                        'month' => $month,
                        'start_date' => $startOfMonth,
                        'end_date' => $endOfMonth,
                    ];
                }
                if(isset($limitedAgentData) && $limitedAgentData != null && $limitedAgentData != ''){
                foreach($limitedAgentData as $agent_res){
                    
               
                    
                    $agent_booking_data = [];
                    foreach($monthsData as $month_res){
                        // Add 7 days to the start date
                        
                        $agentsInvoices = DB::table('add_manage_invoices')->where('agent_Id',$agent_res->agent_id)->where('SU_id',$request->SU_id)
                                                                          ->whereDate('add_manage_invoices.created_at','>=', $month_res->start_date)
                                                                          ->whereDate('add_manage_invoices.created_at','<=', $month_res->end_date)
                                                                          ->count();

                        $agentsPackageBookings =  DB::table('cart_details')->where('agent_name',$agent_res->agent_id)->where('SU_id',$request->SU_id)
                                                                  ->whereDate('cart_details.created_at','>=', $month_res->start_date)
                                                                  ->whereDate('cart_details.created_at','<=', $month_res->end_date)
                                                                  ->count();
                                                                   
                        $total_booking_price = $agentsInvoices + $agentsPackageBookings;
                                        
                      
                        
                        $agent_booking_data[] = floor($total_booking_price * 100) / 100;
                       
                    }
                    
                    $series_data[] = [
                            'name' => $agent_res->agent_Name,
                            'data' => $agent_booking_data
                        ];
                }
                }
            }
            
            // Generate Graph Data YesterDay
            if($request_data->date_type == 'data_today_wise'){
            
                $date = date('Y-m-d');
                if(isset($limitedAgentData) && $limitedAgentData != null && $limitedAgentData != ''){
                foreach($limitedAgentData as $agent_res){
                    
                    $agent_booking_data = [$agent_res->all_bookings];
                    $series_data[] = [
                            'name' => $agent_res->agent_Name,
                            'data' => $agent_booking_data
                        ];
                }
                }
                $categories_data = [$date];
            }
            
            // Generate Graph Data YesterDay
            if($request_data->date_type == 'data_Yesterday_wise'){
            
                $date = date('Y-m-d',strtotime("-1 days"));
                if(isset($limitedAgentData) && $limitedAgentData != null && $limitedAgentData != ''){
                foreach($limitedAgentData as $agent_res){
                    $agent_booking_data = [$agent_res->all_bookings];
                    $series_data[] = [
                            'name' => $agent_res->agent_Name,
                            'data' => $agent_booking_data
                        ];
                }
                }
                $categories_data = [$date];
            }
            
            // Generate Graph Data For Week
            if($request_data->date_type == 'data_week_wise'){
               $today = Carbon::now();
                
                // Create a CarbonPeriod instance for the current week
               $startOfWeek = $today->startOfWeek()->toDateString();
                $endOfWeek = $today->endOfWeek()->toDateString();
                
                // Create a CarbonPeriod instance for the current week
                $period = CarbonPeriod::create($startOfWeek, $endOfWeek);
                
                // Get the dates of the current week as an array
                $datesOfWeek = [];
                foreach ($period as $date) {
                    $datesOfWeek[] = $date->toDateString();
                }
                
                // Get Data for Agent 
                if(isset($limitedAgentData) && $limitedAgentData != null && $limitedAgentData != ''){
                foreach($limitedAgentData as $agent_res){
                    
                    // Loop On DatesOfWeek
                    
                    $agent_booking_data = [];
                    foreach($datesOfWeek as $date_res){
                        $agentsInvoices = DB::table('add_manage_invoices')->where('agent_Id',$agent_res->agent_id)->where('SU_id',$request->SU_id)
                                                                          ->whereDate('created_at',$date_res)
                                                                          ->count();

                        $agentsPackageBookings =  DB::table('cart_details')->where('agent_name',$agent_res->agent_id)->where('SU_id',$request->SU_id)
                                                                  ->whereDate('created_at',$date_res)
                                                                  ->count();
                                                                   
                        $total_booking_price = $agentsInvoices + $agentsPackageBookings;
                                        
                      
                        
                        $agent_booking_data[] = $total_booking_price;
                    }
                    
                    $series_data[] = [
                            'name' => $agent_res->agent_Name,
                            'data' => $agent_booking_data
                        ];
                }
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
                if(isset($limitedAgentData) && $limitedAgentData != null && $limitedAgentData != ''){
                foreach($limitedAgentData as $agent_res){
                    
                    // Start Date Of Month
                    // $currentDate = Carbon::now();
                    $startDate = Carbon::now()->startOfMonth();
                    // $startDate = $currentDate->subMonth()->firstOfMonth();
                    $startDateWeek = $startDate->toDateString();
                    $endDate = $startDate->copy()->addDays(6);
                    $endDateWeek = $endDate->toDateString();
                    
                    $agent_booking_data = [];
                    for($i=1; $i<=5; $i++){
                        // Add 7 days to the start date
                        
                        $agentsInvoices = DB::table('add_manage_invoices')->where('agent_Id',$agent_res->agent_id)->where('SU_id',$request->SU_id)
                                                                          ->whereDate('add_manage_invoices.created_at','>=', $startDate)
                                                                          ->whereDate('add_manage_invoices.created_at','<=', $endDate)
                                                                          ->count();

                        $agentsPackageBookings =  DB::table('cart_details')->where('agent_name',$agent_res->agent_id)->where('SU_id',$request->SU_id)
                                                                  ->whereDate('cart_details.created_at','>=', $startDate)
                                                                  ->whereDate('cart_details.created_at','<=', $endDate)
                                                                  ->count();
                                                                   
                        $total_booking_price = $agentsInvoices + $agentsPackageBookings;
                                        
                      
                        
                        $agent_booking_data[] = $total_booking_price;
                        
                        
                        $startDate = $endDate->copy()->addDays(1);
                        if($i == '4'){
                            $endDate = $startDate->copy()->addDays(2);
                        }else{
                             $endDate = $startDate->copy()->addDays(6);
                        }
                        
                        $startDateWeek = $startDate->toDateString();
                        $endDateWeek = $endDate->toDateString();
                    }
                    
                    $series_data[] = [
                            'name' => $agent_res->agent_Name,
                            'data' => $agent_booking_data
                        ];
                }
                }  
               
                    // dd($startDateWeek,$endDateWeek);
                //   print_r($weekDates);
                //   die;
                $categories_data = ['1st Week','2nd Week','3rd Week','4th Week','5th Week'];
            }
            
            // Generate Graph Data For Year
            if($request_data->date_type == 'data_year_wise'){
                
                $currentYear = date('Y');
                $monthsData = [];
                
                for ($month = 1; $month <= 12; $month++) {
                    
                     $startOfMonth = Carbon::create($currentYear, $month, 1)->startOfMonth();
                     $endOfMonth = Carbon::create($currentYear, $month, 1)->endOfMonth();
                     
                      $categories_data[] = $startOfMonth->format('F');
                     
                     $startOfMonth = $startOfMonth->format('Y-m-d');
                     $endOfMonth = $endOfMonth->format('Y-m-d');
    
                    $monthsData[] = (Object)[
                        'month' => $month,
                        'start_date' => $startOfMonth,
                        'end_date' => $endOfMonth,
                    ];
                }
                if(isset($limitedAgentData) && $limitedAgentData != null && $limitedAgentData != ''){
                foreach($limitedAgentData as $agent_res){
                    
               
                    
                    $agent_booking_data = [];
                    foreach($monthsData as $month_res){
                        // Add 7 days to the start date
                        
                        $agentsInvoices = DB::table('add_manage_invoices')->where('agent_Id',$agent_res->agent_id)->where('SU_id',$request->SU_id)
                                                                          ->whereDate('add_manage_invoices.created_at','>=', $month_res->start_date)
                                                                          ->whereDate('add_manage_invoices.created_at','<=', $month_res->end_date)
                                                                          ->count();

                        $agentsPackageBookings =  DB::table('cart_details')->where('agent_name',$agent_res->agent_id)->where('SU_id',$request->SU_id)
                                                                  ->whereDate('cart_details.created_at','>=', $month_res->start_date)
                                                                  ->whereDate('cart_details.created_at','<=', $month_res->end_date)
                                                                  ->count();
                                                                   
                        $total_booking_price = $agentsInvoices + $agentsPackageBookings;
                                        
                      
                        
                        $agent_booking_data[] = floor($total_booking_price * 100) / 100;
                       
                    }
                    
                    $series_data[] = [
                            'name' => $agent_res->agent_Name,
                            'data' => $agent_booking_data
                        ];
                }
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
    
    public function booking_filter_Season_Working($all_data,$request,$request_data){
        $today_Date             = date('Y-m-d');
        // dd($request_data->season_Id);
        if(isset($request_data->season_Id)){
            if($request_data->season_Id == 'all_Seasons'){
                return $all_data;
            }elseif($request_data->season_Id > 0){
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
    
    public function agent_booking_filter(Request $request){
        $userData               = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        $request_data           = json_decode($request->request_data);
        
        $agentallBookingsObject = [];
        $agent_Groups           = [];
        
        if($request_data->date_type == 'all_data'){
            $agentsInvoices         = DB::table('Agents_detail')
                                        ->leftJoin('add_manage_invoices','add_manage_invoices.agent_Id','Agents_detail.id')
                                        ->where('Agents_detail.customer_id',$userData->id)
                                        // ->select('Agents_detail.id', 'Agents_detail.agent_Name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                                        ->select('Agents_detail.created_at','Agents_detail.id','Agents_detail.agent_Refrence_No', 'Agents_detail.agent_Name','Agents_detail.company_name','Agents_detail.balance','Agents_detail.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_Company) as Invoices_prices_sum'))
                                        ->groupBy('Agents_detail.id')
                                        ->orderBy('Agents_detail.id','asc')
                                        ->get();
            foreach($agentsInvoices as $val_AID){
                $booked_GD  = DB::table('addGroupsdetails')
                                ->where('group_Client_Prefix',$val_AID->agent_Refrence_No)
                                ->select('group_Name','total_Invoices','group_Passport_Count','group_Client_Prefix')
                                ->get();
                                
                if($booked_GD->isEmpty()){
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => '',
                    ];
                    array_push($agent_Groups,$data_AG);
                }else{
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => $booked_GD,
                    ];
                    array_push($agent_Groups,$data_AG);
                }
            }
            
            $agentsPackageBookings  = DB::table('Agents_detail')
                                        ->leftJoin('cart_details','cart_details.agent_name','Agents_detail.id')
                                        ->where('Agents_detail.customer_id',$userData->id)
                                        ->select('Agents_detail.created_at','Agents_detail.id', 'Agents_detail.agent_Name','Agents_detail.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                                        ->groupBy('Agents_detail.id')
                                        ->orderBy('Agents_detail.id','asc')
                                        ->get();
            foreach($agentsInvoices as $index => $invoice_res){
                $total_prices = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                $agentallBookingsObject[] = (Object)[
                        'agent_id' => $invoice_res->id,
                        'agent_Name' => $invoice_res->agent_Name,
                        'company_name' => $invoice_res->company_name,
                        'currency' =>  $invoice_res->currency,
                        'Invoices_booking' => $invoice_res->Invoices_booking,
                        'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                        'packages_booking' => $agentsPackageBookings[$index]->packages_booking,
                        'packages_prices_sum' => number_format($agentsPackageBookings[$index]->packages_prices_sum,2),
                        'all_bookings' => $invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking,
                        'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking),
                        'total_prices' => $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,
                        'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,2),
                        'Paid' => number_format($total_prices - $invoice_res->balance,2),
                        'remaining' => number_format($invoice_res->balance,2),
                        'created_at' => $invoice_res->created_at,
                    ]; 
            }
        }
        
        if($request_data->date_type == 'data_today_wise'){
            $today_date     = date('Y-m-d');
            
            $agentsInvoices = DB::table('Agents_detail')
                                ->leftJoin('add_manage_invoices', function ($join) use($today_date) {
                                    $join->on('add_manage_invoices.agent_Id', '=', 'Agents_detail.id')
                                        ->whereDate('add_manage_invoices.created_at', $today_date);
                                })
                                ->where('Agents_detail.customer_id',$userData->id)
                                // ->select('Agents_detail.id', 'Agents_detail.agent_Name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                                ->select('Agents_detail.created_at','Agents_detail.id','Agents_detail.agent_Refrence_No', 'Agents_detail.agent_Name','Agents_detail.company_name','Agents_detail.balance','Agents_detail.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_Company) as Invoices_prices_sum'))
                                ->groupBy('Agents_detail.id')
                                ->orderBy('Agents_detail.id','asc')
                                ->get();
            
            foreach($agentsInvoices as $val_AID){
                $booked_GD  = DB::table('addGroupsdetails')
                                ->where('group_Client_Prefix',$val_AID->agent_Refrence_No)
                                ->select('group_Name','total_Invoices','group_Passport_Count','group_Client_Prefix')
                                ->get();
                                
                if($booked_GD->isEmpty()){
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => '',
                    ];
                    array_push($agent_Groups,$data_AG);
                }else{
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => $booked_GD,
                    ];
                    array_push($agent_Groups,$data_AG);
                }
            }
            
            $agentsPackageBookings = DB::table('Agents_detail')
                                ->leftJoin('cart_details', function ($join) use($today_date) {
                                    $join->on('cart_details.agent_name', '=', 'Agents_detail.id')
                                        ->whereDate('cart_details.created_at', $today_date);
                                    })
                                ->where('Agents_detail.customer_id',$userData->id)
                                ->select('Agents_detail.created_at','Agents_detail.id', 'Agents_detail.agent_Name','Agents_detail.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                                ->groupBy('Agents_detail.id')
                                ->orderBy('Agents_detail.id','asc')
                                ->get();
                                
                
                // print_r($agentsInvoices);
                // print_r($agentsPackageBookings);
                // die;
                foreach($agentsInvoices as $index => $invoice_res){
                    $total_prices = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                    
                    $payment_today = DB::table('agents_ledgers_new')->where('agent_id',$invoice_res->id)
                                                   ->whereDate('date',$today_date)
                                                  ->where('received_id','!=',NULL)
                                                   ->sum('payment');
                    $total_price = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                    
                    $agentallBookingsObject[] = (Object)[
                            'agent_id' => $invoice_res->id,
                            'agent_Name' => $invoice_res->agent_Name,
                            'company_name' => $invoice_res->company_name,
                            'currency' =>  $invoice_res->currency,
                            'Invoices_booking' => $invoice_res->Invoices_booking,
                            'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                            'packages_booking' => $agentsPackageBookings[$index]->packages_booking,
                            'packages_prices_sum' => number_format($agentsPackageBookings[$index]->packages_prices_sum,2),
                            'all_bookings' => $invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking,
                            'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking),
                            'total_prices' => $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,
                            'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,2),
                            'Paid' => number_format($payment_today,2),
                            'remaining' => number_format($total_price - $payment_today,2),
                            'created_at' => $invoice_res->created_at,
                        ];
                }
        }
        
        if($request_data->date_type == 'data_Yesterday_wise'){
            
            $date = date('Y-m-d',strtotime("-1 days"));
            //  dd($today_date);
             $agentsInvoices = DB::table('Agents_detail')
                                ->leftJoin('add_manage_invoices', function ($join) use($date) {
                                                    $join->on('add_manage_invoices.agent_Id', '=', 'Agents_detail.id')
                                                        ->whereDate('add_manage_invoices.created_at', $date);
                                                })
                                ->where('Agents_detail.customer_id',$userData->id)
                                
                                // ->select('Agents_detail.id', 'Agents_detail.agent_Name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                                ->select('Agents_detail.created_at','Agents_detail.id','Agents_detail.agent_Refrence_No', 'Agents_detail.agent_Name','Agents_detail.company_name','Agents_detail.balance','Agents_detail.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_Company) as Invoices_prices_sum'))
                                ->groupBy('Agents_detail.id')
                                ->orderBy('Agents_detail.id','asc')
                                ->get();
            
            foreach($agentsInvoices as $val_AID){
                $booked_GD  = DB::table('addGroupsdetails')
                                ->where('group_Client_Prefix',$val_AID->agent_Refrence_No)
                                ->select('group_Name','total_Invoices','group_Passport_Count','group_Client_Prefix')
                                ->get();
                                
                if($booked_GD->isEmpty()){
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => '',
                    ];
                    array_push($agent_Groups,$data_AG);
                }else{
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => $booked_GD,
                    ];
                    array_push($agent_Groups,$data_AG);
                }
            }
            
                $agentsPackageBookings = DB::table('Agents_detail')
                                ->leftJoin('cart_details', function ($join) use($date) {
                                                    $join->on('cart_details.agent_name', '=', 'Agents_detail.id')
                                                        ->whereDate('cart_details.created_at', $date);
                                                })
                                ->where('Agents_detail.customer_id',$userData->id)
                                ->select('Agents_detail.created_at','Agents_detail.id', 'Agents_detail.agent_Name','Agents_detail.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                                ->groupBy('Agents_detail.id')
                                ->orderBy('Agents_detail.id','asc')
                                ->get();
                                
                
                // print_r($agentsInvoices);
                // print_r($agentsPackageBookings);
                // die;
                foreach($agentsInvoices as $index => $invoice_res){
                    $total_prices = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                    
                    $payment_today = DB::table('agents_ledgers_new')->where('agent_id',$invoice_res->id)
                                                   ->whereDate('date',$date)
                                                  ->where('received_id','!=',NULL)
                                                   ->sum('payment');
                    $total_price = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                    
                    $agentallBookingsObject[] = (Object)[
                            'agent_id' => $invoice_res->id,
                            'agent_Name' => $invoice_res->agent_Name,
                            'company_name' => $invoice_res->company_name,
                            'currency' =>  $invoice_res->currency,
                            'Invoices_booking' => $invoice_res->Invoices_booking,
                            'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                            'packages_booking' => $agentsPackageBookings[$index]->packages_booking,
                            'packages_prices_sum' => number_format($agentsPackageBookings[$index]->packages_prices_sum,2),
                            'all_bookings' => $invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking,
                            'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking),
                            'total_prices' => $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,
                            'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,2),
                            'Paid' => number_format($payment_today,2),
                            'remaining' => number_format($total_price - $payment_today,2),
                            'created_at' => $invoice_res->created_at,
                        ];
                }
        }
        
        if($request_data->date_type == 'data_week_wise'){
            $startOfWeek    = Carbon::now()->startOfWeek();
            $start_date     = $startOfWeek->format('Y-m-d');
            $end_date       = date('Y-m-d');
            
            //  dd($today_date);
            $agentsInvoices     = DB::table('Agents_detail')
                                    ->leftJoin('add_manage_invoices', function ($join) use($start_date,$end_date) {
                                        $join->on('add_manage_invoices.agent_Id', '=', 'Agents_detail.id')
                                            ->whereDate('add_manage_invoices.created_at','>=', $start_date)
                                            ->whereDate('add_manage_invoices.created_at','<=', $end_date);
                                        })
                                    ->where('Agents_detail.customer_id',$userData->id)
                                    // ->select('Agents_detail.id', 'Agents_detail.agent_Name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                                    ->select('Agents_detail.created_at','Agents_detail.id','Agents_detail.agent_Refrence_No', 'Agents_detail.agent_Name','Agents_detail.company_name','Agents_detail.balance','Agents_detail.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_Company) as Invoices_prices_sum'))
                                    ->groupBy('Agents_detail.id')
                                    ->orderBy('Agents_detail.id','asc')
                                    ->get();
            foreach($agentsInvoices as $val_AID){
                $booked_GD  = DB::table('addGroupsdetails')
                                ->where('group_Client_Prefix',$val_AID->agent_Refrence_No)
                                ->select('group_Name','total_Invoices','group_Passport_Count','group_Client_Prefix')
                                ->get();
                                
                if($booked_GD->isEmpty()){
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => '',
                    ];
                    array_push($agent_Groups,$data_AG);
                }else{
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => $booked_GD,
                    ];
                    array_push($agent_Groups,$data_AG);
                }
            }
                
            $agentsPackageBookings = DB::table('Agents_detail')
                                        ->leftJoin('cart_details', function ($join) use($start_date,$end_date) {
                                            $join->on('cart_details.agent_name', '=', 'Agents_detail.id')
                                                ->whereDate('cart_details.created_at','>=', $start_date)
                                                ->whereDate('cart_details.created_at','<=', $end_date);
                                            })
                                        ->where('Agents_detail.customer_id',$userData->id)
                                        ->select('Agents_detail.created_at','Agents_detail.id', 'Agents_detail.agent_Name','Agents_detail.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                                        ->groupBy('Agents_detail.id')
                                        ->orderBy('Agents_detail.id','asc')
                                        ->get();
            foreach($agentsInvoices as $index => $invoice_res){
                    $total_prices = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                    
                    $payment_today = DB::table('agents_ledgers_new')->where('agent_id',$invoice_res->id)
                                                   ->whereDate('date','>=', $start_date)
                                                   ->whereDate('date','<=', $end_date)
                                                   ->where('received_id','!=',NULL)
                                                   ->sum('payment');
                    $total_price = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                    
                    $agentallBookingsObject[] = (Object)[
                            'agent_id' => $invoice_res->id,
                            'agent_Name' => $invoice_res->agent_Name,
                            'company_name' => $invoice_res->company_name,
                            'currency' =>  $invoice_res->currency,
                            'Invoices_booking' => $invoice_res->Invoices_booking,
                            'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                            'packages_booking' => $agentsPackageBookings[$index]->packages_booking,
                            'packages_prices_sum' => number_format($agentsPackageBookings[$index]->packages_prices_sum,2),
                            'all_bookings' => $invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking,
                            'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking),
                            'total_prices' => $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,
                            'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,2),
                            'Paid' => number_format($payment_today,2),
                            'remaining' => number_format($total_price - $payment_today,2),
                            'created_at' => $invoice_res->created_at,
                        ];
                }
        }
        
        if($request_data->date_type == 'data_month_wise'){
            
            $startOfMonth = Carbon::now()->startOfMonth();
            $start_date = $startOfMonth->format('Y-m-d');
            $end_date = date('Y-m-d');
            
            //  dd($today_date);
            $agentsInvoices = DB::table('Agents_detail')
                                ->leftJoin('add_manage_invoices', function ($join) use($start_date,$end_date) {
                                                    $join->on('add_manage_invoices.agent_Id', '=', 'Agents_detail.id')
                                                        ->whereDate('add_manage_invoices.created_at','>=', $start_date)
                                                        ->whereDate('add_manage_invoices.created_at','<=', $end_date);
                                                })
                                ->where('Agents_detail.customer_id',$userData->id)
                                
                                // ->select('Agents_detail.id', 'Agents_detail.agent_Name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                                ->select('Agents_detail.created_at','Agents_detail.id','Agents_detail.agent_Refrence_No', 'Agents_detail.agent_Name','Agents_detail.company_name','Agents_detail.balance','Agents_detail.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_Company) as Invoices_prices_sum'))
                                ->groupBy('Agents_detail.id')
                                ->orderBy('Agents_detail.id','asc')
                                ->get();
            foreach($agentsInvoices as $val_AID){
                $booked_GD  = DB::table('addGroupsdetails')
                                ->where('group_Client_Prefix',$val_AID->agent_Refrence_No)
                                ->select('group_Name','total_Invoices','group_Passport_Count','group_Client_Prefix')
                                ->get();
                                
                if($booked_GD->isEmpty()){
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => '',
                    ];
                    array_push($agent_Groups,$data_AG);
                }else{
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => $booked_GD,
                    ];
                    array_push($agent_Groups,$data_AG);
                }
            }
            
            $agentsPackageBookings = DB::table('Agents_detail')
                                ->leftJoin('cart_details', function ($join) use($start_date,$end_date) {
                                                    $join->on('cart_details.agent_name', '=', 'Agents_detail.id')
                                                        ->whereDate('cart_details.created_at','>=', $start_date)
                                                        ->whereDate('cart_details.created_at','<=', $end_date);
                                                })
                                ->where('Agents_detail.customer_id',$userData->id)
                                ->select('Agents_detail.created_at','Agents_detail.id', 'Agents_detail.agent_Name','Agents_detail.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                                ->groupBy('Agents_detail.id')
                                ->orderBy('Agents_detail.id','asc')
                                ->get();
                                
                
                // print_r($agentsInvoices);
                // print_r($agentsPackageBookings);
                // die;
                foreach($agentsInvoices as $index => $invoice_res){
                    $total_prices = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                    
                    $payment_today = DB::table('agents_ledgers_new')->where('agent_id',$invoice_res->id)
                                                   ->whereDate('date','>=', $start_date)
                                                   ->whereDate('date','<=', $end_date)
                                                   ->where('received_id','!=',NULL)
                                                   ->sum('payment');
                    $total_price = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                    
                    $agentallBookingsObject[] = (Object)[
                            'agent_id' => $invoice_res->id,
                            'agent_Name' => $invoice_res->agent_Name,
                            'company_name' => $invoice_res->company_name,
                            'currency' =>  $invoice_res->currency,
                            'Invoices_booking' => $invoice_res->Invoices_booking,
                            'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                            'packages_booking' => $agentsPackageBookings[$index]->packages_booking,
                            'packages_prices_sum' => number_format($agentsPackageBookings[$index]->packages_prices_sum,2),
                            'all_bookings' => $invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking,
                            'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking),
                            'total_prices' => $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,
                            'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,2),
                            'Paid' => number_format($payment_today,2),
                            'remaining' => number_format($total_price - $payment_today,2),
                            'created_at' => $invoice_res->created_at,
                        ];
                }
        }
        
        if($request_data->date_type == 'data_year_wise'){
            
            $startOfYear = Carbon::now()->startOfYear();
            $start_date = $startOfYear->format('Y-m-d');
            $end_date = date('Y-m-d');
            
            //  dd($today_date);
             $agentsInvoices = DB::table('Agents_detail')
                                ->leftJoin('add_manage_invoices', function ($join) use($start_date,$end_date) {
                                                    $join->on('add_manage_invoices.agent_Id', '=', 'Agents_detail.id')
                                                        ->whereDate('add_manage_invoices.created_at','>=', $start_date)
                                                        ->whereDate('add_manage_invoices.created_at','<=', $end_date);
                                                })
                                ->where('Agents_detail.customer_id',$userData->id)
                                
                                // ->select('Agents_detail.id', 'Agents_detail.agent_Name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                                ->select('Agents_detail.created_at','Agents_detail.id','Agents_detail.agent_Refrence_No', 'Agents_detail.agent_Name','Agents_detail.company_name','Agents_detail.balance','Agents_detail.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_Company) as Invoices_prices_sum'))
                                ->groupBy('Agents_detail.id')
                                ->orderBy('Agents_detail.id','asc')
                                ->get();
            
            foreach($agentsInvoices as $val_AID){
                $booked_GD  = DB::table('addGroupsdetails')
                                ->where('group_Client_Prefix',$val_AID->agent_Refrence_No)
                                ->select('group_Name','total_Invoices','group_Passport_Count','group_Client_Prefix')
                                ->get();
                                
                if($booked_GD->isEmpty()){
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => '',
                    ];
                    array_push($agent_Groups,$data_AG);
                }else{
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => $booked_GD,
                    ];
                    array_push($agent_Groups,$data_AG);
                }
            }
            
            $agentsPackageBookings = DB::table('Agents_detail')
                                ->leftJoin('cart_details', function ($join) use($start_date,$end_date) {
                                                    $join->on('cart_details.agent_name', '=', 'Agents_detail.id')
                                                        ->whereDate('cart_details.created_at','>=', $start_date)
                                                        ->whereDate('cart_details.created_at','<=', $end_date);
                                                })
                                ->where('Agents_detail.customer_id',$userData->id)
                                ->select('Agents_detail.created_at','Agents_detail.id', 'Agents_detail.agent_Name','Agents_detail.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                                ->groupBy('Agents_detail.id')
                                ->orderBy('Agents_detail.id','asc')
                                ->get();
                                
                
                // print_r($agentsInvoices);
                // print_r($agentsPackageBookings);
                // die;
                foreach($agentsInvoices as $index => $invoice_res){
                    $total_prices = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                    
                    $payment_today = DB::table('agents_ledgers_new')->where('agent_id',$invoice_res->id)
                                                   ->whereDate('date','>=', $start_date)
                                                   ->whereDate('date','<=', $end_date)
                                                   ->where('received_id','!=',NULL)
                                                   ->sum('payment');
                    $total_price = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                    
                    $agentallBookingsObject[] = (Object)[
                            'agent_id' => $invoice_res->id,
                            'agent_Name' => $invoice_res->agent_Name,
                            'company_name' => $invoice_res->company_name,
                            'currency' =>  $invoice_res->currency,
                            'Invoices_booking' => $invoice_res->Invoices_booking,
                            'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                            'packages_booking' => $agentsPackageBookings[$index]->packages_booking,
                            'packages_prices_sum' => number_format($agentsPackageBookings[$index]->packages_prices_sum,2),
                            'all_bookings' => $invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking,
                            'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking),
                            'total_prices' => $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,
                            'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,2),
                            'Paid' => number_format($payment_today,2),
                            'remaining' => number_format($total_price - $payment_today,2),
                            'created_at' => $invoice_res->created_at,
                        ];
                }
        }
        
        if($request_data->date_type == 'data_wise'){
            
            $start_date = $request_data->start_date;
            $end_date   = $request_data->end_date;
            
            $agentsInvoices = DB::table('Agents_detail')
                                ->leftJoin('add_manage_invoices', function ($join) use($start_date,$end_date) {
                                                    $join->on('add_manage_invoices.agent_Id', '=', 'Agents_detail.id')
                                                        ->whereDate('add_manage_invoices.created_at','>=', $start_date)
                                                        ->whereDate('add_manage_invoices.created_at','<=', $end_date);
                                                })
                                ->where('Agents_detail.customer_id',$userData->id)
                                
                                // ->select('Agents_detail.id', 'Agents_detail.agent_Name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                                ->select('Agents_detail.created_at','Agents_detail.id','Agents_detail.agent_Refrence_No', 'Agents_detail.agent_Name','Agents_detail.company_name','Agents_detail.balance','Agents_detail.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_Company) as Invoices_prices_sum'))
                                ->groupBy('Agents_detail.id')
                                ->orderBy('Agents_detail.id','asc')
                                ->get();
            foreach($agentsInvoices as $val_AID){
                $booked_GD  = DB::table('addGroupsdetails')
                                ->where('group_Client_Prefix',$val_AID->agent_Refrence_No)
                                ->select('group_Name','total_Invoices','group_Passport_Count','group_Client_Prefix')
                                ->get();
                                
                if($booked_GD->isEmpty()){
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => '',
                    ];
                    array_push($agent_Groups,$data_AG);
                }else{
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => $booked_GD,
                    ];
                    array_push($agent_Groups,$data_AG);
                }
            }    
            $agentsPackageBookings = DB::table('Agents_detail')
                                ->leftJoin('cart_details', function ($join) use($start_date,$end_date) {
                                                    $join->on('cart_details.agent_name', '=', 'Agents_detail.id')
                                                        ->whereDate('cart_details.created_at','>=', $start_date)
                                                        ->whereDate('cart_details.created_at','<=', $end_date);
                                                })
                                ->where('Agents_detail.customer_id',$userData->id)
                                ->select('Agents_detail.created_at','Agents_detail.id', 'Agents_detail.agent_Name','Agents_detail.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                                ->groupBy('Agents_detail.id')
                                ->orderBy('Agents_detail.id','asc')
                                ->get();
                foreach($agentsInvoices as $index => $invoice_res){
                    $total_prices = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                    
                    $payment_today = DB::table('agents_ledgers_new')->where('agent_id',$invoice_res->id)
                                                   ->whereDate('date','>=', $start_date)
                                                   ->whereDate('date','<=', $end_date)
                                                   ->where('received_id','!=',NULL)
                                                   ->sum('payment');
                    $total_price = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                    
                    $agentallBookingsObject[] = (Object)[
                            'agent_id' => $invoice_res->id,
                            'agent_Name' => $invoice_res->agent_Name,
                            'company_name' => $invoice_res->company_name,
                            'currency' =>  $invoice_res->currency,
                            'Invoices_booking' => $invoice_res->Invoices_booking,
                            'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                            'packages_booking' => $agentsPackageBookings[$index]->packages_booking,
                            'packages_prices_sum' => number_format($agentsPackageBookings[$index]->packages_prices_sum,2),
                            'all_bookings' => $invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking,
                            'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking),
                            'total_prices' => $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,
                            'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,2),
                            'Paid' => number_format($payment_today,2),
                            'remaining' => number_format($total_price - $payment_today,2),
                            'created_at' => $invoice_res->created_at,
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
                $limitedAgentData = $limitedAgentData;
            }
            
            $series_data = [];
            $categories_data = [];
            
            // Generate Graph Data All
            if($request_data->date_type == 'all_data'){
                $currentYear = date('Y');
                $monthsData = [];
            
                for ($month = 1; $month <= 12; $month++) {
                    
                     $startOfMonth = Carbon::create($currentYear, $month, 1)->startOfMonth();
                     $endOfMonth = Carbon::create($currentYear, $month, 1)->endOfMonth();
                     
                      $categories_data[] = $startOfMonth->format('F');
                     
                     $startOfMonth = $startOfMonth->format('Y-m-d');
                     $endOfMonth = $endOfMonth->format('Y-m-d');
    
                    $monthsData[] = (Object)[
                        'month' => $month,
                        'start_date' => $startOfMonth,
                        'end_date' => $endOfMonth,
                    ];
                }
                
                foreach($limitedAgentData as $agent_res){
                    
               
                    
                    $agent_booking_data = [];
                    foreach($monthsData as $month_res){
                        // Add 7 days to the start date
                        
                        $agentsInvoices = DB::table('add_manage_invoices')->where('agent_Id',$agent_res->agent_id)
                                                                          ->whereDate('add_manage_invoices.created_at','>=', $month_res->start_date)
                                                                          ->whereDate('add_manage_invoices.created_at','<=', $month_res->end_date)
                                                                          ->Sum('total_sale_price_all_Services');

                        $agentsPackageBookings =  DB::table('cart_details')->where('agent_name',$agent_res->agent_id)
                                                                  ->whereDate('cart_details.created_at','>=', $month_res->start_date)
                                                                  ->whereDate('cart_details.created_at','<=', $month_res->end_date)
                                                                  ->Sum('tour_total_price');
                                                                   
                        $total_booking_price = $agentsInvoices + $agentsPackageBookings;
                                        
                      
                        
                        $agent_booking_data[] = floor($total_booking_price * 100) / 100;
                       
                    }
                    
                    $series_data[] = [
                            'name' => $agent_res->agent_Name,
                            'data' => $agent_booking_data
                        ];
                }
            }
            
            // Generate Graph Data Today
            if($request_data->date_type == 'data_today_wise'){
            
                $date = date('Y-m-d');
                foreach($limitedAgentData as $agent_res){
                    
                    $agent_booking_data = [$agent_res->total_prices];
                    $series_data[] = [
                            'name' => $agent_res->agent_Name,
                            'data' => $agent_booking_data
                        ];
                }
                
                $categories_data = [$date];
            }
            
            // Generate Graph Data YesterDay
            if($request_data->date_type == 'data_Yesterday_wise'){
            
                $date = date('Y-m-d',strtotime("-1 days"));
                
                foreach($limitedAgentData as $agent_res){
                    $agent_booking_data = [$agent_res->total_prices];
                    $series_data[] = [
                            'name' => $agent_res->agent_Name,
                            'data' => $agent_booking_data
                        ];
                }
                
                $categories_data = [$date];
            }
            
            // Generate Graph Data For Week
            if($request_data->date_type == 'data_week_wise'){
               $today = Carbon::now();
                
                // Create a CarbonPeriod instance for the current week
               $startOfWeek = $today->startOfWeek()->toDateString();
                $endOfWeek = $today->endOfWeek()->toDateString();
                
                // Create a CarbonPeriod instance for the current week
                $period = CarbonPeriod::create($startOfWeek, $endOfWeek);
                
                // Get the dates of the current week as an array
                $datesOfWeek = [];
                foreach ($period as $date) {
                    $datesOfWeek[] = $date->toDateString();
                }
                
                // Get Data for Agent 
                
                foreach($limitedAgentData as $agent_res){
                    
                    // Loop On DatesOfWeek
                    
                    $agent_booking_data = [];
                    foreach($datesOfWeek as $date_res){
                        $agentsInvoices = DB::table('add_manage_invoices')->where('agent_Id',$agent_res->agent_id)
                                                                          ->whereDate('created_at',$date_res)
                                                                          ->Sum('total_sale_price_all_Services');

                        $agentsPackageBookings =  DB::table('cart_details')->where('agent_name',$agent_res->agent_id)
                                                                  ->whereDate('created_at',$date_res)
                                                                  ->Sum('tour_total_price');
                                                                   
                        $total_booking_price = $agentsInvoices + $agentsPackageBookings;
                                        
                      
                        
                        $agent_booking_data[] = $total_booking_price;
                    }
                    
                    $series_data[] = [
                            'name' => $agent_res->agent_Name,
                            'data' => $agent_booking_data
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
                    
                    // Start Date Of Month
                    // $currentDate = Carbon::now();
                    $startDate = Carbon::now()->startOfMonth();
                    // $startDate = $currentDate->subMonth()->firstOfMonth();
                    $startDateWeek = $startDate->toDateString();
                    $endDate = $startDate->copy()->addDays(6);
                    $endDateWeek = $endDate->toDateString();
                    
                    $agent_booking_data = [];
                    for($i=1; $i<=5; $i++){
                        // Add 7 days to the start date
                        
                        $agentsInvoices = DB::table('add_manage_invoices')->where('agent_Id',$agent_res->agent_id)
                                                                          ->whereDate('add_manage_invoices.created_at','>=', $startDate)
                                                                          ->whereDate('add_manage_invoices.created_at','<=', $endDate)
                                                                          ->Sum('total_sale_price_all_Services');

                        $agentsPackageBookings =  DB::table('cart_details')->where('agent_name',$agent_res->agent_id)
                                                                  ->whereDate('cart_details.created_at','>=', $startDate)
                                                                  ->whereDate('cart_details.created_at','<=', $endDate)
                                                                  ->Sum('tour_total_price');
                                                                   
                        $total_booking_price = $agentsInvoices + $agentsPackageBookings;
                                        
                      
                        
                        $agent_booking_data[] = $total_booking_price;
                        
                        
                        $startDate = $endDate->copy()->addDays(1);
                        if($i == '4'){
                            $endDate = $startDate->copy()->addDays(2);
                        }else{
                             $endDate = $startDate->copy()->addDays(6);
                        }
                        
                        $startDateWeek = $startDate->toDateString();
                        $endDateWeek = $endDate->toDateString();
                    }
                    
                    $series_data[] = [
                            'name' => $agent_res->agent_Name,
                            'data' => $agent_booking_data
                        ];
                }
                    
               
                    // dd($startDateWeek,$endDateWeek);
                //   print_r($weekDates);
                //   die;
                $categories_data = ['1st Week','2nd Week','3rd Week','4th Week','5th Week'];
            }
            
            // Generate Graph Data For Year
            if($request_data->date_type == 'data_year_wise'){
                
                $currentYear = date('Y');
                $monthsData = [];
            
                for ($month = 1; $month <= 12; $month++) {
                    
                     $startOfMonth = Carbon::create($currentYear, $month, 1)->startOfMonth();
                     $endOfMonth = Carbon::create($currentYear, $month, 1)->endOfMonth();
                     
                      $categories_data[] = $startOfMonth->format('F');
                     
                     $startOfMonth = $startOfMonth->format('Y-m-d');
                     $endOfMonth = $endOfMonth->format('Y-m-d');
    
                    $monthsData[] = (Object)[
                        'month' => $month,
                        'start_date' => $startOfMonth,
                        'end_date' => $endOfMonth,
                    ];
                }
                
                foreach($limitedAgentData as $agent_res){
                    
               
                    
                    $agent_booking_data = [];
                    foreach($monthsData as $month_res){
                        // Add 7 days to the start date
                        
                        $agentsInvoices = DB::table('add_manage_invoices')->where('agent_Id',$agent_res->agent_id)
                                                                          ->whereDate('add_manage_invoices.created_at','>=', $month_res->start_date)
                                                                          ->whereDate('add_manage_invoices.created_at','<=', $month_res->end_date)
                                                                          ->Sum('total_sale_price_all_Services');

                        $agentsPackageBookings =  DB::table('cart_details')->where('agent_name',$agent_res->agent_id)
                                                                  ->whereDate('cart_details.created_at','>=', $month_res->start_date)
                                                                  ->whereDate('cart_details.created_at','<=', $month_res->end_date)
                                                                  ->Sum('tour_total_price');
                                                                   
                        $total_booking_price = $agentsInvoices + $agentsPackageBookings;
                                        
                      
                        
                        $agent_booking_data[] = floor($total_booking_price * 100) / 100;
                       
                    }
                    
                    $series_data[] = [
                            'name' => $agent_res->agent_Name,
                            'data' => $agent_booking_data
                        ];
                }
                // print_r($series_data);
                // die;
    
            }
            
            // return $limitedAgentData;
            
            // Season
            if($userData->id == 4 || $userData->id == 54){
                // dd($limitedAgentData);
                $limitedAgentData = $this->booking_filter_Season_Working($limitedAgentData,$request,$request_data);
                // dd($limitedAgentData);
            }
            // Season
            
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
            
            // Reindex the collection starting from 0
            $agentallBookingsObject = $agentallBookingsObject->values();
            
            $agentallBookingsObject = $agentallBookingsObject->toArray();
            
            if(sizeOf($agentallBookingsObject) >= 4){
                $limitedAgentData = array_slice($agentallBookingsObject, 0, 4);
            }else{
                $limitedAgentData = $limitedAgentData;
            }
            
            // dd($limitedAgentData);
            $series_data        = [];
            $categories_data    = [];
            
            // Generate Graph Data All
            if($request_data->date_type == 'all_data'){
                $currentYear = date('Y');
                $monthsData = [];
            
                for ($month = 1; $month <= 12; $month++) {
                    
                     $startOfMonth = Carbon::create($currentYear, $month, 1)->startOfMonth();
                     $endOfMonth = Carbon::create($currentYear, $month, 1)->endOfMonth();
                     
                      $categories_data[] = $startOfMonth->format('F');
                     
                     $startOfMonth = $startOfMonth->format('Y-m-d');
                     $endOfMonth = $endOfMonth->format('Y-m-d');
    
                    $monthsData[] = (Object)[
                        'month' => $month,
                        'start_date' => $startOfMonth,
                        'end_date' => $endOfMonth,
                    ];
                }
                
                foreach($limitedAgentData as $agent_res){
                    
               
                    
                    $agent_booking_data = [];
                    foreach($monthsData as $month_res){
                        // Add 7 days to the start date
                        
                        $agentsInvoices = DB::table('add_manage_invoices')->where('agent_Id',$agent_res->agent_id)
                                                                          ->whereDate('add_manage_invoices.created_at','>=', $month_res->start_date)
                                                                          ->whereDate('add_manage_invoices.created_at','<=', $month_res->end_date)
                                                                          ->count();

                        $agentsPackageBookings =  DB::table('cart_details')->where('agent_name',$agent_res->agent_id)
                                                                  ->whereDate('cart_details.created_at','>=', $month_res->start_date)
                                                                  ->whereDate('cart_details.created_at','<=', $month_res->end_date)
                                                                  ->count();
                                                                   
                        $total_booking_price = $agentsInvoices + $agentsPackageBookings;
                                        
                      
                        
                        $agent_booking_data[] = floor($total_booking_price * 100) / 100;
                       
                    }
                    
                    $series_data[] = [
                            'name' => $agent_res->agent_Name,
                            'data' => $agent_booking_data
                        ];
                }
            }
            
            // Generate Graph Data YesterDay
            if($request_data->date_type == 'data_today_wise'){
            
                $date = date('Y-m-d');
                foreach($limitedAgentData as $agent_res){
                    
                    $agent_booking_data = [$agent_res->all_bookings];
                    $series_data[] = [
                            'name' => $agent_res->agent_Name,
                            'data' => $agent_booking_data
                        ];
                }
                
                $categories_data = [$date];
            }
            
            // Generate Graph Data YesterDay
            if($request_data->date_type == 'data_Yesterday_wise'){
            
                $date = date('Y-m-d',strtotime("-1 days"));
                
                foreach($limitedAgentData as $agent_res){
                    $agent_booking_data = [$agent_res->all_bookings];
                    $series_data[] = [
                            'name' => $agent_res->agent_Name,
                            'data' => $agent_booking_data
                        ];
                }
                
                $categories_data = [$date];
            }
            
            // Generate Graph Data For Week
            if($request_data->date_type == 'data_week_wise'){
               $today = Carbon::now();
                
                // Create a CarbonPeriod instance for the current week
               $startOfWeek = $today->startOfWeek()->toDateString();
                $endOfWeek = $today->endOfWeek()->toDateString();
                
                // Create a CarbonPeriod instance for the current week
                $period = CarbonPeriod::create($startOfWeek, $endOfWeek);
                
                // Get the dates of the current week as an array
                $datesOfWeek = [];
                foreach ($period as $date) {
                    $datesOfWeek[] = $date->toDateString();
                }
                
                // Get Data for Agent 
                
                foreach($limitedAgentData as $agent_res){
                    
                    // Loop On DatesOfWeek
                    
                    $agent_booking_data = [];
                    foreach($datesOfWeek as $date_res){
                        $agentsInvoices = DB::table('add_manage_invoices')->where('agent_Id',$agent_res->agent_id)
                                                                          ->whereDate('created_at',$date_res)
                                                                          ->count();

                        $agentsPackageBookings =  DB::table('cart_details')->where('agent_name',$agent_res->agent_id)
                                                                  ->whereDate('created_at',$date_res)
                                                                  ->count();
                                                                   
                        $total_booking_price = $agentsInvoices + $agentsPackageBookings;
                                        
                      
                        
                        $agent_booking_data[] = $total_booking_price;
                    }
                    
                    $series_data[] = [
                            'name' => $agent_res->agent_Name,
                            'data' => $agent_booking_data
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
                    
                    // Start Date Of Month
                    // $currentDate = Carbon::now();
                    $startDate = Carbon::now()->startOfMonth();
                    // $startDate = $currentDate->subMonth()->firstOfMonth();
                    $startDateWeek = $startDate->toDateString();
                    $endDate = $startDate->copy()->addDays(6);
                    $endDateWeek = $endDate->toDateString();
                    
                    $agent_booking_data = [];
                    for($i=1; $i<=5; $i++){
                        // Add 7 days to the start date
                        
                        $agentsInvoices = DB::table('add_manage_invoices')->where('agent_Id',$agent_res->agent_id)
                                                                          ->whereDate('add_manage_invoices.created_at','>=', $startDate)
                                                                          ->whereDate('add_manage_invoices.created_at','<=', $endDate)
                                                                          ->count();

                        $agentsPackageBookings =  DB::table('cart_details')->where('agent_name',$agent_res->agent_id)
                                                                  ->whereDate('cart_details.created_at','>=', $startDate)
                                                                  ->whereDate('cart_details.created_at','<=', $endDate)
                                                                  ->count();
                                                                   
                        $total_booking_price = $agentsInvoices + $agentsPackageBookings;
                                        
                      
                        
                        $agent_booking_data[] = $total_booking_price;
                        
                        
                        $startDate = $endDate->copy()->addDays(1);
                        if($i == '4'){
                            $endDate = $startDate->copy()->addDays(2);
                        }else{
                             $endDate = $startDate->copy()->addDays(6);
                        }
                        
                        $startDateWeek = $startDate->toDateString();
                        $endDateWeek = $endDate->toDateString();
                    }
                    
                    $series_data[] = [
                            'name' => $agent_res->agent_Name,
                            'data' => $agent_booking_data
                        ];
                }
                    
               
                    // dd($startDateWeek,$endDateWeek);
                //   print_r($weekDates);
                //   die;
                $categories_data = ['1st Week','2nd Week','3rd Week','4th Week','5th Week'];
            }
            
            // Generate Graph Data For Year
            if($request_data->date_type == 'data_year_wise'){
                
                $currentYear = date('Y');
                $monthsData = [];
            
                for ($month = 1; $month <= 12; $month++) {
                    
                     $startOfMonth = Carbon::create($currentYear, $month, 1)->startOfMonth();
                     $endOfMonth = Carbon::create($currentYear, $month, 1)->endOfMonth();
                     
                      $categories_data[] = $startOfMonth->format('F');
                     
                     $startOfMonth = $startOfMonth->format('Y-m-d');
                     $endOfMonth = $endOfMonth->format('Y-m-d');
    
                    $monthsData[] = (Object)[
                        'month' => $month,
                        'start_date' => $startOfMonth,
                        'end_date' => $endOfMonth,
                    ];
                }
                
                foreach($limitedAgentData as $agent_res){
                    
               
                    
                    $agent_booking_data = [];
                    foreach($monthsData as $month_res){
                        // Add 7 days to the start date
                        
                        $agentsInvoices = DB::table('add_manage_invoices')->where('agent_Id',$agent_res->agent_id)
                                                                          ->whereDate('add_manage_invoices.created_at','>=', $month_res->start_date)
                                                                          ->whereDate('add_manage_invoices.created_at','<=', $month_res->end_date)
                                                                          ->count();

                        $agentsPackageBookings =  DB::table('cart_details')->where('agent_name',$agent_res->agent_id)
                                                                  ->whereDate('cart_details.created_at','>=', $month_res->start_date)
                                                                  ->whereDate('cart_details.created_at','<=', $month_res->end_date)
                                                                  ->count();
                                                                   
                        $total_booking_price = $agentsInvoices + $agentsPackageBookings;
                                        
                      
                        
                        $agent_booking_data[] = floor($total_booking_price * 100) / 100;
                       
                    }
                    
                    $series_data[] = [
                            'name' => $agent_res->agent_Name,
                            'data' => $agent_booking_data
                        ];
                }
                // print_r($series_data);
                // die;
    
            }
            
            // Season
            if($userData->id == 4 || $userData->id == 54){
                // dd($limitedAgentData);
                $limitedAgentData = $this->booking_filter_Season_Working($limitedAgentData,$request,$request_data);
                // dd($limitedAgentData);
            }
            // Season
            
            return response()->json([
                'status'            => 'success',
                'data'              => $limitedAgentData,
                'series_data'       => $series_data,
                'categories_data'   => $categories_data,
                'agent_Groups'      => $agent_Groups,
            ]);
        }
    }
    
    public function top_agents_booking_subUser(Request $request){ 
        $userData               = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        $agentsInvoices         = DB::table('Agents_detail')
                                    ->leftJoin('add_manage_invoices','add_manage_invoices.agent_Id','Agents_detail.id')
                                    ->where('Agents_detail.customer_id',$userData->id)
                                    ->where('Agents_detail.SU_id',$request->SU_id)
                                    // ->select('Agents_detail.id', 'Agents_detail.agent_Name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                                    ->select('Agents_detail.id','Agents_detail.agent_Refrence_No','Agents_detail.agent_Name','Agents_detail.company_name','Agents_detail.balance','Agents_detail.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_Company) as Invoices_prices_sum'))
                                    ->groupBy('Agents_detail.id')
                                    ->orderBy('Agents_detail.id','asc')
                                    ->get();
        $agent_Groups = [];
        foreach($agentsInvoices as $val_AID){
            $booked_GD  = DB::table('addGroupsdetails')->where('SU_id',$request->SU_id)
                            ->where('group_Client_Prefix',$val_AID->agent_Refrence_No)
                            ->select('group_Name','total_Invoices','group_Passport_Count','group_Client_Prefix')
                            ->get();
                            
            if($booked_GD->isEmpty()){
                $data_AG = [
                    'agent_ID'  => $val_AID->id,
                    'agent_RN'  => $val_AID->agent_Refrence_No,
                    'booked_GD' => '',
                ];
                array_push($agent_Groups,$data_AG);
            }else{
                $data_AG = [
                    'agent_ID'  => $val_AID->id,
                    'agent_RN'  => $val_AID->agent_Refrence_No,
                    'booked_GD' => $booked_GD,
                ];
                array_push($agent_Groups,$data_AG);
            }
        }
        
        $agentsPackageBookings  = DB::table('Agents_detail')
                                    ->leftJoin('cart_details','cart_details.agent_name','Agents_detail.id')
                                    ->where('Agents_detail.customer_id',$userData->id)
                                    ->where('Agents_detail.SU_id',$request->SU_id)
                                    ->select('Agents_detail.id', 'Agents_detail.agent_Name','Agents_detail.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                                    ->groupBy('Agents_detail.id')
                                    ->orderBy('Agents_detail.id','asc')
                                    ->get();
                                    
        $agentallBookingsObject = [];
        
        foreach($agentsInvoices as $index => $invoice_res){
            $total_prices = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
            $agentallBookingsObject[] = (Object)[
                'agent_id'                  => $invoice_res->id,
                'agent_Name'                => $invoice_res->agent_Name,
                'company_name'              => $invoice_res->company_name,
                'currency'                  =>  $invoice_res->currency,
                'Invoices_booking'          => $invoice_res->Invoices_booking,
                'Invoices_prices_sum'       => number_format($invoice_res->Invoices_prices_sum,2),
                'packages_booking'          => $agentsPackageBookings[$index]->packages_booking,
                'packages_prices_sum'       => number_format($agentsPackageBookings[$index]->packages_prices_sum,2),
                'all_bookings'              => $invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking,
                'all_bookings_num_format'   => number_format($invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking),
                'total_prices'              => $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,
                'total_prices_num_format'   => number_format($invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,2),
                'Paid'                      => number_format($total_prices - $invoice_res->balance,2),
                'remaining'                 => number_format($invoice_res->balance,2)
            ];
        }
        
        $agentallBookingsObject = new Collection($agentallBookingsObject);
        
        $agentallBookingsObject = $agentallBookingsObject->sortByDesc('total_prices');
        
        // Reindex the collection starting from 0
        $agentallBookingsObject = $agentallBookingsObject->values();
        
        $agentallBookingsObject = $agentallBookingsObject->toArray();
        
        if(sizeOf($agentallBookingsObject) >= 4){
            $limitedAgentData = array_slice($agentallBookingsObject, 0, 4);
        }else{
            $limitedAgentData = $agentallBookingsObject ?? '';
        }
        
        $series_data        = [];
        $categories_data    = [];
        $currentYear        = date('Y');
        $monthsData         = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $startOfMonth       = Carbon::create($currentYear, $month, 1)->startOfMonth();
            $endOfMonth         = Carbon::create($currentYear, $month, 1)->endOfMonth();
            $categories_data[]  = $startOfMonth->format('F');
            $startOfMonth       = $startOfMonth->format('Y-m-d');
            $endOfMonth         = $endOfMonth->format('Y-m-d');
            
            $monthsData[] = (Object)[
                'month'         => $month,
                'start_date'    => $startOfMonth,
                'end_date'  => $endOfMonth,
            ];
        }
        
        if(isset($limitedAgentData) && $limitedAgentData != null && $limitedAgentData != ''){
            foreach($limitedAgentData as $agent_res){
                $agent_booking_data = [];
                foreach($monthsData as $month_res){
                    // Add 7 days to the start date
                    
                    $agentsInvoices = DB::table('add_manage_invoices')->where('agent_Id',$agent_res->agent_id)->where('SU_id',$request->SU_id)
                                                                      ->whereDate('add_manage_invoices.created_at','>=', $month_res->start_date)
                                                                      ->whereDate('add_manage_invoices.created_at','<=', $month_res->end_date)
                                                                      ->Sum('total_sale_price_all_Services');
    
                    $agentsPackageBookings =  DB::table('cart_details')->where('agent_name',$agent_res->agent_id)->where('SU_id',$request->SU_id)
                                                              ->whereDate('cart_details.created_at','>=', $month_res->start_date)
                                                              ->whereDate('cart_details.created_at','<=', $month_res->end_date)
                                                              ->Sum('tour_total_price');
                                                               
                    $total_booking_price = $agentsInvoices + $agentsPackageBookings;
                                    
                  
                    
                    $agent_booking_data[] = floor($total_booking_price * 100) / 100;
                   
                }
                
                $series_data[] = [
                    'name' => $agent_res->agent_Name,
                    'data' => $agent_booking_data
                ];
            }
        }
        
        return response()->json([
            'status'            => 'success',
            'data'              => $agentallBookingsObject,
            'series_data'       => $series_data,
            'categories_data'   => $categories_data,
            'agent_Groups'      => $agent_Groups,
        ]);
    }
    
    public function top_agents_booking(Request $request){ 
        $userData               = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        $agentsInvoices         = DB::table('Agents_detail')
                                    ->leftJoin('add_manage_invoices','add_manage_invoices.agent_Id','Agents_detail.id')
                                    ->where('Agents_detail.customer_id',$userData->id)
                                    // ->select('Agents_detail.id', 'Agents_detail.agent_Name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                                    ->select('Agents_detail.id','Agents_detail.agent_Refrence_No','Agents_detail.agent_Name','Agents_detail.company_name','Agents_detail.balance','Agents_detail.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_Company) as Invoices_prices_sum'))
                                    ->groupBy('Agents_detail.id')
                                    ->orderBy('Agents_detail.id','asc')
                                    ->get();
        $agentsPackageBookings  = DB::table('Agents_detail')
                                    ->leftJoin('cart_details','cart_details.agent_name','Agents_detail.id')
                                    ->where('Agents_detail.customer_id',$userData->id)
                                    ->select('Agents_detail.id', 'Agents_detail.agent_Name','Agents_detail.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                                    ->groupBy('Agents_detail.id')
                                    ->orderBy('Agents_detail.id','asc')
                                    ->get();
        // Season
        if($userData->id == 4 || $userData->id == 54){
            $today_Date                 = date('Y-m-d');
            if(isset($request->season_Id)){
                if($request->season_Id == 'all_Seasons'){
                    $season_Details     = null;
                }elseif($request->season_Id > 0){
                    $season_Details     = DB::table('add_Seasons')->where('customer_id', $userData->id)->where('id', $request->season_Id)->first();
                }else{
                    $season_Details     = null;
                }
            }else{
                $season_Details         = DB::table('add_Seasons')->where('customer_id', $userData->id)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
            }
            
            if($season_Details != null){
                $start_Date             = $season_Details->start_Date;
                $end_Date               = $season_Details->end_Date;
                $agentsInvoices         = DB::table('Agents_detail')
                                            ->leftJoin('add_manage_invoices','add_manage_invoices.agent_Id','Agents_detail.id')
                                            ->where('Agents_detail.customer_id',$userData->id)
                                            ->whereBetween('Agents_detail.created_at', [$start_Date, $end_Date])
                                            // ->select('Agents_detail.id', 'Agents_detail.agent_Name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                                            ->select('Agents_detail.id','Agents_detail.agent_Refrence_No','Agents_detail.agent_Name','Agents_detail.company_name','Agents_detail.balance','Agents_detail.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_Company) as Invoices_prices_sum'))
                                            ->groupBy('Agents_detail.id')
                                            ->orderBy('Agents_detail.id','asc')
                                            ->get();
                $agentsPackageBookings  = DB::table('Agents_detail')
                                            ->leftJoin('cart_details','cart_details.agent_name','Agents_detail.id')
                                            ->where('Agents_detail.customer_id',$userData->id)
                                            ->whereBetween('Agents_detail.created_at', [$start_Date, $end_Date])
                                            ->select('Agents_detail.id', 'Agents_detail.agent_Name','Agents_detail.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                                            ->groupBy('Agents_detail.id')
                                            ->orderBy('Agents_detail.id','asc')
                                            ->get();
            }
        }
        // Season
        
        $agent_Groups = [];
        foreach($agentsInvoices as $val_AID){
            $booked_GD  = DB::table('addGroupsdetails')
                            ->where('group_Client_Prefix',$val_AID->agent_Refrence_No)
                            ->select('group_Name','total_Invoices','group_Passport_Count','group_Client_Prefix')
                            ->get();
                            
            if($booked_GD->isEmpty()){
                $data_AG = [
                    'agent_ID'  => $val_AID->id,
                    'agent_RN'  => $val_AID->agent_Refrence_No,
                    'booked_GD' => '',
                ];
                array_push($agent_Groups,$data_AG);
            }else{
                $data_AG = [
                    'agent_ID'  => $val_AID->id,
                    'agent_RN'  => $val_AID->agent_Refrence_No,
                    'booked_GD' => $booked_GD,
                ];
                array_push($agent_Groups,$data_AG);
            }
        }
        
        $agentallBookingsObject = [];
        
        foreach($agentsInvoices as $index => $invoice_res){
            $total_prices = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
            $agentallBookingsObject[] = (Object)[
                    'agent_id'                  => $invoice_res->id,
                    'agent_Name'                => $invoice_res->agent_Name,
                    'company_name'              => $invoice_res->company_name,
                    'currency'                  =>  $invoice_res->currency,
                    'Invoices_booking'          => $invoice_res->Invoices_booking,
                    'Invoices_prices_sum'       => number_format($invoice_res->Invoices_prices_sum,2),
                    'packages_booking'          => $agentsPackageBookings[$index]->packages_booking,
                    'packages_prices_sum'       => number_format($agentsPackageBookings[$index]->packages_prices_sum,2),
                    'all_bookings'              => $invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking,
                    'all_bookings_num_format'   => number_format($invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking),
                    'total_prices'              => $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,
                    'total_prices_num_format'   => number_format($invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,2),
                    'Paid'                      => number_format($total_prices - $invoice_res->balance,2),
                    'remaining'                 => number_format($invoice_res->balance,2)
                ];
        }
        
        $agentallBookingsObject = new Collection($agentallBookingsObject);
        
        $agentallBookingsObject = $agentallBookingsObject->sortByDesc('total_prices');
        
        // Reindex the collection starting from 0
        $agentallBookingsObject = $agentallBookingsObject->values();
        
        $agentallBookingsObject = $agentallBookingsObject->toArray();
        
        if(sizeOf($agentallBookingsObject) >= 4){
            $limitedAgentData = array_slice($agentallBookingsObject, 0, 4);
        }else{
            $limitedAgentData = $limitedAgentData;
        }
        
        $series_data        = [];
        $categories_data    = [];
        $currentYear        = date('Y');
        $monthsData         = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $startOfMonth       = Carbon::create($currentYear, $month, 1)->startOfMonth();
            $endOfMonth         = Carbon::create($currentYear, $month, 1)->endOfMonth();
            $categories_data[]  = $startOfMonth->format('F');
            $startOfMonth       = $startOfMonth->format('Y-m-d');
            $endOfMonth         = $endOfMonth->format('Y-m-d');
            
            $monthsData[] = (Object)[
                'month'         => $month,
                'start_date'    => $startOfMonth,
                'end_date'  => $endOfMonth,
            ];
        }
        
        foreach($limitedAgentData as $agent_res){
            $agent_booking_data = [];
            foreach($monthsData as $month_res){
                // Add 7 days to the start date
                
                $agentsInvoices = DB::table('add_manage_invoices')->where('agent_Id',$agent_res->agent_id)
                                                                  ->whereDate('add_manage_invoices.created_at','>=', $month_res->start_date)
                                                                  ->whereDate('add_manage_invoices.created_at','<=', $month_res->end_date)
                                                                  ->Sum('total_sale_price_all_Services');

                $agentsPackageBookings =  DB::table('cart_details')->where('agent_name',$agent_res->agent_id)
                                                          ->whereDate('cart_details.created_at','>=', $month_res->start_date)
                                                          ->whereDate('cart_details.created_at','<=', $month_res->end_date)
                                                          ->Sum('tour_total_price');
                                                           
                $total_booking_price = $agentsInvoices + $agentsPackageBookings;
                                
              
                
                $agent_booking_data[] = floor($total_booking_price * 100) / 100;
               
            }
            
            $series_data[] = [
                'name' => $agent_res->agent_Name,
                'data' => $agent_booking_data
            ];
        }
        
        return response()->json([
            'status'            => 'success',
            'data'              => $agentallBookingsObject,
            'series_data'       => $series_data,
            'categories_data'   => $categories_data,
            'agent_Groups'      => $agent_Groups,
        ]);
    }
    
    public function agents_details_ByType(Request $request){
        $userData               = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        $request_data           = $request;
        
        $agentallBookingsObject = [];
        $agent_Groups           = [];
        
        if($request_data->date_type == 'data_today_wise'){
            $today_date     = date('Y-m-d');
            $agentsInvoices = DB::table('Agents_detail')
                                ->leftJoin('add_manage_invoices', function ($join) use($today_date) {
                                    $join->on('add_manage_invoices.agent_Id', '=', 'Agents_detail.id')->whereDate('add_manage_invoices.created_at', $today_date);
                                })->where('Agents_detail.customer_id',$userData->id)->where('Agents_detail.id',$request->id)
                                ->select('Agents_detail.id','Agents_detail.agent_Refrence_No', 'Agents_detail.agent_Name','Agents_detail.company_name','Agents_detail.balance','Agents_detail.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_Company) as Invoices_prices_sum'))
                                ->groupBy('Agents_detail.id')->orderBy('Agents_detail.id','asc')->get();
            foreach($agentsInvoices as $val_AID){
                $booked_GD  = DB::table('addGroupsdetails')
                                ->where('group_Client_Prefix',$val_AID->agent_Refrence_No)
                                ->select('group_Name','total_Invoices','group_Passport_Count','group_Client_Prefix')
                                ->get();
                                
                if($booked_GD->isEmpty()){
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => '',
                    ];
                    array_push($agent_Groups,$data_AG);
                }else{
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => $booked_GD,
                    ];
                    array_push($agent_Groups,$data_AG);
                }
            }
            
            $agentsPackageBookings = DB::table('Agents_detail')
                                    ->leftJoin('cart_details', function ($join) use($today_date) {
                                        $join->on('cart_details.agent_name', '=', 'Agents_detail.id')->whereDate('cart_details.created_at', $today_date);
                                    })
                                    ->where('Agents_detail.customer_id',$userData->id)
                                    ->select('Agents_detail.id', 'Agents_detail.agent_Name','Agents_detail.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                                    ->groupBy('Agents_detail.id')
                                    ->orderBy('Agents_detail.id','asc')
                                    ->get();
            foreach($agentsInvoices as $index => $invoice_res){
                $total_prices   = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                $payment_today  = DB::table('agents_ledgers_new')->where('agent_id',$invoice_res->id)->whereDate('date',$today_date)->where('received_id','!=',NULL)->sum('payment');
                $total_price    = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                
                $agentallBookingsObject[] = (Object)[
                        'agent_id' => $invoice_res->id,
                        'agent_Name' => $invoice_res->agent_Name,
                        'company_name' => $invoice_res->company_name,
                        'currency' =>  $invoice_res->currency,
                        'Invoices_booking' => $invoice_res->Invoices_booking,
                        'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                        'packages_booking' => $agentsPackageBookings[$index]->packages_booking,
                        'packages_prices_sum' => number_format($agentsPackageBookings[$index]->packages_prices_sum,2),
                        'all_bookings' => $invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking,
                        'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking),
                        'total_prices' => $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,
                        'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,2),
                        'Paid' => number_format($payment_today,2),
                        'remaining' => number_format($total_price - $payment_today,2)
                    ];
            }
        }
        
        if($request_data->date_type == 'data_week_wise'){
            
            $startOfWeek = Carbon::now()->startOfWeek();
            $start_date = $startOfWeek->format('Y-m-d');
            $end_date = date('Y-m-d');
            
            //  dd($today_date);
             $agentsInvoices = DB::table('Agents_detail')
                                ->leftJoin('add_manage_invoices', function ($join) use($start_date,$end_date) {
                                                    $join->on('add_manage_invoices.agent_Id', '=', 'Agents_detail.id')
                                                        ->whereDate('add_manage_invoices.created_at','>=', $start_date)
                                                        ->whereDate('add_manage_invoices.created_at','<=', $end_date);
                                                })
                                ->where('Agents_detail.customer_id',$userData->id)->where('Agents_detail.id',$request->id)
                                
                                // ->select('Agents_detail.id', 'Agents_detail.agent_Name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                                ->select('Agents_detail.id','Agents_detail.agent_Refrence_No', 'Agents_detail.agent_Name','Agents_detail.company_name','Agents_detail.balance','Agents_detail.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_Company) as Invoices_prices_sum'))
                                ->groupBy('Agents_detail.id')
                                ->orderBy('Agents_detail.id','asc')
                                ->get();
            
            foreach($agentsInvoices as $val_AID){
                $booked_GD  = DB::table('addGroupsdetails')
                                ->where('group_Client_Prefix',$val_AID->agent_Refrence_No)
                                ->select('group_Name','total_Invoices','group_Passport_Count','group_Client_Prefix')
                                ->get();
                                
                if($booked_GD->isEmpty()){
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => '',
                    ];
                    array_push($agent_Groups,$data_AG);
                }else{
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => $booked_GD,
                    ];
                    array_push($agent_Groups,$data_AG);
                }
            }
                
                $agentsPackageBookings = DB::table('Agents_detail')
                                ->leftJoin('cart_details', function ($join) use($start_date,$end_date) {
                                                    $join->on('cart_details.agent_name', '=', 'Agents_detail.id')
                                                        ->whereDate('cart_details.created_at','>=', $start_date)
                                                        ->whereDate('cart_details.created_at','<=', $end_date);
                                                })
                                ->where('Agents_detail.customer_id',$userData->id)
                                ->select('Agents_detail.id', 'Agents_detail.agent_Name','Agents_detail.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                                ->groupBy('Agents_detail.id')
                                ->orderBy('Agents_detail.id','asc')
                                ->get();
                
                foreach($agentsInvoices as $index => $invoice_res){
                    $total_prices = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                    
                    $payment_today = DB::table('agents_ledgers_new')->where('agent_id',$invoice_res->id)
                                                   ->whereDate('date','>=', $start_date)
                                                   ->whereDate('date','<=', $end_date)
                                                   ->where('received_id','!=',NULL)
                                                   ->sum('payment');
                    $total_price = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                    
                    $agentallBookingsObject[] = (Object)[
                            'agent_id' => $invoice_res->id,
                            'agent_Name' => $invoice_res->agent_Name,
                            'company_name' => $invoice_res->company_name,
                            'currency' =>  $invoice_res->currency,
                            'Invoices_booking' => $invoice_res->Invoices_booking,
                            'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                            'packages_booking' => $agentsPackageBookings[$index]->packages_booking,
                            'packages_prices_sum' => number_format($agentsPackageBookings[$index]->packages_prices_sum,2),
                            'all_bookings' => $invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking,
                            'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking),
                            'total_prices' => $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,
                            'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,2),
                            'Paid' => number_format($payment_today,2),
                            'remaining' => number_format($total_price - $payment_today,2)
                        ];
                }
        }
        
        if($request_data->date_type == 'data_month_wise'){
            
            $startOfMonth = Carbon::now()->startOfMonth();
            $start_date = $startOfMonth->format('Y-m-d');
            $end_date = date('Y-m-d');
            
            //  dd($today_date);
             $agentsInvoices = DB::table('Agents_detail')
                                ->leftJoin('add_manage_invoices', function ($join) use($start_date,$end_date) {
                                                    $join->on('add_manage_invoices.agent_Id', '=', 'Agents_detail.id')
                                                        ->whereDate('add_manage_invoices.created_at','>=', $start_date)
                                                        ->whereDate('add_manage_invoices.created_at','<=', $end_date);
                                                })
                                ->where('Agents_detail.customer_id',$userData->id)->where('Agents_detail.id',$request->id)
                                
                                // ->select('Agents_detail.id', 'Agents_detail.agent_Name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                                ->select('Agents_detail.id','Agents_detail.agent_Refrence_No', 'Agents_detail.agent_Name','Agents_detail.company_name','Agents_detail.balance','Agents_detail.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_Company) as Invoices_prices_sum'))
                                ->groupBy('Agents_detail.id')
                                ->orderBy('Agents_detail.id','asc')
                                ->get();
            
            foreach($agentsInvoices as $val_AID){
                $booked_GD  = DB::table('addGroupsdetails')
                                ->where('group_Client_Prefix',$val_AID->agent_Refrence_No)
                                ->select('group_Name','total_Invoices','group_Passport_Count','group_Client_Prefix')
                                ->get();
                                
                if($booked_GD->isEmpty()){
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => '',
                    ];
                    array_push($agent_Groups,$data_AG);
                }else{
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => $booked_GD,
                    ];
                    array_push($agent_Groups,$data_AG);
                }
            }
                
                $agentsPackageBookings = DB::table('Agents_detail')
                                ->leftJoin('cart_details', function ($join) use($start_date,$end_date) {
                                                    $join->on('cart_details.agent_name', '=', 'Agents_detail.id')
                                                        ->whereDate('cart_details.created_at','>=', $start_date)
                                                        ->whereDate('cart_details.created_at','<=', $end_date);
                                                })
                                ->where('Agents_detail.customer_id',$userData->id)
                                ->select('Agents_detail.id', 'Agents_detail.agent_Name','Agents_detail.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                                ->groupBy('Agents_detail.id')
                                ->orderBy('Agents_detail.id','asc')
                                ->get();
                                
                
                // print_r($agentsInvoices);
                // print_r($agentsPackageBookings);
                // die;
                foreach($agentsInvoices as $index => $invoice_res){
                    $total_prices = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                    
                    $payment_today = DB::table('agents_ledgers_new')->where('agent_id',$invoice_res->id)
                                                   ->whereDate('date','>=', $start_date)
                                                   ->whereDate('date','<=', $end_date)
                                                   ->where('received_id','!=',NULL)
                                                   ->sum('payment');
                    $total_price = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                    
                    $agentallBookingsObject[] = (Object)[
                            'agent_id' => $invoice_res->id,
                            'agent_Name' => $invoice_res->agent_Name,
                            'company_name' => $invoice_res->company_name,
                            'currency' =>  $invoice_res->currency,
                            'Invoices_booking' => $invoice_res->Invoices_booking,
                            'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                            'packages_booking' => $agentsPackageBookings[$index]->packages_booking,
                            'packages_prices_sum' => number_format($agentsPackageBookings[$index]->packages_prices_sum,2),
                            'all_bookings' => $invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking,
                            'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking),
                            'total_prices' => $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,
                            'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,2),
                            'Paid' => number_format($payment_today,2),
                            'remaining' => number_format($total_price - $payment_today,2)
                        ];
                }
        }
        
        if($request_data->date_type == 'data_year_wise'){
            
            $startOfYear    = Carbon::now()->startOfYear();
            $start_date     = $startOfYear->format('Y-m-d');
            $end_date       = date('Y-m-d');
            
            //  dd($today_date);
             $agentsInvoices = DB::table('Agents_detail')
                                ->leftJoin('add_manage_invoices', function ($join) use($start_date,$end_date) {
                                                    $join->on('add_manage_invoices.agent_Id', '=', 'Agents_detail.id')
                                                        ->whereDate('add_manage_invoices.created_at','>=', $start_date)
                                                        ->whereDate('add_manage_invoices.created_at','<=', $end_date);
                                                })
                                ->where('Agents_detail.customer_id',$userData->id)->where('Agents_detail.id',$request->id)
                                
                                // ->select('Agents_detail.id', 'Agents_detail.agent_Name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                                ->select('Agents_detail.id','Agents_detail.agent_Refrence_No', 'Agents_detail.agent_Name','Agents_detail.company_name','Agents_detail.balance','Agents_detail.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_Company) as Invoices_prices_sum'))
                                ->groupBy('Agents_detail.id')
                                ->orderBy('Agents_detail.id','asc')
                                ->get();
            
            foreach($agentsInvoices as $val_AID){
                $booked_GD  = DB::table('addGroupsdetails')
                                ->where('group_Client_Prefix',$val_AID->agent_Refrence_No)
                                ->select('group_Name','total_Invoices','group_Passport_Count','group_Client_Prefix')
                                ->get();
                                
                if($booked_GD->isEmpty()){
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => '',
                    ];
                    array_push($agent_Groups,$data_AG);
                }else{
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => $booked_GD,
                    ];
                    array_push($agent_Groups,$data_AG);
                }
            }
            
                $agentsPackageBookings = DB::table('Agents_detail')
                                ->leftJoin('cart_details', function ($join) use($start_date,$end_date) {
                                                    $join->on('cart_details.agent_name', '=', 'Agents_detail.id')
                                                        ->whereDate('cart_details.created_at','>=', $start_date)
                                                        ->whereDate('cart_details.created_at','<=', $end_date);
                                                })
                                ->where('Agents_detail.customer_id',$userData->id)
                                ->select('Agents_detail.id', 'Agents_detail.agent_Name','Agents_detail.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                                ->groupBy('Agents_detail.id')
                                ->orderBy('Agents_detail.id','asc')
                                ->get();
                                
                
                // print_r($agentsInvoices);
                // print_r($agentsPackageBookings);
                // die;
                foreach($agentsInvoices as $index => $invoice_res){
                    $total_prices = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                    
                    $payment_today = DB::table('agents_ledgers_new')->where('agent_id',$invoice_res->id)
                                                   ->whereDate('date','>=', $start_date)
                                                   ->whereDate('date','<=', $end_date)
                                                   ->where('received_id','!=',NULL)
                                                   ->sum('payment');
                    $total_price = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                    
                    $agentallBookingsObject[] = (Object)[
                            'agent_id' => $invoice_res->id,
                            'agent_Name' => $invoice_res->agent_Name,
                            'company_name' => $invoice_res->company_name,
                            'currency' =>  $invoice_res->currency,
                            'Invoices_booking' => $invoice_res->Invoices_booking,
                            'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                            'packages_booking' => $agentsPackageBookings[$index]->packages_booking,
                            'packages_prices_sum' => number_format($agentsPackageBookings[$index]->packages_prices_sum,2),
                            'all_bookings' => $invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking,
                            'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking),
                            'total_prices' => $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,
                            'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,2),
                            'Paid' => number_format($payment_today,2),
                            'remaining' => number_format($total_price - $payment_today,2)
                        ];
                }
        }
        
        if($request_data->date_type == 'data_wise'){
            $start_date = $request_data->start_date;
            $end_date   = $request_data->end_date;
            
            //  dd($today_date);
             $agentsInvoices = DB::table('Agents_detail')
                                ->leftJoin('add_manage_invoices', function ($join) use($start_date,$end_date) {
                                                    $join->on('add_manage_invoices.agent_Id', '=', 'Agents_detail.id')
                                                        ->whereDate('add_manage_invoices.created_at','>=', $start_date)
                                                        ->whereDate('add_manage_invoices.created_at','<=', $end_date);
                                                })
                                ->where('Agents_detail.customer_id',$userData->id)->where('Agents_detail.id',$request->id)
                                
                                // ->select('Agents_detail.id', 'Agents_detail.agent_Name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                                ->select('Agents_detail.id','Agents_detail.agent_Refrence_No', 'Agents_detail.agent_Name','Agents_detail.company_name','Agents_detail.balance','Agents_detail.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_Company) as Invoices_prices_sum'))
                                ->groupBy('Agents_detail.id')
                                ->orderBy('Agents_detail.id','asc')
                                ->get();
            
            foreach($agentsInvoices as $val_AID){
                $booked_GD  = DB::table('addGroupsdetails')
                                ->where('group_Client_Prefix',$val_AID->agent_Refrence_No)
                                ->select('group_Name','total_Invoices','group_Passport_Count','group_Client_Prefix')
                                ->get();
                                
                if($booked_GD->isEmpty()){
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => '',
                    ];
                    array_push($agent_Groups,$data_AG);
                }else{
                    $data_AG = [
                        'agent_ID'  => $val_AID->id,
                        'agent_RN'  => $val_AID->agent_Refrence_No,
                        'booked_GD' => $booked_GD,
                    ];
                    array_push($agent_Groups,$data_AG);
                }
            }    
            
                $agentsPackageBookings = DB::table('Agents_detail')
                                ->leftJoin('cart_details', function ($join) use($start_date,$end_date) {
                                                    $join->on('cart_details.agent_name', '=', 'Agents_detail.id')
                                                        ->whereDate('cart_details.created_at','>=', $start_date)
                                                        ->whereDate('cart_details.created_at','<=', $end_date);
                                                })
                                ->where('Agents_detail.customer_id',$userData->id)
                                ->select('Agents_detail.id', 'Agents_detail.agent_Name','Agents_detail.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                                ->groupBy('Agents_detail.id')
                                ->orderBy('Agents_detail.id','asc')
                                ->get();
                                
                
                // print_r($agentsInvoices);
                // print_r($agentsPackageBookings);
                // die;
                foreach($agentsInvoices as $index => $invoice_res){
                    $total_prices = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                    
                    $payment_today = DB::table('agents_ledgers_new')->where('agent_id',$invoice_res->id)
                                                   ->whereDate('date','>=', $start_date)
                                                   ->whereDate('date','<=', $end_date)
                                                   ->where('received_id','!=',NULL)
                                                   ->sum('payment');
                    $total_price = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
                    
                    $agentallBookingsObject[] = (Object)[
                            'agent_id' => $invoice_res->id,
                            'agent_Name' => $invoice_res->agent_Name,
                            'company_name' => $invoice_res->company_name,
                            'currency' =>  $invoice_res->currency,
                            'Invoices_booking' => $invoice_res->Invoices_booking,
                            'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                            'packages_booking' => $agentsPackageBookings[$index]->packages_booking,
                            'packages_prices_sum' => number_format($agentsPackageBookings[$index]->packages_prices_sum,2),
                            'all_bookings' => $invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking,
                            'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking),
                            'total_prices' => $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,
                            'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,2),
                            'Paid' => number_format($payment_today,2),
                            'remaining' => number_format($total_price - $payment_today,2)
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
                $limitedAgentData = $agentallBookingsObject;
            }
            
            $series_data        = [];
            $categories_data    = [];
            
            // Generate Graph Data Today
            if($request_data->date_type == 'data_today_wise'){
                $date = date('Y-m-d');
                foreach($limitedAgentData as $agent_res){
                    $agent_booking_data = [$agent_res->total_prices];
                    $series_data[] = [
                        'name' => $agent_res->agent_Name,
                        'data' => $agent_booking_data
                    ];
                }
                $categories_data = [$date];
            }
            
            // Generate Graph Data For Week
            if($request_data->date_type == 'data_week_wise'){
               $today = Carbon::now();
                
                // Create a CarbonPeriod instance for the current week
               $startOfWeek = $today->startOfWeek()->toDateString();
                $endOfWeek = $today->endOfWeek()->toDateString();
                
                // Create a CarbonPeriod instance for the current week
                $period = CarbonPeriod::create($startOfWeek, $endOfWeek);
                
                // Get the dates of the current week as an array
                $datesOfWeek = [];
                foreach ($period as $date) {
                    $datesOfWeek[] = $date->toDateString();
                }
                
                // Get Data for Agent 
                
                foreach($limitedAgentData as $agent_res){
                    
                    // Loop On DatesOfWeek
                    
                    $agent_booking_data = [];
                    foreach($datesOfWeek as $date_res){
                        $agentsInvoices = DB::table('add_manage_invoices')->where('agent_Id',$agent_res->agent_id)
                                                                          ->whereDate('created_at',$date_res)
                                                                          ->Sum('total_sale_price_all_Services');

                        $agentsPackageBookings =  DB::table('cart_details')->where('agent_name',$agent_res->agent_id)
                                                                  ->whereDate('created_at',$date_res)
                                                                  ->Sum('tour_total_price');
                                                                   
                        $total_booking_price = $agentsInvoices + $agentsPackageBookings;
                                        
                      
                        
                        $agent_booking_data[] = $total_booking_price;
                    }
                    
                    $series_data[] = [
                            'name' => $agent_res->agent_Name,
                            'data' => $agent_booking_data
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
                    
                    // Start Date Of Month
                    // $currentDate = Carbon::now();
                    $startDate = Carbon::now()->startOfMonth();
                    // $startDate = $currentDate->subMonth()->firstOfMonth();
                    $startDateWeek = $startDate->toDateString();
                    $endDate = $startDate->copy()->addDays(6);
                    $endDateWeek = $endDate->toDateString();
                    
                    $agent_booking_data = [];
                    for($i=1; $i<=5; $i++){
                        // Add 7 days to the start date
                        
                        $agentsInvoices = DB::table('add_manage_invoices')->where('agent_Id',$agent_res->agent_id)
                                                                          ->whereDate('add_manage_invoices.created_at','>=', $startDate)
                                                                          ->whereDate('add_manage_invoices.created_at','<=', $endDate)
                                                                          ->Sum('total_sale_price_all_Services');

                        $agentsPackageBookings =  DB::table('cart_details')->where('agent_name',$agent_res->agent_id)
                                                                  ->whereDate('cart_details.created_at','>=', $startDate)
                                                                  ->whereDate('cart_details.created_at','<=', $endDate)
                                                                  ->Sum('tour_total_price');
                                                                   
                        $total_booking_price = $agentsInvoices + $agentsPackageBookings;
                                        
                      
                        
                        $agent_booking_data[] = $total_booking_price;
                        
                        
                        $startDate = $endDate->copy()->addDays(1);
                        if($i == '4'){
                            $endDate = $startDate->copy()->addDays(2);
                        }else{
                             $endDate = $startDate->copy()->addDays(6);
                        }
                        
                        $startDateWeek = $startDate->toDateString();
                        $endDateWeek = $endDate->toDateString();
                    }
                    
                    $series_data[] = [
                            'name' => $agent_res->agent_Name,
                            'data' => $agent_booking_data
                        ];
                }
                    
               
                    // dd($startDateWeek,$endDateWeek);
                //   print_r($weekDates);
                //   die;
                $categories_data = ['1st Week','2nd Week','3rd Week','4th Week','5th Week'];
            }
            
            // Generate Graph Data For Year
            if($request_data->date_type == 'data_year_wise'){
                
                $currentYear = date('Y');
                $monthsData = [];
            
                for ($month = 1; $month <= 12; $month++) {
                    
                     $startOfMonth = Carbon::create($currentYear, $month, 1)->startOfMonth();
                     $endOfMonth = Carbon::create($currentYear, $month, 1)->endOfMonth();
                     
                      $categories_data[] = $startOfMonth->format('F');
                     
                     $startOfMonth = $startOfMonth->format('Y-m-d');
                     $endOfMonth = $endOfMonth->format('Y-m-d');
    
                    $monthsData[] = (Object)[
                        'month' => $month,
                        'start_date' => $startOfMonth,
                        'end_date' => $endOfMonth,
                    ];
                }
                
                foreach($limitedAgentData as $agent_res){
                    
               
                    
                    $agent_booking_data = [];
                    foreach($monthsData as $month_res){
                        // Add 7 days to the start date
                        
                        $agentsInvoices = DB::table('add_manage_invoices')->where('agent_Id',$agent_res->agent_id)
                                                                          ->whereDate('add_manage_invoices.created_at','>=', $month_res->start_date)
                                                                          ->whereDate('add_manage_invoices.created_at','<=', $month_res->end_date)
                                                                          ->Sum('total_sale_price_all_Services');

                        $agentsPackageBookings =  DB::table('cart_details')->where('agent_name',$agent_res->agent_id)
                                                                  ->whereDate('cart_details.created_at','>=', $month_res->start_date)
                                                                  ->whereDate('cart_details.created_at','<=', $month_res->end_date)
                                                                  ->Sum('tour_total_price');
                                                                   
                        $total_booking_price = $agentsInvoices + $agentsPackageBookings;
                                        
                      
                        
                        $agent_booking_data[] = floor($total_booking_price * 100) / 100;
                       
                    }
                    
                    $series_data[] = [
                            'name' => $agent_res->agent_Name,
                            'data' => $agent_booking_data
                        ];
                }
                // print_r($series_data);
                // die;
    
            }
            
            // Generate Graph Data For Custom Date
            if($request_data->date_type == 'data_wise'){
                $series_data        = [];
                $categories_data    = [];
                
                $startDate      = Carbon::parse($request_data->start_date);
                $currentDate    = $startDate->copy();
                $endDate        = Carbon::parse($request_data->end_date);
                $dateRange      = [];
                
                while ($currentDate->lte($endDate)) {
                    $dateRange[] = $currentDate->format('Y-m-d');
                    $currentDate->addDay();
                }
                
                $agent_res = $limitedAgentData[0];
                
                foreach ($dateRange as $date) {
                    $series_data            = [];
                    $agentsInvoices         = DB::table('add_manage_invoices')->where('agent_Id',$agent_res->agent_id)
                                                ->whereDate('add_manage_invoices.created_at', '=', $date)
                                                ->sum('total_sale_price_all_Services');
                    $agentsPackageBookings  = DB::table('cart_details')->where('agent_name',$agent_res->agent_id)
                                                ->whereDate('cart_details.created_at', '=', $date)
                                                ->sum('tour_total_price');
                    $total_booking_price    = $agentsInvoices + $agentsPackageBookings; 
                    if($total_booking_price > 0){
                        $agent_booking_data[]   = $total_booking_price;
                        $series_data[]          = [
                            'name'              => $agent_res->agent_Name,
                            'data'              => $agent_booking_data
                        ];
                    }else{
                        $agent_booking_data[]   = 0;
                        $series_data[]          = [
                            'name'              => $agent_res->agent_Name,
                            'data'              => $agent_booking_data
                        ];
                    }
                    $categories_data[]          = $date;
                }
            }
            
            return response()->json([
                'status'            => 'success',
                'data'              => $limitedAgentData,
                'series_data'       => $series_data,
                // 'series_data1'      => $series_data1,
                'categories_data'   => $categories_data,
                'agent_Groups'      => $agent_Groups,
                // 'categories_data1'  => $categories_data1,
            ]);
        }else{
            $agentallBookingsObject = new Collection($agentallBookingsObject);
            
            $agentallBookingsObject = $agentallBookingsObject->sortByDesc('all_bookings');
            
            // Reindex the collection starting from 0
            $agentallBookingsObject = $agentallBookingsObject->values();
            
            $agentallBookingsObject = $agentallBookingsObject->toArray();
            
            if(sizeOf($agentallBookingsObject) >= 4){
                $limitedAgentData = array_slice($agentallBookingsObject, 0, 4);
            }else{
                $limitedAgentData = $limitedAgentData;
            }
            
            // dd($limitedAgentData);
            $series_data = [];
            $categories_data = [];
            
            // Generate Graph Data YesterDay
            if($request_data->date_type == 'data_today_wise'){
            
                $date = date('Y-m-d');
                foreach($limitedAgentData as $agent_res){
                    
                    $agent_booking_data = [$agent_res->all_bookings];
                    $series_data[] = [
                            'name' => $agent_res->agent_Name,
                            'data' => $agent_booking_data
                        ];
                }
                
                $categories_data = [$date];
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
    
    public function customer_details_ByType(Request $request){
        $userData               = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        $request_data           = $request;
        $agentallBookingsObject = [];
        
        if($request_data->date_type == 'data_today_wise'){
            $today_date = date('Y-m-d');
            $customersInvoices =   DB::table('booking_customers')
                                        ->leftJoin('add_manage_invoices', function ($join) use($today_date) {
                                            $join->on('add_manage_invoices.booking_customer_id', '=', 'booking_customers.id')->whereDate('add_manage_invoices.created_at', $today_date);
                                        })->where('booking_customers.id',$request->id)
                                        ->where('booking_customers.customer_id',$userData->id)
                                        ->select('booking_customers.id', 'booking_customers.name','booking_customers.balance','booking_customers.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_all_Services) as Invoices_prices_sum'))
                                        ->groupBy('booking_customers.id')
                                        ->orderBy('booking_customers.id','asc')
                                        ->get();
                            
            $customersPackageBookings = DB::table('booking_customers')
                            ->leftJoin('cart_details', function ($join) use($today_date){
                                $join->on('booking_customers.id', '=', DB::raw('JSON_UNQUOTE(JSON_EXTRACT(cart_details.cart_total_data, "$.customer_id"))'))
                                ->whereDate('cart_details.created_at', $today_date);
                            })->where('booking_customers.id',$request->id)
                            ->where('booking_customers.customer_id',$userData->id)
                            ->select('booking_customers.id', 'booking_customers.name','booking_customers.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                            ->groupBy('booking_customers.id')
                            ->orderBy('booking_customers.id','asc')
                            ->get();
            
         
            $customerallBookingsObject = [];
            
            foreach($customersInvoices as $index => $invoice_res){
                $total_prices = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                
                $payment_today = DB::table('customer_ledger')->where('booking_customer',$invoice_res->id)
                                                   ->whereDate('date',$today_date)
                                                  ->where('received_id','!=',NULL)
                                                   ->sum('payment');
                                                   
                $total_price = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                $customerallBookingsObject[] = (Object)[
                        'booking_customer_id' => $invoice_res->id,
                        'name' => $invoice_res->name,
                        'currency' =>  $invoice_res->currency,
                        'Invoices_booking' => $invoice_res->Invoices_booking,
                        'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                        'packages_booking' => $customersPackageBookings[$index]->packages_booking,
                        'packages_prices_sum' => number_format($customersPackageBookings[$index]->packages_prices_sum,2),
                        'all_bookings' => $invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking,
                        'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking),
                        'total_prices' => $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,
                        'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,2),
                        'Paid' => number_format($payment_today,2),
                        'remaining' => number_format($total_price - $payment_today,2)
                    ];
            }
        }
        
        if($request_data->date_type == 'data_Yesterday_wise'){
            
             $date = date('Y-m-d',strtotime("-1 days"));
             $customersInvoices = DB::table('booking_customers')
                            ->leftJoin('add_manage_invoices', function ($join) use($date) {
                                                    $join->on('add_manage_invoices.booking_customer_id', '=', 'booking_customers.id')
                                                        ->whereDate('add_manage_invoices.created_at', $date);
                                                })->where('booking_customers.id',$request->id)
                            ->where('booking_customers.customer_id',$userData->id)
                            // ->select('booking_customers.id', 'booking_customers.name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                            ->select('booking_customers.id', 'booking_customers.name','booking_customers.balance','booking_customers.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_all_Services) as Invoices_prices_sum'))
                            ->groupBy('booking_customers.id')
                            ->orderBy('booking_customers.id','asc')
                            ->get();
                            
            $customersPackageBookings = DB::table('booking_customers')
                            ->leftJoin('cart_details', function ($join) use($date){
                                $join->on('booking_customers.id', '=', DB::raw('JSON_UNQUOTE(JSON_EXTRACT(cart_details.cart_total_data, "$.customer_id"))'))
                                ->whereDate('cart_details.created_at', $date);
                            })->where('booking_customers.id',$request->id)
                            ->where('booking_customers.customer_id',$userData->id)
                            ->select('booking_customers.id', 'booking_customers.name','booking_customers.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                            ->groupBy('booking_customers.id')
                            ->orderBy('booking_customers.id','asc')
                            ->get();
            
         
            $customerallBookingsObject = [];
            
            foreach($customersInvoices as $index => $invoice_res){
                $total_prices = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                
                $payment_today = DB::table('customer_ledger')->where('booking_customer',$invoice_res->id)
                                                   ->whereDate('date',$date)
                                                  ->where('received_id','!=',NULL)
                                                   ->sum('payment');
                                                   
                $total_price = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                $customerallBookingsObject[] = (Object)[
                        'booking_customer_id' => $invoice_res->id,
                        'name' => $invoice_res->name,
                        'currency' =>  $invoice_res->currency,
                        'Invoices_booking' => $invoice_res->Invoices_booking,
                        'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                        'packages_booking' => $customersPackageBookings[$index]->packages_booking,
                        'packages_prices_sum' => number_format($customersPackageBookings[$index]->packages_prices_sum,2),
                        'all_bookings' => $invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking,
                        'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking),
                        'total_prices' => $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,
                        'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,2),
                        'Paid' => number_format($payment_today,2),
                        'remaining' => number_format($total_price - $payment_today,2)
                    ];
            }
            
        }
        
        if($request_data->date_type == 'data_week_wise'){
            
            $startOfWeek = Carbon::now()->startOfWeek();
            $start_date = $startOfWeek->format('Y-m-d');
            $end_date = date('Y-m-d');
            
             $customersInvoices = DB::table('booking_customers')
                            ->leftJoin('add_manage_invoices', function ($join) use($start_date,$end_date) {
                                                    $join->on('add_manage_invoices.booking_customer_id', '=', 'booking_customers.id')
                                                        ->whereDate('add_manage_invoices.created_at','>=', $start_date)
                                                        ->whereDate('add_manage_invoices.created_at','<=', $end_date);
                                                })->where('booking_customers.id',$request->id)
                            ->where('booking_customers.customer_id',$userData->id)
                            // ->select('booking_customers.id', 'booking_customers.name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                            ->select('booking_customers.id', 'booking_customers.name','booking_customers.balance','booking_customers.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_all_Services) as Invoices_prices_sum'))
                            ->groupBy('booking_customers.id')
                            ->orderBy('booking_customers.id','asc')
                            ->get();
                            
            $customersPackageBookings = DB::table('booking_customers')
                            ->leftJoin('cart_details', function ($join) use($start_date,$end_date){
                                $join->on('booking_customers.id', '=', DB::raw('JSON_UNQUOTE(JSON_EXTRACT(cart_details.cart_total_data, "$.customer_id"))'))
                                ->whereDate('cart_details.created_at','>=', $start_date)
                                ->whereDate('cart_details.created_at','<=', $end_date);
                            })->where('booking_customers.id',$request->id)
                            ->where('booking_customers.customer_id',$userData->id)
                            ->select('booking_customers.id', 'booking_customers.name','booking_customers.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                            ->groupBy('booking_customers.id')
                            ->orderBy('booking_customers.id','asc')
                            ->get();
            
         
            $customerallBookingsObject = [];
            
            foreach($customersInvoices as $index => $invoice_res){
                $total_prices = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                
                $payment_today = DB::table('customer_ledger')->where('booking_customer',$invoice_res->id)
                                                   ->whereDate('date','>=', $start_date)
                                                   ->whereDate('date','<=', $end_date)
                                                   ->where('received_id','!=',NULL)
                                                   ->sum('payment');
                                                   
                $total_price = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                $customerallBookingsObject[] = (Object)[
                        'booking_customer_id' => $invoice_res->id,
                        'name' => $invoice_res->name,
                        'currency' =>  $invoice_res->currency,
                        'Invoices_booking' => $invoice_res->Invoices_booking,
                        'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                        'packages_booking' => $customersPackageBookings[$index]->packages_booking,
                        'packages_prices_sum' => number_format($customersPackageBookings[$index]->packages_prices_sum,2),
                        'all_bookings' => $invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking,
                        'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking),
                        'total_prices' => $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,
                        'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,2),
                        'Paid' => number_format($payment_today,2),
                        'remaining' => number_format($total_price - $payment_today,2)
                    ];
            }
            
        }
        
        if($request_data->date_type == 'data_month_wise'){
            
            $startOfMonth = Carbon::now()->startOfMonth();
            $start_date = $startOfMonth->format('Y-m-d');
            $end_date = date('Y-m-d');
            
             $customersInvoices = DB::table('booking_customers')
                            ->leftJoin('add_manage_invoices', function ($join) use($start_date,$end_date) {
                                                    $join->on('add_manage_invoices.booking_customer_id', '=', 'booking_customers.id')
                                                        ->whereDate('add_manage_invoices.created_at','>=', $start_date)
                                                        ->whereDate('add_manage_invoices.created_at','<=', $end_date);
                                                })->where('booking_customers.id',$request->id)
                            ->where('booking_customers.customer_id',$userData->id)
                            // ->select('booking_customers.id', 'booking_customers.name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                            ->select('booking_customers.id', 'booking_customers.name','booking_customers.balance','booking_customers.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_all_Services) as Invoices_prices_sum'))
                            ->groupBy('booking_customers.id')
                            ->orderBy('booking_customers.id','asc')
                            ->get();
                            
            $customersPackageBookings = DB::table('booking_customers')
                            ->leftJoin('cart_details', function ($join) use($start_date,$end_date){
                                $join->on('booking_customers.id', '=', DB::raw('JSON_UNQUOTE(JSON_EXTRACT(cart_details.cart_total_data, "$.customer_id"))'))
                                ->whereDate('cart_details.created_at','>=', $start_date)
                                ->whereDate('cart_details.created_at','<=', $end_date);
                            })->where('booking_customers.id',$request->id)
                            ->where('booking_customers.customer_id',$userData->id)
                            ->select('booking_customers.id', 'booking_customers.name','booking_customers.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                            ->groupBy('booking_customers.id')
                            ->orderBy('booking_customers.id','asc')
                            ->get();
            
         
            $customerallBookingsObject = [];
            
            foreach($customersInvoices as $index => $invoice_res){
                $total_prices = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                
                $payment_today = DB::table('customer_ledger')->where('booking_customer',$invoice_res->id)
                                                   ->whereDate('date','>=', $start_date)
                                                   ->whereDate('date','<=', $end_date)
                                                   ->where('received_id','!=',NULL)
                                                   ->sum('payment');
                                                   
                $total_price = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                $customerallBookingsObject[] = (Object)[
                        'booking_customer_id' => $invoice_res->id,
                        'name' => $invoice_res->name,
                        'currency' =>  $invoice_res->currency,
                        'Invoices_booking' => $invoice_res->Invoices_booking,
                        'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                        'packages_booking' => $customersPackageBookings[$index]->packages_booking,
                        'packages_prices_sum' => number_format($customersPackageBookings[$index]->packages_prices_sum,2),
                        'all_bookings' => $invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking,
                        'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking),
                        'total_prices' => $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,
                        'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,2),
                        'Paid' => number_format($payment_today,2),
                        'remaining' => number_format($total_price - $payment_today,2)
                    ];
            }
            
        }
        
        if($request_data->date_type == 'data_year_wise'){
            
            $startOfYear = Carbon::now()->startOfYear();
            $start_date = $startOfYear->format('Y-m-d');
            $end_date = date('Y-m-d');
            
             $customersInvoices = DB::table('booking_customers')
                            ->leftJoin('add_manage_invoices', function ($join) use($start_date,$end_date) {
                                                    $join->on('add_manage_invoices.booking_customer_id', '=', 'booking_customers.id')
                                                        ->whereDate('add_manage_invoices.created_at','>=', $start_date)
                                                        ->whereDate('add_manage_invoices.created_at','<=', $end_date);
                                                })->where('booking_customers.id',$request->id)
                            ->where('booking_customers.customer_id',$userData->id)
                            // ->select('booking_customers.id', 'booking_customers.name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                            ->select('booking_customers.id', 'booking_customers.name','booking_customers.balance','booking_customers.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_all_Services) as Invoices_prices_sum'))
                            ->groupBy('booking_customers.id')
                            ->orderBy('booking_customers.id','asc')
                            ->get();
                            
            $customersPackageBookings = DB::table('booking_customers')
                            ->leftJoin('cart_details', function ($join) use($start_date,$end_date){
                                $join->on('booking_customers.id', '=', DB::raw('JSON_UNQUOTE(JSON_EXTRACT(cart_details.cart_total_data, "$.customer_id"))'))
                                ->whereDate('cart_details.created_at','>=', $start_date)
                                ->whereDate('cart_details.created_at','<=', $end_date);
                            })->where('booking_customers.id',$request->id)
                            ->where('booking_customers.customer_id',$userData->id)
                            ->select('booking_customers.id', 'booking_customers.name','booking_customers.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                            ->groupBy('booking_customers.id')
                            ->orderBy('booking_customers.id','asc')
                            ->get();
            
         
            $customerallBookingsObject = [];
            
            foreach($customersInvoices as $index => $invoice_res){
                $total_prices = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                
                $payment_today = DB::table('customer_ledger')->where('booking_customer',$invoice_res->id)
                                                   ->whereDate('date','>=', $start_date)
                                                   ->whereDate('date','<=', $end_date)
                                                   ->where('received_id','!=',NULL)
                                                   ->sum('payment');
                                                   
                $total_price = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                $customerallBookingsObject[] = (Object)[
                        'booking_customer_id' => $invoice_res->id,
                        'name' => $invoice_res->name,
                        'currency' =>  $invoice_res->currency,
                        'Invoices_booking' => $invoice_res->Invoices_booking,
                        'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                        'packages_booking' => $customersPackageBookings[$index]->packages_booking,
                        'packages_prices_sum' => number_format($customersPackageBookings[$index]->packages_prices_sum,2),
                        'all_bookings' => $invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking,
                        'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking),
                        'total_prices' => $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,
                        'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,2),
                        'Paid' => number_format($payment_today,2),
                        'remaining' => number_format($total_price - $payment_today,2)
                    ];
            }
            
        }
        
        if($request_data->date_type == 'data_wise'){
            $start_date                 = $request_data->start_date;
            $end_date                   = $request_data->end_date;
            $customersInvoices          = DB::table('booking_customers')
                                            ->leftJoin('add_manage_invoices', function ($join) use($start_date,$end_date) {
                                                                    $join->on('add_manage_invoices.booking_customer_id', '=', 'booking_customers.id')
                                                                        ->whereDate('add_manage_invoices.created_at','>=', $start_date)
                                                                        ->whereDate('add_manage_invoices.created_at','<=', $end_date);
                                                                })->where('booking_customers.id',$request->id)
                                            ->where('booking_customers.customer_id',$userData->id)
                                            // ->select('booking_customers.id', 'booking_customers.name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                                            ->select('booking_customers.id', 'booking_customers.name','booking_customers.balance','booking_customers.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_all_Services) as Invoices_prices_sum'))
                                            ->groupBy('booking_customers.id')
                                            ->orderBy('booking_customers.id','asc')
                                            ->get();
            // dd($customersInvoices);
            $customersPackageBookings   = DB::table('booking_customers')
                                            ->leftJoin('cart_details', function ($join) use($start_date,$end_date){
                                                $join->on('booking_customers.id', '=', DB::raw('JSON_UNQUOTE(JSON_EXTRACT(cart_details.cart_total_data, "$.customer_id"))'))
                                                ->whereDate('cart_details.created_at','>=', $start_date)
                                                ->whereDate('cart_details.created_at','<=', $end_date);
                                            })->where('booking_customers.id',$request->id)
                                            ->where('booking_customers.customer_id',$userData->id)
                                            ->select('booking_customers.id', 'booking_customers.name','booking_customers.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                                            ->groupBy('booking_customers.id')
                                            ->orderBy('booking_customers.id','asc')
                                            ->get();
            // dd($customersPackageBookings);
            $customerallBookingsObject  = [];
            
            foreach($customersInvoices as $index => $invoice_res){
                $total_prices   = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                
                $payment_today  = DB::table('customer_ledger')->where('booking_customer',$invoice_res->id)
                                   ->whereDate('date','>=', $start_date)
                                   ->whereDate('date','<=', $end_date)
                                   ->where('received_id','!=',NULL)
                                   ->sum('payment');
                $total_price    = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                $customerallBookingsObject[] = (Object)[
                    'booking_customer_id' => $invoice_res->id,
                    'name' => $invoice_res->name,
                    'currency' =>  $invoice_res->currency,
                    'Invoices_booking' => $invoice_res->Invoices_booking,
                    'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                    'packages_booking' => $customersPackageBookings[$index]->packages_booking,
                    'packages_prices_sum' => number_format($customersPackageBookings[$index]->packages_prices_sum,2),
                    'all_bookings' => $invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking,
                    'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking),
                    'total_prices' => $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,
                    'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,2),
                    'Paid' => number_format($payment_today,2),
                    'remaining' => number_format($total_price - $payment_today,2)
                ];
            }
        }
        
        // dd($customersInvoices);
        
        if($request_data->filter_type == 'total_revenue'){
            $customerallBookingsObject = new Collection($customerallBookingsObject);
            
            $customerallBookingsObject = $customerallBookingsObject->sortByDesc('total_prices');
            
            $customerallBookingsObject = $customerallBookingsObject->values();
            
            $customerallBookingsObject = $customerallBookingsObject->toArray();
            
            if(sizeOf($customerallBookingsObject) >= 4){
                $limitedCustomerData = array_slice($customerallBookingsObject, 0, 4);
            }else{
                $limitedCustomerData = $customerallBookingsObject;
            }
            
            $series_data        = [];
            $categories_data    = [];
            
            if($request_data->date_type == 'data_today_wise'){
            
                $date = date('Y-m-d');
                foreach($limitedCustomerData as $cust_res){
                    
                    $customer_booking_data = [$cust_res->total_prices];
                    $series_data[] = [
                            'name' => $cust_res->name,
                            'data' => $customer_booking_data
                        ];
                }
                
                $categories_data = [$date];
            }
            
            if($request_data->date_type == 'data_Yesterday_wise'){
            
                $date = date('Y-m-d',strtotime("-1 days"));
                foreach($limitedCustomerData as $cust_res){
                    
                    $customer_booking_data = [$cust_res->total_prices];
                    $series_data[] = [
                            'name' => $cust_res->name,
                            'data' => $customer_booking_data
                        ];
                }
                
                $categories_data = [$date];
            }
            
            if($request_data->date_type == 'data_week_wise'){
               $today = Carbon::now();
                
                // Create a CarbonPeriod instance for the current week
               $startOfWeek = $today->startOfWeek()->toDateString();
                $endOfWeek = $today->endOfWeek()->toDateString();
                
                // Create a CarbonPeriod instance for the current week
                $period = CarbonPeriod::create($startOfWeek, $endOfWeek);
                
                // Get the dates of the current week as an array
                $datesOfWeek = [];
                foreach ($period as $date) {
                    $datesOfWeek[] = $date->toDateString();
                }
                
                // Get Data for Agent 
                
                foreach($limitedCustomerData as $cust_res){
                    
                    // Loop On DatesOfWeek
                    
                    $customer_booking_data = [];
                    foreach($datesOfWeek as $date_res){
                        $customerInvoices = DB::table('add_manage_invoices')->where('booking_customer_id',$cust_res->booking_customer_id)
                                                                          ->whereDate('created_at',$date_res)
                                                                          ->Sum('total_sale_price_all_Services');

                        $customerPackageBookings =  DB::table('cart_details')->whereJsonContains('cart_total_data->customer_id',"$cust_res->booking_customer_id")
                                                                  ->whereDate('created_at',$date_res)
                                                                  ->Sum('tour_total_price');
                                                                   
                        $total_booking_price = $customerInvoices + $customerPackageBookings;
                                        
                      
                        
                        $customer_booking_data[] = floor($total_booking_price * 100) / 100;
                    }
                    
                    $series_data[] = [
                            'name' => $cust_res->name,
                            'data' => $customer_booking_data
                        ];
                }
                
                // Get Data For Categories
                
                $dayNamesOfWeek = [];
                for ($day = 0; $day < 7; $day++) {
                    $dayNamesOfWeek[] = $today->startOfWeek()->addDays($day)->format('l'); // 'l' format represents the full day name
                }
                
                $categories_data = $dayNamesOfWeek;
            }
            
            if($request_data->date_type == 'data_month_wise'){
                
                foreach($limitedCustomerData as $cust_res){
                    
                    // Start Date Of Month
                    // $currentDate = Carbon::now();
                    $startDate = Carbon::now()->startOfMonth();
                    // $startDate = $currentDate->subMonth()->firstOfMonth();
                    $startDateWeek = $startDate->toDateString();
                    $endDate = $startDate->copy()->addDays(6);
                    $endDateWeek = $endDate->toDateString();
                    
                    $customer_booking_data = [];
                    for($i=1; $i<=5; $i++){
                        // Add 7 days to the start date
                        
                        $customerInvoices = DB::table('add_manage_invoices')->where('booking_customer_id',$cust_res->booking_customer_id)
                                                                          ->whereDate('add_manage_invoices.created_at','>=', $startDate)
                                                                          ->whereDate('add_manage_invoices.created_at','<=', $endDate)
                                                                          ->Sum('total_sale_price_all_Services');

                        $customerPackageBookings =  DB::table('cart_details')->whereJsonContains('cart_total_data->customer_id',"$cust_res->booking_customer_id")
                                                                  ->whereDate('cart_details.created_at','>=', $startDate)
                                                                  ->whereDate('cart_details.created_at','<=', $endDate)
                                                                  ->Sum('tour_total_price');
                                                                   
                        $total_booking_price = $customerInvoices + $customerPackageBookings;
                                        
                      
                        
                        $customer_booking_data[] = floor($total_booking_price * 100) / 100;;
                        
                        
                        $startDate = $endDate->copy()->addDays(1);
                        if($i == '4'){
                            $endDate = $startDate->copy()->addDays(2);
                        }else{
                             $endDate = $startDate->copy()->addDays(6);
                        }
                        
                        $startDateWeek = $startDate->toDateString();
                        $endDateWeek = $endDate->toDateString();
                    }
                    
                    $series_data[] = [
                            'name' => $cust_res->name,
                            'data' => $customer_booking_data
                        ];
                }
                    
               
                    // dd($startDateWeek,$endDateWeek);
                //   print_r($weekDates);
                //   die;
                $categories_data = ['1st Week','2nd Week','3rd Week','4th Week','5th Week'];
            }
            
            if($request_data->date_type == 'data_wise'){
                $startDate      = Carbon::parse($request_data->start_date);
                $currentDate    = $startDate->copy();
                $endDate        = Carbon::parse($request_data->end_date);
                $dateRange      = [];
                
                while ($currentDate->lte($endDate)) {
                    $dateRange[] = $currentDate->format('Y-m-d');
                    $currentDate->addDay();
                }
                
                $cust_res = $limitedCustomerData[0];
                
                foreach ($dateRange as $date) {
                    $series_data                = [];
                    $customerInvoices           = DB::table('add_manage_invoices')->where('add_manage_invoices.booking_customer_id',$cust_res->booking_customer_id)
                                                    ->whereDate('add_manage_invoices.created_at', '=', $date)->Sum('total_sale_price_all_Services');
                    $customerPackageBookings    =  DB::table('cart_details')->whereJsonContains('cart_total_data->customer_id',$cust_res->booking_customer_id)
                                                    ->whereDate('cart_details.created_at', '=', $date)->Sum('tour_total_price');
                    $total_booking_price        = $customerInvoices + $customerPackageBookings;
                    if($total_booking_price > 0){
                        $customer_booking_data[]    = $total_booking_price;
                        $series_data[]              = [
                            'name'                  => $cust_res->name,
                            'data'                  => $customer_booking_data
                        ];
                    }else{
                        // dd($total_booking_price);
                        $customer_booking_data[]   = 0;
                        $series_data[]              = [
                            'name'                  => $cust_res->name,
                            'data'                  => $customer_booking_data
                        ];
                    }
                    $categories_data[]              = $date;
                }
            }
            
            // dd($limitedCustomerData);
            
            return response()->json([
                'status'            => 'success',
                'data'              => $limitedCustomerData,
                'series_data'       => $series_data,
                'categories_data'   => $categories_data,
            ]);
        }else{
            $customerallBookingsObject = new Collection($customerallBookingsObject);
            
            $customerallBookingsObject = $customerallBookingsObject->sortByDesc('all_bookings');
            
            $customerallBookingsObject = $customerallBookingsObject->values();
            
            $customerallBookingsObject = $customerallBookingsObject->toArray();
            
            if(sizeOf($customerallBookingsObject) >= 4){
                $limitedCustomerData = array_slice($customerallBookingsObject, 0, 4);
            }else{
                $limitedCustomerData = $limitedCustomerData;
            }
         
            $series_data = [];
            $categories_data = [];
            
            if($request_data->date_type == 'data_today_wise'){
            
                $date = date('Y-m-d');
                foreach($limitedCustomerData as $cust_res){
                    
                    $customer_booking_data = [$cust_res->all_bookings];
                    $series_data[] = [
                            'name' => $cust_res->name,
                            'data' => $customer_booking_data
                        ];
                }
                
                $categories_data = [$date];
            }
            
            if($request_data->date_type == 'data_Yesterday_wise'){
            
                $date = date('Y-m-d',strtotime("-1 days"));
                foreach($limitedCustomerData as $cust_res){
                    
                    $customer_booking_data = [$cust_res->all_bookings];
                    $series_data[] = [
                            'name' => $cust_res->name,
                            'data' => $customer_booking_data
                        ];
                }
                
                $categories_data = [$date];
            }
            
            if($request_data->date_type == 'data_week_wise'){
               $today = Carbon::now();
                
                // Create a CarbonPeriod instance for the current week
               $startOfWeek = $today->startOfWeek()->toDateString();
                $endOfWeek = $today->endOfWeek()->toDateString();
                
                // Create a CarbonPeriod instance for the current week
                $period = CarbonPeriod::create($startOfWeek, $endOfWeek);
                
                // Get the dates of the current week as an array
                $datesOfWeek = [];
                foreach ($period as $date) {
                    $datesOfWeek[] = $date->toDateString();
                }
                
                // Get Data for Agent 
                
                foreach($limitedCustomerData as $cust_res){
                    
                    // Loop On DatesOfWeek
                    
                    $customer_booking_data = [];
                    foreach($datesOfWeek as $date_res){
                        $customerInvoices = DB::table('add_manage_invoices')->where('booking_customer_id',$cust_res->booking_customer_id)
                                                                          ->whereDate('created_at',$date_res)
                                                                          ->count();

                        $customerPackageBookings =  DB::table('cart_details')->whereJsonContains('cart_total_data->customer_id',"$cust_res->booking_customer_id")
                                                                  ->whereDate('created_at',$date_res)
                                                                  ->count();
                                                                   
                        $total_booking_price = $customerInvoices + $customerPackageBookings;
                                        
                      
                        
                        $customer_booking_data[] = floor($total_booking_price * 100) / 100;
                    }
                    
                    $series_data[] = [
                            'name' => $cust_res->name,
                            'data' => $customer_booking_data
                        ];
                }
                
                // Get Data For Categories
                
                $dayNamesOfWeek = [];
                for ($day = 0; $day < 7; $day++) {
                    $dayNamesOfWeek[] = $today->startOfWeek()->addDays($day)->format('l'); // 'l' format represents the full day name
                }
                
                $categories_data = $dayNamesOfWeek;
            }
            
            if($request_data->date_type == 'data_month_wise'){
                
                foreach($limitedCustomerData as $cust_res){
                    
                    // Start Date Of Month
                    // $currentDate = Carbon::now();
                    $startDate = Carbon::now()->startOfMonth();
                    // $startDate = $currentDate->subMonth()->firstOfMonth();
                    $startDateWeek = $startDate->toDateString();
                    $endDate = $startDate->copy()->addDays(6);
                    $endDateWeek = $endDate->toDateString();
                    
                    $customer_booking_data = [];
                    for($i=1; $i<=5; $i++){
                        // Add 7 days to the start date
                        
                        $customerInvoices = DB::table('add_manage_invoices')->where('booking_customer_id',$cust_res->booking_customer_id)
                                                                          ->whereDate('add_manage_invoices.created_at','>=', $startDate)
                                                                          ->whereDate('add_manage_invoices.created_at','<=', $endDate)
                                                                          ->count();

                        $customerPackageBookings =  DB::table('cart_details')->whereJsonContains('cart_total_data->customer_id',"$cust_res->booking_customer_id")
                                                                  ->whereDate('cart_details.created_at','>=', $startDate)
                                                                  ->whereDate('cart_details.created_at','<=', $endDate)
                                                                  ->count();
                                                                   
                        $total_booking_price = $customerInvoices + $customerPackageBookings;
                                        
                      
                        
                        $customer_booking_data[] = floor($total_booking_price * 100) / 100;;
                        
                        
                        $startDate = $endDate->copy()->addDays(1);
                        if($i == '4'){
                            $endDate = $startDate->copy()->addDays(2);
                        }else{
                             $endDate = $startDate->copy()->addDays(6);
                        }
                        
                        $startDateWeek = $startDate->toDateString();
                        $endDateWeek = $endDate->toDateString();
                    }
                    
                    $series_data[] = [
                            'name' => $cust_res->name,
                            'data' => $customer_booking_data
                        ];
                }
                    
               
                    // dd($startDateWeek,$endDateWeek);
                //   print_r($weekDates);
                //   die;
                $categories_data = ['1st Week','2nd Week','3rd Week','4th Week','5th Week'];
            }
            
            return response()->json([
                'status'            => 'success',
                'data'              => $limitedCustomerData,
                'series_data'       => $series_data,
                'categories_data'   => $categories_data,
            ]);
        }
    }
    
    public function top_customer_booking_subUser(Request $request){
        $userData   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
                        
         $customersInvoices = DB::table('booking_customers')
                        ->leftJoin('add_manage_invoices','add_manage_invoices.booking_customer_id','booking_customers.id')
                        ->where('booking_customers.customer_id',$userData->id)
                        ->where('booking_customers.SU_id',$request->SU_id)
                        // ->select('booking_customers.id', 'booking_customers.name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                        ->select('booking_customers.id', 'booking_customers.name','booking_customers.balance','booking_customers.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_all_Services) as Invoices_prices_sum'))
                        ->groupBy('booking_customers.id')
                        ->orderBy('booking_customers.id','asc')
                        ->get();
                        
        $customersPackageBookings = DB::table('booking_customers')
                        ->leftJoin('cart_details', function ($join) {
                            $join->on('booking_customers.id', '=', DB::raw('JSON_UNQUOTE(JSON_EXTRACT(cart_details.cart_total_data, "$.customer_id"))'));
                        })
                        ->where('booking_customers.customer_id',$userData->id)
                        ->where('booking_customers.SU_id',$request->SU_id)
                        ->select('booking_customers.id', 'booking_customers.name','booking_customers.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                        ->groupBy('booking_customers.id')
                        ->orderBy('booking_customers.id','asc')
                        ->get();
        
     
        $customerallBookingsObject = [];
        
        foreach($customersInvoices as $index => $invoice_res){
            $total_prices = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
            $customerallBookingsObject[] = (Object)[
                    'booking_customer_id' => $invoice_res->id,
                    'name' => $invoice_res->name,
                    'currency' =>  $invoice_res->currency,
                    'Invoices_booking' => $invoice_res->Invoices_booking,
                    'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                    'packages_booking' => $customersPackageBookings[$index]->packages_booking,
                    'packages_prices_sum' => number_format($customersPackageBookings[$index]->packages_prices_sum,2),
                    'all_bookings' => $invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking,
                    'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking),
                    'total_prices' => $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,
                    'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,2),
                    'Paid' => number_format($total_prices - $invoice_res->balance,2),
                    'remaining' => number_format($invoice_res->balance,2)
                ];
        }
        
        $customerallBookingsObject = new Collection($customerallBookingsObject);

        $customerallBookingsObject = $customerallBookingsObject->sortByDesc('total_prices');
        
        $customerallBookingsObject = $customerallBookingsObject->values();
        
        $customerallBookingsObject = $customerallBookingsObject->toArray();
        
        if(sizeOf($customerallBookingsObject) >= 4){
            $limitedCustomerData = array_slice($customerallBookingsObject, 0, 4);
        }else{
            $limitedCustomerData = $customerallBookingsObject ?? '';
        }
        
        $series_data        = [];
        $categories_data    = [];
        $currentYear        = date('Y');
        $monthsData         = [];
        
        for ($month = 1; $month <= 12; $month++) {
            
            $startOfMonth = Carbon::create($currentYear, $month, 1)->startOfMonth();
            $endOfMonth = Carbon::create($currentYear, $month, 1)->endOfMonth();
             
            $categories_data[] = $startOfMonth->format('F');
             
            $startOfMonth = $startOfMonth->format('Y-m-d');
            $endOfMonth = $endOfMonth->format('Y-m-d');
            
            $monthsData[] = (Object)[
                'month' => $month,
                'start_date' => $startOfMonth,
                'end_date' => $endOfMonth,
            ];
        }
        
        if(isset($limitedCustomerData) && $limitedCustomerData != null && $limitedCustomerData != ''){
            foreach($limitedCustomerData as $cust_res){
                
                
                
                $customer_booking_data = [];
                foreach($monthsData as $month_res){
                    // Add 7 days to the start date
                    
                    $customerInvoices = DB::table('add_manage_invoices')->where('booking_customer_id',$cust_res->booking_customer_id)->where('SU_id',$request->SU_id)
                                                                      ->whereDate('add_manage_invoices.created_at','>=', $month_res->start_date)
                                                                      ->whereDate('add_manage_invoices.created_at','<=', $month_res->end_date)
                                                                      ->Sum('total_sale_price_all_Services');
    
                    $customerPackageBookings =  DB::table('cart_details')->where('SU_id',$request->SU_id)->whereJsonContains('cart_total_data->customer_id',"$cust_res->booking_customer_id")
                                                              ->whereDate('cart_details.created_at','>=', $month_res->start_date)
                                                              ->whereDate('cart_details.created_at','<=', $month_res->end_date)
                                                              ->Sum('tour_total_price');
                                                               
                    $total_booking_price = $customerInvoices + $customerPackageBookings;
                                    
                  
                    
                    $customer_booking_data[] = floor($total_booking_price * 100) / 100;
                   
                }
                
                $series_data[] = [
                        'name' => $cust_res->name,
                        'data' => $customer_booking_data
                    ];
            }
        }
        
        return response()->json([
                    'status' => 'success',
                    'data' => $limitedCustomerData,
                    'series_data' => $series_data,
                    'categories_data' => $categories_data,
                ]);
    }
    
    public function top_customer_booking(Request $request){
        $userData                   = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        
        $customersInvoices          = DB::table('booking_customers')
                                        ->leftJoin('add_manage_invoices','add_manage_invoices.booking_customer_id','booking_customers.id')
                                        ->where('booking_customers.customer_id',$userData->id)
                                        // ->select('booking_customers.id', 'booking_customers.name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                                        ->select('booking_customers.id', 'booking_customers.name','booking_customers.balance','booking_customers.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_all_Services) as Invoices_prices_sum'))
                                        ->groupBy('booking_customers.id')
                                        ->orderBy('booking_customers.id','asc')
                                        ->get();
        $customersPackageBookings   = DB::table('booking_customers')
                                        ->leftJoin('cart_details', function ($join) {
                                            $join->on('booking_customers.id', '=', DB::raw('JSON_UNQUOTE(JSON_EXTRACT(cart_details.cart_total_data, "$.customer_id"))'));
                                        })
                                        ->where('booking_customers.customer_id',$userData->id)
                                        ->select('booking_customers.id', 'booking_customers.name','booking_customers.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                                        ->groupBy('booking_customers.id')
                                        ->orderBy('booking_customers.id','asc')
                                        ->get();
        // Season
        if($userData->id == 4 || $userData->id == 54){
            $today_Date                 = date('Y-m-d');
            if(isset($request->season_Id)){
                if($request->season_Id == 'all_Seasons'){
                    $season_Details     = null;
                }elseif($request->season_Id > 0){
                    $season_Details     = DB::table('add_Seasons')->where('customer_id', $userData->id)->where('id', $request->season_Id)->first();
                }else{
                    $season_Details     = null;
                }
            }else{
                $season_Details         = DB::table('add_Seasons')->where('customer_id', $userData->id)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
            }
            
            if($season_Details != null){
                $start_Date                 = $season_Details->start_Date;
                $end_Date                   = $season_Details->end_Date;
                $customersInvoices          = DB::table('booking_customers')
                                                ->leftJoin('add_manage_invoices','add_manage_invoices.booking_customer_id','booking_customers.id')
                                                ->where('booking_customers.customer_id',$userData->id)
                                                ->whereBetween('booking_customers.created_at', [$start_Date, $end_Date])
                                                // ->select('booking_customers.id', 'booking_customers.name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                                                ->select('booking_customers.id', 'booking_customers.name','booking_customers.balance','booking_customers.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_all_Services) as Invoices_prices_sum'))
                                                ->groupBy('booking_customers.id')
                                                ->orderBy('booking_customers.id','asc')
                                                ->get();
                $customersPackageBookings   = DB::table('booking_customers')
                                                ->leftJoin('cart_details', function ($join) {
                                                    $join->on('booking_customers.id', '=', DB::raw('JSON_UNQUOTE(JSON_EXTRACT(cart_details.cart_total_data, "$.customer_id"))'));
                                                })
                                                ->where('booking_customers.customer_id',$userData->id)
                                                ->whereBetween('booking_customers.created_at', [$start_Date, $end_Date])
                                                ->select('booking_customers.id', 'booking_customers.name','booking_customers.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                                                ->groupBy('booking_customers.id')
                                                ->orderBy('booking_customers.id','asc')
                                                ->get();
            }
        }
        // Season
        
        $customerallBookingsObject = [];
        
        foreach($customersInvoices as $index => $invoice_res){
            $total_prices = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
            $customerallBookingsObject[] = (Object)[
                'booking_customer_id' => $invoice_res->id,
                'name' => $invoice_res->name,
                'currency' =>  $invoice_res->currency,
                'Invoices_booking' => $invoice_res->Invoices_booking,
                'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                'packages_booking' => $customersPackageBookings[$index]->packages_booking,
                'packages_prices_sum' => number_format($customersPackageBookings[$index]->packages_prices_sum,2),
                'all_bookings' => $invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking,
                'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking),
                'total_prices' => $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,
                'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,2),
                'Paid' => number_format($total_prices - $invoice_res->balance,2),
                'remaining' => number_format($invoice_res->balance,2)
            ];
        }
        
        $customerallBookingsObject = new Collection($customerallBookingsObject);
        
        $customerallBookingsObject = $customerallBookingsObject->sortByDesc('total_prices');
        
        $customerallBookingsObject = $customerallBookingsObject->values();
        
        $customerallBookingsObject = $customerallBookingsObject->toArray();
        
        if(sizeOf($customerallBookingsObject) >= 4){
            $limitedCustomerData = array_slice($customerallBookingsObject, 0, 4);
        }else{
            $limitedCustomerData = '';
        }
        
        $series_data = [];
        $categories_data = [];
        
        $currentYear = date('Y');
        $monthsData = [];
        
        for ($month = 1; $month <= 12; $month++) {
            
             $startOfMonth = Carbon::create($currentYear, $month, 1)->startOfMonth();
             $endOfMonth = Carbon::create($currentYear, $month, 1)->endOfMonth();
             
              $categories_data[] = $startOfMonth->format('F');
             
             $startOfMonth = $startOfMonth->format('Y-m-d');
             $endOfMonth = $endOfMonth->format('Y-m-d');

            $monthsData[] = (Object)[
                'month' => $month,
                'start_date' => $startOfMonth,
                'end_date' => $endOfMonth,
            ];
        }
        
        if(isset($limitedCustomerData) && $limitedCustomerData != null && $limitedCustomerData != ''){
            foreach($limitedCustomerData as $cust_res){
                
                $customer_booking_data = [];
                foreach($monthsData as $month_res){
                    // Add 7 days to the start date
                    
                    $customerInvoices = DB::table('add_manage_invoices')->where('booking_customer_id',$cust_res->booking_customer_id)
                                                                      ->whereDate('add_manage_invoices.created_at','>=', $month_res->start_date)
                                                                      ->whereDate('add_manage_invoices.created_at','<=', $month_res->end_date)
                                                                      ->Sum('total_sale_price_all_Services');
    
                    $customerPackageBookings =  DB::table('cart_details')->whereJsonContains('cart_total_data->customer_id',"$cust_res->booking_customer_id")
                                                              ->whereDate('cart_details.created_at','>=', $month_res->start_date)
                                                              ->whereDate('cart_details.created_at','<=', $month_res->end_date)
                                                              ->Sum('tour_total_price');
                                                               
                    $total_booking_price = $customerInvoices + $customerPackageBookings;
                                    
                  
                    
                    $customer_booking_data[] = floor($total_booking_price * 100) / 100;
                   
                }
                
                $series_data[] = [
                    'name' => $cust_res->name,
                    'data' => $customer_booking_data
                ];
            }
        }
        
        return response()->json([
            'status' => 'success',
            'data' => $limitedCustomerData,
            'series_data' => $series_data,
            'categories_data' => $categories_data,
        ]);
    }
    
    public function all_agents_booking(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
                        
         $agentsInvoices = DB::table('Agents_detail')
                        ->leftJoin('add_manage_invoices','add_manage_invoices.agent_Id','Agents_detail.id')
                        ->where('Agents_detail.customer_id',$userData->id)
                        // ->select('Agents_detail.id', 'Agents_detail.agent_Name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                        ->select('Agents_detail.id', 'Agents_detail.agent_Name','Agents_detail.company_name','Agents_detail.balance','Agents_detail.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_all_Services) as Invoices_prices_sum'))
                        ->groupBy('Agents_detail.id')
                        ->orderBy('Agents_detail.id','asc')
                        ->get();
                        
        $agentsPackageBookings = DB::table('Agents_detail')
                        ->leftJoin('cart_details','cart_details.agent_name','Agents_detail.id')
                        ->where('Agents_detail.customer_id',$userData->id)
                        ->select('Agents_detail.id', 'Agents_detail.agent_Name','Agents_detail.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                        ->groupBy('Agents_detail.id')
                        ->orderBy('Agents_detail.id','asc')
                        ->get();
                        
        $agentallBookingsObject = [];
        
        foreach($agentsInvoices as $index => $invoice_res){
            $total_prices = $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum;
            $agentallBookingsObject[] = (Object)[
                    'agent_id' => $invoice_res->id,
                    'agent_Name' => $invoice_res->agent_Name,
                    'company_name' => $invoice_res->company_name,
                    'currency' =>  $invoice_res->currency,
                    'Invoices_booking' => $invoice_res->Invoices_booking,
                    'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                    'packages_booking' => $agentsPackageBookings[$index]->packages_booking,
                    'packages_prices_sum' => number_format($agentsPackageBookings[$index]->packages_prices_sum,2),
                    'all_bookings' => $invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking,
                    'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $agentsPackageBookings[$index]->packages_booking),
                    'total_prices' => $invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,
                    'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $agentsPackageBookings[$index]->packages_prices_sum,2),
                    'Paid' => number_format($total_prices - $invoice_res->balance,2),
                    'remaining' => number_format($invoice_res->balance,2)
                ];
        }
        
        $agentallBookingsObject = new Collection($agentallBookingsObject);

        $agentallBookingsObject = $agentallBookingsObject->sortByDesc('total_prices');
        
        // Reindex the collection starting from 0
        $agentallBookingsObject = $agentallBookingsObject->values();
        
        $agentallBookingsObject = $agentallBookingsObject->toArray();

       return response()->json([
                    'status' => 'success',
                    'data' => $agentallBookingsObject,
                ]);
    }
    
    public function customer_booking_filter_subUser(Request $request){
        $userData               = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        $request_data           = json_decode($request->request_data);
        $agentallBookingsObject = [];
        
        if($request_data->date_type == 'all_data'){
            $customersInvoices = DB::table('booking_customers')
                            ->leftJoin('add_manage_invoices','add_manage_invoices.booking_customer_id','booking_customers.id')
                            ->where('booking_customers.customer_id',$userData->id)
                            ->where('booking_customers.SU_id',$request->SU_id)
                            // ->select('booking_customers.id', 'booking_customers.name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                            ->select('booking_customers.id', 'booking_customers.name','booking_customers.balance','booking_customers.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_all_Services) as Invoices_prices_sum'))
                            ->groupBy('booking_customers.id')
                            ->orderBy('booking_customers.id','asc')
                            ->get();
                            
            $customersPackageBookings = DB::table('booking_customers')
                            ->leftJoin('cart_details', function ($join) {
                                $join->on('booking_customers.id', '=', DB::raw('JSON_UNQUOTE(JSON_EXTRACT(cart_details.cart_total_data, "$.customer_id"))'));
                            })
                            ->where('booking_customers.customer_id',$userData->id)
                            ->where('booking_customers.SU_id',$request->SU_id)
                            ->select('booking_customers.id', 'booking_customers.name','booking_customers.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                            ->groupBy('booking_customers.id')
                            ->orderBy('booking_customers.id','asc')
                            ->get();
            
         
            $customerallBookingsObject = [];
            
            foreach($customersInvoices as $index => $invoice_res){
                $total_prices = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                $customerallBookingsObject[] = (Object)[
                        'booking_customer_id' => $invoice_res->id,
                        'name' => $invoice_res->name,
                        'currency' =>  $invoice_res->currency,
                        'Invoices_booking' => $invoice_res->Invoices_booking,
                        'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                        'packages_booking' => $customersPackageBookings[$index]->packages_booking,
                        'packages_prices_sum' => number_format($customersPackageBookings[$index]->packages_prices_sum,2),
                        'all_bookings' => $invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking,
                        'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking),
                        'total_prices' => $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,
                        'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,2),
                        'Paid' => number_format($total_prices - $invoice_res->balance,2),
                        'remaining' => number_format($invoice_res->balance,2)
                    ];
            }
        }
        
        if($request_data->date_type == 'data_today_wise'){
            
             $today_date = date('Y-m-d');
             $customersInvoices = DB::table('booking_customers')
                            ->leftJoin('add_manage_invoices', function ($join) use($today_date) {
                                                    $join->on('add_manage_invoices.booking_customer_id', '=', 'booking_customers.id')
                                                        ->whereDate('add_manage_invoices.created_at', $today_date);
                                                })
                            ->where('booking_customers.customer_id',$userData->id)
                            ->where('booking_customers.SU_id',$request->SU_id)
                            // ->select('booking_customers.id', 'booking_customers.name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                            ->select('booking_customers.id', 'booking_customers.name','booking_customers.balance','booking_customers.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_all_Services) as Invoices_prices_sum'))
                            ->groupBy('booking_customers.id')
                            ->orderBy('booking_customers.id','asc')
                            ->get();
                            
            $customersPackageBookings = DB::table('booking_customers')
                            ->leftJoin('cart_details', function ($join) use($today_date){
                                $join->on('booking_customers.id', '=', DB::raw('JSON_UNQUOTE(JSON_EXTRACT(cart_details.cart_total_data, "$.customer_id"))'))
                                ->whereDate('cart_details.created_at', $today_date);
                            })
                            ->where('booking_customers.customer_id',$userData->id)
                            ->where('booking_customers.SU_id',$request->SU_id)
                            ->select('booking_customers.id', 'booking_customers.name','booking_customers.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                            ->groupBy('booking_customers.id')
                            ->orderBy('booking_customers.id','asc')
                            ->get();
            
         
            $customerallBookingsObject = [];
            
            foreach($customersInvoices as $index => $invoice_res){
                $total_prices = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                
                $payment_today = DB::table('customer_ledger')->where('booking_customer',$invoice_res->id)->where('SU_id',$request->SU_id)
                                                   ->whereDate('date',$today_date)
                                                  ->where('received_id','!=',NULL)
                                                   ->sum('payment');
                                                   
                $total_price = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                $customerallBookingsObject[] = (Object)[
                        'booking_customer_id' => $invoice_res->id,
                        'name' => $invoice_res->name,
                        'currency' =>  $invoice_res->currency,
                        'Invoices_booking' => $invoice_res->Invoices_booking,
                        'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                        'packages_booking' => $customersPackageBookings[$index]->packages_booking,
                        'packages_prices_sum' => number_format($customersPackageBookings[$index]->packages_prices_sum,2),
                        'all_bookings' => $invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking,
                        'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking),
                        'total_prices' => $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,
                        'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,2),
                        'Paid' => number_format($payment_today,2),
                        'remaining' => number_format($total_price - $payment_today,2)
                    ];
            }
        }
        
        if($request_data->date_type == 'data_Yesterday_wise'){
            
             $date = date('Y-m-d',strtotime("-1 days"));
             $customersInvoices = DB::table('booking_customers')
                            ->leftJoin('add_manage_invoices', function ($join) use($date) {
                                                    $join->on('add_manage_invoices.booking_customer_id', '=', 'booking_customers.id')
                                                        ->whereDate('add_manage_invoices.created_at', $date);
                                                })
                            ->where('booking_customers.customer_id',$userData->id)
                            ->where('booking_customers.SU_id',$request->SU_id)
                            // ->select('booking_customers.id', 'booking_customers.name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                            ->select('booking_customers.id', 'booking_customers.name','booking_customers.balance','booking_customers.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_all_Services) as Invoices_prices_sum'))
                            ->groupBy('booking_customers.id')
                            ->orderBy('booking_customers.id','asc')
                            ->get();
                            
            $customersPackageBookings = DB::table('booking_customers')
                            ->leftJoin('cart_details', function ($join) use($date){
                                $join->on('booking_customers.id', '=', DB::raw('JSON_UNQUOTE(JSON_EXTRACT(cart_details.cart_total_data, "$.customer_id"))'))
                                ->whereDate('cart_details.created_at', $date);
                            })
                            ->where('booking_customers.customer_id',$userData->id)
                            ->where('booking_customers.SU_id',$request->SU_id)
                            ->select('booking_customers.id', 'booking_customers.name','booking_customers.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                            ->groupBy('booking_customers.id')
                            ->orderBy('booking_customers.id','asc')
                            ->get();
            
         
            $customerallBookingsObject = [];
            
            foreach($customersInvoices as $index => $invoice_res){
                $total_prices = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                
                $payment_today = DB::table('customer_ledger')->where('booking_customer',$invoice_res->id)->where('SU_id',$request->SU_id)
                                                   ->whereDate('date',$date)
                                                  ->where('received_id','!=',NULL)
                                                   ->sum('payment');
                                                   
                $total_price = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                $customerallBookingsObject[] = (Object)[
                        'booking_customer_id' => $invoice_res->id,
                        'name' => $invoice_res->name,
                        'currency' =>  $invoice_res->currency,
                        'Invoices_booking' => $invoice_res->Invoices_booking,
                        'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                        'packages_booking' => $customersPackageBookings[$index]->packages_booking,
                        'packages_prices_sum' => number_format($customersPackageBookings[$index]->packages_prices_sum,2),
                        'all_bookings' => $invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking,
                        'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking),
                        'total_prices' => $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,
                        'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,2),
                        'Paid' => number_format($payment_today,2),
                        'remaining' => number_format($total_price - $payment_today,2)
                    ];
            }
            
        }
        
        if($request_data->date_type == 'data_week_wise'){
            
            $startOfWeek = Carbon::now()->startOfWeek();
            $start_date = $startOfWeek->format('Y-m-d');
            $end_date = date('Y-m-d');
            
             $customersInvoices = DB::table('booking_customers')
                            ->leftJoin('add_manage_invoices', function ($join) use($start_date,$end_date) {
                                                    $join->on('add_manage_invoices.booking_customer_id', '=', 'booking_customers.id')
                                                        ->whereDate('add_manage_invoices.created_at','>=', $start_date)
                                                        ->whereDate('add_manage_invoices.created_at','<=', $end_date);
                                                })
                            ->where('booking_customers.customer_id',$userData->id)
                            ->where('booking_customers.SU_id',$request->SU_id)
                            // ->select('booking_customers.id', 'booking_customers.name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                            ->select('booking_customers.id', 'booking_customers.name','booking_customers.balance','booking_customers.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_all_Services) as Invoices_prices_sum'))
                            ->groupBy('booking_customers.id')
                            ->orderBy('booking_customers.id','asc')
                            ->get();
                            
            $customersPackageBookings = DB::table('booking_customers')
                            ->leftJoin('cart_details', function ($join) use($start_date,$end_date){
                                $join->on('booking_customers.id', '=', DB::raw('JSON_UNQUOTE(JSON_EXTRACT(cart_details.cart_total_data, "$.customer_id"))'))
                                ->whereDate('cart_details.created_at','>=', $start_date)
                                ->whereDate('cart_details.created_at','<=', $end_date);
                            })
                            ->where('booking_customers.customer_id',$userData->id)
                            ->where('booking_customers.SU_id',$request->SU_id)
                            ->select('booking_customers.id', 'booking_customers.name','booking_customers.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                            ->groupBy('booking_customers.id')
                            ->orderBy('booking_customers.id','asc')
                            ->get();
            
         
            $customerallBookingsObject = [];
            
            foreach($customersInvoices as $index => $invoice_res){
                $total_prices = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                
                $payment_today = DB::table('customer_ledger')->where('booking_customer',$invoice_res->id)->where('SU_id',$request->SU_id)
                                                   ->whereDate('date','>=', $start_date)
                                                   ->whereDate('date','<=', $end_date)
                                                   ->where('received_id','!=',NULL)
                                                   ->sum('payment');
                                                   
                $total_price = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                $customerallBookingsObject[] = (Object)[
                        'booking_customer_id' => $invoice_res->id,
                        'name' => $invoice_res->name,
                        'currency' =>  $invoice_res->currency,
                        'Invoices_booking' => $invoice_res->Invoices_booking,
                        'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                        'packages_booking' => $customersPackageBookings[$index]->packages_booking,
                        'packages_prices_sum' => number_format($customersPackageBookings[$index]->packages_prices_sum,2),
                        'all_bookings' => $invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking,
                        'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking),
                        'total_prices' => $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,
                        'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,2),
                        'Paid' => number_format($payment_today,2),
                        'remaining' => number_format($total_price - $payment_today,2)
                    ];
            }
            
        }
        
        if($request_data->date_type == 'data_month_wise'){
            
            $startOfMonth = Carbon::now()->startOfMonth();
            $start_date = $startOfMonth->format('Y-m-d');
            $end_date = date('Y-m-d');
            
             $customersInvoices = DB::table('booking_customers')
                            ->leftJoin('add_manage_invoices', function ($join) use($start_date,$end_date) {
                                                    $join->on('add_manage_invoices.booking_customer_id', '=', 'booking_customers.id')
                                                        ->whereDate('add_manage_invoices.created_at','>=', $start_date)
                                                        ->whereDate('add_manage_invoices.created_at','<=', $end_date);
                                                })
                            ->where('booking_customers.customer_id',$userData->id)
                            ->where('booking_customers.SU_id',$request->SU_id)
                            // ->select('booking_customers.id', 'booking_customers.name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                            ->select('booking_customers.id', 'booking_customers.name','booking_customers.balance','booking_customers.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_all_Services) as Invoices_prices_sum'))
                            ->groupBy('booking_customers.id')
                            ->orderBy('booking_customers.id','asc')
                            ->get();
                            
            $customersPackageBookings = DB::table('booking_customers')
                            ->leftJoin('cart_details', function ($join) use($start_date,$end_date){
                                $join->on('booking_customers.id', '=', DB::raw('JSON_UNQUOTE(JSON_EXTRACT(cart_details.cart_total_data, "$.customer_id"))'))
                                ->whereDate('cart_details.created_at','>=', $start_date)
                                ->whereDate('cart_details.created_at','<=', $end_date);
                            })
                            ->where('booking_customers.customer_id',$userData->id)
                            ->where('booking_customers.SU_id',$request->SU_id)
                            ->select('booking_customers.id', 'booking_customers.name','booking_customers.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                            ->groupBy('booking_customers.id')
                            ->orderBy('booking_customers.id','asc')
                            ->get();
            
         
            $customerallBookingsObject = [];
            
            foreach($customersInvoices as $index => $invoice_res){
                $total_prices = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                
                $payment_today = DB::table('customer_ledger')->where('booking_customer',$invoice_res->id)->where('SU_id',$request->SU_id)
                                                   ->whereDate('date','>=', $start_date)
                                                   ->whereDate('date','<=', $end_date)
                                                   ->where('received_id','!=',NULL)
                                                   ->sum('payment');
                                                   
                $total_price = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                $customerallBookingsObject[] = (Object)[
                        'booking_customer_id' => $invoice_res->id,
                        'name' => $invoice_res->name,
                        'currency' =>  $invoice_res->currency,
                        'Invoices_booking' => $invoice_res->Invoices_booking,
                        'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                        'packages_booking' => $customersPackageBookings[$index]->packages_booking,
                        'packages_prices_sum' => number_format($customersPackageBookings[$index]->packages_prices_sum,2),
                        'all_bookings' => $invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking,
                        'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking),
                        'total_prices' => $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,
                        'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,2),
                        'Paid' => number_format($payment_today,2),
                        'remaining' => number_format($total_price - $payment_today,2)
                    ];
            }
            
        }
        
        if($request_data->date_type == 'data_year_wise'){
            
            $startOfYear = Carbon::now()->startOfYear();
            $start_date = $startOfYear->format('Y-m-d');
            $end_date = date('Y-m-d');
            
             $customersInvoices = DB::table('booking_customers')
                            ->leftJoin('add_manage_invoices', function ($join) use($start_date,$end_date) {
                                                    $join->on('add_manage_invoices.booking_customer_id', '=', 'booking_customers.id')
                                                        ->whereDate('add_manage_invoices.created_at','>=', $start_date)
                                                        ->whereDate('add_manage_invoices.created_at','<=', $end_date);
                                                })
                            ->where('booking_customers.customer_id',$userData->id)
                            ->where('booking_customers.SU_id',$request->SU_id)
                            // ->select('booking_customers.id', 'booking_customers.name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                            ->select('booking_customers.id', 'booking_customers.name','booking_customers.balance','booking_customers.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_all_Services) as Invoices_prices_sum'))
                            ->groupBy('booking_customers.id')
                            ->orderBy('booking_customers.id','asc')
                            ->get();
                            
            $customersPackageBookings = DB::table('booking_customers')
                            ->leftJoin('cart_details', function ($join) use($start_date,$end_date){
                                $join->on('booking_customers.id', '=', DB::raw('JSON_UNQUOTE(JSON_EXTRACT(cart_details.cart_total_data, "$.customer_id"))'))
                                ->whereDate('cart_details.created_at','>=', $start_date)
                                ->whereDate('cart_details.created_at','<=', $end_date);
                            })
                            ->where('booking_customers.customer_id',$userData->id)
                            ->where('booking_customers.SU_id',$request->SU_id)
                            ->select('booking_customers.id', 'booking_customers.name','booking_customers.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                            ->groupBy('booking_customers.id')
                            ->orderBy('booking_customers.id','asc')
                            ->get();
            
         
            $customerallBookingsObject = [];
            
            foreach($customersInvoices as $index => $invoice_res){
                $total_prices = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                
                $payment_today = DB::table('customer_ledger')->where('booking_customer',$invoice_res->id)->where('SU_id',$request->SU_id)
                                                   ->whereDate('date','>=', $start_date)
                                                   ->whereDate('date','<=', $end_date)
                                                   ->where('received_id','!=',NULL)
                                                   ->sum('payment');
                                                   
                $total_price = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                $customerallBookingsObject[] = (Object)[
                        'booking_customer_id' => $invoice_res->id,
                        'name' => $invoice_res->name,
                        'currency' =>  $invoice_res->currency,
                        'Invoices_booking' => $invoice_res->Invoices_booking,
                        'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                        'packages_booking' => $customersPackageBookings[$index]->packages_booking,
                        'packages_prices_sum' => number_format($customersPackageBookings[$index]->packages_prices_sum,2),
                        'all_bookings' => $invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking,
                        'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking),
                        'total_prices' => $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,
                        'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,2),
                        'Paid' => number_format($payment_today,2),
                        'remaining' => number_format($total_price - $payment_today,2)
                    ];
            }
            
        }
        
        if($request_data->date_type == 'data_wise'){
            
            $start_date = $request_data->start_date;
            $end_date = $request_data->end_date;
            
             $customersInvoices = DB::table('booking_customers')
                            ->leftJoin('add_manage_invoices', function ($join) use($start_date,$end_date) {
                                                    $join->on('add_manage_invoices.booking_customer_id', '=', 'booking_customers.id')
                                                        ->whereDate('add_manage_invoices.created_at','>=', $start_date)
                                                        ->whereDate('add_manage_invoices.created_at','<=', $end_date);
                                                })
                            ->where('booking_customers.customer_id',$userData->id)
                            ->where('booking_customers.SU_id',$request->SU_id)
                            // ->select('booking_customers.id', 'booking_customers.name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                            ->select('booking_customers.id', 'booking_customers.name','booking_customers.balance','booking_customers.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_all_Services) as Invoices_prices_sum'))
                            ->groupBy('booking_customers.id')
                            ->orderBy('booking_customers.id','asc')
                            ->get();
                            
            $customersPackageBookings = DB::table('booking_customers')
                            ->leftJoin('cart_details', function ($join) use($start_date,$end_date){
                                $join->on('booking_customers.id', '=', DB::raw('JSON_UNQUOTE(JSON_EXTRACT(cart_details.cart_total_data, "$.customer_id"))'))
                                ->whereDate('cart_details.created_at','>=', $start_date)
                                ->whereDate('cart_details.created_at','<=', $end_date);
                            })
                            ->where('booking_customers.customer_id',$userData->id)
                            ->where('booking_customers.SU_id',$request->SU_id)
                            ->select('booking_customers.id', 'booking_customers.name','booking_customers.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                            ->groupBy('booking_customers.id')
                            ->orderBy('booking_customers.id','asc')
                            ->get();
            
         
            $customerallBookingsObject = [];
            
            foreach($customersInvoices as $index => $invoice_res){
                $total_prices = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                
                $payment_today = DB::table('customer_ledger')->where('booking_customer',$invoice_res->id)->where('SU_id',$request->SU_id)
                                                   ->whereDate('date','>=', $start_date)
                                                   ->whereDate('date','<=', $end_date)
                                                   ->where('received_id','!=',NULL)
                                                   ->sum('payment');
                                                   
                $total_price = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                $customerallBookingsObject[] = (Object)[
                        'booking_customer_id' => $invoice_res->id,
                        'name' => $invoice_res->name,
                        'currency' =>  $invoice_res->currency,
                        'Invoices_booking' => $invoice_res->Invoices_booking,
                        'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                        'packages_booking' => $customersPackageBookings[$index]->packages_booking,
                        'packages_prices_sum' => number_format($customersPackageBookings[$index]->packages_prices_sum,2),
                        'all_bookings' => $invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking,
                        'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking),
                        'total_prices' => $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,
                        'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,2),
                        'Paid' => number_format($payment_today,2),
                        'remaining' => number_format($total_price - $payment_today,2)
                    ];
            }
            
        }
        
        if($request_data->filter_type == 'total_revenue'){
            $customerallBookingsObject = new Collection($customerallBookingsObject);
            
            $customerallBookingsObject = $customerallBookingsObject->sortByDesc('total_prices');
            
            $customerallBookingsObject = $customerallBookingsObject->values();
            
            $customerallBookingsObject = $customerallBookingsObject->toArray();
            
            if(sizeOf($customerallBookingsObject) >= 4){
                $limitedCustomerData = array_slice($customerallBookingsObject, 0, 4);
            }else{
                $limitedCustomerData = $customerallBookingsObject ?? '';
            }
            
            $series_data = [];
            $categories_data = [];
            
            if($request_data->date_type == 'all_data'){
                
                $currentYear = date('Y');
                $monthsData = [];
                
                for ($month = 1; $month <= 12; $month++) {
                    
                     $startOfMonth = Carbon::create($currentYear, $month, 1)->startOfMonth();
                     $endOfMonth = Carbon::create($currentYear, $month, 1)->endOfMonth();
                     
                      $categories_data[] = $startOfMonth->format('F');
                     
                     $startOfMonth = $startOfMonth->format('Y-m-d');
                     $endOfMonth = $endOfMonth->format('Y-m-d');
    
                    $monthsData[] = (Object)[
                        'month' => $month,
                        'start_date' => $startOfMonth,
                        'end_date' => $endOfMonth,
                    ];
                }
                if(isset($limitedCustomerData) && $limitedCustomerData != null && $limitedCustomerData != ''){
                    foreach($limitedCustomerData as $cust_res){
                        
                   
                        
                        $customer_booking_data = [];
                        foreach($monthsData as $month_res){
                            // Add 7 days to the start date
                            
                            $customerInvoices = DB::table('add_manage_invoices')->where('booking_customer_id',$cust_res->booking_customer_id)->where('SU_id',$request->SU_id)
                                                                              ->whereDate('add_manage_invoices.created_at','>=', $month_res->start_date)
                                                                              ->whereDate('add_manage_invoices.created_at','<=', $month_res->end_date)
                                                                              ->Sum('total_sale_price_all_Services');
    
                            $customerPackageBookings =  DB::table('cart_details')->where('SU_id',$request->SU_id)->whereJsonContains('cart_total_data->customer_id',"$cust_res->booking_customer_id")
                                                                      ->whereDate('cart_details.created_at','>=', $month_res->start_date)
                                                                      ->whereDate('cart_details.created_at','<=', $month_res->end_date)
                                                                      ->Sum('tour_total_price');
                                                                       
                            $total_booking_price = $customerInvoices + $customerPackageBookings;
                                            
                          
                            
                            $customer_booking_data[] = floor($total_booking_price * 100) / 100;
                           
                        }
                        
                        $series_data[] = [
                                'name' => $cust_res->name,
                                'data' => $customer_booking_data
                            ];
                    }
                }
            }
            
            if($request_data->date_type == 'data_today_wise'){
            
                $date = date('Y-m-d');
                if(isset($limitedCustomerData) && $limitedCustomerData != null && $limitedCustomerData != ''){
                    foreach($limitedCustomerData as $cust_res){
                        
                        $customer_booking_data = [$cust_res->total_prices];
                        $series_data[] = [
                                'name' => $cust_res->name,
                                'data' => $customer_booking_data
                            ];
                    }
                }
                $categories_data = [$date];
            }
            
            if($request_data->date_type == 'data_Yesterday_wise'){
                $date = date('Y-m-d',strtotime("-1 days"));
                if(isset($limitedCustomerData) && $limitedCustomerData != null && $limitedCustomerData != ''){
                    foreach($limitedCustomerData as $cust_res){
                        
                        $customer_booking_data = [$cust_res->total_prices];
                        $series_data[] = [
                                'name' => $cust_res->name,
                                'data' => $customer_booking_data
                            ];
                    }
                }
                $categories_data = [$date];
            }
            
            // Generate Graph Data For Week
            if($request_data->date_type == 'data_week_wise'){
               $today = Carbon::now();
                
                // Create a CarbonPeriod instance for the current week
               $startOfWeek = $today->startOfWeek()->toDateString();
                $endOfWeek = $today->endOfWeek()->toDateString();
                
                // Create a CarbonPeriod instance for the current week
                $period = CarbonPeriod::create($startOfWeek, $endOfWeek);
                
                // Get the dates of the current week as an array
                $datesOfWeek = [];
                foreach ($period as $date) {
                    $datesOfWeek[] = $date->toDateString();
                }
                
                // Get Data for Agent 
                if(isset($limitedCustomerData) && $limitedCustomerData != null && $limitedCustomerData != ''){
                foreach($limitedCustomerData as $cust_res){
                    
                    // Loop On DatesOfWeek
                    
                    $customer_booking_data = [];
                    foreach($datesOfWeek as $date_res){
                        $customerInvoices = DB::table('add_manage_invoices')->where('booking_customer_id',$cust_res->booking_customer_id)->where('SU_id',$request->SU_id)
                                                                          ->whereDate('created_at',$date_res)
                                                                          ->Sum('total_sale_price_all_Services');

                        $customerPackageBookings =  DB::table('cart_details')->where('SU_id',$request->SU_id)->whereJsonContains('cart_total_data->customer_id',"$cust_res->booking_customer_id")
                                                                  ->whereDate('created_at',$date_res)
                                                                  ->Sum('tour_total_price');
                                                                   
                        $total_booking_price = $customerInvoices + $customerPackageBookings;
                                        
                      
                        
                        $customer_booking_data[] = floor($total_booking_price * 100) / 100;
                    }
                    
                    $series_data[] = [
                            'name' => $cust_res->name,
                            'data' => $customer_booking_data
                        ];
                }
                }
                // Get Data For Categories
                
                $dayNamesOfWeek = [];
                for ($day = 0; $day < 7; $day++) {
                    $dayNamesOfWeek[] = $today->startOfWeek()->addDays($day)->format('l'); // 'l' format represents the full day name
                }
                
                $categories_data = $dayNamesOfWeek;
            }
            
            if($request_data->date_type == 'data_month_wise'){
                if(isset($limitedCustomerData) && $limitedCustomerData != null && $limitedCustomerData != ''){
                foreach($limitedCustomerData as $cust_res){
                    
                    // Start Date Of Month
                    // $currentDate = Carbon::now();
                    $startDate = Carbon::now()->startOfMonth();
                    // $startDate = $currentDate->subMonth()->firstOfMonth();
                    $startDateWeek = $startDate->toDateString();
                    $endDate = $startDate->copy()->addDays(6);
                    $endDateWeek = $endDate->toDateString();
                    
                    $customer_booking_data = [];
                    for($i=1; $i<=5; $i++){
                        // Add 7 days to the start date
                        
                        $customerInvoices = DB::table('add_manage_invoices')->where('booking_customer_id',$cust_res->booking_customer_id)->where('SU_id',$request->SU_id)
                                                                          ->whereDate('add_manage_invoices.created_at','>=', $startDate)
                                                                          ->whereDate('add_manage_invoices.created_at','<=', $endDate)
                                                                          ->Sum('total_sale_price_all_Services');

                        $customerPackageBookings =  DB::table('cart_details')->where('SU_id',$request->SU_id)->whereJsonContains('cart_total_data->customer_id',"$cust_res->booking_customer_id")
                                                                  ->whereDate('cart_details.created_at','>=', $startDate)
                                                                  ->whereDate('cart_details.created_at','<=', $endDate)
                                                                  ->Sum('tour_total_price');
                                                                   
                        $total_booking_price = $customerInvoices + $customerPackageBookings;
                                        
                      
                        
                        $customer_booking_data[] = floor($total_booking_price * 100) / 100;;
                        
                        
                        $startDate = $endDate->copy()->addDays(1);
                        if($i == '4'){
                            $endDate = $startDate->copy()->addDays(2);
                        }else{
                             $endDate = $startDate->copy()->addDays(6);
                        }
                        
                        $startDateWeek = $startDate->toDateString();
                        $endDateWeek = $endDate->toDateString();
                    }
                    
                    $series_data[] = [
                            'name' => $cust_res->name,
                            'data' => $customer_booking_data
                        ];
                }
                }
               
                    // dd($startDateWeek,$endDateWeek);
                //   print_r($weekDates);
                //   die;
                $categories_data = ['1st Week','2nd Week','3rd Week','4th Week','5th Week'];
            }
            
            if($request_data->date_type == 'data_year_wise'){
                $currentYear = date('Y');
                $monthsData = [];
            
                for ($month = 1; $month <= 12; $month++) {
                    
                     $startOfMonth = Carbon::create($currentYear, $month, 1)->startOfMonth();
                     $endOfMonth = Carbon::create($currentYear, $month, 1)->endOfMonth();
                     
                      $categories_data[] = $startOfMonth->format('F');
                     
                     $startOfMonth = $startOfMonth->format('Y-m-d');
                     $endOfMonth = $endOfMonth->format('Y-m-d');
    
                    $monthsData[] = (Object)[
                        'month' => $month,
                        'start_date' => $startOfMonth,
                        'end_date' => $endOfMonth,
                    ];
                }
                if(isset($limitedCustomerData) && $limitedCustomerData != null && $limitedCustomerData != ''){
                foreach($limitedCustomerData as $cust_res){
                    
               
                    
                    $customer_booking_data = [];
                    foreach($monthsData as $month_res){
                        // Add 7 days to the start date
                        
                        $customerInvoices = DB::table('add_manage_invoices')->where('booking_customer_id',$cust_res->booking_customer_id)->where('SU_id',$request->SU_id)
                                                                          ->whereDate('add_manage_invoices.created_at','>=', $month_res->start_date)
                                                                          ->whereDate('add_manage_invoices.created_at','<=', $month_res->end_date)
                                                                          ->Sum('total_sale_price_all_Services');

                        $customerPackageBookings =  DB::table('cart_details')->where('SU_id',$request->SU_id)->whereJsonContains('cart_total_data->customer_id',"$cust_res->booking_customer_id")
                                                                  ->whereDate('cart_details.created_at','>=', $month_res->start_date)
                                                                  ->whereDate('cart_details.created_at','<=', $month_res->end_date)
                                                                  ->Sum('tour_total_price');
                                                                   
                        $total_booking_price = $customerInvoices + $customerPackageBookings;
                                        
                      
                        
                        $customer_booking_data[] = floor($total_booking_price * 100) / 100;
                       
                    }
                    
                    $series_data[] = [
                            'name' => $cust_res->name,
                            'data' => $customer_booking_data
                        ];
                }
                }
            }
            
            return response()->json([
                    'status' => 'success',
                    'data' => $limitedCustomerData,
                    'series_data' => $series_data,
                    'categories_data' => $categories_data,
                ]);
        }else{
            $customerallBookingsObject = new Collection($customerallBookingsObject);

            $customerallBookingsObject = $customerallBookingsObject->sortByDesc('all_bookings');
            
            $customerallBookingsObject = $customerallBookingsObject->values();
            
            $customerallBookingsObject = $customerallBookingsObject->toArray();
            
            if(sizeOf($customerallBookingsObject) >= 4){
                $limitedCustomerData = array_slice($customerallBookingsObject, 0, 4);
            }else{
                $limitedCustomerData = $customerallBookingsObject ?? '';
            }
            
         
           $series_data = [];
            $categories_data = [];
            
            if($request_data->date_type == 'all_data'){
                
                $currentYear = date('Y');
                $monthsData = [];
            
                for ($month = 1; $month <= 12; $month++) {
                    
                     $startOfMonth = Carbon::create($currentYear, $month, 1)->startOfMonth();
                     $endOfMonth = Carbon::create($currentYear, $month, 1)->endOfMonth();
                     
                      $categories_data[] = $startOfMonth->format('F');
                     
                     $startOfMonth = $startOfMonth->format('Y-m-d');
                     $endOfMonth = $endOfMonth->format('Y-m-d');
    
                    $monthsData[] = (Object)[
                        'month' => $month,
                        'start_date' => $startOfMonth,
                        'end_date' => $endOfMonth,
                    ];
                }
                if(isset($limitedCustomerData) && $limitedCustomerData != null && $limitedCustomerData != ''){
                foreach($limitedCustomerData as $cust_res){
                    
               
                    
                    $customer_booking_data = [];
                    foreach($monthsData as $month_res){
                        // Add 7 days to the start date
                        
                        $customerInvoices = DB::table('add_manage_invoices')->where('booking_customer_id',$cust_res->booking_customer_id)
                                                                          ->whereDate('add_manage_invoices.created_at','>=', $month_res->start_date)
                                                                          ->whereDate('add_manage_invoices.created_at','<=', $month_res->end_date)
                                                                          ->count();

                        $customerPackageBookings =  DB::table('cart_details')->whereJsonContains('cart_total_data->customer_id',"$cust_res->booking_customer_id")
                                                                  ->whereDate('cart_details.created_at','>=', $month_res->start_date)
                                                                  ->whereDate('cart_details.created_at','<=', $month_res->end_date)
                                                                  ->count();
                                                                   
                        $total_booking_price = $customerInvoices + $customerPackageBookings;
                                        
                      
                        
                        $customer_booking_data[] = floor($total_booking_price * 100) / 100;
                       
                    }
                    
                    $series_data[] = [
                            'name' => $cust_res->name,
                            'data' => $customer_booking_data
                        ];
                }
                }
     
       
            }
            
            if($request_data->date_type == 'data_today_wise'){
            
                $date = date('Y-m-d');
                if(isset($limitedCustomerData) && $limitedCustomerData != null && $limitedCustomerData != ''){
                foreach($limitedCustomerData as $cust_res){
                    
                    $customer_booking_data = [$cust_res->all_bookings];
                    $series_data[] = [
                            'name' => $cust_res->name,
                            'data' => $customer_booking_data
                        ];
                }
                }
                
                $categories_data = [$date];
            }
            
            if($request_data->date_type == 'data_Yesterday_wise'){
                
                $date = date('Y-m-d',strtotime("-1 days"));
                
                if(isset($limitedCustomerData) && $limitedCustomerData != null && $limitedCustomerData != ''){
                foreach($limitedCustomerData as $cust_res){
                    
                    $customer_booking_data = [$cust_res->all_bookings];
                    $series_data[] = [
                            'name' => $cust_res->name,
                            'data' => $customer_booking_data
                        ];
                }
                }
                
                $categories_data = [$date];
            }
            
            // Generate Graph Data For Week
            if($request_data->date_type == 'data_week_wise'){
               $today = Carbon::now();
                
                // Create a CarbonPeriod instance for the current week
               $startOfWeek = $today->startOfWeek()->toDateString();
                $endOfWeek = $today->endOfWeek()->toDateString();
                
                // Create a CarbonPeriod instance for the current week
                $period = CarbonPeriod::create($startOfWeek, $endOfWeek);
                
                // Get the dates of the current week as an array
                $datesOfWeek = [];
                foreach ($period as $date) {
                    $datesOfWeek[] = $date->toDateString();
                }
                
                // Get Data for Agent 
                if(isset($limitedCustomerData) && $limitedCustomerData != null && $limitedCustomerData != ''){
                foreach($limitedCustomerData as $cust_res){
                    
                    // Loop On DatesOfWeek
                    
                    $customer_booking_data = [];
                    foreach($datesOfWeek as $date_res){
                        $customerInvoices = DB::table('add_manage_invoices')->where('booking_customer_id',$cust_res->booking_customer_id)
                                                                          ->whereDate('created_at',$date_res)
                                                                          ->count();

                        $customerPackageBookings =  DB::table('cart_details')->whereJsonContains('cart_total_data->customer_id',"$cust_res->booking_customer_id")
                                                                  ->whereDate('created_at',$date_res)
                                                                  ->count();
                                                                   
                        $total_booking_price = $customerInvoices + $customerPackageBookings;
                                        
                      
                        
                        $customer_booking_data[] = floor($total_booking_price * 100) / 100;
                    }
                    
                    $series_data[] = [
                            'name' => $cust_res->name,
                            'data' => $customer_booking_data
                        ];
                }
                }
                // Get Data For Categories
                
                $dayNamesOfWeek = [];
                for ($day = 0; $day < 7; $day++) {
                    $dayNamesOfWeek[] = $today->startOfWeek()->addDays($day)->format('l'); // 'l' format represents the full day name
                }
                
                $categories_data = $dayNamesOfWeek;
            }
            
            if($request_data->date_type == 'data_month_wise'){
                if(isset($limitedCustomerData) && $limitedCustomerData != null && $limitedCustomerData != ''){
                foreach($limitedCustomerData as $cust_res){
                    
                    // Start Date Of Month
                    // $currentDate = Carbon::now();
                    $startDate = Carbon::now()->startOfMonth();
                    // $startDate = $currentDate->subMonth()->firstOfMonth();
                    $startDateWeek = $startDate->toDateString();
                    $endDate = $startDate->copy()->addDays(6);
                    $endDateWeek = $endDate->toDateString();
                    
                    $customer_booking_data = [];
                    for($i=1; $i<=5; $i++){
                        // Add 7 days to the start date
                        
                        $customerInvoices = DB::table('add_manage_invoices')->where('booking_customer_id',$cust_res->booking_customer_id)
                                                                          ->whereDate('add_manage_invoices.created_at','>=', $startDate)
                                                                          ->whereDate('add_manage_invoices.created_at','<=', $endDate)
                                                                          ->count();

                        $customerPackageBookings =  DB::table('cart_details')->whereJsonContains('cart_total_data->customer_id',"$cust_res->booking_customer_id")
                                                                  ->whereDate('cart_details.created_at','>=', $startDate)
                                                                  ->whereDate('cart_details.created_at','<=', $endDate)
                                                                  ->count();
                                                                   
                        $total_booking_price = $customerInvoices + $customerPackageBookings;
                                        
                      
                        
                        $customer_booking_data[] = floor($total_booking_price * 100) / 100;;
                        
                        
                        $startDate = $endDate->copy()->addDays(1);
                        if($i == '4'){
                            $endDate = $startDate->copy()->addDays(2);
                        }else{
                             $endDate = $startDate->copy()->addDays(6);
                        }
                        
                        $startDateWeek = $startDate->toDateString();
                        $endDateWeek = $endDate->toDateString();
                    }
                    
                    $series_data[] = [
                            'name' => $cust_res->name,
                            'data' => $customer_booking_data
                        ];
                }
                }
                $categories_data = ['1st Week','2nd Week','3rd Week','4th Week','5th Week'];
            }
            
            if($request_data->date_type == 'data_year_wise'){
                $currentYear = date('Y');
                $monthsData = [];
                
                for ($month = 1; $month <= 12; $month++) {
                    
                     $startOfMonth = Carbon::create($currentYear, $month, 1)->startOfMonth();
                     $endOfMonth = Carbon::create($currentYear, $month, 1)->endOfMonth();
                     
                      $categories_data[] = $startOfMonth->format('F');
                     
                     $startOfMonth = $startOfMonth->format('Y-m-d');
                     $endOfMonth = $endOfMonth->format('Y-m-d');
    
                    $monthsData[] = (Object)[
                        'month' => $month,
                        'start_date' => $startOfMonth,
                        'end_date' => $endOfMonth,
                    ];
                }
                if(isset($limitedCustomerData) && $limitedCustomerData != null && $limitedCustomerData != ''){
                foreach($limitedCustomerData as $cust_res){
                    
               
                    
                    $customer_booking_data = [];
                    foreach($monthsData as $month_res){
                        // Add 7 days to the start date
                        
                        $customerInvoices = DB::table('add_manage_invoices')->where('booking_customer_id',$cust_res->booking_customer_id)
                                                                          ->whereDate('add_manage_invoices.created_at','>=', $month_res->start_date)
                                                                          ->whereDate('add_manage_invoices.created_at','<=', $month_res->end_date)
                                                                          ->count();

                        $customerPackageBookings =  DB::table('cart_details')->whereJsonContains('cart_total_data->customer_id',"$cust_res->booking_customer_id")
                                                                  ->whereDate('cart_details.created_at','>=', $month_res->start_date)
                                                                  ->whereDate('cart_details.created_at','<=', $month_res->end_date)
                                                                  ->count();
                                                                   
                        $total_booking_price = $customerInvoices + $customerPackageBookings;
                                        
                      
                        
                        $customer_booking_data[] = floor($total_booking_price * 100) / 100;
                       
                    }
                    
                    $series_data[] = [
                            'name' => $cust_res->name,
                            'data' => $customer_booking_data
                        ];
                }
                }
            }
            
            return response()->json([
                'status' => 'success',
                'data' => $limitedCustomerData,
                'series_data' => $series_data,
                'categories_data' => $categories_data,
            ]);
        }
    }
    
    public function customer_booking_filter(Request $request){
        // dd($request->all());
        $userData               = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        
        $request_data           = json_decode($request->request_data);
        
        $agentallBookingsObject = [];
        
        if($request_data->date_type == 'all_data'){
            $customersInvoices          = DB::table('booking_customers')
                                            ->leftJoin('add_manage_invoices','add_manage_invoices.booking_customer_id','booking_customers.id')
                                            ->where('booking_customers.customer_id',$userData->id)
                                            // ->select('booking_customers.id', 'booking_customers.name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                                            ->select('booking_customers.created_at','booking_customers.id', 'booking_customers.name','booking_customers.balance','booking_customers.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_all_Services) as Invoices_prices_sum'))
                                            ->groupBy('booking_customers.id')
                                            ->orderBy('booking_customers.id','asc')
                                            ->get();
                            
            $customersPackageBookings   = DB::table('booking_customers')
                                            ->leftJoin('cart_details', function ($join) {
                                                $join->on('booking_customers.id', '=', DB::raw('JSON_UNQUOTE(JSON_EXTRACT(cart_details.cart_total_data, "$.customer_id"))'));
                                            })
                                            ->where('booking_customers.customer_id',$userData->id)
                                            ->select('booking_customers.created_at','booking_customers.id', 'booking_customers.name','booking_customers.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                                            ->groupBy('booking_customers.id')
                                            ->orderBy('booking_customers.id','asc')
                                            ->get();
            $customerallBookingsObject = [];
            
            // dd($customersPackageBookings);
            
            foreach($customersInvoices as $index => $invoice_res){
                $total_prices                   = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                $customerallBookingsObject[]    = (Object)[
                    'booking_customer_id'       => $invoice_res->id,
                    'name'                      => $invoice_res->name,
                    'currency'                  => $invoice_res->currency,
                    'Invoices_booking'          => $invoice_res->Invoices_booking,
                    'Invoices_prices_sum'       => number_format($invoice_res->Invoices_prices_sum,2),
                    'packages_booking'          => $customersPackageBookings[$index]->packages_booking,
                    'packages_prices_sum'       => number_format($customersPackageBookings[$index]->packages_prices_sum,2),
                    'all_bookings'              => $invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking,
                    'all_bookings_num_format'   => number_format($invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking),
                    'total_prices'              => $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,
                    'total_prices_num_format'   => number_format($invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,2),
                    'Paid'                      => number_format($total_prices - $invoice_res->balance,2),
                    'remaining'                 => number_format($invoice_res->balance,2),
                    'created_at'                => $invoice_res->created_at,
                ];
            }
        }
        
        if($request_data->date_type == 'data_today_wise'){
            $today_date = date('Y-m-d');
            $customersInvoices = DB::table('booking_customers')
                            ->leftJoin('add_manage_invoices', function ($join) use($today_date) {
                                                    $join->on('add_manage_invoices.booking_customer_id', '=', 'booking_customers.id')
                                                        ->whereDate('add_manage_invoices.created_at', $today_date);
                                                })
                            ->where('booking_customers.customer_id',$userData->id)
                            // ->select('booking_customers.id', 'booking_customers.name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                            ->select('booking_customers.created_at','booking_customers.id', 'booking_customers.name','booking_customers.balance','booking_customers.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_all_Services) as Invoices_prices_sum'))
                            ->groupBy('booking_customers.id')
                            ->orderBy('booking_customers.id','asc')
                            ->get();
                            
            $customersPackageBookings = DB::table('booking_customers')
                            ->leftJoin('cart_details', function ($join) use($today_date){
                                $join->on('booking_customers.id', '=', DB::raw('JSON_UNQUOTE(JSON_EXTRACT(cart_details.cart_total_data, "$.customer_id"))'))
                                ->whereDate('cart_details.created_at', $today_date);
                            })
                            ->where('booking_customers.customer_id',$userData->id)
                            ->select('booking_customers.created_at','booking_customers.id', 'booking_customers.name','booking_customers.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                            ->groupBy('booking_customers.id')
                            ->orderBy('booking_customers.id','asc')
                            ->get();
            $customerallBookingsObject = [];
            
            foreach($customersInvoices as $index => $invoice_res){
                $total_prices = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                
                $payment_today = DB::table('customer_ledger')->where('booking_customer',$invoice_res->id)
                                                   ->whereDate('date',$today_date)
                                                  ->where('received_id','!=',NULL)
                                                   ->sum('payment');
                                                   
                $total_price = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                $customerallBookingsObject[] = (Object)[
                        'booking_customer_id' => $invoice_res->id,
                        'name' => $invoice_res->name,
                        'currency' =>  $invoice_res->currency,
                        'Invoices_booking' => $invoice_res->Invoices_booking,
                        'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                        'packages_booking' => $customersPackageBookings[$index]->packages_booking,
                        'packages_prices_sum' => number_format($customersPackageBookings[$index]->packages_prices_sum,2),
                        'all_bookings' => $invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking,
                        'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking),
                        'total_prices' => $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,
                        'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,2),
                        'Paid' => number_format($payment_today,2),
                        'remaining' => number_format($total_price - $payment_today,2),
                        'created_at' => $invoice_res->created_at,
                    ];
            }
        }
        
        if($request_data->date_type == 'data_Yesterday_wise'){
            $date = date('Y-m-d',strtotime("-1 days"));
            $customersInvoices = DB::table('booking_customers')
                            ->leftJoin('add_manage_invoices', function ($join) use($date) {
                                                    $join->on('add_manage_invoices.booking_customer_id', '=', 'booking_customers.id')
                                                        ->whereDate('add_manage_invoices.created_at', $date);
                                                })
                            ->where('booking_customers.customer_id',$userData->id)
                            // ->select('booking_customers.id', 'booking_customers.name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                            ->select('booking_customers.created_at','booking_customers.id', 'booking_customers.name','booking_customers.balance','booking_customers.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_all_Services) as Invoices_prices_sum'))
                            ->groupBy('booking_customers.id')
                            ->orderBy('booking_customers.id','asc')
                            ->get();
                            
            $customersPackageBookings = DB::table('booking_customers')
                            ->leftJoin('cart_details', function ($join) use($date){
                                $join->on('booking_customers.id', '=', DB::raw('JSON_UNQUOTE(JSON_EXTRACT(cart_details.cart_total_data, "$.customer_id"))'))
                                ->whereDate('cart_details.created_at', $date);
                            })
                            ->where('booking_customers.customer_id',$userData->id)
                            ->select('booking_customers.created_at','booking_customers.id', 'booking_customers.name','booking_customers.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                            ->groupBy('booking_customers.id')
                            ->orderBy('booking_customers.id','asc')
                            ->get();
            $customerallBookingsObject = [];
            
            foreach($customersInvoices as $index => $invoice_res){
                $total_prices = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                
                $payment_today = DB::table('customer_ledger')->where('booking_customer',$invoice_res->id)
                                                   ->whereDate('date',$date)
                                                  ->where('received_id','!=',NULL)
                                                   ->sum('payment');
                                                   
                $total_price = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                $customerallBookingsObject[] = (Object)[
                        'booking_customer_id' => $invoice_res->id,
                        'name' => $invoice_res->name,
                        'currency' =>  $invoice_res->currency,
                        'Invoices_booking' => $invoice_res->Invoices_booking,
                        'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                        'packages_booking' => $customersPackageBookings[$index]->packages_booking,
                        'packages_prices_sum' => number_format($customersPackageBookings[$index]->packages_prices_sum,2),
                        'all_bookings' => $invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking,
                        'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking),
                        'total_prices' => $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,
                        'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,2),
                        'Paid' => number_format($payment_today,2),
                        'remaining' => number_format($total_price - $payment_today,2),
                        'created_at' => $invoice_res->created_at,
                    ];
            }
        }
        
        if($request_data->date_type == 'data_week_wise'){
            
            $startOfWeek = Carbon::now()->startOfWeek();
            $start_date = $startOfWeek->format('Y-m-d');
            $end_date = date('Y-m-d');
            
            $customersInvoices = DB::table('booking_customers')
                            ->leftJoin('add_manage_invoices', function ($join) use($start_date,$end_date) {
                                                    $join->on('add_manage_invoices.booking_customer_id', '=', 'booking_customers.id')
                                                        ->whereDate('add_manage_invoices.created_at','>=', $start_date)
                                                        ->whereDate('add_manage_invoices.created_at','<=', $end_date);
                                                })
                            ->where('booking_customers.customer_id',$userData->id)
                            // ->select('booking_customers.id', 'booking_customers.name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                            ->select('booking_customers.created_at','booking_customers.id', 'booking_customers.name','booking_customers.balance','booking_customers.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_all_Services) as Invoices_prices_sum'))
                            ->groupBy('booking_customers.id')
                            ->orderBy('booking_customers.id','asc')
                            ->get();
                            
            $customersPackageBookings = DB::table('booking_customers')
                            ->leftJoin('cart_details', function ($join) use($start_date,$end_date){
                                $join->on('booking_customers.id', '=', DB::raw('JSON_UNQUOTE(JSON_EXTRACT(cart_details.cart_total_data, "$.customer_id"))'))
                                ->whereDate('cart_details.created_at','>=', $start_date)
                                ->whereDate('cart_details.created_at','<=', $end_date);
                            })
                            ->where('booking_customers.customer_id',$userData->id)
                            ->select('booking_customers.created_at','booking_customers.id', 'booking_customers.name','booking_customers.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                            ->groupBy('booking_customers.id')
                            ->orderBy('booking_customers.id','asc')
                            ->get();
            $customerallBookingsObject = [];
            
            foreach($customersInvoices as $index => $invoice_res){
                $total_prices = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                
                $payment_today = DB::table('customer_ledger')->where('booking_customer',$invoice_res->id)
                                                   ->whereDate('date','>=', $start_date)
                                                   ->whereDate('date','<=', $end_date)
                                                   ->where('received_id','!=',NULL)
                                                   ->sum('payment');
                                                   
                $total_price = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                $customerallBookingsObject[] = (Object)[
                        'booking_customer_id' => $invoice_res->id,
                        'name' => $invoice_res->name,
                        'currency' =>  $invoice_res->currency,
                        'Invoices_booking' => $invoice_res->Invoices_booking,
                        'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                        'packages_booking' => $customersPackageBookings[$index]->packages_booking,
                        'packages_prices_sum' => number_format($customersPackageBookings[$index]->packages_prices_sum,2),
                        'all_bookings' => $invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking,
                        'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking),
                        'total_prices' => $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,
                        'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,2),
                        'Paid' => number_format($payment_today,2),
                        'remaining' => number_format($total_price - $payment_today,2),
                        'created_at' => $invoice_res->created_at,
                    ];
            }
            
        }
        
        if($request_data->date_type == 'data_month_wise'){
            
            $startOfMonth = Carbon::now()->startOfMonth();
            $start_date = $startOfMonth->format('Y-m-d');
            $end_date = date('Y-m-d');
            
            $customersInvoices = DB::table('booking_customers')
                            ->leftJoin('add_manage_invoices', function ($join) use($start_date,$end_date) {
                                                    $join->on('add_manage_invoices.booking_customer_id', '=', 'booking_customers.id')
                                                        ->whereDate('add_manage_invoices.created_at','>=', $start_date)
                                                        ->whereDate('add_manage_invoices.created_at','<=', $end_date);
                                                })
                            ->where('booking_customers.customer_id',$userData->id)
                            // ->select('booking_customers.id', 'booking_customers.name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                            ->select('booking_customers.created_at','booking_customers.id', 'booking_customers.name','booking_customers.balance','booking_customers.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_all_Services) as Invoices_prices_sum'))
                            ->groupBy('booking_customers.id')
                            ->orderBy('booking_customers.id','asc')
                            ->get();
                            
            $customersPackageBookings = DB::table('booking_customers')
                            ->leftJoin('cart_details', function ($join) use($start_date,$end_date){
                                $join->on('booking_customers.id', '=', DB::raw('JSON_UNQUOTE(JSON_EXTRACT(cart_details.cart_total_data, "$.customer_id"))'))
                                ->whereDate('cart_details.created_at','>=', $start_date)
                                ->whereDate('cart_details.created_at','<=', $end_date);
                            })
                            ->where('booking_customers.customer_id',$userData->id)
                            ->select('booking_customers.created_at','booking_customers.id', 'booking_customers.name','booking_customers.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                            ->groupBy('booking_customers.id')
                            ->orderBy('booking_customers.id','asc')
                            ->get();
                            
            $customerallBookingsObject = [];
            
            foreach($customersInvoices as $index => $invoice_res){
                $total_prices = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                
                $payment_today = DB::table('customer_ledger')->where('booking_customer',$invoice_res->id)
                                                   ->whereDate('date','>=', $start_date)
                                                   ->whereDate('date','<=', $end_date)
                                                   ->where('received_id','!=',NULL)
                                                   ->sum('payment');
                                                   
                $total_price = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                $customerallBookingsObject[] = (Object)[
                        'booking_customer_id' => $invoice_res->id,
                        'name' => $invoice_res->name,
                        'currency' =>  $invoice_res->currency,
                        'Invoices_booking' => $invoice_res->Invoices_booking,
                        'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                        'packages_booking' => $customersPackageBookings[$index]->packages_booking,
                        'packages_prices_sum' => number_format($customersPackageBookings[$index]->packages_prices_sum,2),
                        'all_bookings' => $invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking,
                        'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking),
                        'total_prices' => $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,
                        'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,2),
                        'Paid' => number_format($payment_today,2),
                        'remaining' => number_format($total_price - $payment_today,2),
                        'created_at' => $invoice_res->created_at,
                    ];
            }
        }
        
        if($request_data->date_type == 'data_year_wise'){
            
            $startOfYear = Carbon::now()->startOfYear();
            $start_date = $startOfYear->format('Y-m-d');
            $end_date = date('Y-m-d');
            
            $customersInvoices = DB::table('booking_customers')
                            ->leftJoin('add_manage_invoices', function ($join) use($start_date,$end_date) {
                                                    $join->on('add_manage_invoices.booking_customer_id', '=', 'booking_customers.id')
                                                        ->whereDate('add_manage_invoices.created_at','>=', $start_date)
                                                        ->whereDate('add_manage_invoices.created_at','<=', $end_date);
                                                })
                            ->where('booking_customers.customer_id',$userData->id)
                            // ->select('booking_customers.id', 'booking_customers.name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                            ->select('booking_customers.created_at','booking_customers.id', 'booking_customers.name','booking_customers.balance','booking_customers.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_all_Services) as Invoices_prices_sum'))
                            ->groupBy('booking_customers.id')
                            ->orderBy('booking_customers.id','asc')
                            ->get();
                            
            $customersPackageBookings = DB::table('booking_customers')
                            ->leftJoin('cart_details', function ($join) use($start_date,$end_date){
                                $join->on('booking_customers.id', '=', DB::raw('JSON_UNQUOTE(JSON_EXTRACT(cart_details.cart_total_data, "$.customer_id"))'))
                                ->whereDate('cart_details.created_at','>=', $start_date)
                                ->whereDate('cart_details.created_at','<=', $end_date);
                            })
                            ->where('booking_customers.customer_id',$userData->id)
                            ->select('booking_customers.created_at','booking_customers.id', 'booking_customers.name','booking_customers.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                            ->groupBy('booking_customers.id')
                            ->orderBy('booking_customers.id','asc')
                            ->get();
            $customerallBookingsObject = [];
            
            foreach($customersInvoices as $index => $invoice_res){
                $total_prices = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                
                $payment_today = DB::table('customer_ledger')->where('booking_customer',$invoice_res->id)
                                                   ->whereDate('date','>=', $start_date)
                                                   ->whereDate('date','<=', $end_date)
                                                   ->where('received_id','!=',NULL)
                                                   ->sum('payment');
                                                   
                $total_price = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                $customerallBookingsObject[] = (Object)[
                        'booking_customer_id' => $invoice_res->id,
                        'name' => $invoice_res->name,
                        'currency' =>  $invoice_res->currency,
                        'Invoices_booking' => $invoice_res->Invoices_booking,
                        'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                        'packages_booking' => $customersPackageBookings[$index]->packages_booking,
                        'packages_prices_sum' => number_format($customersPackageBookings[$index]->packages_prices_sum,2),
                        'all_bookings' => $invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking,
                        'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking),
                        'total_prices' => $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,
                        'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,2),
                        'Paid' => number_format($payment_today,2),
                        'remaining' => number_format($total_price - $payment_today,2),
                        'created_at' => $invoice_res->created_at,
                    ];
            }
        }
        
        if($request_data->date_type == 'data_wise'){
            $start_date                 = $request_data->start_date;
            $end_date                   = $request_data->end_date;
            $customersInvoices          = DB::table('booking_customers')
                                            ->leftJoin('add_manage_invoices', function ($join) use($start_date,$end_date) {
                                                                    $join->on('add_manage_invoices.booking_customer_id', '=', 'booking_customers.id')
                                                                        ->whereDate('add_manage_invoices.created_at','>=', $start_date)
                                                                        ->whereDate('add_manage_invoices.created_at','<=', $end_date);
                                                                })
                                            ->where('booking_customers.customer_id',$userData->id)
                                            // ->select('booking_customers.id', 'booking_customers.name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                                            ->select('booking_customers.created_at','booking_customers.id', 'booking_customers.name','booking_customers.balance','booking_customers.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_all_Services) as Invoices_prices_sum'))
                                            ->groupBy('booking_customers.id')
                                            ->orderBy('booking_customers.id','asc')
                                            ->get();
            $customersPackageBookings = DB::table('booking_customers')
                                            ->leftJoin('cart_details', function ($join) use($start_date,$end_date){
                                                $join->on('booking_customers.id', '=', DB::raw('JSON_UNQUOTE(JSON_EXTRACT(cart_details.cart_total_data, "$.customer_id"))'))
                                                ->whereDate('cart_details.created_at','>=', $start_date)
                                                ->whereDate('cart_details.created_at','<=', $end_date);
                                            })
                                            ->where('booking_customers.customer_id',$userData->id)
                                            ->select('booking_customers.created_at','booking_customers.id', 'booking_customers.name','booking_customers.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                                            ->groupBy('booking_customers.id')
                                            ->orderBy('booking_customers.id','asc')
                                            ->get();
            $customerallBookingsObject = [];
            
            // dd($customersInvoices);
            
            foreach($customersInvoices as $index => $invoice_res){
                $total_prices = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                
                $payment_today = DB::table('customer_ledger')->where('booking_customer',$invoice_res->id)
                                                   ->whereDate('date','>=', $start_date)
                                                   ->whereDate('date','<=', $end_date)
                                                   ->where('received_id','!=',NULL)
                                                   ->sum('payment');
                                                   
                $total_price = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
                $customerallBookingsObject[] = (Object)[
                        'booking_customer_id' => $invoice_res->id,
                        'name' => $invoice_res->name,
                        'currency' =>  $invoice_res->currency,
                        'Invoices_booking' => $invoice_res->Invoices_booking,
                        'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                        'packages_booking' => $customersPackageBookings[$index]->packages_booking,
                        'packages_prices_sum' => number_format($customersPackageBookings[$index]->packages_prices_sum,2),
                        'all_bookings' => $invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking,
                        'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking),
                        'total_prices' => $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,
                        'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,2),
                        'Paid' => number_format($payment_today,2),
                        'remaining' => number_format($total_price - $payment_today,2),
                        'created_at' => $invoice_res->created_at,
                ];
            }
        }
        
        if($request_data->filter_type == 'total_revenue'){
            $customerallBookingsObject = new Collection($customerallBookingsObject);
            
            $customerallBookingsObject = $customerallBookingsObject->sortByDesc('total_prices');
            
            $customerallBookingsObject = $customerallBookingsObject->values();
            
            $customerallBookingsObject = $customerallBookingsObject->toArray();
            
            if(sizeOf($customerallBookingsObject) >= 4){
                $limitedCustomerData = array_slice($customerallBookingsObject, 0, 4);
            }else{
                $limitedCustomerData = $limitedCustomerData;
            }
            
            $series_data = [];
            $categories_data = [];
            
            if($request_data->date_type == 'all_data'){
                
                $currentYear = date('Y');
                $monthsData = [];
            
                for ($month = 1; $month <= 12; $month++) {
                    
                     $startOfMonth = Carbon::create($currentYear, $month, 1)->startOfMonth();
                     $endOfMonth = Carbon::create($currentYear, $month, 1)->endOfMonth();
                     
                      $categories_data[] = $startOfMonth->format('F');
                     
                     $startOfMonth = $startOfMonth->format('Y-m-d');
                     $endOfMonth = $endOfMonth->format('Y-m-d');
    
                    $monthsData[] = (Object)[
                        'month' => $month,
                        'start_date' => $startOfMonth,
                        'end_date' => $endOfMonth,
                    ];
                }
                
                foreach($limitedCustomerData as $cust_res){
                    
               
                    
                    $customer_booking_data = [];
                    foreach($monthsData as $month_res){
                        // Add 7 days to the start date
                        
                        $customerInvoices = DB::table('add_manage_invoices')->where('booking_customer_id',$cust_res->booking_customer_id)
                                                                          ->whereDate('add_manage_invoices.created_at','>=', $month_res->start_date)
                                                                          ->whereDate('add_manage_invoices.created_at','<=', $month_res->end_date)
                                                                          ->Sum('total_sale_price_all_Services');

                        $customerPackageBookings =  DB::table('cart_details')->whereJsonContains('cart_total_data->customer_id',"$cust_res->booking_customer_id")
                                                                  ->whereDate('cart_details.created_at','>=', $month_res->start_date)
                                                                  ->whereDate('cart_details.created_at','<=', $month_res->end_date)
                                                                  ->Sum('tour_total_price');
                                                                   
                        $total_booking_price = $customerInvoices + $customerPackageBookings;
                                        
                      
                        
                        $customer_booking_data[] = floor($total_booking_price * 100) / 100;
                       
                    }
                    
                    $series_data[] = [
                            'name' => $cust_res->name,
                            'data' => $customer_booking_data
                        ];
                }
     
     
       
            }
            
            if($request_data->date_type == 'data_today_wise'){
            
                $date = date('Y-m-d');
                foreach($limitedCustomerData as $cust_res){
                    
                    $customer_booking_data = [$cust_res->total_prices];
                    $series_data[] = [
                            'name' => $cust_res->name,
                            'data' => $customer_booking_data
                        ];
                }
                
                $categories_data = [$date];
            }
            
            if($request_data->date_type == 'data_Yesterday_wise'){
            
                $date = date('Y-m-d',strtotime("-1 days"));
                foreach($limitedCustomerData as $cust_res){
                    
                    $customer_booking_data = [$cust_res->total_prices];
                    $series_data[] = [
                            'name' => $cust_res->name,
                            'data' => $customer_booking_data
                        ];
                }
                
                $categories_data = [$date];
            }
            
            // Generate Graph Data For Week
            if($request_data->date_type == 'data_week_wise'){
               $today = Carbon::now();
                
                // Create a CarbonPeriod instance for the current week
               $startOfWeek = $today->startOfWeek()->toDateString();
                $endOfWeek = $today->endOfWeek()->toDateString();
                
                // Create a CarbonPeriod instance for the current week
                $period = CarbonPeriod::create($startOfWeek, $endOfWeek);
                
                // Get the dates of the current week as an array
                $datesOfWeek = [];
                foreach ($period as $date) {
                    $datesOfWeek[] = $date->toDateString();
                }
                
                // Get Data for Agent 
                
                foreach($limitedCustomerData as $cust_res){
                    
                    // Loop On DatesOfWeek
                    
                    $customer_booking_data = [];
                    foreach($datesOfWeek as $date_res){
                        $customerInvoices = DB::table('add_manage_invoices')->where('booking_customer_id',$cust_res->booking_customer_id)
                                                                          ->whereDate('created_at',$date_res)
                                                                          ->Sum('total_sale_price_all_Services');

                        $customerPackageBookings =  DB::table('cart_details')->whereJsonContains('cart_total_data->customer_id',"$cust_res->booking_customer_id")
                                                                  ->whereDate('created_at',$date_res)
                                                                  ->Sum('tour_total_price');
                                                                   
                        $total_booking_price = $customerInvoices + $customerPackageBookings;
                                        
                      
                        
                        $customer_booking_data[] = floor($total_booking_price * 100) / 100;
                    }
                    
                    $series_data[] = [
                            'name' => $cust_res->name,
                            'data' => $customer_booking_data
                        ];
                }
                
                // Get Data For Categories
                
                $dayNamesOfWeek = [];
                for ($day = 0; $day < 7; $day++) {
                    $dayNamesOfWeek[] = $today->startOfWeek()->addDays($day)->format('l'); // 'l' format represents the full day name
                }
                
                $categories_data = $dayNamesOfWeek;
            }
            
            if($request_data->date_type == 'data_month_wise'){
                
                foreach($limitedCustomerData as $cust_res){
                    
                    // Start Date Of Month
                    // $currentDate = Carbon::now();
                    $startDate = Carbon::now()->startOfMonth();
                    // $startDate = $currentDate->subMonth()->firstOfMonth();
                    $startDateWeek = $startDate->toDateString();
                    $endDate = $startDate->copy()->addDays(6);
                    $endDateWeek = $endDate->toDateString();
                    
                    $customer_booking_data = [];
                    for($i=1; $i<=5; $i++){
                        // Add 7 days to the start date
                        
                        $customerInvoices = DB::table('add_manage_invoices')->where('booking_customer_id',$cust_res->booking_customer_id)
                                                                          ->whereDate('add_manage_invoices.created_at','>=', $startDate)
                                                                          ->whereDate('add_manage_invoices.created_at','<=', $endDate)
                                                                          ->Sum('total_sale_price_all_Services');

                        $customerPackageBookings =  DB::table('cart_details')->whereJsonContains('cart_total_data->customer_id',"$cust_res->booking_customer_id")
                                                                  ->whereDate('cart_details.created_at','>=', $startDate)
                                                                  ->whereDate('cart_details.created_at','<=', $endDate)
                                                                  ->Sum('tour_total_price');
                                                                   
                        $total_booking_price = $customerInvoices + $customerPackageBookings;
                                        
                      
                        
                        $customer_booking_data[] = floor($total_booking_price * 100) / 100;;
                        
                        
                        $startDate = $endDate->copy()->addDays(1);
                        if($i == '4'){
                            $endDate = $startDate->copy()->addDays(2);
                        }else{
                             $endDate = $startDate->copy()->addDays(6);
                        }
                        
                        $startDateWeek = $startDate->toDateString();
                        $endDateWeek = $endDate->toDateString();
                    }
                    
                    $series_data[] = [
                            'name' => $cust_res->name,
                            'data' => $customer_booking_data
                        ];
                }
                    
               
                    // dd($startDateWeek,$endDateWeek);
                //   print_r($weekDates);
                //   die;
                $categories_data = ['1st Week','2nd Week','3rd Week','4th Week','5th Week'];
            }
            
            if($request_data->date_type == 'data_year_wise'){
                $currentYear = date('Y');
                $monthsData = [];
            
                for ($month = 1; $month <= 12; $month++) {
                    
                     $startOfMonth = Carbon::create($currentYear, $month, 1)->startOfMonth();
                     $endOfMonth = Carbon::create($currentYear, $month, 1)->endOfMonth();
                     
                      $categories_data[] = $startOfMonth->format('F');
                     
                     $startOfMonth = $startOfMonth->format('Y-m-d');
                     $endOfMonth = $endOfMonth->format('Y-m-d');
    
                    $monthsData[] = (Object)[
                        'month' => $month,
                        'start_date' => $startOfMonth,
                        'end_date' => $endOfMonth,
                    ];
                }
                
                foreach($limitedCustomerData as $cust_res){
                    
               
                    
                    $customer_booking_data = [];
                    foreach($monthsData as $month_res){
                        // Add 7 days to the start date
                        
                        $customerInvoices = DB::table('add_manage_invoices')->where('booking_customer_id',$cust_res->booking_customer_id)
                                                                          ->whereDate('add_manage_invoices.created_at','>=', $month_res->start_date)
                                                                          ->whereDate('add_manage_invoices.created_at','<=', $month_res->end_date)
                                                                          ->Sum('total_sale_price_all_Services');

                        $customerPackageBookings =  DB::table('cart_details')->whereJsonContains('cart_total_data->customer_id',"$cust_res->booking_customer_id")
                                                                  ->whereDate('cart_details.created_at','>=', $month_res->start_date)
                                                                  ->whereDate('cart_details.created_at','<=', $month_res->end_date)
                                                                  ->Sum('tour_total_price');
                                                                   
                        $total_booking_price = $customerInvoices + $customerPackageBookings;
                                        
                      
                        
                        $customer_booking_data[] = floor($total_booking_price * 100) / 100;
                       
                    }
                    
                    $series_data[] = [
                            'name' => $cust_res->name,
                            'data' => $customer_booking_data
                        ];
                }
     
            }
            
            // Season
            if($userData->id == 4 || $userData->id == 54){
                // dd($limitedCustomerData);
                $limitedCustomerData = $this->booking_filter_Season_Working($limitedCustomerData,$request,$request_data);
                // dd($limitedCustomerData);
            }
            // Season
            
            return response()->json([
                'status' => 'success',
                'data' => $limitedCustomerData,
                'series_data' => $series_data,
                'categories_data' => $categories_data,
            ]);
        }else{
            
            // dd($customerallBookingsObject);
            
            $customerallBookingsObject = new Collection($customerallBookingsObject);
            
            $customerallBookingsObject = $customerallBookingsObject->sortByDesc('all_bookings');
            
            $customerallBookingsObject = $customerallBookingsObject->values();
            
            $customerallBookingsObject = $customerallBookingsObject->toArray();
            
            if(sizeOf($customerallBookingsObject) >= 4){
                $limitedCustomerData = array_slice($customerallBookingsObject, 0, 4);
            }else{
                $limitedCustomerData = $limitedCustomerData;
            }
            
            $series_data = [];
            $categories_data = [];
            
            if($request_data->date_type == 'all_data'){
                
                $currentYear = date('Y');
                $monthsData = [];
            
                for ($month = 1; $month <= 12; $month++) {
                    
                     $startOfMonth = Carbon::create($currentYear, $month, 1)->startOfMonth();
                     $endOfMonth = Carbon::create($currentYear, $month, 1)->endOfMonth();
                     
                      $categories_data[] = $startOfMonth->format('F');
                     
                     $startOfMonth = $startOfMonth->format('Y-m-d');
                     $endOfMonth = $endOfMonth->format('Y-m-d');
    
                    $monthsData[] = (Object)[
                        'month' => $month,
                        'start_date' => $startOfMonth,
                        'end_date' => $endOfMonth,
                    ];
                }
                
                foreach($limitedCustomerData as $cust_res){
                    
               
                    
                    $customer_booking_data = [];
                    foreach($monthsData as $month_res){
                        // Add 7 days to the start date
                        
                        $customerInvoices = DB::table('add_manage_invoices')->where('booking_customer_id',$cust_res->booking_customer_id)
                                                                          ->whereDate('add_manage_invoices.created_at','>=', $month_res->start_date)
                                                                          ->whereDate('add_manage_invoices.created_at','<=', $month_res->end_date)
                                                                          ->count();

                        $customerPackageBookings =  DB::table('cart_details')->whereJsonContains('cart_total_data->customer_id',"$cust_res->booking_customer_id")
                                                                  ->whereDate('cart_details.created_at','>=', $month_res->start_date)
                                                                  ->whereDate('cart_details.created_at','<=', $month_res->end_date)
                                                                  ->count();
                                                                   
                        $total_booking_price = $customerInvoices + $customerPackageBookings;
                                        
                      
                        
                        $customer_booking_data[] = floor($total_booking_price * 100) / 100;
                       
                    }
                    
                    $series_data[] = [
                            'name' => $cust_res->name,
                            'data' => $customer_booking_data
                        ];
                }
     
     
       
            }
            
            if($request_data->date_type == 'data_today_wise'){
            
                $date = date('Y-m-d');
                foreach($limitedCustomerData as $cust_res){
                    
                    $customer_booking_data = [$cust_res->all_bookings];
                    $series_data[] = [
                            'name' => $cust_res->name,
                            'data' => $customer_booking_data
                        ];
                }
                
                $categories_data = [$date];
            }
            
            if($request_data->date_type == 'data_Yesterday_wise'){
            
                $date = date('Y-m-d',strtotime("-1 days"));
                foreach($limitedCustomerData as $cust_res){
                    
                    $customer_booking_data = [$cust_res->all_bookings];
                    $series_data[] = [
                            'name' => $cust_res->name,
                            'data' => $customer_booking_data
                        ];
                }
                
                $categories_data = [$date];
            }
            
            // Generate Graph Data For Week
            if($request_data->date_type == 'data_week_wise'){
               $today = Carbon::now();
                
                // Create a CarbonPeriod instance for the current week
               $startOfWeek = $today->startOfWeek()->toDateString();
                $endOfWeek = $today->endOfWeek()->toDateString();
                
                // Create a CarbonPeriod instance for the current week
                $period = CarbonPeriod::create($startOfWeek, $endOfWeek);
                
                // Get the dates of the current week as an array
                $datesOfWeek = [];
                foreach ($period as $date) {
                    $datesOfWeek[] = $date->toDateString();
                }
                
                // Get Data for Agent 
                
                foreach($limitedCustomerData as $cust_res){
                    
                    // Loop On DatesOfWeek
                    
                    $customer_booking_data = [];
                    foreach($datesOfWeek as $date_res){
                        $customerInvoices = DB::table('add_manage_invoices')->where('booking_customer_id',$cust_res->booking_customer_id)
                                                                          ->whereDate('created_at',$date_res)
                                                                          ->count();

                        $customerPackageBookings =  DB::table('cart_details')->whereJsonContains('cart_total_data->customer_id',"$cust_res->booking_customer_id")
                                                                  ->whereDate('created_at',$date_res)
                                                                  ->count();
                                                                   
                        $total_booking_price = $customerInvoices + $customerPackageBookings;
                                        
                      
                        
                        $customer_booking_data[] = floor($total_booking_price * 100) / 100;
                    }
                    
                    $series_data[] = [
                            'name' => $cust_res->name,
                            'data' => $customer_booking_data
                        ];
                }
                
                // Get Data For Categories
                
                $dayNamesOfWeek = [];
                for ($day = 0; $day < 7; $day++) {
                    $dayNamesOfWeek[] = $today->startOfWeek()->addDays($day)->format('l'); // 'l' format represents the full day name
                }
                
                $categories_data = $dayNamesOfWeek;
            }
            
            if($request_data->date_type == 'data_month_wise'){
                
                foreach($limitedCustomerData as $cust_res){
                    
                    // Start Date Of Month
                    // $currentDate = Carbon::now();
                    $startDate = Carbon::now()->startOfMonth();
                    // $startDate = $currentDate->subMonth()->firstOfMonth();
                    $startDateWeek = $startDate->toDateString();
                    $endDate = $startDate->copy()->addDays(6);
                    $endDateWeek = $endDate->toDateString();
                    
                    $customer_booking_data = [];
                    for($i=1; $i<=5; $i++){
                        // Add 7 days to the start date
                        
                        $customerInvoices = DB::table('add_manage_invoices')->where('booking_customer_id',$cust_res->booking_customer_id)
                                                                          ->whereDate('add_manage_invoices.created_at','>=', $startDate)
                                                                          ->whereDate('add_manage_invoices.created_at','<=', $endDate)
                                                                          ->count();

                        $customerPackageBookings =  DB::table('cart_details')->whereJsonContains('cart_total_data->customer_id',"$cust_res->booking_customer_id")
                                                                  ->whereDate('cart_details.created_at','>=', $startDate)
                                                                  ->whereDate('cart_details.created_at','<=', $endDate)
                                                                  ->count();
                                                                   
                        $total_booking_price = $customerInvoices + $customerPackageBookings;
                                        
                      
                        
                        $customer_booking_data[] = floor($total_booking_price * 100) / 100;;
                        
                        
                        $startDate = $endDate->copy()->addDays(1);
                        if($i == '4'){
                            $endDate = $startDate->copy()->addDays(2);
                        }else{
                             $endDate = $startDate->copy()->addDays(6);
                        }
                        
                        $startDateWeek = $startDate->toDateString();
                        $endDateWeek = $endDate->toDateString();
                    }
                    
                    $series_data[] = [
                            'name' => $cust_res->name,
                            'data' => $customer_booking_data
                        ];
                }
                    
               
                    // dd($startDateWeek,$endDateWeek);
                //   print_r($weekDates);
                //   die;
                $categories_data = ['1st Week','2nd Week','3rd Week','4th Week','5th Week'];
            }
            
            if($request_data->date_type == 'data_year_wise'){
                $currentYear = date('Y');
                $monthsData = [];
            
                for ($month = 1; $month <= 12; $month++) {
                    
                     $startOfMonth = Carbon::create($currentYear, $month, 1)->startOfMonth();
                     $endOfMonth = Carbon::create($currentYear, $month, 1)->endOfMonth();
                     
                      $categories_data[] = $startOfMonth->format('F');
                     
                     $startOfMonth = $startOfMonth->format('Y-m-d');
                     $endOfMonth = $endOfMonth->format('Y-m-d');
    
                    $monthsData[] = (Object)[
                        'month' => $month,
                        'start_date' => $startOfMonth,
                        'end_date' => $endOfMonth,
                    ];
                }
                
                foreach($limitedCustomerData as $cust_res){
                    
               
                    
                    $customer_booking_data = [];
                    foreach($monthsData as $month_res){
                        // Add 7 days to the start date
                        
                        $customerInvoices = DB::table('add_manage_invoices')->where('booking_customer_id',$cust_res->booking_customer_id)
                                                                          ->whereDate('add_manage_invoices.created_at','>=', $month_res->start_date)
                                                                          ->whereDate('add_manage_invoices.created_at','<=', $month_res->end_date)
                                                                          ->count();

                        $customerPackageBookings =  DB::table('cart_details')->whereJsonContains('cart_total_data->customer_id',"$cust_res->booking_customer_id")
                                                                  ->whereDate('cart_details.created_at','>=', $month_res->start_date)
                                                                  ->whereDate('cart_details.created_at','<=', $month_res->end_date)
                                                                  ->count();
                                                                   
                        $total_booking_price = $customerInvoices + $customerPackageBookings;
                                        
                      
                        
                        $customer_booking_data[] = floor($total_booking_price * 100) / 100;
                       
                    }
                    
                    $series_data[] = [
                            'name' => $cust_res->name,
                            'data' => $customer_booking_data
                        ];
                }
     
            }
            
            // Season
            if($userData->id == 4 || $userData->id == 54){
                // dd($limitedCustomerData);
                $limitedCustomerData = $this->booking_filter_Season_Working($limitedCustomerData,$request,$request_data);
                // dd($limitedCustomerData);
            }
            // Season
            
            return response()->json([
                'status' => 'success',
                'data' => $limitedCustomerData,
                'series_data' => $series_data,
                'categories_data' => $categories_data,
            ]);
        }
    }
    
    public function all_customers_booking(Request $request){
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
                        
         $customersInvoices = DB::table('booking_customers')
                        ->leftJoin('add_manage_invoices','add_manage_invoices.booking_customer_id','booking_customers.id')
                        ->where('booking_customers.customer_id',$userData->id)
                        // ->select('booking_customers.id', 'booking_customers.name', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('COUNT(cart_details.id) as packages_booking'))
                        ->select('booking_customers.id', 'booking_customers.name','booking_customers.balance','booking_customers.currency', DB::raw('COUNT(add_manage_invoices.id) as Invoices_booking'),DB::raw('SUM(add_manage_invoices.total_sale_price_all_Services) as Invoices_prices_sum'))
                        ->groupBy('booking_customers.id')
                        ->orderBy('booking_customers.id','asc')
                        ->get();
                        
        $customersPackageBookings = DB::table('booking_customers')
                        ->leftJoin('cart_details', function ($join) {
                            $join->on('booking_customers.id', '=', DB::raw('JSON_UNQUOTE(JSON_EXTRACT(cart_details.cart_total_data, "$.customer_id"))'));
                        })
                        ->where('booking_customers.customer_id',$userData->id)
                        ->select('booking_customers.id', 'booking_customers.name','booking_customers.balance',DB::raw('COUNT(cart_details.id) as packages_booking'),DB::raw('SUM(cart_details.tour_total_price) as packages_prices_sum'))
                        ->groupBy('booking_customers.id')
                        ->orderBy('booking_customers.id','asc')
                        ->get();
        
     
        $customerallBookingsObject = [];
        
        foreach($customersInvoices as $index => $invoice_res){
            $total_prices = $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum;
            $customerallBookingsObject[] = (Object)[
                    'booking_customer_id' => $invoice_res->id,
                    'name' => $invoice_res->name,
                    'currency' =>  $invoice_res->currency,
                    'Invoices_booking' => $invoice_res->Invoices_booking,
                    'Invoices_prices_sum' => number_format($invoice_res->Invoices_prices_sum,2),
                    'packages_booking' => $customersPackageBookings[$index]->packages_booking,
                    'packages_prices_sum' => number_format($customersPackageBookings[$index]->packages_prices_sum,2),
                    'all_bookings' => $invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking,
                    'all_bookings_num_format' => number_format($invoice_res->Invoices_booking + $customersPackageBookings[$index]->packages_booking),
                    'total_prices' => $invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,
                    'total_prices_num_format' => number_format($invoice_res->Invoices_prices_sum + $customersPackageBookings[$index]->packages_prices_sum,2),
                    'Paid' => number_format($total_prices - $invoice_res->balance,2),
                    'remaining' => number_format($invoice_res->balance,2)
                ];
        }
        
        $customerallBookingsObject = new Collection($customerallBookingsObject);

        $customerallBookingsObject = $customerallBookingsObject->sortByDesc('total_prices');
        
        $customerallBookingsObject = $customerallBookingsObject->values();
        
        $customerallBookingsObject = $customerallBookingsObject->toArray();
        

     
       return response()->json([
                    'status' => 'success',
                    'data' => $customerallBookingsObject,
                ]);
    }
    
    public function index(Request $request){
        
        // dd('STOP1');
        
        $pre_Week                       = date("Y-m-d", strtotime("-10 days"));
        $pre_Week1                      = date("Y-m-d", strtotime("-4 days"));
        $current_week                   = $this->getCurrentWeek();
        $currentYear                    = date("Y");
        $currentMonth                   = date("m");
        
        $packages_tour                  = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->count();
        $no_of_pax_days                 = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->sum('no_of_pax_days');
        $booked_tourA                   = DB::table('tours_bookings')->join('cart_details','tours_bookings.id','cart_details.booking_id')->where('cart_details.pakage_type','tour')->sum('adults');
        $booked_tourC                   = DB::table('tours_bookings')->join('cart_details','tours_bookings.id','cart_details.booking_id')->where('cart_details.pakage_type','tour')->sum('childs');
        $toTal                          = DB::table('cart_details')->where('pakage_type','tour')->sum('price');
        $recieved                       = DB::table('view_booking_payment_recieve')->sum('remaining_amount');
        $activities_count               = DB::table('new_activites')->count();
        $activities_no_of_pax_days      = DB::table('new_activites')->sum('max_people');
        $booked_activitiesA             = DB::table('tours_bookings')->join('cart_details','tours_bookings.id','cart_details.booking_id')->where('cart_details.pakage_type','activity')->sum('adults');
        $booked_activitiesC             = DB::table('tours_bookings')->join('cart_details','tours_bookings.id','cart_details.booking_id')->where('cart_details.pakage_type','activity')->sum('childs');
        $toTal_activities               = DB::table('cart_details')->where('pakage_type','activity')->sum('price');
        $recieved_activities            = DB::table('view_booking_payment_recieve_Activity')->sum('remaining_amount');
        $data1                          = DB::table('cart_details')->get();
        $data3                          = DB::table('tours_bookings')->join('customer_subcriptions','tours_bookings.customer_id','customer_subcriptions.id')->join('cart_details','tours_bookings.id','cart_details.booking_id')->where('cart_details.pakage_type','activity')->get();
        $latest_packages                = DB::table('tours_bookings')->join('customer_subcriptions','tours_bookings.customer_id','customer_subcriptions.id')->join('cart_details','tours_bookings.id','cart_details.booking_id')->get();
        $new_activites                  = DB::table('new_activites')->get();
        $tours                          = DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->get();
        $data2                          = DB::table('tours_bookings')->join('customer_subcriptions','tours_bookings.customer_id','customer_subcriptions.id')->join('cart_details','tours_bookings.id','cart_details.booking_id')->where('cart_details.pakage_type','tour')->get();
        
        for($i = 1; $i<=12; $i++){
            $months[]                   = $this->getMonthsName($i);
            $month                      = $i;
            if($i < 10){ $month         = 0 . $i; }
            $total_bookings             = DB::table('cart_details')->whereMonth('created_at', '=', $month)->whereYear('created_at', '=', $currentYear)->where('pakage_type','tour')->count();
            $package_month[]            = $total_bookings;
            $total_bookings1            = DB::table('cart_details')->whereMonth('created_at', '=', $month)->whereYear('created_at', '=', $currentYear)->where('pakage_type','activity')->count();
            $package_month1[]           = $total_bookings1;
        }
        
        for($i = 1; $i<=7; $i++){
            $weeks[]                    = $this->getWeeksName($i);
            $week                       = $i;
            if($i < 8){ $week           = 0 . $i; }
            $total_bookings_w           = DB::table('cart_details')->whereDay('created_at', '=', $week)->whereMonth('created_at', '=', $currentMonth)->whereYear('created_at', '=', $currentYear)->where('pakage_type','tour')->count();
            $package_weeks[]            = $total_bookings_w;
            $total_bookings_w1          = DB::table('cart_details')->whereDay('created_at', '=', $week)->whereMonth('created_at', '=', $currentMonth)->whereYear('created_at', '=', $currentYear)->where('pakage_type','activity')->count();
            $package_weeks1[]           = $total_bookings_w1;
        }
        
        // dd('STOP2');
        
        $travellanda_HotelBooking   = DB::table('hotel_booking')
                                        ->where('hotel_reservation','confirmed')
                                        ->where('provider','travellanda')
                                        ->select(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(travellandaSelectionRS, '$[*].TotalPrice')) as TotalPrice"))
                                        ->get();
        
        $hotelbeds_HotelBooking     = DB::table('hotel_booking')
                                        ->where('hotel_reservation','confirmed')
                                        ->where('provider','hotelbeds')
                                        ->select(DB::raw("JSON_EXTRACT(hotelbedSelectionRS, '$.hotel.totalNet') as TotalPrice"))
                                        ->get();
        
        $tbo_HotelBooking           = DB::table('hotel_booking')
                                        ->where('hotel_reservation','confirmed')
                                        ->where('provider','tbo')
                                        ->select(DB::raw("JSON_EXTRACT(tboSelectionRS, '$.HotelResult') as HotelResult"))
                                        ->get();
        
        $ratehawk_HotelBooking      = DB::table('hotel_booking')
                                        ->where('hotel_reservation','confirmed')
                                        ->where('provider','ratehawk')
                                        ->select(DB::raw("JSON_EXTRACT(ratehawk_details_rs1, '$.hotels') as hotels"))
                                        ->get();
        
        $hotels_HotelBooking        = DB::table('hotel_booking')
                                        ->where('hotel_reservation','confirmed')
                                        ->where('provider','hotels')
                                        ->select(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(rooms_checkavailability, '$[*].rooms_price')) as rooms_price"))
                                        ->get();
                                        
        // Separate Revenue Invoice
        $separate_Revenue_accomodation      = DB::table('add_manage_invoices')
                                                ->whereJsonContains('services',['accomodation_tab'])
                                                ->select('accomodation_details','accomodation_details_more')
                                                ->get();
        
        $separate_Revenue_flight            = DB::table('add_manage_invoices')
                                                ->whereJsonContains('services',['flights_tab'])
                                                ->select('no_of_pax_days','flights_details','flights_details_more','markup_details')
                                                ->get();
        
        $separate_Revenue_visa              = DB::table('add_manage_invoices')
                                                ->whereJsonContains('services',['visa_tab'])
                                                ->select('visa_fee','no_of_pax_days','total_visa_markup_value','visa_Pax','more_visa_details','markup_details')
                                                ->get();
        
        $separate_Revenue_transportation    = DB::table('add_manage_invoices')
                                                ->whereJsonContains('services',['transportation_tab'])
                                                ->select('no_of_pax_days','transportation_details','transportation_details_more','markup_details')
                                                ->get();
        // End
        
        // Separate Revenue and Cost Package and Activity Bookings
        $separate_package_booking   = DB::table('tours_bookings')->join('cart_details','tours_bookings.id','cart_details.booking_id')->where('cart_details.pakage_type','tour')->get();
        if(count($separate_package_booking) > 0){
            $separate_package_grand_profit = 0;
            $separate_package_cost_price   = 0;
            $separate_package_Revenue      = 0;
            foreach($separate_package_booking as $tour_res){
                $tours_costing  = DB::table('tours_2')
                                    ->where('tour_id',$tour_res->tour_id)
                                    ->select('quad_cost_price','triple_cost_price','double_cost_price','without_acc_cost_price','child_grand_total_cost_price','infant_total_cost_price')
                                    ->first();
                $cart_all_data  = json_decode($tour_res->cart_total_data); 
                     
                if(isset($cart_all_data->double_adults) && $cart_all_data->double_adults > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_adult_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_adults;
                        $double_profit = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    }else{
                        $double_adult_total_cost = 1 * $cart_all_data->double_adults;
                        $double_profit  = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    }
                    $separate_package_grand_profit += $double_profit;
                    $separate_package_cost_price += $double_adult_total_cost;
                    $separate_package_Revenue += $cart_all_data->double_adult_total;
                }
                 
                if(isset($cart_all_data->triple_adults) && $cart_all_data->triple_adults > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_adult_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    }else{
                        $triple_adult_total_cost = 1 * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    }
                    $separate_package_grand_profit += $triple_profit;
                    $separate_package_cost_price += $triple_adult_total_cost;
                    $separate_package_Revenue += $cart_all_data->triple_adult_total;
                }
                 
                if(isset($cart_all_data->quad_adults) && $cart_all_data->quad_adults > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_adult_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    }else{
                        $quad_adult_total_cost = 1 * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    }
                    $separate_package_grand_profit += $quad_profit;
                    $separate_package_cost_price += $quad_adult_total_cost;
                    $separate_package_Revenue += $cart_all_data->quad_adult_total;
                }
                 
                if(isset($cart_all_data->without_acc_adults) && $cart_all_data->without_acc_adults > 0){
                    if(isset($tours_costing->without_acc_cost_price) && $tours_costing->without_acc_cost_price != null && $tours_costing->without_acc_cost_price != ''){
                        $without_acc_adult_total_cost = $tours_costing->without_acc_cost_price * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    }else{
                        $without_acc_adult_total_cost = 1 * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    }
                    $separate_package_grand_profit += $without_acc_profit;
                    $separate_package_cost_price += $without_acc_adult_total_cost;
                    $separate_package_Revenue += $cart_all_data->without_acc_adult_total;
                }
                 
                if(isset($cart_all_data->double_childs) && $cart_all_data->double_childs > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_child_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    }else{
                        $double_child_total_cost = 1 * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    }
                    $separate_package_grand_profit += $double_profit;
                    $separate_package_cost_price += $double_child_total_cost;
                    $separate_package_Revenue += $cart_all_data->double_childs_total;
                }
             
                if(isset($cart_all_data->triple_childs) && $cart_all_data->triple_childs > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_child_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    }else{
                        $triple_child_total_cost = 1 * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    }
                    $separate_package_grand_profit += $triple_profit;
                    $separate_package_cost_price += $triple_child_total_cost;
                    $separate_package_Revenue += $cart_all_data->triple_childs_total;
                }
                
                if(isset($cart_all_data->quad_childs) && $cart_all_data->quad_childs > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_child_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    }else{
                        $quad_child_total_cost = 1 * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    }
                    $separate_package_grand_profit += $quad_profit;
                    $separate_package_cost_price += $quad_child_total_cost;
                    $separate_package_Revenue += $cart_all_data->quad_child_total;
                }
                
                if(isset($cart_all_data->children) && $cart_all_data->children > 0){
                    if(isset($tours_costing->child_grand_total_cost_price) && $tours_costing->child_grand_total_cost_price != null && $tours_costing->child_grand_total_cost_price != ''){
                        $without_acc_child_total_cost = $tours_costing->child_grand_total_cost_price * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    }else{
                        $without_acc_child_total_cost = 1 * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    }
                    $separate_package_grand_profit += $without_acc_profit;
                    $separate_package_cost_price += $without_acc_child_total_cost;
                    $separate_package_Revenue += $cart_all_data->without_acc_child_total;
                }
    
                if(isset($cart_all_data->double_infant) && $cart_all_data->double_infant > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_infant_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    }else{
                        $double_infant_total_cost = 1 * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    }
                    $separate_package_grand_profit += $double_profit;
                    $separate_package_cost_price += $double_infant_total_cost;
                    $separate_package_Revenue += $cart_all_data->double_infant_total;
                }
                
                if(isset($cart_all_data->triple_infant) && $cart_all_data->triple_infant > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_infant_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    }else{
                        $triple_infant_total_cost = 1 * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    }
                    $separate_package_grand_profit += $triple_profit;
                    $separate_package_cost_price += $triple_infant_total_cost;
                    $separate_package_Revenue += $cart_all_data->triple_infant_total;
                }
                
                if(isset($cart_all_data->quad_infant) && $cart_all_data->quad_infant > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_infant_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    }else{
                        $quad_infant_total_cost = 1 * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    }
                    $separate_package_grand_profit += $quad_profit;
                    $separate_package_cost_price += $quad_infant_total_cost;
                    $separate_package_Revenue += $cart_all_data->quad_infant_total;
                }
                 
                if(isset($cart_all_data->infants) && $cart_all_data->infants > 0){
                    if(isset($tours_costing->infant_total_cost_price) && $tours_costing->infant_total_cost_price != null && $tours_costing->infant_total_cost_price != ''){
                        $without_acc_infant_total_cost = $tours_costing->infant_total_cost_price * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    }else{
                        $without_acc_infant_total_cost = 1 * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    }
                    $separate_package_grand_profit += $without_acc_profit;
                    $separate_package_cost_price += $without_acc_infant_total_cost;
                    $separate_package_Revenue += $cart_all_data->without_acc_infant_total;
                }
            }
        }else{
            $separate_package_grand_profit = 0;
            $separate_package_cost_price   = 0;
            $separate_package_Revenue      = 0;
        }
        
        $separate_activity_booking  = DB::table('tours_bookings')->join('cart_details','tours_bookings.id','cart_details.booking_id')->where('cart_details.pakage_type','activity')->get();
        if(count($separate_activity_booking) > 0){
            $separate_activity_grand_profit = 0;
            $separate_activity_cost_price   = 0;
            $separate_activity_Revenue      = 0;
            foreach($separate_activity_booking as $tour_res){
                $tours_costing  = DB::table('new_activites')->where('new_activites.id',$tour_res->tour_id)->first();
                $cart_all_data  = json_decode($tour_res->cart_total_data);
                
                // Profit From Double Adults
                if(isset($cart_all_data) && $cart_all_data->adults > 0){
                    //$double_adult_total_cost        = $tours_costing->cost_price * $cart_all_data->adults ?? 0;
                    $double_profit                  = $cart_all_data->adult_price - $double_adult_total_cost;
                    $separate_activity_grand_profit += $double_profit;
                    $separate_activity_cost_price   += $double_adult_total_cost;
                    $separate_activity_Revenue      += $cart_all_data->adult_price;
                }
                
                if(isset($cart_all_data) && $cart_all_data->children > 0){
                   // $double_child_total_cost        = $tours_costing->cost_price * $cart_all_data->children;
                    $double_profit                  = $cart_all_data->child_price - $double_child_total_cost;
                    $separate_activity_grand_profit += $double_profit;
                    $separate_activity_cost_price   += $double_child_total_cost;
                    $separate_activity_Revenue      += $cart_all_data->child_price;
                }
            }
        }else{
            $separate_activity_grand_profit = 0;
            $separate_activity_cost_price   = 0;
            $separate_activity_Revenue      = 0;
        }
        // End
        
        // dd('STOP3');
        
        // Top 5 Agents
        $agent_final_detail         = [];
        $agent_detail               = DB::table('Agents_detail')->get();
        $agent_detail_array         = [];
        if(isset($agent_detail) && $agent_detail != null && $agent_detail != ''){
            foreach($agent_detail as $agent_detailS){
                // Invoice
                $agent_total_bookings_count     = 0;
                $agent_total_sale_price         = 0;
                $all_invoices                   = DB::table('add_manage_invoices')->where('agent_id',$agent_detailS->id)->select('agent_id','total_sale_price_all_Services')->get();
                
                foreach($all_invoices as $all_invoicesS){
                    $agent_total_bookings_count = $agent_total_bookings_count + 1;
                    $agent_total_sale_price     = $agent_total_sale_price + $all_invoicesS->total_sale_price_all_Services;
                }
                
                // Package
                $all_tour_bookings      = DB::table('cart_details')->select('cart_total_data','price')->get();
                foreach($all_tour_bookings as $all_tour_bookingsS){
                    $cart_total_data_E = $all_tour_bookingsS->cart_total_data;
                    if(isset($cart_total_data_E) && $cart_total_data_E != null && $cart_total_data_E != ''){
                        $cart_total_data = json_decode($cart_total_data_E);
                        if(isset($cart_total_data->agent_name) && $cart_total_data->agent_name != null && $cart_total_data->agent_name != '' && $cart_total_data->agent_name > 0){
                            if($cart_total_data->agent_name == $agent_detailS->id){
                                // dd($all_tour_bookingsS);
                                $agent_total_bookings_count = $agent_total_bookings_count + 1;
                                $agent_total_sale_price     = $agent_total_sale_price + $all_tour_bookingsS->price;
                            }
                        }
                    }
                }
                
                if(isset($agent_detailS->company_name) && $agent_detailS->company_name != null && $agent_detailS->company_name != ''){
                    $A_company_name = $agent_detailS->company_name;
                }else{
                    $A_company_name = $agent_detailS->agent_Name;
                }
                
                $agent_TBCA = (object)[
                    'agent_id'                      => $agent_detailS->id,
                    'company_name'                  => $A_company_name,
                    'balance'                       => $agent_total_sale_price,
                    'agent_total_bookings_count'    => $agent_total_bookings_count,
                ];
                array_push($agent_detail_array,$agent_TBCA);
                
            }
        }
        
        $agent_detail_array_count = count($agent_detail_array);
        if($agent_detail_array_count > 0){
            $agent_balance = array();
            foreach($agent_detail_array as $agent_detail_arrayS){
                $agent_balance[] = $agent_detail_arrayS->balance;
            }
            array_multisort($agent_balance, SORT_DESC, $agent_detail_array);
            
            for($i=0; $i<5; $i++){
                array_push($agent_final_detail,$agent_detail_array[$i]);
            }
        }
        
        // Top 5 Customers
        $customer_final_detail      = [];
        $customer_detail            = DB::table('booking_customers')->get();
        $customer_detail_array      = [];
        if(isset($customer_detail) && $customer_detail != null && $customer_detail != ''){
            foreach($customer_detail as $customer_detailS){
                // Invoice
                $customer_total_bookings_count      = 0;
                $customer_total_sale_price          = 0;
                
                $all_invoices                       = DB::table('add_manage_invoices')->where('booking_customer_id',$customer_detailS->id)->select('booking_customer_id','total_sale_price_all_Services')->get();
                foreach($all_invoices as $all_invoicesS){
                    $customer_total_bookings_count  = $customer_total_bookings_count + 1;
                    $customer_total_sale_price      = $customer_total_sale_price + $all_invoicesS->total_sale_price_all_Services;
                }
                
                // Package
                $all_tour_bookings      = DB::table('cart_details')->select('id','cart_total_data','price')->get();
                foreach($all_tour_bookings as $all_tour_bookingsS){
                    $cart_total_data_E = $all_tour_bookingsS->cart_total_data;
                    if(isset($cart_total_data_E) && $cart_total_data_E != null && $cart_total_data_E != ''){
                        $cart_total_data = json_decode($cart_total_data_E);
                        if(isset($cart_total_data->customer_id) && $cart_total_data->customer_id != null && $cart_total_data->customer_id != '' && $cart_total_data->customer_id > 0){
                            if($cart_total_data->customer_id == $customer_detailS->id){
                                $customer_total_bookings_count = $customer_total_bookings_count + 1;
                                $customer_total_sale_price     = $customer_total_sale_price + $all_tour_bookingsS->price;
                            }
                        }
                    }
                }
                
                $customer_TBCA = (object)[
                    'booking_customer_id'           => $customer_detailS->id,
                    'name'                          => $customer_detailS->name,
                    'balance'                       => $customer_total_sale_price,
                    'customer_total_bookings_count' => $customer_total_bookings_count,
                ];
                array_push($customer_detail_array,$customer_TBCA);
            }
        }
        
        $customer_detail_array_count = count($customer_detail_array);
        if($customer_detail_array_count > 0){
            $customer_balance = array();
            foreach($customer_detail_array as $customer_detail_arrayS){
                $customer_balance[] = $customer_detail_arrayS->balance;
            }
            array_multisort($customer_balance, SORT_DESC, $customer_detail_array);
            
            for($i=0; $i<5; $i++){
                array_push($customer_final_detail,$customer_detail_array[$i]);
            }
        }
        
        // Top 5 Suppliers
        $supplier_final_detail          = [];
        $supplier_detail                = [];
            // Flights
        $all_flight_supplier_count      = 0;
        $flight_supplier_detail_array   = [];
        $flight_supplier_detail         = DB::table('supplier')->orderBy('id', 'DESC')->get();
        if(isset($flight_supplier_detail) && $flight_supplier_detail != null && $flight_supplier_detail != ''){
            foreach($flight_supplier_detail as $flight_supplier_detailS){
                $flight_supplier_bookings_count     = 0;
                $flight_supplier_total_sale_price   = 0;
                
                // Invoice
                $all_invoices           = DB::table('add_manage_invoices')->whereJsonContains('services',['flights_tab'])->select('flight_supplier','total_sale_price_all_Services')->get();
                foreach($all_invoices as $all_invoicesS){
                    $flight_supplier = $all_invoicesS->flight_supplier;
                    if(isset($flight_supplier) && $flight_supplier != null && $flight_supplier != ''){
                        if($flight_supplier == $flight_supplier_detailS->id){
                            $flight_supplier_bookings_count     = $flight_supplier_bookings_count + 1;
                            $all_flight_supplier_count          = $all_flight_supplier_count + 1;
                            $flight_supplier_total_sale_price   = $flight_supplier_total_sale_price + $all_invoicesS->total_sale_price_all_Services;
                        }
                    }
                }
                
                // Package
                $all_tour_bookings      = DB::table('cart_details')->select('cart_total_data')->where('pakage_type','tour')->get();
                foreach($all_tour_bookings as $all_tour_bookingsS){
                    $cart_total_data_E = $all_tour_bookingsS->cart_total_data;
                    if(isset($cart_total_data_E) && $cart_total_data_E != null && $cart_total_data_E != ''){
                        $cart_total_data    = json_decode($cart_total_data_E);
                        $tours_data         = DB::table('tours_2')->where('tour_id',$cart_total_data->tourId)->select('tour_id','flight_supplier')->first();
                        if(isset($tours_data->flight_supplier) && $tours_data->flight_supplier != null && $tours_data->flight_supplier != ''){
                            if($tours_data->flight_supplier == $flight_supplier_detailS->id){
                                $flight_supplier_bookings_count     = $flight_supplier_bookings_count + 1;
                                $all_flight_supplier_count          = $all_flight_supplier_count + 1;
                                $flight_supplier_total_sale_price   = $flight_supplier_total_sale_price + $all_invoicesS->total_sale_price_all_Services;
                            }
                        }
                    }
                }
                
                $supplier_TBCA = (object)[
                    'supplier_id'               => $flight_supplier_detailS->id,
                    'supplier_name'             => $flight_supplier_detailS->companyname,
                    'supplier_balance'          => $flight_supplier_total_sale_price,
                    'supplier_bookings_count'   => $flight_supplier_bookings_count,
                ];
                array_push($flight_supplier_detail_array,$supplier_TBCA);
                
                array_push($supplier_detail,$supplier_TBCA);
            }
        }
        
            // Hotel
        $all_hotel_supplier_count       = 0;
        $hotel_supplier_detail_array    = [];
        $hotel_supplier_detail          = DB::table('rooms_Invoice_Supplier')->get();
        if(isset($hotel_supplier_detail) && $hotel_supplier_detail != null && $hotel_supplier_detail != ''){
            foreach($hotel_supplier_detail as $hotel_supplier_detailS){
                $hotel_supplier_bookings_count     = 0;
                $hotel_supplier_total_sale_price   = 0;
                
                $invoice_N = 'Invoices';
                $package_N = 'package';
                $all_rooms = DB::table('rooms')->where('room_supplier_name',$hotel_supplier_detailS->id)->select('id','room_supplier_name')->get();
                if(isset($all_rooms) && $all_rooms != null && $all_rooms != ''){
                    foreach($all_rooms as $all_roomsS){
                        
                        // Invoice
                        $rooms_bookings_details_I = DB::table('rooms_bookings_details')->where('room_id',$all_roomsS->id)->where('booking_from',$invoice_N)->select('quantity','booking_id')->get();
                        if(isset($rooms_bookings_details_I) && $rooms_bookings_details_I != null && $rooms_bookings_details_I != ''){
                            foreach($rooms_bookings_details_I as $rooms_bookings_detailsS){
                                
                                $quantity                           = $rooms_bookings_detailsS->quantity;
                                $hotel_supplier_bookings_count      = $hotel_supplier_bookings_count + $quantity;
                                $all_hotel_supplier_count           = $all_hotel_supplier_count + $quantity;
                                
                                $all_invoices = DB::table('add_manage_invoices')->where('id',$rooms_bookings_detailsS->booking_id)->select('total_sale_price_all_Services')->first();
                                if(isset($all_invoices) && $all_invoices != null && $all_invoices != ''){
                                    $hotel_supplier_total_sale_price    = $hotel_supplier_total_sale_price + $all_invoices->total_sale_price_all_Services;   
                                }
                            }
                        }
                        
                        // Package
                        $rooms_bookings_details_P = DB::table('rooms_bookings_details')->where('room_id',$all_roomsS->id)->where('booking_from',$package_N)->select('quantity','booking_id')->get();
                        if(isset($rooms_bookings_details_P) && $rooms_bookings_details_P != null && $rooms_bookings_details_P != ''){
                            foreach($rooms_bookings_details_P as $rooms_bookings_detailsS){
                                $quantity                       = $rooms_bookings_detailsS->quantity;
                                $hotel_supplier_bookings_count  = (int)$hotel_supplier_bookings_count + (int)$quantity;
                                $all_hotel_supplier_count       = (int)$all_hotel_supplier_count + (int)$quantity;
                                
                                $all_tour_bookings = DB::table('cart_details')->where('invoice_no',$rooms_bookings_detailsS->booking_id)->select('price')->first();
                                if(isset($all_tour_bookings) && $all_tour_bookings != null && $all_tour_bookings != ''){
                                    $hotel_supplier_total_sale_price   = $hotel_supplier_total_sale_price + $all_tour_bookings->price;
                                }
                                
                            }
                        }
                    }
                }
                
                $supplier_TBCA = (object)[
                    'supplier_id'               => $hotel_supplier_detailS->id,
                    'supplier_name'             => $hotel_supplier_detailS->room_supplier_name,
                    'supplier_balance'          => $hotel_supplier_total_sale_price,
                    'supplier_bookings_count'   => $hotel_supplier_bookings_count,
                ];
                array_push($hotel_supplier_detail_array,$supplier_TBCA);
                array_push($supplier_detail,$supplier_TBCA);
            }
        }
        
            // Transfer
        $all_transfer_supplier_count        = 0;
        $transfer_supplier_detail_array     = [];
        $transfer_supplier_detail           = DB::table('transfer_Invoice_Supplier')->orderBy('id', 'DESC')->get();
        if(isset($transfer_supplier_detail) && $transfer_supplier_detail != null && $transfer_supplier_detail != ''){
            foreach($transfer_supplier_detail as $transfer_supplier_detailS){
                $transfer_supplier_bookings_count       = 0;
                $transfer_supplier_total_sale_price     = 0;
                
                // invoice
                $all_invoices           = DB::table('add_manage_invoices')->whereJsonContains('services',['transportation_tab'])->select('transfer_supplier_id','total_sale_price_all_Services')->get();
                foreach($all_invoices as $all_invoicesS){
                    if($transfer_supplier_detailS->id == $all_invoicesS->transfer_supplier_id){
                        $transfer_supplier_bookings_count   = $transfer_supplier_bookings_count + 1;
                        $all_transfer_supplier_count        = $all_transfer_supplier_count + 1;
                        $transfer_supplier_total_sale_price = $transfer_supplier_total_sale_price + $all_invoicesS->total_sale_price_all_Services;
                    }
                }
                
                // Package
                $all_tour_bookings      = DB::table('cart_details')->select('cart_total_data','price')->where('pakage_type','tour')->get();
                foreach($all_tour_bookings as $all_tour_bookingsS){
                    $cart_total_data_E = $all_tour_bookingsS->cart_total_data;
                    if(isset($cart_total_data_E) && $cart_total_data_E != null && $cart_total_data_E != ''){
                        $cart_total_data    = json_decode($cart_total_data_E);
                        $tours_data         = DB::table('tours_2')->where('tour_id',$cart_total_data->tourId)->select('tour_id','transfer_supplier_id')->first();
                        if(isset($tours_data->transfer_supplier_id) && $tours_data->transfer_supplier_id != null && $tours_data->transfer_supplier_id != ''){
                            if($tours_data->transfer_supplier_id == $transfer_supplier_detailS->id){
                                $flight_supplier_bookings_count     = $flight_supplier_bookings_count + 1;
                                $all_flight_supplier_count          = $all_flight_supplier_count + 1;
                                $flight_supplier_total_sale_price   = $flight_supplier_total_sale_price + $all_tour_bookingsS->price;
                            }
                        }
                    }
                }
                
                $supplier_TBCA = (object)[
                    'supplier_id'            => $transfer_supplier_detailS->id,
                    'supplier_name'          => $transfer_supplier_detailS->room_supplier_name,
                    'supplier_balance'       => $transfer_supplier_total_sale_price,
                    'supplier_bookings_count'=> $transfer_supplier_bookings_count,
                ];
                array_push($transfer_supplier_detail_array,$supplier_TBCA);
                array_push($supplier_detail,$supplier_TBCA);
            }
        }
        
        $supplier_detail_count = count($supplier_detail);
        if($supplier_detail_count > 0){
            $supplier_balance = array();
            foreach($supplier_detail as $supplier_detailS){
                $supplier_balance[] = $supplier_detailS->supplier_balance;
            }
            array_multisort($supplier_balance, SORT_DESC, $supplier_detail);
            
            for($i=0; $i<5; $i++){
                array_push($supplier_final_detail,$supplier_detail[$i]);
            }
        }
        
        $all_Customers      = DB::table('customer_subcriptions')->get();
        
        $lead_in_process    = 0;
        $all_leads          = DB::table('addLead')->get();
        if(isset($all_leads) && $all_leads != null && $all_leads != ''){
            foreach($all_leads as $all_leads_value){
                $lead_quotation         = DB::table('addManageQuotationPackage')->where('lead_id',$all_leads_value->id)->get();
                $lead_quotation_count   = count($lead_quotation);
                if(isset($lead_quotation) && $lead_quotation != null && $lead_quotation != ''){
                    if($lead_quotation_count > 0){
                        foreach($lead_quotation as $lead_quotation_value){
                            if($lead_quotation_value->quotation_status != '1'){
                                $lead_in_process = $lead_in_process + 1;
                            }
                        }
                    }
                }
            }
        }
        
        $tours1 =  [
            'lead_in_process'                   => $lead_in_process,
            'packages_tour'                     => $packages_tour,
            'no_of_pax_days'                    => $no_of_pax_days,
            'booked_tourA'                      => $booked_tourA,
            'booked_tourC'                      => $booked_tourC,
            'toTal'                             => $toTal,
            'recieved'                          => $recieved,
            'activities_count'                  => $activities_count,
            'activities_no_of_pax_days'         => $activities_no_of_pax_days,
            'booked_activitiesA'                => $booked_activitiesA,
            'booked_activitiesC'                => $booked_activitiesC,
            'toTal_activities'                  => $toTal_activities,
            'recieved_activities'               => $recieved_activities,
            'data1'                             => $data1,
            'data2'                             => $data2,
            'data3'                             => $data3,
            'latest_packages'                   => $latest_packages,
            'new_activites'                     => $new_activites,
            'tours'                             => $tours,
            'package_month'                     => $package_month,
            'package_month1'                    => $package_month1,
            
            'agent_final_detail'                => $agent_final_detail,
            'customer_final_detail'             => $customer_final_detail,
            'supplier_final_detail'             => $supplier_final_detail,
            
            'travellanda_HotelBooking'          => $travellanda_HotelBooking,
            'hotelbeds_HotelBooking'            => $hotelbeds_HotelBooking,
            'tbo_HotelBooking'                  => $tbo_HotelBooking,
            'ratehawk_HotelBooking'             => $ratehawk_HotelBooking,
            'hotels_HotelBooking'               => $hotels_HotelBooking,
            
            'separate_Revenue_accomodation'     => $separate_Revenue_accomodation,
            'separate_Revenue_flight'           => $separate_Revenue_flight,
            'separate_Revenue_visa'             => $separate_Revenue_visa,
            'separate_Revenue_transportation'   => $separate_Revenue_transportation,
            'separate_package_Revenue'          => $separate_package_Revenue,
            'separate_activity_Revenue'         => $separate_activity_Revenue,
            
            'separate_package_cost_price'       => $separate_package_cost_price,
            'separate_activity_cost_price'      => $separate_activity_cost_price,
            
            'all_Customers'                     => $all_Customers,
            
            'package_weeks'                     => $package_weeks,
            'package_weeks1'                    => $package_weeks1,
        ];
        
        // dd('STOP4');
        
        // $tours1                     = json_decode($response);
        
        $packages_tour              = $tours1['packages_tour'];
        $no_of_pax_days             = $tours1['no_of_pax_days'];
        $booked_tourA               = $tours1['booked_tourA'];
        $booked_tourC               = $tours1['booked_tourC'];
        $toTal                      = $tours1['toTal'];
        $recieved                   = $tours1['recieved'];
        $outStandings               = $toTal - $recieved;
        $activities_count           = $tours1['activities_count'];
        $activities_no_of_pax_days  = $tours1['activities_no_of_pax_days'];
        $booked_activitiesA         = $tours1['booked_activitiesA'];
        $booked_activitiesC         = $tours1['booked_activitiesC'];
        $toTal_activities           = $tours1['toTal_activities'];
        $recieved_activities        = $tours1['recieved_activities'];
        $activities_outStandings    = $toTal_activities - $recieved_activities;
        $tours                      = $tours1['tours'];
        $final_data                 = $tours1['data1'];
        $package_month              = $tours1['package_month'];
        $package_month1             = $tours1['package_month1'];
        $data                       = [];
        foreach($tours as $tours_res){
            $tour_book = false;
            foreach($final_data as $book_res){
                if($tours_res->id == $book_res->tour_id){
                    $tour_book = true;
                }
            }

            $single_tour = [
                'id'                 => $tours_res->id,
                'title'              => $tours_res->title,
                'no_of_pax_days'     => $tours_res->no_of_pax_days,
                'start_date'         => $tours_res->start_date,
                'end_date'           => $tours_res->end_date,
                'tour_location'      => $tours_res->tour_location,
                'tour_author'        => $tours_res->tour_author,
                'tour_publish'       => $tours_res->tour_publish,
                'no_of_pax_days'     => $tours_res->no_of_pax_days,
                'book_status'        => $tour_book,
            ];
            array_push($data,$single_tour);      
        }
        $data1                      = $tours1['data2'];
        $new_activites              = $tours1['new_activites'];
        $data3                      = $tours1['data3'];
        $latest_packages            = $tours1['latest_packages'];
        $package_weeks              = $tours1['package_weeks'];
        $package_weeks1             = $tours1['package_weeks1'];
        
        $TotalPrice_travelanda      = 0;
        $travellanda_HotelBooking   = $tours1['travellanda_HotelBooking'];
        if(isset($travellanda_HotelBooking) && $travellanda_HotelBooking != null && $travellanda_HotelBooking != ''){
            foreach($travellanda_HotelBooking as $value){
                $TotalPrice_E   = $value->TotalPrice;
                $TotalPrice_D   = json_decode($TotalPrice_E);
                foreach($TotalPrice_D as $TotalPrice_DS){
                    $TotalPrice_travelanda = $TotalPrice_travelanda + $TotalPrice_DS;
                }
            }
        }
        
        $TotalPrice_hotelbeds   = 0;
        $hotelbeds_HotelBooking = $tours1['hotelbeds_HotelBooking'];
        if(isset($hotelbeds_HotelBooking) && $hotelbeds_HotelBooking != null && $hotelbeds_HotelBooking != ''){
            foreach($hotelbeds_HotelBooking as $value){
                $TotalPrice_E           = $value->TotalPrice;
                $TotalPrice_D           = json_decode($TotalPrice_E);
                $TotalPrice_hotelbeds   = $TotalPrice_hotelbeds + $TotalPrice_D;
            }
        }
        
        $TotalPrice_tbo         = 0;
        $tbo_HotelBooking       = $tours1['tbo_HotelBooking'];
        if(isset($tbo_HotelBooking) && $tbo_HotelBooking != null && $tbo_HotelBooking != ''){
            foreach($tbo_HotelBooking as $value){
                $HotelResult_E  = $value->HotelResult;
                $HotelResult_D  = json_decode($HotelResult_E);
                foreach($HotelResult_D as $HotelResult_DS){
                    $Rooms = $HotelResult_DS->Rooms;
                    foreach($Rooms as $RoomsS){
                        $TotalFare = $RoomsS->TotalFare;
                        $TotalPrice_tbo = $TotalPrice_tbo + $TotalFare;
                    }
                }
            }
        }
        
        $TotalPrice_ratehawk    = 0;
        $ratehawk_HotelBooking  = $tours1['ratehawk_HotelBooking'];
        if(isset($ratehawk_HotelBooking) && $ratehawk_HotelBooking != null && $ratehawk_HotelBooking != ''){
            foreach($ratehawk_HotelBooking as $value){
                $hotels_E  = $value->hotels;
                $hotels_D  = json_decode($hotels_E);
                foreach($hotels_D as $hotels_DS){
                    $rates = $hotels_DS->rates;
                    foreach($rates as $ratesS){
                        $payment_options = $ratesS->payment_options->payment_types;
                        foreach($payment_options as $payment_optionsS){
                            $show_amount = $payment_optionsS->show_amount;
                            $TotalPrice_tbo = $TotalPrice_tbo + $show_amount;
                        }
                    }
                }
            }
        }
        
        $TotalPrice_hotels      = 0;
        $hotels_HotelBooking    = $tours1['hotels_HotelBooking'];
        if(isset($hotels_HotelBooking) && $hotels_HotelBooking != null && $hotels_HotelBooking != ''){
            foreach($hotels_HotelBooking as $value){
                $rooms_price_E   = $value->rooms_price;
                if(isset($rooms_price_E) && $rooms_price_E != null && $rooms_price_E != ''){
                    $rooms_price_D   = json_decode($rooms_price_E);
                    foreach($rooms_price_D as $rooms_price_DS){
                        $TotalPrice_hotels = $TotalPrice_hotels + $rooms_price_DS;
                    }
                }
            }
        }
        
        // Separate Revenue
        $separate_Revenue_accomodation_Price    = 0;
        $separate_Revenue_accomodation          = $tours1['separate_Revenue_accomodation'];
        if(isset($separate_Revenue_accomodation) && $separate_Revenue_accomodation != null && $separate_Revenue_accomodation != ''){
            foreach($separate_Revenue_accomodation as $separate_Revenue_accomodationS){
                $accomodation_details_E = $separate_Revenue_accomodationS->accomodation_details;
                if(isset($accomodation_details_E) && $accomodation_details_E != null && $accomodation_details_E != ''){
                    $accomodation_details   = json_decode($accomodation_details_E);
                    if(isset($accomodation_details)){
                        foreach($accomodation_details as $accomodation_detailsS){
                        if(isset($accomodation_detailsS->hotel_invoice_markup) && $accomodation_detailsS->hotel_invoice_markup != null && $accomodation_detailsS->hotel_invoice_markup != ''){
                            $separate_Revenue_accomodation_Price = $separate_Revenue_accomodation_Price + ($accomodation_detailsS->hotel_invoice_markup * $accomodation_detailsS->acc_qty);
                        }else{
                            $separate_Revenue_accomodation_Price = $separate_Revenue_accomodation_Price + ($accomodation_detailsS->acc_total_amount * $accomodation_detailsS->acc_qty);
                        }
                    }
                    }
                }
                $accomodation_details_more_E = $separate_Revenue_accomodationS->accomodation_details_more;
                if(isset($accomodation_details_more_E) && $accomodation_details_more_E != null && $accomodation_details_more_E != ''){
                    $accomodation_details_more   = json_decode($accomodation_details_more_E);
                    if(isset($accomodation_details_more) && $accomodation_details_more != null && $accomodation_details_more != '' && $accomodation_details_more != 'null'){
                        foreach($accomodation_details_more as $accomodation_details_moreS){
                        if(isset($accomodation_details_moreS->more_hotel_invoice_markup) && $accomodation_details_moreS->more_hotel_invoice_markup != null && $accomodation_details_moreS->more_hotel_invoice_markup != ''){
                            $separate_Revenue_accomodation_Price = $separate_Revenue_accomodation_Price + ($accomodation_details_moreS->more_hotel_invoice_markup * $accomodation_details_moreS->more_acc_qty);
                        }else{
                            $separate_Revenue_accomodation_Price = $separate_Revenue_accomodation_Price + ($accomodation_details_moreS->more_acc_total_amount * $accomodation_details_moreS->more_acc_qty);
                        }
                    }
                    }
                }
            }
        }
        $separate_Revenue_accomodation_Price = round($separate_Revenue_accomodation_Price,2);
        
        $separate_Revenue_visa_Price    = 0;
        $separate_Revenue_visa          = $tours1['separate_Revenue_visa'];
        if(isset($separate_Revenue_visa) && $separate_Revenue_visa != null && $separate_Revenue_visa != ''){
            foreach($separate_Revenue_visa as $separate_Revenue_visaS){
                if(isset($separate_Revenue_visaS->total_visa_markup_value) && $separate_Revenue_visaS->total_visa_markup_value != null && $separate_Revenue_visaS->total_visa_markup_value != ''){
                    $separate_Revenue_visa_Price = $separate_Revenue_visa_Price + ($separate_Revenue_visaS->total_visa_markup_value * $separate_Revenue_visaS->visa_Pax);
                }else{
                    $separate_Revenue_visa_Price = $separate_Revenue_visa_Price + ($separate_Revenue_visaS->visa_fee * $separate_Revenue_visaS->no_of_pax_days);
                }
                if(isset($separate_Revenue_visaS->more_visa_details) && $separate_Revenue_visaS->more_visa_details && $separate_Revenue_visaS->more_visa_details){
                    $more_visa_details = json_decode($separate_Revenue_visaS->more_visa_details);
                    if(isset($more_visa_details) && $more_visa_details != null && $more_visa_details != ''){
                        foreach($more_visa_details as $more_visa_detailsS){
                            if(isset($more_visa_detailsS->more_total_visa_markup_value) && $more_visa_detailsS->more_total_visa_markup_value != null && $more_visa_detailsS->more_total_visa_markup_value != ''){
                                $separate_Revenue_visa_Price = $separate_Revenue_visa_Price + ($more_visa_detailsS->more_total_visa_markup_value * $more_visa_detailsS->more_visa_Pax);
                            }
                        }
                    }
                }
            }
        }
        $separate_Revenue_visa_Price = round($separate_Revenue_visa_Price,2);
        
        $separate_Revenue_flight_Price  = 0;
        $separate_Revenue_flight        = $tours1['separate_Revenue_flight'];
        if(isset($separate_Revenue_flight) && $separate_Revenue_flight != null && $separate_Revenue_flight != ''){
            foreach($separate_Revenue_flight as $separate_Revenue_flightS){
                $no_of_pax_days     = $separate_Revenue_flightS->no_of_pax_days;
                $markup_details_E   = $separate_Revenue_flightS->markup_details;
                if(isset($markup_details_E) && $markup_details_E != null && $markup_details_E != ''){
                    $markup_details = json_decode($markup_details_E);
                    foreach($markup_details as $markup_detailsS){
                        if($markup_detailsS->markup_Type_Costing == 'flight_Type_Costing'){
                            if(isset($markup_detailsS->markup_price) && $markup_detailsS->markup_price != null && $markup_detailsS->markup_price != ''){
                                $separate_Revenue_flight_Price = $separate_Revenue_flight_Price + ($markup_detailsS->markup_price * $no_of_pax_days);
                            }else{
                                if(isset($markup_detailsS->without_markup_price) && $markup_detailsS->without_markup_price != null && $markup_detailsS->without_markup_price != ''){
                                    $separate_Revenue_flight_Price = $separate_Revenue_flight_Price + ($markup_detailsS->without_markup_price * $no_of_pax_days);
                                }
                            }
                        }
                    }
                }
            }
        }
        $separate_Revenue_flight_Price = round($separate_Revenue_flight_Price,2);
        
        $separate_Revenue_transportation_Price  = 0;
        $separate_Revenue_transportation        = $tours1['separate_Revenue_transportation'];
        if(isset($separate_Revenue_transportation) && $separate_Revenue_transportation != null && $separate_Revenue_transportation != ''){
            foreach($separate_Revenue_transportation as $separate_Revenue_transportationS){
                $no_of_pax_days     = $separate_Revenue_transportationS->no_of_pax_days;
                $markup_details_E   = $separate_Revenue_transportationS->markup_details;
                if(isset($markup_details_E) && $markup_details_E != null && $markup_details_E != ''){
                    $markup_details = json_decode($markup_details_E);
                    foreach($markup_details as $markup_detailsS){
                        if($markup_detailsS->markup_Type_Costing == 'transportation_Type_Costing'){
                            if(isset($markup_detailsS->markup_price) && $markup_detailsS->markup_price != null && (float)$markup_detailsS->markup_price != ''){
                                $separate_Revenue_transportation_Price = $separate_Revenue_transportation_Price + ((float)$markup_detailsS->markup_price * $no_of_pax_days);   
                            }else{
                                $separate_Revenue_transportation_Price = $separate_Revenue_transportation_Price + ((float)$markup_detailsS->without_markup_price * (float)$no_of_pax_days);
                            }
                        }
                    }
                }
            }
        }
        $separate_Revenue_transportation_Price = round($separate_Revenue_transportation_Price,2);
        
        $separate_package_Revenue   = $tours1['separate_package_Revenue'];
        $separate_activity_Revenue  = $tours1['separate_activity_Revenue'];
        $separate_revenue_hotels    = $TotalPrice_hotels + $TotalPrice_travelanda + $TotalPrice_hotelbeds + $TotalPrice_tbo + $TotalPrice_ratehawk;
        $separate_revenue_hotels    = round($separate_revenue_hotels,2);
        
        $separate_Total_Revenue = $separate_Revenue_accomodation_Price + $separate_Revenue_visa_Price + $separate_Revenue_flight_Price + $separate_Revenue_transportation_Price + $separate_activity_Revenue + $separate_package_Revenue+ $separate_revenue_hotels;
        $separate_Total_Revenue = round($separate_Total_Revenue,2);
        // End
        
        // dd('STOP5');
        
        // Separate Cost Price
        $separate_Cost_accomodation_Price   = 0;
        $separate_Revenue_accomodation      = $tours1['separate_Revenue_accomodation'];
        // dd($separate_Revenue_accomodation);
        if(isset($separate_Revenue_accomodation) && $separate_Revenue_accomodation != null && $separate_Revenue_accomodation != ''){
            foreach($separate_Revenue_accomodation as $separate_Revenue_accomodationS){
                $accomodation_details_E = $separate_Revenue_accomodationS->accomodation_details;
                if(isset($accomodation_details_E) && $accomodation_details_E != null && $accomodation_details_E != ''){
                    $accomodation_details   = json_decode($accomodation_details_E);
                    if($accomodation_details != null && $accomodation_details != '' && $accomodation_details != 'null'){
                        foreach($accomodation_details as $accomodation_detailsS){
                            if(isset($accomodation_detailsS->acc_total_amount) && $accomodation_detailsS->acc_total_amount != null && $accomodation_detailsS->acc_total_amount != ''){
                                $separate_Cost_accomodation_Price = $separate_Cost_accomodation_Price + ($accomodation_detailsS->acc_total_amount * $accomodation_detailsS->acc_qty);
                            }
                        }
                    }
                }
                $accomodation_details_more_E = $separate_Revenue_accomodationS->accomodation_details_more;
                if(isset($accomodation_details_more_E) && $accomodation_details_more_E != null && $accomodation_details_more_E != ''){
                    $accomodation_details_more   = json_decode($accomodation_details_more_E);
                    
                    if(isset($accomodation_details_more) && $accomodation_details_more != null && $accomodation_details_more != '' && $accomodation_details_more != 'null'){
                        foreach($accomodation_details_more as $accomodation_details_moreS){
                            if(isset($accomodation_details_moreS->more_acc_total_amount) && $accomodation_details_moreS->more_acc_total_amount != null && $accomodation_details_moreS->more_acc_total_amount != ''){
                                $separate_Cost_accomodation_Price = $separate_Cost_accomodation_Price + ($accomodation_details_moreS->more_acc_total_amount * $accomodation_details_moreS->more_acc_qty);
                            }
                        }
                    }
                }
            }
        }
        $separate_Cost_accomodation_Price = round($separate_Cost_accomodation_Price,2);
        
        $separate_Cost_visa_Price   = 0;
        $separate_Revenue_visa      = $tours1['separate_Revenue_visa'];
        if(isset($separate_Revenue_visa) && $separate_Revenue_visa != null && $separate_Revenue_visa != ''){
            foreach($separate_Revenue_visa as $separate_Revenue_visaS){
                if(isset($separate_Revenue_visaS->visa_Pax) && $separate_Revenue_visaS->visa_Pax != null && $separate_Revenue_visaS->visa_Pax != ''){
                    $separate_Cost_visa_Price = $separate_Cost_visa_Price + ($separate_Revenue_visaS->visa_fee * $separate_Revenue_visaS->visa_Pax);
                }else{
                    $separate_Cost_visa_Price = $separate_Cost_visa_Price + ($separate_Revenue_visaS->visa_fee * $separate_Revenue_visaS->no_of_pax_days);
                }
                if(isset($separate_Revenue_visaS->more_visa_details) && $separate_Revenue_visaS->more_visa_details && $separate_Revenue_visaS->more_visa_details){
                    $more_visa_details = json_decode($separate_Revenue_visaS->more_visa_details);
                    if(isset($more_visa_details) && $more_visa_details != null && $more_visa_details != ''){
                        foreach($more_visa_details as $more_visa_detailsS){
                            if(isset($more_visa_detailsS->more_visa_fee) && $more_visa_detailsS->more_visa_fee != null && $more_visa_detailsS->more_visa_fee != ''){
                                $separate_Cost_visa_Price = $separate_Cost_visa_Price + ($more_visa_detailsS->more_visa_fee * $more_visa_detailsS->more_visa_Pax);
                            }
                        }
                    }
                }
            }
        }
        $separate_Cost_visa_Price = round($separate_Cost_visa_Price,2);
        
        $separate_Cost_flight_Price = 0;
        $separate_Revenue_flight    = $tours1['separate_Revenue_flight'];
        if(isset($separate_Revenue_flight) && $separate_Revenue_flight != null && $separate_Revenue_flight != ''){
            foreach($separate_Revenue_flight as $separate_Revenue_flightS){
                $no_of_pax_days     = $separate_Revenue_flightS->no_of_pax_days;
                $flights_details_E  = $separate_Revenue_flightS->flights_details;
                if(isset($flights_details_E) && $flights_details_E != null && $flights_details_E != ''){
                    $flights_details = json_decode($flights_details_E);
                    if(isset($flights_details->flights_per_person_price) && $flights_details->flights_per_person_price != null && $flights_details->flights_per_person_price != ''){
                        $separate_Cost_flight_Price = $separate_Cost_flight_Price + ($flights_details->flights_per_person_price * $no_of_pax_days);
                    }
                }
            }
        }
        $separate_Cost_flight_Price = round($separate_Cost_flight_Price,2);
        
        $separate_Cost_transportation_Price = 0;
        $separate_Revenue_transportation    = $tours1['separate_Revenue_transportation'];
        if(isset($separate_Revenue_transportation) && $separate_Revenue_transportation != null && $separate_Revenue_transportation != ''){
            foreach($separate_Revenue_transportation as $separate_Revenue_transportationS){
                $no_of_pax_days             = $separate_Revenue_transportationS->no_of_pax_days;
                $transportation_details_E   = $separate_Revenue_transportationS->transportation_details;
                if(isset($transportation_details_E) && $transportation_details_E != null && $transportation_details_E != ''){
                    $transportation_details = json_decode($transportation_details_E);
                    if(isset($transportation_details->transportation_price_per_person) && $transportation_details->transportation_price_per_person != null && $transportation_details->transportation_price_per_person != ''){
                        $separate_Cost_transportation_Price = $separate_Cost_transportation_Price + ($transportation_details->transportation_price_per_person * $no_of_pax_days);
                    }
                }
            }
        }
        $separate_Cost_transportation_Price = round($separate_Cost_transportation_Price,2);
        
        $separate_package_cost_price   = $tours1['separate_package_cost_price'];
        $separate_activity_cost_price  = $tours1['separate_activity_cost_price'];
        
        $separate_Total_Cost = $separate_Cost_accomodation_Price + $separate_Cost_visa_Price + $separate_Cost_flight_Price + $separate_Cost_transportation_Price + $separate_package_cost_price + $separate_activity_cost_price + $separate_revenue_hotels;
        $separate_Total_Cost = round($separate_Total_Cost,2);
        // End
        
        // Separate Profit
        $separate_profit_accomodation = $separate_Revenue_accomodation_Price - $separate_Cost_accomodation_Price;
        $separate_profit_accomodation = round($separate_profit_accomodation,2);
        
        $separate_profit_visa = $separate_Revenue_visa_Price - $separate_Cost_visa_Price;
        $separate_profit_visa = round($separate_profit_visa,2);
        
        $separate_profit_flight = $separate_Revenue_flight_Price - $separate_Cost_flight_Price;
        $separate_profit_flight = round($separate_profit_flight,2);
        
        $separate_profit_transportation = $separate_Revenue_transportation_Price - $separate_Cost_transportation_Price;
        $separate_profit_transportation = round($separate_profit_transportation,2);
        
        $separate_profit_package = $separate_package_Revenue - $separate_package_cost_price;
        $separate_profit_package = round($separate_profit_package,2);
        
        $separate_profit_activity = $separate_activity_Revenue - $separate_activity_cost_price;
        $separate_profit_activity = round($separate_profit_activity,2);
        
        $separate_profit_hotel = $separate_revenue_hotels - $separate_revenue_hotels;
        $separate_profit_hotel = round($separate_profit_hotel,2);
        
        $separate_profit_Total = $separate_Total_Revenue - $separate_Total_Cost;
        $separate_profit_Total = round($separate_profit_Total,2);
        // End
        
        // dd('STOP6');
        
        if($separate_profit_Total > 0){
            $profit_percentage = ($separate_profit_Total * 100)/$separate_Total_Revenue;
            $profit_percentage = round($profit_percentage,2);
            Session::put('profit_percentage',$profit_percentage);
        }else{
            $profit_percentage = ($separate_Total_Revenue * 100)/$separate_profit_Total;
            $profit_percentage = round($profit_percentage,2);
            Session::put('profit_percentage',$profit_percentage);
        }
        
        if($separate_Total_Revenue > $separate_Total_Cost){
            $expense_percentage = ($separate_Total_Cost * 100)/$separate_Total_Revenue;
            $expense_percentage = round($expense_percentage,2);
            Session::put('expense_percentage',$expense_percentage);
        }else{
            $expense_percentage = ($separate_Total_Revenue * 100)/$separate_Total_Cost;
            $expense_percentage = round($expense_percentage,2);
            Session::put('profit_percentage',$expense_percentage);
        }
        
        $agent_final_detail     = $tours1['agent_final_detail'];
        $customer_final_detail  = $tours1['customer_final_detail'];
        $supplier_final_detail  = $tours1['supplier_final_detail'];
        
        $all_Customers          = $tours1['all_Customers'];
        
        $lead_in_process        = $tours1['lead_in_process'];
        Session::put('lead_in_process',$lead_in_process);
        
        // dd('STOP7');
        
        return view('template/frontend/userdashboard/index',compact(
            'lead_in_process',
            
            'separate_Total_Revenue',
            'separate_Total_Cost',
            'separate_profit_Total',
            
            'packages_tour',
            'no_of_pax_days',
            'booked_tourA',
            'booked_tourC',
            'toTal',
            'recieved',
            'outStandings',
            
            'activities_count',
            'activities_no_of_pax_days',
            'booked_activitiesA',
            'booked_activitiesC',
            
            'toTal_activities',
            'recieved_activities',
            'activities_outStandings',
            
            'data',
            'data1',
            'new_activites',
            'data3',
            
            'agent_final_detail',
            'customer_final_detail',
            'supplier_final_detail',
            
            'latest_packages',
            
            'package_month',
            'package_month1',
            
            'all_Customers',
            
            'package_weeks',
            'package_weeks1'
        ));
        
        // return view('template/frontend/userdashboard/index');
	}
	
    public function get_customerS_details(Request $request){
            $id                                 = $request->id;
            $token_id                           = $request->token_id;
            
            $separate_Revenue_accomodation      = DB::table('add_manage_invoices')
                                                    ->where('add_manage_invoices.customer_id',$request->id)
                                                    ->whereJsonContains('services',['accomodation_tab'])
                                                    ->select('accomodation_details','accomodation_details_more')
                                                    ->get();
            $separate_Revenue_flight            = DB::table('add_manage_invoices')
                                                    ->where('add_manage_invoices.customer_id',$request->id)
                                                    ->whereJsonContains('services',['flights_tab'])
                                                    ->select('no_of_pax_days','flights_details','flights_details_more','markup_details')
                                                    ->get();
            $separate_Revenue_visa              = DB::table('add_manage_invoices')
                                                    ->where('add_manage_invoices.customer_id',$request->id)
                                                    ->whereJsonContains('services',['visa_tab'])
                                                    ->select('visa_fee','no_of_pax_days','total_visa_markup_value','visa_Pax','more_visa_details','markup_details')
                                                    ->get();
            $separate_Revenue_transportation    = DB::table('add_manage_invoices')
                                                    ->where('add_manage_invoices.customer_id',$request->id)
                                                    ->whereJsonContains('services',['transportation_tab'])
                                                    ->select('no_of_pax_days','transportation_details','transportation_details_more','markup_details')
                                                    ->get();
            $travellanda_HotelBooking           = DB::table('hotel_booking')
                                                    ->where('hotel_booking.auth_token',$request->token_id)
                                                    ->where('hotel_reservation','confirmed')
                                                    ->where('provider','travellanda')
                                                    ->select(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(travellandaSelectionRS, '$[*].TotalPrice')) as TotalPrice"))
                                                    ->get();
            $hotelbeds_HotelBooking             = DB::table('hotel_booking')
                                                    ->where('hotel_booking.auth_token',$request->token_id)
                                                    ->where('hotel_reservation','confirmed')
                                                    ->where('provider','hotelbeds')
                                                    ->select(DB::raw("JSON_EXTRACT(hotelbedSelectionRS, '$.hotel.totalNet') as TotalPrice"))
                                                    ->get();
            $tbo_HotelBooking                   = DB::table('hotel_booking')
                                                    ->where('hotel_booking.auth_token',$request->token_id)
                                                    ->where('hotel_reservation','confirmed')
                                                    ->where('provider','tbo')
                                                    ->select(DB::raw("JSON_EXTRACT(tboSelectionRS, '$.HotelResult') as HotelResult"))
                                                    ->get();
            
            $ratehawk_HotelBooking              = DB::table('hotel_booking')
                                                    ->where('hotel_booking.auth_token',$request->token_id)
                                                    ->where('hotel_reservation','confirmed')
                                                    ->where('provider','ratehawk')
                                                    ->select(DB::raw("JSON_EXTRACT(ratehawk_details_rs1, '$.hotels') as hotels"))
                                                    ->get();
                                            
            $hotels_HotelBooking                = DB::table('hotel_booking')
                                                    ->where('hotel_booking.auth_token',$request->token_id)
                                                    ->where('hotel_reservation','confirmed')
                                                    ->where('provider','hotels')
                                                    ->select(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(rooms_checkavailability, '$[*].rooms_price')) as rooms_price"))
                                                    ->get();
            
            $separate_package_booking           = DB::table('tours_bookings')
                                                    ->where('tours_bookings.customer_id',$request->id)
                                                    ->join('cart_details','tours_bookings.id','cart_details.booking_id')
                                                    ->where('cart_details.pakage_type','tour')->get();
            if(count($separate_package_booking) > 0){
                $separate_package_grand_profit = 0;
                $separate_package_cost_price   = 0;
                $separate_package_Revenue      = 0;
                foreach($separate_package_booking as $tour_res){
                    $tours_costing  = DB::table('tours_2')
                                        ->where('tour_id',$tour_res->tour_id)
                                        ->select('quad_cost_price','triple_cost_price','double_cost_price','without_acc_cost_price','child_grand_total_cost_price','infant_total_cost_price')
                                        ->first();
                    $cart_all_data  = json_decode($tour_res->cart_total_data); 
                         
                    if(isset($cart_all_data->double_adults) && $cart_all_data->double_adults > 0){
                        if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                            $double_adult_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_adults;
                            $double_profit = $cart_all_data->double_adult_total - $double_adult_total_cost;
                        }else{
                            $double_adult_total_cost = 1 * $cart_all_data->double_adults;
                            $double_profit  = $cart_all_data->double_adult_total - $double_adult_total_cost;
                        }
                        $separate_package_grand_profit += $double_profit;
                        $separate_package_cost_price += $double_adult_total_cost;
                        $separate_package_Revenue += $cart_all_data->double_adult_total;
                    }
                     
                    if(isset($cart_all_data->triple_adults) && $cart_all_data->triple_adults > 0){
                        if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                            $triple_adult_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_adults;
                            $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                        }else{
                            $triple_adult_total_cost = 1 * $cart_all_data->triple_adults;
                            $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                        }
                        $separate_package_grand_profit += $triple_profit;
                        $separate_package_cost_price += $triple_adult_total_cost;
                        $separate_package_Revenue += $cart_all_data->triple_adult_total;
                    }
                     
                    if(isset($cart_all_data->quad_adults) && $cart_all_data->quad_adults > 0){
                        if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                            $quad_adult_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_adults;
                            $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                        }else{
                            $quad_adult_total_cost = 1 * $cart_all_data->quad_adults;
                            $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                        }
                        $separate_package_grand_profit += $quad_profit;
                        $separate_package_cost_price += $quad_adult_total_cost;
                        $separate_package_Revenue += $cart_all_data->quad_adult_total;
                    }
                     
                    if(isset($cart_all_data->without_acc_adults) && $cart_all_data->without_acc_adults > 0){
                        if(isset($tours_costing->without_acc_cost_price) && $tours_costing->without_acc_cost_price != null && $tours_costing->without_acc_cost_price != ''){
                            $without_acc_adult_total_cost = $tours_costing->without_acc_cost_price * $cart_all_data->without_acc_adults;
                            $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                        }else{
                            $without_acc_adult_total_cost = 1 * $cart_all_data->without_acc_adults;
                            $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                        }
                        $separate_package_grand_profit += $without_acc_profit;
                        $separate_package_cost_price += $without_acc_adult_total_cost;
                        $separate_package_Revenue += $cart_all_data->without_acc_adult_total;
                    }
                     
                    if(isset($cart_all_data->double_childs) && $cart_all_data->double_childs > 0){
                        if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                            $double_child_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_childs;
                            $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                        }else{
                            $double_child_total_cost = 1 * $cart_all_data->double_childs;
                            $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                        }
                        $separate_package_grand_profit += $double_profit;
                        $separate_package_cost_price += $double_child_total_cost;
                        $separate_package_Revenue += $cart_all_data->double_childs_total;
                    }
                 
                    if(isset($cart_all_data->triple_childs) && $cart_all_data->triple_childs > 0){
                        if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                            $triple_child_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_childs;
                            $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                        }else{
                            $triple_child_total_cost = 1 * $cart_all_data->triple_childs;
                            $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                        }
                        $separate_package_grand_profit += $triple_profit;
                        $separate_package_cost_price += $triple_child_total_cost;
                        $separate_package_Revenue += $cart_all_data->triple_childs_total;
                    }
                    
                    if(isset($cart_all_data->quad_childs) && $cart_all_data->quad_childs > 0){
                        if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                            $quad_child_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_childs;
                            $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                        }else{
                            $quad_child_total_cost = 1 * $cart_all_data->quad_childs;
                            $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                        }
                        $separate_package_grand_profit += $quad_profit;
                        $separate_package_cost_price += $quad_child_total_cost;
                        $separate_package_Revenue += $cart_all_data->quad_child_total;
                    }
                    
                    if(isset($cart_all_data->children) && $cart_all_data->children > 0){
                        if(isset($tours_costing->child_grand_total_cost_price) && $tours_costing->child_grand_total_cost_price != null && $tours_costing->child_grand_total_cost_price != ''){
                            $without_acc_child_total_cost = $tours_costing->child_grand_total_cost_price * $cart_all_data->children;
                            $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                        }else{
                            $without_acc_child_total_cost = 1 * $cart_all_data->children;
                            $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                        }
                        $separate_package_grand_profit += $without_acc_profit;
                        $separate_package_cost_price += $without_acc_child_total_cost;
                        $separate_package_Revenue += $cart_all_data->without_acc_child_total;
                    }
        
                    if(isset($cart_all_data->double_infant) && $cart_all_data->double_infant > 0){
                        if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                            $double_infant_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_infant;
                            $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                        }else{
                            $double_infant_total_cost = 1 * $cart_all_data->double_infant;
                            $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                        }
                        $separate_package_grand_profit += $double_profit;
                        $separate_package_cost_price += $double_infant_total_cost;
                        $separate_package_Revenue += $cart_all_data->double_infant_total;
                    }
                    
                    if(isset($cart_all_data->triple_infant) && $cart_all_data->triple_infant > 0){
                        if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                            $triple_infant_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_infant;
                            $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                        }else{
                            $triple_infant_total_cost = 1 * $cart_all_data->triple_infant;
                            $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                        }
                        $separate_package_grand_profit += $triple_profit;
                        $separate_package_cost_price += $triple_infant_total_cost;
                        $separate_package_Revenue += $cart_all_data->triple_infant_total;
                    }
                    
                    if(isset($cart_all_data->quad_infant) && $cart_all_data->quad_infant > 0){
                        if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                            $quad_infant_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_infant;
                            $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                        }else{
                            $quad_infant_total_cost = 1 * $cart_all_data->quad_infant;
                            $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                        }
                        $separate_package_grand_profit += $quad_profit;
                        $separate_package_cost_price += $quad_infant_total_cost;
                        $separate_package_Revenue += $cart_all_data->quad_infant_total;
                    }
                     
                    if(isset($cart_all_data->infants) && $cart_all_data->infants > 0){
                        if(isset($tours_costing->infant_total_cost_price) && $tours_costing->infant_total_cost_price != null && $tours_costing->infant_total_cost_price != ''){
                            $without_acc_infant_total_cost = $tours_costing->infant_total_cost_price * $cart_all_data->infants;
                            $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                        }else{
                            $without_acc_infant_total_cost = 1 * $cart_all_data->infants;
                            $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                        }
                        $separate_package_grand_profit += $without_acc_profit;
                        $separate_package_cost_price += $without_acc_infant_total_cost;
                        $separate_package_Revenue += $cart_all_data->without_acc_infant_total;
                    }
                }
            }else{
                $separate_package_grand_profit = 0;
                $separate_package_cost_price   = 0;
                $separate_package_Revenue      = 0;
            }
            
            
            $separate_activity_booking  = DB::table('tours_bookings')->join('cart_details','tours_bookings.id','cart_details.booking_id')
                                            ->where('tours_bookings.customer_id',$request->id)
                                            ->where('cart_details.pakage_type','activity')
                                            ->get();
            if(count($separate_activity_booking) > 0){
                $separate_activity_grand_profit = 0;
                $separate_activity_cost_price   = 0;
                $separate_activity_Revenue      = 0;
                foreach($separate_activity_booking as $tour_res){
                    $tours_costing  = DB::table('new_activites')->where('new_activites.id',$tour_res->tour_id)->first();
                    $cart_all_data  = json_decode($tour_res->cart_total_data);
                    
                    // Profit From Double Adults
                    if(isset($cart_all_data) && $cart_all_data->adults > 0){
                        $double_adult_total_cost        = $tours_costing->cost_price * $cart_all_data->adults;
                        $double_profit                  = $cart_all_data->adult_price - $double_adult_total_cost;
                        $separate_activity_grand_profit += $double_profit;
                        $separate_activity_cost_price   += $double_adult_total_cost;
                        $separate_activity_Revenue      += $cart_all_data->adult_price;
                    }
                    
                    if(isset($cart_all_data) && $cart_all_data->children > 0){
                        $double_child_total_cost        = $tours_costing->cost_price * $cart_all_data->children;
                        $double_profit                  = $cart_all_data->child_price - $double_child_total_cost;
                        $separate_activity_grand_profit += $double_profit;
                        $separate_activity_cost_price   += $double_child_total_cost;
                        $separate_activity_Revenue      += $cart_all_data->child_price;
                    }
                }
            }else{
                $separate_activity_grand_profit = 0;
                $separate_activity_cost_price   = 0;
                $separate_activity_Revenue      = 0;
            }
            
            
            $tours1 = [
                'separate_Revenue_accomodation'     => $separate_Revenue_accomodation,
                'separate_Revenue_flight'           => $separate_Revenue_flight,
                'separate_Revenue_visa'             => $separate_Revenue_visa,
                'separate_Revenue_transportation'   => $separate_Revenue_transportation,
                
                'travellanda_HotelBooking'          => $travellanda_HotelBooking,
                'hotelbeds_HotelBooking'            => $hotelbeds_HotelBooking,
                'tbo_HotelBooking'                  => $tbo_HotelBooking,
                'ratehawk_HotelBooking'             => $ratehawk_HotelBooking,
                'hotels_HotelBooking'               => $hotels_HotelBooking,
                
                'separate_package_Revenue'          => $separate_package_Revenue,
                'separate_package_cost_price'       => $separate_package_cost_price,
                
                'separate_activity_Revenue'         => $separate_activity_Revenue,
                'separate_activity_cost_price'      => $separate_activity_cost_price,
            ];
            
            // $tours1 = json_decode($response);
            
            // dd($tours1);
            
            // Revenue
            $TotalPrice_travelanda      = 0;
            $travellanda_HotelBooking   = $tours1['travellanda_HotelBooking'];
            if(isset($travellanda_HotelBooking) && $travellanda_HotelBooking != null && $travellanda_HotelBooking != ''){
                foreach($travellanda_HotelBooking as $value){
                    $TotalPrice_E   = $value->TotalPrice;
                    $TotalPrice_D   = json_decode($TotalPrice_E);
                    foreach($TotalPrice_D as $TotalPrice_DS){
                        $TotalPrice_travelanda = $TotalPrice_travelanda + $TotalPrice_DS;
                    }
                }
            }
            
            $TotalPrice_hotelbeds   = 0;
            $hotelbeds_HotelBooking = $tours1['hotelbeds_HotelBooking'];
            if(isset($hotelbeds_HotelBooking) && $hotelbeds_HotelBooking != null && $hotelbeds_HotelBooking != ''){
                foreach($hotelbeds_HotelBooking as $value){
                    $TotalPrice_E           = $value->TotalPrice;
                    $TotalPrice_D           = json_decode($TotalPrice_E);
                    $TotalPrice_hotelbeds   = $TotalPrice_hotelbeds + $TotalPrice_D;
                }
            }
            
            $TotalPrice_tbo         = 0;
            $tbo_HotelBooking       = $tours1['tbo_HotelBooking'];
            if(isset($tbo_HotelBooking) && $tbo_HotelBooking != null && $tbo_HotelBooking != ''){
                foreach($tbo_HotelBooking as $value){
                    $HotelResult_E  = $value->HotelResult;
                    $HotelResult_D  = json_decode($HotelResult_E);
                    foreach($HotelResult_D as $HotelResult_DS){
                        $Rooms = $HotelResult_DS->Rooms;
                        foreach($Rooms as $RoomsS){
                            $TotalFare = $RoomsS->TotalFare;
                            $TotalPrice_tbo = $TotalPrice_tbo + $TotalFare;
                        }
                    }
                }
            }
            
            $TotalPrice_ratehawk    = 0;
            $ratehawk_HotelBooking  = $tours1['ratehawk_HotelBooking'];
            if(isset($ratehawk_HotelBooking) && $ratehawk_HotelBooking != null && $ratehawk_HotelBooking != ''){
                foreach($ratehawk_HotelBooking as $value){
                    $hotels_E  = $value->hotels;
                    $hotels_D  = json_decode($hotels_E);
                    foreach($hotels_D as $hotels_DS){
                        $rates = $hotels_DS->rates;
                        foreach($rates as $ratesS){
                            $payment_options = $ratesS->payment_options->payment_types;
                            foreach($payment_options as $payment_optionsS){
                                $show_amount = $payment_optionsS->show_amount;
                                $TotalPrice_tbo = $TotalPrice_tbo + $show_amount;
                            }
                        }
                    }
                }
            }
            
            $TotalPrice_hotels      = 0;
            $hotels_HotelBooking    = $tours1['hotels_HotelBooking'];
            if(isset($hotels_HotelBooking) && $hotels_HotelBooking != null && $hotels_HotelBooking != ''){
                foreach($hotels_HotelBooking as $value){
                    $rooms_price_E   = $value->rooms_price;
                    if(isset($rooms_price_E) && $rooms_price_E != null && $rooms_price_E != ''){
                        $rooms_price_D   = json_decode($rooms_price_E);
                        foreach($rooms_price_D as $rooms_price_DS){
                            $TotalPrice_hotels = $TotalPrice_hotels + $rooms_price_DS;
                        }
                    }
                }
            }
            
            $separate_revenue_hotels    = $TotalPrice_travelanda + $TotalPrice_hotelbeds + $TotalPrice_tbo + $TotalPrice_ratehawk + $TotalPrice_hotels;
            $separate_revenue_hotels    = round($separate_revenue_hotels,2);
            
            $separate_Revenue_accomodation_Price    = 0;
            $separate_Revenue_accomodation          = $tours1['separate_Revenue_accomodation'];
            if(isset($separate_Revenue_accomodation) && $separate_Revenue_accomodation != null && $separate_Revenue_accomodation != ''){
                foreach($separate_Revenue_accomodation as $separate_Revenue_accomodationS){
                    $accomodation_details_E = $separate_Revenue_accomodationS->accomodation_details;
                    if(isset($accomodation_details_E) && $accomodation_details_E != null && $accomodation_details_E != ''){
                        $accomodation_details   = json_decode($accomodation_details_E);
                        if(isset($accomodation_details)){
                            foreach($accomodation_details as $accomodation_detailsS){
                            if(isset($accomodation_detailsS->hotel_invoice_markup) && $accomodation_detailsS->hotel_invoice_markup != null && $accomodation_detailsS->hotel_invoice_markup != ''){
                                $separate_Revenue_accomodation_Price = $separate_Revenue_accomodation_Price + ($accomodation_detailsS->hotel_invoice_markup * $accomodation_detailsS->acc_qty);
                            }else{
                                $separate_Revenue_accomodation_Price = $separate_Revenue_accomodation_Price + ($accomodation_detailsS->acc_total_amount * $accomodation_detailsS->acc_qty);
                            }
                        }
                        }
                    }
                    $accomodation_details_more_E = $separate_Revenue_accomodationS->accomodation_details_more;
                    if(isset($accomodation_details_more_E) && $accomodation_details_more_E != null && $accomodation_details_more_E != ''){
                        $accomodation_details_more   = json_decode($accomodation_details_more_E);
                        if(isset($accomodation_details_more) && $accomodation_details_more != null && $accomodation_details_more != '' && $accomodation_details_more != 'null'){
                            foreach($accomodation_details_more as $accomodation_details_moreS){
                            if(isset($accomodation_details_moreS->more_hotel_invoice_markup) && $accomodation_details_moreS->more_hotel_invoice_markup != null && $accomodation_details_moreS->more_hotel_invoice_markup != ''){
                                $separate_Revenue_accomodation_Price = $separate_Revenue_accomodation_Price + ($accomodation_details_moreS->more_hotel_invoice_markup * $accomodation_details_moreS->more_acc_qty);
                            }else{
                                $separate_Revenue_accomodation_Price = $separate_Revenue_accomodation_Price + ($accomodation_details_moreS->more_acc_total_amount * $accomodation_details_moreS->more_acc_qty);
                            }
                        }
                        }
                    }
                }
            }
            $separate_Revenue_accomodation_Price = round($separate_Revenue_accomodation_Price,2);
            
            $separate_Revenue_visa_Price    = 0;
            $separate_Revenue_visa          = $tours1['separate_Revenue_visa'];
            if(isset($separate_Revenue_visa) && $separate_Revenue_visa != null && $separate_Revenue_visa != ''){
                foreach($separate_Revenue_visa as $separate_Revenue_visaS){
                    if(isset($separate_Revenue_visaS->total_visa_markup_value) && $separate_Revenue_visaS->total_visa_markup_value != null && $separate_Revenue_visaS->total_visa_markup_value != ''){
                        $separate_Revenue_visa_Price = $separate_Revenue_visa_Price + ($separate_Revenue_visaS->total_visa_markup_value * $separate_Revenue_visaS->visa_Pax);
                    }else{
                        $separate_Revenue_visa_Price = $separate_Revenue_visa_Price + ($separate_Revenue_visaS->visa_fee * $separate_Revenue_visaS->no_of_pax_days);
                    }
                    if(isset($separate_Revenue_visaS->more_visa_details) && $separate_Revenue_visaS->more_visa_details && $separate_Revenue_visaS->more_visa_details){
                        $more_visa_details = json_decode($separate_Revenue_visaS->more_visa_details);
                        if(isset($more_visa_details) && $more_visa_details != null && $more_visa_details != ''){
                            foreach($more_visa_details as $more_visa_detailsS){
                                if(isset($more_visa_detailsS->more_total_visa_markup_value) && $more_visa_detailsS->more_total_visa_markup_value != null && $more_visa_detailsS->more_total_visa_markup_value != ''){
                                    $separate_Revenue_visa_Price = $separate_Revenue_visa_Price + ($more_visa_detailsS->more_total_visa_markup_value * $more_visa_detailsS->more_visa_Pax);
                                }
                            }
                        }
                    }
                }
            }
            $separate_Revenue_visa_Price = round($separate_Revenue_visa_Price,2);
            
            $separate_Revenue_flight_Price  = 0;
            $separate_Revenue_flight        = $tours1['separate_Revenue_flight'];
            if(isset($separate_Revenue_flight) && $separate_Revenue_flight != null && $separate_Revenue_flight != ''){
                foreach($separate_Revenue_flight as $separate_Revenue_flightS){
                    $no_of_pax_days     = $separate_Revenue_flightS->no_of_pax_days;
                    $markup_details_E   = $separate_Revenue_flightS->markup_details;
                    if(isset($markup_details_E) && $markup_details_E != null && $markup_details_E != ''){
                        $markup_details = json_decode($markup_details_E);
                        foreach($markup_details as $markup_detailsS){
                            if($markup_detailsS->markup_Type_Costing == 'flight_Type_Costing'){
                                if(isset($markup_detailsS->markup_price) && $markup_detailsS->markup_price != null && $markup_detailsS->markup_price != ''){
                                    $separate_Revenue_flight_Price = $separate_Revenue_flight_Price + ($markup_detailsS->markup_price * $no_of_pax_days);
                                }else{
                                    $separate_Revenue_flight_Price = $separate_Revenue_flight_Price + ($markup_detailsS->without_markup_price * $no_of_pax_days);   
                                }
                            }
                        }
                    }
                }
            }
            $separate_Revenue_flight_Price = round($separate_Revenue_flight_Price,2);
            
            $separate_Revenue_transportation_Price  = 0;
            $separate_Revenue_transportation        = $tours1['separate_Revenue_transportation'];
            if(isset($separate_Revenue_transportation) && $separate_Revenue_transportation != null && $separate_Revenue_transportation != ''){
                foreach($separate_Revenue_transportation as $separate_Revenue_transportationS){
                    $no_of_pax_days     = $separate_Revenue_transportationS->no_of_pax_days;
                    $markup_details_E   = $separate_Revenue_transportationS->markup_details;
                    if(isset($markup_details_E) && $markup_details_E != null && $markup_details_E != ''){
                        $markup_details = json_decode($markup_details_E);
                        foreach($markup_details as $markup_detailsS){
                            if($markup_detailsS->markup_Type_Costing == 'transportation_Type_Costing'){
                                if(isset($markup_detailsS->markup_price) && $markup_detailsS->markup_price != null && (float)$markup_detailsS->markup_price != ''){
                                    $separate_Revenue_transportation_Price = $separate_Revenue_transportation_Price + ((float)$markup_detailsS->markup_price * $no_of_pax_days);   
                                }else{
                                    $separate_Revenue_transportation_Price = $separate_Revenue_transportation_Price + ((float)$markup_detailsS->without_markup_price * (float)$no_of_pax_days);
                                }
                            }
                        }
                    }
                }
            }
            $separate_Revenue_transportation_Price = round($separate_Revenue_transportation_Price,2);
            
            $toTal_revenue_Invoice_New  = $separate_Revenue_accomodation_Price + $separate_Revenue_visa_Price + $separate_Revenue_flight_Price + $separate_Revenue_transportation_Price;
            $separate_package_Revenue   = $tours1['separate_package_Revenue'];
            $separate_activity_Revenue  = $tours1['separate_activity_Revenue'];
           
            $separate_Total_Revenue     = $toTal_revenue_Invoice_New + $separate_package_Revenue + $separate_activity_Revenue + $separate_revenue_hotels;
            $separate_Total_Revenue     = round($separate_Total_Revenue,2);
            // Revenue End
            
            // Separate Cost Price
            $separate_Cost_accomodation_Price   = 0;
            $separate_Revenue_accomodation      = $tours1['separate_Revenue_accomodation'];
            if(isset($separate_Revenue_accomodation) && $separate_Revenue_accomodation != null && $separate_Revenue_accomodation != ''){
                foreach($separate_Revenue_accomodation as $separate_Revenue_accomodationS){
                    $accomodation_details_E = $separate_Revenue_accomodationS->accomodation_details;
                    if(isset($accomodation_details_E) && $accomodation_details_E != null && $accomodation_details_E != ''){
                        $accomodation_details   = json_decode($accomodation_details_E);
                        foreach($accomodation_details as $accomodation_detailsS){
                            if(isset($accomodation_detailsS->acc_total_amount) && $accomodation_detailsS->acc_total_amount != null && $accomodation_detailsS->acc_total_amount != ''){
                                $separate_Cost_accomodation_Price = $separate_Cost_accomodation_Price + ($accomodation_detailsS->acc_total_amount * $accomodation_detailsS->acc_qty);
                            }
                        }
                    }
                    $accomodation_details_more_E = $separate_Revenue_accomodationS->accomodation_details_more;
                    if(isset($accomodation_details_more_E) && $accomodation_details_more_E != null && $accomodation_details_more_E != ''){
                        $accomodation_details_more   = json_decode($accomodation_details_more_E);
                        
                        if(isset($accomodation_details_more) && $accomodation_details_more != null && $accomodation_details_more != '' && $accomodation_details_more != 'null'){
                            foreach($accomodation_details_more as $accomodation_details_moreS){
                            if(isset($accomodation_details_moreS->more_acc_total_amount) && $accomodation_details_moreS->more_acc_total_amount != null && $accomodation_details_moreS->more_acc_total_amount != ''){
                                $separate_Cost_accomodation_Price = $separate_Cost_accomodation_Price + ($accomodation_details_moreS->more_acc_total_amount * $accomodation_details_moreS->more_acc_qty);
                            }
                        }
                        }
                    }
                }
            }
            $separate_Cost_accomodation_Price = round($separate_Cost_accomodation_Price,2);
            
            $separate_Cost_visa_Price   = 0;
            $separate_Revenue_visa      = $tours1['separate_Revenue_visa'];
            if(isset($separate_Revenue_visa) && $separate_Revenue_visa != null && $separate_Revenue_visa != ''){
                foreach($separate_Revenue_visa as $separate_Revenue_visaS){
                    if(isset($separate_Revenue_visaS->visa_Pax) && $separate_Revenue_visaS->visa_Pax != null && $separate_Revenue_visaS->visa_Pax != ''){
                        $separate_Cost_visa_Price = $separate_Cost_visa_Price + ($separate_Revenue_visaS->visa_fee * $separate_Revenue_visaS->visa_Pax);
                    }else{
                        $separate_Cost_visa_Price = $separate_Cost_visa_Price + ($separate_Revenue_visaS->visa_fee * $separate_Revenue_visaS->no_of_pax_days);
                    }
                    if(isset($separate_Revenue_visaS->more_visa_details) && $separate_Revenue_visaS->more_visa_details && $separate_Revenue_visaS->more_visa_details){
                        $more_visa_details = json_decode($separate_Revenue_visaS->more_visa_details);
                        if(isset($more_visa_details) && $more_visa_details != null && $more_visa_details != ''){
                            foreach($more_visa_details as $more_visa_detailsS){
                                if(isset($more_visa_detailsS->more_visa_fee) && $more_visa_detailsS->more_visa_fee != null && $more_visa_detailsS->more_visa_fee != ''){
                                    $separate_Cost_visa_Price = $separate_Cost_visa_Price + ($more_visa_detailsS->more_visa_fee * $more_visa_detailsS->more_visa_Pax);
                                }
                            }
                        }
                    }
                }
            }
            $separate_Cost_visa_Price = round($separate_Cost_visa_Price,2);
            
            $separate_Cost_flight_Price = 0;
            $separate_Revenue_flight    = $tours1['separate_Revenue_flight'];
            if(isset($separate_Revenue_flight) && $separate_Revenue_flight != null && $separate_Revenue_flight != ''){
                foreach($separate_Revenue_flight as $separate_Revenue_flightS){
                    $no_of_pax_days     = $separate_Revenue_flightS->no_of_pax_days;
                    $flights_details_E  = $separate_Revenue_flightS->flights_details;
                    if(isset($flights_details_E) && $flights_details_E != null && $flights_details_E != ''){
                        $flights_details = json_decode($flights_details_E);
                        if(isset($flights_details->flights_per_person_price) && $flights_details->flights_per_person_price != null && $flights_details->flights_per_person_price != ''){
                            $separate_Cost_flight_Price = $separate_Cost_flight_Price + ($flights_details->flights_per_person_price * $no_of_pax_days);
                        }
                    }
                }
            }
            $separate_Cost_flight_Price = round($separate_Cost_flight_Price,2);
            
            $separate_Cost_transportation_Price = 0;
            $separate_Revenue_transportation    = $tours1['separate_Revenue_transportation'];
            if(isset($separate_Revenue_transportation) && $separate_Revenue_transportation != null && $separate_Revenue_transportation != ''){
                foreach($separate_Revenue_transportation as $separate_Revenue_transportationS){
                    $no_of_pax_days             = $separate_Revenue_transportationS->no_of_pax_days;
                    $transportation_details_E   = $separate_Revenue_transportationS->transportation_details;
                    if(isset($transportation_details_E) && $transportation_details_E != null && $transportation_details_E != ''){
                        $transportation_details = json_decode($transportation_details_E);
                        if(isset($transportation_details->transportation_price_per_person) && $transportation_details->transportation_price_per_person != null && $transportation_details->transportation_price_per_person != ''){
                            $separate_Cost_transportation_Price = $separate_Cost_transportation_Price + ($transportation_details->transportation_price_per_person * $no_of_pax_days);
                        }
                    }
                }
            }
            $separate_Cost_transportation_Price = round($separate_Cost_transportation_Price,2);
            
            $toTal_cost_Invoice             = $separate_Cost_accomodation_Price + $separate_Cost_visa_Price + $separate_Cost_flight_Price + $separate_Cost_transportation_Price + $separate_revenue_hotels;
            $toTal_cost_Invoice             = round($toTal_cost_Invoice,2);
            $separate_package_cost_price    = $tours1['separate_package_cost_price'];
            $separate_activity_cost_price   = $tours1['separate_activity_cost_price'];
            $separate_Total_Cost            = $toTal_cost_Invoice + $separate_package_cost_price + $separate_activity_cost_price;
            // Cost End
            
            // Profit
            $separate_profit_Total          = $separate_Total_Revenue - $separate_Total_Cost;
            
            $profit_percentage = ($separate_profit_Total * 100)/$separate_Total_Revenue;
            $profit_percentage = round($profit_percentage,2);
            Session::put('profit_percentage',$profit_percentage);
            
            $expense_percentage = ($separate_Total_Cost * 100)/$separate_Total_Revenue;
            $expense_percentage = round($expense_percentage,2);
            Session::put('expense_percentage',$expense_percentage);
            
            return response()->json([
                'separate_Total_Revenue'    => $separate_Total_Revenue,
                'separate_Total_Cost'       => $separate_Total_Cost,
                'separate_profit_Total'     => $separate_profit_Total,
            ]);
        }
    
    public function get_agent_data(){
          $agent = \DB::table('Agents_detail')->orderBy('id','desc')->get();
          return response()->json(['agent'=>$agent]);
        }
    
    public function convert_object_to_array($object){
    
    
    
            foreach($object as $value){
    
                
    
                $converted_array[] =  (array) $value;
    
            }
    
            
    
            return $converted_array;
    
        }
    
    public function manage_user_roles(){
    
    
    
            
    
            $user_permissions=Auth::guard('web')->user()->permissions;
    
            $permissions = json_decode($user_permissions);
            $users = \DB::table('users')
            ->where('id','!=',1)
            ->get();
    
            //end header data
    
            return view('template/frontend/userdashboard/pages/user_role',compact('permissions','users'));
    
        }
    
    public function add_user_permission(Request $request){
    
    
    
            $permission = [
    
                'umrah_package' => 0,
    
                'tour_package' => 0,
    
                'customer_subcription' => 0,
    
                'offers' => 0,
    
                
                
                'accounts' => 0,
    
                'visa' => 0,
    
                'arrival_departure' => 0,
    
                
                'support_ticket' => 0,
                
                'hrms' => 0,
    
                'settings' => 0,
                
                'email_marketing' => 0,
    
                
    
                'manage_user_roles' => 0
    
            ];
    
            if(isset($request->user_permission) && !empty($request->user_permission)){
    
                
    
                foreach ($_POST['user_permission'] as $key => $value) {
    
                    
    
                    if($value=="on"){
    
                        $permission[$key] = 1;
    
                    }
    
                }
    
            }
    
            $per = json_encode($permission);
    
            $add_user = DB::table('users')
    
            ->insert([
    
                'name' => $request->name,
    
                'email' => $request->email,
    
                'title' => $request->title,
    
                'password' => bcrypt($request->password),
    
                'permissions' => $per
    
            ]);
    
            if($add_user){
    
                
    
                $request->session()->flash('success','User Added Successful!');
    
                return redirect('super_admin/manage_user_roles');
    
            }
    
        }
    
    public function edit_user_permission(Request $request){
    
            $permission = [
    
                'umrah_package' => 0,
    
                'tour_package' => 0,
    
                'customer_subcription' => 0,
    
                
    
               
                
                'accounts' => 0,
    
                'visa' => 0,
    
                'arrival_departure' => 0,
    
                
    
                'support_ticket' => 0,
                
                'hrms' => 0,
    
                'settings' => 0,
                
                'offers' => 0,
    
                'email_marketing' => 0,
    
                'manage_user_roles' => 0
    
            ];
    
            if(isset($request->user_permission) && !empty($request->user_permission)){
    
                
    
                foreach ($_POST['user_permission'] as $key => $value) {
    
                    
    
                    if($value=="on"){
    
                        $permission[$key] = 1;
    
                    }
    
                }
    
            }
    
            $per = json_encode($permission);
    
            $edit_user = DB::table('users')
            ->where('id','=',$request->user_id)
            ->update([
    
                'name' => $request->name,
    
                'email' => $request->email,
    
                'title' => $request->title,
    
                'permissions' => $per
    
            ]);
            if($edit_user){
    
                $request->session()->flash('success','User Updated Successfully!');
    
                return redirect('super_admin/manage_user_roles');
    
            }
    
        }
    
    public function view_offers(){
            $offers=DB::table('offers')->get();
            $tours=DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->get();
            $umrah_packages=DB::table('umrah_packages')->get();
            $data = $tours->union($umrah_packages);
           
         
           
            // print_r($data);die();
            return view('template/frontend/userdashboard/pages/offers/view_offers',compact('offers','data'));
        }
    
    public function submit_offers(Request $request){
            
            $offers=DB::table('offers')->insert(
                [
                    'from_date'=>$request->from_date,
                    'to_date'=>$request->to_date,
                    'package_name'=>$request->package_name,
                    'amount'=>$request->amount,
                ]);
                if($offers)
                {
                    return redirect()->back();
                }
            
        }
    
    public function delete_offers(Request $request,$id){
        
        $offers=DB::table('offers')->where('id', $id)->delete();
            if($offers)
            {
                return redirect()->back();
            }
        
    }
public function edit_offers(Request $request,$id){
        
        $offers=DB::table('offers')->where('id', $id)->first();
        $tours=DB::table('tours')->join('tours_2','tours.id','tours_2.tour_id')->get();
        $umrah_packages=DB::table('umrah_packages')->get();
        $data = $tours->union($umrah_packages);
         return view('template/frontend/userdashboard/pages/offers/edit_offers',compact('offers','data'));
    }
public function submit_edit_offers(Request $request,$id){
        
        $offers=DB::table('offers')->where('id',$id)->update(
            [
                'from_date'=>$request->from_date,
                'to_date'=>$request->to_date,
                'package_name'=>$request->package_name,
                'amount'=>$request->amount,
            ]);
            if($offers)
            {
                return redirect('super_admin/view_offers');
            }
        
    }
public function add_markup(Request $request){



$customer=\DB::table('customer_subcriptions')->get();
$custom_hotel_provider = \DB::table('custom_hotel_provider')->get();
return view('template/frontend/userdashboard/pages/markup/add_markup',compact('customer','custom_hotel_provider'));

    }
public function admin_markup(Request $request){
    
    // dd('STOP');
    
        $provider_data = DB::table('custom_hotel_provider')->where('provider_name',$request->provider)->first();
        $provider_id = '';
        if($provider_data){
            $provider_id = $provider_data->customer_id;
        }
        

        $setting_markup=\DB::table('admin_markups')->insert(

        array(

            'provider'   =>   $request->provider,
            'provider_id' => $provider_id,
            'customer_id'   =>  $request->customer_id,

            'markup_type'   =>   $request->markup_type,

            'markup_value'   =>   $request->markup_value,
             'added_markup'   =>   $request->added_markup,
             'services_type'   =>   $request->services_type,
             'status'   =>   1,
        ));

        return redirect()->back()->with('message','Markup Has been Added.');

    }
public function view_markup(Request $request){



$markup=DB::table('admin_markups')->orderBy('id','DESC')->get();
return view('template/frontend/userdashboard/pages/markup/view_markup',compact('markup'));

    }
public function edit_markup(Request $request,$id){


$markup=\DB::table('admin_markups')->where('id',$id)->first();
$customer=\DB::table('customer_subcriptions')->get();
return view('template/frontend/userdashboard/pages/markup/edit_markup',compact('customer','markup'));

    }  
public function update_markup(Request $request,$id){



        $setting_markup=\DB::table('admin_markups')->where('id',$id)->update(

        array(

            'provider'   =>   $request->provider,

            'customer_id'   =>  $request->customer_id,

            'markup_type'   =>   $request->markup_type,

            'markup_value'   =>   $request->markup_value,
            'services_type'   =>   $request->services_type,
             'status'   =>   1,
        ));

        return redirect('super_admin/view_markup')->with('message','Markup Has been Updated.');

    }    
public function delete_markup(Request $request,$id){



        $setting_markup=\DB::table('admin_markups')->where('id',$id)->delete();

        return redirect()->back()->with('message','Markup Has been Delete.');

    }  
public function settings(){



       

        return view('template/frontend/userdashboard/pages/settings');

    }
public function view_ticket(){
$ticket=Ticket::orderBy('id', 'DESC')->get();

$conversation=Conversation::orderBy('id', 'DESC')->latest()->first();
// print_r($conversation->ticket_id);die();
return view('template/frontend/userdashboard/pages/ticket/view_ticket',compact('ticket','conversation'));

    }
public function view_conversation($name,$id){
        $ticket=Ticket::find($id);
        $conversation=$ticket->conversation;
        
        $unread_message=$ticket->conversation_unread_message;
        
        if(isset($unread_message[0]->id))
        {
        $conversation_update=Conversation::find($unread_message[0]->id);
        $conversation_update->read_message=1;
        $conversation_update->update();
        }
        
  
        $client=DB::table('customer_subcriptions')->where('id',$ticket->customer_id)->first();
        
        //print_r($unread_message->id);die();
     return view('template/frontend/userdashboard/pages/ticket/view_conversation',compact('ticket','conversation','client'));   
    }
    
    
public function view_all_conversation(Request $request){
        $ticket_id=$request->ticket_id;
        $ticket=Ticket::find($ticket_id);
        $conversation=$ticket->conversation;
        // print_r($conversation);die();
        $client=DB::table('customer_subcriptions')->where('id',$ticket->customer_id)->first();
         return $conversation;
        
        
    }
  
  public function downloadFile($uuid){
    $book = Conversation::where('uuid', $uuid)->firstOrFail();
    //print_r($book->message);die();
    $myFile = public_path("uploads/file/".$book->message);
    
    $pathToFile = $myFile;
    return response()->download($pathToFile);
}



public function conversation_submit(Request $request,$id){



if(isset($request->message_type))
{
 if ($request->hasFile('getFilesupload')) {
 $book['uuid'] = (string) Uuid::generate();   
$file=$request->file('getFilesupload');
    $book['getFilesupload'] = $request->getFilesupload->getClientOriginalName();
   $file->move('public/uploads/file/',$book['getFilesupload']);
   
}
else
{
    $book['uuid'] = NULL;
    $book['message_type'] = $request->input('message_type');
}


$message_type = $request->message_type;

DB::beginTransaction();

try {
    $conversation = new Conversation();
    $conversation->message = $message_type;
    $conversation->uuid = $book['uuid'];
    $conversation->ticket_id = $id;
    $conversation->message_sent = 'Admin';
    $conversation->save();

    DB::commit();

    return response()->json(['message' => 'success']);
} catch (Throwable $e) {
    DB::rollback();
    return response()->json(['message' => 'error']);
}   
}
else
{
 return response()->json(['message' => 'please type the message here.']);   
}

$book = $request->all();
//print_r($book);die();


    }
    
public function admin_ticket(Request $request,$id){

$ticket=Ticket::find($id);
   
//print_r($request->add_comment);die();

if($ticket){
if($ticket->add_comment == NULL){
    
$ticket->status=$request->status;
$ticket->add_comment=$request->add_comment;
$ticket->update();
return redirect()->back()->with('message','Add admin Comment Successful!');

}
else{
$key = random_int(0, 9999999);
$random = str_pad($key, 6, 0, STR_PAD_LEFT);
$ticket=new Ticket();
$ticket->company_name=$request->company_name;
$ticket->customer_id=$request->customer_id;
$ticket->customer_name=$request->customer_name;
$ticket->email=$request->email;
$ticket->phone=$request->phone;
$ticket->additinal_email=$request->additinal_email;
$ticket->ticket_type=$request->ticket_type;
$ticket->ticket_priorty=$request->ticket_priorty;
$ticket->subject=$request->subject;
$ticket->description=$request->description;
$ticket->ticket_id=$random;
$ticket->status=$request->status;
$ticket->add_comment=$request->add_comment;
$ticket->save();
return redirect()->back()->with('message','Add admin Comment Successful!');   
}
}
}

public function updated_status_ticket(Request $request,$id){
  $ticket=Ticket::find($id);
  DB::beginTransaction();
        try {   
       
       if($request->status == 'Resoloved')
       {
          $ticket_status='Checked';
          $ticket->status=$ticket_status;
          $ticket->update();
          
          $conversation=new Conversation();
            $conversation->message='Your ticket status has been change to Resolved.';
            $conversation->ticket_id =$id;
            $conversation->message_sent='Admin';
            $conversation->save();
       }
       else{
          $ticket_status=$request->status;
          $ticket->status=$ticket_status;
        $ticket->update();
        
        $conversation=new Conversation();
            $conversation->message='Your ticket status has been changed to In-Process.';
            $conversation->ticket_id =$id;
            $conversation->message_sent='Admin';
            $conversation->save();
       }
        
        
        DB::commit();
            return redirect()->back()->with('message','Status Updated Successful!');
           
        } catch (Throwable $e) {
                 DB::rollback();
                  return redirect()->back()->with('message','Status Not Updated Successful!');
        }
}

public function send_email_to_agents(){



       
        $data = DB::table('customer_subcriptions')->get();

        return view('template/frontend/userdashboard/pages/customer_subcription/send_email',compact('data'));

    }
    
    
    
public function view_invoice_location_map(Request $request){
    
//   $add_manage_invoices=DB::table('add_manage_invoices')
//   ->where('customer_id',$request->customer_id)
//   ->whereJsonContains('services', 'transportation_tab')
//   ->select('id','services')
//   ->get();
   
   $gettracker=DB::table('C_Tracker')
   ->where('customer_id',$request->customer_id)->orderBy('id','DESC')->get();
   
   return response()->json(['locationData'=>$gettracker]);
   
   //print_r($gettracker);
}
    
}

