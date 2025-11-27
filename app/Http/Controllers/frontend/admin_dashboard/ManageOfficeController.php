<?php

namespace App\Http\Controllers\frontend\admin_dashboard;

use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UmrahPackage;
use App\Models\CustomerSubcription\CustomerSubcription;
use App\Models\Agents_detail;
use App\Models\booking_customers;
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
use App\Models\expense\expense;
use App\Models\expense\expenseCategory;
use App\Models\expense\expenseSubCategory;
use App\Models\Accounts\CashAccounts;
use App\Models\Accounts\CashAccountsBal;
use App\Models\Accounts\CashAccountledger;
use App\Models\uploadDocumentInvoice;
use App\Models\agent_Slots;
use App\Models\tours_bookings;
use App\Models\services_Invoices;
use App\Models\extra_Services_Invoices;
use App\Models\addManageInvoiceRemark;
use App\Models\addManageInvoiceAdvanceoptions;
use DB;
use PDF;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Http\Controllers\Mail3rdPartyController;
use App\Models\otherPaxDetail;

class ManageOfficeController extends Controller
{
    // Agents
    public function create_Agents(Request $req){
        // if($req->SU_id != null && $req->SU_id != ''){
        //     $Agents_detail  = DB::table('Agents_detail')->where('SU_id',$req->SU_id)->where('customer_id',$req->customer_id)->get();
        // }else{
            $Agents_detail  = DB::table('Agents_detail')->where('customer_id',$req->customer_id)->get();
        // }
        $countries  = DB::table('countries')->get();
        return response()->json(['status'=>'success','Agents_detail'=>$Agents_detail,'countries'=>$countries]);
    }
    
    public function customer_profile(Request $req){
        $all_countries                      = country::all();
        $userData                           = CustomerSubcription::where('Auth_key',$req->token)->select('id','status')->first();
        if($userData){
            $customer_personal_details      = DB::table('booking_customers')->where('id',$req->customer_id)->first();
            $currency_data                  = DB::table('mange_currencies')->where('customer_id',$userData->id)->get();
            $customers_Lead_details         = DB::table('addLead')->where('customer_id',$userData->id)->where('customer_name',$customer_personal_details->id)->first();
            $all_suppliers                  = DB::table('rooms_Invoice_Supplier')->where('customer_id',$userData->id)->get();
            $season_Details                 = DB::table('add_Seasons')->where('customer_id', $userData->id)->get();
            return response()->json([
                'status'=>'success',
                'customer_personal_details' => $customer_personal_details,
                "currency_data"             => $currency_data,
                'all_countries'             => $all_countries,
                'customers_Lead_details'    => $customers_Lead_details,
                'all_suppliers'             => $all_suppliers,
                'season_Details'            => $season_Details,
            ]);
        }
    }
    
    public function agent_profile(Request $req){
        $all_countries                      = country::all();
        $userData                           = CustomerSubcription::where('Auth_key',$req->token)->select('id','status')->first();
        if($userData){
            $agent_personal_details         = DB::table('Agents_detail')->where('id',$req->agent_id)->first();
            $all_suppliers                  = DB::table('rooms_Invoice_Supplier')->where('customer_id',$userData->id)->get();
            $currency_data                  = DB::table('mange_currencies')->where('customer_id',$userData->id)->get();
            $agents_Lead_details            = DB::table('addLead')->where('customer_id',$userData->id)->where('agent_Id',$agent_personal_details->id)->first();
            return response()->json([
                'status'                    => 'success',
                'agent_personal_details'    => $agent_personal_details,
                "currency_data"             => $currency_data,
                'all_suppliers'             => $all_suppliers,
                'all_countries'             => $all_countries,
                'agents_Lead_details'       => $agents_Lead_details,
            ]);
        }
    }
    
    public function delete_invoice(Request $request){
        
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
        
        $invoice_data  = DB::table('add_manage_invoices')->where('id',$request->invoice_id)->first();
        
        // dd($invoice_data);
        
        DB::beginTransaction();
        try {
            // if(isset($invoice_data->agents_Enquiry) && $invoice_data->agents_Enquiry == 'YES'){
            //     $quo_Det = DB::table('addManageQuotationPackage')->where('customer_id',$request->customer_id)->where('id',$invoice_data->quotation_id)->first();
            //     DB::table('addAgentsEnquiry')->where('customer_id',$request->customer_id)->where('id',$quo_Det->lead_id)->where('lead_id',$invoice_data->quotation_id)->delete();
            // }
            
            if(isset($invoice_data->agents_Enquiry) && $invoice_data->agents_Enquiry == 'YES'){
                if(isset($invoice_data->quotation_id) && $invoice_data->quotation_id != null && $invoice_data->quotation_id != ''){
                    $q_d = DB::table('addManageQuotationPackage')->where('customer_id',$request->customer_id)->where('id',$invoice_data->quotation_id)->first();
                    if(isset($q_d->agents_Enquiry) && $q_d->agents_Enquiry == 'YES'){
                        DB::table('addAgentsEnquiry')->where('customer_id',$request->customer_id)->where('id',$q_d->lead_id)->update([
                            'proceed_Status'    => NULL,
                            'quotation_status'  => NULL,
                        ]);
                        DB::table('addManageQuotationPackage')->where('customer_id',$request->customer_id)->where('id',$invoice_data->quotation_id)->delete();
                    }
                }
            }
            
            if(isset($invoice_data->groups_id) && $invoice_data->groups_id != null && $invoice_data->groups_id != '' && $invoice_data->groups_id != 'null'){
                $groups_id = json_decode($invoice_data->groups_id);
                foreach($groups_id as $val_GID){
                    DB::table('addGroupsdetails')->where('id',$val_GID)->update(['total_Invoices' => NULL]);
                }
            }
            
            DB::table('addManageQuotationPackage')->where('id',$invoice_data->quotation_id)->update(['quotation_status' => NULL,'mail_request_pax' => NULL]);
            
            $lead_in_process    = 0;
            $all_leads          = DB::table('addLead')->where('customer_id',$request->customer_id)->get();
            if(isset($all_leads) && $all_leads != null && $all_leads != ''){
                foreach($all_leads as $all_leads_value){
                    $lead_quotation         = DB::table('addManageQuotationPackage')->where('customer_id',$request->customer_id)->where('lead_id',$all_leads_value->id)->get();
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
            
            $new_acc            = json_decode($invoice_data->accomodation_details);
            $new_acc_more       = json_decode($invoice_data->accomodation_details_more);
            
            // Loop on Previous Accomodations 
            
            $booking_id = $invoice_data->id;
            
            DB::table('rooms_bookings_details')->where('booking_id', "$booking_id")->where('booking_from','Invoices')->delete();
            
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
            
            // dd($new_acc,$new_acc_more);
            
            // Accomodation Working
            if(isset($new_acc) && !empty($new_acc)){
                foreach($new_acc as $new_acc_res){
                    $ele_found = false;
                    //  dd($new_acc_res);
                 
                    if(isset($new_acc_res->hotelRoom_type_id) AND !empty($new_acc_res->hotelRoom_type_id)){
                        if(!$ele_found){
                            $room_data = DB::table('rooms')->where('id',$new_acc_res->hotelRoom_type_id)->first();
                            if($room_data){
                                // print_r($room_data);
                                $update_booked = (int)$room_data->booked - (int)$new_acc_res->acc_qty;
                                
                                $room_data_update = DB::table('rooms')->where('id',$new_acc_res->hotelRoom_type_id)->update([
                                    'booked'=>$update_booked
                                ]);
                                // print_r($room_data);
                                
                                $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
                                
                                if(isset($supplier_data)){
                                    $week_days_total            = 0;
                                    $week_end_days_totals       = 0;
                                    $total_price                = 0;
                                    $new_acc_res->acc_check_in  = date('Y-m-d',strtotime($new_acc_res->acc_check_in));
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
                                    
                                    $all_days_price             = $total_price * $new_acc_res->acc_qty;
                                    
                                    $supplier_balance           = $supplier_data->balance - $all_days_price;
                                    $supplier_payable_balance   = $supplier_data->payable - $all_days_price;
                                    
                                    // dd($supplier_balance,$supplier_payable_balance);
                                    
                                    // update Agent Balance
                                    DB::table('hotel_supplier_ledger')->insert([
                                        'supplier_id'=>$supplier_data->id,
                                        'payment'=>$all_days_price,
                                        'balance'=>$supplier_balance,
                                        'payable_balance'=>$supplier_payable_balance,
                                        'room_id'=>$room_data->id,
                                        'customer_id'=>$invoice_data->customer_id,
                                        'date'=>date('Y-m-d'),
                                        'invoice_no'=>$invoice_data->id,
                                        'available_from'=>$new_acc_res->acc_check_in,
                                        'available_to'=>$new_acc_res->acc_check_out,
                                        'room_quantity'=>$new_acc_res->acc_qty,
                                        'remarks'=>'Invoice Deleted'
                                    ]);
                                    
                                    $different_In_Price     = $new_acc_res->acc_total_amount_purchase ?? 0;
                                    $supplier_balance_New   = $supplier_data->balance - $different_In_Price;
                                    $supplier_payable_New   = $supplier_data->payable - $different_In_Price;
                                    
                                    DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                                        'balance'   => $supplier_balance_New,
                                        'payable'   => $supplier_payable_New
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
         
            if(isset($new_acc_more) && !empty($new_acc_more)){
                foreach($new_acc_more as $new_acc_res){
                    $ele_found = false;
                    
                    if(isset($new_acc_res->more_hotelRoom_type_id) AND !empty($new_acc_res->more_hotelRoom_type_id)){
                        if(!$ele_found){
                            $room_data = DB::table('rooms')->where('id',$new_acc_res->more_hotelRoom_type_id)->first();
                            if($room_data){
                                $update_booked = (int)$room_data->booked - (int)$new_acc_res->more_acc_qty;
                                
                                $room_data_update = DB::table('rooms')->where('id',$new_acc_res->more_hotelRoom_type_id)->update(['booked'=>$update_booked]);
                                
                                $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
                                
                                if(isset($supplier_data)){
                                    $week_days_total            = 0;
                                    $week_end_days_totals       = 0;
                                    $total_price                = 0;
                                    $new_acc_res->acc_check_in  = date('Y-m-d',strtotime($new_acc_res->more_acc_check_in));
                                    $new_acc_res->acc_check_out = date('Y-m-d',strtotime($new_acc_res->more_acc_check_out));
                                    
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
                                    
                                    $all_days_price             = $total_price * $new_acc_res->more_acc_qty;
                                    
                                    $supplier_balance           = $supplier_data->balance - $all_days_price;
                                    $supplier_payable_balance   = $supplier_data->payable - $all_days_price;
                                    
                                    // update Agent Balance
                                    DB::table('hotel_supplier_ledger')->insert([
                                        'supplier_id'=>$supplier_data->id,
                                        'payment'=>$all_days_price,
                                        'balance'=>$supplier_balance,
                                        'payable_balance'=>$supplier_payable_balance,
                                        'room_id'=>$room_data->id,
                                        'customer_id'=>$invoice_data->customer_id,
                                        'date'=>date('Y-m-d'),
                                        'invoice_no'=>$invoice_data->id,
                                        'available_from'=>$new_acc_res->acc_check_in,
                                        'available_to'=>$new_acc_res->acc_check_out,
                                        'room_quantity'=>$new_acc_res->more_acc_qty,
                                        'remarks'=>'Inovice Deleted'
                                    ]);
                                    
                                    $different_In_Price     = $new_acc_res->more_acc_total_amount_purchase ?? 0;
                                    $supplier_balance_New   = $supplier_data->balance - $different_In_Price;
                                    $supplier_payable_New   = $supplier_data->payable - $different_In_Price;
                                    
                                    DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                                        'balance'           => $supplier_balance_New,
                                        'payable'           => $supplier_payable_New
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
            
            // Flight Supplier Working
            $flights_Pax_details            = json_decode($invoice_data->flights_Pax_details);
            if(isset($flights_Pax_details) && $flights_Pax_details != null && $flights_Pax_details != ''){
                foreach($flights_Pax_details as $value){
                    
                    DB::table('flight_seats_occupied')->where('booking_id',$invoice_data->id)->where('type','Invoice')->delete();
                    
                    // Update Flight Supplier Balance
                    $supplier_data                  = DB::table('supplier')->where('id',$invoice_data->flight_supplier)->first();
                    //  Calculate Child Price
                    $child_price_wi_adult_price     = $value->flights_cost_per_seats_adult * $value->flights_adult_seats;
                    $child_price_wi_child_price     = $value->flights_cost_per_seats_child * $value->flights_child_seats;
                    $infant_price                   = $value->flights_cost_per_seats_infant * $value->flights_infant_seats;
                    $price_diff                     = $child_price_wi_adult_price - $child_price_wi_child_price;
                    
                    // New
                    $total_Balance                  = $child_price_wi_adult_price + $child_price_wi_child_price + $infant_price;
                    // New
                    
                    if($price_diff != 0 || $infant_price != 0){
                        $supplier_balance           = $supplier_data->balance + $price_diff;
                        $supplier_balance           = $supplier_balance - $infant_price;
                        $total_differ               = $infant_price - $price_diff;
                        $total_differ               = -1 * abs($total_differ);
                        DB::table('flight_supplier_ledger')->insert([
                            'supplier_id'=>$supplier_data->id,
                            'payment'=>$total_differ,
                            'balance'=>$supplier_balance,
                            'route_id'=>$value->flight_route_id_occupied,
                            'date'=>date('Y-m-d'),
                            'customer_id'=>$invoice_data->customer_id,
                            'adult_price'=>$value->flights_cost_per_seats_adult,
                            'child_price'=>$value->flights_cost_per_seats_child,
                            'infant_price'=>$value->flights_cost_per_seats_infant,
                            'adult_seats_booked'=>$value->flights_adult_seats,
                            'child_seats_booked'=>$value->flights_child_seats,
                            'infant_seats_booked'=>$value->flights_infant_seats,
                            'invoice_no'=>$invoice_data->id,
                            'remarks'=>'Invoice Deleted',
                        ]);
                        // DB::table('supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                    }
                    
                    // New
                    $supplier_balance_New = $supplier_data->balance - $total_Balance;
                    DB::table('supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance_New]);
                    // New
                }
            }
            
            // Transfer Supplier Working
            if(isset($invoice_data->transportation_details) && !empty($invoice_data->transportation_details) && $invoice_data->transportation_details != null){
                $transfer_data = json_decode($invoice_data->transportation_details);
                // dd($transfer_data);
                if(isset($transfer_data)){
                    if(1 == count($transfer_data)){
                        foreach($transfer_data as $index => $trans_res_data){
                            if(isset($trans_res_data->transportation_pick_up_location) && $trans_res_data->transportation_pick_up_location != null && $trans_res_data->transportation_pick_up_location != ''){
                                $transfer_sup_data = DB::table('transfer_Invoice_Supplier')->where('id',$invoice_data->transfer_supplier_id)->select('id','balance')->first();
                                $trans_sup_balance = (float)$transfer_sup_data->balance - (float)$trans_res_data->transportation_vehicle_total_price;
                                
                                $total_differ = -1 * abs($trans_res_data->transportation_vehicle_total_price);
                                DB::table('transfer_supplier_ledger')->insert([
                                        'supplier_id'=>$invoice_data->transfer_supplier_id,
                                        'payment'=> $total_differ,
                                        'balance'=> $trans_sup_balance,
                                        'vehicle_price'=> $trans_res_data->transportation_price_per_vehicle,
                                        'vehicle_type'=>$trans_res_data->transportation_vehicle_type,
                                        'no_of_vehicles'=> $trans_res_data->transportation_no_of_vehicle,
                                        'destination_id'=> $trans_res_data->destination_id,
                                        'invoice_no'=>$invoice_data->id,
                                        'remarks'=>'Invoice Deleted',
                                        'date'=>date('Y-m-d'),
                                        'customer_id'=>$invoice_data->customer_id,
                                    ]);
                                DB::table('transfer_Invoice_Supplier')->where('id',$invoice_data->transfer_supplier_id)->update(['balance'=>$trans_sup_balance]);
                            }
                        }
                    }else{
                        $total_price = 0;
                        foreach($transfer_data  as $index => $trans_res_data){
                            if($index != 0){
                                if($trans_res_data->transportation_vehicle_total_price != ''){
                                    $total_price += $trans_res_data->transportation_vehicle_total_price;
                                }else{
                                    $total_price += 0;
                                }
                            }
                        }
                      
                        $transfer_sup_data = DB::table('transfer_Invoice_Supplier')->where('id',$invoice_data->transfer_supplier_id)->select('id','balance')->first();
                        $trans_sup_balance = $transfer_sup_data->balance - $total_price;
                            
                        $total_price = -1 * abs($total_price);
                        DB::table('transfer_supplier_ledger')->insert([
                            'supplier_id'=>$invoice_data->transfer_supplier_id,
                            'payment'=> $total_price,
                            'balance'=> $trans_sup_balance,
                            'invoice_no'=>$invoice_data->id,
                            'remarks'=>'Invoice Deleted',
                            'date'=>date('Y-m-d'),
                            'customer_id'=>$invoice_data->customer_id,
                        ]);
                        DB::table('transfer_Invoice_Supplier')->where('id',$invoice_data->transfer_supplier_id)->update(['balance'=>$trans_sup_balance]);
                    }
                }
            }
            
            // Visa Supplier Working
            $visa_details = json_decode($invoice_data->all_visa_price_details);
            
            if(isset($visa_details)){
                foreach($visa_details as $index => $visa_res){
                    // 2 Update No of Seats Occupied in Visa
                    $visa_avail_data = DB::table('visa_Availability')->where('id',$visa_res->visa_avail_id)->first();
                    if($visa_avail_data != null){
                        $updated_seats = $visa_avail_data->visa_available + $visa_res->visa_occupied;
                        DB::table('visa_Availability')->where('id',$visa_res->visa_avail_id)->update(['visa_available' => $updated_seats]);
                        
                        // 3 Update Visa Supplier Balance
                        $visa_supplier_data             = DB::table('visa_Sup')->where('id',$visa_avail_data->visa_supplier)->first();
                        $visa_supplier_payable_balance  = $visa_supplier_data->payable - ($visa_res->visa_fee_purchase * $visa_res->visa_occupied);
                        $visa_total_sale                = $visa_avail_data->visa_price * $visa_res->visa_occupied;
                        $visa_total_sale                = -1 * abs($visa_total_sale);
                        
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
                                'invoice_no' => $invoice_data->id,
                                'visa_avl_id' => $visa_avail_data->id,
                                'remarks' => 'Invoice Deleted',
                                'date' => date('Y-m-d'),
                                'customer_id' => $invoice_data->customer_id,
                        ]);
                        
                        DB::table('visa_Sup')->where('id',$visa_avail_data->visa_supplier)->update(['payable' => $visa_supplier_payable_balance]);
                    }
                }
            }
            
            $invoice_Advance_Option     = DB::table('add_manage_invoice_advanceoptions')->where('customer_id',$request->customer_id)->where('invoice_Id',$invoice_data->id)->first();
            if($invoice_Advance_Option != null){
                DB::table('add_manage_invoice_advanceoptions')->where('customer_id',$request->customer_id)->where('invoice_Id',$invoice_data->id)->delete();
            }
            
            DB::table('add_manage_invoices')->where('id', $invoice_data->id)->delete();
            
            DB::commit();
            return response()->json(['message'=>'success','lead_in_process'=>$lead_in_process]);
        } catch (Throwable $e) {
            // echo "etner i catch ";
            echo $e;
            DB::rollback();
            return response()->json(['message'=>'error','lead_in_process'=>$lead_in_process]);
        }
    }
    
    public function delete_Quotation(Request $request){
        DB::beginTransaction();
        try {
            
            DB::table('addManageQuotationPackage')->where('id',$request->quotation_id)->delete();
            
            $lead_in_process = 0;
            $all_leads = DB::table('addLead')->where('customer_id',$request->customer_id)->get();
            if(isset($all_leads) && $all_leads != null && $all_leads != ''){
                foreach($all_leads as $all_leads_value){
                    $lead_quotation         = DB::table('addManageQuotationPackage')->where('customer_id',$request->customer_id)->where('lead_id',$all_leads_value->id)->get();
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
            
            DB::commit();
            return response()->json(['message'=>'success','lead_in_process'=>$lead_in_process]);
        } catch (Throwable $e) {
            // echo "etner i catch ";
                echo $e;
            DB::rollback();
            return response()->json(['message'=>'error','lead_in_process'=>$lead_in_process]);
        }
    }
    
    public function add_Agents(Request $request){
        $agent_Id = '';
        
        if(isset($request->upload_data) && $request->upload_data != null && $request->upload_data != ''){
            $upload_data = json_decode($request->upload_data);
            foreach($upload_data as $val_UD){
                if($val_UD->agent_Refrence_No != null && $val_UD->agent_Refrence_No != ''){
                    $check = DB::table('Agents_detail')->where('customer_id',$val_UD->customer_id)->where('agent_Refrence_No',$val_UD->agent_Refrence_No)->first();
                    if($check == null){
                        DB::table('Agents_detail')->insert([
                            'customer_id'       => $val_UD->customer_id,
                            'SU_id'             => $val_UD->SU_id ?? NULL,
                            'company_name'      => $val_UD->company_name,
                            'agent_Name'        => $val_UD->agent_Name,
                            'country_Code'      => $val_UD->country_Code,
                            'code_No'           => $val_UD->code_No,
                            'agent_Refrence_No' => $val_UD->agent_Refrence_No,
                        ]);
                    }else{
                        DB::table('Agents_detail')->where('agent_Refrence_No',$check->agent_Refrence_No)->update([
                            'customer_id'       => $val_UD->customer_id,
                            'SU_id'             => $val_UD->SU_id ?? NULL,
                            'company_name'      => $val_UD->company_name,
                            'agent_Name'        => $val_UD->agent_Name,
                            'country_Code'      => $val_UD->country_Code,
                            'code_No'           => $val_UD->code_No,
                            'agent_Refrence_No' => $val_UD->agent_Refrence_No,
                        ]);
                    }
                }
            }
        }else{
            if(isset($request->agent_Refrence_No) && $request->agent_Refrence_No != null && $request->agent_Refrence_No != ''){
                $check_Agent = DB::table('Agents_detail')->where('customer_id',$request->customer_id)->where('agent_Refrence_No',$request->agent_Refrence_No)->first();
                if($check_Agent != null){
                    return response()->json(['status'=>'error','message1'=>'error','message'=>'Agent Already Exist!']);
                }else{
                    $agent_Refrence_No                  = $request->agent_Refrence_No;
                }
            }else{
                $four_digit_code                        = rand(0,4444);
                $agent_Refrence_No                      = 'TT'.$four_digit_code;
            }
            
            $opening_balance                            = $request->opening_bal;
            $Agents_detail                              = new Agents_detail();
            $Agents_detail->customer_id                 = $request->customer_id;
            
            if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
                $Agents_detail->SU_id                   = $request->SU_id;
            }
            
            $Agents_detail->opening_balance             = $opening_balance;
            $Agents_detail->balance                     = $opening_balance;
            $Agents_detail->agent_Name                  = $request->agent_Name;
            $Agents_detail->agent_Email                 = $request->agent_Email;
            $Agents_detail->agent_Address               = $request->agent_Address;
            $Agents_detail->agent_contact_number        = $request->agent_contact_number;
            
            $Agents_detail->company_name                = $request->company_name;
            $Agents_detail->company_email               = $request->company_email;
            $Agents_detail->company_contact_number      = $request->company_contact_number;
            $Agents_detail->company_address             = $request->company_address;
            
            $Agents_detail->country                     = $request->country;
            $Agents_detail->city                        = $request->city;
            $Agents_detail->currency                    = $request->currency;
            
            $Agents_detail->agent_Refrence_No           = $agent_Refrence_No;
            
            // $countries          = DB::table('countries')->get();
            // foreach($countries as $value_countries){
                // if($value_countries->name == $request->country){
                    // $agent_Refrence_No = 'TT'.'-'.$value_countries->iso2.'-'.$four_digit_code;
                    // $agent_Refrence_No                  = 'TT'.$four_digit_code;
                    // $Agents_detail->agent_Refrence_No   = $agent_Refrence_No;
                // }
            // }
            
            $Agents_detail->save();
            
            $agent_Id = $Agents_detail->id;
        }
        
        $all_agents = DB::table('Agents_detail')->where('customer_id',$request->customer_id)->get();
        
        return response()->json(['status'=>'success','message1'=>'success','message'=>'Agent Added Successful!','agent_Id'=>$agent_Id,'all_agents'=>$all_agents]);
    }
    
    public function agents_new_slot(Request $req){
        $all_currency       = DB::table('countries')->get();
        // if(isset($req->SU_id) && $req->SU_id != null && $req->SU_id != ''){
        //     $agent_Details      = DB::table('Agents_detail')->where('SU_id',$req->SU_id)->where('customer_id',$req->customer_id)->where('id',$req->id)->first();
        //     $mange_currencies   = DB::table('mange_currencies')->where('SU_id',$req->SU_id)->where('customer_id',$req->customer_id)->get();
        //     $agent_Slots        = DB::table('agent_Slots')->where('SU_id',$req->SU_id)->where('customer_id',$req->customer_id)->where('agent_Id',$req->id)->get();
        //     $visa_suppliers     = DB::table('visa_Sup')->where('SU_id',$req->SU_id)->where('customer_id',$req->customer_id)->get();
        //     $invoice_AG         = DB::table('add_manage_invoices')->where('SU_id',$req->SU_id)->where('customer_id',$req->customer_id)->where('agent_Id',$req->id)->where('groups_id','!=','')->get();
        // }else{
            $agent_Details      = DB::table('Agents_detail')->where('customer_id',$req->customer_id)->where('id',$req->id)->first();
            $mange_currencies   = DB::table('mange_currencies')->where('customer_id',$req->customer_id)->get();
            $agent_Slots        = DB::table('agent_Slots')->where('customer_id',$req->customer_id)->where('agent_Id',$req->id)->get();
            $visa_suppliers     = DB::table('visa_Sup')->where('customer_id',$req->customer_id)->get();
            $invoice_AG         = DB::table('add_manage_invoices')->where('customer_id',$req->customer_id)->where('agent_Id',$req->id)->where('groups_id','!=','')->get();
        // }
        
        return response()->json(['message'=>'success','invoice_AG'=>$invoice_AG,'all_currency'=>$all_currency,'agent_Details'=>$agent_Details,'mange_currencies'=>$mange_currencies,'agent_Slots'=>$agent_Slots,'visa_suppliers'=>$visa_suppliers]);
    }
    
    public function add_agents_new_slot(Request $request){
        
        $agent_Slots        = DB::table('agent_Slots')->where('agent_Id',$request->agent_Id)->where('customer_id',$request->customer_id)->get();
        if(isset($agent_Slots) && $agent_Slots != null && $agent_Slots != '' && count($agent_Slots) != 0){
            $total                  = count($agent_Slots);
            $selectedDate           = $request->agent_Start_Date;
            $selectedDate           = Carbon::parse($selectedDate);
            $previousDate           = $selectedDate->subDays(1);
            $previousDateFormatted  = $previousDate->toDateString();
            DB::table('agent_Slots')->where('id',$agent_Slots[$total - 1]->id)->where('customer_id',$agent_Slots[$total - 1]->customer_id)->update(['agent_End_Date' => $previousDateFormatted]);
            
            $agent_Slots                            = new agent_Slots();
            $agent_Slots->customer_id               = $request->customer_id;
            
            if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
                $agent_Slots->SU_id                 = $request->SU_id;
            }
            
            $agent_Slots->agent_Id                  = $request->agent_Id;
            $agent_Slots->agent_Start_Date          = $request->agent_Start_Date;
            $agent_Slots->agent_End_Date            = $request->agent_End_Date;
            $agent_Slots->agent_Cost_Price          = $request->agent_Cost_Price;
            $agent_Slots->agent_Currency_Conversion = $request->agent_Currency_Conversion;
            $agent_Slots->agent_Exchange_Rate       = $request->agent_Exchange_Rate;
            $agent_Slots->agent_Sale_Price          = $request->agent_Sale_Price;
            
            $agent_Slots->agent_Visa_Supplier       = $request->agent_Visa_Supplier;
            $agent_Slots->selected_VS_details       = $request->selected_VS_details;
            $agent_Slots->agent_Currency            = $request->agent_Currency;
            $agent_Slots->visa_price_all            = $request->visa_price_all;
            
            if($request->agent_Currency_Select != ''){
                DB::table('Agents_detail')->where('id',$request->agent_Id)->where('customer_id',$request->customer_id)->update(['currency' => $request->agent_Currency_Select]);
            }
            
            $agent_Slots->save();
            
            return response()->json(['status'=>'success','message1'=>'success','message'=>'Agent Slot Added Successful!']);
            
        }else{
            $agent_Slots                            = new agent_Slots();
            $agent_Slots->customer_id               = $request->customer_id;
            
            if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
                $agent_Slots->SU_id                 = $request->SU_id;
            }
            
            $agent_Slots->agent_Id                  = $request->agent_Id;
            $agent_Slots->agent_Start_Date          = $request->agent_Start_Date;
            $agent_Slots->agent_End_Date            = $request->agent_End_Date;
            $agent_Slots->agent_Cost_Price          = $request->agent_Cost_Price;
            $agent_Slots->agent_Currency_Conversion = $request->agent_Currency_Conversion;
            $agent_Slots->agent_Exchange_Rate       = $request->agent_Exchange_Rate;
            $agent_Slots->agent_Sale_Price          = $request->agent_Sale_Price;
            
            $agent_Slots->agent_Visa_Supplier       = $request->agent_Visa_Supplier;
            $agent_Slots->selected_VS_details       = $request->selected_VS_details;
            $agent_Slots->agent_Currency            = $request->agent_Currency;
            $agent_Slots->visa_price_all            = $request->visa_price_all;
            
            if($request->agent_Currency_Select != ''){
                DB::table('Agents_detail')->where('id',$request->agent_Id)->where('customer_id',$request->customer_id)->update(['currency' => $request->agent_Currency_Select]);
            }
            
            $agent_Slots->save();
            
            return response()->json(['status'=>'success','message1'=>'success','message'=>'Agent Slot Added Successful!']);
        }
    }
    
    public function edit_agents_new_slot(Request $req){
        $agent_Slots        = DB::table('agent_Slots')->where('customer_id',$req->customer_id)->where('id',$req->id)->first();
        return response()->json(['message'=>'success','agent_Slots'=>$agent_Slots]);
    }
    
    public function update_agents_new_slot(Request $request){
        DB::beginTransaction();
        try {
            DB::table('agent_Slots')->where('id',$request->id)->where('customer_id',$request->customer_id)->update(['agent_Sale_Price'=>$request->agent_Sale_Price]);
            DB::commit();
            return response()->json(['status'=>'success','message'=>'Agent Slot Updated Successful!']);
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
        }
    }
    
    public function edit_Agents(Request $request){
        $edit_Agents    = Agents_detail::find($request->id);
        $agents_Eqnuiry = DB::table('addAgentsEnquiry')->where('customer_id',$request->customer_id)->where('agent_Id',$request->id)->get();
        $countries      = DB::table('countries')->get();
        return response()->json(['status'=>'success','edit_Agents'=>$edit_Agents,'countries'=>$countries,'agents_Eqnuiry'=>$agents_Eqnuiry]); 
    }
    
    public function update_Agents(Request $request){
        $id             = $request->id;
        $Agents_detail  = Agents_detail::find($id);
        if($Agents_detail)
        {
            $Agents_detail->customer_id             = $request->customer_id;
            $Agents_detail->agent_Name              = $request->agent_Name;
            $Agents_detail->agent_Email             = $request->agent_Email;
            $Agents_detail->agent_Address           = $request->agent_Address;
            $Agents_detail->agent_contact_number    = $request->agent_contact_number;
            $Agents_detail->company_name            = $request->company_name;
            $Agents_detail->company_email           = $request->company_email;
            $Agents_detail->company_contact_number  = $request->company_contact_number;
            $Agents_detail->company_address         = $request->company_address;
            $Agents_detail->country                 = $request->country;
            $Agents_detail->city                    = $request->city;
            $Agents_detail->currency                = $request->currency;
            // $Agents_detail->agent_Refrence_No       = $agent_Refrence_No;
            
            // $four_digit_code    = rand(0,4444);
            // $countries          = DB::table('countries')->get();
            // foreach($countries as $value_countries)
            // if($value_countries->name == $request->country){
            //     // $agent_Refrence_No = 'TT'.'-'.$value_countries->iso2.'-'.$four_digit_code;
            //     $agent_Refrence_No = 'TT'.$four_digit_code;
            //     $Agents_detail->agent_Refrence_No = $agent_Refrence_No;
            // }
            
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
        $Agents_detail->status = 0;
        $result = $Agents_detail->update();
        if($result){
            return response()->json(['status'=>'success']);
        }else{
            return response()->json(['status'=>'error']);
        }
    }
    // Agents
    
    // Invoices
    public function create_Invoices(Request $req){
        $customer_id                    = $req->customer_id;
        $all_countries                  = country::all();
        $all_countries_currency         = country::all();
        $all_curr_symboles              = country::all();
        $customer                       = DB::table('customer_subcriptions')->where('id','!=',$customer_id)->get();
        $currency_Symbol                = DB::table('customer_subcriptions')->where('id',$customer_id)->get();
        // if(isset($req->SU_id) && $req->SU_id != null && $req->SU_id != ''){
        //     $categories                 = DB::table('categories')->where('SU_id',$req->SU_id)->where('customer_id',$customer_id)->get();
        //     $attributes                 = DB::table('attributes')->where('SU_id',$req->SU_id)->where('customer_id',$customer_id)->get();
        //     $bir_airports               = DB::table('bir_airports')->get();
        //     $payment_gateways           = DB::table('payment_gateways')->where('SU_id',$req->SU_id)->where('customer_id',$customer_id)->get();
        //     $payment_modes              = DB::table('payment_modes')->where('SU_id',$req->SU_id)->where('customer_id',$customer_id)->get();
        //     $Agents_detail              = DB::table('Agents_detail')->where('SU_id',$req->SU_id)->where('customer_id',$req->customer_id)->get();
        //     $agent_Slots                = DB::table('agent_Slots')->where('SU_id',$req->SU_id)->where('customer_id',$req->customer_id)->get();
        //     $visa_supplier              = DB::table('visa_Sup')->where('SU_id',$req->SU_id)->where('customer_id',$customer_id)->get();
        //     $visa_Supp_Slots            = DB::table('visa_supplier_Slots')->where('SU_id',$req->SU_id)->where('customer_id',$req->customer_id)->get();
        //     $user_hotels                = Hotels::where('owner_id',$customer_id)->where('SU_id',$req->SU_id)->get();
        //     $mange_currencies           = DB::table('mange_currencies')->where('SU_id',$req->SU_id)->where('customer_id',$customer_id)->get();
        //     $supplier_detail            = DB::table('rooms_Invoice_Supplier')->where('SU_id',$req->SU_id)->where('customer_id',$customer_id)->get();
        //     $tranfer_supplier           = DB::table('transfer_Invoice_Supplier')->where('SU_id',$req->SU_id)->where('customer_id',$customer_id)->get();
        //     $customers_data             = DB::table('booking_customers')->where('SU_id',$req->SU_id)->where('customer_id',$customer_id)->get();
        //     $all_flight_routes          = DB::table('flight_rute')->where('SU_id',$req->SU_id)->where('customer_id',$customer_id)->get();
        //     $flight_suppliers           = DB::table('supplier')->where('SU_id',$req->SU_id)->where('customer_id',$customer_id)->get();
        //     $visa_types                 = DB::table('visa_types')->where('SU_id',$req->SU_id)->where('customer_id',$customer_id)->get();
        //     $tranfer_vehicle            = DB::table('tranfer_vehicle')->where('SU_id',$req->SU_id)->where('customer_id',$customer_id)->get();
        //     $tranfer_destination        = DB::table('tranfer_destination')->where('SU_id',$req->SU_id)->where('customer_id',$customer_id)->get();
        //     $tranfer_supplier           = DB::table('transfer_Invoice_Supplier')->where('SU_id',$req->SU_id)->where('customer_id',$customer_id)->get();
        //     $tranfer_company            = DB::table('transfer_Invoice_Company')->where('SU_id',$req->SU_id)->where('customer_id',$customer_id)->get();
        //     $mange_currencies           = DB::table('mange_currencies')->where('SU_id',$req->SU_id)->where('customer_id',$customer_id)->get();
        //     $vehicle_category           = DB::table('vehicle_category')->where('SU_id',$req->SU_id)->where('customer_id',$customer_id)->get();
        //     $groups_Detail              = DB::table('addGroupsdetails')->where('SU_id',$req->SU_id)->where('customer_id',$req->customer_id)->get();
        // }else{
            $categories                 = DB::table('categories')->where('customer_id',$customer_id)->get();
            $attributes                 = DB::table('attributes')->where('customer_id',$customer_id)->get();
            $bir_airports               = DB::table('bir_airports')->get();
            $payment_gateways           = DB::table('payment_gateways')->where('customer_id',$customer_id)->get();
            $payment_modes              = DB::table('payment_modes')->where('customer_id',$customer_id)->get();
            $b2b_Agents_detail          = DB::table('b2b_agents')->where('token',$req->token)->get();
            $Agents_detail              = DB::table('Agents_detail')->where('customer_id',$req->customer_id)->get();
            $agent_Slots                = DB::table('agent_Slots')->where('customer_id',$req->customer_id)->get();
            $visa_supplier              = DB::table('visa_Sup')->where('customer_id',$customer_id)->get();
            $visa_Supp_Slots            = DB::table('visa_supplier_Slots')->where('customer_id',$req->customer_id)->get();
            $user_hotels                = Hotels::where('owner_id',$customer_id)->get();
            $mange_currencies           = DB::table('mange_currencies')->where('customer_id',$customer_id)->get();
            $supplier_detail            = DB::table('rooms_Invoice_Supplier')->where('customer_id',$customer_id)->get();
            $tranfer_supplier           = DB::table('transfer_Invoice_Supplier')->where('customer_id',$customer_id)->get();
            $customers_data             = DB::table('booking_customers')->where('customer_id',$customer_id)->get();
            $all_flight_routes          = DB::table('flight_rute')->where('customer_id',$customer_id)->get();
            $flight_suppliers           = DB::table('supplier')->where('customer_id',$customer_id)->get();
            $visa_types                 = DB::table('visa_types')->where('customer_id',$customer_id)->get();
            $tranfer_vehicle            = DB::table('tranfer_vehicle')->where('customer_id',$customer_id)->get();
            $tranfer_destination        = DB::table('tranfer_destination')->where('customer_id',$customer_id)->get();
            $tranfer_supplier           = DB::table('transfer_Invoice_Supplier')->where('customer_id',$customer_id)->get();
            $tranfer_company            = DB::table('transfer_Invoice_Company')->where('customer_id',$customer_id)->get();
            $mange_currencies           = DB::table('mange_currencies')->where('customer_id',$customer_id)->get();
            $vehicle_category           = DB::table('vehicle_category')->where('customer_id',$customer_id)->get();
            $groups_Detail              = DB::table('addGroupsdetails')->where('customer_id',$req->customer_id)->get();
            $custom_Meal_Types          = DB::table('custom_Meal_Types')->where('customer_id',$req->customer_id)->get();
        // }
        
        $services_Invoices              = DB::table('services_Invoices')->where('customer_id',$customer_id)->get();
        
        return response()->json(['message'=>'success','custom_Meal_Types'=>$custom_Meal_Types,'b2b_Agents_detail'=>$b2b_Agents_detail,'services_Invoices'=>$services_Invoices,'visa_Supp_Slots'=>$visa_Supp_Slots,'agent_Slots'=>$agent_Slots,'groups_Detail'=>$groups_Detail,'vehicle_category'=>$vehicle_category,'tranfer_vehicle'=>$tranfer_vehicle,'tranfer_destination'=> $tranfer_destination,'tranfer_supplier'=> $tranfer_supplier,'tranfer_company'=>$tranfer_company,'mange_currencies'=>$mange_currencies,'visa_supplier'=>$visa_supplier,'visa_types'=>$visa_types,'flight_suppliers'=>$flight_suppliers,'all_flight_routes'=>$all_flight_routes,'all_curr_symboles'=>$all_curr_symboles,'tranfer_supplier'=>$tranfer_supplier,'supplier_detail'=>$supplier_detail,'mange_currencies'=>$mange_currencies,'user_hotels'=>$user_hotels,'Agents_detail'=>$Agents_detail,'currency_Symbol'=>$currency_Symbol,'customer'=>$customer,'attributes'=>$attributes,'categories'=>$categories,'all_countries'=>$all_countries,'all_countries_currency'=>$all_countries_currency,'bir_airports'=>$bir_airports,'payment_gateways'=>$payment_gateways,'payment_modes'=>$payment_modes,'customers_data'=>$customers_data]);
    }
    
    // Extra Services
    public function services_submit_Ajax(Request $req){
        $insert                 = new services_Invoices();
        $insert->token          = $req->token;
        $insert->customer_id    = $req->customer_id;
        $insert->services_Name  = $req->services_Name;
        $insert->services_Type  = $req->services_Type;
        $insert->save();
        
        $services_Invoices = DB::table('services_Invoices')->where('token',$req->token)->where('customer_id',$req->customer_id)->get();
        
        return response()->json(['message'=>'success','services_Invoices'=>$services_Invoices]);
    }
    
    public function add_extra_Services_Invoices(Request $req){
        DB::beginTransaction();
        try {
            
            $insert                             = new extra_Services_Invoices();
            $insert->token                      = $req->token;
            $insert->customer_id                = $req->customer_id;
            $generate_id                        = rand(0,9999999);
            $insert->generate_id                = $generate_id;
            if(isset($req->SU_id) && $req->SU_id != null && $req->SU_id != ''){
                $insert->SU_id               = $req->SU_id;
            }
            $insert->booking_customer_id        = $req->booking_customer_id;
            $insert->agent_Company_Name         = $req->agent_Company_Name;
            $insert->agent_Id                   = $req->agent_Id;
            $insert->agent_Name                 = $req->agent_Name;
            $insert->currency_symbol            = $req->currency_symbol;
            $insert->currency_conversion        = $req->currency_conversion;
            $insert->conversion_type_Id         = $req->conversion_type_Id;
            $insert->author_Name                = $req->author_Name;
            $insert->f_name                     = $req->f_name;
            $insert->middle_name                = $req->middle_name;
            $insert->email                      = $req->email;
            $insert->mobile                     = $req->mobile;
            $insert->country                    = $req->country;
            $insert->services_Id                = $req->services_Id;
            $insert->services_Name              = $req->services_Name;
            $insert->services_Quantity          = $req->services_Quantity;
            $insert->services_Type              = $req->services_Type;
            $insert->services_Price_Per_KG      = $req->services_Price_Per_KG;
            $insert->services_Price_Per_Unit    = $req->services_Price_Per_Unit;
            $insert->services_Total_Price       = $req->services_Total_Price;
            $insert->services_Comment           = $req->services_Comment;
            
            $insert->save();
            
            $invoice_id                         = $insert->id;
            
            $agent_data                         = DB::table('Agents_detail')->where('id',$req->agent_Id)->select('id','balance')->first();
            if(isset($agent_data)){
                
                if($agent_data->balance != null){
                    $balance_AJ = $agent_data->balance;
                }else{
                    $balance_AJ = 0;
                }
                
                $services_Total_Price   = $insert->services_Total_Price;
                $agent_balance          = $balance_AJ + $services_Total_Price;
                
                DB::table('Agents_detail')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                
                DB::table('agents_ledgers_new')->insert([
                    'agent_id'      => $agent_data->id,
                    'received'      => $insert->services_Total_Price ?? '0',
                    'balance'       => $agent_balance,
                    'invoice_no'    => $insert->id,
                    'customer_id'   => $req->customer_id,
                    'date'          => date('Y-m-d'),
                ]);
            }
            
            if($req->booking_customer_id != '-1'){
                $customer_data = DB::table('booking_customers')->where('id',$req->booking_customer_id)->select('id','balance')->first();
                if(isset($customer_data)){
                    $customer_balance = $customer_data->balance + $insert->services_Total_Price;
                    DB::table('customer_ledger')->insert([
                        'booking_customer'  => $customer_data->id,
                        'received'          => $insert->services_Total_Price,
                        'balance'           => $customer_balance,
                        'invoice_no'        => $insert->id,
                        'customer_id'       => $req->customer_id,
                        'date'              => date('Y-m-d'),
                    ]);
                    
                    DB::table('booking_customers')->where('id',$customer_data->id)->update(['balance'=>$customer_balance]);
                }
            }
            
            DB::commit();
            return response()->json(['status'=>'success','invoice_id'=>$invoice_id,'message'=>'Extra Services Invoice added Succesfully']); 
        
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
        }
    }
    
    public function view_Invoices_Extra(Request $req){
        DB::beginTransaction(); 
        try {
            $filterDate                 = Carbon::createFromFormat('d-m-Y', '31-12-2024')->startOfDay();
            $all_countries              = country::all();
            $services_Invoices          = DB::table('services_Invoices')->where('customer_id',$req->customer_id)->orderBy('id', 'DESC')->get();
            $extra_Services_Invoices    = DB::table('extra_Services_Invoices')->where('customer_id',$req->customer_id)->where('created_at', '>', $filterDate)->orderBy('id', 'DESC')->get();
            $total_ESI_Price            = DB::table('extra_Services_Invoices')->where('customer_id',$req->customer_id)->where('created_at', '>', $filterDate)->sum('services_Total_Price');
            $total_ESI_Hadi             = DB::table('extra_Services_Invoices')->where('customer_id',$req->customer_id)->where('created_at', '>', $filterDate)->sum('services_Quantity');
            $agents_Detail              = DB::table('Agents_detail')->where('customer_id',$req->customer_id)->get();
            $customer_Details           = DB::table('booking_customers')->where('customer_id',$req->customer_id)->get();
            return response()->json([
                'total_ESI_Hadi'            => $total_ESI_Hadi,
                'total_ESI_Price'           => $total_ESI_Price,
                'all_countries'             => $all_countries,
                'services_Invoices'         => $services_Invoices,
                'extra_Services_Invoices'   => $extra_Services_Invoices,
                'agents_Detail'             => $agents_Detail,
                'customer_Details'          => $customer_Details,
            ]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function view_Invoices_Extra_2024(Request $req){
        DB::beginTransaction(); 
        try {
            $all_countries              = country::all();
            $services_Invoices          = DB::table('services_Invoices')->where('customer_id',$req->customer_id)->orderBy('id', 'DESC')->get();
            $extra_Services_Invoices    = DB::table('extra_Services_Invoices')->where('customer_id',$req->customer_id)->whereYear('created_at', '<', 2025)->orderBy('id', 'DESC')->get();
            $total_ESI_Price            = DB::table('extra_Services_Invoices')->where('customer_id',$req->customer_id)->whereYear('created_at', '<', 2025)->sum('services_Total_Price');
            $total_ESI_Hadi             = DB::table('extra_Services_Invoices')->where('customer_id',$req->customer_id)->whereYear('created_at', '<', 2025)->sum('services_Quantity');
            $agents_Detail              = DB::table('Agents_detail')->where('customer_id',$req->customer_id)->get();
            $customer_Details           = DB::table('booking_customers')->where('customer_id',$req->customer_id)->get();
            return response()->json([
                'total_ESI_Hadi'            => $total_ESI_Hadi,
                'total_ESI_Price'           => $total_ESI_Price,
                'all_countries'             => $all_countries,
                'services_Invoices'         => $services_Invoices,
                'extra_Services_Invoices'   => $extra_Services_Invoices,
                'agents_Detail'             => $agents_Detail,
                'customer_Details'          => $customer_Details,
            ]);
        } catch (Throwable $e) {
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong!']);
        }
    }
    
    public function Extra_Services_invoice_Agent(Request $req){
        try {
            $all_countries              = country::all();
            $extra_Services_Invoices    = DB::table('extra_Services_Invoices')->where('id',$req->id)->first();
            $services_Invoices          = DB::table('services_Invoices')->where('id',$extra_Services_Invoices->services_Id)->get();
            $agent_data                 = DB::table('Agents_detail')->where('id',$extra_Services_Invoices->agent_Id)->first();
            $customer_data              = DB::table('customer_subcriptions')->where('id',$req->customer_id)->first();
            $contact_details            = DB::table('contact_details')->where('customer_id',$req->customer_id)->first();
            
            return response()->json([
                'message'                   => 'success',
                'all_countries'             => $all_countries,
                'services_Invoices'         => $services_Invoices,
                'extra_Services_Invoices'   => $extra_Services_Invoices,
                'agent_data'                => $agent_data,
                'customer_data'             => $customer_data,
                'contact_details'           => $contact_details,
            ]);
        } catch (\Exception $e) {
            echo $e;
        }
    }
    
    public function edit_Extra_Services_invoice_Agent(Request $req){
        try {
            $customer_id                = $req->customer_id;
            $extra_Services_Invoices    = DB::table('extra_Services_Invoices')->where('id',$req->id)->first();
            // $services_Invoices          = DB::table('services_Invoices')->where('id',$extra_Services_Invoices->services_Id)->get();
            $services_Invoices          = DB::table('services_Invoices')->where('customer_id',$customer_id)->get();
            $agent_data                 = DB::table('Agents_detail')->where('id',$extra_Services_Invoices->agent_Id)->first();
            $customer_data              = DB::table('customer_subcriptions')->where('id',$customer_id)->first();
            $contact_details            = DB::table('contact_details')->where('customer_id',$customer_id)->first();
            // Create Invoices
            $all_countries              = country::all();
            $all_countries_currency     = country::all();
            $all_curr_symboles          = country::all();
            $customer                   = DB::table('customer_subcriptions')->where('id','!=',$customer_id)->get();
            $currency_Symbol            = DB::table('customer_subcriptions')->where('id',$customer_id)->get();
            $categories                 = DB::table('categories')->where('customer_id',$customer_id)->get();
            $attributes                 = DB::table('attributes')->where('customer_id',$customer_id)->get();
            $bir_airports               = DB::table('bir_airports')->get();
            $payment_gateways           = DB::table('payment_gateways')->where('customer_id',$customer_id)->get();
            $payment_modes              = DB::table('payment_modes')->where('customer_id',$customer_id)->get();
            $b2b_Agents_detail          = DB::table('b2b_agents')->where('token',$req->token)->get();
            $Agents_detail              = DB::table('Agents_detail')->where('customer_id',$req->customer_id)->get();
            $agent_Slots                = DB::table('agent_Slots')->where('customer_id',$req->customer_id)->get();
            $visa_supplier              = DB::table('visa_Sup')->where('customer_id',$customer_id)->get();
            $visa_Supp_Slots            = DB::table('visa_supplier_Slots')->where('customer_id',$req->customer_id)->get();
            $user_hotels                = Hotels::where('owner_id',$customer_id)->get();
            $mange_currencies           = DB::table('mange_currencies')->where('customer_id',$customer_id)->get();
            $supplier_detail            = DB::table('rooms_Invoice_Supplier')->where('customer_id',$customer_id)->get();
            $tranfer_supplier           = DB::table('transfer_Invoice_Supplier')->where('customer_id',$customer_id)->get();
            $customers_data             = DB::table('booking_customers')->where('customer_id',$customer_id)->get();
            $all_flight_routes          = DB::table('flight_rute')->where('customer_id',$customer_id)->get();
            $flight_suppliers           = DB::table('supplier')->where('customer_id',$customer_id)->get();
            $visa_types                 = DB::table('visa_types')->where('customer_id',$customer_id)->get();
            $tranfer_vehicle            = DB::table('tranfer_vehicle')->where('customer_id',$customer_id)->get();
            $tranfer_destination        = DB::table('tranfer_destination')->where('customer_id',$customer_id)->get();
            $tranfer_company            = DB::table('transfer_Invoice_Company')->where('customer_id',$customer_id)->get();
            $vehicle_category           = DB::table('vehicle_category')->where('customer_id',$customer_id)->get();
            $groups_Detail              = DB::table('addGroupsdetails')->where('customer_id',$req->customer_id)->get();
            $custom_Meal_Types          = DB::table('custom_Meal_Types')->where('customer_id',$req->customer_id)->get();
            
            return response()->json([
                'message'=>'success','extra_Services_Invoices'=>$extra_Services_Invoices,'agent_data'=>$agent_data,'customer_data'=>$customer_data,'contact_details'=>$contact_details,
                // Create Invoices
                'custom_Meal_Types'=>$custom_Meal_Types,'b2b_Agents_detail'=>$b2b_Agents_detail,'services_Invoices'=>$services_Invoices,'visa_Supp_Slots'=>$visa_Supp_Slots,
                'agent_Slots'=>$agent_Slots,'groups_Detail'=>$groups_Detail,'vehicle_category'=>$vehicle_category,'tranfer_vehicle'=>$tranfer_vehicle,'tranfer_destination'=> $tranfer_destination,
                'tranfer_supplier'=> $tranfer_supplier,'tranfer_company'=>$tranfer_company,'mange_currencies'=>$mange_currencies,'visa_supplier'=>$visa_supplier,'visa_types'=>$visa_types,
                'flight_suppliers'=>$flight_suppliers,'all_flight_routes'=>$all_flight_routes,'all_curr_symboles'=>$all_curr_symboles,'tranfer_supplier'=>$tranfer_supplier,
                'supplier_detail'=>$supplier_detail,'mange_currencies'=>$mange_currencies,'user_hotels'=>$user_hotels,'Agents_detail'=>$Agents_detail,'currency_Symbol'=>$currency_Symbol,
                'customer'=>$customer,'attributes'=>$attributes,'categories'=>$categories,'all_countries'=>$all_countries,'all_countries_currency'=>$all_countries_currency,
                'bir_airports'=>$bir_airports,'payment_gateways'=>$payment_gateways,'payment_modes'=>$payment_modes,'customers_data'=>$customers_data
            ]);
        } catch (\Exception $e) {
            echo $e;
        }
    }
    
    public function update_extra_Services_Invoices(Request $req){
        DB::beginTransaction();
        try {
            
            $insert                             = extra_Services_Invoices::find($req->id);
            $insert->token                      = $req->token;
            $insert->customer_id                = $req->customer_id;
            // $generate_id                        = rand(0,9999999);
            // $insert->generate_id                = $generate_id;
            if(isset($req->SU_id) && $req->SU_id != null && $req->SU_id != ''){
                $insert->SU_id               = $req->SU_id;
            }
            $insert->booking_customer_id        = $req->booking_customer_id;
            $insert->agent_Company_Name         = $req->agent_Company_Name;
            $insert->agent_Id                   = $req->agent_Id;
            $insert->agent_Name                 = $req->agent_Name;
            $insert->currency_symbol            = $req->currency_symbol;
            $insert->currency_conversion        = $req->currency_conversion;
            $insert->conversion_type_Id         = $req->conversion_type_Id;
            $insert->author_Name                = $req->author_Name;
            $insert->f_name                     = $req->f_name;
            $insert->middle_name                = $req->middle_name;
            $insert->email                      = $req->email;
            $insert->mobile                     = $req->mobile;
            $insert->country                    = $req->country;
            $insert->services_Id                = $req->services_Id;
            $insert->services_Name              = $req->services_Name;
            $insert->services_Quantity          = $req->services_Quantity;
            $insert->services_Type              = $req->services_Type;
            $insert->services_Price_Per_KG      = $req->services_Price_Per_KG;
            $insert->services_Price_Per_Unit    = $req->services_Price_Per_Unit;
            $insert->services_Total_Price       = $req->services_Total_Price;
            $insert->services_Comment           = $req->services_Comment;
            
            $insert->update();
            
            $invoice_id                         = $insert->id;
            
            $agent_data                         = DB::table('Agents_detail')->where('id',$req->agent_Id)->select('id','balance')->first();
            if(isset($agent_data)){
                
                if($agent_data->balance != null){
                    $balance_AJ = $agent_data->balance;
                }else{
                    $balance_AJ = 0;
                }
                
                $services_Total_Price   = $insert->services_Total_Price;
                $agent_balance          = $balance_AJ + $services_Total_Price;
                
                DB::table('Agents_detail')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                
                DB::table('agents_ledgers_new')->insert([
                    'agent_id'      => $agent_data->id,
                    'received'      => $insert->services_Total_Price ?? '0',
                    'balance'       => $agent_balance,
                    'invoice_no'    => $insert->id,
                    'customer_id'   => $req->customer_id,
                    'date'          => date('Y-m-d'),
                ]);
            }
            
            if($req->booking_customer_id != '-1'){
                $customer_data = DB::table('booking_customers')->where('id',$req->booking_customer_id)->select('id','balance')->first();
                if(isset($customer_data)){
                    $customer_balance = $customer_data->balance + $insert->services_Total_Price;
                    DB::table('customer_ledger')->insert([
                        'booking_customer'  => $customer_data->id,
                        'received'          => $insert->services_Total_Price,
                        'balance'           => $customer_balance,
                        'invoice_no'        => $insert->id,
                        'customer_id'       => $req->customer_id,
                        'date'              => date('Y-m-d'),
                    ]);
                    
                    DB::table('booking_customers')->where('id',$customer_data->id)->update(['balance'=>$customer_balance]);
                }
            }
            
            DB::commit();
            return response()->json(['status'=>'success','invoice_id'=>$invoice_id,'message'=>'Extra Services Invoice updated Succesfully']); 
        
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
        }
    }
    // Extra Services
    
    public static function createInvoice_MailSend($request,$invoice_Id){
        $mail_Send_Status       = false;
        
        // Umrah Shop
        if($request->token == config('token_UmrahShop')){
            $from_Address           = config('mail_From_Address_UmrahShop');
            $website_Title          = config('mail_Title_UmrahShop');
            $mail_Template_Key      = config('mail_Template_Key_UmrahShop');
            $website_Url            = config('website_Url_UmrahShop');
            $dashboard_Url          = config('mail_From_Dashboard_UmrahShop');
            $mail_Send_Status       = true;
        }
        
        // Umrah Shop
        if($request->token == config('token_HashimTravel')){
            $from_Address           = config('mail_From_Address_HashimTravel');
            $website_Title          = config('mail_Title_HashimTravel');
            $mail_Template_Key      = config('mail_Template_Key_HashimTravel');
            $website_Url            = config('website_Url_HashimTravel');
            $dashboard_Url          = config('mail_From_Dashboard_HashimTravel');
            $mail_Send_Status       = true;
        }
        
        if($mail_Send_Status != false){
            $customer_data              = DB::table('customer_subcriptions')->where('Auth_key',$request->token)->first();
            $data1                      = addManageInvoice::find($invoice_Id);
            
            if(isset($data1->agent_Id) && $data1->agent_Id != null && $data1->agent_Id != ''){
                $agent_data             = DB::table('Agents_detail')->where('customer_id',$customer_data->id)->where('id',$data1->agent_Id)->first();
                $total_paid_amount      = DB::table('invoices_payment_recv')->where('invoice_no',$data1->generate_id)->get();
            }else{
                $agent_data             = DB::table('Agents_detail')->where('customer_id',$customer_data->id)->get();
                $total_paid_amount      = DB::table('invoices_payment_recv')->where('invoice_no',$data1->generate_id)->get();
                // $total_paid_amount      = 0;
            }
            
            $groups_Data                = DB::table('addGroupsdetails')->where('customer_id',$customer_data->id)->where('group_Invoice_No',$invoice_Id)->get();
            $contact_details            = DB::table('contact_details')->where('customer_id',$customer_data->id)->first();
            $invoice_P_details          = DB::table('pay_Invoice_Agent')->where('customer_id',$customer_data->id)->where('invoice_Id',$invoice_Id)->get();
            $total_invoice_Payments     = DB::table('pay_Invoice_Agent')->where('customer_id',$customer_data->id)->where('invoice_Id',$invoice_Id)->first();
            $recieved_invoice_Payments  = DB::table('pay_Invoice_Agent')->where('customer_id',$customer_data->id)->where('invoice_Id',$invoice_Id)->sum('amount_paid');
            $remainig_invoice_Payments  = DB::table('pay_Invoice_Agent')->where('customer_id',$customer_data->id)->where('invoice_Id',$invoice_Id)->sum('remaining_amount');
            $all_countries              = country::all();
            
            $dataN                      = $data1;
            $generate_id                = $dataN->generate_id;
            if($dataN->confirm != null){
                $confirm_status = 'CONFIRMED';
            }else{
                $confirm_status = 'TENTATIVE';
            }
            $comment_Area                       = ' A '.$confirm_status.' invoice ('.$generate_id.') has been created at '.$website_Title.', As per your required services.';
            $passengerDetailAdults              = json_decode($dataN->passengerDetailAdults);
            $passengerDetailChilds              = $dataN->passengerDetailChilds;
            $flights_details                    = json_decode($dataN->flights_details);
            $flights_details_more               = json_decode($dataN->flights_details_more);
            $transportation_details             = json_decode($dataN->transportation_details);
            $markup_details                     = json_decode($dataN->markup_details);
            
            $title                              = $website_Title;
            $data["title"]                      = $title;
            $data["comment_Area"]               = $comment_Area;
            $data["id"]                         = $invoice_Id;
            $data["data"]                       = $dataN;
            $data["passengerDetailAdults"]      = $passengerDetailAdults;
            $data["passengerDetailChilds"]      = $passengerDetailChilds;
            $data["flights_details"]            = $flights_details;
            $data["flights_details_more"]       = $flights_details_more;
            $data["transportation_details"]     = $transportation_details;
            $data["markup_details"]             = $markup_details;
            $data["customer_data"]              = $customer_data;
            $data["contact_details"]            = $contact_details;
            $data["invoice_P_details"]          = $invoice_P_details;
            $data["total_invoice_Payments"]     = $total_invoice_Payments;
            $data["recieved_invoice_Payments"]  = $recieved_invoice_Payments;
            $data["remainig_invoice_Payments"]  = $remainig_invoice_Payments;
            $data["all_countries"]              = $all_countries;
            
            // dd($dataN->generate_id);
            
            // $data = $dataN;
            
            // return view('template.frontend.userdashboard.emails.lead_mail_attachments.send_Invoice_mail',compact(['data']));
            
            // dd('stop');
            
            $customer_Name      = $dataN->f_name ?? 'Customer';
            $invoice_Status     = $confirm_status;
            $file_Name_Original = $dataN->generate_id.'_invoice.pdf';
            $file_Name          = $generate_id.'_invoice.pdf';
            $pdf                = PDF::loadView('template.frontend.userdashboard.emails.lead_mail_attachments.send_Invoice_mail', $data);
            $path               = $dashboard_Url.'/public/invoice/pdf/'.$file_Name;
            Storage::put($path, $pdf->output());
            $pdf_File_Link      = $dashboard_Url.'/storage/app/public/invoice/pdf/'.$file_Name;
            
            $email_Message      = '<div> <h3> Dear '.$customer_Name.',</h3 <h4> A '.$invoice_Status.' invoice has been created at '.$website_Title.', As per your required services. <br> Please check the attachment to view your invoice. <br> <br> Regards, <br> '.$website_Title.' </h4> </div>';
            // dd($email_Message);
            
            // $from_Address       = 'noreply@system.alhijaztours.net';
            // $to_Address         = $email_Address[$a];
            // $mail_Check         = Mail3rdPartyController::mail_Check($from_Address,$to_Address,$email_Message,$pdf_File_Link,$file_Name_Original);
            // dd($mail_Check);
            
            
            $lead_email                 = $request->f_name ?? '';
            $reciever_Name              = $request->email ?? '';
            $details                    = [
                'lead_Name'             => $reciever_Name,
                'email'                 => $lead_email,
            ];
            $to_Address                 = 'ua758323@gmail.com';
            // $to_Address                 = $lead_email;
            
            // $invoice_Link               = $dashboard_Url.'/invoice_Agent/'.$invoice_Id;
            // $email_Message              = '<div> <h3> Dear '.$details['lead_Name'].', </h3> Your Invoice has been Created. Click on the link below to view detials. <br><br> <h3>Invoice Details: '.$invoice_Link.'</h3> <br> Do you have any questions or require further assistance, please do not hesitate to contact us. <br> <br> Regards, <br> '.$website_Title.' </div>';
            // $mail_Check                 = Mail3rdPartyController::mail_Check_All($from_Address,$to_Address,$reciever_Name,$email_Message,$mail_Template_Key);
            $mail_Check                 = Mail3rdPartyController::mail_Check_Invoice($from_Address,$to_Address,$reciever_Name,$email_Message,$mail_Template_Key,$pdf_File_Link,$file_Name_Original);
            dd($mail_Check);
            
            if($mail_Check == 'Success'){
                return 'Success';
            }else{
                return 'Error';
            }
        }else{
            return 'Error';
        }
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
       
        DB::beginTransaction();
        try {
            
            if(isset($req->accomodation_details)){
                $accomodation_data      = json_decode($req->accomodation_details);
                $accomodation_more_data = json_decode($req->more_accomodation_details);
                
                // dd($accomodation_data);
                
                if(isset($accomodation_data)){
                    foreach($accomodation_data as $index => $acc_res){
                        if($acc_res->room_select_type == 'true'){
                            $room_type_data             = json_decode($acc_res->new_rooms_type);
                            $Rooms                      = new Rooms;
                            $Rooms->hotel_id            =  $acc_res->hotel_id;
                            $Rooms->rooms_on_rq         = '';
                            $Rooms->room_type_id        =  $room_type_data->parent_cat; 
                            $Rooms->room_type_name      =  $room_type_data->room_type; 
                            $Rooms->room_type_cat       =  $room_type_data->id; 
                            $Rooms->SU_id               =  $req->SU_id ?? NULL;
                            $Rooms->quantity            =  $acc_res->acc_qty;  
                            $Rooms->min_stay            =  0; 
                            $Rooms->max_child           =  1; 
                            $Rooms->max_adults          =  $room_type_data->no_of_persons; 
                            $Rooms->extra_beds          =  0; 
                            $Rooms->extra_beds_charges  =  0; 
                            $Rooms->availible_from      =  $acc_res->acc_check_in; 
                            $Rooms->availible_to        =  $acc_res->acc_check_out; 
                            $Rooms->room_option_date    =  $acc_res->acc_check_in; 
                            $Rooms->price_week_type     =  'for_all_days'; 
                            $Rooms->price_all_days      =  $acc_res->price_per_room_purchase;
                            $Rooms->room_supplier_name  =  $acc_res->new_supplier_id;
                            $Rooms->room_meal_type      = $acc_res->hotel_meal_type;
                            // $Rooms->weekdays = serialize($request->weekdays);
                            $Rooms->weekdays            = null;
                            $Rooms->weekdays_price =  NULL; 
                            // $Rooms->weekends =  serialize($request->weekend); 
                            $Rooms->weekends            =  null;
                            $Rooms->weekends_price      =  NULL; 
                            $user_id                    = $req->customer_id;
                            $Rooms->owner_id            = $user_id;
                            
                            $result                     = $Rooms->save();
                            $Roomsid                    = $Rooms->id;
                            
                            $supplier_data              = DB::table('rooms_Invoice_Supplier')->where('id',$Rooms->room_supplier_name)->select('id','balance','payable')->first();
                            if(isset($supplier_data)){
                                $week_days_total        = 0;
                                $week_end_days_totals   = 0;
                                $total_price            = 0;
                                if($Rooms->price_week_type == 'for_all_days'){
                                    $avaiable_days  = dateDiffInDays($Rooms->availible_from, $Rooms->availible_to);
                                    $total_price    = $Rooms->price_all_days * $avaiable_days;
                                }else{
                                    $avaiable_days  = dateDiffInDays($Rooms->availible_from, $Rooms->availible_to);
                                    $all_days       = getBetweenDates($Rooms->availible_from, $Rooms->availible_to);
                                    $week_days      = json_decode($Rooms->weekdays);
                                    $week_end_days  = json_decode($Rooms->weekends);
                                    
                                    foreach($all_days as $day_res){
                                        $day                = date('l', strtotime($day_res));
                                        $day                = trim($day);
                                        $week_day_found     = false;
                                        $week_end_day_found = false;
                                        
                                        foreach($week_days as $week_day_res){
                                            if($week_day_res == $day){
                                                $week_day_found = true;
                                            }
                                        }
                                        if($week_day_found){
                                            $week_days_total        += $Rooms->weekdays_price;
                                        }else{
                                            $week_end_days_totals   += $Rooms->weekends_price;
                                        }
                                    }
                                    $total_price = $week_days_total + $week_end_days_totals;
                                }    
                                $all_days_price     = $total_price * $Rooms->quantity;
                                
                                // dd($all_days_price);
                                
                                $supplier_balance   = $supplier_data->balance + $all_days_price;
                                $supplier_payable   = $supplier_data->payable + $all_days_price;
                                
                                // dd('Acc If',$supplier_balance,$supplier_payable);
                                
                                // update Agent Balance
                                DB::table('hotel_supplier_ledger')->insert([
                                    'SU_id' => $req->SU_id ?? NULL,
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
                                
                                $different_In_Price     = $acc_res->acc_total_amount_purchase ?? 0;
                                $supplier_balance_New   = $supplier_data->balance + $different_In_Price;
                                $supplier_payable_New   = $supplier_data->payable + $different_In_Price;
                                
                                DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance_New,'payable'=>$supplier_payable_New]);
                            }
                            
                            $accomodation_data[$index]->acc_type            = $room_type_data->parent_cat;
                            $accomodation_data[$index]->hotel_supplier_id   = $acc_res->new_supplier_id;
                            $accomodation_data[$index]->hotel_type_id       = $room_type_data->id;
                            $accomodation_data[$index]->hotel_type_cat      = $room_type_data->room_type;
                            $accomodation_data[$index]->hotelRoom_type_id   = $Roomsid;
                        }
                        else{
                            // dd($acc_res);
                            // $accomodation_res = $acc_res;
                            // if(isset($accomodation_res->hotelRoom_type_id)){
                            //     $room_data = DB::table('rooms')->where('id',$accomodation_res->hotelRoom_type_id)->first();
                                
                            //     if($room_data){
                            //         $supplier_data  = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
                                    
                            //         if(isset($supplier_data)){
                            //             $week_days_total                    = 0;
                            //             $week_end_days_totals               = 0;
                            //             $total_price                        = 0;
                            //             $accomodation_res->acc_check_in     = date('Y-m-d',strtotime($accomodation_res->acc_check_in));
                            //             $accomodation_res->acc_check_out    = date('Y-m-d',strtotime($accomodation_res->acc_check_out));
                                        
                            //             if($room_data->price_week_type == 'for_all_days'){
                            //                 $avaiable_days          = dateDiffInDays($accomodation_res->acc_check_in, $accomodation_res->acc_check_out);
                            //                 $total_price            = $room_data->price_all_days * $avaiable_days;
                            //             }else{
                            //                 $avaiable_days  = dateDiffInDays($accomodation_res->acc_check_in, $accomodation_res->acc_check_out);
                            //                 $all_days       = getBetweenDates($accomodation_res->acc_check_in, $accomodation_res->acc_check_out);
                            //                 $week_days      = json_decode($room_data->weekdays);
                            //                 $week_end_days  = json_decode($room_data->weekends);
                                            
                            //                 foreach($all_days as $day_res){
                            //                     $day                = date('l', strtotime($day_res));
                            //                     $day                = trim($day);
                            //                     $week_day_found     = false;
                            //                     $week_end_day_found = false;
                                                
                            //                     foreach($week_days as $week_day_res){
                            //                         if($week_day_res == $day){
                            //                             $week_day_found = true;
                            //                         }
                            //                     }
                                                
                            //                     if($week_day_found){
                            //                         $week_days_total        += $room_data->weekdays_price;
                            //                     }else{
                            //                         $week_end_days_totals   += $room_data->weekends_price;
                            //                     }
                            //                 }
                                            
                            //                 $total_price            = $week_days_total + $week_end_days_totals;
                            //             }
                                        
                            //             $all_days_price         = $total_price * $accomodation_res->acc_qty;
                            //             $supplier_balance       = $supplier_data->balance + $all_days_price;
                            //             $supplier_payable       = $supplier_data->payable + $all_days_price;
                                        
                            //             // dd('Acc Else',$supplier_balance,$supplier_payable);
                                        
                            //             DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                            //                 'balance'           => $supplier_balance,
                            //                 'payable'           => $supplier_payable
                            //             ]);
                            //         }
                            //     }
                            // }
                            
                            $acc_Supplier_Id            = $acc_res->hotel_supplier_id ?? $acc_res->new_supplier_id ?? '';
                            if($acc_Supplier_Id > 0){
                                $supplier_data          = DB::table('rooms_Invoice_Supplier')->where('id',$acc_Supplier_Id)->select('id','balance','payable')->first();
                                // dd($supplier_data);
                                if(isset($supplier_data)){
                                    $different_In_Price = $acc_res->acc_total_amount_purchase ?? 0;
                                    $supplier_balance   = $supplier_data->balance + $different_In_Price;
                                    $supplier_payable   = $supplier_data->payable + $different_In_Price;
                                    
                                    DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance,'payable'=>$supplier_payable]);
                                }
                            }
                        }
                    }
                }
                
                if(isset($accomodation_more_data)){
                    foreach($accomodation_more_data as $index => $acc_res){
                        if($acc_res->more_room_select_type == 'true'){
                            $room_type_data             = json_decode($acc_res->more_new_rooms_type);
                            $Rooms                      = new Rooms;
                            $Rooms->hotel_id            = $acc_res->more_hotel_id;
                            $Rooms->rooms_on_rq         = '';
                            $Rooms->room_type_id        = $room_type_data->parent_cat; 
                            $Rooms->room_type_name      = $room_type_data->room_type; 
                            $Rooms->room_type_cat       = $room_type_data->id; 
                            $Rooms->SU_id               = $req->SU_id ?? NULL;
                            $Rooms->quantity            = $acc_res->more_acc_qty;  
                            $Rooms->min_stay            = 0; 
                            $Rooms->max_child           = 1; 
                            $Rooms->max_adults          = $room_type_data->no_of_persons; 
                            $Rooms->extra_beds          = 0; 
                            $Rooms->extra_beds_charges  = 0; 
                            $Rooms->availible_from      = $acc_res->more_acc_check_in; 
                            $Rooms->availible_to        = $acc_res->more_acc_check_out; 
                            $Rooms->room_option_date    = $acc_res->more_acc_check_in; 
                            $Rooms->price_week_type     = 'for_all_days'; 
                            $Rooms->price_all_days      = $acc_res->more_price_per_room_purchase;
                            $Rooms->room_supplier_name  = $acc_res->more_new_supplier_id;
                            $Rooms->room_meal_type      = $acc_res->more_hotel_meal_type;
                            // $Rooms->weekdays = serialize($request->weekdays);
                            $Rooms->weekdays            = null;
                            $Rooms->weekdays_price      = NULL; 
                            // $Rooms->weekends =  serialize($request->weekend); 
                            $Rooms->weekends            =  null;
                            $Rooms->weekends_price      =  NULL; 
                            $user_id                    = $req->customer_id;
                            $Rooms->owner_id            = $user_id;
                            $result                     = $Rooms->save();
                            $Roomsid                    = $Rooms->id;
                            
                            $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$Rooms->room_supplier_name)->select('id','balance','payable')->first();
                            if(isset($supplier_data)){
                                
                                $week_days_total        = 0;
                                $week_end_days_totals   = 0;
                                $total_price            = 0;
                                
                                if($Rooms->price_week_type == 'for_all_days'){
                                    $avaiable_days      = dateDiffInDays($Rooms->availible_from, $Rooms->availible_to);
                                    $total_price        = $Rooms->price_all_days * $avaiable_days;
                                }
                                
                                $all_days_price         = $total_price * $Rooms->quantity;
                                $supplier_balance       = $supplier_data->balance + $all_days_price;
                                $supplier_payable       = $supplier_data->payable + $all_days_price;
                                
                                // dd('More Acc If',$supplier_balance,$supplier_payable);
                                
                                DB::table('hotel_supplier_ledger')->insert([
                                    'SU_id'             => $req->SU_id ?? NULL,
                                    'supplier_id'       => $supplier_data->id,
                                    'payment'           => $all_days_price,
                                    'balance'           => $supplier_balance,
                                    'payable_balance'   => $supplier_data->payable,
                                    'room_id'           => $Roomsid,
                                    'customer_id'       => $user_id,
                                    'date'              => date('Y-m-d'),
                                    'available_from'    => $Rooms->availible_from,
                                    'available_to'      => $Rooms->availible_to,
                                    'room_quantity'     => $Rooms->quantity,
                                ]);
                                
                                $different_In_Price     = $acc_res->more_acc_total_amount_purchase ?? 0;
                                $supplier_balance_New   = $supplier_data->balance + $different_In_Price;
                                $supplier_payable_New   = $supplier_data->payable + $different_In_Price;
                                
                                DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance_New,'payable'=>$supplier_payable_New]);
                            }
                            
                            $accomodation_more_data[$index]->more_acc_type          = $room_type_data->parent_cat;
                            $accomodation_more_data[$index]->more_hotel_supplier_id = $acc_res->more_new_supplier_id;
                            $accomodation_more_data[$index]->more_hotel_type_id     = $room_type_data->id;
                            $accomodation_more_data[$index]->more_hotel_type_cat    = $room_type_data->room_type;
                            $accomodation_more_data[$index]->more_hotelRoom_type_id = $Roomsid;
                        }
                        else{
                            // $accomodation_res = $acc_res;
                            // if(isset($accomodation_res->more_hotelRoom_type_id)){
                            //     $room_data          = DB::table('rooms')->where('id',$accomodation_res->more_hotelRoom_type_id)->first();
                            //     if($room_data){
                            //         $supplier_data  = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
                                    
                            //         if(isset($supplier_data)){
                            //             $week_days_total                    = 0;
                            //             $week_end_days_totals               = 0;
                            //             $total_price                        = 0;
                            //             $accomodation_res->acc_check_in     = date('Y-m-d',strtotime($accomodation_res->more_acc_check_in));
                            //             $accomodation_res->acc_check_out    = date('Y-m-d',strtotime($accomodation_res->more_acc_check_out));
                                        
                            //             if($room_data->price_week_type == 'for_all_days'){
                            //                 $avaiable_days                  = dateDiffInDays($accomodation_res->acc_check_in, $accomodation_res->acc_check_out);
                            //                 $total_price                    = $room_data->price_all_days * $avaiable_days;
                            //             }else{
                            //                 $avaiable_days                  = dateDiffInDays($accomodation_res->acc_check_in, $accomodation_res->acc_check_out);
                            //                 $all_days                       = getBetweenDates($accomodation_res->acc_check_in, $accomodation_res->acc_check_out);
                            //                 $week_days                      = json_decode($room_data->weekdays);
                            //                 $week_end_days                  = json_decode($room_data->weekends);
                                            
                            //                 foreach($all_days as $day_res){
                            //                     $day                        = date('l', strtotime($day_res));
                            //                     $day                        = trim($day);
                            //                     $week_day_found             = false;
                            //                     $week_end_day_found         = false;
                                                
                            //                     foreach($week_days as $week_day_res){
                            //                         if($week_day_res == $day){
                            //                             $week_day_found = true;
                            //                         }
                            //                     }
                                                
                            //                     if($week_day_found){
                            //                         $week_days_total += $room_data->weekdays_price;
                            //                     }else{
                            //                         $week_end_days_totals += $room_data->weekends_price;
                            //                     }
                            //                 }
                                            
                            //                 $total_price = $week_days_total + $week_end_days_totals;
                            //             }
                                        
                            //             $all_days_price             = $total_price * $accomodation_res->more_acc_qty;
                            //             $supplier_balance           = $supplier_data->balance + $all_days_price;
                            //             $supplier_payable           = $supplier_data->payable + $all_days_price;
                                        
                            //             // dd('More Acc If',$supplier_balance,$supplier_payable);
                                        
                            //             DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                            //                 'balance'               => $supplier_balance,
                            //                 'payable'               => $supplier_payable
                            //             ]);
                            //         }
                            //     }
                            // }
                            
                            $acc_Supplier_Id            = $acc_res->more_hotel_supplier_id ?? $acc_res->more_new_supplier_id ?? '';
                            if($acc_Supplier_Id > 0){
                                $supplier_data          = DB::table('rooms_Invoice_Supplier')->where('id',$acc_Supplier_Id)->select('id','balance','payable')->first();
                                // dd($supplier_data);
                                if(isset($supplier_data)){
                                    $different_In_Price = $acc_res->more_acc_total_amount_purchase ?? 0;
                                    $supplier_balance   = $supplier_data->balance + $different_In_Price;
                                    $supplier_payable   = $supplier_data->payable + $different_In_Price;
                                    
                                    DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance,'payable'=>$supplier_payable]);
                                }
                            }
                        }
                    }
                }
                
                $accomodation_data      = json_encode($accomodation_data);
                $accomodation_more_data = json_encode($accomodation_more_data);
            }else{
                $accomodation_data      = '';
                $accomodation_more_data = '';
            }
            
            // dd(json_decode($accomodation_data));
            
            $visa_details = json_decode($req->all_visa_price_details);
            if(isset($visa_details)){
                foreach($visa_details as $index => $visa_res){
                    
                    // 1 Check Add New Visa or Exists Use
                    if($visa_res->visa_add_type_new !== 'false'){
                        // Add As New
                        
                        $visa_avail_id = DB::table('visa_Availability')->insertGetId([
                                'SU_id' => $req->SU_id ?? NULL,
                                'visa_supplier' => $visa_res->visa_supplier_id,
                                'visa_type' => $visa_res->visa_type_id,
                                'visa_qty' => $visa_res->visa_occupied,
                                'visa_available' => $visa_res->visa_occupied,
                                'visa_price' => $visa_res->visa_fee_purchase,
                                'availability_from' => $visa_res->visa_av_from,
                                'availability_to' => $visa_res->visa_av_to,
                                'country' => $visa_res->visa_country_id,
                                'currency_conversion' => $req->conversion_type_Id,
                                'visa_price_conversion_rate' => $visa_res->exchange_rate_visa ?? '',
                                'visa_converted_price'=> $visa_res->visa_fee,
                                'customer_id' => $req->customer_id,
                        ]);
                        
                           $visa_details[$index]->visa_avail_id = $visa_avail_id;
                           $supplier_data = DB::table('visa_Sup')->where('id',$visa_res->visa_supplier_id)->first();
                            
                            if(isset($supplier_data)){
                                $supplier_balance = $supplier_data->balance + $visa_res->visa_purchase_total;
                                
                                DB::table('visa_supplier_ledger')->insert([
                                        'SU_id' => $req->SU_id ?? NULL,
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
            
            $insert                         = new addManageInvoice();
            //new additon
            //1
            $insert->customer_id             = $req->customer_id;
            
            if(isset($req->SU_id) && $req->SU_id != null && $req->SU_id != ''){
                $insert->SU_id               = $req->SU_id;
            }
            
            $insert->hotel_Supplier_Details  = $req->hotel_Supplier_Details ?? '';
            
            $insert->booking_customer_id     = $req->booking_customer_id;
            $insert->services                = $req->services;
            $insert->agent_Id                = $req->agent_Id;
            $insert->agent_Name              = $req->agent_Name;
            $insert->agent_Company_Name      = $req->agent_Company_Name;
            
            // dd($req->b2b_Agent_Id,$req->b2b_Agent_Name);
            
            $insert->b2b_Agent_Company_Name  = $req->b2b_Agent_Company_Name;
            $insert->b2b_Agent_Id            = $req->b2b_Agent_Id;
            $insert->b2b_Agent_Name          = $req->b2b_Agent_Name;
            
            $insert->prefix                  = $req->prefix;
            $insert->f_name                  = $req->f_name;
            $insert->middle_name             = $req->middle_name;
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
            
            $insert->passport_Image          = $req->passport_Image ?? '';
            
            $insert->exchange_Rate_For_All   = $req->exchange_Rate_For_All ?? '';
            
            // Lead Pass
            $insert->lead_title             = $req->lead_title ?? '';
            $insert->lead_fname             = $req->first_Name_passport ?? '';
            $insert->lead_lname             = $req->last_Name_passport ?? '';
            $insert->lead_gender            = $req->gender ?? '';
            $insert->lead_dob               = $req->date_of_birth_passport ?? '';
            $insert->lead_nationality       = $req->nationality_passport ?? '';
            $insert->lead_passport_number   = $req->passport_Number ?? '';
            $insert->lead_passport_expiry   = $req->passport_Expiry ?? '';
            $insert->more_Passenger_Data    = $req->more_Passenger_Data ?? '';
            $insert->count_P_Input          = $req->count_P_Input ?? '';
            // Lead Pass
            
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
            
            $insert->accomodation_details           = $accomodation_data;
            $insert->accomodation_details_more      = $accomodation_more_data;
            $insert->transportation_details         = $req->transportation_details;
            $insert->transportation_details_more    = $req->transportation_details_more;
            
            $insert->visa_type                      = $req->visa_type;
            $insert->visa_fee                       = $req->visa_fee;
            $insert->visa_Pax                       = $req->visa_Pax;
            $insert->exchange_rate_visaI            = $req->exchange_rate_visaI;
            $insert->total_visa_markup_value        = $req->total_visa_markup_value;
            $insert->exchange_rate_visa_fee         = $req->exchange_rate_visa_fee;
            
            if($req->more_visa_details != '""' && $req->more_visa_details != ''){
                $insert->more_visa_details              = $req->more_visa_details;
            }
            
            $insert->all_visa_price_details             = $visa_details;
            
            $insert->visa_rules_regulations             = $req->visa_rules_regulations;
            $insert->visa_image                         = $req->visa_image;
            
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
            
            $insert->hotel_Reservation_details  = $req->hotel_Reservation_details ?? '';
            $insert->transfer_Reservation_No    = $req->transfer_Reservation_No ?? '';
            $insert->flight_Reservation_No      = $req->flight_Reservation_No ?? '';
            $insert->visa_Reservation_No        = $req->visa_Reservation_No ?? '';
            
            $insert->ziyarat_details            = $req->ziyarat_details ?? '';
            
            $insert->lead_currency              = $req->lead_currency ?? '';
            
            $insert->currency_Rate_AC           = $req->currency_Rate_AC ?? '';
            $insert->currency_Type_AC           = $req->currency_Type_AC ?? '';
            $insert->currency_value_AC          = $req->currency_value_AC ?? '';
            $insert->total_cost_price_AC        = $req->total_cost_price_AC ?? '';
            $insert->total_sale_price_AC        = $req->total_sale_price_AC ?? '';
            
            $insert->currency_Rate_Company      = $req->currency_Rate_Company ?? '';
            $insert->currency_Type_Company      = $req->currency_Type_Company ?? '';
            $insert->currency_value_Company     = $req->currency_value_Company ?? '';
            $insert->total_cost_price_Company   = $req->total_cost_price_Company ?? '';
            $insert->total_sale_price_Company   = $req->total_sale_price_Company ?? '';
            
            $insert->groups_id                  = $req->groups_id ?? '';
            $insert->agent_Currency             = $req->agent_Currency ?? '';
            $insert->agent_Currency_all_details = $req->agent_Currency_all_details ?? '';
            // $insert->visa_group_pax             = $req->visa_group_pax ?? '';
            
            $insert->visa_supplier_details      = $req->visa_supplier_details ?? '';
            
            $insert->group_Name                 = $req->group_Name ?? '';
            
            $insert->costing_Switch             = $req->costing_Switch ?? '';
            
            $today                              = Carbon::now();
            $formattedDate                      = $today->format('d M Y');
            $formattedTime                      = $today->format('H:i');
            if(isset($req->SU_id) && $req->SU_id > 0){
                $sub_User_Data                  = DB::table('role_managers')->where('customer_id',$req->customer_id)->where('id',$req->SU_id)->first();
                $created_Remarks                = $sub_User_Data->first_name.' '.$sub_User_Data->last_name;
                // $created_Remarks                = $sub_User_Data->first_name.' '.$sub_User_Data->last_name.' create invoice on '.$formattedDate.' at '.$formattedTime;
            }else{
                $sub_User_Data                  = DB::table('customer_subcriptions')->where('id',$req->customer_id)->first();
                $created_Remarks                = $sub_User_Data->name.' '.$sub_User_Data->lname;
                // $created_Remarks                = $sub_User_Data->name.' '.$sub_User_Data->lname.' create invoice on '.$formattedDate.' at '.$formattedTime;
            }
            // $insert->created_Remarks            = $created_Remarks ?? NULL;
            
            if(isset($req->quotation_id) && $req->quotation_id != null && $req->quotation_id != ''){
                $q_d = DB::table('addManageQuotationPackage')->where('customer_id',$req->customer_id)->where('id',$req->quotation_id)->first();
                if(isset($q_d->agents_Enquiry) && $q_d->agents_Enquiry != null){
                    $country_code       = $req->country;
                    $all_countries      = country::all();
                    foreach($all_countries as $val_country){
                        if($val_country->id == $req->country){
                            $country_code = $val_country->iso2;
                        }
                    }
                    $generate_RN                        = rand(0,9999);
                    $enquiry_Ref_No                     = 'TT-'.$country_code.'-'.$generate_RN.'-INV';
                    $insert->enquiry_Ref_No             = $enquiry_Ref_No;
                    $insert->agents_Enquiry             = $q_d->agents_Enquiry;
                    $insert->hotel_supplier_for_price   = $q_d->hotel_supplier_for_price ?? '';
                }else{
                    $insert->enquiry_Ref_No             = null;
                    $insert->agents_Enquiry             = null;
                    $insert->hotel_supplier_for_price   = null;
                }
            }
            
            $insert->save();
            
            $invoice_id                         = $insert->id;
            
            if(isset($req->otherPaxDetails)){
                if($req->paxAdded > 1){
                    $otherPaxDetails = json_decode($req->otherPaxDetails);
                    foreach($otherPaxDetails as $val_PD){
                        $otherPaxDetail                 = new otherPaxDetail();
                        $otherPaxDetail->customer_id    = $req->customer_id;
                        $otherPaxDetail->invoice_id     = $insert->id;
                        $otherPaxDetail->pilgramsName   = $val_PD->pilgramsName;
                        $otherPaxDetail->passportNumber = $val_PD->passportNumber;
                        $otherPaxDetail->dateOfBirth    = $val_PD->dateOfBirth;
                        $otherPaxDetail->groupType      = $val_PD->groupType;
                        $otherPaxDetail->issueDate      = $val_PD->issueDate;
                        $otherPaxDetail->groupCode      = $val_PD->groupCode;
                        $otherPaxDetail->save();
                    }
                }
            }
            
            // if($req->token == config('token_UmrahShop') || $req->token == config('token_HashimTravel')){
            //     $mailSend = $this->createInvoice_MailSend($req,$insert->id);
            //     // dd($mailSend);
            // }
            
            // Remarks
            $insertRemark                       = new addManageInvoiceRemark();
            $insertRemark->customer_id          = $req->customer_id;
            $insertRemark->invoice_Id           = $insert->id;
            $insertRemark->remark_Name          = $created_Remarks;
            $insertRemark->remark_Status        = 'create_Invoice';
            $insertRemark->invoice_Remarks      = $req->invoice_Remarks ?? NULL;
            $insertRemark->save();
            // Remarks
            
            if($req->groups_id != null && $req->groups_id != ''){
                $groups_id = json_decode($req->groups_id);
                foreach($groups_id as $val_groups_id){
                    DB::table('addGroupsdetails')->where('customer_id',$req->customer_id)->where('id',$val_groups_id)->update([
                        'group_Invoice_No'  => $invoice_id,
                        'total_Invoices'    => 1,
                    ]);
                }
            }
            
            if(isset($req->note_Section) && $req->note_Section != null && $req->note_Section != ''){
                $upload_Documents = new uploadDocumentInvoice();
                
                $upload_Documents->customer_id      = $req->customer_id ?? '';
                $upload_Documents->invoice_Id       = $invoice_id ?? '';
                $upload_Documents->date_Upload      = $req->date_Upload ?? '';
                $upload_Documents->note_Section     = $req->note_Section ?? '';
                $upload_Documents->upload_Documents = $req->upload_Documents ?? '';
                
                $upload_Documents->save();
            }
            
            // $all_invoices_data = DB::table('add_manage_invoices')->where('customer_id',$req->customer_id)->where('groups_id',$req->groups_id)->count();
            // DB::table('addGroupsdetails')->where('customer_id',$req->customer_id)->where('id',$req->groups_id)->update(['total_Invoices'=>$all_invoices_data]);
            
            $agent_data = DB::table('Agents_detail')->where('id',$req->agent_Id)->select('id','balance')->first();
            
            if(isset($agent_data)){
                
                if($agent_data->balance != null){
                    $balance_AJ = $agent_data->balance;
                }else{
                    $balance_AJ = 0;
                }
                
                if($insert->total_sale_price_AC != null){
                    $total_sale_price_AC = $insert->total_sale_price_AC;
                }else{
                    $total_sale_price_AC = $insert->total_sale_price_all_Services;
                }
                
                $agent_balance = $balance_AJ + $total_sale_price_AC;
                
                DB::table('Agents_detail')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                
                DB::table('agents_ledgers_new')->insert([
                    'agent_id'      => $agent_data->id,
                    'received'      => $insert->total_sale_price_AC ?? '0',
                    'balance'       => $agent_balance,
                    'invoice_no'    => $insert->id,
                    'customer_id'   => $req->customer_id,
                    'date'          => date('Y-m-d'),
                ]);
            }
            
            if($req->booking_customer_id != '-1'){
                $customer_data = DB::table('booking_customers')->where('id',$req->booking_customer_id)->select('id','balance')->first();
                // print_r($agent_data);
                if(isset($customer_data)){
                    
                    // if($customer_data->balance != null){
                    //     $balance_CB = $customer_data->balance;
                    // }else{
                    //     $balance_CB = 0;
                    // }
                    
                    // if($insert->total_sale_price_AC != null){
                    //     $total_sale_price_CB = $insert->total_sale_price_AC;
                    // }else{
                    //     $total_sale_price_CB = $insert->total_sale_price_all_Services;
                    // }
                    
                    // $customer_balance = $balance_CB + $total_sale_price_CB;
                    
                    $customer_balance = $customer_data->balance + $insert->total_sale_price_all_Services;
                    
                    // update Agent Balance
                    DB::table('customer_ledger')->insert([
                        'booking_customer'  => $customer_data->id,
                        'received'          => $insert->total_sale_price_all_Services,
                        'balance'           => $customer_balance,
                        'invoice_no'        => $insert->id,
                        'customer_id'       => $req->customer_id,
                        'date'              => date('Y-m-d'),
                    ]);
                    
                    DB::table('booking_customers')->where('id',$customer_data->id)->update(['balance'=>$customer_balance]);
                }
            }
            
            // B2B Agents
            $agent_data = DB::table('b2b_agents')->where('id',$req->b2b_Agent_Id)->select('id','balance')->first();
            if(isset($agent_data)){
                
                if($agent_data->balance != null){
                    $balance_AJ = $agent_data->balance;
                }else{
                    $balance_AJ = 0;
                }
                
                if($insert->total_sale_price_AC != null){
                    $total_sale_price_AC = $insert->total_sale_price_AC;
                }else{
                    $total_sale_price_AC = $insert->total_sale_price_all_Services;
                }
                
                $agent_balance = $balance_AJ + $total_sale_price_AC;
                
                DB::table('b2b_agents')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                
                DB::table('agents_ledgers_new')->insert([
                    'b2b_Agent_id'  => $agent_data->id,
                    'received'      => $insert->total_sale_price_AC ?? '0',
                    'balance'       => $agent_balance,
                    'invoice_no'    => $insert->id,
                    'customer_id'   => $req->customer_id,
                    'date'          => date('Y-m-d'),
                ]);
            }
            // B2B Agents
            
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
            
            $flights_Pax_details = json_decode($req->flights_Pax_details);
            if(isset($flights_Pax_details) && $flights_Pax_details != null && $flights_Pax_details != ''){
                foreach($flights_Pax_details as $value){
                    
                    DB::table('flight_seats_occupied')->insert([
                        'SU_id'                         => $req->SU_id ?? NULL,
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
                    $supplier_data                  = DB::table('supplier')->where('id',$req->flight_supplier)->first();
                    
                    //  Calculate Child Price
                    $child_price_wi_adult_price     = $value->flights_cost_per_seats_adult * $value->flights_adult_seats;
                    $child_price_wi_child_price     = $value->flights_cost_per_seats_child * $value->flights_child_seats;
                    $infant_price                   = $value->flights_cost_per_seats_infant * $value->flights_infant_seats;
                    // New
                    $total_Balance                  = $child_price_wi_adult_price + $child_price_wi_child_price + $infant_price;
                    // New
                    $price_diff                     = $child_price_wi_adult_price - $child_price_wi_child_price;
                    
                    if($price_diff != 0 || $infant_price != 0){
                        $supplier_balance           = $supplier_data->balance - $price_diff;
                        $supplier_balance           = $supplier_balance + $infant_price;
                        $total_differ               = $infant_price - $price_diff;
                        DB::table('flight_supplier_ledger')->insert([
                            'SU_id'                 => $req->SU_id ?? NULL,
                            'supplier_id'           => $supplier_data->id,
                            'payment'               => $total_differ,
                            'balance'               => $supplier_balance,
                            'route_id'              => $value->flight_route_id_occupied,
                            'date'                  => date('Y-m-d'),
                            'customer_id'           => $insert->customer_id,
                            'adult_price'           => $value->flights_cost_per_seats_adult,
                            'child_price'           => $value->flights_cost_per_seats_child,
                            'infant_price'          => $value->flights_cost_per_seats_infant,
                            'adult_seats_booked'    => $value->flights_adult_seats,
                            'child_seats_booked'    => $value->flights_child_seats,
                            'infant_seats_booked'   => $value->flights_infant_seats,
                            'invoice_no'            => $insert->id,
                            'remarks'               => 'Invoice Booked',
                        ]);
                        
                        // DB::table('supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                        
                        // New
                        $supplier_balance_New = $total_Balance + $supplier_data->balance;
                        DB::table('supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance_New]);
                        // New
                    }
                }
            }
            
            if(isset($accomodation) && $accomodation != null && $accomodation != ''){
                $accomodation      = json_decode($accomodation_data);
                // dd($accomodation);
                if(isset($accomodation)){
                    foreach($accomodation as $accomodation_res){
                        if(isset($accomodation_res->hotelRoom_type_id)){
                            $room_data = DB::table('rooms')->where('id',$accomodation_res->hotelRoom_type_id)->first();
                            
                            if($room_data){
                                $total_booked = $room_data->booked + $accomodation_res->acc_qty;
                                DB::table('rooms_bookings_details')->insert([
                                   'SU_id'          => $req->SU_id ?? NULL,
                                    'room_id'       => $accomodation_res->hotelRoom_type_id,
                                    'booking_from'  => 'Invoices',
                                    'quantity'      => $accomodation_res->acc_qty,
                                    'booking_id'    => $invoice_id,
                                    'date'          => date('Y-m-d'),
                                    'check_in'      => $accomodation_res->acc_check_in,
                                    'check_out'     => $accomodation_res->acc_check_out,
                                ]);
                                
                                DB::table('rooms')->where('id',$accomodation_res->hotelRoom_type_id)->update(['booked'=>$total_booked]);
                                
                                // Update Hotel Supplier Balance
                                $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
                                
                                if(isset($supplier_data)){
                                    $week_days_total                    = 0;
                                    $week_end_days_totals               = 0;
                                    $total_price                        = 0;
                                    $accomodation_res->acc_check_in     = date('Y-m-d',strtotime($accomodation_res->acc_check_in));
                                    $accomodation_res->acc_check_out    = date('Y-m-d',strtotime($accomodation_res->acc_check_out));
                                    
                                    if($room_data->price_week_type == 'for_all_days'){
                                        $avaiable_days          = dateDiffInDays($accomodation_res->acc_check_in, $accomodation_res->acc_check_out);
                                        $total_price            = $room_data->price_all_days * $avaiable_days;
                                    }else{
                                        $avaiable_days  = dateDiffInDays($accomodation_res->acc_check_in, $accomodation_res->acc_check_out);
                                        $all_days       = getBetweenDates($accomodation_res->acc_check_in, $accomodation_res->acc_check_out);
                                        $week_days      = json_decode($room_data->weekdays);
                                        $week_end_days  = json_decode($room_data->weekends);
                                        
                                        foreach($all_days as $day_res){
                                            $day                = date('l', strtotime($day_res));
                                            $day                = trim($day);
                                            $week_day_found     = false;
                                            $week_end_day_found = false;
                                            
                                            foreach($week_days as $week_day_res){
                                                if($week_day_res == $day){
                                                    $week_day_found = true;
                                                }
                                            }
                                            
                                            if($week_day_found){
                                                $week_days_total        += $room_data->weekdays_price;
                                            }else{
                                                $week_end_days_totals   += $room_data->weekends_price;
                                            }
                                        }
                                        
                                        $total_price            = $week_days_total + $week_end_days_totals;
                                    }
                                    
                                    $all_days_price             = $total_price * $accomodation_res->acc_qty;
                                    $supplier_balance           = $supplier_data->balance + $all_days_price;
                                    $supplier_payable_balance   = $supplier_data->payable + $all_days_price;
                                    
                                    // dd($supplier_data,$all_days_price);
                                    
                                    // update Agent Balance
                                    DB::table('hotel_supplier_ledger')->insert([
                                        'SU_id'             => $req->SU_id ?? NULL,
                                        'supplier_id'       => $supplier_data->id,
                                        'payment'           => $all_days_price,
                                        'balance'           => $supplier_balance,
                                        'payable_balance'   => $supplier_payable_balance,
                                        'room_id'           => $room_data->id,
                                        'customer_id'       => $req->customer_id,
                                        'date'              => date('Y-m-d'),
                                        'invoice_no'        => $invoice_id,
                                        'available_from'    => $accomodation_res->acc_check_in,
                                        'available_to'      => $accomodation_res->acc_check_out,
                                        'room_quantity'     => $accomodation_res->acc_qty,
                                    ]);
                                    
                                    // DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                                    //     'balance'           => $supplier_balance,
                                    //     'payable'           => $supplier_payable_balance
                                    // ]);
                                }
                            }
                        }
                    }
                }
            }
            
            // dd('stop');
            
            if(isset($more_accomodation_details)  && $more_accomodation_details != null && $more_accomodation_details != ''){
                $more_accomodation_details = json_decode($accomodation_more_data);
                // dd($more_accomodation_details);
                if(isset($more_accomodation_details)){
                    foreach($more_accomodation_details as $accomodation_res){
                        if(isset($accomodation_res->more_hotelRoom_type_id)){
                            $room_data = DB::table('rooms')->where('id',$accomodation_res->more_hotelRoom_type_id)->first();
                            if($room_data){
                                
                                $total_booked = $room_data->booked + $accomodation_res->more_acc_qty;
                                
                                DB::table('rooms_bookings_details')->insert([
                                    'SU_id' => $req->SU_id ?? NULL,
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
                                    
                                    $all_days_price             = $total_price * $accomodation_res->more_acc_qty;
                                    $supplier_balance           = $supplier_data->balance + $all_days_price;
                                    $supplier_payable_balance   = $supplier_data->payable + $all_days_price;
                                    
                                    DB::table('hotel_supplier_ledger')->insert([
                                        'SU_id'                 => $req->SU_id ?? NULL,
                                        'supplier_id'           => $supplier_data->id,
                                        'payment'               => $all_days_price,
                                        'balance'               => $supplier_balance,
                                        'payable_balance'       => $supplier_payable_balance,
                                        'room_id'               => $room_data->id,
                                        'customer_id'           => $req->customer_id,
                                        'date'                  => date('Y-m-d'),
                                        'invoice_no'            => $invoice_id,
                                        'available_from'        => $accomodation_res->acc_check_in,
                                        'available_to'          => $accomodation_res->acc_check_out,
                                        'room_quantity'         => $accomodation_res->more_acc_qty,
                                    ]);
                                    
                                    // DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                                    //     'balance'               => $supplier_balance,
                                    //     'payable'               => $supplier_payable_balance
                                    // ]);
                                }
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
                                 if(isset($transfer_sup_data)){
                                      $trans_sup_balance = $transfer_sup_data->balance + $trans_res_data->transportation_vehicle_total_price;
                                        DB::table('transfer_supplier_ledger')->insert([
                                            'SU_id' => $req->SU_id ?? NULL,
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
                        }
                    }else{
                        
                        $total_price = 0;
                        foreach($transfer_data  as $index => $trans_res_data){
                            if($index != 0){
                            $total_price += $trans_res_data->transportation_vehicle_total_price;
                            }
                        }
                      
                        $transfer_sup_data = DB::table('transfer_Invoice_Supplier')->where('id',$req->transfer_supplier_id)->select('id','balance')->first();
                        
                        if(isset($transfer_sup_data)){
                            $trans_sup_balance = $transfer_sup_data->balance + $total_price;
                                
                                
                            DB::table('transfer_supplier_ledger')->insert([
                                'SU_id' => $req->SU_id ?? NULL,
                                'supplier_id'=>$req->transfer_supplier_id,
                                'payment'=> $total_price,
                                'balance'=> $trans_sup_balance,
                                'invoice_no'=>$invoice_id,
                                'remarks'=>'New Invoice Created',
                                'date'=>date('Y-m-d'),
                                'customer_id'=>$req->customer_id,
                            ]);
                            $result = DB::table('transfer_Invoice_Supplier')->where('id',$req->transfer_supplier_id)->update(['balance'=>$trans_sup_balance]);
                        }
                        // dd($result);
                    }
                }
            }
            
            $visa_details = json_decode($visa_details);
            if(isset($visa_details)){
                foreach($visa_details as $index => $visa_res){
                    
                    if(isset($visa_res->visa_avail_id) && !empty($visa_res->visa_avail_id)){
                        // 2 Update No of Seats Occupied in Visa
                    
                        $visa_avail_data = DB::table('visa_Availability')->where('id',$visa_res->visa_avail_id)->first();
                        
                        $updated_seats = $visa_avail_data->visa_available - $visa_res->visa_occupied;
                        
                        DB::table('visa_Availability')->where('id',$visa_res->visa_avail_id)->update([
                                'visa_available' => $updated_seats
                            ]);
                      
                        // 3 Update Visa Supplier Balance
                        $visa_supplier_data = DB::table('visa_Sup')->where('id',$visa_avail_data->visa_supplier)->first();
                        
                        
                        $visa_supplier_payable_balance = $visa_supplier_data->payable + ($visa_res->visa_fee_purchase * $visa_res->visa_occupied);
                        $visa_total_sale = $visa_res->visa_fee_purchase * $visa_res->visa_occupied;
                        
                        
                        DB::table('visa_supplier_ledger')->insert([
                            'SU_id' => $req->SU_id ?? NULL,
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
                    }else{
                        
                         // 3 Update Visa Supplier Balance
                        $visa_supplier_data = DB::table('visa_Sup')->where('id',$visa_res->visa_supplier_id)->first();
                        
                        
                        $visa_supplier_payable_balance = $visa_supplier_data->payable + ($visa_res->visa_fee_purchase * $visa_res->visa_occupied);
                        $visa_total_sale = $visa_res->visa_fee_purchase * $visa_res->visa_occupied;
                
                        
                        DB::table('visa_supplier_ledger')->insert([
                                'SU_id' => $req->SU_id ?? NULL,
                                'supplier_id' => $visa_res->visa_supplier_id,
                                'payment' => $visa_total_sale,
                                'balance' => $visa_supplier_data->balance,
                                'payable' => $visa_supplier_payable_balance,
                                'visa_qty' => $visa_res->visa_occupied,
                                'visa_type' => $visa_res->visa_type_name,
                                'invoice_no' => $invoice_id,
                                'remarks' => 'New Invoice Create',
                                'date' => date('Y-m-d'),
                                'customer_id' => $req->customer_id,
                        ]);
                        
                        DB::table('visa_Sup')->where('id',$visa_res->visa_supplier_id)->update([
                                'payable' => $visa_supplier_payable_balance
                        ]);
                    }
                       
                    
                    
                 
                }
            }
            
            if(isset($req->quotation_id) && $req->quotation_id != null && $req->quotation_id != ''){
                $quotation_Status   = DB::table('addManageQuotationPackage')->where('id',$req->quotation_id)->update(['quotation_status' => '1','mail_request_pax' => '1']);
                if(isset($req->lead_id)){
                    $enquiry_Status     = DB::table('addAgentsEnquiry')->where('customer_id',$req->customer_id)->where('id',$req->lead_id)->where('lead_id',$req->quotation_id)->first();
                    if($enquiry_Status != null && $enquiry_Status != ''){
                        DB::table('addAgentsEnquiry')->where('customer_id',$req->customer_id)->where('id',$req->lead_id)->where('lead_id',$req->quotation_id)->update(['quotation_status' => '1','mail_request_pax' => '1']);
                        $new_enquiry    = DB::table('addAgentsEnquiry')->where('customer_id',$req->customer_id)->where('id',$req->lead_id)->where('lead_id',$req->quotation_id)->first();
                    }
                }
            }
            
            $lead_in_process = 0;
            if(isset($req->SU_id) && $req->SU_id != null && $req->SU_id != ''){
                $all_leads = DB::table('addLead')->where('SU_id',$req->SU_id)->where('customer_id',$req->customer_id)->get();
            }else{
                $all_leads = DB::table('addLead')->where('customer_id',$req->customer_id)->get();
            }
            if(isset($all_leads) && $all_leads != null && $all_leads != ''){
                foreach($all_leads as $all_leads_value){
                    if(isset($req->SU_id) && $req->SU_id != null && $req->SU_id != ''){
                        $lead_quotation         = DB::table('addManageQuotationPackage')->where('SU_id',$req->SU_id)->where('customer_id',$req->customer_id)->where('lead_id',$all_leads_value->id)->get();
                    }else{
                        $lead_quotation         = DB::table('addManageQuotationPackage')->where('customer_id',$req->customer_id)->where('lead_id',$all_leads_value->id)->get();
                    }
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
            
            $slc_services=json_decode($req->services);
            foreach($slc_services as $ser){
                if($ser == 'transportation_tab'){
                    $C_Tracker = DB::table('C_Tracker')->insert([
                        'SU_id' => $req->SU_id ?? NULL,
                      'customer_id'=>$req->customer_id,
                      'invoice'=> $invoice_id,
                      'leadname'=> $req->f_name,
                      'leadmail'=>$req->email,
                      'leadpassword'=>'123456',
                      'location'=>$req->transportation_details,
                      'more_location'=>$req->transportation_details_more
                      ]);
                }
            }
            
            DB::commit();
            return response()->json(['status'=>'success','invoice_id'=>$invoice_id,'lead_in_process'=>$lead_in_process,'message'=>'Agent Invoice added Succesfully']); 
            
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
        }
    }
    
    public function add_Invoices_CP(Request $req){
        // dd('STOP');
        
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
       
        DB::beginTransaction();
        try {
            
            if(isset($req->accomodation_details)){
                $accomodation_data      = json_decode($req->accomodation_details);
                $accomodation_more_data = json_decode($req->more_accomodation_details);
                
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
                                        
                                        $Rooms->SU_id =  $room_type_data->SU_id ?? NULL;
                                        
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
                                                    'SU_id' => $req->SU_id ?? NULL,
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
                                                    
                                                // DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                                                
                                                $different_In_Price     = $acc_res->acc_total_amount_purchase ?? 0;
                                                $supplier_balance_New   = $supplier_data->balance + $different_In_Price;
                                                $supplier_payable_New   = $supplier_data->payable + $different_In_Price;
                                                
                                                DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance_New,'payable'=>$supplier_payable_New]);  
                                            }
                                            
                                        $accomodation_data[$index]->acc_type = $room_type_data->parent_cat;
                                        $accomodation_data[$index]->hotel_supplier_id = $acc_res->new_supplier_id;
                                        $accomodation_data[$index]->hotel_type_id = $room_type_data->id;
                                        $accomodation_data[$index]->hotel_type_cat = $room_type_data->room_type;
                                        $accomodation_data[$index]->hotelRoom_type_id = $Roomsid;
                        }
                        else{
                            $acc_Supplier_Id            = $acc_res->hotel_supplier_id ?? $acc_res->new_supplier_id ?? '';
                            if($acc_Supplier_Id > 0){
                                $supplier_data          = DB::table('rooms_Invoice_Supplier')->where('id',$acc_Supplier_Id)->select('id','balance','payable')->first();
                                // dd($supplier_data);
                                if(isset($supplier_data)){
                                    $different_In_Price = $acc_res->acc_total_amount_purchase ?? 0;
                                    $supplier_balance   = $supplier_data->balance + $different_In_Price;
                                    $supplier_payable   = $supplier_data->payable + $different_In_Price;
                                    
                                    DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance,'payable'=>$supplier_payable]);
                                }
                            }
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
                                        
                                        $Rooms->SU_id =  $room_type_data->SU_id ?? NULL;
                                       
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
                                                    'SU_id' => $req->SU_id ?? NULL,
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
                                                
                                                // DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                                                
                                                $different_In_Price     = $acc_res->more_acc_total_amount_purchase ?? 0;
                                                $supplier_balance_New   = $supplier_data->balance + $different_In_Price;
                                                $supplier_payable_New   = $supplier_data->payable + $different_In_Price;
                                                
                                                DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance_New,'payable'=>$supplier_payable_New]); 
                                            }
                                            
                                        $accomodation_more_data[$index]->more_acc_type = $room_type_data->parent_cat;
                                        $accomodation_more_data[$index]->more_hotel_supplier_id = $acc_res->more_new_supplier_id;
                                        $accomodation_more_data[$index]->more_hotel_type_id = $room_type_data->id;
                                        $accomodation_more_data[$index]->more_hotel_type_cat = $room_type_data->room_type;
                                        $accomodation_more_data[$index]->more_hotelRoom_type_id = $Roomsid;
                        }
                        else{
                            $acc_Supplier_Id            = $acc_res->more_hotel_supplier_id ?? $acc_res->more_new_supplier_id ?? '';
                            if($acc_Supplier_Id > 0){
                                $supplier_data          = DB::table('rooms_Invoice_Supplier')->where('id',$acc_Supplier_Id)->select('id','balance','payable')->first();
                                // dd($supplier_data);
                                if(isset($supplier_data)){
                                    $different_In_Price = $acc_res->more_acc_total_amount_purchase ?? 0;
                                    $supplier_balance   = $supplier_data->balance + $different_In_Price;
                                    $supplier_payable   = $supplier_data->payable + $different_In_Price;
                                    
                                    DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance,'payable'=>$supplier_payable]);
                                }
                            }
                        }
                    }
                }
            
                $accomodation_data      = json_encode($accomodation_data);
                $accomodation_more_data = json_encode($accomodation_more_data);
            }else{
                $accomodation_data      = '';
                $accomodation_more_data = '';
            }
            
            $insert                          = new addManageInvoice();
            //new additon
            //1
            $insert->customer_id             = $req->customer_id;
            
            if(isset($req->SU_id) && $req->SU_id != null && $req->SU_id != ''){
                $insert->SU_id               = $req->SU_id;
            }
            
            $insert->booking_customer_id     = $req->booking_customer_id;
            $insert->services                = $req->services;
            $insert->agent_Id                = $req->agent_Id;
            $insert->agent_Name              = $req->agent_Name;
            $insert->agent_Company_Name      = $req->agent_Company_Name;
            
            $insert->b2b_Agent_Company_Name  = $req->b2b_Agent_Company_Name;
            $insert->b2b_Agent_Id            = $req->b2b_Agent_Id;
            $insert->b2b_Agent_Name          = $req->b2b_Agent_Name;
            
            $insert->prefix                  = $req->prefix;
            $insert->f_name                  = $req->f_name;
            $insert->middle_name             = $req->middle_name;
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
            
            $insert->passport_Image          = $req->passport_Image ?? '';
            
            // Lead Pass
            $insert->lead_title             = $req->lead_title ?? '';
            $insert->lead_fname             = $req->first_Name_passport ?? '';
            $insert->lead_lname             = $req->last_Name_passport ?? '';
            $insert->lead_gender            = $req->gender ?? '';
            $insert->lead_dob               = $req->date_of_birth_passport ?? '';
            $insert->lead_nationality       = $req->nationality_passport ?? '';
            $insert->lead_passport_number   = $req->passport_Number ?? '';
            $insert->lead_passport_expiry   = $req->passport_Expiry ?? '';
            $insert->more_Passenger_Data    = $req->more_Passenger_Data ?? '';
            $insert->count_P_Input          = $req->count_P_Input ?? '';
            // Lead Pass
            
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
            
            $insert->accomodation_details           = $accomodation_data;
            $insert->accomodation_details_more      = $accomodation_more_data;
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
            
            $insert->total_cost_price_all_Services      = $req->total_cost_price_all_Services;
            
            if(isset($req->total_discount_all_Services) && $req->total_discount_all_Services != null && $req->total_discount_all_Services != '' && $req->total_discount_all_Services != 0){
                $insert->total_sale_price_all_Services      = $req->total_discount_price_all_Services;
                $insert->total_discount_price_all_Services  = $req->total_sale_price_all_Services ?? '';
            }else{
                $insert->total_sale_price_all_Services      = $req->total_sale_price_all_Services;
                $insert->total_discount_price_all_Services  = NULL;
            }
            
            $insert->total_discount_all_Services        = $req->total_discount_all_Services ?? '';
            
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
            
            $insert->all_dynamic_costing            = $req->all_dynamic_costing;
            $insert->all_dynamic_child_costing      = $req->all_dynamic_child_costing;
            $insert->all_dynamic_infant_costing     = $req->all_dynamic_infant_costing;
            
            $insert->all_costing_details            = $req->all_costing_details;
            $insert->all_costing_details_child      = $req->all_costing_details_child;
            $insert->all_costing_details_infant     = $req->all_costing_details_infant;
            $all_services_quotation                 = 1;
            $insert->all_services_quotation         = $all_services_quotation;
            
            // Adult
            $insert->WQFVT_details                  = $req->WQFVT_details ?? '';
            $insert->WDFVT_details                  = $req->WDFVT_details ?? '';
            $insert->WTFVT_details                  = $req->WTFVT_details ?? '';
            $insert->WAFVT_details                  = $req->WAFVT_details ?? '';
            
            // Child
            $insert->WQFVT_details_child            = $req->WQFVT_details_child ?? '';
            $insert->WTFVT_details_child            = $req->WTFVT_details_child ?? '';
            $insert->WDFVT_details_child            = $req->WDFVT_details_child ?? '';
            $insert->WAFVT_details_child            = $req->WAFVT_details_child ?? '';
            
            // Infant
            $insert->WQFVT_details_infant           = $req->WQFVT_details_infant ?? '';
            $insert->WTFVT_details_infant           = $req->WTFVT_details_infant ?? '';
            $insert->WDFVT_details_infant           = $req->WDFVT_details_infant ?? '';
            $insert->WAFVT_details_infant           = $req->WAFVT_details_infant ?? '';
            
            $insert->hotel_Reservation_details      = $req->hotel_Reservation_details ?? '';
            $insert->transfer_Reservation_No        = $req->transfer_Reservation_No ?? '';
            $insert->flight_Reservation_No          = $req->flight_Reservation_No ?? '';
            $insert->visa_Reservation_No            = $req->visa_Reservation_No ?? '';
            
            $insert->ziyarat_details                = $req->ziyarat_details ?? '';
            
            $insert->lead_currency                  = $req->lead_currency ?? '';
            
            $insert->currency_Rate_AC               = $req->currency_Rate_AC ?? '';
            $insert->currency_Type_AC               = $req->currency_Type_AC ?? '';
            $insert->currency_value_AC              = $req->currency_value_AC ?? '';
            $insert->total_cost_price_AC            = $req->total_cost_price_AC ?? '';
            $insert->total_sale_price_AC            = $req->total_sale_price_AC ?? '';
            
            $insert->currency_Rate_Company          = $req->currency_Rate_Company ?? '';
            $insert->currency_Type_Company          = $req->currency_Type_Company ?? '';
            $insert->currency_value_Company         = $req->currency_value_Company ?? '';
            $insert->total_cost_price_Company       = $req->total_cost_price_Company ?? '';
            $insert->total_sale_price_Company       = $req->total_sale_price_Company ?? '';
            
            
            $insert->groups_id                      = $req->groups_id ?? '';
            
            $today                                  = Carbon::now();
            $formattedDate                          = $today->format('d M Y');
            $formattedTime                          = $today->format('H:i');
            if(isset($req->SU_id) && $req->SU_id > 0){
                $sub_User_Data                      = DB::table('role_managers')->where('customer_id',$req->customer_id)->where('id',$req->SU_id)->first();
                $created_Remarks                    = $sub_User_Data->first_name.' '.$sub_User_Data->last_name;
                // $created_Remarks                    = $sub_User_Data->first_name.' '.$sub_User_Data->last_name.' create invoice on '.$formattedDate.' at '.$formattedTime;
            }else{
                $sub_User_Data                      = DB::table('customer_subcriptions')->where('id',$req->customer_id)->first();
                $created_Remarks                    = $sub_User_Data->name.' '.$sub_User_Data->lname;
                // $created_Remarks                    = $sub_User_Data->name.' '.$sub_User_Data->lname.' create invoice on '.$formattedDate.' at '.$formattedTime;
            }
            // $insert->created_Remarks                = $created_Remarks ?? NULL;
            
            $insert->save();
            
            // if($req->token == config('token_UmrahShop') || $req->token == config('token_HashimTravel')){
            //     $mailSend = $this->createInvoice_MailSend($req,$insert->id);
            //     dd($mailSend);
            // }
            
            if(isset($req->otherPaxDetails)){
                if($req->paxAdded > 1){
                    $otherPaxDetails = json_decode($req->otherPaxDetails);
                    foreach($otherPaxDetails as $val_PD){
                        $otherPaxDetail                 = new otherPaxDetail();
                        $otherPaxDetail->customer_id    = $req->customer_id;
                        $otherPaxDetail->invoice_id     = $insert->id;
                        $otherPaxDetail->pilgramsName   = $val_PD->pilgramsName;
                        $otherPaxDetail->passportNumber = $val_PD->passportNumber;
                        $otherPaxDetail->dateOfBirth    = $val_PD->dateOfBirth;
                        $otherPaxDetail->groupType      = $val_PD->groupType;
                        $otherPaxDetail->issueDate      = $val_PD->issueDate;
                        $otherPaxDetail->groupCode      = $val_PD->groupCode;
                        $otherPaxDetail->save();
                    }
                }
            }
            
            // Advance Options Working
            if(isset($req->WOFVT_details) && $req->WOFVT_details != null && $req->WOFVT_details != ''){
                $insertAdvanceOption                            = new addManageInvoiceAdvanceoptions();
                $insertAdvanceOption->customer_id               = $req->customer_id;
                $insertAdvanceOption->invoice_Id                = $insert->id;
                $insertAdvanceOption->all_costing_details_AO    = $req->all_costing_details_AO ?? '';
                $insertAdvanceOption->WOFVT_details             = $req->WOFVT_details ?? '';
                $insertAdvanceOption->save();
            }
            // Advance Options Working
            
            // Remarks
            $insertRemark                       = new addManageInvoiceRemark();
            $insertRemark->customer_id          = $req->customer_id;
            $insertRemark->invoice_Id           = $insert->id;
            $insertRemark->remark_Name          = $created_Remarks;
            $insertRemark->remark_Status        = 'create_Invoice';
            $insertRemark->invoice_Remarks      = $req->invoice_Remarks ?? NULL;
            $insertRemark->save();
            // Remarks
            
            // $all_invoices_data  = DB::table('add_manage_invoices')->where('customer_id',$req->customer_id)->where('groups_id',$req->groups_id)->count();
            // $all_invoices_pax   = DB::table('add_manage_invoices')->where('customer_id',$req->customer_id)->where('groups_id',$req->groups_id)->where('all_services_quotation',1)->sum('no_of_pax_days');
            // DB::table('addGroupsdetails')->where('customer_id',$req->customer_id)->where('id',$req->groups_id)->update(['total_Invoices'=>$all_invoices_data,'total_Pax'=>$all_invoices_pax]);
            
            $agent_data = DB::table('Agents_detail')->where('id',$req->agent_Id)->select('id','balance')->first();
            if(isset($agent_data)){
                // echo "Enter hre ";
                $agent_balance = $agent_data->balance ?? '0' + $insert->total_sale_price_AC ?? '0';
                
                // update Agent Balance
                DB::table('Agents_detail')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                DB::table('agents_ledgers_new')->insert([
                    'agent_id'=>$agent_data->id,
                    'received'=>$insert->total_sale_price_AC,
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
            
            // B2B Agents
            $agent_data = DB::table('b2b_agents')->where('id',$req->b2b_Agent_Id)->select('id','balance')->first();
            if(isset($agent_data)){
                
                if($agent_data->balance != null){
                    $balance_AJ = $agent_data->balance;
                }else{
                    $balance_AJ = 0;
                }
                
                if($insert->total_sale_price_AC != null){
                    $total_sale_price_AC = $insert->total_sale_price_AC;
                }else{
                    $total_sale_price_AC = $insert->total_sale_price_all_Services;
                }
                
                $agent_balance = $balance_AJ + $total_sale_price_AC;
                
                DB::table('b2b_agents')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                
                DB::table('agents_ledgers_new')->insert([
                    'b2b_Agent_id'  => $agent_data->id,
                    'received'      => $insert->total_sale_price_AC ?? '0',
                    'balance'       => $agent_balance,
                    'invoice_no'    => $insert->id,
                    'customer_id'   => $req->customer_id,
                    'date'          => date('Y-m-d'),
                ]);
            }
            // B2B Agents
            
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
            
            $flights_Pax_details = json_decode($req->flights_Pax_details);
            if(isset($flights_Pax_details) && $flights_Pax_details != null && $flights_Pax_details != ''){
                foreach($flights_Pax_details as $value){
                    
                    DB::table('flight_seats_occupied')->insert([
                        'SU_id'                         => $req->SU_id ?? NULL,
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
                    $supplier_data              = DB::table('supplier')->where('id',$req->flight_supplier)->first();
                    
                    //  Calculate Child Price
                    $child_price_wi_adult_price = $value->flights_cost_per_seats_adult * $value->flights_adult_seats;
                    $child_price_wi_child_price = $value->flights_cost_per_seats_child * $value->flights_child_seats;
                    $infant_price               = $value->flights_cost_per_seats_infant * $value->flights_infant_seats;
                    $price_diff                 = $child_price_wi_adult_price - $child_price_wi_child_price;
                    
                    // New
                    $total_Balance              = $child_price_wi_adult_price + $child_price_wi_child_price + $infant_price;
                    // New
                    
                    if($price_diff != 0 || $infant_price != 0){
                        $supplier_balance       = $supplier_data->balance - $price_diff;
                        $supplier_balance       = $supplier_balance + $infant_price;
                        $total_differ           = $infant_price - $price_diff;
                        
                        DB::table('flight_supplier_ledger')->insert([
                            'SU_id' => $req->SU_id ?? NULL,
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
                        // DB::table('supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                    }
                    
                    // New
                    $supplier_balance_New = $total_Balance + $supplier_data->balance;
                    DB::table('supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance_New]);
                    // New
                }
            }
            
            if(isset($accomodation) && $accomodation != null && $accomodation != ''){
                $accomodation      = json_decode($accomodation_data);
                if(isset($accomodation)){
                    foreach($accomodation as $accomodation_res){
                        if(isset($accomodation_res->hotelRoom_type_idM)){
                   
                        
                                    $room_data = DB::table('rooms')->where('id',$accomodation_res->hotelRoom_type_id)->first();
                                    
                                    if($room_data){
                                        
                                        
                                        $total_booked = $room_data->booked + $accomodation_res->acc_qty;
                                        
                                       
                                       DB::table('rooms_bookings_details')->insert([
                                                'SU_id' => $req->SU_id ?? NULL,
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
                                                    'SU_id' => $req->SU_id ?? NULL,
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
                                                    
                                                // DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                                                //     'balance'=>$supplier_balance,
                                                //     'payable'=>$supplier_payable_balance
                                                //     ]);
                                                
                                                
                                                  
                                                                            
                                            }
                                    }
                         }
                    }
                }
            }
            
            if(isset($more_accomodation_details)  && $more_accomodation_details != null && $more_accomodation_details != ''){
                // print_r($more_accomodation_details);
                $more_accomodation_details = json_decode($accomodation_more_data);
                if(isset($more_accomodation_details)){
                foreach($more_accomodation_details as $accomodation_res){
                     if(isset($accomodation_res->more_hotelRoom_type_idM)){
                     
                            
                            $room_data = DB::table('rooms')->where('id',$accomodation_res->more_hotelRoom_type_id)->first();
                                
                                if($room_data){
                                    
                               
                                    $total_booked = $room_data->booked + $accomodation_res->more_acc_qty;
                                    
                                    DB::table('rooms_bookings_details')->insert([
                                            'SU_id' => $req->SU_id ?? NULL,
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
                                                    'SU_id' => $req->SU_id ?? NULL,
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
            
            }
            
            if(isset($req->transportation_details) && !empty($req->transportation_details) && $req->transportation_details != null){
                $transfer_data = json_decode($req->transportation_details);
                
                if(isset($transfer_data)){
                    
                    if(1 == count($transfer_data)){
                        foreach($transfer_data as $index => $trans_res_data){
                            if(isset($trans_res_data->transportation_pick_up_location) && $trans_res_data->transportation_pick_up_location != null && $trans_res_data->transportation_pick_up_location != ''){
                                $transfer_sup_data = DB::table('transfer_Invoice_Supplier')->where('id',$req->transfer_supplier_id)->select('id','balance')->first();
                                 if(isset($transfer_sup_data)){
                                      $trans_sup_balance = $transfer_sup_data->balance + $trans_res_data->transportation_vehicle_total_price;
                                        DB::table('transfer_supplier_ledger')->insert([
                                                'SU_id' => $req->SU_id ?? NULL,
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
                        }
                    }else{
                        
                        $total_price = 0;
                        foreach($transfer_data  as $index => $trans_res_data){
                            if($index != 0){
                                // dd($trans_res_data);
                                if($trans_res_data->transportation_vehicle_total_price != null && $trans_res_data->transportation_vehicle_total_price != ''){
                                    $total_price += $trans_res_data->transportation_vehicle_total_price ?? '0';
                                }
                            }
                        }
                      
                        $transfer_sup_data = DB::table('transfer_Invoice_Supplier')->where('id',$req->transfer_supplier_id)->select('id','balance')->first();
                        
                        if(isset($transfer_sup_data)){
                            $trans_sup_balance = $transfer_sup_data->balance + $total_price;
                                
                                
                            DB::table('transfer_supplier_ledger')->insert([
                                'SU_id' => $req->SU_id ?? NULL,
                                'supplier_id'=>$req->transfer_supplier_id,
                                'payment'=> $total_price,
                                'balance'=> $trans_sup_balance,
                                'invoice_no'=>$invoice_id,
                                'remarks'=>'New Invoice Created',
                                'date'=>date('Y-m-d'),
                                'customer_id'=>$req->customer_id,
                            ]);
                            $result = DB::table('transfer_Invoice_Supplier')->where('id',$req->transfer_supplier_id)->update(['balance'=>$trans_sup_balance]);
                        }
                        // dd($result);
                    }
                }
            }
            
            if(isset($req->quotation_id) && $req->quotation_id != null && $req->quotation_id != ''){
                $quotation_Status = DB::table('addManageQuotationPackage')->where('id',$req->quotation_id)->update(['quotation_status' => '1','mail_request_pax' => '1']);
            }
            
            $lead_in_process = 0;
            if(isset($req->SU_id) && $req->SU_id != null && $req->SU_id != ''){
                $all_leads = DB::table('addLead')->where('SU_id',$req->SU_id)->where('customer_id',$req->customer_id)->get();
            }else{
                $all_leads = DB::table('addLead')->where('customer_id',$req->customer_id)->get();
            }
            
            if(isset($all_leads) && $all_leads != null && $all_leads != ''){
                foreach($all_leads as $all_leads_value){
                    if(isset($req->SU_id) && $req->SU_id != null && $req->SU_id != ''){
                        $lead_quotation         = DB::table('addManageQuotationPackage')->where('SU_id',$req->SU_id)->where('customer_id',$req->customer_id)->where('lead_id',$all_leads_value->id)->get();
                    }else{
                        $lead_quotation         = DB::table('addManageQuotationPackage')->where('customer_id',$req->customer_id)->where('lead_id',$all_leads_value->id)->get();
                    }
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
            
            DB::commit();
            return response()->json(['status'=>'success','invoice_id'=>$invoice_id,'lead_in_process'=>$lead_in_process,'message'=>'Agent Invoice added Succesfully']); 
        
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
        }
    }
    
    public function add_Invoices_test(Request $req){
        
        
        // dd($req->all());
        
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
       
        DB::beginTransaction();
        try {
            
            if(isset($req->accomodation_details)){
                $accomodation_data      = json_decode($req->accomodation_details);
                $accomodation_more_data = json_decode($req->more_accomodation_details);
            
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
            
                $accomodation_data      = json_encode($accomodation_data);
                $accomodation_more_data = json_encode($accomodation_more_data);
            }else{
                $accomodation_data = '';
                $accomodation_more_data = '';
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
            
            
            
            // dd($accomodation_data);
            
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
            
            $insert->accomodation_details           = $accomodation_data;
            $insert->accomodation_details_more      = $accomodation_more_data;
            $insert->transportation_details         = $req->transportation_details;
            $insert->transportation_details_more    = $req->transportation_details_more;
            
            $insert->visa_type                      = $req->visa_type;
            $insert->visa_fee                       = $req->visa_fee;
            $insert->visa_Pax                       = $req->visa_Pax;
            $insert->exchange_rate_visaI            = $req->exchange_rate_visaI;
            $insert->total_visa_markup_value        = $req->total_visa_markup_value;
            $insert->exchange_rate_visa_fee         = $req->exchange_rate_visa_fee;
            
            
            // dd($req->more_visa_details);
            if($req->more_visa_details != '""'){
                $insert->more_visa_details              = $req->more_visa_details;
            }
            
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
            
            $flights_Pax_details = json_decode($req->flights_Pax_details);
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
            
            if(isset($accomodation) && $accomodation != null && $accomodation != ''){
                $accomodation      = json_decode($accomodation_data);
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
            }
            
            if(isset($more_accomodation_details)  && $more_accomodation_details != null && $more_accomodation_details != ''){
                // print_r($more_accomodation_details);
                $more_accomodation_details = json_decode($accomodation_more_data);
                if(isset($more_accomodation_details)){
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
            
            }
            
            
            if(isset($req->transportation_details) && !empty($req->transportation_details) && $req->transportation_details != null){
                $transfer_data = json_decode($req->transportation_details);
                
                if(isset($transfer_data)){
                    
                    if(1 == count($transfer_data)){
                        foreach($transfer_data as $index => $trans_res_data){
                            if(isset($trans_res_data->transportation_pick_up_location) && $trans_res_data->transportation_pick_up_location != null && $trans_res_data->transportation_pick_up_location != ''){
                                $transfer_sup_data = DB::table('transfer_Invoice_Supplier')->where('id',$req->transfer_supplier_id)->select('id','balance')->first();
                                 if(isset($transfer_sup_data)){
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
                        }
                    }else{
                        
                        $total_price = 0;
                        foreach($transfer_data  as $index => $trans_res_data){
                            if($index != 0){
                            $total_price += $trans_res_data->transportation_vehicle_total_price;
                            }
                        }
                      
                        $transfer_sup_data = DB::table('transfer_Invoice_Supplier')->where('id',$req->transfer_supplier_id)->select('id','balance')->first();
                        
                        if(isset($transfer_sup_data)){
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
                            $result = DB::table('transfer_Invoice_Supplier')->where('id',$req->transfer_supplier_id)->update(['balance'=>$trans_sup_balance]);
                        }
                        // dd($result);
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
            dd($e);
            return response()->json(['status'=>'error','message'=>'Something Went Wrong Try Again']); 
        }
    }
    
    public static function view_Invoices_Season_Working($all_data,$request){
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
                
                $start_Date     = Carbon::parse($start_Date);
                $end_Date       = Carbon::parse($end_Date);
                $services       = [];
                if (isset($record->services)) {
                    $decoded    = json_decode($record->services, true);
                    $services   = is_array($decoded) ? $decoded : [];
                }
                
                if (is_array($services) && in_array('accomodation_tab', $services)) {
                    $accomodation_details   = json_decode($record->accomodation_details);
                    if (!isset($accomodation_details[0]->acc_check_in)) {
                        return false;
                    }
                    $created_at             = Carbon::parse($accomodation_details[0]->acc_check_in);
                } else {
                    if (!isset($record->created_at) || empty($record->created_at)) {
                        return false;
                    }
                    $created_at             = Carbon::parse($record->created_at);
                }
                return $created_at->between($start_Date, $end_Date) || ($created_at->lte($start_Date) && $created_at->gte($end_Date));
            });
            
            $all_data = $filtered_data;
        }
        return $all_data;
    }
    
    public static function view_Package_Season_Working($all_data,$request){
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
                // $services       = json_decode($record->services, true); // Decode the JSON string
                
                $start_Date     = Carbon::parse($start_Date);
                $end_Date       = Carbon::parse($end_Date);
                
                // If services exists and is valid JSON
                $services = [];
                if (isset($record->services)) {
                    $decoded = json_decode($record->services, true);
                    $services = is_array($decoded) ? $decoded : [];
                }
                
                // If 'accomodation_tab' is present in services
                if (is_array($services) && in_array('accomodation_tab', $services)) {
                    if (!isset($record->start_date) || empty($record->start_date)) {
                        return false;
                    }
                    
                    $created_at = Carbon::parse($record->start_date);
                } else {
                    // Fallback to created_at
                    if (!isset($record->created_at) || empty($record->created_at)) {
                        return false;
                    }
                    
                    $created_at = Carbon::parse($record->created_at);
                }
                
                // Now check if the date falls within range
                return $created_at->between($start_Date, $end_Date) || ($created_at->lte($start_Date) && $created_at->gte($end_Date));
                // return $created_at->between($start_Date, $end_Date);
            });
            
            $all_data = $filtered_data;
        }
        return $all_data;
    }
    
    public function view_Invoices(Request $req){
        $all_countries          = country::all();
        $all_Users              = DB::table('role_managers')->get();
        
        if(isset($req->customer_id) && $req->customer_id == '4'){
            $data1                  = [];
            $data1                  = DB::table('add_manage_invoices')->where('customer_id',$req->customer_id)->orderBy('id', 'DESC')->get();
            if($data1->isEmpty()){
                
            }else{
                foreach($data1 as $val_ID){
                    $invoice_Remarks    = DB::table('add_manage_invoice_remarks')->where('customer_id',$req->customer_id)->where('invoice_Id',$val_ID->id)->orderBy('id', 'ASC')->get();
                    $val_ID->Invoice_Remarks_Detils = $invoice_Remarks;
                }
            }
            
            $confirm_quotations     = DB::table('addManageQuotationPackage')->where('customer_id',$req->customer_id)->where('quotation_status',1)->orderBy('id', 'DESC')->get();
            $booking_customers      = DB::table('booking_customers')->where('customer_id',$req->customer_id)->get();
            $b2b_Agents_Detail      = DB::table('b2b_agents')->where('token',$req->token)->get();
            $agents_Detail          = DB::table('Agents_detail')->where('customer_id',$req->customer_id)->get();
            $agent_data             = DB::table('Agents_detail')->where('customer_id',$req->customer_id)->get();
            $documents_Detail       = DB::table('uploadDocumentInvoice')->where('customer_id',$req->customer_id)->get();
            $groups_Detail          = DB::table('addGroupsdetails')->where('customer_id',$req->customer_id)->get();
            $role_managers          = DB::table('role_managers')->where('customer_id',$req->customer_id)->get();
            
            if(isset($req->SU_id) && $req->SU_id != null && $req->SU_id != ''){
                $tour_Bookings      = DB::table('tours_bookings')
                                        ->join('customer_subcriptions','tours_bookings.customer_id','customer_subcriptions.id')
                                        ->join('cart_details','tours_bookings.id','cart_details.booking_id')->where('pakage_type','tour')
                                        ->where('tours_bookings.SU_id',$req->SU_id)->where('tours_bookings.customer_id',$req->customer_id)
                                        ->orderBy('tours_bookings.id', 'DESC')->get();
            }else{
                $tour_Bookings      = DB::table('tours_bookings')
                                        ->join('customer_subcriptions','tours_bookings.customer_id','customer_subcriptions.id')
                                        ->join('cart_details','tours_bookings.id','cart_details.booking_id')->where('pakage_type','tour')
                                        ->join('tours','cart_details.tour_id','tours.id')
                                        ->where('tours_bookings.customer_id',$req->customer_id)
                                        ->select('tours_bookings.*', 'cart_details.*', 'tours.start_date')
                                        ->orderBy('tours_bookings.id', 'DESC')->get();
            }
            
        }else{
            if(isset($req->SU_id) && $req->SU_id != null && $req->SU_id != ''){
                $data1              = DB::table('add_manage_invoices')->where('SU_id',$req->SU_id)->where('customer_id',$req->customer_id)->orderBy('id', 'DESC')->get();
                $confirm_quotations = DB::table('addManageQuotationPackage')->where('SU_id',$req->SU_id)->where('customer_id',$req->customer_id)->where('quotation_status',1)->orderBy('id', 'DESC')->get();
                $booking_customers  = DB::table('booking_customers')->where('SU_id',$req->SU_id)->where('customer_id',$req->customer_id)->get();
                $b2b_Agents_Detail  = DB::table('b2b_agents')->where('token',$req->token)->get();
                $agents_Detail      = DB::table('Agents_detail')->where('SU_id',$req->SU_id)->where('customer_id',$req->customer_id)->get();
                $agent_data         = DB::table('Agents_detail')->where('SU_id',$req->SU_id)->where('customer_id',$req->customer_id)->get();
                $documents_Detail   = DB::table('uploadDocumentInvoice')->where('SU_id',$req->SU_id)->where('customer_id',$req->customer_id)->get();
                $groups_Detail      = DB::table('addGroupsdetails')->where('SU_id',$req->SU_id)->where('customer_id',$req->customer_id)->get();
                $role_managers      = DB::table('role_managers')->where('customer_id',$req->customer_id)->get();
                $tour_Bookings      = DB::table('tours_bookings')
                                        ->join('customer_subcriptions','tours_bookings.customer_id','customer_subcriptions.id')
                                        ->join('cart_details','tours_bookings.id','cart_details.booking_id')->where('pakage_type','tour')
                                        ->where('tours_bookings.SU_id',$req->SU_id)->where('tours_bookings.customer_id',$req->customer_id)
                                        ->orderBy('tours_bookings.id', 'DESC')->get();
            }else{
                $data1              = DB::table('add_manage_invoices')->where('customer_id',$req->customer_id)->orderBy('id', 'DESC')->get();
                $confirm_quotations = DB::table('addManageQuotationPackage')->where('customer_id',$req->customer_id)->where('quotation_status',1)->orderBy('id', 'DESC')->get();
                $booking_customers  = DB::table('booking_customers')->where('customer_id',$req->customer_id)->get();
                $b2b_Agents_Detail  = DB::table('b2b_agents')->where('token',$req->token)->get();
                $agents_Detail      = DB::table('Agents_detail')->where('customer_id',$req->customer_id)->get();
                $agent_data         = DB::table('Agents_detail')->where('customer_id',$req->customer_id)->get();
                $documents_Detail   = DB::table('uploadDocumentInvoice')->where('customer_id',$req->customer_id)->get();
                $groups_Detail      = DB::table('addGroupsdetails')->where('customer_id',$req->customer_id)->get();
                $role_managers      = DB::table('role_managers')->where('customer_id',$req->customer_id)->get();
                $tour_Bookings      = DB::table('tours_bookings')
                                        ->join('customer_subcriptions','tours_bookings.customer_id','customer_subcriptions.id')
                                        ->join('cart_details','tours_bookings.id','cart_details.booking_id')->where('pakage_type','tour')
                                        ->join('tours','cart_details.tour_id','tours.id')
                                        ->select('tours_bookings.*', 'cart_details.*', 'tours.start_date')
                                        ->where('tours_bookings.customer_id',$req->customer_id)
                                        ->orderBy('tours_bookings.id', 'DESC')->get();
            }
        }
        
        // Season
        // dd($req->season_Id);
        $today_Date             = date('Y-m-d');
        $season_Id              = '';
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
                $data1   = $this->view_Invoices_Season_Working($data1,$req);
                // dd($data1);
            }
            
            if(empty($tour_Bookings)){
            }else{
                // dd($tour_Bookings);
                $tour_Bookings  = $this->view_Package_Season_Working($tour_Bookings,$req);
                // dd($tour_Bookings);
            }
        }
        // Season
        
        return response()->json([
            'data1'                 => $data1,
            'role_managers'         => $role_managers,
            'confirm_quotations'    => $confirm_quotations,
            'all_countries'         => $all_countries,
            'booking_customers'     => $booking_customers,
            'agents_Detail'         => $agents_Detail,
            'documents_Detail'      => $documents_Detail,
            'groups_Detail'         => $groups_Detail,
            'tour_Bookings'         => $tour_Bookings,
            'b2b_Agents_Detail'     => $b2b_Agents_Detail,
            'all_Users'             => $all_Users,
            'season_Details'        => $season_Details,
            'season_Id'             => $season_Id,
        ]); 
    }
    
    public function view_invoices_visa_groups(Request $req){
        $all_countries          = country::all();
        $all_Users              = DB::table('role_managers')->get();
        
        if(isset($req->SU_id) && $req->SU_id != null && $req->SU_id != ''){
            $data               = DB::table('add_manage_invoices')->where('SU_id',$req->SU_id)->where('customer_id',$req->customer_id)->orderBy('id', 'DESC')->get();
            $booking_customers  = DB::table('booking_customers')->where('SU_id',$req->SU_id)->where('customer_id',$req->customer_id)->get();
            $b2b_Agents_Detail  = DB::table('b2b_agents')->where('token',$req->token)->get();
            $agents_Detail      = DB::table('Agents_detail')->where('SU_id',$req->SU_id)->where('customer_id',$req->customer_id)->get();
            $groups_Detail      = DB::table('addGroupsdetails')->where('SU_id',$req->SU_id)->where('customer_id',$req->customer_id)->get();
        }else{
            $data               = DB::table('add_manage_invoices')->where('add_manage_invoices.customer_id',$req->customer_id)->join('otherPaxDetails','add_manage_invoices.id','otherPaxDetails.invoice_id')->orderBy('add_manage_invoices.id', 'DESC')->get();
            $booking_customers  = DB::table('booking_customers')->where('customer_id',$req->customer_id)->get();
            $b2b_Agents_Detail  = DB::table('b2b_agents')->where('token',$req->token)->get();
            $agents_Detail      = DB::table('Agents_detail')->where('customer_id',$req->customer_id)->get();
            $groups_Detail      = DB::table('addGroupsdetails')->where('customer_id',$req->customer_id)->get();
        }
        
        // dd($data);
        
        // Season
        $today_Date             = date('Y-m-d');
        $season_Id              = '';
        if(isset($req->season_Id) && $req->season_Id == 'all_Seasons'){
            $season_Id          = 'all_Seasons';
        }elseif(isset($req->season_Id) && $req->season_Id > 0){
            $season_SD          = DB::table('add_Seasons')->where('customer_id', $req->customer_id)->where('id', $req->season_Id)->first();
            $season_Id          = $season_SD->id ?? '';
        }else{
            $season_SD          = DB::table('add_Seasons')->where('customer_id', $req->customer_id)->whereDate('start_Date', '<=', $today_Date)->whereDate('end_Date', '>=', $today_Date)->first();
            $season_Id          = $season_SD->id ?? '';
        }
        
        $season_Details         = DB::table('add_Seasons')->where('customer_id', $req->customer_id)->get();
        if($req->customer_id == 4 || $req->customer_id == 54){
            if($data->isEmpty()){
            }else{
                $data   = $this->view_Invoices_Season_Working($data,$req);
            }
        }
        // Season
        
        return response()->json([
            'data'                  => $data,
            'all_countries'         => $all_countries,
            'booking_customers'     => $booking_customers,
            'agents_Detail'         => $agents_Detail,
            'groups_Detail'         => $groups_Detail,
            'b2b_Agents_Detail'     => $b2b_Agents_Detail,
            'all_Users'             => $all_Users,
            'season_Details'        => $season_Details,
            'season_Id'             => $season_Id,
        ]); 
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
        $b2b_Agents_Detail              = DB::table('b2b_agents')->where('token',$req->token)->get();
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
        
        $vehicle_category               = DB::table('vehicle_category')->where('customer_id',$customer_id)->get();
        
        $groups_Detail                  = DB::table('addGroupsdetails')->where('customer_id',$req->customer_id)->get();
        
        $custom_Meal_Types              = DB::table('custom_Meal_Types')->where('customer_id',$req->customer_id)->get();
        
        $otherPaxDetails                = DB::table('otherPaxDetails')->where('customer_id',$req->customer_id)->where('invoice_id',$req->id)->get();
        
        return response()->json(['message'=>'success','custom_Meal_Types'=>$custom_Meal_Types,'otherPaxDetails'=>$otherPaxDetails,'b2b_Agents_Detail'=>$b2b_Agents_Detail,'groups_Detail'=>$groups_Detail,'vehicle_category'=>$vehicle_category,'tranfer_vehicle'=>$tranfer_vehicle,'tranfer_destination'=>$tranfer_destination,'tranfer_company'=>$tranfer_company,'visa_supplier'=>$visa_supplier,'visa_types'=>$visa_types,'all_flight_routes'=>$all_flight_routes,'flight_suppliers'=>$flight_suppliers,'all_curr_symboles'=>$all_curr_symboles,'tranfer_supplier'=>$tranfer_supplier,'supplier_detail'=>$supplier_detail,'visa_type'=>$visa_type,'get_invoice'=>$get_invoice,'mange_currencies'=>$mange_currencies,'user_hotels'=>$user_hotels,'Agents_detail'=>$Agents_detail,'currency_Symbol'=>$currency_Symbol,'customer'=>$customer,'attributes'=>$attributes,'categories'=>$categories,'all_countries'=>$all_countries,'all_countries_currency'=>$all_countries_currency,'bir_airports'=>$bir_airports,'payment_gateways'=>$payment_gateways,'payment_modes'=>$payment_modes,'customers_data'=>$customers_data]);
    }
    
    public function update_Invoices(Request $req){
        $id     = $req->id;
        $insert = addManageInvoice::find($id);
        // dd($insert);
        
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
            
            $prev_acc               = $insert->accomodation_details;
            $prev_acc_more          = $insert->accomodation_details_more;
            
            $previous_agent         = $insert->agent_Id;
            $new_agent              = $req->agent_Id;
            
            $previous_b2b_Agent     = $insert->b2b_Agent_Id;
            $new_b2b_Agent          = $req->b2b_Agent_Id;
            
            $previous_transfer_sup  = $insert->transfer_supplier_id;
            $new_transfer_sup       = $req->transfer_supplier_id;
            
            $previous_customer      = $insert->booking_customer_id;
            $new_customer           = $req->booking_customer_id;
            
            $previous_total_price   = $insert->total_sale_price_all_Services;
            $new_total_price        = $req->total_sale_price_all_Services;
            
            $prev_transfer_det      = json_decode($insert->transportation_details);
            $new_transfer_det       = json_decode($req->transportation_details);
            
            $prev_flight_pax        = json_decode($insert->flights_Pax_details);
            // dd($prev_flight_pax);
            $new_flight_pax         = json_decode($req->flights_Pax_details);
            
            $accomodation_data      = json_decode($req->accomodation_details);
            $accomodation_more_data = json_decode($req->more_accomodation_details);
            
            $prev_visa_all_details  = json_decode($insert->all_visa_price_details);
            $new_visa_all_details   = json_decode($req->all_visa_price_details);
            
            // dd(json_decode($prev_acc),$accomodation_data);
            
            $prev_acc_Decoded = json_decode($prev_acc);
            if(isset($prev_acc_Decoded) && $prev_acc_Decoded != null && $prev_acc_Decoded != ''){
                foreach($prev_acc_Decoded as $val_PAD){
                    $acc_Supplier_Id            = $val_PAD->hotel_supplier_id ?? '';
                    if($acc_Supplier_Id > 0){
                        $supplier_data          = DB::table('rooms_Invoice_Supplier')->where('id',$acc_Supplier_Id)->select('id','balance','payable')->first();
                        if(isset($supplier_data)){
                            $different_In_Price = $val_PAD->acc_total_amount_purchase ?? 0;
                            $supplier_balance   = $supplier_data->balance - $different_In_Price;
                            $supplier_payable   = $supplier_data->payable - $different_In_Price;
                            
                            DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance,'payable'=>$supplier_payable]);
                        }
                    }
                }
            }
            
            if(isset($accomodation_data) && $accomodation_data != null && $accomodation_data != ''){
                foreach($accomodation_data as $index => $acc_res){
                    if($acc_res->room_select_type == 'true'){
                        // dd('IF');
                        
                        $room_type_data             = json_decode($acc_res->new_rooms_type);
                        $Rooms                      = new Rooms;
                        $Rooms->hotel_id            = $acc_res->hotel_id;
                        $Rooms->rooms_on_rq         = '';
                        $Rooms->room_type_id        = $room_type_data->parent_cat; 
                        $Rooms->room_type_name      = $room_type_data->room_type; 
                        $Rooms->room_type_cat       = $room_type_data->id; 
                        $Rooms->quantity            = $acc_res->acc_qty;  
                        $Rooms->min_stay            = 0; 
                        $Rooms->max_child           = 1; 
                        $Rooms->max_adults          = $room_type_data->no_of_persons; 
                        $Rooms->extra_beds          = 0; 
                        $Rooms->extra_beds_charges  = 0; 
                        $Rooms->availible_from      = $acc_res->acc_check_in; 
                        $Rooms->availible_to        = $acc_res->acc_check_out; 
                        $Rooms->room_option_date    = $acc_res->acc_check_in; 
                        $Rooms->price_week_type     = 'for_all_days'; 
                        $Rooms->price_all_days      = $acc_res->price_per_room_purchase;
                        $Rooms->room_supplier_name  = $acc_res->new_supplier_id;
                        $Rooms->room_meal_type      = $acc_res->hotel_meal_type;
                        // $Rooms->weekdays = serialize($request->weekdays);
                        $Rooms->weekdays            = null;
                        $Rooms->weekdays_price      = NULL; 
                        // $Rooms->weekends =  serialize($request->weekend); 
                        $Rooms->weekends            = null;
                        $Rooms->weekends_price      = NULL; 
                        
                        $user_id                    = $req->customer_id;
                        $Rooms->owner_id            = $user_id;
                        
                        $result                     = $Rooms->save();
                        $Roomsid                    = $Rooms->id;
                        
                        $supplier_data              = DB::table('rooms_Invoice_Supplier')->where('id',$Rooms->room_supplier_name)->select('id','balance','payable')->first();
                        
                        if(isset($supplier_data)){
                            $week_days_total        = 0;
                            $week_end_days_totals   = 0;
                            $total_price            = 0;
                            if($Rooms->price_week_type == 'for_all_days'){
                                $avaiable_days      = dateDiffInDays($Rooms->availible_from, $Rooms->availible_to);
                                $total_price        = $Rooms->price_all_days * $avaiable_days;
                            }else{
                                $avaiable_days      = dateDiffInDays($Rooms->availible_from, $Rooms->availible_to);
                                $all_days           = getBetweenDates($Rooms->availible_from, $Rooms->availible_to);
                                $week_days          = json_decode($Rooms->weekdays);
                                $week_end_days      = json_decode($Rooms->weekends);
                                
                                foreach($all_days as $day_res){
                                    $day                = date('l', strtotime($day_res));
                                    $day                = trim($day);
                                    $week_day_found     = false;
                                    $week_end_day_found = false;
                                    
                                    foreach($week_days as $week_day_res){
                                        if($week_day_res == $day){
                                            $week_day_found = true;
                                        }
                                    }
                                    
                                    if($week_day_found){
                                        $week_days_total        += $Rooms->weekdays_price;
                                    }else{
                                        $week_end_days_totals   += $Rooms->weekends_price;
                                    }
                                }
                                $total_price    = $week_days_total + $week_end_days_totals;
                            }
                            
                            // dd('IF');
                            
                            // Check Previous
                            // $prev_acc_Decoded                   = json_decode($prev_acc);
                            // if(isset($prev_acc_Decoded[$index]) && $prev_acc_Decoded[$index] != null && $prev_acc_Decoded[$index] != ''){
                            //     $acc_Supplier_Id            = $prev_acc_Decoded[$index]->hotel_supplier_id ?? '';
                            //     if($acc_Supplier_Id > 0){
                            //         $supplier_data          = DB::table('rooms_Invoice_Supplier')->where('id',$acc_Supplier_Id)->select('id','balance','payable')->first();
                            //         if(isset($supplier_data)){
                            //             $different_In_Price = $prev_acc_Decoded[$index]->acc_total_amount ?? 0;
                            //             $supplier_balance   = $supplier_data->balance - $different_In_Price;
                            //             $supplier_payable   = $supplier_data->payable - $different_In_Price;
                                        
                            //             DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance,'payable'=>$supplier_payable]);
                            //         }
                            //     }
                            // }
                            // $supplier_data          = DB::table('rooms_Invoice_Supplier')->where('id',$Rooms->room_supplier_name)->select('id','balance','payable')->first();
                            // Check Previous
                            
                            $all_days_price         = $total_price * $Rooms->quantity;
                            $supplier_balance       = $supplier_data->balance + $all_days_price;
                            $supplier_payable       = $supplier_data->payable + $all_days_price;
                            
                            // dd($acc_res,$supplier_balance,$supplier_payable,$all_days_price);
                            
                            // update Agent Balance
                            DB::table('hotel_supplier_ledger')->insert([
                                'supplier_id'       => $supplier_data->id,
                                'payment'           => $all_days_price,
                                'balance'           => $supplier_balance,
                                'payable_balance'   => $supplier_data->payable,
                                'room_id'           => $Roomsid,
                                'customer_id'       => $user_id,
                                'date'              => date('Y-m-d'),
                                'available_from'    => $Rooms->availible_from,
                                'available_to'      => $Rooms->availible_to,
                                'room_quantity'     => $Rooms->quantity,
                            ]);
                            
                            $different_In_Price     = $acc_res->acc_total_amount_purchase ?? 0;
                            $supplier_balance_New   = $supplier_data->balance + $different_In_Price;
                            $supplier_payable_New   = $supplier_data->payable + $different_In_Price;
                            
                            DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance_New,'payable'=>$supplier_payable_New]);
                        }
                        
                        $accomodation_data[$index]->acc_type            = $room_type_data->parent_cat;
                        $accomodation_data[$index]->hotel_supplier_id   = $acc_res->new_supplier_id;
                        $accomodation_data[$index]->hotel_type_id       = $room_type_data->id;
                        $accomodation_data[$index]->hotel_type_cat      = $room_type_data->room_type;
                        $accomodation_data[$index]->hotelRoom_type_id   = $Roomsid;
                    }
                    else{
                        // dd('ELSE');
                        $acc_Supplier_Id                = $acc_res->hotel_supplier_id ?? $acc_res->new_supplier_id ?? '';
                        if($acc_Supplier_Id > 0){
                            $supplier_data              = DB::table('rooms_Invoice_Supplier')->where('id',$acc_Supplier_Id)->select('id','balance','payable')->first();
                            // dd($supplier_data);
                            if(isset($supplier_data)){
                                // $old_Cost                   = 0;
                                
                                // // Check Previous
                                // $prev_acc_Decoded           = json_decode($prev_acc);
                                // if(isset($prev_acc_Decoded[$index]) && $prev_acc_Decoded[$index] != null && $prev_acc_Decoded[$index] != ''){
                                //     $acc_Previous_SID       = $prev_acc_Decoded[$index]->hotel_supplier_id ?? '';
                                //     $acc_New_SID            = $accomodation_data[$index]->hotel_supplier_id ?? '';
                                //     if($acc_Previous_SID > 0 && $acc_Previous_SID != $acc_New_SID){
                                //         $previous_SD            = DB::table('rooms_Invoice_Supplier')->where('id',$acc_Previous_SID)->select('id','balance','payable')->first();
                                //         if(isset($previous_SD)){
                                //             $different_In_Price = $prev_acc_Decoded[$index]->acc_total_amount ?? 0;
                                //             $supplier_balance   = $previous_SD->balance - $different_In_Price;
                                //             $supplier_payable   = $previous_SD->payable - $different_In_Price;
                                            
                                //             DB::table('rooms_Invoice_Supplier')->where('id',$previous_SD->id)->update(['balance'=>$supplier_balance,'payable'=>$supplier_payable]);
                                //         }
                                        
                                //         $old_Cost       = 0;
                                //     }else{
                                //         $prev_SID           = $prev_acc_Decoded[$index]->hotel_supplier_id ?? $prev_acc_Decoded[$index]->new_supplier_id ?? '';
                                //         if($prev_SID > 0){
                                //             $old_Cost       = $prev_acc_Decoded[$index]->acc_total_amount ?? 0;
                                //         }
                                //     }
                                // }
                                // // Check Previous
                                
                                // $new_Cost               = $accomodation_data[$index]->acc_total_amount ?? 0;
                                // // dd($old_Cost,$new_Cost);
                                // if($new_Cost > $old_Cost){
                                //     $different_In_Price = $new_Cost - $old_Cost;
                                //     $supplier_balance   = $supplier_data->balance + $different_In_Price;
                                //     $supplier_payable   = $supplier_data->payable + $different_In_Price;
                                // }elseif($new_Cost < $old_Cost){
                                //     $different_In_Price = $old_Cost - $new_Cost;
                                //     $supplier_balance   = $supplier_data->balance - $different_In_Price;
                                //     $supplier_payable   = $supplier_data->payable - $different_In_Price;
                                // }else{
                                //     $supplier_balance   = $supplier_data->balance;
                                //     $supplier_payable   = $supplier_data->payable;
                                // }
                                
                                $different_In_Price = $acc_res->acc_total_amount_purchase ?? 0;
                                $supplier_balance   = $supplier_data->balance + $different_In_Price;
                                $supplier_payable   = $supplier_data->payable + $different_In_Price;
                                // dd($acc_res,$supplier_balance,$supplier_payable);
                                
                                DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance,'payable'=>$supplier_payable]);
                            }
                        }
                    }
                }
            }else{
                // dd('ELSE 2');
                // $prev_acc_Decoded                   = json_decode($prev_acc);
                // if(isset($prev_acc_Decoded) && $prev_acc_Decoded != null && $prev_acc_Decoded != ''){
                //     foreach($prev_acc_Decoded as $index => $val_PAD){
                //         $acc_Supplier_Id            = $val_PAD->hotel_supplier_id ?? $val_PAD->new_supplier_id ?? '';
                //         if($acc_Supplier_Id > 0){
                //             $supplier_data          = DB::table('rooms_Invoice_Supplier')->where('id',$acc_Supplier_Id)->select('id','balance','payable')->first();
                //             if(isset($supplier_data)){
                //                 $different_In_Price = $val_PAD->acc_total_amount ?? 0;
                //                 $supplier_balance   = $supplier_data->balance - $different_In_Price;
                //                 $supplier_payable   = $supplier_data->payable - $different_In_Price;
                                
                //                 DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance,'payable'=>$supplier_payable]);
                //             }
                //         }
                //     }
                // }
            }
            
            $prev_acc_more_Decoded = json_decode($prev_acc_more);
            if(isset($prev_acc_more_Decoded) && $prev_acc_more_Decoded != null && $prev_acc_more_Decoded != ''){
                foreach($prev_acc_more_Decoded as $val_PAMD){
                    $acc_Supplier_Id            = $val_PAMD->more_hotel_supplier_id ?? '';
                    if($acc_Supplier_Id > 0){
                        $supplier_data          = DB::table('rooms_Invoice_Supplier')->where('id',$acc_Supplier_Id)->select('id','balance','payable')->first();
                        if(isset($supplier_data)){
                            $different_In_Price = $val_PAMD->more_acc_total_amount_purchase ?? 0;
                            $supplier_balance   = $supplier_data->balance - $different_In_Price;
                            $supplier_payable   = $supplier_data->payable - $different_In_Price;
                            
                            DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance,'payable'=>$supplier_payable]);
                        }
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
                            $week_days_total        = 0;
                            $week_end_days_totals   = 0;
                            $total_price            = 0;
                            if($Rooms->price_week_type == 'for_all_days'){
                                $avaiable_days      = dateDiffInDays($Rooms->availible_from, $Rooms->availible_to);
                                $total_price        = $Rooms->price_all_days * $avaiable_days;
                            }
                            
                            // Check Previous
                            // $prev_acc_Decoded                   = json_decode($prev_acc_more);
                            // if(isset($prev_acc_Decoded[$index]) && $prev_acc_Decoded[$index] != null && $prev_acc_Decoded[$index] != ''){
                            //     // foreach($prev_acc_Decoded as $index => $val_PAD){
                            //         $acc_Supplier_Id            = $prev_acc_Decoded[$index]->more_hotel_supplier_id ?? '';
                            //         if($acc_Supplier_Id > 0){
                            //             $supplier_data          = DB::table('rooms_Invoice_Supplier')->where('id',$acc_Supplier_Id)->select('id','balance','payable')->first();
                            //             if(isset($supplier_data)){
                            //                 $different_In_Price = $prev_acc_Decoded[$index]->more_acc_total_amount ?? 0;
                            //                 $supplier_balance   = $supplier_data->balance - $different_In_Price;
                            //                 $supplier_payable   = $supplier_data->payable - $different_In_Price;
                                            
                            //                 DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance,'payable'=>$supplier_payable]);
                            //             }
                            //         }
                            //     // }
                            // }
                            // // Check Previous
                            // $supplier_data          = DB::table('rooms_Invoice_Supplier')->where('id',$Rooms->room_supplier_name)->select('id','balance','payable')->first();
                            
                            $all_days_price         = $total_price * $Rooms->quantity;
                            $supplier_balance       = $supplier_data->balance + $all_days_price;
                            $supplier_payable       = $supplier_data->payable + $all_days_price;
                            
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
                            
                            $different_In_Price     = $acc_res->more_acc_total_amount_purchase ?? 0;
                            $supplier_balance_New   = $supplier_data->balance + $different_In_Price;
                            $supplier_payable_New   = $supplier_data->payable + $different_In_Price;
                            
                            DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance_New,'payable'=>$supplier_payable_New]);
                        }
                        
                        $accomodation_more_data[$index]->more_acc_type = $room_type_data->parent_cat;
                        $accomodation_more_data[$index]->more_hotel_supplier_id = $acc_res->more_new_supplier_id;
                        $accomodation_more_data[$index]->more_hotel_type_id = $room_type_data->id;
                        $accomodation_more_data[$index]->more_hotel_type_cat = $room_type_data->room_type;
                        $accomodation_more_data[$index]->more_hotelRoom_type_id = $Roomsid;
                    }
                    else{
                        // dd('ELSE');
                        $acc_Supplier_Id                = $acc_res->more_hotel_supplier_id ?? $acc_res->more_new_supplier_id ?? '';
                        if($acc_Supplier_Id > 0){
                            $supplier_data              = DB::table('rooms_Invoice_Supplier')->where('id',$acc_Supplier_Id)->select('id','balance','payable')->first();
                            // dd($supplier_data);
                            if(isset($supplier_data)){
                                // Check Previous
                                // $prev_acc_Decoded           = json_decode($prev_acc_more);
                                // if(isset($prev_acc_Decoded[$index]) && $prev_acc_Decoded[$index] != null && $prev_acc_Decoded[$index] != ''){
                                //     $acc_Previous_SID       = $prev_acc_Decoded[$index]->more_hotel_supplier_id ?? '';
                                //     $acc_New_SID            = $accomodation_data[$index]->more_hotel_supplier_id ?? '';
                                //     if($acc_Previous_SID > 0 && $acc_Previous_SID != $acc_New_SID){
                                //         $previous_SD            = DB::table('rooms_Invoice_Supplier')->where('id',$acc_Previous_SID)->select('id','balance','payable')->first();
                                //         if(isset($previous_SD)){
                                //             $different_In_Price = $prev_acc_Decoded[$index]->more_acc_total_amount ?? 0;
                                //             $supplier_balance   = $previous_SD->balance - $different_In_Price;
                                //             $supplier_payable   = $previous_SD->payable - $different_In_Price;
                                            
                                //             DB::table('rooms_Invoice_Supplier')->where('id',$previous_SD->id)->update(['balance'=>$supplier_balance,'payable'=>$supplier_payable]);
                                //         }
                                        
                                //         $old_Cost       = 0;
                                //     }else{
                                //         $prev_SID           = $prev_acc_Decoded[$index]->more_hotel_supplier_id ?? $prev_acc_Decoded[$index]->more_new_supplier_id ?? '';
                                //         if($prev_SID > 0){
                                //             $old_Cost       = $prev_acc_Decoded[$index]->more_acc_total_amount ?? 0;
                                //         }
                                //     }
                                // }
                                // // Check Previous
                                
                                // $new_Cost               = $accomodation_more_data[$index]->more_acc_total_amount ?? 0;
                                // // dd($old_Cost,$new_Cost);
                                // if($new_Cost > $old_Cost){
                                //     $different_In_Price = $new_Cost - $old_Cost;
                                //     $supplier_balance   = $supplier_data->balance + $different_In_Price;
                                //     $supplier_payable   = $supplier_data->payable + $different_In_Price;
                                // }elseif($new_Cost < $old_Cost){
                                //     $different_In_Price = $old_Cost - $new_Cost;
                                //     $supplier_balance   = $supplier_data->balance - $different_In_Price;
                                //     $supplier_payable   = $supplier_data->payable - $different_In_Price;
                                // }else{
                                //     $supplier_balance   = $supplier_data->balance;
                                //     $supplier_payable   = $supplier_data->payable;
                                // }
                                
                                $different_In_Price = $acc_res->more_acc_total_amount_purchase ?? '';
                                $supplier_balance   = $supplier_data->balance + $different_In_Price;
                                $supplier_payable   = $supplier_data->payable + $different_In_Price;
                                
                                // dd($supplier_balance,$supplier_payable);
                                
                                DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance,'payable'=>$supplier_payable]);
                            }
                        }
                    }
                }
            }else{
                // $prev_acc_Decoded                   = json_decode($prev_acc_more);
                // // dd($prev_acc_Decoded);
                // if(isset($prev_acc_Decoded) && $prev_acc_Decoded != null && $prev_acc_Decoded != ''){
                //     foreach($prev_acc_Decoded as $index => $val_PAD){
                //         $acc_Supplier_Id            = $val_PAD->more_hotel_supplier_id ?? $val_PAD->more_new_supplier_id ?? '';
                //         if($acc_Supplier_Id > 0){
                //             $supplier_data          = DB::table('rooms_Invoice_Supplier')->where('id',$acc_Supplier_Id)->select('id','balance','payable')->first();
                //             if(isset($supplier_data)){
                //                 $different_In_Price = $val_PAD->more_acc_total_amount ?? 0;
                //                 $supplier_balance   = $supplier_data->balance - $different_In_Price;
                //                 $supplier_payable   = $supplier_data->payable - $different_In_Price;
                                
                //                 DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance,'payable'=>$supplier_payable]);
                //             }
                //         }
                //     }
                // }
            }
            
            // dd('STOP');
            
            $req->accomodation_details      = json_encode($accomodation_data);
            $req->more_accomodation_details = json_encode($accomodation_more_data);
            
            $visa_details = json_decode($req->all_visa_price_details);
            
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
            
            // print_r($insert);
            
            //1
            $insert->customer_id             = $req->customer_id;
            $insert->services                = $req->services;
            $insert->booking_customer_id     = $req->booking_customer_id;
            $insert->agent_Id                = $req->agent_Id;
            $insert->agent_Name              = $req->agent_Name;
            $insert->agent_Company_Name      = $req->agent_Company_Name;
            
            $insert->b2b_Agent_Company_Name  = $req->b2b_Agent_Company_Name;
            $insert->b2b_Agent_Id            = $req->b2b_Agent_Id;
            $insert->b2b_Agent_Name          = $req->b2b_Agent_Name;
            
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
            $insert->passport_Image          = $req->passport_Image;
            
            //2
            $insert->city_Count              = $req->city_Count;
            $insert->adults                  = $req->adults;
            $insert->childs                  = $req->childs;
            $insert->infant                  = $req->infant;
            // Package_Data
            $generate_id                            = rand(0,9999999);
            // $insert->generate_id                    = $generate_id;
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
            
            $insert->all_visa_price_details             = $visa_details;
            $insert->visa_supplier_details              = $req->visa_supplier_details ?? '';
            
            if($req->more_visa_details != '""' && $req->more_visa_details != ''){
                $insert->more_visa_details              = $req->more_visa_details;
            }
            
            $insert->visa_rules_regulations             = $req->visa_rules_regulations;
            $insert->visa_image                         = $req->visa_image;
            
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
            
            $insert->quotation_id               = $req->quotation_id ?? '';
            $insert->quotation_Invoice          = $req->quotation_Invoice ?? '';
            
            $insert->hotel_Reservation_details  = $req->hotel_Reservation_details ?? '';
            $insert->transfer_Reservation_No    = $req->transfer_Reservation_No ?? '';
            $insert->flight_Reservation_No      = $req->flight_Reservation_No ?? '';
            $insert->visa_Reservation_No        = $req->visa_Reservation_No ?? '';
            
            $insert->ziyarat_details            = $req->ziyarat_details ?? '';
            
            $insert->lead_currency              = $req->lead_currency ?? '';
            
            $insert->currency_Rate_AC           = $req->currency_Rate_AC ?? '';
            $insert->currency_Type_AC           = $req->currency_Type_AC ?? '';
            $insert->currency_value_AC          = $req->currency_value_AC ?? '';
            $insert->total_cost_price_AC        = $req->total_cost_price_AC ?? '';
            $insert->total_sale_price_AC        = $req->total_sale_price_AC ?? '';
            
            $insert->currency_Rate_Company      = $req->currency_Rate_Company ?? '';
            $insert->currency_Type_Company      = $req->currency_Type_Company ?? '';
            $insert->currency_value_Company     = $req->currency_value_Company ?? '';
            $insert->total_cost_price_Company   = $req->total_cost_price_Company ?? '';
            $insert->total_sale_price_Company   = $req->total_sale_price_Company ?? '';
            
            $insert->groups_id                  = $req->groups_id ?? '';
            $insert->agent_Currency             = $req->agent_Currency ?? '';
            $insert->agent_Currency_all_details = $req->agent_Currency_all_details ?? '';
            $insert->group_Name                 = $req->group_Name ?? '';
            
            $insert->hotel_Supplier_Details     = $req->hotel_Supplier_Details ?? '';
            $insert->costing_Switch             = $req->costing_Switch ?? '';
            
            $today                              = Carbon::now();
            $formattedDate                      = $today->format('d M Y');
            $formattedTime                      = $today->format('H:i');
            if(isset($req->SU_id) && $req->SU_id > 0){
                $sub_User_Data                  = DB::table('role_managers')->where('customer_id',$req->customer_id)->where('id',$req->SU_id)->first();
                $updated_Remarks                = $sub_User_Data->first_name.' '.$sub_User_Data->last_name;
                // $updated_Remarks                = $sub_User_Data->first_name.' '.$sub_User_Data->last_name.' edited the invoice on '.$formattedDate.' at '.$formattedTime;
            }else{
                $sub_User_Data                  = DB::table('customer_subcriptions')->where('id',$req->customer_id)->first();
                $updated_Remarks                = $sub_User_Data->name.' '.$sub_User_Data->lname;
                // $updated_Remarks                = $sub_User_Data->name.' '.$sub_User_Data->lname.' edited the invoice on '.$formattedDate.' at '.$formattedTime;
            }
            // $insert->updated_Remarks            = $updated_Remarks ?? NULL;
            
            $insert->updated_at                 = Carbon::now();
            
            DB::beginTransaction();
            
            try {
                    $insert->update();
                    
                    $invoice_id     = $insert->id;
                    
                    // New Update
                    if(isset($req->otherPaxDetails)){
                        if($req->paxAdded > 1){
                            // Previous Remove
                            DB::table('otherPaxDetails')->where('invoice_id', $insert->id)->delete();
                            
                            $otherPaxDetails = json_decode($req->otherPaxDetails);
                            foreach($otherPaxDetails as $val_PD){
                                $otherPaxDetail                 = new otherPaxDetail();
                                $otherPaxDetail->customer_id    = $req->customer_id;
                                $otherPaxDetail->invoice_id     = $insert->id;
                                $otherPaxDetail->pilgramsName   = $val_PD->pilgramsName;
                                $otherPaxDetail->passportNumber = $val_PD->passportNumber;
                                $otherPaxDetail->dateOfBirth    = $val_PD->dateOfBirth;
                                $otherPaxDetail->groupType      = $val_PD->groupType;
                                $otherPaxDetail->issueDate      = $val_PD->issueDate;
                                $otherPaxDetail->groupCode      = $val_PD->groupCode;
                                $otherPaxDetail->save();
                            }
                        }
                    }
                    
                    // Remarks
                    $insertRemark                       = new addManageInvoiceRemark();
                    $insertRemark->customer_id          = $req->customer_id;
                    $insertRemark->invoice_Id           = $insert->id;
                    $insertRemark->remark_Name          = $updated_Remarks;
                    $insertRemark->remark_Status        = 'update_Invoice';
                    $insertRemark->invoice_Remarks      = $req->invoice_Remarks ?? NULL;
                    $insertRemark->save();
                    // Remarks
                    
                    $prev_acc       = json_decode($prev_acc);
                    $prev_acc_more  = json_decode($prev_acc_more);
                    $new_acc        = json_decode($req->accomodation_details);
                    $new_acc_more   = json_decode($req->more_accomodation_details);
                    
                    if($req->groups_id != null && $req->groups_id != ''){
                        $groups_id = json_decode($req->groups_id);
                        foreach($groups_id as $val_groups_id){
                            DB::table('addGroupsdetails')->where('customer_id',$req->customer_id)->where('id',$val_groups_id)->update([
                                'group_Invoice_No'  => $invoice_id,
                                'total_Invoices'    => 1,
                            ]);
                        }
                    }
                    
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
                        $agent_data = DB::table('Agents_detail')->where('id',$previous_agent)->select('id','balance')->first();
                        if(isset($agent_data)){
                            $received_amount = -1 * abs($previous_total_price);
                            $agent_balance = $agent_data->balance - $previous_total_price;
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
                        
                        $agent_data = DB::table('Agents_detail')->where('id',$new_agent)->select('id','balance')->first();
                        if(isset($agent_data)){
                            $received_amount    = $new_total_price;
                            $agent_balance      = $agent_data->balance + $new_total_price;
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
                        $difference  = $new_total_price - $previous_total_price;
                        $agent_data = DB::table('Agents_detail')->where('id',$new_agent)->select('id','balance')->first();
                        if(isset($agent_data)){
                            $agent_balance = $agent_data->balance + $difference;
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
                    
                    // B2B Agents
                    if($previous_b2b_Agent != $new_b2b_Agent){
                        $agent_data = DB::table('b2b_agents')->where('id',$previous_b2b_Agent)->select('id','balance')->first();
                        if(isset($agent_data)){
                            $received_amount    = -1 * abs($previous_total_price);
                            $agent_balance      = $agent_data->balance - $previous_total_price;
                            DB::table('b2b_agents')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                            DB::table('agents_ledgers_new')->insert([
                                'b2b_Agent_id'  => $agent_data->id,
                                'received'      => $received_amount,
                                'balance'       => $agent_balance,
                                'invoice_no'    => $insert->id,
                                'customer_id'   => $insert->customer_id,
                                'date'          => date('Y-m-d'),
                                'remarks'       => 'Invoice Updated',
                            ]);
                        }
                        
                        $agent_data = DB::table('b2b_agents')->where('id',$new_b2b_Agent)->select('id','balance')->first();
                        if(isset($agent_data)){
                            $received_amount    = $new_total_price;
                            $agent_balance      = $agent_data->balance + $new_total_price;
                            DB::table('b2b_agents')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                            DB::table('agents_ledgers_new')->insert([
                                'b2b_Agent_id'  => $agent_data->id,
                                'received'      => $received_amount,
                                'balance'       => $agent_balance,
                                'invoice_no'    => $insert->id,
                                'customer_id'   => $insert->customer_id,
                                'date'          => date('Y-m-d'),
                                'remarks'       => 'Invoice Updated',
                            ]);
                        }
                    }else{
                        $difference  = $new_total_price - $previous_total_price;
                        $agent_data = DB::table('b2b_agents')->where('id',$new_b2b_Agent)->select('id','balance')->first();
                        if(isset($agent_data)){
                            $agent_balance = $agent_data->balance + $difference;
                            DB::table('b2b_agents')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                            DB::table('agents_ledgers_new')->insert([
                                'b2b_Agent_id'  => $agent_data->id,
                                'received'      => $difference,
                                'balance'       => $agent_balance,
                                'invoice_no'    => $insert->id,
                                'customer_id'   => $insert->customer_id,
                                'date'          => date('Y-m-d'),
                                'remarks'       => 'Invoice Updated',
                            ]);
                        } 
                    }
                    // B2B Agents
                    
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
                    DB::table('rooms_bookings_details')->where('booking_id', "$insert->id")->where('booking_from','Invoices')->delete();
                    
                    // Previous Element Found Working
                    $arr_ele_found = [];
                    if(isset($prev_acc) && !empty($prev_acc)){
                        foreach($prev_acc as $prev_acc_res){
                            if(isset($prev_acc_res->hotelRoom_type_id) AND !empty($prev_acc_res->hotelRoom_type_id)){
                                // echo $prev_acc_res->hotelRoom_type_id;
                                
                                $found              = false;
                                foreach($arr_ele_found as $arr_id_res){
                                    if($arr_id_res == $prev_acc_res->hotelRoom_type_id){
                                        $found  = true;
                                    }
                                }
                                
                                if(!$found){
                                    $perv_total             = 0;
                                    $rooms_total_pr_prev    = 0;
                                    foreach($prev_acc as $cal_total_prev){
                                        if($prev_acc_res->hotelRoom_type_id == $cal_total_prev->hotelRoom_type_id){
                                            $perv_total += $cal_total_prev->acc_qty;
                                            $room_data  = DB::table('rooms')->where('id',$cal_total_prev->hotelRoom_type_id)->first();
                                            if($room_data){
                                                $week_days_total        = 0;
                                                $week_end_days_totals   = 0;
                                                $total_price            = 0;
                                                $acc_check_in           = date('Y-m-d',strtotime($cal_total_prev->acc_check_in));
                                                $acc_check_out          = date('Y-m-d',strtotime($cal_total_prev->acc_check_out));
                                                    if($room_data->price_week_type == 'for_all_days'){
                                                        $avaiable_days  = dateDiffInDays($acc_check_in, $acc_check_out);
                                                        $total_price    = $room_data->price_all_days * $avaiable_days;
                                                    }else{
                                                        $avaiable_days  = dateDiffInDays($acc_check_in, $acc_check_out);
                                                        $all_days       = getBetweenDates($acc_check_in, $acc_check_out);
                                                        $week_days      = json_decode($room_data->weekdays);
                                                        $week_end_days  = json_decode($room_data->weekends);
                                                        
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
                                                                $week_days_total += $room_data->weekdays_price;
                                                            }else{
                                                                $week_end_days_totals += $room_data->weekends_price;
                                                            }
                                                        }
                                                        
                                                        $total_price    = $week_days_total + $week_end_days_totals;
                                                    }
                                                    
                                                    $all_days_price         = $total_price * $cal_total_prev->acc_qty;
                                                    $rooms_total_pr_prev    += $all_days_price;
                                            }
                                        }
                                    }
                                    
                                    if(isset($prev_acc_more) && !empty($prev_acc_more)){
                                        foreach($prev_acc_more as $cal_total_prev){
                                            if($prev_acc_res->hotelRoom_type_id == $cal_total_prev->more_hotelRoom_type_id){
                                                $perv_total += $cal_total_prev->more_acc_qty;
                                                
                                                $room_data  = DB::table('rooms')->where('id',$cal_total_prev->more_hotelRoom_type_id)->first();
                                            
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
                                                                    
                                                                 }
                                                                 
                                                                 $total_price = $week_days_total + $week_end_days_totals;
                                                            }
                                                            
                                                            
                                                            $all_days_price = $total_price * $cal_total_prev->more_acc_qty;
                                                            $rooms_total_pr_prev += $all_days_price;
                                                    }
                                            }
                                        }
                                    }
                                    
                                    $new_total          = 0;
                                    $rooms_total_pr_new = 0;
                                    foreach($new_acc as $cal_total_prev){
                                        if($prev_acc_res->hotelRoom_type_id == $cal_total_prev->hotelRoom_type_id){
                                            $new_total  += $cal_total_prev->acc_qty;
                                            $room_data  = DB::table('rooms')->where('id',$cal_total_prev->hotelRoom_type_id)->first();
                                            
                                            if($room_data){
                                                $week_days_total        = 0;
                                                $week_end_days_totals   = 0;
                                                $total_price            = 0;
                                                $acc_check_in           = date('Y-m-d',strtotime($cal_total_prev->acc_check_in));
                                                $acc_check_out          = date('Y-m-d',strtotime($cal_total_prev->acc_check_out));
                                                if($room_data->price_week_type == 'for_all_days'){
                                                    $avaiable_days      = dateDiffInDays($acc_check_in, $acc_check_out);
                                                    $total_price        = $room_data->price_all_days * $avaiable_days;
                                                }else{
                                                    $avaiable_days      = dateDiffInDays($acc_check_in, $acc_check_out);
                                                    $all_days           = getBetweenDates($acc_check_in, $acc_check_out);
                                                    
                                                    $week_days          = json_decode($room_data->weekdays);
                                                    $week_end_days      = json_decode($room_data->weekends);
                                                    foreach($all_days as $day_res){
                                                        $day                = date('l', strtotime($day_res));
                                                        $day                = trim($day);
                                                        $week_day_found     = false;
                                                        $week_end_day_found = false;
                                                        
                                                        foreach($week_days as $week_day_res){
                                                            if($week_day_res == $day){
                                                                $week_day_found = true;
                                                            }
                                                        }
                                                        
                                                        if($week_day_found){
                                                            $week_days_total        += $room_data->weekdays_price;
                                                        }else{
                                                            $week_end_days_totals   += $room_data->weekends_price;
                                                        }
                                                    }
                                                    $total_price    = $week_days_total + $week_end_days_totals;
                                                }
                                                
                                                $all_days_price     = $total_price * $cal_total_prev->acc_qty;
                                                $rooms_total_pr_new += $all_days_price;
                                            }
                                            
                                            DB::table('rooms_bookings_details')->insert([
                                                'room_id'       => $cal_total_prev->hotelRoom_type_id,
                                                'booking_from'  => 'Invoices',
                                                'quantity'      => $cal_total_prev->acc_qty,
                                                'booking_id'    => $insert->id,
                                                'date'          => date('Y-m-d',strtotime($insert->created_at)),
                                                'check_in'      => $cal_total_prev->acc_check_in,
                                                'check_out'     => $cal_total_prev->acc_check_out,
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
                                
                                $room_data          = DB::table('rooms')->where('id',$prev_acc_res->hotelRoom_type_id)->first();
                                $difference         = $new_total - $perv_total;
                                $Price_difference   = $rooms_total_pr_new - $rooms_total_pr_prev;
                                $update_booked = null;
                                if(isset($room_data->booked)){
                                    $update_booked      = $room_data->booked + $difference;
                                     $room_data          = DB::table('rooms')->where('id',$prev_acc_res->hotelRoom_type_id)->update(['booked'=>$update_booked]);
                                        $room_data          = DB::table('rooms')->where('id',$prev_acc_res->hotelRoom_type_id)->first();
                                        $supplier_data      = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
                                        
                                        if(isset($supplier_data)){
                                            $supplier_balance           = $supplier_data->balance;
                                            $supplier_payable_balance   = $supplier_data->payable + $Price_difference;
                                            
                                            DB::table('hotel_supplier_ledger')->insert([
                                                'supplier_id'       => $supplier_data->id,
                                                'payment'           => $Price_difference,
                                                'balance'           => $supplier_balance,
                                                'payable_balance'   => $supplier_payable_balance,
                                                'room_id'           => $room_data->id,
                                                'customer_id'       => $insert->customer_id,
                                                'date'              => date('Y-m-d'),
                                                'invoice_no'        => $insert->id,
                                                'available_from'    => '',
                                                'available_to'      => '',
                                                'remarks'           => 'Invoice Updated',
                                            ]);
                                            
                                            // DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                                            //     'balance'   => $supplier_balance,
                                            //     'payable'   => $supplier_payable_balance
                                            // ]);
                                        }
                                }
                               
                            }
                        }
                    }
                    // else{
                    //     foreach($accomodation_data as $index => $val_AD){
                    //         if($acc_res->new_supplier_id){
                    //             $supplier_data              = DB::table('rooms_Invoice_Supplier')->where('id',$acc_res->new_supplier_id)->select('id','balance','payable')->first();
                    //             if(isset($supplier_data)){
                    //                 $prev_acc_Decoded       = json_decode($prev_acc);
                    //                 $old_Cost               = $prev_acc_Decoded[$index]->price_per_room_purchase ?? 0;
                    //                 $new_Cost               = $accomodation_data[$index]->price_per_room_purchase ?? 0;
                    //                 if($new_Cost > $old_Cost){
                    //                     $different_In_Price = $new_Cost - $old_Cost;
                    //                     $supplier_balance   = $supplier_data->balance + $different_In_Price;
                    //                     $supplier_payable   = $supplier_data->payable + $different_In_Price;
                    //                 }elseif($new_Cost < $old_Cost){
                    //                     $different_In_Price = $old_Cost - $new_Cost;
                    //                     $supplier_balance   = $supplier_data->balance - $different_In_Price;
                    //                     $supplier_payable   = $supplier_data->payable - $different_In_Price;
                    //                 }else{
                    //                     $supplier_balance   = $supplier_data->balance;
                    //                     $supplier_payable   = $supplier_data->payable;
                    //                 }
                                    
                    //                 DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance,'payable'=>$supplier_payable]);
                    //             }
                    //         }
                    //     }
                    // }
                    
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
                                                     }
                                                    
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
                                    
                                    $new_total          = 0;
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
                                    
                                    $room_data          = DB::table('rooms')->where('id',$prev_acc_res->more_hotelRoom_type_id)->first();
                                    $difference         = $new_total - $perv_total;
                                    $Price_difference   = $rooms_total_pr_new - $rooms_total_pr_prev;
                                    $update_booked      = $room_data->booked + $difference;
                                    
                                    $room_data          = DB::table('rooms')->where('id',$prev_acc_res->more_hotelRoom_type_id)->update(['booked'=>$update_booked]);
                                    $room_data          = DB::table('rooms')->where('id',$prev_acc_res->more_hotelRoom_type_id)->first();
                                    
                                    $supplier_data      = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
                                    
                                    if(isset($supplier_data)){
                                        $supplier_balance           = $supplier_data->balance;
                                        $supplier_payable_balance   = $supplier_data->payable + $Price_difference;
                                        
                                        DB::table('hotel_supplier_ledger')->insert([
                                            'supplier_id'       => $supplier_data->id,
                                            'payment'           => $Price_difference,
                                            'balance'           => $supplier_balance,
                                            'payable_balance'   => $supplier_payable_balance,
                                            'room_id'           => $room_data->id,
                                            'customer_id'       => $insert->customer_id,
                                            'date'              => date('Y-m-d'),
                                            'invoice_no'        => $insert->id,
                                            'available_from'    => '',
                                            'available_to'      => '',
                                            'remarks'           => 'Invoice Updated',
                                        ]);
                                        
                                        // DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                                        //     'balance'=>$supplier_balance,
                                        //     'payable'=>$supplier_payable_balance
                                        // ]);
                                    }
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
                                    $room_data      = DB::table('rooms')->where('id',$new_acc_res->hotelRoom_type_id)->first();
                                    $update_booked  = (int)$room_data->booked + (int)$new_acc_res->acc_qty;
                                    $room_update    = DB::table('rooms')->where('id',$new_acc_res->hotelRoom_type_id)->update(['booked'=>$update_booked]);
                                    
                                    DB::table('rooms_bookings_details')->insert([
                                        'room_id'       => $new_acc_res->hotelRoom_type_id,
                                        'booking_from'  => 'Invoices',
                                        'quantity'      => $new_acc_res->acc_qty,
                                        'booking_id'    => $insert->id,
                                        'date'          => date('Y-m-d',strtotime($insert->created_at)),
                                        'check_in'      => $new_acc_res->acc_check_in,
                                        'check_out'     => $new_acc_res->acc_check_out,
                                    ]);
                                    
                                    $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$room_data->room_supplier_name)->select('id','balance','payable')->first();
                                    
                                    if(isset($supplier_data)){
                                        
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
                                              
                                                     if($week_day_found){
                                                         $week_days_total += $room_data->weekdays_price;
                                                     }else{
                                                         $week_end_days_totals += $room_data->weekends_price;
                                                     }
                                                 }
                                                 $total_price = $week_days_total + $week_end_days_totals;
                                            }
                                            
                                            
                                            $all_days_price             = $total_price * $new_acc_res->acc_qty;
                                            
                                            $supplier_balance           = $supplier_data->balance;
                                            $supplier_payable_balance   = $supplier_data->payable + $all_days_price;
                                        
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
                                        
                                        // DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                                        //     'balance'   => $supplier_balance,
                                        //     'payable'   => $supplier_payable_balance
                                        // ]);   
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
                                                     
                                                 }
                                                 
                                                 $total_price = $week_days_total + $week_end_days_totals;
                                            }
                                            
                                            
                                        $all_days_price = $total_price * $new_acc_res->more_acc_qty;
                                        
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
                                            
                                        // DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                                        //     'balance'=>$supplier_balance,
                                        //     'payable'=>$supplier_payable_balance
                                        // ]);
                                    }
                                }
                             }
                         }
                     }
                    
                    // 1 Loop on Previous
                    // dd($prev_flight_pax,$new_flight_pax);
                    if(isset($prev_flight_pax)){
                        foreach($prev_flight_pax as $flight_prev_res){
                            $ele_found                                      = false;
                            foreach($new_flight_pax as $flight_new_res){
                                if($flight_prev_res->flight_route_id_occupied == $flight_new_res->flight_route_id_occupied){
                                    $ele_found                              = true;
                                    $route_obj                              = DB::table('flight_rute')->where('id',$flight_prev_res->flight_route_id_occupied)->first();
                                    if($route_obj != null){
                                        // Calaculate Child Prev Price Differ
                                        $child_price_wi_adult_price_prev    = $route_obj->flights_per_person_price * $flight_prev_res->flights_adult_seats;
                                        $child_price_wi_child_price_prev    = $route_obj->flights_per_child_price * $flight_prev_res->flights_child_seats;
                                        $price_diff_prev                    = $child_price_wi_adult_price_prev - $child_price_wi_child_price_prev;
                                        // Calaculate Child New Price Differ
                                        $child_price_wi_adult_price_new     = $route_obj->flights_per_person_price * $flight_new_res->flights_adult_seats;
                                        $child_price_wi_child_price_new     = $route_obj->flights_per_child_price * $flight_new_res->flights_child_seats;
                                        $price_diff_new                     = $child_price_wi_adult_price_new - $child_price_wi_child_price_new;
                                        // Calculate Final Differ
                                        $child_price_diff                   = $price_diff_new - $price_diff_prev;
                                        // Calaculate Infant Prev Price
                                        $infant_price_prev                  = $route_obj->flights_per_infant_price * $flight_prev_res->flights_infant_seats;
                                        // Calaculate Infant New Price
                                        $infant_price_new                   = $route_obj->flights_per_infant_price * $flight_new_res->flights_infant_seats;
                                        // Calculate Final Differ
                                        $infant_price_diff                  = $infant_price_new - $infant_price_prev;
                                        $supplier_data                      = DB::table('supplier')->where('id',$route_obj->dep_supplier)->first();
                                        if($child_price_diff != 0 || $infant_price_diff != 0){
                                            $supplier_balance               = $supplier_data->balance - $child_price_diff;
                                            $supplier_balance               = $supplier_balance + $infant_price_diff;
                                            $total_differ                   = $infant_price_diff - $child_price_diff;
                                            
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
                            }
                            
                            // If element Not Found in New
                            if(!$ele_found){
                                $route_obj                          = DB::table('flight_rute')->where('id',$flight_prev_res->flight_route_id_occupied)->first();
                                $child_price_wi_adult_price_prev    = $route_obj->flights_per_person_price * $flight_prev_res->flights_adult_seats;
                                $child_price_wi_child_price_prev    = $route_obj->flights_per_child_price * $flight_prev_res->flights_child_seats;
                                $price_diff_prev                    = $child_price_wi_adult_price_prev - $child_price_wi_child_price_prev;
                                // Calaculate Infant Prev Price
                                $infant_price_prev                  = $route_obj->flights_per_infant_price * $flight_prev_res->flights_infant_seats;
                                
                                // New
                                $total_Balance                      = $child_price_wi_adult_price_prev + $child_price_wi_child_price_prev + $infant_price_prev;
                                // New
                                
                                $supplier_data                      = DB::table('supplier')->where('id',$route_obj->dep_supplier)->first();
                                if($price_diff_prev != 0 || $infant_price_prev != 0){
                                    $supplier_balance               = $supplier_data->balance + $price_diff_prev;
                                    $supplier_balance               = $supplier_balance - $infant_price_prev;
                                    $total_differ                   = $price_diff_prev - $infant_price_prev;
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
                                    // DB::table('supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                                }
                                
                                // New
                                $supplier_balance_New = $supplier_data->balance - $total_Balance;
                                DB::table('supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance_New]);
                                // New
                            }
                        }
                    }
                    
                    // 2 Loop on New
                    if(!empty($new_flight_pax)){
                        foreach($new_flight_pax as $flight_new_res){
                            // dd($flight_new_res);
                            $pre_el_found = false;
                            if(!empty($prev_flight_pax)){
                                foreach($prev_flight_pax as $flight_prev_res){
                                    if($flight_prev_res->flight_route_id_occupied == $flight_new_res->flight_route_id_occupied){
                                        $pre_el_found = true;
                                    }
                                }
                            }
                            
                            // If element Not Found in Prev
                            if(!$pre_el_found){
                                $route_obj                          = DB::table('flight_rute')->where('id',$flight_new_res->flight_route_id_occupied)->first();
                                
                                // New
                                $flights_per_person_price           = $route_obj->flights_per_person_price ?? 0;
                                $flights_per_child_price            = $route_obj->flights_per_child_price ?? 0;
                                $flights_per_infant_price           = $route_obj->flights_per_infant_price ?? 0;
                                // New
                                
                                $child_price_wi_adult_price_prev    = $flights_per_person_price * $flight_new_res->flights_adult_seats;
                                $child_price_wi_child_price_prev    = $flights_per_child_price * $flight_new_res->flights_child_seats;
                                $price_diff_prev                    = $child_price_wi_adult_price_prev - $child_price_wi_child_price_prev;
                                // Calaculate Infant Prev Price
                                $infant_price_prev                  = $flights_per_infant_price * $flight_new_res->flights_infant_seats;
                                $supplier_data                      = DB::table('supplier')->where('id',$route_obj->dep_supplier ?? '00')->first();
                                
                                // New
                                $total_Balance                      = $child_price_wi_adult_price_prev + $child_price_wi_child_price_prev + $infant_price_prev;
                                // New
                                
                                if($price_diff_prev != 0 || $infant_price_prev != 0){
                                    $supplier_balance               = $supplier_data->balance - $price_diff_prev;
                                    $supplier_balance               = $supplier_balance + $infant_price_prev;
                                    $total_differ                   = $infant_price_prev - $price_diff_prev;
                                    DB::table('flight_supplier_ledger')->insert([
                                        'supplier_id'=>$supplier_data->id,
                                        'payment'=>$total_differ,
                                        'balance'=>$supplier_balance,
                                        'route_id'=>$flight_new_res->flight_route_id_occupied,
                                        'date'=>date('Y-m-d'),
                                        'customer_id'=>$insert->customer_id,
                                        'adult_price'=>$route_obj->flights_per_person_price ?? '0',
                                        'child_price'=>$route_obj->flights_per_child_price ?? '0',
                                        'infant_price'=>$route_obj->flights_per_infant_price ?? '0',
                                        'adult_seats_booked'=>$flight_new_res->flights_adult_seats,
                                        'child_seats_booked'=>$flight_new_res->flights_child_seats,
                                        'infant_seats_booked'=>$flight_new_res->flights_infant_seats,
                                        'invoice_no'=>$insert->id,
                                        'remarks'=>'Invoice Update',
                                    ]);
                                    // DB::table('supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                                }
                                
                                // New
                                $supplier_balance_New = $supplier_data->balance + $total_Balance;
                                DB::table('supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance_New]);
                                // New
                            }
                        }
                    }
                    
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
                        
                        $req->transfer_supplier_id = (int)$req->transfer_supplier_id;
                        // Update Supplier Balance
                        $transfer_sup_data = DB::table('transfer_Invoice_Supplier')->where('id',$req->transfer_supplier_id)->select('id','balance')->first();
                        
                        if(isset($transfer_sup_data)){
                            $trans_sup_balance = $transfer_sup_data->balance + $price_diff;
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
                                    
                                    $trans_sup_balance = (float)$trans_sup_balance;
                                    
                                $update_result = DB::table('transfer_Invoice_Supplier')->where('id',$req->transfer_supplier_id)->update(['balance'=>$trans_sup_balance]);
                              
                            }
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
                                             
                                    if(isset($transfer_sup_data)){               
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
                                            DB::table('transfer_Invoice_Supplier')->where('id',$transfer_sup_data->id)->update(['balance'=>$trans_sup_balance]);
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
                                    
                                    // echo $transfer_new_total;
                                    // die;
                                      // Update Supplier Balance
                                     $transfer_new_total = (float)$transfer_new_total;
                                    $transfer_sup_data = DB::table('transfer_Invoice_Supplier')->where('id',$new_transfer_sup)->select('id','balance')->first();
                                    
                                    if(isset($transfer_sup_data)){                        
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
                            
                        
                    }
                    
                    if($req->customer_id != 30){
                        // 1 Loop on New 
                        if(isset($new_visa_all_details)){
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
                        }
                        
                        // 2 Loop on Previous 
                        if(isset($new_visa_all_details) && isset($prev_visa_all_details)){
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
                        }
                    }else{
                        // dd($prev_visa_all_details);
                        // print_r($new_visa_all_details);
                        // die;
                        
                        if(isset($prev_visa_all_details) && !empty($prev_visa_all_details) && isset($new_visa_all_details) && !empty($new_visa_all_details)){
                            if($prev_visa_all_details[0]->visa_supplier_id == $new_visa_all_details[0]->visa_supplier_id && $new_visa_all_details[0]->visa_supplier_id != 'Select Supplier'){
                                // Supplier is Not Change
                                
                                // Calaculate Total Visa price in Previous Invoice
                                $visa_total_price_previous = 0;
                                foreach($prev_visa_all_details as $visa_res){
                                    $visa_total_price_previous += $visa_res->visa_fee_purchase * $visa_res->visa_occupied;
                                }
                                
                                // Calaculate Total Visa price in Updated Invoice
                                $visa_total_price_new = 0;
                                foreach($new_visa_all_details as $visa_res){
                                    $visa_total_price_new += $visa_res->visa_fee_purchase * $visa_res->visa_occupied;
                                }
                                
                                // Difference Amount
                                $difference = $visa_total_price_new - $visa_total_price_previous;
                                
                                  // 3 Update Visa Supplier Balance
                                 if(isset($new_visa_all_details[0]->visa_supplier_id) && !empty($new_visa_all_details[0]->visa_supplier_id)){
                                     $visa_supplier_id = $new_visa_all_details[0]->visa_supplier_id;
                                     $visa_supplier_data = DB::table('visa_Sup')->where('id',$visa_supplier_id)->first();
                                
                                
                                    $visa_supplier_payable_balance = $visa_supplier_data->payable + $difference;
                                    $visa_total_sale = $difference;
                            
                                    
                                    DB::table('visa_supplier_ledger')->insert([
                                            'supplier_id' => $visa_supplier_id,
                                            'payment' => $visa_total_sale,
                                            'balance' => $visa_supplier_data->balance,
                                            'payable' => $visa_supplier_payable_balance,
                                            'visa_qty' => '',
                                            'visa_type' => '',
                                            'invoice_no' => $insert->id,
                                            'remarks' => 'Invoice Updated',
                                            'date' => date('Y-m-d'),
                                            'customer_id' => $req->customer_id,
                                    ]);
                                    
                                    DB::table('visa_Sup')->where('id',$visa_supplier_id)->update([
                                            'payable' => $visa_supplier_payable_balance
                                    ]);
                                 }
                               
                        
                                // echo "Previous Invoice price $visa_total_price_previous and New Invoice Price $visa_total_price_new";
                                // dd('Stop');
                            }else{
                                // Supplier is Change
                                
                                // Calaculate Total Visa price in Previous Invoice
                                $visa_total_price_previous = 0;
                                foreach($prev_visa_all_details as $visa_res){
                                    $visa_total_price_previous += $visa_res->visa_fee_purchase * $visa_res->visa_occupied;
                                }
                                
                              // 3 Update Previous Visa Supplier Balance
                                     if(isset($prev_visa_all_details[0]->visa_supplier_id) && !empty($prev_visa_all_details[0]->visa_supplier_id) && $prev_visa_all_details[0]->visa_supplier_id != 'Select Supplier'){
                                         $visa_supplier_id = $prev_visa_all_details[0]->visa_supplier_id;
                                         $visa_supplier_data = DB::table('visa_Sup')->where('id',$visa_supplier_id)->first();
                                    
                                    
                                        $visa_supplier_payable_balance = $visa_supplier_data->payable - $visa_total_price_previous;
                                        $visa_total_sale = $visa_total_price_previous;
                                
                                        
                                        DB::table('visa_supplier_ledger')->insert([
                                                'supplier_id' => $visa_supplier_id,
                                                'payment' => $visa_total_sale,
                                                'balance' => $visa_supplier_data->balance,
                                                'payable' => $visa_supplier_payable_balance,
                                                'visa_qty' => '',
                                                'visa_type' => '',
                                                'invoice_no' => $insert->id,
                                                'remarks' => 'Invoice Updated',
                                                'date' => date('Y-m-d'),
                                                'customer_id' => $req->customer_id,
                                        ]);
                                        
                                        DB::table('visa_Sup')->where('id',$visa_supplier_id)->update([
                                                'payable' => $visa_supplier_payable_balance
                                        ]);
                                     }
                                    
                                    // Calaculate Total Visa price in Updated Invoice
                                    $visa_total_price_new = 0;
                                    foreach($new_visa_all_details as $visa_res){
                                        $visa_total_price_new += $visa_res->visa_fee_purchase * $visa_res->visa_occupied;
                                    }
                                    
                                // 3 Update New Visa Supplier Balance
                                     if(isset($new_visa_all_details[0]->visa_supplier_id) && !empty($new_visa_all_details[0]->visa_supplier_id) && $new_visa_all_details[0]->visa_supplier_id != 'Select Supplier'){
                                         $visa_supplier_id = $new_visa_all_details[0]->visa_supplier_id;
                                         $visa_supplier_data = DB::table('visa_Sup')->where('id',$visa_supplier_id)->first();
                                    
                                    
                                        $visa_supplier_payable_balance = $visa_supplier_data->payable + $visa_total_price_new;
                                        $visa_total_sale = $visa_total_price_new;
                                
                                        
                                        DB::table('visa_supplier_ledger')->insert([
                                                'supplier_id' => $visa_supplier_id,
                                                'payment' => $visa_total_sale,
                                                'balance' => $visa_supplier_data->balance,
                                                'payable' => $visa_supplier_payable_balance,
                                                'visa_qty' => '',
                                                'visa_type' => '',
                                                'invoice_no' => $insert->id,
                                                'remarks' => 'Invoice Updated',
                                                'date' => date('Y-m-d'),
                                                'customer_id' => $req->customer_id,
                                        ]);
                                        
                                        DB::table('visa_Sup')->where('id',$visa_supplier_id)->update([
                                                'payable' => $visa_supplier_payable_balance
                                        ]);
                                     }
                                }
                        }
                       
                    }
                 
                    DB::commit();
                 
            } catch (Throwable $e) {
                echo $e;
                DB::rollback();
                return response()->json(['message'=>'error','booking_id'=> '']);
            }
            return response()->json(['status'=>'success','invoice_id'=>$req->id,'message'=>'Agent Invoice Updated Succesfully']); 
        }else{
            return response()->json(['status'=>'error','message'=>$id]); 
        }
    }
    
    public function update_Invoices_CP(Request $req){
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
            
            $prev_acc               = $insert->accomodation_details;
            $prev_acc_more          = $insert->accomodation_details_more;
            
            $previous_agent         = $insert->agent_Id;
            $new_agent              = $req->agent_Id;
            
            $previous_b2b_Agent     = $insert->b2b_Agent_Id;
            $new_b2b_Agent          = $req->b2b_Agent_Id;
            
            $previous_transfer_sup  = $insert->transfer_supplier_id;
            $new_transfer_sup       = $req->transfer_supplier_id;
            
            $previous_customer      = $insert->booking_customer_id;
            $new_customer           = $req->booking_customer_id;
            
            $previous_total_price   = $insert->total_sale_price_all_Services;
            $new_total_price        = $req->total_sale_price_all_Services;
            
            $prev_flight_pax        = json_decode($insert->flights_Pax_details);
            $new_flight_pax         = json_decode($req->flights_Pax_details);
            
            $prev_transfer_det      = json_decode($insert->transportation_details);
            $new_transfer_det       = json_decode($req->transportation_details);
            
            $accomodation_data      = json_decode($req->accomodation_details);
            $accomodation_more_data = json_decode($req->more_accomodation_details);
            
            // dd(json_decode($prev_acc_more),$accomodation_more_data);
            
            $prev_acc_Decoded = json_decode($prev_acc);
            if(isset($prev_acc_Decoded) && $prev_acc_Decoded != null && $prev_acc_Decoded != ''){
                foreach($prev_acc_Decoded as $val_PAD){
                    $acc_Supplier_Id            = $val_PAD->hotel_supplier_id ?? '';
                    if($acc_Supplier_Id > 0){
                        $supplier_data          = DB::table('rooms_Invoice_Supplier')->where('id',$acc_Supplier_Id)->select('id','balance','payable')->first();
                        if(isset($supplier_data)){
                            $different_In_Price = $val_PAD->acc_total_amount_purchase ?? 0;
                            $supplier_balance   = $supplier_data->balance - $different_In_Price;
                            $supplier_payable   = $supplier_data->payable - $different_In_Price;
                            
                            DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance,'payable'=>$supplier_payable]);
                        }
                    }
                }
            }
            
            if(isset($accomodation_data)){
                // dd($accomodation_data);
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
                            
                            $all_days_price     = $total_price * $Rooms->quantity;
                            $supplier_balance   = $supplier_data->balance + $all_days_price;
                            
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
                            
                            // DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                            
                            $different_In_Price     = $acc_res->acc_total_amount_purchase ?? 0;
                            $supplier_balance_New   = $supplier_data->balance + $different_In_Price;
                            $supplier_payable_New   = $supplier_data->payable + $different_In_Price;
                            
                            DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance_New,'payable'=>$supplier_payable_New]);    
                        }
                        
                        $accomodation_data[$index]->acc_type = $room_type_data->parent_cat;
                        $accomodation_data[$index]->hotel_supplier_id = $acc_res->new_supplier_id;
                        $accomodation_data[$index]->hotel_type_id = $room_type_data->id;
                        $accomodation_data[$index]->hotel_type_cat = $room_type_data->room_type;
                        $accomodation_data[$index]->hotelRoom_type_id = $Roomsid;
                    }
                    else{
                        $acc_Supplier_Id                = $acc_res->hotel_supplier_id ?? $acc_res->new_supplier_id ?? '';
                        if($acc_Supplier_Id > 0){
                            $supplier_data              = DB::table('rooms_Invoice_Supplier')->where('id',$acc_Supplier_Id)->select('id','balance','payable')->first();
                            if(isset($supplier_data)){
                                $different_In_Price     = $acc_res->acc_total_amount_purchase ?? 0;
                                $supplier_balance       = $supplier_data->balance + $different_In_Price;
                                $supplier_payable       = $supplier_data->payable + $different_In_Price;
                                DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance,'payable'=>$supplier_payable]);
                            }
                        }
                    }
                }
            }
            
            $prev_acc_more_Decoded = json_decode($prev_acc_more);
            if(isset($prev_acc_more_Decoded) && $prev_acc_more_Decoded != null && $prev_acc_more_Decoded != ''){
                foreach($prev_acc_more_Decoded as $val_PAMD){
                    $acc_Supplier_Id            = $val_PAMD->more_hotel_supplier_id ?? '';
                    if($acc_Supplier_Id > 0){
                        $supplier_data          = DB::table('rooms_Invoice_Supplier')->where('id',$acc_Supplier_Id)->select('id','balance','payable')->first();
                        if(isset($supplier_data)){
                            $different_In_Price = $val_PAMD->more_acc_total_amount_purchase ?? 0;
                            $supplier_balance   = $supplier_data->balance - $different_In_Price;
                            $supplier_payable   = $supplier_data->payable - $different_In_Price;
                            
                            DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance,'payable'=>$supplier_payable]);
                        }
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
                            
                            $all_days_price     = $total_price * $Rooms->quantity;
                            $supplier_balance   = $supplier_data->balance + $all_days_price;
                            
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
                            
                            // DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                            
                            $different_In_Price     = $acc_res->more_acc_total_amount_purchase ?? 0;
                            $supplier_balance_New   = $supplier_data->balance + $different_In_Price;
                            $supplier_payable_New   = $supplier_data->payable + $different_In_Price;
                            
                            DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance_New,'payable'=>$supplier_payable_New]);  
                        }
                        
                        $accomodation_more_data[$index]->more_acc_type = $room_type_data->parent_cat;
                        $accomodation_more_data[$index]->more_hotel_supplier_id = $acc_res->more_new_supplier_id;
                        $accomodation_more_data[$index]->more_hotel_type_id = $room_type_data->id;
                        $accomodation_more_data[$index]->more_hotel_type_cat = $room_type_data->room_type;
                        $accomodation_more_data[$index]->more_hotelRoom_type_id = $Roomsid;
                    }
                    else{
                        $acc_Supplier_Id                = $acc_res->more_hotel_supplier_id ?? $acc_res->more_new_supplier_id ?? '';
                        if($acc_Supplier_Id > 0){
                            $supplier_data              = DB::table('rooms_Invoice_Supplier')->where('id',$acc_Supplier_Id)->select('id','balance','payable')->first();
                            if(isset($supplier_data)){
                                $different_In_Price     = $acc_res->more_acc_total_amount_purchase ?? '';
                                $supplier_balance       = $supplier_data->balance + $different_In_Price;
                                $supplier_payable       = $supplier_data->payable + $different_In_Price;
                                DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance,'payable'=>$supplier_payable]);
                            }
                        }
                    }
                }
            }
            
            $req->accomodation_details      = json_encode($accomodation_data);
            $req->more_accomodation_details = json_encode($accomodation_more_data);
            
            //1
            $insert->customer_id             = $req->customer_id;
            $insert->services                = $req->services;
            // $insert->quotation_validity      = $req->quotation_validity;
            $insert->booking_customer_id     = $req->booking_customer_id;
            $insert->agent_Id                = $req->agent_Id;
            $insert->agent_Name              = $req->agent_Name;
            $insert->agent_Company_Name      = $req->agent_Company_Name;
            
            $insert->b2b_Agent_Company_Name  = $req->b2b_Agent_Company_Name;
            $insert->b2b_Agent_Id            = $req->b2b_Agent_Id;
            $insert->b2b_Agent_Name          = $req->b2b_Agent_Name;
            
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
            
            $insert->passport_Image          = $req->passport_Image ?? '';
            
            //2
            $insert->adults                  = $req->adults;
            $insert->childs                  = $req->childs;
            $insert->infant                  = $req->infant;
            // Package_Data
            $generate_id                            = rand(0,9999999);
            // $insert->generate_id                    = $generate_id;
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
            
            
            $insert->total_cost_price_all_Services      = $req->total_cost_price_all_Services;
            
            if(isset($req->total_discount_all_Services) && $req->total_discount_all_Services != null && $req->total_discount_all_Services != '' && $req->total_discount_all_Services != 0){
                $insert->total_sale_price_all_Services      = $req->total_discount_price_all_Services;
                $insert->total_discount_price_all_Services  = $req->total_sale_price_all_Services ?? '';
            }else{
                $insert->total_sale_price_all_Services      = $req->total_sale_price_all_Services;
                $insert->total_discount_price_all_Services  = NULL;
            }
            
            $insert->total_discount_all_Services    = $req->total_discount_all_Services ?? ''; 
            
            $insert->transfer_supplier              = $req->transfer_supplier;
            $insert->transfer_supplier_id           = $req->transfer_supplier_id;
            
            $insert->all_costing_details            = $req->all_costing_details;
            $insert->all_costing_details_child      = $req->all_costing_details_child;
            $insert->all_costing_details_infant     = $req->all_costing_details_infant;
            
            $insert->all_dynamic_costing            = $req->all_dynamic_costing;
            $insert->all_dynamic_child_costing      = $req->all_dynamic_child_costing;
            $insert->all_dynamic_infant_costing     = $req->all_dynamic_infant_costing;
            
            $all_services_quotation                 = 1;
            $insert->all_services_quotation         = $all_services_quotation;
            
            // Adult
            $insert->WQFVT_details                  = $req->WQFVT_details ?? '';
            $insert->WDFVT_details                  = $req->WDFVT_details ?? '';
            $insert->WTFVT_details                  = $req->WTFVT_details ?? '';
            $insert->WAFVT_details                  = $req->WAFVT_details ?? '';
            
            // Child
            $insert->WQFVT_details_child            = $req->WQFVT_details_child ?? '';
            $insert->WTFVT_details_child            = $req->WTFVT_details_child ?? '';
            $insert->WDFVT_details_child            = $req->WDFVT_details_child ?? '';
            $insert->WAFVT_details_child            = $req->WAFVT_details_child ?? '';
            
            // Infant
            $insert->WQFVT_details_infant           = $req->WQFVT_details_infant ?? '';
            $insert->WTFVT_details_infant           = $req->WTFVT_details_infant ?? '';
            $insert->WDFVT_details_infant           = $req->WDFVT_details_infant ?? '';
            $insert->WAFVT_details_infant           = $req->WAFVT_details_infant ?? '';
            
            $insert->hotel_Reservation_details      = $req->hotel_Reservation_details ?? '';
            $insert->transfer_Reservation_No        = $req->transfer_Reservation_No ?? '';
            $insert->flight_Reservation_No          = $req->flight_Reservation_No ?? '';
            $insert->visa_Reservation_No            = $req->visa_Reservation_No ?? '';
            
            $insert->ziyarat_details                = $req->ziyarat_details ?? '';
            
            $insert->lead_currency                  = $req->lead_currency ?? '';
            
            $insert->currency_Rate_AC               = $req->currency_Rate_AC ?? '';
            $insert->currency_Type_AC               = $req->currency_Type_AC ?? '';
            $insert->currency_value_AC              = $req->currency_value_AC ?? '';
            $insert->total_cost_price_AC            = $req->total_cost_price_AC ?? '';
            $insert->total_sale_price_AC            = $req->total_sale_price_AC ?? '';
            
            $insert->currency_Rate_Company          = $req->currency_Rate_Company ?? '';
            $insert->currency_Type_Company          = $req->currency_Type_Company ?? '';
            $insert->currency_value_Company         = $req->currency_value_Company ?? '';
            $insert->total_cost_price_Company       = $req->total_cost_price_Company ?? '';
            $insert->total_sale_price_Company       = $req->total_sale_price_Company ?? '';
            
            $insert->groups_id                      = $req->groups_id ?? '';
            
            // dd($req->SU_id);
            
            $today                                  = Carbon::now();
            $formattedDate                          = $today->format('d M Y');
            $formattedTime                          = $today->format('H:i');
            if(isset($req->SU_id) && $req->SU_id > 0){
                $sub_User_Data                      = DB::table('role_managers')->where('customer_id',$req->customer_id)->where('id',$req->SU_id)->first();
                // dd($sub_User_Data);
                $updated_Remarks                    = $sub_User_Data->first_name.' '.$sub_User_Data->last_name;
                // $updated_Remarks                    = $sub_User_Data->first_name.' '.$sub_User_Data->last_name.' edited the invoice on '.$formattedDate.' at '.$formattedTime;
            }else{
                $sub_User_Data                      = DB::table('customer_subcriptions')->where('id',$req->customer_id)->first();
                $updated_Remarks                    = $sub_User_Data->name.' '.$sub_User_Data->lname;
                // $updated_Remarks                    = $sub_User_Data->name.' '.$sub_User_Data->lname.' edited the invoice on '.$formattedDate.' at '.$formattedTime;
            }
            
            // $insert->updated_Remarks                = $updated_Remarks ?? NULL;
            
            $insert->updated_at                     = Carbon::now();
            
            DB::beginTransaction();
            
            try {
                    $insert->update();
                    
                    $invoice_id     = $insert->id;
                    
                    // New Update
                    if(isset($req->otherPaxDetails)){
                        if($req->paxAdded > 1){
                            // Previous Remove
                            DB::table('otherPaxDetails')->where('invoice_id', $insert->id)->delete();
                            
                            $otherPaxDetails = json_decode($req->otherPaxDetails);
                            foreach($otherPaxDetails as $val_PD){
                                $otherPaxDetail                 = new otherPaxDetail();
                                $otherPaxDetail->customer_id    = $req->customer_id;
                                $otherPaxDetail->invoice_id     = $insert->id;
                                $otherPaxDetail->pilgramsName   = $val_PD->pilgramsName;
                                $otherPaxDetail->passportNumber = $val_PD->passportNumber;
                                $otherPaxDetail->dateOfBirth    = $val_PD->dateOfBirth;
                                $otherPaxDetail->groupType      = $val_PD->groupType;
                                $otherPaxDetail->issueDate      = $val_PD->issueDate;
                                $otherPaxDetail->groupCode      = $val_PD->groupCode;
                                $otherPaxDetail->save();
                            }
                        }
                    }
                    
                    // Remarks
                    $insertRemark                       = new addManageInvoiceRemark();
                    $insertRemark->customer_id          = $req->customer_id;
                    $insertRemark->invoice_Id           = $insert->id;
                    $insertRemark->remark_Name          = $updated_Remarks;
                    $insertRemark->remark_Status        = 'update_Invoice';
                    $insertRemark->invoice_Remarks      = $req->invoice_Remarks ?? NULL;
                    $insertRemark->save();
                    // Remarks
                    
                    $prev_acc                           = json_decode($prev_acc);
                    $prev_acc_more                      = json_decode($prev_acc_more);
                    $new_acc                            = json_decode($req->accomodation_details);
                    $new_acc_more                       = json_decode($req->more_accomodation_details);
                    
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
                        $agent_data = DB::table('Agents_detail')->where('id',$previous_agent)->select('id','balance')->first();
                        if(isset($agent_data)){
                            $received_amount = -1 * abs($previous_total_price);
                            $agent_balance = $agent_data->balance - $previous_total_price;
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
                        $agent_data = DB::table('Agents_detail')->where('id',$new_agent)->select('id','balance')->first();
                        if(isset($agent_data)){
                            $received_amount = $new_total_price;
                            $agent_balance = $agent_data->balance + $new_total_price;
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
                        $difference  = $new_total_price - $previous_total_price;
                        $agent_data = DB::table('Agents_detail')->where('id',$new_agent)->select('id','balance')->first();
                        if(isset($agent_data)){
                            $agent_balance = $agent_data->balance + $difference;
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
                    
                    // B2B Agents
                    if($previous_b2b_Agent != $new_b2b_Agent){
                        $agent_data = DB::table('b2b_agents')->where('id',$previous_b2b_Agent)->select('id','balance')->first();
                        if(isset($agent_data)){
                            $received_amount    = -1 * abs($previous_total_price);
                            $agent_balance      = $agent_data->balance - $previous_total_price;
                            DB::table('b2b_agents')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                            DB::table('agents_ledgers_new')->insert([
                                'b2b_Agent_id'  => $agent_data->id,
                                'received'      => $received_amount,
                                'balance'       => $agent_balance,
                                'invoice_no'    => $insert->id,
                                'customer_id'   => $insert->customer_id,
                                'date'          => date('Y-m-d'),
                                'remarks'       => 'Invoice Updated',
                            ]);
                        }
                        
                        $agent_data = DB::table('b2b_agents')->where('id',$new_b2b_Agent)->select('id','balance')->first();
                        if(isset($agent_data)){
                            $received_amount    = $new_total_price;
                            $agent_balance      = $agent_data->balance + $new_total_price;
                            DB::table('b2b_agents')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                            DB::table('agents_ledgers_new')->insert([
                                'b2b_Agent_id'  => $agent_data->id,
                                'received'      => $received_amount,
                                'balance'       => $agent_balance,
                                'invoice_no'    => $insert->id,
                                'customer_id'   => $insert->customer_id,
                                'date'          => date('Y-m-d'),
                                'remarks'       => 'Invoice Updated',
                            ]);
                        }
                    }else{
                        $difference  = $new_total_price - $previous_total_price;
                        $agent_data = DB::table('b2b_agents')->where('id',$new_b2b_Agent)->select('id','balance')->first();
                        if(isset($agent_data)){
                            $agent_balance = $agent_data->balance + $difference;
                            DB::table('b2b_agents')->where('id',$agent_data->id)->update(['balance'=>$agent_balance]);
                            DB::table('agents_ledgers_new')->insert([
                                'b2b_Agent_id'  => $agent_data->id,
                                'received'      => $difference,
                                'balance'       => $agent_balance,
                                'invoice_no'    => $insert->id,
                                'customer_id'   => $insert->customer_id,
                                'date'          => date('Y-m-d'),
                                'remarks'       => 'Invoice Updated',
                            ]);
                        } 
                    }
                    // B2B Agents
                    
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
                    DB::table('rooms_bookings_details')->where('booking_id', "$insert->id")->where('booking_from','Invoices')->delete();
                    
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
                                            
                                        // DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                                        //     'balance'=>$supplier_balance,
                                        //     'payable'=>$supplier_payable_balance
                                        //     ]);

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
                                                
                                            // DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                                            //     'balance'=>$supplier_balance,
                                            //     'payable'=>$supplier_payable_balance
                                            //     ]);
    
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
                                        
                                        // DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                                        //     'balance'=>$supplier_balance,
                                        //     'payable'=>$supplier_payable_balance
                                        //     ]);
                                    
                                    
                                    }
                                }
                            }
                        }
                    }
                    
                    // dd($new_acc_more);
                    
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
                                    $room_data      = DB::table('rooms')->where('id',$new_acc_res->more_hotelRoom_type_id)->first();
                                    $update_booked  = (int)$room_data->booked + (int)$new_acc_res->more_acc_qty;
                                    $room_update    = DB::table('rooms')->where('id',$new_acc_res->more_hotelRoom_type_id)->update([
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
                                        
                                        $week_days_total        = 0;
                                        $week_end_days_totals   = 0;
                                        $total_price            = 0;
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
                                        
                                        // DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update([
                                        //     'balance'=>$supplier_balance,
                                        //     'payable'=>$supplier_payable_balance
                                        // ]);
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
                                    
                                    if($route_obj != null){
                                        // Calaculate Child Prev Price Differ
                                        $child_price_wi_adult_price_prev = $route_obj->flights_per_person_price * $flight_prev_res->flights_adult_seats;
                                        $child_price_wi_child_price_prev = $route_obj->flights_per_child_price * $flight_prev_res->flights_child_seats;
                                        
                                        $price_diff_prev = $child_price_wi_adult_price_prev - $child_price_wi_child_price_prev;
                                        
                                        // Calaculate Child New Price Differ
                                        $child_price_wi_adult_price_new = $route_obj->flights_per_person_price * $flight_new_res->flights_adult_seats;
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
                            }
                            
                            // If element Not Found in New
                            if(!$ele_found){
                                $route_obj = DB::table('flight_rute')->where('id',$flight_prev_res->flight_route_id_occupied)->first();
                                    // print_r($route_obj);
                                    // die;
                                if($route_obj != null){
                                    // Calaculate Child Prev Price Differ
                                    $child_price_wi_adult_price_prev    = $route_obj->flights_per_person_price * $flight_prev_res->flights_adult_seats;
                                    $child_price_wi_child_price_prev    = $route_obj->flights_per_child_price * $flight_prev_res->flights_child_seats;
                                    $price_diff_prev                    = $child_price_wi_adult_price_prev - $child_price_wi_child_price_prev;
                                    // Calaculate Infant Prev Price
                                    $infant_price_prev                  = $route_obj->flights_per_infant_price * $flight_prev_res->flights_infant_seats;
                                    
                                    // New
                                    $total_Balance                      = $child_price_wi_adult_price_prev + $child_price_wi_child_price_prev + $infant_price_prev;
                                    // New
                                    
                                    $supplier_data                      = DB::table('supplier')->where('id',$route_obj->dep_supplier)->first();
                                    
                                    if($price_diff_prev != 0 || $infant_price_prev != 0){
                                        $supplier_balance               = $supplier_data->balance + $price_diff_prev;
                                        $supplier_balance               = $supplier_balance - $infant_price_prev;
                                        $total_differ                   = $price_diff_prev - $infant_price_prev;
                                        
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
                                        // DB::table('supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                                    }
                                    
                                    // New
                                    $supplier_balance_New = $supplier_data->balance - $total_Balance;
                                    DB::table('supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance_New]);
                                    // New
                                }
                            }
                        }
                    }
                    
                    // 2 Loop on New 
                    if(isset($new_flight_pax)){
                        foreach($new_flight_pax as $flight_new_res){
                            $pre_el_found = false;
                            // dd($prev_flight_pax);
                            if($prev_flight_pax != null && $prev_flight_pax != ''){
                                foreach($prev_flight_pax as $flight_prev_res){
                                    if($flight_prev_res->flight_route_id_occupied == $flight_new_res->flight_route_id_occupied){
                                        $pre_el_found = true;
                                    }
                                }
                            }
                            
                            // If element Not Found in Prev
                            if(!$pre_el_found){
                                $route_obj = DB::table('flight_rute')->where('id',$flight_new_res->flight_route_id_occupied)->first();
                                if($route_obj != null){
                                    // New
                                    $flights_per_person_price           = $route_obj->flights_per_person_price ?? 0;
                                    $flights_per_child_price            = $route_obj->flights_per_child_price ?? 0;
                                    $flights_per_infant_price           = $route_obj->flights_per_infant_price ?? 0;
                                    // New
                                    
                                    // Calaculate Child Prev Price Differ
                                    $child_price_wi_adult_price_prev    = $flights_per_person_price * $flight_new_res->flights_adult_seats;
                                    $child_price_wi_child_price_prev    = $flights_per_child_price * $flight_new_res->flights_child_seats;
                                    $price_diff_prev                    = $child_price_wi_adult_price_prev - $child_price_wi_child_price_prev;
                                    // Calaculate Infant Prev Price
                                    $infant_price_prev                  = $flights_per_infant_price * $flight_new_res->flights_infant_seats;
                                    
                                    // New
                                    $total_Balance                      = $child_price_wi_adult_price_prev + $child_price_wi_child_price_prev + $infant_price_prev;
                                    // New
                                    
                                    $supplier_data                      = DB::table('supplier')->where('id',$route_obj->dep_supplier)->first();
                                    
                                    if($price_diff_prev != 0 || $infant_price_prev != 0){
                                        $supplier_balance               = $supplier_data->balance - $price_diff_prev;
                                        $supplier_balance               = $supplier_balance + $infant_price_prev;
                                        $total_differ                   = $infant_price_prev - $price_diff_prev;
                                        
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
                                        // DB::table('supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance]);
                                    }
                                    
                                    // New
                                    $supplier_balance_New = $supplier_data->balance + $total_Balance;
                                    DB::table('supplier')->where('id',$supplier_data->id)->update(['balance'=>$supplier_balance_New]);
                                    // New
                                }
                            }
                        }
                    }
                    
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
                        
                        $req->transfer_supplier_id = (int)$req->transfer_supplier_id;
                        // Update Supplier Balance
                        $transfer_sup_data = DB::table('transfer_Invoice_Supplier')->where('id',$req->transfer_supplier_id)->select('id','balance')->first();
                        
                        if(isset($transfer_sup_data)){
                            $trans_sup_balance = $transfer_sup_data->balance + $price_diff;
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
                                    
                                    $trans_sup_balance = (float)$trans_sup_balance;
                                    
                                $update_result = DB::table('transfer_Invoice_Supplier')->where('id',$req->transfer_supplier_id)->update(['balance'=>$trans_sup_balance]);
                              
                            }
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
                                             
                                    if(isset($transfer_sup_data)){               
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
                                            DB::table('transfer_Invoice_Supplier')->where('id',$transfer_sup_data->id)->update(['balance'=>$trans_sup_balance]);
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
                                    
                                    // echo $transfer_new_total;
                                    // die;
                                      // Update Supplier Balance
                                     $transfer_new_total = (float)$transfer_new_total;
                                    $transfer_sup_data = DB::table('transfer_Invoice_Supplier')->where('id',$new_transfer_sup)->select('id','balance')->first();
                                    
                                    if(isset($transfer_sup_data)){                        
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
                            
                        
                    }
                 
                    DB::commit();
                 
            } catch (Throwable $e) {
                echo $e;
                DB::rollback();
                return response()->json(['message'=>'error','booking_id'=> '']);
            }
            return response()->json(['status'=>'success','invoice_id'=>$req->id,'message'=>'Invoice Updated Succesfully']); 
        }else{
            return response()->json(['status'=>'error','message'=>$id]); 
        }
    }
    
    public function cancellation_policy_invoice(Request $req){
        DB::beginTransaction();
        try {
            $insert = addManageInvoice::find($req->invoice_Id);
            if($insert){
                $pre_Sale_Price             = 0;
                $new_Sale_Price             = 0;
                $total_Cancellation_Price   = 0;
                if($req->cancellation_accomodation_details != ''){
                    // $insert->cancellation_accomodation_details      = $req->cancellation_accomodation_details ?? '';
                    
                    $cancellation_accomodation_details              = json_decode($req->cancellation_accomodation_details);
                    foreach($cancellation_accomodation_details as $val_CAD){
                        $pre_Sale_Price += $val_CAD->markup_price * $val_CAD->acc_hotel_Quantity;
                        $new_Sale_Price += $val_CAD->price_For_Remainig_Nights * $val_CAD->acc_hotel_Quantity;
                    }
                    // dd($insert,$cancellation_more_accomodation_details);
                }
                
                if($req->cancellation_more_accomodation_details != ''){
                    // $insert->cancellation_more_accomodation_details = $req->cancellation_more_accomodation_details ?? '';
                    
                    $cancellation_more_accomodation_details         = json_decode($req->cancellation_more_accomodation_details);
                    foreach($cancellation_more_accomodation_details as $val_MCAD){
                        $pre_Sale_Price += $val_MCAD->more_markup_price * $val_MCAD->more_acc_hotel_Quantity;
                        $new_Sale_Price += $val_MCAD->more_price_For_Remainig_Nights * $val_MCAD->more_acc_hotel_Quantity;
                        
                    }
                    // dd($cancellation_more_accomodation_details);
                }
                
                if($new_Sale_Price > 0 && $pre_Sale_Price > 0){
                    $total_Cancellation_Price = $pre_Sale_Price - $new_Sale_Price;
                }
                
                // dd($pre_Sale_Price,$new_Sale_Price,$total_Cancellation_Price);
                
                $insert->cancellation_Status            = 'Cancelled';
                
                $insert->cancellation_Price             = $req->$total_Cancellation_Price;
                $insert->prev_cancellation_Sale_Price   = $req->$pre_Sale_Price;
                
                $insert->total_sale_price_AC            = $req->$new_Sale_Price;
                $insert->total_sale_price_Company       = $req->$new_Sale_Price;
                $insert->total_sale_price_all_Services  = $req->$new_Sale_Price;
                
                // cancellation_Price
                // new_cancellation_Sale_Price
                
                // dd('Stop');
                
                // $insert->update();
                
                DB::commit();
                return response()->json(['status'=>'success','message'=>'Invoice Cancel Succesfully!']); 
            }
        } catch (Throwable $e) {
            echo $e;
            DB::rollback();
            return response()->json(['status'=>'error','message'=> 'Something Went Wrong!']);
        }
    }
    // Invoices
}