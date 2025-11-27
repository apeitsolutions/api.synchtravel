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

class TransferCompanyController extends Controller
{
    public function view_transfer_company(Request $request){
        $customer_id = $request->customer_id;
        $data=DB::table('transfer_Invoice_Company')->where('customer_id',$customer_id)->get();
        return response()->json(['data'=>$data]);
    }
   
    public function transfer_company_stats(Request $request){
        // print_r($request->all());
         $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            
            function dateDiffInDays($date1, $date2){
                $diff = strtotime($date2) - strtotime($date1);
                return abs(round($diff / 86400));
            }
            
            function getBetweenDates($startDate, $endDate){
                $rangArray = [];
                    
                $startDate = strtotime($startDate);
                $endDate = strtotime($endDate);
                     
                for ($currentDate = $startDate; $currentDate <= $endDate; 
                                                $currentDate += (86400)) {
                                                        
                    $date = date('Y-m-d', $currentDate);
                    $rangArray[] = $date;
                }
          
                return $rangArray;
            }
 



              $suppliers = DB::table('transfer_Invoice_Company')
                                ->where('customer_id',$userData->id)->get();
              $all_rooms_types = DB::table('rooms_types')
                                ->where('customer_id',$userData->id)->get();
              $all_suppliers_data = [];
              foreach($suppliers as $sup_res){
                  
                  
                  
                  $supplier_total_rooms = 0;
                  $supplier_total_rooms_booked = 0;
                  $supplier_total_cost = 0;
                  $supplier_total_payable = 0;
                  $supplier_total_paid = 0;
                

                  $room_records = [];
                  $rooms_types_arr = [];
                  
                  foreach($all_rooms_types as $room_type_res){
                      $rooms_types_count = 0;
                      $rooms_types_count_booked = 0;
                      $suppliers_rooms = DB::table('rooms')
                                        ->where('room_supplier_name',$sup_res->id)
                                        ->where('room_type_cat',$room_type_res->id)
                                        ->orWhere(function($query) use($sup_res,$room_type_res) {
                                            $query->whereJsonContains('more_room_type_details',['more_room_supplier_name'=>"$sup_res->id",'more_room_type_id'=> $room_type_res->id]);
                                         })
                                        
                                        ->get();
                        // echo "supplier id is ".$sup_res->id." room type id is ".$room_type_res->id." Room name is ".$room_type_res->room_type."  \n";
                        // print_r($suppliers_rooms);
                        
                                  foreach($suppliers_rooms as $room_res){
                                      $hotel_name = DB::table('hotels')->where('id',$room_res->hotel_id)->select('property_name','property_city')->first();
                                       
                                        //  echo "supplier id is ".$sup_res->id."⧵n";
                                      if($room_res->room_supplier_name == $sup_res->id && $room_res->room_type_cat == $room_type_res->id){
                                        //   echo "The supplier id is $sup_res->id and room sup id is ".$room_res->room_supplier_name;
                                         $supplier_total_rooms += $room_res->quantity;
                                         $rooms_types_count += $room_res->quantity;
                                         $rooms_types_count_booked += $room_res->booked;
                                         $supplier_total_rooms_booked += $room_res->booked;
                                     
                                          
                                          
                                          
                                        
                                        
                                        // Calculate Price 
                                        
                                         $week_days_total = 0;
                                         $week_end_days_totals = 0;
                                         $total_price = 0;
                                        if($room_res->price_week_type == 'for_all_days'){
                                            $avaiable_days = dateDiffInDays($room_res->availible_from, $room_res->availible_to);
                                            $total_price = $room_res->price_all_days * $avaiable_days;
                                        }else{
                                             $avaiable_days = dateDiffInDays($room_res->availible_from, $room_res->availible_to);
                                             
                                             
                                             $all_days = getBetweenDates($room_res->availible_from, $room_res->availible_to);
                                             $week_days = json_decode($room_res->weekdays);
                                             $week_end_days = json_decode($room_res->weekends);
                                             
                                            
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
                                                     $week_days_total += $room_res->weekdays_price;
                                                 }else{
                                                     $week_end_days_totals += $room_res->weekends_price;
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
                                      
                                      
                                             $Invoices_cost_SAR = 0;
                                             $Invoices_cost_GBP = 0;
                                             $website_booking_cost = 0;
                                            $purchase_currency = '';
                                            $sale_currency = '';
                                            if(isset($room_res->booking_details) && !empty($room_res->booking_details)){
                                                $booking_details = json_decode($room_res->booking_details);
                                                
                                                foreach($booking_details as $book_res){
                                                    if($book_res->booking_from == 'website'){
                                                        $website_booking = DB::table('hotel_booking')->where('search_id',$book_res->booking_id)->select('payment_status','recieved_amount','amount_paid','total_price')->first();
                                                        $website_booking_cost += $website_booking->total_price;
                                                        // print_r($website_booking);
                                                    }
                                                    
                                                    
                                                   
                                                    if($book_res->booking_from == 'Invoices'){
                                                        $website_booking = DB::table('add_manage_invoices')->where('id',$book_res->booking_id)
                                                        ->select('currency_conversion','conversion_type_Id','accomodation_details_more','accomodation_details','markup_details','more_markup_details')->first();
                                                        
                                                        $conversion_data = DB::table('mange_currencies')->where('id',$website_booking->conversion_type_Id)->first();
                                                        $purchase_currency = $conversion_data->purchase_currency;
                                                        $sale_currency =  $conversion_data->sale_currency;
                                                        $accomodation_detials = json_decode($website_booking->accomodation_details);
                                                        $accomodation_detials_more = json_decode($website_booking->accomodation_details_more);
                                                        
                                                        if(isset($accomodation_detials)){
                                                            foreach($accomodation_detials as $acc_res){
                                                                if($sup_res->id == $acc_res->hotel_supplier_id && $room_type_res->id == $acc_res->hotel_type_id && $acc_res->hotelRoom_type_idM == ""){
                                                                    // print_r($acc_res);
                                                                    $single_inv_cost = $acc_res->acc_total_amount * $acc_res->acc_qty;
                                                                    $Invoices_cost_GBP += $single_inv_cost;
                                                                    
                                                                    
                                                                    if($conversion_data->conversion_type == 'Divided'){
                                                                        $single_inv_cost_SAR = $single_inv_cost * $acc_res->exchange_rate_price;
                                                                    }else{
                                                                        $single_inv_cost_SAR = $single_inv_cost / $acc_res->exchange_rate_price;
                                                                    }
                                                                    
                                                                    $Invoices_cost_SAR += $single_inv_cost_SAR;
                                                                    // echo " The total cost is ".$Invoices_cost_GBP." SAR Cost $Invoices_cost_SAR Single is $single_inv_cost ";
                                                            
                                                                 }
                                                            }
                                                        }
                                                        
                                                        if(isset($accomodation_detials_more)){
                                                            foreach($accomodation_detials_more as $acc_more_res){
                                                                if($sup_res->id == $acc_more_res->more_hotel_supplier_id  && $room_type_res->id == $acc_more_res->more_hotel_type_id && $acc_more_res->more_hotelRoom_type_idM == ""){
                                                                    // print_r($acc_more_res);
                                                                $single_inv_cost = $acc_more_res->more_acc_total_amount * $acc_more_res->more_acc_qty;
                                                                $Invoices_cost_GBP += $single_inv_cost;
                                                                
                                                                
                                                                  if($conversion_data->conversion_type == 'Divided'){
                                                                        $single_inv_cost_SAR = $single_inv_cost * $acc_more_res->more_exchange_rate_price;
                                                                    }else{
                                                                        $single_inv_cost_SAR = $single_inv_cost / $acc_more_res->more_exchange_rate_price;
                                                                    }
                                                                    
                                                                
                                                                $Invoices_cost_SAR += $single_inv_cost_SAR;
                                                                // echo " The total cost is ".$Invoices_cost_GBP." SAR Cost $Invoices_cost_SAR Single is $single_inv_cost ";
                                                            
                                                                }
                                                            }
                                                        }
                                                        
                                                       
                                                        // print_r($accomodation_detials);
                                                        // print_r($accomodation_detials_more);
                                                    
                                                        
                                                        // echo "This is Next ";
                                                    }
                                                    
                                                     if($book_res->booking_from == 'package'){
                                                          $website_booking = DB::table('tours')->where('id',$book_res->package_id)
                                                          ->select('currency_conversion','conversion_type_Id','accomodation_details_more','accomodation_details')->first();
                                                          
                                                          $conversion_data = DB::table('mange_currencies')->where('id',$website_booking->conversion_type_Id)->first();
                                                          $purchase_currency = $conversion_data->purchase_currency;
                                                          $sale_currency = $conversion_data->sale_currency;
                                                    
                                                          $accomodation_detials = json_decode($website_booking->accomodation_details);
                                                          $accomodation_detials_more = json_decode($website_booking->accomodation_details_more);
                                                          
                                                          if(isset($accomodation_detials)){
                                                              foreach($accomodation_detials as $acc_res){
                                                                 if($sup_res->id == $acc_res->hotel_supplier_id && $room_type_res->id == $acc_res->hotel_type_id && $acc_res->hotelRoom_type_idM == ""){
                                                                      // print_r($acc_res);
                                                                      $single_inv_cost = ($acc_res->price_per_room_sale * $acc_res->acc_no_of_nightst) * $book_res->quantity;
                                                                      $Invoices_cost_GBP += $single_inv_cost;
                                                                      
                                                                      if($conversion_data->conversion_type == 'Divided'){
                                                                          $single_inv_cost_SAR = $single_inv_cost * $acc_res->exchange_rate_price;
                                                                      }else{
                                                                          $single_inv_cost_SAR = $single_inv_cost / $acc_res->exchange_rate_price;
                                                                      }
                                                    
                                                                      
                                                                      $Invoices_cost_SAR += $single_inv_cost_SAR;
                                                                      // echo " The total cost is ".$Invoices_cost_GBP." SAR Cost $Invoices_cost_SAR Single is $single_inv_cost ";
                                                              
                                                                    
                                                                   }
                                                              }
                                                          }
                                                          
                                                          if(isset($accomodation_detials_more)){
                                                              foreach($accomodation_detials_more as $acc_more_res){
                                                                  if($sup_res->id == $acc_more_res->more_hotel_supplier_id  && $room_type_res->id == $acc_more_res->more_hotel_type_id && $acc_more_res->more_hotelRoom_type_idM == ""){
                                                                      // print_r($acc_more_res);
                                                                  $single_inv_cost = ($acc_more_res->more_price_per_room_sale * $acc_more_res->more_acc_no_of_nightst) * $book_res->quantity;
                                                                  $Invoices_cost_GBP += $single_inv_cost;
                                                                  
                                                                   if($conversion_data->conversion_type == 'Divided'){
                                                                      $single_inv_cost_SAR = $single_inv_cost * $acc_more_res->more_exchange_rate_price;
                                                                  }else{
                                                                      $single_inv_cost_SAR = $single_inv_cost / $acc_more_res->more_exchange_rate_price;
                                                                  }
                                                                      
                                                                  
                                                                  $Invoices_cost_SAR += $single_inv_cost_SAR;
                                                                  // echo " The total cost is ".$Invoices_cost_GBP." SAR Cost $Invoices_cost_SAR Single is $single_inv_cost ";
                                                              
                                                                     
                                                                  }
                                                              }
                                                          }
                                                          
                                                    
                                                         
                                                      }
                                                    
                                                    
                                                }
                                                
                                            }
                                            
                                            
                                             
                                      
                                            $room_record_single = ['room_id'=>$room_res->id,
                                                             'paid_amount'=>$room_res->paid_amount,
                                                             'over_paid_amount'=>$room_res->over_paid,
                                                             'room_supplier_id'=>$sup_res->id,
                                                             'room_supplier_name'=>$sup_res->room_supplier_name,
                                                             'wallat_balance'=>$sup_res->wallat_balance,
                                                             'hotel_id'=>$room_res->hotel_id,
                                                             'hotel_name'=>$hotel_name->property_name,
                                                             'hotel_city'=>$hotel_name->property_city,
                                                             'hotel_id'=>$room_res->hotel_id,
                                                             'room_type'=>$room_res->room_type_id,
                                                             'room_generate_id'=>null,
                                                             'room_type_id'=>$room_res->room_type_cat,
                                                             'room_type_name'=>$room_type_res->room_type,
                                                             'quantity'=>$room_res->quantity,
                                                             'booked'=>$room_res->booked,
                                                             'booked_details'=>$room_res->booking_details,
                                                             'purchase_currency'=>$purchase_currency,
                                                             'sale_currency'=>$sale_currency,
                                                             'total_cost_purchase'=>$Invoices_cost_SAR + $website_booking_cost,
                                                             'total_cost_convert'=>$Invoices_cost_GBP,
                                                             'website_booking_cost'=>$website_booking_cost,
                                                             'booked_details'=>$room_res->booking_details,
                                                             'availible_from'=>$room_res->availible_from,
                                                             'availible_to'=>$room_res->availible_to,
                                                             'avaiable_days'=>$avaiable_days,
                                                             'price_week_type'=>$room_res->price_week_type,
                                                             'price_all_days'=>$room_res->price_all_days,
                                                             'weekdays'=>$room_res->weekdays,
                                                             'weekdays_price'=>$room_res->weekdays_price,
                                                             'weekends'=>$room_res->weekends,
                                                             'weekends_price'=>$room_res->weekends_price,
                                                             'weekdays_total'=>$week_days_total,
                                                             'weekends_total'=>$week_end_days_totals,
                                                             'single_room_price'=>$total_price,
                                                             'all_room_price'=>$total_price * $room_res->quantity
                                                             ];
                                                             
                                                             $supplier_total_paid += $room_res->paid_amount;
                                            $supplier_total_cost += $total_price * $room_res->quantity;
                                            $supplier_total_payable += ($Invoices_cost_SAR + $website_booking_cost);
                                             array_push($room_records,$room_record_single);
                                      }
                                      
                                      
                                     
                                                             
                                       
                                      
                                      if($room_res->more_room_type_details != null && $room_res->more_room_type_details !='null' && $room_res->more_room_type_details != '')
                                      {
                                          
                                          $more_rooms_data = json_decode($room_res->more_room_type_details);
                                         
                                          
                                          foreach($more_rooms_data as $more_room_res){
                                                    // print_r($more_room_res);
                                                 if($more_room_res->more_room_supplier_name == $sup_res->id && $more_room_res->more_room_type_id == $room_type_res->id){
                                                      $supplier_total_rooms += $more_room_res->more_quantity;
                                                      $booked_rooms = 0;
                                                      $booked_details = '';
                                                      if(isset($more_room_res->more_quantity_booked)){
                                                          $supplier_total_rooms_booked += $more_room_res->more_quantity_booked;
                                                          $booked_rooms = $more_room_res->more_quantity_booked;
                                                          
                                                          $booked_details = $more_room_res->more_booking_details;
                                                          
                                                          $rooms_types_count_booked += $more_room_res->more_quantity_booked;
                                                      }
                                                       
                                                     
                                                          $rooms_types_count += $more_room_res->more_quantity;
                                                    
                                                      
                                                            $week_days_total = 0;
                                                             $week_end_days_totals = 0;
                                                             $total_price = 0;
                                                            if($more_room_res->more_week_price_type == 'for_all_days'){
                                                                $avaiable_days = dateDiffInDays($more_room_res->more_room_av_from, $more_room_res->more_room_av_to);
                                                                $total_price = $more_room_res->more_price_all_days * $avaiable_days;
                                                            }else{
                                                                 $avaiable_days = dateDiffInDays($more_room_res->more_room_av_from, $more_room_res->more_room_av_to);
                                                                 
                                                                 
                                                                 $all_days = getBetweenDates($more_room_res->more_room_av_from, $more_room_res->more_room_av_to);
                                                                 $week_days = json_decode($more_room_res->more_weekdays);
                                                                 $week_end_days = json_decode($more_room_res->more_weekend);
                                                                 
                                                                //  print_r($all_days);
                                                                
                                                                 foreach($all_days as $day_res){
                                                                     
                                                                     $day = date('l', strtotime($day_res));
                                                                    //  echo "day is $day  ⧵n";
                                                                     $day = trim($day);
                                                                     $week_day_found = false;
                                                                     $week_end_day_found = false;
                                                                    
                                                                     foreach($week_days as $week_day_res){
                                                                        //  echo $week_day_res;
                                                                         if($week_day_res == $day){
                                                                             $week_day_found = true;
                                                                         }
                                                                     }
                                                              
                                                                     if($week_day_found){
                                                                         $week_days_total += $more_room_res->more_week_days_price;
                                                                     }else{
                                                                         $week_end_days_totals += $more_room_res->more_week_end_price;
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
                                                            
                                                            $Invoices_cost_SAR = 0;
                                                             $Invoices_cost_GBP = 0;
                                                             $website_booking_cost = 0;
                                                            $purchase_currency = '';
                                                            $sale_currency = '';
                                                            
                                                        if(isset($more_room_res->more_booking_details) && !empty($more_room_res->more_booking_details)){
                                                            $booking_details = json_decode($more_room_res->more_booking_details);
                                                            
                                                            foreach($booking_details as $book_res){
                                                                if($book_res->booking_from == 'website'){
                                                                    $website_booking = DB::table('hotel_booking')->where('search_id',$book_res->booking_id)->select('payment_status','recieved_amount','amount_paid','total_price')->first();
                                                                    $website_booking_cost += $website_booking->total_price;
                                                                    // print_r($website_booking);
                                                                }
                                                                
                                                                
                                                               
                                                                if($book_res->booking_from == 'Invoices'){
                                                                    // echo " room id is ".$room_res->id." room generate id is $more_room_res->room_gen_id "."<br>";
                                                                    // echo "Invoice is Booked form more supplier id ".$sup_res->id;
                                                                    $website_booking = DB::table('add_manage_invoices')->where('id',$book_res->booking_id)
                                                                    ->select('currency_conversion','conversion_type_Id','accomodation_details_more','accomodation_details','markup_details','more_markup_details')->first();
                                                                    // print_r($website_booking);
                                                                    
                                                                    $conversion_data = DB::table('mange_currencies')->where('id',$website_booking->conversion_type_Id)->first();
                                                                    
                                                                    $purchase_currency = $conversion_data->purchase_currency;
                                                                    $sale_currency =  $conversion_data->sale_currency;
                                                                    
                                                                    $accomodation_detials = json_decode($website_booking->accomodation_details);
                                                                    $accomodation_detials_more = json_decode($website_booking->accomodation_details_more);
                                                                    
                                                                    if(isset($accomodation_detials)){
                                                                        
                                                                        foreach($accomodation_detials as $acc_res){
                                                                            // print_r($acc_res);
                                                                            if($sup_res->id == $acc_res->hotel_supplier_id  && $room_type_res->id == $acc_res->hotel_type_id && $more_room_res->room_gen_id == $acc_res->hotelRoom_type_idM){
                                                                                // echo "from more ";
                                                                                // print_r($acc_res);
                                                                                $single_inv_cost = $acc_res->acc_total_amount * $acc_res->acc_qty;
                                                                                $Invoices_cost_GBP += $single_inv_cost;
                                                                                
                                                                                if($conversion_data->conversion_type == 'Divided'){
                                                                                    $single_inv_cost_SAR = $single_inv_cost * $acc_res->exchange_rate_price;
                                                                                }else{
                                                                                    $single_inv_cost_SAR = $single_inv_cost / $acc_res->exchange_rate_price;
                                                                                }

                                                                                
                                                                                $Invoices_cost_SAR += $single_inv_cost_SAR;
                                                                                // echo " The total cost is ".$Invoices_cost_GBP." SAR Cost $Invoices_cost_SAR Single is $single_inv_cost ";
                                                                            }
                                                                        }
                                                                    }
                                                                    
                                                                    if(isset($accomodation_detials_more)){
                                                                        foreach($accomodation_detials_more as $acc_more_res){
                                                                            if($sup_res->id == $acc_more_res->more_hotel_supplier_id && $room_type_res->id == $acc_more_res->more_hotel_type_id && $more_room_res->room_gen_id == $acc_more_res->more_hotelRoom_type_idM){
                                                                                // echo "from more ";
                                                                                //       print_r($acc_more_res);
                                                                            $single_inv_cost = $acc_more_res->more_acc_total_amount * $acc_more_res->more_acc_qty;
                                                                            $Invoices_cost_GBP += $single_inv_cost;
                                                                            
                                                                            if($conversion_data->conversion_type == 'Divided'){
                                                                                $single_inv_cost_SAR = $single_inv_cost * $acc_more_res->more_exchange_rate_price;
                                                                            }else{
                                                                                $single_inv_cost_SAR = $single_inv_cost /  $acc_more_res->more_exchange_rate_price;
                                                                            }
                                                                            
                                                                            
                                                                            $Invoices_cost_SAR += $single_inv_cost_SAR;
                                                                            // echo " The total cost is ".$Invoices_cost_GBP." SAR Cost $Invoices_cost_SAR Single is $single_inv_cost ";
                                                                        
                                                                            }
                                                                        }
                                                                    }
                                                                    
                                                                   
                                                                    // print_r($accomodation_detials);
                                                                    // print_r($accomodation_detials_more);
                                                                
                                                                    
                                                                    // echo "This is Next ";
                                                                }
                                                                
                                                                if($book_res->booking_from == 'package'){
                                                                    
                                                                        
                                                                      $website_booking = DB::table('tours')->where('id',$book_res->package_id)
                                                                      ->select('currency_conversion','conversion_type_Id','accomodation_details_more','accomodation_details')->first();
                                                                      
                                                                      $conversion_data = DB::table('mange_currencies')->where('id',$website_booking->conversion_type_Id)->first();
                                                                      $purchase_currency = $conversion_data->purchase_currency;
                                                                      $sale_currency = $conversion_data->sale_currency;
                                                                
                                                                      $accomodation_detials = json_decode($website_booking->accomodation_details);
                                                                      $accomodation_detials_more = json_decode($website_booking->accomodation_details_more);
                                                                      
                                                                      if(isset($accomodation_detials)){
                                                                          foreach($accomodation_detials as $acc_res){
                                                                             if($sup_res->id == $acc_res->hotel_supplier_id  && $room_type_res->id == $acc_res->hotel_type_id && $more_room_res->room_gen_id == $acc_res->hotelRoom_type_idM){
                                                                                  // print_r($acc_res);
                                                                                  $single_inv_cost = ($acc_res->price_per_room_sale * $acc_res->acc_no_of_nightst) * $book_res->quantity;
                                                                                  $Invoices_cost_GBP += $single_inv_cost;
                                                                                  
                                                                                  if($conversion_data->conversion_type == 'Divided'){
                                                                                      $single_inv_cost_SAR = $single_inv_cost * $acc_res->exchange_rate_price;
                                                                                  }else{
                                                                                      $single_inv_cost_SAR = $single_inv_cost / $acc_res->exchange_rate_price;
                                                                                  }
                                                                
                                                                                  
                                                                                  $Invoices_cost_SAR += $single_inv_cost_SAR;
                                                                                  // echo " The total cost is ".$Invoices_cost_GBP." SAR Cost $Invoices_cost_SAR Single is $single_inv_cost ";
                                                                          
                                                                                
                                                                               }
                                                                          }
                                                                      }
                                                                      
                                                                      if(isset($accomodation_detials_more)){
                                                                          foreach($accomodation_detials_more as $acc_more_res){
                                                                              if($sup_res->id == $acc_more_res->more_hotel_supplier_id && $room_type_res->id == $acc_more_res->more_hotel_type_id && $more_room_res->room_gen_id == $acc_more_res->more_hotelRoom_type_idM){
                                                                                  // print_r($acc_more_res);
                                                                              $single_inv_cost = ($acc_more_res->more_price_per_room_sale * $acc_more_res->more_acc_no_of_nightst) * $book_res->quantity;
                                                                              $Invoices_cost_GBP += $single_inv_cost;
                                                                              
                                                                               if($conversion_data->conversion_type == 'Divided'){
                                                                                  $single_inv_cost_SAR = $single_inv_cost * $acc_more_res->more_exchange_rate_price;
                                                                              }else{
                                                                                  $single_inv_cost_SAR = $single_inv_cost / $acc_more_res->more_exchange_rate_price;
                                                                              }
                                                                                  
                                                                              
                                                                              $Invoices_cost_SAR += $single_inv_cost_SAR;
                                                                              // echo " The total cost is ".$Invoices_cost_GBP." SAR Cost $Invoices_cost_SAR Single is $single_inv_cost ";
                                                                          
                                                                                 
                                                                              }
                                                                          }
                                                                      }
                                                                      
                                                                
                                                                     
                                                                  }
                                                                
                                                                
                                                            }
                                                            
                                                        }
                                                      
                                                      $paid_amount = 0;
                                                      if(isset($more_room_res->paid_amount)){
                                                          $paid_amount = $more_room_res->paid_amount;
                                                      }
                                                      
                                                       $over_paid_amount = 0;
                                                      if(isset($more_room_res->over_paid)){
                                                          $over_paid_amount = $more_room_res->over_paid;
                                                      }
                                              
                                                             
                                                      $room_record_single = ['room_id'=>$room_res->id,
                                                                             'paid_amount'=>$paid_amount,
                                                                             'over_paid_amount'=>$over_paid_amount,
                                                                             'hotel_id'=>$room_res->hotel_id,
                                                                             'room_supplier_name'=>$sup_res->room_supplier_name,
                                                                             'room_supplier_id'=>$sup_res->id,
                                                                             'wallat_balance'=>$sup_res->wallat_balance,
                                                                             'hotel_name'=>$hotel_name->property_name,
                                                                             'hotel_city'=>$hotel_name->property_city,
                                                                             'hotel_id'=>$room_res->hotel_id,
                                                                             'room_type'=>$more_room_res->more_room_type,
                                                                             'room_type_id'=>$more_room_res->more_room_type_id,
                                                                             'room_generate_id'=>$more_room_res->room_gen_id,
                                                                             'room_type_name'=>$room_type_res->room_type,
                                                                             'quantity'=>$more_room_res->more_quantity,
                                                                             'booked'=>$booked_rooms,
                                                                             'purchase_currency'=>$purchase_currency,
                                                                             'sale_currency'=>$sale_currency,
                                                                             'total_cost_purchase'=>$Invoices_cost_SAR + $website_booking_cost,
                                                                             'total_cost_convert'=>$Invoices_cost_GBP,
                                                                             'website_booking_cost'=>$website_booking_cost,
                                                                             'booked_details'=>$booked_details,
                                                                             'availible_from'=>$more_room_res->more_room_av_from,
                                                                             'availible_to'=>$more_room_res->more_room_av_to,
                                                                             'avaiable_days'=>$avaiable_days,
                                                                             'price_week_type'=>$more_room_res->more_week_price_type,
                                                                             'price_all_days'=>$more_room_res->more_price_all_days,
                                                                             'weekdays'=>$more_room_res->more_weekdays,
                                                                             'weekdays_price'=>$more_room_res->more_week_days_price,
                                                                             'weekends'=>$more_room_res->more_weekend,
                                                                             'weekends_price'=>$more_room_res->more_week_end_price,
                                                                             'weekdays_total'=>$week_days_total,
                                                                             'weekends_total'=>$week_end_days_totals,
                                                                             'single_room_price'=>$total_price,
                                                                             'all_room_price'=>$total_price * $more_room_res->more_quantity
                                                                             ];
                                                             
                                                             $supplier_total_paid += $paid_amount;
                                                             $supplier_total_cost += $total_price * $more_room_res->more_quantity;
                                                             $supplier_total_payable += ($Invoices_cost_SAR + $website_booking_cost);
                                                        array_push($room_records,$room_record_single);
                                                  }
                                                  
                
                                                      
                                      
                                          }
                                     
                                      }
                                  }
                                  
                                  $single_type_record = [
                                        'room_type_id'=>$room_type_res->id,
                                        'type_name'=>$room_type_res->room_type,
                                        'rooms_count'=>$rooms_types_count,
                                        'booked'=>$rooms_types_count_booked
                                      ];
                                      
                                 array_push($rooms_types_arr,$single_type_record);
                  }
                  
                //   print_r($suppliers_rooms);
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  $supplier_data = ['supplier_id'=>$sup_res->id,
                                    'supplier_name'=>$sup_res->room_supplier_name,
                                    'wallet_amount'=>$sup_res->wallat_balance,
                                    'total_rooms'=>$supplier_total_rooms,
                                    'total_booked'=>$supplier_total_rooms_booked,
                                    'supplier_total_cost' =>$supplier_total_cost,
                                    'supplier_total_payable'=>$supplier_total_payable,
                                    'total_paid'=>$supplier_total_paid,
                                    'rooms_details'=>$rooms_types_arr,
                                    'rooms_data'=>$room_records
                  ];
                  
                  array_push($all_suppliers_data,$supplier_data);
                //   
              }
              
              
              
            // print_r($all_suppliers_data);
        }
      
        
    // $customer_id = $request->customer_id;
    //   $data=DB::table('transfer_Invoice_Company')->where('customer_id',$customer_id)->get();
      

      return response()->json(['status'=>'success','data'=>$all_suppliers_data]);
        
        
   }
   
    public function add_transfer_company(Request $request){
        $countries=DB::table('countries')->get();
        return response()->json(['countries'=>$countries]);
    }
  
    public function submit_transfer_company(Request $request){
        $customer_id            = $request->customer_id;
        $room_supplier_name     = $request->room_supplier_name;
        $email                  = $request->email;
        $phone_no               = $request->phone_no;
        $address                = $request->address;
        $contact_person_name    = $request->contact_person_name;
        $country                = $request->country;
        $city                   = $request->city;
        $more_phone_no          = $request->more_phone_no;
        
        $data=DB::table('transfer_Invoice_Company')->insert([  
            'customer_id'           => $customer_id,
            'room_supplier_name'    => $room_supplier_name,
            'email'                 => $email,
            'phone_no'              => $phone_no,
            'address'               => $address,
            'contact_person_name'   => $contact_person_name,
            'country'               => $country,
            'city'                  => $city,
            'more_phone_no'         => $more_phone_no,
        ]);
       return response()->json(['Status'=>'SuccessFull','data'=>$data]);
    }
  
    public function edit_transfer_company(Request $request){
        $id=$request->id;
        $data=DB::table('transfer_Invoice_Company')->where('id',$id)->first();
      
        $countries=DB::table('countries')->get();
        return response()->json(['data'=>$data,'countries'=>$countries]);
        
        
    }
   
    public function submit_edit_transfer_company(Request $request){
        $id                     = $request->id;
        $customer_id            = $request->customer_id;
        $room_supplier_name     = $request->room_supplier_name;
        $email                  = $request->email;
        $phone_no               = $request->phone_no;
        $address                = $request->address;
        $contact_person_name    = $request->contact_person_name;
        $country                = $request->country;
        $city                   = $request->city;
        $more_phone_no          = $request->more_phone_no;
        $data=DB::table('transfer_Invoice_Company')->where('id',$id)->update([
            'customer_id'           => $customer_id,
            'room_supplier_name'    => $room_supplier_name,
            'email'                 => $email,
            'phone_no'              => $phone_no,
            'address'               => $address,
            'contact_person_name'   => $contact_person_name,
            'country'               => $country,
            'more_phone_no'         => $more_phone_no,
          
        ]);
      
       return response()->json(['Status'=>'SuccessFull','data'=>$data]);
    }
   
    public function delete_transfer_company(Request $request){
        $id=$request->id;
        $data=DB::table('transfer_Invoice_Company')->where('id',$id)->delete();
        return response()->json(['data'=>$data,'Status'=>'Successful']);
    }
}
