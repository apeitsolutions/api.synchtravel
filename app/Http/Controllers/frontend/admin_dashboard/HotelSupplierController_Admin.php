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

class HotelSupplierController_Admin extends Controller
{
    public function view_hotel_suppliers_Admin(Request $request){
        $all_Users  = DB::table('customer_subcriptions')->get();
        $data       = DB::table('rooms_Invoice_Supplier')->get();
        return view('template/frontend/userdashboard/pages/hotel_supplier/view_supplier',compact(['data','all_Users']));
    }
   
    public function hotel_suppliers_ledger(Request $request)
    {
       $supplier_detail  = DB::table('rooms_Invoice_Supplier')->where('id',$request->supplier_id)->first();
        $supplier_ledger_data  = DB::table('hotel_supplier_ledger')->where('supplier_id',$request->supplier_id)->get();
        
        return response()->json(['status'=>'success','ledger_data'=>$supplier_ledger_data,'supplier_Pers_details'=>$supplier_detail]);
        
        
   }
   
   
   
   public function hotel_supplier_stats(Request $request)
    {
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
 



              $suppliers = DB::table('rooms_Invoice_Supplier')
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
                                        // ->orWhere(function($query) use($sup_res,$room_type_res) {
                                        //     $query->whereJsonContains('more_room_type_details',['more_room_supplier_name'=>"$sup_res->id",'more_room_type_id'=> $room_type_res->id]);
                                        //  })
                                        ->get();
                        // echo "supplier id is ".$sup_res->id." room type id is ".$room_type_res->id." Room name is ".$room_type_res->room_type."  \n";
                        // print_r($suppliers_rooms);
                        // die;
                                  foreach($suppliers_rooms as $room_res){
                                    //   echo "The room id is ".$room_res->id;
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
                                            
                                            
                                            $rooms_booking_details = DB::table('rooms_bookings_details')->where('room_id',$room_res->id)->get();
                                            if(isset($rooms_booking_details) && !empty($rooms_booking_details)){
                                                $booking_details = $rooms_booking_details;
                                                
                                                foreach($booking_details as $book_res){
                                                    if($book_res->booking_from == 'website'){
                                                        $website_booking = DB::table('hotel_booking')->where('search_id',$book_res->booking_id)->select('payment_status','recieved_amount','amount_paid','total_price')->first();
                                                        $website_booking_cost += $website_booking->total_price;
                                                        // print_r($website_booking);
                                                    }
                                                    
                                                    
                                                   
                                                    if($book_res->booking_from == 'Invoices'){
                                                        $website_booking = DB::table('add_manage_invoices')->where('id',$book_res->booking_id)
                                                        ->select('currency_conversion','conversion_type_Id','accomodation_details_more','accomodation_details','markup_details','more_markup_details')->first();
                                                        
                                                        
                                                        if(isset($website_booking)){
                                                            $conversion_data = DB::table('mange_currencies')->where('id',$website_booking->conversion_type_Id)->first();
                                                            
                                                            if(isset($conversion_data)){
                                                                 $purchase_currency = $conversion_data->purchase_currency;
                                                                $sale_currency =  $conversion_data->sale_currency;
                                                                $conversion_type = $conversion_data->conversion_type;
                                                            }else{
                                                                 $purchase_currency = '';
                                                                 $sale_currency =  '';
                                                                 $conversion_type = 'Divided';
                                                            }
                                                           
                                                            $accomodation_detials = json_decode($website_booking->accomodation_details);
                                                            $accomodation_detials_more = json_decode($website_booking->accomodation_details_more);
                                                            
                                                            if(isset($accomodation_detials)){
                                                                foreach($accomodation_detials as $acc_res){
                                                                    if($sup_res->id == $acc_res->hotel_supplier_id && $room_type_res->id == $acc_res->hotel_type_id){
                                                                        // print_r($acc_res);
                                                                        $single_inv_cost = $acc_res->acc_total_amount * $acc_res->acc_qty;
                                                                        $Invoices_cost_GBP += $single_inv_cost;
                                                                        
                                                                        
                                                                        if($conversion_type == 'Divided'){
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
                                                                    if($sup_res->id == $acc_more_res->more_hotel_supplier_id  && $room_type_res->id == $acc_more_res->more_hotel_type_id){
                                                                        // print_r($acc_more_res);
                                                                    $single_inv_cost = $acc_more_res->more_acc_total_amount * $acc_more_res->more_acc_qty;
                                                                    $Invoices_cost_GBP += $single_inv_cost;
                                                                    
                                                                    
                                                                      if($conversion_type == 'Divided'){
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
                                                       
                                                        // print_r($accomodation_detials);
                                                        // print_r($accomodation_detials_more);
                                                    
                                                        
                                                        // echo "This is Next ";
                                                    }
                                                    
                                                     if($book_res->booking_from == 'package'){
                                                          $website_booking = DB::table('tours')->where('id',$book_res->package_id)
                                                          ->select('currency_conversion','conversion_type_Id','accomodation_details_more','accomodation_details')->first();
                                                          
                                                          if(isset($website_booking)){
                                                              $conversion_data = DB::table('mange_currencies')->where('id',$website_booking->conversion_type_Id)->first();
                                                              
                                                             if(isset($conversion_data)){
                                                                    $purchase_currency = $conversion_data->purchase_currency;
                                                                    $sale_currency =  $conversion_data->sale_currency;
                                                                    $conversion_type == $conversion_data->conversion_type;
                                                                }else{
                                                                    $purchase_currency = '';
                                                                    $sale_currency =  '';
                                                                    
                                                                    $conversion_type = 'Divided';
                                                                }
                                                              $accomodation_detials = json_decode($website_booking->accomodation_details);
                                                              $accomodation_detials_more = json_decode($website_booking->accomodation_details_more);
                                                              
                                                              
                                                            //   print_r($accomodation_detials);
                                                            //   die;
                                                              if(isset($accomodation_detials)){
                                                                  foreach($accomodation_detials as $acc_res){
                                                                      if(isset($acc_res->hotel_supplier_id)){
                                                                     if($sup_res->id == $acc_res->hotel_supplier_id && $room_type_res->id == $acc_res->hotel_type_id){
                                                                          // print_r($acc_res);
                                                                          $single_inv_cost = ($acc_res->price_per_room_sale * $acc_res->acc_no_of_nightst) * $book_res->quantity;
                                                                          $Invoices_cost_GBP += $single_inv_cost;
                                                                          
                                                                          if($conversion_type == 'Divided'){
                                                                              $single_inv_cost_SAR = $single_inv_cost * $acc_res->exchange_rate_price;
                                                                          }else{
                                                                              $single_inv_cost_SAR = $single_inv_cost / $acc_res->exchange_rate_price;
                                                                          }
                                                        
                                                                          
                                                                          $Invoices_cost_SAR += $single_inv_cost_SAR;
                                                                          // echo " The total cost is ".$Invoices_cost_GBP." SAR Cost $Invoices_cost_SAR Single is $single_inv_cost ";
                                                                  
                                                                        
                                                                       }
                                                                      }
                                                                  }
                                                              }
                                                              
                                                              if(isset($accomodation_detials_more)){
                                                                  foreach($accomodation_detials_more as $acc_more_res){
                                                                      
                                                                      if(isset($acc_res->hotel_supplier_id)){
                                                                      if($sup_res->id == $acc_more_res->more_hotel_supplier_id  && $room_type_res->id == $acc_more_res->more_hotel_type_id){
                                                                          // print_r($acc_more_res);
                                                                      $single_inv_cost = ($acc_more_res->more_price_per_room_sale * $acc_more_res->more_acc_no_of_nightst) * $book_res->quantity;
                                                                      $Invoices_cost_GBP += $single_inv_cost;
                                                                      
                                                                       if($conversion_type == 'Divided'){
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
                                                             'room_generate_id'=>$room_res->room_gen_id,
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
    //   $data=DB::table('rooms_Invoice_Supplier')->where('customer_id',$customer_id)->get();
      

      return response()->json(['status'=>'success','data'=>$all_suppliers_data]);
        
        
   }
   
   public function hotel_supplier_payments(Request $request){
    //   print_r($request->all());
    //   die;
       $request_data = json_decode($request->request_data);
         DB::beginTransaction();
                        try {
                            
                                $room_data = DB::table('rooms')->where('id',$request_data->room_id)->first();
                      
                                if($request_data->payment_type == 'payable_amount'){
                                    $total_amount = $request_data->total_amount_payable;
                                }else{
                                    $total_amount = $request_data->total_amount;
                                }
                                
                                if(!empty($request_data->room_id_generate) && $request_data->room_id_generate != null && $request_data->room_id_generate != 'null'){
                                    
                                    $rooms_more_data = json_decode($room_data->more_room_type_details);
                                    
                                     foreach($rooms_more_data as $key => $room_more_res){
                                        // echo "The room gen ".$room_more_res->room_gen_id." and request is ".$request->generate_id;
                                        if($room_more_res->room_gen_id == $request_data->room_id_generate){
                                            // print_r($room_more_res);
                                            
                                            
                                            $paid_amount = 0;
                                            $over_paid_amount = 0;
                                            if(isset($room_more_res->paid_amount)){
                                                $paid_amount = $room_more_res->paid_amount;
                                                $over_paid_amount = $room_more_res->over_paid;
                                            }
                                            
                                            $total_paid_amount = $paid_amount + $request_data->amount_paid;
                                            $total_over_paid = 0;
                                            $over_paid_am = 0;
                                            if($total_paid_amount > $total_amount){
                                                $over_paid_am = $total_paid_amount - $total_amount;
                                                $total_over_paid = $over_paid_amount + $over_paid_am;
                                                
                                                $total_paid_amount = $total_paid_amount - $over_paid_am;
                                            }
                                            
                                            $rooms_more_data[$key]->paid_amount = $total_paid_amount;
                                            $rooms_more_data[$key]->over_paid = $total_over_paid;
                                            
                                                  DB::table('rooms')->where('id',$request_data->room_id)->update([
                                                        'more_room_type_details' => $rooms_more_data,
                                                  ]);
                                                    
                                                    
                                                    
                                                    
                                                    
                                                   
                                                    
                                                    $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$request_data->supplier_id)->select('id','wallat_balance')->first();
                                                    $supplier_wallet_am = $supplier_data->wallat_balance + $over_paid_am;
                                                    DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['wallat_balance'=>$supplier_wallet_am]);
                                                    
                                                    
                                                    if($over_paid_am != 0){ 
                                                           DB::table('hotel_supplier_wallet_trans')->insert(['over_paid_am'=>$over_paid_am,
                                                                                                            'payment_am'=>$request_data->amount_paid,
                                                                                                            'balance'=>$supplier_wallet_am,
                                                                                                            'room_id'=>$request_data->room_id,
                                                                                                            'room_generted_id'=>$request_data->room_id_generate,
                                                                                                            'supplier_id'=>$request_data->supplier_id,
                                                                                                            'date'=>$request_data->date,
                                                                                                            'pay_method'=>$request_data->payment_method,
                                                                                                         ]);
                                                    }
                                                 
                                                    
                                                    if($request_data->payment_method == 'Wallet'){
                                                        $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$request_data->supplier_id)->select('id','wallat_balance')->first();
                                                        $supplier_wallet_am = $supplier_data->wallat_balance - $request_data->amount_paid;
                                                        DB::table('rooms_Invoice_Supplier')->where('id',$request_data->supplier_id)->update(['wallat_balance'=>$supplier_wallet_am]);
                                                        
                                                        DB::table('hotel_supplier_wallet_trans')->insert(['over_paid_am'=>$over_paid_am,
                                                                                                        'payment_am'=>$request_data->amount_paid,
                                                                                                        'balance'=>$supplier_wallet_am,
                                                                                                        'room_id'=>$request_data->room_id,
                                                                                                        'room_generted_id'=>$request_data->room_id_generate,
                                                                                                        'supplier_id'=>$request_data->supplier_id,
                                                                                                        'date'=>$request_data->date,
                                                                                                        'pay_method'=>$request_data->payment_method,
                                                                                                     ]);
                                                    }
                                                    
                                                    DB::table('hotel_supplier_payments')->insert(['supplier_id'=>$request_data->supplier_id,
                                                                                                    'over_paid_am'=>$over_paid_am,
                                                                                                    'room_id'=>$request_data->room_id,
                                                                                                    'room_generted_id'=>$request_data->room_id_generate,
                                                                                                    'payment_amount'=>$request_data->amount_paid,
                                                                                                    'date'=>$request_data->date,
                                                                                                     ]);
                                            
                                            
                                            // echo "total booked rooms is $total_booked";
                                        }
                                    }
                                    
                               
                                }else{
                                     $paid_amount = $room_data->paid_amount;
                                    $over_paid_amount = $room_data->over_paid;
                                    
                                    $total_paid_amount = $paid_amount + $request_data->amount_paid;
                                    $total_over_paid = 0;
                                    $over_paid_am = 0;
                                    if($total_paid_amount > $total_amount){
                                        $over_paid_am = $total_paid_amount - $total_amount;
                                        $total_over_paid = $over_paid_amount + $over_paid_am;
                                        
                                        $total_paid_amount = $total_paid_amount - $over_paid_am;
                                    }
                                    
                                    DB::table('rooms')->where('id',$request_data->room_id)->update([
                                        'paid_amount' => $total_paid_amount,
                                        'over_paid' => $total_over_paid,
                                    ]);
                                    
                                    $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$request_data->supplier_id)->select('id','wallat_balance')->first();
                                    $supplier_wallet_am = $supplier_data->wallat_balance + $over_paid_am;
                                    DB::table('rooms_Invoice_Supplier')->where('id',$supplier_data->id)->update(['wallat_balance'=>$supplier_wallet_am]);
                                    
                                    
                                    if($over_paid_am != 0){
                                           DB::table('hotel_supplier_wallet_trans')->insert(['over_paid_am'=>$over_paid_am,
                                                                                            'payment_am'=>$request_data->amount_paid,
                                                                                            'balance'=>$supplier_wallet_am,
                                                                                            'room_id'=>$request_data->room_id,
                                                                                            'supplier_id'=>$request_data->supplier_id,
                                                                                            'date'=>$request_data->date,
                                                                                            'pay_method'=>$request_data->payment_method,
                                                                                         ]);
                                    }
                                 
                                    
                                    if($request_data->payment_method == 'Wallet'){
                                        $supplier_data = DB::table('rooms_Invoice_Supplier')->where('id',$request_data->supplier_id)->select('id','wallat_balance')->first();
                                        $supplier_wallet_am = $supplier_data->wallat_balance - $request_data->amount_paid;
                                        DB::table('rooms_Invoice_Supplier')->where('id',$request_data->supplier_id)->update(['wallat_balance'=>$supplier_wallet_am]);
                                        
                                        DB::table('hotel_supplier_wallet_trans')->insert(['over_paid_am'=>$over_paid_am,
                                                                                        'payment_am'=>$request_data->amount_paid,
                                                                                        'balance'=>$supplier_wallet_am,
                                                                                        'room_id'=>$request_data->room_id,
                                                                                        'supplier_id'=>$request_data->supplier_id,
                                                                                        'date'=>$request_data->date,
                                                                                        'pay_method'=>$request_data->payment_method,
                                                                                     ]);
                                    }
                                    
                                    DB::table('hotel_supplier_payments')->insert(['supplier_id'=>$request_data->supplier_id,
                                                                                    'over_paid_am'=>$over_paid_am,
                                                                                    'room_id'=>$request_data->room_id,
                                                                                    'payment_amount'=>$request_data->amount_paid,
                                                                                    'date'=>$request_data->date,
                                                                                     ]);
                                }
                                
                               
                                
                    
          
                            DB::commit();
                            
                            return response()->json(['status'=>'success','message'=>'Balance is Updated Successfully']);
                        } catch (\Exception $e) {
                            DB::rollback();
                            echo $e;die;
                            return response()->json(['status'=>'error','message'=>'Something Went Wrong try Again']);
                            // something went wrong
                        }
                        
   }
   
   
   public function view_room_bookings(Request $request){
    //   print_r($request->all());
    
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
    //   die;
                     $rooms_types_count = 0;
                      $rooms_types_count_booked = 0;
                      $room_records = [];
                       $supplier_total_rooms = 0;
                      $supplier_total_rooms_booked = 0;
                      $supplier_total_cost = 0;
                  
                      $suppliers_rooms = DB::table('rooms')
                                        ->where('id',$request->room_id) 
                                        ->get();
                      
                    //   print_r($suppliers_rooms);
                    //   die;
                foreach($suppliers_rooms as $room_res){
                    $hotel_name = DB::table('hotels')->where('id',$room_res->hotel_id)->select('property_name','property_city')->first();
                        $booking_details_arr = [];
                    

                            //  echo "supplier id is ".$request->supplier_id."⧵n";
                          if($room_res->room_supplier_name == $request->supplier_id && $room_res->room_type_cat == $request->room_type_id){
                            //   echo "The supplier id is $request->supplier_id and room sup id is ".$room_res->room_supplier_name;
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
                                 
                                 $rooms_booking_details = DB::table('rooms_bookings_details')->where('room_id',$room_res->id)->get();
                                 
                                if(isset($rooms_booking_details) && !empty($rooms_booking_details)){
                                   
                                    
                                    foreach($rooms_booking_details as $book_res){
                                        if($book_res->booking_from == 'website'){
                                            $website_booking = DB::table('hotel_booking')->where('search_id',$book_res->booking_id)->select('payment_status','recieved_amount','amount_paid','total_price')->first();
                                            $website_booking_cost += $website_booking->total_price;
                                            
                                        
                                            // print_r($website_booking);
                                            
                                            $single_booking_details = [
                                                    'booking_form'=>'Website',
                                                    'Invoice_id' => $book_res->booking_id,
                                                    'quantity' => $book_res->quantity,
                                                    'check_in'=> date('d-m-Y',strtotime($book_res->check_in)),
                                                    'check_out'=> date('d-m-Y',strtotime($book_res->check_out)),
                                                    'cost_SAR' => $website_booking->total_price,
                                                    'cost_GBP' => $website_booking->total_price,
                                               ];
                                               
                                            array_push($booking_details_arr,$single_booking_details);
                                        }
                                        
                                        
                                       
                                       $invoice_total_cost_SAR = 0;
                                       $invoice_total_cost_GBP = 0;
                                        if($book_res->booking_from == 'Invoices'){
                                            $website_booking = DB::table('add_manage_invoices')->where('id',$book_res->booking_id)
                                            ->select('currency_conversion','conversion_type_Id','accomodation_details_more','accomodation_details','markup_details','more_markup_details')->first();
                                            
                                            
                                            $conversion_type = '';
                                            if(isset($website_booking)){
                                                $conversion_data = DB::table('mange_currencies')->where('id',$website_booking->conversion_type_Id)->first();
                                                 if(isset($conversion_data)){
                                                    $purchase_currency = $conversion_data->purchase_currency;
                                                    $sale_currency =  $conversion_data->sale_currency;
                                                    $conversion_type == $conversion_data->conversion_type;
                                                }else{
                                                    $purchase_currency = '';
                                                    $sale_currency =  '';
                                                    
                                                    $conversion_type = 'Divided';
                                                }
                                     
                                                $accomodation_detials = json_decode($website_booking->accomodation_details);
                                                $accomodation_detials_more = json_decode($website_booking->accomodation_details_more);
                                                
                                                if(isset($accomodation_detials)){
                                                    foreach($accomodation_detials as $acc_res){
                                                        if($request->supplier_id == $acc_res->hotel_supplier_id && $request->room_type_id == $acc_res->hotel_type_id){
                                                            // print_r($acc_res);
                                                            $single_inv_cost = $acc_res->acc_total_amount * $acc_res->acc_qty;
                                                            $Invoices_cost_GBP += $single_inv_cost;
                                                            
                                                            if($conversion_type == 'Divided'){
                                                                $single_inv_cost_SAR = $single_inv_cost * $acc_res->exchange_rate_price;
                                                            }else{
                                                                $single_inv_cost_SAR = $single_inv_cost / $acc_res->exchange_rate_price;
                                                            }
    
                                                            
                                                            $Invoices_cost_SAR += $single_inv_cost_SAR;
                                                            // echo " The total cost is ".$Invoices_cost_GBP." SAR Cost $Invoices_cost_SAR Single is $single_inv_cost ";
                                                    
                                                            $invoice_total_cost_SAR += $single_inv_cost_SAR;
                                                            $invoice_total_cost_GBP += $single_inv_cost;
                                                         }
                                                    }
                                                }
                                                
                                                if(isset($accomodation_detials_more)){
                                                    foreach($accomodation_detials_more as $acc_more_res){
                                                        if($request->supplier_id == $acc_more_res->more_hotel_supplier_id  && $request->room_type_id == $acc_more_res->more_hotel_type_id){
                                                            // print_r($acc_more_res);
                                                        $single_inv_cost = $acc_more_res->more_acc_total_amount * $acc_more_res->more_acc_qty;
                                                        $Invoices_cost_GBP += $single_inv_cost;
                                                        
                                                         if($conversion_type == 'Divided'){
                                                            $single_inv_cost_SAR = $single_inv_cost * $acc_more_res->more_exchange_rate_price;
                                                        }else{
                                                            $single_inv_cost_SAR = $single_inv_cost / $acc_more_res->more_exchange_rate_price;
                                                        }
                                                            
                                                        
                                                        $Invoices_cost_SAR += $single_inv_cost_SAR;
                                                        // echo " The total cost is ".$Invoices_cost_GBP." SAR Cost $Invoices_cost_SAR Single is $single_inv_cost ";
                                                    
                                                            $invoice_total_cost_SAR += $single_inv_cost_SAR;
                                                            $invoice_total_cost_GBP += $single_inv_cost;
                                                        }
                                                    }
                                                }
                                                
                                               $single_booking_details = [
                                                        'booking_form'=>'Invoices',
                                                        'Invoice_id' => $book_res->booking_id,
                                                        'quantity' => $book_res->quantity,
                                                        'check_in'=> date('d-m-Y',strtotime($book_res->check_in)),
                                                        'check_out'=> date('d-m-Y',strtotime($book_res->check_out)),
                                                        'cost_SAR' => $invoice_total_cost_SAR,
                                                        'cost_GBP' => $invoice_total_cost_GBP,
                                                        'purchase_currency'=>$purchase_currency,
                                                        'sale_currency'=>$sale_currency,
                                                   ];
                                                   
                                                array_push($booking_details_arr,$single_booking_details);
                                            }
                                           
                                        }
                                        
                                      $package_total_cost_SAR = 0;
                                      $package_total_cost_GBP = 0;
                                        if($book_res->booking_from == 'package'){
                                            $website_booking = DB::table('tours')->where('id',$book_res->package_id)
                                            ->select('currency_conversion','conversion_type_Id','accomodation_details_more','accomodation_details')->first();
                                            
                                            if(isset($website_booking)){
                                                $conversion_data = DB::table('mange_currencies')->where('id',$website_booking->conversion_type_Id)->first();
                                                
                                                $conversion_type = '';
                                                if(isset($conversion_data)){
                                                    $purchase_currency = $conversion_data->purchase_currency;
                                                    $sale_currency =  $conversion_data->sale_currency;
                                                    $conversion_type == $conversion_data->conversion_type;
                                                }else{
                                                    $purchase_currency = '';
                                                    $sale_currency =  '';
                                                    
                                                    $conversion_type = 'Divided';
                                                }
                                     
                                                $accomodation_detials = json_decode($website_booking->accomodation_details);
                                                $accomodation_detials_more = json_decode($website_booking->accomodation_details_more);
                                                
                                                if(isset($accomodation_detials)){
                                                    foreach($accomodation_detials as $acc_res){
                                                        if($request->supplier_id == $acc_res->hotel_supplier_id && $request->room_type_id == $acc_res->hotel_type_id){
                                                            // print_r($acc_res);
                                                            $single_inv_cost = ($acc_res->price_per_room_sale * $acc_res->acc_no_of_nightst) * $book_res->quantity;
                                                            $Invoices_cost_GBP += $single_inv_cost;
                                                            
                                                            if($conversion_type == 'Divided'){
                                                                $single_inv_cost_SAR = $single_inv_cost * $acc_res->exchange_rate_price;
                                                            }else{
                                                                $single_inv_cost_SAR = $single_inv_cost / $acc_res->exchange_rate_price;
                                                            }
    
                                                            
                                                            $Invoices_cost_SAR += $single_inv_cost_SAR;
                                                            // echo " The total cost is ".$Invoices_cost_GBP." SAR Cost $Invoices_cost_SAR Single is $single_inv_cost ";
                                                    
                                                            $package_total_cost_SAR += $single_inv_cost_SAR;
                                                            $package_total_cost_GBP += $single_inv_cost;
                                                         }
                                                    }
                                                }
                                                
                                                if(isset($accomodation_detials_more)){
                                                    foreach($accomodation_detials_more as $acc_more_res){
                                                        if($request->supplier_id == $acc_more_res->more_hotel_supplier_id  && $request->room_type_id == $acc_more_res->more_hotel_type_id){
                                                            // print_r($acc_more_res);
                                                        $single_inv_cost = ($acc_more_res->more_price_per_room_sale * $acc_more_res->more_acc_no_of_nightst) * $book_res->quantity;
                                                        $Invoices_cost_GBP += $single_inv_cost;
                                                        
                                                         if($conversion_type == 'Divided'){
                                                            $single_inv_cost_SAR = $single_inv_cost * $acc_more_res->more_exchange_rate_price;
                                                        }else{
                                                            $single_inv_cost_SAR = $single_inv_cost / $acc_more_res->more_exchange_rate_price;
                                                        }
                                                            
                                                        
                                                        $Invoices_cost_SAR += $single_inv_cost_SAR;
                                                        // echo " The total cost is ".$Invoices_cost_GBP." SAR Cost $Invoices_cost_SAR Single is $single_inv_cost ";
                                                    
                                                            $package_total_cost_SAR += $single_inv_cost_SAR;
                                                            $package_total_cost_GBP += $single_inv_cost;
                                                        }
                                                    }
                                                }
                                                
                                              $single_booking_details = [
                                                        'booking_form'=>'Package',
                                                        'Invoice_id' => $book_res->booking_id,
                                                        'quantity' => $book_res->quantity,
                                                        'check_in'=> date('d-m-Y',strtotime($book_res->check_in)),
                                                        'check_out'=> date('d-m-Y',strtotime($book_res->check_out)),
                                                        'cost_SAR' => $package_total_cost_SAR,
                                                        'cost_GBP' => $package_total_cost_GBP,
                                                        'purchase_currency'=>$purchase_currency,
                                                        'sale_currency'=>$sale_currency,
                                                  ];
                                                   
                                                array_push($booking_details_arr,$single_booking_details);
                                            }
                                           
                                        }
                                        
                                        
                                    }
                                    
                                }
                          
                                $room_record_single = ['room_id'=>$room_res->id,
                                                 'hotel_id'=>$room_res->hotel_id,
                                                 'hotel_name'=>$hotel_name->property_name,
                                                 'hotel_city'=>$hotel_name->property_city,
                                                 'hotel_id'=>$room_res->hotel_id,
                                                 'room_type'=>$room_res->room_type_id,
                                                 'room_type_id'=>$room_res->room_type_cat,
                                                 'room_type_name'=>$room_res->room_type_name,
                                                 'quantity'=>$room_res->quantity,
                                                 'booked'=>$room_res->booked,
                                                 'booked_details'=>$room_res->booking_details,
                                                 
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
                                                 'all_room_price'=>$total_price * $room_res->quantity,
                                                 'booking_details'=>$booking_details_arr
                                                 ];
                                                 
                                $supplier_total_cost += $total_price * $room_res->quantity;
                                 array_push($room_records,$room_record_single);
                          }
                          
                    
                         
                                                 
                           
                     
                          
                          
                      }
                      
                // print_r($room_records);
                  return response()->json(['status'=>'success','data'=>$room_records]);
                    
   }
   
   public function add_hotel_suppliers(Request $request)
    {
   
      $countries=DB::table('countries')->get();
      

       return response()->json(['countries'=>$countries]);
        
        
   }
   
     public function supplier_hotel_wallet_trans(Request $request)
    {
        // print_r($request->all());
        // die;
      $supplier_wallet_trans=DB::table('hotel_supplier_wallet_trans')->where('supplier_id',$request->supplier_id)->get();
      

       return response()->json(['message'=>'success','suppliers_trans'=>$supplier_wallet_trans]);
        
        
   }
  
    public function submit_hotel_suppliers(Request $request){
        $customer_id            = $request->customer_id;
        $room_supplier_name     = $request->room_supplier_name;
        $email                  = $request->email;
        $phone_no               = $request->phone_no;
        $address                = $request->address;
        $contact_person_name    = $request->contact_person_name;
        $country                = $request->country;
        $city                   = $request->city;
        $more_phone_no          = $request->more_phone_no;
        
        $data=DB::table('rooms_Invoice_Supplier')->insert([  
            'customer_id'           => $customer_id,
            'opening_balance'    => $request->opening_balance,
            'balance'    => $request->opening_balance,
            'opening_payable'    => $request->payable_balance,
            'payable'    => $request->payable_balance,
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
  
    public function edit_hotel_suppliers(Request $request){
        $id=$request->id;
        $data=DB::table('rooms_Invoice_Supplier')->where('id',$id)->first();
      
        $countries=DB::table('countries')->get();
        return response()->json(['data'=>$data,'countries'=>$countries]);
        
        
    }
   
    public function submit_edit_hotel_suppliers(Request $request)
    {
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
        $data=DB::table('rooms_Invoice_Supplier')->where('id',$id)->update([
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
   
   public function delete_hotel_suppliers(Request $request)
    {
   $id=$request->id;
      $data=DB::table('rooms_Invoice_Supplier')->where('id',$id)->delete();
      

       return response()->json(['data'=>$data,'Status'=>'Successful']);
        
        
   }
}
