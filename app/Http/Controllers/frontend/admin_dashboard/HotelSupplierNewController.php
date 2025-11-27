<?php

namespace App\Http\Controllers\frontend\admin_dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UmrahPackage;
use App\Models\Tour;
use App\Models\country;
use DateTime;
use App\Models\view_booking_payment_recieve;
use App\Models\view_booking_payment_recieve_Activity;
use App\Models\CustomerSubcription\CustomerSubcription;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Auth;
use DB;

class HotelSupplierNewController extends Controller
{
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
                                 
                                 
                                //  print_r($rooms_booking_details);
                                 
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
                                            
                                            $cart_data = DB::table('cart_details')->where('booking_id',$book_res->booking_id)->select('id','tour_id')->first();
                                            
                                            
                                            $website_booking = DB::table('tours')->where('id',$cart_data->tour_id)
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
    
    public function view_hotel_suppliers(Request $request){
        // dd($request->SU_id);
        if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
            $data           = DB::table('rooms_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->get();
            $customer_Id    = $request->customer_id;
            $SU_id          = $request->SU_id;
            $data           = DB::table('rooms_Invoice_Supplier')
                                ->where(function ($query) use ($customer_Id) {
                                    $query->where('customer_id', $customer_Id);
                                })
                                ->orWhere(function ($query) use ($SU_id) {
                                    $query->where('SU_id', $SU_id);
                                })
                                ->orderBy('rooms_Invoice_Supplier.id','desc')->get();
            $all_Customer   = DB::table('customer_subcriptions')->where('id', '!=' ,$request->customer_id)->get();
        }else{
            $all_Customer   = DB::table('customer_subcriptions')->where('id', '!=' ,$request->customer_id)->get();
            $customer_Id    = $request->customer_id;
            $data           = DB::table('rooms_Invoice_Supplier')
                                ->where(function ($query) use ($customer_Id) {
                                    $query->where('customer_id', $customer_Id);
                                })
                                ->orWhere(function ($query) use ($customer_Id) {
                                    $query->where('id', 135);
                                })
                                ->orderBy('rooms_Invoice_Supplier.id','desc')->get();
        }
        
        // dd($data);
        
        return response()->json(['data'=>$data,'all_Customer'=>$all_Customer]);
    }
   
    public function add_hotel_suppliers(Request $request){
   
      $countries=DB::table('countries')->get();
      

       return response()->json(['countries'=>$countries]);
        
        
    }
   
    public function supplier_hotel_wallet_trans(Request $request){
        // print_r($request->all());
        // die;
      $supplier_wallet_trans=DB::table('hotel_supplier_wallet_trans')->where('supplier_id',$request->supplier_id)->get();
      

       return response()->json(['message'=>'success','suppliers_trans'=>$supplier_wallet_trans]);
        
        
    }
   
    public function hotel_suppliers_ledger(Request $request){
       $supplier_detail  = DB::table('rooms_Invoice_Supplier')->where('id',$request->supplier_id)->first();
        $supplier_ledger_data  = DB::table('hotel_supplier_ledger')->where('supplier_id',$request->supplier_id)->get();

        $sup_all_ledger = [];
        foreach($supplier_ledger_data as $sup_led_res){
            $invoice_booking_data = DB::table('rooms_bookings_details')->where('booking_id',$sup_led_res->invoice_no)
                                                                     ->where('room_id',$sup_led_res->room_id)
                                                                     ->get();
                                                                     
            $package_booking_data = DB::table('rooms_bookings_details')->where('booking_id',$sup_led_res->package_invoice_no)
                                                                     ->where('room_id',$sup_led_res->room_id)
                                                                     ->get();
                                                                     
        //   print_r($sup_led_res);
            $rooms_data = DB::table('rooms')->where('id',$sup_led_res->room_id)->select('room_type_name','id')->first();
            
            // echo "Room id $sup_led_res->room_id";
            // print_r($rooms_data);
            if(count($invoice_booking_data) > 0){
                $invoice_booking_data = $invoice_booking_data;
            }else{
                $invoice_booking_data = '';
            }
            
            $room_name = '';
            if(isset($rooms_data->room_type_name)){
                $room_name = $rooms_data->room_type_name;
            }
            
            if(count($package_booking_data) > 0){
                $package_booking_data = $package_booking_data;
            }else{
                $package_booking_data = '';
            }
            $ledger_item = (object)[
                    'payment'=>$sup_led_res->payment,
                    'received'=>$sup_led_res->received,
                    'balance'=>$sup_led_res->balance,
                    'sale_price'=>$sup_led_res->sale_price,
                    'exchange_rate'=>$sup_led_res->exchange_rate,
                    'payable_balance'=>$sup_led_res->payable_balance,
                    'payment_id'=>$sup_led_res->payment_id,
                    'package_invoice_no'=>$sup_led_res->package_invoice_no,
                    'invoice_no'=>$sup_led_res->invoice_no,
                    'remarks'=>$sup_led_res->remarks,
                    'date'=>date('d-m-Y',strtotime($sup_led_res->date)),
                    'website_booking_id'=>$sup_led_res->website_booking_id,
                    'room_id'=>$sup_led_res->room_id,
                    'ledger_room_type'=>$room_name,
                    'quantity'=>$sup_led_res->room_quantity,
                    'available_from'=>$sup_led_res->available_from,
                    'available_to'=>$sup_led_res->available_to,
                    'invoice_booking_data'=> $invoice_booking_data,
                    'package_booking_data'=> $package_booking_data,
                ];
                
            array_push($sup_all_ledger,$ledger_item);
            // print_r($package_booking_data);
        }
        
        // print_r($sup_all_ledger);
        
        // die;
        return response()->json(['status'=>'success','ledger_data'=>$sup_all_ledger,'supplier_Pers_details'=>$supplier_detail]);
        
        
   }
  
    public function submit_hotel_suppliers(Request $request){
        DB::beginTransaction();
        try {
            $words_RSN          = preg_split('/\s+/', $request->room_supplier_name);
            $room_supplier_name = '';
            foreach ($words_RSN as $word) {
                if (ctype_alpha($word[0])) {
                    $room_supplier_name .= strtoupper($word[0]);
                }
            }
            
            if($request->room_supplier_name != null && $request->room_supplier_name != '' && $request->email != null && $request->email != ''){
                $data = DB::table('rooms_Invoice_Supplier')->insert([  
                    'SU_id'                 => $request->SU_id ?? NULL,
                    'customer_id'           => $request->customer_id,
                    'opening_balance'       => $request->opening_balance,
                    'balance'               => $request->opening_balance,
                    'opening_payable'       => $request->payable_balance,
                    'payable'               => $request->payable_balance,
                    'room_supplier_name'    => $request->room_supplier_name,
                    'room_supplier_code'    => $room_supplier_name.'-'.rand(0,4444),
                    'email'                 => $request->email,
                    'phone_no'              => $request->phone_no,
                    'address'               => $request->address,
                    'contact_person_name'   => $request->contact_person_name,
                    'country'               => $request->country,
                    'city'                  => $request->city,
                    'currency'              => $request->currency ?? NULL,
                    'more_phone_no'         => $request->more_phone_no,
                ]);
                if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
                    $all_HS = DB::table('rooms_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->get();
                }else{
                    $all_HS = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->get();
                }
                
                DB::commit();
                
                return response()->json(['message'=>'success','Status'=>'SuccessFull','data'=>$data,'all_HS'=>$all_HS]);
            }else{
                return response()->json(['message'=>'error']);    
            }
        } catch (Throwable $e) {
            DB::rollback();
            echo $e;
            
            return response()->json(['message'=>'error']);
        }
    }
    
    public function submit_mulitple_suppliers(Request $request){
        DB::beginTransaction();
        try {
          
            $supplier_types = json_decode($request->supplier_types);
            
            if($request->room_supplier_name != null && $request->room_supplier_name != '' && $request->email != null && $request->email != ''){
                foreach($supplier_types as $supplier){
                    if($supplier == "Accomodation"){
                        
                            $data = DB::table('rooms_Invoice_Supplier')->insert([  
                                'SU_id'                 => $request->SU_id ?? NULL,
                                'customer_id'           => $request->customer_id,
                                'opening_balance'       => $request->opening_balance,
                                'balance'               => $request->opening_balance,
                                'opening_payable'       => $request->payable_balance,
                                'payable'               => $request->payable_balance,
                                'room_supplier_name'    => $request->room_supplier_name,
                                'email'                 => $request->email,
                                'phone_no'              => $request->phone_no,
                                'address'               => $request->address,
                                'contact_person_name'   => $request->contact_person_name,
                                'country'               => $request->country,
                                'city'                  => $request->city,
                                'more_phone_no'         => $request->more_phone_no,
                            ]);
                            if(isset($request->SU_id) && $request->SU_id != null && $request->SU_id != ''){
                                $all_HS = DB::table('rooms_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->get();
                            }else{
                                $all_HS = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->get();
                            }
                            
                    }
                    
                    if($supplier == "Flight"){
                        DB::table('supplier')->insert([
                            'SU_id'                 => $request->SU_id ?? NULL,
                            'token'                 => $request->token,
                            'customer_id'           => $request->customer_id,
                            'companyname'           => $request->room_supplier_name,
                            'Companyaddress'        => $request->city.",".$request->country,
                            'companyemail'          => $request->email,
                            'contactpersonname'     => $request->contact_person_name,
                            'contactpersonemail'    => $request->email,
                            'personcontactno'       => $request->phone_no,
                            'opening_balance'       => $request->opening_balance,
                            'balance'               => $request->opening_balance,
                            
                        ]);
                    }
                    
                    if($supplier == "Transfer"){
                         DB::table('transfer_Invoice_Supplier')->insert([  
                                'SU_id'                 => $request->SU_id ?? NULL,
                                'customer_id'           => $request->customer_id,
                                'opening_balance'       => $request->opening_balance,
                                'balance'               => $request->opening_balance,
                                'room_supplier_name'    => $request->room_supplier_name,
                                'email'                 => $request->email,
                                'phone_no'              => $request->phone_no,
                                'address'               => $request->city.",".$request->country,
                                'contact_person_name'   => $request->contact_person_name,
                                'country'               => $request->country,
                                'city'                  => $request->city,
                                'more_phone_no'         => $request->more_phone_no,
                            ]);
                    }
                    
                    if($supplier == "Visa"){
                        DB::table('visa_Sup')->insert([
                                    'SU_id'                 => $request->SU_id ?? NULL,
                                    'opening_balance'       => $request->opening_balance,
                                    'balance'               => $request->opening_balance,
                                    'opening_payable'       => $request->opening_payable,
                                    'payable'               => $request->opening_payable,
                                    'customer_id'           => $request->customer_id,
                                    'visa_supplier_name'    => $request->room_supplier_name,
                                    'email'                 => $request->email,
                                    'phone_no'              => $request->phone_no,
                                    'address'               => $request->city.",".$request->country,
                                    'contact_person_name'   => $request->contact_person_name,
                                    'country'               => $request->country,
                                    'city'                  => $request->city,
                                    'currency'              => $request->currency ?? '',
                                ]);
                    }
                }
            }
            DB::commit();
            
            return response()->json(['status'=>'success', 'message' => 'Suppliers Added Successfully']);
            
        } catch (Throwable $e) {
            DB::rollback();
            echo $e;
            
            return response()->json(['status'=>'error']);
        }
    }
    
    public function mulitple_suppliers_list(Request $request){
        
        $userData = CustomerSubcription::where('Auth_key',$request->token)->select('id','status')->first();
        if($userData){
            $hotel_suppliers = DB::table('rooms_Invoice_Supplier')->where('customer_id',$userData->id)
                                            ->select('*','id as hotel_supplier_id')
                                            ->get();
            $flight_suppliers = DB::table('supplier')->where('customer_id',$userData->id)
                                             ->select('*','id as flight_supplier_id')
                                             ->get();
            $transfer_suppliers = DB::table('transfer_Invoice_Supplier')
                                            ->where('customer_id',$userData->id)
                                            ->select('*','id as transfer_supplier_id')
                                            ->get();
            
            $mergedCollection = $hotel_suppliers->merge($flight_suppliers)->merge($transfer_suppliers);

            // Sort the merged collection by created_at in ascending order
            $sortedCollection = $mergedCollection->sortBy('created_at');
            
            // If you want to convert it back to an array, you can use toArray()
            $resultArray = $sortedCollection->values()->toArray();
            
            $visa_suppliers = DB::table('visa_Sup')->where('customer_id',$userData->id)
                                            ->select('*','id as visa_supplier_id')
                                            ->get();
            
            $all_suppliers = $visa_suppliers->merge($resultArray);
            
            return response()->json(['status'=> 'success','data'=>$all_suppliers]);
        }
        
        return response()->json(['status'=> 'error','data'=>'validation Error']);
        
    }
    
    public function edit_hotel_suppliers(Request $request){
        $id=$request->id;
        $data=DB::table('rooms_Invoice_Supplier')->where('id',$id)->first();
      
        $countries=DB::table('countries')->get();
        return response()->json(['data'=>$data,'countries'=>$countries]);
        
        
    }
   
    public function submit_edit_hotel_suppliers(Request $request){
        DB::beginTransaction();
        try {
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
            $data = DB::table('rooms_Invoice_Supplier')->where('customer_id',$customer_id)->where('id',$id)->update([
                'customer_id'           => $customer_id,
                'room_supplier_name'    => $room_supplier_name,
                'email'                 => $email,
                'phone_no'              => $phone_no,
                'address'               => $address,
                'contact_person_name'   => $contact_person_name,
                'country'               => $country,
                'city'                  => $city,
                'currency'              => $request->currency ?? NULL,
                'more_phone_no'         => $more_phone_no,
            ]);
            
            DB::commit();
            
            return response()->json(['Status'=>'SuccessFull','data'=>$data]);
        } catch (Throwable $e) {
            DB::rollback();
            echo $e;
            
            return response()->json(['message'=>'error']);
        }
    }
   
    public function delete_hotel_suppliers(Request $request){
        DB::beginTransaction();
        try {
            $data = DB::table('rooms_Invoice_Supplier')->where('customer_id',$request->customer_id)->where('id',$request->id)->delete();
            DB::commit();
            return response()->json(['data'=>$data,'message'=>'success']);
        } catch (Throwable $e) {
            DB::rollback();
            echo $e;
            return response()->json(['message'=>'error']);
        }
    }
    
    public function hotel_supplier_reports_sub_new_subUser_Old(Request $request){
        $request_data =  json_decode($request->request_data);
        
        // dd($request_data);
        
        if($request_data->report_type == 'all_data'){
            if($request_data->supplier == 'all' && $request_data->agent_Id == 'all_agent'){
             
                $allgetsuppliers    = DB::table('rooms_Invoice_Supplier')->where('SU_id',$request->SU_id)->where('customer_id',$request->customer_id)->get(); 
                $hotel_supplier     = DB::table('add_manage_invoices')
                                        ->where('customer_id',$request->customer_id)
                                        ->where('SU_id',$request->SU_id)
                                        ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
                                        'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
                                        'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
                                        ->get();
                // dd($allgetsuppliers);
                
                $aray_data=array();
                foreach($hotel_supplier as $hotel){
                    if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL){
                    //   $accomodation_details=json_decode($hotel->accomodation_details);
                      $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                    //   $array_merge=array_merge($accomodation_details,$accomodation_details_more);
                      foreach($accomodation_details_more as $arr_hotel)
                      {
                          if(isset($arr_hotel->more_hotel_supplier_id))
                           {
                        foreach($allgetsuppliers as $suppliers)
                        {
                           if($suppliers->id == $arr_hotel->more_hotel_supplier_id)
                           {
                               
                           
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            'check_out'=>date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->more_hotel_city,
                            'room_type'=>$arr_hotel->more_acc_type,
                            'reservation_no'=>$arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=>$arr_hotel->more_acc_qty,
                            'supplier'=>$arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                           }
                        }
                      }
                    }
                    }
                    
                    if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL){
                        $accomodation_details=json_decode($hotel->accomodation_details);
                        foreach($accomodation_details as $arr_hotel){
                            if(isset($arr_hotel->hotel_supplier_id)){
                                foreach($allgetsuppliers as $suppliers){
                                    if($suppliers->id == $arr_hotel->hotel_supplier_id){  
                                        $aray_data[]=(object)[
                                            'invoice_no'=> $hotel->generate_id,
                                            'invoice_id'=>$hotel->id,
                                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                                            'room_type'=> $arr_hotel->acc_type,
                                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                                            'quantity'=> $arr_hotel->acc_qty,
                                            'supplier'=> $arr_hotel->hotel_supplier_id,
                                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                                            'agent_id'=> $hotel->agent_Id,
                                            'agent_name'=> $hotel->agent_Name,
                                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                                            'booking_type'=> 'invoice',
                                            'status'=> $hotel->confirm,
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
                
                return response()->json(['hotel_supplier'=>$aray_data]);
            }
          
            if($request_data->supplier == 'all' && $request_data->agent_Id != 'all_agent'){
               
               
                          $hotel_supplier = DB::table('add_manage_invoices')
                          ->where('customer_id',$request->customer_id)
                          ->where('SU_id',$request->SU_id)
            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
              ->get();
                                        
                                        $aray_data=array();
            foreach($hotel_supplier as $hotel)
            {
                if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL 
                && isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL
                )
                {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                  $array_merge=array_merge($accomodation_details,$accomodation_details_more);
                  foreach($array_merge as $arr_hotel)
                  {
                      if($request_data->agent_Id == $hotel->agent_Id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                         'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in ?? $arr_hotel->more_acc_check_in)),
                       'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out ?? $arr_hotel->more_acc_check_out)),
                       
                        'hotel_name'=> $arr_hotel->acc_hotel_name ?? $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name ?? $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->acc_type ?? $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty ?? $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id ?? $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                      }
                      
                  }
                }
                else
                {
                    if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                    {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  foreach($accomodation_details as $arr_hotel)
                  {
                      if($request_data->agent_Id == $hotel->agent_Id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                      }
                      
                  }
                }
                }
                
                
              
                
            }
            //  print_r($aray_data);
            // die();
        return response()->json(['hotel_supplier'=>$aray_data]);   
         
           }
           
            if($request_data->supplier != 'all' && $request_data->agent_Id == 'all_agent'){
           
              $hotel_supplier = DB::table('add_manage_invoices')
              ->where('customer_id',$request->customer_id)
              ->where('SU_id',$request->SU_id)
            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
              ->get();
                                        
                                        $aray_data=array();
            foreach($hotel_supplier as $hotel)
            {
                 if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                    {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  foreach($accomodation_details as $arr_hotel)
                  {
                      if($request_data->supplier == $arr_hotel->hotel_supplier_id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                      }
                      
                  }
                }
                
                if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                {
                  
                  $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                 
                  foreach($accomodation_details_more as $arr_hotel)
                  {
                      if(isset($arr_hotel->hotel_supplier_id))
                      {
                      if($request_data->supplier == $arr_hotel->hotel_supplier_id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                        'check_out'=> $arr_hotel->more_acc_check_out,
                        'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->more_acc_qty,
                        'supplier'=>  $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                      }
                  }
                  if(isset($arr_hotel->more_hotel_supplier_id))
                  {
                      if($request_data->supplier == $arr_hotel->more_hotel_supplier_id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                         'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in ?? $arr_hotel->more_acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out ?? $arr_hotel->more_acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name ?? $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name ?? $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->acc_type ?? $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty ?? $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id ?? $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];   
                  }
                  }
                  }
                }
              
                   
               
                
                
              
                
            }
            //  print_r($aray_data);
            // die();
        return response()->json(['hotel_supplier'=>$aray_data]);  
          }
            else{
           
              $hotel_supplier = DB::table('add_manage_invoices')
              ->where('customer_id',$request->customer_id)
              ->where('SU_id',$request->SU_id)
            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
              ->get();
                                        
                                        $aray_data=array();
            foreach($hotel_supplier as $hotel)
            {
                 if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                    {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  foreach($accomodation_details as $arr_hotel)
                  {
                      if($request_data->supplier == $arr_hotel->hotel_supplier_id && $request_data->agent_Id == $hotel->agent_Id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                      }
                      
                  }
                }
                
                if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                {
                  
                  $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                 
                  foreach($accomodation_details_more as $arr_hotel)
                  {
                      if(isset($arr_hotel->hotel_supplier_id))
                      {
                      if($request_data->supplier == $arr_hotel->hotel_supplier_id && $request_data->agent_Id == $hotel->agent_Id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                        'check_out'=> $arr_hotel->more_acc_check_out,
                        'hotel_name'=>$arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->more_acc_qty,
                        'supplier'=>  $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                      }
                  }
                  if(isset($arr_hotel->more_hotel_supplier_id))
                  {
                      if($request_data->supplier == $arr_hotel->more_hotel_supplier_id && $request_data->agent_Id == $hotel->agent_Id)
                      {
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                         'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in ?? $arr_hotel->more_acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out ?? $arr_hotel->more_acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name ?? $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name ?? $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->acc_type ?? $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty ?? $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id ?? $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];   
                  }
                  }
                  }
                }
              
                   
               
                
                
              
                
            }
            //  print_r($aray_data);
            // die();
        return response()->json(['hotel_supplier'=>$aray_data]);  
          }
        }
      
        if($request_data->report_type == 'data_today_wise' || $request_data->report_type == 'data_tomorrow_wise' || $request_data->report_type == 'data_week_wise'|| $request_data->report_type == 'data_month_wise'){
            if($request_data->supplier == 'all' && $request_data->agent_Id == 'all_agent'){
                 
                  $hotel_supplier = DB::table('add_manage_invoices')
                  ->where('customer_id',$request->customer_id)
                  ->where('SU_id',$request->SU_id)
                ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
                'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
                'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
                  ->get();
                                            
                $aray_data=array();
                foreach($hotel_supplier as $hotel)
                {
                    if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                        {
                      $accomodation_details=json_decode($hotel->accomodation_details);
                      foreach($accomodation_details as $arr_hotel)
                      {
                          if($request_data->report_type == 'data_today_wise')
                           {
                             $today_date=date('Y-m-d');
                            if($arr_hotel->acc_check_in == $today_date)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_tomorrow_wise')
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+1 day');
                                $tomorrowdate=$datetime->format('Y-m-d');
                            if($arr_hotel->acc_check_in == $tomorrowdate)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_week_wise')
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+6 day');
                                $this_week=$datetime->format('Y-m-d');
                            if($arr_hotel->acc_check_in >= $today_date && $arr_hotel->acc_check_in <= $this_week)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_month_wise')
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                 $datetime->modify('+29 day');
                                $this_month=$datetime->format('Y-m-d');
                            if($arr_hotel->acc_check_in >= $today_date && $arr_hotel->acc_check_in <= $this_month)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                          
                      }
                    }
                    
                    if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                    {
                      
                      $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                     
                      foreach($accomodation_details_more as $arr_hotel)
                      {
                          if($request_data->report_type == 'data_today_wise')
                           {
                              $today_date=date('Y-m-d');
                              if($arr_hotel->more_acc_check_in == $today_date)
                              {
                                $aray_data[]=(object)[
                                
                                'invoice_no'=> $hotel->generate_id,
                                'invoice_id'=>$hotel->id,
                                'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                
                                'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                
                                'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                                'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                                'room_type'=> $arr_hotel->more_acc_type,
                                'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                                'quantity'=> $arr_hotel->more_acc_qty,
                                'supplier'=> $arr_hotel->more_hotel_supplier_id,
                                'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                                'agent_id'=> $hotel->agent_Id,
                                'agent_name'=> $hotel->agent_Name,
                                'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                                'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                                'booking_type'=> 'invoice',
                                'status'=> $hotel->confirm,
                                
                                ];  
                              }
                           }
                          if($request_data->report_type == 'data_tomorrow_wise')
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+1 day');
                                $tomorrowdate=$datetime->format('Y-m-d');
                            if($arr_hotel->more_acc_check_in == $tomorrowdate)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_week_wise')
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+6 day');
                                $this_week=$datetime->format('Y-m-d');
                            if($arr_hotel->more_acc_check_in >= $today_date && $arr_hotel->more_acc_check_in <= $this_week)
                            {  
                          
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_month_wise')
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                 $datetime->modify('+29 day');
                                $this_month=$datetime->format('Y-m-d');
                            if($arr_hotel->more_acc_check_in >= $today_date && $arr_hotel->more_acc_check_in <= $this_month)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                             'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                        
                           
                          
                      }
                    }
                    
                     
                   
                    
                    
                  
                    
                }
                //  print_r($aray_data);
                // die();
            return response()->json(['hotel_supplier'=>$aray_data]);
             }
            
            if($request_data->supplier == 'all' && $request_data->agent_Id != 'all_agent'){
                 
                  $hotel_supplier = DB::table('add_manage_invoices')
                  ->where('customer_id',$request->customer_id)
                  ->where('SU_id',$request->SU_id)
                ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
                'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
                'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
                  ->get();
                  
                $aray_data=array();
                foreach($hotel_supplier as $hotel)
                {
                    if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                        {
                      $accomodation_details=json_decode($hotel->accomodation_details);
                      foreach($accomodation_details as $arr_hotel)
                      {
                          if($request_data->report_type == 'data_today_wise' && $request_data->agent_Id == $hotel->agent_Id)
                           {
                             $today_date=date('Y-m-d');
                            if($arr_hotel->acc_check_in == $today_date)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_tomorrow_wise' && $request_data->agent_Id == $hotel->agent_Id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+1 day');
                                $tomorrowdate=$datetime->format('Y-m-d');
                            if($arr_hotel->acc_check_in == $tomorrowdate)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_week_wise' && $request_data->agent_Id == $hotel->agent_Id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+6 day');
                                $this_week=$datetime->format('Y-m-d');
                            if($arr_hotel->acc_check_in >= $today_date && $arr_hotel->acc_check_in <= $this_week)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_month_wise' && $request_data->agent_Id == $hotel->agent_Id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                 $datetime->modify('+29 day');
                                $this_month=$datetime->format('Y-m-d');
                            if($arr_hotel->acc_check_in >= $today_date && $arr_hotel->acc_check_in <= $this_month)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                          
                      }
                    }
                    
                    if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                    {
                      
                      $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                     
                      foreach($accomodation_details_more as $arr_hotel)
                      {
                          if($request_data->report_type == 'data_today_wise' && $request_data->agent_Id == $hotel->agent_Id)
                           {
                              $today_date=date('Y-m-d');
                              if($arr_hotel->more_acc_check_in == $today_date)
                              {
                                $aray_data[]=(object)[
                                
                                'invoice_no'=> $hotel->generate_id,
                                'invoice_id'=>$hotel->id,
                                'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                                'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                                'room_type'=> $arr_hotel->more_acc_type,
                                'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                                'quantity'=> $arr_hotel->more_acc_qty,
                                'supplier'=> $arr_hotel->more_hotel_supplier_id,
                                'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                                'agent_id'=> $hotel->agent_Id,
                                'agent_name'=> $hotel->agent_Name,
                                'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                                'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                                'booking_type'=> 'invoice',
                                'status'=> $hotel->confirm,
                                
                                ];  
                              }
                           }
                          if($request_data->report_type == 'data_tomorrow_wise' && $request_data->agent_Id == $hotel->agent_Id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+1 day');
                                $tomorrowdate=$datetime->format('Y-m-d');
                            if($arr_hotel->more_acc_check_in == $tomorrowdate)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_week_wise' && $request_data->agent_Id == $hotel->agent_Id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+6 day');
                                $this_week=$datetime->format('Y-m-d');
                            if($arr_hotel->more_acc_check_in >= $today_date && $arr_hotel->more_acc_check_in <= $this_week)
                            {  
                          
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_month_wise' && $request_data->agent_Id == $hotel->agent_Id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                 $datetime->modify('+29 day');
                                $this_month=$datetime->format('Y-m-d');
                            if($arr_hotel->more_acc_check_in >= $today_date && $arr_hotel->more_acc_check_in <= $this_month)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                        
                           
                          
                      }
                    }
                    
                     
                   
                    
                    
                  
                    
                }
                //  print_r($aray_data);
                // die();
            return response()->json(['hotel_supplier'=>$aray_data]);
             } 
            else{
                  
                  
                  
                  
                  
                  
                  $hotel_supplier = DB::table('add_manage_invoices')
                  ->where('customer_id',$request->customer_id)
                  ->where('SU_id',$request->SU_id)
                ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
                'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
                'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
                  ->get();
                 
                $aray_data=array();
                foreach($hotel_supplier as $hotel)
                {
                    if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                        {
                      $accomodation_details=json_decode($hotel->accomodation_details);
                      foreach($accomodation_details as $arr_hotel)
                      {
                          
                          if($request_data->report_type == 'data_today_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->hotel_supplier_id)
                           {
                             $today_date=date('Y-m-d');
                            if($arr_hotel->acc_check_in == $today_date)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_tomorrow_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->hotel_supplier_id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+1 day');
                                $tomorrowdate=$datetime->format('Y-m-d');
                            if($arr_hotel->acc_check_in == $tomorrowdate)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_week_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->hotel_supplier_id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+6 day');
                                $this_week=$datetime->format('Y-m-d');
                            if($arr_hotel->acc_check_in >= $today_date && $arr_hotel->acc_check_in <= $this_week)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_month_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->hotel_supplier_id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                 $datetime->modify('+29 day');
                                $this_month=$datetime->format('Y-m-d');
                            if($arr_hotel->acc_check_in >= $today_date && $arr_hotel->acc_check_in <= $this_month)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                            'hotel_name'=> $arr_hotel->acc_hotel_name,
                            'hotel_city_name'=> $arr_hotel->hotel_city_name,
                            'room_type'=> $arr_hotel->acc_type,
                            'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->acc_qty,
                            'supplier'=> $arr_hotel->hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                          
                      }
                    }
                    
                    if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                    {
                      
                      $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                     
                      foreach($accomodation_details_more as $arr_hotel)
                      {
                          if($request_data->report_type == 'data_today_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->more_hotel_supplier_id)
                           {
                              $today_date=date('Y-m-d');
                              if($arr_hotel->more_acc_check_in == $today_date)
                              {
                                $aray_data[]=(object)[
                                
                                'invoice_no'=> $hotel->generate_id,
                                'invoice_id'=>$hotel->id,
                                'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                                'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                                'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                                'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                                'room_type'=> $arr_hotel->more_acc_type,
                                'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                                'quantity'=> $arr_hotel->more_acc_qty,
                                'supplier'=> $arr_hotel->more_hotel_supplier_id,
                                'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                                'agent_id'=> $hotel->agent_Id,
                                'agent_name'=> $hotel->agent_Name,
                                'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                                'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                                'booking_type'=> 'invoice',
                                'status'=> $hotel->confirm,
                                
                                ];  
                              }
                           }
                          if($request_data->report_type == 'data_tomorrow_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->more_hotel_supplier_id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+1 day');
                                $tomorrowdate=$datetime->format('Y-m-d');
                            if($arr_hotel->more_acc_check_in == $tomorrowdate)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_week_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->more_hotel_supplier_id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                $datetime->modify('+6 day');
                                $this_week=$datetime->format('Y-m-d');
                            if($arr_hotel->more_acc_check_in >= $today_date && $arr_hotel->more_acc_check_in <= $this_week)
                            {  
                          
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                           if($request_data->report_type == 'data_month_wise' && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->more_hotel_supplier_id)
                           {
                             $today_date=date('Y-m-d');
                               $datetime = new DateTime($today_date);
                                 $datetime->modify('+29 day');
                                $this_month=$datetime->format('Y-m-d');
                            if($arr_hotel->more_acc_check_in >= $today_date && $arr_hotel->more_acc_check_in <= $this_month)
                            {  
                        $aray_data[]=(object)[
                            
                            'invoice_no'=> $hotel->generate_id,
                            'invoice_id'=>$hotel->id,
                            'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                            'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                            'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                            'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                            'room_type'=> $arr_hotel->more_acc_type,
                            'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                            'quantity'=> $arr_hotel->more_acc_qty,
                            'supplier'=> $arr_hotel->more_hotel_supplier_id,
                            'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                            'agent_id'=> $hotel->agent_Id,
                            'agent_name'=> $hotel->agent_Name,
                            'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                            'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                            'booking_type'=> 'invoice',
                            'status'=> $hotel->confirm,
                            
                            ];
                         
                      }    
                           }
                        
                           
                          
                      }
                    }
                    
                     
                   
                    
                    
                  
                    
                }
                //  print_r($aray_data);
                // die();
            return response()->json(['hotel_supplier'=>$aray_data]);
                  
                  
                 
              }
        }
        
        if($request_data->report_type == 'data_wise'){
            if($request_data->supplier == 'all' && $request_data->agent_Id == 'all_agent'){
             
              $hotel_supplier = DB::table('add_manage_invoices')
              ->where('customer_id',$request->customer_id)
              ->where('SU_id',$request->SU_id)
            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
              ->get();
                                        
                                        $aray_data=array();
            foreach($hotel_supplier as $hotel)
            {
                if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                    {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  foreach($accomodation_details as $arr_hotel)
                  {
                    if($arr_hotel->acc_check_in >= $request_data->check_in && $arr_hotel->acc_check_in <= $request_data->check_out)
                      {  
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                  }  
                  }
                }
                
                if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                {
                  
                  $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                 
                  foreach($accomodation_details_more as $arr_hotel)
                  {
                      if($arr_hotel->more_acc_check_in >= $request_data->check_in && $arr_hotel->more_acc_check_in <= $request_data->check_out
                     
                      )
                      {
                        $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                        'check_out'=>  date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                        'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];  
                      }
                    
                     
                      
                  }
                }
                
                 
               
                
                
              
                
            }
            //  print_r($aray_data);
            // die();
        return response()->json(['hotel_supplier'=>$aray_data]);
         }
            
            if($request_data->supplier == 'all' && $request_data->agent_Id != 'all_agent')
            {
               
        $hotel_supplier = DB::table('add_manage_invoices')
        ->where('customer_id',$request->customer_id)
        ->where('SU_id',$request->SU_id)
            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
              ->get();
                                        
                                        $aray_data=array();
            foreach($hotel_supplier as $hotel)
            {
                if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                    {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  foreach($accomodation_details as $arr_hotel)
                  {
                     
                    if($arr_hotel->acc_check_in >= $request_data->check_in && $arr_hotel->acc_check_in <= $request_data->check_out && $request_data->agent_Id == $hotel->agent_Id)
                      {  
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                  }  
                  }
                }
                
                if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                {
                  
                  $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                 
                  foreach($accomodation_details_more as $arr_hotel)
                  {
                      if($arr_hotel->more_acc_check_in >= $request_data->check_in && $arr_hotel->more_acc_check_in <= $request_data->check_out && $request_data->agent_Id == $hotel->agent_Id)
                      {
                        $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                        'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];  
                      }
                    
                     
                      
                  }
                }
                
                 
               
                
                
              
                
            }
            //  print_r($aray_data);
            // die();
        return response()->json(['hotel_supplier'=>$aray_data]);  
         
           }
            else
            {
                $hotel_supplier = DB::table('add_manage_invoices')
                ->where('customer_id',$request->customer_id)
                ->where('SU_id',$request->SU_id)
            ->select('add_manage_invoices.agent_Id','add_manage_invoices.agent_Name','add_manage_invoices.agent_Company_Name','add_manage_invoices.generate_id',
            'add_manage_invoices.confirm','add_manage_invoices.accomodation_details','add_manage_invoices.accomodation_details_more','add_manage_invoices.f_name',
            'add_manage_invoices.middle_name','add_manage_invoices.created_at','add_manage_invoices.id')
              ->get();
                                        
                                        $aray_data=array();
            foreach($hotel_supplier as $hotel)
            {
                if(isset($hotel->accomodation_details) && $hotel->accomodation_details != 'null' && $hotel->accomodation_details != NULL)
                    {
                  $accomodation_details=json_decode($hotel->accomodation_details);
                  foreach($accomodation_details as $arr_hotel)
                  {
                      if(isset($arr_hotel->acc_check_in) && isset($arr_hotel->acc_check_out) && isset($arr_hotel->agent_Id) && isset($arr_hotel->hotel_supplier_id))
                      {
                    if($arr_hotel->acc_check_in >= $request_data->check_in && $arr_hotel->acc_check_in <= $request_data->check_out && $request_data->agent_Id == $hotel->agent_Id && $request_data->supplier == $arr_hotel->hotel_supplier_id)
                      {  
                    $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->acc_check_out)),
                        'hotel_name'=> $arr_hotel->acc_hotel_name,
                        'hotel_city_name'=> $arr_hotel->hotel_city_name,
                        'room_type'=> $arr_hotel->acc_type,
                        'reservation_no'=> $arr_hotel->room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->acc_qty,
                        'supplier'=> $arr_hotel->hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];
                     
                  }
                  }
                  }
                }
                
                if(isset($hotel->accomodation_details_more) && $hotel->accomodation_details_more != 'null' && $hotel->accomodation_details_more != NULL)
                {
                  
                  $accomodation_details_more=json_decode($hotel->accomodation_details_more);
                 
                  foreach($accomodation_details_more as $arr_hotel)
                  {
                      if(isset($arr_hotel->more_acc_check_in) && isset($arr_hotel->more_acc_check_out) && isset($arr_hotel->agent_Id) && isset($arr_hotel->more_hotel_supplier_id))
                      {
                      if($arr_hotel->more_acc_check_in >= $request_data->check_in && $arr_hotel->more_acc_check_in <= $request_data->check_out && $request_data->agent_Id == $hotel->agent_Id && $arr_hotel->more_hotel_supplier_id)
                      {
                        $aray_data[]=(object)[
                        
                        'invoice_no'=> $hotel->generate_id,
                        'invoice_id'=>$hotel->id,
                        'check_in'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_in)),
                        'check_out'=> date('d-m-Y', strtotime($arr_hotel->more_acc_check_out)),
                        'hotel_name'=> $arr_hotel->more_acc_hotel_name,
                        'hotel_city_name'=>  $arr_hotel->more_hotel_city,
                        'room_type'=> $arr_hotel->more_acc_type,
                        'reservation_no'=> $arr_hotel->more_room_reservation_no ?? '',
                        'quantity'=> $arr_hotel->more_acc_qty,
                        'supplier'=> $arr_hotel->more_hotel_supplier_id,
                        'lead_passenger_name'=> $hotel->f_name . ' ' . $hotel->middle_name,
                        'agent_id'=> $hotel->agent_Id,
                        'agent_name'=> $hotel->agent_Name,
                        'agent_company_name'=> $hotel->agent_Company_Name ?? $hotel->f_name . ' ' . $hotel->middle_name,
                        'booking_date'=> date('d-m-Y', strtotime($hotel->created_at)),
                        'booking_type'=> 'invoice',
                        'status'=> $hotel->confirm,
                        
                        ];  
                      }
                    
                      }
                      
                  }
                }
                
                 
               
                
                
              
                
            }
            //  print_r($aray_data);
            // die();
        return response()->json(['hotel_supplier'=>$aray_data]); 
         }
      }
    }
}