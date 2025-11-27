<?php

namespace App\Http\Controllers\frontend\admin_dashboard;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SupplierController;
use Illuminate\Http\Request;
use App\Models\UmrahPackage;
use App\Models\CustomerSubcription\CustomerSubcription;
use App\Models\Agents_detail;
use App\Models\addManageInvoice;
use App\Models\pay_Invoice_Agent;
use App\Models\hotel_manager\Hotels;
use App\Models\hotel_manager\Rooms;
use App\Models\Tour;
use App\Models\Active;
use App\Models\country;
use App\Models\Activities;
use App\Models\payInvoiceAgent;
use App\Models\rooms_Invoice_Supplier;
use App\Models\alhijaz_Notofication;
use App\Models\flight_seats_occupied;
use DB;

class ManageOfficeController_Admin extends Controller
{
    public function booking_financial_stats_Admin(Request $request){
    
        // $userData = DB::table('customer_subcriptions')->select('id','status')->get();
        // if($userData){
            $agent_lists = DB::table('Agents_detail')->get();
            
            $all_agents_data = [];
            $agents_tour_booking    = DB::table('cart_details') ->where('pakage_type','tour')->get();
            $agents_invoice_booking = DB::table('add_manage_invoices')->select('customer_id','generate_id','created_at','services','agent_Id','agent_name','more_visa_details','total_sale_price_all_Services','total_cost_price_all_Services','total_paid_amount','over_paid_amount','accomodation_details','accomodation_details_more','markup_details','more_markup_details','no_of_pax_days','id','agent_Id','currency_symbol','visa_Pax')->get();
            
            $booking_all_data = [];
            foreach($agents_tour_booking as $tour_res){
             
                $tours_costing = DB::table('tours_2')
                                    ->join('tours','tours_2.tour_id','tours.id')
                                    ->where('tours_2.tour_id',$tour_res->tour_id)
                                    ->select('tours.created_at','tours.title','tours_2.quad_cost_price','tours_2.triple_cost_price','tours_2.double_cost_price','tours_2.without_acc_cost_price','tours_2.child_grand_total_cost_price','tours_2.infant_total_cost_price')->first();
                
                $passenger_nameQ    = DB::table('tours_bookings')
                                        ->where('tours_bookings.id',$tour_res->booking_id)
                                        ->select('passenger_name')
                                        ->get();
                
                $cart_all_data = json_decode($tour_res->cart_total_data);
                
                $grand_profit = 0;
                $grand_cost = 0;
                $grand_sale = 0;
             
                // Profit From Double Adults
                if(isset($cart_all_data->double_adults) && $cart_all_data->double_adults != null && $cart_all_data->double_adults != '' && $cart_all_data->double_adults > 0){
                    $double_adult_total_cost = $tours_costing->double_cost_price ?? '0' * $cart_all_data->double_adults;
                    $double_profit = $cart_all_data->double_adult_total - $double_adult_total_cost;
                    $grand_profit += $double_profit;
                    $grand_cost += $double_adult_total_cost;
                    $grand_sale += $cart_all_data->double_adult_total;
                }
                 
                // Profit From Triple Adults
                if(isset($cart_all_data->triple_adults) && $cart_all_data->triple_adults != null && $cart_all_data->triple_adults != '' && $cart_all_data->triple_adults > 0){
                    $triple_adult_total_cost = $tours_costing->triple_cost_price ?? '0' * $cart_all_data->triple_adults;
                    $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    $grand_profit += $triple_profit;
                    $grand_cost += $triple_adult_total_cost;
                    $grand_sale += $cart_all_data->triple_adult_total;
                }
                    
                // Profit From Quad Adults
                if(isset($cart_all_data->quad_adults) && $cart_all_data->quad_adults != null && $cart_all_data->quad_adults != '' && $cart_all_data->quad_adults > 0){
                    $quad_adult_total_cost = $tours_costing->quad_cost_price ?? '0' * $cart_all_data->quad_adults;
                    $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    $grand_profit += $quad_profit;
                    $grand_cost += $quad_adult_total_cost;
                    $grand_sale += $cart_all_data->quad_adult_total;
                }
                 
                // Profit From Without Acc
                if(isset($cart_all_data->without_acc_adults) && $cart_all_data->without_acc_adults != null && $cart_all_data->without_acc_adults != '' && $cart_all_data->without_acc_adults > 0){
                    $without_acc_adult_total_cost = $tours_costing->without_acc_cost_price ?? '0' * $cart_all_data->without_acc_adults;
                    $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    $grand_profit += $without_acc_profit;
                    $grand_cost += $without_acc_adult_total_cost;
                    $grand_sale += $cart_all_data->without_acc_adult_total;
                }
                 
                // Profit From Double Childs
                if(isset($cart_all_data->double_childs) && $cart_all_data->double_childs != null && $cart_all_data->double_childs != '' && $cart_all_data->double_childs > 0){
                    $double_child_total_cost = $tours_costing->double_cost_price ?? '0' * $cart_all_data->double_childs;
                    $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    $grand_profit += $double_profit;
                    $grand_cost += $double_child_total_cost;
                    $grand_sale += $cart_all_data->double_childs_total;
                }
                 
                // Profit From Triple Childs
                if(isset($cart_all_data->triple_childs) && $cart_all_data->triple_childs != null && $cart_all_data->triple_childs != '' && $cart_all_data->triple_childs > 0){
                    $triple_child_total_cost = $tours_costing->triple_cost_price ?? '0' * $cart_all_data->triple_childs;
                    $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    $grand_profit += $triple_profit;
                    $grand_cost += $triple_child_total_cost;
                    $grand_sale += $cart_all_data->triple_childs_total;
                }
                    
                // Profit From Quad Childs
                if(isset($cart_all_data->quad_childs) && $cart_all_data->quad_childs != null && $cart_all_data->quad_childs != '' && $cart_all_data->quad_childs > 0){
                    $quad_child_total_cost = $tours_costing->quad_cost_price ?? '0' * $cart_all_data->quad_childs;
                    $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    $grand_profit += $quad_profit;
                    $grand_cost += $quad_child_total_cost;
                    $grand_sale += $cart_all_data->quad_child_total;
                }
                 
                // Profit From Without Acc Child
                if(isset($cart_all_data->children) && $cart_all_data->children != null && $cart_all_data->children != '' && $cart_all_data->children > 0){
                    $without_acc_child_total_cost = $tours_costing->child_grand_total_cost_price ?? '0' * $cart_all_data->children;
                    $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    $grand_profit += $without_acc_profit;
                    $grand_cost += $without_acc_child_total_cost;
                    $grand_sale += $cart_all_data->without_acc_child_total;
                }

                // Profit From Double Infant
                if(isset($cart_all_data->double_infant) && $cart_all_data->double_infant != null && $cart_all_data->double_infant != '' && $cart_all_data->double_infant > 0){
                    $double_infant_total_cost   = $tours_costing->double_cost_price ?? '0' * $cart_all_data->double_infant;
                    $double_profit              = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    $grand_profit               += $double_profit;
                    $grand_cost                 += $double_infant_total_cost;
                    $grand_sale                 += $cart_all_data->double_infant_total;
                }
                 
                // Profit From Triple Infant
                if(isset($cart_all_data->triple_infant) && $cart_all_data->triple_infant != null && $cart_all_data->triple_infant != '' && $cart_all_data->triple_infant > 0){
                    $triple_infant_total_cost = $tours_costing->triple_cost_price ?? '0' * $cart_all_data->triple_infant;
                    $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    $grand_profit += $triple_profit;
                    $grand_cost += $triple_infant_total_cost;
                    $grand_sale += $cart_all_data->triple_infant_total;
                }
                 
                // Profit From Quad Infant
                if(isset($cart_all_data->quad_infant) && $cart_all_data->quad_infant != null && $cart_all_data->quad_infant != '' && $cart_all_data->quad_infant > 0){
                    $quad_infant_total_cost = $tours_costing->quad_cost_price ?? '0' * $cart_all_data->quad_infant;
                    $quad_profit            = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    $grand_profit           += $quad_profit;
                    $grand_cost            += $quad_infant_total_cost;
                    $grand_sale             += $cart_all_data->quad_infant_total;
                }
                 
                // Profit From Without Acc Infant  
                if(isset($cart_all_data->infants) && $cart_all_data->infants != null && $cart_all_data->infants != '' && $cart_all_data->infants > 0){
                    $without_acc_infant_total_cost = $tours_costing->infant_total_cost_price ?? '0' * $cart_all_data->infants;
                    $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    $grand_profit += $without_acc_profit;
                    $grand_cost += $without_acc_infant_total_cost;
                    $grand_sale += $cart_all_data->without_acc_infant_total;
                }
                  
                $over_all_dis = 0;
                
                if(isset($cart_all_data->discount_type) && $cart_all_data->discount_type != null && $cart_all_data->discount_type != '' &&  $cart_all_data->discount_type == 'amount'){
                    $final_profit   = $grand_profit - $cart_all_data->discount_enter_am;
                    $grand_sale     = $grand_sale - $cart_all_data->discount_enter_am;
                }else{
                    $discunt_am_over_all = ($cart_all_data->price ?? '0' * $cart_all_data->discount_enter_am ?? '0') / 100;
                    $final_profit = $grand_profit - $discunt_am_over_all;
                    $grand_sale   = $grand_sale - $discunt_am_over_all;
                }
                
                if(isset($cart_all_data->special_discount) && $cart_all_data->special_discount != null && $cart_all_data->special_discount != ''){
                    $final_profit   = $grand_profit - $cart_all_data->special_discount;
                    $grand_sale     = $grand_sale - $cart_all_data->special_discount;
                }
                
                $commission_add = '';
                if(isset($cart_all_data->agent_commsion_add_total) && $cart_all_data->agent_commsion_add_total != null && $cart_all_data->agent_commsion_add_total != ''){
                    $commission_add = $cart_all_data->agent_commsion_add_total;
                }
                
                $agent_name = $tour_res->agent_name;
                foreach($agent_lists as $agent_res){
                    if($agent_res->id == $tour_res->agent_name){
                        $agent_name = $agent_res->agent_Name;
                    }
                }
                
                if(isset($passenger_nameQ[0]) && $passenger_nameQ[0] != null && $passenger_nameQ[0] != ''){
                    $passenger_name = $passenger_nameQ[0]->passenger_name;
                }else{
                    $passenger_name = '';   
                }
                
                $booking_data = [
                        'title'                     => $tour_res->tour_name,
                        'agent_Id'                  => $tour_res->agent_name,
                        'agent_name'                => $agent_name,
                        'invoice_id'                => $tour_res->invoice_no,
                        'booking_id'                => $tour_res->booking_id,
                        'passenger_name'            => $passenger_name,
                        'tour_id'                   => $tour_res->tour_id,
                        'price'                     => $tour_res->tour_total_price,
                        'paid_amount'               => $tour_res->total_paid_amount,
                        'remaing_amount'            => $tour_res->tour_total_price - $tour_res->total_paid_amount,
                        'over_paid_amount'          => $tour_res->over_paid_amount,
                        'tour_name'                 => $cart_all_data->name,
                        'profit'                    => $final_profit,
                        'discount_am'               => $cart_all_data->discount_Price ?? '0',
                        'special_discount'          => $cart_all_data->special_discount ?? '0',
                        'total_cost'                => $grand_cost,
                        'total_sale'                => $grand_sale,
                        'commission_am'             => $cart_all_data->agent_commsion_am ?? '0',
                        'agent_commsion_add_total'  => $commission_add,
                        'currency'                  => $tour_res->currency,
                        'created_at'                => $tour_res->created_at,
                        'customer_id'               => $tour_res->client_id
                    ];
                
                array_push($booking_all_data,$booking_data);
            }
            
            $invoices_all_data = [];
            foreach($agents_invoice_booking as $agent_inv_res){
                
                $accomodation           = json_decode($agent_inv_res->accomodation_details);
                $accomodation_more      = json_decode($agent_inv_res->accomodation_details_more);
                $markup_details         = json_decode($agent_inv_res->markup_details);
                $more_markup_details    = json_decode($agent_inv_res->more_markup_details);

                // Caluclate Flight Price 
                $grand_cost     = 0;
                $grand_sale     = 0;
                $flight_cost    = 0;
                $flight_sale    = 0;
                foreach($markup_details as $mark_res){
                    if($mark_res->markup_Type_Costing == 'flight_Type_Costing'){
                        $flight_cost = $mark_res->without_markup_price; 
                        $flight_sale = $mark_res->markup_price; 
                    }
                }
                $flight_total_cost = (float)$flight_cost * (float)$agent_inv_res->no_of_pax_days;
                $flight_total_sale = (float)$flight_sale * (float)$agent_inv_res->no_of_pax_days;
                
                $flight_profit = $flight_total_sale - $flight_total_cost;
                
                $grand_cost += $flight_total_cost;
                $grand_sale += $flight_total_sale;
                
                // Caluclate Visa Price 
                $visa_cost = 0;
                $visa_sale = 0;
                $more_visa_cost = 0;
                $more_visa_sale = 0;
                foreach($markup_details as $mark_res){
                    if($mark_res->markup_Type_Costing == 'visa_Type_Costing'){
                        $visa_cost = $mark_res->without_markup_price; 
                        $visa_sale = $mark_res->markup_price; 
                    }
                }
                
                if(isset($agent_inv_res->visa_Pax) && $agent_inv_res->visa_Pax != null && $agent_inv_res->visa_Pax != ''){
                    $visa_total_cost = (float)$visa_cost * (float)$agent_inv_res->visa_Pax;
                    $visa_total_sale = (float)$visa_sale * (float)$agent_inv_res->visa_Pax;
                }else{
                    $visa_total_cost = (float)$visa_cost * (float)$agent_inv_res->no_of_pax_days;
                    $visa_total_sale = (float)$visa_sale * (float)$agent_inv_res->no_of_pax_days;
                }
                
                $more_visa_details_Pricing = json_decode($agent_inv_res->more_visa_details);
                if(isset($more_visa_details_Pricing) && $more_visa_details_Pricing != null && $more_visa_details_Pricing != ''){
                    foreach($more_visa_details_Pricing as $value){
                        if(isset($value->more_visa_Pax) && $value->more_visa_Pax != null && $value->more_visa_Pax != ''){
                            if(isset($value->more_exchange_rate_visa_fee) && $value->more_exchange_rate_visa_fee != null && $value->more_exchange_rate_visa_fee != ''){
                                $more_exchange_rate_visa_fee = (float)$value->more_exchange_rate_visa_fee;
                            }else{
                                $more_exchange_rate_visa_fee = 0;
                            }
                            $visa_total_cost = $visa_total_cost + $more_exchange_rate_visa_fee * (float)$value->more_visa_Pax;
                            $visa_total_sale = $visa_total_sale + (float)$value->more_total_visa_markup_value * (float)$value->more_visa_Pax;
                        }
                    }
                }
                
                $visa_profit = $visa_total_sale - $visa_total_cost;
                $grand_cost += $visa_total_cost;
                $grand_sale += $visa_total_sale;

                // Caluclate Transportation Price
                $trans_cost = 0;
                $trans_sale = 0;
                foreach($markup_details as $mark_res){
                    if($mark_res->markup_Type_Costing == 'transportation_Type_Costing'){
                        $trans_cost = $mark_res->without_markup_price; 
                        $trans_sale = $mark_res->markup_price; 
                    }
                }
                $trans_total_cost = (float)$trans_cost * (float)$agent_inv_res->no_of_pax_days;
                $trans_total_sale = (float)$trans_sale * (float)$agent_inv_res->no_of_pax_days;
                $trans_profit = $trans_total_sale - $trans_total_cost;
                $grand_cost += $trans_total_cost;
                $grand_sale += $trans_total_sale;

                // Caluclate Double Room Price
                $double_total_cost = 0;
                $double_total_sale = 0;
                $double_total_profit = 0;
                if(isset($accomodation)){
                    foreach($accomodation as $accmod_res){
                        if($accmod_res->acc_type == 'Double'){
                            (float)$double_cost     = $accmod_res->acc_total_amount; 
                            (float)$double_sale     = $accmod_res->hotel_invoice_markup ?? 0; 
                            $double_total_cost      = $double_total_cost + ((float)$double_cost * (float)$accmod_res->acc_qty);
                            $double_total_sale      = $double_total_sale + ((float)$double_sale * (float)$accmod_res->acc_qty);
                            $double_profit          = ((float)$double_sale - (float)$double_cost) * (float)$accmod_res->acc_qty;
                            $double_total_profit    = $double_total_profit + $double_profit;
                        }
                    }
                }
                if(isset($accomodation_more)){
                    foreach($accomodation_more as $accmod_res){
                        if($accmod_res->more_acc_type == 'Double'){
                            $double_cost = $accmod_res->more_acc_total_amount; 
                            if(isset($accmod_res->more_hotel_invoice_markup)){
                                $double_sale = $accmod_res->more_hotel_invoice_markup ?? 0; 
                            }else{
                                $double_sale = 0; 
                            }
                            $double_total_cost = $double_total_cost + ((float)$double_cost * (float)$accmod_res->more_acc_qty);
                            $double_total_sale = $double_total_sale + ((float)$double_sale * (float)$accmod_res->more_acc_qty);
                            $double_profit = ((float)$double_sale - (float)$double_cost) * (float)$accmod_res->more_acc_qty;
                            $double_total_profit = $double_total_profit + $double_profit;
                        }
                    }
                }
                $grand_cost += $double_total_cost;
                $grand_sale += $double_total_sale;
                
                // Caluclate Triple Room Price
                $triple_total_cost = 0;
                $triple_total_sale = 0;
                $triple_total_profit = 0;
                if(isset($accomodation)){
                    foreach($accomodation as $accmod_res){
                        if($accmod_res->acc_type == 'Triple'){
                            if(isset($accmod_res->acc_total_amount)  && $accmod_res->acc_total_amount != null && $accmod_res->acc_total_amount != ''){
                                $triple_cost = (float)$accmod_res->acc_total_amount ?? '0';
                            }else{
                                $triple_cost = 0;
                            }
                            
                            if(isset($accmod_res->hotel_invoice_markup) && $accmod_res->hotel_invoice_markup != null && $accmod_res->hotel_invoice_markup != ''){
                                $triple_sale = (float)$accmod_res->hotel_invoice_markup ?? '0';
                            }else{
                                $triple_sale = 0;
                            }
                            
                            $triple_total_cost = $triple_total_cost + ($triple_cost * (float)$accmod_res->acc_qty);
                            $triple_total_sale = $triple_total_sale + ($triple_sale * (float)$accmod_res->acc_qty);
                            $triple_profit = ($triple_sale - $triple_cost) * (float)$accmod_res->acc_qty;
                            $triple_total_profit = $triple_total_profit + $triple_profit;
                        }
                    }
                }
                if(isset($accomodation_more)){
                    foreach($accomodation_more as $accmod_res){
                        if($accmod_res->more_acc_type == 'Triple'){
                            $triple_cost = (float)$accmod_res->more_acc_total_amount; 
                            if(isset($accmod_res->more_hotel_invoice_markup)){
                                $triple_sale = (float)$accmod_res->more_hotel_invoice_markup ?? '0'; 
                            }else{
                                $triple_sale = 0; 
                            }
                            $triple_total_cost = $triple_total_cost + ($triple_cost * (float)$accmod_res->more_acc_qty);
                            $triple_total_sale = $triple_total_sale + ($triple_sale * (float)$accmod_res->more_acc_qty);
                            $triple_profit = ($triple_sale - $triple_cost) * $accmod_res->more_acc_qty;
                            $triple_total_profit = $triple_total_profit + $triple_profit;
                        }
                    }
                }
                $grand_cost += $triple_total_cost;
                $grand_sale += $triple_total_sale;
                 
                // Caluclate Quad Room Price
                $quad_total_cost = 0;
                $quad_total_sale = 0;
                $quad_total_profit = 0;
                if(isset($accomodation)){
                    foreach($accomodation as $accmod_res){
                        if($accmod_res->acc_type == 'Quad'){
                            $quad_cost = $accmod_res->acc_total_amount; 
                            if(isset($accmod_res->hotel_invoice_markup)){
                                $quad_sale = (float)$accmod_res->hotel_invoice_markup ?? 0; 
                            }else{
                                $quad_sale = 0; 
                            }
                            $quad_total_cost    = $quad_total_cost + ($quad_cost * (float)$accmod_res->acc_qty);
                            $quad_total_sale    = $quad_total_sale + ($quad_sale * (float)$accmod_res->acc_qty);
                            $quad_profit        = ($quad_sale - $quad_cost) * (float)$accmod_res->acc_qty;
                            $quad_total_profit  = $quad_total_profit + $quad_profit;
                        }
                    }
                }
                if(isset($accomodation_more)){
                    foreach($accomodation_more as $accmod_res){
                        if($accmod_res->more_acc_type == 'Quad'){
                            $quad_cost = (float)$accmod_res->more_acc_total_amount;
                            if(isset($accmod_res->more_hotel_invoice_markup)){
                                $quad_sale = (float)$accmod_res->more_hotel_invoice_markup ?? 0; 
                            }else{
                                $quad_sale = 0; 
                            }
                            $quad_total_cost = $quad_total_cost + ($quad_cost * (float)$accmod_res->more_acc_qty);
                            $quad_total_sale = $quad_total_sale + ($quad_sale * (float)$accmod_res->more_acc_qty);
                            $quad_profit = ($quad_sale - $quad_cost) * $accmod_res->more_acc_qty;
                            $quad_total_profit = $quad_total_profit + $quad_profit;
                        }
                    }
                }
                $grand_cost += $quad_total_cost;
                $grand_sale += $quad_total_sale;
                
                // Caluclate Final Price
                $Final_inv_price    = $flight_total_sale + $visa_total_sale + $trans_total_sale + $double_total_sale + $triple_total_sale + $quad_total_sale;
                $Final_inv_profit   = $flight_profit + $visa_profit + $trans_profit + $double_total_profit + $triple_total_profit + $quad_total_profit;
                
                $invoice_payments = DB::table('pay_Invoice_Agent')->where('invoice_Id',$agent_inv_res->id)->get();
                $total_paid_amount = 0;
                foreach($invoice_payments as $inv_pay_res){
                    $total_paid_amount += $inv_pay_res->amount_Paid;
                }
                
                $service_type = '';
                $services = json_decode($agent_inv_res->services);
                // dd($services[0]);
                if($services[0] == 'accomodation_tab'){
                    $service_type = 'Hotel';
                }else if($services[0] == 'flight_tab'){
                    $service_type = 'Flight';
                }else if($services[0] == 'visa_tab'){
                    $service_type = 'Visa';
                }else if($services[0] == 'transportation_tab'){
                    $service_type = 'Transfer';
                }else{
                    $service_type = 'Not Selected';
                }
                
                $inv_single_data = [
                    'agent_Id'          => $agent_inv_res->agent_Id,
                    'agent_name'        => $agent_inv_res->agent_name,
                    'service_type'      => $service_type,
                    'invoice_id'        => $agent_inv_res->id,
                    'generate_id'       => $agent_inv_res->generate_id,
                    'price'             => $agent_inv_res->total_sale_price_all_Services,
                    'paid_amount'       => $agent_inv_res->total_paid_amount,
                    'remaing_amount'    => $agent_inv_res->total_sale_price_all_Services - $agent_inv_res->total_paid_amount,
                    'over_paid_amount'  => $agent_inv_res->over_paid_amount,
                    'profit'            => $agent_inv_res->total_sale_price_all_Services - $agent_inv_res->total_cost_price_all_Services,
                    'total_cost'        => $agent_inv_res->total_cost_price_all_Services,
                    'total_sale'        => $agent_inv_res->total_sale_price_all_Services,
                    'currency'          => $agent_inv_res->currency_symbol,
                    'created_at'        => $agent_inv_res->created_at,
                    'customer_id'       => $agent_inv_res->customer_id
                ];
                array_push($invoices_all_data,$inv_single_data);
            }
            
            $invoice_Acc_details = [];
            foreach($agents_invoice_booking as $agent_inv_res){
                $accomodation       = json_decode($agent_inv_res->accomodation_details);
                $accomodation_more  = json_decode($agent_inv_res->accomodation_details_more);
                 
                // Caluclate Double Room Price
                $double_total_cost = 0;
                $double_total_sale = 0;
                $double_total_profit = 0;
                if(isset($accomodation)){
                    foreach($accomodation as $accmod_res){
                        if($accmod_res->acc_type == 'Double'){
                            (float)$double_cost = $accmod_res->acc_total_amount;
                            if(isset($accmod_res->hotel_invoice_markup)){
                                (float)$double_sale = $accmod_res->hotel_invoice_markup ?? 0; 
                            }else{
                                $double_sale = 0; 
                            }
                            
                             $double_total_cost = $double_total_cost + ((float)$double_cost * (float)$accmod_res->acc_qty);
                             $double_total_sale = $double_total_sale + ((float)$double_sale * (float)$accmod_res->acc_qty);
                             $double_profit = ((float)$double_sale - (float)$double_cost) * (float)$accmod_res->acc_qty;
                             $double_total_profit = $double_total_profit + $double_profit;
                        }
                    }
                }
                if(isset($accomodation_more)){
                    foreach($accomodation_more as $accmod_res){
                        if($accmod_res->more_acc_type == 'Double'){
                            $double_cost = $accmod_res->more_acc_total_amount; 
                            if(isset($accmod_res->more_hotel_invoice_markup)){
                                $double_sale = $accmod_res->more_hotel_invoice_markup ?? 0; 
                            }else{
                                $double_sale = 0; 
                            }
                            $double_total_cost = $double_total_cost + ((float)$double_cost * (float)$accmod_res->more_acc_qty);
                            $double_total_sale = $double_total_sale + ((float)$double_sale * (float)$accmod_res->more_acc_qty);
                            $double_profit = ((float)$double_sale - (float)$double_cost) * (float)$accmod_res->more_acc_qty;
                            $double_total_profit = $double_total_profit + $double_profit;
                        }
                    }
                }
                $grand_cost += $double_total_cost;
                $grand_sale += $double_total_sale;
                
                // Caluclate Triple Room Price
                $triple_total_cost = 0;
                $triple_total_sale = 0;
                $triple_total_profit = 0;
                if(isset($accomodation)){
                    foreach($accomodation as $accmod_res){
                        if($accmod_res->acc_type == 'Triple'){
                            $triple_cost = (float)$accmod_res->acc_total_amount;
                            if(isset($accmod_res->hotel_invoice_markup)){
                                $triple_sale = (float)$accmod_res->hotel_invoice_markup ?? 0; 
                            }else{
                                $triple_sale = 0; 
                            }
                            $triple_total_cost = $triple_total_cost + ($triple_cost * (float)$accmod_res->acc_qty);
                            $triple_total_sale = $triple_total_sale + ($triple_sale * (float)$accmod_res->acc_qty);
                            $triple_profit = ($triple_sale - $triple_cost) * (float)$accmod_res->acc_qty;
                            $triple_total_profit = $triple_total_profit + $triple_profit;
                        }
                    }
                }
                if(isset($accomodation_more)){
                    foreach($accomodation_more as $accmod_res){
                        if($accmod_res->more_acc_type == 'Triple'){
                            $triple_cost = (float)$accmod_res->more_acc_total_amount; 
                            if(isset($accmod_res->more_hotel_invoice_markup)){
                                $triple_sale = (float)$accmod_res->more_hotel_invoice_markup ?? 0; 
                            }else{
                                $triple_sale = 0; 
                            }
                            $triple_total_cost = $triple_total_cost + ($triple_cost * (float)$accmod_res->more_acc_qty);
                            $triple_total_sale = $triple_total_sale + ($triple_sale * (float)$accmod_res->more_acc_qty);
                            $triple_profit = ($triple_sale - $triple_cost) * $accmod_res->more_acc_qty;
                            $triple_total_profit = $triple_total_profit + $triple_profit;
                        }
                    }
                }
                $grand_cost += $triple_total_cost;
                $grand_sale += $triple_total_sale;
                 
                // Caluclate Quad Room Price
                $quad_total_cost = 0;
                $quad_total_sale = 0;
                $quad_total_profit = 0;
                if(isset($accomodation)){
                            foreach($accomodation as $accmod_res){
                                if($accmod_res->acc_type == 'Quad'){
                                    $quad_cost = $accmod_res->acc_total_amount; 
                                    if(isset($accmod_res->hotel_invoice_markup)){
                                        $quad_sale = (float)$accmod_res->hotel_invoice_markup ?? 0; 
                                    }else{
                                        $quad_sale = 0; 
                                    }
                                    
                                     $quad_total_cost = $quad_total_cost + ($quad_cost * (float)$accmod_res->acc_qty);
                                     $quad_total_sale = $quad_total_sale + ($quad_sale * (float)$accmod_res->acc_qty);
                                     $quad_profit = ($quad_sale - $quad_cost) * (float)$accmod_res->acc_qty;
                                     $quad_total_profit = $quad_total_profit + $quad_profit;
                                }
                            }
                         }
                if(isset($accomodation_more)){
                    foreach($accomodation_more as $accmod_res){
                        if($accmod_res->more_acc_type == 'Quad'){
                            $quad_cost = (float)$accmod_res->more_acc_total_amount; 
                            if(isset($accmod_res->more_hotel_invoice_markup)){
                                $quad_sale = (float)$accmod_res->more_hotel_invoice_markup ?? 0; 
                            }else{
                                $quad_sale = 0; 
                            }
                            $quad_total_cost = $quad_total_cost + ($quad_cost * (float)$accmod_res->more_acc_qty);
                            $quad_total_sale = $quad_total_sale + ($quad_sale * (float)$accmod_res->more_acc_qty);
                            $quad_profit = ($quad_sale - $quad_cost) * $accmod_res->more_acc_qty;
                            $quad_total_profit = $quad_total_profit + $quad_profit;
                        }
                    }
                }
                $grand_cost += $quad_total_cost;
                $grand_sale += $quad_total_sale;
                
                // Caluclate Final Price
                $Final_inv_price    = $double_total_sale + $triple_total_sale + $quad_total_sale;
                $Final_inv_profit   = $double_total_profit + $triple_total_profit + $quad_total_profit;
                
                $invoice_payments = DB::table('pay_Invoice_Agent')->where('invoice_Id',$agent_inv_res->id)->get();
                $total_paid_amount = 0;
                foreach($invoice_payments as $inv_pay_res){
                    $total_paid_amount += $inv_pay_res->amount_Paid;
                }
                $inv_single_data = [
                    'invoice_id'        => $agent_inv_res->id,
                    'price'             => $agent_inv_res->total_sale_price_all_Services,
                    'paid_amount'       => $agent_inv_res->total_paid_amount,
                    'remaing_amount'    => $agent_inv_res->total_sale_price_all_Services - $agent_inv_res->total_paid_amount,
                    'over_paid_amount'  => $agent_inv_res->over_paid_amount,
                    'profit'            => $Final_inv_profit,
                    'total_cost'        => $grand_cost,
                    'total_sale'        => $grand_sale,
                    'currency'          => $agent_inv_res->currency_symbol,
                    'customer_id'       => $agent_inv_res->customer_id
                ];
                array_push($invoice_Acc_details,$inv_single_data);
            }
            
            $invoice_Flight_details = [];
            foreach($agents_invoice_booking as $agent_inv_res){
                $markup_details         = json_decode($agent_inv_res->markup_details);
                $more_markup_details    = json_decode($agent_inv_res->more_markup_details);
                 
                // Caluclate Flight Price 
                $grand_cost = 0;
                $grand_sale = 0;
                $flight_cost = 0;
                $flight_sale = 0;
                foreach($markup_details as $mark_res){
                    if($mark_res->markup_Type_Costing == 'flight_Type_Costing'){
                        $flight_cost = $mark_res->without_markup_price; 
                        $flight_sale = $mark_res->markup_price; 
                    }
                }
                        
                $flight_total_cost = (float)$flight_cost * (float)$agent_inv_res->no_of_pax_days;
                $flight_total_sale = (float)$flight_sale * (float)$agent_inv_res->no_of_pax_days;
                
                $flight_profit = $flight_total_sale - $flight_total_cost;
                
                $grand_cost += $flight_total_cost;
                $grand_sale += $flight_total_sale;
                
                // Caluclate Final Price
                $Final_inv_price    = $flight_total_sale;
                $Final_inv_profit   = $flight_profit;
                
                $invoice_payments   = DB::table('pay_Invoice_Agent')->where('invoice_Id',$agent_inv_res->id)->get();
                $total_paid_amount  = 0;
                foreach($invoice_payments as $inv_pay_res){
                    $total_paid_amount += $inv_pay_res->amount_Paid;
                }
                $inv_single_data = [
                    'invoice_id'        => $agent_inv_res->id,
                    'price'             => $agent_inv_res->total_sale_price_all_Services,
                    'paid_amount'       => $agent_inv_res->total_paid_amount,
                    'remaing_amount'    => $agent_inv_res->total_sale_price_all_Services - $agent_inv_res->total_paid_amount,
                    'over_paid_amount'  => $agent_inv_res->over_paid_amount,
                    'profit'            => $Final_inv_profit,
                    'total_cost'        => $grand_cost,
                    'total_sale'        => $grand_sale,
                    'currency'          => $agent_inv_res->currency_symbol,
                    'customer_id'       => $agent_inv_res->customer_id
                ];
                array_push($invoice_Flight_details,$inv_single_data);
            }
            
            $invoice_Visa_details = [];
            foreach($agents_invoice_booking as $agent_inv_res){
                $markup_details         = json_decode($agent_inv_res->markup_details);
                $more_markup_details    = json_decode($agent_inv_res->more_markup_details);
               // Caluclate Visa Price 
                $visa_cost = 0;
                $visa_sale = 0;
                foreach($markup_details as $mark_res){
                    if($mark_res->markup_Type_Costing == 'visa_Type_Costing'){
                        $visa_cost = $mark_res->without_markup_price; 
                        $visa_sale = $mark_res->markup_price; 
                    }
                }
                $visa_total_cost = (float)$visa_cost * (float)$agent_inv_res->no_of_pax_days;
                $visa_total_sale = (float)$visa_sale * (float)$agent_inv_res->no_of_pax_days;
                $visa_profit = $visa_total_sale - $visa_total_cost;
                $grand_cost += $visa_total_cost;
                $grand_sale += $visa_total_sale;

                // Caluclate Final Price
                $Final_inv_price    = $visa_total_sale;
                $Final_inv_profit   = $visa_profit;
                
                $invoice_payments   = DB::table('pay_Invoice_Agent')->where('invoice_Id',$agent_inv_res->id)->get();
                $total_paid_amount  = 0;
                foreach($invoice_payments as $inv_pay_res){
                    $total_paid_amount += $inv_pay_res->amount_Paid;
                }
                $inv_single_data = [
                    'invoice_id'        => $agent_inv_res->id,
                    'price'             => $agent_inv_res->total_sale_price_all_Services,
                    'paid_amount'       => $agent_inv_res->total_paid_amount,
                    'remaing_amount'    => $agent_inv_res->total_sale_price_all_Services - $agent_inv_res->total_paid_amount,
                    'over_paid_amount'  => $agent_inv_res->over_paid_amount,
                    'profit'            => $Final_inv_profit,
                    'total_cost'        => $grand_cost,
                    'total_sale'        => $grand_sale,
                    'currency'          => $agent_inv_res->currency_symbol,
                    'customer_id'       => $agent_inv_res->customer_id
                ];
                array_push($invoice_Visa_details,$inv_single_data);
            }
            
            $invoice_Transportation_details = [];
            foreach($agents_invoice_booking as $agent_inv_res){
                $markup_details         = json_decode($agent_inv_res->markup_details);
                $more_markup_details    = json_decode($agent_inv_res->more_markup_details);
               
                // Caluclate Transportation Price
                $trans_cost = 0;
                $trans_sale = 0;
                foreach($markup_details as $mark_res){
                    if($mark_res->markup_Type_Costing == 'transportation_Type_Costing'){
                        $trans_cost = $mark_res->without_markup_price; 
                        $trans_sale = $mark_res->markup_price; 
                    }
                }
                $trans_total_cost = (float)$trans_cost * (float)$agent_inv_res->no_of_pax_days;
                $trans_total_sale = (float)$trans_sale * (float)$agent_inv_res->no_of_pax_days;
                $trans_profit = $trans_total_sale - $trans_total_cost;
                $grand_cost += $trans_total_cost;
                $grand_sale += $trans_total_sale;

                // Caluclate Final Price
                $Final_inv_price    = $trans_total_sale;
                $Final_inv_profit   = $trans_profit;
                
                $invoice_payments = DB::table('pay_Invoice_Agent')->where('invoice_Id',$agent_inv_res->id)->get();
                $total_paid_amount = 0;
                foreach($invoice_payments as $inv_pay_res){
                    $total_paid_amount += $inv_pay_res->amount_Paid;
                }
                $inv_single_data = [
                    'invoice_id'        => $agent_inv_res->id,
                    'price'             => $agent_inv_res->total_sale_price_all_Services,
                    'paid_amount'       => $agent_inv_res->total_paid_amount,
                    'remaing_amount'    => $agent_inv_res->total_sale_price_all_Services - $agent_inv_res->total_paid_amount,
                    'over_paid_amount'  => $agent_inv_res->over_paid_amount,
                    'profit'            => $Final_inv_profit,
                    'total_cost'        => $grand_cost,
                    'total_sale'        => $grand_sale,
                    'currency'          => $agent_inv_res->currency_symbol,
                    'customer_id'       => $agent_inv_res->customer_id
                ];
                array_push($invoice_Transportation_details,$inv_single_data);
                 
            }
            
            $agent_data = [
                    'agents_tour_booking'               => $booking_all_data,
                    'agents_invoices_booking'           => $invoices_all_data,
                    'invoice_Acc_details'               => $invoice_Acc_details,
                    'invoice_Flight_details'            => $invoice_Flight_details,
                    'invoice_Visa_details'              => $invoice_Visa_details,
                    'invoice_Transportation_details'    => $invoice_Transportation_details,
            ];
            array_push($all_agents_data,$agent_data);
        // }
        
        $all_Users                          = DB::table('customer_subcriptions')->get();
        $agents_data                        = $agent_data;
        
        return view('template/frontend/userdashboard/pages/manage_Office/Agents/booking_financial_stats',compact('all_Users','agents_data'));
    }
    
    
    function add_special_discount_Invoice(Request $request){
        $add_manage_invoices      = DB::table('add_manage_invoices')->where('id',$request->invoice_id)->first();
        // dd($add_manage_invoices);
        DB::beginTransaction();
        try {
            if(isset($add_manage_invoices->total_sale_price_all_Services) && $add_manage_invoices->total_sale_price_all_Services != null && $add_manage_invoices->total_sale_price_all_Services != ''){
                $total_sale_price_all_Services                      = $add_manage_invoices->total_sale_price_all_Services - $request->discount_amount;
                $add_manage_invoices->total_sale_price_all_Services = $total_sale_price_all_Services;
                
                if(isset($add_manage_invoices->discount_amount) && $add_manage_invoices->discount_amount != null && $add_manage_invoices->discount_amount != ''){
                    $discount_amount = $add_manage_invoices->discount_amount + $request->discount_amount;
                }else{
                    $discount_amount = $request->discount_amount;
                }
            }
            
            $result = DB::table('add_manage_invoices')->where('id',$request->invoice_id)->update([
                'total_sale_price_all_Services' => $total_sale_price_all_Services,
                'discount_amount'               => $discount_amount,
            ]);
            DB::commit();
            return response()->json(['message'=>'success']);
        } catch (Throwable $e) {
            DB::rollback();
            return response()->json(['message'=>'error']);
        }
        
    }

    // Agents
    public function create_Agents_Admin(Request $req){
        $all_Users          = DB::table('customer_subcriptions')->get();
        $Agents_detail      = DB::table('Agents_detail')->get();
        return view('template/frontend/userdashboard/pages/manage_Office/Agents/create_Agents',compact('Agents_detail','all_Users'));  
    }
    
    public function create_customer_Admin(Request $req){
        $all_Users          = DB::table('customer_subcriptions')->get();
		$customer_detail    = DB::table('booking_customers')->get();
		$countires          = DB::table('countries')->select('id','name')->get();
        return view('template/frontend/userdashboard/pages/manage_Office/customer/create_customer',compact('customer_detail','countires','all_Users'));
    }
    
    public function submit_customer(Request $req){
        // print_r($req->all());
        // die;
         $userData = CustomerSubcription::where('Auth_key',$req->token)->select('id','status')->first();
        if($userData){
            $request_data = json_decode($req->request_data);
            $country = json_decode($request_data->country);
            
            $customer_exist = DB::table('booking_customers')->where('email',$request_data->email)->first();
            if(!$customer_exist){
                $customer_detail  = DB::table('booking_customers')->insert([
                    'name' => $request_data->cust_name,
                    'opening_balance' => $request_data->opening_balance,
                    'balance' => $request_data->opening_balance,
                    'email' => $request_data->email,
                    'phone' => $request_data->phone,
                    'whatsapp' => $request_data->whatsapp,
                    'gender' => $request_data->gender,
                    'country' => $country->name,
                    'city' => $request_data->city,
                    'post_code' => $request_data->post_code,
                    'customer_id'=> $userData->id
                  ]);
                  
                 $customer_detail  = DB::table('booking_customers')->where('customer_id',$req->customer_id)->get();
                 $countires  = DB::table('countries')->select('id','name')->get();
              
                return response()->json(['status'=>'success','customer_detail'=>$customer_detail,'countires'=>$countires,'message'=>'customer added Successfully']);
            }else{
                 $customer_detail  = DB::table('booking_customers')->where('customer_id',$req->customer_id)->get();
                 $countires  = DB::table('countries')->select('id','name')->get();
                 
                return response()->json(['status'=>'error','customer_detail'=>$customer_detail,'countires'=>$countires,'message'=>'customer already exist']);
            }
              
        }else{
              return response()->json(['status'=>'error','customer_detail'=>'','message'=>'validation Error']);
        }
      
        // return view('template/frontend/userdashboard/pages/manage_Office/Agents/create_Agents',compact('Agents_detail'));  
    }
    
    public function agent_ledeger(Request $req){
        $Agents_detail  = DB::table('Agents_detail')->where('id',$req->agent_id)->first();
        $Agents_ledger_data  = DB::table('agents_ledgers_new')->where('agent_id',$req->agent_id)->get();
        return response()->json(['status'=>'success','Agents_detail'=>$Agents_ledger_data,'Agents_Pers_details'=>$Agents_detail]);
        // return view('template/frontend/userdashboard/pages/manage_Office/Agents/create_Agents',compact('Agents_detail'));  
    }
    
    public function customer_ledeger(Request $req){
        $customer_detail  = DB::table('booking_customers')->where('id',$req->customer_id)->first();
        $customer_ledger_data  = DB::table('customer_ledger')->where('booking_customer',$req->customer_id)->get();
        return response()->json(['status'=>'success','customer_detail'=>$customer_ledger_data,'customer_Pers_details'=>$customer_detail]);
        // return view('template/frontend/userdashboard/pages/manage_Office/Agents/create_Agents',compact('Agents_detail'));  
    }
    
    public function delete_invoice(Request $request){
        // print_r($request->all());
        
        $invoice_data  = DB::table('add_manage_invoices')->where('id',$request->invoice_id)->first();
        
        // print_r($invoice_data);
        // die;
             DB::beginTransaction();
                  
                     try {
            

                            // print_r($insert);
                            $new_acc = json_decode($invoice_data->accomodation_details);
                            $new_acc_more = json_decode($invoice_data->accomodation_details_more);
                            
                           
                            // Loop on Previous Accomodations 
                            
                            DB::table('rooms_bookings_details')->where('booking_id', $invoice_data->id)
                                                                ->where('booking_from','Invoices')->delete();
                                                                
                                                                
                                                                
                                $agent_data = DB::table('Agents_detail')->where('id',$invoice_data->agent_Id)->select('id','balance')->first();
               
                                        if(isset($agent_data)){
                                            // echo "Enter hre ";
                                            $received_amount = $invoice_data->total_sale_price_all_Services;
                                            $agent_balance = $agent_data->balance - $invoice_data->total_sale_price_all_Services;
                                            
                                            // update Agent Balance
                                            DB::table('Agents_detail')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                                            DB::table('agents_ledgers_new')->insert([
                                                'agent_id'=>$agent_data->id,
                                                'received'=>$received_amount,
                                                'balance'=>$agent_balance,
                                                'invoice_no'=>$invoice_data->id,
                                                'customer_id'=>$invoice_data->customer_id,
                                                'date'=>date('Y-m-d'),
                                                'remarks'=>'Invoice Deleted',
                                                ]);
                                        }
                                        
                                        if($invoice_data->booking_customer_id != '-1'){
                                             $agent_data = DB::table('booking_customers')->where('id',$invoice_data->booking_customer_id)->select('id','balance')->first();
               
                                            if(isset($agent_data)){
                                                // echo "Enter hre ";
                                                $received_amount = $invoice_data->total_sale_price_all_Services;
                                                $agent_balance = $agent_data->balance - $invoice_data->total_sale_price_all_Services;
                                                
                                                // update Agent Balance
                                                
                                                DB::table('customer_ledger')->insert([
                                                    'booking_customer'=>$agent_data->id,
                                                    'received'=>$received_amount,
                                                    'balance'=>$agent_balance,
                                                    'invoice_no'=>$invoice_data->id,
                                                    'customer_id'=>$invoice_data->customer_id,
                                                    'date'=>date('Y-m-d'),
                                                    'remarks'=>'Invoice Deleted',
                                                    ]);
                                                    
                                                DB::table('booking_customers')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                                            }
                                        }
                                        
                            
                        
                            
                             // New Element Found Working
                              if(isset($new_acc) && !empty($new_acc)){
                                 foreach($new_acc as $new_acc_res){
                                     $ele_found = false;
                                     
                                      if(isset($new_acc_res->hotelRoom_type_id) AND !empty($new_acc_res->hotelRoom_type_id)){
                                             if(!$ele_found){
                                                 $room_data = DB::table('rooms')->where('id',$new_acc_res->hotelRoom_type_id)->select('id','booked')->first();
                                                    
                                                    
                                                    $update_booked = (int)$room_data->booked - (int)$new_acc_res->acc_qty;
                                                    
                                                    $room_data = DB::table('rooms')->where('id',$new_acc_res->hotelRoom_type_id)->update([
                                                    'booked'=>$update_booked
                                                    ]);
                                                
                                             }
                                      }
                                 }
                             }
                             
                             if(isset($new_acc_more) && !empty($new_acc_more)){
                                 foreach($new_acc_more as $new_acc_res){
                                     $ele_found = false;
                                   
                                     
                                     if(isset($new_acc_res->more_hotelRoom_type_id) AND !empty($new_acc_res->more_hotelRoom_type_id)){
                                         if(!$ele_found){
                                             $room_data = DB::table('rooms')->where('id',$new_acc_res->more_hotelRoom_type_id)->select('id','booked')->first();
                                                
                                                
                                                $update_booked = (int)$room_data->booked - (int)$new_acc_res->more_acc_qty;
                                                
                                                $room_data = DB::table('rooms')->where('id',$new_acc_res->more_hotelRoom_type_id)->update([
                                                'booked'=>$update_booked
                                                ]);
                                            
                                         }
                                     }
                                 }
                             }
                             
                           
                            
                             
                             DB::table('add_manage_invoices')->where('id', $invoice_data->id)->delete();
                           
                             
                            // print_r($arr_ele_found);
                            
                            // die;
                            
                             DB::commit();
                            //  echo "etner i catch commit";
                            //  die;
                                // echo $result;
                                
                                   return response()->json(['message'=>'success',]);
                        } catch (Throwable $e) {
                            // echo "etner i catch ";
                                echo $e;
                            DB::rollback();
                            return response()->json(['message'=>'error','booking_id'=> '']);
                        }
        
        // print_r($invoice_data);
        // die;
    }
    
    public function add_Agents(Request $request){
        
        $opening_balance = $request->opening_bal;
        $Agents_detail                  = new Agents_detail();
        $Agents_detail->customer_id     = $request->customer_id;
        $Agents_detail->opening_balance      = $opening_balance;
        $Agents_detail->balance      = $opening_balance;
        $Agents_detail->agent_Name      = $request->agent_Name;
        $Agents_detail->agent_Email     = $request->agent_Email;
        $Agents_detail->agent_Address   = $request->agent_Address;
        $Agents_detail->agent_contact_number   = $request->agent_contact_number;
        
        
        $Agents_detail->company_name     = $request->company_name;
        $Agents_detail->company_email      = $request->company_email;
        $Agents_detail->company_contact_number     = $request->company_contact_number;
        $Agents_detail->company_address   = $request->company_address;
        $Agents_detail->save();
        return response()->json(['status'=>'success','message'=>'Agent Added Successful!']);
    }
    
    public function edit_Agents(Request $request){
        $edit_Agents    = Agents_detail::find($request->id);
        return response()->json(['status'=>'success','edit_Agents'=>$edit_Agents]); 
    }
    
    public function update_Agents(Request $request){
        $id             = $request->id;
        $Agents_detail  = Agents_detail::find($id);
        if($Agents_detail)
        {
            $Agents_detail->customer_id     = $request->customer_id;
            $Agents_detail->agent_Name      = $request->agent_Name;
            $Agents_detail->agent_Email     = $request->agent_Email;
            $Agents_detail->agent_Address   = $request->agent_Address;
            $Agents_detail->agent_contact_number   = $request->agent_contact_number;
        
            
            $Agents_detail->company_name     = $request->company_name;
            $Agents_detail->company_email      = $request->company_email;
            $Agents_detail->company_contact_number     = $request->company_contact_number;
            $Agents_detail->company_address   = $request->company_address;
            $Agents_detail->update();
            return response()->json(['Success'=>'Agent Updated Successful!']);
        }
        else{
            return response()->json(['Agents_detail'=>$Agents_detail,'Error'=>'Agents Not Updated!']);
        }
    }
    
    public function delete_Agents(Request $request){
        $id             = $request->id;
        $Agents_detail  = Agents_detail::find($id);
        $Agents_detail->delete();
        return response()->json(['message'=>'Agent and its all details deleted Successful!']);
    }
    
    // Invoices
    
    public function create_Invoices(Request $req){
        $customer_id                = $req->customer_id;
        $categories                 = DB::table('categories')->get();
        $attributes                 = DB::table('attributes')->get();
        $customer                   = DB::table('customer_subcriptions')->where('id','!=',$customer_id)->get();
        $all_countries              = country::all();
        $all_countries_currency     = country::all();
        $bir_airports               = DB::table('bir_airports')->get();
        $payment_gateways           = DB::table('payment_gateways')->get();
        $payment_modes              = DB::table('payment_modes')->get();
        $currency_Symbol            = DB::table('customer_subcriptions')->get();
        $Agents_detail              = DB::table('Agents_detail')->get();
        $user_hotels                = Hotels::where('owner_id',$customer_id)->get();
        $mange_currencies           = DB::table('mange_currencies')->get();
        $supplier_detail            = DB::table('rooms_Invoice_Supplier')->get();
        $tranfer_supplier           = DB::table('transfer_Invoice_Supplier')->get();
        $customers_data             = DB::table('booking_customers')->get();
        $all_curr_symboles          = country::all();
        $all_flight_routes          = DB::table('flight_rute')->get();
        $flight_suppliers           = DB::table('supplier')->get();
        $visa_supplier              = DB::table('visa_Sup')->get();
        $visa_types                 = DB::table('visa_types')->get();
        $tranfer_vehicle            = DB::table('tranfer_vehicle')->get();
        $tranfer_destination        = DB::table('tranfer_destination')->get();
        $tranfer_supplier           = DB::table('transfer_Invoice_Supplier')->get();
        $tranfer_company            = DB::table('transfer_Invoice_Company')->get();
        $mange_currencies           = DB::table('mange_currencies')->get();
        $manage_currency            = DB::table('mange_currencies')->get();;
        
        $suppliers    = DB::table('supplier')->get();
        $new_arr      = [];
        foreach($suppliers as $key => $suppliersz){
            $type       = [];
            $all_rutes  = DB::table('flight_rute')->where('dep_supplier',$suppliersz->id)->get();
            foreach($all_rutes as $all_rutesz){
                $rute_type_of_supplier = [
                   'multi_rute' => $all_rutesz->dep_flight_type,
                ];
                array_push($type,$rute_type_of_supplier);
            }
            $rute_type_of_supplier = [   
                'multi_rute_suplier'    => $suppliersz,
                'multi_rute'            => $type,
            ];
            array_push($new_arr,$rute_type_of_supplier);
        }
        
        $supplier   = $new_arr;
        $airline    = SupplierController::fetchairline();
        $airlines   = SupplierController::fetch_airline_code();
        if(isset($airlines)){
            if(isset($airlines->airlines)){
                $airline_code = $airlines->airlines;
            }else{
                $airline_code = '';
            }
        }else{
            $airline_code = '';
        }
        
        // return response()->json(['message'=>'success','fetchedsupplier' => $new_arr]); 
        
        return view('template/frontend/userdashboard/pages/manage_Office/Invoices/create_Invoices',compact('manage_currency','tranfer_vehicle','tranfer_destination','tranfer_supplier','tranfer_company','mange_currencies','airline','visa_types','visa_supplier','airline_code','all_flight_routes','flight_suppliers','tranfer_supplier','all_curr_symboles','supplier_detail','mange_currencies','user_hotels','Agents_detail','customer','attributes','categories','all_countries','payment_gateways','payment_modes','supplier','customers_data'));
        
        // return response()->json(['message'=>'success','tranfer_vehicle'=>$tranfer_vehicle,'tranfer_destination'=> $tranfer_destination,'tranfer_supplier'=> $tranfer_supplier,'tranfer_company'=>$tranfer_company,'mange_currencies'=>$mange_currencies,'visa_supplier'=>$visa_supplier,'visa_types'=>$visa_types,'flight_suppliers'=>$flight_suppliers,'all_flight_routes'=>$all_flight_routes,'all_curr_symboles'=>$all_curr_symboles,'tranfer_supplier'=>$tranfer_supplier,'supplier_detail'=>$supplier_detail,'mange_currencies'=>$mange_currencies,'user_hotels'=>$user_hotels,'Agents_detail'=>$Agents_detail,'currency_Symbol'=>$currency_Symbol,'customer'=>$customer,'attributes'=>$attributes,'categories'=>$categories,'all_countries'=>$all_countries,'all_countries_currency'=>$all_countries_currency,'bir_airports'=>$bir_airports,'payment_gateways'=>$payment_gateways,'payment_modes'=>$payment_modes,'customers_data'=>$customers_data]);
    }
    
    public function add_Invoices(Request $req){
        
        function dateDiffInDays($date1, $date2){
            $diff = strtotime($date2) - strtotime($date1);
            return abs(round($diff / 86400));
        }
        
        function getBetweenDates($startDate, $endDate){
            $rangArray = [];
            $startDate = strtotime($startDate);
            $endDate = strtotime($endDate);
                 
                 $startDate += (86400); 
            for ($currentDate = $startDate; $currentDate <= $endDate; 
                                            $currentDate += (86400)) {
                                                    
                $date = date('Y-m-d', $currentDate);
                $rangArray[] = $date;
            }
      
            return $rangArray;
        }
        
        $accomodation_data = json_decode($req->accomodation_details);
        $accomodation_more_data = json_decode($req->more_accomodation_details);
        
        DB::beginTransaction();
        try {
        
            if(isset($accomodation_data)){
                foreach($accomodation_data as $index => $acc_res){
                if($acc_res->room_select_type == 'true'){
                    
                    $room_type_data = json_decode($acc_res->new_rooms_type);
                     $Rooms = new Rooms;
                                $Rooms->hotel_id =  $acc_res->hotel_id;
                                 $Rooms->rooms_on_rq= '';
                                $Rooms->room_type_id =  $room_type_data->parent_cat; 
                                $Rooms->room_type_name =  $room_type_data->room_type; 
                                $Rooms->room_type_cat =  $room_type_data->id; 
                              
                               
                                $Rooms->quantity =  $acc_res->acc_qty;  
                                $Rooms->min_stay =  0; 
                                $Rooms->max_child =  1; 
                                $Rooms->max_adults =  $room_type_data->no_of_persons; 
                                $Rooms->extra_beds =  0; 
                                $Rooms->extra_beds_charges =  0; 
                                $Rooms->availible_from =  $acc_res->acc_check_in; 
                                $Rooms->availible_to =  $acc_res->acc_check_out; 
                                $Rooms->room_option_date =  $acc_res->acc_check_in; 
                                $Rooms->price_week_type =  'for_all_days'; 
                                $Rooms->price_all_days =  $acc_res->price_per_room_purchase;
                                $Rooms->room_supplier_name =  $acc_res->new_supplier_id;
                                $Rooms->room_meal_type  = $acc_res->hotel_meal_type;
                                // $Rooms->weekdays = serialize($request->weekdays);
                                $Rooms->weekdays = null;
                                $Rooms->weekdays_price =  NULL; 
                                // $Rooms->weekends =  serialize($request->weekend); 
                                $Rooms->weekends =  null;
                                $Rooms->weekends_price =  NULL; 
                                
                              
                                
                                $user_id = $req->customer_id;
                                $Rooms->owner_id = $user_id;
                                
                                
                                $result = $Rooms->save();
                                $Roomsid = $Rooms->id;
                                
                                $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$Rooms->room_supplier_name)->select('id','balance','payable')->first();
                   
                                    if(isset($supplier_data)){
                                        // echo "Enter hre ";
                                        
                                             $week_days_total = 0;
                                             $week_end_days_totals = 0;
                                             $total_price = 0;
                                            if($Rooms->price_week_type == 'for_all_days'){
                                                $avaiable_days = dateDiffInDays($Rooms->availible_from, $Rooms->availible_to);
                                                $total_price = $Rooms->price_all_days * $avaiable_days;
                                            }else{
                                                 $avaiable_days = dateDiffInDays($Rooms->availible_from, $Rooms->availible_to);
                                                 
                                                 
                                                 $all_days = getBetweenDates($Rooms->availible_from, $Rooms->availible_to);
                                                 $week_days = json_decode($Rooms->weekdays);
                                                 $week_end_days = json_decode($Rooms->weekends);
                                                 
                                                
                                                 foreach($all_days as $day_res){
                                                     $day = date('l', strtotime($day_res));
                                                     $day = trim($day);
                                                     $week_day_found = false;
                                                     $week_end_day_found = false;
                                                    
                                                     foreach($week_days as $week_day_res){
                                                         if($week_day_res == $day){
                                                             $week_day_found = true;
                                                         }
                                                     }
                                              
                                                     if($week_day_found){
                                                         $week_days_total += $Rooms->weekdays_price;
                                                     }else{
                                                         $week_end_days_totals += $Rooms->weekends_price;
                                                     }
                                                     
                                                     
                                                    //  foreach($week_end_days as $week_day_res){
                                                    //      if($week_day_res == $day){
                                                    //          $week_end_day_found = true;
                                                    //      }
                                                    //  }
                                                    //   if($week_end_day_found){
                                                          
                                                    //  }
                                                 }
                                                 
                                                 
                                                  
                                                 
                                                //  print_r($all_days);
                                                 $total_price = $week_days_total + $week_end_days_totals;
                                            }
                                            
                                            
                                        $all_days_price = $total_price * $Rooms->quantity;
                                        $supplier_balance = $supplier_data->balance + $all_days_price;
                                        
                                        // update Agent Balance
                                        
                                        DB::table('hotel_supplier_ledger')->insert([
                                            'supplier_id'=>$supplier_data->id,
                                            'payment'=>$all_days_price,
                                            'balance'=>$supplier_balance,
                                            'payable_balance'=>$supplier_data->payable,
                                            'room_id'=>$Roomsid,
                                            'customer_id'=>$user_id,
                                            'date'=>date('Y-m-d'),
                                            'available_from'=>$Rooms->availible_from,
                                            'available_to'=>$Rooms->availible_to,
                                            'room_quantity'=>$Rooms->quantity,
                                            ]);
                                            
                                        DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                                        
                                        
                                          
                                                                    
                                    }
                                    
                                $accomodation_data[$index]->acc_type = $room_type_data->parent_cat;
                                $accomodation_data[$index]->hotel_supplier_id = $acc_res->new_supplier_id;
                                $accomodation_data[$index]->hotel_type_id = $room_type_data->id;
                                $accomodation_data[$index]->hotel_type_cat = $room_type_data->room_type;
                                $accomodation_data[$index]->hotelRoom_type_id = $Roomsid;
                }
                
            }
            }
            // print_r($accomodation_data);
            
            if(isset($accomodation_more_data)){
                foreach($accomodation_more_data as $index => $acc_res){
                if($acc_res->more_room_select_type == 'true'){
                    
                    $room_type_data = json_decode($acc_res->more_new_rooms_type);
                     $Rooms = new Rooms;
                                $Rooms->hotel_id =  $acc_res->more_hotel_id;
                                 $Rooms->rooms_on_rq= '';
                                $Rooms->room_type_id =  $room_type_data->parent_cat; 
                                $Rooms->room_type_name =  $room_type_data->room_type; 
                                $Rooms->room_type_cat =  $room_type_data->id; 
                              
                               
                                $Rooms->quantity =  $acc_res->more_acc_qty;  
                                $Rooms->min_stay =  0; 
                                $Rooms->max_child =  1; 
                                $Rooms->max_adults =  $room_type_data->no_of_persons; 
                                $Rooms->extra_beds =  0; 
                                $Rooms->extra_beds_charges =  0; 
                                $Rooms->availible_from =  $acc_res->more_acc_check_in; 
                                $Rooms->availible_to =  $acc_res->more_acc_check_out; 
                                $Rooms->room_option_date =  $acc_res->more_acc_check_in; 
                                $Rooms->price_week_type =  'for_all_days'; 
                                $Rooms->price_all_days =  $acc_res->more_price_per_room_purchase;
                                $Rooms->room_supplier_name =  $acc_res->more_new_supplier_id;
                                $Rooms->room_meal_type  = $acc_res->more_hotel_meal_type;
                                // $Rooms->weekdays = serialize($request->weekdays);
                                $Rooms->weekdays = null;
                                $Rooms->weekdays_price =  NULL; 
                                // $Rooms->weekends =  serialize($request->weekend); 
                                $Rooms->weekends =  null;
                                $Rooms->weekends_price =  NULL; 
                                
                              
                                
                                $user_id = $req->customer_id;
                                $Rooms->owner_id = $user_id;
                                
                                
                                $result = $Rooms->save();
                                $Roomsid = $Rooms->id;
                                
                                $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$Rooms->room_supplier_name)->select('id','balance','payable')->first();
                   
                                    if(isset($supplier_data)){
                                        // echo "Enter hre ";
                                        
                                             $week_days_total = 0;
                                             $week_end_days_totals = 0;
                                             $total_price = 0;
                                            if($Rooms->price_week_type == 'for_all_days'){
                                                $avaiable_days = dateDiffInDays($Rooms->availible_from, $Rooms->availible_to);
                                                $total_price = $Rooms->price_all_days * $avaiable_days;
                                            }
                                            
                                            
                                        $all_days_price = $total_price * $Rooms->quantity;
                                        $supplier_balance = $supplier_data->balance + $all_days_price;
                                        
                                        // update Agent Balance
                                        
                                        DB::table('hotel_supplier_ledger')->insert([
                                            'supplier_id'=>$supplier_data->id,
                                            'payment'=>$all_days_price,
                                            'balance'=>$supplier_balance,
                                            'payable_balance'=>$supplier_data->payable,
                                            'room_id'=>$Roomsid,
                                            'customer_id'=>$user_id,
                                            'date'=>date('Y-m-d'),
                                            'available_from'=>$Rooms->availible_from,
                                            'available_to'=>$Rooms->availible_to,
                                            'room_quantity'=>$Rooms->quantity,
                                            ]);
                                            
                                        DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                                        
                                        
                                          
                                                                    
                                    }
                                    
                                $accomodation_more_data[$index]->more_acc_type = $room_type_data->parent_cat;
                                $accomodation_more_data[$index]->more_hotel_supplier_id = $acc_res->more_new_supplier_id;
                                $accomodation_more_data[$index]->more_hotel_type_id = $room_type_data->id;
                                $accomodation_more_data[$index]->more_hotel_type_cat = $room_type_data->room_type;
                                $accomodation_more_data[$index]->more_hotelRoom_type_id = $Roomsid;
                }
                
            }
            }
            
            $visa_details = json_decode($req->all_visa_price_details);
            // print_r($visa_details);
            // die;
            
            if(isset($visa_details)){
                foreach($visa_details as $index => $visa_res){
                    
                    // 1 Check Add New Visa or Exists Use
                    if($visa_res->visa_add_type_new !== 'false'){
                        // Add As New
                        
                        $visa_avail_id = DB::table('visa_Availability')->insertGetId([
                                'visa_supplier' => $visa_res->visa_supplier_id,
                                'visa_type' => $visa_res->visa_type_id,
                                'visa_qty' => $visa_res->visa_occupied,
                                'visa_available' => $visa_res->visa_occupied,
                                'visa_price' => $visa_res->visa_fee_purchase,
                                'availability_from' => $visa_res->visa_av_from,
                                'availability_to' => $visa_res->visa_av_to,
                                'country' => $visa_res->visa_country_id,
                                'currency_conversion' => $req->conversion_type_Id,
                                'visa_price_conversion_rate' => $visa_res->exchange_rate_visa,
                                'visa_converted_price'=> $visa_res->visa_fee,
                                'customer_id' => $req->customer_id,
                        ]);
                        
                           $visa_details[$index]->visa_avail_id = $visa_avail_id;
                           $supplier_data = DB::table('visa_Sup')->where('id',$visa_res->visa_supplier_id)->first();
        
                            if(isset($supplier_data)){
                                $supplier_balance = $supplier_data->balance + $visa_res->visa_purchase_total;
                                
                                DB::table('visa_supplier_ledger')->insert([
                                        'supplier_id' => $visa_res->visa_supplier_id,
                                        'payment' => $visa_res->visa_purchase_total,
                                        'balance' => $supplier_balance,
                                        'payable' => $supplier_data->payable,
                                        'visa_qty' => $visa_res->visa_occupied,
                                        'visa_type' => $visa_res->visa_type_id,
                                        'visa_price' => $visa_res->visa_fee_purchase,
                                        'visa_avl_id'=> $visa_avail_id,
                                        'date'=> date('Y-m-d'),
                                        'remarks'=> 'New Visa Purchased',
                                        'customer_id'=> $req->customer_id,
                                    ]);
                                
                                $data=DB::table('visa_Sup')->where('id',$visa_res->visa_supplier_id)->update([  
                                   'balance'               => $supplier_balance,
                                ]);
                            }
                    }
                    
                   
                    
                 
                }
            }
            
            $visa_details = json_encode($visa_details);
            // print_r($accomodation_more_data);
            // die;
            
            $insert = new addManageInvoice();
            //new additon
            //1
            $insert->customer_id             = $req->customer_id;
            $insert->booking_customer_id     = $req->booking_customer_id;
            $insert->services                = $req->services;
            $insert->agent_Id                = $req->agent_Id;
            $insert->agent_Name              = $req->agent_Name;
            $insert->agent_Company_Name      = $req->agent_Company_Name;
            $insert->prefix                  = $req->prefix;
            $insert->f_name                  = $req->f_name;
            $insert->middle_name             = $req->middle_name;
            
            $insert->lead_nationality       = $req->lead_nationality;
            $insert->lead_dob               = $req->lead_dob;
            $insert->lead_passport_number   = $req->lead_passport_number;
            $insert->lead_passport_expiry   = $req->lead_passport_expiry;
            
            $insert->currency_conversion     = $req->currency_conversion;
            $insert->conversion_type_Id      = $req->conversion_type_Id;
            $insert->city_Count              = $req->city_Count;
            
            $insert->gender                  = $req->gender;
            $insert->email                   = $req->email;
            $insert->contact_landline        = $req->contact_landline;
            $insert->mobile                  = $req->mobile;
            $insert->contact_work            = $req->contact_work;
            $insert->postCode                = $req->postCode;
            $insert->country                 = $req->country;
            $insert->city                    = $req->city;
            $insert->primery_address         = $req->primery_address;
            // $insert->quotation_prepared      = $req->quotation_prepared;
            // $insert->quotation_valid_date    = $req->quotation_valid_date;
            //2
            $insert->adults                  = $req->adults;
            $insert->childs                  = $req->childs;
            $insert->infant                  = $req->infant;
            $insert->passengerDetailAdults   = $req->passengerDetailAdults;
            $insert->passengerDetailChilds   = $req->passengerDetailChilds;
            $insert->passengerDetailInfant   = $req->passengerDetailInfant;
            // Package_Data
            $generate_id                            = rand(0,9999999);
            $insert->generate_id                    = $generate_id;
            $insert->title                          = $req->title;
            $insert->external_packages              = '';
            $insert->content                        = $req->content;
            $insert->categories                     = $req->tour_categories;
            $insert->tour_attributes                = $req->tour_attributes;
            $insert->no_of_pax_days                 = $req->no_of_pax_days;
            $insert->destination_details            = $req->destination_details;
            $insert->destination_details_more       = $req->destination_details_more;
            
            $insert->flight_route_type              = $req->flight_route_type;
            $insert->flight_supplier                = $req->flight_supplier;
            $insert->flights_details                = $req->flights_details;
            $insert->return_flights_details         = $req->return_flights_details;
            $insert->flights_Pax_details            = $req->flights_Pax_details;
            
            $insert->accomodation_details           = json_encode($accomodation_data);
            $insert->accomodation_details_more      = json_encode($accomodation_more_data);
            $insert->transportation_details         = $req->transportation_details;
            $insert->transportation_details_more    = $req->transportation_details_more;
            
            $insert->visa_type                      = $req->visa_type;
            $insert->visa_fee                       = $req->visa_fee;
            $insert->visa_Pax                       = $req->visa_Pax;
            $insert->exchange_rate_visaI            = $req->exchange_rate_visaI;
            $insert->total_visa_markup_value        = $req->total_visa_markup_value;
            $insert->exchange_rate_visa_fee         = $req->exchange_rate_visa_fee;
            
            $insert->more_visa_details              = $req->more_visa_details;
            $insert->all_visa_price_details         = $visa_details;
            $insert->visa_rules_regulations         = $req->visa_rules_regulations;
            $insert->visa_image                     = $req->visa_image;
            
            $insert->without_markup_total_price_visa    = $req->without_markup_total_price_visa;
            $insert->markup_total_price_visa            = $req->markup_total_price_visa;
            
            $insert->quad_grand_total_amount        = $req->quad_grand_total_amount;
            $insert->triple_grand_total_amount      = $req->triple_grand_total_amount;
            $insert->double_grand_total_amount      = $req->double_grand_total_amount;
            $insert->quad_cost_price                = $req->quad_cost_price;
            $insert->triple_cost_price              = $req->triple_cost_price;
            $insert->double_cost_price              = $req->double_cost_price;
            $insert->all_markup_type                = $req->all_markup_type;
            $insert->all_markup_add                 = $req->all_markup_add;
            $insert->gallery_images                 = $req->gallery_images;
            $insert->start_date                     = $req->start_date;
            $insert->end_date=$req->end_date;
            $insert->time_duration=$req->time_duration;
            $insert->tour_location=$req->tour_location;
            $insert->whats_included=$req->whats_included;
            $insert->whats_excluded=$req->whats_excluded;
            $insert->currency_symbol=$req->currency_symbol;
            $insert->tour_publish=$req->defalut_state;
            $insert->tour_author=$req->tour_author;
            $insert->tour_feature=$req->tour_feature;
            $insert->defalut_state=$req->defalut_state;
            $insert->payment_gateways=$req->payment_gateways;
            $insert->payment_modes=$req->payment_modes;
            $insert->markup_details=$req->markup_details;
            $insert->more_markup_details=$req->more_markup_details;
            $insert->tour_featured_image=$req->tour_featured_image;
            $insert->tour_banner_image=$req->tour_banner_image;
            $insert->Itinerary_details=$req->Itinerary_details;
            $insert->tour_itinerary_details_1=$req->tour_itinerary_details_1;
            $insert->tour_extra_price=$req->tour_extra_price;
            $insert->tour_extra_price_1=$req->tour_extra_price_1;
            $insert->tour_faq=$req->tour_faq;
            $insert->tour_faq_1=$req->tour_faq_1;
            
            $insert->payment_messag=$req->checkout_message;
            
            $insert->total_Cost_Price_All=$req->total_Cost_Price_All;
            $insert->total_Sale_Price_All=$req->total_Sale_Price_All;
            
            $insert->option_date=$req->option_date;
            $insert->reservation_number=$req->reservation_number;
            $insert->hotel_reservation_number=$req->hotel_reservation_number;
            
            $insert->total_sale_price_all_Services = $req->total_sale_price_all_Services;
            $insert->total_cost_price_all_Services = $req->total_cost_price_all_Services;
            
            $insert->without_discount_total_sale_amount = $req->total_sale_price_all_Services;
            
            $insert->adultfinalqty              = $req->adultfinalqty ?? ""; 
            $insert->adultPerprice              = $req->adultPerprice ?? ""; 
            $insert->adult_total_with_markup    = $req->adult_total_with_markup ?? ""; 
            $insert->adult_markup_price         = $req->adult_markup_price ?? ""; 
            $insert->childfinalqty              = $req->childfinalqty ?? ""; 
            $insert->childPerprice              = $req->childPerprice ?? ""; 
            $insert->child_total_with_markup    = $req->child_total_with_markup ?? ""; 
            $insert->infantfinalqty             = $req->infantfinalqty ?? ""; 
            $insert->infantPerprice             = $req->infantPerprice ?? ""; 
            $insert->infant_total_with_markup   = $req->infant_total_with_markup ?? ""; 
            
            $insert->adult_markup_price1        = $req->adult_markup_price1 ?? ""; 
            $insert->total_markup_for_child     = $req->total_markup_for_child ?? ""; 
            $insert->total_markup_for_infant    = $req->total_markup_for_infant ?? ""; 
            
            $insert->transfer_supplier          = $req->transfer_supplier;
            $insert->transfer_supplier_id       = $req->transfer_supplier_id;
            
            $insert->quotation_id               = $req->quotation_id ?? '';
            $insert->quotation_Invoice          = $req->quotation_Invoice ?? '';
        
            $insert->save();
            
            $agent_data = DB::table('Agents_detail')->where('id',$req->agent_Id)->select('id','balance')->first();
            if(isset($agent_data)){
                // echo "Enter hre ";
                $agent_balance = $agent_data->balance + $insert->total_sale_price_all_Services;
                
                // update Agent Balance
                DB::table('Agents_detail')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                DB::table('agents_ledgers_new')->insert([
                    'agent_id'=>$agent_data->id,
                    'received'=>$insert->total_sale_price_all_Services,
                    'balance'=>$agent_balance,
                    'invoice_no'=>$insert->id,
                    'customer_id'=>$req->customer_id,
                    'date'=>date('Y-m-d'),
                    ]);
            }
            
            if($req->booking_customer_id != '-1'){
                 $customer_data = DB::table('booking_customers')->where('id',$req->booking_customer_id)->select('id','balance')->first();
                // print_r($agent_data);
                if(isset($customer_data)){
                    // echo "Enter hre ";
                    $customer_balance = $customer_data->balance + $insert->total_sale_price_all_Services;
                    
                    // update Agent Balance
                    
                    DB::table('customer_ledger')->insert([
                        'booking_customer'=>$customer_data->id,
                        'received'=>$insert->total_sale_price_all_Services,
                        'balance'=>$customer_balance,
                        'invoice_no'=>$insert->id,
                        'customer_id'=>$req->customer_id,
                        'date'=>date('Y-m-d'),
                        ]);
                        
                    DB::table('booking_customers')->where('id',$customer_data->id)->update(['balance'=>$customer_balance]);
                }
            }
            
            $invoice_id                 = $insert->id;
           
            $accomodation               = $accomodation_data;
            $more_accomodation_details  = $accomodation_more_data;
            
            $notification_insert = new alhijaz_Notofication();
            $notification_insert->type_of_notification_id   = $insert->id ?? ''; 
            $notification_insert->customer_id               = $insert->customer_id ?? ''; 
            $notification_insert->type_of_notification      = 'create_Invoice' ?? ''; 
            $notification_insert->generate_id               = $insert->generate_id ?? '';
            $notification_insert->notification_creator_name = $req->agent_Name ?? '';
            $notification_insert->total_price               = $insert->total_sale_price_all_Services ?? ''; 
            $notification_insert->remaining_price           = $insert->total_sale_price_all_Services ?? ''; 
            $notification_insert->notification_status       = '1' ?? ''; 
            
            $notification_insert->save();
            
            $flights_Pax_details            = json_decode($req->flights_Pax_details);
            
            if(isset($flights_Pax_details) && $flights_Pax_details != null && $flights_Pax_details != ''){
                foreach($flights_Pax_details as $value){
                    
                    DB::table('flight_seats_occupied')->insert([
                        'token'                         => $req->token,
                        'type'                          => 'Invoice',
                        'booking_id'                    => $invoice_id,
                        'flight_supplier'               => $req->flight_supplier,
                        'flight_route_id'               => $value->flight_route_id_occupied,
                        'flights_adult_seats'           => $value->flights_adult_seats,
                        'flights_child_seats'           => $value->flights_child_seats,
                        'flight_route_seats_occupied'   => $value->flights_adult_seats + $value->flights_child_seats,
                    ]);
                
                
                    // Update Flight Supplier Balance
                    
                    $supplier_data = DB::table('supplier')->where('id',$req->flight_supplier)->first();
                    
                    //  Calculate Child Price
                    $child_price_wi_adult_price = $value->flights_cost_per_seats_adult * $value->flights_child_seats;
                    $child_price_wi_child_price = $value->flights_cost_per_seats_child * $value->flights_child_seats;
                    
                    $infant_price = $value->flights_cost_per_seats_infant * $value->flights_infant_seats;
                    
                    $price_diff = $child_price_wi_adult_price - $child_price_wi_child_price;
                    
                    if($price_diff != 0 || $infant_price != 0){
                        $supplier_balance = $supplier_data->balance - $price_diff;
                        
                        $supplier_balance = $supplier_balance + $infant_price;
                        $total_differ = $infant_price - $price_diff;
                        
                        DB::table('flight_supplier_ledger')->insert([
                                    'supplier_id'=>$supplier_data->id,
                                    'payment'=>$total_differ,
                                    'balance'=>$supplier_balance,
                                    'route_id'=>$value->flight_route_id_occupied,
                                    'date'=>date('Y-m-d'),
                                    'customer_id'=>$insert->customer_id,
                                    'adult_price'=>$value->flights_cost_per_seats_adult,
                                    'child_price'=>$value->flights_cost_per_seats_child,
                                    'infant_price'=>$value->flights_cost_per_seats_infant,
                                    'adult_seats_booked'=>$value->flights_adult_seats,
                                    'child_seats_booked'=>$value->flights_child_seats,
                                    'infant_seats_booked'=>$value->flights_infant_seats,
                                    'invoice_no'=>$insert->id,
                                    'remarks'=>'Invoice Booked',
                                  ]);
                                  
                        DB::table('supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                    }  
                }
            }
            
            if(isset($accomodation)){
                 foreach($accomodation as $accomodation_res){
                     
                     if(isset($accomodation_res->hotelRoom_type_idM)){
               
                    
                                $room_data = DB::table('rooms')->where('id',$accomodation_res->hotelRoom_type_id)->first();
                                
                                if($room_data){
                                    
                                    
                                    $total_booked = $room_data->booked + $accomodation_res->acc_qty;
                                    
                                   
                                   DB::table('rooms_bookings_details')->insert([
                                             'room_id'=> $accomodation_res->hotelRoom_type_id,
                                             'booking_from'=>'Invoices',
                                             'quantity'=>$accomodation_res->acc_qty,
                                             'booking_id'=>$invoice_id,
                                             'date'=>date('Y-m-d'),
                                             'check_in'=>$accomodation_res->acc_check_in,
                                             'check_out'=>$accomodation_res->acc_check_out,
                                             ]);
                                    
                                
                                    DB::table('rooms')->where('id',$accomodation_res->hotelRoom_type_id)->update(['booked'=>$total_booked]);
                                    
                                    // Update Hotel Supplier Balance
                            
                                     $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
           
                                     if(isset($supplier_data)){
                                            // echo "Enter hre ";
                                            
                                                 $week_days_total = 0;
                                                 $week_end_days_totals = 0;
                                                 $total_price = 0;
                                                 $accomodation_res->acc_check_in = date('Y-m-d',strtotime($accomodation_res->acc_check_in));
                                                 $accomodation_res->acc_check_out = date('Y-m-d',strtotime($accomodation_res->acc_check_out));
                                                if($room_data->price_week_type == 'for_all_days'){
                                                    $avaiable_days = dateDiffInDays($accomodation_res->acc_check_in, $accomodation_res->acc_check_out);
                                                    $total_price = $room_data->price_all_days * $avaiable_days;
                                                }else{
                                                     $avaiable_days = dateDiffInDays($accomodation_res->acc_check_in, $accomodation_res->acc_check_out);
                                                     
                                                     $all_days = getBetweenDates($accomodation_res->acc_check_in, $accomodation_res->acc_check_out);
                                                     
                                                    //  print_r($all_days);
                                                     $week_days = json_decode($room_data->weekdays);
                                                     $week_end_days = json_decode($room_data->weekends);
                                                     
                                                     foreach($all_days as $day_res){
                                                         $day = date('l', strtotime($day_res));
                                                         $day = trim($day);
                                                         $week_day_found = false;
                                                         $week_end_day_found = false;
                                                        
                                                         foreach($week_days as $week_day_res){
                                                             if($week_day_res == $day){
                                                                 $week_day_found = true;
                                                             }
                                                         }
                                                  
                                                        // echo "  ".$room_data->weekdays_price;
                                                         if($week_day_found){
                                                             $week_days_total += $room_data->weekdays_price;
                                                         }else{
                                                             $week_end_days_totals += $room_data->weekends_price;
                                                         }
                                                         
                                                         
                                                        //  foreach($week_end_days as $week_day_res){
                                                        //      if($week_day_res == $day){
                                                        //          $week_end_day_found = true;
                                                        //      }
                                                        //  }
                                                        //   if($week_end_day_found){
                                                              
                                                        //  }
                                                     }
                                                     
                                                     
                                                    //   echo "Week Days price Total $week_days_total and Weekend price Total $week_end_days_totals";
                                                     
                                                    //  print_r($all_days);
                                                     $total_price = $week_days_total + $week_end_days_totals;
                                                }
                                                
                                                
                                            $all_days_price = $total_price * $accomodation_res->acc_qty;
                                            
                                            // echo "All Days price is $total_price qty ".$accomodation_res->acc_qty;
                                            // die;
                                            
                                            // echo "The supplier Balance is ".$supplier_data->balance;
                                            $supplier_balance = $supplier_data->balance;
                                            $supplier_payable_balance = $supplier_data->payable + $all_days_price;
                                            
                                            // update Agent Balance
                                            
                                            DB::table('hotel_supplier_ledger')->insert([
                                                'supplier_id'=>$supplier_data->id,
                                                'payment'=>$all_days_price,
                                                'balance'=>$supplier_balance,
                                                'payable_balance'=>$supplier_payable_balance,
                                                'room_id'=>$room_data->id,
                                                'customer_id'=>$req->customer_id,
                                                'date'=>date('Y-m-d'),
                                                'invoice_no'=>$invoice_id,
                                                'available_from'=>$accomodation_res->acc_check_in,
                                                'available_to'=>$accomodation_res->acc_check_out,
                                                'room_quantity'=>$accomodation_res->acc_qty,
                                                ]);
                                                
                                            DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                                                'balance'=>$supplier_balance,
                                                'payable'=>$supplier_payable_balance
                                                ]);
                                            
                                            
                                              
                                                                        
                                        }
                                }
                     }
                  
                 }
                
            }
            
            if(isset($more_accomodation_details)){
                // print_r($more_accomodation_details);
                
                 foreach($more_accomodation_details as $accomodation_res){
                     if(isset($accomodation_res->more_hotelRoom_type_idM)){
                     
                            
                            $room_data = DB::table('rooms')->where('id',$accomodation_res->more_hotelRoom_type_id)->first();
                                
                                if($room_data){
                                    
                               
                                    $total_booked = $room_data->booked + $accomodation_res->more_acc_qty;
                                    
                                    DB::table('rooms_bookings_details')->insert([
                                             'room_id'=> $accomodation_res->more_hotelRoom_type_id,
                                             'booking_from'=>'Invoices',
                                             'quantity'=>$accomodation_res->more_acc_qty,
                                             'booking_id'=>$invoice_id,
                                             'date'=>date('Y-m-d'),
                                             'check_in'=>$accomodation_res->more_acc_check_in,
                                             'check_out'=>$accomodation_res->more_acc_check_out,
                                             ]);
                                             
                                    DB::table('rooms')->where('id',$accomodation_res->more_hotelRoom_type_id)->update(['booked'=>$total_booked]);
                                    
                                    
                                    $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
           
                                     if(isset($supplier_data)){
                                            // echo "Enter hre ";
                                            
                                                 $week_days_total = 0;
                                                 $week_end_days_totals = 0;
                                                 $total_price = 0;
                                                 $accomodation_res->acc_check_in = date('Y-m-d',strtotime($accomodation_res->more_acc_check_in));
                                                 $accomodation_res->acc_check_out = date('Y-m-d',strtotime($accomodation_res->more_acc_check_out));
                                                if($room_data->price_week_type == 'for_all_days'){
                                                    $avaiable_days = dateDiffInDays($accomodation_res->acc_check_in, $accomodation_res->acc_check_out);
                                                    $total_price = $room_data->price_all_days * $avaiable_days;
                                                }else{
                                                     $avaiable_days = dateDiffInDays($accomodation_res->acc_check_in, $accomodation_res->acc_check_out);
                                                     
                                                     $all_days = getBetweenDates($accomodation_res->acc_check_in, $accomodation_res->acc_check_out);
                                                     
                                                    //  print_r($all_days);
                                                     $week_days = json_decode($room_data->weekdays);
                                                     $week_end_days = json_decode($room_data->weekends);
                                                     
                                                     foreach($all_days as $day_res){
                                                         $day = date('l', strtotime($day_res));
                                                         $day = trim($day);
                                                         $week_day_found = false;
                                                         $week_end_day_found = false;
                                                        
                                                         foreach($week_days as $week_day_res){
                                                             if($week_day_res == $day){
                                                                 $week_day_found = true;
                                                             }
                                                         }
                                                  
                                                        // echo "  ".$room_data->weekdays_price;
                                                         if($week_day_found){
                                                             $week_days_total += $room_data->weekdays_price;
                                                         }else{
                                                             $week_end_days_totals += $room_data->weekends_price;
                                                         }
                                                         
                                                         
                                                        //  foreach($week_end_days as $week_day_res){
                                                        //      if($week_day_res == $day){
                                                        //          $week_end_day_found = true;
                                                        //      }
                                                        //  }
                                                        //   if($week_end_day_found){
                                                              
                                                        //  }
                                                     }
                                                     
                                                    //   echo "Week Days price Total $week_days_total and Weekend price Total $week_end_days_totals";
                                                      
                                                     
                                                    //  print_r($all_days);
                                                     $total_price = $week_days_total + $week_end_days_totals;
                                                }
                                                
                                                
                                            $all_days_price = $total_price * $accomodation_res->more_acc_qty;
                                            
                                            // echo "All Days price is $total_price qty ".$accomodation_res->acc_qty;
                                            // die;
                                            
                                            // echo "The supplier Balance is ".$supplier_data->balance;
                                            $supplier_balance = $supplier_data->balance;
                                            $supplier_payable_balance = $supplier_data->payable + $all_days_price;
                                            
                                            // update Agent Balance
                                            
                                            DB::table('hotel_supplier_ledger')->insert([
                                                    'supplier_id'=>$supplier_data->id,
                                                    'payment'=>$all_days_price,
                                                    'balance'=>$supplier_balance,
                                                    'payable_balance'=>$supplier_payable_balance,
                                                    'room_id'=>$room_data->id,
                                                    'customer_id'=>$req->customer_id,
                                                    'date'=>date('Y-m-d'),
                                                    'invoice_no'=>$invoice_id,
                                                    'available_from'=>$accomodation_res->acc_check_in,
                                                    'available_to'=>$accomodation_res->acc_check_out,
                                                    'room_quantity'=>$accomodation_res->more_acc_qty,
                                                ]);
                                                
                                            DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                                                'balance'=>$supplier_balance,
                                                'payable'=>$supplier_payable_balance
                                                ]);
                                            
                                            
                                              
                                                                        
                                        }
                                }
                                
                       
                     }
                      
                 }
            
            }
            
            if(isset($req->transportation_details) && !empty($req->transportation_details) && $req->transportation_details != null){
                $transfer_data = json_decode($req->transportation_details);
                if(isset($transfer_data)){
                    if(1 == count($transfer_data)){
                        foreach($transfer_data as $index => $trans_res_data){
                            if(isset($trans_res_data->transportation_pick_up_location) && $trans_res_data->transportation_pick_up_location != null && $trans_res_data->transportation_pick_up_location != ''){
                                $transfer_sup_data = DB::table('transfer_Invoice_Supplier')->where('id',$req->transfer_supplier_id)->select('id','balance')->first();
                                $trans_sup_balance = $transfer_sup_data->balance + $trans_res_data->transportation_vehicle_total_price;
                                DB::table('transfer_supplier_ledger')->insert([
                                        'supplier_id'=>$req->transfer_supplier_id,
                                        'payment'=> $trans_res_data->transportation_vehicle_total_price,
                                        'balance'=> $trans_sup_balance,
                                        'vehicle_price'=> $trans_res_data->transportation_price_per_vehicle,
                                        'vehicle_type'=>$trans_res_data->transportation_vehicle_type,
                                        'no_of_vehicles'=> $trans_res_data->transportation_no_of_vehicle,
                                        'destination_id'=> $trans_res_data->destination_id,
                                        'invoice_no'=>$invoice_id,
                                        'remarks'=>'New Invoice Created',
                                        'date'=>date('Y-m-d'),
                                        'customer_id'=>$req->customer_id,
                                    ]);
                                DB::table('transfer_Invoice_Supplier')->where('id',$req->transfer_supplier_id)->update(['balance'=>$trans_sup_balance]);
                            }
                        }
                    }else{
                        $total_price = 0;
                        foreach($transfer_data  as $index => $trans_res_data){
                            if($index != 0){
                            $total_price += $trans_res_data->transportation_vehicle_total_price;
                            }
                        }
                      
                        $transfer_sup_data = DB::table('transfer_Invoice_Supplier')->where('id',$req->transfer_supplier_id)->select('id','balance')->first();
                        $trans_sup_balance = $transfer_sup_data->balance + $total_price;
                            
                            
                        DB::table('transfer_supplier_ledger')->insert([
                            'supplier_id'=>$req->transfer_supplier_id,
                            'payment'=> $total_price,
                            'balance'=> $trans_sup_balance,
                            'invoice_no'=>$invoice_id,
                            'remarks'=>'New Invoice Created',
                            'date'=>date('Y-m-d'),
                            'customer_id'=>$req->customer_id,
                        ]);
                        DB::table('transfer_Invoice_Supplier')->where('id',$req->transfer_supplier_id)->update(['balance'=>$trans_sup_balance]);
                    }
                }
            }
            
            $visa_details = json_decode($visa_details);
            // print_r($visa_details);
            
            // dd($visa_details);
            // die;
            
            if(isset($visa_details)){
                foreach($visa_details as $index => $visa_res){
                    
                  
                    
                    // 2 Update No of Seats Occupied in Visa
                        $visa_avail_data = DB::table('visa_Availability')->where('id',$visa_res->visa_avail_id)->first();
                        
                        $updated_seats = $visa_avail_data->visa_available - $visa_res->visa_occupied;
                        
                        DB::table('visa_Availability')->where('id',$visa_res->visa_avail_id)->update([
                                'visa_available' => $updated_seats
                            ]);
                      
                    // 3 Update Visa Supplier Balance
                        $visa_supplier_data = DB::table('visa_Sup')->where('id',$visa_avail_data->visa_supplier)->first();
                        
                        
                        $visa_supplier_payable_balance = $visa_supplier_data->payable + ($visa_res->visa_fee_purchase * $visa_res->visa_occupied);
                        $visa_total_sale = $visa_avail_data->visa_price * $visa_res->visa_occupied;
                        DB::table('visa_Sup')->where('id',$visa_avail_data->visa_supplier)->update([
                                'payable' => $visa_supplier_payable_balance
                        ]);
                        
                        DB::table('visa_supplier_ledger')->insert([
                                'supplier_id' => $visa_avail_data->visa_supplier,
                                'payment' => $visa_total_sale,
                                'balance' => $visa_supplier_data->balance,
                                'payable' => $visa_supplier_payable_balance,
                                'visa_qty' => $visa_res->visa_occupied,
                                'visa_type' => $visa_avail_data->visa_type,
                                'invoice_no' => $invoice_id,
                                'visa_avl_id' => $visa_avail_data->id,
                                'remarks' => 'New Invoice Create',
                                'date' => date('Y-m-d'),
                                'customer_id' => $req->customer_id,
                        ]);
                        
                        DB::table('visa_Sup')->where('id',$visa_avail_data->visa_supplier)->update([
                                'payable' => $visa_supplier_payable_balance
                        ]);
                    
                 
                }
            }
            
            DB::commit();
            return response()->json(['status'=>'success','invoice_id'=>$invoice_id,'message'=>'Agent Invoice added Succesfully']); 
        
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
        }
    }
    
    public function view_Invoices(Request $req){
        // $data1              = DB::table('add_manage_invoices')->where('customer_id',$req->customer_id)->orderBy('id', 'DESC')->get();
        // $all_countries      = country::all();
        // $customers_data     = DB::table('booking_customers')->where('customer_id',$req->customer_id)->get();
        // $Agents_detail      = DB::table('Agents_detail')->where('customer_id',$req->customer_id)->get();
        
        $data               = DB::table('add_manage_invoices')->orderBy('id', 'DESC')->get();;
        $all_countries      = country::all();
        $all_countries1     = country::all();
        $customers_data     = DB::table('booking_customers')->get();
        $Agents_detail      = DB::table('Agents_detail')->get();
        
        $all_Users          = DB::table('customer_subcriptions')->get();
        
        return view('template/frontend/userdashboard/pages/manage_Office/Invoices/view_Invoices',compact(['data','all_countries','all_countries1','Agents_detail','customers_data','all_Users']));
        
        // return response()->json([
        //     'data1'             => $data1,
        //     'all_countries'     => $all_countries,
        //     'customers_data'    => $customers_data,
        //     'Agents_detail'     => $Agents_detail,
        // ]); 
    }
    
    public function edit_Invoices(Request $req){
        $customer_id                    = $req->customer_id;
        $categories                     = DB::table('categories')->where('customer_id',$customer_id)->get();
        $attributes                     = DB::table('attributes')->where('customer_id',$customer_id)->get();
        $customer                       = DB::table('customer_subcriptions')->where('id','!=',$customer_id)->get();
        $all_countries                  = country::all();
        $all_countries_currency         = country::all();
        $bir_airports                   = DB::table('bir_airports')->get();
        $payment_gateways               = DB::table('payment_gateways')->where('customer_id',$customer_id)->get();
        $payment_modes                  = DB::table('payment_modes')->where('customer_id',$customer_id)->get();
        $currency_Symbol                = DB::table('customer_subcriptions')->where('id',$customer_id)->get();
        $Agents_detail                  = DB::table('Agents_detail')->where('customer_id',$req->customer_id)->get();
        $mange_currencies               = DB::table('mange_currencies')->where('customer_id',$req->customer_id)->get();
        $user_hotels                    = Hotels::where('owner_id',$customer_id)->get();
        $get_invoice                    = addManageInvoice::where('id',$req->id)->first();
        
        $visa_type                      = DB::table('visa_types')->where('customer_id',$customer_id)->get();
        
        $supplier_detail                = DB::table('rooms_Invoice_Supplier')->where('customer_id',$customer_id)->get();
        
        $tranfer_supplier               = DB::table('transfer_Invoice_Supplier')->where('customer_id',$customer_id)->get();
        // $destination_details            = DB::table('tranfer_destination')->where('customer_id',$customer_id)->get();
        $customers_data                 = DB::table('booking_customers')->where('customer_id',$customer_id)->get();
        
        $all_curr_symboles              = country::all();
        
        $all_flight_routes              = DB::table('flight_rute')->where('customer_id',$customer_id)->get();
        $flight_suppliers               = DB::table('supplier')->where('customer_id',$customer_id)->get();
        
        $visa_supplier                  = DB::table('visa_Sup')->where('customer_id',$customer_id)->get();
        $visa_types                     = DB::table('visa_types')->where('customer_id',$customer_id)->get();
        
        $tranfer_vehicle                = DB::table('tranfer_vehicle')->where('customer_id',$customer_id)->get();
        $tranfer_destination            = DB::table('tranfer_destination')->where('customer_id',$customer_id)->get();
        $tranfer_company                = DB::table('transfer_Invoice_Company')->where('customer_id',$customer_id)->get();

        return response()->json(['message'=>'success','tranfer_vehicle'=>$tranfer_vehicle,'tranfer_destination'=>$tranfer_destination,'tranfer_company'=>$tranfer_company,'visa_supplier'=>$visa_supplier,'visa_types'=>$visa_types,'all_flight_routes'=>$all_flight_routes,'flight_suppliers'=>$flight_suppliers,'all_curr_symboles'=>$all_curr_symboles,'tranfer_supplier'=>$tranfer_supplier,'supplier_detail'=>$supplier_detail,'visa_type'=>$visa_type,'get_invoice'=>$get_invoice,'mange_currencies'=>$mange_currencies,'user_hotels'=>$user_hotels,'Agents_detail'=>$Agents_detail,'currency_Symbol'=>$currency_Symbol,'customer'=>$customer,'attributes'=>$attributes,'categories'=>$categories,'all_countries'=>$all_countries,'all_countries_currency'=>$all_countries_currency,'bir_airports'=>$bir_airports,'payment_gateways'=>$payment_gateways,'payment_modes'=>$payment_modes,'customers_data'=>$customers_data]);
    }
    
    public function update_Invoices(Request $req){
        $id     = $req->id;
        $insert = addManageInvoice::find($id);
        
        function dateDiffInDays($date1, $date2){
            $diff = strtotime($date2) - strtotime($date1);
            return abs(round($diff / 86400));
        }
        
        function getBetweenDates($startDate, $endDate){
            $rangArray = [];
            $startDate = strtotime($startDate);
            $endDate = strtotime($endDate);
                 
                 $startDate += (86400); 
            for ($currentDate = $startDate; $currentDate <= $endDate; 
                                            $currentDate += (86400)) {
                                                    
                $date = date('Y-m-d', $currentDate);
                $rangArray[] = $date;
            }
      
            return $rangArray;
        }
        
        if($insert){
            
            $prev_acc = $insert->accomodation_details;
            $prev_acc_more = $insert->accomodation_details_more;
        
            $previous_agent = $insert->agent_Id;
            $new_agent = $req->agent_Id;
            
            // print_r($req->all());die;
            
            $previous_transfer_sup = $insert->transfer_supplier_id;
            $new_transfer_sup = $req->transfer_supplier_id;
            
            $previous_customer = $insert->booking_customer_id;
            $new_customer = $req->booking_customer_id;
            
            $previous_total_price = $insert->total_sale_price_all_Services;
            $new_total_price = $req->total_sale_price_all_Services;
            
            $prev_flight_pax = json_decode($insert->flights_Pax_details);
            $new_flight_pax = json_decode($req->flights_Pax_details);
            
            $prev_transfer_det = json_decode($insert->transportation_details);
            $new_transfer_det = json_decode($req->transportation_details);
            
            $accomodation_data = json_decode($req->accomodation_details);
            $accomodation_more_data = json_decode($req->more_accomodation_details);
            
            $prev_visa_all_details = json_decode($insert->all_visa_price_details);
            $new_visa_all_details = json_decode($req->all_visa_price_details);
            
            if(isset($accomodation_data)){
                foreach($accomodation_data as $index => $acc_res){
                    if($acc_res->room_select_type == 'true'){
                        
                        $room_type_data = json_decode($acc_res->new_rooms_type);
                         $Rooms = new Rooms;
                                    $Rooms->hotel_id =  $acc_res->hotel_id;
                                     $Rooms->rooms_on_rq= '';
                                    $Rooms->room_type_id =  $room_type_data->parent_cat; 
                                    $Rooms->room_type_name =  $room_type_data->room_type; 
                                    $Rooms->room_type_cat =  $room_type_data->id; 
                                  
                                   
                                    $Rooms->quantity =  $acc_res->acc_qty;  
                                    $Rooms->min_stay =  0; 
                                    $Rooms->max_child =  1; 
                                    $Rooms->max_adults =  $room_type_data->no_of_persons; 
                                    $Rooms->extra_beds =  0; 
                                    $Rooms->extra_beds_charges =  0; 
                                    $Rooms->availible_from =  $acc_res->acc_check_in; 
                                    $Rooms->availible_to =  $acc_res->acc_check_out; 
                                    $Rooms->room_option_date =  $acc_res->acc_check_in; 
                                    $Rooms->price_week_type =  'for_all_days'; 
                                    $Rooms->price_all_days =  $acc_res->price_per_room_purchase;
                                    $Rooms->room_supplier_name =  $acc_res->new_supplier_id;
                                    $Rooms->room_meal_type  = $acc_res->hotel_meal_type;
                                    // $Rooms->weekdays = serialize($request->weekdays);
                                    $Rooms->weekdays = null;
                                    $Rooms->weekdays_price =  NULL; 
                                    // $Rooms->weekends =  serialize($request->weekend); 
                                    $Rooms->weekends =  null;
                                    $Rooms->weekends_price =  NULL; 
                                    
                                  
                                    
                                    $user_id = $req->customer_id;
                                    $Rooms->owner_id = $user_id;
                                    
                                    
                                    $result = $Rooms->save();
                                    $Roomsid = $Rooms->id;
                                    
                                    $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$Rooms->room_supplier_name)->select('id','balance','payable')->first();
                       
                                        if(isset($supplier_data)){
                                            // echo "Enter hre ";
                                            
                                                 $week_days_total = 0;
                                                 $week_end_days_totals = 0;
                                                 $total_price = 0;
                                                if($Rooms->price_week_type == 'for_all_days'){
                                                    $avaiable_days = dateDiffInDays($Rooms->availible_from, $Rooms->availible_to);
                                                    $total_price = $Rooms->price_all_days * $avaiable_days;
                                                }else{
                                                     $avaiable_days = dateDiffInDays($Rooms->availible_from, $Rooms->availible_to);
                                                     
                                                     
                                                     $all_days = getBetweenDates($Rooms->availible_from, $Rooms->availible_to);
                                                     $week_days = json_decode($Rooms->weekdays);
                                                     $week_end_days = json_decode($Rooms->weekends);
                                                     
                                                    
                                                     foreach($all_days as $day_res){
                                                         $day = date('l', strtotime($day_res));
                                                         $day = trim($day);
                                                         $week_day_found = false;
                                                         $week_end_day_found = false;
                                                        
                                                         foreach($week_days as $week_day_res){
                                                             if($week_day_res == $day){
                                                                 $week_day_found = true;
                                                             }
                                                         }
                                                  
                                                         if($week_day_found){
                                                             $week_days_total += $Rooms->weekdays_price;
                                                         }else{
                                                             $week_end_days_totals += $Rooms->weekends_price;
                                                         }
                                                         
                                                         
                                                        //  foreach($week_end_days as $week_day_res){
                                                        //      if($week_day_res == $day){
                                                        //          $week_end_day_found = true;
                                                        //      }
                                                        //  }
                                                        //   if($week_end_day_found){
                                                              
                                                        //  }
                                                     }
                                                     
                                                     
                                                      
                                                     
                                                    //  print_r($all_days);
                                                     $total_price = $week_days_total + $week_end_days_totals;
                                                }
                                                
                                                
                                            $all_days_price = $total_price * $Rooms->quantity;
                                            $supplier_balance = $supplier_data->balance + $all_days_price;
                                            
                                            // update Agent Balance
                                            
                                            DB::table('hotel_supplier_ledger')->insert([
                                                'supplier_id'=>$supplier_data->id,
                                                'payment'=>$all_days_price,
                                                'balance'=>$supplier_balance,
                                                'payable_balance'=>$supplier_data->payable,
                                                'room_id'=>$Roomsid,
                                                'customer_id'=>$user_id,
                                                'date'=>date('Y-m-d'),
                                                'available_from'=>$Rooms->availible_from,
                                                'available_to'=>$Rooms->availible_to,
                                                'room_quantity'=>$Rooms->quantity,
                                                ]);
                                                
                                            DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                                            
                                            
                                              
                                                                        
                                        }
                                        
                                    $accomodation_data[$index]->acc_type = $room_type_data->parent_cat;
                                    $accomodation_data[$index]->hotel_supplier_id = $acc_res->new_supplier_id;
                                    $accomodation_data[$index]->hotel_type_id = $room_type_data->id;
                                    $accomodation_data[$index]->hotel_type_cat = $room_type_data->room_type;
                                    $accomodation_data[$index]->hotelRoom_type_id = $Roomsid;
                    }
                }
            }
       
            if(isset($accomodation_more_data)){
                foreach($accomodation_more_data as $index => $acc_res){
                    if($acc_res->more_room_select_type == 'true'){
                        
                        $room_type_data = json_decode($acc_res->more_new_rooms_type);
                         $Rooms = new Rooms;
                                    $Rooms->hotel_id =  $acc_res->more_hotel_id;
                                     $Rooms->rooms_on_rq= '';
                                    $Rooms->room_type_id =  $room_type_data->parent_cat; 
                                    $Rooms->room_type_name =  $room_type_data->room_type; 
                                    $Rooms->room_type_cat =  $room_type_data->id; 
                                  
                                   
                                    $Rooms->quantity =  $acc_res->more_acc_qty;  
                                    $Rooms->min_stay =  0; 
                                    $Rooms->max_child =  1; 
                                    $Rooms->max_adults =  $room_type_data->no_of_persons; 
                                    $Rooms->extra_beds =  0; 
                                    $Rooms->extra_beds_charges =  0; 
                                    $Rooms->availible_from =  $acc_res->more_acc_check_in; 
                                    $Rooms->availible_to =  $acc_res->more_acc_check_out; 
                                    $Rooms->room_option_date =  $acc_res->more_acc_check_in; 
                                    $Rooms->price_week_type =  'for_all_days'; 
                                    $Rooms->price_all_days =  $acc_res->more_price_per_room_purchase;
                                    $Rooms->room_supplier_name =  $acc_res->more_new_supplier_id;
                                    $Rooms->room_meal_type  = $acc_res->more_hotel_meal_type;
                                    // $Rooms->weekdays = serialize($request->weekdays);
                                    $Rooms->weekdays = null;
                                    $Rooms->weekdays_price =  NULL; 
                                    // $Rooms->weekends =  serialize($request->weekend); 
                                    $Rooms->weekends =  null;
                                    $Rooms->weekends_price =  NULL; 
                                    
                                  
                                    
                                    $user_id = $req->customer_id;
                                    $Rooms->owner_id = $user_id;
                                    
                                    
                                    $result = $Rooms->save();
                                    $Roomsid = $Rooms->id;
                                    
                                    $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$Rooms->room_supplier_name)->select('id','balance','payable')->first();
                       
                                        if(isset($supplier_data)){
                                            // echo "Enter hre ";
                                            
                                                 $week_days_total = 0;
                                                 $week_end_days_totals = 0;
                                                 $total_price = 0;
                                                if($Rooms->price_week_type == 'for_all_days'){
                                                    $avaiable_days = dateDiffInDays($Rooms->availible_from, $Rooms->availible_to);
                                                    $total_price = $Rooms->price_all_days * $avaiable_days;
                                                }
                                                
                                                
                                            $all_days_price = $total_price * $Rooms->quantity;
                                            $supplier_balance = $supplier_data->balance + $all_days_price;
                                            
                                            // update Agent Balance
                                            
                                            DB::table('hotel_supplier_ledger')->insert([
                                                'supplier_id'=>$supplier_data->id,
                                                'payment'=>$all_days_price,
                                                'balance'=>$supplier_balance,
                                                'payable_balance'=>$supplier_data->payable,
                                                'room_id'=>$Roomsid,
                                                'customer_id'=>$user_id,
                                                'date'=>date('Y-m-d'),
                                                'available_from'=>$Rooms->availible_from,
                                                'available_to'=>$Rooms->availible_to,
                                                'room_quantity'=>$Rooms->quantity,
                                                ]);
                                                
                                            DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                                            
                                            
                                              
                                                                        
                                        }
                                        
                                    $accomodation_more_data[$index]->more_acc_type = $room_type_data->parent_cat;
                                    $accomodation_more_data[$index]->more_hotel_supplier_id = $acc_res->more_new_supplier_id;
                                    $accomodation_more_data[$index]->more_hotel_type_id = $room_type_data->id;
                                    $accomodation_more_data[$index]->more_hotel_type_cat = $room_type_data->room_type;
                                    $accomodation_more_data[$index]->more_hotelRoom_type_id = $Roomsid;
                    }
                    
                }
            }
            
             $visa_details = json_decode($req->all_visa_price_details);
            // print_r($visa_details);
            // die;
            
            if(isset($visa_details)){
                foreach($visa_details as $index => $visa_res){
                    
                    // 1 Check Add New Visa or Exists Use
                    if($visa_res->visa_add_type_new !== 'false'){
                        // Add As New
                        
                        $visa_avail_id = DB::table('visa_Availability')->insertGetId([
                                'visa_supplier' => $visa_res->visa_supplier_id,
                                'visa_type' => $visa_res->visa_type_id,
                                'visa_qty' => $visa_res->visa_occupied,
                                'visa_available' => $visa_res->visa_occupied,
                                'visa_price' => $visa_res->visa_fee_purchase,
                                'availability_from' => $visa_res->visa_av_from,
                                'availability_to' => $visa_res->visa_av_to,
                                'country' => $visa_res->visa_country_id,
                                'currency_conversion' => $req->conversion_type_Id,
                                'visa_price_conversion_rate' => $visa_res->exchange_rate_visa,
                                'visa_converted_price'=> $visa_res->visa_fee,
                                'customer_id' => $req->customer_id,
                        ]);
                        
                           $visa_details[$index]->visa_avail_id = $visa_avail_id;
                           $supplier_data = DB::table('visa_Sup')->where('id',$visa_res->visa_supplier_id)->first();
        
                            if(isset($supplier_data)){
                                $supplier_balance = $supplier_data->balance + $visa_res->visa_purchase_total;
                                
                                DB::table('visa_supplier_ledger')->insert([
                                        'supplier_id' => $visa_res->visa_supplier_id,
                                        'payment' => $visa_res->visa_purchase_total,
                                        'balance' => $supplier_balance,
                                        'payable' => $supplier_data->payable,
                                        'visa_qty' => $visa_res->visa_occupied,
                                        'visa_type' => $visa_res->visa_type_id,
                                        'visa_price' => $visa_res->visa_fee_purchase,
                                        'visa_avl_id'=> $visa_avail_id,
                                        'date'=> date('Y-m-d'),
                                        'remarks'=> 'New Visa Purchased',
                                        'customer_id'=> $req->customer_id,
                                    ]);
                                
                                $data=DB::table('visa_Sup')->where('id',$visa_res->visa_supplier_id)->update([  
                                   'balance'               => $supplier_balance,
                                ]);
                            }
                    }
                    
                   
                    
                 
                }
            }
            $new_visa_all_details = $visa_details;
            $visa_details = json_encode($visa_details);
            
            $req->accomodation_details = json_encode($accomodation_data);
            $req->more_accomodation_details = json_encode($accomodation_more_data);
            // print_r($insert);
            //1
            $insert->customer_id             = $req->customer_id;
            $insert->services                = $req->services;
            $insert->booking_customer_id = $req->booking_customer_id;
            $insert->agent_Id                = $req->agent_Id;
            $insert->agent_Name              = $req->agent_Name;
            $insert->agent_Company_Name     = $req->agent_Company_Name;
            $insert->prefix                  = $req->prefix;
            $insert->f_name                  = $req->f_name;
            $insert->middle_name             = $req->middle_name;
            $insert->currency_conversion     = $req->currency_conversion;
            $insert->conversion_type_Id      = $req->conversion_type_Id;
             
            $insert->gender                  = $req->gender;
            $insert->email                   = $req->email;
            $insert->contact_landline        = $req->contact_landline;
            $insert->mobile                  = $req->mobile;
            $insert->contact_work            = $req->contact_work;
            $insert->postCode                = $req->postCode;
            $insert->country                 = $req->country;
            $insert->city                    = $req->city;
            $insert->primery_address         = $req->primery_address;
            // $insert->quotation_prepared      = $req->quotation_prepared;
            // $insert->quotation_valid_date    = $req->quotation_valid_date;
            //2
            $insert->adults                  = $req->adults;
            $insert->childs                  = $req->childs;
            $insert->infant                  = $req->infant;
            // Package_Data
            $generate_id                            = rand(0,9999999);
            $insert->generate_id                    = $generate_id;
            $insert->title                          = $req->title;
            $insert->external_packages              = '';
            $insert->content                        = $req->content;
            $insert->categories                     = $req->tour_categories;
            $insert->tour_attributes                = $req->tour_attributes;
            $insert->no_of_pax_days                 = $req->no_of_pax_days;
            $insert->destination_details            = $req->destination_details;
            $insert->destination_details_more       = $req->destination_details_more;
            
            $insert->flight_route_type              = $req->flight_route_type;
            $insert->flight_supplier                = $req->flight_supplier;
            $insert->flights_details                = $req->flights_details;
            $insert->return_flights_details         = $req->return_flights_details;
            $insert->flights_Pax_details            = $req->flights_Pax_details;
            
            $insert->accomodation_details           = $req->accomodation_details;
            $insert->accomodation_details_more      = $req->more_accomodation_details;
            $insert->transportation_details         = $req->transportation_details;
            $insert->transportation_details_more    = $req->transportation_details_more;
            
            $insert->visa_type                      = $req->visa_type;
            $insert->visa_fee                       = $req->visa_fee;
            $insert->visa_Pax                       = $req->visa_Pax;
            $insert->exchange_rate_visaI            = $req->exchange_rate_visaI;
            $insert->total_visa_markup_value        = $req->total_visa_markup_value;
            $insert->exchange_rate_visa_fee         = $req->exchange_rate_visa_fee;
            
            $insert->more_visa_details              = $req->more_visa_details;
            $insert->all_visa_price_details         = $visa_details;
            $insert->visa_rules_regulations         = $req->visa_rules_regulations;
            $insert->visa_image                     = $req->visa_image;
            
            $insert->without_markup_total_price_visa    = $req->without_markup_total_price_visa;
            $insert->markup_total_price_visa            = $req->markup_total_price_visa;
            
            $insert->quad_grand_total_amount        = $req->quad_grand_total_amount;
            $insert->triple_grand_total_amount      = $req->triple_grand_total_amount;
            $insert->double_grand_total_amount      = $req->double_grand_total_amount;
            $insert->quad_cost_price                = $req->quad_cost_price;
            $insert->triple_cost_price              = $req->triple_cost_price;
            $insert->double_cost_price              = $req->double_cost_price;
            $insert->all_markup_type                = $req->all_markup_type;
            $insert->all_markup_add                 = $req->all_markup_add;
            $insert->gallery_images                 = $req->gallery_images;
            $insert->start_date                     = $req->start_date;
            $insert->end_date=$req->end_date;
            $insert->time_duration=$req->time_duration;
            $insert->tour_location=$req->tour_location;
            $insert->whats_included=$req->whats_included;
            $insert->whats_excluded=$req->whats_excluded;
            $insert->currency_symbol=$req->currency_symbol;
            $insert->tour_publish=$req->defalut_state;
            $insert->tour_author=$req->tour_author;
            $insert->tour_feature=$req->tour_feature;
            $insert->defalut_state=$req->defalut_state;
            $insert->payment_gateways=$req->payment_gateways;
            $insert->payment_modes=$req->payment_modes;
            $insert->markup_details=$req->markup_details;
            $insert->more_markup_details=$req->more_markup_details;
            $insert->tour_featured_image=$req->tour_featured_image;
            $insert->tour_banner_image=$req->tour_banner_image;
            $insert->Itinerary_details=$req->Itinerary_details;
            $insert->tour_itinerary_details_1=$req->tour_itinerary_details_1;
            $insert->tour_extra_price=$req->tour_extra_price;
            $insert->tour_extra_price_1=$req->tour_extra_price_1;
            $insert->tour_faq=$req->tour_faq;
            $insert->tour_faq_1=$req->tour_faq_1;
            
            $insert->payment_messag=$req->checkout_message;
            
            $insert->total_Cost_Price_All=$req->total_Cost_Price_All;
            $insert->total_Sale_Price_All=$req->total_Sale_Price_All;
            
            $insert->option_date=$req->option_date;
            $insert->reservation_number=$req->reservation_number;
            $insert->hotel_reservation_number=$req->hotel_reservation_number;
            
            $insert->total_sale_price_all_Services=$req->total_sale_price_all_Services;
            $insert->total_cost_price_all_Services=$req->total_cost_price_all_Services;
            
            $insert->without_discount_total_sale_amount = $req->total_sale_price_all_Services;
            
            $insert->transfer_supplier      = $req->transfer_supplier;
            $insert->transfer_supplier_id   = $req->transfer_supplier_id;
            
            DB::beginTransaction();
        
            try {
                    $insert->update();
                    
                    $invoice_id = $insert->id;
                    
                    $prev_acc = json_decode($prev_acc);
                    $prev_acc_more = json_decode($prev_acc_more);
                    
                    $new_acc = json_decode($req->accomodation_details);
                    $new_acc_more = json_decode($req->more_accomodation_details);
                    
                    $flights_Pax_details = json_decode($req->flights_Pax_details);
                    if(isset($flights_Pax_details) && $flights_Pax_details != null && $flights_Pax_details != ''){
                        
                        $occupied_against_id = DB::table('flight_seats_occupied')->where('booking_id',$id)->get();
                        if(isset($occupied_against_id) && $occupied_against_id != null && $occupied_against_id != ''){
                            foreach($occupied_against_id as $occupied_against_idS){
                                DB::table('flight_seats_occupied')->where('id',$occupied_against_idS->id)->delete();
                            }
                        }
                        
                        foreach($flights_Pax_details as $value){
                            $check_id = DB::table('flight_seats_occupied')->where('booking_id',$id)->where('flight_route_id',$value->flight_route_id_occupied)->first();
                            if(isset($check_id) && $check_id != null && $check_id != ''){
                                DB::table('flight_seats_occupied')->insert([
                                    'token'                         => $req->token,
                                    'type'                          => 'Invoice',
                                    'booking_id'                    => $invoice_id,
                                    'flight_supplier'               => $req->flight_supplier,
                                    'flight_route_id'               => $value->flight_route_id_occupied,
                                    'flights_adult_seats'           => $value->flights_adult_seats,
                                    'flights_child_seats'           => $value->flights_child_seats,
                                    'flight_route_seats_occupied'   => $value->flights_adult_seats + $value->flights_child_seats,
                                ]);
                            }else{
                                DB::table('flight_seats_occupied')->insert([
                                    'token'                         => $req->token,
                                    'type'                          => 'Invoice',
                                    'booking_id'                    => $invoice_id,
                                    'flight_supplier'               => $req->flight_supplier,
                                    'flight_route_id'               => $value->flight_route_id_occupied,
                                    'flights_adult_seats'           => $value->flights_adult_seats,
                                    'flights_child_seats'           => $value->flights_child_seats,
                                    'flight_route_seats_occupied'   => $value->flights_adult_seats + $value->flights_child_seats,
                                ]);
                            }
                        }
                    }
                    
                    // Agent Data Updated
                    if($previous_agent != $new_agent){
                         
                         // Agent is Changed
                         // previous Agent Working
                   
                              
                              
                                $agent_data = DB::table('Agents_detail')->where('id',$previous_agent)->select('id','balance')->first();
       
                                if(isset($agent_data)){
                                    // echo "Enter hre ";
                                    $received_amount = -1 * abs($previous_total_price);
                                    $agent_balance = $agent_data->balance - $previous_total_price;
                                    
                                    // update Agent Balance
                                    DB::table('Agents_detail')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                                    DB::table('agents_ledgers_new')->insert([
                                        'agent_id'=>$agent_data->id,
                                        'received'=>$received_amount,
                                        'balance'=>$agent_balance,
                                        'invoice_no'=>$insert->id,
                                        'customer_id'=>$insert->customer_id,
                                        'date'=>date('Y-m-d'),
                                        'remarks'=>'Invoice Updated',
                                        ]);
                                }
                        
                         
                         
                         // New Agent Working
                    
                             
                              
                                $agent_data = DB::table('Agents_detail')->where('id',$new_agent)->select('id','balance')->first();
       
                                if(isset($agent_data)){
                                    // echo "Enter hre ";
                                    $received_amount = $new_total_price;
                                    $agent_balance = $agent_data->balance + $new_total_price;
                                    
                                    // update Agent Balance
                                    DB::table('Agents_detail')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                                    DB::table('agents_ledgers_new')->insert([
                                        'agent_id'=>$agent_data->id,
                                        'received'=>$received_amount,
                                        'balance'=>$agent_balance,
                                        'invoice_no'=>$insert->id,
                                        'customer_id'=>$insert->customer_id,
                                        'date'=>date('Y-m-d'),
                                        'remarks'=>'Invoice Updated',
                                        ]);
                                }
                        
                         
                         
                    }else{
                         // Agent is Not Changed
                         
                              $difference  = $new_total_price - $previous_total_price;
                              
                            //   echo "Differ is $difference ";
                              
                                $agent_data = DB::table('Agents_detail')->where('id',$new_agent)->select('id','balance')->first();
       
                                if(isset($agent_data)){
                                    // echo "Enter hre ";
                                    $agent_balance = $agent_data->balance + $difference;
                                    
                                    // update Agent Balance
                                    DB::table('Agents_detail')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                                    DB::table('agents_ledgers_new')->insert([
                                        'agent_id'=>$agent_data->id,
                                        'received'=>$difference,
                                        'balance'=>$agent_balance,
                                        'invoice_no'=>$insert->id,
                                        'customer_id'=> $insert->customer_id,
                                        'date'=>date('Y-m-d'),
                                        'remarks'=>'Invoice Updated',
                                        ]);
                                }
                        
                         
                     }
                    
                    // Customer Data Updated
                    if($previous_customer != $new_customer){
                         
                         // Agent is Changed
                         // previous Agent Working
                   
                              
                              
                                $customer_data = DB::table('booking_customers')->where('id',$previous_customer)->select('id','balance')->first();
       
                                if(isset($customer_data)){
                                    // echo "Enter hre ";
                                    $received_amount = -1 * abs($previous_total_price);
                                    $customer_balance = $customer_data->balance - $previous_total_price;
                                    
                                    // update Agent Balance
                                    
                                    DB::table('customer_ledger')->insert([
                                        'booking_customer'=>$customer_data->id,
                                        'received'=>$received_amount,
                                        'balance'=>$customer_balance,
                                        'invoice_no'=>$insert->id,
                                        'customer_id'=>$insert->customer_id,
                                        'date'=>date('Y-m-d'),
                                        'remarks'=>'Invoice Updated',
                                        ]);
                                        
                                    DB::table('booking_customers')->where('id',$customer_data->id)->update(['balance'=>$customer_balance]);
                                }
                        
                         
                         
                         // New Agent Working
                    
                             
                              
                                $customer_data = DB::table('booking_customers')->where('id',$new_customer)->select('id','balance')->first();
       
                                if(isset($customer_data)){
                                    // echo "Enter hre ";
                                    $received_amount = $new_total_price;
                                    $customer_balance = $customer_data->balance + $new_total_price;
                                    
                                    // update Agent Balance
                                    
                                    DB::table('customer_ledger')->insert([
                                        'booking_customer'=>$customer_data->id,
                                        'received'=>$received_amount,
                                        'balance'=>$customer_balance,
                                        'invoice_no'=>$insert->id,
                                        'customer_id'=>$insert->customer_id,
                                        'date'=>date('Y-m-d'),
                                        'remarks'=>'Invoice Updated',
                                        ]);
                                        
                                    DB::table('booking_customers')->where('id',$customer_data->id)->update(['balance'=>$customer_balance]);
                                }
                        
                         
                         
                    }else{
                         // Agent is Not Changed
                         
                              $difference  = $new_total_price - $previous_total_price;
                              
                            //   echo "Differ is $difference ";
                              
                                $customer_data = DB::table('booking_customers')->where('id',$new_customer)->select('id','balance')->first();
       
                                if(isset($customer_data)){
                                    // echo "Enter hre ";
                                    $customer_balance = $customer_data->balance + $difference;
                                    
                                    // update Agent Balance
                                    DB::table('booking_customers')->where('id',$customer_data->id)->update(['balance'=>$customer_balance]);
                                    DB::table('customer_ledger')->insert([
                                        'booking_customer'=>$customer_data->id,
                                        'received'=>$difference,
                                        'balance'=>$customer_balance,
                                        'invoice_no'=>$insert->id,
                                        'customer_id'=> $insert->customer_id,
                                        'date'=>date('Y-m-d'),
                                        'remarks'=>'Invoice Updated',
                                        ]);
                                }
                        
                         
                     }
                    
                    // Loop on Previous Accomodations 
                    DB::table('rooms_bookings_details')->where('booking_id', $insert->id)->where('booking_from','Invoices')->delete();
                    
                    // Previous Element Found Working
                    $arr_ele_found = [];
                    if(isset($prev_acc) && !empty($prev_acc)){
                        foreach($prev_acc as $prev_acc_res){
                            
                            if(isset($prev_acc_res->hotelRoom_type_id) AND !empty($prev_acc_res->hotelRoom_type_id)){
                                // echo $prev_acc_res->hotelRoom_type_id;
                                
                                $found = false;
                                foreach($arr_ele_found as $arr_id_res){
                                    if($arr_id_res == $prev_acc_res->hotelRoom_type_id){
                                        $found = true;
                                    }
                                }
                                
                                if(!$found){
                                    $perv_total = 0;
                                    $rooms_total_pr_prev = 0;
                                    foreach($prev_acc as $cal_total_prev){
                                        if($prev_acc_res->hotelRoom_type_id == $cal_total_prev->hotelRoom_type_id){
                                            $perv_total += $cal_total_prev->acc_qty;
                                            
                                            // Calaculate Room Prices
                                             $room_data = DB::table('rooms')->where('id',$cal_total_prev->hotelRoom_type_id)->first();
                            
                                                if($room_data){
                                                         $week_days_total = 0;
                                                         $week_end_days_totals = 0;
                                                         $total_price = 0;
                                                         $acc_check_in = date('Y-m-d',strtotime($cal_total_prev->acc_check_in));
                                                         $acc_check_out = date('Y-m-d',strtotime($cal_total_prev->acc_check_out));
                                                        if($room_data->price_week_type == 'for_all_days'){
                                                            $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                            $total_price = $room_data->price_all_days * $avaiable_days;
                                                        }else{
                                                             $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                             
                                                             $all_days = getBetweenDates($acc_check_in, $acc_check_out);
                                                             
                                                            //  print_r($all_days);
                                                             $week_days = json_decode($room_data->weekdays);
                                                             $week_end_days = json_decode($room_data->weekends);
                                                             
                                                             foreach($all_days as $day_res){
                                                                 $day = date('l', strtotime($day_res));
                                                                 $day = trim($day);
                                                                 $week_day_found = false;
                                                                 $week_end_day_found = false;
                                                                
                                                                 foreach($week_days as $week_day_res){
                                                                     if($week_day_res == $day){
                                                                         $week_day_found = true;
                                                                     }
                                                                 }
                                                          
                                                                // echo "  ".$room_data->weekdays_price;
                                                                 if($week_day_found){
                                                                     $week_days_total += $room_data->weekdays_price;
                                                                 }else{
                                                                     $week_end_days_totals += $room_data->weekends_price;
                                                                 }
                                                                 
                                                                 
                                                                //  foreach($week_end_days as $week_day_res){
                                                                //      if($week_day_res == $day){
                                                                //          $week_end_day_found = true;
                                                                //      }
                                                                //  }
                                                                //   if($week_end_day_found){
                                                                      
                                                                //  }
                                                             }
                                                             
                                                             
                                                            //   echo "Week Days price Total $week_days_total and Weekend price Total $week_end_days_totals";
                                                             
                                                            //  print_r($all_days);
                                                             $total_price = $week_days_total + $week_end_days_totals;
                                                        }
                                                        
                                                        
                                                        $all_days_price = $total_price * $cal_total_prev->acc_qty;
                                                        $rooms_total_pr_prev += $all_days_price;
                                                }
                                                    
                                                    
                                                      
                                             
                                            
                                            
                                            
                                            
                                        }
                                    }
                                    
                                    
                                    if(isset($prev_acc_more) && !empty($prev_acc_more)){
                                        foreach($prev_acc_more as $cal_total_prev){
                                            if($prev_acc_res->hotelRoom_type_id == $cal_total_prev->more_hotelRoom_type_id){
                                                $perv_total += $cal_total_prev->more_acc_qty;
                                                
                                                // Calaculate Room Prices
                                           $room_data = DB::table('rooms')->where('id',$cal_total_prev->more_hotelRoom_type_id)->first();
                                
                                                    if($room_data){
                                                             $week_days_total = 0;
                                                             $week_end_days_totals = 0;
                                                             $total_price = 0;
                                                             $acc_check_in = date('Y-m-d',strtotime($cal_total_prev->more_acc_check_in));
                                                             $acc_check_out = date('Y-m-d',strtotime($cal_total_prev->more_acc_check_out));
                                                            if($room_data->price_week_type == 'for_all_days'){
                                                                $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                                $total_price = $room_data->price_all_days * $avaiable_days;
                                                            }else{
                                                                 $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                                 
                                                                 $all_days = getBetweenDates($acc_check_in, $acc_check_out);
                                                                 
                                                                //  print_r($all_days);
                                                                 $week_days = json_decode($room_data->weekdays);
                                                                 $week_end_days = json_decode($room_data->weekends);
                                                                 
                                                                 foreach($all_days as $day_res){
                                                                     $day = date('l', strtotime($day_res));
                                                                     $day = trim($day);
                                                                     $week_day_found = false;
                                                                     $week_end_day_found = false;
                                                                    
                                                                     foreach($week_days as $week_day_res){
                                                                         if($week_day_res == $day){
                                                                             $week_day_found = true;
                                                                         }
                                                                     }
                                                              
                                                                    // echo "  ".$room_data->weekdays_price;
                                                                     if($week_day_found){
                                                                         $week_days_total += $room_data->weekdays_price;
                                                                     }else{
                                                                         $week_end_days_totals += $room_data->weekends_price;
                                                                     }
                                                                     
                                                                     
                                                                    //  foreach($week_end_days as $week_day_res){
                                                                    //      if($week_day_res == $day){
                                                                    //          $week_end_day_found = true;
                                                                    //      }
                                                                    //  }
                                                                    //   if($week_end_day_found){
                                                                          
                                                                    //  }
                                                                 }
                                                                 
                                                                 
                                                                //   echo "Week Days price Total $week_days_total and Weekend price Total $week_end_days_totals";
                                                                 
                                                                //  print_r($all_days);
                                                                 $total_price = $week_days_total + $week_end_days_totals;
                                                            }
                                                            
                                                            
                                                            $all_days_price = $total_price * $cal_total_prev->more_acc_qty;
                                                            $rooms_total_pr_prev += $all_days_price;
                                                    }
                                            }
                                        }
                                    }
                                    
                                    
                                    
                                   
                                    $new_total = 0;
                                    $rooms_total_pr_new = 0;
                                    foreach($new_acc as $cal_total_prev){
                                        if($prev_acc_res->hotelRoom_type_id == $cal_total_prev->hotelRoom_type_id){
                                            $new_total += $cal_total_prev->acc_qty;
                                            
                                            // Calaculate Room Prices
                                             $room_data = DB::table('rooms')->where('id',$cal_total_prev->hotelRoom_type_id)->first();
                            
                                                if($room_data){
                                                         $week_days_total = 0;
                                                         $week_end_days_totals = 0;
                                                         $total_price = 0;
                                                         $acc_check_in = date('Y-m-d',strtotime($cal_total_prev->acc_check_in));
                                                         $acc_check_out = date('Y-m-d',strtotime($cal_total_prev->acc_check_out));
                                                        if($room_data->price_week_type == 'for_all_days'){
                                                            $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                            $total_price = $room_data->price_all_days * $avaiable_days;
                                                        }else{
                                                             $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                             
                                                             $all_days = getBetweenDates($acc_check_in, $acc_check_out);
                                                             
                                                            //  print_r($all_days);
                                                             $week_days = json_decode($room_data->weekdays);
                                                             $week_end_days = json_decode($room_data->weekends);
                                                             
                                                             foreach($all_days as $day_res){
                                                                 $day = date('l', strtotime($day_res));
                                                                 $day = trim($day);
                                                                 $week_day_found = false;
                                                                 $week_end_day_found = false;
                                                                
                                                                 foreach($week_days as $week_day_res){
                                                                     if($week_day_res == $day){
                                                                         $week_day_found = true;
                                                                     }
                                                                 }
                                                          
                                                                // echo "  ".$room_data->weekdays_price;
                                                                 if($week_day_found){
                                                                     $week_days_total += $room_data->weekdays_price;
                                                                 }else{
                                                                     $week_end_days_totals += $room_data->weekends_price;
                                                                 }
                                                                 
                                                                 
                                                                //  foreach($week_end_days as $week_day_res){
                                                                //      if($week_day_res == $day){
                                                                //          $week_end_day_found = true;
                                                                //      }
                                                                //  }
                                                                //   if($week_end_day_found){
                                                                      
                                                                //  }
                                                             }
                                                             
                                                             
                                                            //   echo "Week Days price Total $week_days_total and Weekend price Total $week_end_days_totals";
                                                             
                                                            //  print_r($all_days);
                                                             $total_price = $week_days_total + $week_end_days_totals;
                                                        }
                                                        
                                                        
                                                        $all_days_price = $total_price * $cal_total_prev->acc_qty;
                                                        $rooms_total_pr_new += $all_days_price;
                                                }
                                            
                                            DB::table('rooms_bookings_details')->insert([
                                                    'room_id'=> $cal_total_prev->hotelRoom_type_id,
                                                    'booking_from'=> 'Invoices',
                                                    'quantity'=> $cal_total_prev->acc_qty,
                                                    'booking_id'=> $insert->id,
                                                    'date'=> date('Y-m-d',strtotime($insert->created_at)),
                                                    'check_in'=> $cal_total_prev->acc_check_in,
                                                    'check_out'=> $cal_total_prev->acc_check_out,
                                                ]);
                                            
                                        }
                                    }
                                    
                                    
                                    if(isset($new_acc_more) && !empty($new_acc_more)){
                                        foreach($new_acc_more as $cal_total_prev){
                                            if($prev_acc_res->hotelRoom_type_id == $cal_total_prev->more_hotelRoom_type_id){
                                                $new_total += $cal_total_prev->more_acc_qty;
                                                
                                                  // Calaculate Room Prices
                                                 $room_data = DB::table('rooms')->where('id',$cal_total_prev->more_hotelRoom_type_id)->first();
                                
                                                    if($room_data){
                                                             $week_days_total = 0;
                                                             $week_end_days_totals = 0;
                                                             $total_price = 0;
                                                             $acc_check_in = date('Y-m-d',strtotime($cal_total_prev->more_acc_check_in));
                                                             $acc_check_out = date('Y-m-d',strtotime($cal_total_prev->more_acc_check_out));
                                                            if($room_data->price_week_type == 'for_all_days'){
                                                                $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                                $total_price = $room_data->price_all_days * $avaiable_days;
                                                            }else{
                                                                 $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                                 
                                                                 $all_days = getBetweenDates($acc_check_in, $acc_check_out);
                                                                 
                                                                //  print_r($all_days);
                                                                 $week_days = json_decode($room_data->weekdays);
                                                                 $week_end_days = json_decode($room_data->weekends);
                                                                 
                                                                 foreach($all_days as $day_res){
                                                                     $day = date('l', strtotime($day_res));
                                                                     $day = trim($day);
                                                                     $week_day_found = false;
                                                                     $week_end_day_found = false;
                                                                    
                                                                     foreach($week_days as $week_day_res){
                                                                         if($week_day_res == $day){
                                                                             $week_day_found = true;
                                                                         }
                                                                     }
                                                              
                                                                    // echo "  ".$room_data->weekdays_price;
                                                                     if($week_day_found){
                                                                         $week_days_total += $room_data->weekdays_price;
                                                                     }else{
                                                                         $week_end_days_totals += $room_data->weekends_price;
                                                                     }
                                                                     
                                                                     
                                                                    //  foreach($week_end_days as $week_day_res){
                                                                    //      if($week_day_res == $day){
                                                                    //          $week_end_day_found = true;
                                                                    //      }
                                                                    //  }
                                                                    //   if($week_end_day_found){
                                                                          
                                                                    //  }
                                                                 }
                                                                 
                                                                 
                                                                //   echo "Week Days price Total $week_days_total and Weekend price Total $week_end_days_totals";
                                                                 
                                                                //  print_r($all_days);
                                                                 $total_price = $week_days_total + $week_end_days_totals;
                                                            }
                                                            
                                                            
                                                            $all_days_price = $total_price * $cal_total_prev->more_acc_qty;
                                                            $rooms_total_pr_new += $all_days_price;
                                                    }
                                                
                                                DB::table('rooms_bookings_details')->insert([
                                                        'room_id'=> $cal_total_prev->more_hotelRoom_type_id,
                                                        'booking_from'=> 'Invoices',
                                                        'quantity'=> $cal_total_prev->more_acc_qty,
                                                        'booking_id'=> $insert->id,
                                                        'date'=> date('Y-m-d',strtotime($insert->created_at)),
                                                        'check_in'=> $cal_total_prev->more_acc_check_in,
                                                        'check_out'=> $cal_total_prev->more_acc_check_out,
                                                    ]);
                                            }
                                        }
                                    }
                                    
                                    array_push($arr_ele_found,$prev_acc_res->hotelRoom_type_id);
                                }
                                
                                $room_data = DB::table('rooms')->where('id',$prev_acc_res->hotelRoom_type_id)->first();
                                 
                                $difference = $new_total - $perv_total;
                                $Price_difference = $rooms_total_pr_new - $rooms_total_pr_prev;
                                
                                $update_booked = $room_data->booked + $difference;
                                
                                $room_data = DB::table('rooms')->where('id',$prev_acc_res->hotelRoom_type_id)->update(['booked'=>$update_booked]);
                                    
                                $room_data = DB::table('rooms')->where('id',$prev_acc_res->hotelRoom_type_id)->first();
                                
                                $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
        
                                if(isset($supplier_data)){

                                    $supplier_balance = $supplier_data->balance;
                                        $supplier_payable_balance = $supplier_data->payable + $Price_difference;
                                        
                                        // update Agent Balance
                                        
                                        DB::table('hotel_supplier_ledger')->insert([
                                            'supplier_id'=>$supplier_data->id,
                                            'payment'=>$Price_difference,
                                            'balance'=>$supplier_balance,
                                            'payable_balance'=>$supplier_payable_balance,
                                            'room_id'=>$room_data->id,
                                            'customer_id'=>$insert->customer_id,
                                            'date'=>date('Y-m-d'),
                                            'invoice_no'=>$insert->id,
                                            'available_from'=>'',
                                            'available_to'=>'',
                                            'remarks'=>'Invoice Updated',
                                            ]);
                                            
                                        DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                                            'balance'=>$supplier_balance,
                                            'payable'=>$supplier_payable_balance
                                            ]);

                                }
                            }
                        }
                    }
                    
                    if(isset($prev_acc_more) && !empty($prev_acc_more)){
                        foreach($prev_acc_more as $prev_acc_res){
                            if(isset($prev_acc_res->more_hotelRoom_type_id) AND !empty($prev_acc_res->more_hotelRoom_type_id)){
                                $found = false;
                                foreach($arr_ele_found as $arr_id_res){
                                    if($arr_id_res == $prev_acc_res->more_hotelRoom_type_id){
                                        $found = true;
                                    }
                                }
                                if(!$found){
                                    
                                    $perv_total = 0;
                                    $rooms_total_pr_prev = 0;
                                    foreach($prev_acc as $cal_total_prev){
                                        if($prev_acc_res->more_hotelRoom_type_id == $cal_total_prev->hotelRoom_type_id){
                                            $perv_total += $cal_total_prev->acc_qty;
                                            
                                             $room_data = DB::table('rooms')->where('id',$cal_total_prev->hotelRoom_type_id)->first();
                                
                                                    if($room_data){
                                                             $week_days_total = 0;
                                                             $week_end_days_totals = 0;
                                                             $total_price = 0;
                                                             $acc_check_in = date('Y-m-d',strtotime($cal_total_prev->acc_check_in));
                                                             $acc_check_out = date('Y-m-d',strtotime($cal_total_prev->acc_check_out));
                                                            if($room_data->price_week_type == 'for_all_days'){
                                                                $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                                $total_price = $room_data->price_all_days * $avaiable_days;
                                                            }else{
                                                                 $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                                 
                                                                 $all_days = getBetweenDates($acc_check_in, $acc_check_out);
                                                                 
                                                                //  print_r($all_days);
                                                                 $week_days = json_decode($room_data->weekdays);
                                                                 $week_end_days = json_decode($room_data->weekends);
                                                                 
                                                                 foreach($all_days as $day_res){
                                                                     $day = date('l', strtotime($day_res));
                                                                     $day = trim($day);
                                                                     $week_day_found = false;
                                                                     $week_end_day_found = false;
                                                                    
                                                                     foreach($week_days as $week_day_res){
                                                                         if($week_day_res == $day){
                                                                             $week_day_found = true;
                                                                         }
                                                                     }
                                                              
                                                                    // echo "  ".$room_data->weekdays_price;
                                                                     if($week_day_found){
                                                                         $week_days_total += $room_data->weekdays_price;
                                                                     }else{
                                                                         $week_end_days_totals += $room_data->weekends_price;
                                                                     }
                                                                     
                                                                     
                                                                    //  foreach($week_end_days as $week_day_res){
                                                                    //      if($week_day_res == $day){
                                                                    //          $week_end_day_found = true;
                                                                    //      }
                                                                    //  }
                                                                    //   if($week_end_day_found){
                                                                          
                                                                    //  }
                                                                 }
                                                                 
                                                                 
                                                                //   echo "Week Days price Total $week_days_total and Weekend price Total $week_end_days_totals";
                                                                 
                                                                //  print_r($all_days);
                                                                 $total_price = $week_days_total + $week_end_days_totals;
                                                            }
                                                            
                                                            
                                                            $all_days_price = $total_price * $cal_total_prev->acc_qty;
                                                            $rooms_total_pr_prev += $all_days_price;
                                                    }
                                        }
                                    }
                                    
                                    
                                    if(isset($prev_acc_more) && !empty($prev_acc_more)){
                                        foreach($prev_acc_more as $cal_total_prev){
                                            if($prev_acc_res->more_hotelRoom_type_id == $cal_total_prev->more_hotelRoom_type_id){
                                                $perv_total += $cal_total_prev->more_acc_qty;
                                                
                                                 // Calaculate Room Prices
                                               $room_data = DB::table('rooms')->where('id',$cal_total_prev->more_hotelRoom_type_id)->first();
                                    
                                                        if($room_data){
                                                                 $week_days_total = 0;
                                                                 $week_end_days_totals = 0;
                                                                 $total_price = 0;
                                                                 $acc_check_in = date('Y-m-d',strtotime($cal_total_prev->more_acc_check_in));
                                                                 $acc_check_out = date('Y-m-d',strtotime($cal_total_prev->more_acc_check_out));
                                                                if($room_data->price_week_type == 'for_all_days'){
                                                                    $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                                    $total_price = $room_data->price_all_days * $avaiable_days;
                                                                }else{
                                                                     $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                                     
                                                                     $all_days = getBetweenDates($acc_check_in, $acc_check_out);
                                                                     
                                                                    //  print_r($all_days);
                                                                     $week_days = json_decode($room_data->weekdays);
                                                                     $week_end_days = json_decode($room_data->weekends);
                                                                     
                                                                     foreach($all_days as $day_res){
                                                                         $day = date('l', strtotime($day_res));
                                                                         $day = trim($day);
                                                                         $week_day_found = false;
                                                                         $week_end_day_found = false;
                                                                        
                                                                         foreach($week_days as $week_day_res){
                                                                             if($week_day_res == $day){
                                                                                 $week_day_found = true;
                                                                             }
                                                                         }
                                                                  
                                                                        // echo "  ".$room_data->weekdays_price;
                                                                         if($week_day_found){
                                                                             $week_days_total += $room_data->weekdays_price;
                                                                         }else{
                                                                             $week_end_days_totals += $room_data->weekends_price;
                                                                         }
                                                                         
                                                                         
                                                                        //  foreach($week_end_days as $week_day_res){
                                                                        //      if($week_day_res == $day){
                                                                        //          $week_end_day_found = true;
                                                                        //      }
                                                                        //  }
                                                                        //   if($week_end_day_found){
                                                                              
                                                                        //  }
                                                                     }
                                                                     
                                                                     
                                                                    //   echo "Week Days price Total $week_days_total and Weekend price Total $week_end_days_totals";
                                                                     
                                                                    //  print_r($all_days);
                                                                     $total_price = $week_days_total + $week_end_days_totals;
                                                                }
                                                                
                                                                
                                                                $all_days_price = $total_price * $cal_total_prev->more_acc_qty;
                                                                $rooms_total_pr_prev += $all_days_price;
                                                        }
                                            }
                                        }
                                    }
                                    
                                    
                                    $new_total = 0;
                                    $rooms_total_pr_new = 0;
                                    foreach($new_acc as $cal_total_prev){
                                        if($prev_acc_res->more_hotelRoom_type_id == $cal_total_prev->hotelRoom_type_id){
                                            $new_total += $cal_total_prev->acc_qty;
                                            
                                            // Calaculate Room Prices
                                                 $room_data = DB::table('rooms')->where('id',$cal_total_prev->hotelRoom_type_id)->first();
                                
                                                    if($room_data){
                                                             $week_days_total = 0;
                                                             $week_end_days_totals = 0;
                                                             $total_price = 0;
                                                             $acc_check_in = date('Y-m-d',strtotime($cal_total_prev->acc_check_in));
                                                             $acc_check_out = date('Y-m-d',strtotime($cal_total_prev->acc_check_out));
                                                            if($room_data->price_week_type == 'for_all_days'){
                                                                $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                                $total_price = $room_data->price_all_days * $avaiable_days;
                                                            }else{
                                                                 $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                                 
                                                                 $all_days = getBetweenDates($acc_check_in, $acc_check_out);
                                                                 
                                                                //  print_r($all_days);
                                                                 $week_days = json_decode($room_data->weekdays);
                                                                 $week_end_days = json_decode($room_data->weekends);
                                                                 
                                                                 foreach($all_days as $day_res){
                                                                     $day = date('l', strtotime($day_res));
                                                                     $day = trim($day);
                                                                     $week_day_found = false;
                                                                     $week_end_day_found = false;
                                                                    
                                                                     foreach($week_days as $week_day_res){
                                                                         if($week_day_res == $day){
                                                                             $week_day_found = true;
                                                                         }
                                                                     }
                                                              
                                                                    // echo "  ".$room_data->weekdays_price;
                                                                     if($week_day_found){
                                                                         $week_days_total += $room_data->weekdays_price;
                                                                     }else{
                                                                         $week_end_days_totals += $room_data->weekends_price;
                                                                     }
                                                                     
                                                                     
                                                                    //  foreach($week_end_days as $week_day_res){
                                                                    //      if($week_day_res == $day){
                                                                    //          $week_end_day_found = true;
                                                                    //      }
                                                                    //  }
                                                                    //   if($week_end_day_found){
                                                                          
                                                                    //  }
                                                                 }
                                                                 
                                                                 
                                                                //   echo "Week Days price Total $week_days_total and Weekend price Total $week_end_days_totals";
                                                                 
                                                                //  print_r($all_days);
                                                                 $total_price = $week_days_total + $week_end_days_totals;
                                                            }
                                                            
                                                            
                                                            $all_days_price = $total_price * $cal_total_prev->acc_qty;
                                                            $rooms_total_pr_new += $all_days_price;
                                                    }
                                            
                                             DB::table('rooms_bookings_details')->insert([
                                                    'room_id'=> $cal_total_prev->hotelRoom_type_id,
                                                    'booking_from'=> 'Invoices',
                                                    'quantity'=> $cal_total_prev->acc_qty,
                                                    'booking_id'=> $insert->id,
                                                    'date'=> date('Y-m-d',strtotime($insert->created_at)),
                                                    'check_in'=> $cal_total_prev->acc_check_in,
                                                    'check_out'=> $cal_total_prev->acc_check_out,
                                                ]);
                                        }
                                    }
                                    
                                    
                                     if(isset($new_acc_more) && !empty($new_acc_more)){
                                        foreach($new_acc_more as $cal_total_prev){
                                            if($prev_acc_res->more_hotelRoom_type_id == $cal_total_prev->more_hotelRoom_type_id){
                                                $new_total += $cal_total_prev->more_acc_qty;
                                                
                                                   // Calaculate Room Prices
                                                     $room_data = DB::table('rooms')->where('id',$cal_total_prev->more_hotelRoom_type_id)->first();
                                    
                                                        if($room_data){
                                                                 $week_days_total = 0;
                                                                 $week_end_days_totals = 0;
                                                                 $total_price = 0;
                                                                 $acc_check_in = date('Y-m-d',strtotime($cal_total_prev->more_acc_check_in));
                                                                 $acc_check_out = date('Y-m-d',strtotime($cal_total_prev->more_acc_check_out));
                                                                if($room_data->price_week_type == 'for_all_days'){
                                                                    $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                                    $total_price = $room_data->price_all_days * $avaiable_days;
                                                                }else{
                                                                     $avaiable_days = dateDiffInDays($acc_check_in, $acc_check_out);
                                                                     
                                                                     $all_days = getBetweenDates($acc_check_in, $acc_check_out);
                                                                     
                                                                    //  print_r($all_days);
                                                                     $week_days = json_decode($room_data->weekdays);
                                                                     $week_end_days = json_decode($room_data->weekends);
                                                                     
                                                                     foreach($all_days as $day_res){
                                                                         $day = date('l', strtotime($day_res));
                                                                         $day = trim($day);
                                                                         $week_day_found = false;
                                                                         $week_end_day_found = false;
                                                                        
                                                                         foreach($week_days as $week_day_res){
                                                                             if($week_day_res == $day){
                                                                                 $week_day_found = true;
                                                                             }
                                                                         }
                                                                  
                                                                        // echo "  ".$room_data->weekdays_price;
                                                                         if($week_day_found){
                                                                             $week_days_total += $room_data->weekdays_price;
                                                                         }else{
                                                                             $week_end_days_totals += $room_data->weekends_price;
                                                                         }
                                                                         
                                                                         
                                                                        //  foreach($week_end_days as $week_day_res){
                                                                        //      if($week_day_res == $day){
                                                                        //          $week_end_day_found = true;
                                                                        //      }
                                                                        //  }
                                                                        //   if($week_end_day_found){
                                                                              
                                                                        //  }
                                                                     }
                                                                     
                                                                     
                                                                    //   echo "Week Days price Total $week_days_total and Weekend price Total $week_end_days_totals";
                                                                     
                                                                    //  print_r($all_days);
                                                                     $total_price = $week_days_total + $week_end_days_totals;
                                                                }
                                                                
                                                                
                                                                $all_days_price = $total_price * $cal_total_prev->more_acc_qty;
                                                                $rooms_total_pr_new += $all_days_price;
                                                        }
                                                
                                                DB::table('rooms_bookings_details')->insert([
                                                        'room_id'=> $cal_total_prev->more_hotelRoom_type_id,
                                                        'booking_from'=> 'Invoices',
                                                        'quantity'=> $cal_total_prev->more_acc_qty,
                                                        'booking_id'=> $insert->id,
                                                        'date'=> date('Y-m-d',strtotime($insert->created_at)),
                                                        'check_in'=> $cal_total_prev->more_acc_check_in,
                                                        'check_out'=> $cal_total_prev->more_acc_check_out,
                                                    ]);
                                            }
                                        }
                                     }
                                    
                                    array_push($arr_ele_found,$prev_acc_res->more_hotelRoom_type_id);
                                    
                                    $room_data = DB::table('rooms')->where('id',$prev_acc_res->more_hotelRoom_type_id)->first();
                                    $difference = $new_total - $perv_total;
                                    
                                    $Price_difference = $rooms_total_pr_new - $rooms_total_pr_prev;
                                    
                                    $update_booked = $room_data->booked + $difference;
                                    
                                    $room_data = DB::table('rooms')->where('id',$prev_acc_res->more_hotelRoom_type_id)->update([
                                    'booked'=>$update_booked
                                    ]);
                                    
                                    $room_data = DB::table('rooms')->where('id',$prev_acc_res->more_hotelRoom_type_id)->first();
                                     $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
           
                                     if(isset($supplier_data)){
    
                                        $supplier_balance = $supplier_data->balance;
                                            $supplier_payable_balance = $supplier_data->payable + $Price_difference;
                                            
                                            // update Agent Balance
                                            
                                            DB::table('hotel_supplier_ledger')->insert([
                                                'supplier_id'=>$supplier_data->id,
                                                'payment'=>$Price_difference,
                                                'balance'=>$supplier_balance,
                                                'payable_balance'=>$supplier_payable_balance,
                                                'room_id'=>$room_data->id,
                                                'customer_id'=>$insert->customer_id,
                                                'date'=>date('Y-m-d'),
                                                'invoice_no'=>$insert->id,
                                                'available_from'=>'',
                                                'available_to'=>'',
                                                'remarks'=>'Invoice Updated',
                                                ]);
                                                
                                            DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                                                'balance'=>$supplier_balance,
                                                'payable'=>$supplier_payable_balance
                                                ]);
    
                                    }
                                    // echo "The Room id is ".$prev_acc_res->more_hotelRoom_type_id." and total qty is $perv_total and new $new_total "."<br>";
                                }
                            }
                        }
                    }
                    
                    // New Element Found Working
                    if(isset($new_acc) && !empty($new_acc)){
                        foreach($new_acc as $new_acc_res){
                            $ele_found = false;
                         
                            foreach($arr_ele_found as $arr_res){
                                if($new_acc_res->hotelRoom_type_id == $arr_res){
                                    $ele_found = true;
                                }
                            }
                         
                            if(isset($new_acc_res->hotelRoom_type_id) AND !empty($new_acc_res->hotelRoom_type_id)){
                                if(!$ele_found){
                                    $room_data = DB::table('rooms')->where('id',$new_acc_res->hotelRoom_type_id)->first();
                                
                                
                                    $update_booked = (int)$room_data->booked + (int)$new_acc_res->acc_qty;
                                
                                    $room_update = DB::table('rooms')->where('id',$new_acc_res->hotelRoom_type_id)->update(['booked'=>$update_booked
                                    ]);
                            
                                    DB::table('rooms_bookings_details')->insert([
                                            'room_id'=> $new_acc_res->hotelRoom_type_id,
                                            'booking_from'=> 'Invoices',
                                            'quantity'=> $new_acc_res->acc_qty,
                                            'booking_id'=> $insert->id,
                                            'date'=> date('Y-m-d',strtotime($insert->created_at)),
                                            'check_in'=> $new_acc_res->acc_check_in,
                                            'check_out'=> $new_acc_res->acc_check_out,
                                        ]);
                                        
                                    $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
   
                                    if(isset($supplier_data)){
                                        // echo "Enter hre ";
                                    
                                         $week_days_total = 0;
                                         $week_end_days_totals = 0;
                                         $total_price = 0;
                                         $new_acc_res->acc_check_in = date('Y-m-d',strtotime($new_acc_res->acc_check_in));
                                         $new_acc_res->acc_check_out = date('Y-m-d',strtotime($new_acc_res->acc_check_out));
                                        if($room_data->price_week_type == 'for_all_days'){
                                            $avaiable_days = dateDiffInDays($new_acc_res->acc_check_in, $new_acc_res->acc_check_out);
                                            $total_price = $room_data->price_all_days * $avaiable_days;
                                        }else{
                                             $avaiable_days = dateDiffInDays($new_acc_res->acc_check_in, $new_acc_res->acc_check_out);
                                             
                                             $all_days = getBetweenDates($new_acc_res->acc_check_in, $new_acc_res->acc_check_out);
                                             
                                            //  print_r($all_days);
                                             $week_days = json_decode($room_data->weekdays);
                                             $week_end_days = json_decode($room_data->weekends);
                                             
                                             foreach($all_days as $day_res){
                                                 $day = date('l', strtotime($day_res));
                                                 $day = trim($day);
                                                 $week_day_found = false;
                                                 $week_end_day_found = false;
                                                
                                                 foreach($week_days as $week_day_res){
                                                     if($week_day_res == $day){
                                                         $week_day_found = true;
                                                     }
                                                 }
                                          
                                                // echo "  ".$room_data->weekdays_price;
                                                 if($week_day_found){
                                                     $week_days_total += $room_data->weekdays_price;
                                                 }else{
                                                     $week_end_days_totals += $room_data->weekends_price;
                                                 }
                                                 
                                                 
                                                //  foreach($week_end_days as $week_day_res){
                                                //      if($week_day_res == $day){
                                                //          $week_end_day_found = true;
                                                //      }
                                                //  }
                                                //   if($week_end_day_found){
                                                      
                                                //  }
                                             }
                                             
                                             
                                            //   echo "Week Days price Total $week_days_total and Weekend price Total $week_end_days_totals";
                                             
                                            //  print_r($all_days);
                                             $total_price = $week_days_total + $week_end_days_totals;
                                        }
                                        
                                        
                                    $all_days_price = $total_price * $new_acc_res->acc_qty;
                                    
                                    // echo "All Days price is $total_price qty ".$accomodation_res->acc_qty;
                                    // die;
                                    
                                    // echo "The supplier Balance is ".$supplier_data->balance;
                                    $supplier_balance = $supplier_data->balance;
                                    $supplier_payable_balance = $supplier_data->payable + $all_days_price;
                                    
                                    // update Agent Balance
                                    
                                    DB::table('hotel_supplier_ledger')->insert([
                                        'supplier_id'=>$supplier_data->id,
                                        'payment'=>$all_days_price,
                                        'balance'=>$supplier_balance,
                                        'payable_balance'=>$supplier_payable_balance,
                                        'room_id'=>$room_data->id,
                                        'customer_id'=>$insert->customer_id,
                                        'date'=>date('Y-m-d'),
                                        'invoice_no'=>$insert->id,
                                        'available_from'=>$new_acc_res->acc_check_in,
                                        'available_to'=>$new_acc_res->acc_check_out,
                                        'room_quantity'=>$new_acc_res->acc_qty,
                                        ]);
                                        
                                    DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                                        'balance'=>$supplier_balance,
                                        'payable'=>$supplier_payable_balance
                                        ]);
                                    
                                    
                                      
                                                                
                                }
                                }
                            }
                        }
                    }
                  
                    if(isset($new_acc_more) && !empty($new_acc_more)){
                         foreach($new_acc_more as $new_acc_res){
                             $ele_found = false;
                             
                             foreach($arr_ele_found as $arr_res){
                                 if($new_acc_res->more_hotelRoom_type_id == $arr_res){
                                     $ele_found = true;
                                 }
                             }
                             
                             if(isset($new_acc_res->more_hotelRoom_type_id) AND !empty($new_acc_res->more_hotelRoom_type_id)){
                                 if(!$ele_found){
                                 $room_data = DB::table('rooms')->where('id',$new_acc_res->more_hotelRoom_type_id)->first();
                                    
                                    $update_booked = (int)$room_data->booked + (int)$new_acc_res->more_acc_qty;
                                    
                                    $room_update = DB::table('rooms')->where('id',$new_acc_res->more_hotelRoom_type_id)->update([
                                    'booked'=>$update_booked
                                    ]);
                                    
                                    // echo "room id is from new".$new_acc_res->more_hotelRoom_type_id;
                                    // print_r($room_data);
                                
                                  DB::table('rooms_bookings_details')->insert([
                                                'room_id'=> $new_acc_res->more_hotelRoom_type_id,
                                                'booking_from'=> 'Invoices',
                                                'quantity'=> $new_acc_res->more_acc_qty,
                                                'booking_id'=> $insert->id,
                                                'date'=> date('Y-m-d',strtotime($insert->created_at)),
                                                'check_in'=> $new_acc_res->more_acc_check_in,
                                                'check_out'=> $new_acc_res->more_acc_check_out,
                                            ]);
                                            
                                    
                                            
                                            
                                            
                                 $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
       
                                 if(isset($supplier_data)){
                                        // echo "Enter hre ";
                                        
                                             $week_days_total = 0;
                                             $week_end_days_totals = 0;
                                             $total_price = 0;
                                             $new_acc_res->more_acc_check_in = date('Y-m-d',strtotime($new_acc_res->more_acc_check_in));
                                             $new_acc_res->more_acc_check_out = date('Y-m-d',strtotime($new_acc_res->more_acc_check_out));
                                            if($room_data->price_week_type == 'for_all_days'){
                                                $avaiable_days = dateDiffInDays($new_acc_res->more_acc_check_in, $new_acc_res->more_acc_check_out);
                                                $total_price = $room_data->price_all_days * $avaiable_days;
                                            }else{
                                                 $avaiable_days = dateDiffInDays($new_acc_res->more_acc_check_in, $new_acc_res->more_acc_check_out);
                                                 
                                                 $all_days = getBetweenDates($new_acc_res->more_acc_check_in, $new_acc_res->more_acc_check_out);
                                                 
                                                //  print_r($all_days);
                                                 $week_days = json_decode($room_data->weekdays);
                                                 $week_end_days = json_decode($room_data->weekends);
                                                 
                                                 foreach($all_days as $day_res){
                                                     $day = date('l', strtotime($day_res));
                                                     $day = trim($day);
                                                     $week_day_found = false;
                                                     $week_end_day_found = false;
                                                    
                                                     foreach($week_days as $week_day_res){
                                                         if($week_day_res == $day){
                                                             $week_day_found = true;
                                                         }
                                                     }
                                              
                                                    // echo "  ".$room_data->weekdays_price;
                                                     if($week_day_found){
                                                         $week_days_total += $room_data->weekdays_price;
                                                     }else{
                                                         $week_end_days_totals += $room_data->weekends_price;
                                                     }
                                                     
                                                     
                                                    //  foreach($week_end_days as $week_day_res){
                                                    //      if($week_day_res == $day){
                                                    //          $week_end_day_found = true;
                                                    //      }
                                                    //  }
                                                    //   if($week_end_day_found){
                                                          
                                                    //  }
                                                 }
                                                 
                                                 
                                                //   echo "Week Days price Total $week_days_total and Weekend price Total $week_end_days_totals";
                                                 
                                                //  print_r($all_days);
                                                 $total_price = $week_days_total + $week_end_days_totals;
                                            }
                                            
                                            
                                        $all_days_price = $total_price * $new_acc_res->more_acc_qty;
                                        
                                        // echo "All Days price is $total_price qty ".$accomodation_res->acc_qty;
                                        // die;
                                        
                                        // echo "The supplier Balance is ".$supplier_data->balance;
                                        $supplier_balance = $supplier_data->balance;
                                        $supplier_payable_balance = $supplier_data->payable + $all_days_price;
                                        
                                        // update Agent Balance
                                        
                                        DB::table('hotel_supplier_ledger')->insert([
                                            'supplier_id'=>$supplier_data->id,
                                            'payment'=>$all_days_price,
                                            'balance'=>$supplier_balance,
                                            'payable_balance'=>$supplier_payable_balance,
                                            'room_id'=>$room_data->id,
                                            'customer_id'=>$insert->customer_id,
                                            'date'=>date('Y-m-d'),
                                            'invoice_no'=>$insert->id,
                                            'available_from'=>$new_acc_res->more_acc_check_in,
                                            'available_to'=>$new_acc_res->more_acc_check_out,
                                            'room_quantity'=>$new_acc_res->more_acc_qty,
                                            ]);
                                            
                                        DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                                            'balance'=>$supplier_balance,
                                            'payable'=>$supplier_payable_balance
                                            ]);
                                        
                                        
                                          
                                                                    
                                    }
                                }
                             }
                         }
                     }
                    
                    // 1 Loop on Previous
                    if(isset($prev_flight_pax)){
                        foreach($prev_flight_pax as $flight_prev_res){
                            $ele_found = false;
                            foreach($new_flight_pax as $flight_new_res){
                                if($flight_prev_res->flight_route_id_occupied == $flight_new_res->flight_route_id_occupied){
                                    $ele_found = true;
                                    $route_obj = DB::table('flight_rute')->where('id',$flight_prev_res->flight_route_id_occupied)->first();
                                    // print_r($route_obj);
                                    // die;
                                    
                                    // Calaculate Child Prev Price Differ
                                    $child_price_wi_adult_price_prev = $route_obj->flights_per_person_price * $flight_prev_res->flights_child_seats;
                                    $child_price_wi_child_price_prev = $route_obj->flights_per_child_price * $flight_prev_res->flights_child_seats;
                                    
                                    $price_diff_prev = $child_price_wi_adult_price_prev - $child_price_wi_child_price_prev;
                                    
                                    // Calaculate Child New Price Differ
                                    $child_price_wi_adult_price_new = $route_obj->flights_per_person_price * $flight_new_res->flights_child_seats;
                                    $child_price_wi_child_price_new = $route_obj->flights_per_child_price * $flight_new_res->flights_child_seats;
                                    
                                    $price_diff_new = $child_price_wi_adult_price_new - $child_price_wi_child_price_new;
                                    
                                    // Calculate Final Differ
                                    $child_price_diff = $price_diff_new - $price_diff_prev;
                                    
                                    
                                    // Calaculate Infant Prev Price
                                    $infant_price_prev = $route_obj->flights_per_infant_price * $flight_prev_res->flights_infant_seats;
                                    
                                     // Calaculate Infant New Price
                                    $infant_price_new = $route_obj->flights_per_infant_price * $flight_new_res->flights_infant_seats;
                                    
                                    // Calculate Final Differ
                                    $infant_price_diff = $infant_price_new - $infant_price_prev;
                                    
                                    
                                    
                                    $supplier_data = DB::table('supplier')->where('id',$route_obj->dep_supplier)->first();
                                    
                                    if($child_price_diff != 0 || $infant_price_diff != 0){
                                        $supplier_balance = $supplier_data->balance - $child_price_diff;
                                        
                                        $supplier_balance = $supplier_balance + $infant_price_diff;
                                        $total_differ = $infant_price_diff - $child_price_diff;
                                        
                                        DB::table('flight_supplier_ledger')->insert([
                                                    'supplier_id'=>$supplier_data->id,
                                                    'payment'=>$total_differ,
                                                    'balance'=>$supplier_balance,
                                                    'route_id'=>$flight_prev_res->flight_route_id_occupied,
                                                    'date'=>date('Y-m-d'),
                                                    'customer_id'=>$insert->customer_id,
                                                    'adult_price'=>$route_obj->flights_per_person_price,
                                                    'child_price'=>$route_obj->flights_per_child_price,
                                                    'infant_price'=>$route_obj->flights_per_infant_price,
                                                    'adult_seats_booked'=>$flight_new_res->flights_adult_seats,
                                                    'child_seats_booked'=>$flight_new_res->flights_child_seats,
                                                    'infant_seats_booked'=>$flight_new_res->flights_infant_seats,
                                                    'invoice_no'=>$insert->id,
                                                    'remarks'=>'Invoice Update',
                                                  ]);
                                                  
                                        DB::table('supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                                    }
                                    
                                }
                            }
                            
                            // If element Not Found in New
                            if(!$ele_found){
                                $route_obj = DB::table('flight_rute')->where('id',$flight_prev_res->flight_route_id_occupied)->first();
                                    // print_r($route_obj);
                                    // die;
                                    
                                    // Calaculate Child Prev Price Differ
                                    $child_price_wi_adult_price_prev = $route_obj->flights_per_person_price * $flight_prev_res->flights_child_seats;
                                    $child_price_wi_child_price_prev = $route_obj->flights_per_child_price * $flight_prev_res->flights_child_seats;
                                    
                                    $price_diff_prev = $child_price_wi_adult_price_prev - $child_price_wi_child_price_prev;
                                    
                                    
                                    // Calaculate Infant Prev Price
                                    $infant_price_prev = $route_obj->flights_per_infant_price * $flight_prev_res->flights_infant_seats;
                                    
                                    
                                    
                                    
                                    
                                    $supplier_data = DB::table('supplier')->where('id',$route_obj->dep_supplier)->first();
                                    
                                    if($price_diff_prev != 0 || $infant_price_prev != 0){
                                        $supplier_balance = $supplier_data->balance + $price_diff_prev;
                                        
                                        $supplier_balance = $supplier_balance - $infant_price_prev;
                                        $total_differ = $price_diff_prev - $infant_price_prev;
                                        
                                        DB::table('flight_supplier_ledger')->insert([
                                                    'supplier_id'=>$supplier_data->id,
                                                    'payment'=>$total_differ,
                                                    'balance'=>$supplier_balance,
                                                    'route_id'=>$flight_prev_res->flight_route_id_occupied,
                                                    'date'=>date('Y-m-d'),
                                                    'customer_id'=>$insert->customer_id,
                                                    'adult_price'=>$route_obj->flights_per_person_price,
                                                    'child_price'=>$route_obj->flights_per_child_price,
                                                    'infant_price'=>$route_obj->flights_per_infant_price,
                                                    'adult_seats_booked'=>$flight_prev_res->flights_adult_seats,
                                                    'child_seats_booked'=>$flight_prev_res->flights_child_seats,
                                                    'infant_seats_booked'=>$flight_prev_res->flights_infant_seats,
                                                    'invoice_no'=>$insert->id,
                                                    'remarks'=>'Invoice Update',
                                                  ]);
                                                  
                                        DB::table('supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                                    }
                            }
                        }
                    }
                        
                    // 2 Loop on New 
                    if(isset($new_flight_pax)){
                        foreach($new_flight_pax as $flight_new_res){
                            $pre_el_found = false;
                            foreach($prev_flight_pax as $flight_prev_res){
                                if($flight_prev_res->flight_route_id_occupied == $flight_new_res->flight_route_id_occupied){
                                    $pre_el_found = true;
                                }
                            }
                            
                            // If element Not Found in Prev
                            if(!$pre_el_found){
                                $route_obj = DB::table('flight_rute')->where('id',$flight_new_res->flight_route_id_occupied)->first();
                                    // print_r($route_obj);
                                    // die;
                                    
                                    // Calaculate Child Prev Price Differ
                                    $child_price_wi_adult_price_prev = $route_obj->flights_per_person_price * $flight_new_res->flights_child_seats;
                                    $child_price_wi_child_price_prev = $route_obj->flights_per_child_price * $flight_new_res->flights_child_seats;
                                    
                                    $price_diff_prev = $child_price_wi_adult_price_prev - $child_price_wi_child_price_prev;
                                    
                                    
                                    // Calaculate Infant Prev Price
                                    $infant_price_prev = $route_obj->flights_per_infant_price * $flight_new_res->flights_infant_seats;
                                    
                                    
                                    
                                    
                                    
                                    $supplier_data = DB::table('supplier')->where('id',$route_obj->dep_supplier)->first();
                                    
                                    if($price_diff_prev != 0 || $infant_price_prev != 0){
                                        $supplier_balance = $supplier_data->balance - $price_diff_prev;
                                        
                                        $supplier_balance = $supplier_balance + $infant_price_prev;
                                        $total_differ = $infant_price_prev - $price_diff_prev;
                                        
                                        DB::table('flight_supplier_ledger')->insert([
                                                    'supplier_id'=>$supplier_data->id,
                                                    'payment'=>$total_differ,
                                                    'balance'=>$supplier_balance,
                                                    'route_id'=>$flight_new_res->flight_route_id_occupied,
                                                    'date'=>date('Y-m-d'),
                                                    'customer_id'=>$insert->customer_id,
                                                    'adult_price'=>$route_obj->flights_per_person_price,
                                                    'child_price'=>$route_obj->flights_per_child_price,
                                                    'infant_price'=>$route_obj->flights_per_infant_price,
                                                    'adult_seats_booked'=>$flight_new_res->flights_adult_seats,
                                                    'child_seats_booked'=>$flight_new_res->flights_child_seats,
                                                    'infant_seats_booked'=>$flight_new_res->flights_infant_seats,
                                                    'invoice_no'=>$insert->id,
                                                    'remarks'=>'Invoice Update',
                                                  ]);
                                                  
                                        DB::table('supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                                    }
                            }
                        }
                    }
                    
                    // echo "$previous_transfer_sup id is $new_transfer_sup";
                    
                    if($previous_transfer_sup == $new_transfer_sup){
                        
                        // Calculate Previous Total
                         $transfer_prev_total = 0;
                        if(isset($prev_transfer_det) && !empty($prev_transfer_det) && $prev_transfer_det != null){
                                $transfer_data = $prev_transfer_det;
                
                                if(isset($transfer_data)){
                                        if(1 == count($transfer_data)){
                                              foreach($transfer_data  as $index => $trans_res_data){
                                     
                                                     $transfer_prev_total += (float)$trans_res_data->transportation_vehicle_total_price;
                                            
                                             }
                                            
                                        }else{
                                          
                                          foreach($transfer_data  as $index => $trans_res_data){
                                            if($trans_res_data->transportation_pick_up_location != ''){
                                               $transfer_prev_total += (float)$trans_res_data->transportation_vehicle_total_price;
                                              
                                            }
                                          }
                                        
                                    }
                                }
                            }
                            
                        // Calculate New Total
                        $transfer_new_total = 0;
                        if(isset($new_transfer_det) && !empty($new_transfer_det) && $new_transfer_det != null){
                                $transfer_data = $new_transfer_det;
                                // print_r($transfer_data);
                                // die;
                                if(isset($transfer_data)){
                                        if(1 == count($transfer_data)){
                                              foreach($transfer_data  as $index => $trans_res_data){
                                     
                                                     $transfer_new_total += (float)$trans_res_data->transportation_vehicle_total_price;
                                            
                                             }
                                            
                                        }else{
                                          
                                          foreach($transfer_data  as $index => $trans_res_data){
                                            if($trans_res_data->transportation_pick_up_location != ''){
                                               $transfer_new_total += (float)$trans_res_data->transportation_vehicle_total_price;
                                              
                                            }
                                          }
                                        
                                    }
                                }
                            }
                            
                        $price_diff = $transfer_new_total - $transfer_prev_total;
                        
                        // Update Supplier Balance
                        $transfer_sup_data = DB::table('transfer_Invoice_Supplier')->where('id',$req->transfer_supplier_id)->select('id','balance')->first();
                                                
                        $trans_sup_balance = $transfer_sup_data->balance ?? '0' + $price_diff;
                        
                        if($price_diff != 0){
                            DB::table('transfer_supplier_ledger')->insert([
                                    'supplier_id'=>$req->transfer_supplier_id,
                                    'payment'=> $price_diff,
                                    'balance'=> $trans_sup_balance,
                                    'invoice_no'=>$insert->id,
                                    'remarks'=>'Invoice Updated',
                                    'date'=>date('Y-m-d'),
                                    'customer_id'=>$req->customer_id,
                                ]);
                            DB::table('transfer_Invoice_Supplier')->where('id',$req->transfer_supplier_id)->update(['balance'=>$trans_sup_balance]);
                        }
                    }else{
                         // Update Previous Supplier Balance
                         $transfer_prev_total = 0;
                        if(isset($prev_transfer_det) && !empty($prev_transfer_det) && $prev_transfer_det != null){
                                $transfer_data = $prev_transfer_det;
                
                                if(isset($transfer_data)){
                                        if(1 == count($transfer_data)){
                                              foreach($transfer_data  as $index => $trans_res_data){
                                     
                                                     $transfer_prev_total += (float)$trans_res_data->transportation_vehicle_total_price;
                                            
                                             }
                                            
                                        }else{
                                          
                                          foreach($transfer_data  as $index => $trans_res_data){
                                            if($trans_res_data->transportation_pick_up_location != ''){
                                               $transfer_prev_total += (float)$trans_res_data->transportation_vehicle_total_price;
                                              
                                            }
                                          }
                                        
                                    }
                                    
                                    $price_diff = 0 - $transfer_prev_total;
                                    
                                    // Update Supplier Balance
                                    $transfer_sup_data = DB::table('transfer_Invoice_Supplier')->where('id',$previous_transfer_sup)->select('id','balance')->first();
                                                            
                                    $trans_sup_balance = (float)$transfer_sup_data->balance + (float)$price_diff;
                                    
                                     if($price_diff != 0){
                                        DB::table('transfer_supplier_ledger')->insert([
                                                'supplier_id'=>$previous_transfer_sup,
                                                'payment'=> $price_diff,
                                                'balance'=> $trans_sup_balance,
                                                'invoice_no'=>$insert->id,
                                                'remarks'=>'Invoice Updated',
                                                'date'=>date('Y-m-d'),
                                                'customer_id'=>$req->customer_id,
                                            ]);
                                        DB::table('transfer_Invoice_Supplier')->where('id',$previous_transfer_sup)->update(['balance'=>$trans_sup_balance]);
                                     }
                                }
                            }
                            
                        // Calculate New Total
                        $transfer_new_total = 0;
                        if(isset($new_transfer_det) && !empty($new_transfer_det) && $new_transfer_det != null){
                                $transfer_data = $new_transfer_det;
                                // print_r($transfer_data);
                                // die;
                                if(isset($transfer_data)){
                                        if(1 == count($transfer_data)){
                                              foreach($transfer_data  as $index => $trans_res_data){
                                     
                                                     $transfer_new_total += (float)$trans_res_data->transportation_vehicle_total_price;
                                            
                                             }
                                            
                                        }else{
                                          
                                          foreach($transfer_data  as $index => $trans_res_data){
                                            if($trans_res_data->transportation_pick_up_location != ''){
                                               $transfer_new_total += (float)$trans_res_data->transportation_vehicle_total_price;
                                              
                                            }
                                          }
                                        
                                    }
                                    
                                    // echo $transfer_new_total;
                                    // die;
                                      // Update Supplier Balance
                                     $transfer_new_total = (float)$transfer_new_total;
                                    $transfer_sup_data = DB::table('transfer_Invoice_Supplier')->where('id',$new_transfer_sup)->select('id','balance')->first();
                                                            
                                    $trans_sup_balance = (float)$transfer_sup_data->balance + $transfer_new_total;
                                    
                                    
                                    if($transfer_new_total != 0){
                                        DB::table('transfer_supplier_ledger')->insert([
                                                'supplier_id'=>$req->transfer_supplier_id,
                                                'payment'=> $transfer_new_total,
                                                'balance'=> $trans_sup_balance,
                                                'invoice_no'=>$insert->id,
                                                'remarks'=>'Invoice Updated',
                                                'date'=>date('Y-m-d'),
                                                'customer_id'=>$req->customer_id,
                                            ]);
                                        DB::table('transfer_Invoice_Supplier')->where('id',$new_transfer_sup)->update(['balance'=>$trans_sup_balance]);
                                    }
                                }
                            }
                            
                        
                    }
                    
                    
                    // Visa Seats And Supplier Ledgers Update
                    
                    // print_r($new_visa_all_details);
                    // print_r($prev_visa_all_details);
                    // 1 Loop on New 
                    foreach($new_visa_all_details as $new_visa_res){
                        $el_found = false;
                        foreach($prev_visa_all_details as $prev_visa_res){
                            if($new_visa_res->visa_avail_id == $prev_visa_res->visa_avail_id){
                                $el_found = true;
                                
                               // 2 Update No of Seats Occupied in Visa
                                $visa_avail_data = DB::table('visa_Availability')->where('id',$new_visa_res->visa_avail_id)->first();
                                
                                    // Calculate Visa Difference
                                    $visa_diff = $new_visa_res->visa_occupied - $prev_visa_res->visa_occupied;
                                
                                $updated_seats = $visa_avail_data->visa_available - $visa_diff;
                                
                                DB::table('visa_Availability')->where('id',$new_visa_res->visa_avail_id)->update([
                                        'visa_available' => $updated_seats
                                ]);
                                
                                // 3 Update Visa Supplier Balance
                                $visa_supplier_data = DB::table('visa_Sup')->where('id',$visa_avail_data->visa_supplier)->first();
                                
                                    // Calculate Visa Price Difference
                                    $prev_visa_price_total = $prev_visa_res->visa_fee_purchase * $prev_visa_res->visa_occupied;
                                    $new_visa_price_total = $new_visa_res->visa_fee_purchase * $new_visa_res->visa_occupied;
                                    
                                    $price_difference = $new_visa_price_total - $prev_visa_price_total;
                                    if($price_difference != 0){
                                
                                        $visa_supplier_payable_balance = $visa_supplier_data->payable + $price_difference;
                                        DB::table('visa_Sup')->where('id',$visa_avail_data->visa_supplier)->update([
                                                'payable' => $visa_supplier_payable_balance
                                        ]);
                                        
                                        DB::table('visa_supplier_ledger')->insert([
                                                'supplier_id' => $visa_avail_data->visa_supplier,
                                                'payment' => $price_difference,
                                                'balance' => $visa_supplier_data->balance,
                                                'payable' => $visa_supplier_payable_balance,
                                                'visa_qty' => $visa_diff,
                                                'visa_type' => $visa_avail_data->visa_type,
                                                'invoice_no' => $insert->id,
                                                'visa_avl_id' => $visa_avail_data->id,
                                                'remarks' => 'Invoice Updated',
                                                'date' => date('Y-m-d'),
                                                'customer_id' => $req->customer_id,
                                        ]);
                                        
                                        DB::table('visa_Sup')->where('id',$visa_avail_data->visa_supplier)->update([
                                                'payable' => $visa_supplier_payable_balance
                                        ]);
                                    }
                            }
                        }
                        
                        if(!$el_found){
                            // echo $new_visa_res->visa_avail_id;
                            // die;
                            // 2 Update No of Seats Occupied in Visa
                                $visa_avail_data = DB::table('visa_Availability')->where('id',$new_visa_res->visa_avail_id)->first();
                                
                                    // Calculate Visa Difference
                                    $visa_diff = $new_visa_res->visa_occupied;
                                
                                $updated_seats = $visa_avail_data->visa_available - $visa_diff;
                                
                                DB::table('visa_Availability')->where('id',$new_visa_res->visa_avail_id)->update([
                                        'visa_available' => $updated_seats
                                ]);
                                
                                // 3 Update Visa Supplier Balance
                                $visa_supplier_data = DB::table('visa_Sup')->where('id',$visa_avail_data->visa_supplier)->first();
                                
                                    // Calculate Visa Price Difference
                                    
                                    $new_visa_price_total = $new_visa_res->visa_fee_purchase * $new_visa_res->visa_occupied;
                                    
                                    $price_difference = $new_visa_price_total;
                                
                                $visa_supplier_payable_balance = $visa_supplier_data->payable + $price_difference;
                                DB::table('visa_Sup')->where('id',$visa_avail_data->visa_supplier)->update([
                                        'payable' => $visa_supplier_payable_balance
                                ]);
                                
                                DB::table('visa_supplier_ledger')->insert([
                                        'supplier_id' => $visa_avail_data->visa_supplier,
                                        'payment' => $price_difference,
                                        'balance' => $visa_supplier_data->balance,
                                        'payable' => $visa_supplier_payable_balance,
                                        'visa_qty' => $visa_diff,
                                        'visa_type' => $visa_avail_data->visa_type,
                                        'invoice_no' => $insert->id,
                                        'visa_avl_id' => $visa_avail_data->id,
                                        'remarks' => 'Invoice Updated',
                                        'date' => date('Y-m-d'),
                                        'customer_id' => $req->customer_id,
                                ]);
                                
                                DB::table('visa_Sup')->where('id',$visa_avail_data->visa_supplier)->update([
                                        'payable' => $visa_supplier_payable_balance
                                ]);
                        }
                    }
                    
                    // 2 Loop on Previous 
                    foreach($prev_visa_all_details as $prev_visa_res){
                        $el_found = false;
                        foreach($new_visa_all_details as $new_visa_res){
                            if($new_visa_res->visa_avail_id == $prev_visa_res->visa_avail_id){
                                $el_found = true;
                            }
                        }
                        
                        if(!$el_found){
                            // 2 Update No of Seats Occupied in Visa
                                $visa_avail_data = DB::table('visa_Availability')->where('id',$prev_visa_res->visa_avail_id)->first();
                                
                                    // Calculate Visa Difference
                                    $visa_diff = $prev_visa_res->visa_occupied;
                                
                                $updated_seats = $visa_avail_data->visa_available + $visa_diff;
                                
                                DB::table('visa_Availability')->where('id',$prev_visa_res->visa_avail_id)->update([
                                        'visa_available' => $updated_seats
                                ]);
                                
                                // 3 Update Visa Supplier Balance
                                $visa_supplier_data = DB::table('visa_Sup')->where('id',$visa_avail_data->visa_supplier)->first();
                                
                                    // Calculate Visa Price Difference
                                    
                                    $new_visa_price_total = $prev_visa_res->visa_fee_purchase * $prev_visa_res->visa_occupied;
                                    
                                    $price_difference = $new_visa_price_total;
                                
                                $visa_supplier_payable_balance = $visa_supplier_data->payable - $price_difference;
                                DB::table('visa_Sup')->where('id',$visa_avail_data->visa_supplier)->update([
                                        'payable' => $visa_supplier_payable_balance
                                ]);
                                
                                DB::table('visa_supplier_ledger')->insert([
                                        'supplier_id' => $visa_avail_data->visa_supplier,
                                        'payment' => $price_difference,
                                        'balance' => $visa_supplier_data->balance,
                                        'payable' => $visa_supplier_payable_balance,
                                        'visa_qty' => $visa_diff,
                                        'visa_type' => $visa_avail_data->visa_type,
                                        'invoice_no' => $insert->id,
                                        'visa_avl_id' => $visa_avail_data->id,
                                        'remarks' => 'Invoice Updated',
                                        'date' => date('Y-m-d'),
                                        'customer_id' => $req->customer_id,
                                ]);
                                
                                DB::table('visa_Sup')->where('id',$visa_avail_data->visa_supplier)->update([
                                        'payable' => $visa_supplier_payable_balance
                                ]); 
                        }
                    }
                 
                //  die;
                    DB::commit();
                 
            } catch (Throwable $e) {
                echo $e;
                DB::rollback();
                return response()->json(['message'=>'error','booking_id'=> '']);
            }
            return response()->json(['status'=>'success','message'=>'Agent Invoice Updated Succesfully']); 
        }else{
            return response()->json(['status'=>'error','message'=>$id]); 
        }
    }
    
    public function get_TranferSuppliers(Request $req){
        $tranfer_supplier   = DB::table('transfer_Invoice_Supplier')
                                ->where('transfer_Invoice_Supplier.customer_id',$req->customer_id)
                                ->where('transfer_Invoice_Supplier.id',$req->id)
                                ->first();
                                
        $vehicle_details    = DB::table('tranfer_destination')
                                ->where('tranfer_destination.customer_id',$req->customer_id)
                                ->select('vehicle_details')
                                // ->whereJsonContains('vehicle_details',['transfer_supplier_Id'])
                                ->get();
                                
                            // ->whereJsonContains('services',['visa_tab'])    
                            
        return response()->json([
            'tranfer_supplier'  => $tranfer_supplier,
            'vehicle_details'   => $vehicle_details,
        ]);
    }
    
    public function add_Invoices_TranSupp(Request $req){
        // Data
            $insert = new addManageInvoice();
            //1
            $insert->customer_id             = $req->customer_id;
            $insert->services                = $req->services;
            $insert->agent_Id                = $req->agent_Id;
            $insert->agent_Name              = $req->agent_Name;
            $insert->prefix                  = $req->prefix;
            $insert->f_name                  = $req->f_name;
            $insert->middle_name             = $req->middle_name;
            $insert->currency_conversion     = $req->currency_conversion;
            $insert->conversion_type_Id      = $req->conversion_type_Id;
            $insert->city_Count              = $req->city_Count;
            
            // $insert->data_of_birth                  = $req->data_of_birth;
            // $insert->passport_no                   = $req->passport_no;
            // $insert->passport_expiry_date        = $req->passport_expiry_date;
            
            
            $insert->gender                  = $req->gender;
            $insert->email                   = $req->email;
            $insert->contact_landline        = $req->contact_landline;
            $insert->mobile                  = $req->mobile;
            $insert->contact_work            = $req->contact_work;
            $insert->postCode                = $req->postCode;
            $insert->country                 = $req->country;
            $insert->city                    = $req->city;
            $insert->primery_address         = $req->primery_address;
            // $insert->quotation_prepared      = $req->quotation_prepared;
            // $insert->quotation_valid_date    = $req->quotation_valid_date;
            //2
            $insert->adults                  = $req->adults;
            $insert->childs                  = $req->childs;
            $insert->infant                  = $req->infant;
            $insert->passengerDetailAdults   = $req->passengerDetailAdults;
            $insert->passengerDetailChilds   = $req->passengerDetailChilds;
            $insert->passengerDetailInfant   = $req->passengerDetailInfant;
            // Package_Data
            $generate_id                            = rand(0,9999999);
            $insert->generate_id                    = $generate_id;
            $insert->title                          = $req->title;
            $insert->external_packages              = '';
            $insert->content                        = $req->content;
            $insert->categories                     = $req->tour_categories;
            $insert->tour_attributes                = $req->tour_attributes;
            $insert->no_of_pax_days                 = $req->no_of_pax_days;
            $insert->destination_details            = $req->destination_details;
            $insert->destination_details_more       = $req->destination_details_more;
            $insert->flights_details                = $req->flights_details;
            $insert->flights_details_more           = $req->more_flights_details;
            $insert->accomodation_details           = $req->accomodation_details;
            $insert->accomodation_details_more      = $req->more_accomodation_details;
            $insert->transportation_details         = $req->transportation_details;
            $insert->transportation_details_more    = $req->transportation_details_more;
            
            $insert->visa_type                      = $req->visa_type;
            $insert->visa_fee                       = $req->visa_fee;
            $insert->visa_Pax                       = $req->visa_Pax;
            $insert->exchange_rate_visaI            = $req->exchange_rate_visaI;
            $insert->total_visa_markup_value        = $req->total_visa_markup_value;
            $insert->exchange_rate_visa_fee         = $req->exchange_rate_visa_fee;
            
            $insert->more_visa_details              = $req->more_visa_details;
            $insert->visa_rules_regulations         = $req->visa_rules_regulations;
            $insert->visa_image                     = $req->visa_image;
            
            $insert->without_markup_total_price_visa    = $req->without_markup_total_price_visa;
            $insert->markup_total_price_visa            = $req->markup_total_price_visa;
            
            $insert->quad_grand_total_amount        = $req->quad_grand_total_amount;
            $insert->triple_grand_total_amount      = $req->triple_grand_total_amount;
            $insert->double_grand_total_amount      = $req->double_grand_total_amount;
            $insert->quad_cost_price                = $req->quad_cost_price;
            $insert->triple_cost_price              = $req->triple_cost_price;
            $insert->double_cost_price              = $req->double_cost_price;
            $insert->all_markup_type                = $req->all_markup_type;
            $insert->all_markup_add                 = $req->all_markup_add;
            $insert->gallery_images                 = $req->gallery_images;
            $insert->start_date                     = $req->start_date;
            $insert->end_date=$req->end_date;
            $insert->time_duration=$req->time_duration;
            $insert->tour_location=$req->tour_location;
            $insert->whats_included=$req->whats_included;
            $insert->whats_excluded=$req->whats_excluded;
            $insert->currency_symbol=$req->currency_symbol;
            $insert->tour_publish=$req->defalut_state;
            $insert->tour_author=$req->tour_author;
            $insert->tour_feature=$req->tour_feature;
            $insert->defalut_state=$req->defalut_state;
            $insert->payment_gateways=$req->payment_gateways;
            $insert->payment_modes=$req->payment_modes;
            $insert->markup_details=$req->markup_details;
            $insert->more_markup_details=$req->more_markup_details;
            $insert->tour_featured_image=$req->tour_featured_image;
            $insert->tour_banner_image=$req->tour_banner_image;
            $insert->Itinerary_details=$req->Itinerary_details;
            $insert->tour_itinerary_details_1=$req->tour_itinerary_details_1;
            $insert->tour_extra_price=$req->tour_extra_price;
            $insert->tour_extra_price_1=$req->tour_extra_price_1;
            $insert->tour_faq=$req->tour_faq;
            $insert->tour_faq_1=$req->tour_faq_1;
            
            $insert->payment_messag=$req->checkout_message;
            
            $insert->total_Cost_Price_All=$req->total_Cost_Price_All;
            $insert->total_Sale_Price_All=$req->total_Sale_Price_All;
            
            $insert->option_date=$req->option_date;
            $insert->reservation_number=$req->reservation_number;
            $insert->hotel_reservation_number=$req->hotel_reservation_number;
            
            $insert->total_sale_price_all_Services=$req->total_sale_price_all_Services;
            $insert->total_cost_price_all_Services=$req->total_cost_price_all_Services;
            
            $insert->transfer_supplier      = $req->transfer_supplier;
            $insert->transfer_supplier_id   = $req->transfer_supplier_id;
            
            
            DB::beginTransaction();
            try {
                $insert->save();
                
                $invoice_id = $insert->id;
                $accomodation = json_decode($req->accomodation_details);
                $more_accomodation_details = json_decode($req->more_accomodation_details);
                
                if(isset($accomodation)){
                     foreach($accomodation as $accomodation_res){
                         
                         if(isset($accomodation_res->hotelRoom_type_idM)){
                             if($accomodation_res->hotelRoom_type_idM != null && $accomodation_res->hotelRoom_type_idM != ''){
                        //   echo "Enter here ";
                                $room_data = DB::table('rooms')->where('id',$accomodation_res->hotelRoom_type_id)->first();
                                
                                if($room_data){
                                    $rooms_more_data = json_decode($room_data->more_room_type_details);
                                    // print_r($rooms_more_data);
                                    
                                    $total_booked = 0;
                                    $booking_main_data = [];
                                    foreach($rooms_more_data as $key => $room_more_res){
                                        // echo "The room gen ".$room_more_res->room_gen_id." and request is ".$request->generate_id;
                                        if($room_more_res->room_gen_id == $accomodation_res->hotelRoom_type_idM){
                                            // print_r($room_more_res);
                                            $total_booked = $room_more_res->more_quantity_booked + $accomodation_res->acc_qty;
                                            
                                            
                                            if(!empty($room_more_res->more_booking_details) && $room_more_res->more_booking_details !== null){
                                                $booking_main_data = json_decode($room_more_res->more_booking_details);
                                            }
                                            
                                             $booking_details = ['booking_from'=>'Invoices',
                                             'quantity'=>$accomodation_res->acc_qty,
                                             'booking_id'=>$invoice_id,
                                             'date'=>date('Y-m-d'),
                                             'check_in'=>$accomodation_res->acc_check_in,
                                             'check_out'=>$accomodation_res->acc_check_out,
                                             ];
                                            array_push($booking_main_data,$booking_details);
                                            $booking_main_data = json_encode($booking_main_data);
                                            
                                            $rooms_more_data[$key]->more_quantity_booked = $total_booked;
                                            $rooms_more_data[$key]->more_booking_details = $booking_main_data;
                                            // echo "total booked rooms is $total_booked";
                                        }
                                    }
                                    
                                    // print_r($rooms_more_data);
                                    
                                    
                                    // die;
                             
                                    DB::table('rooms')->where('id',$accomodation_res->hotelRoom_type_id)->update(['more_room_type_details'=>$rooms_more_data]);
                                }
                        
                        
                        
                        
                            }else{
                                    // die;
                                    $room_data = DB::table('rooms')->where('id',$accomodation_res->hotelRoom_type_id)->first();
                                    
                                    if($room_data){
                                        
                                        
                                        $total_booked = $room_data->booked + $accomodation_res->acc_qty;
                                        
                                        $booking_main_data = [];
                                        if(!empty($room_data->booking_details) && $room_data->booking_details !== null){
                                            $booking_main_data = json_decode($room_data->booking_details);
                                        }
                                        
                                       
                                        
                                          $booking_details = ['booking_from'=>'Invoices',
                                                 'quantity'=>$accomodation_res->acc_qty,
                                                 'booking_id'=>$invoice_id,
                                                 'date'=>date('Y-m-d'),
                                                 'check_in'=>$accomodation_res->acc_check_in,
                                                 'check_out'=>$accomodation_res->acc_check_out,
                                                 ];
                                                 
                                        array_push($booking_main_data,$booking_details);
                                        // print_r($booking_main_data);
                                        $booking_main_data = json_encode($booking_main_data);
                                        DB::table('rooms')->where('id',$accomodation_res->hotelRoom_type_id)->update(['booked'=>$total_booked,'booking_details'=>$booking_main_data]);
                                
                                    }
                                }
                         }
                      
                     }
                    
                }
                
                if(isset($more_accomodation_details)){
                    // print_r($more_accomodation_details);
                    
                     foreach($more_accomodation_details as $accomodation_res){
                         if(isset($accomodation_res->more_hotelRoom_type_idM)){
                             if($accomodation_res->more_hotelRoom_type_idM != null && $accomodation_res->more_hotelRoom_type_idM != ''){
                            //   echo "Enter here ";
                            $room_data = DB::table('rooms')->where('id',$accomodation_res->more_hotelRoom_type_id)->first();
                            
                                if($room_data){
                                    $rooms_more_data = json_decode($room_data->more_room_type_details);
                                    // print_r($rooms_more_data);
                                    
                                    $total_booked = 0;
                                    $booking_main_data = [];
                                    foreach($rooms_more_data as $key => $room_more_res){
                                        // echo "The room gen ".$room_more_res->room_gen_id." and request is ".$request->generate_id;
                                        if($room_more_res->room_gen_id == $accomodation_res->more_hotelRoom_type_idM){
                                            // print_r($room_more_res);
                                            $total_booked = $room_more_res->more_quantity_booked + $accomodation_res->more_acc_qty;
                                            
                                            
                                            if(!empty($room_more_res->more_booking_details) && $room_more_res->more_booking_details !== null){
                                                $booking_main_data = json_decode($room_more_res->more_booking_details);
                                            }
                                            
                                             $booking_details = ['booking_from'=>'Invoices',
                                             'quantity'=>$accomodation_res->more_acc_qty,
                                             'booking_id'=>$invoice_id,
                                             'date'=>date('Y-m-d'),
                                             'check_in'=>$accomodation_res->more_acc_check_in,
                                             'check_out'=>$accomodation_res->more_acc_check_out,
                                             ];
                                            array_push($booking_main_data,$booking_details);
                                            $booking_main_data = json_encode($booking_main_data);
                                            
                                            $rooms_more_data[$key]->more_quantity_booked = $total_booked;
                                            $rooms_more_data[$key]->more_booking_details = $booking_main_data;
                                            // echo "total booked rooms is $total_booked";
                                        }
                                    }
                                    
                                    // echo "More";
                                    // print_r($rooms_more_data);
                                    
                                    
                                    // die;
                             
                                    DB::table('rooms')->where('id',$accomodation_res->more_hotelRoom_type_id)->update(['more_room_type_details'=>$rooms_more_data]);
                                
                                }
                            
                             }else{
                                    // die;
                                    $room_data = DB::table('rooms')->where('id',$accomodation_res->more_hotelRoom_type_id)->first();
                                    
                                    if($room_data){
                                        
                                   
                                        $total_booked = $room_data->booked + $accomodation_res->more_acc_qty;
                                        
                                        $booking_main_data = [];
                                        if(!empty($room_data->booking_details) && $room_data->booking_details !== null){
                                            $booking_main_data = json_decode($room_data->booking_details);
                                        }
                                        
                                       
                                        
                                          $booking_details = ['booking_from'=>'Invoices',
                                                 'quantity'=>$accomodation_res->more_acc_qty,
                                                 'booking_id'=>$invoice_id,
                                                 'date'=>date('Y-m-d'),
                                                 'check_in'=>$accomodation_res->more_acc_check_in,
                                                 'check_out'=>$accomodation_res->more_acc_check_out,
                                                 ];
                                                 
                                        array_push($booking_main_data,$booking_details);
                                        
                                        // print_r($booking_main_data);
                                        $booking_main_data = json_encode($booking_main_data);
                                        DB::table('rooms')->where('id',$accomodation_res->more_hotelRoom_type_id)->update(['booked'=>$total_booked,'booking_details'=>$booking_main_data]);
                                    }
                                }
                         }
                          
                     }
                
                }
                
                DB::commit();
                return response()->json(['status'=>'success','message'=>'Agent Invoice added Succesfully']); 
            
            } catch (\Exception $e) {
                DB::rollback();
                echo $e;
                return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
            }
    }
    
    public function add_more_passenger_Invoice(Request $req){
        // if($req->type == 'tours'){
            // return response()->json(['status'=>'success','message'=>'More Passengers of Tours added Successfully']); 
        // }elseif($req->type == 'Invoice'){
        
            // print_r($req->all());
            if($req->request_form == 'leadPassenger'){
                try {
                   $insert = DB::table('add_manage_invoices')->where('id',$req->invoice_Id_Input)->update([
                        'lead_fname'            => $req->lead_fname,
                        'f_name'                => $req->lead_fname,
                        'lead_lname'            => $req->lead_lname,
                        'middle_name'            => $req->lead_lname,
                        'lead_gender'           => $req->lead_gender,
                        'lead_dob'              => $req->lead_dob,
                        'email'                  => $req->lead_email,
                        'lead_nationality'      => $req->lead_nationality,
                        'contact_landline'      => $req->contact_landline,
                        'lead_passport_number'  => $req->lead_passport_number,
                        'lead_passport_expiry'  => $req->lead_passport_expiry,
                        'lead_visa_Type'        => $req->lead_visa_Type,
                    ]);
                    // echo $insert;
                    return response()->json(['status'=>'success','message'=>'More Passengers of Invoice added Successfully']);
                } catch (Throwable $e) {
                    echo $e;
             
                    // return false;
                }
                 
                
                
                
                
            }else if($req->request_form == 'otherPassenger'){
                $request_data = json_decode($req->request_data);
                $others_passengers = DB::table('add_manage_invoices')->where('id',$request_data->invoice_id)->select('more_Passenger_Data')->first();
                $others_passengers = $others_passengers->more_Passenger_Data;
                $passenger_data =  ["more_p_fname" => $request_data->passengerName,
                                     "more_p_lname" => $request_data->lname,
                                     "more_p_gender" => $request_data->gender,
                                     "more_p_dob" => $request_data->date_of_birth,
                                     "more_p_nationality" => $request_data->country,
                                     "more_p_passport_number" => $request_data->passport_lead,
                                     "more_p_passport_expiry" => $request_data->passport_exp_lead,
                                     "more_p_passport" => '',
                                     "more_p_image" => '',
                                     "more_p_visa_Type" => ""];
                                 
                if(!is_null($others_passengers)){
                    $others_passengers = json_decode($others_passengers);
                }else{
                    $others_passengers = [];
                }
                
                array_push($others_passengers,$passenger_data);
                $others_passengers = json_encode($others_passengers);
                
                 try {
                   $update = DB::table('add_manage_invoices')->where('id',$request_data->invoice_id)->update([
                        'more_Passenger_Data' => $others_passengers,
                    ]);
                    // echo $update;
                    return response()->json(['status'=>'success','message'=>'More Passengers of Invoice added Successfully']);
                } catch (Throwable $e) {
                    echo $e;
             
                    // return false;
                }
            }else if($req->request_form == 'updatePassenger'){
                $request_data = json_decode($req->request_data);
                $others_passengers = DB::table('add_manage_invoices')->where('id',$request_data->invoice_id)->select('more_Passenger_Data')->first();
                 $others_passengers = $others_passengers->more_Passenger_Data;
                $passenger_data =  ["more_p_fname" => $request_data->passengerName,
                                     "more_p_lname" => $request_data->lname,
                                     "more_p_gender" => $request_data->gender,
                                     "more_p_dob" => $request_data->date_of_birth,
                                     "more_p_nationality" => $request_data->country,
                                     "more_p_passport_number" => $request_data->passport_lead,
                                     "more_p_passport_expiry" => $request_data->passport_exp_lead,
                                     "more_p_passport" => '',
                                     "more_p_image" => '',
                                     "more_p_visa_Type" => ""
                                     ];


                if(!is_null($others_passengers)){
                    $others_passengers = json_decode($others_passengers);
                }else{
                    $others_passengers = [];
                }
               
                $others_passengers[$request_data->index - 2] = $passenger_data;
                $others_passengers = json_encode($others_passengers);
                
                 try {
                     $update = DB::table('add_manage_invoices')->where('id',$request_data->invoice_id)->update([
                        'more_Passenger_Data'        => $others_passengers,
                    ]);
                    return response()->json(['status'=>'success','message'=>'More Passengers of Invoice Updated Successfully']);
                } catch (Throwable $e) {
                    echo $e;
             
                    // return false;
                }
            }
           
        // }else{
            // return response()->json(['status'=>'error','message'=>'CHECK REQUEST!']);
        // }  
    }
    
    public function get_single_Invoice(Request $req){
        // if($req->type == 'Invoice'){
            $data1                          = addManageInvoice::where('customer_id',$req->customer_id)->where('id',$req->id)->first();
            $invoice_Agent_Payment_data     = DB::table('pay_Invoice_Agent')->where('customer_id',$req->customer_id)->where('invoice_Id',$req->id)->get();
            $outStanding_Invoice_Agent      = DB::table('pay_Invoice_Agent')->where('customer_id',$req->customer_id)->where('invoice_Id',$req->id)->sum('amount_Paid');
            $total_Invoice_Agent            = DB::table('pay_Invoice_Agent')->where('customer_id',$req->customer_id)->where('invoice_Id',$req->id)->first();
            return response()->json([
                'data1'                         => $data1,
                'invoice_Agent_Payment_data'    => $invoice_Agent_Payment_data,
                'outStanding_Invoice_Agent'     => $outStanding_Invoice_Agent,
                'total_Invoice_Agent'           => $total_Invoice_Agent,
            ]);
        // }else if($req->type == 'Tour_Booking'){
        //     $data1 = DB::table('tours_bookings')->where('customer_id',$req->customer_id)->where('id',$req->id)->first();
        //     return response()->json([
        //         'data1' => $data1, 
        //     ]);
        // }else{
        //     return response()->json([
        //         'message' => 'error',
        //     ]);
        // }
    }
    
    public function get_flights_selected_supplier(Request $req){
        $data     = DB::table('flight_rute')->where('customer_id',$req->customer_id)->where('dep_supplier',$req->supp_Id)->get();
        return response()->json([
            'data'      => $data,
        ]); 
    }
    
    public function get_flights_all_routes(Request $req){
        $data     = DB::table('flight_rute')->where('customer_id',$req->customer_id)->get();
        return response()->json([
            'data'      => $data,
        ]); 
    }
    
    public function get_flights_selected_route(Request $req){
        $data     = DB::table('flight_rute')->where('customer_id',$req->customer_id)->where('id',$req->SR_Id)->get();
        return response()->json([
            'data'      => $data,
        ]); 
    }
    
    public function get_all_transfer_destinations(Request $req){
        $data     = DB::table('tranfer_destination')->where('customer_id',$req->customer_id)->get();
        return response()->json([
            'data'      => $data,
        ]); 
    }
    
    public function get_flights_occupied_seats(Request $req){
        // dd($req->all());
        $data       = DB::table('flight_seats_occupied')
                        ->where('token',$req->token)
                        ->where('flight_supplier',$req->supp_Id)
                        ->where('flight_route_id',$req->route_Id)
                        ->sum('flight_route_seats_occupied');
        $dataId     = DB::table('flight_seats_occupied')
                        ->where('flight_supplier',$req->supp_Id)
                        ->where('flight_route_id',$req->route_Id)
                        ->where('booking_id',$req->booking_id)
                        ->where('type',$req->I_type)->select('booking_id','flight_route_id','flight_route_seats_occupied')->first();
        return response()->json([
            'data'      => $data,
            'dataId'    => $dataId,
        ]); 
    }
    
    public function get_flights_occupied_seats_edit(Request $req){
        // dd($req->all());
        $data       = DB::table('flight_seats_occupied')
                        ->where('flight_supplier',$req->supp_Id)
                        ->where('flight_route_id',$req->route_Id)
                        ->where('type',$req->I_type)
                        ->sum('flight_route_seats_occupied');
        $dataId     = DB::table('flight_seats_occupied')
                        ->where('flight_supplier',$req->supp_Id)
                        ->where('flight_route_id',$req->route_Id)
                        ->where('booking_id',$req->booking_id)
                        ->where('type',$req->I_type)->select('booking_id','flight_route_id','flight_route_seats_occupied')->first();
        return response()->json([
            'data'      => $data,
            'dataId'    => $dataId,
        ]); 
    }
    
    // End Invoice
    
    public function update_Invoices_TranSupp(Request $req){
        $id     = $req->id;
        $insert = addManageInvoice::find($id);
        if($insert)
        {
            //1
            $insert->customer_id             = $req->customer_id;
            $insert->services                = $req->services;
            $insert->agent_Id                = $req->agent_Id;
            $insert->agent_Name              = $req->agent_Name;
            $insert->prefix                  = $req->prefix;
            $insert->f_name                  = $req->f_name;
            $insert->middle_name             = $req->middle_name;
            $insert->currency_conversion     = $req->currency_conversion;
            $insert->conversion_type_Id      = $req->conversion_type_Id;
             
            $insert->gender                  = $req->gender;
            $insert->email                   = $req->email;
            $insert->contact_landline        = $req->contact_landline;
            $insert->mobile                  = $req->mobile;
            $insert->contact_work            = $req->contact_work;
            $insert->postCode                = $req->postCode;
            $insert->country                 = $req->country;
            $insert->city                    = $req->city;
            $insert->primery_address         = $req->primery_address;
            // $insert->quotation_prepared      = $req->quotation_prepared;
            // $insert->quotation_valid_date    = $req->quotation_valid_date;
            //2
            $insert->adults                  = $req->adults;
            $insert->childs                  = $req->childs;
            $insert->infant                  = $req->infant;
            // Package_Data
            $generate_id                            = rand(0,9999999);
            $insert->generate_id                    = $generate_id;
            $insert->title                          = $req->title;
            $insert->external_packages              = '';
            $insert->content                        = $req->content;
            $insert->categories                     = $req->tour_categories;
            $insert->tour_attributes                = $req->tour_attributes;
            $insert->no_of_pax_days                 = $req->no_of_pax_days;
            $insert->destination_details            = $req->destination_details;
            $insert->destination_details_more       = $req->destination_details_more;
            $insert->flights_details                = $req->flights_details;
            $insert->flights_details_more           = $req->more_flights_details;
            $insert->accomodation_details           = $req->accomodation_details;
            $insert->accomodation_details_more      = $req->more_accomodation_details;
            $insert->transportation_details         = $req->transportation_details;
            $insert->transportation_details_more    = $req->transportation_details_more;
            
            $insert->visa_type                      = $req->visa_type;
            $insert->visa_fee                       = $req->visa_fee;
            $insert->visa_Pax                       = $req->visa_Pax;
            $insert->exchange_rate_visaI            = $req->exchange_rate_visaI;
            $insert->total_visa_markup_value        = $req->total_visa_markup_value;
            $insert->exchange_rate_visa_fee         = $req->exchange_rate_visa_fee;
            
            $insert->more_visa_details              = $req->more_visa_details;
            $insert->visa_rules_regulations         = $req->visa_rules_regulations;
            $insert->visa_image                     = $req->visa_image;
            
            $insert->without_markup_total_price_visa    = $req->without_markup_total_price_visa;
            $insert->markup_total_price_visa            = $req->markup_total_price_visa;
            
            $insert->quad_grand_total_amount        = $req->quad_grand_total_amount;
            $insert->triple_grand_total_amount      = $req->triple_grand_total_amount;
            $insert->double_grand_total_amount      = $req->double_grand_total_amount;
            $insert->quad_cost_price                = $req->quad_cost_price;
            $insert->triple_cost_price              = $req->triple_cost_price;
            $insert->double_cost_price              = $req->double_cost_price;
            $insert->all_markup_type                = $req->all_markup_type;
            $insert->all_markup_add                 = $req->all_markup_add;
            $insert->gallery_images                 = $req->gallery_images;
            $insert->start_date                     = $req->start_date;
            $insert->end_date=$req->end_date;
            $insert->time_duration=$req->time_duration;
            $insert->tour_location=$req->tour_location;
            $insert->whats_included=$req->whats_included;
            $insert->whats_excluded=$req->whats_excluded;
            $insert->currency_symbol=$req->currency_symbol;
            $insert->tour_publish=$req->defalut_state;
            $insert->tour_author=$req->tour_author;
            $insert->tour_feature=$req->tour_feature;
            $insert->defalut_state=$req->defalut_state;
            $insert->payment_gateways=$req->payment_gateways;
            $insert->payment_modes=$req->payment_modes;
            $insert->markup_details=$req->markup_details;
            $insert->more_markup_details=$req->more_markup_details;
            $insert->tour_featured_image=$req->tour_featured_image;
            $insert->tour_banner_image=$req->tour_banner_image;
            $insert->Itinerary_details=$req->Itinerary_details;
            $insert->tour_itinerary_details_1=$req->tour_itinerary_details_1;
            $insert->tour_extra_price=$req->tour_extra_price;
            $insert->tour_extra_price_1=$req->tour_extra_price_1;
            $insert->tour_faq=$req->tour_faq;
            $insert->tour_faq_1=$req->tour_faq_1;
            
            $insert->payment_messag=$req->checkout_message;
            
            $insert->total_Cost_Price_All=$req->total_Cost_Price_All;
            $insert->total_Sale_Price_All=$req->total_Sale_Price_All;
            
            $insert->option_date=$req->option_date;
            $insert->reservation_number=$req->reservation_number;
            $insert->hotel_reservation_number=$req->hotel_reservation_number;
            
            $insert->total_sale_price_all_Services=$req->total_sale_price_all_Services;
            
            $insert->transfer_supplier      = $req->transfer_supplier;
            $insert->transfer_supplier_id   = $req->transfer_supplier_id;
            
            $insert->update();
            return response()->json(['status'=>'success','message'=>'Agent Invoice Updated Succesfully']); 
        }else{
            return response()->json(['status'=>'error','message'=>$id]); 
        }
    }
    
    public function confirm_invoice_Agent(Request $request){
        $id             = $request->id;
        $Invoice_detail = addManageInvoice::find($id);
        if($Invoice_detail)
        {
            $Invoice_detail->confirm = 1;
            $Invoice_detail->update();
            return response()->json(['Success'=>'Invoice Confirmed Successful!']);
        }
        else{
            return response()->json(['Invoice_detail'=>$Invoice_detail,'Error'=>'Agents Not Updated!']);
        }
    }
    
    public function invoice_Agent(Request $req){
        $data1                      = addManageInvoice::find($req->id);
        $customer_data              = DB::table('customer_subcriptions')->where('id',$req->customer_id)->first();
        $contact_details            = DB::table('contact_details')->where('customer_id',$req->customer_id)->first();
        $invoice_P_details          = DB::table('pay_Invoice_Agent')->where('customer_id',$req->customer_id)->where('invoice_Id',$req->id)->get();
        $total_invoice_Payments     = DB::table('pay_Invoice_Agent')->where('customer_id',$req->customer_id)->where('invoice_Id',$req->id)->first();
        $recieved_invoice_Payments  = DB::table('pay_Invoice_Agent')->where('customer_id',$req->customer_id)->where('invoice_Id',$req->id)->sum('amount_paid');
        $remainig_invoice_Payments  = DB::table('pay_Invoice_Agent')->where('customer_id',$req->customer_id)->where('invoice_Id',$req->id)->sum('remaining_amount');
         $all_countries  = country::all();
        return response()->json([
            'data1'                     => $data1,
            'customer_data'             => $customer_data,
            'contact_details'           => $contact_details,
            'invoice_P_details'         => $invoice_P_details,
            'total_invoice_Payments'    => $total_invoice_Payments,
            'recieved_invoice_Payments' => $recieved_invoice_Payments,
            'remainig_invoice_Payments' => $remainig_invoice_Payments,
            'all_countries' => $all_countries,
        ]);
   }
    
    public function get_rooms_list(Request $request){
        $customer_id    = $request->customer_id;
        $id             = $request->id;
        
    //   print_r($request->all());
    //   die;
    
        $rooms_types = DB::table('rooms_types')->where('customer_id',$customer_id)->get();
        $rooms_suppliers = DB::table('rooms_Invoice_Supplier')->where('customer_id',$customer_id)->get();
        
        $user_rooms     = DB::table('rooms')->where('hotel_id',$id)
                                ->where('owner_id',$customer_id)
                                ->where('availible_from','<=', $request->check_in)
                                ->where('availible_to','>=', $request->check_out)
                                ->get();
        return response()->json(['status'=>'success','user_rooms'=>$user_rooms,'rooms_types'=>$rooms_types,'rooms_suppliers'=>$rooms_suppliers]); 
    }
    
    public function get_invoiceRoomSupplier_detail(Request $request){
        $customer_id        = $request->customer_id;
        $RI_suppliers       = DB::table('rooms_Invoice_Supplier')->where('customer_id',$customer_id)->get();
        return response()->json(['status'=>'success','RI_suppliers'=>$RI_suppliers]); 
    }
    
    public function submit_invoiceRoomSupplier(Request $request){
        $customer_id        = $request->customer_id;
        $room_supplier_name = $request->room_supplier_name;
        $RI_suppliers_add   = DB::table('rooms_Invoice_Supplier')->insert(['room_supplier_name'=>$room_supplier_name,'customer_id'=>$request->customer_id]);
        $RI_suppliers       = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->get();
        return response()->json(['success'=>'Room Invoice Suppliers Added SuccessFUl!','RI_suppliers'=>$RI_suppliers]);
    } 
    
    public function get_hotels_list(Request $request){
        $customer_id        = $request->customer_id;
        // $start_date         = $request->start_date;
        // $enddate            = $request->enddate;
        $property_city_new  = $request->property_city_new;
        $user_hotels    = DB::table('hotels')->where('owner_id',$customer_id)->where('property_city',$property_city_new)->get();
        return response()->json(['status'=>'success','user_hotels'=>$user_hotels]); 
    }
    
    public function pay_invoice_Agent(Request $request){
        // dd($request);
        $data1                      = DB::table('add_manage_invoices')->where('id',$request->id)->get();
        $amount_Paid                = DB::table('pay_Invoice_Agent')->where('invoice_Id',$request->id)->sum('amount_Paid');
        return response()->json([
            'data1'         => $data1,
            'amount_Paid'   => $amount_Paid,
        ]);
    }
    
    public function recieve_invoice_Agent(Request $req){
        
        DB::beginTransaction();
        try {
                $invoice_data       = DB::table('add_manage_invoices')->where('id',$req->invoice_Id)->first();
                
                $total_amount       = $invoice_data->total_sale_price_all_Services;
                $paid_amount        = $invoice_data->total_paid_amount;
                $over_paid_amount   = $invoice_data->over_paid_amount;
                
                $total_paid_amount  = $paid_amount + $req->recieved_Amount;
                $total_over_paid    = 0;
                $over_paid_am       = 0;
                
                if($total_paid_amount > $total_amount){
                    $over_paid_am       = $total_paid_amount - $total_amount;
                    $total_over_paid    = $over_paid_amount + $over_paid_am;
                    $total_paid_amount  = $total_paid_amount - $over_paid_am;
                }
                
                DB::table('add_manage_invoices')->where('id',$req->invoice_Id)->update([
                    'total_paid_amount' => $total_paid_amount,
                    'over_paid_amount'  => $total_over_paid,
                ]);
                
                $agent_data         = DB::table('Agents_detail')->where('id',$invoice_data->agent_Id)->select('id','over_paid_am')->first();
                $agent_over_paid    = $agent_data->over_paid_am + $over_paid_am;
                
                DB::table('Agents_detail')->where('id',$agent_data->id)->update(['over_paid_am'=>$agent_over_paid]);
    
                $insert                     = new pay_Invoice_Agent();
                $insert->invoice_Id         = $req->invoice_Id;
                $insert->generate_id        = $req->generate_id;
                $insert->customer_id        = $req->customer_id;
                $insert->agent_Name         = $req->agent_Name;
                $insert->date               = $req->date;
                $insert->passenger_Name     = $req->passenger_Name;
                $insert->total_Amount       = $req->total_Amount;
                $insert->recieved_Amount    = $req->recieved_Amount;
                $insert->remaining_Amount   = $req->remaining_Amount;
                $insert->amount_Paid        = $req->amount_Paid;
                $insert->save();
                
                $notification_insert                            = new alhijaz_Notofication();
                $notification_insert->type_of_notification_id   = $insert->id ?? ''; 
                $notification_insert->customer_id               = $insert->customer_id ?? ''; 
                $notification_insert->type_of_notification      = 'pay_Invoice' ?? ''; 
                $notification_insert->generate_id               = $insert->generate_id ?? '';
                $notification_insert->notification_creator_name = $req->agent_Name ?? '';
                $notification_insert->total_price               = $insert->total_Amount ?? ''; 
                $notification_insert->amount_paid               = $insert->amount_Paid ?? ''; 
                $notification_insert->remaining_price           = $insert->remaining_Amount ?? ''; 
                $notification_insert->notification_status       = '1' ?? ''; 
                $notification_insert->save();
                
                DB::table('agents_ledgers')->insert([
                    'agent_id'=>$agent_data->id,
                    'payment_id'=>$insert->id,
                    "payment"=>$req->recieved_Amount,
                    "invoice_no"=>$invoice_data->id,
                    "total_amount"=>$total_amount,
                    "paid_amount"=>$total_paid_amount,
                    "remaining_amount"=>$total_amount - $total_paid_amount,
                    'over_paid'=>$agent_over_paid,
                    'date'=>$req->date,
                  
                 ]);
                
                DB::commit();
            
                return response()->json([
                    'message' => 'Success',
                ]);
                
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
        }
    }
    
    public function payable_ledger(Request $req){
        $customer_id        = $req->customer_id;
        $join_data          = DB::table('add_manage_invoices')
                                ->join('pay_Invoice_Agent','add_manage_invoices.id','pay_Invoice_Agent.invoice_Id')
                                ->where('add_manage_invoices.customer_id',$customer_id)
                                ->get();
                        
        $invoice_data       = DB::table('add_manage_invoices')->where('add_manage_invoices.customer_id',$customer_id)->get();
        $payment_data       = DB::table('pay_Invoice_Agent')->where('pay_Invoice_Agent.customer_id',$customer_id)->orderBy('invoice_Id', 'ASC')->orderBy('pay_Invoice_Agent.created_at', 'ASC')->get();
        $total_Dues         = DB::table('add_manage_invoices')->where('add_manage_invoices.customer_id',$customer_id)->get();
        $total_Sdue         = DB::table('add_manage_invoices')
                                ->join('pay_Invoice_Agent','add_manage_invoices.id','pay_Invoice_Agent.invoice_Id')
                                ->where('add_manage_invoices.customer_id',$customer_id)->select('invoice_Id','amount_Paid')->get();
                                
        $invoice_payment_count = DB::table('pay_Invoice_Agent')->where('pay_Invoice_Agent.customer_id',$customer_id)
                                    ->select('invoice_Id')->groupBy('invoice_Id')->count();
                            
        return response()->json([
            'status'        => 'success',
            'join_data'     => $join_data,
            'invoice_data'  => $invoice_data,
            'payment_data'  => $payment_data,
            'total_Dues'    => $total_Dues,
            'total_Sdue'    => $total_Sdue,
            'invoice_payment_count' => $invoice_payment_count,
        ]);
    }
    
    public function receivAble_ledger(Request $req){
        $customer_id            = $req->customer_id;
        $join_data              = DB::table('add_manage_invoices')
                                    ->join('pay_Invoice_Agent','add_manage_invoices.id','pay_Invoice_Agent.invoice_Id')
                                    ->where('add_manage_invoices.customer_id',$customer_id)
                                    ->get();
                        
        $invoice_data           = DB::table('add_manage_invoices')->where('add_manage_invoices.customer_id',$customer_id)->get();
        $payment_data           = DB::table('pay_Invoice_Agent')->where('pay_Invoice_Agent.customer_id',$customer_id)->orderBy('invoice_Id', 'ASC')->orderBy('pay_Invoice_Agent.created_at', 'ASC')->get();
        $total_recieveAble      = DB::table('pay_Invoice_Agent')->where('pay_Invoice_Agent.customer_id',$customer_id)->sum('recieved_Amount');
        $total_Sdue             = DB::table('add_manage_invoices')
                                    ->join('pay_Invoice_Agent','add_manage_invoices.id','pay_Invoice_Agent.invoice_Id')
                                    ->where('add_manage_invoices.customer_id',$customer_id)->select('invoice_Id','amount_Paid')->get();
                                
        $invoice_payment_count  = DB::table('pay_Invoice_Agent')->where('pay_Invoice_Agent.customer_id',$customer_id)
                                    ->select('invoice_Id')->groupBy('invoice_Id')->count();
                                    
        // $jugar_count            = DB::table('pay_Invoice_Agent')->where('pay_Invoice_Agent.customer_id',$customer_id)->count();
        
        return response()->json([
            'status'                => 'success',
            'join_data'             => $join_data,
            'invoice_data'          => $invoice_data,
            'payment_data'          => $payment_data,
            'total_recieveAble'     => $total_recieveAble,
            'total_Sdue'            => $total_Sdue,
            'invoice_payment_count' => $invoice_payment_count,
            // 'jugar_count'           => $jugar_count,
        ]);
    }
    
    public function cash_ledger(Request $req){
        $customer_id        = $req->customer_id;
        $join_data          = DB::table('add_manage_invoices')
                                ->join('pay_Invoice_Agent','add_manage_invoices.id','pay_Invoice_Agent.invoice_Id')
                                ->where('add_manage_invoices.customer_id',$customer_id)
                                ->get();
                        
        $invoice_data       = DB::table('add_manage_invoices')->where('add_manage_invoices.customer_id',$customer_id)->get();
        $payment_data       = DB::table('pay_Invoice_Agent')->where('pay_Invoice_Agent.customer_id',$customer_id)->orderBy('invoice_Id', 'ASC')->orderBy('pay_Invoice_Agent.created_at', 'ASC')->get();
        $total_Dues         = DB::table('add_manage_invoices')->where('add_manage_invoices.customer_id',$customer_id)->get();
        $total_Sdue         = DB::table('add_manage_invoices')
                                ->join('pay_Invoice_Agent','add_manage_invoices.id','pay_Invoice_Agent.invoice_Id')
                                ->where('add_manage_invoices.customer_id',$customer_id)->select('invoice_Id','amount_Paid')->get();
                            
        return response()->json([
            'status'        => 'success',
            'join_data'     => $join_data,
            'invoice_data'  => $invoice_data,
            'payment_data'  => $payment_data,
            'total_Dues'    => $total_Dues,
            'total_Sdue'    => $total_Sdue,
        ]);
    }
    
    public function request_Invoices(Request $req){
        $customer_id=$req->customer_id;
        $categories=DB::table('categories')->where('customer_id',$customer_id)->get();
        $attributes=DB::table('attributes')->where('customer_id',$customer_id)->get();
        $customer=DB::table('customer_subcriptions')->where('id','!=',$customer_id)->get();
        $all_countries = country::all();
        $all_countries_currency = country::all();
        $bir_airports=DB::table('bir_airports')->get();
        $payment_gateways=DB::table('payment_gateways')->where('customer_id',$customer_id)->get();
        $payment_modes=DB::table('payment_modes')->where('customer_id',$customer_id)->get();
        $currency_Symbol = DB::table('customer_subcriptions')->where('id',$customer_id)->get();
        $Agents_detail  = DB::table('Agents_detail')->where('customer_id',$req->customer_id)->get();
        $user_hotels    = Hotels::where('owner_id',$customer_id)->get();
        return response()->json(['message'=>'success','user_hotels'=>$user_hotels,'Agents_detail'=>$Agents_detail,'currency_Symbol'=>$currency_Symbol,'customer'=>$customer,'attributes'=>$attributes,'categories'=>$categories,'all_countries'=>$all_countries,'all_countries_currency'=>$all_countries_currency,'bir_airports'=>$bir_airports,'payment_gateways'=>$payment_gateways,'payment_modes'=>$payment_modes]);
    }
    
    public function single_notification_detail(Request $request){
        $single_notification_detail = DB::table('alhijaz_Notofication')->where('id',$request->id)->first();
        return response()->json([
            'single_notification_detail' => $single_notification_detail,
        ]);
    }
    
    public function all_notification_detail(Request $request){
        $all_notification_detail = DB::table('alhijaz_Notofication')->where('alhijaz_Notofication.notification_status','1')->get();
        return response()->json([
            'all_notification_detail' => $all_notification_detail,
        ]);
    }
    
    public function agents_financial_stats(Request $request){
        
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            $agent_lists = DB::table('Agents_detail')->get();
            
            $all_agents_data = [];
            foreach($agent_lists as $agent_res){
                $agents_tour_booking    = DB::table('cart_details')->where('agent_name',$agent_res->id)->get();
                $agents_invoice_booking = DB::table('add_manage_invoices')->where('agent_Id',$agent_res->id)->select('total_sale_price_all_Services','total_paid_amount','over_paid_amount','accomodation_details','accomodation_details_more','markup_details','more_markup_details','no_of_pax_days','id','agent_Id','currency_symbol')->get();
                
                $booking_all_data = [];
                foreach($agents_tour_booking as $tour_res){
                 
                    $tours_costing = DB::table('tours_2')->where('tour_id',$tour_res->tour_id)->select('quad_cost_price','triple_cost_price','double_cost_price','without_acc_cost_price','child_grand_total_cost_price','infant_total_cost_price')->first();

                    $cart_all_data = json_decode($tour_res->cart_total_data);
                 
                    $grand_profit = 0;
                    $grand_cost = 0;
                    $grand_sale = 0;
                 
                    // Profit From Double Adults
                 
                        if($cart_all_data->double_adults > 0){
                            $double_adult_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_adults;
                            $double_profit = $cart_all_data->double_adult_total - $double_adult_total_cost;
                            $grand_profit += $double_profit;
                            $grand_cost += $double_adult_total_cost;
                            $grand_sale += $cart_all_data->double_adult_total;
                        }
                     
                    // Profit From Triple Adults
                     
                        if($cart_all_data->triple_adults > 0){
                            $triple_adult_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_adults;
                            $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                            $grand_profit += $triple_profit;
                            $grand_cost += $triple_adult_total_cost;
                            $grand_sale += $cart_all_data->triple_adult_total;
                        }
                        
                    // Profit From Quad Adults
                     
                        if($cart_all_data->quad_adults > 0){
                            $quad_adult_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_adults;
                            $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                            $grand_profit += $quad_profit;
                            $grand_cost += $quad_adult_total_cost;
                            $grand_sale += $cart_all_data->quad_adult_total;
                        }
                     
                    // Profit From Without Acc
                     
                        if($cart_all_data->without_acc_adults > 0){
                            $without_acc_adult_total_cost = $tours_costing->without_acc_cost_price * $cart_all_data->without_acc_adults;
                            $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                            $grand_profit += $without_acc_profit;
                            $grand_cost += $without_acc_adult_total_cost;
                            $grand_sale += $cart_all_data->without_acc_adult_total;
                        }
                     
                    // Profit From Double Childs
                     
                        if($cart_all_data->double_childs > 0){
                            $double_child_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_childs;
                            $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                            $grand_profit += $double_profit;
                            $grand_cost += $double_child_total_cost;
                            $grand_sale += $cart_all_data->double_childs_total;
                        }
                     
                    // Profit From Triple Childs
                     
                       if($cart_all_data->triple_childs > 0){
                            $triple_child_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_childs;
                            $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                            $grand_profit += $triple_profit;
                            $grand_cost += $triple_child_total_cost;
                            $grand_sale += $cart_all_data->triple_childs_total;
                        }
                        
                    // Profit From Quad Childs
                     
                        if($cart_all_data->quad_childs > 0){
                            $quad_child_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_childs;
                            $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                            $grand_profit += $quad_profit;
                            $grand_cost += $quad_child_total_cost;
                            $grand_sale += $cart_all_data->quad_child_total;
                        }
                     
                    // Profit From Without Acc Child

                        if($cart_all_data->children > 0){
                            $without_acc_child_total_cost = $tours_costing->child_grand_total_cost_price * $cart_all_data->children;
                            $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                            $grand_profit += $without_acc_profit;
                            $grand_cost += $without_acc_child_total_cost;
                            $grand_sale += $cart_all_data->without_acc_child_total;
                        }

                    // Profit From Double Infant
                    
                        if($cart_all_data->double_infant > 0){
                            $double_infant_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_infant;
                            $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                            $grand_profit += $double_profit;
                             $grand_cost += $double_infant_total_cost;
                            $grand_sale += $cart_all_data->double_infant_total;
                        }
                     
                    // Profit From Triple Infant
                     
                        if($cart_all_data->triple_infant > 0){
                            $triple_infant_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_infant;
                            $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                            $grand_profit += $triple_profit;
                             $grand_cost += $triple_infant_total_cost;
                            $grand_sale += $cart_all_data->triple_infant_total;
                        }
                     
                    // Profit From Quad Infant
                     
                        if($cart_all_data->quad_infant > 0){
                            $quad_infant_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_infant;
                            $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                            $grand_profit += $quad_profit;
                             $grand_cost += $quad_infant_total_cost;
                            $grand_sale += $cart_all_data->quad_infant_total;
                        }
                     
                    // Profit From Without Acc Infant  
                     
                        if($cart_all_data->infants > 0){
                            $without_acc_infant_total_cost = $tours_costing->infant_total_cost_price * $cart_all_data->infants;
                            $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                            $grand_profit += $without_acc_profit;
                            $grand_cost += $without_acc_infant_total_cost;
                            $grand_sale += $cart_all_data->without_acc_infant_total;
                      }
                      
                    $over_all_dis = 0;
                    
                    if($cart_all_data->discount_type == 'amount'){
                        $final_profit = $grand_profit - $cart_all_data->discount_enter_am;
                    }else{
                       $discunt_am_over_all = ($cart_all_data->price * $cart_all_data->discount_enter_am) / 100;
                       $final_profit = $grand_profit - $discunt_am_over_all;
                    }
                    
                    $commission_add = '';
                    if(isset($cart_all_data->agent_commsion_add_total)){
                        $commission_add = $cart_all_data->agent_commsion_add_total;
                    }

                    $booking_data = [
                            'invoice_id'=>$tour_res->invoice_no,
                            'booking_id'=>$tour_res->booking_id,
                            'tour_id'=>$tour_res->tour_id,
                            'price'=>$tour_res->tour_total_price,
                            'paid_amount'=>$tour_res->total_paid_amount,
                            'remaing_amount'=> $tour_res->tour_total_price - $tour_res->total_paid_amount,
                            'over_paid_amount'=> $tour_res->over_paid_amount,
                            'tour_name'=>$cart_all_data->name,
                            'profit'=>$final_profit,
                            'discount_am'=>$cart_all_data->discount_Price,
                            'total_cost'=>$grand_cost,
                            'total_sale'=>$grand_sale,
                            'commission_am'=>$cart_all_data->agent_commsion_am,
                            'agent_commsion_add_total'=>$commission_add,
                            'currency'=>$tour_res->currency,
                         ];
                    
                    array_push($booking_all_data,$booking_data);
                }
                
                $invoices_all_data = [];
                foreach($agents_invoice_booking as $agent_inv_res){
                    
                    $accomodation = json_decode($agent_inv_res->accomodation_details);
                    $accomodation_more = json_decode($agent_inv_res->accomodation_details_more);
                    $markup_details = json_decode($agent_inv_res->markup_details);
                    $more_markup_details = json_decode($agent_inv_res->more_markup_details);
                     
                    // Caluclate Flight Price 
                    $grand_cost = 0;
                    $grand_sale = 0;
                    $flight_cost = 0;
                    $flight_sale = 0;
                    foreach($markup_details as $mark_res){
                        if($mark_res->markup_Type_Costing == 'flight_Type_Costing'){
                            $flight_cost = $mark_res->without_markup_price; 
                            $flight_sale = $mark_res->markup_price; 
                        }
                    }
                            
                    $flight_total_cost = (float)$flight_cost * (float)$agent_inv_res->no_of_pax_days;
                    $flight_total_sale = (float)$flight_sale * (float)$agent_inv_res->no_of_pax_days;
                    
                    $flight_profit = $flight_total_sale - $flight_total_cost;
                    
                    $grand_cost += $flight_total_cost;
                    $grand_sale += $flight_total_sale;
                    
                    // Caluclate Visa Price 
                    $visa_cost = 0;
                    $visa_sale = 0;
                    foreach($markup_details as $mark_res){
                        if($mark_res->markup_Type_Costing == 'visa_Type_Costing'){
                            $visa_cost = $mark_res->without_markup_price; 
                            $visa_sale = $mark_res->markup_price; 
                        }
                    }
                    $visa_total_cost = (float)$visa_cost * (float)$agent_inv_res->no_of_pax_days;
                    $visa_total_sale = (float)$visa_sale * (float)$agent_inv_res->no_of_pax_days;
                    $visa_profit = $visa_total_sale - $visa_total_cost;
                    $grand_cost += $visa_total_cost;
                    $grand_sale += $visa_total_sale;

                    // Caluclate Transportation Price
                    $trans_cost = 0;
                    $trans_sale = 0;
                    foreach($markup_details as $mark_res){
                        if($mark_res->markup_Type_Costing == 'transportation_Type_Costing'){
                            $trans_cost = $mark_res->without_markup_price; 
                            $trans_sale = $mark_res->markup_price; 
                        }
                    }
                    $trans_total_cost = (float)$trans_cost * (float)$agent_inv_res->no_of_pax_days;
                    $trans_total_sale = (float)$trans_sale * (float)$agent_inv_res->no_of_pax_days;
                    $trans_profit = $trans_total_sale - $trans_total_cost;
                    $grand_cost += $trans_total_cost;
                    $grand_sale += $trans_total_sale;

                    // Caluclate Double Room Price
                    $double_total_cost = 0;
                    $double_total_sale = 0;
                    $double_total_profit = 0;
                    if(isset($accomodation)){
                        foreach($accomodation as $accmod_res){
                            if($accmod_res->acc_type == 'Double'){
                                (float)$double_cost     = $accmod_res->acc_total_amount; 
                                (float)$double_sale     = $accmod_res->hotel_invoice_markup ?? 0; 
                                $double_total_cost      = $double_total_cost + ((float)$double_cost * (float)$accmod_res->acc_qty);
                                $double_total_sale      = $double_total_sale + ((float)$double_sale * (float)$accmod_res->acc_qty);
                                $double_profit          = ((float)$double_sale - (float)$double_cost) * (float)$accmod_res->acc_qty;
                                $double_total_profit    = $double_total_profit + $double_profit;
                            }
                        }
                    }
                    if(isset($accomodation_more)){
                        foreach($accomodation_more as $accmod_res){
                            if($accmod_res->more_acc_type == 'Double'){
                                $double_cost = $accmod_res->more_acc_total_amount; 
                                if(isset($accmod_res->more_hotel_invoice_markup)){
                                    $double_sale = $accmod_res->more_hotel_invoice_markup ?? 0; 
                                }else{
                                    $double_sale = 0; 
                                }
                                $double_total_cost = $double_total_cost + ((float)$double_cost * (float)$accmod_res->more_acc_qty);
                                $double_total_sale = $double_total_sale + ((float)$double_sale * (float)$accmod_res->more_acc_qty);
                                $double_profit = ((float)$double_sale - (float)$double_cost) * (float)$accmod_res->more_acc_qty;
                                $double_total_profit = $double_total_profit + $double_profit;
                            }
                        }
                    }
                    $grand_cost += $double_total_cost;
                    $grand_sale += $double_total_sale;
                    
                    // Caluclate Triple Room Price
                    $triple_total_cost = 0;
                    $triple_total_sale = 0;
                    $triple_total_profit = 0;
                    if(isset($accomodation)){
                        foreach($accomodation as $accmod_res){
                            if($accmod_res->acc_type == 'Triple'){
                                $triple_cost = (float)$accmod_res->acc_total_amount; 
                                $triple_sale = (float)$accmod_res->hotel_invoice_markup ?? 0; 
                                $triple_total_cost = $triple_total_cost + ($triple_cost * (float)$accmod_res->acc_qty);
                                $triple_total_sale = $triple_total_sale + ($triple_sale * (float)$accmod_res->acc_qty);
                                $triple_profit = ($triple_sale - $triple_cost) * (float)$accmod_res->acc_qty;
                                $triple_total_profit = $triple_total_profit + $triple_profit;
                            }
                        }
                    }
                    if(isset($accomodation_more)){
                        foreach($accomodation_more as $accmod_res){
                            if($accmod_res->more_acc_type == 'Triple'){
                                $triple_cost = (float)$accmod_res->more_acc_total_amount; 
                                if(isset($accmod_res->more_hotel_invoice_markup)){
                                    $triple_sale = (float)$accmod_res->more_hotel_invoice_markup ?? '0'; 
                                }else{
                                    $triple_sale = 0; 
                                }
                                $triple_total_cost = $triple_total_cost + ($triple_cost * (float)$accmod_res->more_acc_qty);
                                $triple_total_sale = $triple_total_sale + ($triple_sale * (float)$accmod_res->more_acc_qty);
                                $triple_profit = ($triple_sale - $triple_cost) * $accmod_res->more_acc_qty;
                                $triple_total_profit = $triple_total_profit + $triple_profit;
                            }
                        }
                    }
                    $grand_cost += $triple_total_cost;
                    $grand_sale += $triple_total_sale;
                     
                    // Caluclate Quad Room Price
                    $quad_total_cost = 0;
                    $quad_total_sale = 0;
                    $quad_total_profit = 0;
                    if(isset($accomodation)){
                                foreach($accomodation as $accmod_res){
                                    if($accmod_res->acc_type == 'Quad'){
                                        $quad_cost = $accmod_res->acc_total_amount; 
                                        if(isset($accmod_res->hotel_invoice_markup)){
                                            $quad_sale = (float)$accmod_res->hotel_invoice_markup ?? 0; 
                                        }else{
                                            $quad_sale = 0; 
                                        }
                                        $quad_total_cost = $quad_total_cost + ($quad_cost * (float)$accmod_res->acc_qty);
                                        $quad_total_sale = $quad_total_sale + ($quad_sale * (float)$accmod_res->acc_qty);
                                        $quad_profit = ($quad_sale - $quad_cost) * (float)$accmod_res->acc_qty;
                                        $quad_total_profit = $quad_total_profit + $quad_profit;
                                    }
                                }
                             }
                    if(isset($accomodation_more)){
                        foreach($accomodation_more as $accmod_res){
                            if($accmod_res->more_acc_type == 'Quad'){
                                $quad_cost = (float)$accmod_res->more_acc_total_amount;
                                if(isset($accmod_res->more_hotel_invoice_markup)){
                                    $quad_sale = (float)$accmod_res->more_hotel_invoice_markup ?? 0; 
                                }else{
                                    $quad_sale = 0; 
                                }
                                $quad_total_cost = $quad_total_cost + ($quad_cost * (float)$accmod_res->more_acc_qty);
                                $quad_total_sale = $quad_total_sale + ($quad_sale * (float)$accmod_res->more_acc_qty);
                                $quad_profit = ($quad_sale - $quad_cost) * $accmod_res->more_acc_qty;
                                $quad_total_profit = $quad_total_profit + $quad_profit;
                            }
                        }
                    }
                    $grand_cost += $quad_total_cost;
                    $grand_sale += $quad_total_sale;
                    
                    // Caluclate Final Price
                    $Final_inv_price = $flight_total_sale + $visa_total_sale + $trans_total_sale + $double_total_sale + $triple_total_sale + $quad_total_sale;
                    $Final_inv_profit = $flight_profit + $visa_profit + $trans_profit + $double_total_profit + $triple_total_profit + $quad_total_profit;
                    
                    $invoice_payments = DB::table('pay_Invoice_Agent')->where('invoice_Id',$agent_inv_res->id)->get();
                    $total_paid_amount = 0;
                    foreach($invoice_payments as $inv_pay_res){
                        $total_paid_amount += $inv_pay_res->amount_Paid;
                    }
                    $inv_single_data = [
                        'invoice_id'=>$agent_inv_res->id,
                        'price'=>$agent_inv_res->total_sale_price_all_Services,
                        'paid_amount'=>$agent_inv_res->total_paid_amount,
                        'remaing_amount'=> $agent_inv_res->total_sale_price_all_Services - $agent_inv_res->total_paid_amount,
                        'over_paid_amount'=>$agent_inv_res->over_paid_amount,
                        'profit'=>$Final_inv_profit,
                        'total_cost'=>$grand_cost,
                        'total_sale'=>$grand_sale,
                        'currency'=>$agent_inv_res->currency_symbol,
                    ];
                    array_push($invoices_all_data,$inv_single_data);
                     
                }
                
                $invoice_Acc_details = [];
                foreach($agents_invoice_booking as $agent_inv_res){
                    $accomodation       = json_decode($agent_inv_res->accomodation_details);
                    $accomodation_more  = json_decode($agent_inv_res->accomodation_details_more);
                     
                    // Caluclate Double Room Price
                    $double_total_cost = 0;
                    $double_total_sale = 0;
                    $double_total_profit = 0;
                    if(isset($accomodation)){
                        foreach($accomodation as $accmod_res){
                            if($accmod_res->acc_type == 'Double'){
                                (float)$double_cost = $accmod_res->acc_total_amount;
                                if(isset($accmod_res->hotel_invoice_markup)){
                                    (float)$double_sale = $accmod_res->hotel_invoice_markup ?? 0; 
                                }else{
                                    $double_sale = 0; 
                                }
                                
                                 $double_total_cost = $double_total_cost + ((float)$double_cost * (float)$accmod_res->acc_qty);
                                 $double_total_sale = $double_total_sale + ((float)$double_sale * (float)$accmod_res->acc_qty);
                                 $double_profit = ((float)$double_sale - (float)$double_cost) * (float)$accmod_res->acc_qty;
                                 $double_total_profit = $double_total_profit + $double_profit;
                            }
                        }
                    }
                    if(isset($accomodation_more)){
                        foreach($accomodation_more as $accmod_res){
                            if($accmod_res->more_acc_type == 'Double'){
                                $double_cost = $accmod_res->more_acc_total_amount; 
                                if(isset($accmod_res->more_hotel_invoice_markup)){
                                    $double_sale = $accmod_res->more_hotel_invoice_markup ?? 0; 
                                }else{
                                    $double_sale = 0; 
                                }
                                $double_total_cost = $double_total_cost + ((float)$double_cost * (float)$accmod_res->more_acc_qty);
                                $double_total_sale = $double_total_sale + ((float)$double_sale * (float)$accmod_res->more_acc_qty);
                                $double_profit = ((float)$double_sale - (float)$double_cost) * (float)$accmod_res->more_acc_qty;
                                $double_total_profit = $double_total_profit + $double_profit;
                            }
                        }
                    }
                    $grand_cost += $double_total_cost;
                    $grand_sale += $double_total_sale;
                    
                    // Caluclate Triple Room Price
                    $triple_total_cost = 0;
                    $triple_total_sale = 0;
                    $triple_total_profit = 0;
                    if(isset($accomodation)){
                        foreach($accomodation as $accmod_res){
                            if($accmod_res->acc_type == 'Triple'){
                                $triple_cost = (float)$accmod_res->acc_total_amount;
                                if(isset($accmod_res->hotel_invoice_markup)){
                                    $triple_sale = (float)$accmod_res->hotel_invoice_markup ?? 0; 
                                }else{
                                    $triple_sale = 0; 
                                }
                                $triple_total_cost = $triple_total_cost + ($triple_cost * (float)$accmod_res->acc_qty);
                                $triple_total_sale = $triple_total_sale + ($triple_sale * (float)$accmod_res->acc_qty);
                                $triple_profit = ($triple_sale - $triple_cost) * (float)$accmod_res->acc_qty;
                                $triple_total_profit = $triple_total_profit + $triple_profit;
                            }
                        }
                    }
                    if(isset($accomodation_more)){
                        foreach($accomodation_more as $accmod_res){
                            if($accmod_res->more_acc_type == 'Triple'){
                                $triple_cost = (float)$accmod_res->more_acc_total_amount; 
                                if(isset($accmod_res->more_hotel_invoice_markup)){
                                    $triple_sale = (float)$accmod_res->more_hotel_invoice_markup ?? 0; 
                                }else{
                                    $triple_sale = 0; 
                                }
                                $triple_total_cost = $triple_total_cost + ($triple_cost * (float)$accmod_res->more_acc_qty);
                                $triple_total_sale = $triple_total_sale + ($triple_sale * (float)$accmod_res->more_acc_qty);
                                $triple_profit = ($triple_sale - $triple_cost) * $accmod_res->more_acc_qty;
                                $triple_total_profit = $triple_total_profit + $triple_profit;
                            }
                        }
                    }
                    $grand_cost += $triple_total_cost;
                    $grand_sale += $triple_total_sale;
                     
                    // Caluclate Quad Room Price
                    $quad_total_cost = 0;
                    $quad_total_sale = 0;
                    $quad_total_profit = 0;
                    if(isset($accomodation)){
                                foreach($accomodation as $accmod_res){
                                    if($accmod_res->acc_type == 'Quad'){
                                        $quad_cost = $accmod_res->acc_total_amount; 
                                        if(isset($accmod_res->hotel_invoice_markup)){
                                            $quad_sale = (float)$accmod_res->hotel_invoice_markup ?? 0; 
                                        }else{
                                            $quad_sale = 0; 
                                        }
                                        
                                         $quad_total_cost = $quad_total_cost + ($quad_cost * (float)$accmod_res->acc_qty);
                                         $quad_total_sale = $quad_total_sale + ($quad_sale * (float)$accmod_res->acc_qty);
                                         $quad_profit = ($quad_sale - $quad_cost) * (float)$accmod_res->acc_qty;
                                         $quad_total_profit = $quad_total_profit + $quad_profit;
                                    }
                                }
                             }
                    if(isset($accomodation_more)){
                        foreach($accomodation_more as $accmod_res){
                            if($accmod_res->more_acc_type == 'Quad'){
                                $quad_cost = (float)$accmod_res->more_acc_total_amount; 
                                if(isset($accmod_res->more_hotel_invoice_markup)){
                                    $quad_sale = (float)$accmod_res->more_hotel_invoice_markup ?? 0; 
                                }else{
                                    $quad_sale = 0; 
                                }
                                $quad_total_cost = $quad_total_cost + ($quad_cost * (float)$accmod_res->more_acc_qty);
                                $quad_total_sale = $quad_total_sale + ($quad_sale * (float)$accmod_res->more_acc_qty);
                                $quad_profit = ($quad_sale - $quad_cost) * $accmod_res->more_acc_qty;
                                $quad_total_profit = $quad_total_profit + $quad_profit;
                            }
                        }
                    }
                    $grand_cost += $quad_total_cost;
                    $grand_sale += $quad_total_sale;
                    
                    // Caluclate Final Price
                    $Final_inv_price    = $double_total_sale + $triple_total_sale + $quad_total_sale;
                    $Final_inv_profit   = $double_total_profit + $triple_total_profit + $quad_total_profit;
                    
                    $invoice_payments = DB::table('pay_Invoice_Agent')->where('invoice_Id',$agent_inv_res->id)->get();
                    $total_paid_amount = 0;
                    foreach($invoice_payments as $inv_pay_res){
                        $total_paid_amount += $inv_pay_res->amount_Paid;
                    }
                    $inv_single_data = [
                        'invoice_id'        => $agent_inv_res->id,
                        'price'             => $agent_inv_res->total_sale_price_all_Services,
                        'paid_amount'       => $agent_inv_res->total_paid_amount,
                        'remaing_amount'    => $agent_inv_res->total_sale_price_all_Services - $agent_inv_res->total_paid_amount,
                        'over_paid_amount'  => $agent_inv_res->over_paid_amount,
                        'profit'            => $Final_inv_profit,
                        'total_cost'        => $grand_cost,
                        'total_sale'        => $grand_sale,
                        'currency'          => $agent_inv_res->currency_symbol,
                    ];
                    array_push($invoice_Acc_details,$inv_single_data);
                }
                
                $invoice_Flight_details = [];
                foreach($agents_invoice_booking as $agent_inv_res){
                    $markup_details         = json_decode($agent_inv_res->markup_details);
                    $more_markup_details    = json_decode($agent_inv_res->more_markup_details);
                     
                    // Caluclate Flight Price 
                    $grand_cost = 0;
                    $grand_sale = 0;
                    $flight_cost = 0;
                    $flight_sale = 0;
                    foreach($markup_details as $mark_res){
                        if($mark_res->markup_Type_Costing == 'flight_Type_Costing'){
                            $flight_cost = $mark_res->without_markup_price; 
                            $flight_sale = $mark_res->markup_price; 
                        }
                    }
                            
                    $flight_total_cost = (float)$flight_cost * (float)$agent_inv_res->no_of_pax_days;
                    $flight_total_sale = (float)$flight_sale * (float)$agent_inv_res->no_of_pax_days;
                    
                    $flight_profit = $flight_total_sale - $flight_total_cost;
                    
                    $grand_cost += $flight_total_cost;
                    $grand_sale += $flight_total_sale;
                    
                    // Caluclate Final Price
                    $Final_inv_price    = $flight_total_sale;
                    $Final_inv_profit   = $flight_profit;
                    
                    $invoice_payments   = DB::table('pay_Invoice_Agent')->where('invoice_Id',$agent_inv_res->id)->get();
                    $total_paid_amount  = 0;
                    foreach($invoice_payments as $inv_pay_res){
                        $total_paid_amount += $inv_pay_res->amount_Paid;
                    }
                    $inv_single_data = [
                        'invoice_id'=>$agent_inv_res->id,
                        'price'=>$agent_inv_res->total_sale_price_all_Services,
                        'paid_amount'=>$agent_inv_res->total_paid_amount,
                        'remaing_amount'=> $agent_inv_res->total_sale_price_all_Services - $agent_inv_res->total_paid_amount,
                        'over_paid_amount'=>$agent_inv_res->over_paid_amount,
                        'profit'=>$Final_inv_profit,
                        'total_cost'=>$grand_cost,
                        'total_sale'=>$grand_sale,
                        'currency'=>$agent_inv_res->currency_symbol,
                    ];
                    array_push($invoice_Flight_details,$inv_single_data);
                     
                }
                
                $invoice_Visa_details = [];
                foreach($agents_invoice_booking as $agent_inv_res){
                    $markup_details         = json_decode($agent_inv_res->markup_details);
                    $more_markup_details    = json_decode($agent_inv_res->more_markup_details);
                   // Caluclate Visa Price 
                    $visa_cost = 0;
                    $visa_sale = 0;
                    foreach($markup_details as $mark_res){
                        if($mark_res->markup_Type_Costing == 'visa_Type_Costing'){
                            $visa_cost = $mark_res->without_markup_price; 
                            $visa_sale = $mark_res->markup_price; 
                        }
                    }
                    $visa_total_cost = (float)$visa_cost * (float)$agent_inv_res->no_of_pax_days;
                    $visa_total_sale = (float)$visa_sale * (float)$agent_inv_res->no_of_pax_days;
                    $visa_profit = $visa_total_sale - $visa_total_cost;
                    $grand_cost += $visa_total_cost;
                    $grand_sale += $visa_total_sale;

                    // Caluclate Final Price
                    $Final_inv_price    = $visa_total_sale;
                    $Final_inv_profit   = $visa_profit;
                    
                    $invoice_payments   = DB::table('pay_Invoice_Agent')->where('invoice_Id',$agent_inv_res->id)->get();
                    $total_paid_amount  = 0;
                    foreach($invoice_payments as $inv_pay_res){
                        $total_paid_amount += $inv_pay_res->amount_Paid;
                    }
                    $inv_single_data = [
                        'invoice_id'=>$agent_inv_res->id,
                        'price'=>$agent_inv_res->total_sale_price_all_Services,
                        'paid_amount'=>$agent_inv_res->total_paid_amount,
                        'remaing_amount'=> $agent_inv_res->total_sale_price_all_Services - $agent_inv_res->total_paid_amount,
                        'over_paid_amount'=>$agent_inv_res->over_paid_amount,
                        'profit'=>$Final_inv_profit,
                        'total_cost'=>$grand_cost,
                        'total_sale'=>$grand_sale,
                        'currency'=>$agent_inv_res->currency_symbol,
                    ];
                    array_push($invoice_Visa_details,$inv_single_data);
                     
                }
                
                $invoice_Transportation_details = [];
                foreach($agents_invoice_booking as $agent_inv_res){
                    $markup_details         = json_decode($agent_inv_res->markup_details);
                    $more_markup_details    = json_decode($agent_inv_res->more_markup_details);
                   
                    // Caluclate Transportation Price
                    $trans_cost = 0;
                    $trans_sale = 0;
                    foreach($markup_details as $mark_res){
                        if($mark_res->markup_Type_Costing == 'transportation_Type_Costing'){
                            $trans_cost = $mark_res->without_markup_price; 
                            $trans_sale = $mark_res->markup_price; 
                        }
                    }
                    $trans_total_cost = (float)$trans_cost * (float)$agent_inv_res->no_of_pax_days;
                    $trans_total_sale = (float)$trans_sale * (float)$agent_inv_res->no_of_pax_days;
                    $trans_profit = $trans_total_sale - $trans_total_cost;
                    $grand_cost += $trans_total_cost;
                    $grand_sale += $trans_total_sale;

                    // Caluclate Final Price
                    $Final_inv_price    = $trans_total_sale;
                    $Final_inv_profit   = $trans_profit;
                    
                    $invoice_payments = DB::table('pay_Invoice_Agent')->where('invoice_Id',$agent_inv_res->id)->get();
                    $total_paid_amount = 0;
                    foreach($invoice_payments as $inv_pay_res){
                        $total_paid_amount += $inv_pay_res->amount_Paid;
                    }
                    $inv_single_data = [
                        'invoice_id'=>$agent_inv_res->id,
                        'price'=>$agent_inv_res->total_sale_price_all_Services,
                        'paid_amount'=>$agent_inv_res->total_paid_amount,
                        'remaing_amount'=> $agent_inv_res->total_sale_price_all_Services - $agent_inv_res->total_paid_amount,
                        'over_paid_amount'=>$agent_inv_res->over_paid_amount,
                        'profit'=>$Final_inv_profit,
                        'total_cost'=>$grand_cost,
                        'total_sale'=>$grand_sale,
                        'currency'=>$agent_inv_res->currency_symbol,
                    ];
                    array_push($invoice_Transportation_details,$inv_single_data);
                     
                }
                
                $agent_data = [
                        'agent_id'=>$agent_res->id,
                        'agent_name'=>$agent_res->agent_Name,
                        'agent_over_paid'=>$agent_res->over_paid_am,
                        'agent_company'=>$agent_res->company_name,
                        'agents_tour_booking'=>$booking_all_data,
                        'agents_invoices_booking'=>$invoices_all_data,
                        'invoice_Acc_details'               => $invoice_Acc_details,
                        'invoice_Flight_details'            => $invoice_Flight_details,
                        'invoice_Visa_details'              => $invoice_Visa_details,
                        'invoice_Transportation_details'    => $invoice_Transportation_details,
                ];
                array_push($all_agents_data,$agent_data);         
            }
        }
        
        $separate_Revenue_accomodation      = DB::table('add_manage_invoices')
                                                ->where('add_manage_invoices.customer_id',$request->customer_id)
                                                ->where('add_manage_invoices.agent_id',$request->agent_id)
                                                ->whereJsonContains('services',['accomodation_tab'])
                                                ->sum('total_sale_price_all_Services');

        $separate_Revenue_flight            = DB::table('add_manage_invoices')
                                                ->where('add_manage_invoices.customer_id',$request->customer_id)
                                                ->where('add_manage_invoices.agent_id',$request->agent_id)
                                                ->whereJsonContains('services',['flights_tab'])
                                                ->sum('total_sale_price_all_Services');
        
        $separate_Revenue_visa              = DB::table('add_manage_invoices')
                                                ->where('add_manage_invoices.customer_id',$request->customer_id)
                                                ->where('add_manage_invoices.agent_id',$request->agent_id)
                                                ->whereJsonContains('services',['visa_tab'])
                                                ->sum('total_sale_price_all_Services');
        
        $separate_Revenue_transportation    = DB::table('add_manage_invoices')
                                                ->where('add_manage_invoices.customer_id',$request->customer_id)
                                                ->where('add_manage_invoices.agent_id',$request->agent_id)
                                                ->whereJsonContains('services',['transportation_tab'])
                                                ->sum('total_sale_price_all_Services');
                                                
        $separate_package_booking           = DB::table('tours_bookings')->join('cart_details','tours_bookings.id','cart_details.booking_id')
                                                ->where('tours_bookings.customer_id',$request->customer_id)
                                                ->where('cart_details.agent_name',$request->agent_id)
                                                ->where('cart_details.pakage_type','tour')
                                                ->get();
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
        
        $separate_activity_booking          = DB::table('tours_bookings')->join('cart_details','tours_bookings.id','cart_details.booking_id')
                                                ->where('tours_bookings.customer_id',$request->customer_id)
                                                ->where('cart_details.agent_name',$request->agent_id)
                                                ->where('cart_details.pakage_type','activity')
                                                ->get();
        if(count($separate_activity_booking) > 0){
            $separate_activity_grand_profit = 0;
            $separate_activity_cost_price   = 0;
            $separate_activity_Revenue      = 0;
            foreach($separate_activity_booking as $tour_res){
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
                    $separate_activity_grand_profit += $double_profit;
                    $separate_activity_cost_price += $double_adult_total_cost;
                    $separate_activity_Revenue += $cart_all_data->double_adult_total;
                }
                 
                if(isset($cart_all_data->triple_adults) && $cart_all_data->triple_adults > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_adult_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    }else{
                        $triple_adult_total_cost = 1 * $cart_all_data->triple_adults;
                        $triple_profit = $cart_all_data->triple_adult_total - $triple_adult_total_cost;
                    }
                    $separate_activity_grand_profit += $triple_profit;
                    $separate_activity_cost_price += $triple_adult_total_cost;
                    $separate_activity_Revenue += $cart_all_data->triple_adult_total;
                }
                 
                if(isset($cart_all_data->quad_adults) && $cart_all_data->quad_adults > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_adult_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    }else{
                        $quad_adult_total_cost = 1 * $cart_all_data->quad_adults;
                        $quad_profit = $cart_all_data->quad_adult_total - $quad_adult_total_cost;
                    }
                    $separate_activity_grand_profit += $quad_profit;
                    $separate_activity_cost_price += $quad_adult_total_cost;
                    $separate_activity_Revenue += $cart_all_data->quad_adult_total;
                }
                 
                if(isset($cart_all_data->without_acc_adults) && $cart_all_data->without_acc_adults > 0){
                    if(isset($tours_costing->without_acc_cost_price) && $tours_costing->without_acc_cost_price != null && $tours_costing->without_acc_cost_price != ''){
                        $without_acc_adult_total_cost = $tours_costing->without_acc_cost_price * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    }else{
                        $without_acc_adult_total_cost = 1 * $cart_all_data->without_acc_adults;
                        $without_acc_profit = $cart_all_data->without_acc_adult_total - $without_acc_adult_total_cost;
                    }
                    $separate_activity_grand_profit += $without_acc_profit;
                    $separate_activity_cost_price += $without_acc_adult_total_cost;
                    $separate_activity_Revenue += $cart_all_data->without_acc_adult_total;
                }
                 
                if(isset($cart_all_data->double_childs) && $cart_all_data->double_childs > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_child_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    }else{
                        $double_child_total_cost = 1 * $cart_all_data->double_childs;
                        $double_profit = $cart_all_data->double_childs_total - $double_child_total_cost;
                    }
                    $separate_activity_grand_profit += $double_profit;
                    $separate_activity_cost_price += $double_child_total_cost;
                    $separate_activity_Revenue += $cart_all_data->double_childs_total;
                }
             
                if(isset($cart_all_data->triple_childs) && $cart_all_data->triple_childs > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_child_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    }else{
                        $triple_child_total_cost = 1 * $cart_all_data->triple_childs;
                        $triple_profit = $cart_all_data->triple_childs_total - $triple_child_total_cost;
                    }
                    $separate_activity_grand_profit += $triple_profit;
                    $separate_activity_cost_price += $triple_child_total_cost;
                    $separate_activity_Revenue += $cart_all_data->triple_childs_total;
                }
                
                if(isset($cart_all_data->quad_childs) && $cart_all_data->quad_childs > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_child_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    }else{
                        $quad_child_total_cost = 1 * $cart_all_data->quad_childs;
                        $quad_profit = $cart_all_data->quad_child_total - $quad_child_total_cost;
                    }
                    $separate_activity_grand_profit += $quad_profit;
                    $separate_activity_cost_price += $quad_child_total_cost;
                    $separate_activity_Revenue += $cart_all_data->quad_child_total;
                }
                
                if(isset($cart_all_data->children) && $cart_all_data->children > 0){
                    if(isset($tours_costing->child_grand_total_cost_price) && $tours_costing->child_grand_total_cost_price != null && $tours_costing->child_grand_total_cost_price != ''){
                        $without_acc_child_total_cost = $tours_costing->child_grand_total_cost_price * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    }else{
                        $without_acc_child_total_cost = 1 * $cart_all_data->children;
                        $without_acc_profit = $cart_all_data->without_acc_child_total - $without_acc_child_total_cost;
                    }
                    $separate_activity_grand_profit += $without_acc_profit;
                    $separate_activity_cost_price += $without_acc_child_total_cost;
                    $separate_activity_Revenue += $cart_all_data->without_acc_child_total;
                }
    
                if(isset($cart_all_data->double_infant) && $cart_all_data->double_infant > 0){
                    if(isset($tours_costing->double_cost_price) && $tours_costing->double_cost_price != null && $tours_costing->double_cost_price != ''){
                        $double_infant_total_cost = $tours_costing->double_cost_price * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    }else{
                        $double_infant_total_cost = 1 * $cart_all_data->double_infant;
                        $double_profit = $cart_all_data->double_infant_total - $double_infant_total_cost;
                    }
                    $separate_activity_grand_profit += $double_profit;
                    $separate_activity_cost_price += $double_infant_total_cost;
                    $separate_activity_Revenue += $cart_all_data->double_infant_total;
                }
                
                if(isset($cart_all_data->triple_infant) && $cart_all_data->triple_infant > 0){
                    if(isset($tours_costing->triple_cost_price) && $tours_costing->triple_cost_price != null && $tours_costing->triple_cost_price != ''){
                        $triple_infant_total_cost = $tours_costing->triple_cost_price * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    }else{
                        $triple_infant_total_cost = 1 * $cart_all_data->triple_infant;
                        $triple_profit = $cart_all_data->triple_infant_total - $triple_infant_total_cost;
                    }
                    $separate_activity_grand_profit += $triple_profit;
                    $separate_activity_cost_price += $triple_infant_total_cost;
                    $separate_activity_Revenue += $cart_all_data->triple_infant_total;
                }
                
                if(isset($cart_all_data->quad_infant) && $cart_all_data->quad_infant > 0){
                    if(isset($tours_costing->quad_cost_price) && $tours_costing->quad_cost_price != null && $tours_costing->quad_cost_price != ''){
                        $quad_infant_total_cost = $tours_costing->quad_cost_price * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    }else{
                        $quad_infant_total_cost = 1 * $cart_all_data->quad_infant;
                        $quad_profit = $cart_all_data->quad_infant_total - $quad_infant_total_cost;
                    }
                    $separate_activity_grand_profit += $quad_profit;
                    $separate_activity_cost_price += $quad_infant_total_cost;
                    $separate_activity_Revenue += $cart_all_data->quad_infant_total;
                }
                 
                if(isset($cart_all_data->infants) && $cart_all_data->infants > 0){
                    if(isset($tours_costing->infant_total_cost_price) && $tours_costing->infant_total_cost_price != null && $tours_costing->infant_total_cost_price != ''){
                        $without_acc_infant_total_cost = $tours_costing->infant_total_cost_price * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    }else{
                        $without_acc_infant_total_cost = 1 * $cart_all_data->infants;
                        $without_acc_profit = $cart_all_data->without_acc_infant_total - $without_acc_infant_total_cost;
                    }
                    $separate_activity_grand_profit += $without_acc_profit;
                    $separate_activity_cost_price += $without_acc_infant_total_cost;
                    $separate_activity_Revenue += $cart_all_data->without_acc_infant_total;
                }
            }
        }else{
            $separate_activity_grand_profit = 0;
            $separate_activity_cost_price   = 0;
            $separate_activity_Revenue      = 0;
        }
        
        return response()->json(['message'=>'success',
            'agent_data'                        => $all_agents_data,
            'separate_Revenue_accomodation'     => $separate_Revenue_accomodation,
            'separate_Revenue_flight'           => $separate_Revenue_flight,
            'separate_Revenue_visa'             => $separate_Revenue_visa,
            'separate_Revenue_transportation'   => $separate_Revenue_transportation,
            'separate_package_Revenue'          => $separate_package_Revenue,
            'separate_activity_Revenue'         => $separate_activity_Revenue,
        ]);
    }
    
}